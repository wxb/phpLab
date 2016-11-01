<?php

namespace Dingtalk\Common\Util;

/**
 * http请求 工具类
 * Class Http
 * @package Dingtalk\Common\Util
 */
Class Http
{
    const TIME_OUT = 10;   // 超时时间

    public static function get($path, $params)
    {
        $url = self::joinParams($path, $params);
        $response = \Requests::get($url, array(), array('timeout'=>self::TIME_OUT));
        return self::checkResult($response);
    }

    public static function post($path, $params, $data)
    {
        $url = self::joinParams($path, $params);
        $response = \Requests::post($url, array(), $data, array('timeout'=>self::TIME_OUT));
        return self::checkResult($response);
    }

    private static function checkResult($response)
    {
        if(200 != $response->status_code) throw new \Exception('HTTP请求失败！[钉钉BOSS微应用]');
        $data = json_decode($response->body, true);
        if((0 != $data['errcode'])) throw new \Exception($data['errmsg']);
        return $data;
    }

    private static function joinParams($path, $params)
    {
        $url = $path;
        (count($params) > 0) && ($url = sprintf("%s?%s", $path, http_build_query($params)));
        return $url;
    }
}