<?php
	$scQ="select have_intro_no,use_e_cash from comp_data";
	$scR=$db->query($scQ);
	if($scR->size()>0){
		$scRow=$scR->fetch();
		//$have_intro預設Yes1 ，yesPlacement ； 0YesNoPlacement
		$have_intro=$scRow['have_intro_no'];
	}

	// $grade_query="SELECT * FROM org_data as a , org_data_demo as b where a.yn ='Y'  and a.enfield = b.enfield and a.org_kind= b.org_kind  order by b.sort  ";
	$grade_query="SELECT * FROM org_data where yn ='Y' order by abs(sort),no";
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
	$gdata5='{"index":'.json_encode($index).',"data":['.$str.']}';
	
	$mb_query="select mb_no from mbst where mb_no=true_intro_no";
	$mb_res=$db->query($mb_query);
	$mb_data=$mb_res->fetch();
	$mb_no1=$mb_data['mb_no'];
	
?>
<style type='text/css'>
	@import "module/orgseq/CSS/orgseq.css";
</style>
<div id='BLOCK_orgseq5'>
	<div class='filter_div'>


		<?php
			$query1="SELECT no,en_name name,img FROM grade order by abs(no)";
			$res1=$db->query($query1);
			while($data1=$res1->fetch()){
				echo "<img src='module/orgseq/grade/".$data1['img']."'>".$data1['name']."　";
			}
		?>
	</div>
	<div class='org_kind_div' style="margin-bottom:10px;">
	Expand generation<select id='level_limit5' class='limit'>
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
		
		
		
		<input type='text' id='f_mb_no5' onblur='UpperStr(this)'  size='15'>
		<select name='f_org_kind5' id='f_org_kind5'>
			<option value=0>Sponsor chart</option>
			<?php if($have_intro==1){?>
			<option value=1>Placement chart</option>
			<?php }?>
		</select>
		<select name='f_his' id='f_his'>
			<option value=-1>Real Time</option>
		</select>
		<input type='hidden' id='changed5'>
		<input type='button' value='Enquiry' id='f_chg_btn5'>
		<!--<input type='button' value='export network chart' id='exp_orgseq_btn' onclick=exp_orgseq5();>-->
		<input type='button' value='PDFexport' id='print_in_org' onclick=print_pdf();>
		<input type='button' value='EXCELexport' id='print_in_org' onclick=print_excel();>
		<font color = '#696969'>Ex : <?php echo $mb_no1;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>		
	</div>	
	<div id='BLOCK_orgseq_body5' class='org_tree_div5'></div>
	<div id='r_m006' class="r_m006">
		<div id='orgseq_list6' ></div>
	</div>
	<div id='r_m005' class="r_m005">
		<div id='orgseq_list5'   ></div>
	</div>
</div>

