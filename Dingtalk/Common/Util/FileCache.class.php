<?php
/**
 * Created by PhpStorm.
 * User: wangxb
 * Date: 16-10-18
 * Time: 下午2:57
 */

namespace Dingtalk\Common\Util;

/**
 * 文件缓存形式 工具类
 * Class FileCache
 * @package Dingtalk\Common\Util
 */
class FileCache
{
    function set($key, $value)
    {
        if($key&&$value){
            $data = json_decode($this->get_file(dirname(__FILE__) ."/filecache.temp"),true);
            $item = array();
            $item["$key"] = $value;

            $keyList = array('isv_corp_access_token','suite_access_token','js_ticket','corp_access_token');
            if(in_array($key,$keyList)){
                $item['expire_time'] = time() + 7000;
            }else{
                $item['expire_time'] = 0;
            }
            $item['create_time'] = time();
            $data["$key"] = $item;
            $this->set_file(dirname(__FILE__) ."/filecache.temp",json_encode($data));
        }
    }

    function get($key)
    {
        if($key){
            $data = json_decode($this->get_file(dirname(__FILE__) ."/filecache.temp"),true);
            if($data&&array_key_exists($key,$data)){
                $item = $data["$key"];
                if(!$item){
                    return false;
                }
                if($item['expire_time']>0&&$item['expire_time'] < time()){
                    return false;
                }

                return $item["$key"];
            }else{
                return false;
            }

        }
    }

    function get_file($filename) {
        if (!file_exists($filename)) {
            $fp = fopen($filename, "w");
            fwrite($fp, "<?php exit();?>" . '');
            fclose($fp);
            return false;
        }else{
            $content = trim(substr(file_get_contents($filename), 15));
        }
        return $content;
    }

    function set_file($filename, $content) {
        $fp = fopen($filename, "w");
        fwrite($fp, "<?php exit();?>" . $content);
        fclose($fp);
    }
}