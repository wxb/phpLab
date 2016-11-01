<?php
/**
 * 通用方法
 * Created by PhpStorm.
 * User: wangxb
 * Date: 16-10-27
 * Time: 下午2:48
 */


/**
 * 日期转时间戳
 * @param $flag: yesterday|today|lastweek|week|lastmonth|month
 * @return array
 */
function convertToUnixTime($flag)
{
    $ret = array();
    switch($flag){
        case 'yesterday':  // 昨天
            $ret = array(
                strtotime("yesterday"),
                strtotime("today")
            );
            break;
        case 'lastweek':  // 上周
            $ret = array(
                mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')),
                mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')),
            );
            break;
        case 'week':      // 本周
            $ret = array(
                mktime(0,0,0,date('m'),date('d')-date('w')+1,date('Y')),
                mktime(0,0,0,date('m'),date('d')-date('w')+1+7,date('Y')),
            );
            break;
        case 'lastmonth': // 上月
            $ret = array(
                mktime(0,0,0,date('m')-1,1,date('Y')),
                mktime(0,0,0,date('m'),1,date('Y')),
            );
            break;
        case 'month':     // 本月
            $ret = array(
                mktime(0,0,0,date('m'),1,date('Y')),
                mktime(0,0,0,date('m'),date('t')+1,date('Y')),
            );
            break;
        case 'today':
        default:          // 今日
            $ret = array(strtotime("today"), strtotime("tomorrow"));
    }
    return $ret;
}
