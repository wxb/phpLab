<?php
//目录中相关的配置信息
//指定前端文件存放目录等

return array(
    // 添加数据库配置信息
    'DB_TYPE'=>'mysql',// 数据库类型
    'DB_HOST'=>'127.0.0.1',// 服务器地址
    'DB_NAME'=>'boss',// 数据库名
    'DB_USER'=>'root',// 用户名
    'DB_PWD'=>'root',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集

    //memcache
    'MEMCACHED_HOST' => '127.0.0.1',
    'MEMCACHED_PORT' => 11211,
    'MEMCACHED_SASL_USERNAME' => '',
    'MEMCACHED_SASL_PASSWORD' => '',

    'TMPL_TEMPLATE_SUFFIX' => '.html', //模板后缀



);
