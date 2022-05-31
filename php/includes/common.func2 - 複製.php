<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

if (phpversion() < '4.2.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php41x.php" );
}
if (phpversion() < '4.3.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php42x.php" );
}
if (in_array( '_post', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  Post variable hack attempted.' );
}
if (in_array( '_get', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  GET variable hack attempted.' );
}
if (in_array( '_request', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  REQUEST variable hack attempted.' );
}


@set_magic_quotes_runtime( 0 );

if (@$globalConf_error_reporting === 0) {
	error_reporting( 0 );
} else if (@$globalConf_error_reporting > 0) {
	error_reporting( $globalConf_error_reporting );
}

function getFieldValue( $sql , $fName)
{	
	global $db;
  	$db->setQuery( $sql );
  	if($db->loadObject($r))
  	{
  		return  $r->$fName;
    }
    else
		return  null;
}
function imgupd($imageData,$path,$tablename,$id,$num=1,$cut=true){
	global $db,$conf_dir_path;
	
	$filteredData = substr($imageData, strpos($imageData, ",") + 1);
	$unencodedData = base64_decode($filteredData);
	$fpath=$path;
	$path=$conf_dir_path.$path;
	$fp = fopen($path, 'wb');
	fwrite($fp, $unencodedData);
	fclose($fp);
	try{
		$quality = 80;
		
		if($tablename == "products") {
			$targetWidth = "622";
			$targetHeight = "726";
		}else{
			$cut = false;
		}
		
		switch (exif_imagetype($path)) {
		 
			case IMAGETYPE_PNG :
                $img = imagecreatefrompng($path);
		        imagesavealpha($img, true);
                if($cut) {
                	$src_w = imagesx($img);
                    $src_h = imagesy($img);
                    
                    if( $src_w > $src_h){
                        $new_w = $src_h * $targetWidth / $targetHeight;
                        $new_h = $src_h;
                    }else{
                        $new_w = $src_w;
                        $new_h = $src_w * $targetHeight / $targetWidth;
                    }
                    
                    $srt_w = ( $src_w - $new_w ) / 2;
                    $srt_h = ( $src_h - $new_h ) / 2;
                    
                    $newpc = imagecreatetruecolor($new_w,$new_h);
                    
                    imagealphablending($newpc, false);
		            imagesavealpha($newpc, true);
                    imagecopy($newpc, $img, 0, 0, $srt_w, $srt_h, $new_w, $new_h );
                    @imagedestroy($img);
                    $img = $newpc;
                    
                } else {
                	imagealphablending($img, false);
                }
                
                @imagepng($img, $path, 8);
				break;
			case IMAGETYPE_JPEG :
				@$img = imagecreatefromjpeg($path);
				if($cut) {
                	$src_w = imagesx($img);
                    $src_h = imagesy($img);
                    
                    if( $src_w > $src_h){
                        $new_w = $src_h * $targetWidth / $targetHeight;
                        $new_h = $src_h;
                    }else{
                        $new_w = $src_w;
                        $new_h = $src_w * $targetHeight / $targetWidth;
                    }
                    
                    $srt_w = ( $src_w - $new_w ) / 2;
                    $srt_h = ( $src_h - $new_h ) / 2;
                    
                    $newpc = imagecreatetruecolor($new_w,$new_h);
                    
                    imagecopy($newpc, $img, 0, 0, $srt_w, $srt_h, $new_w, $new_h );
                    @imagedestroy($img);
                    $img = $newpc;
                    
                }
				@imagejpeg($img, $path, $quality);
				break;
		}
		
		@imagedestroy($img);
	}catch(Exception $e){
		
	}
	if(is_file($path)){
		$code=getFieldValue("select code from imglist where belongid='$id' AND path='$tablename' AND num='$num'","code");
		if(!$code){
			$db->setQuery("insert into imglist (belongid,path,name,code,num,version) values ('$id','$tablename','$fpath','".md5($id.$num.$tablename)."','$num',1)");
			$db->query();
		}else{
			$db->setQuery("update imglist set version=version+1 where code='$code'");
			$db->query();
		}
		return true;
	}else{
		return false;
	}
}

function fileupd($fileData,$path,$tablename,$id,$num=1,$file_name,$cut=true){
	global $db,$conf_dir_path;
	
	$filteredData = substr($fileData, strpos($fileData, ",") + 1);
	$unencodedData = base64_decode($filteredData);
	$fpath=$path;
	$path = mb_convert_encoding($path, "BIG5");
	$path=$conf_dir_path.$path;
	$fp = fopen($path, 'wb');
	fwrite($fp, $unencodedData);
	fclose($fp);
	$delsql = "DELETE FROM filelist WHERE belongid=$id";
	$db->setQuery($delsql);
	$db->query();
	$db->setQuery("insert into filelist (belongid,path,name,origin_name,code,num,version) values ('$id','$tablename','$fpath','$file_name','".md5($id.$num.$tablename)."','$num',1)");
	$db->query();
	// if(is_file($path)){
	// 	$code=getFieldValue("select code from filelist where belongid='$id' AND path='$tablename' AND num='$num'","code");
	// 	if(!$code){
		// $db->setQuery("insert into filelist (belongid,path,name,origin_name,code,num,version) values ('$id','$tablename','$fpath','$file_name','".md5($id.$num.$tablename)."','$num',1)");
		// $db->query();
	// 	}else{
	// 		$db->setQuery("update filelist set version=version+1 where code='$code'");
	// 		$db->query();
	// 		$db->setQuery("update filelist set origin_name=$file_name where code='$code'");
	// 		$db->query();
	// 	}
	// 	return true;
	// }else{
	// 	return false;
	// }
}

function getimg($tablename,$belongid=0,$num=0){
	global $db,$conf_dir_path,$conf_real_upload,$conf_upload;
	$where_str="";
	if($belongid){
		$where_str.=" AND belongid='$belongid'";
	}
	
	if($num>0){
		$where_str.=" AND num='$num'";
	}
	$sql="select * from imglist where path='$tablename' $where_str order by num";
	$db->setQuery($sql);
	$r=$db->loadRowList();
	$imgArr=array();
	foreach($r as $row){
		$name=$row['name'];
		if(is_file($conf_dir_path.$name)){
			if($num==0){
				$imgArr[$row['num']]=str_replace($conf_upload,$conf_real_upload,$name)."?v=".$row['version'];
			}else{
				$imgArr=str_replace($conf_upload,$conf_real_upload,$name)."?v=".$row['version'];
			}
		}
	}
	return $imgArr;
}
function delimg($tablename,$id,$num=1){
	global $db,$conf_dir_path;
	$code=getFieldValue("select code from imglist where belongid='$id' AND path='$tablename' AND num='$num'","code");
	if($code){
		$name=getFieldValue("select name from imglist where belongid='$id' AND path='$tablename' AND num='$num'","name");
		$db->setQuery("delete from imglist where belongid='$id' AND path='$tablename' AND num='$num'");
		$r=$db->query();
		if($r){
			unlink($conf_dir_path.$name);
		}
	}
}


function delAllimg($tablename, $id) {
	global $db, $conf_dir_path;
	
	$db->setQuery("select name from imglist where belongid='$id' AND path='$tablename'");
	$list = $db->loadRowList();
	$db->setQuery("delete from imglist where belongid='$id' AND path='$tablename'");
	$r=$db->query();
	if($list && $r) {
		foreach($list as $key=>$val) {
			unlink($conf_dir_path.$val['name']);
		}
	}
}

function odrchg($tablename,$id,$ext_where=null,$str=null){
	global $db,$conf_user;
	$sql=null;
	
	if(empty($str))
	{
		$str = 'odring';
	}
	
	for($i=1;$i<=count($id);$i++){
		$sql.="update $tablename set $str='$i' where id='{$id[($i-1)]}' $ext_where;";
	}
	$db->setQuery( $sql );
	$db->query_batch();
	
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}

function JsonEnd($arrJson)
{
    global $conf_user;
	
    $showtxt = 0;
    
	global_output_header('json');
	session_write_close();
	if(isset($_GET['callback'])) {
		echo $_GET['callback']."(".json_encode($arrJson).")"; 
	} else {
		echo json_encode($arrJson);
	}
	exit;
}


function getSysid($ulevel){
	global $conf_user,$db,$root_admin,$root_store;
	
	
	if($ulevel==$root_admin){
		return "admin";
	}else if($ulevel==$root_store){
		return $_SESSION[$conf_user]['uid'];
	}else{
		return false;
	}
}

function getsiteinfo($on=array()){
	global $db,$conf_user;
	
	$sql="select * from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=array();
	foreach($r as $key=>$value){
		if(in_array($key,$on)){
			
			if($key == "name" && $_SESSION[$conf_user]['syslang'] && $r["name_".$_SESSION[$conf_user]['syslang']])
			{
				$value = $r["name_".$_SESSION[$conf_user]['syslang']];
			}
			if($key == "addr" && $_SESSION[$conf_user]['syslang'] && $r["addr_".$_SESSION[$conf_user]['syslang']])
			{
				$value = $r["addr_".$_SESSION[$conf_user]['syslang']];
			}
			
			$data[$key]=$value;
		}
	}
	return $data;
}

function getpubcode($ajax=null){
	global $db,$conf_user;
	
	$sql_str = "codeName";
	if($_SESSION[$conf_user]['syslang'])
	{
		switch ($_SESSION[$conf_user]['syslang']) {
			case 'zh-cn':
				$sql_str = "codeName_chs";
				break;
			case 'en':
				$sql_str = "codeName_en";
				break;
			case 'in':
				$sql_str = "codeName_in";
				break;
			default :
				$sql_str = "codeName";
				break;
		}
	}
	
	$sql = " select * from pubcode where deleteChk=0 order by odring";	

	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$info=array();
		$info['name']=$row[$sql_str];
		$info['codeName_chs']=$row['codeName_chs'];
		$info['codeName_en']=$row['codeName_en'];
		$info['value']=$row['codeValue'];
		
		$data[$row['codeKinds']][$info['value']]=$info;
		
		$_SESSION['pubcode'][$row['codeKinds']][$info['value']]=$info;
	}
	if($ajax){
		JsonEnd($data);
	}
}

function getWMDate($type,$targetDate=null){
	if(!$targetDate){
		$targetDate=date("Y-m-d");
	}
	if(!is_numeric($type)){
		if(!$type)$type="w";
		if($type=='w'){
	    	$sdate=date("Y-m-d",strtotime("-7 day".$targetDate));
	    }else if($type=='m'){
	    	$sdate=date("Y-m-d",strtotime("-1 month".$targetDate));
	    }
	}else{
		if($type>0)$type="+".$type;
		$sdate=date("Y-m-d",strtotime("$type day".$targetDate));
	}
    return $sdate;
}

function dateNum($sdate,$edate,$row,$type='label',$usedate=true){
	
	$s=((strtotime($edate)-strtotime($sdate))/60/60/24);
	$a=array();
	
	
	for($i=0;$i<($s-0);$i++){
		if($usedate){
			$d=date("Y-m-d",strtotime("+{$i} day ".$sdate));
			if($row[$d]){
				if($type=='label'){
					$a[$d]=date("m-d",strtotime($row[$d]));
				}else{
					$a[$d]=$row[$d];
				}
			}else{
				if($type=='value'){
					$v=0;
				}else{
					$v=date("m-d",strtotime($d));
				}
				$a[$d]=$v;
			}
		}else{
			return $row;
		}
	}
	
	
	return $a;
}

function arrayRmKey($arr=array(),$arr2=array()){
	ksort($arr);
	ksort($arr2);

	if(count($arr)>0){
		if(count($arr2)>0){
			$a=array();
			
			foreach($arr as$key=>$value){
				$b=array();
				$b['key']=$value;
				$b['values']=$arr2[$key];
				$a[]=$b;
			}
		}else{
			$a=array();
			foreach($arr as $value){
				if(is_numeric($value) && substr($value,1)!=0){
					$value=floatval($value);
				}
				$a[]=$value;
			}
		}
		$arr=$a;
	}
	
	return $arr;
}


function getChart($chartType,$labels,$total,$series){
	
	$dataArr=array();
	if($chartType=="d3"){
		$dataArr['data']=arrayRmKey($labels,$total);
	}else if($chartType=="chartjs"){
		$labels=arrayRmKey($labels);
		$total=arrayRmKey($total);
		$series=array($series);
		$dataArr['labels']=$labels;
		$dataArr['series']=$series;
		$dataArr['data']=array($total);
	}
	return $dataArr;
}

function zeroChk($v1,$v2){
	
	if($v1!="∞"){
		if($v2!=0){
			$v1=round($v1/$v2);
		}else{
			$v1="∞";
		}
	}
	return $v1;
}

function parr($arr=array()){
	
	echo "<pre>".print_r($arr,true)."</pre>"; 
}

function enpw($pw=null){
	global $globalConf_encrypt_1,$globalConf_encrypt_2;
	if(!$pw)die();
	return md5($globalConf_encrypt_1.$pw.$globalConf_encrypt_2);
}



function ComputeDesActve($allcart,$cart,$act,$actRangePCode,$discount,$discountFree,$activeProChk = false){
	global $db,$conf_user;
	
    
    
	
	$uid = LoginChk();
	$historyEffectiveTimes = getFieldValue("SELECT SUM(AR.times) AS effectiveTimes FROM activeRecord AS AR , orders AS O WHERE AR.orderid = O.id AND O.status <> '6' AND AR.memberid = '$uid' AND AR.activeid = '{$act['id']}';", "effectiveTimes");

	$actTypePCode = $act['actTypePCode'];	
	$activePlanid = $act['activePlanid'];	
	
	
	
	
    $arr = array();
    
    
	if(count($cart) > 0)
	{
        $cartChk=true;
		$tarcart = array('totalAmt'=>0,'totalNum'=>0);
		$usepro = array();	
		$minproid = 0;	
		$minproAmt = 0;	
		$actproArr = array();   
		
		$odrproArr = array();	
		$odrproRArr = array();	
		
		foreach($cart as $pid=>$row)
		{
			if($activeProChk)
			{
				$activeUsedPro_arr = $_SESSION[$conf_user]['activeUsedPro_arr'];
				if(!empty($activeUsedPro_arr[$pid]) && $act['ptype'] != $activeUsedPro_arr[$pid])
				{
					continue;
				}
			}
			
			
			if($actRangePCode == '2' && strpos($act['var03'],"||".$pid."||") === false)
			{
				continue;
			}
			else
			{
				
				$tarcart['totalAmt'] += $row['num'] * $row['siteAmt'];
				$tarcart['totalNum'] += $row['num'];
				
				
				$usepro[] = $pid;
				
				
				if($minproAmt == 0 || $minproAmt > $row['siteAmt'])
				{
					$minproid = $pid;
					$minproAmt = $row['siteAmt'];
				}
				
				
				if($activePlanid == '12' || $activePlanid == '3' || $activePlanid == '2' || $activePlanid == '1')
				{
					$tmp = array();
					$chk = true;
					if(count($odrproArr) > 0)
					{
						foreach($odrproArr as $pro)
						{
							if($pro['siteAmt'] > $row['siteAmt'] && $chk)
							{
								for($i = 0 ; $i < $row['num'] ; $i++)
								{
									$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
								}
								$chk = false;
							}
							
							$tmp[] = $pro;
							
						}
						
						if($chk)
						{
							for($i = 0 ; $i < $row['num'] ; $i++)
							{
								$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
							}
						}
						
					}
					else
					{
						for($i = 0 ; $i < $row['num'] ; $i++)
						{
							$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
						}
					}
					$odrproArr = $tmp;
					
				}
			}
        }
        
		$odrproRArr = array_reverse($odrproArr);
		
		
		if($activePlanid == '8' || $activePlanid == '9' || $activePlanid == '13' || $activePlanid == '10' || $activePlanid == '14')
		{
			if($actTypePCode == '2')
			{
				$allcart['totalAmt'] = $allcart['totalAmt'] - intval($discount);
			}
			else if($actTypePCode == '4')
			{
				$tarcart['totalAmt'] = $tarcart['totalAmt'] - intval($discount);
			}
		}
		
		if($activePlanid == '9' || $activePlanid == '13')
		{
			if($actTypePCode == '2')
			{
				$allcart['totalAmt'] = $allcart['totalAmt'] - intval($discountFree);
			}
			else if($actTypePCode == '4')
			{
				$tarcart['totalAmt'] = $tarcart['totalAmt'] - intval($discountFree);
			}
		}
		
		
		switch ($actTypePCode) {
			case "2":	
				if($allcart['totalAmt'] < $act['var01']) {
                    $cartChk=false;	
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalAmt'] / $act['var01']);
				}
				
		        break;
			case "3":	
				if($allcart['totalNum'] < $act['var01']) {
					$cartChk=false;	
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalNum'] / $act['var01']);
				}
				
				break;
			case "4":	
				if($tarcart['totalAmt'] < $act['var01']) {
					$cartChk=false;	
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalAmt'] / $act['var01']);
				}
				
				break;
			case "5":	
				if($tarcart['totalNum'] < $act['var01']) {
					$cartChk=false;	
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalNum'] / $act['var01']);
				}
				
				break;
		}
		
		$effectiveTime = 0;
        
        
		switch($activePlanid)
		{
			case "1":	
				
				
				$effectiveTime = $tarcart['totalNum']; 
				if ( $effectiveTime > 0 && $act['limitChk'] && ($effectiveTime + $historyEffectiveTimes) > $act['limitCnt']) 
				{
					$tarcart['totalNum'] = $act['limitCnt'] - $historyEffectiveTimes;
					$effectiveTime = $tarcart['totalNum']; 
					if(!$effectiveTime)
					{
						$cartChk=false;	
					}
					else
					{
						$usepro = array();
						for($i = 0 ; $i < $effectiveTime ; $i++)
						{
							$usepro[] = $odrproRArr[$i]['pid'];
						}
					}
				}
				
				$tmp = $act['var02'] * $tarcart['totalNum'];
				$arr['dispro'] = $usepro;
				$arr['actproArr'] = $usepro;
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? $tarcart['totalAmt'] : $tmp);
				
				break;
			case "2":	
				
				
				$effectiveTime = $tarcart['totalNum']; 
				if ( $effectiveTime > 0 && $act['limitChk'] 
					 && ($effectiveTime + $historyEffectiveTimes) > $act['limitCnt']) {
					$tarcart['totalNum'] = $act['limitCnt'] - $historyEffectiveTimes;
					$effectiveTime = $tarcart['totalNum']; 
					if(!$effectiveTime)
					{
						$cartChk=false;	
					}
					else
					{
						$usepro = array();
						$tarcart['totalAmt'] = 0;
						for($i = 0 ; $i < $effectiveTime ; $i++)
						{
							$usepro[] = $odrproRArr[$i]['pid'];
							$tarcart['totalAmt'] += $odrproRArr[$i]['siteAmt'];
						}
					}
				}
				
				$arr['dispro'] = $usepro;
				$arr['actproArr'] = $usepro;
				$arr['disAmt'] = intval($tarcart['totalAmt'] - round($tarcart['totalAmt'] * intval($act['var02']) * 0.01));
				break;
			case "3":	
				$usepro = array();
				$var01 = intval($act['var01']);	
				$var02 = intval($act['var02']);	
				$q = 1;
				if($var01 > 0)
				{
					$q = intval($tarcart['totalNum'] / $var01);
				}
				
				
				$effectiveTime = $q; 
				if ( $effectiveTime > 0 && $act['limitChk'] 
					 && ($effectiveTime + $historyEffectiveTimes) > $act['limitCnt']) {
					$q = $act['limitCnt'] - $historyEffectiveTimes;
					$effectiveTime = $q; 
					if(!$effectiveTime)
					{
						$cartChk=false;	
					}
				}
				
				$sum = 0;
				for($i = 0 ; $i< ($q * $var01) ; $i++)
				{
					$sum += $odrproRArr[$i]['siteAmt'];
					$usepro[] = $odrproRArr[$i]['pid'];
					$arr['dispro'][] = $odrproRArr[$i]['pid'];
					$arr['actproArr'][] = $odrproRArr[$i]['pid'];
				}
				$arr['disAmt'] = intval(($sum < ($q * $var02)) ? '0' : $sum - ($q * $var02));
				break;
			case "4":	
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? $tarcart['totalAmt'] : $tmp);
				$effectiveTime = 1;
				break;
			case "5":	
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? '0' : $tarcart['totalAmt'] - $tmp);
				$effectiveTime = 1;
				break;
			case "6":	
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $minproAmt) ? $minproAmt : $tmp);
				$effectiveTime = 1;
				break;
			case "7":	
				$arr['disAmt'] = intval(round($minproAmt * (100 - intval($act['var02'])) * 0.01));
				$effectiveTime = 1;
				break;
			case "8":	
			case "14":
			case "9":	
			case "13":	
				$arr['disAmt'] = '0';
				$arr['actpid'] = $activePlanid;
				
				
				$effectiveTime = intval($freeCnt); 
				if ( $effectiveTime > 0 && $act['limitChk'] && ($effectiveTime + $historyEffectiveTimes) > $act['limitCnt']) 
				{
					$tarcart['totalNum'] = $act['limitCnt'] - $historyEffectiveTimes;
					$effectiveTime = $tarcart['totalNum']; 
					if(!$effectiveTime)
					{
						$cartChk=false;	
					}
					else
					{
						$freeCnt = $effectiveTime;
					}
				}
				
				if($activePlanid == '9' || $activePlanid == '13' || $activePlanid == '8' || $activePlanid == '14')
				{
					if(empty($freeCnt))
					{
                        $cartChk=false;	
                        break;
					}
					
					$arr['freeCnt'] = $freeCnt;
				}
				$effectiveTime = $freeCnt;
				break;
			case "10":	
			case "11":	
				$arr['disAmt'] = '0';
				$arr['actpid'] = $activePlanid;
                $effectiveTime = -1;
				break;
			case "12":	
				$effectiveTime = -1;
				$sum = 0;
				$arr['dispro'] = array();
				$pro_cnt = array();
				$usepro = array();
				
				$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
				
				if(count($pairProArr) > 0)	
				{
					$disAmt = 0;
					foreach($pairProArr as $pair)
					{
						$pairArr = explode("@@",$pair);
						foreach($odrproArr as $odrpro)
						{
							if($odrpro['pid'] == $pairArr[1])
							{
								
								$disAmt +=   (intval($odrpro['siteAmt']) -  round(intval($odrpro['siteAmt']) * intval($act['var02']) * 0.01));
								$arr['dispro'][] = $odrpro['pid'];
								
								break;
							}
						}
						
						if(count($pairArr) > 0)
						{
							foreach($pairArr as $pair_pid)
							{
								if(!empty($pair_pid))
								{
									$arr['actproArr'][]= $pair_pid;
								}
							}
						}
						
					}
				}
				else
				{
					$disAmt = 0;
					
				}
				
				
				$tmpNum = ($actTypePCode == '2' || $actTypePCode == '3') ? $allcart['totalNum'] : $tarcart['totalNum'];
				$disAmtMax = 0;
				$odrproArr_reverse  = array_reverse($odrproArr);
				for($i = 0 ; $i< intval($tmpNum / 2) ; $i++)
				{
					
					$j = (2*$i)+1;
					if(count($odrproArr_reverse[$j]) > 0)
					{
						$disAmtMax +=   (intval($odrproArr_reverse[$j]['siteAmt']) -  round(intval($odrproArr_reverse[$j]['siteAmt']) * intval($act['var02']) * 0.01));
					}
				}
				
				if(count($odrproArr) > 0)
				{
					foreach($odrproArr as $odrproData)
					{
						$usepro[] = $odrproData['pid'];
					}
				}
				
				
				$arr['disAmt'] = $disAmt;
				$arr['disAmtMax'] = $disAmtMax;
				break;
			
        }
        
		if ( $activePlanid != "1" && $activePlanid != "2" && $activePlanid != "3"  
			 && $effectiveTime > 0 && $act['limitChk'] 
		     && ($effectiveTime + $historyEffectiveTimes) > $act['limitCnt']) {
			$cartChk=false;	
		}
    }
    
    
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];
    if($activeBundleCart && count($activeBundleCart) > 0)
	{
        $activeBundleCartChk=true;
		$tarcart = array('totalAmt'=>0,'totalNum'=>0);
		$usepro_activeBundle = array();	
		$minproid = 0;	
		$minproAmt = 0;	
		$actproArr = array();   
		
		$odrproArr = array();	
        $odrproRArr = array();	
        
		foreach($activeBundleCart as $pid=>$row)
		{
            $row['num']=1;
			
            $tarcart['totalAmt'] += $row['num'] * $row['price'];
            $tarcart['totalNum'] += $row['num'];
            
            
            $usepro_activeBundle[] = $pid;
            
            
            if($minproAmt == 0 || $minproAmt > $row['price'])
            {
                $minproid = $pid;
                $minproAmt = $row['price'];
            }
            
            
            if($activePlanid == '12' || $activePlanid == '3')
            {
                $tmp = array();
                $chk = true;
                if(count($odrproArr) > 0)
                {
                    foreach($odrproArr as $pro)
                    {
                        if($pro['siteAmt'] > $row['price'] && $chk)
                        {
                            for($i = 0 ; $i < $row['num'] ; $i++)
                            {
                                $tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
                            }
                            $chk = false;
                        }
                        
                        $tmp[] = $pro;
                        
                    }
                    
                    if($chk)
                    {
                        for($i = 0 ; $i < $row['num'] ; $i++)
                        {
                            $tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
                        }
                    }
                    
                }
                else
                {
                    for($i = 0 ; $i < $row['num'] ; $i++)
                    {
                        $tmp[] = array('pid'=>$pid,'siteAmt'=>$row['price']);
                    }
                }
                $odrproArr = $tmp;
                
            }
		}
		
		$odrproRArr = array_reverse($odrproArr);
        
        
		
		switch ($actTypePCode) {
			case "2":	
				if($allcart['totalAmt'] < $act['var01']) {
                    $activeBundleCartChk=false;
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalAmt'] / $act['var01']);
				}
				
		        break;
			case "3":	
				if($allcart['totalNum'] < $act['var01']) {
					$activeBundleCartChk=false;
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalNum'] / $act['var01']);
				}
				
				break;
			case "4":	
				if($tarcart['totalAmt'] < $act['var01']) {
					$activeBundleCartChk=false;
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalAmt'] / $act['var01']);
				}
				
				break;
			case "5":	
				if($tarcart['totalNum'] < $act['var01']) {
					$activeBundleCartChk=false;
                    break;
				}
				
				
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalNum'] / $act['var01']);
				}
				
				break;
		}
		
		$effectiveTime = 0;
        
		switch($activePlanid)
		{
			case "10":	
				$arr['disAmt'] = '0';
				$arr['actpid'] = $activePlanid;
                $effectiveTime = -1;
				break;
			
        }
        
		if ($effectiveTime > 0 && $act['limitChk'] && $effectiveTime + $historyEffectiveTimes > $act['limitCnt']) {
			$activeBundleCartChk=false;	
        }
        
    }
    
    if(!$cartChk && !$activeBundleCartChk){
        return null;
    }
    
	$arr['effectiveTime'] = $effectiveTime;
	
	if(count($usepro) > 0)
	{
		$arr['usepro'] = $usepro;
	}
	else if(count($usepro_activeBundle) > 0)
	{
		$arr['usepro'] = $usepro_activeBundle;
	}
	
	return $arr;
}





