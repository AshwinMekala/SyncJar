<?php
	$url = explode("/", $_SERVER["REQUEST_URI"]);
	$data_parent = $url[2];
	unlink("./files/".$url[2]);
		
		include('db.php');
		$db = mysql_select_db("syncjar", $connection);
		$query = mysql_query("DELETE FROM files WHERE data_id='$data_parent'", $connection);
		deleteAll($data_parent, $connection);
	  echo ("Deleted");

	  function deleteAll($data_parent, $connection){
	  	$query = mysql_query("select * from files where data_parent='$data_parent'", $connection);
        while ($tableRow = mysql_fetch_assoc($query)) {
        	$data_parent = $tableRow["data_id"];
        	mysql_query("DELETE FROM files WHERE data_id='$data_parent'", $connection);
        	unlink("./files/".$tableRow["data_id"]);
        	deleteAll($data_parent, $connection);
        }
	  }
?>