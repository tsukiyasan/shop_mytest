app.controller('productSelector_modal', ['$rootScope', '$scope', 'CRUD', '$location', '$translate', '$uibModalInstance', 'items', 
function($rootScope, $scope, CRUD, $location, $translate, $uibModalInstance, items) {
    var my = this;
	my.selectedData = null;
	my.selectList = [];
	my.selectedProductType = [];
	my.productList = [];
	
	var boardcastEvent = items.boardcastEvent;
	var exceptProductId = items.exceptProductId;
	var repeatProduct = (items.repeatProduct && items.repeatProduct == 'true') ? true : false;
	if(repeatProduct) //允許重複的話，不需要除外商品
	{
		exceptProductId = "";
	}
	
	var productType = items.productType;
	function getProductTypeList(parentId) {
		CRUD.list({task: "productTypeList", parentId: parentId}, "GET", true, "modals/productSelector/api.php")
		.then(function(response) {
			if (response.status == 1) {
				my.selectList.push(response.data);
			} else {
				error(response.errorMsg);
			}
		});
	}
	
	function getProductList() {
		my.selected = [];
		var param = {
			task: "productList",
			exceptProductId: exceptProductId,
			productType: productType
		};
		
		if (my.selectedData) {
			param.productTypeId = my.selectedData.id;
		}
		
		CRUD.list(param, "POST", true, "modals/productSelector/api.php")
		.then(function(response){
			if(response.status == 1) {
				my.productList = response.data;
				initPageCtrl("productList");
			} else {
				error(response.errorMsg);
			}
		});
	}
	
	my.searchProduct = function() {
		getProductList();
	}
	
	my.selectProductType = function(level) {
		if (my.selectedProductType[level]) {
			my.productList = [];
			my.selected = [];
			var selectListLength = my.selectList.length;
			for (var i = 0; i < (selectListLength - (level + 1)); i++) {
				my.selectList.pop();
			}
			if (my.selectedProductType[level].parentId == 'root') {
				my.selectedData = null;
				getProductTypeList(my.selectedProductType[level].id);
			} else {
				my.selectedData = my.selectedProductType[level];
				getProductList();
			}
		}
	}
	
	
	my.batchAddProductRelation = function() {
		$rootScope.$emit(boardcastEvent, my.selected);
		angular.forEach(my.selected, function(val, key) {
			var index = my.productList.indexOf(val);
			my.productList.splice(index, 1);
			if (exceptProductId && !repeatProduct) {
				exceptProductId.push(val.id);
			}
		});
		initPageCtrl("productList");
		my.selected = [];
	}
	
	my.addProductRelation = function(product, index) {
		$rootScope.$emit(boardcastEvent, [product]);
		if (my.limitMode) {
			$uibModalInstance.dismiss('cancel');
		} else {
			
			//若不允許重複，加入商品的商品要從列表中排除，並加進例外商品
			if (exceptProductId && !repeatProduct) {
				my.productList.splice(index, 1);
				exceptProductId.push(product.id);
			}
			initPageCtrl("productList");
		}
	}
	
	my.cancel = function() {
		$uibModalInstance.dismiss('cancel');
	}
	
	//List Check Box Control
	my.selected = [];
	my.toggle = function (item, list) {
		var idx = list.indexOf(item);
		if (idx > -1) {
			list.splice(idx, 1);
		}
		else {
			list.push(item);
		}
	};
	my.exists = function (item, list) {
		return list.indexOf(item) > -1;
	};
	my.isIndeterminate = function() {
		return (my.selected.length !== 0 && my.selected.length !== my.productList.length);
	};
	my.isChecked = function() {
		return my.selected.length === my.productList.length && my.productList.length > 0;
	};
	my.toggleAll = function() {
		if (my.selected.length === my.productList.length) {
			my.selected = [];
		} else if (my.selected.length === 0 || my.selected.length > 0) {
			my.selected = my.productList.slice(0);
		}
	};
	
	getProductTypeList(0);
	
	my.pageCtrl = {
		productList: {
			pageShow: 5,
			currentPage: 1,
			pageCount: 1,
			pages: [1]
		}
	}
	
	function initPageCtrl(type) {
		my.pageCtrl[type].pageCount = Math.max(my[type] ? (my[type].length % my.pageCtrl[type].pageShow == 0) ? (my[type].length / my.pageCtrl[type].pageShow) : (Math.floor(my[type].length / my.pageCtrl[type].pageShow) + 1) : 1, 1);
		my.pageCtrl[type].currentPage = Math.max(Math.min(my.pageCtrl[type].pageCount, my.pageCtrl[type].currentPage), 1);
		my.pageCtrl[type].pages = _.range(1, my.pageCtrl[type].pageCount + 1);
	}

	my.checkShow = function(type, index) {
		var result = false;
		if (Math.floor(index / my.pageCtrl[type].pageShow) + 1 == my.pageCtrl[type].currentPage) {
			result = true;
		}
		return result;
	}

	my.changePage = function(type, page) {
		if (page == "prev") {
			if (my.pageCtrl[type].currentPage > 1) {
				my.pageCtrl[type].currentPage -= 1;
			}
		} else if (page == "next") {
			if (my.pageCtrl[type].currentPage < my.pageCtrl[type].pageCount) {
				my.pageCtrl[type].currentPage += 1;
			}
		} else {
			page = parseInt(page);
			my.pageCtrl[type].currentPage = page;
		}
	}

	my.numberShow = function (type, page) {
		var result = false;
		page = parseInt(page);
		switch (my.pageCtrl[type].currentPage) {
			case 1:
			case 2:
				if (page < 6) {
					result = true;
				}
				break;
			case my.pageCtrl[type].pageCount:
			case my.pageCtrl[type].pageCount -1:
				if (page > my.pageCtrl[type].pageCount - 5) {
					result = true;
				}
				break;
			default:
				if (page > my.pageCtrl[type].currentPage - 3 && page < my.pageCtrl[type].currentPage + 3) {
					result = true;
				}
				break;
		}
		return result;
    }
}]);