<script type='text/javascript'>
var true_intro_no5='true_intro_no';
var level_no5='level_no1';
var flag=false;
loader=new Image();
loader.src="images/org_loader.gif";
loader.title="Reading...";
/* export network chart */
function exp_orgseq5(){
	if(_id('f_mb_no5').value.length>0){	
		location.href='module/orgseq/orgData.php?org='+_id('f_org_kind5').value+'&mb_no='+_id('f_mb_no5').value.trim()+'&his='+_id('f_his').value;		
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
function query_unit_res5(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				_id("f_mb_no5").value='';
				alert("Member ID not found!");
			}else{
				unit_data=request.responseText.parseJSON();
				w=1;
				if(unit_data.length>0){
					leng=_id('f_his').options.length-1;
					while(leng>0){
						_id('f_his').remove(leng);
						leng--;
					}
					leng=unit_data.length;
					while(w<=leng){
						_id('f_his').options[w]=new Option(unit_data[w-1],unit_data[w-1]);
						w++;
					}
				}
			}
		}
	}
}
function print_pdf(){
	if(confirm('Print Data?')==true){	
		if(_id('f_mb_no5').value.length > 0 && _id('f_mb_no5').value!='- Member ID -'){
			var post_str='';
			post_str+='print_orgseq=1&org='+_id('f_org_kind5').value;
			post_str+="&mb_no="+_id('f_mb_no5').value.trim()+"&his="+_id('f_his').value+"&org="+_id('f_org_kind5').value+"&level_limit="+_id('level_limit5').value;
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
						msg_block.show_info("File transfer completed!!Click here to download<a href=module/orgseq/MEMORG/"+request.responseText.split('_')[1]+" target='_blank'>"+request.responseText.split('_')[1]+"</a>");
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
function print_excel(){
	if(confirm('Print Data?')==true){	
		if(_id('f_mb_no5').value.length > 0 && _id('f_mb_no5').value!='- Member ID -'){
			var post_str='';
			post_str+='print_orgseq=1&org='+_id('f_org_kind5').value;
			post_str+="&mb_no="+_id('f_mb_no5').value.trim()+"&his="+_id('f_his').value+"&org="+_id('f_org_kind5').value+"&level_limit="+_id('level_limit5').value;
			createRequest();
			var url="module/orgseq/org_excel.php";
			request.onreadystatechange=print_excel_res;
			request.open("POST",url,true);
			request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
			request.send(post_str);		
			msg_block.show_info("EXCEL File is processing...Please wait!!");
		}else{
			msg_block.show_err("Empty date, print not available!");
		}
	}	
}
	
function print_excel_res(){
	if(request.readyState==4){
		if(request.status==200){	
			if((request.responseText.indexOf('ERRNO') >= 0)||(request.responseText.indexOf('error') >= 0)||(request.responseText.length == 0)){
				alert((request.responseText.length==0?"server error.":request.responseText));
			}else{
				if(request.responseText.split('_')[0]==1){
					if(request.responseText.split('_')[1]!='none'){
						msg_block.show_info("File transfer completed!!Click here to download<a href=module/orgseq/MEMORG/"+request.responseText.split('_')[1]+" target='_blank'>"+request.responseText.split('_')[1]+"</a>");
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
function query_unit5(){
	var mb_no=_id("f_mb_no5").value.trim();
	if(mb_no.length>0 && mb_no!=_id('f_mb_no5').defaultValue){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='query_unit=1&mb_no='+mb_no;
		request.onreadystatechange=query_unit_res5;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

var orgData;
var tmp_level1;
function headsearchRes5(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				orgData=request.responseText.parseJSON();
				if((orgData.data.length==1)&&(orgData.data[0].line_label.length==1)){				
					obj=_id('BLOCK_orgseq_body5');
					//移除first ul下的所yes節點
					while(obj.childNodes.length>0){
						obj.removeChild(obj.lastChild);
					}
					dv=document.createElement('div');	
					dv.innerHTML=chgNumToImg5("00");		
					obj.appendChild(dv);
					
					dv=document.createElement("div");
					dv.id='org5|'+orgData.data[0].mb_no;
					dv.title=eval("orgData.data[0]."+level_no5);
					obj.appendChild(dv);
					dv_info=new Array();
					dv_info.push('<span class=line_flag>'+chgNumToImg5(orgData.data[0].line_label)+chgGradeImg5(orgData.data[0].grade_class)+'</span>');
					dv_info.push('<span style="padding-top:10px;">0 generation</span>');
					tmp_level1=eval("orgData.data[0]."+level_no5);
					if(_id('f_org_kind5').value==1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+orgData.data[0].line_kind+'.gif></span>');
					}
					dv_info.push("<span id='show_"+(orgData.data[0].mb_no)+"'>");
					dv_info.push("<span id=id >"+orgData.data[0].mb_no+"</span>");
					dv_info.push("<span class=name >"+orgData.data[0].mb_name+"</span>");
					dv_info.push("</span>");
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
					
					//Right邊資料
					obj=_id('r_m005');					
					//移除first ul下的所yes節點(保留表頭)
					while(obj.childNodes.length>2){
						obj.removeChild(obj.lastChild);
					}					
					dv=document.createElement("div");
					dv.setAttribute("className","right_orgseq_list5");
					dv.setAttribute("class","right_orgseq_list5");
					dv.id='rorg5|'+orgData.data[0].mb_no;
					dv.title=eval("orgData.data[0]."+level_no5);
					obj.appendChild(dv);
					dv_info=new Array();
					//改成根據Column 抓資料
					var tmpObj2='<?php echo $gdata5?>';					
					give_tb2=tmpObj2.parseJSON();					
					gcount2=give_tb2.data.length;
					c2=0;				
					while(c2<gcount2){
						if(_id('f_org_kind5').value=='1'){
							var org_kind='intro_no';
						}else{
							var org_kind='true_intro_no';
						}
						if(give_tb2.data[c2].org_kind==org_kind){
							var fie=give_tb2.data[c2].enfield;							
							dv_info.push("<div class='right_org_data' style='color:"+give_tb2.data[c2].color+"'>"+eval("orgData.data[0]."+fie)+"</div>");
						}
						c2++;
					}
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
				}
				document.getElementById("BLOCK_orgseq_body5").onscroll=function(){
					scrollWin1();
				};
				document.getElementById("r_m005").onscroll=function(){
					scrollWin2();
				};
				document.getElementById("r_m006").onscroll=function(){
					scrollWin3();
				};
			}
		}
	}
}

function headsearchOrg5(){
	var mb_no=arguments[0];
	if(mb_no.length>0){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='orgseq=0&mb_no='+mb_no+'&org_kind='+_id('f_org_kind5').value+'&his='+_id('f_his').value;
		request.onreadystatechange=headsearchRes5;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function right_headsearchRes5(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				obj=_id('orgseq_list6');
				//移除first ul下的所yes節點
				while(obj.childNodes.length>0){
					obj.removeChild(obj.lastChild);
				}	
				var flist_data=request.responseText;
				orgData=flist_data.parseJSON();
				doe=orgData.data.length;
				dst=0;
				while(dst<doe){					
					dv=document.createElement("div");							
					obj.appendChild(dv);
					dv.id='for_title';
					dv_info=new Array();
					fie=orgData.data[dst].enfield;
					dv_info.push("<span class='"+fie+"' style='color:"+orgData.data[dst].color+"'>"+orgData.data[dst].chfield_en+"</span>");						
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
					dst++
				}	
					
				headsearchOrg5(_id('f_mb_no5').value.trim());
				
			}
		}
	}
}

function right_headsearchOrg5(){
	var mb_no=arguments[0];
	if(mb_no.length>0){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='right_orgseq=0&org_kind='+_id('f_org_kind5').value+'&his='+_id('f_his').value;
		request.onreadystatechange=right_headsearchRes5;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function subsearchRes5(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				res=request.responseText.parseJSON();
				res_true_intro_no=res.true_intro_no;
				res_count=res.data.length;
				orgData_count=orgData.data.length;
				var i=0;
				var obj=document.getElementById('org5|'+res.true_intro_no);
				_id('load_div_info5').parentNode.removeChild(_id('load_div_info5'));
				var objr=document.getElementById('rorg5|'+res.true_intro_no);
				
				while(i<res_count){
					objTarget=_id('BLOCK_orgseq_body5');
					dv=document.createElement('div');
					dv.id='org5|'+res.data[i].mb_no;	//Settingtr id為org5_+Member ID
					dv.title=(eval("res.data[i]."+level_no5));
					insertAfter(dv,obj);
					obj=dv;
					dv_info=new Array();
					//20100329 Add字串Front方Add入&nbsp;  by Bear Dale
					dv_info.push('&nbsp;<span class=line_flag>'+chgNumToImg5(eval("orgData.data[orgData.index[res.data[i]."+true_intro_no5+"]].parent_label+res.data[i].line_label"))+'</span>');
					dv_info.push(chgGradeImg5(res.data[i].grade_class));
					dv_info.push('<span>'+(eval("res.data[i]."+level_no5)-tmp_level1)+' generation</span>');
					if(_id('f_org_kind5').value == 1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+res.data[i].line_kind+'.gif></span>');
					}
					dv_info.push("<span id='show_"+(res.data[i].mb_no)+"'>");					
					dv_info.push("<span id=id onmouseover=hover_list1('"+(res.data[i].mb_no)+"') onmouseout=hover_list_out1('"+(res.data[i].mb_no)+"') >"+res.data[i].mb_no+"</span>");
					dv_info.push("<span id=name onmouseover=hover_list1('"+(res.data[i].mb_no)+"') onmouseout=hover_list_out1('"+(res.data[i].mb_no)+"')  >"+res.data[i].mb_name+"</span>");
					dv_info.push("</span>");
					
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
					
					orgData.index[res.data[i]['mb_no']]=orgData_count+i;
					res.data[i].parent_label=eval("orgData.data[orgData.index[res.data[i]."+true_intro_no5+"]].parent_label+res.data[i].parent_label");
					orgData.data.push(res.data[i]);
					
					//Right邊資料			
					objrTarget=_id('r_m005');
					dv=document.createElement('div');
					dv.id='rorg5|'+res.data[i].mb_no;	//Settingtr id為org5_+Member ID
					dv.setAttribute("className","right_orgseq_list5");
					dv.setAttribute("class","right_orgseq_list5");
					dv.title=res.data[i].mb_no;
					insertAfter(dv,objr);
					objr=dv;
					dv_info=new Array();
					//改成根據Column 抓資料
					var tmpObj1='<?php echo $gdata5?>';					
					give_tb1=tmpObj1.parseJSON();					
					gcount1=give_tb1.data.length;
					c1=0;					
					while(c1<gcount1){
						if(_id('f_org_kind5').value=='1'){
							var org_kind='intro_no';
						}else{
							var org_kind='true_intro_no';
						}
						if(give_tb1.data[c1].org_kind==org_kind){
							var fie=give_tb1.data[c1].enfield;
							dv_info.push("<div class='right_org_data' style='color:"+give_tb1.data[c1].color+"'>"+eval("res.data[i]."+fie)+'</div>');						
							
						}
						c1++;
					}
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";

					
					i++;
				}
			}
		}
	}
}
/*
 * query下線Member
 */
function subsearchOrg5(){
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	post_str='top_mb_no='+_id("f_mb_no5").value+'&orgseq=0&sub_mb_no='+arguments[0]+'&limit='+_id('level_limit5').value+'&org_kind='+_id('f_org_kind5').value+'&his='+_id('f_his').value;
	request.onreadystatechange=subsearchRes5;
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

/*
 * 處理滑鼠點擊事件
 */
function nodeEvent5(event){
	if(_id('changed5').value.length>0){
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
						if(tmp_obj.nextSibling.title <= (tarLevel_no1+Number(_id('level_limit5').value))){
							tmp_obj.nextSibling.style.display='';
							//Right邊區塊
							rtme="r"+tmp_obj.nextSibling.id;
							_id(rtme).style.display='';
							if(tmp_obj.nextSibling.title <(tarLevel_no1+Number(_id('level_limit5').value))){
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
					load_dv.id='load_div_info5';
					insertAfter(load_dv,target.parentNode.parentNode.parentNode);
				//	load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
					subsearchOrg5(target.parentNode.parentNode.parentNode.id.split('|')[1]);
				}
			}else{
				load_dv=document.createElement("div");
				load_dv.id='load_div_info5';
				insertAfter(load_dv,target.parentNode.parentNode.parentNode);
				//load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
				subsearchOrg5(target.parentNode.parentNode.parentNode.id.split('|')[1]);
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
				//Right邊區塊
				rtme="r"+tmp_obj.nextSibling.id;
				_id(rtme).style.display='none';
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

function chgGradeImg5(){
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
function chgNumToImg5(){
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

function chk_country5(){
	if(_id('f_mb_no5').value.length>0){
		post_str='chk_ct=1&ct_mb_no='+_id('f_mb_no5').value.trim();
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country_res5;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function chk_country_res5(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				alert('This account does not have permission to check this country member!');
			}else{
				toChgOrg5();
			}
		}
	}
}

function toChgOrg5(){
	_id('BLOCK_orgseq_body5').innerHTML='';
	_id('r_m005').innerHTML='<div id="orgseq_list6" ></div><div id="orgseq_list5" ></div>';

	_id('changed5').value='';
	if(_id('f_org_kind5').value==0){
		true_intro_no5='true_intro_no';
		level_no5='level_no1';
	}else{
		true_intro_no5='intro_no';
		level_no5='level_no';
	}
	if(_id('f_mb_no5').value.length>0){
		right_headsearchOrg5(_id('f_mb_no5').value.trim());
	}
}

attachEventListener(_id('f_chg_btn5'),'click',chk_country5,false);
attachEventListener(_id('f_org_kind5'),'change',function(){_id('changed5').value=1},false);
attachEventListener(_id('f_his'),'change',function(){_id('changed5').value=1},false);
function blur_mb_no5(){
	_id('f_mb_no5').defaultValue='- Member ID -';
	if(_id('f_mb_no5').value.length<1){
		_id('f_mb_no5').value.trim()=_id('f_mb_no5').defaultValue.trim();
		_id('f_mb_no5').style.color="#CCC";
		_id('f_mb_no5').style.paddingLeft="5px";
		_id('f_mb_no5').style.background="#FEFEFE";
	}
}
function focus_mb_no5(){
	if(_id('f_mb_no5').value.trim() == _id('f_mb_no5').defaultValue){
		_id('f_mb_no5').value='';
		_id('f_mb_no5').style.color="#333";
		_id('f_mb_no5').style.paddingLeft="0px";
		_id('f_mb_no5').style.background="#E5FFDD";
	}
}
attachEventListener(_id('f_mb_no5'),"click",focus_mb_no5,false);
attachEventListener(_id('f_mb_no5'),"blur",blur_mb_no5,false);


function scrollWin1() {       
    _id('r_m005').scrollTop	= _id('BLOCK_orgseq_body5').scrollTop;       
	//_id('r_m005').scrollLeft= _id('BLOCK_orgseq_body5').scrollLeft;       
} 
function scrollWin2() {       
    _id('BLOCK_orgseq_body5').scrollTop	= _id('r_m005').scrollTop;  
	 _id('r_m006').scrollLeft	= _id('r_m005').scrollLeft;
}  
function scrollWin3() {       
    _id('r_m005').scrollLeft	= _id('r_m006').scrollLeft;
}  

</script>

<script type='text/javascript'>
function ini_orgseq5(){
	document.getElementsByTagName('body')[0].id='block2';
	_id('add_btn_block').style.display='none';
	_id('save_btn_block').style.display='none';
	_id('modify_btn_block').style.display='none';
	_id('cancel_btn_block').style.display='none';
	_id('del_btn_block').style.display='none';
	_id('print_btn_block').style.display='none';
	_id('module_title').getElementsByTagName('span')[0].innerHTML="Members of the network";
}
_id('f_org_kind5').value='<?php echo isset($_GET['org_kind'])?$_GET['org_kind']:0 ?>';
_id('f_his').value='<?php echo isset($_GET['his'])?$_GET['his']:-1 ?>';
attachEventListener(_id('BLOCK_orgseq_body5'),'click',nodeEvent5,false);
attachEventListener(_id('f_mb_no5'),'blur',query_unit5,false);

attachEventListener(_id('BLOCK_orgseq_body5'),'scroll',scrollWin1,false);
attachEventListener(_id('r_m005'),'scroll',scrollWin2,false);
attachEventListener(_id('r_m006'),'scroll',scrollWin3,false);
blur_mb_no5();



attachEventListener(_id('f_mb_no5'),'dblclick',function(){outwork('f_mb_no5');},false);
function outwork(fun){
  	window.open("./module/qmbst/qmbst.php?fun="+fun,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
}

function hover_list1(who){  //add by Dale 20120207 滑鼠移上去要變色
	//alert(who);
	_id('show_'+who).style.backgroundColor='#fcf080';
	//_id('rem_mb_no').value=mb_no;
	_id('rorg5|'+who).style.backgroundColor='#fcf080';
}
function hover_list_out1(who){ //add by Dale 20120207  滑鼠移開變回白色
	//alert('test');
	_id('show_'+who).style.backgroundColor='#FFFFFF';
	_id('rorg5|'+who).style.backgroundColor='#FFFFFF';
	//_id('rem_mb_no').value=mb_no;
}
</script>