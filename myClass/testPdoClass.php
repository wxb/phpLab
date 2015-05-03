<?php
	/*
	 * filename: testPdoClass.php
	 * author  : wangxb
	 * create  : 2015_05_04 05:14:30
	 * update  : @update
	 */


// 引入数据库配置常量
require('./config.pdomysql.php');
// 引入PdoMySQLClass.php
require('./PdoMysql.Class.php');

// 测试
$pdomysql = new PdoMysql();
$sql = 'SELECT * FROM user';
//print_r($pdomysql->getAll($sql));
print_r($pdomysql->getRow($sql));
