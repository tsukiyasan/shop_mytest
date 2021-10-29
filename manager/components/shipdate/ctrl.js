app.controller('shipdate_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$filter', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $filter) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		CRUD.setUrl("components/shipdate/api.php");
		var path = $location.path();
		
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? "" : param.search
		}
		
		my.listCB = [];
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
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
			urlCtrl.go("/shipdate_page", param);
		}
		
		my.delete = function(id) {
			CRUD.del({id:id}, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					my.refresh();
				}
			});
		}
		
		$scope.$watch('allCB',function(n,o){
			angular.forEach(my.listCB,function(v,k){
				my.listCB[k]=n;
			});
		});
		my.operateChg = function(v){
			if(v){
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
		};
		
		my.list();
	}
}])

.controller('shipdate_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	if(param) {
		my.detailData={};
		my.detailData.type=0;
		$scope.$on('$viewContentLoaded', function() {
			
			setTimeout(function(){
				$('#shipdate_date').datepicker({
	    			language: "zh-TW",
	    			todayBtn: "linked",
					format: 'yyyy-mm-dd'
				});
			},1);
		});
		
		CRUD.setUrl("components/shipdate/api.php");
		my.params = {
			page: !param.page ? 1 : param.page,
			id: !param.id ? null : param.id
		}
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.detailData = res.info;
					//my.listcanton(my.detailData.city);
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
			CRUD.update(my.detailData, "POST").then(function(res) {
				if(res.status == 1) {
					success(res.msg);
					my.cancel();
				}
			});
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
		
		
		CRUD.detail({task:'getpcode'}, "GET").then(function(res) {
			if(res.status == 1) {
				my.pcode = res.pcode;
				
			}	
		});
		
		if(param.id) {
			my.detail();
			my.list();
		} else {
			my.detailData = {
				name: "",
				type: 0
			}
		}
		
	}
	
}]);