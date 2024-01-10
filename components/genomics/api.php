<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="news";
userPermissionChk($tablename);
switch ($task) {
	
	case "list": 
		genomics_list();
		break;
}

function genomics_list()
{
	global $db5, $globalConf_list_limit;
	
	$table = 'sn_data';
	$cur = global_get_param( $_REQUEST, 'page', null);
	$sn_status_type = global_get_param( $_REQUEST, 'sn_status_type', null);
	$search = global_get_param( $_REQUEST, 'search', null);
	$orderby = global_get_param( $_REQUEST, 'orderby', null);
	$arrJson = array();
	
	if($orderby) {
		$orderby = str_replace('\"', '"', $orderby);
		$orderarray = json_decode($orderby,true);
		if(count($orderarray) > 0) {
			$orderstr = "";
			foreach($orderarray as $k=>$v) {
				$orderstr = $k." ".$v.",".$orderstr;
			}
			$orderstr = "ORDER BY ".$orderstr."id desc";
		}
	} else {
		$orderstr = "ORDER BY id desc";
	}

	

	$where_str = "WHERE 1";

	if($sn_status_type){
		if($sn_status_type == '9'){
			$where_str .= " AND sn_status = '0'";
		}else{
			$where_str .= " AND sn_status = '$sn_status_type'";
		}
	}

	if($search) {
		
		$search = str_replace('\"', '"', $search);
		$search = str_replace('\\\\', '', $search);
		$searcharray = json_decode($search,true);
		if(count($searcharray) > 0) {
			foreach($searcharray as $k=>$v) {
				if(!empty($v) && $v != ''){
					if($k == 'keyword'){
						$where_str.= " AND (mb_name LIKE '%".$v."%' OR boss_id LIKE '%".$v."%' OR mb_no LIKE '%".$v."%' OR mb_mobile LIKE '%".$v."%' OR sn LIKE '%".$v."%' OR report_date LIKE '%".$v."%')";
					}
					// $where_str .= " AND ".$k." like '%".$v."%' ";
				}
			}
		}

	}

	
		
	$today = date("Y-m-d");
	 

	$sql = " select * from $table $where_str $orderstr";	

	$db5->setQuery( $sql );
	$arrJson['sql'] = $sql;
	$row = $db5->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	$returnArray = array();
	
	for($i = $from; $i < min($end, $cnt); $i++) {
		$sn_status = $row[$i]['sn_status'];
		switch ($sn_status) {
			case '1':
				$sn_status_str = '合格';
				break;
				case '2':
					$sn_status_str = '作廢';
					break;
					case '3':
						$sn_status_str = '退回';
						break;
						case '4':
							$sn_status_str = '雜發';
							break;
			default:
				$sn_status_str = '售出';
				break;
		}
		$row[$i]['sn_status_str'] = $sn_status_str;
		$region = $row[$i]['region'];
		switch ($region) {
			case 'tw':
				$region_str = '臺灣';
				break;
				case 'us':
					$region_str = '美國';
					break;
				
			default:
				$region_str = '無國別';
				break;
		}
		$row[$i]['region_str'] = $region_str;

		if(!empty($row[$i]['report_url'])){
			$report_exist = '1';
		}else{
			$report_exist = '0';
		}
		$row[$i]['report_exist'] = $report_exist;
		$returnArray[] = $row[$i];
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;
	$arrJson['cnt'] = $pagecnt;
	$arrJson['ss'] = $_REQUEST;

	JsonEnd($arrJson);
}

function news_detail(){
	global $db,$conf_user, $conf_news, $conf_dir_path;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );

	$sql = "SELECT id, name, linktype, post_type, audience, newsDate, pubDate, target, content, newsType, publish, linkurl, summary FROM news WHERE id = '$id'";
	$db->setQuery( $sql );
	$news_arr = $db->loadRow();	
	
	$info = array();
	if(count($news_arr)>0)
	{
		$info['id'] = $news_arr['id'];
		$info['name'] = $news_arr['name'];
		$info['post_type'] = $news_arr['post_type'];
		$info['audience'] = $news_arr['audience'];
		$info['linktype'] = $news_arr['linktype'];
		$info['newsDate'] = $news_arr['newsDate'];
		$info['pubDate'] = $news_arr['pubDate'];
		$info['content'] = $news_arr['content'];
		$info['newsType'] = $news_arr['newsType'];
		$info['publish'] = $news_arr['publish'];
		$info['target'] = $news_arr['target'];
		$info['linkurl'] = $news_arr['linkurl'];
		$info['summary'] = $news_arr['summary'];
		
		$imglist=getimg("news",$id);
		foreach($imglist as $num=>$value){
			$info['var'][$num]=$info["var{$num}"];
			$info['img'][$num]=$value;
		}
		
		$dbpagelinkdata = getdbpagelinkdata("news", $id);
		foreach($dbpagelinkdata as $key=>$value){
			$info[$key]=$value;
		}
		
	}
	$arrJson['info'] = $info;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
	
}

