<?php
session_start();
require_once "../../class/class.db.php";
require_once "../../function/data.php";
require_once "../../class/class.purview.php";
$conf=parse_ini_file("../../Connection/conf.ini",true);
$db=new dbClass($conf['db']['dbHost'],$conf['db']['dbUser'],$conf['db']['dbPass'],$conf['db']['dbName']);
$purview=new mb_purview($db);
if(isset($_POST['org_kind'])){
	if($_POST['org_kind']==0){
		define("ORG_KIND","true_intro_no");
		define("ORGSEQ_NO","orgseq_no1");
		define("LEVEL_NO","level_no1");
		define("TRUE_INTRO_NO","true_intro_no");
		define("LEVELLINEFLAG","levellineflag1");
		define("SEQ_NUM",4); //基因碼Unit長度
		define("LEN_NUM",-4);
	}else{
		define("ORG_KIND","intro_no");
		define("ORGSEQ_NO","orgseq_no");
		define("LEVEL_NO","level_no");
		define("TRUE_INTRO_NO","intro_no");
		define("LEVELLINEFLAG","levellineflag");
		define("SEQ_NUM",1); //基因碼Unit長度
		define("LEN_NUM",-1);
	}
	if($_POST['his']==-1){
		define("TB","mbst");
	}else{
		define("TB","his_moneypv2");
	}
}

if(isset($_POST['right_orgseq'])){
	// $r_query="SELECT * FROM org_data as a , org_data_demo as b 
		// where a.yn ='Y' and a.org_kind='".ORG_KIND."' and a.enfield = b.enfield and a.org_kind= b.org_kind  order by b.sort  ";
	$r_query="SELECT * FROM org_data where yn ='Y' and org_kind='".ORG_KIND."' order by abs(sort),no";
	$r_res=$db->query($r_query);
	$i=0;
	$org=new stdClass();
	$org->data=array();
	while($r_data=$r_res->fetch()){
		$org->$r_data['no']=$i;
		array_push($org->data,$r_data);
		$i++;
	}
	echo json_encode($org);
}
if(isset($_POST['show_award'])){
	if(isset($_POST['mb_no2'])){
		$show_query="select g.name,h.mb_no,h.mb_name,substring(h.yymm,5,6) mm,h.yymm,h.grade_class,h.per_m,h.org_m,h.intro_sum,h.subtotal from his_moneypv2 h left join grade g on h.grade_class=g.no where h.mb_no='".$_POST['mb_no2']."' and h.yymm like '".$_POST['year']."%' order by yymm";
		$show_res=$db->query($show_query);
		if($show_res->size()>0){
			$i=0;
			$data='';
			$index=array();
			while($list=$show_res->fetch()){
				if($i!=0){
					$data.=',';
				}
				$index[$list['mm']]=$i;
				$data.=json_encode($list);
				$i++;
			}
			$index='"index":'.json_encode($index);
			$data='"data":['.$data.']';
			$dpmt='{"show":{'.$index.','.$data.'}}';
			echo $dpmt;
		}else{
			echo 'none';
		}
	}else{
		echo 'none';
	}
}

if(isset($_POST['showchart'])){
	if(isset($_POST['mb_no2'])){
		/* 兩圖合併
		$mb_no=$_POST['mb_no2'];
		$name_res=$db->query("select mb_name from mbst where mb_no='".$mb_no."'");
		$name_data=$name_res->fetch();
		$mb_name=$name_data['mb_name'];
		$chd_str='&chd=t:';
		
		$chart_str=date('Y').'Year';
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i='0'.$i;
			}
			$org_m[$i] = 0;
			$auto_sum[$i] = 0;
		}
		$max_query="SELECT max(org_m) max FROM his_moneypv2 where mb_no ='".$mb_no."' and yymm like '".date('Y')."%' group by mb_no";
		$max_res=$db->query($max_query);
		$max_data=$max_res->fetch();
		$max=$max_data['max'];
		
		$query="SELECT org_m,auto_sum,substring(yymm,5,6) mm FROM his_moneypv2 where mb_no ='".$mb_no."' and yymm like '".date('Y')."%'";
		
		$list_res=$db->query($query);
		while($list=$list_res->fetch()){
			$org_m[$list['mm']]=$list['org_m'];
			$auto_sum[$list['mm']]=$list['auto_sum'];
		}
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i='0'.$i;
			}
			$chd_str.=$org_m[$i];
			if($i!=12){
				$chd_str.=",";
			}
		}
		$chd_str.="|";
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i='0'.$i;
			}
			$chd_str.=$auto_sum[$i];
			if($i!=12){
				$chd_str.=",";
			}
		}
		//  cht==>圖形Total類  lsLine Chart pPie Chart  http://code.google.com/intl/zh-TW/apis/chart/docs/gallery/chart_gall.html
		//  chs==>圖形大小  chm==>顯示各點的value(N(不計算),RGB,0(第幾條線),(點間隔),字體大小)
		//	chd==>x軸的value   chds==>y軸範圍   chxr==>y軸的顯示value  chxl==>x軸的顯示value   chco==>線條Colour    chdl==>線條Description 
		//$tmp_chart_str="http://chart.apis.google.com/chart?cht=lc&chs=600x400&chxt=x,y&chm=N,E0BC2A,0,,12|N,589A57,1,,12&chco=FDD432,589A57&chdl=組織業績|from動定貨&chxl=0:|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec&chxr=1,0,".$max."&chds=0,".$max.$chd_str;
		echo $mb_no.'@'.$mb_name.'@'.$max.'@'.$chd_str;
		*/
		// 兩圖Minute開
		$mb_no=$_POST['mb_no2'];
		$name_res=$db->query("select mb_name from mbst where mb_no='".$mb_no."'");
		$name_data=$name_res->fetch();
		$mb_name=$name_data['mb_name'];
		$chart=$_POST['chart'];
		$chd_str='&chd=t:';
		if($chart=='org_m'){
			$chart_str='Annual network sales Performance';
		}else if($chart=='auto_sum'){
			$chart_str='Number of annual auto order';
		}
		$chart_str.=' ( '.$_POST['year'].' )';
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i='0'.$i;
			}
			$month[$i] = 0;
		}
		$max_query="SELECT max(".$chart.") max FROM his_moneypv2 where mb_no ='".$mb_no."' and yymm like '".$_POST['year']."%' group by mb_no";
		$max_res=$db->query($max_query);
		$max_data=$max_res->fetch();
		$max=$max_data['max'];
		
		$query="SELECT ".$chart.",substring(yymm,5,6) mm FROM his_moneypv2 where mb_no ='".$mb_no."' and yymm like '".$_POST['year']."%'";
		//echo $query;
		$list_res=$db->query($query);
		while($list=$list_res->fetch()){
			$month[$list['mm']]=$list[$chart];
		}
		for($i=1;$i<=12;$i++){
			if($i<10){
				$i='0'.$i;
			}
			$chd_str.=$month[$i];
			if($i!=12){
				$chd_str.=",";
			}
		}
		echo $mb_no.'@'.$mb_name.'@'.$max.'@'.$chd_str.'@'.$chart_str;
	}else{
		echo 'none';
	}
}
if(isset($_POST['show_info'])){
	if(isset($_POST['mb_no2'])){
		//$show_query="select g.name,h.mb_no,h.mb_name,substring(h.yymm,5,6) mm,h.yymm,h.grade_class,h.intro_money,h.red2_money,h.org_money,h.lead_money,h.red1_money,h.red3_money,h.red7_money,h.red_money,h.ab_money from his_moneypv2 h left join grade g on h.grade_class=g.no where h.mb_no='".$_POST['mb_no2']."' and h.yymm like '".date(Y)."%' order by yymm";
$show_query="select g.name,h.mb_no,h.mb_name,substring(h.yymm,5,6) mm,h.yymm,h.grade_class,h.per_m,h.org_m,h.intro_sum from his_moneypv2 h left join grade g on h.grade_class=g.no where h.mb_no='".$_POST['mb_no2']."' and h.yymm like '".$_POST['year']."%' and length(yymm)='6' order by yymm";		//echo $show_query;
		$show_res=$db->query($show_query);
		if($show_res->size()>0){
			$i=0;
			$data='';
			$index=array();
			while($list=$show_res->fetch()){
				if($i!=0){
					$data.=',';
				}
				$index[$list['mm']]=$i;
				$data.=json_encode($list);
				$i++;
			}
			$index='"index":'.json_encode($index);
			$data='"data":['.$data.']';
			$dpmt='{"show":{'.$index.','.$data.'}}';
			echo $dpmt;
		}else{
			echo 'none';
		}
	}else{
		echo 'none';
	}
}

