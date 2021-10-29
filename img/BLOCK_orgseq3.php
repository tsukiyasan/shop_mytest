<?php
/*
 * Sponsor樹狀Network chart
*/
?>
<style type='text/css'>
	@import "<?php echo $conf['web']['base_url']?>/module/orgseq/CSS/orgseq.css";
</style>
<div id='BLOCK_orgseq3'>
	<div class='filter_div'>
		<?php
			$query1="SELECT no,en_name name,img FROM grade  order by abs(no)";
			$res1=$db->query($query1);
			while($data1=$res1->fetch()){
				echo "<img src='module/orgseq/grade/".$data1['img']."' >".$data1['name']."　";
			}
			$first="select mb_no from mbst where mb_no=true_intro_no ";
			$first_res=$db->query($first);
			$first_data=$first_res->fetch();
		?>
	</div>
	<div>
		<span id='org1'>Member ID：</span><input type='text' name='mb_no3' id='mb_no3' value='- Member ID -' onblur='UpperStr(this)'>
		<select name='f_his6' id='f_his6'>
			<option value=-1>Real Time</option>
		</select>
		<input type='button' value='Enquiry' id='search_mb_org6'>
		
		<a href='javascript:void(0)' onclick="queryOrgseq3(nowmb,'prev')" style='margin-left:15px'><img src='module/orgseq/img/back.gif' style="border:none;display:inline;"></a>  
		<a href='javascript:void(0)' onclick="queryOrgseq3(nowmb,'head')"><img src='module/orgseq/img/top.gif' style="border:none;display:inline;"></a>
	</div>
	<div style="overflow:auto; width:1200px; height:450px;">
	<!-- Produce空table來存放資料 -->
	<table id='first_tb3' ></table>
	</div>
</div>

<script type='text/javascript'>
var nowmb;
function chgMb(event){
	toShowTip=0;
	if(typeof event=="undefined"){
		event=window.event;
	}
	target=getEventTarget(event);
	if(target.tagName.toLowerCase()=="span" || target.tagName.toLowerCase()=="img"){
		while(target.className == null || !/(^| )mb_block( |$)/.test(target.className)){
			target = target.parentNode;
		}
		var mb_info=target.id.split("||");
		if(mb_info[1]!=_id("mb_no3").value.trim()){
			queryOrgseq3(mb_info[0]);
		}else{
			toShowTip=1;
		}
	}else{
		toShowTip=1;
	}
}

