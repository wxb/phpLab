<?php

/**
 * implode ( string $glue , array $pieces ) : string
 * implode ( array $pieces ) : string
 * 
 * 1. 用 glue 将一维数组的值连接为一个字符串。
 * 2. 因为历史原因，implode() 可以接收两种参数顺序，但是 explode() 不行。
 * 
 */

$arr = [
    'com',
    'org',
    'net',
    'tt'
];

// 拼接成字符串
var_dump(implode($arr));


$array = array('lastname', 'email', 'phone');
$comma_separated = implode(",", $array);

echo $comma_separated; // lastname,email,phone

// Empty string when using an empty array:
var_dump(implode('hello', array())); // string(0) ""