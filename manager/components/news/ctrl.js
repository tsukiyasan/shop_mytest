app.controller('news_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	if(param) {
		CRUD.setUrl("components/news/api.php");
		
		my.funcPerm = $rootScope.funclist['news'];
		
		var path = $location.path();
		my.data_list = [];
		my.params = {
			page: !param.page ? 1 : param.page,
			newsType: !param.newsType ? "topnews" : param.newsType,
			search: !param.search ? {name: "", newsDate: null} : param.search,
			orderby: !param.orderby ? {newsDate: "desc", pubDate: "desc"} : param.orderby
		}
		
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.selected = [];
					my.cnt = res.cnt;
					my.data_list = res.data.data;
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
			//my.params.search.newsDate = my.date.format("YYYY-MM-DD");
			my.list();
		}
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/news_page", param);
		}
		
		my.publishChange = function(id, publish){
		    var params = {
				id: id,
				publish: 1 - publish
			};
			
			if(my.funcPerm.U == 'true')
			{
				CRUD.update(params, "POST").then(function(res){
					if(res.status == 1) {
						success(res.msg);
						my.list();
					}
				});
			}
			
			
		};	
		
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
		
		my.list();
	}
}])

.controller('news_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', '$uibModal', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl, $uibModal) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	
	my.textList = [];
	my.nameList = [];
	my.summaryList = [];
	my.contentList = [];
	
	if(param) {
		CRUD.setUrl("components/news/api.php");
		my.params = {
			id: !param.id ? null : param.id
		}
		
		my.funcPerm = $rootScope.funclist['news'];
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.news_dtl = res.info;
					my.nameList=res.nameList;
					my.summaryList=res.summaryList;
					my.contentList=res.contentList;
					
					$scope.previewImage = my.news_dtl.img;
					
					if(my.news_dtl.linktype == "database") {
						CRUD.getDBPagePath(my.news_dtl.tablename, my.news_dtl.databaseid)
						.then(function(res) {
							my.news_dtl.path = $translate.instant(my.news_dtl.databasename) + res.path;
						});
					}
					
				}
			});
		}
		
		my.submit = function() {
			if(CKEDITOR.instances.news_content){
				my.news_dtl.content = CKEDITOR.instances.news_content.getData();
			}
			
			if(my.news_dtl.databaseid == "" || my.news_dtl.tablename == "") {
				my.news_dtl.linktype = "page";
			}
			
			if(my.news_dtl.img != $scope.previewImage) {
				my.news_dtl.img = $scope.previewImage;
				my.news_dtl.isuploadimg = true;
			}
			
			angular.forEach(my.textList,function(v,k){
				my.news_dtl['name_'+v['code']] = my.nameList[v['code']];
				my.news_dtl['summary_'+v['code']] = my.summaryList[v['code']];
				if(CKEDITOR.instances['news_content'+v['code']]){
					my.news_dtl['content_'+v['code']] = CKEDITOR.instances['news_content'+v['code']].getData();
				}				
			});
			
			if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
			{
				CRUD.update(my.news_dtl, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
			}
			
			
		}
		
		my.cancel = function() {
			urlCtrl.go("/news_list", listparams);
			//urlCtrl.go("-1");
		}
		
		if(param.id) {
			my.detail();
		} else {
			var date = new Date();
			my.news_dtl = {
				newsType: listparams.newsType,
				content: "",
				linkurl: "http://",
				name: "",
				newsDate: date.toISOString().slice(0,10).replace(/-/g,"-"),
				pubDate: "",
				publish: '1',
				linktype: "page",
				tablename: "",
				databaseid: 0,
				databasename: ""
			}
		}
		
		my.init = function() {
			CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
				if(res.status == 1) {
					my.textList = res.textList;
					if(!param.id)
					{
						angular.forEach(my.textList,function(v,k){
							my.nameList[v.code] = '';
							my.summaryList[v.code] = '';
							my.contentList[v.code] = '';
						});
					}
				}
			});
		}
		my.init();
		
	}
}])