function news_update() {
	global $db, $conf_user, $conf_news;
	$arrJson = array();
	$today = date("Y-m-d H:i:s");
	
	$dataArr = array();
	$dataArr['id']			= global_get_param($_REQUEST, 'id', null);
	$dataArr['publish']		= global_get_param($_REQUEST, 'publish', null);
	$dataArr['target']		= global_get_param($_REQUEST, 'target', null);
	$dataArr['newsType']	= global_get_param($_REQUEST, 'newsType', null);
	$dataArr['name']		= global_get_param($_REQUEST, 'name', null);
	$dataArr['post_type']	= global_get_param($_REQUEST, 'post_type', null);
	$dataArr['audience']	= global_get_param($_REQUEST, 'audience', null);
	$dataArr['linktype']	= global_get_param($_REQUEST, 'linktype', null);
	$dataArr['content']		= global_get_param($_REQUEST, 'content', null);
	$dataArr['linkurl']		= global_get_param($_REQUEST, 'linkurl', null);
	$dataArr['newsDate']	= global_get_param($_REQUEST, 'newsDate', null);
	$dataArr['pubDate']		= global_get_param($_REQUEST, 'pubDate', null);
	$dataArr['summary']		= global_get_param($_REQUEST, 'summary', null);
	$dataArr['mtime']		= $today;
	$dataArr['muser']		= $_SESSION[$conf_user]['uid'];
	

	
	$img = global_get_param( $_REQUEST, 'img', null );
	$isuploadimg = global_get_param( $_REQUEST, 'isuploadimg', null );
	$tablename = global_get_param( $_REQUEST, 'tablename', null );
	$databaseid = global_get_param( $_REQUEST, 'databaseid', null );
	$databasename = global_get_param( $_REQUEST, 'databasename', null );
	
	$now = date("Y-m-d H:i:s");
	
	$sql = createUpdateSql('news', $dataArr);
	
	
	$db->setQuery( $sql );
	if($db->query()) {
		$msg = $dataArr['id'] ? "更新成功" : "新增成功";
		$id = $dataArr['id'] ? $dataArr['id'] : $db->insertid();
		$dbid = getFieldValue("select id from dbpageLink where fromtable='news' AND fromid='$id'","id");
		
		if($linktype=="database") {	
			$dirid = $databaseid;
			$pageid = 0;
			$db->setQuery("DESCRIBE $tablename");
			$collist=$db->loadRowList();
			foreach($collist as $row){
				
				if($row['Field']=='belongid'){
					$pagetype=getFieldValue("select pagetype from $tablename where id='$databaseid'","pagetype");
					if($pagetype=="dir"){
						$dirid=$databaseid;
						$pageid=0;
					}else{
						$dirid=getFieldValue("select belongid from $tablename where id='$databaseid'","belongid");
						$pageid=$databaseid;
					}
					
					break;
				}
			}
			
			if($dbid){
				$sql="update dbpageLink set totable='$tablename',dirid='$dirid',pageid='$pageid',name='$databasename' where id='$dbid'";
			}else{
				$sql="insert into dbpageLink (fromtable,fromid,totable,dirid,pageid,name) values ('news','$id','$tablename','$dirid','$pageid','$databasename')";
			}
			$db->setQuery($sql);
			$db->query();
		} else {
			if($dbid){
				$sql="delete from dbpageLink where id='$dbid'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		if($isuploadimg){
			$path=$id.".jpg";
			imgupd($img,$conf_news.$path,"news",$id);
		}
		
		$arrJson['status'] = 1;
		$arrJson['msg'] = $msg;
	} else {
		$arrJson['status'] = 0;
		$arrJson['msg'] = $id ? "更新失敗" : "新增失敗";
	}
	JsonEnd($arrJson);
}

function news_delete() {
	global $db, $conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$sql = "DELETE FROM news WHERE  id='$id'";
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_ERR;
	}
	else
	{
		$arrJson['status'] = "1";
		$arrJson['msg'] = "刪除成功";
	}
		
	JsonEnd($arrJson);
}

function batchOperate(){
	global $db, $conf_user;
	$tablename = 'news';
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
