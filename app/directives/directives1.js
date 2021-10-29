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
	.module('goodarch2uApp')
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
angular.module('goodarch2uApp').directive('dndList', function(CRUD) {
 
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


angular.module('goodarch2uApp').directive("pageCtrlEl",function($location,urlCtrl){
    return {
        restrict: "E",
        scope: {//傳進來的參數。@表示單次綁定、=表示雙向綁定
            cnt:"="
        },
        template: '<div class="col-xs-12 padding_0">'+
        			'<div class="text-center">'+
						'<ul class="pagination">'+
							'<li ng-class="pageel_ctrl.cur<=1?\'disabled\':\'\'"><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(pageel_ctrl.prepage.p, params)" >«</a></li>'+
							'<li ng-class="{\'true\': \'active\', \'false\': \'\'}[page.active]" ng-repeat="page in pageel_ctrl.pages"><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(page.p, params)" ng-bind="page.p"></a></li>'+
							'<li ng-class="pageel_ctrl.cur>=pageel_ctrl.pageCnt?\'disabled\':\'\'"><a href="javascript:void(0)" ng-click="pageel_ctrl.pageChg(pageel_ctrl.postpage.p, params)" >»</a></li>'+
						'</ul>'+
					'</div>'+
				'</div>',
		replace: true,
		link: function (scope, element, attrs,controller) {
		    var self=controller;
		    self.param="?";
		    var createPageCtrl=function(pageCnt) {
                pageCnt=!pageCnt?1:pageCnt;
                var cur=1;
                var param=$location.search();
                
                angular.forEach(param,function(v,k){
                	if(k!="cur"){
                		self.param+=k+"="+v+"&";
                	}else{
                		cur=v;
                	}
                });
                
                cur=!cur?1:cur;
                cur=parseInt(cur);
                pageCnt=parseInt(pageCnt);
                cur = cur < 1 ? 1 : cur > pageCnt ? pageCnt : cur;
                
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
		            createPageCtrl(newval);
		        }
		    });
            
        },
		controllerAs: 'pageel_ctrl',
		controller:function($scope,urlCtrl){
		    var self = this;
            self.pageChg=function(cur){
                if(self.cur != cur) {
                	
                    urlCtrl.go(self.path+self.param+"cur="+cur);
                }
            };
        
		}
    };
});

angular.module('goodarch2uApp').directive('onFinishRender', function ($timeout) {
    return {
        restrict: 'A',
        link: function (scope, element, attr) {
        	
            if (scope.$last === true) {
                $timeout(function () {
                    scope.$emit('ng-repeat-finished');
                });
            }
        }
    }
});

