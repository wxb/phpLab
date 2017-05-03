<?php

    $redis = new \Redis();

    $redis->connect('127.0.0.1');
    $res = $redis->ping();
    $is = $redis->set('key', 'value');
    $val= $redis->get('key');
    var_dump($is, $val);
