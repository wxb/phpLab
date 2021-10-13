<?php

require 'vendor/autoload.php';

use Carbon\Carbon;
use Carbon\CarbonImmutable;

$mutable = Carbon::now('Asia/Shanghai');
$immutable = CarbonImmutable::now('Asia/Shanghai');
$modifiedMutable = $mutable->add(1, 'day');
$modifiedImmutable = $immutable->add(1, 'day');

// 意味着$mutable和$modifiedMutable是同一个实例，两个变量都加了1天
var_dump($modifiedMutable === $mutable);
var_dump($mutable->isoFormat('dddd D'));
var_dump($modifiedMutable->isoFormat('dddd D'));

// $immutable仍然是当天且不能改变，$modifiedImmutable是一个新的实例
var_dump($modifiedImmutable === $immutable);
var_dump($immutable->isoFormat('dddd D'));
var_dump($modifiedImmutable->isoFormat('dddd D'));

var_dump($mutable->isMutable(), $mutable->isImmutable());
var_dump($immutable->isMutable(), $immutable->isImmutable());

$mutable = CarbonImmutable::now('Asia/Shanghai')->toMutable();
var_dump($mutable->isMutable(), $mutable->isImmutable());
$immutable = Carbon::now('Asia/Shanghai')->toImmutable();
var_dump($immutable->isMutable(), $immutable->isImmutable());