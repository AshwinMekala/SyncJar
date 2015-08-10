<?php
	$url = explode("/", $_SERVER["REQUEST_URI"]);
	$move_raised = $url[3];
	$move_to = $url[2];
	$user_id = $_POST['user_id'];

	include('db.php');
	$db = mysql_select_db("syncjar", $connection);

    $query = mysql_query("select * from files where data_id='$move_raised' AND user_id='$user_id'", $connection);
    $tableRow = mysql_fetch_assoc($query);
    
    $raised_data_name =  $tableRow['data_name'];
    $raised_data_extension = $tableRow['data_extension'];
    $raised_data_type = $tableRow['data_type'];

    $query = mysql_query("select * from files where user_id='$user_id' AND data_name='$raised_data_name' AND data_parent='$move_to' AND data_extension='$raised_data_extension' AND data_type='$raised_data_type'", $connection);
    $exist = mysql_num_rows($query);
    
    if ($exist === 0) {
    	mysql_query("UPDATE files SET data_parent='$move_to' WHERE data_id='$move_raised'", $connection);	
    }else{
    	echo "File Exists!";
    }

    mysql_close($connection);
?>