function saleCalc($proArr,$numArr){
	global $db,$conf_user;
	
	if(count($proArr)==0)return false;
	
	

	
	$amt=0;
	$total=0;
	
	$cartpro=$proArr;
	$cartpro2=$proArr;
	
	$data=array();
	
	
	$today=date("Y-m-d H:i");
	$sql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='')
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$active=array();
	$des_active=array(); 
	$all_active=array(); 
	foreach($r as $row){
		$active[$row['id']]=$row;
		$all_active[$row['actRangePCode']][$row['ptype']][$row['id']] = $row;
	}
	
	$sql = "select coin_to,coin_take from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$coin_to=$r['coin_to'];
	$coin_take=$r['coin_take'];
	
	$active_list=array();
	$discount=0;
	$disDlvrAmt=0;
	$loopable=true;
	$otherCalc=true;

	$usePro=array();
	$calcPro=$proArr;
	$tmpActive=array();
	$tmpproamt=0;
	
	$allprodiscount = 0;	
	$allproactive_arr = array();
	
	
	
	if(count($proArr) > 0)
	{
		$allcart = array('totalAmt'=>0,'totalNum'=>0);
		$cartpro = array();
		foreach($proArr as $row)
		{
			if(count($cartpro[$row['id']]) == '0')
			{
				$info = array();
				$info['id'] = $row['id'];
				$info['imgname'] = $row['imgname'];
				$info['num'] = 0;
				$info['siteAmt'] = $row['siteAmt'];
				$info['CalcHighAmt'] = 0;
				$info['CalcSiteAmt'] = 0;
				$info['item'] = array();
				
				$cartpro[$row['id']] = $info;
			}
			
			$cartpro[$row['id']]['num'] += $row['num'];
			$cartpro[$row['id']]['CalcHighAmt'] += $row['CalcHighAmt'];
			$cartpro[$row['id']]['CalcSiteAmt'] += $row['CalcSiteAmt'];
			
			$info = array();
			$info['num'] = $row['num'];
			$info['format1'] = $row['format1'];
			$info['format2'] = $row['format2'];
			$info['format1title'] = $row['format1title'];
			$info['format2title'] = $row['format2title'];
			$info['format1name'] = $row['format1name'];
			$info['format2name'] = $row['format2name'];
			$info['name'] = $row['name'];
			
			$cartpro[$row['id']]['item'][] = $info;
			
			$allcart['totalAmt'] += $row['siteAmt']*$row['num'];
			$allcart['totalNum'] += $row['num'];
			
		}
	}
	
	$amt = $allcart['totalAmt'];	
	$total=$amt;
	
	
	
	
	
	if(count($all_active) > 0)
	{
		foreach($all_active as $actRangePCode=>$info)
		{
			$tmp_active = $info;
			foreach($tmp_active as $ptype=>$row)
			{
				$tmp_cartpro = $cartpro;
				while((count($tmp_active[$ptype]) > 0) && (count($tmp_cartpro) > 0))
				{
					
					if(count($row) > 0)
					{
						$tmp_actid = 0;	
						$tmp_disAmt = 0; 
						$tmp_usepro = array();	
						
						foreach($row as $act)
						{
							$arr = ComputeDesActve($allcart,$tmp_cartpro,$act,$actRangePCode);
							
							if(count($arr) > 0 && ( $arr['disAmt'] > $tmp_disAmt) || (!empty($arr['actpid'])))
							{
								$tmp_actid = $act['id'];
								$tmp_disAmt = $arr['disAmt'];
								$tmp_usepro = $arr['usepro'];
							}
						}
						
						if(!empty($tmp_actid))
						{
							if($active[$tmp_actid]['ptype'] == '2')	
							{
								
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$addAmtPro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$proinfo['format'] =getProductFormat($proinfo['id']);
										$proinfo['img']=getimg('products',$proinfo['id'],1);
										$addAmtPro[] = $proinfo;
									}
								}
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"addAmtPro"=>$addAmtPro,"usepro"=>$tmp_usepro);
							}
							elseif($active[$tmp_actid]['ptype'] == '3')	
							{
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0);
							}
							elseif($active[$tmp_actid]['ptype'] == '4')	
							{
								
								$disDlvrAmt=$_SESSION[$conf_user]['dlvrAmt'];
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$disDlvrAmt,"dlvrfee"=>1);
							}
							else
							{
								$discount += $tmp_disAmt; 
										
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro);
									
								$amt=$amt-($tmp_disAmt);	
							}
								
							
							foreach($tmp_usepro as $proid)
							{
								unset($tmp_cartpro[$proid]);
							}
							
							unset($tmp_active[$ptype][$tmp_actid]);
							
						}
						else
						{
							break;
						}
					}
				}
			}
		}
	}
	
	
	
	if($coin_to){
		$free_coin=floor(($amt-intval($_SESSION[$conf_user]['usecoin']))/$coin_to)*$coin_take;
	}
	
	$data['amt']=$amt;
	$data['total']=$total;
	$data['active_list']=$active_list;
	$data['discount']=$discount;
	$data['disDlvrAmt']=$disDlvrAmt;
	$data['usecoin']=intval($_SESSION[$conf_user]['usecoin']);
	$data['free_coin']=$free_coin;
	
	
	return $data;
	
	

	

	
	
	
}



