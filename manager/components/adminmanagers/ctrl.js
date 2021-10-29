app.controller('adminmanagers_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		
		my.funcPerm = $rootScope.funclist['adminmanagers'];
		
		CRUD.setUrl("components/adminmanagers/api.php");
		var path = $location.path();
		
		my.listCB = [];
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? "" : param.search
		}
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					console.log(res.data.data);
					my.pageCtrl = res.pageCtrl;
					my.data_list = res.data.data;
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
			urlCtrl.go("/adminmanagers_page", param);
		}
		
		my.goview = function(id) {
			var param = {
				id: id,
				view: 'view',
				listparams: my.params
			}
			urlCtrl.go("/adminmanagers_page", param);
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
		
		my.lockedChange = function(id,locked){
		    
			if(my.funcPerm.U == 'true')
			{
				var params = {
					task:'lockedChg',
					id:id,
					locked:locked==1?0:1
				};
				
				CRUD.update(params, "POST").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						$route.reload();
					}
				});
			}
		};
		
		/*
		$scope.$watch('allCB',function(n,o){
			angular.forEach(my.listCB,function(v,k){
				my.listCB[k]=n;
			});
		});
		my.operateChg = function(v){
			if(v){
				
				if( ((v==1 || v==2) && my.funcPerm.U == 'true') || ( v==3 && my.funcPerm.U == 'true'))
				{
					var listCB=[];
					var txt="";
					angular.forEach(my.listCB,function(v,k){
						if(v==true){
							listCB.push(k);
						}
					});
					if(v==1){
						txt='確定開啟所選項目?';
					}else if(v==2){
						txt='確定關閉所選項目?';
					}else if(v==3){
						txt='確定刪除所選項目?';
					}
					 alertify.confirm(txt)
					.setHeader("<i class='fa fa-info-circle'></i> 操作提示")
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
			}	
		};
		*/
		
		my.list();
	}
}])

.controller('adminmanagers_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		
		my.funcPerm = $rootScope.funclist['adminmanagers'];
		
		CRUD.setUrl("components/adminmanagers/api.php");
		my.params = {
			page: !param.page ? 1 : param.page,
			id: !param.id ? null : param.id,
			view : !param.view ? 'edit' : param.view
		}
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.allCB = [];
		my.funlist = [];
		my.listCB = [];
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.detailData = res.info;
					my.functionsCht_arr = res.functionsCht_arr;
					
					my.detailData.orderExport = (my.detailData.orderExport == 'true') ? true : false;
					
					if(my.functionsCht_arr)
					{
						angular.forEach(my.functionsCht_arr,function(v,k){
							my.funlist.push(k);
							my.listCB[k] = [];
							my.listCB[k]['C'] = (v.C == 'true') ? true : false;
							my.listCB[k]['U'] = (v.U == 'true') ? true : false;
							my.listCB[k]['D'] = (v.D == 'true') ? true : false;
							my.listCB[k]['R'] = (v.R == 'true') ? true : false;
							
							if(my.listCB[k]['C'] && my.listCB[k]['U'] && my.listCB[k]['D'] && my.listCB[k]['R'])
							{
								my.allCB[k] = true;
							}
						});
					}
					my.getfunctionslist();
				}
			});
		}
		
		my.getfunctionslist = function() {
			
			var param = {
				task: 'getfunctionslist'
			}
			CRUD.list(param, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.functions_list = res.data.functions_list;
					
					angular.forEach(my.functions_list,function(v,k){
						if( my.listCB[v.func] === undefined ) {
							my.funlist.push(v.func);
							my.listCB[v.func] = [];
							my.listCB[v.func]['C']=false;
							my.listCB[v.func]['U']=false;
							my.listCB[v.func]['D']=false;
							my.listCB[v.func]['R']=false;	
						}			
					});
					
				}
			});
		}
		
		my.selectAll = function(func,v) {
			console.log(func, v);
			console.log(my.listCB);
			my.listCB[func]['C'] = v;
			my.listCB[func]['U'] = v;
			my.listCB[func]['D'] = v;
			my.listCB[func]['R'] = v;
		}
		
		my.selectClick = function(func,str) {
			if(!my.listCB[func][str])
			{
				my.allCB[func] = false;
			}
			else if(my.listCB[func]['C'] && my.listCB[func]['U'] && my.listCB[func]['D'] && my.listCB[func]['R'])
			{
				my.allCB[func] = true;
			}
		}
		
		my.submit = function() {
			
			if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
			{
				var txt="|||||";		
				angular.forEach(my.funlist,function(v,k){
					txt += v+"|||"+my.listCB[v]['C']+"|||"+my.listCB[v]['U']+"|||"+my.listCB[v]['D']+"|||"+my.listCB[v]['R']+'|||||';
				});
				
				my.detailData.functionsCht = txt;
				CRUD.update(my.detailData, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
			}
			
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
		
		if(param.id) {
			my.detail();
		} else {
			my.detailData = {
				name: "",
				rootFlag: "0",
				locked: "1",
				seeAmtChk: "1"
			}
			
			my.getfunctionslist();
		}
	}
	
}]);