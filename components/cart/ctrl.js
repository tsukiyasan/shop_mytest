app.controller("cart_list", [
  "$rootScope",
  "$scope",
  "$http",
  "$location",
  "$route",
  "$routeParams",
  "$translate",
  "CRUD",
  "$filter",
  "sessionCtrl",
  function (
    $rootScope,
    $scope,
    $http,
    $location,
    $route,
    $routeParams,
    $translate,
    CRUD,
    $filter,
    sessionCtrl
  ) {
    var my = this;

    my.lang = sessionCtrl.get("_lang");
    my.currency = sessionCtrl.get("_currency");

    my.get_addrCode = function () {
      var turl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.detail({ task: "get_addrCode" }, "GET").then(function (res) {
        if (res.status == 1) {
          my.city = res.city;
        }
      });
      CRUD.setUrl(turl);
    };
    my.get_addrCode();

    CRUD.setUrl("components/cart/api.php");
    var productFormat = [];
    var addproArr = [];
    var addproList = [];
    var freeproList = [];
    my.pairid = 0;
    my.pairList = [];
    my.pairArr = [];
    my.index = 1;
    my.cart_page = "";
    $scope.logistics_type = { id: 1 };
    $scope.mo = { id: 1 };
    my.use_points_val = 0;
    my.cb_use_points_val = 0;
    my.cartcvs = {};
    my.cartcvs.dt = "US-PM";
    // console.log(localStorage.getItem("udt"));
    // if (
    //   localStorage.getItem("udt") != "null" ||
    //   localStorage.getItem("udt") != NULL ||
    //   localStorage.getItem("udt") != "" ||
    //   localStorage.getItem("udt") != undefined
    // ) {
    //   my.cartcvs.dt = localStorage.getItem("udt");
    // } else {
    //   my.cartcvs.dt = "US-PM";
    // }

    // my.cartcvs.name = localStorage.getItem("uname");
    // my.cartcvs.mobile = localStorage.getItem("umobile");
    // my.cartcvs.email = localStorage.getItem("uemail");
    // my.cartcvs.address = localStorage.getItem("uaddress");
    // my.cartcvs.city = localStorage.getItem("ucity");
    // my.cartcvs.zip = localStorage.getItem("uzip");
    // if (localStorage.getItem("unotes") == "null") {
    //   var vnotes = "";
    // } else {
    //   var vnotes = localStorage.getItem("unotes");
    // }
    // my.cartcvs.notes = vnotes;
    // var ustate = JSON.parse(localStorage.getItem("ustate"));
    // my.cartcvs.state = ustate;

    // my.check_use_points = 0;
    my.get_cart_num = function () {
      turl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.list({ task: "get_cart_num" }, "GET").then(function (res) {
        $rootScope.cartCnt = res.cnt;
        my.order.sumTotal();
      });
      CRUD.setUrl(turl);
    };

    my.order = {
      total: 0,
      sumTotal: function () {
        // my.activeBundleFun.sumTotal();
        this.total = 0;
        if (!isNaN($rootScope.totalAmt)) {
          this.total += Number($rootScope.totalAmt);
        }
        if (!isNaN($rootScope.batotal)) {
          this.total += Number($rootScope.batotal);
        }
        this.total += Number(my.activeBundle.total);
        this.total += Number(my.activeBundle.bundleadd);
      },
    };

    //新的活動分組相關參數
    my.activeBundle = {
      total: 0,
      bundleadd: 0,
      pv: 0,
      bv: 0,
      ccv: 0,
    };

    //活動分組相關操作
    my.activeBundleFun = {
      //計算總合
      sumTotal: function () {
        let ab = Object.entries($rootScope.shopCart.session.activeBundle);
        my.activeBundle.total = 0;
        my.activeBundle.pv = 0;
        my.activeBundle.bv = 0;
        my.activeBundle.ccv = 0;
        if (ab.length > 0) {
          for (var _i in ab) {
            let uuid = ab[_i][0];
            $rootScope.shopCart.session.activeBundle[uuid].bundleadd = 0;
            if (ab[_i][1]) {
              let abId = ab[_i][1].id;
              let products = ab[_i][1].params.products;
              let _activeBundle =
                $rootScope.shopCart.activeBundle.actives[abId];
              my.activeBundle.total += Number(_activeBundle.price);
              my.activeBundle.pv += Number(_activeBundle.pv);
              my.activeBundle.bv += Number(_activeBundle.bv);
              my.activeBundle.ccv += Number(_activeBundle.ccv);
              //console.log(_activeBundle);
              //產品
              let productArr = Object.entries(products);
              for (_ii in productArr) {
                let pid = productArr[_ii][1].id;
                let product = $rootScope.shopCart.activeBundle.products[pid];
                let bundleadd = Number(product.bundleadd);
                //計算bundleadd
                if (bundleadd > 0 && product.bundleaddChk == "1") {
                  $rootScope.shopCart.session.activeBundle[uuid].bundleadd +=
                    bundleadd;
                  my.activeBundle.total += bundleadd;
                }
              }
            }
          }
        }
        //console.log($rootScope.shopCart.session.activeBundle);
      },

      updateActiveBundle: function () {
        let shopCart = $rootScope.shopCart;
        let data = shopCart.session.activeBundle;
        CRUD.update(
          { task: "updateActiveBundle", data: data },
          "POST",
          true
        ).then(function (res) {
          console.log(res);
        });
      },
      delActiveBundle: function (uuid) {
        //console.log($rootScope.shopCart.session.activeBundle[uuid]);
        if (confirm("確定刪除？")) {
          CRUD.setUrl("components/cart/api.php");
          CRUD.list({ task: "delActiveBundle", uuid: uuid }, "GET").then(
            function (res) {
              //console.log(res);
              if (res.status == "1") {
                delete $rootScope.shopCart.session.activeBundle[uuid];
                //更新購物車數量
                my.get_cart_num();
                my.ACB.init();
                my.cb_init();
                $rootScope.getlist();
              }
            }
          );
        }
      },
      //規格-顏色改變
      colorChange: function (product) {
        product.proInstockId = "";
        let color =
          $rootScope.shopCart.activeBundle.specs.structure[product.id] || false;
        if (color) {
          let size =
            $rootScope.shopCart.activeBundle.specs.structure[product.id][
              product.color
            ] || false;
          if (size && this.ObjAttrCount(size) == 1) {
            var sizeObj = Object.entries(size);
            product.proInstockId = sizeObj[0][1];
          }
        }
      },
      //商品改變
      productChange: function (product) {
        product.color = "";
        let color =
          $rootScope.shopCart.activeBundle.specs.structure[product.id] || false;
        if (color && this.ObjAttrCount(color) == 1) {
          var colorObj = Object.entries(color);
          product.color = colorObj[0][0];
        }
        this.colorChange(product);
      },
      //物件計算(物件版的array.length)
      ObjAttrCount: function (obj) {
        let count = 0;
        for (var _i in obj) {
          count++;
        }
        return count;
      },
      //數量改變
      quantityChange: function (obj, type) {
        var quantity = !(obj.quantity == undefined) ? Number(obj.quantity) : 0;
        quantity = Number(obj.quantity);
        if (!(type == undefined)) {
          quantity += Number(1 * type);
        }
        if (quantity < 0) {
          quantity = 0;
        }
        if (!(obj.quantity == undefined)) {
          obj.quantity = quantity;
        }
      },
    };

    //運費相關
    my.dlvr = {
      amount: 0,
      msg: "",
      dlvrfree: 0,
      dlvrfreenum: 0,
      params: null,
      reset: function () {
        this.amount = 0;
        this.msg = 0;
        this.params = null;
      },
      init: function () {
        this.reset();
        this.getData();
        //this.calculateAmount();
      },
      getData: function () {
        let vm = this;
        CRUD.setUrl("components/cart/api.php");
        CRUD.list({ task: "get_logistics",cm: localStorage.getItem('cartMode') }, "GET").then(function (res) {
          console.log('get_logistics-start');
          console.log(res);
          console.log('get_logistics-end');
          if (res.status) {
            vm.params = res.data;
            vm.dlvrfree = res.CalcDlvrFree;
            vm.dlvrfreenum = res.CalcDlvrFreeNum;
          } else {
            console.log(res.msg);
          }
          vm.calculateAmount();
        });
      },
      calculateAmount: function () {
        //訂單金額
        let orderAmount = 0;
        orderAmount = Math.round(my.order.total);
        origin_orderAmount = Math.round(my.order.total);
        //回饋點
        let cb = 0;
        if (my.cb_use_points_val) {
          cb = my.cb_use_points_val;
        }

        //活動回饋點
        let acb = 0;
        if (my.ACB) {
          acb = my.ACB.usePoint;
        }

        //去掉免運品價錢
        orderAmount = Number(orderAmount) - Number(this.dlvrfree);
        
        //折抵後訂單金額=訂單金額-回饋點-活動回饋點
        orderAmount = Number(orderAmount) - Number(cb);
        //console.log(orderAmount + '/回饋點後');
        orderAmount = Number(orderAmount) - Number(acb);
        //console.log(orderAmount + '/活動回饋點後');

        

        //購物車類型(cart=>一般,frozen=>冷凍)
        var cartmode = localStorage.getItem("cartMode");
        //本島(1)/離島(2)
        let moid = "main";
        if ($scope.mo.id == "2") {
          moid = "outlying";
        }

        //基本運費
        let basic = 0;
        //免運條件
        let fst = 0;
        //未滿免運條件運費
        let dlvr = 0;
        //欄位名稱
        let colName = "";

        //console.log(cartmode + '/cartmode');
        switch (cartmode) {
          case "frozen_cart":
            colName = "f_" + moid;
            break;
          case "cart":
            colName = moid;
            break;
        }
        //console.log(colName + '/colName');
        if (!(colName == "") && this.params) {
          //console.log(this.params);
          //console.log(colName + '/colName');
          dlvr = Number(this.params[colName + "_dlvr"]);
          //console.log(dlvr + '/dlvr');
          fst = Number(this.params[colName + "_fst"]);
          //console.log(fst + '/fst');
          if (cartmode == "frozen_cart") {
            basic = Number(this.params[colName + "_dlvr_basic"]);
            //console.log(basic + '/' + colName + '_dlvr_basic');
          }
        }

        this.amount = dlvr;
        console.log(orderAmount + '/orderAmount');
        console.log(basic);
        //達到免運費標準
        if (orderAmount >= fst) {
          //剛好
          if (orderAmount == fst) {
            this.amount = basic;
            console.log(basic);
          }
          //超過
          if (orderAmount > fst) {
            //一般
            this.amount = basic;
            //冷凍
            if (cartmode == "frozen_cart" && fst > 0) {
              if($scope.mo.id == "2"){
                this.amount = basic;
              }else{
                this.amount = 0;
              }
              
              // var unitCount = parseInt(orderAmount / fst);
              // //剛好部分
              // this.amount = Number(basic * unitCount);
              // //未滿部分
              // if (orderAmount - fst * unitCount > 0) {
              //   this.amount = this.amount + dlvr;
              // }
            }
          }
        }

        if ($scope.mo.id == "2") {
          if(origin_orderAmount == this.dlvrfree){
            this.amount = 0;
          }
          console.log(this.origin_orderAmount);
          console.log(this.amount);
          console.log(this.dlvrfree);
          console.log(basic);
          extra_dlvr = this.dlvrfreenum * basic;
          // this.amount = this.amount + extra_dlvr;
          
        }

        if ($scope.mo.id == "1") {
          if(origin_orderAmount == this.dlvrfree){
            this.amount = 0;
          }
        }

        //運費折抵
        if (!isNaN($rootScope.discountDlvrAmt)) {
          this.amount = this.amount - Number($rootScope.discountDlvrAmt);
        }
        //小於0以0計算
        if (this.amount < 0) {
          this.amount = 0;
        }
        //console.log(this);
      },
    };
    my.dlvr.init();

    //活動回饋點相關
    my.ACB = {
      point: 0,
      usePoint: 0,
      data: null,
      list: [],
      //重置資料
      reset: function () {
        this.point = 0;
        this.usePoint = 0;
        this.data = null;
        this.list = [];
      },
      //初始化
      init: function () {
        this.reset();
        this.getData();
      },
      //取資料
      getData: function () {
        let vm = this;
        CRUD.setUrl("components/cart/api.php");
        CRUD.list({ task: "act_cash_back" }, "GET").then(function (res) {
          vm.list = [];
          vm.point = res.point;
          vm.data = res.data;
          var maxData = Object.entries(res.max);
          for (var _i in maxData) {
            let id = maxData[_i][0];
            let acb = maxData[_i][1];
            if (acb.point > 0) {
              acb.usePoint = 0;
              vm.list.push(acb);
            }
          }
        });
      },
      //設定最大值
      setMax: function (list) {
        list.usePoint = this.getMax(list);
        this.sumPoint();
      },
      //取可使用點數最大值
      getMax: function (list) {
        let percent = list.use_percent || 0;
        let max = 0;
        //可使用點數
        let can_use_point = list.point;
        let max_can_use_point = Math.round(my.order.total * percent * 0.01);
        //可使用點數超過上限以上限計算
        max =
          can_use_point >= max_can_use_point
            ? max_can_use_point
            : can_use_point;
        return max;
      },
      //計算活動回饋點
      sumPoint: function () {
        let vm = this;
        let usePoint = 0;
        let orderAmt = 0;
        orderAmt = my.order.total;
        for (var _i in this.list) {
          let list = this.list[_i];
          let max = vm.getMax(list);
          list.usePoint = isNaN(list.usePoint) ? 0 : Number(list.usePoint);
          let point = list.usePoint;
          //超過上限以上限為主
          if (point > max) {
            point = max;
            list.usePoint = point;
          }
          usePoint += point;
          //超過訂單金額以訂單金額為上限
          if (usePoint >= orderAmt) {
            list.usePoint -= Number(usePoint - orderAmt);
            usePoint = orderAmt;
          }
        }
        vm.usePoint = usePoint;
        my.dlvr.calculateAmount();
      },
    };

    //取得使用CB上限
    my.getMaxUseCashBack = function () {
      let max = 0;
      console.dir(my.CBData);
      if (my.CBData) {
        my.order.sumTotal();
        var CBDataMax = Object.entries(my.CBData.max);
        for (var _i in CBDataMax) {
          let configID = CBDataMax[_i][0];
          let cb_config = CBDataMax[_i][1];
          //可使用點數
          let can_use_point = cb_config.point;
          let max_can_use_point = Math.round(
            my.order.total * cb_config.use_percent * 0.01
          );
          //可使用點數超過上限以上限計算
          max +=
            can_use_point >= max_can_use_point
              ? max_can_use_point
              : can_use_point;
        }
      }
      return max;
    };

    $rootScope.getlist = function () {
      
      $cartmode = localStorage.getItem("cartMode");
      $(".count_table").hide();
      $(".cg-busy2").removeClass("ng-hide");
      $(".cg-busy").removeClass("ng-hide");

      CRUD.setUrl("components/cart/api.php");
      CRUD.list({ task: "list", cartmode: $cartmode }, "GET").then(function (
        res
      ) {
        
        my.pairid = 0;
        my.pairList = [];
        my.pairArr = [];
        console.log(res);
        if (res.status == 1) {
          $rootScope.data_list = res.data.list;
          $rootScope.activeBundleCart = res.activeBundleCart;
          $rootScope.shopCart = res.shopCart;
          $rootScope.addpro_list = res.addpro_list;
          $rootScope.addpro_list_arr = res.addpro_list_arr;
          $rootScope.addpro_list_sub = res.addpro_list_sub;
          console.log($rootScope.shopCart);
          //檢查活動分組規格
          // var activeBundleParams = Object.entries(
          //   $rootScope.shopCart.session.activeBundle
          // );
          // for (var _i in activeBundleParams) {
          //   var _uuid = activeBundleParams[_i][0];
          //   var _activeBundle = activeBundleParams[_i][1];
          //   var _products = Object.entries(_activeBundle.params.products);
          //   for (var _ii in _products) {
          //     var notHasInstock = null;
          //     var _index = _products[_ii][0];
          //     var _product = _products[_ii][1];
          //     //顏色不存在
          //     if (
          //       !$rootScope.shopCart.activeBundle.specs.structure[_product.id][
          //         _product.color
          //       ]
          //     ) {
          //       notHasInstock = JSON.parse(JSON.stringify(_product));
          //       //預設為空
          //       $rootScope.shopCart.session.activeBundle[_uuid].params.products[
          //         _index
          //       ].color = "";
          //       var _colors = Object.entries(
          //         $rootScope.shopCart.activeBundle.specs.structure[_product.id]
          //       );
          //       var _colorsCount = _colors.length;
          //       //如果顏色只有一個
          //       if (_colorsCount == 1) {
          //         //自動選擇該顏色
          //         $rootScope.shopCart.session.activeBundle[
          //           _uuid
          //         ].params.products[_index].color = _colors[0][0];
          //         //規格預設為空
          //         $rootScope.shopCart.session.activeBundle[
          //           _uuid
          //         ].params.products[_index].proInstockId = "";
          //         var _instocks = Object.entries(
          //           $rootScope.shopCart.activeBundle.specs.structure[
          //             _product.id
          //           ][_colors[0][0]]
          //         );
          //         var _instocksCount = _instocks.length;
          //         //如果尺寸只有一個
          //         if (_instocksCount == 1) {
          //           //自動選擇該尺寸
          //           $rootScope.shopCart.session.activeBundle[
          //             _uuid
          //           ].params.products[_index].proInstockId = _instocks[0][0];
          //         }
          //       }
          //     }
          //     //若顏色為空，尺寸設為空
          //     if (
          //       $rootScope.shopCart.session.activeBundle[_uuid].params.products[
          //         _index
          //       ].color == ""
          //     ) {
          //       notHasInstock = JSON.parse(JSON.stringify(_product));
          //       $rootScope.shopCart.session.activeBundle[_uuid].params.products[
          //         _index
          //       ].proInstockId = "";
          //     }
          //     if (notHasInstock) {
          //       $rootScope.shopCart.session.activeBundle[_uuid].params.products[
          //         _index
          //       ].notHasInstock = notHasInstock;
          //     }
          //   }
          // }
          console.log(res.actProArr);
          $rootScope.batotal = res.batotal;
          $rootScope.actProArr = res.actProArr;
          $rootScope.isfourone = res.isfourone;

          if (
            $cartmode == "cart" &&
            res.data.list.length == 0 &&
            res.activeBundleCart.length == 0 &&
            activeBundleParams.length == 0
          ) {
            $route.reload();
            error("此購物車已清空");
          }

          if ($cartmode == "frozen" && res.data.list.length == 0) {
            $route.reload();
            error("此購物車已清空");
          }
          /*$rootScope.event_data_list = res.eventproArr;
                $rootScope.event_list = res.event_list;
                console.log($rootScope.event_data_list);
                console.log($rootScope.event_list);*/

          $rootScope.activeExtraList = res.activeExtraList;
          if (res.pairList) {
            angular.forEach(res.pairList, function (v, k) {
              my.pairList.push(v);
            });
          }

          if (res.pairArr) {
            angular.forEach(res.pairArr, function (v, k) {
              my.pairArr.push(v);
            });
          }

          $rootScope.total = res.total;
          $rootScope.h_pv = res.h_pv;
          $rootScope.m_pv = res.m_pv;
          $rootScope.t_pv = res.t_pv;
          $rootScope.h_bv = res.h_bv;
          $rootScope.allamt = res.amt;
          my.order.sumTotal();
          $rootScope.active_list = res.active_list;
          $rootScope.activePro_arr = res.activePro_arr;
          $rootScope.activeUsedPro_arr = res.activeUsedPro_arr;
          $rootScope.activePro_actName_arr = res.activePro_actName_arr;
          $rootScope.discount = res.discount;
          $rootScope.rp_discount = res.rp_discount;
          $rootScope.actPair_discount = res.actPair_discount;
          $rootScope.max_rp = res.rp;
          if (res.rp > 0 && res.total > 1200) {
            $rp_cnt = Math.floor(res.total / 1200);
          } else {
            $rp_cnt = 0;
          }
          my.rp_cnt = $rp_cnt;
          $rp_discount = res.rp_discount;
          my.rp_discount = res.rp_discount;

          if (!isNaN(res.btotal)) {
            $rootScope.btotal = res.btotal;
          } else {
            $rootScope.btotal = 0;
          }

          //$scope.usecoin = parseInt(res.usecoin);
          $rootScope.cart_use_coin = parseInt(res.usecoin);
          $rootScope.now_points = res.now_points;
          my.now_points = res.now_points;
          my.om = res.om;
          if (res.CBData.data) {
            my.CBData = res.CBData;
            my.max_cb_points = my.CBData.point;
          }

          //可使用回饋點上限
          var $max_ocb = my.getMaxUseCashBack();
          console.log("max_ocb");
          console.log($max_ocb);
          //可使用總回饋點
          $max_cp = my.max_cb_points;

          my.cb_points = $max_cp > $max_ocb ? $max_ocb : $max_cp;

          $rootScope.dlvrAmt = res.dlvrAmt;
          my.mode = res.mode;
          my.bonusArr = res.bonusArr;

          addproArr = res.addPro;
          // my.dlvrfeeStr = res.dlvrfeeStr;

          $rootScope.addAmtAction = [];
          //console.log($rootScope.active_list);
          angular.forEach($rootScope.active_list, function (v, k) {
            if (typeof v.addAmtPro != "undefined" && v.addAmtPro.length > 0) {
              if (v.act.activePlanid == "14") {
                $rootScope.addAmtAction.push({
                  actid: v.id,
                  name: v.name,
                  proarr: v.addAmtPro,
                  addProCnt: v.addProCnt,
                  e3: "1",
                });
              } else {
                $rootScope.addAmtAction.push({
                  actid: v.id,
                  name: v.name,
                  proarr: v.addAmtPro,
                  addProCnt: v.addProCnt,
                });
              }
            }
            // console.log($rootScope.addAmtAction);
            var tmp_addProCnt = v.addProCnt;

            angular.forEach(v.addAmtPro, function (v, k) {
              if (!productFormat[v.id]) productFormat[v.id] = [];
              productFormat[v.id]["f1"] = v.format.format1[0];
              productFormat[v.id]["f2"] = v.format.format2[v.format.format1[0]];
              v.addProCnt = tmp_addProCnt;
              addproList[v.id] = v;
            });
            angular.forEach(v.freePro, function (v, k) {
              if (!productFormat[v.id]) productFormat[v.id] = [];
              productFormat[v.id]["f1"] = v.format.format1[0];
              productFormat[v.id]["f2"] = v.format.format2[v.format.format1[0]];

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

          angular.forEach(res.activeBundleGiftProduct, function (value, key) {
            if (!productFormat[value.id]) productFormat[value.id] = [];
            productFormat[value.id]["f1"] = value.format
              ? value.format.format1[0]
              : "";
            productFormat[value.id]["f2"] = value.format
              ? value.format.format2[value.format.format1[0]]
              : "";

            freeproList[value.id] = value;
          });

          if (my.mode == "cart" || my.mode == "frozen") {
            my.initAddPro();
          }

          //付款方式與取貨方式
          $rootScope.pay_type_list = res.pay_type;
          $rootScope.take_type_list = res.take_type;
          $rootScope.logistics_list = res.logistics_type;

          my.dlvrfeeShowStr = res.dlvrfeeShowStr;

          my.c_pairArr_list = res.cart_act_pair_list;
          console.log($rootScope.actProArr);
          my.logisticsTypeClick();
        } else {
          // console.log(res.status);
          if (res.status == "3") {
            var $html = "<span>" + res.msg + "</span>";

            $("#s_chk").html($html);
            $("#myModal_chk").addClass("in act");
            $("#myModal_chk").show();
            // $location.path("member_page/info");
          } else if (res.status == "4") {
            var $html = "<span>" + res.msg + "</span>";
            $("#s_order").html($html);
            $("#myModal_order").addClass("in act");
            $("#myModal_order").show();
            // $location.path("member_page/order");
          } else {
            error(res.msg);
            $location.path("index_page");
          }
        }

        // if ($type == null) {
        // 	my.do_regpoint();
        // }
        $(".count_table").show();
        $(".cg-busy").addClass("ng-hide");
        $(".cg-busy2").addClass("ng-hide");
      });
    };

    my.reff = function (e) {
      if (e == "1") {
        $location.path("member_page/info");
      }
      if (e == "2") {
        $location.path("member_page/order");
      }
    };
    // $rootScope.getlist();
    my.initAddPro = function () {
      angular.forEach(addproArr, function (v, k) {
        if (!my.checkboxG) my.checkboxG = [];
        my.checkboxG[k] = true;
      });
    };

    // CRUD.list({ task: "showAddProlist" }, "GET").then(function (res) {
    //   if (res.status == 1) {
    //     my.addpro_list = res.data.list;
    //   } else {
    //     //$location.path("index_page");
    //     error(res.msg);
    //   }
    // });

    $scope.$watch("testamt", function (value) {
      console.log("testamt");
      console.log($scope.testamt);
    });
    console.log($scope.testamt);

    $scope.$watch("allamt", function (value) {
      //這邊計算總額
      $scope.cart_use_coin = $scope.cart_use_coin ? $scope.cart_use_coin : 0;
      $rootScope.totalAmt = value * 1 - $scope.cart_use_coin * 1;
      $rootScope.memAmt = $rootScope.totalAmt;

      //可使用回饋點上限
      var $max_ocb = my.getMaxUseCashBack();
      //可使用總回饋點
      $max_cp = my.max_cb_points;
      my.cb_points = $max_cp > $max_ocb ? $max_ocb : $max_cp;
      // my.ACB.init();
      my.dlvr.calculateAmount();
      //my.dlvr.init();
      my.order.sumTotal();
    });

    my.do_regpoint = function () {
      $(".cg-busy").removeClass("ng-hide");
      value = $("#check_regpoint").prop("checked");
      $use_rp = 0;
      if (value == true) {
        $use_rp = 1;
      } else {
        $use_rp = 0;
      }
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update({ task: "set_use_regpoint", use_rp: $use_rp }, "GET").then(
        function (res) {
          if (res.status == 1) {
            if (value == true) {
              $rootScope.discount =
                parseInt($rootScope.discount) +
                parseInt($rootScope.rp_discount) +
                parseInt($rootScope.actPair_discount);
              $rootScope.totalAmt =
                parseInt($rootScope.totalAmt) -
                parseInt($rootScope.rp_discount) -
                parseInt($rootScope.actPair_discount);
              // if (parseInt($rootScope.totalAmt) < 6000) {
              // 	$rootScope.dlvrAmt = 100;
              // }
              console.log("HERE");
            } else {
              // $rootScope.discount = parseInt($rootScope.discount) - parseInt($rootScope.rp_discount);
              $rootScope.totalAmt = parseInt($rootScope.memAmt);
              //console.log('set_use_regpoint');
              $rootScope.getlist("rp");
            }
          }
          // $('.cg-busy').addClass('ng-hide');
        }
      );
      CRUD.setUrl(ourl);
    };

    $scope.$watch("cart_use_coin", function (newValue, oldValue) {
      $rootScope.totalAmt = $scope.allamt * 1 - newValue * 1;
      $rootScope.memAmt = $rootScope.totalAmt;
    });

    my.use_all_points = function () {
      var use_p = 0;
      var $p = my.cb_use_points_val;
      //var ta = $rootScope.totalAmt * 1 - $p * 1;
      var ta = my.order.total - $p * 1;
      var np = parseInt(Math.floor(my.now_points));
      var ba = parseInt(Math.floor($rootScope.batotal));
      console.log(ta);
      console.log(ba);
      if (ta >= np) {
        use_p = np;
      }
      if (np > ta) {
        use_p = ta;
      }
      use_p += ba;
      $("#use_points_val").val(use_p);
      my.use_points_val = use_p;
    };

    my.check_points_val = function () {
      var $val = my.use_points_val;
      var use_p = 0;
      var $p = my.cb_use_points_val;
      //var ta = $rootScope.totalAmt * 1 - $p * 1;
      var ta = my.order.total - $p * 1;
      var np = parseInt(Math.floor(my.now_points));
      var ba = parseInt(Math.floor($rootScope.batotal));

      if (ta >= np) {
        use_p = np;
      }
      if (np > ta) {
        use_p = ta;
      }

      use_p += ba;

      var $right_val = $val;
      if ($val < 0) {
        $right_val = 0;
      }
      $right_val = parseInt(Math.floor($right_val));

      if ($right_val > use_p) {
        $right_val = use_p;
      }
      console.log($right_val);
      if (isNaN($right_val) || $right_val == "") {
        $right_val = 0;
      }

      $("#use_points_val").val($right_val);
      my.use_points_val = $right_val;
    };

    my.check_use_points_chg = function () {
      value = $("#check_use_points").prop("checked");
      $use_p = 0;
      if (value == true) {
        $("#show_points").show();
        $("#o_total td span").css("color", "black");
        $("#o_total td span").css("font-size", "1.0em");
        $("#o_total td span").css("font-weight", "normal");
        my.use_points_val = 0;
        // my.check_use_points = 1;
      } else {
        $("#show_points").hide();
        $("#o_total td span").css("color", "#eb4023");
        $("#o_total td span").css("font-size", "1.2em");
        $("#o_total td span").css("font-weight", "700");
        my.use_points_val = 0;
        // my.check_use_points = 0;
      }
    };

    my.cb_init = function () {
      var cb_use_p = 0;
      $("#cb_use_points_val").val(cb_use_p);
      my.cb_use_points_val = cb_use_p;
      my.cb_check_chg();
    };

    my.cb_use_all_points = function () {
      var cb_use_p = 0;
      var $p = my.use_points_val;
      //var ta = $rootScope.totalAmt * 1 - $p * 1;
      var ta = my.order.total - $p * 1;
      var np = parseInt(Math.floor(my.cb_points));
      cb_use_p = ta >= np ? np : ta;
      $("#cb_use_points_val").val(cb_use_p);
      my.cb_use_points_val = cb_use_p;
      my.cb_check_chg();
    };

    my.cb_check_points_val = function () {
      var $val = my.cb_use_points_val;
      var $p = my.use_points_val;
      var cb_use_p = 0;
      //var ta = $rootScope.totalAmt * 1 - $p * 1;
      var ta = my.order.total - $p * 1;
      var np = parseInt(Math.floor(my.cb_points));

      cb_use_p = ta >= np ? np : ta;

      var $right_val = $val;
      if ($val < 0) {
        $right_val = 0;
      }
      $right_val = parseInt(Math.floor($right_val));

      if ($right_val > cb_use_p) {
        $right_val = cb_use_p;
      }

      if (isNaN($right_val) || $right_val == "") {
        $right_val = 0;
      }

      $("#cb_use_points_val").val($right_val);
      my.cb_use_points_val = $right_val;
      my.dlvr.calculateAmount();
    };

    my.cb_check_use_points_chg = function () {
      value = $("#cb_check_use_points").prop("checked");
      $cb_use_p = 0;
      if (value == true) {
        $("#cb_show_points").show();
        $("#o_total td span").css("color", "black");
        $("#o_total td span").css("font-size", "1.0em");
        $("#o_total td span").css("font-weight", "normal");
        my.cb_use_points_val = 0;
        // my.check_use_points = 1;
      } else {
        $("#cb_show_points").hide();
        $("#o_total td span").css("color", "#eb4023");
        $("#o_total td span").css("font-size", "1.2em");
        $("#o_total td span").css("font-weight", "700");
        my.cb_use_points_val = 0;
        my.cb_check_chg();
        // my.check_use_points = 0;
      }
    };

    my.cb_check_chg = function () {
      value = $("#cb_check_use_points").prop("checked");
      if (value == true) {
        var $cb_use_p = 1;
      } else {
        var $cb_use_p = 0;
      }
      $(".cg-busy").removeClass("ng-hide");
      $cb_use_points = $("#cb_use_points_val").val();
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        {
          task: "set_cb_use_points",
          cb_use_p: $cb_use_p,
          cb_use_points: $cb_use_points,
        },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
        } else {
          error($translate.instant("lg_cart.please_check_cb"));
          err++;
        }
        $(".cg-busy").addClass("ng-hide");
      });
      CRUD.setUrl(ourl);
      //console.log('set_cb_use_points');
      $rootScope.getlist();
    };

    my.cb_use_points_chg = function () {
      my.cb_check_chg();
    };

    $rootScope.reset_use_p = function () {
      my.use_points_val = 0;
      my.cb_use_points_val = 0;
    };

    my.show_addcart_modal = function (id) {
      angular.forEach(addproList, function (v, k) {
        if (v.id == id) {
          my.proid = v.id;
          my.proname = v.name;
          my.proimg = v.img;
          my.prositeAmt = v.amtProAmt;
          my.proformat1title = v.format.format1title;
          my.proformat2title = v.format.format2title;
          my.proformat1 = v.format.format1;
          my.proformat2 = v.format.format2;
          my.proformat22 = v.format.format22;
          my.addpromax = v.addProCnt;
          my.time = new Date().getTime();
        }
      });
    };

    my.show_e3addcart_modal = function (id) {
      CRUD.setUrl("components/cart/api.php");
      CRUD.list({ task: "gete3point" }, "GET").then(function (res) {
        if (res.status == 1) {
          my.e3point = res.data;
          my.e3cnt = res.e3_cnt;
        } else {
          my.e3point = "0";
          my.e3cnt = "0";
        }
      });
      angular.forEach(addproList, function (v, k) {
        if (v.id == id) {
          my.proid = v.id;
          my.proname = v.name;
          my.proimg = v.img;
          my.prositeAmt = v.e3bonusAmt;
          my.proformat1title = v.format.format1title;
          my.proformat2title = v.format.format2title;
          my.proformat1 = v.format.format1;
          my.proformat2 = v.format.format2;
          my.proformat22 = v.format.format22;
          my.addpromax = v.addProCnt;
          my.time = new Date().getTime();
        }
      });
      // console.log(my);
    };

    my.show_freecart_modal = function (id, fid, eventid) {
      // console.log(freeproList);
      angular.forEach(freeproList, function (v, k) {
        // console.log(k);
        if (v.id == id) {
          my.proid = v.id;
          my.proname = v.name;
          my.proimg = v.img;
          my.profid = fid;
          my.prositeAmt = v.siteAmt;
          my.proformat1title = v.format.format1title;
          my.proformat2title = v.format.format2title;
          my.proformat1 = v.format.format1;
          my.proformat2 = v.format.format2;
          my.time = new Date().getTime();
        }
      });
    };

    my.show_cart_modal = function (
      id,
      num,
      amt,
      imgname,
      f1,
      f2,
      protype,
      instock,
      instockchk,
      eventid
    ) {
      $scope.amt = amt;
      $scope.modal_cart_num = num;
      $scope.modaltotal = $scope.modal_cart_num * $scope.amt;
      $scope.proid = id;
      $scope.imgname = imgname;
      $scope.format1 = f1;
      $scope.format2 = f2;
      $scope.protype = protype;
      $scope.instock = instock;
      $scope.instockchk = instockchk;
      $scope.eventid = eventid;
      if (instock < num) {
        $scope.showMsg = true;
      } else {
        $scope.showMsg = false;
      }
    };

    my.show_cartdel_modal = function (
      id,
      num,
      amt,
      imgname,
      f1,
      f2,
      protype,
      eventid
    ) {
      $scope.amt = amt;
      $scope.modal_cart_num = num;
      $scope.modaltotal = $scope.modal_cart_num * $scope.amt;
      $scope.proid = id;
      $scope.imgname = imgname;
      $scope.format1 = f1;
      $scope.format2 = f2;
      $scope.protype = protype;
      $scope.eventid = eventid;
    };

    my.show_e3cartdel_modal = function (
      id,
      num,
      amt,
      imgname,
      f1,
      f2,
      protype,
      eventid
    ) {
      $scope.amt = amt;
      $scope.modal_cart_num = num;
      $scope.modaltotal = $scope.modal_cart_num * $scope.amt;
      $scope.proid = id;
      $scope.imgname = imgname;
      $scope.format1 = f1;
      $scope.format2 = f2;
      $scope.protype = protype;
      $scope.eventid = eventid;
    };

    my.payTypeClick = function () {
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        { task: "set_pay_type", pay_type: $scope.pay_type.id },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
          if ($scope.get_take_modal_data) {
            $scope.get_take_modal_data(1);
            my.logisticsTypeClick();
            $scope.pay_type_str = $scope.pay_type_list[$scope.pay_type.id].name;
          }
        }
      });
      CRUD.setUrl(ourl);
    };

    my.logisticsTypeClick = function () {
      $(".cg-busy").removeClass("ng-hide");
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        {
          task: "set_logistics_type",
          logistics_type: $scope.logistics_type.id,
          mo: $scope.mo.id,
        },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
          //console.log(res);
          $rootScope.dlvrAmt = res.dlvrAmt;
          my.dlvrfeeStr = res.dlvrfeeStr;
          $(".cg-busy").addClass("ng-hide");
          my.dlvr.getData();
        }
        
      });
      CRUD.setUrl(ourl);
      // console.log($scope.logistics_type.id);
      //my.dlvr.calculateAmount();
    };

    my.takeTypeClick = function () {
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        { task: "set_take_type", take_type: $scope.take_type.id },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
          if ($scope.take_type_list[$scope.take_type.id]) {
            $scope.take_type_str =
              $scope.take_type_list[$scope.take_type.id].name;
          }
          // $rootScope.dlvrAmt = res.dlvrAmt;
        }
      });
      CRUD.setUrl(ourl);
    };

    my.cart_chk = function () {
      var err = 0;
      if (!$scope.pay_type.id || $scope.pay_type.id === "0") {
        error("請選擇付款方式");
        err++;
      }
      if (!$scope.take_type.id) {
        error("請選擇取貨方式");
        err++;
      }
      if (!$scope.mo.id) {
        error("請選擇區域");
        err++;
      }
      if (!$scope.logistics_type.id) {
        error("請選擇物流方式");
        err++;
      }

      if(!my.check_terms){
        error($translate.instant("lg_cart.please_check_terms"));
        err++;
      }

      //檢查活動選取
      if (
        $rootScope.activePro_arr &&
        Object.keys($rootScope.activePro_arr).length > 0
      ) {
        if ($rootScope.activeUsedPro_arr != "null") {
          angular.forEach($rootScope.activeUsedPro_arr, function (v, k) {
            if (v != "1" && v != "3") {
              error("請選擇優惠活動");
              err++;
            }
          });
        } else {
          error("請選擇優惠活動");
          err++;
        }
      }

      //檢查贈品規格
      angular.forEach($rootScope.data_list, function (v, k) {
        if (!v.format1) {
          error("請選擇贈品" + v.name + "規格");
          err++;
        }
      });

      //檢查購物金
      value = $("#check_use_points").prop("checked");
      if (value == true) {
        var $use_p = 1;
      } else {
        var $use_p = 0;
      }

      //檢查活動分組
      if (
        $rootScope.shopCart.session &&
        $rootScope.shopCart.session.activeBundle
      ) {
        var activeBundles = Object.entries(
          $rootScope.shopCart.session.activeBundle
        );
        if (activeBundles.length > 0) {
          for (var _i in activeBundles) {
            var _uuid = activeBundles[_i][0];
            var _activeBundle = activeBundles[_i][1];
            var _products = Object.entries(_activeBundle.params.products);
            if (_products.length > 0) {
              for (var _ii in _products) {
                var _index = _products[_ii][0];
                var _product = _products[_ii][1];
                $rootScope.shopCart.session.activeBundle[_uuid].params.products[
                  _index
                ].errorStatus = "0";
                if (_product.color == "" || _product.proInstockId == "") {
                  $rootScope.shopCart.session.activeBundle[
                    _uuid
                  ].params.products[_index].errorStatus = "1";
                  error("顏色或尺寸未選擇，請確認");
                  err++;
                }
                if (!(_product.notHasInstock == undefined) && err == 0) {
                  delete $rootScope.shopCart.session.activeBundle[_uuid].params
                    .products[_index].notHasInstock;
                }
              }
            }
          }
        }
      }
      if (err == 0) {
        //更新活動分組
        // my.activeBundleFun.updateActiveBundle();
      }

      $(".cg-busy").removeClass("ng-hide");
      $use_points = $("#use_points_val").val();
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        { task: "set_use_points", use_p: $use_p, use_points: $use_points },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
        } else {
          error($translate.instant("lg_cart.please_check_points"));
          err++;
        }
        $(".cg-busy").addClass("ng-hide");
      });
      CRUD.setUrl(ourl);

      //檢查回饋點
      value = $("#cb_check_use_points").prop("checked");
      if (value == true) {
        var $cb_use_p = 1;
      } else {
        var $cb_use_p = 0;
      }
      $(".cg-busy").removeClass("ng-hide");
      $cb_use_points = $("#cb_use_points_val").val();
      var ourl = CRUD.getUrl();
      CRUD.setUrl("app/controllers/eways.php");
      CRUD.update(
        {
          task: "set_cb_use_points",
          cb_use_p: $cb_use_p,
          cb_use_points: $cb_use_points,
        },
        "GET"
      ).then(function (res) {
        if (res.status == 1) {
        } else {
          error($translate.instant("lg_cart.please_check_cb"));
          err++;
        }
        $(".cg-busy").addClass("ng-hide");
      });
      CRUD.setUrl(ourl);

      //檢查活動回饋點
      // let oUrl = CRUD.getUrl();
      // CRUD.setUrl("app/controllers/eways.php");
      // CRUD.update(
      //   { task: "set_acb_use_points", params: my.ACB.list },
      //   "POST"
      // ).then(function (res) {
      //   if (!(res.status == "1")) {
      //     error($translate.instant("lg_cart.please_check_cb"));
      //     err++;
      //   }
      // });
      // CRUD.setUrl(oUrl);

      if (err == 0) {
        $location.path("cartcvs_list");
      }
    };

    if ($scope.member_status == 1) {
      my.next_url = "";
    } else {
      my.next_url = 'data-toggle="modal" data-target="#myModal_Login"';
    }

    my.setPair = function (index, rid) {
      my.pairid = index;
      my.firstrid = rid;
      // console.log(index);
      // console.log(rid);
    };

    my.addPair = function (index) {
      $rootScope.actProArr[index]["pairid"] = my.pairid;
      $rootScope.actProArr[my.pairid]["pairid"] = index;

      // console.log(my.pairid);
      // console.log(index);

      my.pairList.push(my.pairid + "|" + index);
      my.pairArr.push({
        pair1id: my.pairid,
        pair1Name: $rootScope.actProArr[my.pairid]["name"],
        pair2id: index,
        pair2Name: $rootScope.actProArr[index]["name"],
      });
      my.pairid = 0;

      my.submitPair();
    };

    // my.addPair = function (index, rid) {
    // 	chk = 0;
    // 	if (rid != my.firstrid) {
    // 		chk++;
    // 	}
    // 	if (chk == 0) {
    // 		$rootScope.actProArr[index]['pairid'] = my.pairid;
    // 		$rootScope.actProArr[my.pairid]['pairid'] = index;

    // 		console.log(my.pairid);
    // 		console.log(index);

    // 		my.pairList.push(my.pairid + "|" + index);
    // 		my.pairArr.push({ pair1id: my.pairid, pair1Name: $rootScope.actProArr[my.pairid]['name'], pair2id: index, pair2Name: $rootScope.actProArr[index]['name'] });
    // 		my.pairid = 0;
    // 		my.submitPair();
    // 	} else {
    // 		error('只能同系列商品配對!');
    // 	}
    // };

    my.reFirst = function () {
      my.firstrid = 0;
      my.pairid = 0;
    };

    my.delPair = function (index) {
      var key = $rootScope.actProArr[index]["pairid"];
      $rootScope.actProArr[key]["pairid"] = "";
      $rootScope.actProArr[index]["pairid"] = "";

      var i = my.pairList.indexOf(key + "|" + index);
      if (i > -1) {
        my.pairList.splice(i, 1);
        my.pairArr.splice(i, 1);
      }

      i = my.pairList.indexOf(index + "|" + key);
      if (i > -1) {
        my.pairList.splice(i, 1);
        my.pairArr.splice(i, 1);
      }
      my.pairid = 0;
      my.submitPair();
    };

    my.reSetPair = function () {
      my.firstrid = 0;
      my.pairid = 0;
      my.pairList = [];
      my.pairArr = [];
      angular.forEach($rootScope.actProArr, function (v, k) {
        $rootScope.actProArr[k]["pairid"] = "";
      });

      my.submitPair();
    };

    my.submitPair = function () {
      var pairProStr = "";
      console.log($rootScope.actProArr);
      angular.forEach(my.pairList, function (v, k) {
        var arr = v.split("|");

        id1 = $rootScope.actProArr[arr[0]]["id"];
        id2 = $rootScope.actProArr[arr[1]]["id"];

        if (pairProStr != "") {
          pairProStr += "@-@";
        }

        if (parseInt(arr[0]) < parseInt(arr[1])) {
          pairProStr += id1 + "@@" + id2;
        } else {
          pairProStr += id2 + "@@" + id1;
        }
      });

      console.log(pairProStr);

      CRUD.setUrl("components/cart/api.php");
      CRUD.list(
        { task: "setPairProList", pairProStr: pairProStr },
        "POST"
      ).then(function (res) {
        console.log($rootScope.actProArr);
        if (res.status == 1) {
          $rootScope.getlist();
          //console.log('setparprolist');
          //$('#pairArea').slideToggle();
        } else {
          error(res.msg);
        }
      });
    };

    my.changeActive = function (pid, index) {
      // console.log(pid);
      // console.log(index);
      CRUD.setUrl("components/cart/api.php");
      CRUD.list(
        { task: "setActiveProList", pid: pid, atype: index },
        "POST"
      ).then(function (res) {
        if (res.status == 1) {
          //console.log('setactiveProList');
          $rootScope.getlist();
        } else {
          error(res.msg);
        }
      });
    };

    //刪除組合商品
    my.delBundle = function (unique) {
      CRUD.setUrl("components/cart/api.php");
      CRUD.update({ task: "delBundle", unique: unique }, "POST").then(function (
        res
      ) {
        if (res.status == 1) {
          //console.log('delbundle');
          $rootScope.getlist();
        } else {
          error(res.msg);
        }
      });
    };

    my.showBundleModal = function (info, key, dtlKey, mainKey) {
      my.modalIndex = key;
      my.modalDtlKey = dtlKey;
      my.modalMainKey = mainKey;
      my.productInfo = info;

      $("#productInfoModal").modal("show");
    };

    my.updateBundleProduct = function () {
      if (my.modalIndex && my.modalDtlKey && my.productInfo) {
        my.selectedProductList = {};
        var chk = 0;
        var specName = [];
        angular.forEach(my.productInfo.spec, function (v) {
          if (v && v.id) {
            chk++;
            specName.push(v.name);
          }
        });
        if (chk != 2) {
          error("請選擇規格");
          return;
        }

        my.selectedProductList["spec"] = my.productInfo.spec;
        my.selectedProductList["selected"] = 1;
        my.selectedProductList["productId"] = my.productInfo.productId;
        my.selectedProductList["productName"] = my.productInfo.productName;
        my.selectedProductList["productImg"] = my.productInfo.productImg;
        my.productInfo.selectedSpecName = specName.join("／");
        my.selectedProductList["selectedSpecName"] =
          my.productInfo.selectedSpecName;
        angular.forEach(my.detailList, function (v) {
          if (v.sequence == my.modalIndex) {
            v.selectedSpec = 1;
            angular.forEach(v.products, function (p) {
              p.selectedSpec = 0;
            });
          }
        });

        CRUD.setUrl("components/cart/api.php");
        CRUD.update(
          {
            task: "updateBundle",
            selectedProductList: my.selectedProductList,
            modalIndex: my.modalIndex,
            modalDtlKey: my.modalDtlKey,
            modalMainKey: my.modalMainKey,
          },
          "POST",
          true
        ).then(function (res) {
          if (res.status == 1) {
            //console.log('updatebundle');
            $rootScope.getlist();
            $("#productInfoModal").modal("hide");
          } else {
            error(res.msg);
          }
        });
      } else {
        $("#productInfoModal").modal("hide");
      }
    };

    if (my.cart_page == "") {
      CRUD.list({ task: "get_num_cart_list" }, "GET").then(function (res) {
        if (res.status == 1) {
          my.t_num = res.twcart_num;
          my.c_num = res.cart_num;
        }
      });

      // my.get_act12_list();
    }

    my.n_cart_c = function () {
      localStorage.setItem("cartMode", "cart");
      // if($scope.om == '0'){
      //   if (my.act12_list.length > 0) {
      //     my.cart_page = "active12";
      //     // my.cart_page = 'n_cart';
      //   } else {
          my.cart_page = "n_cart";
          $rootScope.getlist();
      //   }
      // }else if($scope.om == '1'){
      //   my.cart_page = "n_cart";
      //   $rootScope.getlist();
      // }
      

      
      my.use_points_val = 0;
      my.cb_use_points_val = 0;

      CRUD.setUrl("components/cart/api.php");
      CRUD.update(
        {
          task: "reset_points"
        },
        "POST",
        true
      ).then(function (res) {
        console.log(res);
      });

      // my.jump = "0";
      // $(".acts_div").first().show();
      //console.log('n_cart_c');
      // $rootScope.getlist();
    };

    my.t_cart_c = function () {
      localStorage.setItem("cartMode", "twcart_cart");
      // if (my.act12_list.length > 0) {
      //   my.cart_page = "active12";
      //   // my.cart_page = 'n_cart';
      // } else {
        my.cart_page = "n_cart";
        $rootScope.getlist();
      // }
      
      CRUD.setUrl("components/cart/api.php");
      CRUD.update(
        {
          task: "reset_points"
        },
        "POST",
        true
      ).then(function (res) {
        console.log(res);
      });

      my.use_points_val = 0;
      my.cb_use_points_val = 0;
      // my.jump = "0";
      // $(".acts_div").first().show();
      //console.log('f_cart_c');
      // $rootScope.getlist();
    };
  },
]);
