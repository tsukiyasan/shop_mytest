app.controller('pwchg_page',['$rootScope','$scope','$http','$location','CRUD',function($rootScope, $scope, $http, $location, CRUD) {
	var self = this;
	CRUD.setUrl("components/pwchg/api.php");
	
	self.funcPerm = $rootScope.funclist['pwchg'];
	
	self.submit = function(){
		
		if( self.funcPerm.U == 'true' ) 
		{
			var err = 0;
			var msg = "";
			if(!self.opw){
				//請輸入舊密碼
				msg += $translate.instant('lg_pwchg.pwchg_msg1')+"<br>";
				err++;
			}
			if(!self.pw1){
				//請輸入新密碼
				msg += $translate.instant('lg_pwchg.pwchg_msg2')+"<br>";
				err++;
			}
			if(self.pw1 != self.pw2){
				//新密碼與確認密碼不同
				msg += $translate.instant('lg_pwchg.pwchg_msg3')+"<br>";
				err++;
			}
			if(self.pw1 && self.pw1.length<6){
				//新密碼長度太短
				msg += $translate.instant('lg_pwchg.pwchg_msg4')+"<br>";
				err++;
			}
			if(err == 0){
				var parm = {
					opw: self.opw,
					npw: self.pw1,
					task: "pwchg"
				}
				CRUD.update(parm, "POST").then(function(res){
					if(res.status == 1) {
						success(msgStyle(res.msg));
					}
				});
			}else{
				error(msgStyle(msg));
			}
		}
		
		
	}
	
}]);

