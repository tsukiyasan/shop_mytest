<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
switch ($task) {

	case "dbpage":
		getdbpage();
		break;

}


function getdbpage() {
	global $db,$conf_user;
	
	$pageid = intval(global_get_param( $_REQUEST, 'pageid', null ,0,1  ));
	$dirid = intval(global_get_param( $_REQUEST, 'dirid', null ,0,1  ));
	
	$textList = getLanguageList("text");
	$sql_str = "";
	if($textList && count($textList) > 0)
	{
		foreach($textList as $row)
		{
			$sql_str .= " `name_{$row['code']}`,`content_{$row['code']}`, ";
			$sql_strA .= " A.`name_{$row['code']}`,A.`content_{$row['code']}`, ";
			$sql_strB .= " B.`name_{$row['code']}` AS `bname_{$row['code']}` ,";
		}
	}
	
	if(isset($pageid) && isset($dirid))
	{
		$tablename = "treemenus";
		
		if($pageid) {
			if($dirid) {
				$sql = "select A.id,A.name,{$sql_strA} A.content,A.treelevel,A.linktype,A.target,B.name as bname,{$sql_strB} B.id as bid from treemenus A,treemenus B WHERE A.id='$pageid' and A.publish=1 and A.belongid=B.id";
			} else {
				$sql = "select id, name,{$sql_str} content, treelevel, linktype, target,belongid as bid from treemenus WHERE id='$pageid' and publish=1 ";
			}
		} else {
			$dirid = $dirid ? $dirid : "root";
			$sql = "select A.id,A.name,{$sql_strA} A.content,A.treelevel,A.linktype,A.target,B.name as bname,{$sql_strB} B.id as bid 
					from treemenus A,treemenus B 
					WHERE B.id='$dirid' and A.publish=1 and A.belongid=B.id and A.pagetype='page' 
					order by A.odring,A.id limit 1";
		}
		
		$db->setQuery($sql);
		$r = $db->loadRow();
		if($r)
		{
			$data=array();
			$content=$r['content'];
			$linktype=$r['linktype'];
			$treelevel=$r['treelevel'];
			$target=$r['target'];
			$name=$r['name'];
			$bname=$r['bname'];
			
			if($_SESSION[$conf_user]['syslang'] && $r['name_'.$_SESSION[$conf_user]['syslang']])
			{
				$name=$r['name_'.$_SESSION[$conf_user]['syslang']];
			}
			if($_SESSION[$conf_user]['syslang'] && $r['bname_'.$_SESSION[$conf_user]['syslang']])
			{
				$bname=$r['bname_'.$_SESSION[$conf_user]['syslang']];
			}
			if($_SESSION[$conf_user]['syslang'] && $r['content_'.$_SESSION[$conf_user]['syslang']])
			{
				$content=$r['content_'.$_SESSION[$conf_user]['syslang']];
			}
			
			$id=$r['id'];
			$bid=$r['bid'];
			
			$sql = "SELECT id,{$sql_str} name from treemenus where belongid='$bid' and publish=1 order by odring,id";
				
			$db->setQuery($sql);
			$r0 = $db->loadRowList();
			$left=array();
			foreach($r0 as $key0=>$row0){
				$info=array();
				$info['id']=$row0['id'];
				$info['bid']=$bid;
				$info['name']=$row0['name'];
				
				if($_SESSION[$conf_user]['syslang'] && $row0['name_'.$_SESSION[$conf_user]['syslang']])
				{
					$info['name']=$row0['name_'.$_SESSION[$conf_user]['syslang']];
				}
				
				if($pageid==$info['id']){
					$info['active']="active";
				}
				$left[]=$info;
			}
			
			
			
			$data['_content']=mb_substr( strip_tags($content),0,150,"utf-8");
			
	
			$content.="<script>
				$(document).ready(
					function(){
						$('#dbpage_content').find('img').addClass('img-responsive');
					}
				);
				
			</script>";
			$data['content']=$content;
			$data['linktype']=$linktype;
			$data['treelevel']=$treelevel;
			$data['target']=$target;
			$data['name']=$name;
			$data['id']=$id;
			
			$tmpName = "關於紅崴";
			if($_SESSION[$conf_user]['syslang'])
			{
				$tmpName = getFieldValue(" SELECT `name_".$_SESSION[$conf_user]['syslang']."` AS name FROM mainmenus WHERE id='1' ","name");
			}
			
			if(isset($bname)) {
				JsonEnd(array("status" => 1, "data" => $data, "bname"=>$bname,"leftmenu"=>array(array("id"=>$bid,"name"=>$tmpName,"active"=>"active","child"=>$left))));
			} else {
				JsonEnd(array("status" => 1, "data" => $data, "leftmenu"=>array(array("id"=>$bid,"name"=>$tmpName,"active"=>"active","child"=>$left))));
			}	
			
		}
		else
		{
			JsonEnd(array("status" => 0, "errorcode" => 2));
		}
	}
	else
	{
		JsonEnd(array("status" => 0, "errorcode" => 1));
	}
	
}

include( $conf_php.'common_end.php' ); 
?>