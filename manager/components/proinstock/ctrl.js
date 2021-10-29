app.controller('proinstock_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/proinstock/api.php");
		
		my.funcPerm = $rootScope.funclist['proinstock'];
		
		//instockMode庫存模式 single:單一庫存 multiple:規格庫存
		
		var level=param.level;
		if(!level)level=1;
		my.level=level;
		my.search_str=param.search_str;
		my.params = {
				belongid:!param.belongid ? 'root' : param.belongid,
				page: !param.page ? 1 : param.page,
				level:!level?1:level,
				search_str:!param.search_str ? '' : param.search_str,
				ptid: param.ptid,
				instockMode: $rootScope.conf_instock_mode,
				order:!param.order ? '' : param.order
			};	
			
		//返回上一層的hash	
		my.backhash=urlCtrl.enaes(param.p);
		
		my.instockMode = my.params.instockMode;
		
		//新增的hash
		my.odrhash=urlCtrl.enaes({component:'proinstock',p:param});
		my.newhash=urlCtrl.enaes({p:param});
		my.listCB = [];
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res){
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					//加上給下一層的hash
					angular.forEach(res.data.data,function(v,key){
						res.data.data[key].param=urlCtrl.enaes({belongid:v.id,level:parseInt(v.treelevel)+1,page:1,p:param});
						res.data.data[key].edit=urlCtrl.enaes({id:v.id,p:param,from:'proinstock'});
						if(res.data.data[key].url){
							res.data.data[key].url=res.data.data[key].url+urlCtrl.enaes({id:v.id});
						}
					});
					my.data_list = res.data.data;
					if(level>1){
						my.format1_title=my.data_list[0].format1_type;
						my.format2_title=my.data_list[0].format2_type;
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
		
		my.orderChg=function(t){
			my.params.order = t;
			my.list();
		};

		my.publishChange = function(id,publish){
		    var params = {
				task:'publishChg',
				id:id,
				publish:publish
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
					angular.forEach(my.listCB,function(v,k){
						if(v==true){
							listCB.push(k);
						}
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
		

		
		my.instockchg = function(id,instock){
			
			if(!isNaN(instock))
			{
				var tmp = {
					task:'instockchg',
					id:id,
					instock:instock,
					instockMode:my.instockMode
				};
				
				if(my.funcPerm.U == 'true')
				{
					
					CRUD.update(tmp, "GET").then(function(res){
						if(res.status == 1) {
							success(res.msg);
							my.list();
						}
					});
					
				}

			}
		};
		
		
		my.instockchkChange = function(id,instockchk){
			
			var tmp = {
				task:'instockchkChange',
				id:id,
				instockchk: 1 - instockchk
			};
			
			if(my.funcPerm.U == 'true')
			{
				
				CRUD.update(tmp, "GET").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						my.list();
					}
				});
				
			}
		};
		
	}	
}]).controller('proinstock_dir',['$rootScope','$scope','$http','$location','$route','$translate','CRUD','urlCtrl',function($rootScope,$scope, $http, $location,$route,$translate,CRUD,urlCtrl) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/proinstock/api.php");
		
		my.funcPerm = $rootScope.funclist['proinstock'];
		
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
						my.proinstock_dtl=res.data;
						
					}
				});
			}
			my.detail();
		}
	}
	my.submit=function(){
		
		var name =  my.proinstock_dtl.name;		
		if(!name)
		{
			error($translate.instant('lg_proinstock.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		var params = {
			task:'dir_update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:encodeURI(my.proinstock_dtl.name),
			publish:my.proinstock_dtl.publish
		};
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("proinstock_list",param);
				}
			});
			
		}
		
		
	}
	
}]).controller('proinstock_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','urlCtrl','CRUD','$uibModal',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,urlCtrl,CRUD,$uibModal) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.content_list=[];
	var uploader=[];
	if(param){
		my.proinstock_dtl={};
		
		CRUD.setUrl("components/proinstock/api.php");
		
		my.funcPerm = $rootScope.funclist['proinstock'];
		
		var id=!param.id ? '' : param.id;
		my.backhash=urlCtrl.enaes(param.p);
		
		//庫存模式 single:單一庫存 multiple:規格庫存
		my.instockMode = !param.p.instockMode ? 'single' : param.p.instockMode;
				
		if(id){
			var params = {
				task:'detail',
				id:id,
				instockMode:my.instockMode
			};
			my.detail = function() {
				CRUD.detail(params, "GET").then(function(res){
					if(res.status == 1) {
						
						my.proinstock_dtl=res.data;
						my.proinstock_dtl.url=res.data.url+urlCtrl.enaes({id:res.data.id});
						//seteditor('proinstock_content');
						
						my.proNum_arr = res.proNum_arr;					
					}
				});
			}
			my.detail();
		}else{
			//seteditor('proinstock_content');
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
		} else if (list.length === 0 || list.length > 0) {
			my.proTypeArrays[index][6] = my.proTypeArrays[index][4].slice(0);
		}
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
		var obj = {id:"", name:"", instock:""};
		my.proNum_arr.push(obj);
	}
	
	//刪除
	my.deleteInstockTR=function(index){		
			
		alertify.confirm(msgStyle($translate.instant('lg_proinstock.specification_delete_msg')))
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
	
	
	my.submit=function(){
					
		if(!my.proinstock_dtl.name)
		{
			error($translate.instant('lg_proinstock.name')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		if(!my.proinstock_dtl.highAmt)
		{
			error($translate.instant('lg_proinstock.highAmt')+$translate.instant('lg_main.empty'));
			return false;
		}
		
		if(isNaN(my.proinstock_dtl.highAmt))
		{
			error($translate.instant('lg_proinstock.highAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(my.proinstock_dtl.siteAmt && isNaN(my.proinstock_dtl.siteAmt))
		{
			error($translate.instant('lg_proinstock.siteAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(my.proinstock_dtl.oriAmt && isNaN(my.proinstock_dtl.oriAmt))
		{
			error($translate.instant('lg_proinstock.oriAmt')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		if(my.proinstock_dtl.instock && isNaN(my.proinstock_dtl.instock))
		{
			error($translate.instant('lg_proinstock.instock')+$translate.instant('lg_main.num_only'));
			return false;
		}
		
		var content=my.proinstock_dtl.var03;
		if(CKEDITOR.instances.proinstock_content){
			content=CKEDITOR.instances.proinstock_content.getData();
		}
								
		var params = {
			task:'update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.proinstock_dtl.name,
			publish:my.proinstock_dtl.publish,
			var03:content,
			img: my.img,
			code:my.proinstock_dtl.code,
			instock:my.proinstock_dtl.instock,
			highAmt:my.proinstock_dtl.highAmt,
			siteAmt:my.proinstock_dtl.siteAmt,
			oriAmt:my.proinstock_dtl.oriAmt,
			proTypeArrays:my.proTypeArrays,
			proNum_arr:my.proNum_arr
		};
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("proinstock_list",param.p);
				}
			});
			
		}
				
		
	}
	
	/*
	var uploader = new JSImageUploader();
		uploader.setTrigger( btnUpload );	
		uploader.setPreviewHandler(function(event) {
			var preview = document.createElement("div");
				preview.appendChild(event.detail.preview);
			my.img=event.detail.preview.src;	
			files.firstChild.innerHTML = '';
			files.firstChild.appendChild( preview );
		});
	uploader.init();
	*/
	
	var imglistCnt=5;
	my.imglist=[];
	for(var i=1;i<=imglistCnt;i++){
		my.imglist.push(i);
	}
	var uploader=[],upf=[];
	my.img=[];
	$scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
		angular.forEach(my.imglist,function(v,k){
				
				uploader[v] = new JSImageUploader();
				document.getElementById('btnUpload_'+v).setAttribute('num',v);
				uploader[v].setTrigger(document.getElementById('btnUpload_'+v));
				upf[v] = document.getElementById('files_'+v);
				uploader[v].setPreviewHandler(function(event) {
					var preview = document.createElement("div");
					preview.appendChild(event.detail.preview);
					my.img[v]=event.detail.preview.src;	
					upf[event.detail.num].firstChild.innerHTML = '';
					upf[event.detail.num].firstChild.appendChild( preview );
				});
				uploader[v].init();
		});	
 	});
	
	
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
	
	
}]);