angular.module('goodarch2uApp').directive("cartModal",function(CRUD,$location,$timeout,sessionCtrl){
    return {
        restrict: "E",
        scope: {
            modalindex: "=",
            proid: "=",
            proname: "=",
            proimg: "=",
            prositeamt: "=",
            proformat1title: "=",
            proformat2title: "=",
            formatonly: "=",
			format1only: "=",
            format2only: "=",
            proformat1: "=",
            proformat2: "=",
            proformat22: "=",
            profid: "=",
            addpromax: "=",
            protype: "@",
			eventdid: "@"
        },
        
        template: '<div class="modal fade" id="modal_addtocart{{modalindex}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
					  '<div class="modal-dialog">'+
					    '<div class="modal-content">'+
					      '<div class="modal-header modal_header">'+
					            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					            '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;'+
					            	'<span ng-if="protype != \'freepro\'" ng-bind="\'lg_directives.directives_add_cart\' | translate">放入購物車</span>'+
					            	'<span ng-if="protype == \'freepro\'" ng-bind="\'lg_directives.directives_edit_format\' | translate">修改規格</span>'+
					            '</h4>'+
					      '</div>'+
					      '<div class="modal-body modal_body">'+
					      		'<div class="row">'+
					                '<div class="col-xs-12 col-sm-5">'+
					                    '<img ng-src="{{proimg}}" class="img-responsive img-rounded center-block">'+
					                '</div>'+
					                
					                '<div class="col-xs-12 col-sm-7 modal_body_content">'+
					                    '<ul class="modal_product_detail_list">'+
					                        '<li class="col-xs-12"><h3>{{proname}}</h3>'+
					                        '</li>'+
					                        '<li ng-show="proformat1" class="col-xs-6"><p>{{proformat1title}}</p>'+
                                                '<select class="form-control" ng-options="data.name for data in proformat1 track by data.id" ng-model="model_cart_ctrl.modal_cart_format1[modalindex]">'+
													'<option value="">{{\'lg_main.select\' | translate}}{{proformat1title}}</option>'+
                                                '</select>'+
                                            '</li>'+
                                            '<li ng-show="proformat2" class="col-xs-6"><p>{{proformat2title}}</p>'+
                                                '<select class="form-control" ng-options="data.name for data in proformat2[model_cart_ctrl.modal_cart_format1[modalindex].id] track by data.id" ng-model="model_cart_ctrl.modal_cart_format2[modalindex]">'+
                                                  '<option value="">{{\'lg_main.select\' | translate}}{{proformat2title}}</option>'+
                                                '</select>'+
                                            '</li>'+
					                        '<li ng-show="protype != \'freepro\'" class="col-xs-12"><p ng-bind="\'lg_main.count\' | translate">數量</p>'+
                                                '<div class="input-group">'+
					                                '<span class="input-group-btn"><button type="button" ng-click="model_cart_ctrl.modal_cart_num_chg(-1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-minus"></i></button></span>'+
					                                '<input valid-number class="form-control required form-control-xs form-control-inline" min="0" max="99999" ng-model="model_cart_ctrl.modal_cart_num[modalindex]" type="text" maxlength="5" >'+
					                                '<span class="input-group-btn"><button type="button" ng-click="model_cart_ctrl.modal_cart_num_chg(1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-plus"></i></button></span>'+
					                            '</div>'+
                                            '</li>'+
					                        '<li ng-if="protype != \'freepro\' && model_cart_ctrl.showMsg" class="col-xs-12"><p align="left" class="price" style="font-size:1em">'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg1\' | translate">該商品目前缺貨中! 仍可購買，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg2\' | translate">但購買後無法立即出貨，</txt><br />'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg3\' | translate">必須等到商品到貨後另行通知出貨時間，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg4\' | translate">如不想等待，</txt>'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg5\' | translate">請挑選購買其他商品，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg6\' | translate">謝謝!</txt>'+
											'</p></li>'+
											'<li ng-if="protype != \'freepro\' && model_cart_ctrl.showMsg2" class="col-xs-12"><p align="left" class="price" style="font-size:1em">'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg7\' | translate">注意：該商品目前庫存數量為</txt><txt ng-bind="model_cart_ctrl.modal_cart_num[modalindex]"></txt><txt ng-bind="\'lg_directives.directives_cartModal_msg8\' | translate">，您所購買數的量已經達到目前庫存，謝謝！</txt>'+
											'</p></li>'+
					                        '<li ng-if="protype != \'freepro\'" class="col-xs-12"><p align="right"><txt ng-bind="\'lg_directives.directives_subtotal\' | translate">小計：</txt><span class="price">{{("lg_money."+model_cart_ctrl.currency) | translate}} {{(prositeamt*100*model_cart_ctrl.modal_cart_num[modalindex])/100 | formatnumber}}</span></p></li>'+
					                    '</ul>'+
					                '</div>'+
					                '<div class="col-xs-12 col-sm-12">'+
					                '<div class="video_container" ng-if="promedia">'+
					                	'<iframe height="100%" ng-src="{{promedia_url}}" frameborder="0" allowfullscreen></iframe>'+
					                '</div>'+
					                '</div>'+
					            '</div>'+
					      '</div>'+
					      
					      '<div class="modal-footer">'+
					           '<div class="btn-group btn-group-justified">'+
					           	'<a ng-if="protype != \'amtpro\' && protype != \'freepro\'" href="javascript:void(0)" ng-click="add_to_cart(\'go\')" class="btn btn-lg btn-first btn_left" ng-bind="\'lg_directives.directives_go_pay\' | translate">立即結帳</a>'+
					            '<a ng-if="protype != \'freepro\'" href="javascript:void(0)" ng-click="add_to_cart()" class="btn btn-lg btn-second" ng-bind="\'lg_directives.directives_add_cart\' | translate">放入購物車</a>'+
					            '<a ng-if="protype == \'freepro\'" href="javascript:void(0)" ng-click="add_to_cart()" class="btn btn-lg btn-second" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
					           '</div>'+
					      '</div>'+
					    '</div>'+
					  '</div>'+
					'</div>',
		replace: true,
		controllerAs: 'model_cart_ctrl',
		controller:function($scope,$rootScope,$translate){
				var self=this;
				
				self.lang = sessionCtrl.get('_lang');
				self.currency = sessionCtrl.get('_currency');
				
				
				$scope.proformat1=[];
				self.num_arr=[{value:1}];
				self.modal_cart_format1=[];
				self.modal_cart_format2=[];
				self.modal_cart_num=[];
				self.modal_cart_num[$scope.modalindex]=1;
				self.showMsg = false;
				self.showMsg2 = false;
				
				$scope.$watch(function(){
					return $scope.format1only;
				}, function(value){
					
					if($scope.formatonly && $scope.format1only)
					{
						self.modal_cart_format1[$scope.modalindex] = $scope.format1only;
					}
				});
				
				$scope.$watch(function(){
					return $scope.format2only;
				}, function(value){
					
					if($scope.formatonly && $scope.format2only)
					{
						self.modal_cart_format2[$scope.modalindex] = $scope.format2only;
					}
				});
				
				$scope.$watch('model_cart_ctrl.modal_cart_format1[modalindex]', function(value){
					
					if(value){
						self.modal_cart_num[$scope.modalindex]=1;
					}
					self.showMsgChk();
				});
				
				$scope.$watch('model_cart_ctrl.modal_cart_format2[modalindex]', function(value){
					
					if(value){
						self.modal_cart_num[$scope.modalindex]=1;
					}
					self.showMsgChk();
				});
				
			
				$scope.$watch('model_cart_ctrl.modal_cart_num[modalindex]', function(value){
					
					self.showMsg2 = false;
					self.modal_cart_num[$scope.modalindex]=parseInt(self.modal_cart_num[$scope.modalindex]);
					
					if(isNaN(self.modal_cart_num[$scope.modalindex]))self.modal_cart_num[$scope.modalindex]=1;
					
					if(self.modal_cart_format1[$scope.modalindex] && self.modal_cart_format2[$scope.modalindex])
					{
						if($scope.proformat22)
						{
							if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id])
							{
								if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock && self.modal_cart_num[$scope.modalindex])
								{
									if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock <= self.modal_cart_num[$scope.modalindex])
									{
										if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instockchk == 1)
										{
											self.modal_cart_num[$scope.modalindex] = $scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock;
											self.showMsg2 = true;
										}
									}
								}
							}
						}
					}
					
					
					self.showMsgChk();
				});
				
				/*
				$scope.$watch('model_cart_ctrl.modal_cart_format1[modalindex]', function(value){
					
					if(value){
						self.modal_cart_num[$scope.modalindex]=1;
					}
					
				});
				*/
				
				self.modal_cart_num_chg=function(v){
					self.modal_cart_num[$scope.modalindex]=parseInt(self.modal_cart_num[$scope.modalindex]);
					
					if(isNaN(self.modal_cart_num[$scope.modalindex]))self.modal_cart_num[$scope.modalindex]=1;
			　　　　　　　　	
					if(self.modal_cart_num[$scope.modalindex]<1)self.modal_cart_num[$scope.modalindex]=1;
					
					self.showMsg2 = false;
					if(self.modal_cart_format1[$scope.modalindex] && self.modal_cart_format2[$scope.modalindex] && v > 0)
					{
						if($scope.proformat22)
						{
							if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id])
							{
								if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock && self.modal_cart_num[$scope.modalindex])
								{
									if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock <= self.modal_cart_num[$scope.modalindex])
									{
										if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instockchk == 1)
										{
											v=0;
											self.showMsg2 = true;
										}
									}
								}
							}
						}
					}
					
					self.modal_cart_num[$scope.modalindex]=self.modal_cart_num[$scope.modalindex]+v;
					if(self.modal_cart_num[$scope.modalindex]<1)self.modal_cart_num[$scope.modalindex]=1;
					
					if($scope.protype == 'amtpro' && $scope.addpromax > 0)
					{
						if(self.modal_cart_num[$scope.modalindex] > $scope.addpromax )
						{
							self.modal_cart_num[$scope.modalindex] = $scope.addpromax;
						}
					}
					
					self.showMsgChk();
				};
				
				self.showMsgChk = function(){
					self.showMsg = false;
					if(self.modal_cart_format1[$scope.modalindex] && self.modal_cart_format2[$scope.modalindex])
					{
						if($scope.proformat22)
						{
							if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id])
							{
								if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock && self.modal_cart_num[$scope.modalindex])
								{
									if($scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock < self.modal_cart_num[$scope.modalindex] && $scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instockchk != 1 )
									{
										self.showMsg = true;
									}
								}
								
								//當一開始庫存為0的處理
								if( $scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instock <= 0 && $scope.proformat22[self.modal_cart_format1[$scope.modalindex].id][self.modal_cart_format2[$scope.modalindex].id].instockchk == 1)
								{
									self.modal_cart_num[$scope.modalindex]=0;
									self.showMsg2 = true;
								}
							}
						}
					}
					
				};
				
