app.controller('index_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my = this;
	
	$("#index-banner").carousel();

	
	
	my.show_cart_modal=function(id,amt,img,promedia){
		$scope.amt=amt;
		$scope.modal_cart_num=1;
		$scope.modaltotal=$scope.modal_cart_num*$scope.amt;
		$scope.proid=id;
		$scope.imgname=img;
		$scope.promedia=promedia;
		$scope.promedia_url=$sce.trustAsResourceUrl("https://www.youtube.com/embed/"+promedia);
		
		angular.forEach(my.hotpro,function(v,k){
			if(v.id==id){
				my.title=v.name;
				my.summary=v.var03;
			}
		});
		
	};
	CRUD.setUrl("components/member/api.php");
	CRUD.list({task:'likeProduct'},"GET").then(function(res){
		if(res.status==1){
			my.add_to_favorite_arr=res.favorite;
			CRUD.setUrl("components/index/api.php");
		}
	});
	
	my.add_to_favorite=function(id){
		var data=[];
		angular.forEach(my.add_to_favorite_arr,function(v,k){
			data.push(v);
		});
		
		CRUD.setUrl("components/product/api.php");
		if(data.indexOf(id)==-1){
			CRUD.update({task: "add_to_favorite",data:id}, "GET").then(function(res){
				if(res.status == 1) {
					
				}
			});
			my.add_to_favorite_arr[id]=id;
		}else{
			CRUD.update({task: "delete_to_favorite",data:id}, "GET").then(function(res){
				if(res.status == 1) {
					
				}
			});
			delete my.add_to_favorite_arr[id];
		}
		CRUD.setUrl("components/index/api.php");
	};
	
}]);

