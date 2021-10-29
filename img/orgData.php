<?php
require_once "../../class/class.db.php";
require_once "../../function/data.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);
$mbno=$_GET['mb_no'];
$grade="grade_class";
$spacer="-";
//基本Setting Column Setting
if($_GET['org']=="0"){
	//Sponsor
	$f_kind='Sponsor';
	$fname="orgData.txt";
	$orgseq_no="orgseq_no1";
	$level_no="level_no1";
	$levellineflag="levellineflag1";
}else if($_GET['org']=="1"){
	//Place
	$f_kind='Place';
	$fname="orgData1.txt";
	$orgseq_no="orgseq_no";
	$level_no="level_no";
	$levellineflag="levellineflag";
}else if($_GET['org']=="2"){
	//Place
	$f_kind='network placement';
	$fname="orgData2.txt";
	$orgseq_no="orgseq_no2";
	$level_no="level_no2";
	$levellineflag="levellineflag2";
}else{
	//Did not pass parameters to stop
	exit();
}
$all_org_m=0;
if($_GET['his']=='-1'){
	$query1="SELECT * FROM mbst WHERE mb_no='".$mbno."'";
}else{
	$query1="SELECT * FROM his_moneypv2 WHERE mb_no='".$mbno."' and yymm='".$_GET['his']."'";
}
$res1=$db->query($query1);
$data1=$res1->fetch();
$mbname=$data1['mb_name'];
$query1='';
if($_GET['dia']=='1'){
	// 鑽石組織不顯示
	
	$l1_query = "select mb_no,".$orgseq_no." from mbst where ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and ".$grade." >= 6 and mb_no <> '".$mbno."'";
		$l1_res = $db->query($l1_query);
		if($l1_res->size() > 0){
			while($l1 = $l1_res->fetch()){
				$query1.=" and (".$orgseq_no." not like '".$l1[$orgseq_no]."_%')";
			}
		}
	// 鑽石組織不顯示
}
if($_GET['his']=='-1'){
	$query2="SELECT * FROM mbst LEFT JOIN grade ON mbst.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' ". $query1;
	if($_GET['no_show']=='1'){
		$query2.=" and mb_status=1";
	}
	$query2.=" ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM mbst WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%'";
	$res3=$db->query($query3);
	$data3=$res3->fetch();
}else{
	$query2="SELECT * FROM his_moneypv2 LEFT JOIN grade ON his_moneypv2.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and his_moneypv2.yymm='".$_GET['his']."' ". $query1;
	if($_GET['no_show']=='1'){
		$query2.=" and mb_status=1";
	}
	$query2.=" ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM his_moneypv2 WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and yymm='".$_GET['his']."'";
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
	$p++;
	//Calculate the relative generation
	$all_org_m+=$data2['per_m'];
	$l=$data2[$level_no]-$data1[$level_no];
	$str1="";
	$i=0;
	unset($ndata2);
	if($p<$res2->size()){
		$ndata2=$res2->fetch();
	}
	while($i<$l){
		$str1.=$head_line[$i];
		$i++;
	}
	if($data2['mb_no']==$_GET['mb_no']){
		$str1.="⊿";
	}else{
		if($ndata2[$level_no]==$data2[$level_no]){
			$str1.="├";
			$head_line[$l]=1;
		}else if($ndata2[$level_no]<$data2[$level_no]){
			$str1.="└";
			$head_line[$l]=0;
		}else if($data2[$levellineflag]=='└'){
			$str1.="└";
			$head_line[$l]=0;
		}else if($data2[$levellineflag]==''){
			$str1.="├";
			$head_line[$l]=1;
		}
	}
	$str1=str_replace("0","　",$str1);
	$str1=str_replace("1","│",$str1);
	$tmp=array();
	/*$tmp['mb_no']=$data2['MB_NO'];
	$tmp['status']=chgStatus($data2['MB_STATUS']);
	$tmp['pg_date']=$data2['PG_DATE2'];
	$tmp['intro_name']=$data2['intro_name2'];*/
	$temp_str='';
	if($p==1){
		$tt=91;
	}else{
		$tt=90;
	}
	
	//YesNoSuspended
	if($data2['mb_status'] == '2'){
		$show_status = $spacer.'Suspended';
	}else{
		$show_status = '';
	}
	//YesNoSuspendedEnd
	
	//$temp_str=$str1.$data2[$set_levellineflag].$l."generation".$spacer.$data2['mb_no'].$spacer.$data2['mb_name'].$spacer.date('Y-m-d',$data2['pg_date']).$spacer.$data2['name'].'('.$data2['bgrade_per'].'%)'.$show_status."";
	$temp_str=$str1.$data2[$set_levellineflag].$l."generation".$spacer.$data2['mb_no'].$spacer.$data2['mb_name'].$spacer.date('Y-m-d',$data2['pg_date']).$spacer.$data2['name'].$show_status."";
	
	
	$temp_str1= addnullstrtostrleft('',($tt-big5_strlen($temp_str))+12).'   '.number_format(addnullstrtostrleft($data2['per_m'],10))."\r\n";
	
	
//)strlen($data2['org_m']).'-'.$data2['org_m']."\r\n";//addnullstrtostrleft($data2['org_m'],(10-)))."\r\n";  
	
	$tmp['orgData']=$temp_str .$temp_str1;
	if($data2['yymm']!=''){
		$yymm=$data2['yymm'];
	}
	//If a count matrix has not yet been generated
	if(empty($leng)){
		$leng=array();
		$i=0;
		$c=count($tmp);
		while($i<$c){
			array_push($leng,0);
			$i++;
		}
		$keys=array_keys($tmp);
		$kc=count($keys);
	}
	$i=0;
	while($i<$kc){
		(strlen($tmp[$keys[$i]])>$leng[$i])?$leng[$i]=strlen($tmp[$keys[$i]]):$leng[$i];
		$i++;
	}
	array_push($data,$tmp);
	if($p<$res2->size()){
		$res2->data_seek($p);
	}
}
$c=count($data);
$i=0;
$file=fopen($fname,"w+");
$str="";
//$pp="<<達康美生活股份yes限Compnay>>\r\n\r\n";
//$pp.=$mbno.$f_kind."Network chart\r\n\r\n";
$pp.=$mbno.$mbname."\r\n"."\r\n"."\r\n";

