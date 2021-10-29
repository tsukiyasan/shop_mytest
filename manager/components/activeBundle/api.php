<?php


include( '../../config.php' ); 
$task = global_get_param($_REQUEST, 'task', null, 0, 1);
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tableName = "activeBundle";
userPermissionChk($tableName);

switch ($task) {
	case "list":	
		activeBundle_list();
		break;
	case "detail":	
		activeBundle_detail();
		break;
	case "add":		
	case "update":	
		activeBundle_update();
		break;
	case "del":	
		activeBundle_del();
		break;
	case "getBasicInfo":	
		getBasicInfo();
	    break;
	case "alllist":
	    activeBundle_list('all');
		break;
	case "odrchg":	
		odrchg($tableName,$id);
		break;
	case "getBasicInfo2":
		getBasicInfo2();
		break;
}

function getBasicInfo2()
{
	global $db, $tablename;
	$arrJson = array();
	
	$arrJson['textList'] = getLanguageList("text");
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function activeBundle_list($mode=null) {
	global $db, $globalConf_list_limit, $tableName,$conf_user;
	$returnArray = array();
	
	$page 	= global_get_param($_REQUEST, 'page', null, 0, 1);
	$limit	= global_get_param($_REQUEST, 'limit', $globalConf_list_limit, 0, 1);
	$search = global_get_param($_REQUEST, 'search', null, 0, 1);
	
	$whereStr = "";
	if (isset($search)) {
		
		$searchArray = explode(" ", $search);
		if (count($searchArray) > 0) {
			foreach($searchArray as $str) {
				$whereStr .= " OR ( name like N'%$str%' )";
			}
		}
		$whereStr = "AND ( 1 <> 1 $whereStr) ";
	}
	
	
	$count = getFieldValue("SELECT COUNT(1) AS count FROM $tableName WHERE alive = 1 $whereStr;", "count");
	$pageCount = max((floor($count / $limit) + ($count % $limit == 0 ? 0 : 1)), 1);
	$page = max(min($page, $pageCount), 1);
	$offset = ($page - 1) * $limit;
	
	$sql = "SELECT * FROM $tableName WHERE alive = 1 $whereStr ORDER BY odring, startTime DESC ";
	if($mode!='all'){
		$sql.=" LIMIT $limit OFFSET $offset ";
	}
	$db->setQuery($sql);
	$activeBundleList = $db->loadRowList();
	if (isset($activeBundleList)) {

		foreach ($activeBundleList as $key=>$value) {
			
			if($_SESSION[$conf_user]['syslang'] && $activeBundleList[$key]['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$activeBundleList[$key]['name']=$activeBundleList[$key]['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			
			$activeBundleList[$key]['url'] = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].'/active_list/0?id='.$value['id'];
		}

		$returnArray['status'] = 1;
		$returnArray['data'] = $activeBundleList;
		$returnArray['pageCount'] = $pageCount;
	} else {
		$returnArray['status'] = 0;
		$returnArray['errorMsg'] = _COMMON_QUERYMSG_SELECT_ERR;
	}
	
	JsonEnd($returnArray);
}

function activeBundle_detail(){
	global $db, $tableName,$conf_user;
	$returnArray = array();
	
	$sql_str = " name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str = " name AS nameO , `name_".$_SESSION[$conf_user]['syslang']."` AS name  ";
	}
	
	$id = global_get_param($_REQUEST, 'id', null);
	$sql = "SELECT * FROM $tableName WHERE id = '$id' AND alive = 1;";

	$db->setQuery($sql);
	$activeBundle = $db->loadRow();	
	if (isset($activeBundle)) {
		
		$giftList = array();
		if ($activeBundle['giftProductId']) {
			$giftIdArray = explode('|', trim($activeBundle['giftProductId'], "|"));
			$sql = "SELECT id, $sql_str FROM products WHERE id IN ('".implode("','", $giftIdArray)."')";
			$db->setQuery($sql);
			$giftList = $db->loadRowList();
			if (isset($giftList) && count($giftList) > 0) {
				$tmp_giftList = array();
				foreach ($giftList as $key=>$product) {
					$product['name'] = (!empty($product['name'])) ? $product['name'] : $product['nameO'];
					$tmp_giftList[$product['id']] = $product;
					$tmp_giftList[$product['id']]['image'] = reset(getimg('products', $product['id']));
				}
				$giftList = array();
				foreach($giftIdArray as $key=>$giftProductId)
				{
					if(!empty($tmp_giftList[$giftProductId]))
					{
						$giftList[] = $tmp_giftList[$giftProductId];
					}
					
				}
				
				
				
				
			} else {
				$giftList = array();
			}
		}
		
		
		$sql = "SELECT * FROM activeBundleDetail WHERE activeBundleId = '$id' order by sequence;";
		$db->setQuery($sql);
		$activeBundleDetail = $db->loadRowList();
		foreach ($activeBundleDetail as $key=>$value) {
			$productIdArray = explode('|', trim($value['products'], "|"));
			$sql = "SELECT  id, $sql_str FROM products WHERE id IN ('".implode("','", $productIdArray)."')";
			$db->setQuery($sql);
			$productList = $db->loadRowList();
			if (isset($productList) && count($productList) > 0) {
				foreach ($productList as $productKey=>$product) {
					$productList[$productKey]['name'] = (!empty($product['name'])) ? $product['name'] : $product['nameO'];
					$productList[$productKey]['image'] = reset(getimg('products', $product['id']));
				}
			} else {
				$productList = array();
			}
			$activeBundleDetail[$key]['productList'] = $productList;
		}
		
		$textList = getLanguageList("text");
		$nameList = array();
		$notesList = array();
		if($textList && count($textList) > 0)
		{
			foreach($textList as $row)
			{
				$nameList[$row['code']] = $activeBundle["name_".$row['code']];
				$notesList[$row['code']] = $activeBundle["name_".$row['code']];
			}
		}
		
		$returnArray['status'] = 1;
		$returnArray['data'] = $activeBundle;
		$returnArray['nameList'] = $nameList;
		$returnArray['notesList'] = $notesList;
		$returnArray['giftList'] = $giftList;
		$returnArray['areaList'] = $activeBundleDetail;
	} else {
		$returnArray['status'] = 0;
		$returnArray['errorMsg'] = _COMMON_QUERYMSG_SELECT_ERR;
	}
	
	JsonEnd($returnArray);
}

function activeBundle_update() {
	global $db, $conf_user, $tableName;
	$returnArray = array();
	$currentTime = date("Y-m-d H:i:s");
	$uid = $_SESSION[$conf_user]['uid'];
	
	$id	= global_get_param($_REQUEST, 'id', null);

	$sqlParam = array();
	$sqlParam['enable'] 			= global_get_param($_REQUEST, 'enable', null);
	$sqlParam['name'] 				= global_get_param($_REQUEST, 'name', null);
	$sqlParam['notes'] 				= global_get_param($_REQUEST, 'notes', null);
	$sqlParam['startTime'] 			= global_get_param($_REQUEST, 'startTime', null);
	$sqlParam['endTime'] 			= global_get_param($_REQUEST, 'endTime', null);
	$sqlParam['price'] 				= global_get_param($_REQUEST, 'price', null);
	
	$pv = global_get_param($_REQUEST, 'pv', null);
	$pvbvRatio = getFieldValue("SELECT pvbvratio FROM siteinfo", "pvbvratio");
	$sqlParam['pv']  		= $pv;
	$sqlParam['bv']  		= $pv * $pvbvRatio;
	
	$sqlParam['passwordCheck'] 		= global_get_param($_REQUEST, 'passwordCheck', null);
	$sqlParam['passwordText']		= global_get_param($_REQUEST, 'passwordText', null);
	$sqlParam['limitCount']			= global_get_param($_REQUEST, 'limitCount', null);

	$sqlParam['giftCheck'] 			= global_get_param($_REQUEST, 'giftCheck', null);
	$sqlParam['giftTargetAmount']	= global_get_param($_REQUEST, 'giftTargetAmount', null);
	
	$areaList = global_get_param($_REQUEST, 'areaList', null);
	$giftList = global_get_param($_REQUEST, 'giftList', null);
	
	$sql = "BEGIN;";
	if (is_array($giftList) && count($giftList) > 0) {
		$sqlParam['giftProductId']	= '|'.implode('|', $giftList).'|';
		$sql .= "UPDATE products SET freeProChk = '1' WHERE id IN ('".implode("','", $giftList)."');";	
	}
	
	$updatesql_addStr = "";
	$updatevalue_addStr = "";
	$updatesqlend_addStr = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			if(empty($sqlParam['name']))
			{
				$sqlParam['name'] = $nameTmp;
			}
			$sqlParam["name_".$row['code']] = $nameTmp;
			
			$notesTmp = global_get_param( $_REQUEST, 'notes_'.$row['code'], null ,0,0  );
			if(empty($sqlParam['notes']))
			{
				$sqlParam['notes'] = $notesTmp;
			}
			$sqlParam["notes_".$row['code']] = $notesTmp;
		}
	}
	
	
	if ($id) {
		$sqlParam['updateTime'] 	= $currentTime;
		$sqlParam['updateUserId'] 	= $uid;
		
		$whereArray = array();
		$whereArray['id']		= $id;
		$whereArray['alive']	= 1;

		$sql .= createUpdateSqlV2($tableName, $sqlParam, $whereArray);
		$sql .= "DELETE FROM activeBundleDetail WHERE activeBundleId = '$id';";
	} else {
		$sqlParam['alive'] 			= 1;
		$sqlParam['createTime'] 	= $currentTime;
		$sqlParam['updateTime'] 	= $currentTime;
		$sqlParam['updateUserId'] 	= $uid;
		$sql .= createInsertSqlV2($tableName, $sqlParam);
		$sql .= "SET @insertId = LAST_INSERT_ID();";
	}

	$i = 1;
	if (is_array($areaList) && count($areaList) > 0) {
		foreach ($areaList as $value) {
			$productId = "|";
			foreach ($value['productList'] as $product) {
				$productId .= $product['id']."|";
			}
			
			if($textList && count($textList) > 0)
			{
				$updatesql_addStr = "";
				$updatevalue_addStr = "";
				foreach($textList as $row)
				{
					$nameTmp =  $value['name_'.$row['code']];
					if(empty($value['name']))
					{
						$value['name'] = $nameTmp;
					}
					$updatesql_addStr .= " `name_".$row['code']."` , ";
					$updatevalue_addStr .= " N'$nameTmp', ";
				}
			}
			
			if ($id) {
				$sql .= "INSERT INTO activeBundleDetail (activeBundleId, name,{$updatesql_addStr} quantity, products, sequence) VALUES ('$id', '{$value['name']}',{$updatevalue_addStr} '{$value['quantity']}', '$productId', '$i');";
			} else {
				$sql .= "INSERT INTO activeBundleDetail (activeBundleId, name,{$updatesql_addStr} quantity, products, sequence) VALUES (@insertId, '{$value['name']}',{$updatevalue_addStr} '{$value['quantity']}', '$productId', '$i');";
			}
			$i++;
		}
	}
	
	$sql .= "COMMIT;";
	$db->setQuery($sql);
	if($db->query_batch()) {
		if (!$id) {
			$id = getFieldValue("SELECT @insertId", "@insertId");
		}
		$returnArray['status'] = 1;
		$returnArray['activeBundleId'] = $id;
		$returnArray['message'] = $id ? _COMMON_QUERYMSG_UPD_SUS : _COMMON_QUERYMSG_ADD_SUS;
	} else {
		$returnArray['status'] = 0;
		$returnArray['errorMsg'] = $id ? _COMMON_QUERYMSG_UPD_ERR : _COMMON_QUERYMSG_ADD_ERR;
	}
	
	JsonEnd($returnArray);
}

