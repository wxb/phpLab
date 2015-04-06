<?php
/*
 * 使用exec方法执行插入语句
 */
// 通过参数形式连接数据库
try{
	$dsn = 'mysql:host=localhost;dbname=pdotest';
	$name = 'root';
	$passwd = 'root';
	$pdo = new PDO($dsn, $name, $passwd);
	//$sql = "SELECT * FROM user WHERE id=12";
	$sql = "SELECT * FROM user";
	// query 方法执行将返回一个PDOStatment对象
	$stmt = $pdo->query($sql);
	if(!$pdo && !$stmt){
		var_dump($pdo->errorInof());
	}
	// 对于query返回的PDOStatment对象，我们同样可是使用foreach方法来遍历数据其中的数据
	foreach($stmt as $row){
		var_dump($row);
		echo '<hr>';
	}

	// 这里注意：虽然手册上说query返回的是一个PDOStatment对象，但是下面这里使用PDOStatment对象方法时反悔了false，有待以后学习理解
	echo '-------------------<br>';
	var_dump($stmt->fetch());	
	echo '-------------------<br>';

	// prepare方法是为execute准备一个statmentObj
	$sth  =  $pdo -> prepare ( "SELECT name, id FROM user" );
	$sth -> execute ();
	echo '++++++++++++++++++++++++';
	var_dump($sth->fetch());
	echo '++++++++++++++++++++++++';
	var_dump($stmt);
	// 现在运行完成，在此关闭连接
	$pdo = null;
}catch(PDOException $e){
	echo $e->getMessage();
}



