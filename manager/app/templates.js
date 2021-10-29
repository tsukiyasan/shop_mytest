app.controller('template_ctrl', ['$rootScope', '$route', 'sessionCtrl', function($rootScope, $route, sessionCtrl) {
 	$rootScope.template = {};
	/*
	    模板陣列
	*/
	$rootScope.templates = {
		default: {index: 'templates/default/index.html', login: 'templates/default/login.html'}
	};
	/*
		替換view檔路徑
	*/
	$rootScope.temp_chg = function(v){
		if(sessionCtrl.localsessionCheck()) {
			$rootScope.template.url = $rootScope.templates[v].index ;
		} else {
			$rootScope.template.url = $rootScope.templates[v].login ;
		}
		sessionCtrl.set("_template", v);
	}
	
	$rootScope.gotoLogin = function() {
		var nowTemplate = sessionCtrl.get("_template");
		$rootScope.template.url = $rootScope.templates[nowTemplate].login;
	}
	
	$rootScope.gotoIndex = function() {
		var nowTemplate = sessionCtrl.get("_template");
		$rootScope.template.url = $rootScope.templates[nowTemplate].index;
	}
	
	if(typeof(sessionCtrl.get("_template")) == 'undefined' || !sessionCtrl.get("_template")) {
		$rootScope.temp_chg("default");
	} else {
		$rootScope.temp_chg(sessionCtrl.get("_template"));
	}
}]);