　　　　　　　  var ourl=CRUD.getUrl();
　　　　　　　　$scope.add_to_cart=function(t){
　　　　　　　　	var err=0;
　　　　　　　　
　　　　　　　　	var id=$scope.proid;
　　　　　　　　	
　　　　　　　　	if($scope.protype == 'amtpro')
　　　　　　　　	{
　　　　　　　　		var task = "update_amtpro_cart_list2";
　　　　　　　　	}
　　　　　　　　	else if($scope.protype == 'freepro')
　　　　　　　　	{
　　　　　　　　		var task = "update_freepro_cart_list2";
　　　　　　　　		self.modal_cart_num[$scope.modalindex] = 1;
　　　　　　　　	}
　　　　　　　　	else
　　　　　　　　	{
　　　　　　　　		var task = "update_cart_list2";
　　　　　　　　	}
　　　　　　　　	
　　　　　　　　	var param={
　　　　　　　　		task:task,
　　　　　　　　		id:$scope.proid,num:self.modal_cart_num[$scope.modalindex]
　　　　　　　　		
　　　　　　　　	};
　　　　　　　　	
　　　　　　　　	if($scope.protype == 'freepro')
　　　　　　　　	{
　　　　　　　　		param.fid = $scope.profid;
　　　　　　　　	}

					if($scope.protype == 'event')
　　　　　　　　	{
　　　　　　　　		param.eventdid = $scope.eventdid;
　　　　　　　　	}
　　　　　　　　	
　　　　　　　　	if(self.modal_cart_format1[$scope.modalindex]){
　　　　　　　　		param.format1=self.modal_cart_format1[$scope.modalindex].id;
　　　　　　　　	}else{
　　　　　　　　		err=1;
　　　　　　　　		error($translate.instant('lg_main.select')+$scope.proformat1title);
　　　　　　　　	}
　　　　　　　　	if($scope.proformat2){
						if(self.modal_cart_format2 && self.modal_cart_format2[$scope.modalindex]){
							param.format2=self.modal_cart_format2[$scope.modalindex].id;
						}else{
							err=1;
							error($translate.instant('lg_main.select')+$scope.proformat2title);
						}
　　　　　　　　	}

					if(self.modal_cart_num[$scope.modalindex] <= 0)
					{
						err=1;
						//請選擇數量
						error($translate.instant('lg_main.select')+$translate.instant('lg_main.count'));
					}

　　　　　　　　	if(err==0){
　　　　　　　　		CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　		CRUD.update(param,'GET').then(function(res){
　　　　　　　　			$rootScope.cartCnt=res.cnt;
　　　　　　　　			$('#modal_addtocart'+$scope.modalindex).modal('hide');
　　　　　　　　			if(res.status==1){
								if(t=="go"){
									$timeout(function(){
										$location.path("cart_list");
									},200);
								}else{
									$timeout(function(){
										$rootScope.getlist();
									},200);
								}
　　　　　　　　			}
　　　　　　　　		});
　　　　　　　　		CRUD.setUrl(ourl);
　　　　　　　　	}
　　　　　　　　	
　　　　　　　　};
　　　　　　　　$scope.$watch('modal_cart_num', function(value){
					$scope.modaltotal=value*$scope.amt;
				}, true);
				
　　　　　 }
    };
});


