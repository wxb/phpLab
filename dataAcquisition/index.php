<?php

define('BASE_DIR', __DIR__);

require(BASE_DIR.'/loader.php');
spl_autoload_register('\\Load\\Loader::autoload');


class HttpRequest{
    private $httpClient;
    private $response;

    public function __construct(){
        //Create an instance
        $this->httpClient = new \Leaps\HttpClient\Adapter\Curl();
    }

    public function getResponseInfo($url, $id=''){
        if(empty($url)) throw new Exception('请输入请求URL地址');
        !empty($id) && ($id = '?id='.$id);
        $this->response = $this->httpClient->get($url.$id);
        if(!$this->response->isOk()) throw new Exception('请求失败');
        if('text/json' == $this->response->getContentType()){
            return json_decode($this->response->getContent(), true);
        }else{
            $result = $this->response->getContent();
        }
        return $result;
    }

    public function postResponseInfo($url, $data){
        if(empty($url)) throw new Exception('请输入请求URL地址');
        if(!is_array($data) || empty($data)) throw new Exception('请求数据必须为一维非空数组');
        $this->response = $this->httpClient->post($url, $data);
        if(!$this->response->isOk()) throw new Exception('请求失败');
        if('text/json' == $this->response->getContentType()){
            return json_decode($this->response->getContent(), true);
        }else{
            $result = $this->response->getContent();
        }
        return $result;
    }
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', 'root');
define('DB_NAME', 'caiji');
define('DB_PORT', '3306');
define('DB_TYPE', '');
define('DB_CHARSET', 'utf8');


class Mysql{

    private static $config;
    private static $link;
    public function __construct(){
        self::$config = array(
                            'hostname' => DB_HOST,
                            'username' => DB_USER,
                            'password' => DB_PWD,
                            'database' => DB_NAME,
                            'port'     => DB_PORT,
                            'dbtype'   => DB_TYPE,
                            'charset'  => DB_CHARSET,
                            'dsn'      => DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME
                        );
        self::$link = new mysqli(self::$config['hostname'], self::$config['username'], self::$config['password'], self::$config['database']) or die('数据库连接失败');
        self::$link->query('set names '.self::$config['charset']) or die('字符集可是设置错误');
    }

    public function savePersonId($id_arr){
        $sql = '';
        foreach($id_arr as $v){
            $sql .= "INSERT INTO id_person(id) VALUES('{$v}');";

        }
        self::$link->multi_query($sql) or die('数据添加失败');
    }

    public function savePersonInfo($arr){
        $keys = array_keys($arr);
        $val = array_map(function($v){return "'$v'";}, $arr);
        $k_str = implode(',', $keys);
        $v_str = implode(',', $val);
        $sql = '';
            $sql .= "INSERT INTO info_person(".$k_str.") VALUES(".$v_str.")";
        self::$link->multi_query($sql) or die('数据添加失败');
    }
}



$client = new HttpRequest();
//print_r($client->getResponseInfo('http://shixin.court.gov.cn/detail', '70122'));
//echo $client->getResponseInfo('http://shixin.court.gov.cn/personMore.do'); 
//$content = $client->postResponseInfo('http://shixin.court.gov.cn/unitMore.do', array('currentPage'=>1));

//$page_pattern = '/页 ([1-9][0-9]*)\/([1-9][0-9]*) 共([1-9][0-9]*)条/';
//preg_match($page_pattern, $content, $page_matches);


$pageInfo = getPageInfo($client);
$content = $pageInfo[0];
$page  = $pageInfo[1];
$total = $pageInfo[2];
$items = $pageInfo[3];
$pnums = 15;


if($items > 1743001){

    $total = ceil(($items-1743001)/$pnums);
    $nums = $items - 1743001;
    for($i=1; $i<=$total; $i++){
        $info = getPageInfo($client, $i);
        $f = ($nums - (($i - 1) * $pnums)) > $pnums ? $pnums : ($nums - ($i - 1)*$pnums); 
        $id_arr = getId($info[0]);
            //$mysql = new Mysql();
        file_put_contents('./person.txt', 'test,test,test');
        //foreach($id_arr as $v){
         //   $person_arr = $client->getResponseInfo('http://shixin.court.gov.cn/detail', $v);
            //$mysql->savePersonInfo($person_arr);
            //file_put_contents('./person.txt', implode(',', $person_arr));
        //}
    }
    /*
    $info = getPageInfo($client);
    $content = $info[0];//$client->postResponseInfo('http://shixin.court.gov.cn/unitMore.do', array('currentPage'=>1));
    $id_pattern = '/<a href="javascript:void\(0\);" class="iView" id="([\d]*)">查看<\/a>/';
    preg_match_all($id_pattern, $content, $id_matches); 
    $id_arr = $id_matches[1];
    print_r($id_arr);
     */

}

function getPageInfo($client, $page=1){
    $content = $client->postResponseInfo('http://shixin.court.gov.cn/personMore.do', array('currentPage'=>$page));

    $page_pattern = '/页 ([1-9][0-9]*)\/([1-9][0-9]*) 共([1-9][0-9]*)条/';
    preg_match($page_pattern, $content, $page_matches);
    $page_matches[0] = $content;
    return $page_matches;

}

function getId($content){
    $id_pattern = '/<a href="javascript:void\(0\);" class="iView" id="([\d]*)">查看<\/a>/';
    preg_match_all($id_pattern, $content, $id_matches); 
    $id_arr = $id_matches[1];
    return $id_arr;
}






