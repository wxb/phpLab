```php
<?php
namespace Org\Util;

/**
 *------------------------------------------------------------------------------
 * @deprecated     CSV文件导出类
 *------------------------------------------------------------------------------
 * @class           CsvExport
 * @namespace       Org\Util
 * @author          xxx  <xxx@gmail.com>
 * @version         1.0.0
 * @copyright       xxx.com  Copyright (c) 2015
 * @date            2015.06.29
 *------------------------------------------------------------------------------
 */

class CsvExport {

    private $csv_data;
    private $keys;

    /*
    @param $filenName 是要输出的csv文件名
    @param $title     是表字段数组例如array('name'='sdeepwang')
    @param $data      是要导出表的数据这个数组是二维例如 array(array('id'=1,'name'='sdeepwang'))
    */
    function getCsv($fileName,$title,$data){
        $this->setTitle($title);
        $this->setData($data);
        header("Content-Type:text/csv");
        header("Content-Disposition:attachment;filename={$fileName}.csv");
        echo mb_convert_encoding($this->csv_data,"UTF-8","auto");
        exit;
    }

    //设置csv的标题
    function setTitle($title){
        $this->keys = array_keys($title);
        if(is_array($title) && $title != ""){
            foreach($title as $key=>$value){
                $title[$key] = str_replace(",",", ",$value);
            }
            $this->csv_data .= implode(",",$title) . "\n";
        }
    }

    //设置导出的csv数据
    function setData($data){
        if(is_array($data) && $data != ""){
            foreach($data  as $value){
                $line = "";
                foreach($this->keys as $key){
                    $line .= '"' . $value[$key] . '"' .",";
                }
                $line = substr($line,0,-1);
                $this->csv_data .= $line . "\n";
            }
        }
    }
}

```
