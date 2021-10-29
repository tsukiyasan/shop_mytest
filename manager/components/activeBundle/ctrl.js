app.controller('activeBundle_list', ['$rootScope', '$scope', 'urlCtrl', '$location', '$route', '$routeParams', 'CRUD', 
function($rootScope, $scope, urlCtrl, $location, $route, $routeParams, CRUD) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());//解碼網址hash
	if (param) {
		CRUD.setUrl("components/activeBundle/api.php");
		
		my.permission = $rootScope.funclist['activeBundle'];
		
		my.params = {
			page: param.page ? param.page : 1,
			search: param.search ? param.search: "",
			orderby: !param.orderby ? {newsDate: "asc", pubDate: "desc"} : param.orderby
		}
		my.odrhash=urlCtrl.enaes({component:'activeBundle',p:param});
		my.activeBundleList = [];
		my.list = function() {
			CRUD.list(my.params, "GET", true)
			.then(function(response) {
				if (response.status == 1) {
					my.activeBundleList = response.data;
					my.pageCount = response.pageCount;
				} else {
					if (response.errorMsg) {
						error(response.errorMsg);
					}
				}
			});
		};
		
		my.delete = function(id) {
			CRUD.del({id:id}, "GET", true)
			.then(function(response) {
				if (response.status == 1) {
					success(response.message);
					my.list();
				} else {
					if (response.errorMsg) {
						error(response.errorMsg);
					}
				}	
			});
		};
		
		my.goPage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/activeBundle_page", param);
		}
		
		my.list();
	}
}])

