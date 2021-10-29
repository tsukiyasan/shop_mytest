<?php

	header('Access-Control-Allow-Origin: *'); 
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
require( 'common_start.php' ); 
$root_admin = "WEQGWH";
$root_store = "HJKTDS";
$task = $_GET['task'];
	global $conf_user;
		
switch($task)
{
	case "bannerupd":
		$table = 'advrolls';
		break;
	case "marqueeupd":
		$table = 'marquee';
		break;
	case "activitiesupd":
		$table = 'activities';
		break;
	case "newsupd":
		$table = 'news';
		break;
	case "noticeupd":
		$table = 'shopNotice';
		break;
	case "mainmenuupd":
		$table = 'mainmenus';
		break;
	case "superiorityupd":
		$table = 'dbpage';
		break;
	case "qlinkupd":
		$table = 'bottommenus';
		break;
	case "dbpageupd":
		$table = 'dbpage';
		break;
	case "productsupd":
		$table = 'products';
		break;
	default:
		die('ERROR');
}

	
if(!empty($table) && !empty($task) ){
	
	$id = $_GET['id'];
	$uid = $_GET['uid'];
	$sql = "select * from $table where id='$id'";
	
	$db->setQuery( $sql );
	$arr = $db->loadRowList();	
	if(count($arr)<1)
	{
		die("error");
	}
	
	$content = global_get_param( $_POST, 'content', null, 0, 0, 0, null, _COMMON_PARAM_CONTENT);
	
	
	$sql = "UPDATE $table SET content=N'$content' WHERE id='$id'";
	
	if($task=="productsupd")
	{
		$var01 = global_get_param( $_POST, 'var01', null, 0, 0, 0, null, _COMMON_PARAM_CONTENT);
		$var02 = global_get_param( $_POST, 'var02', null, 0, 0, 0, null, _COMMON_PARAM_CONTENT);
		$var03 = global_get_param( $_POST, 'var03', null, 0, 0, 0, null, _COMMON_PARAM_CONTENT);
		$sql = "UPDATE $table SET notes=N'$content',var01='$var01',var02='$var02',var03='$var03' WHERE id='$id'";
	}
	
	$db->setQuery( $sql );
	$db->query(); 

}

require( 'common_end.php' ); 
?>