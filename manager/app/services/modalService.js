angular.module('managerApp')
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