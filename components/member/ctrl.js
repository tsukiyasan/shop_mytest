app.controller('member_page', ['$rootScope', '$scope', '$http', '$location', '$route', '$routeParams', '$translate', 'CRUD', '$filter', 'fbLogin', 'gpLogin', '$timeout', '$sce', '$compile', 'sessionCtrl', function ($rootScope, $scope, $http, $location, $route, $routeParams, $translate, CRUD, $filter, fbLogin, gpLogin, $timeout, $sce, $compile, sessionCtrl) {
    var my = this;
    my.currency = sessionCtrl.get("_currency");
    CRUD.setUrl("components/member/api.php");
    my.isIE11 = !!window.MSInputMethodContext && !!document.documentMode;
    my.resertpw = {};
    my.member_page = $routeParams.type;
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

    // var ICregex = /^(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/;

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


    my.sign = {};
    my.login = {};
    my.sign20 = {};
    // my.sign20.signupMode = 'MAIL'
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
    my.imagelist = [];
    for (var i = 1; i <= imagelimit; i++) {
        my.imagelist.push(i);
    }


    my.multileftmenu = [
        {
            id: '1',
            name: $translate.instant('lg_member.member'),
            active: "active",
            child: [
                {
                    id: "login",
                    name: $translate.instant('lg_member.member_login_singup'),
                    title: $translate.instant('lg_member.login'),
                    logoutonly: true
                },
                {
                    id: "signup",
                    name: $translate.instant('lg_member.signup'),
                    title: $translate.instant('lg_member.signup'),
                    logoutonly: true,
                    hide: true
                },
                {
                    id: "forgot",
                    name: $translate.instant('lg_member.forgot'),
                    title: $translate.instant('lg_member.forgot'),
                    logoutonly: true,
                    hide: true
                },
                {
                    id: "info",
                    name: $translate.instant('lg_member.info'),
                    title: $translate.instant('lg_member.info'),
                    loginonly: true
                },
                {
                    id: "m_info",
                    name: $translate.instant('lg_member.member_profile'),
                    title: $translate.instant('lg_member.member_profile'),
                    loginonly: true
                },
                {
                    id: "m_points",
                    name: $translate.instant('lg_member.member_points'),
                    title: $translate.instant('lg_member.member_points'),
                    loginonly: true
                },
                {
                    id: "member_news",
                    name: $translate.instant('lg_member.member_news'),
                    title: $translate.instant('lg_member.member_news'),
                    loginonly: true
                },
                {
                    id: "member_news_page",
                    name: "會員最新消息",
                    title: "會員最新消息",
                    loginonly: true,
                    hide: true,
                    dtl: "詳細"
                },
                {
                    id: "order",
                    name: $translate.instant('lg_member.order'),
                    title: $translate.instant('lg_member.order'),
                    loginonly: true
                },
                {
                    id: "orderdtl",
                    name: $translate.instant('lg_member.order_dtl'),
                    title: $translate.instant('lg_member.order_dtl'),
                    loginonly: true,
                    hide: true,
                    dtl: $translate.instant('lg_member.order_dtl')
                },
                {
                    id: "e_cash",
                    name: $translate.instant('lg_member.travel_point'),
                    title: $translate.instant('lg_member.travel_point'),
                    loginonly: true
                },
                {
                    id: "pwchg",
                    name: $translate.instant('lg_member.pwd_set'),
                    title: $translate.instant('lg_member.pwd_set'),
                    loginonly: true
                },
                {
                    id: "logout",
                    name: $translate.instant('lg_member.logout_btn'),
                    title: $translate.instant('lg_member.logout_btn'),
                    loginonly: true,
                    fun: 'logout()'
                }
            ]
        }, {
            id: '2',
            name: $translate.instant('lg_member.performance_inquiry'),
            loginonly: true,
            child: [
                {
                    id: "orgseq5",
                    name: $translate.instant('lg_member.performance_inquiry'),
                    title: $translate.instant('lg_member.performance_inquiry'),
                    loginonly: true,
                }, {
                    id: "orgseq",
                    name: $translate.instant('lg_member.organization_inquiry'),
                    title: $translate.instant('lg_member.organization_inquiry'),
                    loginonly: true,
                },
                {
                    id: "money_total",
                    name: $translate.instant('lg_member.bonus_inquiry'),
                    title: $translate.instant('lg_member.bonus_inquiry'),
                    loginonly: true
                },
                {
                    id: "moneydtl",
                    name: "獎金查詢",
                    title: "獎金查詢",
                    loginonly: true,
                    hide: true,
                    dtl: "獎金明細"
                }
            ]
        }, {
            id: "4",
            name: $translate.instant('lg_member.file_download'),
            loginonly: true,
            child: [
                {
                    id: "download_page",
                    name: $translate.instant('lg_member.file_download'),
                    title: $translate.instant('lg_member.file_download'),
                    loginonly: true
                }
            ]
        }, {
            id: "5",
            name: $translate.instant('lg_member.business_manuals'),
            loginonly: true,
            child: [
                {
                    id: "operation_page",
                    name: $translate.instant('lg_member.business_manuals'),
                    title: $translate.instant('lg_member.business_manuals'),
                    loginonly: true
                }
            ]
        }
    ];

    angular.forEach(my.multileftmenu, function (v, k) {
        angular.forEach(v.child, function (v2, k2) {
            if (v2.dtl) {
                if (v2.id == my.member_page) {
                    my.subtitle = v2.title;
                    my.dtltitle = v2.dtl;
                }
            } else {
                if (v2.id == my.member_page) {
                    my.subtitle = v2.title;
                }
            }
        });
    });

    my.multileftmenu1 = [{
        id: '1',
        name: $translate.instant('lg_member.distributor_center'),
        child: [{
            id: "login",
            name: $translate.instant('lg_member.member_login_singup'),
            title: $translate.instant('lg_member.login'),
            logoutonly: true
        },
        {
            id: "signup",
            name: $translate.instant('lg_member.signup'),
            title: $translate.instant('lg_member.signup'),
            logoutonly: true,
            hide: true
        },
        {
            id: "forgot",
            name: $translate.instant('lg_member.forgot'),
            title: $translate.instant('lg_member.forgot'),
            logoutonly: true,
            hide: true
        },
        {
            id: "info",
            name: $translate.instant('lg_member.info'),
            title: $translate.instant('lg_member.info'),
            loginonly: true
        },
        {
            id: "m_info",
            name: $translate.instant('lg_member.member_profile'),
            title: $translate.instant('lg_member.member_profile'),
            loginonly: true
        },
        {
            id: "m_points",
            name: $translate.instant('lg_member.distributor_points'),
            title: $translate.instant('lg_member.distributor_points'),
            loginonly: true,
            hide:true
        },
        {
            id: "member_news",
            name: $translate.instant('lg_member.member_news'),
            title: $translate.instant('lg_member.member_news'),
            loginonly: true
        },
        {
            id: "member_poster",
            // name: "實行中優惠方案",
            // title: "實行中優惠方案",
            name: $translate.instant('lg_member.Ongoing_Promotions'),
            title: $translate.instant('lg_member.Ongoing_Promotions'),
            loginonly: true
            // hide: true
        },
        {
            id: "member_news_page",
            name: $translate.instant('lg_member.member_news_page'),
            title: $translate.instant('lg_member.member_news_page'),
            loginonly: true,
            hide: true,
            dtl: "詳細"
        },
        {
            id: "order",
            name: $translate.instant('lg_member.order'),
            title: $translate.instant('lg_member.order'),
            loginonly: true
        },
        {
            id: "orderdtl",
            name: $translate.instant('lg_member.order_dtl'),
            title: $translate.instant('lg_member.order_dtl'),
            loginonly: true,
            hide: true,
            dtl: $translate.instant('lg_member.order_dtl')
        },
        {
            id: "mlm_order",
            name: $translate.instant('lg_member.mlm_order'),
            title: $translate.instant('lg_member.mlm_order'),
            // name: "紙本訂單資訊",
            // title: "紙本訂單資訊",
            loginonly: true
        },
        {
            id: "mlm_orderdtl",
            name: $translate.instant('lg_member.mlm_order_dtl'),
            title: $translate.instant('lg_member.mlm_order_dtl'),
            loginonly: true,
            hide: true,
            dtl: $translate.instant('lg_member.mlm_order_dtl'),
        },
        {
            id: "e_cash",
            name: $translate.instant('lg_member.travel_point'),
            title: $translate.instant('lg_member.travel_point'),
            loginonly: true
        },
        {
            id: "e_cash_new2",
            name: $translate.instant('lg_member.3S_reward_points'),
            title: $translate.instant('lg_member.3S_reward_points'),
            loginonly: true,
            fin: 1,
            child: [{
                id: "e_cash_new2_1",
                name: $translate.instant('lg_member.3S_reward_points'),
                title: $translate.instant('lg_member.3S_reward_points'),
                loginonly: true,
            }, {
                id: "e_cash_new2_2",
                name: $translate.instant('lg_member.90_3S_reward_points_inquire'),
                title: $translate.instant('lg_member.90_3S_reward_points_inquire'),
                loginonly: true
            }, {
                id: "ecash21_dtl",
                name: "獎勵3S點數查詢",
                title: "獎勵3S點數查詢",
                loginonly: true,
                hide: true,
                dtl: "明細"
            }, {
                id: "ecash22_dtl",
                name: "90天到期獎勵3S點數",
                title: "90天到期獎勵3S點數",
                loginonly: true,
                hide: true,
                dtl: "明細"
            }]
        },
        {
          id: "carry_treasure",
          name: $translate.instant('lg_member.health_guard_redemption'),
          title: $translate.instant('lg_member.health_guard_redemption'),
          loginonly: true,
        },
        {
          id: "birthday_voucher",
          name: $translate.instant('lg_member.birthday_voucher_redemption'),
          title: $translate.instant('lg_member.birthday_voucher_redemption'),
          loginonly: true,
        },
        {
            id: "pwchg",
            name: $translate.instant('lg_member.pwd_set'),
            title: $translate.instant('lg_member.pwd_set'),
            loginonly: true
        },
        {
            id: "logout",
            name: $translate.instant('lg_member.logout_btn'),
            title: $translate.instant('lg_member.logout_btn'),
            loginonly: true,
            fun: 'logout()'
        }
        ]
    }, {
        id: '2',
        name: $translate.instant('lg_member.performance_inquiry'),
        loginonly: true,
        child: [{
            id: "orgseq5",
            name: $translate.instant('lg_member.performance_inquiry'),
            title: $translate.instant('lg_member.performance_inquiry'),
            loginonly: true,
        }, {
            id: "orgseq",
            name: $translate.instant('lg_member.organization_inquiry'),
            title: $translate.instant('lg_member.organization_inquiry'),
            loginonly: true,
        },
        {
            id: "money_total",
            name: $translate.instant('lg_member.bonus_inquiry'),
            title: $translate.instant('lg_member.bonus_inquiry'),
            loginonly: true
        },
        {
            id: "moneydtl",
            name: $translate.instant('lg_member.bonus_inquiry'),
            title: $translate.instant('lg_member.bonus_inquiry'),
            loginonly: true,
            hide: true,
            dtl: $translate.instant('lg_member.bonus_dtl'),
        },
        {
          //JIE
          id: "orgseq_member",
          name: $translate.instant('lg_member.direct_sponsored_members'),
          title: $translate.instant('lg_member.direct_sponsored_members'),
          loginonly: true,
        },
        ]
    }, {
        id: "4",
        name: $translate.instant('lg_member.file_download'),
        loginonly: true,
        child: [{
            id: "download_page",
            name: $translate.instant('lg_member.file_download'),
            title: $translate.instant('lg_member.file_download'),
            loginonly: true
        }]
    }, {
        id: "5",
        name: $translate.instant('lg_member.business_manuals'),
        loginonly: true,
        child: [{
            id: "operation_page",
            name: $translate.instant('lg_member.business_manuals'),
            title: $translate.instant('lg_member.business_manuals'),
            loginonly: true
        }]
    }];

    angular.forEach(my.multileftmenu1, function (v, k) {
        angular.forEach(v.child, function (v2, k2) {
            if (v2.dtl) {
                if (v2.id == my.member_page) {
                    my.subtitle1 = v2.title;
                    my.dtltitle1 = v2.dtl;
                }
            } else {
                if (v2.id == my.member_page) {
                    my.subtitle1 = v2.title;
                }
            }
        });
    });



    my.multileftmenu2 = [{
        id: '1',
        name: $translate.instant('lg_member.member_center'),
        child: [{
            id: "login",
            name: "會員登入／註冊",
            title: "會員登入",
            logoutonly: true
        },
        {
            id: "signup",
            name: "會員e化註冊",
            title: "會員e化註冊",
            logoutonly: true,
            hide: true
        },
        {
            id: "forgot",
            name: "忘記密碼",
            title: "忘記密碼",
            logoutonly: true,
            hide: true
        },
        {
            id: "info",
            name: $translate.instant('lg_member.details_setting'),
            title: $translate.instant('lg_member.details_setting'),
            loginonly: true
        },
        {
            id: "m_info",
            name: $translate.instant('lg_member.Member_Information'),
            title: $translate.instant('lg_member.Member_Information'),
            loginonly: true
        },
        {
            id: "member_news",
            name: $translate.instant('lg_member.Member_Latest_News'),
            title: $translate.instant('lg_member.Member_Latest_News'),
            loginonly: true
        },
        {
            id: "member_poster",
            name: $translate.instant('lg_member.Ongoing_Promotions'),
            title: $translate.instant('lg_member.Ongoing_Promotions'),
            loginonly: true
        },
        {
            id: "order",
            name: $translate.instant('lg_member.order'),
            title: $translate.instant('lg_member.order'),
            loginonly: true
        },
        {
            id: "orderdtl",
            name: $translate.instant('lg_member.order'),
            title: $translate.instant('lg_member.order'),
            loginonly: true,
            hide: true,
            dtl: "詳細"
        },
        {
            id: "mlm_order",
            name: $translate.instant('lg_member.Forms_Information'),
            title: $translate.instant('lg_member.Forms_Information'),
            loginonly: true
        },
        {
            id: "mlm_orderdtl",
            name: $translate.instant('lg_member.Forms_Information'),
            title: $translate.instant('lg_member.Forms_Information'),
            loginonly: true,
            hide: true,
            dtl: "紙本詳細"
        },
        {
            id: "pwchg",
            name: $translate.instant('lg_member.pwd_set'),
            title: $translate.instant('lg_member.pwd_set'),
            loginonly: true
        },
        {
            id: "logout",
            name: $translate.instant('lg_member.logout_btn'),
            title: $translate.instant('lg_member.logout_btn'),
            loginonly: true,
            fun: 'logout()'
        }
        ]
    }, {
        id: '2',
        name: $translate.instant('lg_member.My_Benefits'),
        loginonly: true,
        child: [{
            id: "m_points",
            name: $translate.instant('lg_member.Cash_Points_Inquiry'),
            title: $translate.instant('lg_member.Cash_Points_Inquiry'),
            loginonly: true
        }, {
            id: "cash_back_list",
            name: $translate.instant('lg_member.Online_Rebate_Points_Inquiry'),
            title: $translate.instant('lg_member.Online_Rebate_Points_Inquiry'),
            loginonly: true
        }, {
            id: "sp_bonus",
            name: $translate.instant('lg_member.Special_Reward'),
            title: $translate.instant('lg_member.Special_Reward'),
            loginonly: true
        }]
    }, {
        id: "4",
        name: $translate.instant('lg_member.doc_download'),
        loginonly: true,
        child: [{
            id: "download_page",
            name: $translate.instant('lg_member.doc_download'),
            title: $translate.instant('lg_member.doc_download'),
            loginonly: true
        }]
    }
        // , {
        //     id: "5",
        //     name: "事業手冊",
        //     loginonly: true,
        //     hide:true,
        //     child: [{
        //         id: "operation_page",
        //         name: "事業手冊下載",
        //         title: "事業手冊下載",
        //         loginonly: true,
        //         hide:true
        //     }]
        // }
        , {
        id: "6",
        name: $translate.instant('lg_member.update_to_Distributor'),
        loginonly: true,
        child: [{
            id: "update_member",
            name: $translate.instant('lg_member.update_to_Distributor'),
            title: $translate.instant('lg_member.update_to_Distributor'),
            loginonly: true
        }]
    }];

    angular.forEach(my.multileftmenu2, function (v, k) {
        angular.forEach(v.child, function (v2, k2) {
            if (v2.dtl) {
                if (v2.id == my.member_page) {
                    my.subtitle2 = v2.title;
                    my.dtltitle2 = v2.dtl;
                }
            } else {
                if (v2.id == my.member_page) {
                    my.subtitle2 = v2.title;
                }
            }
        });
    });




    CRUD.detail({ task: "loginStatus" }, "GET").then(function (res) {
        if (res.status == 1) {
            $scope.$parent.member_status = parseInt(res.data);
            $scope.$parent.om = parseInt(res.onlymember);
            $scope.$parent.signupMode2020 = res.signupMode2020;
            my.uloginType = res.uloginType;
            $('#show_card_btn').show();
            if ((!my.member_page || my.member_page == "login" || my.member_page == "forgot" || my.member_page == "resetPW") && $scope.$parent.member_status == 1) {
                $location.path("member_page/info");
            } else if ((!my.member_page || my.member_page == "info" || my.member_page == "m_info" || my.member_page == "pwchg" || my.member_page == "order" || my.member_page == "orderdtl" || my.member_page == "likeproduct" || my.member_page == "money_total" || my.member_page == "moneydtl" || my.member_page == "e_cash" || my.member_page == "e_cash_new2_1" || my.member_page == "e_cash_new2_2" || my.member_page == "ecash21_dtl" || my.member_page == "ecash22_dtl" || my.member_page == "member_news_page" || my.member_page == "orgseq" || my.member_page == "orgseq5" || my.member_page == "birthday_voucher" || my.member_page == "soybean_voucher" || my.member_page == "carry_treasure" || my.member_page == "register_tb_list") && $scope.$parent.member_status == 0) {
                $('.cg-busy').addClass('ng-hide');
                my.member_page = 'login';
                $location.path("member_page/login");
            }

            if (res.uid_status == 0) {
                $('.cg-busy').addClass('ng-hide');
                my.member_page = 'login';
                $location.path("member_page/login");
            }

            if (res.has_precode == '1') {
                localStorage.setItem('precode', res.precode);
            }

            if(res.onlyMember == '1'){
                $scope.om = parseInt(res.onlymember);
            }
        }
        console.log(my.member_page);
    });


    my.fb_login = function () {
        fbLogin.getUserId();
    };
    my.gp_login = function () {
        gpLogin.getUserId();
    };
    my.jump = function (id) {
        if ($('#' + id).length > 0) {
            $position = $('#' + id).offset().top;
            speed = 500, // 捲動速度
                $body = $(document),
                $win = $(window);
            $("html, body").animate({ scrollTop: $position }, speed);
            $win.on({
                scroll: function () { goTopMove(); },
                resize: function () { goTopMove(); }
            });
        }


    }

    my.exchange_coupon = function (no) {
        CRUD.update({ task: "exchange_coupon", coupon_no: no }, "POST").then(function (res) {
            if (res.success) {
                if (my.ct_yy_select.length > 0) {
                    my.carry_treasure_fn(my.ct_yy_select.id);
                } else {
                    my.carry_treasure_fn();
                }

                success("兌換申請成功,請至門市領取");
                $route.reload();
            } else {
                error("兌換申請失敗,查無此兌換券");
                $route.reload();
            }
        });
    }
    my.login = function () {
        try {
            var email = my.login.email;
            var passwd = my.login.passwd;
            var err = 0;

            if (!email) {
                error($translate.instant('lg_member.js_please_loginid'));
                err++;
            }
            if (!passwd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }

            if (err == 0) {
                CRUD.update({ task: "login", email: email, passwd: passwd }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        console.log(res);
                        $scope.$parent.member_status = 1;
                        if (res.redirect_url) {
                            $location.path(res.redirect_url);
                        } else {

                            if (res.emailChk == '1') {
                                //尚未完成信箱驗證
                                $location.path("member_page/info");
                            } else if ($location.search().f) {
                                //從商品詳細頁點或活動頁登入帶過來的
                                history.go(-1);
                            } else {
                                // $location.path("/");
                                // console.log('do somethig');
                                // $route.reload();
                                location.href = '/';
                            }
                            my.get_card();
                            $('#show_card_btn').show();
                            
                        }
                    } else if (res.status == 9) {
                        localStorage.setItem('boss_id', res.m_boss_id);
                        localStorage.setItem('phone', res.m_mobile);
                        $location.path("member_page/signup");
                    } else {
                        if (res.errMsg.length > 0) {
                            error(res.errMsg);
                        }

                    }
                });

                my.loginStatus();

            }
        } catch (e) { }
    };
    my.login_text_fn = function (keyEvent) {
        if (keyEvent.which === 13) {

            my.login();
        }
    };

    my.get_card = function () {
        CRUD.setUrl("app/controllers/eways.php");
        CRUD.list({ task: "get_card" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.card_om = res.om;
                my.barcode_64 = 1;

                $('#show_card_btn').show();
                $('#card_svg').append(res.barcode_64);
                if (res.om == '0') {
                    $('.card_title').text('經銷商');
                    $('.n_center').text($translate.instant('lg_member.js_please_old_pwd'));
                    $('#card_qr1i').append(res.qr1);
                    $('#card_qr2i').append(res.qr2);
                } else {
                    $('.card_title').text('會員');
                    $('.n_center').text($translate.instant('lg_member.js_please_old_pwd'));
                    $('#card_choice').hide();
                }
                $('#card_name').append(res.mb_name);


                var $width = $(window).width();
                var $height = $(window).height();
                if ($width > $height) {
                    $('#card_svg').removeClass('degree_90');
                    $('#card_svg').removeClass('d_center');
                    $('#vip_card').hide();
                    // $('#show_card .modal-footer').hide();
                    $('#card_svg').find('svg').css('width', '100%');
                    $('#card_svg').find('svg').css('height', '170px');
                    $('svg').attr('viewBox', '0 0 154 100');
                    if ($height < 500) {
                        $('#show_card .modal-footer').hide();
                    }
                }
                else if ($width < $height) {
                    // $('#card_svg').addClass('degree_90');
                    $('#card_svg').addClass('d_center');
                    $('#vip_card').show();
                    $('#show_card .modal-footer').show();
                    $('#card_svg').find('svg').css('width', '100%');
                    $('#card_svg').find('svg').css('height', '170px');
                    $('svg').attr('viewBox', '0 0 154 100');
                    if ($height < 500) {
                        $('#show_card .modal-footer').hide();
                    }
                } else {
                    // $('#card_svg').addClass('degree_90');
                    $('#card_svg').addClass('d_center');
                    $('#vip_card').show();
                    $('#show_card .modal-footer').show();
                    $('#card_svg').find('svg').css('width', '100%');
                    $('#card_svg').find('svg').css('height', '170px');
                    $('svg').attr('viewBox', '0 0 154 100');
                    if ($height < 500) {
                        $('#show_card .modal-footer').hide();
                    }
                }
            } else {
                $('#show_card_btn').hide();
            }
        });
        CRUD.setUrl("components/member/api.php");
    }
    my.pwchg_fn = function () {
        try {
            var opasswd = my.pwchg.opasswd;
            var passwd = my.pwchg.passwd;
            var passwd2 = my.pwchg.passwd2;
            var err = 0;

            if (!opasswd) {
                error($translate.instant('lg_member.js_please_old_pwd'));
                err++;
            }
            if (!passwd) {
                error($translate.instant('lg_member.js_please_new_pwd'));
                err++;
            }
            if (passwd.length < 6) {
                error($translate.instant('lg_member.js_pwd_msg1'));
                err++;
            }
            if (!passwd2) {
                err++;
            }
            if (passwd != passwd2) {
                error($translate.instant('lg_member.js_pwd_msg2'));
                err++;
            }

            if (err == 0) {
                CRUD.update({ task: "pwchg", opasswd: opasswd, passwd: passwd }, "POST").then(function (res) {
                    if (res.status == 1) {
                        $location.path("member_page/info");
                        success($translate.instant('lg_member.js_edit_success'));
                    }
                });
            }
        } catch (e) { }
    };

    //新經銷商系統------------------------------------------------------------
    my.signNew = {};
    if (my.member_page == "signup") {
        console.log(my.sign30);
        if ($scope.member_status != 0) {
            CRUD.update({ task: "logout" }, "POST").then(function (res) {
                $scope.member_status = 0;

            });
        }
        my.has_mem = '0';
        my.sign.sid = localStorage.getItem('boss_id');
        my.sign.phone = localStorage.getItem('phone');

        my.signNew.rec = $routeParams.rec;
        my.signNew.rec2 = $routeParams.rec2;
        my.signNew.mem = $routeParams.mem;
        my.signNew.l = $routeParams.l;

        my.signNew.rec_code = $routeParams.code;
        if (my.signNew.rec_code) {
            CRUD.update({ task: "sign_codeChk", code: my.signNew.rec_code, mb_no: my.signNew.rec }, "POST", true).then(function (res) {
                if (res.status == '1') {
                    localStorage.setItem('temp_email', res.data.email);
                    localStorage.setItem('rec_code', res.data.random);
                    console.log(localStorage);
                } else {
                    my.member_page = 'login';
                    $location.url("member_page/login");
                    error($translate.instant('lg_member.code_error'));
                }
            });
        }



        if (my.signNew.rec && !my.signNew.mem) {
            console.log('HERE4');
            my.member_page = "signupNew1";

            if (my.signNew.rec2) {
                my.member_page = "signupNew2";
            } else {
                $routeParams.re = $routeParams.rec;

                //my.member_page = "signupNew3"; //改為先驗證頁面
                my.sign20.signupMode = 'SMS';
                my.member_page = "signup3011";
            }
        } else {
            my.sign20.sid = localStorage.getItem('boss_id');
            // console.log(my.sign20.sid);
            if (my.sign20.sid == null) {
                my.member_page = "signup300";
            } else {
                var sid = my.sign20.sid;
                my.sign20.signupMode = 'SMS';
                // my.member_page = "signup201";
                CRUD.update({ task: "sign20_signupChk", sid: sid }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        console.log(res.data);
                        my.sign20.memberName = res.data.name;
                        my.sign20.memberSID = res.data.sid;
                        my.sign20.memberCity = res.data.city;
                        my.sign20.memberCanton = res.data.canton;
                        my.sign20.memberCardno = res.data.cardno;
                        my.sign20.memberPhone = my.sign.phone;
                        my.member_page = "signup201";
                    } else {
                        my.member_page = 'login';
                        $location.url("member_page/login");
                    }
                });
                localStorage.removeItem('boss_id');
            }

        }

        my.signNew.re = $routeParams.re;
        my.sign300 = $routeParams.t;
        my.no_re = $routeParams.nr;
        my.signNew.re2 = $routeParams.re2;

        if (my.signNew.re) {
            my.member_page = "signupNew3";
            if (my.sign300 == 1) {
                my.sign20.signupMode = $routeParams.m;
                my.member_page = 'signup301';
                // my.sign300 = 0;
                console.log(my.no_re);
            } else if (!my.sign300) {
                my.sign20.signupMode = 'SMS';
                my.member_page = "signup3012";
            }

        }

        my.signNew.signupMode = $routeParams.m;

        if (!my.signNew.signupMode && my.signNew.re) {
            my.signNew.re = (my.signNew.re) ? my.signNew.re : '';
            my.signNew.re2 = (my.signNew.re2) ? my.signNew.re2 : '';
            my.signNew.l = (my.signNew.l) ? my.signNew.l : '';
            $location.url("member_page/signup203?re=" + my.signNew.re + "&re2=" + my.signNew.re2 + "&l=" + my.signNew.l);

        }

        if (my.signNew.mem == '1') {
            my.sign30.eid = $routeParams.rec;

            my.member_page = 'signup300';
            my.has_mem = '1';
        } else {
            my.signNew.mem = '0';
            my.has_mem = '0';
        }
        console.log(my.has_mem);

        if (my.signNew.re) {
            //取得推薦人資料
            // console.log(my);
            my.signNew.code = localStorage.getItem('rec_code');

            CRUD.list({ task: "signNew_getRecData", rec: my.signNew.re, rec2: my.signNew.re2 }, "POST", true).then(function (res) {

                if (res.status == 1) {
                    my.signNew.referrerNo = res.info.referrerNo;
                    my.signNew.referrerName = res.info.referrerName;
                    my.signNew.referrerTel = res.info.referrerTel;
                    my.signNew.referrerPhone = res.info.referrerPhone;

                    my.signNew.memberNo = res.info.memberNo;
                    my.signNew.memberName = res.info.memberName;
                    my.signNew.memberNameChk = (res.info.memberName) ? true : false;
                    my.signNew.memberSID = res.info.memberSID;
                    my.signNew.memberAddress = res.info.memberAddress;
                    my.signNew.memberResAddress = res.info.memberResAddress;
                    my.signNew.memberTel = res.info.memberTel;
                    my.signNew.memberPhone = res.info.memberPhone;
                    my.signNew.memberEmail = res.info.memberEmail;

                    my.signNew.memberBirthday = (res.info.memberBirthday) ? new Date(res.info.memberBirthday + 'T00:00:00.000Z') : "";
                    my.signNew.memberBirthday = "";

                    my.signNew.memberWNo = res.info.memberWNo;
                    if (my.signNew.memberWNo) {
                        my.signNew.usedChk = true;
                    }

                    var nmemberPhone = localStorage.getItem('memberPhone2');
                    var nmemberEmail = localStorage.getItem('memberMail2');
                    var nmemberCaptcha = localStorage.getItem('memberCaptcha2');

                    my.signNew.memberPhone = nmemberPhone;
                    my.signNew.memberEmail = nmemberEmail;
                    my.signNew.memberCaptcha = nmemberCaptcha;

                    // localStorage.removeItem('memberCaptcha');
                    // localStorage.removeItem('memberPhone');

                    //20200309 文字調整
                    my.ESignupActiveChk = res.info.ESignupActiveChk;

                } else {
                    alert($translate.instant('lg_member.js_msg1'))
                        .set('onok', function (closeEvent) {
                            if (my.signNew.mem == '1') {
                                $location.path("member_page/signup");
                                try {
                                    $scope.$apply();
                                } catch (e) {

                                }
                            } else {
                                $location.path("member_page/signup203");
                                try {
                                    $scope.$apply();
                                } catch (e) {

                                }
                            }

                            //location.href="http://www.goodarch2u.com.tw/tw/index.php";
                        });
                }
            });
        }

        //預設值
        my.signNew.payType = 6;
        my.signNew.dlvrType = 1;

        my.payTypeChange = function (index) {
            if (index == 5) {
                my.signNew.dlvrType = '2';
            } else {
                my.signNew.dlvrType = '1';
            }
        }

    }

    if (my.member_page == "paySuccess") {
        my.first = {};
        my.user = {};

        my.first.loginId = $routeParams.m1;
        my.first.passwd = $routeParams.m2;
        my.first.rec = $routeParams.m3;
        my.user.email = $routeParams.m4;
        my.user.emailChk = $routeParams.m5;

        ////檢查身分證
        //CRUD.update({task: "signNew_getMemberData"}, "POST", true).then(function(res){			
        //	if(res.status == 1) {
        //		my.first.loginId = res.loginId;
        //		my.first.passwd = res.passwd;
        //		my.first.rec = res.rec;
        //		my.user.email = res.email;
        //	}
        //});
    }

    $scope.$watch('ctrl.same_member_info', function (value) {

        if (my.signNew) {
            if (value) {
                
                var memberCity = my.signNew.memberCity ? my.signNew.memberCity.id : '';
                memberCity = memberCity ? memberCity-1 : '';
                console.log(my.city);
                console.log(memberCity);
                console.log(my.signNew.memberCity);
                memberCityStr = my.city[memberCity].state_u;
                // memberCityStr = my.city[memberCity].name;
                // var memberCanton = my.signNew.memberCanton ? my.signNew.memberCanton.id : '';
                // memberCanton = memberCanton ? memberCanton : '';
                // memberCantonStr = my.canton[memberCity][memberCanton].name;
                my.signNew.dlvrAddr = memberCityStr + ' ' + my.signNew.memberAddress;
            } else {
                my.signNew.dlvrAddr = '';
            }
        }
    });

    $scope.$watch('ctrl.o5_yy_select', function (value) {
        console.log(my);
    })

    $scope.$watch('ctrl.same_member_addr', function (value) {

        if (my.signNew) {
            if (value) {

                my.signNew.memberResCity = my.signNew.memberCity;
                my.signNew.memberResCanton = my.signNew.memberCanton;
                my.signNew.memberResAddress = my.signNew.memberAddress;
            } else {
                my.signNew.dlvrAddr = '';
            }
        }
    });

    function checkID(id) {
        tab = "ABCDEFGHJKLMNPQRSTUVXYWZIO"
        A1 = new Array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3);
        A2 = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5);
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
        tab = "ABCDEFGHJKLMNPQRSTUVXYWZIO"
        A1 = new Array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 3, 3);
        A2 = new Array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0, 1, 2, 3, 4, 5);
        Mx = new Array(9, 8, 7, 6, 5, 4, 3, 2, 1, 1);

        if (id.length != 10) return false;
        i = tab.indexOf(id.charAt(0));
        if (i == -1) return false;

        i = tab.indexOf(id.charAt(1));
        if (i == -1) return false;

        var idHeader = "ABCDEFGHJKLMNPQRSTUVXYWZIO"; //按照轉換後權數的大小進行排序
        //這邊把身分證字號轉換成準備要對應的
        studIdNumber = id;
        studIdNumber = (idHeader.indexOf(studIdNumber.substring(0, 1)) + 10) +
            '' + ((idHeader.indexOf(studIdNumber.substr(1, 1)) + 10) % 10) + '' + studIdNumber.substr(2, 8);
        //開始進行身分證數字的相乘與累加，依照順序乘上1987654321

        s = parseInt(studIdNumber.substr(0, 1)) +
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
        if ((s % 10) == 0 || (10 - s % 10) == checkNum) {
            return true;
        } else {
            return false;
        }
    }

    my.memberSIDChk = function (index) {

        var memberSID = my.signNew.memberSID;
        var err = 0;
        if (!memberSID) {
            error($translate.instant('lg_member.please_input_sid'));
            err++;
        } else {
            memberSID = memberSID.toUpperCase();
            // var SIDregex = /^(?!000|666)[0-8][0-9]{2}-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$/;
            var ICregex = /^(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/;

            if (ICregex.test(memberSID) != true) {
                error($translate.instant('lg_member.js_sid_msg'));
                // $translate.instant('lg_member.js_enter_mobile')
                err++;
            }
        }


        if (err == 0) {
            //檢查身分證
            // CRUD.update({ task: "signNew_memberSIDChk", sid: memberSID, rec2: my.signNew.re2 }, "POST", true).then(function (res) {
            //     if (res.status == 1) {
            //         $("#memberSID").prop("readonly", true);
            //         $("#memberSIDChk").val("驗證通過");
            //         success("驗證通過");
            //     }
            // });
        }

    };

    my.memberEmailChk = function (index) {

        var memberEmail = my.signNew.memberEmail;
        var err = 0;
        if (!memberEmail) {
            error("請輸入電子信箱");
            err++;
        }
        if (err == 0) {
            //取得推薦人資料
            CRUD.update({ task: "signNew_memberEmailChk", email: memberEmail, rec2: my.signNew.re2 }, "POST").then(function (res) {

                if (res.status == 1) {
                    success("驗證通過");
                }
            });
        }

    };

    my.signNewChkExit = function () {
        alertify.confirm(msgStyle($translate.instant('lg_member.js_chk_exit')))
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
            .set('onok', function (closeEvent) {
                location.href = "/";
            });
    }

    my.sign3011ChkExit = function () {
        alertify.confirm(msgStyle($translate.instant('lg_member.js_chk_exit')))
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
            .set('onok', function (closeEvent) {
                location.href = "/";
            });
    }

    my.sign3012ChkExit = function () {
        alertify.confirm(msgStyle($translate.instant('lg_member.js_chk_exit')))
            .setHeader("<i class='fa fa-info-circle'></i>" + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
            .set('onok', function (closeEvent) {
                location.href = "/";
            });
    }



    my.signNewChk = function (index) {


        var referrerNo = my.signNew.referrerNo;
        var referrerName = my.signNew.referrerName;
        var referrerTel = my.signNew.referrerTel;
        var referrerPhone = my.signNew.referrerPhone;

        var memberNo = my.signNew.memberNo;
        var memberName = my.signNew.memberName;
        my.signNew.memberSID = my.signNew.memberSID.toUpperCase();
        var memberSID = my.signNew.memberSID;
        var memberCity = my.signNew.memberCity;
        var memberCanton = my.signNew.memberCanton;

        var memberCity = my.signNew.memberCity ? my.signNew.memberCity.id : '';
        memberCity = memberCity ? memberCity : '';
        var memberCanton = my.signNew.memberCanton ? my.signNew.memberCanton.id : '';
        memberCanton = memberCanton ? memberCanton : '';

        var memberAddress = my.signNew.memberAddress;
        var memberResCity = my.signNew.memberCity;
        var memberResCanton = my.signNew.memberResCanton;

        var memberResCity = my.signNew.memberResCity ? my.signNew.memberResCity.id : '';
        memberResCity = memberResCity ? memberResCity : '';
        var memberResCanton = my.signNew.memberResCanton ? my.signNew.memberResCanton.id : '';
        memberResCanton = memberResCanton ? memberResCanton : '';

        var memberResAddress = my.signNew.memberResAddress;
        var memberTel = my.signNew.memberTel1 + my.signNew.memberTel2;
        var memberPhone = my.signNew.memberPhone;
        var memberEmail = my.signNew.memberEmail;
        var memberBirthday = my.signNew.memberBirthday;
        var payType = my.signNew.payType;
        var dlvrType = my.signNew.dlvrType;
        var dlvrAddr = my.signNew.dlvrAddr;
        var dlvrLocation = my.signNew.dlvrLocation;

        var usedChk = my.signNew.usedChk;
        var memberWNo = my.signNew.memberWNo;

        var img1 = $scope.previewImage;
        var img2 = $scope.previewImage2;
        var img3 = $scope.previewImage3;

        var err = 0;

        if (!memberName) {
            error("請輸入經銷商姓名");
            err++;
        }
        // if (!memberSID) {
        //     // error("請輸入身分證字號");
        //     // err++;
        // } else {

        //     // var SIDregex = /^(?!000|666)[0-8][0-9]{2}-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$/;
        //     // if (SIDregex.test(memberSID) != true) {
        //     //     error("SSN輸入格式錯誤");
        //     //     err++;
        //     // }
        // }
        if (!memberSID) {
            error($translate.instant('lg_member.please_input_sid'));
            err++;
        } else {
            memberSID = memberSID.toUpperCase();
            // var SIDregex = /^(?!000|666)[0-8][0-9]{2}-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$/;
            var ICregex = /^(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/;
            if (ICregex.test(memberSID) != true) {
                error($translate.instant('lg_member.js_sid_msg'));
                // $translate.instant('lg_member.js_enter_mobile')
                err++;
            }
        }
        if ($scope.$parent.signupMode2020 && my.signNew.signupMode == 'SMS') {
            var signupMode = my.signNew.signupMode;
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            var numberRegxp3 = /^09[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp3.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }

            var memberCaptcha = my.signNew.memberCaptcha;
            if (!memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
        }

        if ($scope.$parent.signupMode2020) {
            var memberPasswd = my.signNew.memberPasswd;
            var PasswdChk = my.signNew.PasswdChk;
            if (!memberPasswd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }
            if (!PasswdChk) {
                error($translate.instant('lg_member.re_enter'));
                err++;
            }
            if (memberPasswd != PasswdChk) {
                error($translate.instant('lg_member.no_match'));
                err++;
            }
        }

        // if (!memberCity) {
        //     error("請輸入通訊縣市");
        //     err++;
        // }
        // if (!memberCanton) {
        //     error("請輸入通訊地區");
        //     err++;
        // }
        // if (!memberAddress) {
        //     error("請輸入通訊地址");
        //     err++;
        // }
        // if (!memberResCity) {
        //     error("請輸入戶籍縣市");
        //     err++;
        // }
        // if (!memberResCanton) {
        //     error("請輸入戶籍地區");
        //     err++;
        // }
        // if (!memberResAddress) {
        //     error("請輸入戶籍地址");
        //     err++;
        // }
        // if (!memberTel) {
        //     error("請輸入電話號碼");
        //     err++;
        // }

        if (memberTel) {
            // var numberRegxp = /^(\d{3,4})?\d{6,8}$/;
            // if (numberRegxp.test(memberTel) != true) {
            //     error("電話號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }



        // if (!memberPhone) {
        //     error($translate.instant('lg_member.js_enter_mobile'));
        //     err++;
        // }

        // var numberRegxp2 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
        // if (numberRegxp2.test(memberPhone) != true) {
        //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
        //     err++;
        // }

        if (!$scope.$parent.signupMode2020) {
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            // var numberRegxp1 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // var numberRegxp2 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp1.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }


        if (!$scope.$parent.signupMode2020) {
            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }
        }

        if ($scope.$parent.signupMode2020 && my.signNew.signupMode == 'MAIL') {
            var signupMode = my.signNew.signupMode;
            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }

            var memberCaptcha = my.signNew.memberCaptcha;
            if (!memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
        }

        if (!memberBirthday) {
            error($translate.instant('lg_member.js_enter_birthday'));
            err++;
        }
        // if (!payType) {
        //     error("請輸入付款方式");
        //     err++;
        // }
        // if (!dlvrType) {
        //     error("請輸入取貨方式");
        //     err++;
        // }

        // if (dlvrType == 1) {
        //     if (!dlvrAddr) {
        //         error("請輸入寄送地址");
        //         err++;
        //     }
        // }

        // if (payType == 1 || dlvrType == 2) {
        //     if (!dlvrLocation) {
        //         error("請選擇服務中心");
        //         err++;
        //     }
        // }

        if (!my.memberTermsChk) {
            error($translate.instant('lg_member.js_enter_clause'));
            err++;
        }


        // if(!img1 || img1==''){
        //     error("請上傳身分證正面");
        //     err++;
        // }

        // if(!img2 || img2==''){
        //     error("請上傳身分證反面");
        //     err++;
        // }

        if (err == 0) {

            my.signNew.memberBirthdayStr = [my.signNew.memberBirthday.getFullYear(), my.signNew.memberBirthday.getMonth() + 1, my.signNew.memberBirthday.getDate()].join('-');

            var memberCity = my.signNew.memberCity ? my.signNew.memberCity.id : '';
            memberCity = memberCity ? memberCity : '';
            if (memberCity == '') {
                memberCityStr = '';
            } else {
                memberCitys = parseInt(memberCity) - 1;
                memberCityStr = my.city[memberCitys].state_u;
                // memberCityStr = my.city[memberCity].name;
            }

            var memberCanton = my.signNew.memberCanton ? my.signNew.memberCanton.id : '';
            memberCanton = memberCanton ? memberCanton : '';
            if (memberCanton == '') {
                memberCantonStr = '';
            } else {
                memberCantonStr = my.canton[memberCity][memberCanton].name;
            }


            my.signNew.memberAddressStr = memberCityStr + ' ' + memberCantonStr + my.signNew.memberAddress;

            var memberResCity = my.signNew.memberResCity ? my.signNew.memberResCity.id : '';
            memberResCity = memberResCity ? memberResCity : '';
            if (memberResCity == '') {
                memberResCityStr = '';
            } else {
                memberResCityStr = my.city[memberResCity].name;
            }

            var memberResCanton = my.signNew.memberResCanton ? my.signNew.memberResCanton.id : '';
            memberResCanton = memberResCanton ? memberResCanton : '';
            if (memberResCanton == '') {
                memberResCantonStr = '';
            } else {
                memberResCantonStr = my.canton[memberResCity][memberResCanton].name;
            }

            my.signNew.memberResAddressStr = memberResCityStr + memberResCantonStr + my.signNew.memberResAddress;

            if (my.signNew.memberTel1 != '' && my.signNew.memberTel2 != '') {
                memberTel = my.signNew.memberTel2;
            } else {
                memberTel = '';
            }


            if (index == 0) {
                //檢查身分證
                // CRUD.update({
                //     task: "signNew_memberSIDChk",
                //     sid: memberSID,
                //     email: memberEmail,
                //     rec2: my.signNew.re2,
                //     signupMode: signupMode,
                //     memberCaptcha: memberCaptcha,
                //     memberEmail: memberEmail,
                //     memberPhone: memberPhone
                // }, "POST", true).then(function (res) {
                //     if (res.status == 1) {
                //         $('#signNewChk').modal('show');
                //     } else {
                //         err++;
                //     }
                // });
                $('#signNewChk').modal('show');

            } else {
                $('#signNewChk').modal('hide');

                $timeout(function () {
                    CRUD.update({
                        task: "signNew_signup",
                        referrerNo: referrerNo,
                        referrerName: referrerName,
                        referrerTel: referrerTel,
                        referrerPhone: referrerPhone,
                        memberNo: memberNo,
                        memberName: memberName,
                        memberSID: memberSID,
                        memberCity: memberCity,
                        memberCanton: memberCanton,
                        memberAddress: memberAddress,
                        memberResCity: memberResCity,
                        memberResCanton: memberResCanton,
                        memberResAddress: memberResAddress,
                        memberTel: memberTel,
                        memberPhone: memberPhone,
                        memberEmail: memberEmail,
                        memberBirthday: memberBirthday,
                        memberBirthdayStr: my.signNew.memberBirthdayStr,
                        payType: payType,
                        dlvrType: dlvrType,
                        dlvrAddr: dlvrAddr,
                        dlvrLocation: dlvrLocation,
                        billCity:my.signNew.billCity,
                        billAddr:my.signNew.billAddr,
                        usedChk: usedChk,
                        memberWNo: memberWNo,
                        signupMode: signupMode,
                        memberCaptcha: memberCaptcha,
                        memberPasswd: memberPasswd,
                        PasswdChk: PasswdChk,
                        img1: img1,
                        img2: img2,
                        img3: img3,
                        rec_code: my.signNew.code
                    }, "POST", true).then(function (res) {
                        if (res.status == 1) {
                            if (payType == 6) {
                                // var form = document.createElement("form");
                                // form.method = "POST";
                                // var element = document.createElement("input");
                                // element.value = 'order_submit_tspg';
                                // element.name = 'task';
                                // form.appendChild(element);

                                // var element = document.createElement("input");
                                // element.value = res.oid;
                                // element.name = 'id';
                                // form.appendChild(element);
                                // form.action = "components/cartcvs/api.php";
                                // document.body.appendChild(form);

                                // form.submit();
                                console.log(res);
                                var url = res.url;
                                window.location.replace(url);
                                // window.location.replace("/app/controllers/publicBank.php?task=orderSale&handMode=1&session=0&orderNum="+res.orderNum);
                            } else {
                                my.chk_pay.code4 = '2';
                                // $('#myModal_CHKPAY').modal('show');
                                $location.path("member_page/signup202");
                                success($translate.instant('lg_member.js_signup_success'));
                            }

                            // $location.path("member_page/signup202");
                            // success($translate.instant('lg_member.re_send'));

                        } else {
                            error(res.msg);
                            $route.reload();
                        }
                    });
                }, 200);

            }

        }

    };

    my.tosignupNew0 = function (index) {

        var eid = my.signNew.eid;
        var err = 0;
        if (!eid) {
            //error("請輸入保固卡號");
            err++;
        }
        if (err == 0) {
            $location.url("member_page/signup?rec=" + eid);
            //location.href="member_page/signup?rec="+eid;
        }
    };

    my.tosignupNew1 = function (index) {

        if (index == 1) {
            $location.url("member_page/signup?re=" + my.signNew.rec);
        } else if (index == 2) {
            var wid = my.signNew.wid;
            var err = 0;
            if (!wid) {
                //error("請輸入保固卡號");
                err++;
            }
            if (err == 0) {
                CRUD.update({ task: "signNew_checkWID", wid: wid }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        $location.url("member_page/signup?re=" + my.signNew.rec + "&re2=" + res.rec2);
                        success("有效卡號");
                    }
                });
            }
        }
    };

    my.tosignupNew2 = function (index) {

        if (index == 1) {
            $location.url("member_page/signup?re=" + my.signNew.rec + "&re2=" + my.signNew.rec2);
        } else if (index == 2) {
            alertify.confirm(msgStyle("如註冊資訊有誤，請洽詢上線經銷商。"))
                .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
                .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
                .set('onok', function (closeEvent) {
                    location.href = "/";
                });
        }
    };

    my.showimg = function () {
        console.log($scope);
    }

    my.emailVerify = function () {
        var email = my.user.email;
        var rec = (my.first) ? my.first.rec : "";
        var err = 0;
        if (!email) {
            error("請輸入電子信箱");
            err++;
        }
        if (err == 0) {
            CRUD.update({ task: "signNew_emailChk", email: email, rec: rec }, "POST").then(function (res) {
                if (res.status == 1) {
                    $location.url("member_page/sendSuccess");
                    //success("已成功發送認證信件至您的信箱");
                }
            });
        }
    }

    if (my.member_page == "signupChk") {
        var signupChk = $routeParams.a;
        var err = 0;
        if (!signupChk) {
            //error($translate.instant('lg_member.in_Verification_Code'));
            err++;
        }
        if (err == 0) {
            CRUD.update({ task: "signNew_signupChk", a: signupChk }, "POST").then(function (res) {
                if (res.status == 1) {
                    $location.url("member_page/emailSuccess");
                    //success("完成信箱認證，您可以進行購物。");
                }
            });
        }
    }

    my.chk_pay = {};
    my.order_chk_pay = function () {

        var code1 = my.chk_pay.code1;
        var code2 = my.chk_pay.code2;
        var code3 = my.chk_pay.code3;
        var code4 = my.chk_pay.code4;
        var err = 0;
        if (!code1) {
            error("請輸入確認碼1");
            err++;
        }
        if (!code2) {
            error("請輸入確認碼2");
            err++;
        }
        if (err == 0) {
            CRUD.update({ task: "signNew_chkPay", code1: code1, code2: code2, code3: code3, code4: code4 }, "POST").then(function (res) {
                if (res.status == 1) {
                    $('#myModal_CHKPAY').modal('hide');
                    success("更新成功。");
                    $timeout(function () {

                        if ($scope.$parent.signupMode2020) {
                            $location.path("member_page/signup202");
                        } else {
                            $location.url("member_page/paySuccess?m1=" + res.m1 + "&m2=" + res.m2 + "&m3=" + res.m3 + "&m4=" + res.m4 + "&m5=" + res.m5);

                        }
                        /*
                        alertify.confirm(msgStyle("ｅ化入會成功！如需使用購物平台請登入後進行驗證。"))
                            .setHeader("<i class='fa fa-info-circle'></i> "+$translate.instant('lg_member.js_tip')+"")
                            .set({ labels : { ok: $translate.instant('lg_main.yes') ,cancel:$translate.instant('lg_main.no')} })
                            .set('onok', function(closeEvent){ 
                                location.href="member_page/login";									
                            })
                            .set('oncancel', function(closeEvent){ 
                                location.href="http://www.goodarch2u.com.tw/tw/index.php";
                        });
                        */
                        //$route.reload();
                    }, 200);
                }
            });
        }

    };

    my.order_chk_pay_cancel = function () {
        var code4 = my.chk_pay.code4;
        $('#myModal_CANCEL').modal('hide');
        $timeout(function () {
            if (code4 == '2') {
                $location.url("member_page/login");
            } else {
                //$route.reload();
                $location.url("index_page");
            }
        }, 200);
    }

    my.payResultClick = function (index) {

        if (index == 1) {
            $location.url("member_page/login");
        } else if (index == 2) {
            location.href = "/";
        } else if (index == 3) {
            $location.url("contact_page?qtype=3");
        }
    };


    //新經銷商系統 END------------------------------------------------------------

    //新會員系統2020 START------------------------------------------------------------
    // my.sign20 = {};
    my.signup200 = function () {
        console.log(my);
        try {
            var sid = my.sign20.sid;
            my.sign20.signupMode = 'SMS';
            var signupMode = my.sign20.signupMode;

            var err = 0;
            if (!sid) {
                error("請輸入身分證字號");
                err++;
            }
            // if (!signupMode) {
            //     error("請選擇註冊方式");
            //     err++;
            // }
            if (err == 0) {
                CRUD.update({ task: "sign20_signupChk", sid: sid }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        console.log(res.data);
                        my.sign20.memberName = res.data.name;
                        my.sign20.memberSID = res.data.sid;
                        my.sign20.memberCity = res.data.city;
                        my.sign20.memberCanton = res.data.canton;
                        my.sign20.memberCardno = res.data.cardno;
                        my.member_page = "signup201";
                    } else {
                        my.member_page = 'login';
                        $location.url("member_page/login");
                    }
                });
                localStorage.removeItem('boss_id');
            }
        } catch (e) { }
    };

    my.verification = {};
    my.verification.verifying = false;
    my.verification.countdownTime = 60;
    my.verification.sendCaptchaStr = $translate.instant('lg_member.Send_Verification_Code');
    my.verification.verifyingE = false;
    my.verification.sendCaptchaStrE = $translate.instant('lg_member.Send_Verification_Code');
    my.verification.verifyingM = false;
    my.verification.sendCaptchaStrM = $translate.instant('lg_member.Send_Verification_Code');
    my.countdown = function () {
        if (my.verification.verifying && my.verification.countdownTime > 0) {
            my.verification.countdownTime--;
            my.verification.sendCaptchaStr = my.verification.countdownTime + $translate.instant('lg_member.sec');
            $timeout(my.countdown, 1000);
        } else {
            my.verification.verifying = false;
            my.verification.countdownTime = 60;
            my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
        }
    }

    my.signup20_sendCaptcha = function (signupMode, deviceStr) {

        if (signupMode == "SMS") {
            var numberRegxp1 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_mobile'));
            } 
            // else if (numberRegxp1.test(deviceStr) != true && numberRegxp2.test(deviceStr) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // } 
            else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "sign20_sendCaptcha", signupMode: signupMode, phone: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStr = my.verification.countdownTime + "秒";
                        success("已發送驗證碼簡訊至您的手機" + res.msg);
                        $timeout(my.countdown, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        } else if (signupMode == "MAIL") {
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_email2'));
            } else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "sign20_sendCaptcha", signupMode: signupMode, mail: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStr = my.verification.countdownTime + $translate.instant('lg_member.sec');
                        success($translate.instant('lg_member.send_email'));
                        $timeout(my.countdown, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        }
    }

    my.signup20_SMSTIP = function () {

        alertify.alert("如果您曾經向您的電信業者設定過拒收廣告簡訊，將無法收到此認證簡訊，建議您向電信業者反應，或是用另一個門號做設定")
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "");

    }

    my.signup201 = function () {
        try {
            var signupMode = my.sign20.signupMode;
            var err = 0;
            // if (signupMode == "SMS" && !my.sign20.memberPhone) {
            //     error($translate.instant('lg_member.js_enter_mobile'));
            //     err++;
            // }
            // if (signupMode == "SMS" && !my.sign20.memberCaptcha) {
            //     error($translate.instant('lg_member.in_Verification_Code'));
            //     err++;
            // }
            if(!my.sign20.memberPhone){
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            if (!my.sign20.memberPasswd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }
            if (!my.sign20.PasswdChk) {
                error($translate.instant('lg_member.re_enter'));
                err++;
            }
            if (my.sign20.memberPasswd != my.sign20.PasswdChk) {
                error($translate.instant('lg_member.no_match'));
                err++;
            }
            // if (!my.sign20.memberTel1 || !my.sign20.memberTel2) {
            //     error("請輸入電話號碼");
            //     err++;
            // }
            if (signupMode == "MAIL" && !my.sign20.memberEmail) {
                error($translate.instant('lg_member.js_enter_email2'));
                err++;
            }
            if (signupMode == "MAIL" && !my.sign20.memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
            // if (!my.sign20.memberCity) {
            //     error("請選擇通訊縣市");
            //     err++;
            // }
            // if (!my.sign20.memberCanton) {
            //     error("請選擇通訊地區");
            //     err++;
            // }
            // if (!my.sign20.memberAddress) {
            //     error("請輸入地址");
            //     err++;
            // }

            if (err == 0) {

                var tmpData = {
                    task: "sign20_signup",
                    signupMode: my.sign20.signupMode,
                    memberName: my.sign20.memberName,
                    memberSID: my.sign20.memberSID,
                    memberPhone: my.sign20.memberPhone,
                    memberPasswd: my.sign20.memberPasswd,
                    PasswdChk: my.sign20.PasswdChk,
                    memberTel1: my.sign20.memberTel1,
                    memberTel2: my.sign20.memberTel2,
                    memberEmail: my.sign20.memberEmail,
                    memberCaptcha: my.sign20.memberCaptcha,
                    memberCity: my.sign20.memberCity,
                    memberCanton: my.sign20.memberCanton,
                    memberAddress: my.sign20.memberAddress,
                    memberCardno: my.sign20.memberCardno,
                    ismlm: 1
                };

                CRUD.update(tmpData, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        $location.path("member_page/signup202");
                        success($translate.instant('lg_member.js_signup_success'));
                    } else {
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        } catch (e) {
            console.log(e);
        }
    };

    my.signup202_htmlStr = "";
    if (my.member_page == "signup202") {
        CRUD.update({ task: "sign20_signupSuccess" }, "POST", true).then(function (res) {
            if (res.status == 1) {
                my.signup202_htmlStr = $sce.trustAsHtml(res.msg);
            }else{
                location.href = "index_page";
            }
        });
    }


    my.sign20.re = $routeParams.re;
    my.sign20.re2 = $routeParams.re2;
    my.sign20.eid = (my.sign20.re) ? my.sign20.re : "";
    my.sign20.l = $routeParams.l;

    my.signup203 = function () {
        try {

            var eid = my.sign20.eid;
            var signupMode = my.sign20.signupMode;
            signupMode = 'MAIL';
            var err = 0;
            if (!eid) {
                error($translate.instant('lg_member.msg_5'));
                err++;
            }
            if (!signupMode) {
                error("請選擇註冊方式");
                err++;
            }
            if (err == 0) {


                $location.url("member_page/signup?m=" + signupMode + "&re=" + eid + "&re2=" + my.sign20.re2);
            }
        } catch (e) { }

    }

    if (my.member_page == "signupNew") {
        my.signNew.signupMode = $routeParams.m;
        if (!my.signNew.signupMode) {
            my.signNew.re = (my.signNew.re) ? my.signNew.re : '';
            my.signNew.re2 = (my.signNew.re2) ? my.signNew.re2 : '';
            $location.url("member_page/signup203?re=" + my.signNew.re + "&re2=" + my.signNew.re2);
        }
    }

    if (my.member_page == "forgot") {
        $location.url("member_page/forgot200");
        // my.forgot20.forgotMode='MAIL';
    }
    my.forgot20 = {};


    if (my.member_page == "forgot200") {
        my.forgot20.forgotMode = 'MAIL';
    }

    my.forgot20_sendCaptcha = function (forgotMode, phone, captcha) {

        var numberRegxp1 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
        var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX

        // if (numberRegxp1.test(phone) != true && numberRegxp2.test(phone) != true) {
        //     error("手機號碼輸入格式錯誤（請填寫數字即可，開頭的0不需要輸入）");
        //     err++;
        // } else 
        if (!my.verification.verifying && phone) {
            my.verification.verifying = true;
            CRUD.update({ task: "resetPW20_sendCaptcha", forgotMode: forgotMode, phone: phone, captcha: captcha }, "POST").then(function (res) {
                if (res.status == 1) {
                    my.member_page = "forgot201";
                    my.verification.sendCaptchaStr = my.verification.countdownTime + "秒";
                    success("已發送驗證碼簡訊至您的手機" + res.msg);
                    $timeout(my.countdown, 1000);
                } else {
                    my.verification.verifying = false;
                    my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                }
            });
        }
    }

    my.resetPW200 = function () {
        try {
            my.verification.countdownTime = 60;
            var forgotMode = my.forgot20.forgotMode;
            var phone = my.forgot20.phone;
            var email = my.forgot20.email;
            var err = 0;

            if (!forgotMode) {
                error("請選擇取回方式");
                err++;
            }
            if (forgotMode == "SMS" && !phone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }

            if (forgotMode == "MAIL" && !email) {
                error($translate.instant('lg_member.js_enter_email2'));
                err++;
            }

            if (err == 0) {
                if (forgotMode == "MAIL") {
                    my.forgot = {};
                    my.forgot.email = email;
                    my.resetPW();
                } else {
                    my.forgot20_sendCaptcha(forgotMode, phone, "captcha");
                }
            }
        } catch (e) {
            console.log(e);
        }
    };

    my.resetPW201 = function () {
        try {
            var forgotMode = my.forgot20.forgotMode;
            var forgotCaptcha = my.forgot20.forgotCaptcha;
            var phone = my.forgot20.phone;
            var err = 0;

            if (err == 0) {

                CRUD.update({ task: "resetPW20", forgotMode: forgotMode, forgotCaptcha: forgotCaptcha, phone: phone }, "POST").then(function (res) {
                    if (res.status == 1) {
                        $location.url("member_page/resetPW?a=" + res.a);
                    }
                });
            }
        } catch (e) {

        }
    }

    my.resetPW202 = function () {
        try {
            $location.path("member_page/login")
        } catch (e) {

        }
    }

    my.info20_editInfo = function (mode, chk) {
        if (mode == "MAIL") {
            if (chk) {
                my.verification.verifyingM = false;
                my.verification.verifyingE = true;
                //20210218
                my.user.mobile = my.userOri_mobile;
                my.user.email = "";
            } else {
                my.verification.verifyingE = false;
                my.user.email = my.userOri_email;
                my.user.captcha = "";
            }
        } else {
            if (chk) {
                my.verification.verifyingM = true;
                my.verification.verifyingE = false;
                my.user.mobile = "";
                //20210218
                my.user.email = my.userOri_email;
            } else {
                my.verification.verifyingM = false;
                my.user.mobile = my.userOri_mobile;
                my.user.captcha = "";
            }
        }
    }

    my.countdownM = function () {
        if (my.verification.verifying && my.verification.countdownTime > 0) {
            my.verification.countdownTime--;
            my.verification.sendCaptchaStrM = my.verification.countdownTime + "秒";
            $timeout(my.countdownM, 1000);
        } else {
            my.verification.verifying = false;
            my.verification.countdownTime = 60;
            my.verification.sendCaptchaStrM = $translate.instant('lg_member.re_send');
        }
    }

    my.countdownE = function () {
        if (my.verification.verifying && my.verification.countdownTime > 0) {
            my.verification.countdownTime--;
            my.verification.sendCaptchaStrE = my.verification.countdownTime + "秒";
            $timeout(my.countdownE, 1000);
        } else {
            my.verification.verifying = false;
            my.verification.countdownTime = 60;
            my.verification.sendCaptchaStrE = $translate.instant('lg_member.re_send');
        }
    }

    my.info20_sendCaptcha = function (infoMode, deviceStr) {

        if (infoMode == "SMS") {
            var numberRegxp1 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_mobile'));
            } 
            // else if (numberRegxp1.test(deviceStr) != true && numberRegxp2.test(deviceStr) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // } 
            else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "info20_sendCaptcha", infoMode: infoMode, phone: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStrM = my.verification.countdownTime + "秒";
                        success("已發送驗證碼簡訊至您的手機" + res.msg);
                        $timeout(my.countdownM, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStrM = $translate.instant('lg_member.re_send');
                    }
                });
            }
        } else if (infoMode == "MAIL") {
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_email2'));
            } else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "info20_sendCaptcha", infoMode: infoMode, mail: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStrE = my.verification.countdownTime + $translate.instant('lg_member.sec');
                        success($translate.instant('lg_member.send_email'));
                        $timeout(my.countdownE, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStrE = $translate.instant('lg_member.re_send');
                    }
                });
            }
        }
    }



    //新會員系統2020 END------------------------------------------------------------

    //2021
    // my.sign30 = {};

    function ClearAllIntervals() {
        for (var i = 1; i < 99999; i++)
            window.clearInterval(i);
    }


    my.signup300 = function () {
        try {
            var signupMode = my.sign30.signupMode;
            var err = 0;
            if (!signupMode) {
                error("請選擇註冊方式");
                err++;
            }
            if (err == 0) {
                my.sign300 = 1;
                my.member_page = "signup301";

            }
        } catch (e) { }
    };

    my.signup30_sendCaptcha = function (signupMode, deviceStr) {

        if (signupMode == "SMS") {
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_mobile'));
            } 
            // else if (numberRegxp2.test(deviceStr) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // } 
            else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "sign20_sendCaptcha", signupMode: signupMode, phone: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStr = my.verification.countdownTime + $translate.instant('lg_member.sec');
                        success("已發送驗證碼簡訊至您的手機" + res.msg);
                        $timeout(my.countdown, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        } else if (signupMode == "MAIL") {
            if (!deviceStr) {
                error($translate.instant('lg_member.js_enter_email2'));
            } else if (!my.verification.verifying && deviceStr) {
                my.verification.verifying = true;
                CRUD.update({ task: "sign20_sendCaptcha", signupMode: signupMode, mail: deviceStr }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.sendCaptchaStr = my.verification.countdownTime + $translate.instant('lg_member.sec');
                        success($translate.instant('lg_member.send_email'));
                        $timeout(my.countdown, 1000);
                    } else {
                        my.verification.verifying = false;
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        }
    }

    my.signup30_SMSTIP = function () {

        alertify.alert("如果您曾經向您的電信業者設定過拒收廣告簡訊，將無法收到此認證簡訊，建議您向電信業者反應，或是用另一個門號做設定")
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "");

    }

    my.countdownR = function ($sec) {
        // ClearAllIntervals();
        var countDownDate = new Date().getTime() + 15 * 60 * 1000;
        // Update the count down every 1 second
        var x = setInterval(function () {
            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            // document.getElementById("demo").innerHTML = "請於" + minutes + "分 " + seconds + "秒 內完成會員註冊流程";
            $('#demo').find('.mm').text(minutes);
            $('#demo').find('.ss').text(seconds);
            // If the count down is finished, write some text

            if (distance < 0) {
                clearInterval(x);
                $('#demo').hide();
                location.href = "index_page";
            }
        }, 1000);
    }

    if (my.member_page == 'signup301') {
        ClearAllIntervals();

        setTimeout(function () { $('.ajs-visible').click(); console.log('done'); }, 3000);
        my.countdownR('900');
        my.sign30.memberPhone = localStorage.getItem('memberPhone');
        my.sign30.memberCaptcha = localStorage.getItem('memberCaptcha');
        my.sign30.memberEmail = localStorage.getItem('memberEmail');
        console.log(my.sign20);
        if (my.sign30.memberCaptcha == null || my.sign30.memberCaptcha == 'null') {
            $location.url("member_page/signup");
            my.member_page = "signup300";
            error($translate.instant('lg_member.msg_2'));
        }


        localStorage.removeItem('memberPhone');
        localStorage.removeItem('memberCaptcha');
        localStorage.removeItem('memberEmail');
    }

    my.signup301 = function () {
        try {
            var signupMode = my.sign20.signupMode;
            var err = 0;
            // if (signupMode == "SMS" && !my.sign30.memberPhone) {
            //     error($translate.instant('lg_member.js_enter_mobile'));
            //     err++;
            // }
            if(!my.sign30.memberPhone){
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            if (signupMode == "SMS" && !my.sign30.memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
            if (!my.sign30.memberPasswd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }
            if (!my.sign30.PasswdChk) {
                error($translate.instant('lg_member.re_enter'));
                err++;
            }
            if (!my.sign30.memberBirthday) {
                error($translate.instant('lg_member.js_enter_birthday'));
                err++;
            }
            if (my.sign30.memberPasswd != my.sign30.PasswdChk) {
                error($translate.instant('lg_member.no_match'));
                err++;
            }
            // if (!my.sign30.memberTel1 || !my.sign30.memberTel2) {
            //     error("請輸入電話號碼");
            //     err++;
            // }
            if (signupMode == "MAIL" && !my.sign30.memberEmail) {
                error($translate.instant('lg_member.js_enter_email2'));
                err++;
            }
            if (signupMode == "MAIL" && !my.sign30.memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
            // if (!my.sign30.memberCity) {
            //     error("請選擇通訊縣市");
            //     err++;
            // }
            // if (!my.sign30.memberCanton) {
            //     error("請選擇通訊地區");
            //     err++;
            // }
            // if (!my.sign30.memberAddress) {
            //     error("請輸入地址");
            //     err++;
            // }

            if (err == 0) {

                var tmpData = {
                    task: "sign30_signup",
                    eid: my.sign30.eid,
                    signupMode: my.sign20.signupMode,
                    memberName: my.sign30.memberName,
                    memberSID: my.sign30.memberSID,
                    memberPhone: my.sign30.memberPhone,
                    memberPasswd: my.sign30.memberPasswd,
                    PasswdChk: my.sign30.PasswdChk,
                    memberTel1: my.sign30.memberTel1,
                    memberTel2: my.sign30.memberTel2,
                    memberEmail: my.sign30.memberEmail,
                    memberCaptcha: my.sign30.memberCaptcha,
                    memberCity: my.sign30.memberCity,
                    memberCanton: my.sign30.memberCanton,
                    memberAddress: my.sign30.memberAddress,
                    memberCardno: my.sign30.memberCardno,
                    img1: img1,
                    img2: img2,
                    img3: img3
                };

                CRUD.update(tmpData, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        $location.path("member_page/signup302");
                        success($translate.instant('lg_member.js_signup_success'));
                    } else {
                        my.verification.sendCaptchaStr = $translate.instant('lg_member.re_send');
                    }
                });
            }
        } catch (e) {
            console.log(e);
        }
    };

    my.sign30.re = $routeParams.re;
    my.sign30.re2 = $routeParams.re2;
    my.sign30.eid = (my.sign30.re) ? my.sign30.re : "";

    if (my.member_page == 'signup300') {
        my.sign30.eid = $routeParams.rec;
        if (my.sign30.eid == undefined) {
            CRUD.detail({ task: "get_code" }, "GET", true).then(function (res) {
                if (res.status == 1) {
                    if (res.has_precode == '1') {
                        my.sign30.eid = res.precode;
                    }
                }
            });
        }
        // http://192.168.7.43/member_page/signup?mem=1&rec=TWN20210300003&rec2=
    }

    if (my.member_page == 'signup3012') {
        $temp_email = localStorage.getItem('temp_email');
        my.sign3012 = {};
        my.sign3012.memberEmail = $temp_email;
    }

    console.log(my.sign30.eid);

    my.signup303 = function () {
        try {

            var eid = my.sign30.eid;
            var signupMode = my.sign20.signupMode;
            var err = 0;
            var no_re = 0;
            if (!my.no_re) {
                if (!eid) {
                    error($translate.instant('lg_member.msg_5'));
                    err++;
                }
            } else {
                eid = 'MY20170100002';
                // TW020210300078
                no_re = 1;
            }

            if (!signupMode) {
                error("請選擇註冊方式");
                err++;
            }

            if (err == 0) {
                CRUD.list({ task: "signNew_getRecData", rec: eid }, "POST", true).then(function (res) {

                    if (res.status == 1) {
                        my.member_page = "signup3011";
                    } else {
                        alert($translate.instant('lg_member.js_msg1'))
                            .set('onok', function (closeEvent) {
                                $location.path("member_page/signup");
                                try {
                                    $scope.$apply();
                                } catch (e) {

                                }
                                //location.href="http://www.goodarch2u.com.tw/tw/index.php";
                            });
                    }
                });

            }
        } catch (e) { }

    }

    my.sign3011Chk = function () {
        var signupMode = my.sign20.signupMode;
        var memberPhone = my.sign3011.memberPhone;
        var memberEmail = my.sign3011.memberEmail;
        var eid = my.sign30.eid;
        var no_re = 0;
        var err = 0;
        if (!my.no_re) {
            if (!eid) {
                my.member_page = 'signup301';
                err++;
            }
        } else {
            eid = 'MY20170100002';
            // TW020210300078
            no_re = 1;
        }

        if ($scope.$parent.signupMode2020 && signupMode == 'SMS') {
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }

            // var numberRegxp3 = /^09[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp3.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }

        if ($scope.$parent.signupMode2020 && my.sign20.signupMode == 'MAIL') {

            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }

        }


        var memberCaptcha = my.sign3011.memberCaptcha;
        if (!memberCaptcha) {
            error($translate.instant('lg_member.in_Verification_Code'));
            err++;
        }

        if (err == 0) {
            CRUD.update({
                task: "signupChk3011",
                memberCaptcha: memberCaptcha,
                signupMode: signupMode,
                memberPhone: memberPhone,
                memberEmail: memberEmail
            }, "POST").then(function (res) {
                if (res.status == 1) {
                    success(res.msg);
                    localStorage.setItem('memberCaptcha', memberCaptcha);
                    if (signupMode == 'SMS') {
                        localStorage.setItem('memberPhone', memberPhone);
                    } else if (signupMode == 'MAIL') {
                        localStorage.setItem('memberEmail', memberEmail);
                    }

                    $location.url("member_page/signup?t=1&nr=" + no_re + "&m=" + signupMode + "&re=" + eid + "&re2=" + my.sign30.re2);
                } else {
                    // error(res.msg);
                }

            });
            // $location.url("member_page/signup?t=1&nr=" + no_re + "&m=" + signupMode + "&re=" + eid + "&re2=" + my.sign30.re2);
        }
    }


    my.sign3012Chk = function () {
        var signupMode = my.sign20.signupMode;
        var memberPhone = my.sign3012.memberPhone;
        var memberEmail = my.sign3012.memberEmail;
        var eid = my.sign30.eid;
        var no_re = 0;
        var err = 0;
        if (!my.no_re) {
            if (!eid) {
                my.member_page = 'signup203';
                err++;
            }
        } else {
            eid = 'MY20170100002';
            // TW020210300078
            no_re = 1;
        }

        if ($scope.$parent.signupMode2020 && signupMode == 'SMS') {
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }

            var numberRegxp3 = /^09[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp3.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }

        if ($scope.$parent.signupMode2020 && my.sign20.signupMode == 'MAIL') {

            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }

        }


        var memberCaptcha = my.sign3012.memberCaptcha;
        if (!memberCaptcha) {
            error($translate.instant('lg_member.in_Verification_Code'));
            err++;
        }

        if (err == 0) {
            CRUD.update({
                task: "signupChk3011",
                memberCaptcha: memberCaptcha,
                signupMode: signupMode,
                memberPhone: memberPhone,
                memberEmail: memberEmail
            }, "POST").then(function (res) {
                if (res.status == 1) {
                    success(res.msg);
                    localStorage.setItem('memberCaptcha2', memberCaptcha);
                    if (signupMode == 'SMS') {
                        localStorage.setItem('memberPhone2', memberPhone);
                    } else if (signupMode == 'MAIL') {
                        localStorage.setItem('memberMail2', memberEmail);
                    }

                    $location.url("member_page/signup?t=0&m=" + signupMode + "&re=" + eid + "&re2=" + my.sign30.re2);
                } else {
                    // error(res.msg);
                }
                // console.log(res);
            });
            // $location.url("member_page/signup?t=1&nr=" + no_re + "&m=" + signupMode + "&re=" + eid + "&re2=" + my.sign30.re2);
        }
    }


    my.sign30ChkExit = function () {
        alertify.confirm(msgStyle($translate.instant('lg_member.js_chk_exit')))
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
            .set('onok', function (closeEvent) {
                location.href = "/";
            });
    }



    my.sign30Chk = function (index) {


        var referrerNo = my.sign30.referrerNo;
        var referrerName = my.sign30.referrerName;
        var referrerTel = my.sign30.referrerTel;
        var referrerPhone = my.sign30.referrerPhone;

        var memberNo = my.sign30.memberNo;
        var memberName = my.sign30.memberName;
        if (my.sign30.memberSID) {
            my.sign30.memberSID = my.sign30.memberSID.toUpperCase();
        }

        var no_re = my.no_re;

        var memberSID = my.sign30.memberSID;
        var memberCity = my.sign30.memberCity;
        var memberCanton = my.sign30.memberCanton;

        var memberCity = my.sign30.memberCity ? my.sign30.memberCity.id : '';
        memberCity = memberCity ? memberCity : '';
        var memberCanton = my.sign30.memberCanton ? my.sign30.memberCanton.id : '';
        memberCanton = memberCanton ? memberCanton : '';

        var memberAddress = my.sign30.memberAddress;
        var memberResCity = my.sign30.memberCity;
        var memberResCanton = my.sign30.memberResCanton;

        var memberResCity = my.sign30.memberResCity ? my.sign30.memberResCity.id : '';
        memberResCity = memberResCity ? memberResCity : '';
        var memberResCanton = my.sign30.memberResCanton ? my.sign30.memberResCanton.id : '';
        memberResCanton = memberResCanton ? memberResCanton : '';

        var memberResAddress = my.sign30.memberResAddress;
        var memberTel = my.sign30.memberTel1 + my.sign30.memberTel2;
        var memberPhone = my.sign30.memberPhone;
        var memberEmail = my.sign30.memberEmail;
        var memberBirthday = my.sign30.memberBirthday;
        var payType = my.sign30.payType;
        var dlvrType = my.sign30.dlvrType;
        var dlvrAddr = my.sign30.dlvrAddr;
        var dlvrLocation = my.sign30.dlvrLocation;

        var usedChk = my.sign30.usedChk;
        var memberWNo = my.sign30.memberWNo;

        // var img1 = $scope.previewImage;
        // var img2 = $scope.previewImage2;
        // var img3 = $scope.previewImage3;

        var err = 0;

        if (!memberName) {
            error("請輸入經銷商姓名");
            err++;
        }
        // if (!memberSID) {
        //     error("請輸入身分證字號");
        //     err++;
        // } else {
        //     // memberSID = memberSID.toUpperCase();
        //     var SIDregex = /^(?!000|666)[0-8][0-9]{2}-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$/;
        //     if (SIDregex.test(memberSID) != true) {
        //         error("SSN輸入格式錯誤");
        //         err++;
        //     }
        // }
        if (!memberSID) {
            error($translate.instant('lg_member.please_input_sid'));
            err++;
        } else {
            memberSID = memberSID.toUpperCase();
            // var SIDregex = /^(?!000|666)[0-8][0-9]{2}-(?!00)[0-9]{2}-(?!0000)[0-9]{4}$/;
            var ICregex = /^(([[0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([0-9]{2})([0-9]{4})$/;

            if (ICregex.test(memberSID) != true) {
                error($translate.instant('lg_member.js_sid_msg'));
                // $translate.instant('lg_member.js_enter_mobile')
                err++;
            }
        }
        if ($scope.$parent.signupMode2020 && my.sign20.signupMode == 'SMS') {
            var signupMode = my.sign20.signupMode;
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            var numberRegxp3 = /^09[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            var numberRegxp2 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp3.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }

            var memberCaptcha = my.sign30.memberCaptcha;
            if (!memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
        }

        if ($scope.$parent.signupMode2020) {
            var memberPasswd = my.sign30.memberPasswd;
            var PasswdChk = my.sign30.PasswdChk;
            if (!memberPasswd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }
            if (!PasswdChk) {
                error($translate.instant('lg_member.re_enter'));
                err++;
            }
            if (memberPasswd != PasswdChk) {
                error($translate.instant('lg_member.no_match'));
                err++;
            }
        }

        // if (!memberCity) {
        //     error("請輸入通訊縣市");
        //     err++;
        // }
        // if (!memberCanton) {
        //     error("請輸入通訊地區");
        //     err++;
        // }
        // if (!memberAddress) {
        //     error("請輸入通訊地址");
        //     err++;
        // }
        // if (!memberResCity) {
        //     error("請輸入戶籍縣市");
        //     err++;
        // }
        // if (!memberResCanton) {
        //     error("請輸入戶籍地區");
        //     err++;
        // }
        // if (!memberResAddress) {
        //     error("請輸入戶籍地址");
        //     err++;
        // }
        // if (!memberTel) {
        //     error("請輸入電話號碼");
        //     err++;
        // }

        if (memberTel) {
            // var numberRegxp = /^(\d{3,4})?\d{6,8}$/;
            // if (numberRegxp.test(memberTel) != true) {
            //     error("電話號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }



        // if (!memberPhone) {
        //     error($translate.instant('lg_member.js_enter_mobile'));
        //     err++;
        // }

        // var numberRegxp2 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
        // if (numberRegxp2.test(memberPhone) != true) {
        //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
        //     err++;
        // }

        if (!$scope.$parent.signupMode2020) {
            if (!memberPhone) {
                error($translate.instant('lg_member.js_enter_mobile'));
                err++;
            }
            var numberRegxp1 = /^9[0-9]{2}[0-9]{6}$/; //格式需為9XXXXXXXX
            var numberRegxp2 = /^09[0-9]{2}[0-9]{6}$/; //格式需為09XXXXXXXX
            // if (numberRegxp2.test(memberPhone) != true && numberRegxp1.test(memberPhone) != true) {
            //     error("手機號碼輸入格式錯誤（請填寫數字即可）");
            //     err++;
            // }
        }


        if (!$scope.$parent.signupMode2020) {
            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }
        }

        if ($scope.$parent.signupMode2020 && my.sign20.signupMode == 'MAIL') {
            var signupMode = my.sign20.signupMode;
            if (!memberEmail) {
                error($translate.instant('lg_member.js_enter_email'));
                err++;
            }

            var memberCaptcha = my.sign30.memberCaptcha;
            if (!memberCaptcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }
        }

        if (!memberBirthday) {
            error($translate.instant('lg_member.js_enter_birthday'));
            err++;
        }
        // if (!payType) {
        //     error("請輸入付款方式");
        //     err++;
        // }
        // if (!dlvrType) {
        //     error("請輸入取貨方式");
        //     err++;
        // }

        // if (dlvrType == 1) {
        //     if (!dlvrAddr) {
        //         error("請輸入寄送地址");
        //         err++;
        //     }
        // }

        // if (payType == 1 || dlvrType == 2) {
        //     if (!dlvrLocation) {
        //         error("請選擇服務中心");
        //         err++;
        //     }
        // }

        // if(!img1 || img1==''){
        //     error("請上傳身分證正面");
        //     err++;
        // }

        // if(!img2 || img2==''){
        //     error("請上傳身分證反面");
        //     err++;
        // }

        // console.log(img1);

        if (!my.memberTermsChk) {
            error($translate.instant('lg_member.js_enter_clause'));
            err++;
        }

        if (err == 0) {

            my.sign30.memberBirthdayStr = [my.sign30.memberBirthday.getFullYear(), my.sign30.memberBirthday.getMonth() + 1, my.sign30.memberBirthday.getDate()].join('-');

            var memberCity = my.sign30.memberCity ? my.sign30.memberCity.id : '';
            memberCity = memberCity ? memberCity : '';
            if (memberCity == '') {
                memberCityStr = '';
            } else {
                memberCitys = parseInt(memberCity) - 1;
                memberCityStr = my.city[memberCitys].state_u;
            }
            var memberCanton = my.sign30.memberCanton ? my.sign30.memberCanton.id : '';
            memberCanton = memberCanton ? memberCanton : '';
            if (memberCanton == '') {
                memberCantonStr = '';
            } else {
                memberCantonStr = my.canton[memberCity][memberCanton].name;
            }

            if (my.sign30.memberAddress) {
                my.sign30.memberAddressStr = memberCityStr + memberCantonStr + my.sign30.memberAddress;
            } else {
                my.sign30.memberAddressStr = '';
            }


            var memberResCity = my.sign30.memberResCity ? my.sign30.memberResCity.id : '';
            memberResCity = memberResCity ? memberResCity : '';
            if (memberResCity == '') {
                memberResCityStr = '';
            } else {
                memberResCityStr = my.city[memberResCity].name;
            }

            var memberResCanton = my.sign30.memberResCanton ? my.sign30.memberResCanton.id : '';
            memberResCanton = memberResCanton ? memberResCanton : '';
            if (memberResCanton == '') {
                memberResCantonStr = '';
            } else {
                memberResCantonStr = my.canton[memberResCity][memberResCanton].name;
            }

            my.sign30.memberResAddressStr = memberResCityStr + ' ' + memberResCantonStr + my.sign30.memberResAddress;

            if (my.sign30.memberTel1 != '' && my.sign30.memberTel2 != '') {
                memberTel = my.sign30.memberTel1 + '-' + my.sign30.memberTel2;
            } else {
                memberTel = '';
            }

            if (memberTel == '-') {
                memberTel = '';
            }

            index = 1;

            if (index == 0) {
                //檢查身分證
                CRUD.update({
                    task: "signNew_memberSIDChk",
                    sid: memberSID,
                    eid: my.sign30.eid,
                    email: memberEmail,
                    rec2: my.sign30.re2,
                    signupMode: signupMode,
                    memberCaptcha: memberCaptcha,
                    memberEmail: memberEmail,
                    memberPhone: memberPhone
                }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        $('#sign30Chk').modal('show');
                    } else {
                        err++;
                    }
                });

            } else {
                $('#sign30Chk').modal('hide');
                $timeout(function () {
                    CRUD.update({
                        task: "sign30_signup",
                        referrerNo: referrerNo,
                        referrerName: referrerName,
                        referrerTel: referrerTel,
                        referrerPhone: referrerPhone,
                        eid: my.sign30.eid,
                        memberSID:memberSID,
                        memberNo: memberNo,
                        memberName: memberName,
                        memberCity: memberCity,
                        memberCanton: memberCanton,
                        memberAddress: memberAddress,
                        memberResCity: memberResCity,
                        memberResCanton: memberResCanton,
                        memberResAddress: memberResAddress,
                        memberTel: memberTel,
                        memberPhone: memberPhone,
                        memberEmail: memberEmail,
                        memberBirthday: memberBirthday,
                        memberBirthdayStr: my.sign30.memberBirthdayStr,
                        payType: payType,
                        dlvrType: dlvrType,
                        dlvrAddr: dlvrAddr,
                        dlvrLocation: dlvrLocation,
                        usedChk: usedChk,
                        memberWNo: memberWNo,
                        signupMode: signupMode,
                        memberCaptcha: memberCaptcha,
                        memberPasswd: memberPasswd,
                        PasswdChk: PasswdChk,
                        no_re: no_re
                    }, "POST", true).then(function (res) {
                        if (res.status == 1) {

                            if (payType == 6) {
                                var form = document.createElement("form");
                                form.method = "POST";
                                var element = document.createElement("input");
                                element.value = 'order_submit_tspg';
                                element.name = 'task';
                                form.appendChild(element);

                                var element = document.createElement("input");
                                element.value = res.oid;
                                element.name = 'id';
                                form.appendChild(element);
                                form.action = "components/cartcvs/api.php";
                                document.body.appendChild(form);

                                form.submit();

                            } else {
                                my.chk_pay.code4 = '2';
                                // $('#myModal_CHKPAY').modal('show');
                                $location.path("member_page/signup202");
                                success($translate.instant('lg_member.js_signup_success'));
                            }

                        } else {
                            error(res.msg);
                            // $route.reload();
                        }
                    });
                }, 200);

            }

        }

    };

    //2021 END

    my.signup = function () {
        console.log('HERE2');
        try {
            var sid = my.sign.sid;
            var email = my.sign.email;
            var chk_pass = $('input[name="new_password"]:checked').val();
            var passwd = my.sign.passwd;
            var passwd2 = my.sign.passwd2;
            var phone = my.sign.phone;
            //var cardnumber=my.sign.cardnumber;
            var err = 0;

            // if (!sid) {
            //     error("請輸入身分證字號");
            //     err++;
            // }
            // if (!email) {
            // 	error($translate.instant('lg_member.js_enter_email2'));
            // 	err++;
            // }
            if (!phone) {
                error("請輸入電話");
                err++;
            }
            /*
            if(!cardnumber){
                error("請輸入經銷商卡號");
                err++;
            }
            */
            if (chk_pass == 1) {
                if (!passwd) {
                    error($translate.instant('lg_member.please_input_pwd'));
                    err++;
                }
                if (passwd.length < 6) {
                    error("密碼長度不足，請輸入6位以上英數字");
                    err++;
                }
                if (!passwd2) {
                    err++;
                }
                if (passwd != passwd2) {
                    error($translate.instant('lg_member.js_pwd_msg2'));
                    err++;
                }
            }


            if (err == 0) {
                CRUD.update({ task: "signup", email: email, passwd: passwd, chk_pass: chk_pass, phone: phone, sid: sid, rec_code: $location.search().rec }, "POST", true).then(function (res) {
                    if (res.status == 1) {
                        $location.path("member_page/login");
                        success("註冊成功，請登入");
                    } else {
                        error("發生錯誤，請洽客服人員。");
                    }
                });
            }
        } catch (e) { }
    };


    my.resetPW = function () {
        try {
            var email = my.forgot.email;
            var err = 0;

            if (!email) {
                error($translate.instant('lg_member.js_enter_email2'));
                err++;
            }

            if (err == 0) {
                CRUD.update({ task: "resetPW", email: email }, "POST").then(function (res) {
                    $scope.member_status = 0;
                    $location.path("member_page/login");
                    success($translate.instant('lg_member.js_msg3'));
                });
            }
        } catch (e) {

        }
    };

    my.get_addrCode = function () {
        var turl = CRUD.getUrl();
        CRUD.setUrl("app/controllers/eways.php");
        CRUD.detail({ task: "get_addrCode" }, "GET").then(function (res) {
            if (res.status == 1) {
                console.log(res);
                my.city = res.city;
                // my.canton = res.canton;
            }
        });
        CRUD.setUrl(turl);
    };
    my.get_addrCode();
    CRUD.detail({ task: "userInfo" }, "GET").then(function (res) {
        if (res.status == 1) {
            my.user = res.data;
            if(my.user.mobile == 'null' || my.user.mobile == null || my.user.mobile == 'undefined' || my.user.mobile == undefined){
                my.user.mobile = '';
            }
            my.userOri_mobile = res.data.mobile;
            my.userOri_email = res.data.email;

            my.user['hasPV'] = my.user['pv'];
            my.user['hasBV'] = my.user['bv'];

            my.user['hasBonus'] = my.user['bonus'];
            my.user['allBonus'] = my.user['allBonus'];
            my.user['rp'] = my.user['rp'];
            my.user['rp_dDate'] = my.user['dDate'];
            my.user['bonusValue'] = my.user['bonusValue'];
        }
    });

    my.updateUser = function () {
        try {
            var name = my.user.name;
            var mobile = my.user.mobile;
            var email = my.user.email;
            var address = my.user.address;
            var cardnumber = my.user.cardnumber;
            var city = my.user.city ? my.user.city.id : '';
            city = city ? city : '';
            var canton = my.user.canton ? my.user.canton.id : '';
            canton = canton ? canton : '';
            var err = 0;

            if (!name) {
                error("請輸入收件人");
                err++;
            }
            // if (!mobile) {
            //     error("請輸入電話");
            //     err++;
            // }
            // if (!email) {
            // 	error($translate.instant('lg_member.js_enter_email2'));
            // 	err++;
            // }
            // if (!city) {
            //     error("請選擇居住縣市");
            //     err++;
            // }
            // if (!canton) {
            //     error("請選擇居住地區");
            //     err++;
            // }
            // if (!address) {
            //     error("請輸入地址");
            //     err++;
            // }

            var captcha = my.user.captcha;
            var verifyingM = my.verification.verifyingM;
            var verifyingE = my.verification.verifyingE;
            if ((verifyingM || verifyingE) && !captcha) {
                error($translate.instant('lg_member.in_Verification_Code'));
                err++;
            }

            if (err == 0) {
                CRUD.update({ task: "updateUser", name: name, mobile: mobile, email: email, address: address, city: city, canton: canton, cardnumber: cardnumber, captcha: captcha }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.verification.verifyingE = false;
                        my.verification.verifyingE = false;
                        my.user.captcha = "";
                        success("更新成功");
                        $route.reload();
                    }
                });
            }
        } catch (e) { }
    };

    my.toSales = function () {
        alertify.confirm(msgStyle($translate.instant('lg_member.salesChk')))
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.yes'), cancel: $translate.instant('lg_main.no') } })
            .set('onok', function (closeEvent) {
                CRUD.update({ task: "updateToSales" }, "POST").then(function (res) {
                    if (res.status == 1) {
                        success("已成功申請經銷商資格，請等候審核");
                        $route.reload();
                    }
                });

            });
    };

    my.money_list_fn = function (search_yy) {

        CRUD.list({ task: "money_list", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.money_list = res.data;
                my.money_yy_list = res.money_yy_list;
                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
            }
        })
    }

    my.m_info_fn = function () {
        my.productimg = [];
        my.productimg2 = [];
        my.productimg3 = [];
        $scope.previewImage = [];
        $scope.previewImage2 = [];
        $scope.previewImage3 = [];
        CRUD.list({ task: "minfo_list" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.minfo = res.result;
                console.log(my.minfo);
            } else {
                error('查無資料')
            }
            $('.cg-busy').addClass('ng-hide');
        })
    }

    my.stock_plan_fn = function () {
        CRUD.list({ task: "stock_list" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.stock_plan = res.data;
            } else {

            }
            $('.cg-busy').addClass('ng-hide');
        })
    }

    my.annual_dividend_fn = function () {
        CRUD.list({ task: "annual_dividend" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.annual_dividend = res.data;
                my.annual_dividend_t = res.vdata;
                my.r_annual_dividend_t = res.rvtdata;
                my.r_annual_dividend = res.rvdata;
                my.closing_year = res.closing_year;
                my.v_str = res.v_str;
                my.vs_str = res.vs_str;
                my.v_total = res.all_total;
                my.a_mb_name = res.mb_name;
                my.mb_performance = res.mb_performance;
                my.d_member = res.d_member;
                my.d_md = res.md;
            } else {

            }
            $('.cg-busy').addClass('ng-hide');
        })
    }
    my.orgseq_member_fn = function () {
        CRUD.list({ task: "orgseq_member" }, "GET").then(function (res) {
          if (res.status == 1) {
            // data
            //console.log(res);
            my.mbst = [];
            my.list = [];
            my.c_lv_name = [];
            my.total = [];
            my.date = [];
            var i = 0;
            while (i < res.data.list.length) {
              my.mbst = res.data.mbst;
              my.list = res.data.list;
              my.c_lv_name = res.data.c_lv_name;
              my.c_total = res.data.c_total;
              my.total = res.data.total;
              var time_str = res.data.list[i].regDate;
              var t = time_str.substr(0, 10);
              my.date = t;
              i++;
            }
          }
          $(".cg-busy").addClass("ng-hide");
        });
      };
    my.ecash_list_fn = function (search_yy) {
        CRUD.list({ task: "ecash_list", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.ecash_list = res.data;
                my.ecash_yy_list = res.ecash_yy_list;
                if (res.l == '2020') {
                    my.ecsp = '2020-01';
                    my.ecep = '2021-02';
                } else {
                    my.ecsp = res.l + '-03';
                    my.ecep = res.l + '-02';
                }
                my.ecs = res.t + '-03';
                my.ece = res.n + '-02';
                if (res.data.basic_data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
            }
        })
    }

    my.register_tb_list_fn = function () {
        CRUD.list({ task: "register_tb_list" }, "GET").then(function (res) {
            if (res.status == 1) {
                console.log(res.data);
                my.register_tb_list = res.data;

                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }

            }
        })
    }

    my.ecash_new2_1_list_fn = function (search_yy) {
        CRUD.list({ task: "ecash_new2_1_list", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.ecash_new2_1_list = res.data;
                my.ecash_new2_1_yy_list = res.ecash_yy_list;
                if (res.data.basic_data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
            }
        })
    }

    my.ecash_new2_2_list_fn = function (search_yy) {
        CRUD.list({ task: "ecash_new2_2_list", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.ecash_new2_2_list = res.data;
                my.ecash_new2_2_yy_list = res.ecash_yy_list;
                if (res.data.basic_data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.cg-busy').addClass('ng-hide');
            }
        })
    }

    my.order_list_fn = function (search_str) {
        CRUD.list({ task: "order_list", search_str: search_str ? search_str : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.order_list = res.data;
            }

        });
    };

    my.cur = !$location.search().cur ? 1 : $location.search().cur;

    my.member_news_list_fn = function () {
        CRUD.list({ task: "member_news_list", page: my.cur }, "GET").then(function (res) {
            if (res.status == 1) {
                my.member_news_list = res.data;
                my.news_cnt = res.cnt;
                my.test = res;
                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
            }
        });
    };

    my.member_poster_list_fn = function () {
        CRUD.list({ task: "member_poster_list", page: my.cur }, "GET").then(function (res) {
            if (res.status == 1) {
                my.member_poster_list = res.data;
                $('.cg-busy').addClass('ng-hide');
            } else {
                error('無資料');
                $('.cg-busy').addClass('ng-hide');
            }
        });
    };

    my.carry_treasure_fn = function (search_yy) {
        $('.cg-busy').removeClass('ng-hide');
        my.carry_treasure_list = [];
        CRUD.list({ task: "carry_treasure", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.carry_treasure_list = res.data;
                my.ct_unuse = res.ct_cnt_1;
                my.ct_used = res.ct_cnt_3;
                my.ct_useing = res.ct_cnt_2;
                my.ct_yy_list = res.ct_yy_list;
                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.rwd-tables').basictable({ breakpoint: 768 });
            }
        });
    };

    my.birthday_voucher_fn = function (search_yy) {
        $('.cg-busy').removeClass('ng-hide');
        my.birthday_voucher_list = [];
        CRUD.list({ task: "birthday_voucher", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.birthday_voucher_list = res.data;
                my.bv_unuse = res.bv_cnt_1;
                my.bv_used = res.bv_cnt_2;
                my.bv_yy_list = res.bv_yy_list;
                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.rwd-tables').basictable({ breakpoint: 768 });
            }

        });
    };

    my.soybean_voucher_fn = function (search_yy) {
        $('.cg-busy').removeClass('ng-hide');
        my.soybean_voucher_list = [];
        CRUD.list({ task: "soybean_voucher", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.soybean_voucher_list = res.data;
                if (res.data == 'null') {
                    my.soybean_voucher_list = [];
                }
                my.sb_unuse = res.sb_cnt_1;
                my.sb_used = res.sb_cnt_2;
                my.sb_total = res.sb_total
                if (res.data.length == 0 || res.data == 'null') {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.rwd-tables').basictable({ breakpoint: 768 });
            }

        });
    };

    my.chk_birthdate_fn = function () {
        $('.cg-busy').removeClass('ng-hide');
        CRUD.list({ task: "chk_birthdate" }, "GET").then(function (res) {
            var tmpObj = res.msg.split('||')[0];
            var tmpObj1 = res.msg.split('||')[1];
            var tmpObj2 = tmpObj1.substr(0, 4) + '年' + tmpObj1.substr(4, 2) + '月' + tmpObj1.substr(6, 2) + '日';
            if (tmpObj == 'none') {
                alert('很抱歉,您生日不符當月領取資格！');
            } else if (tmpObj == 'none1') {
                alert('很抱歉,您的經銷商資料有誤！');
            } else if (tmpObj == 'give') {
                alert('您已領取過囉,可至生日劵查詢！');
            } else if (tmpObj == 'ok') {
                alert('恭喜您,已領取生日劵,可至生日劵查詢！');
                alert("您的生日券將在" + tmpObj2 + "到期");
                $timeout(function () {
                    $route.reload();
                }, 200);
            } else {
                alert('今年度生日劵已使用完畢，訂單編號：' + tmpObj + '，無法再領取');
            }
            $('.cg-busy').addClass('ng-hide');
        });
    }


    my.download_page_fn = function (kind) {
        CRUD.list({ task: "download_page", kind: kind ? kind : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.download_list = res.data;
                my.kind_list = res.kind_list;
                if (res.data.length == 0 || res.data == 'null') {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.rwd-tables').basictable({ breakpoint: 768 });
                console.log(my);
            }
        });
    };

    my.m_points_fn = function (val) {
        CRUD.list({ task: "m_points" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.myStyle = { color: '#0E6900' };
                my.member_detail = res.member_detail;
                if (my.member_detail.m_level == 3) {
                    my.myStyle = { color: '#870000' };
                } else if (my.member_detail.m_level == 2) {
                    my.myStyle = { color: '#FFC42C' };
                } else if (my.member_detail.m_level == 1) {
                    my.myStyle = { color: '#0E6900' };
                }
                my.now_points = res.now_points;
                my.withdraw_points = res.withdraw_points;
                my.withdraw_list = res.withdraw_list;
                my.this_points = parseFloat(res.this_points);
                my.next_points = parseFloat(res.next_points);
                my.this_year = res.this_year;
                my.next_year = res.next_year;
                my.get_list = res.get_list;
                my.use_list = res.use_list;
                
                console.log(my.myStyle);
            }
            
        });


    };
    my.pm = {};
    my.pm.set_point = '25.00';

    my.check_sp = function () {

        var sp = (Math.round(my.pm.set_point * 100) / 100);
        console.log(sp);
        var max_sp = my.withdraw_points;
        if (sp > parseFloat(my.withdraw_points)) {
            sp = max_sp;
        }

        if (sp < 25.00) {
            sp = 25.00;
        }

        if (isNaN(max_sp)) {
            sp = 0;
        }

        my.pm.set_point = sp;

    }

    my.do_sp = function(){
        CRUD.update({ task: "set_pm", sp: my.pm.set_point }, "POST").then(function (res) {
            if(res.status == '1'){
                success(res.msg);
               
            }
            my.m_points_fn('new');
            
        });
    }

    my.del_pm = function(id){
        var yes = confirm($translate.instant('lg_member.decide_delete'));
        if(yes){
            CRUD.update({ task: "del_pm" , id:id}, "POST").then(function(res){
                if(res.status == '1'){
                    my.m_points_fn();
                    success('OK');
                }else{
                    error('Something Wrong!');
                }
            })
        }
    }

    my.cash_back_list_fn = function () {
        CRUD.list({ task: "cash_back_list" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.cb_get_list = res.get_list;
                my.cb_use_list = res.use_list;
                my.user_cb = res.user_cb;
            }
        });
    };

    my.toggleModal = function (id) {
        console.log(id);
        $('#' + id).modal('toggle');
    }

    my.show_re = function () {
        if (my.no_re) {
            $('#signup30eid').prop('readonly', true);
            $('#signup30eid').val('');
            $('#signup30eid').text('');
            my.sign30.eid = '';
        } else {
            $('#signup30eid').prop('readonly', false);
            my.sign30.eid = '';
        }
    }

    my.get_page = function () {
        $('.point_page').addClass('hide');
        $('.point_page_head').removeClass('hide_effect');
        $('#use_page_head').addClass('hide_effect');
        $('#get_page').removeClass('hide');
    }
    my.use_page = function () {
        $('.point_page').addClass('hide');
        $('.point_page_head').removeClass('hide_effect');
        $('#get_page_head').addClass('hide_effect');
        $('#use_page').removeClass('hide');
    }

    my.cb_get_page = function () {
        $('.cb_point_page').addClass('hide');
        $('.cb_point_page_head').removeClass('hide_effect');
        $('#cb_use_page_head').addClass('hide_effect');
        $('#cb_get_page').removeClass('hide');
    }
    my.cb_use_page = function () {
        $('.cb_point_page').addClass('hide');
        $('.cb_point_page_head').removeClass('hide_effect');
        $('#cb_get_page_head').addClass('hide_effect');
        $('#cb_use_page').removeClass('hide');
    }

    my.screenshot = function () {
        var $ww = document.getElementById('scsh').scrollWidth;
        var $hh = document.getElementById('scsh').scrollHeight;
        $hh = $hh + 50;
        var d = new Date();
        var y = d.getFullYear();
        var m = d.getMonth() + 1;
        var day = d.getDate();
        var H = d.getHours();
        var i = d.getMinutes();
        var s = d.getSeconds();
        domtoimage.toJpeg(document.getElementById('scsh'), { quality: 0.95, bgcolor: "#fff", width: $ww, height: $hh })
            .then(function (dataUrl) {
                var link = document.createElement('a');
                link.download = y + '-' + m + '-' + day + '_' + H + '_' + i + '_' + s + '.jpeg';
                link.href = dataUrl;
                link.click();
            });
    }

    my.operation_page_fn = function (kind) {
        CRUD.list({ task: "operation_page", kind: kind ? kind : '' }, "GET").then(function (res) {
            $('.rwd-tables').basictable({ breakpoint: 768 });
        });
    };

    my.orgseq5_fn = function (search_yy) {
        CRUD.list({ task: "orgseq5", search_yy: search_yy ? search_yy : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.o5_check = 1;
                my.o5_list = res.data;
                my.o5_yy_list = res.o5_yy_list;
                my.o5_sum = res.sum;
                my.check_level = res.check_level;

                if (res.check_level == 0) {
                    my.e = res.e;
                }
                console.log(my);

                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
                $('.rwd-tables').basictable({ breakpoint: 768 });
            } else if (res.status == 2) {
                my.o5_check = 2;
                my.o5_yy_list = res.o5_yy_list;
                my.o5_yy_select = res.yy_select;
                $('.cg-busy').addClass('ng-hide');
            }
        });
    };


    my.orgseq_fn = function (limit, org_kind, his, name) {
        $('.cg-busy').removeClass('ng-hide');
        var true_intro_no5 = 'true_intro_no';
        var level_no5 = 'level_no1';
        var flag = false;
        CRUD.list({ task: "orgseq1", org_kind: org_kind ? org_kind : '', his: his ? his : '', limit: limit ? limit : '' }, "GET").then(function (result) {
            res = result.data;
            my.rank_exp = result.rank_exp;
            $('#rank_block').empty();
            $('#rank_block').append(result.rank_exp);
            my.f_his_list = result.f_his;
            my.have_intro = result.have_intro;
            if (res && !result.error) {
                orgData = res;
                my.back_org = orgData;
                if ((orgData.data.length == 1) && (orgData.data[0].line_label.length == 1)) {
                    obj = my._id('BLOCK_orgseq_body5');
                    //移除first ul下的所有節點
                    while (obj.childNodes.length > 0) {
                        obj.removeChild(obj.lastChild);
                    }
                    /* dv=document.createElement('div');	
                    dv.innerHTML=chgNumToImg5("00");		
                    obj.appendChild(dv); */

                    dv = document.createElement("div");
                    dv.id = 'org5|' + orgData.data[0].mb_no;
                    dv.title = eval("orgData.data[0]." + level_no5);
                    obj.appendChild(dv);
                    dv_info = new Array();

                    if (orgData.data[0].line_label == 5) { //自動展開
                        dv_info.push("<span class='line_flag'><img class='clickonnode' src='" + lastsnode.src + "' title='" + lastsnode.title + "'>" + orgData.data[0].grade_info + "</span>");
                    } else {
                        dv_info.push('<span class="line_flag">' + my.chgNumToImg5(orgData.data[0].line_label) + orgData.data[0].grade_info + '</span>');
                    }

                    dv_info.push('<span>0 代</span>');
                    tmp_level1 = eval("orgData.data[0]." + level_no5);
                    // if(my._id('f_org_kind5').value==1){
                    // 	dv_info.push('<span class=line_img><img src=orgseq/line_img/gr'+orgData.data[0].line_kind+'.gif></span>');
                    // }
                    dv_info.push("<span id='show_" + (orgData.data[0].mb_no) + "'>");
                    dv_info.push("<span id=id >" + orgData.data[0].mb_no + "</span>");
                    dv_info.push("<span class=name >" + orgData.data[0].mb_name + "</span>");
                    dv_info.push("<span class=name >" + orgData.data[0].pg_date + "</span>");
                    dv_info.push("</span>");

                    var html = "<div style='display:inline-block' class='oreseq_detail_block'>" +
                        "<button mb_no='" + orgData.data[0].mb_no + "' ng-click='ctrl.show_mb_detail($event)' data-toggle='modal' data-target='#myModal_mb_detail' type='button' style='margin-left:10px' class='btn'>" +
                        "詳細" +
                        "</button>" +
                        "</div>";
                    // dv_info.push(html);
                    $innHtml = "<nobr>" + dv_info.join('') + "</nobr>";
                    $(dv).append($compile($innHtml)($scope));
                    // dv.innerHTML = "<nobr>" + dv_info.join('') + "</nobr>";

                    //右邊資料
                    obj = my._id('r_m005');
                    //移除first ul下的所有節點(保留表頭)
                    while (obj.childNodes.length > 2) {
                        obj.removeChild(obj.lastChild);
                    }
                    dv = document.createElement("div");
                    dv.setAttribute("className", "right_orgseq_list5");
                    dv.setAttribute("class", "right_orgseq_list5");
                    dv.id = 'rorg5|' + orgData.data[0].mb_no;
                    dv.title = eval("orgData.data[0]." + level_no5);
                    obj.appendChild(dv);
                    dv_info = new Array();
                    //改成根據欄位抓資料
                    // var tmpObj2='<?php echo $gdata5?>';					
                    // give_tb2=tmpObj2.parseJSON();					
                    // gcount2=give_tb2.data.length;
                    // c2=0;				
                    // while(c2<gcount2){
                    // 	if(_id('f_org_kind5').value=='1'){
                    // 		var org_kind='intro_no';
                    // 	}else{
                    // 		var org_kind='true_intro_no';
                    // 	}
                    // 	if(give_tb2.data[c2].org_kind==org_kind){
                    // 		var fie=give_tb2.data[c2].enfield;		
                    // 		if(_id("title_"+fie).checked==true){		
                    // 			dv_info.push("<div class='right_org_data' style='color:"+give_tb2.data[c2].color+"'>"+eval("orgData.data[0]."+fie)+"</div>");
                    // 		}
                    // 	}
                    // 	c2++;
                    // }
                    dv.innerHTML = "<nobr>" + dv_info.join('') + "</nobr>";
                } else {
                    $('.cg-busy').addClass('ng-hide');
                    error('查無資料');
                }
                // document.getElementById("BLOCK_orgseq_body5").onscroll = function () {
                // 	scrollWin1();
                // }
                // document.getElementById("r_m005").onscroll = function () {
                // 	scrollWin2();
                // }
                // document.getElementById("r_m006").onscroll = function () {
                // 	scrollWin3();
                // }

                //然後就直接展開
                if (orgData.data[0].line_label == 5) {
                    var dv_str = 'org5|' + orgData.data[0].mb_no;
                    // alert("Line 289:"+_id(dv_str).title);
                    load_dv = document.createElement("div");
                    load_dv.id = 'load_div_info5';
                    my.insertAfter(load_dv, my._id(dv_str));
                    //load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
                    my.orgseq2(orgData.data[0].mb_no, limit, org_kind, his, name);
                } else {
                    $('.cg-busy').addClass('ng-hide');
                    error('查無下線資料');
                    my.orgseq_num = 0;
                    $('#orgseq_num').val('0');
                    $('#orgseq_num').text('0');
                }
                $('#orgseq5_cnt').show();
            } else {
                $('.cg-busy').addClass('ng-hide');
                $('#orgseq5_cnt').hide();
            }
        });

    };

    my.orgseq2 = function () {


        // if (res.data.length == 0) {
        // 	$('.cg-busy').addClass('ng-hide');
        // }
        var true_intro_no5 = 'true_intro_no';
        var level_no5 = 'level_no1';
        var flag = false;
        var mb_no = arguments[0];
        var limit = arguments[1];
        var org_kind = arguments[2];
        var his = arguments[3];
        var name = arguments[4];
        CRUD.list({ task: "orgseq2", mb_no: mb_no ? mb_no : '', limit: limit ? limit : '', org_kind: org_kind ? org_kind : '', his: his ? his : '', name: name ? name : '' }, "GET").then(function (result) {
            res = result.data;
            my.orgseq_num = result.row_cnt;
            orgData = my.back_org;
            res_true_intro_no = res.true_intro_no;
            res_count = res.data.length;
            orgData_count = orgData.data.length;

            var i = 0;
            var obj = document.getElementById('org5|' + res.true_intro_no);
            my._id('load_div_info5').parentNode.removeChild(my._id('load_div_info5'));
            var objr = document.getElementById('rorg5|' + res.true_intro_no);
            if (result.search_status == 1) {
                while (i < res_count) {
                    objTarget = my._id('BLOCK_orgseq_body5');
                    dv = document.createElement('div');
                    dv.id = 'org5|' + res.data[i].mb_no; //設定tr id為org5_+直銷商編號
                    dv.title = (eval("res.data[i]." + level_no5));
                    my.insertAfter(dv, obj);
                    obj = dv;
                    dv_info = new Array();
                    //20100329 加字串前方加入&nbsp;  by Bear Dale
                    $o_index = _.findIndex(orgData.data, function (p) {
                        return p.mb_no === res.data[i].true_intro_no;
                    })
                    // $o_index = $o_index
                    dv_info.push('&nbsp;<span class="line_flag"></span>');
                    // dv_info.push('&nbsp;<span class=line_flag>' + my.chgNumToImg5(eval("orgData.data[orgData.index[res.data[i]." + true_intro_no5 + "]].parent_label+res.data[i].line_label")) + '</span>');
                    dv_info.push(res.data[i].grade_info);
                    dv_info.push('<span>' + (eval("res.data[i]." + level_no5) - tmp_level1) + ' 代</span>');
                    // if (my._id('f_org_kind5').value == 1) {
                    // 	dv_info.push('<span class=line_img><img src=orgseq/line_img/gr' + res.data[i].line_kind + '.gif></span>');
                    // }
                    dv_info.push("<span id='show_" + (res.data[i].mb_no) + "'>");
                    // if (res.data[i].m_status == '1') {
                    //     dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    // } else {
                    //     dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].tmp_no + "</span>");
                    // }

                    // if (res.data[i].m_status == '1') {
                    //     dv_info.push("<span id=name>" + res.data[i].mb_name + "</span>");
                    // } else {
                    //     dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].mb_name + "</span>");
                    // }

                    if (res.data[i].m_status == '2') {
                        dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].tmp_no + "</span>");
                    } else if (res.data[i].m_status == '1') {
                        dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    } else if (res.data[i].m_status == '4') {
                        dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    }

                    if (res.data[i].m_status == '2') {
                        dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].mb_name + "</span><span style='color:red'>(停權)</span>");

                    } else if (res.data[i].m_status == '1') {
                        dv_info.push("<span id=name>" + res.data[i].mb_name + "</span>");
                    } else if (res.data[i].m_status == '4') {
                        dv_info.push("<span id=name>" + res.data[i].mb_name + "</span><span style='color:#f766f7'>(待審核)</span>");
                    }

                    dv_info.push("<span style='color:blue'>" + res.data[i].pg_date + "</span>");
                    dv_info.push("</span>");
                    var html = "<div style='display:inline-block' class='oreseq_detail_block'>" +
                        "<button mb_no='" + res.data[i].mb_no + "' ng-click='ctrl.show_mb_detail($event)' data-toggle='modal' data-target='#myModal_mb_detail' type='button' style='margin-left:10px' class='btn'>" +
                        "詳細" +
                        "</button>" +
                        "</div>";
                    // dv_info.push(html);
                    $innHtml = "<nobr>" + dv_info.join('') + "</nobr>";
                    $(dv).append($compile($innHtml)($scope));
                    // dv.innerHTML = $compile($innHtml)($scope)[0];
                    // orgData.index[res.data[i]['mb_no']] = orgData_count + i;
                    // res.data[i].parent_label = eval("orgData.data[" + $o_index + "].parent_label+res.data[i].parent_label");
                    orgData.data.push(res.data[i]);
                    i++;
                }
                $('#BLOCK_orgseq_body5').find('div:first').hide();
            } else {
                while (i < res_count) {
                    objTarget = my._id('BLOCK_orgseq_body5');
                    dv = document.createElement('div');
                    dv.id = 'org5|' + res.data[i].mb_no; //設定tr id為org5_+直銷商編號
                    dv.title = (eval("res.data[i]." + level_no5));
                    my.insertAfter(dv, obj);
                    obj = dv;
                    dv_info = new Array();
                    //20100329 加字串前方加入&nbsp;  by Bear Dale
                    // $o_index = orgData.data.findIndex(p => p.mb_no === res.data[i].true_intro_no);
                    $o_index = _.findIndex(orgData.data, function (p) {
                        return p.mb_no === res.data[i].true_intro_no;
                    })
                    // $o_index = $o_index
                    dv_info.push('&nbsp;<span class="line_flag">' + my.chgNumToImg5(eval("orgData.data[" + $o_index + "].parent_label+res.data[i].line_label")) + '</span>');
                    // dv_info.push('&nbsp;<span class=line_flag>' + my.chgNumToImg5(eval("orgData.data[orgData.index[res.data[i]." + true_intro_no5 + "]].parent_label+res.data[i].line_label")) + '</span>');
                    dv_info.push(res.data[i].grade_info);
                    dv_info.push('<span>' + (eval("res.data[i]." + level_no5) - tmp_level1) + ' 代</span>');
                    // if (my._id('f_org_kind5').value == 1) {
                    // 	dv_info.push('<span class=line_img><img src=orgseq/line_img/gr' + res.data[i].line_kind + '.gif></span>');
                    // }
                    dv_info.push("<span id='show_" + (res.data[i].mb_no) + "'>");
                    // if (res.data[i].m_status == '1') {
                    //     dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    // } else {
                    //     dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].tmp_no + "</span>");
                    // }

                    // if (res.data[i].m_status == '1') {
                    //     dv_info.push("<span id=name>" + res.data[i].mb_name + "</span>");
                    // } else {
                    //     dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].mb_name + "</span>");
                    // }

                    if (res.data[i].m_status == '2') {
                        dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].tmp_no + "</span>");
                    } else if (res.data[i].m_status == '1') {
                        dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    } else if (res.data[i].m_status == '4') {
                        dv_info.push("<span>" + res.data[i].tmp_no + "</span>");
                    }

                    if (res.data[i].m_status == '2') {
                        dv_info.push("<span style='color:red;text-decoration: line-through;'>" + res.data[i].mb_name + "</span><span style='color:red'>(停權)</span>");

                    } else if (res.data[i].m_status == '1') {
                        dv_info.push("<span id=name>" + res.data[i].mb_name + "</span>");
                    } else if (res.data[i].m_status == '4') {
                        dv_info.push("<span id=name>" + res.data[i].mb_name + "</span><span style='color:#f766f7'>(待審核)</span>");
                    }

                    dv_info.push("<span style='color:blue'>" + res.data[i].pg_date + "</span>");
                    dv_info.push("</span>");
                    var html = "<div style='display:inline-block' class='oreseq_detail_block'>" +
                        "<button mb_no='" + res.data[i].mb_no + "' ng-click='ctrl.show_mb_detail($event)' data-toggle='modal' data-target='#myModal_mb_detail' type='button' style='margin-left:10px' class='btn'>" +
                        "詳細" +
                        "</button>" +
                        "</div>";
                    // dv_info.push(html);
                    $innHtml = "<nobr>" + dv_info.join('') + "</nobr>";
                    $(dv).append($compile($innHtml)($scope));
                    // dv.innerHTML = $compile($innHtml)($scope)[0];
                    $s_index = _.findIndex(orgData.data, function (p) {
                        return p.mb_no === res.data[i].true_intro_no;
                    })
                    // orgData.index[res.data[i]['mb_no']] = orgData_count + i;
                    res.data[i].parent_label = eval("orgData.data[" + $o_index + "].parent_label+res.data[i].parent_label");
                    orgData.data.push(res.data[i]);

                    //右邊資料			
                    objrTarget = my._id('r_m005');
                    dv = document.createElement('div');
                    dv.id = 'rorg5|' + res.data[i].mb_no; //設定tr id為org5_+直銷商編號
                    dv.setAttribute("className", "right_orgseq_list5");
                    dv.setAttribute("class", "right_orgseq_list5");
                    dv.title = res.data[i].mb_no;
                    my.insertAfter(dv, objr);
                    objr = dv;
                    dv_info = new Array();
                    //改成根據欄位抓資料
                    // var tmpObj1 = '<?php echo $gdata5?>';
                    // give_tb1 = tmpObj1.parseJSON();
                    // gcount1 = give_tb1.data.length;
                    // c1 = 0;
                    // while (c1 < gcount1) {
                    // 	if (_id('f_org_kind5').value == '1') {
                    // 		var org_kind = 'intro_no';
                    // 	} else {
                    // 		var org_kind = 'true_intro_no';
                    // 	}

                    // 	if (give_tb1.data[c1].org_kind == org_kind) {
                    // 		var fie = give_tb1.data[c1].enfield;
                    // 		if (_id("title_" + fie).checked == true) {
                    // 			if (res.data[i].block_view == '0') {
                    // 				dv_info.push("<div class='right_org_data' style='color:" + give_tb1.data[c1].color + "'>" + eval("res.data[i]." + fie) + '</div>');
                    // 			} else {
                    // 				dv_info.push("<div class='right_org_data' style='color:" + give_tb1.data[c1].color + "'>" + 'X' + '</div>');

                    // 			}

                    // 		}
                    // 	}
                    // 	c1++;
                    // }

                    dv.innerHTML = "<nobr>" + dv_info.join('') + "</nobr>";


                    //alert(dv.innerHTML);
                    //attachEventListener(dv,'click',hover_list,false);


                    i++;
                }
            }

            $('.cg-busy').addClass('ng-hide');
        });
    };

    my.order_cancel = function (id, num) {
        my.cancelOrderNum = num;
        my.cancelOrderId = id;
    };

    my.order_cancel_cancel = function () {
        my.cancelOrderId = null;
        my.cancelOrderNum = null;
    };

    my.goTop = function () {
        speed = 500, // 捲動速度
            $body = $(document),
            $win = $(window);

        $("html, body").animate({ scrollTop: 0 }, speed);
        $win.on({
            scroll: function () { goTopMove(); },
            resize: function () { goTopMove(); }
        });
    }

    my.order_cancel_chk = function () {
        if (my.cancelOrderId) {
            CRUD.update({ task: "order_cancel", id: my.cancelOrderId }, "POST").then(function (res) {
                my.order_list_fn();
                my.order_cancel_cancel();
            });
        }
    };

    my.order_search_fn = function (keyEvent) {
        if (keyEvent.which === 13) {

            my.order_list_fn(my.order_search);
        }
    };

    my.go_money_dtl = function (yymm, mbno, mbname) {
        localStorage.setItem('yymm', yymm);
        localStorage.setItem('mbno', mbno);
        $location.path("member_page/moneydtl/" + yymm);

    };

    my.e_cash_new2_1_detail = function (ord_no) {
        my.ecash21_ord_no = ord_no;
        $location.path("member_page/ecash21_dtl/" + ord_no);
    };
    my.e_cash_new2_2_detail = function (ord_no) {
        my.ecash21_ord_no = ord_no;
        $location.path("member_page/ecash22_dtl/" + ord_no);
    };

    if (my.member_page == "ecash21_dtl" && my.resertpw.uid) {
        $('.cg-busy').removeClass('ng-hide');
        if (my.resertpw.uid) {
            CRUD.detail({ task: "ecash21_dtl", ord_no: my.resertpw.uid }, "POST").then(function (res) {
                if (res.status == 1) {
                    my.ecash21_dtl = res.data;
                } else {
                    $location.path("member_page/e_cash_new2_1");
                }
            });
        }
    }

    if (my.member_page == "ecash_stat") {
        $('.cg-busy').removeClass('ng-hide');
        CRUD.detail({ task: "ecash_stat" }, "POST").then(function (res) {
            if (res.status == 1) {
                my.e_year = res.e_year;
                my.e_date = res.e_date;
                my.ecash_stat = res.ecash_stat;
            }
        });
        $('.cg-busy').addClass('ng-hide');
    }

    if (my.member_page == "member_news_page" && my.resertpw.uid) {
        if (my.resertpw.uid) {
            CRUD.detail({ task: "member_news_page", news_no: my.resertpw.uid }, "POST").then(function (res) {
                if (res.status == 1) {

                    my.member_backurl = "member_page/member_news?cur=" + my.cur;
                    res.data.content = $sce.trustAsHtml(res.data.content);
                    my.member_news_detail = res.data;
                    my.subtitle2 = '最新消息';
                    my.dtltitle2 = my.member_news_detail.name;
                    $('#member_news').parent().addClass('active');
                    $('#member_news').parents('#collapseCategory0').addClass('in').css('height:auto');
                    console.log(my.member_news_detail);
                } else {
                    $location.path("member_page/member_news");
                }
            });
        }
    }

    if (my.member_page == "moneydtl" && my.resertpw.uid) { //my.resertpw.uid=dtlID
        yymm = localStorage.getItem('yymm');
        mbno = localStorage.getItem('mbno');

        CRUD.detail({ task: "money_dtl", id: my.resertpw.uid, yymm: yymm, mbno: mbno }, "POST").then(function (res) {
            if (res.status == 1) {
                my.money_dtl = res.data;
                my.f_subtotal2 = parseInt(res.data.rsdata.subtotal) + parseInt(res.data.rsdata.nopay_money);
                if (res.data.rsdata.ok_g5_flag == 0) {
                    my.f_givemoney = 0;
                } else {
                    my.f_givemoney = res.data.rsdata.f_givemoney;
                }
                my.money_dtl.ec = res.ec;
                my.money_dtl.ec2 = res.ec2;
                my.money_dtl.ec3 = res.ec3;
                my.money_dtl.ec4 = res.ec4;
                console.log(my);
                $('.rwd-tables').basictable({ breakpoint: 768 });
            } else {
                $location.path("member_page/money_total");
            }
        });
    }

    my.go_order_dtl = function (id) {
        $location.path("member_page/orderdtl/" + id);
    };

    my.go_pay = function (type,orderNum) {
        // console.log(arguments);
        if(type == 'pb'){
            window.location.replace("/app/controllers/publicBank.php?task=orderSale&orderNum="+orderNum);
        }
        // var form = document.createElement("form");
        // form.method = "POST";
        // var element = document.createElement("input");

        // if (str == 'tspg') {
        //     element.value = 'order_submit_tspg';
        // } else {
        //     element.value = 'order_submit2';
        // }

        // element.name = 'task';
        // form.appendChild(element);

        // var element = document.createElement("input");
        // element.value = my.resertpw.uid;
        // element.name = 'id';
        // form.appendChild(element);
        // form.action = "components/cartcvs/api.php";
        // document.body.appendChild(form);

        // form.submit();
        // CRUD.setUrl("components/cartcvs/api.php");
        // CRUD.detail({task: "order_submit_auth",id:my.od_oid}, "POST").then(function(res){
        //     if(res.status == 1) {
        //         console.log(res);
        //         alert(res.c_status.err);
        //         setInterval(function(){
        //             location.href='/member_page/orderdtl/'+my.od_oid;
        //         },3000);
                
        //         // console.log('/member_page/orderdtl/'+oid);
        //     }else{
        //         alert('Error.');
        //         setInterval(function(){
        //             location.href='/member_page/orderdtl/'+my.od_oid;
        //         },3000);
                
        //     }
            
            
        // });
        // CRUD.setUrl("components/members/api.php");

    };

    if (my.member_page == "orderdtl" && my.resertpw.uid) { //my.resertpw.uid=dtlID
        CRUD.detail({ task: "order_dtl", id: my.resertpw.uid }, "POST").then(function (res) {
            if (res.status == 1) {
                console.log(res.data);
                my.order_dtl = res.data;
                my.od_oid = res.data.oid;
                my.pv = 0;
                my.bv = 0;
                my.bonus = 0;
                my.orderBundleArray = res.orderBundleArray;
                my.pv = res.data['totalpv'];
                my.bv = res.data['totalbv'];
                my.bonus = res.data['totalbonus'];

                my.bonusAmt = 0;
                angular.forEach(my.order_dtl.data, function (v, k) {
                    //my.pv+=parseInt(v.pv);
                    //my.bv+=parseInt(v.bv);
                    //my.bonus+=parseInt(v.bonus);
                    my.bonusAmt += parseInt(v.bonusAmt);
                });

                my.chk_pay.code4 = "1";

            } else {
                $location.path("member_page/order");
            }
        });
    }

    my.copyStr = function (id) {
        var TextRange = document.createRange();
        TextRange.selectNode(document.getElementById(id));
        sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(TextRange);
        document.execCommand("copy");
    };

    $scope.logout = function () {
        CRUD.update({ task: "logout" }, "POST").then(function (res) {
            $scope.member_status = 0;

            $location.url("index_page");
        });
    };


    my.chkresetPW = function () {
        try {
            var uid = my.resertpw.uid;
            var passwd = my.resertpw.passwd;
            var passwd2 = my.resertpw.passwd2;
            var err = 0;

            if (!uid) {
                error("非法請求");
                err++;
            }
            if (!passwd) {
                error($translate.instant('lg_member.please_input_pwd'));
                err++;
            }
            if (!passwd2) {
                err++;
            }
            if (passwd != passwd2) {
                error($translate.instant('lg_member.js_pwd_msg2'));
                err++;
            }

            if (err == 0) {
                CRUD.update({ task: "chkresetPW", uid: uid, passwd: passwd }, "POST").then(function (res) {
                    if ($scope.$parent.signupMode2020) {
                        $location.path("member_page/forgot202");
                    } else {
                        $location.path("member_page/login");
                        success(res.msg);
                    }
                });
            }
        } catch (e) { }
    };


    $scope.popup2 = {
        opened: false
    };
    $scope.open2 = function () {
        $scope.popup2.opened = true;
    };

    $scope.popup3 = {
        opened: false
    };
    $scope.open3 = function () {
        $scope.popup3.opened = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        minDate: new Date(),
        startingDay: 1
    };


    my.order_upd_fn = function () {
        try {
            if (!my.order_upd) my.order_upd = {};
            my.order_upd.task = "order_upd";
            var atmDate = my.order_upd.atmDate;
            var atmTime = my.order_upd.atmTime;
            var atmlastNum = my.order_upd.atmlastNum;
            var atmName = my.order_upd.atmName;
            var atmBank = my.order_upd.atmBank;
            var atmMoney = my.order_upd.atmMoney;

            var err = 0;

            if (!atmName) {
                error("請填寫匯款人");
                err++;
            }
            if (!atmBank) {
                error("請填寫銀行名稱");
                err++;
            }

            if (!atmDate) {
                error("請選擇付款日期");
                err++;
            }
            /*
            if(!atmTime){
                error("請輸入付款時間");
                err++;
            }
            */

            if (!atmlastNum) {
                error("請輸入付款帳號後五碼");
                err++;
            }



            if (err == 0) {
                var m = (atmDate.getMonth() + 1);
                if (m < 10) m = '0' + m;
                my.order_upd.atmDate = atmDate.getFullYear() + "-" + m + "-" + atmDate.getDate();
                CRUD.update(my.order_upd, "POST").then(function (res) {
                    if (res.status == 1) {
                        $('#myModal_ATM').modal('hide');
                        $timeout(function () {
                            $route.reload();
                        }, 200);
                    }
                });
            }

        } catch (e) {
            console.log(e);
            error("網路連線錯誤");
        }
    };



    CRUD.list({ task: 'likeProduct' }, "GET").then(function (res) {
        if (res.status == 1) {
            my.like_product_list = res.data;
            my.add_to_favorite_arr = res.favorite;
        }
    });
    if (my.member_page == "order") {
        my.order_list_fn();
    }
    if (my.member_page == "money_total") {
        $('.cg-busy').removeClass('ng-hide');
        my.money_list_fn();
    }
    if (my.member_page == "m_info") {
        $('.cg-busy').removeClass('ng-hide');
        my.m_info_fn();

        my.upload_cert = function ($type) {
            var img1 = $scope.previewImage;
            var img2 = $scope.previewImage2;
            var img3 = $scope.previewImage3;
            $('.cg-busy').removeClass('ng-hide');
            if ($type == '3') {
                CRUD.update({
                    task: 'upload_cert',
                    img3: img3
                }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.minfo.img3 = res.img3;
                        my.minfo.ex3 = '1';
                        $('.cg-busy').addClass('ng-hide');
                    }
                });
            }
            if ($type == '2') {
                CRUD.update({
                    task: 'upload_cert',
                    img2: img2
                }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.minfo.img2 = res.img2;
                        my.minfo.ex2 = '1';
                        $('.cg-busy').addClass('ng-hide');
                    }
                });
            }
            if ($type == '1') {
                CRUD.update({
                    task: 'upload_cert',
                    img1: img1
                }, "POST").then(function (res) {
                    if (res.status == 1) {
                        my.minfo.img1 = res.img1;
                        my.minfo.ex1 = '1';
                        $('.cg-busy').addClass('ng-hide');
                    }
                });
            }
        }

    }
    if (my.member_page == "orgseq_member") {
        $(".cg-busy").removeClass("ng-hide");
        my.orgseq_member_fn();
      }
    if (my.member_page == "stock_plan") {
        $('.cg-busy').removeClass('ng-hide');
        my.stock_plan_fn();
    }
    if (my.member_page == "annual_dividend") {
        $('.cg-busy').removeClass('ng-hide');
        my.annual_dividend_fn();
    }
    if (my.member_page == "e_cash") {
        $('.cg-busy').removeClass('ng-hide');
        my.ecash_list_fn();
    }
    if (my.member_page == "register_tb_list") {
        $('.cg-busy').removeClass('ng-hide');
        my.register_tb_list_fn();
    }
    if (my.member_page == "e_cash_new2_1") {
        $('.cg-busy').removeClass('ng-hide');
        my.ecash_new2_1_list_fn();
    }
    if (my.member_page == "e_cash_new2_2") {
        $('.cg-busy').removeClass('ng-hide');
        my.ecash_new2_2_list_fn();
    }
    if (my.member_page == "member_news") {
        $('.cg-busy').removeClass('ng-hide');
        my.member_news_list_fn();
    }
    if (my.member_page == "member_poster") {
        $('.cg-busy').removeClass('ng-hide');
        my.member_poster_list_fn();
    }
    if (my.member_page == "carry_treasure") {
        $('.cg-busy').removeClass('ng-hide');
        my.carry_treasure_fn();
    }
    if (my.member_page == "birthday_voucher") {
        $('.cg-busy').removeClass('ng-hide');
        my.birthday_voucher_fn();
    }
    if (my.member_page == "soybean_voucher") {
        $('.cg-busy').removeClass('ng-hide');
        my.soybean_voucher_fn();
    }
    if (my.member_page == "orgseq5") {
        $('.cg-busy').removeClass('ng-hide');
        my.orgseq5_fn();
    }
    if (my.member_page == "download_page") {
        $('.cg-busy').removeClass('ng-hide');
        my.download_page_fn();
    }
    if (my.member_page == "operation_page") {
        my.operation_page_fn();
    }
    if (my.member_page == "m_points") {
        my.m_points_fn();
    }
    if (my.member_page == "cash_back_list") {
        my.cash_back_list_fn();
    }
    if (my.member_page == "update_member") {
        // my.update_member_fn();
        my.chg_reco = false;

        my.payTypeChange = function(index) {
            if (index == 5) {
                my.signNew.dlvrType = '2';
            } else {
                my.signNew.dlvrType = '1';
                my.signNew.mo = '1';
            }
        }

        $scope.$watch("ctrl.u_same_member_info", function (value) {
            if (value) {
            //     console.log(my.signNew.udlvrCity2);
            //     console.log(my.signNew.udlvrAddr2);
              my.signNew.udlvrCity = my.city[my.signNew.udlvrCity2-1]; 
  
            //   my.signNew.udlvrCanton = my.canton[my.signNew.udlvrCity2][my.signNew.udlvrCanton2];
              my.signNew.udlvrAddr = my.signNew.udlvrAddr2;
              console.log(my.city);
            } else {
              my.signNew.udlvrAddr = "";
            }
            //console.log(my);
        });

        CRUD.list({ task: "get_recommend" }, "GET").then(function (res) {
            if (res.status == 1) {
                my.mb_data = res.mb_data;
                my.re_data = res.re_data;
            }
        })

        CRUD.detail({ task: "get_udlvrAddr" }, "GET").then(function (res) {
            my.signNew.udlvrAddr2 = res.address;
            my.signNew.udlvrCity2 = res.city_code;
            if (res.can_update == '1') {
                my.can_update = '1';
                my.reg_data = res.reg_data;
            } else {
                my.can_update = '0';
            }

            if (res.is_updating == '1') {
                my.is_updating = '1';
            } else {
                my.is_updating = '0';
            }

            if (res.dataShowMode == false) {

                my.member_page = 'order';
                error('您已申請過升級');
                $location.path("member_page/order");

            }

        });

        my.updateMembernow = function () {


            var addr = my.signNew.udlvrAddr;
            var city = my.signNew.udlvrCity;
            var canton = my.signNew.udlvrCanton;
            var payType = my.signNew.payType;
            var dlvrType = my.signNew.dlvrType;
            var dlvrLocation = my.signNew.dlvrLocation;
            var img1 = $scope.previewImage;
            var img2 = $scope.previewImage2;
            var img3 = $scope.previewImage3;
            
            var err = 0;
            var sid = my.mb_data.sid;
            var rechg = my.chg_reco;
            // var code = my.reg_data.random;
            if (!payType) {
                error("請輸入付款方式");
                err++;
            }
            if (!dlvrType) {
                error("請輸入取貨方式");
                err++;
            }

            if (dlvrType == 1) {
                if (!addr) {
                    error("請輸入寄件地址");
                    err++;
                }
            }

            if (payType == 1 || dlvrType == 2) {
                if (!dlvrLocation) {
                    error("請選擇服務中心");
                    err++;
                }
            }

            if (!my.memberTermsChk) {
                error($translate.instant('lg_member.js_enter_clause'));
                err++;
            }

            // if(!img1 || img1==''){
            //     error("請上傳身分證正面");
            //     err++;
            // }

            // if(!img2 || img2==''){
            //     error("請上傳身分證反面");
            //     err++;
            // }

            if (err == 0) {
                $timeout(function () {
                    CRUD.update({
                        task: "update_member_now",
                        rechg: rechg,
                        city: city,
                        canton: canton,
                        addr: addr,
                        payType: payType,
                        dlvrType: dlvrType,
                        dlvrLocation: dlvrLocation,
                        img1: img1,
                        img2: img2,
                        img3: img3

                    }, "POST", true).then(function (res) {
                        if (res.status == 1) {
                            my.signNew.udlvrAddr2 = res.address;
                            if (payType == 6) {
                                var url = res.url;
                                window.location.replace(url);
                            } else {
                                my.chk_pay.code4 = "2";
                                $("#myModal_CHKPAY2").modal("show");
                                success("升級申請成功");
                                $location.path("member_page/order");
                            }
                        } else {
                            error(res.msg);
                            $route.reload();
                        }
                    });
                }, 200);
            }


        }
    }


    my.toggle_pwd = function () {
        $type = $('.thepwd').prop('type');
        if ($type == 'password') {
            $('.close_eye').hide();
            $('.open_eye').show();
            // $('.thepwd').parent().addClass('shine');
            $('.thepwd').prop('type', 'text');
        } else {
            $('.close_eye').show();
            $('.open_eye').hide();
            // $('.thepwd').parent().removeClass('shine');
            $('.thepwd').prop('type', 'password');
        }
    }

    my.headsearchRes5 = function () {
        orgData = my.o_list;
        if ((orgData.data.length == 1) && (orgData.data[0].line_label.length == 1)) {
            obj = my._id('BLOCK_orgseq_body5');
            //移除first ul下的所有節點
            while (obj.childNodes.length > 0) {
                obj.removeChild(obj.lastChild);
            }
            /* dv=document.createElement('div');	
            dv.innerHTML=chgNumToImg5("00");		
            obj.appendChild(dv); */

            dv = document.createElement("div");
            dv.id = 'org5|' + orgData.data[0].mb_no;
            dv.title = eval("orgData.data[0]." + level_no5);
            obj.appendChild(dv);
            dv_info = new Array();

            if (orgData.data[0].line_label == 5) { //自動展開
                dv_info.push("<span class='line_flag'><img class='clickonnode' src='" + lastsnode.src + "' title='" + lastsnode.title + "'>" + chgGradeImg5(orgData.data[0].grade_class) + "</span>");
            } else {
                dv_info.push('<span class="line_flag">' + chgNumToImg5(orgData.data[0].line_label) + chgGradeImg5(orgData.data[0].grade_class) + '</span>');
            }

            dv_info.push('<span>0 代</span>');
            tmp_level1 = eval("orgData.data[0]." + level_no5);
            dv_info.push('<span class=line_img><img src=orgseq/line_img/gr' + orgData.data[0].line_kind + '.gif></span>');
            // if (my._id('f_org_kind5').value == 1) {
            // 	dv_info.push('<span class=line_img><img src=orgseq/line_img/gr' + orgData.data[0].line_kind + '.gif></span>');
            // }
            dv_info.push("<span id='show_" + (orgData.data[0].mb_no) + "'>");
            dv_info.push("<span id=id >" + orgData.data[0].mb_no + "</span>");
            dv_info.push("<span class=name >" + orgData.data[0].mb_name + "</span>");
            dv_info.push("</span>");
            dv.innerHTML = "<nobr>" + dv_info.join('') + "</nobr>";

            //右邊資料
            obj = my._id('r_m005');
            //移除first ul下的所有節點(保留表頭)
            while (obj.childNodes.length > 2) {
                obj.removeChild(obj.lastChild);
            }
            dv = document.createElement("div");
            dv.setAttribute("className", "right_orgseq_list5");
            dv.setAttribute("class", "right_orgseq_list5");
            dv.id = 'rorg5|' + orgData.data[0].mb_no;
            dv.title = eval("orgData.data[0]." + level_no5);
            obj.appendChild(dv);
            dv_info = new Array();
            //改成根據欄位抓資料
            var tmpObj2 = '<?php echo $gdata5?>';
            give_tb2 = tmpObj2.parseJSON();
            gcount2 = give_tb2.data.length;
            c2 = 0;
            while (c2 < gcount2) {
                var org_kind = 'intro_no';
                // if (my._id('f_org_kind5').value == '1') {
                // 	var org_kind = 'intro_no';
                // } else {
                // 	var org_kind = 'true_intro_no';
                // }
                if (give_tb2.data[c2].org_kind == org_kind) {
                    var fie = give_tb2.data[c2].enfield;
                    if (my._id("title_" + fie).checked == true) {
                        dv_info.push("<div class='right_org_data' style='color:" + give_tb2.data[c2].color + "'>" + eval("orgData.data[0]." + fie) + "</div>");
                    }
                }
                c2++;
            }
            dv.innerHTML = "<nobr>" + dv_info.join('') + "</nobr>";
        }
        document.getElementById("BLOCK_orgseq_body5").onscroll = function () {
            scrollWin1();
        }
        document.getElementById("r_m005").onscroll = function () {
            scrollWin2();
        }
        document.getElementById("r_m006").onscroll = function () {
            scrollWin3();
        }

        //然後就直接展開
        if (orgData.data[0].line_label == 5) {
            var dv_str = 'org5|' + orgData.data[0].mb_no;
            // alert("Line 289:"+my._id(dv_str).title);
            load_dv = document.createElement("div");
            load_dv.id = 'load_div_info5';
            my.insertAfter(load_dv, my._id(dv_str));
            //load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
            subsearchOrg5(orgData.data[0].mb_no);
        }

    }

    if (my.member_page == "orgseq") {
        // $('.cg-busy').removeClass('ng-hide');
        my.orgseq_fn();
        // my.headsearchRes5();
    }
    my.show_cart_modal = function (id, amt, img, promedia) {
        $scope.amt = amt;
        $scope.modal_cart_num = 1;
        $scope.total = $scope.modal_cart_num * $scope.amt;
        $scope.proid = id;
        $scope.imgname = img;
        $scope.promedia = promedia;
        $scope.promedia_url = $sce.trustAsResourceUrl("https://www.youtube.com/embed/" + promedia);
        angular.forEach(my.like_product_list, function (v, k) {
            if (v.id == id) {
                my.title = v.name;
                my.summary = v.summary;
            }
        });
    };
    my.go_end = function () {

        alertify.confirm($translate.instant('lg_member.chk_end'))
            .setHeader("<i class='fa fa-info-circle'></i> " + $translate.instant('lg_member.js_tip') + "")
            .set({ labels: { ok: $translate.instant('lg_main.cfm'), cancel: $translate.instant('lg_main.cancel') } })
            .set('onok', function (closeEvent) {
                CRUD.update({ task: "go_end" }, "GET").then(function (res) {
                    if (res.status == 1) {
                        $route.reload();
                        success(res.msg);
                    }
                });

            });

    };
    my.add_to_favorite = function (id) {
        var data = [];
        angular.forEach(my.add_to_favorite_arr, function (v, k) {
            data.push(v);
        });

        CRUD.setUrl("components/product/api.php");
        if (data.indexOf(id) == -1) {
            CRUD.update({ task: "add_to_favorite", data: id }, "GET").then(function (res) {
                if (res.status == 1) {

                }
            });
            my.add_to_favorite_arr[id] = id;
        } else {
            CRUD.update({ task: "delete_to_favorite", data: id }, "GET").then(function (res) {
                if (res.status == 1) {

                }
            });
            delete my.add_to_favorite_arr[id];
        }
        CRUD.setUrl("components/member/api.php");
    };


    my.recomUser = function () {
        CRUD.detail({ task: "get_share_url" }, "GET").then(function (res) {
            if (res.status == 1) {
                var $html = $translate.instant('lg_main.js_msg5') + "<br><span id='surl'>" + res.share_url + "</span>";
                var $html2 = "<span id='surl2'>您好，我是" + my.user.name + "，點選下方連結，輕鬆加入紅崴經銷商<br>" + res.share_url + "<br>和我一起科技養生，重塑人生</span>";
                $('#s_url').html($html);
                $('#s_url2').html($html2);
                $('#myModal_surl').addClass('in act');
                $('#myModal_surl').show();
                // alert("透過此網址成功註冊之經銷商，您即可成為該經銷商之推薦人<br>" + res.share_url);
            } else {
                // var $html = '您不是經銷商。';
                var $html = $translate.instant('lg_main.js_msg6');
                console.log($html);
                $('#s_url').html($html);
                $('#myModal_surl').addClass('in act');
                $('#myModal_surl').show();
            }
        });
    };
    my.recomUser2 = function () {
        CRUD.detail({ task: "get_share_url2" }, "GET").then(function (res) {
            if (res.status == 1) {
                var $html = $translate.instant('lg_main.js_msg5');
                $html += "<br><span id='surl'>" + res.share_url + "</span>";
                var $html2 = "<span id='surl2'>您好，我是" + my.user.name + "，點選下方連結，輕鬆加入紅崴網路福利會員<br>" + res.share_url + "<br>和我一起創造您的斜槓人生</span>";
                $('#s_url').html($html);
                $('#s_url2').html($html2);
                $('#myModal_surl').addClass('in act');
                $('#myModal_surl').show();
                // alert("透過此網址成功註冊之經銷商，您即可成為該經銷商之推薦人<br>" + res.share_url);
            } else {
                // var $html = '您不是經銷商。';
                // var $html = '您尚未完成經銷商入會申請手續，入會申請作業流程約需兩個工作日審核，請先使用會員編號14碼，進行推薦入會';
                var $html = $translate.instant("lg_main.js_msg6");
                $('#s_url').html($html);
                $('#myModal_surl').addClass('in act');
                $('#myModal_surl').show();
            }
        });
    };

    my.close_clip = function () {
        $('#myModal_surl').removeClass('in act');
        $('#myModal_surl').hide();
    }

    my.alert_clip = function () {
        alert('已複製');
    }

    my.changerecommend = function () {

        $('#myModal_updaterecommend').addClass('in act');
        $('#myModal_updaterecommend').show();
    };
    my.close_ur = function () {
        my.chg_reco = true;
        $('#myModal_updaterecommend').removeClass('in act');
        $('#myModal_updaterecommend').hide();
    }
    my.cancel_ur = function () {
        my.chg_reco = false;
        $('#myModal_updaterecommend').removeClass('in act');
        $('#myModal_updaterecommend').hide();
    }



    $rootScope.$on('scrollToEnd', function () {
        $('.rwd-tables').basictable({ breakpoint: 768 });
    });

    $rootScope.$on('finishData', function () {
        $('.cg-busy').addClass('ng-hide');
        $('.rwd-tables').basictable({ breakpoint: 768 });
    });

    $rootScope.$on('mleftmenu', function () {
        $('.active').parent().addClass('in');
        $('.active').parents('div').addClass('in');
    });


    my.printHtml = function (html) {
        var bodyHtml = document.body.innerHTML;
        document.body.innerHTML = html;
        window.print();
        document.body.innerHTML = bodyHtml;
    }
    my.onprint = function () {
        var html = $(".print_this").html();
        my.printHtml(html);
    }
    my.open_collapse = function (id) {
        my.open_collpase_val = id;
        // console.log(my);
    }
    my.multiauto = function (obj) {
        // console.log(obj);
    }

    my._id = function () {
        var elements = new Array();
        for (var i = 0; i < arguments.length; i++) {
            var element = arguments[i];
            if (typeof element == 'string') {
                element = document.getElementById(element);
            }
            if (arguments.length == 1) {
                return element;
            }
            elements.push(element);
        }
        return elements;
    }

    my.chgNumToImg5 = function ($strl) {
        var l = 0;
        var strl = $strl.length;
        var ar = new Array();

        while (l < strl) {
            ar.push($strl.charAt(l));
            l++;
        }
        var arc = 0;
        var arl = ar.length;
        var img = '';
        while (arc < arl) {
            switch (ar[arc]) {
                case '0':
                    img += "<img src='" + spacer.src + "' title='" + spacer.title + "'>";
                    break;
                case '1':
                    img += "<img src='" + vline.src + "' title='" + vline.title + "'>";
                    break;
                case '2':
                    img += "<img src='" + node.src + "' title='" + node.title + "'>";
                    break;
                case '3':
                    img += "<img src='" + lastnode.src + "' title='" + lastnode.title + "'>";
                    break;
                case '4':
                    img += "<img src='" + pnode.src + "' title='" + pnode.title + "'>";
                    break;
                case '5':
                    img += "<img src='" + lastpnode.src + "' title='" + lastpnode.title + "'>";
                    break;
                case '6':
                    img += "<img src='" + snode.src + "' title='" + snode.title + "'>";
                    break;
                case '7':
                    img += "<img src='" + lastsnode.src + "' title='" + lastsnode.title + "'>";
                    break;
            }
            arc++;
        }
        return img;
    }

    my.insertAfter = function (newer, obj) {
        if (obj.parentNode.lastChild == obj) {
            obj.parentNode.appendChild(newer);
        } else {
            obj.parentNode.insertBefore(newer, obj.nextSibling);
        }
    }

    my.show_mb_detail = function () {
        my.mbdetail = [];
        // console.log($(event.target).text());
        mb_no = $(event.target).attr('mb_no');
        my.check_ex = true;
        CRUD.list({ task: "search_mbno", search_mbno: mb_no ? mb_no : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.mbdetail = res.data;
                if (res.data.length == 0) {
                    $('.cg-busy').addClass('ng-hide');
                }
                if (res.is_member == '1') {
                    $('.modal_product_detail_list2').show();
                    $('.modal_product_detail_list').hide();
                } else {
                    $('.modal_product_detail_list2').hide();
                    $('.modal_product_detail_list').show();
                }
            }
        });
    }

    my.getEventTarget = function (event) {
        var targetElement = null;
        if (typeof event.target != "undefined") {
            targetElement = event.target;
        } else {
            targetElement = event.srcElement;
        }
        // //若指定為文字節點時
        while ((targetElement.nodeType == 3) && (targetElement.parentNode != null)) {
            targetElement = targetElement.parentNode;
        }
        return targetElement;
    }

    addEventListener('click', function (e) {
        my.nodeEvent5(e);
    }, false);


    my.nodeEvent5 = function (event) {

        if (typeof event == 'undefined') {
            event = window.event;
        }
        var target = my.getEventTarget(event);
        if (typeof target.src != 'undefined') {
            objTarget = target.parentNode.parentNode.parentNode;
            tarLevel_no1 = Number(target.parentNode.parentNode.parentNode.title);

            if ((target.title == 'pnode') || (target.title == 'lastpnode')) { //要展開
                if (objTarget != objTarget.parentNode.lastChild) {
                    if (Number(objTarget.nextSibling.title) > tarLevel_no1) {
                        tmp_obj = objTarget;
                        done = 0;
                        while ((tmp_obj.nextSibling.title) > tarLevel_no1 && done == 0) {
                            if (tmp_obj.nextSibling.title <= (tarLevel_no1 + 3)) {
                                tmp_obj.nextSibling.style.display = '';
                                //右邊區塊
                                rtme = "r" + tmp_obj.nextSibling.id;
                                my._id(rtme).style.display = '';
                                if (tmp_obj.nextSibling.title < (tarLevel_no1 + 3)) {
                                    ig = tmp_obj.nextSibling.getElementsByTagName('img');
                                    igl = ig.length;
                                    ic = 0;
                                    while (ic < igl) {
                                        if (tmp_obj.nextSibling != tmp_obj.parentNode.lastChild) {
                                            if (tmp_obj.nextSibling.title < tmp_obj.nextSibling.nextSibling.title) {
                                                if (ig[ic].title == 'pnode') {
                                                    ig[ic].src = snode.src;
                                                    ig[ic].title = snode.title;
                                                }
                                                if (ig[ic].title == 'lastpnode') {
                                                    ig[ic].src = lastsnode.src;
                                                    ig[ic].title = lastsnode.title;
                                                }
                                            }
                                        }
                                        ic++;
                                    }
                                }
                            }
                            if (tmp_obj.nextSibling != tmp_obj.parentNode.lastChild) {
                                tmp_obj = tmp_obj.nextSibling;
                            } else {
                                done = 1;
                            }
                        }
                    } else {
                        // alert("Line 518:"+target.id);
                        load_dv = document.createElement("div");
                        load_dv.id = 'load_div_info5';
                        insertAfter(load_dv, target.parentNode.parentNode.parentNode);
                        //load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
                        subsearchOrg5(target.parentNode.parentNode.parentNode.id.split('|')[1]);
                    }
                } else {
                    // alert("Line 526:"+target.id);
                    load_dv = document.createElement("div");
                    load_dv.id = 'load_div_info5';
                    insertAfter(load_dv, target.parentNode.parentNode.parentNode);
                    //load_dv.innerHTML="<span class='loading'><img src="+loader.src+" title="+loader.title+"></span>";
                    subsearchOrg5(target.parentNode.parentNode.parentNode.id.split('|')[1]);
                }
                if (target.title == 'pnode') {
                    target.src = snode.src;
                    target.title = snode.title;
                } else {
                    target.src = lastsnode.src;
                    target.title = lastsnode.title;
                }
            } else if ((target.title == 'snode') || (target.title == 'lastsnode')) { //要收合
                tmp_obj = objTarget;
                done = 0;
                while (Number(tmp_obj.nextSibling.title) > tarLevel_no1 && done == 0) {
                    tmp_obj.nextSibling.style.display = 'none';
                    //右邊區塊
                    rtme = "r" + tmp_obj.nextSibling.id;
                    my._id(rtme).style.display = 'none';
                    /* 因為有很多圖片包含空白等等... */
                    ig = tmp_obj.nextSibling.getElementsByTagName('img');
                    igl = ig.length;
                    ic = 0;
                    while (ic < igl) {
                        if (ig[ic].title == 'snode') {
                            ig[ic].src = pnode.src;
                            ig[ic].title = pnode.title;
                        }
                        if (ig[ic].title == 'lastsnode') {
                            ig[ic].src = lastpnode.src;
                            ig[ic].title = lastpnode.title;
                        }
                        ic++;
                    }
                    if (tmp_obj.nextSibling != tmp_obj.parentNode.lastChild) {
                        tmp_obj = tmp_obj.nextSibling;
                    } else {
                        done = 1;
                    }
                }
                if (target.title == 'snode') {
                    target.src = pnode.src;
                    target.title = pnode.title;
                } else {
                    target.src = lastpnode.src;
                    target.title = lastpnode.title;
                }
            }
        }
    }

    my.coupon_exchange = function () {
        console.log(my);
        CRUD.detail({ task: "exchange_coupon_all", num: my.coupon_num }, "POST").then(function (res) {
            if (res.success) {
                success("兌換申請成功,請至門市領取");
                $route.reload();
            } else {
                if (res.error_msg) {
                    error(res.error_msg);
                }
            }
        });
    }

    my.change_coupon_num = function () {
        $val = parseInt($('#coupon_input').val());
        if ($val < 1 || isNaN($val)) {
            $('#coupon_input').val(1);
        }
        console.log($val);
    }

    my.transfer_data = function () {
        CRUD.list({ task: "transfer_data" }, "GET").then(function (res) {

        });
    }
    my.pw_change_val = 0;

    my.pw_change = function () {
        $val = $('input[name="new_password"]:checked').val();
        if ($val == 1) {
            $('#pass_div').show();
            $('#pass_div').find('label').show();
            $('#pass_div').find('input').show();
            $('#pass_div').find('input').prop('disabled', false);
        } else {
            $('#pass_div').hide();
            $('#pass_div').find('label').hide();
            $('#pass_div').find('input').hide();
            $('#pass_div').find('input').prop('disabled', true);
        }
    }

    my.cc = function () {
        console.log(my);
    }

    my.mlm_order_list_fn = function (search_str) {
        CRUD.list({ task: "mlm_order_list", search_str: search_str ? search_str : '' }, "GET").then(function (res) {
            if (res.status == 1) {
                my.mlm_order_list = res.order_list;
            }

        });
    };
    my.go_mlm_order_dtl = function (id) {
        $location.path("member_page/mlm_orderdtl/" + id);
    };
    if (my.member_page == "mlm_orderdtl" && my.resertpw.uid) { //my.resertpw.uid=dtlID
        CRUD.detail({ task: "mlm_order_dtl", id: my.resertpw.uid }, "POST").then(function (res) {
            if (res.status == 1) {
                my.mlm_order_dtl = res.data;
                my.pv = 0;
                my.bv = 0;
                my.bonus = 0;
                my.pv = res.data['totalpv'];
                my.bv = res.data['totalbv'];
                my.bonus = res.data['totalbonus'];
                console.log(my.mlm_order_dtl);

                my.bonusAmt = 0;
                angular.forEach(my.mlm_order_dtl.data, function (v, k) {
                    //my.pv+=parseInt(v.pv);
                    //my.bv+=parseInt(v.bv);
                    //my.bonus+=parseInt(v.bonus);
                    my.bonusAmt += parseInt(v.bonusAmt);
                });

                my.chk_pay.code4 = "1";

            } else {
                $location.path("member_page/mlm_order");
            }
        });
    }

    if (my.member_page == "mlm_order") {
        my.mlm_order_list_fn();
    }

    my.check_tspg = function () {
        CRUD.list({ task: "check_tspg" }, "GET").then(function (res) {

        });
    }

    my.get_erate = function () {
        CRUD.list({ task: "get_erate" }, "GET").then(function (res) {

        });
    }

    my.test = function () {
        console.log('soap');
        CRUD.list({ task: "get_soap" }, "GET").then(function (res) {

        });
    }

    my.pmpage = function () {
        console.log('pm');
        $('.pmpage').toggle();
        $('.points_page').toggle();
        $('#pmpage').toggle();
    }


}]).controller('member_page2', ['$rootScope', '$scope', '$http', '$location', '$route', '$routeParams', '$translate', 'CRUD', '$filter', '$sce', function ($rootScope, $scope, $http, $location, $route, $routeParams, $translate, CRUD, $filter, $sce) {
    var my = this;



}]);