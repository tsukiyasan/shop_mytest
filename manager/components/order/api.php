<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="orders";
userPermissionChk("order");
switch ($task) {
	
	case "list": 
		showlist();
		break;
	case "detail": 
		showdetail();
		break;
	case "add": 
	case "update": 
		updatepage();
		break;
	case "del": 
		deletepage();
		break;
	case "getModel": 
		getModel();
		break;	
	case "trace": 
		tracepage();
		break;	
	case "cancel": 
		cancelpage();
		break;
	case "return": 
		returnpage();
		break;	
	case "operate":	
		operate();
		break;	
		
	case "porlist":	
		getporlist();
		break;	
		
	case "receive_update":	
		receive_update();
		break;	
		
	case "invoice_update":	
		invoice_update();
		break;	
	
	case "finalPayDate_update":	
		finalPayDate_update();
		break;	
		
	case "getpubcode":	
		getcode();
		break;	
	case "upd_exportChk":	
		upd_exportChk();
		break;
	case "shipDate_update":	
		shipDate_update();
		break;	
}


function upd_exportChk(){
	global $db, $tablename, $conf_user;
	
	$filename = "";
	
	$date = getFieldValue(" SELECT codeName FROM pubcode WHERE codeKinds = 'exportNum' ","codeName");
	if(date("Y-m-d",strtotime($date)) == date("Y-m-d"))
	{
		$num = intval(getFieldValue(" SELECT codeValue FROM pubcode WHERE codeKinds = 'exportNum' ","codeValue"));
		$next = $num + 1;
		$num = ($num < 10) ? "0".$num : $num;
		$filename = "PU".date("ymd").$num.".csv";
		
		$sql = " update pubcode set codeValue = '".$next."' WHERE codeKinds = 'exportNum' ";
		$db->setQuery($sql);
		$db->query();
	}
	else
	{
		$filename = "PU".date("ymd")."01.csv";
		
		$sql = " update pubcode set codeName = '".date("Y-m-d")."', codeValue = '2' WHERE codeKinds = 'exportNum' ";
		$db->setQuery($sql);
		$db->query();
	}
	
	
	$idstr = global_get_param( $_REQUEST, 'idstr', null);
	$idstrArr = explode("||",trim($idstr,"||"));
	$oidArr = array();
	if(count($idstrArr) > 0 && !empty($idstrArr[0]))
	{
		foreach($idstrArr as $oid)
		{
			$oidArr[] = intval($oid);
		}
		
		rsort($oidArr);
		
		$dataArr = $_SESSION[$conf_user]['order_printcsv_array'];
		
		$content = $dataArr[0];
		foreach($oidArr as $oid)
		{
			$content .= $dataArr[$oid];
		}
		
		$sql="update $tablename set exportChk = 1 where id IN ('".implode("','", $oidArr)."') ";
		$db->setQuery($sql);
		$db->query();
	}
	else
	{
		$content = $_SESSION[$conf_user]['order_printcsv'];
		
		$orderid_arr = $_SESSION[$conf_user]['orderid_arr'];
	
		if(count($orderid_arr) > 0)
		{
			$sql="update $tablename set exportChk = 1 where id IN ('".implode("','", $orderid_arr)."') ";
			$db->setQuery($sql);
			$db->query();
		}
	}
	
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$filename);
	
	
	$content = mb_convert_encoding($content , "Big5" , "UTF-8");
	echo $content;
	exit;
	
	
}

function getcode(){
	global $db,$conf_user;
	
	$sql_str = "codeName";
	if($_SESSION[$conf_user]['syslang'])
	{
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs";
				break;
			case 'en':
				$sql_str = "codeName_en";
				break;
			case 'in':
				$sql_str = "codeName_in";
				break;
			default :
				$sql_str = "codeName";
				break;
		}
	}
	
	$sql = " select * from pubcode where deleteChk=0 AND codeKinds='bill' order by odring";	

	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$info=array();
		$info['name']=$row[$sql_str];
		$info['codeName_chs']=$row['codeName_chs'];
		$info['codeName_en']=$row['codeName_en'];
		$info['value']=$row['codeValue'];
		
		$data[]=$info;
		
		
	}
	JsonEnd(array("status"=>1, "data"=>$data));
}

function finalPayDate_update(){
	global $db,$tablename,$conf_user;
	
	$finalPayDate = global_get_param( $_REQUEST, 'finalPayDate', null ,0,1  );
	
	$order_id=intval($_SESSION[$conf_user]['order_id']);
	if($order_id==0){
		
		JsonEnd(array("status"=>0, "msg"=>_ORDER_ORDER_EMPTY));
	}
	
	$sql="update $tablename set finalPayDate=N'$finalPayDate' where id='$order_id'";
	$db->setQuery( $sql );
	$db->query();
	
	JsonEnd(array("status"=>1, "msg"=>_ORDER_UPDATE_PAYDATE));
	
}

function invoice_update(){
	global $db,$tablename,$conf_user;
	
	$invoiceType = global_get_param( $_REQUEST, 'invoiceType', null ,0,1  );
	$invoiceTitle = global_get_param( $_REQUEST, 'invoiceTitle', null ,0,1  );
	$invoiceSN = global_get_param( $_REQUEST, 'invoiceSN', null ,0,1  );
	$invoice = global_get_param( $_REQUEST, 'invoice', null ,0,1  );
	
	$order_id=intval($_SESSION[$conf_user]['order_id']);
	if($order_id==0){
		
		JsonEnd(array("status"=>0, "msg"=>_ORDER_ORDER_EMPTY));
	}
	
	$sql="update $tablename set invoiceType=N'$invoiceType',invoiceTitle=N'$invoiceTitle',invoiceSN=N'$invoiceSN',invoice=N'$invoice' where id='$order_id'";
	
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1, "msg"=>_ORDER_UPDATE_INVOICE_INFO));
	
}

function receive_update(){
	global $db,$tablename,$conf_user;
	$dlvrName = global_get_param( $_REQUEST, 'dlvrName', null ,0,1  );
	$dlvrMobile = global_get_param( $_REQUEST, 'dlvrMobile', null ,0,1  );
	$dlvrCity = global_get_param( $_REQUEST, 'dlvrCity', null ,0,1  );
	$dlvrCanton = global_get_param( $_REQUEST, 'dlvrCanton', null ,0,1  );
	$dlvrAddr = global_get_param( $_REQUEST, 'dlvrAddr', null ,0,1  );
	$dlvrDate = global_get_param( $_REQUEST, 'dlvrDate', null ,0,1  );
	$dlvrTime = global_get_param( $_REQUEST, 'dlvrTime', null ,0,1  );
	$dlvrNote = global_get_param( $_REQUEST, 'dlvrNote', null ,0,1  );
	$order_id=intval($_SESSION[$conf_user]['order_id']);
	if($order_id==0){
		
		JsonEnd(array("status"=>0, "msg"=>_ORDER_ORDER_EMPTY));
	}
	
	$sql="update $tablename set dlvrName=N'$dlvrName',dlvrMobile=N'$dlvrMobile',dlvrCity=N'$dlvrCity',dlvrCanton=N'$dlvrCanton',dlvrAddr=N'$dlvrAddr'
				,dlvrDate=N'$dlvrDate',dlvrTime=N'$dlvrTime',dlvrNote=N'$dlvrNote' where id='$order_id'";
	
	$db->setQuery( $sql );
	$db->query();
	
	JsonEnd(array("status"=>1, "msg"=>_ORDER_UPDATE_RECEIVE));
}

function getporlist()
{
	global $db,$tablename,$conf_user;
	
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$id=implode(",",$idarr);
	$order_arr = array();
	if(!empty($idarr))
	{
		$sql = " SELECT B.quantity,C.id,C.name FROM orderdtl B , products C WHERE B.pid = C.id AND B.oid IN ({$id}) ORDER BY C.odring ASC";
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		foreach($r as $row)
		{
			if(empty($order_arr[$row['id']]))
			{
				$order_arr[$row['id']]['proNmae'] = $row['name'];
				$order_arr[$row['id']]['proCnt'] = 0;
			}
			
			$order_arr[$row['id']]['proCnt'] += (int)$row['quantity'];
		}
		JsonEnd(array("status"=>1,'data'=>$order_arr));
	}
	
	JsonEnd(array("status"=>0, "msg"=>_ADMINMANAGERS_NO_SELECT));
}

