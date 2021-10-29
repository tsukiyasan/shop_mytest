app.controller('active_list',['$rootScope','$scope','urlCtrl','$location','$route','$routeParams','CRUD', function($rootScope,$scope, urlCtrl, $location,$route,$routeParams,CRUD) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash

	if(param){
		CRUD.setUrl("components/active/api.php");
		
		my.funcPerm = $rootScope.funclist['active'];
		
		my.params = {
			page: !param.page ? 1 : param.page,
			search: !param.search ? {name: "", newsDate: ""} : param.search,
			orderby: !param.orderby ? {newsDate: "asc", pubDate: "desc"} : param.orderby
		}
		my.odrhash=urlCtrl.enaes({component:'active',p:param});
		my.list = function() {
			CRUD.list(my.params, "GET").then(function(res) {
				if(res.data.status == 1) {
					my.cnt = res.cnt;
					my.data_list = res.data.data;
				}	
			});
		};
		
		my.delete = function(id){
			
			if(my.funcPerm.D == 'true')
			{
				CRUD.del({id:id}, "GET").then(function(res) {
					if(res.status == 1) {
						$route.reload();
					}	
				});
			}
			
			
		};
		
		my.gopage = function(id) {
			var param = {
				id: id,
				listparams: my.params
			}
			urlCtrl.go("/active_page", param);
		}
		
		my.list();
	}
	
	
}])

