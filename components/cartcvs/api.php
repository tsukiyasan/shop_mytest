<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="products";

$EncRes = global_get_param( $_POST, 'URLResEnc', null ,0,1);
if(!empty($EncRes))
{
	$task = "getReturn";
}


switch ($task) {
	case "list":
		showlist();
	    break;
	case "order_submit":
		order_submit();
	    break;
	case "order_submit2":
		order_submit2();
	    break;
	case "getReturn":
		getReturn();
	    break;
	case "order_submit_tspg":
		order_submit2("tspg");
	    break;
	case "getReturn_tspg":
		getReturn_tspg();
	    break;
	case "backViewAJAX_tspg":
		backViewAJAX_tspg();
	    break;
	case "backView_tspg":
		backView_tspg();
	    break;
	case "getReturn_vatm":
		getReturn_vatm();
	    break;
}

function http_response_code_output($httpStatusCode, $httpStatusMsg)
{
	$phpSapiName    = substr(php_sapi_name(), 0, 3);
	if ($phpSapiName == 'cgi' || $phpSapiName == 'fpm') {
		header('Status: '.$httpStatusCode.' '.$httpStatusMsg);
	} else {
		$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
		header($protocol.' '.$httpStatusCode.' '.$httpStatusMsg);
	}
	exit;
	die();
}



