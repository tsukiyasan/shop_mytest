<?php
include_once( '../common_start.php' ); 

$task = global_get_param( $_REQUEST, 'task', null ,0,1  );

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
}


function adminLogin($tableName = 'adminmanagers')
{
	global $db,$globalConf_encrypt_1,$globalConf_encrypt_2,$root_admin ,$root_store,$conf_user;
	
	$arrJson = array();
	
	
	
	
	$loginid = global_get_param( $_REQUEST, 'loginid', null, 0, 1, 1, null, _COMMON_PARAM_LOGINID);	
	$passwd	= global_get_param( $_REQUEST, 'passwd', null, 0, 1, 1, null,   _COMMON_PARAM_PASSWD);	
	$checkcode	= global_get_param( $_REQUEST, 'checkcode', null, 0, 1, 1, null,   _COMMON_PARAM_CHECKCODE);	
	$remember = global_get_param( $_REQUEST, 'remember', null );	
	
	
	

	
	
	if ( strtolower ($checkcode) != strtolower ($_SESSION['checkcode']) or $checkcode==null or $_SESSION['checkcode']==null )
	{
		$arrJson['status'] = "0";
		$arrJson['errMsg'] = _COMMON_QUERYMSG_LOGIN_CHECKCODE_ERROR;		
		JsonEnd($arrJson);
	}
	
	
	if(!empty($remember) && $remember == 'checked')
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
	
	$passwd = md5 ($globalConf_encrypt_1.$passwd.$globalConf_encrypt_2);
	$sql = "SELECT * FROM $tableName WHERE lockedPcode=0 AND loginid='$loginid' AND passwd='$passwd'";
	$db->setQuery( $sql );
	$info_arr = $db->loadRow();	
	
	if(empty($info_arr) || count($info_arr) == '0')
	{
		$arrJson['status'] = "0";
		$arrJson['errMsg'] = _COMMON_QUERYMSG_LOGIN_ERROR;		
		JsonEnd($arrJson);
	}
	if($info_arr['rootFlag'] == '0'){
		$arrJson['ulevel'] = $root_admin;
		$sysCode = 'root';
		
	}else{
		$arrJson['ulevel'] = $root_store;
		$sysCode = 's_'.$info_arr['sysCode'];
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
	
	JsonEnd($arrJson);
	
}


function adminLogout(){
	global $db,$conf_user;
	$arrJson = array();
		
	$id = intval(global_get_param( $_REQUEST, 'id', null, 0, 1, 1, 'int', _COMMON_PARAM_ID));
	
	if($_SESSION[$conf_user]['uid'] == $id){
		unset($_SESSION[$conf_user]);
		$arrJson['status'] = "1";
	}else{
		$arrJson['status'] = "0";
		$arrJson['errMsg'] = _COMMON_ERRORMSG_LOGINOUT_ERR;		
	}
	
	JsonEnd($arrJson);
}


function adminInfo($tableName = 'adminmanagers'){
	global $db,$conf_user;
	$arrJson = array();
	$id = intval(global_get_param( $_REQUEST, 'id', null, 0, 1, 1, 'int', _COMMON_PARAM_ID));

	$sql = "SELECT * FROM $tableName WHERE lockedPcode=0 AND id='{$_SESSION[$conf_user]['uid']}'";
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
	
	$arrJson['loginInfo'] = $loginid;
	$arrJson['passwdInfo'] = $passwd;
	
	JsonEnd($arrJson);
}

?>