.controller('active_page', ['$rootScope','$scope','urlCtrl','$location','$route','$routeParams','CRUD','$uibModal','$translate',function($rootScope,$scope, urlCtrl, $location,$route,$routeParams,CRUD,$uibModal,$translate) {
	var my=this;
	var param=urlCtrl.deaes($location.hash());//解碼網址hash
	my.textList = [];
	my.nameList = [];
	if(param){
		CRUD.setUrl("components/active/api.php");
		
		my.funcPerm = $rootScope.funclist['active'];
		
		my.active={};
		
		
		var listparams = !param.listparams ? {} : param.listparams;
		
		my.datepicker_opt={
			singleDatePicker: true,
			timePicker:true,
			locale: {
	            format: 'YYYY-MM-DD HH:mm'
	        }
		};
		my.detail = function() {
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.active = res.data;
					my.nameList=res.nameList;
					my.selectpro_list = res.selectpro_list;
					$rootScope.selectpro_list=my.selectpro_list;
					my.selectgift_list = res.selectgift_list;
					$rootScope.selectgift_list=my.selectgift_list;
					my.active.var01=parseFloat(my.active.var01);
					my.active.var02=parseFloat(my.active.var02);
					
					my.actRange_list = res.actRange_list;
					my.actType_list = res.actType_list;
					my.activePlans_list = res.activePlans_list;
					my.pvbvratio = res.pvbvratio;
					
					if(my.active.activePlanid == '13')
					{
						my.active.pvp = my.active.pv;
					}
					
					//console.log(my.activePlans_list);
					
				}
				
			});
		}
		
		my.actPlanChg = function() {
			my.selectpro_list = [];
			$rootScope.selectpro_list=my.selectpro_list;
			
			var params = {
				task:'getActTypeList',
				activePlanid:my.active.activePlanid
			};
			CRUD.list(params, "GET").then(function(res){
				if(res.data.status == 1)
				{
					my.actRange_list = res.data.actRange_list;
					my.actType_list = res.data.actType_list;
				}
			});
			
			
		}
		
		my.getActList = function(change) {
			
			if(my.active.activePlanid == '13')
			{
				my.active.actTypePCode = '5';
				my.active.actRangePCode = '2';
			}
			
			var params = {
				task:'getActList',
				actRange : my.active.actRangePCode,
				actType : my.active.actTypePCode,
				getPlans : change
			};
			
			CRUD.list(params, "GET").then(function(res){
				if(res.data.status == 1)
				{
					if(!change)
					{
						my.actRange_list = res.data.actRange_list;
						my.actType_list = res.data.actType_list;
					}
					my.activePlans_list = res.data.activePlans_list;
					my.pvbvratio = res.data.pvbvratio;
					
				}
			});
		};
		
		if(param.id) {
			my.actionType=1;
			my.params = {
				id: !param.id ? null : param.id
			}
			my.detail();
		} else {
			my.actionType=0;
			my.active.allpro='1';
			my.selectpro_list = [];
			$rootScope.selectpro_list=my.selectpro_list;
			my.selectgift_list = [];
			$rootScope.selectgift_list=my.selectgift_list;
			
			var dt = new Date();
			year = dt.getFullYear();
			month = dt.getMonth() + 1;
			month = (month < 10) ? '0'+month : month;
			date = dt.getDate();
			date = (date < 10) ? '0'+date : date;
			hour = dt.getHours();
			hour = (hour < 10) ? '0'+hour : hour;
			min = dt.getMinutes();
			min = (min < 10) ? '0'+min : min;
			
			my.active.sdate= year+'-'+month+'-'+date+' '+hour+':'+min;
			my.active.edate='';
			
			my.active.actRangePCode = '1';
			my.active.actTypePCode = '1';
			
			my.active.pv = '0';
			my.active.bv = '0';
			
			my.getActList();
			
		}
		
		my.deletepro = function(index,target) {
			
			if(target == 'gift')
			{
				var t=my.selectgift_list;
				t.splice(index,1);
				my.selectgift_list=t;
			}
			else
			{
				var t=my.selectpro_list;
				t.splice(index,1);
				my.selectpro_list=t;
				//console.log(my.selectpro_list);
			}
		}
		
		my.addPro = function(target,proType) {
			
			$rootScope.selectpro_list=my.selectpro_list;
			$rootScope.selectgift_list=my.selectgift_list;
			$rootScope.select_target=target;
			$rootScope.select_proType=proType;
			$rootScope.select_planType=my.activePlans_list[my.active.activePlanid].type;
			$rootScope.oriactiveid = my.active.id;
			$rootScope.oriactivesdate = my.active.sdate;
			$rootScope.oriactiveedate = my.active.edate;
			
			if($rootScope.oriactivesdate)
			{
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'AddProductModal.html',
					controller: 'Product_modal',
					backdrop: 'static',
					controllerAs: 'ctrl',
					resolve: {
						items: function () {
							return $scope.items;
						}
					}
				});
			
				modalInstance.result.then(function () {
					my.getTypeList();
				});
			}
			else
			{
				error($translate.instant('lg_active.active_sdate_empty'));
				return false;
			}
		}
		
		my.init = function() {
			CRUD.detail({task:"getBasicInfo"}, "GET").then(function(res) {
				if(res.status == 1) {
					my.textList = res.textList;
					if(!id)
					{
						angular.forEach(my.textList,function(v,k){
							my.nameList[v.code] = '';
						});
					}
				}
			});
		}
		my.init();
		
		my.submit = function() {
			
			//if(!my.active.name)
			//{
			//	error($translate.instant('lg_main.title')+$translate.instant('lg_main.empty'));
			//	return false;
			//}
			
			var nameChk = true;
			angular.forEach(my.textList,function(v,k){
				if(!my.nameList[v['code']])
				{
					nameChk = false;
				}
				my.active['name_'+v['code']] = my.nameList[v['code']];				
			});
			if(!nameChk)
			{
				error($translate.instant('lg_main.title')+$translate.instant('lg_main.empty'));
				return false;
			}
			
			if(!my.active.sdate)
			{
				error($translate.instant('lg_active.active_sdate')+$translate.instant('lg_main.empty'));
				return false;
			}
			
			selectpro = '||';
			angular.forEach($rootScope.selectpro_list,function(v,key){
				selectpro += v.id+'||';
			});
			
			my.active.selectpro = selectpro;
			
			selectgift = '||';
			angular.forEach($rootScope.selectgift_list,function(v,key){
				selectgift += v.id+'||';
			});
			
			my.active.selectgift = selectgift;
			
			if(!my.active.activePlanid)
			{
				error($translate.instant('lg_active.activePlans')+$translate.instant('lg_main.empty'));
				return false;
			}
			
			
			if(my.active.activePlanid == '3')
			{
				my.active.bv = my.active.pv * my.pvbvratio;
			}
			else if(my.active.activePlanid == '13')
			{
				my.active.pv = my.active.pvp;
				my.active.bv = my.active.pvp;
			}
			else
			{
				my.active.pv = '0';
				my.active.bv = '0';
			}
			
			
			if( (!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true') ) 
			{
				
				CRUD.update(my.active, "POST").then(function(res) {
					if(res.status == 1) {
						success(res.msg);
						my.cancel();
					}
				});
				
			}
			
			
			
		}
		
		
		
		my.cancel = function() {
			urlCtrl.go("/active_list", listparams);
		}
	}
	
	
}]).controller('Product_modal', ['$rootScope', '$scope', '$translate', 'CRUD', 'urlCtrl', '$uibModalInstance', 'items', '$filter', function($rootScope, $scope, $translate, CRUD, urlCtrl, $uibModalInstance, items, $filter) {
	var my = this;
	
	CRUD.setUrl("components/active/api.php");
	//var selecteddata = null;
	
	//my.selectlist = [];
	//my.selected = [];
	my.TypeListMArr = [];
	my.init = function(){
		
		var params = {
			task:'getTypeListM'
		};
		
		my.getTypeListM = function() {
			CRUD.list(params, "GET").then(function(res){
				if(res.data.status == 1)
				{
					my.TypeListMArr = res.data.data;
				}
			});
		};
		my.getTypeListM();
	}
	
	
	my.TypeListChg = function() {
		
		selectpro = '';
			
		if($rootScope.select_target == 'gift')
		{
			var tmp_list = $rootScope.selectgift_list;
		}
		else
		{
			var tmp_list = $rootScope.selectpro_list;
		}
		
		angular.forEach(tmp_list,function(v,key){
			if(selectpro == '')
			{
				selectpro += v.id;
			}
			else
			{
				selectpro += '||'+v.id;
			}
		});
		
		var params = {
			task:'getProList',
			typeid:my.TypeListM,
			selectpro:selectpro,
			activeid:$rootScope.oriactiveid,
			activesdate:$rootScope.oriactivesdate,
			activeedate:$rootScope.oriactiveedate,
			target:$rootScope.select_target,
			proType:$rootScope.select_proType,
			planType:$rootScope.select_planType
		};
		
		
		
		CRUD.list(params, "GET").then(function(res){
			if(res.data.status == 1)
			{
				my.ProListArr = res.data.data;
				
				my.ProListCnt = res.data.cnt;
			}
		});
	}
	
	my.addproAll = function(){
		
		var t = [];
		angular.forEach(my.ProListArr,function(v,key){
			
			if(v.has == '0')
			{
				if($rootScope.select_target == 'gift')
				{
					$rootScope.selectgift_list.push(v);
				}
				else
				{
					$rootScope.selectpro_list.push(v);
				}
			}
			else
			{
				t.push(v);
			}
		});
		
		my.ProListArr=t;
	}
	
	my.addproM = function(index) {
		
		if($rootScope.select_target == 'gift')
		{
			$rootScope.selectgift_list.push(my.ProListArr[index]);
		}
		else
		{
			$rootScope.selectpro_list.push(my.ProListArr[index]);
		}
		
		var t=my.ProListArr;
		t.splice(index,1);
		my.ProListArr=t;
		//console.log(my.selectpro_list);
		
	}
	
	my.cancel = function() {
    	$uibModalInstance.dismiss('cancel');
	}
	
	my.init();
}]);