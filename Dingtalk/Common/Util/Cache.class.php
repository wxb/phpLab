<?php

namespace Dingtalk\Common\Util;

use \Common\Lib\MemcachedTool as MemcachedTool;

/**
 * 钉钉缓存信息 封装类
 * Class Cache
 * @package Dingtalk\Common\Util
 */
class Cache
{
    public static function setSuiteTicket($ticket)
    {
        $memcache = self::getMemcached();
        $memcache->write("suite_ticket", $ticket);
    }
    
    public static function getSuiteTicket()
    {
        $memcache = self::getMemcached();
        return $memcache->read("suite_ticket");
    }
    
    public static function setJsTicket($ticket)
    {
        $memcache = self::getMemcached();
        $memcache->write("js_ticket", $ticket, 7000); // js ticket有效期为7200秒，这里设置为7000秒
    }
    
    public static function getJsTicket()
    {
        $memcache = self::getMemcached();
        return $memcache->read("js_ticket");
    }
    
    public static function setSuiteAccessToken($accessToken)
    {
        $memcache = self::getMemcached();
        $memcache->write("suite_access_token", $accessToken, 7000); // suite access token有效期为7200秒，这里设置为7000秒
    }
    
    public static function getSuiteAccessToken()
    {
        $memcache = self::getMemcached();
        return $memcache->read("suite_access_token");
    }
    
    public static function setCorpAccessToken($accessToken)
    {
        $memcache = self::getMemcached();
        $memcache->write("corp_access_token", $accessToken, 7000); // corp access token有效期为7200秒，这里设置为7000秒
    }
    
    public static function getCorpAccessToken()
    {
        $memcache = self::getMemcached();
        return $memcache->read("corp_access_token");
    }

    public static function setIsvCorpAccessToken($accessToken)
    {
        $memcache = self::getMemcached();
        $memcache->write("isv_corp_access_token", $accessToken, 7000); // corp access token有效期为7200秒，这里设置为7000秒
    }

    public static function getIsvCorpAccessToken()
    {
        $memcache = self::getMemcached();
        return $memcache->read("isv_corp_access_token");
    }

    public static function setTmpAuthCode($tmpAuthCode){
        $memcache = self::getMemcached();
        $memcache->write("tmp_auth_code", $tmpAuthCode);
    }

    public static function getTmpAuthCode(){
        $memcache = self::getMemcached();
        $memcache->read("tmp_auth_code");
    }

    public static function setPermanentAuthCodeInfo($code)
    {
        $memcache = self::getMemcached();
        $memcache->write("permanent_auth_code_info", $code);
    }
    
    public static function getPermanentAuthCodeInfo()
    {
        $memcache = self::getMemcached();
        return $memcache->read("permanent_auth_code_info");
    }
    
    
    private static function getMemcached()
    {
        if (class_exists("Memcached")) {
            $memcached = new MemcachedTool();
            return $memcached;
        }
        return new FileCache;
    }
    
    public static function get($key)
    {
        return self::getMemcached()->read($key);
    }
    
    public static function set($key, $value)
    {
        self::getMemcached()->write($key, $value);
    }
}
