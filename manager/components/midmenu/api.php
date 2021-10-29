<?php



include( '../../config.php' ); 
$tablename="midmenus";
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
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
}

function publishChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	
	$sql="update $tablename set publish='$publish' where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>"更新成功"));
	
}
function del(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select count(1) as cnt from midmenus where type='database' AND databaseid='$id'";
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
	global $db,$tablename,$template_option,$conf_midmenu;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$sql = "select * from $tablename where id='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	    }
	}

	$data['img']=getimg($tablename,$id);
	$data['img']=$data['img'][1];
	$data['img']=$data['img']?$data['img']:'';
	$data['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
	JsonEnd(array("status"=>1,"data"=>$data));
}
function dirdb(){
	global $db,$tablename,$conf_user;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	$date=date("Y-m-d H:i:s");
	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,pagetype,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','dir','$date','$date','{$_SESSION[$conf_user]['uid']}')";
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
		 	$info['belongid']=$row['belongid'];
		 	$info['pagetype']=$row['pagetype'];
		 	$info['publish']=intval($row['publish']);
		 	$info['level']=intval($row['treelevel']);
		 	$info['odring']=intval($row['odring']);
		 	
			if(is_file($conf_dir_path.$conf_midmenu.$id.".jpg")){
				$info['img'] = $conf_midmenu.$id.".jpg?t=".time();
			}else{
				$info['img'] = '';
			}
			$data[]=$info;
		}
	}
	$backid=getFieldValue("select belongid from $tablename where id='{$info['belongid']}'","belongid");
	JsonEnd(array("status"=>1,"data"=>$data,"backid"=>$backid,"cnt"=>$pagecnt));
	
}
function pagedb(){
    global $db,$tablename,$conf_user,$conf_midmenu;
    
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $belongid = !$belongid?'root':$belongid;
    $name = global_get_param( $_REQUEST, 'name', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    $linktype = global_get_param( $_REQUEST, 'linktype', null ,0,1  );
    $dbpagename = global_get_param( $_REQUEST, 'tablename', null ,0,1  );
    $databaseid = global_get_param( $_REQUEST, 'databaseid', null ,0,1  );
    $databasename = global_get_param( $_REQUEST, 'databasename', null ,0,1  );
    $linkurl = global_get_param( $_REQUEST, 'linkurl', null ,0,1  );
	$img = global_get_param( $_REQUEST, 'img', null );
    $isuploadimg = global_get_param( $_REQUEST, 'isuploadimg', null );
	
    $date=date("Y-m-d H:i:s");
    $updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,pagetype,linktype,linkurl,tablename,databaseid,databasename,ctime,mtime,muser) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','page','$linktype','$linkurl','$dbpagename','$databaseid',N'$databasename','$date','$date','{$_SESSION[$conf_user]['uid']}')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish),linktype=VALUES(linktype),linkurl=VALUES(linkurl)
					,tablename=VALUES(tablename),databaseid=VALUES(databaseid),databasename=VALUES(databasename),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	if($isuploadimg){
		$path=$id.".jpg";
		imgupd($img,$conf_midmenu.$path,$tablename,$id);
	}
	if($id==0){
		$msg="新增成功";
	}else{
		$msg="更新成功";
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$r=$db->query();
	
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>