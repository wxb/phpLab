<?php
/*
 * 使用errorCode 和 errorInfo 两个方法显示错误信息
 */
try{
	$dsn = 'mysql:host=localhost;dbname=pdotest';
	$name = 'root';
	$passwd = 'root';
	$pdo = new PDO($dsn, $name, $passwd);
	// 我们这里插入的一个没有的表 user123 中
	$sql = "
		INSERT INTO user123 (name, passwd, email) 
		VALUES('wangxb', MD5('123'), 'wangxb@mail.com') 
	";
	// 
	$res = $pdo->exec($sql);
	if(false === $res){
		// errorCode 获取跟数据库句柄上一次操作相关的 SQLSTATE
		echo $pdo->errorCode();
		print "\n";
		// errorInfo 返回一个错误信息数据：
		// 0: SQLSTATE error code
		// 1: specific error code.
		// 2: specific error message
		print_r($pdo->errorInfo());
		return false;
	}
	var_dump($res);
	// 返回最后插入行的ID或序列值
	echo "最后一天插入的信息ID：".$pdo->lastInsertId();
	// 现在运行完成，在此关闭连接
	$pdo = null;
}catch(PDOException $e){
	echo $e->getMessage();
}