//Nationality權限
if(isset($_POST['chk_ct'])){
	$chk_query="select country from mbst where mb_no='".$_POST['ct_mb_no']."'";
	$chk_res=$db->query($chk_query);
	if($chk_res->size()>0){
		$row=$chk_res->fetch();	
		if($purview->chk_purview('function_'.$row['country'])){
			echo 1;
		}else{
			echo 'none';
		}
	}else{
		echo 0;
	}
}
if(isset($_POST['query_unit'])){
	$mb_query="select mb_no from mbst where mb_no='".$_POST['mb_no']."'";
	$mb_res=$db->query($mb_query);
	if($mb_res->size()<1){
		echo 'none';
	}else{
		$query2="SELECT yymm FROM his_moneypv2 WHERE mb_no='".$_POST['mb_no']."' and yymm not like '*%' GROUP BY yymm ORDER BY yymm DESC";
		$res2=$db->query($query2);
		$his_data=array();
		while($data2=$res2->fetch()){
			array_push($his_data,$data2['yymm']);
		}
		echo json_encode($his_data);
	}
	exit();
} 
if((isset($_POST['mb_no']))&&($_POST['orgseq']==0)){   //headsearchOrg
	$std=new stdClass();
	$std->data=array();
	$std->his=array();
	$qres=array();
	//對照Column 抓資料
	$str='';
	$fie_s="SELECT * from org_data where yn ='Y' order by abs(sort),no";
	$fie_t=$db->query($fie_s);
	while($data1=$fie_t->fetch()){
		$str .= ','.$data1['enfield'];
	}
	$query1="SELECT mb_no,mb_name".$str.",mb_status,pg_date,".LEVEL_NO.",grade_class,grade.name AS grade_name,grade_class,line_kind
			 FROM ".TB." 
			 LEFT JOIN grade ON ".TB.".grade_class=grade.no 
			 WHERE mb_no='".$_POST['mb_no']."'";
	/*
	$query1="SELECT * 
			 FROM ".TB." 
			 LEFT JOIN grade ON ".TB.".grade_class=grade.no 
			 WHERE mb_no='".$_POST['mb_no']."'";
	*/
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$_POST['his']."'";
	}
	$res1=$db->query($query1);
	if($res1->size()<1){
		echo 'none';
	}else{
		$data1=$res1->fetch();
		$query2="SELECT mb_no FROM ".TB." WHERE ".TRUE_INTRO_NO."='".$_POST['mb_no']."'  and mb_no<>true_intro_no ";
		if(TB=='his_moneypv2'){
			$query2.=" and yymm='".$_POST['his']."'";
		}
		$res2=$db->query($query2);
		$qres=$data1;
		$qres['mb_name']=str_replace('_','',$qres['mb_name']);
		$qres['pg_date']=date('Y-m-d',$qres['pg_date']);
		$qres['mb_status']=chgStatus($qres['mb_status']);
		$qres['parent_label']='0';
		if($res2->size()>0){
			$qres['line_label']='5';
		}else{
			$qres['line_label']='3';
		}
		/*
		if($_POST['his'] == -1){
			$query2="SELECT yymm FROM his_moneypv2 WHERE mb_no='".$_POST['mb_no']."' GROUP BY yymm ORDER BY yymm DESC";
			$res2=$db->query($query2);
			$his_data=array();
			while($data2=$res2->fetch()){
				array_push($std->his,$data2['yymm']);
			}
		}
		*/
		$std->index->$data1['mb_no']=0;
		array_push($std->data,$qres);
		echo json_encode($std);
	}
}
if((isset($_POST['sub_mb_no']))&&($_POST['orgseq']==0)){  //subsearchOrg()
	$res=$db->query("select orgseq_no1 from mbst where mb_no='".$_POST['top_mb_no']."'");
	$sRow=$res->fetch();
	/*切代 ---angel */
	$str_temp='';
	$orgseq_no1_res=$db->query("select orgseq_no1,mb_no from mbst where orgseq_no1 like '".$sRow['orgseq_no1']."%' and grade_class>=3 and mb_no!='".$_POST['top_mb_no']."'");

	while($orgseq_no1_data=$orgseq_no1_res->fetch()){
		$str_temp.=" and (orgseq_no1 not like '".$orgseq_no1_data['orgseq_no1']."%' or orgseq_no1='".$orgseq_no1_data['orgseq_no1']."')";
	}
		/*切代 ----end */
	//對照Column 抓資料
	$str='';
	$fie_s="SELECT * from org_data where yn='Y' order by abs(sort),no";
	$fie_t=$db->query($fie_s);
	while($data1=$fie_t->fetch()){
		$str .= ','.$data1['enfield'];
	}
	
	$query3="SELECT ".ORGSEQ_NO.",".LEVEL_NO." FROM ".TB." WHERE mb_no='".$_POST['sub_mb_no']."'";
	if(TB=='his_moneypv2'){
		$query3.=" and yymm='".$_POST['his']."'";
	}
	$res3=$db->query($query3);
	$data3=$res3->fetch();
	
	$std=new stdClass();
	$std->data=array();
	$qres=array();
	$query1="SELECT mb_no,mb_name".$str.",mb_status,pg_date,".TRUE_INTRO_NO.",".LEVEL_NO.",grade_class,grade.name AS grade_name,grade_class,line_kind,".LEVELLINEFLAG.",per_m 
			 FROM ".TB." 
			 LEFT JOIN grade ON ".TB.".grade_class=grade.no 
			 WHERE ".ORGSEQ_NO." LIKE '".$data3[ORGSEQ_NO]."%' and mb_no<>".TRUE_INTRO_NO." and ".LEVEL_NO.">".$data3[LEVEL_NO]." and ".LEVEL_NO."<".($data3[LEVEL_NO]+$_POST['limit']+1);
	/*
	 $query1="SELECT * 
			 FROM ".TB." 
			 LEFT JOIN grade ON ".TB.".grade_class=grade.no 
			 WHERE ".ORGSEQ_NO." LIKE '".$data3[ORGSEQ_NO]."%' and mb_no<>".TRUE_INTRO_NO." and ".LEVEL_NO.">".$data3[LEVEL_NO]." and ".LEVEL_NO."<".($data3[LEVEL_NO]+$_POST['limit']+1);
	*/
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$_POST['his']."'";
	}
	$query1.=$str_temp;
	$query1.=" ORDER BY ".ORGSEQ_NO.",pg_date";
	
	$res1=$db->query($query1);
	if($res1->size()<1){
		echo 'none';
	}else{
		$i=1;
		while($data1=$res1->fetch()){
			$query2="SELECT mb_no FROM ".TB." WHERE ".TRUE_INTRO_NO."='".$data1['mb_no']."' and mb_no<>".TRUE_INTRO_NO;
			if(TB=='his_moneypv2'){
				$query2.=" and yymm='".$_POST['his']."'";
			}
			$res2=$db->query($query2);
			$qres=$data1;
		
			$qres['mb_name']=str_replace('_','',$qres['mb_name']);
			$qres['pg_date']=date('Y-m-d',$qres['pg_date']);
			$qres['mb_status']=chgStatus($qres['mb_status']);
			
			// Has downline
			if($res2->size()>0){
				if($data1[LEVEL_NO] == ($data3[LEVEL_NO]+$_POST['limit'])){
					if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
						$qres['line_label']='5';
						$qres['parent_label']='0';
					}else{
						$qres['line_label']='4';
						$qres['parent_label']='1';
					}
				}else{
					if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
						$qres['line_label']='7';
						$qres['parent_label']='0';
					}else{
						$qres['line_label']='6';
						$qres['parent_label']='1';
					}
				}
			}else{
				if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
					$qres['line_label']='3';
					$qres['parent_label']='0';
				}else{
					$qres['line_label']='2';
					$qres['parent_label']='1';
				}
			}
			$i++;
			array_push($std->data,$qres);
		}
		$std->true_intro_no=$_POST['sub_mb_no'];
		$std->level=$_POST['level'];
		echo json_encode($std);
	}
}

