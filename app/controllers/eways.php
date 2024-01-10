<?php

include( '../../config.php' ); 
include('../../lib/barcode-master/barcode.php');
$task = global_get_param( $_REQUEST, 'task', null , 0, 1);

switch ($task) {
	case "mainmenulist":
		mainmenulist();
		break;
	case "bottommenulist":
	    bottommenulist();
	    break;
	case "siteinfo":
	    siteinfo();
	    break;
	case "getlang":
	    getlang();
	    break;
	case "getCurrency":
	    getCurrency();
	    break;
	case "update_cart_list":
	    update_cart_list();
	    break;
	case "update_bonus_cart_list":
	    update_cart_list2("bonus");
	    break;
	case "edit_cart_list":
	    update_cart_list2(getCartMode());
	    break;
	case "get_cart_num":
	    get_cart_num();
	    break;
	case "pay_type":
	    pay_type();
	    break;
	case "take_type":
	    take_type();
	    break;
	case "set_pay_type":
	    set_pay_type();
	    break;
	case "set_logistics_type":
		set_logistics_type();
		break;
	case "set_take_type":
	    set_take_type();
	    break;
	case "get_usr_coupon":
	    get_usr_coupon();
	    break;
	case "cart_set_use_coin":
	    cart_set_use_coin();
	    break;
	case "add_to_like":
	    add_to_like();
	    break;
	case "get_addrCode":
	    get_addrCode();
	    break;
	case "get_invoice":
	    get_invoice();
	    break;
	case "getIndexProduct":
	    getIndexProduct();
	    break;
	case "banner":
		getBanner();
		break;    
	case "getAdvContent":
		getAdvContent();
		break;    
	case "getMediaContent":
		getMediaContent();
		break;    
	case "getNewsContent":
		getNewsContent();
		break;    
    case "sessionChk": 
		sessionChk();
		break;
	case "update_cart_list2":
	    update_cart_list2();
	    break;	
	case "update_amtpro_cart_list2":
	    update_cart_list2("amtpro");
	    break;
	case "update_freepro_cart_list2":
	    update_cart_list2("freepro");
	    break;
	case "update_event_list2":
	    update_cart_list2("event");
		break;	
	case "get_year":
		get_year();
		break;
	case "get_state":
		get_state();
		break;
	case "set_use_points":
		set_use_points();
		break;
	case "set_cb_use_points":
		set_cb_use_points();
		break;
	case "set_upsp":
		set_upsp();
		break;
	case "get_card":
		get_card();
		break;
}

