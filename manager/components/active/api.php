<?php




include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tablename="active";
userPermissionChk($tablename);
switch ($task) {
	
	case "list":	
		showlist();
		break;
	case "detail":	
		showdetail();
		break;
	case "update":	
		updatepage();
		break;
	case "del":	
		deletepage();
		break;
	case "odrchg":	
		odrchg($tablename,$id);
		break;	
	case "alllist":
	    showlist('all');
	    break;	
	case "getTypeListM":
	    getTypeListM();
	    break;	
	case "getProList":
	    getProList();
	    break;
	case "getActList":	
	    getActList();
	    break;
	case "getActTypeList":	
	    getActTypeList();
	    break;
	case "getBasicInfo":
		getBasicInfo();
		break;
}

function getBasicInfo()
{
	global $db, $tablename;
	$arrJson = array();
	
	$arrJson['textList'] = getLanguageList("text");
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function getActTypeList(){
	global $db, $conf_user;
	
	$activePlanid = global_get_param( $_REQUEST, 'activePlanid', null );

	if(!empty($activePlanid))
	{
		$sql = " SELECT * FROM activePlans WHERE id = '$activePlanid'";
		$db->setQuery( $sql );
		$info = $db->loadRow();	
		
		if($activePlanid == "13")
		{
			$info['actRange1'] = "0";
			$info['actType1'] = "0";
		}
		
		$actRange_arr = array();
		if($info['actRange1'] == '1')
		{
			$actRange_arr[] = '1';
		}
		if($info['actRange2'] == '1')
		{
			$actRange_arr[] = '2';
		}
		$actRange_whereStr = "";
		if(count($actRange_arr) > 0)
		{
			$actRange_whereStr = " AND codeValue IN ('".implode("','",$actRange_arr)."')";
		}
		
		
		$actType_arr = array();
		if($info['actType1'] == '1')
		{
			$actType_arr[] = '1';
		}
		if($info['actType2'] == '1')
		{
			$actType_arr[] = '2';
		}
		if($info['actType3'] == '1')
		{
			$actType_arr[] = '3';
		}
		if($info['actType4'] == '1')
		{
			$actType_arr[] = '4';
		}
		if($info['actType5'] == '1')
		{
			$actType_arr[] = '5';
		}
		$actType_whereStr = "";
		if(count($actType_arr) > 0)
		{
			$actType_whereStr = " AND codeValue IN ('".implode("','",$actType_arr)."')";
		}
		
		$sql_str = "codeName";
		if($_SESSION[$conf_user]['syslang'])
		{
			switch ($_SESSION[$conf_user]['syslang']) {
				case 'zh-cn':
					$sql_str = "codeName_chs";
					break;
				case 'en':
					$sql_str = "codeName_en";
					break;
				case 'in':
					$sql_str = "codeName_in";
					break;
				default :
					$sql_str = "codeName";
					break;
			}
		}
		
		
		$sql = " SELECT codeValue AS id,{$sql_str} AS name FROM pubcode WHERE codeKinds = 'actRange' AND deleteChk = '0' $actRange_whereStr ORDER BY odring ";
		$db->setQuery( $sql );
		$list = $db->loadRowList();	
		$actRange_list = array();
		if(count($list) > 0)
		{
			$actRange_list = $list;
		}
		
		
		$sql = " SELECT codeValue AS id,{$sql_str} AS name FROM pubcode WHERE codeKinds = 'actType' AND deleteChk = '0' $actType_whereStr ORDER BY odring ";
		$db->setQuery( $sql );
		$list = $db->loadRowList();	
		$actType_list = array();
		if(count($list) > 0)
		{
			$actType_list = $list;
		}
	}
	
	$arrJson = array();
	$arrJson['status'] = '1';
	$arrJson['actRange_list'] = $actRange_list;
	$arrJson['actType_list'] = $actType_list;
	JsonEnd($arrJson);
	
}

function getProList(){
	global $db, $globalConf_list_limit,$tablename;
	
	$arrJson = array();
	
	$typeid = global_get_param( $_REQUEST, 'typeid', null );
	$selectpro = global_get_param( $_REQUEST, 'selectpro', null );
	$activeid = intval(global_get_param( $_REQUEST, 'activeid', null ));
	$sdate = global_get_param( $_REQUEST, 'activesdate', null );
	$edate = global_get_param( $_REQUEST, 'activeedate', null );
	$target = global_get_param( $_REQUEST, 'target', null );
	$proType = global_get_param( $_REQUEST, 'proType', null );
	$planType = global_get_param( $_REQUEST, 'planType', null );
	
	
	
	$has_arr = array();	
	if($target != 'gift')
	{
		$where_str = "";
		if(!empty($activeid))
		{
			$where_str .= " AND A.id <> '$activeid'";
		}
		
		if(!empty($sdate) && empty($edate))
		{
			$sdate = date("Y-m-d H:s",strtotime($sdate));
			
			$where_str .= " AND ( A.edate = '' OR ( A.edate <> '' AND A.edate >= '$sdate' )  )  ";	
		}
		elseif(!empty($sdate) && !empty($edate))
		{
			$sdate = date("Y-m-d H:s",strtotime($sdate));
			$edate = date("Y-m-d H:s",strtotime($edate));
			
			$where_str .= " AND ( ( A.edate = '' AND  A.sdate <= '$edate' ) OR ( A.edate <> '' AND ( ( A.sdate >= '$sdate' AND A.sdate <= '$edate' ) OR ( A.edate >= '$sdate' AND A.edate <= '$edate' ) ) ) ) ";
		}
		
		if(!empty($planType))
		{
			$where_str .= " AND B.type = '$planType'";
		}
	
		$where_str .= " AND A.var03 <> ''";
	
		
		$sql = " SELECT A.* FROM active A, activePlans B WHERE A.activePlanid = B.id AND A.publish = '1' AND A.actRangePCode = '2' $where_str";
		$db->setQuery( $sql );
		$list0 = $db->loadRowList();
		if(count($list0))
		{
			foreach($list0 as $row)
			{
				$tmp = $row['var03'];
				
				if(!empty($tmp))
				{
					$arr = explode("||",$tmp);
					if(count($arr) > 0)
					{
						foreach($arr as $r)
						{
							if(!empty($r))
							{
								$has_arr[] = $r;
							}
						}
					}
				}
			}
		}
	}
	
	
	$where_str = "";
	if(!empty($selectpro))
	{
		$where_str = " AND A.id NOT IN ('".implode("','",explode("||",$selectpro))."')";
	}
	
	if($proType == 'amtPro')
	{
		$sql = " SELECT A.* FROM products A , protype B WHERE A.id = B.pid AND amtProChk='1' AND ptid='$typeid' $where_str ";
	}
	elseif($proType == 'freePro')
	{
		$sql = " SELECT A.* FROM products A , protype B WHERE A.id = B.pid AND freeProChk='1' AND ptid='$typeid' $where_str ";
	}
	else
	{
		$sql = " SELECT A.* FROM products A , protype B WHERE A.id = B.pid AND ptid='$typeid' $where_str ";
	}
	
	$db->setQuery( $sql );
	$list = $db->loadRowList();
	$data_list = array();
	if(count($list) > 0)
	{
		foreach($list as $row)
		{
			$info = array();
			$info['id'] = $row['id'];
			$info['name'] = $row['name'];
			
			$imglist=getimg('products',$row['id']);	
			foreach($imglist as $var)
			{
				$info['img'] = $var;
				break;
			}
			
			if($target == 'gift')	
			{
				$info['has'] = "0";
			}
			else
			{
				if(in_array($row['id'],$has_arr))
				{
					$info['has'] = "1";
				}
				else
				{
					$info['has'] = "0";
				}
			}
			$info['has'] = "0";
			
			$data_list[] = $info;
		}
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $data_list;
	$arrJson['cnt'] = count($data_list);
	JsonEnd($arrJson);
}

function getTypeListM(){
	global $db, $globalConf_list_limit,$tablename;
	
	$arrJson = array();
	
	
	$sql = " SELECT * FROM producttype WHERE 1=1 ORDER BY belongid DESC, odring, id";
	$db->setQuery( $sql );
	$list = $db->loadRowList();
	
	$root_arr = array();
	$data_arr = array();
	if(count($list) > 0)
	{
		foreach($list as $row)
		{
			$info = array();
			$info['id'] = $row['id'];
			$info['name'] = $row['name'];
			
			if($row['belongid'] == 'root')
			{
				$root_arr[$row['id']] = $info;
			}
			else
			{
				$data_arr[$row['belongid']][] = $info;
			}
		}
	}
	
	$data_list = array();
	if(count($root_arr) > 0)
	{
		foreach($root_arr as $key=>$row)
		{
			$data_list[] = $row;
			
			if(count($data_arr[$key]))
			{
				foreach($data_arr[$key] as $key2=>$row2)
				{
					$row2['name'] = $row['name']." - ".$row2['name'];
					
					$data_list[] = $row2;
				}
			}
		}
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $data_list;
	JsonEnd($arrJson);
	
	
}



function showlist($mode=null){
	global $db, $globalConf_list_limit,$tablename,$conf_user;
	$arrJson = array();
	
	$page = global_get_param( $_REQUEST, 'page', null );
	$name = global_get_param( $_REQUEST, 'name', null );
	
	if(isset($name)){
		
		
		$search_arr = explode(" ", $name);
		$search_where_str = "";
		if(count($search_arr) > 0)
		{
			foreach($search_arr as $str)
			{
				if(isset($str))
				{
					$search_where_str .= " OR ( name like N'%$str%' )";
				}
			}
		}
		if(isset($search_where_str))
			$where_str = " WHERE ( 1<>1 $search_where_str) ";
	}
	
	
	$cnt = getFieldValue(" SELECT COUNT(id) AS cnt FROM $tablename ","cnt");
	$globalConf_list_limit = 10;
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$page = ($page > $pagecnt) ? $pagecnt : $page;
	
	$from = ($page - 1 ) * $globalConf_list_limit;
	$end = $page * $globalConf_list_limit;
	
	$data = array();
	$sql = "SELECT * FROM $tablename ORDER BY odring, id DESC";
	if($mode!='all'){
		$sql.=" limit $from,$globalConf_list_limit";
	}
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	foreach($r as $key=>$row){
		$info=array();
	 	$info['id']=$row['id'];
	 	$info['name']=$row['name'];
		
		if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
	 	$info['publish']=$row['publish'];
		$data[]=$info;
	}

	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
}

function showdetail(){
	global $db,$globalConf_upload_dir,$globalConf_upload_banner,$tablename;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	$sql = "SELECT * FROM $tablename WHERE id='$id'";

	$db->setQuery( $sql );
	$advrolls_arr = $db->loadRow();	
	$data = array();
	foreach($advrolls_arr as $key=>$row){
	    if(!is_numeric($key)){
	    	
	    	if($key=="planid"){
	    		$name=getFieldValue("select codeName from pubcode where codeKinds='plan' AND codeValue='$row'","codeName");
	    		$row=array("value"=>strval($row),"name"=>$name);
	    	}
	    	if(is_numeric($row)){
	    		$row=floatval($row);
	    	}
	        $data[$key]=$row;
	    }
	}
	
	$selectpro_list = array();
	if($advrolls_arr['actRangePCode'] == '2')
	{
		$pro_str = $advrolls_arr['var03'];
		if(!empty($pro_str))
		{
			$pro_arr = explode('||',$pro_str);
			
			$sql = " SELECT * FROM products WHERE id IN ('".implode("','",$pro_arr)."')";
			
			$db->setQuery( $sql );
			$arr = $db->loadRowList();
			if(count($arr)>0)
			{
				foreach($arr as $row)
				{
					$info=array();
		 			$info['id']=$row['id'];
		 			$info['name']=$row['name'];
		 			
		 			$imglist=getimg('products',$info['id']);	
					foreach($imglist as $var)
					{
						$info['img'] = $var;
						break;
					}
					
					$selectpro_list[]=$info;
				}
			}
		}
	}
	
	$selectgift_list = array();
	$gift_str = $advrolls_arr['var04'];
	if(!empty($gift_str))
	{
		$gift_arr = explode('||',$gift_str);
		
		$sql = " SELECT * FROM products WHERE id IN ('".implode("','",$gift_arr)."')";
		
		$db->setQuery( $sql );
		$arr = $db->loadRowList();
		if(count($arr)>0)
		{
			foreach($arr as $row)
			{
				$info=array();
	 			$info['id']=$row['id'];
	 			$info['name']=$row['name'];
	 			
	 			$imglist=getimg('products',$info['id']);	
				foreach($imglist as $var)
				{
					$info['img'] = $var;
					break;
				}
				
				$selectgift_list[]=$info;
			}
		}
	}
	
	$textList = getLanguageList("text");
	$nameList = array();
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameList[$row['code']] = $data["name_".$row['code']];
		}
	}
	
	$act_list = getActList('return',$data['actRangePCode'],$data['actTypePCode'],$data['activePlanid']);
	$arrJson['actRange_list'] = $act_list['actRange_list'];
	$arrJson['actType_list'] = $act_list['actType_list'];
	$arrJson['activePlans_list'] = $act_list['activePlans_list'];
	$arrJson['pvbvratio'] = $act_list['pvbvratio'];
	
	$arrJson['data'] = $data;
	$arrJson['nameList'] = $nameList;
	$arrJson['selectpro_list'] = $selectpro_list;
	$arrJson['selectgift_list'] = $selectgift_list;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function getActList( $type = null, $actRange = null, $actType = null, $activePlanid = null){
	global $db, $conf_user;
	
	$getPlans = global_get_param( $_REQUEST, 'getPlans', null );
	if(empty($getPlans))
	{
		$actRange_whereStr = "";
		$actType_whereStr = "";
		if(!empty($activePlanid))
		{
			$sql = " SELECT * FROM activePlans WHERE id = '$activePlanid'";
			$db->setQuery( $sql );
			$info = $db->loadRow();	
			
			$actRange_arr = array();
			if($info['actRange1'] == '1')
			{
				$actRange_arr[] = '1';
			}
			if($info['actRange2'] == '1')
			{
				$actRange_arr[] = '2';
			}
			
			if(count($actRange_arr) > 0)
			{
				$actRange_whereStr = " AND codeValue IN ('".implode("','",$actRange_arr)."')";
			}
			
			
			$actType_arr = array();
			if($info['actType1'] == '1')
			{
				$actType_arr[] = '1';
			}
			if($info['actType2'] == '1')
			{
				$actType_arr[] = '2';
			}
			if($info['actType3'] == '1')
			{
				$actType_arr[] = '3';
			}
			if($info['actType4'] == '1')
			{
				$actType_arr[] = '4';
			}
			if($info['actType5'] == '1')
			{
				$actType_arr[] = '5';
			}
			
			if(count($actType_arr) > 0)
			{
				$actType_whereStr = " AND codeValue IN ('".implode("','",$actType_arr)."')";
			}
		
		}
		
		
		$sql_str = "codeName";
		if($_SESSION[$conf_user]['syslang'])
		{
			switch ($_SESSION[$conf_user]['syslang']) {
				case 'zh-cn':
					$sql_str = "codeName_chs";
					break;
				case 'en':
					$sql_str = "codeName_en";
					break;
				case 'in':
					$sql_str = "codeName_in";
					break;
				default :
					$sql_str = "codeName";
					break;
			}
		}
		
		
		$sql = " SELECT codeValue AS id,{$sql_str} AS name FROM pubcode WHERE codeKinds = 'actRange' AND deleteChk = '0' $actRange_whereStr ORDER BY odring ";
		$db->setQuery( $sql );
		$list = $db->loadRowList();	
		$actRange_list = array();
		if(count($list) > 0)
		{
			$actRange_list = $list;
		}
		
		
		$sql = " SELECT codeValue AS id,{$sql_str} AS name FROM pubcode WHERE codeKinds = 'actType' AND deleteChk = '0' $actType_whereStr ORDER BY odring ";
		$db->setQuery( $sql );
		$list = $db->loadRowList();	
		$actType_list = array();
		if(count($list) > 0)
		{
			$actType_list = $list;
		}
	}
	
	
	if(empty($actRange))
	{
		$actRange = global_get_param( $_REQUEST, 'actRange', null );
		$actType = global_get_param( $_REQUEST, 'actType', null );
	}
	if(!empty($actRange) && !empty($actType))
	{
		$where_str = " AND actRange{$actRange} = '1' AND actType{$actType} = '1'";
	}
	else
	{
		$where_str = " AND 1<>1";
	}
	
	$sql_str = "";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str .= " `name_".$_SESSION[$conf_user]['syslang']."` , ";
		$sql_str .= " `msg2_".$_SESSION[$conf_user]['syslang']."` , ";
	}
	
	$sql = " SELECT id, name, type, unit, msg1, msg2,{$sql_str}  msg3 FROM activePlans WHERE 1=1 AND publish = '1' $where_str ";
	
	$db->setQuery( $sql );
	$list = $db->loadRowList();	
	$activePlans_list = array();
	if(count($list) > 0)
	{
		foreach($list as $row)
		{
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$row['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			if($_SESSION[$conf_user]['syslang'] && $row['msg2_'.$_SESSION[$conf_user]['syslang']])
			{
				$row['msg2']=$row['msg2_'.$_SESSION[$conf_user]['syslang']];
			}
			
			$activePlans_list[$row['id']] = $row;
		}
	}
	
	
	$pvbvratio = getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
	
	if($type == 'return')
	{
		return array("actRange_list"=>$actRange_list , "actType_list"=>$actType_list, "activePlans_list"=>$activePlans_list,"pvbvratio"=>$pvbvratio);
	}
	else
	{
		$arrJson = array();
		$arrJson['status'] = '1';
		$arrJson['actRange_list'] = $actRange_list;
		$arrJson['actType_list'] = $actType_list;
		$arrJson['activePlans_list'] = $activePlans_list;
		$arrJson['pvbvratio'] = $pvbvratio;
		JsonEnd($arrJson);
	}
}


function updatepage(){
	global $db, $conf_user,$tablename,$globalConf_upload_dir,$globalConf_upload_banner;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ));
	$name = global_get_param( $_REQUEST, 'name', null );
	$sdate = global_get_param( $_REQUEST, 'sdate', null );
	$edate = global_get_param( $_REQUEST, 'edate', null );
	$planid = global_get_param( $_REQUEST, 'planid', null );
	$passwordChk = global_get_param( $_REQUEST, 'passwordChk', null );
	$passwordText = global_get_param( $_REQUEST, 'passwordText', null );
	$limitChk = global_get_param( $_REQUEST, 'limitChk', null );
	$limitCnt = global_get_param( $_REQUEST, 'limitCnt', null );
	$var01 = floatval(global_get_param( $_REQUEST, 'var01', null ));
	$var02 = floatval(global_get_param( $_REQUEST, 'var02', null ));
	$cative = intval(global_get_param( $_REQUEST, 'cative', null ));
	
	$actRangePCode = intval(global_get_param( $_REQUEST, 'actRangePCode', null ));
	$actTypePCode = global_get_param( $_REQUEST, 'actTypePCode', null );
	$activePlanid = intval(global_get_param( $_REQUEST, 'activePlanid', null ));
	
	$pv = intval(global_get_param( $_REQUEST, 'pv', null ));
	$bv = intval(global_get_param( $_REQUEST, 'bv', null ));
	
	$date=date("Y-m-d H:i:s");
	$planid=$planid['value'];
	
	
	$var03 = global_get_param( $_REQUEST, 'selectpro', null );
	
	if($var03 == '||')
	{
		$var03 = "";
	}
	
	$var04 = global_get_param( $_REQUEST, 'selectgift', null );
	
	if($var04 == '||')
	{
		$var04 = "";
	}
	
	if($activePlanid == '13')
	{
		
		$var04 = $var03;
	}
	
	$nameTmp = global_get_param( $_REQUEST, 'name_zh-cn', null ,0,0  );
	
	$name = $nameTmp;
	

	$updatesql_addStr = "";
	$updatevalue_addStr = "";
	$updatesqlend_addStr = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			if(empty($name))
			{
				$name = $nameTmp;
			}
			$updatesql_addStr .= " `name_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$nameTmp', ";
			$updatesqlend_addStr .= " `name_".$row['code']."`=VALUES(`name_".$row['code']."`), ";
		}
	}
	
	$updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} publish,sdate,edate,planid,cative,actRangePCode,actTypePCode,activePlanid,var01,var02,var03,var04,pv,bv,passwordChk,passwordText,limitChk,limitCnt,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$publish','$sdate','$edate','$planid','$cative','$actRangePCode','$actTypePCode','$activePlanid','$var01','$var02','$var03','$var04','$pv','$bv','$passwordChk',N'$passwordText','$limitChk','$limitCnt','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} publish=VALUES(publish),sdate=VALUES(sdate),edate=VALUES(edate),planid=VALUES(planid)
						,cative=VALUES(cative),actRangePCode=VALUES(actRangePCode),actTypePCode=VALUES(actTypePCode),activePlanid=VALUES(activePlanid),var01=VALUES(var01),var02=VALUES(var02),var03=VALUES(var03),var04=VALUES(var04),pv=VALUES(pv),bv=VALUES(bv),passwordChk=VALUES(passwordChk),passwordText=VALUES(passwordText),limitChk=VALUES(limitChk),limitCnt=VALUES(limitCnt),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	
	if(!$db->query())
	{
		$arrJson['msg'] = _COMMON_QUERYMSG_UPD_ERR;
		$arrJson['status'] = "0";
		JsonEnd($arrJson);
	}
	
	if($activePlanid == '13')
	{
		
		$sql = " UPDATE products SET freeProChk = '1' WHERE id IN ('0".implode("','",explode("||",$var04))."0') ";	
		$db->setQuery( $sql );
		$db->query();
	}
	
	$arrJson['msg'] = _COMMON_QUERYMSG_UPD_SUS;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}

function deletepage(){
	global $db, $conf_user,$globalConf_upload_dir,$globalConf_upload_banner,$tablename;
	$arrJson = array();
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ));
	
	
	$sql = "DELETE FROM $tablename WHERE id = '$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
		JsonEnd($arrJson);
	}
	else{
		if(is_file("../../".$globalConf_upload_dir.$globalConf_upload_banner."{$id}.jpg")){
			unlink("../../".$globalConf_upload_dir.$globalConf_upload_banner."{$id}.jpg");
		}
		
		delimg($tablename,$id);
		
		$arrJson['status'] = "1";
		JsonEnd($arrJson);
	}
		
}

include( $conf_php.'common_end.php' ); 
?>