if((isset($_POST['mb_no']))&&($_POST['orgseq']==5)){

	
	
	if($_POST['his']==-1){
		define("TB","mbst");
	}else{
		define("TB","his_moneypv2");
	}


	$query3="SELECT * FROM org_data WHERE org_kind = 'intro_no' and yn='Y' order by abs(sort),no";
	$res3=$db->query($query3);
	$data_ar=array();
	while($data3=$res3->fetch()){
		array_push($data_ar,$data3['enfield']);
	}
	$mb_no=$_POST['mb_no'];
	if(isset($_POST['prevP'])){
		if($_POST['prevP']=='h'){
			$query1="SELECT mb_no FROM ".TB." WHERE mb_no=intro_no";
			if(TB=='his_moneypv2'){
				$query1.=" and yymm='".$_POST['his']."'";
			}
			$res1=$db->query($query1);
			$data1=$res1->fetch();
			$mb_no=$data1['mb_no'];
		}else{
			$l=4;
			while($l>0){
				$query1="SELECT m.intro_no,(SELECT level_no FROM ".TB." WHERE mb_no=m.intro_no";
				if(TB=='his_moneypv2'){
					$query1.=" and yymm='".$_POST['his']."'";
				}
				$query1.=") AS level_no FROM ".TB." AS m WHERE m.mb_no='".$mb_no."'";
				if(TB=='his_moneypv2'){
					$query1.=" and yymm='".$_POST['his']."'";
				}
				$res1=$db->query($query1);
				$data1=$res1->fetch();
				$mb_no=$data1['intro_no'];
				$l--;
			}
		}
	}
	$chk_res=$db->query("SELECT m.mb_no FROM mbst AS m WHERE m.mb_no='".$mb_no."' and level_no1>=(SELECT level_no1 FROM mbst WHERE mb_no='".$_POST['true_mb_no']."')");
	if($chk_res->size()<1){
		$mb_no=$_POST['true_mb_no'];
	}
	$tmp_ar2=array();
	$tmp_data=new stdClass();
	$main_ar[0]=array();
	$query1="SELECT * FROM ".TB." WHERE mb_no='".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$_POST['his']."'";
	}
	$res1=$db->query($query1);
	if($res1->size() != 1){
		echo "none";
	}else{
		$data1=$res1->fetch();
		$data1['down_line']=chk_down_line($data1['mb_no'],$_POST['his']);
		array_push($main_ar[0],$data1);
		
		//20150304JOE
		if($data1['orgseq_no1']!=""){
			$i=1;	//階層數
			while($i<=4){
				$tmp_ar=array();
				$tmp_count=pow(2,($i-1));
				$j=0;	//上線unit數
				while($j<$tmp_count){
					if(($main_ar[($i-1)][$j]['mb_no'] != 'NONE')||($main_ar[($i-1)][$j]['mb_no'] != null)){
						$query2="SELECT mb_no,mb_name,grade_class,a_line_subs_new,b_line_subs_new,a_line_sum,b_line_sum,bgrade_class,line_kind,pg_week_no,yymm";
						if(count($data_ar)>0){
							$query2.=",".join(",",$data_ar);
						}
						$query2.=" FROM ".TB." WHERE intro_no='".$main_ar[($i-1)][$j]['mb_no']."' and mb_no<>intro_no ";
						if(TB=='his_moneypv2'){
							$query2.=" and yymm='".$_POST['his']."'";
						}
						
						$query2.="ORDER BY line_kind";
						$res2=$db->query($query2);
						if($res2->size()>1){
							$d=0;
							while(($data2=$res2->fetch())&&($d<2)){
								$data2['down_line']=chk_down_line($data2['mb_no'],$_POST['his']);
								array_push($tmp_ar,$data2);
								$d++;
							}
						}else if($res2->size()==1){
							$data2=$res2->fetch();
							if($data2['line_kind']=='1'){
								$data2['down_line']=chk_down_line($data2['mb_no'],$_POST['his']);
								array_push($tmp_ar,$data2);
								$tmp_ar2['mb_no']='NONE';
								array_push($tmp_ar,$tmp_ar2);
							}else{
								$tmp_ar2['mb_no']='NONE';
								array_push($tmp_ar,$tmp_ar2);
								$data2['down_line']=chk_down_line($data2['mb_no'],$_POST['his']);
								array_push($tmp_ar,$data2);
							}
						}else{
							$tmp_ar2['mb_no']='NONE';
							array_push($tmp_ar,$tmp_ar2);
							$tmp_ar2['mb_no']='NONE';
							array_push($tmp_ar,$tmp_ar2);
						}
					}else{
						$tmp_ar2['mb_no']='NONE';
						array_push($tmp_ar,$tmp_ar2);
						$tmp_ar2['mb_no']='NONE';
						array_push($tmp_ar,$tmp_ar2);
					}
					$j++;
				}
				$i++;
				array_push($main_ar,$tmp_ar);
			}
		}
		echo json_encode($main_ar);
	}
}
if((isset($_POST['find_mb_no']))&&($_POST['orgseq']==9)){
	//SearchPaxYesNo在Member的組織底下
	$checkflag = 0;
	$Select = "SELECT mb_no, ".TRUE_INTRO_NO.", ".ORGSEQ_NO." FROM ".TB." WHERE mb_no='".$_POST['find_mb_no']."'";
	$Query = $db->query($Select);
	$arrResult = $Query->fetch();
	$c_mb_no = $arrResult['mb_no'];
	$c_ture_intro_no = $arrResult[TRUE_INTRO_NO];
	
	while($c_mb_no != $c_ture_intro_no){	
		if($_POST['mbst_mb_no'] == $c_ture_intro_no){
			$checkflag = 1;
			break;
		}else{
			$Select = "SELECT mb_no, ".TRUE_INTRO_NO." FROM ".TB." WHERE mb_no='".$c_ture_intro_no."'";
			$Query = $db->query($Select);
			$arrResult2 = $Query->fetch();
			$c_mb_no = $arrResult2['mb_no'];
			$c_ture_intro_no = $arrResult2[TRUE_INTRO_NO];
		}
	}
	//SearchPaxYesNo在Member的組織底下 END
	
	if($checkflag == 1){
		//對照Column 抓資料
		$str='';
		$fie_s="SELECT * from org_data where yn='Y' order by abs(sort),no";
		$fie_t=$db->query($fie_s);
		while($data1=$fie_t->fetch()){
			$str .= ','.$data1['enfield'];
		}
		
		$Select = "SELECT mb_no, ".TRUE_INTRO_NO.", ".ORGSEQ_NO." FROM ".TB." WHERE mb_no='".$_POST['mbst_mb_no']."'";
		$Query = $db->query($Select);
		$arrResult = $Query->fetch();
		
		$query3="SELECT ".ORGSEQ_NO.",".LEVEL_NO.",".TRUE_INTRO_NO." FROM ".TB." WHERE mb_no='".$_POST['find_mb_no']."'";
		if(TB=='his_moneypv2'){
			$query3.=" and yymm='".$_POST['his']."'";
		}
		$res3=$db->query($query3);
		$data3=$res3->fetch();
	
		$std=new stdClass();
		$std->data=array();
		$qres=array();
		
		//根據Language過濾顯示Ranking-------------------------------------------------------------------
		if((isset($_SESSION['lang']))&&($_SESSION['lang']!='ct')){		
			$query1="SELECT mb_no,mb_name".$str.",mb_status,pg_date,".TRUE_INTRO_NO.",".LEVEL_NO.",bgrade_class,translate.".$_SESSION['lang']." as grade_name,grade_class,line_kind,".LEVELLINEFLAG.",per_m,".ORGSEQ_NO." 
						 FROM ".TB." ,grade,translate 
						 WHERE ".TB.".bgrade_class=grade.no and grade.name=translate.ct and ".ORGSEQ_NO." LIKE '".$arrResult[ORGSEQ_NO]."%' and ".LEVEL_NO."<=".$data3[LEVEL_NO];
		}else{
			$query1="SELECT mb_no,mb_name".$str.",mb_status,pg_date,".TRUE_INTRO_NO.",".LEVEL_NO.",bgrade_class,grade.name AS grade_name,grade_class,line_kind,".LEVELLINEFLAG.",per_m,".ORGSEQ_NO." 
						 FROM ".TB." 
						 LEFT JOIN grade ON ".TB.".bgrade_class=grade.no 
						 WHERE ".ORGSEQ_NO." LIKE '".$arrResult[ORGSEQ_NO]."%' and ".LEVEL_NO."<=".$data3[LEVEL_NO];
		}
		//-------------------------------------------------------------------------------------------------	
		
		if(TB=='his_moneypv2'){
			$query1.=" and yymm='".$_POST['his']."'";
		}
		$query1.=" ORDER BY ".ORGSEQ_NO.",pg_date";
		//echo $query1.'---';
		$res1=$db->query($query1);
		
		while($data1=$res1->fetch()){
			$check_orgseq = substr($data3[ORGSEQ_NO], 0, (($data1[LEVEL_NO] + 1) * 2));
			
			$check_orgseq2 = substr($data3[ORGSEQ_NO], 0, (($data1[LEVEL_NO]) * 2));
			
			$checksql = "select mb_no from mbst where ".ORGSEQ_NO."='".$check_orgseq2."'";
			$checkquery = $db->query($checksql);
			$checkdate = $checkquery->fetch();
			
			if(($data1[ORGSEQ_NO] == $check_orgseq) || ($data3[TRUE_INTRO_NO] == $data1[TRUE_INTRO_NO]) || ($checkdate['mb_no'] == $data1[TRUE_INTRO_NO])){
				
				$query2="SELECT mb_no FROM ".TB." WHERE ".TRUE_INTRO_NO."='".$data1['mb_no']."'";
				if(TB=='his_moneypv2'){
					$query2.=" and yymm='".$_POST['his']."'";
				}
				$res2=$db->query($query2);
				$qres=$data1;
				//echo $data1['mb_no'].'--';
				
				// Has downline
				if($res2->size()>0){
					if($data3[LEVEL_NO] == $data1[LEVEL_NO]){
						if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
							$qres['line_label']='5';
							$qres['parent_label']='0';
						}else{
							$qres['line_label']='4';
							$qres['parent_label']='1';
						}
					}else{
						if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
							if($data1[ORGSEQ_NO] == $check_orgseq){
								$qres['line_label']='7';
								$qres['parent_label']='0';
							}else{
								$qres['line_label']='5';
								$qres['parent_label']='0';
							}
						}else{
							if(($data1[ORGSEQ_NO] == $check_orgseq) && ($data1['mb_no'] != $_POST['mbst_mb_no'])){
								$qres['line_label']='6';
								$qres['parent_label']='1';
							}elseif($data1['mb_no'] == $_POST['mbst_mb_no']){
								$qres['line_label']='7';
								$qres['parent_label']='0';
							}else{
								$qres['line_label']='4';
								$qres['parent_label']='1';
							}
						}
					}
				}else{
					if(strlen(trim($qres[LEVELLINEFLAG]))>0){ // As Final 
						$qres['line_label']='3';
						$qres['parent_label']='0';
					}else{
						$qres['line_label']='2';
						$qres['parent_label']='1';
					}
				}
				$std->index->$data1[TRUE_INTRO_NO]=0;
				array_push($std->data,$qres);
			}
		}
		//$std->true_intro_no=$_POST['find_mb_no'];
		$std->true_intro_no=$_POST['mbst_mb_no'];
	
		$std->level=$_POST['level'];
		echo json_encode($std);
		
	}else{
		echo "NOFIND";
	}
}


