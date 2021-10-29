<?php



include('../../config.php');
$task = global_get_param($_REQUEST, 'task', null, 0, 1);
$tablename = "p_to_m";
userPermissionChk("order");
switch ($task) {

	case "list":
		showlist();
		break;
	case "upd_exportChk":
		upd_exportChk();
		break;
	case "set_carried":
		set_carried();
		break;
}


function showlist()
{
	global $db, $db3, $tablename, $conf_user, $globalConf_list_limit;
	$cur = global_get_param($_REQUEST, 'page', null);
	$date = global_get_param($_REQUEST, 'date', null);
	$date = str_replace('\"', '"', $date);
	$datearray = json_decode($date, true);
	$startDate = $datearray['startDate'];
	$endDate = $datearray['endDate'];

	$cdate = global_get_param($_REQUEST, 'cdate', null);
	$cdate = str_replace('\"', '"', $cdate);
	$datearray = json_decode($cdate, true);
	$startCDate = $datearray['startDate'];
	$endCDate = $datearray['endDate'];

	$search = global_get_param($_REQUEST, 'search', null);
	$status = global_get_param($_REQUEST, 'status', null);
	$exportChk = global_get_param($_REQUEST, 'exportChk', null);
	$carriedChk = global_get_param($_REQUEST, 'carriedChk', null);
	$invalidChk = global_get_param($_REQUEST, 'invalidChk', null);
	$alldataChk = global_get_param($_REQUEST, 'alldataChk', null);
	$orderby = global_get_param($_REQUEST, 'orderby', null);
	$arrJson = array();
	$data = array();
	if ($orderby) {
		$orderby = str_replace('\"', '"', $orderby);
		$orderarray = json_decode($orderby, true);
		if (count($orderarray) > 0) {
			$orderstr = "";
			foreach ($orderarray as $k => $v) {
				$orderstr = "A." . $k . " " . $v . "," . $orderstr;
			}
			$orderstr = "ORDER BY " . $orderstr;
			$orderstr = substr($orderstr, 0, -1);
		}
		$orderstr .= ",A.id desc";
	}
	$midStr = '';
	$where_str = '';
	if ($search) {
		$sql = " select id from members where name like '%$search%'";
		$db->setQuery($sql);
		$row = $db->loadRowList();
		$idarr = array();
		foreach ($row as $id) {
			$idarr[] = $id['id'];
		}
		if (count($idarr) > 0) {
			$midStr .= " OR A.memberid in ('" . implode("','", $idarr) . "')";
		}
		$where_str .= " AND ( A.dlvrName like '%$search%' OR A.orderNum like '%$search%' OR A.email like '%$search%' OR A.traceNumber like '%$search%' $midStr )";
	}

	if ($startDate) {

		$startDate = date("Y-m-d", strtotime($startDate));

		$where_str .= " AND A.set_date>='$startDate'";
	}
	if ($endDate) {

		$endDate = date("Y-m-d", strtotime($endDate));

		$where_str .= " AND A.set_date<='$endDate'";
	}

	if ($startCDate) {
		$status = 6;
		$where_str .= " AND A.endDate>='$startCDate'";
	}
	if ($endCDate) {
		$status = 6;
		$where_str .= " AND A.endDate<='$endCDate'";
	}

	if (isset($status) && $status != -1) {
		$where_str .= " AND A.status='$status'";
	}

	if (isset($exportChk) && $exportChk != -1) {

		if ($exportChk == '1') {
			$where_str .= " AND A.exportChk='$exportChk'";
		} else {
			$where_str .= " AND ( A.exportChk='0' OR A.exportChk IS NULL )";
		}
	}


	if (isset($carriedChk) && $carriedChk != -1) {

		if ($carriedChk == '1') {
			$where_str .= " AND A.carried_forward='$carriedChk'";
		} else {
			$where_str .= " AND ( A.carried_forward='0' OR A.carried_forward IS NULL )";
		}
	}

	if (isset($invalidChk) && $invalidChk != -1) {

		if ($invalidChk == '1') {
			$where_str .= " AND A.is_invalid='$invalidChk'";
		} else {
			$where_str .= " AND ( A.is_invalid='0' OR A.is_invalid IS NULL )";
		}
	}

	$orderMode = global_get_param($_REQUEST, 'orderMode', null);
	$payType = global_get_param($_REQUEST, 'payType', null);
	if (isset($orderMode) && $orderMode != -1) {

		if ($orderMode == '1') {
			$where_str .= " AND A.orderMode <> 'addMember'";
		} else {
			$where_str .= " AND A.orderMode = 'addMember'";
		}
	}
	if (isset($payType) && $payType != -1) {

		$where_str .= " AND A.payType = '$payType'";
	}

	


	$today = date("Y-m-d");

	$orderstr = ' order by set_date desc,create_time desc';

	$sql = " select * from $tablename A where 1=1 $where_str $orderstr ";
	$arrJson['sq'] = $sql;
	$db3->setQuery($sql);
	$row = $db3->loadRowList();

	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;

	$from = ($cur - 1) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;

	$alldata_list = $row;
	foreach ($alldata_list as $k => $v) {
		if($alldata_list[$k]['kind'] == '1'){
			$alldata_list[$k]['kind_name'] = _PM_KIND_1;
		}else if ($alldata_list[$k]['kind'] == '2') {
			$alldata_list[$k]['kind_name'] = _PM_KIND_2;
		}

		if ($alldata_list[$k]['is_invalid'] == '1') {
			$alldata_list[$k]['is_invalid_str'] = _PM_IS_INV_1;
		} else if ($alldata_list[$k]['is_invalid'] == '0') {
			$alldata_list[$k]['is_invalid_str'] = _PM_IS_INV_0;
		}

		if ($alldata_list[$k]['carried_forward'] == '1') {
			$alldata_list[$k]['carried_forward_str'] = _PM_CF_1;
		} else if ($alldata_list[$k]['carried_forward'] == '0') {
			$alldata_list[$k]['carried_forward_str'] = _PM_CF_0;
		}


		if ($alldata_list[$k]['exportChk'] == '1') {
			$alldata_list[$k]['exportChk_str'] = _YES;
		} else if ($alldata_list[$k]['exportChk'] == '0') {
			$alldata_list[$k]['exportChk_str'] = _NO;
		}
		$sql2 = "SELECT name FROM members where ERPID = '{$alldata_list[$k]['mb_no']}'";
		$db->setQuery($sql2);
		$m = $db->loadRow();
		$name = $m['name'];
		$alldata_list[$k]['mb_name'] = $name;

	}



	for ($i = $from; $i < min($end, $cnt); $i++) {
		foreach ($row[$i] as $each) {
			if ($row[$i]['kind'] == '1') {
				$row[$i]['kind_name'] = _PM_KIND_1;
			} else if ($row[$i]['kind'] == '2') {
				$row[$i]['kind_name'] = _PM_KIND_2;
			}
	
			if ($row[$i]['is_invalid'] == '1') {
				$row[$i]['is_invalid_str'] = _PM_IS_INV_1;
			} else if ($row[$i]['is_invalid'] == '0') {
				$row[$i]['is_invalid_str'] = _PM_IS_INV_0;
			}
	
			if ($row[$i]['carried_forward'] == '1') {
				$row[$i]['carried_forward_str'] = _PM_CF_1;
			} else if ($row[$i]['carried_forward'] == '0') {
				$row[$i]['carried_forward_str'] = _PM_CF_0;
			}
	
	
			if ($row[$i]['exportChk'] == '1') {
				$row[$i]['exportChk_str'] = _YES;
			} else if ($row[$i]['exportChk'] == '0') {
				$row[$i]['exportChk_str'] = _NO;
			}
			$sql2 = "SELECT name FROM members where ERPID = '{$row[$i]['mb_no']}'";
			$db->setQuery($sql2);
			$m = $db->loadRow();
			$name = $m['name'];
			$row[$i]['mb_name'] = $name;
		}

		
		$data[] = $row[$i];
	}

	$printcsv = "";
	$printcsv .= _PM_EXPORT_STR_1 . "," . _PM_EXPORT_STR_2 . "," . _PM_EXPORT_STR_3 . "," . _PM_EXPORT_STR_4 . "," . _PM_EXPORT_STR_5 . "," . _PM_EXPORT_STR_6 . "," . _PM_EXPORT_STR_7 . "\n";

	$printcsv_array = array();
	$printcsv_array[0] = $printcsv;

	// $sql = "SELECT * FROM $tablename order by set_date desc";
	// $db3->setQuery($sql);
	// $list = $db3->loadRowList();
	$pmid_arr = array();

	if($alldataChk == '1'){
		$print_data = $data;
	}else{
		$print_data = $alldata_list;
	}
	
	
	foreach ($print_data as $each) {

		$printcsvStr = "";
		$printcsvStr .= $each['mb_no'] . ",";
		$printcsvStr .= $each['mb_name'] . ",";
		$printcsvStr .= $each['set_date'] . ",";
		$printcsvStr .= $each['points'] . ",";
		$printcsvStr .= $each['kind_name'] . ",";
		$printcsvStr .= $each['is_invalid_str'] . ",";
		$printcsvStr .= $each['carried_forward_str'] . ",\n";

		$printcsv .= $printcsvStr;
		$printcsv_array[intval($each['id'])] .= $printcsvStr;

		
		if (empty($each['exportChk'])) {
			$pmid_arr[] = $each['id'];
		}
	}
	
	if (count($pmid_arr) > 0) {
		$_SESSION[$conf_user]['pmid_arr'] = $pmid_arr;
	}

	$_SESSION[$conf_user]['pm_printcsv'] = $printcsv;
	$_SESSION[$conf_user]['pm_printcsv_array'] = $printcsv_array;


	$arrJson['status'] = '1';
	$arrJson['db3'] = $sql;
	$arrJson['orderExport'] = getFieldValue(" SELECT orderExport FROM adminmanagers WHERE id= '{$_SESSION[$conf_user]['uid']}' ", "orderExport");

	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	// $arrJson['printhtml'] = $printhtml;
	$arrJson['printcsv_array'] = $printcsv_array;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
}

