<?php
	/*
	 * filename: testPdoClass.php
	 * author  : wangxb
	 * create  : 2015_05_04 05:14:30
	 * update  : @update
	 */


// 引入数据库配置常量
require('./config.pdomysql.php');
// 引入PDOMysql封装类文件 PdoMySQLClass.php
require('./PdoMysql.Class.php');

// 测试
$pdomysql = new PdoMysql();
$sql = 'SELECT * FROM user';

// 调用getAll() 方法 返回查询到的所有数据
//print_r($pdomysql->getAll($sql));

// 调用getRow() 方法 返回一条数据
//print_r($pdomysql->getRow($sql));


// 新增
//$insertSql = "INSERT INTO user(name, passwd, email) VALUES('wangxb7', '123456', '11111@qq.com')";
//echo $pdomysql->execute($insertSql);

// 更新
//$updateSql = "UPDATE user SET passwd='2222' WHERE name='wangxb7'";
//echo $pdomysql->execute($updateSql);

// 删除
//$delSql = "DELETE FROM user WHERE name='wangxb7'";
//echo $pdomysql->execute($delSql);

// 测试findById

//print_r($pdomysql->findById('user', 17));
print_r($pdomysql->findById('user', 17, array('name','email')));





