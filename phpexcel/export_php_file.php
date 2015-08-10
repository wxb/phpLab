<?php
// 引入PHPExcel类
require('./Classes/PHPExcel.php');

// 实例化一个PHPExcel类对象，相对于我们在桌面上新建了一个excel表格文件
$objPhpExcel = new PHPExcel();
// 获取当前活动的sheet操作对象
$objSheet = $objPhpExcel->getActiveSheet();
// 设置当前活动sheet的名称
$objSheet->setTitle('Baili中php文件目录');

$handle = fopen('./php_file.log', 'r');
while(!feof($handle)){
    $arr[] = fgets($handle, 9999);
}

foreach($arr as $k=>$v){
    $dataArr[] = array($v);
}


$objSheet->fromArray($dataArr); 
// 调用这个PHPExcel_IOFactory类中的静态方法createWriter生成指定格式的excel文件
$objWrite = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
// 保存文件	
$objWrite->save('./php_file.xlsx');


