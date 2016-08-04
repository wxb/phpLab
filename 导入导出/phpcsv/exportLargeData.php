<?php

set_time_limit(0);
//ini_set('', '256M');
// 连接、选择数据库
$link = mysql_connect('127.0.0.1', 'root', 'root')
    or die('Could not connect: ' . mysql_error());
mysql_select_db('boss', $link) or die('Could not select database');
mysql_query('set names utf8');
// 执行 SQL 查询
$sql = "SELECT * FROM `bs_customer` WHERE `id` > 0 AND `ctype` = 0";
$result = mysql_unbuffered_query($sql,$link) or die('Query failed: ' . mysql_error());
//$result = mysql_query($sql,$link) or die('Query failed: ' . mysql_error());
ob_clean();
// 以 HTML 打印查询结果
header("Content-Type:text/csv");
header("Content-Disposition:attachment;filename=wangxb.csv");
$fp = fopen('php://output', 'a');
$title = array(
    'seq' => '序号',
    'name' => '客户姓名',
    'phone' => '电话',
    'source' => '来源'
);
foreach($title as $k=>$v){
    $title[$k] = iconv('utf-8', 'gbk', $v);
}
fputcsv($fp, $title);
while($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    foreach($line as $k=>$v){
        $row[$k] = iconv('utf-8', 'gbk', $v);
    }
    //dump($row);  die;
    fputcsv($fp, $row);
}
exit();

// 释放结果集
mysql_free_result($result);
die;