function upd_exportChk()
{
	global $db, $db3, $tablename, $conf_user;
	$filename = "";

	$date = getFieldValue(" SELECT codeName FROM pubcode WHERE codeKinds = 'exportNum' ", "codeName");
	if (date("Y-m-d", strtotime($date)) == date("Y-m-d")) {
		$num = intval(getFieldValue(" SELECT codeValue FROM pubcode WHERE codeKinds = 'exportNum' ", "codeValue"));
		$next = $num + 1;
		$num = ($num < 10) ? "0" . $num : $num;
		$filename = "PM" . date("ymd") . $num . ".csv";

		$sql = " update pubcode set codeValue = '" . $next . "' WHERE codeKinds = 'exportNum' ";
		$db->setQuery($sql);
		$db->query();
	} else {
		$filename = "PM" . date("ymd") . "01.csv";

		$sql = " update pubcode set codeName = '" . date("Y-m-d") . "', codeValue = '2' WHERE codeKinds = 'exportNum' ";
		$db->setQuery($sql);
		$db->query();
	}


	$idstr = global_get_param($_REQUEST, 'idstr', null);
	$idstrArr = explode("||", trim($idstr, "||"));
	$oidArr = array();
	if (count($idstrArr) > 0 && !empty($idstrArr[0])) {
		foreach ($idstrArr as $oid) {
			$oidArr[] = intval($oid);
		}

		rsort($oidArr);

		$dataArr = $_SESSION[$conf_user]['pm_printcsv_array'];

		$content = $dataArr[0];
		foreach ($oidArr as $oid) {
			$content .= $dataArr[$oid];
		}

		$sql = "update $tablename set exportChk = 1 where id IN ('" . implode("','", $oidArr) . "') ";
		$db3->setQuery($sql);
		$db3->query();
	} else {
		$content = $_SESSION[$conf_user]['pm_printcsv'];

		$pmid_arr = $_SESSION[$conf_user]['pmid_arr'];

		if (count($pmid_arr) > 0) {
			$pm_arr = implode("','", array_filter($pmid_arr));
			if(count($pm_arr > 0)){
				$sql = "update $tablename set exportChk = 1 where id IN ('" . $pm_arr . "') ";
				$db3->setQuery($sql);
				$db3->query();
			}
			
		}
	}



	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=" . $filename);


	$content = mb_convert_encoding($content, "Big5", "UTF-8");
	echo $content;
	exit;
}

function set_carried(){

	global $db,$db3,$conf_user,$tablename;
	$res = array();
	$uid = $_SESSION[$conf_user]['uid'];
	$idstr = global_get_param($_POST, 'idstr', null);
	$idstrArr = explode("||", trim($idstr, "||"));
	$oidArr = array();
	if (count($idstrArr) > 0 && !empty($idstrArr[0])) {
		foreach ($idstrArr as $oid) {
			$oidArr[] = intval($oid);
		}

		rsort($oidArr);


		$sql = "update $tablename set carried_forward = 1 where id IN ('" . implode("','", $oidArr) . "') ";
		$db3->setQuery($sql);
		$db3->query();

		//紀錄
		$log = array();
		$log['user'] = $uid;
		$log['pm_id'] = json_encode($oidArr);
		$lsql = dbInsert('carried_forward',$log);
		$db3->setQuery($lsql);
		$db3->query();
	}
	$res['status'] = '1';
	$res['msg'] = 'Done';
	JsonEnd($res);
}

include($conf_php . 'common_end.php');
