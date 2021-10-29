<?php
session_start();
require_once "../../class/class.db.php";
require_once "../../function/data.php";
require_once "../../fpdf/ufpdf.php";
require_once "../../fpdf/chinese.php";
require_once "../../fpdf/chinese-unicode.php";

$conf=parse_ini_file("../../Connection/conf.ini",true);
GLOBAL $db;
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);


$comp_dataQ="select * from comp_data";
$comp_dataR=$db->query($comp_dataQ);
$comp_dataD=$comp_dataR->fetch();

define('FPDF_FONTPATH','font/');

$pdf=new  PDF_Unicode();

$pdf->Open();
$pdf->AddPage(L);
$pdf->AddUniCNSFont();

$mbno=$_POST['mb_no'];
$grade="grade_class";
$spacer="-";
//基本Setting Column Setting
if($_POST['org']=="0"){
	//Sponsor
	$f_kind='Sponsor';
	$fname="orgData.txt";
	$orgseq_no="orgseq_no1";
	$ORG_KIND="true_intro_no";
	$level_no="level_no1";
	$levellineflag="levellineflag1";
}else if($_POST['org']=="1"){
	//Place
	$f_kind='Place';
	$fname="orgData1.txt";
	$orgseq_no="orgseq_no";
	$ORG_KIND="intro_no";
	$level_no="level_no";
	$levellineflag="levellineflag";
}else if($_POST['org']=="2"){
	//Place
	$f_kind='network placement';
	$fname="orgData2.txt";
	$orgseq_no="orgseq_no2";
	$ORG_KIND="intro_no";
	$level_no="level_no2";
	$levellineflag="levellineflag2";
}else{
	//Did not pass parameters to stop
	exit();
}

$pdf->SetFont('uni','',24);

$pdf->ln();
$pdf->Cell(0,8,$comp_dataD['comp_name'],0,0,C);
$pdf->Ln();
$pdf->Cell(0,8,$_POST['mb_no'].$_POST['mb_name'].'Network chart',B,0,C); 
$pdf->SetFont('uni','',10);
$pdf->ln();
$str="";
$r_query="SELECT * FROM org_data as a , org_data_demo as b  where a.yn ='Y' and a.org_kind='".$ORG_KIND."' and a.enfield = b.enfield and a.org_kind= b.org_kind  order by b.sort  ";
$r_res=$db->query($r_query);
while($r_data=$r_res->fetch()){
	$len=strlen($r_data['chfield']);	
	$pdf->Cell($len+5,8,$r_data['chfield'],B,0,C);
	$str .= ','.$r_data['enfield'];
}
$pdf->Cell(0,8,"",B,0,C); 

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
	$query2="SELECT mb_no,mb_name,grade_class,bgrade_class,pg_date2,per_m,level_no1,level_no,orgseq_no1,orgseq_no,g5_sorg_m,levellineflag,name,org_m".$str." FROM mbst LEFT JOIN grade ON mbst.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' ". $query1.$str_temp;
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
	//Calculate the relative generation
	if($l<=$_POST['level_limit']){
		$all_org_m+=$data2['per_m'];
		
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
		if($data2['mb_no']==$_POST['mb_no']){
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
		$temp_str='';
		if($p==1){
			$tt=91;
		}else{
			$tt=90;
		}
		// $temp_str1=addnullstrtostrleft($data2['per_m'],10).'	'.addnullstrtostrleft($data2['g4_sorg_m'],10).'	'.addnullstrtostrleft($data2['g5_sorg_m'],10).'	'.addnullstrtostrleft($data2['g6_sorg_m'],10);
		$temp_str='   '.$str1.$data2[$set_levellineflag].$l."generation".$spacer.$data2['mb_no'].$spacer.$data2['mb_name'];
		
		$tmp['orgData']=$temp_str1.$temp_str;
		
		$bg_res=$db->query("select name from grade where no='".$data2['bgrade_class']."'");
		$gb_data=$bg_res->fetch();
		$pdf->ln();
		while($r_data2=$r_res->fetch()){
			$len=strlen($r_data2['chfield']);		
			if($r_data2['enfield']=='pg_date'){
				$pdf->Cell($len+5,4,date('Y-m-d',$data2[$r_data2['enfield']]),0,0,C);
			}else{
				$pdf->Cell($len+5,4,$data2[$r_data2['enfield']],0,0,C);
			}
		}
		$pdf->Cell(0,4,$temp_str,0,0,L); 
		if($p<$res2->size()){
			$res2->data_seek($p);
		}
	}
}
$file_name = $_SESSION['YTT']['account']."-".$_POST['mb_no']."-".Date('YmdHis').".pdf";
$pdf->Output("MEMORG/".$file_name,"F");
echo "1_".$file_name;

?>