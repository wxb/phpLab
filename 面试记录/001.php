<?php


function handle($str){
    $arr = str_split($str, 1);
    $map = [
        '[' => ']',
        '(' => ')',
        '{' => '}'
    ];

    if(count($arr) % 2 != 0){
        return false;
    }
    if(!array_key_exists($arr[0], $map) || !in_array($arr[count($arr)-1], $map)){
        return false;
    }

    $temp = [];
    foreach($arr as $v){
        if(array_key_exists($v, $map)){
            $temp[] = $v;
        }
        if(in_array($v, $map) && $map[$temp[count($temp)-1]] == $v){
            array_pop($temp);
        }
    }

    return empty($temp) ? true : false;
}


$str = "()()[[]]{}{}";
var_dump(handle($str));