if((isset($_POST['mb_no']))&&($_POST['orgseq']==6)){ //Upright sponsor chart\
	if($_POST['his']==-1){
		define("TB","mbst");
	}else{
		define("TB","his_moneypv2");
	}

	$query3="SELECT * FROM org_data WHERE org_kind = 'true_intro_no' and yn='Y' order by abs(sort),no";
	$res3=$db->query($query3);
	$data_ar=array();
	while($data3=$res3->fetch()){
		array_push($data_ar,$data3['enfield']);
	}
	$mb_no=$_POST['mb_no'];
	if(isset($_POST['prevP'])){
		if($_POST['prevP']=='h'){
			$query1="SELECT mb_no FROM ".TB." WHERE mb_no=true_intro_no";
			if(TB=='his_moneypv2'){
				$query1.=" and yymm='".$_POST['his']."'";
			}
			$res1=$db->query($query1);
			$data1=$res1->fetch();
			$mb_no=$data1['mb_no'];
			
		}else{
			//$l=4;
			//while($l>0){
				$query1="SELECT m.true_intro_no,(SELECT level_no1 FROM ".TB;
				$query1.=" WHERE mb_no=m.true_intro_no";
				if(TB=='his_moneypv2'){
					$query1.=" and yymm='".$_POST['his']."'";
				}
				$query1.=") AS level_no1 FROM ".TB." AS m WHERE m.mb_no='".$mb_no."'";
				if(TB=='his_moneypv2'){
					$query1.=" and m.yymm='".$_POST['his']."'";
				}

				$res1=$db->query($query1);
				$data1=$res1->fetch();
				$mb_no=$data1['true_intro_no'];
				
				//$l--;
			//}
		}
	}
	//20100816 BEAR 不可以看Tofrom己的上線!
    $chk_res=$db->query("SELECT m.mb_no FROM mbst AS m WHERE m.mb_no='".$mb_no."' and level_no1>=(SELECT level_no1 FROM mbst WHERE mb_no='".$_POST['true_mb_no']."')");
	if($chk_res->size()<1){
		$mb_no=$_POST['true_mb_no'];
	}
	$tmp_ar2=array();
	$tmp_data=new stdClass();
	$main_ar[0] = array();
	$main_ar[1] = array();
	$main_ar[2] = array();
	$main_ar[3] = array();
	$query1="SELECT mb_no,mb_name,true_intro_no,grade_class,bgrade_class,pg_date,level_no1,orgseq_no1,".join(",",$data_ar)." FROM ".TB." WHERE mb_no='".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$_POST['his']."'";
	}
	$res1=$db->query($query1);
	if($res1->size() != 1){
		echo "none";
	}else{
		$sRow=$res1->fetch();
		$topseq=$sRow['orgseq_no1'];
		$lv0=$sRow['level_no1'];
		$lv1=$sRow['level_no1']+1;
		$lv2=$sRow['level_no1']+2;
		$lv3=$sRow['level_no1']+3;
		if($topseq!=""){
		
		
			
			$qthr_s="select mb_no,mb_name,true_intro_no,".$grade_class." grade_class,bgrade_class,pg_date,level_no1,orgseq_no1,".join(",",$data_ar)." from ".TB." where level_no1=".$lv3." and orgseq_no1 like '".$topseq."%'  ";
			if(TB=='his_moneypv2'){
				$qthr_s.=" and yymm='".$_POST['his']."'";
			}
			$qthr_s.=" order by orgseq_no1";
			$thrR=$db->query($qthr_s);
			
			$leftone_seq=0;
			$lefttwo_seq=0;
			if($thrR->size()>0){
				//$thrStr='<tr>';
				while($thrD=$thrR->fetch()){
					$count_td = 0;
					$count_td_r = 0;
					$count_td_col = 0;
					$num=0;
					$rightone_seq=substr($thrD['orgseq_no1'],0,-6);
					$upOne_s="select mb_no from ".TB." where level_no1=".$lv1." and orgseq_no1 like '".$topseq."%' and orgseq_no1>'".$leftone_seq."' and orgseq_no1<'".$rightone_seq."'";
					if(TB=='his_moneypv2'){
						$upOne_s.=" and yymm='".$_POST['his']."'";
					}
					$upOne=$db->query($upOne_s);
					while($upOneD=$upOne->fetch()){
						$notNum_s="select mb_no from ".TB." where true_intro_no='".$upOneD['mb_no']."'";
						if(TB=='his_moneypv2'){
							$notNum_s.=" and yymm='".$_POST['his']."'";
						}
						$notNum=$db->query($notNum_s);
						if($notNum->size()<1){
							$num++;
						}
					}
					
					for($i=0;$i<$num;$i++){
						//$thrStr.='<td></td>';
						$count_td = $count_td + 1;
					}
				
					$righttwo_seq=substr($thrD['orgseq_no1'],0,-3);
					$upTwo_s="select mb_no from ".TB." where level_no1=".$lv2." and orgseq_no1 like '".$topseq."%' and  orgseq_no1>'".$lefttwo_seq."' and orgseq_no1<'".$righttwo_seq."'";
					if(TB=='his_moneypv2'){
						$upTwo_s.=" and yymm='".$_POST['his']."'";
					}
					
					$upTwo=$db->query($upTwo_s);
					$num=$upTwo->size();
					for($i=0;$i<$num;$i++){
						//$thrStr.='<td></td>';
						$count_td = $count_td + 1;
					}
					
					//$thrStr.='<td>'.$thrD['mb_no'].'</td>';
					$thrD['down_line']=chk_down_line3($thrD['mb_no'],$_POST['his']);
					$thrD['down_count']=chk_down_count3($thrD['mb_no'],$_POST['his']);
					$thrD['count_mb'] = $count_td;
					$thrD['count_mb_r'] = $count_td_r;
					$thrD['count_mb_col'] = $count_td_col;
					$thrD['pg_date'] = date('Y-m-d',$thrD['pg_date']);
					array_push($main_ar[0],$thrD);
					
					$leftone_seq=$rightone_seq;
					$lefttwo_seq=$righttwo_seq;
				}
				
				$count_td_r = 0;
				$total_num=0;
				$rightNum1_s="select mb_no from ".TB." where level_no1=".$lv1." and orgseq_no1 like '".$topseq."%' and  orgseq_no1>'".$rightone_seq."'";
				if(TB=='his_moneypv2'){
					$rightNum1_s.=" and yymm='".$_POST['his']."'";
				}
				$rightNum1=$db->query($rightNum1_s);
				while($rigD=$rightNum1->fetch()){
					$unNum_s="select mb_no from ".TB." where true_intro_no='".$rigD['mb_no']."'";
					if(TB=='his_moneypv2'){
						$unNum_s.=" and yymm='".$_POST['his']."'";
					}	
					$unNum=$db->query($unNum_s);
					if($unNum->size()>0){
						$total_num+=$unNum->size();
					}else{
						$total_num++;
					}
				}
				$rightNum2_s="select mb_no from ".TB." where level_no1=".$lv2." and orgseq_no1 like '".$rightone_seq."%' and  orgseq_no1>'".$righttwo_seq."'";
				if(TB=='his_moneypv2'){
					$rightNum2_s.=" and yymm='".$_POST['his']."'";
				}
				$rightNum2=$db->query($rightNum2_s);	
				$total_num+=$rightNum2->size();			
				if($total_num>0){
					for($i=0;$i<$total_num;$i++){
						//$thrStr.='<td></td>';
						$count_td_r = $count_td_r + 1;
					}
					$arrCount = count($main_ar[0]);
					$main_ar[0][$arrCount-1]['count_mb_r'] = $count_td_r;
				}
				//$thrStr.='</tr>';
			}else{
				$thrStr='';
			}
			
			//
			$twoR_s="select mb_no,mb_name,grade_class,bgrade_class,pg_date,orgseq_no1,".join(",",$data_ar)." from ".TB." where level_no1=".$lv2." and orgseq_no1 like '".$topseq."%' ";
			
			if(TB=='his_moneypv2'){
				$twoR_s.=" and yymm='".$_POST['his']."'";
			}
			$twoR_s.= " order by orgseq_no1";
			$twoR=$db->query($twoR_s);
			$leftone_seq=0;
			//$twoStr='<tr>';
			while($twoD=$twoR->fetch()){
				$count_td = 0;
				$count_td_r = 0;
				$count_td_col = 0;
				$rightone_seq=substr($twoD['orgseq_no1'],0,-3);
				$upOne_s="select mb_no from ".TB." where level_no1=".$lv1." and orgseq_no1 like '".$topseq."%' and  orgseq_no1>'".$leftone_seq."' and orgseq_no1<'".$rightone_seq."'";
				if(TB=='his_moneypv2'){
					$upOne_s.=" and yymm='".$_POST['his']."'";
				}
				$upOne=$db->query($upOne_s);
				$num=$upOne->size();
				for($i=0;$i<$num;$i++){
					//$twoStr.='<td></td>';
					$count_td = $count_td + 1;
				}
				$cols_s="select mb_no from ".TB." where true_intro_no='".$twoD['mb_no']."'";
				if(TB=='his_moneypv2'){
					$cols_s.=" and yymm='".$_POST['his']."'";
				}
				$cols=$db->query($cols_s);
				if($cols->size()>0){
					$num=$cols->size();
				}else{
					$num=1;
				}
				$a[$twoD['mb_no']]=$num;
				//$twoStr.="<td colspan='".$num."' align='center'>".$twoD['mb_no']."</td>";
				$count_td_col = $num;
				$twoD['down_line']=chk_down_line3($twoD['mb_no'],$_POST['his']);
				$twoD['down_count']=chk_down_count3($twoD['mb_no'],$_POST['his']);
				$twoD['count_mb'] = $count_td;
				$twoD['count_mb_r'] = $count_td_r;
				$twoD['count_mb_col'] = $count_td_col;
				$twoD['pg_date'] = date('Y-m-d',$twoD['pg_date']);
				array_push($main_ar[1],$twoD);
				
				$leftone_seq=$rightone_seq;
			}
			$count_td_r = 0;
			$total_num=0;
			$rightNum_s="select mb_no from ".TB." where level_no1=".$lv1." and orgseq_no1 like '".$topseq."%' and  orgseq_no1>'".$rightone_seq."'";
			if(TB=='his_moneypv2'){
				$rightNum_s.=" and yymm='".$_POST['his']."'";
			}
			
			$rightNum=$db->query($rightNum_s);
			
			$total_num=$rightNum->size();
			if($total_num>0){
				for($i=0;$i<$total_num;$i++){
					//$twoStr.='<td></td>';
					$count_td_r = $count_td_r + 1;
				}
				//$twoStr.="<td colspan='".$total_num."' align='center'></td>";
				$arrCount = count($main_ar[1]);
				$main_ar[1][$arrCount-1]['count_mb_r'] = $count_td_r;
			}
			//$twoStr.='</tr>';
			
			$oneR_s="select mb_no,mb_name,grade_class,bgrade_class,pg_date,orgseq_no1,".join(",",$data_ar)." from ".TB." where level_no1=".$lv1." and orgseq_no1 like '".$topseq."%'  ";
			if(TB=='his_moneypv2'){
				$oneR_s.=" and yymm='".$_POST['his']."'";
			}
			$oneR_s.=" order by orgseq_no1 ";
			$oneR=$db->query($oneR_s);
			//$oneStr='<tr>';
			while($oneD=$oneR->fetch()){
				$count_td = 0;
				$count_td_r = 0;
				$count_td_col = 0;
				$colnum=0;
				$cols_s="select mb_no from ".TB." where true_intro_no='".$oneD['mb_no']."'";
				if(TB=='his_moneypv2'){
					$cols_s.=" and yymm='".$_POST['his']."'";
				}
				$cols=$db->query($cols_s);
				if($cols->size()>0){
					while($colD=$cols->fetch()){
						$colnum+=$a[$colD['mb_no']];
					}
				}else{
					$colnum=1;
				}
				$a[$oneD['mb_no']]=$colnum;
				//echo $oneD['mb_no'].'-'.$a[$oneD['mb_no']];
				//$oneStr.="<td colspan='".$colnum."' align='center'>".$oneD['mb_no']."</td>";
				$count_td_col = $colnum;
				$oneD['down_line']=chk_down_line3($oneD['mb_no'],$_POST['his']);
				$oneD['down_count']=chk_down_count3($oneD['mb_no'],$_POST['his']);
				$oneD['count_mb'] = $count_td;
				$oneD['count_mb_r'] = $count_td_r;
				$oneD['count_mb_col'] = $count_td_col;
				$oneD['pg_date'] = date('Y-m-d',$oneD['pg_date']);
				array_push($main_ar[2],$oneD);
			}
			//$oneStr.='</tr>';
			
			//
			$count_td = 0;
			$count_td_r = 0;
			$count_td_col = 0;
			$colnum=0;
			//$cols=$db->query("select mb_no from mbst where true_intro_no='".$_POST['mb_no']."'");
			$cols_s="select mb_no from ".TB." where true_intro_no='".$mb_no."'";
			if(TB=='his_moneypv2'){
				$cols_s.=" and yymm='".$_POST['his']."'";
			}
			
			$cols=$db->query($cols_s);
			while($colD=$cols->fetch()){
				$colnum+=$a[$colD['mb_no']];
			}
		//$zeroStr="<tr><td colspan='".$colnum."' align='center'>".$_POST['mb_no']."</td></tr>";
			$count_td_col = $colnum;
		}
		$sRow['down_line']=chk_down_line3($mb_no,$_POST['his']);
		$sRow['down_count']=chk_down_count3($mb_no,$_POST['his']);
		$sRow['count_mb'] = $count_td;
		$sRow['count_mb_r'] = $count_td_r;
		$sRow['count_mb_col'] = $count_td_col;
		$sRow['pg_date'] = date('Y-m-d',$sRow['pg_date']);
		array_push($main_ar[3],$sRow);
		
		//echo "<table border=1px>".$zeroStr.$oneStr.$twoStr.$thrStr."</table>";
		echo json_encode($main_ar);
	}
}



