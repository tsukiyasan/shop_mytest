<?php
/*
 * Place雙軌樹狀Network chart
*/
?>
<style type='text/css'>
	@import "<?php echo $conf['web']['base_url']?>/module/orgseq/CSS/orgseq.css";
</style>
<div id='BLOCK_orgseq2'>
	<div class='filter_div'>
		<?php
			$query1="SELECT * FROM grade order by abs(no)  ";
			$res1=$db->query($query1);
			while($data1=$res1->fetch()){
				echo "<img src='module/orgseq/grade/".$data1['img']."'>".$data1['name']."　";
			}
			$first="select mb_no from mbst where mb_no=true_intro_no ";
			$first_res=$db->query($first);
			$first_data=$first_res->fetch();
		?>
	</div>
	<div>
		<span id='org1'>Member ID：</span><input type='text' name='mb_no2' id='mb_no2' value='- Member ID -' onblur='UpperStr(this)'>
		<select name='f_his2' id='f_his2'>
			<option value=-1>Real Time</option>
		</select>
		<input type='button' value='Enquiry' id='search_mb_org5'>
		
		<a href='javascript:void(0)' onclick="queryOrgseq(nowmb,'prev')" style='margin-left:15px'><img src='module/orgseq/img/back.gif' style="border:none;display:inline;"></a>  
		<a href='javascript:void(0)' onclick="queryOrgseq(nowmb,'head')"><img src='module/orgseq/img/top.gif' style="border:none;display:inline;"></a>
	</div>
	<!-- Produce空table來存放資料 -->
	<table id='first_tb2'></table>
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
		// _id("mb_no2").value.trim()=target.id;
		// queryOrgseq(mb_info[0]);
		var mb_info=target.id.split("||");
		if(mb_info[1]!=_id("mb_no2").value.trim()){
			queryOrgseq(mb_info[0]);
		}else{
			toShowTip=1;
		}
	}else{
		toShowTip=1;
	}
	
}

function orgseqRes(){
	if(request.readyState==4){
		if(request.status==200){
			
			if(request.responseText=='none'){
				msg_block.show_err('Member ID not found');
			}else{
				var obj=_id('first_tb2');
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
				var i=1;
				while(i <= orgseq.length){	//階層
					cels=Math.pow(2,(i-1));	//cell數
					colspan=(Math.pow(2,orgseq.length)/cels)/2;	//須合併cell數
					nr=obj.insertRow(-1);
					var c=0;
					while(c<cels){	
						nc=nr.insertCell(-1);
						if(colspan!=1){
							nc.colSpan=colspan;
						}
						nc.style.width=(100/cels)+"%";
						
						if(orgseq[(i-1)][c].mb_no != "NONE"){
							var mb_block=document.createElement("span");
							if(i==1){
								mb_block.setAttribute("id",orgseq[(i-1)][c].intro_no+"||"+orgseq[(i-1)][c].mb_no);
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
							info.push("<tr><th><nobr>Ranking：</nobr></th><td><nobr>"+chgGradeName(orgseq[(i-1)][c].grade_class)+"</nobr></td></tr>");
							// info.push("<tr><th><nobr>本期New：</nobr></th><td><nobr>Left"+orgseq[(i-1)][c].a_line_subs_new+";Right"+orgseq[(i-1)][c].b_line_subs_new+"</nobr></td></tr>");
							// info.push("<tr><th><nobr>業績累計：</nobr></th><td><nobr>Left"+orgseq[(i-1)][c].a_line_sum+";Right"+orgseq[(i-1)][c].b_line_sum+"</nobr></td></tr>");
							info.push("<tr><th><nobr>Join cycle：</nobr></th><td><nobr>"+orgseq[(i-1)][c].pg_week_no+"</nobr></td></tr>");
							var yymm=(orgseq[(i-1)][c].yymm==undefined)?'':orgseq[(i-1)][c].yymm;
							info.push("<tr><th><nobr>Date：</nobr></th><td><nobr>"+yymm+"</nobr></td></tr>");
							<?php
								$query1="SELECT * FROM org_data WHERE org_kind='intro_no' and yn='Y'  order by abs(sort),no";
								$res1=$db->query($query1);
								while($data1=$res1->fetch()){
									echo "info.push('<tr><th><nobr>".$data1['chfield']."：</nobr></th><td><nobr>'+orgseq[(i-1)][c].".$data1['enfield']."+'</nobr></td></tr>');";
								}
							?>
							info.push("</table>");
							title_str=info.join("");
							toolTip.setAttribute("title",title_str);
							if(i==orgseq.length){			
								toolTip.innerHTML=chgGradeImg(orgseq[(i-1)][c].grade_class);	//最Back一排不顯示資訊指顯示Ranking的Pax頭
							}else{
								toolTip.innerHTML=chgGradeImg(orgseq[(i-1)][c].grade_class)+"<br>";
								toolTip.innerHTML+=orgseq[(i-1)][c].mb_name;
							}
							mb_block.appendChild(toolTip);
							nc.appendChild(mb_block);
						}else{
							nc.innerHTML=" ";
						}
						c++;
					}
					nr=obj.insertRow(-1);
					var c=0;
					while(c<cels){
						nc=nr.insertCell(-1);
						if(colspan!=1){
							nc.colSpan=colspan;
						}
						if((orgseq[(i-1)][c].mb_no != "NONE")&&(orgseq[(i-1)][c].down_line != 0)){
							//20110307 joe
							var arrow_block=document.createElement("span");
							if(i==1){
								arrow_block.setAttribute("id",orgseq[(i-1)][c].intro_no+"||"+orgseq[(i-1)][c].mb_no);
								nowmb=orgseq[(i-1)][c].mb_no;
							}else{
								arrow_block.setAttribute("id",orgseq[(i-1)][c].mb_no+"||"+orgseq[(i-1)][c].mb_no);
							}
							arrow_block.setAttribute("className","mb_block");
							arrow_block.setAttribute("class","mb_block");
							arrow_block.innerHTML="<img src='module/orgseq/img/"+i+".gif'>";
							nc.appendChild(arrow_block);
							//end----------------------------joe
						}
						c++;
					}
					i++;
				}
				msg_block.show_info("Data processing is completed");
				initTooltips();
			}
		}
	}
}
function queryOrgseq(){
	msg_block.show_info("Data is processing...");
	createRequest();
	var url='module/orgseq/ajax.orgseq.php';
	post_str='orgseq=5&mb_no='+arguments[0]+'&his='+_id('f_his2').value+'&true_mb_no='+_id('mb_no2').value.trim();
	if(typeof arguments[1] !='undefined'){
		if(arguments[1]=='head'){
			post_str+="&prevP=h";
		}else{
			post_str+="&prevP=1";
		}
	}
	
	request.onreadystatechange=orgseqRes;
	request.open('POST',url,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	request.send(post_str);
}

function chk_country(){
	//msg_block.clear();
	if(_id('mb_no2').value.length>0){
		post_str='chk_ct=1&ct_mb_no='+_id('mb_no2').value.trim();
		//alert(post_str);
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		request.onreadystatechange=chk_country_res;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}

function chk_country_res(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){		
				//msg_block.show_err("This account does not have permission to check this country member!");
				alert("This account does not have permission to check this country member!");
				_id('mb_no2').value='';
			}else if(request.responseText==0){		
				alert("Member not found!");
				_id('mb_no2').value='';
			}
		}
	}
}

