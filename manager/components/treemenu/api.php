<?php



include( '../../config.php' ); 
$tablename="treemenus";
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
userPermissionChk("treemenu");
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
		odrchg($tablename,$id," AND type='treemenu'");
		break;	
	case "operate":	
		operate();
		break;	
	case "imgdel":	
		imgdel();
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

function imgdel(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$num = intval(global_get_param( $_REQUEST, 'num', null ,0,1  ));
	
	delimg($tablename,$id,$num);
	$date=date("Y-m-d H:i:s");
	$db->setQuery("update $tablename set var{$num}='',mtime='$date' where id='$id' AND type='treemenu'");
	$db->query();
				
	JsonEnd(array("status"=>1,"msg"=>"操作成功"));
}
function operate(){
	global $db,$tablename,$conf_user;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(count($idarr)==0){
		JsonEnd(array("status"=>0,"msg"=>"沒有選擇項目"));
	}
	$date=date("Y-m-d H:i:s");
	$id=implode(',',$idarr);
	$field="";
	if($action==1){
		$field="publish=1,mtime='$date'";
		$sql="update $tablename set $field where id in ($id) AND type='treemenu'";
	}else if($action==2){
		$field="publish=0,mtime='$date'";
		$sql="update $tablename set $field where id in ($id) AND type='treemenu'";
	}else if($action==3){
		$sql="";
		foreach($idarr as $value){
			$sql.="delete from $tablename where belongid ='$value' AND type='treemenu';";
		}
		$sql.="delete from $tablename where id in ($id);";
	}
	
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>"操作成功"));
}

function publishChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	
	$sql="update $tablename set publish='$publish' where id='$id' AND type='treemenu'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>"更新成功"));
	
}
function del(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select count(1) as cnt from mainmenus where type='database' AND databaseid='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	if($r['cnt']>0){
		JsonEnd(array("status"=>0,"msg"=>"此頁面已被使用，不可刪除"));
	}else{
		$sql="delete from $tablename where id='$id'";
		$db->setQuery( $sql );
		$db->query();
		JsonEnd(array("status"=>1,"msg"=>"刪除成功"));
	}
}

function detail(){
	global $db,$tablename,$template_option;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select * from $tablename where id='$id' AND type='treemenu'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	    }
	}
	
	$textList = getLanguageList("text");
	$nameList = array();
	$contentList = array();
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameList[$row['code']] = $data["name_".$row['code']];
			$contentList[$row['code']] = $data["content_".$row['code']];
		}
	}
	
	
	

	
	JsonEnd(array("status"=>1,"data"=>$data,"nameList"=>$nameList,"contentList"=>$contentList));
}
function dirdb(){
	global $db,$tablename,$conf_user;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	
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
	
	$date=date("Y-m-d H:i:s");
	$updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} belongid,treelevel,publish,type,pagetype,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$belongid','$level','$publish','treemenu','dir','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} publish=VALUES(publish),mtime=VALUES(mtime),muser=VALUES(muser)";
		
	if($id==0){
		$msg="新增成功";
	}else{
		$msg="更新成功";
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
    	$where_str=" AND name like '%$search_str%'";
    }
    $sql = "select * from $tablename where type='treemenu' AND belongid='{$data['belongid']}' $where_str order by odring,id";
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
		 	$info['publish']=intval($row['publish']);
		 	$info['level']=intval($row['treelevel']);
		 	$info['odring']=intval($row['odring']);
		 	if($row['pagetype']=="page"){
		 		$info['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
		 	}
			$data[]=$info;
		}
	}
	$backid=getFieldValue("select belongid from $tablename where id='{$info['belongid']}'","belongid");
	JsonEnd(array("status"=>1,"data"=>$data,"backid"=>$backid,"cnt"=>$pagecnt));
	
}
function pagedb(){
    global $db,$tablename,$conf_user,$conf_treemenu;
    
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $name = global_get_param( $_REQUEST, 'name_zh-tw', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
    $content = global_get_param( $_REQUEST, 'content', null ,0,0  );
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    $img = global_get_param( $_REQUEST, 'img', null ,0,1  );
    $link = global_get_param( $_REQUEST, 'link', null ,0,1  );
    $var1 = global_get_param( $_REQUEST, 'var1', null ,0,1  );
	
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
			
			$contentTmp = global_get_param( $_REQUEST, 'content_'.$row['code'], null ,0,0  );
			if(empty($content))
			{
				$content = $contentTmp;
			}
			$updatesql_addStr .= " `content_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$contentTmp', ";
			$updatesqlend_addStr .= " `content_".$row['code']."`=VALUES(`content_".$row['code']."`), ";
		}
	}
    
    $date=date("Y-m-d H:i:s");
	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,type,pagetype,content,{$updatesql_addStr} var1,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','treemenu','page',N'$content',{$updatevalue_addStr} N'$var1','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish),content=VALUES(content),{$updatesqlend_addStr} var1=VALUES(var1),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	if($id==0){
		$msg="新增成功";
	}else{
		$msg="更新成功";
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$r=$db->query();
	if($r){
		if(!$id){
			$id=$db->insertid();
		}
		
		

	}
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>