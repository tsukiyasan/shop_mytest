<?php
	/*
	$grade_query="SELECT * FROM org_data as a , org_data_demo as b where a.yn ='Y'  and a.enfield = b.enfield and a.org_kind= b.org_kind and a.org_kind= 'true_intro_no' order by b.sort  ";
	$grade_res=$db->query($grade_query);
	$i=0;
	$str='';
	while($row=$grade_res->fetch()){
		if($i!=0){
			$str.=",";
		}
		$index[$row['no']]=$i;
		$str.=json_encode($row);
		$i++;
	}
	$gdata='{"index":'.json_encode($index).',"data":['.$str.']}';
	*/
	$scQ="select have_intro_no,use_e_cash from comp_data";
	$scR=$db->query($scQ);
	if($scR->size()>0){
		$scRow=$scR->fetch();
		//$have_intro預設Yes1 ，yesPlacement ； 0YesNoPlacement
		$have_intro=$scRow['have_intro_no'];
	}
	
	$mb_query="select mb_no from mbst order by mb_no limit 1";
	$mb_res=$db->query($mb_query);
	$mb_data=$mb_res->fetch();
	$mb_no1=$mb_data['mb_no'];
	
	$year_query="select distinct SUBSTR(yymm,1,4) yy from his_moneypv2 order by yy desc";
	$year_res=$db->query($year_query);
	$ji=0;
	$index=array();
	$ydata='';
	while($yearRow=$year_res->fetch()){
		if($ji>0){
			$ydata.=',';
		}
		$index[$yearRow['yy']]=$ji;
		$ydata.=json_encode($yearRow);
		$ji++;
	}
	$yy='{"index":'.json_encode($index).',"ydata":['.$ydata.']}';
?>
<style type='text/css'>
	@import "module/orgseq/CSS/orgseq.css";
</style>
<div id='BLOCK_orgseq_info'>
	<div class='filter_div'>
		

		<?php
			$query1="SELECT no,en_name name,img FROM grade order by abs(no)";
			$res1=$db->query($query1);
			while($data1=$res1->fetch()){
				echo "<img src='module/orgseq/grade/".$data1['img']."'>".$data1['name']."　";
			}
		?>
	</div>
	
	
	<div class='org_kind_div'>
	
		Expand generation<select id='level_limit_info' class='limit'>
			<option value=1>1</option>
			<option value=2>2</option>
			<option value=3 selected>3</option>
			<option value=4>4</option>
			<option value=5>5</option>
			<option value=6>6</option>
			<option value=7>7</option>
			<option value=8>8</option>
			<option value=9>9</option>
			<option value=10>10</option>
		</select>
		
		
		
		<input type='text' id='f_mb_no' onblur='UpperStr(this)'  size='9' height="23">
		<select name='f_org_kind' id='f_org_kind'>
			<option value=0>Sponsor chart</option>
			<?php if($have_intro==1){?>
			<option value=1>Placement chart</option>
			<?php }?>
		</select>
		<select name='f_his_info' id='f_his_info'>
			<option value=-1>Real Time</option>
		</select>
		<input type='hidden' id='changed'>
		<input type='button' value='Enquiry' id='f_chg_btn'>
		<!--<input type='button' value='export network chart' id='exp_orgseq_btn' onclick=exp_orgseq2();>-->
		<!--<input type='button' value='PDFexport' id='print_in_org' onclick=print_orgseq();>-->
		<input type='button' value='Excelexport' id='excel_in_org' onclick=print_excel_info();>
		<!--ex:<?php //echo $mb_no1;?>&nbsp;&nbsp;&nbsp;-->
		&nbsp;&nbsp;
		<select id='year'></select>
		<select id='chart_kind'>
			<option value='org_m'>Annual network sales Performance</option>
		</select>
		<img src="./images/org03.gif" title='Show graphs' onclick="showChart()" />
		<img src="./images/org01.gif" title='Show bonuses' onclick="showAward()" />
		<img src="./images/org04.gif" title='Personal Information' onclick="memo()" />
		<input type='text' id='find_mb_no' size='8'>
		<img src="./images/org02.gif" title='Search'  onclick="chk_country4()" />
		<input type='hidden' id='rem_mb_no'>
	</div>	
	<div id='BLOCK_orgseq_body2' class='org_tree_div2'></div>
	<div id='r_m002' class="r_m002">
	<!--<span class="r_m001">-->
		<span class="display_mb" id="display_mb"></span><br>
		<table id='orgseq_list_table' width='550px'></table>
	<!--</span>-->
	</div>
</div>

<script type='text/javascript'>
var true_intro_no='true_intro_no';
var level_no='level_no1';
var flag=false;
loader=new Image();
loader.src="images/org_loader.gif";
loader.title="Reading...";
/* export network chart */
function exp_orgseq2(){
	if(_id('f_mb_no').value.length>0){	
		location.href='module/orgseq/orgData.php?org='+_id('f_org_kind').value+'&mb_no='+_id('f_mb_no').value.trim()+'&his='+_id('f_his_info').value;		
	}
}
//Setting路徑
var node=new Image();
node.src="module/orgseq/tree/tree_node.gif";
node.title="node";
var lastnode=new Image();
lastnode.src="module/orgseq/tree/tree_lastnode.gif";
lastnode.title="lastnode";
var pnode=new Image();
pnode.src="module/orgseq/tree/tree_pnode.gif";
pnode.title="pnode";
var lastpnode=new Image();
lastpnode.src="module/orgseq/tree/tree_lastpnode.gif";
lastpnode.title="lastpnode";
var snode=new Image();
snode.src="module/orgseq/tree/tree_snode.gif";
snode.title="snode";
var lastsnode=new Image();
lastsnode.src="module/orgseq/tree/tree_lastsnode.gif";
lastsnode.title="lastsnode";
var vline=new Image();
vline.src="module/orgseq/tree/tree_vline.gif";
vline.title="vline";
var spacer=new Image();
spacer.src="module/orgseq/tree/tree_spacer.gif";
spacer.title="spacer";
function query_unit_res2(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				_id("f_mb_no").value='';
				alert("Member ID not found!");
			}else{
				unit_data=request.responseText.parseJSON();
				w=1;
				if(unit_data.length>0){
					leng=_id('f_his_info').options.length-1;
					while(leng>0){
						_id('f_his_info').remove(leng);
						leng--;
					}
					leng=unit_data.length;
					while(w<=leng){
						_id('f_his_info').options[w]=new Option(unit_data[w-1],unit_data[w-1]);
						w++;
					}
				}
			}
		}
	}
}

