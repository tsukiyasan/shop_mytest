<?php


include( '../../config.php' ); 

$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tablename="receivemessages";
userPermissionChk("message");
switch ($task) {
	
	case "list":	
		showlist();
		break;
	case "detail":	
		showdetail();
		break;
	case "update":	
		updatepage();
		break;
	case "del":	
		deletepage();
		break;
	case "odrchg":	
		odrchg($tablename,$id);
		break;	
	case "alllist":
	    showlist('all');
	    break;	
}

function showlist($mode=null){
	global $db, $globalConf_list_limit,$tablename;
	$arrJson = array();
	
	$page = max(intval(global_get_param( $_REQUEST, 'page', 1 )), 1);
	$name = global_get_param( $_REQUEST, 'name', null );
	
	if(isset($name)){
		
		
		$search_arr = explode(" ", $name);
		$search_where_str = "";
		if(count($search_arr) > 0)
		{
			foreach($search_arr as $str)
			{
				if(isset($str))
				{
					$search_where_str .= " OR ( name like N'%$str%' )";
				}
			}
		}
		if(isset($search_where_str))
			$where_str = " WHERE ( 1<>1 $search_where_str) ";
	}
	
	
	$sql = "SELECT * FROM $tablename $where_str ORDER BY state,rectime desc";	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$cnt = count($r);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$page = ($page > $pagecnt) ? $pagecnt : $page;
	
	$from = ($page - 1 ) * $globalConf_list_limit;
	$end = $page * $globalConf_list_limit;
	
	$data = array();
	for($i = $from; $i < min($end, $cnt); $i++) {
		$info=array();
	 	$info['id']=$r[$i]['id'];
	 	$info['title']=$r[$i]['title'];
	 	$info['name']=$r[$i]['name'];
	 	$info['tel']=$r[$i]['tel'];
	 	$info['email']=$r[$i]['email'];
	 	$info['recTime']=$r[$i]['recTime'];
	 	$info['ip'] = $r[$i]['ip'];
	 	$info['state']=$r[$i]['state'];
		$info['qtype'] = (!empty($r[$i]['qtype'])) ? $r[$i]['qtype'] : "";
		$info['city'] = $row['city'];
		
		$data[]=$info;
	}
	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
}

function showdetail(){
	global $db,$conf_dir_path,$conf_banner,$tablename;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	$sql = "SELECT * FROM $tablename WHERE id='$id'";

	$db->setQuery( $sql );
	$row = $db->loadRow();	
	if(count($row) > 0)
	{
		$info = array();
		$info['id'] = $id;
		$info['title'] = $row['title'];
		$info['name'] = $row['name'];
		$info['recTime'] = $row['recTime'];
		$info['tel'] = $row['tel'];
		$info['email'] = $row['email'];
		$info['ip'] = $row['ip'];
		$info['state'] = $row['state'];
		$info['recContent'] = nl2br($row['recContent']);
		if($row['state']==3){
			$info['sendContent'] = nl2br(getFieldValue("select sendContent from sendmessages where messageid='$id'",'sendContent'));
		}
		
		$info['qtype'] = (!empty($row['qtype'])) ? $row['qtype'] : "";
		$info['city'] = $row['city'];
		
	}
	
	$arrJson['data'] = $info;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function updatepage(){
	global $db, $conf_user,$tablename,$conf_banner;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	$state = intval(global_get_param( $_REQUEST, 'state', null ));
	$sendContent = global_get_param( $_REQUEST, 'sendContent', null );
	
	if(empty($state))
	{
		$state = 3;
	}
	
	
	$date=date("Y-m-d H:i:s");
	$field="";
	$field=" state='".$state."',";
	
	
	
	if($sendContent){
		$sql="insert into sendmessages (messageid,type,title,sendContent,sendTime,viewState,ctime,mtime,muser)
				values
				('$id','contact',N'',N'$sendContent','$date',1,'$date','$date',1)";
		$db->setQuery($sql);
		$db->query();
		$field=" state=3,";
	}
	$updatesqlend = "UPDATE $tablename set $field mtime='$date',muser=1 where id='$id'";
	
	$db->setQuery($updatesqlend);
	if(!$db->query())
	{
		$arrJson['msg'] = _COMMON_QUERYMSG_UPD_ERR;
		JsonEnd($arrJson);
	}
	
	$arrJson['msg'] = _COMMON_QUERYMSG_UPD_SUS;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function deletepage(){
	global $db, $conf_user,$conf_banner,$tablename,$conf_dir_path;
	$arrJson = array();
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ));
	
	
	$sql = "DELETE FROM $tablename WHERE id = '$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
		JsonEnd($arrJson);
	}
	else{
		if(is_file($conf_dir_path.$conf_banner."{$id}.jpg")){
			unlink($conf_dir_path.$conf_banner."{$id}.jpg");
		}
		
		delimg($tablename,$id);
		
		$arrJson['status'] = "1";
		JsonEnd($arrJson);
	}
		
}

include( $conf_php.'common_end.php' ); 
?>