function operate(){
	global $db,$tablename,$conf_user;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		
		JsonEnd(array("status"=>0, "msg"=>_ADMINMANAGERS_NO_SELECT));
	}
	$date=date("Y-m-d H:i:s");
	$id=implode(",",$idarr);
	$field="";
	if($action==1){
		$field="publish=1,mtime='$date'";
		
		$sql = "select * from $tablename where id in ($id)";
		$db->setQuery( $sql );
		$ori=$db->loadRowList();
		
		
		if(count($ori) > 0)
		{
			$new_idarr = array();
			$del_idarr = array();
			foreach($ori as $row)
			{
				$sql = " SELECT * FROM $tablename WHERE combineid = '{$row['id']}'";
				$db->setQuery( $sql );
				$r0=$db->loadRowList();
				if(count($r0) > 0)
				{
					foreach($r0 as $row0)
					{
						$new_idarr[] = $row0['id'];
					}
					
					$del_idarr[] = $row['id'];
				}
				else
				{
					$new_idarr[] = $row['id'];
				}
			}
			
			if(count($del_idarr) > 0)
			{
				$del_sql = " DELETE FROM orderlog WHERE oid IN (".implode(",",$del_idarr).");";
				$del_sql .= " DELETE FROM orderdtl WHERE oid IN (".implode(",",$del_idarr).");";
				$del_sql .= " DELETE FROM orders WHERE id IN (".implode(",",$del_idarr).");";
			}
			
			if(count($new_idarr) > 0)
			{
				$sql = " SELECT *  FROM orders WHERE id IN (".implode(",",$new_idarr).")";
				$db->setQuery( $sql );
				$r=$db->loadRowList();
				
				$id=implode(",",$new_idarr);
			}
			else {
				
				JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE));	
			}
		}
		
		$tmp_arr = array();
		
		$sum_sumAmt = 0;
		$sum_discount = 0;
		$dcntAmt = 0;
		$high_dlvrFee = 0;
		$sum_usecoin = 0;
		$totalAmt = 0;
		$notes_str = "";
		
		foreach($r as $row){
			
			$sum_sumAmt += (int)$row['sumAmt'];
			$sum_discount += (int)$row['discount'];
			if($high_dlvrFee < (int)$row['dlvrFee'])
			{
				$high_dlvrFee = (int)$row['dlvrFee'];
			}
			$sum_usecoin += (int)$row['usecoin'];
			
			$notes_str .= $row['dlvrNote'];
			
			if(count($tmp_arr) == 0)
			{
				
				
				if( $row['payType'] == '1' && $row['status'] != '2')
				{
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG1));	
				}
				
				
				if( $row['payType'] != '1' && $row['status'] != '0')
				{
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG2));	
				}
				
				$tmp_arr = $row;
			}
			else {
				
				if($row['memberid'] != $tmp_arr['memberid']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG3));
				}elseif($row['payType'] != $tmp_arr['payType']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG4));
				}elseif($row['dlvrType'] != $tmp_arr['dlvrType']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG5));
				}elseif($row['status'] != $tmp_arr['status']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG6));
				}elseif($row['dlvrTimePeriod'] != $tmp_arr['dlvrTimePeriod']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG7));
				}elseif($row['dlvrName'] != $tmp_arr['dlvrName']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG8));
				}elseif($row['dlvrMobile'] != $tmp_arr['dlvrMobile']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG9));
				}elseif($row['dlvrAddr'] != $tmp_arr['dlvrAddr']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG10));
				}elseif($row['dlvrDate'] != $tmp_arr['dlvrDate']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG11));
				}elseif($row['invoiceType'] != $tmp_arr['invoiceType']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG12));
				}elseif($row['invoiceTitle'] != $tmp_arr['invoiceTitle']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG13));
				}elseif($row['invoiceSN'] != $tmp_arr['invoiceSN']){
					
					JsonEnd(array("status"=>0,"msg"=>_ORDER_UNABLE_MERGE_MSG14));
				}
			}
		}
		
		
		if(!empty($del_sql))
		{
			$db->setQuery( $del_sql );
			$db->query_batch();
		}
		
		$dcntAmt = $sum_sumAmt - $sum_discount;
		$totalAmt = $sum_sumAmt - $sum_discount + $high_dlvrFee - $sum_usecoin;
		
		
		$now = date('Y-m-d H:i:s');
		$today = date('Y-m-d');
		$uid = $_SESSION[$conf_user]['uid'];
		
		
		$sql="BEGIN;";
		
		$sql.= "INSERT INTO orders (combineid,memberid,email,buyDate,payType,dlvrType,status,sumAmt,discount,dcntAmt,dlvrFee,usecoin,freecoin,totalAmt,scoreAmt,dlvrTimePeriod,dlvrName,dlvrMobile,dlvrCity,dlvrCanton,dlvrZip,dlvrAddr,dlvrDate,dlvrTime,dlvrNote,atmlastNum,atmName,atmDate,atmTime,atmBank,atmMoney,invoiceInfo,invoiceType,invoiceTitle,invoiceSN,traceNumber,traceName,traceUrl,ctime,mtime,muser) VALUES ";
		$sql.= "('0','{$tmp_arr['memberid']}','{$tmp_arr['email']}','$today','{$tmp_arr['payType']}','{$tmp_arr['dlvrType']}','{$tmp_arr['status']}','$sum_sumAmt','$sum_discount','$dcntAmt','$high_dlvrFee','$sum_usecoin','{$tmp_arr['freecoin']}','$totalAmt','{$tmp_arr['scoreAmt']}','{$tmp_arr['dlvrTimePeriod']}',N'{$tmp_arr['dlvrName']}','{$tmp_arr['dlvrMobile']}','{$tmp_arr['dlvrCity']}','{$tmp_arr['dlvrCanton']}','{$tmp_arr['dlvrZip']}',N'{$tmp_arr['dlvrAddr']}','{$tmp_arr['dlvrDate']}','{$tmp_arr['dlvrTime']}','{$notes_str}','{$tmp_arr['atmlastNum']}','{$tmp_arr['atmName']}','{$tmp_arr['atmDate']}','{$tmp_arr['atmTime']}','{$tmp_arr['atmBank']}','{$tmp_arr['atmMoney']}','{$tmp_arr['invoiceInfo']}','{$tmp_arr['invoiceType']}','{$tmp_arr['invoiceTitle']}','{$tmp_arr['invoiceSN']}','{$tmp_arr['traceNumber']}','{$tmp_arr['traceName']}','{$tmp_arr['traceUrl']}','$now','$now','$uid');";
		
		
		$sql.="
			SET @insertid=LAST_INSERT_ID();
		";
		
		$sql0 = "select * from orderdtl where oid in ($id) ORDER BY oid";
		$db->setQuery( $sql0 );
		$dtl_list=$db->loadRowList();
		foreach($dtl_list as $dtl){
			
			$orioid = (!empty($dtl['orioid'])) ? $dtl['orioid'] : $dtl['oid'];
			
			$sql.="INSERT INTO orderdtl (oid,orioid,pid,unitAmt,quantity,subAmt,ctime,mtime,muser)
			     values 
			     (@insertid,'{$orioid}','{$dtl['pid']}','{$dtl['unitAmt']}','{$dtl['quantity']}','{$dtl['subAmt']}','$now','$now','$uid');";
		}
		
		
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
		if($orderseq<10){
			$orderseq="0000".$orderseq;
		}else if($orderseq<100){
			$orderseq="000".$orderseq;
		}else if($orderseq<1000){
			$orderseq="00".$orderseq;
		}else if($orderseq<10000){
			$orderseq="0".$orderseq;
		}
		$orderseq=date("Ymd").$orderseq;
		
		$sql.="update orders set orderNum='$orderseq' where id=@insertid;";
		$sql.="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values (@insertid,'$today','{$tmp_arr['status']}','$now','$now','$uid');";
		$sql.= "update orders set combineid=@insertid where id in ($id);";
		
		$sql.="COMMIT;";
		
		
		
		
	}else if($action==2){
		$field="publish=0,mtime='$date'";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==3){
		$sql="";
		$sql.="delete from $tablename where id in ($id);";
		$sql.="delete from orderdtl where oid in ($id);";
	}
	
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}
function cancelpage(){
	global $db,$tablename,$conf_user;
	$id = intval(global_get_param( $_REQUEST, 'id', null));
	
	$ori_status = getFieldValue(" SELECT status FROM $tablename WHERE id = '$id'", "status");
	
	$sql="update $tablename set status=6,mtime='".date("Y-m-d H:i:s")."' where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	
	$now = date('Y-m-d H:i:s');
	$today = date('Y-m-d');
	$uid = $_SESSION[$conf_user]['uid'];
	
	$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values 
	('$id','$today','6','$now','$now','$uid');";
	$db->setQuery($sql);
	$db->query();
	
	
	order_instock($ori_status,"6",$id);
	
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
}

function returnpage(){
	global $db,$tablename,$conf_user;
	$id = intval(global_get_param( $_REQUEST, 'id', null));
	
	$ori_status = getFieldValue(" SELECT status FROM $tablename WHERE id = '$id'", "status");
	
	$sql="update $tablename set status=8,mtime='".date("Y-m-d H:i:s")."' where id='$id';";
	
	$now = date('Y-m-d H:i:s');
	$today = date('Y-m-d');
	$uid = $_SESSION[$conf_user]['uid'];
	
	$sql .= "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$id','$today','8','$now','$now','$uid');";
	
	$db->setQuery( $sql );
	$db->query_batch();
	
	
	order_instock($ori_status,"8",$id);
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
}

function tracepage(){
	global $db,$tablename,$conf_user;
	$id = intval(global_get_param( $_REQUEST, 'id', null));
	$traceNumber = global_get_param( $_REQUEST, 'traceNumber', null);
	$traceName = global_get_param( $_REQUEST, 'traceName', null);
	$traceUrl = global_get_param( $_REQUEST, 'traceUrl', null);
	
	if(!empty($traceNumber))
	{
		$traceUrl = "http://www.t-cat.com.tw/Inquire/TraceDetail.aspx?BillID=".$traceNumber;
	}
	
	$now=date("Y-m-d H:i:s");
	$today=date("Y-m-d");
	
	$sql="update $tablename set traceNumber='$traceNumber',traceName='$traceName',traceUrl='$traceUrl',status=3 where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	
	$sql = "select * from siteinfo ";

	$db->setQuery( $sql );
	$siteinfo_arr = $db->loadRow();
	
	$sql = "select * from  $tablename where id='$id'";
	$db->setQuery( $sql );
	$order_arr = $db->loadRow();
	
	$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$id','$today','3','$now','$now','{$order_arr['memberid']}');";
	$db->setQuery($sql);
	$db->query();
	
	$code=md5($order_arr['memberid'].$id.$order_arr['memberid']);
	$sql="insert into requestLog (ctime,memberid,code,type,var01) values ('$now','{$order_arr['memberid']}','$code','orderurl','member_page/orderdtl/$id')";
	$db->setQuery($sql);
	$db->query();
	
	$from = $siteinfo_arr['email'];
	$fromname = $siteinfo_arr['name'];
	$addr = $siteinfo_arr['addr'];
	if($_SESSION[$conf_user]['syslang'])
	{
		$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
		$addr = $siteinfo_arr['addr_'.$_SESSION[$conf_user]['syslang']];
	}
	$sendto = array(array("email"=>$order_arr['email'],"name"=>$order_arr['dlvrName']));
	
	$subject = $fromname." - "._ORDER_SHIPPING_MSG." (".date("Y-m-d H:i:s").")";
	$body = "
	<html>
	<head>
			<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
			<title>$fromname "._ORDER_SHIPPING_MSG."</title>

			</head>
	<body style=\"margin:0;padding:0;\">
		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
			<h3 style=\"letter-spacing:1px;\">"._ORDER_SHIPPING_MSG1." ".$order_arr['dlvrName']." "._ORDER_SHIPPING_MSG2."</h3>
			<p style=\"line-height:180%;\">"._ORDER_SHIPPING_MSG3."</p>
			<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
			<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
				<tbody>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"150\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG4."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">{$order_arr['orderNum']}</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG5."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">{$order_arr['buyDate']}</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG6."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">".number_format($order_arr['totalAmt'])."</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG7."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$traceName</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG8."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$traceNumber</td>
					</tr>
					<tr>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG9."</strong></td>
						<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$traceUrl</td>
					</tr>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG10."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><a href=\"http://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/$code\">http://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/$code</a></td>
						</tr>
				</tbody>
			</table>
			<br>
			<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
			"._ORDER_SHIPPING_MSG11."{$siteinfo_arr['tel']}&emsp;&emsp;"._ORDER_SHIPPING_MSG12."$addr<br>
			"._ORDER_SHIPPING_MSG13."{$siteinfo_arr['email']}</p>
			
		</div>
	</body>
	</html>
	";

	$rs = global_send_mail($from,$fromname,$sendto,$subject,$body);
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
}

