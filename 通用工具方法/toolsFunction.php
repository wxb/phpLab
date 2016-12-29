<?php
/**
 * 通用基础工具方法
 * @author: wangxb
 */


/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function get_randomstr($lenth = 6) {
    return get_random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
* 产生随机字符串
*
* @param    int        $length  输出长度
* @param    string     $chars   可选的 ，默认为 0123456789
* @return   string     字符串
*/
function get_random($length, $chars = '0123456789') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 验证手机号是否正确
 * param INT $phone
 * date: 2015-7-14
 */
function validatePhone($phone) {
    if (!is_numeric($phone)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[\d]{9}$|^18[\d]{9}$#', $phone) ? true : false;
}



/**
 * url安全编码encode编码
 * date:2015-10-06
 */
function urlsafe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
}

/**
 * url安全编码encode解码
 * date:2015-10-06
 */
function urlsafe_b64decode($string) {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}

/**
 * @param $curlPost
 * @param $url
 * @return mixed
 * POSt
 */
function send_post($curlPost,$url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
    $return_str = curl_exec($curl);
    curl_close($curl);
    return $return_str;
}


/**
 * @param $xml
 * @return mixed
 *
 */
function xml_to_array($xml){
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
    if(preg_match_all($reg, $xml, $matches)){
        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
            $subxml= $matches[2][$i];
            $key = $matches[1][$i];
            if(preg_match( $reg, $subxml )){
                $arr[$key] = xml_to_array( $subxml );
            }else{
                $arr[$key] = $subxml;
            }
        }
    }
    return $arr;
}


/**
 * @param $xml
 * @return mixed
 * 发送短信
 */
function send_sms($mobile,$content){
    $target = C(SEND_MAIL_URL);
    $post_data = "mobile=".$mobile."&content=".rawurlencode($content);
    $ret = send_post($post_data, $target);
    $gets =  xml_to_array($ret);
    $issend = false;

    if($gets['SubmitResult']['code']==2){
        $issend = true;
    }else{
    }
    return $issend;
}


/**
 * @param $filename
 * @param $data
 * 导出 CSV
 */
function export_csv($filename,$data) {
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

/**
 * @param $flag
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
        case 'tomorrow':  // 明天
            $ret = array(
                strtotime("tomorrow"),
                mktime(0,0,0,date('m'),date('d')+2,date('Y')),
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
        case '7days':     // 七天内
            $ret = array(
                mktime(0,0,0,date('m'),date('d')-6,date('Y')),
                strtotime("tomorrow")
            );
            break;
        case 'today':
        default:          // 今日
            $ret = array(strtotime("today"), strtotime("tomorrow"));
    }
    return $ret;
}

function convertToFormatDate($flag, $format='Y-m-d H:i:s')
{
    $timeArr = convertToUnixTime($flag);
    $ret = array_map(function($v, $m){
        return date($m, $v);
    }, $timeArr, array($format, $format));
    return $ret;
}

/**
 * 取出数组中一组指定键的数据
 * @param $keyArr
 * @param $valArr
 * @return array
 */
function combineKeyValue($keyArr, $valArr)
{
    $ret = array();
    foreach($keyArr as $v){
        $ret[$v] = isset($valArr[$v]) ? $valArr[$v] : null;
    }
    return $ret;
}
