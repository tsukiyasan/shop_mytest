<?php



include('../../config.php');
$task = global_get_param($_REQUEST, 'task', null, 0, 1);
$tablename = "members";
$returnData = array(
	'status' => '0',
	'data' => array(),
	'msg' => ''
);
session_set_cookie_params(["SameSite" => "Strict"]);

switch ($task) {
case "spouse_add":
	spouse_add();
	break;
case "spouse_chk":
	spouse_chk();
	break;
case "sid_chk":
	sid_chk();
	break;
}

function mobileChk($str)
{
	if (strlen($str) == 9) {
		$str = '0' . $str;
	}
	return $str;
}


function spouse_add()
{
	global $db,$db2,$sql,$sql2, $conf_user, $tablename, $globalConf_signup_ver2020, $conf_members, $chkcd;
	$uid = LoginChk();
	$ERPID = getFieldValue("SELECT * FROM members where id = '$uid'", "ERPID");

	$res = array();

	$ms_set = global_get_param($_POST, 'ms_set', null, 0, 1);
	if(!$ms_set){
		$ms_set="0";
	}

	$ms_erpid = global_get_param($_POST, 'ms_erpid', null, 0, 1);
	if(!$ms_erpid){
		$ms_erpid=$ERPID.'C';
	}
	$now = date("Y-m-d H:i:s");
	$date = date("Y-m-d");

	$ms_name = global_get_param($_POST, 'ms_name', null, 0, 1);
	$ms_cellphone = global_get_param($_POST, 'ms_cellphone', null, 0, 1);
	$ms_cellphone = mobileChk($ms_cellphone);
	$ms_phone = global_get_param($_POST, 'ms_phone', null, 0, 1);
	$ms_phone = mobileChk($ms_phone);
	$ms_birthday = global_get_param($_POST, 'ms_birthday', null, 0, 1);
	$ms_birthday = date("Y-m-d",strtotime($ms_birthday));
	$ms_relation = global_get_param($_POST, 'ms_relation', null, 0, 1);
	$ms_sid = global_get_param($_POST, 'ms_sid', null, 0, 1);

	$sql_chkms = "SELECT *,count(*) AS countrow FROM members_spouse WHERE m_id = '".$uid."' And ms_status = 1";
	$db->setQuery($sql_chkms);
	$chkms = $db->loadRow();
	
	if($chkms['countrow'] == 0){
	$sql="INSERT into members_spouse 
	(ms_name,ms_cellphone,ms_phone,ms_set,ms_birthday,ms_sid,ms_erpid,m_id,ms_relation,ms_status,ms_note,updatetime)
	values 
	('$ms_name','$ms_cellphone','$ms_phone','$ms_set','$ms_birthday','$ms_sid','$ms_erpid',$uid,'$ms_relation',1,'spouse_add','$now');";
	}else{
	$sql="UPDATE members_spouse 
	SET ms_name = '$ms_name', ms_cellphone = '$ms_cellphone', ms_phone = '$ms_phone',ms_set = '$ms_set', ms_birthday = '$ms_birthday', ms_sid ='$ms_sid', ms_erpid = '$ms_erpid', ms_relation = '$ms_relation', ms_status = 1, ms_note = 'spouse_add', updatetime = '$now'
	WHERE m_id = $uid ";
	}
	$db->setQuery($sql);
	$res['data'] =$db->query();


	$sql_chkcd = "SELECT * FROM couple_data WHERE ERPID = '".$ERPID."'";
	$db2->setQuery($sql_chkcd);
	$chkcd = $db2->loadRow();

	if ($chkcd['status'] != 1) {	
	$sql2="INSERT into couple_data
	(ERPID,userNo,userName,cell,tel,Shoes,userID,userbirthday,relationship,status,createtime,createUser,updatetime,updateUser,data_from,filing_date)
	values 
	('".$ERPID."','".$ms_erpid."','".$ms_name."','".$ms_cellphone."','".$ms_phone."',".$ms_set.",'".$ms_sid."','".$ms_birthday."','".$ms_relation."','1','".$now."','shop','','','shop','".$date."');";
	}else{
		$sql2="UPDATE couple_data
		SET userNo = '".$ms_erpid."', userName = '".$ms_name."', cell = '".$ms_cellphone."', tel = '".$ms_phone."', Shoes = ".$ms_set.", userID = '".$ms_sid."', userbirthday = '".$ms_birthday."', relationship = '".$ms_relation."', updatetime = '".$now."'
		WHERE ERPID = '".$ERPID."'";
	}

	$db2->setQuery($sql2);
	$db2->query();

	
	$sqldb2log="INSERT into couple_data_log SELECT * FROM couple_data WHERE ERPID='".$ERPID."' AND userName='".$ms_name."' AND userID='".$ms_sid."';";

	$db2->setQuery($sqldb2log);
	$db2->query();

	$img1 = global_get_param($_POST, 'img1', null);
	$img2 = global_get_param($_POST, 'img2', null);

		if (count($img1) > 0){
			foreach ($img1 as $key => $value) {
				if ($value) {

					$newtablename='newpic';//處理新圖
					$newpicid = getFieldValue("SELECT mp_id FROM member_pics WHERE  ERPID='".$ms_erpid."' and mp_title='spousebossid_p' ", "mp_id");

					if(!$newpicid){
						$newpicid =getFieldValue("SHOW TABLE STATUS LIKE 'member_pics';", "Auto_increment");
					}					
					
					$path = $newpicid . "C_p" . $key . ".jpg";
					imgupd($value, $conf_members . $path, $newtablename, $newpicid, $key);

					$chkimg1 = "SELECT * FROM member_pics WHERE ERPID='$ms_erpid' AND mp_title='spousebossid_p'";//設定sql語句
					$db->setQuery($chkimg1);//設定sql語句
					$chkimg1num =$db->loadRow();//執行語句
 
					if(count($chkimg1num)<1){
						$img1 = "INSERT INTO member_pics (ERPID, mp_title,mp_pic,updatetime) VALUES ('$ms_erpid', 'spousebossid_p', '$path','$now')";
					}elseif(count($chkimg1num)>=1){
						$img1 = "UPDATE member_pics SET mp_pic='$path',updatetime='$now' WHERE ERPID='$ms_erpid' AND mp_title='spousebossid_p'";
					}

					$db->setQuery($img1);
					$db->query();

				}
			}
		}

		if (count($img2) > 0) {
			foreach ($img2 as $key => $value) {
				if ($value) {

					$newtablename='newpic';//處理新圖
					$newpicid = getFieldValue("SELECT mp_id FROM member_pics WHERE  ERPID='$ms_erpid' and mp_title='spousebossid_n' ", "mp_id");

					if(!$newpicid){
						$newpicid =getFieldValue("SHOW TABLE STATUS LIKE 'member_pics';", "Auto_increment");
					}

					$path = $newpicid . "C_n" . $key . ".jpg";
					imgupd($value, $conf_members . $path, $newtablename, $newpicid, $key);

					

					$chkimg2 = "SELECT * FROM member_pics WHERE ERPID='$ms_erpid' AND mp_title='spousebossid_n'";//設定sql語句
					$db->setQuery($chkimg2);//設定sql語句
					$chkimg2num =$db->loadRow();//執行語句

					if(count($chkimg2num)< 1){
						$img2 = "INSERT INTO member_pics (ERPID, mp_title,mp_pic,updatetime) VALUES ('$ms_erpid', 'spousebossid_n', '$path','$now')";
					}elseif(count($chkimg2num)>=1){
						$img2 = "UPDATE member_pics SET mp_pic='$path',updatetime='$now' WHERE ERPID='$ms_erpid' AND mp_title='spousebossid_n'";
					}

					$db->setQuery($img2);
					$db->query();
				}
			}
		}
	
		$status = 1;
		$msg = '註冊成功';

	// $status = '100';
	JsonEnd(array("status" => $status, "msg" => $msg, "res" => $res));
}