function getModel(){
	global $db;
	
	$sql = " select * from pubcode where deleteChk=0 AND codeKinds in ('bill','payType','dlvrType','invoiceType') order by odring";	

	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$info=array();
		$info['name']=$row['codeName'];
		$info['codeName_chs']=$row['codeName_chs'];
		$info['codeName_en']=$row['codeName_en'];
		$info['value']=$row['codeValue'];
		
		$data[$row['codeKinds']][]=$info;
	}
	JsonEnd(array("status"=>1,"data"=>$data));
}

function showlist()
{
	global $db, $globalConf_list_limit,$tablename,$conf_user;
	$cur = global_get_param( $_REQUEST, 'page', null);
	$date = global_get_param( $_REQUEST, 'date', null);
	$date = str_replace('\"', '"', $date);
	$datearray = json_decode($date,true);
	$startDate = $datearray['startDate'];
	$endDate = $datearray['endDate'];
	
	$cdate = global_get_param( $_REQUEST, 'cdate', null);
	$cdate = str_replace('\"', '"', $cdate);
	$datearray = json_decode($cdate,true);
	$startCDate = $datearray['startDate'];
	$endCDate = $datearray['endDate'];
	
	$search = global_get_param( $_REQUEST, 'search', null);
	$status = global_get_param( $_REQUEST, 'status', null);
	$exportChk = global_get_param( $_REQUEST, 'exportChk', null);
	$orderby = global_get_param( $_REQUEST, 'orderby', null);
	$arrJson = array();
	if($orderby) {
		$orderby = str_replace('\"', '"', $orderby);
		$orderarray = json_decode($orderby,true);
		if(count($orderarray) > 0) {
			$orderstr = "";
			foreach($orderarray as $k=>$v) {
				$orderstr = "A.".$k." ".$v.",".$orderstr;
			}
			$orderstr = "ORDER BY ".$orderstr;
			$orderstr = substr($orderstr, 0 ,-1);
		}
		$orderstr.=",A.id desc";
	}
	if($search) {
		$sql = " select id from members where name like '%$search%'";	
		$db->setQuery( $sql );
		$row = $db->loadRowList();
		$idarr=array();
		foreach($row as $id){
			$idarr[]=$id['id'];
		}
		if(count($idarr)>0){
			$midStr.=" OR A.memberid in ('".implode("','",$idarr)."')";
		}
		$where_str.= " AND ( A.dlvrName like '%$search%' OR A.orderNum like '%$search%' OR A.email like '%$search%' OR A.traceNumber like '%$search%' $midStr )";
	}
	
	if($startDate){
		
		$startDate = date("Y-m-d H:i:s", strtotime($startDate));
		
		$where_str.=" AND A.ctime>='$startDate'";
	}	
	if($endDate){
		
		$endDate = date("Y-m-d H:i:s", strtotime($endDate));
		
		$where_str.=" AND A.ctime<='$endDate'";
	}
	
	if($startCDate){
		$status = 6;
		$where_str.=" AND A.endDate>='$startCDate'";
	}	
	if($endCDate){
		$status = 6;
		$where_str.=" AND A.endDate<='$endCDate'";
	}
	
	if(isset($status) && $status != -1){
		$where_str.=" AND A.status='$status'";
	}
	
	if(isset($exportChk) && $exportChk != -1){
		
		if($exportChk == '1')
		{
			$where_str.=" AND A.exportChk='$exportChk'";
		}
		else
		{
			$where_str.=" AND ( A.exportChk='0' OR A.exportChk IS NULL )";
		}	
	}
	
	$orderMode = global_get_param( $_REQUEST, 'orderMode', null);
	$payType = global_get_param( $_REQUEST, 'payType', null);
	if(isset($orderMode) && $orderMode != -1){
		
		if($orderMode == '1')
		{
			$where_str.=" AND A.orderMode <> 'addMember'";
		}
		else
		{
			$where_str.=" AND A.orderMode = 'addMember'";
		}	
	}
	if(isset($payType) && $payType != -1){
		
		$where_str.=" AND A.payType = '$payType'";	
	}

	
	
	$today = date("Y-m-d");
	 

	$sql = " select * from $tablename A where 1=1 AND A.combineid = '0' $where_str $orderstr ";
	
	$db->setQuery( $sql );
	$row = $db->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	$data = array();
	
	$now=date("Y-m-d H:i:s");
	
	$sql_str = "codeName";
	if($_SESSION[$conf_user]['syslang'])
	{
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs";
				break;
			case 'en':
				$sql_str = "codeName_en";
				break;
			case 'in':
				$sql_str = "codeName_in";
				break;
			default :
				$sql_str = "codeName";
				break;
		}
	}

	for($i = $from; $i < min($end, $cnt); $i++) {
		foreach($row[$i] as $key=>$item){
			if($key=="status"){
				$row[$i]['statusPcode']=$row[$i]['status'];
				
			
				$row[$i]['status']=getFieldValue("select $sql_str AS codeName from pubcode where codeKinds='bill' AND codeValue='{$row[$i]['status']}'","codeName");
				
				
				$row[$i]['cancelDate'] = "";
				if($row[$i]['statusPcode'] == '6')
				{
					$cancelDate = getFieldValue( " SELECT cdate FROM orderlog WHERE oid = '".$row[$i]['id']."' AND status = '6'" , "cdate");
					if(!empty($cancelDate))
					{
						$row[$i]['cancelDate'] = $cancelDate;
					}
					else
					{
						$mtime = date("Y-m-d", strtotime($row[$i]['mtime']));
						$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values 
						('{$row[$i]['id']}','$mtime','6','$now','$now','{$row[$i]['memberid']}');";
						$db->setQuery($sql);
						$db->query();
						$row[$i]['cancelDate'] = $mtime;
					}
				}
				
			}else if($key=="payType"){
				$row[$i]['payType']=pay_type($row[$i]['payType']);
			}else if($key=="dlvrType"){
				$row[$i]['dlvrType']=take_type(null,$row[$i]['dlvrType']);
			}else if($key=="exportChk"){
				$row[$i]['exportChk']= (empty($row[$i]['exportChk'])) ? _NO : _YES;
			}
		}
		
		if($row[$i]['dlvrType'] == take_type(null,2))
		{
			$row[$i]['dlvrLocation'] = getFieldValue(" SELECT dlvrLocation FROM members WHERE id = '".$row[$i]['memberid']."' " , "dlvrLocation");
		}
		$row[$i]['dlvrLocation'] = (empty($row[$i]['dlvrLocation'])) ? "" : $row[$i]['dlvrLocation'];
		
		
		$orderNum_tmp = str_replace("-","",$row[$i]['orderNum']);
		$row[$i]['payDate'] = getFieldValue("SELECT ctime FROM orderctblog WHERE lidm like '{$orderNum_tmp}%' AND type = 'Return' AND status = '0' AND errcode = '00' ","ctime");
		$row[$i]['payDate'] = (empty($row[$i]['payDate'])) ? '' : $row[$i]['payDate'];
		
		$data[] = $row[$i];
	}
	
	
	

	
	
	$sql = " SELECT * FROM region ";
	$db->setQuery( $sql );
	$addr_arr = $db->loadRowList();
	$addr_list = array();
	if(count($addr_arr) > 0)
	{
		foreach($addr_arr as $row)
		{
			$addr_list[$row['id']] = $row['name'];
		}
	}
	
	if(empty($where_str))
	{
		$startDate = date("Y-m-d H:i:s", strtotime("-6 month"));
		$where_str.=" AND A.ctime>='$startDate'";
	}
	
	
	$orderIdList = array_map(function($val) {
		return $val['id'];
	}, $data);
	if ($orderIdList) {
		$where_str .= " AND A.id IN ('".implode("','", $orderIdList)."')";
	}
	
	
	$sql_str1 = " B.format1name  ";
	$sql_str2 = " B.format2name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str1 = " ( SELECT 
						CASE P1.`name_".$_SESSION[$conf_user]['syslang']."` 
							WHEN null THEN P1.name  
							WHEN '' THEN P1.name 
							ELSE P1.`name_".$_SESSION[$conf_user]['syslang']."` 
						END
						FROM proformat P1 WHERE P1.id = B.format1 ) ";
		
		$sql_str2 = " ( SELECT 
						CASE P2.`name_".$_SESSION[$conf_user]['syslang']."` 
							WHEN null THEN P2.name  
							WHEN '' THEN P2.name 
							ELSE P2.`name_".$_SESSION[$conf_user]['syslang']."` 
						END
						FROM proformat P2 WHERE P2.id = B.format2 ) ";
	}
	
	
	$sql = " select A.*, B.id as odid, B.pid as dtl_pid, B.format1 as dtl_format1, B.format2 as dtl_format2 ".
	" , B.quantity as dtl_quantity, B.unitAmt as dtl_unitAmt, B.subAmt as dtl_subAmt ".
	" , B.pv as dtl_pv, B.bv as dtl_bv , $sql_str1 as dtl_format1name, $sql_str2 as dtl_format2name ".
	" from $tablename A LEFT JOIN orderdtl B ON A.id = B.oid where A.combineid = '0' $where_str $orderstr ";
	$db->setQuery( $sql );
	$order_arr = $db->loadRowList();
	
	$memid_arr = array();
	$oid_arr = array();
	if(count($order_arr) > 0)
	{
		foreach($order_arr as $info)
		{
			$memid_arr[] = $info['memberid'];
			$oid_arr[] = $info['id'];
		}
	}
	
	
	$sql = " SELECT * FROM members WHERE id IN ('".implode("','", $memid_arr)."') ";
	$db->setQuery( $sql );
	$member_arr = $db->loadRowList();
	$member_list = array();
	if(count($member_arr) > 0)
	{
		foreach($member_arr as $row)
		{
			if(!empty($row['cardnumber']))
			{
				
			}
			
			$member_list[$row['id']] = $row;
		}
	}
	
	
	$sql = "SELECT A.id, COUNT(B.id) AS cnt FROM $tablename A,orderdtl B WHERE A.id=B.oid AND A.id IN ('".implode("','", $oid_arr)."') GROUP BY A.id ";
	$db->setQuery( $sql );
	$orderDtlCnt_arr = $db->loadRowList();
	$orderDtlCnt_list = array();
	$orderDtlFreeCnt_list = array();
	$orderDtlCntTmp_list = array();
	$orderDtlAmtTmp_list = array();
	if(count($orderDtlCnt_arr) > 0)
	{
		foreach($orderDtlCnt_arr as $row)
		{
			$orderDtlCnt_list[$row['id']] = $row['cnt'];
			$orderDtlCntTmp_list[$row['id']] = 0;
			$orderDtlAmtTmp_list[$row['id']] = 0;
		}
	}
	
	
	$sql = "SELECT A.id, COUNT(B.id) AS cnt FROM $tablename A,orderdtl B WHERE A.id=B.oid AND B.protype = 'freepro' AND A.id IN ('".implode("','", $oid_arr)."') GROUP BY A.id ";
	$db->setQuery( $sql );
	$orderDtlCnt_arr = $db->loadRowList();
	if(count($orderDtlCnt_arr) > 0)
	{
		foreach($orderDtlCnt_arr as $row)
		{
			$orderDtlFreeCnt_list[$row['id']] = $row['cnt'];
		}
	}
	
	
	
	$sql = " SELECT * FROM orderprodtl WHERE oid IN ('".implode("','", $oid_arr)."') ORDER BY id  ";
	$db->setQuery( $sql );
	$orderprodtl_arr = $db->loadRowList();
	$orderprodtl_list = array();
	if(count($orderprodtl_arr) > 0)
	{
		foreach($orderprodtl_arr as $row)
		{
			$orderprodtl_list[$row['odid']][] = $row;
		}
	}
	
	
	
	$sql = " SELECT pid, format1, format2, code FROM proinstock WHERE 1=1 ";
	$db->setQuery( $sql );
	$proinstock_arr = $db->loadRowList();
	$proinstock_code_list = array();
	if(count($proinstock_arr) > 0)
	{
		foreach($proinstock_arr as $row)
		{
			$proinstock_code_list[$row['pid']."||".$row['format1']."||".$row['format2']] = $row['code'];
		}
	}
	
	
	$sql_str = " name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str = " CASE `name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN name  
						WHEN '' THEN name 
						ELSE `name_".$_SESSION[$conf_user]['syslang']."` 
					END AS name";
	}
	
	$sql = " SELECT id, $sql_str, highAmt FROM products WHERE 1=1 ";
	$db->setQuery( $sql );
	$products_arr = $db->loadRowList();
	$products_name_list = array();
	$products_highAmt_list = array();
	if(count($products_arr) > 0)
	{
		foreach($products_arr as $row)
		{
			$products_name_list[$row['id']] = $row['name'];
			$products_highAmt_list[$row['id']] = $row['highAmt'];
		}
	}
	
	
	if(count($order_arr) > 0)
	{
		$orderId_tmpArr = array();
		foreach($order_arr as $key=>$info)
		{
			
			if($info['status'] != '0' && $info['status'] != '6' && $info['status'] != '8')
			{
				$orderId_tmpArr[] = $info['id'];
			}
		}
		$sql = " SELECT OB.orderId , OB.activeBundleId , OB.activeBundleName, OB.price AS OB_price, OB.pv AS OB_pv, OB.bv AS OB_bv , OBD.* FROM orderBundle OB LEFT JOIN orderBundleDetail OBD ON OB.id = OBD.orderBundleId WHERE OB.orderId IN ('".implode("','", $orderId_tmpArr)."')  ";
		$db->setQuery( $sql );
		$orderBundle_arr = $db->loadRowList();
		$orderBundle_list = array();
		$orderBundle_detail_list = array();
		if(count($orderBundle_arr) > 0)
		{
			foreach($orderBundle_arr as $row)
			{
				if(!in_array($row['orderBundleId'],$orderBundle_list[$row['orderId']]))
				{
					$orderBundle_list[$row['orderId']][] = $row['orderBundleId'];
				}
				$orderBundle_detail_list[$row['orderBundleId']][] = $row;
			}
		}
		
	}
	
		
	
	$printhtml = "<table>";
	$printhtml .= "<tr>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR1."oea01</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR2."oea02</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR3."oea61</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR4."oea1008</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR5."oea87</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR6."ta_oea02</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR7.",ta_oea06</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR8.",ta_oea07</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR9."oeaud02</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR10."ta_oea05</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR11."oeb01</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR12."oeb03</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR13."oeb04</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR14."oeb06</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR15."oeb12</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR16."oeb13</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR17."oeb14</th>";
	$printhtml .= "	<th>"._ORDER_EXPORT_STR18."oeb14t</th>";
	$printhtml .= "	<th>PV、ta_oeb01</th>";
	$printhtml .= "	<th>BV ta_oeb02</th>";
	$printhtml .= "</tr>";
	
	
	
	$printcsv = "";
	$printcsv .= _ORDER_EXPORT_STR1."oea01,"._ORDER_EXPORT_STR2."oea02,"._ORDER_EXPORT_STR3."oea61,"._ORDER_EXPORT_STR4."oea1008,"._ORDER_EXPORT_STR5."oea87,"._ORDER_EXPORT_STR6."ta_oea02,"._ORDER_EXPORT_STR7." ta_oea06,"._ORDER_EXPORT_STR8." ta_oea07,"._ORDER_EXPORT_STR19."oeaud02,"._ORDER_EXPORT_STR20.","._ORDER_EXPORT_STR10."ta_oea05,"._ORDER_EXPORT_STR11."oeb01,"._ORDER_EXPORT_STR12."oeb03,"._ORDER_EXPORT_STR13."oeb04,"._ORDER_EXPORT_STR14."oeb06,"._ORDER_EXPORT_STR21.","._ORDER_EXPORT_STR22.","._ORDER_EXPORT_STR15."oeb12,"._ORDER_EXPORT_STR23.","._ORDER_EXPORT_STR16."oeb13,"._ORDER_EXPORT_STR17."oeb14,"._ORDER_EXPORT_STR18."oeb14t,PV ta_oeb01,BV ta_oeb02,"._ORDER_EXPORT_STR24.","._ORDER_EXPORT_STR25.","._ORDER_EXPORT_STR26."\n";
	
	$printcsv_array = array();
	$printcsv_array[0] = $printcsv;
	
	
	
	$orderdtl_index = array();
	
	$orderid_arr = array();
	
	
	
	
	
		
	if(count($order_arr) > 0)
	{
		if(count($order_arr) > 300)
		{
			$printcsv = _ORDER_EXPORT_MSG."\n";
			$printcsv .= _ORDER_EXPORT_STR1."oea01,"._ORDER_EXPORT_STR2."oea02,"._ORDER_EXPORT_STR3."oea61,"._ORDER_EXPORT_STR4."oea1008,"._ORDER_EXPORT_STR5."oea87,"._ORDER_EXPORT_STR6."ta_oea02,"._ORDER_EXPORT_STR7." ta_oea06,"._ORDER_EXPORT_STR8." ta_oea07,"._ORDER_EXPORT_STR19."oeaud02,"._ORDER_EXPORT_STR20.","._ORDER_EXPORT_STR10."ta_oea05,"._ORDER_EXPORT_STR11."oeb01,"._ORDER_EXPORT_STR12."oeb03,"._ORDER_EXPORT_STR13."oeb04,"._ORDER_EXPORT_STR14."oeb06,"._ORDER_EXPORT_STR21.","._ORDER_EXPORT_STR22.","._ORDER_EXPORT_STR15."oeb12,"._ORDER_EXPORT_STR23.","._ORDER_EXPORT_STR16."oeb13,"._ORDER_EXPORT_STR17."oeb14,"._ORDER_EXPORT_STR18."oeb14t,PV ta_oeb01,BV ta_oeb02,"._ORDER_EXPORT_STR24.","._ORDER_EXPORT_STR25.","._ORDER_EXPORT_STR26."\n";
		}
		
		foreach($order_arr as $key=>$info)
		{
			if($key > 300)
			{
				break;
			}
			
			
			if($info['status'] == '0' || $info['status'] == '6' || $info['status'] == '8')
			{
				continue;
			}
			
			$oid = $info['id'];	
			$odid = $info['odid']; 
			$pid = $info['dtl_pid'];	
			
			
			$chk49 = false; 
			$cnt49_1 = 0; 	
			$cnt49_2 = 0;	
			if($orderprodtl_list[$odid] > 0)
			{
				foreach($orderprodtl_list[$odid] as $row)
				{
					if(strpos($row['note'],'第二件49折') !== false)
					{
						$chk49 = true;
						
						if(empty($row['pv']))
						{
							$cnt49_2 += 1;
						}
						else
						{
							$cnt49_1 += 1;
						}
					}
				}
			}
			
			
			
			$pro_code_where_str = "";
			if(!empty($info['dtl_format1']))
			{
				$pro_code_where_str .= " AND format1 = '{$info['dtl_format1']}' "; 
			}
			if(!empty($info['dtl_format2']))
			{
				$pro_code_where_str .= " AND format2 = '{$info['dtl_format2']}' "; 
			}
			$pro_code = $proinstock_code_list[$info['dtl_pid']."||".$info['dtl_format1']."||".$info['dtl_format2']];
			$pro_name = $products_name_list[$info['dtl_pid']];
			$pro_highAmt = $products_highAmt_list[$info['dtl_pid']];
			
			if($info['orderMode'] == 'addMember')
			{
				if($pro_code == '4713696386469')
				{
					$pro_code = '4713696386469';
					$pro_name = 'e化入會（贈精油潔淨凝膠一條）';
					$pro_highAmt = "0";
				}
				else
				{
					$pro_code = '4713696383420';
					$pro_name = 'e化入會（贈七彩夜光珠7mm-28顆）';
					$pro_highAmt = "880";
				}
			}
			
			
			if(!empty($info['finalPayDate']))
			{
				$info['buyDate'] = date("Y/m/d",strtotime($info['finalPayDate']));
			}
			
			
			
			
			$tmpStr1 = "";
			$tmpStr1 .= $info['orderNum'].",";
			$tmpStr1 .= $info['buyDate'].",";
			$tmpStr1 .= round( (intval($info['totalAmt']) / 1.05) ).",";
			$tmpStr1 .= $info['totalAmt'].",";
			$tmpStr1 .= $member_list[$info['memberid']]['ERPID'].",";
			$tmpStr1 .= $member_list[$info['memberid']]['name'].",";
			$tmpStr1 .= $info['pv'].",";
			$tmpStr1 .= $info['bv'].",";
			$tmpStr1 .= $info['dlvrName'].",";
			$tmpStr1 .= $info['dlvrMobile'].",";
			$tmpStr1 .= $info['dlvrZip'].$addr_list[$info['dlvrCity']].$addr_list[$info['dlvrCanton']].$info['dlvrAddr'].",";
			$tmpStr1 .= $info['orderNum'].",";
			
			
			$tmpStr2 = "";
			$tmpStr2 .= $pro_code.",";
			$tmpStr2 .= $pro_name.",";
			$tmpStr2 .= "\"".$info['dtl_format1name']."\",";
			$tmpStr2 .= "\"".$info['dtl_format2name']."\",";
			
			$orderBundle_dataOnly_chk = false;
			
			
			if(count($orderBundle_list[$oid]) > 0)
			{
				
				if(empty($pro_name))
				{
					$orderBundle_dataOnly_chk = true;
				}
				
				foreach($orderBundle_list[$oid] as $orderBundleId)
				{
					
					$obd_highAmt_sum = 0; 
					$obd_otherProduct_price_sum = $obd_otherProduct_pv_sum = $obd_otherProduct_bv_sum = 0; 
					foreach($orderBundle_detail_list[$orderBundleId] as $OBD_key=>$orderBundle_data)
					{
						$obd_highAmt_sum += intval($products_highAmt_list[$orderBundle_data['productId']]);
					}
					
					foreach($orderBundle_detail_list[$orderBundleId] as $OBD_key=>$orderBundle_data)
					{
						if($OBD_key == (count($orderBundle_detail_list[$orderBundleId])-1))
						{
							
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_price'] = $orderBundle_data['OB_price'] - $obd_otherProduct_price_sum;
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_pv'] = $orderBundle_data['OB_pv'] - $obd_otherProduct_pv_sum;
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_bv'] = $orderBundle_data['OB_bv'] - $obd_otherProduct_bv_sum;
						}
						else
						{
							
							
							$ratio = ($obd_highAmt_sum > 0) ?  intval($products_highAmt_list[$orderBundle_data['productId']]) / $obd_highAmt_sum : 0;
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_price'] = round($orderBundle_data['OB_price'] * $ratio , 0);
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_pv'] = round($orderBundle_data['OB_pv'] * $ratio , 0);
							$orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_bv'] = round($orderBundle_data['OB_bv'] * $ratio , 0);
							
							$obd_otherProduct_price_sum += $orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_price'];
							$obd_otherProduct_pv_sum += $orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_pv'];
							$obd_otherProduct_bv_sum += $orderBundle_detail_list[$orderBundleId][$OBD_key]['OBD_bv'];
						}
					}
					
					foreach($orderBundle_detail_list[$orderBundleId] as $OBD_key=>$orderBundle_data)
					{
						if(empty($orderdtl_index[$info['id']]))
						{
							$orderdtl_index[$info['id']] = 1;
						}
						else
						{
							$orderdtl_index[$info['id']] ++;
						}
						
						$pro_code = $proinstock_code_list[$orderBundle_data['productId']."||".$orderBundle_data['productFormat1']."||".$orderBundle_data['productFormat2']];
						$pro_name = $products_name_list[$orderBundle_data['productId']];
						$pro_highAmt2 = $products_highAmt_list[$orderBundle_data['productId']];
						$productSpecName_arr = explode("／",$orderBundle_data['productSpecName']);
						
						$printcsvStr = "";
						$printcsvStr .= $tmpStr1;
						$printcsvStr .= $orderdtl_index[$info['id']].",";
						$printcsvStr .= $pro_code.",";
						$printcsvStr .= $pro_name.",";
						$printcsvStr .= "\"".$productSpecName_arr[0]."\",";
						$printcsvStr .= "\"".$productSpecName_arr[1]."\",";
						$printcsvStr .= $orderBundle_data['quantity'].",";
						$printcsvStr .= $pro_highAmt2.",";
						
						$printcsvStr .= $orderBundle_data['OBD_price'].","; 
						$printcsvStr .= round( (intval($orderBundle_data['OBD_price']) / 1.05) ).","; 
						$printcsvStr .= $orderBundle_data['OBD_price'].",";; 
						$printcsvStr .= $orderBundle_data['OBD_pv'].","; 
						$printcsvStr .= $orderBundle_data['OBD_bv'].","; 
						
						$printcsvStr .= $orderBundle_data['activeBundleName'].",";
						
						
						if($info['payType'] == 3 )
						{
							$orderNum = str_replace("-","",$info['orderNum']);
							$last4digitpan = getFieldValue(" SELECT last4digitpan FROM orderctblog WHERE type = 'Return' AND status = '0' AND errcode = '00' AND lidm LIKE '$orderNum%' ","last4digitpan");
							$printcsvStr .= $last4digitpan.",";
						}
						else
						{
							$printcsvStr .= ",";
						}
							
						
						if((!empty($info['discount']) && $orderDtlCntTmp_list[$info['id']] == 1) || $orderDtlCntTmp_list[$info['id']] == 0 )
						{
							$printcsvStr .= "\"".$info['dlvrNote']."\"\n";
						}
						else
						{
							$printcsvStr .= "\n";
						}
						
						$printcsv .= $printcsvStr;
						$printcsv_array[intval($info['id'])] .= $printcsvStr;
						
					}
				}
				
				
				unset($orderBundle_list[$oid]);
			}
			
			if($chk49)
			{
				
				if($cnt49_1 > 0)
				{
					if(empty($orderdtl_index[$info['id']]))
					{
						$orderdtl_index[$info['id']] = 1;
					}
					else
					{
						$orderdtl_index[$info['id']] ++;
					}
					$info['dtl_quantity'] = $cnt49_1;
					
					$printcsvStr = "";
					$printcsvStr .= $tmpStr1;
					$printcsvStr .= $orderdtl_index[$info['id']].",";
					$printcsvStr .= $tmpStr2;
					$printcsvStr .= $info['dtl_quantity'].",";
					$printcsvStr .= $pro_highAmt.",";
					
					if(count($orderprodtl_list[$info['odid']]) > 0)
					{
						foreach($orderprodtl_list[$info['odid']] as $r)
						{
							if(!empty($r['note']))
							{
								$note_tmparr = explode(",",$r['note']);
								$info['actNotes'] = $note_tmparr[0];
							}
						}
					}
					
					
					$info['actNotes'] = "";
					$info['dtl_subAmt'] = intval($info['dtl_unitAmt'] * $info['dtl_quantity']);
					$info['dtl_unitAmt'] = round(intval($info['dtl_subAmt']) / intval($info['dtl_quantity']));
					
					$printcsvStr .= $info['dtl_unitAmt'].",";
					$printcsvStr .= round( (intval($info['dtl_subAmt']) / 1.05) ).",";
					$printcsvStr .= $info['dtl_subAmt'].",";
					$printcsvStr .= $info['dtl_pv'].",";
					$printcsvStr .= $info['dtl_bv'].",";
					
					
					if(!empty($info['actNotes']))
					{
						$printcsvStr .= $info['actNotes'].",";
					}
					else
					{
						if(intval($info['dtl_unitAmt']) == "0")
						{
							$printcsvStr .= _ORDER_EXPORT_STR27.",";
						}
						else
						{
							$printcsvStr .= ",";
						}
					}
					
					
					if($info['payType'] == 3 )
					{
						$orderNum = str_replace("-","",$info['orderNum']);
						
						$last4digitpan = getFieldValue(" SELECT last4digitpan FROM orderctblog WHERE type = 'Return' AND status = '0' AND errcode = '00' AND lidm LIKE '$orderNum%' ","last4digitpan");
						
						$printcsvStr .= $last4digitpan.",";
					}
					else
					{
						$printcsvStr .= ",";
					}
					
					
					
					
					if((!empty($info['discount']) && $orderDtlCntTmp_list[$info['id']] == 1) || $orderDtlCntTmp_list[$info['id']] == 0 )
					{
						$printcsvStr .= "\"".$info['dlvrNote']."\"\n";
					}
					else
					{
						$printcsvStr .= "\n";
					}
					
					$printcsv .= $printcsvStr;
					$printcsv_array[intval($info['id'])] .= $printcsvStr;
				}
				
				
				
				if($cnt49_2 > 0)
				{
					if(empty($orderdtl_index[$info['id']]))
					{
						$orderdtl_index[$info['id']] = 1;
					}
					else
					{
						$orderdtl_index[$info['id']] ++;
					}
					$info['dtl_quantity'] = $cnt49_2;
					
					$printcsvStr = "";
					$printcsvStr .= $tmpStr1;
					$printcsvStr .= $orderdtl_index[$info['id']].",";
					$printcsvStr .= $tmpStr2;
					$printcsvStr .= $info['dtl_quantity'].",";
					$printcsvStr .= $pro_highAmt.",";
					
					$info['actNotes'] = "";
					$info['dtl_subAmt'] = round(round(intval($info['dtl_unitAmt']) * 0.49) * intval($info['dtl_quantity']));
					$info['dtl_unitAmt'] = round(intval($info['dtl_subAmt']) / intval($info['dtl_quantity']));
					
					if(count($orderprodtl_list[$info['odid']]) > 0)
					{
						foreach($orderprodtl_list[$info['odid']] as $r)
						{
							if(!empty($r['note']))
							{
								$note_tmparr = explode(",",$r['note']);
								$info['actNotes'] = $note_tmparr[0];
							}
						}
					}
					
					$printcsvStr .= $info['dtl_unitAmt'].",";
					$printcsvStr .= round( (intval($info['dtl_subAmt']) / 1.05) ).",";
					$printcsvStr .= $info['dtl_subAmt'].",";
					$printcsvStr .= "0,";
					$printcsvStr .= "0,";
					
					if(!empty($info['actNotes']))
					{
						$printcsvStr .= $info['actNotes'].",";
					}
					else
					{
						if(intval($info['dtl_unitAmt']) == "0")
						{
							$printcsvStr .= _ORDER_EXPORT_STR27.",";
						}
						else
						{
							$printcsvStr .= ",";
						}
					}
					
					
					if($info['payType'] == 3 )
					{
						$orderNum = str_replace("-","",$info['orderNum']);
						
						$last4digitpan = getFieldValue(" SELECT last4digitpan FROM orderctblog WHERE type = 'Return' AND status = '0' AND errcode = '00' AND lidm LIKE '$orderNum%' ","last4digitpan");
						
						$printcsvStr .= $last4digitpan.",";
					}
					else
					{
						$printcsvStr .= ",";
					}
					
					
					
					
					if((!empty($info['discount']) && $orderDtlCntTmp_list[$info['id']] == 1) || $orderDtlCntTmp_list[$info['id']] == 0 )
					{
						$printcsvStr .= "\"".$info['dlvrNote']."\"\n";
					}
					else
					{
						$printcsvStr .= "\n";
					}
					
					$printcsv .= $printcsvStr;
					$printcsv_array[intval($info['id'])] .= $printcsvStr;
				}
				
				
				
			}
			else if(!$orderBundle_dataOnly_chk)
			{
				
				if(empty($orderdtl_index[$info['id']]))
				{
					$orderdtl_index[$info['id']] = 1;
				}
				else
				{
					$orderdtl_index[$info['id']] ++;
				}
				
				$printcsvStr = "";
				$printcsvStr .= $tmpStr1;
				$printcsvStr .= $orderdtl_index[$info['id']].",";
				$printcsvStr .= $tmpStr2;
				$printcsvStr .= $info['dtl_quantity'].",";
				$printcsvStr .= $pro_highAmt.",";
				
				$info['actNotes'] = "";
				if(count($orderprodtl_list[$info['odid']]) > 0)
				{
					$info['dtl_subAmt'] = 0;
					$info['dtl_unitAmt'] = 0;
					foreach($orderprodtl_list[$info['odid']] as $r)
					{
						$info['dtl_subAmt'] += $r['amt'];
						if(!empty($r['note']))
						{
							$note_tmparr = explode(",",$r['note']);
							$info['actNotes'] = $note_tmparr[0];
						}
					}
					
					$info['dtl_unitAmt'] = round(intval($info['dtl_subAmt']) / intval($info['dtl_quantity']));
					
				}
				else
				{
					if(!empty($info['discount']))
					{
						$orderDtlCntTmp_list[$info['id']]++;
						if($orderDtlCntTmp_list[$info['id']] < ($orderDtlCnt_list[$info['id']] - intval($orderDtlFreeCnt_list[$info['id']])))
						{
							$info['dtl_unitAmt'] =  round(round(intval($info['dtl_unitAmt']) / intval($info['sumAmt']),2) * intval($info['dcntAmt']));
							$info['dtl_subAmt'] = $info['dtl_unitAmt'] * $info['dtl_quantity'];
							
							$orderDtlAmtTmp_list[$info['id']] += intval($info['dtl_subAmt']);
						}
						else if($orderDtlCntTmp_list[$info['id']] == ($orderDtlCnt_list[$info['id']] - intval($orderDtlFreeCnt_list[$info['id']])))
						{
							$info['dtl_subAmt'] = $info['dcntAmt'] - $orderDtlAmtTmp_list[$info['id']];
							$info['dtl_unitAmt'] = round(intval($info['dtl_subAmt']) / intval($info['dtl_quantity']));
						}
						else
						{
							$info['dtl_subAmt'] = "0";
							$info['dtl_unitAmt'] = "0";
						}
					}
				}
				
				
				$printcsvStr .= $info['dtl_unitAmt'].",";
				$printcsvStr .= round( (intval($info['dtl_subAmt']) / 1.05) ).",";
				$printcsvStr .= $info['dtl_subAmt'].",";
				$printcsvStr .= $info['dtl_pv'].",";
				$printcsvStr .= $info['dtl_bv'].",";
				
				
				if(!empty($info['actNotes']))
				{
					$printcsvStr .= $info['actNotes'].",";
				}
				else
				{
					if(intval($info['dtl_unitAmt']) == "0")
					{
						$printcsvStr .= _ORDER_EXPORT_STR27.",";
					}
					else
					{
						$printcsvStr .= ",";
					}
				}
				
				
				if($info['payType'] == 3 )
				{
					$orderNum = str_replace("-","",$info['orderNum']);
					
					$last4digitpan = getFieldValue(" SELECT last4digitpan FROM orderctblog WHERE type = 'Return' AND status = '0' AND errcode = '00' AND lidm LIKE '$orderNum%' ","last4digitpan");
					
					$printcsvStr .= $last4digitpan.",";
				}
				else
				{
					$printcsvStr .= ",";
				}
				
				
				
				
				if((!empty($info['discount']) && $orderDtlCntTmp_list[$info['id']] == 1) || $orderDtlCntTmp_list[$info['id']] == 0 )
				{
					$printcsvStr .= "\"".$info['dlvrNote']."\"\n";
				}
				else
				{
					$printcsvStr .= "\n";
				}
				
				$printcsv .= $printcsvStr;
				$printcsv_array[intval($info['id'])] .= $printcsvStr;
				
			}
			
			
			if(!empty($info['dlvrFee']) && (empty($order_arr[$key+1]) || (!empty($order_arr[$key+1]) && $order_arr[$key]['id'] != $order_arr[$key+1]['id'])))
			{
				
				$printcsvStr = "";
				$printcsvStr .= $info['orderNum'].",";
				$printcsvStr .= $info['buyDate'].",";
				$printcsvStr .= round( (intval($info['totalAmt']) / 1.05) ).",";
				$printcsvStr .= $info['totalAmt'].",";
				$printcsvStr .= $member_list[$info['memberid']]['ERPID'].",";
				$printcsvStr .= $member_list[$info['memberid']]['name'].",";
				$printcsvStr .= "0,";
				$printcsvStr .= "0,";
				$printcsvStr .= $info['dlvrName'].",";
				$printcsvStr .= $info['dlvrMobile'].",";
				$printcsvStr .= $info['dlvrZip'].$addr_list[$info['dlvrCity']].$addr_list[$info['dlvrCanton']].$info['dlvrAddr'].",";
				$printcsvStr .= $info['orderNum'].",";
				$printcsvStr .= ($orderdtl_index[$info['id']]+1).",";
				$printcsvStr .= "MISC-160,";
				$printcsvStr .= _ORDER_EXPORT_STR28.",";
				$printcsvStr .= ",";
				$printcsvStr .= ",";
				$printcsvStr .= "1,";
				$printcsvStr .= $info['dlvrFee'].",";
				$printcsvStr .= $info['dlvrFee'].",";
				$printcsvStr .= round( (intval($info['dlvrFee']) / 1.05) ).",";
				$printcsvStr .= $info['dlvrFee'].",";
				$printcsvStr .= "0,";
				$printcsvStr .= "0,";
				$printcsvStr .= ",";
				$printcsvStr .= ",";
				$printcsvStr .= ",\n";
				
				$printcsv .= $printcsvStr;
				$printcsv_array[intval($info['id'])] .= $printcsvStr;
			}
			
			if(empty($info['exportChk']))
			{
				$orderid_arr[] = $info['id'];
			}
			
		}
	}
	
	$printhtml .= "</table>";
	
	
	$_SESSION[$conf_user]['order_printcsv']=$printcsv;
	$_SESSION[$conf_user]['order_printcsv_array']=$printcsv_array;
		
	
	if(count($orderid_arr) > 0)
	{
		$_SESSION[$conf_user]['orderid_arr']=$orderid_arr;
	}
	
	
	$arrJson['orderExport'] = getFieldValue(" SELECT orderExport FROM adminmanagers WHERE id= '{$_SESSION[$conf_user]['uid']}' ","orderExport");
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['printhtml'] = $printhtml;
	$arrJson['printcsv_array'] = $printcsv_array;
	$arrJson['cnt'] = $pagecnt;

	JsonEnd($arrJson);
}

function showdetail(){
	global $db,$conf_user,$tablename;
	ini_set('display_errors','1');

	$arrJson = array();
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ));

	$sql = "SELECT * FROM $tablename WHERE id = '$id'";
	$db->setQuery( $sql );
	$r = $db->loadRow();	
	
	if(count($r)>0)
	{
		$data = array();
		foreach($r as $key=>$row){
			if($key=="memberid"){
				$data['membername']=getFieldValue("select name from members where id='{$row}'","name");
			}else if($key=="status"){
				$row=intval($row);
				
			}else if($key=="payType"){
				$data['payTypeCode']=$row;
				$row=pay_type($row);
			}else if($key=="dlvrType"){
				$row=take_type(null,$row);
			}
			if($key=="dlvrCity" || $key=="dlvrCanton" || $key=="dlvrState"){
				$data[$key]['id']=$row;
			}else if($key=="dlvrTime"){
				$data[$key]=$row;
			}else{
				$data[$key]=$row;
			}
		}
		
		if($data['invoiceType']==0){
			$data['invoiceTypeStr']=_ORDER_INVOICETYPESTR0;
		}else if($data['invoiceType']==1){
			$data['invoiceTypeStr']=_ORDER_INVOICETYPESTR1;
		}else if($data['invoiceType']==2){
			$data['invoiceTypeStr']=_ORDER_INVOICETYPESTR2;
			
		}
		
		if(!$data['dlvrDate']){
			$data['dlvrDate']="";
		}
		
		$data['delayCnt']=getFieldValue("select delayCnt from members where id='{$r['memberid']}'","delayCnt");
		$data['dlvrAddr']=$data['dlvrAddr'];
	}
	
	
	$sql = " SELECT * FROM orders WHERE combineid = '$id'";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$ori_order=array();
	foreach($r as $row){
		$ori_order[]=$row['id'];
		$ori_order_info[$row['id']]=$row;
	}
	
	if(count($ori_order) > 0)
	{
		$sql = "SELECT orderid,pid,discount,notes FROM activeRecord WHERE memberid = '{$data['memberid']}' AND orderid IN (".implode(",",$ori_order).")";
		$db->setQuery( $sql );
		$r = $db->loadRowList();
		$activeRecord = array();
		foreach($r as $row)
		{
			if(!empty($row['pid']))
			{
				$arr = explode("||",$row['pid']);
				foreach($arr as $pid)
				{
					if(!empty($pid))
					{
						$activeRecord[$row['orderid']][$pid] = "【".$row['notes']."】";
					}
				}
			}
			else
			{
				$activeRecord[$row['orderid']]['notes'] .= "【".$row['notes']."】";
			}
			
			$activeRecord[$row['orderid']]['discount'] += $row['discount'];
			$activeRecord[$row['orderid']]['subAmt'] = $ori_order_info[$row['orderid']]['dcntAmt'];
			
		}
		
		$data['activeRecord']=$activeRecord;
	}
	else
	{
		$sql = "SELECT discount,notes FROM activeRecord WHERE memberid = '{$data['memberid']}' AND orderid='$id'";
		$db->setQuery( $sql );
		$r = $db->loadRow();
		$data['actionDiscount']=$r['discount']*-1;
		$data['actionNotes']=!$r['notes']?'':$r['notes'];
		
		
		$sql = "SELECT discount,notes FROM activeRecord WHERE memberid = '{$data['memberid']}' AND orderid='$id' AND activePlanid = '10'";
		$db->setQuery( $sql );
		$r = $db->loadRow();
		$data['actionNotes']=!$r['notes']?'':$r['notes'];
	}
	
	
	
	$sql_str = "codeName";
	if($_SESSION[$conf_user]['syslang'])
	{
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs";
				break;
			case 'en':
				$sql_str = "codeName_en";
				break;
			case 'in':
				$sql_str = "codeName_in";
				break;
			default :
				$sql_str = "codeName";
				break;
		}
	}
	
	
	
	$sql = "SELECT * FROM pubcode where deleteChk=0 AND codeKinds in ('bill','payType','dlvrType','invoiceType') order by odring";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$pcode=array();
	foreach($r as $row){
		$info=array();
		$info['codeName']=$row[$sql_str];
		if($row['codeName_chs']){
			$info['codeName_chs']=$row['codeName_chs'];
		}
		$info['odring']=$row['odring'];
		$info['codeValue']=intval($row['codeValue']);
		$pcode[$row['codeKinds']][]=$info;
	}
	
	
	$sql_strB = " B.name  ";
	$sql_str1 = " A.format1name  ";
	$sql_str2 = " A.format2name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_strB = " CASE B.`name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN B.name  
						WHEN '' THEN B.name 
						ELSE B.`name_".$_SESSION[$conf_user]['syslang']."` 
					END AS name";
					
		$sql_str1 = " ( SELECT 
						CASE P1.`name_".$_SESSION[$conf_user]['syslang']."` 
							WHEN null THEN P1.name  
							WHEN '' THEN P1.name 
							ELSE P1.`name_".$_SESSION[$conf_user]['syslang']."` 
						END
						FROM proformat P1 WHERE P1.id = A.format1 ) AS format1name ";
		
		$sql_str2 = " ( SELECT 
						CASE P2.`name_".$_SESSION[$conf_user]['syslang']."` 
							WHEN null THEN P2.name  
							WHEN '' THEN P2.name 
							ELSE P2.`name_".$_SESSION[$conf_user]['syslang']."` 
						END
						FROM proformat P2 WHERE P2.id = A.format2 ) AS format2name ";
	}
	
	$sql = "SELECT A.orioid,A.pid,A.unitAmt,A.subAmt,A.quantity,A.protype,$sql_strB,A.actionNotes,B.highAmt, $sql_str1 , $sql_str2 FROM orders C,orderdtl A,products B WHERE C.id=A.oid AND A.oid = '$id' AND A.pid=B.id";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$dtl=array();
	foreach($r as $row){
		$info=array();
		$info['orioid']=$row['orioid'];
		$info['pid']=$row['pid'];
		$info['realAmt']=$row['unitAmt'];
		$info['highAmt']=$row['highAmt'];
		$info['quantity']=$row['quantity'];
		$info['totalAmt']=$row['unitAmt']*$row['quantity'];
		$info['name']=$row['name'];
		
		$format1name = $row['format1name'];
		$format2name = $row['format2name'];
		$formatStr = "";
		if(!empty($format1name))
		{
			$formatStr .= $format1name;
		}
		if(!empty($format2name))
		{
			if(!empty($formatStr))
			{
				$formatStr .= " - ".$format2name;
			}
			else
			{
				$formatStr .= $format2name;
			}
		}
		if(!empty($formatStr))
		{
			$info['name'] .= "【".$formatStr."】";
		}
		
		
		$info['protype']=$row['protype'];
		$info['actionNotes']=$row['actionNotes'];
		
		if(!empty($row['orioid']))
		{
			$dtl[$row['orioid']][]=$info;
		}
		else
		{
			$dtl[]=$info;
		}
		
	}
	
	if(count($ori_order) > 0)
	{
		$arrJson['combinorder'] = 1;
	}
	else
	{
		$arrJson['combinorder'] = 0;
	}
	
	
	if($data['payTypeCode'] == "3")
	{
		
		$orderNum = str_replace("-","",$data['orderNum']);
		
		$last4digitpan = getFieldValue(" SELECT last4digitpan FROM orderctblog WHERE type = 'Return' AND lidm LIKE '{$orderNum}%' AND status = '0' AND errcode = '00' ","last4digitpan");
		
		if(!empty($last4digitpan))
		{
			$data['last4digitpan'] = $last4digitpan;
		}
		
	}
	
	
	if($data['payTypeCode'] == '6')
	{
		$orderNum = str_replace("-","",$data['orderNum']);
		
		$last4digitpan = getFieldValue(" SELECT last_4_digit_of_pan FROM orderanLog WHERE type = 'return' AND orderNum LIKE '{$orderNum}%' AND retCode = '1' ","last_4_digit_of_pan");
		
		if(!empty($last4digitpan))
		{
			$data['last4digitpan'] = $last4digitpan;
		}
	}
    

    $sql="select * from orderBundleDetail where exists(select 1 from orderBundle A where A.orderId='{$data['id']}' AND A.id=orderBundleId)";
    $db->setQuery($sql);
    $r=$db->loadRowList();
    $orderBundleDetailObj=array();
    foreach($r as $value){
        $orderBundleDetailObj[$value['orderBundleId']][]=$value;
    }

    $sql="select * from orderBundle where orderId='{$data['id']}'";
    $db->setQuery($sql);
    $orderBundleArray=$db->loadRowList();
    foreach($orderBundleArray as $key=>$value){
        $orderBundleArray[$key]['orderBundleDetail']=$orderBundleDetailObj[$value['id']];
    }
	

	
	$_SESSION[$conf_user]['order_id']=$id;
	
	$moneyList = getLanguageList("money");	
	$sysCurrency = $moneyList[0]['code'];
	
	$arrJson['dtl'] = $dtl;
	$arrJson['data'] = $data;
	$arrJson['orderBundleArray'] = $orderBundleArray;
	$arrJson['pcode'] = $pcode;
	$arrJson['status'] = "1";
	$arrJson['sysCurrency'] = $sysCurrency;
	JsonEnd($arrJson);
	
}

