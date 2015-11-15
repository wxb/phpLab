<?php
	/*
	 * filename: pdo_bindColumn.php
	 * author  : wangxb
	 * create  : 2015_04_14 02:23:51
	 * update  : @update
	 */


try{
    $dns = "mysql:host=localhost;dbname=pdotest";
    $pdo = new PDO($dns, 'root', 'root');
    // 使用 ？ 占位符方式
    $sql = "SELECT id, name, email FROM user";
    $stmt = $pdo->prepare($sql);
	// bindColumn() 方法可以通过第一个参数：通过列号和通过列名
    $stmt->bindColumn(1, $id);
    $stmt->bindColumn(2, $name);
    $stmt->bindColumn('email', $email);
    $stmt->execute();
	// 通过 fetch()方法返回一条信息，我们已经将查询到的每一个字段的信息绑定到相应的php变量，然后输出
	while($stmt->fetch(PDO::FETCH_BOUND)){
		echo "用户ID:$id > 用户名: $name > 邮箱：$email";
		echo "<hr/>";
	}
	// 使用countColumn（）方法返回结果集中信息列数，注意是：列数
	echo "共查询到 ".$stmt->columnCount().' 条';
	// getColumnMeta 返回结果集中一列的元数据 ,以0开始索引，但是注意PHP手册中警告：这个方法不安全，可能在未来会被修改，所以使用时需谨慎
	echo "第一列元数据<br/>";
	print_r($stmt->getColumnMeta(0));
}catch(PDOException $e){
    $e->getMessage();
}
