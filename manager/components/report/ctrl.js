app.controller('report_list',['$rootScope','$scope','$location','$translate','CRUD','urlCtrl','$filter','Excel','$timeout','store',function($rootScope,$scope, $location,$translate,CRUD,urlCtrl,$filter,Excel,$timeout,store) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/report/api.php");
		var path = $location.path();
		//取得可使用報表
		if(!store.get('report_able_list') || true){
			CRUD.list({task:'report_list'}, "POST").then(function(res){
				if(res.data.status == 1) {
					my.report_able=res.data.data;
					store.set('report_able_list',res.data.data);
				}
			});
		}else{
			my.report_able=store.get('report_able_list');
		}
		
		my.params={task:param.task,sdate:param.sdate,edate:param.edate,member_name:param.member_name,orderby:param.orderby};
		my.params.orderby_str=[];
		angular.forEach(param.orderby,function(v,k){
			if(v.indexOf("-")>-1){
				my.params.orderby_str[v.replace(/\-/,"")]="desc";
			}else{
				my.params.orderby_str[v]="asc";
			}
		});
		
		//報表切換
		my.reportChg=function(task){
			param.task=task;
			param.orderby={};
			urlCtrl.go('report_list',param);
		};
		my.reportUrl="components/report/list_"+param.task+".html";
		
		if(param.task == 'salesdetails')
		{
			my.reportUrl2="components/report/list_"+param.task+"2.html";
		}
		
		my.report_title=$filter('translate')('lg_report.report_'+param.task);
		my.sdate=param.sdate;
		my.edate=param.edate;
		my.member_name=param.member_name;
		my.search=function(){
			param.sdate=my.sdate;
			param.edate=my.edate;
			param.member_name=my.member_name;
			my.reportChg(param.task);
		};
		
		//顯示搜尋條件&&預設排序
		$scope.$watch("ctrl.report_able",function(n,o){
			angular.forEach(n,function(v,k){
				if(v.value==param.task){
					my.search_container=v.search_container;
					my.list_orderby=v.orderby;
					//給定預設排序
					if(my.isEmpty(my.params.orderby)){
						my.params.orderby=[];
						angular.forEach(v.orderby,function(v2,k2){
							var f=v2.seq;
							f=f.replace(/desc/, "-").replace(/asc/,"")+v2.name;
							my.params.orderby.push(f);
						});
						
					}
				}
			});
		});
		
		//排序
		my.sort=function(name){
			var seq='';
			//移動排序順序
			//點選b後
			//['-a','b','c']->['-b','-a','c']
			angular.forEach(my.params.orderby,function(v,k){
				if(v.indexOf(name)>-1){
					seq=v;
					my.params.orderby.splice(k,1);
				}
			});
			if(seq.indexOf("-")>-1){
				name=name.replace(/\-/,"");
			}else{
				name="-"+name;
			}
			my.params.orderby.unshift(name);
			my.refresh();
		};
		
		my.refresh = function() {
			urlCtrl.go(path, my.params);
		}
		
		
		my.list=function(){
			CRUD.list(my.params, "POST").then(function(res){
				if(res.data.status == 1) {
					my.data_list = parseNum(res.data.data);
					
					if(param.task == 'salesdetails')
					{
						my.totalAmtSum = 0;
						my.totalItemSum = 0;
						my.shipmentSum = 0;
						my.unShipmentSum = 0;
						var buyDateStr = "";
						angular.forEach(my.data_list.data,function(v,k){
							
							if(buyDateStr != v.buyDate)
							{
								my.totalAmtSum += parseInt(v.totalAmt);
								buyDateStr = v.buyDate;
							}
							my.totalItemSum += parseInt(v.totalItem);
							my.shipmentSum += parseInt(v.shipment);
							my.unShipmentSum += parseInt(v.unShipment);
						});
					}
					
					my.sdate=res.data.sdate;
					my.edate=res.data.edate;
					my.view=res.data.view;
				}
			});
		};
		
		
		my.list();
		
		my.options = {
			scales: {
				yAxes: [{
		            display: true,
		            ticks: {
		                beginAtZero: true
		            }
		        }]
			}
		};
		
		my.export = function(){
			
			if(param.task == 'salesdetails')
			{
				var html = "<table>"+angular.element("#export_area2").find("table").html()+"</table>";
			}
			else
			{
				var html = "<table>"+angular.element("#export_area").find("table").html()+"</table>";
			}
			
			var export_url=Excel.tableToExcel(html,'sheet name');
			$timeout(function() {
				//location.href=export_url;
				var link = document.createElement('a');
				link.download = my.report_title+".xls";
				link.href = export_url;
				link.click();
			}, 100);
		};
		
		my.isEmpty = function(obj) {
		    for(var prop in obj) {
		        if(obj.hasOwnProperty(prop))
		            return false;
		    }
		    return true;
		};
		
		my.showData = function(str1,str2,index,i) {
			
			var chk = $('#salesdetails'+str1+str2+index).is(":hidden");
			$('.salesdetails').hide(); 
			if(chk)
			{
				$('#salesdetails'+str1+str2+index).show();
				
				if(index == 3)
				{
					my.data_list.data[i].icon3 = true;
				}
				else if(index == 2)
				{
					my.data_list.data[i].icon2 = true;
				}
				else
				{
					my.data_list.data[i].icon1 = true;
				}
				
				
			}
			else
			{
				$('#salesdetails'+str1+str2+index).hide();
				icon = false;
				
				if(index == 3)
				{
					my.data_list.data[i].icon3 = false;
				}
				else if(index == 2)
				{
					my.data_list.data[i].icon2 = false;
				}
				else
				{
					my.data_list.data[i].icon1 = false;
				}
			}
			
		}
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/order_page", param);
		}

	}	
}]);