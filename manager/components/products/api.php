<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
$tablename="products";
userPermissionChk($tablename);
switch ($task) {
	case "alllist":
	       showlist('all');
	    break;
	case "alllist2":
	       showlist('all2');
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
	case "odrchg2":	
		odrchg($tablename,$id,null,'odring2');
		break;
	case "operate":	
		operate();
		break;	
		
	case "getTypeList":	
		getTypeList();
		break;	
		
	case "TypeListM_add":	
		TypeListM_add();
		break;	
	
	case "getTypeListM":	
		getTypeListM();
		break;	
		
	case "delInstock":	
		delInstock();
		break;	
		
	case "proCopy":	
		proCopy();
		break;	
	case "imgdel":	
		imgdel();
		break;	
	case "getFormat": 
		getFormat();
		break;	
	case "getProFormat": 
		getProFormat();
		break;	
	case "getProFormat2": 
		getProFormat2();
		break;	
	case "product_import":	
		product_import();
		break;
		
	case "upd_exportChk":	
		upd_exportChk();
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

function upd_exportChk(){
	global $db, $tablename, $conf_user;
	
	
	$printcsv = "";
	$printcsv .= "商品編號(料號),品名-顏色尺寸,(PV),(BV),標準售價\n";
	
	$sql = " SELECT B.code,A.name as pname,C.name as fname1,D.name as fname2,A.pv,A.bv,A.siteAmt FROM products A INNER JOIN proinstock B ON A.id = B.pid LEFT JOIN proformat C ON B.format1 = C.id LEFT JOIN proformat D ON B.format2 = D.id ORDER BY A.odring, A.id desc, B.id   ";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	foreach($r as $row)
	{
		$printcsv .= "\"{$row['code']}\",\"{$row['pname']}-{$row['fname1']}{$row['fname2']}\",\"{$row['pv']}\",\"{$row['bv']}\",\"{$row['siteAmt']}\"\n";
	}
	
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=output.csv");
	
	$content = $printcsv;
	$content = mb_convert_encoding($content , "Big5" , "UTF-8");
	echo $content;
	exit;
	
	
}

function product_import(){
	global $db,$tablename,$conf_dir_path,$conf_upload,$conf_php,$conf_product;
	
	$excelfile = global_get_param( $_POST, 'excelfile', null ,0,1  );
	$zipfile = global_get_param( $_POST, 'zipfile', null ,0,1  );
	if(!$excelfile){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCTS_SELECT_FILE));	
	}
	$fileChk=explode(";base64,",$excelfile);
	$fileChk=$fileChk[0];
	if(strripos($fileChk,"officedocument")===false && strripos($fileChk,"ms-excel")===false){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCTS_EXCEL_FILE));
	}
	
	

	
	$fname=$conf_dir_path.$conf_upload."tmpupd/prolist.xlsx";
	$filteredData = substr($excelfile, strpos($excelfile, ",") + 1);
	$excelfile = base64_decode($filteredData);
	$f=fopen($fname,"w");
	fwrite($f,$excelfile);
	fclose($f);
	require_once ($conf_php.'PHPExcleReader/Classes/PHPExcel/IOFactory.php');
	

	$objPHPExcel = PHPExcel_IOFactory::load($fname);
	
	if(is_file($conf_dir_path.$conf_upload."tmpupd/errpro.csv")){
		unlink($conf_dir_path.$conf_upload."tmpupd/errpro.csv");
	}
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	foreach( $sheetData as $i=>$row)
	{
		if($i != 1)
		{
			$errmsg="";
			
			$procode=trim($row['A']);
			$pronme=trim($row['B']);
			$type1=trim($row['C']);
			$type2=trim($row['D']);
			$f1=trim($row['E']);
			$f2=trim($row['F']);
			$instock=intval(trim($row['G']));
			$group=trim($row['H']);
			$amt=floatval(trim($row['I']));
			$bv=intval(trim($row['K']));
			$pv=intval(trim($row['J']));
			
			
			if($bv < $pv)
			{
				$tmp = $bv;
				$bv = $pv;
				$pv = $tmp;
			}
			
			$bonusChk=intval(trim($row['L']));
			$bonus=intval(trim($row['M']));
			$imgname=trim($row['N']);
			
			if(!$procode){
				$errmsg.="料件編號為空,";
			}
			if(!$pronme){
				$errmsg.="商品名稱為空,";
			}
			if(!$type1){
				$errmsg.="第一層分類為空,";
			}
			if(!$type2){
				$errmsg.="第二層分類為空,";
			}
			if(!$f1){
				$errmsg.="規格一為空,";
			}
			if(!$f2){
				$errmsg.="規格二為空,";
			}
			if(!$group){
				$errmsg.="分群碼為空,";
			}
			if(!$amt){
				$errmsg.="單價為空,";
			}
			

			if(!$errmsg){
				$id=getFieldValue("select A.id from $tablename A
					where A.name=N'$pronme' AND A.var05=N'$group' ","id");
				
				
				if(!$id){
					$sql="insert into $tablename (name,type,siteAmt,highAmt,var05,bv,pv,bonusAmt,bonusChk,publish,treelevel,belongid)
								values (N'$pronme','page','$amt','$amt',N'$group','$bv','$pv','$bonus','$bonusChk',1,1,'root')";
					$db->setQuery($sql);
					$db->query();
					$id=$db->insertid();
				}
				
				$type1id=getFieldValue("select id from producttype where name='$type1'","id");
				$type2id=getFieldValue("select id from producttype where name='$type2'","id");
				if(!$type2id){
					if($type2){
						$odring=intval(getFieldValue("select odring from producttype where belongid='$type1id' order by odring desc","odring"))+1;
						$db->setQuery("insert into producttype (name,belongid,treelevel,pagetype,publish,odring) 
						values 
						(N'$type2','$type1id',2,'page',1,'$odring')");
						
						$db->query();
						$type2id=$db->insertid();
					}else{
						$type2id=$type1id;
					}
				}
				if($type2id){
					$ptChk=getFieldValue("select id from protype where pid='$id' and ptid='$type2id'","id");
					if(!$ptChk){
						$db->setQuery("insert into protype (pid,ptid) values ('$id','$type2id')");
						$db->query();
					}
				}
				
				$f1id=getFieldValue("select id from proformat where belongid=30 and name=N'$f1'","id");
				if(!$f1id){
					$odring=intval(getFieldValue("select odring from proformat where belongid=30 order by odring desc","odring"))+1;
					$db->setQuery("insert into proformat (name,belongid,treelevel,pagetype,publish,odring) values (N'$f1',30,2,'page',1,'$odring')");
					$db->query();
					$f1id=$db->insertid();
				}
				
				$f2id=getFieldValue("select id from proformat where belongid=34 and name=N'$f2'","id");
				if(!$f2id){
					$odring=intval(getFieldValue("select odring from proformat where belongid=34 order by odring desc","odring"))+1;
					$db->setQuery("insert into proformat (name,belongid,treelevel,pagetype,publish,odring) values (N'$f2',34,2,'page',1,'$odring')");
					$db->query();
					$f2id=$db->insertid();
				}
				
				$fChk=getFieldValue("select id from proinstock where format1='$f1id' and format2='$f2id' AND pid='$id'","id");
				if(!$fChk){
					
					$db->setQuery("insert into proinstock (pid,format1_type,format2_type,format1,format2,instock,code) values ('$id',30,34,'$f1id','$f2id','$instock','$procode')");
					$db->query();
				}
				

			}else{
				
				$errmsg=$str=iconv("UTF-8","big5",_PRODUCTS_IMPORT_MSG1."{$i}"._PRODUCTS_IMPORT_MSG2.$procode."-".$pronme.",".$errmsg."\r\n");
				
				$err=fopen($conf_dir_path.$conf_upload."tmpupd/errpro.csv","a+");
				fwrite($err,$errmsg);
				fclose($err);
				
				JsonEnd(array("status"=>1,"errmsg"=>_PRODUCTS_IMPORT_MSG3));
			}
			
		}
	}	
	
	JsonEnd(array("status"=>1,"msg"=>_PRODUCTS_IMPORT_MSG4));
}

function getFormat(){
	global $db,$tablename,$conf_user;
	
	$data = array();
	
	$sql_str1 = " A.name ";
	$sql_str2 = " B.name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str1 = " A.name AS nameA , A.`name_".$_SESSION[$conf_user]['syslang']."`  ";
		$sql_str2 = " B.name AS nameB , B.`name_".$_SESSION[$conf_user]['syslang']."`  ";
	}
	
	$notshowid = intval(global_get_param( $_REQUEST, 'notshowid', null ,0,1  ));
	$sql="select A.id as mid,$sql_str1 as mname,B.id as id,$sql_str2 as name from proformat A,proformat B where A.pagetype='dir' AND A.treelevel=1 AND A.publish=1 AND A.id=B.belongid AND B.publish=1";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$dtl=array();
	foreach($r as $key=>$row){
		if($row['mid']!=$notshowid){
			$info=array();
		 	$info['mid']=$row['mid'];
		 	$info['mname']=(!empty($row['mname'])) ? $row['mname'] : $row['nameA'];
			$data[$info['mid']]=$info;
			$info=array();
		 	$info['id']=$row['id'];
		 	$info['name']=(!empty($row['name'])) ? $row['name'] : $row['nameB'];
			$dtl[$row['mid']][]=$info;
			
		}
	}
	
	
	$arrJson['status'] = 1;
	$arrJson['data'] = $data;
	$arrJson['dtl'] = $dtl;
	
	JsonEnd($arrJson);
	
}

