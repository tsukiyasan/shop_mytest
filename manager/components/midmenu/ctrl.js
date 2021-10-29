app.controller('midmenu_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/midmenu/api.php");
		
		var level=param.level;
		if(!level)level=1;
		my.level=level;
		my.search_str=param.search_str;
		my.params = {
				belongid:!param.belongid ? 'root' : param.belongid,
				page: !param.page ? 1 : param.page,
				search_str:!param.search_str ? '' : param.search_str
			};
		//返回上一層的hash	
		my.backhash=urlCtrl.enaes(param.p);
		//新增的hash
		my.odrhash=urlCtrl.enaes({component:'midmenu',p:param});
		my.newhash=urlCtrl.enaes({p:param});
	
		
		my.listCB = [];
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res){
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					//加上給下一層的hash
					angular.forEach(res.data.data,function(v,key){
						res.data.data[key].param=urlCtrl.enaes({belongid:v.id,level:parseInt(v.level)+1,page:1,p:param});
						res.data.data[key].edit=urlCtrl.enaes({id:v.id,p:param});
						if(res.data.data[key].url){
							res.data.data[key].url=res.data.data[key].url+urlCtrl.enaes({id:v.id});
						}
					});
					my.data_list = res.data.data;
					//$( "#datacontent" ).sortable();
				}
			});
		}
		my.list();
		my.search = function(){
			my.params = {
				belongid:my.params.belongid,
				page:1,
				search_str: my.search_str
			};
			searchStr=my.search_str;
			my.list();
		};
		my.del = function(id){
			CRUD.del({id:id}, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					$route.reload();
				}
			});
		};
		my.publishChange = function(id,publish){
		    var params = {
				task:'publishChg',
				id:id,
				publish:publish==1?0:1
			};
			
			CRUD.update(params, "POST").then(function(res){
				
				if(res.status == 1) {
					success(res.msg);
					$route.reload();
				}
			});
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
	}	
}]).controller('midmenu_dir',['$rootScope','$scope','$http','$location','$route','$translate','CRUD','urlCtrl',function($rootScope,$scope, $http, $location,$route,$translate,CRUD,urlCtrl) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/midmenu/api.php");
		
		var id=!param.id ? '' : param.id;
		my.backhash=urlCtrl.enaes(param.p);
		if(id){
			var params = {
				task:'detail',
				id:id
			};
			my.detail = function() {
				CRUD.detail(params, "GET").then(function(res){
					if(res.status == 1) {
						my.midmenu_dtl=res.data;
						my.midmenu_dtl.publish=parseInt(my.midmenu_dtl.publish);
					}
				});
			}
			my.detail();
		}
	}
	my.submit=function(){
		var params = {
			task:'dir_update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.midmenu_dtl.name,
			publish:my.midmenu_dtl.publish
		};
		
		CRUD.update(params, "POST").then(function(res){
			if(res.status == 1) {
				success(res.msg);
				urlCtrl.go("midmenu_list",param);
			}
		});
	}
	
}]).controller('midmenu_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','urlCtrl','CRUD','$uibModal',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,urlCtrl,CRUD,$uibModal) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.content_list=[];
	var uploader=[];
	if(param){
		my.midmenu_dtl={level:param.p.level,belongid:param.p.belongid};
		CRUD.setUrl("components/midmenu/api.php");
		var id=!param.id ? '' : param.id;
		my.backhash=urlCtrl.enaes(param.p);
		if(id){
			var params = {id:id};
			my.detail = function() {
				CRUD.detail(params, "GET").then(function(res){
					if(res.status == 1) {
						
						my.midmenu_dtl=res.data;
						if(my.midmenu_dtl.linktype == "database") {
							CRUD.getDBPagePath(my.midmenu_dtl.tablename, my.midmenu_dtl.databaseid)
							.then(function(res) {
								my.midmenu_dtl.path = $translate.instant(my.midmenu_dtl.databasename) + res.path;
							});
						}
						my.midmenu_dtl.url=res.data.url+urlCtrl.enaes({id:res.data.id});
					}
				});
			}
			my.detail();
		}
		
	};
	
	
	my.submit=function(){
		
		CRUD.update(my.midmenu_dtl, "POST").then(function(res){
			if(res.status == 1) {
				
				success(res.msg);
				urlCtrl.go("midmenu_list",param.p);
			}
		});
	};
	
	my.select_page = function(size) {
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'SelectPageModal.html',
			controller: 'midmenu_modal',
			backdrop: 'static',
			controllerAs: 'ctrl',
			size: size,
			resolve: {
				items: function () {
					return $scope.items;
				}
			}
	    });
	
		modalInstance.result.then(function (selectedItem) {
			
			my.midmenu_dtl.tablename = selectedItem.tablename;
			my.midmenu_dtl.databasename = selectedItem.databasename;
			my.midmenu_dtl.databaseid = selectedItem.databaseid;
			CRUD.getDBPagePath(my.midmenu_dtl.tablename, my.midmenu_dtl.databaseid)
			.then(function(res) {
				my.midmenu_dtl.path = $translate.instant(my.midmenu_dtl.databasename) + res.path;
			});
		});
	};
	
	
	$(".filestyle").filestyle({buttonName: "btn-primary", buttonText: "瀏覽", buttonBefore: true, iconName: "fa fa-folder-open-o"});
	$("#midmenu_picture").change(function (event){
		if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                my.midmenu_dtl.img = e.target.result;
                my.midmenu_dtl.isuploadimg = true;
            	$scope.$apply();
            }
            
            reader.readAsDataURL(event.target.files[0]);
        }
	});
	
}]).controller('midmenu_modal', ['$rootScope', '$scope', '$translate', 'CRUD', 'urlCtrl', '$uibModalInstance', 'items', '$filter', function($rootScope, $scope, $translate, CRUD, urlCtrl, $uibModalInstance, items, $filter) {
	var my = this;
	
	
	var selecteddata = null;
	
	my.selectlist = [];
	my.selected = [];
	my.init = function(){
		CRUD.getDBPageRootList().then(function(res) {
			var tmplist = {};
			angular.forEach(res, function(v, k) {
				if(v.dbpage == "true") {
					v.name = k;
					tmplist[k] = v;
				}	
				if(angular.isObject(v.child)) {
					angular.forEach(v.child, function(v,k) {
						if(v.dbpage == "true") {
							v.name = k;
							tmplist[k] = v;
						}	
					});
				}
			});
			my.selectlist.push(tmplist);
		});
	}
	
	my.select = function(level) {
		var param = {};
		//console.log(my.selected[level]);
		if(my.selected[level]) {
			var slength = my.selectlist.length;
			for(var i = 0; i < (slength - (level + 1)); i++) {
				my.selectlist.pop();
			}
			if(level == 0 || my.selected[level].pagetype == "dir"){
				//root 或是還有子節點的時後 
				param.level = level;
				param.tablename = my.selected[0].tablename;
				param.param = my.selected[level].param;
				if(my.selected[level].pagetype == "dir") {
					param.belongid = my.selected[level].id;
				}
				CRUD.getDBPageLeafList(param).then(function(res) {
					my.selectlist.push(res);
					selecteddata = null;
				});
			} else {
				//選擇到可連結的頁面
				var name = 'lg_' + my.selected[0].name + '.' + my.selected[0].name;
				selecteddata = {
					tablename: my.selected[0].tablename,
					databasename: name,
					databaseid: my.selected[level].id
				}
			}
		}
	}
	
	my.confirm = function() {
		if(selecteddata) {
			$uibModalInstance.close(selecteddata);
		} else {
			error(msgStyle($filter('translate')('lg_main.select_page_error')));
		}
	}
	
	my.cancel = function() {
    	$uibModalInstance.dismiss('cancel');
	}
	
	my.init();
}]);

