<?php
/**
 * Created by PhpStorm.
 * File       : index.php
 * Author     : wangxb
 * Date       : 2017-04-06 16:35
 * Description: 冒泡排序PHP实现
 */


/**
 * @Function    bubbleSortOne
 * @Author      wangxb
 * @Description 冒泡排序
 * @param $arr  要排序的数组
 * @param string $type 升序降序: DESC-大到小; ASC-小到大(默认)
 * @return mixed
 */
function bubbleSortOne($arr, $type='ASC')
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
        print_r($arr);
    }
    return $arr;
}

/**
 * @Function    bubbleSortTwo
 * @Author
 * @Description
 * @param $arr
 * @param string $type
 * @return mixed
 */
function bubbleSortTwo($arr, $type='ASC')
{
    $len = count($arr);
    for($i=1; $i<$len; $i++){
        for($j=0; $j<$len-$i; $j++){
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
        print_r($arr);
    }
    return $arr;
}

/**
 * @Function    bubbleSortThree
 * @Author
 * @Description
 * @param $arr
 * @param string $type
 * @return mixed
 */
function bubbleSortThree($arr, $type='ASC')
{
    $len = count($arr);
    for($i=$len; $i>1; $i--){
        for($j=$len-1; $j>$len-$i; $j--){
            if(('ASC' == $type) && ($arr[$j]>$arr[$j-1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j-1];
                $arr[$j-1] = $tmp;
            }
            if(('DESC' == $type) && ($arr[$j]<$arr[$j-1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j-1];
                $arr[$j-1] = $tmp;
            }
        }
        print_r($arr);
    }
    return $arr;
}

/**
 * @Function    bubbleSortFour
 * @Author
 * @Description
 * @param $arr
 * @param string $type
 * @return mixed
 */
function bubbleSortFour($arr, $type='ASC')
{
    $len = count($arr);
    for($i=$len-1; $i>0; $i--){
        for($j=$len-1; $j>$len-$i-1; $j--){
            if(('ASC' == $type) && ($arr[$j]>$arr[$j-1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j-1];
                $arr[$j-1] = $tmp;
            }
            if(('DESC' == $type) && ($arr[$j]<$arr[$j-1])){
                $tmp = $arr[$j];
                $arr[$j]=$arr[$j-1];
                $arr[$j-1] = $tmp;
            }
        }
        print_r($arr);
    }
    return $arr;
}

$arr = [3, 4, 2, 6, 1, 0, 9];

$one = bubbleSortOne($arr, 'DESC');
$two = bubbleSortTwo($arr, 'ASC');
$three = bubbleSortThree($arr, 'DESC');
$four  = bubbleSortFour($arr, 'ASC');

print_r($one);
print_r($two);
print_r($three);
print_r($four);
