<?php
    $data_name=trim($_POST['data_name']);
    $user_id=$_POST["user_id"];
    $parent = $_POST["parent"];

    $ext = explode(".", $data_name);
    $data_name_file=$ext[0];
    $ext = (sizeof($ext) < 2) ? "0" : $ext[1];
    $ext = ($ext === "0") ? "0" : "$ext";

    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    include('db.php');

    // To protect MySQL injection for Security purpose
    $data_name = stripslashes($data_name);
    $user_id = stripslashes($user_id);
    $parent = stripslashes($parent);
    $data_name = mysql_real_escape_string($data_name);
    $user_id = mysql_real_escape_string($user_id);
    $parent = mysql_real_escape_string($parent);

    // Selecting Database
    $db = mysql_select_db("syncjar", $connection);

    $query = mysql_query("select * from files where data_name='$data_name' AND data_parent='$parent' AND user_id='$user_id' AND data_extension='$ext' UNION select * from files where data_name='$data_name_file' AND data_parent='$parent' AND user_id='$user_id' AND data_extension='$ext'", $connection);

    $rows = mysql_num_rows($query);
    if ($rows === 0) {
        $query = mysql_query("INSERT INTO files (user_id, modified, data_type, data_name, data_parent, data_extension, size, updates, star, shared)
        VALUES ('$user_id', NOW(), 0, '$data_name', '$parent', 0, 0, 0, 0, 0);", $connection);

        if($query){
          echo mysql_insert_id();
        }else{
            echo "Error Creating!";
        }
     }else{
        echo "Error Creating!";
    }
?>