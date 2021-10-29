<?php



include( '../../config.php' ); 
$task = global_get_param($_REQUEST, 'task', null, 0, 1);

switch ($task) {
	case "productTypeList":	
		productTypeList();
		break;
	case "productList":	
		productList();
		break;
}

function productTypeList() {
    global $db, $tableName, $conf_user;
    $userId = $_SESSION[$conf_user]['uid'];
    $returnArray = array();
    
    $parentId = global_get_param($_REQUEST, 'parentId', null);
    if (!$parentId) {
		$parentId = 'root';
	}
	
	$sql_str = " name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str = " name AS nameO , `name_".$_SESSION[$conf_user]['syslang']."` AS name  ";
	}
	
    $sql = "SELECT id, belongid AS parentId, $sql_str FROM producttype WHERE publish = 1 AND belongid = '$parentId' ORDER BY odring, id";
	$db->setQuery($sql);
	$productTypeList = $db->loadRowList();
	
	if (isset($productTypeList)) {
		
		foreach($productTypeList as $key=>$row)
		{
			$productTypeList[$key]['name'] = (!empty($row['name'])) ? $row['name'] : $row['nameO'];
		}
		
		$returnArray['status'] = 1;
		$returnArray['data'] = $productTypeList;
	} else {
		$returnArray['status'] = 0;
		$returnArray['errorCode'] = 1;
		$returnArray['errorMsg'] = _COMMON_MESSAGE_SELECT_ERROR;
	}
	    
    
    JsonEnd($returnArray);
}

function productList() {
    global $db, $conf_user;
    $userId = $_SESSION[$conf_user]['uid'];
    $returnArray = array();
    
    $productTypeId		= global_get_param($_REQUEST, 'productTypeId', null);
	$exceptProductId	= global_get_param($_REQUEST, 'exceptProductId', null);
	$productType 		= global_get_param($_REQUEST, 'productType', null);
	$searchStr = "";
	
    
    if ($productTypeId) {
    	$searchStr .= " AND A.id IN (SELECT pid FROM protype WHERE ptid = '$productTypeId') ";
	}

	if ($productType == 'product') {
    	$searchStr .= " AND amtProChk = '1' ";
	} else if ($productType == 'gift') {
    	$searchStr .= " AND freeProChk = '1' ";
	}
	
	$sql = "SELECT A.* FROM products A WHERE A.publish = 1 $searchStr ORDER BY A.id DESC;";
	$db->setQuery($sql);
	$productList = $db->loadRowList();
	
	
	$sql = "SELECT * FROM imglist WHERE path = 'products';";
	$db->setQuery($sql);
	$imageList = $db->loadRowList();
	
	if (isset($productList)) {
		if ($exceptProductId) {
			foreach($productList as $key=>$val) {
				if (in_array($val['id'], $exceptProductId)) {
					unset($productList[$key]);
				}
			}
		}
		
		foreach($productList as $key=>$val) {
			if($_SESSION[$conf_user]['syslang'] && $val['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$productList[$key]['name']=$val['name_'.$_SESSION[$conf_user]['syslang']];
			}
		}

		$productList = array_values($productList);
		if (isset($imageList)) {
			foreach ($productList as $pKey => $product) {
				foreach ($imageList as $image) {
					if ($image['belongid'] == $product['id'] && is_null($productList[$pKey]['image'])) {
						$productList[$pKey]['image'] = $image['name'];
					}
				}
			}
		}
		
		$returnArray['status'] = 1;
		$returnArray['data'] = $productList;
	} else {
		$returnArray['status'] = 0;
		$returnArray['errorCode'] = 1;
		$returnArray['errorMsg'] = _COMMON_MESSAGE_SELECT_ERROR;
	}
	    
    
    JsonEnd($returnArray);
}

include( $conf_php.'common_end.php' ); 

?>