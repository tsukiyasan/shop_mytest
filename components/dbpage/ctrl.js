app.controller('dbpage_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my = this;
	
	my.dirid=$routeParams.dirid;
	my.pageid=parseInt($location.search().id);
	
	CRUD.setUrl("components/dbpage/api.php");
	
	my.getdbpage = function() {
		CRUD.list({task: "dbpage",pageid:my.pageid,dirid:my.dirid}, "GET").then(function(res){
			if(res.status == 1) {
				my.dbpage = $sce.trustAsHtml(res.data.content);
				my.name = res.data.name;
				my.title = res.data.var1;
				my.bname = res.bname;
				my.leftmenu = res.leftmenu;
				
				$scope._content = res.data._content;
				$scope._name = res.data.name;
				//setTimeout(function(){$("img").addClass("img-responsive");},1000);
			}
		});
	}
	my.getdbpage();
	
}]);

