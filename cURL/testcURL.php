<?php

/**
 * 发送HTTP请求
 */

function send_code($tel) {
                if(11 != strlen(trim($tel))) {
                        echo 1; exit();
                }
                //$code = $this->random(6);
                //Session::set('verified_code',$code);
                $recipient = '+81'.trim($tel);
                $text = 'hello! Verification code: ';//.$code;
               
                $url = 'https://api.sms-xxxxx.jp/xxx.php?username=******&password=*****&recipient=';//.$recipient.'&text='.$text;
                //$url = 'http://www.baidu.com';
                $ch = curl_init($url);
                
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
                // 注意，在类似于上面这张https 的URL时，要特别注意下面这一项相关的配置，具体使用见PHP手册
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);   
                $res = curl_exec($ch);
                // curl_getinfo — 获取一个cURL连接资源句柄的信息 ，就像下面注释中的输出一样
                $info = curl_getinfo($ch);
                curl_close($ch);
                print_r($info);
                echo $res; 

        }
//echo send_request('https://api.sms-xxxxx.jp/xxxxx.php','username=****&password=******&recipient=+******&text=hello! Verification code: 195522');
send_code('******');
/*
print_r($info);
echo $res; 
上面两项输出结果
Array
(
    [url] => https://api.sms-xxxxx.jp/xxxxx.php?username=*****&password=*****&recipient=+*****&text=hello! Verification code: 
    [content_type] => text/html; charset=utf-8
    [http_code] => 200  
    [header_size] => 391
    [request_size] => 165
    [filetime] => -1
    [ssl_verify_result] => 20
    [redirect_count] => 0
    [total_time] => 0.686
    [namelookup_time] => 0.062
    [connect_time] => 0.14
    [pretransfer_time] => 0.593
    [size_upload] => 0
    [size_download] => 1
    [speed_download] => 1
    [speed_upload] => 0
    [download_content_length] => -1
    [upload_content_length] => 0
    [starttransfer_time] => 0.686
    [redirect_time] => 0
    [certinfo] => Array
        (
        )

    [redirect_url] => 
)
2
 */
?>
