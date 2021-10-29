app.controller('order_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl','pubcode','$filter','$timeout','Excel','modalService', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl,pubcode,$filter,$timeout,Excel,modalService) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/order/api.php");
		
		my.funcPerm = $rootScope.funclist['order'];
		
		var path = $location.path();
		my.options = {
			ranges: {
	            '最近 7 天': [moment().subtract(6, 'days'), moment()],
	            '最近 30 天': [moment().subtract(29, 'days'), moment()]
	        }
		}
		
		my.options2 = {
	        locale: {
				format: 'YYYY-MM-DD H:mm'
			},
			ranges: {
	            '最近 7 天': [moment().subtract(6, 'days'), moment()],
	            '最近 30 天': [moment().subtract(29, 'days'), moment()]
	        },
			timePicker : true
		}
		
		my.params = {
			page: !param.page ? 1 : param.page,
			status: !param.status ? -1 : param.status,
			exportChk: !param.exportChk ? -1 : param.exportChk,
			search: !param.search ? "" : param.search,
			date: {
				startDate:  param.date ? (!param.date.startDate ? null : param.date.startDate) : null,
				endDate: param.date ? (!param.date.endDate ? null : param.date.endDate) : null
			},
			cdate: {
				startCDate: param.cdate ? (!param.cdate.startCDate ? null : param.cdate.startCDate) : null,
				endCDate: param.cdate ? (!param.cdate.endCDate ? null : param.cdate.endCDate) : null
			},
			orderby: !param.orderby ? {buyDate: "desc"} : param.orderby,
			orderType: !param.orderType ? "" : param.orderType,
			orderMode: !param.orderMode ? -1 : param.orderMode,
			payType: !param.payType ? -1 : param.payType
		}
		
		my.data_list = [];
		CRUD.detail({task:'getpubcode'}, "GET").then(function(res) {
			if(res.status == 1) {
				my.code_list = res.data;
			}	
		});
		
		
		//List Check Box Control
		my.selected = [];
		my.toggle = function (item, list) {
		    var idx = list.indexOf(item);
		    if (idx > -1) {
		    	list.splice(idx, 1);
		    }
		    else {
		    	list.push(item);
		    }
		};
		my.exists = function (item, list) {
		    return list.indexOf(item) > -1;
		};
		my.isIndeterminate = function() {
		    return (my.selected.length !== 0 && my.selected.length !== (my.data_list.length - cancelcnt));
		};
		my.isChecked = function() {
		    return my.selected.length === (my.data_list.length - cancelcnt) && my.data_list.length > 0;
		};
		my.toggleAll = function() {
			if (my.selected.length === (my.data_list.length-cancelcnt)) {
				my.selected = [];
			} else if (my.selected.length === 0 || my.selected.length > 0) {
				my.selected = my.data_list.slice(0);
				angular.forEach(my.selected, function(val, key) {
					if(val.statusPcode == 6) {
						var idx = my.selected.indexOf(val);
						if (idx > -1) {
					    	my.selected.splice(idx, 1);
					    }
					}	
				});
			}
		};
		var cancelcnt = 0;
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
					
					my.printhtml = res.data.printhtml;
					
					//登入者的訂單匯出權限
					my.orderExport = (res.data.orderExport == 'true') ? true : false;
					
					angular.forEach(my.data_list, function(val, key) {
						if(val.statusPcode==6)	{
							cancelcnt++;
						}
					});
				}	
			});
		}
		
		my.sort = function(field){
			
			if(my.params.orderby[field] == "asc") {
				delete my.params.orderby[field];
				my.params.orderby[field] = "desc";
			} else {
				delete my.params.orderby[field];
				my.params.orderby[field] = "asc";
			}
			my.refresh();
		}
		
		my.refresh = function() {
			console.log(my.params);
			//urlCtrl.go(path, my.params);
		}
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/order_page", param);
		}
		
		my.delete = function(id) {
			
			if(my.funcPerm.D == 'true')
			{
				CRUD.del({id:id}, "POST").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						my.refresh();
					}
				});
			}
			
		}
		
		my.list();
		
		my.export = function(){
			
			var idstr = "||";
			angular.forEach(my.selected, function(value, key) {
				idstr += value.id+'||';
			});
			$timeout(function() {
				location.href="/manager/components/order/api.php?task=upd_exportChk&idstr="+idstr;
				//my.list();
			}, 100);
			
			
			
			
			
			/*
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
					
					my.printhtml = res.data.printhtml;
					
					//登入者的訂單匯出權限
					my.orderExport = (res.data.orderExport == 'true') ? true : false;
										
					angular.forEach(my.data_list, function(val, key) {
						if(val.statusPcode==6)	{
							cancelcnt++;
						}
					});
					
					var html = my.printhtml;
					var export_url=Excel.tableToExcel(html,'sheet name');
					$timeout(function() {
						location.href="/manager/components/order/api.php?task=upd_exportChk";
						
						my.list();
					}, 100);
				}	
			});
			*/
			
		};
		
		
		my.operateChg = function(v){
			
			if(  ((v==1 || v==2) && my.funcPerm.U == 'true') || (v==3 && my.funcPerm.D == 'true') )
			{
				
				if(v){
					if(my.selected.length > 0) {
						var listCB=[];
						var txt="";
						var idstr = "||";
						angular.forEach(my.selected, function(value, key) {
							listCB.push(value.id);
							idstr += value.id+'||';
						});
						
						if(v==1){
							txt= $translate.instant('lg_main.batch_opt1_chk');
						}else if(v==2){
							txt= $translate.instant('lg_main.batch_opt2_chk');
						}else if(v==3){
							txt= $translate.instant('lg_main.batch_opt3_chk');
						}
						
						if(txt)
						{
							 alertify.confirm(txt)
							.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
							.set({ labels : { ok: $filter('translate')('lg_main.yes') ,cancel:$filter('translate')('lg_main.no')} })
							.set('onok', function(closeEvent){
								var params = {
									task:'operate',
									id:listCB,
									action:v
								};
								CRUD.update(params, "POST").then(function(res){
							
									if(res.status == 1) {
										success(res.msg);
										$route.reload();
									}
								});
							});
						}
						else
						{
							if(v==4)
							{
								window.open("components/order/print.php?v=4&idStr="+idstr);
							}
							else if(v==5)
							{
								window.open("components/order/print.php?v=5&idStr="+idstr);
							}
							else
							{
								var param = {
									id: listCB,
									p: my.params
								}
								urlCtrl.go("/order_porlist", param);
							}
						}
					} else {
						message($translate.instant("lg_main.nochoice"));
					}
				}
				
			}
			
			
			
		};
		
		my.orderTypeChg = function(v){
			my.params.status=v;
			if(v==-1){
				my.orderTypeStr=$translate.instant('lg_order.order_type');//訂單狀態
			}else{
				my.orderTypeStr=$rootScope.pubcodeArr.bill[v]['name'];
			}
			my.list();
		};
		if(my.params.status){
			if(my.params.status==-1){
				my.orderTypeStr=$translate.instant('lg_order.order_type');//訂單狀態
			}else{
				my.orderTypeStr=$rootScope.pubcodeArr.bill[my.params.status]['name'];
			}
			//my.orderTypeChg(my.params.status);
		}
		
		my.exportChkChg = function(v){
			my.params.exportChk=v;
			if(v==-1){
				my.exportChk=$translate.instant('lg_order.order_exportChk');//是否轉出
			}else{
				my.exportChk=(v==1) ? $translate.instant('lg_order.order_exportYes') : $translate.instant('lg_order.order_exportNo');
			}
			my.list();
		};
		if(my.params.exportChk){
			
			if(my.params.exportChk==-1){
				my.exportChk=$translate.instant('lg_order.order_exportChk');//是否轉出
			}else{
				my.exportChk=(my.params.exportChk==1) ? $translate.instant('lg_order.order_exportYes') : $translate.instant('lg_order.order_exportNo');
			}
			//my.exportChkChg(my.params.exportChk);
		}
		
		
		my.exportProductReport = function (productId) {
			my.selectModalInstance = modalService.openModal("timeSelector", {
				setting: [{
					label: '開始日期',
					format: 'YYYY-MM-DD HH:mm:ss',
					required: false
				},{
					label: '結束日期',
					format: 'YYYY-MM-DD HH:mm:ss',
					required: false
				}]
			});
			my.selectModalInstance.result
			.then(function (date) {
				var startDate = (date[0].date) ? date[0].date : '';
				var endDate = (date[1].date) ? date[1].date : '';
				
				my.params.date.startDate = startDate;
				my.params.date.endDate = endDate;
				
				my.dateStr = startDate+" - "+endDate;
				
				my.list();
				
			});
		}
		
		//my.exportProductReport();
		
		my.orderModeChg = function(v){
			my.params.orderMode=v;
			if(v==-1){
				my.orderModeStr=$translate.instant('lg_order.order_orderModeStr');//訂單種類
			}else{
				var tmpStr = "";
				if(v == 1)
				{
					tmpStr = $translate.instant('lg_order.order_orderModeStr1');//線上購物的一般付費
				}
				else if(v == 2)
				{
					tmpStr = $translate.instant('lg_order.order_orderModeStr2');//線上加入的會費
				}
				my.orderModeStr=tmpStr;
			}
			my.list();
		};
		
		my.payTypeChg = function(v){
			my.params.payType=v;
			if(v==-1){
				my.payTypeStr=$translate.instant('lg_order.order_payType');//付款方式
			}else{
				var tmpStr = "";
				if(v == 2)
				{
					tmpStr = $translate.instant('lg_order.order_payType2');//ATM匯款
				}
				else if(v == 3)
				{
					tmpStr = $translate.instant('lg_order.order_payType3');//線上刷卡
				}
				else if(v == 5)
				{
					tmpStr = $translate.instant('lg_order.order_payType5');//店取付現
				}
				my.payTypeStr=tmpStr;
			}
			my.list();
		};
		
		
	}
}])

