app.controller('bonus_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','$window',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,$window) {
	var my=this;
	
	my.typeid=parseInt($routeParams.bonuslistid);
	if($location.search().id){
		my.typeid=parseInt($location.search().id);
	}
	my.searchmode=false;
	if(!$location.search().id && $location.search().q){
		my.search_text=$location.search().q;
		my.searchmode=true;
	}
	
	my.cur=parseInt($routeParams.cur?$routeParams.cur:1);
	my.add_to_favorite_arr=[];
		
	CRUD.setUrl("components/bonus/api.php");
	my.bonus_type_title='';	
	my.bonus_type2_title='';	
	my.bonus_type_url='';
	my.leftmenu_href="bonus_list";
	CRUD.list({task: "protypelist",typeid:my.typeid}, "GET").then(function(res){
		if(res.status == 1) {
			my.tlist = res.data;
		
			angular.forEach(my.tlist,function(v,k){
				angular.forEach(v.child,function(v2,k2){
					if(v2.active){
						my.typeid=v2.id;
						my.bonus_type_title=v.name;
						my.bonus_type2_title=v2.name;
						my.bonus_type_url=my.leftmenu_href+v.id;
					}
				});
			});
		}
	});
	
	
}]).controller('bonus_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my=this;
	
	my.typeid=parseInt($routeParams.bonuslistid);
	if($location.search().id){
		my.proid=parseInt($location.search().id);
	}
	
	CRUD.setUrl("components/bonus/api.php");
	my.leftmenu_href="bonus_list";
	CRUD.list({task: "protypelist",typeid:my.typeid}, "GET").then(function(res){
		if(res.status == 1) {
			my.tlist = res.data;
			angular.forEach(my.tlist,function(v,k){
				angular.forEach(v.child,function(v2,k2){
					if(v2.active){
						my.bonus_type_title=v.name;
						my.bonus_type2_title=v2.name;
						my.bonus_type_url=my.leftmenu_href+v.id;
					}
				});
			});
		}
	});
	
	
	CRUD.list({task: "detail",proid:my.proid}, "GET",true).then(function(res){
		if(res.status == 1) {
			my.bonus_detail = res.data;
			my.content = $sce.trustAsHtml(res.data.var03);
			if(res.data.var04){
				my.vote_media_url=$sce.trustAsResourceUrl("https://www.youtube.com/embed/"+res.data.var04);
			}
			$scope._content = res.data._content;
			$scope._imgwidth = res.data._imgwidth;
			$scope._imgheight = res.data._imgheight;
			$scope._content_img = res.data._content_img;
			$scope._name = res.data.name;
		}
	});
	my.num = 0;
	my.modal_cart_num_chg=function(v){
		my.num=parseInt(my.num);
		
		if(isNaN(my.num))my.num=0;
　　　　　　　　	
		if(my.num<0)my.num=0;
		my.num=my.num+v;
		if(my.num<0)my.num=0;
	};
	
	my.add_to_cart=function(t){
		var err=0;
		
		var id=my.proid;
		var param={
			task:'update_bonus_cart_list',
			id:id,
			num:my.num
		
		};
		if(my.format1){
			param.format1=my.format1.id;
		}else{
			err=1;
			error($translate.instant('lg_main.select')+my.bonus_detail.format.format1title);
		}
		if(my.format2){
			if(my.format2){
				param.format2=my.format2.id;
			}else{
				err=1;
				error($translate.instant('lg_main.select')+my.bonus_detail.format.format2title);
			}
		}
		if(err==0){
			var ourl=CRUD.getUrl();
			CRUD.setUrl("app/controllers/eways.php");
			CRUD.update(param,'POST').then(function(res){
				$rootScope.cartCnt=res.cnt;
				success($translate.instant('lg_main.AddedCart'));
				if(t=='cart'){
					$location.search({});
					$location.path("cart_list");
				}
			});
			CRUD.setUrl(ourl);
		}
		　　　　　　　　	
	};
	
	
}]);

