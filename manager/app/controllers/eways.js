app.controller('app_ctrl', function($rootScope, $scope, $http, $location, templateCtrl, sessionCtrl,urlCtrl,$translate) {
	var my = this;

	/*
	每10分鐘更新session
	*/
	var sessionalive = setInterval(function(){
		$.ajax({
			type: "GET",
			async: false,
			url: "controller/php/common.php",
			data: {task: "sessionTimeReset"},
			dataType: "jsonp",
			jsonpCallback:"callback",
			success: function(res){},
			error: function(){}
		});
	},600000);
	$rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
		var param = urlCtrl.deaes($location.hash());
		if(param && param.setLang==1){
			sessionCtrl.set('_lang',param.lang);
			$translate.use(param.lang);
		}else{
			$translate.use(sessionCtrl.get('_lang'));
		}
    });
	$rootScope.$on("event:templatechg", function() {
		my.page = templateCtrl.CurrentPage;
	});
	
	templateCtrl.loadTemplate();
	my.logout = function (){
		var uid = sessionCtrl.getuid();
		clearInterval(sessionalive);
		var realurl = "controller/php/common.php"+"?callback=JSON_CALLBACK&task=adminLogout&id="+uid;
		$http.jsonp(realurl).success(function (data, status, headers, config) {
			templateCtrl.gotoLogin();
		});
		sessionCtrl.removeAll();
	};
	
});