function query_unit2(){
	var mb_no=_id("f_mb_no").value.trim();
	if(mb_no.length>0 && mb_no!=_id('f_mb_no').defaultValue){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='query_unit=1&mb_no='+mb_no;
		request.onreadystatechange=query_unit_res2;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

var orgData;
var tmp_level1;
function headsearchRes2(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				orgData=request.responseText.parseJSON();
				if((orgData.data.length==1)&&(orgData.data[0].line_label.length==1)){				
					obj=_id('BLOCK_orgseq_body2');
					//移除first ul下的所yes節點
					while(obj.childNodes.length>0){
						obj.removeChild(obj.lastChild);
					}
					dv=document.createElement("div");
					dv.id='org0info|'+orgData.data[0].mb_no;
					dv.title=eval("orgData.data[0]."+level_no);
					obj.appendChild(dv);
					dv_info=new Array();
					dv_info.push('<span class=line_flag>'+chgNumToImg2(orgData.data[0].line_label)+chgGradeImg2(orgData.data[0].grade_class)+'</span>');
					dv_info.push('<span>0 generation</span>');
					tmp_level1=eval("orgData.data[0]."+level_no);
					if(_id('f_org_kind').value==1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+orgData.data[0].line_kind+'.gif></span>');
					}
					dv_info.push("<span id='show1_"+(orgData.data[0].mb_no)+"'>");
					dv_info.push("<span id=id onclick=choiceMB('"+(orgData.data[0].mb_no)+"')>"+orgData.data[0].mb_no+"</span>");
					dv_info.push("<span class=name onclick=choiceMB('"+(orgData.data[0].mb_no)+"')>"+orgData.data[0].mb_name+"</span>");
					//dv_info.push("<span id=id onclick=memo('"+(orgData.data[0].mb_no)+"')>"+orgData.data[0].mb_no+"</span>");
					//dv_info.push("<span class='hastooltip' className='hastooltip' id='show_msg' onclick=show_info_mb('"+(orgData.data[0].mb_no)+"',1)>"+orgData.data[0].mb_name+"</span>");
					dv_info.push("</span>");
					
					
					/*dv_info.push('<span class=per_m>'+orgData.data[0].per_m+'</span>');
					dv_info.push('<span class=grade>'+orgData.data[0].grade_name+'</span>');
					dv_info.push('<span class=pg_date>Date Join：'+orgData.data[0].pg_date+'</span>');*/
					
					/* HistoryNetwork chart 
					w=1;
					if(orgData.his.length>0){
						leng=_id('f_his_info').options.length-1;
						while(leng>0){
							_id('f_his_info').remove(leng);
							leng--;
						}
						leng=orgData.his.length;
						while(w<=leng){
							_id('f_his_info').options[w]=new Option(orgData.his[w-1],orgData.his[w-1]);
							w++;
						}
					}
					*/
					
					//改成根據Column 抓資料
					/*
					if(_id('f_his_info').value==-1){
						var tmpObj2='<?php echo $gdata?>';
						
						give_tb2=tmpObj2.parseJSON();
						
						gcount2=give_tb2.data.length;
						c2=0;
						
						while(c2<gcount2){
							fie=give_tb2.data[c2].enfield;
							//alert(fie) ;
							//dv_info.push("<span class='"+fie+"' style='color:"+give_tb2.data[c2].color+"'>"+give_tb2.data[c2].chfield+':'+eval("res.data[i]."+fie)+'</span>');
							dv_info.push("<span class='"+fie+"' style='color:"+give_tb2.data[c2].color+"'>"+give_tb2.data[c2].chfield+':'+eval("orgData.data[0]."+fie)+'</span>');
							c2++;
						}
					}*/
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
					
				}
			}
		}
	}
}
function print_excel_info(){
	if(confirm('confirm export this member network?')==true){	
		if(_id('rem_mb_no').value.length > 0){
			var post_str='';
			post_str+='print_orgseq=1';
			post_str+="&mb_no="+_id('rem_mb_no').value+"&his="+_id('f_his_info').value+"&org_kind="+_id('f_org_kind').value+"&from_where=info&year="+_id('year').value;
			createRequest();
			var url="module/orgseq/mbst_data.php";
			request.onreadystatechange=print_excel_info_res;
			request.open("POST",url,true);
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(post_str);		
			msg_block.show_info("PDF File is processing...Please wait!!");
		}else{
			msg_block.show_err("Please click on the member you want to view!");
		}
	}	
}
	
function print_excel_info_res(){
	if(request.readyState==4){
		if(request.status==200){	
			if((request.responseText.indexOf('ERRNO') >= 0)||(request.responseText.indexOf('error') >= 0)||(request.responseText.length == 0)){
				alert((request.responseText.length==0?"server error.":request.responseText));
			}else{
				if(request.responseText.split('_')[0]==1){
					if(request.responseText.split('_')[1]!='none'){
						msg_block.show_info("File transfer completed!!Click here to download<a href=module/orgseq/excel_file/"+request.responseText.split('_')[1]+" target='_blank'>"+request.responseText.split('_')[1]+"</a>");
					}else{
						msg_block.show_info("No file is generated!!");
					}
				}else{
					msg_block.show_err("File transfer error!");
				}
			}
		}
	}		
}

function print_orgseq(){
	if(confirm('Print Data?')==true){			
		if(_id('f_mb_no').value.length > 0 && _id('f_mb_no').value.trim()!='- Member ID -'){
			var post_str='';
			post_str+='print_orgseq=1&org='+_id('f_org_kind').value;
				post_str+="&mb_no="+_id('f_mb_no').value.trim()+"&his="+_id('f_his_info').value+"&org="+_id('f_org_kind').value;
			createRequest();
			var url="module/orgseq/pdftest.php";
			request.onreadystatechange=print_orgseq_res;
			request.open("POST",url,true);
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(post_str);		
			msg_block.show_info("PDF File is processing...Please wait!!");
		}else{
			msg_block.show_err("Empty date, print not available!");
		}
	}	
}
	
