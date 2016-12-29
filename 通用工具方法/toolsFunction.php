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


    /**
     * 检验浮点数的小数位长度是否符合要求
     * @param $num 被检查数
     * @param $limit 小数点位数，例如：2，返回：3-3.00、0.1-0.10、5.011-false
     * @return bool|string 失败-false，成功返回规范的浮点数
     * @author wangxb
     * @date 2015-11-06
     */
    function checkFloat($num, $limit){
        if(is_numeric($num)){
            $arr = explode('.', $num);
            if(1 == count($arr)){
                $ret = sprintf('%01.'.(int)$limit.'f', $num);
            }elseif((2 == count($arr) && isset($arr[1]) && ($limit >= strlen($arr[1])))){
                $ret = sprintf('%01.'.(int)$limit.'f', $num);
            }else{
                $ret = false;
            }
        }else{
            $ret = false;
        }
        return $ret;
    }

    function day($start, $end, $delay=0){
        $curr  = time();
        $sdiff = $ediff = 0;
        $start = strtotime(date('Y-m-d 00:00:00',$start));  // 进度单位是:天, 这里对精确的时间戳换算成对应日期的一天开始时间戳
        $end = strtotime(date('Y-m-d 23:59:59',$end));      // 同上
        $curr > (int)$start && $sdiff = $curr > $end   ? ($end-(int)$start)/3600/24 : ($curr-(int)$start)/3600/24;
        (int)$end > $curr   && $ediff = $curr < $start ? ((int)$end-$start)/3600/24+$delay : ((int)$end-$curr)/3600/24+$delay;
        return [ceil($sdiff), floor($ediff)];
    }

    /**
     * 计算某个时间点之后几天或几月或几年的时间点
     * @param  int    $time 计算时间
     * @param  int    $num  制后量
     * @param  string $type 制后量类型(d-天, m-月, Y-年)，默认‘d’
     * @param  int    $scope 精确范围，0-某段日期之后当前时间，1 - 某段日期后那天结束时间
     * @return int    unix 时间戳
     * @author: wangxb
     * @date  : 2015-12-25
     * @update:
     */
    function behind($time, $num, $type='d', $scope=0){
        if(empty($time) || empty($num) || !in_array($type, array('d', 'm', 'Y')) || !in_array($scope, array(0, 1))){
            return false;
        }
        $ret = time();
        switch($type){
        case 'd': // 日
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time)+$num, date('Y', $time)));
            (1 == $scope) && ($ret = mktime(23, 59, 59, date('n', $time), date('j', $time)+$num, date('Y', $time)));
            break;
        case 'm': // 月
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time)+$num, date('j', $time), date('Y', $time)));
            (1 == $scope) && ($ret = mktime(23, 59, 59, date('n', $time)+$num, date('j', $time), date('Y', $time)));
            break;
        case 'Y': // 年
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time), date('Y', $time)+$num));
            (1 == $scope) && ($ret = mktime(23, 59, 59, date('n', $time), date('j', $time), date('Y', $time)+$num));
            break;
        default: // 默认日
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time)+$num, date('Y', $time)));
            (1 == $scope) && ($ret = mktime(23, 59, 59, date('n', $time), date('j', $time)+$num, date('Y', $time)));
        }
        return $ret;
    }

    /**
     * 计算某个时间前几天或几月或几年的时间点
     * @param  int    $time 计算时间
     * @param  int    $num  提前量
     * @param  string $type 提前量类型(d-天, m-月, Y-年)，默认‘d’
     * @param  int    $scope 精确范围，0-某段日期之前当前时间，-1 - 某段日期前那天开始时间
     * @return int    unix 时间戳
     * @author: wangxb
     * @date  : 2015-12-25
     * @update:
     */
    function behead($time, $num, $type='d', $scope=0){
        if(empty($time) || empty($num) || !in_array($type, array('d', 'm', 'Y')) || !in_array($scope, array(0, -1))){
            return false;
        }
        $ret = time();
        switch($type){
        case 'd': // 日
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time)-$num, date('Y', $time)));
            (-1 == $scope) && ($ret = mktime(0, 0, 0, date('n', $time), date('j', $time)-$num, date('Y', $time)));
            break;
        case 'm': // 月
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time)-$num, date('j', $time), date('Y', $time)));
            (-1 == $scope) && ($ret = mktime(0, 0, 0, date('n', $time)-$num, date('j', $time), date('Y', $time)));
            break;
        case 'Y': // 年
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time), date('Y', $time)-$num));
            (-1 == $scope) && ($ret = mktime(0, 0, 0, date('n', $time), date('j', $time), date('Y', $time)-$num));
            break;
        default: // 默认日
            (0 == $scope) && ($ret = mktime(date('H', $time), date('i', $time), date('s', $time), date('n', $time), date('j', $time)-$num, date('Y', $time)));
            (-1 == $scope) && ($ret = mktime(0, 0, 0, date('n', $time), date('j', $time)-$num, date('Y', $time)));
        }
        return $ret;
    }

    /**
     * 获取今年或明年某个月的时间戳
    * @param  int    $year 年
    * @param  int    $month  月

    * @author: wangxb
    * @date  : 2015-12-25
     * @update:
     */
    function launch($year, $month){
        if($year=='今年'){
            $nowYear = date('Y',time());
        }else if($year=='明年'){
            $nowYear = date("Y",strtotime("+1 year"));
        }
         switch($month){
            case '1月':
                $nowMonth=1;
                break;
            case '2月':
                $nowMonth=2;
                break;
            case '3月':
                $nowMonth=3;
                break;
            case '4月':
                $nowMonth=4;
                break;
            case '5月':
                $nowMonth=5;
                break;
            case '6月':
                $nowMonth=6;
                break;
            case '7月':
                $nowMonth=7;
                break;
            case '8月':
                $nowMonth=8;
                break;
            case '9月':
                $nowMonth=9;
                break;
            case '10月':
                $nowMonth=10;
                break;
            case '11月':
                $nowMonth=11;
                break;
            case '12月':
                $nowMonth=12;
                break;
        }
        $data=$nowYear.'-'.$nowMonth;
        $showTime = strtotime($data);
        return $showTime;
    }
