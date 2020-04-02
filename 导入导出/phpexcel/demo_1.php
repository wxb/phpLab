<?php
// 引入PHPExcel类
require('./Classes/PHPExcel.php');

// 实例化一个PHPExcel类对象，相对于我们在桌面上新建了一个excel表格文件
$objPhpExcel = new PHPExcel();
// 获取当前活动的sheet操作对象
$objSheet = $objPhpExcel->getActiveSheet();
// 设置当前活动sheet的名称
$objSheet->setTitle('demo1');
// 给当前活动sheet填充数据，setCellValue() 方法接受两个参数，第一个是excel中每个单元格的位置坐标，第二个是填充的数据
$objSheet->setCellValue('A1', '姓名')->setCellValue('B1', '班级')->setCellValue('C1', '成绩');
$objSheet->setCellValue('A2', '张三')->setCellValue('B2', '5班')->setCellValue('C2', '98');
$objSheet->setCellValue('A3', '李四')->setCellValue('B3', '1班')->setCellValue('C3', '85');
// 调用这个PHPExcel_IOFactory类中的静态方法createWriter生成指定格式的excel文件
$objWrite = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
// 保存文件	
$objWrite->save('./demo_1.xlsx');


