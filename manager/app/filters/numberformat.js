angular.module('managerApp').filter('formatnumber', function () {
	try{
        return function (input) {
			try{
				if(input>0){
					return input.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				}else{
					return input;
				}
			}catch(e){}	
        };
	}catch(e){}	
});