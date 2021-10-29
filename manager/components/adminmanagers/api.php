<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename = "adminmanagers";

userPermissionChk($tablename);

switch ($task) {
	
	case "list": 
		adminmanagers_list();
		break;
	case "detail": 
		adminmanagers_detail();
		break;
	case "add": 
	case "update": 
		adminmanagers_update();
		break;
	case "del": 
		adminmanagers_delete();
		break;
	case "getfunctionslist":
		getfunctionslist();
		break;
	case "operate":
		operate();
		break;
	case "lockedChg":	
		lockedChg();
		break;

}

function adminmanagers_list()
{
	global $db,$tablename, $globalConf_list_limit,$globalConf_dbtype;
	
	$cur = global_get_param( $_REQUEST, 'page', null);
	$search = global_get_param( $_REQUEST, 'search', null);
	
	$arrJson = array();
	if($search) {
		$where_str  = "";
		$where_str .= " OR A.name like '%$search%' ";
		$where_str .= " OR A.loginid like '%$search%' ";
		$where_str .= " OR A.sid like '%$search%' ";
		$where_str .= " OR A.mobile like '%$search%' ";
		$where_str .= " OR A.email like '%$search%' ";
		if($where_str)
		{
			$where_str = "AND ( 1<>1 $where_str )";
		}
	}
	
	$where_str .= " AND id <> '3' ";
		
	$sql = " SELECT count(id) AS cnt FROM $tablename A WHERE 1=1 $where_str ";	
	$db->setQuery( $sql );
	$cntinfo = $db->loadRow();
	$cnt = $cntinfo['cnt'];
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	$sql = "SELECT * FROM $tablename A WHERE 1=1 $where_str";
	if($globalConf_dbtype == 'mysql')
	{
		$sql .= " LIMIT ".$from.",".$globalConf_list_limit;	
	}
	elseif($globalConf_dbtype == 'sqlsrv' || $globalConf_dbtype == 'mssql')
	{
		$tblsql = " SELECT row_number() OVER (ORDER BY mtime DESC ) as RowNum,A.*  ".
			" FROM $tablename A WHERE 1=1 $where_str ";
		$sql = "SELECT tbl.* from ($tblsql) AS tbl WHERE 1=1 AND RowNum BETWEEN $from AND $end";	
	}
		
	$db->setQuery( $sql );
	$list = $db->loadRowList();
	
	$returnArray = array();
	if(count($list) > 0)
	{
		foreach($list as $row)
		{
			if(empty($row['mobile']))
			{
				$row['mobile'] = "";
			}
			if(empty($row['email']))
			{
				$row['email'] = "";
			}
			
			$returnArray[] = $row;
		}
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;
	$arrJson['cnt'] = $pagecnt;
	
	
	JsonEnd($arrJson);
}

function adminmanagers_detail(){
	global $db,$tablename,$conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );

	$sql = "SELECT id, rootFlag,locked,loginid,sid,name,depTitle,mobile,email,loginCnt,functionsCht,orderExport FROM $tablename WHERE id = '$id'";
	
	$db->setQuery( $sql );
	$r = $db->loadRow();	
	
	if(count($r)>0)
	{
		$info = array();
		foreach($r as $key=>$row)
		{
			if(!is_numeric($key))
			{
				if($key == 'depTitle' && empty($row))
				{
					$row = '';
				}
				if($key == 'mobile' && empty($row))
				{
					$row = '';
				}
				if($key == 'email' && empty($row))
				{
					$row = '';
				}
				if($key == 'loginCnt' && empty($row))
				{
					$row = '0';
				}
				if($key == 'orderExport' && empty($row))
				{
					$row = '0';
				}
				if($key == 'functionsCht')
				{
					$functionsCht = $row;
					continue;
				}
				
				$info[$key] = $row;
			}
		}
		
		$arrJson['info'] = $info;
		
		if(!empty($functionsCht))
		{
			$fun_arr = explode( "|||||" ,$functionsCht);
			$functionsCht_arr = array();
			if(count($fun_arr) > 0)
			{
				foreach($fun_arr as $row)
				{
					if(!empty($row))
					{
						$arr = explode( "|||" ,$row);
						$functionsCht_arr[$arr[0]]['C'] = $arr[1];
						$functionsCht_arr[$arr[0]]['U'] = $arr[2];
						$functionsCht_arr[$arr[0]]['D'] = $arr[3];
						$functionsCht_arr[$arr[0]]['R'] = $arr[4];
					}
				}
			}
			
			$arrJson['functionsCht_arr'] = $functionsCht_arr;
		}
		
		$arrJson['status'] = "1";
	}
	else
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
	}
	
	JsonEnd($arrJson);
	
}


