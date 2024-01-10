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
case "stock_2023":
	stock_2023();
	break;
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

function stock_2023()
{
	global $db, $db2, $conf_user, $yymm;
	$res = array();
	$data_arr = array();
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	// $mb_no = 'TW020211100160';

	$sql = "SELECT pk_date1 FROM paper_keys WHERE pk_id = ".STOCKDATE." ";
	$db->setQuery($sql);
	$yymm = $db->loadRow();

	$stockDate = strtotime($yymm['pk_date1'] ."01");

	//$stockDate = strtotime(STOCKDATE . '-01');
	//+1month
	$stockDate = strtotime('+ 1 month', $stockDate);
	//-1day
	$stockDate = strtotime('- 1 day', $stockDate);
	$stockDateYm = date('Ym', $stockDate);

	$sql = "SELECT * FROM stock2023 where mb_no = '$mb_no' and yymm = '$stockDateYm' and mb_status = '1'";
	$db2->setQuery($sql);
	$data = $db2->loadRow();
	$stock_total = '0'; //stock8~stock14
	$stock_name = '';
	$stock_num = '';
	$stock_1 = '0';
	$stock_m = '0';
	$stock_pv = '0';
	$stock_sum = '0';
	$pv1 = '0';
	$pv2 = '0';
	$pv1_2 = '0';
	if (!empty($data)) {
		$pv1 = $data['pv1'];
		$pv2 = $data['pv2'];
		$pv1_2 = $data['pv1'] + $data['pv2'];

		if (!empty($data['stock8'])) {
			$stock_name = '明珠級代理店';
			$stock_num = '3';
			$stock_total = $data['stock8'];
		}
		if (!empty($data['stock9'])) {
			$stock_name = '翡翠級代理店';
			$stock_num = '5';
			$stock_total = $data['stock9'];
		}
		if (!empty($data['stock10'])) {
			$stock_name = '鑽石級代理店';
			$stock_num = '8';
			$stock_total = $data['stock10'];
		}
		if (!empty($data['stock11'])) {
			$stock_name = '雙鑽級代理店';
			$stock_num = '11';
			$stock_total = $data['stock11'];
		}
		if (!empty($data['stock12'])) {
			$stock_name = '三鑽級代理店';
			$stock_num = '14';
			$stock_total = $data['stock12'];
		}
		if (!empty($data['stock13'])) {
			$stock_name = '皇冠級代理店';
			$stock_num = '17';
			$stock_total = $data['stock13'];
		}
		if (!empty($data['stock14'])) {
			$stock_name = '皇冠大使級代理店';
			$stock_num = '20';
			$stock_total = $data['stock14'];
		}
		$stock_sum = $data['stock_pv'] + $data['stock_1'] + $data['stock_m'] + $stock_total;
		$stock_pv = $data['stock_pv'];
		$stock_1 = $data['stock_1'];
		$stock_m = $data['stock_m'];
	}
	$data_arr['pv1'] = $pv1;
	$data_arr['pv2'] = $pv2;
	$data_arr['pv1_2'] = $pv1_2;
	$data_arr['stock_total'] = $stock_total;
	$data_arr['stock_name'] = $stock_name;
	$data_arr['stock_num'] = $stock_num;
	$data_arr['stock_sum'] = $stock_sum;
	$data_arr['stock_pv'] = $stock_pv;
	$data_arr['stock_1'] = $stock_1;
	$data_arr['stock_m'] = $stock_m;
	$res['data'] = $data_arr;
	$res['stockDateYm'] = $stockDateYm;
	$res['stock_year'] = '2023';
	//$res['e_date'] = '2021-08-31';
	$res['stock_date'] = date("Y-m-d", $stockDate);
	$res['stockDate'] = date("Y-m-d", $stockDate);
	$res['status'] = '1';
	JsonEnd($res);
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
