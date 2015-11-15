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
	$sql = "
		INSERT INTO user (name, passwd, email) 
		VALUES('wangxb', MD5('123'), 'wangxb@mail.com'), 
			  ('wangxb1', MD5('123'), 'wangxb@mail.com'),
			  ('wangxb2', MD5('123'), 'wangxb@mail.com')
	";
	// exec 对于没有受影响的行数将返回0
	$res = $pdo->exec($sql);
	var_dump($res);
	// 返回最后插入行的ID或序列值
	echo "最后一天插入的信息ID：".$pdo->lastInsertId();
	// 现在运行完成，在此关闭连接
	$pdo = null;
}catch(PDOException $e){
	echo $e->getMessage();
}



