<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="news";
userPermissionChk($tablename);
switch ($task) {
	
	case "list": 
		news_list();
		break;
	case "detail": 
		news_detail();
		break;
	case "add": 
	case "update": 
		news_update();
		break;
	case "del": 
		news_delete();
		break;
	case "batchOperate":
		batchOperate();
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

function news_list()
{
	global $db, $globalConf_list_limit,$conf_user;
	
	$table = 'news';
	$cur = global_get_param( $_REQUEST, 'page', null);
	$newsType = global_get_param( $_REQUEST, 'newsType', null);
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
	if($search) {
		
		$textList = getLanguageList("text");
		
		$search = str_replace('\"', '"', $search);
		$search = str_replace('\\\\', '', $search);
		$searcharray = json_decode($search,true);
		if(count($searcharray) > 0) {
			$where_str = "";
			foreach($searcharray as $k=>$v) {
				
				if($k == 'name' && $textList && count($textList) > 0)
				{
					$where_str .= " AND ( ".$k." like '%".$v."%' ";	
					foreach($textList as $row)
					{
						$where_str .= " OR `".$k."_".$row['code']."` like '%".$v."%' ";	
					}
					$where_str .= " )";	
				}
				else
				{
					$where_str .= " AND ".$k." like '%".$v."%' ";	
				}
			}
		}
	}
		
	$today = date("Y-m-d");
	 

	$sql = " select * from $table where newsType='$newsType' $where_str $orderstr ";	

	$db->setQuery( $sql );
	$row = $db->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $globalConf_list_limit;
	$end = $cur * $globalConf_list_limit;
	
	$returnArray = array();
	
	for($i = $from; $i < min($end, $cnt); $i++) {
		
		if($_SESSION[$conf_user]['syslang'] && $row[$i]['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$row[$i]['name']=$row[$i]['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$returnArray[] = $row[$i];
	}
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $returnArray;
	$arrJson['cnt'] = $pagecnt;

	JsonEnd($arrJson);
}

function news_detail(){
	global $db,$conf_user, $conf_news, $conf_dir_path;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$textList = getLanguageList("text");
	$sql_str = "";
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$sql_str .= " `name_{$row['code']}`,`summary_{$row['code']}`,`content_{$row['code']}`, ";
		}
	}

	$sql = "SELECT id,{$sql_str} name, linktype, newsDate, pubDate, content, newsType, publish, linkurl, summary FROM news WHERE id = '$id'";
	$db->setQuery( $sql );
	$news_arr = $db->loadRow();	
	
	$info = array();
	if(count($news_arr)>0)
	{
		$info['id'] = $news_arr['id'];
		$info['name'] = $news_arr['name'];
		$info['linktype'] = $news_arr['linktype'];
		$info['newsDate'] = $news_arr['newsDate'];
		$info['pubDate'] = $news_arr['pubDate'];
		$info['content'] = $news_arr['content'];
		$info['newsType'] = $news_arr['newsType'];
		$info['publish'] = $news_arr['publish'];
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
	
	
	$nameList = array();
	$summaryList = array();
	$contentList = array();
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameList[$row['code']] = $news_arr["name_".$row['code']];
			$summaryList[$row['code']] = $news_arr["summary_".$row['code']];
			$contentList[$row['code']] = $news_arr["content_".$row['code']];
		}
	}
	
	$arrJson['info'] = $info;
	$arrJson['nameList'] = $nameList;
	$arrJson['summaryList'] = $summaryList;
	$arrJson['contentList'] = $contentList;
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
	$dataArr['newsType']	= global_get_param($_REQUEST, 'newsType', null);
	$dataArr['linktype']	= global_get_param($_REQUEST, 'linktype', null);
	$dataArr['linkurl']		= global_get_param($_REQUEST, 'linkurl', null);
	$dataArr['newsDate']	= global_get_param($_REQUEST, 'newsDate', null);
	$dataArr['pubDate']		= global_get_param($_REQUEST, 'pubDate', null);
	$dataArr['mtime']		= $today;
	$dataArr['muser']		= $_SESSION[$conf_user]['uid'];
	
	
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$dataArr['`name_'.$row['code'].'`'] = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			$dataArr['`summary_'.$row['code'].'`'] = global_get_param( $_REQUEST, 'summary_'.$row['code'], null ,0,0  );
			$dataArr['`content_'.$row['code'].'`'] = global_get_param( $_REQUEST, 'content_'.$row['code'], null ,0,0  );
		}
	}
	else
	{
		$dataArr['name']		= global_get_param($_REQUEST, 'name', null);
		$dataArr['content']		= global_get_param($_REQUEST, 'content', null);
		$dataArr['summary']		= global_get_param($_REQUEST, 'summary', null);
	}
	
	$img = global_get_param( $_REQUEST, 'img', null );
	$isuploadimg = global_get_param( $_REQUEST, 'isuploadimg', null );
	$tablename = global_get_param( $_REQUEST, 'tablename', null );
	$databaseid = global_get_param( $_REQUEST, 'databaseid', null );
	$databasename = global_get_param( $_REQUEST, 'databasename', null );
	
	$now = date("Y-m-d H:i:s");
	
	$sql = createUpdateSql('news', $dataArr);
	
	
	$db->setQuery( $sql );
	if($db->query()) {
		$msg = $dataArr['id'] ? _COMMON_QUERYMSG_UPD_SUS : _COMMON_QUERYMSG_ADD_SUS;
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
		$arrJson['msg'] = $id ? _COMMON_QUERYMSG_UPD_ERR : _COMMON_QUERYMSG_ADD_ERR;
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
		$arrJson['msg'] = _COMMON_QUERYMSG_DEL_SUS;
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
?>