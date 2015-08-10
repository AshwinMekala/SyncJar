<?php 
	session_start();
	if(isset($_SESSION['user_id'])){
		include "shared_header.php";
		include "shared_body.php";
		include "contextmenu.php";
		include "footer_models.php";
	}
	else{
	  	header("location: //www.syncjar.com/");
	}

?>