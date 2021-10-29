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
	
ini_set('memory_limit', '20M');	
switch($task)
{
	case "bannerupd":
		$table = 'advrolls';
		$imgName = 'banner';
		break;
	case "dm":
		$imgName = 'dm';
		break;
	case "dm2":
		$imgName = 'dm2';
		break;
	case "storeupd":
		$table = 'dbpage';
		$imgName = 'store';
		break;
	case "productupd":
		$table = 'products';
		$imgName = 'product';
		break;
	case "product_banner":
		$table = 'products';
		$imgName = 'product_banner';
		break;
	case "activitieupd":
		$table = 'activities';
		$imgName = 'activities';
		break;
	case "siteupd":
		$table = 'siteinfo';
		$imgName = 'logo';
		break;
	default:
		die('ERROR');
}

	
if(!empty($imgName) && !empty($task) ){
	
	
	if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
		
		$id = $_GET['id'];
		$uid = $_GET['uid'];
		if($task!="dm" && $task!="dm2"){
			$sql = "select * from $table where id='$id'";
			
			if($table == 'siteinfo')
				$sql = "select * from $table where sysid='$id'";
			
			$db->setQuery( $sql );
			$arr = $db->loadRowList();	
			if(count($arr)<1)
			{
				die("error");
			}
		}
		$imageData = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		$filteredData = substr($imageData, strpos($imageData, ",") + 1);
		$unencodedData = base64_decode($filteredData);
		$img_type = "jpg";
		if($task == 'productupd' || $task == 'product_banner' || $task == 'activitieupd' || $task == 'siteupd')
		{
			$imgid = $_GET['imgid'];
			if($task == 'siteupd' && $imgid==3){
				$img_type = "ico";
			}
			$filename="../upload/{$imgName}/{$imgName}_{$id}_{$imgid}.{$img_type}";
			$tmp_filename="../upload/{$imgName}/tmp_{$id}_{$imgid}.{$img_type}";
		}else if($task=="dm"){
			$tmp_filename="../upload/tmp_{$imgName}.{$img_type}";
			$filename="../upload/{$imgName}.{$img_type}";
		}else if($task=="dm2"){
			$tmp_filename="../upload/tmp_{$imgName}.{$img_type}";
			$filename="../upload/{$imgName}.{$img_type}";
		}
		else{
			$tmp_filename="../upload/{$imgName}/tmp_{$id}.{$img_type}";
			$filename="../upload/{$imgName}/{$imgName}_{$id}.{$img_type}";
		}
		
		
		
		
		$fp = fopen($tmp_filename, 'wb');
		fwrite($fp, $unencodedData);
		fclose($fp);
		if(filesize($tmp_filename)==0){
			unlink($tmp_filename);
			die("no upd1");
		}
		if(is_file($filename)){
			if(filesize($filename)!=filesize($tmp_filename)){
				unlink($filename);
				rename($tmp_filename,$filename);
				die("updsuc");
			}else{
				unlink($tmp_filename);
				die("no upd2");
			}
		}else{
			rename($tmp_filename,$filename);
			die("addsuc");
		}
		
	} 

}

require( 'common_end.php' ); 
?>