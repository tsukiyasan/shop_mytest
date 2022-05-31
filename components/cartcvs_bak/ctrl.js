app.controller('cartcvs_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$routeParams', '$translate', 'CRUD', '$filter', '$timeout', 'sessionCtrl', function ($rootScope, $scope, $http, $location, $route, $routeParams, $translate, CRUD, $filter, $timeout, sessionCtrl) {
	var my = this;

	my.lang = sessionCtrl.get('_lang');
	my.currency = sessionCtrl.get("_currency");
	my.m_discount = 0;
	CRUD.setUrl("components/cartcvs/api.php");

	my.get_addrCode = function () {
		var turl = CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({ task: "get_addrCode" }, "GET").then(function (res) {
			if (res.status == 1) {
				my.city = res.city[2];
				my.canton = res.canton;
			}
		});
		CRUD.setUrl(turl);
	};
	my.get_addrCode();

	
	my.get_year = function () {
		var turl = CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({ task: "get_year" }, "GET").then(function (res) {
			if (res.status == 1) {
				my.year_list = res.year_list;
			}
		});
		CRUD.setUrl(turl);
	};
	my.get_year();


	my.get_state = function () {
		var turl = CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({ task: "get_state" }, "GET").then(function (res) {
			if (res.status == 1) {
				my.state_list = res.state_list;
			}
		});
		CRUD.setUrl(turl);
	};
	my.get_state();

	my.get_invoice = function () {
		var turl = CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({ task: "get_invoice" }, "GET").then(function (res) {
			if (res.status == 1) {
				my.invoice = res.invoice;
			}

		});
		CRUD.setUrl(turl);
	};
	my.get_invoice();
	my.activeUsePwCnt = 0;
	my.adticeUsePwText = [];
	my.init_list = function (activeChk) {
		if (!activeChk) {
			activeChk = false;
		}
		CRUD.list({ task: 'list', activeChk: activeChk }, 'GET').then(function (res) {
			if (res.status == 1) {
				my.info = res;
				console.log(res.userinfo);
				// if(res.userinfo.state.id == '5'){
				// 	my.tax_fee = res.ntax_fee;
				// }else{
				// 	my.tax_fee = 0;
				// }
				my.tax_fee = res.ntax_fee;
				my.activeBundleCart = res.activeBundleCart;
				$scope.dlvrAmt = res.dlvrAmt;
				my.mode = res.mode;
				my.same_member_info = false;
				my.use_p = res.use_p;
				my.use_points = res.use_points;
				my.cb_use_p = res.cb_use_p;
				my.cb_use_points = res.cb_use_points;
				my.user_res_info = res.userinfo;
				my.m_discount_rate = res.m_discount_rate;
				if (my.m_discount_rate > 0) {
					// my.m_discount = Math.round(res.pv * my.m_discount_rate * 100)/100;
					my.m_discount = res.m_dis;
				}
				$scope.dateOptions = {
					dateDisabled: disabled,
					formatYear: 'yy',
					minDate: new Date(),
					startingDay: 1
				};

				my.pv = res.pv;
				my.m_dis = res.m_dis;
				my.bv = res.bv;
				my.bonus = res.bouns;
				// console.log('-------------');
				// console.log(my);
				// console.log('-------------');
				my.newactiveChk = false;
				angular.forEach(my.info.active_list, function (v, k) {
					if (v.id == '75' || v.id == '76') {
						my.newactiveChk = true;
					}
				});

				my.activeUsePwCnt = 0;
				my.adticeUsePwText = [];
				angular.forEach(res.active_list, function (v, k) {
					if (v.act.passwordChk == 1) {
						my.activeUsePwCnt++;
						my.adticeUsePwText.push(v.act.passwordText);
					}
				});
				//console.log(my.pv);

				/*
				angular.forEach(my.info.data.list,function(v,k){
					my.pv+=parseInt(v.pv * v.num);
					my.bv+=parseInt(v.bv * v.num);
					my.bonus+=parseInt(v.bonus * v.num);
				});
				*/
				my.bonusArr = res.bonusArr;

				my.activeChk = (res.activeChk == 'true') ? true : false;

			} else {

				if (res.status == 2) {
					error(res.msg);
					$location.path("cart_list");
				}
				else {
					$location.path("index_page");
				}
			}
		});
	}

	my.init_list();

	my.cartcvs = {};

	// my.cartcvs.bill_address=localStorage.getItem('uaddress');
	// my.cartcvs.bill_city=localStorage.getItem('ucity');
	// my.cartcvs.bill_zip=localStorage.getItem('uzip');
	// var ustate = JSON.parse(localStorage.getItem('ustate'));
	// my.cartcvs.bill_state=ustate;

	console.log(my.cartcvs);

	my.cartcvs.invoiceType = '0';
	my.activeChange = function () {
		my.init_list(my.activeChk);
	}



	$scope.$watch('ctrl.same_member_info', function (value) {

		if (my.info) {
			if (value) {
				my.cartcvs.name = my.info.member.name;
				my.cartcvs.mobile = my.info.member.mobile;
				my.cartcvs.address = my.info.member.address;
				my.cartcvs.city = my.info.member.city;
				my.cartcvs.canton = my.info.member.canton;
				my.cartcvs.canton = my.info.member.canton;
			} else {
				my.cartcvs.name = '';
				my.cartcvs.mobile = '';
				my.cartcvs.address = '';
				my.cartcvs.city = '';
				my.cartcvs.canton = '';
			}
		}
	});

	my.cart_calc = function (value) {
		var err = 0;

		var city = my.cartcvs.city ? my.cartcvs.city.id : '';
		var canton = my.cartcvs.canton ? my.cartcvs.canton.id : '';
		var invoice = my.cartcvs.invoice ? my.cartcvs.invoice.id : '';


		if (value != 'free') {// if(!my.cartcvs.email && !my.info.member.email){
			// 	err++;
			// 	error($translate.instant('lg_cartcvs.please_email'));
			// }


			// if(my.info.take_type!=2){
			// 	if(!city){
			// 		error($translate.instant('lg_cartcvs.please_live_city'));
			// 		err++;
			// 	}
			// 	if(!canton){
			// 		error($translate.instant('lg_cartcvs.please_live_area'));
			// 		err++;
			// 	}
			// 	if(!my.cartcvs.address){
			// 		error($translate.instant('lg_cartcvs.please_addr'));
			// 		err++;
			// 	}
			// }
			my.cartcvs.cityCode = city;
			// my.cartcvs.cantonCode=canton;

			// if(!my.cartcvs.invoiceType){
			// 	err++;
			// 	error($translate.instant('lg_cartcvs.please_invoice_type'));
			// }
			// if(!invoice && my.cartcvs.invoiceType==1){
			// 	err++;
			// 	error($translate.instant('lg_cartcvs.please_invoice_unit'));
			// }

			/*
			if(!my.cartcvs.invoiceTitle && my.cartcvs.invoiceType==0){
				err++;
				error("請填寫收據抬頭");
			}
			
			if(!my.cartcvs.invoiceSN && my.cartcvs.invoiceType==0){
				err++;
				error("請填寫統一編號");
			}
			*/

			if (!my.cartcvs.creditcard_name) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_name_required'));
			}

			if (!my.cartcvs.creditcard_l_name) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_l_name_required'));
			}

			if (!my.cartcvs.creditcard_number) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_number_required'));
			}

			if (!my.cartcvs.creditcard_month) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_month_required'));
			}

			if (!my.cartcvs.creditcard_year) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_year_required'));
			}

			if (!my.cartcvs.creditcard_cvv) {
				err++;
				error($translate.instant('lg_cartcvs.creditcard_cvv_required'));
			}

			if (!my.cartcvs.bill_address) {
				err++;
				error($translate.instant('lg_cartcvs.bill_address_required'));
			}

			if (!my.cartcvs.bill_city) {
				err++;
				error($translate.instant('lg_cartcvs.bill_city_required'));
			}

			if (!my.cartcvs.bill_state) {
				err++;
				error($translate.instant('lg_cartcvs.bill_state_required'));
			}

			if (!my.cartcvs.bill_zip) {
				err++;
				error($translate.instant('lg_cartcvs.bill_zip_required'));
			}



			if (my.activeUsePwCnt > 0) {
				if (!my.cartcvs.notes) {
					error($translate.instant('lg_cartcvs.please_PwText'));
					err++;
				} else {
					angular.forEach(my.adticeUsePwText, function (v) {
						if (v) {
							if (my.cartcvs.notes.indexOf(v) == -1) {
								error($translate.instant('lg_cartcvs.please_correct_PwText'));
								err++;
							}
						}
					});
				}
			}

			my.cartcvs.invoice = invoice;
		}

		if (value == 'free') {
			err = 0;
		}

		if (err == 0) {
			my.cartcvs.task = "order_submit";
			CRUD.update(my.cartcvs, "POST").then(function (res) {
				oid = res.oid;
				my.orderseq_bak = res.data;
				console.log(my.orderseq_bak);
				console.log(res);
				if (res.status == 1) {
					$rootScope.cartCnt = 0;
					my.orderseq = res.data;
					$('#myModal_ORDER').modal('show');
				}
				else if (res.status == 'vatm') {
					$rootScope.cartCnt = 0;
					my.orderseq = res.data;
					my.ATMSTR = res.data2;
					my.deadLineDT = res.data3;
					$('#myModal_ORDER').modal('show');
				}
				else if (res.status == 'aio' || res.status == 'tspg') {
					CRUD.detail({ task: "order_submit_auth", id: res.oid }, "POST").then(function (res) {
						if (res.status == 1) {
							// alert(res.c_status.err);
							my.orderseq = my.orderseq_bak;
							$('#myModal_ORDER2').modal('show');
							setInterval(function () {
								location.href = '/member_page/orderdtl/' + oid;
							}, 3000);
							// console.log('/member_page/orderdtl/'+oid);
						} else if (res.status == '9') {
							// alert(res.msg);
							my.orderseq = my.orderseq_bak;
							$('#myModal_ORDER2').modal('show');
							setInterval(function () {
								location.href = '/member_page/orderdtl/' + oid;
							}, 3000);
						} else if (res.status == 'pay_failed'){
							my.cartcvs.old_orderNum = res.orderNum;
							error($translate.instant('lg_cartcvs.repay'));
						} else {
							error($translate.instant('lg_cartcvs.repay'));
						}
						
						

					});
					// var form = document.createElement("form");
					// form.method = "POST";
					// var element = document.createElement("input"); 

					// 	if(res.status == 'tspg')
					// 	{
					// 		element.value='order_submit_auth';
					// 	}
					// 	else
					// 	{
					// 		element.value='order_submit2';
					// 	}

					//     element.name='task';
					//     form.appendChild(element); 

					// var element = document.createElement("input"); 
					// 	element.value=res.oid;
					//     element.name='id';
					//     form.appendChild(element);     
					// form.action = "components/cartcvs/api.php";  
					// document.body.appendChild(form);

					// form.submit();


				}
			});

		}
	};

	my.copyStr = function (id) {
		var TextRange = document.createRange();
		TextRange.selectNode(document.getElementById(id));
		sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(TextRange);
		document.execCommand("copy");
	};

	my.order_clear = function (path) {
		$('#myModal_ORDER').modal('hide');
		$timeout(function () {
			$location.path(path);
		}, 200);
	}
	$scope.popup2 = {
		opened: false
	};
	$scope.open2 = function () {
		$scope.popup2.opened = true;
	};

	$scope.dateOptions = {
		dateDisabled: disabled,
		formatYear: 'yy',
		minDate: new Date(),
		startingDay: 1
	};
	function disabled(data) {
		var date = data.date,
			mode = data.mode;

		var m = (date.getMonth() + 1);
		if (m < 10) m = '0' + m;
		var d = date.getDate();
		if (d < 10) d = '0' + d;
		var to = date.getFullYear() + "-" + m + "-" + d;


		//return mode === 'day' && ((date.getDay() === 0 && my.info.enableDate.indexOf(to)==-1) || (date.getDay() === 1 && my.info.enableDate.indexOf(to)==-1)) || (my.info.disableDate.indexOf(to)>-1);
		return mode === 'day' && (my.info.disableDate.indexOf(to) > -1);
	}


}]);

