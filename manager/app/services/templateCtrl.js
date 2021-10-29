angular.module('managerApp').factory('templateCtrl',['$rootScope','sessionCtrl', function ($rootScope,sessionCtrl) {
    return {
        CurrentPage: "",
        templates: {
    		default: {index: 'templates/default/index.html', login: 'templates/default/login.html'},
    		test: {index: 'templates/test/index.html', login: 'templates/test/login.html'}
    	},
        
        templateChg: function(template){
            var self = this;
    		if(sessionCtrl.localsessionCheck()) {
    			self.CurrentPage = self.templates[template].index ;
    		} else {
    			self.CurrentPage = self.templates[template].login ;
    		}
    		sessionCtrl.set("_template", template);
        },
        
        loadTemplate: function() {
            var self = this;
           	if(typeof(sessionCtrl.get("_template")) == 'undefined' || !sessionCtrl.get("_template")) {
        		self.templateChg("default");
        	} else {
        		self.templateChg(sessionCtrl.get("_template"));
        	}
        },
        
        gotoLogin: function(){
            var self = this;
    		var nowTemplate = sessionCtrl.get("_template");
    		self.CurrentPage = self.templates[nowTemplate].login;
        },
        
        gotoIndex: function(){
            var self = this;
    		var nowTemplate = sessionCtrl.get("_template");
    		self.CurrentPage = self.templates[nowTemplate].index;
        }
        
        
    }
}]);