function orgseq3Res(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				msg_block.show_err('Member ID not found');
			}else{
				var isIE = navigator.userAgent.search("MSIE") > -1;
				var isIE6 = navigator.userAgent.search("MSIE 6") > -1; 
				var isIE7 = navigator.userAgent.search("MSIE 7") > -1; 
				var isFirefox = navigator.userAgent.search("Firefox") > -1;
				
				var obj=_id('first_tb3');
				var tips=document.getElementsByTagName('div');
				var tips_leng=tips.length;
				var tl=0;
				var shd=0;
				while(tl<tips_leng){
					if(typeof tips[tl] != 'undefined'){
						if(tips[tl].className == 'tooltip'){
							tips[tl].parentNode.removeChild(tips[tl]);
							shd=1;
						}
					}
					tl++;
				}
				if(shd==1){
					_id('sha').parentNode.removeChild(_id('sha'));
				}
				orgseq=request.responseText.parseJSON();
				if(obj.rows.length>1){
					i=(obj.rows.length-1);
					while(i>=0){
						obj.deleteRow(i);
						i--;
					}
				}
				var cellWidth=80; // 每個一格要多寬
				var i = orgseq.length;
				while(i > 0){	//階層
					if(i == orgseq.length){
						cels = 1;
					}else{
						cels = orgseq[i-1].length;	//cell數
					}
								
					nr=obj.insertRow(-1);
					var c=0;
					while(c < cels){
						c_td = 1;
						while(c_td <= orgseq[(i-1)][c].count_mb){
							nc = nr.insertCell(-1);
							nc.style.width=cellWidth+'px';
							nc.width=cellWidth;
							c_td++;
						}
						
						nc=nr.insertCell(-1);
						if((orgseq[(i-1)][c].count_mb_col) >= 1){
							nc.colSpan = orgseq[(i-1)][c].count_mb_col;
							nc.style.width= (orgseq[(i-1)][c].count_mb_col * cellWidth)+'px';
							nc.width= (orgseq[(i-1)][c].count_mb_col * cellWidth);
						}else{
							nc.style.width=cellWidth+'px';
							nc.width=cellWidth;
						}											
						if(orgseq[(i-1)][c].mb_no != "NONE"){
							var mb_block=document.createElement("span");
							// if(i==4){
							if(i == orgseq.length){
								mb_block.setAttribute("id",orgseq[(i-1)][c].true_intro_no+"||"+orgseq[(i-1)][c].mb_no);
								nowmb=orgseq[(i-1)][c].mb_no;
							}else{
								mb_block.setAttribute("id",orgseq[(i-1)][c].mb_no+"||"+orgseq[(i-1)][c].mb_no);
							}
							
							mb_block.setAttribute("className","mb_block");
							mb_block.setAttribute("class","mb_block");
							var toolTip=document.createElement("span");
							toolTip.setAttribute("className","hastooltip");
							toolTip.setAttribute("class","hastooltip");
							info=new Array();
							info.push("<table class='org_mb_info'>");
							info.push("<tr><th><nobr>Member ID：</nobr></th><td><nobr>"+orgseq[(i-1)][c].mb_no+"</nobr></td></tr>");
							info.push("<tr><th><nobr>Member Name：</nobr></th><td><nobr>"+orgseq[(i-1)][c].mb_name+"</nobr></td></tr>");
							info.push("<tr><th><nobr>Ranking：</nobr></th><td><nobr>"+chgGradeName3(orgseq[(i-1)][c].grade_class)+"</nobr></td></tr>");
							// info.push("<tsr><th><nobr>Date Join：</nobr></th><td><nobr>"+orgseq[(i-1)][c].pg_date+"</nobr></td></tr>");
							// info.push("<tr><th><nobr>Date：</nobr></th><td><nobr>"+orgseq[(i-1)][c].yymm+"</nobr></td></tr>");
							<?php
								$query1="SELECT * FROM org_data WHERE org_kind='true_intro_no' and yn='Y' order by abs(sort),no";
								$res1=$db->query($query1);
								while($data1=$res1->fetch()){
									echo "info.push('<tr><th><nobr>".$data1['chfield_en']."：</nobr></th><td><nobr>'+orgseq[(i-1)][c].".$data1['enfield']."+'</nobr></td></tr>');";
								}
							?>
							info.push("</table>");
							title_str=info.join("");
							toolTip.setAttribute("title",title_str);
							if(i==1){
								toolTip.innerHTML=chgGradeImg3(orgseq[(i-1)][c].grade_class);	//最Back一排不顯示資訊指顯示Ranking的Pax頭
							}else{
								toolTip.innerHTML=chgGradeImg3(orgseq[(i-1)][c].grade_class);
								//toolTip.innerHTML+=orgseq[(i-1)][c].mb_name;
							}
							toolTip.innerHTML+="<br>"+orgseq[(i-1)][c].mb_no;
							toolTip.innerHTML+="<br>"+orgseq[(i-1)][c].mb_name2;
							mb_block.appendChild(toolTip);
							nc.appendChild(mb_block);
							
							c_td_r = 1;
							while(c_td_r <= orgseq[(i-1)][c].count_mb_r){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								c_td_r++;
							}
						}else{
							nc.innerHTML=" ";
						}
						
						c++;
					}
					
					if(i == 1){
						nr=obj.insertRow(-1);
						var c=0;
						while(c<cels){
							c_td = 1;
							while(c_td <= orgseq[(i-1)][c].count_mb){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td++;
							}
							//20110307 joe
							var arrow_block=document.createElement("span");
							// if(i==4){
							if(i == orgseq.length){
								arrow_block.setAttribute("id",orgseq[(i-1)][c].true_intro_no+"||"+orgseq[(i-1)][c].mb_no);
								nowmb=orgseq[(i-1)][c].mb_no;
							}else{
								arrow_block.setAttribute("id",orgseq[(i-1)][c].mb_no+"||"+orgseq[(i-1)][c].mb_no);
							}
							arrow_block.setAttribute("className","mb_block");
							arrow_block.setAttribute("class","mb_block");
							
							nc=nr.insertCell(-1);
							if((orgseq[(i-1)][c].count_mb_col) >= 1){
								arrow_block.colSpan = orgseq[(i-1)][c].count_mb_col;
								arrow_block.style.width= (orgseq[(i-1)][c].count_mb_col * cellWidth)+'px';
								arrow_block.width= (orgseq[(i-1)][c].count_mb_col * cellWidth);
							}else{
								arrow_block.style.width=cellWidth+'px';
								arrow_block.width=cellWidth;
							}
							
							if((i == 1) && (orgseq[(i-1)][c].down_line != 0)){
								//nc.innerHTML="<img src='module/orgseq/img/5.gif'>";
								arrow_block.innerHTML="<img src='module/orgseq/img/temp2.gif' width='30' height='16'>";
							}else{
								arrow_block.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
							}
							nc.appendChild(arrow_block);
							//end--------------------------joe
							c_td_r = 1;
							while(c_td_r <= orgseq[(i-1)][c].count_mb_r){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td_r++;
							}
							
							c++;
						}
					}else{
						nr=obj.insertRow(-1);
						var c=0;
						while(c<cels){
							c_td = 1;
							while(c_td <= orgseq[(i-1)][c].count_mb){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td++;
							}
							
							nc=nr.insertCell(-1);
							if((orgseq[(i-1)][c].count_mb_col) >= 1){
								nc.colSpan = orgseq[(i-1)][c].count_mb_col;
								nc.style.width= (orgseq[(i-1)][c].count_mb_col * cellWidth)+'px';
								nc.width= (orgseq[(i-1)][c].count_mb_col * cellWidth);
							}else{
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
							}
							
							if(orgseq[(i-1)][c].down_line != 0){
								nc.innerHTML="│";			
							}else{
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
							}
																			
							c_td_r = 1;
							while(c_td_r <= orgseq[(i-1)][c].count_mb_r){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td_r++;
							}
							
							c++;
						}
						
						nr=obj.insertRow(-1);
						var c=0;
						while(c < orgseq[i-2].length){
							c_td = 1;
							while(c_td <= orgseq[(i-2)][c].count_mb){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td++;
							}
							
							nc=nr.insertCell(-1);
							if((orgseq[(i-2)][c].count_mb_col) >= 1){
								nc.colSpan = orgseq[(i-2)][c].count_mb_col;
								nc.style.width= (orgseq[(i-2)][c].count_mb_col * cellWidth)+'px';
								nc.width= (orgseq[(i-2)][c].count_mb_col * cellWidth);
							}else{
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
							}
														
							if(orgseq[(i-2)][c].down_count == 2){
								/*
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10)-5;
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 5);
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10);
									}
									if(chk_line > 0){
										var p = 0;
										while(p < chk_line){
											count_line = count_line + '-'; 
											p++;
										}
									}
								}
								nc.innerHTML="<div align='right' width='100%'>┌-----"+count_line+"</div>";
								*/
								/* by 20150206
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (((Math.floor(orgseq[(i-2)][c].count_mb_col / 2))-1)*30)+15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";							
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*30;
										count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
									}
								}
								nc.innerHTML="<div align='right' width='100%'><img src='module/orgseq/img/L_line.jpg' width='30' height='16'>"+count_line+"</div>";*/
								
								var count_L_line = '';
								var count_R_line = '';
								var wid=orgseq[(i-2)][c].count_mb_col*cellWidth/2-15;
								count_L_line = "<img src='module/orgseq/img/temp.gif' style='width:"+(wid)+"px;' height='16'>";
								count_R_line = "<img src='module/orgseq/img/H_line.jpg' style='width:"+(wid)+"px;' height='16'>";
								nc.innerHTML="<div align='right' width='100%'>"+count_L_line+"<img src='module/orgseq/img/L_line.jpg' style='width:30px;' height='16'>"+count_R_line+"</div>";
							}else if(orgseq[(i-2)][c].down_count == 3){
								/*
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10)-5;
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 5);
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10);
									}
									if(chk_line > 0){
										var p = 0;
										while(p < chk_line){
											count_line = count_line + '-'; 
											p++;
										}
									}
								}
								nc.innerHTML="<div align='left' width='100%'>"+count_line+"-----┐</div>";
								*/
								/* by 20150206
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (((Math.floor(orgseq[(i-2)][c].count_mb_col / 2))-1)*30)+15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";							
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*30;
										count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
									}
								}
								nc.innerHTML="<div align='left' width='100%'>"+count_line+"<img src='module/orgseq/img/R_line.jpg' width='30' height='16'></div>";*/
								var count_L_line = '';
								var count_R_line = '';
								var wid=orgseq[(i-2)][c].count_mb_col*cellWidth/2-15;
								count_L_line = "<img src='module/orgseq/img/H_line.jpg' style='width:"+wid+"px;' height='16'>";
								count_R_line = "<img src='module/orgseq/img/temp.gif' style='width:"+wid+"px;' height='16'>";
								nc.innerHTML="<div align='left' width='100%'>"+count_L_line+"<img src='module/orgseq/img/R_line.jpg' style='width:30px;' height='16'>"+count_R_line+"</div>";
							}else if(orgseq[(i-2)][c].down_count == 4){
								/*
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10)-5;
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 5);
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2) * 10);
									}
									if(chk_line > 0){
										var p = 0;
										while(p < chk_line){
											count_line = count_line + '-';
											p++;
										}
									}
								}
								nc.innerHTML="<div align='center' width='100%'>"+count_line+"----┬----"+count_line+"</div>";
								if(isIE){
									nc.innerHTML="<div align='center' width='100%'>"+count_line+"----┬----"+count_line+"</div>";
								}
								if(isIE6){
									nc.innerHTML="<div align='center' width='100%'>"+count_line+"---┬---"+count_line+"</div>";
								}
								*/
								/* by 20150206
								var count_line = '';
								if(orgseq[(i-2)][c].count_mb_col > 1){
									var cal = orgseq[(i-2)][c].count_mb_col % 2;
									if(cal == 0){
										if(orgseq[(i-2)][c].count_mb_col > 2){
											var chk_line = (((Math.floor(orgseq[(i-2)][c].count_mb_col / 2))-1)*30)+15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";							
										}else{
											var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*15;
											count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
										}
									}else{
										var chk_line = (Math.floor(orgseq[(i-2)][c].count_mb_col / 2))*30;
										count_line = "<img src='module/orgseq/img/H_line.jpg' width='"+chk_line+"' height='16'>";
									}
								}
								nc.innerHTML="<div align='center' width='100%'>"+count_line+"<img src='module/orgseq/img/C_line.jpg' width='30' height='16'>"+count_line+"</div>";*/
								var count_L_line = '';
								var count_R_line = '';
								var wid=orgseq[(i-2)][c].count_mb_col*cellWidth/2-15;
								count_L_line = "<img src='module/orgseq/img/H_line.jpg' style='width:"+wid+"px;' height='16'>";
								count_R_line = "<img src='module/orgseq/img/H_line.jpg' style='width:"+wid+"px;' height='16'>";
								nc.innerHTML="<div align='center' width='100%'>"+count_L_line+"<img src='module/orgseq/img/C_line.jpg' style='width:30px;' height='16'>"+count_R_line+"</div>";
							}else{
								nc.innerHTML="<div align='center' width='100%'>│</div>";
							}

							c_td_r = 1;
							while(c_td_r <= orgseq[(i-2)][c].count_mb_r){
								nc = nr.insertCell(-1);
								nc.style.width=cellWidth+'px';
								nc.width=cellWidth;
								nc.innerHTML="<img src='module/orgseq/img/temp.gif' width='30' height='16'>";
								c_td_r++;
							}
							
							c++;
						}
					}
					
					i--;
				}
				msg_block.show_info("Data processing is completed");
				initTooltips();
			}
		}
	}
}
function queryOrgseq3(){
	msg_block.show_info("Data is processing...");
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	post_str='orgseq=99&org_kind=0&mb_no='+arguments[0]+'&his='+_id('f_his6').value+'&true_mb_no='+_id('mb_no3').value.trim();
	if(typeof arguments[1] !='undefined'){
		if(arguments[1]=='head'){
			post_str+="&prevP=h";
		}else{
			post_str+="&prevP=1";
		}
	}
	request.onreadystatechange=orgseq3Res;
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

function chk_country33(){
	//msg_block.clear();
	if(_id('mb_no3').value.length>0){
		post_str='chk_ct=1&ct_mb_no='+_id('mb_no3').value.trim();
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country3_res;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}
function chk_country3_res(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				//msg_block.show_err("This account does not have permission to check this country member!");
				alert("This account does not have permission to check this country member!");
				_id('mb_no3').value='';
			}else if(request.responseText==0){		
				alert("Member not found!");
				_id('mb_no3').value='';
			}
		}
	}
}

