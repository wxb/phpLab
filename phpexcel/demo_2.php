<?php
// 引入PHPExcel类
require('./Classes/PHPExcel.php');

// 实例化一个PHPExcel类对象，相对于我们在桌面上新建了一个excel表格文件
$objPhpExcel = new PHPExcel();
// 获取当前活动的sheet操作对象
$objSheet = $objPhpExcel->getActiveSheet();
// 设置当前活动sheet的名称
$objSheet->setTitle('demo1');
/* 使用fromArray($arr)方法填充数据
 * 使用fromArray()方法，对传入的数组格式是有要求的
 * 二维数组中的每一个数组代表对应excel一行数据
 */
$dataArr = array(
	array("姓名", "性别", "成绩"),
	array('刘星', '男', '50'),
	array('小米', '女', '90')
);
$objSheet->fromArray($dataArr); 
// 调用这个PHPExcel_IOFactory类中的静态方法createWriter生成指定格式的excel文件
$objWrite = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
// 保存文件	
$objWrite->save('./demo_2.xlsx');


