<?php
/*
 * 使用exec方法建表
 */
// 通过参数形式连接数据库
try{
	$dsn = 'mysql:host=localhost;dbname=pdotest';
	$name = 'root';
	$passwd = 'root';
	$pdo = new PDO($dsn, $name, $passwd);
	$sql = <<<EOF
CREATE TABLE IF NOT EXISTS user(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(20) NOT NULL UNIQUE,
	passwd VARCHAR(32) NOT NULL,
	email VARCHAR(30) NOT NULL
);
EOF;
	// exec 对于没有受影响的行数将返回0
	$res = $pdo->exec($sql);
	var_dump($res);
	// 现在运行完成，在此关闭连接
	$pdo = null;
}catch(PDOException $e){
	echo $e->getMessage();
}



