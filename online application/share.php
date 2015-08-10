<?php

	$edit_user = $_POST['edit_user'];
	$edit_permit = $_POST['edit_permit'];
	$add_user = $_POST['add_user'];
	$add_permit = $_POST['add_permit'];
	$data_id = $_POST['data_id'];
	$success = 0;

	//echo "user : ".$edit_user." permit : ".$edit_permit." add user : ".$add_user; 
//	echo "Error user!";
	
	include('db.php');
	$db = mysql_select_db("syncjar", $connection);	

	if(!empty($edit_user) && !empty($edit_permit)){
		if($edit_permit == 3){
			mysql_query("DELETE FROM data_shared WHERE user_id='$edit_user' AND data_id='$data_id'");
			$success = 1;
		}else{
			mysql_query("UPDATE data_shared SET permit_type='$edit_permit' WHERE user_id='$edit_user'", $connection);
			$success = 1;
		}
		check_share($connection,$data_id);
	}

	if (!empty($add_user) && !empty($add_permit)) {
		$query = mysql_query("select * from users where user_name='$add_user'", $connection);
		$rows = mysql_num_rows($query);
		if($rows === 1){
			$tableRow = mysql_fetch_assoc($query);
				$user_id = $tableRow['user_id'];
				$query = mysql_query("select * from data_shared where data_id='$data_id' AND user_id='$user_id'", $connection);
			    $rows = mysql_num_rows($query);
			    if($rows === 0){
					$query = mysql_query("INSERT INTO data_shared (data_id, user_id, permit_type)
			        VALUES ('$data_id', '$user_id', '$add_permit');", $connection);
			        check_share($connection,$data_id);
			        $success = 1;
				}
		}
	}

	function check_share($connection,$data_id){
		$query = mysql_query("select * from data_shared where data_id='$data_id'", $connection);
		$rows = mysql_num_rows($query);
		    if($rows === 0){
		    	mysql_query("UPDATE files SET shared=0 WHERE data_id='$data_id'", $connection);
		    }else{
		    	mysql_query("UPDATE files SET shared=1 WHERE data_id='$data_id'", $connection);
		    }
	}
	if($success === 1)
		echo "Success!";

?>