/**
 * Cube - Bootstrap Admin Theme
 * Copyright 2014 Phoonio
 */


angular.module('goodarch2uApp').directive("takeModal",function(CRUD){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="myModal_TAKE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                          '<div class="modal-dialog">'+
                            '<div class="modal-content">'+
                              '<div class="modal-header modal_header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                    '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-money"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_taketype\' | translate">取貨方式</txt></h4>'+
                              '</div>'+
                              '<div class="modal-body modal_body">'+
                              		'<div class="row">'+
                                        '<div class="col-xs-12 modal_body_content">'+
                                            '<ul class="modal_product_detail_list">'+
                                                '<li class="col-xs-12"><p ng-bind="\'lg_directives.directives_select_taketype\' | translate">請選擇取貨方式：</p>'+
                                                    '<div class="radio i-checks" ng-repeat="data in take_type_list">'+
                                                      '<label>'+
                                                        '<input type="radio" ng-model="take_type.id" ng-value="data.id" ng-checked="take_type.id==data.id">'+
                                                        '<i></i> {{data.name}} (${{data.amt}})'+
                                                      '</label>'+
                                                    '</div>'+
                                                '</li>'+
                                            '</ul>'+
                                        '</div>'+
                                    '</div>'+
                              '</div>'+
                              '<div class="modal-footer">'+
                                   '<div class="btn-group btn-group-justified">'+
                                   	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
                                   	'<a href="javascript:void(0)" ng-click="set_take_type()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
                                   '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>'+
                        '</div>',
		replace: true,
		controller:function($scope,$rootScope){
　　　　　　　　$scope.take_type = {
　　　　　　　　	id: 3
　　　　　　　　};
　　　　　　　　var ourl=CRUD.getUrl();
　　　　　　　　$scope.get_take_modal_data=function(type){
　　　　　　　　	CRUD.setUrl("app/controllers/eways.php");
　　　　　　　	　	CRUD.detail({task:'take_type'},'GET').then(function(res){
　　　　　　　　		if(res.status==1){
　　　　　　　　			$scope.take_type_list = res.data;
　　　　　　　　			$scope.take_type.id = res.take_type;
　　　　　　　　			if($scope.take_type_list[$scope.take_type.id]){
　　　　　　　　				$scope.take_type_str=$scope.take_type_list[$scope.take_type.id].name;
　　　　　　　　			}
　　　　　　　　			$rootScope.dlvrAmt=res.dlvrAmt;
　　　　　　　　			if(type){
　　　　　　　　				$scope.set_take_type();
　　　　　　　　			}
　　　　　　　　		}
	　　　　　　　　});
					CRUD.setUrl(ourl);	　　　　　　　　
　　　　　　　　}
　　　　　　　　//$scope.get_take_modal_data(1);
　　　　　　　　$scope.set_take_type=function(){
　　　　　　　　	CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　	CRUD.update({task:'set_take_type',take_type:$scope.take_type.id},'GET').then(function(res){
　　　　　　　　		if(res.status==1){
　　　　　　　　		    if($scope.take_type_list[$scope.take_type.id]){
　　　　　　　　		    	$scope.take_type_str=$scope.take_type_list[$scope.take_type.id].name;
　　　　　　　　		    	
　　　　　　　　		    }
　　　　　　　　		    $rootScope.dlvrAmt=res.dlvrAmt;
　　　　　　　　		    
　　　　　　　　		}
　　　　　　　　	});
　　　　　　　　	CRUD.setUrl(ourl);
　　　　　　　　};
　　　　　　　　
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("cartCouponModal",function(CRUD,$location){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="myModal_DOLLAR" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-dollar"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_use_coupon\' | translate">購物金折抵</txt></h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                          		'<p><txt ng-bind="\'lg_directives.directives_coupon_msg1\' | translate">請輸入欲折抵的金額</txt><span class="txt_red">（<txt ng-bind="\'lg_directives.directives_coupon_msg2\' | translate">購物金餘額：</txt>{{maxcoin | formatnumber}}）</span></p><input valid-number type="text" ng-model="usecoin" class="form-control"><br />'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
                               	'<a href="javascript:void(0)" ng-click="cart_setcoin()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		controller:function($scope){
　　　　　　　　var ourl=CRUD.getUrl();
　　　　　　　　CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　$scope.$watch('member_status', function(value){
					if(value==1){
						CRUD.detail({task:'get_usr_coupon'},'GET').then(function(res){
							if(res.status==1){
								$scope.maxcoin = res.data;
								$scope.usecoin = parseInt(res.usecoin);
							}
		　　　　　　　　});
					}
				});
　　　　　　　　
　　　　　　　　
　　　　　　　　$scope.$watch('usecoin', function(value){
　　　　　　　　	if($scope.totalAmt>$scope.maxcoin){
　　　　　　　　		if(!isNaN(value)){
　　　　　　　　			if(parseInt(value)>parseInt($scope.maxcoin)){
　　　　　　　　				$scope.usecoin=parseInt($scope.maxcoin);
　　　　　　　　				
　　　　　　　　			}
　　　　　　　　		}
　　　　　　　　	}else{
　　　　　　　　		if(!isNaN(value)){
　　　　　　　　			if(parseInt(value)>parseInt($scope.allamt)){
　　　　　　　　				$scope.usecoin=parseInt($scope.allamt);
　　　　　　　　			}
　　　　　　　　		}
　　　　　　　　	}
					if(value<=0)$scope.usecoin=0;
				});
	
　　　　　　　　$scope.cart_setcoin=function(){
　　　　　　　　	var id=$scope.proid;
　　　　　　　　	CRUD.update({task:'cart_set_use_coin',usecoin:$scope.usecoin},'POST').then(function(res){
　　　　　　　　		$scope.cart_use_coin=$scope.usecoin;
　　　　　　　　	});
　　　　　　　　	
　　　　　　　　};
　　　　　　　　CRUD.setUrl(ourl);
　　　　　 }
    };
});


angular.module('goodarch2uApp').directive("cartLoginModal",function(CRUD,$location,fbLogin,gpLogin,$route){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="myModal_Login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-user"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_member_login\' | translate">會員登入</txt></h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                          		'<p ng-bind="\'lg_directives.directives_email\' | translate">E-mail</p><input type="text" ng-model="cart.email" class="form-control" /><br />'+
                          		'<p ng-bind="\'lg_directives.directives_pwd\' | translate">密碼</p><input type="password" ng-model="cart.passwd" class="form-control" /><br />'+
                          		'<!--<p >確認密碼 (如無帳號需快速註冊者直接填此欄位即可快速註冊，否則請留空)</p><input type="password" ng-model="cart.passwd2" class="form-control"><br />-->'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
                               	'<a href="javascript:void(0)" ng-click="cart_login()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
                               	'<!--a href="javascript:void(0)" ng-click="cart_fb_login()" class="btn btn-lg btn-primary" data-dismiss="modal" aria-label="Close"><i class="fa fa-facebook-square"></i></a>'+
                               	'<a href="javascript:void(0)" ng-click="cart_gp_login()" class="btn btn-lg btn-danger" data-dismiss="modal" aria-label="Close"><i class="fa fa-google-plus-square"></i></a-->'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		controller:function($scope,$translate){
　　　　　　　　
　　　　　　　　
　　　　　　　　$scope.cart_fb_login=function(){
					fbLogin.getUserId('cart_list');
				};
				$scope.cart_gp_login=function(){
					gpLogin.getUserId();
				};
　　　　　　　　$scope.cart_login=function(){
　　　　　　　　	try{
						var email=$scope.cart.email;
						var passwd=$scope.cart.passwd;
						var passwd2=$scope.cart.passwd2;
						var err=0;
						
						if(!email){
							//請輸入信箱
							error($translate.instant('lg_directives.directives_enter_email'));
							err++;
						}
						if(!passwd){
							//請輸入密碼
							error($translate.instant('lg_directives.directives_enter_pwd'));
							err++;
						}
						var task="login";
						if(passwd2){
							if(passwd2!=passwd){
								//確認密碼錯誤
								error($translate.instant('lg_directives.directives_pwd_error'));
								err++;
							}else{
								task="signup2";
							}
							
						}
						
						if(err==0){
							var ourl=CRUD.getUrl();
							CRUD.setUrl("components/member/api.php");
							CRUD.update({task: task,email:email,passwd:passwd}, "POST").then(function(res){
								if(res.status == 1 || res.status == 2) {
									$scope.member_status=1;
									location.reload();
									success(res.msg);
								}
							});
							CRUD.setUrl(ourl);
						}
					}catch(e){}
　　　　　　　　};
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("topBtn",function(CRUD,$location,fbLogin,gpLogin,$route){

    return {
        restrict: "E",
        template: "<img id='goTopButton' style='display: none; z-index: 5; cursor: pointer;' title='回到頂端'/>",
		replace: true,
		controller:function($scope){
　　　　　　　　var img = "templates/default/images/go-top.png",
				locatioin = 5/6, // 按鈕出現在螢幕的高度
				right = 10, // 距離右邊 px 值
				opacity = 0.9, // 透明度
				speed = 500, // 捲動速度
				$button = $("#goTopButton"),
				$body = $(document),
				$win = $(window);
				$button.attr("src", img);
				$button.on({
					mouseover: function() {$button.css("opacity", 1);},
					mouseout: function() {$button.css("opacity", opacity);},
					click: function() {$("html, body").animate({scrollTop: 0}, speed);}
				});
				window.goTopMove = function () {
					var scrollH = $body.scrollTop(),
						winH = $win.height(),
						css = {"top": winH * locatioin + "px", "position": "fixed", "right": right, "opacity": opacity};
					if(scrollH > 20) {
						$button.css(css);
						$button.fadeIn("slow");
					} else {
						$button.fadeOut("slow");
					}
				};
				$win.on({
					scroll: function() {goTopMove();},
					resize: function() {goTopMove();}
				});
　　　　　　　　
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("searchContent",function(CRUD,$location,fbLogin,gpLogin,$route){

    return {
        restrict: "E",
        template: 
        		'<div class="modal fade" id="myModal_Search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
	                  '<div class="modal-dialog">'+
	                    '<div class="modal-content">'+
	                        '<div class="modal-header modal_header">'+
	                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
	                            '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-search"></i>&emsp;<txt ng-bind="\'lg_directives.directives_search\' | translate">搜尋商品</txt></h4>'+
	                        '</div>'+
	                        '<div class="modal-body" style="font-size:1.1em;">'+
	                          '<p ng-bind="\'lg_directives.directives_search_msg1\' | translate">請輸入商品名稱或關鍵字：</p>'+
	                          '<input type="text" ng-model="mainctrl.search_text" class="form-control" placeholder="{{\'lg_directives.directives_search_msg2\' | translate}}"><br />'+
	                        '</div>'+
	                        '<div class="modal-footer">'+
	                          '<div class="btn-group btn-group-justified">'+
	                            '<a href="javascript:void(0)" id="search_close" class="btn btn-lg btn-second btn_left" style="padding:10px 5px;" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
	                            '<a href="javascript:void(0)" ng-click="mainctrl.search()" class="btn btn-lg btn-first" style="padding:10px 5px;" data-dismiss="modal" ng-bind="\'lg_main.search\' | translate">搜尋</a>'+
	                          '</div>'+
	                        '</div>'+
	                    '</div>'+
	                  '</div>'+
	                '</div>',
		replace: true
    };
});

angular.module('goodarch2uApp').directive("bannerContent",function(CRUD,$location,fbLogin,gpLogin,$route){

    return {
        restrict: "E",
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            index: "@"
        },
        template: 
        		'<div id="myCarousel{{index}}" class="carousel slide" data-ride="carousel">'+
			          '<ol class="carousel-indicators">'+
			            '<li data-target="#myCarousel{{index}}" data-slide-to="{{$index}}" ng-class="{active:$index==0}" ng-repeat="data in bannercontent_ctrl.banners[index] track by data.id"></li>'+
			          '</ol>'+
			          '<div class="container-fulid">'+
			            '<div class="carousel-inner" role="listbox">'+
			              '<div  class="item" ng-class="{active: $index==0}" ng-repeat="data in bannercontent_ctrl.banners[index] track by data.id">'+
			            	'<a ng-href="{{data.linkurl?data.linkurl:\'javascript:void(0)\'}}" target="{{data.linktype == \'link\' ? \'_blank\' : \'_self\'}}">'+
			                '<img ng-swipe-left="bannercontent_ctrl.left(index)" ng-swipe-right="bannercontent_ctrl.right(index)" ng-src="{{data.img}}" alt="">'+
			                '</a>'+
			              '</div>'+
			            '</div>'+
			          '</div>'+
			      '</div>',
		replace: true,
		controllerAs: 'bannercontent_ctrl',
		controller:function($scope){
			var self=this;
			if(!$scope.index)$scope.index=1;
			self.banners=[];
			self.left=function(index){
				$("#myCarousel"+index).carousel('next');
			};
			self.right=function(index){
				$("#myCarousel"+index).carousel('prev');
			};
			var ourl=CRUD.getUrl();
			CRUD.setUrl("app/controllers/eways.php");
			CRUD.list({task: "banner",index:$scope.index}, "GET").then(function(res){
				if(res.status == 1) {
					self.banners[$scope.index] = res.data;
				}
			});
	        CRUD.setUrl(ourl);
	        
　　　　　　
　　　　}
    };
});

angular.module('goodarch2uApp').directive("indexProduct",function(CRUD,$location,fbLogin,gpLogin,$route,$sce,sessionCtrl){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            index: "@",
            showcnt: "@",
            title: "@",
            title2: "@",
            type:"@",
            proclass:"@"
        },
        template:'<div>'+ 
	        		'<div class="page_title text-center">'+
		                '<h3>{{title}}</h3>'+
		                '<span>{{title2}}</span>'+
		              '</div>'+
		              '<div class="Products padding_0">'+
		                '<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3" ng-class="proclass" ng-repeat-start="data in indexpro_ctrl.indexpro[index] track by $index">'+                 
		                    '<div class="col-xs-12 padding_0 text-center">'+
		                      '<a ng-href="product_page/{{data.ptid}}?id={{data.id}}"><img  ng-src="{{data.img}}" alt="{{data.name}}" class="img-responsive center-block"></a>'+
		                      '<h4 ng-bind="data.name"></h4>'+
		                      '<p ng-if="indexpro_ctrl.loginonly"><span><txt ng-if="data.siteAmt != data.highAmt" ng-bind="\'lg_directives.directives_siteAmt\' | translate">特惠價</txt>{{("lg_money."+indexpro_ctrl.currency) | translate}} <span ng-bind="data.siteAmt | formatnumber"></span></span></p>'+
		                      '<p ng-if="indexpro_ctrl.loginonly && data.siteAmt != data.highAmt"><span style="font-size: 0.8em; color: #BBB; text-decoration: line-through;"><txt ng-bind="\'lg_directives.directives_highAmt\' | translate">原價</txt>{{("lg_money."+indexpro_ctrl.lang) | translate}} {{data.highAmt | formatnumber}}</span></p>'+
		                      '<a ng-if="indexpro_ctrl.loginonly" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{index}}" ng-click="indexpro_ctrl.show_cart_modal(data.id)"><div class="col-xs-12 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_add_cart2\' | translate">加入購物車</txt></div></a>'+
							  '<p ng-if="indexpro_ctrl.logoutonly"><span style="color: #BBB;"><txt><img ng-src="templates/default/images/favicon.png" height="23" width="23" style="border:0; margin-bottom: 3px;" /><txt ng-bind="\'lg_directives.directives_login_first\' | translate">登入後顯示價格</txt></txt></span></p>'+
		                    '</div>'+       
		                '</div>'+
						'<div ng-if="($index+1)%2==0" class="clearfix visible-xs visible-md visible-sm"></div>'+
						'<div ng-repeat-end ng-if="$index%4 == 3" class="clearfix  visible-md visible-lg"></div>'+
		              '</div>'+
		              /*
		            	modalindex:第幾個modal
		            	proid:商品id
		            	proname:商品名
		            	proimg:商品圖片
		            	prositeamt:商品售價
		            	proformat1:商品規格1
		            	proformat2:商品規格2
		            	proformat1title:規格1標題
		            	proformat2title:規格2標題
		            	formatonly:單一規格
		            	format1only:規格1單一規格
		            	format2only:規格2單一規格
		              */
		              '<cart-modal modalindex="index" proid="indexpro_ctrl.proid" proname="indexpro_ctrl.proname" proimg="indexpro_ctrl.proimg" '+
		              'prositeamt="indexpro_ctrl.prositeAmt" proformat1="indexpro_ctrl.proformat1" proformat2="indexpro_ctrl.proformat2" proformat22="indexpro_ctrl.proformat22" '+
		              'proformat1title="indexpro_ctrl.proformat1title" proformat2title="indexpro_ctrl.proformat2title" formatonly="indexpro_ctrl.formatonly" format1only="indexpro_ctrl.format1only" format2only="indexpro_ctrl.format2only" ></cart-modal>'+
            	'</div>',
		replace: true,
		controllerAs: 'indexpro_ctrl',
		controller:function($scope){
			var self=this;
			
			self.lang = sessionCtrl.get('_lang');
			self.currency = sessionCtrl.get('_currency');
			
			if(!$scope.index)$scope.index=1;
			self.indexpro=[];//初始化
			
			//檢查登入
			self.loginStatus = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("components/member/api.php");
				CRUD.detail({task: "loginStatus"}, "POST").then(function(res){
					if(res.status == 1) {
						self.loginonly = parseInt(res.data)==1?true:false;
						self.logoutonly=!self.loginonly;
						self.om = res.onlymember;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.loginStatus();
			
			self.getIndexProduct = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("app/controllers/eways.php");
				CRUD.list({task: 'getIndexProduct',type:$scope.type,showcnt:$scope.showcnt,index:$scope.index}, "GET").then(function(res){
					
					if(res.status == 1) {
						self.indexpro[$scope.index] = res.data;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.getIndexProduct();
			
			self.show_cart_modal=function(id){
				/*$scope.amt=amt;
				$scope.modal_cart_num=1;
				$scope.modaltotal=$scope.modal_cart_num*$scope.amt;
				$scope.proid=id;
				$scope.imgname=img;
				$scope.promedia=promedia;
				$scope.promedia_url=$sce.trustAsResourceUrl("https://www.youtube.com/embed/"+promedia);
				*/
				angular.forEach(self.indexpro[$scope.index],function(v,k){
					if(v.id==id){
						self.proid=v.id;
						self.proname=v.name;
						self.proimg=v.img;
						self.prositeAmt=v.siteAmt;
						self.proformat1title=v.format.format1title;
						self.proformat2title=v.format.format2title;
						self.proformat1=v.format.format1;
						self.proformat22=v.format.format2;
						self.proformat2=v.format.format22;
						self.formatonly=v.format.formatonly;
						self.format1only=v.format.format1only;
						self.format2only=v.format.format2only;
					}
				});
				
			};
　　　　}
    };
});



angular.module('goodarch2uApp').directive("advContent",function(CRUD,$location,fbLogin,gpLogin,$route,$sce){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            index: "@"
        },
        template:'<div class="container" ng-class="{\'1\':\'row_area\',\'2\':\'container_bottom\'}[index]">'+
		            '<div class="col-xs-12 padding_0 text-center">'+
		              '<div class="Add" ng-repeat="data in advcontent_ctrl.advlist[index] track by $index">'+
		                '<div class="col-xs-12 row_area" ng-class="{\'1\':\'col-sm-12\',\'2\':\'col-sm-6\',\'3\':\'col-sm-4\',\'4\':\'col-sm-3\'}[advcontent_ctrl.viewmode]">'+
		                  '<a target="_blank" ng-href="{{data.url}}">'+
		                    '<div class="col-xs-12 padding_0">'+
		                      '<img ng-src="{{data.img}}" alt="" class="img-responsive center-block">'+
		                    '</div>'+
		                 '</a>'+
		                '</div>'+
		              '</div>'+
		            '</div>'+
		      '</div>',
		replace: true,
		controllerAs: 'advcontent_ctrl',
		controller:function($scope){
			var self=this;
			if(!$scope.index)$scope.index=1;
			self.advlist=[];//初始化
			self.getIndexProduct = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("app/controllers/eways.php");
				CRUD.list({task: 'getAdvContent',index:$scope.index}, "GET").then(function(res){
					if(res.status == 1) {
						self.advlist[$scope.index] = res.data;
						self.viewmode=res.viewmode?res.viewmode:2;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.getIndexProduct();
			
　　　　}
    };
});


angular.module('goodarch2uApp').directive("mediaContent",function(CRUD,$location,fbLogin,gpLogin,$route,$sce){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            index: "@",
            title: "@",
            title2: "@"
        },
        template:'<div class="col-xs-12 col-sm-12 col-md-6">'+
		              '<div class="page_title">'+
		                '<h3>{{title}}</h3>'+
		                '<span>{{title2}}</span>'+
		              '</div>'+
		              '<div class="Media-Report">'+
		                '<div class="col-xs-12 row_area padding_0">'+
		                    '<div class="embed-responsive embed-responsive-16by9">'+
		                        '<iframe ng-src="{{mediacontent_ctrl.medialist.linkurl}}" frameborder="0" allowfullscreen></iframe>'+
		                    '</div>'+
		                    '<p><span><i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;&nbsp;{{mediacontent_ctrl.medialist.date}}</span></p>'+
		                    '<h4>{{mediacontent_ctrl.medialist.name}}</h4>'+
		                '</div>'+
		              '</div>'+
		            '</div>',
		replace: true,
		controllerAs: 'mediacontent_ctrl',
		controller:function($scope){
			var self=this;
			if(!$scope.index)$scope.index=1;
			self.medialist=[];//初始化
			self.getIndexProduct = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("app/controllers/eways.php");
				CRUD.list({task: 'getMediaContent'}, "GET").then(function(res){
					if(res.status == 1) {
						self.medialist.linkurl= $sce.trustAsResourceUrl("https://www.youtube.com/embed/"+res.data.linkurl);
						self.medialist.name= res.data.name;
						self.medialist.date= res.data.date;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.getIndexProduct();
			
　　　　}
    };
});


angular.module('goodarch2uApp').directive("newsContent",function(CRUD,$location,fbLogin,gpLogin,$route,$sce){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            index: "@",
            title: "@",
            title2: "@",
            showcnt: "@"
        },
        template:'<div class="col-xs-12 col-sm-12 col-md-6">'+
		              '<div class="page_title">'+
		                '<h3>{{title}}</h3>'+
		                '<span>{{title2}}</span>'+
		              '</div>'+
		              '<div class="row_area">'+
		                  '<div class="Lastest-News" ng-repeat="data in mediacontent_ctrl.newslist track by data.id">'+
		                    '<div class="col-xs-12 new_area_padding padding_0">'+
		                      '<div class="col-xs-2 date">'+
		                        '<a ng-href="/news_page/{{data.id}}"><p>{{data.newsDate_M}}<br><span>{{data.newsDate_D}}</span></p></a>'+
		                     '</div>'+
		                      '<div class="col-xs-10 content">'+
		                        '<a href="/news_page/{{data.id}}">'+
		                          '<h4>{{data.name}}</h4>'+
		                          '<p>{{data.summary}}</p>'+
		                        '</a>'+
		                      '</div>'+
		                    '</div>'+
		                  '</div>'+
		              '</div>'+
		            '</div>',
		replace: true,
		controllerAs: 'mediacontent_ctrl',
		controller:function($scope){
			var self=this;
			if(!$scope.index)$scope.index=1;
			self.medialist=[];//初始化
			self.getIndexProduct = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("app/controllers/eways.php");
				CRUD.list({task: 'getNewsContent'}, "GET").then(function(res){
					if(res.status == 1) {
						self.newslist= res.data;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.getIndexProduct();
			
　　　　}
    };
});

//數字驗證
angular.module('goodarch2uApp').directive('validNumber', function() {
  return {
    require: '?ngModel',
    link: function(scope, element, attrs, ngModelCtrl) {
      if(!ngModelCtrl) {
        return; 
      }

      ngModelCtrl.$parsers.push(function(val) {
        if (angular.isUndefined(val)) {
            var val = '';
        }
        var clean = val.replace( /[^0-9]+/g, '');
        if (val !== clean) {
          ngModelCtrl.$setViewValue(clean);
          ngModelCtrl.$render();
        }
        return clean;
      });

      element.bind('keypress', function(event) {
        if(event.keyCode === 32) {
          event.preventDefault();
        }
      });
    }
  };
});

//左側選單
angular.module('goodarch2uApp').directive("leftMenu",function(CRUD,sessionCtrl,$location){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            tlist: "=",
            usehref: "@"
        },
        template:'<div class="col-xs-12 col-sm-3 col-md-3">'+
	                '<div class="panel panel-default" ng-repeat="roottype in tlist track by roottype.id" ng-style="{\'margin-top\':\'20px\'}[$index>0]">'+
	                  '<div class="panel-heading">'+
	                    '<h4 class="panel-title">'+
	                      '<a data-toggle="collapse" href="javascript:void(0)" data-target="#collapseCategory{{$index}}" class="collapseWill" aria-expanded="true">'+
	                        '<span class="Left_nav"><img src="templates/default/images/title_icon.png" alt=""/>&nbsp;{{roottype.name}}</span>'+
	                        '<span class="Left_nav_openi visible-xs" style="float: right;"><i class="fa fa-caret-down" aria-hidden="true"></i></span>'+
	                      '</a>'+
	                    '</h4>'+
	                  '</div>'+
	                  '<div id="collapseCategory{{$index}}" class="panel-collapse collapse" ng-class="roottype.active ? \'in\' : \'\'" aria-expanded="true">'+
	                    '<div class="panel-body">'+
	                      '<ul class="nav nav-pills nav-stacked tree Left_nav_ul">'+
	                        '<li ng-class="list.active" ng-repeat="list in roottype.child track by list.id" ng-show="((!list.loginonly && !list.logoutonly) || (list.loginonly && list.loginonly==loginonly) || (list.logoutonly && list.logoutonly==logoutonly)) && !list.hide">'+
	                          '<a ng-show="roottype.id && !list.fun" ng-href="{{usehref}}/{{roottype.id}}?id={{list.id}}" ng-bind="list.name"></a>'+
	                          '<a ng-show="!roottype.id && !list.fun " ng-href="{{usehref}}/{{list.id}}" ng-bind="list.name"></a>'+
	                          '<a ng-show="list.fun " href="javascript:void(0)" ng-click="logout()" ng-bind="list.name"></a>'+
	                        '</li>'+
	                      '</ul>'+
	                    '</div>'+
	                  '</div>'+
	                '</div>'+
	            '</div>',
		replace: true,
		link:function(scope){
			
			CRUD.setUrl("components/member/api.php");
	
			CRUD.detail({task: "loginStatus"}, "POST").then(function(res){
				if(res.status == 1) {
					scope.loginonly = parseInt(res.data)==1?true:false;
					scope.logoutonly=!scope.loginonly;
				}
			});
	
			var isloop=true;
			var activeChk=function(listarr){
				
				angular.forEach(listarr,function(v,k){
					var tmparr=[];
					var activeArr=[];
					if(angular.isObject(v.child)){
						tmparr=activeChk(v.child);
						
					}else{
						
						
						if($location.path()=="/"+scope.usehref+"/"+v.id && isloop){
							activeArr[k]="active";
							scope.title=v.name;
							isloop=false;
						}else if(($location.path()=="/"+scope.usehref+"/signup" || $location.path()=="/"+scope.usehref+"/forgot") && isloop){
							activeArr[k]="active";
							isloop=false;
						}else if($location.path().indexOf("/"+scope.usehref+"/orderdtl")>-1 && v.id=="order" && isloop){
							
							activeArr[k]="active";
							isloop=false;
						}
					}
					
					if(tmparr.length>0){
						listarr[k]['child']=tmparr;
					}
					if(activeArr[k]=="active"){
						listarr[k]['active']="active";
						
					}
					
				});
				
				return listarr;
			}
			scope.tlist=activeChk(scope.tlist);
			scope.logout=function(){
				CRUD.update({task: "logout"}, "POST").then(function(res){
					location.href="index_page";
				});
			};
		}
    };
});

angular.module('goodarch2uApp').directive("multileftMenu", function (CRUD, sessionCtrl, $location) {

	return {
		restrict: "E",//element
		scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
			tlist: "=",
			usehref: "@"
		},
		template: '<div class="col-xs-12 col-sm-3 col-md-3" id="myGroup">' +
			'<div class="panel panel-default" ng-repeat="roottype in tlist track by roottype.id" on-finish-render="mleftmenu" ng-style="{\'margin-top\':\'20px\'}[$index>0]">' +
			'<div class="panel-heading">' +
			'<h4 class="panel-title" ng-if="((!roottype.loginonly && !roottype.logoutonly) || (roottype.loginonly && roottype.loginonly==loginonly) || (roottype.logoutonly && roottype.logoutonly==logoutonly)) && !roottype.hide && !roottype.shine">' +
			'<a data-toggle="collapse" data-parent="#myGroup"  href="" data-target="#collapseCategory{{$index}}" class="collapseWill" aria-expanded="true">' +
			'<span class="Left_nav" ><img src="templates/default/images/title_icon.png" alt=""/>&nbsp;{{roottype.name}}</span>' +
			'<span class="Left_nav_openi visible-xs" style="float: right;"><i class="fa fa-caret-down" aria-hidden="true"></i></span>' +
			'</a>' +
			'</h4>' +
			'<h4 class="panel-title shine" ng-if="((!roottype.loginonly && !roottype.logoutonly) || (roottype.loginonly && roottype.loginonly==loginonly) || (roottype.logoutonly && roottype.logoutonly==logoutonly)) && !roottype.hide && roottype.shine">' +
			'<a data-toggle="collapse" data-parent="#myGroup" href="" data-target="#collapseCategory{{$index}}" class="collapseWill" aria-expanded="true">' +
			'<span class="Left_nav" ><img src="templates/default/images/title_icon.png" alt=""/>&nbsp;{{roottype.name}}</span>' +
			'<span class="Left_nav_openi visible-xs" style="float: right;"><i class="fa fa-caret-down" aria-hidden="true"></i></span>' +
			'</a>' +
			'</h4>' +
			'</div>' +
			'<div id="collapseCategory{{$index}}" class="panel-collapse collapse" aria-expanded="true">' +
			'<div class="panel-body">' +
			'<ul class="nav nav-pills nav-stacked tree Left_nav_ul">' +
			'<li ng-class="list.active" on-finish-render="mleftmenu" ng-repeat="list in roottype.child track by $index" ng-show="((!list.loginonly && !list.logoutonly) || (list.loginonly && list.loginonly==loginonly) || (list.logoutonly && list.logoutonly==logoutonly)) && !list.hide">' +
			'<a ng-if="!list.fin && !list.fun && !list.limitshow" ng-href="{{usehref}}/{{list.id}}" id="{{list.id}}" ng-bind="list.name"></a>' +
			'<a ng-if="!list.fin && list.fun && !list.limitshow" href="javascript:void(0)" ng-click="logout()" ng-bind="list.name"></a>' +
			'<a ng-if="list.fin && !list.limitshow" ng-href="#" data-toggle="collapse" data-target=".multi-collapse"><i ng-if="list.fin" class="fa fa-angle-down" aria-hidden="true">{{list.name}}</i></a>' +
			'<a ng-if="list.limitshow && limitshow == 1" ng-href="{{usehref}}/{{list.id}}" id="{{list.id}}" ng-bind="list.name"></a>' +
			'<ul ng-class="list.active" ng-if="list.fin" class="nav nav-pills nav-stacked tree Left_nav_ul_2 panel-collapse collapse multi-collapse" aria-expanded="true">' +
			'<li class="sec_nav" ng-class="clist.active" ng-repeat="clist in list.child track by clist.id" on-finish-render="mleftmenu" ng-show="((!clist.loginonly && !clist.logoutonly) || (clist.loginonly && clist.loginonly==loginonly) || (clist.logoutonly && clist.logoutonly==logoutonly)) && !clist.hide">' +
			'<a ng-href="{{usehref}}/{{clist.id}}" ng-bind="clist.name"></a>' +
			'</li>' +
			'</ul>' +
			'</li>' +
			'</ul>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>',
		replace: true,
		link: function (scope) {

			CRUD.setUrl("components/member/api.php");

			CRUD.detail({ task: "loginStatus" }, "POST").then(function (res) {
				if (res.status == 1) {
					scope.loginonly = parseInt(res.data) == 1 ? true : false;
					scope.logoutonly = !scope.loginonly;
					scope.limitshow = res.stock_status;
				}
			});

			var isloop = true;
			var activeChk = function (listarr) {

				angular.forEach(listarr, function (v, k) {
					var tmparr = [];
					var activeArr = [];
					if (angular.isObject(v.child)) {
						tmparr = activeChk(v.child);

					} else {


						if ($location.path() == "/" + scope.usehref + "/" + v.id && isloop) {
							activeArr[k] = "active";
							scope.title = v.name;
							isloop = false;
						} else if (($location.path() == "/" + scope.usehref + "/signup" || $location.path() == "/" + scope.usehref + "/forgot") && isloop) {
							activeArr[k] = "active";
							isloop = false;
						} else if ($location.path().indexOf("/" + scope.usehref + "/orderdtl") > -1 && v.id == "order" && isloop) {

							activeArr[k] = "active";
							isloop = false;
						} else if ($location.path().indexOf("/" + scope.usehref + "/moneydtl") > -1 && v.id == "money_total" && isloop) {

							activeArr[k] = "active";
							isloop = false;
						} else if ($location.path().indexOf("/" + scope.usehref + "/ecash21_dtl") > -1 && v.id == "e_cash_new2_1" && isloop) {
							activeArr[k] = "active";
							isloop = false;
						} else if ($location.path().indexOf("/" + scope.usehref + "/ecash22_dtl") > -1 && v.id == "e_cash_new2_2" && isloop) {

							activeArr[k] = "active";
							isloop = false;
						}
					}

					if (tmparr.length > 0) {
						listarr[k]['child'] = tmparr;
					}
					if (activeArr[k] == "active") {
						listarr[k]['active'] = "active";

					}

				});

				return listarr;
			}
			scope.tlist = activeChk(scope.tlist);
			scope.logout = function () {
				CRUD.update({ task: "logout" }, "POST").then(function (res) {
					location.href = "index_page";
				});
			};
		}
	};
});

angular.module('goodarch2uApp').directive("onFinishRender", function ($timeout) {
	return {
		restrict: 'A',
		link: function (scope, element, attr) {
			if (scope.$last === true) {
				$timeout(function () {
					scope.$emit(attr.onFinishRender);
				});
			}
		}
	}
});

angular.module('goodarch2uApp').directive("productList",function(CRUD,$location,$route,$sce,store,sessionCtrl){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
			typeid: "=",
			eventid: "=",
            cur: "=",
            searchmode: "@",
            searchtext: "=",
            exttype:"@",
            usehref:"@"
        },
        template:'<div>'+
        			'<div class="col-xs-12 page_content" ng-class="{true:\'col-sm-12 col-md-12\',false:\'col-sm-9 col-md-9\'}[searchmode]">'+
	                  '<div class="row">'+
	                    '<div class="col-xs-12 page_content_title">'+
	                      '<h3 ng-if="searchmode==\'false\'" ng-bind="productlist_ctrl.typeName"></h3>'+
	                      '<center ng-if="searchmode==\'true\'"><h3 ng-bind="(\'lg_products.search_result\' | translate) +\'：\'+ searchtext"></h3></center>'+
	                    '</div>'+
	                  '</div>'+
	                  '<div class="row">'+
	                    '<div class="col-xs-12">'+
	                      '<div class="product_select">'+
	                        '<select size="1" name="D1" ng-model="productlist_ctrl.list_order" ng-change="productlist_ctrl.list_orderChg()" class="ng-pristine ng-valid">'+
		                          '<option value="" selected="" ng-bind="\'lg_directives.directives_order_type\' | translate">商品排序方式</option>'+
		                          '<option value="1" ng-bind="\'lg_directives.directives_order_type1\' | translate">新品上市</option>'+
		                          '<option value="2" ng-bind="\'lg_directives.directives_order_type2\' | translate">價格高至低</option>'+
		                          '<option value="3" ng-bind="\'lg_directives.directives_order_type3\' | translate">價格低至高</option>'+
	                        '</select>'+
	                        '<div style="float:right; width:auto;">'+
	                          '<a href="javascript:void(0)" ng-click="productlist_ctrl.displayChg(1)" ng-class="{\'1\':\'active\'}[productlist_ctrl.display_type]"><i class="glyphicon glyphicon-th-list icon_N"></i></a>'+
	                          '<a href="javascript:void(0)" ng-click="productlist_ctrl.displayChg(2)" ng-class="{\'2\':\'active\'}[productlist_ctrl.display_type]"><i class="glyphicon glyphicon-th-large icon_Y"></i></a>'+         
	                        '</div>'+
	                      '</div>'+
	                    '</div>'+
	                  '</div>'+
	                  '<div class="row">'+
	                	'<div class="Products_row" ng-show="productlist_ctrl.display_type==1">'+
		                    '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 product_row_bottom padding_0" ng-repeat-start="data in productlist_ctrl.data_list track by $index">'+
								'<div class="col-xs-12">'+
								    '<div class="col-xs-5 col-sm-4 col-md-5">'+
										'<a ng-href="{{usehref}}/{{data.typeid}}?id={{data.id}}"><img ng-src="{{data.img}}" alt="{{data.name}}"  class="img-responsive"></a>'+
								    '</div>'+
								    '<div class="col-xs-7 col-sm-8 col-md-7">'+
									    '<div class="col-xs-12 padding_0">'+
										    '<a ng-href="{{usehref}}/{{data.typeid}}?id={{data.id}}"><h4>{{data.name}}</h4></a>'+
										    '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')">{{("lg_money."+productlist_ctrl.currency) | translate}}{{data.siteAmt | formatnumber}}</p>'+
										    '<p ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'"><txt ng-bind="\'lg_directives.directives_bonus\' | translate">紅利</txt> {{data.bonusAmt | formatnumber}}<txt ng-bind="\'lg_directives.directives_point\' | translate">點</txt></p>'+
											'<p ng-if="productlist_ctrl.logoutonly"><span style="color: #BBB; font-size: 1em;"><txt><img ng-src="templates/default/images/favicon.png" height="23" width="23" style="border:0; margin-bottom: 3px;" /><txt ng-bind="\'lg_directives.directives_login_first\' | translate">登入後顯示價格</txt></txt></span></p>'+
									    '</div>'+
									    '<a ng-if="productlist_ctrl.loginonly" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)" ><div class="col-xs-12 col-sm-6 col-sm-offset-6 col-md-12 col-md-offset-0 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_add_cart2\' | translate">加入購物車</txt></div></a>'+
								    '</div>'+
								'</div>'+
							'</div>'+
							'<div ng-repeat-end ng-if="($index+1)%2==0" class="clearfix  visible-md visible-lg"></div>'+
						'</div>'+
	                    '<div class="col-xs-12 padding_0" ng-show="productlist_ctrl.display_type==2">'+
	                      '<div class="Products text-center">'+
	                        '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-3 product_row_bottom" ng-repeat-start="data in productlist_ctrl.data_list track by $index">'+
	                            '<div class="col-xs-12 padding_0">'+
	                              '<a ng-href="{{usehref}}/{{data.typeid}}?id={{data.id}}"><img ng-src="{{data.img}}" alt="{{data.name}}" / class="img-responsive center-block"></a>'+
	                              '<h4>{{data.name}}</h4>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')"><span><txt ng-if="data.siteAmt != data.highAmt" ng-bind="\'lg_directives.directives_siteAmt\' | translate">特惠價</txt>{{("lg_money."+productlist_ctrl.currency) | translate}} {{data.siteAmt | formatnumber}}</span></p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\') && (data.siteAmt != data.highAmt)" style=\"margin: 0 0 3px;\"><span style="font-size: 0.8em; color: #BBB; text-decoration: line-through;"><txt ng-bind="\'lg_directives.directives_highAmt\' | translate">原價</txt>{{("lg_money."+productlist_ctrl.currency) | translate}} {{data.highAmt | formatnumber}}</span></p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\') && !(data.siteAmt != data.highAmt)" style=\"margin: 0 0 3px;\">&nbsp;</p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'"><span><txt ng-bind="\'lg_directives.directives_bonus\' | translate">紅利</txt> {{data.bonusAmt | formatnumber}}<txt ng-bind="\'lg_directives.directives_point\' | translate">點</txt></span></p>'+
	                              '<a ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)"><div class="col-xs-10 col-xs-offset-1 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_add_cart2\' | translate">加入購物車</txt></div></a>'+
	                              '<a ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)"><div class="col-xs-10 col-xs-offset-1 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_go_exchange2\' | translate">加入兌換</txt></div></a>'+
								  '<p ng-if="productlist_ctrl.logoutonly"><span style="color: #BBB; font-size: 1em;"><txt><img ng-src="templates/default/images/favicon.png" height="23" width="23" style="border:0; margin-bottom: 3px;" /><txt ng-bind="\'lg_directives.directives_login_first\' | translate">登入後顯示價格</txt></txt></span></p>'+
	                            '</div>'+
	                        '</div>'+
							'<div ng-if="($index+1)%2==0" class="clearfix visible-xs visible-md visible-sm"></div>'+
							'<div ng-repeat-end ng-if="$index%4 == 3" class="clearfix  visible-md visible-lg"></div>'+
	                      '</div>'+
	                    '</div>'+
	                    
	                  '</div>'+
	                    '<div class="col-xs-12 padding_0">'+
	                      '<div class="text-center">'+
	                        '<page-ctrl-el cnt="productlist_ctrl.cnt"></page-ctrl-el>'+
	                      '</div>'+
	                    '</div>'+
	              '</div>'+
		              /*
		            	modalindex:第幾個modal
		            	proid:商品id
		            	proname:商品名
		            	proimg:商品圖片
		            	prositeamt:商品售價
		            	proformat1:商品規格1
		            	proformat2:商品規格2
		            	proformat1title:規格1標題
		            	proformat2title:規格2標題
						formatonly:單一規格
		            	format1only:規格1單一規格
		            	format2only:規格2單一規格
		              */
		              '<div ng-if="!exttype && exttype !=\'event\'">'+
		              '<cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.prositeAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" proformat22="productlist_ctrl.proformat22" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only"></cart-modal>'+
		              '</div>'+
		              '<div ng-if="exttype==\'bonus\'">'+
		              '<bonus-cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.probonusAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only" ></bonus-cart-modal>'+
		              '</div>'+
					  '<div ng-if="exttype==\'event\'">'+
		              '<cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.prositeAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" proformat22="productlist_ctrl.proformat22" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only" protype="event" eventdid="{{typeid}}" ></cart-modal>'+
		              '</div>'+
            	'</div>',
		replace: true,
		controllerAs: 'productlist_ctrl',
		controller:function($scope,$location,store){
			var self=this;
			
			self.lang = sessionCtrl.get('_lang');
			self.currency = sessionCtrl.get('_currency');
			
			self.index=1;
			if(!$scope.usehref)$scope.usehref="product_page";
			
			//檢查登入
			self.loginStatus = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("components/member/api.php");
				CRUD.detail({task: "loginStatus"}, "POST").then(function(res){
					if(res.status == 1) {
						self.loginonly = parseInt(res.data)==1?true:false;
						self.logoutonly=!self.loginonly;
						self.om = res.onlymember;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.loginStatus();
			
			//預設使用圖片
			if(!self.display_type)self.display_type=store.get("display_type")?store.get("display_type"):2;
			if(!self.list_order)self.list_order=store.get("list_order")?store.get("list_order"):'';
			self.list = function(loading){
				if($scope.exttype){
					CRUD.setUrl("components/"+$scope.exttype+"/api.php");
				}else{
					CRUD.setUrl("components/product/api.php");
				}
				var param={
						task: "productlist",
						cur:$scope.cur,
						display_type:self.display_type,
						orderType:self.list_order
					};
					
				if($scope.searchmode=='true'){
					
					param.searchtext=$scope.searchtext;
				}else{
					param.typeid=$scope.typeid;
					param.eventid=$scope.eventid;
				}
				
				
				CRUD.list(param, "GET",loading).then(function(res){
					if(res.status == 1) {
						self.data_list = res.data;
						self.cnt = res.cnt;
						self.typeName = res.typeName;
						
					}
				});
			};
			
			$scope.$watch('typeid',function(v){
				
				if(!isNaN(v)){
					self.list(true);
				}
			});
			
			$scope.$watch('eventid',function(v){
				
				if(!isNaN(v)){
					self.list(true);
				}
			});
			
			self.show_cart_modal=function(id){
				
				angular.forEach(self.data_list,function(v,k){
					if(v.id==id){
						self.proid=v.id;
						self.proname=v.name;
						self.proimg=v.img;
						self.prositeAmt=v.siteAmt;
						self.probonusAmt=v.bonusAmt;
						self.proformat1title=v.format.format1title;
						self.proformat2title=v.format.format2title;
						self.proformat1=v.format.format1;
						self.proformat22=v.format.format2;
						self.proformat2=v.format.format22;
						self.formatonly=v.format.formatonly;
						self.format1only=v.format.format1only;
						self.format2only=v.format.format2only;
					}
				});
				
			};
			
			self.list_orderChg=function(){
				store.set("list_order",self.list_order);
				self.list(true);
			};
			
			self.displayChg=function(type){
				self.display_type=type;
				store.set("display_type",type);
				self.list(false);
			};
			self.list();
　　　　}
    };
});

angular.module('goodarch2uApp').directive("productList2",function(CRUD,$location,$route,$sce,store){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
			typeid: "=",
			eventid: "=",
            cur: "=",
            searchmode: "@",
            searchtext: "=",
            exttype:"@",
            usehref:"@"
        },
        template:'<div>'+
        			'<div class="col-xs-12 page_content" ng-class="{true:\'col-sm-12 col-md-12\',false:\'col-sm-9 col-md-9\'}[searchmode]">'+
	                  '<div class="row">'+
	                    '<div class="col-xs-12 page_content_title">'+
	                      '<h3 ng-if="searchmode==\'false\'" ng-bind="productlist_ctrl.typeName"></h3>'+
	                      '<center ng-if="searchmode==\'true\'"><h3 ng-bind="(\'lg_products.search_result\' | translate) +\'：\'+ searchtext"></h3></center>'+
	                    '</div>'+
	                  '</div>'+
	                  '<div class="row">'+
	                    '<div class="col-xs-12">'+
	                      
	                    '</div>'+
	                  '</div>'+
	                  '<div class="row">'+
	                	'<div class="Products_row" ng-show="productlist_ctrl.display_type==1">'+
		                    '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 product_row_bottom padding_0" ng-repeat-start="data in productlist_ctrl.data_list track by $index">'+
								'<div class="col-xs-12">'+
								    '<div class="col-xs-5 col-sm-4 col-md-5">'+
										'<a ng-href="{{usehref}}/{{data.typeid}}?id={{data.id}}"><img ng-src="{{data.img}}" alt="{{data.name}}"  class="img-responsive"></a>'+
								    '</div>'+
								    '<div class="col-xs-7 col-sm-8 col-md-7">'+
									    '<div class="col-xs-12 padding_0">'+
										    '<a ng-href="{{usehref}}/{{data.typeid}}?id={{data.id}}"><h4>{{data.name}}</h4></a>'+
										    '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')">NT.{{data.siteAmt | formatnumber}}</p>'+
										    '<p ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'">紅利 {{data.bonusAmt | formatnumber}}點</p>'+
											'<p ng-if="productlist_ctrl.logoutonly"><span style="color: #BBB; font-size: 1em;"><txt><img ng-src="templates/default/images/favicon.png" height="23" width="23" style="border:0; margin-bottom: 3px;" />登入後顯示價格</txt></span></p>'+
									    '</div>'+
									    // '<a ng-if="productlist_ctrl.loginonly" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)" ><div class="col-xs-12 col-sm-6 col-sm-offset-6 col-md-12 col-md-offset-0 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;加入購物車</div></a>'+
								    '</div>'+
								'</div>'+
							'</div>'+
							'<div ng-repeat-end ng-if="($index+1)%2==0" class="clearfix  visible-md visible-lg"></div>'+
						'</div>'+
	                    '<div class="col-xs-12 padding_0" ng-show="productlist_ctrl.display_type==2">'+
	                      '<div class="Products text-center">'+
	                        '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-3 product_row_bottom" ng-repeat-start="data in productlist_ctrl.data_list track by $index">'+
	                            '<div class="col-xs-12 padding_0">'+
	                              '<a ng-href="active_list/{{typeid}}?id={{data.id}}"><img ng-src="{{data.img}}" alt="{{data.name}}" / class="img-responsive center-block"></a>'+
	                              '<h4>{{data.name}}</h4>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')"><span><txt ng-if="data.siteAmt != data.highAmt">特惠價</txt>NT. {{data.siteAmt | formatnumber}}</span></p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\') && (data.siteAmt != data.highAmt)" style=\"margin: 0 0 3px;\"><span style="font-size: 0.8em; color: #BBB; text-decoration: line-through;">原價NT. {{data.highAmt | formatnumber}}</span></p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\') && !(data.siteAmt != data.highAmt)" style=\"margin: 0 0 3px;\">&nbsp;</p>'+
	                              '<p ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'"><span>紅利 {{data.bonusAmt | formatnumber}}點</span></p>'+
	                            //   '<a ng-if="productlist_ctrl.loginonly && (!exttype || exttype==\'event\')" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)"><div class="col-xs-10 col-xs-offset-1 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;加入購物車</div></a>'+
	                            //   '<a ng-if="productlist_ctrl.loginonly && exttype==\'bonus\'" href="javascript:void(0)" data-toggle="modal" data-target="#modal_addtocart{{productlist_ctrl.index}}" ng-click="productlist_ctrl.show_cart_modal(data.id)"><div class="col-xs-10 col-xs-offset-1 shopping_button"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;&nbsp;加入兌換</div></a>'+
								  '<p ng-if="productlist_ctrl.logoutonly"><span style="color: #BBB; font-size: 1em;"><txt><img ng-src="templates/default/images/favicon.png" height="23" width="23" style="border:0; margin-bottom: 3px;" />登入後顯示價格</txt></span></p>'+
	                            '</div>'+
	                        '</div>'+
							'<div ng-if="($index+1)%2==0" class="clearfix visible-xs visible-md visible-sm"></div>'+
							'<div ng-repeat-end ng-if="$index%4 == 3" class="clearfix  visible-md visible-lg"></div>'+
	                      '</div>'+
	                    '</div>'+
	                    
	                  '</div>'+
	                    '<div class="col-xs-12 padding_0">'+
	                      '<div class="text-center">'+
	                        '<page-ctrl-el cnt="productlist_ctrl.cnt"></page-ctrl-el>'+
	                      '</div>'+
	                    '</div>'+
	              '</div>'+
		              /*
		            	modalindex:第幾個modal
		            	proid:商品id
		            	proname:商品名
		            	proimg:商品圖片
		            	prositeamt:商品售價
		            	proformat1:商品規格1
		            	proformat2:商品規格2
		            	proformat1title:規格1標題
		            	proformat2title:規格2標題
						formatonly:單一規格
		            	format1only:規格1單一規格
		            	format2only:規格2單一規格
		              */
		              '<div ng-if="!exttype && exttype !=\'event\'">'+
		              '<cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.prositeAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" proformat22="productlist_ctrl.proformat22" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only"></cart-modal>'+
		              '</div>'+
		              '<div ng-if="exttype==\'bonus\'">'+
		              '<bonus-cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.probonusAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only" ></bonus-cart-modal>'+
		              '</div>'+
					  '<div ng-if="exttype==\'event\'">'+
		              '<cart-modal modalindex="productlist_ctrl.index" proid="productlist_ctrl.proid" proname="productlist_ctrl.proname" proimg="productlist_ctrl.proimg" '+
		              'prositeamt="productlist_ctrl.prositeAmt" proformat1="productlist_ctrl.proformat1" proformat2="productlist_ctrl.proformat2" proformat22="productlist_ctrl.proformat22" '+
		              'proformat1title="productlist_ctrl.proformat1title" proformat2title="productlist_ctrl.proformat2title" formatonly="productlist_ctrl.formatonly" format1only="productlist_ctrl.format1only" format2only="productlist_ctrl.format2only" protype="event" eventdid="{{typeid}}" ></cart-modal>'+
		              '</div>'+
            	'</div>',
		replace: true,
		controllerAs: 'productlist_ctrl',
		controller:function($scope,$location,store){
			var self=this;
			self.index=1;
			if(!$scope.usehref)$scope.usehref="product_page";
			
			//檢查登入
			self.loginStatus = function() {
				var ourl=CRUD.getUrl();
				CRUD.setUrl("components/member/api.php");
				CRUD.detail({task: "loginStatus"}, "POST").then(function(res){
					if(res.status == 1) {
						self.loginonly = parseInt(res.data)==1?true:false;
						self.logoutonly=!self.loginonly;
					}
				});
				CRUD.setUrl(ourl);
			}
			self.loginStatus();
			
			//預設使用圖片
			if(!self.display_type)self.display_type=store.get("display_type")?store.get("display_type"):2;
			if(!self.list_order)self.list_order=store.get("list_order")?store.get("list_order"):'';
			self.list = function(loading){
				if($scope.exttype){
					CRUD.setUrl("components/"+$scope.exttype+"/api.php");
				}else{
					CRUD.setUrl("components/product/api.php");
				}
				var param={
						task: "productlist",
						cur:$scope.cur,
						display_type:self.display_type,
						orderType:self.list_order
					};
					
				if($scope.searchmode=='true'){
					
					param.searchtext=$scope.searchtext;
				}else{
					param.typeid=$scope.typeid;
					param.eventid=$scope.eventid;
				}
				
				
				CRUD.list(param, "GET",loading).then(function(res){
					if(res.status == 1) {
						self.data_list = res.data;
						self.cnt = res.cnt;
						self.typeName = res.typeName;
						
					}
				});
			};
			
			$scope.$watch('typeid',function(v){
				
				if(!isNaN(v)){
					self.list(true);
				}
			});
			
			$scope.$watch('eventid',function(v){
				
				if(!isNaN(v)){
					self.list(true);
				}
			});
			
			self.show_cart_modal=function(id){
				
				angular.forEach(self.data_list,function(v,k){
					if(v.id==id){
						self.proid=v.id;
						self.proname=v.name;
						self.proimg=v.img;
						self.prositeAmt=v.siteAmt;
						self.probonusAmt=v.bonusAmt;
						self.proformat1title=v.format.format1title;
						self.proformat2title=v.format.format2title;
						self.proformat1=v.format.format1;
						self.proformat22=v.format.format2;
						self.proformat2=v.format.format22;
						self.formatonly=v.format.formatonly;
						self.format1only=v.format.format1only;
						self.format2only=v.format.format2only;
					}
				});
				
			};
			
			self.list_orderChg=function(){
				store.set("list_order",self.list_order);
				self.list(true);
			};
			
			self.displayChg=function(type){
				self.display_type=type;
				store.set("display_type",type);
				self.list(false);
			};
			self.list();
　　　　}
    };
});

angular.module('goodarch2uApp').directive("plusProduct",function(CRUD,sessionCtrl,$location){

    return {
        restrict: "E",//element
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            tlist: "=",
            usehref: "@"
        },
        template:'<div class="col-xs-12 col-sm-3 col-md-3">'+
	                '<div class="panel panel-default" ng-repeat="roottype in tlist track by roottype.id" ng-style="{\'margin-top\':\'20px\'}[$index>0]">'+
	                  '<div class="panel-heading">'+
	                    '<h4 class="panel-title">'+
	                      '<a data-toggle="collapse" href="javascript:void(0)" data-target="#collapseCategory{{$index}}" class="collapseWill" aria-expanded="true">'+
	                        '<span class="Left_nav"><img src="templates/default/images/title_icon.png" alt=""/>&nbsp;{{roottype.name}}</span>'+
	                        '<span class="Left_nav_openi visible-xs" style="float: right;"><i class="fa fa-caret-down" aria-hidden="true"></i></span>'+
	                      '</a>'+
	                    '</h4>'+
	                  '</div>'+
	                  '<div id="collapseCategory{{$index}}" class="panel-collapse collapse in" aria-expanded="true">'+
	                    '<div class="panel-body">'+
	                      '<ul class="nav nav-pills nav-stacked tree Left_nav_ul">'+
	                        '<li ng-class="list.active" ng-repeat="list in roottype.child track by list.id" ng-show="((!list.loginonly && !list.logoutonly) || (list.loginonly && list.loginonly==loginonly) || (list.logoutonly && list.logoutonly==logoutonly)) && !list.hide">'+
	                          '<a ng-show="roottype.id && !list.fun" ng-href="{{usehref}}/{{roottype.id}}?id={{list.id}}" ng-bind="list.name"></a>'+
	                          '<a ng-show="!roottype.id && !list.fun " ng-href="{{usehref}}/{{list.id}}" ng-bind="list.name"></a>'+
	                          '<a ng-show="list.fun " href="javascript:void(0)" ng-click="logout()" ng-bind="list.name"></a>'+
	                        '</li>'+
	                      '</ul>'+
	                    '</div>'+
	                  '</div>'+
	                '</div>'+
	            '</div>',
		replace: true,
		link:function(scope){
			
			CRUD.setUrl("components/member/api.php");
	
			CRUD.detail({task: "loginStatus"}, "POST").then(function(res){
				if(res.status == 1) {
					scope.loginonly = parseInt(res.data)==1?true:false;
					scope.logoutonly=!scope.loginonly;
				}
			});
	
			
			var isloop=true;
			angular.forEach(scope.tlist,function(v,k){
				if(isloop){
					angular.forEach(v,function(v2,k2){
						if(angular.isObject(v2) && isloop){
							angular.forEach(v2,function(v3,k3){
								var tmpk3="";
								if(v3.id=="login"){
									tmpk3=k3;
								}
								if($location.path()=="/"+scope.usehref+"/"+v3.id && isloop){
									
									scope.tlist[k][k2][k3]['active']="active";
									scope.title=v3.name;
									isloop=false;
								}else if($location.path()=="/"+scope.usehref+"/signup" && isloop){
									scope.tlist[k][k2][tmpk3]['active']="active";
									isloop=false;
								}
							});
						}
					});
				}
			});
			
			scope.logout=function(){
				CRUD.update({task: "logout"}, "POST").then(function(res){
					location.href="index_page";
				});
			};
		}
    };
});

angular.module('goodarch2uApp').directive('addthisContent', function($timeout,$location) {
	
	function initShareContent(data){
		
		$timeout(function(){
			if(data._content){
				if(!addthis){
					initShareContent(data);
				}else{
					
					var siteurl=$location.protocol()+"://"+$location.host()+$location.path();
					if(data._content_imgcode){
						$location.search().code=data._content_imgcode;
						var i=0;
						angular.forEach($location.search(),function(v,k){
							if(i==0){
								siteurl+="?";
							}else{
								siteurl+="&";
							}
							siteurl+=k+"="+v;
							i++;
						});
						
					}
					addthis.update('share', 'url', siteurl);
					addthis.update('share', 'title', data._name);
					addthis.update('share', 'description', data._content);
					
				}
			}
			
			if(addthis){
				if (addthis.layers && addthis.layers.refresh) {
					addthis.layers.refresh();
				}
			}
		},500);
	}
    return {
        restrict: "E",//element
        template:'<div class="addthis_sharing_toolbox"></div>',
		replace: true,
		controller:function($scope,$location){
			try{
				if(document.getElementById("addthis_lib")){
					document.getElementById("addthis_lib").remove();
				}
				var js = document.createElement("script");
				js.type = "text/javascript";
				js.id = "addthis_lib";
				js.src = "https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57456adebf26c371";
				document.body.appendChild(js);
				
				initShareContent($scope);
			}catch(e){
			}
		}
		
		
    };
}).directive("textContent",function($sce){

    return {
        restrict: "E",//element
        template:'<txt class="textContent" ng-bind-html="textContentCtrl.content"></txt>',
		replace: true,
		controllerAs: 'textContentCtrl',
		scope:{
		    text:"=text"
		},
		controller:function($scope){
		    var self=this;
		    
		    $scope.$watch(function(){
		        return $scope.text;
		    },function(v){
		        if(v){
		            self.content=$sce.trustAsHtml(v);
		        }
		    });
		    
		}
    };
});