//$pp.="                               組織世系表                      列印Hour間:".date('Y-m-d')."  小組累計   Sales performance of the month(".$yymm.")\r\n";
//$pp.="                               組織世系表                      列印Hour間:".date('Y-m-d')."  上Month累計    首席小組   Sales performance of the month(".$yymm.")\r\n";
$pp.="                               組織世系表                      列印Hour間:".date('Y-m-d')."                        Sales performance of the month(".$yymm.")\r\n";

//$pp.="-----------------------------------------------------------------------------------------------------------------------\r\n";
$pp.="----------------------------------------------------------------------------------------------------------------------------------------------\r\n";

$pp.="\r\n";
fputs($file,$pp);
while($i<$c){
	$i2=0;
	$str="";
	
	while($i2<$kc){
		$aaa='';
		$aaa.=$data[$i][$keys[$i2]]." ";
		$tl=strlen($data[$i][$keys[$i2]]);
		while($tl<$leng[$i2]){
			//$aaa.=" ";
			$tl++;
		}
		$i2++;
	}
	$str.=$aaa;   //.'-'.addnullstrtostrleft('org_m_old',(100-big5_strlen($aaa)))."\r\n"
	
	// grade
	
	
	
	
	
	fputs($file,$str);
	//echo $str."<br>";
	$i++;
}

if($_GET['his']=='-1'){
	$yymm=date('Ym');
}else{
	$yymm=$_GET['his'];
}

	$q2_s="SELECT count(mb_no) a FROM mbst where ".$orgseq_no." like '".$data1[$orgseq_no]."%' and pg_yymm = '".$yymm."'";
	$q2_t=$db->query($q2_s);
	$q2=$q2_t->fetch();
	
	//$str="--------------------------------------------------------------------------------------------------------\r\n";
	$str="---------------------------------------------------------------------------------------------------------------------------------------------\r\n";
	
	//$str.=addnullstrtostrleft('Total of the month:'.$all_org_m,110);
	$str.=addnullstrtostrleft('Total of the month:'.number_format($all_org_m),125);
	
	$str.="\r\n".$yymm."total new join members of the month  : ".number_format($q2['a']) ."\r\n\r\n";
	$str.="\r\nNumber of each ranking\r\n\r\n";
	$q1_s="SELECT *, (length(name)/3) a FROM grade order by no";
	$q1_t=$db->query($q1_s);
	while($q1=$q1_t->fetch()){
		
		$q2_s="SELECT count(mb_no) a FROM mbst where ".$orgseq_no." like '".$data1[$orgseq_no]."%' and ".$grade." = '".$q1['no']."'";
		$q2_t=$db->query($q2_s);
		$q2=$q2_t->fetch();
		//$str.=addnullstrtostrright($q1['name'],(20-($q1['a']*2))).":  ".$q2['a']." Pax20-".($q1['a']*2)."\r\n";
		$str.=$q1['name'].":  ".$q2['a']." Pax\r\n";
		
	
	
	
	}
	
	$q2_s="SELECT count(mb_no) a FROM mbst where ".$orgseq_no." like '".$data1[$orgseq_no]."%' and mb_status='1'";
	$q2_t=$db->query($q2_s);
	$q2=$q2_t->fetch();
	$str.="\r\nOfficial number: ".number_format($q2['a'])." Pax\r\n";
	$q2_s="SELECT count(mb_no) a FROM mbst where ".$orgseq_no." like '".$data1[$orgseq_no]."%' and mb_status!='1'";
	$q2_t=$db->query($q2_s);
	$q2=$q2_t->fetch();
	
	$str.="Suspension: ".number_format($q2['a'])." Pax\r\n";
	$str.="\r\nTotal network members: ".number_format($i)." Pax\r\n";
	fputs($file,$str);

header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=".basename($fname));  
readfile($fname);

function chgStatus($id){
	switch ($id){
		case '1':
			return "Normal";
			break;
		case '2':
			return "Suspended";
			break;
		case '3':
			return "Termination";
			break;
	}
}
?>