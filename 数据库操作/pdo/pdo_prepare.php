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

	/*
	 * 使用prepare和execute方法形式
	 */
	$id1 = 12;
	$id2 = 13;
	// 利用占位符形式
	$sql = "SELECT * FROM user WHERE id=? OR id=?";
	$sth  =  $pdo -> prepare ($sql);
	$sth -> execute(array($id1, $id2));
	var_dump($sth->fetchAll(PDO::FETCH_ASSOC));
	
	echo '<hr>';
	// 使用参数形式
	$sql_1 = "SELECT id, name FROM user WHERE id= :id";
	$sth_1  =  $pdo -> prepare ($sql_1);
	$sth_1 -> execute(array('id'=>$id1));
	var_dump($sth_1->fetchAll(PDO::FETCH_ASSOC));

	echo '<hr>';
	// 使用bindParam方式
	$sql_2 = "SELECT * FROM user WHERE id=? OR id=?";
	$sth_2  =  $pdo -> prepare ($sql_2);
	$sth_2->bindParam(1, $id1, PDO::PARAM_INT);
	$sth_2->bindParam(2, $id2, PDO::PARAM_INT);
	$sth_2 -> execute();
	var_dump($sth_2->fetchAll(PDO::FETCH_ASSOC));

	// 现在运行完成，在此关闭连接
	$pdo = null;
}catch(PDOException $e){
	echo $e->getMessage();
}



