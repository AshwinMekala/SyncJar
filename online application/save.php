<?php
	
	$user_lock = $_GET['user_lock'];
	$data_id = $_GET['data_id'];
	$x = $_GET['x'];
	$y = $_GET['y'];
	
	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);
	mysql_query("UPDATE files SET user_lock='$user_lock', last_connected = NOW(), x='$x', y='$y' WHERE data_id='$data_id'", $connection);

	if(file_put_contents("./files/".$_GET['data_id'], file_get_contents("php://input"))){
		echo "success!";
	}
?>