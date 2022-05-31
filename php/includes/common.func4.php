<?php

defined('_VALID_WAY') or die('Do not Access the Location Directly!');

if (phpversion() < '4.2.0') {
    require "$globalConf_absolute_path/includes/compat.php41x.php";
}
if (phpversion() < '4.3.0') {
    require "$globalConf_absolute_path/includes/compat.php42x.php";
}
if (in_array('_post', array_keys(array_change_key_case($_REQUEST, CASE_LOWER)))) {
    die('Fatal error.  Post variable hack attempted.');
}
if (in_array('_get', array_keys(array_change_key_case($_REQUEST, CASE_LOWER)))) {
    die('Fatal error.  GET variable hack attempted.');
}
if (in_array('_request', array_keys(array_change_key_case($_REQUEST, CASE_LOWER)))) {
    die('Fatal error.  REQUEST variable hack attempted.');
}

@set_magic_quotes_runtime(0);

if (@$globalConf_error_reporting === 0) {
    error_reporting(0);
} else if (@$globalConf_error_reporting > 0) {
    error_reporting($globalConf_error_reporting);
}

function take_type($getamt = null, $getname = null, $getarray = null)
{
    global $db, $conf_user;
    $mode = getCartMode();
    $origin_mode = $mode;
    $is_twcart = $_SESSION[$conf_user]['is_twcart_cart'];
    if ($is_twcart == '1') {
        $mode = 'twcart';
    } else {
        $mode = $origin_mode;
    }
    $data = array();
    $data2 = array();
    $sql = "select * from payconf";
    $db->setQuery($sql);
    $r = $db->loadRow();
    $pay_type = $_SESSION[$conf_user]['pay_type'];
    if (($r['homeDlvr'] == 1 && ($pay_type == 2 || $pay_type == 3 || $pay_type == 4 || $pay_type == 6 || $pay_type == 7)) || $getname || $getamt) {

        $data[1] = array("id" => 1, "name" => _EWAYS_TAKE_TYPE1, "amt" => $r['homeDlvrAmt']);
    }

    $data2[1] = array("id" => 1, "name" => _EWAYS_TAKE_TYPE1, "amt" => $r['homeDlvrAmt']);

    if (($r['selfDlvr'] == 1 && ($pay_type == 2 || $pay_type == 3 || $pay_type == 4 || $pay_type == 5 || $pay_type == 6 || $pay_type == 7)) || $getname || $getamt) {
        // $data[2] = array("id" => 2, "name" => _EWAYS_TAKE_TYPE2, "amt" => 0);
    }

    $data2[2] = array("id" => 2, "name" => _EWAYS_TAKE_TYPE2, "amt" => 0);

    if (($r['dlvrPay'] == 1 && $pay_type == 1) || $getname || $getamt) {

        $data[3] = array("id" => 3, "name" => _EWAYS_TAKE_TYPE3, "amt" => $r['dlvrAmt']);
    }

    $data2[3] = array("id" => 3, "name" => _EWAYS_TAKE_TYPE3, "amt" => $r['dlvrAmt']);

    if ($getname) {
        return $data2[$getname]['name'];
    }
    $take_type = 1;
    if ($pay_type == 5) {
        $take_type = 2;
    }
    if ($pay_type == 1) {
        $take_type = 3;
    }
    $dlvrAmt = 0;
    if ($_SESSION[$conf_user]['take_type']) {
        foreach ($data as $key => $row) {
            if ($row['id'] == $_SESSION[$conf_user]['take_type']) {
                $take_type = $row['id'];
                $dlvrAmt = $row['amt'];

            }
        }
    }

    if ($getamt) {
        return $dlvrAmt;
    }
    $cart = $_SESSION[$conf_user]["{$mode}_list"];
    $proArr = CartProductInfo2($cart);

    if ($getarray) {
        if (count($data) == 0) {

            $data[1] = array("id" => 1, "name" => _EWAYS_TAKE_TYPE1, "amt" => $r['homeDlvrAmt']);
        }
        return $data;
    } else {
        JsonEnd(array("status" => 1, "data" => $data, "take_type" => $take_type, "dlvrAmt" => intval($dlvrAmt) - intval($proArr['disDlvrAmt']), "disDlvrAmt" => intval($proArr['disDlvrAmt'])));
    }

}

function logisitics_type($getid = null, $getom = null, $getdlvr = null, $temp_total = null)
{
    global $db, $conf_user;
    $res = array();
    $res['dlvrfeeStr'] = '';
    $mode = getCartMode();
    $sql = "select * from logistics where 1=1";
    $use_p = $_SESSION[$conf_user]['use_p']; //檢測是否有選使用購物金
    if ($use_p == 1) {
        $use_points = $_SESSION[$conf_user]['use_points'];
    } else {
        $use_points = 0;
    }

    //檢測是否有選使用回饋點
    $cb_use_p = $_SESSION[$conf_user]['cb_use_p'];
    if ($cb_use_p == 1) {
        $cb_use_points = $_SESSION[$conf_user]['cb_use_points'];
    } else {
        $cb_use_points = 0;
    }

    //檢測是否有選使用活動回饋點
    $acb_use_points = 0;
    if (isset($_SESSION[$conf_user]['acb_use_points']) && $_SESSION[$conf_user]['acb_use_points'] > 0) {
        $acb_use_points = $_SESSION[$conf_user]['acb_use_points'];
    }

    if ($getid) {
        $sql .= " AND id='$getid'";
    }

    if ($getom == '2') {
        $sql .= " AND having_outlying = '1'";
    }

    if ($getdlvr) {
        $db->setQuery($sql);
        $r = $db->loadRow();
        $_SESSION[$conf_user]['logistics_str'] = $r['company'];
        $dlvr = $r[$getdlvr];
        $CalcFlvrFree = $_SESSION[$conf_user]['CalcDlvrFree'];
        $CalcFlvrFreeNum = $_SESSION[$conf_user]['CalcDlvrFreeNum'];
        $extra_dlvrfee = 0;
        $tmp_total = $_SESSION[$conf_user]['tmp_total'];
        if (!empty($temp_total)) {
            $tmp_total = $temp_total;
        }
        $totalAmt = $tmp_total - $cb_use_points - $acb_use_points - $CalcFlvrFree;
        //加上新的活動分組金額
        $shopCart = getShopCartData();
        $activeBundle = isset($shopCart['session']['activeBundle']) ? $shopCart['session']['activeBundle'] : false;
        if (!empty($activeBundle)) {
            foreach ($activeBundle as $_activeBundle) {
                $totalAmt += $shopCart['activeBundle']['actives'][$_activeBundle['id']]['price'];
                //分組加價
                foreach ($_activeBundle['params']['products'] as $_product) {
                    $bundleaddChk = $shopCart['activeBundle']['products'][$_product['id']]['bundleaddChk'];
                    if ($bundleaddChk == '1') {
                        $bundleadd = $shopCart['activeBundle']['products'][$_product['id']]['bundleadd'];
                        $totalAmt += $bundleadd;
                    }
                }
            }
        }
        //print_r([$totalAmt]);
        // $totalAmt = $totalAmt - $use_p - $cb_use_p;
        if ($getdlvr == 'main_dlvr' || $getdlvr == 'outlying_dlvr') {
            $m_fst = $r['main_fst'];
            $o_fst = $r['outlying_fst'];

            if ($getdlvr == 'main_dlvr') {
                if ($m_fst > 0) {
                    if ($totalAmt >= $m_fst) {
                        $dlvr = '0';
                        $res['dlvrfeeStr'] = '滿' . $m_fst . '免運';
                    }
                }
            }

            if ($getdlvr == 'outlying_dlvr') {
                if ($o_fst > 0) {
                    if ($totalAmt >= $o_fst) {
                        $dlvr = '0';
                        $res['dlvrfeeStr'] = '滿' . $o_fst . '免運';
                    }
                }
            }
        } else if ($getdlvr == 'f_outlying_dlvr' || $getdlvr == 'f_main_dlvr') { //如果冷凍
            $f_o_fst = $r['f_outlying_fst'];
            $f_m_fst = $r['f_main_fst'];
            $fmdb = $r['f_main_dlvr_basic'];
            $fodb = $r['f_outlying_dlvr_basic'];
            $fmd = $r['f_main_dlvr'];
            $fod = $r['f_outlying_dlvr'];
            $dlvr = 0;
            if ($getdlvr == 'f_main_dlvr') { //本島冷凍
                // if ($f_m_fst > 0) {
                //     $dlvr_cnt = floor($totalAmt / $f_m_fst);
                //     $dlvr_cnt2 = fmod($totalAmt, $f_m_fst);
                //     if ($dlvr_cnt > 0) {
                //         $dlvr += $dlvr_cnt * $fmdb;
                //     }
                //     if ($dlvr_cnt2 > 0) {
                //         $dlvr += $fmd;
                //     }
                // }
                if ($f_m_fst > 0) {
                    //3680免運
                    if ($totalAmt >= $f_m_fst) {
                        $dlvr = '0';
                    } else {
                        $dlvr = $fmd;
                    }
                }
            }

            if ($getdlvr == 'f_outlying_dlvr') {
                // if ($f_o_fst > 0) {
                //     $dlvr_cnt = floor($totalAmt / $f_o_fst);
                //     $dlvr_cnt2 = fmod($totalAmt, $f_o_fst);
                //     if ($dlvr_cnt > 0) {
                //         $dlvr += $dlvr_cnt * $fodb;
                //     }
                //     if ($dlvr_cnt2 > 0) {
                //         $dlvr += $fod;
                //     }
                // }

                // if ($CalcFlvrFreeNum > 0) {
                //     $extra_dlvrfee = $CalcFlvrFreeNum * $fodb;
                // }

                // $dlvr += $extra_dlvrfee;
                if ($f_o_fst > 0) {
                    //3680免運
                    if ($totalAmt >= $f_o_fst) {
                        $dlvr = $fodb;
                    } else {
                        $dlvr = $fod;
                    }
                }
            }
        }

        //特殊狀態:只有免運品
        if ($_SESSION[$conf_user]['tmp_total'] == $CalcFlvrFree) {
            $dlvr = $extra_dlvrfee;
        }

        // JsonEnd(array("status" => 1,"data"=>$r,"take_type"=>$getdlvr,"dlvrAmt"=>intval($dlvr),"f"=>$f_o_fst,"dd"=>$dlvr_cnt,"to"=>$totalAmt,"getid"=>$getid));
        $res['dlvr'] = $dlvr;
        $mode = getCartMode();

        $res['cart'] = $_SESSION[$conf_user]["{$mode}_list"];
        $res['mode'] = $getdlvr;
        $res['totalAmt'] = $totalAmt;
        $res['dlvrf'] = $_SESSION[$conf_user]['CalcDlvrFree'];
        $res['dlvrfn'] = $_SESSION[$conf_user]['CalcDlvrFreeNum'];

        return $res;
    } else {
        $db->setQuery($sql);
        $r = $db->loadRowList();
        return $r;
    }
}

