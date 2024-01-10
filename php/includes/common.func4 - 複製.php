<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

if (phpversion() < '4.2.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php41x.php" );
}
if (phpversion() < '4.3.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php42x.php" );
}
if (in_array( '_post', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  Post variable hack attempted.' );
}
if (in_array( '_get', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  GET variable hack attempted.' );
}
if (in_array( '_request', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  REQUEST variable hack attempted.' );
}


@set_magic_quotes_runtime( 0 );

if (@$globalConf_error_reporting === 0) {
	error_reporting( 0 );
} else if (@$globalConf_error_reporting > 0) {
	error_reporting( $globalConf_error_reporting );
}


function take_type($getamt=null,$getname=null,$getarray=null){
	global $db,$conf_user;
	$mode=getCartMode();
	$data=array();
	$data2=array();
	$sql="select * from payconf";
	$db->setQuery( $sql );
	$r = $db->loadRow();
	$pay_type=$_SESSION[$conf_user]['pay_type'];
	if(($r['homeDlvr']==1 && ( $pay_type==2 || $pay_type==3 || $pay_type==4 || $pay_type==6 || $pay_type==7)) || $getname || $getamt){
		
		$data[1]=array("id"=>1,"name"=>_EWAYS_TAKE_TYPE1,"amt"=>$r['homeDlvrAmt']);
	}
	
	$data2[1]=array("id"=>1,"name"=>_EWAYS_TAKE_TYPE1,"amt"=>$r['homeDlvrAmt']);
	
	if(($r['selfDlvr']==1 && ($pay_type==2 || $pay_type==3 || $pay_type==4 || $pay_type==5 || $pay_type==6 || $pay_type==7)) || $getname || $getamt){
		
		
	}
	
	$data2[2]=array("id"=>2,"name"=>_EWAYS_TAKE_TYPE2,"amt"=>0);
	
	if(($r['dlvrPay']==1 && $pay_type==1) || $getname || $getamt){
		
		$data[3]=array("id"=>3,"name"=>_EWAYS_TAKE_TYPE3,"amt"=>$r['dlvrAmt']);
	}
	
	$data2[3]=array("id"=>3,"name"=>_EWAYS_TAKE_TYPE3,"amt"=>$r['dlvrAmt']);
	
	if($getname){
		return $data2[$getname]['name'];
	}
	$take_type=1;
	if($pay_type==5){
		$take_type=2;
	}
	if($pay_type==1){
		$take_type=3;
	}
	$dlvrAmt=0;
	if($_SESSION[$conf_user]['take_type']){
		foreach($data as $key=>$row){
			if($row['id']==$_SESSION[$conf_user]['take_type']){
				$take_type=$row['id'];
				$dlvrAmt=$row['amt'];
				
			}
		}
	}
	
	if($getamt){
		return $dlvrAmt;
	}
	$cart=$_SESSION[$conf_user]["{$mode}_list"];
	$proArr=CartProductInfo2($cart);
	
	if($getarray)
	{
		if(count($data) == 0)
		{
			
			$data[1]=array("id"=>1,"name"=>_EWAYS_TAKE_TYPE1,"amt"=>$r['homeDlvrAmt']);
		}
		return $data;
	}
	else
	{
		JsonEnd(array("status" => 1, "data" =>$data,"take_type"=>$take_type,"dlvrAmt"=>intval($dlvrAmt)-intval($proArr['disDlvrAmt']),"disDlvrAmt"=>intval($proArr['disDlvrAmt'])));
	}
	
}

function pay_type($getname=null,$getarray=null){
	global $db,$conf_user;
	
	$data=array();
	$data2=array();
	$sql="select * from payconf";
	$db->setQuery( $sql );
	$r = $db->loadRow();
	if($r['dlvrPay']==1){
		
		$data[1]=array("id"=>1,"name"=>_EWAYS_PAY_TYPE1);
	}
	
	$data2[1]=array("id"=>1,"name"=>_EWAYS_PAY_TYPE1);
	
	if($r['bankPay']==1){
		
		$data[2]=array("id"=>2,"name"=>_EWAYS_PAY_TYPE2);
	}
	
	$data2[2]=array("id"=>2,"name"=>_EWAYS_PAY_TYPE2);
	
	if($r['creditallPay']==1){
		
		$data[3]=array("id"=>3,"name"=>_EWAYS_PAY_TYPE3);
	}
	
	$data2[3]=array("id"=>3,"name"=>_EWAYS_PAY_TYPE3);
	
	if($r['vanallPay']==1){
		
		$data[4]=array("id"=>4,"name"=>_EWAYS_PAY_TYPE4);
	}
	
	$data2[4]=array("id"=>4,"name"=>_EWAYS_PAY_TYPE4);
	
	
	
	$data2[5]=array("id"=>5,"name"=>_EWAYS_PAY_TYPE5);
	
	
	if($r['tspgPayCredit']==1){
		$data[6]=array("id"=>6,"name"=>_EWAYS_PAY_TYPE6);
	}
	$data2[6]=array("id"=>6,"name"=>_EWAYS_PAY_TYPE6);
	
	
	if($r['ccbPayVATM']==1){
		$data[7]=array("id"=>7,"name"=>_EWAYS_PAY_TYPE7);
	}
	$data2[7]=array("id"=>7,"name"=>_EWAYS_PAY_TYPE7);
	
	if($getname){
		return $data2[$getname]['name'];
	}
	
	$pay_type = 0;
	
	

	
	if($_SESSION[$conf_user]['pay_type']){
		foreach($data as $key=>$row){
			if($row['id']==$_SESSION[$conf_user]['pay_type']){
				$pay_type=$row['id'];
			}
		}
	}else{
		$_SESSION[$conf_user]['pay_type']=$pay_type;
	}
	
	if($getarray)
	{
		return $data;
	}
	else
	{
		JsonEnd(array("status" => 1, "data" =>$data,"pay_type"=>$pay_type));
	}
	
	
}

function getdbpagelinkdata($tablename, $fromid=0){
	global $db;
	$dbpageDate = array();
	if($fromid){
	
		$sql = "select * from dbpageLink where fromtable='$tablename' AND fromid = '$fromid'";
		$db->setQuery($sql);
		$r = $db->loadRow();
		if($r) {
			$dbpageDate['tablename'] = $r['totable'];
			$dbpageDate['databaseid'] = $r['pageid'] ? $r['pageid'] : $r['dirid'];
			$dbpageDate['databasename'] = $r['name'];
		}
	}
	return $dbpageDate;
}

function getProductFormat($id=0){
	global $db,$conf_user;
	
	$dataArr=array();
	if($id==0){
		return $dataArr;
	}
	
	$sql_str1 = "";
	$sql_str2 = "";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str1 .= " (select `name_".$_SESSION[$conf_user]['syslang']."` from proformat where id=A.format1) as `name1_".$_SESSION[$conf_user]['syslang']."` ,";
		$sql_str1 .= " (select `name_".$_SESSION[$conf_user]['syslang']."` from proformat where id=A.format1_type) as `title1_".$_SESSION[$conf_user]['syslang']."` ,";
		$sql_str2 .= " (select `name_".$_SESSION[$conf_user]['syslang']."` from proformat where id=A.format2) as `name2_".$_SESSION[$conf_user]['syslang']."` ,";
		$sql_str2 .= " (select `name_".$_SESSION[$conf_user]['syslang']."` from proformat where id=A.format2_type) as `title2_".$_SESSION[$conf_user]['syslang']."` ,";
	}

	$sql=" SELECT * FROM ( select 
			A.format1,(select name from proformat where id=A.format1) as name1,(select name from proformat where id=A.format1_type) as title1, {$sql_str1} 
			A.format2,(select name from proformat where id=A.format2) as name2,(select name from proformat where id=A.format2_type) as title2, {$sql_str2} 
			A.instock, A.instockchk ,(select odring from proformat where id=A.format1) as odring1 , B.odring
		  from proinstock A LEFT JOIN proformat B ON A.format2 = B.id 
		  where A.pid='$id' ) AS tbl order by odring1, odring ";
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$format1Arr=array();
	$format2Arr=array();
	foreach($r as $row){
		$format1=intval($row['format1']);
		$format2=intval($row['format2']);
		$name1=$row['name1'];
		$name2=$row['name2'];
		$title1=$row['title1'];
		$title2=$row['title2'];
		
		if($_SESSION[$conf_user]['syslang'] && $row['name1_'.$_SESSION[$conf_user]['syslang']])
		{
			$name1=$row['name1_'.$_SESSION[$conf_user]['syslang']];
		}
		if($_SESSION[$conf_user]['syslang'] && $row['name2_'.$_SESSION[$conf_user]['syslang']])
		{
			$name2=$row['name2_'.$_SESSION[$conf_user]['syslang']];
		}
		if($_SESSION[$conf_user]['syslang'] && $row['title1_'.$_SESSION[$conf_user]['syslang']])
		{
			$title1=$row['title1_'.$_SESSION[$conf_user]['syslang']];
		}
		if($_SESSION[$conf_user]['syslang'] && $row['title2_'.$_SESSION[$conf_user]['syslang']])
		{
			$title2=$row['title2_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$instock=intval($row['instock']);
		$instockchk=intval($row['instockchk']);								 
		
		if($instock>0 || true){
			
			if($instockchk == 1 && $instock <= 0)
			{
				continue;
			}
			
			$format1Arr[$format1]['id']=$format1;
			$format1Arr[$format1]['name']=$name1;
			$format2Arr[$format1][$format2]['id']=$format2;
			$format2Arr[$format1][$format2]['name']=$name2;
			$format2Arr[$format1][$format2]['instock']=$instock;
			$format2Arr[$format1][$format2]['instockchk']=$instockchk;												 
			$format2Arr2[$format1][] = array('id'=>$format2,'name'=>$name2,'instock'=>$instock,'instockchk'=>$instockchk);
		}
		
	}
	
	
	$tmp=array();
	foreach($format1Arr as $row){
		$tmp[]=$row;
	}
	$format1Arr=$tmp;
	
	$dataArr['format1title']=$title1;
	$dataArr['format1']=$format1Arr;
	$dataArr['format2title']=$title2;
	$dataArr['format2']=$format2Arr;
	$dataArr['format22']=$format2Arr2;
	
	$dataArr['formatonly'] = false;
	if(count($format1Arr) == 1 && count($format2Arr) == 1)
	{
		if(count($format2Arr[$format1Arr[0]['id']]) == 1)
		{
			$dataArr['formatonly'] = true;
			$dataArr['format1only'] = $format1Arr[0];
			
			foreach($format2Arr[$format1Arr[0]['id']] as $row)
			{
				$dataArr['format2only'] = $row;
			}
		}
		else
		{
			$dataArr['formatonly'] = true;
			$dataArr['format1only'] = $format1Arr[0];
		}
	}
	
	return $dataArr;
}

function LoginChk(){
	global $conf_user;
	$uid=intval($_SESSION[$conf_user]['uid']);
	if($uid==0){
		
		JsonEnd(array("status" => 0, "msg" =>_MEMBER_LOGIN_FIRST));
	}
	return $uid;
}



function getUserPermission(){
	global $conf_user;
	$uid=intval($_SESSION[$conf_user]['uid']);
	$functionsCht = getFieldValue(" SELECT functionsCht FROM adminmanagers WHERE locked ='0' AND id='$uid' ","functionsCht");
	
	$funclist = array();
	if(!empty($functionsCht))
	{
		$fun_arr = explode( "|||||" ,$functionsCht);
		if(count($fun_arr) > 0)
		{
			foreach($fun_arr as $row)
			{
				if(!empty($row))
				{
					$arr = explode( "|||" ,$row);
					$funclist[$arr[0]] = array("C"=>$arr[1],"U"=>$arr[2],"D"=>$arr[3],"R"=>$arr[4]);
				}
			}
		}
	}
	return $funclist;
}


function userPermissionChk($func){
	global $conf_user;
	
	$arrJson = array();
	
	
	if(empty($func))
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
		JsonEnd($arrJson);
	}
	
	
	if(count($_SESSION[$conf_user]['funclist']) == 0 || true)
	{
		$_SESSION[$conf_user]['funclist'] = getUserPermission();
	}
	

	if(!empty($_SESSION[$conf_user]['funclist']) && count($_SESSION[$conf_user]['funclist']) > 0)
	{
		$funclist = $_SESSION[$conf_user]['funclist'];
		
		
		if(count($funclist[$func]) == 0)
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
		
		
		if($funclist[$func]["C"] == "false" && $funclist[$func]["U"] == "false" && $funclist[$func]["D"] == "false" && $funclist[$func]["R"] == "false")
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
		
		$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
		
		
		if($task == 'list')
		{
			$search = global_get_param( $_REQUEST, 'search', null);
			if(!empty($search))
			{
				$task = 'search';
			}
		}
		
		
		if($task == 'operate')
		{
			$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
			if($action == '3')
			{
				$task = 'operate_D';
			}
			else
			{
				$task = 'operate_U';
			}
		}
		
		$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
		$task = ($task == "update" && empty($id)) ? 'add' : $task;
		
		switch ($task) {
			case "add": 
				$Permission = 'C';
				break;
			case "update": 
			case "operate_U":	
			case "publishChg":	
				$Permission = 'U';
				break;
			case "del": 
			case "operate_D":	
				$Permission = 'D';
				break;
			case "search": 
				$Permission = 'R';
				break;
			default:
				$Permission = 'R';
				break;
		}
		
		if($funclist[$func][$Permission] != 'true')
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
	}
	else
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
		JsonEnd($arrJson);
	}
	

}

function createUpdateSql($tablename, $dataArr) {
	$updatesql = "INSERT INTO $tablename (";
	$updateval = " VALUES (";
	$updateend = " ON DUPLICATE KEY UPDATE ";
	$i = 0;

	foreach($dataArr as $key => $val) {
		if(isset($val)) {
			if(++$i > 1) {
				$updatesql .= ",";
				$updateval .= ",";
				$updateend .= ",";
			}
			$updatesql .= "$key";
			$updateval .= "'$val'";
			$updateend .= "$key=VALUES($key)";
		}
	}
	$updatesql .= ")";
	$updateval .= ")";
	return $updatesql.$updateval.$updateend.";";
}


function getIP(){
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
	   $ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) 
	   $ip = $_SERVER['HTTP_X_FORWARDED'];
	elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) 
	   $ip = $_SERVER['HTTP_FORWARDED_FOR'];
	elseif (!empty($_SERVER['HTTP_FORWARDED'])) 
	   $ip = $_SERVER['HTTP_FORWARDED'];
	else 
	   $ip = $_SERVER['REMOTE_ADDR'];
	   
	return $ip;   
}

