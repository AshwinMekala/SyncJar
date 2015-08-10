<?php
	
	$takeover = $_POST['user_id'];
	$data_id = $_POST['data_id'];

	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("UPDATE files SET takeover = '$takeover' WHERE data_id='$data_id'", $connection);
	echo "1";

?>