function sid_chk(){
	global $db, $sql, $data, $erpid;
	
	$ms_sid = global_get_param($_REQUEST, 'ms_sid', null);
	$data = getFieldValue("SELECT * FROM  members_spouse WHERE ms_sid = '$ms_sid' ", "ms_sid");

	If($data != ""){
		$msg = '此會員已綁定配偶';
		$status = 1;
	}else{
		$status = 0;
	}
	JsonEnd(array("status" => $status, "msg" => $msg));
}

function spouse_chk(){
	global $db2, $sql2, $data, $erpid;
	$uid = LoginChk();
	$m_ERPID = getFieldValue("SELECT * FROM members where id = '$uid'", "ERPID");

	$erpid = global_get_param($_REQUEST, 'id', null);
	If($erpid != $m_ERPID){
		$sql2="SELECT mb_name, birthday2, tel2, tel3, boss_id
		FROM  mbst 
		WHERE mb_no = '$erpid' ";
		$db2->setQuery($sql2);
		$data = $db2->loadRow();
		if ($data != NULL) {
			$msg = '查詢成功';
			$status = 1;
		}else{
			$msg = '查無此會員資料';
			$status = 0;
		}
	}else{
		$msg = '配偶不能填寫自己';
		$status = 0;
	}

	JsonEnd(array("status" => $status, "msg" => $msg, "data" => $data));


}


include($conf_php . 'common_end.php');
