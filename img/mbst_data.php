<?php
session_start();
//require_once "../error_handler/error_handler.php";
require_once "../../class/class.db.php";
require_once "../../function/data.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);

//header("Content-type:application/vnd.ms-excel");
if(isset($_POST['org_kind'])){
	if($_POST['org_kind']==0){
		define("ORG_KIND","true_intro_no");
		define("ORGSEQ_NO","orgseq_no1");
		define("LEVEL_NO","level_no1");
		define("TRUE_INTRO_NO","true_intro_no");
		define("LEVELLINEFLAG","levellineflag1");
	}else{
		define("ORG_KIND","intro_no");
		define("ORGSEQ_NO","orgseq_no");
		define("LEVEL_NO","level_no");
		define("TRUE_INTRO_NO","intro_no");
		define("LEVELLINEFLAG","levellineflag");
	}
	if($_POST['his']==-1){
		define("TB","mbst");
	}else{
		define("TB","his_moneypv2");
	}
	
	
}

	

if($_POST['from_where']=='info'){


		$searchData="<html>

	<head>
	<meta http-equiv='Content-Language' content='zh-tw'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<title></title>
	<style type='text/css'>
	<!--
	table td{
		text-align:left;
		font-family: Verdana,Arial;
		font-size: 12px;
	}
	-->
	</style>
	</head>



	<table border='1' width='100%'>
		<tr>	
			<td>Date</td>
			<td>Member ID</td>
			<td>Member Name</td>
			<td>date join</td>
			<td>Sales performance of the month</td>
			<td>Cumulative direct sponsor</td>
			<td>Ranking</td>
			
			<td>Overflow points</td>
			
			<td>Spending points</td>
			<td>Return points</td>
			
			<td>Payment Amount</td>
			<td>Refund amount</td>
			<td>Total Bonus</td>
			<td>Pay bonus</td>
			<td>Recall of bonuses</td>
			<td>Sponsor number</td>
			<td>Sponsor name</td>
			<td>Placement number</td>
			<td>Placement name</td>
			";
			
			
			
			
	
	$searchData.="</tr>"; //	<td>合格太陽級</td> //暫不Opened
	$q1_s=" select * from his_moneyPV2 where mb_no ='". $_POST['mb_no']."'";
	$q1_s.=" and yymm like '".$_POST['year']."%'";
	
	$q1_t=$db->query($q1_s);
	while($q1=$q1_t->fetch()){
	
	
	
	
	/*Spending points Return points*/
		$refund_pv=0;
		$order_pv=0;
			
		$r1_s="select ord_no  from order_m where mb_no ='".$q1['mb_no']  ."' and pv_month ='".$q1['yymm']  ."' and io_kind ='1'";
		$r1_t=$db->query($r1_s);
		while($r1=$r1_t->fetch()){
			$r2_s="select * from order_d where ord_no ='".$r1['ord_no'] ."'";
			$r2_t=$db->query($r2_s);
			while($r2=$r2_t->fetch()){
				$order_pv+= $r2['sub_pv']-($r2['sub_pv']*$r2['deduct']/100);
			}
		}
		$q1['c1_money']=$order_pv;
		
		$r1_s="select ord_no  from order_m where mb_no ='".$q1['mb_no']  ."' and pv_month ='".$q1['yymm']  ."' and io_kind ='2'";
		$r1_t=$db->query($r1_s);
		while($r1=$r1_t->fetch()){
			$r2_s="select * from order_d where ord_no ='".$r1['ord_no']."'";
			$r2_t=$db->query($r2_s);
			while($r2=$r2_t->fetch()){
				$refund_pv+= $r2['sub_pv']-($r2['sub_pv']*$r2['deduct']/100);
			}
		}
		
		$q1['c2_money']=$refund_pv;
	
	
	
	
	
		/*
		$r1_s="select sum(money_pv) a  from money_adm  where mb_no ='".$q1['mb_no']  ."' and yymm ='".$q1['yymm']  ."' and kind in (4,1)";
		$r1_t=$db->query($r1_s);
		$r1=$r1_t->fetch();
		
		$q1['money_adm']=($r1['a']=='')?'-':$r1['a'];
		*/
		$q1['money_adm']='-';
		
		$r1_s="select sum(total_money) a  from order_m where mb_no ='". $q1['mb_no']."' and pv_month='". $q1['yymm'] ."' and io_kind='1'";
		$r1_t=$db->query($r1_s);
		$r1=$r1_t->fetch();
		$q1['p1_money']=($r1['a']=='')?'-':$r1['a'];
		
		$r1_s="select sum(total_money) a  from order_m where mb_no ='". $q1['mb_no']."' and pv_month='". $q1['yymm']."' and io_kind='2'";
		$r1_t=$db->query($r1_s);
		$r1=$r1_t->fetch();
		$q1['p2_money']=($r1['a']=='')?'-':$r1['a'];
		
		$r1['subtotal']=($q1['subtotal']=='')?'0':$q1['subtotal'];
		/*
		$r2_s="select subtotal from nopaid_tb where mb_no ='". $q1['mb_no']."' and yymm='". $q1['yymm'] ."' ";
		$r2_t=$db->query($r2_s);
		$r2=$r2_t->fetch();
		$r2['subtotal']=($r2['subtotal']=='')?'0':$r2['subtotal'];
		*/
		
		
		$q1['p3_money']=$r1['subtotal']+$r2['subtotal'];
		$q1['p4_money']=($q1['givemoney']=='')?'-':$q1['givemoney'];
		
		$r1_s="select sum(money) a   from money_adm where mb_no ='". $q1['mb_no']."' and yymm='". $q1['yymm'] ."'  and kind ='4'";
		$r1_t=$db->query($r1_s);
		$r1=$r1_t->fetch();
		$q1['p5_money']=($r1['a']=='')?'-':$r1['a'];
		
		
		$r1_s="select name   from grade where no ='". $q1['grade_class']."' ";
		$r1_t=$db->query($r1_s);
		$r1=$r1_t->fetch();
		$q1['grade_class']=$r1['name'];
		
		$searchData.="<tr>
			<td>".$q1['yymm']."</td>
			<td style=mso-number-format:'\@';>".$q1['mb_no']."</td>
			<td>".$q1['mb_name']."</td>
			<td>".date('Y/m/d',$q1['pg_date'])."</td>		
			<td>".$q1['per_m']."</td>
			
			<td>".$q1['intro_num']."</td>
			
			<td>".$q1['grade_class']."</td>
			<td>".$q1['money_adm']."</td>
			<td>".$q1['c1_money']."</td>
			<td>".$q1['c2_money']."</td>
			
			<td>".$q1['p1_money']."</td>
			<td>".$q1['p2_money']."</td>
			<td>".$q1['p3_money']."</td>
			<td>".$q1['p4_money']."</td>
			<td>".$q1['p5_money']."</td>
			<td style=mso-number-format:'\@';>".$q1['true_intro_no']."</td>
			<td>".$q1['true_intro_name']."</td>
			<td style=mso-number-format:'\@';>".$q1['intro_no']."</td>
			<td>".$q1['intro_name']."</td>
			
			
			";
			
			
			
			$searchData.="</tr>"; 
		}
  	
  	
  	$searchData.="</table></body></html>";
  	$filename=date("YmdGi").".xls";
  	$file=fopen($conf['path']['org_path'].$filename,"w");
		/*if(fwrite($file,$searchData)){
  	  
  	  //header("Content-Disposition:filename=".$filename);
  	  	toUrl("./mbst_file/".$filename);
  	  	
		//echo "file ".$filename." 已Success寫入";
	}else{
	  	echo "Save error";
	}*/
	if(fwrite($file,$searchData)){		
		echo "1_".$filename;
	}else{
		echo "1_";
	}

}


