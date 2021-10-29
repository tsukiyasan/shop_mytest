<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$chartType = global_get_param( $_POST, 'chartType', null ,0,1  );
switch ($task) {

	case "hotpro":
		getHotProduct();
		break;
	case "midmenu":
		getMidMenu();
		break;
}
function getMidMenu() {
	global $db;
	
	$sql = "select * from midmenus order by odring";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	
	$dataArr=array();
	foreach($r as $key=>$row){
		
		$info = array();
		$info['id'] = $row['id'];
		$info['name'] = $row['name'];
		$info['linktype'] = $row['linktype'];
		$info['linkurl']=getDBPageLink($row['linktype'],$row['linkurl'],$row['tablename'],$row['databaseid']);
		$info['tablename'] = $row['tablename'];
		$info['databaseid'] = $row['databaseid'];
		$info['odring'] = $row['odring'];
		$info['publish'] = $row['publish'];
		
		$info['img'] = getimg("midmenus", $info['id']);
		$info['img']=$info['img'][1];
		$info['img'] = $info['img'] ? $info['img'] : '';
		
		$dataArr[] = $info;
	}
	
	
	if(isset($r)) {
		JsonEnd(array("status" => 1, "data" => $dataArr));
	} else {
		JsonEnd(array("status" => 0, "errorcode" => 1));
	}
}


function getHotProduct() {
	global $db;
	
	$sql = "select * from products where publish = '1' AND hotChk = '1' order by mtime desc limit 0 , 4";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	$dataArr=array();
	foreach($r as $key=>$row){
		
		$info = array();
		$info['id'] = $row['id'];
		$info['name'] = $row['name'];
		$info['highAmt'] = $row['highAmt'];	
		$info['siteAmt'] = $row['siteAmt'];	
		$info['var03'] = $row['var03'];	
		$info['promedia'] = $row['var04'];	
		
		$imglist=getimg('products',$info['id']);	
		foreach($imglist as $var)
		{
			$info['img'] = str_replace("../","",$var);
			break;
		}
		
		$dataArr[] = $info;
	}
	
	if(isset($r)) {
		JsonEnd(array("status" => 1, "data" => $dataArr));
	} else {
		JsonEnd(array("status" => 0, "errorcode" => 1));
	}
}



include( $conf_php.'common_end.php' ); 
?>