function query_unit2_1(){
	
	var mb_no=_id("mb_no2").value.trim();
	if(mb_no.length>0  && _id('mb_no2').value!='- Member ID -'){
		createRequest();
		var url='module/orgseq/ajax.orgseq.php';
		post_str='query_unit=1&mb_no='+mb_no;
		request.onreadystatechange=query_unit_res2_1;
		request.open('POST',url,true);
		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
		request.send(post_str);
	}
}
function query_unit_res2_1(){
	if(request.readyState==4){
		if(request.status==200){
			if(request.responseText=='none'){
				_id("mb_no2").value='';
				alert("Member ID not found!");
			}else{
				unit_data=request.responseText.parseJSON();
				w=1;
				if(unit_data.length>0){
					leng=_id('f_his2').options.length-1;
					while(leng>0){
						_id('f_his2').remove(leng);
						leng--;
					}
					leng=unit_data.length;
					while(w<=leng){
						_id('f_his2').options[w]=new Option(unit_data[w-1],unit_data[w-1]);
						w++;
					}
				}
				chk_country();
			}
		}
	}
}

<?php
	$grade_query="SELECT no,name FROM grade ORDER BY no";
	$grade_res=$db->query($grade_query);
?>
function chgGradeName(){
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
function chgGradeImg(){
	switch(arguments[0]){
		<?php
			$grade_query="SELECT no,name,img FROM grade ORDER BY no";
			$grade_res=$db->query($grade_query);
			while($grade=$grade_res->fetch()){
				echo "case '".$grade['no']."':";
				echo "return \"<img src='module/orgseq/grade/".$grade['img']."'>\";";
				echo "break;";
			}
		?>
	}
}

function ini_orgseq2(){
	msg_block.clear();
	document.getElementsByTagName('body')[0].id='block3';
	_id('add_btn_block').style.display='none';
	_id('save_btn_block').style.display='none';
	_id('modify_btn_block').style.display='none';
	_id('cancel_btn_block').style.display='none';
	_id('del_btn_block').style.display='none';
	_id('print_btn_block').style.display='none';
	_id('module_title').getElementsByTagName('span')[0].innerHTML="Upright placement chart";
	
}
attachEventListener(_id('first_tb2'),'dblclick',chgMb,false);
attachEventListener(_id('search_mb_org5'),"click",function(){queryOrgseq(_id("mb_no2").value.trim());},false);
attachEventListener(_id('mb_no2'),'dblclick',function(){outwork('mb_no2');},false);
attachEventListener(_id('mb_no2'),'blur',function(){query_unit2_1();},false);
//attachEventListener(_id('mb_no2'),'blur',function(){chk_country();},false);
_id('f_his2').value='<?php echo isset($_GET['his'])?$_GET['his']:-1 ?>';
// function outwork(fun){
  	// window.open("./module/qmbst/qmbst.php?fun="+fun,"test","width=710,height=580,scrollbars=yes,resizable=yes,status=yes");  
// }

function blur_mb_no2_1(){
	_id('mb_no2').defaultValue='- Member ID -';
	if(_id('mb_no2').value.length<1){
		_id('mb_no2').value=_id('mb_no2').defaultValue.trim();
		_id('mb_no2').style.color="#CCC";
		_id('mb_no2').style.paddingLeft="5px";
		_id('mb_no2').style.background="#FEFEFE";
	}
}
function focus_mb_no2_1(){
	if(_id('mb_no2').value == _id('mb_no2').defaultValue){
		_id('mb_no2').value='';
		_id('mb_no2').style.color="#333";
		_id('mb_no2').style.paddingLeft="0px";
		_id('mb_no2').style.background="#E5FFDD";
	}
}
attachEventListener(_id('mb_no2'),"click",focus_mb_no2_1,false);
attachEventListener(_id('mb_no2'),"blur",blur_mb_no2_1,false);

</script>