function getReturn_vatm()
{
	global $db,$conf_user,$tablename,$conf_php,$real_domain,$HTTP_X_FORWARDED_PROTO;
	
	
	
	
	$MsgID = global_get_param( $_POST, 'MsgID', null ,0,1);
	
	
	$TransactionNo = global_get_param( $_POST, 'TransactionNo', null ,0,1);
	$body = global_get_param( $_POST, 'body', null ,0,1);
	
	
	
	$arr = array();
	$arr[0] = $body;
	$arr[1] = substr($body,0,12);   
	$arr[2] = substr($body,12,1);   
	$arr[3] = substr($body,13,7);   
	$arr[4] = substr($body,20,14);  
	$arr[5] = substr($body,34,15);  
	$arr[6] = substr($body,49,7);   
	$arr[7] = substr($body,56,6);   
	$arr[8] = substr($body,62,10);  
	$arr[9] = substr($body,72,1);   
	$arr[10] = (substr($body,73,1) == '-') ? '-':'+';  
	$arr[11] = substr($body,74,10); 
	$arr[12] = substr($body,84,1);  
	$arr[13] = substr($body,85,19); 
	$arr[14] = substr($body,104,11);
	$arr[15] = substr($body,115,5); 
	$arr[16] = substr($body,120,3); 
	$arr[17] = substr($body,123,8); 
	$arr[18] = substr($body,131,9); 
	$arr[19] = substr($body,140,1); 
	$arr[20] = substr($body,141,6); 
	$arr[21] = substr($body,147,3); 
	$arr[22] = substr($body,150,80);
	$arr[23] = substr($body,230,80);
	
	$today=date("Y-m-d");
	$now=date("Y-m-d H:i:s");
	$ip = getIP();
	
	
	
	
	
	$virtualAccount = $arr[15].trim($arr[4]);
	
	
	try{
		$db->setQuery("insert into orderVatmLog (type,MsgID,TransactionNo,vAtmNo,createTime,ip,responseData)
		values ('Return','$MsgID','$TransactionNo','$virtualAccount','".date("Y-m-d H:i:s")."','$ip','".json_encode($arr)."')");
		$db->query();
	}catch(Exception $e){

	}
	
	
	if($MsgID == '2')
	{
		
		$cnt = intval(getFieldValue(" SELECT COUNT(1) as cnt FROM orderVatmLog WHERE TransactionNo = '$TransactionNo' ","cnt"));
		if($cnt == 1)
		{
			$totalAmt = substr($arr[5],-2); 
	
			
			$sql2 = "SELECT * FROM orders WHERE virtualAccount = '$virtualAccount'";
			$db->setQuery($sql2);
			$r2 = $db->loadRow();
			$oid = $r2['id'];
			
			if(!empty($oid))
			{
				$orderNum = $r2['orderNum'];
				$orderMode = $r2['orderMode'];
				
				if($orderMode == 'addMember')
				{
					
					
					$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '".$r2['memberid']."' ","loginid");
					$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '".$r2['memberid']."' ","sid");
					$name = getFieldValue(" SELECT name FROM members WHERE id = '".$r2['memberid']."' ","name");
					$email = getFieldValue(" SELECT email FROM members WHERE id = '".$r2['memberid']."' ","email");
					$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '".$r2['memberid']."' ","ERPID");
					
					
					$sql_tmp ="update members set ".
					" salesChk        = 1 , ".
					" payDate        ='$today' ".
					" where id='{$r2['memberid']}' ";
					
					$db->setQuery( $sql_tmp );
					$db->query();
				}
				
				
				$sql3 ="update orders set ".
				" status        ='9' ,".
				" mtime 		='$now',".
				" finalPayDate 		='$now'".
				" where id=$oid ";
				
				$db->setQuery( $sql3 );
				$db->query();
				
				$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$oid','$today','9','$now','$now','".$r2['memberid']."');";
				$db->setQuery( $sql );
				$db->query();
				$sus = $RtnMsg;
				
				$buyDate=getFieldValue("select buyDate from orders where memberid='{$r2['memberid']}' AND id='$oid'","buyDate");
				if(strtotime(date("Y-m-d"))-strtotime($buyDate)>432000){
					$db->setQuery("update members set delayCnt=delayCnt+1 where id='{$r2['memberid']}'");
					$db->query();
				}
				
				
				
				if($r2['pointchk'] == '0')
				{
					$sql="update members set pv=pv+'{$r2['pv']}',bv=bv+'{$r2['bv']}',bonus=bonus+'{$r2['bonus']}' where id='{$r2['memberid']}';";
					$db->setQuery($sql);
					$db->query();
					
					$sql="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
							values ('{$r2['memberid']}','$today','{$r2['bonus']}',0,'$oid','$now','$now','{$r2['memberid']}');";
					$db->setQuery($sql);
					$db->query();
					
					$sql="update orders set pointchk=1 where id='$oid';";
					$db->setQuery($sql);
					$db->query();
				}
				
				$sql = "select * from siteinfo where sysid ='$sysid' ";

				$db->setQuery( $sql );
				$siteinfo_arr = $db->loadRow();
				
				$from = $siteinfo_arr['email'];
				$fromname = $siteinfo_arr['name'];
				
				
				$sendto = array(array("email"=>$from,"name"=>$fromname));
				$subject = $fromname." - "._CART_PAY_SUCCESS_MSG1." (".date("Y-m-d H:i:s").")";
				$body = "
				<html>
				<head>
					<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
					<title>$fromname "._CART_PAY_SUCCESS_MSG2."</title>

				</head>
				<body style=\"margin:0;padding:0;\">
					<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
						<h3 style=\"letter-spacing:1px;\">"._CART_PAY_SUCCESS_MSG3."</h3>
						<p style=\"line-height:180%;\">"._CART_PAY_SUCCESS_MSG4."</p>
						<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
						<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
							<tbody>
								<tr>
									<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._CART_PAY_SUCCESS_MSG5."</strong></td>
									<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
								</tr>
							</tbody>
						</table>
						<br>
						<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
						"._CART_PAY_SUCCESS_MSG6."{$siteinfo_arr['tel']}&emsp;&emsp;"._CART_PAY_SUCCESS_MSG7."{$siteinfo_arr['addr']}<br>
						"._CART_PAY_SUCCESS_MSG8."{$siteinfo_arr['email']}</p>
						
					</div>
				</body>
				</html>
				";
				
				$rs = global_send_mail("eways100@gmail.com","威誠購物平台",$sendto,$subject,$body);
				
				if($orderMode == 'addMember')
				{
					
					sendMailToMemberBySignupSuccess($r2['memberid']);
				}
								
				http_response_code(200);
				die();
			}
			else	
			{
				
				http_response_code_output(543,'ordersNumber Error');
				die();
			}
			
		}
		else	
		{
			
			http_response_code_output(250,'Repeat TransactionNo');
			die();
		}
	}
	else	
	{
		
		http_response_code_output(543,'MsgID Error');
		die();
	}
	
	
	http_response_code_output(543,'webNet Error');
	die();
}


function getReturn_tspg()
{
	global $db,$conf_user,$tablename,$conf_php,$real_domain,$HTTP_X_FORWARDED_PROTO;
	$data = json_decode(file_get_contents('php://input'),true);
	
	$today=date("Y-m-d");
	$now=date("Y-m-d H:i:s");
	
	$orderNum = $data['params']['order_no'];
	$ip = getIP();
	$ret_code = $data['params']['ret_code'];
	$last_4_digit_of_pan = $data['params']['last_4_digit_of_pan'];
	
	
	try{
		$db->setQuery("insert into orderTspgLog (type,orderNum,createTime,ip,sendData,responseData,retCode,last_4_digit_of_pan)
		values ('Return','$orderNum','".date("Y-m-d H:i:s")."','$ip','','".json_encode($data)."','$ret_code','$last_4_digit_of_pan')");
		$db->query();
	}catch(Exception $e){

	}
		
	$orderNum = substr($data['params']['order_no'],0,-2);
	$orderNum = str_replace("1S010","1S010-",$orderNum);
	$orderNum = str_replace("3S010","3S010-",$orderNum);
	$orderNum = str_replace("3U010","3U010-",$orderNum);
	
	
	$sql2 = "SELECT * FROM orders WHERE orderNum = '$orderNum'";
	$db->setQuery($sql2);
	$r2 = $db->loadRow();
	$oid = $r2['id'];
	
	
	$tmpStr = substr($data['params']['order_no'],-2);
	$orderMode = $r2['orderMode'];
	$addMemberFirst = ($orderMode == 'addMember' && $tmpStr == '01') ? true : false;
	
	
	
	if ($ret_code == "00")
	{
		if($orderMode == 'addMember')
		{
			
			
			$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '".$r2['memberid']."' ","loginid");
			$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '".$r2['memberid']."' ","sid");
			$name = getFieldValue(" SELECT name FROM members WHERE id = '".$r2['memberid']."' ","name");
			$email = getFieldValue(" SELECT email FROM members WHERE id = '".$r2['memberid']."' ","email");
			$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '".$r2['memberid']."' ","ERPID");
			
			
			$sql_tmp ="update members set ".
			" salesChk        = 1 , ".
			" payDate        ='$today' ".
			" where id='{$r2['memberid']}' ";
			
			$db->setQuery( $sql_tmp );
			$db->query();
		}
		
		
		$sql3 ="update orders set ".
		" status        ='1' ,".
		" mtime 		='$now',".
		" finalPayDate 		='$now'".
		" where id=$oid ";
		
		$db->setQuery( $sql3 );
		$db->query();
		
		$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$oid','$today','1','$now','$now','".$r2['memberid']."');";
		$db->setQuery( $sql );
		$db->query();
		$sus = $RtnMsg;
		
		$buyDate=getFieldValue("select buyDate from orders where memberid='{$r2['memberid']}' AND id='$oid'","buyDate");
		if(strtotime(date("Y-m-d"))-strtotime($buyDate)>432000){
			$db->setQuery("update members set delayCnt=delayCnt+1 where id='{$r2['memberid']}'");
			$db->query();
		}
		
		
		
		if($r2['pointchk'] == '0')
		{
			$sql="update members set pv=pv+'{$r2['pv']}',bv=bv+'{$r2['bv']}',bonus=bonus+'{$r2['bonus']}' where id='{$r2['memberid']}';";
			$db->setQuery($sql);
			$db->query();
			
			$sql="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
					values ('{$r2['memberid']}','$today','{$r2['bonus']}',0,'$oid','$now','$now','{$r2['memberid']}');";
			$db->setQuery($sql);
			$db->query();
			
			$sql="update orders set pointchk=1 where id='$oid';";
			$db->setQuery($sql);
			$db->query();
		}
		
		
		$sql = "select * from siteinfo where sysid ='$sysid' ";

		$db->setQuery( $sql );
		$siteinfo_arr = $db->loadRow();
		
		$from = $siteinfo_arr['email'];
		$fromname = $siteinfo_arr['name'];
		
		
		$sendto = array(array("email"=>$from,"name"=>$fromname));
		$subject = $fromname." - "._CART_PAY_SUCCESS_MSG1." (".date("Y-m-d H:i:s").")";
		$body = "
		<html>
		<head>
			<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
			<title>$fromname "._CART_PAY_SUCCESS_MSG2."</title>

		</head>
		<body style=\"margin:0;padding:0;\">
			<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
				<h3 style=\"letter-spacing:1px;\">"._CART_PAY_SUCCESS_MSG3."</h3>
				<p style=\"line-height:180%;\">"._CART_PAY_SUCCESS_MSG4."</p>
				<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
				<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
					<tbody>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._CART_PAY_SUCCESS_MSG5."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
						</tr>
					</tbody>
				</table>
				<br>
				<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
				"._CART_PAY_SUCCESS_MSG6."{$siteinfo_arr['tel']}&emsp;&emsp;"._CART_PAY_SUCCESS_MSG7."{$siteinfo_arr['addr']}<br>
				"._CART_PAY_SUCCESS_MSG8."{$siteinfo_arr['email']}</p>
				
			</div>
		</body>
		</html>
		";
		
		$rs = global_send_mail("eways100@gmail.com","威誠購物平台",$sendto,$subject,$body);
		
		if($orderMode == 'addMember')
		{
			
			sendMailToMemberBySignupSuccess($r2['memberid']);
		}
		
		if($addMemberFirst)
		{
			
			$_SESSION[$conf_user]=array();
			unset($_SESSION[$conf_user]);
		}
	}
	
	die();
}


function backViewAJAX_tspg()
{
	global $db,$conf_user,$tablename,$conf_php,$real_domain,$HTTP_X_FORWARDED_PROTO;
	$id = global_get_param( $_REQUEST, 'id', null ,0,1);
	
	if(!empty($id))
	{
		$orderNum = substr($id,0,-2);
		$orderNum = str_replace("1S010","1S010-",$orderNum);
		$orderNum = str_replace("3S010","3S010-",$orderNum);
		$orderNum = str_replace("3U010","3U010-",$orderNum);
		
		$status = getFieldValue(" SELECT status FROM orders WHERE orderNum = '$orderNum' ","status");
		
		if($status == '1')	
		{
			die("susses");
		}
		else if($status == '0') 
		{
			$retCode = getFieldValue(" SELECT retCode FROM orderTspgLog WHERE orderNum = '$id' ","retCode");
			if(!empty($retCode))
			{
				die("fail");
			}
			else
			{
				die("empty");
			}
		}
		else 
		{
			die("fail");	
		}
	}
	die("empty");
}


function backView_tspg()
{
	global $HTTP_X_FORWARDED_PROTO;
	
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1);
	
	
	$orderNum = substr($id,0,-2);
	$orderNum = str_replace("1S010","1S010-",$orderNum);
	$orderNum = str_replace("3S010","3S010-",$orderNum);
	$orderNum = str_replace("3U010","3U010-",$orderNum);
	$oid = getFieldValue(" SELECT id FROM orders WHERE orderNum = '$orderNum' ","id");
	
	header("Content-Type:text/html; charset=utf-8");
			
	?>
	<script src="<?=$HTTP_X_FORWARDED_PROTO?>://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js "></script>
	<script language=javascript>
	var t=null;
	$(function(){		
		t=setInterval(backViewAJAX_tspg,1000);
		
	});
	
	
	function backViewAJAX_tspg()
	{
		
		$.post("api.php", { task: "backViewAJAX_tspg" , inner: "1" , id: "<?=$id?>" })
		.done(function(data) { 
			if(data == 'fail')
			{
				clearInterval(t);
				alert('<?=_CART_PAY_ERROR_MSG1?>');
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/".$oid ?>';
			}
			else if(data == 'susses')
			{
				clearInterval(t);
				alert('<?=_CART_PAY_SUCCESS?>');
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/".$oid ?>';
			}
			else
			{
				
			}
		}).fail(function() { 
			alert("ERR"); 
		});
		
	}
	
	
	</script> 
	<?
	
	die();
}

function order_submit(){
	ini_set('display_errors','1');
	global $db,$conf_user,$tablename,$globalConf_disable_day,$conf_php,$conf_instock_mode,$db3;
	
	$mode=getCartMode();
	$uid=LoginChk();
	$uemail=$_SESSION[$conf_user]['uemail'];
	$uname=$_SESSION[$conf_user]['uname'];
	$umobile=$_SESSION[$conf_user]['umobile'];
	$uaddress=$_SESSION[$conf_user]['uaddress'];
	$ucanton=$_SESSION[$conf_user]['ucanton'];
	$ucity=$_SESSION[$conf_user]['ucity'];

	
	
	$name = global_get_param( $_POST, 'name', null ,0,1);
	$mobile = global_get_param( $_POST, 'mobile', null ,0,1);
	$email = global_get_param( $_POST, 'email', null ,0,1);
	$address = global_get_param( $_POST, 'address', null ,0,1);
	$dlvrDate = global_get_param( $_POST, 'dlvrDateD', null ,0,1);
	$dlvrTime = global_get_param( $_POST, 'dlvrTime', null ,0,1);
	$invoiceType = global_get_param( $_POST, 'invoiceType', null ,0,1);
	$invoiceSN = global_get_param( $_POST, 'invoiceSN', null ,0,1);
	$invoiceTitle = global_get_param( $_POST, 'invoiceTitle', null ,0,1);
	// $canton = global_get_param( $_POST, 'cantonCode', null ,0,1);
	$city = global_get_param( $_POST, 'cityCode', null ,0,1);
	
	$invoice = global_get_param( $_POST, 'invoice', null ,0,1);
	if(empty($invoice))
	{
		$invoice=getFieldValue("select invoice from siteinfo","invoice");
		
	}
	
	$notes = global_get_param( $_POST, 'notes', null ,0,1);
	if(!$name || !$mobile || ( !$address && $_SESSION[$conf_user]['pay_type'] != '5' )){
		JsonEnd(array("status"=>0,"msg"=>'請填寫收貨人資訊'));
	}
	$today=date("Y-m-d");
	$now=date("Y-m-d H:i:s");
	
	$bill = array();
	$bill['address'] = $bill_address = global_get_param( $_POST, 'bill_address', null ,0,1);
	$bill_city_arr = global_get_param( $_POST, 'bill_city', null ,0,1);
	$bill['city'] = $bill_city = $bill_city_arr['state_u'];
	$bill['zip'] = $bill_zip = global_get_param( $_POST, 'bill_zip', null ,0,1);
	$_SESSION[$conf_user]['bill_info'] = $bill;


	if(empty($bill_address)){
		$bill_address = $address;
	}

	if(empty($bill_city)){
		$bill_city = $ucity;
	}

	
	
	
	if($_SESSION[$conf_user]['pay_type']==1){
		$status=2;
	}else{
		$status=0;
	}
	
	
	$proArr=$_SESSION[$conf_user]['proArr'];
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    
	if(count($proArr)==0 && count($activeBundleCart)==0){
		JsonEnd(array("status"=>0,"msg"=>_CART_EMPTY));
	}
	
	$proData = cartProductClac( $proArr['active_list'], $proArr['data'], $proArr['activeExtraList']);
	
	
	if($mode=="bonus"){
		$bonusAmt=$_SESSION[$conf_user]['totalAmt'];
		$_SESSION[$conf_user]['totalAmt']=0;
	}

	$finalccv = floatval($proArr['totalccv']) - floatval($proArr['discount']);

	$sisql = "SELECT * from siteinfo";
	$db->setQuery($sisql);
	$siteinfo = $db->loadRow();

	$use_p = $_SESSION[$conf_user]['use_p'];
	$use_points = $_SESSION[$conf_user]['use_points'];
	if($use_p == 0){
		$use_points = '0';
	}

	$cb_use_p = $_SESSION[$conf_user]['cb_use_p'];
	$cb_use_points = $_SESSION[$conf_user]['cb_use_points'];

	if($cb_use_p == 0){
		$cb_use_points = '0';
	}


	$taxrate = $siteinfo['taxrate'] / 100;
	
	$total=$_SESSION[$conf_user]['totalAmt']+$_SESSION[$conf_user]['realDlvrAmt'];
	$tax_fee = $total * $taxrate;
	$total = $total * (1+$taxrate);

	$total = c_round($total,2);
    
	$sql = "select coin_to,coin_take from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$coin_to=$r['coin_to'];
	$coin_take=$r['coin_take'];
	$free_coin=0;
	if($coin_to){
		$free_coin=floor($_SESSION[$conf_user]['totalAmt']/$coin_to)*$coin_take;
	}

	$use_p = $_SESSION[$conf_user]['use_p'];
	$use_points = $_SESSION[$conf_user]['use_points'];

	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now_date = date('Y-m-d');
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind and p.active_date <= '$now_date'";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	foreach ($plist as $each) {
		if($each['p_type'] == '1'){
			$now_points = bcadd($now_points,$each['point'],2);
		}else if($each['p_type'] == '2'){
			$now_points = bcsub($now_points,$each['point'],2);
		}
	}
	if($now_points < $use_points){
		JsonEnd(array("status"=>0,"msg"=>_POINTS_NOT_ENOUGH));
	}
	
	$sql="BEGIN;";
	if($mode=="cart" || $mode = 'twcart'){
		
		$sql.="insert into orders 
			(orderMode,memberid,email,buyDate,payType,dlvrType,status,sumAmt,discount,dcntAmt,dlvrFee,usecoin,totalAmt,ccv,taxrate,taxfee,dlvrName,
			dlvrMobile,dlvrCanton,dlvrCity,dlvrAddr,dlvrDate,dlvrTime,dlvrNote,invoiceType,invoiceTitle,invoiceSN,invoice,ctime,mtime,muser,use_p,use_points,cb_use_p,cb_use_points,bill_address,bill_city,bill_zip)
			values 
			('$mode','$uid','$uemail','$today','{$_SESSION[$conf_user]['pay_type']}','{$_SESSION[$conf_user]['take_type']}',$status,'{$proArr['total']}',
			 '{$proArr['discount']}','".($proArr['total']-$proArr['discount'])."','{$_SESSION[$conf_user]['realDlvrAmt']}','{$_SESSION[$conf_user]['usecoin']}','$total','$finalccv','$taxrate','$tax_fee',N'$name','$mobile','$ucanton','$ucity',N'$address',
			 '$dlvrDate','$dlvrTime','$notes','$invoiceType','$invoiceTitle','$invoiceSN','$invoice','$now','$now','$uid','$use_p','$use_points','$cb_use_p','$cb_use_points','$bill_address','$bill_city','$bill_zip');";
		
		$sql.="
			SET @insertid=LAST_INSERT_ID();
		";	 
		
	}else if($mode=="bonus"){
		
		$userbonus=intval(getFieldValue("select bonus from members where id='$uid'","bonus"));
		if($userbonus<$bonusAmt){
			JsonEnd(array("status"=>0,"msg"=>'您的紅利不足'));
		}
		
		
		$sql.="insert into orders 
		(orderMode,memberid,email,buyDate,payType,dlvrType,status,discount,dcntAmt,dlvrFee,usecoin,totalAmt,ccv,taxrate,taxfee,bonusAmt,dlvrName,
			dlvrMobile,dlvrCanton,dlvrCity,dlvrAddr,dlvrDate,dlvrTime,dlvrNote,invoiceType,invoiceTitle,invoiceSN,invoice,ctime,mtime,muser)
			values 
			('$mode','$uid','$uemail','$today','{$_SESSION[$conf_user]['pay_type']}','{$_SESSION[$conf_user]['take_type']}',$status,
			 '{$proArr['discount']}','0','{$_SESSION[$conf_user]['realDlvrAmt']}','{$_SESSION[$conf_user]['usecoin']}','$total','$finalccv','$taxrate','$tax_fee','$bonusAmt',N'$name','$mobile','$ucanton','$ucity',N'$address',
			 '$dlvrDate','$dlvrTime','$notes','$invoiceType','$invoiceTitle','$invoiceSN','$invoice','$now','$now','$uid');";
		
		$sql.="update members set bonus=bonus-'$bonusAmt' where id='$uid';";
		
		
		$sql.="
			SET @insertid=LAST_INSERT_ID();
		";
		
		$sql.="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
				values ('$uid','$today','$bonusAmt',1,@insertid,'$now','$now','$uid');";
	}
	
	
	
	if(count($proArr['data'])==0 && count($activeBundleCart)==0){
		JsonEnd(array("status"=>0,"msg"=>_CART_EMPTY));
	}
	$totalPv=0;
	$totalBv=0;
	$totalBonus=0;
	
	
	$cart_quantity_array = array();
	
	foreach($proArr['data'] as $key=>$pro){
		$pid=$pro['id'];
		$quantity=$pro['num'];
		$unitAmt=$pro['siteAmt'];
		$subAmt=$pro['CalcSiteAmt'];
		$bonus=$pro['bonus'];
		$pv=(!empty($proData[$key]['prodtl_pv']) || $proData[$key]['prodtl_pv'] == '0') ? $proData[$key]['prodtl_pv'] : $pro['pv'];
		$bv=(!empty($proData[$key]['prodtl_bv']) || $proData[$key]['prodtl_bv'] == '0') ? $proData[$key]['prodtl_bv'] : $pro['bv'];
		$totalPv+=$pv;
		$totalBv+=$bv;
		$totalBonus+=$bonus;
		$protype = $pro['protype'];
		
		$format1 = $pro['format1'];
		$format1name = $pro['format1name'];
		$format2 = $pro['format2'];
		$format2name = $pro['format2name'];
		$unitCcv = $pro['unitCcv'];
		$ccvAmt = $pro['CalcCcv'];
		
		if($mode=="cart"){
			$sql.="insert into orderdtl (oid,pid,unitAmt,quantity,subAmt,pv,bv,bonus,protype,format1,format2,format1name,format2name,ctime,mtime,muser,unitCcv,ccvAmt)
			     values 
			     (@insertid,'$pid','$unitAmt','$quantity','$subAmt','$pv','$bv','$bonus','$protype','$format1','$format2','$format1name','$format2name','$now','$now','$uid','$unitCcv','$ccvAmt');";
		}else{
			$sql.="insert into orderdtl (oid,pid,quantity,bonusAmt,format1,format2,format1name,format2name,ctime,mtime,muser)
			     values 
			     (@insertid,'$pid','$quantity','$subAmt','$format1','$format2','$format1name','$format2name','$now','$now','$uid');";
		}
		
		
		
		
		if(count($proData) > 0)
		{
			
			$sql.="
				SET @insertdtlid=LAST_INSERT_ID();
			";
			
			foreach($proData as $r)
			{
				if($r['fid'] == $pro['fid'])
				{
					if(count($r['prodtl']) > 0)
					{
						for($i = 0 ; $i < $quantity ; $i++)
						{
							$sql.="insert into orderprodtl (oid,odid,pid,amt,pv,bv,note,ctime,mtime,muser)
						     values 
						     (@insertid,@insertdtlid,'$pid','".$r['prodtl']['amt'][$i]."','".$r['prodtl']['amt_pv'][$i]."','".$r['prodtl']['amt_bv'][$i]."','".$r['prodtl_act']."','$now','$now','$uid');";
						}
					}
				}
			}
		}
		
		
		
		$stockChk=intval(getFieldValue("select stockChk from products where id='$pid'","stockChk"));
		if($stockChk==1 && false){
			$instock=intval(getFieldValue("select instock from products where id='$pid'","instock"));
			if($quantity-$instock>0){
				$pname=getFieldValue("select name from products where id='$pid'","name");
				JsonEnd(array("status"=>0,"msg"=>$pname.' '._CART_INSTOCK_ERROR_MSG));
			}
		}
		
		if($conf_instock_mode=="single" || !$conf_instock_mode){
			$sql.="update products set instock=instock-'$quantity' where id='$pid';";
		}
		
		
		if(!empty($format1) && !empty($format2))
		{
			$format_instock=intval(getFieldValue("select instock from proinstock where pid='$pid' AND format1 = '$format1' AND format2 = '$format2'","instock"));
			if(($quantity - $format_instock > 0) && false)
			{
				$pname=getFieldValue("select name from products where id='$pid'","name");
				JsonEnd(array("status"=>0,"msg"=>$pname.' '._CART_INSTOCK_ERROR_MSG));
			}
			else {
				
				
				$format_proinstock_instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instockchk");
				$format_proinstock_instock = getFieldValue(" SELECT instock FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instock");
				
				
				$cart_quantity_array[$pid."||".$format1."||".$format2] = $quantity;
				
				if($format_proinstock_instockchk == 1 && ($quantity - $format_proinstock_instock > 0))
				{
					$pname=getFieldValue("select name from products where id='$pid'","name");
					JsonEnd(array("status"=>0,"msg"=>$pname.' '._CART_INSTOCK_ERROR_MSG));
				}
				else
				{
					$sql.="update proinstock set instock=instock-'$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
				}
			}
		}
		
	}
	
	$totalBonus = $proArr['bonus'];
    
    if($activeBundleCart && count($activeBundleCart)>0){
        
		
		$activeBundle_passwordText_array = array();
		foreach($activeBundleCart as $key=>$value){
			
			foreach($value['activeBundleDetail'] as $activeBundleDetail){
				$_products=$activeBundleDetail['products'];
				$cart_quantity_array[$_products['productId']."||".$_products['spec'][1]['id']."||".$_products['spec'][2]['id']] += 1;
			}
			
			if($value['passwordCheck'] == '1')
			{
				$activeBundle_passwordText_array[] = $value['passwordText'];
			}
			
		}
		foreach($cart_quantity_array as $key=>$quantity)
		{
			$key_arr = explode("||",$key);
			$pid = $key_arr[0];
			$format1 = $key_arr[1];
			$format2 = $key_arr[2];
			
			
			$format_proinstock_instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instockchk");
			$format_proinstock_instock = getFieldValue(" SELECT instock FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instock");
			
			if($format_proinstock_instockchk == 1 && ($quantity - $format_proinstock_instock > 0))
			{
				$pname=getFieldValue("select name from products where id='$pid'","name");
				JsonEnd(array("status"=>0,"msg"=>$pname.' '._CART_INSTOCK_ERROR_MSG));
			}
		}
		
		
		if(count($activeBundle_passwordText_array) > 0)
		{
			foreach($activeBundle_passwordText_array as $passwordText)
			{
				if(strripos($notes,$passwordText)===false){
					if(empty($notes))
					{
						
						JsonEnd(array("status"=>0,"msg"=>_CART_PASSWORDTEXT_ERROR_MSG1));
					}
					else
					{
						
						JsonEnd(array("status"=>0,"msg"=>_CART_PASSWORDTEXT_ERROR_MSG2));
					}
                }
			}
		}
				
		$activeBundlePrice=0;
        $activeBundlePv=0;
        $activeBundleBv=0;
        foreach($activeBundleCart as $key=>$value){

            $activeBundlePrice+=$value['price'];
            $activeBundlePv+=$value['pv'];
            $activeBundleBv+=$value['bv'];
            $sql.="insert into orderBundle 
            (orderId,activeBundleId,activeBundleName,price,pv,bv,createTime,updateTime,updateUserId) 
            values 
            (@insertid,'{$value['id']}','{$value['name']}','{$value['price']}','{$value['pv']}','{$value['bv']}','$now','$now','$uid');";
            
            $sql.="
                SET @insertOrderBundleId=LAST_INSERT_ID();
            ";
            foreach($value['activeBundleDetail'] as $activeBundleDetail){
                $_products=$activeBundleDetail['products'];
                $sql.="insert into orderBundleDetail
                (orderBundleId,activeBundleDetailSequence,activeBundleDetailName,quantity,productId,productName,productSpecName,productFormat1,productFormat2,price,pv,bv,createTime,updateTime,updateUserId)
                values
                (@insertOrderBundleId,'{$activeBundleDetail['sequence']}','{$activeBundleDetail['name']}','1','{$_products['productId']}','{$_products['productName']}'
                ,'{$_products['selectedSpecName']}','{$_products['spec'][1]['id']}','{$_products['spec'][2]['id']}','{$_products['siteAmt']}','{$_products['pv']}'
                ,'{$_products['bv']}','$now','$now','$uid');";

                $sql.="update proinstock set instock=instock-1 where pid='{$_products['productId']}' AND format1 = '{$_products['spec'][1]['id']}' AND format2 = '{$_products['spec'][2]['id']}';";
            }
        }
        $totalPv+=$activeBundlePv;
        $totalBv+=$activeBundleBv;
    }
    
	if($totalPv || $totalBv || $totalBonus){
		$sql.="update orders set pv='$totalPv',bv='$totalBv',bonus='$totalBonus' where id=@insertid;";
	}
	
	if(count($proArr['active_list'])>0){
		foreach($proArr['active_list'] as $act){
            if($act['act']['passwordChk']==1){
                if(strripos($notes,$act['act']['passwordText'])===false){
					if(empty($notes))
					{
						
						JsonEnd(array("status"=>0,"msg"=>_CART_PASSWORDTEXT_ERROR_MSG1));
					}
					else
					{
						
						JsonEnd(array("status"=>0,"msg"=>_CART_PASSWORDTEXT_ERROR_MSG2));
					}
                }
            }
			$usepro='';
			if(count($act['usepro'])>0){
				$usepro='||'.implode("||",$act['usepro'])."||";
			}
			$sql.="insert into activeRecord (memberid,orderid,activeid,activePlanid,notes,pid,amt,discount,times,ctime,mtime,muser)
			     values 
			     ('$uid',@insertid,'{$act['id']}','{$act['act']['activePlanid']}','{$act['name']}','$usepro','{$act['amt']}','{$act['discount']}','{$act['effectiveTime']}','$now','$now','$uid');";
		}
    }
	
	
	$toMonth = date("Y-m");
	$orderCodeName=getFieldValue("select codeName_chs from pubcode where codeKinds='orderseq'","codeName_chs");
	if($orderCodeName==$toMonth){
		$orderseq=intval(getFieldValue("select codeName_en from pubcode where codeKinds='orderseq'","codeName_en"))+1;
		$db->setQuery("update pubcode set codeName_en='$orderseq' where codeKinds='orderseq'");
		$r=$db->query();
	}else{
		$db->setQuery("update pubcode set codeName_en=1,codeName_chs='$toMonth' where codeKinds='orderseq'");
		$r=$db->query();
		$orderseq=1;
	}
	
	$orderseqStr = "";
	if($orderseq<10){
		$orderseqStr="000".$orderseq;
	}else if($orderseq<100){
		$orderseqStr="00".$orderseq;
	}else if($orderseq<1000){
		$orderseqStr="0".$orderseq;
	}else if($orderseq<10000){
		$orderseqStr="".$orderseq;
	}
	
	
	if(strtotime($today) >= strtotime('2021-07-15'))
	{
		$orderNum = "3S010-".date("ym").$orderseqStr;
	}else if(strtotime($today) >= strtotime('2019-10-30')){
		$orderNum = "3S010-".date("ym").$orderseqStr;
	}else{
		$orderNum = "1S010-".date("ym").$orderseqStr;
	}
	$now_date = date('Y-m-d');

	$p_data = array();
	$p_data['orderNum'] = $orderNum;
	$p_data['mb_no'] = $mb_no;
	$p_data['point'] = $use_points;
	$p_data['provide_date'] = date('Y-m-d');
	$p_data['kind'] = '2';
	$p_data['consume_1'] = 0;
	$p_data['consume_2'] = 0;
	$p_data['active_date'] = $now_date;
	$p_data['expiry_date'] = $now_date;
	$p_data['provide_date'] = $now_date;

	$this_year = date('Y');
	
	$s_point = 0;
	$u1_point = 0;
	$u2_point = 0;


	$tsql = "SELECT SUM(point) as s_point from points as p, point_kind as pk where is_invalid = '0' and expiry_date like '$this_year%' and pk.kind = p.kind and pk.type = '1' and p.active_date <= '$now_date' and p.mb_no = '$mb_no'";
	$db3->setQuery($tsql);
	$this_list = $db3->loadRow();
	if(!empty($this_list['s_point'])){
		$s_point = $this_list['s_point']; //今年所得到的所有點數
	}
	

	$u1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u1sql);
	$u1_list = $db3->loadRow();
	if(!empty($u1_list['u1_point'])){
		$u1_point = $u1_list['u1_point'];
	}
	

	$u2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$this_year' and pk.kind = p.kind and pk.type = '2' and p.mb_no = '$mb_no'";
	$db3->setQuery($u2sql);
	$u2_list = $db3->loadRow();

	if(!empty($u2_list['u2_point'])){
		$u2_point = $u2_list['u2_point'];
	}
	
	$ss_point = bcadd($u1_point,$u2_point,2);
	$t_point = bcsub($s_point,$ss_point,2); //今年可用

	if($t_point >= $use_points){
		$this_use_point = $use_points;
		$c_point = 0;
	}else if($t_point < $use_points){
		$this_use_point = $t_point;
		$c_point = bcsub($use_points,$t_point,2);//扣除後剩下使用點數
	}
	$next_year = date('Y', strtotime('+1 year'));
	if($c_point > 0){
		
	
		// $ns_point = 0;
		// $nu1_point = 0;
		// $nu2_point = 0;
	
	
		// $ntsql = "SELECT SUM(point) as s_point from points as p, point_kind as pk where is_invalid = '0' and expiry_date like '$next_year%' and pk.kind = p.kind and pk.type = '1'";
		// $db3->setQuery($ntsql);
		// $next_list = $db3->loadRow();
		// if(!empty($next_list)){
		// 	$ns_point = $next_list['s_point']; //今年所得到的所有點數
		// }
		
	
		// $nu1sql = "SELECT SUM(deduct_point_1) as u1_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_1 = '$next_year' and pk.kind = p.kind and pk.type = '2'";
		// $db3->setQuery($nu1sql);
		// $nu1_list = $db3->loadRow();
		// if(!empty($nu1_list)){
		// 	$nu1_point = $nu1_list['u1_point'];
		// }
		
	
		// $nu2sql = "SELECT SUM(deduct_point_2) as u2_point from points as p, point_kind as pk where is_invalid = '0' and deduct_year_2 = '$next_year' and pk.kind = p.kind and pk.type = '2'";
		// $db3->setQuery($nu2sql);
		// $nu2_list = $db3->loadRow();
	
		// if(!empty($nu2_list)){
		// 	$nu2_point = $nu2_list['u2_point'];
		// }
		
	
		// $nt_point = $ns_point - $nu1_point - $nu2_point;
	
		// if($nt_point > 0){
		// 	$next_use_point = $nt_point;
		// }else{
		// 	$next_use_point = 0;
		// }
		$next_use_point = $c_point;
	}else{
		$next_use_point = 0;
	}
	
	


	if(!empty($c_point) && $t_point != '0.00'){
		$p_data['deduct_year_1'] = $this_year; //要改
		$p_data['deduct_point_1'] = $this_use_point;
		$p_data['deduct_year_2'] = $next_year; //要改
		$p_data['deduct_point_2'] = $next_use_point;
	}else if(!empty($c_point) && $t_point == '0.00'){
		$p_data['deduct_year_1'] = $next_year; //要改
		$p_data['deduct_point_1'] = $next_use_point;
	}else{
		$p_data['deduct_year_1'] = $this_year; //要改
		$p_data['deduct_point_1'] = $this_use_point;
	}
	
	
	
	$i_sql = dbInsert('points',$p_data);
	$db3->setQuery($i_sql);
	$db3->query();

	// $res = array();
	// $res['t_total'] = $t_point;
	// $res['t_t'] = empty($t_point);
	// $res['c_total'] = $c_point;
	// $res['s_total'] = $s_point;
	// $res['u'] = $use_points;

	// JsonEnd($res);
	
	
	$orderCodeName=getFieldValue("select codeName from pubcode where codeKinds='orderseq'","codeName");
	if($orderCodeName==$today){
		$orderseq=intval(getFieldValue("select codeValue from pubcode where codeKinds='orderseq'","codeValue"))+1;
		$db->setQuery("update pubcode set codeValue='$orderseq' where codeKinds='orderseq'");
		$r=$db->query();
	}else{
		$db->setQuery("update pubcode set codeValue=1,codeName='$today' where codeKinds='orderseq'");
		$r=$db->query();
		$orderseq=1;
	}
	$orderseqStr2 = "";
	if($orderseq<10){
		$orderseqStr2="0000".$orderseq;
	}else if($orderseq<100){
		$orderseqStr2="000".$orderseq;
	}else if($orderseq<1000){
		$orderseqStr2="00".$orderseq;
	}else if($orderseq<10000){
		$orderseqStr2="0".$orderseq;
	}
	
	
	$orderECNum = date("Ymd").$orderseqStr2;
	
	
	$field="";
	$field2="";
	
	
	
	
	if($name && !$uname){
		
		$uname=$name;
	}
	if($email && !$uemail){
		
		$field2.=",email='$email'";
		$uemail=$email;
	}
	if($mobile && !$umobile){
		
		$field2.=",dlvrMobile='$mobile'";
	}
	if($address && !$uaddress){
		
	}
	if($city){
		
		$field2.=",dlvrCity='$city'";
	}
	if($canton){
		
		$field2.=",dlvrCanton='$canton'";
	}else{
		$canton=$ucanton;
	}
	$postcode=getFieldValue("select postcode from addrcode where id='$canton'","postcode");
	
	$field2.=",dlvrZip='$postcode'";
	
	$allcoin=getFieldValue("select coin from members where id='$uid'","coin");
	if(intval($_SESSION[$conf_user]['usecoin'])>$allcoin){
		
		JsonEnd(array("status"=>0,"msg"=>_CART_BONUS_ERROR_MSG));
	}
	$sql.="update orders set orderNum='$orderNum',orderECNum='$orderECNum',freecoin='$free_coin' $field2 where id=@insertid;";
	$sql.="update members set coin=coin-'".intval($_SESSION[$conf_user]['usecoin'])."' $field  where id='$uid';";
	$sql.="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values (@insertid,'$today','$status','$now','$now','$uid');";
	
	$sql.="COMMIT;";
	
	// JsonEnd(array('status' => '0', 'sql' => $sql));

	$db->setQuery($sql);
	$r=$db->query_batch();
	
	if(!$r){
		
		JsonEnd(array("status"=>0,"msg"=>_CART_NET_ERROR_MSG));
	}
	
	$oid=getFieldValue("select id from orders where memberid='$uid' order by id desc ","id");
	$code=md5($uid.$oid.$uid);
	$sql="insert into requestLog (ctime,memberid,code,type,var01) values ('$now','$uid','$code','orderurl','member_page/orderdtl/$oid')";
	$db->setQuery($sql);
	$db->query();
	

	$osql = "SELECT point,expiry_date from cash_back where mb_no = '$mb_no' and note = '初次發放' and kind = '0' and expiry_date > '$today'";
	$db3->setQuery($osql);
	$oresult = $db3->loadRow();
	$first_ed = date('Y-m-d');
	if (!empty($oresult)) { //如果有找到
		$first_point = $oresult['point'];
		$first_ed = $oresult['expiry_date'];
		$usql = "SELECT sum(point) as use_points from cash_back where mb_no = '$mb_no' and kind = '1' and expiry_date = '$first_ed'";
		$db3->setQuery($usql);
		$uresult = $db3->loadRow();
		if (!empty($uresult)) {
			$used_point = $uresult['use_points'];
		} else {
			$used_point = '0';
		}
	} else {
		$first_point = '0';
		$used_point = '0';
	}
	$cb_status = 0;
	$remain_fp = $first_point - $used_point; //送的剩餘
	if ($remain_fp > 0) { //如果還有
		if ($remain_fp > $cb_use_points) {
			$this_cb_use_point = $cb_use_points;
			$other_cb_use_point = '0';
			$cb_status = 1;
		} else {
			$this_cb_use_point = $remain_fp;
			$other_cb_use_point = $cb_use_points - $remain_fp;
			$cb_status = 2;
		}
	} else {
		$this_cb_use_point = $remain_fp;
		$other_cb_use_point = $cb_use_points;
		$cb_status = 0;
	}

	$cb_arr = array();
	$cb_arr['mb_no'] = $mb_no;
	// $cb_arr['point'] = ;
	// $cb_arr['expiry_date'] = ;
	// $cb_arr['note'] = ;
	$cb_arr['orderNum'] = $orderNum;
	$cb_arr['kind'] = '1';
	$cb_arr['provide_date'] = $today;
	$cb_arr['active_date'] = $today;

	// $cb_arr['remain'] = ;
	if ($cb_status == 0) {
		$cb_arr['point'] = $other_cb_use_point;
		$cb_arr['expiry_date'] = $first_ed;
		$cb_arr['note'] = '正常使用';
		$isql = dbInsert('cash_back', $cb_arr);
		$db3->setQuery($isql);
		$db3->query();
	} else if ($cb_status == 1) {
		$cb_arr['point'] = $this_cb_use_point;
		$cb_arr['expiry_date'] = $first_ed;
		$cb_arr['note'] = '使用初次點數';
		$isql = dbInsert('cash_back', $cb_arr);
		$db3->setQuery($isql);
		$db3->query();
	} else if ($cb_status == 2) {
		$cb_arr['point'] = $this_cb_use_point;
		$cb_arr['expiry_date'] = $first_ed;
		$cb_arr['note'] = '使用初次點數';
		$isql = dbInsert('cash_back', $cb_arr);
		$db3->setQuery($isql);
		$db3->query();
		$cb_arr['point'] = $other_cb_use_point;
		$cb_arr['expiry_date'] = $first_ed;
		$cb_arr['note'] = '切開正常點數';
		$isql = dbInsert('cash_back', $cb_arr);
		$db3->setQuery($isql);
		$db3->query();
	}

	
	$sql = "select * from siteinfo where sysid ='$sysid' ";

	$db->setQuery( $sql );
	$siteinfo_arr = $db->loadRow();
	
	$sql = "select * from  members where sysid ='$sysid' AND id='$uid'";
	$db->setQuery( $sql );
	$members_arr = $db->loadRow();
	
	$moneyStr = "NT.";
	if($_SESSION[$conf_user]['syslang'])
	{
		$c_lang = $_SESSION[$conf_user]['syslang'];
		$c_lang = 'ms';
		$moneyStr=getFieldValue(" SELECT moneyCode FROM langConf WHERE code = '".$c_lang."' ","moneyCode");
	}
	
	$from = $siteinfo_arr['email'];
	
	$fromname = $siteinfo_arr['name'];
	if($_SESSION[$conf_user]['syslang'])
	{
		$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
	}
	$sendto = array(array("email"=>$uemail,"name"=>$uname));
	$subject = $fromname." - "._CART_ORDER_ADD_MSG1." (".date("Y-m-d H:i:s").")";
	$body = "
	<html>
	<head>
			<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
			<title>$fromname "._CART_ORDER_ADD_MSG2."</title>

			</head>
	<body style=\"margin:0;padding:0;\">
		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
			<h3 style=\"letter-spacing:1px;\">"._CART_ORDER_ADD_MSG3." ".$uname." "._CART_ORDER_ADD_MSG4."</h3>
			<p style=\"line-height:180%;\">"._CART_ORDER_ADD_MSG5." $fromname "._CART_ORDER_ADD_MSG6."</p>
			<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
			<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
				<tbody>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG7."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG8."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$now</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG9."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$moneyStr ".number_format($total)."元</td>
					</tr>
				</tbody>
			</table>
			<br>
			<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
			"._CART_ORDER_ADD_MSG10."{$siteinfo_arr['tel']}&emsp;&emsp;"._CART_ORDER_ADD_MSG11."{$siteinfo_arr['addr']}<br>
			"._CART_ORDER_ADD_MSG12."{$siteinfo_arr['email']}</p>
			
		</div>
	</body>
	</html>
	";

	
	$rs = global_send_mail($from,$fromname,$sendto,$subject,$body);
	
	
	
	$sendto = array(array("email"=>$from,"name"=>$fromname));
	$body = "
	<html>
	<head>
			<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
			<title>$fromname "._CART_ORDER_ADD_MSG2."</title>

			</head>
	<body style=\"margin:0;padding:0;\">
		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
			<h3 style=\"letter-spacing:1px;\">"._CART_ORDER_ADD_MSG13."</h3>
			<p style=\"line-height:180%;\">"._CART_ORDER_ADD_MSG14."</p>
			<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
			<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
				<tbody>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG7."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG8."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$now</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._CART_ORDER_ADD_MSG9."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$moneyStr ".number_format($total)."元</td>
					</tr>
				</tbody>
			</table>
			<br>
			<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
			"._CART_ORDER_ADD_MSG10."{$siteinfo_arr['tel']}&emsp;&emsp;"._CART_ORDER_ADD_MSG11."{$siteinfo_arr['addr']}<br>
			"._CART_ORDER_ADD_MSG12."{$siteinfo_arr['email']}</p>

		</div>
	</body>
	</html>
	";
	
	$rs2 = global_send_mail("eways100@gmail.com","威誠購物平台",$sendto,$subject,$body);
	
	
	
	
	
	$pay_type = $_SESSION[$conf_user]['pay_type'];
	$mode=getCartMode();
	// unset($_SESSION[$conf_user]["{$mode}_list"]);
	// unset($_SESSION[$conf_user]['disDlvrAmt']);
	// unset($_SESSION[$conf_user]['pay_type']);
	// unset($_SESSION[$conf_user]['take_type']);
	// unset($_SESSION[$conf_user]['dlvrAmt']);
	// unset($_SESSION[$conf_user]['usecoin']);
	// unset($_SESSION[$conf_user]['proArr']);
	// unset($_SESSION[$conf_user]['realDlvrAmt']);
	// unset($_SESSION[$conf_user]['totalAmt']);
	// unset($_SESSION[$conf_user]["cart_list_mode"]);
    // unset($_SESSION[$conf_user]["freepro_list"]);
    // unset($_SESSION[$conf_user]['activeBundleCart']);
    // unset($_SESSION[$conf_user]['use_p']);
    // unset($_SESSION[$conf_user]['use_points']);
    $_SESSION[$conf_user]['activeBundleCart']=array();
	$_SESSION[$conf_user]['cart_addpro_list']=array();
	unset($_SESSION[$conf_user]['cart_addpro_list']);

	$orderid = getFieldValue("SELECT id FROM orders WHERE orderNum = '$orderNum'","id");
	//未付款先進傳銷訂單
	toMLM($orderid,'0');


	if($pay_type==3 || $pay_type==4){
		JsonEnd(array("status"=>'aio',"oid"=>$oid));
	}else if($pay_type==6){
		$url = '';
		$url = "/app/controllers/publicBank.php?task=orderSale&orderNum=".$orderNum;

		JsonEnd(array("status"=>'tspg',"oid"=>$oid,"orderNum" => $orderNum,"rs"=>$rs,"url"=>$url ));
	}else if($pay_type==7){
		
		
		$sql = " SELECT A.ccbPayCode FROM payconf A";
		$db->setQuery($sql);
		$payconf = $db->loadRow();
		
		$ccbPayCode = $payconf['ccbPayCode'];	
		
		$ccbPayCode = str_pad($ccbPayCode,5,'0',STR_PAD_LEFT);
		
		
		
		$orderNum_vatm = $orderNum;
		$orderNum_vatm = str_replace("1S010-","",$orderNum_vatm);
		$orderNum_vatm = str_replace("3S010-","",$orderNum_vatm);
		$orderNum_vatm = str_replace("3U010-","",$orderNum_vatm);
		
		$orderNum_vatm = str_pad($orderNum_vatm,10,'0',STR_PAD_LEFT);
		
		
		$totalAmt = "$total";
		
		if(strlen($totalAmt) >= 8 )
		{
			$totalAmt = substr($totalAmt,-8);
		}
		else
		{
			$totalAmt = str_pad($totalAmt,8,'0',STR_PAD_LEFT);
		}
		
		
		$ccbPayCode_array = str_split($ccbPayCode);
		$orderNum_vatm_array = str_split($orderNum_vatm);
		$array1 = array_merge($ccbPayCode_array, $orderNum_vatm_array);
		
		
		$array2 = str_split($totalAmt);
		
		$key_array = array(3,7,1);	
		
		
		
		
		
		
		
		$num = 0;
		foreach($array1 as $key=>$row)
		{
			$num += $row * $key_array[($key%3)];
		}
		$A = $num%10;
		
		
		$num = 0;
		foreach($array2 as $key=>$row)
		{
			$num += $row * $key_array[($key%3)];
		}
		$B = $num%10;
		
		
		$C = ($A+$B)%10;
		
		
		$checkCode = (10-$C)%10;
		
		
		$virtualAccount = "{$ccbPayCode}{$orderNum_vatm}{$checkCode}";
		
		
		$sql = "update orders set virtualAccount='$virtualAccount' where orderNum='$orderNum';";
		$db->setQuery($sql);
		$db->query();
		
		
		$deadLineDT = date("Y/m/d 23:59:59",strtotime("+4 day"));
		
		

		JsonEnd(array("status"=>'vatm',"data"=>$orderNum,"data2"=>$virtualAccount,"data3"=>$deadLineDT));
	}
	else{
		$url = '';
		$url = "/app/controllers/eghl.php?task=orderSale&orderNum=".$orderNum;
		JsonEnd(array("status"=>1,"data"=>$orderNum,"rs" => $rs, "url"=>$url));	
	}
	
}

