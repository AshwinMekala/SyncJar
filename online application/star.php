<?php
	$data_id =  $_POST['data_id'];

	// database Connections
	include('db.php');
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("UPDATE files SET star = NOT star WHERE data_id='$data_id'", $connection);

?>