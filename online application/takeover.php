<?php

	$takeover_id = $_POST['takeover_id'];
	$data_id = $_POST['data_id'];

	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("UPDATE files SET user_lock = '$takeover_id', takeover = 0 WHERE data_id='$data_id'", $connection);
	sleep(1);
	echo "1";

?>