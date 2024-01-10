<?php



include( '../../config.php' ); 
$task = global_get_param( $_REQUEST, 'task', null ,0,1  );

userPermissionChk("members");

switch ($task) {
	
	case "list": 
		members_list();
		break;
	case "detail": 
		members_detail();
		break;
	case "add": 
	case "update": 
		members_update();
		break;
	case "del": 
		members_delete();
		break;
	case "citylist":
		citylist();
		break;
	case "cantonlist":
		cantonlist();
		break;
	case "operate":
		operate();
		break;
	case "log": 
		members_log();
		break;
	case "coin": 
		members_coin();
		break;
	case "update_classtosales":
		update_classtosales();
		break;
	case "update_classtosales2":
		update_classtosales2();
		break;
	case "upd_exportChk":	
		upd_exportChk();
		break;	
}

function upd_exportChk(){
	global $db, $tablename, $conf_user;
	
	
	$filename = _MEMBERS_EXPORT_DATA.date("Ymd").".csv";
		
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$filename);
	
	$content = $_SESSION[$conf_user]['members_printcsv'];
	$content = mb_convert_encoding($content , "Big5" , "UTF-8");
	echo $content;
	exit;
	
	
}

function update_classtosales(){
	global $db, $conf_user;
	$id=$_SESSION[$conf_user]['memberid'];
	
	

	
	
	$allBonus = getFieldValue(" SELECT SUM(amt) as cnt FROM bonusRecord where memberid='$id' AND status=0", "cnt");
	
	
	$bonusValue = getFieldValue(" SELECT bonusValue FROM siteinfo", "bonusValue");
	
	if($allBonus>=$bonusValue){
		$db->setQuery("update members set salesChk=1 where id='$id'");
		$db->query();
		
		JsonEnd(array("status"=>1,"msg"=>_MEMBERS_AUDIT_MSG1));
	}else{
		
		JsonEnd(array("status"=>0,"msg"=>_MEMBERS_AUDIT_MSG2));
	}
	
	
}

function update_classtosales2(){
	global $db, $conf_user;
	$id=$_SESSION[$conf_user]['memberid'];
		
	if(true){
		$db->setQuery("update members set salesChk=1 where id='$id'");
		$db->query();
		
		JsonEnd(array("status"=>1,"msg"=>_MEMBERS_AUDIT_MSG1));
	}else{
		
		JsonEnd(array("status"=>0,"msg"=>_MEMBERS_AUDIT_MSG2));
	}
	
	
}

