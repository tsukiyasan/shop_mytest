app.controller('message_list',['$rootScope','$scope','urlCtrl','$location','$route','$routeParams','CRUD', function($rootScope,$scope, urlCtrl, $location,$route,$routeParams,CRUD) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/message/api.php");
		
		my.funcPerm = $rootScope.funclist['message'];
		
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? {name: "", newsDate: ""} : param.search,
			orderby: !param.orderby ? {newsDate: "asc", pubDate: "desc"} : param.orderby
		}
		my.odrhash=urlCtrl.enaes({component:'message',p:param});
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		};
		
		my.delete = function(id){
			
			if(my.funcPerm.D == 'true')
			{
				
				CRUD.del({id:id}, "GET").then(function(res) {
					if(res.status == 1) {
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
			urlCtrl.go("/message_page", param);
		}
		
		my.list();
	}
	
	
}])

.controller('message_page', ['$rootScope','$scope','urlCtrl','$location','$route','$routeParams','CRUD','$sce',function($rootScope,$scope, urlCtrl, $location,$route,$routeParams,CRUD,$sce) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		CRUD.setUrl("components/message/api.php");
		
		my.funcPerm = $rootScope.funclist['message'];
		
		my.message={};
		
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.message = res.data;
					my.state=res.data.state;
					my.message.state = $rootScope.pubcodeArr['msgState'][res.data.state]['name'];
				
					my.message.recContent=$sce.trustAsHtml(my.message.recContent);
					if(res.data.sendContent){
						my.params.sendContent=$sce.trustAsHtml(res.data.sendContent);
					}
					if(my.state==1){
						
						CRUD.update({id:param.id,state:2}).then(function(res) {
							if(res.status == 1) {
								my.message.state=$rootScope.pubcodeArr['msgState'][2]['name'];
							}
						});
						
					}
				}
			});
		}
		
		if(param.id) {
			my.actionType=1;
			my.params = {
				id: !param.id ? null : param.id
			}
			my.detail();
		} else {
			my.actionType=0;
			
		}
		my.submit = function() {
			
			if(my.funcPerm.U == 'true')
			{
				
				CRUD.update(my.params, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
				
			}
			
			
			
		}
		
		my.cancel = function() {
			urlCtrl.go("/message_list", listparams);
		}
	}
	
	
}]);