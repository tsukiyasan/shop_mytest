<?php
require_once "../../class/class.db.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);
$query1="SELECT mb_no,orgseq_no,level_no,orgseq_no1,level_no1,intro_no,true_intro_no FROM mbst";
$res1=$db->query($query1);
while($data1=$res1->fetch()){
	$query3="UPDATE mbst SET levellineflag = NULL WHERE level_no=".($data1['level_no']+1)." and orgseq_no LIKE '".$data1['orgseq_no']."%'";
	$db->query($query3);
	$query2="SELECT * FROM mbst WHERE level_no=".($data1['level_no']+1)." and orgseq_no LIKE '".$data1['orgseq_no']."%' ORDER BY orgseq_no DESC LIMIT 1";
	$res2=$db->query($query2);
	$data2=$res2->fetch();
	$arData=array();
	$arData['levellineflag']="└";
	$sWhere="mb_no='".$data2['mb_no']."'";
	$db->dbUpdate("mbst",$arData,$sWhere);
	
	$query5="UPDATE mbst SET levellineflag1 = NULL WHERE level_no1=".($data1['level_no1']+1)." and orgseq_no1 LIKE '".$data1['orgseq_no1']."%'";
	$db->query($query5);
	$query4="SELECT * FROM mbst WHERE level_no1=".($data1['level_no1']+1)." and orgseq_no1 LIKE '".$data1['orgseq_no1']."%' ORDER BY orgseq_no1 DESC LIMIT 1";
	$res4=$db->query($query4);
	$data4=$res4->fetch();
	$arData=array();
	$arData['levellineflag1']="└";
	$sWhere="mb_no='".$data4['mb_no']."'";
	$db->dbUpdate("mbst",$arData,$sWhere);
}
?>