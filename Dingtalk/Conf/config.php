<?php
//目录中相关的配置信息
//指定前端文件存放目录等

return array(
    // 添加数据库配置信息
    'DB_TYPE'=>'mysql',// 数据库类型
    'DB_HOST'=>'',// 服务器地址
    'DB_NAME'=>'',// 数据库名
    'DB_USER'=>'',// 用户名
    'DB_PWD'=>'',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集


    //默认错误跳转对应的模板文件
    'TMPL_ACTION_ERROR' => 'Common/dispatch_jump',
    //默认成功跳转对应的模板文件
    'TMPL_ACTION_SUCCESS' => 'Common/dispatch_jump',

	'DEFAULT_FILTER'        =>  'strip_tags,stripslashes,trim',


    // 钉钉 BOSS微应用
    "DING_CORPID"    => "",
    "DING_SECRET"    => "",
    "DING_AGENTID"   => "",    //必填，在创建微应用的时候会分配


);
