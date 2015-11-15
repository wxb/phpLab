<?php
// 通过参数形式连接数据库
try{
	$dsn = 'mysql:host=localhost;dbname=pdotest';
	$name = 'root';
	$passwd = 'root';
	$pdo = new PDO($dsn, $name, $passwd);
	var_dump($pdo);
}catch(PDOException $e){
	echo $e->getMessage();
}



