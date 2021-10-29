app.controller('active_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','$window',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,$window) {
	var my=this;
	
	my.activeId=parseInt($routeParams.activeId);
	if($location.search().id){
		my.typeid=parseInt($location.search().id);
	}
	my.searchmode=false;
	if(!$location.search().id && $location.search().q){
		my.search_text=$location.search().q;
		my.searchmode=true;
	}
	
	turl=CRUD.getUrl();
	CRUD.setUrl("components/member/api.php");
	CRUD.detail({task: "loginStatus"}, "GET").then(function(res){
		if(res.status == 1) {
			my.member_status = parseInt(res.data);
		}
	});
	CRUD.setUrl(turl);
	
	my.cur=parseInt($routeParams.cur?$routeParams.cur:1);
	my.add_to_favorite_arr=[];
		
	CRUD.setUrl("components/active/api.php");
	my.active_type_title='';	
	my.active_type2_title='';	
    my.active_type_url='';
    my.selectedProductList={};
    my.leftmenu_href="active_list";
    my.getList=function(){
        CRUD.list({task: "activelist",id:my.typeid}, "GET").then(function(res){
            if(res.status == 1) {
                my.tlist = res.data;
                my.activeObj=res.activeObj;
                my.detailList=res.detail;
                angular.forEach(my.detailList,function(v){
                    my.selectedProductList[v.sequence]={selected:0};
                });
            }
        });
    }
	my.getList();
    
    my.showModal=function(info,index){
        my.modalIndex=index;
		my.productInfo=info;
		if (!my.productInfo.spec) {
			my.productInfo.spec = {}
		}
		if (!my.productInfo.spec[1] && my.productInfo.format.format1 && my.productInfo.format.format1.length == 1) {
			my.productInfo.spec[1] = my.productInfo.format.format1[0];
			if (!my.productInfo.spec[2] && my.productInfo.format.format2[my.productInfo.spec[1].id] && Object.keys(my.productInfo.format.format2[my.productInfo.spec[1].id]).length == 1) {
				my.productInfo.spec[2] = my.productInfo.format.format2[my.productInfo.spec[1].id][Object.keys(my.productInfo.format.format2[my.productInfo.spec[1].id])[0]];
			}
		}
		console.log(my.productInfo.spec);
        $('#productInfoModal').modal('show');
    };
	
	my.showProductData=function(info,index){
        my.modalIndex=index;
		info.content = $sce.trustAsHtml(info.var03);
        my.productInfo=info;
        $('#productDataModal').modal('show');
    };
	
	my.resetProduct=function(index){
        my.modalIndex=index;
		angular.forEach(my.detailList,function(v){
			if(v.sequence == my.modalIndex)
			{
				my.selectedProductList[v.sequence]={selected:0};
				v.selectedSpec=0;
                angular.forEach(v.products,function(p){
                    p.selectedSpec=0;
                });
			}
		});
    };

    my.selectProduct=function(){
        var chk=0;
        var specName=[];
        angular.forEach(my.productInfo.spec,function(v){
            if(v && v.id){
                chk++;
                specName.push(v.name);
            }
        });
        if(chk!=2){
            error($translate.instant('lg_actives.active_pleaseSelectFormat'));
            return;
        }
        my.selectedProductList[my.modalIndex]['spec']=my.productInfo.spec;
        my.selectedProductList[my.modalIndex]['selected']=1;
        my.selectedProductList[my.modalIndex]['productId']=my.productInfo.id;
        my.selectedProductList[my.modalIndex]['productName']=my.productInfo.name;
        my.selectedProductList[my.modalIndex]['productImg']=my.productInfo.imgPath;
        my.productInfo.selectedSpecName=specName.join('／');
        my.selectedProductList[my.modalIndex]['selectedSpecName']=my.productInfo.selectedSpecName;
        angular.forEach(my.detailList,function(v){
            if(v.sequence==my.modalIndex){
                v.selectedSpec=1;
                angular.forEach(v.products,function(p){
                    p.selectedSpec=0;
                });
            }
        });
        my.productInfo.selectedSpec=1;
        
        $('#productInfoModal').modal('hide');
    };

    my.addToCart=function(){
        var chk=0;
        angular.forEach(my.detailList,function(v){
            if(v.selectedSpec==1){
                chk++;
            }
        });
        if(chk!=my.detailList.length){
            error($translate.instant('lg_actives.active_notSelectedProduct'));
            return;
        }
        var data={
            active:my.activeObj.id,
            selectedProductList:my.selectedProductList
        };
        CRUD.setUrl("components/active/api.php");
        CRUD.update({task: "updateToCart",data:data}, "POST",true).then(function(res){
            if(res.status == 1) {
                success($translate.instant('lg_actives.active_addCart2'));
                my.getList();
                my.selectedProductList={};
                var turl=CRUD.getUrl();
                CRUD.setUrl("app/controllers/eways.php");
                CRUD.list({task: "get_cart_num"}, "GET").then(function(res){
                    $rootScope.cartCnt=res.cnt;
                });
                CRUD.setUrl(turl);
            }else{
                if(res.errorMessage){
                    error(res.errorMessage);
                }
            }
        });
    };
	
	
	
}]).controller('active_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my=this;
	
	my.typeid=parseInt($routeParams.activelistid);
	if($location.search().id){
		my.proid=parseInt($location.search().id);
	}
	
	turl=CRUD.getUrl();
	CRUD.setUrl("components/member/api.php");
	CRUD.detail({task: "loginStatus"}, "GET").then(function(res){
		if(res.status == 1) {
			my.member_status = parseInt(res.data);
		}
	});
	CRUD.setUrl(turl);
	
	CRUD.setUrl("components/active/api.php");
	my.leftmenu_href="active_list";
	CRUD.list({task: "protypelist",typeid:my.typeid}, "GET").then(function(res){
		if(res.status == 1) {
			my.tlist = res.data;
			angular.forEach(my.tlist,function(v,k){
				angular.forEach(v.child,function(v2,k2){
					if(v2.active){
						my.active_type_title=v.name;
						my.active_type2_title=v2.name;
						my.active_type_url=my.leftmenu_href+v.id;
					}
				});
			});
		}
	});
	
	my.num = 1;
	CRUD.list({task: "detail",proid:my.proid}, "GET",true).then(function(res){
		if(res.status == 1) {
			my.active_detail = res.data;			
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
		
		if(my.active_detail)
		{
			return my.active_detail.format.format1only;
		}
		else
		{
			return "";
		}
		
	}, function(value){	
		
		if(my.active_detail)
		{
			if(my.active_detail.format.formatonly && my.active_detail.format.format1only)
			{
				my.format1 = my.active_detail.format.format1only;
			}
		}
		
	});
	
	$scope.$watch(function(){
		
		if(my.active_detail)
		{
			return my.active_detail.format.format2only;
		}
		else
		{
			return "";
		}
		
	}, function(value){
		
		if(my.active_detail)
		{
			if(my.active_detail.format.formatonly && my.active_detail.format.format2only)
			{
				my.format2 = my.active_detail.format.format2only;
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
	
	$scope.$watch(function(){
		return my.num;
	}, function(value){
		my.num=parseInt(my.num);
		if(isNaN(my.num))my.num=0;
		my.showMsgChk();
	});
	
	my.showMsg = false;
	
	my.modal_cart_num_chg=function(v){
		my.num=parseInt(my.num);
		
		if(isNaN(my.num))my.num=0;
　　　　　　　　	
		if(my.num<0)my.num=0;
		my.num=my.num+v;
		if(my.num<0)my.num=0;
		
		my.showMsgChk();
	};
	
	my.showMsgChk = function()
	{
		my.showMsg = false;
		
		if(my.active_detail)
		{
			if(my.format1 && my.format2)
			{
				if(my.active_detail.format.format2[my.format1.id][my.format2.id].instock && my.num)
				{
					if(my.active_detail.format.format2[my.format1.id][my.format2.id].instock < my.num)
					{
						my.showMsg = true;
					}
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
			error("請選擇"+my.active_detail.format.format1title);
		}
		
		if(my.format1){
			if(my.format2){
				param.format2=my.format2.id;
			}else{
				err=1;
				error("請選擇"+my.active_detail.format.format2title);
			}
		}
		if(err==0){
			var ourl=CRUD.getUrl();
			CRUD.setUrl("app/controllers/eways.php");
			CRUD.update(param,'POST').then(function(res){
				if(res.status==1){
					$rootScope.cartCnt=res.cnt;
					success("已加入購物車");
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

