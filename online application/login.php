<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
		header("location: //www.syncjar.com/index.php?error=error");
	}
	else
	{
	// Define $username and $password
	$username=$_POST['username'];
	$password=$_POST['password'];

	include('db.php');

	// Selecting Database
	$db = mysql_select_db("syncjar", $connection);
	// SQL query to fetch information of registerd users and finds user match.
	$query = mysql_query("select * from users where password='$password' AND user_name='$username'", $connection);
	$rows = mysql_num_rows($query);
	if ($rows == 1) {
	$_SESSION['user_name'] = $username;

	$row = mysql_fetch_assoc($query);
	$_SESSION['user_id'] =$row['user_id'];

	header("location: //www.syncjar.com/my-jar"); // Redirecting To Other Page
	} else {
	header("location: //www.syncjar.com/index.php?error=error");
	}
	mysql_close($connection); // Closing Connection
	}
}
?>