function print_orgseq_res(){
	if(request.readyState==4){
		if(request.status==200){	
			if((request.responseText.indexOf('ERRNO') >= 0)||(request.responseText.indexOf('error') >= 0)||(request.responseText.length == 0)){
				alert((request.responseText.length==0?"server error.":request.responseText));
			}else{
				if(request.responseText.split('_')[0]==1){
					if(request.responseText.split('_')[1]!='none'){
						msg_block.show_info("File transfer completed!!<a href=./index_pdf.php?module=<?php echo $_SESSION['module']?>/MEMORG&url="+request.responseText.split('_')[1]+" target='_blank'>Click here to download</a>");
					}else{
						msg_block.show_info("No file is generated!!");
					}
				}else{
					msg_block.show_err("File transfer error!");
				}
			}
		}
	}		
}
function show_info_mb(){
	flag=false;
	var mb_no=_id('rem_mb_no').value;
	var year=_id('year').value;
	//alert(mb_no);	
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	var post_str='show_info=1&mb_no2='+mb_no+'&year='+year;
	request.onreadystatechange=show_info_res;
	//alert(post_str);
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

function show_info_res(){
	if(request.readyState==4){
		if(request.status==200){
			var targetTb=_id("orgseq_list_table");
			//如果畫面上已yes資料,清除舊資料
			if(targetTb.rows.length>1){
				i=(targetTb.rows.length-1);
				while(i>=0){
					targetTb.deleteRow(i);
					i--;
				}
			}
			//alert(request.responseText);
			if(request.responseText=='none'){
				_id('display_mb').innerHTML="<br><div class='s_org3'>This member has no current year information</div>";
			}else{
				var mb_no5=_id('rem_mb_no').value;
				var flist_data=request.responseText;
				var listJObj5=flist_data.parseJSON();
				var fcount5=listJObj5.show.data.length;
				var mb_name5=listJObj5.show.data[0].mb_name;
				var month=new Array();
				
				var xyz=0;
				var xya=0;
				
				//共12個Month==>xyz
				for(xyz=1;xyz<=12;xyz++){
					if(xyz<10){
						xyz='0'+xyz;
					}
					//各項Details==>xya
					month[xyz] = new Array(8);
					for(xya=0;xya<6;xya++){
						month[xyz][xya] = 0;
					}
				}
			
				var lc5=0;					
				while(lc5<fcount5){	//info5.push("<tr><td>"+listJObj5.show.data[lc5].yymm+"</td><td>"+listJObj5.show.data[lc5].grade_class+"</td><td>"+listJObj5.show.data[lc5].intro_money+"</td><td>"+listJObj5.show.data[lc5].red2_money+"</td><td>"+listJObj5.show.data[lc5].org_money+"</td><td>"+listJObj5.show.data[lc5].lead_money+"</td><td>"+listJObj5.show.data[lc5].red1_money+"</td><td>"+listJObj5.show.data[lc5].red3_money+"</td><td>"+listJObj5.show.data[lc5].red_money+"</td><td>"+listJObj5.show.data[lc5].red7_money+"</td><td>"+listJObj5.show.data[lc5].ab_money+"</td></tr>");
					month[listJObj5.show.data[lc5].mm][0]=listJObj5.show.data[lc5].mm;
					month[listJObj5.show.data[lc5].mm][1]=listJObj5.show.data[lc5].yymm;
					month[listJObj5.show.data[lc5].mm][2]=listJObj5.show.data[lc5].name;
					month[listJObj5.show.data[lc5].mm][3]=listJObj5.show.data[lc5].intro_sum;
					month[listJObj5.show.data[lc5].mm][4]=listJObj5.show.data[lc5].per_m;
					month[listJObj5.show.data[lc5].mm][5]=listJObj5.show.data[lc5].org_m;
					
				/*
					month[listJObj5.show.data[lc5].mm][0]=listJObj5.show.data[lc5].mm;
					month[listJObj5.show.data[lc5].mm][1]=listJObj5.show.data[lc5].yymm;
					month[listJObj5.show.data[lc5].mm][2]=listJObj5.show.data[lc5].name;
					month[listJObj5.show.data[lc5].mm][3]=listJObj5.show.data[lc5].intro_money;
					month[listJObj5.show.data[lc5].mm][4]=listJObj5.show.data[lc5].red2_money;
					month[listJObj5.show.data[lc5].mm][5]=listJObj5.show.data[lc5].org_money;
					month[listJObj5.show.data[lc5].mm][6]=listJObj5.show.data[lc5].lead_money;
					month[listJObj5.show.data[lc5].mm][7]=listJObj5.show.data[lc5].red1_money;
					month[listJObj5.show.data[lc5].mm][8]=listJObj5.show.data[lc5].red3_money;					
					month[listJObj5.show.data[lc5].mm][9]=listJObj5.show.data[lc5].red_money;					
					month[listJObj5.show.data[lc5].mm][10]=listJObj5.show.data[lc5].red7_money;					
					month[listJObj5.show.data[lc5].mm][11]=listJObj5.show.data[lc5].ab_money;
				*/
					lc5++;
				}
				_id('display_mb').innerHTML="<br><div class='s_org3'>"+mb_name5+" ( "+mb_no5+" )</div>";
				/*
				ndlr=targetTb.insertRow(-1);	
				ndlc=ndlr.insertCell(-1);
				ndlc.colSpan=3;
				ndlc.innerHTML=mb_name5+" ( "+mb_no5+" )";
				*/
				ndlr=targetTb.insertRow(-1);	
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Date Year Month</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Ranking</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Total direct sponsor</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Personal performance</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Group sales performance</div>";
				
				
				for(xyz=1;xyz<=12;xyz++){
					if(xyz<10){
						xyz='0'+xyz;
					}
					ndlr=targetTb.insertRow(-1);	
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+_id('year').value+xyz+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][2]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][3]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][4]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][5]+"</div>";
				}
				highlighRow("orgseq_list_table","#E6ECFF");
			}
		}
	}
}

function showAward(){
	//_id('display_mb').innerHTML='';
	if(_id('rem_mb_no').value.length<1){
		alert("No members selected!");
	}else{
		if(flag){
			return show_info_mb();
		}else{
			flag=true;
			var mb_no=_id('rem_mb_no').value;
			var year=_id('year').value;
			createRequest();
			var url='module/orgseq/ajax.orgseq.php';
			post_str='show_award=1&mb_no2='+mb_no+'&year='+year;
			request.onreadystatechange=showAwardRes;
			//alert(post_str);
			request.open('POST',url,true);
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(post_str);
		}
	}
}

