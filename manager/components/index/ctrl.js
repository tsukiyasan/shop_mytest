app.controller('index_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','urlCtrl','$filter',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,urlCtrl,$filter) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	if(param){
		//api位置
		CRUD.setUrl("components/index/api.php");
		
		my.params = {chartType:'chartjs'};
		my.detail = function() {
			CRUD.detail(my.params, "POST").then(function(res){
				if(res.status == 1) {
					my.index_dtl = res.data;
				}
			});
		}
		my.detail();
		
		
		my.amtTypeChg = function(){
			my.params.amtType=my.index_dtl.amt.type;
			my.detail();
		};
		my.viewTypeChg = function(){
			my.params.viewType=my.index_dtl.view.type;
			my.detail();
		};
		my.proviewTypeChg = function(){
			my.params.proviewType=my.index_dtl.proview.type;
			my.detail();
		};
		my.procntTypeChg = function(){
			my.params.procntType=my.index_dtl.procnt.type;
			my.detail();
		};
		my.orderTypeChg = function(){
			my.params.orderType=my.index_dtl.order.type;
			my.detail();
		};
		
		my.options = {
			scales: {
				yAxes: [{
		            display: true,
		            ticks: {
		                beginAtZero: true
		            }
		        }]
			}
		};
		
		my.go=function(path,hash){
			urlCtrl.go(path,hash);
		}
	}
	
}]);

