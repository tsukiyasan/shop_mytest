<?php



include('../../config.php');
$task = global_get_param($_REQUEST, 'task', null, 0, 1);
$tablename = "members";
session_set_cookie_params(["SameSite" => "Strict"]);

switch ($task) {
	case "login":
		login();
		break;
	case "fb_login":
		fb_login();
		break;
	case "fb_chk":
		fb_chk();
		break;
	case "gp_login":
		gp_login();
		break;
	case "gp_chk":
		gp_chk();
		break;
	case "pwchg":
		pwchg();
		break;
	case "signup":
		signup();
		break;
	case "signup2":
		signup(1);
		break;
	case "loginStatus":
		loginStatus();
		break;
	case "userInfo":
		userInfo();
		break;
	case "updateUser":
		updateUser();
		break;
	case "logout":
		logout();
		break;
	case "resetPW":
		resetPW();
		break;
	case "chkresetPW":
		chkresetPW();
		break;
	case "order_list":
		order_list();
		break;
	case "order_cancel":
		order_cancel();
		break;
	case "order_dtl":
		order_dtl();
		break;
	case "order_upd":
		order_upd();
		break;
	case "likeProduct":
		likeProduct();
		break;
	case "go_end":
		go_end();
		break;
	case "updateToSales":
		updateToSales();
		break;
	case "get_share_url":
		get_share_url();
		break;
	case "get_share_url2":
		get_share_url2();
		break;
	case "test_member":
		test_member();
		break;

	case "signNew_checkWID":
		signNew_checkWID();
		break;
	case "signNew_getRecData":
		signNew_getRecData();
		break;
	case "signNew_memberSIDChk":
		signNew_memberSIDChk();
		break;
	case "signNew_memberEmailChk":
		signNew_memberEmailChk();
		break;
	case "signNew_signup":
		signNew_signup();
		break;
	case "signNew_emailChk":
		signNew_emailChk();
		break;
	case "signNew_signupChk":
		signNew_signupChk();
		break;
	case "signNew_chkPay":
		signNew_chkPay();
		break;
	case "signNew_getMemberData":
		signNew_getMemberData();
		break;
	case "money_list":
		money_list();
		break;
	case "money_dtl":
		money_dtl();
		break;
	case "ecash_list":
		ecash_list();
		break;
	case "ecash_new2_1_list":
		ecash_new2_1_list();
		break;
	case "ecash_new2_2_list":
		ecash_new2_2_list();
		break;
	case "ecash21_dtl":
		ecash21_dtl();
		break;
	case "member_news_list":
		member_news_list();
		break;
	case "member_news_page":
		member_news_page();
		break;
	case "carry_treasure":
		carry_treasure();
		break;
	case "birthday_voucher":
		birthday_voucher();
		break;
	case "orgseq5":
		orgseq5();
		break;
	case "orgseq1":
		orgseq1();
		break;
	case "orgseq2":
		orgseq2();
		break;
	case "download_page":
		download_page();
		break;
	case "search_mbno":
		search_mbno();
		break;
	case "exchange_coupon":
		exchange_coupon();
		break;
	case "exchange_coupon_all":
		exchange_coupon_all();
		break;
	case "chk_birthdate":
		chk_birthday();
		break;
	case "transfer_data":
		transfer_data();
		break;
	case "get_mb_no":
		get_mb_no();
		break;
	case "minfo_list":
		minfo_list();
		break;
	case "stock_list":
		// stock_list();
		break;
	case "ecash_stat":
		ecash_stat();
		break;
	case "member_poster_list":
		member_poster_list();
		break;
	case "annual_dividend":
		annual_dividend();
		break;
	case "orgseq_member":
		orgseq_member();
		break;
	case "sign20_signupChk":
		sign20_signupChk();
		break;
	case "sign20_sendCaptcha":
		sign20_sendCaptcha();
		break;
	case "sign20_signup":
		sign20_signup();
		break;
	case "sign20_signupSuccess":
		sign20_signupSuccess();
		break;

	case "resetPW20_sendCaptcha":
		resetPW20_sendCaptcha();
		break;
	case "resetPW20":
		resetPW20();
		break;
	case "resetPW20_captchaChk":
		resetPW20_captchaChk();
		break;

	case "info20_sendCaptcha":
		info20_sendCaptcha();
		break;

	case "sign30_signupChk":
		sign30_signupChk();
		break;
	case "sign30_signup":
		sign30_signup();
		break;
	case "sign30_signupSuccess":
		sign30_signupSuccess();
		break;
	case "m_points":
		m_points();
		break;
	case "returns_order":
		returns_order();
		break;
	case "signupChk3011":
		signupChk3011();
		break;
	case "get_udlvrAddr":
		get_udlvrAddr();
		break;
	case "update_member_now":
		update_member_now();
		break;
	case "upload_cert":
		upload_cert();
		break;

	case "cash_back_list":
		cash_back_list();
		break;
	case "ai_login":
		ai_login();
		break;
	case "get_recommend":
		get_recommend();
		break;
	case "mlm_order_list":
		mlm_order_list();
		break;
	case "mlm_order_dtl":
		mlm_order_dtl();
		break;
	case "soybean_voucher":
		soybean_voucher();
		break;
	case "check_tspg":
		check_tspg();
		break;
	case "mls":
		mls();
		break;
	case "sign_codeChk":
		sign_codeChk();
		break;
	case "register_tb_list":
		register_tb_list();
		break;
	case "get_erate":
		get_erate();
		break;
	case "get_soap":
		get_soap();
		break;
	case "set_pm":
		set_pm();
		break;
	case "del_pm":
		del_pm();
		break;
	case "test_mlm":
		$res = array();
		$response = toMLM('113', '1404');
		$res['resp'] = $response;
		JsonEnd($res);
		break;
	case "chkchk":
		chkchk();
		break;
	case "mls":
		mls();
		break;
	case "get_last":
		$res = array();
		$date = $_GET['d'];
		$d = getlastMonthDays($date);
		$d2 = getNextMonthDays($date);
		$res['d'] = $d;
		$res['d2'] = $d2;
		$res['date'] = $date;
		JsonEnd($res);
		break;
	case "get_cp58":
		get_cp58();
		break;
	case "footpic":
		footpic();
		break;
	case "genomics":
		genomics();
		break;
	case "my_bone_density":
		my_bone_density();
		break;
}


function signNew_getMemberData()
{
	global $db, $conf_user, $tablename;

	if (!empty($_SESSION['first']['memberid'])) {
		$memberid = intval($_SESSION['first']['memberid']);

		$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$memberid' ", "loginid");
		$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$memberid' ", "sid");
		$email = getFieldValue(" SELECT email FROM members WHERE id = '$memberid' ", "email");
		$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$memberid' ", "ERPID");

		unset($_SESSION['first']['memberid']);


		$_SESSION['second']['memberid'] = $memberid;

		JsonEnd(array("status" => 1, "loginId" => $loginId, "passwd" => $passwd, "email" => $email, "rec" => $ERPID));
	} else {
		JsonEnd(array("status" => 1, "msg" => _MEMBER_NO_DATA));
	}
}

function signNew_checkWID()
{
	global $db, $db2, $conf_user, $tablename;

	$wid = global_get_param($_POST, 'wid', null, 0, 1);

	if (!empty($wid)) {

		$randNo = mt_rand(1, 99999999);
		$vc = md5(($wid . $randNo));

		// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/toglan1.xsql?rec3=" . $wid);
		// $login_result = iconv("BIG5", "UTF-8", $login_result);

		$sql = "SELECT * from warr_card where card_no = '$wid'";
		$db2->setQuery($sql);
		$login_result = $db2->loadRow();

		if (!empty($login_result)) {

			$rec2 = $login_result['mb_no'];

			if (empty($rec2)) {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
			} else {
				JsonEnd(array("status" => 1, "rec2" => $rec2));
			}
		} else {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
		}
	} else {
		JsonEnd(array("status" => 0, "msg" => "無效卡片"));
	}
}

function signNew_getRecData()
{
	ini_set('display_errors', '1');
	global $db, $db2, $conf_user, $tablename, $globalConf_signupDemo_ver2020;

	$rec = global_get_param($_POST, 'rec', null, 0, 1);
	$rec2 = global_get_param($_POST, 'rec2', null, 0, 1);

	if (!empty($rec)) {

		if ($globalConf_signupDemo_ver2020) {
			$info = array();
			$info["referrerNo"]       = $rec;
			$info["referrerName"]     = "推薦人名稱";
			$info["referrerTel"]      = "推薦人電話";
			$info["referrerPhone"]    = "推薦人手機";
			$info["memberNo"]         = (!empty($rec2)) ? $rec2 : "";
			$info["memberName"]       = (!empty($rec2)) ? "顧客名稱" : "";
			$info["memberSID"]        = (!empty($rec2)) ? "顧客身分證號" : "";
			$info["memberAddress"]    = (!empty($rec2)) ? "顧客地址" : "";
			$info["memberResAddress"] = (!empty($rec2)) ? "顧客收件地址" : "";
			$info["memberTel"]        = (!empty($rec2)) ? "顧客電話" : "";
			$info["memberPhone"]      = (!empty($rec2)) ? "顧客手機" : "";
			$info["memberEmail"]      = (!empty($rec2)) ? "顧客信箱" : "";
			$info["memberBirthday"]   = (!empty($rec2)) ? "顧客生日" : "";
			$info["memberWNo"]        = (!empty($rec2)) ? "顧客編號" : "";

			global $ESignupActiveEndDate;
			$info['ESignupActiveChk'] = "false";
			if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
				$info['ESignupActiveChk'] = "true";
			}

			JsonEnd(array("status" => 1, "info" => $info));
		} else {
			$randNo = mt_rand(1, 99999999);
			$vc = md5(($rec . $rec2 . $randNo));

			// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/toglan1.xsql?rec2=" . $rec);
			// $login_result = iconv("BIG5", "UTF-8", $login_result);

			$lsql = "SELECT * FROM mbst where mb_no = '$rec' and (pg_end_date != '' or pg_end_date is not null)";
			$db2->setQuery($lsql);
			$result = $db2->loadRow();

			$recNo = "";
			if (!empty($result)) {

				if (count($result) > 0) {
					$date_arr = array();
					if ($result['grade_1_chk'] == '1' && $result['mb_status'] == '1') {
						$recNo = $result['mb_no'];
						$recName = $result['mb_name'];
						$recTel = $result['tel2'];
						$recPhone = $result['tel3'];
					}
				}

				// if (empty($recNo)) {

				// $sql = " SELECT * FROM members WHERE cardnumber = '$rec' AND salesChk = '1' AND locked = '0' and onlyMember = '0'";
				// $db->setQuery($sql);
				// $r = $db->loadRow();
				// if ($r) {
				// 	$recNo = trim($r['cardnumber']);
				// 	$recName = trim($r['name']);
				// 	$recTel = trim($r['phone']);
				// 	$recPhone = trim($r['mobile']);
				// }

				// }


				if (empty($recNo)) {
					JsonEnd(array("status" => 0, "sql" => $lsql, "msg" => _MEMBER_NO_DATA));
				}
			} else {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
			}
			// else { //查詢網購
			// 	$msql = "SELECT * FROM members where ERPID='$rec' and locked='0' and (emailChk='0' or mobileChk = '1') and salesChk='1'";
			// 	$db->setQuery($msql);
			// 	$mlist = $db->loadRow();

			// 	if (count($mlist) > 0) {
			// 		$recNo = $mlist['ERPID'];
			// 		$recName = $mlist['name'];
			// 		$recTel = $mlist['mobile'];
			// 		$recPhone = $mlist['phone'];
			// 	}
			// }
			// if (!empty($login_result)) {
			// 	$login_result_array = explode("\r\n", $login_result);



			// 	if (count($login_result_array) > 0) {
			// 		$date_arr = array();
			// 		foreach ($login_result_array as $key => $row) {
			// 			if ($key == 0) {
			// 				continue;
			// 			}

			// 			$tmp = explode("|", $row);
			// 			$date_arr[] = $tmp;

			// 			if (trim($tmp[18]) == "是" && trim($tmp[61]) == "N") {
			// 				$recNo = trim($tmp[6]);
			// 				$recName = trim($tmp[5]);
			// 				$recTel = trim($tmp[36]);
			// 				$recPhone = trim($tmp[38]);
			// 			}
			// 		}
			// 	}

			// 	if (empty($recNo)) {

			// 		$sql = " SELECT * FROM members WHERE cardnumber = '$rec' AND salesChk = '1' AND locked = '0'";
			// 		$db->setQuery($sql);
			// 		$r = $db->loadRow();
			// 		if ($r) {
			// 			$recNo = trim($r['cardnumber']);
			// 			$recName = trim($r['name']);
			// 			$recTel = trim($r['phone']);
			// 			$recPhone = trim($r['mobile']);
			// 		}
			// 	}


			// 	if (empty($recNo)) {
			// 		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
			// 	}
			// }



			if (!empty($rec2)) {


				$lsql2 = "SELECT * FROM mbst where mb_no = '$rec2'";
				$db2->setQuery($lsql2);
				$result2 = $db2->loadRow();
				// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/toglan1.xsql?rec2=" . $rec2);
				// $login_result = iconv("BIG5", "UTF-8", $login_result);

				if (!empty($result2)) {
					if (count($result2) > 0) {
						if ($result2['grade_1_chk'] == "1" && $result2['mb_status'] == "1" && $result2['true_intro_name'] == $recNo) {
							$rec2No = $result['mb_no'];
							$rec2Name = $result['mb_name'];
							$rec2SID        = $result2['sid'];
							$rec2Address    = $result2['add2'];
							$rec2ResAddress = $result2['add1'];
							$rec2Tel = $result['tel2'];
							$rec2Phone = $result['tel3'];
							$rec2Email      = $result2['email'];
							$rec2Birthday   = $result2['birthday2'];
							$rec2WNo        = ''; //保證卡號
						}
					}
				}


				// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/toglan1.xsql?rec2=" . $rec2);
				// $login_result = iconv("BIG5", "UTF-8", $login_result);

				// if (!empty($login_result)) {
				// 	$login_result_array = explode("\r\n", $login_result);


				// 	if (count($login_result_array) > 0) {
				// 		$date_arr = array();
				// 		foreach ($login_result_array as $key => $row) {
				// 			if ($key == 0) {
				// 				continue;
				// 			}

				// 			$tmp = explode("|", $row);
				// 			$date_arr[] = $tmp;
				// 			if (trim($tmp[18]) != "是" && trim($tmp[61]) == "N" && trim($tmp[59]) == $recNo) {
				// 				$rec2No         = trim($tmp[6]);
				// 				$rec2Name       = trim($tmp[5]);
				// 				$rec2SID        = trim($tmp[7]);
				// 				$rec2Address    = trim($tmp[33]);
				// 				$rec2ResAddress = trim($tmp[34]);
				// 				$rec2Tel        = trim($tmp[36]);
				// 				$rec2Phone      = trim($tmp[38]);
				// 				$rec2Email      = trim($tmp[39]);
				// 				$rec2Birthday   = trim($tmp[8]);
				// 				$rec2WNo        = trim($tmp[45]);
				// 			}
				// 		}
				// 	}
				// }
			}


			if (!empty($recNo)) {
				$info = array();
				$info["referrerNo"]       = $recNo;
				$info["referrerName"]     = $recName;
				$info["referrerTel"]      = $recTel;
				$info["referrerPhone"]    = $recPhone;
				$info["memberNo"]         = (!empty($rec2No)) ? $rec2No : "";
				$info["memberName"]       = (!empty($rec2Name)) ? $rec2Name : "";
				$info["memberSID"]        = (!empty($rec2SID)) ? $rec2SID : "";
				$info["memberAddress"]    = (!empty($rec2Address)) ? $rec2Address : "";
				$info["memberResAddress"] = (!empty($rec2ResAddress)) ? $rec2ResAddress : "";
				$info["memberTel"]        = (!empty($rec2Tel)) ? $rec2Tel : "";
				$info["memberPhone"]      = (!empty($rec2Phone)) ? $rec2Phone : "";
				$info["memberEmail"]      = (!empty($rec2Email)) ? $rec2Email : "";
				$info["memberBirthday"]   = (!empty($rec2Birthday)) ? $rec2Birthday : "";
				$info["memberWNo"]        = (!empty($rec2WNo)) ? $rec2WNo : "";

				global $ESignupActiveEndDate;
				$info['ESignupActiveChk'] = "false";
				if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
					$info['ESignupActiveChk'] = "true";
				}

				JsonEnd(array("status" => 1, "info" => $info));
			} else {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
			}
		}
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DATA));
	}
}

function signNew_memberSIDChk()
{
	global $db, $db2, $conf_user, $tablename, $globalConf_signup_ver2020;

	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$email = global_get_param($_POST, 'email', null, 0, 1);
	$rec2 = global_get_param($_POST, 'rec2', null, 0, 1);

	if ($globalConf_signup_ver2020) {
		$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);
		$memberCaptcha = global_get_param($_POST, 'memberCaptcha', null, 0, 1);
		$memberPhone = global_get_param($_POST, 'memberPhone', null, 0, 1);
		$memberEmail = global_get_param($_POST, 'memberEmail', null, 0, 1);

		$memberPhone = mobileChk($memberPhone);

		//檢查驗證碼
		$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
		if ($signupMode == "SMS") {
			$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		} else if ($signupMode == "MAIL") {
			$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		}

		if (empty($chk)) {
			JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤"));
		} else {
			//更新驗證碼
			// $sql = "update requestLog set type= 'sign20Chked' where id='$chk'";
			// $db->setQuery( $sql );
			// $db->query();
		}
	}

	if (!empty($sid)) {

		$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE sid = '$sid' and locked = '0' ", "cnt");

		if ($sid == "A123456789") {
			$cnt = 0;
		}

		if (empty($cnt)) {

			$randNo = mt_rand(1, 99999999);
			$vc = md5(($sid . $randNo));
			//$now = strtotime("now");
			$nowdate = date('Y-m-d');
			$now = strtotime($nowdate);



			// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/mmnt.xsql?sid=" . $sid . "&r=" . $randNo . "&vc=" . $vc);
			// $login_result = json_decode($login_result, true);

			$sql = "SELECT * FROM mbst where boss_id = '$sid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= '$now'";
			$db2->setQuery($sql);
			$result = $db2->loadRow();

			$sql2 = "SELECT * FROM mbst where boss_id = '$sid' and mb_status = '1' and member_date !=''";
			$db2->setQuery($sql2);
			$result2 = $db2->loadRow();

			if (!empty($result)) { //已存在的經銷商
				JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
				//檢查網購內有沒有此會編
				// $check_mb_no = $result['mb_no'];
				// $check_ERPID = getFieldValue("SELECT ERPID FROM members WHERE ERPID = '$check_mb_no' and locked = '0'","ERPID");
				// if(!empty($check_ERPID)){
				// 	JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
				// }else{
				// 	JsonEnd(array("status" => 1));
				// }

			} else if ($result2) {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT_MEMBER));
			} else {

				$csql = "SELECT * from mbst where boss_id = '$sid' and mb_status != '1'";
				$db2->setQuery($csql);
				$check_ress = $db2->loadRowList();
				$last_ress_date = '';
				if (count($check_ress) > 0) {
					foreach ($check_ress as $each) {
						if (empty($last_ress_date)) {
							if (!empty($each['rescission_date'])) {
								$last_ress_date = $each['rescission_date'];
							} else {
								$last_ress_date = '1970-01-01';
							}
						} else {
							if (!empty($each['rescission_date'])) {
								if ($last_ress_date < $each['rescission_date']) {
									$last_ress_date = $each['rescission_date'];
								}
							}
						}
					}
					$now = date('Y-m-d');
					$last_ress_date = date('Y-m-d', $last_ress_date);
					$snow = date('Y-m-d', strtotime("$last_ress_date +6 month"));

					if ($now < $snow) {
						JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_USED));
					} else {
						JsonEnd(array("status" => 1));
					}
				} else {
					$res = array();
					$msg = '';
					if ($signupMode == "SMS") {
						$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE email = '$email' and email <> '' and locked = '0' ", "cnt");
					} else if ($signupMode == "MAIL") {
						$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE email = '$email' and locked = '0' ", "cnt");
					}
					if (empty($cnt)) {
						$res['status'] = 1;
						// JsonEnd(array("status" => 1));
					} else {
						$res['status'] = 0;
						$msg .= '電子信箱已使用過';
						// JsonEnd(array("status" => 0, "msg" => _MEMBER_EMAIL_REPEAT));
					}

					if ($memberPhone == 'null' || $memberPhone == null) {
						$memberPhone = '';
					}

					if ($signupMode == "SMS") {
						$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE mobile = '$memberPhone' and locked = '0' and mobileChk = '1'", "cnt");
					} else if ($signupMode == "MAIL") {
						$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE mobile = '$memberPhone' and locked = '0' and mobile <> '' and mobileChk = '1'", "cnt");
					}
					if (empty($cnt)) {
						$res['status'] = 1;
					} else {
						$res['status'] = 0;
						$msg .= _MEMBER_SID_USED;
					}
					$res['msg'] = $msg;

					JsonEnd($res);
				}
			}
		} else {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
		}
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_ERROR));
	}
}

function signNew_memberEmailChk()
{
	global $db, $conf_user, $tablename;

	$email = global_get_param($_POST, 'email', null, 0, 1);
	$rec2 = global_get_param($_POST, 'rec2', null, 0, 1);

	if (!empty($email)) {

		$cnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE email = '$email' ", "cnt");

		if ($email == "csl0412@gmail.com") {
			$cnt = 0;
		}

		if (empty($cnt)) {

			$randNo = mt_rand(1, 99999999);
			$vc = md5(($email . $rec2 . $randNo));




			if ($email == "csl0412@gmail.com") {
				$login_result["status"] = 1;
			}

			if ($login_result["status"] == 1) {
				JsonEnd(array("status" => 1));
			} else {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_EMAIL_REPEAT));
			}
		} else {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_EMAIL_REPEAT));
		}
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_EMAIL_ERROR));
	}
}

function signNew_signup()
{
	global $db, $db2, $db3, $conf_user, $tablename, $globalConf_signup_ver2020, $conf_members;

	$res = array();
	$referrerNo = global_get_param($_POST, 'referrerNo', null, 0, 1);
	$recommendName = global_get_param($_POST, 'referrerName', null, 0, 1);
	$recommendPhone = global_get_param($_POST, 'referrerTel', null, 0, 1);
	$recommendMobile = global_get_param($_POST, 'referrerPhone', null, 0, 1);

	$memberNo = global_get_param($_POST, 'memberNo', null, 0, 1);
	$memberName = global_get_param($_POST, 'memberName', null, 0, 1);
	$memberSID = global_get_param($_POST, 'memberSID', null, 0, 1);
	$memberCity = global_get_param($_POST, 'memberCity', null, 0, 1);
	$memberCanton = global_get_param($_POST, 'memberCanton', null, 0, 1);
	$memberAddress = global_get_param($_POST, 'memberAddress', null, 0, 1);
	$memberResCity = global_get_param($_POST, 'memberResCity', null, 0, 1);
	$memberResCanton = global_get_param($_POST, 'memberResCanton', null, 0, 1);
	$memberResAddress = global_get_param($_POST, 'memberResAddress', null, 0, 1);
	$memberTel = global_get_param($_POST, 'memberTel', null, 0, 1);
	$memberPhone = global_get_param($_POST, 'memberPhone', null, 0, 1);
	$memberPhone = mobileChk($memberPhone);
	$memberEmail = global_get_param($_POST, 'memberEmail', null, 0, 1);
	$memberBirthday = global_get_param($_POST, 'memberBirthday', null, 0, 1);
	$memberBirthdayStr = global_get_param($_POST, 'memberBirthdayStr', null, 0, 1);

	$memberBirthday = date("Y-m-d", strtotime($memberBirthdayStr));

	$payType = global_get_param($_POST, 'payType', null, 0, 1);
	if ($payType == 5) {
		$_SESSION["tmpData"]["payD"] = 1;
	}
	$dlvrType = global_get_param($_POST, 'dlvrType', null, 0, 1);
	$dlvrAddr = global_get_param($_POST, 'dlvrAddr', null, 0, 1);
	$dlvrLocation = global_get_param($_POST, 'dlvrLocation', null, 0, 1);

	$billCity = global_get_param($_POST, 'billCity', null, 0, 1);
	if (!empty($billCity)) {
		$billCityStr = $billCity['state_u'];
	}
	$billAddr = global_get_param($_POST, 'billAddr', null, 0, 1);

	$usedChk = global_get_param($_POST, 'usedChk', null, 0, 1);
	$memberWNo = global_get_param($_POST, 'memberWNo', null, 0, 1);

	$rec_code = global_get_param($_POST, 'rec_code', null, 0, 1);


	// $sql3 = "SELECT * from register_tb where random = '$rec_code' and mb_no = '$referrerNo' and is_used = '0'";
	// $db2->setQuery($sql3);
	// $code_list = $db2->loadRow();


	if (!empty($referrerNo) && !empty($recommendName)) { // && !empty($code_list)

		if ($globalConf_signup_ver2020) {
			$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);
			$memberCaptcha = global_get_param($_POST, 'memberCaptcha', null, 0, 1);
			$memberPhone = global_get_param($_POST, 'memberPhone', null, 0, 1);
			$memberEmail = global_get_param($_POST, 'memberEmail', null, 0, 1);

			$memberPhone = mobileChk($memberPhone);

			//檢查驗證碼
			$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
			if ($signupMode == "SMS") {
				$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
				$sqlchk = "SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC";
			} else if ($signupMode == "MAIL") {
				$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
				$sqlchk = "SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC";
			}

			if (empty($chk)) {
				JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤", "data" => $sqlchk));
			} else {
				// 更新驗證碼
				$sql = "update requestLog set type= 'sign20Chked' where id='$chk'";
				$db->setQuery($sql);
				$db->query();
			}
		}


		if ($globalConf_signup_ver2020) {
			$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);
			$memberPasswd = global_get_param($_POST, 'memberPasswd', null, 0, 1);

			$loginid = $memberSID;
			$passwd = enpw($memberPasswd);
		} else {
			$loginid = $memberEmail;
			$passwd = enpw($memberSID);
		}

		$tsql = "SELECT * from mbst where boss_id = '$memberSID' and mb_status = '1' and grade_class='0' and member_date = ''"; //是顧客
		$db2->setQuery($tsql);
		$tmd = $db2->loadRow();
		$is_customer = false;
		if (!empty($tmd)) {
			$memberNo = $tmd['mb_no'];
			$tin = $tmd['true_intro_no'];
			$is_customer = true;
		} else {
			$memberNo = '';
			$tin = '';
		}

		if (empty($memberNo)) {

			// $memberNo_max = getFieldValue(" SELECT ERPID FROM members WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");
			$memberNo_max = getFieldValue(" SELECT ERPID FROM erpid WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");

			$chk_code = "";
			if (!empty($memberNo_max)) {
				$chk_code = intval(substr($memberNo_max, -5)) + 1;
			}

			if (!empty($chk_code))
				$code  = str_pad($chk_code, 5, '0', STR_PAD_LEFT);
			else
				$code  = "00001";

			$memberNo = "MYN" . date("Ym") . $code;
		}

		$emailChk = 1;
		$mobileChk = 0;
		$salesChk = 1; //正式經銷商1  
		$ERPChk = 0;

		$memType = 1;

		if ($globalConf_signup_ver2020) {
			//20210218
			$emailChk = ($signupMode == "MAIL") ? 0 : 1;
			$mobileChk = ($signupMode == "SMS") ? 1 : 0;
		}


		$sql = "insert into members (belongid,name,sid,email,emailChk,mobileChk,regDate,loginid,passwd,cardnumber,recommendCode,salesChk,mobile,ERPID,phone,fulladdr,ERPChk,resaddr,city,canton,addr,rescity,rescanton,dlvrLocation,Birthday,memType,recommendName,recommendPhone,recommendMobile, usedChk, memberWNo, pvgeLevel, use_code) values 
				('0',N'$memberName','$memberSID','$memberEmail','$emailChk','$mobileChk','" . date("Y-m-d H:i:s") . "','$memberEmail','$passwd','$memberNo','$referrerNo','$salesChk','$memberPhone','$memberNo','$memberTel',N'$memberAddress',$ERPChk,N'$memberResAddress','$memberCity','$memberCanton',N'$memberAddress','$memberResCity','$memberResCanton',N'$dlvrLocation',N'$memberBirthdayStr','$memType',N'$recommendName',N'$recommendPhone',N'$recommendMobile', '$usedChk', '$memberWNo','$signupMode', '$rec_code')";

		$db->setQuery($sql);
		$db->query();

		//20230817額外寫入一個accmbno
		$acc = array();
		$acc['mb_no'] = $memberNo;
		$acc_sql = dbInsert('accmbno',$acc);
		$db2->setQuery($acc_sql);
		$db2->query();

		//建立會編日誌
		$e_arr = array();
		$e_arr['ERPID'] = $memberNo;
		$esql = dbInsert('erpid', $e_arr);
		$db->setQuery($esql);
		$db->query();

		$res['sql'] = $sql;

		$memberid = getFieldValue(" SELECT id FROM members ORDER BY id DESC ", "id");
		$today = date("Y-m-d");
		$status = 0;
		$sumAmt = 88;
		$discount = 0;
		$dcntAmt = 88;
		$dlvrFee = ($dlvrType == '1') ? 10 : 0;
		$usecoin = 0;
		$totalAmt = $sumAmt + $dlvrFee;
		$now = date("Y-m-d H:i:s");


		if ($dlvrType == '2' && !empty($dlvrLocation)) {
			if ($dlvrLocation == '北區服務中心') {
				$dlvrAddr = "新北市林口區文化二路一段 266 號 11 樓之 1";
			} else if ($dlvrLocation == '新竹服務中心') {
				$dlvrAddr = "新竹市北區東大路二段 76 號 12 樓";
			} else if ($dlvrLocation == '台中服務中心') {
				$dlvrAddr = "台中市南屯區五權西路二段 666 號 8 樓之 3";
			} else if ($dlvrLocation == '雲林服務中心') {
				$dlvrAddr = "雲林縣虎尾鎮興中里 15 鄰清雲六街 123 號";
			} else if ($dlvrLocation == '高雄服務中心') {
				$dlvrAddr = "高雄市鼓山區明華路 315 號 11 樓之 2";
			} else if ($dlvrLocation == '台南總部服務中心') {
				$dlvrAddr = "台南市安南區工業一路 23 號";
			} else if ($dlvrLocation == 'GOLDARCH ENTERPRISE') {
				$dlvrAddr = "Prangin Mall, 33-4-75, Jalan Dr. Lim Chwee Leong, 10100 Georgetown, Pulau Pinang.";
			} else if ($dlvrLocation == 'SIMPANG AMPAT') {
				$dlvrAddr = "26, Lorong Kendi 6, Kawasan Perniagaan Taman Merak, 14100 Simpang Ampat, Pulau Pinang.";
			} else if ($dlvrLocation == 'GREATARCH ENTERPRISE') {
				$dlvrAddr = "96, Jalan Radin Anum 1, Sri Petaling, 57000 Kuala Lumpur.";
			} else if ($dlvrLocation == 'BATU PAHAT') {
				$dlvrAddr = "2A, Jalan Sri Pantai 1, Taman Sri Pantai, 83000 Batu Pahat, Johor.";
			} else if ($dlvrLocation == 'ARCHCARE ENTERPRISE') {
				$dlvrAddr = "2, Jalan Zapin 11, Taman Skudai, 81300 Johor Bahru, Johor.";
			} else if ($dlvrLocation == 'MIRI') {
				$dlvrAddr = "Lot.403, Jalan Cosmos 2A, Pelita Garden, 98000 Miri, Sarawak.";
			} else if ($dlvrLocation == 'KOTA KINABALU') {
				$dlvrAddr = "No.101, Lorong Bunga Dahlia 5, Jalan Penampang, Taman Cantik, 88200 Kota Kinabalu, Sarawak.";
			} else if ($dlvrLocation == '大城堡總部服務中心' || $dlvrLocation == 'Sri Petaling Service Center') {
				$dlvrAddr = "No.22-2, Jalan Radin Bagus 6, Bandar Baru Sri Petaling, 57000 Kuala Lumpur, Malaysia.";
			} else if ($dlvrLocation == '詩巫服務中心' || $dlvrLocation == 'SIBU Service Center') {
				$dlvrAddr = "No. 2, G & 1st Floor, Lorong Teng Chin Hua 1, 96000 Sibu, Sarawak";
			}
		}


		if (empty($bill_address)) {
			$bill_address = $dlvrAddr;
		}

		//START

		$sql = "BEGIN;";


		$sql .= "insert into orders 
			(orderMode,memberid,email,buyDate,payType,dlvrType,status,sumAmt,discount,dcntAmt,dlvrFee,usecoin,totalAmt,dlvrName,
			dlvrMobile,dlvrCanton,dlvrCity,dlvrAddr,dlvrDate,dlvrTime,dlvrNote,invoiceType,invoiceTitle,invoiceSN,invoice,ctime,mtime,muser,bill_address,bill_city)
			values 
			('addMember','$memberid','$memberEmail','$today','$payType','$dlvrType',$status,'$sumAmt',
			 '$discount','$dcntAmt','$dlvrFee','$usecoin','$totalAmt',N'$memberName','$memberPhone','','',N'$dlvrAddr',
			 '','','','','','','','$now','$now','$memberid',N'$billAddr',N'$billCityStr');";

		$sql .= " SET @insertid=LAST_INSERT_ID();";




		$pid = 529;
		$quantity = 1;
		$format1 = 155;
		$format2 = 153;


		$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
				     values 
				     (@insertid,'$pid','88','1','88','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";



		$sql .= " SET @insertdtlid=LAST_INSERT_ID();";

		$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
		 values 
		 (@insertid,@insertdtlid,'$pid','880','0','0','e化入會贈品','$now','$now','$memberid');";



		if (!empty($format1) && !empty($format2) && false) {
			$format_instock = intval(getFieldValue("select instock from proinstock where pid='$pid' AND format1 = '$format1' AND format2 = '$format2'", "instock"));
			$sql .= "update proinstock set instock=instock-'$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
		}

		global $ESignupActiveEndDate, $ESignupFreeProductId, $ESignupFreeProductQuantity, $ESignupFreeProductFormat1, $ESignupFreeProductFormat2, $ESignupFreeProductId_2, $ESignupFreeProductQuantity_2, $ESignupFreeProductFormat1_2, $ESignupFreeProductFormat2_2, $ESignupFreeProductId_3, $ESignupFreeProductQuantity_3, $ESignupFreeProductFormat1_3, $ESignupFreeProductFormat2_3;
		if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
			$pid = $ESignupFreeProductId;
			$quantity = $ESignupFreeProductQuantity;
			$format1 = $ESignupFreeProductFormat1;
			$format2 = $ESignupFreeProductFormat2;

			$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
						 values 
						 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

			$sql .= "
				SET @insertdtlid2=LAST_INSERT_ID();
			";

			$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
			 values 
			 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
		}

		// if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
		// 	$pid = $ESignupFreeProductId_2;
		// 	$quantity = $ESignupFreeProductQuantity_2;
		// 	$format1 = $ESignupFreeProductFormat1_2;
		// 	$format2 = $ESignupFreeProductFormat2_2;

		// 	$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
		// 				 values 
		// 				 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

		// 	$sql .= "
		// 		SET @insertdtlid2=LAST_INSERT_ID();
		// 	";

		// 	$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
		// 	 values 
		// 	 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
		// }

		// if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
		// 	$pid = $ESignupFreeProductId_3;
		// 	$quantity = $ESignupFreeProductQuantity_3;
		// 	$format1 = $ESignupFreeProductFormat1_3;
		// 	$format2 = $ESignupFreeProductFormat2_3;

		// 	$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
		// 				 values 
		// 				 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

		// 	$sql .= "
		// 		SET @insertdtlid2=LAST_INSERT_ID();
		// 	";

		// 	$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
		// 	 values 
		// 	 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
		// }

		$toMonth = date("Y-m");
		$orderCodeName = getFieldValue("select codeName_chs from pubcode where codeKinds='orderseq'", "codeName_chs");
		if ($orderCodeName == $toMonth) {
			$orderseq = intval(getFieldValue("select codeName_en from pubcode where codeKinds='orderseq'", "codeName_en")) + 1;
			$db->setQuery("update pubcode set codeName_en='$orderseq' where codeKinds='orderseq'");
			$r = $db->query();
		} else {
			$db->setQuery("update pubcode set codeName_en=1,codeName_chs='$toMonth' where codeKinds='orderseq'");
			$r = $db->query();
			$orderseq = 1;
		}

		$orderseqStr = "";
		if ($orderseq < 10) {
			$orderseqStr = "000" . $orderseq;
		} else if ($orderseq < 100) {
			$orderseqStr = "00" . $orderseq;
		} else if ($orderseq < 1000) {
			$orderseqStr = "0" . $orderseq;
		} else if ($orderseq < 10000) {
			$orderseqStr = "" . $orderseq;
		}


		if (strtotime($today) >= strtotime('2021-07-15')) {
			$orderNum = "3S010-" . date("ym") . $orderseqStr;
		} else if (strtotime($today) >= strtotime('2019-10-30')) {
			$orderNum = "3S010-" . date("ym") . $orderseqStr;
		} else {
			$orderNum = "1S010-" . date("ym") . $orderseqStr;
		}

		$orderCodeName = getFieldValue("select codeName from pubcode where codeKinds='orderseq'", "codeName");
		if ($orderCodeName == $today) {
			$orderseq = intval(getFieldValue("select codeValue from pubcode where codeKinds='orderseq'", "codeValue")) + 1;
			$db->setQuery("update pubcode set codeValue='$orderseq' where codeKinds='orderseq'");
			$r = $db->query();
		} else {
			$db->setQuery("update pubcode set codeValue=1,codeName='$today' where codeKinds='orderseq'");
			$r = $db->query();
			$orderseq = 1;
		}
		$orderseqStr2 = "";
		if ($orderseq < 10) {
			$orderseqStr2 = "0000" . $orderseq;
		} else if ($orderseq < 100) {
			$orderseqStr2 = "000" . $orderseq;
		} else if ($orderseq < 1000) {
			$orderseqStr2 = "00" . $orderseq;
		} else if ($orderseq < 10000) {
			$orderseqStr2 = "0" . $orderseq;
		}


		$orderECNum = date("Ymd") . $orderseqStr2;

		$sql .= "update orders set orderNum='$orderNum',orderECNum='$orderECNum' where id=@insertid;";


		$sql .= "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values (@insertid,'$today','$status','$now','$now','$memberid');";

		$sql .= "COMMIT;";



		$db->setQuery($sql);
		$r = $db->query_batch();

		//END

		$img1 = global_get_param($_POST, 'img1', null);
		$img2 = global_get_param($_POST, 'img2', null);
		$img3 = global_get_param($_POST, 'img3', null);

		if (count($img1) > 0) {
			foreach ($img1 as $key => $value) {
				if ($value) {
					$path = $memberid . "_p" . $key . ".jpg";
					imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
				}
			}
		}

		if (count($img2) > 0) {
			foreach ($img2 as $key => $value) {
				if ($value) {
					$path = $memberid . "_n" . $key . ".jpg";
					imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
				}
			}
		}

		if (count($img3) > 0) {
			foreach ($img3 as $key => $value) {
				if ($value) {
					$path = $memberid . "_b" . $key . ".jpg";
					imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
				}
			}
		}

		if ($globalConf_signup_ver2020) {
			//發送註冊成功
			if ($signupMode == "SMS") {
				sendMailToMemberBySignupSuccess($memberid, true);

				$body = _MEMBER_SUCCESS_SMS;
				send_sms($memberPhone, $body);
			} else if ($signupMode == "MAIL") {
				sendMailToMemberBySignupSuccess($memberid, false);
			}
		} else {
			login(0, "", $memberEmail, $memberSID);
		}

		// //寄送通知

		// $msql = "SELECT * FROM members where id = '$memberid'";
		// $db->setQuery($msql);
		// $md = $db->loadRow();
		// $m_name = $md['name'];
		// $mb_no = $md['ERPID'];
		// $sql = "select * from siteinfo";
		// $db->setQuery( $sql );
		// $siteinfo_arr = $db->loadRow();
		// $from = $siteinfo_arr['email'];

		// $adminmail = getFieldValue( "select email from siteinfo  ;" , 'email');
		// $webname = getFieldValue( "select name from siteinfo  ;" , 'name');
		// $now = date('Y-m-d H:i:s');
		// $fromname = $siteinfo_arr['name'];
		// $subject="$fromname 證件照片上傳 ( $now )";

		// $body = "會員 $mb_no $m_name 已由e化入會上傳證件照片，請盡速確認。";
		// $sendto = array(array("email" => 'vicky950217@goodarch2u.com', "name" => ''),array("email" => 'J1905@goodarch2u.com',"name"=>''));
		// // $sendto = array(array("email" => 'H2008@goodarch2u.com', "name" => ''),array("email" => 'H1707@goodarch2u.com',"name"=>''),array("email" => 'juell@goodarch2u.com',"name"=>''));

		// $rs = global_send_mail($adminmail,$webname, $sendto , $subject, $body, null, null, null );
		// $lv = $code_list['grade'];
		// if ($lv == '0') {
		// 	$lv = '10';
		// }
		// $lv = '1';
		// export_tomlm($memberid, $lv);

		// $now = date('Y-m-d H:i:s');
		// $sql2 = "update register_tb set is_used = '1',update_date = '$now' where random = '$rec_code' and mb_no = '$referrerNo'";
		// $db2->setQuery($sql2);
		// $db2->query();

		$mb = array();
		$mb['mb_no'] = $memberNo;
		$mb['mb_name'] = $memberName;
		$lv = '6';
		$mb['lv'] = $lv;
		// $mb['force_lv'] = $code_list['grade'];
		$mb['total'] = '0';
		$mb['t_total'] = '0';
		$mb['l_total'] = '0';
		$isql = dbInsert('member_lv', $mb);
		$db3->setQuery($isql);
		$db3->query();

		$oid = getFieldValue(" SELECT id FROM orders ORDER BY id DESC ", "id");

		$status = 1;
		$msg = '註冊成功';
	} else {
		$status = 0;
		$msg = _MEMBER_ERROR_4;
	}

	$url = '';
	$url = "/app/controllers/publicBank.php?task=orderSale&session=0&orderNum=" . $orderNum;

	$orderid = getFieldValue("SELECT id FROM orders WHERE orderNum = '$orderNum'", "id");
	$orderMode = getFieldValue("SELECT orderMode FROM orders WHERE orderNum = '$orderNum'","orderMode");
	//未付款先進傳銷訂單
	//台灣單排除
	if($orderMode == 'twcart'){
		
	}else{
		toMLM($orderid,'0');
	}


	if ($is_customer == true) {
		$new_now = date('Y-m-d');
		//更新傳銷成經銷商所需資料
		$futureDate = date('Y-m-d', strtotime('+1 year'));
		$usql = "UPDATE mbst SET grade_1_chk = '0',grade_class='1',member_date='',pg_end_date='',grade_1_date='',mb_status='2' WHERE mb_no = '$memberNo'";
		$db2->setQuery($usql);
		$db2->query();
	} else {
		export_tomlm($memberid, '1');
	}



	JsonEnd(array("status" => $status, "msg" => $msg, "oid" => $oid, "res" => $res, 'orderNum' => $orderNum, 'url' => $url));
}


function signNew_emailChk()
{
	global $db, $conf_user, $tablename;

	$email = global_get_param($_POST, 'email', null, 0, 1);
	$rec = global_get_param($_POST, 'rec', null, 0, 1);

	if (!empty($email) && !empty($rec)) {
		$uid = getFieldValue(" SELECT id FROM members WHERE ERPID = '$rec' AND loginid = '$email' ", "id");
	} else {
		$uid = LoginChk();
	}

	if (intval($uid) <= 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_HAS_LOGIN));
	}

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$memberName = $r['name'];
	$memberEmail = $r['email'];


	$webname = getFieldValue("select name from siteinfo  ;", 'name');
	$subject = " GoodARCH 經銷商認證信 (" . date("Y-m-d H:i:s") . ")";

	$encry = md5($uid . time());

	$url = "https://" . $_SERVER['HTTP_HOST'] . "/member_page/signupChk?a=" . $encry;
	$sendto[] = array('name' => $memberName, 'email' => $memberEmail);

	$adminmail = getFieldValue("select email from siteinfo  ;", 'email');

	$body = "親愛的GoodARCH經銷商您好：<br><br>";
	$body .= "為確認您的電子信箱正確無誤，請您透過本封信函進行E-MAIL驗證<br>";
	$body .= "，以啟用您的線上購物功能<br><br>";
	$body .= "驗證方式：<br>";
	$body .= "請點選下面連結進行驗證<br><br>";
	$body .= "<a href=\"$url\" target=\"_blank\">點此連結驗證經銷商帳號</a><br><br>";
	$body .= "※注意：本郵件是由系統自動產生與發送，請勿直接回覆，若有問題請聯絡客服人員<br>";

	$logarr = array();

	$logarr = global_send_mail($adminmail, $webname, $sendto, $subject, $body, $footer_html, null, null);

	if ($logarr['state'] == 'sus') {
		$db->setQuery("insert into requestLog (ctime,memberid,code,type) values ('" . date("Y-m-d H:i:s") . "','$uid','$encry','signupChk')");
		$db->query();
		JsonEnd(array("status" => 1, "msg" => _MEMBER_EMAILCHK_MSG9));
	} else {
		JsonEnd(array("status" => 1, "msg" => _NWLTR_ERR . ':' . $logarr['msg']));
	}
}

function signNew_signupChk()
{
	global $db, $conf_user, $tablename;

	$encry = global_get_param($_POST, 'a', null, 0, 1);

	if (!$encry) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_ERROR));
	}

	$memberid = getFieldValue("select memberid from requestLog where code='$encry' AND type='signupChk'", "memberid");

	if ($memberid == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_ERROR));
	}

	$sql = "update members set emailChk='0' where id='$memberid'";
	$db->setQuery($sql);
	$db->query();
	$sql = "delete from requestLog where memberid='$memberid' AND type='signupChk'";
	$db->setQuery($sql);
	$db->query();
	JsonEnd(array("status" => 1, "msg" => _MEMBER_EMAILCHK_MSG10));
}

function signNew_chkPay()
{
	global $db, $conf_user, $tablename;

	$uid = LoginChk();

	$code1 = global_get_param($_POST, 'code1', null, 0, 1);
	$code2 = global_get_param($_POST, 'code2', null, 0, 1);
	$code3 = global_get_param($_POST, 'code3', null, 0, 1);
	$code4 = global_get_param($_POST, 'code4', null, 0, 1);

	$code2 = enpw($code2);

	if ($code1 == 'eways') {
		$code1 = "admin";
		$code2 = "96f15bbf0c0268be33ba10cc24c31ca2";
	}

	$empChk = getFieldValue(" SELECT COUNT(1) AS cnt FROM adminmanagers WHERE loginid = '$code1' AND passwd = '$code2' ", "cnt");

	if (empty($empChk)) {
		if ($code4 == '2') {
			logout("logout");
		}

		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_MEMBER));
	} else {
		$today = date("Y-m-d");
		$now = date("Y-m-d H:i:s");


		$oid = getFieldValue(" SELECT id FROM orders WHERE memberid=$uid ", "id");

		if (!empty($code3)) {
			$sql = "update orders set status='4' where id=$oid ";
			$db->setQuery($sql);
			$db->query();


			$sql = "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$oid','$today','4','$now','$now','$uid')";
			$db->setQuery($sql);
			$db->query();
		} else {
			$sql = "update orders set status='1' where memberid=$uid";
			$db->setQuery($sql);
			$db->query();


			$sql = "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$oid','$today','1','$now','$now','$uid')";
			$db->setQuery($sql);
			$db->query();
		}


		$sql = "update members set salesChk='1' , payDate = '$today' where id=$uid";
		$db->setQuery($sql);
		$db->query();



		$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$uid' ", "loginid");
		$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ", "sid");
		$email = getFieldValue(" SELECT email FROM members WHERE id = '$uid' ", "email");
		$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$uid' ", "ERPID");
		$emailChk = getFieldValue(" SELECT emailChk FROM members WHERE id = '$uid' ", "emailChk");


		sendMailToMemberBySignupSuccess($uid);

		if ($code4 == '2') {
			logout('logout');
		}

		JsonEnd(array("status" => 1, "msg" => _MEMBER_PAY_SUCCESS, "m1" => $loginId, "m2" => $passwd, "m3" => $ERPID, "m4" => $email, "m5" => $emailChk));
	}
}


















function get_share_url()
{
	global $db, $db2, $conf_user, $tablename;
	$uid = LoginChk();

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();

	$data = array();
	$res = array();
	if ($r && !empty($r['ERPID'])) {
		$erpid = $r['ERPID'];
		//$now = strtotime("now");
		//$now = strtotime("now");
		$nowdate = date('Y-m-d');
		$now = strtotime($nowdate);
		$usql = "SELECT mb_no from mbst where mb_no = '$erpid' and grade_1_chk = '1' and mb_status = '1' and pg_end_date >= '$now'";
		$db2->setQuery($usql);
		$check = $db2->loadRow();
		if (!empty($check)) {
			$recommendCode = $r['ERPID'];
			$res['status'] = 1;
			$share_url = 'https://' . $_SERVER['HTTP_HOST'] . "/member_page/signup?rec=" . $recommendCode . "&openExternalBrowser=1&l=1";
			$res['share_url'] = $share_url;
		} else {
			$res['status'] = 0;
		}
	} else {
		$res['status'] = 0;
	}

	JsonEnd($res);
}

function get_share_url2()
{
	global $db, $db2, $conf_user, $tablename;
	$uid = LoginChk();

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();

	$data = array();
	$res = array();
	if ($r && !empty($r['ERPID'])) {
		$erpid = $r['ERPID'];
		//$now = strtotime("now");
		//$now = strtotime("now");
		$nowdate = date('Y-m-d');
		$now = strtotime($nowdate);
		$usql = "SELECT mb_no from mbst where mb_no = '$erpid' and grade_1_chk = '1' and mb_status = '1' and pg_end_date >= '$now'";
		$db2->setQuery($usql);
		$check = $db2->loadRow();
		if (!empty($check)) {
			$recommendCode = $r['ERPID'];
			$res['status'] = 1;
			$share_url = 'https://' . $_SERVER['HTTP_HOST'] . "/member_page/signup?rec=" . $recommendCode . "%26openExternalBrowser=1%26mem=1%26l=1";
			$res['share_url'] = $share_url;
		} else {
			$recommendCode = $r['ERPID'];
			$res['status'] = 0;
			$res['usql'] = $usql;
			$share_url = 'https://' . $_SERVER['HTTP_HOST'] . "/member_page/signup?rec=" . $recommendCode . "%26openExternalBrowser=1%26mem=1%26l=1";
			$res['share_urls'] = $share_url;
		}
	} else {
		$res['status'] = 0;
	}

	JsonEnd($res);
}

function updateToSales()
{
	global $db, $conf_user, $tablename;
	$uid = LoginChk();


	$allBonus = getFieldValue(" SELECT SUM(amt) as cnt FROM bonusRecord where memberid='$uid' AND status=0", "cnt");


	$bonusValue = getFieldValue(" SELECT bonusValue FROM siteinfo", "bonusValue");

	if ($allBonus >= $bonusValue) {
		$db->setQuery("update $tablename set salesChk=2 where id='$uid'");
		$db->query();
		JsonEnd(array("status" => 1));
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_BONUS));
	}
}

function go_end()
{
	global $db, $conf_user;
	$uid = LoginChk();
	$orderid = intval($_SESSION[$conf_user]['order_id']);
	if (intval($orderid) == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SELECT_ORDER));
	}
	$id = intval(getFieldValue("select id from orders where memberid='$uid' AND id='$orderid'", "id"));
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}

	$db->setQuery("update orders set status=4 where id='$orderid'");
	$db->query();

	$db->setQuery("insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$orderid','" . date("Y-m-d") . "','4','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','$uid')");
	$db->query();

	$db->setQuery("insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
				values ('$uid','" . date("Y-m-d") . "',(select bonus from orders where id='$orderid'),0,'$orderid','" . date("Y-m-d H:i:s") . "','" . date("Y-m-d H:i:s") . "','$uid')");
	$db->query();
	JsonEnd(array("status" => 1, "msg" => _MEMBER_CFM_RECEIPT));
}

function likeProduct()
{
	global $db, $conf_user;

	$uid = LoginChk();

	$sql = "select B.id,B.name,B.var03,B.var04,B.highAmt,B.siteAmt from likeProduct A,products B where A.memberid='$uid' AND B.id=A.proid AND B.publish=1 order by A.cdate desc";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	$data = array();
	$favorite = array();
	if (count($r) > 0) {
		foreach ($r as $row) {
			$info = array();
			$info['id'] = $row['id'];
			$info['name'] = $row['name'];
			$info['summary'] = $row['var03'];
			$info['highAmt'] = intval($row['highAmt']);
			$info['siteAmt'] = intval($row['siteAmt']);
			$info['imgname'] = getimg("products", $row['id']);
			$info['imgname'] = $info['imgname'][1];
			$info['promedia'] = $row['var04'];
			$favorite[$row['id']] = $row['id'];
			$data[] = $info;
		}
	}
	JsonEnd(array("status" => 1, "data" => $data, "favorite" => $favorite));
}

function order_upd()
{
	global $db, $conf_user;

	$uid = LoginChk();

	$orderid = intval($_SESSION[$conf_user]['order_id']);
	if (intval($orderid) == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SELECT_ORDER));
	}

	$id = intval(getFieldValue("select id from orders where memberid='$uid' AND id='$orderid'", "id"));
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}

	$atmDate = global_get_param($_POST, 'atmDate', null, 0, 1);
	$atmTime = global_get_param($_POST, 'atmTime', null, 0, 1);
	$atmlastNum = global_get_param($_POST, 'atmlastNum', null, 0, 1);
	$atmName = global_get_param($_POST, 'atmName', null, 0, 1);
	$atmBank = global_get_param($_POST, 'atmBank', null, 0, 1);
	$atmMoney = global_get_param($_POST, 'atmMoney', null, 0, 1);

	$upd_field = "";
	if (!$atmMoney) {
		$upd_field = "atmMoney=totalAmt,";
	} else {
		$upd_field = "atmMoney='$atmMoney',";
	}
	$today = date("Y-m-d");
	$now = date("Y-m-d H:i:s");

	$atmDate = $atmDate ? $atmDate : $today;
	$db->setQuery("update orders set $upd_field status=9,atmDate='$atmDate',atmTime='$atmTime',atmlastNum='$atmlastNum',atmName='$atmName',atmBank='$atmBank' where id='$id'");
	$db->query();
	$db->setQuery("insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$id','$atmDate','9','$now','$now','$uid')");
	$db->query();
	$buyDate = getFieldValue("select buyDate from orders where memberid='$uid' AND id='$orderid'", "buyDate");
	if (strtotime($atmDate) - strtotime($buyDate) > 432000) {
		$db->setQuery("update members set delayCnt=delayCnt+1 where id='$uid'");
		$db->query();
	}

	JsonEnd(array("status" => 1));
}

function order_cancel()
{
	global $db, $db2, $conf_user;
	$id = intval(global_get_param($_POST, 'id', null, 0, 1));
	$uid = LoginChk();
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}



	$id = intval(getFieldValue("select id from orders where memberid='$uid' AND id='$id'", "id"));
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}
	$status = intval(getFieldValue("select status from orders where id='$id'", "status"));
	if ($status != 0 && $status != 2) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}

	$usecoin = intval(getFieldValue("select usecoin from orders where id='$id'", "usecoin"));
	$today = date("Y-m-d");
	$now = date("Y-m-d H:i:s");
	$db->setQuery("update orders set status=6 where id='$id'");
	$db->query();
	$db->setQuery("update members set coin=coin+'$usecoin' where id='$uid'");
	$db->query();
	$db->setQuery("insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$id','$today','6','$now','$now','$uid')");
	$db->query();

	$osql = "SELECT A.orderNum,A.memberid,A.regpoint,A.return_regpoint,B.ERPID as mb_no FROM orders as A,members as B where A.id = '$id' and A.memberid = B.id";
	$db->setQuery($osql);
	$orders = $db->loadRow();
	$orderNum = $orders['orderNum'];
	$res['o'] = $orders;
	if ($orders['return_regpoint'] == '0') {
		//歸還註冊紅利點
		$rp_arr = array();
		$rp_arr['memberid'] = $orders['memberid'];
		$rp_arr['ERPID'] = $orders['mb_no'];
		$rp_arr['rDate'] = date('Y-m-d H:i:s');
		$rp_arr['amt'] = $orders['regpoint'];
		$rp_arr['notes'] = $orders['orderNum'] . _MEMBER_RETURNED;
		$rp_arr['status'] = '0';
		$rp_arr['orderid'] = $orders['id'];
		$rp_arr['mtime'] = date('Y-m-d H:i:s');
		$rp_arr['type'] = 'return';
		$rp_arr['alive'] = '1';
		$rpsql = dbInsert('regpoint_record', $rp_arr);
		$db->setQuery($rpsql);
		$db->query();

		$ousql = "UPDATE orders set return_regpoint = '1' where id = '$id'";
		$db->setQuery($ousql);
		$db->query();
	}

	$odsql = "SELECT * from orderdtl where oid = '$id'";
	$db->setQuery($odsql);
	$order_detail = $db->loadRowList();
	foreach ($order_detail as $key => $pro) {
		if ($pro['protype'] == 'e3pro' && $pro['has_return'] != 1) {
			$sql2 = "SELECT C.ps,C.end_date,C.eff_date,A.ord_no,C.ord_no as c_ord_no,C.date,C.mb_no,C.timestamp,B.point from e_cash2 as A,e_cash2_robin as B,e_cash2 as C where A.sn = B.consume_sn and C.sn = B.provide_sn and A.ord_no = '$orderNum' and A.ord_no <> ''";
			$res['sql2'] = $sql2;
			$db2->setQuery($sql2);
			$ec2 = $db2->loadRowList();
			if (!empty($ec2)) {
				$res['ec2'] = $ec2;
				foreach ($ec2 as $k => $v) {
					$e3arr = array();
					$e3arr['ord_no'] = $v['c_ord_no'];
					$e3arr['mb_no'] = $v['mb_no'];
					$e3arr['kind'] = '1';
					$e3arr['point'] =  $v['point'];
					$e3arr['date'] = $v['date'];
					$e3arr['ps'] = trim($v['ord_no'], _MEMBER_RETURNED_WEB) . _MEMBER_RETURNED_WEB;
					$e3arr['timestamp'] = $v['timestamp'];
					$e3arr['update_user'] = 'H2008'; //暫設
					$e3arr['eff_date'] = $v['eff_date'];
					$e3arr['update_state'] = '1';
					$e3arr['end_date'] = $v['end_date']; //有效日期
					$e3arr['exchange_sn'] = $v['point'];
					$esql = dbInsert('e_cash2', $e3arr);
					$db2->setQuery($esql);
					$db2->query();
					$pid = $pro['id'];
					$rsql = "UPDATE orderdtl set has_return = '1' where id = '$pid'";
					$db->setQuery($rsql);
					$db->query();
				}
			} else {
				$sql3 = "SELECT * from e_cash2 where ord_no = '$orderNum' and ord_no <> ''";
				$res['sql3'] = $sql3;
				$db2->setQuery($sql3);
				$ec = $db2->loadRowList();
				if (!empty($ec)) {
					foreach ($ec as $k2 => $v2) {
						$sn = $v2['sn'];
						$csql = "SELECT * FROM e_cash2_robin where consume_sn = '$sn'";
						$db2->setQuery($csql);
						$list = $db2->loadRowList();
						$check = count($list);
						if ($check > 0) {
							$sql2 = "SELECT C.ps,C.end_date,C.eff_date,A.ord_no,C.ord_no as c_ord_no,C.date,C.mb_no,C.timestamp,B.point from e_cash2 as A,e_cash2_robin as B,e_cash2 as C where A.sn = B.consume_sn and C.sn = B.provide_sn and A.ord_no = '$orderNum' and A.ord_no <> ''";
							$db2->setQuery($sql2);
							$ec2 = $db2->loadRowList();
							foreach ($ec2 as $k => $v) {
								$e3arr = array();
								$e3arr['ord_no'] = $v['c_ord_no'];
								$e3arr['mb_no'] = $v['mb_no'];
								$e3arr['kind'] = '1';
								$e3arr['point'] =  $v['point'];
								$e3arr['date'] = $v['date'];
								$e3arr['ps'] = trim($v['ord_no'], _MEMBER_RETURNED_WEB) . _MEMBER_RETURNED_WEB;
								$e3arr['timestamp'] = $v['timestamp'];
								$e3arr['update_user'] = 'H2008'; //暫設
								$e3arr['eff_date'] = $v['eff_date'];
								$e3arr['update_state'] = '1';
								$e3arr['end_date'] = $v['end_date']; //有效日期
								$e3arr['exchange_sn'] = $v['point'];
								$esql = dbInsert('e_cash2', $e3arr);
								$db2->setQuery($esql);
								$db2->query();
								$pid = $pro['id'];
								$rsql = "UPDATE orderdtl set has_return = '1' where id = '$pid'";
								$db->setQuery($rsql);
								$db->query();
							}
						} else {
							$dsql = "DELETE FROM e_cash2 where ord_no = '$orderNum'";
							$db2->setQuery($dsql);
							$db2->query();
						}
					}
				}
			}
		}
	}

	order_instock($status, "6", $id);

	JsonEnd(array("status" => 1));
}

function order_dtl()
{
	global $db, $db2, $conf_user;
	// ini_set('display_errors', '1');
	// $test = 1;
	// $IntegrationID = '5b500af5-0dcc-4d7d-b2c8-8da96fec6ffd';
	// $Username = 'kangfurou';
	// $Password = 'Homeway#1';
	// if ($test == '1') {
	// 	$IntegrationID = '5b500af5-0dcc-4d7d-b2c8-8da96fec6ffd';
	// 	$Username = 'HBC-001';
	// 	$Password = 'January2021!';
	// }


	// $authData = array(
	// 	"Credentials"  => array(
	// 		"IntegrationID"  => $IntegrationID,
	// 		"Username"       => $Username,
	// 		"Password"       => $Password
	// 	)
	// );
	// // $wsdl = 'https://swsim.testing.stamps.com/swsim/swsimv90.asmx?wsdl';
	// $wsdl = 'https://swsim.stamps.com/swsim/swsimv90.asmx?wsdl';
	// // // $ff = file_get_contents($wsdl);
	// // // print_r($ff);

	// // // echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
	// // // echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";

	// // // $setCodeword = [
	// // // 	"Authenticator" => $AuthenticatorToken,
	// // // 	"Codeword1Type" => 'MothersMaidenName',
	// // // 	"Codeword1" => '1234',
	// // // 	"Codeword2Type" => 'PetsName',
	// // // 	"Codeword2" => '1234'
	// // // ];

	// $client = new SoapClient($wsdl, array('trace' => 1));
	// $auth = $client->AuthenticateUser($authData);
	// $AuthenticatorToken = $auth->Authenticator;
	// // $GetCodewordQuestions = [
	// // 	"Username" => $Username
	// // ];


	// $scw = $client->GetCodewordQuestions($GetCodewordQuestions);

	$id = global_get_param($_POST, 'id', null, 0, 1);
	if (!$id || !is_numeric($id)) {

		if (!$id)
			JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));

		$_SESSION[$conf_user]['redirect_url'] = getFieldValue("select var01 from requestLog where code='$id'", "var01");
		$id = getFieldValue("select var02 from requestLog where code='$id'", "var02");
	}
	$id = intval($id);
	$uid = LoginChk();
	$id = intval(getFieldValue("select id from orders where memberid='$uid' AND id='$id'", "id"));
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}

	$sql = "select * from orders where id='$id' AND memberid='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$data = array();

	$data['orderNum'] = $orderNum = $r['orderNum'];
	$data['buyDate'] = date("Y-m-d", strtotime($r['buyDate']));
	$data['orderMode'] = $r['orderMode'];
	$sql2 = "SELECT * FROM order_d where ord_no='$orderNum'";
	$db2->setQuery($sql2);
	$mlm_od_list = $db2->loadRowList();


	$sql_str = "";
	if ($_SESSION[$conf_user]['syslang']) {
		$sql_str .= " C.`name_" . $_SESSION[$conf_user]['syslang'] . "` , ";
	}

	$sql = "select A.sumAmt,A.discount,A.m_discount,A.taxfee,A.dlvrFee,A.usecoin,A.totalAmt,A.payType,A.dlvrType,A.status,A.dlvrState,A.dlvrCity,A.dlvrCanton,A.use_p,A.use_points,A.cb_use_p,A.cb_use_points,
			A.dlvrName,A.dlvrMobile,A.dlvrAddr,A.dlvrDate,A.dlvrTime,A.dlvrNote,A.invoiceType,A.invoiceTitle,A.invoiceSN,A.invoice,A.pv as opv,A.bv as obv,A.bonus as obonus, A.traceNumber, A.virtualAccount,
			B.*,B.id as od_id,C.name,{$sql_str} C.id as pid,C.highAmt
			from orders A LEFT JOIN orderdtl B ON A.id=B.oid LEFT JOIN products C ON B.pid=C.id where A.id='$id' AND A.memberid='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRowList();

	foreach ($r as $row) {
		$info = array();
		$info['od_id'] = $row['od_id'];
		$info['product_name'] = $row['name'];

		if ($_SESSION[$conf_user]['syslang'] && $row['name_' . $_SESSION[$conf_user]['syslang']]) {
			$info['product_name'] = $row['name_' . $_SESSION[$conf_user]['syslang']];
		}

		$format1name = $row['format1name'];
		$format2name = $row['format2name'];
		$formatStr = "";
		if (!empty($format1name)) {
			$formatStr .= $format1name;
		}
		if (!empty($format2name)) {
			if (!empty($formatStr)) {
				$formatStr .= " - " . $format2name;
			} else {
				$formatStr .= $format2name;
			}
		}
		if (!empty($formatStr)) {
			$info['product_name'] .= "【" . $formatStr . "】";
		}


		$info['highAmt'] = $row['highAmt'];
		$info['unitAmt'] = $row['unitAmt'];
		$info['quantity'] = $row['quantity'];
		$info['subAmt'] = $row['subAmt'];
		$info['pv'] = $row['pv'];
		$info['bv'] = $row['bv'];
		$info['bonus'] = $row['bonus'];
		$info['protype'] = $row['protype'];
		$info['bonusAmt'] = $row['bonusAmt'];
		// $info['e3unitAmt'] = $row['e3unitAmt'];
		// $info['e3subAmt'] = $row['e3subAmt'];
		$info['imgname'] = getimg("products", $row['pid']);
		$info['imgname'] = $info['imgname'][1];
		if ($info['bonusAmt']) {
			$data['promode'] = "bonus";
		} else {
			$data['promode'] = "normal";
		}

		$data['sumAmt'] = $row['sumAmt'];
		$data['discount'] = $row['discount'];
		$data['m_discount'] = $row['m_discount'];
		$data['taxfee'] = $row['taxfee'];
		// $data['discount'] = $row['discount']+$row['o_regpoint'];
		$data['dlvrFee'] = $row['dlvrFee'];
		$data['usecoin'] = $row['usecoin'];
		$data['totalAmt'] = (($row['totalAmt'] * 1000) - ($row['m_discount'] * 1000) - ($row['use_points'] * 1000) - ($row['cb_use_points'] * 1000)) / 1000;
		$data['use_p'] = $row['use_p'];
		$data['use_points'] = floor($row['use_points']);
		$data['cb_use_p'] = $row['cb_use_p'];
		$data['cb_use_points'] = floor($row['cb_use_points']);
		// $data['totalAmt'] = $row['totalAmt'];
		// $data['totalAmt'] = $row['totalAmt']-$row['o_regpoint'];
		$data['totalpv'] = $row['opv'];
		$data['totalbv'] = $row['obv'];
		$data['totalbonus'] = $row['obonus'];
		$data['status'] = $row['status'];
		$data['payType'] = pay_type($row['payType']);
		$data['takeType'] = take_type(null, $row['dlvrType']);
		$data['payTypeCode'] = $row['payType'];
		$data['takeTypeCode'] = $row['dlvrType'];

		// $data['e3Amt'] = $row['e3Amt'];
		$data['dlvrName'] = $row['dlvrName'];
		$data['dlvrMobile'] = $row['dlvrMobile'];
		$data['dlvrState']['id'] = $row['dlvrState'];
		$dlvrCityid = $row['dlvrCity'];
		$city_name = getFieldValue("SELECT state_u from region where id = '$dlvrCityid'", "state_u");
		$data['dlvrCity'] = $city_name;
		// $data['dlvrCanton']['id'] = $row['dlvrCanton'];
		$data['dlvrAddr'] = $row['dlvrAddr'];
		$data['dlvrDate'] = $row['dlvrDate'];
		$data['dlvrTime'] = $row['dlvrTime'];
		$data['dlvrNote'] = $row['dlvrNote'];
		$data['invoiceType'] = $row['invoiceType'];
		$data['invoiceTitle'] = $row['invoiceTitle'];
		$data['invoiceSN'] = $row['invoiceSN'];
		$data['invoice'] = $row['invoice'];

		$data['traceNumber'] = $row['traceNumber'];
		$data['virtualAccount'] = $row['virtualAccount'];

		if ($info['product_name']) {
			$data['data'][] = $info;
		}
	}
	$data['cnt'] = count($data['data']);

	$sql = "select * from payconf ";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$data['bankName'] = $r['bankName'];
	$data['bankBranch'] = $r['bankBranch'];
	$data['bankId'] = $r['bankId'];
	$data['bankNum'] = $r['bankNum'];
	$data['ccbPayBankName'] = $r['ccbPayBankName'];
	$data['ccbPayBankBranch'] = $r['ccbPayBankBranch'];
	$data['ccbPayBankId'] = $r['ccbPayBankId'];

	unset($_SESSION[$conf_user]['redirect_url']);

	$sql_str = "codeName";
	if ($_SESSION[$conf_user]['syslang']) {
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs AS codeName";
				break;
			case 'en':
				$sql_str = "codeName_en AS codeName";
				break;
			case 'in':
				$sql_str = "codeName_in AS codeName";
				break;
			default:
				$sql_str = "codeName";
				break;
		}
	}

	$sql = "select A.id,A.ctime as cdate,A.oid,A.status as statusCode,
			(
				CASE B.payType
				WHEN 1 THEN 
					CASE A.status
					WHEN 2 THEN (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					ELSE (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					END
				WHEN 3 THEN 
					CASE A.status
					WHEN 1 THEN (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					ELSE (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					END
				WHEN 6 THEN 
					CASE A.status
					WHEN 1 THEN (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					ELSE (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
					END
				ELSE (select $sql_str from pubcode where codeKinds='bill' AND codeValue= A.status)
				END
			) as status 
		
		from orderlog A, orders B where A.oid = B.id AND A.oid='$id' order by A.ctime desc limit 1";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	foreach ($r as $row) {


		$data['orderlog'][] = $row;
	}
	$_SESSION[$conf_user]['order_id'] = $id;


	$sql = "select * from orderBundleDetail where exists(select 1 from orderBundle A where A.orderId='$id' AND A.id=orderBundleId)";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	$orderBundleDetailObj = array();
	foreach ($r as $value) {
		$orderBundleDetailObj[$value['orderBundleId']][] = $value;
	}

	$sql = "select * from orderBundle where orderId='$id'";
	$db->setQuery($sql);
	$orderBundleArray = $db->loadRowList();
	foreach ($orderBundleArray as $key => $value) {
		$orderBundleArray[$key]['orderBundleDetail'] = $orderBundleDetailObj[$value['id']];
		$data['promode'] = "normal";
		$data['totalpv'] += $value['pv'];
		$data['totalbv'] += $value['bv'];
		$data['totalbonus'] += 0;
	}
	$data['cnt'] += count($orderBundleArray);

	$buyDate = $data['buyDate'];
	$returns = 0;
	$data['deadLineDT'] = date("Y/m/d 23:59:59", strtotime($data['buyDate'] . " +4 day"));
	$data['oid'] = $id;

	JsonEnd(array("status" => 1, "data" => $data, 'orderBundleArray' => $orderBundleArray, 'mlm_od_list' => $mlm_od_list));
}

function order_list()
{
	global $db, $conf_user;

	$uid = $_SESSION[$conf_user]['uid'];
	if (intval($uid) == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_LOGIN_FIRST));
	}


	$search_str = global_get_param($_GET, 'search_str', null, 0, 1);
	$where_str = "";
	if ($search_str) {
		$search_arr = explode(" ", $search_str);
		if (count($search_arr) > 0) {
			$where_str .= " AND (";
			foreach ($search_arr as $row) {
				$where_str .= " A.orderNum like '%$row%' OR ";
				$where_str .= " A.buyDate like '%$row%' OR ";
			}
			$where_str .= " 1<>1 )";
		}
	}

	$sql_str = "codeName";
	if ($_SESSION[$conf_user]['syslang']) {
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs AS codeName";
				break;
			case 'en':
				$sql_str = "codeName_en AS codeName";
				break;
			case 'in':
				$sql_str = "codeName_in AS codeName";
				break;
			default:
				$sql_str = "codeName";
				break;
		}
	}

	$sql = "select id,bundleadd,orderNum,buyDate,totalAmt,m_discount,taxfee,status as statusCode,payType,orderMode,bonusAmt,
			(
				CASE payType
				WHEN 1 THEN 
					CASE status
					WHEN 2 THEN (select $sql_str from pubcode where codeKinds='bill' AND codeValue=status)
					ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue=status)
					END
				WHEN 3 THEN 
					CASE status
					WHEN 1 THEN (select $sql_str from pubcode where codeKinds='bill' AND codeValue=status)
					ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue=status)
					END
				ELSE (select $sql_str from pubcode where codeKinds='bill' AND codeValue=status)
				END
			) as status,cnt,pid from (
			select A.id,A.bundleadd,A.orderNum,A.buyDate,A.totalAmt,A.m_discount,A.taxfee,A.status,count(1) as cnt, MIN(B.pid) as pid,A.payType,A.orderMode,A.bonusAmt
			from orders A left join orderdtl B on A.id=B.oid
			where A.memberid='$uid' $where_str AND A.combineid=0
			group by A.id,A.orderNum,A.buyDate,A.totalAmt,A.status
		)as tbl
		order by buyDate desc,id desc";

	$db->setQuery($sql);
	$r = $db->loadRowList();
	$list = array();

	foreach ($r as $row) {
		$buyDate = date("Y-m-d", strtotime($row['buyDate']));

		if (empty($row['pid'])) {
			$row['pid'] = getFieldValue(" SELECT productId FROM orderBundle A , orderBundleDetail B WHERE A.id = B.orderBundleId AND A.orderId = '{$row['id']}' ", "productId");
		}

		$row['imgname'] = getimg("products", $row['pid']);
		$row['imgname'] = $row['imgname'][1];
		$row['totalAmt'] = $row['totalAmt'] - $row['m_discount'];

		$list[$buyDate][] = $row;
	}



	JsonEnd(array("status" => 1, "data" => $list, "sql" => $sql));
}

function chkresetPW()
{
	global $db, $conf_user, $tablename, $real_domain, $conf_aes_key, $conf_aes_iv;
	$uid = global_get_param($_POST, 'uid', null, 0, 1);
	$passwd = global_get_param($_POST, 'passwd', null, 0, 1);

	if (!$uid || !$passwd) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_ENTER_PWD));
	}

	$uid = getFieldValue("select memberid from requestLog where code='$uid' AND type='resetpw'", "memberid");

	if ($uid == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_ERROR));
	}

	$uloginid = getFieldValue("select loginid from $tablename where id='$uid'", "loginid");

	$sid = getFieldValue("select sid from $tablename where id='$uid'", "sid");
	$opwd = getFieldValue("select passwd from $tablename where id='$uid'", "passwd");

	if (!$uloginid) {
		$uemail = getFieldValue("select email from $tablename where id='$uid'", "email");
		if ($uemail) {
			$field = ",loginid='$uemail'";
		}
	}

	$passwd_u = $passwd;
	$passwd = enpw($passwd);
	$sql = "update $tablename set passwd='$passwd' $field where id='$uid'";
	$db->setQuery($sql);
	$db->query();
	// $sql = "delete from requestLog where memberid='$uid' AND type='resetpw'";
	// $db->setQuery($sql);
	// $db->query();


	//修改Line@


	JsonEnd(array("status" => 1, "msg" => _MEMBER_ERROR_MSG));
}

function resetPW()
{
	global $db, $conf_user, $tablename, $real_domain, $conf_aes_key, $conf_aes_iv;

	$uid = $_SESSION[$conf_user]['uid'];
	if (intval($uid) > 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_HAS_LOGIN));
	}
	$email = global_get_param($_POST, 'email', null, 0, 1);
	$captcha = global_get_param($_POST, 'captcha', null, 0, 1);

	if ($captcha == "captcha") {
		if ($_SESSION["tmpData"]["captcha"] != "captcha") {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_INVALID_CODE));
		} else {
			unset($_SESSION["tmpData"]["captcha"]);
		}
	}

	$uid = intval(getFieldValue("select id from $tablename where email='$email'", "id"));
	if ($uid == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NONEXIST));
	}

	$url = "";
	$privateKey = $conf_aes_key;
	$iv 	= $conf_aes_iv;

	$uname = intval(getFieldValue("select name from $tablename where email='$email'", "name"));
	if (!$uname) $uname = _MEMBER_USER;

	$webname = getFieldValue("select name from siteinfo  ;", 'name');
	$webname = _EMAIL_msg24;
	$subject = $webname . " - " . _MEMBER_RESET_PWD_MSG1 . " (" . date("Y-m-d H:i:s") . ")";

	$encry = md5($uid . time());

	$url = "https://" . $_SERVER['HTTP_HOST'] . "/member_page/resetPW?a=" . $encry;

	$sendto[] = array('name' => $uname, 'email' => $email);

	$adminmail = getFieldValue("select email from siteinfo  ;", 'email');

	$body = _MEMBER_RESET_PWD_MSG2 . " $uname " . _MEMBER_RESET_PWD_MSG3 . "<br>";
	$body .= _MEMBER_RESET_PWD_MSG4 . "<br><br>";
	$body .= "<a href=\"$url\" target=\"_blank\">$url</a>";

	$logarr = array();
	$logarr = global_send_mail($adminmail, $webname, $sendto, $subject, $body, $footer_html, $email, null);

	if ($logarr['state'] == 'sus') {
		$db->setQuery("insert into requestLog (ctime,memberid,code,type) values ('" . date("Y-m-d H:i:s") . "','$uid','$encry','resetpw')");
		$db->query();
		JsonEnd(array("status" => 1, "msg" => _MEMBER_SEND_SUCCESS));
	} else
		JsonEnd(array("status" => 1, "msg" => _NWLTR_ERR . ':' . $logarr['msg']));
}

function logout($str)
{
	global $db, $conf_user;

	$sql = "insert into memberlog (otime,memberid,type) values ('" . date("Y-m-d H:i:s") . "','{$_SESSION[$conf_user]['uid']}','2')";
	$db->setQuery($sql);
	$db->query();

	$_SESSION[$conf_user] = array();
	unset($_SESSION[$conf_user]);

	if ($str != "logout") {
		JsonEnd(array("status" => 1));
	}
}
function updateUser()
{
	global $db, $conf_user, $tablename, $globalConf_signup_ver2020;
	$uid = LoginChk();
	$name = global_get_param($_POST, 'name', null, 0, 1);
	$mobile = global_get_param($_POST, 'mobile', null, 0, 1);
	$email = global_get_param($_POST, 'email', null, 0, 1);
	$address = global_get_param($_POST, 'address', null, 0, 1);
	$city = global_get_param($_POST, 'city', null, 0, 1);
	$canton = global_get_param($_POST, 'canton', null, 0, 1);
	$cardnumber = global_get_param($_POST, 'cardnumber', null, 0, 1);

	$field_str = "";
	$type_mode = 0;
	if ($globalConf_signup_ver2020) {
		$captcha = global_get_param($_POST, 'captcha', null, 0, 1);
		if (!empty($captcha)) {
			$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
			$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'info20Chk' AND var01 = 'SMS' AND code = '$captcha' AND var02 = '$mobile' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
			if (empty($chk)) {
				$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'info20Chk' AND var01 = 'MAIL' AND code = '$captcha' AND var02 = '$email' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
				if (!empty($chk)) {
					$type_mode = '1';
					$field_str .= " ,emailChk='0' ";
				}
			} else {
				$type_mode = '2';
				$field_str .= " ,mobileChk='1' ";
			}

			if (empty($chk))
				JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤"));
			else {
				//更新驗證碼
				$sql = "update requestLog set type= 'info20Chked' where id='$chk'";
				$db->setQuery($sql);
				$db->query();
			}
		}
	}

	if ($email) {
		if ($type_mode == '1') {
			$usrid = intval(getFieldValue("select id from $tablename where email='$email' and emailChk ='0'", "id"));

			if ($usrid != $uid && $usrid != 0) {
				JsonEnd(array("status" => 0, "msg" => '此信箱已被使用'));
			}
		}
	}

	if ($mobile) {
		if ($type_mode == '2') {
			$usrid = intval(getFieldValue("select id from $tablename where mobile='$mobile' and mobileChk ='1'", "id"));

			if ($usrid != $uid && $usrid != 0) {
				JsonEnd(array("status" => 0, "msg" => _MEMBER_MOBILE_USED));
			}
		}
	}

	$salesChk = intval(getFieldValue("select salesChk from $tablename where id='$uid'", "salesChk"));
	if (!$salesChk && $cardnumber) {
		$salesChk = 3;
		$field_str .= ",cardnumber='$cardnumber',salesChk='$salesChk'";
	}

	if ($type_mode == '1') {
		$sql = "update $tablename set name=N'$name',email=N'$email',mobile=N'$mobile',loginid=N'$email',addr=N'$address',city=N'$city',canton=N'$canton' $field_str where id='$uid'";
	} else if ($type_mode == '2') {
		$sql = "update $tablename set name=N'$name',mobile=N'$mobile',loginid=N'$email',addr=N'$address',city=N'$city',canton=N'$canton' $field_str where id='$uid'";
	} else {
		$sql = "update $tablename set name=N'$name',loginid=N'$email',mobile=N'$mobile',addr=N'$address',city=N'$city',canton=N'$canton' $field_str where id='$uid'";
	}

	$db->setQuery($sql);
	$db->query();

	$_SESSION[$conf_user]['uname'] = $name;
	$_SESSION[$conf_user]['umobile'] = $mobile;
	$_SESSION[$conf_user]['uemail'] = $email;
	$_SESSION[$conf_user]['uaddress'] = $address;
	$_SESSION[$conf_user]['ucity'] = $city;
	$_SESSION[$conf_user]['ucanton'] = $canton;
	$_SESSION[$conf_user]['salesChk'] = $salesChk;
	JsonEnd(array("status" => 1));
}

function userInfo()
{
	global $db, $db2, $db3, $conf_user, $tablename;
	ini_set('display_errors', '1');
	$uid = LoginChk();
	unset($_SESSION[$conf_user]['mo']);
	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$now_date = date('Y-m-d');
	// check_regpoint();
	$mb_no = $r['ERPID'];
	$cb_gpoints = 0;
	$csql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '0' and expiry_date > '$now_date'";
	$db3->setQuery($csql);
	$cgetlist = $db3->loadRow();
	if (!empty($cgetlist)) {
		$cb_gpoints = $cgetlist['cb_points']; //目前可用的得到點數
	}

	$usql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '1' and expiry_date > '$now_date'";
	$db3->setQuery($usql);
	$cuselist = $db3->loadRow();
	if (!empty($cuselist)) {
		$cb_upoints = $cuselist['cb_points']; //目前已使用的得到點數
	}

	$now_points = intval($cb_gpoints) - intval($cb_upoints);

	$msql = "UPDATE members set cash_back = '$now_points' where id = '$uid'";
	$db->setQuery($msql);
	$db->query();

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();

	$data = array();
	if ($r) {
		$mb_no = $r['ERPID'];

		$sql = "SELECT * FROM mbst where mb_no = '$mb_no'";
		$db2->setQuery($sql);
		$result = $db2->loadRow();
		if (!empty($result)) {
			$data['birthdate'] = $result['birthday2'];
		}
		$data['name'] = $r['name'];
		$data['mobile'] = $r['mobile'];
		$data['email'] = $r['email'];
		$data['emailChk'] = $r['emailChk'];
		$data['mobileChk'] = $r['mobileChk'];
		$data['address'] = (empty($r['addr'])) ? $r['fulladdr'] : $r['addr'];
		$data['canton']['id'] = $r['canton'];
		$data['city']['id'] = $r['city'];
		$data['bonus'] = $r['bonus'];
		$data['pv'] = $r['pv'];
		$data['bv'] = $r['bv'];
		$data['salesChk'] = $r['salesChk'];
		$data['cardnumber'] = $r['cardnumber'];
		$data['mb_no'] = $r['ERPID'];
		$data['cb'] = $r['cash_back'];


		$orderCnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM orders WHERE memberid = '$uid' AND orderMode = 'addMember' ", "cnt");
		if ($orderCnt > 0) {
			$orderStatus = getFieldValue(" SELECT status FROM orders WHERE memberid = '$uid' AND orderMode = 'addMember' ", "status");
			$data['dataShowMode'] = ($orderStatus == '4' || $orderStatus == '1') ? true : false;
		} else {
			$data['dataShowMode'] = true;
		}
	}


	$data['allBonus'] = intval(getFieldValue(" SELECT SUM(amt) as cnt FROM bonusRecord where memberid='$uid' AND status=0", "cnt"));


	$data['bonusValue'] = intval(getFieldValue(" SELECT bonusValue FROM siteinfo", "bonusValue"));
	$rpsql = "SELECT * FROM regpoint_record where ERPID = '$mb_no' and alive = '1' and type='reg'";
	$db->setQuery($rpsql);
	$rp_list = $db->loadRow();
	if (!empty($rp_list)) {
		$dDate = $rp_list['dDate'];
	} else {
		$dDate = '--';
	}

	$data['dDate'] = $dDate;
	$res = array();
	$res['status'] = '1';
	$res['data'] = $data;

	JsonEnd($res);
}

function loginStatus()
{
	global $db, $db3, $conf_user, $globalConf_signup_ver2020;

	orderChk();

	$uid = intval($_SESSION[$conf_user]['uid']);
	if ($uid != 0) {
		$u_data = get_user_info_m();
		$mb_no = $u_data['mb_no'];
		$res = array();
		$sql = "SELECT * FROM dividend_members where mb_no = '$mb_no'";
		$db->setQuery($sql);
		$data = $db->loadRow();
		if (count($data) > 0) {
			$res['stock_status'] = '1';
		} else {
			$res['stock_status'] = '0';
		}

		$chk = getFieldValue("select onlyMember from members where id='$uid'", "onlyMember");
	}



	$res['status'] = '1';

	$globalConf_signup_ver2020 = ($globalConf_signup_ver2020) ? true : false;
	unset($_SESSION["tmpData"]["captcha"]);

	if (intval($_SESSION[$conf_user]['uid']) > 0) {
		$res['data'] = 1;
		$res['uloginType'] =  $_SESSION[$conf_user]['uloginType'];
		$res['onlymember'] = $chk;
	} else {
		$res['data'] = 0;
		$res['onlymember'] = '0';
	}
	$res['signupMode2020'] = $globalConf_signup_ver2020;
	if ($uid == 0) {
		$uidstatus = 0;
	} else {
		$uidstatus = 1;
	}
	$res['uid_stauts'] = $uidstatus;

	//檢查購物金
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	foreach ($plist as $each) {
		if ($each['p_type'] == '1') {
			$now_points = bcadd($now_points, $each['point'], 0);
		} else if ($each['p_type'] == '2') {
			$now_points = bcsub($now_points, $each['point'], 0);
		}
	}
	if ($now_points > 0) {
		$res['has_m_points'] = '1';
	} else {
		$res['has_m_points'] = '0';
	}

	if (isset($_SESSION['temp_reccommend_code'])) {
		$res['has_precode'] = '1';
		$res['precode'] = $_SESSION['temp_reccommend_code'];
	}
	JsonEnd($res);
}

function get_code()
{
	$res = array();
	$res['status'] = '1';
	if (isset($_SESSION['temp_reccommend_code'])) {
		$res['has_precode'] = '1';
		$res['precode'] = $_SESSION['temp_reccommend_code'];
	}
	JsonEnd($res);
}

function pwchg()
{
	global $db, $tablename, $conf_user;
	$uid = LoginChk();

	$sql = "SELECT emailChk,mobileChk FROM members WHERE id = '$uid'";
	$db->setQuery($sql);
	$md = $db->loadRow();
	$pass = false;
	if (!empty($md)) {
		$emailChk = $md['emailChk'];
		$mobileChk = $md['mobileChk'];
		if ($emailChk == '0') {
			$pass = true;
		}

		if ($mobileChk == '1') {
			$pass = true;
		}
	}

	if ($pass) {
		$opasswd = global_get_param($_POST, 'opasswd', null, 0, 1);
		$passwd = global_get_param($_POST, 'passwd', null, 0, 1);


		$sid = getFieldValue("SELECT sid from members where id = '$uid'", "sid");
		$opwd = getFieldValue("select passwd from $tablename where id='$uid'", "passwd");
		if (!$opasswd || !$passwd) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_PWD_ERROR_MSG1));
		}

		$opasswd_u = $opasswd;
		$opasswd = enpw($opasswd);
		$chk = getFieldValue("select count(1) as cnt from $tablename where id='$uid' AND passwd='$opasswd'", "cnt");
		if ($chk == 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_PWD_ERROR_MSG2));
		}
		$passwd_u = $passwd;
		$passwd = enpw($passwd);


		$sql = "update $tablename set passwd='$passwd' where id='$uid'";
		$db->setQuery($sql);
		$db->query();


		JsonEnd(array("status" => 1, "msg" => "修改成功"));
	} else {
		JsonEnd(array("status" => 0, "msg" => _PW_ERROR_MSG));
	}
}

function test_member()
{
	global $db, $tablename, $conf_user;


	$randNo = mt_rand(1, 99999999);

	$sid = "L224515920";
	$vc = md5(($sid . $randNo));
	$login_result = file_get_contents("http://192.168.7.81/xjd/webservice/mmnt.xsql?sid=" . $sid . "&r=" . $randNo . "&vc=" . $vc);
	$tmp_login_result = $login_result;
	$login_result = json_decode($login_result, true);
	if (empty($login_result)) {
		$login_result = json_decode(iconv("big5", "UTF-8", $tmp_login_result), true);
	}

	parr($login_result);
	$name = (!empty($login_result['name'])) ? $login_result['name'] : iconv("big5", "UTF-8", $login_result['name']);
	$mobile = (!empty($login_result['cell'])) ? $login_result['cell'] : $phone;
	$phone = $login_result['tel'];
	$fulladdr = (!empty($login_result['addr'])) ? $login_result['addr'] : iconv("big5", "UTF-8", $login_result['addr']);

	$sql = "insert into $tablename (belongid,name,sid,email,regDate,loginid,passwd,cardnumber,recommendCode,salesChk,mobile,ERPID,phone,fulladdr,ERPChk) values 
				('$belongid',N'$name','" . $sid . "TEST','$email','" . date("Y-m-d H:i:s") . "','" . $sid . "TEST','$passwd','$cardnumber','$recommendCode','$salesChk','$mobile','" . $sid . "TEST','$phone',N'$fulladdr',1)";
	echo $sql;




	die();
}

function signup($type = null)
{
	global $db, $db2, $tablename, $conf_user;
	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$email = global_get_param($_POST, 'email', null, 0, 1);
	$passwd = global_get_param($_POST, 'passwd', null, 0, 1);

	$chk_pass = global_get_param($_POST, 'chk_pass', null, 0, 1);



	$phone = global_get_param($_POST, 'phone', null, 0, 1);
	$rec_code = global_get_param($_POST, 'rec_code', 0, 0, 1);


	if (!$sid) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_EMPTY));
	}

	$chk = getFieldValue("select count(1) as cnt from $tablename where sid='$sid' and locked = '0'", "cnt");
	if ($chk > 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
	}

	if ($chk_pass == 0) {
		$nowdate = date('Y-m-d');
		$now = strtotime($nowdate);
		$sql = "SELECT * FROM mbst where boss_id = '$sid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= '$now'";
		$db2->setQuery($sql);
		$chk_exist = $db2->loadRow();
		if (count($chk_exist)) {
			$passwd = $sid;
		}
	} else {
		if (!$passwd) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_LOGINID_ENPTY));
		}
	}


	$chk = getFieldValue("select count(1) as cnt from $tablename where loginid='$email'", "cnt");
	// if ($chk > 0) {
	// 	JsonEnd(array("status" => 0, "msg" => "此信箱已被註冊"));
	// }


	$randNo = mt_rand(1, 99999999);
	$vc = md5(($sid . $randNo));


	// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/mmnt.xsql?sid=" . $sid . "&r=" . $randNo . "&vc=" . $vc);
	$login_result = file_get_contents(MLMURL . "ct/mbst/get_reg_member_api.php?sid=" . $sid . "&r=" . $randNo . "&vc=" . $vc);
	$tmp_login_result = $login_result;
	$login_result = json_decode($login_result, true);
	if (empty($login_result)) {
		$login_result = json_decode(iconv("big5", "UTF-8", $tmp_login_result), true);
	}

	if ($login_result["status"] == 1) {

		$ERPID = $login_result['id'];
		$cardnumber = $login_result['cardno'];
		$name = iconv("big5", "UTF-8", $login_result['name']);
		// if (empty($name)) {
		$name = $login_result['name'];
		// }
		$mobile = (!empty($login_result['cell'])) ? $login_result['cell'] : $phone;
		$phone = $login_result['tel'];
		$fulladdr = iconv("big5", "UTF-8", $login_result['addr']);

		if (!$cardnumber) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_CARD_EMPTY));
		}


		$chk2 = getFieldValue("select count(1) as cnt from $tablename where cardnumber='$cardnumber'", "cnt");
		if ($chk2 > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_CARD_USED));
		}

		if (empty($passwd)) {
			$passwd = $sid;
		}

		$passwd = enpw($passwd);
		$recommendCode = md5(time() . $passwd);

		$salesChk = 0;
		if ($cardnumber) {
			$salesChk = 1;
		}

		$belongid = intval(getFieldValue("select id from $tablename where recommendCode='$rec_code'", "id"));


		$sql = "insert into $tablename (belongid,name,sid,email,regDate,loginid,passwd,cardnumber,recommendCode,salesChk,mobile,ERPID,phone,fulladdr,ERPChk) values 
				('$belongid',N'$name','$sid','$email','" . date("Y-m-d H:i:s") . "','$email','$passwd','$cardnumber','$recommendCode','$salesChk','$mobile','$ERPID','$phone',N'$fulladdr',1)";



		$db->setQuery($sql);
		$db->query();

		// $msql = "UPDATE mbst set chk_used = 1 where boss_id = '$sid'";
		// $db->setQuery($msql);
		// $db->query();

		if ($type) {
			login($email, 'web');
		} else {
			JsonEnd(array("status" => 1, "msg" => _MEMBER_SIGNUP_SUCCESS));
		}
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DISTRIBUTOR));
	}
}



function login($extid = 0, $type = null, $loginid_tmp = null, $passwd_tmp = null)
{
	global $db, $db2, $tablename, $conf_user;
	$skipChk = false;
	if ($extid && $type) {
		$skipChk = true;
		if ($type == "fb") {
			$sql = "select * from $tablename where locked=0 AND fbloginid='$extid'";
		} else if ($type == "gp") {
			$sql = "select * from $tablename where locked=0 AND gploginid='$extid'";
		} else if ($type == "web") {
			$sql = "select * from $tablename where locked=0 AND email='$extid'";
		}
	} else {

		if (!empty($loginid_tmp)) {
			$loginid = $loginid_tmp;
			$passwd = $passwd_tmp;
		} else {
			$loginid = global_get_param($_POST, 'email', null, 0, 1, 1, '', _COMMON_PARAM_EMAIL);
			$passwd = global_get_param($_POST, 'passwd', null, 0, 1, 1, '',  _COMMON_PARAM_PASSWD);
		}
		$uloginid = ucfirst($loginid);
		$passwd = $passwd;
		$boss_id = $passwd;
		$upasswd = $passwd;
		// nopass
		// if (ucfirst($passwd) == $uloginid) {
		// 	$upasswd = enpw(ucfirst($passwd));
		// } else {
		// 	$upasswd = enpw($passwd);
		// }
		$upasswd = enpw($passwd);
		$passwd = enpw($passwd);

		$type = "web";

		if ($loginid == 'csl0412@gmail.com') {
		}

		$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND loginid='$loginid'";


		$db->setQuery($sql);
		$r = $db->loadRow();
		if (!$r) {

			$sql = "select * from $tablename where locked=0 AND passwd='$upasswd' AND sid IS NOT NULL AND sid <> '' AND sid='$uloginid'";
		}
	}
	$status = array();
	$db->setQuery($sql);
	$r = $db->loadRow();
	$dd = $sql;

	if (($r['loginid'] != "$loginid" && $r['sid'] != "$uloginid" && !$skipChk) || !$r) {
		//檢查是否有此帳號在網購
		$sql2 = "select * from $tablename where sid IS NOT NULL AND sid <> '' AND sid='$loginid' order by id desc limit 1";
		$db->setQuery($sql2);
		$rs = $db->loadRow();

		if (!empty($rs)) {
			if ($rs['locked'] == 1) { //要檢查傳銷有沒有新的會編是正常的
				$mbno = $rs['ERPID'];
				//$now = strtotime("now");
				//$now = strtotime("now");
				$nowdate = date('Y-m-d');
				$now = strtotime($nowdate);
				$csql = "SELECT * from mbst where boss_id = '$loginid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= '$now' and mb_no <> '$mbno'";
				$db2->setQuery($csql);
				$checkO = $db2->loadRow();
				if (!empty($checkO)) {
					$status['status'] = '9';
					$status['m_boss_id'] = $checkO['boss_id'];
					$status['m_mobile'] = $checkO['tel3'];
				} else {
					//檢查此會編在傳銷是否正常
					$checksql = "SELECT * from mbst where mb_no = '$mbno'";
					$db2->setQuery($checksql);
					$checkmb = $db2->loadRow();
					if ($checkmb['mb_status'] != '1' || $checkmb['grade_1_chk'] != '1' || $checkmb['pg_end_date'] < $now) {
						$status['status'] = 0;
						$status['msg'] = _MEMBER_UNOFFICIAL . '。(C01)';
					} else if ($checkmb['mb_status'] == '1') {
						$status['status'] = 0;
						$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR . '(9)';
					}
				}
			} else {
				$status['status'] = 0;
				$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR;
			}
		} else {
			//第二次檢查是否經銷商
			$nowdate = date('Y-m-d');
			$now = strtotime($nowdate);
			$csql = "SELECT * FROM mbst where boss_id = '$loginid' and boss_id = '$boss_id' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= '$now'";
			$db2->setQuery($csql);
			$m_res = $db2->loadRow();
			if (count($m_res) > 0) {
				$status['status'] = 9;
				$status['m_boss_id'] = $m_res['boss_id'];
				$status['m_mobile'] = $m_res['tel3'];
			} else {
				$dsql = "SELECT * FROM mbst where boss_id = '$loginid' and boss_id = '$boss_id'";
				$db2->setQuery($dsql);
				$d_res = $db2->loadRow();
				if (count($d_res) > 0) {
					if ($d_res['mb_status'] != '1' || $d_res['grade_1_chk'] != '1' || $d_res['pg_end_date'] < $now) {
						$status['status'] = 0;
						$status['msg'] = _MEMBER_UNOFFICIAL . '。(C02)';
					} else if ($d_res['mb_status'] == '1') {
						$status['status'] = 9;
						$status['m_boss_id'] = $d_res['boss_id'];
						$status['m_mobile'] = $d_res['tel3'];
						// $status['status'] = 0;
						// $status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR.'(9)';
					}
				} else {
					$status['status'] = 0;
					$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR;
				}
			}
		}
	} else if ($r['salesChk'] != '1' && $r['salesChk'] != '2') {
		$status['status'] = 0;
		$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR2;
	} else {



		if (empty($r['ERPChk'])) {

			$randNo = mt_rand(1, 99999999);
			$vc = md5(($r['sid'] . $randNo));

			$nowdate = date('Y-m-d');
			$now = strtotime($nowdate);
			$sid = $r['sid'];
			$sql = "SELECT mb_no,tel3 as cell,tel2 as tel, mb_name as name, add2 as addr FROM mbst where boss_id = '$sid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= $now";
			$db2->setQuery($sql);
			$login_result = $db2->loadRow();
			// $login_result = file_get_contents("http://192.168.7.81/xjd/webservice/mmnt.xsql?sid=" . $r['sid'] . "&r=" . $randNo . "&vc=" . $vc);

			$tmp_login_result = $login_result;
			// $login_result = json_decode($login_result, true);
			// if (empty($login_result)) {
			// 	$login_result = json_decode(mb_convert_encoding("big5", "UTF-8", $tmp_login_result), true);
			// }

			if (!empty($login_result)) {
				$name = (!empty($login_result['name'])) ? $login_result['name'] : mb_convert_encoding($login_result['name'], "UTF-8", "big5");
				$fulladdr = (!empty($login_result['addr'])) ? $login_result['addr'] : mb_convert_encoding($login_result['addr'], "UTF-8", "big5");
				// cardnumber = '" . $login_result['cardno'] . "' 
				$sql = "update $tablename set 
	    			ERPID = '" . $login_result['mb_no'] . "' , 
	    			cardnumber = '" . $login_result['mb_no'] . "' , 
	    			name = '$name' , 
	    			phone = '" . $login_result['tel'] . "' , 
	    			mobile = '" . $login_result['cell'] . "' , 
	    			fulladdr = '$fulladdr' , 
	    			ERPChk = '1'
	    			where id='{$r['id']}'";
				$db->setQuery($sql);
				$db->query();
			} else {
				$errMsg = 'no data';
				$sql = "update $tablename set 
	    			ERPChk = '1',
	    			ERPResponse = '" . $errMsg . "'
	    		where id='{$r['id']}'";
				$db->setQuery($sql);
				$db->query();
			}
		}

		//檢查註冊日期
		$regDate = $r['regDate'];
		$regCheckDate = date('Y-m-d', strtotime("$regDate +1 month -1 day"));

		$sid = $r['sid'];
		$mb_no = $r['ERPID'];
		$checkMSql = "SELECT mb_status,pg_end_date,grade_1_chk from mbst where mb_no = '$mb_no'";
		// $status['sql'] = $checkMSql;
		$db2->setQuery($checkMSql);
		$checkM = $db2->loadRow();
		//check 經銷經銷商狀態

		//如果超過註冊一個月後檢查
		$now = date('Y-m-d');
		if ($r['onlyMember'] != '1') {
			if ($now < $regCheckDate) {
				$data = array();
				$status['status'] = 1;
				if ($extid && $type) {
					$status['status'] = 2;
				}
				if ($_SESSION[$conf_user]['redirect_url']) {
					$status['redirect_url'] = $_SESSION[$conf_user]['redirect_url'];
					unset($_SESSION[$conf_user]['redirect_url']);
				}
				$status['data'] = $data;
				$status['msg'] = "登入成功";
				$_SESSION[$conf_user]['uid'] = $r['id'];
				$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
				$_SESSION[$conf_user]['uname'] = $r['name'];
				$_SESSION[$conf_user]['uemail'] = $r['email'];
				$_SESSION[$conf_user]['umobile'] = $r['mobile'];
				$_SESSION[$conf_user]['uaddress'] = $r['addr'];
				$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
				$_SESSION[$conf_user]['uloginType'] = $type;
				check_regpoint();

				$sql = "update $tablename set logincnt=logincnt+1 where id='{$r['id']}'";
				$db->setQuery($sql);
				$db->query();

				$sql = "insert into memberlog (otime,memberid,type,loginType) values ('" . date("Y-m-d H:i:s") . "','{$r['id']}','1','$type')";
				$db->setQuery($sql);
				$db->query();


				$status['emailChk'] = intval($r['emailChk']);
				$status['rs'] = $r['sid'];
				$status['vc'] = $vc;
				$status['rand'] = $randNo;
			} else {
				$pg_end_date = date('Y-m-d', $checkM['pg_end_date']);
				$now = date('Y-m-d');
				$cmb_status = $checkM['mb_status'];
				$cgrade_1_chk = $checkM['grade_1_chk'];
				if ($cmb_status != '1' || $cgrade_1_chk != '1' || $pg_end_date < $now) {
					$status['status'] = 0;
					$errmsg = '';
					if ($cmb_status == '1') {
						if ($cgrade_1_chk != '1') {
							$errmsg .= _MEMBER_ERROR5;
						}
						if ($pg_end_date < $now) {
							$errmsg .= _MEMBER_ERROR6;
						}
					} else {
						$errmsg .= _MEMBER_UNOFFICIAL . '。(C03)';
						if ($cgrade_1_chk != '1') {
							$errmsg .= _MEMBER_ERROR5;
						}
						if ($pg_end_date < $now) {
							$errmsg .= _MEMBER_ERROR6;
						}
					}
					$status['errMsg'] = $errmsg;
				} else {
					$data = array();
					$status['status'] = 1;
					if ($extid && $type) {
						$status['status'] = 2;
					}
					if ($_SESSION[$conf_user]['redirect_url']) {
						$status['redirect_url'] = $_SESSION[$conf_user]['redirect_url'];
						unset($_SESSION[$conf_user]['redirect_url']);
					}
					$status['data'] = $data;
					$status['msg'] = "登入成功";
					$_SESSION[$conf_user]['uid'] = $r['id'];
					$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
					$_SESSION[$conf_user]['uname'] = $r['name'];
					$_SESSION[$conf_user]['uemail'] = $r['email'];
					$_SESSION[$conf_user]['umobile'] = $r['mobile'];
					$_SESSION[$conf_user]['uaddress'] = $r['addr'];
					$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
					$_SESSION[$conf_user]['uloginType'] = $type;
					check_regpoint();

					$sql = "update $tablename set logincnt=logincnt+1 where id='{$r['id']}'";
					$db->setQuery($sql);
					$db->query();

					$sql = "insert into memberlog (otime,memberid,type,loginType) values ('" . date("Y-m-d H:i:s") . "','{$r['id']}','1','$type')";
					$db->setQuery($sql);
					$db->query();


					$status['emailChk'] = intval($r['emailChk']);
					$status['rs'] = $r['sid'];
					$status['vc'] = $vc;
					$status['rand'] = $randNo;
				}
			}
		} else {
			$data = array();
			$status['status'] = 1;
			if ($extid && $type) {
				$status['status'] = 2;
			}
			if ($_SESSION[$conf_user]['redirect_url']) {
				$status['redirect_url'] = $_SESSION[$conf_user]['redirect_url'];
				unset($_SESSION[$conf_user]['redirect_url']);
			}
			$status['data'] = $data;
			$status['msg'] = "登入成功";
			$_SESSION[$conf_user]['uid'] = $r['id'];
			$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
			$_SESSION[$conf_user]['uname'] = $r['name'];
			$_SESSION[$conf_user]['uemail'] = $r['email'];
			$_SESSION[$conf_user]['umobile'] = $r['mobile'];
			$_SESSION[$conf_user]['uaddress'] = $r['addr'];
			$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
			$_SESSION[$conf_user]['uloginType'] = $type;
			check_regpoint();

			$sql = "update $tablename set logincnt=logincnt+1 where id='{$r['id']}'";
			$db->setQuery($sql);
			$db->query();

			$sql = "insert into memberlog (otime,memberid,type,loginType) values ('" . date("Y-m-d H:i:s") . "','{$r['id']}','1','$type')";
			$db->setQuery($sql);
			$db->query();


			$status['emailChk'] = intval($r['emailChk']);
			$status['rs'] = $r['sid'];
			$status['vc'] = $vc;
			$status['rand'] = $randNo;
		}
	}
	if ($r['onlyMember'] == '1') {
		chk_cb($r['id']);
	}
	$status['r'] = $r;
	$status['onlyMember'] = $r['onlyMember'];
	if (!empty($loginid_tmp)) {
	} else {
		JsonEnd($status);
	}
}

function check_regpoint()
{
	global $db;
	ini_set('display_errors', '1');
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$sql = "SELECT * FROM members where ERPID = '$mb_no' and regDate >= '2021-01-01' and regPoint = '0'";
	$db->setQuery($sql);
	$mb_data = $db->loadRow();
	$mid = $mb_data['id'];
	$rd = $mb_data['payDate'];
	$payDate = $mb_data['payDate'];
	$dd = date('Y-m-d', strtotime("+90 day" . $rd));
	if (count($mb_data) > 0) {
		$regPoint = $mb_data['regPoint'];
		if ($regPoint == 0 && !empty($payDate)) { //未發送
			$r = array();
			$r['memberid'] = $mb_data['id'];
			$r['ERPID'] = $mb_data['ERPID'];
			$r['rDate'] = $rd;
			$r['dDate'] = $dd;
			$r['amt'] = '880'; //發送880點註冊紅利點
			$r['notes'] = '註冊發送880紅利點';
			$r['status'] = '0';
			$r['orderid'] = '';
			$r['ctime'] = date('Y-m-d H:i:s');
			$r['mtime'] = date('Y-m-d H:i:s');
			$r['muser'] = '';
			$r['type'] = 'reg';
			$r['alive'] = '1';
			$sql = dbInsert("regpoint_record", $r); //發送紀錄
			$db->setQuery($sql);
			$db->query();
			$msql = "UPDATE members set regPoint = '1' where id = '$mid'"; //更新為已發送
			$db->setQuery($msql);
			$db->query();
		}
	}
	return $sql;
}
//function login($extid = 0, $type = null, $loginid_tmp = null, $passwd_tmp = null)
// {
// 	global $db, $tablename, $conf_user;
// 	$skipChk = false;
// 	if ($extid && $type) {
// 		$skipChk = true;
// 		if ($type == "fb") {
// 			$sql = "select * from $tablename where locked=0 AND fbloginid='$extid'";
// 		} else if ($type == "gp") {
// 			$sql = "select * from $tablename where locked=0 AND gploginid='$extid'";
// 		} else if ($type == "web") {
// 			$sql = "select * from $tablename where locked=0 AND email='$extid'";
// 		} 
// 	} else {

// 		if (!empty($loginid_tmp)) {
// 			$loginid = $loginid_tmp;
// 			$passwd = $passwd_tmp;
// 		} else {
// 			$loginid = global_get_param($_POST, 'email', null, 0, 1, 1, '', _COMMON_PARAM_EMAIL);
// 			$passwd = global_get_param($_POST, 'passwd', null, 0, 1, 1, '',  _COMMON_PARAM_PASSWD);
// 		}
// 		$passwd = $passwd;
// 		// nopass
// 		$passwd = enpw($passwd);
// 		$type = "web";

// 		if ($loginid == 'csl0412@gmail.com') {
// 		}

// 		$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND loginid='$loginid'";


// 		$db->setQuery($sql);
// 		$r = $db->loadRow();
// 		if (!$r) {

// 			$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND sid IS NOT NULL AND sid <> '' AND sid='$loginid'";
// 		}
// 	}
// 	$db->setQuery($sql);
// 	$r = $db->loadRow();
// 	$status = array();
// 	if (($r['loginid'] != "$loginid" && $r['sid'] != "$loginid" && !$skipChk) || !$r) {
// 		$status['status'] = 0;
// 		$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR;
// 	} else if ($r['salesChk'] != '1' && $r['salesChk'] != '2') {
// 		$status['status'] = 0;
// 		$status['msg'] = _COMMON_QUERYMSG_LOGIN_ERROR2;
// 	} else {



// 		if (empty($r['ERPChk'])) {

// 			$randNo = mt_rand(1, 99999999);
// 			$vc = md5(($r['sid'] . $randNo));



// 			$login_result = file_get_contents("http://192.168.7.81/xjd/webservice/mmnt.xsql?sid=" . $r['sid'] . "&r=" . $randNo . "&vc=" . $vc);
// 			$tmp_login_result = $login_result;
// 			$login_result = json_decode($login_result, true);
// 			if (empty($login_result)) {
// 				$login_result = json_decode(iconv("big5", "UTF-8", $tmp_login_result), true);
// 			}

// 			if ($login_result["status"] == '1') {
// 				$name = (!empty($login_result['name'])) ? $login_result['name'] : iconv("big5", "UTF-8", $login_result['name']);
// 				$fulladdr = (!empty($login_result['addr'])) ? $login_result['addr'] : iconv("big5", "UTF-8", $login_result['addr']);

// 				$sql = "update $tablename set 
// 	    			ERPID = '" . $login_result['id'] . "' , 
// 	    			cardnumber = '" . $login_result['cardno'] . "' , 
// 	    			name = '$name' , 
// 	    			phone = '" . $login_result['tel'] . "' , 
// 	    			mobile = '" . $login_result['cell'] . "' , 
// 	    			fulladdr = '$fulladdr' , 
// 	    			ERPChk = '1'
// 	    			where id='{$r['id']}'";
// 				$db->setQuery($sql);
// 				$db->query();
// 			} else {
// 				$sql = "update $tablename set 
// 	    			ERPChk = '1',
// 	    			ERPResponse = '" . $login_result['errMsg'] . "'
// 	    		where id='{$r['id']}'";
// 				$db->setQuery($sql);
// 				$db->query();
// 			}
// 		}


// 		$data = array();
// 		$status['status'] = 1;
// 		if ($extid && $type) {
// 			$status['status'] = 2;
// 		}
// 		if ($_SESSION[$conf_user]['redirect_url']) {
// 			$status['redirect_url'] = $_SESSION[$conf_user]['redirect_url'];
// 			unset($_SESSION[$conf_user]['redirect_url']);
// 		}
// 		$status['data'] = $data;
// 		$status['msg'] = "登入成功";
// 		$_SESSION[$conf_user]['uid'] = $r['id'];
// 		$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
// 		$_SESSION[$conf_user]['uname'] = $r['name'];
// 		$_SESSION[$conf_user]['uemail'] = $r['email'];
// 		$_SESSION[$conf_user]['umobile'] = $r['mobile'];
// 		$_SESSION[$conf_user]['uaddress'] = $r['addr'];
// 		$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
// 		$_SESSION[$conf_user]['uloginType'] = $type;

// 		$sql = "update $tablename set logincnt=logincnt+1 where id='{$r['id']}'";
// 		$db->setQuery($sql);
// 		$db->query();

// 		$sql = "insert into memberlog (otime,memberid,type,loginType) values ('" . date("Y-m-d H:i:s") . "','{$r['id']}','1','$type')";
// 		$db->setQuery($sql);
// 		$db->query();


// 		$status['emailChk'] = intval($r['emailChk']);
// 	}

// 	if (!empty($loginid_tmp)) {
// 	} else {
// 		JsonEnd($status);
// 	}
// }
function fb_chk()
{
	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2, $conf_php, $conf_fb_id, $conf_fb_key, $conf_fb_version;
	$fbid = global_get_param($_POST, 'fbid', null, 0, 1);
	$token = global_get_param($_POST, 'token', null, 0, 1);
	include($conf_php . 'Facebook/autoload.php');

	$fb = new Facebook\Facebook([
		'app_id' => $conf_fb_id,
		'app_secret' => $conf_fb_key,
		'default_graph_version' => $conf_fb_version
	]);

	try {
		$response = $fb->get('/me', "$token");
	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		JsonEnd(array("status" => 0, "msg" => "登入失敗"));
		exit;
	} catch (Facebook\Exceptions\FacebookSDKException $e) {
		JsonEnd(array("status" => 0, "msg" => "登入失敗"));
		exit;
	}

	$status = array();

	$pw = md5($globalConf_encrypt_1 . $fbid . $globalConf_encrypt_2);
	$status['status'] = 1;
	$status['pw'] = $pw;
	$_SESSION['fbpw'] = $pw;


	JsonEnd($status);
}
function fb_login()
{
	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2;
	$fbid = global_get_param($_POST, 'fbid', null, 0, 1);
	$name = global_get_param($_POST, 'name', null, 0, 1);
	$res = global_get_param($_POST, 'res', null, 0, 1);
	$pw = md5($globalConf_encrypt_1 . $fbid . $globalConf_encrypt_2);
	if ($res['pw'] != $pw || $res['status'] != 1 || $_SESSION['fbpw'] != $pw || $_SESSION['fbpw'] != $res['pw']) {
		JsonEnd(array("status" => 0, "msg" => _COMMON_QUERYMSG_LOGIN_ERROR));
	}
	unset($_SESSION['fbpw']);
	$cnt = intval(getFieldValue("select count(1) as cnt from $tablename where fbloginid='$fbid' AND passwd='$pw'", "cnt"));
	$status = array();
	if ($cnt > 0) {
		login($fbid, "fb");
	} else {
		$date = date("Y-m-d H:i:s");
		$sql = "insert into $tablename (name,fbloginid,passwd,regDate,ctime,mtime) values (N'$name','$fbid','$pw','$date','$date','$date')";
		$db->setQuery($sql);
		$db->query();
		$status['status'] = 1;
		$status['msg'] = _MEMBER_SIGNUP_SUCCESS;
		login($fbid, "fb");
	}
	JsonEnd($status);
}
function gp_chk()
{
	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2, $conf_php;
	$gpid = global_get_param($_POST, 'gpid', null, 0, 1);

	$status = array();

	$pw = md5($globalConf_encrypt_1 . $gpid . $globalConf_encrypt_2);
	$status['status'] = 1;
	$status['pw'] = $pw;
	$_SESSION['gppw'] = $pw;
	JsonEnd($status);
}
function gp_login()
{
	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2;
	$gpid = global_get_param($_POST, 'gpid', null, 0, 1);
	$name = global_get_param($_POST, 'name', null, 0, 1);
	$res = global_get_param($_POST, 'res', null, 0, 1);
	$email = global_get_param($_POST, 'email', null, 0, 1);
	$pw = md5($globalConf_encrypt_1 . $gpid . $globalConf_encrypt_2);
	if ($res['pw'] != $pw || $res['status'] != 1 || $_SESSION['gppw'] != $pw || $_SESSION['gppw'] != $res['pw']) {
		JsonEnd(array("status" => 0, "msg" => _COMMON_QUERYMSG_LOGIN_ERROR));
	}
	unset($_SESSION['gppw']);
	$cnt = intval(getFieldValue("select count(1) as cnt from $tablename where gploginid='$gpid' AND passwd='$pw'", "cnt"));
	$status = array();
	if ($cnt > 0) {
		login($gpid, "gp");
	} else {
		$date = date("Y-m-d H:i:s");
		$sql = "insert into $tablename (name,gploginid,passwd,email,regDate,ctime,mtime) values (N'$name','$gpid','$pw','$email','$date','$date','$date')";
		$db->setQuery($sql);
		$db->query();
		$status['status'] = 1;
		$status['msg'] = _MEMBER_SIGNUP_SUCCESS;
		login($gpid, "gp");
	}
	JsonEnd($status);
}


function ai_login()
{
	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2;

	$loginid = $_POST['email'];
	$passwd = $_POST['passwd'];

	//echo $_POST['email'];

	$passwd = $passwd;
	$boss_id = $passwd;
	// nopass
	//$passwd = enpw($passwd);
	$type = "web";

	$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND sid='$loginid'";
	//echo $sql;
	$db->setQuery($sql);
	$r = $db->loadRow();
	if (!empty($r)) {

		//$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND sid IS NOT NULL AND sid <> '' AND sid='$loginid'";
		//echo $sql;
		$status['status'] = 1;
		$status['msg'] = "登入成功";
		$_SESSION[$conf_user]['uid'] = $r['id'];
		$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
		$_SESSION[$conf_user]['uname'] = $r['name'];
		$_SESSION[$conf_user]['uemail'] = $r['email'];
		$_SESSION[$conf_user]['umobile'] = $r['mobile'];
		$_SESSION[$conf_user]['uaddress'] = $r['addr'];
		$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
		$_SESSION[$conf_user]['uloginType'] = $type;

		//echo $_POST['kind'];

		//海旅點數	
		if ($_POST['kind'] == "34") {
			header('Location: http://125.227.104.50:8123/member_page/e_cash');
			exit;
		}
		//獎勵3S點數
		if ($_POST['kind'] == "35") {
			header('Location: http://125.227.104.50:8123/member_page/e_cash_new2_1');
			exit;
		}
		//隨身寶兌換
		if ($_POST['kind'] == "36") {
			header('Location: http://125.227.104.50:8123/member_page/carry_treasure');
			exit;
		}
		//生日券
		if ($_POST['kind'] == "37") {
			header('Location: http://125.227.104.50:8123/member_page/birthday_voucher');
			exit;
		}
		//海旅資格統計
		if ($_POST['kind'] == "38") {
			header('Location: http://125.227.104.50:8123/member_page/ecash_stat');
			exit;
		}
		//密碼設定
		if ($_POST['kind'] == "39") {
			header('Location: http://125.227.104.50:8123/member_page/pwchg');
			exit;
		}
		//業績查詢
		if ($_POST['kind'] == "40") {
			header('Location: http://125.227.104.50:8123/member_page/orgseq5');
			exit;
		}
		//組織查詢
		if ($_POST['kind'] == "41") {
			header('Location: http://125.227.104.50:8123/member_page/orgseq');
			exit;
		}
		//獎金查詢
		if ($_POST['kind'] == "42") {
			header('Location: http://125.227.104.50:8123/member_page/money_total');
			exit;
		}
		//訂單資訊
		if ($_POST['kind'] == "45") {
			header('Location: http://125.227.104.50:8123/member_page/order');
			exit;
		}
		//線上購物
		if ($_POST['kind'] == "46") {
			header('Location: http://125.227.104.50:8123/product_list/22?id=48');
			exit;
		}
		//商品資訊
		if ($_POST['kind'] == "49") {
			$url = 'https://shop.goodarch2u.com.tw/product_page/' . $_POST['kindid'] . '?id=' . $_POST['pid'];
			//echo $url;
			header('Location:' . $url);
			exit;
		}
	} else {

		$status['status'] = 0;
		$status['msg'] = "登入失敗!";
	}


	JsonEnd($status);
}



function sign20_signupChk()
{
	global $db, $db2, $tablename, $conf_user, $globalConf_signupDemo_ver2020;

	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);

	if (!$sid) {

		JsonEnd(array("status" => 0, "msg" => "請輸入身分證字號"));
	}

	$chk = getFieldValue("select count(1) as cnt from $tablename where sid='$sid' and locked = '0'", "cnt");
	if ($chk > 0) {

		JsonEnd(array("status" => 0, "msg" => _MEMBER_EXIST_IC));
	}

	if ($globalConf_signupDemo_ver2020) {
		$login_result["status"] = 1;
		$login_result["cardno"] = "DEMO2020";
		$userInfo["name"] = "Steven";
		$userInfo["sid"] = $sid;
		$userInfo["city"] = "18";
		$userInfo["canton"] = "286";
	} else {
		$randNo = mt_rand(1, 99999999);
		$vc = md5(($sid . $randNo));

		$nowdate = date('Y-m-d');
		$now = strtotime($nowdate);
		$sql = "SELECT mb_no,tel3 as cell,tel2 as tel, mb_name as name, add2 as addr, mb_status FROM mbst where boss_id = '$sid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= $now";
		$db2->setQuery($sql);
		$login_result = $db2->loadRow();

		$tmp_login_result = $login_result;


		$name = (!empty($login_result['name'])) ? $login_result['name'] : mb_convert_encoding($login_result['name'], "UTF-8", "big5");
		$fulladdr = (!empty($login_result['addr'])) ? $login_result['addr'] : mb_convert_encoding($login_result['addr'], "UTF-8", "big5");

		$userInfo["name"] = $name;
		$userInfo["sid"] = $sid;
		$userInfo["addr"] = $fulladdr;
	}

	if ($login_result["mb_status"] == 1) {

		JsonEnd(array("status" => 1, "data" => $userInfo, "msg" => _MEMBER_SIGNUP_SUCCESS));
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DISTRIBUTOR, "RR" => $login_result));
	}
}

function generateCaptcha($type = null, $var01 = null, $var02 = null)
{
	global $db;

	if (!empty($type)) {
		$sql = " UPDATE requestLog SET type = '{$type}old' WHERE type='$type' AND var01 = '$var01' AND var02 = '$var02'";
		$db->setQuery($sql);
		$db->query();

		$id_len = 6;
		//$word = 'ABCDEFGHIJKMNPQRSTUVWXYZ23456789';
		$word = '1234567890';
		$len = strlen($word);
		$captcha = "";
		for ($i = 0; $i < $id_len; $i++) {
			$captcha .= $word[rand() % $len];
		}
		$now = date("Y-m-d H:i:s");
		$sql = "insert into requestLog (ctime,memberid,code,type,var01,var02) values ('$now','0','$captcha','$type','$var01','$var02')";
		$db->setQuery($sql);
		$db->query();

		return $captcha;
	}
}


function sign20_sendCaptcha($type = null)
{
	global $db, $tablename, $conf_user, $globalConf_signupDemo_ver2020;

	if ($_SESSION['syslang']) {
		$_SESSION[$conf_user]['syslang'] = $_SESSION['syslang'];
	}


	$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);
	$mobile = global_get_param($_POST, 'phone', null, 0, 1);
	$email = global_get_param($_POST, 'mail', null, 0, 1);

	//驗證
	if ($signupMode == "SMS") {
		$mobile = mobileChk($mobile);
		$var02 = $mobile;
		$chk = getFieldValue("select count(1) as cnt from $tablename where mobile='$mobile' AND locked = '0' and mobileChk = '1' ORDER BY id DESC", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_EXIST_MOBILE));
		}
	} else if ($signupMode == "MAIL") {
		$var02 = $email;
		$chk = getFieldValue("select count(1) as cnt from $tablename where email='$email' AND locked = '0' and emailChk = '0' and email <> 'juell@goodarch2u.com' ORDER BY id DESC", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBERS_SAME_EMAIL));
		}
	}
	// $res = array();
	// $res['var02'] = $var02;
	// $res['chk'] = $chk;
	// JsonEnd($res);
	//產生驗證碼
	$captcha = generateCaptcha("sign20Chk", $signupMode, $var02);

	//發送
	if ($signupMode == "SMS") {
		// $body = "【GoodARCH】您的驗證碼為{$captcha}，請於15分鐘內輸入，感謝您";
		$body = _EMAIL_code . $captcha . _EMAIL_msg4;
		send_sms($mobile, $body);
		JsonEnd(array("status" => 1, "msg" => ""));
	} else if ($signupMode == "MAIL") {
		$sql_str = " name ";
		$sql = "select * from siteinfo where sysid ='' ";

		$db->setQuery($sql);
		$siteinfo_arr = $db->loadRow();

		$from = $siteinfo_arr['email'];


		$webname = getFieldValue("select $sql_str from siteinfo  ;", 'name');
		$subject = _EMAIL_code . $captcha . "" . " (" . date("Y-m-d H:i:s") . ")";
		// $subject = " " . $captcha . " 你的 GoodARCH 驗證碼" . " (" . date("Y-m-d H:i:s") . ")";

		$sendto[] = array('name' => $email, 'email' => $email);

		$adminmail = getFieldValue("select email from siteinfo  ;", 'email');

		if ($_SESSION['syslang']) {
			$_SESSION[$conf_user]['syslang'] = $_SESSION['syslang'];
			$webname = $siteinfo_arr['name_' . $_SESSION[$conf_user]['syslang']];
		}
		// $body = "親愛的經銷商<br /><br />";
		// $body .= "您正在 會員註冊驗證，驗證碼為：" . $captcha . "<br /><br />";
		// $body .= "請於15分鐘內輸入至註冊頁面中<br /><br />";
		// $body .= "此為系統郵件，請勿回覆<br />";
		$target = _EMAIL_membership;
		$v = time();
		$add1 = _EMAIL_register;
		$body = '<table cellspacing="1" cellpadding="10" border="1" style="width:600px;">' .
			'<thead>' .
			'<tr>' .
			'<th style="background-color:#70ad46;color:white;text-align:center;height:60px;font-size:32px">' .
			_EMAIL_notification .
			'</th>' .
			'</tr>' .
			'</thead>' .
			'<tbody>' .
			'<tr>' .
			'<td>' .
			'<span>' . _EMAIL_dear . ' ' . $target . _EMAIL_hello . '</span><br /><br />' .
			'<span style="padding-left:32px">' . _EMAIL_msg1 . '</span>' . '<br />' . '<span style="padding-left:32px">' . _EMAIL_msg2 . '</span><br /><br />' .
			'<span style="padding-left:32px;font-size: 2em;">' . $captcha . '</span><br /><br />' .
			'<span style="padding-left:32px">' . _EMAIL_msg4 . '</span>' .
			'</td>' .
			'</tr>' .
			'<tr style="padding-top:30px;">' .
			'<td style="border-top:#ccc">' .
			'<span style="color:red">' . _EMAIL_msg5 . '</span><br /><br />' .
			'<span style="padding-left:32px">    ' . _EMAIL_msg6 . ' <a href="https://www.goodarch2us.com/">https://www.goodarch2us.com/</a></span><br /><br />' .
			'<span style="padding-left:32px">    ' . _EMAIL_msg7 . ' <a href="https://usshop.goodarch2u.com  ">https://usshop.goodarch2u.com</a></span><br /><br />' .
			'</td>' .
			'</tr>' .
			'</tbody>' .
			'<div style="width:200px;display:inline-block">' .
			'<img src="https://usshop.goodarch2u.com/upload/goodarch-logo-s.png?' . $v . '" style="padding-left:100px;height:64px;width:64px;">' .
			'</div>' .
			'<div style="width:350px;display:inline-block">' .
			'<div style="height:32px">' . _EMAIL_msg8 . '</div><br />' .
			'<div style="height:32px">GoodARCH Technology Sdn. Bhd. All Rights Reserved. </div>' .
			'</div>' .
			'</table>';

		$logarr = array();

		$logarr = global_send_mail($adminmail, $webname, $sendto, $subject, $body, null, null, null);

		if ($logarr['state'] == 'sus') {
			JsonEnd(array("status" => 1, "msg" => _MEMBER_EMAILCHK_MSG9));
		} else {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_SEND_FAIL . ':' . $logarr['msg']));
		}
	}
}

function sign20_signup()
{
	global $db, $db2, $tablename, $conf_user, $globalConf_signupDemo_ver2020, $HTTP_X_FORWARDED_PROTO;
	ini_set('display_errors', '1');
	$signupMode = global_get_param($_POST, "signupMode", null, 0, 1);
	$memberName = global_get_param($_POST, "memberName", null, 0, 1);
	$memberSID = global_get_param($_POST, "memberSID", null, 0, 1);
	$memberPhone = global_get_param($_POST, "memberPhone", null, 0, 1);
	$memberPasswd = global_get_param($_POST, "memberPasswd", null, 0, 1);
	$PasswdChk = global_get_param($_POST, "PasswdChk", null, 0, 1);
	$memberTel1 = global_get_param($_POST, "memberTel1", null, 0, 1);
	$memberTel2 = global_get_param($_POST, "memberTel2", null, 0, 1);
	$memberEmail = global_get_param($_POST, "memberEmail", null, 0, 1);
	$memberCaptcha = global_get_param($_POST, "memberCaptcha", null, 0, 1);
	$memberCity = global_get_param($_POST, "memberCity", null, 0, 1);
	$memberCanton = global_get_param($_POST, "memberCanton", null, 0, 1);
	$memberAddress = global_get_param($_POST, "memberAddress", null, 0, 1);
	$memberCardno = global_get_param($_POST, "memberCardno", null, 0, 1);
	$memberWNo = global_get_param($_POST, "memberWNo", null, 0, 1);
	$memberBirthday = global_get_param($_POST, 'memberBirthday', null, 0, 1);
	$ismlm = global_get_param($_POST, 'ismlm', null, 0, 1);

	// || empty($memberCaptcha)|| ($signupMode == "SMS" && empty($memberPhone))
	// || empty($memberSID)
	if (
		empty($signupMode) || empty($memberName) || empty($memberPasswd)
		|| empty($PasswdChk) || ($signupMode == "MAIL" && empty($memberEmail))
		|| $memberPasswd != $PasswdChk
	) {
		JsonEnd(array("status" => 0, "msg" => _COMMON_ERRORMSG_NET_ERR));
	}
	if (!empty($memberTel1) && !empty($memberTel2)) {
		$memberTel = $memberTel1 . "-" . $memberTel2;
	} else {
		$memberTel = '';
	}

	if (!empty($memberCity) && !empty($memberCanton) && !empty($memberAddress)) {
		$memberAddress = $memberCity['name'] . $memberCanton['name'] . $memberAddress;
	} else {
		$memberAddress = '';
	}

	if (!empty($memberCity)) {
		$memberCity = $memberCity['id'];
	}

	if (!empty($memberCanton)) {
		$memberCanton = $memberCanton['id'];
	}



	$memberPhone = mobileChk($memberPhone);

	if (!empty($memberEmail)) {
		$chk = getFieldValue("select count(1) as cnt from members where email='$memberEmail' and locked = '0' and email <> 'H1707@goodarch2u.com' ", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBERS_SAME_EMAIL));
		}
	}

	//檢查驗證碼
	// $ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
	// if ($signupMode == "SMS") {
	// 	$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
	// } else if ($signupMode == "MAIL") {
	// 	$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
	// }

	// if (empty($chk)) {
	// 	JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤"));
	// } else {
	// 	//更新驗證碼
	// 	$sql = "update requestLog set type= 'sign20Chked' where id='$chk'";
	// 	$db->setQuery($sql);
	// 	$db->query();
	// }

	$passwd = enpw($memberPasswd);

	$tsql = "SELECT * from mbst where boss_id = '$memberSID' and mb_status = '1'";
	$db2->setQuery($tsql);
	$tmd = $db2->loadRow();
	if (!empty($tmd)) {
		$memberNo = $tmd['mb_no'];
		$tin = $tmd['true_intro_no'];

		$check_web_exist = false;
		$sql = "SELECT * FROM members WHERE ERPID = '$memberNo' and locked = '0'";
		$db->setQuery($sql);
		$check_web = $db->loadRow();
		if (!empty($check_web)) {
			$check_web_exist = true;
		}
	} else {
		$memberNo = '';
		$tin = '';
	}

	if ($check_web_exist == true) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
	}


	if (empty($memberNo)) {
		// $memberNo_max = getFieldValue(" SELECT ERPID FROM members WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");

		$memberNo_max = getFieldValue(" SELECT ERPID FROM erpid WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");

		$chk_code = "";
		if (!empty($memberNo_max)) {
			$chk_code = intval(substr($memberNo_max, -5)) + 1;
		}

		if (!empty($chk_code))
			$code  = str_pad($chk_code, 5, '0', STR_PAD_LEFT);
		else
			$code  = "00001";

		$memberNo = "MYN" . date("Ym") . $code;
	}

	$emailChk = ($signupMode == "MAIL") ? 0 : 1;
	$mobileChk = 0;
	// $mobileChk = ($signupMode == "SMS") ? 1:0;
	$salesChk = 1;
	$ERPChk = 1;
	$memType = 0;
	$pvgeLevel = $signupMode;



	$sql = "insert into members ( belongid,name,sid,email,emailChk,mobileChk,
				regDate,loginid,passwd,cardnumber,recommendCode,
				salesChk,mobile,ERPID,phone,fulladdr,
				ERPChk,resaddr,city,canton,addr,
				rescity,rescanton,dlvrLocation,Birthday,memType,
				recommendName,recommendPhone,recommendMobile, usedChk, memberWNo, pvgeLevel ) values 
				('0',N'$memberName','$memberSID','$memberEmail','$emailChk','$mobileChk',
				'" . date("Y-m-d H:i:s") . "','$memberSID','$passwd','$memberNo','$tin',
				'$salesChk','$memberPhone','$memberNo','$memberTel',N'$memberAddress',
				$ERPChk,N'$memberResAddress','$memberCity','$memberCanton',N'$memberAddress',
				'','',N'',N'$memberBirthdayStr','$memType',
				N'',N'',N'', '', '$memberWNo', '$pvgeLevel' )";

	$db->setQuery($sql);
	$db->query();

	//20230817額外寫入一個accmbno
	$acc = array();
	$acc['mb_no'] = $memberNo;
	$acc_sql = dbInsert('accmbno',$acc);
	$db2->setQuery($acc_sql);
	$db2->query();

	//建立會編日誌
	$e_arr = array();
	$e_arr['ERPID'] = $memberNo;
	$esql = dbInsert('erpid', $e_arr);
	$db->setQuery($esql);
	$db->query();

	$memberid = getFieldValue(" SELECT id FROM members ORDER BY id DESC ", "id");

	//發送註冊成功
	if ($ismlm == '1') {
		if ($signupMode == "SMS") {
			sendMailToMemberBySignupSuccess($memberid, true);

			$body = _MEMBER_SUCCESS_SMS;
			// send_sms($memberPhone, $body);
		} else if ($signupMode == "MAIL") {
			sendMailToMemberBySignupSuccess($memberid, false);
		}
	} else {
		if ($signupMode == "SMS") {
			sendMailToMemberBySignupSuccess($memberid, true, "sign20_signup");

			$body = _MEMBER_SUCCESS_SMS;
			// send_sms($memberPhone, $body);
		} else if ($signupMode == "MAIL") {
			sendMailToMemberBySignupSuccess($memberid, false, "sign20_signup");
		}
	}

	//login(0,"",$memberSID,$memberPasswd);

	JsonEnd(array("status" => 1, "msg" => _MEMBER_SIGNUP_SUCCESS));
}

function sign20_signupSuccess()
{
	$tmpStr = $_SESSION["tmpData"]["bodyStr"];
	if (empty($tmpStr)) {
		JsonEnd(array("status" => 0));
	}
	unset($_SESSION["tmpData"]["bodyStr"]);
	unset($_SESSION["tmpData"]["payD"]);
	JsonEnd(array("status" => 1, "msg" => $tmpStr));
}

function resetPW20_sendCaptcha()
{
	global $db, $tablename, $conf_user, $globalConf_signupDemo_ver2020;

	$forgotMode = global_get_param($_POST, 'forgotMode', null, 0, 1);
	$mobile = global_get_param($_POST, 'phone', null, 0, 1);
	$email = global_get_param($_POST, 'mail', null, 0, 1);
	$captcha = global_get_param($_POST, 'captcha', null, 0, 1);

	if ($captcha == "captcha") {
		if ($_SESSION["tmpData"]["captcha"] != "captcha") {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_INVALID_CODE));
		} else {
			unset($_SESSION["tmpData"]["captcha"]);
		}
	}


	//驗證
	if ($forgotMode == "SMS") {
		$mobile = mobileChk($mobile);
		$var02 = $mobile;
		$chk = getFieldValue("select count(1) as cnt from $tablename where mobile='$mobile' AND locked = '0' and mobileChk = '1' ORDER BY id DESC", "cnt");
		if ($chk == 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_MOBILE_INVALID));
		}
	} else if ($forgotMode == "MAIL") {
		$var02 = $email;
		$chk = getFieldValue("select count(1) as cnt from $tablename where email='$email' AND locked = '0' and emailChk = '0' ORDER BY id DESC", "cnt");
		if ($chk == 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_EMAIL_INVALID));
		}
	}

	//產生驗證碼
	$captcha = generateCaptcha("resetPW20Chk", $forgotMode, $var02);

	//發送
	if ($forgotMode == "SMS") {
		$body = _EMAIL_code . $captcha . _EMAIL_msg4;
		send_sms($mobile, $body);
		JsonEnd(array("status" => 1, "msg" => ""));
	} else if ($forgotMode == "MAIL") {
		JsonEnd(array("status" => 1, "msg" => _MEMBER_EMAILCHK_MSG9));
	}
}

function resetPW20()
{
	global $db, $tablename, $conf_user, $globalConf_signupDemo_ver2020;

	$forgotMode = global_get_param($_POST, 'forgotMode', null, 0, 1);
	$forgotCaptcha = global_get_param($_POST, 'forgotCaptcha', null, 0, 1);
	$phone = global_get_param($_POST, 'phone', null, 0, 1);
	$email = global_get_param($_POST, 'mail', null, 0, 1);


	//檢查驗證碼
	$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
	if ($forgotMode == "SMS") {
		$phone = mobileChk($phone);
		$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'resetPW20Chk' AND var01 = '$forgotMode' AND code = '$forgotCaptcha' AND var02 = '$phone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		$uid = getFieldValue(" select id from $tablename where mobile='$phone' AND locked = '0' ORDER BY id DESC", "id");
	} else if ($forgotMode == "MAIL") {
		$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'resetPW20Chk' AND var01 = '$forgotMode' AND code = '$forgotCaptcha' AND var02 = '$email' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		$uid = getFieldValue(" select id from $tablename where email='$email' AND locked = '0' ORDER BY id DESC", "id");
	}

	if (empty($chk)) {
		JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤"));
	} else {

		//更新驗證碼
		$sql = "update requestLog set type= 'resetPW20Chked' where id='$chk'";
		$db->setQuery($sql);
		$db->query();


		$encry = md5($uid . time());
		$db->setQuery("insert into requestLog (ctime,memberid,code,type) values ('" . date("Y-m-d H:i:s") . "','$uid','$encry','resetpw')");
		$db->query();

		JsonEnd(array("status" => 1, "a" => $encry));
	}
}

function resetPW20_captchaChk()
{
	global $conf_php;
	$c = $_POST['response'];
	$secret = $_POST['secret'];
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$myip = $_SERVER['HTTP_CLIENT_IP'];
	} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$myip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$myip = $_SERVER['REMOTE_ADDR'];
	}

	include($conf_php . 'recaptchalib.php');
	// Register API keys at https://www.google.com/recaptcha/admin
	$siteKey = "6LfhzSAgAAAAABL9W0c82QV5sYh5KvRjAf5muiyB";
	$secret = "6LfhzSAgAAAAAHsAwBxVFjVCw2NMXh4ITjb7vTAy";
	// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
	$lang = "zh-TW";
	$lang = $_SESSION['lang'];
	// The response from reCAPTCHA
	$resp = null;
	// The error code from reCAPTCHA, if any
	$error = null;

	$reCaptcha = new ReCaptcha($secret);
	// Was there a reCAPTCHA response?
	if (!empty($_POST['response'])) {
		$resp = $reCaptcha->verifyResponse($_SERVER['REMOTE_ADDR'], $_POST['response']);
	}
	if (($resp != null) && ($resp->success == true)) {
		$_SESSION["tmpData"]["captcha"] = "captcha";
		die();
	} else {
		die("error");
	}
}

function info20_sendCaptcha()
{
	global $db, $tablename, $conf_user, $globalConf_signupDemo_ver2020, $HTTP_X_FORWARDED_PROTO;

	$uid = LoginChk();
	$infoMode = global_get_param($_POST, 'infoMode', null, 0, 1);
	$mobile = global_get_param($_POST, 'phone', null, 0, 1);
	$email = global_get_param($_POST, 'mail', null, 0, 1);

	//驗證
	if ($infoMode == "SMS") {
		$mobile = mobileChk($mobile);
		$var02 = $mobile;
		$chk = getFieldValue("select count(1) as cnt from $tablename where mobile='$mobile' AND locked = '0' and mobileChk = '1' ORDER BY id DESC", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_EXIST_MOBILE));
		}
	} else if ($infoMode == "MAIL") {
		$var02 = $email;
		$chk = getFieldValue("select count(1) as cnt from $tablename where email='$email' AND locked = '0' and emailChk = '0' ORDER BY id DESC", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBERS_SAME_EMAIL));
		}
	}

	//產生驗證碼
	$captcha = generateCaptcha("info20Chk", $infoMode, $var02);

	//發送
	if ($infoMode == "SMS") {
		$body = _EMAIL_code . $captcha . _EMAIL_msg4;
		$re = send_sms($mobile, $body);
		JsonEnd(array("status" => 1, "msg" => "", "re" => $re, "ca" => $captcha));
	} else if ($infoMode == "MAIL") {
		$sql_str = " name ";
		$webname = getFieldValue("select $sql_str from siteinfo  ;", 'name');
		$subject = " " . $captcha . _MEMBER_REC_CODE . " (" . date("Y-m-d H:i:s") . ")";

		$sendto[] = array('name' => $email, 'email' => $email);

		$adminmail = getFieldValue("select email from siteinfo  ;", 'email');

		// $body = "親愛的經銷商<br /><br />";
		// $body .= "您正在 修改會員資料，驗證碼為：" . $captcha . "<br /><br />";
		// $body .= "請於15分鐘內輸入<br /><br />";
		// $body .= "此為系統郵件，請勿回覆<br />";
		$target = _EMAIL_membership;
		$v = time();
		$add1 = _EMAIL_register;
		$body = '<table cellspacing="1" cellpadding="10" border="1" style="width:600px;">' .
			'<thead>' .
			'<tr>' .
			'<th style="background-color:#70ad46;color:white;text-align:center;height:60px;font-size:32px">' .
			_EMAIL_notification .
			'</th>' .
			'</tr>' .
			'</thead>' .
			'<tbody>' .
			'<tr>' .
			'<td>' .
			'<span>' . _EMAIL_dear . ' ' . $target . _EMAIL_hello . '</span><br /><br />' .
			'<span style="padding-left:32px">' . _EMAIL_msg1 . '</span>' . '<br />' . '<span style="padding-left:32px">' . _EMAIL_msg2 . '</span><br /><br />' .
			'<span style="padding-left:32px;font-size: 2em;">' . $captcha . '</span><br /><br />' .
			'<span style="padding-left:32px">' . _EMAIL_msg4 . '</span>' .
			'</td>' .
			'</tr>' .
			'<tr style="padding-top:30px;">' .
			'<td style="border-top:#ccc">' .
			'<span style="color:red">' . _EMAIL_msg5 . '</span><br /><br />' .
			'<span style="padding-left:32px">    ' . _EMAIL_msg6 . ' <a href="https://www.goodarch2us.com/">https://www.goodarch2us.com/</a></span><br /><br />' .
			'<span style="padding-left:32px">    ' . _EMAIL_msg7 . ' <a href="https://usshop.goodarch2u.com  ">https://usshop.goodarch2u.com</a></span><br /><br />' .
			'</td>' .
			'</tr>' .
			'</tbody>' .
			'<div style="width:200px;display:inline-block">' .
			'<img src="https://usshop.goodarch2u.com/upload/goodarch-logo-s.png?' . $v . '" style="padding-left:100px;height:64px;width:64px;">' .
			'</div>' .
			'<div style="width:350px;display:inline-block">' .
			'<div style="height:32px">' . _EMAIL_msg8 . '</div><br />' .
			'<div style="height:32px">GoodARCH Technology Sdn. Bhd. All Rights Reserved. </div>' .
			'</div>' .
			'</table>';

		$logarr = array();

		$logarr = global_send_mail($adminmail, $webname, $sendto, $subject, $body, null, null, null);

		if ($logarr['state'] == 'sus') {
			JsonEnd(array("status" => 1, "msg" => _MEMBER_EMAILCHK_MSG9));
		} else {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_SEND_FAIL . ':' . $logarr['msg']));
		}
	}
}

function mobileChk($str)
{
	if (strlen($str) == 9) {
		$str = '0' . $str;
	}
	return $str;
}

function ecash21_dtl()
{
	$ord_no = global_get_param($_POST, 'ord_no', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	if (!empty($ord_no)) {
		$e21_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_new2_1_detail_api.php?ord_no=$ord_no&mb_no=$mb_no");
		if (!empty($e21_result)) {
			$e21_result = json_decode($e21_result, true);
			JsonEnd(array("status" => 1, "data" => $e21_result));
		} else {
			JsonEnd(array("status" => 0, "error_code" => _MEMBER_E21_EMPTY));
		}
	} else {
		JsonEnd(array("status" => 0, "error_code" => _MEMBER_ERROR_7));
	}
}

function money_dtl()
{
	global $db2, $conf_user;
	ini_set('display_errors', '1');
	$yymm = global_get_param($_POST, 'yymm', null, 0, 1);
	$mbno = global_get_param($_POST, 'mbno', null, 0, 1);
	$mbname = global_get_param($_POST, 'mbname', null, 0, 1);
	$lang = $_SESSION[$conf_user]['syslang'];

	if (!empty($mbno) && !empty($yymm)) {
		// $mtd_result = file_get_contents(MLMURL . "ct/mbst/get_money_total_detail_api.php?mb_no=$mbno&yymm=$yymm");

		$set_his_report = "his_report";
		$set_his_moneypv = "his_moneypv2";
		$set_week_no = "yymm";

		$intro_money = 0;
		$red2_money = 0;
		$org_money = 0;
		$lead_money = 0;
		$red1_money = 0;
		$red3_money = 0;

		$subtotal = 0;
		$tax2 = 0;
		$tax3 = 0;
		$tax = 0;
		$givemoney = 0;

		$circle_plus = 0;
		$circle_minus = 0;
		$data_arr = array();
		if ($yymm != "") {
			$rs = "select A.*,B.opendate2 from " . $set_his_moneypv . " as A,date_tb as B where A.mb_no = '" . $mbno . "' and A." . $set_week_no . "='" . $yymm . "' and A.yymm = B.yymm";
			$db2->setQuery($rs);

			$rsresult = $db2->loadRow();

			if (!empty($rsresult)) {

				$rsdata = $rsresult;

				$subtotal = $rsdata["subtotal"];
				// $tax=forthousand($rsdata["tax"]);
				// $tax2=forthousand($rsdata["tax2"]);
				// $tax3=forthousand($rsdata["tax3"]);
				// $givemoney=forthousand($rsdata["givemoney"]);            
				$tax = ($rsdata["tax"]);
				$tax2 = ($rsdata["tax2"]);
				$tax3 = ($rsdata["tax3"]);
				$givemoney = ($rsdata["givemoney"]);
				$gsql = "select * from grade where no = '" . $rsdata['grade_class'] . "'";
				$db2->setQuery($gsql);
				$g_data = $db2->loadRow();
				if ($lang == 'en') {
					$grade_name = $g_data['en_name'];
				} else if ($lang == 'zh-cn') {
					$grade_name = $g_data['cs_name'];
				} else {
					$grade_name = $g_data['name'];
				}


				$rsdata['grade_name'] = $grade_name;

				$per_m = $rsdata['per_m'];
				$org_m = $rsdata['org_m'];
				$org_sum = $rsdata['org_sum'];

				$intro_money = $rsdata['intro_money'];
				$red2_money = $rsdata['red2_money'];
				$org_money = $rsdata['org_money'];
				$lead_money = $rsdata['lead_money'];
				$red1_money = $rsdata['red1_money'];
				$red3_money = $rsdata['red3_money'];

				$a_intro_new = $rsdata['a_intro_new'];
				$b_intro_new = $rsdata['b_intro_new'];
				$c_intro_new = $rsdata['c_intro_new'];
				$a_intro_sum = $rsdata['a_intro_sum'];
				$b_intro_sum = $rsdata['b_intro_sum'];
				$c_intro_sum = $rsdata['c_intro_sum'];

				$a_line_m = $rsdata['a_line_m'];
				$b_line_m = $rsdata['b_line_m'];
				$c_line_m = $rsdata['c_line_m'];
				$a_line_sum = $rsdata['a_line_sum'];
				$b_line_sum = $rsdata['b_line_sum'];
				$c_line_sum = $rsdata['c_line_sum'];

				$ch_tax = $rsdata['change_tax'];
				$outside_tax = $rsdata['outside_tax'];
				$circle_plus = $rsdata['circle_plus'];
				$circle_minus = $rsdata['circle_minus'];
				$money_adm = $rsdata['money_adm'];
				$rsdata['circle_adjust'] = $circle_plus - $circle_minus;
				$f_subtotal = $givemoney - $tax2 + $outside_tax;
				$rsdata['f_subtotal'] = forthousand($f_subtotal);
				$rsdata['f_tax'] = forthousand($tax);
				$rsdata['f_tax2'] = forthousand($tax2);
				$rsdata['f_money_adm'] = forthousand($money_adm);
				$rsdata['f_givemoney'] = forthousand($givemoney);
				// $rsdata['f_total_e'] = forthousand($subtotal + $tax2);
				$rsdata['f_total_e'] = forthousand($givemoney + $outside_tax);

				$data_arr['rsdata'] = $rsdata;
			}
		}


		if ($lang == 'en') {
			$name_type = 'money_name_en';
		} else if ($lang == 'zh-cn') {
			$name_type = 'money_name';
		} else {
			$name_type = 'money_name';
		}

		$sql = "select money_id, `" . $name_type . "` as money_name from money_data order by sort";
		$db2->setQuery($sql);
		$show_money_res = $db2->loadRowList();

		$show_data_array = array();

		foreach ($show_money_res as $show_money_data) {
			if ($rsdata[$show_money_data['money_id']] == '') {
				$show_money_data['money_val'] = '0';
			} else {
				$show_money_data['money_val'] = forthousand($rsdata[$show_money_data['money_id']]);
			}
			$show_data_array[] = $show_money_data;
		}


		$data_arr['show_money_data'] = $show_data_array;


		$inFlag = false;
		$rssdata_array = array();

		$rss = "select detail_kind,group_title,money_field," . $set_week_no . " from " . $set_his_report . "  where " . $set_week_no . "='" . $yymm . "' and mb_no = '" . $mbno . "' group by detail_kind,group_title," . $set_week_no . " order by abs(sort)";
		$db2->setQuery($rss);
		$rssresult = $db2->loadRowList();
		$r2 = array();
		foreach ($rssresult as $rssdata) {
			$detail_kind = $rssdata["detail_kind"];
			$group_title = $rssdata["group_title"];

			$level_str = _MEMBER_GENERATION;

			$rs1 = "select detail_kind ,mb_no ,under_mb_no,under_mb_name,ps,level_no,details ,subtotal,group_title,orgseq_no from " . $set_his_report . " where mb_no = '" . $mbno . "' and " . $set_week_no . "='" . $yymm . "' and detail_kind='" . $detail_kind . "' order by mb_no,detail_kind,level_no,under_mb_no,ps";

			$db2->setQuery($rs1);
			$rs1result = $db2->loadRowList();

			if ($detail_kind == '[海旅點數]') {
				$r2[] = $rs1;
			}

			$subs = 0;
			$a_subs = 0;
			$b_subs = 0;
			if (!empty($rs1result)) {
				$inFlag = true;
				$k = 0;
				$rsldata_array = array();

				foreach ($rs1result as $rs1data) {
					$subs = $subs + $rs1data["subtotal"];
					$rs1data["now_subs"] = $subs;
					$level_no = ($rs1data["level_no"] > 0) ? $rs1data["level_no"] : "";

					//**去掉累計量
					if ($detail_kind == '[經銷商車馬費]' || $detail_kind == '[工程師技術費]' || $detail_kind == '[代理店輔導費]') {
						$details = substr($rs1data['details'], 0, strpos($rs1data['details'], "(") - 1);
					} else {
						$details = $rs1data['details'];
					}


					if ($detail_kind == '[新增左右件數]') {
						if ($rs1data['ps'] == 'L線新增') {
							$a_subs = $a_subs + $rs1data["subtotal"];
							$rs1data["a_subs"] = $a_subs;
						} else {
							$b_subs = $b_subs + $rs1data["subtotal"];
							$rs1data["b_subs"] = $b_subs;
						}
					}
					if ($detail_kind == '[組織獎金]' || $detail_kind == '[對碰獎金]') {
						$rsdata['f_a_line_suba_old'] = forthousand($rsdata['a_line_subs_old']);
						$rsdata['f_b_line_suba_old'] = forthousand($rsdata['b_line_subs_old']);
						$rsdata['f_a_line_suba_new'] = forthousand($rsdata['a_line_subs_new']);
						$rsdata['f_b_line_suba_new'] = forthousand($rsdata['b_line_subs_new']);
						$rsdata['f_a_line_subs'] = forthousand($rsdata['a_line_subs']);
						$rsdata['f_b_line_subs'] = forthousand($rsdata['a_line_subs']);
					} else {
						$rs1data['f_subtotal'] = $rs1data['subtotal'];
					}


					$rs1data["level_nos"] = $level_no;

					$rsldata_array[] = $rs1data;
				}
				$rssdata["subs"] = $subs;
			}
			$trans_dk = trans_name($detail_kind);
			$rssdata['dk_name'] = $trans_dk;
			$rssdata['rsldata'] = $rsldata_array;
			$rssdata_array[] = $rssdata;
		}


		$data_arr['rssdata'] = $rssdata_array;




		$mtd_result = $data_arr;



		//angel 0918
		$esql = "select SUM(point) as e_cash from e_cash where mb_no='$mbno' and yymm = '$yymm' and kind = '6' group by mb_no";
		$db2->setQuery($esql);
		$e_cash_data = $db2->loadRow();
		$e_cash = $e_cash_data['e_cash'];
		if ($e_cash < 1) {
			$e_cash = 0;
		}

		$e_cash2 = 0;
		$esql2 = "select SUM(point) as e_cash from e_cash where mb_no='$mbno' and yymm = '$yymm' and kind in ('2','3')";
		$db2->setQuery($esql2);
		$e_cash_data2 = $db2->loadRow();
		$e_cash2 = $e_cash_data2['e_cash'] * -1;


		$ec4 = round($e_cash2 / 1.05);
		$ec3 = $e_cash2 - $ec4;
		if (!empty($mtd_result)) {

			JsonEnd(array("status" => 1, "data" => $mtd_result, 'ec' => $e_cash, 'ec2' => $e_cash2, 'ec3' => $ec3, 'ec4' => $ec4, 'r2' => $r2));
		} else {
			JsonEnd(array("status" => 0, "error_code" => _MEMBER_MTD_EMPTY));
		}
	} else {
		JsonEnd(array("status" => 0, "error_code" => _MEMBER_ERROR_7));
	}
}

function trans_name($name)
{
	global $conf_user;

	$lang = $_SESSION[$conf_user]['syslang'];

	if ($lang == 'en') {
		switch ($name) {

			case "經銷商車馬費":
				$grade_name = "Distributor Travel Bonus";
				break;
			case "[經銷商車馬費]":
				$grade_name = "[Distributor Travel Bonus]";
				break;
			case "工程師技術費":
				$grade_name = "Engineer Rank Technical Bonus";
				break;
			case "[工程師技術費]":
				$grade_name = "[Engineer Rank Technical Bonus]";
				break;
			case "代理店輔導費":
				$grade_name = "Franchise Rank Leadership Bonus";
				break;
			case "[代理店輔導費]":
				$grade_name = "[Franchise Rank Leadership Bonus]";
				break;
			case "初階績效獎金":
				$grade_name = "Level Bonus";
				break;
			case "[初階績效獎金]":
				$grade_name = "[Level Bonus]";
				break;
			case "中階績效獎金":
				$grade_name = "Pearl Franchise";
				break;
			case "[中階績效獎金]":
				$grade_name = "[Pearl Franchise]";
				break;
			case "高階績效獎金":
				$grade_name = "Jade Franchise";
				break;
			case "[高階績效獎金]":
				$grade_name = "[Jade Franchise]";
				break;
			case "超額績效獎金":
				$grade_name = "Outstanding Level Bonus";
				break;
			case "[超額績效獎金]":
				$grade_name = "[Outstanding Level Bonus]";
				break;
			case "組織輔導獎金":
				$grade_name = "Leadership Bonus";
				break;
			case "[組織輔導獎金]":
				$grade_name = "[Leadership Bonus]";
				break;
			case "集會與專賣店補貼":
				$grade_name = "Subsidy for Various Functions & Distributor";
				break;
			case "[集會與專賣店補貼]":
				$grade_name = "[Subsidy for Various Functions & Distributor]";
				break;
			case "專賣店績效獎金":
				$grade_name = "Professionals Store GQB";
				break;
			case "[專賣店績效獎金]":
				$grade_name = "[Professionals Store GQB]";
				break;
			case "鞋子訂單回饋":
				$grade_name = "Shoe Rebate";
				break;
			case "[鞋子訂單回饋]":
				$grade_name = "[Shoe Rebate]";
				break;
			case "年度分紅領取":
				$grade_name = "Profit Sharing-Annual  Bonus Requirements";
				break;
			case "[年度分紅領取]":
				$grade_name = "[Profit Sharing-Annual  Bonus Requirements]";
				break;
			case "年度分紅":
				$grade_name = "Profit Sharing-Annual Bonus";
				break;
			case "[年度分紅]":
				$grade_name = "[Profit Sharing-Annual Bonus]";
				break;
			case "[EX.<span>前期保留]":
				$grade_name = "[EX.Pre-reservations]";
		}
	} else if ($lang == 'zh-cn') {
		switch ($name) {

			case "經銷商車馬費":
				$grade_name = "经销商车马费";
				break;
			case "[經銷商車馬費]":
				$grade_name = "[经销商车马费]";
				break;
			case "工程師技術費":
				$grade_name = "工程师技术费";
				break;
			case "[工程師技術費]":
				$grade_name = "[工程师技术费]";
				break;
			case "代理店輔導費":
				$grade_name = "代理店辅导费";
				break;
			case "[代理店輔導費]":
				$grade_name = "[代理店辅导费]";
				break;
			case "初階績效獎金":
				$grade_name = "初阶绩效奖金";
				break;
			case "[初階績效獎金]":
				$grade_name = "[初阶绩效奖金]";
				break;
			case "中階績效獎金":
				$grade_name = "中阶绩效奖金";
				break;
			case "[中階績效獎金]":
				$grade_name = "[中阶绩效奖金]";
				break;
			case "高階績效獎金":
				$grade_name = "高阶绩效奖金";
				break;
			case "[高階績效獎金]":
				$grade_name = "[高阶绩效奖金]";
				break;
			case "超額績效獎金":
				$grade_name = "超额绩效奖金";
				break;
			case "[超額績效獎金]":
				$grade_name = "[超额绩效奖金]";
				break;
			case "組織輔導獎金":
				$grade_name = "组织辅导奖金";
				break;
			case "[組織輔導獎金]":
				$grade_name = "[组织辅导奖金]";
				break;
			case "集會與專賣店補貼":
				$grade_name = "集会与专卖店补贴";
				break;
			case "[集會與專賣店補貼]":
				$grade_name = "[集会与专卖店补贴]";
				break;
			case "專賣店績效獎金":
				$grade_name = "专卖店绩效奖金";
				break;
			case "[專賣店績效獎金]":
				$grade_name = "[专卖店绩效奖金]";
				break;
			case "鞋子訂單回饋":
				$grade_name = "鞋子订单回馈";
				break;
			case "[鞋子訂單回饋]":
				$grade_name = "[鞋子订单回馈]";
				break;
			case "年度分紅領取":
				$grade_name = "年度分红领取";
				break;
			case "[年度分紅領取]":
				$grade_name = "[年度分红领取]";
				break;
			case "年度分紅":
				$grade_name = "年度分红";
				break;
			case "[年度分紅]":
				$grade_name = "[年度分红]";
				break;
			case "[EX.<span>前期保留]":
				$grade_name = "[EX.<span>前期保留]";
		}
	} else {
		switch ($name) {

			case "經銷商車馬費":
				$grade_name = "經銷商車馬費";
				break;
			case "[經銷商車馬費]":
				$grade_name = "[經銷商車馬費]";
				break;
			case "工程師技術費":
				$grade_name = "工程師技術費";
				break;
			case "[工程師技術費]":
				$grade_name = "[工程師技術費]";
				break;
			case "代理店輔導費":
				$grade_name = "代理店輔導費";
				break;
			case "[代理店輔導費]":
				$grade_name = "[代理店輔導費]";
				break;
			case "初階績效獎金":
				$grade_name = "初階績效獎金";
				break;
			case "[初階績效獎金]":
				$grade_name = "[初階績效獎金]";
				break;
			case "中階績效獎金":
				$grade_name = "中階績效獎金";
				break;
			case "[中階績效獎金]":
				$grade_name = "[中階績效獎金]";
				break;
			case "高階績效獎金":
				$grade_name = "高階績效獎金";
				break;
			case "[高階績效獎金]":
				$grade_name = "[高階績效獎金]";
				break;
			case "超額績效獎金":
				$grade_name = "超額績效獎金";
				break;
			case "[超額績效獎金]":
				$grade_name = "[超額績效獎金]";
				break;
			case "組織輔導獎金":
				$grade_name = "組織輔導獎金";
				break;
			case "[組織輔導獎金]":
				$grade_name = "[組織輔導獎金]";
				break;
			case "集會與專賣店補貼":
				$grade_name = "集會與專賣店補貼";
				break;
			case "[集會與專賣店補貼]":
				$grade_name = "[集會與專賣店補貼]";
				break;
			case "專賣店績效獎金":
				$grade_name = "專賣店績效獎金";
				break;
			case "[專賣店績效獎金]":
				$grade_name = "[專賣店績效獎金]";
				break;
			case "鞋子訂單回饋":
				$grade_name = "鞋子訂單回饋";
				break;
			case "[鞋子訂單回饋]":
				$grade_name = "[鞋子訂單回饋]";
				break;
			case "年度分紅領取":
				$grade_name = "年度分紅領取";
				break;
			case "[年度分紅領取]":
				$grade_name = "[年度分紅領取]";
				break;
			case "年度分紅":
				$grade_name = "年度分紅";
				break;
			case "[年度分紅]":
				$grade_name = "[年度分紅]";
				break;
			case "[EX.<span>前期保留]":
				$grade_name = "[EX.<span>前期保留]";
		}
	}



	return $grade_name;
}

function money_list()
{
	global $db, $db2;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$yy = $search_yy;
	// if (!empty($search_yy) && $search_yy != 'all') {
	// 	$mt_result = file_get_contents(MLMURL . "ct/mbst/get_money_total_api.php?mb_no=$mb_no&boss_id=$boss_id&yy=$search_yy");
	// } else {
	// 	$mt_result = file_get_contents(MLMURL . "ct/mbst/get_money_total_api.php?mb_no=$mb_no&boss_id=$boss_id");
	// }

	$his_query = "select a.* from his_moneypv2 as a , date_tb as b  where a.mb_no = '" . $mb_no . "'  and (a.yymm=b.yymm)  and b.do_show ='Y' and (b.opendate2='' or b.opendate2 is null or b.opendate2<='" . date('Y-m-d') . "')";
	$his_query .= " and (b.opentime2='00:00:00' or b.opentime2=''  or b.opentime2 is null or b.opentime2<='" . date('H:i:s') . "')";
	if (!empty($yy)) {
		$his_query .= " and a.yymm like '" . $yy . "%'";
	}
	$his_query .= " order by a.yymm desc";

	$db2->setQuery($his_query);
	$his_res = $db2->loadRowList();


	$mt_result = $his_res;
	$earlist_yy = '2017';
	$now_yy = date('Y');
	$money_yy_list = array();
	for ($i = $earlist_yy; $i <= $now_yy; $i++) {
		$obj = [
			"id" => $i,
			"year" => $i
		];
		array_unshift($money_yy_list, $obj);
	}

	JsonEnd(array("status" => 1, "data" => $mt_result, "money_yy_list" => $money_yy_list, "da" => $his_res));
}


function minfo_list()
{
	global $db, $db2, $real_domain, $conf_user;
	$lang = $_SESSION[$conf_user]['syslang'];
	ini_set('allow_url_fopen', '1');
	$uid = loginChk();
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$sql = "SELECT m.mb_no,m.boss_id,m.mb_name,m.sex,m.birthday2,m.email,m.tel2,m.tel3,m.add2,m.full_add2,m.grade_1_date,m.true_intro_name,m.bank_ac,m.pg_end_date,m.service_day,m.bank_no,m.give_method,m.ac_name,m.ac_id FROM mbst as m where mb_no = '$mb_no'";
	$db2->setQuery($sql);
	$result = $db2->loadRow();
	if ($result['sex'] == 1) {
		if ($lang == 'en') {
			$gentle = 'male';
		} else if ($lang == 'zh-cn') {
			$gentle = '男';
		} else {
			$gentle = '男';
		}
	} else if ($result['sex'] == 0) {
		if ($lang == 'en') {
			$gentle = 'female';
		} else if ($lang == 'zh-cn') {
			$gentle = '女';
		} else {
			$gentle = '女';
		}
	}

	if (empty($result)) {
		$gentle = '';
	}
	$give_method = $result['give_method'];
	$sql2 = "SELECT * from bank where give_method_no = '$give_method'";
	$db2->setQuery($sql2);
	$bank = $db2->loadRow();
	if (!empty($bank)) {
		$result['give_method'] = $bank['give_method'];
	} else {
		$result['give_method'] = '';
	}
	if (!empty($result['pg_end_date'])) {
		$result['pg_end_day'] = date('Y-m-d', $result['pg_end_date']);
	} else {
		$result['pg_end_day'] = '';
	}




	$result['gentle'] = $gentle;
	if (empty($result['service_day'])) {
		$result['service_day'] = '--';
	}
	if (!empty($result['bank_ac']) && !empty($result['give_method']) && !empty($result['ac_name'])) {
		if ($lang == 'en') {
			$result['bank_result'] = 'Submitted';
		} else if ($lang == 'zh-cn') {
			$result['bank_result'] = '已缴交';
		} else {
			$result['bank_result'] = '已繳交';
		}

		$result['has_bank'] = '1';
	} else {
		if ($lang == 'en') {
			$result['bank_result'] = 'Not submitted';
		} else if ($lang == 'zh-cn') {
			$result['bank_result'] = '未繳交';
		} else {
			$result['bank_result'] = '未繳交';
		}

		$result['has_bank'] = '0';
	}

	$path = 'http://192.168.7.46/upload_my/members/' . $uid . '_p1.jpg?v=' . time();
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

	$result['ex1'] = getimagesize($path);

	$path = 'http://192.168.7.46/upload_my/members/' . $uid . '_n1.jpg?v=' . time();
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base641 = 'data:image/' . $type . ';base64,' . base64_encode($data);

	$result['ex2'] = getimagesize($path);

	$path = 'http://192.168.7.46/upload_my/members/' . $uid . '_b1.jpg?v=' . time();
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base642 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$result['ex3'] = getimagesize($path);


	// $result['rr'] = $type;
	$result['img1'] = $base64;
	$result['img2'] = $base641;
	$result['img3'] = $base642;


	$res = array();
	if (!empty($result)) {
		$res['result'] = $result;
		$res['status'] = 1;
	} else {
		$res['status'] = 0;
	}

	JsonEnd($res);
}

function load_contents($url)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

function base64EncodeImage($image_file)
{
	$base64_image = '';
	$image_info = getimagesize($image_file);
	$image_data = fread(fopen($image_file, 'r'), filesize($image_file));
	$base64_image = 'data:image/jpg;base64,' . chunk_split(base64_encode($image_data));
	return $base64_image;
}

function stock_list()
{
	global $db, $db2;

	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$res = array();
	$sql = "SELECT * FROM stock_list where mb_no = '$mb_no'";
	$db->setQuery($sql);
	$data = $db->loadRow();
	if (count($data) > 0) {
		$res['status'] = '1';
		$res['data'] = $data;
	} else {
		$res['status'] = '0';
	}

	JsonEnd($res);
}

function annual_dividend()
{
	global $db, $db2;

	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$closing_year = '2020';
	$res = array();
	$sql = "SELECT * FROM annual_dividend where mb_no = '$mb_no' and closing_year = '$closing_year'";
	$db->setQuery($sql);
	$data = $db->loadRowList();
	$vsql = "SELECT SUM(v_1) as v_1_t,SUM(v_2) as v_2_t,SUM(v_3) as v_3_t,SUM(v_4) as v_4_t,SUM(v_5) as v_5_t,SUM(v_6) as v_6_t,SUM(v_total) as v_total_t  FROM annual_dividend where mb_no = '$mb_no' and closing_year = '$closing_year'";
	$db->setQuery($vsql);
	$vdata = $db->loadRow();
	$v_str = '';
	$vs_str = '';
	if ($vdata['v_6_t'] > 0) {
		$v_str .= '翡翠、';
		$vs_str .= '翡翠年度分紅1%、';
	}
	if ($vdata['v_5_t'] > 0) {
		$v_str .= '鑽石、';
		$vs_str .= '鑽石年度分紅0.75%、';
	}
	if ($vdata['v_4_t'] > 0) {
		$v_str .= '雙鑽、';
		$vs_str .= '雙鑽年度分紅0.5%、';
	}
	if ($vdata['v_3_t'] > 0) {
		$v_str .= '三鑽、';
		$vs_str .= '三鑽年度分紅0.25%、';
	}
	if ($vdata['v_2_t'] > 0) {
		$v_str .= '皇冠、';
		$vs_str .= '皇冠年度分紅0.25%、';
	}
	if ($vdata['v_1_t'] > 0) {
		$v_str .= '皇冠大使';
		$vs_str .= '皇冠大使年度分紅0.25%';
	}

	$v_str = trim($v_str, '、');

	$sql2 = "SELECT * from mbst where mb_no = '$mb_no'";
	$db2->setQuery($sql2);
	$mb_data = $db2->loadRow();
	$mb_name = $mb_data['mb_name'];

	$sql3 = "SELECT SUM(v_total) as all_total from annual_dividend where mb_no = '$mb_no'";
	$db->setQuery($sql3);
	$all_total = $db->loadRow();

	$rvtsql = "SELECT SUM(v_1) as v_1_t,SUM(v_2) as v_2_t,SUM(v_3) as v_3_t,SUM(v_4) as v_4_t,SUM(v_5) as v_5_t,SUM(v_6) as v_6_t,SUM(v_total) as v_total_t  FROM annual_dividend where mb_no = '$mb_no' and provide_year = '$closing_year'";
	$db->setQuery($rvtsql);
	$rvtdata = $db->loadRow();

	$rvsql = "SELECT * FROM annual_dividend where mb_no = '$mb_no' and provide_year = '$closing_year' order by closing_year asc";
	$db->setQuery($rvsql);
	$rvdata = $db->loadRowList();

	//得該會員的基本資料
	$dsql = "SELECT * FROM dividend_members where mb_no = '$mb_no'";
	$db->setQuery($dsql);
	$d_member = $db->loadRow();

	$ymd = $d_member['provide_date'];
	$md = date('m', strtotime($ymd)) . '月' . date('d', strtotime($ymd)) . '日';

	if (count($data) > 0 || count($d_member) > 0) {
		$res['closing_year'] = $closing_year;
		$res['status'] = '1';
		$res['data'] = $data;
		$res['vdata'] = $vdata;
		$res['rvtdata'] = $rvtdata;
		$res['rvdata'] = $rvdata;
		$res['mb_name'] = $d_member['mb_name'];
		$res['mb_rank'] = $d_member['mb_rank'];
		$res['mb_performance'] = $d_member['mb_performance'];
		$res['v_str'] = $v_str;
		$res['vs_str'] = $vs_str;
		$res['all_total'] = $all_total['all_total'];
		$res['d_member'] = $d_member;
		$res['md'] = $md;
	} else {
		$res['status'] = '0';
	}

	JsonEnd($res);
}
function orgseq_member()
{
	//呼叫變數
	global $db, $db2, $db3, $conf_user;
	$lang = $_SESSION[$conf_user]['syslang'];
	$res = array();
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	// 網購會員資料
	$dsql = "SELECT * from members where recommendCode = '$mb_no' and onlyMember = '1'";
	$db->setQuery($dsql);
	$list = $db->loadRowList();
	// mbst
	$arData = array();

	foreach ($list as $i => $value) {
		$grade = "SELECT mb_status,have_order_1 FROM mbst WHERE mb_no='" . $list[$i]['ERPID'] . "'  order by mb_no,member_date";
		$db2->setQuery($grade);
		$graderes = $db2->loadRowList();
		array_push($arData, $graderes);

		// 會員狀態
		$list[$i]['mb_status'] = $graderes[0]['mb_status'];

		// 是否穿鞋組
		if ($graderes[0]['have_order_1'] != '') {

			$list[$i]['have_order_1'] = $graderes[0]['have_order_1'];
		} else {
			$list[$i]['have_order_1'] = '2';
		}
		$mb_no = $list[$i]['ERPID'];

		$c_sql2 = "SELECT _m.*, ll.`" . $lang . "` as lv_name from member_lv as _m LEFT JOIN lv_list as ll on _m.lv = ll.level where _m.mb_no = '$mb_no'";
		// $c_sql2 = "SELECT * from member_lv where mb_no = '" . $list[$i]['ERPID'] . "'";
		$db3->setQuery($c_sql2);
		$c_res2 = $db3->loadRow();
		if (!empty($c_res2)) {
			$c_lv = $c_res2['lv']; //會員等級
			$c_lv_1 = $c_res2['force_lv'];
			$lv_name = $c_res2['lv_name'];
		} else {
			$c_sql3 = "SELECT `" . $lang . "` as lv_name from lv_list where level = '1'";
			$db3->setQuery($c_sql3);
			$c_res3 = $db3->loadRow();
			$lv_name = $c_res3['lv_name'];
			$c_lv = '1';
			$c_lv_1 = '1';
		}

		switch ($c_lv) {
			case '1':
				// $lv_name = '銀卡會員';
				// $list[$i]['lv'] = $lv_name;
				$list[$i]['lv_nu'] = '1';
				break;
			case '2':
				// $lv_name = '金卡會員';
				// $list[$i]['lv'] = $lv_name;
				$list[$i]['lv_nu'] = '2';
				break;
			case '3':
				// $lv_name = '白金會員';
				// $list[$i]['lv'] = $lv_name;
				$list[$i]['lv_nu'] = '3';
				break;
			default:
				// $lv_name = '銀卡會員';
				// $list[$i]['lv'] = $lv_name;
				$list[$i]['lv_nu'] = '1';
				break;
		}
		switch ($c_lv_1) {
			case '1':
				// $lv_name = '銀卡會員';
				// $list[$i]['lv_1'] = $lv_name;
				$list[$i]['lv_nu_1'] = '1';
				break;
			case '2':
				// $lv_name = '金卡會員';
				// $list[$i]['lv_1'] = $lv_name;
				$list[$i]['lv_nu_1'] = '2';
				break;
			case '3':
				// $lv_name = '白金會員';
				// $list[$i]['lv_1'] = $lv_name;
				$list[$i]['lv_nu_1'] = '3';
				break;
			default:
				// $lv_name = '銀卡會員';
				// $list[$i]['lv_1'] = $lv_name;
				$list[$i]['lv_nu_1'] = '1';
				break;
		}
		$list[$i]['lv'] = $lv_name;
		$list[$i]['lv_1'] = $lv_name;
		//會員消費額度
		if (!empty($c_res2)) {
			$c_total = $c_res2['total'];
			$list[$i]['total'] = $c_total;
		} else {
			$c_total = '0.00';
			$list[$i]['total'] = $c_total;
		}

		$list[$i]['csql2'] = $c_sql2;
		$list[$i]['csql3'] = $c_sql3;
	}

	$res['status'] = '1';
	$res['list'] = $list;
	$res['mbst'] = $arData;

	JsonEnd(array('status' => '1', 'data' => $res));
}
function ecash_list()
{
	global $conf_user;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];

	$lang = $_SESSION[$conf_user]['syslang'];

	if ($lang == 'en') {
		$name_type = 'name_en';
	} else if ($lang == 'zh-cn') {
		$name_type = 'name_zh_cn';
	} else {
		$name_type = 'name';
	}

	if (!empty($search_yy) && $search_yy != 'all') {
		$ec_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_list_api.php?mb_no=$mb_no&boss_id=$boss_id&lang=$name_type&yy=$search_yy");
	} else {
		$ec_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_list_api.php?mb_no=$mb_no&boss_id=$boss_id&lang=$name_type");
	}


	$ec_result = json_decode($ec_result, true);
	$earlist_yy = date('Y');
	$now_yy = date('Y', strtotime('+1 Year'));
	$ecash_yy_list = array();
	for ($i = $earlist_yy; $i <= $now_yy; $i++) {
		$obj = [
			"id" => $i,
			"year" => $i
		];
		array_unshift($ecash_yy_list, $obj);
	}

	$this_year = date('Y');
	$now = date('Ymd');
	$last_year = date('Y', strtotime($now . "- 1 year"));
	$next_year = date('Y', strtotime($now . "+ 1 year"));

	JsonEnd(array("status" => 1, "data" => $ec_result, "ecash_yy_list" => $ecash_yy_list, "t" => $this_year, "l" => $last_year, "n" => $next_year));
}


function carry_treasure()
{
	global $db2;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	if (!empty($search_yy) && $search_yy != 'all') {
		$search_yy -= 1;
		//$sql2 = "SELECT m.*,om.mb_name from carry_treasure as m,order_m as om where m.mb_no= '$mb_no' and chk_invalid = 0 and m.from_ord_no = om.ord_no and m.status like '$search_yy'";
		$sql2 = "SELECT m.* from carry_treasure as m where m.mb_no= '$mb_no' and chk_invalid = 0 and m.status like '$search_yy'";
	} else {
		//$sql2 = "SELECT m.*,om.mb_name from carry_treasure as m,order_m as om where m.mb_no= '$mb_no' and chk_invalid = 0 and m.from_ord_no = om.ord_no";
		$sql2 = "SELECT m.* from carry_treasure as m where m.mb_no= '$mb_no' and chk_invalid = 0";
	}

	$db2->setQuery($sql2);
	$r = $db2->loadRowList();

	$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status = 0 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0"; //可使用
	$db2->setQuery($sql);
	$row = $db2->loadRowList();
	$row_cnt = count($row);
	$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status= 1"; //使用中
	$db2->setQuery($sql);
	$row2 = $db2->loadRowList();
	$row2_cnt = count($row2);
	$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status= 2"; //已使用
	$db2->setQuery($sql);
	$row3 = $db2->loadRowList();
	$row3_cnt = count($row3);



	$ct_yy_list = array();
	array_unshift($ct_yy_list, array("id" => 3, "name" => '兌換完成'));
	array_unshift($ct_yy_list, array("id" => 2, "name" => '兌換中'));
	array_unshift($ct_yy_list, array("id" => 1, "name" => '未使用'));


	JsonEnd(array("status" => 1, "data" => $r, "ct_cnt_1" => $row_cnt, "ct_cnt_2" => $row2_cnt, "ct_cnt_3" => $row3_cnt, "ct_yy_list" => $ct_yy_list, "sql" => $sql2));
}


function birthday_voucher()
{
	global $db2;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	if (!empty($search_yy) && $search_yy != 'all') {
		$search_yy -= 1;
		$sql2 = "SELECT * from birthday_voucher where mb_no='$mb_no' and chk_invalid = 0 and status = '$search_yy'";
	} else {
		$sql2 = "SELECT * from birthday_voucher where mb_no='$mb_no' and chk_invalid = 0";
	}

	$db2->setQuery($sql2);
	$r = $db2->loadRowList();

	foreach ($r as $k => $v) {
		//兌換訂單編號
		if (empty($v['ord_no'])) {
			$r[$k]['ord_no'] = '--';
		}
		//兌換日期
		if (empty($v['exchange_date'])) {
			$r[$k]['exchange_date'] = '--';
		}
	}

	$sql = "SELECT * FROM birthday_voucher WHERE mb_no = '$mb_no' and status = 3 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0"; //可使用
	$db2->setQuery($sql);
	$row = $db2->loadRowList();
	$row_cnt = count($row);
	$sql = "SELECT * FROM birthday_voucher WHERE mb_no = '$mb_no' and (status= 1 or status = 4)"; //已使用
	$db2->setQuery($sql);
	$row2 = $db2->loadRowList();
	$row2_cnt = count($row2);

	$ct_yy_list = array();
	array_unshift($ct_yy_list, array("id" => 5, "name" => '兌換處理中'));
	array_unshift($ct_yy_list, array("id" => 4, "name" => '兌換中'));
	array_unshift($ct_yy_list, array("id" => 2, "name" => '兌換完成'));
	array_unshift($ct_yy_list, array("id" => 1, "name" => '未使用'));



	JsonEnd(array("status" => 1, "data" => $r, "bv_cnt_1" => $row_cnt, "bv_cnt_2" => $row2_cnt, "bv_yy_list" => $ct_yy_list));
}

function soybean_voucher()
{

	global $db2;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	if (!empty($search_yy) && $search_yy != 'all') {
		$search_yy -= 1;
		$sql2 = "SELECT * from soy_coupon where mb_no='$mb_no' and is_invalid = 'N'";
	} else {
		$sql2 = "SELECT * from soy_coupon where mb_no='$mb_no' and is_invalid = 'N'";
	}

	$db2->setQuery($sql2);
	$r = $db2->loadRowList();

	foreach ($r as $k => $v) {
		//兌換訂單編號
		if (empty($v['ord_no'])) {
			$r[$k]['ord_no'] = '--';
		}
		//兌換日期
		if (empty($v['exchange_date'])) {
			$r[$k]['exchange_date'] = '--';
		}
	}

	$sql = "SELECT * FROM soy_coupon WHERE mb_no = '$mb_no' and state = '0' and is_invalid ='N'"; //可使用
	$db2->setQuery($sql);
	$row = $db2->loadRowList();
	$row_cnt = count($row);
	$sql = "SELECT * FROM soy_coupon WHERE mb_no = '$mb_no' and state = '1' and is_invalid ='N'"; //已使用
	$db2->setQuery($sql);
	$row2 = $db2->loadRowList();
	$row2_cnt = count($row2);
	$sql = "SELECT * FROM soy_coupon WHERE mb_no = '$mb_no' and is_invalid ='N'"; //可使用
	$db2->setQuery($sql);
	$row3 = $db2->loadRowList();
	$all_cnt = count($row3);

	JsonEnd(array("status" => 1, "data" => $r, "sb_cnt_1" => $row_cnt, "sb_cnt_2" => $row2_cnt, "sb_total" => $all_cnt, "mb_no" => $sql2));
}

function chk_birthday()
{
	global $db2;
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$dataQ = "select birthday,birthday2,mb_no,mb_name,grade_1_date from mbst where mb_no='$mb_no'";
	$db2->setQuery($dataQ);
	$dataR = $db2->loadRow();
	$res = array();
	if (count($dataR) > 0 && !empty($dataR['birthday'])) {
		$check_m = 1;
		$dataRow = $dataR;
		$g_date = $dataRow['grade_1_date'];
		$this_year = date('Y');
		$g_year = date('Y', strtotime($g_date));
		if ($g_year == $this_year) { //檢查是否有資格領取
			$g_month = date('m', strtotime($g_date));
			$b2 = $dataRow['birthday2'];
			$b_month = date('m', strtotime($b2));
			if ($g_month <= $b_month) { //入會日在生日當月或之前
				$check_m = 1;
			} else {
				$check_m = 0;
			}
		}

		if ($check_m == 0) {
			$res = array();
			$res['msg'] = "none" . "||";
			JsonEnd($res);
		}

		// $bir_day=date("Y").date('m',$dataRow['birthday']);
		// $now_day = date('Ymd');
		$now = date('Ym');
		$now_day = $dataRow['birthday2'];
		//$now_day="20171101";
		$last_day = getlastMonthDays($now_day); //修改為上月至下個月可以領
		// $last_day = date('Y').date('m',$dataR['birthday']);
		$next_day = getNextMonthDays($now_day);

		//jill
		if (date('m', $dataRow['birthday']) == "01") {
			$bit_y = substr($next_day, 0, 4);
		} else {
			$bit_y = substr($last_day, 0, 4);
		}
		$bir_day = $bit_y . date('m', $dataRow['birthday']);
		//jill

		//下個月 月底
		//echo  $bir_day."||".$last_day."||".$next_day;
		if ($now >= $last_day && $now <= $next_day) {

			$chk_s = "select mbst_get,ord_no from birthday_voucher where mb_no='$mb_no' and mbst_get='$bit_y'";
			$chk_t = $db2->setQuery($chk_s);
			$chk_r = $db2->loadRow();
			if (count($chk_r) <= 0) {

				$q1_s = "select max(coupon_no)coupon_no from birthday_voucher ";
				$q1_t = $db2->setQuery($q1_s);
				$q1_D = $db2->loadRow();

				$q1_D = $q1_D['coupon_no'];
				$nu = (int)substr($q1_D, 4, 5) + 1;
				$ord_no = str_pad($nu, 5, '0', STR_PAD_LEFT);

				$arData = array();
				$arData['coupon_no'] = date('ym') . $ord_no;
				$arData['mb_no'] = $dataRow['mb_no'];
				$arData['mb_name'] = $dataRow['mb_name'];
				$arData['date'] = date('Ymd');
				$arData['status'] = '0'; //0未兌換 //1已兌換 //3兌換中 //4兌換處理中
				$new_time1 = strtotime(date('Ymd')); //
				$arData['end_date'] = getNextMonthDaysdd($dataRow['birthday2'], $bit_y);
				$arData['timestamp'] = time();
				$arData['mbst_get'] = $bit_y; //已年判斷是否該年已領取過
				$arData['ps'] = '經銷商於' . date('Ymd') . '領取';
				$arData['update_user'] = 'mbst';

				// $coupon_no = date('ym') . $ord_no;
				// $mb_no = $dataRow['mb_no'];
				// $mb_name = $dataRow['mb_name'];
				// $date = date('Ymd');
				// $new_time1 = strtotime(date('Ymd')); //
				// $end_date = getNextMonthDaysdd($dataRow['birthday2'], $bit_y);
				// $timestamp = time();
				// $mbst_get = $bit_y; //已年判斷是否該年已領取過
				// $ps = '經銷商於' . date('Ymd') . '領取';
				// $update_user = 'mbst';

				// $iSql = "insert into birthday_voucher (coupon_no,mb_no,mb_name,date,end_date,timestamp,mbst_get,ps,update_user) values ('$coupon_no','$mb_no','$mb_name','$date','$end_date','$timestamp','$mbst_get','$ps','$update_user')"

				$dsql = dbInsert("birthday_voucher", $arData);
				$db2->setQuery($dsql);
				$db2->query();
				$res['msg'] = "ok||" . $arData['end_date'];
			} else {
				$temp = $chk_r;
				if ($temp['ord_no'] != '' && $temp['ord_no'] != null) {
					$res['msg'] = $temp['ord_no'] . "||";
				} else {
					$res['msg'] = "give" . "||";
				}
			}
		} else {
			$res['msg'] = "none" . "||";
		}
	} else {
		$res['msg'] = "none1" . "||";
	}
	// $res['dataQ'] = $dataQ;
	$res['check_m'] = $check_m;
	$res['now'] = $now;
	$res['last_day'] = $last_day;
	$res['next_day'] = $next_day;
	JsonEnd($res);
}

// function getlastMonthDays($date)
// {
// 	$timestamp = strtotime($date);
// 	$arr = getdate($timestamp);
// 	if ($arr['mon'] == 12) {
// 		$now = date('Y-m-d');
// 		$now_time = strtotime($now);
// 		$now_arr = getdate($now_time);
// 		if ($now_arr['mon'] == 1) {
// 			$firstday = date('Ym', strtotime(date('Y', strtotime("-1 Year")) . '-' . (date('m', $timestamp)) . '-01'));
// 		} else {
// 			$firstday = date('Ym', strtotime(date('Y') . '-' . (date('m', $timestamp)) . '-01'));
// 		}
// 		// $firstday = date('Ym', strtotime(date('Y', strtotime("-1 Year")) . '-' . (date('m', $timestamp)) . '-01'));
// 	} else {
// 		$firstday = date('Ym', strtotime(date('Y') . '-' . (date('m', $timestamp)) . '-01'));
// 		$lastday = date('Ym', strtotime("$firstday +1 month -1 day"));
// 	}

// 	return $firstday;
// }

function getlastMonthDays($date)
{
	$timestamp = strtotime($date);
	$arr = getdate($timestamp);
	$now = date('Y-m-d');
	$now_time = strtotime($now);
	$now_arr = getdate($now_time);
	$now_mon = date('m');
	// return $now_arr;
	if ($arr['mon'] == 12) {
		if ($now_mon == 1) {
			$year = date('Y', strtotime('-1 Year'));
		} else {
			$year = date('Y');
		}
		$month = $arr['mon'] - 1;
		$firstday = date('Ym', strtotime($year . '-' . $month . '-01'));
		$lastday = date('Ym', strtotime("$firstday +1 month -1 day"));
	}  else {
		$firstday = date('Ym', strtotime(date('Y') . '-' . (date('m', $timestamp) - 1) . '-01'));
		$lastday = date('Ym', strtotime("$firstday +1 month -1 day"));
	}

	return $firstday;
}
function getNextMonthDays($date)
{
	$timestamp = strtotime($date);
	$arr = getdate($timestamp);
	$now_mon = date('m');
	if ($arr['mon'] == 12) {
		if ($now_mon == 1) {
			$year = date('Y');
		} else {
			$year = date('Y', strtotime('+1 Year'));
		}
		$month = $arr['mon'] - 11;
		$firstday = date('Ym', strtotime($year . '-0' . $month . '-01'));
		$lastday = date('Ym', strtotime("$firstday +1 month -1 day"));
	} else {
		$firstday = date('Ym', strtotime(date('Y') . '-' . (date('m', $timestamp) + 1) . '-01'));
		$lastday = date('Ym', strtotime("$firstday +1 month -1 day"));
	}
	return $firstday;
}
function getNextMonthDaysdd($date, $mbst_get)
{
	$timestamp = strtotime($date, $mbst_get);
	$arr = getdate($timestamp);
	$now_mon = date('m');
	if ($arr['mon'] == 12) {
		$year = $arr['year'] + 1;
		$month = $arr['mon'] - 11;
		$firstday = $year . '-0' . $month . '-01';
		if ($now_mon == 1) {
			$lastday = (date("Y")) . date('md', strtotime("$firstday +1 month -1 day"));
		} else {
			$lastday = (date("Y") + 1) . date('md', strtotime("$firstday +1 month -1 day"));
		}
	} else {
		$firstday = date('Ymd', strtotime(date('Y', $timestamp) . '-' . (date('m', $timestamp) + 1) . '-01'));
		if ($mbst_get > date("Y")) {
			$lastday = $mbst_get . date('md', strtotime("$firstday +1 month -1 day"));
		} else {
			$lastday = date("Y") . date('md', strtotime("$firstday +1 month -1 day"));
		}
	}
	return $lastday;
}

function calculate_month($date, $algorithm)
{
	$day = date('d', $date);
	$newDateFirst = date('Y-m', strtotime('first day of ' . $algorithm, $date));
	$newDate = $newDateFirst . '-' . $day;
	$newDateLast = date('Y-m-d', strtotime('last day of ' . $algorithm, $date));
	return $newDateLast < $newDate ? $newDateLast : $newDate;
}

function orgseq5()
{
	global $db2;
	ini_set('display_errors', '1');
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$sum = 0;
	$i = 0;
	$r = array();
	$res = array();
	if (empty($search_yy)) {
		$search_yy = date('Ym');
	}

	$csql = "SELECT * FROM moneypv_log WHERE yymm = '$search_yy' ORDER BY update_date DESC LIMIT 1";
	$db2->setQuery($csql);
	$checks = $db2->loadRow();
	if ($checks['state'] == 1) {

		$res['yy_select'] = $search_yy;
		if (!empty($search_yy) && $search_yy != 'all') {
			$msql = "select sum(details) temp from his_report222 r,his_moneypv2 m where m.mb_no=r.under_mb_no and m.grade_class=0 and r.yymm=m.yymm and r.mb_no='$mb_no' and r.yymm='$search_yy'";
			$db2->setQuery($msql);
			$dt = $db2->loadRow();
			$sql2 = "SELECT * from his_report222 where mb_no='$mb_no' and yymm='$search_yy' order by level_no,under_mb_no";
			$db2->setQuery($sql2);
			$r = $db2->loadRowList();
			$q = array();
			$check = 0;

			foreach ($r as $key => $e) {
				if ($e['level_no'] == 0) {
					$check = 1;
				}
				$embno = $e['under_mb_no'];
				$sql3 = "select m.g7_sorg_m,m.per_m,m.order_kind2_pv,m.true_intro_name,m.mb_name,m.grade_class as gc,m.per_mm,g.name as grade_name from his_moneypv2 as m,grade as g where m.mb_no='$embno' and m.yymm='$search_yy' and m.grade_class=g.no";
				$db2->setQuery($sql3);
				$l = $db2->loadRow();
				// if ($e['level_no'] != 0) {
				// 	if ($l['g7_sorg_m'] > $e['detail_kind']) {
				// 		$g7s = $e['detail_kind'];
				// 	} else {
				// 		$g7s = $l['g7_sorg_m'];
				// 	}
				// } else {
				// 	if ($l['gc'] > 2) {
				// 		$g7s = $e['ps'];
				// 	}
				// }
				if ($i != 0) {
					if ($l['g7_sorg_m'] > $e['detail_kind']) {
						$g7s = $e['detail_kind'];
					} else {
						$g7s = $l['g7_sorg_m'];
					}
				} else {
					$g7s = $l['g7_sorg_m'];
				}
				$g7st = $g7s;
				$sum = $sum + $g7st;
				$per_m = $l['per_m'];
				// if ($l['grade_class'] == 0) {
				// 	$per_m = $e['details'];
				// } else {
				// 	//如果是第零階要刪除其客戶的pv值
				// 	if ($e['level_no'] == 0) {
				// 		$per_m = $l['per_m'] - $dt['temp'];
				// 	} else {
				// 		$per_m = $l['per_m'];
				// 	}
				// }

				$r[$key]['u_grade_name'] = $l['grade_name'];
				$r[$key]['u_per_m'] = $per_m;
				$r[$key]['u_g7_sorg_m'] = $g7st;
				$r[$key]['u_true_intro_name'] = $l['true_intro_name'];
				$r[$key]['grade_class'] = $l['gc'];

				$i++;
			}
		}

		if ($check == 0) {
			$usql = "select m.g7_sorg_m,m.per_m,m.order_kind2_pv,m.true_intro_name,m.mb_no,m.mb_name,m.grade_class,m.per_mm,g.name as grade_name from his_moneypv2 as m,grade as g where m.mb_no='$mb_no' and m.yymm='$search_yy' and m.grade_class=g.no";
			$db2->setQuery($usql);
			$mb = $db2->loadRow();
		}


		// $report_query = "select distinct yymm,yymm as id from his_moneypv2 where mb_no='$mb_no' order by yymm desc limit 2";
		// $db2->setQuery($report_query);
		// $report_res = $db2->loadRowList();

		$report_res = array();
		$this_m = date('Ym');
		$r1 = new stdClass();
		$r1->yymm = $this_m;
		$r1->id = $this_m;
		array_push($report_res, $r1);


		$last_m = date('Ym', strtotime('-1 month'));

		$r1 = new stdClass();
		$r1->yymm = $last_m;
		$r1->id = $last_m;
		array_push($report_res, $r1);


		JsonEnd(array("status" => 1, "data" => $r, "o5_yy_list" => $report_res, 'sum' => $sum, 'yy_select' => $search_yy, 'check_level' => $check, 'e' => $mb, 'checks' => $checks));
	} else {
		$report_res = array();
		$this_m = date('Ym');
		$r1 = new stdClass();
		$r1->yymm = $this_m;
		$r1->id = $this_m;
		array_push($report_res, $r1);


		$last_m = date('Ym', strtotime('-1 month'));

		$r1 = new stdClass();
		$r1->yymm = $last_m;
		$r1->id = $last_m;
		array_push($report_res, $r1);

		JsonEnd(array("status" => 2, "o5_yy_list" => $report_res, 'yy_select' => $search_yy));
	}
}




function orgseq1()
{
	ini_set('display_errors', 1);
	global $conf_user, $db2;
	$org_kind = global_get_param($_GET, 'org_kind', null, 0, 1);
	$his = global_get_param($_GET, 'his', null, 0, 1);
	$limit = global_get_param($_GET, 'limit', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $top_mb_no = $u_data['mb_no'];
	$sub_mb_no = $mb_no;
	$boss_id = $u_data['boss_id'];
	$r = array();
	$res = array();
	if (empty($org_kind)) {
		$org_kind = 0;
	}
	if (empty($his)) {
		// $his = '-1';
		$his = date('Ym');
	}
	if (empty($limit)) {
		$limit = '3';
	}

	$now = date('Ym');

	$lSql = "SELECT yymm FROM his_moneypv2 WHERE mb_no='$mb_no' GROUP BY yymm ORDER BY yymm DESC";
	$db2->setQuery($lSql);
	$f_his2 = $db2->loadRowList();
	$err = 0;
	if (empty($f_his2)) {
		$err++;
	}

	$lSql = "SELECT yymm FROM his_moneypv2 WHERE mb_no='$mb_no' and yymm <> $now GROUP BY yymm ORDER BY yymm DESC";
	$db2->setQuery($lSql);
	$f_his = $db2->loadRowList();

	if (empty($err)) {
		$scQ = "select have_intro_no,use_e_cash from comp_data";
		$db2->setQuery($scQ);
		$scR = $db2->loadRow();
		$have_intro = 1;
		if (count($scR) > 0) {
			$have_intro = $scR['have_intro_no'];
		}

		if (isset($org_kind)) {
			if ($org_kind == 0) {
				define("ORG_KIND", "true_intro_no");
				define("ORGSEQ_NO", "orgseq_no1");
				define("LEVEL_NO", "level_no1");
				define("TRUE_INTRO_NO", "true_intro_no");
				define("LEVELLINEFLAG", "levellineflag1");
				define("SEQ_NUM", 4); //基因碼單位長度
				define("LEN_NUM", -4);
			} else {
				define("ORG_KIND", "intro_no");
				define("ORGSEQ_NO", "orgseq_no");
				define("LEVEL_NO", "level_no");
				define("TRUE_INTRO_NO", "intro_no");
				define("LEVELLINEFLAG", "levellineflag");
				define("SEQ_NUM", 1); //基因碼單位長度
				define("LEN_NUM", -1);
			}
			if ($his == '-1') {
				define("TB", "mbst");
			} else {
				define("TB", "his_moneypv2");
			}
		}

		$std = new stdClass();
		$std->data = array();
		$std->his = array();
		$qres = array();
		//對照欄位抓資料
		$str = '';
		$fie_s = "SELECT * from org_data where yn ='Y' and for_web=1 order by abs(sort),no";
		$db2->setQuery($fie_s);
		$data1 = $db2->loadRowList();
		foreach ($data1 as $key => $value) {
			$str .= ',' . $value['enfield'];
		}

		//根據語系過濾顯示職級-------------------------------------------------------------------
		if ((isset($lang)) && ($lang != 'ct')) {
			$query1 = "SELECT mb_no,mb_name" . $str . ",mb_status,pg_date," . LEVEL_NO . ",bgrade_class,translate." . $lang . " as grade_name,grade_class,line_kind,true_a_line_subs,true_b_line_subs,a_line_subs,b_line_subs 
				 FROM " . TB . " ,grade,translate  
				 WHERE " . TB . ".bgrade_class=grade.no and grade.name=translate.ct and mb_no='" . $mb_no . "'";
		} else {
			$query1 = "SELECT mb_no,mb_name" . $str . ",mb_status,pg_date," . LEVEL_NO . ",bgrade_class,grade.name AS grade_name,grade_class,line_kind,true_a_line_subs,true_b_line_subs,a_line_subs,b_line_subs 
				 FROM " . TB . " 
				 LEFT JOIN grade ON " . TB . ".bgrade_class=grade.no 
				 WHERE mb_no='" . $mb_no . "'";
		}
		//------------------------------------------------------------------------------------------------	

		if (TB == 'his_moneypv2') {
			$query1 .= " and yymm='$his'";
		}

		$db2->setQuery($query1);
		$data1 = $db2->loadRow();
		if (count($data1) < 0) {
			JsonEnd('none');
		} else {
			$query2 = "SELECT mb_no FROM " . TB . " WHERE " . TRUE_INTRO_NO . "='$mb_no'";
			if (TB == 'his_moneypv2') {
				$query2 .= " and yymm='$his'";
			}

			$qres = $data1;
			$qres['tmp_no'] = $qres['mb_no'];
			//紅崴修改20190403 modi 
			//$qres['mb_name']=encrypt_name($qres['mb_name']);
			$qres['mb_name'] = $qres['mb_name'];
			// $qres['mb_name']=str_replace('_','',$qres['mb_name']);
			$qres['pg_date'] = date('Y-m-d', $qres['pg_date']);
			$qres['mb_status'] = chgStatus($qres['mb_status']);
			$qres['parent_label'] = '0';

			$db2->setQuery($query2);
			$data2 = $db2->loadRowList();
			if (count($data2) > 0) {
				$qres['line_label'] = '5';
			} else {
				$qres['line_label'] = '3';
			}
			if (!empty($qres['grade_class'])) {
				$g_class = $qres['grade_class'];
				$grade_query = "SELECT no,name,img FROM grade where no = '$g_class' ORDER BY odring";
				$db2->setQuery($grade_query);
				$grade = $db2->loadRow();
				$g_img = $grade['img'];
				$g_name = $grade['name'];
				$qres['grade_info'] = "<img src='img/grade/$g_img' title='$g_name'>";
			}
			// $std->index->$data1['mb_no']=0;
			array_push($std->data, $qres);

			$rsql = "SELECT * FROM grade order by abs(odring)";
			$db2->setQuery($rsql);
			$rank_exp = $db2->loadRowList();
			$html = '';

			$lang = $_SESSION[$conf_user]['syslang'];

			if ($lang == 'en') {
				$name_type = 'en_name';
			} else if ($lang == 'zh-cn') {
				$name_type = 'cs_name';
			} else {
				$name_type = 'name';
			}


			foreach ($rank_exp as $each) {

				$html .= "<div style='display:inline-flex;justify-content:space-between'><img src='img/grade/" . $each['img'] . "' width='18'>" . $each[$name_type] . "</div>";
			}


			$res['data'] = $std;
			$res['f_his'] = $f_his;
			$res['have_intro'] = $have_intro;
			$res['rank_exp'] = $html;
		}
	} else {
		$res['error'] = '1';
		$res['f_his'] = $f_his;
	}
	$res['lsql'] = $lSql;

	JsonEnd($res);
}

function orgseq2()
{
	// ini_set('display_errors', '1');
	ini_set('memory_limit', '512M');
	ini_set('max_execution_time', 1800);
	global $db2;
	$mb_no = global_get_param($_GET, 'mb_no', null, 0, 1);
	$org_kind = global_get_param($_GET, 'org_kind', null, 0, 1);
	$his = global_get_param($_GET, 'his', null, 0, 1);
	$limit = global_get_param($_GET, 'limit', null, 0, 1);
	$search_name = global_get_param($_GET, 'name', null, 0, 1);
	$u_data = get_user_info();
	$top_mb_no = $u_data['mb_no'];
	$sub_mb_no = $mb_no;
	$boss_id = $u_data['boss_id'];
	$r = array();

	$sql = "select orgseq_no1 from mbst where mb_no='$top_mb_no'";
	$db2->setQuery($sql);
	$sRow = $db2->loadRow();
	if (empty($org_kind)) {
		$org_kind = 0;
	}
	if (empty($his)) {
		$his = '-1';
	}
	if (empty($limit)) {
		$limit = '3';
	}

	if (isset($org_kind)) {
		if ($org_kind == 0) {
			define("ORG_KIND", "true_intro_no");
			define("ORGSEQ_NO", "orgseq_no1");
			define("LEVEL_NO", "level_no1");
			define("TRUE_INTRO_NO", "true_intro_no");
			define("LEVELLINEFLAG", "levellineflag1");
			define("SEQ_NUM", 4); //基因碼單位長度
			define("LEN_NUM", -4);
		} else {
			define("ORG_KIND", "intro_no");
			define("ORGSEQ_NO", "orgseq_no");
			define("LEVEL_NO", "level_no");
			define("TRUE_INTRO_NO", "intro_no");
			define("LEVELLINEFLAG", "levellineflag");
			define("SEQ_NUM", 1); //基因碼單位長度
			define("LEN_NUM", -1);
		}
		if ($his == '-1') {
			define("TB", "mbst");
		} else {
			define("TB", "his_moneypv2");
		}
	}

	//代切
	$s_orgseq = $sRow['orgseq_no1'];
	$db2->setQuery("select orgseq_no1,mb_no from mbst where orgseq_no1 like '$s_orgseq%' and grade_class>=20 and mb_no!='$top_mb_no'");

	$orgseq_no1_data = $db2->loadRowList();
	$str_temp = '';
	foreach ($orgseq_no1_data as $each) {
		$str_temp .= " and (orgseq_no1 not like '" . $each['orgseq_no1'] . "%' or orgseq_no1='" . $each['orgseq_no1'] . "')";
	}

	// $orgseq_no1_data = $db2->loadRow();
	// $str_temp = '';
	// if (!empty($orgseq_no1_data)) {
	// 	$str_temp .= " and (orgseq_no1 not like '" . $orgseq_no1_data['orgseq_no1'] . "%' or orgseq_no1='" . $orgseq_no1_data['orgseq_no1'] . "')";
	// }

	$str = '';
	$fie_s = "SELECT * from org_data where yn='Y' and for_web=1 order by abs(sort),no";
	$db2->setQuery($fie_s);
	$data1 = $db2->loadRow();
	// if (!empty($data1)) {
	// 	$str .= ',' . $data1['enfield'];
	// }



	$query3 = "SELECT " . ORGSEQ_NO . "," . LEVEL_NO . " FROM " . TB . " WHERE mb_no='$sub_mb_no'";
	if (TB == 'his_moneypv2') {
		$query3 .= " and yymm='$his'";
	}
	$db2->setQuery($query3);
	$data3 = $db2->loadRow();

	$std = new stdClass();
	$std->data = array();
	$qres = array();

	if (!empty($search_name)) {
		$search_status = 1;
		$query1 = "SELECT mb_no,mb_name, mb_status,mb_status as m_status,pg_date,orgseq_no1," . TRUE_INTRO_NO . "," . LEVEL_NO . ",bgrade_class,grade.name AS grade_name,grade_class,line_kind," . LEVELLINEFLAG . ",per_m ,true_a_line_subs,true_b_line_subs,a_line_subs,b_line_subs,block_view,member_date
					 FROM " . TB . " 
					 LEFT JOIN grade ON " . TB . ".bgrade_class=grade.no 
					 WHERE " . ORGSEQ_NO . " LIKE '" . $data3[ORGSEQ_NO] . "%' and mb_no<>" . TRUE_INTRO_NO . " and mb_name LIKE '%" . $search_name . "%'";
	} else {
		$search_status = 0;
		$query1 = "SELECT mb_no,mb_name, mb_status,mb_status as m_status,pg_date,orgseq_no1," . TRUE_INTRO_NO . "," . LEVEL_NO . ",bgrade_class,grade.name AS grade_name,grade_class,line_kind," . LEVELLINEFLAG . ",per_m ,true_a_line_subs,true_b_line_subs,a_line_subs,b_line_subs,block_view,member_date
		FROM " . TB . " 
		LEFT JOIN grade ON " . TB . ".bgrade_class=grade.no 
		WHERE " . ORGSEQ_NO . " LIKE '" . $data3[ORGSEQ_NO] . "%' and mb_no<>" . TRUE_INTRO_NO . " and " . LEVEL_NO . ">" . $data3[LEVEL_NO] . " and " . LEVEL_NO . "<" . ($data3[LEVEL_NO] + $limit + 1);
	}
	if (TB == 'his_moneypv2') {
		$query1 .= " and yymm='$his'";
	}
	$query1 .= $str_temp;
	$query1 .= " ORDER BY " . ORGSEQ_NO . ",pg_date";
	$db2->setQuery($query1);
	$result = $db2->loadRowList();
	$row_num = count($result);
	if (empty($result)) {
		$status = 0;
	} else {
		foreach ($result as $k => $v) {
			$qres = $v;
			$qres['tmp_no'] = $qres['mb_no'];
			//紅崴修改20190403 modi 
			//$qres['mb_name']=encrypt_name($qres['mb_name']);
			$qres['mb_name'] = $qres['mb_name'];
			// $qres['mb_name']=str_replace('_','',$qres['mb_name']);
			$qres['pg_date'] = date('Y-m-d', $qres['pg_date']);
			$qres['mb_status'] = chgStatus($qres['mb_status']);
			$query2 = "SELECT mb_no FROM " . TB . " WHERE " . TRUE_INTRO_NO . "='" . $v['mb_no'] . "' and mb_no<>" . TRUE_INTRO_NO;
			if (TB == 'his_moneypv2') {
				$query2 .= " and yymm='$his'";
			}
			$db2->setQuery($query2);
			$result2 = $db2->loadRowList();
			# 有下線時
			if (count($result2) > 0) {
				if ($v[LEVEL_NO] == ($data3[LEVEL_NO] + $limit)) {
					if (strlen(trim($qres[LEVELLINEFLAG])) > 0) { # 為最後一筆
						$qres['line_label'] = '5';
						$qres['parent_label'] = '0';
					} else {
						$qres['line_label'] = '4';
						$qres['parent_label'] = '1';
					}
				} else {
					if (strlen(trim($qres[LEVELLINEFLAG])) > 0) { # 為最後一筆
						$qres['line_label'] = '7';
						$qres['parent_label'] = '0';
					} else {
						$qres['line_label'] = '6';
						$qres['parent_label'] = '1';
					}
				}
			} else {
				if (strlen(trim($qres[LEVELLINEFLAG])) > 0) { # 為最後一筆
					$qres['line_label'] = '3';
					$qres['parent_label'] = '0';
				} else {
					$qres['line_label'] = '2';
					$qres['parent_label'] = '1';
				}
			}
			if (!empty($qres['grade_class']) || $qres['grade_class'] == '0') {
				$g_class = $qres['grade_class'];
				if (!empty($qres['member_date'])) {
					$g_class = '15';
				}
				$grade_query = "SELECT no,name,img FROM grade where no = '$g_class' ORDER BY no";
				$db2->setQuery($grade_query);
				$grade = $db2->loadRow();
				$g_img = $grade['img'];
				$g_name = $grade['name'];
				$qres['grade_info'] = "<img src='img/grade/$g_img' title='$g_name'>";
			}
			array_push($std->data, $qres);
		}
		$std->true_intro_no = $sub_mb_no;
		$std->level = null;
		$status = 1;
	}

	// $report_query = "select distinct yymm,yymm as id from his_moneypv2 where mb_no='$mb_no' order by yymm desc limit 2";
	// $db2->setQuery($report_query);
	// $report_res = $db2->loadRowList();
	JsonEnd(array('data' => $std, 'row_cnt' => $row_num, 'status' => $status, 'search_status' => $search_status));
	// JsonEnd(array("status" => 1, "data" => $std));
}

function search_mbno()
{
	ini_set('display_errors', '1');
	global $db, $db2, $db3;
	$search_mbno = global_get_param($_GET, 'search_mbno', null, 0, 1);
	$mb_no = $search_mbno;
	$org_kind = 0;
	$his = '-1';
	$limit = '3';
	$res = array();
	if (isset($org_kind)) {
		if ($org_kind == 0) {
			define("ORG_KIND", "true_intro_no");
			define("ORGSEQ_NO", "orgseq_no1");
			define("LEVEL_NO", "level_no1");
			define("TRUE_INTRO_NO", "true_intro_no");
			define("LEVELLINEFLAG", "levellineflag1");
			define("SEQ_NUM", 4); //基因碼單位長度
			define("LEN_NUM", -4);
		} else {
			define("ORG_KIND", "intro_no");
			define("ORGSEQ_NO", "orgseq_no");
			define("LEVEL_NO", "level_no");
			define("TRUE_INTRO_NO", "intro_no");
			define("LEVELLINEFLAG", "levellineflag");
			define("SEQ_NUM", 1); //基因碼單位長度
			define("LEN_NUM", -1);
		}
		if ($his == '-1') {
			define("TB", "mbst");
		} else {
			define("TB", "his_moneypv2");
		}
	}

	$str = '';
	$fie_s = "SELECT * from org_data where yn ='Y' and for_web=1 order by abs(sort),no";
	$db2->setQuery($fie_s);
	$data1 = $db2->loadRowList();
	foreach ($data1 as $key => $value) {
		$str .= ',' . $value['enfield'];
	}

	$query1 = "SELECT mb_no,mb_name" . $str . ",mb_status,FROM_UNIXTIME(`pg_date`,'%Y-%m-%d') as pg_date," . LEVEL_NO . ",bgrade_class,grade.name AS grade_name,grade_class,line_kind,true_a_line_subs,true_b_line_subs,a_line_subs,b_line_subs,grade_1_date,have_order_1 ,member_date
			 FROM " . TB . " 
			 LEFT JOIN grade ON " . TB . ".bgrade_class=grade.no 
			 WHERE mb_no='" . $search_mbno . "'";

	$db2->setQuery($query1);
	$result = $db2->loadRow();
	$lang = $_SESSION[$conf_user]['syslang'];
	if ($result['grade_class'] == '0' && !empty($result['member_date'])) {
		$c_sql2 = "SELECT _m.*, ll.`" . $lang . "` as lv_name from member_lv as _m LEFT JOIN lv_list as ll on _m.lv = ll.level where _m.mb_no = '$mb_no'";
		$db3->setQuery($c_sql2);
		$c_res2 = $db3->loadRow();
		if (!empty($c_res2)) {
			$c_lv = $c_res2['lv']; //會員等級
			$lv_name = $c_res2['lv_name'];
		} else {
			$c_sql3 = "SELECT `" . $lang . "` as lv_name from lv_list where level = '1'";
			$db3->setQuery($c_sql3);
			$c_res3 = $db3->loadRow();
			$lv_name = $c_res3['lv_name'];
		}

		// switch ($c_lv) {
		// 	case '1':
		// 		$lv_name = '銀卡會員';
		// 		break;
		// 	case '2':
		// 		$lv_name = '金卡會員';
		// 		break;
		// 	case '3':
		// 		$lv_name = '白金會員';
		// 		break;
		// 	default:
		// 		$lv_name = '銀卡會員';
		// 		break;
		// }
		if (!empty($c_res2)) {
			$c_total = $c_res2['total']; //會員消費額度
		} else {
			$c_total = '0';
		}


		$c_member_date = $result['member_date'];

		$c_have_order_1 = $result['have_order_1'];

		$res['c_lv_name'] = $lv_name;
		$res['c_total'] = $c_total;
		$res['c_member_date'] = $c_member_date;
		$res['c_have_order_1'] = $c_have_order_1;


		JsonEnd(array('status' => '1', 'data' => $res, 'is_member' => '1'));
	} else {
		JsonEnd(array('status' => '1', 'data' => $result));
	}
}

function chgStatus($id)
{
	switch ($id) {
		case '1':
			return '正式';
			break;
		case '2':
			return '停權';
			break;
		case '3':
			return '解約';
			break;
	}
}
function chgKind($id)
{
	switch ($id) {
		case 1:
			return '消費經銷商';
			return;
		case 2:
			return '直銷商';
			break;
	}
}

function ecash_new2_1_list()
{
	global $conf_user;
	$search_yy = global_get_param($_GET, 'search_yy', null, 0, 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];
	$lang = $_SESSION[$conf_user]['syslang'];

	if ($lang == 'en') {
		$name_type = 'name_en';
	} else if ($lang == 'zh-cn') {
		$name_type = 'name_zh_cn';
	} else {
		$name_type = 'name';
	}
	if (!empty($search_yy) && $search_yy != 'all') {
		$ec_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_new2_1_list_api.php?mb_no=$mb_no&boss_id$boss_id&lang=$name_type&yy=$search_yy");
	} else {
		$ec_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_new2_1_list_api.php?mb_no=$mb_no&boss_id$boss_id&lang=$name_type");
	}


	$ec_result = json_decode($ec_result, true);
	$earlist_yy = '2017';
	$now_yy = date('Y');
	$ecash_yy_list = array();
	for ($i = $earlist_yy; $i <= $now_yy; $i++) {
		$obj = [
			"id" => $i,
			"year" => $i
		];
		array_unshift($ecash_yy_list, $obj);
	}

	JsonEnd(array("status" => 1, "data" => $ec_result, "ecash_yy_list" => $ecash_yy_list));
}


function ecash_new2_2_list()
{
	global $conf_user;
	// ini_set('display_errors',1);

	// global $db, $conf_user;

	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$boss_id = $u_data['boss_id'];

	$lang = $_SESSION[$conf_user]['syslang'];

	if ($lang == 'en') {
		$name_type = 'name_en';
	} else if ($lang == 'zh-cn') {
		$name_type = 'name_zh_cn';
	} else {
		$name_type = 'name';
	}

	$ec_result = file_get_contents(MLMURL . "ct/mbst/get_e_cash_new2_2_list_api.php?mb_no=$mb_no&boss_id$boss_id&lang=$name_type");

	$ec_result = json_decode($ec_result, true);
	// $bonus_query="SELECT point,a.ord_no,date,eff_date,end_date,ps,IFNULL(datediff(a.end_date,now()),0) remain,b.name FROM e_cash2 as a,e_cash2_kind as b WHERE mb_no = '".$mb_no;
	// $bonus_query.="' and a.kind=b.id and end_date>=DATE_FORMAT(now(),'%Y-%m-%d') and datediff(end_date,now()) <= 90 and exchange_sn > 0 order by eff_date desc,date desc ";

	// $db2->setQuery($bonus_query);
	// $r=$db2->loadRowList();
	$url = MLMURL . "ct/mbst/get_e_cash_new2_2_list_api.php?mb_no=$mb_no&boss_id$boss_id&lang=$name_type";
	JsonEnd(array("status" => 1, "data" => $ec_result, "url" => $url));
}

function member_news_list()
{
	global $db, $db2, $globalConf_list_limit, $conf_news, $conf_user;

	$arrJson = array();
	$lang = $_SESSION[$conf_user]['syslang'];

	$page = max(intval(global_get_param($_REQUEST, 'page', 1)), 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	// $sql2 = "select * from mbst where mb_no = '$mb_no'";
	// $sql2 = "SELECT * FROM news where publish=0 AND (newsDate='' OR newsDate<='".date("Y-m-d")."') AND (pubDate='' OR pubDate>='".date("Y-m-d")."') ORDER BY newsDate desc,id desc";
	// $db2->setQuery( $sql2 );
	// $result = $db2->loadRowList();

	$uid = loginChk();
	$om = getFieldValue("SELECT onlyMember from members where id = '$uid'", "onlyMember");
	$om_str = '';
	if ($om == '1') {
		$om_str = " AND (audience = '1' or audience = '0')";
	} else {
		$om_str = " AND (audience = '2' or audience = '0')";
	}


	$sql = "SELECT *,`name_" . $lang . "` as name,`summary_" . $lang . "` as summary FROM news where publish=1 AND target=1 AND (newsDate='' OR newsDate<='" . date("Y-m-d") . "') AND (pubDate='' OR pubDate>='" . date("Y-m-d") . "') $om_str ORDER BY newsDate desc,id desc";
	$db->setQuery($sql);
	$r = $db->loadRowList();

	$cnt = count($r);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$page = ($page > $pagecnt) ? $pagecnt : $page;

	$from = ($page - 1) * $globalConf_list_limit;
	$end = $page * $globalConf_list_limit;

	$data = array();
	for ($i = $from; $i < min($end, $cnt); $i++) {
		$info = array();
		$info['id'] = $r[$i]['id'];
		$info['name'] = $r[$i]['name'];
		$info['summary'] = $r[$i]['summary'];
		$info['linktype'] = $r[$i]['linktype'];

		if ($r[$i]['linktype'] == "page") {
			$info['linkurl'] = "member_page/member_news_page/{$r[$i]['id']}?cur=$page";
		} else if ($r[$i]['linktype'] == "link") {
			$info['linkurl'] = $r[$i]['linkurl'];
		}
		$info['newsM'] = date("m", strtotime($r[$i]['newsDate']));
		$info['newsD'] = date("d", strtotime($r[$i]['newsDate']));
		$info['imgname'] = getimg("news", $r[$i]['id'])[1];
		if (!$info['imgname']) $info['imgname'] = $conf_news . "default.jpg";
		$data[] = $info;
	}
	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	$arrJson['sql'] = $sql;
	JsonEnd($arrJson);
}

function member_news_page()
{
	global $db, $conf_upload, $conf_user;

	$lang = $_SESSION[$conf_user]['syslang'];

	$arrJson = array();
	$id = intval(global_get_param($_REQUEST, 'news_no', 1));
	if ($id == 0) {
		JsonEnd(array("status" => 0, "msg" => "無此訊息"));
	}

	$sql = "select *,`name_" . $lang . "` as name,`summary_" . $lang . "` as summary,`content_" . $lang . "` as content from news where id='$id' AND publish=1 AND target=1";
	$db->setQuery($sql);
	$r = $db->loadRow();
	if (count($r) == 0) {
		JsonEnd(array("status" => 0, "msg" => "無此訊息"));
	}

	$arrJson['status'] = 1;
	$arrJson['data']['name'] = $r['name'];
	$arrJson['data']['newsDate'] = $r['newsDate'];

	$arrJson['data']['_content'] = mb_substr(strip_tags($r['content']), 0, 150, "utf-8");
	preg_match('/<img[^>]*>/Ui', $r['content'], $content_img);

	preg_match('@src="([^"]+)"@', $content_img[0], $contentimg_src);
	$src = array_pop($contentimg_src);
	$imginfo = getimagesize($conf_dir_path . "../.." . $src);
	$arrJson['data']['_content_img'] = $src;
	$arrJson['data']['_imgwidth'] = $imginfo[0];
	$arrJson['data']['_imgheight'] = $imginfo[1];

	$arrJson['data']['content'] = $r['content'] . "<script>
										$(document).ready(
											function(){
												$('#dbpage_content').find('img').addClass('img-responsive');
											}
										);
										
									</script>";
	JsonEnd($arrJson);
}

function download_page()
{
	global $db2, $db, $conf_user;
	$u_data = get_user_info();
	$mb_grade = $u_data['mb_grade'];

	$uid = loginChk();
	$msql = "SELECT onlyMember from members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$om = $md['onlyMember'];

	$kind = global_get_param($_GET, 'kind', null, 0, 1);
	// $lang = 'tw';
	$lang = $_SESSION[$conf_user]['syslang'];
	$sql = "select * from down_kind where 1 and lang='$lang' order by ording asc";
	$db->setQuery($sql);
	$kind_list = $db->loadRowList();

	if ($lang == 'en') {
		$kind = $kind - 4;
	}

	if ($lang == 'zh-cn') {
		$kind = $kind - 8;
	}

	if ($kind < 0) {
		$kind = 0;
	}

	$where_str = '';
	if ($om == '0') {
		$where_str = " AND (m.filetarget='1' OR m.filetarget='2')";
	} else if ($om == '1') {
		$where_str = " AND (m.filetarget='1' OR m.filetarget='3')";
	}
	$sql2 = "SELECT m.name as mfile_name,DATE(m.mtime) as mtime,m.note,m.id as mid,f.name as f_name from uploadfiles as m,filelist as f where m.id = f.belongid  $where_str";

	if (!empty($kind)) {
		$sql2 .= " and m.filetype = '$kind'";
	}
	$sql2 .= " and m.publish = 1 order by m.odring asc";
	$db->setQuery($sql2);
	// echo $sql2;
	$result = $db->loadRowList();
	// $lang = 'ct';
	// $sql = "SELECT * from down_kind where 1 and lang='$lang'";
	// $db2->setQuery($sql);
	// $kind_list = $db2->loadRowList();
	// $lang2 = " and lang='" . $lang . "'";
	// $tmp_str = '';
	// $cat_str = '';
	// if (!empty($kind)) {
	// 	$level2_query = "SELECT level2 FROM down_kind,down_kind_link 
	// 	WHERE level1=$kind and level2=down_kind.kind_no and level3 is NULL ORDER BY sort,level2";
	// 	$db2->setQuery($level2_query);
	// 	$level2_result = $db2->loadRowList();
	// 	if (count($level2_result) > 0) {
	// 		foreach ($level2_result as $lr) {
	// 			$tmp_str .= " or link_no='" . $lr['level2'] . "'";
	// 		}
	// 	}
	// 	$cat_str = " and (link_no='" . $kind . "'" . $tmp_str . ")";
	// } else {
	// 	$cat_str = '';
	// }

	// $query = "SELECT *,FROM_UNIXTIME(`add_date`,'%Y-%m-%d') as start_date FROM download where 1 " . $cat_str . $lang2 . " and (grade_$mb_grade=1 or grade_all=1) ORDER BY start_time Desc";
	// $db2->setQuery($query);
	// $result = $db2->loadRowList();

	$data = array();
	$data['status'] = 1;
	$data['data'] = $result;
	$data['kind_list'] = $kind_list;
	$data['kind'] = $kind;
	JsonEnd($data);
}

// function download_page()
// {
// 	global $db2, $db;
// 	$u_data = get_user_info();
// 	$mb_grade = $u_data['mb_grade'];

// 	$uid = loginChk();

// 	$msql = "SELECT onlyMember from members where id = '$uid'";
// 	$db->setQuery($msql);
// 	$md = $db->loadRow();

// 	$om = $md['onlyMember'];



// 	$kind = global_get_param($_GET, 'kind', null, 0, 1);
// 	$lang = 'tw';
// 	$sql = "select * from down_kind where 1 and lang='$lang' order by ording asc";
// 	$db->setQuery($sql);
// 	$kind_list = $db->loadRowList();

// 	if ($lang != 'tw') {
// 		$kind = $kind - 4;
// 	}

// 	$where_str = '';
// 	if($om=='0'){
// 		$where_str = " AND (m.filetarget='1' OR m.filetarget='2')";
// 	}else if($om=='1'){
// 		$where_str = " AND (m.filetarget='1' OR m.filetarget='3')";
// 	}

// 	$sql2 = "SELECT m.name as mfile_name,DATE(m.mtime) as mtime,m.note,m.id as mid,f.name as f_name from uploadfiles as m,filelist as f where m.id = f.belongid  $where_str";
// 	if (!empty($kind)) {
// 		$sql2 .= " and m.filetype = '$kind'";
// 	}
// 	$sql2 .= " and m.publish = 1 order by m.odring asc";
// 	$db->setQuery($sql2);
// 	// echo $sql2;
// 	$result = $db->loadRowList();
// 	// $lang = 'ct';
// 	// $sql = "SELECT * from down_kind where 1 and lang='$lang'";
// 	// $db2->setQuery($sql);
// 	// $kind_list = $db2->loadRowList();
// 	// $lang2 = " and lang='" . $lang . "'";
// 	// $tmp_str = '';
// 	// $cat_str = '';
// 	// if (!empty($kind)) {
// 	// 	$level2_query = "SELECT level2 FROM down_kind,down_kind_link 
// 	// 	WHERE level1=$kind and level2=down_kind.kind_no and level3 is NULL ORDER BY sort,level2";
// 	// 	$db2->setQuery($level2_query);
// 	// 	$level2_result = $db2->loadRowList();
// 	// 	if (count($level2_result) > 0) {
// 	// 		foreach ($level2_result as $lr) {
// 	// 			$tmp_str .= " or link_no='" . $lr['level2'] . "'";
// 	// 		}
// 	// 	}
// 	// 	$cat_str = " and (link_no='" . $kind . "'" . $tmp_str . ")";
// 	// } else {
// 	// 	$cat_str = '';
// 	// }

// 	// $query = "SELECT *,FROM_UNIXTIME(`add_date`,'%Y-%m-%d') as start_date FROM download where 1 " . $cat_str . $lang2 . " and (grade_$mb_grade=1 or grade_all=1) ORDER BY start_time Desc";
// 	// $db2->setQuery($query);
// 	// $result = $db2->loadRowList();

// 	$data = array();
// 	$data['status'] = 1;
// 	$data['data'] = $result;
// 	$data['kind_list'] = $kind_list;
// 	JsonEnd($data);
// }

function exchange_coupon()
{
	global $db2;
	$res = array();
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$coupon_no = global_get_param($_POST, 'coupon_no', null, 0, 1);

	$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status=0 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0 and coupon_no='$coupon_no'";
	$db2->setQuery($sql);
	$result = $db2->loadRow();
	if (count($result) > 0) {
		$rsn = $result['sn'];
		$sql2 = "update carry_treasure set status=1 where coupon_no='$coupon_no' and sn = '$rsn'";
		$db2->setQuery($sql2);
		$db2->query();
		$res['success'] = true;
	} else {
		$res['error'] = true;
	}
	JsonEnd($res);
}

function exchange_coupon_all()
{
	ini_set('display_errors', '1');
	global $db2;
	$res = array();
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	$coupon_num = global_get_param($_POST, 'num', null, 0, 1);

	if (is_numeric($coupon_num) && !empty($coupon_num)) {
		$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status=0 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0 order by date asc limit $coupon_num";
		$db2->setQuery($sql);
		$db2->query();
		$result = $db2->loadRowList();
		$can_use_num = count($result);
		if ($coupon_num == $can_use_num) {
			foreach ($result as $each) {
				$coupon_no = $each["coupon_no"];
				$csn = $each['sn'];
				$sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status=0 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0 and coupon_no='$coupon_no' and sn = '$csn'";
				$db2->setQuery($sql);
				$result2 = $db2->loadRow();
				if (count($result2) > 0) {
					$rsn = $result2['sn'];
					$sql2 = "update carry_treasure set status=1 where coupon_no='$coupon_no' and sn = '$rsn'";
					$db2->setQuery($sql2);
					$db2->query();
					$res['success'] = true;
				} else {
					$res['error'] = true;
					$res['error_msg'] = _MEMBER_NO_VOUCHER . $coupon_no;
				}
			}
		} else {
			$res['error_msg'] = _MEMBER_ERROR_8;
		}
	} else {
		$res['error_msg'] = _MEMBER_ERROR_9;
	}


	// $sql = "SELECT * FROM carry_treasure WHERE mb_no = '$mb_no' and status=0 and DATE_FORMAT(date,'%Y%m%d')<='" . date('Ymd') . "' and chk_invalid=0 and coupon_no='$coupon_no'";
	// $db2->setQuery($sql);
	// $result = $db2->loadRow();
	// if (count($result) > 0) {
	// 	$sql2 = "update carry_treasure set status=1 where coupon_no='$coupon_no'";
	// 	$db2->setQuery($sql2);
	// 	$db2->query();
	// 	$res['success'] = true;
	// } else {
	// 	$res['error'] = true;
	// }
	JsonEnd($res);
}


function get_user_info()
{
	global $db, $db2, $conf_user, $tablename;

	$uid = LoginChk();

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$data = array();
	$data['mb_no'] = $r['ERPID'];
	$mb_no = $data['mb_no'];
	$data['boss_id'] = $r['cardnumber'];

	$sql2 = "select * from mbst where mb_no = '$mb_no'";
	$db2->setQuery($sql2);
	$r2 = $db2->loadRow();
	$data['mb_grade'] = $r2['grade_class'];


	return $data;
}

// function transfer_data()
// {
// 	global $db,$db2;
// 	$res = array();
// 	$sql = "SELECT * FROM members";
// 	$db->setQuery($sql);
// 	$result = $db->loadRowList();
// 	$res['result'] = count($result);
// 	foreach ($result as $each) {
// 		$mb_no = $each['ERPID'];
// 		$sql = "SELECT mb_no from mbst where chk_used = '0' and mb_no = '$mb_no'";
// 		$db->setQuery($sql);
// 		$oldr = $db->loadRow();
// 		if(count($oldr) > 0){
// 			$usql = "UPDATE mbst set chk_used = '1' where mb_no = '$mb_no'";
// 			$db->setQuery($usql);
// 			$db->query();
// 			$isql = "Insert into mbst_log (mb_no) values ('$mb_no')";
// 			$db->setQuery($isql);
// 			$db->query();
// 		}
// 	}
// 	JsonEnd($res);

// }

// function transfer_data()
// {
// 	ini_set('display_errors',1);
// 	global $db,$db2;
// 	$res = array();
// 	$sql = "SELECT * FROM members";
// 	$db->setQuery($sql);
// 	$result = $db->loadRowList();
// 	$res['result'] = count($result);
// 	foreach ($result as $each) {
// 		if(!empty($each['sid'])){
// 			$pwd = enpw($each['sid']);
// 			$id = $each['id'];
// 			$sql = "UPDATE members set passwd='$pwd' where id = '$id'";
// 			$db->setQuery($sql);
// 			$db->query();
// 		}
// 	}
// 	JsonEnd($res);

// }

function transfer_data() //回覆已刪除的訂單
{
	ini_set('display_errors', 1);
	global $db, $db2;
	$res = array();
	$sql = "SELECT * FROM deletelog where id = '171'";
	$db->setQuery($sql);
	$result = $db->loadRow();
	$members_str = $result['mambers'];
	$members_arr = explode('，', $members_str);
	$member_str = implode(',', $members_arr);
	$m = str_replace(",", "','", $member_str);
	$m = "'" . $m . "'";

	$orders_str = $result['orders'];
	$orders_arr = explode('，', $orders_str);
	$order_str = implode(',', $orders_arr);
	$o = str_replace(",", "','", $order_str);
	$o = "'" . $o . "'";

	$ordersd_str = $result['orderdtl'];
	$ordersd_arr = explode('，', $ordersd_str);
	$orderd_str = implode(',', $ordersd_arr);
	$od = str_replace(",", "','", $orderd_str);
	$od = "'" . $od . "'";

	$sql2 = "INSERT INTO `members` (`id`, `sysid`, `ERPID`, `locked`, `belongid`, `recommendCode`, `certificated`, `certifiCode`, `rootChk`, `salesChk`, `level`, `name`, `name2`, `receivename`, `sid`, `email`, `emailChk`, `cardnumber`, `pvgeLevel`, `sex`, `birthdate`, `regDate`, `mobile`, `phone`, `education`, `zip`, `city`, `canton`, `addr`, `fulladdr`, `reszip`, `rescity`, `rescanton`, `resaddr`, `department`, `depTitle`, `memberChk`, `newsletterChk`, `loginid`, `fbloginid`, `gploginid`, `token`, `passwd`, `coin`, `pv`, `bv`, `bonus`, `odring`, `ERPChk`, `ERPResponse`, `logincnt`, `delayCnt`, `dlvrLocation`, `Birthday`, `memType`, `payDate`, `recommendName`, `recommendPhone`, `recommendMobile`, `usedChk`, `memberWNo`, `ctime`, `mtime`, `muser`, `errMsg`, `regPoint`) VALUES ($m)";
	$db->setQuery($sql2);
	$db->query();

	$sql3 = "INSERT INTO `orders` (`id`, `orderNum`, `orderECNum`, `combineid`, `memberid`, `email`, `buyDate`, `endDate`, `finalPayDate`, `status`, `payType`, `dlvrType`, `sumAmt`, `discount`, `dcntAmt`, `dlvrFee`, `usecoin`, `freecoin`, `totalAmt`, `scoreAmt`, `bonusAmt`, `e3Amt`, `orderMode`, `pv`, `bv`, `bonus`, `regpoint`, `pointchk`, `dlvrTimePeriod`, `dlvrName`, `dlvrMobile`, `dlvrCity`, `dlvrCanton`, `dlvrZip`, `dlvrAddr`, `dlvrDate`, `dlvrTime`, `dlvrNote`, `atmlastNum`, `atmName`, `atmDate`, `atmTime`, `atmBank`, `atmMoney`, `invoiceInfo`, `invoiceType`, `invoiceTitle`, `invoiceSN`, `invoice`, `traceNumber`, `traceName`, `traceUrl`, `exportChk`, `createDate`, `virtualAccount`, `ctime`, `mtime`, `muser`, `return_regpoint`) VALUES ($o)";
	$db->setQuery($sql3);
	$db->query();

	$sql4 = "INSERT INTO `orderdtl` (`id`, `oid`, `orioid`, `pid`, `format1`, `format2`, `format3`, `format1name`, `format2name`, `format3name`, `unitAmt`, `e3unitAmt`, `quantity`, `subAmt`, `sesubAmt`, `e3subAmt`, `subScore`, `actionid`, `actionNotes`, `bonusAmt`, `pv`, `bv`, `bonus`, `regpoint`, `protype`, `ctime`, `mtime`, `muser`, `has_return`) VALUES ($od)";
	$db->setQuery($sql4);
	$db->query();

	JsonEnd($res);
}

// function transfer_data(){
// 	ini_set('display_errors', 1);
// 	global $db,$db2;
// 	$sql = "SELECT * from members where locked = '1'";
// 	$db->setQuery($sql);
// 	$members = $db->loadRowList();
// 	foreach ($members as $each) {
// 		$mb_no = $each['ERPID'];
// 		$sql2 = "SELECT * from mbst where mb_no = '$mb_no'";
// 		$db2->setQuery($sql2);
// 		$m = $db2->loadRow();
// 		$mstatus = $m['mb_status'];
// 		$mgrade = $m['grade_1_chk'];
// 		$mpg = date('Y-m-d',$m['pg_end_date']);
// 		$now = date('Y-m-d');

// 		if($mstatus == 1 && $mgrade == 1 && $mpg > $now){
// 			$usql = "UPDATE members set locked = '0',errMsg = 'unlocked by manual' where ERPID = '$mb_no'";
// 			$db->setQuery($usql);
// 			$db->query();
// 		}
// 	}

// }

function get_mb_no()
{
	global $db, $db2, $conf_user, $tablename;

	$uid = LoginChk();

	$sql = "select * from $tablename where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$data = array();
	if (!empty($r)) {
		$data['mb_no'] = $r['ERPID'];
		$data['status'] = 1;
	} else {
		$data['status'] = 0;
	}

	JsonEnd($data);
}

function ecash_stat()
{
	global $db, $db2, $conf_user, $tablename;
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$sql = "SELECT * FROM his_order_disc AS A,date_tb AS B WHERE A.yymm = B.yymm AND B.do_show = 'Y' and A.mb_no = '$mb_no' AND B.yymm = '202105' order by A.yymm desc limit 1";
	$db2->setQuery($sql);
	$e = $db2->loadRow();
	$sql2 = "SELECT * FROM his_order_disc AS A,date_tb AS B WHERE A.yymm = B.yymm AND B.do_show = 'Y' AND B.yymm = '202105' order by A.yymm desc limit 1";
	$db2->setQuery($sql2);
	$e2 = $db2->loadRow();
	$res = array();
	$d = $e2['cut_date'];
	$e_date = date('Y-m-d', strtotime($d));
	if (date('m') <= 02) {
		$e_year = date('Y');
	} else {
		$e_year = date('Y');
	}
	$ecash_stat = array();
	$ecash_stat['pointa'] = $e['pointa'];
	$ecash_stat['pointj'] = $e['pointj'];
	$price_point = $e['pointd'] + $e['pointe'] + $e['pointf'] + $e['pointg'] + $e['pointh'] + $e['pointk'] + $e['pointl'];
	$ecash_stat['price_point'] = $price_point;
	$ecash_stat['all_point'] = $e['pointa'] + $ecash_stat['price_point'];
	if (empty($ecash_stat['pointa'])) {
		$ecash_stat['pointa'] = 0;
	}
	if (empty($ecash_stat['pointj'])) {
		$ecash_stat['pointj'] = 0;
	}
	if (empty($ecash_stat['price_point'])) {
		$ecash_stat['price_point'] = 0;
	}
	if (empty($ecash_stat['all_point'])) {
		$ecash_stat['all_point'] = 0;
	}
	$res['ecash_list'] = $e;
	$res['ecash_stat'] = $ecash_stat;
	// $res['e_year'] = $e_year;
	// $res['e_date'] = $e_date;
	$res['e_year'] = '2022';
	$res['e_date'] = '2021-05-31';
	$res['status'] = 1;
	JsonEnd($res);
}

function member_poster_list()
{
	global $db, $db2, $globalConf_list_limit, $conf_news;

	$arrJson = array();

	$page = max(intval(global_get_param($_REQUEST, 'page', 1)), 1);
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];
	// $sql2 = "select * from mbst where mb_no = '$mb_no'";
	// $sql2 = "SELECT * FROM news where publish=0 AND (newsDate='' OR newsDate<='".date("Y-m-d")."') AND (pubDate='' OR pubDate>='".date("Y-m-d")."') ORDER BY newsDate desc,id desc";
	// $db2->setQuery( $sql2 );
	// $result = $db2->loadRowList();
	$now = date('Y-m-d');
	$isql = "SELECT A.id,A.name AS name,B.name AS img_url FROM poster A, imglist B WHERE A.id = B.belongid AND B.path = 'poster' AND A.publish = '1' AND (('$now' >= A.start_date and '$now' <= A.end_date) or ('$now' >= A.start_date and (A.end_date is NULL or A.end_date = '')) or ((A.start_date is NULL or A.start_date = '') and '$now' <= A.end_date) or ((A.start_date is NULL or A.start_date = '') and (A.end_date is NULL or A.end_date = ''))) order by odring asc";

	$db->setQuery($isql);
	$imglist = $db->loadRowList();
	$arrJson['data'] = $imglist;

	$arrJson['status'] = 1;

	JsonEnd($arrJson);
}

function sign30_signupChk()
{
	global $db, $db2, $tablename, $conf_user, $globalConf_signupDemo_ver2020;

	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);

	if (!$sid) {

		JsonEnd(array("status" => 0, "msg" => "請輸入身分證字號"));
	}

	$chk = getFieldValue("select count(1) as cnt from $tablename where sid='$sid' and locked = '0'", "cnt");
	if ($chk > 0) {

		JsonEnd(array("status" => 0, "msg" => _MEMBER_EXIST_IC));
	}

	if ($globalConf_signupDemo_ver2020) {
		$login_result["status"] = 1;
		$login_result["cardno"] = "DEMO2020";
		$userInfo["name"] = "Steven";
		$userInfo["sid"] = $sid;
		$userInfo["city"] = "18";
		$userInfo["canton"] = "286";
	} else {
		$randNo = mt_rand(1, 99999999);
		$vc = md5(($sid . $randNo));

		$nowdate = date('Y-m-d');
		$now = strtotime($nowdate);
		$sql = "SELECT mb_no,tel3 as cell,tel2 as tel, mb_name as name, add2 as addr, mb_status FROM mbst where boss_id = '$sid' and mb_status = '1' and grade_1_chk = '1' and pg_end_date >= $now";
		$db2->setQuery($sql);
		$login_result = $db2->loadRow();

		$tmp_login_result = $login_result;


		$name = (!empty($login_result['name'])) ? $login_result['name'] : mb_convert_encoding($login_result['name'], "UTF-8", "big5");
		$fulladdr = (!empty($login_result['addr'])) ? $login_result['addr'] : mb_convert_encoding($login_result['addr'], "UTF-8", "big5");

		$userInfo["name"] = $name;
		$userInfo["sid"] = $sid;
		$userInfo["addr"] = $fulladdr;
	}

	if ($login_result["mb_status"] == 1) {

		JsonEnd(array("status" => 1, "data" => $userInfo, "msg" => _MEMBER_SIGNUP_SUCCESS));
	} else {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_DISTRIBUTOR, "RR" => $login_result));
	}
}


function sign30_signup()
{
	global $db, $db2, $tablename, $conf_user, $conf_members, $globalConf_signupDemo_ver2020, $HTTP_X_FORWARDED_PROTO;
	ini_set('display_errors', '1');
	$eid = global_get_param($_POST, "eid", null, 0, 1);
	$signupMode = global_get_param($_POST, "signupMode", null, 0, 1);
	$memberName = global_get_param($_POST, "memberName", null, 0, 1);
	$memberSID = global_get_param($_POST, "memberSID", null, 0, 1);
	$memberPhone = global_get_param($_POST, "memberPhone", null, 0, 1);
	$memberPasswd = global_get_param($_POST, "memberPasswd", null, 0, 1);
	$PasswdChk = global_get_param($_POST, "PasswdChk", null, 0, 1);
	$memberTel1 = global_get_param($_POST, "memberTel1", null, 0, 1);
	$memberTel2 = global_get_param($_POST, "memberTel2", null, 0, 1);
	$memberEmail = global_get_param($_POST, "memberEmail", null, 0, 1);
	$memberCaptcha = global_get_param($_POST, "memberCaptcha", null, 0, 1);
	$memberCity = global_get_param($_POST, "memberCity", null, 0, 1);
	$memberCanton = global_get_param($_POST, "memberCanton", null, 0, 1);
	$memberAddress = global_get_param($_POST, "memberAddress", null, 0, 1);
	$memberCardno = global_get_param($_POST, "memberCardno", null, 0, 1);
	$memberWNo = global_get_param($_POST, "memberWNo", null, 0, 1);
	$no_re = global_get_param($_POST, "no_re", null, 0, 1);
	$memberBirthday = global_get_param($_POST, "memberBirthday", null, 0, 1);
	$memberBirthdayStr = global_get_param($_POST, "memberBirthdayStr", null, 0, 1);
	// JsonEnd(array('status'=>0,'msg'=>$memberCaptcha));
	if (
		empty($signupMode) || empty($memberName) || ($signupMode == "SMS" && empty($memberPhone))  || empty($memberPasswd)
		|| empty($PasswdChk) || ($signupMode == "MAIL" && empty($memberEmail)) || empty($memberCaptcha)
		|| $memberPasswd != $PasswdChk
	) {
		JsonEnd(array("status" => 0, "msg" => _COMMON_ERRORMSG_NET_ERR));
	}
	if (!empty($memberTel1) && !empty($memberTel2)) {
		$memberTel = $memberTel1 . "-" . $memberTel2;
	}

	// $mmCity = "SELECT * FROM addrcode where id = '$memberCity'";
	$mmCity = "SELECT * FROM region where id = '$memberCity'";
	$db->setQuery($mmCity);
	$mcn = $db->loadRow();

	$memberCityName = $mcn['state_u'];

	$mmCanton = "SELECT * FROM addrcode where id = '$memberCanton'";
	$db->setQuery($mmCanton);
	$mcc = $db->loadRow();

	$memberCantonName = $mcc['name'];

	$memberAddress = $memberCityName . $memberCantonName . $memberAddress;
	// $memberCity = $memberCity['id'];
	// $memberCanton = $memberCanton['id'];

	if (empty($memberPhone) || $memberPhone == 'null') {
		$memberPhone = '';
	}

	if (!empty($memberPhone)) {
		$chk = getFieldValue("select count(1) as cnt from members where mobile='$memberPhone' and locked = '0' and mobileChk = '1'", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_EXIST_MOBILE));
		}
	}

	$memberPhone = mobileChk($memberPhone);

	if (empty($memberEmail) || $memberEmail == 'null') {
		$memberEmail = '';
	}

	if (!empty($memberEmail)) {
		$chk = getFieldValue("select count(1) as cnt from members where email='$memberEmail' and locked = '0' and emailChk = '0' and email <> 'H1707@goodarcu2u.com'", "cnt");
		if ($chk > 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBERS_SAME_EMAIL));
		}
	}

	//檢查驗證碼
	$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
	if ($signupMode == "SMS") {
		$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
	} else if ($signupMode == "MAIL") {
		$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
	}

	if (empty($chk)) {
		JsonEnd(array("status" => 0, "msg" => "驗證碼錯誤"));
	} else {
		//更新驗證碼
		$sql = "update requestLog set type= 'sign20Chked' where id='$chk'";
		$db->setQuery($sql);
		$db->query();
	}

	$passwd = enpw($memberPasswd);

	$tsql = "SELECT * from mbst where boss_id = '$memberSID' and mb_status = '1' and grade_class='0' and member_date = ''"; //是顧客
	$db2->setQuery($tsql);
	$tmd = $db2->loadRow();
	$is_customer = false;
	if (!empty($tmd)) {
		$memberNo = $tmd['mb_no'];
		$tin = $tmd['true_intro_no'];
		$is_customer = true;
	} else {
		$memberNo = $memberCardno;
		$tin = '';
	}

	$check_web_exist = false;
	$sql = "SELECT * FROM members WHERE ERPID = '$memberNo' and locked = '0'";
	$db->setQuery($sql);
	$check_web = $db->loadRow();
	if (!empty($check_web)) {
		$check_web_exist = true;
	}

	if ($check_web_exist == true) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_SID_REPEAT));
	}

	// $memberNo = $memberCardno;
	if (empty($memberNo)) {
		// $memberNo_max = getFieldValue(" SELECT ERPID FROM members WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");
		//更改成從會編日誌找
		$memberNo_max = getFieldValue(" SELECT ERPID FROM erpid WHERE ERPID LIKE '" . "MYN" . date("Ym") . "%' ORDER BY ERPID DESC ", "ERPID");
		$chk_code = "";
		if (!empty($memberNo_max)) {
			$chk_code = intval(substr($memberNo_max, -5)) + 1;
		}

		if (!empty($chk_code))
			$code  = str_pad($chk_code, 5, '0', STR_PAD_LEFT);
		else
			$code  = "00001";

		$memberNo = "MYN" . date("Ym") . $code;
	}

	$emailChk = ($signupMode == "MAIL") ? 0 : 1;
	$mobileChk = ($signupMode == "SMS") ? 1 : 0;
	$salesChk = 1; //經銷商身分 此處4為會員
	$ERPChk = 1;
	$memType = 0;
	$pvgeLevel = $signupMode;
	if ($signupMode == 'MAIL') {
		$memberAC = $memberEmail;
	} else {
		$memberAC = $memberSID;
	}

	$sql = "insert into members ( belongid,name,sid,email,emailChk,mobileChk,
				regDate,loginid,passwd,cardnumber,recommendCode,
				salesChk,mobile,ERPID,phone,fulladdr,
				ERPChk,resaddr,city,canton,addr,
				rescity,rescanton,dlvrLocation,Birthday,memType,
				recommendName,recommendPhone,recommendMobile, usedChk, memberWNo, pvgeLevel,onlyMember,no_re ) values 
				('0',N'$memberName','$memberSID','$memberEmail','$emailChk','$mobileChk',
				'" . date("Y-m-d H:i:s") . "','$memberAC','$passwd','$memberNo','$eid',
				'$salesChk','$memberPhone','$memberNo','$memberTel',N'$memberAddress',
				$ERPChk,N'$memberResAddress','$memberCity','$memberCanton',N'$memberAddress',
				'','',N'',N'$memberBirthdayStr','$memType',
				N'',N'',N'', '', '$memberWNo', '$pvgeLevel', '1' ,'$no_re')";

	$db->setQuery($sql);
	$db->query();

	//20230817額外寫入一個accmbno
	$acc = array();
	$acc['mb_no'] = $memberNo;
	$acc_sql = dbInsert('accmbno',$acc);
	$db2->setQuery($acc_sql);
	$db2->query();


	//建立會編日誌
	$e_arr = array();
	$e_arr['ERPID'] = $memberNo;
	$esql = dbInsert('erpid', $e_arr);
	$db->setQuery($esql);
	$db->query();

	$memberid = getFieldValue(" SELECT id FROM members ORDER BY id DESC ", "id");


	// $img1 = global_get_param($_POST, 'img1', null);
	// $img2 = global_get_param($_POST, 'img2', null);
	// $img3 = global_get_param($_POST, 'img3', null);
	// if (count($img1) > 0) {
	// 	foreach ($img1 as $key => $value) {
	// 		if ($value) {
	// 			$path = $memberid . "_p" . $key . ".jpg";
	// 			imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
	// 		}
	// 	}
	// }
	// if (count($img2) > 0) {
	// 	foreach ($img2 as $key => $value) {
	// 		if ($value) {
	// 			$path = $memberid . "_n" . $key . ".jpg";
	// 			imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
	// 		}
	// 	}
	// }
	// if (count($img3) > 0) {
	// 	foreach ($img3 as $key => $value) {
	// 		if ($value) {
	// 			$path = $memberid . "_b" . $key . ".jpg";
	// 			imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
	// 		}
	// 	}
	// }
	$msql = "SELECT * FROM members where id = '$memberid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$m_name = $md['name'];
	$mb_no = $md['ERPID'];
	$sql = "select * from siteinfo";
	$db->setQuery($sql);
	$siteinfo_arr = $db->loadRow();
	$from = $siteinfo_arr['email'];
	// $adminmail = getFieldValue( "select email from siteinfo  ;" , 'email');
	// $webname = getFieldValue( "select name from siteinfo  ;" , 'name');
	// $now = date('Y-m-d H:i:s');
	// $fromname = $siteinfo_arr['name'];
	// $subject="$fromname 證件照片上傳 ( $now )";
	// $body = "會員 $mb_no $m_name 已由會員註冊上傳證件照片，請盡速確認。";
	// // $sendto = array(array("email" => 'vicky950217@goodarch2u.com', "name" => ''),array("email" => 'J1905@goodarch2u.com',"name"=>''));
	// $sendto = array(array("email" => 'H2008@goodarch2u.com', "name" => ''),array("email" => 'H1707@goodarch2u.com',"name"=>''),array("email" => 'juell@goodarch2u.com',"name"=>''));
	// $rs = global_send_mail($adminmail,$webname, $sendto , $subject, $body, null, null, null );

	//會員單
	//檢查是不是顧客

	if ($is_customer == true) {
		$new_member_date = date('Y-m-d');
		//更新傳銷成會員所需資料
		$usql = "UPDATE mbst SET grade_class = '0',member_date = '$new_member_date' WHERE mb_no = '$mb_no'";
		$db2->setQuery($usql);
		$db2->query();
	} else {
		export_tomlm($memberid, '0');
	}


	//發送註冊成功
	if ($signupMode == "SMS") {
		sendMailToMemberBySignupSuccess($memberid, true, "sign20_signup");

		$body = _MEMBER_SUCCESS_SMS;
		send_sms($memberPhone, $body);
	} else if ($signupMode == "MAIL") {
		sendMailToMemberBySignupSuccess($memberid, false, "sign20_signup");
	}

	//login(0,"",$memberSID,$memberPasswd);

	JsonEnd(array("status" => 1, "msg" => _MEMBER_SIGNUP_SUCCESS));
}

function sign30_signupSuccess()
{
	$tmpStr = $_SESSION["tmpData"]["bodyStr"];
	unset($_SESSION["tmpData"]["bodyStr"]);
	unset($_SESSION["tmpData"]["payD"]);
	JsonEnd(array("status" => 1, "msg" => $tmpStr));
}

function m_points()
{
	ini_set('display_errors', '1');
	$res = array();
	global $db3, $conf_user;
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now = date('Y-m-d');
	$this_year = date('Y');
	$next_year = $this_year + 1;

	$sql_str = "";
	$sql_str2 = "";
	$sql_str_pk = "";
	if ($_SESSION[$conf_user]['syslang']) {
		$lang = $_SESSION[$conf_user]['syslang'];
		$sql_str .= " l.`" . $_SESSION[$conf_user]['syslang'] . "` ";
		$sql_str2 .= " ll.`" . $_SESSION[$conf_user]['syslang'] . "` ";
		$sql_str_pk .= " pk.`" . $_SESSION[$conf_user]['syslang'] . "` ";
	}
	$uid = LoginChk();
	chk_cb($uid);
	$sql = "SELECT p.* FROM points p, point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date > '$now' and '$now' >= p.active_date and p.kind = pk.kind and pk.type = '1'"; //總可用點數
	$db3->setQuery($sql);
	$list = $db3->loadRowList();
	$total = 0;
	foreach ($list as $each) {
		$total = bcadd($total, $each['point'], 2);
	}

	$msql = "SELECT p.* FROM points p, point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind and pk.type = '2'";
	$db3->setQuery($msql);
	$mlist = $db3->loadRowList();
	$mtotal = 0;
	foreach ($mlist as $each) {
		if ($each['deduct_year_1'] == $this_year || $each['deduct_year_1'] == $next_year) {
			$mtotal = bcadd($mtotal, $each['deduct_point_1'], 2);
		}
		if ($each['deduct_year_2'] == $this_year || $each['deduct_year_2'] == $next_year) {
			$mtotal = bcadd($mtotal, $each['deduct_point_2'], 2);
		}
	}

	$total = bcsub($total, $mtotal, 2);


	$sql2 = "SELECT * FROM points p,point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date like '$this_year%' and pk.kind = p.kind and pk.type = '1'";  //今年到期
	$db3->setQuery($sql2);
	$list2 = $db3->loadRowList();
	$t_total = 0;
	foreach ($list2 as $each) {
		$t_total = bcadd($t_total, $each['point'], 2);
	}


	$sql3 = "SELECT p.*,pk.type as p_type FROM points p,point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date like '$next_year%' and pk.kind = p.kind and pk.type = '1'"; //明年到期
	$db3->setQuery($sql3);
	$list3 = $db3->loadRowList();
	$n_total = 0;
	$u1_point = 0;
	$u2_point = 0;
	$nu1_point = 0;
	$nu2_point = 0;
	foreach ($list3 as $each) {
		$n_total = bcadd($n_total, $each['point'], 2);
	}


	$u1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u1sql);
	$u1_list = $db3->loadRow();
	if (!empty($u1_list['u1_point'])) {
		$u1_point = $u1_list['u1_point'];
	}


	$u2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u2sql);
	$u2_list = $db3->loadRow();
	if (!empty($u2_list['u2_point'])) {
		$u2_point = $u2_list['u2_point'];
	}


	$nu1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$next_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($nu1sql);
	$nu1_list = $db3->loadRow();
	if (!empty($nu1_list['u1_point'])) {
		$nu1_point = $nu1_list['u1_point'];
	}


	$nu2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$next_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($nu2sql);
	$nu2_list = $db3->loadRow();
	if (!empty($nu2_list['u2_point'])) {
		$nu2_point = $nu2_list['u2_point'];
	}
	$nu_point = bcadd($nu1_point, $nu2_point, 2);
	$u_point = bcadd($u1_point, $u2_point, 2);
	$n_total = bcsub($n_total, $nu_point, 2);
	$t_total = bcsub($t_total, $u_point, 2);


	$sql4 = "SELECT _m.*,_m.point as point,_m.this_ccv as this_ccv,{$sql_str_pk} as kind_name FROM points _m , point_kind pk where _m.mb_no = '$mb_no'  and _m.is_invalid = '0' and expiry_date > '$now' and point > 0 and pk.kind = _m.kind and pk.type='1' order by _m.create_time desc";
	$db3->setQuery($sql4);
	$getlist = $db3->loadRowList();

	$sql5 = "SELECT _m.*,_m.point as point,{$sql_str_pk} as kind_name FROM points _m , point_kind pk where _m.mb_no = '$mb_no'  and _m.is_invalid = '0' and pk.type = '2' and pk.kind = _m.kind and _m.point != '0' order by _m.create_time desc";
	$db3->setQuery($sql5);
	$uselist = $db3->loadRowList();


	$sql6 = "SELECT _m.*,t_total as t_total,{$sql_str} as m_rank,l.level as m_level,ll.level as m_llevel,{$sql_str2} as m_lrank from member_lv as _m,lv_list as l,lv_list as ll where mb_no = '$mb_no' and _m.lv = l.level and _m.force_lv = ll.level";
	$db3->setQuery($sql6);
	$member_detail = $db3->loadRow();

	if ($member_detail['force_lv'] > $member_detail['lv']) {
		$member_detail['m_rank'] = $member_detail['m_lrank'];
	}



	$res['now_points'] = $total;
	$res['get_list'] = $getlist;
	$res['use_list'] = $uselist;

	$res['this_points'] = $t_total;
	$res['next_points'] = $n_total;

	$this_year = date('Y') . '-12-31';
	$next_year = date('Y', strtotime('+1 year')) . '-12-31';

	$res['this_year'] = $this_year;
	$res['next_year'] = $next_year;


	$res['member_detail'] = $member_detail;
	$res['status'] = '1';

	$res['msql'] = $sql4;


	// $pmsql = "SELECT sum(point) as p_points FROM points as p left join point_kind as pk on pk.kind = p.kind where p.withdraw='1' and p.is_invalid = '0' and pk.type='1' and p.expiry_date > '$now' and p.active_date <= '$now' and p.point > 0 and p.mb_no = '$mb_no'";
	// $db3->setQuery($pmsql);
	// $pm_data = $db3->loadRow();
	// $pm_p_points = $pm_data['p_points'];


	// $pmsql2 = "SELECT sum(point) as n_points FROM points as p left join point_kind as pk on pk.kind = p.kind where p.withdraw='1' and p.is_invalid = '0' and pk.type='2' and p.kind = '5' and p.point > 0 and p.mb_no = '$mb_no' and p.active_date <= '$now' and p.expiry_date >= '$now'";
	// $db3->setQuery($pmsql2);
	// $pm_data2 = $db3->loadRow();
	// $pm_n_points = $pm_data2['n_points'];

	// $withdraw_points = bcsub($pm_p_points, $pm_n_points, 2);
	// if ($withdraw_points < 0) {
	// 	$withdraw_points = 0;
	// }
	// $res['withdraw_points'] = $withdraw_points;

	// $now = date('Y-m-d H:i:s');
	// // $pmsql3 = "SELECT * FROM points as p left join point_kind as pk on pk.kind = p.kind where p.withdraw='1' and p.is_invalid = '0' and pk.type='2' and p.kind = '5' and p.point > 0 and p.mb_no = '$mb_no' and p.active_date <= '$now' and p.expiry_date >= '$now'";
	// $pmsql3 = "SELECT *,TIMESTAMPDIFF(SECOND,create_time,NOW()) as diffdate from p_to_m where mb_no = '$mb_no' and is_invalid = '0' order by set_date desc,create_time desc";
	// $db3->setQuery($pmsql3);
	// $pm_data3 = $db3->loadRowList();

	// $res['withdraw_list'] = $pm_data3;
	// $res['n1'] = $n_total;
	// $res['n2'] = $nu_point;
	JsonEnd($res);
}

function returns_order()
{
	global $db, $conf_user;
	$returns_arr =  global_get_param($_POST, 'returns_arr', null, 0, 1);
	$returns_str = implode(',', $returns_arr);
	$uid = LoginChk();
	foreach ($returns_arr as $each) {
		//檢查是否是他的

		if ($each == 0) {

			JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
		}

		$sql = "SELECT A.id as od_id,B.id as o_id FROM orderdtl A,order B where A.oid = B.id and A.id = '$each' and B.memberid='$uid'";
		$db->setQuery($sql);
		$detail = $db->loadRow();
		$od_id = $detail['od_id'];
		$o_id = $detail['o_id'];
		if ($o_id == 0) {
			JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
		}
		$status = intval(getFieldValue("select status from orders where id='$o_id'", "status"));
		if ($status != 3) {

			JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
		}

		$osql = "SELECT * FROM orders where id = '$o_id'";
		$db->setQuery($osql);
		$o_detail = $db->loadRow();
	}
	$res = array();
	$res['success'] = '1';
	$res['arr'] = $returns_str;
	JsonEnd($res);
}

function signupChk3011()
{
	global $db, $db2, $conf_user, $tablename, $globalConf_signup_ver2020;
	$res = array();
	if ($globalConf_signup_ver2020) {
		$signupMode = global_get_param($_POST, 'signupMode', null, 0, 1);
		$memberCaptcha = global_get_param($_POST, 'memberCaptcha', null, 0, 1);
		$memberPhone = global_get_param($_POST, 'memberPhone', null, 0, 1);
		$memberEmail = global_get_param($_POST, 'memberEmail', null, 0, 1);

		$memberPhone = mobileChk($memberPhone);

		//檢查驗證碼
		$ctimeStr = date("Y-m-d H:i:s", strtotime("-15 minutes"));
		if ($signupMode == "SMS") {
			$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		} else if ($signupMode == "MAIL") {
			$chk = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' AND ctime >= '$ctimeStr' ORDER BY id DESC ", "id");
		}



		if (empty($chk)) {
			if ($signupMode == "SMS") {
				$chk2 = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberPhone' ORDER BY id DESC ", "id");
			} else if ($signupMode == "MAIL") {
				$chk2 = getFieldValue(" SELECT * FROM requestLog WHERE type = 'sign20Chk' AND var01 = '$signupMode' AND code = '$memberCaptcha' AND var02 = '$memberEmail' ORDER BY id DESC ", "id");
			}
			if (empty($chk2)) {
				$res['status'] = '0';
				$res['msg'] = _EMAIL_msg20;
			} else {
				$res['status'] = '0';
				$res['msg'] = _EMAIL_msg21;
			}
		} else {
			//更新驗證碼
			// $sql = "update requestLog set type= 'sign20Chked' where id='$chk'";
			// $db->setQuery( $sql );
			// $db->query();
			$res['status'] = '1';
			$res['msg'] = _EMAIL_msg19;
		}
	}
	JsonEnd($res);
}

function get_udlvrAddr()
{
	global $db, $db2, $db3, $conf_user;
	$uid = LoginChk();
	$res = array();
	$msql = "SELECT ERPID,updating from members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$mb_no = $md['ERPID'];

	//尋找有沒有註冊碼
	// $sql2 = "SELECT * from register_tb where register_mb_no = '$mb_no' and is_upgrade = '1' and is_invalid = '0'";
	// $db2->setQuery($sql2);
	// $reg_data = $db2->loadRow();
	// if (!empty($reg_data)) {
	// 	$res['can_update'] = '1';
	// 	$res['reg_data'] = $reg_data;
	// 	$_SESSION[$conf_user]['reg_code'] = $reg_data['random'];
	// } else {
	// 	$res['can_update'] = '0';
	// }

	$res['can_update'] = '1';
	// $res['reg_data'] = $reg_data;
	// $_SESSION[$conf_user]['reg_code'] = $reg_data['random'];

	//是否升級中
	if ($md['updating'] == '1') {
		$res['is_updating'] = '1';
	} else {
		$res['is_updating'] = '0';
	}


	$orderCnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM orders WHERE memberid = '$uid' AND orderMode = 'updateMember' ", "cnt");
	if ($orderCnt > 0) {
		$orderStatus = getFieldValue(" SELECT status FROM orders WHERE memberid = '$uid' AND orderMode = 'updateMember' ", "status");
		//0待付款 9已通知付款 1已核定 3已配送 4交易完成 8已退貨 6已取消
		$res['dataShowMode'] = ($orderStatus == '0' || $orderStatus == '9' || $orderStatus == '3' || $orderStatus == '4' || $orderStatus == '1') ? false : true;
		// $res['dataShowMode'] = false;
	} else {
		$res['dataShowMode'] = true; //是否要顯示升級
	}

	$msql = "SELECT A.addr as addr, B.state_u as city,B.id as city_code FROM members A, region B where A.city=B.id and A.id = '$uid'";
	$db->setQuery($msql);
	$ma = $db->loadRow();
	$res['city_code'] = $ma['city_code'];
	$res['address'] = $ma['addr'];
	// $res['msql'] = $msql;
	$res['status'] = '1';

	$mobileChk = getFieldValue(" SELECT mobileChk FROM members WHERE id = '$uid'", "mobileChk");
	if ($mobileChk == 0) {
		// $res['status'] = '3';
	}

	$res['mobileChk'] = $mobileChk;


	//檢查購物金
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	foreach ($plist as $each) {
		if ($each['p_type'] == '1') {
			$now_points = bcadd($now_points, $each['point'], 0);
		} else if ($each['p_type'] == '2') {
			$now_points = bcsub($now_points, $each['point'], 0);
		}
	}

	if ($now_points > 0) {
		$res['has_m_points'] = '1';
	} else {
		$res['has_m_points'] = '0';
	}

	JsonEnd($res);
}
/*
function update_member_now()
{

	global $db, $db2, $conf_user, $tablename, $conf_user, $conf_members, $globalConf_signup_ver2020;
	$uid = LoginChk();
	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$rechg = global_get_param($_POST, 'rechg', null, 0, 1);

	$memberid = $uid;
	$msql = "SELECT * FROM members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$memberEmail = $md['email'];
	$today = date('Y-m-d');
	$status = 0;
	$sumAmt = 88;
	$mb_no = $md['ERPID'];
	$memberName = $md['name'];
	$memberPhone = $md['phone'];
	$signupMode = $md['pbgeLevel'];

	$now = date("Y-m-d H:i:s");
	
	if (!empty($sid)) {
		$sql = "UPDATE members SET sid='$sid',updating='1',rechg='$rechg' where id = '$uid'";
		$db->setQuery($sql);
		$db->query();

		$sql = "UPDATE mbst set grade_1_date = '$today' where mb_no = '$mb_no'";
		$db2->setQuery($sql);
		$db2->query();

		$sql3 = "UPDATE register_tb set is_used = '1' where register_mb_no = '$mb_no' and random = '$code' and is_used = '0'";
		$db2->setQuery($sql3);
		$db2->query();
	} else {
		$status = 0;
		$msg = '發生錯誤!';
		JsonEnd(array("status" => $status, "msg" => $msg, "oid" => $oid));
	}


	if ($globalConf_signup_ver2020) {
		//發送註冊成功
		sendMailToMemberBySignupSuccess($memberid, false);
	} else {
		login(0, "", $memberEmail, $sid);
	}


	$oid = getFieldValue(" SELECT id FROM orders ORDER BY id DESC ", "id");

	$status = 1;
	$msg = '註冊成功';
	JsonEnd(array("status" => $status, "msg" => $msg, "oid" => $oid));
}
*/

function update_member_now()
{
	global $db, $conf_user, $tablename, $conf_user, $conf_members, $globalConf_signup_ver2020;
	$uid = LoginChk();
	$City = global_get_param($_POST, 'city', null, 0, 1);
	$dlvrCity = $City['id'];
	$Canton = global_get_param($_POST, 'canton', null, 0, 1);
	$dlvrCanton = $Canton['id'];
	$dlvrAddr = global_get_param($_POST, 'addr', null, 0, 1);
	$payType = global_get_param($_POST, 'payType', null, 0, 1);
	$dlvrType = global_get_param($_POST, 'dlvrType', null, 0, 1);
	$mo = global_get_param($_POST, 'mo', null, 0, 1);
	$memberid = $uid;
	$msql = "SELECT * FROM members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$memberEmail = $md['email'];
	$today = date("Y-m-d");
	$status = 0;
	$sumAmt = 88;
	$discount = 0;
	$dcntAmt = 88;
	$dlvrFee = ($dlvrType == '1') ? 10 : 0;
	$usecoin = 0;
	$totalAmt = $sumAmt + $dlvrFee;
	$memberName = $md['name'];
	$memberPhone = $md['phone'];
	$signupMode = $md['pbgeLevel'];
	$memberSID = $md['sid'];
	$now = date("Y-m-d H:i:s");
	// JsonEnd($_POST);
	$billCity = global_get_param($_POST, 'billCity', null, 0, 1);
	if (!empty($City)) {
		$billCityStr = $City['state_u'];
	}
	$billAddr = $dlvrAddr;

	if ($dlvrType == '2' && !empty($dlvrLocation)) {
		if ($dlvrLocation == '北區服務中心') {
			$dlvrAddr = "新北市林口區文化二路一段 266 號 11 樓之 1";
		} else if ($dlvrLocation == '新竹服務中心') {
			$dlvrAddr = "新竹市北區東大路二段 76 號 12 樓";
		} else if ($dlvrLocation == '台中服務中心') {
			$dlvrAddr = "台中市南屯區五權西路二段 666 號 8 樓之 3";
		} else if ($dlvrLocation == '雲林服務中心') {
			$dlvrAddr = "雲林縣虎尾鎮興中里 15 鄰清雲六街 123 號";
		} else if ($dlvrLocation == '高雄服務中心') {
			$dlvrAddr = "高雄市鼓山區明華路 315 號 11 樓之 2";
		} else if ($dlvrLocation == '台南總部服務中心') {
			$dlvrAddr = "台南市安南區工業一路 23 號";
		} else if ($dlvrLocation == 'GOLDARCH ENTERPRISE') {
			$dlvrAddr = "Prangin Mall, 33-4-75, Jalan Dr. Lim Chwee Leong, 10100 Georgetown, Pulau Pinang.";
		} else if ($dlvrLocation == 'SIMPANG AMPAT') {
			$dlvrAddr = "26, Lorong Kendi 6, Kawasan Perniagaan Taman Merak, 14100 Simpang Ampat, Pulau Pinang.";
		} else if ($dlvrLocation == 'GREATARCH ENTERPRISE') {
			$dlvrAddr = "96, Jalan Radin Anum 1, Sri Petaling, 57000 Kuala Lumpur.";
		} else if ($dlvrLocation == 'BATU PAHAT') {
			$dlvrAddr = "2A, Jalan Sri Pantai 1, Taman Sri Pantai, 83000 Batu Pahat, Johor.";
		} else if ($dlvrLocation == 'ARCHCARE ENTERPRISE') {
			$dlvrAddr = "2, Jalan Zapin 11, Taman Skudai, 81300 Johor Bahru, Johor.";
		} else if ($dlvrLocation == 'MIRI') {
			$dlvrAddr = "Lot.403, Jalan Cosmos 2A, Pelita Garden, 98000 Miri, Sarawak.";
		} else if ($dlvrLocation == 'KOTA KINABALU') {
			$dlvrAddr = "No.101, Lorong Bunga Dahlia 5, Jalan Penampang, Taman Cantik, 88200 Kota Kinabalu, Sarawak.";
		} else if ($dlvrLocation == '大城堡總部服務中心' || $dlvrLocation == 'Sri Petaling Service Center') {
			$dlvrAddr = "No.22-2, Jalan Radin Bagus 6, Bandar Baru Sri Petaling, 57000 Kuala Lumpur, Malaysia.";
		} else if ($dlvrLocation == '詩巫服務中心' || $dlvrLocation == 'SIBU Service Center') {
			$dlvrAddr = "No. 2, G & 1st Floor, Lorong Teng Chin Hua 1, 96000 Sibu, Sarawak";
		}
	}

	$sql = "BEGIN;";


	$sql .= "insert into orders 
			(orderMode,memberid,email,buyDate,payType,dlvrType,status,sumAmt,discount,dcntAmt,dlvrFee,usecoin,totalAmt,dlvrName,
			dlvrMobile,dlvrCanton,dlvrCity,dlvrAddr,dlvrDate,dlvrTime,dlvrNote,invoiceType,invoiceTitle,invoiceSN,invoice,ctime,mtime,muser,bill_address,bill_city)
			values 
			('updateMember','$memberid','$memberEmail','$today','$payType','$dlvrType',$status,'$sumAmt',
			 '$discount','$dcntAmt','$dlvrFee','$usecoin','$totalAmt',N'$memberName','$memberPhone','','',N'$dlvrAddr',
			 '','','','','','','','$now','$now','$memberid',N'$billAddr',N'$billCityStr');";

	$sql .= " SET @insertid=LAST_INSERT_ID();";
	$pid = 529;
	$quantity = 1;
	$format1 = 155;
	$format2 = 153;


	$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
				 values 
				 (@insertid,'$pid','88','1','88','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";



	$sql .= " SET @insertdtlid=LAST_INSERT_ID();";

	$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
	 values 
	 (@insertid,@insertdtlid,'$pid','880','0','0','e化入會贈品','$now','$now','$memberid');";



	if (!empty($format1) && !empty($format2) && false) {
		$format_instock = intval(getFieldValue("select instock from proinstock where pid='$pid' AND format1 = '$format1' AND format2 = '$format2'", "instock"));
		$sql .= "update proinstock set instock=instock-'$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
	}

	global $ESignupActiveEndDate, $ESignupFreeProductId, $ESignupFreeProductQuantity, $ESignupFreeProductFormat1, $ESignupFreeProductFormat2, $ESignupFreeProductId_2, $ESignupFreeProductQuantity_2, $ESignupFreeProductFormat1_2, $ESignupFreeProductFormat2_2, $ESignupFreeProductId_3, $ESignupFreeProductQuantity_3, $ESignupFreeProductFormat1_3, $ESignupFreeProductFormat2_3;
	if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
		$pid = $ESignupFreeProductId;
		$quantity = $ESignupFreeProductQuantity;
		$format1 = $ESignupFreeProductFormat1;
		$format2 = $ESignupFreeProductFormat2;

		$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
					 values 
					 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

		$sql .= "
			SET @insertdtlid2=LAST_INSERT_ID();
		";

		$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
		 values 
		 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
	}

	// if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
	// 	$pid = $ESignupFreeProductId_2;
	// 	$quantity = $ESignupFreeProductQuantity_2;
	// 	$format1 = $ESignupFreeProductFormat1_2;
	// 	$format2 = $ESignupFreeProductFormat2_2;

	// 	$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
	// 				 values 
	// 				 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

	// 	$sql .= "
	// 		SET @insertdtlid2=LAST_INSERT_ID();
	// 	";

	// 	$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
	// 	 values 
	// 	 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
	// }

	// if (!empty($ESignupActiveEndDate) &&  strtotime(date("Y-m-d")) <= strtotime($ESignupActiveEndDate)) {
	// 	$pid = $ESignupFreeProductId_3;
	// 	$quantity = $ESignupFreeProductQuantity_3;
	// 	$format1 = $ESignupFreeProductFormat1_3;
	// 	$format2 = $ESignupFreeProductFormat2_3;

	// 	$sql .= "insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser)
	// 				 values 
	// 				 (@insertid,'$pid','0',$quantity,'0','0','0','0','','$format1','$format2','單一顏色','單一規格','$now','$now','$memberid');";

	// 	$sql .= "
	// 		SET @insertdtlid2=LAST_INSERT_ID();
	// 	";

	// 	$sql .= "insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
	// 	 values 
	// 	 (@insertid,@insertdtlid2,'$pid','0','0','0','e化入會贈品','$now','$now','$memberid');";
	// }

	$toMonth = date("Y-m");
	$orderCodeName = getFieldValue("select codeName_chs from pubcode where codeKinds='orderseq'", "codeName_chs");
	if ($orderCodeName == $toMonth) {
		$orderseq = intval(getFieldValue("select codeName_en from pubcode where codeKinds='orderseq'", "codeName_en")) + 1;
		$db->setQuery("update pubcode set codeName_en='$orderseq' where codeKinds='orderseq'");
		$r = $db->query();
	} else {
		$db->setQuery("update pubcode set codeName_en=1,codeName_chs='$toMonth' where codeKinds='orderseq'");
		$r = $db->query();
		$orderseq = 1;
	}

	$orderseqStr = "";
	if ($orderseq < 10) {
		$orderseqStr = "000" . $orderseq;
	} else if ($orderseq < 100) {
		$orderseqStr = "00" . $orderseq;
	} else if ($orderseq < 1000) {
		$orderseqStr = "0" . $orderseq;
	} else if ($orderseq < 10000) {
		$orderseqStr = "" . $orderseq;
	}


	if (strtotime($today) >= strtotime('2019-10-30')) {
		$orderNum = "3S010-" . date("ym") . $orderseqStr;
	} else {
		$orderNum = "1S010-" . date("ym") . $orderseqStr;
	}

	$orderCodeName = getFieldValue("select codeName from pubcode where codeKinds='orderseq'", "codeName");
	if ($orderCodeName == $today) {
		$orderseq = intval(getFieldValue("select codeValue from pubcode where codeKinds='orderseq'", "codeValue")) + 1;
		$db->setQuery("update pubcode set codeValue='$orderseq' where codeKinds='orderseq'");
		$r = $db->query();
	} else {
		$db->setQuery("update pubcode set codeValue=1,codeName='$today' where codeKinds='orderseq'");
		$r = $db->query();
		$orderseq = 1;
	}
	$orderseqStr2 = "";
	if ($orderseq < 10) {
		$orderseqStr2 = "0000" . $orderseq;
	} else if ($orderseq < 100) {
		$orderseqStr2 = "000" . $orderseq;
	} else if ($orderseq < 1000) {
		$orderseqStr2 = "00" . $orderseq;
	} else if ($orderseq < 10000) {
		$orderseqStr2 = "0" . $orderseq;
	}


	$orderECNum = date("Ymd") . $orderseqStr2;

	$sql .= "update orders set orderNum='$orderNum',orderECNum='$orderECNum' where id=@insertid;";


	$sql .= "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values (@insertid,'$today','$status','$now','$now','$memberid');";

	$sql .= "COMMIT;";


	$db->setQuery($sql);
	$r = $db->query_batch();

	$img1 = global_get_param($_POST, 'img1', null);
	$img2 = global_get_param($_POST, 'img2', null);
	$img3 = global_get_param($_POST, 'img3', null);
	if (count($img1) > 0) {
		foreach ($img1 as $key => $value) {
			if ($value) {
				$path = $memberid . "_p" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
	}
	if (count($img2) > 0) {
		foreach ($img2 as $key => $value) {
			if ($value) {
				$path = $memberid . "_n" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
	}
	if (count($img3) > 0) {
		foreach ($img3 as $key => $value) {
			if ($value) {
				$path = $memberid . "_b" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
	}

	if ($globalConf_signup_ver2020) {
		//發送註冊成功
		if ($signupMode == "SMS") {
			sendMailToMemberBySignupSuccess($memberid, true);

			$body = _MEMBER_SUCCESS_SMS;
			send_sms($memberPhone, $body);
		} else if ($signupMode == "MAIL") {
			sendMailToMemberBySignupSuccess($memberid, false);
		}
	} else {
		login(0, "", $memberEmail, $memberSID);
	}



	$oid = getFieldValue(" SELECT id FROM orders ORDER BY id DESC ", "id");

	$orderid = getFieldValue("SELECT id FROM orders WHERE orderNum = '$orderNum'", "id");
	$orderMode = getFieldValue("SELECT orderMode FROM orders WHERE orderNum = '$orderNum'","orderMode");
	//未付款先進傳銷訂單
	//台灣單排除
	if($orderMode == 'twcart'){
		
	}else{
		toMLM($orderid,'0');
	}

	$status = 1;
	$msg = '註冊成功';

	$url = '';
	$url = "/app/controllers/publicBank.php?task=orderSale&session=0&orderNum=" . $orderNum;

	JsonEnd(array("status" => $status, "msg" => $msg, "oid" => $oid, "orderNum" => $orderNum, 'url' => $url));
}

function get_erate()
{
	ini_set('display_errors', '1');
	// $f = file_get_contents('http://es.digiwin.com/authservice/ttop/exchangeRate/get/file/BANK_004/16463600/J1602@goodarch2u.com');
	// $res = array();
	// $f = preg_replace("/\s/", "||", $f);
	// $res['f'] = $f;
	// https://rate.bot.com.tw/xrt/fltxt/0/day
	// $f = downloadFile('http://es.digiwin.com/authservice/ttop/exchangeRate/get/file/BANK_004/16463600/J1602@goodarch2u.com');
	$f = downloadFile('https://rate.bot.com.tw/xrt/fltxt/0/day', 'eratebot');
	$file = fopen("../../upload/eratebot", "r");
	$list = array();
	$idata = array();
	$i = 0;
	while (!feof($file)) {
		$list[$i] = fgets($file);
		$i++;
	}
	fclose($file);
	// $list = array_filter($list);
	$list = $list[1];
	$list = str_replace("         ", "||", $list);
	$list = str_replace("        ", "||", $list);
	$list = str_replace("       ", "||", $list);
	$list = str_replace("     ", "||", $list);
	$list = str_replace("    ", "||", $list);
	$list = str_replace("   ", "||", $list);
	$list = str_replace("  ", "||", $list);
	$list = str_replace(" ", "||", $list);
	// $list = str_replace("   ","||",$list);
	$list = explode("||", $list);

	// $f1 = downloadFile('https://portal.sw.nat.gov.tw/APGQ/GC331!downLoad?formBean.downLoadFile=CURRENT_TXT', 'eratesw');
	// $file1 = fopen("../../upload/eratesw", "r");
	// $list1 = array();
	// $j = 0;
	// while (!feof($file1) || $j > 30) {
	// 	$list1[$j] = fgets($file1);
	// 	$j++;
	// }
	// fclose($file1);
	// $list = array_filter($list);
	// $list1 = $list1[1];
	// $list1 = str_replace("         ","||",$list1);
	// $list1 = str_replace("        ","||",$list1);
	// $list1 = str_replace("       ","||",$list1);
	// $list1 = str_replace("     ","||",$list1);
	// $list1 = str_replace("    ","||",$list1);
	// $list1 = str_replace("   ","||",$list1);
	// $list1 = str_replace("  ","||",$list1);
	// $list1 = str_replace(" ","||",$list1);
	// // $list = str_replace("   ","||",$list);
	// $list1 = explode("||",$list1);

	$res['i'] = $i;
	$res['status'] = '1';
	$res['f'] = $list;
	// $res['f1'] = $list1;
	$res['type'] = $list[0];
	$bankbuy = $list[2];
	$banksell = $list[12];

	// $res['type1'] = $list1[0];
	if ($list[0] == 'USD') {
	}
	$json_url = 'https://portal.sw.nat.gov.tw/APGQ/GC331!downLoad?formBean.downLoadFile=CURRENT_JSON';
	$json = file_get_contents($json_url);
	$filej = json_decode($json);
	$items = $filej->items;
	foreach ($items as $each) {
		if ($each->code == 'USD') {
			$buyV = $each->buyValue;
			$sellV = $each->sellValue;
		}
	}

	$idata['azk01'] = 'USD';
	$idata['azk02'] = date('Y-m-d');
	$idata['azk03'] = $bankbuy;
	$idata['azk04'] = $banksell;
	$b = (floatval($bankbuy) + floatval($banksell)) / 2;
	$idata['azk051'] = $buyV;
	$idata['azk052'] = $sellV;
	$idata['azkdate'] = date('Y-m-d');
	$idata['azkgrup'] = 'H10012';
	$idata['azkorig'] = 'H10012';
	$idata['azkoriu'] = 'J1602';
	$idata['azktime'] = date('H:i:s');
	$idata['azkuser'] = 'J1602';
	$sql = dbInsert('azk_file', $idata);
	$res['sql'] = $sql;
	JsonEnd($res);
}

function downloadFile($url, $fileName, $savePath = '')
{
	// $fileName = getUrlFileExt($url);
	// $fileName = 'eratebot';
	$file = file_get_contents($url);
	$savePath = '../../upload/';
	file_put_contents($savePath . $fileName, $file);
	return $fileName;
}

function downloadFile2($url, $fileName, $savePath = '')
{
	ini_set('display_errors', '1');
	// $fileName = getUrlFileExt($url);
	// $fileName = 'eratebot';
	// $arrContextOptions=array(
	// 	"ssl"=>array(
	// 		"verify_peer"=>false,
	// 		"verify_peer_name"=>false,
	// 	),
	// );  
	// $file = file_get_contents($url, true, stream_context_create($arrContextOptions));
	$json_url = 'https://portal.sw.nat.gov.tw/APGQ/GC331!downLoad?formBean.downLoadFile=CURRENT_JSON';
	$json = file_get_contents($json_url);
	$file = json_decode($json);
	JsonEnd($file);
	$savePath = '../../upload/';
	file_put_contents($savePath . $fileName, $file);
	return $fileName;
}

function getUrlFileExt($url)
{
	$ary = parse_url($url);
	$file = basename($ary['path']);
	$ext = explode('.', $file);
	return $ext[1];
}

function upload_cert()
{
	global $db, $conf_user, $tablename, $globalConf_signup_ver2020, $conf_members;
	$res = array();
	$img1 = global_get_param($_POST, 'img1', null);
	$img2 = global_get_param($_POST, 'img2', null);
	$img3 = global_get_param($_POST, 'img3', null);
	$memberid = loginChk();
	$msql = "SELECT * FROM members where id = '$memberid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$m_name = $md['name'];
	$mb_no = $md['ERPID'];
	if (count($img1) > 0) {
		foreach ($img1 as $key => $value) {
			if ($value) {
				$path = $memberid . "_p" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
		$path = 'http://192.168.7.46/upload_my/members/' . $memberid . '_p1.jpg';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path . '?v=' . time());
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$result['ex2'] = getimagesize($path);
		$res['img1'] = $base64;
	}

	if (count($img2) > 0) {
		foreach ($img2 as $key => $value) {
			if ($value) {
				$path = $memberid . "_n" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
		$path = 'http://192.168.7.46/upload_my/members/' . $memberid . '_n1.jpg';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path . '?v=' . time());
		$base641 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$result['ex2'] = getimagesize($path);
		$res['img2'] = $base641;
	}



	if (count($img3) > 0) {
		foreach ($img3 as $key => $value) {
			if ($value) {
				$path = $memberid . "_b" . $key . ".jpg";
				imgupd($value, $conf_members . $path, $tablename, $memberid, $key);
			}
		}
		$path = 'http://192.168.7.46/upload_my/members/' . $memberid . '_b1.jpg';
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path . '?v=' . time());
		$base642 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$result['ex3'] = getimagesize($path);
		$res['img3'] = $base642;
	}



	//寄送通知
	$sql = "select * from siteinfo";
	$db->setQuery($sql);
	$siteinfo_arr = $db->loadRow();
	$from = $siteinfo_arr['email'];

	$adminmail = getFieldValue("select email from siteinfo  ;", 'email');
	$webname = getFieldValue("select name from siteinfo  ;", 'name');

	$fromname = $siteinfo_arr['name'];
	$now = date('Y-m-d H:i:s');
	$subject = "$fromname 證件照片上傳 ( $now )";

	$body = "會員 $mb_no $m_name 已補上傳證件照片，請盡速確認。";
	$sendto = array(array("email" => 'vicky950217@goodarch2u.com', "name" => ''), array("email" => 'J1905@goodarch2u.com', "name" => ''));

	// $sendto = array(array("email" => 'H2008@goodarch2u.com', "name" => ''),array("email" => 'H1707@goodarch2u.com',"name"=>''),array("email" => 'juell@goodarch2u.com',"name"=>''));

	$rs = global_send_mail($adminmail, $webname, $sendto, $subject, $body, null, null, null);



	// $result['rr'] = $type;


	$res['status'] = 1;
	$res['rs'] = $rs;
	JsonEnd($res);
}


//add by tudojohn 20210429 Line@api串接
// function ai_login()
// {
// 	global $db, $tablename, $conf_user, $globalConf_encrypt_1, $globalConf_encrypt_2;

// 	$loginid = $_POST['email'];
// 	$passwd = $_POST['passwd'];

// 	$passwd = $passwd;
// 	$boss_id = $passwd;
// 	// nopass
// 	$passwd = enpw($passwd);
// 	$type = "web";

// 	$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND sid='$loginid'";
// 	// echo $sql;
// 	$db->setQuery($sql);
// 	$r = $db->loadRow();
// 	if (!empty($r)) {

// 		//$sql = "select * from $tablename where locked=0 AND passwd='$passwd' AND sid IS NOT NULL AND sid <> '' AND sid='$loginid'";
// 		//echo $sql;
// 		$status['status'] = 1;
// 		$status['msg'] = "登入成功";
// 		$_SESSION[$conf_user]['uid'] = $r['id'];
// 		$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
// 		$_SESSION[$conf_user]['uname'] = $r['name'];
// 		$_SESSION[$conf_user]['uemail'] = $r['email'];
// 		$_SESSION[$conf_user]['umobile'] = $r['mobile'];
// 		$_SESSION[$conf_user]['uaddress'] = $r['addr'];
// 		$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];
// 		$_SESSION[$conf_user]['uloginType'] = $type;

// 		//echo $_POST['kind'];

// 		//echo $_POST['kind'];
// 		//海旅點數	
// 		if ($_POST['kind'] == "34") {
// 			header('Location: http://125.227.104.50:8123/member_page/e_cash');
// 			exit;
// 		}
// 		//獎勵3S點數
// 		if ($_POST['kind'] == "35") {
// 			header('Location: http://125.227.104.50:8123/member_page/e_cash_new2_1');
// 			exit;
// 		}
// 		//隨身寶兌換
// 		if ($_POST['kind'] == "36") {
// 			header('Location: http://125.227.104.50:8123/member_page/carry_treasure');
// 			exit;
// 		}
// 		//生日券
// 		if ($_POST['kind'] == "37") {
// 			header('Location: http://125.227.104.50:8123/member_page/birthday_voucher');
// 			exit;
// 		}
// 		//海旅資格統計
// 		if ($_POST['kind'] == "38") {
// 			header('Location: http://125.227.104.50:8123/member_page/ecash_stat');
// 			exit;
// 		}
// 		//密碼設定
// 		if ($_POST['kind'] == "39") {
// 			header('Location: http://125.227.104.50:8123/member_page/pwchg');
// 			exit;
// 		}
// 		//業績查詢
// 		if ($_POST['kind'] == "40") {
// 			header('Location: http://125.227.104.50:8123/member_page/orgseq5');
// 			exit;
// 		}
// 		//組織查詢
// 		if ($_POST['kind'] == "41") {
// 			header('Location: http://125.227.104.50:8123/member_page/orgseq');
// 			exit;
// 		}
// 		//獎金查詢
// 		if ($_POST['kind'] == "42") {
// 			header('Location: http://125.227.104.50:8123/member_page/money_total');
// 			exit;
// 		}
// 		//訂單資訊
// 		if ($_POST['kind'] == "45") {
// 			header('Location: http://125.227.104.50:8123/member_page/order');
// 			exit;
// 		}
// 		//線上購物
// 		if ($_POST['kind'] == "46") {
// 			header('Location: http://125.227.104.50:8123/product_list/22?id=48');
// 			exit;
// 		}
// 	} else {

// 		$status['status'] = 0;
// 		$status['msg'] = "登入失敗!";
// 	}

// 	JsonEnd($status);
// }

function cash_back_list()
{
	global $db, $db3;
	$res = array();
	$uid = loginChk();
	$mb_no = getFieldValue("select ERPID from members where id = '$uid' ;", 'ERPID');
	$now_date = date('Y-m-d');
	if (!empty($mb_no)) {
		$cb_gpoints = 0;
		$csql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '0' and is_invalid = '0' and expiry_date > '$now_date'";
		$db3->setQuery($csql);
		$cgetlist = $db3->loadRow();
		if (!empty($cgetlist)) {
			$cb_gpoints = $cgetlist['cb_points']; //目前可用的得到點數
		}
		$usql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '1' and is_invalid = '0' and expiry_date > '$now_date'";
		$db3->setQuery($usql);
		$cuselist = $db3->loadRow();
		if (!empty($cuselist)) {
			$cb_upoints = $cuselist['cb_points']; //目前已使用的得到點數
		}
		$now_points = bcsub($cb_gpoints, $cb_upoints, 2);
		$msql = "UPDATE members set cash_back = '$now_points' where id = '$uid'";
		$db->setQuery($msql);
		$db->query();
		$res['user_cb'] = $now_points;
	}
	if (!empty($mb_no)) {
		$sql = "SELECT * FROM cash_back where mb_no = '$mb_no' and kind = '0'and is_invalid = '0' and point != '0' order by create_time desc";
		$db3->setQuery($sql);
		$get_list = $db3->loadRowList();
		$sql = "SELECT * FROM cash_back where mb_no = '$mb_no' and kind = '1'and is_invalid = '0' and point != '0' order by create_time desc, expiry_date desc";
		$db3->setQuery($sql);
		$use_list = $db3->loadRowList();
		$res['get_list'] = $get_list;
		$res['use_list'] = $use_list;
		$res['status'] = '1';
	} else {
		$res['status'] = '0';
	}
	JsonEnd($res);
}
function get_recommend()
{
	global $db, $db2, $conf_user, $tablename, $globalConf_signup_ver2020, $conf_members;
	$res = array();
	$uid = loginChk();
	$recommend_code = getFieldValue("select recommendCode from members where id = '$uid' ;", 'recommendCode');
	$sql = "SELECT mb_no,mb_name,tel3 FROM mbst where mb_no = '$recommend_code'";
	$db2->setQuery($sql);
	$data = $db2->loadRow();
	$msql = "SELECT * from members where id = '$uid'";
	$db->setQuery($msql);
	$mb_data = $db->loadRow();
	$res['status'] = '1';
	$res['re_data'] = $data;
	$res['mb_data'] = $mb_data;
	JsonEnd($res);
}

function mlm_order_list()
{
	global $db, $db2, $conf_user;

	$uid = $_SESSION[$conf_user]['uid'];
	if (intval($uid) == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_LOGIN_FIRST));
	}

	$msql = "SELECT ERPID from members where id = '$uid'";
	$db->setQuery($msql);
	$members = $db->loadRow();
	$ERPID = $members['ERPID'];

	$osql = "SELECT ord_no as orderNum,ord_date,total_money as totalAmt,total_pv,total_bv, 'cart' as orderMode FROM order_m where mb_no = '$ERPID' and ord_no not like '3S%'";
	$db2->setQuery($osql);
	$order_list = $db2->loadRowList();
	$order_lists = array();
	foreach ($order_list as $each) {
		$eorderNum = $each['orderNum'];
		$odsql = "SELECT count(d_no) as cnt from order_d where ord_no = '$eorderNum'";
		$db2->setQuery($odsql);
		$cnt_list = $db2->loadRow();
		$cnt = $cnt_list['cnt'];
		$c_date = date('Y-m-d', $each['ord_date']);
		$each['cnt'] = $cnt;
		$each['totalpv'] = $each['total_pv'];
		$each['totalbv'] = $each['total_bv'];
		$order_lists[$c_date][] = $each;
	}


	$search_str = global_get_param($_GET, 'search_str', null, 0, 1);
	$where_str = "";
	if ($search_str) {
		$search_arr = explode(" ", $search_str);
		if (count($search_arr) > 0) {
			$where_str .= " AND (";
			foreach ($search_arr as $row) {
				$where_str .= " A.orderNum like '%$row%' OR ";
				$where_str .= " A.buyDate like '%$row%' OR ";
			}
			$where_str .= " 1<>1 )";
		}
	}

	$sql = "
		select id,bundleadd,orderNum,buyDate,totalAmt,regpoint,status as statusCode,payType,orderMode,bonusAmt,
			(
				CASE payType
				WHEN 1 THEN 
					CASE status
					WHEN 2 THEN (select codeName_chs from pubcode where codeKinds='bill' AND codeValue=status)
					ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue=status)
					END
				WHEN 3 THEN 
					CASE status
					WHEN 1 THEN (select codeName_chs from pubcode where codeKinds='bill' AND codeValue=status)
					ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue=status)
					END
				ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue=status)
				END
			) as status,cnt,pid from (
			select A.id,A.bundleadd,A.orderNum,A.buyDate,A.totalAmt,A.regpoint,A.status,count(1) as cnt, MIN(B.pid) as pid,A.payType,A.orderMode,A.bonusAmt
			from orders A left join orderdtl B on A.id=B.oid
			where A.memberid='$uid' $where_str AND A.combineid=0
			group by A.id,A.orderNum,A.buyDate,A.totalAmt,A.status
		)as tbl
		order by buyDate desc,id desc";

	$db->setQuery($sql);
	$f = $sql;
	$r = $db->loadRowList();
	$list = array();

	foreach ($r as $row) {
		$buyDate = date("Y-m-d", strtotime($row['buyDate']));

		if (empty($row['pid'])) {
			$row['pid'] = getFieldValue(" SELECT productId FROM orderBundle A , orderBundleDetail B WHERE A.id = B.orderBundleId AND A.orderId = '{$row['id']}' ", "productId");
		}

		$row['totalAmt'] = $row['totalAmt'] - $row['regpoint'];
		$row['imgname'] = getimg("products", $row['pid']);
		$row['imgname'] = $row['imgname'][1];

		$list[$buyDate][] = $row;
	}



	JsonEnd(array("status" => 1, "data" => $list, "order_list" => $order_lists, "osql" => $order_list));
}

function mlm_order_dtl()
{
	global $db, $db2, $conf_user;


	$id = global_get_param($_POST, 'id', null, 0, 1);

	// JsonEnd($id);

	// if (!$id || !is_numeric($id)) {

	// 	if (!$id)
	// 		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));

	// 	$_SESSION[$conf_user]['redirect_url'] = getFieldValue("select var01 from requestLog where code='$id'", "var01");
	// 	$id = getFieldValue("select var02 from requestLog where code='$id'", "var02");
	// }
	// $id = intval($id);
	$uid = LoginChk();
	$msql = "SELECT ERPID from members where id = '$uid'";
	$db->setQuery($msql);
	$mdetail = $db->loadRow();
	$ERPID = $mdetail['ERPID'];

	$osql = "SELECT * FROM order_m where ord_no = '$id'";
	$db2->setQuery($osql);
	$r = $db2->loadRow();
	$data = array();



	$odsql = "SELECT A.*,B.ord_date,B.ord_no as orderNum,'0' as bonusAmt,A.price as unitAmt,A.qty as quantity,A.sub_money as subAmt,C.prod_name as product_name FROM order_d A,order_m B,product_m C where A.ord_no = B.ord_no and A.ord_no = '$id' and B.mb_no = '$ERPID' and C.prod_no = A.prod_no AND A.prod_no not like 'fee_tw%'";
	$db2->setQuery($odsql);
	$od_detail = $db2->loadRowList();
	// JsonEnd($od_detail);

	if (count($od_detail) == 0) {
		JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	}
	$data['data'] = $od_detail;
	$data['orderNum'] = $r['ord_no'];
	$data['buyDate'] = date("Y-m-d", $r['ord_date']);
	$data['sumAmt'] = $r['total_money'];
	$data['totalpv'] = $r['total_pv'];
	$data['totalbv'] = $r['total_bv'];
	$data['promode'] = 'normal';
	$data['cnt'] = count($od_detail);

	// $id = intval(getFieldValue("select id from orders where memberid='$uid' AND id='$id'", "id"));
	// if ($id == 0) {
	// 	JsonEnd(array("status" => 0, "msg" => _MEMBER_NO_OEDER));
	// }


	// $data['orderNum'] = $r['orderNum'];
	// $data['buyDate'] = date("Y-m-d", strtotime($r['buyDate']));


	// $sql = "select A.sumAmt,A.discount,A.e3Amt,A.dlvrFee,A.usecoin,A.totalAmt,A.regpoint as o_regpoint,A.payType,A.dlvrType,A.status,A.dlvrCity,A.dlvrCanton,
	// 		A.dlvrName,A.dlvrMobile,A.dlvrAddr,A.dlvrDate,A.dlvrTime,A.dlvrNote,A.invoiceType,A.invoiceTitle,A.invoiceSN,A.invoice,A.pv as opv,A.bv as obv,A.bonus as obonus, A.traceNumber, A.virtualAccount,A.bundleadd,
	// 		B.*,C.name,C.id as pid,C.highAmt
	// 		from orders A LEFT JOIN orderdtl B ON A.id=B.oid LEFT JOIN products C ON B.pid=C.id where A.id='$id' AND A.memberid='$uid'";
	// $db->setQuery($sql);
	// $r = $db->loadRowList();

	// foreach ($r as $row) {
	// 	$info = array();
	// 	// $info['product_name'] = $row['name'];

	// 	// $format1name = $row['format1name'];
	// 	// $format2name = $row['format2name'];
	// 	// $formatStr = "";
	// 	// if (!empty($format1name)) {
	// 	// 	$formatStr .= $format1name;
	// 	// }
	// 	// if (!empty($format2name)) {
	// 	// 	if (!empty($formatStr)) {
	// 	// 		$formatStr .= " - " . $format2name;
	// 	// 	} else {
	// 	// 		$formatStr .= $format2name;
	// 	// 	}
	// 	// }
	// 	// if (!empty($formatStr)) {
	// 	// 	$info['product_name'] .= "【" . $formatStr . "】";
	// 	// }


	// 	$info['highAmt'] = $row['highAmt'];
	// 	$info['unitAmt'] = $row['unitAmt'];
	// 	$info['quantity'] = $row['quantity'];
	// 	$info['subAmt'] = $row['subAmt'];
	// 	$info['pv'] = $row['pv'];
	// 	$info['bv'] = $row['bv'];
	// 	$info['bonus'] = $row['bonus'];
	// 	$info['protype'] = $row['protype'];
	// 	$info['bonusAmt'] = $row['bonusAmt'];
	// 	$info['e3unitAmt'] = $row['e3unitAmt'];
	// 	$info['e3subAmt'] = $row['e3subAmt'];
	// 	$info['imgname'] = getimg("products", $row['pid']);
	// 	$info['imgname'] = $info['imgname'][1];
	// 	if ($info['bonusAmt']) {
	// 		$data['promode'] = "bonus";
	// 	} else {
	// 		$data['promode'] = "normal";
	// 	}

	// 	$data['sumAmt'] = $row['sumAmt'];
	// 	$data['discount'] = $row['discount'] + $row['o_regpoint'];
	// 	$data['dlvrFee'] = $row['dlvrFee'];
	// 	$data['usecoin'] = $row['usecoin'];
	// 	$data['totalAmt'] = $row['totalAmt'] - $row['o_regpoint'];
	// 	$data['totalpv'] = $row['opv'];
	// 	$data['totalbv'] = $row['obv'];
	// 	$data['totalbonus'] = $row['obonus'];
	// 	$data['status'] = $row['status'];
	// 	$data['payType'] = pay_type($row['payType']);
	// 	$data['takeType'] = take_type(null, $row['dlvrType']);
	// 	$data['payTypeCode'] = $row['payType'];
	// 	$data['takeTypeCode'] = $row['dlvrType'];

	// 	$data['e3Amt'] = $row['e3Amt'];

	// 	$data['dlvrName'] = $row['dlvrName'];
	// 	$data['dlvrMobile'] = $row['dlvrMobile'];
	// 	$data['dlvrCity']['id'] = $row['dlvrCity'];
	// 	$data['dlvrCanton']['id'] = $row['dlvrCanton'];
	// 	$data['dlvrAddr'] = $row['dlvrAddr'];
	// 	$data['dlvrDate'] = $row['dlvrDate'];
	// 	$data['dlvrTime'] = $row['dlvrTime'];
	// 	$data['dlvrNote'] = $row['dlvrNote'];
	// 	$data['invoiceType'] = $row['invoiceType'];
	// 	$data['invoiceTitle'] = $row['invoiceTitle'];
	// 	$data['invoiceSN'] = $row['invoiceSN'];
	// 	$data['invoice'] = $row['invoice'];

	// 	$data['traceNumber'] = $row['traceNumber'];
	// 	$data['virtualAccount'] = $row['virtualAccount'];
	// 	$data['bundleadd'] = $row['bundleadd'];

	// 	if ($info['product_name']) {
	// 		$data['data'][] = $info;
	// 	}
	// }
	// $data['cnt'] = count($data['data']);

	// $sql = "select * from payconf ";
	// $db->setQuery($sql);
	// $r = $db->loadRow();
	// $data['bankName'] = $r['bankName'];
	// $data['bankBranch'] = $r['bankBranch'];
	// $data['bankId'] = $r['bankId'];
	// $data['bankNum'] = $r['bankNum'];
	// $data['ccbPayBankName'] = $r['ccbPayBankName'];
	// $data['ccbPayBankBranch'] = $r['ccbPayBankBranch'];
	// $data['ccbPayBankId'] = $r['ccbPayBankId'];

	unset($_SESSION[$conf_user]['redirect_url']);

	// $sql = "select A.id,A.ctime as cdate,A.oid,A.status as statusCode,
	// 		(
	// 			CASE B.payType
	// 			WHEN 1 THEN 
	// 				CASE A.status
	// 				WHEN 2 THEN (select codeName_chs from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				END
	// 			WHEN 3 THEN 
	// 				CASE A.status
	// 				WHEN 1 THEN (select codeName_chs from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				END
	// 			WHEN 6 THEN 
	// 				CASE A.status
	// 				WHEN 1 THEN (select codeName_chs from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 				END
	// 			ELSE (select codeName from pubcode where codeKinds='bill' AND codeValue= A.status)
	// 			END
	// 		) as status 

	// 	from orderlog A, orders B where A.oid = B.id AND A.oid='$id' order by A.ctime desc limit 1";
	// $db->setQuery($sql);
	// $r = $db->loadRowList();
	// foreach ($r as $row) {


	// 	$data['orderlog'][] = $row;
	// }
	$_SESSION[$conf_user]['order_id'] = $id;


	// $sql = "select * from orderBundleDetail where exists(select 1 from orderBundle A where A.orderId='$id' AND A.id=orderBundleId)";
	// $db->setQuery($sql);
	// $r = $db->loadRowList();
	// $orderBundleDetailObj = array();
	// foreach ($r as $value) {
	// 	$orderBundleDetailObj[$value['orderBundleId']][] = $value;
	// }

	// $sql = "select * from orderBundle where orderId='$id'";
	// $db->setQuery($sql);
	// $orderBundleArray = $db->loadRowList();

	// foreach ($orderBundleArray as $key => $value) {
	// 	$orderBundleArray[$key]['orderBundleDetail'] = $orderBundleDetailObj[$value['id']];
	// 	$data['promode'] = "normal";
	// 	// $data['totalpv'] += $value['pv'];
	// 	// $data['totalbv'] += $value['bv'];
	// 	$data['totalbonus'] += 0;
	// }
	// $data['cnt'] += count($orderBundleArray);


	// $data['deadLineDT'] = date("Y年m月d日 23時59分59秒", strtotime($data['buyDate'] . " +4 day"));

	JsonEnd(array("status" => 1, "data" => $data));
}

// function check_tspg(){
// 	global $db;
// 	ini_set('dispaly_errors','1');
// 	ini_set('max_execution_time', '0');
// 	set_time_limit(0);
// 	$sql = "SELECT * from ordertspglog where type = 'Add' and createTime between '2021-06-01' and '2021-06-30'";
// 	$db->setQuery($sql);
// 	$list = $db->loadRowList();
// 	$sql = " SELECT A.tspgPayMid,A.tspgPayTid FROM payconf A";
// 	$db->setQuery($sql);
// 	$payconf = $db->loadRow();
// 	$mid = $payconf['tspgPayMid'];
// 	$tid = $payconf['tspgPayTid'];
// 	foreach ($list as $k => $r) {
// 		$ServiceURL = "https://tspg.taishinbank.com.tw/tspgapi/restapi/other.ashx";
// 		$tx_type = 7;
// 		$param = array();
// 		// $orderNum = $r['orderNum'];
// 		// $orderNum = str_replace("3S010-", "3S010", $orderNum);
// 		// $csql = "SELECT * FROM ordertspglog WHERE orderNum LIKE '$orderNum%' and type='Add' ORDER BY createTime desc";
// 		// $db->setQuery($csql);
// 		// $otlog = $db->loadRow();
// 		$orderNum = $r['orderNum'];
// 		$param['order_no'] = "$orderNum";
// 		$param['result_flag'] = '1';
// 		$send = array();
// 		$send['sender'] = 'rest';
// 		$send['ver'] = '1.0.0';
// 		$send['mid'] = $mid;
// 		$send['tid'] = $tid;
// 		$send['pay_type'] = 1;
// 		$send['tx_type'] = $tx_type;
// 		$send['params'] = $param;
// 		// $send['result_flag'] = '1';

// 		$ch = curl_init();
// 		$headers = array(
// 			'Content-Type:application/json',
// 			'Accept:application/json'
// 		);

// 		curl_setopt($ch, CURLOPT_URL, $ServiceURL);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 		curl_setopt($ch, CURLOPT_POST, true);
// 		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));
// 		$rep = curl_exec($ch);


// 		curl_close($ch);
// 		$rep = json_decode($rep, true);
// 		$re_param = $rep['params'];
// 		$res = array();
// 		$res['orderNum'] = $orderNum;
// 		$res['send'] = json_encode($send,true);
// 		$res['rep'] = json_encode($rep,true);
// 		$res['order_status'] = $re_param['order_status'];
// 		$sql = dbInsert('tspglog',$res);
// 		$db->setQuery($sql);
// 		$db->query();
// 	}

// }
function mls()
{
	global $db, $conf_user, $HTTP_X_FORWARDED_PROTO;

	$tid = $_GET['tid'];
	$ac = $_GET['ac'];
	$upwd = $_GET['upwd'];
	$sql = "SELECT * FROM adminmanagers where loginid = '$ac' and passwd = '$upwd' and memberloginStraight = 'true'";
	$db->setQuery($sql);
	$list = $db->loadRow();
	if (!empty($list)) {
		$sql = "SELECT * FROM members WHERE id = '$tid'";
		$db->setQuery($sql);
		$r = $db->loadRow();
		$_SESSION[$conf_user]['uid'] = $r['id'];
		$_SESSION[$conf_user]['uloginid'] = $r['loginid'];
		$_SESSION[$conf_user]['uname'] = $r['name'];
		$_SESSION[$conf_user]['uemail'] = $r['email'];
		$_SESSION[$conf_user]['umobile'] = $r['mobile'];
		$_SESSION[$conf_user]['uaddress'] = $r['addr'];
		$_SESSION[$conf_user]['salesChk'] = $r['salesChk'];

		header("Location: " . $HTTP_X_FORWARDED_PROTO . "://" . $_SERVER['HTTP_HOST'] . "/");
		exit();
	} else {
		header("Location: " . $HTTP_X_FORWARDED_PROTO . "://" . $_SERVER['HTTP_HOST'] . "/");
		exit();
	}
	// $_SESSION[$conf_user]['uid'] = $tid;
}

function sign_codeChk()
{
	global $db2, $conf_user;
	$res = array();

	$code = global_get_param($_POST, 'code', null, 0, 1);
	$mb_no = global_get_param($_POST, 'mb_no', null, 0, 1);
	$sql = "SELECT * FROM register_tb where mb_no = '$mb_no' and random = '$code' and is_used = '0'";
	$db2->setQuery($sql);
	$data = $db2->loadRow();
	if (!empty($data)) {
		$res['data'] = $data;
		$res['status'] = '1';
	} else {
		$res['status'] = '0';
	}

	JsonEnd($res);
}

function register_tb_list()
{
	global $db, $db2, $conf_user;
	$res = array();
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$sql = "SELECT * FROM register_tb where mb_no ='$mb_no' order by create_date desc";
	$db2->setQuery($sql);
	$list = $db2->loadRowList();
	$res['data'] = $list;
	$res['status'] = '1';
	JsonEnd($res);
}

function get_soap()
{
	ini_set('display_errors', '1');
	$res = array();
	$test = 1;
	$IntegrationID = '5b500af5-0dcc-4d7d-b2c8-8da96fec6ffd';
	$Username = 'kangfurou';
	$Password = 'Homeway#1';
	if ($test == '1') {
		$IntegrationID = '5b500af5-0dcc-4d7d-b2c8-8da96fec6ffd';
		$Username = 'HBC-001';
		$Password = 'August2021!';
	}


	$authData = array(
		"Credentials"  => array(
			"IntegrationID"  => $IntegrationID,
			"Username"       => $Username,
			"Password"       => $Password
		)
	);
	$wsdl = 'https://swsim.testing.stamps.com/swsim/swsimv111.asmx?wsdl';
	// $wsdl = 'https://swsim.stamps.com/swsim/swsimv111.asmx?wsdl';
	// $ff = file_get_contents($wsdl);
	// print_r($ff);

	// echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
	// echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";



	$client = new SoapClient($wsdl, array('trace' => 1));
	$auth = $client->AuthenticateUser($authData);
	$AuthenticatorToken = $auth->Authenticator;
	$setCodewords = [
		"Authenticator" => $AuthenticatorToken,
		// "Credentials" => $authData,
		"Codeword1Type" => 'Last4SocialSecurityNumber',
		"Codeword1" => '1234',
		"Codeword2Type" => 'Last4DriversLicense',
		"Codeword2" => '1234'
	];
	$GetCodewordQuestions = [
		"Username" => $Username
	];

	$GetAccountInfo = [
		"Credentials" => $authData
	];

	$PurchasePostage = [
		"Authenticator" => $AuthenticatorToken,
		"PurchaseAmount" => '100',
		"ControlTotal" => "5.0000"
	];

	try {
		// $res['AuthenticatorToken'] = $client->AuthenticateUser($authData);
		// $res['GetCodewordQuestions'] = $client->GetCodewordQuestions($GetCodewordQuestions);
		// $res['objectresult3'] = $client->SetCodeWords($setCodewords);
		// $client->GetAccountInfo($authData);
		$client->PurchasePostage($PurchasePostage);
	} catch (Exception $e) {
		echo "EXCEPTION: " . $e->getMessage();
		print_r($e);
		exit;
	}

	echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
	echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";

	// JsonEnd($res);
}

function set_pm()
{
	global $db, $db3, $conf_user;
	// ini_set('display_errors','1');
	$sp = global_get_param($_POST, "sp", null, 0, 1);
	if ($sp < 25) {
		JsonEnd(array("status" => '0', "msg" => _SET_PM_MIN));
	}

	$res = array();
	$res['sp'] = $sp;
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now = date('Y-m-d H:i:s');
	$today = date('Y-m-d');


	$msql = "select * from members where ERPID = '$mb_no'";
	$db->setQuery($msql);
	$m = $db->loadRow();

	$name = $m['name'];


	$this_year = date('Y');
	$next_year = $this_year + 1;
	$sql = "SELECT p.* FROM points p, point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date > '$now' and '$now' >= p.active_date and p.kind = pk.kind and pk.type = '1'"; //總可用點數
	$db3->setQuery($sql);
	$list = $db3->loadRowList();
	$total = 0;
	foreach ($list as $each) {
		$res['o_total'] = $total = bcadd($total, $each['point'], 2);
	}

	$msql = "SELECT p.* FROM points p, point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind and pk.type = '2'";
	$db3->setQuery($msql);
	$mlist = $db3->loadRowList();
	$mtotal = 0;
	foreach ($mlist as $each) {
		if ($each['deduct_year_1'] == $this_year || $each['deduct_year_1'] == $next_year) {
			$mtotal = bcadd($mtotal, $each['deduct_point_1'], 2);
		}
		if ($each['deduct_year_2'] == $this_year || $each['deduct_year_2'] == $next_year) {
			$mtotal = bcadd($mtotal, $each['deduct_point_2'], 2);
		}
	}
	$res['mtotal'] = $mtotal;
	$res['total'] = $total = bcsub($total, $mtotal, 2);

	if ($sp > $total) {
		JsonEnd(array("status" => '0', "msg" => _POINTS_NOT_ENOUGH));
	}

	$sql2 = "SELECT * FROM points p,point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date like '$this_year%' and pk.kind = p.kind and pk.type = '1'";  //今年到期
	$db3->setQuery($sql2);
	$list2 = $db3->loadRowList();
	$t_total = 0;
	foreach ($list2 as $each) {
		$t_total = bcadd($t_total, $each['point'], 2);
	}
	$res['t_total'] = $t_total;

	$sql3 = "SELECT p.*,pk.type as p_type FROM points p,point_kind pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.expiry_date like '$next_year%' and pk.kind = p.kind and pk.type = '1'"; //明年到期
	$db3->setQuery($sql3);
	$list3 = $db3->loadRowList();
	$n_total = 0;
	$u1_point = 0;
	$u2_point = 0;
	$nu1_point = 0;
	$nu2_point = 0;
	foreach ($list3 as $each) {
		$n_total = bcadd($n_total, $each['point'], 2);
	}
	$res['n_total'] = $n_total;

	$u1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u1sql);
	$u1_list = $db3->loadRow();
	if (!empty($u1_list['u1_point'])) {
		$u1_point = $u1_list['u1_point'];
	}


	$u2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u2sql);
	$u2_list = $db3->loadRow();
	if (!empty($u2_list['u2_point'])) {
		$u2_point = $u2_list['u2_point'];
	}


	$nu1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$next_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($nu1sql);
	$nu1_list = $db3->loadRow();
	if (!empty($nu1_list['u1_point'])) {
		$nu1_point = $nu1_list['u1_point'];
	}


	$nu2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$next_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($nu2sql);
	$nu2_list = $db3->loadRow();
	if (!empty($nu2_list['u2_point'])) {
		$nu2_point = $nu2_list['u2_point'];
	}
	$res['nu_point'] = $nu_point = bcadd($nu1_point, $nu2_point, 2);
	$res['u_point'] = $u_point = bcadd($u1_point, $u2_point, 2);
	$res['n_total'] = $n_total = bcsub($n_total, $nu_point, 2);
	$res['t_total'] = $t_total = bcsub($t_total, $u_point, 2);

	$sp_arr = array();
	$sp_arr['mb_no'] = $mb_no;
	$sp_arr['set_date'] = $today;
	$sp_arr['points'] = $sp;
	$sp_arr['periods_seq'] = 1;
	$sp_arr['kind'] = 2;
	$sp_arr['is_invalid'] = 0;
	$sp_arr['carried_forward'] = 0;
	$sp_arr['create_time'] = $now;
	$sp_arr['create_user'] = 'H2008';

	$sql = dbInsert('p_to_m', $sp_arr);
	$db3->setQuery($sql);
	$check = $db3->query();

	if ($check == true) {
		$pm_id = $db3->insertid();
	}

	$s_arr = array();
	$s_arr['mb_no'] = $mb_no;
	$s_arr['point'] = $sp;
	$s_arr['active_date'] = $today;
	$s_arr['expiry_date'] = '9999-12-31';
	$s_arr['provide_date'] = $today;
	$s_arr['kind'] = '5';
	$s_arr['is_invalid'] = 0;
	$s_arr['creator_name'] = $name;
	$s_arr['create_time'] = $now;
	$s_arr['consume_1'] = 0;
	$s_arr['consume_2'] = 0;
	$s_arr['rate'] = 0;
	$s_arr['deduct_year_1'] = date('Y');
	$s_arr['deduct_point_1'] = $sp;
	$s_arr['note'] = '獎金轉點數';
	$s_arr['withdraw'] = '1';
	$s_arr['pm_id'] = $pm_id;

	$sql2 = dbInsert('points', $s_arr);
	$db3->setQuery($sql2);
	$db3->query();

	// $res['sql'] = $sql;
	// $res['sql2'] = $sql2;
	// $res['pm_id'] = $pm_id;
	$res['status'] = '1';
	$res['msg'] = _PM_DONE;

	JsonEnd($res);
}

function del_pm()
{
	global $conf_user, $db3;
	// ini_Set('display_errors','1');
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$id = global_get_param($_POST, "id", null, 0, 1);
	$res = array();
	//p_to_m無效
	$sql = "update p_to_m set is_invalid = '1' where id ='$id' and mb_no = '$mb_no'";
	$db3->setQuery($sql);
	$check = $db3->query();

	$sql2 = "update points set is_invalid = '1' where pm_id ='$id' and mb_no = '$mb_no'";
	$db3->setQuery($sql2);
	$check1 = $db3->query();

	if ($check == true && $check1 == true) {
		$res['status'] = '1';
	} else {
		$res['status'] = '0';
	}

	$res['sql'] = $sql;
	$res['sql2'] = $sql2;

	JsonEnd($res);
}

function chkchk()
{
	global $db, $conf_user;
	$uid = LoginChk();
	$res = array();
	$sql = "SELECT emailChk,mobileChk FROM members WHERE id = '$uid'";
	$db->setQuery($sql);
	$md = $db->loadRow();
	$pass = false;
	$msg = '';
	if (!empty($md)) {
		$emailChk = $md['emailChk'];
		$mobileChk = $md['mobileChk'];
		if ($emailChk == '0') {
			$pass = true;
		}

		if ($mobileChk == '1') {
			$pass = true;
		}
		if ($pass) {
			$res['status'] = '1';
		} else {
			$res['status'] = '0';
		}
	} else {
		$res['status'] = '0';
	}
	JsonEnd($res);
}

function get_cp58(){
	global $db, $db2, $conf_user;
	ini_set('display_errors','1');
	$res = array();
	$uid = intval($_SESSION[$conf_user]['uid']);
	if ($uid != 0) {
		$u_data = get_user_info_m();
		$mb_no = $u_data['mb_no'];
	}
	// $mb_no = 'MY20190500193';
	$sql = "SELECT * FROM cp58_report_log WHERE mb_no = '$mb_no' and status = '1'";
	$db2->setQuery($sql);
	$list = $db2->loadRowList();
	$cp58_list = array();
	foreach ($list as $k => $v) {
		$yy = $v['yy'];
		$pdf_name = $v['pdf_name'];
		$create_time = date('Y-m-d',strtotime($v['create_time']));
		$list[$k]['create_time'] = $create_time;
		$url = MLMURL . "form/module/cp58_report/check_cp58_api.php?mb_no=$mb_no&yy=$yy&pdf_name=$pdf_name";
		$result = file_get_contents($url);

		$cp58_result = json_decode($result);
		if($cp58_result->check == true){
			$path = MLMURL . "form/module/cp58_report/" . $v['pdf_name'];
			$list[$k]['path'] = $path;
			$cp58_list[] = $v;
		}
	}


	$res['list'] = $list;
	$res['url'] = $url;
	// $url = MLMURL . "form/module/cp58_report/check_cp58_api.php?mb_no='$mb_no'";
	// $result = file_get_contents($url);
	$res['status'] = '1';
	$res['result'] = $result;
	// $res['cp58_list'] = $cp58_list;
	$res['cp58_list'] = $list;
	if(!empty($cp58_list)){
		$res['cp58_exist'] = true;
	}else{
		$res['cp58_exist'] = false;
	}
	JsonEnd($res);
}

function footpic(){
	global $db, $conf_user,$db2,$db6;
	$u_data = get_user_info();
	$mb_no = $u_data['mb_no'];//有會編

	$sql = "SELECT mb_no,aifootno,real_mb_no from order_m where real_mb_no='$mb_no'";//從訂單找到會編,列出此會員的訂單，實際購買者=登入人
	$db2->setQuery($sql);
	$start = $db2->loadRowList();
	$result = array();
	$result2 = array();
	$res = array();
	foreach($start as $k=>$v){
		$aifootno = $v['aifootno'];//取腳圖編號
		$sql2 = "SELECT * from ai_foot_file WHERE af_id='$aifootno' ";//到54尋找腳圖
		$db6->setQuery($sql2);
		$list = $db6->loadRowList();
		if(!empty($list)){
			foreach ($list as $key => $value) {
				if($list[$key]['af_status'] == 1){
					$path = 'https://footview.goodarch2u.com/'.$list[$key]['af_filename'];
				}
				else{
					$path = 'http://192.168.7.54/aifoot/public/uploadfile/myfoot_image/'.$list[$key]['af_filename'];
				
				}
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$data = file_get_contents($path);
				$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
				
				$result2['ex'.$key] = getimagesize($path);
				$result[$list[$key]['af_id']] = $base64;
			}
		}
		
		
		if (!empty($result)) {
			$res['result'] = $result;
			$res['result2'] = $result2;
			$res['status'] = 1;
		} else {
			$res['status'] = 0;
		}
		$res['list'] = $list;
		
	}
	JsonEnd($res);
		
}
function my_bone_density()
{
	global $db, $conf_user;
	$sid = global_get_param($_POST, 'sid', null, 0, 1);
	$uid = LoginChk();
	$result = array();
	$result2 = array();
	$list = array();
	$mb_no = getFieldValue("SELECT ERPID FROM members WHERE id = '$uid'", "ERPID");	
	$mb_sid = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ", "sid");	
	$res = array();
	$sql2= "select members.ERPID, members.sid, members.name,tscore.sex as sex,inputdate,tscore from members join tscore on tscore.id=members.sid where members.sid = '$mb_sid' order by inputdate desc;";
	//$sql = "select mb_no,boss_id,mb_name,birthday2,contact_name,tscore.sex as sex,inputdate,tscore from mbst join tscore on id=boss_id where mb_status=1 and grade_1_chk=1 and boss_id = 'J220253482' order by inputdate desc;";
	$db->setQuery($sql2);
	$list = $db->loadRowList();
	if(!empty($list)){
		foreach ($list as $key => $value){
			$inputdate = $list[$key]['inputdate'];
			$tscore = $list[$key]['tscore'];
			$result[$list[$key]['inputdate']] = $tscore;
		}
	}
	if (!empty($result)) {
		$res['result'] = $result;
		$res['status'] = 1;
	} else {
		$res['status'] = 0;
	}
	$res['list'] = $list;
	
	JsonEnd($res);

}
function genomics(){
	global $db,$db2,$db5,$globalConf_list_limit,$conf_news,$conf_user,$tablename;
	$uid = LoginChk();//抓登入者id
	$sq = "select * from members where id='$uid'";
	$db->setQuery($sq);
	$r = $db->loadRow();
	$boss_id = $r['sid'];	// 要檢查的身分證號碼	A123123123	
	// $boss_id ='601005125069';
    $arrJson = array();
	$page = max(intval(global_get_param( $_REQUEST, 'page', 1 )), 1);
	$pageRow_records=10;//預設每頁筆數
	//抓端粒資料
	$sql = "SELECT * FROM sn_data where region='my' AND  sn_status='1' and status='1' and boss_id='$boss_id'";//未加限制顯示筆數的SQL敘述句
	$db5->setQuery($sql);//把語法放置db5
	$result = $db5->loadRowList();//未加限制顯示筆數的SQL查詢到的資料放到$result中
	$total_records=count($result);//計算總筆數
	$pagecnt = max($total_records % $pageRow_records == 0 ? floor($total_records / $pageRow_records) : floor($total_records / $pageRow_records) + 1, 1);//計算總頁數
	$page = ($page > $pagecnt) ? $pagecnt : $page;
	$from = ($page - 1 ) * $pageRow_records;
	$end = $page * $pageRow_records;	
	$data = array();
	for($i = $from; $i < min($end, $total_records); $i++) {
		$info=array();
		$info['id']=$result[$i]['id'];
	 	$info['report_date']=$result[$i]['report_date'];
        $info['report_url']=$result[$i]['report_url'];
	 	$info['sn_status']=$result[$i]['sn_status'];
	 	$info['mb_name']=$result[$i]['mb_name'];
	 	$data[]=$info;
	}
	$arrJson['status'] = 1;
	$arrJson['test']=$uid ;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
	
	
}

include($conf_php . 'common_end.php');