function pay_type($getname = null, $getarray = null)
{
    global $db, $conf_user;

    $data = array();
    $data2 = array();
    $sql = "select * from payconf";
    $db->setQuery($sql);
    $r = $db->loadRow();
    if ($r['dlvrPay'] == 1) {

        $data[1] = array("id" => 1, "name" => _EWAYS_PAY_TYPE1);
    }

    $data2[1] = array("id" => 1, "name" => _EWAYS_PAY_TYPE1);

    if ($r['bankPay'] == 1) {

        $data[2] = array("id" => 2, "name" => _EWAYS_PAY_TYPE2);
    }

    $data2[2] = array("id" => 2, "name" => _EWAYS_PAY_TYPE2);

    if ($r['creditallPay'] == 1) {

        $data[3] = array("id" => 3, "name" => _EWAYS_PAY_TYPE3);
    }

    $data2[3] = array("id" => 3, "name" => _EWAYS_PAY_TYPE3);

    if ($r['vanallPay'] == 1) {

        $data[4] = array("id" => 4, "name" => _EWAYS_PAY_TYPE4);
    }

    $data2[4] = array("id" => 4, "name" => _EWAYS_PAY_TYPE4);

    $data2[5] = array("id" => 5, "name" => _EWAYS_PAY_TYPE5);

    if ($r['tspgPayCredit'] == 1) {
        $data[6] = array("id" => 6, "name" => _EWAYS_PAY_TYPE6);
    }
    $data2[6] = array("id" => 6, "name" => _EWAYS_PAY_TYPE6);

    if ($r['ccbPayVATM'] == 1) {
        $data[7] = array("id" => 7, "name" => _EWAYS_PAY_TYPE7);
    }
    $data2[7] = array("id" => 7, "name" => _EWAYS_PAY_TYPE7);

    if ($getname) {
        return $data2[$getname]['name'];
    }

    $pay_type = 0;

    if ($_SESSION[$conf_user]['pay_type']) {
        foreach ($data as $key => $row) {
            if ($row['id'] == $_SESSION[$conf_user]['pay_type']) {
                $pay_type = $row['id'];
            }
        }
    } else {
        $_SESSION[$conf_user]['pay_type'] = $pay_type;
    }

    if ($getarray) {
        return $data;
    } else {
        JsonEnd(array("status" => 1, "data" => $data, "pay_type" => $pay_type));
    }

}

function getdbpagelinkdata($tablename, $fromid = 0)
{
    global $db;
    $dbpageDate = array();
    if ($fromid) {

        $sql = "select * from dbpageLink where fromtable='$tablename' AND fromid = '$fromid'";
        $db->setQuery($sql);
        $r = $db->loadRow();
        if ($r) {
            $dbpageDate['tablename'] = $r['totable'];
            $dbpageDate['databaseid'] = $r['pageid'] ? $r['pageid'] : $r['dirid'];
            $dbpageDate['databasename'] = $r['name'];
        }
    }
    return $dbpageDate;
}

function getProductFormat($id = 0)
{
    global $db, $conf_user;

    $dataArr = array();
    if ($id == 0) {
        return $dataArr;
    }

    $sql_str1 = "";
    $sql_str2 = "";
    if ($_SESSION[$conf_user]['syslang']) {
        $sql_str1 .= " (select `name_" . $_SESSION[$conf_user]['syslang'] . "` from proformat where id=A.format1) as `name1_" . $_SESSION[$conf_user]['syslang'] . "` ,";
        $sql_str1 .= " (select `name_" . $_SESSION[$conf_user]['syslang'] . "` from proformat where id=A.format1_type) as `title1_" . $_SESSION[$conf_user]['syslang'] . "` ,";
        $sql_str2 .= " (select `name_" . $_SESSION[$conf_user]['syslang'] . "` from proformat where id=A.format2) as `name2_" . $_SESSION[$conf_user]['syslang'] . "` ,";
        $sql_str2 .= " (select `name_" . $_SESSION[$conf_user]['syslang'] . "` from proformat where id=A.format2_type) as `title2_" . $_SESSION[$conf_user]['syslang'] . "` ,";
    }

    $sql = " SELECT * FROM ( select
			A.format1,(select name from proformat where id=A.format1) as name1,(select name from proformat where id=A.format1_type) as title1, {$sql_str1}
			A.format2,(select name from proformat where id=A.format2) as name2,(select name from proformat where id=A.format2_type) as title2, {$sql_str2}
			A.instock, A.instockchk ,(select odring from proformat where id=A.format1) as odring1 , B.odring
		  from proinstock A LEFT JOIN proformat B ON A.format2 = B.id
		  where A.pid='$id' ) AS tbl order by odring1, odring ";

    $db->setQuery($sql);
    $r = $db->loadRowList();
    $format1Arr = array();
    $format2Arr = array();
    foreach ($r as $row) {
        $format1 = intval($row['format1']);
        $format2 = intval($row['format2']);
        $name1 = $row['name1'];
        $name2 = $row['name2'];
        $title1 = $row['title1'];
        $title2 = $row['title2'];

        if ($_SESSION[$conf_user]['syslang'] && $row['name1_' . $_SESSION[$conf_user]['syslang']]) {
            $name1 = $row['name1_' . $_SESSION[$conf_user]['syslang']];
        }
        if ($_SESSION[$conf_user]['syslang'] && $row['name2_' . $_SESSION[$conf_user]['syslang']]) {
            $name2 = $row['name2_' . $_SESSION[$conf_user]['syslang']];
        }
        if ($_SESSION[$conf_user]['syslang'] && $row['title1_' . $_SESSION[$conf_user]['syslang']]) {
            $title1 = $row['title1_' . $_SESSION[$conf_user]['syslang']];
        }
        if ($_SESSION[$conf_user]['syslang'] && $row['title2_' . $_SESSION[$conf_user]['syslang']]) {
            $title2 = $row['title2_' . $_SESSION[$conf_user]['syslang']];
        }

        $instock = intval($row['instock']);
        $instockchk = intval($row['instockchk']);

        if ($instock > 0 || true) {

            if ($instockchk == 1 && $instock <= 0) {
                continue;
            }

            $format1Arr[$format1]['id'] = $format1;
            $format1Arr[$format1]['name'] = $name1;
            $format2Arr[$format1][$format2]['id'] = $format2;
            $format2Arr[$format1][$format2]['name'] = $name2;
            $format2Arr[$format1][$format2]['instock'] = $instock;
            $format2Arr[$format1][$format2]['instockchk'] = $instockchk;
            $format2Arr2[$format1][] = array('id' => $format2, 'name' => $name2, 'instock' => $instock, 'instockchk' => $instockchk);
        }

    }

    $tmp = array();
    foreach ($format1Arr as $row) {
        $tmp[] = $row;
    }
    $format1Arr = $tmp;

    $dataArr['format1title'] = $title1;
    $dataArr['format1'] = $format1Arr;
    $dataArr['format2title'] = $title2;
    $dataArr['format2'] = $format2Arr;
    $dataArr['format22'] = $format2Arr2;

    $dataArr['formatonly'] = false;
    if (count($format1Arr) == 1 && count($format2Arr) == 1) {
        if (count($format2Arr[$format1Arr[0]['id']]) == 1) {
            $dataArr['formatonly'] = true;
            $dataArr['format1only'] = $format1Arr[0];

            foreach ($format2Arr[$format1Arr[0]['id']] as $row) {
                $dataArr['format2only'] = $row;
            }
        } else {
            $dataArr['formatonly'] = true;
            $dataArr['format1only'] = $format1Arr[0];
        }
    }

    return $dataArr;
}

function LoginChk()
{
    global $conf_user;
    $uid = intval($_SESSION[$conf_user]['uid']);
    if ($uid == 0) {

        JsonEnd(array("status" => 0, "msg" => _MEMBER_LOGIN_FIRST));
    }
    return $uid;
}

function getUserPermission()
{
    global $conf_user;
    $uid = intval($_SESSION[$conf_user]['uid']);
    $functionsCht = getFieldValue(" SELECT functionsCht FROM adminmanagers WHERE locked ='0' AND id='$uid' ", "functionsCht");

    $funclist = array();
    if (!empty($functionsCht)) {
        $fun_arr = explode("|||||", $functionsCht);
        if (count($fun_arr) > 0) {
            foreach ($fun_arr as $row) {
                if (!empty($row)) {
                    $arr = explode("|||", $row);
                    $funclist[$arr[0]] = array("C" => $arr[1], "U" => $arr[2], "D" => $arr[3], "R" => $arr[4]);
                }
            }
        }
    }
    return $funclist;
}

function userPermissionChk($func)
{
    global $conf_user;

    $arrJson = array();

    if (empty($func)) {
        $arrJson['status'] = "0";
        $arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
        JsonEnd($arrJson);
    }

    if (count($_SESSION[$conf_user]['funclist']) == 0 || true) {
        $_SESSION[$conf_user]['funclist'] = getUserPermission();
    }

    if (!empty($_SESSION[$conf_user]['funclist']) && count($_SESSION[$conf_user]['funclist']) > 0) {
        $funclist = $_SESSION[$conf_user]['funclist'];

        if (count($funclist[$func]) == 0) {
            $arrJson['status'] = "0";
            $arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
            JsonEnd($arrJson);
        }

        if ($funclist[$func]["C"] == "false" && $funclist[$func]["U"] == "false" && $funclist[$func]["D"] == "false" && $funclist[$func]["R"] == "false") {
            $arrJson['status'] = "0";
            $arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
            JsonEnd($arrJson);
        }

        $task = global_get_param($_REQUEST, 'task', null, 0, 1);

        if ($task == 'list') {
            $search = global_get_param($_REQUEST, 'search', null);
            if (!empty($search)) {
                $task = 'search';
            }
        }

        if ($task == 'operate') {
            $action = intval(global_get_param($_REQUEST, 'action', null, 0, 1));
            if ($action == '3') {
                $task = 'operate_D';
            } else {
                $task = 'operate_U';
            }
        }

        $id = intval(global_get_param($_REQUEST, 'id', null, 0, 1));
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

        if ($funclist[$func][$Permission] != 'true') {
            $arrJson['status'] = "0";
            $arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
            JsonEnd($arrJson);
        }
    } else {
        $arrJson['status'] = "0";
        $arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
        JsonEnd($arrJson);
    }

}

