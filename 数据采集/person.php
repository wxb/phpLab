<?php

define('BASE_DIR', __DIR__);

require(BASE_DIR.'/config.php');
require(BASE_DIR.'/mysql.php');
require(BASE_DIR.'/function.php');
require(BASE_DIR.'/loader.php');
require(BASE_DIR.'/HttpRequest.php');
spl_autoload_register('\\Load\\Loader::autoload');




$client = new HttpRequest();

$separate  = '|@@|';
$line_end = '|<>|';
$data_file = __DIR__.'/person.txt'; 
$person_detail_url = 'http://shixin.court.gov.cn/detail';
$person_page_url = 'http://shixin.court.gov.cn/personMore.do';

$pageInfo = getPageInfo($person_page_url, $client);
$content = $pageInfo[0];
$page  = $pageInfo[1];
$total = $pageInfo[2];
$items = $pageInfo[3];
$pnums = 15;

$mysql = new Mysql();
$count = $mysql->countPersonInfo();

if($items > $count){

    $total = ceil(($items-$count)/$pnums);
    $nums = $items - $count;

    file_exists($data_file) && file_put_contents($data_file, '');

    for($i=1; $i<=$total; $i++){
        $info = getPageInfo($person_page_url, $client, $i);
        $f = ($nums - (($i - 1) * $pnums)) > $pnums ? $pnums : ($nums - ($i - 1)*$pnums); 
        $id_arr = getId($info[0]);
        for($j=0; $j<$f; $j++){
            $person_arr = $client->getResponseInfo($person_detail_url, $id_arr[$j]);
            file_put_contents($data_file, implode($separate, $person_arr).$line_end, (FILE_APPEND | LOCK_EX)) or die('Error:数据保存失败');
        }
    }
}

$mysql->savePersonInfo($data_file, $separate, $line_end);
echo 'Success: 数据采集完成';








