<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {
	
	case "list": 
		shipdate_list();
		break;
	case "detail": 
		shipdate_detail();
		break;
	case "add": 
	case "update": 
		shipdate_update();
		break;
	case "del": 
		shipdate_delete();
		break;
	case "operate":	
		operate();
		break;	
	case "getpcode":	
		getpcode();
		break;	
	
}

function shipdate_list()
{
	global $db, $globalConf_list_limit;
	
	
	$table = 'shipdate';
	$cur = global_get_param( $_REQUEST, 'page', null);
	$search = global_get_param( $_REQUEST, 'search', null);
	$arrJson = array();
	if($search) {
		$where_str .= " OR name like '%$search%' ";
		$where_str .= " OR content like '%$search%' ";
		if(!empty($where_str))
		{
			$where_str = "AND ( 1<>1 $where_str )";
		}
	}
		
	$today = date("Y-m-d");
	
	
	$sql = " select * from $table where 1=1 $where_str order by date desc ";	

	$db->setQuery( $sql );
	$row = $db->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	$returnArray = array();
	
	for($i = $from; $i < min($end, $cnt); $i++) {
		$returnArray[] = $row[$i];
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;
	$arrJson['cnt'] = $pagecnt;
	
	JsonEnd($arrJson);
}

function shipdate_detail(){
	global $db,$conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );

	$sql = "SELECT * FROM shipdate WHERE id = '$id'";
	$db->setQuery( $sql );
	$shipdate_info = $db->loadRow();	
	
	if(count($shipdate_info)>0)
	{
		$info = array();
		$info['id'] = $shipdate_info['id'];
		$info['name'] = $shipdate_info['name'];
		$info['type'] = $shipdate_info['type'];
		$info['date'] = $shipdate_info['date'];
		$info['content'] = $shipdate_info['content'];		
	}
	
	
	$arrJson['info'] = $info;
	$arrJson['pcode'] = $pcode;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
	
}

function getpcode()
{
	global $db, $conf_user;
	
	$sql = "SELECT * FROM pubcode where deleteChk=0 AND codeKinds = 'shipdaytype' order by odring";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$pcode=array();
	foreach($r as $row){
		$info=array();
		$info['codeName']=$row['codeName'];
		$info['codeValue']=intval($row['codeValue']);
		$pcode[$row['codeKinds']][$row['codeValue']]=$info;
	}
	
	$arrJson['pcode'] = $pcode;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function shipdate_update() {
	global $db, $conf_user;
	$arrJson = array();

	$uid = $_SESSION[$conf_user]['uid'];
	$id = global_get_param( $_REQUEST, 'id', null );
	$name = global_get_param( $_REQUEST, 'name', null );
	$type = global_get_param( $_REQUEST, 'type', null );
	$date = global_get_param( $_REQUEST, 'date', null );
	$content = global_get_param( $_REQUEST, 'content', null );
	
	if(!empty($date))
	{
		$year = date("Y",strtotime($date));
		$month = date("m",strtotime($date));
		$day = date("d",strtotime($date));
		
		
		

	}
	
	$now = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO shipdate (id, name, type, year, month, day, date, content, ctime, mtime, muser)
			VALUES ( '$id', '$name', '$type', '$year', '$month', '$day', '$date', '$content', '$now', '$now', '$uid')
			ON DUPLICATE KEY UPDATE name=VALUES(name),type=VALUES(type),year=VALUES(year),month=VALUES(month),day=VALUES(day),date=VALUES(date),content=VALUES(content),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	$msg = $id ? "更新成功" : "新增成功";
	
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_ADD_ERR;
		JsonEnd($arrJson);
	}
	$arrJson['status'] = "1";
	$arrJson['msg'] = $msg;
	JsonEnd($arrJson);
}

function shipdate_delete() {
	global $db, $conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$sql = "DELETE FROM shipdate WHERE  id='$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
	}
	else
	{
		$arrJson['status'] = "1";
		$arrJson['msg'] = "刪除成功";
	}
		
	JsonEnd($arrJson);
}

function operate(){
	global $db,$tablename,$conf_user;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		JsonEnd(array("status"=>0,"msg"=>"沒有選擇項目"));
	}
	$id=implode(",",$idarr);
	$field="";
	if($action==1){
		$field="publish='1'";
		$sql="update shipdate set $field where id in ($id)";
	}else if($action==2){
		$field="publish='0'";
		$sql="update shipdate set $field where id in ($id)";
	}else if($action==3){
		$sql="delete from shipdate where id in ($id);";
	}
	
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>"操作成功"));
}

include( $conf_php.'common_end.php' ); 
?>