if($_POST['from_where']=='orgseq5'){




	$searchData="<html>

	<head>
	<meta http-equiv='Content-Language' content='zh-tw'>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<title></title>
	<style type='text/css'>
	<!--
	table td{
		text-align:left;
		font-family: Verdana,Arial;
		font-size: 12px;
	}
	-->
	</style>
	</head>



	<table border='1' width='100%'>
		<tr>	
			<td>Number of generation</td>
			<td>Member ID</td>
			<td>Member Name</td>
			<td>date join</td>
			<td>Sales performance of the month</td>
			<td>採計業績</td>";
	if($_POST['org_kind']==0){
		$searchData.="<td>十全Group</td><td>合格太陽級</td>";
	}
	$searchData.="</tr>"; //	<td>合格太陽級</td> //暫不Opened
	$q1_s=" select ".ORGSEQ_NO.",".LEVEL_NO." from ".TB." where mb_no ='". $_POST['mb_no']."'";
	if(TB=='his_moneypv2'){
		$q1_s.=" and yymm='".$_POST['his']."'";
	}
	$q1_t=$db->query($q1_s);
	$q1=$q1_t->fetch();
	
	$q2_s="select * from ".TB." where ".ORGSEQ_NO." like '".$q1[ORGSEQ_NO]  ."%' order by ".ORGSEQ_NO.",".LEVEL_NO;
	$q2_t=$db->query($q2_s);
	while($q2=$q2_t->fetch()){
		$str1=get_ten_yymm($q2['mb_no']);
		$str2=get_sun_yymm($q2['mb_no'],$_POST['his']);
		$searchData.="<tr>
			<td>".($q2[LEVEL_NO]-$q1[LEVEL_NO])."</td>
			<td style=mso-number-format:'\@';>".$q2['mb_no']."</td>
			<td>".$q2['mb_name']."</td>
			<td>".date('Y/m/d',$q2['pg_date'])."</td>		
			<td>".$q2['per_m']."</td>
			<td>".$q2['true_per_m']."</td>";
			if($_POST['org_kind']==0){
				$searchData.="<td>".$str1."</td><td>".$str2."</td>";
			}
			
			
		$searchData.="</tr>"; //<td>".$str2."</td>  合格太陽級 暫不Opened
  	}
  	
  	$searchData.="</table></body></html>";
  	$filename=date("YmdGi").".xls";
  	$file=fopen($conf['path']['org_path'].$filename,"w");
		/*if(fwrite($file,$searchData)){
  	  
  	  //header("Content-Disposition:filename=".$filename);
  	  	toUrl("./mbst_file/".$filename);
  	  	
		//echo "file ".$filename." 已Success寫入";
	}else{
	  	echo "Save error";
	}*/
	if(fwrite($file,$searchData)){		
		echo "1_".$filename;
	}else{
		echo "1_";
	}
}
function get_ten_yymm($mb_no){
	global $db;
	$q1_s="select * from ab_list where mb_no ='".$mb_no  ."'";
	$q1_t=$db->query($q1_s);
	
	if($q1_t->size()>0){
		$q1=$q1_t->fetch();
		return $q1['yymm'];
	}else{
		return '-';
	}

}

function get_sun_yymm($mb_no,$ym){
	global $db;
	if($ym=='-1'){
		$q1_s="select count(yymm) a  from his_moneyPV2 where mb_no ='".$mb_no  ."' and grade_class >=40 and yymm like '". date('Y') ."%'";
	}else{
		$q1_s="select count(yymm) a  from his_moneyPV2 where mb_no ='".$mb_no  ."' and grade_class >=40 and yymm like '". substr($ym,0,4) ."%'";
	}
	$q1_t=$db->query($q1_s);
	
	
	$q1=$q1_t->fetch();
	if($q1['a']!=''){
		return $q1['a'];
	}else{
		return '-';
	}
	

}
 ?>