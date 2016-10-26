<?php
namespace Dingtalk\Common\Api;

use Dingtalk\Common\Util\Http;
use Dingtalk\Common\Util\Cache;

/**
 * 钉钉认证类
 * Class Auth
 * @package Dingtalk\Common\Api
 */
class Auth
{
    const DING_API_HOST = 'https://oapi.dingtalk.com';

    /**
     * 获取accessToken
     * @return mixed|null
     */
    public static function getAccessToken()
    {
        /**
         * 缓存accessToken。accessToken有效期为两小时，需要在失效前请求新的accessToken（注意：以下代码没有在失效前刷新缓存的accessToken）。
         */
        $accessToken = Cache::get('corp_access_token');
        if (!$accessToken) {
            $params = array('corpid' => C('DING_CORPID'), 'corpsecret' => C('DING_SECRET'));
            $response = Http::get(self::DING_API_HOST.'/gettoken', $params);
            $accessToken = $response['access_token'];
            Cache::set('corp_access_token', $accessToken);
        }
        return $accessToken;
    }
    
     /**
      * 缓存jsTicket。jsTicket有效期为两小时，需要在失效前请求新的jsTicket（注意：以下代码没有在失效前刷新缓存的jsTicket）。
      */
    public static function getTicket($accessToken)
    {
        $jsticket = Cache::getJsTicket('js_ticket');
        if (!$jsticket) {
            $params = array('type' => 'jsapi', 'access_token' => $accessToken);
            $response = Http::get(self::DING_API_HOST.'/get_jsapi_ticket', $params);
            $jsticket = $response['ticket'];
            Cache::setJsTicket($jsticket);
        }
        return $jsticket;
    }

    /**
     * @return string
     */
    function curPageURL()
    {
        $pageURL = 'http';

        if (array_key_exists('HTTPS',$_SERVER)&&$_SERVER["HTTPS"] == "on")
        {
            $pageURL .= "s";
        }
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80")
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }
        else
        {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * 获取jsApi校验的config参数
     * @return array
     * @throws \Exception
     */
    public static function getConfig()
    {
        $corpId = C('DING_CORPID');
        $agentId = C('DING_AGENTID');
        $nonceStr = self::randomStr();
        $timeStamp = time();
        $url = self::curPageURL();
        $corpAccessToken = self::getAccessToken();
        if (!$corpAccessToken) throw new \Exception('获取access_token失败！[钉钉BOSS微应用]');
        $ticket = self::getTicket($corpAccessToken);
        $signature = self::sign($ticket, $nonceStr, $timeStamp, $url);
        
        return array(
            'url' => $url,
            'nonceStr' => $nonceStr,
            'agentId' => $agentId,
            'timeStamp' => $timeStamp,
            'corpId' => $corpId,
            'signature' => $signature
        );
    }

    /**
     * 签名方法
     * @param $ticket
     * @param $nonceStr
     * @param $timeStamp
     * @param $url
     * @return string
     */
    public static function sign($ticket, $nonceStr, $timeStamp, $url)
    {
        $plain = 'jsapi_ticket=' . $ticket .
            '&noncestr=' . $nonceStr .
            '&timestamp=' . $timeStamp .
            '&url=' . $url;
        return sha1($plain);
    }

    /**
     * 生成随机字符串 方法
     * @param int $length
     * @param int $numeric
     * @return string
     */
    public static function randomStr($length = 6, $numeric = 0) {
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz-_@';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

}