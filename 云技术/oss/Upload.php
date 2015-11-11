<?php

/**
 * 头像上传
 * @author wangxb <@yunbix.com>
 * @copyright 2015~ (c) @yunbix.com
 * @Time: Thu 15 Oct 2015 11:15:10 AM CST
 */

class Action_Upload extends App_Action
{
	const OSS_PATH = 'baili/avatar/'; // 上传到bucket的目录
	const OSS_URL = 'http://xbmarks.oss-cn-beijing.aliyuncs.com/'; // 阿里云bucket地址

	private $loginInfo;
	private $userInfo;

	public function __prepare(){
		$this->setView(Blue_Action::VIEW_JSON);
		$this->loginInfo = $this->getLogined();
		$this->userInfo = $this->loginInfo['user_info'];
	}

	public function __execute(){
		$ret = array('status'=>1, 'ossFileUrl'=>'');
		if(isset($_FILES['avatar']) && ($_FILES['avatar']['error'] == 0)){
			$up = $this->__uploadFile($_FILES['avatar']['tmp_name']);
			if(false !== $up){
				$commitData = array(
					'users_id'=>$this->userInfo['users_id'], 
					'portrait'=>self::OSS_URL.$up
				);
				Blue_Commit::call('Users_Edit', $commitData); // 更新数据库头像地址
				$ret = array('status'=>0, 'ossFileUrl'=>self::OSS_URL.$up);
			}
		}
		return $ret;
	}

	public function __complete(){
	}

	/**
	 * 图片上传方法
	 * @param string $filePath 上传文件路径
	 * @return 成功：返回OSS文件目录；失败：返回false：
	 */
	private function __uploadFile($filePath){
		if(file_exists($filePath)){
			$upFileName = md5(file_get_contents($filePath));
			$fileExt = 'jpg'; // 文件后缀
			$object = self::OSS_PATH.$upFileName;
			!empty($fileExt) && $object .= '.'.$fileExt;
			$flag = App_Oss_Oss::createObject($object, $filePath);
			(true === $flag) && ($flag = $object);
		}else{
			$flag = false;
		}
		return $flag;
	}
}
