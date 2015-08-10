<?php

	$url = explode("/", $_SERVER["REQUEST_URI"]);
	$data_id = $url[2];
	$status = 0;
	$return_arr = [];

	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from files where data_id='$data_id'", $connection);
	$status = mysql_query("select * from files where data_id='$data_id' AND last_connected < DATE_SUB(NOW(), INTERVAL 5 SECOND)", $connection);
	$status = mysql_num_rows($status);
	
	$filesRow = mysql_fetch_array($query, MYSQL_ASSOC);
	$takeover_id = $filesRow['takeover'];

	$query = mysql_query("select * from users where user_id='$takeover_id'", $connection);
	$takeoverRow = mysql_fetch_array($query, MYSQL_ASSOC);

	$user_lock = $filesRow['user_lock'];

	$userQuery = mysql_query("select * from users where user_id='$user_lock'", $connection);
	$userRow = mysql_fetch_array($userQuery, MYSQL_ASSOC);

	    $row_array['user_name'] = $userRow['user_name'];
	    $row_array['user_lock'] = $filesRow['user_lock'];
	    $row_array['status'] = $status;
	    $row_array['takeover'] = $filesRow['takeover'];
	    $row_array['takeover_name'] = $takeoverRow['user_name'];
	    $row_array['x'] = $filesRow['x'];
	    $row_array['y'] = $filesRow['y'];


	array_push($return_arr,$row_array);
	
	echo json_encode($return_arr);
?>