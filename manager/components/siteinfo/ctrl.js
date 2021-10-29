app.controller('siteinfo_page',['$rootScope','$scope','CRUD','$location','$route', 'urlCtrl','$translate',function($rootScope,$scope, CRUD, $location,$route, urlCtrl,$translate) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	my.textList = [];
	if(param) {
	
		CRUD.setUrl("components/siteinfo/api.php");
		
		my.funcPerm = $rootScope.funclist['siteinfo'];
		
		my.moneyChange = function(money){
			if(money.moneyChk == 1)
			{
				angular.forEach(my.moneyList,function(v,k){
					my.moneyList[k]['moneyChk'] = (v['code'] == money.code) ? '1' : '0';
				});
			}
		}
		
		my.submit = function() {
			
			if( my.funcPerm.U == 'true' ) 
			{
				
				var err=0;
				
				if(my.textList.length == 0)
				{
					if(!my.siteinfo_dtl.name){
						//請填名稱
						error($translate.instant('lg_siteinfo.siteinfo_write_name'));
						err++;
					}
				}
				else
				{
					angular.forEach(my.textList,function(v,k){
						if(!my.siteinfo_dtl['name_'+v['code']]){
							//請填名稱
							error($translate.instant('lg_siteinfo.siteinfo_write_name'));
							err++;
						}
					});
				}
				
				if(!my.siteinfo_dtl.email){
					//請填寫客服信箱
					error($translate.instant('lg_siteinfo.siteinfo_write_email'));
					err++;
				}
				if(!my.siteinfo_dtl.pvbvratio){
					//請填寫PVBV換算比例
					error($translate.instant('lg_siteinfo.siteinfo_write_PVBV'));
					err++;
				}
				if(!my.siteinfo_dtl.bouns1){
					//請填寫紅利設定
					error($translate.instant('lg_siteinfo.siteinfo_write_bouns'));
					err++;
				}
				if(!my.siteinfo_dtl.bouns2){
					//請填寫紅利設定
					error($translate.instant('lg_siteinfo.siteinfo_write_bouns'));
					err++;
				}
				/*
				if(!my.siteinfo_dtl.coin_to){
					error("請填寫購物金設定");
					err++;
				}
				if(!my.siteinfo_dtl.coin_take){
					error("請填寫購物金設定");
					err++;
				}
				if(!my.siteinfo_dtl.invoice){
					error("請填寫發票捐獻單位");
					err++;
				}
				*/
				
				var textChk = false;
				angular.forEach(my.textList,function(v,k){
					if(v['textChk'] == 1){
						textChk = true;
					}
				});
				if(!textChk)
				{
					//請至少設定一個語言
					error($translate.instant('lg_siteinfo.siteinfo_text_setting_msg'));
					err++;
				}
				else
				{
					my.siteinfo_dtl.textList = my.textList;
				}
				
				if(err==0){
					CRUD.update(my.siteinfo_dtl, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				}
			}
		}
			
		my.detail = function() {
			
			if( my.funcPerm.R == 'true' ) 
			{
				my.params = {};
				CRUD.detail(my.params, "GET").then(function(res) {
					if(res.status == 1) {
						my.siteinfo_dtl=res.data;
						my.textList=res.textList;
					}
				});
			}
		
		}
		my.detail();
	}
}]);

