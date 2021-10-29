app.controller('goodrecommend_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my = this;
	
	my.pageid=parseInt($routeParams.pageid);
	
	CRUD.setUrl("components/goodrecommend/api.php");
	my.getgoodrecommend = function() {
		CRUD.list({task: "goodrecommend",pageid:my.pageid}, "GET").then(function(res){
			if(res.status == 1) {
				my.data_list = res.data;
				my.title = res.title;
			}
		});
	}
	my.getgoodrecommend();
	
}]);

