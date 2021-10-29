<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename = "payconf";
userPermissionChk("payconfig");
switch ($task) {
	case "detail":
	    pageinfo();
	    break;
	case "update":	
		pagedb();
		break;
}
function pageinfo(){
    global $db;
    $sql = "select * from payconf";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	foreach($r as $key=>$row){
	    if(!is_numeric($key) && $row){
	        $data[$key]=$row;
	    }
	}
	
	$moneyList = getLanguageList("money","all");
	
	JsonEnd(array("status"=>1,"data"=>$data,"moneyList"=>$moneyList));
	
}
function pagedb(){
    global $db;
    
    $data = array();
    $data['dlvrPay'] = intval(global_get_param( $_POST, 'dlvrPay', null ,0,1  ));
    $data['bankPay'] = intval(global_get_param( $_POST, 'bankPay', null ,0,1  ));
    $data['creditallPay'] = intval(global_get_param( $_POST, 'creditallPay', null ,0,1  ));
    $data['vanallPay'] = intval(global_get_param( $_POST, 'vanallPay', null ,0,1  ));
    
    
    $data['allpayMerchantID'] = global_get_param( $_POST, 'allpayMerchantID', null ,0,1  );
    $data['allpayHashKey'] = global_get_param( $_POST, 'allpayHashKey', null ,0,1  );
    $data['allpayHashIV'] = global_get_param( $_POST, 'allpayHashIV', null ,0,1  );
    
    
    $data['merID'] = global_get_param( $_POST, 'merID', null ,0,1  );
    $data['MerchantID'] = global_get_param( $_POST, 'MerchantID', null ,0,1  );
    $data['TerminalID'] = global_get_param( $_POST, 'TerminalID', null ,0,1  );
    $data['Key'] = global_get_param( $_POST, 'Key', null ,0,1  );
	
	
    $data['tspgPayCredit'] = intval(global_get_param( $_POST, 'tspgPayCredit', null ,0,1  )); 
	$data['tspgPayMid'] = global_get_param( $_POST, 'tspgPayMid', null ,0,1  );
	$data['tspgPayTid'] = global_get_param( $_POST, 'tspgPayTid', null ,0,1  );
	
	
	$data['ccbPayVATM'] = intval(global_get_param( $_POST, 'ccbPayVATM', null ,0,1  )); 
	$data['ccbPayCode'] = global_get_param( $_POST, 'ccbPayCode', null ,0,1  );
	$data['ccbPayBankName'] = global_get_param( $_POST, 'ccbPayBankName', null ,0,1  );
	$data['ccbPayBankBranch'] = global_get_param( $_POST, 'ccbPayBankBranch', null ,0,1  );
	$data['ccbPayBankId'] = global_get_param( $_POST, 'ccbPayBankId', null ,0,1  );
	
    
    $data['selfDlvr'] = intval(global_get_param( $_POST, 'selfDlvr', null ,0,1  ));
    $data['homeDlvr'] = intval(global_get_param( $_POST, 'homeDlvr', null ,0,1  ));
    $data['homeDlvrAmt'] = intval(global_get_param( $_POST, 'homeDlvrAmt', null ,0,1  ));
    $data['dlvrAmt'] = intval(global_get_param( $_POST, 'dlvrAmt', null ,0,1  ));
	$data['tcatDlvr'] = intval(global_get_param( $_POST, 'tcatDlvr', null ,0,1  ));
    $data['tcatDlvrAmt'] = intval(global_get_param( $_POST, 'tcatDlvrAmt', null ,0,1  ));
    $data['bankName'] = global_get_param( $_POST, 'bankName', null ,0,1  );
    $data['bankBranch'] = global_get_param( $_POST, 'bankBranch', null ,0,1  );
    $data['bankId'] = global_get_param( $_POST, 'bankId', null ,0,1  );
    $data['bankNum'] = global_get_param( $_POST, 'bankNum', null ,0,1  );
    $field="";
    foreach($data as $key=>$item){
        $field.="payconf."."{$key}=N'$item',";
    }
    $sql = "update payconf set $field mtime='".date("Y-m-d H:i:s")."'";
    
	$db->setQuery( $sql );
	$db->query();
	
	$setMoney_array = global_get_param( $_POST, 'moneyList', null ,0,1  );
	
	$moneyChk = true;
	foreach($setMoney_array as $row)
	{
		if($row['moneyChk'] == 1)
		{
			$moneyChk = false;
		}
	}
	if($moneyChk)
	{
		$setMoney_array["zh-tw"] = 1;
	}
	
	
	foreach($setMoney_array as $row)
	{
		$sql = " UPDATE langConf SET moneyChk = ".$row['moneyChk']." WHERE id = '".$row['id']."' ";
		$db->setQuery( $sql );
    	$db->query();
	}
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
		
}



include( $conf_php.'common_end.php' ); 
?>