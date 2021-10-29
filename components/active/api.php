<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tableName='activeBundle';
switch ($task) {
	case "updateToCart":
        updateToCart();
	    break;
		
	case "activelist":
	    activelist();
	    break;

}

function updateToCart(){
    global $db,$conf_user,$globalConf_list_limit,$tableName;

    $data = global_get_param( $_POST, 'data', null ,0,1  );
    $returnJson=array();
    if(!$data || !$data['active'] || !$data['selectedProductList']){
        $returnJson['status']=0;
    }else{
        
        $now=date('Y-m-d H:i:s');
        $data['active']=intval($data['active']);
        $sql="select * from $tableName where id='{$data['active']}' AND enable=1 AND alive=1 AND (startTime<='$now' OR startTime='') AND (endTime>='$now' OR endTime='')";
        $db->setQuery( $sql );
        $active=$db->loadRow();
        $dataObj=array();
        if($active && count($active)>0){
            
			if($_SESSION[$conf_user]['syslang'] && $active['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$active['name'] = $active['name_'.$_SESSION[$conf_user]['syslang']];
			}
			
			$dataObj=$active;
            $amount=0;
            $dataObj['unique']=md5(microtime(true).mt_rand(1,9));
            foreach($data['selectedProductList'] as $activeBundleDetailId=>$activeBundleDetailRow){
                
				$sql_str = " name ";
				if($_SESSION[$conf_user]['syslang'] )
				{
					$sql_str = " `name_".$_SESSION[$conf_user]['syslang']."` AS name ";
				}
				
				$sql="select sequence,{$sql_str} from activeBundleDetail where sequence='$activeBundleDetailId' AND activeBundleId='{$active['id']}' ORDER BY sequence";
                $db->setQuery( $sql );
                $activeBundleDetail=$db->loadRow();
                $sql="select siteAmt,highAmt,pv,bv from products where id='{$activeBundleDetailRow['productId']}'";
                $db->setQuery( $sql );
                $products=$db->loadRow();
                $activeBundleDetailRow['format']=getProductFormat($activeBundleDetailRow['productId']);
                $activeBundleDetailRow['quantity']=1;
                $activeBundleDetailRow['unique']=md5('p'.microtime(true).mt_rand(1,9));
                $activeBundleDetailRow['siteAmt']=$products['siteAmt']?$products['siteAmt']:$products['highAmt'];
                $activeBundleDetailRow['pv']=$products['pv'];
                $activeBundleDetailRow['bv']=$products['bv'];
                $activeBundleDetail['products']=$activeBundleDetailRow;
                $activeBundleDetail['unique']=md5('d'.microtime(true).mt_rand(1,9));
                $amount+=$activeBundleDetailRow['siteAmt'];
                $dataObj['activeBundleDetail'][]=$activeBundleDetail;
            }
            $dataObj['amount']=$amount;
            $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
            
			
			$findCnt=0;
			foreach($activeBundleCart as $activeKey=>$activeRow){
				if($activeRow['id']==$dataObj['id']){
					++$findCnt;
				}
			}
			
			
			$joinCnt = intval(getFieldValue(" SELECT COUNT(1) AS cnt FROM orders O , orderBundle OB WHERE O.id = OB.orderId AND O.status <> '6' AND OB.activeBundleId = '".$active['id']."' AND memberid = '".$_SESSION[$conf_user]['uid']."' ","cnt"));
			
			if(($findCnt+$joinCnt)<$active['limitCount'] || empty($active['limitCount'])){
				$activeBundleCart[]=$dataObj;
			}else{
				$returnJson['status']=0;
				$returnJson['errorMessage']=_ACTIVE_ACTIVITY_LIMIT;
				JsonEnd($returnJson);
			}
			
            $_SESSION[$conf_user]['activeBundleCart']=$activeBundleCart;
            $returnJson['dataObj']=$dataObj;
            $returnJson['status']=1;
        }else{
            $returnJson['status']=0;
        }
    }
    JsonEnd($returnJson);
}

function activelist(){
	global $db,$conf_user,$globalConf_list_limit,$tableName;
    
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));

    $now=date('Y-m-d H:i:s');
	
	$sql_str = ", name , notes  ";
	if($_SESSION[$conf_user]['syslang'] )
	{
		$sql_str = " , `name_".$_SESSION[$conf_user]['syslang']."` AS name , `notes_".$_SESSION[$conf_user]['syslang']."` AS notes ";
	}
	
    $sql="select id $sql_str from $tableName where enable=1 AND alive=1 AND (startTime<='$now' OR startTime='') AND (endTime>='$now' OR endTime='') order by odring, startTime desc";
    $db->setQuery( $sql );
    $activeList=$db->loadRowList();
    $activeObj=array();
    if($activeList && count($activeList)>0){
        foreach($activeList as $key=>$value){
            if(!$id || $id==$value['id']){
                $activeList[$key]['active']='active';
                $id=$value['id'];
				$value['notes'] = nl2br($value['notes']);
                $activeObj=$value;
            }
        }
    }
	
    $sql="select * from activeBundleDetail where activeBundleId='$id' order by sequence";
    $db->setQuery( $sql );
    $detail=$db->loadRowList();
    foreach($detail as $key=>$value){
        
		if($_SESSION[$conf_user]['syslang'] && $value['name_'.$_SESSION[$conf_user]['syslang']])
		{
			$detail[$key]['name'] = $value['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$sql="select A.*,CONCAT(REPLACE(I.name,'../',''),'?v=',I.version) as imgPath 
            from imglist I ,products A
            where A.publish=1 
            AND exists (select 1 from activeBundleDetail B where B.activeBundleId='$id' AND B.sequence='{$value['sequence']}' AND B.products like CONCAT('%|',A.id,'|%'))
            AND A.id=I.belongid AND I.path='products' AND I.num=1";
        $db->setQuery( $sql );
        $products=$db->loadRowList();
        foreach($products as $pk=>$pv){
			if($_SESSION[$conf_user]['syslang'] && $pv['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$products[$pk]['name'] = $pv['name_'.$_SESSION[$conf_user]['syslang']];
			}
			if($_SESSION[$conf_user]['syslang'] && $pv['var03_'.$_SESSION[$conf_user]['syslang']])
			{
				$products[$pk]['var03'] = $pv['var03_'.$_SESSION[$conf_user]['syslang']];
			}
			$products[$pk]['format']=getProductFormat($pv['id']);
        }
        $detail[$key]['products']=$products;
    }
    


	JsonEnd(array("status"=>1,"data"=>array(array('id'=>'0','name'=>_ACTIVE_TITLE,'active'=>'active','child'=>$activeList)),'detail'=>$detail,'activeObj'=>$activeObj));
}




include( $conf_php.'common_end.php' ); 
?>