angular.module('managerApp').factory('sessionCtrl', ['store','$http','$rootScope', function (store, $http, $rootScope) {
 
    return {
        loginCheck: function() {
            var self = this;
            var uid = self.getuid();
            if(!store.get('uid') || !store.get(uid+'_'+'uloginid') || typeof(store.get(uid+'_'+'uloginid')) == 'undefined'){
        		$rootScope.gotoLogin();
        	}else{
        	    var params = {
        	        task: "sessionChk",
        	        ulevel: store.get(uid + '_ulevel'),
        	        uloginid: store.get(uid + '_uloginid'),
        	        lang: store.get('_lang')
        	    }
        	    $http.get("controller/php/common.php", { "params" : params })
        	         .success(function(data, status, headers, config){
        	             if(data.length < 1 || !data.ulevel) {
        					error(msgStyle("逾時，請重新登入"), function(){
        					    $rootScope.$apply(function() {
        					        $rootScope.gotoLogin();
                                });
        					});
        	             } else {
        	                 var d = new Date();
        	                 d.setTime(data.loginTime * 1000);
        	                 d = ("00" + d.getHours()).slice(-2) + ":" + ("00" + d.getMinutes()).slice(-2) + ":" + ("00" + d.getSeconds()).slice(-2);
        	                 $("#last_upd_time").text(d);
        	             }
        	         })
        	         .error(function(){
        	             
        	         });
        	}
        },
        
        localsessionCheck: function() {
            var self = this;
            var uid = self.getuid();
            if(!store.get('uid') || !store.get(uid+'_'+'uloginid') || typeof(store.get(uid+'_'+'uloginid')) == 'undefined'){
        		return false;
        	} else {
        	    return true;
        	}
        },
        
        getuid: function() {
            return store.get('uid');
        },
        
        set: function(key, obj) {
            return store.set(key, obj);
        },
        
        get: function(key) {
            return store.get(key);
        },
        
        remove: function(key) {
            return store.remove(key);
        },
        
        removeAll: function() {
            var self = this;
            angular.forEach(store.inMemoryCache, function(value, key) {
                if(key != "_template" && key != "_lang") {
                    self.remove(key);
                }
        	});
        }
    }
}]);