function updatepage() {
	global $db, $conf_user,$tablename;
	$arrJson = array();
	
	$now=date("Y-m-d H:i:s");
	$today=date("Y-m-d");
	$id = global_get_param( $_REQUEST, 'id', null );
	$status = global_get_param( $_REQUEST, 'status', null );
	
	
	if(!empty($id))
	{
		$ori_status = getFieldValue(" SELECT status FROM $tablename WHERE id = '$id'", "status");
	}
	

	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,pagetype,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','dir','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	$sql="update $tablename set status='$status' where id='$id'";
	
	$msg = $id ? _COMMON_QUERYMSG_UPD_SUS : _COMMON_QUERYMSG_ADD_SUS;
	
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_ADD_ERR;
		JsonEnd($arrJson);
	}
	$sql = "select * from  $tablename where id='$id'";
	$db->setQuery( $sql );
	$order_arr = $db->loadRow();
		
	$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$id','$today','$status','$now','$now','{$order_arr['memberid']}');";
	$db->setQuery($sql);
	$db->query();
	if($status==1 || $status==3 || $status==4)
	{
		
		
		if($order_arr['pointchk'] == '0')
		{
			$sql="update members set pv=pv+'{$order_arr['pv']}',bv=bv+'{$order_arr['bv']}',bonus=bonus+'{$order_arr['bonus']}' where id='{$order_arr['memberid']}';";
			$db->setQuery($sql);
			$db->query();
			
			$sql="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
					values ('{$order_arr['memberid']}','$today','{$order_arr['bonus']}',0,'$id','$now','$now','{$order_arr['memberid']}');";
			$db->setQuery($sql);
			$db->query();
			
			
			$sql="update $tablename set pointchk=1 where id='$id';";
			$db->setQuery($sql);
			$db->query();
		}
		
		
		if($order_arr['orderMode'] == 'addMember')
		{
			
			$sql="update members set salesChk=1 where id='{$order_arr['memberid']}';";
			$db->setQuery($sql);
			$db->query();
			
			
			$sql="update members set payDate='$today' where id='{$order_arr['memberid']}' AND payDate = '' ;";
			$db->setQuery($sql);
			$db->query();
		}
	}
	
	if($status==3){
		$sql = "select * from siteinfo";
		$db->setQuery( $sql );
		$siteinfo_arr = $db->loadRow();
		

		$sql="update $tablename set freecoin=0 where id='$id';";
		$db->setQuery($sql);
		$db->query();
		
		$code=md5($order_arr['memberid'].$id.$order_arr['memberid']);
		$sql="insert into requestLog (ctime,memberid,code,type,var01) values ('$now','{$order_arr['memberid']}','$code','orderurl','member_page/orderdtl/$id')";
		$db->setQuery($sql);
		$db->query();
		$from = $siteinfo_arr['email'];
		
		$fromname = $siteinfo_arr['name'];
		$addr = $siteinfo_arr['addr'];
		if($_SESSION[$conf_user]['syslang'])
		{
			$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
			$addr = $siteinfo_arr['addr_'.$_SESSION[$conf_user]['syslang']];
		}
		$sendto = array(array("email"=>$order_arr['email'],"name"=>$order_arr['dlvrName']));
		
		$subject = $fromname." - "._ORDER_SHIPPING_MSG." (".date("Y-m-d H:i:s").")";
		$body = "
		<html>
		<head>
				<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
				<title>$fromname "._ORDER_SHIPPING_MSG."</title>
	
				</head>
		<body style=\"margin:0;padding:0;\">
			<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
				<h3 style=\"letter-spacing:1px;\">"._ORDER_SHIPPING_MSG1." ".$order_arr['dlvrName']." "._ORDER_SHIPPING_MSG2."</h3>
				<p style=\"line-height:180%;\">"._ORDER_SHIPPING_MSG3."</p>
				<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
				<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
					<tbody>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG4."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">{$order_arr['orderNum']}</td>
						</tr>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG5."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">{$order_arr['buyDate']}</td>
						</tr>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG6."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">".number_format($order_arr['totalAmt'])."</td>
						</tr>
						<tr>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><strong>"._ORDER_SHIPPING_MSG10."</strong></td>
							<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\"><a href=\"http://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/$code\">http://".$_SERVER['HTTP_HOST']."/member_page/orderdtl/$code</a></td>
						</tr>
					</tbody>
				</table>
				<br>
				<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$fromname</strong><br>
				"._ORDER_SHIPPING_MSG11."{$siteinfo_arr['tel']}&emsp;&emsp;"._ORDER_SHIPPING_MSG12."$addr<br>
				"._ORDER_SHIPPING_MSG13."{$siteinfo_arr['email']}</p>
				
			</div>
		</body>
		</html>
		";
	
		$rs = global_send_mail($from,$fromname,$sendto,$subject,$body);
	}else if($status==4){
		$sql="update members set coin=coin+'{$order_arr['freecoin']}' where id='{$order_arr['memberid']}';";
		$db->setQuery($sql);
		$db->query();
		
		$sql="update $tablename set freecoin=0 where id='$id';";
		$db->setQuery($sql);
		$db->query();
		
		
		
		
		if($order_arr['pointchk'] == '0' && false)
		{
			$sql="update members set pv=pv+'{$order_arr['pv']}',bv=bv+'{$order_arr['bv']}',bonus=bonus+'{$order_arr['bonus']}' where id='{$order_arr['memberid']}';";
			$db->setQuery($sql);
			$db->query();
			
			$sql="insert into bonusRecord (memberid,rDate,amt,status,orderid,ctime,mtime,muser) 
					values ('{$order_arr['memberid']}','$today','{$order_arr['bonus']}',0,'$id','$now','$now','{$order_arr['memberid']}');";
			$db->setQuery($sql);
			$db->query();
			
			
			$sql="update $tablename set pointchk=1 where id='$id';";
			$db->setQuery($sql);
			$db->query();
		}
		
		
	}else if($status==6){
		$sql="update $tablename set freecoin=0 where id='$id';";
		$db->setQuery($sql);
		$db->query();
	}
	$results = array();
	
	if($status == 1 || $status == 3 || $status == 4){
		$get_point_url = "http://192.168.7.46/money_bank/public/api/front_orders/calc_points/".$order_arr['orderNum'];
		$results = file_get_contents($get_point_url);
	}
	
	
	order_instock($ori_status,$status,$id);
	
	$arrJson['status'] = "1";
	$arrJson['msg'] = $msg;
	$arrJson['points_msg'] = $results['error'];
	JsonEnd($arrJson);
}