function activeBundle_del() {
	global $db, $conf_user, $tableName;
    $returnArray = array();
	$currentTime = date("Y-m-d H:i:s");
	$uid = $_SESSION[$conf_user]['uid'];
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ));
	
	$sql = "UPDATE $tableName SET alive = '0', updateTime = '$currentTime', updateUserId = '$uid' WHERE id = '$id'";

	$db->setQuery($sql);
	if($db->query()) {
		$returnArray['status'] = 1;
		$returnArray['message'] = _COMMON_QUERYMSG_DEL_SUS;
	} else{
		$returnArray['status'] = 0;
		$returnArray['errorMsg'] = _COMMON_QUERYMSG_DEL_ERR;
	}
	
	JsonEnd($returnArray);
}

function getBasicInfo() {
	global $db, $conf_user;
	
    $returnArray = array();

	
	$pvbvRatio = getFieldValue("SELECT pvbvratio FROM siteinfo", "pvbvratio");
	
	$returnArray['status'] = '1';
	$returnArray['pvbvRatio'] = $pvbvRatio;
	JsonEnd($returnArray);
}

function createInsertSqlV2($tableName, $sqlParam) {
	$sqlField = "";
	$sqlValue = "";
	
	foreach ($sqlParam as $field => $value) {
		if (isset($value)) {
			$value=str_replace("''","'",$value);
			$value=str_replace("'","''",$value);
			$sqlField .= "`$field`,";
			if (strpos($value, '@') === 0 && (strpos($value, 'Id') !== false || strpos($value, 'id') !== false)) {
				$sqlValue .= "$value,";
			} else {
				$sqlValue .= "N'$value',";
			}
		}
	}
	$sqlField = rtrim($sqlField, ",");
	$sqlValue = rtrim($sqlValue, ",");
	
	return "INSERT INTO $tableName ($sqlField) VALUES ($sqlValue);";
}

function createUpdateSqlV2($tableName, $sqlParam, $whereArray = array()) {
	$sqlSet = "";
	$sqlWhere = "";
	
	foreach ($sqlParam as $field => $value) {
		if (isset($value)) {
			$value=str_replace("''","'",$value);
			$value=str_replace("'","''",$value);
			if (strpos($value, '@') === 0 && (strpos($value, 'Id') !== false || strpos($value, 'id') !== false)) {
				$sqlSet .= "`$field`=$value,";
			} else {
				$sqlSet .= "`$field`=N'$value',";
			}
		}
	}
	$sqlSet = rtrim($sqlSet, ",");
	
	foreach ($whereArray as $field => $value) {
		if (is_array($value)) {
			$value=str_replace("''","'",$value);
			$value=str_replace("'","''",$value);
			$target = implode(",", $value);
			$sqlWhere .= " AND `$field` IN ($target)";
		} else {
			$sqlWhere .= " AND `$field`=N'$value'";
		}
	}
	
	return "UPDATE $tableName SET $sqlSet WHERE 1=1 $sqlWhere;";
}
include( $conf_php.'common_end.php' ); 
?>