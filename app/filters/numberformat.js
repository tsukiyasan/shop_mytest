angular.module('goodarch2uApp').filter('formatnumber', function () {
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
}).filter('range', function() {
  return function(input, total) {
    if(!total)total=1;  
    total = parseInt(total);

    for (var i=1; i<=total; i++) {
      input.push(i);
    }
    
    return input;
  };
});