if((isset($_POST['mb_no']))&&($_POST['orgseq']==99)){  //Upright sponsor chart Number of generationthree數
	if($_POST['his']==-1){
		define("TB","mbst");
	}else{
		define("TB","his_moneypv2");
	}
	$query3="SELECT * FROM org_data WHERE org_kind = '".ORGSEQ_NO."' and yn='Y' and for_web=1";
	$res3=$db->query($query3);
	$data_ar=array();
	while($data3=$res3->fetch()){
		array_push($data_ar,$data3['enfield']);
	}
	$mb_no=$_POST['mb_no'];
	if(isset($_POST['prevP'])){
		if($_POST['prevP']=='h'){
			$query1="SELECT mb_no FROM ".TB." WHERE mb_no=true_intro_no";
			if(TB=='his_moneypv2'){
				$query1.=" and yymm='".$_POST['his']."'";
			}
			$res1=$db->query($query1);
			$data1=$res1->fetch();
			$mb_no=$data1['mb_no'];
			
		}else{
			//$l=4;
			//while($l>0){
				$query1="SELECT m.true_intro_no,(SELECT level_no1 FROM ".TB;
				$query1.=" WHERE mb_no=m.true_intro_no";
				if(TB=='his_moneypv2'){
					$query1.=" and yymm='".$_POST['his']."'";
				}
				$query1.=") AS level_no1 FROM ".TB." AS m WHERE m.mb_no='".$mb_no."'";
				if(TB=='his_moneypv2'){
					$query1.=" and m.yymm='".$_POST['his']."'";
				}

				$res1=$db->query($query1);
				$data1=$res1->fetch();
				$mb_no=$data1['true_intro_no'];
				
				//$l--;
			//}
		}
	}
	//20100816 BEAR 不可以看Tofrom己的上線!
	$chk_res=$db->query("SELECT m.mb_no FROM mbst AS m WHERE m.mb_no='".$mb_no."' and level_no1>=(SELECT level_no1 FROM mbst WHERE mb_no='".$_POST['true_mb_no']."')");
	if($chk_res->size()<1){
		$mb_no=$_POST['true_mb_no'];
	}
	//20100816 BEAR 不可以看Tofrom己的上線!
    // $chk_res=$db->query("SELECT m.mb_no FROM mbst AS m WHERE m.mb_no='".$mb_no."' and level_no1>=(SELECT level_no1 FROM mbst WHERE mb_no='".$_SESSION['mb']['mb_no']."')");
	// if($chk_res->size()<1){
		// $mb_no=$_SESSION['mb']['mb_no'];
	// }
	
	$level_num=4;
	
	$query1="SELECT mb_no,grade_class,bgrade_class,level_no1,true_intro_no,orgseq_no1,pg_date,per_m,a_line_subs,b_line_subs,org_m,mb_name,g7_sorg_m,yymm,to_up_per_bv,org_bv_sum,per_bv2_sum FROM ".TB." WHERE mb_no='".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$_POST['his']."'";
	}
	// echo $query1;
	$res1=$db->query($query1);
	if($res1->size() != 1){
		echo "none";
	}else{
		$sRow=$res1->fetch();

		/*切代 ---angel */
		$str_temp='';
		$orgseq_no1_res=$db->query("select orgseq_no1,mb_no from mbst where orgseq_no1 like '".$sRow['orgseq_no1']."%' and grade_class>=3 and mb_no!='".$mb_no."'");

		while($orgseq_no1_data=$orgseq_no1_res->fetch()){
			$str_temp.=" and (orgseq_no1 not like '".$orgseq_no1_data['orgseq_no1']."%' or orgseq_no1='".$orgseq_no1_data['orgseq_no1']."')";
		}
		/*切代 ----end */

		$grade_res=$db->query("select name from grade where no='".$sRow['grade_class']."'");
		$grade_data=$grade_res->fetch();
		
		if($sRow['bgrade_class']<8){
			$grade_data['name']="無";
		}
		
		$sRow['grade_name']=$grade_data['name'];
		$sRow['mb_name2']=substr($sRow['mb_name'],0,10);
		$topseq=$sRow[ORGSEQ_NO];
		
		//20150304JOE
		$arr_num=0;
		$main_ar[$arr_num] = array();
		if($topseq!=""){
			$lv_query="select (".LEVEL_NO."-".$sRow[LEVEL_NO].") as max_level from ".TB." where ".ORGSEQ_NO." like '".$topseq."%'";
			if(TB=='his_moneypv2'){
				$lv_query.=" and yymm='".$_POST['his']."'";
			}
			$lv_query.=" order by ".LEVEL_NO." desc limit 1";
			$level_res=$db->query($lv_query);
			$level_data=$level_res->fetch();
			$show_max_level=$level_data['max_level'];
			if($level_num>$show_max_level){
				$level_num=$show_max_level;
			}
			$arr_num=0;
			
			for($show_level=$level_num;$show_level>0;$show_level--){
				$now_lv=$sRow[LEVEL_NO]+$show_level;
				$main_ar[$arr_num] = array();
				for($up_i=1;$up_i<($level_num-$arr_num);$up_i++){
					$left_up_seq[$up_i]=0;
				}
				$org_query="select mb_no,grade_class,bgrade_class,level_no1,true_intro_no,orgseq_no1,pg_date,per_m,a_line_subs,b_line_subs,org_m,mb_name,g7_sorg_m,yymm,to_up_per_bv,org_bv_sum,per_bv2_sum from ".TB." where ".LEVEL_NO."=".$now_lv." and ".ORGSEQ_NO." like '".$topseq."%'";
				if(TB=='his_moneypv2'){
					$org_query.=" and yymm='".$_POST['his']."'";
				}

				/*切代 angel*/
				$org_query.=$str_temp;
				/*end*/
				$org_query.=" order by ".ORGSEQ_NO;
				$thrR=$db->query($org_query);			
				while($thrD=$thrR->fetch()){
					$left_null = 0;
					$right_null = 0;
					for($up_j=1;$up_j<($level_num-$arr_num);$up_j++){
						$right_up_seq[$up_j]=substr($thrD[ORGSEQ_NO],0,(0-$up_j*SEQ_NUM));
						$subQuery="select mb_no from ".TB." where ".LEVEL_NO."=".($now_lv-$up_j)." and ".ORGSEQ_NO." like '".$topseq."%' and ".ORGSEQ_NO.">'".$left_up_seq[$up_j]."' and ".ORGSEQ_NO."<'".$right_up_seq[$up_j]."'";
						if(TB=='his_moneypv2'){
							$subQuery.=" and yymm='".$_POST['his']."'";
						}
						$subQuery.=" order by ".ORGSEQ_NO;
						$upOne=$db->query($subQuery);
						if($up_j==1){
							$left_null=$upOne->size();
						}else{
							while($upOneD=$upOne->fetch()){
								$subQuery="select mb_no from ".TB." where ".TRUE_INTRO_NO."='".$upOneD['mb_no']."'";
								if(TB=='his_moneypv2'){
									$subQuery.=" and yymm='".$_POST['his']."'";
								}
								$notNum=$db->query($subQuery);
								if($notNum->size()<1){
									$left_null++;
								}
							}
						}
						$left_up_seq[$up_j]=$right_up_seq[$up_j];
					}
					
					if($show_level==$level_num){
						$mb_col[$thrD['mb_no']]=1;
					}else{
						$colnum=0;
						$subQuery="select mb_no from ".TB." where ".TRUE_INTRO_NO."='".$thrD['mb_no']."'";
						if(TB=='his_moneypv2'){
							$subQuery.=" and yymm='".$_POST['his']."'";
						}
						$subQuery.=$str_temp; //angel
						$cols=$db->query($subQuery);
						if($cols->size()>0){
							while($colD=$cols->fetch()){
								$colnum+=$mb_col[$colD['mb_no']];
							}
						}else{
							$colnum=1;
						}
						$mb_col[$thrD['mb_no']]=$colnum;
					}
					$grade_res=$db->query("select name from grade where no='".$thrD['grade_class']."'");
					$grade_data=$grade_res->fetch();
					$thrD['down_line']=chk_down_line3($thrD['mb_no'],$_POST['his']);
					$thrD['down_intro']=chk_down_intro3($thrD['mb_no'],$_POST['his']);
					$thrD['down_count']=chk_down_count3($thrD['mb_no'],$_POST['his']);  //我Yes上線第幾個Sponsor的  //0:None  1:唯一個的1個  2:第1個  3:最Back一個  4:Others
					$thrD['count_mb'] = $left_null;
					$thrD['count_mb_r'] = $right_null;
					$thrD['count_mb_col'] = $mb_col[$thrD['mb_no']];
					$thrD['pg_date'] = date('Y-m-d',$thrD['pg_date']);
					$thrD['per_m'] = $thrD['per_m'];
					$thrD['a_line_subs'] = $thrD['a_line_subs'];
					$thrD['b_line_subs'] = $thrD['b_line_subs'];
					$thrD['org_m'] = $thrD['org_m'];
					//$thrD['mb_name'] = $thrD['mb_name'];
					$thrD['mb_name2']=substr($thrD['mb_name'],0,10);
					$thrD['grade_name'] = $grade_data['name'];
					$grade_res=$db->query("select name from grade where no='".$thrD['bgrade_class']."'");
					$grade_data=$grade_res->fetch();
					
					
					$thrD['bgrade_class'] = $grade_data['name'];
					if($thrD['bgrade_class']<8){
						$grade_data['name']="無";
					}
					array_push($main_ar[$arr_num],$thrD);
				}
				$arr_num++;
			}
		}
		$main_ar[$arr_num] = array();
		$colnum=0;
		$subQuery="select mb_no from ".TB." where ".TRUE_INTRO_NO."='".$mb_no."'";
		if(TB=='his_moneypv2'){
			$subQuery.=" and yymm='".$_POST['his']."'";
		}
		$cols=$db->query($subQuery);
		while($colD=$cols->fetch()){
			$colnum+=$mb_col[$colD['mb_no']];
		}
		$sRow['down_line']=chk_down_line3($mb_no,$_POST['his']);
		$sRow['down_intro']=chk_down_intro3($mb_no,$_POST['his']);
		// $sRow['down_count']=chk_down_count3($mb_no,$_POST['his']);//我Yes上線第幾個Sponsor的  //0:None  1:唯一個的1個  2:第1個  3:最Back一個  4:Others
		$sRow['down_count']=0;
		$sRow['count_mb'] = 0;
		$sRow['count_mb_col'] = $colnum;
		$sRow['count_mb_r'] = 0;
		$sRow['pg_date'] = date('Y-m-d',$sRow['pg_date']);
		
		array_push($main_ar[$arr_num],$sRow);
		echo json_encode($main_ar);
	}
}

