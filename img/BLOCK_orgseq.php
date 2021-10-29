<?php
	$grade_query="SELECT * FROM org_data as a , org_data_demo as b where a.yn ='Y'  and a.enfield = b.enfield and a.org_kind= b.org_kind  order by b.sort  ";
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
	
	
	$mb_query="select mb_no from mbst order by pg_yymm,mb_no limit 1";
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
<div id='BLOCK_orgseq'>
	<div class='filter_div'>
		Expand generation<select id='level_limit' class='limit'>
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

		<?php
			$query1="SELECT * FROM grade";
			$res1=$db->query($query1);
			while($data1=$res1->fetch()){
				echo "<img src='module/orgseq/grade/".$data1['img']."' width='12px'>".$data1['name']."　";
			}
		?>
	</div>
	<div class='org_kind_div'>
		<input type='text' id='mb_no' onblur='UpperStr(this)'>
		<select name='org_kind' id='org_kind'>
			<option value=0>Sponsor chart</option>
			<option value=1>Placement chart</option>
		</select>
		<select name='his' id='his'>
			<option value=-1>Real Time</option>
		</select>
		<input type='hidden' id='changed'>
		<input type='button' value='Enquiry' id='chg_btn'>
		<input type='button' value='export network chart' id='exp_orgseq_btn' onclick=exp_orgseq();>
		<font color = '#696969'>Ex : <?php echo $mb_no1;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
		<!--
		<select id='year'></select>
		<select id='chart_kind'>
			<option value='per_m'>Annual sales</option>
		</select>
		<input type='button' value='Show graphs' id='show_chart'>-->
	</div>
	<div style="overflow:auto;height:550px;width:100%;">
		<div id='BLOCK_orgseq_body' class='org_tree_div'></div>
	</div>
</div>

<script type='text/javascript'>
var true_intro_no='true_intro_no';
var level_no='level_no1';
loader=new Image();
loader.src="images/org_loader.gif";
loader.title="Reading...";
/* export network chart */
function exp_orgseq(){
	if(_id('mb_no').value.length>0){	
		location.href='module/orgseq/orgData.php?org='+_id('org_kind').value+'&mb_no='+_id('mb_no').value+'&his='+_id('his').value;		
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
function query_unit_res(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				_id("mb_no").value='';
				alert("Member ID not found!");
			}else{
				unit_data=request.responseText.parseJSON();
				w=1;
				if(unit_data.length>0){
					leng=_id('his').options.length-1;
					while(leng>0){
						_id('his').remove(leng);
						leng--;
					}
					leng=unit_data.length;
					while(w<=leng){
						_id('his').options[w]=new Option(unit_data[w-1],unit_data[w-1]);
						w++;
					}
				}
			}
		}
	}
}

