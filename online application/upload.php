<?php

    // upload.php
    // 'images' refers to your file input name attribute
    if (empty($_FILES['files'])) {
        echo json_encode(['error'=>'No files found for upload.']); 
        // or you can throw an exception 
        return; // terminate
    }
     
    // get the files posted
    $files = $_FILES['files'];
     
    // get user id posted
    $user_id = empty($_POST['id']) ? '' : $_POST['id'];
     
    // get user name posted
    $parent = empty($_POST['parent']) ? '' : $_POST['parent'];
    $parent = !empty($parent) ? "'$parent'" : "0";

    // a flag to see if everything is ok
    $success = null;
     
    // file paths to store
    $paths= [];
     
    // get file names
    $filenames = $files['name'];

    //database connection
    include('db.php');
    $db = mysql_select_db("syncjar", $connection);
     
    // loop and process files
    for($i=0; $i < count($filenames); $i++){
        $ext = explode(".", basename($filenames[$i]));
        $data_name=$ext[0];
        $ext = (sizeof($ext) < 2) ? "0" : $ext[1];
        $ext = ($ext === "0") ? "0" : "$ext";

        $query = mysql_query("select * from files where data_name='$data_name' AND data_parent=$parent AND data_type=1 AND user_id='$user_id' AND data_extension='$ext'", $connection);
        $rows = mysql_num_rows($query);
        
        if ($rows === 0) {
            $query = mysql_query("INSERT INTO files (user_id, modified, data_type, data_name, data_parent, data_extension, size, updates, star, shared)
            VALUES ('$user_id', NOW(), 1, '$data_name', $parent, '$ext', 0, 0, 0, 0);", $connection);
            if(mysql_errno()){
                echo "MySQL error ".mysql_errno().": "
                     .mysql_error()."\n<br>When executing <br>\n$query\n<br>";
            }
            $target = "files" . DIRECTORY_SEPARATOR . mysql_insert_id();
            if(move_uploaded_file($files['tmp_name'][$i], $target)) {
                $size = filesize("files" . DIRECTORY_SEPARATOR . mysql_insert_id());
                $data_id = mysql_insert_id();
                $query = mysql_query("UPDATE files SET size = '$size' WHERE data_id='$data_id'", $connection);
                $query = mysql_query("UPDATE users SET data_size = '$size' WHERE user_id='$user_id'", $connection);
                $success = true;
                $paths[] = $target;
            } else {
                $success = false;
                break;
            }
        } else {
                $success = false;
                break;
        }
    }
    function save_data($userid, $username, $paths){

    }
     
    // check and process based on successful status 
    if ($success === true) {
        // call the function to save all data to database
        // code for the following function `save_data` is not 
        // mentioned in this example
        //save_data($userid, $username, $paths);
     
        // store a successful response (default at least an empty array). You
        // could return any additional response info you need to the plugin for
        // advanced implementations.
        $output = ['data_id' => mysql_insert_id()];
        // for example you can get the list of files uploaded this way
        // $output = ['uploaded' => $paths];
    } elseif ($success === false) {
        
        $output = ['error'=>'Error! Files Already Exists'];
        // delete any uploaded files
        foreach ($paths as $file) {
            unlink($file);
        }
    } else {
        $output = ['error'=>'No files were processed.'];
    }
     
    // return a json encoded response for plugin to process successfully
    echo json_encode($output);

?>