function showAwardRes(){
	if(request.readyState==4){
		if(request.status==200){
			var targetTb=_id("orgseq_list_table");
			//如果畫面上已yes資料,清除舊資料
			if(targetTb.rows.length>1){
				i=(targetTb.rows.length-1);
				while(i>=0){
					targetTb.deleteRow(i);
					i--;
				}
			}
			//alert(request.responseText);
			if(request.responseText=='none'){
				_id('display_mb').innerHTML="<br><div class='s_org3'>This member has no current year information</div>";
			}else{
				var mb_no5=_id('rem_mb_no').value;
				var flist_data=request.responseText;
				var listJObj5=flist_data.parseJSON();
				var fcount5=listJObj5.show.data.length;
				var mb_name5=listJObj5.show.data[0].mb_name;
				var month=new Array();
				var xyz=0;
				var xya=0;
				//共12個Month==>xyz
				for(xyz=1;xyz<=12;xyz++){
					if(xyz<10){
						xyz='0'+xyz;
					}
					//各項Details==>xya
					month[xyz] = new Array(9);
					for(xya=0;xya<7;xya++){
						month[xyz][xya] = 0;
					}
				}
			
				var lc5=0;					
				while(lc5<fcount5){
					month[listJObj5.show.data[lc5].mm][0]=listJObj5.show.data[lc5].mm;
					month[listJObj5.show.data[lc5].mm][1]=listJObj5.show.data[lc5].yymm;
					month[listJObj5.show.data[lc5].mm][2]=listJObj5.show.data[lc5].name;
					month[listJObj5.show.data[lc5].mm][3]=listJObj5.show.data[lc5].intro_sum;
					month[listJObj5.show.data[lc5].mm][4]=listJObj5.show.data[lc5].per_m;
					month[listJObj5.show.data[lc5].mm][5]=listJObj5.show.data[lc5].org_m;					
					month[listJObj5.show.data[lc5].mm][6]=listJObj5.show.data[lc5].subtotal;
					lc5++;
				}
				_id('display_mb').innerHTML="<br><div class='s_org3'>"+mb_name5+" ( "+mb_no5+" )</div>";
				/*
				ndlr=targetTb.insertRow(-1);	
				ndlc=ndlr.insertCell(-1);
				ndlc.colSpan=3;
				ndlc.innerHTML=mb_name5+" ( "+mb_no5+" )";
				*/
				ndlr=targetTb.insertRow(-1);	
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Date Year Month</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Ranking</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Total direct sponsor</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Personal performance</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>Group sales performance</div>";
				ndlc=ndlr.insertCell(-1);
				ndlc.innerHTML="<div class='s_org1'>bonus</div>";
				
				for(xyz=1;xyz<=12;xyz++){
					if(xyz<10){
						xyz='0'+xyz;
					}
					ndlr=targetTb.insertRow(-1);	
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+_id('year').value+xyz+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][2]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][3]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][4]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][5]+"</div>";
					ndlc=ndlr.insertCell(-1);
					ndlc.innerHTML="<div id='s_org2'>"+month[xyz][6]+"</div>";
				}	
				highlighRow("orgseq_list_table","#E6ECFF");				
			}
		}
	}
}

function headsearchOrg2(){
	var mb_no=arguments[0];
	if(mb_no.length>0){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='orgseq=0&mb_no='+mb_no+'&org_kind='+_id('f_org_kind').value+'&his='+_id('f_his_info').value;
		request.onreadystatechange=headsearchRes2;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function subsearchRes2(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				res=request.responseText.parseJSON();
				res_true_intro_no=res.true_intro_no;
				res_count=res.data.length;
				orgData_count=orgData.data.length;
				var i=0;
				var obj=document.getElementById('org0info|'+res.true_intro_no);
				_id('load_div_info').parentNode.removeChild(_id('load_div_info'));
				while(i<res_count){
					objTarget=_id('BLOCK_orgseq_body2');
					dv=document.createElement('div');
					dv.id='org0info|'+res.data[i].mb_no;	//Settingtr id為org0info_+Member ID
					dv.title=(eval("res.data[i]."+level_no));
					insertAfter(dv,obj);
					obj=dv;
					dv_info=new Array();
					//20100329 Add字串Front方Add入&nbsp;  by Bear Dale
					dv_info.push('&nbsp;<span class=line_flag>'+chgNumToImg2(eval("orgData.data[orgData.index[res.data[i]."+true_intro_no+"]].parent_label+res.data[i].line_label"))+'</span>');
					dv_info.push(chgGradeImg2(res.data[i].grade_class));
					dv_info.push('<span>'+(eval("res.data[i]."+level_no)-tmp_level1)+' generation</span>');
					if(_id('f_org_kind').value == 1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+res.data[i].line_kind+'.gif></span>');
					}
					dv_info.push("<span id='show1_"+(res.data[i].mb_no)+"'>");
					//dv_info.push("<span id=id onclick=memo('"+(res.data[i].mb_no)+"')>"+res.data[i].mb_no+"</span>");
					//dv_info.push("<span class='hastooltip' className='hastooltip' id='show_msg"+res.data[i].mb_no+"' onclick=show_info_mb('"+(res.data[i].mb_no)+"',2)>"+res.data[i].mb_name+"</span>");
					dv_info.push("<span id=id onclick=choiceMB('"+(res.data[i].mb_no)+"') onmouseover=hover_list('"+(res.data[i].mb_no)+"') onmouseout=hover_list_out('"+(res.data[i].mb_no)+"')>"+res.data[i].mb_no+"</span>");
					dv_info.push("<span id=name onclick=choiceMB('"+(res.data[i].mb_no)+"') onmouseover=hover_list('"+(res.data[i].mb_no)+"') onmouseout=hover_list_out('"+(res.data[i].mb_no)+"')>"+res.data[i].mb_name+"</span>");
					dv_info.push("</span>");
					//dv_info.push('<span >'+res.data[i].mb_name+'</span>');
					/*
					if(_id('f_his_info').value==-1){
						var tmpObj1='<?php echo $gdata?>';
						
						give_tb1=tmpObj1.parseJSON();
						
						gcount1=give_tb1.data.length;
						c1=0;
						
						while(c1<gcount1){
							fie=give_tb1.data[c1].enfield;
							//alert(fie) ;
							dv_info.push("<span class='"+fie+"' style='color:"+give_tb1.data[c1].color+"'>"+give_tb1.data[c1].chfield+':'+eval("res.data[i]."+fie)+'</span>');
							c1++;
						}
					}
					*/
					//dv_info.push('<span class=per_m>'+res.data[i].per_m+'</span>');
					//dv_info.push('<span class=grade>'+res.data[i].grade_name+'</span>');
					//dv_info.push('<span class=pg_date>Date Join：'+res.data[i].pg_date+'</span>');
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
					
					orgData.index[res.data[i]['mb_no']]=orgData_count+i;
					res.data[i].parent_label=eval("orgData.data[orgData.index[res.data[i]."+true_intro_no+"]].parent_label+res.data[i].parent_label");
					orgData.data.push(res.data[i]);
					i++;
				}
			}
		}
	}
}
/*
 * query下線Member
 */
