app.controller('odring_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/"+param.component+"/api.php");
		my.backhash=urlCtrl.enaes(param.p);
		my.backpath=param.component+"_list";
		CRUD.list({task:'alllist',belongid:param.p.belongid}, "GET").then(function(res){
			if(res.data.status == 1) {
				my.data_list = res.data.data;
			}
		});
	
	}	
}]).controller('odring_list2',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/"+param.component+"/api.php");
		my.backhash=urlCtrl.enaes(param.p);
		my.backpath=param.component+"_list";
		CRUD.list({task:'alllist2',belongid:param.p.belongid}, "GET").then(function(res){
			if(res.data.status == 1) {
				my.data_list = res.data.data;
			}
		});
	
	}	
}]);