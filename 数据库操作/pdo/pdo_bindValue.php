<?php
	/*
	 * filename: pdo_bindValue.php
	 * author  : wangxb
	 * create  : 2015_04_14 02:02:04
	 * update  : @update
	 */


try{
	$dns = "mysql:host=localhost;dbname=pdotest";
	$pdo = new PDO($dns, 'root', 'root');
	// 使用 ？ 占位符方式
	$sql = "INSERT INTO user(name, passwd, email) VALUES(?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	// bindValue()和bindParam()方法的区别就是，第二个参数可以是一个值，也可以是一个变量
	$passwd = '123';
	$stmt->bindValue(1, 'wangxb6');
	$stmt->bindValue(2, $passwd);
	$stmt->bindValue(3, 'wangxb6@qq.com');
	$stmt->execute();
	echo $stmt->rowCount();
}catch(PDOException $e){
	$e->getMessage();
}
