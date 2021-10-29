angular.module('managerApp')

.factory('urlCtrl',['$rootScope','$location', function ($rootScope,$location) {
    var key = "bibibobo";
    return {
        enaes: function(word){
            if(word=='' || typeof word=='undefined')word={};
            
        	if(word){
        		if(angular.isObject(word)){
        			word = angular.toJson(word);
        		}
        		//encrypt
        		var rawStr = CryptoJS.AES.encrypt(word, key).toString();
        		var wordArray = CryptoJS.enc.Utf8.parse(rawStr);
        		return CryptoJS.enc.Base64.stringify(wordArray);
        	}
        	
        	return word;
        },
        
        deaes: function(word){
           	//decrypt
            try{	
            	var parsedWordArray = CryptoJS.enc.Base64.parse(word);
            	var parsedStr = parsedWordArray.toString(CryptoJS.enc.Utf8);
            	var result = CryptoJS.AES.decrypt(parsedStr, key).toString(CryptoJS.enc.Utf8);
                result = angular.fromJson(result);
            }catch(e){
                result = result === "" ? {} : false;
            	this.go("index_page",{}); //解密失敗導回第一頁
            }
        	return result;
        },
        
        go: function(path, hash){
            var self = this;
            $('#sidebar-nav').removeClass('in');
            if(path == "-1") {
    			history.back();
    		} else {
    			$location.url(path + "#" + self.enaes(hash));
    		}
        }
    }
}])

.factory('sessionCtrl', ['store','$http','$rootScope', function (store, $http, $rootScope) {
 
    return {
        loginCheck: function() {
            var self = this;
            var uid = self.getuid();
            if(!store.get('uid') || !store.get(uid+'_'+'uloginid') || typeof(store.get(uid+'_'+'uloginid')) == 'undefined'){
        		return false;
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
        					        return false;
                                });
        					});
        	             } else {
        	                 var d = new Date();
        	                 d.setTime(data.loginTime * 1000);
        	                 d = d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
        	                 $("#last_upd_time").text(d);
        	             }
        	         })
        	         .error(function(){
        	             
        	         });
        	}
        	return true;
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
}])

.factory('Excel',['$window', function($window){
	var uri = 'data:application/vnd.ms-excel;base64,',
		template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><meta charset="UTF-8"><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
		base64 = function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
		format = function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
	return {
		tableToExcel: function(html, worksheetName){
			var ctx = {
				    worksheet:worksheetName,
				    table:html
				},
				href = uri + base64(format(template,ctx));
			return href;
		}
	};
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
                request = $http.post(url, parameter);
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
				return angular.forEach(tmp_arr, function(value, key) {
					if(key=="list"){
						return array_decode(value);
					}else{
						if(angular.isObject(value)){
							return array_decode(value);
						}else{
						    if(value!==true && value!==false){
    							try{
    								tmp_arr[key] = decodeURI(decodeURIComponent(value).replace(/\+/g,' '));
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
			}else{
			    if(tmp_arr!==true && tmp_arr!==false){
    				try{
    					tmp_arr=decodeURIComponent(tmp_arr).replace(/\+/g,' ');
    					if(!isNaN(tmp_arr) && tmp_arr && tmp_arr[0]!=0 && numfilter){
    						tmp_arr=parseFloat(tmp_arr);
    					}
    				}catch(e){
    					tmp_arr=decodeURI(tmp_arr).replace(/\+/g,' ');
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
		    if(typeof encodeValue=='undefined')encodeValue='';
		    if(encodeValue=='undefined')encodeValue='';
		    if(encodeValue=='null')encodeValue='';
	        return encodeValue;
	    }
	}
    return {
        url: "",
        status: 0,
        
        add: function(params, method) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            param.task = "add";
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            $rootScope.promise = request;
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
        
        update: function(params, method) {
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
                    $rootScope.promise = request;
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
                $rootScope.promise = request;
                request
                .success(function(data, status, headers, config) {
                    if(data == '請輸入正確網址!')
					{
						error(msgStyle($translate.instant('lg_main.net_error2')));
						deferred.reject(status);
					}
					else
					{
						self.status = status;
						if(data.status==1){
							deferred.resolve(array_decode(data));
						}else{
							error(array_decode(data.msg));
						}
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
        
        del: function(params, method, ignoreMsg) {
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
                $rootScope.promise = request;
                
			    request
                .success(function(data, status, headers, config) {
                    if (!ignoreMsg) {
                        self.status = status;
                        if(data.status != '1'){
                            success(msgStyle(decodeURI(data.msg)));
                        }
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
        
        list: function(params, method, resolveOrigin, url) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "list";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest((url ? url : self.url), param, method);
            $rootScope.promise = request;
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                if (resolveOrigin) {
                    deferred.resolve(array_decode(data));
                } else {
                    deferred.resolve({data:array_decode(data), cnt:data.cnt});
                }
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        },
        
        detail: function(params, method) {
            //var param = Object.assign({}, params);
            var param = clone({}, params);
            if(!param.task){
                 param.task = "detail";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            $rootScope.promise = request;
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
        },
        
        getDBPagePath: function(tablename, id) {
            var param = {
                tablename: tablename,
                id: id,
                task: "getDBPage_path"
            }
            
            var self =this;
            var deferred = $q.defer();
            var request = getRequest("controller/php/common.php", param, "GET");
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

.factory('templateCtrl',['$rootScope','sessionCtrl','urlCtrl', function ($rootScope,sessionCtrl,urlCtrl) {
    return {
        CurrentPage: "",
        templates: {
    		default: {index: 'templates/default/index.html', login: 'templates/default/login.html'}
    	},
        
        templateChg: function(template){
            var self = this;
    		if(sessionCtrl.localsessionCheck()) {
    			self.CurrentPage = self.templates[template].index ;
    		} else {
    			self.CurrentPage = self.templates[template].login ;
    		}
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
            urlCtrl.go('index_page',{});
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
}])
.factory('modalService', ['$uibModal', function ($uibModal) {
    return {
        openModal: function (name, data, setting) {
            var my = this;
            setting = setting || {};
            return $uibModal.open({
    			animation: setting.animation || true,
    			templateUrl: 'modals/' + name + "/template.html",
    			controller: name + '_modal',
    			backdrop: setting.backdrop || true,
    			controllerAs: 'ctrl',
    			size: setting.size || '',
    			resolve: {
    				items: function () {
    					return data;
    				}
    			}
    	    });
        }
    }
}]);