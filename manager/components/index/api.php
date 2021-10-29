<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$chartType = global_get_param( $_POST, 'chartType', null ,0,1  );
switch ($task) {
	case "detail":
	    detail($chartType);
	    break;
}
function amt($chartType){
	global $db;
	
    $today=date("Y-m-d");
	
    $amtType = global_get_param( $_POST, 'amtType', null ,0,1  );
    
    $sdate=getWMDate($amtType);
    $edate=date("Y-m-d",strtotime("+1 day".$today));
    $where_str=" AND buyDate<='$edate' AND buyDate>='$sdate'";
    
    $sql = "select * from orders where status=4 $where_str order by buyDate";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$dataArr=array();
	$labels=array();
	$total=array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		$labels[$d]=date("Y-m-d",strtotime($row['buyDate']));
		$total[$d]+=floatval($row['totalAmt']);
	}
	$labels=dateNum($sdate,$edate,$labels);
	$total=dateNum($sdate,$edate,$total,'value');
	
	$dataArr=getChart($chartType,$labels,$total,_COMMON_AMT);
	$dataArr['type']=!$amtType?'w':$amtType;
	return $dataArr;
}

function view($chartType){
	global $db;
	
    $today=date("Y-m-d");
    
	$viewType = global_get_param( $_POST, 'viewType', null ,0,1  );
	$sdate=getWMDate($viewType);
    $edate=date("Y-m-d",strtotime("+1 day".$today));
    
    $where_str=" AND viewDate<='$edate' AND viewDate>='$sdate'";
    
    $sql = "select * from webviewcnt where 1=1 $where_str order by viewDate";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$dataArr=array();
	$labels=array();
	$total=array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['viewDate']));
		$labels[$d]=date("Y-m-d",strtotime($row['viewDate']));
		$total[$d]+=floatval($row['cnt']);
	}

	$labels=dateNum($sdate,$edate,$labels);
	$total=dateNum($sdate,$edate,$total,'value');
	
	$dataArr=getChart($chartType,$labels,$total,_COMMON_VIEWCNT);
	$dataArr['type']=!$viewType?'w':$viewType;
	return $dataArr;
	
}