function getProFormat(){
	global $db,$tablename,$conf_user;
	
	$proid = intval(global_get_param( $_REQUEST, 'proid', null ,0,1  ));
	if($proid==0){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCT_NO_DATA));
	}
	
	$sql_str2 = " B.name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str2 = " B.name AS nameB , B.`name_".$_SESSION[$conf_user]['syslang']."`  ";
	}
	
	$arrJson=array();
	$sql = " SELECT A.id,A.instock,A.format1,A.format1_type,$sql_str2 as format1_name
				FROM proinstock A,proformat B WHERE A.pid = '$proid' AND A.format1=B.id";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$format1_type="";
	$format1= array();
	if(count($r) > 0)
	{
		foreach($r as $row)
		{
			$info = array();
			$info['id'] = $row['id'];
			$info['format1'] = $row['format1'];
			$info['instock'] = $row['instock'];
			
			$row['format1_name']=(!empty($row['format1_name'])) ? $row['format1_name'] : $row['nameB'];
			
			$format1_type=$row['format1_type'];
			$format1[$row['format1']]=$row['format1_name'];
		}
	}
	
	$arrJson['format1_type']['mid']=$format1_type;
	$arrJson['format1']=$format1;
	
	JsonEnd(array("status"=>1,"data"=>$arrJson));	
}
function getProFormat2(){
	global $db,$tablename,$conf_user;
	
	$proid = intval(global_get_param( $_REQUEST, 'proid', null ,0,1  ));
	if($proid==0){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCT_NO_DATA));
	}
	
	$sql_str2 = " B.name ";
	$sql_str3 = " C.name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str2 = " B.name AS nameB , B.`name_".$_SESSION[$conf_user]['syslang']."`  ";
		$sql_str3 = " C.name AS nameC , C.`name_".$_SESSION[$conf_user]['syslang']."`  ";
	}
	
	$arrJson=array();
	$sql = " SELECT A.id,A.instock,A.format1,A.format1_type,A.format2,A.format2_type,$sql_str2 as format1_name,$sql_str3 as format2_name,A.code as procode 
			 FROM proformat B,proinstock A left join proformat C on  A.format2=C.id WHERE A.pid = '$proid' AND A.format1=B.id";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$format2_type="";
	$format2= array();
	$format2_instock= array();
	$format2_procode= array();
	if(count($r) > 0)
	{
		foreach($r as $row)
		{
			$info = array();
			$info['id'] = $row['id'];
			$info['format1'] = $row['format1'];
			$info['instock'] = $row['instock'];
			$info['procode'] = $row['procode'];
			$info['format2'] = $row['format2'];
			
			$row['format1_name']=(!empty($row['format1_name'])) ? $row['format1_name'] : $row['nameB'];
			$row['format2_name']=(!empty($row['format2_name'])) ? $row['format2_name'] : $row['nameC'];
			
			$format1_type=$row['format1_type'];
			$format2_type=$row['format2_type'];
			$format1[$row['format1']]=$row['format1_name'];
			if(!$row['format2']){
				$row['format2']=$row['format1'];
				$row['format2_name']=$row['format1_name'];
				$row['format2_name']=$row['format1_name'];
				$format2[$row['format2']]=$row['format2_name'];
				$format2_instock[$row['format2']]=$row['instock'];
				$format2_procode[$row['format2']]=$row['procode'];
			}else{
				$format2[$row['format1']][$row['format2']]=$row['format2_name'];
				$format2_instock[$row['format1']][$row['format2']]=$row['instock'];
				$format2_procode[$row['format1']][$row['format2']]=$row['procode'];
			}
		}
	}
	
	$arrJson['format2_type']['mid']=$format2_type;
	$arrJson['format2']=$format2;
	$arrJson['format2_instock']=$format2_instock;
	$arrJson['format2_procode']=$format2_procode;
	
	JsonEnd(array("status"=>1,"data"=>$arrJson));	
}

