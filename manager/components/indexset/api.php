<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
$tablename="indexconf";
userPermissionChk('indexset');
switch ($task) {
	case "update":	
		updatepage();
		break;
	case "detail":	
		showdetail();
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

function updatepage(){
	global $db, $conf_user,$tablename,$conf_index;
	$arrJson = array();
	$today = date("Y-m-d H:i:s");
	$uid=$_SESSION[$conf_user]['uid'];
	
	$dataarr= global_get_param($_REQUEST, 'dataarr', null);
	$img = global_get_param( $_REQUEST, 'img', null );
	$imgurl=$dataarr['adv'];
	$imgcnt=$dataarr['advcnt'];
	$media_url=$dataarr['media_url'];
	$media_name=$dataarr['media_name'];
	$media_date=$dataarr['media_date'];
    $sql="";
	if(count($img)>0){
		
		foreach($img as $key1=>$row1){
			if($key1<=$imgcnt){
				foreach($row1 as $key2=>$row2){
					$dataimg=$row2;
					if($imgurl[$key1][$key2]){
						$url=$imgurl[$key1][$key2]['url'];
					}
					$id=getFieldValue("select id from $tablename where num1='$key1' AND num2='$key2' AND type='adv'","id");
					if(!$id){
						$sql.="insert into $tablename (num1,num2,type,publish,linkurl,ctime,mtime,muser)
							values ('$key1','$key2','adv',1,'$url','$today','$today','$uid');";
					}
					
					$id = $dataArr['id'] ? $dataArr['id'] : $db->insertid();
					if($dataimg){
						$path=$key1."_".$key2.".jpg";
						imgupd($dataimg,$conf_index.$path,$tablename,$key1,$key2);
					}
				}
			}
		}
	}
	
	$textList = getLanguageList("text");
	$urlChk = true;
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$img = global_get_param( $_REQUEST, 'img'.$row['id'], null );
			if(count($img)>0){
				foreach($img as $key1=>$row1){
					if($key1<=$imgcnt){
						foreach($row1 as $key2=>$row2){
							$dataimg=$row2;
							
							if($urlChk)
							{
								$urlChk = false;
								if($imgurl[$key1][$key2]){
									$url=$imgurl[$key1][$key2]['url'];
								}
								$id=getFieldValue("select id from $tablename where num1='$key1' AND num2='$key2' AND type='adv'","id");
								if(!$id){
									$sql.="insert into $tablename (num1,num2,type,publish,linkurl,ctime,mtime,muser)
										values ('$key1','$key2','adv',1,'$url','$today','$today','$uid');";
								}
							}
							
							$id = $dataArr['id'] ? $dataArr['id'] : $db->insertid();
							if($dataimg){
								$path=$row['id']."_".$key1."_".$key2.".jpg";
								imgupd($dataimg,$conf_index.$path,$tablename.$row['id'],$key1,$key2);
							}
						}
					}
				}
			}
		}
	}
	
	
	if(count($imgurl)>0){
		foreach($imgurl as $key1=>$row1){
			if($key1<=count($imgcnt)){
				foreach($row1 as $key2=>$row2){
					if($key2<=$imgcnt[$key1]){
						$url=$row2['url'];
						if(strripos($url,"http://")!==0 && !strripos($url,"http://") && $url){
							$url="http://".$url;
						}
						$id=getFieldValue("select id from $tablename where num1='$key1' AND num2='$key2' AND type='adv'","id");
						if($id){
							$sql.="update $tablename set linkurl='$url',mtime='$today',muser='$uid' where id='$id';";
						}
					}else{
						$sql.="delete from $tablename where num1='$key1' AND num2='$key2' AND type='adv';";
					}
				}
			}else{
				$sql.="delete from $tablename where num1='$key1' AND type='adv';";
			}
		}
	}
	
	$sql_str = "";
	$textList = getLanguageList("text");
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$nameTmp = $dataarr['name_'.$row['code']];
			$sql_str .= " `name_".$row['code']."` = N'$nameTmp', ";
		}
	}
	
	$id=getFieldValue("select id from $tablename where type='media'","id");
	if($id){
		$sql.="update $tablename set linkurl='$media_url',name='$media_name',{$sql_str} content='$media_date',mtime='$today',muser='$uid' where id='$id';";
	}else{
		$sql.="insert into $tablename (type,publish,linkurl,name,content,ctime,mtime,muser)
							values ('media',1,'$media_url','$media_name','$media_date','$today','$today','$uid');";
	}
	
	
	
	$db->setQuery($sql);
	$db->query_batch();
	$arrJson['msg'] = _COMMON_QUERYMSG_UPD_SUS;
	$arrJson['status'] = "1";
	
	JsonEnd($arrJson);
}

function showdetail(){
	global $db, $conf_user,$tablename,$conf_index;
	
	$textList = getLanguageList("text");
	
	$dataArr=array();
	
	$sql="select * from indexconf";
	
	$db->setQuery($sql);
	$r=$db->loadRowList();
	foreach($r as $row){
		$dataArr[$row['type']][$row['num1']][$row['num2']]['img']=getimg($tablename,$row['num1'],$row['num2']);
		
		if($textList && count($textList) > 0)
		{
			foreach($textList as $text)
			{
				$dataArr[$row['type']][$row['num1']][$row['num2']]['img'.$text['id']]=getimg($tablename.$text['id'],$row['num1'],$row['num2']);
			}
		}
		
		$dataArr[$row['type']][$row['num1']][$row['num2']]['url']=$row['linkurl'];
		if($row['type']=="media"){
			$dataArr["media_url"]=$row['linkurl'];
			$dataArr["media_name"]=$row['name'];
			
			$nameList = array();
			if($textList && count($textList) > 0)
			{
				foreach($textList as $text)
				{
					$nameList[$text['code']] = $row["name_".$text['code']];
				}
			}
			
			$dataArr["media_date"]=$row['content'];
		}
		$dataArr['advcnt'][$row['num1']]=count($dataArr[$row['type']][$row['num1']]);
	}
	
	$arrJson['status'] = "1";
	$arrJson['data'] = $dataArr;
	$arrJson['nameList'] = $nameList;
	
	JsonEnd($arrJson);
	
}

include( $conf_php.'common_end.php' ); 
?>