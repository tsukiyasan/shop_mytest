app.controller('advrolls_list', ['$rootScope', '$scope', 'urlCtrl', '$location', '$route', '$routeParams', 'CRUD', '$translate', function($rootScope, $scope, urlCtrl, $location, $route, $routeParams, CRUD, $translate) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	if(param){
		CRUD.setUrl("components/advrolls/api.php");
		
		my.funcPerm = $rootScope.funclist['advrolls'];
		
		my.data_list = [];
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? {name: ""} : param.search,
			orderby: !param.orderby ? {newsDate: "asc", pubDate: "desc"} : param.orderby
		}
		my.odrhash = urlCtrl.enaes({component:'advrolls', p:param});
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		};
		
		my.test = function() {
			console.log("repeat complete");
		}
		
		my.delete = function(id){
			
			if(my.funcPerm.D == 'true')
			{
				CRUD.del({id:id}, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						$route.reload();
					}	
				});
			}
			
		};
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/advrolls_page", param);
		}
		
		
		my.publishChange = function(id, publish){
		    
		    if(my.funcPerm.U == 'true')
		    {
		    	var params = {
					id: id,
					publish: 1 - publish
				};
				
				
				CRUD.update(params, "POST").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						my.list();
					}
				});
		    }
		    
		};	
		
		my.list();
		
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
		my.batchOperate = function(action) {
			
			if( ((action == "open" || action == "close") && my.funcPerm.U == 'true' ) || (action == 'delete' && my.funcPerm.D == 'true' ) )
			{
				if(my.selected.length > 0) {
					var selectedid = [];
					angular.forEach(my.selected, function(value, key) {
						selectedid.push(value.id);
					});
					console.log(selectedid);
					alertify
					.confirm($translate.instant("lg_main." + action + "confirm"))
					.setHeader("<i class='fa fa-help-circle'></i> " + $translate.instant("lg_main.batchconfirm"))
					.set({labels: {ok: $translate.instant("lg_main.yes"), cancel: $translate.instant("lg_main.no")}})
					.set('onok', function(closeEvent) {
	    				var params = {
							task:'batchOperate',
							id: selectedid,
							action: action
						};
						CRUD.update(params, "POST")
						.then(function(res) {
							if(res.status == 1) {
								success($translate.instant("lg_main." + action + "success"));
								my.list();
								my.selected = [];
							} else {
								error($translate.instant("lg_main." + action + "fail"));
							}
						})
					});
				} else {
					message($translate.instant("lg_main.nochoice"));
				}
				
			}
			
		}
	}
	
	
}])

.controller('advrolls_page', ['$rootScope','$scope','urlCtrl','$location','$route','$routeParams','CRUD','$translate',function($rootScope,$scope, urlCtrl, $location,$route,$routeParams,CRUD,$translate) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	
	console.log(param);
	
	
	if(param){
		CRUD.setUrl("components/advrolls/api.php");
		
		my.funcPerm = $rootScope.funclist['advrolls'];
		
		my.advrolls={};
		my.advrollimg = [];
		$scope.previewImage = [];
		
		var imagelimit = 4;
		my.imagelist = [];
		for(var i = 1; i <= imagelimit; i++) {
			my.imagelist.push(i);
		}
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.advrolls = res.data;

					if(my.advrolls.linktype == "database") {
						CRUD.getDBPagePath(my.advrolls.tablename, my.advrolls.databaseid)
						.then(function(res) {
							my.advrolls.path = $translate.instant(my.advrolls.databasename) + res.path;
							console.log(my.advrolls.path);
						});
					}
				}
			});
		}
		
		my.init = function() {
			
			CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
				if(res.status == 1) {
					my.textList = res.textList;
				}
			});
		}
		
		my.init();
		
		if(param.id) {
			my.actionType=1;
			my.params = {
				id: !param.id ? null : param.id
			}
			my.detail();
		} else {
			my.advrolls = {
				linktype: 'link'
			}
			my.actionType=0;
			
		}
		my.submit = function() {
			
			if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
			{
				my.params = {
					id: !param.id ? null : param.id,
					name: my.advrolls.name,
					publish: my.advrolls.publish,
					img: $scope.previewImage,
					tablename: my.advrolls.tablename,
					databaseid: my.advrolls.databaseid,
					databasename: my.advrolls.databasename,
					linktype: my.advrolls.linktype,
					linkurl: my.advrolls.linkurl
				}
				
				if(!my.params.name)
				{
					error($translate.instant('lg_main.title')+$translate.instant('lg_main.empty'));
					return false;
				}
				
				if(!my.params.id)
				{
					if($scope.previewImage.length == 0)
					{
						error($translate.instant('lg_advrolls.advrolls_upload_image')+$translate.instant('lg_main.empty'));
						return false;
					}
				}
				
				CRUD.update(my.params, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
			}
			
		}
		
		my.cancel = function() {
			urlCtrl.go("/advrolls_list", listparams);
		}
	}
	
	
}]);