<?php
	$data_id =  $_POST['data_id'];

	// database Connections
	include('db.php');
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("select * from files where data_id='$data_id'", $connection);
    $tableRow = mysql_fetch_assoc($query);
    if($tableRow['star'] == 0){
    	echo 0;
    }else{
    	echo 1;
    }
?>