app.controller('timeSelector_modal', ['$rootScope', '$scope', 'CRUD', '$location', '$translate', 'urlCtrl', '$uibModalInstance', 'items', function($rootScope, $scope, CRUD, $location, $translate, urlCtrl, $uibModalInstance, items) {
    var my = this;
	//var param = Cryptography.AES.decrypt($location.hash());
	var param = urlCtrl.deaes($location.hash());
	if (param) {
	    my.datepickerList = items.setting;
	    my.confirm = function() {
	    	var errorCount = 0;
	    	var requireField = "";
	    	angular.forEach(my.datepickerList, function(datepicker) {
	    		if (datepicker.required && !datepicker.date) {
	    			if (errorCount == 0) {
	    				requireField += datepicker.label;
	    			} else {
	    				requireField += "、" + datepicker.label;
	    			}
	    			errorCount++;
	    		}	
	    	});
	        if (errorCount == 0) {
	            $uibModalInstance.close(my.datepickerList);
	        } else {
	            error($translate.instant('lg_selectTime.selectError', {requireField: requireField}));
	        }
	    }
	    
	    my.cancel = function() {
        	$uibModalInstance.dismiss('cancel');
    	}
	}
}]);