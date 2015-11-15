<?php
// 通过Uri形式链接数据库
try{
	$dsn = 'uri:/var/www/html/php-code/pdo/pdo_uri_conn.txt';
	$name = 'root';
	$passwd = 'root';
	$pdo = new PDO($dsn, $name, $passwd);
	var_dump($pdo);
}catch(PDOException $e){
	echo $e->getMessage();
}



