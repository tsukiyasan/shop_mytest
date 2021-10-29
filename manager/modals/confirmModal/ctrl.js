app.controller('confirmModal_modal', ['$rootScope', '$scope', 'CRUD', '$location', '$translate', '$uibModalInstance', 'items', function($rootScope, $scope, CRUD, $location, $translate, $uibModalInstance, items) {
    var my = this;
	my.title = items.title;
	my.message = items.message;
	
	my.confirm = function() {
		$uibModalInstance.close(true);
	}
	
	my.cancel = function() {
		$uibModalInstance.dismiss('cancel');
		//$uibModalInstance.close(false);
	}
	
}]);