<?php
	$grade_query="SELECT no,name FROM grade ORDER BY no";
	$grade_res=$db->query($grade_query);
?>
function chgGradeName3(){
	switch(arguments[0]){
		<?php
			while($grade=$grade_res->fetch()){
				echo "case '".$grade['no']."':";
				echo "return '".$grade['name']."';";
				echo "break;";
			}
		?>
	}
}
function chgGradeImg3(){
	switch(arguments[0]){
		<?php
			$grade_query="SELECT no,name,img FROM grade ORDER BY abs(no)";
			$grade_res=$db->query($grade_query);
			while($grade=$grade_res->fetch()){
				echo "case '".$grade['no']."':";
				echo "return \"<img src='module/orgseq/grade/".$grade['img']."'>\";";
				echo "break;";
			}
		?>
	}
}

function ini_orgseq3(){
	msg_block.clear();
	document.getElementsByTagName('body')[0].id='block3';
	_id('add_btn_block').style.display='none';
	_id('save_btn_block').style.display='none';
	_id('modify_btn_block').style.display='none';
	_id('cancel_btn_block').style.display='none';
	_id('del_btn_block').style.display='none';
	_id('print_btn_block').style.display='none';
	_id('module_title').getElementsByTagName('span')[0].innerHTML="Upright sponsor chart";
	
}
function query_unit3_1(){
	var mb_no=_id("mb_no3").value.trim();
	if(mb_no.length>0  && _id('mb_no3').value!='- Member ID -'){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='query_unit=1&mb_no='+mb_no;
		request.onreadystatechange=query_unit_res3_1;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}
function query_unit_res3_1(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				_id("mb_no3").value='';
				alert("Member ID not found!");
			}else{
				unit_data=request.responseText.parseJSON();
				w=1;
				if(unit_data.length>0){
					leng=_id('f_his6').options.length-1;
					while(leng>0){
						_id('f_his6').remove(leng);
						leng--;
					}
					leng=unit_data.length;
					while(w<=leng){
						_id('f_his6').options[w]=new Option(unit_data[w-1],unit_data[w-1]);
						w++;
					}
				}
				chk_country33();
			}
		}
	}
}
attachEventListener(_id('first_tb3'),'dblclick',chgMb,false);
attachEventListener(_id('search_mb_org6'),"click",function(){queryOrgseq3(_id("mb_no3").value.trim());},false);
attachEventListener(_id('mb_no3'),'dblclick',function(){outwork3('mb_no3');},false);
//attachEventListener(_id('mb_no3'),'blur',function(){chk_country33();},false);
attachEventListener(_id('mb_no3'),'blur',function(){query_unit3_1();},false);
function outwork3(fun){
  	window.open("./module/qmbst/qmbst.php?fun="+fun,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
}
function blur_mb_no3_1(){
	_id('mb_no3').defaultValue='- Member ID -';
	if(_id('mb_no3').value.length<1){
		_id('mb_no3').value=_id('mb_no2').defaultValue;
		_id('mb_no3').style.color="#CCC";
		_id('mb_no3').style.paddingLeft="5px";
		_id('mb_no3').style.background="#FEFEFE";
	}
}
function focus_mb_no3_1(){
	if(_id('mb_no3').value == _id('mb_no2').defaultValue){
		_id('mb_no3').value='';
		_id('mb_no3').style.color="#333";
		_id('mb_no3').style.paddingLeft="0px";
		_id('mb_no3').style.background="#E5FFDD";
	}
}
attachEventListener(_id('mb_no3'),"click",focus_mb_no3_1,false);
attachEventListener(_id('mb_no3'),"blur",blur_mb_no3_1,false);

</script>