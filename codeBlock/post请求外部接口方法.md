
```php
/**
 * +----------------------------------------------------------------------
 * | post请求外部接口方法
 * +----------------------------------------------------------------------
 * @function requestInterface
 * @param string $reqUrl 请求接口的url
 * @param array $dataArr 发送给接口的数据
 * @param string $option 请求接口时设置头部信息
 * @return array 返回请求结果
 * @author   wangxb      <wxb0328@gmail.com>
 * @date 2015-07-02
 * +----------------------------------------------------------------------
 */
function requestApi($reqUrl, $dataArr=null, $option=null){
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
    return json_decode($result, true);

}
```
