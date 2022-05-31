app.controller('uploadfiles_list', ['$rootScope', '$scope', 'urlCtrl', '$location', '$route', '$routeParams', 'CRUD', '$translate', function($rootScope, $scope, urlCtrl, $location, $route, $routeParams, CRUD, $translate) {
    var my = this;
    var param = urlCtrl.deaes($location.hash());

    if (param) {
        CRUD.setUrl("components/uploadfiles/api.php");

        my.funcPerm = $rootScope.funclist['uploadfiles'];

        my.data_list = [];
        my.params = {
            page: !param.page ? 1 : param.page,
            search: !param.search ? { name: "" } : param.search,
            orderby: !param.orderby ? { newsDate: "asc", pubDate: "desc" } : param.orderby
        }
        my.odrhash = urlCtrl.enaes({ component: 'uploadfiles', p: param });
        my.list = function() {
            console.log('list');
            CRUD.list(my.params, "GET").then(function(res) {
                console.log(res);
                if (res.data.status == 1) {
                    my.cnt = res.cnt;
                    my.data_list = res.data.data;
                }
            });
        };

        my.test = function() {
            console.log("repeat complete");
        }

        my.delete = function(id) {

            if (my.funcPerm.D == 'true') {
                CRUD.del({ id: id }, "POST").then(function(res) {
                    if (res.status == 1) {
                        success(res.msg);
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
            urlCtrl.go("/uploadfiles_page", param);
        }


        my.publishChange = function(id, publish) {
            console.log(my);
            if (my.funcPerm.U == 'true') {
                var params = {
                    id: id,
                    publish: 1 - publish
                };


                CRUD.update(params, "POST").then(function(res) {
                    if (res.status == 1) {
                        success(res.msg);
                        my.list();
                    }
                });
            }

        };

        my.list();

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
                    console.log(selectedid);
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
                                        my.selected = [];
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
    }


}])

.controller('uploadfiles_page', ['$rootScope', '$scope', 'urlCtrl', '$location', '$route', '$routeParams', 'CRUD', '$translate', 'Upload', function($rootScope, $scope, urlCtrl, $location, $route, $routeParams, CRUD, $translate, Upload) {
    var my = this;
    var param = urlCtrl.deaes($location.hash()); //解碼網址hash
    if (param) {
        CRUD.setUrl("components/uploadfiles/api.php");

        my.funcPerm = $rootScope.funclist['uploadfiles'];

        my.uploadfiles = {};
        my.uploadfiles_files = [];
        $scope.previewFile = [];

        var filelimit = 1;
        my.filelist = [];
        for (var i = 1; i <= filelimit; i++) {
            my.filelist.push(i);
        }

        var listparams = !param.listparams ? {} : param.listparams;

        my.detail = function() {
            CRUD.detail(my.params, "GET").then(function(res) {
                if (res.status == 1) {
                    my.uploadfiles = res.data;
                    my.filetype = res.filetype;
                    my.filetarget = res.filetarget;

                    if (my.uploadfiles.linktype == "database") {
                        CRUD.getDBPagePath(my.uploadfiles.tablename, my.uploadfiles.databaseid)
                            .then(function(res) {
                                my.uploadfiles.path = $translate.instant(my.uploadfiles.databasename) + res.path;
                                console.log(my.uploadfiles.path);
                            });
                    }
                }
            });
            console.log(my);
        }

        if (param.id) {
            my.actionType = 1;
            my.params = {
                id: !param.id ? null : param.id
            }
            my.detail();
        } else {
            my.uploadfiles = {
                linktype: 'link'
            }
            my.actionType = 0;

        }
        my.submit = function() {

            if ((!param.id && my.funcPerm.C == 'true') || (param.id && my.funcPerm.U == 'true')) {

                if (!my.uploadfile && my.uploadfiles.name.length == 0) {
                    alert($translate.instant('lg_uploadfiles.uploadfiles_upload_files') + $translate.instant('lg_main.empty'));
                    return false;
                }

                if (my.uploadfile) {
                    file_name = my.uploadfile.name;
                    file_sub_name = my.getFileExtension(file_name);
                } else {
                    file_name = my.uploadfiles.name;
                    file_sub_name = my.getFileExtension(file_name);
                }

                my.params = {
                    id: !param.id ? null : param.id,
                    name: my.uploadfiles.name,
                    publish: my.uploadfiles.publish,
                    file: $scope.previewFile,
                    file_name: file_name,
                    file_sub_name: file_sub_name,
                    file_type: my.filetype,
                    file_target: my.filetarget,
                    note: my.uploadfiles.note,
                    tablename: my.uploadfiles.tablename,
                    databaseid: my.uploadfiles.databaseid,
                    databasename: my.uploadfiles.databasename,
                    linktype: my.uploadfiles.linktype,
                    linkurl: my.uploadfiles.linkurl
                }
                console.log(my);

                if (!my.params.name) {
                    alert($translate.instant('lg_main.title') + $translate.instant('lg_main.empty'));
                    return false;
                }

                if (!my.params.id) {
                    if ($scope.previewFile.length == 0) {
                        alert($translate.instant('lg_uploadfiles.uploadfiles_upload_image') + $translate.instant('lg_main.empty'));
                        return false;
                    }

                }

                if (!my.filetype) {
                    alert($translate.instant('lg_uploadfiles.uploadfiles_file_type') + $translate.instant('lg_main.empty'));
                    return false;
                }

                if (!my.filetarget) {
                    alert($translate.instant('lg_uploadfiles.uploadfiles_file_target') + $translate.instant('lg_main.empty'));
                    return false;
                }

                CRUD.update(my.params, "POST").then(function(res) {
                    if (res.status == 1) {
                        success(res.msg);
                        my.cancel();
                    }
                });
            }

        }

        my.cancel = function() {
            urlCtrl.go("/uploadfiles_list", listparams);
        }

        my.getFileExtension = function(filename) {
            return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
        }

    }


}]);