function msgStyle(msg){
	return "<font size=5 color='000000'>"+msg+"</font>";
}
app.controller('app_ctrl', function($rootScope, $scope, $http, $location, templateCtrl, sessionCtrl,$translate) {
	var my = this;
	$rootScope.$on('$locationChangeStart',function(event,n,o){
		$(".modal-backdrop.in").hide();
		$('body').removeClass('modal-open');
		var path = $location.path();
		var host = $location.host();
		var protocol = $location.protocol();
		if (path == '/search' && host == 'webcache.googleusercontent.com') {
			var search = $location.search();
			//var matches = search.q.match(/(((https?:\/\/)|(www\.))[^\s]+)/g);
			var tmp = search.q.split(":");
			var url = tmp[tmp.length - 1];
			if (protocol == 'https') {
				url = 'https:' + url;
			} else {
				url = 'http://' + url;
			}
			var urlObj = new URL(url);
			
			$location.url(urlObj.pathname+urlObj.search).replace();
		}		
	});
	$rootScope.$on("event:templatechg", function() {
		my.page = templateCtrl.CurrentPage;
	});
	
	templateCtrl.loadTemplate();
	my.plswaittext = $translate.instant('lg_index.index_wait');
	if(my.plswaittext=='lg_index.index_wait'){
		if(syslang=='zh-tw'){
			my.plswaittext='請稍後';
		}else if(syslang=='zh-cn'){
			my.plswaittext='请稍后';
		}else if(syslang=='in'){
			my.plswaittext='tunggu sebentar';
		}else{
			my.plswaittext='Please wait';
		}
	}
	
	
});