function imgdel(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$num = intval(global_get_param( $_REQUEST, 'num', null ,0,1  ));
	
	delimg($tablename,$id,$num);
	
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}

function proCopy()
{
	global $db,$tablename,$conf_user,$conf_product,$conf_dir_path;
	
	$uid = $_SESSION[$conf_user]['uid'];
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	
	if(!empty($id))
	{
		$sql = " SELECT * FROM $tablename WHERE id = '$id'";
		$db->setQuery( $sql );
		$r=$db->loadRow();
		
		if(count($r) > 0)
		{
			$updatesql = "INSERT INTO $tablename (name,belongid,treelevel,publish,hotChk,var03,instock,highAmt,siteAmt,oriAmt,ctime,mtime,muser) VALUES ";
			$updatevalue = "(N'{$r['name']}','{$r['belongid']}','{$r['treelevel']}','0','0',N'{$r['var03']}','{$r['instock']}','{$r['highAmt']}','{$r['siteAmt']}','{$r['oriAmt']}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
			
			$db->setQuery( $updatesql.$updatevalue );
			$db->query();
			
			$newid=$db->insertid();
						
			$sql = " SELECT * FROM imglist WHERE belongid = '$id' AND path = 'products'";
			$db->setQuery( $sql );
			$rs=$db->loadRowList();
			if(count($rs) > 0)
			{
				foreach($rs as $row)
				{
					
					if(is_file($conf_dir_path.$row['name'])){
						copy($conf_dir_path.$row['name'] , $conf_dir_path.$conf_product.$newid."_".$row['num'].".jpg");
												
						$sql = " INSERT INTO imglist ( belongid, num, path, name, code) VALUES 
							( '{$newid}', N'{$row['num']}', '{$row['path']}', '".$conf_product.$newid."_".$row['num'].".jpg','".md5($newid.$row['num'].$tablename)."')";
						$db->setQuery( $sql );
						$db->query();
					}
				}
			}
			
			
			
			$sql = " SELECT * FROM proinstock WHERE pid = '$id'";
			$db->setQuery( $sql );
			$rs=$db->loadRowList();
			if(count($rs) > 0)
			{
				foreach($rs as $row)
				{
					$sql = " INSERT INTO proinstock ( pid, name, instock, ctime, mtime, muser) VALUES 
					( '{$newid}', N'{$row['name']}', '{$row['instock']}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
					
					$db->setQuery( $sql );
					$db->query();
				}
			}
			
			
			$sql = " SELECT * FROM protype WHERE pid = '$id'";
			$db->setQuery( $sql );
			$rs=$db->loadRowList();
			if(count($rs) > 0)
			{
				foreach($rs as $row)
				{
					$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
						( '{$newid}', '{$row['ptid']}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
					
					$db->setQuery( $sql );
					$db->query();
				}
			}
		}		
	}
	
	JsonEnd(array("status"=>1,"msg"=>_PRODUCTS_COPY_SUCCESS));
}


function delInstock()
{
	global $db,$tablename,$conf_user;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	
	if(!empty($id))
	{
		
		$sql = "delete from proinstock where id ='$id'";		
		$db->setQuery( $sql );
		$db->query();
	}
	
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_DEL_SUS));
}

