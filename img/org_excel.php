<?php
session_start();
require_once "../../class/class.db.php";
require_once "../../function/data.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
// $conf['db']['dbName']='shaklee_new';
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);

$comp_dataQ="select * from comp_data";
$comp_dataR=$db->query($comp_dataQ);
$comp_dataD=$comp_dataR->fetch();


$mbno=$_POST['mb_no'];
$grade="grade_class";
$spacer="-";
//基本設定 欄位設定
if($_POST['org']=="0"){
	//推薦
	$f_kind='推薦';
	$fname="orgData.txt";
	$orgseq_no="orgseq_no1";
	$ORG_KIND="true_intro_no";
	$level_no="level_no1";
	$levellineflag="levellineflag1";
}else if($_POST['org']=="1"){
	//安置
	$f_kind='安置';
	$fname="orgData1.txt";
	$orgseq_no="orgseq_no";
	$ORG_KIND="intro_no";
	$level_no="level_no";
	$levellineflag="levellineflag";
}else if($_POST['org']=="2"){
	//安置
	$f_kind='組織安置';
	$fname="orgData2.txt";
	$orgseq_no="orgseq_no2";
	$ORG_KIND="intro_no";
	$level_no="level_no2";
	$levellineflag="levellineflag2";
}else{
	//沒傳參數就停住
	exit();
}

	//style=mso-number-format:'\@';
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
		text-align:center;
		border: 1px #999 solid;

	}
	-->
	</style>
	</head>

	<table  width='100%'  >
		";
	$searchData.="<tr>
					<td colspan=4>".$comp_dataD['comp_name']."</td>	
				</tr>
				<tr>
					<td colspan=4>".$_POST['mb_no'].$_POST['mb_name'].'Network chart'."</td>	
				</tr>";
	$r_query="SELECT * FROM org_data as a , org_data_demo as b  where a.yn ='Y' and a.org_kind='".$ORG_KIND."' and a.enfield = b.enfield and a.org_kind= b.org_kind  order by b.sort  ";
	$r_res=$db->query($r_query);
	$searchData.="<tr>";
	while($r_data=$r_res->fetch()){
		$searchData.="<td>".$r_data['chfield']."</td>";
		$str .= ','.$r_data['enfield'];
	}
	$searchData.="</tr>";
	
$all_org_m=0;
if($_POST['his']=='-1'){
	$query1="SELECT * FROM mbst WHERE mb_no='".$mbno."'";
}else{
	$query1="SELECT * FROM his_moneypv2 WHERE mb_no='".$mbno."' and yymm='".$_POST['his']."'";
}
$res1=$db->query($query1);
$data1=$res1->fetch();
$mbname=$data1['mb_name'];

/*切代 ---angel */
	$str_temp='';
	$orgseq_no1_res=$db->query("select orgseq_no1,mb_no from mbst where orgseq_no1 like '".$data1['orgseq_no1']."%' and grade_class>=3 and mb_no!='".$mbno."'");

	while($orgseq_no1_data=$orgseq_no1_res->fetch()){
		$str_temp.=" and (orgseq_no1 not like '".$orgseq_no1_data['orgseq_no1']."%' or orgseq_no1='".$orgseq_no1_data['orgseq_no1']."')";
	}
		/*切代 ----end */
		
		