function proview($chartType){
	global $db,$conf_user;
	
	$today=date("Y-m-d");
	
	$proviewType = global_get_param( $_POST, 'proviewType', null ,0,1  );
	$sdate=getWMDate($proviewType);
	$sdate0=getWMDate($proviewType,$sdate);
    $where_str=" AND A.viewDate<='".date("Y-m-d",strtotime("+1 day".$today))."' AND A.viewDate>='$sdate'";
    $where_str2=" AND viewDate<'$sdate' AND viewDate>='$sdate0'";
    
	$sql_str1 = "";
	$sql_str2 = "";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str1 .= " B.`name_".$_SESSION[$conf_user]['syslang']."` as `name_".$_SESSION[$conf_user]['syslang']."`, ";
		$sql_str2 .= " , B.`name_".$_SESSION[$conf_user]['syslang']."`  ";
	}
	
    $sql = "
    	select * from (
		    select A.proid,SUM(A.cnt) as cnt,B.name as name, $sql_str1
		    
		    (SUM(A.cnt)-(select SUM(cnt) as cnt from productviewcnt where proid=A.proid $where_str2))/
		    (select SUM(cnt) as cnt from productviewcnt where proid=A.proid $where_str2)*100
		    as discount
		    from productviewcnt A,products B 
		    where 1=1 $where_str AND A.proid=B.id 
		    group by A.proid,B.name $sql_str2
		    	
		) as tbl order by cnt desc limit 6";
	
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$dataArr=array();
	$data=array();
	foreach($r as $key=>$row){
		$info=array();
		$info['proid']=$row['proid'];
		$info['name']=$row['name'];
		
		if($_SESSION[$conf_user]['syslang'] && $row['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$info['name']=$row['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$info['cnt']=$row['cnt'];
		$info['discount']=number_format($row['discount'],1);
		$data[]=$info;
	}
	$dataArr['type']=!$proviewType?'w':$proviewType;
	$dataArr['data']=$data;
	return $dataArr;
}
function procnt($chartType){
	global $db;
	
	$today=date("Y-m-d");
	
	$procntType = global_get_param( $_POST, 'procntType', null ,0,1  );
	$sdate=getWMDate($procntType);
	$edate=date("Y-m-d",strtotime("+1 day".$today));
    $where_str=" AND A.buyDate<='$edate' AND A.buyDate>='$sdate'";
    
    $sql = "
    select * from (
    	select SUM(tab.quantity) as cnt,tab.pid,tab.name from 
			(
				SELECT B.quantity, B.pid, C.name FROM orders A,orderdtl B,products C where status in (1,3,4) $where_str AND A.id=B.oid AND B.pid=C.id
				UNION ALL 
				SELECT OBD.quantity, OBD.productId AS pid, OBD.productName AS name FROM orders A , orderBundle OB , orderBundleDetail OBD WHERE A.id = OB.orderId AND OB.id = OBD.orderBundleId AND A.status in (1,3,4) $where_str
			) AS tab
			 group by tab.pid,tab.name
    	) as tbl order by cnt asc limit 6
    ";
	
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$dataArr=array();
	$labels=array();
	$total=array();
	foreach($r as $key=>$row){
		$pid=$row['pid'];
		$labels[]=$row['name'];
		$total[]=floatval($row['cnt']);
	}
	
	
	
	$labels=dateNum($sdate,$edate,$labels,'',false);
	$total=dateNum($sdate,$edate,$total,'value',false);
		
	$dataArr=getChart($chartType,$labels,$total,_COMMON_SALECNT);
	$dataArr['type']=!$procntType?'w':$procntType;
	return $dataArr;
}

function order($chartType){
	global $db;
	
	$today=date("Y-m-d");
	
	$where_str=" AND buyDate<='".date("Y-m-d",strtotime("+1 day".$today))."' AND buyDate>='$today'";
	$sql = "select * from orders where 1=1 AND combineid=0";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$cnt=0;
	$process=0;
	$order=array();
	foreach($r as $key=>$row){
		$buyDate=$row['buyDate'];
		if($buyDate==$today){
			$cnt++;
		}
		if($row['status']==2){
			$process++;
		}
	}
	
	$order['today']=$cnt;
	$order['process']=$process;
	
	
	
	$orderType = global_get_param( $_POST, 'orderType', null ,0,1  );
	$orderType=!$orderType?'w':$orderType;
	$sdate=getWMDate($orderType);
	$sdate0=getWMDate($orderType,$sdate);
    $where_str=" AND A.buyDate<='".date("Y-m-d",strtotime("+1 day".$today))."' AND A.buyDate>='$sdate'";
    $where_str2=" AND buyDate<'$sdate' AND buyDate>='$sdate0'";
    
    $sql = "
		    select SUM(A.totalAmt) as totalAmt,
		    (SUM(A.totalAmt)-(select SUM(totalAmt) as totalAmt from orders where status in (1,3,4) AND combineid=0 $where_str2))/
		    (select SUM(totalAmt) as totalAmt from orders where status in (1,3,4) AND combineid=0 $where_str2)*100
		    as discount
		    from orders A
		    where status in (1,3,4) $where_str";
	
	$db->setQuery( $sql );
	$r=$db->loadRow();
	
	$data=array();
	$data['targetAmt']=($r['totalAmt']/getFieldValue("select ".strtoupper($orderType)."targetAmt from siteinfo ",strtoupper($orderType)."targetAmt"))*100;
	$data['totalAmt']=number_format($r['totalAmt']);
	$data['discount']=floatval($r['discount']);
	$data['discountStr']=$data['discount']<0?($data['discount']*-1)."% down":$data['discount']."% up";
	
	
	$sql = "
		    select count(1) as cnt,
		    (count(1)-(select count(1) as cnt from orders where status<>6 AND combineid=0 $where_str2))/
		    (select count(1) as cnt from orders where status<>6 AND combineid=0 $where_str2)*100
		    as discount
		    from orders A
		    where status<>6 $where_str";
	
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data['targetOrder']=($r['cnt']/getFieldValue("select ".strtoupper($orderType)."targetOrder from siteinfo ",strtoupper($orderType)."targetOrder"))*100;
	$data['cnt']=number_format($r['cnt']);
	$data['discount2']=floatval($r['discount']);
	$data['discountStr2']=$data['discount2']<0?($data['discount2']*-1)."% down":$data['discount2']."% up";
	
    $where_str=" AND A.regDate<='".date("Y-m-d",strtotime("+1 day".$today))."' AND A.regDate>='$sdate'";
    $where_str2=" AND regDate<'$sdate' AND regDate>='$sdate0'";
	$sql = "
		    select count(1) as cnt,
		    (count(1)-(select count(1) as cnt from members where 1=1 $where_str2))/
		    (select count(1) as cnt from members where 1=1 $where_str2)*100
		    as discount
		    from members A
		    where 1=1 $where_str";
		    
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data['targetMember']=($r['cnt']/getFieldValue("select ".strtoupper($orderType)."targetMember from siteinfo ",strtoupper($orderType)."targetMember"))*100;
	$data['newmember']=number_format($r['cnt']);
	$data['discount3']=floatval($r['discount']);	    
	$data['discountStr3']=$data['discount3']<0?($data['discount3']*-1)."% down":$data['discount3']."% up";
		    
	$where_str=" AND A.viewDate<='".date("Y-m-d",strtotime("+1 day".$today))."' AND A.viewDate>='$sdate'";
    $where_str2=" AND viewDate<'$sdate' AND viewDate>='$sdate0'";
	$sql = "
		    select count(1) as cnt,
		    (count(1)-(select count(1) as cnt from webviewcnt where 1=1 $where_str2))/
		    (select count(1) as cnt from webviewcnt where 1=1 $where_str2)*100
		    as discount
		    from webviewcnt A
		    where 1=1 $where_str";
		    
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data['targetCustom']=($r['cnt']/getFieldValue("select ".strtoupper($orderType)."targetCustom from siteinfo ",strtoupper($orderType)."targetCustom"))*100;
	$data['viewcnt']=number_format($r['cnt']);
	$data['discount4']=floatval($r['discount']);
	$data['discountStr4']=$data['discount4']<0?($data['discount4']*-1)."% down":$data['discount4']."% up";
	
	
	$edate=date("Y-m-d",strtotime("+1 day".$today));
	$where_str=" AND buyDate<='$edate' AND buyDate>='$sdate'";
	$sql = "select * from orders where 1=1 $where_str ";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$labels=array();
	$total=array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		$labels[$d]=date("Y-m-d",strtotime($row['buyDate']));
		$total[$d]+=1;
	}
	$labels=dateNum($sdate,$edate,$labels);
	$total=dateNum($sdate,$edate,$total,'value');
	$data0=getChart($chartType,$labels,$total,_COMMON_SALECNT);
	foreach($data0 as $key=>$row){
		$data[$key]=$row;
	}
	
	$order['type']=$orderType;
	$order['data']=$data;
	
	return $order;
}