function saleCalc2($proArr, $carchk = false, $activeProChk = false){
	global $db,$conf_user;
    
    $activeBundleCart=$_SESSION[$conf_user]['activeBundleCart'];

	if(count($proArr)==0 && count($activeBundleCart)==0)return false;
	
	$amt=0;
	$total=0;
		
	$cartpro=$proArr;
	$cartpro2=$proArr;
	
	$data=array();
	
	$where_str = "";
	if($carchk)	
	{
		$where_str = " AND A.activePlanid = '10'";
	}
	
	
	$today=date("Y-m-d H:i");
	$sql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='') $where_str
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$active=array();
	$des_active=array(); 
	$all_active=array(); 
	foreach($r as $row){
        $row['passwordText']=$row['passwordText'];
		
		if($_SESSION[$conf_user]['syslang'] && !empty($row['name_'.$_SESSION[$conf_user]['syslang']]))
		{
			$row['name'] = $row['name_'.$_SESSION[$conf_user]['syslang']];
		}
		
		$active[$row['id']]=$row;
		if($row['ptype'] == 1)
		{
			if($row['activePlanid'] == 12)
			{
				$all_active[1][12][$row['id']] = $row;
			}
			else
			{
				$all_active[1][$row['ptype']][$row['id']] = $row;
			}
			
		}
		else
		{
			$all_active[$row['actRangePCode']][$row['ptype']][$row['id']] = $row;
		}
	}
	
	ksort($all_active);
		
	$sql = "select coin_to,coin_take from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$coin_to=$r['coin_to'];
	$coin_take=$r['coin_take'];
	
	$active_list=array();
	$discount=0;
	$discountFree=0;
	$disDlvrAmt=0;
	$loopable=true;
	$otherCalc=true;

	$usePro=array();
	$calcPro=$proArr;
	$tmpActive=array();
	$tmpproamt=0;
	
	$allprodiscount = 0;	
	$allproactive_arr = array();
	
	
	if(count($proArr) > 0)
	{
		$allcart = array('totalAmt'=>0,'totalNum'=>0);
		$cartpro = array();
		foreach($proArr as $row)
		{
			if(count($cartpro[$row['id']]) == '0')
			{
				$info = array();
				$info['id'] = $row['id'];
				$info['imgname'] = $row['imgname'];
				$info['num'] = 0;
				$info['siteAmt'] = $row['siteAmt'];
				$info['CalcHighAmt'] = 0;
				$info['CalcSiteAmt'] = 0;
				$info['item'] = array();
				
				$cartpro[$row['id']] = $info;
			}
			
			$cartpro[$row['id']]['num'] += $row['num'];
			$cartpro[$row['id']]['CalcHighAmt'] += $row['CalcHighAmt'];
			$cartpro[$row['id']]['CalcSiteAmt'] += $row['CalcSiteAmt'];
			
			$info = array();
			$info['num'] = $row['num'];
			$info['format1'] = $row['format1'];
			$info['format2'] = $row['format2'];
			$info['format1title'] = $row['format1title'];
			$info['format2title'] = $row['format2title'];
			$info['format1name'] = $row['format1name'];
			$info['format2name'] = $row['format2name'];
			$info['name'] = $row['name'];
			
			$cartpro[$row['id']]['item'][] = $info;
			
			$allcart['totalAmt'] += $row['siteAmt']*$row['num'];
			$allcart['totalNum'] += $row['num'];
			
		}
	}
	if($activeBundleCart && count($activeBundleCart)>0){
        foreach($activeBundleCart as $value){
            $allcart['totalAmt']+=$value['price'];
        }
    }
	$amt = $allcart['totalAmt'];	
	$total=$amt;
	$act_cnt = 0;
	$tmp_actproArr = array();	
	
	$tmp_cartpro_1 = array();
	$tmp_cartpro_1_chk = false;
	
	
	if(count($all_active) > 0)
	{
		foreach($all_active as $actRangePCode=>$info)
		{
            $tmp_active = $info;
            
			foreach($tmp_active as $ptype=>$row)
			{
				$tmp_cartpro = $cartpro;
				
				if($ptype == 12 && $tmp_cartpro_1_chk)
				{
					$tmp_cartpro = $tmp_cartpro_1;
				}
				
				while((count($tmp_active[$ptype]) > 0) && ((count($tmp_cartpro) > 0) || ((count($tmp_cartpro)==0) && (count($activeBundleCart)>0))) && $act_cnt < 50)
				{
					$act_cnt++;
					
					
					if(count($tmp_active[$ptype]) > 0)
					{
						$tmp_actid = 0;	
						$tmp_disAmt = 0; 
						$tmp_usepro = array();	
						$tmp_dispro = array();	
						$tmp_freeCnt = 0; 
						
						
						if((count($tmp_cartpro)==0) && (count($activeBundleCart)>0) && $ptype != '4')
						{
							break;
						}
												
						foreach($tmp_active[$ptype] as $act)
						{
							$arr = ComputeDesActve($allcart,$tmp_cartpro,$act,$act['actRangePCode'],$discount,$discountFree,$activeProChk);
							
							$disAmtMax = ( intval($arr['disAmtMax']) > 0 ) ? $arr['disAmtMax'] : $arr['disAmt'];
							
							
							$freeCnt_chk = false;
							$tmp_freeCnt2 = intval($arr['freeCnt']);
							$tmp_arr = explode ("耶誕好禮滿",$act['name']);
							if(count($tmp_arr) == 1 || empty($tmp_freeCnt2) || empty($tmp_freeCnt) || $tmp_freeCnt2 < $tmp_freeCnt)
							{
								$freeCnt_chk = true;
							}
							
							
							if(count($arr) > 0 && (( $disAmtMax > $tmp_disAmt) || (!empty($arr['actpid']))) && $freeCnt_chk)
							{
                                
								$tmp_actid = $act['id'];								
								$tmp_disAmt = $arr['disAmt'];
								$tmp_usepro = $arr['usepro'];
								$tmp_act = $act;
								$tmp_effectiveTime = $arr['effectiveTime'];
								if(count($arr['dispro']) > 0)
								{
									$tmp_dispro = $arr['dispro'];
								}
								else
								{
									$tmp_dispro = array();
								}
								$tmp_freeCnt = $arr['freeCnt'];
								$tmp_actproArr = $arr['actproArr'];
							}
						}	
																		
						if(!empty($tmp_actid) && count($tmp_active[$ptype][$tmp_actid]) > 0)
						{
							if($active[$tmp_actid]['ptype'] == '2' && $active[$tmp_actid]['activePlanid'] != '14')	
							{
								
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$addAmtPro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$proinfo['bid'] = getFieldValue(" SELECT A.ptid FROM protype A, producttype B WHERE A.ptid = B.id AND A.pid = '".$proinfo['id']."' AND B.pagetype = 'page'","ptid");
										$proinfo['format'] =getProductFormat($proinfo['id']);
										$proinfo['img']=getimg('products',$proinfo['id'],1);
										$addAmtPro[] = $proinfo;
									}
								}
								
								$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"addAmtPro"=>$addAmtPro,"usepro"=>$tmp_usepro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr,"addProCnt"=>$tmp_freeCnt);
							}
							elseif($active[$tmp_actid]['ptype'] == '2' && $active[$tmp_actid]['activePlanid'] == '14')
							{
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$sql3 = $sql;
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$e3AmtPro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$proinfo['bid'] = getFieldValue(" SELECT A.ptid FROM protype A, producttype B WHERE A.ptid = B.id AND A.pid = '".$proinfo['id']."' AND B.pagetype = 'page'","ptid");
										$proinfo['format'] =getProductFormat($proinfo['id']);
										$proinfo['img']=getimg('products',$proinfo['id'],1);
										$e3AmtPro[] = $proinfo;
									}
								}
								$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"addAmtPro"=>$e3AmtPro,"usepro"=>$tmp_usepro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr,"addProCnt"=>$tmp_freeCnt);
							}
							elseif($active[$tmp_actid]['ptype'] == '3')	
							{
								
								global $tmpActiveId0221;
								if($tmp_actid != $tmpActiveId0221)
								{
									$discountFree += $tmp_freeCnt * intval($active[$tmp_actid]['var01']);	
								}
								
								
								$var02 = intval($active[$tmp_actid]['var02']);	
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$activePlanid = getFieldValue(" SELECT activePlanid FROM active WHERE id = '$tmp_actid' ","activePlanid");
								if($activePlanid == '13')
								{									
									
									$freePro = array();
									$tmp_dispro = array();
									$tmp_usepro = array();
									$activeUsedPro_arr = $_SESSION[$conf_user]['activeUsedPro_arr'];
									
									if(count($list) > 0)
									{
										$tmp_var01 = intval($active[$tmp_actid]['var01']);	
										foreach($list as $proinfo)
										{
											if($activeProChk)
											{
												if( !empty($activeUsedPro_arr[$proinfo['id']]) && $activeUsedPro_arr[$proinfo['id']] != "3" )
												{
													continue;
												}
											}
											
											$cartproNum = $cartpro[$proinfo['id']]['num']; 
											$tmp_var00 = 0;
											$tmp_var02 = 0;
											if( $cartproNum >= $tmp_var01 && !empty($tmp_var01) )
											{
												$tmp_var00 = (int)($cartproNum / $tmp_var01) * $tmp_var01;
												$tmp_var02 = (int)($cartproNum / $tmp_var01) * $var02;
											}
											
											for($i = 1 ; $i<= $tmp_var00 ; $i++)
											{
												$tmp_dispro[] = $proinfo['id'];
												$tmp_usepro[] = intval($proinfo['id']);
											}
											
											for($i = 1 ; $i<= $tmp_var02 ; $i++)
											{
												$proinfo['fid'] = $proinfo['id']."|||0|||".$tmp_actid."|||".$i;
												$proinfo['format'] =getProductFormat($proinfo['id']);
												$proinfo['img']=getimg('products',$proinfo['id'],1);
												$proinfo['var03'] = "";
												$freePro[] = $proinfo;
												
											}
										}
									}
									
									if($tmp_usepro[0] != 0)
									{
										$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"usepro"=>$tmp_usepro,"dispro"=>$tmp_dispro,"freePro"=>$freePro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
									}
					
								}
								else
								{
									
									$freePro = array();
									if(count($list) > 0)
									{
										foreach($list as $proinfo)
										{
											$tmp_var02 = $var02;
											if($tmp_freeCnt > 1)
											{
												$tmp_var02 = (int)($tmp_freeCnt) * $var02;
											}
											
											
											if($tmp_actid == $tmpActiveId0221){
												$tmp_var02 = 1;
											}
											
											for($i = 1 ; $i<= $tmp_var02 ; $i++)
											{
												$proinfo['fid'] = $proinfo['id']."|||0|||".$tmp_actid."|||".$i;
												$proinfo['format'] =getProductFormat($proinfo['id']);
												$proinfo['img']=getimg('products',$proinfo['id'],1);
												$proinfo['var03'] = "";
												$freePro[] = $proinfo;
											}
										}
									}
									
									
									
									$discount += $tmp_disAmt * intval($tmp_freeCnt); 
									
									unset($_SESSION['tmpActive0221']);
									if($tmp_usepro[0] != 0)
									{
										global $tmpActiveId0221;
										if($tmpActiveId0221 == $tmp_actid)
										{
											$_SESSION['tmpActive0221'] = array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro,"freePro"=>$freePro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr,"freeCnt"=>$tmp_freeCnt);
										}
										else
										{
											$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro,"freePro"=>$freePro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr,"freeCnt"=>$tmp_freeCnt);
										}
									}
									
									$amt=$amt-($tmp_disAmt * intval($tmp_freeCnt));	
									
								}						
							}
							elseif($active[$tmp_actid]['ptype'] == '4')	
                            {
                                
								
								$disDlvrAmt=$_SESSION[$conf_user]['dlvrAmt'];
								$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$disDlvrAmt,"dlvrfee"=>1,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							elseif($active[$tmp_actid]['ptype'] == '5')	
							{
								
								$var02 = intval($active[$tmp_actid]['var02']);	
								$var03 = $active[$tmp_actid]['var03'];	
								
								if(empty($var03))
								{
									$tmp_usepro = array();
								}
								
								$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"bonus"=>$var02,"bonuspro"=>$tmp_usepro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							else
							{
								$discount += $tmp_disAmt; 
										
								
								$active_list[]=array("id"=>$tmp_actid,"effectiveTime"=>$tmp_effectiveTime,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro,"dispro"=>$tmp_dispro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
									
								$amt=$amt-($tmp_disAmt);	
							}
							
							if(count($tmp_dispro) > 0)
							{
								
								foreach($tmp_dispro as $proid)
								{
									if(intval($tmp_cartpro[$proid]['num']) > 1)
									{
										$tmp_cartpro[$proid]['num'] = intval($tmp_cartpro[$proid]['num']) - 1;
									}
									else
									{
										unset($tmp_cartpro[$proid]);
									}
								}
							}
							else if($active[$tmp_actid]['ptype'] == '3') 
							{
								
							}
							else
							{
								
								foreach($tmp_usepro as $proid)
								{
									unset($tmp_cartpro[$proid]);
								}
							}
							
							
							
							if($ptype == 1)
							{
								$tmp_cartpro_1_chk = true;
								$tmp_cartpro_1 = $tmp_cartpro;
							}
							
							
							unset($tmp_active[$ptype][$tmp_actid]);
							
						}
						else
						{
							break;
						}
					}
				}
			}
		}
	}
	
	if($coin_to){
		$free_coin=floor(($amt-intval($_SESSION[$conf_user]['usecoin']))/$coin_to)*$coin_take;
	}
	
	$data['amt']=$amt;
	$data['total']=$total;
	$data['active_list']=$active_list;
	$data['discount']=$discount+$activeExtraDiscount;
	$data['disDlvrAmt']=$disDlvrAmt;
	$data['usecoin']=intval($_SESSION[$conf_user]['usecoin']);
	$data['free_coin']=$free_coin;
	$data['activeExtraList']=$effectiveList;
	
	
	
	
	return $data;
	
}



