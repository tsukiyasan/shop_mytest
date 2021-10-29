angular.module('goodarch2uApp')
.factory('urlCtrl',['$rootScope','$location', function ($rootScope,$location) {
    return {
        go: function(path){
    		$location.url(path);
    		
        }
    }
}])
.factory('sessionCtrl', ['store','CRUD','$rootScope', function (store, CRUD, $rootScope) {
 
    return {
        loginCheck: function() {
            var self = this;
            var uid = self.getuid();
            
    	    var params = {
    	        task: "sessionChk",
    	        ulevel: store.get(uid + '_ulevel'),
    	        uloginid: store.get(uid + '_uloginid'),
    	        lang: store.get('_lang')
    	    }
    	    
    	    var ourl=CRUD.getUrl();
    	    CRUD.setUrl("app/controllers/eways.php");
    	    return CRUD.detail(params, "POST").then(function(res){
				console.log(res);
				CRUD.setUrl(ourl);
				return res;
			});
		
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
            
			if(key == '_lang' && !(store.get(key)))
			{
				var ourl=CRUD.getUrl();
				CRUD.setUrl("app/controllers/eways.php");
				return CRUD.detail({task: "getlang"}, "POST").then(function(res){
					store.set(key, res.syslang.toString());
					return store.get(key);
				});
			}
			else if(key == '_currency')
			{
				store.set(key, sysCurrency);
				return store.get(key);
			}
			else
			{
				return store.get(key);
			}
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
}])

.factory('CRUD', ['$http', '$q', '$rootScope','$translate', function ($http, $q, $rootScope, $translate) {
    $rootScope.promise=null;
    function getRequest(url, params, method) {
        var request;
        switch (method) {
            case 'GET':
                request = $http.get(url, { "params" : params });
                break;
            case 'POST':
                var parameter = $.param(params);
                request = $http.post(url, parameter,{headers: {'Content-Type': 'application/x-www-form-urlencoded'}});
                break;
            case 'JSONP':
            default:
                url += "?callback=JSON_CALLBACK";
                request = $http.jsonp(url, { "params" : params });
                break;
        }
        return request;
    }
    
    function array_encode(tmp_arr){
        try{
			if(angular.isObject(tmp_arr)){
				angular.forEach(tmp_arr, function(value, key) {
					if(key=="list"){
						tmp_arr[key] = array_encode(value);
					}else{
						if(angular.isObject(value)){
							tmp_arr[key] = array_encode(value);
						}else{
						    if(value){
    							try{
    								tmp_arr[key] = encodeURIComponent(value);
    							}catch(e){
    								try{
    									tmp_arr[key] = encodeURI(value);
    								}catch(e){
    									tmp_arr[key] = value;
    								}
    							}
						    }else{
						        tmp_arr='';
						    }
							return tmp_arr;
						}
					}
				});
				return tmp_arr;
			}else{
			    if(tmp_arr){
    				try{
    					tmp_arr=encodeURIComponent(tmp_arr);
    				}catch(e){
    					tmp_arr=encodeURI(tmp_arr);
    				}
			    }else{
			        tmp_arr='';
			    }
			    return tmp_arr;
			}
		}catch(e){
			return tmp_arr;
		}
    }
    
    function array_decode(tmp_arr){
        var numfilter=false;
		try{
			if(angular.isObject(tmp_arr)){
				angular.forEach(tmp_arr, function(value, key) {
					if(key=="list"){
						tmp_arr[key]=array_decode(value);
					}else{
						if(angular.isObject(value)){
							tmp_arr[key]=array_decode(value);
						}else{
						    if(value!==true && value!==false){
    							try{
    							    if(value.indexOf("base64")>-1){
    							        tmp_arr[key] = decodeURI(decodeURIComponent(value));
    							    }else{
    							    	tmp_arr[key] = decodeURI(decodeURIComponent(value).replace(/\+/g,' '));
    							    }
    								if(!isNaN(tmp_arr[key]) && tmp_arr[key] && tmp_arr[key][0]!=0 && numfilter){
    									tmp_arr[key]=parseFloat(tmp_arr[key]);
    								}
    							}catch(e){
    								try{
    									tmp_arr[key] = decodeURI(value);
    									if(!isNaN(tmp_arr[key]) && tmp_arr[key] && tmp_arr[key][0]!=0 && numfilter){
    										tmp_arr[key]=parseFloat(tmp_arr[key]);
    									}
    								}catch(e){
    									tmp_arr[key] = value;
    									if(!isNaN(tmp_arr[key]) && tmp_arr[key] && tmp_arr[key][0]!=0 && numfilter){
    										tmp_arr[key]=parseFloat(tmp_arr[key]);
    									}
    								}
    							}
						    }
							return tmp_arr;
						}
					}
				});
				return tmp_arr;
			}else{
			    if(tmp_arr!==true && tmp_arr!==false){
    				try{
    				    if(value.indexOf("base64")>-1){
    				        tmp_arr=decodeURIComponent(tmp_arr);
    				    }else{
    					    tmp_arr=decodeURIComponent(tmp_arr).replace(/\+/g,' ');
    				    }
    					if(!isNaN(tmp_arr) && tmp_arr && tmp_arr[0]!=0 && numfilter){
    						tmp_arr=parseFloat(tmp_arr);
    					}
    				}catch(e){
    				    if(value.indexOf("base64")>-1){
    				        tmp_arr=decodeURI(tmp_arr);
    				    }else{
    				    	tmp_arr=decodeURI(tmp_arr).replace(/\+/g,' ');
    				    }
    					if(!isNaN(tmp_arr) && tmp_arr && tmp_arr[0]!=0 && numfilter){
    						tmp_arr=parseFloat(tmp_arr);
    					}
    				}
			    }
			    return tmp_arr;
			}
		}catch(e){
		    if(tmp_arr!==true && tmp_arr!==false){
    			if(!isNaN(tmp_arr) && tmp_arr && tmp_arr[0]!=0 && numfilter){
    				tmp_arr=parseFloat(tmp_arr);
    			}
		    }
			return tmp_arr;
		}
	};
	
	function clone(target, source) {
	    if(angular.isObject(source) || angular.isArray(source)){
	        angular.forEach(source, function(value, key) {
                target[key] = clone({}, value);
	        });
	        return target;
	    } else {
	        var encodeValue;
            try{
				encodeValue = encodeURIComponent(source);
			}catch(e){
				try{
					encodeValue = encodeURI(source);
				}catch(e){
					encodeValue = source;
				}
			}
	        return encodeValue;
	    }
	}
    return {
        url: "",
        status: 0,
        
        add: function(params, method,ajaxload) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            param.task = "add";
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            if(ajaxload){
                $rootScope.promise = request;
            }
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                deferred.resolve(array_decode(data));
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
            
        },
        
        update: function(params, method,ajaxload) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            var request = null;
            var self = this;
            var deferred = $q.defer();
            if(!param.task){
                 param.task = "update";
            }
            //param=array_encode(param);
            
            if(param.task == "publishChg"){
                alertify.confirm(msgStyle($translate.instant('lg_main.publish_change')))
    			.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
    			.set({ labels : { ok: $translate.instant('lg_main.yes') ,cancel:$translate.instant('lg_main.no')} })
    			.set('onok', function(closeEvent){ 
    			    request = getRequest(self.url, param, method);
                    if(ajaxload){
                        $rootScope.promise = request;
                    }
                    request
                    .success(function(data, status, headers, config) {
                        self.status = status;
                        deferred.resolve(array_decode(data));
                    })
                    .error(function(data, status, headers, config) {
                        self.status = status;
                        error(msgStyle($translate.instant('lg_main.net_error')));
                        deferred.reject(status);
                    });
    			});
            }else{
                request = getRequest(self.url, param, method);
            }
            
            
            if(request){
                if(ajaxload){
                    $rootScope.promise = request;
                }
                request
                .success(function(data, status, headers, config) {
                    self.status = status;
                    if(data.status!=0){
                        deferred.resolve(array_decode(data));
                    }else{
                        if(data.msg){
                            error(array_decode(data.msg));
                        }
                        
                        deferred.resolve(array_decode(data));
                    }
                })
                .error(function(data, status, headers, config) {
                    self.status = status;
                    error(msgStyle($translate.instant('lg_main.net_error')));
                    deferred.reject(status);
                });
            }
            return deferred.promise;
        },
        
        del: function(params, method,ajaxload) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "del";
            }
            var self = this;
            var deferred = $q.defer();
            alertify.confirm(msgStyle($translate.instant('lg_main.delChk')))
			.setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_main.batch_opt_msg'))
			.set({ labels : { ok: $translate.instant('lg_main.cfm') ,cancel:$translate.instant('lg_main.cancel')} })
			.set('onok', function(closeEvent){ 
			    
                var request = getRequest(self.url, param, method);
                if(ajaxload){
                    $rootScope.promise = request;
                }
                
			    request
                .success(function(data, status, headers, config) {
                    self.status = status;
                    if(data.status == 0){
						success(msgStyle(decodeURI(data.msg)));
					}
                    deferred.resolve(data);
                })
                .error(function(data, status, headers, config) {
                    self.status = status;
                    error(msgStyle($translate.instant('lg_main.net_error')));
                    deferred.reject(status);
                });
			});
			
			return deferred.promise;
        },
        
        list: function(params, method,ajaxload) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "list";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            if(ajaxload){
                $rootScope.promise = request;
            }
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                if(data.status !=0) {
                }
                deferred.resolve(array_decode(data));
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        },
        
        detail: function(params, method,ajaxload) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "detail";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            if(ajaxload){
                $rootScope.promise = request;
            }
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                deferred.resolve(array_decode(data));
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        },
        
        setUrl: function(url) {
            this.url = url;
        },
        getUrl: function() {
            return this.url;
        },
        getDBPageRootList: function(){
            var self = this;
            var deferred = $q.defer();
            var request = getRequest("permission/1.json", {}, "GET");
            $rootScope.pomise = request;
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                deferred.resolve(array_decode(data));
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        },
        
        getDBPageLeafList: function(params) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "getDBPage_list";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest("controller/php/common.php", param, "GET");
            $rootScope.pomise = request;
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                deferred.resolve(array_decode(data));
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        }
    }
}])