function getTypeList()
{
	global $db,$tablename,$conf_user;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	
	$sql_str = " name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str = " name AS nameO , `name_".$_SESSION[$conf_user]['syslang']."` AS name  ";
	}
	
	$sql = " SELECT id,belongid,treelevel,pagetype, $sql_str ,odring FROM producttype WHERE 1=1 ORDER BY treelevel, odring, id"; 
	
	
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$master = array();
	$detail = array();
	$tmp = array();
	if(count($r) > 0)
	{
		foreach($r as $row)
		{
			$row['name']=(!empty($row['name'])) ? $row['name'] : $row['nameO'];
			
			if($row['belongid'] == 'root')
			{
				$master[] = $row;
				$tmp[$row['id']] = $row['id'];
			}
			else
			{
				$detail[$row['belongid']][] = $row;
				
				$tmp[$row['id']] = $row['belongid'];
			}
		}
	}
	
	
	$selected = array();
	if(!empty($id))
	{
		$sql = " SELECT * FROM protype WHERE pid = '$id'";
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		if(count($r) > 0)
		{
			foreach($r as $row)
			{
				$selected[$tmp[$row['ptid']]][] = $row['ptid'];
			}
		}
	}
	
	
	$pvbvratio = getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
		
	JsonEnd(array("status"=>1,"master"=>$master,"detail"=>$detail,"selected"=>$selected,"pvbvratio"=>$pvbvratio,"msg"=>"操作成功"));
}

function getTypeListM()
{
	global $db,$tablename,$conf_user;
	
	$data = array();
	
	$data[] = array( "id"=>"root","name"=>_PRODUCTS_ROOT);
	
	$sql_str = " name ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str = " name AS nameO , `name_".$_SESSION[$conf_user]['syslang']."` AS name  ";
	}
	
	$sql = " SELECT id,$sql_str FROM producttype WHERE belongid = 'root' AND pagetype = 'dir' AND publish = '1' ORDER BY odring, id ";
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	if(count($r) > 0)
	{
		foreach($r as $row)
		{
			$info = array();
			$info['id'] = $row['id'];
			$info['name'] = (!empty($row['name'])) ? $row['name'] : $row['nameO'];
			$data[] = $info;
		}
	}
	
	JsonEnd(array("status"=>1,"data"=>$data,"msg"=>"操作成功"));
}

function TypeListM_add()
{
	global $db,$tablename,$conf_user;
	
	$uid = $_SESSION[$conf_user]['uid'];
	
	$sql_str1 = " name ";
	$sql_str2 = " N'{$name}' ";
	if($_SESSION[$conf_user]['syslang'])
	{
		$sql_str1 = " name  , `name_".$_SESSION[$conf_user]['syslang']."`   ";
		$sql_str2 = "  N'{$name}' , N'{$name}'  ";
	}
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	if(!empty($id) && !empty($name))
	{
		if($id == 'root')
		{
			$sql = " INSERT INTO producttype ( belongid, treelevel, pagetype, publish, $sql_str1 , odring, click, ctime, mtime, muser) VALUES 
				( 'root', '1', 'page', '1', $sql_str2 , 0, 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
		}
		else
		{
			$sql = " INSERT INTO producttype ( belongid, treelevel, pagetype, publish, $sql_str1 , odring, click, ctime, mtime, muser) VALUES 
				( '{$id}', '2', 'page', '1', $sql_str2 , 0, 0, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
		}
		
		$db->setQuery( $sql );
		$db->query();
	}
	
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));	
}