function members_list()
{
	global $db, $globalConf_list_limit,$conf_user;
	
	
	$id = global_get_param( $_REQUEST, 'id', null);
	
	$cnt1 = getFieldValue("SELECT count(id) as cnt1 FROM members WHERE locked = '0' and onlyMember = '0'","cnt1"); //經銷商數量
	$cnt2 = getFieldValue("SELECT count(id) as cnt2 FROM members WHERE locked = '0' and onlyMember = '1'","cnt2");
	
	if($id) {
		$table = 'orders';
		$cur = global_get_param( $_REQUEST, 'page', null);
		$sql = " select * from $table where 1=1 AND memberid = $id";	

		$db->setQuery( $sql );
		$row = $db->loadRowList();
		$cnt = count($row);
		$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
		$cur = ($cur > $pagecnt) ? 1 : $cur;
		
		$from = ($cur - 1 ) * $globalConf_list_limit;
		$end = $cur * $globalConf_list_limit;
		
		$sql_str = "codeName AS ";
		if($_SESSION[$conf_user]['syslang'])
		{
			switch ($_SESSION[$conf_user]['syslang']) {
				case 'zh-cn':
					$sql_str = "codeName_chs AS ";
					break;
				case 'en':
					$sql_str = "codeName_en AS ";
					break;
				case 'in':
					$sql_str = "codeName_in AS ";
					break;
				default :
					$sql_str = "codeName AS ";
					break;
			}
		}
		
		$data = array();
		for($i = $from; $i < min($end, $cnt); $i++) {
			foreach($row[$i] as $key=>$item){
				if($key=="status"){
					$row[$i]['statusPcode']=$row[$i]['status'];
					$row[$i]['status']=getFieldValue("select $sql_str codeName from pubcode where codeKinds='bill' AND codeValue='{$row[$i]['status']}'","codeName");
					
				}else if($key=="payType"){
					$row[$i]['payType']=pay_type($row[$i]['payType']);//getFieldValue("select $sql_str codeName from pubcode where codeKinds='payType' AND codeValue='{$row[$i]['payType']}'","codeName");
				}else if($key=="dlvrType"){
					$row[$i]['dlvrType']=take_type(null,$row[$i]['dlvrType']);//getFieldValue("select $sql_str codeName from pubcode where codeKinds='dlvrType' AND codeValue='{$row[$i]['dlvrType']}'","codeName");
				}
				
				
			}
			
			$data[] = $row[$i];
		}
		
		$arrJson['status'] = 1;
		$arrJson['data'] = $data;
		$arrJson['cnt'] = $pagecnt;
	} else {
		$proclass = global_get_param( $_REQUEST, 'proclass', null);
		$memType = global_get_param( $_REQUEST, 'memType', null);
		$memLocked = global_get_param( $_REQUEST, 'memLocked', null);
		$table = 'members';
		$cur = global_get_param( $_REQUEST, 'page', null);
		$search = global_get_param( $_REQUEST, 'search', null);
		
		$date = global_get_param( $_REQUEST, 'date', null);
		$date = str_replace('\"', '"', $date);
		$datearray = json_decode($date,true);
		$startDate = $datearray['startDate'];
		$endDate = $datearray['endDate'];
		
		$date2 = global_get_param( $_REQUEST, 'date2', null);
		$date2 = str_replace('\"', '"', $date2);
		$date2array = json_decode($date2,true);
		$startDate2 = $date2array['startDate'];
		$endDate2 = $date2array['endDate'];
		$where_str = '';
		
		$arrJson = array();
		if($search) {
			$where_str .= " OR name like '%$search%' ";
			$where_str .= " OR mobile like '%$search%' ";
			$where_str .= " OR receivename like '%$search%' ";
			$where_str .= " OR email like '%$search%' ";
			$where_str .= " OR fulladdr like '%$search%' ";
			$where_str .= " OR sid like '%$search%' ";
			$where_str .= " OR phone like '%$search%' ";
			$where_str .= " OR ERPID like '%$search%' ";
			if($where_str)
			{
				$where_str = "AND ( 1<>1 $where_str )";
			}
		}
			
		$today = date("Y-m-d");
		if($proclass!=-1){
			$where_str.=" AND salesChk='$proclass'";
		} 
		
		if($memType == '0' || $memType == '1'){
			$where_str.=" AND memType='$memType' AND onlyMember='0'";
		} 
		if($memType == '4'){
			$where_str.=" AND onlyMember='1'";
		}

		if($memType == '6'){
			$where_str.=" AND updating='1'";
		}

		if($memLocked == '0' || $memLocked == '1'){
			$where_str.=" AND locked=$memLocked";
		}
		

		if($startDate2){
			$startDate2 = date("Y-m-d", strtotime($startDate2));
			$where_str.=" AND payDate>='$startDate2'";
		}	
		if($endDate2){
			$endDate2 = date("Y-m-d", strtotime($endDate2));
			$where_str.=" AND payDate<='$endDate2'";
		}

		if($memType == '5'){

			$where_str.=" AND exMember='1'";

			if($startDate){
				$startDate = date("Y-m-d 00:00:00", strtotime($startDate));
				$where_str.=" AND exTime>='$startDate'";
			}	
			if($endDate){
				$endDate = date("Y-m-d 23:59:59", strtotime($endDate));
				$where_str.=" AND exTime<='$endDate'";
			}
		}else{
			if($startDate){
				$startDate = date("Y-m-d 00:00:00", strtotime($startDate));
				$where_str.=" AND regDate>='$startDate'";
			}	
			if($endDate){
				$endDate = date("Y-m-d 23:59:59", strtotime($endDate));
				$where_str.=" AND regDate<='$endDate'";
			}
			
		}
	
		$sql = " select * from $table where 1=1 $where_str ORDER BY id DESC";	
	
		$db->setQuery( $sql );
		$row = $db->loadRowList();
		$cnt = count($row);
		$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
		$cur = ($cur > $pagecnt) ? 1 : $cur;
		
		$from = ($cur - 1 ) * $globalConf_list_limit;
		$end = $cur * $globalConf_list_limit;
		
		$returnArray = array();
		
		for($i = $from; $i < min($end, $cnt); $i++) {
			if($row[$i]['salesChk']==0){
				
				$row[$i]['salesChk']=_MEMBERS_SALESCHK0;
			}else if($row[$i]['salesChk']==3){
				
				$row[$i]['salesChk']=_MEMBERS_SALESCHK3;
			}else if($row[$i]['salesChk']==2){
				
				$row[$i]['salesChk']=_MEMBERS_SALESCHK2;
			}else if($row[$i]['salesChk']==1){
				
				$row[$i]['salesChk']=_MEMBERS_SALESCHK1;
			}
			
			$row[$i]['regDate'] = date("Y-m-d", strtotime($row[$i]['regDate']));
			
			$memTypeStr = _MEMBERS_MEMTYPE2;
			if($row[$i]['memType'] == '1'){
				$memTypeStr = _MEMBERS_MEMTYPE1;
			}
			if($row[$i]['exMember'] == '1'){
				$memTypeStr = _MEMBERS_MEMTYPE3;
			}

			$row[$i]['memTypeStr'] = $memTypeStr;

			// $row[$i]['memTypeStr'] = ($row[$i]['memType'] == '1') ? _MEMBERS_MEMTYPE1 : _MEMBERS_MEMTYPE2;
			
			if($row[$i]['onlyMember'] == '1'){
				$row[$i]['memTypeStr'] = _MEMBERS_SALESCHK4;
			}

			$returnArray[] = $row[$i];
		}
		
		$arrJson['status'] = 1;
		$arrJson['data'] = $returnArray;
		$arrJson['cnt'] = $pagecnt;
		
		
		
		$sql = "SELECT * FROM region WHERE 1=1";
		$db->setQuery($sql);
		$list = $db->loadRowList();
		
		$addrcode_arr = array();
		
		if(count($list) > 0)
		{
			foreach($list as $info)
			{
				$addrcode_arr[$info['id']] = $info['state_u'];
			}
		}
		
		
		
		
		
		$printcsv = "";	
		$printcsv .= _MEMBERS_EXCEL_TITLE."\n";
		
		if(count($row) > 0)
		{
			foreach($row as $key=>$info)
			{
				$Birthday = (!empty($info["Birthday"])) ? date("Y/m/d", strtotime($info["Birthday"])):"";
				$payDate = (!empty($info["payDate"])) ? date("Y/m/d", strtotime($info["payDate"])):"";
				$memberYN = 'N';
				if($info['onlyMember'] == '1'){
					$memberYN = 'Y';
				}
				

				$printcsv .= $info['cardnumber'].",";
				$printcsv .= $info['sid'].",";
				$printcsv .= $info['name'].",";
				$addr_tmp = $addrcode_arr[$info['city']].' '.$info['addr'].",";
				$addr_tmp = str_replace(',', '', $addr_tmp);
				$addr_tmp = str_replace(';', '', $addr_tmp);
				$printcsv .= "\"" . $addr_tmp . "\",";
				$printcsv .= $info['phone'].",";
				$printcsv .= $info['mobile'].",";
				$addr_tmp2 = $addrcode_arr[$info['rescity']].' '.$info['resaddr'].",";
				$addr_tmp2 = str_replace(',', '', $addr_tmp);
				$addr_tmp2 = str_replace(';', '', $addr_tmp);
				$printcsv .= "\"" . $addr_tmp2 . "\",";
				$printcsv .= $info['email'].",";
				$printcsv .= $Birthday.",";
				$printcsv .= $payDate.",";
				$printcsv .= $info['recommendCode'].",";
				$printcsv .= $info['recommendName'].",";
				$printcsv .= $info['recommendPhone'].",";
				$printcsv .= $info['recommendMobile'].",";
				$printcsv .= $memberYN.",";
				$printcsv .= "\n";
			}
		}
		
		$_SESSION[$conf_user]['members_printcsv']=$printcsv;
		
	}
	$arrJson['cnt1'] = $cnt1;
	$arrJson['cnt2'] = $cnt2;
	JsonEnd($arrJson);
}

