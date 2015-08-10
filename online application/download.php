<?php

// We'll be outputting a PDF
//header('Content-type: ' . $mime_type);

// It will be called downloaded.pdf

	$url = explode("/", $_SERVER["REQUEST_URI"]);

	$data_id =  $url[2];
	
	// database Connections
	include('db.php');
	$db = mysql_select_db("syncjar", $connection);

	$query = mysql_query("select * from files where data_id='$data_id'", $connection);
    $tableRow = mysql_fetch_assoc($query);
    $data_name = $tableRow['data_name'];
    $data_extension = $tableRow['data_extension'];
    if(empty($data_extension)){
		header('Content-Disposition: attachment; filename="'.$data_name.'"');
	}else{
		header('Content-Disposition: attachment; filename="'.$data_name.'.'.$data_extension.'"');
	}

	// The PDF source is in original.pdf
	readfile("./files/".$url[2]);

?>