.factory('templateCtrl',['$rootScope','sessionCtrl', function ($rootScope,sessionCtrl) {
    return {
        CurrentPage: "",
        templates: {
    		default: {index: 'templates/default/index.html?v=081102', login: 'templates/default/login.html'}
    	},
        
        templateChg: function(template){
            var self = this;
    		self.CurrentPage = self.templates[template].index ;
    		sessionCtrl.set("_template", template);
            $rootScope.$broadcast("event:templatechg");
        },
        
        loadTemplate: function() {
            var self = this;
           	if(typeof(sessionCtrl.get("_template")) == 'undefined' || !sessionCtrl.get("_template") || typeof(self.templates[sessionCtrl.get("_template")]) == 'undefined') {
        		self.templateChg("default");
        	} else {
        		self.templateChg(sessionCtrl.get("_template"));
        	}
        	self.gotoIndex();
        },
        
        gotoLogin: function(){
            var self = this;
    		var nowTemplate = sessionCtrl.get("_template");
    		self.CurrentPage = self.templates[nowTemplate].login;
            $rootScope.$broadcast("event:templatechg");
        },
        
        gotoIndex: function(){
            var self = this;
    		var nowTemplate = sessionCtrl.get("_template");
    		self.CurrentPage = self.templates[nowTemplate].index;
            $rootScope.$broadcast("event:templatechg");
        }
        
        
    }
}])
.factory('pubcode',['$rootScope','$location','CRUD', function ($rootScope,$location,CRUD) {
    
    return {
        get: function(word){
            var self=this;
            if(!$rootScope.pubcodeArr)$rootScope.pubcodeArr=[];
            if($rootScope.pubcodeArr.length==0){
                CRUD.setUrl("controller/php/common.php");
            	CRUD.detail({task:'getpubcode'}, "GET").then(function(res){
    				    $rootScope.pubcodeArr=res;
    			});
                
            }
        }
    }
}]).factory('fbLogin',['$q','$window','CRUD','store','$location','$route', function($q,$window,CRUD,store,$location,$route) {
    var my=this;
    $window.fbAsyncInit = function() {
		    FB.init({ 
		      appId: '1570172986614698',
		      status: true, 
		      cookie: true, 
		      xfbml: true,
		      version: 'v2.6'
		    });
		};
	 (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/zh_TW/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));	
	function fblogin(r,path){
	    FB.api('/'+r.userID, function(response) {
	        CRUD.setUrl("components/member/api.php");
	        CRUD.detail({task:'fb_chk',fbid:r.userID,token:r.accessToken}, "POST").then(function(res) {
    		    if(res.status == 1) {
    				CRUD.update({task:'fb_login',fbid:r.userID,name:response.name,res:res}, "POST").then(function(res) {
            		    if(res.status == 1 || res.status == 2) {
            				//location.reload();
            				if(path){
            				    success("登入成功");
            				    if($location.path()=="/"+path){
            				        location.reload();
            				    }else{
            				        if(res.redirect_url){
            							$location.path(res.redirect_url);
            						}else{
            							$location.path(path);
            						}
            				    }
            				}else{
            				    $location.path("member_page/info");
            				}
            			}else{
            			    error(res.msg);
            			}
            		});
    			}else{
    			    error(res.msg);
    			}
    		});
            
        });
	}	
    return {
        getUserId: function(path) {
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    fblogin(response.authResponse,path);
                }
                else {
                    FB.login(function(response){
                        fblogin(response.authResponse,path);
                        
                    });
                }
            });
        }
    }
}]).factory('gpLogin',['$q','$window','CRUD','GooglePlus','$location', function($q,$window,CRUD,GooglePlus,$location) {
    var my=this;
    
    return {
        getUserId: function(path) {
            GooglePlus.login().then(function (authResult) {
	            GooglePlus.getUser().then(function (user) {
	            	CRUD.detail({task:'gp_chk',gpid:user.id}, "POST").then(function(res) {
						if(res.status == 1) {
							CRUD.detail({task:'gp_login',gpid:user.id,email:user.email,name:user.name,res:res}, "POST").then(function(res) {
								if(res.status == 1 || res.status == 2) {
									if(path){
                    				    success("登入成功");
                    				    if($location.path()=="/"+path){
                    				        location.reload();
                    				    }else{
                    				        if(res.redirect_url){
                    							$location.path(res.redirect_url);
                    						}else{
                    							$location.path(path);
                    						}
                    				    }
                    				}else{
                    				    success(res.msg);
                    				    $location.path("member_page/info");
                    				}
								}
							});
						}
					});
	            });
	        }, function (err) {
	              console.log(err);
	        });
        }
    }
}]);