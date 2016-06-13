<?php
include 'psl-config.php';

$db=@mysql_connect(HOST, USER, PASSWORD);
if(!$db){
	echo "Failed to connnect to MySql";
}
mysql_select_db('DATABASE', $db);
?>