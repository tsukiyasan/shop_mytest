<?php


include( '../../config.php' ); 

$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tablename="uploadfiles";
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
	case "batchOperate":
		batchOperate();
		break;
}

function showlist($mode=null){
	global $db, $globalConf_list_limit, $tablename;
	$arrJson = array();
	
	$page = max(intval(global_get_param( $_REQUEST, 'page', 1 )), 1);
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
	
	
	$sql = "SELECT * FROM $tablename $where_str ORDER BY odring";	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$cnt = count($r);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$page = ($page > $pagecnt) ? $pagecnt : $page;
	
	$from = ($page - 1 ) * $globalConf_list_limit;
	$end = $page * $globalConf_list_limit;
	
	$data = array();
	if($mode == 'all'){
		foreach($r as $key=>$row){
			$info=array();
		 	$info['id']=$row['id'];
		 	$info['name']=$row['name'];
		 	$info['publish']=intval($row['publish']);
		 	$info['content'] = $row['content'];
		 	$info['odring']=intval($row['odring']);
			$data[]=$info;
		}
	} else {
		for($i = $from; $i < min($end, $cnt); $i++) {
			$info=array();
		 	$info['id']=$r[$i]['id'];
		 	$info['name']=$r[$i]['name'];
		 	$info['publish']=intval($r[$i]['publish']);
		 	$info['content'] = $r[$i]['content'];
		 	$info['odring']=intval($r[$i]['odring']);
			$data[]=$info;
		}
	}

	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
}

function showdetail(){
	global $db,$conf_dir_path,$conf_banner, $tablename;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	$sql = "SELECT * FROM $tablename WHERE id='$id'";

	$db->setQuery( $sql );
	$uploadfiles_arr = $db->loadRow();	
	$uploadfilesList = array();
	if(count($uploadfiles_arr) > 0)
	{
		$info = array();
		$info['id'] = $id;
		$info['name'] = $uploadfiles_arr['name'];
		$info['publish'] = intval($uploadfiles_arr['publish']);
		$info['content'] = $uploadfiles_arr['content'];
		$info['odring'] = $uploadfiles_arr['odring'];
		$info['linktype'] = $uploadfiles_arr['linktype'];
		$info['linkurl'] = $uploadfiles_arr['linkurl'];
		$info['filetype'] = $uploadfiles_arr['filetype'];
		$info['note'] = $uploadfiles_arr['note'];
		$filetype = $uploadfiles_arr['filetype'];
		$imglist = getimg($tablename, $id);
		foreach($imglist as $num=>$value){
			$info['img'][$num]=$value;
		}
		$filesql = "select * from filelist where belongid = $id";
		$db->setQuery($filesql);
		$file_list = $db->loadRow();
		$info['files'] = $file_list;
		$dbpagelinkdata = getdbpagelinkdata($tablename, $id);
		foreach($dbpagelinkdata as $key=>$value){
			$info[$key] = $value;
		}
	}
	
	$arrJson['data'] = $info;
	$arrJson['status'] = "1";
	$arrJson['filetype'] = $filetype;
	JsonEnd($arrJson);
}

