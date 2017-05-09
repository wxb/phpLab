<?php

    $serv = new Swoole\Server("127.0.0.1", 9501);

    $serv = set(array(
        'worker_num' => 8,
        'daemonize'  => true,
    ));