function getNewsContent(){
	global $db,$conf_user;
	
	$showcnt = intval(global_get_param( $_GET, 'showcnt', null ,0,1  ));
	$showcnt=max($showcnt,3);
	
	$today=date("Y-m-d");
	$sql="select * from news where publish=1 and (newsDate<='$today' or newsDate='') and (pubDate>='$today' or pubDate='') order by newsDate desc ,id desc";
	$db->setQuery( $sql );
	$row = $db->loadRowList();
	
	$returnArray = array();
	
	for($i = 0; $i < min($showcnt,count($row)); $i++) {
		
		$row[$i]['id']=$row[$i]['id'];
		$row[$i]['name']=$row[$i]['name'];
		if($_SESSION[$conf_user]['syslang'] && $row[$i]['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$row[$i]['name']=$row[$i]['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$row[$i]['summary']=$row[$i]['summary'];
		if($_SESSION[$conf_user]['syslang'] && $row[$i]['summary_'.$_SESSION[$conf_user]['syslang']])
		{
			$row[$i]['summary']=$row[$i]['summary_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$row[$i]['newsDate_M']=date("m",strtotime($row[$i]['newsDate']));
		$row[$i]['newsDate_D']=date("d",strtotime($row[$i]['newsDate']));
		$returnArray[] = $row[$i];
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;

	JsonEnd($arrJson);
}

function getMediaContent(){
	global $db,$conf_user;
	
	$returnArray=array();
	$sql = "select * from indexconf where publish = 1 AND type='media' order by num1,num2";
	$db->setQuery($sql);
	$r=$db->loadRowList();
	foreach($r as $row){
		$returnArray['linkurl']=$row['linkurl'];
		$returnArray['name']=$row['name'];
		
		if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$returnArray['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$returnArray['date']=date("Y/m/d",strtotime($row['content']));
	}
	
	if(isset($r)) {
		JsonEnd(array("status" => 1, "data" => $returnArray));
	} else {
		
		JsonEnd(array("status" => 0, "msg" => _EWAYS_NO_VIDEO));
	}
	
}

function getAdvContent(){
	global $db,$conf_user;
	ini_set('display_errors','1');
	$str = "indexconf";
	if($_SESSION[$conf_user]['syslang'])
	{
		$str = "indexconf".intval(getFieldValue(" SELECT * FROM langConf WHERE code = '".$_SESSION[$conf_user]['syslang']."' ","id"));
	}
	
	$index = intval(global_get_param( $_GET, 'index', null ,0,1  ));
	$returnArray=array();
	$sql = "select * from indexconf where publish = 1 AND type='adv' AND num1='$index' order by num1,num2";
	$db->setQuery($sql);
	$r=$db->loadRowList();
	foreach($r as $row){
		
		$returnArray[$row['num2']]['img']=getimg($str,$row['num1'],$row['num2']);
		
		$returnArray[$row['num2']]['url']=$row['linkurl'];
	}
	
	if(isset($r)) {
		JsonEnd(array("status" => 1, "data" => $returnArray,"viewmode"=>count($returnArray)));
	} else {
		
		JsonEnd(array("status" => 0, "msg" => _EWAYS_NO_AD));
	}
}

function getBanner() {
	global $db,$conf_user;
	
	$index = intval(global_get_param( $_GET, 'index', null ,0,1  ));
	
	$sql = "select * from advrolls where publish = 1 AND seq='$index' order by odring";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	
	$imgIndex = 1;
	if($_SESSION[$conf_user]['syslang'])
	{
		$imgIndex = intval(getFieldValue(" SELECT * FROM langConf WHERE code = '".$_SESSION[$conf_user]['syslang']."' ","id"));
	}
	
	$returnArray=array();
	foreach($r as $key=>$row){
		
		$info = array();
		$info['id'] = $row['id'];
		$info['name'] = $row['name'];
		$info['odring'] = $row['odring'];
		$info['publish'] = $row['publish'];
		
		$info['linktype'] = $row['linktype'];
	 	$info['linkurl'] = getDBPageLink($row['linktype'], $row['linkurl'], "advrolls", $row['id']);
	 	
		$info['img'] = getimg("advrolls",$info['id']);
		$info['img'] = $info['img'][$imgIndex];
		$info['img'] = $info['img'] ? $info['img'] : '';
		
		$returnArray[] = $info;
	}
	
	
	if(isset($r)) {
		JsonEnd(array("status" => 1, "data" => $returnArray));
	} else {
		
		JsonEnd(array("status" => 0, "msg" => _EWAYS_NO_ADVROLLS_IMAGE));
	}
}


function getIndexProduct(){
	global $db,$conf_user;
	
	$type = global_get_param( $_GET, 'type', null ,0,1  );
	$showcnt = intval(global_get_param( $_GET, 'showcnt', null ,0,1  ));
	$showcnt=max($showcnt,1);
	
	
	$where_str = " AND A.bundleProChk <> '1' ";
	
	$sql_str = "";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str .= " A.`name_".$_SESSION[$conf_user]['syslang']."` , ";
	}
	
	$uid = intval($_SESSION[$conf_user]['uid']);
	if(!empty($uid)){
		$om = getFieldValue("SELECT onlyMember from members WHERE id = '$uid'","onlyMember");
		
		if($om == '1'){
			$where_str = " AND notomChk = '0'";
		}
	}else{
		$where_str = " AND notomChk = '0'";
	}
	
	if($type=="new_product"){
		$sql="select * from (  
			    select A.id,A.name,{$sql_str} A.highAmt,A.siteAmt,A.var03 as content,A.var04 as promedia,A.newDate,A.ctime,C.ptid,A.notomChk
				from products A,proinstock B ,protype C
				where A.publish=1 AND A.type='page' AND A.id=B.pid AND A.id = C.pid $where_str
				group by A.id,A.name,{$sql_str} A.highAmt,A.siteAmt,A.var03,A.var04
			  )as tbl
		      order by newDate desc,ctime desc, id desc";
		
	}else if($type=="special_product"){
		$sql="select * from (  
			    select A.id,A.name,{$sql_str} A.highAmt,A.siteAmt,A.var03 as content,A.var04 as promedia,A.ctime,C.ptid,A.notomChk
				from products A,proinstock B ,protype C
				where A.publish=1 AND A.type='page' AND A.id=B.pid AND A.hotChk=1 AND A.id = C.pid $where_str
				group by A.id,A.name,{$sql_str} A.highAmt,A.siteAmt,A.var03,A.var04
			  )as tbl
		      order by ctime desc, id desc";
		
	}
	
	$db->setQuery( $sql );
	$row = $db->loadRowList();
	
	$returnArray = array();
	
	for($i = 0; $i < min($showcnt,count($row)); $i++) {
		
		if($_SESSION[$conf_user]['syslang'] && $row[$i]['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$row[$i]['name']=$row[$i]['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$img=getimg('products',$row[$i]['id']);
		$row[$i]['img']=$img[1];
		$row[$i]['format']=getProductFormat($row[$i]['id']);
		$returnArray[] = $row[$i];
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;

	JsonEnd($arrJson);
	
}

function siteinfo(){
	global $conf_user;
	
	$n=array('addr','tel','fax','email','ytUrl','fbUrl','gpUrl','lineUrl');
	
	$langList = getLanguageList("text");	
	$syslang = ($_SESSION[$conf_user]['syslang']) ? $_SESSION[$conf_user]['syslang'] : '';
	
	JsonEnd(array("status" => 1,"data"=>getsiteinfo($n),"langList"=>$langList,"syslang"=>$syslang));
}

function getlang(){
	global $conf_user;
	
	if($_SESSION[$conf_user]['syslang'])
	{
		$syslang = $_SESSION[$conf_user]['syslang'];
	}
	else
	{
		$langList = getLanguageList("text");	
		$syslang = $langList[0]['code'];
	}
	
	JsonEnd(array("status" => 1,"syslang"=>$syslang));
}

function getCurrency(){
	global $conf_user;
	
	if($_SESSION[$conf_user]['sysCurrency'])
	{
		$sysCurrency = $_SESSION[$conf_user]['sysCurrency'];
	}
	else
	{
		$moneyList = getLanguageList("money");	
		$sysCurrency = $moneyList[0]['code'];
		$_SESSION[$conf_user]['sysCurrency'] = $sysCurrency;
	}
	
	JsonEnd(array("status" => 1,"sysCurrency"=>$sysCurrency));
}

function get_invoice(){
	global $db,$conf_user;
	
	$sql="select invoice,invoice2,invoice3 from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$invoice=array();
	
	if(!empty($r['invoice']))
	{
		$invoice[] = array("id"=>$r['invoice'],"name"=>$r['invoice']);
	}
	
	if(!empty($r['invoice2']))
	{
		$invoice[] = array("id"=>$r['invoice2'],"name"=>$r['invoice2']);
	}
	
	if(!empty($r['invoice3']))
	{
		$invoice[] = array("id"=>$r['invoice3'],"name"=>$r['invoice3']);
	}
	
	JsonEnd(array("status" => 1,"invoice"=>$invoice));
}

function get_addrCode(){
	global $db,$conf_user;
	
	$sql="select * from region order by id asc";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$city_list = array();
	foreach ($r as $each) {
		if($each['state_u'] == 'OTHERS' || $each['state_u'] == 'OTHER'){
			$each['state_u'] = $each['country'];
		}
		$city_list[] = $each;
	}
	$data=array();
	$province=array();
	$city=array();
	$canton=array();
	
	
	
	JsonEnd(array("status" => 1,"city"=>$city_list));
}

function get_year(){
	$data = array();
	$d = array();
	$now = date('Y');
	for ($i=0; $i < 10; $i++) { 
		array_push($d,$now);
		$now += 1;
	}
	$data['year_list'] = $d;
	$data['status'] = 1;
	JsonEnd($data);
	
}

function get_state(){
	global $db;
	$data = array();
	$sql = "select * from region order by id";
	$db->setQuery($sql);
	$list = $db->loadRowList();
	$data['state_list'] = $list;
	$data['status'] = 1;
	JsonEnd($data);
	
}

function add_to_like(){
	global $db,$conf_user;
	
	$uid=LoginChk();
	
	$pid = intval(global_get_param( $_POST, 'id', null ,0,1  ));
	
	$id=intval(getFieldValue("select id from likeProduct where memberid='$uid' AND proid='$pid'","id"));
	if($id>0){
		$sql="delete from likeProduct where id='$id'";
		$db->setQuery( $sql );
		$db->query();
	}else{
		$sql="insert into likeProduct (cdate,proid,memberid) values ('".date("Y-m-d")."','$pid','$uid')";
		$db->setQuery( $sql );
		$db->query();
	}
	JsonEnd(array("status" => 1));
}

function cart_set_use_coin(){
	global $db,$conf_user;
	
	$uid=LoginChk();
	
	$usecoin = intval(global_get_param( $_POST, 'usecoin', null ,0,1  ));
	
	$_SESSION[$conf_user]['usecoin']=$usecoin;
	JsonEnd(array("status" => 1));
}

function get_usr_coupon(){
	global $db,$conf_user;
	
	$uid=LoginChk();
	
	$coin=intval(getFieldValue("select coin from members where id='$uid'","coin"));
	
	if($_SESSION[$conf_user]['usecoin']){
		$usecoin=$_SESSION[$conf_user]['usecoin'];
	}
	
	JsonEnd(array("status" => 1,"data"=>$coin,"usecoin"=>$usecoin));
	
}

function set_take_type(){
	global $db,$conf_user;
	$take_type = intval(global_get_param( $_REQUEST, 'take_type', null ,0,1  ));
	if($take_type==0){
		
		JsonEnd(array("status" => 0, "msg" =>_EWAYS_SELECT_TAKETYPE));
	}
	$mode=getCartMode();
	$is_twcart = $_SESSION[$conf_user]['is_twcart_cart'];
	$origin_mode = $mode;
	if ($is_twcart == '1') {
		$getdlvr = 'outlying_dlvr';
		$mode = 'twcart';
	} else if ($is_twcart == '0') {
		$getdlvr = 'main_dlvr';
		$mode = $origin_mode;
	}
	$_SESSION[$conf_user]['take_type']=$take_type;
	// $_SESSION[$conf_user]['dlvrAmt']=take_type(true);
	
	$cart=$_SESSION[$conf_user]["{$mode}_list"];
	$proArr=CartProductInfo2($cart);
	JsonEnd(array("status" => 1,"dlvrAmt"=>intval($_SESSION[$conf_user]['dlvrAmt'])-intval($proArr['disDlvrAmt']),"disDlvrAmt"=>intval($proArr['disDlvrAmt'])));
}

function set_logistics_type()
{
	global $db, $conf_user;
	$logistics_type = intval(global_get_param($_REQUEST, 'logistics_type', null, 0, 1));
	$mo = intval(global_get_param($_REQUEST, 'mo', null, 0, 1));
	if ($logistics_type == 0) {
		JsonEnd(array("status" => 0, "msg" => "請選擇物流方式"));
	}
	$is_twcart = $_SESSION[$conf_user]['is_twcart_cart'];
	$mode = getCartMode();
	$origin_mode = $mode;
	// if ($mo == '1') { //本島
	// 	if ($is_twcart == '1') {
	// 		$getdlvr = 'f_main_dlvr';
	// 	} else if ($is_twcart == '0') {
	// 		$getdlvr = 'main_dlvr';
	// 	}
	// } else if ($mo == '2') {
	// 	if ($is_twcart == '1') {
	// 		$getdlvr = 'f_outlying_dlvr';
	// 	} else if ($is_twcart == '0') {
	// 		$getdlvr = 'outlying_dlvr';
	// 	}
	// }

	if ($is_twcart == '1') {
		$getdlvr = 'outlying_dlvr';
		$mode = 'twcart';
	} else if ($is_twcart == '0') {
		$getdlvr = 'main_dlvr';
		$mode = $origin_mode;
	}

	$logres = logisitics_type($logistics_type, null, $getdlvr);
	$_SESSION[$conf_user]['logistics_type'] = $logistics_type;
	$_SESSION[$conf_user]['mo'] = $mo;
	$_SESSION[$conf_user]['dlvrAmt'] = $logres['dlvr'];


	$cart = $_SESSION[$conf_user]["{$mode}_list"];
	$proArr = CartProductInfo2($cart);
	JsonEnd(array("status" => 1, "dlvrAmt" => intval($_SESSION[$conf_user]['dlvrAmt']) - intval($proArr['disDlvrAmt']), "disDlvrAmt" => intval($proArr['disDlvrAmt']), "dd" => $logres, "dlvrfeeStr" => $logres['dlvrfeeStr']));
}


function set_pay_type(){
	global $db,$conf_user;
	$pay_type = intval(global_get_param( $_REQUEST, 'pay_type', null ,0,1  ));
	if($pay_type==0){
		
		JsonEnd(array("status" => 0, "msg" =>_EWAYS_SELECT_PAYTYPE));
	}
	
	$_SESSION[$conf_user]['pay_type']=$pay_type;
	$_SESSION[$conf_user]['take_type']=1;
	JsonEnd(array("status" => 1));
}



function get_cart_num()
{
	global $conf_user;
	chkCartPro();
	$mode = getCartMode();
	$cnt = count($_SESSION[$conf_user]["cart_list"]);
	$backto = '0';
	// $cnt = count($_SESSION[$conf_user]["{$mode}_list"]);
	if ($cnt == 0) {
		$_SESSION[$conf_user]["cart_list"] = array();
		// $_SESSION[$conf_user]["{$mode}_list"] = array();
	}
	//舊的活動分組
	if ($_SESSION[$conf_user]['activeBundleCart'] && count($_SESSION[$conf_user]['activeBundleCart']) > 0) {
		$cnt += count($_SESSION[$conf_user]['activeBundleCart']);
	}
	//新加的session
	$cnt += getShopCartItemCount();

	if ($_SESSION[$conf_user]['twcart_list'] && count($_SESSION[$conf_user]['twcart_list']) > 0) {
		$cnt += count($_SESSION[$conf_user]['twcart_list']);
	}
	$res = array("status" => 1, "cnt" => $cnt);
	$u_data = get_user_info_m();
	if (!empty($u_data)) {
		$res['mb_no'] = $u_data['mb_no'];
	} else {
		$res['mb_no'] = '';
	}
	$res['cart'] = $_SESSION[$conf_user]["{$mode}_list"];
	$res['fro'] = $_SESSION[$conf_user]['twcart_list'];
	$is_twcart = $_SESSION[$conf_user]['is_twcart_cart'];
	if ($is_twcart == '1') {
		if (count($_SESSION[$conf_user]['twcart_list']) == '0') {
			$backto = '1';
		}
	} else if ($is_twcart == '0') {
		if (count($_SESSION[$conf_user]["cart_list"]) == '0') {
			$backto = '1';
		}
	}
	$res['backto'] = $backto;
	JsonEnd($res);
}


function update_cart_list($mode="cart"){
	global $conf_user,$db;
	ini_set('display_errors','1');
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$num = intval(global_get_param( $_REQUEST, 'num', null ,0,1  ));
	$format1 = intval(global_get_param( $_REQUEST, 'format1', null ,0,1  ));
	$format2 = intval(global_get_param( $_REQUEST, 'format2', null ,0,1  ));
	$editmode=false;
	if($mode=="edit"){
		$editmode=true;
		$mode=getCartMode();
	}else if($num==0){
		$editmode=true;
		$mode=getCartMode();
	}
	
	if($id && $num){
		if(count($_SESSION[$conf_user]["{$mode}_list"])>0){
			$format=$_SESSION[$conf_user]["{$mode}_list"][$id];
			if($editmode){
				foreach($format as $f1=>$fr){
					if(is_array($fr)){
						foreach($fr as $f2=>$fnum){
							$format[$f1][$f2]=$num;
						}
					}else{
						$format[$f1]=$num;
					}
				}
			}else{
				if($format1){
					if($format2){
						$format[$format1][$format2]=$num;
					}else{
						$format[$format1]=$num;
					}
				}else{
					$format=$num;
				}
			}
			
			$_SESSION[$conf_user]["{$mode}_list"][$id]=$format;
		}else{
			$_SESSION[$conf_user]["{$mode}_list"]=array();
			$format=$num;
			if($format1){
				$format=array();
				if($format2){
					$format[$format1][$format2]=$num;
				}else{
					$format[$format1]=$num;
				}
			}
			$_SESSION[$conf_user]["{$mode}_list"][$id]=$format;
		}
		
	}else if($id && $num==0){
		$format=null;
		if($format1){
			$format=array();
			if($format2){
				$format[$format1][$format2]=null;
				unset($_SESSION[$conf_user]["{$mode}_list"][$id][$format1][$format2]);
			}else{
				$format[$format1]=null;
				unset($_SESSION[$conf_user]["{$mode}_list"][$id][$format1]);
			}
		}
		$_SESSION[$conf_user]["{$mode}_list"][$id]=$format;
		unset($_SESSION[$conf_user]["{$mode}_list"][$id]);
	}else{
		unset($_SESSION[$conf_user]["cart_list_mode"]);
		
		JsonEnd(array("status" => 0, "msg" =>_EWAYS_SELECT_PRODUCT));
	}
	
	
	$view=array();
	
	foreach($_SESSION[$conf_user]["{$mode}_list"] as $pid=>$row){
		if(is_array($row)){
			foreach($row as $format1=>$row2){
				if(is_array($row2)){
					foreach($row2 as $format2=>$row3){
						$view[$pid]+=$row3;
					}
				}else{
					$view[$pid]+=$row2;
				}
			}
		}else{
				$view[$pid]+=$row;
		}
	}
	
	
	if(!$_SESSION[$conf_user]["cart_list_mode"]){
		$_SESSION[$conf_user]["cart_list_mode"]=$mode;
	}else if($_SESSION[$conf_user]["cart_list_mode"] && $_SESSION[$conf_user]["cart_list_mode"]!=$mode && count($view)>0){
		if($mode=="cart"){
			
			$msg=_EWAYS_CART_MSG1;
		}else if($mode=="bonus"){
			
			$msg=_EWAYS_CART_MSG2;
		}
		
		JsonEnd(array("status" => 0, "msg" =>$msg));
	}
	
	
	$today=date("Y-m-d");
	$sql="select * from productviewcnt where viewDate='$today'";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$plist=array();
	foreach($r as $row){
		$plist[]=$row['proid'];
	}
	
	if(count($view)>0){
		foreach($view as $pid=>$num){
			if(in_array($pid,$plist)){
				$sql="update productviewcnt set cnt=cnt+1 where proid='$pid' AND viewDate='$today'";
			}else{
				$sql="insert into productviewcnt (proid,viewDate,cnt) values ('$pid','$today','1')";
				$plist[]=$pid;
			}
			$db->setQuery( $sql );
			$db->query();
		}
		
	}else{
		unset($_SESSION[$conf_user]["{$mode}_list"]);
		unset($_SESSION[$conf_user]['disDlvrAmt']);
		unset($_SESSION[$conf_user]['pay_type']);
		unset($_SESSION[$conf_user]['take_type']);
		unset($_SESSION[$conf_user]['dlvrAmt']);
		unset($_SESSION[$conf_user]['usecoin']);
		unset($_SESSION[$conf_user]['proArr']);
		unset($_SESSION[$conf_user]['realDlvrAmt']);
		unset($_SESSION[$conf_user]['totalAmt']);
	}
	
	$proArr=CartProductInfo($_SESSION[$conf_user]["{$mode}_list"]);
	
	
	
	JsonEnd(
		array(
			"status" => 1, 
			"cnt"=>count($view),
			"data"=>$proArr['data'],
			"total"=>$proArr['total'],
			"amt"=>$proArr['amt'],
			"active_list"=>$proArr['active_list'],
			"discount"=>$proArr['discount'],
			"free_coin"=>$proArr['free_coin'],
			"dlvrAmt"=>intval($_SESSION[$conf_user]['dlvrAmt'])-intval($proArr['disDlvrAmt'])
		)
	);
}


function update_cart_list2($mode = "cart")
{
	global $conf_user, $db;

	$origin_mode = $mode;

	if ($mode == "bonus") {
	}



	$id = intval(global_get_param($_REQUEST, 'id', null, 0, 1));
	$num = intval(global_get_param($_REQUEST, 'num', null, 0, 1));
	$format1 = intval(global_get_param($_REQUEST, 'format1', null, 0, 1));
	$format2 = intval(global_get_param($_REQUEST, 'format2', null, 0, 1));

	$addid = intval(global_get_param($_REQUEST, 'addid', null, 0, 1));
	$addpro_format = $_SESSION[$conf_user]['cart_addpro_list'][$addid];

	

	//檢查是否有開放購買 20210922
	$now = date('Y-m-d H:i:s');
	
	// if($mode != 'freepro'){ //免費品不檢查
	// 	$csql = "SELECT * FROM products where id = '$id' and publish = '1' and (has_end='0' or (has_end ='1' and end_time >= '$now')) and newDate <= '$now'";
	// 	$db->setQuery($csql);
	// 	$pd = $db->loadRow();
	// 	if (empty($pd)) {
	// 		JsonEnd(array("status" => 0, "msg" => "此項目目前無法購買"));
	// 	}
	// }

	// JsonEnd(array("status"=>'1','csal'=>$csql));
	


	//檢查身份能不能買
	$uid = LoginChk();
	$om = getFieldValue("select onlyMember from members where id='$uid'", "onlyMember");
	if ($om == '1') { //檢查是否可以買該商品
		$notomChk = getFieldValue("select notomChk from products where id='$id'", "notomChk");
		if ($notomChk == '1') {
			JsonEnd(array("status" => 0, "msg" => "會員無法購買"));
		}
	}

	$editmode = false;
	if ($mode == "edit") {
		$editmode = true;
		$mode = getCartMode();
	} else if ($num == 0) {
		$editmode = true;
		$mode = getCartMode();
	}

	// $cm = $_SESSION[$conf_user]['is_twcart_cart'];
	// if($cm == '1'){
	// 	$mode = 'twcart';
	// }

	$is_twcart = getFieldValue("select forTW from products where id='$id'", "forTW");
	if ($is_twcart == '1' && $origin_mode != 'freepro') {
		$mode = 'twcart';
	} else {
		$mode = $origin_mode;
	}


	$protype = global_get_param($_REQUEST, 'protype', null, 0, 1);
	if (!empty($protype)) {
		$_SESSION[$conf_user]["cart_list_mode"] = $protype;
		$mode = $protype;
	}

	$fid = global_get_param($_REQUEST, 'fid', null, 0, 0);

	$where_str = "";
	if ($format1) {
		$where_str .= " AND format1='$format1'";
		if ($format2) {
			$where_str .= " AND format2='$format2'";
		}
	}

	if ($addid) {
		$formatid = getFieldValue("select id from proinstock where pid='$addid' $where_str", "id");
		$pid = $addid;
		if ($formatid) {
			$pid .= "|||" . $formatid;
		}
		if ($addid && $num) {
			$_SESSION[$conf_user]["cart_addpro_list"][$pid] = $num;
			// $num = $_SESSION[$conf_user]["cart_addpro_list"][$pid] + $num;
		} else if ($addid && $num == 0) {
			$_SESSION[$conf_user]["cart_addpro_list"][$pid] = null;
			unset($_SESSION[$conf_user]["cart_addpro_list"][$pid]);
		}
	} else {
		$formatid = getFieldValue("select id from proinstock where pid='$id' $where_str", "id");

		$pid = $id;
		if ($formatid) {
			$pid .= "|||" . $formatid;
		}

		if ($mode == 'freepro' && !empty($fid)) {
			$freelist = $_SESSION[$conf_user]['freepro_list'];
			$cartlist = $_SESSION[$conf_user]['cart_list'];
			$fid_arr = explode("|||", $fid);
			$pid .= "|||" . $fid_arr[2] . "|||" . $fid_arr[3];
			$num = 1;
			$cnt_sel = 0;

			foreach ($freelist as $f => $v) {
				$f_arr = explode("|||", $f);
				if ($formatid == $f_arr[1]) {
					$cnt_sel++;
				}
			}

			foreach ($cartlist as $c => $v) {
				$c_arr = explode("|||", $c);
				if ($formatid == $c_arr[1]) {
					$cnt_sel++;
				}
			}
			//檢查庫存
			$instock = intval(getFieldValue("select instock from proinstock where id='$formatid'", "instock")); //限有的量
			if ($cnt_sel >= $instock) {
				$resu = array();
				$resu['status'] = '0';
				// $resu['fid'] = $fid_arr;
				// $resu['pid'] = $pid;
				// $resu['formatid'] = $formatid;
				// $resu['freelist'] = $freelist;
				// $resu['good'] = true;
				// $resu['cnt_sel'] = $cnt_sel;
				// $resu['insto'] = $instock;
				// $resu['cartlist'] = $cartlist;
				$resu['msg'] = '庫存不足，請重新選擇。';

				JsonEnd($resu);
			}
		}

		if ($mode == 'amtpro' && $id && $num) {
			$num = intval($_SESSION[$conf_user]["amtpro_list"][$pid]) + $num;
		}

		if ($mode == 'e3pro' && $id && $num) {
			// $num = intval($_SESSION[$conf_user]["e3pro_list"][$pid]) + $num;
			$num = $num; //修改數量時不加上去
		}

		if ($id && $num) {
			if ($mode == 'freepro' && count($_SESSION[$conf_user]["freepro_list"]) > 0) {
				$fid_arr = explode("|||", $fid);
				foreach ($_SESSION[$conf_user]["freepro_list"] as $key => $row) {
					$key_arr = explode("|||", $key);
					if ($key_arr[0] == $fid_arr[0] && $key_arr[2] == $fid_arr[2] && $key_arr[3] == $fid_arr[3]) {
						$_SESSION[$conf_user]["freepro_list"][$key] = null;
						unset($_SESSION[$conf_user]["freepro_list"][$key]);
					}
				}
			}

			if ($mode == "cart") {
				$ori_num = intval($_SESSION[$conf_user]["{$mode}_list"][$pid]);
				if ($ori_num > $num) {

					$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
					$pairProArr_tmp = array();
					if (count($pairProArr) > 0) {
						foreach ($pairProArr as $pair) {
							$pairArr = explode("@@", $pair);

							if ($pairArr[0] != $id && $pairArr[1] != $id) {
								$pairProArr_tmp[] = $pair;
							}
						}
						$_SESSION[$conf_user]["pairpro_list"] = $pairProArr_tmp;
					}
				}
			}

			if ($mode == "twcart") {
				$ori_num = intval($_SESSION[$conf_user]["{$mode}_list"][$pid]);
				if ($ori_num > $num) {

					$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
					$pairProArr_tmp = array();
					if (count($pairProArr) > 0) {
						foreach ($pairProArr as $pair) {
							$pairArr = explode("@@", $pair);

							if ($pairArr[0] != $id && $pairArr[1] != $id) {
								$pairProArr_tmp[] = $pair;
							}
						}
						$_SESSION[$conf_user]["pairpro_list"] = $pairProArr_tmp;
					}
				}
			}


			if ($mode == "event") {

				$_SESSION[$conf_user]["cart_list"][$pid . "|||event|||" . $activeextraId] = $num;
			} else {
				if(isset($_SESSION[$conf_user]['edit_cart']) && $_SESSION[$conf_user]['edit_cart'] == '1'){
					$_SESSION[$conf_user]["{$mode}_list"][$pid] = $num;
				}else{
					// $num = $_SESSION[$conf_user]["{$mode}_list"][$pid] + $num;
					$_SESSION[$conf_user]["{$mode}_list"][$pid] = $num;
					
				}
				unset($_SESSION[$conf_user]['edit_cart']);
				
			}
		} else if ($id && $num == 0) {

			if ($mode == "cart") {

				$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
				$pairProArr_tmp = array();
				if (count($pairProArr) > 0) {
					foreach ($pairProArr as $pair) {
						$pairArr = explode("@@", $pair);

						if ($pairArr[0] != $id && $pairArr[1] != $id) {
							$pairProArr_tmp[] = $pair;
						}
					}
					$_SESSION[$conf_user]["pairpro_list"] = $pairProArr_tmp;
				}


				foreach ($_SESSION[$conf_user]["cart_list"] as $fff => $rrr) {
					$fff_arr = explode("|||", $fff);
					if (count($fff_arr) == 2 && $fff_arr[0] == $id) {
						$proChk = getFieldValue("select count(1) AS cnt from proinstock where pid='" . $fff_arr[0] . "' and id ='" . $fff_arr[1] . "' ", "cnt");

						if ($proChk == '0') {
							unset($_SESSION[$conf_user]["cart_list"][$fff]);
						}
					}
				}
			}

			if ($mode == "twcart") {

				$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
				$pairProArr_tmp = array();
				if (count($pairProArr) > 0) {
					foreach ($pairProArr as $pair) {
						$pairArr = explode("@@", $pair);

						if ($pairArr[0] != $id && $pairArr[1] != $id) {
							$pairProArr_tmp[] = $pair;
						}
					}
					$_SESSION[$conf_user]["pairpro_list"] = $pairProArr_tmp;
				}


				foreach ($_SESSION[$conf_user]["twcart_list"] as $fff => $rrr) {
					$fff_arr = explode("|||", $fff);
					if (count($fff_arr) == 2 && $fff_arr[0] == $id) {
						$proChk = getFieldValue("select count(1) AS cnt from proinstock where pid='" . $fff_arr[0] . "' and id ='" . $fff_arr[1] . "' ", "cnt");

						if ($proChk == '0') {
							unset($_SESSION[$conf_user]["twcart_list"][$fff]);
						}
					}
				}
			}

			if ($mode == "event") {

				$_SESSION[$conf_user]["cart_list"][$pid . "|||event|||" . $activeextraId] = null;
				unset($_SESSION[$conf_user]["cart_list"][$pid . "|||event|||" . $activeextraId]);
			} else {
				$_SESSION[$conf_user]["{$mode}_list"][$pid] = null;
				unset($_SESSION[$conf_user]["{$mode}_list"][$pid]);
			}
		} else {

			if ($mode == "cart") {
				unset($_SESSION[$conf_user]['pairpro_list']);
			}

			if ($mode == "twcart") {
				unset($_SESSION[$conf_user]['pairpro_list']);
			}

			unset($_SESSION[$conf_user]["cart_list_mode"]);
			JsonEnd(array("status" => 0, "msg" => "請選擇商品"));
		}
	}


	if ($mode == "event") {
		$mode = "cart";
		$_SESSION[$conf_user]["cart_list_mode"] = $mode;
	}


	$view = array();
	$ProFormatList = getProFormatList();
	foreach ($_SESSION[$conf_user]["{$mode}_list"] as $formatid => $num) {
		$formatid = explode("|||", $formatid);
		if ($formatid[0]) {
			$view[$formatid[0]] += $num;
		}
	}
	$ss = $mode;

	if ($mode == 'e3pro') {
		foreach ($_SESSION[$conf_user]["cart_list"] as $formatid => $num) {
			$formatid = explode("|||", $formatid);
			if ($formatid[0]) {
				$view[$formatid[0]] += $num;
			}
		}
	}


	if (!$_SESSION[$conf_user]["cart_list_mode"]) {
		$_SESSION[$conf_user]["cart_list_mode"] = $mode;
	} else if (($mode == "cart" || $mode == 'bonus')  &&  $_SESSION[$conf_user]["cart_list_mode"] && $_SESSION[$conf_user]["cart_list_mode"] != $mode && count($view) > 0 && $_SESSION[$conf_user]["cart_list_mode"] != 'twcart') {
		if ($mode == "cart") {
			$msg = "購物車已有紅利商品，請先清空購物車";
		} else if ($mode == "bonus") {
			$msg = "購物車已有一般商品，請先清空購物車";
		}

		JsonEnd(array("status" => 0, "msg" => $msg));
	}


	$today = date("Y-m-d");
	$sql = "select * from productviewcnt where viewDate='$today'";
	$db->setQuery($sql);
	$r = $db->loadRowList();
	$plist = array();
	foreach ($r as $row) {
		$plist[] = $row['proid'];
	}
	$activeBundleCart = $_SESSION[$conf_user]['activeBundleCart'];
	if (count($view) > 0 || count($activeBundleCart) > 0) {
		if (count($view) > 0) {
			foreach ($view as $pid => $num) {
				if (in_array($pid, $plist)) {
					$sql = "update productviewcnt set cnt=cnt+1 where proid='$pid' AND viewDate='$today'";
				} else {
					$sql = "insert into productviewcnt (proid,viewDate,cnt) values ('$pid','$today','1')";
					$plist[] = $pid;
				}
				$db->setQuery($sql);
				$db->query();
			}
		}
	} else {
		unset($_SESSION[$conf_user]["{$mode}_list"]);
		unset($_SESSION[$conf_user]['disDlvrAmt']);
		unset($_SESSION[$conf_user]['pay_type']);
		unset($_SESSION[$conf_user]['take_type']);
		unset($_SESSION[$conf_user]['use_rp']);
		unset($_SESSION[$conf_user]['rp_discount']);
		unset($_SESSION[$conf_user]['dlvrAmt']);
		unset($_SESSION[$conf_user]['usecoin']);
		unset($_SESSION[$conf_user]['proArr']);
		unset($_SESSION[$conf_user]['realDlvrAmt']);
		unset($_SESSION[$conf_user]['totalAmt']);
		unset($_SESSION[$conf_user]['totalpv']);
	}
	// JsonEnd(array('status' => '0', 'msg' => $_SESSION[$conf_user]["{$mode}_list"] , "cc" => $mode,'tw' => $is_twcart));
	$proArr = CartProductInfo2($_SESSION[$conf_user]["{$mode}_list"]);

	$cart_addpro_list = array();

	$addPro = $_SESSION[$conf_user]['cart_addpro_list'];

	$ap = array();
	if (count($addPro) > 0) {
		foreach ($addPro as $k => $row) {
			$k = explode("|||");
			if ($k[0] && $row) {
				$ap[$k[0]] = $row;
			}
		}
	}
	$addPro = $ap;
	if (count($addPro) > 0) {
		$proArr = AddCartProductInfo($addPro, $proArr);
		$proData = $proArr['data'];
		$cnt += count($addPro);
	} else {
		$proData = $proArr;
	}

	JsonEnd(
		array(
			"status" => 1,
			"cnt" => count($view),
			"data" => $proArr['data'],
			"total" => $proArr['total'],
			"amt" => $proArr['amt'],
			"active_list" => $proArr['active_list'],
			"discount" => $proArr['discount'],
			"free_coin" => $proArr['free_coin'],
			"dlvrAmt" => intval($_SESSION[$conf_user]['dlvrAmt']) - intval($proArr['disDlvrAmt']),
			"all_num" => intval($proArr['all_num']),
			"ssuu" => $ss,
			"mode" => $num
		)
	);
}

function mainmenulist($mode = 'all')
{
	global $db, $real_page, $template_option, $conf_user;
	$tablename = "mainmenus";
	
    $sql = "select * from $tablename where publish = 1 order by odring, id";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$menuimg=array();
	if(isset($r)) {
	    $data=array();
		
		foreach($r as $key=>$row){
			$info = array();
			$img=array();
		 	$info['id'] = $row['id'];
		 	$info['name'] = $row['name']?$row['name']:'';
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
				$row['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			if($_SESSION[$conf_user]['syslang'] && $row['imgname_'.$_SESSION[$conf_user]['syslang']])
			{
				$info['imgname']=$row['imgname_'.$_SESSION[$conf_user]['syslang']];
				$row['imgname']=$row['imgname_'.$_SESSION[$conf_user]['syslang']];
			}
			
		 	$info['linktype'] = $row['linktype'];
		 	$info['linkurl']=getDBPageLink($row['linktype'],$row['linkurl'],$tablename,$row['id']);
		 		
			$img['img']=getimg("mainmenus",$row['id'],1);
			$img['img']=$img['img']?$img['img']:'templates/default/images/title_banner.png';
			$img['imgname']=$row['imgname']?$row['imgname']:'';
			$img['name']=$row['name']?$row['name']:'';


		 	$info['tablename'] = $row['tablename'];
		 	$info['databaseid'] = $row['databaseid'];
		 	$info['odring'] = intval($row['odring']);
			$data[$row['belongid']][] = $info;
			
			$menuimg[$info['id']]=$img;
		}
		
	    JsonEnd(array("status" => 1, "data" => $data,"menuimg"=>$menuimg));
	} else { 
	    JsonEnd(array("status" => 2, "errorcode" => 1));
	}
	
}

function bottommenulist($mode = 'all')
{
    global $db, $real_page, $template_option, $conf_user;
	$tablename = "bottommenus";
	
    $sql = "select * from $tablename where publish = 1 order by odring, id";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	if(isset($r)) {
	    $data=array();
		
		foreach($r as $key=>$row){
			$info = array();
		 	$info['id'] = $row['id'];
		 	$info['name'] = $row['name'];
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
		 	$info['belongid'] = $row['belongid'];
		 	$info['odring'] = intval($row['odring']);
		 	$info['linktype'] = $row['linktype'];
		 	$info['linkurl']=getDBPageLink($row['linktype'],$row['linkurl'],$tablename,$row['id']);
		 		
		 	
		 	$info['tablename'] = $row['tablename'];
		 	$info['databaseid'] = $row['databaseid'];
			$data[] = $info;
		}
		
	    JsonEnd(array("status" => 1, "data" => $data));
	} else { 
	    JsonEnd(array("status" => 2, "errorcode" => 1));
	}
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
		JsonEnd(array("status"=>0));
	}
	
	$uloginid = global_get_param( $_REQUEST, 'uloginid', null ,0,1  );	
	$ulevel = global_get_param( $_REQUEST, 'ulevel', null ,0,1  );	
	if($ulevel!=$root_admin && $ulevel!=$root_store){
		JsonEnd(array("status"=>0));
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
		JsonEnd(array("status"=>0));
	}
	$_SESSION[$conf_user]['loginTime'] = time();
	JsonEnd(array("ulevel"=>$_SESSION[$conf_user]['ulevel'],"loginTime"=>$_SESSION[$conf_user]['loginTime']));
}

function set_use_points(){
	global $db3, $conf_user;
	$res = array();
	$use_p = intval(global_get_param($_REQUEST, 'use_p', null, 0, 1));
	$use_points = floatval(global_get_param($_REQUEST, 'use_points', null, 0, 1));
	$use_points = bcdiv(bcmul($use_points,100,2),100,2);
	$_SESSION[$conf_user]['use_p'] = $use_p;
	//check_points
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind";
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
	if($now_points >= $use_points){
		$res['status'] = 1;
		$_SESSION[$conf_user]['use_points'] = $use_points;
	}else{
		$res['status'] = 0;
		$_SESSION[$conf_user]['use_points'] = 0;
	}

	if(!is_numeric($use_points)){
		$res['status'] = 0;
		$_SESSION[$conf_user]['use_points'] = 0;
	}

	JsonEnd($res);
}

function set_cb_use_points()
{
	global $db3, $conf_user;
	$res = array();
	$use_p = intval(global_get_param($_REQUEST, 'cb_use_p', null, 0, 1));
	$use_points = floatval(global_get_param($_REQUEST, 'cb_use_points', null, 0, 1));
	$use_points = bcdiv(bcmul($use_points, 100, 2), 100, 2);
	$_SESSION[$conf_user]['cb_use_p'] = $use_p;
	//check_points
	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now_date = date('Y-m-d');
	$cb_gpoints = 0;
	$csql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '0' and expiry_date > '$now_date' and is_invalid = '0'";
	$db3->setQuery($csql);
	$cgetlist = $db3->loadRow();
	if(!empty($cgetlist)){
		$cb_gpoints = $cgetlist['cb_points']; //目前可用的得到點數
	}
	$usql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '1' and expiry_date > '$now_date' and is_invalid = '0'";
	$db3->setQuery($usql);
	$cuselist = $db3->loadRow();
	if(!empty($cuselist)){
		$cb_upoints = $cuselist['cb_points']; //目前已使用的得到點數
	}
	
	$now_points = bcsub($cb_gpoints,$cb_upoints,2);
	if ($now_points >= $use_points) {
		$res['status'] = 1;
		$_SESSION[$conf_user]['cb_use_points'] = $use_points;
	} else {
		$res['status'] = 0;
		$_SESSION[$conf_user]['cb_use_points'] = 0;
	}
	if (!is_numeric($use_points)) {
		$res['status'] = 0;
		$_SESSION[$conf_user]['cb_use_points'] = 0;
	}
	$res['cb_gpoints'] = $cb_gpoints;
	$res['cb_upoints'] = $cb_upoints;
	$res['csql'] = $csql;
	$res['usql'] = $usql;
	JsonEnd($res);
}

function get_card()
{
	global $db;
	ini_set('display_errors','1');
	$res = array();
	$uid = LoginChk();
	$sql = "select ERPID,name,onlyMember from members where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	if (!empty($r['ERPID'])) {
		$barcode = new barcode_generator();
		$format = 'png';
		$symbology = 'Code 128';
		$data = $r['ERPID'];
		$options = array();
		// $options['h'] = '60';
		// $options['p'] = '0';
		$res['status'] = 1;
		$svg = $barcode->render_svg($symbology, $data, $options);

		// imagedestroy($barcode);
		$res['barcode_64'] = $svg;
		$res['mb_name'] = $r['name'];



		$urlToEncode = 'http://' . $_SERVER['HTTP_HOST'] . "/member_page/signup?rec=" . $data . "%26openExternalBrowser=1%26l=1";
		$qr1 = generateQRwithGoogle($urlToEncode);
		$res['qr1'] = $qr1;
		$urlToEncode2 = 'http://' . $_SERVER['HTTP_HOST'] . "/member_page/signup?rec=" . $data . "%26openExternalBrowser=1%26mem=1%26l=1";
		$qr2 = generateQRwithGoogle($urlToEncode2);
		$res['qr2'] = $qr2;
	} else {
		$res['status'] = 0;
	}
	$res['om'] = $r['onlyMember'];
	JsonEnd($res);
}

function generateQRwithGoogle($url, $widthHeight = '150', $EC_level = 'L', $margin = '0')
{
	$url = urlencode($url);
	$html = '<img src="http://chart.apis.google.com/chart?chs=' . $widthHeight .
		'x' . $widthHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin .
		'&chl=' . $url . '" alt="QR code" widthHeight="' . $widthHeight .
		'" widthHeight="' . $widthHeight . '"/>';
	return $html;
}


include( $conf_php.'common_end.php' ); 
?>