function operate(){
	global $db,$tablename,$conf_user;
	$idarr = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		
		JsonEnd(array("status"=>0,"msg"=>_ADMINMANAGERS_NO_SELECT));
	}
	$id=implode(",",$idarr);
	$field="";
	if($action==1){
		$field="publish='1'";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==2){
		$field="publish='0'";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==3){
		$sql="";
		foreach($idarr as $value){
			$sql.="delete from $tablename where belongid ='$value';";
		}
		$sql.="delete from $tablename where id in ($id);";
	}
	
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}
function publishChg(){
	global $db,$tablename;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
	
	$sql="update $tablename set publish='$publish' where id='$id'";
	$db->setQuery( $sql );
	$db->query();
	JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_UPD_SUS));
	
}
function del(){
	global $db,$tablename,$conf_user;
	
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	
	
	$sql = " SELECT count(1) as cnt FROM orderdtl WHERE pid = '$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	if($r['cnt']>0){
		
		JsonEnd(array("status"=>0,"msg"=>_PRODUCTS_DELETE_ERROR));
	}else{
		
		
		$sql = " SELECT * FROM imglist WHERE belongid = '$id' AND path = 'products'";
		$db->setQuery( $sql );
		$rs=$db->loadRowList();
		if(count($rs) > 0)
		{
			foreach($rs as $row)
			{
				delimg($tablename,$id,$row['num']);
			}
		}
		$sql="DELETE FROM imglist WHERE belongid = '$id' AND path = 'products'";
		$db->setQuery( $sql );
		$db->query();
		
		
		$sql="DELETE FROM proinstock WHERE pid = '$id'";
		$db->setQuery( $sql );
		$db->query();
		
		
		
		$sql="DELETE FROM protype WHERE pid = '$id'";
		$db->setQuery( $sql );
		$db->query();
		
		
		$sql="delete from $tablename where id='$id'";
		$db->setQuery( $sql );
		$db->query();
		JsonEnd(array("status"=>1,"msg"=>_COMMON_QUERYMSG_DEL_SUS));
	}
}

