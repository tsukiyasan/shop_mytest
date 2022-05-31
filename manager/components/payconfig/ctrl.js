app.controller('payconfig_page',['$rootScope','$scope','CRUD','$location','$route', 'urlCtrl','$translate','modalService',function($rootScope,$scope, CRUD, $location,$route, urlCtrl, $translate,modalService) {
	var my = this;
	var param = urlCtrl.deaes($location.hash());
	if(param) {
	
		CRUD.setUrl("components/payconfig/api.php");
		
		my.funcPerm = $rootScope.funclist['payconfig'];
		
		my.moneyChange = function(money){
			
			modalService.openModal("confirmModal", {
				title: $translate.instant('lg_main.batch_opt_msg'),
				message: $translate.instant('lg_payconfig.payconfig_money_change_msg')
			}, {
				backdrop: false
			}).result.then(function() {
				//true
				if(money.moneyChk == 1)
				{
					angular.forEach(my.moneyList,function(v,k){
						my.moneyList[k]['moneyChk'] = (v['code'] == money.code) ? '1' : '0';
					});
				}
			}, function() {
				//false
				money.moneyChk = (money.moneyChk == 1) ? '0':'1';
			});
		}
		
		my.submit = function() {
			
			if( my.funcPerm.U == 'true' ) 
			{
				var err=0;
				my.params = {
					task:"update",
					//name:encodeURI(my.siteinfo_dtl.name),
					dlvrPay:my.siteinfo_dtl.dlvrPay,
					bankPay:my.siteinfo_dtl.bankPay,
					creditallPay:my.siteinfo_dtl.creditallPay,
					vanallPay:my.siteinfo_dtl.vanallPay,
					allpayMerchantID:my.siteinfo_dtl.allpayMerchantID,
					allpayHashKey:my.siteinfo_dtl.allpayHashKey,
					allpayHashIV:my.siteinfo_dtl.allpayHashIV,
					merID:my.siteinfo_dtl.merID,
					MerchantID:my.siteinfo_dtl.MerchantID,
					TerminalID:my.siteinfo_dtl.TerminalID,
					Key:my.siteinfo_dtl.Key,
					selfDlvr:my.siteinfo_dtl.selfDlvr,
					homeDlvr:my.siteinfo_dtl.homeDlvr,
					homeDlvrAmt:my.siteinfo_dtl.homeDlvrAmt,
					dlvrAmt:my.siteinfo_dtl.dlvrAmt,
					tcatDlvr:my.siteinfo_dtl.tcatDlvr,
					tcatDlvrAmt:my.siteinfo_dtl.tcatDlvrAmt,
					bankName:my.siteinfo_dtl.bankName,
					bankBranch:my.siteinfo_dtl.bankBranch,
					bankId:my.siteinfo_dtl.bankId,
					bankNum:my.siteinfo_dtl.bankNum,
					//台新線上刷卡
					tspgPayCredit:my.siteinfo_dtl.tspgPayCredit,
					tspgPayMid:my.siteinfo_dtl.tspgPayMid,
					tspgPayTid:my.siteinfo_dtl.tspgPayTid,
					//中國信託虛擬帳戶
					ccbPayVATM:my.siteinfo_dtl.ccbPayVATM,
					ccbPayCode:my.siteinfo_dtl.ccbPayCode,
					ccbPayBankName:my.siteinfo_dtl.ccbPayBankName,
					ccbPayBankBranch:my.siteinfo_dtl.ccbPayBankBranch,
					ccbPayBankId:my.siteinfo_dtl.ccbPayBankId
					
				}
				
				var moneyChk = false;
				angular.forEach(my.moneyList,function(v,k){
					if(v['moneyChk'] == 1){
						moneyChk = true;
					}
				});
				if(!moneyChk)
				{
					//請至少設定一個貨幣
					error($translate.instant('lg_payconfig.payconfig_money_setting_msg'));
					err++;
				}
				else
				{
					my.params.moneyList = my.moneyList;
				}
				
				if(my.params.creditallPay==1 || my.params.vanallPay==1){
					if(!my.params.allpayHashKey || !my.params.allpayHashIV || !my.params.allpayMerchantID){
						//請填寫歐付寶相關設定
						error($translate.instant('lg_payconfig.payconfig_please_allPay'));
						err++;
					}
				}
				if(!my.params.bankName || !my.params.bankBranch || !my.params.bankId || !my.params.bankNum){
					//請填寫銀行相關設定
					error($translate.instant('lg_payconfig.payconfig_please_bank'));
					err++;
				}
				
				//台新線上刷卡
				if(my.params.tspgPayCredit==1){
					if(!my.params.tspgPayMid || !my.params.tspgPayTid ){
						//請填寫台新線上刷卡相關設定
						error($translate.instant('lg_payconfig.payconfig_please_tspg'));
						err++;
					}
				}
				
				//中國信託虛擬帳戶
				if(my.params.ccbPayVATM==1){
					if(!my.params.ccbPayCode){
						//請填寫中國信託虛擬帳戶相關設定
						error($translate.instant('lg_payconfig.payconfig_please_ccbPayVATM'));
						err++;
					}
				}
				
				if(err==0){
					CRUD.update(my.params, "POST").then(function(res) {
						if(res.status == 1) {
							success(res.msg);
							$route.reload();
						}
					});
				}
			}
			
		}
			
		my.detail = function() {
			my.params = {
				task:'detail'
			};
			CRUD.detail(my.params, "GET").then(function(res) {
				if(res.status == 1) {
					my.siteinfo_dtl=res.data;
					my.moneyList=res.moneyList;
					my.lg_list = res.lg_list;
				}
			});
		}
		my.detail();


		my.lg_reset = function(){
			my.lg_edit_status = '0';
			my.lg = [];
		}

		my.addlg = function(){
			$err2 = 0;
			console.log(my.lg);
			params = {
				task:'add_lg',
				company:my.lg.company,
				main_dlvr:my.lg.main_dlvr,
				outlying_dlvr:my.lg.outlying_dlvr,
				f_main_dlvr:my.lg.f_main_dlvr,
				f_outlying_dlvr:my.lg.f_outlying_dlvr,
				f_outlying_dlvr_basic:my.lg.f_outlying_dlvr_basic,
				main_fst:my.lg.main_fst,
				outlying_fst:my.lg.outlying_fst,
				f_main_fst:my.lg.f_main_fst,
				f_outlying_fst:my.lg.f_outlying_fst,
				having_outlying:my.lg.having_outlying
			}
			if(!my.lg.company || my.lg.company == null){
				$err2++;
				error('請填寫物流公司名稱');
			}
			if(!my.lg.main_dlvr || !my.lg.outlying_dlvr || !my.lg.f_main_dlvr || !my.lg.f_main_dlvr_basic || !my.lg.f_outlying_dlvr){
				$err2++;
				error('請填寫運費相關設定');
			}
			if(!my.lg.main_fst || !my.lg.outlying_fst || !my.lg.f_main_fst || !my.lg.f_outlying_fst){
				$err2++;
				error('請填寫免運門檻相關');
			}

			CRUD.update(params, "POST").then(function(res) {
				if(res.status == 1) {
					$route.reload();
					$('.closing_btn').click();
				}
			});
			
		}

		my.get_lg = function(id){
			console.log(id);
			params = {
				task : 'get_lg',
				id : $gid
			}
			CRUD.detail(params, "POST").then(function(res) {
				if(res.status == 1) {
					$route.reload();
					$('.closing_btn').click();
				}
			});
		}

		my.lg_del = function(id){
			console.log(id);
			if (confirm("確定要刪除?")) {
				params = {
					task:'lg_delete',
					id:id
				};
				CRUD.update(params, "POST").then(function(res) {
					if(res.status == 1) {
						$route.reload();
						success('已更新');
					}
				});
				
			 }
		}

		my.lg_edit_pre = function(id){
			params = {
				task: 'lg_get',
				id:id
			}
			CRUD.update(params, "POST").then(function(res) {
				if(res.status == 1) {
					my.lg = res.data;
					my.lg_edit_status = '1';
				}
			});
		}

		my.lg_edit = function(){
			params = {
				task:'lg_edit',
				id:my.lg.id,
				company:my.lg.company,
				main_dlvr:my.lg.main_dlvr,
				outlying_dlvr:my.lg.outlying_dlvr,
				f_main_dlvr:my.lg.f_main_dlvr,
				f_outlying_dlvr:my.lg.f_outlying_dlvr,
				f_outlying_dlvr_basic:my.lg.f_outlying_dlvr_basic,
				main_fst:my.lg.main_fst,
				outlying_fst:my.lg.outlying_fst,
				f_main_fst:my.lg.f_main_fst,
				f_outlying_fst:my.lg.f_outlying_fst,
				having_outlying:my.lg.having_outlying
			};
			CRUD.detail(params, "POST").then(function(res) {
				console.log(res);
				if(res.status == 1) {
					$route.reload();
					success('已更新');
				}else if(res.status == '0'){

					console.log('err');
					error('物流重複');
				}
			});
		}

	}
}]);