function deletepage() {
	global $db, $conf_user,$tablename;
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
		$sql = "DELETE FROM $tablename WHERE  oid='$id'";
		$db->setQuery( $sql );
		$db->query();
		$arrJson['status'] = "1";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_SUS;
	}
		
	JsonEnd($arrJson);
}

function shipDate_update(){
	global $db,$conf_user;
	ini_set('display_errors','1');
	$res = array();
	$shipDate = global_get_param( $_POST, 'shipDate', null );
	$today = date('Y-m-d');
	if($shipDate < $today){
		$res['status'] = '2';
		$res['msg'] = _ORDER_SHIPDATE_ERROR;
		JsonEnd($res);
	}

	//貨運產生標籤

	// $res = array();
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

	$from = [
		"State" => 'CA',
		"ZIPCode" => '90245',
		"Country" => 'US'
	];
	$to = [
		"ZIPCode" => '80204'
	];
	$rate = [
		"From" => $from,
		"To" => $to,
		"ShipDate" => '2021-08-25'
	];

	$SampleGetRates = [
		"Authenticator" => $AuthenticatorToken,
		"Rate" => $rate
	];
	$order_id=intval($_SESSION[$conf_user]['order_id']);
	$sql = "SELECT * FROM orders where id = '$order_id'";
	$db->setQuery($sql);
	$od = $db->loadRow();

	$d=[
        'Authenticator' => $AuthenticatorToken,
        'Address' => [
            "FullName"	=> 	$od['dlvrName'],
			"Address1"	=>	$od['dlvrAddr'],
			"City" 		=> 	$od['dlvrCity'],
            'State'     => 	$od['dlvrStateStr']
            ]
        ];
	// $d=[
	// 'Authenticator' => $AuthenticatorToken,
	// 'Address' => [
	// 	"FullName"	=> 'Evan Chen',
	// 	"Address1"	=>'12959 Coral Tree Place',
	// 	"City" 		=> 'Los Angeles',
	// 	'State'     => 'CA',
	// 	'ZIPcode'   => '90066'
	// 		]
	// ];
	$cleanseToAddressResponse = $client -> CleanseAddress($d);
	if (!$cleanseToAddressResponse->CityStateZipOK) {
		JsonEnd(array('err'=>'yes','code'=>_('Bad customer adress')));  
	}

	// $client = new SoapClient($wsdl, array('trace' => 1));
	$auth = $client->AuthenticateUser($authData);
	$AuthenticatorToken = $auth->Authenticator;

	$company=[
        'Authenticator' => $AuthenticatorToken,
        'Address' => [
            'FullName'  => 'Homeway Biotech Corp - GoodARCH',
            'Address1'  => '78 South Rosemead Blvd',
			'City' 		=> 'Pasadena',
            'State'     => 'CA',
            'ZIPcode'   => '91107'
             ]
        ];
	$cleanseFromAddressResponse = $client -> CleanseAddress($company);
	if (!$cleanseFromAddressResponse->CityStateZipOK) {
		return array('err'=>'yes','code'=>_('Bad company adress'));  
    }
	// JsonEnd($cleanseFromAddressResponse);
	// $client = new SoapClient($wsdl, array('trace' => 1));
	$auth = $client->AuthenticateUser($authData);
	$AuthenticatorToken = $auth->Authenticator;
	// $shipDate = date('Y-m-d',strtotime('+3 Days'));
	$rateOptions = [
		'Authenticator' => $AuthenticatorToken,
        "Rate"      => [
			// 'From' => [
			// 	'FullName'  => 'Homeway Biotech Corp - GoodARCH',
			// 	'Address1'  => '78 South Rosemead Blvd',
			// 	'City' 		=> 'Pasadena',
			// 	'State'     => 'CA',
			// 	'ZIPCode'   => '91107',
			// 	'Country'	=> 'US',
			// 	'EmailAddress' => 'H2008@goodarch2u.com'
			// ],
			'From' => $cleanseFromAddressResponse -> Address,
			// 'To' => [
			// 	"FullName"	=> 'Evan Chen',
			// 	"Address1"	=> '12959 Coral Tree Place',
			// 	"City" 		=> 'Los Angeles',
			// 	'State'     => 'CA',
			// 	'ZIPCode'   => '90066',
			// 	'Country'	=> 'US',
			// 	'EmailAddress' => 'H2008@goodarch2u.com'
			// ],
			'To' => $cleanseToAddressResponse -> Address,
            'WeightLb'     => '4.0',
            'ServiceType'  => $od['dt'],
            "PackageType"   => "Package",
            "ShipDate"  => $shipDate,
            "InsuredValue"  => '0.0',
			"AddOns"	=>	[
				"AddOnType"	=> 'US-A-DC'
			]
        ]
    ];
	// JsonEnd($rateOptions);
	$rates = $client -> getRates($rateOptions);

	$auth = $client->AuthenticateUser($authData);
	$AuthenticatorToken = $auth->Authenticator;
	$IntegratorTxID=time();
	$isSampleOnly = false;
	// $From = [
	// 	'FirstName'  => 'HomeWay',
	// 	'LastName'	=>	'Way',
	// 	'Address1'  => '3420 Ocean Park Bl',
	// 	'Address2'	=> 'Ste 1000',
	// 	'City' 		=> 'Santa Monica',
	// 	'State'     => 'CA',
	// 	'ZIPCode'  	=> '90405'
	// ];
	// $To = [
	// 	'FirstName' => 	'Charles',
	// 	'LastName'	=>	'Way',
	// 	"Address1"	=>	'1900 E Grand Ave',
	// 	"City" 		=> 	'El Segundo',
	// 	'State'     => 	'CA',
	// 	'ZIPCode'   => 	'90245'
	// ];
	$R = $rates -> Rates;
	$Rs = $R -> Rate;
	$Rs->FromZIPCode = '91107';
	$Rs->ToZIPCode = '90066';
	$Rs->AddOns = array(
		array(
			'AddOnType' => 'US-A-DC'
		),
		array(
			'AddOnType' => 'SC-A-HP'
		)
	);
	// // JsonEnd($Rs);
	// // $R = [
	// // 	"From" => $From,
	// // 	"To" => $To,
	// // 	"Amount" => '5.0',
	// // 	"ServicedType" => 'US-FC',
	// // 	'WeightLb' => '1',
	// // 	"ShipDate"=>'2021-08-25',
	// // 	"PackageType" => 'Package'
	// // ];
	$labelOptions = [
        'Authenticator'     =>	$AuthenticatorToken,
        'IntegratorTxID'    =>	$IntegratorTxID,
		'TrackingNumber'	=> '',
        'SampleOnly'        =>	$isSampleOnly,
        'ImageType'         => 'Pdf',
        'Rate'              => $Rs
    ];


	// $labelOptions = [
    //     'Authenticator'     =>	$AuthenticatorToken,
    //     'IntegratorTxID'    =>	$IntegratorTxID,
	// 	'TrackingNumber'	=> '',
    //     'SampleOnly'        =>	$isSampleOnly,
    //     'ImageType'         => 'Pdf',
    //     'Rate'              => $Rs,
	// 	'From'				=> $cleanseFromAddressResponse,
	// 	'To'				=> $cleanseToAddressResponse
    // ];

	// JsonEnd($labelOptions);
	// $client -> CreateIndicium($labelOptions);

	// $address = [
	// 	"FullName" => 'Evan Chen',
	// 	"Address1"=>'12959 Coral Tree Place',
	// 	"City" => 'Los Angeles'
	// ];
	// $cadd = [
	// 	"Authenticator" => $AuthenticatorToken,
	// 	"Address" => $address,
	// 	"FromZIPCode" => '90245'
	// ];

	try { 
		// $res['AuthenticatorToken'] = $client->AuthenticateUser($authData);
		// $res['GetCodewordQuestions'] = $client->GetCodewordQuestions($GetCodewordQuestions);
		// $res['objectresult3'] = $client->SetCodeWords($setCodewords);
		// $client->GetAccountInfo($authData);
		// $client->PurchasePostage($PurchasePostage);
		// $rates = $client->GetRates($rateOptions);
		// $cleanseFromAddressResponse = $client -> CleanseAddress($company);
		$labels = $client -> CreateIndicium($labelOptions);
		
	} catch (Exception $e) { 
		// echo "EXCEPTION: " . $e->getMessage() . "\n";
		// print_r($client->__getLastRequest());
		// JsonEnd($e);
		$res['err'] = $e->getMessage();
		JsonEnd($res);
		exit;
	}
	
	$res['status'] = '1';
	$res['labels'] = $labels;
	$fileName = $od['orderNum'];
	if($labels){
		$label_url = $labels->URL;
		file_put_contents('../../../upload/stamps/'.$fileName.'.pdf', fopen($label_url, 'r'));
		$sql = "update orders set has_label ='1',label_shipDate ='$shipDate' where orderNum = '$fileName'";
		$db->setQuery($sql);
		$db->query();
	}
	JsonEnd($res);
}
include( $conf_php.'common_end.php' ); 
?>