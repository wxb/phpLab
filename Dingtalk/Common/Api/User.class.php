<?php
namespace Dingtalk\Common\Api;

use Dingtalk\Common\Util\Http as Http;

/**
 * 获取钉钉用户信息操作类
 * Class User
 * @package Dingtalk\Common\Api
 */
class User
{
    const DING_API_HOST = 'https://oapi.dingtalk.com';

    /**
     * 获取钉钉员工关键信息
     * @param $accessToken
     * @param $code
     * @return mixed
     */
    public static function getUserInfo($accessToken, $code)
    {
        $response = Http::get(self::DING_API_HOST."/user/getuserinfo",
            array("access_token" => $accessToken, "code" => $code));
        return $response;
    }

    /**
     * 获取钉钉员工详细信息
     * @param $accessToken
     * @param $userId
     * @return mixed
     */
    public static function getUserDetail($accessToken, $userId)
    {
        $response = Http::get(self::DING_API_HOST."/user/get",
            array("access_token" => $accessToken, "userid" => $userId));
        return $response;
    }


    public static function simplelist($accessToken,$deptId){
        $response = Http::get(self::DING_API_HOST."/user/simplelist",
            array("access_token" => $accessToken,"department_id"=>$deptId));
        return $response->userlist;

    }
}