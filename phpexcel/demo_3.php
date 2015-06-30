<?php

$dir = dirname(__FILE__);
require($dir.'/config.php');
require($dir.'/mysql.class.php');
require($dir.'/Classes/PHPExcel.php');

$db = new MysqlDb($localDB);
$pidArr = $db->queryPid();
// 实例化一个PHPExcel类对象，相对于我们在桌面上新建了一个excel表格文件
$objPhpExcel = new PHPExcel();
for($i=0; $i<count($pidArr); $i++){
	($i > 0) && $objPhpExcel->createSheet();
	$objPhpExcel->setActiveSheetIndex($i);
	$objSheet = $objPhpExcel->getActiveSheet();
	$objSheet->setTitle('pid-'.$pidArr[$i]);
	$res = $db->queryInfoByPid($pidArr[$i]);
	$objSheet->setCellValue('A1', 'id')
			->setCellValue('B1', 'title')
			->setCellValue('C1', 'sort')
			->setCellValue('D1', 'url')
			->setCellValue('E1', 'hide')
			->setCellValue('F1', 'tip')
			->setCellValue('G1', 'group')
			->setCellValue('H1', 'is_dev');
	$flag = 2;
	foreach($res as $val){
		$objSheet->setCellValue('A'.$flag, $val['id'])
			->setCellValue('B'.$flag, $val['title'])
			->setCellValue('C'.$flag, $val['sort'])
			->setCellValue('D'.$flag, $val['url'])
			->setCellValue('E'.$flag, $val['hide'])
			->setCellValue('F'.$flag, $val['tip'])
			->setCellValue('G'.$flag, $val['group'])
			->setCellValue('H'.$flag, $val['is_dev']);
		$flag++;
	}
}
browser_exprot('export_3.xlsx');
$objWrite = PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
// 保存文件	
//$objWrite->save('./demo_3.xlsx');


$objWrite->save('php://output');


function browser_exprot($fileName, $excelType='Excel2007'){
	if('Excel5' == excelType){
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
	}else{
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		
	}
	header('Content-Disposition: attachment;filename="'.$fileName.'"');
	header('Cache-Control: max-age=0');
}

