<?php
if (isset($_POST['submit']))
{
    $name=$_POST['name'];
    $username=$_POST['username'];
    $password=$_POST['password'];
    $email=$_POST['email'];
    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    include('db.php');
    // To protect MySQL injection for Security purpose
    $name = stripslashes($name);
    $username = stripslashes($username);
    $password = stripslashes($password);
    $email = stripslashes($email);
    $name = mysql_real_escape_string($name);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    // Selecting Database
    $db = mysql_select_db("syncjar", $connection);
    // SQL query to fetch information of registerd users and finds user match.
    $query = mysql_query("INSERT INTO users (user_name, password, name, email)
    VALUES ('$username', '$password', '$name', '$email');", $connection);

    if($query){
      header("location: //www.syncjar.com/index.php?success=success");
    }else{
      header("location: //www.syncjar.com/index.php?error=error");
    }
}
?>