function getfunctionslist() {
	global $db, $tablename, $conf_user,$globalConf_dbtype;
	$arrJson = array();
	
	
	$defaultPageArr = json_decode(file_get_contents("../../permission/1.json"),true);
	$functions_list = array();
	
	if(count($defaultPageArr) > 0)
	{
		foreach($defaultPageArr as $fun=>$row)
		{
			if($row['hide'] == '1' )
			{
				continue;
			}
			elseif(count($row['child']) > 0)
			{
				foreach($row['child'] as $fun2=>$row2)
				{
					$info = array();
					$info['func'] = $fun2;
					$info['name'] = $row2['name'];
					
					if($fun2 == 'siteinfo' || $fun2 == 'indexset' || $fun2 == 'payconfig' || $fun2 == 'pwchg' || $fun2 == 'mainmenu' || $fun2 == 'proinstock')
					{
						$info['C'] = '0';
						$info['U'] = '1';
						$info['D'] = '0';
						$info['R'] = '1';
					}
					else
					{
						$info['C'] = '1';
						$info['U'] = '1';
						$info['D'] = '1';
						$info['R'] = '1';
					}
					
					$functions_list[] = $info;
				}
			}
			else
			{
				$info = array();
				
				$info['func'] = $fun;
				$info['name'] = $row['name'];
				
				if($fun == 'index' || $fun == 'report')
				{
					$info['C'] = '0';
					$info['U'] = '0';
					$info['D'] = '0';
					$info['R'] = '1';
				}
				else if($fun == 'order' || $fun == 'message')
				{
					$info['C'] = '0';
					$info['U'] = '1';
					$info['D'] = '0';
					$info['R'] = '1';
				}
				else
				{
					$info['C'] = '1';
					$info['U'] = '1';
					$info['D'] = '1';
					$info['R'] = '1';
				}
				
				
				$functions_list[] = $info;
			}
		}
		
		$arrJson['functions_list'] = $functions_list;
		$arrJson['status'] = 1;
	}
	else
	{
		$arrJson['status'] = 0;
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
	}
	
	JsonEnd($arrJson);
}


function adminmanagers_update() {
	global $db, $tablename, $conf_user,$globalConf_dbtype,$globalConf_encrypt_1,$globalConf_encrypt_2;
	$arrJson = array();

	$uid = $_SESSION[$conf_user]['uid'];
	$id = global_get_param( $_REQUEST, 'id', null );
	$rootFlag = intval(global_get_param( $_REQUEST, 'rootFlag', null ));
	$locked = intval(global_get_param( $_REQUEST, 'locked', null ));
	$loginid = global_get_param( $_REQUEST, 'loginid', null );
	$passwd = global_get_param( $_REQUEST, 'passwd', null );
	$sid = global_get_param( $_REQUEST, 'sid', null );
	$name = global_get_param( $_REQUEST, 'name', null );
	$depTitle = global_get_param( $_REQUEST, 'depTitle', null );
	$mobile = global_get_param( $_REQUEST, 'mobile', null );
	$email = global_get_param( $_REQUEST, 'email', null );
	$functionsCht = global_get_param( $_REQUEST, 'functionsCht', null );
	
	
	$orderExport = global_get_param( $_REQUEST, 'orderExport', null );
	
	
	if(!empty($id))
	{
		$where_str = " AND id <> '$id'";
	}
	$cnt = getFieldValue( "SELECT COUNT(id) AS cnt FROM $tablename WHERE loginid='$loginid' $where_str", "cnt");
	if($cnt > '0')
	{
		$arrJson['status'] = "0";
		
		$arrJson['msg'] = _ADMINMANAGERS_SAME_USER;
		JsonEnd($arrJson);
	}
	
	$add_str1 = "";
	$add_str2 = "";
	$upd_str = "";
	if(!empty($passwd))
	{
		$passwd = md5($globalConf_encrypt_1.$passwd.$globalConf_encrypt_2);
		
		$add_str1 = " passwd,";
		$add_str2 = " N'{$passwd}' ,";
		$upd_str  = "  passwd=VALUES(passwd) ,";
		
	}
	
	$now = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO $tablename (id, rootFlag, locked, loginid,{$add_str1} sid, name, depTitle, mobile, email, functionsCht, orderExport, ctime, mtime, muser)
			VALUES ('$id', '$rootFlag', '$locked', N'$loginid',{$add_str2} N'$sid', '$name', N'$depTitle', '$mobile', '$email', '$functionsCht', '$orderExport', '$now','$now','$uid')
			ON DUPLICATE KEY UPDATE rootFlag=VALUES(rootFlag),locked=VALUES(locked), loginid=VALUES(loginid),{$upd_str} sid=VALUES(sid),name=VALUES(name),depTitle=VALUES(depTitle),mobile=VALUES(mobile),email=VALUES(email),functionsCht=VALUES(functionsCht), orderExport=VALUES(orderExport),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	
	$msg = $id ? _COMMON_QUERYMSG_UPD_SUS : _COMMON_QUERYMSG_ADD_SUS;
	
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

function adminmanagers_delete() {
	global $db, $tablename, $conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$sql = "DELETE FROM $tablename WHERE  id='$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
	}
	else
	{
		$arrJson['status'] = "1";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_SUS;
	}
		
	JsonEnd($arrJson);
}

function operate(){
	global $db,$conf_user,$tablename;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0 ,1 );
	$idstr = implode(',',$idarr);
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		
		JsonEnd(array("status"=>0, "msg"=>_ADMINMANAGERS_NO_SELECT));
	}
	$field="";
	if($action==1){
		$field="locked=0";
		$sql="update $tablename set $field where id in ($idstr)";
	}else if($action==2){
		$field="locked=1";
		$sql="update $tablename set $field where id in ($idstr)";
	}else if($action==3){
		
		foreach($idarr as $id)
		{
			$arrJson = array();
			$arrJson = delectchk($id);
			if(count($arrJson) > 0)
			{
				JsonEnd($arrJson);
			}
		}
		
		$sql="";
		$sql.="delete from $tablename where id in ($idstr);";
	}
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}

function lockedChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$locked = intval(global_get_param( $_REQUEST, 'locked', null ,0,1  ));
	
	$sql="update $tablename set locked='$locked' where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
	
}

include( $conf_php.'common_end.php' ); 
?>