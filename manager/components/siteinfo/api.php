<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename = "siteinfo";
userPermissionChk($tablename);

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
    $sql = "select * from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        
	        if($key == 'pvbvratio')
	        {
	            $data[$key]=(float)$row;
	        }
	        else
	        {
                $data[$key]=$row;   
	        }
	    }
	}
	
	$textList = getLanguageList("text","all");	
	
	JsonEnd(array("status"=>1,"data"=>$data,"textList"=>$textList));
	
}
function pagedb(){
    global $db;
    
    $data = array();
    $data['name'] = global_get_param( $_POST, 'name', null ,0,1  );
    $data['tel'] = global_get_param( $_POST, 'tel', null ,0,1  );
    $data['fax'] = global_get_param( $_POST, 'fax', null ,0,1  );
    $data['email'] = global_get_param( $_POST, 'email', null ,0,1  );
    $data['addr'] = global_get_param( $_POST, 'addr', null ,0,1  );
    $data['webkeys'] = global_get_param( $_POST, 'webkeys', null ,0,1  );
    $data['webintro'] = global_get_param( $_POST, 'webintro', null ,0,1  );
    $data['WtargetAmt'] = global_get_param( $_POST, 'WtargetAmt', null ,0,1  );
    $data['WtargetOrder'] = global_get_param( $_POST, 'WtargetOrder', null ,0,1  );
    $data['WtargetMember'] = global_get_param( $_POST, 'WtargetMember', null ,0,1  );
    $data['WtargetCustom'] = global_get_param( $_POST, 'WtargetCustom', null ,0,1  );
    $data['MtargetAmt'] = global_get_param( $_POST, 'MtargetAmt', null ,0,1  );
    $data['MtargetOrder'] = global_get_param( $_POST, 'MtargetOrder', null ,0,1  );
    $data['MtargetMember'] = global_get_param( $_POST, 'MtargetMember', null ,0,1  );
    $data['MtargetCustom'] = global_get_param( $_POST, 'MtargetCustom', null ,0,1  );
    $data['coin_to'] = global_get_param( $_POST, 'coin_to', null ,0,1  );
    $data['coin_take'] = global_get_param( $_POST, 'coin_take', null ,0,1  );
    $data['invoice'] = global_get_param( $_POST, 'invoice', null ,0,1  );
    $data['invoice2'] = global_get_param( $_POST, 'invoice2', null ,0,1  );
    $data['invoice3'] = global_get_param( $_POST, 'invoice3', null ,0,1  );
    $data['shipdate'] = intval(global_get_param( $_POST, 'shipdate', null ,0,1  ));
    
    $data['pvbvratio'] = intval(global_get_param( $_POST, 'pvbvratio', null ,0,1  ));
    $data['bouns1'] = intval(global_get_param( $_POST, 'bouns1', null ,0,1  ));
    $data['bouns2'] = intval(global_get_param( $_POST, 'bouns2', null ,0,1  ));
    
    $data['bonusValue'] = intval(global_get_param( $_POST, 'bonusValue', null ,0,1  ));
    
    $data['ytUrl'] = global_get_param( $_POST, 'ytUrl', null ,0,1  );
    $data['fbUrl'] = global_get_param( $_POST, 'fbUrl', null ,0,1  );
    $data['gpUrl'] = global_get_param( $_POST, 'gpUrl', null ,0,1  );
    $data['lineUrl'] = global_get_param( $_POST, 'lineUrl', null ,0,1  );
	
	$data['safetystock'] = intval(global_get_param( $_POST, 'safetystock', null ,0,1  ));
	$data['taxrate'] = intval(global_get_param( $_POST, 'taxrate', null ,0,1  ));
    
    
    $ori_pvbvratio = (float)getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
    
    $new_pvbvratio = (float)$data['pvbvratio'];
	
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$data['`name_'.$row['code'].'`'] = global_get_param( $_POST, 'name_'.$row['code'], null ,0,1  );
			$data['`addr_'.$row['code'].'`'] = global_get_param( $_POST, 'addr_'.$row['code'], null ,0,1  );
		}
	}
    
    $field="";
    foreach($data as $key=>$item){
        $field.="$key=N'$item',";
    }
    $sql = "update siteinfo set $field mtime='".date("Y-m-d H:i:s")."'";
   
	$db->setQuery( $sql );
	$db->query();
	
	
	if($ori_pvbvratio != $new_pvbvratio )
	{
	    
	    $sql = " UPDATE products SET bv = pv * (".$new_pvbvratio.")";
	    $db->setQuery( $sql );
    	$db->query();
	    
	    
	    $sql = " UPDATE active SET bv = pv * (".$new_pvbvratio.")";
	    $db->setQuery( $sql );
    	$db->query();
	    
	}
	
	$setText_array = global_get_param( $_POST, 'textList', null ,0,1  );
	
	$textChk = true;
	foreach($setText_array as $row)
	{
		if($row['textChk'] == 1)
		{
			$textChk = false;
		}
	}
	if($textChk)
	{
		$setText_array["zh-tw"] = 1;
	}
	
	
	foreach($setText_array as $row)
	{
		$sql = " UPDATE langConf SET textChk = ".$row['textChk']." WHERE id = '".$row['id']."' ";
		$db->setQuery( $sql );
    	$db->query();
	}
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
		
}



include( $conf_php.'common_end.php' ); 
?>