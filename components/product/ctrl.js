app.controller('product_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','$window',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,$window) {
	var my=this;
	
	my.typeid=parseInt($routeParams.productlistid);
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
		
	CRUD.setUrl("components/product/api.php");
	my.product_type_title='';	
	my.product_type2_title='';	
	my.product_type_url='';
	my.leftmenu_href="product_list";
	CRUD.list({task: "protypelist",typeid:my.typeid,searchtext:my.search_text}, "GET").then(function(res){
		if(res.status == 1) {
			my.tlist = res.data;
			
			console.log(my.tlist);
			
			angular.forEach(my.tlist,function(v,k){
				angular.forEach(v.child,function(v2,k2){
					if(v2.active){
						my.product_type_title=v.name;
						$scope._content=v2.name;
						$scope._name=v.name;
						my.product_type2_title=v2.name;
						my.product_type_url=my.leftmenu_href+v.id;
					}
				});
			});
		}
	});
	
	
}]).controller('product_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','sessionCtrl',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,sessionCtrl) {
	var my=this;
	
	my.lang = sessionCtrl.get("_lang");
	my.currency = sessionCtrl.get("_currency");
	
	my.typeid=parseInt($routeParams.productlistid);
	if($location.search().id){
		my.proid=parseInt($location.search().id);
	}
	
	my.showMode = true;
	if($location.search().f){
		my.fromName=$location.search().f;
		if(my.fromName == "active")
		{
			my.showMode = false;
		}
	}
	
	turl=CRUD.getUrl();
	CRUD.setUrl("components/member/api.php");
	CRUD.detail({task: "loginStatus"}, "GET").then(function(res){
		if(res.status == 1) {
			my.member_status = parseInt(res.data);
		}
	});
	CRUD.setUrl(turl);
	
	CRUD.setUrl("components/product/api.php");
	my.leftmenu_href="product_list";
	CRUD.list({task: "protypelist",typeid:my.typeid}, "GET").then(function(res){
		if(res.status == 1) {
			my.tlist = res.data;
			angular.forEach(my.tlist,function(v,k){
				angular.forEach(v.child,function(v2,k2){
					if(v2.active){
						my.product_type_title=v.name;
						my.product_type2_title=v2.name;
						my.product_type_url=my.leftmenu_href+v.id;
					}
				});
			});
		}
	});
	
	my.num = 1;
	CRUD.list({task: "detail",proid:my.proid}, "GET",true).then(function(res){
		if(res.status == 1) {
			my.product_detail = res.data;			
			my.content = $sce.trustAsHtml(res.data.var03);
			if(res.data.var04){
				my.vote_media_url=$sce.trustAsResourceUrl("https://www.youtube.com/embed/"+res.data.var04);
			}
			$scope._content = res.data._content;
			$scope._imgwidth = res.data._imgwidth;
			$scope._imgheight = res.data._imgheight;
			$scope._content_img = res.data._content_img;
			$scope._content_imgcode = res.data._content_imgcode;
			$scope._name = res.data.name;
		}
	});
	
	$scope.$watch(function(){
		
		if(my.product_detail)
		{
			return my.product_detail.format.format1only;
		}
		else
		{
			return "";
		}
		
	}, function(value){	
		
		if(my.product_detail)
		{
			if(my.product_detail.format.formatonly && my.product_detail.format.format1only)
			{
				my.format1 = my.product_detail.format.format1only;
			}
		}
		
	});
	
	$scope.$watch(function(){
		
		if(my.product_detail)
		{
			return my.product_detail.format.format2only;
		}
		else
		{
			return "";
		}
		
	}, function(value){
		
		if(my.product_detail)
		{
			if(my.product_detail.format.formatonly && my.product_detail.format.format2only)
			{
				my.format2 = my.product_detail.format.format2only;
			}	
		}
		
	});
	
	$scope.$watch(function(){
		return my.format1;
	}, function(value){
		my.num=parseInt(my.num);
		if(isNaN(my.num))my.num=0;
		my.showMsgChk();
	});
	
	$scope.$watch(function(){
		return my.format2;
	}, function(value){
		my.num=parseInt(my.num);
		if(isNaN(my.num))my.num=0;
		my.showMsgChk();
	});
	
	my.showMsg2 = false;
	
	$scope.$watch(function(){
		return my.num;
	}, function(value){
		my.num=parseInt(my.num);
		if(isNaN(my.num))my.num=0;
		my.showMsg2 = false;
		if(my.product_detail && my.num > 0)
		{
			if(my.format1 && my.format2)
			{
				if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock && my.num)
				{
					if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock <= my.num)
					{
						if(my.product_detail.format.format2[my.format1.id][my.format2.id].instockchk == 1)
						{
							my.num = my.product_detail.format.format2[my.format1.id][my.format2.id].instock;
							my.showMsg2 = true;
						}
					}
				}
			}
		}
		
		my.showMsgChk();
	});
	
	my.showMsg = false;
	
	
	my.modal_cart_num_chg=function(v){
		my.num=parseInt(my.num);
		my.showMsg2 = false;
		if(isNaN(my.num))my.num=0;
　　　　　　　　	
		if(my.num<0)my.num=0;
		
		if(my.product_detail && v > 0)
		{
			if(my.format1 && my.format2)
			{
				if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock && my.num)
				{
					if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock <= my.num)
					{
						if(my.product_detail.format.format2[my.format1.id][my.format2.id].instockchk == 1)
						{
							v = 0;
							my.showMsg2 = true;
						}
					}
				}
			}
		}
		
		
		my.num=my.num+v;
		if(my.num<0)my.num=0;
		
		my.showMsgChk();
	};
	
	my.showMsgChk = function()
	{
		my.showMsg = false;
		if(my.num<0)my.num=0;
		
		if(my.product_detail)
		{
			if(my.format1 && my.format2)
			{
				if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock && my.num)
				{
					if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock < my.num && my.product_detail.format.format2[my.format1.id][my.format2.id].instockchk != 1)
					{
						my.showMsg = true;
					}
				}
				
				//當一開始庫存為0的處理
				if(my.product_detail.format.format2[my.format1.id][my.format2.id].instock <= 0 && my.product_detail.format.format2[my.format1.id][my.format2.id].instockchk == 1)
				{
					my.num=0;
					my.showMsg2 = true;
				}
			}
		}
	};
	
	my.add_to_cart=function(t){
		var err=0;
		
		var id=my.proid;
		var param={
			task:'update_cart_list2',
			id:id,
			num:my.num
		
		};
		if(my.format1){
			param.format1=my.format1.id;
		}else{
			err=1;
			error($translate.instant('lg_products.product_select')+my.product_detail.format.format1title);
		}
		
		if(my.format1){
			if(my.format2){
				param.format2=my.format2.id;
			}else{
				err=1;
				error($translate.instant('lg_products.product_select')+my.product_detail.format.format2title);
			}
		}
		
		if(my.num <= 0)
		{
			err=1;
			//請選擇數量
			error($translate.instant('lg_products.product_select_count'));
		}
		
		if(err==0){
			var ourl=CRUD.getUrl();
			CRUD.setUrl("app/controllers/eways.php");
			CRUD.update(param,'POST').then(function(res){
				if(res.status==1){
					$rootScope.cartCnt=res.cnt;
					//已加入購物車
					success($translate.instant('lg_products.product_added_cart'));
					if(t=='cart'){
						$location.search({});
						$location.path("cart_list");
					}
				}
			});
			CRUD.setUrl(ourl);
		}
		　　　　　　　　	
	};
	
	
}]);