function getDBPageLink($linktype,$url,$tb,$id){
	global $db;
	
	if ($linktype == "link" || !$linktype) {
		$linkurl=$url?$url:'javascript:void(0)';
	} else if ($linktype=="database") {
		$sql="select * from dbpageLink where fromtable='$tb' and fromid='$id'";
		$db->setQuery( $sql );
		$r = $db->loadRow();
		if($r){
			$totable=$r['totable'];
			if($r['pageid']){
				$pageid=$r['pageid'];
				$dirid=$r['dirid'];
				$pagetype="page";
			}else if($r['dirid']){
				$pageid=getFieldValue("select id from {$r['totable']} where belongid='{$r['dirid']}' AND publish = 1 order by odring,id","id");
				$dirid=$r['dirid'];
				$pagetype="list";
			} else {
				$pageid = 0;
				$dirid = 0;
				if(fieldExist($r['totable'], "belongid")) {
					if(fieldExist($r['totable'], "pagetype")) {
						$pagetypestr = " and pagetype='page' ";
					}
					$pageid=intval(getFieldValue("select id from {$r['totable']} where belongid='root' $pagetypestr order by odring,id","id"));
				}
				$pagetype="root";
			}
		}
		
		switch ($totable) {
			case "producttype":
				$linkurl="product_list/".$dirid."?id=".$pageid;
				break;
			case "products":
				$linkurl="bonus_list/".$dirid."?id=".$pageid;
				break;	
			case "news":
				if($pagetype=="root"){
					$linkurl="news_list?cur=1";
				}else{
					$linkurl="news_page?id=".$pageid."&cur=1";
				}
				break;
			case "treemenus":
				$linkurl="dbpage_page/".$dirid."?id=".$pageid;
				break;
		}
	}
	
	
	
	return $linkurl;
}


function chkCartPro(){
	global $db,$conf_user;
	$tablename="products";
	$mode=getCartMode();
	$cart=$_SESSION[$conf_user]['{$mode}_list'];
	$ProFormatList=getProFormatList();
	if(count($cart)>0){
		$sql="select id from $tablename where publish=1";
		$db->setQuery( $sql );
		$r = $db->loadRowList();
		$a=array();
		
		foreach($r as $row){
			$a[$row['id']]=1;
		}
		
		foreach($cart as $fid=>$num){
			if($a[$ProFormatList[$fid]['pid']]!=1){
				$_SESSION[$conf_user]['cart_list'][$fid]=null;
				unset($_SESSION[$conf_user]['cart_list'][$fid]);
			}
		}
		
	}
}