function chk_down_line($mb_no,$his){
	GLOBAL $db;
	$query1="SELECT mb_no FROM ".TB." WHERE intro_no='".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$his."'";
	}
	$res1=$db->query($query1);
	if($res1->size()>0){
		return 1;
	}else{
		return 0;
	}
}
function chk_down_line3($mb_no,$his){
	GLOBAL $db,$str_temp;
	$query1="SELECT mb_no FROM ".TB." WHERE true_intro_no='".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$his."'";
	}
	
	$res1=$db->query($query1);
	if($res1->size()>0){
		$query1="SELECT mb_no FROM ".TB." WHERE true_intro_no='".$mb_no."'";
		if(TB=='his_moneypv2'){
			$query1.=" and yymm='".$his."'";
		}
		$query1.=$str_temp;
		$res1=$db->query($query1);
		if($res1->size()>0){
			return 1;
		}else{
			return 99;
		}
	}else{
		return 0;
	}
}
function chk_down_intro3($mb_no,$his){
	GLOBAL $db;
	$query1="SELECT mb_no, ".TRUE_INTRO_NO.", ".ORGSEQ_NO.", ".LEVELLINEFLAG." FROM ".TB." WHERE ".TRUE_INTRO_NO." = '".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$his."'";
	}
	$res1=$db->query($query1);
	return $res1->size();
}
function chk_down_count3($mb_no,$his){
	GLOBAL $db;
	$query1="SELECT mb_no, true_intro_no, orgseq_no1, levellineflag1 FROM ".TB." WHERE mb_no = '".$mb_no."'";
	if(TB=='his_moneypv2'){
		$query1.=" and yymm='".$his."'";
	}
	$res1=$db->query($query1);
	$data1=$res1->fetch();
	
	$query2="SELECT mb_no FROM ".TB." WHERE true_intro_no='".$data1['true_intro_no']."' and mb_no <> true_intro_no ";
	if(TB=='his_moneypv2'){
		$query2.=" and yymm='".$his."'";
	}
	$query2.="order by orgseq_no1";
	$res2=$db->query($query2);
	$chk_size = $res2->size();
	if($chk_size == 1){
		return 1;
	}elseif($chk_size > 1){
		if((intval(substr($data1['orgseq_no1'],-4,4)) == 1) && (strlen(trim($data1['levellineflag1'])) == 0)){
			return 2;
		}elseif((intval(substr($data1['orgseq_no1'],-4,4)) != 1) && (strlen(trim($data1['levellineflag1'])) > 0)){
			return 3;
		}else{
			return 4;
		}
	}else{
		return 0;
	}
}

function chgStatus($id){
	switch($id){
		case '1':
			return 'Official';
			break;
		case '2':
			return 'Suspended';
			break;
		case '3':
			return 'Termination';
			break;
	}
}
function chgKind($id){
	switch($id){
		case 1:
			return 'Consumer Member';
			return;
		case 2:
			return 'Distributor';
			break;
	}
}
function showmb_no($mb_no,$i){
		global $db;
		$now_mb_no=$mb_no;
		$q1_s="select mb_no,true_intro_no from mbst where mb_no ='".$now_mb_no  ."'";
		$q1_t=$db->query($q1_s);
		$q1=$q1_t->fetch();
		$now_intro_no=$q1['true_intro_no'];
		while($i>=1){
			$q1_s="select mb_no,true_intro_no from mbst where mb_no ='".$now_intro_no  ."'";
			$q1_t=$db->query($q1_s);
			$q1=$q1_t->fetch();
			$now_intro_no=$q1['true_intro_no'];
			$now_mb_no=$q1['mb_no'];
			$i--;
		}
		return $now_mb_no;
}
?>