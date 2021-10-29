app.controller('treemenu_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/treemenu/api.php");
		
		my.funcPerm = $rootScope.funclist['treemenu'];
		
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
		my.odrhash=urlCtrl.enaes({component:'treemenu',p:param});
		my.newhash=urlCtrl.enaes({p:param});
	
		
		my.data_list = [];
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
}]).controller('treemenu_dir',['$rootScope','$scope','$http','$location','$route','$translate','CRUD','urlCtrl',function($rootScope,$scope, $http, $location,$route,$translate,CRUD,urlCtrl) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.textList = [];
	my.nameList = [];
	if(param){
		CRUD.setUrl("components/treemenu/api.php");
		
		my.funcPerm = $rootScope.funclist['treemenu'];
		
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
						my.treemenu_dtl=res.data;
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
		var params = {
			task:'dir_update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.treemenu_dtl.name,
			publish:my.treemenu_dtl.publish
		};
		
		angular.forEach(my.textList,function(v,k){
			params['name_'+v['code']] = my.nameList[v['code']];
		});
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("treemenu_list",param);
				}
			});
			
		}
		
		
	}
	
}]).controller('treemenu_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','urlCtrl','CRUD',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,urlCtrl,CRUD) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.content_list=[];
	var uploader=[];
	my.textList = [];
	my.nameList = [];
	my.contentList = [];
	if(param){
		
		
		CRUD.setUrl("components/treemenu/api.php");
		
		my.funcPerm = $rootScope.funclist['treemenu'];
		
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
						my.treemenu_dtl=res.data;
						my.nameList=res.nameList;
						my.contentList=res.contentList;
					}
				});
			}
			my.detail();
		}else{
			my.treemenu_dtl={content:''};
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
						my.contentList[v.code] = '';
					});
				}
			}
		});
	}
	my.init();
	
	my.submit=function(){
		
		var content=my.treemenu_dtl.content;
		if(CKEDITOR.instances.treemenu_content){
			content=CKEDITOR.instances.treemenu_content.getData();
		}
		
		var params = {
			task:'update',
			id:id,
			belongid:!param.p.belongid ? 'root' : param.p.belongid,
			level:!param.p.level ? 1 : param.p.level,
			name:my.treemenu_dtl.name,
			publish:my.treemenu_dtl.publish,
			content:content,
			var1:my.treemenu_dtl.var1
			//type:my.treemenu_dtl.type,
			//img:my.img,
			//link:my.treemenu_dtl.var
		};
		
		angular.forEach(my.textList,function(v,k){
			params['name_'+v['code']] = my.nameList[v['code']];
			if(CKEDITOR.instances['treemenu_content'+v['code']]){
				params['content_'+v['code']] = CKEDITOR.instances['treemenu_content'+v['code']].getData();
			}				
		});
		
		if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
		{
			
			CRUD.update(params, "POST").then(function(res){
				if(res.status == 1) {
					success(res.msg);
					urlCtrl.go("treemenu_list",param.p);
				}
			});
			
		}
		
		
	}
	
	/*
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
	*/
		
}]);

