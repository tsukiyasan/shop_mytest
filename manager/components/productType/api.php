<?php


$tablename="producttype";
include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
userPermissionChk("productType");
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
	$sql="delete from $tablename where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_DEL_SUS));
}

function detail(){
	global $db,$tablename,$template_option;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select * from $tablename where id='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	        if($key=="publish"){
	        	$data[$key]=$row=='1'? '1':'0';
	        }
	        if($key=="formchk"){
	        	$data[$key]=$row=='1'? '1':'0';
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
	
	$data['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
	JsonEnd(array("status"=>1,"data"=>$data,"nameList"=>$nameList));
}
function dirdb(){
	global $db,$tablename;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
	
	$updatesql_addStr = "";
	$updatevalue_addStr = "";
	$updatesqlend_addStr = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			$updatesql_addStr .= " `name_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$nameTmp', ";
			$updatesqlend_addStr .= " `name_".$row['code']."`=VALUES(`name_".$row['code']."`), ";
		}
	}
	
	$updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} belongid,treelevel,publish,pagetype) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$belongid','$level','$publish','dir')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} publish=VALUES(publish)";
		
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
    global $db,$tablename,$real_page,$template_option,$conf_user;
    $cur = intval(global_get_param( $_REQUEST, 'page', null ,0,1  ));
    if($cur==0)$cur=1;
	
	 
    $data['belongid'] = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $data['level'] = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    if($data['belongid']==0)$data['belongid']="root";
    
    $search_str=strval(global_get_param( $_REQUEST, 'search_str', null ,0,1  ));
    if($search_str){
    	
		$textList = getLanguageList("text");
		if($textList && count($textList) > 0)
		{
			$where_str .= " AND ( name like '%$search_str%' ";	
			foreach($textList as $row)
			{
				$where_str .= " OR `name_".$row['code']."` like '%$search_str%' ";	
			}
			$where_str .= " )";	
		}
		else
		{
			$where_str=" AND name like '%$search_str%'";
		}
    }
    $sql = "select * from $tablename where belongid='{$data['belongid']}' $where_str order by odring,id";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$cnt=count($r);
	$pagecnt=ceil($cnt/10);
	$cur = ($cur > $pagecnt) ? $pagecnt : $cur;
	$from=($cur-1)*10;
	if($cnt>0){
		if($mode!='all'){
			$sql.=" limit $from,10";
		}else{
			$_SESSION[$conf_user]['belongid']=$data['belongid'];
		}
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		$data=array();
		
		foreach($r as $key=>$row){
			$info=array();
		 	$info['id']=$row['id'];
		 	$info['name']=$row['name'];
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
		 	$info['belongid']=$row['belongid'];
		 	$info['pagetype']=$row['pagetype'];
		 	$info['publish']=$row['publish']=='1'?'1':'0';
		 	$info['level']=intval($row['treelevel']);
		 	$info['odring']=intval($row['odring']);
		 	if($row['pagetype']=="page"){
		 		$info['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
		 	}
			elseif($row['pagetype']=="dir")
			{
				$info['pagecnt'] = getFieldValue(" SELECT COUNT(*) as cnt FROM $tablename WHERE belongid = '{$info['id']}'","cnt");
			}
			$data[]=$info;
		}
	}
	$backid=getFieldValue("select belongid from $tablename where id='{$info['belongid']}'","belongid");
	
	$sql_str = " name ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str = " CASE `name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN name  
						WHEN '' THEN name 
						ELSE `name_".$_SESSION[$conf_user]['syslang']."` 
					END AS name ";
	}
	$belongname=getFieldValue("select {$sql_str} from $tablename where id='{$info['belongid']}'","name");
	JsonEnd(array("status"=>1,"data"=>$data,"backid"=>$backid,"belongname"=>$belongname,"cnt"=>$pagecnt));
	
}
function pagedb(){
    global $db,$tablename;
    
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $name = global_get_param( $_REQUEST, 'name', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
    $content = global_get_param( $_REQUEST, 'content', null ,0,0  );
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$var1 = global_get_param( $_REQUEST, 'var1', null ,0,1  );
	$formchk = global_get_param( $_REQUEST, 'formchk', null ,0,1  );
    
	$updatesql_addStr = "";
	$updatevalue_addStr = "";
	$updatesqlend_addStr = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			$updatesql_addStr .= " `name_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$nameTmp', ";
			$updatesqlend_addStr .= " `name_".$row['code']."`=VALUES(`name_".$row['code']."`), ";
		}
	}
	
    $updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} belongid,treelevel,publish,pagetype,content,formchk,var1) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$belongid','$level','$publish','page',N'$content','$formchk',N'$var1')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} publish=VALUES(publish),content=VALUES(content),formchk=VALUES(formchk),var1=VALUES(var1)";
		
	if($id==0){
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>