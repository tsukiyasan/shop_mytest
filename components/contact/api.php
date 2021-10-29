<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$chartType = global_get_param( $_POST, 'chartType', null ,0,1  );
switch ($task) {

	case "update":
		contact_sub();
		break;
	
}

function contact_sub() {
	global $db,$conf_user;
	
	$name = global_get_param( $_POST, 'name', '' ,0,1);
	$phone = global_get_param( $_POST, 'phone', '' ,0,1);
	$email = global_get_param( $_POST, 'email', '' ,0,1);
	$qtype = global_get_param( $_POST, 'type', '' ,0,1);
	$city = intval(global_get_param( $_POST, 'city', '' ,0,1));
	$content = global_get_param( $_POST, 'content', '' ,0,1);
	
	
	if(!$name || !$email || !$content || !$qtype || !$city){
		
		JsonEnd(array("status" => 0, "msg" => _CONTACT_WRITE_MSG));
	}
	
	$uid=$_SESSION[$conf_user]['uid'];
	
	$now = date('Y-m-d H:i:s');
	
	
	$cityStr = getFieldValue(" select name from addrcode WHERE id = '$city' " , "name");
	
	$ip=getIP();
	$sql = "select * from siteinfo";
	$db->setQuery( $sql );
	$siteinfo_arr = $db->loadRow();
	$from = $siteinfo_arr['email'];
	
	$fromname = $siteinfo_arr['name'];
	if($_SESSION[$conf_user]['syslang'])
	{
		$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
	}
	$subject="$fromname "._CONTACT_TITLE;
	$sendto = array(array("email"=>$from,"name"=>$fromname));
	$body = "
		"._CONTACT_NAME."$name<br>
		"._CONTACT_TEL."$phone<br>
		"._CONTACT_EMAIL."$email<br>
		"._CONTACT_TYPE."$type<br>
		"._CONTACT_CITY."$cityStr<br>
		"._CONTACT_MSG."<br>
		".nl2br($content)."
	";
	
	$rs = global_send_mail($email,$name,$sendto,$subject,$body);
	
	$sql = " INSERT INTO receivemessages ( type, title, name, tel, email, memberid, recTime, ip, state, recContent, ctime, mtime, muser, qtype , city) VALUES 
				( 'contact', '$subject', N'$name', '$phone', '$email', '$uid', '$now', '$ip', '1', N'$content', '$now', '$now', '$uid', '$qtype', '$cityStr')";
	
	$db->setQuery( $sql );
	$r=$db->query();
	
	
	JsonEnd(array("status" => 1, "msg" => _CONTACT_SUCCESS_MSG));
	
}



include( $conf_php.'common_end.php' ); 
?>