function subsearchOrg2(){
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	post_str='orgseq=0&sub_mb_no='+arguments[0]+'&limit='+_id('level_limit_info').value+'&org_kind='+_id('f_org_kind').value+'&his='+_id('f_his_info').value+"&top_mb_no="+_id("f_mb_no").value;
	request.onreadystatechange=subsearchRes2;
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

/*
 * 處理滑鼠點擊事件
 */
function nodeEvent2(event){
	if(_id('changed').value.length>0){
		alert("You have modified your query criteria,Press the Search button");
		return true;
	}
	if(typeof event == 'undefined'){
		event=window.event;
	}
	var target=getEventTarget(event);
	if(typeof target.src != 'undefined'){
		objTarget=target.parentNode.parentNode.parentNode;
		tarLevel_no1=Number(target.parentNode.parentNode.parentNode.title);
		
		if((target.title=='pnode')||(target.title=='lastpnode')){
			if(objTarget != objTarget.parentNode.lastChild){
				if(Number(objTarget.nextSibling.title)>tarLevel_no1){
					tmp_obj=objTarget;
					done=0;
					while((tmp_obj.nextSibling.title)>tarLevel_no1 && done==0){
						if(tmp_obj.nextSibling.title <= (tarLevel_no1+Number(_id('level_limit_info').value))){
							tmp_obj.nextSibling.style.display='';
							if(tmp_obj.nextSibling.title <(tarLevel_no1+Number(_id('level_limit_info').value))){
								ig=tmp_obj.nextSibling.getElementsByTagName('img');
								igl=ig.length;
								ic=0;
								while(ic<igl){
									if(tmp_obj.nextSibling != tmp_obj.parentNode.lastChild){
										if(tmp_obj.nextSibling.title<tmp_obj.nextSibling.nextSibling.title){
											if(ig[ic].title == 'pnode'){
												ig[ic].src=snode.src;
												ig[ic].title=snode.title;
											}
											if(ig[ic].title == 'lastpnode'){
												ig[ic].src=lastsnode.src;
												ig[ic].title=lastsnode.title;
											}
										}
									}
									ic++;
								}
							}
						}
						if(tmp_obj.nextSibling != tmp_obj.parentNode.lastChild){
							tmp_obj=tmp_obj.nextSibling;
						}else{
							done=1;
						}
					}
				}else{
					load_dv=document.createElement("div");
					load_dv.id='load_div_info';
					insertAfter(load_dv,target.parentNode.parentNode.parentNode);
					//load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
					subsearchOrg2(target.parentNode.parentNode.parentNode.id.split('|')[1]);
				}
			}else{
				load_dv=document.createElement("div");
				load_dv.id='load_div_info';
				insertAfter(load_dv,target.parentNode.parentNode.parentNode);
				//load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
				subsearchOrg2(target.parentNode.parentNode.parentNode.id.split('|')[1]);
			}
			if(target.title == 'pnode'){
				target.src=snode.src;
				target.title=snode.title;
			}else{
				target.src=lastsnode.src;
				target.title=lastsnode.title;
			}
		}else if((target.title=='snode')||(target.title=='lastsnode')){
			tmp_obj=objTarget;
			done=0;
			while(Number(tmp_obj.nextSibling.title)>tarLevel_no1 && done==0){
				tmp_obj.nextSibling.style.display='none';
				/* 因為yes很多image包含空白等等... */
				ig=tmp_obj.nextSibling.getElementsByTagName('img');
				igl=ig.length;
				ic=0;
				while(ic<igl){
					if(ig[ic].title == 'snode'){
						ig[ic].src=pnode.src;
						ig[ic].title=pnode.title;
					}
					if(ig[ic].title == 'lastsnode'){
						ig[ic].src=lastpnode.src;
						ig[ic].title=lastpnode.title;
					}
					ic++;
				}
				if(tmp_obj.nextSibling != tmp_obj.parentNode.lastChild){
					tmp_obj=tmp_obj.nextSibling;
				}else{
					done=1;
				}
			}
			if(target.title == 'snode'){
				target.src=pnode.src;
				target.title=pnode.title;
			}else{
				target.src=lastpnode.src;
				target.title=lastpnode.title;
			}
		}
	}
}

function choiceMB(mb_no){
	//alert(mb_no);	
	//alert(mb_no+'     '+_id('rem_mb_no').value);	
	flag=false;
	//_id('display_mb').innerHTML='';
	var rem_mb_no=_id('rem_mb_no').value;
	if(rem_mb_no!=mb_no && _id('rem_mb_no').value.length>0){
		_id('show1_'+rem_mb_no).style.backgroundColor='#FFFFFF';
		_id('show1_'+mb_no).style.backgroundColor='#51B6FF';
	}else{
		_id('show1_'+mb_no).style.backgroundColor='#51B6FF';
		
	}
	_id('rem_mb_no').value=mb_no;
	show_info_mb();
}
function choiceMB2(mb_no){
	flag=false;
	//_id('display_mb').innerHTML='';
	//var rem_mb_no=_id('rem_mb_no').value;
	_id('show1_'+mb_no).style.backgroundColor='#51B6FF';
	_id('rem_mb_no').value=mb_no;
	show_info_mb();
}
/*
function findMember(){
	find_mb_no=_id('find_mb_no').value;
	mb_no=_id('mb_no').value:
	//alert(_id('mb_no').value.length+'+'+_id('rem_mb_no').value.length);
	if(_id('find_mb_no').value.length<1){
		alert("No members selected!");
	}else{
		if((_id('mb_no').value.length<1 || _id('mb_no').value=='- Member ID -') && _id('rem_mb_no').value.length<1){
			_id('mb_no').value=find_mb_no;
			toChgOrg2();
		}else{
			createRequest();
			var url='module/orgseq/ajax.orgseq.php';
			post_str='show_award=1&mb_no2='+mb_no;
			request.onreadystatechange=showAwardRes;
			//alert(post_str);
			request.open('POST',url,true);
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(post_str);
		
		
		}
	}
	//_id('rem_mb_no').value=mb_no;
	//show_info_mb();
}
*/
function chgGradeImg2(){
	switch(arguments[0]){
		<?php
			$grade_query="SELECT no,name,img FROM grade where no >= 0 ORDER BY no";
			$grade_res=$db->query($grade_query);
			$i=1;
			while($grade=$grade_res->fetch()){
				echo "case '".$grade['no']."':";
				echo "return \"<img src='module/orgseq/grade/".$grade['img']."' title='".$grade['name']."'>\";";
				echo "break;";
				$i++;
			}
		?>
	}
}
function chgNumToImg2(){
	var l=0;
	var strl=arguments[0].length;
	var ar=new Array();
	while(l<strl){
		ar.push(arguments[0].charAt(l));
		l++;
	}
	var arc=0;
	var arl=ar.length;
	var img='';
	while(arc<arl){
		switch(ar[arc]){
			case '0':
				img+="<img src='"+spacer.src+"' title='"+spacer.title+"'>";
				break;
			case '1':
				img+="<img src='"+vline.src+"' title='"+vline.title+"'>";
				break;
			case '2':
				img+="<img src='"+node.src+"' title='"+node.title+"'>";
				break;
			case '3':
				img+="<img src='"+lastnode.src+"' title='"+lastnode.title+"'>";
				break;
			case '4':
				img+="<img src='"+pnode.src+"' title='"+pnode.title+"'>";
				break;
			case '5':
				img+="<img src='"+lastpnode.src+"' title='"+lastpnode.title+"'>";
				break;
			case '6':
				img+="<img src='"+snode.src+"' title='"+snode.title+"'>";
				break;
			case '7':
				img+="<img src='"+lastsnode.src+"' title='"+lastsnode.title+"'>";
				break;
		}
		arc++;
	}
	return img;
}

function chk_country3(){
	_id('display_mb').innerHTML="";
	_id('rem_mb_no').value="";
	var targetTb=_id("orgseq_list_table");
	//如果畫面上已yes資料,清除舊資料
	if(targetTb.rows.length>1){
		i=(targetTb.rows.length-1);
		while(i>=0){
			targetTb.deleteRow(i);
			i--;
		}
	}
	if(_id('f_mb_no').value.length>0){
		post_str='chk_ct=1&ct_mb_no='+_id('f_mb_no').value.trim();
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country_res3;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function chk_country_res3(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				alert('This account does not have permission to check this country member!');
			}else{
				toChgOrg2();
			}
		}
	}
}

