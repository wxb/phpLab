<?php
	/*
	 * filename: pdo_quote.php
	 * author  : wangxb
	 * create  : 2015_04_13 00:29:26
	 * update  : @update
	 */

	$username = 'wangxb';
	$password = '123';
	try{
		$dsn = 'mysql:host=localhost;dbname=pdotest';
		$name = 'root';
		$passwd = 'root';
		$pdo = new PDO($dsn, $name, $passwd);
		// 参数名形式的传递参数
		// 使用prepare预处理 防止sql注入
		$sql = "SELECT * FROM user WHERE name=:name AND passwd=MD5(:password)";
		// 通过调用上面new的pdo对象的prepare方法，返回PDOStatment对象
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':name'=>$username, ':password'=>$password));
		// rowCount() 是PDOStatment 对象中一个方法，返回受上一个 SQL 语句影响的行数
		$resnums = $stmt->rowCount();
		print_r($stmt->fetch(PDO::FETCH_ASSOC));
		echo $resnums;
		var_dump($stmt);
		echo '<hr/>';
		// 使用 ？特殊符号占位符，咱传入参数的时候需要按照次序传入一个相应的数组
		$sql_1 = "SELECT * FROM user WHERE name=? AND passwd=MD5(?)";
		$sth = $pdo->prepare($sql_1);
		$sth->execute(array($username, $password));
		// rowCount() 是PDOStatment 对象中一个方法，返回受上一个 SQL 语句影响的行数
		$rnums = $sth->rowCount();
		print_r($sth->fetch(PDO::FETCH_ASSOC));
		echo $rnums;
		
	}catch(PDOException $e){
		echo $e->getMessage();
	}