.controller('activeBundle_page', ['$rootScope', '$scope', 'urlCtrl', '$location', 'CRUD', 'modalService', '$translate',
function($rootScope, $scope, urlCtrl, $location, CRUD, modalService, $translate) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());//解碼網址hash
	my.textList = [];
	my.nameList = [];
	my.notesList = [];
	if (param) {
		CRUD.setUrl("components/activeBundle/api.php");
		my.actionType = param.id ? '1' : '0';
		my.permission = $rootScope.funclist['activeBundle'];
		
		var listParams = !param.listParams ? {} : param.listParams;
		
		my.datePickerOption = {
			singleDatePicker: true,
			timePicker:true,
			locale: {
	            format: 'YYYY-MM-DD HH:mm'
	        }
		};
		
		my.activeBundle = {};
		my.areaList = [];
		my.giftList = [];
		
		my.detail = function() {
			CRUD.detail(my.params, "GET")
			.then(function(response) {
				if (response.status == 1) {
					my.activeBundle = response.data;
					my.nameList=response.nameList;
					my.notesList=response.notesList;
					my.giftList = response.giftList;
					my.areaList = response.areaList;
				} else {
					if (response.errorMsg) {
						error(response.errorMsg);
					}
				}
				
			});
		}
		
		my.getBasicInfo = function(callback) {
			CRUD.detail({task: 'getBasicInfo'}, "GET")
			.then(function(response) {
				if (response.status == 1) {
					my.pvbvRatio = response.pvbvRatio;
					if (callback) {
						callback();
					}
				} else {
					if (response.errorMsg) {
						error(response.errorMsg);
					}
				}
			});
		};
		
		my.submit = function() {
			var errorCount = 0;
			
			//if (!my.activeBundle.name) {
			//	error($translate.instant('lg_activeBundle.name') + $translate.instant('lg_main.empty'));
			//	errorCount++;
			//}
			//
			//if (!my.activeBundle.notes) {
			//	error($translate.instant('lg_activeBundle.notes') + $translate.instant('lg_main.empty'));
			//	errorCount++;
			//}
			
			var nameChk = true;
			angular.forEach(my.textList,function(v,k){
				if(!my.nameList[v['code']])
				{
					nameChk = false;
				}
				my.activeBundle['name_'+v['code']] = my.nameList[v['code']];				
			});
			if(!nameChk)
			{
				error($translate.instant('lg_activeBundle.name')+$translate.instant('lg_main.empty'));
				return false;
			}
			
			var notesChk = true;
			angular.forEach(my.textList,function(v,k){
				if(!my.notesList[v['code']])
				{
					notesChk = false;
				}
				my.activeBundle['notes_'+v['code']] = my.notesList[v['code']];				
			});
			if(!notesChk)
			{
				error($translate.instant('lg_activeBundle.notes')+$translate.instant('lg_main.empty'));
				return false;
			}
			
			if (!my.activeBundle.startTime) {
				error($translate.instant('lg_activeBundle.startTime') + $translate.instant('lg_main.empty'));
				errorCount++;
			}
			
			if (!my.activeBundle.price) {
				error($translate.instant('lg_activeBundle.price') + $translate.instant('lg_main.empty'));
				errorCount++;
			}
			
			if (!my.activeBundle.pv) {
				error($translate.instant('lg_activeBundle.pv') + $translate.instant('lg_main.empty'));
				errorCount++;
			}
			
			if (my.activeBundle.passwordCheck == '1' && !my.activeBundle.passwordText) {
				error($translate.instant('lg_activeBundle.passwordText') + $translate.instant('lg_main.empty'));
				errorCount++;
			}
			
			if (my.activeBundle.giftCheck == '1' && !my.activeBundle.giftTargetAmount) {
				error($translate.instant('lg_activeBundle.giftTargetAmount') + $translate.instant('lg_main.empty'));
				errorCount++;
			}
			
			if (my.activeBundle.giftCheck == '1' && my.giftList.length == 0) {
				error($translate.instant('lg_activeBundle.giftListEmpty'));
				errorCount++;
			}

			if (my.areaList.length == 0) {
				error($translate.instant('lg_activeBundle.areaListEmpty'));
				errorCount++;
			} else {
				angular.forEach(my.areaList, function(value, key) {
					
					var nameChk = true;
					angular.forEach(my.textList,function(v,k){
						if(!value['name_'+v['code']])
						{
							nameChk = false;
						}
					});
					if(!nameChk)
					{
						error($translate.instant('lg_activeBundle.area', {number: key + 1}) + $translate.instant('lg_activeBundle.areaName') + $translate.instant('lg_main.empty'));
						errorCount++;
					}
					//if (!value.name) {
					//	error($translate.instant('lg_activeBundle.area', {number: key + 1}) + $translate.instant('lg_activeBundle.areaName') + $translate.instant('lg_main.empty'));
					//	errorCount++;
					//}
					if (!value.quantity) {
						error($translate.instant('lg_activeBundle.area', {number: key + 1}) + $translate.instant('lg_activeBundle.areaQuantity') + $translate.instant('lg_main.empty'));
						errorCount++;
					}
					if (value.productList.length == 0) {
						error($translate.instant('lg_activeBundle.areaProductEmpty', {number: key + 1}));
						errorCount++;
					}
				});
			}

			if (errorCount == 0) {
				var activeBundle = angular.copy(my.activeBundle);
				activeBundle.areaList = my.areaList.slice(0);
				activeBundle.giftList = my.giftList.map(function(o) {return o.id});
				
				CRUD.update(activeBundle, "POST")
				.then(function(response) {
					if(response.status == 1) {
						success(response.message);
						if (param.id) {
							my.detail();
							//my.goList();
						} else {
							//my.goList();
							urlCtrl.go("/activeBundle_page", {id: response.activeBundleId, listParams: listParams});
						}
					} else {
						error(response.errorMsg);
					}
				});
			}
		}
		
		CRUD.detail({task:"getBasicInfo2"}, "GET").then(function(res) {
			if(res.status == 1) {
				my.textList = res.textList;
				if(!param.id)
				{
					angular.forEach(my.textList,function(v,k){
						my.nameList[v.code] = '';
						my.nameList[v.code] = '';
					});
				}
			}
		});

		function init() {
			if (param.id) {
				my.params = {
					id: param.id
				}
				my.detail();
			} else {
				my.activeBundle = {
					enable: '1',
					pv: 0,
					limitCount: 0,
					passwordCheck: '0'
				};
			}
		}
		
		my.goList = function() {
			urlCtrl.go("/activeBundle_list", listParams);
		}
		/** 贈品相關Start **/
		my.addGiftProduct = function() {
			var exceptProductId = my.giftList.map(function(o) {return o.id});
			my.selectModalInstance = modalService.openModal("productSelector", {
				productType: "gift", 
				boardcastEvent: "addGiftProduct",
				exceptProductId: exceptProductId,
				repeatProduct: 'true'
			}, {
				size: 'lg'
			});
		}
		var selectGiftProductListener = $rootScope.$on("addGiftProduct", function(events, products) {
			angular.forEach(products, function(value, key) {
				my.giftList.push({
					id: value.id,
					name: value.name,
					image: value.image
				});	
			});
		});
		my.deleteGiftProduct = function(index) {		
			my.giftList.splice(index, 1);
		}
		/** 贈品相關End **/

		/** 分區相關Start **/
		my.addArea = function() {
			my.areaList.push( {
				name: "",
				quantity: 1,
				productList: []
			})
		}
		my.deleteArea = function(index) {			
			my.areaList.splice(index, 1);
		}
		var currentAreaIndex = 0;
		my.addAreaProduct = function(areaIndex) {
			var exceptProductId = my.areaList[areaIndex].productList.map(function(o) {return o.id});
			currentAreaIndex = areaIndex;
			my.selectModalInstance = modalService.openModal("productSelector", {
				//productType: "product", 
				boardcastEvent: "addAreaProduct",
				exceptProductId: exceptProductId
			}, {
				size: 'lg'
			});
		}
		var selectAreaProductListener = $rootScope.$on("addAreaProduct", function(events, products) {
			angular.forEach(products, function(value, key) {
				my.areaList[currentAreaIndex].productList.push({
					id: value.id,
					name: value.name,
					image: value.image
				});	
			});
		});
		$scope.$on("$destroy", function() {
			selectAreaProductListener();
			selectGiftProductListener();
	    });
		my.deleteAreaProduct = function(areaIndex, productIndex) {
			my.areaList[areaIndex].productList.splice(productIndex, 1);
		}
		/** 分區相關End **/
		my.getBasicInfo(init);
	}
	
	
}]);