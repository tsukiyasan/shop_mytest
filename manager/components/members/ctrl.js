app.controller('members_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl','$timeout','Excel', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $timeout, Excel) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/members/api.php");
		
		my.funcPerm = $rootScope.funclist['members'];
		
		var path = $location.path();
		
		my.data_list = [];
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? "" : param.search,
			proclass: typeof param.proclass=='undefined' ? -1 : param.proclass,
			memType: typeof param.memType=='undefined' ? -1 : param.memType,
			date: {
				startDate:  param.date ? (!param.date.startDate ? null : param.date.startDate) : null,
				endDate: param.date ? (!param.date.endDate ? null : param.date.endDate) : null
			},
			date2: {
				startDate:  param.date2 ? (!param.date2.startDate ? null : param.date2.startDate) : null,
				endDate: param.date2 ? (!param.date2.endDate ? null : param.date2.endDate) : null
			}
		}
		
		my.nowclass=$translate.instant('lg_members.members_sales'+my.params.proclass);
		
		if(my.params.memType == 0)
		{
			my.memTypeStr = $translate.instant('lg_members.members_memType1');//一般會員
		}else if(my.params.memType == 1){
			my.memTypeStr = $translate.instant('lg_members.members_memType2'); //Ｅ化加入會員
		}else if (my.params.memType == 4) {
			my.memTypeStr = $translate.instant('lg_members.members_memType4'); 
		} else if (my.params.memType == 5) {
			my.memTypeStr = $translate.instant('lg_members.members_memType5'); 
		} else if (my.params.memType == 6){
			my.memTypeStr = $translate.instant('lg_members.members_memType6');
		}
		
		my.options = {
			locale: {
				format: 'YYYY-MM-DD H:mm'
			},
			ranges: {
	            '最近 7 天': [moment().subtract(6, 'days'), moment()],
	            '最近 30 天': [moment().subtract(29, 'days'), moment()]
	        }
		}
		
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
					
					my.printhtml = res.data.printhtml;
				}	
			});
		}
		
		my.refresh = function() {
			urlCtrl.go(path, my.params);
		}
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/members_page", param);
		}
		
		my.golog = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/members_log", param);
		}
		
		my.gocoin = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/members_coin", param);
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
		
		my.classChg=function(t){
			
			my.nowclass=$translate.instant('lg_members.members_sales'+t);
		
			my.params.proclass=t;
			my.refresh();
		};
		
		my.memTypeChg=function(t){
			
			if(t == 0)
			{
				my.memTypeStr = $translate.instant('lg_members.members_memType1');//一般會員
			} else if(t == 1) {
				my.memTypeStr = $translate.instant('lg_members.members_memType2');//Ｅ化加入會員
			} else if (t == 4) {
				my.memTypeStr = $translate.instant('lg_members.members_memType4');
			} else if (t == 5){
				my.memTypeStr = $translate.instant('lg_members.members_memType5');
			} else if (t == 6){
				my.memTypeStr = $translate.instant('lg_members.members_memType6');
			}
		
			my.params.memType=t;
			my.refresh();
		};
		
		$scope.$watch('allCB',function(n,o){
			angular.forEach(my.listCB,function(v,k){
				my.listCB[k]=n;
			});
		});
		my.operateChg = function(v){
			if(v){
				var listCB=[];
				var txt="";
				angular.forEach(my.selected,function(v,k){
					listCB.push(v.id);
				});
				if(v==1){
					txt= $translate.instant('lg_main.batch_opt1_chk');
				}else if(v==2){
					txt= $translate.instant('lg_main.batch_opt2_chk');
				}else if(v==3){
					txt= $translate.instant('lg_main.batch_opt3_chk');
				}
				 alertify.confirm(txt)
    			.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
    			.set({ labels : { ok: $translate.instant('lg_main.yes') ,cancel:$translate.instant('lg_main.no')} })
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
		};
		
		my.list();
		
		my.export = function(){
			
			var html = my.printhtml;
			var export_url=Excel.tableToExcel(html,'sheet name');
			$timeout(function() {
				
				location.href="/manager/components/members/api.php?task=upd_exportChk";
				
			}, 100);
		};
		
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
		    return (my.selected.length !== 0 && my.selected.length !== my.data_list.length);
		};
		my.isChecked = function() {
		    return my.selected.length === my.data_list.length && my.data_list.length > 0;
		};
		my.toggleAll = function() {
			if (my.selected.length === my.data_list.length) {
				my.selected = [];
			} else if (my.selected.length === 0 || my.selected.length > 0) {
				my.selected = my.data_list.slice(0);
			}
		};
	}
}])

.controller('members_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		
		CRUD.setUrl("components/members/api.php");
		
		my.funcPerm = $rootScope.funclist['members'];
		
		my.params = {
			page: !param.page ? 1 : param.page,
			id: !param.id ? null : param.id
		}
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.listcity = function() {
			CRUD.detail({task: "citylist"}, "GET").then(function(res) {
				my.citylist = res;
			});
		}
		
		my.listcanton = function(cityid) {
			CRUD.detail({task: "cantonlist", cityid: cityid}).then(function(res) {
				my.cantonlist = res;
			});
		}
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.detailData = res.info;
					my.detailData.salesChkStr=$translate.instant('lg_members.members_sales'+my.detailData.salesChk);
					my.listcanton(my.detailData.city);
				}
			});
		}
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		}
		
		my.submit = function() {
			
			if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
			{
				CRUD.update(my.detailData, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
			}
			
			
		}
		
		my.salesChk=function(){
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				alertify.confirm(msgStyle($translate.instant('lg_members.members_salesChk')))
				.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
				.set({ labels : { ok: $translate.instant('lg_main.cfm') ,cancel:$translate.instant('lg_main.cancel')} })
				.set('onok', function(closeEvent){ 
				    
	                CRUD.update({task:'update_classtosales'}, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				});
				
			}
			
			
			
		};
		
		my.salesChk2=function(){
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				alertify.confirm(msgStyle($translate.instant('lg_members.members_salesChk')))
				.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
				.set({ labels : { ok: $translate.instant('lg_main.cfm') ,cancel:$translate.instant('lg_main.cancel')} })
				.set('onok', function(closeEvent){ 
				    
	                CRUD.update({task:'update_classtosales2'}, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				});
				
			}
			
			
		};
		
		my.refresh = function() {
			urlCtrl.go(path, my.params);
		}
		my.cancel = function() {
			urlCtrl.go("-1");
		}
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/order_page", param);
		}
		
		my.delete = function(id) {
			CRUD.del({id:id}, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					my.refresh();
				}
			});
		};
		
		my.listcity();
		if(param.id) {
			my.detail();
			my.list();
		} else {
			my.detailData = {
				name: "",
				locked: 0
			}
		}
	}
	
}]).controller('members_log', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/members/api.php");
		var path = $location.path();
		
		my.listCB = [];
		my.params = {
			task: 'log',
			page: !param.page ? 1 : param.page,
			search: !param.search ? "" : param.search,
			id: !param.id ? null : param.id
		}
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		}
		
		my.backlist = function() {
			urlCtrl.go("-1");
		}
		
		my.list();
	}
}]).controller('members_coin', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/members/api.php");
		var path = $location.path();
		
		my.listCB = [];
		my.params = {
			task: 'coin',
			page: !param.page ? 1 : param.page,
			search: !param.search ? "" : param.search,
			id: !param.id ? null : param.id
		}
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		}
		
		my.backlist = function() {
			urlCtrl.go("-1");
		}
		
		my.goorder = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/order_page", param);
		}
		
		my.list();
	}
}]);