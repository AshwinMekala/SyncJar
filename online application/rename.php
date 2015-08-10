<?php

	$data_id =  $_POST['data_id'];
	$data_name =  $_POST['data_name'];

	// database Connections
	include('db.php');
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("select * from files where data_id='$data_id'", $connection);
    $tableRow = mysql_fetch_assoc($query);
    $data_parent = $tableRow['data_parent'];

	if(empty($data_name)){
    	echo $tableRow['data_name'];
	}else{
		$query = mysql_query("select * from files where data_name='$data_name' AND data_parent='$data_parent'", $connection);
		$rows = mysql_num_rows($query);
		if($rows === 0){
			$query = mysql_query("UPDATE files SET data_name = '$data_name' WHERE data_id='$data_id'", $connection);
			echo "Success!";
		}
	}

?>