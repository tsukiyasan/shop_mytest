app.controller('productType_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/productType/api.php");
		
		my.funcPerm = $rootScope.funclist['productType'];
		
		var level=param.level;
		if(!level)level=1;
		my.level=level;
		my.search_str=param.search_str;
		my.params = {
				belongid:!param.belongid ? 'root' : param.belongid,
				page: !param.page ? 1 : param.page,
				search_str:!param.search_str ? '' : param.search_str,
				level:  !my.level ? 1 : my.level
			};
		//返回上一層的hash	
		my.backhash=urlCtrl.enaes(param.p);
		//新增的hash
		my.odrhash=urlCtrl.enaes({component:'productType',p:my.params});
		my.newhash=urlCtrl.enaes({p:my.params});
		my.data_list = [];
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res){
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					//加上給下一層的hash					
					angular.forEach(res.data.data,function(v,key){
						res.data.data[key].param=urlCtrl.enaes({belongid:v.id,level: parseInt(v.level)+1,page:1,p:my.params});
						res.data.data[key].edit=urlCtrl.enaes({id:v.id,p:my.params});
						res.data.data[key].gopro=urlCtrl.enaes({ptid:v.id,belongid:$scope.menulist.product.child.products.param.belongid,instockMode:$scope.menulist.product.child.products.param.instockMode});
						if(res.data.data[key].url){
							res.data.data[key].url=res.data.data[key].url+urlCtrl.enaes({id:v.id});
						}
					});
					my.data_list = res.data.data;
					if(res.data.belongname && res.data.belongname != 'null')
					{
						my.belongname = res.data.belongname;
					}
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
			
			if(my.funcPerm.D == 'true')
			{
				CRUD.del({id:id}, "POST").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						$route.reload();
					}
				});
			}
			
			
		};
		my.publishChange = function(id,publish){
		    var params = {
				task:'publishChg',
				id:id,
				publish:publish=='1'?'0':'1'
			};
			
			if(my.funcPerm.U == 'true')
			{
				CRUD.update(params, "POST").then(function(res){
				
					if(res.status == 1) {
						success(res.msg);
						$route.reload();
					}
				});
			}
			
			
		};	
		$scope.$watch('allCB',function(n,o){
			angular.forEach(my.listCB,function(v,k){
				my.listCB[k]=n;
			});
		});
		my.operateChg = function(v){
			
			if(  ((v==1 || v==2) && my.funcPerm.U == 'true') || (v==3 && my.funcPerm.D == 'true') )
			{
				
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
	    			.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
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
				
			}
			
			
			
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
}]).controller('productType_dir',['$rootScope','$scope','$http','$location','$route','$translate','CRUD','urlCtrl',function($rootScope,$scope, $http, $location,$route,$translate,CRUD,urlCtrl) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.textList = [];
	my.nameList = [];
	if(param){
		CRUD.setUrl("components/productType/api.php");
		
		my.funcPerm = $rootScope.funclist['productType'];
		
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
						my.productType_dtl=res.data;
						my.nameList=res.nameList;
					}
				});
			}
			my.detail();
		}
	}
	
	my.init = function() {
		CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
			if(res.status == 1) {
				my.textList = res.textList;
				if(!id)
				{
					angular.forEach(my.textList,function(v,k){
						my.nameList[v.code] = '';
					});
				}
			}
		});
	}
	my.init();
	
	my.submit=function(){
		
		//var name =  my.productType_dtl.name;		
		//if(!name)
		//{
		//	error($translate.instant('lg_productType.name')+$translate.instant('lg_main.empty'));
		//	return false;
		//}
		
		var params = {
			task:'dir_update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.productType_dtl.name,
			publish:my.productType_dtl.publish
		};
		
		var nameChk = true;
		angular.forEach(my.textList,function(v,k){
			params['name_'+v['code']] = my.nameList[v['code']];
			if(!my.nameList[v['code']])
			{
				nameChk = false;
			}
		});
		if(!nameChk)
		{
			error($translate.instant('lg_productType.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("productType_list",param);
				}
			});
		}
		
		
	}
	
}]).controller('productType_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','urlCtrl','CRUD',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,urlCtrl,CRUD) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.content_list=[];
	var uploader=[];
	my.textList = [];
	my.nameList = [];
	if(param){
		my.productType_dtl={};
		
		CRUD.setUrl("components/productType/api.php");
		
		my.funcPerm = $rootScope.funclist['productType'];
		
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
						
						my.productType_dtl=res.data;
						my.nameList=res.nameList;
						
						if(my.productType_dtl.var1 == 'null')
						{
							my.productType_dtl.var1 = '';
						}
						
						my.productType_dtl.url=res.data.url+urlCtrl.enaes({id:res.data.id});
						seteditor('productType_content');
					}
				});
			}
			my.detail();
		}else{
			seteditor('productType_content');
		}
		
	}
	
	my.init = function() {
		CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
			if(res.status == 1) {
				my.textList = res.textList;
				if(!id)
				{
					angular.forEach(my.textList,function(v,k){
						my.nameList[v.code] = '';
					});
				}
			}
		});
	}
	my.init();
	
	my.submit=function(){
				
		//var name =  my.productType_dtl.name;		
		//if(!name)
		//{
		//	error($translate.instant('lg_productType.name')+$translate.instant('lg_main.empty'));
		//	return false;
		//}
		
		var content=my.productType_dtl.content;
		if(CKEDITOR.instances.productType_content){
			content=CKEDITOR.instances.productType_content.getData();
		}
		
		var params = {
			task:'update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.productType_dtl.name,
			publish:my.productType_dtl.publish,
			content:content,
			var1:my.productType_dtl.var1,
			formchk:my.productType_dtl.formchk,
			type:my.productType_dtl.type
		};
		
		var nameChk = true;
		angular.forEach(my.textList,function(v,k){
			params['name_'+v['code']] = my.nameList[v['code']];
			if(!my.nameList[v['code']])
			{
				nameChk = false;
			}
		});
		if(!nameChk)
		{
			error($translate.instant('lg_productType.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					
					success(res.msg);
					urlCtrl.go("productType_list",param.p);
				}
			});
			
		}
		
	}
	
	
}]);

