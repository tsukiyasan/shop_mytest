app.controller('p_to_m_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl','pubcode','$filter','$timeout','Excel','modalService', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl,pubcode,$filter,$timeout,Excel,modalService) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/p_to_m/api.php");
		
		my.funcPerm = $rootScope.funclist['p_to_m'];
		
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
			carriedChk: !param.carriedChk ? -1 : param.carriedChk,
			invalidChk: !param.invalidChk ? 2 : param.invalidChk,
			alldataChk: !param.alldataChk ? -1 : param.alldataChk,
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
					console.log(my.data_list);
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
				location.href="/manager/components/p_to_m/api.php?task=upd_exportChk&idstr="+idstr;
				//my.list();
			}, 100);

			my.selected = [];
		};


		my.set_carried = function(){
			console.log('do this');
			var cidstr = "||";
			angular.forEach(my.selected, function(value, key) {
				cidstr += value.id+'||';
			});

			sc_params = {};
			sc_params.idstr = cidstr;
			sc_params.task = 'set_carried';
			CRUD.update(sc_params, "POST").then(function(res){		
				if(res.status == 1) {
					success(res.msg);
					my.list();
				}
			});

			my.selected = [];
			
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
				my.exportChk=$translate.instant('lg_p_to_m.pm_exportChk');//是否轉出
			}else{
				my.exportChk=(v==1) ? $translate.instant('lg_p_to_m.pm_exportYes') : $translate.instant('lg_p_to_m.pm_exportNo');
			}
			my.list();
		};
		if(my.params.exportChk){
			
			if(my.params.exportChk==-1){
				my.exportChk=$translate.instant('lg_p_to_m.pm_exportChk');//是否轉出
			}else{
				my.exportChk=(my.params.exportChk==1) ? $translate.instant('lg_p_to_m.pm_exportYes') : $translate.instant('lg_p_to_m.pm_exportNo');
			}
			//my.exportChkChg(my.params.exportChk);
		}


		my.carriedChkChg = function(v){
			my.params.carriedChk=v;
			if(v==-1){
				my.carriedChk=$translate.instant('lg_p_to_m.pm_cexportChk');//是否轉出
			}else{
				my.carriedChk=(v==1) ? $translate.instant('lg_p_to_m.pm_cexportYes') : $translate.instant('lg_p_to_m.pm_cexportNo');
			}
			my.list();
		};
		if(my.params.carriedChk){
			
			if(my.params.carriedChk==-1){
				my.carriedChk=$translate.instant('lg_p_to_m.pm_cexportChk');//是否轉出
			}else{
				my.carriedChk=(my.params.carriedChk==1) ? $translate.instant('lg_p_to_m.pm_cexportYes') : $translate.instant('lg_p_to_m.pm_cexportNo');
			}
		}

		my.invalidChkChg = function(v){
			my.params.invalidChk=v;
			if(v==-1){
				my.invalidChk=$translate.instant('lg_p_to_m.pm_iexportChk');//是否轉出
			}else{
				my.invalidChk=(v==1) ? $translate.instant('lg_p_to_m.pm_iexportNo') : $translate.instant('lg_p_to_m.pm_iexportYes');
			}
			my.list();
		};
		if(my.params.invalidChk){
			
			if(my.params.invalidChk==-1){
				my.invalidChk=$translate.instant('lg_p_to_m.pm_iexportChk');//是否轉出
			}else{
				my.invalidChk=(my.params.invalidChk==1) ? $translate.instant('lg_p_to_m.pm_iexportNo') : $translate.instant('lg_p_to_m.pm_iexportYes');
			}
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
}]);