function order_submit2($type=null){
	global $db,$conf_user,$tablename,$conf_php,$real_domain,$HTTP_X_FORWARDED_PROTO;
	
	$uid=intval($_SESSION[$conf_user]['uid']);
	
	if($uid==0){
		
		header("Location: ".$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/"); 
	}

	
	$id = global_get_param( $_POST, 'id', null ,0,1);
	
	$sql="select * from orders where id='$id' AND memberid='$uid'";
	$db->setQuery($sql);
	$r=$db->loadRow();
	$orderNum = $r['orderNum'];
	$orderNum = str_replace("1S010-","1S010",$orderNum);
	$orderNum = str_replace("3S010-","3S010",$orderNum);
	$orderNum = str_replace("3U010-","3U010",$orderNum);
	
	
	if(empty($type))
	{
		$type = ($r['payType'] == '6') ? 'tspg' : '';
	}
	
	if($type == "tspg") 
	{
		
		$sql = "SELECT orderNum FROM orderTspgLog WHERE orderNum like '$orderNum%' ORDER BY id DESC LIMIT 0,1";
	}
	else
	{
		
		$sql = "SELECT lidm AS orderNum FROM orderctblog WHERE lidm like '$orderNum%' ORDER BY id DESC LIMIT 0,1";
	}
	
	$db->setQuery($sql);
	$log=$db->loadRow();
	
	if(count($log)>0)
	{
		$last_MerchantTradeNo = $log['orderNum'];
		$index = (int)substr($last_MerchantTradeNo,-2) + 1;
		$index_str = ($index < 10) ? "0$index" : "$index";
		$orderNum = $orderNum.$index_str;
	}
	else
	{
		$orderNum = $orderNum."01";
	}
	
	
	if($type == "tspg") 
	{
		
		$ip = getIP();
		$pay_type = 1;	
		$tx_type = 1;	
		
		
		$sql = " SELECT A.tspgPayMid,A.tspgPayTid FROM payconf A";
		$db->setQuery($sql);
		$payconf = $db->loadRow();
		
		if(!empty($payconf['tspgPayMid']) && !empty($payconf['tspgPayTid']))
		{
			$api = "auth";
			$mid = $payconf['tspgPayMid'];
			$tid = $payconf['tspgPayTid'];
			
			$memberEmail = getFieldValue(" SELECT email FROM members WHERE id = '".$r["memberid"]."' ","email");
			
			if($memberEmail==="csl0412@gmail.com"){
				$mid='999812666555112';
				$tid='T0000000';
			}

			if($mid == '999812666555112' && $tid=='T0000000') 
			{
				$ServiceURL="https://tspg-t.taishinbank.com.tw/tspgapi/restapi/{$api}.ashx";
			}
			else  
			{
				$ServiceURL="https://tspg.taishinbank.com.tw/tspgapi/restapi/{$api}.ashx";
			}
			
			$param=array();
			$param['layout']="1";
			$param['order_no']="$orderNum";
			
			if(isset($r['totalAmt'])){
				$totalAmt = $r['totalAmt'] * 100;
				$param['amt']="$totalAmt";
				$param['cur']='NTD';
				$param['capt_flag']='0';
			}

			$param['result_flag']='1';
			$param['order_desc']="商城訂單";
			$param['post_back_url']=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/components/cartcvs/api.php?task=backView_tspg&id=$orderNum";	
			$param['result_url']=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/components/cartcvs/api.php?task=getReturn_tspg&receive=receive";	
			
			$send=array();
			$send['apiTarget']=$api;
			$send['sender']='rest';
			$send['ver']='1.0.0';
			$send['mid']=$mid;
			$send['tid']=$tid;
			$send['pay_type']=$pay_type;
			$send['tx_type']=$tx_type;
			$send['params']=$param;
			
			$ch = curl_init();
			$headers = array(
				'Content-Type:application/json',
				'Accept:application/json'
			);
			
			curl_setopt($ch, CURLOPT_URL,$ServiceURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));
			$data = curl_exec ($ch);
			 
			 
			curl_close ($ch);
			$data=json_decode($data,true);
			$params=$data['params'];
			$notRecord = false;
			if(!$notRecord){
				try{
					$db->setQuery("insert into orderTspgLog (type, orderNum,createTime,ip,sendData,responseData,retCode)
					values ('Add','$orderNum','".date("Y-m-d H:i:s")."','$ip','".json_encode($send)."','".json_encode($data)."','".$params['ret_code']."')");
					$db->query();
				}catch(Exception $e){

				}
			}

			switch($tx_type){
				case 1:
					if($params['ret_code']=='00'){
						$url=$params['hpp_url'];
						header('Location:'.$url);
					}else{
						if($params['ret_msg']){
							?>
							<script>
								alert('<?=$params['ret_msg']?>');
								location.href='/';
							</script>
							<?
						}else{
							?>
							<script>
								alert('<?=_CART_NET_ERROR_MSG2?>');
								location.href='/';
							</script>
							<?
						}
					}
					break;
				default:
					return $params;
					break;
			}
			
		}
		
		die();
	}
	else 
	{
		
		$sql ="insert into orderctblog set ".
		" type			='NewOrder' ,".
		" lidm		='$orderNum' ,".
		" ctime					='".date('Y-m-d H:i:s')."'";
		
		$db->setQuery( $sql );
		$db->query();
		
		
		
		$sql = " SELECT A.merID,A.MerchantID,A.TerminalID,A.Key FROM payconf A";
		$db->setQuery($sql);
		$payconf = $db->loadRow();
		
		if(!empty($payconf['merID']) && !empty($payconf['MerchantID']) && !empty($payconf['TerminalID']))
		{
			include_once($conf_php.'includes/auth_mpi_mac.php');
			
			$merID = $payconf['merID'];
			$MerchantID = $payconf['MerchantID'];	
			$TerminalID = $payconf['TerminalID'];	
			$lidm = $orderNum;	
			$purchAmt = $r['totalAmt'];	
			$txType = "0";	
			$Option = "1";	
			$Key = $payconf['Key'];	
			$MerchantName = iconv("utf-8","big5","俊達生技 GoodARCH");	
			$AuthResURL = $HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/components/cartcvs/api.php";	
			$OrderDetail = "俊達生技 GoodARCH訂單";	
			$AutoCap = "1";	
			$Customize = "1";	
			$debug = "0";
			
			$MACString=auth_in_mac($MerchantID,$TerminalID,$lidm,$purchAmt,$txType,$Option,$Key,$MerchantName,$AuthResURL,$OrderDetail,$AutoCap,$Customize,$debug);
			
			
			
			$URLEnc=get_auth_urlenc($MerchantID,$TerminalID,$lidm,$purchAmt,$txType,$Option,$Key,$MerchantName,$AuthResURL,$OrderDetail,$AutoCap,$Customize,$MACString,$debug);
			
			
			if($MerchantID == "8226230001184")
			{
				$actionUrl = "https://testepos.ctbcbank.com/auth/SSLAuthUI.jsp";
			}
			else
			{
				$actionUrl = "https://epos.chinatrust.com.tw/auth/SSLAuthUI.jsp";
			}
			
			header("Content-Type:text/html; charset=utf-8");
			
			?>
			<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js "></script>
			<script language=javascript>
			$(function(){
				setTimeout("document.form1.submit( );","500");
			});
			</script> 
			<div style="float:left; display:none;">
				<form method="POST" name="form1" action="<?=$actionUrl?>">
					<table width=540 bgcolor=#999999>
						<tr>
							<td width="100%">網路特店編號(Mer ID)： <INPUT value="<?=$merID?>" name="merID"></td>
						</tr>
						<tr>
							<td width="100%">加密值： <INPUT value="<?=$URLEnc?>" name="URLEnc" length="1000"></td>
						</tr>
						<tr bgColor=#aedcff>
							<td align="middle"><INPUT type="submit" value="Pay by credit card" border=0 name="imageField" height="32" width="161" ></td>
						</tr>
					</table>
				</form>
			</div>
			
			<?
			
			die();
		}
	}
	
	

	
	

	
	JsonEnd(array("status"=>'1',"data"=>$obj));	
}

function getReturn(){
	global $db,$conf_user,$tablename,$conf_php,$HTTP_X_FORWARDED_PROTO;
	
	$type = "Return";
	
	include_once($conf_php.'includes/auth_mpi_mac.php');

	$EncRes = global_get_param( $_POST, 'URLResEnc', null ,0,1);
	
	
	
	$sql = " SELECT A.Key FROM payconf A";
	$db->setQuery($sql);
	$payconf = $db->loadRow();
	
	$Key = $payconf['Key']; 
	$debug="0";
	$EncArray=gendecrypt($EncRes,$Key,$debug);
	
	
	$status = isset($EncArray['status']) ? $EncArray['status'] : "";	
	$errcode = isset($EncArray['errcode']) ? $EncArray['errcode'] : "";	
	$authcode = isset($EncArray['authcode']) ? $EncArray['authcode'] : "";	
	$authamt = isset($EncArray['authamt']) ? $EncArray['authamt'] : "";	
	$merid = isset($EncArray['merid']) ? $EncArray['merid'] : "";	
	$lidm = isset($EncArray['lidm']) ? $EncArray['lidm'] : "";	
	$offsetamt = isset($EncArray['offsetamt']) ? $EncArray['offsetamt'] : "";	
	$originalamt = isset($EncArray['originalamt']) ? $EncArray['originalamt'] : "";	
	$utilizedpoint = isset($EncArray['utilizedpoint']) ? $EncArray['utilizedpoint'] : "";	
	$numberofpay = isset($EncArray['numberofpay']) ? $EncArray[' numberofpay'] : "";	
	
	
	$last4digitpan = isset($EncArray['last4digitpan']) ? $EncArray['last4digitpan'] : "";	
	$CardNumber = isset($EncArray['CardNumber']) ? $EncArray['CardNumber'] : "";	
	$errdesc = isset($EncArray['errdesc']) ? $EncArray['errdesc'] : "";	
	$xid = isset($EncArray['xid']) ? $EncArray['xid'] : "";	
	$authresurl = isset($EncArray['authresurl']) ? $EncArray['authresurl'] : "";	
	
	$errdescU = iconv("BIG5","UTF-8", $errdesc);
	
	$MACString = auth_out_mac($status,$errcode,$authcode,$authamt,$lidm,$offsetamt,$originalamt,$utilizedpoint,$numberofpay,$last4digitpan,$Key,$debug);
	
	if(!empty($EncArray['lidm']))
	{
		$now = date('Y-m-d H:i:s');
		$today=date("Y-m-d");
		
		$sql ="insert into orderctblog set ".
		" type				='$type' ,".
		" status			='$status' ,".
		" errcode			='$errcode' ,".
		" authcode			='$authcode' ,".
		" authamt			='$authamt' ,".
		" merid				='$merid' ,".
		" lidm				='$lidm' ,".
		" offsetamt			='$offsetamt' ,".
		" originalamt		='$originalamt' ,".
		" utilizedpoint		='$utilizedpoint' ,".
		" numberofpay		='$numberofpay' ,".
		" last4digitpan		='$last4digitpan' ,".
		" CardNumber		='$CardNumber' ,".
		" errdesc			= N'$errdescU' ,".
		" xid				='$xid' ,".
		" authresurl		='$authresurl' ,".
		" ctime				='".date('Y-m-d H:i:s')."'";
		
		$db->setQuery( $sql );
		$r=$db->query();
		
		
		$orderNum = substr($lidm,0,-2);
		$orderNum = str_replace("1S010","1S010-",$orderNum);
		$orderNum = str_replace("3S010","3S010-",$orderNum);
		$orderNum = str_replace("3U010","3U010-",$orderNum);
		
		
		$sql2 = "SELECT * FROM orders WHERE orderNum = '$orderNum'";
		$db->setQuery($sql2);
		$r2 = $db->loadRow();
		$oid = $r2['id'];
		
		
		$tmpStr = substr($lidm,-2);
		$orderMode = $r2['orderMode'];
		$addMemberFirst = ($orderMode == 'addMember' && $tmpStr == '01') ? true : false;
		
		
		
		if ($status == '0' && $errcode == '00')
		{
			if($orderMode == 'addMember')
			{
				
				
				$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '".$r2['memberid']."' ","loginid");
				$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '".$r2['memberid']."' ","sid");
				$name = getFieldValue(" SELECT name FROM members WHERE id = '".$r2['memberid']."' ","name");
				$email = getFieldValue(" SELECT email FROM members WHERE id = '".$r2['memberid']."' ","email");
				$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '".$r2['memberid']."' ","ERPID");
				
				
				$sql_tmp ="update members set ".
				" salesChk        = 1 , ".
				" payDate        ='$today' ".
				" where id='{$r2['memberid']}' ";
				
				$db->setQuery( $sql_tmp );
				$db->query();
			}
			
			
			$sql3 ="update orders set ".
			" status        ='1' ,".
			" mtime 		='$now',".
			" finalPayDate 		='$now'".
			" where id=$oid ";
			
			$db->setQuery( $sql3 );
			$db->query();
			
			$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$oid','$today','1','$now','$now','".$r2['memberid']."');";
			$db->setQuery( $sql );
			$db->query();
			$sus = $RtnMsg;
			
			$buyDate=getFieldValue("select buyDate from orders where memberid='{$r2['memberid']}' AND id='$oid'","buyDate");
			if(strtotime(date("Y-m-d"))-strtotime($buyDate)>432000){
				$db->setQuery("update members set delayCnt=delayCnt+1 where id='{$r2['memberid']}'");
				$db->query();
			}
			
			
			
			if($r2['pointchk'] == '0')
			{
				$sql="update members set pv=pv+'{$r2['pv']}',bv=bv+'{$r2['bv']}',bonus=bonus+'{$r2['bonus']}' where id='{$r2['memberid']}';";
				$db->setQuery($sql);
				$db->query();
				
				$sql="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
						values ('{$r2['memberid']}','$today','{$r2['bonus']}',0,'$oid','$now','$now','{$r2['memberid']}');";
				$db->setQuery($sql);
				$db->query();
				
				$sql="update orders set pointchk=1 where id='$oid';";
				$db->setQuery($sql);
				$db->query();
			}
			
			
			$sql = "select * from siteinfo where sysid ='$sysid' ";

			$db->setQuery( $sql );
			$siteinfo_arr = $db->loadRow();
			
			$from = $siteinfo_arr['email'];
			$fromname = $siteinfo_arr['name'];
			
			
			$sendto = array(array("email"=>$from,"name"=>$fromname));
			$subject = $fromname." - "._CART_PAY_SUCCESS_MSG1." (".date("Y-m-d H:i:s").")";
			$body = "
			<html>
			<head>
				<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
				<title>$fromname "._CART_PAY_SUCCESS_MSG2."</title>

			</head>
			<body style=\"margin:0;padding:0;\">
				<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
					<h3 style=\"letter-spacing:1px;\">"._CART_PAY_SUCCESS_MSG3."</h3>
					<p style=\"line-height:180%;\">"._CART_PAY_SUCCESS_MSG4."</p>
					<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
					<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
						<tbody>
							<tr>
								<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._CART_PAY_SUCCESS_MSG5."</strong></td>
								<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
							</tr>
						</tbody>
					</table>
					<br>
					<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
					"._CART_PAY_SUCCESS_MSG6."{$siteinfo_arr['tel']}&emsp;&emsp;"._CART_PAY_SUCCESS_MSG7."{$siteinfo_arr['addr']}<br>
					"._CART_PAY_SUCCESS_MSG8."{$siteinfo_arr['email']}</p>
					
				</div>
			</body>
			</html>
			";
			
			$rs = global_send_mail("eways100@gmail.com","威誠購物平台",$sendto,$subject,$body);
			
			if($orderMode == 'addMember')
			{
				
				sendMailToMemberBySignupSuccess($r2['memberid']);
			}
			
			
			if($addMemberFirst)
			{
				
				$_SESSION[$conf_user]=array();
				unset($_SESSION[$conf_user]);
			}				
			
			header('Content-type: text/html; charset=utf8');
			?>
			<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js "></script>
			<script language=javascript>
			
			<?php if($orderMode == 'addMember'):?>
			$(function(){
				
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/paySuccess?m1=".$loginId."&m2=".$passwd."&m3=".$ERPID."&m4=".$email ?>';
				/*
				if(confirm('ｅ化入會成功！如需使用購物平台請登入後進行驗證'))
				{
					location.href = '<?="http://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/".$oid ?>';
				}
				else
				{
					location.href = 'http://www.goodarch2u.com.tw/tw/index.php';
				}
				*/
				
			});
			<?php else:?>
			$(function(){
				alert('<?=_CART_PAY_SUCCESS?>');
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/".$oid ?>';
			});
			<?php endif;?>
			
			
			</script> 
			<?
			
			
		}
		else
		{
			$err = $errdesc;
			
			
			
			
			if($addMemberFirst)
			{
				
				$_SESSION[$conf_user]=array();
				unset($_SESSION[$conf_user]);
			}
			
			header('Content-type: text/html; charset=big5');
			?>
			<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.9.1.js "></script>
			<script language=javascript>
			
			<?php if($addMemberFirst):?>
			$(function(){
								
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/payFail"?>';
				/*
				if(confirm('<?=$errdesc?>'+'，線上付款失敗！如需重新進行付款請登入購物平台'))
				{
					location.href = '<?="http://".$_SERVER['HTTP_HOST']."/member_page/login" ?>';
				}
				else
				{
					location.href = 'http://www.goodarch2u.com.tw/tw/index.php';
				}
				*/
				
			});
			<?php else:?>
			$(function(){
				alert('<?=$errdesc?>');
				location.href = '<?=$HTTP_X_FORWARDED_PROTO."://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/".$oid ?>';
			});
			<?php endif;?>
			
			
			</script> 
			<?
			
		}
		
		
	}
	
	

	
	
}

function showlist(){
	global $db,$conf_user,$tablename,$db3;
	
	$uid=LoginChk();
	$mode=getCartMode();
	$data=array();
	$cm = $_SESSION[$conf_user]['is_twcart_cart'];
	if ($cm == '1') {
		$mode = 'twcart';
	}
    $cart=$_SESSION[$conf_user]["{$mode}_list"];
    
	// JsonEnd(array('status' => '1', 'msg'=>$is_twcart));
	$activeChk = global_get_param( $_REQUEST, 'activeChk', null ,0,1  );
	
	if($activeChk == 'true')
	{
		$proArr=CartProductInfo2($cart,'false');
	}
	else
	{
		$proArr=CartProductInfo2($cart,null,true);
	}
	
	if(($mode == 'cart' || $mode == 'twcart') && $activeChk != 'true')
	{
		$addPro=$_SESSION[$conf_user]['amtpro_list'];
		
		if(count($addPro)>0){
			$_SESSION[$conf_user]["cart_list_mode"]='amtpro';
			$amtproArr=CartProductInfo2($addPro);
			
			if(count($amtproArr['data']) > 0)
			{
				foreach($amtproArr['data'] as $row)
				{
					$row['protype'] = 'amtpro';
					$proArr['data'][] = $row;
				}
				
				$proArr['total'] += intval($amtproArr['total']);
				$proArr['amt'] += intval($amtproArr['amt']);
			}
			
			
			if ($cm == '1') {
				$_SESSION[$conf_user]["cart_list_mode"] = 'twcart';
			} else {
				$_SESSION[$conf_user]["cart_list_mode"] = 'cart';
			}
		}
		
		
		$freePro=$_SESSION[$conf_user]['freepro_list'];
		
		if(count($freePro)>0){
			$_SESSION[$conf_user]["cart_list_mode"]='freepro';
			$freeproArr=CartProductInfo2($freePro);
			
			if(count($freeproArr['data']) > 0)
			{
				foreach($freeproArr['data'] as $row)
				{
					$row['protype'] = 'freepro';
					
					if(empty($row['activeName']))
					{
						$fid_arr = explode("|||",$row['fid']);
						$activeBundleId = str_replace("999999","",$fid_arr[2]);
						$row['activeName'] = getFieldValue(" SELECT * FROM activeBundle WHERE id = '$activeBundleId' ","name")."的滿額贈品";
					}
					
					$proArr['data'][] = $row;
				}
			}
			
			if ($cm == '1') {
				$_SESSION[$conf_user]["cart_list_mode"] = 'twcart';
			} else {
				$_SESSION[$conf_user]["cart_list_mode"] = 'cart';
			}
		}
		
	}

	

	$data['list']=$proArr['data'];
	
	
	$take_type=$_SESSION[$conf_user]['take_type'];
	$sql="select * from members where id='$uid'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	
	$member=array();
	
	$member['name']=$r['name'];
	$member['mobile']=$r['mobile'];
	$member['address']=$r['addr'];
	$member['email']=$r['email'];
	$member['canton']['id']=$r['canton'];
	$member['city']['id']=$r['city'];
	$member['salesChk']=$r['salesChk'];	
	
	
	$invoice=getFieldValue("select invoice from siteinfo","invoice");
	
	$_SESSION[$conf_user]['proArr']=$proArr;
	$_SESSION[$conf_user]['usecoin']=intval($proArr['usecoin']);


	$is_twcart = $_SESSION[$conf_user]['is_twcart_cart'];
	if ($is_twcart == '1') {
		$getdlvr = 'f_main_dlvr';
	} else if ($is_twcart == '0') {
		$getdlvr = 'main_dlvr';
	}
	if ($activeChk == 'true') {
		// JsonEnd(array('status'=>'1','aa'=>$proArr));
		$logres = logisitics_type($_SESSION[$conf_user]['logistics_type'], null, $getdlvr,$proArr['total']);
	} else {
		$logres = logisitics_type($_SESSION[$conf_user]['logistics_type'], null, $getdlvr);
	}





	// $dlvrAmt=$_SESSION[$conf_user]['dlvrAmt']-intval($proArr['disDlvrAmt']);
	$dlvrAmt = $logres['dlvr'] - intval($proArr['disDlvrAmt']);
	$_SESSION[$conf_user]['realDlvrAmt']=$dlvrAmt;
	$_SESSION[$conf_user]['totalAmt']=$proArr['amt']-intval($proArr['usecoin']);

	$payable=true;
	$bonusArr=array();
	$bonusArr=array("userbonus"=>0,"payable"=>$payable);
	if($mode=="bonus" && $_SESSION[$conf_user]['uid']){
		$bonus=intval(getFieldValue("select bonus from members where id='{$_SESSION[$conf_user]['uid']}'","bonus"));
		if($proArr['total']>$bonus){
			$payable=false;
		}
		$bonusArr=array("userbonus"=>$bonus,"payable"=>$payable);
	}
	
	
	$data['list'] = cartProductClac( $proArr['active_list'], $data['list'], $proArr['activeExtraList']);
	
	$pv = 0;
	$bv = 0;
	$bouns = 0;
	$pvbvratio = (float)getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
	if($member['salesChk'] == '1')
	{
		if(count($data['list']) > 0)
		{
			foreach($data['list'] as $key=>$row)
			{
				if($row['protype'] == 'amtpro' || $row['protype'] == 'freepro')
				{
					
					continue;
				}
				else
				{
					$tmp_pv = $row['prodtl_pv'];
					$pv += $tmp_pv;
					
					
					
				}
			}
		}
		
		
		

		
		$_SESSION[$conf_user]['proArr']=$proArr;
	}
	else
	{
		
		if($mode == 'cart' || $mode == 'twcart')
		{
			
			$bouns1 = intval(getFieldValue("select bouns1 from siteinfo","bouns1"));	
			$bouns2 = intval(getFieldValue("select bouns2 from siteinfo","bouns2"));	
			
			if($bouns1 > 0)
			{
				$bouns = (int)( $proArr['amt'] / $bouns1 ) * $bouns2;
			}
		}
	}
	
	if(($mode == 'cart' || $mode == 'twcart') && $activeChk != 'true')
	{
		
		if(count($proArr['active_list']) > 0)
		{
			foreach($proArr['active_list'] as $row)
			{
				if(intval($row['bonus']) > 0)
				{
					$bouns += round($proArr['amt'] * intval($row['bonus']) / 100); 
				}
			}
		}
	}
    
	
	
	$bv = round($pv * $pvbvratio);
	
	
    
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    if($activeBundleCart && count($activeBundleCart)>0){
        $activeBundlePrice=0;
        $activeBundlePv=0;
        $activeBundleBv=0;
        foreach($activeBundleCart as $key=>$value){
            $activeBundlePrice+=$value['price'];
            $activeBundlePv+=$value['pv'];
            $activeBundleBv+=$value['bv'];
        }
        $_SESSION[$conf_user]['activeBundlePrice']=$activeBundlePrice;
        $_SESSION[$conf_user]['activeBundlePv']=$activeBundlePv;
        $_SESSION[$conf_user]['activeBundleBv']=$activeBundleBv;
        $pv+=$activeBundlePv;
        $bv+=$activeBundleBv;
    }

	$proArr['bonus'] = (int)$bouns;
	$_SESSION[$conf_user]['proArr']=$proArr;
	
	
	
	foreach($proArr['data'] as $key=>$pro){
		$pid=$pro['id'];
		$quantity=$pro['num'];
		
		$format1 = $pro['format1'];
		$format1name = $pro['format1name'];
		$format2 = $pro['format2'];
		$format2name = $pro['format2name'];
		
		
		if(!empty($format1) && !empty($format2))
		{
			
			$format_proinstock_instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instockchk");
			$format_proinstock_instock = getFieldValue(" SELECT instock FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instock");
			
			
			$cart_quantity_array[$pid."||".$format1."||".$format2] = $quantity;
			
			if($format_proinstock_instockchk == 1 && ($quantity - $format_proinstock_instock > 0))
			{
				$pname=getFieldValue("select name from products where id='$pid'","name");
				JsonEnd(array("status"=>2,"msg"=>$pname."【{$format1name}-{$format2name}】".' '._CART_INSTOCK_ERROR_MSG));
			}
		}
	}
	
	
    if($activeBundleCart && count($activeBundleCart)>0){
        
		
		foreach($activeBundleCart as $key=>$value){
			
			foreach($value['activeBundleDetail'] as $activeBundleDetail){
				$_products=$activeBundleDetail['products'];
				$cart_quantity_array[$_products['productId']."||".$_products['spec'][1]['id']."||".$_products['spec'][2]['id']] += 1;
			}
		}
		foreach($cart_quantity_array as $key=>$quantity)
		{
			$key_arr = explode("||",$key);
			$pid = $key_arr[0];
			$format1 = $key_arr[1];
			$format2 = $key_arr[2];
			
			
			$format_proinstock_instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instockchk");
			$format_proinstock_instock = getFieldValue(" SELECT instock FROM proinstock WHERE pid = '$pid' AND format1 = '$format1' AND format2 = '$format2' ","instock");
			
			if($format_proinstock_instockchk == 1 && ($quantity - $format_proinstock_instock > 0))
			{
				$pname=getFieldValue("select name from products where id='$pid'","name");
				JsonEnd(array("status"=>2,"msg"=>$pname.' '._CART_INSTOCK_ERROR_MSG));
			}
		}		
	}
	
	$sisql = "SELECT * from siteinfo";
	$db->setQuery($sisql);
	$siteinfo = $db->loadRow();
	$tax_fee = $siteinfo['taxrate'] / 100;

	$use_p=$_SESSION[$conf_user]['use_p']; //檢測是否有選使用購物金
	if($use_p == 1){
		$use_points = $_SESSION[$conf_user]['use_points'];
	}else{
		$use_points = 0;
	}
	
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	$a_use_points = 0;
	foreach ($plist as $each) {
		if($each['p_type'] == '1'){
			$now_points += $each['point'];
		}else if($each['p_type'] == '2'){
			$now_points -= $each['point'];
		}
	}
	if(floatval($now_points) >= floatval($use_points)){
		$a_use_points = floatval($use_points);
	}else{
		$a_use_points = 0;
	}

	if(!is_numeric($use_points)){
		$a_use_points = 0;
	}


	//檢測回饋點
	$cb_use_p = $_SESSION[$conf_user]['cb_use_p']; //檢測是否有選使用購物金	
	if ($cb_use_p == 1) {
		$cb_use_points = $_SESSION[$conf_user]['cb_use_points'];
	} else {
		$cb_use_points = 0;
	}
	$now_date = date('Y-m-d');
	//回饋點相關資料
	$CBData = get_cash_back(0, $now_date);
	$now_cb_points = $CBData['point'];
	// $cb_gpoints = 0;
	// $csql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '0' and expiry_date > '$now_date'";
	// $db3->setQuery($csql);
	// $cgetlist = $db3->loadRow();
	// if (!empty($cgetlist)) {
	// 	$cb_gpoints = $cgetlist['cb_points']; //目前可用的得到點數
	// }
	// $usql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '1' and expiry_date > '$now_date'";
	// $db3->setQuery($usql);
	// $cuselist = $db3->loadRow();
	// if (!empty($cuselist)) {
	// 	$cb_upoints = $cuselist['cb_points']; //目前已使用的得到點數
	// }

	// $now_cb_points = (int)($cb_gpoints) - (int)($cb_upoints);

	if ($now_cb_points >= $cb_use_points) {
		$b_use_points = (int)$cb_use_points;
	} else {
		$b_use_points = 0;
	}
	if (!is_numeric($cb_use_points)) {
		$b_use_points = 0;
	}

	
	$finalccv = $proArr['totalccv'] - $proArr['discount'];
	JsonEnd(
		array(
			"status" => 1, 
			"data"=>$data,
			"activeBundleCart"=>$activeBundleCart,
			"total"=>$proArr['total'],
			"totalccv"=>$finalccv,
			"tax_fee"=>$tax_fee,
			"amt"=>$proArr['amt'],
			"active_list"=>$proArr['active_list'],
			"discount"=>$proArr['discount'],
			"free_coin"=>$proArr['free_coin'],
			"dlvrAmt"=>$dlvrAmt,
			"usecoin"=>intval($proArr['usecoin']),
			"member"=>$member,
			"take_type"=>$take_type,
			"invoice"=>$invoice,
			"mode"=>$mode,
			"bonusArr"=>$bonusArr,
			"pv"=>$pv,
			"bv"=>$bv,
			"bouns"=>$bouns,
			"activeChk"=>$activeChk,
			"use_p" => $use_p,
			"use_points" => $a_use_points,
			"cb_use_p" => $cb_use_p,
			"cb_use_points" => $b_use_points
		)
	);
	
}

include( $conf_php.'common_end.php' ); 
?>