function detail(){
	global $db,$tablename,$template_option,$conf_dir_path,$conf_product;
	$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
	$instockMode = global_get_param( $_REQUEST, 'instockMode', null ,0,1  );
	$sql = "select * from $tablename where id='$id'";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=[];
	foreach($r as $key=>$row){
	    if(!is_numeric($key)){
	        $data[$key]=$row;
	        if($key=="publish"){
	        	$data[$key]=$row;
	        }
			elseif($key=="newDate"){
				if(empty($row) || $row == "0000-00-00")
				{
					$row = date("Y-m-d",strtotime($r['ctime']));
				}
				$data[$key]=$row;
	        }
	    }
	}
		
	
	

	
	$textList = getLanguageList("text");
	$nameList = array();
	$var03List = array();
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameList[$row['code']] = $data["name_".$row['code']];
			$var03List[$row['code']] = $data["var03_".$row['code']];
		}
	}
	
	$imglist=getimg($tablename,$id);
	foreach($imglist as $num=>$value){
		$data['var'][$num]=$data["var{$num}"];
		$data['img'][$num]=$value;
	}
	
	
	$data['url']=urlencode("http://".$_SERVER['HTTP_HOST']."/".$template_option."page/");
	$arrJson['data'] = $data;
	$arrJson['nameList'] = $nameList;
	$arrJson['var03List'] = $var03List;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
}
function dirdb(){
	global $db,$tablename;
	
	$id = global_get_param( $_REQUEST, 'id', null ,0,1  );
	$belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
	
	$level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$name = global_get_param( $_REQUEST, 'name', null ,0,1  );
	$publish = global_get_param( $_REQUEST, 'publish', null ,0,1  );
	
	$updatesql = "INSERT INTO $tablename (id,name,belongid,treelevel,publish,pagetype) VALUES ";
	$updatevalue = "('$id',N'$name','$belongid','$level','$publish','dir')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),publish=VALUES(publish)";
		
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
    global $db,$tablename,$real_page,$template_option,$conf_user,$conf_dir_path,$conf_product;
    $cur = intval(global_get_param( $_REQUEST, 'page', null ,0,1  ));
    if($cur==0)$cur=1;
	
	 
    $bonusChk = global_get_param( $_REQUEST, 'bonusChk', null ,0,1  );
    $freeProChk = global_get_param( $_REQUEST, 'freeProChk', null ,0,1  );
    $amtProChk = global_get_param( $_REQUEST, 'amtProChk', null ,0,1  );
    $data['belongid'] = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    $data['level'] = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
    if($data['belongid']==0)$data['belongid']="root";
	
	
	$instockMode = global_get_param( $_REQUEST, 'instockMode', null ,0,1  );
	
	if($instockMode == 'multiple')
	{
		$sql = " SELECT pid , SUM(instock) as sum FROM proinstock GROUP BY pid";
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		$sum_arr = array();
		if(count($r) > 0)
		{
			foreach($r as $row)
			{
				$sum_arr[$row['pid']] = (int)$row['sum'];
			}
		}
	}
	
	$data['ptid'] = intval(global_get_param( $_REQUEST, 'ptid', null ,0,1  ));
	$ptid=$data['ptid'];
    $search_str=strval(global_get_param( $_REQUEST, 'search_str', null ,0,1  ));
    $where_str="";
    if($search_str){
    	
		$textList = getLanguageList("text");
		if($textList && count($textList) > 0)
		{
			$tmpStr = "";
			foreach($textList as $row)
			{
				if(!empty($tmpStr))
					$tmpStr .= " OR ";
				$tmpStr .= " A.`name_".$row['code']."` like '%$search_str%' ";
			}
			if(!empty($tmpStr))
				$where_str .=  " AND (".$tmpStr.")";
		}
		else
		{
			$where_str.=" AND A.name like '%$search_str%'";
		}
    }
    
    if($bonusChk){
    	$where_str.=" AND bonusChk='$bonusChk'";
    }
    if($freeProChk){
    	$where_str.=" AND freeProChk='$freeProChk'";
    }
    if($amtProChk){
    	$where_str.=" AND amtProChk='$amtProChk'";
    }
    
    if($mode=='all2'){
    	$order_str = " order by A.odring2, A.id desc ";
    }
    else{
    	$order_str = " order by A.odring, A.id desc ";
    }
	
	if(!empty($data['ptid']))
	{
		
		$sql = " SELECT * FROM producttype WHERE id='{$data['ptid']}' OR belongid='{$data['ptid']}'";
		$db->setQuery( $sql );
		$r=$db->loadRowList();
		$ptid_arr = array();
		if(count($r) > 0)
		{
			foreach($r as $row)
			{
				$ptid_arr[] = $row['id'];
			}
		}
		
		$sql_str = "";
		if($_SESSION[$conf_user]['syslang'])
		{
			$sql_str .= " A.`name_".$_SESSION[$conf_user]['syslang']."` , ";
		}
		
		$sql = "select A.id, A.name,{$sql_str} A.belongid, A.publish, A.treelevel, A.odring, A.highAmt, A.bonusAmt, A.instock ".
			" FROM $tablename A , protype B where A.id = B.pid AND B.ptid IN ('".implode("','",$ptid_arr)."') AND A.belongid='{$data['belongid']}' $where_str ".
			" GROUP BY A.id, A.name,{$sql_str} A.belongid, A.publish, A.treelevel, A.odring, A.highAmt, A.bonusAmt, A.instock $order_str";		
	}
	else
	{
		$sql = "select A.* from $tablename A where A.belongid='{$data['belongid']}' $where_str $order_str";
	}
	
	$db->setQuery( $sql );
	$r=$db->loadRowList();
	$cnt=count($r);
	$pagecnt=max(ceil($cnt/10),1);
	$cur = ($cur > $pagecnt) ? $pagecnt : $cur;
	$from=($cur-1)*10;
	if($cnt>0){
		if($mode!='all' && $mode!='all2'){
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
		 	$info['publish']=$row['publish'];
		 	$info['level']=intval($row['treelevel']);
		 	$info['odring']=intval($row['odring']);
		 	$info['odring2']=intval($row['odring2']);
		 	
			$info['highAmt'] = number_format(floatval($row['highAmt']), 2, '.', ','); 
			$info['bonusAmt'] = number_format(intval($row['bonusAmt']), 0, '.', ','); 
			
			if($instockMode == 'multiple')
			{
				$info['instock'] = intval($sum_arr[$row['id']]); 
			}
			else
			{
				$info['instock'] = intval($row['instock']); 
			}
			
			$imglist=getimg($tablename,$info['id']);	
			foreach($imglist as $var)
			{
				$info['img'] = $var;
				break;
			}
			
			$data[]=$info;
		}
	}
	$backid=getFieldValue("select belongid from $tablename where id='{$info['belongid']}'","belongid");
	
	
	
	$db->setQuery("select id,name from producttype where publish=1 and treelevel=1 order by odring,id");
	$producttype=$db->loadRowList();
	$nowtype=array();
	foreach($producttype as $row){
		if($row['id']==$ptid){
			$nowtype=array("id"=>$row['id'],"name"=>$row['name']);
		}
	}
	
	
	JsonEnd(array("status"=>1,"data"=>$data,"backid"=>$backid,"cnt"=>$pagecnt,"typelist"=>$producttype,"nowtype"=>$nowtype));
	
}
function pagedb(){
    global $db,$tablename,$conf_product,$conf_user;
    
	$uid = $_SESSION[$conf_user]['uid'];
	
    $data = array();
    $id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
    $belongid = global_get_param( $_REQUEST, 'belongid', null ,0,1  );
    // $name = global_get_param( $_REQUEST, 'name', null ,0,1,1,'',_COMMON_PARAM_NAME  );
    $publish = intval(global_get_param( $_REQUEST, 'publish', null ,0,1  ));
    $need_tax = intval(global_get_param( $_REQUEST, 'need_tax', null ,0,1  ));
    $hotChk = global_get_param( $_REQUEST, 'hotChk', null ,0,1  );
    $usebonus = global_get_param( $_REQUEST, 'usebonus', null ,0,1  );
    $var03 = global_get_param( $_REQUEST, 'var03', null ,0,0  );
    $var04 = global_get_param( $_REQUEST, 'var04', null ,0,1  );
    $var05 = global_get_param( $_REQUEST, 'var05', null ,0,1  );
    $level = intval(global_get_param( $_REQUEST, 'level', null ,0,1  ));
	$img = global_get_param( $_REQUEST, 'img', null );
	
    $safetystock = intval(global_get_param( $_REQUEST, 'safetystock', null ,0,1  ));
    $instock = intval(global_get_param( $_REQUEST, 'instock', null ,0,1  ));
    $highAmt = floatval(global_get_param( $_REQUEST, 'highAmt', null ,0,1  ));
    $siteAmt = floatval(global_get_param( $_REQUEST, 'siteAmt', null ,0,1  ));
    if(!$siteAmt)$siteAmt=$highAmt;
    
    $oriAmt = floatval(global_get_param( $_REQUEST, 'oriAmt', null ,0,1  ));
    $proTypeArrays = global_get_param( $_REQUEST, 'proTypeArrays', null ,0,1  );
    $proNum_arr = global_get_param( $_REQUEST, 'proNum_arr', null ,0,1  );
    $format1_arr = global_get_param( $_REQUEST, 'format1_arr', null ,0,1  );
    $format2_arr = global_get_param( $_REQUEST, 'format2_arr', null ,0,1  );
    $checked2_instock = global_get_param( $_REQUEST, 'checked2_instock', null ,0,1  );
    $checked2_procode = global_get_param( $_REQUEST, 'checked2_procode', null ,0,1  );
    
	//新增檢查不可為空
	foreach ($checked2_procode as $k => $v) {
		foreach ($v as $k2 => $v2) {
			if(empty($v2)){
				$msg = _CODE_EMPTY;
				JsonEnd(array("status"=>0,"msg"=>$msg));
			}
		}
	}
	

    $amtProChk = intval(global_get_param( $_REQUEST, 'amtProChk', null ,0,1  ));
    $amtProAmt = floatval(global_get_param( $_REQUEST, 'amtProAmt', null ,0,1  ));
    $freeProChk = intval(global_get_param( $_REQUEST, 'freeProChk', null ,0,1  ));
    $bundleProChk = intval(global_get_param( $_REQUEST, 'bundleProChk', null ,0,1  ));
    $bonusChk = intval(global_get_param( $_REQUEST, 'bonusChk', null ,0,1  ));
    $bonusAmt = intval(global_get_param( $_REQUEST, 'bonusAmt', null ,0,1  ));
    $pv = (global_get_param( $_REQUEST, 'pv', null ,0,1  ));
    $bv = (global_get_param( $_REQUEST, 'bv', null ,0,1  ));
	$ccv = intval(global_get_param( $_REQUEST, 'ccv', null ,0,1  ));
	
	$forTW = intval(global_get_param( $_REQUEST, 'forTW', null ,0,1  ));
	$nccbChk = intval(global_get_param( $_REQUEST, 'nccbChk', null ,0,1  ));
	$notomChk = intval(global_get_param( $_REQUEST, 'notomChk', null ,0,1  ));

	$newDate = global_get_param( $_REQUEST, 'newDate', null ,0,1  );
	$newDate = (!empty($newDate)) ? date("Y-m-d",strtotime($newDate)) : "0000-00-00";
	
	$need_tax = '0';
	
	$updatesql_addStr = "";
	$updatevalue_addStr = "";
	$updatesqlend_addStr = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = global_get_param( $_REQUEST, 'name_'.$row['code'], null ,0,0  );
			if(empty($name))
			{
				$name = $nameTmp;
			}
			$updatesql_addStr .= " `name_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$nameTmp', ";
			$updatesqlend_addStr .= " `name_".$row['code']."`=VALUES(`name_".$row['code']."`), ";
			
			$var03Tmp = global_get_param( $_REQUEST, 'var03_'.$row['code'], null ,0,0  );
			if(empty($var03))
			{
				$var03 = $var03Tmp;
			}
			$updatesql_addStr .= " `var03_".$row['code']."` , ";
			$updatevalue_addStr .= " N'$var03Tmp', ";
			$updatesqlend_addStr .= " `var03_".$row['code']."`=VALUES(`var03_".$row['code']."`), ";
		}
	}
	
	$now=date("Y-m-d H:i:s");
    $updatesql = "INSERT INTO $tablename (id,name,{$updatesql_addStr} bonusChk,bonusAmt,belongid,treelevel,type,publish,hotChk,newDate,usebonus,var03,var04,var05,proCode,safetystock,pv,bv,ccv,instock,highAmt,siteAmt,oriAmt,amtProChk,amtProAmt,freeProChk,bundleProChk,nccbChk,ctime,mtime,muser,need_tax,forTW,notomChk) VALUES ";
	$updatevalue = "('$id',N'$name',{$updatevalue_addStr} '$bonusChk','$bonusAmt','$belongid','$level','page','$publish','$hotChk','$newDate','$usebonus',N'$var03','$var04',N'$var05',N'$proCode','$safetystock','$pv','$bv','$ccv','$instock','$highAmt','$siteAmt','$oriAmt','$amtProChk','$amtProAmt','$freeProChk','$bundleProChk','$nccbChk','$now','$now','$uid','$need_tax','$forTW','$notomChk')";
	$updatesqlend = " ON DUPLICATE KEY UPDATE name=VALUES(name),{$updatesqlend_addStr} bonusChk=VALUES(bonusChk),bonusAmt=VALUES(bonusAmt),publish=VALUES(publish),var05=VALUES(var05),hotChk=VALUES(hotChk),newDate=VALUES(newDate),usebonus=VALUES(usebonus),var03=VALUES(var03),var04=VALUES(var04),proCode=VALUES(proCode),safetystock=VALUES(safetystock),pv=VALUES(pv),bv=VALUES(bv),ccv=VALUES(ccv),instock=VALUES(instock),highAmt=VALUES(highAmt),siteAmt=VALUES(siteAmt),oriAmt=VALUES(oriAmt),amtProChk=VALUES(amtProChk),amtProAmt=VALUES(amtProAmt),freeProChk=VALUES(freeProChk),bundleProChk=VALUES(bundleProChk),nccbChk=VALUES(nccbChk),mtime=VALUES(mtime),muser=VALUES(muser),need_tax=VALUES(need_tax),forTW=VALUES(forTW),notomChk=VALUES(notomChk)";
		
	if($id==0){
		$msg=_COMMON_QUERYMSG_ADD_SUS;
	}else{
		$msg=_COMMON_QUERYMSG_UPD_SUS;
	}
	
	$db->setQuery( $updatesql.$updatevalue.$updatesqlend );
	$db->query();
	
	
	if(!$id){
		$id=$db->insertid();
	}
	
	if(count($img)>0){
		foreach($img as $key=>$value){
			if($value){
				$path=$id."_".$key.".jpg";
				imgupd($value,$conf_product.$path,$tablename,$id,$key);
			}
		}
	}
		
	

	
	if(count($proTypeArrays) > 0)
	{
		
		$sql = "delete from protype where pid ='$id'";		
		$db->setQuery( $sql );
		$db->query();
		$ptid_arr = array();
		foreach($proTypeArrays as $row)
		{
			if($row[0] == 'dir')
			{
				// JsonEnd(array('status' => '0','m' => $proTypeArrays,'r' => $row));
				if(is_array($row[3]) && !empty($row[3][0]))
				{
					if(!in_array($row[3][0] , $ptid_arr))
					{
						$ptid_arr[] = $row[3][0];
						
						
						$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
						( '{$id}', '{$row[3][0]}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
						
						$db->setQuery( $sql );
						$db->query();
					}
					
					
				}
				
				if(is_array($row[6]))
				{					
					
					foreach($row[6] as $ptid)
					{
						if(!in_array($ptid , $ptid_arr))
						{
							$ptid_arr[] = $ptid;
							
							$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
							( '{$id}', '{$ptid}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
							
							$db->setQuery( $sql );
							$db->query();
						}
					}
				}
			}
			elseif($row[0] == 'page')
			{
				if(is_array($row[3]))
				{
					if(!in_array($row[3] , $ptid_arr))
					{
						$ptid_arr[] = $row[3];
						
						
						$sql = " INSERT INTO protype ( pid, ptid, ctime, mtime, muser) VALUES 
						( '{$id}', '{$row[3][0]}', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."', '{$uid}')";
						
						$db->setQuery( $sql );
						$db->query();
					}
				}
			}
		}
	}
		
	
	if($format1_arr && count($format1_arr)>0){
		
		$sql="delete from proinstock where pid='$id';";
		foreach($format1_arr as $key=>$row){
			if($format2_arr[$row['id']]){
				foreach($format2_arr[$row['id']] as $key2=>$row2){
					$instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$id' AND format1 = '{$row['id']}' AND format2 = '{$row2['id']}' ","instockchk");
					$sql.="insert into proinstock (instockchk,format1_type,format1,format2_type,format2,pid,instock,code)
							values ('{$instockchk}',(select belongid from proformat where id='{$row['id']}'),'{$row['id']}',(select belongid from proformat where id='{$row2['id']}'),'{$row2['id']}','$id','{$checked2_instock[$row['id']][$row2['id']]}','{$checked2_procode[$row['id']][$row2['id']]}');";
					
				}
			}
		}
		
		$db->setQuery( $sql );
		$db->query_batch();		
	}else if($format2_arr && count($format2_arr)>0){
		$sql="delete from proinstock where pid='$id';";
		foreach($format2_arr as $key2=>$row2){
			$instockchk = getFieldValue(" SELECT instockchk FROM proinstock WHERE pid = '$id' AND format1 = '{$row2['id']}' ","instockchk");
			$sql.="insert into proinstock (instockchk,format1_type,format1,pid,instock)
					values ('{$instockchk}',(select belongid from proformat where id='{$row2['id']}'),'{$row2['id']}','$id','{$checked2_instock[$row2['id']]}');";
			
		}
		
		$db->setQuery( $sql );
		$db->query_batch();	
	}
		
	JsonEnd(array("status"=>1,"msg"=>$msg));
	
		
}



include( $conf_php.'common_end.php' ); 
?>