function AddCartProductInfo($cart,$realCart=array(),$addToCart=false,$proType = 'amtPro'){
	global $db,$conf_user;
	$tablename="products";
	if(count($cart)==0 || count($realCart)==0){
    	return $realCart;
    }
	
	$pidArr=array();
    $numArr=array();
	foreach($cart as $pid=>$row){
		$pidArr[]=$pid;
		$numArr[$pid]=$row;
	}
	
	if($proType == 'freePro')
	{
		$where_str = "AND amtProChk=1";
	}
	else
	{
		$where_str = "AND amtProChk=1";
	}
	
	
	$sql="select id,name,highAmt,amtProAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND amtProChk=1";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$total=0;
	$proArr=array();
	foreach($numArr as $pid=>$row){
		
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		if(is_array($row)){
			
			foreach($row as $format1=>$row2){
				$f1=getProductFormat($pid);
				if(is_array($row2)){
					
					foreach($row2 as $format2=>$row3){
						
						$data=array();
						$data['id']=$pid;
						$data['imgname']=$imgname;
						$data['num']=$row3;
						
						
						$data['siteAmt']=$info['siteAmt'];
						
						$data['highAmt']=$info['highAmt'];
						$data['CalcHighAmt']=$data['num']*$data['highAmt'];
						$data['CalcSiteAmt']=$data['num']*$data['siteAmt'];
						$total+=$data['CalcSiteAmt'];
						$data['format']=$f1;
						$profr='';
						if($format1 && $format2){
							$data['format1']=$format1;
							$data['format2']=$format2;
							$data['format1title']=$f1['format1title'];
							$data['format2title']=$f1['format2title'];
							
							foreach($f1['format1'] as $f1arr){
								if($f1arr['id']==$format1){
									$data['format1name']=$f1arr['name'];
									break;
								}
							}
							foreach($f1['format2'][$format1] as $f2arr){
								if($f2arr['id']==$format2){
									$data['format2name']=$f2arr['name'];
									break;
								}
							}
							$profr="【{$data['format1name']} - {$data['format2name']}】";
						}
						$data['name']=_EWAYS_ADDPROD.$info['name'].$profr;
						$proArr[]=$data;
						
					}
				}else{
					$data=array();
					$data['id']=$pid;
					$data['imgname']=$imgname;
					$data['name']=_EWAYS_ADDPROD.$info['name'];
					$data['num']=$row2;
					$data['siteAmt']=$info['siteAmt'];
					
					$data['highAmt']=$info['highAmt'];
					$data['CalcHighAmt']=$data['num']*$info['highAmt'];
					$data['CalcSiteAmt']=$data['num']*$data['siteAmt'];
					$total+=$data['CalcSiteAmt'];
					if($format1){
						$data['format1']=$format1;
						$data['format1title']=$f1['format1title'];
						$data['format']=$f1;
						foreach($f1['format1'] as $f1arr){
							if($f1arr['id']==$format1){
								$data['format1name']=$f1arr['name'];
								break;
							}
						}
					}
					$proArr[]=$data;
				}
			}
		}else{
			$data['id']=$pid;
			$data['imgname']=$imgname;
			$data['name']=$info['name'];
			$data['siteAmt']=$info['siteAmt'];
			$data['highAmt']=$info['highAmt'];
			$data['num']=$row;
			$data['CalcHighAmt']=$data['num']*$info['highAmt'];
			$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
			$total+=$data['CalcSiteAmt'];
			$proArr[]=$data;
		}
	}
	if($addToCart){
		foreach($proArr as $row){
			$row['addProChk']=1;
			$realCart['data'][]=$row;
		}
	}
	
	$realCart['amt']=$realCart['amt']+$total;
	$realCart['total']=$realCart['total']+$total;
	
	$total=$realCart['total'];
	$amt=$realCart['amt'];
	$active_list=$realCart['active_list'];
	$discount=$realCart['discount'];
	$disDlvrAmt=$realCart['disDlvrAmt'];
	$usecoin=$realCart['usecoin'];
	$free_coin=$realCart['free_coin'];
	return array("data"=>$realCart,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin);
}




function getProFormatList($bid=0,$idtype=null,$getDtl=false){
	global $db,$conf_user;
	
	$name_sql = "name";
	if($_SESSION[$conf_user]['syslang'])
	{
		$name_sql = " `name_".$_SESSION[$conf_user]['syslang']."` ";
	}
	
	$where_str="";
	if($bid && $idtype=='id'){
		$where_str.=" AND id='$bid'";
	}else if($bid && $idtype=='pid'){
		$where_str.=" AND pid='$bid'";
	}
	$select_str="";
	if($getDtl){
		$select_str.=",(select $name_sql from proformat where id=format1_type) as format1title";
		$select_str.=",(select $name_sql from proformat where id=format2_type) as format2title";
		$select_str.=",(select $name_sql from proformat where id=format1) as format1name";
		$select_str.=",(select $name_sql from proformat where id=format2) as format2name";
	}
	
	$sql="select id,pid,format1_type,format2_type,format1,format2,safetystock,instock, instockchk,odring $select_str from proinstock where 1=1 $where_str";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$data[$row['id']]=$row;
	}
	
	
	
	return $data;
}

