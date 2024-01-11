app.controller("mbst_page", [
  "$rootScope",
  "$scope",
  "$http",
  "$location",
  "$route",
  "$routeParams",
  "$translate",
  "CRUD",
  "$filter",
  "fbLogin",
  "gpLogin",
  "$timeout",
  "$sce",
  "$compile",
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
    fbLogin,
    gpLogin,
    $timeout,
    $sce,
    $compile
  ) {
    var my = this;

    CRUD.setUrl("components/member/api.php");
    my.isIE11 = !!window.MSInputMethodContext && !!document.documentMode;
    my.resertpw = {};
    my.thisPage = my.mbst_page = $routeParams.type;
    var idcardplimit = 1;
    my.idcardplist = [];
    for (var i = 1; i <= idcardplimit; i++) {
      my.idcardplist.push(i);
    }

    var idcardnlimit = 1;
    my.idcardnlist = [];
    for (var i = 1; i <= idcardnlimit; i++) {
      my.idcardnlist.push(i);
    }

    var bankplimit = 1;
    my.bankplist = [];
    for (var i = 1; i <= bankplimit; i++) {
      my.bankplist.push(i);
    }

    //gif
    var node = new Image();
    node.src = "img/tree/tree_node.gif";
    node.title = "node";
    var lastnode = new Image();
    lastnode.src = "img/tree/tree_lastnode.gif";
    lastnode.title = "lastnode";
    var pnode = new Image();
    pnode.src = "img/tree/tree_pnode.gif";
    pnode.title = "pnode";
    var lastpnode = new Image();
    lastpnode.src = "img/tree/tree_lastpnode.gif";
    lastpnode.title = "lastpnode";
    var snode = new Image();
    snode.src = "img/tree/tree_snode.gif";
    snode.title = "snode";
    var lastsnode = new Image();
    lastsnode.src = "img/tree/tree_lastsnode.gif";
    lastsnode.title = "lastsnode";
    var vline = new Image();
    vline.src = "img/tree/tree_vline.gif";
    vline.title = "vline";
    var spacer = new Image();
    spacer.src = "img/tree/tree_spacer.gif";
    spacer.title = "spacer";

    if ($routeParams.uid) {
      my.resertpw.uid = $routeParams.uid;
    } else {
      my.resertpw.uid = $location.search().a;
    }

    $("body").css("overflow", "auto");

    my.sign = {};
    my.login = {};
    my.sign20 = {};
    my.sign30 = {};
    my.sign3011 = {};

    my.imagesizelimit = 1048576;
    my.productimg = [];
    my.productimg2 = [];
    my.productimg3 = [];
    $scope.previewImage = [];
    $scope.previewImage2 = [];
    $scope.previewImage3 = [];

    var imagelimit = 1;



    angular.forEach(my.multileftmenu1, function (v, k) {
      angular.forEach(v.child, function (v2, k2) {
          if (v2.dtl) {
              if (v2.id == my.mbst_page) {
                  my.subtitle1 = v2.title;
                  my.dtltitle1 = v2.dtl;
              }
          } else {
              if (v2.id == my.mbst_page) {
                  my.subtitle1 = v2.title;
              }
          }
      });
  });



    CRUD.detail({ task: "userInfo" }, "GET").then(function (res) {
      if (res.status == 1) {
        my.user = res.data;
        my.userOri_mobile = res.data.mobile;
        my.userOri_email = res.data.email;

        my.user["hasPV"] = parseInt(my.user["pv"]);
        my.user["hasBV"] = parseInt(my.user["bv"]);

        my.user["hasBonus"] = parseInt(my.user["bonus"]);
        my.user["allBonus"] = parseInt(my.user["allBonus"]);
        my.user["rp"] = parseInt(my.user["rp"]);
        my.user["rp_dDate"] = my.user["dDate"];
        my.user["bonusValue"] = parseInt(my.user["bonusValue"]);
      }
    });

    my.jump = function (id) {
      if ($("#" + id).length > 0) {
        $position = $("#" + id).offset().top;
        (speed = 500), // 捲動速度
          ($body = $(document)),
          ($win = $(window));
        $("html, body").animate({ scrollTop: $position }, speed);
        $win.on({
          scroll: function () {
            goTopMove();
          },
          resize: function () {
            goTopMove();
          },
        });
      }
    };

    my.login = function () {
      try {
        var email = my.login.email;
        var passwd = my.login.passwd;
        var err = 0;

        if (!email) {
          error("請輸入信箱或身分證字號");
          err++;
        }
        if (!passwd) {
          error("請輸入密碼");
          err++;
        }

        if (err == 0) {
          localStorage.clear();
          CRUD.update(
            {
              task: "login",
              email: email,
              passwd: passwd,
              routeParams: $routeParams,
            },
            "POST",
            true
          ).then(function (res) {
            console.log(res);
            if (res.status == 1) {
              $scope.$parent.member_status = 1;
              if (res.redirect_url) {
                if (res.redirect == "1") {
                  location.href = res.redirect_url;
                } else {
                  $location.path(res.redirect_url);
                }
              } else {
                console.log(sessionStorage.getItem('returnEvents'));
                if (res.emailChk == "1") {
                  //尚未完成信箱驗證
                  $location.path("mbst_page/info");
                } else if ($location.search().f) {
                  //從商品詳細頁點或活動頁登入帶過來的
                  history.go(-1);
                } else if (sessionStorage.getItem('returnEvents')=='1') {
                  location.href="/index_events.php";
                }else {
                  $location.path("/");
                }
                my.get_card();
                $("#show_card_btn").show();
              }
            } else if (res.status == 9) {
              localStorage.setItem("boss_id", res.m_boss_id);
              localStorage.setItem("phone", res.m_mobile);
              $location.path("mbst_page/signup");
            } else {
              if (res.errMsg.length > 0) {
                error(res.errMsg);
              }
            }
          });
        }
      } catch (e) {}
    };
    my.login_text_fn = function (keyEvent) {
      if (keyEvent.which === 13) {
        my.login();
      }
    };
    
    function checkID(id) {
      tab = "ABCDEFGHJKLMNPQRSTUVXYWZIO";
      A1 = new Array(
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        3,
        3,
        3,
        3,
        3,
        3
      );
      A2 = new Array(
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0,
        1,
        2,
        3,
        4,
        5
      );
      Mx = new Array(9, 8, 7, 6, 5, 4, 3, 2, 1, 1);

      if (id.length != 10) return false;
      i = tab.indexOf(id.charAt(0));
      if (i == -1) return false;
      sum = A1[i] + A2[i] * 9;

      for (i = 1; i < 10; i++) {
        v = parseInt(id.charAt(i));
        if (isNaN(v)) return false;
        sum = sum + v * Mx[i];
      }
      if (sum % 10 != 0) return false;
      return true;
    }

    function checkID2(id) {
      tab = "ABCDEFGHJKLMNPQRSTUVXYWZIO";
      A1 = new Array(
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        1,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        2,
        3,
        3,
        3,
        3,
        3,
        3
      );
      A2 = new Array(
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        0,
        1,
        2,
        3,
        4,
        5
      );
      Mx = new Array(9, 8, 7, 6, 5, 4, 3, 2, 1, 1);

      if (id.length != 10) return false;
      i = tab.indexOf(id.charAt(0));
      if (i == -1) return false;

      i = tab.indexOf(id.charAt(1));
      if (i == -1) return false;

      var idHeader = "ABCDEFGHJKLMNPQRSTUVXYWZIO"; //按照轉換後權數的大小進行排序
      //這邊把身分證字號轉換成準備要對應的
      studIdNumber = id;
      studIdNumber =
        idHeader.indexOf(studIdNumber.substring(0, 1)) +
        10 +
        "" +
        ((idHeader.indexOf(studIdNumber.substr(1, 1)) + 10) % 10) +
        "" +
        studIdNumber.substr(2, 8);
      //開始進行身分證數字的相乘與累加，依照順序乘上1987654321

      s =
        parseInt(studIdNumber.substr(0, 1)) +
        parseInt(studIdNumber.substr(1, 1)) * 9 +
        parseInt(studIdNumber.substr(2, 1)) * 8 +
        parseInt(studIdNumber.substr(3, 1)) * 7 +
        parseInt(studIdNumber.substr(4, 1)) * 6 +
        parseInt(studIdNumber.substr(5, 1)) * 5 +
        parseInt(studIdNumber.substr(6, 1)) * 4 +
        parseInt(studIdNumber.substr(7, 1)) * 3 +
        parseInt(studIdNumber.substr(8, 1)) * 2 +
        parseInt(studIdNumber.substr(9, 1));

      //檢查號碼 = 10 - 相乘後個位數相加總和之尾數。
      checkNum = parseInt(studIdNumber.substr(10, 1));
      //模數 - 總和/模數(10)之餘數若等於第九碼的檢查碼，則驗證成功
      ///若餘數為0，檢查碼就是0
      if (s % 10 == 0 || 10 - (s % 10) == checkNum) {
        return true;
      } else {
        return false;
      }
    }

    my.memberEmailChk = function (index) {
      var memberEmail = my.signNew.memberEmail;
      var err = 0;
      if (!memberEmail) {
        error("請輸入電子信箱");
        err++;
      }
      if (err == 0) {
        //取得推薦人資料
        CRUD.update(
          {
            task: "signNew_memberEmailChk",
            email: memberEmail,
            rec2: my.signNew.re2,
          },
          "POST"
        ).then(function (res) {
          if (res.status == 1) {
            success("驗證通過");
          }
        });
      }
    };


    function ClearAllIntervals() {
      for (var i = 1; i < 99999; i++) window.clearInterval(i);
    }

    my.multileftmenu1 = [
      {
        id: "1",
        name: "經銷商中心",
        child: [
          {
            id: "login",
            name: "經銷商登入／註冊",
            title: "經銷商登入",
            logoutonly: true,
          },
          {
            id: "signup",
            name: "經銷商註冊",
            title: "經銷商註冊",
            logoutonly: true,
            hide: true,
          },
          {
            id: "forgot",
            name: "忘記密碼",
            title: "忘記密碼",
            logoutonly: true,
            hide: true,
          },
          {
            id: "info",
            name: "收件帳戶設定",
            title: "收件帳戶設定",
            loginonly: true,
          },
          {
            id: "spouse_add",
            name: "配偶資料填寫",
            title: "配偶資料填寫",
            loginonly: true,
          },
          {
            id: "m_info",
            name: "經銷商基本資料",
            title: "經銷商基本資料",
            loginonly: true,
          },
          {
            id: "member_news",
            name: "經銷商最新消息",
            title: "經銷商最新消息",
            loginonly: true,
          },
          {
            id: "member_poster",
            name: "實行中優惠方案",
            title: "實行中優惠方案",
            loginonly: true,
          },
          {
            id: "member_news_page",
            name: "經銷商最新消息",
            title: "經銷商最新消息",
            loginonly: true,
            hide: true,
            dtl: "詳細",
          },
          {
            id: "order",
            name: "線上訂單資訊",
            title: "線上訂單資訊",
            loginonly: true,
          },
          {
            id: "orderdtl",
            name: "線上訂單資訊",
            title: "線上訂單資訊",
            loginonly: true,
            hide: true,
            dtl: "詳細",
          },
          {
            id: "mlm_order",
            name: "紙本訂單資訊",
            title: "紙本訂單資訊",
            loginonly: true,
          },
          {
            id: "mlm_orderdtl",
            name: "紙本訂單資訊",
            title: "紙本訂單資訊",
            loginonly: true,
            hide: true,
            dtl: "紙本詳細",
          },
          {
            id: "act_cash_back_list",
            name: "活動回饋點",
            title: "活動回饋點",
            loginonly: true,
          },
          {
            id: "course_list",
            name: "課程報名/查詢",
            title: "課程報名/查詢",
            loginonly: true,
          },
          {
            id: "e_cash",
            name: "海旅點數",
            title: "海旅點數",
            loginonly: true,
          },
          
          {
            id: "e_cash_new2",
            name: "獎勵3S點數",
            title: "獎勵3S點數",
            loginonly: true,
            fin: 1,
            child: [
              {
                id: "e_cash_new2_1",
                name: "獎勵3S點數查詢",
                title: "獎勵3S點數查詢",
                loginonly: true,
              },
              {
                id: "e_cash_new2_2",
                name: "90天到期獎勵3S點數",
                title: "90天到期獎勵3S點數",
                loginonly: true,
              },
              {
                id: "ecash21_dtl",
                name: "獎勵3S點數查詢",
                title: "獎勵3S點數查詢",
                loginonly: true,
                hide: true,
                dtl: "明細",
              },
              {
                id: "ecash22_dtl",
                name: "90天到期獎勵3S點數",
                title: "90天到期獎勵3S點數",
                loginonly: true,
                hide: true,
                dtl: "明細",
              },
            ],
          },
          {
            id: "carry_treasure",
            name: "隨身寶兌換",
            title: "隨身寶兌換",
            loginonly: true,
          },
          {
            id: "e_learning",
            name: "數位學習護照",
            title: "數位學習護照",
            loginonly: true,
          },
          {
            id: "birthday_voucher",
            name: "生日券",
            title: "生日券",
            loginonly: true,
          },
          {
            id: "soybean_voucher",
            name: "醬油券",
            title: "醬油券",
            loginonly: true,
          },
          {
            id: "stock_plan",
            name: "認股通知",
            title: "認股通知",
            loginonly: true,
            limitshow: true,
            hide: true,
          },
          {
            id: "stock_2023",
            name: "2023認股獎勵",
            title: "2023認股獎勵",
            loginonly: true,
            limitshow: true,
            hide: true,
          },
          {
            id: "ecash_stat",
            name: "海旅資格統計",
            title: "海旅資格統計",
            loginonly: true,
          },
          {
            id: "pwchg",
            name: "密碼設定",
            title: "密碼設定",
            loginonly: true,
          },
          {
            id: "logout",
            name: "登出",
            title: "登出",
            loginonly: true,
            fun: "logout()",
          },
        ],
      },
      {
        id: "2",
        name: "業績查詢",
        loginonly: true,
        child: [
          {
            id: "orgseq5",
            name: "業績查詢",
            title: "業績查詢",
            loginonly: true,
          },
          {
            id: "org6",
            name: "滑動業績",
            title: "滑動業績",
            loginonly: true,
          },
          {
            id: "orgseq",
            name: "組織查詢",
            title: "組織查詢",
            loginonly: true,
          },
          {
            id: "money_total",
            name: "獎金查詢",
            title: "獎金查詢",
            loginonly: true,
          },
          {
            id: "moneydtl",
            name: "獎金查詢",
            title: "獎金查詢",
            loginonly: true,
            hide: true,
            dtl: "獎金明細",
          },
          {
            id: "annual_dividend",
            name: "年度分紅",
            title: "年度分紅",
            loginonly: true,
            limitshow: true,
          },
          {
            //JIE
            id: "orgseq_member",
            name: "直推會員",
            title: "直推會員",
            loginonly: true,
          },
        ],
      },
      {
        id:"3",
        name:"健康資訊",
        loginonly:true,
        child:[
          {             
            id: "footpic",
            name: "腳圖查詢",
            title: "腳圖查詢",
            loginonly: true,
          },
          {             
            id: "my_bone_density",
            name: "我的骨密度",
            title: "我的骨密度",
            loginonly: true,
          },
        ]
      },
      {
        id: "4",
        name: "檔案下載",
        loginonly: true,
        child: [
          {
            id: "download_page",
            name: "檔案下載",
            title: "檔案下載",
            loginonly: true,
          },
        ],
      },
      {
        id: "5",
        name: "事業機會",
        loginonly: true,
        child: [
          {
            id: "operation_page",
            name: "事業手冊下載",
            title: "事業手冊下載",
            loginonly: true,
          },
        ],
      },
      {
        id: "6",
        name: "自動配送",
        loginonly: true,
        child: [
          {
            id: "autoBuy_list",
            name: "自動配送申請",
            title: "自動配送申請",
            loginonly: true,
          },
          {
            id: "autoBuy_inquire",
            name: "自動配送查詢",
            title: "自動配送查詢",
            loginonly: true,
            reclick: true,
          },
          {
            id: "autoBuy_ccard",
            name: "查詢/更新信用卡資料",
            title: "查詢/更新信用卡資料",
            loginonly: true,
            limitshow3: true,
          },
        ],
      },
    ];


    angular.forEach(my.multileftmenu1, function (v, k) {
      angular.forEach(v.child, function (v2, k2) {
        if (v2.dtl) {
          if (v2.id == my.mbst_page) {
            my.subtitle1 = v2.title;
            my.dtltitle1 = v2.dtl;
          }
        } else {
          if (v2.id == my.mbst_page) {
            my.subtitle1 = v2.title;
          }
        }
      });
    });


    my.copyStr = function (id) {
      var TextRange = document.createRange();
      TextRange.selectNode(document.getElementById(id));
      sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(TextRange);
      document.execCommand("copy");
    };

    my.goTop = function () {
      (speed = 500), // 捲動速度
        ($body = $(document)),
        ($win = $(window));

      $("html, body").animate({ scrollTop: 0 }, speed);
      $win.on({
        scroll: function () {
          goTopMove();
        },
        resize: function () {
          goTopMove();
        },
      });
    };

    if (my.mbst_page == "bouns_dama") {
      $(".cg-busy").removeClass("ng-hide");
      CRUD.detail({ task: "bouns_dama" }, "POST").then(function (res) {
        //console.log(res);
        if (res.status == 1) {
          my.e_date = res.e_date;
          my.mytrip = res.mytrip;
          console.log('my.mytrip'+my.mytrip);
          console.log(my.mytrip);
        }else{
          //my.e_date = res.e_date;
          my.mytrip.total_pv = 0;
          my.mytrip.intro_count = 0;
          console.log('my.mytrip'+my.mytrip);
          console.log(my.mytrip);
        }
      });
      $(".cg-busy").addClass("ng-hide");
    }

    if (my.mbst_page == "pco") {
      $(".cg-busy").removeClass("ng-hide");
      CRUD.detail({ task: "pco" }, "POST").then(function (res) {
        //console.log(res);
        if (res.status == 1) {
          my.e_date = res.e_date;
          my.pcodata = res.pcodata;
          console.log('my.pcodata'+my.pcodata);
          console.log(my.pcodata);
        }else{
          //my.e_date = res.e_date;
          my.pcodata.total_pv = 0;
          my.pcodata.intro_count = 0;
          console.log('my.pcodata'+my.pcodata);
          console.log(my.pcodata);
        }
      });
      $(".cg-busy").addClass("ng-hide");
    }

    my.copyStr = function (id) {
      var TextRange = document.createRange();
      TextRange.selectNode(document.getElementById(id));
      sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(TextRange);
      document.execCommand("copy");
    };

    my.spouseChk = function (index) {
      $scope.sub_form = true;
      var ms_set = my.ms_set ? my.ms_set : "";
      var ms_erpid = my.ms_erpid ? my.ms_erpid : "";
      var ms_name = my.ms_name ? my.ms_name : "";
      var ms_cellphone = my.ms_cellphone ? my.ms_cellphone : "";
      var ms_phone = my.ms_phone ? my.ms_phone : "";
      var ms_birthday = my.ms_birthday ? my.ms_birthday : "";
      var ms_relation = my.ms_relation ? my.ms_relation : "";
      var ms_sid = my.ms_sid ? my.ms_sid : "";


      var img1 = $scope.previewImage;
      var img2 = $scope.previewImage2;


      var err=0;
      
      if (!ms_name) {
        error("請輸入配偶姓名");
        err++;
      }
      if (!ms_relation) {
        error("請選擇配偶關係");
        err++;
      }
      if (!ms_sid) {
        error("請輸入配偶身分證字號");
        err++;
      } else {
        ms_sid = ms_sid.toUpperCase();

        if (!checkID(ms_sid) && !checkID2(ms_sid)) {
          error("身分證字號或居留證輸入格式錯誤");
          err++;
        }
      }

      if (ms_phone) {
        var numberRegxp = /^(\d{2,4})-\d{6,8}$/;
        var numberRegxp2 = /^(\d{2,4})\d{6,8}$/;
        if (numberRegxp.test(ms_phone) != true && numberRegxp2.test(ms_phone) != true) {
          error("電話號碼輸入格式錯誤（請填寫數字即可）");
          err++;
        }
      }


        if (!ms_cellphone && !ms_phone) {
          error("請輸入手機號碼或電話號碼");
          err++;
        }
        if(ms_cellphone){
        var numberRegxp1 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
        var numberRegxp2 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
        if (
          numberRegxp2.test(ms_cellphone) != true &&
          numberRegxp1.test(ms_cellphone) != true
        ) {
          error("手機號碼輸入格式錯誤（請填寫數字即可）");
          err++;
        }
        }


      if (!ms_birthday) {
        error("請輸入生日(西元年)");
        err++;
      }

      if (!img1 || img1 == "") {
        error("請上傳身分證正面");
        err++;
      }

      if (!img2 || img2 == "") {
        error("請上傳身分證反面");
        err++;
      }
      CRUD.setUrl("components/mbst/api.php");
      CRUD.list(
        {
          task: "sid_chk",
          ms_sid: ms_sid,          
        },
        "POST",
        true
      ).then(function (res) {
        if (res.status == 1) {
          error(res.msg);
          err++;
        }
      });        


      if (err == 0) {
        ms_birthdaystr = [
          my.ms_birthday.getFullYear(),
          my.ms_birthday.getMonth() + 1,
          my.ms_birthday.getDate(),
        ].join("-");

          
          $timeout(function () {
            CRUD.setUrl("components/mbst/api.php");
            CRUD.update(
              {
                task: "spouse_add",
                ms_set: ms_set,
                ms_erpid: ms_erpid,
                ms_name: ms_name,
                ms_cellphone: ms_cellphone,
                ms_phone: ms_phone,
                ms_birthday: ms_birthdaystr,
                ms_relation: ms_relation,
                ms_sid: ms_sid,          
                img1: img1,
                img2: img2
              },
              "POST",
              true
            ).then(function (res) {
              if (res.status == 1) {
                  // $('#myModal_CHKPAY').modal('show');
                  $location.path("member_page/info");
                  success("申請完成，配偶資料審核中");
              } else if (res.status == "100") {
                console.log(res);
              } else {
                error(res.msg);
                $route.reload();
              }
            });
          }, 200);
        
      }else{
          $scope.sub_form = false;
      }
    };
    my.chkerpid = function (keyEvent) {
      var ms_erpid = my.ms_erpid ? my.ms_erpid : "";
      if(ms_erpid){
      CRUD.setUrl("components/mbst/api.php");
      CRUD.list({task: "spouse_chk", id: ms_erpid}, "GET").then(function (res) {
          if (res.status == 1) {
          my.ms_name = res.data.mb_name;
          my.ms_cellphone = res.data.tel3;
          my.ms_phone = res.data.tel2;
          my.ms_birthday = new Date(res.data.birthday2);;
          my.ms_sid = res.data.boss_id;
          console.log(res);
        }else{
            error(res.msg);
            my.ms_erpid = "";
          }
        });
      }
    };

    CRUD.setUrl("components/member/api.php");


  },
]);
