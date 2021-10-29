<?php



include( '../../config.php' ); 
$tablename="bottommenus";
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
userPermissionChk("bottommenu");
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
		odrchg($tablename,$id," AND type='bottommenu'");
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

function publishChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	
	$sql="update $tablename set publish='$publish' where id='$id' AND type='bottommenu'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
	
}
function del(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select count(1) as cnt from mainmenus where type='database' AND databaseid='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	if($r['cnt']>0){
		
		JsonEnd(array("status"=>0,"msg"=>_BOTTOMMENU_USED_NOT_DELETE));
	}else{
		$sql="delete from $tablename where id='$id'";
		$db->setQuery( $sql );
		$db->query();
		JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_DEL_SUS));
	}
}

function detail(){
	global $db,$tablename,$template_option;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select * from $tablename where id='$id' AND type='bottommenu'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	    }
	}
	$imglist=getimg($tablename,$id);
	foreach($imglist as $num=>$value){
		$data['var'][$num]=$data["var{$num}"];
		$data['img'][$num]=$value;
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
	global $db,$tablename,$conf_user;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	$date=date("Y-m-d H:i:s");
	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,type,pagetype,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','bottommenu','dir','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish),mtime=VALUES(mtime),muser=VALUES(muser)";
		
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
    $sql = "select * from $tablename where type='bottommenu' AND belongid='{$data['belongid']}' $where_str order by odring,id";
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
    global $db,$tablename,$conf_user,$conf_product;
    
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $name = global_get_param( $_REQUEST, 'name', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    $linktype = global_get_param( $_REQUEST, 'linktype', null ,0,1  );
    $dbpagename = global_get_param( $_REQUEST, 'tablename', null ,0,1  );
    $databaseid = global_get_param( $_REQUEST, 'databaseid', null ,0,1  );
    $databasename = global_get_param( $_REQUEST, 'databasename', null ,0,1  );
    $linkurl = global_get_param( $_REQUEST, 'linkurl', null ,0,1  );
	
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
    
    $date=date("Y-m-d H:i:s");
    $updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} belongid,treelevel,publish,type,pagetype,linktype,linkurl,tablename,databaseid,databasename,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$belongid','$level','$publish','bottommenu','page','$linktype','$linkurl','$dbpagename','$databaseid',N'$databasename','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} publish=VALUES(publish),linktype=VALUES(linktype),linkurl=VALUES(linkurl)
					,tablename=VALUES(tablename),databaseid=VALUES(databaseid),databasename=VALUES(databasename),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	if($id==0){
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	
	$dirid=$databaseid;
	$pageid=0;
	if($linktype=="database"){	
		if(fieldExist($dbpagename, "belongid")) {
			if(fieldExist($dbpagename, "pagetype")) {
				$pagetype=getFieldValue("select pagetype from $dbpagename where id='$databaseid'","pagetype");
			}
			if($pagetype=="dir"){
				$dirid=$databaseid;
				$pageid=0;
			}else{
				$dirid=getFieldValue("select belongid from $dbpagename where id='$databaseid'","belongid");
				$pageid=$databaseid;
			}
		}else{
			$dirid=0;
			$pageid=$databaseid;
		}
	
	}
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$r=$db->query();
	if($id==0){
		$id=$db->insertid();
		$sql="insert into dbpageLink (fromtable,fromid,totable,dirid,pageid,name) values ('$tablename','$id','$dbpagename','$dirid','$pageid','$databasename')";
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		
		$dbid=getFieldValue("select id from dbpageLink where fromtable='$tablename' AND fromid='$id'","id");
		if($dbid){
			$sql="update dbpageLink set totable='$dbpagename',dirid='$dirid',pageid='$pageid',name='$databasename' where id='$dbid'";
		}else{
			$sql="insert into dbpageLink (fromtable,fromid,totable,dirid,pageid,name) values ('$tablename','$id','$dbpagename','$dirid','$pageid','$databasename')";
		}
		
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	$db->setQuery($sql);
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>