.controller('order_porlist', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal','pubcode', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal,pubcode) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	if(param) {
		CRUD.setUrl("components/order/api.php");
		
		my.funcPerm = $rootScope.funclist['order'];
		
		my.backhash=urlCtrl.enaes(param.p);
		my.backpath="order_list";
		
		my.params = {
			task:'porlist',
			id: !param.id ? null : param.id
		}
		
		my.porlist = function() {
			CRUD.list(my.params, "POST").then(function(res) {
				if(res.data.status == 1) {
					my.data_list = res.data.data;
				}
			});
		}
		
		my.porlist();
		
	}
	
}])
.controller('order_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal','pubcode','$filter','$timeout','Excel', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal,pubcode,$filter,$timeout,Excel) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	var listparams = {};
	if(param) {
		CRUD.setUrl("components/order/api.php");
		
		my.funcPerm = $rootScope.funclist['order'];
		
		my.params = {
			id: !param.id ? null : param.id
		}
		listparams = !param.listparams ? {} : param.listparams;
		$rootScope.firstIndex=0;
				
		if(!my.order)my.order={};
			
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.order = res.data;
					my.pcode = res.pcode;
					my.orderDtl=res.dtl;
					my.combinorder = res.combinorder;
					my.order.dlvrDate = new Date(res.data.dlvrDate);
					my.orderBundleArray=res.orderBundleArray;
					my.sysCurrency=res.sysCurrency;
					if(!my.trace)
					{
						my.trace = {};
						my.trace.traceName = "黑貓宅急便";
						my.trace.traceUrl = "http://www.t-cat.com.tw/Inquire/Trace.aspx";
					}
					my.trace.traceNumber = res.data.traceNumber;
					
					var oldVal=my.order.status;
					$scope.$watch('ctrl.order.status', function (newVal) {
						if(!my.pcode)my.pcode={};
						if(my.pcode['bill'] && oldVal!=newVal){
							//付款方式為線上付款，不可切換狀態
							if(my.order.payTypeCode == 3 && oldVal == 0)
							{
								my.order.status=oldVal;
								//付款方式為線上付款，不可切換訂單狀態。
								error($translate.instant('lg_order.order_msg4'));
							}
							else
							{
								var cname=$rootScope.pubcodeArr['bill'][newVal]['name'];
								if((newVal==2 && my.order.payTypeCode==1) || (newVal==1 && my.order.payTypeCode==3)){
									cname=$rootScope.pubcodeArr['bill'][newVal]['codeName_chs'];
								}
								
								if( my.funcPerm.U == 'true' )
								{
									
									alertify.confirm(msgStyle($translate.instant('lg_order.order_statusChg')+cname+"?"))
									.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
									.set({ labels : { ok: $translate.instant('lg_main.ok') ,cancel:$translate.instant('lg_main.cancel')} })
									.set('onok', function(closeEvent){
										CRUD.update(my.order, "POST").then(function(res) {
											if(res.status == 1) {
												success(res.msg);
												$route.reload();
											}
										});
									}).set('oncancel', function(closeEvent){
										$scope.$apply(function(){
											my.order.status=oldVal;
										});
									});
									
								}
							}
						}
					});
		
					$scope.dateOptions = {
					    dateDisabled: disabled,
					    formatYear: 'yy',
					    minDate: new Date(),
					    startingDay: 1
					};
			
				}
			});
		}
		
		my.datePickerOption = {
			singleDatePicker: true,
			timePicker:true,
			locale: {
	            format: 'YYYY-MM-DD HH:mm'
	        }
		};

		my.datePickerOption2 = {
			singleDatePicker: true,
			timePicker:false,
			locale: {
	            format: 'YYYY-MM-DD'
	        }
		};
		
		//更新完款日期
		var finalPayDate_update={};
		my.order_finalPayDate_update = function(){
			finalPayDate_update.task="finalPayDate_update";
			finalPayDate_update.finalPayDate=my.order.finalPayDate;
			if( my.funcPerm.U == 'true' ) 
			{
				CRUD.update(finalPayDate_update, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
					}
				});
			}
		}

		var shipDate_update={};
		my.order_label_update = function(){
			if(my.order.shipDate){
				shipDate_update.task="shipDate_update";
				shipDate_update.shipDate=my.order.shipDate;
				if( my.funcPerm.U == 'true' ) 
				{
					CRUD.update(shipDate_update, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							my.order.has_label = '1';
							my.order.label_shipDate = my.order.shipDate;
						}else{
							error(res.msg);
						}
					});
				}
			}else{
				error('Please set shipDate');
			}
			
		}
		
		
		my.submit = function() {
			/*CRUD.update(my.order, "POST").then(function(res) {
				if(res.status == 1) {
					success(res.msg);
					my.cancel();
				}
			});*/
		}
		
		my.cancel = function() {
			
			if(listparams.task == 'salesdetails')
			{
				urlCtrl.go("/report_list", listparams);
			}
			else
			{
				urlCtrl.go("/order_list", listparams);
			}
			
			//urlCtrl.go(-1);
		}
		
		
		if(param.id) {
			my.actionType=1;
			my.detail();
		} else {
			my.actionType=0;
			var date = new Date();
			my.order_dtl = {
				orderType: listparams.orderType,
				content: "",
				linkurl: "http://",
				name: "",
				orderDate: date.toISOString().slice(0,10).replace(/-/g,"-"),
				pubDate: "",
				publish: 1,
				type: "page",
				tablename: "",
				databaseid: 0,
				databasename: ""
			}
		}
		
		my.trace_submit=function(){
			if(my.trace){
				my.trace.task='trace';
				my.trace.id=my.order.id;
				
				
				if( my.funcPerm.U == 'true' ) 
				{
					CRUD.update(my.trace, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							setTimeout(function(){
								$route.reload();
							},100);
						}
					});
				}
				
				
			}
		};
		my.cancel_order=function(){
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				alertify.confirm(msgStyle($translate.instant('lg_order.order_msg2')))
				.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
				.set({ labels : { ok: $translate.instant('lg_main.ok') ,cancel:$translate.instant('lg_main.cancel')} })
				.set('onok', function(closeEvent){
					CRUD.update({task:'cancel',id:my.order.id}, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				});
				
			}
			
			
			
		};
		
		my.return_order=function(){
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				alertify.confirm(msgStyle($translate.instant('lg_order.order_msg3')))
				.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
				.set({ labels : { ok: $translate.instant('lg_main.ok') ,cancel:$translate.instant('lg_main.cancel')} })
				.set('onok', function(closeEvent){
					CRUD.update({task:'return',id:my.order.id}, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				});
				
			}
			
			
		};
		
		$scope.popup2 = {
		    opened: false
		};
		$scope.open2 = function() {
		    $scope.popup2.opened = true;
		};
	  
	    $scope.dateOptions = {
		    dateDisabled: disabled,
		    formatYear: 'yy',
		    minDate: new Date(),
		    startingDay: 1
		};
		function disabled(data) {
		    var date = data.date,
		        mode = data.mode;
	
		    var m=(date.getMonth()+1);
		    if(m<10)m='0'+m;
		    var d=date.getDate();
		    if(d<10)d='0'+d;
		    var to=date.getFullYear()+"-"+m+"-"+d;
		    
		    //return mode === 'day' && ((date.getDay() === 0 && my.info.enableDate.indexOf(to)==-1) || (date.getDay() === 1 && my.info.enableDate.indexOf(to)==-1)) || (my.info.disableDate.indexOf(to)>-1);
		    return mode === 'day' && (my.order.disableDate.indexOf(to)>-1);
		} 
	
	
		my.get_addrCode=function(){	
			var turl=CRUD.getUrl();
			CRUD.setUrl("app/controllers/eways.php");
			CRUD.detail({task: "get_addrCode"}, "GET").then(function(res){
				if(res.status == 1) {
					my.city = res.city;
				}
			});
			CRUD.setUrl(turl);
		};
		my.get_addrCode();
		var order_sub={};
		my.receive_update = function(){
			
			order_sub.task="receive_update";
			order_sub.dlvrName=my.order.dlvrName;
			order_sub.dlvrMobile=my.order.dlvrMobile;
			order_sub.dlvrAddr=my.order.dlvrAddr;
			if(my.order.dlvrDate){
				var date=my.order.dlvrDate;
			    order_sub.dlvrDate=$filter('date')(date.getTime(), "yyyy-MM-dd");
			}
		
			order_sub.dlvrTime=my.order.dlvrTime;
			order_sub.dlvrNote=my.order.dlvrNote;
			order_sub.dlvrCity=my.order.dlvrCity.id;
			order_sub.dlvrCanton=my.order.dlvrCanton.id;
			
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				CRUD.update(order_sub, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
					}
				});
				
			}
			
			
		};
		var invoice_update={};
		my.invoice_update = function(){
			invoice_update.task="invoice_update";
			invoice_update.invoiceType=my.order.invoiceType;
			invoice_update.invoiceTitle=my.order.invoiceTitle;
			invoice_update.invoiceSN=my.order.invoiceSN;
			invoice_update.invoice=my.order.invoice;
			
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				CRUD.update(invoice_update, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
					}
				});
				
			}
			
			
		};
	}


}]);