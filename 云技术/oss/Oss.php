<?php
/**
 *  阿里云OSS云存储操作类 
 *   
 *   @author wangxb <@yunbix.com>
 *   @copyright 2015~ (c) @yunbix.com
 *   @Time: Thu 15 Oct 2015 04:40:22 PM CST
 *   关于endpoint的介绍见, endpoint就是OSS访问的域名
 *   http://docs.aliyun.com/?spm=5176.7114037.1996646101.11.XMMlZa&pos=6#/oss/product-documentation/domain-region
 */

require_once realpath(dirname(__FILE__).'/sdk.class.php');
require_once realpath(dirname(__FILE__).'/util/oss_util.class.php');


class App_Oss_Oss
{
    const endpoint = OSS_ENDPOINT;
    const accessKeyId = OSS_ACCESS_ID;
    const accesKeySecret = OSS_ACCESS_KEY;
    const bucket = OSS_TEST_BUCKET;

	/**
	 * 获取OSS实例
	 */
    public static function getOssClient() {
        $oss = new ALIOSS(self::accessKeyId, self::accesKeySecret, self::endpoint);
        return $oss;
    }

	/**
	 * 获取bucket名，
	 * 这里的名字是config.inc.php文件中配置的名字
	 */
    public static function getBucketName() {
        return self::bucket;
    }

	/**
	 * 获取bucket列表
	 * 列出用户所有的Bucket
	 * @return array 存在的所有 bucket 列表
	 */
	public static function getBucketList(){
        $oss = self::getOssClient();
		$options = null;
		$res = $oss->list_bucket($options); 
		$list = array();
		if ($res->isOk()){
			if(!$body = simplexml_load_string($res->body)){
				throw new Exception('bucket list 的xml对象转换成数组失败!');
			}
			$arr = json_decode(json_encode($body), true); // 以数组形式返回
			!empty($arr['Buckets']) && ($list = $arr['Buckets']['Bucket']); 
		}
		return $list;
	}

	/**
	 * 创建bucket
	 * @param string $bucket 需要符合bucket命名规范
	 * @param string $acl ACL权限：private,public-read,public-read-write, 默认：public-read
	 *		  ALIOSS::OSS_ACL_TYPE_PRIVATE，
	 *		  ALIOSS::OSS_ACL_TYPE_PUBLIC_READ，
	 *		  ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE
	 * @return boolean 是否创建成功
	 */
    public static function createBucket($bucket='', $acl='') {
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
        empty($acl) && $acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
        $res = $oss->create_bucket($bucket, $acl);
		return (200 == $res->status) ? true : false;
    }

	/**
	 * 删除bucket
	 * @param string $bucket 需要符合bucket命名规范, 默认删除配置config.inc.php文件中的bucket
	 * @return Boolean
	 */
    public static function deleteBucket($bucket='') {
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		$options = null;
		$res = $oss->delete_bucket($bucket,$options);
		return (204 == $res->status) ? true : false;
    }

	/**
	 *  获取bucket acl
	 * @param string $bucket 需要符合bucket命名规范, 默认删除配置config.inc.php文件中的bucket
	 * @return string bucket的acl权限:private,public-read,public-read-write
	 */   
	public static function getBucketAcl($bucket=''){
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		$res = $oss->get_bucket_acl($bucket);
		$ret = '';
		if ($res->isOK()) {
			$xml = new SimpleXMLElement($res->body);
			$ret = $xml->AccessControlList->Grant;
		}
		return strval($ret);
	}

	/**
	 *  设置bucket acl
	 * @param string $bucket 需要符合bucket命名规范, 默认删除配置config.inc.php文件中的bucket
	 * @param string $acl ACL权限：private,public-read,public-read-write, 默认：public-read
	 *		  ALIOSS::OSS_ACL_TYPE_PRIVATE，
	 *		  ALIOSS::OSS_ACL_TYPE_PUBLIC_READ，
	 *		  ALIOSS::OSS_ACL_TYPE_PUBLIC_READ_WRITE
	 * @return boolean
	 */   
	public static function setBucketAcl($bucket='', $acl=''){
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
        empty($acl) && $acl = ALIOSS::OSS_ACL_TYPE_PUBLIC_READ;
		$res = $oss->set_bucket_acl($bucket, $acl);
		return boolval($res->isOk());
	}

	/**
	 * 上传文件
	 * 创建一个object，即上传文件
	 * @param string $object 上传文件到云端地址(当目录不存在时，会新创建目录)，例：'baili/avatar/xxx.png'
	 * @param string $filePath 要上传文件路径
	 * @param string $bucket 上传到那个bucket中
	 * @param array $options 上传参数
	 * @return Boolean
	 */
	public static function createObject($object, $filePath, $bucket='', $options=array()){
		if(empty($object) || empty($filePath)){
			return false;
		}
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		$res = $oss->upload_file_by_file($bucket, $object, $filePath, $options);
		return boolval($res->isOk());
	}

	/**
	 * 创建模拟文件夹
	 * OSS服务是没有文件夹这个概念的，所有元素都是以Object来存储。但给用户提供了创建模拟文件夹的方式
	 */
	public static function createDir($dir, $bucket=''){
		if(empty($dir)){
			return false;
		}
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		$object = $dir;
		$res = $oss->create_object_dir($bucket, $object);
		return boolval($res->isOk());
	}

	/**
	 * 删除object
	 */
	public static function deleteObject($object, $bucket=''){
		if(empty($object)){
			return false;
		}
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		if(is_array($object)){ //批量删除object
			$objects = array($object);   
			$options = array();
			$res = $oss->delete_objects($bucket, $objects, $options);
		}else{//删除object
			$res = $oss->delete_object($bucket, $object);
		}
		return boolval($res->isOk());
	}

	/**
	 * 判断object是否存在
	 * @param string $object
	 * @param string $bucket
	 * @return boolean
	 */	
	public static function isObjectExists($object, $bucket=''){
		$ret = false;
		if(empty($object)){
			return false;
		}
        $oss = self::getOssClient();
        empty($bucket) && $bucket = self::getBucketName();
		$res = $oss->is_object_exist($bucket, $object);
		if ($res->status === 200) {
			$ret = true;
		}
		return $ret;	
	}
}
