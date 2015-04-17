<?php
	$stime = microtime();
	try{
			// 这里我们简单的写了一个PDO连接数据库的测试程序
			$dsn = "mysql:host=localhost;dbname=test";
			$pdo = new PDO($dsn, 'root', 'root');
			$sql = "SELECT * FROM account";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			// 假设第一次我们只输出了有多少行
			echo $stmt->rowCount();
			echo '<hr/>';
			// 我们想要打印查询出的所有数据
			print_r($stmt->fetchAll());
	}catch(PDOExecption $e){
		$e->getMessage();
	}