$query1='';
if($_POST['dia']=='1'){
	$l1_query = "select mb_no,".$orgseq_no." from mbst where ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and grade_class >= 6 and mb_no <> '".$mbno."'";
		$l1_res = $db->query($l1_query);
		if($l1_res->size() > 0){
			while($l1 = $l1_res->fetch()){
				$query1.=" and (".$orgseq_no." not like '".$l1[$orgseq_no]."_%')";
			}
		}
}
if($_POST['his']=='-1'){
	$query2="SELECT mb_no,mb_name,grade_class,bgrade_class,pg_date2,per_m,level_no1,level_no,orgseq_no1,orgseq_no,g5_sorg_m,levellineflag,name,org_m".$str." FROM mbst LEFT JOIN grade ON mbst.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%'". $query1.$str_temp;
	if($_POST['no_show']=='1'){
		$query2.=" and mb_status=1";
	}
	$query2.=" ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM mbst WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%'";
	$res3=$db->query($query3);
	$data3=$res3->fetch();
}else{
	$query2="SELECT mb_no,mb_name,grade_class,bgrade_class,pg_date2,per_m,level_no1,level_no,orgseq_no1,orgseq_no,g5_sorg_m,levellineflag,name,org_m".$str." FROM his_moneypv2 LEFT JOIN grade ON his_moneypv2.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and his_moneypv2.yymm='".$_POST['his']."' ". $query1.$str_temp;
	if($_POST['no_show']=='1'){
		$query2.=" and mb_status=1";
	}
	$query2.=" ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM his_moneypv2 WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and yymm='".$_POST['his']."'";
	$res3=$db->query($query3);
	$data3=$res3->fetch();
}

	// echo $query2;
	$head_line=array();
	//echo $res2->size()."<br>";
	$head_line=array_pad($head_line,($data3[$level_no]-$data1[$level_no]),"0");
	$data=array();
	$p=0;
	while($data2=$res2->fetch()){
		$l=$data2[$level_no]-$data1[$level_no];
		$p++;
		$temp_str='';
		// if($p==5){
			// echo $l."//".$_POST['level_limit'];
			// exit;
		// }
		if($l<=$_POST['level_limit']){
			
			//echo $data2['mb_no'];
			//算出相對代數
			$all_org_m+=$data2['per_m'];
			
			$str1="";
			$i=0;
			unset($ndata2);
			if($p<$res2->size()){
				$ndata2=$res2->fetch();
			}

			while($i<$l){
				$temp_str.=$head_line[$i];
				$i++;
			}
			
			//echo $ndata2['mb_no'];
			if($data2['mb_no']==$_POST['mb_no']){
				// $str1.="⊿";
				$temp_str.="<td>⊿</td>";
			}else{
				if($ndata2[$level_no]==$data2[$level_no]){
					// $str1.="├";
					$temp_str.="<td>├</td>";
					$head_line[$l]=1;
				}else if($ndata2[$level_no]<$data2[$level_no]){
					// $str1.="└";
					$temp_str.="<td>└</td>";
					$head_line[$l]=0;
				}else if($data2[$levellineflag]=='└'){
					// $str1.="└";
					$temp_str.="<td>└</td>";
					$head_line[$l]=0;
				}else if($data2[$levellineflag]==''){
					// $str1.="├";
					$temp_str.="<td>├</td>";
					$head_line[$l]=1;
				}
			}
			$temp_str=str_replace("0","<td></td>",$temp_str);
			$temp_str=str_replace("1","<td>│</td>",$temp_str);

			
			if($p==1){
				$tt=91;
			}else{
				$tt=90;
			}
			$temp_str.="<td>".$l."代</td>";
			$temp_str.="<td>".$data2['mb_no']."</td>";
			$temp_str.="<td>".$data2['mb_name']."</td>";			
			// $temp_str.="</tr>";
			// $temp_str='   '.$str1.$data2[$set_levellineflag].$l."代".$spacer.$data2['mb_no'].$spacer.$data2['mb_name'];
			$searchData.="<tr>";
			
			$bg_res=$db->query("select name from grade where no='".$data2['bgrade_class']."'");
			$gb_data=$bg_res->fetch();
			while($r_data2=$r_res->fetch()){
				if($r_data2['enfield']=='pg_date'){
					$searchData.="<td>".Date('Y-m-d',$data2[$r_data2['enfield']])."</td>";
				}else{
					$searchData.="<td>".$data2[$r_data2['enfield']]."</td>";
				}
			}
			$searchData.=$temp_str;
			$searchData.="</tr>";
			if($p<$res2->size()){
				$res2->data_seek($p);
			}

		}
		
	}
	
	$searchData.="</table></body></html>";	
	$filename=$_SESSION['YTT']['account']."-".$_POST['mb_no']."-".Date('YmdHis').".xls";
  	$file=fopen("./MEMORG/".$filename,"w");
	
	if(fwrite($file,$searchData)){		
		echo "1_".$filename;
	}else{
		echo "1_";
	}


?>