function CartProductInfo2($cart,$activeChk = null, $activeProChk = null){
	global $db,$conf_user;
	$tablename="products";
	
	$mode=getCartMode();
	
    if(count($cart)==0 && (!$_SESSION[$conf_user]['activeBundleCart'] || count($_SESSION[$conf_user]['activeBundleCart'])==0)){
    	if($mode == 'amtpro' || $mode == 'freepro' || $mode == 'e3pro')
		{
    		unset($_SESSION[$conf_user]["cart_list_mode"]);
    		JsonEnd(array("status" => 1));
    	}
    	else
    	{    		
    		unset($_SESSION[$conf_user]["cart_list_mode"]);
    		unset($_SESSION[$conf_user]["amtpro_list"]);
    		unset($_SESSION[$conf_user]["freepro_list"]);
    		unset($_SESSION[$conf_user]["activeUsedPro_arr"]);
			unset($_SESSION[$conf_user]['disDlvrAmt']);
			unset($_SESSION[$conf_user]['dlvrAmt']);
			unset($_SESSION[$conf_user]['usecoin']);
			unset($_SESSION[$conf_user]['proArr']);
			unset($_SESSION[$conf_user]['realDlvrAmt']);
			unset($_SESSION[$conf_user]['totalAmt']);
    		
			JsonEnd(array("status" => 0, "msg"=>_EWAYS_CART_EMPTY,"cnt"=>0));
    	}
    }
	
	$name_sql = "name";
	if($_SESSION[$conf_user]['syslang'])
	{
		$name_sql = " `name_".$_SESSION[$conf_user]['syslang']."` AS name";
	}
	
	
	if($mode == 'cart' || $mode == 'event')
	{
		$tmpArr = array();
		foreach($cart as $key=>$row){
			$pid=explode("|||",$key);
			
			if(($mode == 'event' && $pid[2] == 'event') || ($mode == 'cart' && $pid[2] != 'event'))
			{
				$tmpArr[$key] = $row;
			}
		}
		$cart = $tmpArr;
	}
	
    $pidArr=array();
    $numArr=array();
    $plist=getProFormatList(0,null,true);
	foreach($cart as $pid=>$row){
		$pid=explode("|||",$pid);
		$pidArr[]=$pid[0];
	}
	$total=0;
	$ntotal=0;
	$totalccv=0;
	$carchk = false; 
	if($mode=="cart"){
		if($activeChk == 'false')
		{
			$discountAble=false;
			$carchk = true;
		}
		else
		{
			$discountAble=true;
		}
		$sql="select id,$name_sql,highAmt,siteAmt,pv,bv,ccv,'0' as bonus,need_tax from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode=="bonus"){
		$discountAble=false;
		$sql="select id,$name_sql,highAmt,bonusAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode == "amtpro")
	{
		$discountAble=false;
		$sql="select id,$name_sql,highAmt,amtProAmt as siteAmt,'0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND amtProChk = 1";
	}else if($mode == "e3pro")
	{
		$discountAble=false;
		$sql="select id,$name_sql,highAmt,e3bonusAmt as siteAmt, '0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND e3bonusChk = 1";
	}else if($mode == "freepro")
	{
		$discountAble=false;
		$sql="select id,$name_sql,highAmt,'0' as siteAmt,'0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND freeProChk = 1";
	}
	else if($mode == "event")
	{
		$discountAble=false;
		$sql="select id,$name_sql,highAmt,siteAmt,'0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}
	
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$proArr=array();
	$all_num = 0;
	foreach($cart as $fid=>$num){
		$orifid = $fid;
		$fid=explode("|||",$fid);
		$pid=$fid[0];
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		
		$data['id']=$pid;
		$data['imgname']=$imgname;
		$data['name']=$info['name'];
		$data['siteAmt']=$info['siteAmt'];
		$data['pv']=$info['pv'];
		$data['bv']=$info['bv'];
		$data['bonus']=$info['bonus'];
		$data['num']=$num;
		$data['fid']=$orifid;
		$all_num += $num;
		$data['CalcHighAmt']=bcmul($data['num'],$info['highAmt'],2);
		$data['CalcSiteAmt']=bcmul($data['num'],$info['siteAmt'],2);
		$total=bcadd($data['CalcSiteAmt'],$total,2);
		$data['need_tax'] = $info['need_tax'];
		if($info['need_tax'] == '1'){
			$ntotal = bcadd($data['CalcSiteAmt'],$ntotal,2);
		}
		
		if(!empty($info['ccv'])){
			$data['unitCcv'] = $info['ccv'];
			$data['CalcCcv'] = $data['num']*$info['ccv'];
		}else{
			$data['unitCcv'] = '0';
			$data['CalcCcv'] = '0';
		}
		$totalccv+=$data['CalcCcv'];
		
		if($fid[1]){
			$format1=$plist[$fid[1]]['format1'];
			
			if($format1){
				$data['format1']=$format1;
				$data['format1title']=$plist[$fid[1]]['format1title'];
				$data['format1name']=$plist[$fid[1]]['format1name'];
				$data['format1instock']=$plist[$fid[1]]['instock'];
				$data['instockchk']=$plist[$fid[1]]['instockchk'];									  
			}
			$format2=$plist[$fid[1]]['format2'];
			if($format2){
				$data['format2']=$format2;
				$data['format2title']=$plist[$fid[1]]['format2title'];
				$data['format2name']=$plist[$fid[1]]['format2name'];
				$data['format2instock']=$plist[$fid[1]]['instock'];
				$data['instockchk']=$plist[$fid[1]]['instockchk'];									  
			}
			
			$data['name']=$info['name'];
			if($data['format1name']){
				$data['name'].="【";
				$data['name'].=$data['format1name'];
				
				if($data['format2name']){
					$data['name'].=" - ";
					$data['name'].=$data['format2name'];
				}
				
				$data['name'].="】";
			}
		}
		
		if($fid[2])
		{
			if(substr($fid[2], 0, 6) == '999999' && strlen($fid[2]) > 6)
			{
				$data['activeName'] = getFieldValue(" SELECT * FROM activeBundle WHERE id = '".substr($fid[2], 6, strlen($fid[2]) - 6)."'","name");
				
				
				
			}
			else
			{
				$data['activeName'] = getFieldValue(" SELECT * FROM active WHERE id = '".$fid[2]."'","name");
			}
		}
		
		if($mode=="event")
		{
			$data['protype'] = "event";
			$proArr[$fid[3]][]=$data;
		}
		else
		{
			$proArr[]=$data;
		}
	}
	
	if($discountAble || $carchk){
		$calc=saleCalc2($proArr, $carchk, $activeProChk);
	}
	else if($mode == 'event')
	{
		
		$eventId_arr = array();
		$eventPro_arr = array();
		foreach($cart as $key=>$row)
		{
			$pid=explode("|||",$key);
			if($pid[2] == 'event' && !empty($pid[3]))
			{
				if(!in_array($pid[3],$eventId_arr))
				{
					$eventId_arr[] = $pid[3];
				}
				$eventPro_arr[$pid[3]][$pid[0]] = (empty($eventPro_arr[$pid[3]][$pid[0]])) ? intval($row) : intval($eventPro_arr[$pid[3]][$pid[0]]) + intval($row);
			}
		}
				
		$calc=array(
			"total"=>$total,
			"ntotal"=>$ntotal,
			"totalccv"=>$totalccv,
			"amt"=>$total,
			"namt"=>$ntotal,
			"discount"=>0,
			"disDlvrAmt"=>0,
			"usecoin"=>0,
			"free_coin"=>0,
			"active_list"=>array()
		);
		
	}
	else{
		$calc=array(
				"total"=>$total,
				"ntotal"=>$ntotal,
				"totalccv"=>$totalccv,
				"amt"=>$total,
				"namt"=>$ntotal,
				"discount"=>0,
				"disDlvrAmt"=>0,
				"usecoin"=>0,
				"free_coin"=>0,
				"active_list"=>array()
			);
	}
	
	
	

	
	$total=$calc['total'];
	$totalCcv=$calc['totalCcv'];
	// $ntotal=$calc['ntotal'];
	$amt=$calc['amt'];
	// $namt=$calc['namt'];
	$active_list=$calc['active_list'];
	$discount=$calc['discount'];
	$disDlvrAmt=$calc['disDlvrAmt'];
	$usecoin=$calc['usecoin'];
	$free_coin=$calc['free_coin'];
	$activeExtraList=$calc['activeExtraList'];
	
	
	return array("data"=>$proArr,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin,"activeExtraList"=>$activeExtraList, "mode"=>$calc, 'all_num'=>$all_num, 'totalccv'=>$totalccv,"ntotal"=>$ntotal,"namt"=>$ntotal,);
}


function CartProductInfo($cart){
	global $db,$conf_user;
	$tablename="products";
	
	$mode=getCartMode();
    if(count($cart)==0){
    	unset($_SESSION[$conf_user]["cart_list_mode"]);
    	
		JsonEnd(array("status" => 0, "msg"=>_EWAYS_CART_EMPTY,"cnt"=>0));
    	
    }
    $pidArr=array();
    $numArr=array();
	foreach($cart as $pid=>$row){
		$pidArr[]=$pid;
		$numArr[$pid]=$row;
	}
	$total=0;
	if($mode=="cart"){
		$discountAble=true;
		$sql="select id,name,highAmt,siteAmt,pv,bv,bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode=="bonus"){
		$discountAble=false;
		$sql="select id,name,highAmt,bonusAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$proArr=array();
	foreach($numArr as $pid=>$row){
		
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		if(is_array($row)){
			
			foreach($row as $format1=>$row2){
				$f1=getProductFormat($pid);
				if(is_array($row2)){
					
					foreach($row2 as $format2=>$row3){
						
						$data=array();
						$data['id']=$pid;
						$data['imgname']=$imgname;
						$data['num']=$row3;
						$data['siteAmt']=$info['siteAmt'];
						$data['pv']=$info['pv'];
						$data['bv']=$info['bv'];
						$data['bonus']=$info['bonus'];
						
						$data['CalcHighAmt']=$data['num']*$info['highAmt'];
						$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
						$data['format1']=$format1;
						$data['format2']=$format2;
						$data['format1title']=$f1['format1title'];
						$data['format2title']=$f1['format2title'];
						$total+=$data['CalcSiteAmt'];
						foreach($f1['format1'] as $f1arr){
							if($f1arr['id']==$format1){
								$data['format1name']=$f1arr['name'];
								break;
							}
						}
						foreach($f1['format2'][$format1] as $f2arr){
							if($f2arr['id']==$format2){
								$data['format2name']=$f2arr['name'];
								break;
							}
						}
						$data['name']=$info['name']."【{$data['format1name']} - {$data['format2name']}】";
						$proArr[]=$data;
						
					}
				}else{
					$data=array();
					$data['id']=$pid;
					$data['imgname']=$imgname;
					$data['name']=$info['name'];
					$data['num']=$row2;
					$data['siteAmt']=$info['siteAmt'];
					$data['pv']=$info['pv'];
					$data['bv']=$info['bv'];
					$data['bonus']=$info['bonus'];
					$data['CalcHighAmt']=$data['num']*$info['highAmt'];
					$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
					$total+=$data['CalcSiteAmt'];
					$data['format1']=$format1;
					$data['format1title']=$f1['format1title'];
					foreach($f1['format1'] as $f1arr){
						if($f1arr['id']==$format1){
							$data['format1name']=$f1arr['name'];
							break;
						}
					}
					$proArr[]=$data;
				}
			}
		}else{
			$data['id']=$pid;
			$data['imgname']=$imgname;
			$data['name']=$info['name'];
			$data['siteAmt']=$info['siteAmt'];
			$data['pv']=$info['pv'];
			$data['bv']=$info['bv'];
			$data['bonus']=$info['bonus'];
			$data['num']=$row;
			$data['CalcHighAmt']=$data['num']*$info['highAmt'];
			$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
			$total+=$data['CalcSiteAmt'];
			$proArr[]=$data;
		}
	}
	
	if($discountAble){
		$calc=saleCalc($proArr,$numArr,$discountAble);
	}else{
		$calc=array(
				"total"=>$total,
				"amt"=>$total,
				"discount"=>0,
				"disDlvrAmt"=>0,
				"usecoin"=>0,
				"free_coin"=>0,
				"active_list"=>array()
			);
	}
	
	$total=$calc['total'];
	$amt=$calc['amt'];
	$active_list=$calc['active_list'];
	$discount=$calc['discount'];
	$disDlvrAmt=$calc['disDlvrAmt'];
	$usecoin=$calc['usecoin'];
	$free_coin=$calc['free_coin'];
	return array("data"=>$proArr,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin);
}

function toMLM($oid,$paid_money){ //插入訂單資訊
	global $db2,$db;
	$d = array();
	$osql = "SELECT * FROM orders where id = '$oid'";
	$db->setQuery($osql);
	$o = $db->loadRow();

	if($o['orderMode'] == 'cart'){
		$orderNum = $o['orderNum'];
		$memberid = $o['memberid'];
		$msql = "SELECT * FROM members where id = '$memberid'";
		$db->setQuery($msql);
		$m = $db->loadRow();
		$mb_no = $m['ERPID'];
		$usersql = "SELECT * from mbst where mb_no = '$mb_no'";
		$db2->setQuery($usersql);
		$user_detail = $db2->loadRow();
		$same_time=time();
		$same_time2 = date('Y-m-d H:i:s');
		$new_ord_no=pick_tmp_no();
		$new_ord_no=get_new_ordno($new_ord_no);
		$new_ord_no=$orderNum;
		if(!empty($o)){
			$d['ord_no'] = $new_ord_no;
			$d['ord_date'] = $same_time;
			$d['ord_date2'] = $same_time2;
			$d['mb_no'] = $mb_no;
			$d['mb_name'] = $user_detail['mb_name'];
			//自取
			if($_SESSION['order']['send_type'] == 1){ //目前沒有
				$arData['branch_no']=$_SESSION['manifest']['branch'];
		
				$branch = $_SESSION['manifest']['branch'];
				$branchQuery=$db->query("select jit_no from branch_list where branch_no = '$branch'");
				$branchRs=$branchQuery->fetch();
		
				$jitQuery=$db->query("select * from jit where jit_no = '$branchRs[jit_no]'");
				$jitRs=$jitQuery->fetch();
				$arData['jit_no'] = $jitRs['jit_no'];
				$arData['jit_name'] = $jitRs['jit_name'];
			}
			else{ //郵寄
				$jit_no='CN01';
				$jit_name='美國倉Usa';
			}
			$d['jit_no'] = $jit_no; //這是大馬的總倉，每區不同，如果有修改自取則要抓取 by Evan20200814
			$d['tel_send'] = $o['dlvrMobile'];
			// IF(isset($_SESSION['order']['send_type'])){
			// 	$arData['send_method']=$_SESSION['order']['send_type'];
			// }else{
			// 	$arData['send_method']=$_SESSION['manifest']['get_method'];
			// }
			$d['send_method'] = 2; //目前只有寄送
			$dlvrst=$o['dlvrStateStr'];
			$dlvrci=$o['dlvrCity'];
			$dlvrca=$o['dlvrCanton'];
			$dlvrzip = $o['dlvrZip'];
			$dlvraddr = $o['dlvrAddr'];
			$sql = "SELECT * from addrcode where id = '$dlvrci'";
			$db->setQuery($sql);
			$scity = $db->loadRow();
			// $dlvrcity=$scity['name'];
			$dlvrcity=$dlvrci;
			
			$add_send = $dlvraddr.','.$dlvrcity.','.$dlvrst;
			$d['add_send'] = $add_send;
			$d['pv_month'] = $pv_month = date('Ym');
			// $d['pv_month2'] = ;
			$d['pv_date'] = date('Y-m-d');
			$d['remark'] = $o['dlvrNote'];
			$d['io_kind'] = 1;
			if($_POST['shopping_type']=='join'){
				//入會訂單為1
				$order_kind=1;
			}else{
				//網路訂單為3
				$order_kind=2;
			}
			$d['order_kind'] = $order_kind;
			$chk_query="select week_no from date_tb where start_date <='$same_time2' and cut_date >='$same_time2'";
			$db2->setQuery($chk_query);
			$chk=$db2->loadRow();
			$d['week_no'] = $chk['week_no'];
			$d['jit_name'] = $jit_name;
			// $d['currency'] = ;
			$d['country_id'] = 'us';
			// $d['sn'] = ;
			$d['order_pv'] = $o['pv'];
			$d['order_money'] = $o['sumAmt'];
			$d['total_pv'] = $o['pv'];
			$atotal = bcsub($o['totalAmt'],$o['m_discount'],2);
			$atotal = bcsub($atotal,$o['use_points'],2);
			$atotal = bcsub($atotal,$o['cb_use_points'],2);
			// $atotal = bcsub($atotal,$o['taxfee'],2);
			$d['total_money'] = $atotal;
			$d['total_paid_money'] = $d['total_money'];
			$d['order_money'] = $d['total_money'];
			// $d['invoice_no'] = ;
			// $d['invoice_date'] = ;
			$d['update_user'] = 'H1905';
			$d['data_timestamp'] = $same_time;
			$d['create_date'] = date('Y-m-d H:i:s');
			$d['send_money'] = $o['dlvrFee'];
			$update_state = 1;//1.新增 2.修改 3.刪除
			$d['update_state'] = $update_state;
			$d['reserve'] = 'n';
			// $d['reserve_unit'] = ;
			// $d['reserve_no'] = ;
			// $d['ord_m_no'] = ;
			// $d['reason'] = ;
			// $d['ps'] = ;
			// $d['deduct'] = ;
			// $d['memo'] = '';
			// $d['memo2'] = '';
			// $d['ord_state'] = ;
			$d['boss_id'] = $user_detail['boss_id'];
			$serQ="SELECT service_no FROM service where money_to_mb_no='$mb_no'";
			$db2->setQuery($serQ);
			$serR=$db2->loadRowList();
			if(count($serR)>0){
				$serRow=$db2->loadRow();
				$d['service_no']=$serRow['service_no'];
			}else{
				$d['service_no']='id01';
			}
			// $d['chk_yn'] = ;
			$d['name_send'] = $o['dlvrName'];
			// $d['verify'] = ;
			// $d['verify_date'] = ;
			// $d['confirm'] = ;
			$d['mail_send'] = $o['email'];
			$d['sub_no'] = $mb_no;
			$d['sub_name'] = $user_detail['mb_name'];
			// $d['b_tax_oym'] = ;
			$d['b_tax_oform'] = '-1';
			$d['b_tax_okind'] = '-1';
			$btotal = bcsub($o['totalAmt'],$o['m_discount'],2);
			$btotal = bcsub($btotal,$o['taxfee'],2);
			$d['b_tax_omoney'] = $btotal;
			$d['b_tax_otax'] = $o['taxfee'];
			// $d['b_tax_ocf'] = ;
			$d['user_confirm'] = '1';
			$d['flag'] = '0';
			$d['create_user'] = 'H1905';
			$t1_s="select count(ord_no) a  from order_m where pv_month ='$pv_month'";
			$db2->setQuery($t1_s);
			$t1=$db2->loadRow();
			$d['order_seq'] = $t1['a']+1;
			$shop3 = 0;
			if($user_detail['grade_class'] >= 10){
				$shop3 = '1';
				$shop2 = '';
				$shop1 = '1';
			}else{
				$s_total = bcsub($o['totalAmt'],$o['taxfee'],2);
				$u_total = bcsub($s_total,$o['dlvrFee'],2);
				$f_total = bcsub($u_total,$o['cb_use_points']);
				$shop2 = $o['pv'].'|@|'.$f_total.'|@|'.$u_total;
				$shop1 = '0.6';
			}
			// $d['cancel_flag'] = ;
			// $d['shop_order'] = ;
			// $d['money_to_mb_no'] = ;
			// $d['service_mb_no'] = ;
			$d['shop_1'] = $shop1;
			
			
			$d['shop_2'] = $shop2;
			
			$d['shop_3'] = $shop3;
			$d['online_order'] = 1;
			$d['ord_send_no'] = '';
			if($_SESSION['order']['chk_use_birthday_voucher']==1&&$_POST['bir_prod']!="-1"&&isset($_POST['bir_prod'])){
				$sql = "select comp_price from product_m where prod_no='".$_POST['bir_prod']."'";
				$db2->setQuery($sql);
				$prod_data=$db2->loadRow();
				$cost_money=round($prod_data['comp_price']-($prod_data['comp_price']*0.79),2);
				
				
				
				$birthday_sql="select * from birthday_voucher where mb_no='$mb_no' and status=0 and chk_invalid=0 and end_date>='".date("Ymd")."' order by coupon_no limit 1";
				$db2->setQuery($birthday_sql);
				$birthday_res=$db2->loadRowList();
				if(count($birthday_res)>=1){
					$birthday_data=$db2->loadRow();
					$db2->setQuery("update birthday_voucher set ord_no ='$new_ord_no',status=1,exchange_date='".date("Ymd")."' where coupon_no ='". $birthday_data['coupon_no'] ."'");
					$db2->query();
				}
				$cost=$cost_money;
				$bir_prod=$_POST['bir_prod'].'||'.$cost_money;
			}else{
				$cost=0;
				$bir_prod='-1';
			}
			$d['bir_prod'] = $bir_prod;
			$d['cost'] = $cost;
			$d['total_bv'] = $o['m_discount'];
			$d['int_adjust'] = '0';
			// $d['service_mb'] = '';
			// $d['gst_tax'] = ;
			if(isset($_POST['MID'])){
				$MID=$_POST['MID'];
			}else{
				$MID="";
			}
			$d['MID'] = $MID;
			$d['card_od_no'] = '0';
			// $d['branch_no'] = '-1'; //目前無店取故-1
			// $d['of_no'] = ;
			// $d['discounted_bv'] = ;
			$d['length_left'] = $o['back_value'];
			$lr = '0';
			if($o['dlvrStateStr'] == 'CA'){
				$lr = '1';
			}
			$d['length_right'] = $lr;
			$d['adjust_left'] = '';
			$d['adjust_right'] = '';
			$d['reward_point'] = $o['cb_use_points'];
			$d['shop_money'] = $o['use_points'];
			$d['real_money'] = (($o['totalAmt']*1000) - ($o['m_discount']*1000) - ($o['use_points']*1000) - ($o['cb_use_points']*1000) - $o['taxfee'] * 1000)/1000;
	
	
			$go_double=0;
			while($go_double<1){
				$chk_double="select ord_no from order_m_log where ord_no='$new_ord_no'";
				$db2->setQuery($chk_double);
				$chk_doubleR=$db2->loadRowList();
				if(count($chk_doubleR)>0){			//1204	代表訂單編號重複了!	09/08/04	從log檔找ord_no
					$d['memo'].=" Double#".$new_ord_no." Date:".date('Y/m/d H:i:s',$same_time);
					$new_ord_no=pick_tmp_no();
					//取正式單號
					$new_ord_no=get_new_ordno($new_ord_no,$same_time);
					$d['ord_no']=$new_ord_no;	//重存		
					$go_double=0;					//再檢查一次
				}else{
					$go_double=1;
				}
			}
	
		}
	
		$sql=dbInsert('order_m',$d);
		$db2->setQuery($sql);
		$db2->query();
		// $dd = array();
		// $dd['o'] = $o;
		// $dd['data'] = $d;
		// JsonEnd($dd);
	
		// JsonEnd(array("status"=>'1',"S"=>$sql));
		toMLMd($o,$new_ord_no,$mb_no,$paid_money);
	}
	
}

function toMLMd($o,$ord_no,$mb_no,$paid_money){
	global $db,$db2;
	$order_id = $o['id'];
	$sql = "SELECT * from orderdtl where oid = '$order_id'";
	$db->setQuery($sql);
	$orderdtl = $db->loadRowList();

	$sql = " SELECT pid, format1, format2, code FROM proinstock WHERE 1=1 ";
	$db->setQuery( $sql );
	$proinstock_arr = $db->loadRowList();
	$proinstock_code_list = array();
	if(count($proinstock_arr) > 0)
	{
		foreach($proinstock_arr as $row)
		{
			$proinstock_code_list[$row['pid']."||".$row['format1']."||".$row['format2']] = $row['code'];
		}
	}
	
	if(count($orderdtl) > 0){
		$i = 1;
		foreach ($orderdtl as $each) {
			$od = array();
			$od['ord_no'] = $ord_no;
			$pro_code = $proinstock_code_list[$each['pid']."||".$each['format1']."||".$each['format2']];
			$pdsql = "SELECT * FROM product_m where prod_no = '$pro_code'";;
			$db2->setQuery($pdsql);
			$pd = $db2->loadRow();
			$return_money = $pd['return_money'];
			$od['prod_no'] = $pro_code;
			// $od['prod_sn'] = ;
			$od['seq_no'] = $i;
			$od['price'] = $each['unitAmt'];
			$od['qty'] = $each['quantity'];
			$od['qty_old'] = 0;
			// $od['out_qty'] = ;
			$od['unit_pv'] = bcdiv($each['pv'],$each['quantity'],2);
			$od['sub_pv'] = $each['pv'];
			$od['sub_money'] = $each['subAmt'];
			// $od['update_user'] = ;
			$od['data_timestamp'] = time();
			$od['update_state'] = 1;
			$od['barcode'] = '';
			// $od['tax_report'] = ;
			$od['return_money'] = $return_money;
			$od['unit_bv'] = bcdiv($each['m_dis'],$each['quantity'],2);
			// $od['unit_bv'] = $o['back_value'];
			$od['sub_bv'] = $each['m_dis'];
			// $od['discount_num'] = '0';
			// $od['discount'] = '';
			$i++;
			$sql = dbInsert('order_d',$od);
			$db2->setQuery($sql);
			$db2->query();
		}
	}

	$sql = " SELECT id, name, highAmt FROM products WHERE 1=1 ";
	$db->setQuery( $sql );
	$products_arr = $db->loadRowList();
	$products_name_list = array();
	$products_highAmt_list = array();
	if(count($products_arr) > 0)
	{
		foreach($products_arr as $row)
		{
			$products_name_list[$row['id']] = $row['name'];
			$products_highAmt_list[$row['id']] = $row['highAmt'];
		}
	}

	$sql = " SELECT OB.orderId , OB.activeBundleId , OB.activeBundleName, OB.price AS OB_price, OB.pv AS OB_pv, OB.bv AS OB_bv , OBD.* FROM orderBundle OB LEFT JOIN orderBundleDetail OBD ON OB.id = OBD.orderBundleId WHERE OB.orderId = $order_id";
	$db->setQuery( $sql );
	$orderBundle_arr = $db->loadRowList();
	$obd_otherProduct_price_sum = $obd_otherProduct_pv_sum = $obd_otherProduct_bv_sum = 0;

	$j = $i+1;
	if(count($orderBundle_arr) > 0){
		
		foreach ($orderBundle_arr as $each) {
			$od = array();
			$od['ord_no'] = $ord_no;
			$pro_code = $proinstock_code_list[$each['productId']."||".$each['productFormat1']."||".$each['productFormat2']];
			$pdsql = "SELECT * FROM product_m where prod_no = '$pro_code'";;
			$db2->setQuery($pdsql);
			$pd = $db2->loadRow();
			$return_money = $pd['return_money'];
			$od['prod_no'] = $pro_code;
			// $od['prod_sn'] = ;
			$od['seq_no'] = $j;
			$od['price'] = $each['unitAmt'];
			$od['qty'] = $each['quantity'];
			$od['qty_old'] = 0;
			// $od['out_qty'] = ;
			$od['unit_pv'] = bcdiv($each['pv'],$each['quantity'],2);
			$od['sub_pv'] = $each['pv'];
			$od['sub_money'] = $each['subAmt'];
			// $od['update_user'] = ;
			$od['data_timestamp'] = time();
			$od['update_state'] = 1;
			// $od['barcode'] = ;
			// $od['tax_report'] = ;
			$od['return_money'] = $return_money;
			$od['unit_bv'] = $o['back_value'];
			$od['sub_bv'] = $each['m_dis'];
			$od['discount_num'] = '0';
			$od['discount'] = $each['m_dis'];
			$j++;
			$sql = dbInsert('order_d',$od);
			$db2->setQuery($sql);
			$db2->query();
		}
	}
	$orderBundle_list = array();
	$orderBundle_detail_list = array();

	paid($o,$ord_no,$mb_no,$paid_money);


}

function paid($o,$ord_no,$mb_no,$paid_money){
	global $db2;
	$p = array();
	$p['ord_no'] = $ord_no;
	$p['seq_no'] = 1;
	$p['pdate'] = date('Y-m-d');
	$p['paid_way'] = '1';
	$p['mb_no'] = $mb_no;
	$p['paid_money'] = $paid_money;
	$p['remark'] = '';
	$p['update_user'] = 'H1905';
	$p['data_timestamp'] = time();
	$p['update_state'] = '1';
	$p['ticket_no'] = '';
	$p['money_date'] = date('Y-m-d');
	$p['bank_ac'] = '';
	// $p['real_money'] = ;
	$p['paid_mb_no'] = '';
	$p['give_method'] = '';
	$sql = dbInsert('paid',$p);
	$db2->setQuery($sql);
	$db2->query();
}

function pick_tmp_no(){
	global $db2;
	if(session_id()!=''){
		$now_time = time();
		$arData = array();
		
		$arData['pick_user']='front_web';
		$arData['pick_time']=$now_time;
		$arData['sid']=session_id();
		$nosql = dbInsert("order_no_temp",$arData);
		$db2->setQuery($nosql);
		$db2->query();
		$nsql="select sn from order_no_temp where sid ='".session_id()."' and pick_time ='".$now_time."' and pick_user ='front_web'";
		$db2->setQuery($nsql);
		$no_data=$db2->loadRow();
		return $no_data['sn'];
	}
}

function dbInsert($table,$arFieldValues){
	$fields=array_keys($arFieldValues);
	$values=array_values($arFieldValues);
	$secVals=array();
	foreach($values as $val){
		$secVals[]=chgStr($val);
	}
	$sql_str="INSERT INTO ".$table."(";
	$sql_str.=join(",",$fields);
	$sql_str.=")VALUES(";
	$sql_str.=join(",",$secVals);
	$sql_str.=")";
	return $sql_str;
}

function dbInsert_o($table,$arFieldValues){
	$fields=array_keys($arFieldValues);
	$values=array_values($arFieldValues);

	$sql_str="INSERT INTO ".$table."(";
	$sql_str.=join(",",$fields);
	$sql_str.=")VALUES(";
	$sql_str.=join(",",$values);
	$sql_str.=")";
	return $sql_str;
}

function dbInsertPdo($table,$arFieldValues){
	$field_str = '';
	$value_str = '';
	foreach($arFieldValues as $key => $value){
		$field_str .= $key.',';
		if($key == 'lpk05' || $key == 'lpkud13'){
			if(!empty($value)){
				$value_str .= '"to_date("'.$value.'","yyyy/mm/dd")",';
			}else{
				$value_str .= "'',";
			}
			
		}else{
			if(!empty($value)){
				$value_str .= "'".$value."',";
			}else{
				$value_str .= "'',";
			}
			
		}
	}
	$field_str = trim($field_str,',');
	$value_str = trim($value_str,',');
	$sql_str="INSERT INTO ".$table."(";
	$sql_str.=$field_str;
	$sql_str.=")VALUES(";
	$sql_str.=$value_str;
	$sql_str.=")";
	return $sql_str;
}


function dbUpdate($table,$arFieldValues,$sWhere=NULL){
	$fields=array_keys($arFieldValues);
	$values=array_values($arFieldValues);
	$arSet=array();
	foreach($arFieldValues as $field=>$val){
		$arSet[]=$field."=".chgStr($val);
	}
	$sSet=implode(",",$arSet);
	$sql_str="UPDATE ".$table." SET ".$sSet;
	if(strlen(trim($sWhere))>0){
		$sql_str.=" WHERE ".$sWhere;
	}
	// echo $sql_str."<br>";
	return $sql_str;
}

function chgStr($str){
	return "'".str_replace("'","''",$str)."'";
}

function get_new_ordno($temp_id){ //add by Dale 20120323

	global $db2;
	$true_flag=0;
	while($true_flag==0){
		$arData = array();
		$d1=date('Y-m-d');
		$d2=date('Ymd');
		$arData['sn']=$temp_id;
		$arData['datetime']=$d1;
		$fsql=dbInsert("order_no_new",$arData);
		$db2->setQuery($fsql);
		$db2->query();
		$q1_s="select * from order_no_new where sn='".$temp_id."' order by order_no desc";
		$db2->setQuery($q1_s);
		$q1=$db2->loadRow();
		
		//$ord_no=$d2.add0tostrleft($q1['order_no'],4);
		//order_m
		/*
		$q2_s="update order_m set ord_no ='". $ord_no   ."' where ord_no ='".$temp_id."'";
		$db->query($q2_s);
		
		//order_d
		$q2_s="update order_d set ord_no ='". $ord_no   ."' where ord_no ='".$temp_id."'";
		$db->query($q2_s);
		
		//order_d
		$q2_s="update order_d set ord_no ='". $ord_no   ."' where ord_no ='".$temp_id."'";
		$db->query($q2_s);
		*/
		// return $d2.add0tostrleft($q1['order_no'],4);
		$sno=$d2.str_pad($q1['order_no'],4,'0',STR_PAD_LEFT);
		$db2->setQuery("select ord_no from order_m where ord_no='".$sno."'");
		$ord_res=$db2->loadRowList();
		if(count($ord_res)==0){
			$true_flag=1;
		}
	}
	return $sno;
}

function get_user_info_m($uid=0)
{
	global $db, $db2, $conf_user, $tablename;

	if(empty($uid)){
		$uid = LoginChk();
	}
	
	$sql = "select * from members where id='$uid'";
	$db->setQuery($sql);
	$r = $db->loadRow();
	$data = array();
	$data['mb_no'] = $r['ERPID'];
	$mb_no = $data['mb_no'];
	$data['boss_id'] = $r['cardnumber'];

	$sql2 = "select * from mbst where mb_no = '$mb_no'";
	$db2->setQuery($sql2);
	$r2 = $db2->loadRow();
	$data['mb_grade'] = $r2['grade_class'];


	return $data;
}

function forthousand($int){
	if((strlen(trim($int))>1)&&($int>0)){
		$pis = explode(".", $int);
		//print_r($pis);
		$a=$pis['0'];
		$len=strlen(trim($a));
		$lll=$len-1;
		for($f=0;$f<$len;$f++){
			$rea[$f]=$a[$lll];
			$lll--;
		}
		$j=0;
		for($i=0;$i<$len;$i++){
			$tmp1[$j]=$rea[$i];
			$j++;
			if(($i%3==2)&&($i!=($len-1))){
				$tmp1[$j]=",";
				$j++;
			}
		}
		$pis[0]=array_reverse($tmp1);
		if(isset ($pis[1])){
			$ans=implode($pis[0]).".".$pis[1];
		}else{
			$ans=implode($pis[0]);
		}	
		return $ans;
		//print_r($pis);
	}else{
		return $int;
	}	
}

function upload_order_pc($order_id){
	global $db3;
	$today = date('Y-m-d');
	$expire_year = date('Y',strtotime('+1 year'));
	$expire_date = $expire_year.'-12-31';
	$active_date = date('Y-m-d',strtotime("+ 1 week"));
	$osql = "SELECT o.*,m.ERPID,m.name as member_name from orders o,members m where o.id = $order_id and o.memberid = m.id";
	$db3->setQuery($osql);
	$o_detail = $db3->loadRow();
	$data = array();
	$data['mb_no'] = $o_detail['ERPID'];
	$data['orderNum'] = $o_detail['orderNum'];
	$data['active_date'] = $active_date;
	$data['expiry_date'] = $expire_date;
	$data['provide_date'] = $today;
	$data['kind'] = '1';
	$data['creator'] = 'SHOP';
	$data['creator_name'] = $o_detail['member_name'];
	//需要計算部分
	$bv = 
	$sql = "SELECT * from lv_list";
	$db3->setQuery($sql);
	$lv_list = $db3->loadRowList();
	// foreach ($lv_list as $ll) {
	// 	if($ll)
	// }
	return $lv_list;
	// $data['point'] = $point;
	// $data['rate'] = '';

}

function export_tomlm($tid, $target)
{
	global $db,$db2;
	$arData=array();

	$sql = "SELECT * FROM members where id = '$tid'";
	$db->setQuery($sql);
	$md = $db->loadRow();

	$now = date('Y-m-d');
	$arData['mb_no'] = $md['ERPID'];
	$arData['intro_no']='';
	$arData['intro_name']='';
	$arData['line_kind'] ='';
	$arData['true_intro_no2']='';
	$arData['true_intro_name2']='';
	
	$arData['service_mb_no']=''; //服務人員編號
	$arData['service_mb_name']=''; //服務人員姓名
	
	$arData['mb_name']=$md['name'];
	$arData['country']='us';
	$sid = $md['sid'];
	$gender = '0'; //0女1男
	$gender_chk_num = substr($sid,-1);
	$gender_chk = $gender_chk_num % 2;
	if($gender_chk == 1){
		$gender = '1';
	}
	$arData['sex']=$gender;
	$arData['shop_kind_grade']=''; //體系

	$arData['boss_id']=$md['sid'];
	if(!empty($md['memberWNo'])){

	}
	// $arData['service_card']='';
	$arData['service_day']='';
	
	// $arData['grade_1_date']='';
	$arData['status_note']='1'; //1.無2.未續約3.人工停權4.掛錯5.失落顧客6.轉線7.失效經銷商8.重新加入9. 線上加入註冊失敗10.解約
	
	// $arData['google_map']='';
	// $arData['note1']='';
	
	// $arData['facebook']='';
	// $arData['use_qrcode']='';
	// $arData['pg_end_date']=mktime(0,0,0,$md['pg_month'],$md['pg_day'],$md['pg_year']);
	// $arData['pg_end_date']='';

	// if($arData['grade_1_chk']=='1'&&$md['grade_1_date']!=""){
	// 	$yyy=substr($md['grade_1_date'],0,4)+1);
	// 	if($yyy>date("Y")+1){
	// 		$yyy=date("Y")+1;
	// 	}
	// 	$arData['pg_end_date']=mktime(0,0,0,'12','31',$yyy);
	// }
	$arData['have_tools']='0';
	if(!empty($md['Birthday'])){
		$arData['birthday']=strtotime($md['Birthday']);
		$arData['birthday2']=date('Y-m-d',strtotime($md['Birthday']));
	}
	// $arData['pg_date']='';
	// $arData['pg_date2']='';
	
	// $arData['pg_week_no'] = '';
	// $arData['pg_yymm'] = '';
	$arData['email']=$md['email'];
	// $arData['mate_name']='';
	// $arData['mate_id']='';
	// $arData['mb_height']='';
	// $arData['mb_weight']='';
	// $arData['tel1']='';
	$arData['tel2']=$md['phone'];
	$arData['tel3']=$md['mobile'];
	// $arData['fax']='';
	//新式地址  初版 Kevin 修改 Dale 20100719
	if(!empty($md['city'])){
		$arData['city1']=$md['city'];
	}else{
		$arData['city1']='-1';
	}

	if(!empty($md['canton'])){
		$arData['area1']=$md['canton'];
	}else{
		$arData['area1']='-1';
	}

	if(!empty($md['addr'])){
		$arData['add1']=$md['addr'];
	}else{
		$arData['add1']='-1';
	}

	if(!empty($md['fulladdr'])){
		$arData['address1']=$md['fulladdr'];
		$arData['full_add1'] = $md['fulladdr'];
	}else{
		$arData['address1']='-1';
		$arData['full_add1'] = '-1';
	}

	$arData['city2']='-1';
	$arData['city3']='-1';
	$arData['area2']='-1';
	$arData['area3']='-1';
	
	
	
		
	// $p_res = $db->query("select * from city where sn = ".$arData['city1']);
	// $p_data = $p_res->fetch();
	// $city1=$p_data['name'];
	// $p_res = $db->query("select * from area where sn = ".$arData['area1']);
	// $p_data = $p_res->fetch();
	// $area1=$p_data['name'];
	// $arData['post_no1']='';
	
	
	// $arData['city2']=$md['city2'];
	// $arData['area2']=$md['canton2'];
	// $arData['add2']=$md['addr2'];
	// $arData['address2']=$md['addr2'];	
	// $p_res=$db->query("select * FROM city where sn = ".$arData['city2']);
	// $p_data = $p_res->fetch();
	// $city2=$p_data['name'];	
	// $p_res = $db->query("select * from area where sn = ".$arData['area2']);
	// $p_data = $p_res->fetch();
	// $area2=$p_data['name'];
	// $arData['post_no2']=$md['post_no2'];
	// $arData['full_add2'] = $city2.$area2.$arData['add2'];
	
	// $arData['city3']=$md['city3'];
	// $arData['area3']=$md['area3'];
	// $arData['add3']=$md['add3'];
	// $arData['address3']=$md['add3'];
	// $p_res=$db->query("select * FROM city where sn = ".$arData['city3']);
	// $p_data = $p_res->fetch();
	// $city3=$p_data['name'];	
	// $p_res = $db->query("select * from area where sn = ".$arData['area3']);
	// $p_data = $p_res->fetch();
	// $area3=$p_data['name'];	
	// $arData['post_no3']=$md['post_no3'];
	// $arData['full_add3'] = $city3.$area3.$arData['add3'];


	$arData['true_intro_no']=trim($md['recommendCode']);
	$rmb_no = $md['recommendCode'];
	$umd = "SELECT * from mbst where mb_no = '$rmb_no'";
	$db2->setQuery($umd);
	$um = $db2->loadRow();
	if(!empty($um)){
		$arData['true_intro_name']=$um['mb_name'];
	}else{
		// $arData['true_intro_name']='';
	}
	
	// $arData['give_method']='';
	// $bank="select give_method FROM bank where give_method_no='".$arData['give_method']."'";
	// $bank_res=$db->query($bank);
	// $bank_data = $bank_res->fetch();
	// $arData['bank_name']='';
	// $arData['bank_no']='';	
	// $arData['ac_name']='';
	// $arData['bank_ac']='';
	// $arData['ac_id']='';
	$arData['mb_pwd']='1234';
	
	$arData['id_kind']=1; // 身份別 1.個人2.法人3.外國人(外僑)
	$arData['insured']=0; //補充保費
	// $arData['country']='';
	$arData['send_m']='1';
    $arData['update_user']='system_update';
	$arData['data_timestamp']=time();
	// $arData['memo']='';
	$arData['auto_order']=0;
	// $arData['area_no']='';
	//20171002 缺繳交資料修正
	// $arData['upload_id']='';
	// $arData['upload_id2']='';
	// $arData['upload_acc']='';
	// $arData['upload_size2']='';
	if($target == '0'){
		$arData['grade_class']=1;
		$arData['member_date']=$now;
		$arData['grade_1_chk']='0';
		$arData['mb_status']='1';
	}else{
		$arData['mb_status']='4';
		$arData['member_date']='';
		$arData['grade_class']=$target;
		$arData['grade_1_chk']='1';
		$futureDate=date('Y-m-d', strtotime('+1 year'));
		$arData['pg_end_date']=$futureDate;
		$arData['grade_1_date']=$now;
	}
	$arData['status_note'] = '-1';
	$arData['create_date'] = date('Y-m-d H:i:s');
	$arData['pg_date']=strtotime($now);
	$arData['pg_date2']=$now;
	$arData['pg_yymm']=date('Ym');
	$arData['pg_week_no']=date('Ym');
	
	$arData['update_state']='1';

	$iSql = dbInsert('mbst',$arData);
	$db2->setQuery($iSql);
	$db2->query();

}


function chk_cb($uid=null)
{ 
	global $db3,$conf_user;
	if (empty($uid)) {
		$uid = LoginChk();
	}
	$csql = "select * from cash_back_config where id = '1'";
	$db3->setQuery($csql);
	$cresult = $db3->loadRow();
	$give_cb = $cresult['give_cb'];
	$use_percent = $cresult['use_percent'];
	$_SESSION[$conf_user]['use_percent'];
	$mb_no = getFieldValue("select ERPID from members where id='$uid'","ERPID");
	$sql = "select * from cash_back where mb_no = '$mb_no'";
	$db3->setQuery($sql);
	$result = $db3->loadRowList();
	$today = date('Y-m-d');
	$time = date('Y-m-d', strtotime('+2 year'));
	if(empty($result)){
		$cb = array();
		$cb['mb_no'] = $mb_no;
		$cb['point'] = $give_cb;
		$cb['provide_date'] = $today;
		$cb['expiry_date'] = $time;
		$cb['note'] = '初次發放';
		$cb['remain'] = $give_cb;
		$cbsql = dbInsert('cash_back',$cb);
		$db3->setQuery($cbsql);
		$db3->query();
	}
}


function file_get_contents_curl( $url ) {

	$ch = curl_init();
  
	curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
	curl_setopt( $ch, CURLOPT_HEADER, 0 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
  
	$data = curl_exec( $ch );
	curl_close( $ch );
  
	return $data;
  
  }
  

?>