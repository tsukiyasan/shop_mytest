<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="products";

switch ($task) {
	case "list":
		showlist();
	    break;
	case "showAddProlist":
		showAddProlist();
	    break;
	case "setPairProList":
		setPairProList();
	    break;
	case "setActiveProList":
		setActiveProList();
	    break;
    case "delBundle":
        delBundle();
        break;
    case "updateBundle":
        updateBundle();
        break;
	case "set_userinfo":
		set_userinfo();
		break;
	case "get_logistics":
		$returnData = array(
			'status' => true,
			'data' => array(),
			'msg' => ''
		);
		$logisitics = logisitics_type();
		$returnData['data'] = $logisitics[0];
		$returnData['cc'] = $_SESSION[$conf_user]['freeChk'];
		$returnData['CalcDlvrFree'] = $_SESSION[$conf_user]['CalcDlvrFree'];
		$returnData['CalcDlvrFreeNum'] = $_SESSION[$conf_user]['CalcDlvrFreeNum'];
		JsonEnd($returnData);
		break;
	case "get_num_cart_list":
		get_num_cart_list();
		break;
}

function updateBundle(){
    global $db,$conf_user,$tablename;

    $returnArr = array();
    
    $selectedProductList = global_get_param( $_REQUEST, 'selectedProductList', null ,0,0  );
    $modalIndex = global_get_param( $_REQUEST, 'modalIndex', null ,0,0  );
    $modalDtlKey = global_get_param( $_REQUEST, 'modalDtlKey', null ,0,0  );
    $modalMainKey = global_get_param( $_REQUEST, 'modalMainKey', null ,0,0  );

    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    if($activeBundleCart && $selectedProductList && $modalIndex && $modalDtlKey && $modalMainKey){
        
        foreach($activeBundleCart as $activeBundleCartKey=>$row){
            $amount=0;
            if($row['unique']==$modalMainKey){
                foreach($row['activeBundleDetail'] as $activeBundleDetailKey=>$activeBundleDetail){
                    if($activeBundleDetail['unique']==$modalDtlKey){
                        
                        $activeBundleDetail['products']=$selectedProductList;

                        $sql="select siteAmt,highAmt,pv,bv from products where id='{$activeBundleDetail['products']['productId']}'";
                        $db->setQuery( $sql );
                        $products=$db->loadRow();

                        $activeBundleDetail['products']['format']=getProductFormat($activeBundleDetail['products']['productId']);
                        $activeBundleDetail['products']['quantity']=1;
                        $activeBundleDetail['products']['unique']=md5('p'.microtime(true).mt_rand(1,9));
                        $activeBundleDetail['products']['siteAmt']=$products['siteAmt']?$products['siteAmt']:$products['highAmt'];
                        $activeBundleDetail['products']['pv']=$products['pv'];
                        $activeBundleDetail['products']['bv']=$products['bv'];
                        $activeBundleDetail['unique']=md5('d'.microtime(true).mt_rand(1,9));
                        $amount+=$activeBundleDetail['products']['siteAmt'];

                        $activeBundleCart[$activeBundleCartKey]['activeBundleDetail'][$activeBundleDetailKey]=$activeBundleDetail;
                    }
                }
            }
        }
        $_SESSION[$conf_user]['activeBundleCart']=$activeBundleCart;
    }else{

    }
    $returnArr["status"] = 1;
    $returnArr["activeBundleCart"] = $activeBundleCart;
	JsonEnd($returnArr);
}

function delBundle(){
    global $db,$conf_user,$tablename;
    $unique = global_get_param( $_REQUEST, 'unique', null ,0,0  );
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    $returnArr = array();
    if($unique && $activeBundleCart && count($activeBundleCart)>0){
        foreach($activeBundleCart as $key=>$value){
            if($value['unique']==$unique){
                unset($activeBundleCart[$key]);
                break;
            }
        }
        $_SESSION[$conf_user]['activeBundleCart']=$activeBundleCart;
    }
    $returnArr["status"] = 1;
    $returnArr["activeBundleCart"] = $activeBundleCart;
	JsonEnd($returnArr);
}

function setActiveProList()
{
	global $db,$conf_user,$tablename;
	
	$returnArr = array();
	
	$pid = (global_get_param( $_REQUEST, 'pid', null ,0,0  ));
	$atype = (global_get_param( $_REQUEST, 'atype', null ,0,0  ));
	
	$atype = ($atype == 1) ? 1 : 3;
	$_SESSION[$conf_user]["activeUsedPro_arr"][$pid]=$atype;

	$returnArr["status"] = 1;
	JsonEnd($returnArr);
}


function setPairProList()
{
	global $db,$conf_user,$tablename;
	
	$returnArr = array();
	
	$pairProStr = global_get_param( $_REQUEST, 'pairProStr', null ,0,0  );
	$pairProArr = array();
	if(!empty($pairProStr))
	{
		$arr = explode("@-@",$pairProStr);
		if(count($arr) > 0)
		{
			foreach($arr as $row)
			{
				if(!empty($row))
				{
					$pairProArr[] = $row;
				}
			}
		}
	}
	
	$_SESSION[$conf_user]["pairpro_list"]=$pairProArr;

	$returnArr["status"] = 1;
	JsonEnd($returnArr);
}

