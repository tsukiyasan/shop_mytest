app.controller('genomics_list', ['$rootScope', '$scope', '$http', '$location', '$route', '$translate', 'CRUD', 'urlCtrl', function($rootScope, $scope, $http, $location, $route, $translate, CRUD, urlCtrl) {
    var my = this;
    var param = urlCtrl.deaes($location.hash());
    if (param) {
        CRUD.setUrl("components/genomics/api.php");

        my.funcPerm = $rootScope.funclist['genomics'];

        var path = $location.path();
        my.data_list = [];
        my.params = {
            page: !param.page ? 1 : param.page,
            search: '',
            orderby: !param.orderby ? { report_date: "desc" } : param.orderby
        }

        my.list = function() {
            CRUD.list(my.params, "GET").then(function(res) {
                if (res.data.status == 1) {
                    my.selected = [];
                    my.cnt = res.cnt;
                    my.data_list = res.data.data;
                }
            });
        }

        my.snstatusChg = function(t) {
            my.params.sn_status_type = t;
            my.list();
        }

        my.posttypeChg = function(t) {
            my.params.posttype_type = t;
            my.list();
        }

        my.sort = function(field) {

            if (my.params.orderby[field] == "asc") {
                delete my.params.orderby[field];
                my.params.orderby[field] = "desc";
            } else {
                delete my.params.orderby[field];
                my.params.orderby[field] = "asc";
            }
            my.refresh();
        }

        my.refresh = function() {
            my.list();
        }

        my.gopage = function(id) {
            var param = {
                id: id,
                listparams: my.params
            }
            urlCtrl.go("/news_page", param);
        }

        my.publishChange = function(id, publish) {
            var params = {
                id: id,
                publish: 1 - publish
            };

            if (my.funcPerm.U == 'true') {
                CRUD.update(params, "POST").then(function(res) {
                    if (res.status == 1) {
                        success(res.msg);
                        my.list();
                    }
                });
            }


        };

        my.delete = function(id) {

            if (my.funcPerm.D == 'true') {
                CRUD.del({ id: id }, "POST").then(function(res) {
                    if (res.status == 1) {
                        success(res.msg);
                        my.refresh();
                    }
                });
            }


        }

        //List Check Box Control
        my.selected = [];
        my.toggle = function(item, list) {
            var idx = list.indexOf(item);
            if (idx > -1) {
                list.splice(idx, 1);
            } else {
                list.push(item);
            }
        };
        my.exists = function(item, list) {
            return list.indexOf(item) > -1;
        };
        my.isIndeterminate = function() {
            return (my.selected.length !== 0 && my.selected.length !== my.data_list.length);
        };
        my.isChecked = function() {
            return my.selected.length === my.data_list.length && my.data_list.length > 0;
        };
        my.toggleAll = function() {
            if (my.selected.length === my.data_list.length) {
                my.selected = [];
            } else if (my.selected.length === 0 || my.selected.length > 0) {
                my.selected = my.data_list.slice(0);
            }
        };
        my.batchOperate = function(action) {

            if (((action == "open" || action == "close") && my.funcPerm.U == 'true') || (action == 'delete' && my.funcPerm.D == 'true')) {
                if (my.selected.length > 0) {
                    var selectedid = [];
                    angular.forEach(my.selected, function(value, key) {
                        selectedid.push(value.id);
                    });
                    alertify
                        .confirm($translate.instant("lg_main." + action + "confirm"))
                        .setHeader("<i class='fa fa-help-circle'></i> " + $translate.instant("lg_main.batchconfirm"))
                        .set({ labels: { ok: $translate.instant("lg_main.yes"), cancel: $translate.instant("lg_main.no") } })
                        .set('onok', function(closeEvent) {
                            var params = {
                                task: 'batchOperate',
                                id: selectedid,
                                action: action
                            };
                            CRUD.update(params, "POST")
                                .then(function(res) {
                                    if (res.status == 1) {
                                        success($translate.instant("lg_main." + action + "success"));
                                        my.list();
                                    } else {
                                        error($translate.instant("lg_main." + action + "fail"));
                                    }
                                })
                        });
                } else {
                    message($translate.instant("lg_main.nochoice"));
                }
            }


        }

        my.list();
    }
}])