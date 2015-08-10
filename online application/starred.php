<?php 
	session_start();
	if(isset($_SESSION['user_id'])){
	    include "starred_header.php";
	    include "starred_body.php";
	    include "contextmenu.php";
	    include "footer_models.php";
	  }
	  else{
	  	header("location: //www.syncjar.com/");
	  }

?>