function product(){
	global $db,$conf_instock_mode;
	
	$sql = "select * from products where 1=1 AND publish=1";
	
	if($conf_instock_mode=="single"){
		$sql = "select * from products where 1=1 AND publish=1";
	}else if($conf_instock_mode=="multiple"){
		$sql = "select B.* from products A left join proinstock B on A.id=B.pid where 1=1 AND A.publish=1";
	}
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	
	$cnt=count($r);
	$process=0;
	$total=array();
	foreach($r as $key=>$row){
		
		if($row['instock']<$row['safetystock'] || $row['instock']<=0){
			$process++;
		}
	}
	
	$safetystock = intval(getFieldValue(" SELECT safetystock FROM siteinfo" , "safetystock"));
	$process = intval(getFieldValue(" SELECT COUNT(1) AS cnt FROM proinstock A, products B WHERE A.pid = B.id AND B.publish = '1' AND A.instock < '$safetystock'","cnt"));
	
	$total['cnt']=$cnt;
	$total['instock']=$process;
	return $total;
	
}

function detail($chartType){
    global $db;
    
	$alldata=array();
    
    $alldata['amt']=amt($chartType);
    $alldata['view']=view($chartType);
    $alldata['proview']=proview($chartType);
    $alldata['procnt']=procnt($chartType);
    $alldata['order']=order($chartType);
    $alldata['product']=product();
    
	
	JsonEnd(array("status"=>1,"data"=>$alldata));
	
}




include( $conf_php.'common_end.php' ); 
?>