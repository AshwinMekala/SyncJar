<?php
	
	$user_name =  $_POST['user_name'];
	$password = $_POST['password'];
	$return_arr = [];

	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from users where password='$password' AND user_name='$user_name'", $connection);
	$rows = mysql_num_rows($query);
	if ($rows == 1) {
		$row = mysql_fetch_assoc($query);
		$user_id = $row['user_id'];
		$query = mysql_query("INSERT INTO devices (user_id)
        VALUES ('$user_id');", $connection);

	    $row_array['status'] = 1;
		$row_array['user_name'] = $user_name;
		$row_array['user_id'] = $user_id;
	    $row_array['device_id'] = mysql_insert_id();

		array_push($return_arr,$row_array);
		
		echo json_encode($return_arr);

	}else{
		$row_array['status'] = 0;

		array_push($return_arr,$row_array);
		
		echo json_encode($return_arr);
	}

?>