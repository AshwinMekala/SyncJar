<?php 
	$url = explode("/", $_SERVER["REQUEST_URI"]);
	$user_id = $url[2];

	include('db.php');

	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("select data_type,data_id,data_name,data_parent,data_extension,size from files where user_id='$user_id'", $connection);

	$rows = array();

	while($row = mysql_fetch_assoc($query)) {
	    $rows[] = $row;
	}
	print json_encode($rows);
?>