function showlist(){
	global $db,$conf_user,$tablename,$db3;
	$mode=getCartMode();
	ini_set('display_errors','1');

	$cm = global_get_param($_GET, 'cartmode', null, 0, 0);

	if ($cm == 'twcart_cart') {
		$_SESSION[$conf_user]['is_twcart_cart'] = '1';
	} else {
		$_SESSION[$conf_user]['is_twcart_cart'] = '0';
	}
	// $tl= '';

	if ($mode == 'bonus') {
		$mode = 'bonus';
	} else if ($cm == 'twcart_cart') {
		$mode = 'twcart';
		$_SESSION[$conf_user]["cart_list_mode"] = $mode;
	} else {
		$mode = 'cart';
		$_SESSION[$conf_user]["cart_list_mode"] = $mode;
	}

	
	// if($mode != 'bonus')
	// {
	// 	$mode = 'cart';
	// 	$_SESSION[$conf_user]["cart_list_mode"]=$mode;
	// }
	
	$uid=LoginChk();
	$msql = "SELECT regDate,mobileChk,emailChk,onlyMember from members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$om = $md['onlyMember'];
	$om_str = '';
	if ($om == '0') {
		$om_str .= " AND fordealer = '1'";
	} else {
		$om_str .= " AND formember = '1'";
	}
	
	$orderCnt = getFieldValue(" SELECT COUNT(1) AS cnt FROM orders WHERE memberid = '$uid' AND orderMode = 'addMember' ", "cnt");
	if( $orderCnt > 0)
	{
		$orderStatus = getFieldValue(" SELECT status FROM orders WHERE memberid = '$uid' AND orderMode = 'addMember' ", "status");		
		$emailChk = getFieldValue(" SELECT emailChk FROM members WHERE id = '$uid' ","emailChk");
		$mobileChk = getFieldValue(" SELECT mobileChk FROM members WHERE id = '$uid' ","mobileChk");
		if(($orderStatus != '4' && $orderStatus != '3' && $orderStatus != '1') || ($emailChk == '1' || $mobileChk == '0'))
		{
			
			JsonEnd(array("status" => 0 , "msg"=>_CART_ERROR_MSG	));
		}
	}
	
	
    $cart=$_SESSION[$conf_user]["{$mode}_list"];
	$data=array();
    $proArr=CartProductInfo2($cart);
    
    $_SESSION[$conf_user]['activeBundlePrice']=0;
    $_SESSION[$conf_user]['activeBundlePv']=0;
    $_SESSION[$conf_user]['activeBundleBv']=0;
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    if($activeBundleCart && count($activeBundleCart)>0){
        $activeBundlePrice=0;
        $activeBundlePv=0;
        $activeBundleBv=0;
        foreach($activeBundleCart as $key=>$value){
            $activeBundlePrice+=$value['price'];
            $activeBundlePv+=$value['pv'];
            $activeBundleBv+=$value['bv'];
        }
        $_SESSION[$conf_user]['activeBundlePrice']=$activeBundlePrice;
        $_SESSION[$conf_user]['activeBundlePv']=$activeBundlePv;
        $_SESSION[$conf_user]['activeBundleBv']=$activeBundleBv;
    }
    
	$uid = LoginChk();
	$msql = "SELECT regDate,mobileChk,emailChk,onlyMember from members where id = '$uid'";
	$db->setQuery($msql);
	$md = $db->loadRow();
	$om = $md['onlyMember'];
	

	$activePro_arr = array(); 
	$activePro_actName_arr = array(); 
	$activeUsedPro_arr = $_SESSION[$conf_user]['activeUsedPro_arr']; 
	if(count($proArr['active_list']) > 0)
	{
		foreach($proArr['active_list'] as $row)
		{
			$ptype = $row['act']['ptype'];
			
			$tmp_arr = explode ("49折活動",$row['name']);
			if(count($tmp_arr)>1)
			{
				continue;
			}
			
			$tmp_arr = explode ("耶誕好禮滿",$row['name']);
			if(count($tmp_arr)>1)
			{
				continue;
			}
			
			if($ptype == '1' || $ptype == '3')	
			{				
				if(count($row['usepro']) > 0)
				{
					foreach($row['usepro'] as $pid)
					{
						if(empty($activePro_arr[$pid]))
						{
							$activePro_arr[$pid] = $ptype;
							$activePro_actName_arr[$pid][$ptype] = $row['name'];
						}
						else
						{
							if(($activePro_arr[$pid] == '1' && $ptype == '3') || ($activePro_arr[$pid] == '3' && $ptype == '1'))
							{
								$activePro_arr[$pid] = "13";
								$activePro_actName_arr[$pid][$ptype] = $row['name'];
							}
						}
					}
				}
			}
		}
	}
	
	$activePro_tmpArr = array();
	$activePro_actName_tmpArr = array();
	if(count($activePro_arr) > 0)
	{
		foreach($activePro_arr as $pid=>$row)
		{
			if($row != "13")
			{
				unset($activePro_arr[$pid]);
				unset($activePro_actName_arr[$pid]);
			}
		}
						
		foreach($activePro_arr as $pid=>$row)
		{
			$fid = "";
			foreach($proArr['data'] as $row2)
			{
				if($row2['id'] == $pid)
				{
					$activePro_tmpArr[$row2['fid']] = "13";
					$activePro_actName_tmpArr[$row2['fid']] = $activePro_actName_arr[$pid];
					break;
				}
			}
		}
		$activePro_arr = $activePro_tmpArr;
		$activePro_actName_arr = $activePro_actName_tmpArr;
	}
	
	if(count($activeUsedPro_arr) > 0)
	{
		$_SESSION[$conf_user]['activeUsedPro_arr'] = $activeUsedPro_arr;
	}	
	
	
	
	$proArr=CartProductInfo2($cart,null,true);
	
	
	$payable=true;
	$bonusArr=array();
	if($mode=="bonus" && $_SESSION[$conf_user]['uid']){
		$bonus=(getFieldValue("select bonus from members where id='{$_SESSION[$conf_user]['uid']}'","bonus"));
		if($proArr['total']>$bonus){
			$payable=false;
		}
		$bonusArr=array("userbonus"=>$bonus,"payable"=>$payable);
	}
	
	$cnt=count($proArr['data']);
	
	$data['list']=$proArr['data'];
	
	$addPro=$_SESSION[$conf_user]['amtpro_list'];
	$ap=array();
	if(count($addPro)>0){
		foreach($addPro as $k=>$row){
			if($k && $row){
				$ap[$k]=$row;
			}
		}
	}
	$addPro=$ap;
	
	
	$addAmtPro_chkArr = array();
	$addAmtPro_cntArr = array();  
	$addAmtPro_active_arr = array();  
	$addAmtPro_active_cntArr = array();  
	if(count($proArr['active_list']) > 0)
	{
		foreach($proArr['active_list'] as $row)
		{
			if(count($row['addAmtPro']) > 0)
			{
				foreach($row['addAmtPro'] as $addAmtPro)
				{
					if(!in_array($addAmtPro['id'],$addAmtPro_chkArr))
					{
						$addAmtPro_chkArr[] = $addAmtPro['id'];
					}
					
					if(empty($addAmtPro_cntArr[$addAmtPro['id']]))
					{
						$addAmtPro_cntArr[$addAmtPro['id']] = 0;
					}
					$addAmtPro_cntArr[$addAmtPro['id']] += ($row['addProCnt']);
					$addAmtPro_active_arr[$addAmtPro['id']] = $row['id'];
				}
				$addAmtPro_active_cntArr[$row['id']] += ($row['addProCnt']);
			}
		}
	}
	
	
	$ap = array();
	if(count($addPro) > 0)
	{
		foreach($addPro as $key=>$row)
		{
			$tmp = explode("|||",$key);
			if(in_array($tmp[0],$addAmtPro_chkArr))
			{
				$ap[$key] = $row;
			}
		}
	}
	$addPro=$ap;
	
	
	
	$ap = array();
	if(count($addPro) > 0)
	{
		foreach($addPro as $key=>$row)
		{
			$tmp = explode("|||",$key);
			
			if(($addAmtPro_cntArr[$tmp[0]] - ($row))  >= 0)
			{
				$ap[$key] = $row;
				
				if($tmp[0] == '483')
				{
					$addAmtPro_cntArr[482] -= ($row);
					
				}
				else if($tmp[0] == '482')
				{
					$addAmtPro_cntArr[483] -= ($row);
					
				}
				
				$addAmtPro_cntArr[$tmp[0]] -= ($row);
				$addAmtPro_active_cntArr[$addAmtPro_active_arr[$tmp[0]]] -= ($row);
			}
		}
	}
	$addPro=$ap;
	$_SESSION[$conf_user]['amtpro_list'] = $addPro;
	
	
	
	if(count($proArr['active_list']) > 0)
	{
		foreach($proArr['active_list'] as $key=>$row)
		{
			if(count($row['addAmtPro']) > 0)
			{
				$proArr['active_list'][$key]['addProCnt'] = $addAmtPro_active_cntArr[$row['id']];
			}
		}
	}
	
	if(count($addPro)>0){
		
		$_SESSION[$conf_user]["cart_list_mode"]='amtpro';
		$amtproArr=CartProductInfo2($addPro);
		
		if(count($amtproArr['data']) > 0)
		{
			foreach($amtproArr['data'] as $row)
			{
				$row['protype'] = 'amtpro';
				$data['list'][] = $row;
			}
		}
		
		$proArr['amt'] += ($amtproArr['amt']);
		$proArr['total'] += ($amtproArr['total']);
		
		$cnt+=count($amtproArr['data']);
		
		$_SESSION[$conf_user]["cart_list_mode"]='cart';
	}
	
	$freePro=$_SESSION[$conf_user]['freepro_list'];
	
	
	$freePro_tmp = array();
	
	if(count($proArr['active_list']) > 0)
	{
		foreach($proArr['active_list'] as $row)
		{
			if(count($row['freePro']) > 0)
			{
				foreach($row['freePro'] as $fPro)
				{
					$freePro_tmp[$fPro['fid']] = 1;
				}
			}
		}
	}
	
	
	global $tmpActiveId0221;
	$today=date("Y-m-d H:i");
	$tmpSql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='') AND A.id = '$tmpActiveId0221'
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	$db->setQuery( $tmpSql );
	$tmpActiveData = $db->loadRow();
	if( $tmpActiveData['ptype'] == '3' && $tmpActiveData['activePlanid'] == '9')
	{
		if($proArr['amt'] >= $tmpActiveData['var01'])
		{
			$proArr['active_list'][] = $_SESSION['tmpActive0221'];
			
			$tmpPid = str_replace("||","",$tmpActiveData['var04']);
			$tmpfid = $tmpPid."|||0|||".$tmpActiveId0221."|||1";
			$freePro_tmp[$tmpfid] = 1;
		}
	}
	

	

	$batotal = 0;
	if(count($activeBundleCart) > 0)
	{
		$activeBundleGiftTargetAmountList = array();
		$activeBundleGiftProductIdList = array();
		$activeBundleAmount = array();
		$activeBundleGiftProduct = array();
		foreach($activeBundleCart as $key=>$row)
		{
			$batotal += $row['total_bundleadd'];
			if($_SESSION[$conf_user]['syslang'] && !empty($row['name_'.$_SESSION[$conf_user]['syslang']]))
			{
				$activeBundleCart[$key]['name'] = $row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			if($row['giftCheck'] == '1')
			{
				$activeBundleGiftTargetAmountList[$row['id']] = $row['giftTargetAmount'];
				$activeBundleGiftProductIdList[$row['id']] = $row['giftProductId'];
				$activeBundleAmount[$row['id']] += $row['price'];
			}
		}
		
		if(count($activeBundleAmount) > 0)
		{
			foreach($activeBundleAmount as $activeBundleId=>$amount)
			{
				$freeProCnt = 0; 
				if($activeBundleGiftTargetAmountList[$activeBundleId] > 0)
				{
					$freeProCnt = ($amount / $activeBundleGiftTargetAmountList[$activeBundleId]);
				}
				
				$giftProducts = explode("|", trim($activeBundleGiftProductIdList[$activeBundleId], "|"));
				$i = 1;
				
				$sql = " SELECT * FROM products WHERE id IN ('".implode("','", $giftProducts)."')";
				$db->setQuery($sql);
				$activeBundleGiftProduct = $db->loadRowList();
				if(count($activeBundleGiftProduct) > 0) {
					foreach($activeBundleGiftProduct as $key=>$val) {
						$activeBundleGiftProduct[$key]['bid'] = getFieldValue(" SELECT A.ptid FROM protype A, producttype B WHERE A.ptid = B.id AND A.pid = '".$val['id']."' AND B.pagetype = 'page'","ptid");
						$activeBundleGiftProduct[$key]['format'] = getProductFormat($val['id']);
						$activeBundleGiftProduct[$key]['img'] = getimg('products',$val['id'], 1);
					}
				}
				
				for($j = 0 ; $j < $freeProCnt ; $j++)
				{
					foreach ($giftProducts as $productId) {
						$freePro_tmp[$productId."|||0|||999999".$activeBundleId."|||".$i++] = 1;
					}
				}
				
			}
		}
	}
		
	

	
	if(count($freePro_tmp) == 0)
	{
		$_SESSION[$conf_user]['freepro_list'] = array();
		$freePro = array();
	}
	
	
	$freePro_oriArr = array();	
	if(count($freePro) > 0)
	{
		foreach($freePro as $key=>$row)
		{
			$key_arr = explode("|||",$key);
			$key_tmp = $key_arr[0]."|||0|||".$key_arr[2]."|||".$key_arr[3];
			if(!empty($freePro_tmp[$key_tmp]))
			{
				$freePro_oriArr[$key_tmp] = $key;
			}
		}
		
		
		if(count($freePro_tmp) > 0)
		{
			$fp = array();
			foreach($freePro_tmp as $key=>$row)
			{
				if(!empty($freePro_oriArr[$key]))
				{
					$fp[$freePro_oriArr[$key]] = $row;
				}
				else
				{
					$fp[$key] = $row;
				}
			}
			$freePro = $fp;
		}
		else
		{
			$freePro = array();
		}
	}
	else
	{
		$freePro = $freePro_tmp;
	}
	
	if(count($freePro)>0){
		$_SESSION[$conf_user]["cart_list_mode"]='freepro';
		$freeproArr=CartProductInfo2($freePro);
		if(count($freeproArr['data']) > 0)
		{
			$_SESSION[$conf_user]['freepro_list'] = array();
			
			foreach($freeproArr['data'] as $row)
			{
				$row['protype'] = 'freepro';
								
				
				if(empty($row['format1']))
				{
					$tmp = getProductFormat($row['id']);
					if($tmp['formatonly'] && !empty($tmp['format2only']))
					{
						$row['formatonly'] = $tmp['formatonly'];
						$row['format1only'] = $tmp['format1only'];
						$row['format2only'] = $tmp['format2only'];
						
						$row['format1'] = $tmp['format1only']['id'];
						$row['format1title'] = $tmp['format1title'];
						$row['format1name'] = $tmp['format1only']['name'];
						$row['format1instock'] = $tmp['format2only']['instock'];
						$row['format2'] = $tmp['format2only']['id'];
						$row['format2title'] = $tmp['format2title'];
						$row['format2name'] = $tmp['format2only']['name'];
						$row['format2instock'] = $tmp['format2only']['instock'];
						
						$row['name'] .= "【".$row['format1name']." - ".$row['format2name']."】"; 
						
						$fid = getFieldValue( " SELECT id FROM proinstock WHERE pid = '".$row['id']."' AND format1 = '".$row['format1']."' AND format2 = '".$row['format2']."' ","id" );
												
						if(!empty($fid))
						{
							$row['fid'] = str_replace("|||0|||","|||".$fid."|||",$row['fid']);
						}
					}
					
				}
				
				if(empty($row['activeName']))
				{
					$fid_arr = explode("|||",$row['fid']);
					$activeBundleId = str_replace("999999","",$fid_arr[2]);
					$row['activeName'] = getFieldValue(" SELECT * FROM activeBundle WHERE id = '$activeBundleId' ","name")."的滿額贈品";
				}
				
				$_SESSION[$conf_user]["freepro_list"][$row['fid']]='1';
				
				
								
				$data['list'][] = $row;
			}
		}
		
		$data['freepro']=$freeproArr['data'];
		$cnt+=count($freeproArr['data']);
		
		$_SESSION[$conf_user]["cart_list_mode"]='cart';
	}
	
	
	

	
	$kindCnt=0;
	

	
	$dlvrfeeStr = "";
	$dlvrfeeChk = true;
	
	if(count($proArr['active_list']) > 0)
	{
		foreach($proArr['active_list'] as $row)
		{
			if($row['dlvrfee'] == '1')
			{
				$dlvrfeeChk = false;
			}
		}
	}
	
	
	if($dlvrfeeChk && empty($_SESSION[$conf_user]['pay_type']))
	{
		$dlvrfeeChk = false;
	}
	
	
	$where_str = " AND A.activePlanid = '10'";
	$today=date("Y-m-d H:i");
	$sql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='') $where_str
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	
	$name_str = "name";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$name_str = "name_".$_SESSION[$conf_user]['syslang']."";
	}
		
	if($dlvrfeeChk)
	{
		$dlvrfeeStr = getFieldValue($sql,$name_str);
	}
	$dlvrfeeShowStr = getFieldValue($sql,$name_str);
	
	
	
	
	$usepro_arr = array();
	if(count($proArr['active_list']) > 0)
	{
		foreach( $proArr['active_list'] as $row )
		{
			if($row['act']['activePlanid'] == '12')
			{
				if(count($row['usepro']) > 0)
				{
					foreach($row['usepro'] as $row2)
					{
						$usepro_arr[] = $row2;
						

					}
				}
			}
		}
	}
	
	
	
	
	$tmp_list = $data['list'];
	
	
	
	$product_order_arr = array();  
	if(count($tmp_list) > 0)
	{
		foreach($tmp_list as $key=>$row)
		{
			if(in_array($row['id'],$usepro_arr) && $row['protype'] != "freepro" && $row['protype'] != "amtpro")
			{
				$product_order_arr[$key] = ($row['siteAmt']);
			}
		}
	}
	arsort($product_order_arr);
	
	$product_arr = array();  
	$index = 0;
	
	if(count($product_order_arr) > 0)
	{
		foreach($product_order_arr as $key=>$row)
		{
			$max_tmp = ($tmp_list[$key]['num']);
			
			$tmp = 0;
			foreach($usepro_arr as $usepro_pid)
			{
				if($usepro_pid == $tmp_list[$key]['id'])
				{
					$tmp++;
				}
			}
			
			$tmp = ($tmp > $max_tmp) ? $max_tmp : $tmp;
			
			for($i = 0 ; $i < $tmp ; $i ++)
			{
				$tmp_list[$key]['num'] = 1;
				$tmp_list[$key]['numOri'] = $tmp;
				$tmp_list[$key]['index'] = $index;
				$product_arr[] = $tmp_list[$key];
				$index++;
			}
		}
	}
	
	$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
		
	$pairList = array();	
	$pairNameArr = array();		
	if(count($pairProArr) > 0)
	{
		foreach($pairProArr as $pair)
		{
			$pairArr = explode("@@",$pair);
			$key1 = "";
			$key2 = "";
			$index = 0;
			foreach($product_arr as $key=>$row)
			{
				if($index == 0 && (empty($row['pairid']) && $row['pairid'] != "0") && ($row['id'] == $pairArr[0] || $row['id'] == $pairArr[1]))
				{
					$key1 = $key;
					$index = 1;
					if($row['id'] == $pairArr[0])
					{
						$pairArr[0] = "0";
					}
					else
					{
						$pairArr[1] = "0";
					}
				}
				else if($index == 1 && (empty($row['pairid']) && $row['pairid'] != "0") && ($row['id'] == $pairArr[0] || $row['id'] == $pairArr[1]))
				{
					$key2 = $key;
					break;
				}
			}
			
			if(($key1 != "" || $key1 == "0") && ($key2 != "" ||  $key2 == "0"))
			{
				$product_arr[$key1]["pairid"] = $key2;
				$product_arr[$key2]["pairid"] = $key1;
				$pairList[] = $key1."|".$key2;
				$amt1 = ($product_arr[$key1]['numOri'] > 0 ) ? round($product_arr[$key1]['CalcSiteAmt'] / $product_arr[$key1]['numOri']) : 0;
				$amt2 = ($product_arr[$key2]['numOri'] > 0 ) ? round($product_arr[$key2]['CalcSiteAmt'] / $product_arr[$key2]['numOri']) : 0;
				$amt = $amt1 + round($amt2 * 0.49);
				
				$amtStr = $amt1." ＋ ( ". $amt2 ." × 0.49 ) = ".$amt; 
				$pairNameArr[] = array("pair1id"=>$key1,"pair1Name"=>$product_arr[$key1]['name'],"pair2id"=>$key2,"pair2Name"=>$product_arr[$key2]['name'],"amtStr"=>$amtStr);
			}
		}
	}
	
	
	
	$sql="select * from members where id='$uid'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	
	$member=array();
	
	$member['name']=$r['name'];
	$member['mobile']=$r['mobile'];
	$member['address']=$r['addr'];
	$member['email']=$r['email'];
	$member['canton']['id']=$r['canton'];
	$member['city']['id']=$r['city'];
	$member['salesChk']=$r['salesChk'];	
	
	
	
	

	
	
	

	$data['list'] = cartProductClac( $proArr['active_list'], $data['list'], $proArr['activeExtraList']);
	
	
	
	$tmp_data_list = array();
	
	if(count($data['list']) > 0)
	{
		foreach($data['list'] as $row)
		{
			if(count($row['prodtl']) > 0 && !empty($row['prodtl_use_act']) && $row['protype'] != 'amtpro' && $row['protype'] != 'freepro' )
			{
				if(count($row['prodtl']['amt']) > 0)
				{
					$row['CalcSiteAmt'] = round($row['CalcSiteAmt'] / $row['num']);
					foreach($row['prodtl']['amt'] as $key=>$row2)
					{
						$row['showMode'] = "One";
						$row['showNum'] = 1;
						$row['prodtl_amt'] = $row2;
						$row['prodtl_pv'] = $row['prodtl']['amt_pv'][$key];
						$row['prodtl_bv'] = $row['prodtl']['amt_bv'][$key];
						$row['pairChk'] = $row['prodtl']['pair'][$key];
						$row['useChk'] = $row['prodtl']['use'][$key];
						$tmp_data_list[] = $row;
					}
				}
			}
			else
			{
				$tmp_data_list[] = $row;
			}
		}
		
		$data['list'] = $tmp_data_list;
	}
	
	
	
	
	$_SESSION[$conf_user]['disDlvrAmt']=$proArr['disDlvrAmt'];
	
	
	
	$pay_type = pay_type(null,true);
	
	// JsonEnd(array('status' => '0' , 'msg' => 'not here'));
	$take_type = take_type(null,null,true);

	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now_date = date('Y-m-d');
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind and p.active_date <= '$now_date'";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	$f = array();
	foreach ($plist as $each) {
		if($each['p_type'] == '1'){
			$now_points = bcadd($now_points,$each['point'],2);
		}else if($each['p_type'] == '2'){
			$now_points = bcsub($now_points,$each['point'],2);
		}
	}
	$CBData = get_cash_back(0, $now_date);
	$cb_points = $CBData['point'];
	$cb_gpoints = 0;
	$csql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '0' and expiry_date > '$now_date'";
	$db3->setQuery($csql);
	$cgetlist = $db3->loadRow();
	if(!empty($cgetlist)){
		$cb_gpoints = $cgetlist['cb_points']; //目前可用的得到點數
	}
	$usql = "select sum(point) as cb_points from cash_back where mb_no = '$mb_no' and kind = '1' and expiry_date > '$now_date'";
	$db3->setQuery($usql);
	$cuselist = $db3->loadRow();
	if(!empty($cuselist)){
		$cb_upoints = $cuselist['cb_points']; //目前已使用的得到點數
	}
	
	$cb_points = bcsub($cb_gpoints,$cb_upoints,2);

	
	$sisql = "SELECT * from siteinfo";
	$db->setQuery($sisql);
	$siteinfo = $db->loadRow();
	
	$tax_fee = bcdiv($siteinfo['taxrate'],100,3);
	$ntotal = $proArr['namt'];
	// JsonEnd(array("status"=>'1',"fee"=>$tax_fee));
	$finalccv = $proArr['totalccv'] - $proArr['discount'];

	$u_data = get_user_info_m();
	$mb_no = $u_data['mb_no'];
	$now_date = date('Y-m-d');
	$psql = "SELECT p.*,pk.type as p_type from points as p,point_kind as pk where p.mb_no = '$mb_no' and p.is_invalid = '0' and p.kind = pk.kind and p.active_date <= '$now_date'";
	$db3->setQuery($psql);
	$plist = $db3->loadRowList();
	$now_points = 0;
	$f = array();
	foreach ($plist as $each) {
		if($each['p_type'] == '1'){
			$now_points = bcadd($now_points,$each['point'],2);
		}else if($each['p_type'] == '2'){
			$now_points = bcsub($now_points,$each['point'],2);
		}
	}

	$_SESSION[$conf_user]['tmp_total'] = $proArr['total'] - $proArr['discount'];
	$show_total = $proArr['total'];
	$show_total = c_round($show_total,2);
	if ($mode == 'cart') {
		JsonEnd(
			array(
				"status" => 1,
				"cnt" => $cnt,
				"addPro" => $addPro,
				"freePro" => $freePro,
				"bonusAct" => $bonusActArr,
				"kindCnt" => $kindCnt,
				"data" => $data,
				'activeBundleCart' => $activeBundleCart,
				'activeBundleGiftProduct' => $activeBundleGiftProduct,
				'batotal' => $batotal,
				'proArr' => $proArr,
				'e3Pro' => $e3Pro,
				'e3' => $e3Pros,
				'e3c' => $e3c_cnt,
				'rp' => $rp,
				'rp_discount' => $rp_discount,
				"actProArr" => $product_arr,
				"pairList" => $pairList,
				"pairArr" => $pairNameArr,
				"total" => $show_total,
				"btotal" => $proArr['btotal'],
				"amt" => $proArr['amt'],
				"active_list" => $proArr['active_list'],
				"activePro_arr" => $activePro_arr,
				"activeUsedPro_arr" => $activeUsedPro_arr,
				"activePro_actName_arr" => $activePro_actName_arr,
				"dlvrfeeStr" => $dlvrfeeStr,
				"discount" => $proArr['discount'],
				"dlvrAmt" => intval($_SESSION[$conf_user]['dlvrAmt']) - intval($proArr['disDlvrAmt']),
				"disDlvrAmt" => intval($proArr['disDlvrAmt']),
				"usecoin" => intval($_SESSION[$conf_user]['usecoin']),
				"free_coin" => $proArr['free_coin'],
				"mode" => $mode,
				"bonusArr" => $bonusArr,
				"activeExtraList" => $activeExtraList_Arr,
				"activeExtraGiftProduct" => $activeExtraGiftProduct,
				"pay_type" => $pay_type,
				"take_type" => $take_type,
				"dlvrfeeShowStr" => $dlvrfeeShowStr,
				"h_pv" => $h_pv,
				"m_pv" => $m_pv,
				"t_pv" => $t_pv,
				"h_bv" => ($h_pv * 30),
				"now_points" => floor($now_points),
				"cb_points" => $cb_points,
				"pa" => $pa,
				"om" => $md['onlyMember'],
				"aa" => $aa,
				"act12_list" => $act12_list,
				"logistics_type" => $logistics_list,
				"CBData" => $CBData,
				// "timelist" => $tl
				// "ppv" => $freeproArr,
				'shopCart' => $shopCart,
				'cart_act_pair_list' => $actPair_c,
				'actPair' => $actPair,
				'actPair_discount' => $actPair_discount,
				'addpro_list' => $addpro_list,
				'addpro_list_sub' => $addpro_list_sub,
				'addpro_list_arr' => $addpro_list_arr
			)
		);
	} else if ($mode == 'twcart') {
		JsonEnd(
			array(
				"status" => 1,
				"cnt" => $cnt,
				"addPro" => $addPro,
				"freePro" => $freePro,
				"bonusAct" => $bonusActArr,
				"kindCnt" => $kindCnt,
				"data" => $data,
				// 'activeBundleCart' => $activeBundleCart,
				// 'activeBundleGiftProduct' => $activeBundleGiftProduct,
				'batotal' => $batotal,
				'proArr' => $proArr,
				'e3Pro' => $e3Pro,
				'e3' => $e3Pros,
				'e3c' => $e3c_cnt,
				'rp' => $rp,
				'rp_discount' => $rp_discount,
				"actProArr" => $product_arr,
				"pairList" => $pairList,
				"pairArr" => $pairNameArr,
				"total" => $proArr['total'],
				"btotal" => $proArr['btotal'],
				"amt" => $proArr['amt'],
				"active_list" => $proArr['active_list'],
				"activePro_arr" => $activePro_arr,
				"activeUsedPro_arr" => $activeUsedPro_arr,
				"activePro_actName_arr" => $activePro_actName_arr,
				"dlvrfeeStr" => $dlvrfeeStr,
				"discount" => $proArr['discount'],
				"dlvrAmt" => intval($_SESSION[$conf_user]['dlvrAmt']) - intval($proArr['disDlvrAmt']),
				"disDlvrAmt" => intval($proArr['disDlvrAmt']),
				"usecoin" => intval($_SESSION[$conf_user]['usecoin']),
				"free_coin" => $proArr['free_coin'],
				"mode" => $mode,
				"bonusArr" => $bonusArr,
				"activeExtraList" => $activeExtraList_Arr,
				"activeExtraGiftProduct" => $activeExtraGiftProduct,
				"pay_type" => $pay_type,
				"take_type" => $take_type,
				"dlvrfeeShowStr" => $dlvrfeeShowStr,
				"h_pv" => cnum_format($h_pv),
				"m_pv" => $m_pv,
				"t_pv" => cnum_format($t_pv),
				"h_bv" => cnum_format(($h_pv * 30)),
				"now_points" => floor($now_points),
				"cb_points" => $cb_points,
				"pa" => $pa,
				"om" => $md['onlyMember'],
				"aa" => $aa,
				"act12_list" => $act12_list,
				"logistics_type" => $logistics_list,
				// "timelist" => $tl
				// "ppv" => $freeproArr,
				"CBData" => $CBData,
				'shopCart' => $shopCart,
				'cart_act_pair_list' => $actPair_c,
				'actPair' => $actPair,
				'actPair_discount' => $actPair_discount,
				'addpro_list' => $addpro_list,
				'addpro_list_sub' => $addpro_list_sub,
				'addpro_list_arr' => $addpro_list_arr
			)
		);
	}
}