function updatepage(){
	ini_set('display_errors','1');
	global $db, $conf_user,$tablename,$conf_banner,$conf_uploadfile;
	$arrJson = array();
	$today = date("Y-m-d H:i:s");
	
	$dataArr = array();
	$dataArr['id']		= $id = global_get_param($_REQUEST, 'id', null);
	$dataArr['name']	= $name = global_get_param($_REQUEST, 'name', null);
	$dataArr['publish']	= global_get_param($_REQUEST, 'publish', null);
	$dataArr['content']	= global_get_param($_REQUEST, 'content', null);
	$dataArr['odring']	= global_get_param($_REQUEST, 'odring', null);
    $dataArr['linktype']= global_get_param( $_REQUEST, 'linktype', null ,0,1  );
    $dataArr['linkurl'] = global_get_param( $_REQUEST, 'linkurl', null);
    $dataArr['filetype'] = global_get_param( $_REQUEST, 'file_type', null);
    $dataArr['note'] = global_get_param( $_REQUEST, 'note', null);
	$dataArr['mtime']	= $today;
	$dataArr['muser']	= $_SESSION[$conf_user]['uid'];
	$check_name = 0;
	$check_sql = "SELECT * from uploadfiles where name ='$name'";
	if (!empty($id)) {
		$check_sql .= " and id != $id";
	}
	$db->setQuery($check_sql);
	$check_result = $db->loadRowList();
	if(count($check_result) > 0){
		$check_name = 1;
	}
	
    $dbpagename = global_get_param( $_REQUEST, 'tablename', null ,0,1  );
    $databaseid = global_get_param( $_REQUEST, 'databaseid', null ,0,1  );
    $databasename = global_get_param( $_REQUEST, 'databasename', null ,0,1  );
	
	if(!$dataArr['id']) {
		$dataArr['ctime'] = $today;
	}
	
	//$img = global_get_param( $_REQUEST, 'img', null );
	$file = global_get_param( $_REQUEST, 'file', null );
	$file_sub_name = global_get_param( $_REQUEST, 'file_sub_name', null );
	$origin_name = global_get_param( $_REQUEST, 'file_name', null );
	
	$dirid = $databaseid;
	$pageid = 0;
	if($dataArr['linktype'] == "database"){	
		if(fieldExist($dbpagename, "belongid")) {
			if(fieldExist($dbpagename, "pagetype")) {
				$pagetype = getFieldValue("select pagetype from $dbpagename where id='$databaseid'","pagetype");
			}
			if($pagetype == "dir"){
				$dirid = $databaseid;
				$pageid = 0;
			}else{
				$dirid = getFieldValue("select belongid from $dbpagename where id='$databaseid'","belongid");
				$pageid = $databaseid;
			}
		}else{
			$dirid = 0;
			$pageid = $databaseid;
		}
	
	}
	if($check_name == 1){
		$arrJson['msg'] = _COMMON_QUERYMSG_SAM_TIT;
		$arrJson['status'] = "0";
	}else{
		$sql = createUpdateSql($tablename, $dataArr);
		$db->setQuery($sql);
		if(!$db->query()){
			$arrJson['msg'] = _COMMON_QUERYMSG_UPD_ERR;
			$arrJson['status'] = "0";
		}else{
			if (empty($id)) {
				$id = getFieldValue("SELECT LAST_INSERT_ID()","LAST_INSERT_ID()");
			}
			
			$dbid = getFieldValue("select id from dbpageLink where fromtable='$tablename' AND fromid='$id'","id");
			
			
			
			
			// if(count($img)>0){
			// 	foreach($img as $key=>$value){
			// 		if($value){
			// 			$path=$id."_".$key.".jpg";
			// 			imgupd($value,$conf_banner.$path,$tablename,$id,$key);
			// 		}
			// 	}
			// }
			if(count($file)>0){
				foreach($file as $key=>$value){
					if($value){
						// $path=$id."_".$key.".".$file_sub_name;
						$path=$name.".".$file_sub_name;
						$arrJson['file_path'] = $path;
						fileupd($value,$conf_uploadfile.$path,$tablename,$id,$key,$path);
					}
				}
			}
			$arrJson['msg'] = _COMMON_QUERYMSG_UPD_SUS;
			$arrJson['status'] = "1";
		}
	}
	
	
	JsonEnd($arrJson);
}

function deletepage(){
	global $db, $conf_user,$conf_banner,$tablename,$conf_dir_path;
	$arrJson = array();
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ));
	
	
	$sql = "DELETE FROM $tablename WHERE id = '$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
		JsonEnd($arrJson);
	} else {
		delAllimg($tablename, $id);
		
		$arrJson['status'] = "1";
		$arrJson['msg'] = "刪除成功";
		JsonEnd($arrJson);
	}
		
}

function batchOperate(){
	global $db, $tablename, $conf_user;
	$idarr = global_get_param($_REQUEST, 'id', null, 0, 1);
	$action = global_get_param( $_REQUEST, 'action', null, 0, 1);
	$returnArray = array();
	if(!$idarr){
		$returnArray['status'] = 0;
	} else {
		$id = implode(",", $idarr);
		if($action == "open"){
			$sql = "update $tablename set publish='1' where id in ($id)";
		}else if($action == "close"){
			$sql = "update $tablename set publish='0' where id in ($id)";
		}else if($action == "delete"){
			$sql = "delete from $tablename where id in ($id);";
			foreach($idarr as $key=>$val) {
				delAllimg($tablename, $val);
			}
		}
		$db->setQuery($sql);
		if($db->query_batch()) {
			$returnArray['status'] = 1;
		} else {
			$reutrnArray['status'] = 0;
		}
	}
	
	JsonEnd($returnArray);
}

include( $conf_php.'common_end.php' );
