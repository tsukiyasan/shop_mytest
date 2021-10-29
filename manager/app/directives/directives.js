/**
 * Cube - Bootstrap Admin Theme
 * Copyright 2014 Phoonio
 */


function bsNavbar($window, $location) {

	var defaults = this.defaults = {
		activeClass: 'active',
		routeAttr: 'data-match-route',
		strict: true
	};

	return {
		restrict: 'A',
		link: function postLink(scope, element, attr, controller) {

			// Directive options
			var options = angular.copy(defaults);
			angular.forEach(Object.keys(defaults), function(key) {
				if(angular.isDefined(attr[key])) options[key] = attr[key];
			});

			// Watch for the $location
			scope.$watch(function() {

				return $location.path();

			}, function(newValue, oldValue) {

				var liElements = element[0].querySelectorAll('li[' + options.routeAttr + '],li > a[' + options.routeAttr + ']');

				angular.forEach(liElements, function(li) {

					var liElement = angular.element(li);
					var pattern = liElement.attr(options.routeAttr).replace('/', '\\/');
					if(options.strict) {
						pattern = '^' + pattern;
					}
					var regexp = new RegExp(pattern, ['i']);

					if(regexp.test(newValue)) {
						liElement.addClass(options.activeClass);
					} else {
						liElement.removeClass(options.activeClass);
					}

				});

				// Close all other opened elements
				var op = $('#sidebar-nav').find('.open:not(.active)');
				op.children('.submenu').slideUp('fast');
				op.removeClass('open');
			});

		}

	};
}

function gd(year, day, month) {
	return new Date(year, month - 1, day).getTime();
}

function showTooltip(x, y, label, data) {
	$('<div id="flot-tooltip">' + '<b>' + label + ': </b><i>' + data + '</i>' + '</div>').css({
		top: y + 5,
		left: x + 20
	}).appendTo("body").fadeIn(200);
}


function showtab() {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
                $(element).tab('show');
            });
        }
    };
}


angular
	.module('managerApp')
	.directive('bsNavbar', bsNavbar)
	.directive('showtab', showtab)
	.directive('myEnter', function () {
	    return function (scope, element, attrs) {
	        element.bind("keydown keypress", function (event) {
	            if(event.which === 13) {
	                scope.$apply(function (){
	                    scope.$eval(attrs.myEnter);
	                });
	
	                event.preventDefault();
	            }
	        });
	    };
	});
//drag drop	
angular.module('managerApp').directive('dndList', function(CRUD) {
 
    return function(scope, element, attrs) {
 
        // variables used for dnd
        var toUpdate;
        var startIndex = -1;
        // watch the model, so we always know what element
        // is at a specific position
        scope.$watch(attrs.dndList, function(value) {
            toUpdate = value;
        },true);
 
        // use jquery to make the element sortable (dnd). This is called
        // when the element is rendered
        $(element[0]).sortable({
            items:'tr',
            start:function (event, ui) {
                // on start we define where the item is dragged from
                startIndex = ($(ui.item).index());
            },
            stop:function (event, ui) {
            	
                // on stop we determine the new index of the
                // item and store it there
                var newIndex = ($(ui.item).index());
                
                var toMove = toUpdate[startIndex];
               
 				
                toUpdate.splice(startIndex,1);
                toUpdate.splice(newIndex,0,toMove);
                var a=[];
                angular.forEach(toUpdate,function(v,k){
                	a.push(v.id);
                });
 				var params = {
					task:'odrchg',
					id:a
				};
				CRUD.update(params, "POST").then(function(res){
					
					if(res.status == 1) {
						success(res.msg);
					}
				});
                // we move items in the array, if we want
                // to trigger an update in angular use $apply()
                // since we're outside angulars lifecycle
                scope.$apply(scope.model);
            },
            axis:'y'
        })
    }
});	

angular.module('managerApp').directive('dndList2', function(CRUD) {
 
    return function(scope, element, attrs) {
 
        // variables used for dnd
        var toUpdate;
        var startIndex = -1;
        // watch the model, so we always know what element
        // is at a specific position
        scope.$watch(attrs.dndList2, function(value) {
            toUpdate = value;
        },true);
 
        // use jquery to make the element sortable (dnd). This is called
        // when the element is rendered
        $(element[0]).sortable({
            items:'tr',
            start:function (event, ui) {
                // on start we define where the item is dragged from
                startIndex = ($(ui.item).index());
            },
            stop:function (event, ui) {
            	
                // on stop we determine the new index of the
                // item and store it there
                var newIndex = ($(ui.item).index());
                
                var toMove = toUpdate[startIndex];
               
 				
                toUpdate.splice(startIndex,1);
                toUpdate.splice(newIndex,0,toMove);
                var a=[];
                angular.forEach(toUpdate,function(v,k){
                	a.push(v.id);
                });
 				var params = {
					task:'odrchg2',
					id:a
				};
				CRUD.update(params, "POST").then(function(res){
					
					if(res.status == 1) {
						success(res.msg);
					}
				});
                // we move items in the array, if we want
                // to trigger an update in angular use $apply()
                // since we're outside angulars lifecycle
                scope.$apply(scope.model);
            },
            axis:'y'
        })
    }
});	