function getCartMode(){
	global $conf_user;
	return $_SESSION[$conf_user]["cart_list_mode"]?$_SESSION[$conf_user]["cart_list_mode"]:"cart";
}

function fieldExist($tablename, $fieldname) {
	global $db;
	$db->setQuery("DESCRIBE $tablename");
	$collist = $db->loadRowList();
	$returnVal = false;
	foreach($collist as $row){
		if($row['Field'] == $fieldname){
			$returnVal = true;
			break;
		}
	}
	return $returnVal;
	
}


function order_instock($ori_status = null , $status = null , $oid = null)
{
	global $db;
	
	$orderMode = getFieldValue(" SELECT orderMode FROM orders WHERE id = '$oid' ", "orderMode");
	
	
	if($ori_status == '0' && $status == '6' && !empty($oid) && $orderMode == 'addMember' )
	{
		
		$orderMode = getFieldValue(" SELECT orderMode FROM orders WHERE id = '$oid' ", "orderMode");
		if( $orderMode == 'addMember')
		{
			$memberid = getFieldValue(" SELECT memberid FROM orders WHERE id = '$oid' ", "memberid");
			
			
			$sql = "SELECT * FROM members WHERE id = '$memberid'";
			$db->setQuery( $sql );
			$members_arr = $db->loadRow();	
			
			$sql = "SELECT * FROM orders WHERE id = '$oid'";
			$db->setQuery( $sql );
			$orders_arr = $db->loadRow();	
			
			$sql = "SELECT * FROM orderdtl WHERE oid='$oid'";
			$db->setQuery( $sql );
			$orderdtl_arr = $db->loadRow();	
			
			$file = 'member_order.txt';
			$current = file_get_contents($file);
			$current .= implode("，",$members_arr)."\n";
			$current .= implode("，",$orders_arr)."\n";
			file_put_contents($file, $current);
			
			
			$sql = " INSERT INTO deletelog (mambers, orders, orderdtl, uid, uname)
				VALUES ('".implode("，",$members_arr)."', '".implode("，",$orders_arr)."', '".implode("，",$orderdtl_arr)."','0','系統執行'); ";
			$db->setQuery($sql);
			$db->query();
			
			
			
			
			
			$sql="BEGIN;";
			
			
			$sql .= " DELETE FROM orderBundleDetail WHERE exists(select 1 from orderBundle A WHERE A.orderId='$oid' AND A.id=orderBundleId) ; ";
			$sql .= " DELETE FROM orderBundle WHERE  orderId='$oid' ; ";
			$sql .= " DELETE FROM orderdtl WHERE  oid='$oid' ; ";
			
			
			$sql .= " DELETE FROM orders WHERE  id='$oid' ; ";
			
			
			$sql .= " DELETE FROM members WHERE  id='$memberid' ; ";
			
			$sql.="COMMIT;";
			
			
			$db->setQuery($sql);
			$r=$db->query_batch();
			
			
			return true;
		}
	}
	else if(($ori_status == '0' || !empty($ori_status)) && ($status == '0' || !empty($status)) && !empty($oid) )
	{
		$instock_chk = "";
		if( $ori_status != "8" && $ori_status != "6" && ($status == "8" || $status == "6"))
		{
			$instock_chk = " + ";
		}
		elseif( ( $ori_status == "8" || $ori_status == "6") &&  $status != "8" && $status != "6")
		{
			$instock_chk = " - ";
		}
		
		if(!empty($instock_chk))
		{
			$sql = " SELECT * FROM orderdtl WHERE oid = '$oid'";
			$db->setQuery($sql);
			$list = $db->loadRowList();
			if(count($list) > 0)
			{
				
				$sql="BEGIN;";
				
				foreach($list as $row)
				{
					$pid = $row['pid'];
					$quantity = $row['quantity'];
					$format1 = $row['format1'];
					$format2 = $row['format2'];
					$sql.="update proinstock set instock=instock $instock_chk '$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
				}
				
				
				$info_sql = " SELECT * FROM orders WHERE id = '$oid'";
				$db->setQuery($info_sql);
				$info = $db->loadRow();
				
				if($info['orderMode'] == 'bonus')
				{
					$sql .= "update members set bonus=bonus+'{$info['bonusAmt']}' where id='{$info['memberid']}';";
				}
				
				$sql.="COMMIT;";
				
				
				$db->setQuery($sql);
				$r=$db->query_batch();
            }
            
            $sql="select * from orderBundleDetail where exists(select 1 from orderBundle A WHERE A.orderId='$oid' AND A.id=orderBundleId)";
            $db->setQuery($sql);
            $list = $db->loadRowList();
            if($list && count($list)>0){
                
                $sql="BEGIN;";
                foreach($list as $value){
                    $pid = $value['productId'];
					$quantity = 1;
					$format1 = $value['productFormat1'];
					$format2 = $value['productFormat2'];
					$sql.="update proinstock set instock=instock $instock_chk '$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
                }
                $sql.="COMMIT;";
                
                
				$db->setQuery($sql);
				$r=$db->query_batch();
            }
            
		}
		
		return true;
	}
	else
	{
		return false;
	}
	
}


