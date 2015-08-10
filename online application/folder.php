<?php 
	session_start();
	if(isset($_SESSION['user_id'])){
	    include "folder_header.php";
	    include "folder_body.php";
	    include "contextmenu.php";
	    include "footer_models.php";
	  }
	  else{
	  	header("location: //www.syncjar.com/");
	  }

?>