function members_detail(){
	global $db,$conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$sql = "SELECT id, name, city, canton, addr, fulladdr, mobile, email, regDate, locked,coin,delayCnt,salesChk,pv,bv,bonus,cardnumber, ERPID, sid , Birthday, payDate FROM members WHERE id = '$id'";
	$db->setQuery( $sql );
	$members_arr = $db->loadRow();	
	
	if(count($members_arr)>0)
	{
		$info = array();
		$info['id'] = $members_arr['id'];
		$info['name'] = $members_arr['name'];
		$info['city'] = $members_arr['city'];
		$info['canton'] = $members_arr['canton'];
		$info['addr'] = (empty($members_arr['addr'])) ? $members_arr['fulladdr'] : $members_arr['addr'];
		$info['phone'] = $members_arr['mobile'];
		$info['email'] = $members_arr['email'];
		$info['regDate'] = $members_arr['regDate'];
		$info['locked'] = $members_arr['locked'];
		$info['coin'] = $members_arr['coin'];
		$info['salesChk'] = $members_arr['salesChk'];
		$info['delayCnt'] = intval($members_arr['delayCnt']);
		
		$info['pv'] = (int)$members_arr['pv'];
		$info['bv'] = (int)$members_arr['bv'];
		$info['bonus'] = (int)$members_arr['bonus'];
		
		$info['cardnumber'] = $members_arr['cardnumber'];
		$info['ERPID'] = $members_arr['ERPID'];
		$info['sid'] = $members_arr['sid'];
		
		$info['Birthday'] = $members_arr['Birthday'];
		$info['payDate'] = $members_arr['payDate'];
		
		$_SESSION[$conf_user]['memberid']=$id;
	}
	
	
	$info['allBonus'] = intval(getFieldValue(" SELECT SUM(amt) as cnt FROM bonusRecord where memberid='$id' AND status=0", "cnt"));
	$arrJson['memberloginStraight'] = getFieldValue(" SELECT memberloginStraight FROM adminmanagers WHERE id= '{$_SESSION[$conf_user]['uid']}' ", "memberloginStraight");
	$arrJson['ac'] = $_SESSION[$conf_user]['uloginid'];
	$arrJson['upwd'] = getFieldValue(" SELECT passwd FROM adminmanagers WHERE id= '{$_SESSION[$conf_user]['uid']}' ", "passwd");
	$arrJson['info'] = $info;
	$arrJson['status'] = "1";
	JsonEnd($arrJson);
	
}