function toChgOrg2(){
	_id('BLOCK_orgseq_body2').innerHTML='';
	_id('changed').value='';
	if(_id('f_org_kind').value==0){
		true_intro_no='true_intro_no';
		level_no='level_no1';
	}else{
		true_intro_no='intro_no';
		level_no='level_no';
	}
	if(_id('f_mb_no').value.length>0){
		headsearchOrg2(_id('f_mb_no').value.trim());
	}
}

function chk_country4(){
	_id('display_mb').innerHTML="<div class='s_org3'>Data is processing...</div>";
	var targetTb=_id("orgseq_list_table");
	//如果畫面上已yes資料,清除舊資料
	if(targetTb.rows.length>1){
		i=(targetTb.rows.length-1);
		while(i>=0){
			targetTb.deleteRow(i);
			i--;
		}
	}
	if(_id('find_mb_no').value.length>0){
		post_str='chk_ct=1&ct_mb_no='+_id('find_mb_no').value;
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country_res4;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function chk_country_res4(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				//alert('This account does not have permission to check this country member!');
				_id('display_mb').innerHTML="<div class='s_org3'>This account does not have permission to check this country member!</div>";
			}else{
				findSearchOrg();
			}
		}
	}
}

//EnquiryNetwork chart
function findSearchOrg(){
	//_id('display_mb').innerHTML='';
	if(_id('find_mb_no').value == ''){
		alert('Please enter the member ID to search');
	}else if((_id('f_mb_no').value.length<1 || _id('f_mb_no').value.trim()=='- Member ID -') && _id('rem_mb_no').value.length<1){
		alert('Please enter the member ID you wish to check');
		//_id('f_mb_no').value.trim()=_id('find_mb_no').value;
		//toChgOrg2();
	}else{
		post_str='orgseq=9&find_mb_no='+_id('find_mb_no').value+'&org_kind='+_id('f_org_kind').value+'&his='+_id('f_his_info').value;
		post_str+='&mbst_mb_no='+_id('f_mb_no').value.trim();
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=findSearchRes;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function findSearchRes(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			
			}else if(request.responseText=='NOFIND'){
				//alert('This member is not in the network!');
				_id('display_mb').innerHTML="<div class='s_org3'>This member is not in the network!</div>";
			}else{			
				temp = request.responseText.parseJSON();
				orgData = temp;
				res = temp;
				
				obj=_id('BLOCK_orgseq_body2');
				//移除first ul下的所yes節點
				while(obj.childNodes.length>0){
					obj.removeChild(obj.lastChild);
				}
				//alert(temp.data.length);
				var i = 0;
				while(i < temp.data.length){
					if(i == 0){
						//第一筆資料
						dv=document.createElement("div");
						dv.id='org0info|'+orgData.data[i].mb_no;
						dv.title=eval("orgData.data[i]."+level_no);
						
						obj.appendChild(dv);
						
						dv_info=new Array();
						dv_info.push('&nbsp;<span class=line_flag>'+chgNumToImg2(orgData.data[i].line_label)+chgGradeImg2(orgData.data[i].grade_class)+'</span>');
						dv_info.push('<span>0 generation</span>');
						
						tmp_level1=eval("orgData.data[i]."+level_no);
						if(_id('f_org_kind').value==1){
							dv_info.push('<span class=line_img><img src=orgseq/line_img/gr'+orgData.data[i].line_kind+'.gif></span>');
						}
						
						dv_info.push("<span id='show1_"+(orgData.data[i].mb_no)+"'>");
						dv_info.push("<span id=id onclick=choiceMB('"+(orgData.data[i].mb_no)+"')>"+orgData.data[i].mb_no+"</span>");
						dv_info.push("<span class=name onclick=choiceMB('"+(orgData.data[i].mb_no)+"')>"+orgData.data[i].mb_name+"</span>");
						//dv_info.push("<span id=id onclick=memo('"+(orgData.data[0].mb_no)+"')>"+orgData.data[0].mb_no+"</span>");
						//dv_info.push("<span class='hastooltip' className='hastooltip' id='show_msg' onclick=show_info_mb('"+(orgData.data[0].mb_no)+"',1)>"+orgData.data[0].mb_name+"</span>");
						dv_info.push("</span>");
						/*
						dv_info.push('<span class=per_m>'+orgData.data[i].per_m+'</span>');
						dv_info.push('<span class=grade>'+orgData.data[i].grade_name+'</span>');
						dv_info.push('<span class=pg_date>Date Join：'+orgData.data[i].pg_date+'</span>');
						*/
						dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
						
					}else{
						var obj=document.getElementById('org0info|'+orgData.data[i-1].mb_no);
						
						dv=document.createElement('div');
						dv.id='org0info|'+orgData.data[i].mb_no;	//Settingtr id為org0info_+Member ID
						dv.title=(eval("orgData.data[i]."+level_no));
					
						insertAfter(dv,obj);
						obj=dv;
						
						dv_info=new Array();
						
						dv_info.push('&nbsp;<span class=line_flag>'+chgNumToImg2(eval("orgData.data[orgData.index[orgData.data[i]."+true_intro_no+"]].parent_label+orgData.data[i].line_label"))+'</span>');
						dv_info.push(chgGradeImg2(orgData.data[i].grade_class));
						
						//if(_id('find_mb_no').value != orgData.data[i].mb_no){
							dv_info.push('<span>'+(eval("orgData.data[i]."+level_no)-tmp_level1)+' generation</span>');
						
							if(_id('f_org_kind').value == 1){
								dv_info.push('<span class=line_img><img src=orgseq/line_img/gr'+orgData.data[i].line_kind+'.gif></span>');
							}
							
							dv_info.push("<span id='show1_"+(orgData.data[i].mb_no)+"'>");
							//dv_info.push("<span id=id onclick=memo('"+(orgData.data[i].mb_no)+"')>"+orgData.data[i].mb_no+"</span>");
							//dv_info.push("<span class='hastooltip' className='hastooltip' id='show_msg"+orgData.data[i].mb_no+"' onclick=show_info_mb('"+(orgData.data[i].mb_no)+"',2)>"+orgData.data[i].mb_name+"</span>");
							dv_info.push("<span id=id onclick=choiceMB('"+(orgData.data[i].mb_no)+"')>"+orgData.data[i].mb_no+"</span>");
							dv_info.push("<span id=name onclick=choiceMB('"+(orgData.data[i].mb_no)+"')>"+orgData.data[i].mb_name+"</span>");
							dv_info.push("</span>");
							
						/*}else{
							dv_info.push('<span><font color="#ffffff" style="background-color:#669933">'+(eval("orgData.data[i]."+level_no)-tmp_level1)+' generation</font></span>');
							
							if(_id('f_org_kind').value == 1){
								dv_info.push('<span class=line_img><img src=orgseq/line_img/gr'+orgData.data[i].line_kind+'.gif></span>');
							}
							
							dv_info.push("<span id=id onclick=memo('"+(orgData.data[i].mb_no)+"')><font color='#ffffff' style='background-color:#669933'>"+orgData.data[i].mb_no+"</font></span>");
							dv_info.push("<span class='hastooltip' className='hastooltip' id='show_msg"+orgData.data[i].mb_no+"' onclick=show_info_mb('"+(orgData.data[i].mb_no)+"',2)><font color='#ffffff' style='background-color:#669933'>"+orgData.data[i].mb_name+"</font></span>");
						}
						*/
						/*
						var tmpObj1='<?php echo $gdata?>';
						give_tb1=tmpObj1.parseJSON();
						gcount1=give_tb1.data.length;
						c1=0;
						while(c1<gcount1){
							fie=give_tb1.data[c1].enfield;
							//alert(fie) ;
							dv_info.push("<span class='"+fie+"' style='color:"+give_tb1.data[c1].color+"'>"+give_tb1.data[c1].chfield+':'+eval("orgData.data[i]."+fie)+'</span>');
							c1++;
						}
						*/
						dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
						
						orgData.index[orgData.data[i]['mb_no']]=i;
						orgData.data[i].parent_label = eval("orgData.data[orgData.index[orgData.data[i]."+true_intro_no+"]].parent_label+orgData.data[i].parent_label");
					}
					
					i++
				}
				
				//移動捲軸
				// alert(_id('BLOCK_orgseq_body2').scrollHeight);
				// alert(_id('show1_'+_id('find_mb_no').value).offsetLeft);
				// alert(_id('show1_'+_id('find_mb_no').value).offsetTop);
				//_id('BLOCK_orgseq_body2').scrollLeft = (_id('BLOCK_orgseq_body2').scrollWidth - _id('show1_'+_id('find_mb_no').value).offsetLeft) + 5;
				//_id('BLOCK_orgseq_body2').scrollTop = _id('BLOCK_orgseq_body2').scrollHeight - _id('show1_'+_id('find_mb_no').value).offsetTop;
				
				_id('BLOCK_orgseq_body2').scrollLeft =  _id('show1_'+_id('find_mb_no').value).offsetLeft;
				if(_id('show1_'+_id('find_mb_no').value).offsetTop>600){
					_id('BLOCK_orgseq_body2').scrollTop = _id('show1_'+_id('find_mb_no').value).offsetTop/2;
				}else{
					_id('BLOCK_orgseq_body2').scrollTop = _id('show1_'+_id('find_mb_no').value).offsetTop/3;
				}
				//移動捲軸 End
				choiceMB2(_id('find_mb_no').value);			
			}
		}
	}
}
//EnquiryNetwork chart END


