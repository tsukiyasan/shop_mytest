angular.module('managerApp').factory('pageCtrl',['$rootScope','$location','urlCtrl', function ($rootScope,$location,urlCtrl) {
    return {
        path: "",
        cur: 0,
        curpage: {},
        pageCnt: 0,
        pages: [],
        allpage: [],
        createPageCtrl: function(pageCnt, cur) {
            cur = cur < 1 ? 1 : cur > pageCnt ? pageCnt : cur;
            var self = this;
            self.cur = cur;
            self.path = $location.path();
            self.pageCnt = pageCnt;
            self.pages = [];
            self.allpage = [];
            self.curpage = {'p':cur};
            self.prepage = cur - 1 > 0 ? {'p':cur - 1} : {'p':1};
            self.postpage = cur + 1 < pageCnt ? {'p':cur + 1} : {'p':pageCnt};
            var disNum = 2;
            var x = cur - disNum < 1 ? 1 : (pageCnt - disNum * 2 < 1 ? 1 : cur - disNum);
            var y = cur - disNum < 1 ? (disNum * 2 + 1 > pageCnt ? pageCnt : disNum * 2 + 1) : (cur + disNum > pageCnt ? pageCnt : cur + disNum);
            for (var i = x; i <= y; i++) {
                if(cur == i) {
                    self.pages.push({'p':i, 'active':true});
                } else {
                    self.pages.push({'p':i, 'active':false});
                }
            }
            
            for(var i = 1; i <= pageCnt; i++) {
                self.allpage.push({'p':i});
            }
        },
        pageChg: function(cur,param){
            var self = this;
            if(self.cur != cur) {
                if(!param)param={};
                var params = Object.assign({}, param);
                params.page = cur;
                urlCtrl.go(self.path, params);
            }
        }
    }
}]);