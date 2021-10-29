<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {
	case "list":
	    showlist();
	    break;
	case "detail":
	    detail();
	    break;
	    
}

function showlist(){
	global $db,$globalConf_list_limit,$conf_news,$conf_user;
	
    $arrJson = array();
	
	$page = max(intval(global_get_param( $_REQUEST, 'page', 1 )), 1);
	
	$sql = "SELECT * FROM news where publish=1 AND (newsDate='' OR newsDate<='".date("Y-m-d")."') AND (pubDate='' OR pubDate>='".date("Y-m-d")."') ORDER BY newsDate desc,id desc";	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$cnt = count($r);
	$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
	$page = ($page > $pagecnt) ? $pagecnt : $page;
	
	$from = ($page - 1 ) * $globalConf_list_limit;
	$end = $page * $globalConf_list_limit;
	
	$data = array();
	for($i = $from; $i < min($end, $cnt); $i++) {
		$info=array();
	 	$info['id']=$r[$i]['id'];
	 	$info['name']=$r[$i]['name'];
	 	$info['summary']=$r[$i]['summary'];
		
		if($_SESSION[$conf_user]['syslang'] && $r[$i]['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$info['name']=$r[$i]['name_'.$_SESSION[$conf_user]['syslang']];
		}
		if($_SESSION[$conf_user]['syslang'] && $r[$i]['summary_'.$_SESSION[$conf_user]['syslang']])
		{
			$info['summary']=$r[$i]['summary_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$info['linktype']=$r[$i]['linktype'];
	 	
	 	if($r[$i]['linktype']=="page"){
	 		$info['linkurl']="news_page/{$r[$i]['id']}?cur=$page";
	 	}else if($r[$i]['linktype']=="link"){
	 		$info['linkurl']=$r[$i]['linkurl'];
	 	}
	 	$info['newsM'] = date("m",strtotime($r[$i]['newsDate']));
	 	$info['newsD'] = date("d",strtotime($r[$i]['newsDate']));
	 	$info['imgname'] = getimg("news",$r[$i]['id'])[1];
	 	if(!$info['imgname'])$info['imgname']=$conf_news."default.jpg";
		$data[]=$info;
	 	
	}
	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['cnt'] = $pagecnt;
	JsonEnd($arrJson);
}

function detail(){
	global $db,$conf_upload,$conf_user;
	$arrJson = array();
	$id = intval(global_get_param( $_REQUEST, 'id', 1 ));
	if($id==0){
		
		JsonEnd(array("status"=>0,"msg"=>_NEWS_NO_DATA));
	}
	
	$sql="select * from news where id='$id' AND publish=1";
	$db->setQuery( $sql );
	$r = $db->loadRow();
	if(count($r)==0){
		
		JsonEnd(array("status"=>0,"msg"=>_NEWS_NO_DATA));
	}
	
	$arrJson['status'] = 1;
	$arrJson['data']['name']=$r['name'];
	
	if($_SESSION[$conf_user]['syslang'] && $r['name_'.$_SESSION[$conf_user]['syslang']])
	{
		$arrJson['data']['name']=$r['name_'.$_SESSION[$conf_user]['syslang']];
	}
	
	$arrJson['data']['newsDate']=$r['newsDate'];
	
	if($_SESSION[$conf_user]['syslang'] && $r['content_'.$_SESSION[$conf_user]['syslang']])
	{
		$r['content']=$r['content_'.$_SESSION[$conf_user]['syslang']];
	}
	
	$arrJson['data']['_content']=mb_substr( strip_tags($r['content']),0,150,"utf-8");
	preg_match('/<img[^>]*>/Ui', $r['content'], $content_img); 
	
	preg_match( '@src="([^"]+)"@' , $content_img[0], $contentimg_src );
	$src = array_pop($contentimg_src);
	$imginfo=getimagesize($conf_dir_path."../..".$src);
	$arrJson['data']['_content_img']=$src;
	$arrJson['data']['_imgwidth']=$imginfo[0];
	$arrJson['data']['_imgheight']=$imginfo[1];
	
	$arrJson['data']['content']=$r['content']."<script>
										$(document).ready(
											function(){
												$('#dbpage_content').find('img').addClass('img-responsive');
											}
										);
										
									</script>";
	JsonEnd($arrJson);
}


include( $conf_php.'common_end.php' ); 
?>