function showChart(){
	_id('display_mb').innerHTML='';
	if(_id('rem_mb_no').value.length<1){
		alert("No members selected!");
	}else{
		flag=false;
		var mb=_id('rem_mb_no').value;
		var chart_kind=_id('chart_kind').value;
		var year=_id('year').value;
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='showchart=1&mb_no2='+mb+'&chart='+chart_kind+'&year='+year;
		request.onreadystatechange=showChartRes;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
		//chart=_id('chart_kind').value;
		//window.open("./module/qmbst/chart.php?mb="+mb+"&chart="+chart,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
	}
}
function showChartRes(){
	if(request.readyState==4){
		if(request.status==200){
			//alert(request.responseText);
			var mb_no=request.responseText.split('@')[0];
			var mb_name=request.responseText.split('@')[1];
			var max=request.responseText.split('@')[2];
			var chd_str=request.responseText.split('@')[3];
			var chart_str=request.responseText.split('@')[4];
			var targetTb=_id("orgseq_list_table");
			//如果畫面上已yes資料,清除舊資料
			if(targetTb.rows.length>1){
				i=(targetTb.rows.length-1);
				while(i>=0){
					targetTb.deleteRow(i);
					i--;
				}
			}
			ndlr=targetTb.insertRow(-1);	
			ndlc=ndlr.insertCell(-1);
			ndlc.innerHTML="<div class='s_org3'>"+"Member："+mb_name+" ( "+mb_no+" ) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; "+chart_str+"</div>";
			ndlr=targetTb.insertRow(-1);	
			ndlr=targetTb.insertRow(-1);	
			ndlc=ndlr.insertCell(-1);
			//兩圖合併					//tmp_chart_str="http://chart.apis.google.com/chart?cht=lc&chs=500x400&chxt=x,y&chm=N,E0BC2A,0,,12|N,589A57,1,,12&chco=FDD432,589A57&chdl=組織業績|Automatic Order&chxl=0:|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec&chxr=1,0,"+max+"&chds=0,"+max+chd_str;
			//兩圖Minute開
			tmp_chart_str="http://chart.apis.google.com/chart?cht=lc&chs=600x400&chxt=x,y&chm=N,000000,0,-1,12&chxl=0:|Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec&chxr=1,0,"+max+"&chds=0,"+max+chd_str;
			ndlc.innerHTML="<img src='"+tmp_chart_str+"' width='550' height='367' />";
		}	
	}
}
attachEventListener(_id('f_chg_btn'),'click',chk_country3,false);
attachEventListener(_id('f_org_kind'),'change',function(){_id('changed').value=1},false);
attachEventListener(_id('f_his_info'),'change',function(){_id('changed').value=1},false);
function blur_mb_no2(){
	_id('f_mb_no').defaultValue='- Member ID -';
	if(_id('f_mb_no').value.length<1){
		_id('f_mb_no').value=_id('f_mb_no').defaultValue.trim();
		_id('f_mb_no').style.color="#CCC";
		_id('f_mb_no').style.paddingLeft="5px";
		_id('f_mb_no').style.background="#FEFEFE";
	}
}
function focus_mb_no2(){
	if(_id('f_mb_no').value.trim() == _id('f_mb_no').defaultValue){
		_id('f_mb_no').value='';
		_id('f_mb_no').style.color="#333";
		_id('f_mb_no').style.paddingLeft="0px";
		_id('f_mb_no').style.background="#E5FFDD";
	}
}
attachEventListener(_id('f_mb_no'),"click",focus_mb_no2,false);
attachEventListener(_id('f_mb_no'),"blur",blur_mb_no2,false);
</script>

