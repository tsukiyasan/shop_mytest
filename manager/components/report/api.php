<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );

switch ($task) {
	case "salesdetails":
	    salesdetails();
	    break;
	case "order":
	    order();
	    break;
	case "prorank":
	    prorank();
	    break;
	case "discount":
	    discount();
	    break;
	case "memberrank":
	    memberrank();
	    break;
	case "custom":
	    custom();
	    break;
	case "report_list":
	    report_list();
	    break;
}

function report_list(){
	global $db,$conf_user;
	$data=array();
	$dir=scandir("./");
	$show_list=file_get_contents("./show_list.json");
	$show_list=json_decode($show_list,true);
	foreach($show_list as $name=>$row){
		
		if(is_file("list_".$name.".html")){
			$orderby=array();
			if($row['orderby']){
				foreach($row['orderby'] as $oname=>$by){
					$orderby[]=array("name"=>$oname,"seq"=>$by);
				}
			}
			$data[]=array("value"=>$name,"search_container"=>$row['search'],"orderby"=>$orderby);
		}
	}
	

	JsonEnd(array("status"=>1,"data"=>$data));
}

function salesdetails(){
	global $db,$conf_user;
	
	$today=date("Y-m-d");
	
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	$vsdate=$sdate;
	$vedate=$edate;
	
	$edate0=date("Y-m-d",strtotime("+1 day".$edate));
	
	
	$where_str=" AND buyDate>='$sdate' AND buyDate < '$edate0'";
	$sql = "select buyDate,SUM(totalAmt) as totalAmt,SUM(discount) as discount from orders where 1=1 AND status<>6 AND status<>0 AND combineid=0 $where_str group by buyDate";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$order=array();
	$labels=array();
	$total=array();
	$orderdtl = array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		$order[$d]['buyDate']=$d;
		$order[$d]['totalAmt']+=$row['totalAmt'];
		$total[$d]+=$row['totalAmt'];
		$labels[$d]=$d;
	}
	
	$sql_strP = " P.name  ";
	$sql_str = " name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_strP = " CASE P.`name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN P.name  
						WHEN '' THEN P.name 
						ELSE P.`name_".$_SESSION[$conf_user]['syslang']."` 
					END AS name";
					
		$sql_str = " CASE `name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN name  
						WHEN '' THEN name 
						ELSE `name_".$_SESSION[$conf_user]['syslang']."` 
					END ";
	}
	
	$where_str=" AND O.buyDate>='$sdate' AND O.buyDate < '$edate0'";
	$sql = "select O.id, O.orderNum, O.buyDate, O.status, {$sql_strP}, OD.quantity, 
		( SELECT {$sql_str} FROM proformat WHERE id = OD.format1 ) AS format1name , 
		( SELECT {$sql_str} FROM proformat WHERE id = OD.format2 ) AS format2name , 
		OD.pid, OD.format1, OD.format2 
		from orders O,orderdtl OD , products P where 1=1 AND O.id = OD.oid AND OD.pid = P.id AND O.status<>6 AND O.status<>0 AND O.combineid=0 $where_str ";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$tmp = array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		
		$keyStr = $row['pid'].$row['format1'].$row['format2'];
		$tmp[$d][$keyStr]['salesItem'] = $row['name'].$row['format1name'].$row['format2name'];
		$tmp[$d][$keyStr]['keyStr'] = $keyStr;
		$tmp[$d][$keyStr]['totalItem'] = intval($tmp[$d][$keyStr]['totalItem']) + intval($row['quantity']);
		
		if(empty($tmp[$d][$keyStr]['shipment'])) $tmp[$d][$keyStr]['shipment'] = 0;
		if(empty($tmp[$d][$keyStr]['unShipment'])) $tmp[$d][$keyStr]['unShipment'] = 0;
		
		if($row['status'] == '0' || $row['status'] == '1' || $row['status'] == '9')
		{
			$tmp[$d][$keyStr]['shipment'] += intval($row['quantity']);
			$tmp[$d][$keyStr]['orderNumList_shipment'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
		}
		else
		{
			$tmp[$d][$keyStr]['unShipment'] += intval($row['quantity']);
			$tmp[$d][$keyStr]['orderNumList_unShipment'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
			
		}
		$tmp[$d][$keyStr]['orderNumList'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
	}
	
	$sql_str = " name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str = " CASE `name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN name  
						WHEN '' THEN name 
						ELSE `name_".$_SESSION[$conf_user]['syslang']."` 
					END ";
	}
	
	$sql = "select O.id, O.orderNum, O.buyDate, O.status, P.name, SUM(OBD.quantity) AS quantity, 
		( SELECT {$sql_str} FROM proformat WHERE id = OBD.productFormat1 ) AS format1name , 
		( SELECT {$sql_str} FROM proformat WHERE id = OBD.productFormat2 ) AS format2name, OBD.productId AS pid, OBD.productFormat1 AS format1, OBD.productFormat2 AS format2 
		from orders O,orderBundle OB, orderBundleDetail OBD , products P where 1=1 
		AND O.id = OB.orderId AND OB.id = OBD.orderBundleId AND OBD.productId = P.id AND O.status<>6 AND O.status<>0 AND O.combineid=0 $where_str 
		GROUP BY id, orderNum, buyDate, status, name, format1name, format2name, pid, format1, format2
		";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		
		$keyStr = $row['pid'].$row['format1'].$row['format2'];
		$tmp[$d][$keyStr]['salesItem'] = $row['name'].$row['format1name'].$row['format2name'];
		$tmp[$d][$keyStr]['keyStr'] = $keyStr;
		$tmp[$d][$keyStr]['totalItem'] = intval($tmp[$d][$keyStr]['totalItem']) + intval($row['quantity']);
		
		if(empty($tmp[$d][$keyStr]['shipment'])) $tmp[$d][$keyStr]['shipment'] = 0;
		if(empty($tmp[$d][$keyStr]['unShipment'])) $tmp[$d][$keyStr]['unShipment'] = 0;
		
		if($row['status'] == '0' || $row['status'] == '1' || $row['status'] == '9')
		{
			$tmp[$d][$keyStr]['shipment'] += intval($row['quantity']);
			$tmp[$d][$keyStr]['orderNumList_shipment'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
		}
		else
		{
			$tmp[$d][$keyStr]['unShipment'] += intval($row['quantity']);
			$tmp[$d][$keyStr]['orderNumList_unShipment'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
			
		}
		$tmp[$d][$keyStr]['orderNumList'][] = array("id"=>$row["id"],"orderNum"=>$row['orderNum'],"keyStr"=>$keyStr);
	}
	
	foreach($tmp as $d=>$row)
	{
		foreach($row as $pidfid=>$row2)
		{
			$order[$d]['salesItem'][] = $row2['salesItem'];
			$order[$d]['keyStr'][] = $row2['keyStr'];
			$order[$d]['totalItem'][] = $row2['totalItem'];
			$order[$d]['shipment'][] = $row2['shipment'];
			$order[$d]['unShipment'][] = $row2['unShipment'];
			$order[$d]['emptyList'][] = $row2['emptyList'];
			$order[$d]['orderNumList'][] = $row2['orderNumList'];
			$order[$d]['orderNumList_shipment'][] = $row2['orderNumList_shipment'];
			$order[$d]['orderNumList_unShipment'][] = $row2['orderNumList_unShipment'];
		}
	}
		
	$order=arrayRmKey($order);
	
	$returnArr = array();
	foreach($order as $row)
	{
		$tmp = array();
		$tmp['buyDate'] = $row['buyDate'];
		$tmp['totalAmt'] = $row['totalAmt'];
		$tmp['rowspan'] = count($row['salesItem']);
		foreach($row['salesItem'] as $key=>$pName)
		{
			$tmp['index'] = $key;
			$tmp['pName'] = $pName;
			$tmp['keyStr'] = $row['keyStr'][$key];
			$tmp['totalItem'] = $row['totalItem'][$key];
			$tmp['shipment'] = $row['shipment'][$key];
			$tmp['unShipment'] = $row['unShipment'][$key];
			$tmp['orderNumList'] = $row['orderNumList'][$key];
			$tmp['orderNumList_shipment'] = $row['orderNumList_shipment'][$key];
			$tmp['orderNumList_unShipment'] = $row['orderNumList_unShipment'][$key];
			
			$returnArr[] = $tmp;
			
		}			
	}
	
	
	
	$labels=dateNum($sdate,$edate0,$labels);
	$total=dateNum($sdate,$edate0,$total,'value');
	$dataArr['view']=getChart('chartjs',$labels,$total,_COMMON_AMT);
	$dataArr['type']=!$amtType?'w':$amtType;
	$dataArr['data']=$returnArr;
	
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"data"=>$dataArr));
}

function order(){
	global $db;
	
	$today=date("Y-m-d");
	
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	$vsdate=$sdate;
	$vedate=$edate;
	
	$edate0=date("Y-m-d",strtotime("+1 day".$edate));
	
	
	$where_str=" AND buyDate>='$sdate' AND buyDate < '$edate0'";
	$sql = "select buyDate,SUM(totalAmt) as totalAmt,SUM(discount) as discount from orders where 1=1 AND status<>6  AND combineid=0 $where_str group by buyDate";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$order=array();
	$member=array();
	$labels=array();
	$total=array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		$order[$d]['buyDate']=$d;
		$order[$d]['totalAmt']+=$row['totalAmt'];
		$order[$d]['discount']+=$row['discount'];
		$total[$d]+=$row['totalAmt'];
		$labels[$d]=$d;
	}
	
	
	$where_str=" AND buyDate>='$sdate' AND buyDate < '$edate0'";
	$sql = "select buyDate,memberid from orders where 1=1 AND status<>6  AND combineid=0 $where_str group by buyDate,memberid";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['buyDate']));
		$member[$d][$row['memberid']]=1;
	}
	foreach($order as $d=>$row){
		$cnt=count($member[$d]);
		$order[$d]['custom']=$cnt;
		$order[$d]['unit']=round($order[$d]['totalAmt']/$cnt);
	}
	
	$order=arrayRmKey($order);
	
	$labels=dateNum($sdate,$edate0,$labels);
	$total=dateNum($sdate,$edate0,$total,'value');
	$dataArr['view']=getChart('chartjs',$labels,$total,_COMMON_AMT);
	$dataArr['type']=!$amtType?'w':$amtType;
	$dataArr['data']=$order;
	
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"data"=>$dataArr));
}

function prorank(){
	global $db,$conf_user;
	
	$today=date("Y-m-d");
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	
	
	
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	$vsdate=$sdate;
	$vedate=$edate;
	$where_str=" AND A.buyDate>='$sdate' AND A.buyDate<='$edate'";
	$where_str2=" AND D.viewDate>='$sdate' AND D.viewDate<'$edate'";
	
	$sql_strC = " C.name  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_strC = " CASE C.`name_".$_SESSION[$conf_user]['syslang']."` 
						WHEN null THEN C.name  
						WHEN '' THEN C.name 
						ELSE C.`name_".$_SESSION[$conf_user]['syslang']."` 
					END ";
	}
	
	$sql = "
		select * from (
			select B.pid,$sql_strC as name,SUM(B.quantity) as cnt,SUM(D.cnt) as clickcnt,SUM(B.subAmt) as subAmt 
			from orders A,orderdtl B,products C,productviewcnt D 
			where 1=1 AND A.id=B.oid AND B.pid=C.id AND A.status<>6 AND A.combineid=0 AND D.proid=C.id $where_str $where_str2
			group by B.pid,name,C.click 
		) as tbl
		";
		
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$newcnt=getFieldValue("select count(A.buyDate) as cnt from orders A where 1=1 AND A.status<>6 AND A.combineid=0 $where_str group by A.buyDate","cnt");
	$nowdata=array();
	foreach($r as $key=>$row){
		$pid=$row['pid'];
		$nowdata[$pid]['salecnt']=zeroChk($row['cnt'],$newcnt);
		$nowdata[$pid]['clickcnt']=zeroChk($row['clickcnt'],$newcnt);
		$nowdata[$pid]['saleamt']=zeroChk($row['subAmt'],$newcnt);
		$nowdata[$pid]['name']=$row['name'];
	}
	
	$edate=$sdate;
	$sdate=getWMDate(-14,$edate);
	
	$where_str=" AND A.buyDate>='$sdate' AND A.buyDate<'$edate'";
	$where_str2=" AND D.viewDate>='$sdate' AND D.viewDate<'$edate'";
	$sql = "
		select * from (
			select B.pid,$sql_strC as name,SUM(B.quantity) as cnt,SUM(D.cnt) as clickcnt,SUM(B.subAmt) as subAmt 
			from orders A,orderdtl B,products C,productviewcnt D 
			where 1=1 AND A.id=B.oid AND B.pid=C.id AND A.status<>6 AND A.combineid=0 AND D.proid=C.id $where_str $where_str2
			group by B.pid,C.name,C.click 
		) as tbl
		";
		
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$olddata=array();
	$oldcnt=getFieldValue("select count(A.buyDate) as cnt from orders A where 1=1 AND A.status<>6 AND A.combineid=0 $where_str group by A.buyDate","cnt");
	$data=array();
	foreach($r as $key=>$row){
		$pid=$row['pid'];
		$olddata[$pid]['salecnt']=zeroChk($row['cnt'],$oldcnt);
		$olddata[$pid]['clickcnt']=zeroChk($row['clickcnt'],$oldcnt);
		$olddata[$pid]['saleamt']=zeroChk($row['subAmt'],$oldcnt);
	}
	
	foreach($nowdata as $pid=>$row){
		$nowdata[$pid]['clickcntChg']=zeroChk(zeroChk(($nowdata[$pid]['clickcnt']-$olddata[$pid]['clickcnt']),$olddata[$pid]['clickcnt']),0.01);
		if($nowdata[$pid]['clickcntChg']<0){
			$nowdata[$pid]['clickcntChg']=$nowdata[$pid]['cntChg']*-1;
			$nowdata[$pid]['clickcntChgType']="down";
		}else{
			$nowdata[$pid]['clickcntChgType']="up";
		}
		
		$nowdata[$pid]['cntChg']=zeroChk(zeroChk(($nowdata[$pid]['salecnt']-$olddata[$pid]['salecnt']),$olddata[$pid]['salecnt']),0.01);
		if($nowdata[$pid]['cntChg']<0){
			$nowdata[$pid]['cntChg']=$nowdata[$pid]['cntChg']*-1;
			$nowdata[$pid]['cntChgType']="down";
		}else{
			$nowdata[$pid]['cntChgType']="up";
		}
		
		$nowdata[$pid]['subAmtChg']=zeroChk(zeroChk(($nowdata[$pid]['saleamt']-$olddata[$pid]['saleamt']),$olddata[$pid]['saleamt']),0.01);
		$nowdata[$pid]['subAmtChgAmt']=$nowdata[$pid]['saleamt']-$olddata[$pid]['saleamt'];
		if($nowdata[$pid]['subAmtChg']<0){
			$nowdata[$pid]['subAmtChg']=$nowdata[$pid]['subAmtChg']*-1;
			$nowdata[$pid]['subAmtChgType']="down";
		}else{
			$nowdata[$pid]['subAmtChgType']="up";
		}
	}
	$nowdata=arrayRmKey($nowdata);
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"data"=>$nowdata));
	
}

function discount(){
	global $db;
	
	$today=date("Y-m-d");
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	$vsdate=$sdate;
	$vedate=$edate;
	
	$where_str=" AND A.buyDate>='$sdate' AND A.buyDate<='$edate'";
	$sql = "
			select * from (
				select B.activeid,B.notes as name,SUM(B.discount) as discount,SUM(B.amt) as amt
				from orders A,activeRecord B
				where 1=1 AND A.id=B.orderid AND A.status in (1,3,4) AND B.amt>0 AND B.discount>0 $where_str
				group by B.activeid,B.notes
			) as tbl
			order by discount desc
		";
	
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$data=array();
	foreach($r as $key=>$row){
		$info=array();
		
		$info['percent']=round(($row['discount']/$row['amt'])*1000)/10;
		$info['discount']=$row['discount'];
		$info['name']=$row['name'];
		$data[]=$info;
	}
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"data"=>$data));
}

function memberrank(){
	global $db;
	
	$today=date("Y-m-d");
	$member_name = global_get_param( $_POST, 'member_name', null ,0,1  );
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	$vsdate=$sdate;
	$vedate=$edate;
	$where_str=" AND A.buyDate>='$sdate' AND A.buyDate<='$edate'";
	
	if($member_name){
		$where_str.=" AND B.name like '%$member_name%' ";
	}
	$sql = "
		select * from (
			select B.id,B.name,SUM(A.totalAmt) as amt
			from orders A,members B
			where 1=1 AND A.memberid=B.id AND A.status in (1,3,4) $where_str
			group by B.id,B.name
		) as tbl order by amt desc limit 20
		";
		
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$data=array();
	foreach($r as $key=>$row){
		$info=array();
		
		$info['totalBuy']=$row['amt'];
		$info['name']=$row['name'];
		$data[]=$info;
	}
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"member_name"=>$member_name,"data"=>$data));
	
}

function custom(){
	global $db;
	
	$today=date("Y-m-d");
	
	$sdate = global_get_param( $_POST, 'sdate', null ,0,1  );
	$edate = global_get_param( $_POST, 'edate', null ,0,1  );
	if(!$edate)$edate=$today;
	if(!$sdate)$sdate=getWMDate('w');
	
	$vsdate=$sdate;
	$vedate=$edate;
	$edate=date("Y-m-d",strtotime("+1 day".$edate));
	
	
	$where_str=" AND viewDate>='$sdate' AND viewDate<='$edate'";
	$sql = "select viewDate,cnt from webviewcnt where 1=1 $where_str";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$order=array();
	foreach($r as $key=>$row){
		$d=date("Y-m-d",strtotime($row['viewDate']));
		$order[$d]['date']=$d;
		$order[$d]['custom']+=$row['cnt'];
	}
	
	$order=arrayRmKey($order);
	
	$view=view($sdate,$edate);
	
	JsonEnd(array("status"=>1,"sdate"=>$vsdate,"edate"=>$vedate,"data"=>$order,"view"=>$view));
}

function view($sdate,$edate){
	global $db;
	
	
    
    
    $edate=date("Y-m-d",strtotime("+0 day".$edate));
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
	
	$dataArr=getChart('chartjs',$labels,$total,_COMMON_VIEWCNT);
	$dataArr['type']=!$viewType?'w':$viewType;
	return $dataArr;
	
}

include( $conf_php.'common_end.php' ); 
?>