app.controller('main_ctrl',["$rootScope","$scope", "$http", "$timeout", "$translate", "CRUD","$window","$location","store","urlCtrl","sessionCtrl",function($rootScope, $scope, $http, $timeout, $translate, CRUD,$window,$location,store,urlCtrl,sessionCtrl) {
	var my = this;
	
	$rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
		my.mainmenu_path=$location.path();
		if(current.params.productlistid) {
			my.productlistid = current.params.productlistid;
		} else {
			my.productlistid = 0;
		}
		if(my.mainmenus){
			my.set_now_menu();
		}
		
		try{
			if(ga){
				ga('send', 'pageview',$location.url());
			}
		}catch(e){
			
		}
    });
	try{
		var heightStr = (window.orientation == 90 || window.orientation == -90) ? '200px':'500px';
		$('.navMenu9999').css({'max-height':heightStr});
		window.addEventListener("orientationchange", function() {
		  var heightStr = (window.orientation == 90 || window.orientation == -90) ? '200px':'500px';
		  $('.navMenu9999').css({'max-height':heightStr});
		});
		$( window ).resize(function() {
			$('#navMenu').css({'height':document.documentElement.clientHeight});
		});
	}catch(e){
	}
	
	
	$rootScope.$on('$routeChangeStart', function (event, current, previous) {
		if(previous.controller == 'active_list')
		{
			var chk=0;
			angular.forEach(previous.scope.ctrl.detailList,function(v){
				if(v.selectedSpec==1){
					chk++;
				}
			});
			//有設定過活動商品
			if(chk > 0)
			{
				//挑選商品會清空會需要重新選擇，是否確定離開此頁面？
				if (!confirm($translate.instant('lg_eways.eways_msg1'))) { 
					event.preventDefault();
				}
			}
		}		
	});
    
    my.set_now_menu = function(){
		if(my.menuimg){	
			keepGoing=true;
			angular.forEach(my.mainmenus.root,function(v,k){
				if(keepGoing) {
					var link=v.linkurl.split("?");
					link=link[0].split("_");
					var linkid="";
					if(link[1]){
						linkid=link[1].split("/");
						linkid=linkid[1];
					}else{
						linkid='';
					}
					link="/"+link[0];
					my.mainmenuid=0;
					if(my.mainmenu_path){
						var mainmenu_path=my.mainmenu_path.split("/");
						mainmenu_pathid=mainmenu_path[2];
						
						
				    	if(my.mainmenu_path.indexOf(link)>-1 && (mainmenu_pathid==linkid || !linkid)){
				    		my.mainmenuid=v.id;
				    		store.set("mainmenuid",my.mainmenuid);
				    		keepGoing=false;
				    	}else{
				    		store.set("mainmenuid",0);
				    		my.mainmenuid=0;
				    	}
					}
				}
		    });
		    
		}else{
			my.mainmenuid=store.get("mainmenuid");
		}
		
		if(my.mainmenuid && my.menuimg){
			
			my.menubanner = my.menuimg[my.mainmenuid];
			
		}
    };
    
    
	var turl="";
	my.langChg = function(langKey) {
		$translate.use(langKey);
    };
	
	my.mainmenulist = function() {
		turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.list({task: "mainmenulist"}, "GET").then(function(res){
			if(res.status == 1) {
				my.mainmenus = res.data;
				my.menuimg = res.menuimg;
				
				my.set_now_menu();
			}
		});
		CRUD.setUrl(turl);
	}
	$scope.$watch('mainctrl.mainmenu_path', function(newval){
		
		if(my.mainmenus){
		    angular.forEach(my.mainmenus.root,function(v,k){
		    	if(newval=="/"+v.linkurl){
		    		$scope.mainmenuid=v.id;
		    	}else{
		    		$scope.mainmenuid=0;
		    	}
		    });
		}
	});
	
				
	my.bottommenulist = function() {
		turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.list({task: "bottommenulist"}, "GET").then(function(res){
			if(res.status == 1) {
				my.bottommenus = res.data;
			}
		});
		CRUD.setUrl(turl);
	}
	my.siteinfo = function() {
		turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.list({task: "siteinfo"}, "GET").then(function(res){
			if(res.status == 1) {
				my.siteinfo = res.data;
				my.langList = res.langList;
			}
		});
		CRUD.setUrl(turl);
	}
	my.get_cart_num = function() {
		turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.list({task: "get_cart_num"}, "GET").then(function(res){
			$rootScope.cartCnt=res.cnt;
		});
		CRUD.setUrl(turl);
	}
	my.get_cart_num();
	my.mainmenulist();
	my.bottommenulist();
	my.siteinfo();
	$scope.$watch('cartCnt', function(value){
	    my.cart_url=value==0 ? "javascript:error('"+ $translate.instant('lg_eways.eways_cart_empty') +"')" : 'cart_list';
	});
	my.top=function(){
		$window.scrollTo(0, 0);
	};
	
	my.go=function(path){
		location.href=path;
		
	};
	my.go_cart=function(){
		my.get_cart_num();
		if($rootScope.cartCnt==0){
			//您的購物車為空
			error($translate.instant('lg_eways.eways_cart_empty'));
		}else{
			$location.search({});
			$location.path("cart_list");
		}
		
	};
	
	my.clickmenu=function(){
		$('#navbar').toggleClass('in');
		
	};
	
	turl=CRUD.getUrl();
	CRUD.setUrl("components/member/api.php");
	CRUD.detail({task: "loginStatus"}, "GET").then(function(res){
		if(res.status == 1) {
			$scope.member_status = parseInt(res.data);
			$scope.om = parseInt(res.onlymember);
		}
	});
	
	CRUD.setUrl(turl);
	
	
	
	my.search=function(){
		if(my.search_text){
			urlCtrl.go("product_list?q="+my.search_text);
		}
	};
	
	my.index_logout=function(){
		turl=CRUD.getUrl();
		CRUD.setUrl("components/member/api.php");		
		CRUD.update({task: "logout"}, "POST").then(function(res){
			$scope.member_status=0;
			location.href="index_page";
		});	
		CRUD.setUrl(turl);
	};
	
	my.set_lang=function(lang){
		lang = (lang) ? lang : 'zh-tw';
		sessionCtrl.set('_lang',lang);
		var path=$location.path();
		var search=$location.search();
		var str='';
		angular.forEach(search,function(v,k){
			if(k!='lang' && k!='setLang'){
				str+=k+'='+v+'&';
			}
		});
		if(str){
			location.href=path+'?'+str+'lang='+lang+'&setLang=1';
		}else{
			location.href=path+'?lang='+lang+'&setLang=1';
		}
	};

	my.get_card = function () {
      
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.list({ task: "get_card" }, "GET").then(function (res) {
		  if (res.status == 1) {
			my.card_om = res.om;
			$scope.om = res.om;
			$scope.$parent.om = res.om;
			my.barcode_64 = 1;
  
			$("#show_card_btn").show();
			$("#card_svg").append(res.barcode_64);
			if (res.om == "0") {
			  $(".card_title").text($translate.instant('lg_member.details_setting_om_status_0'));
			  $(".n_center").text($translate.instant('lg_member.distributor_center'));
			  $("#card_qr1i").append(res.qr1);
			  $("#card_qr2i").append(res.qr2);
			} else {
			  $(".card_title").text($translate.instant('lg_member.details_setting_om_status_1'));
			  $(".n_center").text($translate.instant('lg_member.member_center'));
			  $("#card_choice").hide();
			}
  
			$("#card_name").append(res.mb_name);
			//console.log(res.om);
  
			var $width = $(window).width();
			var $height = $(window).height();
			if ($width > $height) {
			  $("#card_svg").removeClass("degree_90");
			  $("#card_svg").removeClass("d_center");
			  $("#vip_card").hide();
			  // $('#show_card .modal-footer').hide();
			  $("#card_svg").find("svg").css("width", "100%");
			  $("#card_svg").find("svg").css("height", "170px");
  
			  $("#card_svg svg").attr("viewBox", "0 0 170 100");
			  if ($height < 500) {
				$("#show_card .modal-footer").hide();
			  }
			} else if ($width < $height) {
			  // $('#card_svg').addClass('degree_90');
			  $("#card_svg").addClass("d_center");
			  $("#vip_card").show();
			  $("#show_card .modal-footer").show();
			  $("#card_svg").find("svg").css("width", "100%");
			  $("#card_svg").find("svg").css("height", "170px");
			  $("#card_svg svg").attr("viewBox", "0 0 170 100");
			  if ($height < 500) {
				$("#show_card .modal-footer").hide();
			  }
			} else {
			  // $('#card_svg').addClass('degree_90');
			  $("#card_svg").addClass("d_center");
			  $("#vip_card").show();
			  $("#show_card .modal-footer").show();
			  $("#card_svg").find("svg").css("width", "100%");
			  $("#card_svg").find("svg").css("height", "170px");
			  $("#card_svg svg").attr("viewBox", "0 0 170 100");
			  if ($height < 500) {
				$("#show_card .modal-footer").hide();
			  }
			}
		  } else {
			$("#show_card_btn").hide();
		  }
		});
	  };
  
	  $.attrHooks["viewbox"] = {
		set: function (elem, value, name) {
		  elem.setAttributeNS(null, "viewBox", value + "");
		  return value;
		},
	  };
  
	  my.get_card();
  
	  $(window).resize(function () {
		var $width = $(window).width();
		var $height = $(window).height();
		if ($width > $height) {
		  $("#card_svg").removeClass("degree_90");
		  $("#card_svg").removeClass("d_center");
		  $("#vip_card").hide();
		  $("#show_card .modal-footer").hide();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		} else if ($width < $height) {
		  // $('#card_svg').addClass('degree_90');
		  $("#card_svg").addClass("d_center");
		  $("#vip_card").show();
		  $("#show_card .modal-footer").show();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		} else {
		  // $('#card_svg').addClass('degree_90');
		  $("#card_svg").addClass("d_center");
		  $("#vip_card").show();
		  $("#show_card .modal-footer").show();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		}
	  });
  
	  window.onorientationchange = function () {
		var orientation = window.orientation;
		var $width = $(window).width();
		var $height = $(window).height();
		// Look at the value of window.orientation:
  
		if (orientation === 0) {
		  // $('#card_svg').addClass('degree_90');
		  $("#card_svg").addClass("d_center");
		  $("#vip_card").show();
		  $("#show_card .modal-footer").show();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		} else if (orientation === 90) {
		  $("#card_svg").removeClass("degree_90");
		  $("#card_svg").removeClass("d_center");
		  $("#vip_card").hide();
		  $("#show_card .modal-footer").hide();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		} else if (orientation === -90) {
		  $("#card_svg").removeClass("degree_90");
		  $("#card_svg").removeClass("d_center");
		  $("#vip_card").hide();
		  $("#show_card .modal-footer").hide();
		  $("#card_svg").find("svg").css("width", "100%");
		  $("#card_svg").find("svg").css("height", "170px");
		  $("#card_svg svg").attr("viewBox", "0 0 170 100");
		  if ($height < 500) {
			$("#show_card .modal-footer").hide();
		  }
		}
	  };
  
	  my.close_card = function () {
		//console.log('remove');
		$("#show_card").removeClass("in");
	  };
  
	  my.card_type = function (type) {
		$(".card_info").hide();
		if (type == 1) {
		  $("#card_svg").show();
		} else if (type == 2) {
		  $("#card_qr1").show();
		} else if (type == 3) {
		  $("#card_qr2").show();
		}
	  };
	
}]);