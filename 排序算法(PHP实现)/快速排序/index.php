<?php
/**
 * Created by PhpStorm.
 * File       : index.php
 * Author     : wangxb
 * Date       : 2017-04-07 13:33
 * Description: 快速排序
 */

/**
 * @Function    quickSort
 * @Author      wangxb
 * @Description 快速排序
 * @param $arr
 * @param $left
 * @param $right
 * @param string $type
 */
function quickSort(&$arr, $left, $right, $type='ASC'){
    if($left >= $right) return ;

    $pivotKey = $left;
    $pivotVal = $arr[$left];

    $low = $left;
    $hight = $right;
    while($low < $hight){
        if($type=='ASC'){
            while (($low < $hight) && ($arr[$hight] >= $pivotVal))
                $hight--;
            while (($low < $hight) && ($arr[$low] <= $pivotVal))
                $low++;
        }
        if('DESC' == $type){
            while (($low < $hight) && ($arr[$hight] <= $pivotVal))
                $hight--;
            while (($low < $hight) && ($arr[$low] >= $pivotVal))
                $low++;
        }
        $temp = $arr[$hight];
        $arr[$hight] = $arr[$low];
        $arr[$low] = $temp;
    }

    $temp = $arr[$low];
    $arr[$low] = $arr[$pivotKey];
    $arr[$pivotKey] = $temp;

    quickSort($arr, $left, $low-1, $type);
    quickSort($arr, $low+1, $right, $type);
}

$arr = [3, 4, 2, 6, 1, 0, 9];
quickSort($arr, 0, count($arr)-1, 'DESC');
print_r($arr);