function query_unit(){
	var mb_no=_id("mb_no").value;
	if(mb_no.length>0 && mb_no!=_id('mb_no').defaultValue){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='query_unit=1&mb_no='+mb_no;
		request.onreadystatechange=query_unit_res;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

var orgData;
var tmp_level1;
function headsearchRes(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				orgData=request.responseText.parseJSON();
				if((orgData.data.length==1)&&(orgData.data[0].line_label.length==1)){
					obj=_id('BLOCK_orgseq_body');
					//移除first ul下的所yes節點
					while(obj.childNodes.length>0){
						obj.removeChild(obj.lastChild);
					}
					dv=document.createElement("div");
					dv.id='org0|'+orgData.data[0].mb_no;
					dv.title=eval("orgData.data[0]."+level_no);
					obj.appendChild(dv);
					dv_info=new Array();
					dv_info.push('<span class=line_flag>'+chgNumToImg(orgData.data[0].line_label)+chgGradeImg(orgData.data[0].grade_class)+'</span>');
					dv_info.push('<span>0 generation</span>');
					tmp_level1=eval("orgData.data[0]."+level_no);
					if(_id('org_kind').value==1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+orgData.data[0].line_kind+'.gif></span>');
					}
					dv_info.push('<span id=id>'+orgData.data[0].mb_no+'</span>');
					dv_info.push('<span class=name>'+orgData.data[0].mb_name+'</span>');
					/*dv_info.push('<span class=per_m>'+orgData.data[0].per_m+'</span>');
					dv_info.push('<span class=grade>'+orgData.data[0].grade_name+'</span>');
					dv_info.push('<span class=pg_date>Date Join：'+orgData.data[0].pg_date+'</span>');*/
					
					/* HistoryNetwork chart 
					w=1;
					if(orgData.his.length>0){
						leng=_id('his').options.length-1;
						while(leng>0){
							_id('his').remove(leng);
							leng--;
						}
						leng=orgData.his.length;
						while(w<=leng){
							_id('his').options[w]=new Option(orgData.his[w-1],orgData.his[w-1]);
							w++;
						}
					}
					*/
					
					//改成根據Column 抓資料
					var tmpObj2='<?php echo $gdata?>';
					
					give_tb2=tmpObj2.parseJSON();
					
					gcount2=give_tb2.data.length;
					c2=0;
					
					while(c2<gcount2){
						if(_id('org_kind').value=='1'){
							var org_kind='intro_no';
						}else{
							var org_kind='true_intro_no';
						}
						if(give_tb2.data[c2].org_kind==org_kind){
							var fie=give_tb2.data[c2].enfield;
							//alert(fie) ;
							//dv_info.push("<span class='"+fie+"' style='color:"+give_tb2.data[c2].color+"'>"+give_tb2.data[c2].chfield+':'+eval("res.data[i]."+fie)+'</span>');
							dv_info.push("<span class='"+fie+"' style='color:"+give_tb2.data[c2].color+"'>"+give_tb2.data[c2].chfield+':'+eval("orgData.data[0]."+fie)+'</span>');
						}
						c2++;
					}
	
					dv.innerHTML="<nobr>"+dv_info.join('')+"</nobr>";
				}
			}
		}
	}
}
function headsearchOrg(){
	var mb_no=arguments[0];
	if(mb_no.length>0){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='orgseq=0&mb_no='+mb_no+'&org_kind='+_id('org_kind').value+'&his='+_id('his').value;
		request.onreadystatechange=headsearchRes;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function subsearchRes(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
			}else{
				res=request.responseText.parseJSON();
				res_true_intro_no=res.true_intro_no;
				res_count=res.data.length;
				orgData_count=orgData.data.length;
				var i=0;
				var obj=document.getElementById('org0|'+res.true_intro_no);
				_id('load_div').parentNode.removeChild(_id('load_div'));
				while(i<res_count){
					objTarget=_id('BLOCK_orgseq_body');
					dv=document.createElement('div');
					dv.id='org0|'+res.data[i].mb_no;	//Settingtr id為org0_+Member ID
					dv.title=(eval("res.data[i]."+level_no));
					insertAfter(dv,obj);
					obj=dv;
					dv_info=new Array();
					dv_info.push('<span class=line_flag>'+chgNumToImg(eval("orgData.data[orgData.index[res.data[i]."+true_intro_no+"]].parent_label+res.data[i].line_label"))+'</span>');
					dv_info.push(chgGradeImg(res.data[i].grade_class));
					dv_info.push('<span>'+(eval("res.data[i]."+level_no)-tmp_level1)+' generation</span>');
					if(_id('org_kind').value == 1){
						dv_info.push('<span class=line_img><img src=module/orgseq/line_img/gr'+res.data[i].line_kind+'.gif></span>');
					}
					dv_info.push('<span id=id>'+res.data[i].mb_no+'</span>');
					dv_info.push('<span>'+res.data[i].mb_name+'</span>');
					var tmpObj1='<?php echo $gdata?>';
					
					give_tb1=tmpObj1.parseJSON();
					
					gcount1=give_tb1.data.length;
					c1=0;
					
					while(c1<gcount1){
						if(_id('org_kind').value=='1'){
							var org_kind='intro_no';
						}else{
							var org_kind='true_intro_no';
						}
						if(give_tb1.data[c1].org_kind==org_kind){
							var fie=give_tb1.data[c1].enfield;
							dv_info.push("<span class='"+fie+"' style='color:"+give_tb1.data[c1].color+"'>"+give_tb1.data[c1].chfield+':'+eval("res.data[i]."+fie)+'</span>');
						}
						//alert(fie) ;
						c1++;
					}
	
					
					
					
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
function subsearchOrg(){
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	post_str='orgseq=0&sub_mb_no='+arguments[0]+'&limit='+_id('level_limit').value+'&org_kind='+_id('org_kind').value+'&his='+_id('his').value;
	request.onreadystatechange=subsearchRes;
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

/*
 * 處理滑鼠點擊事件
 */
function nodeEvent(event){
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
						if(tmp_obj.nextSibling.title <= (tarLevel_no1+Number(_id('level_limit').value))){
							tmp_obj.nextSibling.style.display='';
							if(tmp_obj.nextSibling.title <(tarLevel_no1+Number(_id('level_limit').value))){
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
					load_dv.id='load_div';
					insertAfter(load_dv,target.parentNode.parentNode.parentNode);
					load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
					subsearchOrg(target.parentNode.parentNode.parentNode.id.split('|')[1]);
				}
			}else{
				load_dv=document.createElement("div");
				load_dv.id='load_div';
				insertAfter(load_dv,target.parentNode.parentNode.parentNode);
				load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
				subsearchOrg(target.parentNode.parentNode.parentNode.id.split('|')[1]);
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
function chgGradeImg(){
	switch(arguments[0]){
		<?php
			$grade_query="SELECT no,name,img FROM grade where no > 0 ORDER BY no";
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
function chgNumToImg(){
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
function toChgOrg(){
	_id('BLOCK_orgseq_body').innerHTML='';
	_id('changed').value='';
	if(_id('org_kind').value==0){
		var true_intro_no='true_intro_no';
		var level_no='level_no1';
	}else{
		var true_intro_no='intro_no';
		var level_no='level_no';
	}
	if(_id('mb_no').value.length>0){
		headsearchOrg(_id('mb_no').value);
	}
	
}
function toChgChart(){
	if((_id('mb_no').value=='- Member ID -') || (_id('mb_no').value.length<1)){
		alert("Member ID not fiiled!");
	}else{
		mb=_id('mb_no').value;
		chart=_id('chart_kind').value;
		year=_id('year').value;
		window.open("./module/qmbst/chart.php?mb="+mb+"&chart="+chart+"&year="+year,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
	}
}
attachEventListener(_id('chg_btn'),'click',toChgOrg,false);
//attachEventListener(_id('show_chart'),'click',toChgChart,false);
attachEventListener(_id('org_kind'),'change',function(){_id('changed').value=1},false);
attachEventListener(_id('his'),'change',function(){_id('changed').value=1},false);
function blur_mb_no(){
	_id('mb_no').defaultValue='- Member ID -';
	if(_id('mb_no').value.length<1){
		_id('mb_no').value=_id('mb_no').defaultValue;
		_id('mb_no').style.color="#CCC";
		_id('mb_no').style.paddingLeft="5px";
		_id('mb_no').style.background="#FEFEFE";
	}
}
function focus_mb_no(){
	if(_id('mb_no').value == _id('mb_no').defaultValue){
		_id('mb_no').value='';
		_id('mb_no').style.color="#333";
		_id('mb_no').style.paddingLeft="0px";
		_id('mb_no').style.background="#E5FFDD";
	}
}
function chk_country2(){
	mb_no = _id('mb_no').value;
	if((mb_no!='- Member ID -')&&(mb_no.length>0)){
		post_str='chk_ct=1&ct_mb_no='+mb_no;
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country_res2;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function chk_country_res2(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				alert("This account does not have permission to check this country member!");
				_id('mb_no').value='';
			}else if(request.responseText==0){		
				alert("Member not found!");
				_id('mb_no').value='';
			}
		}
	}
}
attachEventListener(_id('mb_no'),"click",focus_mb_no,false);
attachEventListener(_id('mb_no'),"blur",blur_mb_no,false);

</script>

<script type='text/javascript'>
function ini_orgseq(){
	document.getElementsByTagName('body')[0].id='block2';
	_id('add_btn_block').style.display='none';
	_id('save_btn_block').style.display='none';
	_id('modify_btn_block').style.display='none';
	_id('cancel_btn_block').style.display='none';
	_id('del_btn_block').style.display='none';
	_id('print_btn_block').style.display='none';
	_id('module_title').getElementsByTagName('span')[0].innerHTML="Member network Chart";
}
// yymm=<?php echo $yy?>;
// ycount=yymm.ydata.length;
// a=0;	
// while(a<ycount){		
	// _id('year').options[a]=new Option(yymm.ydata[a].yy,yymm.ydata[a].yy);		
	// a++;
// }
_id('org_kind').value='<?php echo isset($_GET['org_kind'])?$_GET['org_kind']:0 ?>';
_id('his').value='<?php echo isset($_GET['his'])?$_GET['his']:-1 ?>';
attachEventListener(_id('BLOCK_orgseq_body'),'click',nodeEvent,false);

attachEventListener(_id('mb_no'),'blur',query_unit,false);
attachEventListener(_id('mb_no'),"blur",chk_country2,false);
blur_mb_no();
//headsearchOrg('<?php echo $_SESSION['mb']['mb_no']?>');
attachEventListener(_id('mb_no'),'dblclick',function(){outwork('mb_no');},false);
function outwork(fun){
  	window.open("./module/qmbst/qmbst.php?fun="+fun,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
}
</script>