function createUpdateSql($tablename, $dataArr)
{
    $updatesql = "INSERT INTO $tablename (";
    $updateval = " VALUES (";
    $updateend = " ON DUPLICATE KEY UPDATE ";
    $i = 0;

    foreach ($dataArr as $key => $val) {
        if (isset($val)) {
            if (++$i > 1) {
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
    return $updatesql . $updateval . $updateend . ";";
}

function getIP()
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}

function getCartMode()
{
    global $conf_user;
    return $_SESSION[$conf_user]["cart_list_mode"] ? $_SESSION[$conf_user]["cart_list_mode"] : "cart";
}

function fieldExist($tablename, $fieldname)
{
    global $db;
    $db->setQuery("DESCRIBE $tablename");
    $collist = $db->loadRowList();
    $returnVal = false;
    foreach ($collist as $row) {
        if ($row['Field'] == $fieldname) {
            $returnVal = true;
            break;
        }
    }
    return $returnVal;

}

function order_instock($ori_status = null, $status = null, $oid = null)
{
    global $db;

    $orderMode = getFieldValue(" SELECT orderMode FROM orders WHERE id = '$oid' ", "orderMode");

    if ($ori_status == '0' && $status == '6' && !empty($oid) && $orderMode == 'addMember') {

        $orderMode = getFieldValue(" SELECT orderMode FROM orders WHERE id = '$oid' ", "orderMode");
        if ($orderMode == 'addMember') {
            $memberid = getFieldValue(" SELECT memberid FROM orders WHERE id = '$oid' ", "memberid");

            $sql = "SELECT * FROM members WHERE id = '$memberid'";
            $db->setQuery($sql);
            $members_arr = $db->loadRow();

            $sql = "SELECT * FROM orders WHERE id = '$oid'";
            $db->setQuery($sql);
            $orders_arr = $db->loadRow();

            $sql = "SELECT * FROM orderdtl WHERE oid='$oid'";
            $db->setQuery($sql);
            $orderdtl_arr = $db->loadRow();

            $file = 'member_order.txt';
            $current = file_get_contents($file);
            $current .= implode("，", $members_arr) . "\n";
            $current .= implode("，", $orders_arr) . "\n";
            file_put_contents($file, $current);

            $sql = " INSERT INTO deletelog (mambers, orders, orderdtl, uid, uname)
				VALUES ('" . implode("，", $members_arr) . "', '" . implode("，", $orders_arr) . "', '" . implode("，", $orderdtl_arr) . "','0','系統執行'); ";
            $db->setQuery($sql);
            $db->query();

            $sql = "BEGIN;";

            $sql .= " DELETE FROM orderBundleDetail WHERE exists(select 1 from orderBundle A WHERE A.orderId='$oid' AND A.id=orderBundleId) ; ";
            $sql .= " DELETE FROM orderBundle WHERE  orderId='$oid' ; ";
            $sql .= " DELETE FROM orderdtl WHERE  oid='$oid' ; ";

            $sql .= " DELETE FROM orders WHERE  id='$oid' ; ";

            $sql .= " DELETE FROM members WHERE  id='$memberid' ; ";

            $sql .= "COMMIT;";

            $db->setQuery($sql);
            $r = $db->query_batch();

            return true;
        }
    } else if (($ori_status == '0' || !empty($ori_status)) && ($status == '0' || !empty($status)) && !empty($oid)) {
        $instock_chk = "";
        if ($ori_status != "8" && $ori_status != "6" && ($status == "8" || $status == "6")) {
            $instock_chk = " + ";
        } elseif (($ori_status == "8" || $ori_status == "6") && $status != "8" && $status != "6") {
            $instock_chk = " - ";
        }

        if (!empty($instock_chk)) {
            $sql = " SELECT * FROM orderdtl WHERE oid = '$oid'";
            $db->setQuery($sql);
            $list = $db->loadRowList();
            if (count($list) > 0) {

                $sql = "BEGIN;";

                foreach ($list as $row) {
                    $pid = $row['pid'];
                    $quantity = $row['quantity'];
                    $format1 = $row['format1'];
                    $format2 = $row['format2'];
                    $sql .= "update proinstock set instock=instock $instock_chk '$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
                }

                $info_sql = " SELECT * FROM orders WHERE id = '$oid'";
                $db->setQuery($info_sql);
                $info = $db->loadRow();

                if ($info['orderMode'] == 'bonus') {
                    $sql .= "update members set bonus=bonus+'{$info['bonusAmt']}' where id='{$info['memberid']}';";
                }

                $sql .= "COMMIT;";

                $db->setQuery($sql);
                $r = $db->query_batch();
            }

            $sql = "select * from orderBundleDetail where exists(select 1 from orderBundle A WHERE A.orderId='$oid' AND A.id=orderBundleId)";
            $db->setQuery($sql);
            $list = $db->loadRowList();
            if ($list && count($list) > 0) {

                $sql = "BEGIN;";
                foreach ($list as $value) {
                    $pid = $value['productId'];
                    $quantity = 1;
                    $format1 = $value['productFormat1'];
                    $format2 = $value['productFormat2'];
                    $sql .= "update proinstock set instock=instock $instock_chk '$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
                }
                $sql .= "COMMIT;";

                $db->setQuery($sql);
                $r = $db->query_batch();
            }

        }

        return true;
    } else {
        return false;
    }

}

function cartProductClac($active_list = array(), $cart_list = array(), $activeExtraList = array(), $activeBundleCart = null)
{
    global $db, $conf_user;

    $uid = intval($_SESSION[$conf_user]['uid']);

    $salesChk = "0";
    if (!empty($uid)) {
        $salesChk = getFieldValue("select * from members where id='$uid'", "salesChk");
    }
    $m_discount_rate = $_SESSION[$conf_user]['m_discount_rate'];

    $pvbvratio = (float) getFieldValue("SELECT pvbvratio FROM siteinfo", "pvbvratio");
    $active_disPro_list = array();
    $active_actPro_list = array();
    $active_usePro_list = array();

    $index_pro_list = array();
    $index2_pro_list = array();
    $index3_pro_list = array();

    $index_88 = 0;

    if (count($active_list) > 0) {
        foreach ($active_list as $row) {
            if (count($row['dispro']) > 0) {
                $info = array();
                $info["name"] = $row['name'];
                $info["activePlanid"] = $row['act']['activePlanid'];

                if ($info["activePlanid"] == "1") {
                    $info["var01"] = intval($row['act']['var01']);
                    $info["var02"] = intval($row['act']['var02']);

                    foreach ($row['dispro'] as $key2 => $row2) {
                        foreach ($cart_list as $key3 => $row3) {
                            if ($row3['id'] == $row2) {
                                for ($i = 1; $i <= intval($row3['num']); $i++) {
                                    $index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
                                    $active_disPro_list[$row2 . "|" . $index] = $info;
                                    $index_pro_list[$row2] = $index;
                                }
                            }
                        }
                    }
                } else if ($info["activePlanid"] == "2") {
                    $info["var01"] = intval($row['act']['var01']);
                    $info["var02"] = intval($row['act']['var02']);

                    foreach ($row['dispro'] as $key2 => $row2) {
                        foreach ($cart_list as $key3 => $row3) {
                            if ($row3['id'] == $row2) {
                                for ($i = 1; $i <= intval($row3['num']); $i++) {
                                    $index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
                                    $active_disPro_list[$row2 . "|" . $index] = $info;
                                    $index_pro_list[$row2] = $index;
                                }
                            }
                        }
                    }
                } else if ($info["activePlanid"] == "3") {
                    $info["var01"] = intval($row['act']['var01']);
                    $info["var02"] = intval($row['act']['var02']);
                    $info["pv"] = intval($row['act']['pv']);
                    $info["bv"] = intval($row['act']['bv']);

                    $index_act = 1;
                    $tmp_sum = 0;
                    $tmp_sum_pv = 0;
                    $tmp_sum_bv = 0;
                    foreach ($row['dispro'] as $key2 => $row2) {
                        $index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;

                        if ($index_act % $info["var01"] == 0) {
                            $info["amt"] = $info["var02"] - $tmp_sum;
                            $info["amt_pv"] = $info["pv"] - $tmp_sum_pv;
                            $info["amt_bv"] = $info["bv"] - $tmp_sum_bv;
                            $tmp_sum = 0;
                            $tmp_sum_pv = 0;
                            $tmp_sum_bv = 0;
                        } else {
                            $info["amt"] = round($info["var02"] / $info["var01"]);
                            $info["amt_pv"] = round($info["pv"] / $info["var01"]);
                            $info["amt_bv"] = round($info["bv"] / $info["var01"]);
                            $tmp_sum += $info["amt"];
                            $tmp_sum_pv += $info["amt_pv"];
                            $tmp_sum_bv += $info["amt_bv"];
                        }

                        $active_disPro_list[$row2 . "|" . $index] = $info;
                        $index_pro_list[$row2] = $index;
                        $index_act++;
                    }
                } else if ($info["activePlanid"] == "12") {
                    $info["var01"] = intval($row['act']['var01']);
                    $info["var02"] = intval($row['act']['var02']);

                    foreach ($row['dispro'] as $key2 => $row2) {
                        $index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;

                        $info["amt_pv"] = 0;
                        $info["amt_bv"] = 0;

                        $active_disPro_list[$row2 . "|" . $index] = $info;
                        $index_pro_list[$row2] = $index;
                    }
                } else if ($info["activePlanid"] == "13") {
                    $info["var02"] = intval($row['act']['pv']);

                    foreach ($row['dispro'] as $key2 => $row2) {
                        $index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;

                        $active_disPro_list[$row2 . "|" . $index] = $info;
                        $index_pro_list[$row2] = $index;
                    }
                }
            }

            if (count($row['actproArr'])) {
                foreach ($row['actproArr'] as $row2) {
                    $index = (!empty($index2_pro_list[$row2])) ? (intval($index2_pro_list[$row2]) + 1) : 1;

                    $active_actPro_list[$row2 . "|" . $index] = $row['act']['activePlanid'];
                    $index2_pro_list[$row2] = $index;
                }
            }

            if (count($row['usepro'])) {
                foreach ($row['usepro'] as $row2) {
                    $index = (!empty($index3_pro_list[$row2])) ? (intval($index3_pro_list[$row2]) + 1) : 1;

                    $active_usePro_list[$row2 . "|" . $index] = array("activePlanid" => $row['act']['activePlanid'], "name" => $row['name']);
                    $index3_pro_list[$row2] = $index;
                }
            }

        }
    }

    $index_cart_pro_list = array();

    if (count($cart_list) > 0) {
        foreach ($cart_list as $key => $row) {

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
                foreach ($activeExtraList as $activeExtraKey => $activeExtra) {
                    foreach ($activeExtra['productMix'] as $productMixKey => $productMix) {
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

            for ($i = 1; $i <= intval($row['num']); $i++) {
                if (empty($index_cart_pro_list[$row['id']])) {
                    $index = 1;
                } else {
                    $index = $index_cart_pro_list[$row['id']] + 1;
                }
                $index_cart_pro_list[$row['id']] = $index;

                if ($i <= $activeExtraUseProductCount) {
                    $prodtl['amt'][] = intval($activeExtraAmountList[$i - 1]);
                    $prodtl['amt_pv'][] = intval($activeExtraPVList[$i - 1]);
                    $prodtl['amt_bv'][] = intval($activeExtraPVList[$i - 1] * $pvbvratio);
                    $prodtl['pair'][] = "N";
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
                } else if (count($active_disPro_list[$row['id'] . "|" . $index]) > 0) {
                    $tmp_arr = $active_disPro_list[$row['id'] . "|" . $index];

                    if ($tmp_arr['activePlanid'] == "1") {
                        $amt = round($row["siteAmt"] - $tmp_arr['var02']);
                        $pv = $row["pv"] * (($row['siteAmt'] - $tmp_arr['var02']) / $row['siteAmt']);
                    } else if ($tmp_arr['activePlanid'] == "2") {
                        $amt = round($row["siteAmt"] * $tmp_arr['var02'] * 0.01);
                        $pv = round($row["pv"] * $tmp_arr['var02'] * 0.01);
                    } else if ($tmp_arr['activePlanid'] == "3") {
                        $amt = $tmp_arr["amt"];
                        $pv = $tmp_arr["amt_pv"];
                    } else if ($tmp_arr['activePlanid'] == "12") {
                        $amt = round($row["siteAmt"] * ($tmp_arr["var02"] * 0.01));
                        $pv = $tmp_arr["amt_pv"];
                    } else if ($tmp_arr['activePlanid'] == "13") {
                        $amt = round($row["siteAmt"]);
                        $pv = round($row["pv"] * $tmp_arr['var02'] * 0.01);
                    }

                    $prodtl['amt'][] = $amt;
                    $prodtl['amt_pv'][] = $pv;
                    $prodtl['amt_bv'][] = $pv * $pvbvratio;

                    $prodtl['pair'][] = ($active_actPro_list[$row['id'] . "|" . $index] == "12") ? "Y" : "N";
                    $prodtl['use'][] = ($active_usePro_list[$row['id'] . "|" . $index]['activePlanid'] == "12") ? "Y" : "N";

                    if ($active_usePro_list[$row['id'] . "|" . $index]['activePlanid'] == "12" && $tmp_arr['activePlanid'] == "12") {
                        $prodtl_use_act = $tmp_arr['name'];
                    }

                    $prodtl_amt_sum += ($amt);
                    $prodtl_amt_pv += ($pv);
                    $prodtl_amt_bv += ($pv * $pvbvratio);

                    if (!empty($prodtl_act)) {
                        $prodtl_act .= ",";
                    }
                    $prodtl_act .= $tmp_arr['name'];

                } else {
                    $prodtl['amt'][] = $row["siteAmt"];
                    $prodtl['amt_pv'][] = $row["pv"];
                    $prodtl['amt_bv'][] = $row["bv"];

                    $prodtl['pair'][] = ($active_actPro_list[$row['id'] . "|" . $index] == "12") ? "Y" : "N";
                    $prodtl['use'][] = ($active_usePro_list[$row['id'] . "|" . $index]['activePlanid'] == "12") ? "Y" : "N";

                    if ($active_usePro_list[$row['id'] . "|" . $index]['activePlanid'] == "12") {
                        $prodtl_use_act = $active_usePro_list[$row['id'] . "|" . $index]['name'];
                    }

                    $tmp_arr = $active_usePro_list[$row['id'] . "|" . $index];
                    if (!empty($prodtl_act)) {
                        $prodtl_act .= ",";
                    }
                    $prodtl_act .= $tmp_arr['name'];

                    $prodtl_amt_sum += ($row["siteAmt"]);
                    $prodtl_amt_pv = bcadd($prodtl_amt_pv, $row["pv"], 2);
                    $prodtl_amt_discount = bcmul($prodtl_amt_pv, $m_discount_rate, 2);
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
    global $db, $conf_user;

    $uid = intval($_SESSION[$conf_user]['uid']);

    $chkDate = getFieldValue(" SELECT CodeValue FROM syscode WHERE CodeKind = 'orderChkDate' ", "CodeValue");

    if (strtotime(date("Y-m-d 0:0:0")) > strtotime($chkDate . " 0:0:0")) {
        $day3Str = date("Y-m-d", strtotime("-5 days"));
        $day5Str = date("Y-m-d", strtotime("-5 days"));

        $sql = " SELECT * FROM orders WHERE status='0' AND ( ( buyDate <= '$day3Str' AND orderMode = 'addMember') OR ( buyDate <= '$day5Str' AND orderMode <> 'addMember')) ";
        $db->setQuery($sql);
        $list = $db->loadRowList();

        if (count($list) > 0) {
            foreach ($list as $row) {
                $id = $row['id'];

                $sql = "update orders set status=6,mtime='" . date("Y-m-d H:i:s") . "' where id='$id'";

                $db->setQuery($sql);
                $db->query();

                $now = date('Y-m-d H:i:s');
                $today = date('Y-m-d');

                $sql = "insert into orderlog (oid,cdate,status,ctime,mtime,muser) values
				('$id','$today','6','$now','$now','$uid');";
                $db->setQuery($sql);
                $db->query();

                order_instock("0", "6", $id);

            }
        }

        $sql = "update syscode set CodeValue='" . date("Y-m-d") . "' WHERE CodeKind = 'orderChkDate' ";
        $db->setQuery($sql);
        $db->query();

    }

    return true;
}

// function sendMailToMemberBySignupSuccess($uid)
// {
//     global $db,$conf_php,$conf_upload,$conf_user,$HTTP_X_FORWARDED_PROTO;

//     $imgUrl = $HTTP_X_FORWARDED_PROTO.'://'.$_SERVER['HTTP_HOST'];

//     $sql = "select * from siteinfo where sysid ='' ";

//     $db->setQuery( $sql );
//     $siteinfo_arr = $db->loadRow();

//     $from = $siteinfo_arr['email'];
//     $fromname = $siteinfo_arr['name'];
//     if($_SESSION[$conf_user]['syslang'])
//     {
//         $fromname = $siteinfo_arr['name_'.$_SESSION[$conf_user]['syslang']];
//     }

//     $loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$uid' ","loginid");
//     $passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ","sid");
//     $name = getFieldValue(" SELECT name FROM members WHERE id = '$uid' ","name");
//     $email = getFieldValue(" SELECT email FROM members WHERE id = '$uid' ","email");
//     $ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$uid' ","ERPID");

//     require_once ($conf_php.'includes/Barcode39.php');

//     $bc = new Barcode39($ERPID);
//     $bc->barcode_height = 80;
//     $bc->barcode_text_size = 5;
//     $bc->barcode_bar_thick = 4.5;
//     $bc->barcode_bar_thin = 1.5;

//     $bc->draw("../".$conf_upload."Barcode39/barcode".$uid.".gif");

//     $sendto = array(array("email"=>$email,"name"=>$name));

//     $subject = $fromname." - "._EWAYS_ESIGNUO_MSG1." (".date("Y-m-d H:i:s").")";
//     $body = "
//     <html>
//     <head>
//         <meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
//        <title>$fromname "._EWAYS_ESIGNUO_MSG1."</title>

//     </head>
//     <body style=\"margin:0;padding:0;\">
//         <div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
//             <p style=\"line-height:180%;\">
//                 "._EWAYS_ESIGNUO_MSG2."<b style=\"color:#0d924a\">GoodARCH<b>
//             </p>

//             <table border=\"1\">
//                 <tr>
//                     <td>
//                         "._EWAYS_ESIGNUO_MSG3."<b style=\"color:#0d924a\">$loginId</b><br />
//                         "._EWAYS_ESIGNUO_MSG4."<b style=\"color:#0d924a\">$passwd</b><br />
//                         "._EWAYS_ESIGNUO_MSG5."<b style=\"color:#0d924a\">$ERPID</b>
//                     </td>
//                 </tr>
//                 <tr>
//                     <td align=\"center\">
//                         <img src=\"{$imgUrl}/upload/image005.jpg\" /> <br />
//                         <img src=\"{$imgUrl}/upload/Barcode39/barcode".$uid.".gif\" /> <br />
//                         "._EWAYS_ESIGNUO_MSG6."
//                     </td>
//                 </tr>
//                 <tr>
//                     <td>
//                         <b style=\"color:#ff0000\">"._EWAYS_ESIGNUO_MSG7."</b>"._EWAYS_ESIGNUO_MSG8."
//                     </td>
//                 </tr>
//             </table>

//         </div>
//     </body>
//     </html>
//     ";

//     $rs = global_send_mail($from,$fromname,$sendto,$subject,$body);

//     return true;

// }

function sendMailToMemberBySignupSuccess($uid, $getData = false, $type = null)
{
    global $db, $conf_php, $conf_upload, $conf_user, $HTTP_X_FORWARDED_PROTO, $globalConf_signup_ver2020;

    $imgUrl = $HTTP_X_FORWARDED_PROTO . '://' . $_SERVER['HTTP_HOST'];

    $sql = "select * from siteinfo where sysid ='' ";

    $db->setQuery($sql);
    $siteinfo_arr = $db->loadRow();

    $from = $siteinfo_arr['email'];
    $fromname = $siteinfo_arr['name'];
    if ($_SESSION[$conf_user]['syslang']) {
        $fromname = $siteinfo_arr['name_' . $_SESSION[$conf_user]['syslang']];
    }

    $loginId = getFieldValue(" SELECT loginid FROM members WHERE id = '$uid' ", "loginid");
    if (empty($loginId)) {
        $loginId = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ", "sid");
    }
    $passwd = getFieldValue(" SELECT sid FROM members WHERE id = '$uid' ", "sid");
    $name = getFieldValue(" SELECT name FROM members WHERE id = '$uid' ", "name");
    $email = getFieldValue(" SELECT email FROM members WHERE id = '$uid' ", "email");
    $ERPID = getFieldValue(" SELECT ERPID FROM members WHERE id = '$uid' ", "ERPID");

    $tmpStr = "登入密碼：<b style=\"color:#0d924a\">$passwd</b><br />";
    if ($globalConf_signup_ver2020) {

        $loginId = substr($loginId, 0, 2) . "*****" . substr($loginId, -3);
        $signupMode = getFieldValue(" SELECT pvgeLevel FROM members WHERE id = '$uid' ", "pvgeLevel");

        if ($signupMode == "SMS") {
            $mobile = getFieldValue(" SELECT mobile FROM members WHERE id = '$uid' ", "mobile");
            $tmpStr = "手機號碼：<b style=\"color:#0d924a\">$mobile</b><br />";
        } else if ($signupMode == "MAIL") {
            $tmpStr = "E-Mail：<b style=\"color:#0d924a\">$email</b><br />";
        }
    }

    $title = _EMAIL_MEMBER_1;
    //JIE ADD
    $fromname = _EMAIL_msg24;
    $card_word = '';
    if ($type == "sign20_signup") {
        $title = _EMAIL_MEMBER;
    } else {
        $card_word = _EMAIL_msg22;
    }

    require_once $conf_php . 'includes/Barcode39.php';

    $bc = new Barcode39($ERPID);
    $bc->barcode_height = 80;
    $bc->barcode_text_size = 5;
    $bc->barcode_bar_thick = 4.5;
    $bc->barcode_bar_thin = 1.5;

    $bc->draw("../" . $conf_upload . "Barcode39/barcode" . $uid . ".gif");

    $sendto = array(array("email" => $email, "name" => $name));

    $bodyStr = "";
    $htmlStr = "";

    $subject = $fromname . " - $title (" . date("Y-m-d H:i:s") . ")";

    if ($type == "sign20_signup") {
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
				" . _EWAYS_ESIGNUO_MSG2 . "<b style=\"color:#0d924a\">" . _EMAIL_msg9 . "</b>
			</p>

			<table border=\"1\" width=\"75%\">
				<tr>
					<td>
						" . _COMMON_PARAM_LOGINID . "：<b style=\"color:#0d924a\">$loginId</b><br />
						{$tmpStr}
						" . _EMAIL_msg23 . "：<b style=\"color:#0d924a\">$ERPID</b>
					</td>
				</tr>
				<tr style='padding-bottom:10px;'>
					<td align='center' style='padding: 10px 0px;'>
						" . _EMAIL_msg10 . "<br /><span style='color:#870000'>" . _EMAIL_msg11 . "</span><br />" . _EMAIL_msg25 . "<br>
						<span style='color:#FFC42C'>360 TEST</span>" . _EMAIL_msg14 . "<br>
						<br /><div style='padding-bottom:7px'>" . _EMAIL_msg15 . "</div>

					</td>
				</tr>
				<tr>
					<td align=\"center\">
						<img style='width:80%;max-width:150px;padding:10px 0px' src=\"{$imgUrl}/upload/goodarch-logo-m.png\" /> <br />
						<img style='width:80vmin;max-width:500px' src=\"{$imgUrl}/upload/Barcode39/barcode" . $uid . ".gif\" /> <br />
						";
    } else {
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
				" . _EWAYS_ESIGNUO_MSG2 . "<b style=\"color:#0d924a\">" . _EMAIL_msg9 . "</b>
			</p>

			<table border=\"1\" width=\"75%\">
				<tr>
					<td>
						" . _COMMON_PARAM_LOGINID . "：<b style=\"color:#0d924a\">$loginId</b><br />
						{$tmpStr}
						" . _EMAIL_msg23 . "：<b style=\"color:#0d924a\">$ERPID</b>
					</td>
				</tr>
				<tr style='padding-bottom:10px;'>
					<td align='center' style='padding: 10px 0px;'>
						" . _EMAIL_msg10 . "<br /><span style='color:#870000'>" . _EMAIL_msg11 . "</span><br>" . _EMAIL_msg25 . "



					</td>
				</tr>
				<tr>
					<td align=\"center\">
						<img style='width:80%;max-width:150px;padding:10px 0px' src=\"{$imgUrl}/upload/goodarch-logo-m.png\" /> <br />
						<img style='width:80vmin;max-width:500px' src=\"{$imgUrl}/upload/Barcode39/barcode" . $uid . ".gif\" /> <br />
						";
    }

    if ($_SESSION["tmpData"]["payD"] == 1) {
        $body .= $bodyStr . $card_word . "
						</td>
					</tr>
					<tr>
						<td>
							<b style=\"color:#ff0000\">" . _EMAIL_msg12 . "
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
    } else {
        $body .= $bodyStr . $card_word . "

						</td>
					</tr>
					<tr>
						<td>
							<b style=\"color:#ff0000\">" . _EMAIL_msg12 . "
						</td>
					</tr>
				</table>

			</div>
		</body>
		</html>
		";
    }

    if ($_SESSION["tmpData"]["payD"] == 1) {
        $htmlStr = $bodyStr . $card_word . "
			</td>
		</tr>


		</table>
		<div style='margin-top:20px'>
		<span>提醒您，您的入會程序尚未完成，請您儘速於訂單有效期間內完成付款，以免損害您的相關權益。</span>
		</div>";
    } else {
        $htmlStr = $bodyStr . $card_word . "
			</td>

		</tr>

		</table>";
    }

    $_SESSION["tmpData"]["bodyStr"] = $htmlStr;

    if (!$getData) {
        $rs = global_send_mail($from, $fromname, $sendto, $subject, $body);
    }

    return true;

}

function getLanguageList($txt = null, $all = null)
{
    global $db, $conf_php, $conf_upload;

    $where_str = "";
    switch ($txt) {
        case "text":
            $where_str .= " AND textPublish = 1";
            if (empty($all)) {
                $where_str .= " AND textChk = '1'";
            }
            break;
        case "money":
            $where_str .= ' AND moneyPublish = 1';
            if (empty($all)) {
                $where_str .= " AND moneyChk = '1'";
            }
            break;
        default:
            break;
    }
    $sql = "SELECT * FROM langConf WHERE alive = 1 $where_str ORDER BY odring";

    $db->setQuery($sql);
    $langConf_arr = $db->loadRowList();

    return $langConf_arr;
}

function tomlm_test($oid)
{
    global $db, $db2;
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
function curlRequest($type = "POST", $api, $param)
{

    $process = curl_init($api);
    $curl = curl_init();
    if ($type == "GET") {

        curl_setopt($process, CURLOPT_URL, $api . "?" . http_build_query($param));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    } else if ($type == "POST") {
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    }
    $return = curl_exec($process);
    curl_close($process);
    return $return;
}

/**
 * 購物車session
 */
function getShopCart()
{
    global $conf_user;
    $shopCart = array(
        'activeBundle' => array(),
    );
    if (isset($_SESSION[$conf_user]['shopCart'])) {
        foreach ($_SESSION[$conf_user]['shopCart'] as $_i => $_v) {
            $shopCart[$_i] = $_v;
        }
    } else {
        $_SESSION[$conf_user]['shopCart'] = $shopCart;
    }
    return $shopCart;
}

/**
 * 取購物車項目數量
 * @param    $type    類型(''=>全部,'activeBundle'=>活動分組)
 */
function getShopCartItemCount($type = "")
{
    $shopCart = getShopCart();
    $count = 0;
    if (!empty($shopCart)) {
        if ($type == '') {
            foreach ($shopCart as $_type => $_data) {
                $count += count($shopCart[$_type]);
            }
        } else {
            if (is_array($type)) {
                foreach ($shopCart as $_type => $_data) {
                    if (isset($shopCart[$_type]) && in_array($_type, $type)) {
                        $count += count($shopCart[$_type]);
                    }
                }
            } else {
                if (isset($shopCart[$type])) {
                    $count += count($shopCart[$type]);
                }
            }
        }
    }
    return $count;
}

/**
 * 購物車異動
 *
 * @param    $type    類型(activeBundle=>活動分組)
 * @param    $act    增加(add)/減少(del)/更新(update)/清空(clear)
 * @param    $data    異動資料
 *
 */
function modShopCart($type = "", $act = "clear", $data = array())
{
    global $conf_user;
    if ($type == "") {
        unset($_SESSION[$conf_user]['shopCart']);
    }
    $shopCart = getShopCart();
    if (isset($shopCart[$type])) {
        if (empty($data)) {
            $act = "clear";
        }
        switch ($act) {
            case "add":
                $data['uuid'] = uuid();
                $shopCart[$type][$data['uuid']] = $data;
                break;
            case "update":
                //有uuid
                if (isset($data['uuid'])) {
                    $shopCart[$type][$data['uuid']] = $data;
                    //無uuid
                } else {
                    $hasData = false;
                    foreach ($shopCart[$type] as $_uuid => $_data) {
                        if ($_data == $data) {
                            //增加新的
                            modShopCart($type, 'add', $_data);
                            //刪除舊的
                            unset($shopCart[$type][$_uuid]);
                            $hasData = true;
                        }
                    }
                    if (!$hasData) {
                        modShopCart($type, 'add', $data);
                    }
                }
                break;
            case "del":
                unset($shopCart[$type][$data['uuid']]);
                break;
            case "clear":
                $shopCart[$type] = array();
                break;
        }
    }

    $_SESSION[$conf_user]['shopCart'] = $shopCart;
}

/**
 * 取新的購物車資料格式
 * @param $typeArr    類型陣列['activeBundle']
 */
function getShopCartData($activeBundleIdArr = array(), $typeArr = ['activeBundle'])
{
    $shopCart = array(
        //session資料
        'session' => array(),
        //分組活動相關資料
        'activeBundle' => array(),
    );
    $shopCart['session'] = getShopCart();
    //分組活動相關
    if (in_array('activeBundle', $typeArr)) {
        //活動分組
        $activeBundleSession = isset($shopCart['session']['activeBundle']) && !empty($shopCart['session']['activeBundle']) ? $shopCart['session']['activeBundle'] : false;
        if ($activeBundleSession) {
            foreach ($activeBundleSession as $_uuid => $_activeBundle) {
                if (!in_array($_activeBundle['id'], $activeBundleIdArr)) {
                    $activeBundleIdArr[] = $_activeBundle['id'];
                }
            }
        }

        if (!empty($activeBundleIdArr)) {
            $shopCart['activeBundle'] = getActiveBundle($activeBundleIdArr, '1');
        }
    }
    return $shopCart;
}

/**
 * 新的購物車相關檢查
 * @param $checkType    需檢查的類型(activeBundle)
 * @param $checkData    需檢查的資料
 * @param $typeArr        類型陣列['activeBundle']
 */
function checkShopCart($checkType = '', $checkData = array(), $typeArr = ['activeBundle'])
{
    global $db;

    $returnData = array(
        'error' => 0,
        'data' => array(),
        'msg' => '',
        'update' => array(),
    );
    //是否為session檢查
    $isSession = empty($checkData) ? true : false;
    //活動id陣列
    $activeBundleIdArr = array();
    if (!empty($checkData)) {
        $activeBundleIdArr[] = $checkData['id'];
    }

    $shopCart = getShopCartData($activeBundleIdArr, $typeArr);
    $checkDataArr = array();
    if ($isSession) {
        //沒指定就是全類型全拿
        if ($checkType == '') {
            foreach ($typeArr as $_type) {
                $data = $shopCart['session'][$_type];
                if (!empty($data)) {
                    foreach ($data as $_uuid => $_data) {
                        $checkDataArr[$_type][$_uuid] = $_data;
                    }
                }
            }
        } else {
            $data = $shopCart['session'][$checkType];
            if (!empty($data)) {
                foreach ($data as $_uuid => $_data) {
                    $checkDataArr[$checkType][$_uuid] = $_data;
                }
            }
        }
    } else {
        //如果shopCart無資料
        if (empty($shopCart['session'][$checkType])) {
            switch ($checkType) {
                //活動分組
                case "activeBundle":
                    $shopCart[$checkType] = getActiveBundle($checkData['id'], '1');
                    break;
            }
        }
        $checkDataArr[$checkType][] = $checkData;
    }
    //活動分組檢查
    if (isset($checkDataArr['activeBundle']) && !empty($checkDataArr['activeBundle'])) {
        $returnData['data'] = $checkDataArr['activeBundle'];
        //活動分組資料
        $activeBundleData = $shopCart['activeBundle'];
        //活動分組session
        $activeBundleSession = isset($shopCart['session']['activeBundle']) ? $shopCart['session']['activeBundle'] : array();
        //庫存
        $proInstock = $activeBundleData['proinstocks'];
        //規格
        $specs = $activeBundleData['specs'];
        //需要檢查庫存的規格
        $proInstockCheck = array();
        //需要檢查限購的活動
        $limitActiveBundle = array();

        foreach ($checkDataArr['activeBundle'] as $_uuid => $_checkData) {
            //已選擇商品
            $selectProducts = $_checkData['params']['products'];

            if (!empty($selectProducts)) {
                //活動分組
                $activeBundle = $activeBundleData['actives'][$_checkData['id']];
                //各分組限制商品數量
                $bundleProCount = array();
                foreach ($selectProducts as $_index => $_products) {
                    //unset前端產生的$$hashKey
                    unset($shopCartSession['params']['products'][$_index]['$$hashKey']);

                    if (!isset($bundleProCount[$_products['abdId']])) {
                        $bundleProCount[$_products['abdId']] = array();
                    }
                    $bundleProCount[$_products['abdId']][] = $_index;

                    //分組商品
                    $_bundleProducts = $activeBundleData['bundles'][$_products['abdId']]['products'];
                    //測試商品不在分組內
                    //$_products['id'] = -1;

                    //檢查商品是否在分組裡面
                    if (!in_array($_products['id'], $_bundleProducts)) {
                        $returnData['error'] = '1';
                        $returnData['data'][$_uuid]['params']['products'][$_index]['errorStatus'] = '1';
                        //任選要清空商品(session不用改資料)
                        if ($activeBundle['bundleType'] == '1' && !$isSession) {
                            $returnData['data'][$_uuid]['params']['products'][$_index]['id'] = '';
                        }
                        //非單一顏色清空
                        if (count($specs['structure'][$_products['id']]) > 1 && !$isSession) {
                            $returnData['data'][$_uuid]['params']['products'][$_index]['color'] = '';
                        }
                        $returnData['data'][$_uuid]['params']['products'][$_index]['proInstockId'] = '';
                        if (!$isSession) {
                            $returnData['update']['product'] = $activeBundleData['products'];
                        }
                        $returnData['msg'] = "商品錯誤({$_products['abdId']}:{$_products['id']})";
                        return $returnData;
                    }

                    //檢查庫存
                    //測試無商品規格
                    //unset($proInstock[$_products['proInstockId']]);
                    if (!isset($proInstock[$_products['proInstockId']])) {
                        $returnData['error'] = '1';
                        $returnData['data'][$_uuid]['params']['products'][$_index]['errorStatus'] = '1';
                        if (!$isSession) {
                            $returnData['data'][$_uuid]['params']['products'][$_index]['proInstockId'] = '';
                            $returnData['update']['productFormat'] = $specs;
                        }
                        $_proInstockIdSql = "SELECT id FROM proinstock WHERE id = '{$_products['proInstockId']}'";
                        $db->setQuery($_proInstockIdSql);
                        $_proInstockIdData = $db->loadRow();
                        if (!empty($_proInstockIdData)) {
                            $returnData['msg'] = "商品規格庫存不足，請選擇其他規格";
                        } else {
                            $returnData['msg'] = "查無商品規格，請選擇其他規格";
                        }
                        return $returnData;
                    } else {
                        //需要檢查庫存的規格統計寫入$proInstock中
                        if ($proInstock[$_products['proInstockId']]['instockchk'] == '1') {
                            if (!isset($proInstockCheck[$_products['proInstockId']])) {
                                $proInstockCheck[$_products['proInstockId']] = array(
                                    'index' => array(),
                                    'count' => 0,
                                );
                            }
                            $proInstockCheck[$_products['proInstockId']]['index'][$_uuid][] = $_index;
                            $proInstockCheck[$_products['proInstockId']]['count'] += $_checkData['params']['quantity'];
                        }
                    }
                }

                //檢查商品數量
                if (empty($bundleProCount)) {
                    $returnData['error'] = '1';
                    $returnData['msg'] = "商品數量錯誤";
                    return $returnData;
                } else {
                    foreach ($bundleProCount as $_abdId => $_paramIndex) {
                        //該分組相關資料
                        $_thisBundle = $activeBundleData['bundles'][$_abdId];
                        //分組數量
                        $_bundleQuantity = $_thisBundle['quantity'];
                        //類型是固定需乘設定商品數量
                        if ($activeBundle['bundleType'] == '0') {
                            $_bundleQuantity *= count($_thisBundle['products']);
                        }

                        $proCount = count($_paramIndex);
                        //測試數量錯誤用
                        //$proCount++;
                        if (!($proCount == $_bundleQuantity)) {
                            foreach ($_paramIndex as $_i) {
                                $returnData['data'][$_uuid]['params']['products'][$_index]['errorStatus'] = '1';
                            }
                            $returnData['error'] = '1';
                            $returnData['msg'] = "商品數量錯誤($proCount/$_bundleQuantity)";
                            return $returnData;
                        }
                    }
                }

                //限購數量
                $limitCount = $activeBundle['limitCount'] > 0 ? $activeBundle['limitCount'] : 0;

                //測試限購用
                //$limitCount = 1;

                //限購數量檢查(有設定才加入檢查資料)
                if ($limitCount > 0) {
                    if (!isset($limitActiveBundle[$activeBundle['id']])) {
                        $limitActiveBundle[$activeBundle['id']] = array(
                            'limit' => $limitCount,
                            'count' => 0,
                        );
                    }
                    $limitActiveBundle[$activeBundle['id']]['count'] += $_checkData['params']['quantity'];
                }
            }
        }

        //購物車限購
        $shopCartCountArr = array();
        //購物車內容統計
        foreach ($activeBundleSession as $_uuid => $_sessionData) {
            if (!isset($shopCartCountArr[$_sessionData['id']])) {
                $shopCartCountArr[$_sessionData['id']] = 0;
            }
            //限購統計
            $shopCartCountArr[$_sessionData['id']] += $_sessionData['params']['quantity'];

            //如果不是session需要統計購物車中的規格
            if (!$isSession) {
                $_sessionPro = $_sessionData['params']['products'];
                if (!empty($_sessionPro)) {
                    foreach ($_sessionPro as $_product) {
                        if (isset($proInstockCheck[$_product['proInstockId']])) {
                            $proInstockCheck[$_product['proInstockId']]['count'] += $_product['quantity'];
                        }
                    }
                }
            }
        }

        //print_r($proInstockCheck);
        //檢查規格庫存
        if (!empty($proInstockCheck)) {
            foreach ($proInstockCheck as $_proinstockId => $_ckeck) {
                $instock = $proInstock[$_proinstockId]['instock'];
                $instockCount = $_ckeck['count'];
                //測試庫存不足用
                if ($instock < $instockCount) {
                    $returnData['error'] = '1';
                    //print_r($_ckeck['index']);
                    foreach ($_ckeck['index'] as $_uuid => $_index) {
                        foreach ($_index as $_i) {
                            $returnData['data'][$_uuid]['params']['products'][$_i]['errorStatus'] = '1';
                        }
                    }
                    //print_r($returnData['data']);
                    if (!$isSession) {
                        $returnData['update']['productFormat'] = $specs;
                    }
                    $returnData['msg'] = "商品規格庫存不足($instockCount/$instock)，請選擇其他規格";
                    return $returnData;
                }
            }
        }

        //限購檢查
        //print_r($limitActiveBundle);
        if (!empty($limitActiveBundle)) {
            foreach ($limitActiveBundle as $_abId => $_limitData) {
                //未加入購物車數量
                $exceptShopCartCount = 0;
                if (!$isSession) {
                    $exceptShopCartCount = $_limitData['count'];
                }

                //購物車數量
                $shopCartCount = isset($shopCartCountArr[$_abId]) ? $shopCartCountArr[$_abId] : 0;

                //訂單數量
                $orderBundleCount = 0;
                //訂單所有有該活動分組的資料
                $orderBundleSql = "SELECT";
                $orderBundleSql .= " orderBundle.activeBundleName,orderBundle.price,orderBundle.pv,orderBundle.bv";
                $orderBundleSql .= " orders.status";
                $orderBundleSql .= " FROM orderBundle";
                $orderBundleSql .= " LEFT JOIN orders ON orders.id = orderBundle.orderId";
                $orderBundleSql .= " WHERE 1";
                $orderBundleSql .= " AND orderBundle.activeBundleId = '$_abId'";

                $db->setQuery($orderBundleSql);
                $orderBundleData = $db->loadRowList();
                if (!empty($orderBundleData)) {
                    foreach ($orderBundleData as $_orderBundle) {
                        //status對應為pubcode資料表中codeKinds=bill的資料
                        //6為已取消
                        if (!($_orderBundle['status'] == '6')) {
                            $orderBundleCount++;
                        }
                    }
                }

                $allCount = $exceptShopCartCount + $shopCartCount + $orderBundleCount;
                $limitCount = $_limitData['limit'];
                //print_r($activeBundleData);
                if ($allCount > $limitCount) {
                    $returnData['error'] = '1';
                    $returnData['msg'] = "【" . $activeBundleData['actives'][$_abId]['name'] . "】超出限購次數($allCount/$limitCount)";
                    return $returnData;
                }
            }
        }
    }
    return $returnData;
}

/**
 * 產生uuid
 * @param    $prefix 前綴
 */
function uuid($prefix = '')
{
    mt_srand((float) microtime() * 10000);
    $charid = strtoupper(md5(uniqid(rand(), true)));
    // "-"
    $hyphen = chr(45);
    $uuidArr = array();
    if (!($prefix == '')) {
        $uuidArr[] = $prefix;
    }
    $uuidArr[] = substr($charid, 0, 8);
    $uuidArr[] = substr($charid, 8, 4);
    $uuidArr[] = substr($charid, 12, 4);
    $uuidArr[] = substr($charid, 16, 4);
    $uuidArr[] = substr($charid, 20, 12);
    $uuid = implode($hyphen, $uuidArr);
    return $uuid;
}

/**
 * 訂單成功後的更新
 * @param    $orderNum 訂單編號
 */
function orderSuccessUpdate($orderNum)
{
    global $db, $db2, $db3;

    $time = time();
    $today = date("Y-m-d", $time);
    $now = date("Y-m-d H:i:s", $time);

    $orderSql = "SELECT";
    $orderSql .= " orders.*";
    $orderSql .= " , members.ERPID";
    $orderSql .= " FROM orders";
    $orderSql .= " LEFT JOIN members ON orders.memberid = members.id";
    $orderSql .= " WHERE orders.orderNum = '$orderNum' AND orders.status = '0'";
    $db->setQuery($orderSql);
    $order = $db->loadRow();
    if (!empty($order)) {
        $orderId = $order['id'];
        $orderNum = $order['orderNum'];
        $ERPID = $order['ERPID'];
        $memberId = $order['memberid'];
        $buyDate = $order['buyDate'];
        $totalAmt = $order['totalAmt'] - $order['m_discount'] - $order['cb_use_points'] - $order['use_points'];

        //更新訂單狀態
        $updateData = array(
            'status' => '1',
            'mtime' => $now,
            'finalPayDate' => $now,
        );
        $updateSql = dbUpdate('orders', $updateData, "id = '" . $orderId . "'");
        $db->setQuery($updateSql);
        $db->query();

        //寫訂單log
        $log = array(
            'oid' => $orderId,
            'cdate' => $today,
            'status' => '1',
            'ctime' => $now,
            'mtime' => $now,
            'muser' => $memberId,
        );
        $insertSql = dbInsert('orderlog', $log);
        $db->setQuery($insertSql);
        $db->query();

        //5天
        $delayTime = 5 * 86400;
        if (strtotime(date("Y-m-d")) - strtotime($buyDate) > $delayTime) {
            //超時註記
            $db->setQuery("update members set delayCnt=delayCnt+1 where id='$memberId'");
            $db->query();
        }

        if ($order['pointchk'] == '0') {
            $sql = "update members set pv=pv+'{$order['pv']}',bv=bv+'{$order['bv']}',bonus=bonus+'{$order['bonus']}' where id='$memberId';";
            $db->setQuery($sql);
            $db->query();

            $insertData = array(
                'memberid' => $memberId,
                'rDate' => $today,
                'amt' => $order['bonus'],
                'status' => '0',
                'orderid' => $orderId,
                'ctime' => $now,
                'mtime' => $now,
                'muser' => $memberId,
            );
            $insertSql = dbInsert('bonusRecord', $insertData);
            $db->setQuery($insertSql);
            $db->query();

            $updateData = array(
                'pointchk' => '1',
            );
            $updateSql = dbUpdate('orders', $updateData, "id = '" . $orderId . "'");
            $db->setQuery($updateSql);
            $db->query();
        }

        $siteInfoSql = "SELECT * FROM siteinfo";

        $db->setQuery($siteInfoSql);
        $siteInfo = $db->loadRow();

        //自己寄給自己
        $adminmail = $siteInfo['email'];
        $webname = $siteInfo['name'];
        $sendto = array(array("email" => $adminmail, "name" => $webname));
        $subject = $webname . " - " . _CART_PAY_SUCCESS_MSG1 . " (" . date("Y-m-d H:i:s") . ")";
        $body = "
			<html>
			<head>
				<meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
				<title>$webname " . _CART_PAY_SUCCESS_MSG2 . "</title>

			</head>
			<body style=\"margin:0;padding:0;\">
				<div class=\"formstyle\" style=\"margin:0 auto;padding:20px 0;max-width:600px;min-height:900px;height:auto;font-family:\"Microsoft JhengHei\";font-size:15px;color:#333;\">
					<h3 style=\"letter-spacing:1px;\">" . _CART_PAY_SUCCESS_MSG3 . "</h3>
					<p style=\"line-height:180%;\">" . _CART_PAY_SUCCESS_MSG4 . "</p>
					<h3 style=\"margin-top:25px; text-align:center;letter-spacing:1px;\"></h3>
					<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" style=\"width: 100%;border:3px #333 solid;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;\">
						<tbody>
							<tr>
								<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" width=\"65\" align=\"left\"><strong>" . _CART_PAY_SUCCESS_MSG5 . "</strong></td>
								<td style=\"padding:10px 5px 10px 20px;border-bottom:1px #333 solid;\" align=\"left\">$orderNum</td>
							</tr>
						</tbody>
					</table>
					<br>
					<p style=\"line-height:180%;\"><strong style=\"font-size:16px;\">$webname</strong><br>
					" . _CART_PAY_SUCCESS_MSG6 . "{$siteInfo['tel']}&emsp;&emsp;" . _CART_PAY_SUCCESS_MSG7 . "{$siteInfo['addr']}<br>
					" . _CART_PAY_SUCCESS_MSG8 . "{$siteInfo['email']}</p>

				</div>
			</body>
			</html>
			";

        global_send_mail($adminmail, $webname, $sendto, $subject, $body);

        //E化入會
        if ($order['orderMode'] == 'addMember') {
            $updateData = array(
                'salesChk' => '1',
                'payDate' => $today,
            );
            $updateSql = dbUpdate('members', $updateData, "id = '" . $memberId . "'");
            $db->setQuery($updateSql);
            $db->query();
            sendMailToMemberBySignupSuccess($memberId);
            // $lv = '1';
            // export_tomlm($memberId, $lv);
        }

        if ($order['orderMode'] == 'updateMember') {
            $now = date('Y-m') . '%';
            $nowtime = date('Y-m-d');
            $first_day = date('Y-m') . '-01';
            $this_m = date('Y-m-d');
            $next_m = date('Y-m-d');
            // $next_m = date('Y-m', strtotime('+1 Month')).'-01';
            $msql = "SELECT * FROM members where id = '$memberId'";
            $db->setQuery($msql);
            $md = $db->loadRow();
            $mb_no = $md['ERPID'];

            $sid = $md['sid'];

            $sql = "SELECT COUNT(*) AS cnt from order_m where mb_no = '$mb_no' and ord_date2 like '$now'"; //檢查有沒有訂單
            $db2->setQuery($sql);
            $cnt = $db2->loadRow();

            if ($cnt > 0) {
                $umbsql = "UPDATE mbst SET grade_1_chk = '1' and grade_1_date = '$next_m' where mb_no = '$mb_no'";
            } else {
                $umbsql = "UPDATE mbst SET grade_1_chk = '1' and grade_1_date = '$this_m' where mb_no = '$mb_no'";
            }

            $db2->setQuery($umbsql);
            $db2->query();

            $umsql = "UPDATE members set onlyMember = '0',exMember = '1',exTime = '$nowtime',payDate='$today' where id ='$memberId'";
            $db->setQuery($umsql);
            $db->query();

            //先檢查目前點數中心狀況
            $psql = "SELECT * FROM member_lv where mb_no = '$mb_no'";
            $db3->setQuery($psql);
            $pd = $db3->loadRow();

            //預設升級lv
            $up_lv = $pd['lv'];

            if ($pd['lv'] == '3') {
                $up_lv = '7';
            } else if ($pd['lv'] < 3) {
                $up_lv = '6';
            }

            //與原本不一樣才更新
            if (!($up_lv == $pd['lv'])) {
                $pusql = "UPDATE member_lv SET lv = '$up_lv',yymm = '$this_m' where mb_no = '$mb_no'";
                $db3->setQuery($pusql);
                $db3->query();
            }

        }

        //寫傳銷資料-改在建訂單的時候寫入
        //toMLM($orderId, $totalAmt);

        //傳銷寫入付款資訊
        paid($order,$orderNum,$ERPID,$totalAmt);

        //給購物金
        $get_point_url = POINTBANKURL . "public/api/front_orders/calc_points/" . $orderNum;
        $results = file_get_contents($get_point_url);

        //給回饋點數
        $get_point_url2 = POINTBANKURL . "public/api/front_orders/calc_cb_points/" . $orderNum;
        $results2 = file_get_contents($get_point_url2);

    }

}

/**
 * 訂單取消後的更新
 * @param    $orderNum 訂單編號
 */
function orderCancelUpdate($orderNum)
{
    global $db, $db2, $db3;

    $time = time();
    $today = date("Y-m-d", $time);
    $now = date("Y-m-d H:i:s", $time);

    $orderSql = "SELECT";
    $orderSql .= " orders.*";
    $orderSql .= " , members.ERPID";
    $orderSql .= " FROM orders";
    $orderSql .= " LEFT JOIN members ON orders.memberid = members.id";
    $orderSql .= " WHERE orders.orderNum = '$orderNum' AND orders.status IN ('0','2')";
    $db->setQuery($orderSql);
    $order = $db->loadRow();
    print_r($order);
    if (!empty($order)) {
        $orderId = $order['id'];
        $orderNum = $order['orderNum'];
        $memberId = $order['memberid'];
        $buyDate = $order['buyDate'];
        $totalAmt = $order['totalAmt'];
        $useCoin = $order['usecoin'];
        $status = $order['status'];

        $db->setQuery("update orders set status=6 where id='$orderId'");
        $db->query();
        $db->setQuery("update members set coin=coin+'$useCoin' where id='$memberId'");
        $db->query();
        $db->setQuery("insert into orderlog (oid,cdate,status,ctime,mtime,muser) values ('$orderId','$today','6','$now','$now','" . $memberId . "')");
        $db->query();

        if ($order['return_regpoint'] == '0') {
            //歸還註冊紅利點
            $rp_arr = array();
            $rp_arr['memberid'] = $order['memberid'];
            $rp_arr['ERPID'] = $order['mb_no'];
            $rp_arr['rDate'] = date('Y-m-d H:i:s');
            $rp_arr['amt'] = $order['regpoint'];
            $rp_arr['notes'] = $order['orderNum'] . '歸還';
            $rp_arr['status'] = '0';
            $rp_arr['orderid'] = $order['id'];
            $rp_arr['mtime'] = date('Y-m-d H:i:s');
            $rp_arr['type'] = 'return';
            $rp_arr['alive'] = '1';
            $rpsql = dbInsert('regpoint_record', $rp_arr);
            $db->setQuery($rpsql);
            $db->query();

            $ousql = "UPDATE orders set return_regpoint = '1' where id = '$orderId'";
            $db->setQuery($ousql);
            $db->query();
        }

        $odsql = "SELECT * from orderdtl where oid = '$orderId'";
        $db->setQuery($odsql);
        $order_detail = $db->loadRowList();
        if (!empty($order_detail)) {
            foreach ($order_detail as $key => $pro) {
                if ($pro['protype'] == 'e3pro' && $pro['has_return'] != 1) {
                    $sql2 = "SELECT C.ps,C.end_date,C.eff_date,A.ord_no,C.ord_no as c_ord_no,C.date,C.mb_no,C.timestamp,B.point from e_cash2 as A,e_cash2_robin as B,e_cash2 as C where A.sn = B.consume_sn and C.sn = B.provide_sn and A.ord_no = '$orderNum' and A.ord_no <> ''";
                    $res['sql2'] = $sql2;
                    $db2->setQuery($sql2);
                    $ec2 = $db2->loadRowList();
                    if (!empty($ec2)) {
                        $res['ec2'] = $ec2;
                        foreach ($ec2 as $k => $v) {
                            $e3arr = array();
                            $e3arr['ord_no'] = $v['c_ord_no'];
                            $e3arr['mb_no'] = $v['mb_no'];
                            $e3arr['kind'] = '1';
                            $e3arr['point'] = $v['point'];
                            $e3arr['date'] = $v['date'];
                            $e3arr['ps'] = trim($v['ord_no'], '(網購退還)') . '(網購退還)';
                            $e3arr['timestamp'] = $v['timestamp'];
                            $e3arr['update_user'] = 'H2008'; //暫設
                            $e3arr['eff_date'] = $v['eff_date'];
                            $e3arr['update_state'] = '1';
                            $e3arr['end_date'] = $v['end_date']; //有效日期
                            $e3arr['exchange_sn'] = $v['point'];
                            $esql = dbInsert('e_cash2', $e3arr);
                            $db2->setQuery($esql);
                            $db2->query();
                            $pid = $pro['id'];
                            $rsql = "UPDATE orderdtl set has_return = '1' where id = '$pid'";
                            $db->setQuery($rsql);
                            $db->query();
                        }
                    } else {
                        $sql3 = "SELECT * from e_cash2 where ord_no = '$orderNum' and ord_no <> ''";
                        $res['sql3'] = $sql3;
                        $db2->setQuery($sql3);
                        $ec = $db2->loadRowList();
                        if (!empty($ec)) {
                            foreach ($ec as $k2 => $v2) {
                                $sn = $v2['sn'];
                                $csql = "SELECT * FROM e_cash2_robin where consume_sn = '$sn'";
                                $db2->setQuery($csql);
                                $list = $db2->loadRowList();
                                $check = count($list);
                                if ($check > 0) {
                                    $sql2 = "SELECT C.ps,C.end_date,C.eff_date,A.ord_no,C.ord_no as c_ord_no,C.date,C.mb_no,C.timestamp,B.point from e_cash2 as A,e_cash2_robin as B,e_cash2 as C where A.sn = B.consume_sn and C.sn = B.provide_sn and A.ord_no = '$orderNum' and A.ord_no <> ''";
                                    $db2->setQuery($sql2);
                                    $ec2 = $db2->loadRowList();
                                    foreach ($ec2 as $k => $v) {
                                        $e3arr = array();
                                        $e3arr['ord_no'] = $v['c_ord_no'];
                                        $e3arr['mb_no'] = $v['mb_no'];
                                        $e3arr['kind'] = '1';
                                        $e3arr['point'] = $v['point'];
                                        $e3arr['date'] = $v['date'];
                                        $e3arr['ps'] = trim($v['ord_no'], '(網購退還)') . '(網購退還)';
                                        $e3arr['timestamp'] = $v['timestamp'];
                                        $e3arr['update_user'] = 'H2008'; //暫設
                                        $e3arr['eff_date'] = $v['eff_date'];
                                        $e3arr['update_state'] = '1';
                                        $e3arr['end_date'] = $v['end_date']; //有效日期
                                        $e3arr['exchange_sn'] = $v['point'];
                                        $esql = dbInsert('e_cash2', $e3arr);
                                        $db2->setQuery($esql);
                                        $db2->query();
                                        $pid = $pro['id'];
                                        $rsql = "UPDATE orderdtl set has_return = '1' where id = '$pid'";
                                        $db->setQuery($rsql);
                                        $db->query();
                                    }
                                } else {
                                    $dsql = "DELETE FROM e_cash2 where ord_no = '$orderNum'";
                                    $db2->setQuery($dsql);
                                    $db2->query();
                                }
                            }
                        }
                    }
                }
            }
        }

        //是否已付款(0:待付款,1:已核定付款,2:待配送,3:已配送,4:完成交易,7:備貨中,8:已退貨,9:已通知付款)
        $isPay = in_array($status, array("1", "2", "3", "4", "7", "8", "9")) ? true : false;

        //是否退回饋點
        $CBReturn = false;
        //未付款給退
        if (!$isPay) {
            $CBReturn = true;
        }

        if ($CBReturn) {
            //該訂單回饋點已使用的作廢
            $CBInvalidSql = "UPDATE cash_back SET is_invalid = '1' WHERE orderNum = '$orderNum' AND kind ='1'";
            $db3->setQuery($CBInvalidSql);
            $db3->query();
        }

        // //是否退活動回饋點
        // $ACBReturn = false;
        // //未付款給退
        // if (!$isPay) {
        //     $ACBReturn = true;
        // }
        // if ($ACBReturn) {
        //     //該訂單活動回饋點已使用的作廢
        //     $ACBInvalidSql = "UPDATE act_cash_back SET is_invalid = '1' WHERE orderNum = '$orderNum' AND kind ='1'";
        //     $db3->setQuery($ACBInvalidSql);
        //     $db3->query();
        // }

        //是否退購物金
        $PointReturn = false;
        //未付款給退
        if (!$isPay) {
            $PointReturn = true;
        } else {
            //已付款當天給退
            if ($buyDate == $today) {
                $PointReturn = true;
            }
        }
        if ($PointReturn) {
            //該訂單活動購物金已使用的作廢
            $pointInvalidSql = "UPDATE points SET is_invalid = '1' WHERE orderNum = '$orderNum' AND kind ='2'";
            $db3->setQuery($pointInvalidSql);
            $db3->query();
        }

        order_instock($status, "6", $orderId);
    }

}

function send_sms($sms_to, $sms_msg)
{
    
    global $Conf_sms_username, $Conf_sms_password, $globalConf_sms_open, $conf_user;
    $res = array();
    if ($_SESSION[$conf_user]['syslang'] == 'en') {
        $languageType = '1';
        $send_context = rawurlencode(stripslashes($sms_msg));
    } else {
        $languageType = '2';
        $send_context = String2Hex($sms_msg);
        
    }
    // $languageType = '2';
    if ($globalConf_sms_open) {
        $send_context = json_decode(str_replace(["\u"," "],"",json_encode($send_context)));
        $query_string = "api.aspx?apiusername=" . $Conf_sms_username . "&apipassword=" . $Conf_sms_password;
        $query_string .= "&senderid=INFO&mobileno=" . rawurlencode($sms_to);
        $query_string .= "&message=" . $send_context . "&languagetype=" . $languageType;
        $url = "http://gateway.onewaysms.com.my:10001/" . $query_string;
        $res['url'] = $url;
        $res['context'] = $send_context;
        $res['sms'] = $sms_msg;
        // JsonEnd($res);
        
        $fd = @implode('', file($url));
        if ($fd) {
            if ($fd > 0) {
                // print("MT ID : " . $fd);
                $ok = "success";
            } else {
                // print("Please refer to API on Error : " . $fd);
                $ok = "fail";
            }
        } else {
            // no contact with gateway
            $ok = "fail";
        }
        return $ok;
        // $res['url'] = $url;
        // $res['url2'] = file($url);
        // $res['ok'] = $ok;
        // $res['fd'] = $fd;

        // JsonEnd($res);
    }

}

function String2Hex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        if(ctype_digit($string[$i])){
            $temp = str_pad(dechex(ord($string[$i])),4,"0",STR_PAD_LEFT);
        }else if(ctype_alpha($string[$i])){
            $temp = str_pad(dechex(ord($string[$i])),4,"0",STR_PAD_LEFT);
        }else{
            $temp = $string[$i];
            
        }
        $hex .= $temp;
    }
    return $hex;
}

// function str2hex( $str ){
//     return array_shift(unpack('H*',$str));
// }

// function hex2str( $hex ) {
//     return pack('H*', $hex);
// }


function utf8_substr($str, $start, $num){  
    $res = '';      //存儲截取到的字符串  
    $cnt = 0;       //計數器，用來判斷字符串是否走到$start位置  
    $t = 0;         //計數器，用來判斷字符串已經截取了$num的數量  
    for($i = 0; $i < strlen($str); $i++){  
        if(ord($str[$i]) > 127){    //非ascii碼時  
            if($cnt >= $start){     //如果計數器走到了$start的位置  
                $res .=$str[$i].$str[++$i].$str[++$i]; //utf-8是三字節編碼，$i指針連走三下，把字符存起來  
                $t ++;              //計數器++，表示我存了幾個字符串了到$num的數量就退出了  
            }else{    
                $i++;               //如果沒走到$start的位置，那就只走$i指針，字符不用處理  
                $i++;     
            }         
            $cnt ++;  
        }else{    
            if($cnt >= $start){     //acsii碼正常處理就好  
                $res .=$str[$i];  
                $t++;     
            }         
            $cnt ++;                          
        }         
        if($num == $t) break;       //ok,我要截取的數量已經夠了，我不貪婪，我退出了  
    }         
    return $res;  
}  