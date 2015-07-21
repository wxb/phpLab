###清除缓冲区,避免乱码

经常会出现一些导出的Excel乱码或者说导出的Excel直接打不开，比较好的或者说是比较保险的做法就是，在我们输出Excel到浏览器时，
首先清除一下缓冲区     

**就像这样：**
```php
  ob_end_clean();//清除缓冲区,避免乱码
  // 调用这个PHPExcel_IOFactory类中的静态方法createWriter生成指定格式的excel文件
  $objWrite = PHPExcel_IOFactory::createWriter($objPHPExcel, $excelType);
  // 输出文件到浏览器
  $objWrite->save('php://output');
  exit;
```
**这是一种保险的做法，最好就是每次都加上这个函数，毕竟输出前清除一下缓冲区也不是什么坏事，但却可以避免一些莫名其妙的事情**    
完整代码：   
```php
/**
 * +----------------------------------------------------------------------
 * | Excel文件导出函数
 * +----------------------------------------------------------------------
 * @function export_excel
 * @param $titleArr sheet页面的标题数组
 * @param $dataArr  填充数据数组
 * @param $fileName 导出文件名
 * @param string $fileSuffix 文件后缀
 * @param string $excelType 导出excel版本
 * @author   wangxb      <wxb0328@gmail.com>
 * @date 2015-07-01
 * +----------------------------------------------------------------------
 */
function export_excel($titleArr, $dataArr, $fileName, $excelType='Excel2007'){
    import('Org.PHPExcel.PHPExcel');
    $objPHPExcel = new \PHPExcel();
    $objSheet = $objPHPExcel->getActiveSheet();
    $objSheet->getStyle('A1:'.PHPExcel_Cell::stringFromColumnIndex(count($titleArr)-1).(count($dataArr)+1))
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objSheet->setTitle($fileName);

    $dataArr = array_map(function($v){
        return array_values($v);
    }, $dataArr);
    // 将$titleArr数组放到$dataArr数组第一项中
    array_unshift($dataArr,$titleArr);
    $objSheet->fromArray($dataArr);
    for($i=0; $i<count($titleArr); $i++){
        $objSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($i))
            //->setAutoSize(true);
            ->setWidth(20);
    }
    if('Excel5' == $excelType){
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
    }else{
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
    ob_end_clean(); //清除缓冲区,避免乱码
    header('Content-Disposition: attachment;filename="'.$fileName.'.xlsx"');
    header('Cache-Control: max-age=0');
    // 调用这个PHPExcel_IOFactory类中的静态方法createWriter生成指定格式的excel文件
    $objWrite = PHPExcel_IOFactory::createWriter($objPHPExcel, $excelType);
    // 输出文件到浏览器
    $objWrite->save('php://output');
    exit;
}
```

