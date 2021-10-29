app.controller('news_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','$window',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,$window) {
	var my=this;
	
	my.cur=!$location.search().cur?1:$location.search().cur;
	my.add_to_favorite_arr=[];
		
	CRUD.setUrl("components/news/api.php");
	
	my.list = function() {
		CRUD.list({task: "list",page:my.cur}, "GET").then(function(res){
			if(res.status == 1) {
				my.data_list = res.data;
				my.cnt = res.cnt;
			}
		});
	}
	my.list();
	

	
}]).controller('news_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my=this;
	
	my.cur=parseInt($location.search().cur);
	if(!my.cur)my.cur=1;
	my.id=parseInt($routeParams.id);
	my.backurl="news_list?cur="+my.cur;
	CRUD.setUrl("components/news/api.php");
	
	CRUD.detail({task: "detail",id:my.id}, "GET").then(function(res){
		if(res.status == 1) {
			my.news_detail= res.data;
			my.news_detail.content = $sce.trustAsHtml(res.data.content);
			
			$scope._name = res.data.name;
			$scope._content = res.data._content;
			$scope._imgwidth = res.data._imgwidth;
			$scope._imgheight = res.data._imgheight;
			$scope._content_img = res.data._content_img;
			
		}else{
			error(res.msg).set('onok', function(closeEvent){ 
				$location.path(my.backurl);
				$rootScope.$apply();
			} );
			
		}
	});

}]);