app.controller('main_ctrl', function($rootScope, $route, $scope, $http, $location, Excel, $timeout, $sce, $translate,urlCtrl,store) {
	var my = this;
	
	
	$(function($) {
		(function($,sr){
			// debouncing function from John Hann
			// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
			var debounce = function (func, threshold, execAsap) {
				var timeout;
		
				return function debounced () {
					var obj = this, args = arguments;
					function delayed () {
						if (!execAsap)
							func.apply(obj, args);
						timeout = null;
					};
		
					if (timeout)
						clearTimeout(timeout);
					else if (execAsap)
						func.apply(obj, args);
		
					timeout = setTimeout(delayed, threshold || 100);
				};
			}
			// smartresize 
			jQuery.fn[sr] = function(fn){	return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
		
		})(jQuery,'smartresize');
		setTimeout(function() {
			$('#content-wrapper > .row').css({
				opacity: 1
			});
		}, 200);
		
		$('#sidebar-nav,#nav-col-submenu').on('click', '.dropdown-toggle', function (e) {
			e.preventDefault();
			var $item = $(this).parent();
	
			if (!$item.hasClass('open')) {
				$item.parent().find('.open .submenu').slideUp('fast');
				$item.parent().find('.open').toggleClass('open');
			}
			
			$item.toggleClass('open');
			
			if ($item.hasClass('open')) {
				$item.children('.submenu').slideDown('fast');
			} 
			else {
				$item.children('.submenu').slideUp('fast');
			}
		});
		
		
		$('body').click(function(e) {
			//console.log('test');
			//$('#sidebar-nav').removeClass('in');	
		});
		
		$('#mbody').on('mouseenter', '#page-wrapper.nav-small #sidebar-nav .dropdown-toggle', function (e) {
			
			if ($( document ).width() >= 992) {
				var $item = $(this).parent();
	
				if ($('body').hasClass('fixed-leftmenu')) {
					var topPosition = $item.position().top;
	
					if ((topPosition + 4*$(this).outerHeight()) >= $(window).height()) {
						topPosition -= 6*$(this).outerHeight();
					}
	
					$('#nav-col-submenu').html($item.children('.submenu').clone());
					$('#nav-col-submenu > .submenu').css({'top' : topPosition});
				}
	
				$item.addClass('open');
				$item.children('.submenu').slideDown('fast');
			}
		});
		
		$('#mbody').on('mouseleave', '#page-wrapper.nav-small #sidebar-nav > .nav-pills > li', function (e) {
			if ($( document ).width() >= 992) {
				var $item = $(this);
		
				if ($item.hasClass('open')) {
					$item.find('.open .submenu').slideUp('fast');
					$item.find('.open').removeClass('open');
					$item.children('.submenu').slideUp('fast');
				}
				
				$item.removeClass('open');
			}
		});
		$('#mbody').on('mouseenter', '#page-wrapper.nav-small #sidebar-nav a:not(.dropdown-toggle)', function (e) {
			if ($('body').hasClass('fixed-leftmenu')) {
				$('#nav-col-submenu').html('');
			}
		});
		$('#mbody').on('mouseleave', '#page-wrapper.nav-small #nav-col', function (e) {
			if ($('body').hasClass('fixed-leftmenu')) {
				$('#nav-col-submenu').html('');
			}
		});
	
		$('#make-small-nav').click(function (e) {
			$('#page-wrapper').toggleClass('nav-small');
		});
		
		$('.mobile-search').click(function(e) {
			e.preventDefault();
			
			$('.mobile-search').addClass('active');
			$('.mobile-search form input.form-control').focus();
		});
		$(document).mouseup(function (e) {
			var container = $('.mobile-search');
	
			if (!container.is(e.target) // if the target of the click isn't the container...
				&& container.has(e.target).length === 0) // ... nor a descendant of the container
			{
				container.removeClass('active');
			}
		});
		
		$('.fixed-leftmenu #col-left').nanoScroller({
	    	alwaysVisible: false,
	    	iOSNativeScrolling: false,
	    	preventPageScrolling: true,
	    	contentClass: 'col-left-nano-content'
	    });
		
		// build all tooltips from data-attributes
		$("[data-toggle='tooltip']").each(function (index, el) {
			$(el).tooltip({
				placement: $(this).data("placement") || 'top'
			});
		});
	});
	
	$.fn.removeClassPrefix = function(prefix) {
	    this.each(function(i, el) {
	        var classes = el.className.split(" ").filter(function(c) {
	            return c.lastIndexOf(prefix, 0) !== 0;
	        });
	        el.className = classes.join(" ");
	    });
	    return this;
	};
	$scope.go = function(path,hash,key){
		$scope.menubar=key;
		store.storage.set('menukey',key);
		urlCtrl.go(path, hash);
	};
	$scope.menubar=store.storage.get('menukey');
	if(window.sessionStorage['menu_target']){
		var absUrl = true;
		if($location.absUrl() != window.sessionStorage['absUrl']){
			absUrl=false;
		}
		
		$rootScope.menuFocus(window.sessionStorage['menu_target'],'',absUrl);
	}
	/*
		excel
	*/
	$scope.exportToExcel=function(tableId){ // ex: '#my-table'
		var exportHref=Excel.tableToExcel(tableId,'sheet name');
		$timeout(function(){location.href=exportHref;},100); // trigger download
	};
	$scope.exportPdf = function(oid){
		window.open(url+"?callback=JSON_CALLBACK&task=exportPdf&id="+oid);
		
	};

	/*
		位置導覽
	*/
	$scope.map_title = function(value,level){
		if(window.sessionStorage[uid+'_'+'map_links']){
			var titles = JSON.parse(window.sessionStorage[uid+'_'+'map_links']);
		}else{
			var titles = [];
		}
		
		if(level!=-1){
			if(level==0 && window.sessionStorage[uid+'_'+'level']==0){
				var titles = [];
			}
			if(value){
				for(var i=0;i<value.length;i++){	
					var mapcnt=0;
					for(var j=0;j<titles.length;j++){
						if(titles[j].title==value[i].title && i==j){
							mapcnt++;
						}
					}
					if(mapcnt==0){
						titles.push(value[i]);
					}
				}
			}
		}else{
			titles.pop();
		}
		
		window.sessionStorage[uid+'_'+'map_links'] = JSON.stringify(titles);
		$scope.map_links = titles;
		
	}
	
	$scope.langChg = function(langKey) {
		$translate.use(langKey);
    };
	/*
		解碼
	*/	
	$rootScope.array_decode = function(tmp_arr){
		try{
			if(angular.isObject(tmp_arr)){
				return angular.forEach(tmp_arr, function(value, key) {
					if(key=="list"){
						return $scope.array_decode(value);
					}else{
						if(angular.isObject(value)){
							return $scope.array_decode(value);
						}else{
							try{
								
								tmp_arr[key] = decodeURI(decodeURIComponent(value).replace(/\+/g,' '));
								if(!isNaN(tmp_arr[key])){
									tmp_arr[key]=parseInt(tmp_arr[key]);
								}
							}catch(e){
								try{
									tmp_arr[key] = decodeURI(value);
									if(!isNaN(tmp_arr[key])){
										tmp_arr[key]=parseInt(tmp_arr[key]);
									}
								}catch(e){
									tmp_arr[key] = value;
									if(!isNaN(tmp_arr[key])){
										tmp_arr[key]=parseInt(tmp_arr[key]);
									}
								}
							}
							return tmp_arr;
						}
					}
				});
			}else{
				try{
					tmp_arr=decodeURIComponent(tmp_arr).replace(/\+/g,' ')
					if(!isNaN(tmp_arr)){
						tmp_arr=parseInt(tmp_arr);
					}
					return tmp_arr;
				}catch(e){
					tmp_arr=decodeURI(tmp_arr).replace(/\+/g,' ')
					if(!isNaN(tmp_arr)){
						tmp_arr=parseInt(tmp_arr);
					}
					return tmp_arr;
				}
			}
		}catch(e){
			if(!isNaN(tmp_arr)){
				tmp_arr=parseInt(tmp_arr);
			}
			return tmp_arr;
		}
	};
	
	

	my.showBigPitcture = function(src) {
		/*var height = window.screen.height * 0.7;
		var div = $("<div></div>").attr("style", "width:100%; height:100%; text-align:center; background-color:#000000;");
			img = $("<img/>").attr("src",src).attr("style", "width:auto; height:" + height + "px; position:relative; vertical-align:middle;").appendTo(div), 
			width = img[0].width / (img[0].height / height) + 200;

		alertify.pictureDialog(div[0].outerHTML).set('basic', true).set('padding',false).set('resizable',true).resizeTo(width, height).set('frameless', true); */
	}
	/*
	$http.get("permission/1.json?v="+new Date().getTime()).success(function (data, status, headers, config) {
		$scope.menulist = data;
	});
	*/
});

/*
	user控制
*/
app.controller('user_ctrl', function($rootScope, $scope, $http, $location, sessionCtrl,urlCtrl) {
	var uid = sessionCtrl.getuid();
	var realurl = "controller/php/common.php"+"?callback=JSON_CALLBACK&task=adminInfo&id="+uid;
	$scope.user = {
		name: sessionCtrl.get(uid + '_uname')
	}
	this.a="123";
	/*$scope.logout = function (){
		clearInterval(sessionalive);
		var realurl = "controller/common.php"+"?callback=JSON_CALLBACK&task=adminLogout&id="+uid;
		$http.jsonp(realurl).success(function (data, status, headers, config) {
			$rootScope.gotoLogin();
		});
		sessionCtrl.removeAll();
	};*/
	
	var url = "controller/php/common.php";
	$http.get(url, {"params": {task: "get_langList"}})
		 .success(function(data) {
		 	$scope.langList = data.langList;
		 })
		 .error(function() {
		 	console.log("error");
		 });
	
	$scope.set_lang=function(lang){
		lang = (lang) ? lang : 'zh-tw';
		sessionCtrl.set('_lang',lang);
		var path=$location.path();
		var search=$location.search();
		search.lang=lang;
		search.setLang=1;
		urlCtrl.go(path,search);
	};
	
});



app.controller('login_ctrl', function($rootScope, $scope, $http, $location, sessionCtrl, $translate,store, templateCtrl,urlCtrl) {
	var url = "controller/php/common.php";
	var secimg_url = "../php/securityimg.php";
	$scope.loginparm = {
		loginid: "",
		passwd: "",
		rememberme: true,
		checkcode: "",
		task: "adminLogin"
	};
	
	$scope.$on('$viewContentLoaded', function() {
		setTimeout(function(){
			$scope.CertifImgChange();
		},1);
	});
	
	$scope.login = function() {
		$http.get(url, {"params": $scope.loginparm})
			 .success(function(data, status, header, config) {
			 	if(data.status == 0) {
			 		error(decodeURI(data.msg));
			 	} else {
			 		sessionCtrl.set('uid', data.uid);
			 		sessionCtrl.set(data.uid + '_uloginid', data.uloginid);
			 		sessionCtrl.set(data.uid + '_ulevel', data.ulevel);
			 		sessionCtrl.set(data.uid + '_uname', data.uname);
			 		sessionCtrl.set(data.uid + '_loginTime', data.loginTime);
			 		
			 		templateCtrl.gotoIndex();
					window.location.reload();
			 	}
			 })
			 .error(function(e){
			 	console.log(e);
			 });
	}
	
	$scope.CertifImgChange = function(){
		var dt = new Date();
		var time = dt.getHours() + dt.getMinutes() + dt.getSeconds();
		$('#CertifImg').attr('src',secimg_url+'?t='+time);
	}
	
	
	$http.get(url, {"params": {task: "get_cookieInfo"}})
		 .success(function(data, status, headers, config) {
		 	if (data.loginInfo != "") {
		 		$scope.loginparm.loginid = data.loginInfo;
		 	}
		 	if(data.passwdInfo != "") {
		 		$scope.loginparm.passwd = data.passwdInfo;
		 	}
			if(data.syslangInfo != "") {
		 		$scope.selectLang = data.syslangInfo;
		 	}
		 })
		 .error(function() {
		 	console.log("error");
		 })
	
	var dt = new Date();
	var time = dt.getHours() + dt.getMinutes() + dt.getSeconds();
	$('#CertifImg').attr('src',secimg_url+'?t='+time);
	$('#CertifImg').show();	
	
	
	$scope.selectLang = sessionCtrl.get('_lang');
	$http.get(url, {"params": {task: "get_langList"}})
		 .success(function(data) {
		 	$scope.langList = data.langList;
			if(!$scope.selectLang && data.langList[0]['code'])
			{
				$scope.selectLang = data.langList[0]['code'];
			}
			$scope.set_lang();
		 })
		 .error(function() {
		 	console.log("error");
		 });
	
	$scope.set_lang=function(){
		lang = ($scope.selectLang) ? $scope.selectLang : 'zh-tw';
		
		$http.get(url, {"params": {task: "set_lang", lang: lang}})
			 .success(function(data) {
				sessionCtrl.set('_lang',lang);
				var path=$location.path();
				var search=$location.search();
				search.lang=lang;
				search.setLang=1;
				urlCtrl.go(path,search);
			 })
			 .error(function() {
				console.log("error");
			 });
	};
	
});


