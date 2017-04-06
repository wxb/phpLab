<?php
/**
 * Created by PhpStorm.
 * File       : index.php
 * Author     : wangxb
 * Date       : 2017-04-06 16:35
 * Description: 冒泡排序PHP实现
 */


/**
 * @Function    bubbleSort
 * @Author      wangxb
 * @Description 冒泡排序
 * @param $arr  要排序的数组
 * @param string $type 升序降序: DESC-大到小; ASC-小到大(默认)
 * @return mixed
 */
function bubbleSort($arr, $type='ASC')
{
    $len = count($arr);
    for($i=0; $i<$len-1; $i++){
        for($j=0; $j<$len-1-$i; $j++){
            if(('ASC' == $type) && ($arr[$j]>$arr[$j+1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j+1];
                $arr[$j+1] = $tmp;
            }
            if(('DESC' == $type) && ($arr[$j]<$arr[$j+1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j+1];
                $arr[$j+1] = $tmp;
            }
        }
    }
    return $arr;
}

$arr = [3, 4, 2, 6, 1, 0, 9];
$new = bubbleSort($arr, 'DESC');

print_r($new);
