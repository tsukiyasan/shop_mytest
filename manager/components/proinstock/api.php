<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tablename="products";
userPermissionChk("proinstock");
switch ($task) {
	case "alllist":
	       showlist('all');
	    break;
	case "list":
	       showlist();
	    break;
	case "detail":
	       detail();
	    break;
	case "dir_update":	
	       dirdb();
	    break;
	case "add":	
	case "update":	
		pagedb();
		break;
	case "del":	
		del();
		break;	
	case "publishChg":	
		publishChg();
		break;	
	case "odrchg":	
		odrchg($tablename,$id);
		break;	
	case "operate":	
		operate();
		break;	
		
	case "instockchg":	
		instockchg();
		break;
	
	case "instockchkChange":	
		instockchkChange();
		break;
}


function instockchkChange(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$instockchk = intval(global_get_param( $_REQUEST, 'instockchk', null ,0,1  ));
	
	$sql=" update proinstock set instockchk = '$instockchk' where id = '$id'";

	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
}

function instockchg(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$instock = intval(global_get_param( $_REQUEST, 'instock', null ,0,1  ));
	
	$sql=" update proinstock set instock = '$instock' where id = '$id'";

	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
}

function operate(){
	global $db,$tablename,$conf_user;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		
		JsonEnd(array("status"=>0,"msg"=>_ADMINMANAGERS_NO_SELECT));
	}
	$id=implode(",",$idarr);
	$field="";
	if($action==1){
		$field="publish='1'";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==2){
		$field="publish='0'";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==3){
		$sql="";
		foreach($idarr as $value){
			$sql.="delete from $tablename where belongid ='$value';";
		}
		$sql.="delete from $tablename where id in ($id);";
	}
	
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}
function publishChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
	
	$sql="update $tablename set publish='$publish' where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
	
}
function del(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	
	
	$sql = " SELECT count(1) as cnt FROM orderdtl WHERE pid = '$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	if($r['cnt']>0){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCTS_DELETE_ERROR));
	}else{
		
		
		$sql = " SELECT * FROM imglist WHERE belongid = '$id' AND path = 'products'";
		$db->setQuery( $sql );
		$rs=$db->loadRowList();
		if(count($rs) > 0)
		{
			foreach($rs as $row)
			{
				delimg($tablename,$id,$row['num']);
			}
		}
		$sql="DELETE FROM imglist WHERE belongid = '$id' AND path = 'products'";
		$db->setQuery( $sql );
		$db->query();
		
		
		$sql="DELETE FROM proinstock WHERE pid = '$id'";
		$db->setQuery( $sql );
		$db->query();
		
		
		
		$sql="DELETE FROM protype WHERE pid = '$id'";
		$db->setQuery( $sql );
		$db->query();
		
		
		$sql="delete from $tablename where id='$id'";
		$db->setQuery( $sql );
		$db->query();
		JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_DEL_SUS));
	}
}

function detail(){
	global $db,$tablename,$template_option,$conf_dir_path,$conf_product;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$instockMode = global_get_param( $_REQUEST, 'instockMode', null ,0,1  );
	$sql = "select * from $tablename where id='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	        if($key=="publish"){
	        	$data[$key]=$row=='true'?true:false;
	        }
	    }
	}
		
	
	if($instockMode == 'multiple' && !empty($id))
	{
		$sql = " SELECT * FROM proinstock WHERE pid = '$id'";
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		
		$proNum_arr = array();
		if(count($r) > 0)
		{
			foreach($r as $row)
			{
				$info = array();
				$info['id'] = $row['id'];
				$info['name'] = $row['name'];
				$info['instock'] = $row['instock'];
				
				$proNum_arr[] = $info;
			}
		}
		else
		{
			$info = array();
			$info['id'] = '';
			$info['name'] = '';
			$info['instock'] = '';
			
			$proNum_arr[] = $info;
		}
		
		
		$arrJson['proNum_arr'] = $proNum_arr;
	}
	
	
	$imglist=getimg($tablename,$id);
	foreach($imglist as $num=>$value){
		$data['var'][$num]=$data["var{$num}"];
		$data['img'][$num]=$value;
	}
	
	
	$data['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
	$arrJson['data'] = $data;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}