angular.module('goodarch2uApp').directive("bonusCartModal",function(CRUD,$location,$timeout){
    return {
        restrict: "E",
        scope: {
            modalindex: "=",
            proid: "=",
            proname: "=",
            proimg: "=",
            prositeamt: "=",
            proformat1title: "=",
            proformat2title: "=",
			formatonly: "=",
			format1only: "=",
            format2only: "=",
            proformat1: "=",
            proformat2: "="
        },
        
        template: '<div class="modal fade" id="modal_addtocart{{modalindex}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
					  '<div class="modal-dialog">'+
					    '<div class="modal-content">'+
					      '<div class="modal-header modal_header">'+
					            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					            '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_add_cart\' | translate">放入購物車</txt></h4>'+
					      '</div>'+
					      '<div class="modal-body modal_body">'+
					      		'<div class="row">'+
					                '<div class="col-xs-12 col-sm-5">'+
					                    '<img ng-src="{{proimg}}" class="img-responsive img-rounded center-block">'+
					                '</div>'+
					                
					                '<div class="col-xs-12 col-sm-7 modal_body_content">'+
					                    '<ul class="modal_product_detail_list">'+
					                        '<li class="col-xs-12"><h3>{{proname}}</h3>'+
					                        '</li>'+
					                        '<li ng-show="proformat1" class="col-xs-6"><p>{{proformat1title}}</p>'+
                                                '<select class="form-control" ng-options="data.name for data in proformat1 track by data.id" ng-model="model_cart_ctrl.modal_cart_format1[modalindex]">'+
													'<option value="">{{\'lg_main.select\' | translate}}{{proformat1title}}</option>'+
                                                '</select>'+
                                            '</li>'+
                                            '<li ng-show="proformat2" class="col-xs-6"><p>{{proformat2title}}</p>'+
                                                '<select class="form-control" ng-options="data.name for data in proformat2[model_cart_ctrl.modal_cart_format1[modalindex].id] track by data.id" ng-model="model_cart_ctrl.modal_cart_format2[modalindex]">'+
                                                  '<option value="">{{\'lg_main.select\' | translate}}{{proformat2title}}</option>'+
                                                '</select>'+
                                            '</li>'+
					                        '<li ng-show="protype != \'freepro\'" class="col-xs-12"><p ng-bind="\'lg_main.count\' | translate">數量</p>'+
                                                '<div class="input-group">'+
					                                '<span class="input-group-btn"><button type="button" ng-click="model_cart_ctrl.modal_cart_num_chg(-1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-minus"></i></button></span>'+
					                                '<input valid-number class="form-control required form-control-xs form-control-inline" min="0" max="99999" ng-model="model_cart_ctrl.modal_cart_num[modalindex]" type="text" maxlength="5" >'+
					                                '<span class="input-group-btn"><button type="button" ng-click="model_cart_ctrl.modal_cart_num_chg(1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-plus"></i></button></span>'+
					                            '</div>'+
                                            '</li>'+
					                        '<li class="col-xs-12"><p align="right"><txt ng-bind="\'lg_directives.directives_used_point\' | translate">消費點數：</txt><span class="price">{{\'lg_directives.directives_bonus\' | translate}} {{(prositeamt*100*model_cart_ctrl.modal_cart_num[modalindex])/100 | formatnumber}}{{\'lg_directives.directives_point\' | translate}}</span></p></li>'+
					                    '</ul>'+
					                '</div>'+
					                '<div class="col-xs-12 col-sm-12">'+
					                '<div class="video_container" ng-if="promedia">'+
					                	'<iframe height="100%" ng-src="{{promedia_url}}" frameborder="0" allowfullscreen></iframe>'+
					                '</div>'+
					                '</div>'+
					            '</div>'+
					      '</div>'+
					      
					      '<div class="modal-footer">'+
					           '<div class="btn-group btn-group-justified">'+
					           	'<a href="javascript:void(0)" ng-click="add_to_cart(\'go\')" class="btn btn-lg btn-first btn_left" ng-bind="\'lg_directives.directives_go_exchange\' | translate">立即兌換</a>'+
					            '<a href="javascript:void(0)" ng-click="add_to_cart()" class="btn btn-lg btn-second" ng-bind="\'lg_directives.directives_add_exchange_list\' | translate">加入兌換清單</a>'+
					           '</div>'+
					      '</div>'+
					    '</div>'+
					  '</div>'+
					'</div>',
		replace: true,
		controllerAs: 'model_cart_ctrl',
		controller:function($scope,$rootScope,$translate){
				var self=this;
				$scope.proformat1=[];
				self.num_arr=[{value:1}];
				self.modal_cart_format1=[];
				self.modal_cart_format2=[];
				self.modal_cart_num=[];
				self.modal_cart_num[$scope.modalindex]=1;
				$scope.$watch('model_cart_ctrl.modal_cart_format1[modalindex]', function(value){
					if(value){
						self.modal_cart_num[$scope.modalindex]=1;
					}
					
				});
				
				$scope.$watch(function(){
					return $scope.format1only;
				}, function(value){
					if($scope.formatonly && $scope.format1only)
					{
						self.modal_cart_format1[$scope.modalindex] = $scope.format1only;
					}
				});
				
				
				
				$scope.$watch(function(){
					return $scope.format2only;
				}, function(value){
					if($scope.formatonly && $scope.format2only)
					{
						self.modal_cart_format2[$scope.modalindex] = $scope.format2only;
					}
				});
			
				$scope.$watch('model_cart_ctrl.modal_cart_format2[modalindex]', function(value){
					if(value){
						self.modal_cart_num[$scope.modalindex]=1;
						var instock=[];
						for(var i=1;i<=value.instock;i++){
							instock.push({value:i});
						}
						self.num_arr=instock;
					}
				});
				
				self.modal_cart_num_chg=function(v){
					self.modal_cart_num[$scope.modalindex]=parseInt(self.modal_cart_num[$scope.modalindex]);
					
					if(isNaN(self.modal_cart_num[$scope.modalindex]))self.modal_cart_num[$scope.modalindex]=0;
			　　　　　　　　	
					if(self.modal_cart_num[$scope.modalindex]<0)self.modal_cart_num[$scope.modalindex]=0;
					self.modal_cart_num[$scope.modalindex]=self.modal_cart_num[$scope.modalindex]+v;
					if(self.modal_cart_num[$scope.modalindex]<0)self.modal_cart_num[$scope.modalindex]=0;
				};
				
　　　　　　　  var ourl=CRUD.getUrl();
　　　　　　　　$scope.add_to_cart=function(t){
　　　　　　　　	var err=0;
　　　　　　　　
　　　　　　　　	var id=$scope.proid;
　　　　　　　　	var param={
　　　　　　　　		task:'update_bonus_cart_list',
　　　　　　　　		id:$scope.proid,num:self.modal_cart_num[$scope.modalindex]
　　　　　　　　		
　　　　　　　　	};
　　　　　　　　	if(self.modal_cart_format1[$scope.modalindex]){
　　　　　　　　		param.format1=self.modal_cart_format1[$scope.modalindex].id;
　　　　　　　　	}else{
　　　　　　　　		err=1;
　　　　　　　　		error($translate.instant('lg_main.select')+$scope.proformat1title);
　　　　　　　　	}
　　　　　　　　	if($scope.proformat2){
						if(self.modal_cart_format2 && self.modal_cart_format2[$scope.modalindex]){
							param.format2=self.modal_cart_format2[$scope.modalindex].id;
						}else{
							err=1;
							error($translate.instant('lg_main.select')+$scope.proformat2title);
						}
　　　　　　　　	}
　　　　　　　　	if(err==0){
　　　　　　　　		CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　		CRUD.update(param,'POST').then(function(res){
　　　　　　　　			
　　　　　　　　			
　　　　　　　　			$('#modal_addtocart'+$scope.modalindex).modal('hide');
　　　　　　　　			if(res.status==1){
　　　　　　　　				$rootScope.cartCnt=res.cnt;
								if(t=="go"){
									
									$timeout(function(){
										$location.path("cart_list");
									},200);
								}
　　　　　　　　			}
　　　　　　　　		});
　　　　　　　　		CRUD.setUrl(ourl);
　　　　　　　　	}
　　　　　　　　	
　　　　　　　　};
　　　　　　　　$scope.$watch('modal_cart_num', function(value){
					$scope.modaltotal=value*$scope.amt;
				});
				
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("cartEditModal",function(CRUD,$timeout,sessionCtrl){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="modal_addtocart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
					  '<div class="modal-dialog">'+
					    '<div class="modal-content">'+
					      '<div class="modal-header modal_header">'+
					            '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					            '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_edit_count\' | translate">修改數量</txt></h4>'+
					      '</div>'+
					      '<div class="modal-body modal_body">'+
					      		'<div class="row">'+
					                '<div class="col-xs-12 col-sm-5">'+
					                    '<img ng-src="{{imgname}}" class="img-responsive img-rounded center-block">'+
					                '</div>'+
					                
					                '<div class="col-xs-12 col-sm-7 modal_body_content">'+
					                    '<ul class="modal_product_detail_list">'+
					                        '<li class="col-xs-12"><h3>{{ctrl.title}}</h3>'+
					                        '<p>{{ctrl.summary}}</p>'+
					                        '</li>'+
					                        '<li class="col-xs-12"><p ng-bind="\'lg_directives.directives_count\' | translate">數量：</p>'+
					                            '<div class="input-group">'+
					                                '<span class="input-group-btn"><button type="button" ng-click="modal_cart_num_chg(-1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-minus"></i></button></span>'+
					                                '<input valid-number class="form-control required form-control-xs form-control-inline" min="0" max="99999" ng-model="modal_cart_num" type="text" maxlength="5" >'+
					                                '<span class="input-group-btn"><button type="button" ng-click="modal_cart_num_chg(1)" class="btn btn-default btn-checkout" style="height:37px;"><i class="fa fa-plus"></i></button></span>'+
					                            '</div>'+
					                        '</li>'+
											'<li ng-if="showMsg" class="col-xs-12"><p align="left" class="price" style="font-size:1em">'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg1\' | translate">該商品目前缺貨中! 仍可購買，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg2\' | translate">但購買後無法立即出貨，</txt><br />'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg3\' | translate">必須等到商品到貨後另行通知出貨時間，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg4\' | translate">如不想等待，</txt>'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg5\' | translate">請挑選購買其他商品，</txt><br /><txt ng-bind="\'lg_directives.directives_cartModal_msg6\' | translate">謝謝!</txt>'+
											'</p></li>'+
											'<li ng-if="showMsg2" class="col-xs-12"><p align="left" class="price" style="font-size:1em">'+
											'<txt ng-bind="\'lg_directives.directives_cartModal_msg7\' | translate">注意：該商品目前庫存數量為</txt><txt ng-bind="modal_cart_num"></txt><txt ng-bind="\'lg_directives.directives_cartModal_msg8\' | translate">，您所購買數的量已經達到目前庫存，謝謝！</txt>'+
											'</p></li>'+
					                        '<li class="col-xs-12"><p align="right"><txt ng-bind="\'lg_directives.directives_subtotal\' | translate">小計：</txt><span class="price">{{("lg_money."+currency) | translate}} <span ng-bind="modaltotal | formatnumber"></span></span></p></li>'+
					                    '</ul>'+
					                '</div>'+
					                
					            '</div>'+
					      '</div>'+
					      
					      '<div class="modal-footer">'+
					           '<div class="btn-group btn-group-justified">'+
					           	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
					            '<a href="javascript:void(0)" ng-click="cart_num_chg()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.edit\' | translate">修改</a>'+
					           '</div>'+
					      '</div>'+
					    '</div>'+
					  '</div>'+
					'</div>',
		replace: true,
		controller:function($scope,$rootScope){
　　　　　　　　
				$scope.lang = sessionCtrl.get('_lang');
				$scope.currency = sessionCtrl.get('_currency');
				
				$scope.$watch('modal_cart_num', function(value){
					$scope.showMsg2 = false;
					$scope.modal_cart_num=parseInt($scope.modal_cart_num);
					$scope.showMsg = false;
					if($scope.instock && $scope.modal_cart_num)
					{
						if($scope.instock <= $scope.modal_cart_num && $scope.instockchk == 1)
						{
							 $scope.modal_cart_num = $scope.instock;
							 $scope.showMsg2 = true;
						}
						else if($scope.instock < $scope.modal_cart_num)
						{
							$scope.showMsg = true;
						}
					}
				});
				
				
				$scope.showMsg = false;
				$scope.showMsg2 = false;

				$scope.modal_cart_num_chg=function(v){
　　　　　　　　	$scope.showMsg2 = false;
					$scope.modal_cart_num=parseInt($scope.modal_cart_num);
　　　　　　　　	$scope.amt=parseInt($scope.amt);
　　　　　　　　	if(isNaN($scope.modal_cart_num))$scope.modal_cart_num=1;
　　　　　　　　	
　　　　　　　　	if($scope.modal_cart_num<1)$scope.modal_cart_num=1;
　　　　　　　　	
　　　　　　　　	if($scope.instock && $scope.modal_cart_num && v > 0)
					{
						if($scope.instock <= $scope.modal_cart_num)
						{
							if($scope.instockchk == 1)
							{
								v = 0;
								$scope.showMsg2 = true;
							}
						}
					}
　　　　　　　　	
　　　　　　　　	$scope.modal_cart_num=$scope.modal_cart_num+v;
　　　　　　　　	if($scope.modal_cart_num<1)$scope.modal_cart_num=1;
　　　　　　　　	
　　　　　　　　	$scope.modaltotal=$scope.modal_cart_num*$scope.amt;					
					$scope.showMsg = false;
					if($scope.instock && $scope.modal_cart_num)
					{
						if($scope.instock < $scope.modal_cart_num)
						{
							$scope.showMsg = true;
						}
					}
　　　　　　　　};

　　　　　　　　var ourl=CRUD.getUrl();
　　　　　　　　
　　　　　　　　$scope.cart_num_chg=function(){
　　　　　　　　	var id=$scope.proid;
　　　　　　　　	if($scope.modal_cart_num<1)$scope.modal_cart_num=1;
　　　　　　　　	
　　　　　　　　	CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　	CRUD.update({task:'edit_cart_list',id:id,num:$scope.modal_cart_num,format1:$scope.format1,format2:$scope.format2,protype:$scope.protype,eventid:$scope.eventid},'GET',true).then(function(res){
　　　　　　　　		$rootScope.cartCnt=res.cnt;
　　　　　　　　		$rootScope.data_list = res.data;
						$rootScope.total = res.total;
						$rootScope.allamt = res.amt;
						$rootScope.active_list = res.active_list;
						$rootScope.discount = res.discount;
						
						$rootScope.dlvrAmt=res.dlvrAmt;
　　　　　　　　	});
　　　　　　　　	CRUD.setUrl(ourl);
　　　　　　　　	
　　　　　　　　	$timeout(function(){
　　　　　　　　		$rootScope.getlist();
　　　　　　　　	},200);
　　　　　　　　};
　　　　　　　　$scope.$watch('modal_cart_num', function(value){
					$scope.modaltotal=value*$scope.amt;
				});
				
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("cartDelModal",function(CRUD,$location,$timeout){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="modal_delfromcart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                      '<div class="modal-dialog">'+
                        '<div class="modal-content">'+
                          '<div class="modal-header modal_header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                '<h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_delete_product\' | translate">移除商品</txt></h4>'+
                          '</div>'+
                          '<div class="modal-body modal_body">'+
                          		'<div class="row">'+
                                    
                                    '<div class="col-xs-12 modal_body_content">'+
                                        '<ul class="modal_product_detail_list">'+
                                            '<li class="col-xs-12">'+
                                            '<p><txt ng-bind="\'lg_directives.directives_delete_msg1\' | translate">您確定要移除</txt><span class="txt_red"> {{ctrl.title}} </span><txt ng-bind="\'lg_directives.directives_delete_msg2\' | translate">這項商品嗎？</txt></p>'+
                                            
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+
                                    
                                '</div>'+
                          '</div>'+
                          '<div class="modal-footer">'+
                               '<div class="btn-group btn-group-justified">'+
                               	'<a href="javascript:void(0)" class="btn btn-lg btn-first btn_left" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.cancel\' | translate">取消</a>'+
                               	'<a href="javascript:void(0)" ng-click="del_from_cart()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
                               '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                    '</div>',
		replace: true,
		controller:function($scope,$rootScope){
　　　　　　　　var ourl=CRUD.getUrl();
　　　　　　　　
　　　　　　　　$scope.del_from_cart=function(){
　　　　　　　　	var id=$scope.proid;
　　　　　　　　	CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　	CRUD.update({task:'update_cart_list2',id:id,num:0,format1:$scope.format1,format2:$scope.format2,protype:$scope.protype,eventid:$scope.eventid},'GET').then(function(res){
　　　　　　　　		if(res.status==1){
                            $rootScope.cartCnt=res.cnt;
                            $rootScope.data_list = res.data;
                            $rootScope.allamt = res.amt;
                            $rootScope.total = res.total;
                            $rootScope.active_list = res.active_list;
                            $rootScope.discount = res.discount;
                            $rootScope.cart_use_coin = parseInt(res.usecoin);
                            
                            $rootScope.dlvrAmt=res.dlvrAmt;
                            $timeout(function(){
            　　　　　　　　	$rootScope.getlist();
                            });
　　　　　　　　		}else{
　　　　　　　　			$rootScope.cartCnt=res.cnt;
　　　　　　　　			$('#modal_delfromcart').modal('hide');
　　　　　　　　			$timeout(function(){
　　　　　　　　				$location.path("index_page");
　　　　　　　　			},200);
　　　　　　　　		}
　　　　　　　　	});
　　　　　　　　	CRUD.setUrl(ourl);
　　　　　　　　	
　　　　　　　　	$timeout(function(){
　　　　　　　　		$rootScope.getlist();
　　　　　　　　	},200);
　　　　　　　　};
　　　　　　　　
　　　　　 }
    };
});

angular.module('goodarch2uApp').directive("payModal",function(CRUD){

    return {
        restrict: "E",
        template: '<div class="modal fade" id="myModal_PAY" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'+
                          '<div class="modal-dialog">'+
                            '<div class="modal-content">'+
                              '<div class="modal-header modal_header">'+
                                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
                                    '<h4 class="modal-title" id="myModalLabel"><i class="fa fa-money"></i>&nbsp;&nbsp;<txt ng-bind="\'lg_directives.directives_paytype\' | translate">付款方式</txt></h4>'+
                              '</div>'+
                              '<div class="modal-body modal_body">'+
                              		'<div class="row">'+
                                        '<div class="col-xs-12 modal_body_content">'+
                                            '<ul class="modal_product_detail_list">'+
                                                '<li class="col-xs-12"><p ng-bind="\'lg_directives.directives_select_paytype\' | translate">請選擇付款方式：</p>'+
                                                    '<div class="radio i-checks" ng-repeat="data in pay_type_list">'+
                                                      '<label>'+
                                                        '<input type="radio" ng-model="pay_type.id" ng-value="data.id" ng-checked="pay_type.id==data.id">'+
                                                        '<i></i> {{data.name}}'+
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
                                   	'<a href="javascript:void(0)" ng-click="set_pay_type()" class="btn btn-lg btn-second" data-dismiss="modal" aria-label="Close" ng-bind="\'lg_main.ok\' | translate">確定</a>'+
                                   '</div>'+
                              '</div>'+
                            '</div>'+
                          '</div>'+
                        '</div>',
		replace: true,
		controller:function($scope){
　　　　　　　　$scope.pay_type = {
　　　　　　　　	id: 1
　　　　　　　　};
　　　　　　　　var ourl=CRUD.getUrl();
　　　　　　　　CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　CRUD.detail({task:'pay_type'},'GET').then(function(res){
　　　　　　　　	if(res.status==1){
　　　　　　　　		$scope.pay_type_list = res.data;
　　　　　　　　		$scope.pay_type.id = res.pay_type;
　　　　　　　　		$scope.pay_type_str=($scope.pay_type_list[$scope.pay_type.id]) ? $scope.pay_type_list[$scope.pay_type.id].name : '';
						if($scope.get_take_modal_data){
　　　　　　　　		    $scope.get_take_modal_data(1);
　　　　　　　　		}
　　　　　　　　	}
　　　　　　　　});
　　　　　　　　$scope.set_pay_type=function(){
　　　　　　　　	CRUD.setUrl("app/controllers/eways.php");
　　　　　　　　	CRUD.update({task:'set_pay_type',pay_type:$scope.pay_type.id},'GET').then(function(res){
　　　　　　　　		if(res.status==1){
　　　　　　　　		    if($scope.get_take_modal_data){
　　　　　　　　		    	$scope.get_take_modal_data(1);
　　　　　　　　		    	
　　　　　　　　		    	$scope.pay_type_str=$scope.pay_type_list[$scope.pay_type.id].name;
　　　　　　　　		    }
　　　　　　　　		    
　　　　　　　　		}
　　　　　　　　	});
　　　　　　　　	CRUD.setUrl(ourl);
　　　　　　　　};
　　　　　　　　
　　　　　 }
    };
});
