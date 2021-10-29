<?php
require_once "../../class/class.db.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);
$mbno=$_GET['mb_no'];
$grade="grade_class";
$spacer="-";
//基本Setting Column Setting
if($_GET['org']=="0"){
	#Sponsor
	$fname="orgData.txt";
	$orgseq_no="orgseq_no1";
	$level_no="level_no1";
	$levellineflag="levellineflag1";
}else if($_GET['org']=="1"){
	#Place
	$fname="orgData1.txt";
	$orgseq_no="orgseq_no";
	$level_no="level_no";
	$levellineflag="levellineflag";
}else{
	#Did not pass parameters to stop
	exit();
}
if($_GET['his']=='-1'){
	$query1="SELECT * FROM mbst WHERE mb_no='".$mbno."'";
	$res1=$db->query($query1);
	$data1=$res1->fetch();
	$query2="SELECT * FROM mbst LEFT JOIN grade ON mbst.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM mbst WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%'";
	$res3=$db->query($query3);
	$data3=$res3->fetch();
}else{
	$query1="SELECT * FROM his_moneypv2 WHERE mb_no='".$mbno."' and yymm='".$_GET['his']."'";
	$res1=$db->query($query1);
	$data1=$res1->fetch();
	$query2="SELECT * FROM his_moneypv2 LEFT JOIN grade ON his_moneypv2.".$grade."=grade.no WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and his_moneypv2.yymm='".$_GET['his']."' ORDER BY ".$orgseq_no;
	$res2=$db->query($query2);
	$query3="SELECT MAX(".$level_no.") AS ".$level_no." FROM his_moneypv2 WHERE ".$orgseq_no." LIKE '".$data1[$orgseq_no]."%' and yymm='".$_GET['his']."'";
	$res3=$db->query($query3);
	$data3=$res3->fetch();
	
}

$head_line=array();
//echo $res2->size()."<br>";
$head_line=array_pad($head_line,($data3[$level_no]-$data1[$level_no]),"0");


$data=array();
$p=0;
while($data2=$res2->fetch()){
	$p++;
	#Calculate the relative generation
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
	
	$tmp['orgData']=$str1.$data2[$set_levellineflag].$l."generation".$spacer.$data2['mb_no'].$spacer.$data2['mb_name'].$spacer.date('Y-m-d',$data2['pg_date']).$spacer.$data2['name'];
	#If a count matrix has not yet been generated
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
while($i<$c){
	$i2=0;
	$str="";
	while($i2<$kc){
		$str.=$data[$i][$keys[$i2]]." ";
		$tl=strlen($data[$i][$keys[$i2]]);
		while($tl<$leng[$i2]){
			$str.=" ";
			$tl++;
		}
		$i2++;
	}
	$str.="\r\n";
	fputs($file,$str);
	//echo $str."<br>";
	$i++;
}
fputs($file,'Total:'.$i);

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