function cartProductClac($active_list = array(), $cart_list = array(), $activeExtraList = array(),$activeBundleCart=null)
{
	global $db,$conf_user;
	
	$uid=intval($_SESSION[$conf_user]['uid']);
	
	$salesChk = "0";    
	if(!empty($uid))
	{
		$salesChk = getFieldValue("select * from members where id='$uid'","salesChk"); 
	}
	$m_discount_rate = $_SESSION[$conf_user]['m_discount_rate'];
	
	$pvbvratio = (float)getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
	$active_disPro_list = array();
	$active_actPro_list = array();
	$active_usePro_list = array();
	
	
	
	$index_pro_list = array();	
	$index2_pro_list = array();	
	$index3_pro_list = array();	
	
	$index_88 = 0;
	
	if(count($active_list) > 0)
	{
		foreach($active_list as $row)
		{
			if( count($row['dispro']) > 0)
			{
				$info = array();
				$info["name"] = $row['name'];
				$info["activePlanid"] = $row['act']['activePlanid'];
				
				if($info["activePlanid"] == "1")  
				{
					$info["var01"] = intval($row['act']['var01']);	
					$info["var02"] = intval($row['act']['var02']);	
					
					foreach($row['dispro'] as $key2=>$row2)
					{
						foreach($cart_list as $key3=>$row3)
						{
							if($row3['id'] == $row2)
							{
								for($i = 1 ; $i <= intval($row3['num']) ; $i++)
								{
									$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
									$active_disPro_list[$row2."|".$index] = $info;
									$index_pro_list[$row2] = $index;
								}
							}
						}
					}
				}
				else if($info["activePlanid"] == "2")	
				{
					$info["var01"] = intval($row['act']['var01']);	
					$info["var02"] = intval($row['act']['var02']);	
					
					foreach($row['dispro'] as $key2=>$row2)
					{
						foreach($cart_list as $key3=>$row3)
						{
							if($row3['id'] == $row2)
							{
								for($i = 1 ; $i <= intval($row3['num']) ; $i++)
								{
									$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
									$active_disPro_list[$row2."|".$index] = $info;
									$index_pro_list[$row2] = $index;
								}
							}
						}
					}
				}
				else if($info["activePlanid"] == "3")	
				{
					$info["var01"] = intval($row['act']['var01']);	
					$info["var02"] = intval($row['act']['var02']);	
					$info["pv"] = intval($row['act']['pv']);
					$info["bv"] = intval($row['act']['bv']);
					
					
					$index_act = 1;	
					$tmp_sum = 0;
					$tmp_sum_pv = 0;
					$tmp_sum_bv = 0;
					foreach($row['dispro'] as $key2=>$row2)
					{
						$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
						
						if(  $index_act % $info["var01"] == 0  )
						{
							$info["amt"] = $info["var02"] - $tmp_sum;
							$info["amt_pv"] = $info["pv"] - $tmp_sum_pv;
							$info["amt_bv"] = $info["bv"] - $tmp_sum_bv;
							$tmp_sum = 0;
							$tmp_sum_pv = 0;
							$tmp_sum_bv = 0;
						}
						else
						{
							$info["amt"] = round( $info["var02"] / $info["var01"] );
							$info["amt_pv"] = round( $info["pv"] / $info["var01"] );
							$info["amt_bv"] = round( $info["bv"] / $info["var01"] );
							$tmp_sum += $info["amt"];
							$tmp_sum_pv += $info["amt_pv"];
							$tmp_sum_bv += $info["amt_bv"];
						}
						
						$active_disPro_list[$row2."|".$index] = $info;
						$index_pro_list[$row2] = $index;
						$index_act ++;
					}
				}
				else if($info["activePlanid"] == "12")	
				{
					$info["var01"] = intval($row['act']['var01']);	
					$info["var02"] = intval($row['act']['var02']);	
					
					
					foreach($row['dispro'] as $key2=>$row2)
					{
						$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
						
						$info["amt_pv"] = 0;
						$info["amt_bv"] = 0;
						
						$active_disPro_list[$row2."|".$index] = $info;
						$index_pro_list[$row2] = $index;
					}
				}
				else if($info["activePlanid"] == "13")	
				{					
					$info["var02"] = intval($row['act']['pv']);	
					
					
					foreach($row['dispro'] as $key2=>$row2)
					{
						$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
						
						
						
						
						$active_disPro_list[$row2."|".$index] = $info;
						$index_pro_list[$row2] = $index;
					}
				}
			}
			
			if(count($row['actproArr']))
			{
				foreach($row['actproArr'] as $row2)
				{
					$index = (!empty($index2_pro_list[$row2])) ? (intval($index2_pro_list[$row2]) + 1) : 1;
					
					$active_actPro_list[$row2."|".$index] = $row['act']['activePlanid'];
					$index2_pro_list[$row2] = $index;
				}
			}
			
			if(count($row['usepro']))
			{
				foreach($row['usepro'] as $row2)
				{
					$index = (!empty($index3_pro_list[$row2])) ? (intval($index3_pro_list[$row2]) + 1) : 1;
					
					$active_usePro_list[$row2."|".$index] = array("activePlanid"=>$row['act']['activePlanid'] , "name"=>$row['name']);
					$index3_pro_list[$row2] = $index;
				}
			}
			
		}
	}
	
	
	
	
	
	
	
	
	
	$index_cart_pro_list = array();


	if(count($cart_list) > 0 )
	{
		foreach($cart_list as $key=>$row)
		{
			
			$prodtl = array();
			$prodtl_amt_sum = 0;
			$prodtl_amt_pv = 0;
			$prodtl_amt_bv = 0;
			$prodtl_act = "";
			$prodtl_use_act = "";

			

			$activeExtraUseProductCount = 0; 
			$activeExtraAmountList = array(); 
			$activeExtraPVList = array(); 
			$activeExtraNameList = array(); 
			if (count($activeExtraList) > 0) {
				foreach ($activeExtraList as $activeExtraKey=>$activeExtra) {
					foreach ($activeExtra['productMix'] as $productMixKey=>$productMix) {
						$getPV = round($activeExtra['pv'] / $productMix['quantity']);

						foreach ($productMix['product'] as $product) {
							if ($product['id'] == $row['id'] && $product['format1'] == $row['format1'] && $product['format2'] == $row['format2']) {
								$activeExtraUseProductCount++;
								$activeExtraList[$activeExtraKey]['productMix'][$productMixKey]['tempQuantity']++;
								if ($activeExtraList[$activeExtraKey]['productMix'][$productMixKey]['tempQuantity'] == $productMix['quantity']) {
									$activeExtraPVList[] = $activeExtra['pv'] - ($productMix['quantity'] - 1) * $getPV;
									$activeExtraAmountList[] = $activeExtra['amount'] - $activeExtraList[$activeExtraKey]['productMix'][$productMixKey]['tempAmount'];
									$activeExtraNameList[] = $activeExtra['name'];
									
								} else {
									$tempAmount = round($activeExtra['amount'] * $product['amount'] / $productMix['amount']);
									$activeExtraPVList[] = $getPV;
									$activeExtraAmountList[] = $tempAmount;
									$activeExtraNameList[] = $activeExtra['name'];

									$activeExtraList[$activeExtraKey]['productMix'][$productMixKey]['tempAmount'] += $tempAmount;
								}

							}
						}
					}
				}
			}
			

			for($i = 1 ; $i <= intval($row['num']) ; $i++)
			{
				if(empty($index_cart_pro_list[$row['id']]))
				{
					$index = 1;
				}
				else
				{
					$index = $index_cart_pro_list[$row['id']] + 1;
				}
				$index_cart_pro_list[$row['id']] = $index;
				
				if ($i <= $activeExtraUseProductCount) {
					$prodtl['amt'][] = intval($activeExtraAmountList[$i - 1]);
					$prodtl['amt_pv'][] = intval($activeExtraPVList[$i - 1]);
					$prodtl['amt_bv'][] = intval($activeExtraPVList[$i - 1] * $pvbvratio);
					$prodtl['pair'][] = "N" ;
					$prodtl['use'][] = "Y";
					
					$prodtl_amt_sum += ($activeExtraAmountList[$i - 1]);
					$prodtl_amt_pv += ($activeExtraPVList[$i - 1]);
					$prodtl_amt_bv += ($activeExtraPVList[$i - 1] * $pvbvratio);
					
					if (strrpos($prodtl_act, $activeExtraNameList[$i - 1]) === false) {
						if (!empty($prodtl_act)) {
							$prodtl_act .= ",";
						}
						$prodtl_act .= $activeExtraNameList[$i - 1];
					}
				} else if(count($active_disPro_list[$row['id']."|".$index]) > 0)
				{
					$tmp_arr = $active_disPro_list[$row['id']."|".$index];
					
					if($tmp_arr['activePlanid'] == "1") 
					{
						$amt = round($row["siteAmt"] - $tmp_arr['var02']);
						$pv = $row["pv"] * (($row['siteAmt'] - $tmp_arr['var02'] ) / $row['siteAmt']);
					}
					else if($tmp_arr['activePlanid'] == "2") 
					{
						$amt = round($row["siteAmt"] * $tmp_arr['var02'] * 0.01);
						$pv = round($row["pv"] * $tmp_arr['var02'] * 0.01);
					}
					else if($tmp_arr['activePlanid'] == "3") 
					{
						$amt = $tmp_arr["amt"];
						$pv = $tmp_arr["amt_pv"];
					}
					else if($tmp_arr['activePlanid'] == "12") 
					{
						$amt = round($row["siteAmt"] * ( $tmp_arr["var02"] * 0.01 ));
						$pv = $tmp_arr["amt_pv"];
					}
					else if($tmp_arr['activePlanid'] == "13") 
					{
						$amt = round($row["siteAmt"]);
						$pv = round($row["pv"] * $tmp_arr['var02'] * 0.01);
					}
					
					$prodtl['amt'][] = $amt;
					$prodtl['amt_pv'][] = $pv;
					$prodtl['amt_bv'][] = $pv * $pvbvratio;
					
					$prodtl['pair'][] = ($active_actPro_list[$row['id']."|".$index] == "12") ? "Y" : "N" ;
					$prodtl['use'][] = ($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12") ? "Y" : "N" ;
					
					
					if( $active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12" && $tmp_arr['activePlanid'] == "12")
					{
						$prodtl_use_act = $tmp_arr['name'];
					}
					
					$prodtl_amt_sum += ($amt);
					$prodtl_amt_pv += ($pv);
					$prodtl_amt_bv += ($pv * $pvbvratio);
					
					
					if(!empty($prodtl_act))
					{
						$prodtl_act .= ",";
					}
					$prodtl_act .= $tmp_arr['name'];
					
				}
				else
				{
					$prodtl['amt'][] = $row["siteAmt"];
					$prodtl['amt_pv'][] = $row["pv"];
					$prodtl['amt_bv'][] = $row["bv"];
					
					$prodtl['pair'][] = ($active_actPro_list[$row['id']."|".$index] == "12") ? "Y" : "N" ;
					$prodtl['use'][] = ($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12") ? "Y" : "N" ;
					
					if($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12")
					{
						$prodtl_use_act = $active_usePro_list[$row['id']."|".$index]['name'];
					}
					
					$tmp_arr = $active_usePro_list[$row['id']."|".$index];
					if(!empty($prodtl_act))
					{
						$prodtl_act .= ",";
					}
					$prodtl_act .= $tmp_arr['name'];
					
					$prodtl_amt_sum += ($row["siteAmt"]);
					$prodtl_amt_pv = bcadd($prodtl_amt_pv,$row["pv"],2);
					$prodtl_amt_discount = bcmul($prodtl_amt_pv,$m_discount_rate,2);
					$prodtl_amt_bv += ($row["bv"]);
				}
			}
			
			$cart_list[$key]['prodtl'] = $prodtl;
			
			$cart_list[$key]['prodtl_amt'] = $prodtl_amt_sum;
			$cart_list[$key]['prodtl_pv'] = $prodtl_amt_pv;
			$cart_list[$key]['prodtl_discount'] = $prodtl_amt_discount;
			$cart_list[$key]['prodtl_bv'] = $prodtl_amt_bv;
			$cart_list[$key]['prodtl_act'] = $prodtl_act;
			$cart_list[$key]['prodtl_use_act'] = $prodtl_use_act;
			
			
		}
	}
	
	
	
	return $cart_list;
	
}

function orderChk()
{
	global $db,$conf_user;
	
	$uid=intval($_SESSION[$conf_user]['uid']);
	
	
	$chkDate = getFieldValue(" SELECT CodeValue FROM syscode WHERE CodeKind = 'orderChkDate' ","CodeValue");
	
	if( strtotime(date("Y-m-d 0:0:0")) >  strtotime($chkDate." 0:0:0"))
	{
		$day3Str = date("Y-m-d",strtotime("-3 days"));
		$day5Str = date("Y-m-d",strtotime("-5 days"));
		
		
		// $sql = " SELECT * FROM orders WHERE status='0' AND ( ( buyDate <= '$day3Str' AND orderMode = 'addMember') OR ( buyDate <= '$day5Str' AND orderMode <> 'addMember')) ";
		$sql = " SELECT * FROM orders WHERE status='0' AND (( buyDate <= '$day5Str' AND orderMode <> 'addMember')) ";
		$db->setQuery( $sql );
		$list=$db->loadRowList();
		
		if(count($list) > 0)
		{
			foreach($list as $row)
			{
				$id = $row['id'];
				
				$sql="update orders set status=6,mtime='".date("Y-m-d H:i:s")."' where id='$id'";
				
				$db->setQuery( $sql );
				$db->query();
				
				$now = date('Y-m-d H:i:s');
				$today = date('Y-m-d');
				
				$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values 
				('$id','$today','6','$now','$now','$uid');";
				$db->setQuery($sql);
				$db->query();
				
				
				order_instock("0","6",$id);
				
			}
		}
		
		
		
		$sql="update syscode set CodeValue='".date("Y-m-d")."' WHERE CodeKind = 'orderChkDate' ";
		$db->setQuery($sql);
		$db->query();
		
	}
	
	return true;
}

// function sendMailToMemberBySignupSuccess($uid)
// {
// 	global $db,$conf_php,$conf_upload,$conf_user,$HTTP_X_FORWARDED_PROTO;
	
// 	$imgUrl = $HTTP_X_FORWARDED_PROTO.'://'.$_SERVER['HTTP_HOST'];
		
// 	$sql = "select * from siteinfo where sysid ='' ";

// 	$db->setQuery( $sql );
// 	$siteinfo_arr = $db->loadRow();
	
// 	$from = $siteinfo_arr['email'];
// 	$fromname = $siteinfo_arr['name'];
// 	if($_SESSION[$conf_user]['syslang'])
// 	{
// 		$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
// 	}
	
// 	$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$uid' ","loginid");
// 	$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ","sid");
// 	$name = getFieldValue(" SELECT name FROM members WHERE id = '$uid' ","name");
// 	$email = getFieldValue(" SELECT email FROM members WHERE id = '$uid' ","email");
// 	$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$uid' ","ERPID");
	
	
// 	require_once ($conf_php.'includes/Barcode39.php');
	
// 	$bc = new Barcode39($ERPID); 
// 	$bc->barcode_height = 80; 
// 	$bc->barcode_text_size = 5; 
// 	$bc->barcode_bar_thick = 4.5; 
// 	$bc->barcode_bar_thin = 1.5; 
	
// 	$bc->draw("../".$conf_upload."Barcode39/barcode".$uid.".gif");
	
// 	$sendto = array(array("email"=>$email,"name"=>$name));
	
// 	$subject = $fromname." - "._EWAYS_ESIGNUO_MSG1." (".date("Y-m-d H:i:s").")";
// 	$body = "
// 	<html>
// 	<head>
// 		<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
 //		<title>$fromname "._EWAYS_ESIGNUO_MSG1."</title>

// 	</head>
// 	<body style=\"margin:0;padding:0;\">
// 		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
// 			<p style=\"line-height:180%;\">
// 				"._EWAYS_ESIGNUO_MSG2."<b style=\"color:#0d924a\">GoodARCH<b>
// 			</p>
			
// 			<table border=\"1\">
// 				<tr>
// 					<td>
// 						"._EWAYS_ESIGNUO_MSG3."<b style=\"color:#0d924a\">$loginId</b><br />
// 						"._EWAYS_ESIGNUO_MSG4."<b style=\"color:#0d924a\">$passwd</b><br />
// 						"._EWAYS_ESIGNUO_MSG5."<b style=\"color:#0d924a\">$ERPID</b>
// 					</td>
// 				</tr>
// 				<tr>
// 					<td align=\"center\">
// 						<img src=\"{$imgUrl}/upload/image005.jpg\" /> <br />
// 						<img src=\"{$imgUrl}/upload/Barcode39/barcode".$uid.".gif\" /> <br />
// 						"._EWAYS_ESIGNUO_MSG6."
// 					</td>
// 				</tr>
// 				<tr>
// 					<td>
// 						<b style=\"color:#ff0000\">"._EWAYS_ESIGNUO_MSG7."</b>"._EWAYS_ESIGNUO_MSG8."
// 					</td>
// 				</tr>
// 			</table>
			
// 		</div>
// 	</body>
// 	</html>
// 	";
	
// 	$rs = global_send_mail($from,$fromname,$sendto,$subject,$body);
	
// 	return true;
	
// }


function sendMailToMemberBySignupSuccess($uid,$getData = false,$type = null)
{
	global $db,$conf_php,$conf_upload,$conf_user,$HTTP_X_FORWARDED_PROTO,$globalConf_signup_ver2020;
	
	$imgUrl = $HTTP_X_FORWARDED_PROTO.'://'.$_SERVER['HTTP_HOST'];
	
	$sql = "select * from siteinfo where sysid ='' ";

	$db->setQuery( $sql );
	$siteinfo_arr = $db->loadRow();
	
	$from = $siteinfo_arr['email'];
	$fromname = $siteinfo_arr['name'];
	if($_SESSION[$conf_user]['syslang'])
	{
		$fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
	}

	
	
	$loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$uid' ","loginid");
	if(empty($loginId)){
		$loginId = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ","sid");
	}
	$passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ","sid");
	$name = getFieldValue(" SELECT name FROM members WHERE id = '$uid' ","name");
	$email = getFieldValue(" SELECT email FROM members WHERE id = '$uid' ","email");
	$ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$uid' ","ERPID");
	
	$tmpStr = _MEMBER_ACCPW."：<b style=\"color:#0d924a\">$passwd</b><br />";
	if($globalConf_signup_ver2020){
		
		$loginId = substr($loginId,0,2)."*****".substr($loginId,-3);
		$signupMode = getFieldValue(" SELECT pvgeLevel FROM members WHERE id = '$uid' ","pvgeLevel");
		
		if($signupMode == "SMS")
		{
			$mobile = getFieldValue(" SELECT mobile FROM members WHERE id = '$uid' ","mobile");
			$tmpStr = _MEMBER_MOBILE."：<b style=\"color:#0d924a\">$mobile</b><br />";
		}
		else if($signupMode == "MAIL")
		{
			$tmpStr = "E-Mail：<b style=\"color:#0d924a\">$email</b><br />";
		}
	}
	
	$title = _EMAIL_MEMBER_1;
	//JIE ADD
	$fromname = _EMAIL_msg24;
	$card_word = '';
	if($type == "sign20_signup")
	{
		$title = _EMAIL_MEMBER;
	}else{
		$card_word = _EMAIL_msg22;
	}
	
	require_once ($conf_php.'includes/Barcode39.php');
	
	$bc = new Barcode39($ERPID); 
	$bc->barcode_height = 80; 
	$bc->barcode_text_size = 5; 
	$bc->barcode_bar_thick = 4.5; 
	$bc->barcode_bar_thin = 1.5; 
	
	$bc->draw("../".$conf_upload."Barcode39/barcode".$uid.".gif");
	
	$sendto = array(array("email"=>$email,"name"=>$name));
	
	
	$bodyStr = "";
	$htmlStr = "";
	
	$subject = $fromname." - $title (".date("Y-m-d H:i:s").")";
	
	if($type == "sign20_signup"){
		$body = "
	<html>
	<head>
		<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
		<title>$fromname $title</title>

	</head>
	<body style=\"margin:0;padding:0;\">
		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">";

			$bodyStr = "
			<p style=\"line-height:180%;\">
				"._EWAYS_ESIGNUO_MSG2."<b style=\"color:#0d924a\">"._EMAIL_msg9."</b>
			</p>
			
			<table border=\"1\" width=\"75%\">
				<tr>
					<td>
						"._COMMON_PARAM_LOGINID."：<b style=\"color:#0d924a\">$loginId</b><br />
						{$tmpStr}
						"._EMAIL_msg23."：<b style=\"color:#0d924a\">$ERPID</b>
					</td>
				</tr>
				<tr style='padding-bottom:10px;'>
					<td align='center' style='padding: 10px 0px;'>
						"._EMAIL_msg10."<br /><span style='color:#870000'>"._EMAIL_msg11."</span><br />"._EMAIL_msg25."<br>
						<span style='color:#FFC42C'>360</span>"._EMAIL_msg14."<br>
						<br /><div style='padding-bottom:7px'>"._EMAIL_msg15."</div>
						
					</td>
				</tr>
				<tr>
					<td align=\"center\">
						<img style='width:80%;max-width:150px;padding:10px 0px' src=\"{$imgUrl}/upload/goodarch-logo-m.png\" /> <br />
						<img style='width:80vmin;max-width:500px' src=\"{$imgUrl}/upload/Barcode39/barcode".$uid.".gif\" /> <br />
						";
	}else{
		$body = "
	<html>
	<head>
		<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
		<title>$fromname $title</title>

	</head>
	<body style=\"margin:0;padding:0;\">
		<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">";

			$bodyStr = "
			<p style=\"line-height:180%;\">
				"._EWAYS_ESIGNUO_MSG2."<b style=\"color:#0d924a\">"._EMAIL_msg9."</b>
			</p>
			
			<table border=\"1\" width=\"75%\">
				<tr>
					<td>
						"._COMMON_PARAM_LOGINID."：<b style=\"color:#0d924a\">$loginId</b><br />
						{$tmpStr}
						"._EMAIL_msg23."：<b style=\"color:#0d924a\">$ERPID</b>
					</td>
				</tr>
				<tr style='padding-bottom:10px;'>
					<td align='center' style='padding: 10px 0px;'>
						"._EMAIL_msg10."<br /><span style='color:#870000'>"._EMAIL_msg11."</span><br>"._EMAIL_msg25."
						
						
						
					</td>
				</tr>
				<tr>
					<td align=\"center\">
						<img style='width:80%;max-width:150px;padding:10px 0px' src=\"{$imgUrl}/upload/goodarch-logo-m.png\" /> <br />
						<img style='width:80vmin;max-width:500px' src=\"{$imgUrl}/upload/Barcode39/barcode".$uid.".gif\" /> <br />
						";
	}
	
	
	if($_SESSION["tmpData"]["payD"] == 1){
		$body .= 	$bodyStr.$card_word."	
						</td>
					</tr>
					<tr>
						<td>
							<b style=\"color:#ff0000\">"._EMAIL_msg12."
						</td>
					</tr>
				</table>
				<div>
					<span>提醒您，您的入會程序尚未完成，請您儘速於訂單有效期間內完成付款，以免損害您的相關權益。</span>
				</div>
			</div>
		</body>
		</html>
		";
	}else{
		$body .= 	$bodyStr.$card_word."	
							
						</td>
					</tr>
					<tr>
						<td>
							<b style=\"color:#ff0000\">"._EMAIL_msg12."
						</td>
					</tr>
				</table>
			
			</div>
		</body>
		</html>
		";
	}

	
	
	
	if($_SESSION["tmpData"]["payD"] == 1){
		$htmlStr = $bodyStr.$card_word."	
			</td>
		</tr>
		
		
		</table>
		<div style='margin-top:20px'>
		<span>提醒您，您的入會程序尚未完成，請您儘速於訂單有效期間內完成付款，以免損害您的相關權益。</span>
		</div>";
	}else{
		$htmlStr = $bodyStr.$card_word."	
			</td>
			
		</tr>
		
		</table>";
	}
	
	
	$_SESSION["tmpData"]["bodyStr"] = $htmlStr;
	
	if(!$getData)
		$rs = global_send_mail($from,$fromname,$sendto,$subject,$body);
	
	return true;
	
}



function getLanguageList($txt=null,$all=null)
{
	global $db,$conf_php,$conf_upload;
	
	$where_str = "";
	switch ($txt) {
		case "text":
			$where_str .= " AND textPublish = 1";
			if(empty($all))
			{
				$where_str .= " AND textChk = '1'";
			}
			break;
		case "money":
			$where_str .= ' AND moneyPublish = 1';
			if(empty($all))
			{
				$where_str .= " AND moneyChk = '1'";
			}
			break;
		default:
			break;
	}
	$sql = "SELECT * FROM langConf WHERE alive = 1 $where_str ORDER BY odring";
	
	$db->setQuery( $sql );
	$langConf_arr = $db->loadRowList();
	
	return $langConf_arr;
}



function set_upsp(){
	global $conf_user,$db;
	ini_set('display_errors','1');
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

	$user_info = $_SESSION[$conf_user]['user_res_info'];
	$res['user'] = $user_info;
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

	$d=[
        'Authenticator' => $AuthenticatorToken,
        'Address' => [
            "FullName"	=> 	$user_info['name'],
			"Address1"	=>	$user_info['address'],
			"City" 		=> 	$user_info['city'],
            'State'     => 	$user_info['state']['state_s']
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
		return array('err'=>'yes','code'=>_('Bad customer adress'));  
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
	$shipDate = date('Y-m-d',strtotime('+3 Days'));
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
            'ServiceType'  => $user_info['dt'],
            "PackageType"   => "Package",
            "ShipDate"  => $shipDate,
            "InsuredValue"  => '0.0',
			"AddOns"	=>	[
				"AddOnType"	=> 'US-A-DC'
			]
        ]
    ];
	// JsonEnd($rateOptions);
	// $rates = $client -> getRates($rateOptions);

	// $auth = $client->AuthenticateUser($authData);
	// $AuthenticatorToken = $auth->Authenticator;
	// $IntegratorTxID=time();
	// $isSampleOnly = true;
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
	// $R = $rates -> Rates;
	// $Rs = $R -> Rate;
	// $Rs->FromZIPCode = '91107';
	// $Rs->ToZIPCode = '90066';
	// $Rs->AddOns = array(
	// 	array(
	// 		'AddOnType' => 'US-A-DC'
	// 	),
	// 	array(
	// 		'AddOnType' => 'SC-A-HP'
	// 	)
	// );
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
	// $labelOptions = [
    //     'Authenticator'     =>	$AuthenticatorToken,
    //     'IntegratorTxID'    =>	$IntegratorTxID,
	// 	'TrackingNumber'	=> '',
    //     'SampleOnly'        =>	$isSampleOnly,
    //     'ImageType'         => 'Pdf',
    //     'Rate'              => $Rs
    // ];


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
		$rates = $client->GetRates($rateOptions);
		// $cleanseFromAddressResponse = $client -> CleanseAddress($company);
		// $rates = $client -> CreateIndicium($labelOptions);
	} catch (Exception $e) { 
		// echo "EXCEPTION: " . $e->getMessage() . "\n";
		// print_r($client->__getLastRequest());
		// JsonEnd($e);
		$res['err'] = $e->getMessage();
		JsonEnd($res);
		exit;
	}
	$_SESSION[$conf_user]['dlvrAmt'] = number_format($rates->Rates->Rate->Amount,2);
	$res['rate'] = $rates;
	$res['ship'] = number_format($rates->Rates->Rate->Amount,2);
	return $res['ship'];
}


function tomlm_test($oid){
	global $db,$db2;
	$osql = "SELECT * from orders where id = '$oid'";
	$db->setQuery($osql);
	$o = $db->loadRow();
	$memberid = $o['memberid'];
	$msql = "SELECT * from members where id = '$memberid'";
	$db->setQuery($msql);
	$m = $db->loadRow();
	$o_arr = array();
	// $o_arr['ord_no'] = $o['orderNum'];
	// $o_arr['ord_date'] = $o[''];
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;
	// $o_arr[''] = ;

	$odsql = "SELECT * from orderdtl where oid = '$oid'";
	$db->setQuery($odsql);
	$od = $db->loadRowList();
}


?>