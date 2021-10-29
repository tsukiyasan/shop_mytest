app.controller('indexset_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.textList = [];
	my.textList1 = false;
	my.textList2 = false;
	my.textList3 = false;
	my.textList4 = false;
	my.nameList = [];
	if(param){
		//api位置
		CRUD.setUrl("components/indexset/api.php");
		
		my.funcPerm = $rootScope.funclist['siteinfo'];
		
		my.indexset_dtl=[];
		my.index=3;
		
		angular.element(document.querySelector('#media_date')).datepicker({
			language: "zh-TW",
			todayBtn: "linked",
		    clearBtn: true,
			format: 'yyyy-mm-dd'
		});
				
	    var listparams = !param.listparams ? {} : param.listparams;
		my.detail = function() {
			CRUD.detail({}, "POST").then(function(res){
				if(res.status == 1) {
					my.indexset_dtl = res.data;
					my.nameList=res.nameList;
					angular.forEach(my.indexset_dtl.adv,function(v,k){
						my.set_adv_cnt(k);
					});
				}
			});
		}
		my.detail();
		
		my.init = function() {
			CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
				if(res.status == 1) {
					my.textList = res.textList;
					angular.forEach(my.textList,function(v,k){
						if(v['id'] == '1')
						{
							my.textList1 = true;
						}
						if(v['id'] == '2')
						{
							my.textList2 = true;
						}
						if(v['id'] == '3')
						{
							my.textList3 = true;
						}
						if(v['id'] == '4')
						{
							my.textList4 = true;
						}
					});
				}
			});
		}
		my.init();
		
		my.advs=[];
		my.dataarr=[];
		
		my.advsimg = [];
		$scope.previewImage = [];
		$scope.previewImage1 = [];
		$scope.previewImage2 = [];
		$scope.previewImage3 = [];
		$scope.previewImage4 = [];
		
		for(var i=0;i<my.index;i++){
			my.advs[i]=[];
		}
		
		my.set_adv_cnt=function(key){
			var tmpadvs=[];
			if(my.indexset_dtl.advcnt[key]){
				for(var i=1;i<=my.indexset_dtl.advcnt[key];i++){
					tmpadvs.push(i);
				}
			}
			my.dataarr[key]=tmpadvs;
		};
		
		my.submit = function() {
			
			angular.forEach(my.textList,function(v,k){
				my.indexset_dtl['name_'+v['code']] = my.nameList[v['code']];
			});
			
			if( my.funcPerm.U == 'true' ) 
			{
				CRUD.update({task:'update',dataarr:my.indexset_dtl,img:$scope.previewImage,img1:$scope.previewImage1,img2:$scope.previewImage2,img3:$scope.previewImage3,img4:$scope.previewImage4}, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						
					}
				});
			}
			
		}
		
	}
	
}]);

