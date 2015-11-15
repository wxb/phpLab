<?php
	/*
	 * filename: pdo_quote.php
	 * author  : wangxb
	 * create  : 2015_04_13 00:29:26
	 * update  : @update
	 */

	$username = $_POST['username'];
	$password = $_POST['passwd'];
	try{
		$dsn = 'mysql:host=localhost;dbname=pdotest';
		$name = 'root';
		$passwd = 'root';
		$pdo = new PDO($dsn, $name, $passwd);
		// 这里通过调用pdo对象中的quote方法，去过滤字符串中的特殊字符，也就是添加转义符号
		$username = $pdo->quote($username);
		$password = $pdo->quote($password);
		$sql = "SELECT * FROM user WHERE name='$username' AND passwd=MD5('$password')";
		// 通过调用上面new的pdo对象的query方法，返回PDOStatment对象
		$stmt = $pdo->query($sql);
		// rowCount() 是PDOStatment 对象中一个方法，返回受上一个 SQL 语句影响的行数
		$resnums = $stmt->rowCount();
		echo $resnums;
		var_dump($stmt);
		
	}catch(PDOException $e){
		echo $e->getMessage();
	}
