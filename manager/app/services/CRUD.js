angular.module('managerApp').factory('CRUD', ['$http', '$q', '$rootScope','$translate', function ($http, $q, $rootScope, $translate) {
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
    
    function array_decode(tmp_arr){
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
    								if(!isNaN(tmp_arr[key]) && tmp_arr[key]){
    									tmp_arr[key]=parseInt(tmp_arr[key]);
    								}
    							}catch(e){
    								try{
    									tmp_arr[key] = decodeURI(value);
    									if(!isNaN(tmp_arr[key]) && tmp_arr[key]){
    										tmp_arr[key]=parseInt(tmp_arr[key]);
    									}
    								}catch(e){
    									tmp_arr[key] = value;
    									if(!isNaN(tmp_arr[key]) && tmp_arr[key]){
    										tmp_arr[key]=parseInt(tmp_arr[key]);
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
    					tmp_arr=decodeURIComponent(tmp_arr).replace(/\+/g,' ')
    					if(!isNaN(tmp_arr) && tmp_arr){
    						tmp_arr=parseInt(tmp_arr);
    					}
    				}catch(e){
    					tmp_arr=decodeURI(tmp_arr).replace(/\+/g,' ')
    					if(!isNaN(tmp_arr) && tmp_arr){
    						tmp_arr=parseInt(tmp_arr);
    					}
    				}
			    }
			    return tmp_arr;
			}
		}catch(e){
		    if(tmp_arr!==true && tmp_arr!==false){
    			if(!isNaN(tmp_arr) && tmp_arr){
    				tmp_arr=parseInt(tmp_arr);
    			}
		    }
			return tmp_arr;
		}
	};
    return {
        url: "",
        status: 0,
        
        add: function(params, method) {
            var param = Object.assign({}, params);
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
            var param = Object.assign({}, params);
            var request = null;
            var self = this;
            var deferred = $q.defer();
            if(!param.task){
                 param.task = "update";
            }
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
                    self.status = status;
                    if(data.status==1){
                        deferred.resolve(array_decode(data));
                    }else{
                        error(array_decode(data.msg));
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
        
        del: function(params, method) {
            var param = Object.assign({}, params);
            param.task = "del";
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
                    self.status = status;
                    if(data.status != '1'){
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
        
        list: function(params, method) {
            var param = Object.assign({}, params);
            if(!param.task){
                 param.task = "list";
            }
            var self = this;
            var deferred = $q.defer();
            var request = getRequest(self.url, param, method);
            $rootScope.promise = request;
            request
            .success(function(data, status, headers, config) {
                self.status = status;
                var pagectrl = pageCtrl;
                if(data.status == 1) {
                    pagectrl.createPageCtrl(data.cnt, param.page);
                }
                deferred.resolve({data:array_decode(data), pageCtrl:pagectrl});
            })
            .error(function(data, status, headers, config) {
                self.status = status;
                error(msgStyle($translate.instant('lg_main.net_error')));
                deferred.reject(status);
            });
            
            return deferred.promise;
        },
        
        detail: function(params, method) {
            var param = Object.assign({}, params);
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
            var param = Object.assign({}, params);
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
}]);