<?php

include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null , 0, 1);

switch ($task) {
	case "mainmenulist":
		mainmenulist();
		break;
	case "bottommenulist":
	    bottommenulist();
	    break;
	case "update_cart_list":
	    update_cart_list();
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

// function get_addrCode(){
// 	global $db,$conf_user;
	
// 	$sql="select * from addrcode order by belongid,code";
// 	$db->setQuery( $sql );
// 	$r=$db->loadRowList();
// 	$data=array();
// 	$province=array();
// 	$city=array();
// 	$canton=array();
// 	foreach($r as $row){
// 		if($row['addrlevel']=="city"){
// 			$city[$row['belongid']][$row['id']]=array("id"=>$row['id'],"name"=>$row['name']);
// 		}else if($row['addrlevel']=="canton"){
// 			$canton[$row['belongid']][$row['id']]=array("id"=>$row['id'],"name"=>$row['name'],"postcode"=>$row['postcode']);
// 		}
// 	}
	
	
// 	JsonEnd(array("status" => 1,"city"=>$city,"canton"=>$canton));
// }

function get_addrCode(){
	global $db,$conf_user;
	
	$sql="select * from region order by id asc";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$data=array();
	$province=array();
	$city=array();
	$canton=array();
	
	
	
	JsonEnd(array("status" => 1,"city"=>$r));
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
	
	$_SESSION[$conf_user]['take_type']=$take_type;
	$_SESSION[$conf_user]['dlvrAmt']=take_type(true);
	
	$cart=$_SESSION[$conf_user]['cart_list'];
	$proArr=CartProductInfo($cart);
	JsonEnd(array("status" => 1,"dlvrAmt"=>intval($_SESSION[$conf_user]['dlvrAmt'])-intval($proArr['disDlvrAmt']),"disDlvrAmt"=>intval($proArr['disDlvrAmt'])));
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



function get_cart_num(){
	global $conf_user;
	chkCartPro();
	$cnt=count($_SESSION[$conf_user]['cart_list']);
	if($cnt==0){
		$_SESSION[$conf_user]['cart_list']=array();
	}
	
	JsonEnd(array("status" => 1, "cnt" =>$cnt));
}

function update_cart_list(){
	global $conf_user,$db;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$num = intval(global_get_param( $_REQUEST, 'num', null ,0,1  ));
	
	if($id && $num){
		if(count($_SESSION[$conf_user]['cart_list'])>0){
			$_SESSION[$conf_user]['cart_list'][$id]=$num;
		}else{
			$_SESSION[$conf_user]['cart_list']=array();
			$_SESSION[$conf_user]['cart_list'][$id]=$num;
		}
	}else if($id && $num==0){
		$_SESSION[$conf_user]['cart_list'][$id]=null;
		unset($_SESSION[$conf_user]['cart_list'][$id]);
	}
	
	$today=date("Y-m-d");
	$sql="select * from productviewcnt where viewDate='$today'";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$plist=array();
	foreach($r as $row){
		$plist[]=$row['proid'];
	}
	
	if(count($_SESSION[$conf_user]['cart_list'])>0){
		foreach($_SESSION[$conf_user]['cart_list'] as $pid=>$num){
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
		unset($_SESSION[$conf_user]['cart_list']);
		unset($_SESSION[$conf_user]['disDlvrAmt']);
		unset($_SESSION[$conf_user]['pay_type']);
		unset($_SESSION[$conf_user]['take_type']);
		unset($_SESSION[$conf_user]['dlvrAmt']);
		unset($_SESSION[$conf_user]['usecoin']);
		unset($_SESSION[$conf_user]['proArr']);
		unset($_SESSION[$conf_user]['realDlvrAmt']);
		unset($_SESSION[$conf_user]['totalAmt']);
	}
	
	$proArr=CartProductInfo($_SESSION[$conf_user]['cart_list']);
	
	
	
	JsonEnd(
		array(
			"status" => 1, 
			"cnt"=>count($_SESSION[$conf_user]['cart_list']),
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

function mainmenulist($mode = 'all')
{
	global $db, $real_page, $template_option, $conf_user;
	$tablename = "mainmenus";
	
    $sql = "select * from $tablename where publish = 1 order by odring, id";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	if(isset($r)) {
	    $data=array();
		
		foreach($r as $key=>$row){
			$info = array();
		 	$info['id'] = $row['id'];
		 	$info['name'] = $row['name'];
		 	$info['linktype'] = $row['linktype'];
		 	$info['linkurl']=getDBPageLink($row['linktype'],$row['linkurl'],$row['tablename'],$row['databaseid']);
		 		
		 	
		 	$info['tablename'] = $row['tablename'];
		 	$info['databaseid'] = $row['databaseid'];
		 	$info['odring'] = intval($row['odring']);
			$data[$row['belongid']][] = $info;
		}
		
	    JsonEnd(array("status" => 1, "data" => $data));
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
		 	$info['belongid'] = $row['belongid'];
		 	$info['odring'] = intval($row['odring']);
		 	$info['linktype'] = $row['linktype'];
		 	$info['linkurl']=getDBPageLink($row['linktype'],$row['linkurl'],$row['tablename'],$row['databaseid']);
		 		
		 	
		 	$info['tablename'] = $row['tablename'];
		 	$info['databaseid'] = $row['databaseid'];
			$data[$row['belongid']][] = $info;
		}
		$data['root'][0]['icon'] = "fa fa-commenting";
		$data['root'][1]['icon'] = "fa fa-shopping-bag";
		$data['root'][2]['icon'] = "fa fa-question-circle";
		$data['root'][3]['icon'] = "fa fa-envelope";
	    JsonEnd(array("status" => 1, "data" => $data));
	} else { 
	    JsonEnd(array("status" => 2, "errorcode" => 1));
	}
}

include( $conf_php.'common_end.php' ); 
?>