function dirdb(){
	global $db,$tablename;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
	
	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,pagetype) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','dir')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish)";
		
	if($id==0){
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
}
function showlist($mode=null){
    global $db,$tablename,$globalConf_list_limit,$conf_user;
    $cur = intval(global_get_param( $_REQUEST, 'page', null ,0,1  ));
    if($cur==0)$cur=1;
	
	 
    $data['belongid'] = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $data['level'] = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    if($data['belongid']==0)$data['belongid']="root";
	
	$search_str = global_get_param( $_REQUEST, 'search_str', null ,0,1  );
	$where_str = "";
	if($search_str){
    	$where_str.=" AND ( name like '%$search_str%' OR format1 like '%$search_str%' OR format2 like '%$search_str%')";
    }
    
    $order = global_get_param( $_REQUEST, 'order', null ,0,1  );
	$order_str = "";
	if(!empty($order)){
    	$order = ($order == 'asc') ? 'asc' : 'desc';
    	$order_str .= " tab.instock $order , ";
    }
	
	$sql_str = " name  ";
	$sql_strA = " A.name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str = " CASE `name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN name  
						WHEN '' THEN name 
						ELSE `name_".$_SESSION[$conf_user]['syslang']."` 
					END ";
		$sql_strA = " CASE A.`name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN A.name  
						WHEN '' THEN A.name 
						ELSE A.`name_".$_SESSION[$conf_user]['syslang']."` 
					END AS name ";
	}
	
	$data['level'] = 2;
	
	if($data['level']<2){
		$sql = " select id,belongid,treelevel,{$sql_str},siteAmt,publish from $tablename ";
	
		
	}else{
		$sql = "SELECT tab.* FROM (select B.id,A.belongid,A.treelevel,{$sql_strA},A.siteAmt,A.publish,
				(select {$sql_str} from proformat where id=B.format1_type) as format1_type,
				(select {$sql_str} from proformat where id=B.format2_type) as format2_type,
				(select {$sql_str} from proformat where id=B.format1) as format1,
				(select {$sql_str} from proformat where id=B.format2) as format2,
				B.instock, B.instockchk 
				from $tablename A,proinstock B where A.id=B.pid ) tab WHERE 1=1 $where_str order by $order_str tab.name , tab.format1 , tab.format2 ";
	}
	
	$db->setQuery( $sql );
	$row = $db->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	
	$safetystock = intval(getFieldValue(" SELECT safetystock FROM siteinfo" , "safetystock"));
	
	$data = array();
	
	for($i = $from; $i < min($end, $cnt); $i++) {
		
		if($safetystock > $row[$i]['instock'] )
		{
			$row[$i]['safeCode'] = '0';
			$row[$i]['safe'] = _PROINSTOCK_DANGER;
		}
		else
		{
			$row[$i]['safeCode'] = '1';
			$row[$i]['safe'] = _PROINSTOCK_SAFE;
			
		}
		
		$data[] = $row[$i];
	}
	
	$backid=getFieldValue("select belongid from $tablename where id='{$info['belongid']}'","belongid");
	JsonEnd(array("status"=>1,"data"=>$data,"backid"=>$backid,"cnt"=>$pagecnt));
	
}
function pagedb(){
    global $db,$tablename,$conf_product,$conf_user;
    
	$uid = $_SESSION[$conf_user]['uid'];
	
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $name = global_get_param( $_REQUEST, 'name', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
    $var03 = global_get_param( $_REQUEST, 'var03', null ,0,1  );
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$img = global_get_param( $_REQUEST, 'img', null );
	
    $proCode = global_get_param( $_REQUEST, 'code', null ,0,1  );
    $instock = intval(global_get_param( $_REQUEST, 'instock', null ,0,1  ));
    $highAmt = intval(global_get_param( $_REQUEST, 'highAmt', null ,0,1  ));
    $siteAmt = intval(global_get_param( $_REQUEST, 'siteAmt', null ,0,1  ));
    $oriAmt = intval(global_get_param( $_REQUEST, 'oriAmt', null ,0,1  ));
    $proTypeArrays = global_get_param( $_REQUEST, 'proTypeArrays', null ,0,1  );
    $proNum_arr = global_get_param( $_REQUEST, 'proNum_arr', null ,0,1  );
	
    $updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,var03,proCode,instock,highAmt,siteAmt,oriAmt) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish',N'$var03',N'$code','$instock','$highAmt','$siteAmt','$oriAmt')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish),var03=VALUES(var03),instock=VALUES(instock),highAmt=VALUES(highAmt),siteAmt=VALUES(siteAmt),oriAmt=VALUES(oriAmt)";
		
	if($id==0){
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$db->query();
	
	
	if(!$id){
		$id=$db->insertid();
	}
	
	if(count($img)>0){
		foreach($img as $key=>$value){
			if($value){
				$path=$id."_".$key.".jpg";
				imgupd($value,$conf_product.$path,$tablename,$id,$key);
			}
		}
	}
		
	if(count($proNum_arr) > 0)
	{		
		foreach($proNum_arr as $row)
		{
			$name = urldecode($row['name']);
			
			if(!empty($row['id']))
			{
				$sql = " UPDATE proinstock SET name=N'{$name}',instock='{$row['instock']}', mtime='".date('Y-m-d H:i:s')."', muser = '{$uid}' WHERE id='{$row['id']}'";
			}
			else
			{
				$sql = " INSERT INTO proinstock ( pid, name, instock, ctime, mtime, muser) VALUES 
				( '{$id}', N'{$name}', '{$row['instock']}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
			}
			
			$db->setQuery( $sql );
			$db->query();
		}
	}
	
	
	if(count($proTypeArrays) > 0)
	{
		
		$sql = "delete from protype where pid ='$id'";		
		$db->setQuery( $sql );
		$db->query();
		
		foreach($proTypeArrays as $row)
		{
			if($row[0] == 'dir')
			{
				if(is_array($row[6]))
				{					
					
					foreach($row[6] as $ptid)
					{
						$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
						( '{$id}', '{$ptid}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
						
						$db->setQuery( $sql );
						$db->query();
					}
				}
			}
			elseif($row[0] == 'page')
			{
				if(is_array($row[3]))
				{
					
					$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
					( '{$id}', '{$row[3][0][0]}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
					
					$db->setQuery( $sql );
					$db->query();
				}
			}
		}
	}
		
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>