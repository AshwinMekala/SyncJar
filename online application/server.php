<?php

$url = explode("/", $_SERVER["REQUEST_URI"]);

switch ($url[1]) {
	case 'login':
		include('login.php');
		break;
	case 'logout':
		include('logout.php');
		break;
	case 'signup':
		include('signup.php');
		break;
	case 'my-jar':
		//echo "myjar";
		include 'myjar.php';
		break;
	case 'shared':
		//echo "shared";
		include 'shared.php';
		break;
	case 'recent':
		include('recent.php');
		break;
	case 'starred':
		include('starred.php');
		break;
	case 'account':
		echo "account";
		break;
	case 'logout':
		echo "logout";
		break;
	case 'folder':
		include('folder.php');
		break;
	case 'file':
		// echo "file selected";
		include 'code_editor.php';
		break;
	case 'download':
		include "download.php";
		break;
	case 'newfolder':
		include('newfolder.php');
		break;
	case 'newfile':
		include('newfile.php');
		break;
	case 'move':
		include('move.php');
		break;
	case 'sharedwith':
		include('sharedwith.php');
		break;
	case 'share':
		include('share.php');
		break;
	case 'isstar':
		include('isstar.php');
		break;
	case 'star':
		include('star.php');
		break;
	case 'rename':
		include('rename.php');
		break;
	case 'delete':
		include('delete.php');
		break;
	case 'save':
		include('save.php');
		break;
	case 'filestatus':
		include('filestatus.php');
		break;
	case 'takeover':
		include('takeover.php');
		break;
	case 'takeoverreq':
		include('takeoverreq.php');
		break;
	case 'revoke':
		include('revoke.php');
		break;
	case 'device':
		include('device.php');
		break;
	case 'data':
		include('data.php');
		break;

	default:
		echo "<html style='background-color:#111155;font-size:60px;'>
		<img src='./images/logo_header.png'>
		<h1 style='color:#fff;'>oops! PAGE NOT FOUND</h1>
		</html>";
		break;
}
?>