<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {

	case "goodrecommend":
		getgoodrecommend();
		break;
}
function getgoodrecommend() {
	global $db;
	
	$pageid = intval(global_get_param( $_REQUEST, 'pageid', null ,0,1  ));
	
	$sql = " SELECT * FROM goodrecommend WHERE publish = '1'";
	$db->setQuery($sql);
	$list = $db->loadRowList();
	
	$goodrecommend_arr = array();
	foreach($list as $row)
	{
		if($row['type'] == 'set')
		{
			$title = $row['var1'];
		}
		else
		{
			
			$info = array();
			$info['name'] = $row['name'];
			$info['linkurl'] = $row['linkurl'];
			
			$imglist=getimg('goodrecommend',$row['id']);	
			foreach($imglist as $var)
			{
				$info['img'] = str_replace("../","",$var);
				break;
			}
			
			$goodrecommend_arr[] = $info;
			
		}
	}
	
	JsonEnd(array("status" => 1, "data" => $goodrecommend_arr, "title"=>$title));
	
}

include( $conf_php.'common_end.php' ); 
?>