angular.module('managerApp').directive("pageCtrlEl",function($location,urlCtrl){
    return {
        restrict: "E",
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            params:"=",
            cnt:"=",
            page:"="
        },
        template: '<center>'+
						'<ul class="pagination pull-center">'+
							'<li><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(pageel_ctrl.prepage.p, params)" ><i class="fa fa-chevron-left"></i></a></li>'+
							'<li ng-class="{\'true\': \'active\', \'false\': \'\'}[page.active]" ng-repeat="page in pageel_ctrl.pages"><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(page.p, params)" ng-bind="page.p"></a></li>'+
							'<li><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(pageel_ctrl.postpage.p, params)" ><i class="fa fa-chevron-right"></i></a></li>'+
							'<li>'+
								'<select class="pagesel" ng-options="data.p for data in pageel_ctrl.allpage track by data.p" ng-model="pageel_ctrl.curpage" ng-change="pageel_ctrl.pageChg(pageel_ctrl.curpage.p, params)">'+
								'</select>'+
							'</li>'+
						'</ul>'+
					'</center>',
		replace: true,
		link: function (scope, element, attrs,controller) {
		    var self=controller;
		    var createPageCtrl=function(pageCnt, cur) {
                pageCnt=!pageCnt?1:pageCnt;
                cur=!cur?1:cur;
                cur = cur < 1 ? 1 : cur > pageCnt ? pageCnt : cur;
                cur=parseInt(cur);
                pageCnt=parseInt(pageCnt);
                self.cur = cur;
                self.path = $location.path();
                self.pageCnt = pageCnt;
                self.pages = [];
                self.allpage = [];
                self.curpage = {'p':cur};
                self.prepage = cur - 1 > 0 ? {'p':cur - 1} : {'p':1};
                self.postpage = cur + 1 < pageCnt ? {'p':cur + 1} : {'p':pageCnt};
                var disNum = 2;
                var x = cur - 1;
                var y = pageCnt - cur;
                if(x + y > disNum * 2) {
                    if(x >= disNum && y >= disNum) {
                        x = disNum;
                        y = disNum;
                    } else if (x > disNum && y < disNum) {
                        x = disNum * 2 - y;
                    } else {
                        y = disNum * 2 - x;
                    }
                }
                x = cur - x;
                y = cur + y;
                for (var i = x; i <= y; i++) {
                    if(cur == i) {
                        self.pages.push({'p':i, 'active':true});
                    } else {
                        self.pages.push({'p':i, 'active':false});
                    }
                }
                
                for(var i = 1; i <= pageCnt; i++) {
                    self.allpage.push({'p':i});
                }
            };
            
		    scope.$watch('cnt',function(newval){
		        if(newval){
		            createPageCtrl(newval,scope.page);
		        }
		    });
            
        },
		controllerAs: 'pageel_ctrl',
		controller:function($scope){
		    var self = this;
            self.pageChg=function(cur,param){
                if(self.cur != cur) {
                    if(!param)param={};
                    var params = Object.assign({}, param);
                    params.page = cur;
                    urlCtrl.go(self.path, params);
                }
            };
        
		}
    };
});

angular.module('managerApp').directive("fileField", function() {
    return {
        require: "ngModel",
        restrict: "E",
        link: function(scope, element, attrs, ngModel) {
            if (!attrs.class && !attrs.ngClass) {
                element.addClass("btn")
            }
            
            if(typeof attrs.imgOnly != undefined) {
                element.find("input").attr("accept", "image/jpeg, image/png");
            }
            if(attrs.allFile){
                element.find("input").attr("accept", "");
            }
            var fileField = element.find("input");
            fileField.bind("change", function(event) {
                scope.$evalAsync(function() {
                    if(event.target.files[0]) {
                        ngModel.$setViewValue(event.target.files[0]);
                        if (attrs.preview) {
                            var reader = new FileReader;
                            reader.onload = function(e) {
                                scope.$evalAsync(function() {
                                    
                                    if(attrs.seq2){
                                        if(!scope[attrs.preview][attrs.seq])scope[attrs.preview][attrs.seq]=[];
                                        scope[attrs.preview][attrs.seq][attrs.seq2] = e.target.result;
                                    }else{
                                        if(!scope[attrs.preview])scope[attrs.preview]=[];
                                        scope[attrs.preview][attrs.seq] = e.target.result;
                                    }
                                    
                                })
                            };
                            reader.readAsDataURL(event.target.files[0])
                        }
                    }
                })
            });
            fileField.bind("click", function(e) {
                e.stopPropagation()
            });
            element.bind("click", function(e) {
                e.preventDefault();
                fileField[0].click()
            })
        },
        template: '<button type="button"><ng-transclude></ng-transclude><input type="file" style="display:none"></button>',
        replace: true,
        transclude: true
    }
});


