<?php
define('DIR', dirname(__FILE__));
require(DIR.'/config.php');

class MysqlDb{
	protected $conn = null;
	
	public function __construct($config){
		$this->conn = mysql_connect($config['host'], $config['username'], $config['password']) or die(mysql_error());
		mysql_select_db($config['database'], $this->conn) or die(mysql_error());
		mysql_query('set names '.$config['charset']) or die(mysql_error());
	}
	
	public function query($sql){
		$resource = mysql_query($sql, $this->conn) or die(mysql_error());
		$result = array();
		while(false != ($row = mysql_fetch_assoc($resource))){
			$result[] = $row;
		}
		return $result;
	}
	
	public function queryPid(){
		$sql = 'SELECT pid, COUNT(*) FROM onethink_menu GROUP BY pid';
		$pidArr = $this->query($sql);
		$result = array_map(function($v){
			return $v['pid'];
		}, $pidArr);
		return $result;
	}
	
	public function queryInfoByPid($pid){
		$sql = 'SELECT id, title, sort, url, hide, tip, `group`, is_dev FROM onethink_menu WHERE pid='.$pid;
		return $this->query($sql);
	}
	
}
/*
$db = new MysqlDb($localDB);
$res = $db->queryPid();
$infoArr = array();
foreach($res as $v){
	$infoArr[] = $db->queryInfoByPid($v);
}
print_r($infoArr);
*/


