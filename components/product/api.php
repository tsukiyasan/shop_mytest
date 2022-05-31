<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {
	
	case "detail":
	    detail();
	    break;
		
	case "protypelist":
	    protypelist();
	    break;
		
	case "productlist":
	    productlist();
	    break;

}


function detail(){
	global $db,$conf_dir_path,$conf_url_name,$conf_user;
	$proid = intval(global_get_param( $_REQUEST, 'proid', null ,0,1  ));
	
	if(!empty($proid))
	{
		$sql_str = "";
		$sql_str2 = "";
		$sql_str3 = "";
		if($_SESSION[$conf_user]['syslang'])
		{
			$sql_str .= " `name_".$_SESSION[$conf_user]['syslang']."` , `var03_".$_SESSION[$conf_user]['syslang']."` , ";
			$sql_str2 .= " ,`name_".$_SESSION[$conf_user]['syslang']."` ";
			$sql_strAB .= " , AB.`name_".$_SESSION[$conf_user]['syslang']."` ";
		}
		
		$sql = " SELECT id,name,{$sql_str} instock,highAmt,siteAmt,pv,bv,var03,var04 
		FROM products A
		WHERE A.id = '$proid' and A.publish=1";
		$db->setQuery( $sql );
		$r=$db->loadRow();
		
		if($_SESSION[$conf_user]['syslang'] && $r['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$r['name']=$r['name_'.$_SESSION[$conf_user]['syslang']];
		}
		if($_SESSION[$conf_user]['syslang'] && $r['var03_'.$_SESSION[$conf_user]['syslang']])
		{
			$r['var03']=$r['var03_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$today=date("Y-m-d H:i");
		$db->setQuery("select name {$sql_str2} from active where publish=1 AND ( sdate<='$today' OR sdate='') AND ( edate>='$today' OR edate='') AND (actRangePCode=1 OR var03 like '%||".$proid."||%') ORDER BY odring");
		$ra=$db->loadRowList();
		$actList=array();
		foreach($ra as $row){
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$row['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			$con = explode("1111網購節買",$row['name']);
			if (count($con)>1)
			{
			}
			else
			{
				$actList[]=array("name"=>$row['name']);
			}
		}
		
		
		$today=date("Y-m-d H:i:s");
		$sql = " SELECT DISTINCT AB.id , AB.name {$sql_strAB} FROM activeBundle AB , activeBundleDetail ABD WHERE AB.id = ABD.activeBundleId AND enable = '1' 
		AND ( AB.startTime<='$today' OR AB.startTime='') AND ( AB.endTime>='$today' OR AB.endTime='') AND ABD.products LIKE '%|".$proid."|%' ";
		$db->setQuery($sql);
		$ab=$db->loadRowList();
		foreach($ab as $row){
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$row['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			$actList[]=array("name"=>$row['name'],"url"=>$conf_url_name."/active_list/0?id=".$row['id']);
		}
		
		$r['format']=getProductFormat($proid);
		$r['img']=getimg("products",$proid);
		if(count($actList)>0){
			$r['actList']=$actList;
		}
	}else{
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCT_NO_DATA));
	}
	$r['_content']=mb_substr( strip_tags($r['var03']),0,150,"utf-8");
	$src = explode("?",$r['img'][1]);
	$imgcode=getFieldValue("select code from imglist where path='products' AND num=1 AND belongid='$proid'","code");
	$imginfo=getimagesize($conf_dir_path."../".$src[0]);
	$r['_content_img']=$src;
	$r['_content_imgcode']=$imgcode;
	$r['_imgwidth']=$imginfo[0];
	$r['_imgheight']=$imginfo[1];
	$r['var03']=str_replace("<img","<img class=\"img-responsive\"",$r['var03']);
	JsonEnd(array("status"=>1,"data"=>$r));
}

function createProType($belongid='root',$typeid=0,$usefirstid=0){
	global $db,$conf_user;
	
	if(!$usefirstid){
		$idchk=intval(getFieldValue("select id from producttype where id='$typeid' and publish = '1'","id"));
	}
	
	$uid = intval($_SESSION[$conf_user]['uid']);
	if(!empty($uid)){
		$om = getFieldValue("SELECT onlyMember from members WHERE id = '$uid'","onlyMember");
		$where_str = '';
		if($om == '1'){
			$where_str = " AND for_member = '1'";
		}
	}else{
		$where_str = " AND for_member = '1'";
	}
	
	$sql_str = "";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str .= " `name_".$_SESSION[$conf_user]['syslang']."` , ";
	}
	
	$sql = " SELECT id,name,{$sql_str} pagetype,belongid FROM producttype WHERE publish = '1' AND belongid='$belongid' $where_str ORDER BY treelevel ASC,odring ASC,id ASC";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$dataArr=array();
	if(count($r)>0){
		foreach($r as $key=>$row){
			
			$info = array();
			$info['id'] = $row['id'];
			$info['name'] = $row['name'];
			
			if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			if(($typeid==$row['id'] && $row['pagetype']=="page") || ($usefirstid && $key==0 && $typeid==$belongid)){
				$info['active'] = "active";
			}else if((($typeid==$row['id'] && $row['pagetype']=="dir") || ($idchk==0 && $key==0)) && !$usefirstid){
				if($idchk==0 && $key==0){
					$typeid= $row['id'];
					$_SESSION[$conf_user]['protypeid']=$typeid;
					$_SESSION[$conf_user]['protypebelongid']= $belongid;
				}
				$usefirstid=1;
				
			}
			
			
			$child=createProType($row['id'],$typeid,$usefirstid);
			if($child){
				$info['child']=$child;
				foreach ($child as $val) {
					if ($val['active'] == 'active') {
						$info['active'] = 'active';
					}
				}
			}
			$dataArr[] = $info;
		}
	}
	return $dataArr;
}

function protypelist(){
	global $db;
	
	$typeid = intval(global_get_param( $_REQUEST, 'typeid', null ,0,1  ));
	
	$dataArr=createProType('root',$typeid);
	
	JsonEnd(array("status"=>1,"data"=>$dataArr));
}


function productlist(){
	global $db,$conf_user,$globalConf_list_limit;
	
    $display_type = intval(global_get_param( $_REQUEST, 'display_type', '' ,0,1  ));
    
    if(!$display_type){
    	$display_type=2;
    }

    if($display_type==1)$item_display=12;
    else if($display_type==2)$item_display=16;
    
    
    $typeid = intval(global_get_param( $_REQUEST, 'typeid', null ,0,1  ));
    $searchtext = global_get_param( $_REQUEST, 'searchtext', null ,0,1  );
    
    
    
    $orderType = intval(global_get_param( $_REQUEST, 'orderType', 1 ,0,1  ));

	$cur = intval(global_get_param( $_REQUEST, 'cur', null ,0,1  ));
	if($cur==0)$cur=1;
	
	if(!empty($typeid))
	{
		$where_str = " AND B.ptid='$typeid' ";
	}
	
	if(!empty($searchtext)){
		
		$where_str = "";
		$searcharr=explode(" ",$searchtext);
		foreach($searcharr as $str){
			
			if($_SESSION[$conf_user]['syslang'])
			{
				$where_str.=" AND (A.name like '%$str%' OR A.`name_".$_SESSION[$conf_user]['syslang']."` like '%$str%') ";
			}
			else
			{
				
				$where_str.=" AND (A.name like '%$str%') ";
			}
			
		}
		
	}
	
	
	$where_str .= " AND A.bundleProChk <> '1' ";
	
	if($orderType==1){
		$orderby="id desc,odring";
	}else if($orderType==2){
		$orderby="siteAmt desc,odring";
	}else if($orderType==3){
		$orderby="siteAmt asc,odring";
	}else{
		$orderby="odring,id desc";
	}
	
	if($searchtext) {
		$sql = " SELECT A.*,(select ptid from protype where pid=A.id limit 1) as ptid FROM products A WHERE 1=1 $where_str AND A.publish = '1' order by $orderby";
	} else {
		$sql = " SELECT A.*,B.ptid FROM products A , protype B WHERE A.id = B.pid $where_str AND A.publish = '1' order by $orderby";
	}
	
	$db->setQuery( $sql );
	$row = $db->loadRowList();
	$cnt = count($row);
	$pagecnt = max($cnt % $item_display == 0 ? floor($cnt / $item_display) : floor($cnt / $item_display) + 1, 1);
	$cur = ($cur > $pagecnt) ? 1 : $cur;
	
	$from = ($cur - 1 ) * $item_display;
	$end = $cur * $item_display;
	
	$returnArray = array();
	
	for($i = $from; $i < min($end, $cnt); $i++) {
		$info = array();
		$info['id'] = $row[$i]['id'];
		$info['typeid'] = $row[$i]['ptid'];
		$info['name'] = $row[$i]['name'];
		
		if($_SESSION[$conf_user]['syslang'] && $row[$i]['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$info['name']=$row[$i]['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$info['highAmt'] = $row[$i]['highAmt'];	
		$info['siteAmt'] = $row[$i]['siteAmt'];	
		$info['var03'] = $row[$i]['var03'];	
		$info['promedia'] = $row[$i]['var04'];	
		
		$info['format'] =getProductFormat($row[$i]['id']);
		$info['img']=getimg('products',$info['id'],1);
		
		$returnArray[] = $info;
	}
	
	if(!$typeid){
		$typeid=$info['typeid'];
	}
	
	
	if(!empty($typeid))
	{
		if(!empty($_SESSION[$conf_user]['syslang']))
		{
			$sname = "name_".$_SESSION[$conf_user]['syslang']."";
			$typeName = getFieldValue(" SELECT $sname as name FROM producttype WHERE id = '$typeid'" , "name");
			if($typeName == 'null' || empty($typeName)){
				$typeName = getFieldValue(" SELECT name FROM producttype WHERE id = '$typeid'" , "name");
			}
		}else{
			$typeName = getFieldValue(" SELECT name FROM producttype WHERE id = '$typeid'" , "name");
		}
		
	}
	
	
	

	usleep(100000);
	JsonEnd(array("status"=>1,"data"=>$returnArray,"cnt"=>$pagecnt,"typeName"=>$typeName));
}




include( $conf_php.'common_end.php' ); 
?>