<script type='text/javascript'>
function ini_orgseq_info(){
	document.getElementsByTagName('body')[0].id='block1';
	_id('add_btn_block').style.display='none';
	_id('save_btn_block').style.display='none';
	_id('modify_btn_block').style.display='none';
	_id('cancel_btn_block').style.display='none';
	_id('del_btn_block').style.display='none';
	_id('print_btn_block').style.display='none';
	_id('module_title').getElementsByTagName('span')[0].innerHTML="Member Network Enquiry";
}
_id('display_mb').innerHTML='';
_id('f_org_kind').value='<?php echo isset($_GET['org_kind'])?$_GET['org_kind']:0 ?>';
_id('f_his_info').value='<?php echo isset($_GET['his'])?$_GET['his']:-1 ?>';
attachEventListener(_id('BLOCK_orgseq_body2'),'click',nodeEvent2,false);
attachEventListener(_id('chart_kind'),'change',showChart,false);
attachEventListener(_id('f_mb_no'),'blur',query_unit2,false);
blur_mb_no2();
yymm=<?php echo $yy?>;
ycount=yymm.ydata.length;
a=0;	
while(a<ycount){		
	_id('year').options[a]=new Option(yymm.ydata[a].yy,yymm.ydata[a].yy);		
	a++;
}
//headsearchOrg2('<?php echo $_SESSION['mb']['f_mb_no']?>');
attachEventListener(_id('f_mb_no'),'dblclick',function(){outwork('f_mb_no');},false);
function outwork(fun){
  	window.open("./module/qmbst/qmbst.php?fun="+fun,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
}
function memo(){
	if(_id('rem_mb_no').value.length<1){
		alert("No members selected!");
	}else{
		flag=false;
		mb=_id('rem_mb_no').value;
		window.open("./module/qmbst/memo.php?mb="+mb,"test","width=710,height=300,scrollbars=yes,resizable=yes,status=yes");  
	}
}
function hover_list(who){
	//alert(who);
	aaa=_id('show1_'+who).style.backgroundColor;
	//alert(aaa);
	if(aaa != 'rgb(81, 182, 255)'){
		_id('show1_'+who).style.backgroundColor='#fcf080';
	}
	
	
}
function hover_list_out(who){
	
	aaa=_id('show1_'+who).style.backgroundColor;
	//alert(aaa);
	if(aaa != 'rgb(81, 182, 255)'){
		_id('show1_'+who).style.backgroundColor='#FFFFFF';
	}
	
}
</script>
