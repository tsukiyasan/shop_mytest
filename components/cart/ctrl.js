app.controller('cart_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','sessionCtrl',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,sessionCtrl) {
	var my=this;
	
	my.lang = sessionCtrl.get('_lang');
	my.currency = sessionCtrl.get("_currency");
	
	my.get_addrCode=function(){	
		var turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({task: "get_addrCode"}, "GET").then(function(res){
			if(res.status == 1) {
				my.city = res.city;
			}
		});
		CRUD.setUrl(turl);
	};
	my.get_addrCode();

	CRUD.setUrl("components/cart/api.php");
	var productFormat=[];
	var addproArr=[];
	var addproList=[];
	var freeproList=[];
	my.pairid = 0;
	my.pairList = [];
	my.pairArr = [];
	my.index=1;
	my.use_points_val = 0;
	my.cb_use_points_val = 0;
	my.cartcvs = {};
	my.cartcvs.dt = 'US-PM';
	console.log(localStorage.getItem('udt'));
	if(localStorage.getItem('udt') != 'null' || localStorage.getItem('udt') != NULL || localStorage.getItem('udt') != '' || localStorage.getItem('udt') != undefined){
		my.cartcvs.dt=localStorage.getItem('udt');
	}else{
		my.cartcvs.dt = 'US-PM';
	}
	
	my.cartcvs.name=localStorage.getItem('uname');
	my.cartcvs.mobile=localStorage.getItem('umobile');
	my.cartcvs.email=localStorage.getItem('uemail');
	my.cartcvs.address=localStorage.getItem('uaddress');
	my.cartcvs.city=localStorage.getItem('ucity');
	my.cartcvs.zip=localStorage.getItem('uzip');
	if(localStorage.getItem('unotes') == 'null'){
		var vnotes = '';
	}else{
		var vnotes = localStorage.getItem('unotes');
	}
	my.cartcvs.notes=vnotes;
	var ustate = JSON.parse(localStorage.getItem('ustate'));
	my.cartcvs.state=ustate;
	
	// my.check_use_points = 0;
	$rootScope.getlist=function(){
		CRUD.setUrl("components/cart/api.php");
		CRUD.list({task:'list'},'GET').then(function(res){
			my.info = res;
			my.same_member_info=false;
			my.pairid = 0;
			my.pairList = [];
			my.pairArr = [];
			my.activeNote=res.activeNote;
			if(res.status == 1) {
				$rootScope.data_list = res.data.list;
				$rootScope.activeBundleCart = (res.activeBundleCart && res.activeBundleCart != "null") ? res.activeBundleCart : {};
				$rootScope.actProArr = res.actProArr;
				
				/*$rootScope.event_data_list = res.eventproArr;
				$rootScope.event_list = res.event_list;
				console.log($rootScope.event_data_list);
				console.log($rootScope.event_list);*/
				
				$rootScope.activeExtraList = (res.activeExtraList && res.activeExtraList != "null") ? res.activeExtraList : {};
				if(res.pairList)
				{
					angular.forEach(res.pairList,function(v,k){
						my.pairList.push(v);
					});
					
				}
				
				if(res.pairArr)
				{
					angular.forEach(res.pairArr,function(v,k){
						my.pairArr.push(v);
					});
				}
				
				
				if (!isNaN(res.btotal)) {
					$rootScope.btotal = res.btotal;
				} else {
					$rootScope.btotal = 0;
				}

				$rootScope.total = res.total;
				$rootScope.ntotal = res.ntotal;
				$rootScope.tax_fee_rate = res.tax_fee;
				$rootScope.tax_fee = Math.round(res.ntotal * (res.tax_fee * 100)) / 100;
				$rootScope.tax_fee_save = Math.round(res.ntotal * (res.tax_fee * 100)) / 100;
				$rootScope.allamt = res.amt;
				$rootScope.active_list = res.active_list;
				$rootScope.activePro_arr = res.activePro_arr;
				$rootScope.activeUsedPro_arr = res.activeUsedPro_arr;
				$rootScope.activePro_actName_arr = res.activePro_actName_arr;
				$rootScope.discount = res.discount;
				//$scope.usecoin = parseInt(res.usecoin);
				$rootScope.cart_use_coin = parseInt(res.usecoin);
				
				$rootScope.now_points = res.now_points;
				my.now_points = res.now_points;
                my.max_cb_points = res.cb_points;
				my.om = res.om;
				console.log(res.cb_points);


				var $max_ocb = ((Math.floor($rootScope.totalAmt * 1000 ) / 1000) * 0.1).toFixed(2);
				if(isNaN($max_ocb)){
					$max_ocb = 0;
				}


				
				$max_cp = my.max_cb_points;
				console.log('--------------');
				console.log($max_cp);
				console.log($max_ocb);
				console.log($max_cp > $max_ocb);
				console.log($max_cp < $max_ocb);
				console.log('--------------');
				console.log($rootScope.totalAmt);

				if(parseFloat($max_cp) > parseFloat($max_ocb)){
					my.cb_points = $max_ocb;
				}else{
					my.cb_points = $max_cp;
				}
				console.log(my.cb_points);

				
				$rootScope.dlvrAmt=0;
				my.mode=res.mode;
				my.bonusArr=res.bonusArr;
				
				addproArr=res.addPro;
				my.dlvrfeeStr = res.dlvrfeeStr;
				
				$rootScope.addAmtAction = [];
				angular.forEach($rootScope.active_list,function(v,k){
					if( typeof v.addAmtPro != 'undefined' &&  v.addAmtPro.length > 0)
					{
						$rootScope.addAmtAction.push({'actid':v.id,'name':v.name,'proarr':v.addAmtPro,'addProCnt':v.addProCnt});
					}
					
					var tmp_addProCnt = v.addProCnt;
					
					angular.forEach(v.addAmtPro,function(v,k){
						if(!productFormat[v.id])productFormat[v.id]=[];
						productFormat[v.id]['f1']=v.format.format1[0];
						productFormat[v.id]['f2']=v.format.format2[v.format.format1[0]];
						v.addProCnt = tmp_addProCnt;
						addproList[v.id] = v;
						
					});
					angular.forEach(v.freePro,function(v,k){
						if(!productFormat[v.id])productFormat[v.id]=[];
						productFormat[v.id]['f1']=v.format.format1[0];
						productFormat[v.id]['f2']=v.format.format2[v.format.format1[0]];
						
						freeproList[v.id] = v;
						
					});
				});
				//angular.forEach(res.activeExtraGiftProduct, function(value, key) {
				//	if(!productFormat[value.id])productFormat[value.id]=[];
				//	productFormat[value.id]['f1']=value.format.format1[0];
				//	productFormat[value.id]['f2']=value.format.format2[value.format.format1[0]];
				//	
				//	freeproList[value.id] = value;
				//});
				
				angular.forEach(res.activeBundleGiftProduct, function(value, key) {
					if(!productFormat[value.id])productFormat[value.id]=[];
					productFormat[value.id]['f1']= (value.format) ? value.format.format1[0] : "";
					productFormat[value.id]['f2']= (value.format) ? value.format.format2[value.format.format1[0]] : "";
					
					freeproList[value.id] = value;
				});
				
				
				if(my.mode == 'cart')
				{
					my.initAddPro();
				}
				
				//付款方式與取貨方式
				$rootScope.pay_type_list = res.pay_type;
				$rootScope.take_type_list = res.take_type;
				my.dlvrfeeShowStr = res.dlvrfeeShowStr;
				my.recal_tax();
			}else{
				error(res.msg);
				$location.path("index_page");
			}
		});
	};
	$rootScope.getlist();
	my.initAddPro=function(){
		angular.forEach(addproArr,function(v,k){
			if(!my.checkboxG)my.checkboxG=[];
			my.checkboxG[k]=true;
		});
	};
	
	CRUD.list({task:'showAddProlist'},'GET').then(function(res){
		if(res.status == 1) {
			my.addpro_list = res.data.list;
			
		}else{
			//$location.path("index_page");
			error(res.msg);
		}
	});

	$scope.$watch('ctrl.same_member_info', function(value){
		
		if(my.info ){
			console.log(my.info);
			if(value){
				my.cartcvs.name=my.info.member.name;
				my.cartcvs.mobile=my.info.member.mobile;
				my.cartcvs.address=my.info.member.address;
				my.cartcvs.state=my.info.member.city;
				// my.cartcvs.canton=my.info.member.canton;
			}
			// else{
			// 	my.cartcvs.name='';
			// 	my.cartcvs.mobile='';
			// 	my.cartcvs.address='';
			// 	my.cartcvs.city='';
			// 	my.cartcvs.canton='';
			// }
		}
	});
	
	$scope.$watch('ctrl.cartcvs.state', function(value){
		if(value.id == '5' && value.state_s == 'CA'){
			$rootScope.tax_fee = $rootScope.tax_fee_save;
		}else{
			$rootScope.tax_fee = 0;
		}
	});
	
	// $scope.$watch('allamt', function(value){
	// 	$scope.cart_use_coin=$scope.cart_use_coin?$scope.cart_use_coin:0;
	// 	$rootScope.totalAmt=value*1-$scope.cart_use_coin*1;
	// });

	$scope.$watch('allamt', function (value) {  //這邊計算總額
		$scope.cart_use_coin = $scope.cart_use_coin ? $scope.cart_use_coin : 0;
		$rootScope.totalAmt = value * 1 - $scope.cart_use_coin * 1;
		$rootScope.memAmt = $rootScope.totalAmt;
		console.log($rootScope.totalAmt);
		var $max_ocb = (Math.floor(($rootScope.totalAmt * 1000) / 1000) * 0.1).toFixed(2);
		
		if(isNaN($max_ocb)){
			$max_ocb = 0;
		}

		

		$max_cp = my.max_cb_points;

		if(parseFloat($max_cp) > parseFloat($max_ocb)){
			my.cb_points = $max_ocb;
		}else{
			my.cb_points = $max_cp;
 		}

	});

	$scope.$watch('cart_use_coin', function(value){
		value=value?value:0;
		$rootScope.totalAmt=$scope.allamt*1-value*1;
		
	});

	my.use_all_points = function() {
        var use_p = 0;
		var $p = my.cb_use_points_val;
        var ta = $rootScope.totalAmt * 1 - $p * 1;
        var np = my.now_points;
        var ba = $rootScope.batotal;
        if (ta >= np) {
            use_p = np;
        }
        if (np > ta) {
            use_p = ta;
        }
        // use_p += ba;
        $('#use_points_val').val(use_p);
        my.use_points_val = use_p;
		my.recal_tax();
    }

	my.check_points_val = function() {
        var $val = my.use_points_val;
        var use_p = 0;
        var $p = my.cb_use_points_val;
        var ta = $rootScope.totalAmt * 1 - $p * 1;
        var np = my.now_points;
        var ba = $rootScope.batotal;

        if (ta >= np) {
            use_p = np;
        }
        if (np > ta) {
            use_p = ta;
        }

        // use_p += ba;

        var $right_val = $val;
        if ($val < 0) {
            $right_val = 0;
        }
        $right_val = $right_val;

        if ($right_val > use_p) {
            $right_val = use_p;
        }

        if (isNaN($right_val) || $right_val == '') {
            $right_val = 0;
        }

        $('#use_points_val').val($right_val);
        my.use_points_val = $right_val;
		my.recal_tax();
    }

	my.check_use_points_chg = function () {
		value = $('#check_use_points').prop('checked');
		$use_p = 0;
		if (value == true) {
			$('#show_points').show();
			$('#o_total td span').css('color','black');
			$('#o_total td span').css('font-size','1.0em');
			$('#o_total td span').css('font-weight','normal');
			// my.check_use_points = 1;
			my.use_points_val = 0;
		} else {
			$('#show_points').hide();
			$('#o_total td span').css('color','#eb4023');
			$('#o_total td span').css('font-size','1.2em');
			$('#o_total td span').css('font-weight','700');
			// my.check_use_points = 0;
			my.use_points_val = 0;
		}
	};


	my.cb_use_all_points = function() {
        var cb_use_p = 0;
		var $p = my.use_points_val;
        var ta = (($rootScope.totalAmt * 1000) - ($p * 1000)) / 1000;
        var np = (my.cb_points);
        var ba = ($rootScope.batotal);
        if (ta >= np) {
            cb_use_p = np;
        }
        if (np > ta) {
            cb_use_p = ta;
        }
        $('#cb_use_points_val').val(cb_use_p);
        my.cb_use_points_val = cb_use_p;

		console.log(cb_use_p);

		my.cb_check_chg();
    }

    my.cb_check_points_val = function() {
        var $val = my.cb_use_points_val;
		var $p = my.use_points_val;
        var cb_use_p = 0;
        var ta = $rootScope.totalAmt * 1 - $p * 1;
        var np = my.cb_points;
        var ba = $rootScope.batotal;

        if (ta >= np) {
            cb_use_p = np;
        }
        if (np > ta) {
            cb_use_p = ta;
        }

        // cb_use_p += ba;

        var $right_val = $val;
        if ($val < 0) {
            $right_val = 0;
        }
        $right_val = $right_val;

        if ($right_val > cb_use_p) {
            $right_val = cb_use_p;
        }

        if (isNaN($right_val) || $right_val == '') {
            $right_val = 0;
        }

        $('#cb_use_points_val').val($right_val);
        my.cb_use_points_val = $right_val;

    }

    my.cb_check_use_points_chg = function() {
        value = $('#cb_check_use_points').prop('checked');
        $cb_use_p = 0;
        if (value == true) {
            $('#cb_show_points').show();
            $('#o_total td span').css('color', 'black');
            $('#o_total td span').css('font-size', '1.0em');
            $('#o_total td span').css('font-weight', 'normal');
			my.cb_use_points_val = 0;
            // my.check_use_points = 1;
        } else {
            $('#cb_show_points').hide();
            $('#o_total td span').css('color', '#eb4023');
            $('#o_total td span').css('font-size', '1.2em');
            $('#o_total td span').css('font-weight', '700');
			my.cb_use_points_val = 0;
			my.cb_check_chg();
            // my.check_use_points = 0;
        }
    };

	my.cb_check_chg = function(){
		value = $('#cb_check_use_points').prop('checked');
        if (value == true) {
            var $cb_use_p = 1;
        } else {
            var $cb_use_p = 0;
        }
        $('.cg-busy').removeClass('ng-hide');
        $cb_use_points = $('#cb_use_points_val').val();
        var ourl = CRUD.getUrl();
        CRUD.setUrl("app/controllers/eways.php");
		CRUD.update({ task: 'set_cb_use_points', cb_use_p: $cb_use_p, cb_use_points: $cb_use_points }, 'GET').then(function(res) {
            if (res.status == 1) {

            } else {
                error($translate.instant('lg_cart.please_check_cb'));
                err++;
            }
            $('.cg-busy').addClass('ng-hide');
        });
        CRUD.setUrl(ourl);
		$rootScope.getlist();
	}

	my.cb_use_points_chg = function(){
		my.cb_check_chg();
	}

	$rootScope.reset_use_p = function(){
		my.use_points_val = 0;
		my.cb_use_points_val = 0;
	}

	my.recal_tax = function(){
		$cal_total = (Math.round($rootScope.ntotal*1000 - my.use_points_val*1000 - my.cb_use_points_val*1000)/1000);
		if($cal_total < 0){
			$cal_total = 0;
		}
		var value = my.cartcvs.state;
		console.log(value);
		if(value.id == '5' && value.state_s == 'CA'){
			$rootScope.tax_fee = Math.round($cal_total * ($rootScope.tax_fee_rate * 100)) / 100;
		}else{
			$rootScope.tax_fee = 0;
		}
		
		
	}
	
	my.show_addcart_modal=function(id){
		angular.forEach(addproList,function(v,k){
			if(v.id==id){
				my.proid=v.id;
				my.proname=v.name;
				my.proimg=v.img;
				my.prositeAmt=v.amtProAmt;
				my.proformat1title=v.format.format1title;
				my.proformat2title=v.format.format2title;
				my.proformat1=v.format.format1;
				my.proformat2=v.format.format2;
				my.proformat22=v.format.format22;
				my.addpromax = v.addProCnt;
			}
		});
	};
	
	my.show_freecart_modal=function(id,fid,eventid){
		angular.forEach(freeproList,function(v,k){
			if(v.id==id){
				my.proid=v.id;
				my.proname=v.name;
				my.proimg=v.img;
				my.profid=fid;
				my.prositeAmt=v.siteAmt;
				my.proformat1title=v.format.format1title;
				my.proformat2title=v.format.format2title;
				my.proformat1=v.format.format1;
				my.proformat2=v.format.format2;
			}
		});
	};
	
	
	my.show_cart_modal=function(id,num,amt,imgname,f1,f2,protype,instock,instockchk,eventid){
		$scope.amt=amt;
		$scope.modal_cart_num=num;
		$scope.modaltotal=$scope.modal_cart_num*$scope.amt;
		$scope.proid=id;
		$scope.imgname=imgname;
		$scope.format1=f1;
		$scope.format2=f2;
		$scope.protype=protype;
		$scope.instock=instock;
		$scope.instockchk=instockchk;
		$scope.eventid=eventid;
		if(instock < num)
		{
			$scope.showMsg=true;
		}
		else
		{
			$scope.showMsg=false;
		}
		
	};
	
	my.show_cartdel_modal=function(id,num,amt,imgname,f1,f2,protype,eventid){
		$scope.amt=amt;
		$scope.modal_cart_num=num;
		$scope.modaltotal=$scope.modal_cart_num*$scope.amt;
		$scope.proid=id;
		$scope.imgname=imgname;
		$scope.format1=f1;
		$scope.format2=f2;
		$scope.protype=protype;
        $scope.eventid=eventid;
        
	};
	
	my.payTypeClick = function(){
		
		var ourl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.update({task:'set_pay_type',pay_type:$scope.pay_type.id},'GET').then(function(res){
			if(res.status==1){
				if($scope.get_take_modal_data){
					$scope.get_take_modal_data(1);
					
					$scope.pay_type_str=$scope.pay_type_list[$scope.pay_type.id].name;
				}
			}
		});
		CRUD.setUrl(ourl);
		
	};
	
	my.takeTypeClick = function(){
		
		var ourl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.update({task:'set_take_type',take_type:$scope.take_type.id},'GET').then(function(res){
			if(res.status==1){
				if($scope.take_type_list[$scope.take_type.id]){
					$scope.take_type_str=$scope.take_type_list[$scope.take_type.id].name;
					
				}
				$rootScope.dlvrAmt=0;
			}
		});
		CRUD.setUrl(ourl);
	};
	
	
	my.cart_chk=function(){
		
		var err=0;
		if(!$scope.pay_type.id || $scope.pay_type.id==="0"){
			error($translate.instant('lg_cart.please_select_pay_type'));
			err++;
		}
		if(!$scope.take_type.id){
			error($translate.instant('lg_cart.please_select_take_type'));
			err++;
		}

		if(!my.cartcvs.state){
			error($translate.instant('lg_cart.please_select_state'));
			err++;
		}

		if(!my.cartcvs.dt){
			error($translate.instant('lg_cart.please_select_dt'));
			err++;
		}

		if(!my.cartcvs.name){
			error($translate.instant('lg_cart.please_set_name'));
			err++;
		}

		if(!my.cartcvs.mobile){
			error($translate.instant('lg_cart.please_set_mobile'));
			err++;
		}

		if(!my.cartcvs.email){
			error($translate.instant('lg_cart.please_set_email'));
			err++;
		}

		if(!my.cartcvs.city){
			error($translate.instant('lg_cart.please_set_city'));
			err++;
		}

		if(!my.cartcvs.address){
			error($translate.instant('lg_cart.please_set_address'));
			err++;
		}


		
		//檢查活動選取
		if($rootScope.activePro_arr && Object.keys($rootScope.activePro_arr).length > 0)
		{
			if($rootScope.activeUsedPro_arr != 'null')
			{
				angular.forEach($rootScope.activeUsedPro_arr,function(v,k){
					if(v != '1' && v != '3')
					{
						error($translate.instant('lg_cart.please_select_active'));
						err++;
					}
				});
			}
			else
			{
				error($translate.instant('lg_cart.please_select_active'));
				err++;
			}
		}
		
		//檢查贈品規格
		angular.forEach($rootScope.data_list,function(v,k){
			if(!v.format1)
			{
				error($translate.instant('lg_cart.please_select_freepro')+v.name+$translate.instant('lg_cart.format'));
				err++;
			}
		});		

		//檢查點數
		value = $('#check_use_points').prop('checked');
		if(value == true){
			var $use_p = 1;
		}else{
			var $use_p = 0;
		}
		$('.cg-busy').removeClass('ng-hide');
		$use_points = $('#use_points_val').val();
		var ourl = CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.update({ task: 'set_use_points', use_p: $use_p,use_points: $use_points }, 'GET').then(function (res) {
			if (res.status == 1) {

			}else{
				error($translate.instant('lg_cart.please_check_points'));
				err++;
			}
			$('.cg-busy').addClass('ng-hide');
		});
		CRUD.setUrl(ourl);
		
		value = $('#cb_check_use_points').prop('checked');
        if (value == true) {
            var $cb_use_p = 1;
        } else {
            var $cb_use_p = 0;
        }
        $('.cg-busy').removeClass('ng-hide');
        $cb_use_points = $('#cb_use_points_val').val();
        var ourl = CRUD.getUrl();
        CRUD.setUrl("app/controllers/eways.php");
        CRUD.update({ task: 'set_cb_use_points', cb_use_p: $cb_use_p, cb_use_points: $cb_use_points }, 'GET').then(function(res) {
            if (res.status == 1) {

            } else {
                error($translate.instant('lg_cart.please_check_cb'));
                err++;
            }
            $('.cg-busy').addClass('ng-hide');
        });
        CRUD.setUrl(ourl);

		if(err==0){

			localStorage.setItem('uname',my.cartcvs.name);
			localStorage.setItem('umobile',my.cartcvs.mobile);
			localStorage.setItem('uaddress',my.cartcvs.address);
			localStorage.setItem('uemail',my.cartcvs.email);
			localStorage.setItem('ustate',JSON.stringify(my.cartcvs.state));
			localStorage.setItem('ucity',my.cartcvs.city);
			localStorage.setItem('unotes',my.cartcvs.notes);
			localStorage.setItem('udt',my.cartcvs.dt);
			localStorage.setItem('uzip',my.cartcvs.zip);

			CRUD.setUrl("components/cart/api.php");
			CRUD.update({ 
				task: 'set_userinfo', 
				name:my.cartcvs.name,
				mobile:my.cartcvs.mobile,
				address:my.cartcvs.address,
				email:my.cartcvs.email,
				state:my.cartcvs.state,
				city:my.cartcvs.city,
				notes:my.cartcvs.notes,
				dt:my.cartcvs.dt,
				zip:my.cartcvs.zip
			}, 'POST').then(function (res) {
				console.log(res);
				$('.cg-busy').addClass('ng-hide');
			});
			$location.path("cartcvs_list");
		}
	};
	
	if($scope.member_status==1){
		my.next_url="";
	}else{
		my.next_url="data-toggle=\"modal\" data-target=\"#myModal_Login\"";
	}
	
	
	
	my.setPair=function(index){
		my.pairid = index;
	};
	
	my.addPair=function(index){
		$rootScope.actProArr[index]['pairid'] = my.pairid;
		$rootScope.actProArr[my.pairid]['pairid'] = index;
		
		console.log(my.pairid);
		console.log(index);
		
		my.pairList.push(my.pairid+"|"+index);
		my.pairArr.push({pair1id:my.pairid,pair1Name:$rootScope.actProArr[my.pairid]['name'],pair2id:index,pair2Name:$rootScope.actProArr[index]['name']});
		my.pairid = 0;
		
		my.submitPair();
	};
	
	my.delPair=function(index){
		var key = $rootScope.actProArr[index]['pairid'];
		$rootScope.actProArr[key]['pairid'] = "";
		$rootScope.actProArr[index]['pairid'] = "";
		
		var i = my.pairList.indexOf(key+"|"+index);
		if(i > -1)
		{
			my.pairList.splice(i, 1);
			my.pairArr.splice(i, 1);
		}
		
		i = my.pairList.indexOf(index+"|"+key);
		if(i > -1)
		{
			my.pairList.splice(i, 1);
			my.pairArr.splice(i, 1);
		}
		my.pairid = 0;
		my.submitPair();
	};
	
	my.reSetPair=function(){
		my.pairid = 0;
		my.pairList = [];
		my.pairArr = [];
		angular.forEach($rootScope.actProArr,function(v,k){
			$rootScope.actProArr[k]['pairid'] = "";
		});
		
		my.submitPair();
	};
	
	my.submitPair=function(){
		
		var pairProStr = "";
		angular.forEach(my.pairList,function(v,k){
			var arr = v.split("|");
			
			id1 = $rootScope.actProArr[arr[0]]['id'];
			id2 = $rootScope.actProArr[arr[1]]['id'];
			
			if(pairProStr != "")
			{
				pairProStr += "@-@";
			}
			
			if(parseInt(arr[0]) < parseInt(arr[1]))
			{
				pairProStr += id1+"@@"+id2;
			}
			else
			{
				pairProStr += id2+"@@"+id1;
			}
			
			
		});
		
		console.log(pairProStr);
		
		CRUD.setUrl("components/cart/api.php");
		CRUD.list({task:'setPairProList', pairProStr: pairProStr},'POST').then(function(res){
			if(res.status == 1) {
				$rootScope.getlist();
				//$('#pairArea').slideToggle();
			}else{
				error(res.msg);
			}
		});
		
		
		
	};
	
	my.changeActive = function(pid , index)
	{
		console.log(pid);
		console.log(index);
		CRUD.setUrl("components/cart/api.php");
		CRUD.list({task:'setActiveProList', pid: pid , atype:index},'POST').then(function(res){
			if(res.status == 1) {
				$rootScope.getlist();
			}else{
				error(res.msg);
			}
		});
	}
	
	//刪除組合商品
	my.delBundle=function(unique){
        CRUD.setUrl("components/cart/api.php");
		CRUD.update({task:'delBundle', unique: unique},'POST').then(function(res){
			if(res.status == 1) {
				$rootScope.getlist();
			}else{
				error(res.msg);
			}
		});
    }
    
    my.showBundleModal=function(info,key,dtlKey,mainKey){
        my.modalIndex=key;
        my.modalDtlKey=dtlKey;
        my.modalMainKey=mainKey;
        my.productInfo=info;
               

        $('#productInfoModal').modal('show');
    };

    my.updateBundleProduct=function(){
        if(my.modalIndex && my.modalDtlKey && my.productInfo){
            my.selectedProductList={};
            var chk=0;
            var specName=[];
            angular.forEach(my.productInfo.spec,function(v){
                if(v && v.id){
                    chk++;
                    specName.push(v.name);
                }
            });
            if(chk!=2){
                error($translate.instant('lg_cart.please_select_format'));
                return;
            }
            
            my.selectedProductList['spec']=my.productInfo.spec;
            my.selectedProductList['selected']=1;
            my.selectedProductList['productId']=my.productInfo.productId;
            my.selectedProductList['productName']=my.productInfo.productName;
            my.selectedProductList['productImg']=my.productInfo.productImg;
            my.productInfo.selectedSpecName=specName.join('／');
            my.selectedProductList['selectedSpecName']=my.productInfo.selectedSpecName;
            angular.forEach(my.detailList,function(v){
                if(v.sequence==my.modalIndex){
                    v.selectedSpec=1;
                    angular.forEach(v.products,function(p){
                        p.selectedSpec=0;
                    });
                }
            });
            

            CRUD.setUrl("components/cart/api.php");
            CRUD.update({task:'updateBundle', selectedProductList: my.selectedProductList,modalIndex:my.modalIndex,modalDtlKey:my.modalDtlKey,modalMainKey:my.modalMainKey},'POST',true).then(function(res){
                if(res.status == 1) {
                    $rootScope.getlist();
                    $('#productInfoModal').modal('hide');
                }else{
                    error(res.msg);
                }
            });

        }else{
            $('#productInfoModal').modal('hide');
        }
    }
}]);