function showAddProlist(){
	global $db,$conf_user,$tablename;
	$mode=getCartMode();
    $cart=$_SESSION[$conf_user]["{$mode}_list"];
    foreach($cart as $pro){
    	
    }
    
    $data=array();
	$proArr=CartProductInfo2($cart);
	
	$data['list']=$proArr['data'];
	$_SESSION[$conf_user]['disDlvrAmt']=$proArr['disDlvrAmt'];
	JsonEnd(
		array(
			"status" => 1, 
			"data"=>$data,
			"total"=>$proArr['total'],
			"amt"=>$proArr['amt'],
			"active_list"=>$proArr['active_list'],
			"discount"=>$proArr['discount'],
			"dlvrAmt"=>($_SESSION[$conf_user]['dlvrAmt'])-($proArr['disDlvrAmt']),
			"disDlvrAmt"=>($proArr['disDlvrAmt']),
			"usecoin"=>($_SESSION[$conf_user]['usecoin']),
			"free_coin"=>$proArr['free_coin']
		)
	);
}

function set_userinfo(){
	global $conf_user;
	$data = array();
	$data['name'] = $name = global_get_param( $_POST, 'name', null ,0,1);
	$data['mobile'] = $mobile = global_get_param( $_POST, 'mobile', null ,0,1);
	$data['email'] = $email = global_get_param( $_POST, 'email', null ,0,1);
	$data['state'] = $state = global_get_param( $_POST, 'state', null ,0,1);
	$data['city'] = $city = global_get_param( $_POST, 'city', null ,0,1);
	$data['address'] = $address = global_get_param( $_POST, 'address', null ,0,1);
	$data['notes'] = $notes = global_get_param( $_POST, 'notes', null ,0,1);
	$data['dt'] = $dt = global_get_param( $_POST, 'dt', null ,0,1);
	$data['zip'] = $zip = global_get_param( $_POST, 'zip', null ,0,1);
	

	$_SESSION[$conf_user]['user_res_info'] = $data;
	JsonEnd($data);
}

function get_num_cart_list()
{
	global $conf_user, $db;
	$twcart_num = count($_SESSION[$conf_user]['twcart_list']);
	$cart_num = count($_SESSION[$conf_user]['cart_list']);
	//舊的
	$cart_num += count($_SESSION[$conf_user]['activeBundleCart']);
	//新增加的購物車session數量
	$cart_num += getShopCartItemCount();
	$res = array();
	$res['status'] = '1';
	$res['twcart_num'] = $twcart_num;
	$res['cart_num'] = $cart_num;
	JsonEnd($res);
}
include( $conf_php.'common_end.php' ); 
?>