<?php 

	$dbconn = pg_connect("host=192.168.1.122 dbname=pg_cms user=postgres password=1234 port=5432"); 
if (!$dbconn) { 
	echo 'Could not connect to postgresql'; ; 
}else{
	echo 'Connection OK'; 
}
?> 