angular.module('managerApp').directive("fileFields", function() {
    return {
        require: "ngModel",
        restrict: "E",
        link: function(scope, element, attrs, ngModel) {
            if (!attrs.class && !attrs.ngClass) {
                element.addClass("btn")
            }
            
            if(typeof attrs.imgOnly != undefined) {
                element.find("input").attr("accept", "image/jpeg, image/png");
            }
            if(attrs.allFile){
                element.find("input").attr("accept", "");
            }
            var fileField = element.find("input");
            fileField.bind("change", function(event) {
                scope.$evalAsync(function() {
                    if(event.target.files[0]) {
                        ngModel.$setViewValue(event.target.files[0]);
                        if (attrs.preview) {
                            var reader = new FileReader;
                            reader.onload = function(e) {
                                scope.$evalAsync(function() {
                                    
                                    if(attrs.seq2){
                                        if(!scope[attrs.preview][attrs.seq])scope[attrs.preview][attrs.seq]=[];
                                        scope[attrs.preview][attrs.seq][attrs.seq2] = e.target.result;
                                    }else{
                                        if(!scope[attrs.preview])scope[attrs.preview]=[];
                                        scope[attrs.preview][attrs.seq] = e.target.result;
                                    }
                                    
                                })
                            };
                            reader.readAsDataURL(event.target.files[0])
                        }
                    }
                })
            });
            fileField.bind("click", function(e) {
                e.stopPropagation()
            });
            element.bind("click", function(e) {
                e.preventDefault();
                fileField[0].click()
            })
        },
        template: '<button type="button"><ng-transclude></ng-transclude><input type="file" style="display:none"></button>',
        replace: true,
        transclude: true
    }
});


