<?php
	/*
	 * filename: pdo_quote.php
	 * author  : wangxb
	 * create  : 2015_04_13 00:29:26
	 * update  : @update
	 */

	try{
		$dsn = 'mysql:host=localhost;dbname=pdotest';
		$name = 'root';
		$passwd = 'root';
		$pdo = new PDO($dsn, $name, $passwd);
		$sql = "INSERT INTO user(name, passwd, email) VALUES(:username, :password, :email)";
		$stmt = $pdo->prepare($sql);
		// 使用PDOStatment对象中的bindParam（）方法绑定变量到参数，注意bindParam中的第二个参数是：&$variable 必须传入变量
		$stmt->bindParam(':username', $username, PDO::PARAM_STR);
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':email', $email, PDO::PARAM_STR);
		$username = 'wangxb4';
		$password = '123';
		$email = 'wangxb4@gmail.com';
		$stmt->execute();
		// rowCount() 是PDOStatment 对象中一个方法，返回受上一个 SQL 语句影响的行数
		$resnums = $stmt->rowCount();
		print_r($stmt->fetch(PDO::FETCH_ASSOC));
		echo $resnums;
		var_dump($stmt);
		echo '<hr/>';
		// 使用 ？特殊符号占位符，然后使用bindParam方法绑定变量到特定位置
		$sql = "INSERT INTO user(name, passwd, email) VALUES(?, ?, ?)";
		$stmt = $pdo->prepare($sql);
		// 使用PDOStatment对象中的bindParam（）方法绑定变量到参数，注意bindParam中的第二个参数是：&$variable 必须传入变量
		// 使用 ？占位符时，bindParam方法第一个参数从 1 开始
		$stmt->bindParam(1, $username, PDO::PARAM_STR);
		$stmt->bindParam(2, $password, PDO::PARAM_STR);
		$stmt->bindParam(3, $email, PDO::PARAM_STR);
		$username = 'wangxb5';
		$password = '123';
		$email = 'wangxb5@gmail.com';
		$stmt->execute();
		$rnums = $sth->rowCount();
		print_r($sth->fetch(PDO::FETCH_ASSOC));
		echo $rnums;
		
	}catch(PDOException $e){
		echo $e->getMessage();
	}
