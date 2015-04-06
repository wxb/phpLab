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
	$sql = "SELECT * FROM user";
	echo "自动提交：".$pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
	echo '<br/>';
	echo 'PDO默认的错误处理模式：'.$pdo->getAttribute(PDO::ATTR_ERRMODE);
	
	echo '<br/>';
	//现在我们重设自动提交
	$pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
	echo "重设后的自动提交：".$pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
	
	// 现在运行完成，在此关闭连接
	$pdo = null;
	
	// 我们也可以在new一个PDO对象时，利用传递参数中的第四个参数（可省略）来设置我们此次连接数据库时的各项参数
	// (我们可以在这里找到所有的设置项：http://php.net/manual/zh/pdo.constants.php)
	// 就像下面这样
	$dsn = 'mysql:host=localhost;dbname=pdotest';
	$name = 'root';
	$passwd = 'root';
	$option = array(
					PDO::ATTR_AUTOCOMMIT => O,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION		
				);
	$pdo = new PDO($dsn, $name, $passwd, $option);
	echo '<hr/>';
	echo '使用option设置后';
	echo '<br/>';
	echo "自动提交：".$pdo->getAttribute(PDO::ATTR_AUTOCOMMIT);
	echo '<br/>';
	echo 'PDO错误处理模式：'.$pdo->getAttribute(PDO::ATTR_ERRMODE);
}catch(PDOException $e){
	echo $e->getMessage();
}