angular.module('managerApp').directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
});
angular.module('managerApp').directive("formatSelector",function(CRUD,$location){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="format_selector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel">&nbsp;&nbsp;{{\'lg_proformat.formatselect\' | translate}}</h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                                '<strong class="text-red">{{\'lg_products.formatselect1_msg\' | translate}}</strong>'+
                          		'<select class="form-control" ng-model="format_1" ng-change="format1Chg()" ng-options="item as item.mname for item in format_list track by item.mid">'+
                          		    '<option value="">{{\'lg_proformat.formatselect\' | translate}}</option>'+
                          		'</select>'+
                          '</div>'+
                          '<div class="modal-body modal_body" ng-show="format_1">'+
                          		'<div id="tree-container">'+
									'<fieldset class="demo-fieldset" ng-repeat="type in format_dtl[format_1.mid] track by type.id">'+//根據規格類別顯示該類別下的規格
										'<div layout="row" layout-wrap flex>'+
											'<div class="demo-select-all-checkboxes" flex="100" >'+
												'<md-checkbox ng-model="checked[format_1.mid][type.id]" ng-true-value="\'{{type.name}}\'">'+
												'{{ type.name }}'+
												'</md-checkbox>'+
											'</div>'+
										'</div>'+
										
									'</fieldset>'+
								'</div>'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close">{{\'lg_main.cancel\' | translate}}</a>'+
                               	'<a href="javascript:void(0)" ng-click="setFormat()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close">{{\'lg_main.cfm\' | translate}}</a>'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		controller:function($scope){
		    $scope.checked=[];
		    var tmpid=0;
		    $scope.getFormat = function(){
    			CRUD.detail({task:'getFormat'},'POST').then(function(res){
    				if(res.status==1){
    					$scope.format_list = res.data;
    					$scope.format_dtl = res.dtl;
    					angular.forEach(res.data,function(v,k){
    					    if(!$scope.checked[v.mid]){
    					        $scope.checked[v.mid]=[];
    					    }
    					});
    				}
    　　　　　　});
		    };
		    $scope.getFormat();
		    
　　　　　　$scope.format1Chg=function(){
　　　　　　    if(tmpid!=$scope.format_1.mid){
　　　　　　        
　　　　　　        
　　　　　　        $scope.checked[tmpid]=[];
　　　　　　    }
　　　　　　}
　　　　　　$scope.setFormat=function(){//按確定時才寫入到model
　　　　　　    var tmp=[];
　　　　　　    $scope.useformat1=$scope.format_1;
　　　　　　    
　　　　　　    if(tmpid!=$scope.useformat1.mid){
　　　　　　        $scope.checked[tmpid]=[];
　　　　　　    }
　　　　　　    if($scope.useformat1){
                    angular.forEach($scope.checked[$scope.useformat1.mid],function(v,k){
                        if(v){
                            tmp.push({id:k,name:v});
                        }
                    });
　　　　　　    }else{
　　　　　　        $scope.checked=[];
　　　　　　        $scope.checked2=[];
		            $scope.checked2_instock=[];
　　　　　　    }
　　　　　　    
　　　　　　    tmpid=$scope.useformat1.mid;
　　　　　　    $scope.format1_arr=tmp;
　　　　　　};
　　　　　　
　　　　　　$scope.setProFormat=function(proid){
                CRUD.detail({task:'getProFormat',proid:proid},'POST').then(function(res){
    				if(res.status==1){
    				    if(res.data.format1_type.mid){
    				        $scope.format_1 = res.data.format1_type;
    				        tmpid=$scope.format_1.mid;
    				    }
    					if(res.data.format1){
    					    $scope.checked[$scope.format_1.mid] = res.data.format1;
    					}
						$scope.setFormat();
    				}
    　　　　　　});
　　　　　　};
　　　  }
    };
});
angular.module('managerApp').directive("formatSelector2",function(CRUD,$location){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="format_selector2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel">&nbsp;&nbsp;{{\'lg_proformat.formatselect\' | translate}}</h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                          		'<select class="form-control" ng-model="format_2" ng-change="getformat2()" ng-options="item as item.mname for item in format2_list track by item.mid">'+
                          		    '<option value="">{{\'lg_proformat.formatselect\' | translate}}</option>'+
                          		'</select>'+
                          '</div>'+
                          '<div class="modal-body modal_body" ng-show="format_2">'+
                          		'<div id="tree-container">'+
									'<fieldset ng-show="formatmode==2" class="demo-fieldset" ng-repeat="type in format2_dtl[format_2.mid] track by $index">'+
										'<div layout="row" layout-wrap flex>'+
											'<div class="demo-select-all-checkboxes" flex="100" >'+
												'<md-checkbox  ng-model="checked2[tmpmid][type.id]" ng-true-value="\'{{type.name}}\'">'+
												'{{ type.name }}'+
												'</md-checkbox>'+
												'<span>&emsp;<txt ng-bind="\'lg_main.buy_count\' | translate">可購買數：</txt></span><input  type="text" ng-model="checked2_instock[tmpmid][type.id]">'+
											'</div>'+
										'</div>'+
										
									'</fieldset>'+
									'<fieldset ng-show="formatmode==1" class="demo-fieldset" ng-repeat="type in format2_dtl[format_2.mid] track by $index">'+
										'<div layout="row" layout-wrap flex>'+
											'<div class="demo-select-all-checkboxes" flex="100" >'+
												'<md-checkbox ng-model="checked2[type.id]" ng-true-value="\'{{type.name}}\'">'+
												'{{ type.name }}'+
												'</md-checkbox>'+
												'<span>&emsp;<txt ng-bind="\'lg_main.buy_count\' | translate">可購買數：</txt></span><input type="text" ng-model="checked2_instock[type.id]">'+
											'</div>'+
										'</div>'+
										
									'</fieldset>'+
								'</div>'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close">{{\'lg_main.cancel\' | translate}}</a>'+
                               	'<a href="javascript:void(0)" ng-click="setFormat2()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close">{{\'lg_main.cfm\' | translate}}</a>'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		link:function(scope,el,attr){
		    scope.formatmode=attr.formatmode;
		},
		controller:function($scope){
		    $scope.checked2=[];
		    $scope.checked2_instock=[];
		    $scope.checked2_procode=[];
		    $scope.getFormat2 = function(mid){
		        
		        var params={task:'getFormat'};
		        if($scope.useformat1 && $scope.formatmode==2){
		            params.notshowid=$scope.useformat1.mid;
		        }
		        if($scope.formatmode==2){
    		        $scope.tmpmid=mid;
    		        if(!$scope.checked2[mid]){
    		            $scope.checked2[mid]=[];
    		        }
		        }
		        
    			CRUD.detail(params,'POST').then(function(res){
    				if(res.status==1){
    					$scope.format2_list = res.data;
    					$scope.format2_dtl = res.dtl;
    					
    				}
    　　　　　　});
		    };
		    
		    $scope.getFormat2();
　　　　　　$scope.setFormat2=function(){
　　　　　　    var tmp=[];
　　　　　　    $scope.useformat2=$scope.format_2;
　　　　　　    if($scope.formatmode==2){
                    if($scope.useformat2){
                        $scope.set_checked2_instock=$scope.checked2_instock;
                        angular.forEach($scope.checked2,function(v,k){
        　　　　　　              if(v){
        　　　　　　                  tmp[k]=[];
        　　　　　　                  angular.forEach(v,function(v2,k2){
                                    if(v2){
        　　　　　　                          tmp[k].push({id:k2,name:v2});
                                    }
        　　　　　　            });
                            }
                        });
                    }
　　　　　　    }else if($scope.formatmode==1){
　　　　　　        if($scope.useformat2){
                        $scope.set_checked2_instock=$scope.checked2_instock;
                        tmp=[];
                        angular.forEach($scope.checked2,function(v,k){
        　　　　　　              if(v){
                                tmp.push({id:k,name:v});
                            }
                        });
                    }
　　　　　　    }      
　　　　　　    $scope.format2_arr=tmp;
　　　　　　};
　　　　　　$scope.setProFormat2=function(proid){
                CRUD.detail({task:'getProFormat2',proid:proid},'POST').then(function(res){
    				if(res.status==1){
    				    if(res.data.format2_type.mid){
    					    $scope.format_2 = res.data.format2_type;
    				    }
    				    if(res.data.format2){
    				        $scope.checked2 = res.data.format2;
    				    }
						if(res.data.format2_instock){
						    $scope.checked2_instock = res.data.format2_instock;
						}
						if(res.data.format2_procode){
						    $scope.checked2_procode = res.data.format2_procode;
						}
						$scope.setFormat2();
    				}
    　　　　　　});
　　　　　　};
　　　  }
    };
});


angular.module('managerApp').directive("dbpageSel",function($location,urlCtrl,CRUD){
    return {
        restrict: "E",
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            "data":"="
        },
        template: '<div><script type="text/ng-template" id="SelectPageModal.html">'+
                        '<div class="modal-header">'+
                    		'<button class="md-close close" ng-click="modalctrl.cancel()">&times;</button>'+
                    		'<h4 class="modal-title" ng-bind="\'lg_main.dbpage_select_page\'|translate"></h4>'+
                        '</div>'+
                        '<div class="modal-body">'+
                    		'<div class="form-group form-group-select2" ng-repeat="list in modalctrl.selectlist track by $index">'+
                    			'<select class="form-control" ng-model="modalctrl.selected[$index]" ng-change="modalctrl.select($index)" ng-if="$index == 0"'+
                    				'ng-options="(\'lg_\'+key+\'.\'+key|translate) for (key, option)  in list">'+
                                    '<option value="" disabled selected hidden>-- {{\'lg_main.please_select\'|translate}} --</option>'+
                                '</select>'+
                                
                    			'<select class="form-control" ng-model="modalctrl.selected[$index]" ng-change="modalctrl.select($index)" ng-if="$index > 0"'+
                    				'ng-options="option.name for (key, option) in list">'+
                                    '<option value="" disabled selected hidden>-- {{\'lg_main.please_select\'|translate}} --</option>'+
                                '</select>'+
                    		'</div>'+
                        '</div>'+
                        '<div class="modal-footer">'+
                    		'<a href="javascript:void(0)" ng-click="modalctrl.cancel()" class="btn btn-danger">'+
                    			'<i class="fa fa-close fa-lg"></i>&nbsp;&nbsp;<span ng-bind="\'lg_main.cancel\' | translate"></span>'+
                    		'</a>'+													
                    		'<a href="javascript:void(0)" ng-click="modalctrl.confirm()" class="btn btn-primary">'+
                    			'<i class="fa fa-check fa-lg"></i>&nbsp;&nbsp;<span ng-bind="\'lg_main.save_and_change\'|translate"></span>'+
                    		'</a>'+
                        '</div>'+
                    '</script>'+
                    '<a href="javascript:void(0)" class="md-trigger btn btn-primary" ng-click="select_page()" ng-bind="\'lg_main.select_page\' | translate"></a></div>',
		replace: true,
		controller:function($scope, $uibModal, $translate){
		    var my = this;
		    $scope.select_page = function() {
		        var modalInstance = $uibModal.open({
        			animation: true,
        			templateUrl: 'SelectPageModal.html',
        			controller: pagedbsel_ctrl,
        			controllerAs: "modalctrl",
        			backdrop: 'static',
        			resolve: {
        				items: function () {
        					return $scope.items;
        				}
        			}
        	    });
        	    
        	    modalInstance.result.then(function (selectedItem) {
			
        			$scope.data.tablename = selectedItem.tablename;
        			$scope.data.databasename = selectedItem.databasename;
        			$scope.data.databaseid = selectedItem.databaseid;
        			CRUD.getDBPagePath($scope.data.tablename, $scope.data.databaseid)
        			.then(function(res) {
        				$scope.data.path = $translate.instant($scope.data.databasename) + res.path;
        			});
        		});
		    }
		    
		    var pagedbsel_ctrl = function($rootScope, $scope, $translate, CRUD, urlCtrl, $uibModalInstance, items, $filter) {
		        var my = this;
	
	
            	var selecteddata = null;
            	
            	my.selectlist = [];
            	my.selected = [];
            	my.init = function(){
            	    
            		CRUD.getDBPageRootList().then(function(res) {
            			var tmplist = {};
            			angular.forEach(res, function(v, k) {
            				if(v.dbpage == "true") {
            					v.name = k;
            					tmplist[k] = v;
            				}	
            				if(angular.isObject(v.child)) {
            					angular.forEach(v.child, function(v,k) {
            						if(v.dbpage == "true") {
            							v.name = k;
            							tmplist[k] = v;
            						}	
            					});
            				}
            			});
            			my.selectlist.push(tmplist);
            		});
            	}
            	
            	my.select = function(level) {
            		var param = {};
            		//console.log(my.selected[level]);
            		if(my.selected[level]) {
            			var slength = my.selectlist.length;
            			for(var i = 0; i < (slength - (level + 1)); i++) {
            				my.selectlist.pop();
            			}
            			if(level == 0 || my.selected[level].pagetype == "dir"){
            				//root 或是還有子節點的時後 
            				param.level = level;
            				param.tablename = my.selected[0].tablename;
            				param.param = my.selected[level].param;
            				if(my.selected[level].pagetype == "dir") {
            					param.belongid = my.selected[level].id;
            				}
            				CRUD.getDBPageLeafList(param).then(function(res) {
            					my.selectlist.push(res);
            					var name = 'lg_' + my.selected[0].name + '.' + my.selected[0].name;
            					selecteddata = {
            						tablename: my.selected[0].tablename,
            						databasename: name,
            						databaseid: my.selected[level].id
            					}
            				});
            			} else {
            				//選擇到可連結的頁面
            				var name = 'lg_' + my.selected[0].name + '.' + my.selected[0].name;
            				selecteddata = {
            					tablename: my.selected[0].tablename,
            					databasename: name,
            					databaseid: my.selected[level].id
            				}
            			}
            		}
            	}
            	
            	my.confirm = function() {
            		if(selecteddata) {
            			$uibModalInstance.close(selecteddata);
            		} else {
            			error(msgStyle($filter('translate')('lg_main.select_page_error')));
            		}
            	}
            	
            	my.cancel = function() {
                	$uibModalInstance.dismiss('cancel');
            	}
            	
            	my.init();
		    }
		    
		}
    };
});

angular.module('managerApp').constant('dateRangePickerConfig', {
    clearLabel: '清除',
    locale: {
        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
        separator: " - ",
        format: 'YYYY-MM-DD',
        applyClass: 'btn-green',
        applyLabel: '確定',
        cancelLabel: '取消',
	    customRangeLabel: '自訂範圍',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月',
            '十月', '十一月', '十二月'
        ]
    }
});


angular.module('managerApp').directive('dateRangePicker', ['$compile', '$timeout', '$parse', 'dateRangePickerConfig', function($compile, $timeout, $parse, dateRangePickerConfig) {
    return {
        require: 'ngModel',
        restrict: 'A',
        scope: {
            min: '=',
            max: '=',
            model: '=ngModel',
            opts: '=options',
            clearable: '='
        },
        link: function($scope, element, attrs, modelCtrl) {
            var _mergeOpts, _clear, _setDatePoint, _setStartDate, _setEndDate, _validate, _validateMin, _validateMax, _init, _initBoundaryField;
            var _picker;
            var el, customOpts, opts;
            _mergeOpts = function() {
                var extend, localeExtend;
                localeExtend = angular.extend.apply(angular, Array.prototype.slice.call(arguments).map(function(opt) {
                    return opt != null ? opt.locale : void 0;
                }).filter(function(opt) {
                    return !!opt;
                }));
                
                extend = angular.extend.apply(angular, arguments);
                extend.locale = localeExtend;
                return extend;
            }
            
            el = $(element);
            customOpts = $scope.opts;
            opts = _mergeOpts({}, dateRangePickerConfig, customOpts);
            _picker = null;
            _clear = function () {
                _picker.setStartDate();
                return _picker.setEndDate();
            };
            
            _setDatePoint = function(setter) {
                return function(newValue) {
                    if (_picker && newValue) {
                        return setter(moment(newValue));
                    }  
                };
            }
            
            _setStartDate = _setDatePoint(function(m) {
               if (_picker.endDate < m) {
                   _picker.setEndDate(m);
               } 
               opts.startDate = m;
               return _picker.setStartDate(m);
            });
            
            _setEndDate = _setDatePoint(function(m) {
               if (_picker.startDate > m) {
                   _picker.setStartDate(m);
               }
               opts.endDate = m;
               return _picker.setEndDate(m);
            });
            
            _validate = function(validator) {
                return function(boundary, actual) {
                    if (boundary && actual) {
                        return validator(moment(boundary), moment(actual));
                    } else {
                        return true;
                    }
                }
            }
            
            _validateMin = _validate(function(min, start) {
                return min.isBefore(start) || min.isSame(start, 'day');
            });
            
            _validateMax = _validate(function(max, end) {
               return max.isAfter(end) || max.isSame(end, 'day'); 
            });
            
            
            modelCtrl.$formatters.push(function(objValue) {
                var f;
                f = function(date) {
                    if(!moment.isMoment(date)) {
                        return moment(date).format(opts.locale.format);
                    } else {
                        return date.format(opts.locale.format);
                    }
                }
                if (opts.singleDatePicker) {
                    if(objValue) {
                        return f(objValue);
                    } else {
                        return '';
                    }
                } else if (objValue.startDate) {
                    return [f(objValue.startDate), f(objValue.endDate)].join(opts.locale.separator);
                } else {
                    return '';
                }
            });
            
            modelCtrl.$render = function() {
                if (modelCtrl.$modelValue && modelCtrl.$modelValue.startDate) {
                    _setStartDate(modelCtrl.$modelValue.startDate);
                    _setEndDate(modelCtrl.$modelValue.endDate);
                } if(modelCtrl.$modelValue) {
                    _setStartDate(modelCtrl.$modelValue);
                    _setEndDate(modelCtrl.$modelValue);
                } else {
                    _clear();
                }
                
                return el.val(modelCtrl.$viewValue);
            };
            
            modelCtrl.$parsers.push(function(val) {
                var f, objValue, x;
                f = function(value) {
                    return moment(value, opts.locale.format);
                }
                objValue = opts.singleDatePicker ? null : {startDate: null, endDate: null}
                if (angular.isString(val) && val.length > 0) {
                    if (opts.singleDatePicker) {
                        objValue = f(val);
                    } else {
                        x = val.split(opts.locale.separator).map(f);
                        objValue.startDate = x[0];
                        objValue.endDate = x[1];
                    }
                }
                return objValue;
            });
            
            modelCtrl.$isEmpty = function(val) {
                return !(angular.isString(val) && val.length > 0);
            };
            
            _init = function() {
                var eventType, results;
                el.daterangepicker(angular.extend(opts, {
                    autoUpdateInput: false
                }), function(start, end) {
                    return $scope.$apply(function() {
                        return $scope.model = opts.singleDatePicker ? start.format(opts.locale.format) : {
                            startDate: start.format(opts.locale.format),
                            endDate: end.format(opts.locale.format)
                        };
                    });
                });
                _picker = el.data('daterangepicker');
                results = [];
                el.on('apply.daterangepicker', function(event, picker) {
	        		return $scope.$apply(function() {
                        return $scope.model = opts.singleDatePicker ? picker.startDate.format(opts.locale.format) : {
                            startDate: picker.startDate.format(opts.locale.format),
                            endDate: picker.endDate.format(opts.locale.format)
                        };
                    });
	        	})
                for (eventType in opts.eventHandlers) {
                    results.push(el.on(eventType, function(e, picker) {
                        return $scope.$evalAsync(opts.eventHandlers[eventType](e, picker));
                    }));
                }
                return results;
            };
            _init();
            
            $scope.$watch('model.startDate', function(n) {
                return _setStartDate(n);
            });
            
            $scope.$watch('model.endDate', function(n) {
                return _setEndDate(n);
            });
            
            _initBoundaryField = function(field, validator, modelField, optName) {
                if(attrs[field]) {
                    modelCtrl.$validators[field] = function(value) {
                        return value && validator(opts[optName], value[modelField]);
                    };
                    return $scope.$watch(field, function(date) {
                        opts[optName] = date ? moment(date) : false;
                        return _init();
                    });
                }
            }
            
            _initBoundaryField('min', _validateMin, 'startDate', 'minDate');
            _initBoundaryField('max', _validateMax, 'endDate', 'maxDate');
            
            if (attrs.options) {
                $scope.$watch('opts', function(newOpts) {
                    opts = _mergeOpts(opts, newOpts);
                    return _init();
                }, true);
            }
            
            if(attrs.clearable) {
                $scope.$watch('clearable', function(newClearable) {
                    if(newClearable) {
                        opts = _mergeOpts(opts, {
                            locale: {
                                cancelLabel: opts.clearLabel
                            }
                        });
                    }
                    _init();
                    if(newClearable) {
                        return el.on('cancel.daterangepicker', function() {
                           return $scope.$apply(function() {
                               return $scope.model = opts.singleDatePicker ? null : {
                                   startDate: null,
                                   endDate: null
                               }
                           }) 
                        });
                    }
                })
            }
            
            return $scope.$on('$destory', function() {
               return _picker != null ? _picker.remove() : void 0; 
            });
        }
    };
}]);
//數字驗證
angular.module('managerApp').directive('validNumber', function() {
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
angular.module('managerApp').directive('el2Editor', function($document) {
    function set_editor(id,data){
        if(CKEDITOR) {
            CKEDITOR.replace(id, {
                'extraPlugins': 'showblocks,div,doksoft_backup,doksoft_stat',
                'filebrowserImageBrowseUrl': '/goodarch2u/lib/ckeditor/plugins/imgbrowse/imgbrowse.html',
                'filebrowserImageUploadUrl': '/goodarch2u/lib/ckeditor/plugins/imgupload/imgupload.php',
                'language': 'en'
            });
            //CKEDITOR.instances[id].setData(data);
		}
    }
    return {
        restrict: "A",
        scope:{
            model:'=ngModel'
        },
        replace:false,
        link:function(scope,el,attrs,modelCtrl){
        	
        	scope.$watch('model',function(newVal,oldVal){
        	    
        	    if(typeof newVal!='undefined'){
        	        setTimeout(function(){
        	            set_editor(attrs.id,newVal);
        	        },1000);
        	        
        	    }
        	},true);
        	
        }
    };
});

angular.module('managerApp').directive("importProduct",function(CRUD,$location){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="import_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel">&nbsp;&nbsp;{{\'lg_products.import_products\' | translate}}</h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                          		'<div id="tree-container">'+
                          		    '<span>{{\'lg_products.excel_products\' | translate}}</span>'+
                          		    '<br>'+
									'<file-field all-file="true" ng-model="ctrl.productfile" preview="previewFile" seq="0" class="btn btn-primary" >{{\'lg_main.browser\' | translate}}</file-field>'+
								    '&emsp;<span>{{ctrl.productfile.name}}</span>'+
								    '<!--<br><br>'+
								    '<span>{{\'lg_products.zip_products\' | translate}}</span>'+
                          		    '<br>'+
								    '<file-field all-file="true" ng-model="ctrl.productzip" preview="previewZip" seq="0" class="btn btn-primary" >{{\'lg_main.browser\' | translate}}</file-field>'+
								    '&emsp;<span>{{ctrl.productzip.name}}</span>-->'+
								'</div>'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close">{{\'lg_main.cancel\' | translate}}</a>'+
                               	'<a href="javascript:void(0)" ng-click="fileUpd()" class="btn btn-lg btn-second">{{\'lg_main.cfm\' | translate}}</a>'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		link:function(scope,el,attr){
		    scope.formatmode=attr.formatmode;
		},
		controller:function($scope,CRUD){
		    
		    $scope.fileUpd=function(){
		        var updFile=$scope.previewFile[0];
		        if(updFile){
		            CRUD.update({task:"product_import",excelfile:updFile}, "POST").then(function(res){
            			if(res.status == 1) {
            			    if(res.errmsg){
            			        success(res.errmsg+"<br>"+"<a href='../upload/tmpupd/errpro.csv' target='_blank' ng-bind=\"'lg_main.error_msg' | translate\">點擊下載錯誤商品資料</a>");
            			    }else{
            				    error(res.msg);
            			    }
            				$("#import_product").modal("hide");
            			}
            		});
		        }else{
		            //請選擇檔案
					error($translate.instant('lg_main.select_file'));
		        }
		        
		    };
		    
　　　  }
    };
});