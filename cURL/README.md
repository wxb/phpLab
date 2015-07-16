###PHP中采用POST方式发送数据

**file_get_contents()方式**
```php
function request_api($reqUrl, $dataArr=null, $option=null){
    $opts = array('http' => array(
        'method' => 'POST'
    ));
    if(!empty($dataArr)){
        $postData = http_build_query($dataArr);
        $opts['http']['content'] = $postData;
    }
    if(!empty($option)){
        $opts['http']['header'] = $option;
    }
    $context  = stream_context_create($opts);
    if(!$context){
        throw new \Think\Exception('请求接口参数有误！');
    }
    $result = file_get_contents($reqUrl, false, $context);
    if(!$result){
        throw new \Think\Exception('请求接口失败！');
    }
    $result = json_decode($result, true);
    if(0 != json_last_error()){
        throw new \Think\Exception('请求接口返回json数据错误，json解码失败！');
    }
    return $result;
}

```

**cURL方式**

php中`cURL`全称是 `client Url library`。PHP支持的由Daniel Stenberg创建的libcurl库允许你与各种的服务器使用各种类型的协议进行连接和通讯。libcurl目前支持http、https、ftp、gopher、telnet、dict、file和ldap协议。libcurl同时也支持HTTPS认证、HTTP POST、HTTP PUT、 FTP 上传(这个也能通过PHP的FTP扩展完成)、HTTP 基于表单的上传、代理、cookies和用户名+密码的认证。    
`cURL`并不是默认编译到PHP扩展中，需要自行编译安装

```php
/**
 * +----------------------------------------------------------------------
 * | post请求外部接口方法
 * +----------------------------------------------------------------------
 * @function requestInterface
 * @param string $reqUrl 请求接口的url
 * @param array $dataArr 发送给接口的数据
 * @return array 返回请求结果
 * @author   wangxb      <wxb0328@gmail.com>
 * @date 2015-07-02
 * +----------------------------------------------------------------------
 */
function request_api($reqUrl, $dataArr=null){
    // 初始化一个cURL会话
    $ch = curl_init();
    // curl_setopt 设置一个cURL传输选项
    curl_setopt ($ch, CURLOPT_URL, $reqUrl); // CURLOPT_URL 访问API的URL地址，也可以在curl_init()函数中设置。
    curl_setopt ($ch, CURLOPT_POST, 1); // CURLOPT_POSTFIELDS 使用post发生的数据
    if($dataArr != ''){
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataArr));
    }
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); // CURLOPT_RETURNTRANSFER 将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_HEADER, false); // CURLOPT_HEADER 启用时会将头文件的信息作为数据流输出
    // 执行一个cURL会话
    $result = curl_exec($ch);
    // 关闭一个cURL会话
    curl_close($ch);
    if(!$result){
        throw new \Think\Exception('请求接口失败！');
    }
    $result = json_decode($result, true);
    if(0 != json_last_error()){
        throw new \Think\Exception('请求接口返回json数据错误，json解码失败！');
    }
    //print_r($result); die;
    return $result;
}
```

**参考实例**

```php
class Request{
 
    public static function post($url, $post_data = '', $timeout = 5){//curl
 
        $ch = curl_init();
 
        curl_setopt ($ch, CURLOPT_URL, $url);
 
        curl_setopt ($ch, CURLOPT_POST, 1);
 
        if($post_data != ''){
 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
 
        }
 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
 
        curl_setopt($ch, CURLOPT_HEADER, false);
 
        $file_contents = curl_exec($ch);
 
        curl_close($ch);
 
        return $file_contents;
 
    }
 
 
    public static function post2($url, $data){//file_get_content
 
         
 
        $postdata = http_build_query(
 
            $data
 
        );
 
         
 
        $opts = array('http' =>
 
                      array(
 
                          'method'  => 'POST',
 
                          'header'  => 'Content-type: application/x-www-form-urlencoded',
 
                          'content' => $postdata
 
                      )
 
        );
 
         
 
        $context = stream_context_create($opts);
 
 
        $result = file_get_contents($url, false, $context);
 
        return $result;
 
 
    }
 
 
    public static function post3($host,$path,$query,$others=''){//fsocket
 
 
        $post="POST $path HTTP/1.1\r\nHost: $host\r\n";
 
        $post.="Content-type: application/x-www-form-";
 
        $post.="urlencoded\r\n${others}";
 
        $post.="User-Agent: Mozilla 4.0\r\nContent-length: ";
 
        $post.=strlen($query)."\r\nConnection: close\r\n\r\n$query";
 
        $h=fsockopen($host,80);
 
        fwrite($h,$post);
 
        for($a=0,$r='';!$a;){
 
                $b=fread($h,8192);
 
                $r.=$b;
 
                $a=(($b=='')?1:0);
 
            }
 
        fclose($h);
 
        return $r;
 
    }
}
```