function members_update() {
	global $db, $conf_user;
	$arrJson = array();

	$uid = $_SESSION[$conf_user]['uid'];
	$id = global_get_param( $_REQUEST, 'id', null );
	$name = global_get_param( $_REQUEST, 'name', null );
	$city = global_get_param( $_REQUEST, 'city', null );
	$canton = global_get_param( $_REQUEST, 'canton', null );
	$addr = global_get_param( $_REQUEST, 'addr', null );
	$phone = global_get_param( $_REQUEST, 'phone', null );
	$email = global_get_param( $_REQUEST, 'email', null );
	$regDate = date("Y-m-d H:i:s");
	$locked = global_get_param( $_REQUEST, 'locked', null );
	$coin = global_get_param( $_REQUEST, 'coin', null );
	
	$pv = (int)(global_get_param( $_REQUEST, 'pv', null ));
	$bv = (int)(global_get_param( $_REQUEST, 'bv', null ));
	$bonus = (int)(global_get_param( $_REQUEST, 'bonus', null ));
	
	$cardnumber = global_get_param( $_REQUEST, 'cardnumber', null );
	$ERPID = global_get_param( $_REQUEST, 'ERPID', null );
	$Birthday = global_get_param( $_REQUEST, 'Birthday', null );
	$payDate = global_get_param( $_REQUEST, 'payDate', null );
	
	$Birthday = (!empty($Birthday)) ? date("Y-m-d", strtotime($Birthday)) : "";
	$payDate = (!empty($payDate)) ? date("Y-m-d", strtotime($payDate)) : "";
	
	$where_str = "";
	if(!empty($id))
	{
		$where_str = " AND id <> '$id'";
	}
	$chk = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE email = '$email' $where_str" , "cnt");
	if($chk > "0")
	{
		$arrJson['status'] = "0";
		
		$arrJson['msg'] = _MEMBERS_SAME_EMAIL;
		JsonEnd($arrJson);
	}
	$chk = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE cardnumber = '$cardnumber' $where_str" , "cnt");
	if($chk > "0")
	{
		$arrJson['status'] = "0";
		
		$arrJson['msg'] = _MEMBERS_SAME_CARD_NO;
		JsonEnd($arrJson);
	}
	
	if(!empty($ERPID))
	{
		$chk = getFieldValue(" SELECT COUNT(1) AS cnt FROM members WHERE ERPID = '$ERPID' $where_str" , "cnt");
		if($chk > "0")
		{
			$arrJson['status'] = "0";
			
			$arrJson['msg'] = _MEMBERS_SAME_NO;
			JsonEnd($arrJson);
		}
	}
	
	
	
	if(!$uid)$coin=0;
	
	$now = date("Y-m-d H:i:s");
	
	$sql = "INSERT INTO members (id, name, city, canton, addr, mobile, email, coin, regDate, locked, pv, bv, bonus, cardnumber, ERPID, Birthday, payDate, ctime, mtime, muser)
			VALUES ('$id', '$name', '$city', '$canton', '$addr', '$phone', '$email','$coin', '$regDate', '$locked', '$pv', '$bv', '$bonus', '$cardnumber', '$ERPID' , '$Birthday', '$payDate', '$now','$now','$uid')
			ON DUPLICATE KEY UPDATE name=VALUES(name),city=VALUES(city),canton=VALUES(canton),coin=VALUES(coin),locked=VALUES(locked),addr=VALUES(addr),mobile=VALUES(mobile),email=VALUES(email),pv=VALUES(pv),bv=VALUES(bv),bonus=VALUES(bonus),cardnumber=VALUES(cardnumber),ERPID=VALUES(ERPID),Birthday=VALUES(Birthday),payDate=VALUES(payDate),mtime=VALUES(mtime),muser=VALUES(muser)";
	
	$oldbonus=intval(getFieldValue("select bonus from members where id='$id'","bonus"));
	if($oldbonus!=$bonus){
		if($oldbonus>$bonus){
			$b=$oldbonus-$bonus;
			$status=1;
		}else{
			$b=$bonus-$oldbonus;
			$status=0;
		}
		$db->setQuery("insert into bonusRecord (memberid,rDate,amt,status,ctime,mtime,muser) 
				values ('$id','".date("Y-m-d")."','$b','$status','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','$uid')");
		$db->query();
	}
	
	
	$msg = $id ? _COMMON_QUERYMSG_UPD_SUS : _COMMON_QUERYMSG_ADD_SUS;
	
	$db->setQuery( $sql );
	if(!$db->query())
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_QUERYMSG_ADD_ERR;
		JsonEnd($arrJson);
	}
	else
	{
		$loginid = $email;
		$passwd = global_get_param( $_REQUEST, 'passwd', null );
		
		if(!empty($passwd))
		{
			$passwd=enpw($passwd);
			$str = " ,passwd = '$passwd'";
		}
		if(!empty($loginid))
		{
			$sql="UPDATE members SET loginid = '$loginid' $str WHERE id ='$id'";
			$db->setQuery($sql);
			$db->query();
		}
	}
	$arrJson['status'] = "1";
	$arrJson['msg'] = $msg;
	JsonEnd($arrJson);
}

