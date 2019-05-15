<?php

/**
 * empty ( mixed $var ) : bool
 * 
 * 1. 当一个变量并不存在，或者它的值等同于FALSE，那么它会被认为不存在
 * 2. 本质上与 !isset($var) || $var == false 等价
 * 3. 属于php语言构造器而不是函数，不能被 可变函数 调用
 * 4. php 5.5.0 开始支持表达式了，而不仅仅是变量； 之前版本， empty只支持变量，其他入参会导致解析错误
 * 
 */

var_dump(empty($param));
var_dump(empty($param . ''));