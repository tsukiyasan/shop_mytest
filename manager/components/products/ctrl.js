app.controller('products_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter','store',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter,store) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/products/api.php");
		
		my.funcPerm = $rootScope.funclist['products'];
		
		//instockMode庫存模式 single:單一庫存 multiple:規格庫存
		
		my.title=store.storage.get('menukey');
		var level=param.level;
		if(!level)level=1;
		my.level=level;
		my.search_str=param.search_str;
		my.params = {
				belongid:!param.belongid ? 'root' : param.belongid,
				page: !param.page ? 1 : param.page,
				search_str:!param.search_str ? '' : param.search_str,
				ptid: param.ptid,
				bonusChk: !param.bonusChk?0:param.bonusChk,
				freeProChk: !param.freeProChk?0:param.freeProChk,
				amtProChk: !param.amtProChk?0:param.amtProChk,
				instockMode: $rootScope.conf_instock_mode
			};	
			
		//返回上一層的hash	
		my.backhash=urlCtrl.enaes(param.p);
		//新增的hash
		my.odrhash=urlCtrl.enaes({component:'products',p:param});
		my.newhash=urlCtrl.enaes({p:param});
		my.data_list = [];
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res){
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					//加上給下一層的hash
					angular.forEach(res.data.data,function(v,key){
						res.data.data[key].param=urlCtrl.enaes({belongid:v.id,level:v.level+1,page:1,p:my.params});
						res.data.data[key].edit=urlCtrl.enaes({id:v.id,p:my.params});
						if(res.data.data[key].url){
							res.data.data[key].url=res.data.data[key].url+urlCtrl.enaes({id:v.id});
						}
					});
					my.data_list = res.data.data;
					my.type_list = res.data.typelist;
					my.nowtype = res.data.nowtype;
				}
			});
		}
		my.list();
		my.search = function(){
			my.params = {
				belongid:my.params.belongid,
				page:1,
				ptid: param.ptid,
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
				publish:publish==1?0:1
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
		
		my.typeChg=function(t){
			
			my.params.amtProChk = 0;
			my.params.bonusChk = 0;
			my.params.freeProChk = 0;
			my.params.ptid = 0;
			
			if(t == 'addpro')
			{
				my.params.amtProChk = 1;
			}
			else if(t == 'bonuspro')
			{
				my.params.bonusChk = 1;
			}
			else if(t == 'freepro')
			{
				my.params.freeProChk = 1;
			}
			else
			{
				my.params.ptid=t;
			}
			
			urlCtrl.go('products_list',my.params);
			
		};
		
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
		
		my.copy = function(id){
			
			 if(my.funcPerm.C == 'true')
			 {
			 	
			 	//確定複製此商品？
				alertify.confirm($translate.instant('lg_products.copy_product'))
    			.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
    			.set({ labels : { ok: $filter('translate')('lg_main.yes') ,cancel:$filter('translate')('lg_main.no')} })
    			.set('onok', function(closeEvent){
			
					var params = {
						task:'proCopy',
						id:id,
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
		
		
		my.import_products=function(){
				$("#import_product").modal("show");
		};
	}	
}]).controller('products_dir',['$rootScope','$scope','$http','$location','$route','$translate','CRUD','urlCtrl','store',function($rootScope,$scope, $http, $location,$route,$translate,CRUD,urlCtrl,store) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/products/api.php");
		
		my.funcPerm = $rootScope.funclist['products'];
		
		my.title=store.storage.get('menukey');
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
						my.products_dtl=res.data;
						
					}
				});
			}
			my.detail();
		}
	}
	my.submit=function(){
		
		var name =  my.products_dtl.name;		
		if(!name)
		{
			error($translate.instant('lg_products.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		var params = {
			task:'dir_update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:encodeURI(my.products_dtl.name),
			publish:my.products_dtl.publish,
			need_tax:my.products_dtl.need_tax
		};
		
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("products_list",param);
				}
			});
		}
		
		
	}
	
}]).controller('products_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','urlCtrl','CRUD','$uibModal','store',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,urlCtrl,CRUD,$uibModal,store) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.content_list=[];
	var uploader=[];
	my.textList = [];
	my.nameList = [];
	my.var03List = [];
	my.pvbvratio = 0;
	
	if(param){
		my.products_dtl={var03:'',pv:0};
		my.title=store.storage.get('menukey');
		CRUD.setUrl("components/products/api.php");
		
		my.funcPerm = $rootScope.funclist['products'];
		
		var id=!param.id ? '' : param.id;
		my.backhash=urlCtrl.enaes(param.p);
		if(param.p.bonusChk){
			my.products_dtl.bonusChk=param.p.bonusChk.toString();
			
		}
		if(param.p.freeProChk){
			my.products_dtl.freeProChk=param.p.freeProChk.toString();
			
		}
		
		my.imagesizelimit = 1048576;
		my.productimg = [];
		$scope.previewImage = [];
		
		var imagelimit = 4;
		my.imagelist = [];
		for(var i = 1; i <= imagelimit; i++) {
			my.imagelist.push(i);
		}
		
		//庫存模式 single:單一庫存 multiple:規格庫存
		my.instockMode = !$rootScope.conf_instock_mode ? 'single' : $rootScope.conf_instock_mode;
		$scope.proNum_arr=[];
		//來源
		my.from = !param.from?'products':param.from;
		my.format_mode=2;
		if(id){
			var params = {
				task:'detail',
				id:id,
				instockMode:my.instockMode
			};
			
			my.detail = function() {
				CRUD.detail(params, "GET").then(function(res){
					if(res.status == 1) {
						
						my.products_dtl=res.data;
						my.nameList=res.nameList;
						my.var03List=res.var03List;
						
						my.products_dtl.url=res.data.url+urlCtrl.enaes({id:res.data.id});
						
						if(my.format_mode==2){
							$scope.setProFormat(id);
						}
						$scope.setProFormat2(id);
					}
				});
			}
			my.detail();
		}
		
		
		//取得商品類別
		my.getTypeList = function() {
			var PTArrays =[];
			var params_TL = {
				task:'getTypeList',
				id:id
			};
			
			CRUD.list(params_TL, "GET").then(function(res){
				if(res.data.status == 1) {					
					
					my.pvbvratio = res.data.pvbvratio?res.data.pvbvratio:0;
					
					console.log(my.pvbvratio);
					
					if(res.data.master)
					{
						angular.forEach(res.data.master,function(v,key){
														
							if(v.pagetype == 'page')
							{
								var selected = [];
								if(res.data.selected[v.id])
								{
									selected = res.data.selected[v.id];
								}
								
								PTArrays.push(['page',[v.id],v.name,selected,[],[],[],true]);
								
							}
							else if(v.pagetype == 'dir')
							{
								var name = v.name;
								var item = [];
								var itemname = [];
								var selected = [];
								var selected_r = [];
								if(res.data.selected[v.id])
								{
									selected = res.data.selected[v.id];
									selected_r = [v.id];
								}
								
								if(res.data.detail[v.id])
								{
									angular.forEach(res.data.detail[v.id],function(v2,key2){
										item.push(v2.id); 
										itemname.push(v2.name); 
									});
									
									PTArrays.push(['dir',[v.id],v.name,selected_r,item,itemname,selected,true]);
								}
							}
							
						});
					}
				}
				
				my.proTypeArrays = PTArrays;
				
			});
		}
		my.getTypeList();
			
	}

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
	
	my.isIndeterminate = function(item,list) {
		return (list.length !== 0 &&
		list.length !== item.length);
	};
	
	my.isChecked = function(item,list) {
		return list.length === item.length;
	};
		
	my.toggleAll = function(item,list,index) {
		
		
		if (list.length === item.length) {
			my.proTypeArrays[index][6] = [];
			my.proTypeArrays[index][3] = [];
		} else if (list.length === 0 || list.length > 0) {
			my.proTypeArrays[index][6] = my.proTypeArrays[index][4].slice(0);
		}
		console.log(my.proTypeArrays);
	};
	
	my.isOpen = function(index) {	
		return my.proTypeArrays[index][7];
	};
	
	my.toggleOpen = function(index) {	
		if(my.proTypeArrays[index][7])
		{
			my.proTypeArrays[index][7] = false;
		}
		else
		{
			my.proTypeArrays[index][7] = true;
		}
	};
	
	//新增
	my.addInstockTR=function(){
		var obj = {id:"", name:"", instock:"", safetystock:""};
		my.proNum_arr.push(obj);
	}
	
	//刪除
	my.deleteInstockTR=function(index){		
			
		alertify.confirm(msgStyle($translate.instant('lg_products.specification_delete_msg')))
		.setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_main.batch_opt_msg'))
		.set({ labels : { ok: $translate.instant('lg_main.yes') ,cancel:$translate.instant('lg_main.no')} })
		.set('onok', function(closeEvent){ 
			
			if(my.proNum_arr[index].id)
			{
				var params_DI = {
					task:'delInstock',
					id:my.proNum_arr[index].id
				};	
				CRUD.list(params_DI, "GET").then(function(res){
					if(res.data.status == 1) {					
						my.proNum_arr.splice(index,1);
					}
				});
			}
			else
			{
				my.proNum_arr.splice(index,1);
			}
			
		});
		
	}
	
	my.init = function() {
		CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
			if(res.status == 1) {
				my.textList = res.textList;
				if(!id)
				{
					angular.forEach(my.textList,function(v,k){
						my.nameList[v.code] = '';
						my.var03List[v.code] = '';
					});
				}
			}
		});
	}
	my.init();
	
	my.submit=function(){
		
					
		//if(!my.products_dtl.name)
		//{
		//	error($translate.instant('lg_products.name')+$translate.instant('lg_main.empty'));
		//	return false;
		//}
		
		var nameChk = true;
		angular.forEach(my.textList,function(v,k){
			if(!my.nameList[v['code']])
			{
				nameChk = false;
			}				
		});
		if(!nameChk)
		{
			error($translate.instant('lg_products.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		
		if(!my.products_dtl.highAmt)
		{
			error($translate.instant('lg_products.highAmt')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		if(isNaN(my.products_dtl.highAmt))
		{
			error($translate.instant('lg_products.highAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		/*
		if(!my.products_dtl.siteAmt)
		{
			error($translate.instant('lg_products.siteAmt')+$translate.instant('lg_main.empty'));
			return false;
		}
		*/
		if(my.products_dtl.siteAmt && isNaN(my.products_dtl.siteAmt))
		{
			error($translate.instant('lg_products.siteAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(my.products_dtl.oriAmt && isNaN(my.products_dtl.oriAmt))
		{
			error($translate.instant('lg_products.oriAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(my.products_dtl.instock && isNaN(my.products_dtl.instock))
		{
			error($translate.instant('lg_products.instock')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(!$scope.format1_arr)
		{
			error($translate.instant('lg_products.format1')+$translate.instant('lg_main.empty'));
			return false;
		}
		if(!$scope.format2_arr)
		{
			error($translate.instant('lg_products.format2')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		if(!id)
		{
			if($scope.previewImage.length == 0)
			{
				error($translate.instant('lg_products.upload_image')+$translate.instant('lg_main.empty'));
				return false;
			}
		}
		
		
		
		var content=my.products_dtl.var03;
		if(CKEDITOR.instances.products_content){
			content=CKEDITOR.instances.products_content.getData();
		}
		// if(!content)
		// {
		// 	error($translate.instant('lg_products.var03')+$translate.instant('lg_main.empty'));
		// 	return false;
		// }	
		
		my.products_dtl.bv = my.products_dtl.pv * my.pvbvratio;
		
		var params = {
			task:'update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.products_dtl.name,
			publish:my.products_dtl.publish,
			need_tax:my.products_dtl.need_tax,
			hotChk:my.products_dtl.hotChk,
			usebonus:my.products_dtl.usebonus,
			// var03:content,
			var04:my.products_dtl.var04,
			var05:my.products_dtl.var05,
			img: $scope.previewImage,
			proCode:my.products_dtl.proCode,
			safetystock:my.products_dtl.safetystock,
			instock:my.products_dtl.instock,
			highAmt:my.products_dtl.highAmt,
			siteAmt:my.products_dtl.siteAmt,
			oriAmt:my.products_dtl.oriAmt,
			proTypeArrays:my.proTypeArrays,
			proNum_arr:my.proNum_arr,
			format1_arr:$scope.format1_arr,
			format2_arr:$scope.format2_arr,
			checked2_instock:$scope.checked2_instock,
			checked2_procode:$scope.checked2_procode,
			amtProChk:my.products_dtl.amtProChk,
			amtProAmt:my.products_dtl.amtProAmt,
			freeProChk:my.products_dtl.freeProChk,
			bundleProChk:my.products_dtl.bundleProChk,
			bonusChk:my.products_dtl.bonusChk,
			bonusAmt:my.products_dtl.bonusAmt,
			pv:my.products_dtl.pv,
			bv:my.products_dtl.bv,
			ccv: my.products_dtl.ccv,
			nccbChk: my.products_dtl.nccbChk,
			forTW: my.products_dtl.forTW,
			notomChk: my.products_dtl.notomChk,
			newDate:my.products_dtl.newDate
		};
		
		angular.forEach(my.textList,function(v,k){
			params['name_'+v['code']] = my.nameList[v['code']];
			if(CKEDITOR.instances['products_content'+v['code']]){
				params['var03_'+v['code']] = CKEDITOR.instances['products_content'+v['code']].getData();
			}				
		});
		
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go(my.from+"_list",param.p);
				}
			});
			
		}
		
		
	}
	

	
	my.imgdel=function(num){
		
		CRUD.del({task:'imgdel',id:id,num:num}, "POST").then(function(res){
			if(res.status == 1) {
				success(res.msg);
				$route.reload();
			}
		});
	};
	
	
	my.addProType = function(size) {
		var modalInstance = $uibModal.open({
			animation: true,
			templateUrl: 'AddProductTypeModal.html',
			controller: 'ProductType_modal',
			backdrop: 'static',
			controllerAs: 'ctrl',
			size: size,
			resolve: {
				items: function () {
					return $scope.items;
				}
			}
		});
	
		modalInstance.result.then(function () {
			my.getTypeList();
		});
	}
	
	
}]).controller('ProductType_modal', ['$rootScope', '$scope', '$translate', 'CRUD', 'urlCtrl', '$uibModalInstance', 'items', '$filter', function($rootScope, $scope, $translate, CRUD, urlCtrl, $uibModalInstance, items, $filter) {
	var my = this;
	
	CRUD.setUrl("components/products/api.php");
	//var selecteddata = null;
	
	//my.selectlist = [];
	//my.selected = [];
	my.TypeListMArr = [];
	my.init = function(){
		
		var params = {
			task:'getTypeListM'
		};
		var PTArrays =[];
		my.getTypeListM = function() {
			CRUD.list(params, "GET").then(function(res){
				my.TypeListMArr = res.data.data;
			});
		};
		my.getTypeListM();
	}
	
	
	my.confirm = function() {
				
		if(my.TypeListM && my.TypeListMName)
		{
			var params = {
				task:'TypeListM_add',
				id:my.TypeListM,
				name:my.TypeListMName
				
			};
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					$uibModalInstance.close();
				}
			});
		}
	}
	
	
	my.cancel = function() {
    	$uibModalInstance.dismiss('cancel');
	}
	
	my.init();
}]);