function members_delete() {
	global $db, $conf_user;
	$arrJson = array();
	
	$id = global_get_param( $_REQUEST, 'id', null );
	
	$uid = $_SESSION[$conf_user]['uid'];
	$uname = $_SESSION[$conf_user]['uname'];
	
	$sql = "SELECT * FROM members WHERE id = '$id'";
	$db->setQuery( $sql );
	$members_arr = $db->loadRow();	
	
	
	$sql = " INSERT INTO deletelog (mambers, orders, orderdtl, uid, uname)
		VALUES ('".implode("，",$members_arr)."', '', '','$uid','$uname'); ";
	$db->setQuery($sql);
	$db->query();
	
	
	$sql = "DELETE FROM members WHERE  id='$id'";
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

function citylist()
{
	global $db, $conf_user;
	$arrJson = array();
	
	$sql = "SELECT * FROM region WHERE 1=1";
	$db->setQuery($sql);
	$list = $db->loadRowList();
	if($list) {
		$arrJson = $list;
	}
	JsonEnd($arrJson);
}



function cantonlist()
{
	global $db, $conf_user;
	$arrJson = array();
	
	$cityid = global_get_param( $_REQUEST, 'cityid', null );
	$sql = "SELECT * FROM addrcode WHERE addrlevel = 'canton' AND belongid = '$cityid'";
	$db->setQuery($sql);
	$list = $db->loadRowList();
	if($list) {
		$arrJson = $list;
	}
	JsonEnd($arrJson);
}

function operate(){
	global $db,$conf_user;
	$tablename = "members";
	$idarr = global_get_param( $_REQUEST, 'id', null ,0 ,1 );
	$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
	if(is_null($idarr)){
		
		JsonEnd(array("status"=>0, "msg"=>_ADMINMANAGERS_NO_SELECT));
	}
	
	$uid = $_SESSION[$conf_user]['uid'];
	$uname = $_SESSION[$conf_user]['uname'];
	foreach($idarr as $id)
	{	
		
		$sql = "SELECT * FROM members WHERE id = '$id'";
		$db->setQuery( $sql );
		$members_arr = $db->loadRow();	
		
		
		$sql = " INSERT INTO deletelog (mambers, orders, orderdtl, uid, uname)
			VALUES ('".implode("，",$members_arr)."', '', '','$uid','$uname'); ";
		$db->setQuery($sql);
		$db->query();
	}
	
	
	
	$id=implode(",",$idarr);
	$field="";
	if($action==1){
		$field="locked=0";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==2){
		$field="locked=1";
		$sql="update $tablename set $field where id in ($id)";
	}else if($action==3){
		$sql="";
		$sql.="delete from $tablename where id in ($id);";
	}
	$db->setQuery( $sql );
	$db->query_batch();
	
	JsonEnd(array("status"=>1,"msg"=>_EWAYS_SUCCESS));
}


function members_log()
{
	global $db, $globalConf_list_limit;
	
	
	$id = global_get_param( $_REQUEST, 'id', null);
	
	if($id) {
		$table = 'memberlog';
		$cur = global_get_param( $_REQUEST, 'page', null);
		$sql = " select * from $table where 1=1 AND memberid = $id ORDER BY id desc";	

		$db->setQuery( $sql );
		$row = $db->loadRowList();
		$cnt = count($row);
		$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
		$cur = ($cur > $pagecnt) ? 1 : $cur;
		
		$from = ($cur - 1 ) * $globalConf_list_limit;
		$end = $cur * $globalConf_list_limit;
		
		$data = array();
	
		for($i = $from; $i < min($end, $cnt); $i++) {
			foreach($row[$i] as $key=>$item){
				if($key=="type"){
					
					if($row[$i]['type'] == '2')
					{
						$row[$i]['logstr'] = $row[$i]['loginType']._MEMBERS_LOGOUT; 
					}
					else
					{
						$row[$i]['logstr'] = $row[$i]['loginType']._MEMBERS_LOGIN; 
					}
				}else if($key=="otime"){
					$row[$i]['otime']=date('Y-m-d H:i:s',strtotime($row[$i]['otime']));
				}
				
			}
			
			$data[] = $row[$i];
		}
		
		$arrJson['status'] = 1;
		$arrJson['data'] = $data;
		$arrJson['cnt'] = $pagecnt;
	}
	JsonEnd($arrJson);
}

function members_coin()
{
	global $db, $globalConf_list_limit;
	
	
	$id = global_get_param( $_REQUEST, 'id', null);
	
	if($id) {
		$table = 'orders';
		$cur = global_get_param( $_REQUEST, 'page', null);
		$sql = " select * from $table where 1=1 AND memberid = $id ORDER BY buyDate DESC";	

		$db->setQuery( $sql );
		$row = $db->loadRowList();
		$cnt = count($row);
		$pagecnt = max($cnt % $globalConf_list_limit == 0 ? floor($cnt / $globalConf_list_limit) : floor($cnt / $globalConf_list_limit) + 1, 1);
		$cur = ($cur > $pagecnt) ? 1 : $cur;
		
		$from = ($cur - 1 ) * $globalConf_list_limit;
		$end = $cur * $globalConf_list_limit;
		
		$data = array();
	
		for($i = $from; $i < min($end, $cnt); $i++) {
			
			if(empty($row[$i]['usecoin']))
			{
				continue;
			}
			else
			{
				$data[] = $row[$i];
			}
		}
		
		$arrJson['status'] = 1;
		$arrJson['data'] = $data;
		$arrJson['cnt'] = $pagecnt;
	}
	JsonEnd($arrJson);
}

include( $conf_php.'common_end.php' ); 
?>