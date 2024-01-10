<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {
	
	case "pwchg":
		passwordSet();
		break;
}

function passwordSet(){
	global $db,$conf_user,$globalConf_encrypt_1,$globalConf_encrypt_2;
	$arrJson = array();
	
	$sysid = getSysid($_SESSION[$conf_user]['ulevel']);
	$uloginid = $_SESSION[$conf_user]['uloginid'];
	
	$oripwd	= global_get_param( $_REQUEST, 'opw', null, 0, 1, 1, null,   _COMMON_PARAM_PASSWD);	
	$arrJson['oripwd'] = $oripwd;
	$passwd	= global_get_param( $_REQUEST, 'npw', null, 0, 1, 1, null,   _COMMON_PARAM_PASSWD);	
	$arrJson['passwd'] = $passwd;
	$oripwd = md5 ($globalConf_encrypt_1.$oripwd.$globalConf_encrypt_2);
	$arrJson['oripwd2'] = $oripwd;
	$passwd = md5 ($globalConf_encrypt_1.$passwd.$globalConf_encrypt_2);
	$arrJson['passwd2'] = $passwd;
	
	if($sysid == 'admin')
	{
		$sqlChk = "SELECT * FROM adminmanagers WHERE loginid = '$uloginid' AND  passwd='$oripwd'";
		
		$sql = "UPDATE adminmanagers SET passwd='$passwd' WHERE loginid = '$uloginid'";
	}
	else
	{
		$sqlChk = "SELECT * FROM s_adminmanagers WHERE id = '$sysid' AND  passwd='$oripwd'";
		
		$sql = "UPDATE s_adminmanagers SET passwd='$passwd' WHERE id = '$sysid'";
	}
	
	$arrJson['sqlchk'] = $sqlChk;
	$arrJson['sql'] = $sql;

	$db->setQuery( $sqlChk );
	$info_arr = $db->loadRow();	
	$arrJson['session'] = $_SESSION[$conf_user];
	if(count($info_arr) == 0)
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _MEMBER_PWD_ERROR_MSG2;
		JsonEnd($arrJson);
	}
	else
	{
		$db->setQuery( $sql );
		if(!$db->query())
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_QUERYMSG_UPD_ERR;
			JsonEnd($arrJson);
		}
	}
	
	$arrJson['status'] = "1";
	
	$arrJson['msg'] = _COMMON_QUERYMSG_UPD_SUS;
	JsonEnd($arrJson);
}

include( $conf_php.'common_end.php' ); 
?>