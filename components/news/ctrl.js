app.controller('news_list',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce','$window',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce,$window) {
	var my=this;
	
	my.cur=!$location.search().cur?1:$location.search().cur;
	my.add_to_favorite_arr=[];
		
	CRUD.setUrl("components/news/api.php");
	
	my.list = function() {
		CRUD.list({task: "list",page:my.cur , type: my.news_page}, "GET").then(function(res){
			if(res.status == 1) {
				my.data_list = res.data;
				my.cnt = res.cnt;
			}
		});
	}
	my.list();
	
	my.list_detail = function() {
        CRUD.detail({ task: "list_detail", type: my.news_page }, "GET").then(function(res) {
            if (res.status == 1) {
                my.news_detail = res.data;
                my.news_detail.content = $sce.trustAsHtml(res.data.content);
                my.news_detail.xcontent = $sce.trustAsHtml(res.xcontent);
                $scope._name = res.data.name;
                $scope._content = res.data._content;
                $scope._imgwidth = res.data._imgwidth;
                $scope._imgheight = res.data._imgheight;
                $scope._content_img = res.data._content_img;
            }
        });
    }

    my.go_news = function() {
        $('.news-menu').removeClass('active');
        $('.news').addClass('active');
        my.news_page = 'news_list';
        my.cur = '1';
        my.list();
        localStorage.setItem('news_page', my.news_page);
    }

    my.go_calendar = function() {
        $('.news-menu').removeClass('active');
        $('.cale').addClass('active');
        my.news_page = 'calendar_list';
        my.cur = '1';
        my.list_detail();
        localStorage.setItem('news_page', my.news_page);
    }

    my.go_course = function() {
        $('.news-menu').removeClass('active');
        $('.cour').addClass('active');
        my.news_page = 'course_list';
        my.cur = '1';
        my.list();
        localStorage.setItem('news_page', my.news_page);
    }

    my.go_duty = function() {
        $('.news-menu').removeClass('active');
        $('.duty').addClass('active');
        my.news_page = 'duty_list';
        my.cur = '1';
        my.list_detail();
        localStorage.setItem('news_page', my.news_page);
    }

    var news_page = localStorage.getItem('news_page');
    if (news_page != null) {
        my.news_page = news_page;
        if (news_page == 'calendar_list') {
            $('.news-menu').removeClass('active');
            $('.cale').addClass('active');
            my.go_calendar();
        }else if (news_page == 'course_list') {
            $('.news-menu').removeClass('active');
            $('.cour').addClass('active');
        }else if (news_page == 'duty_list') {
            $('.news-menu').removeClass('active');
            $('.duty').addClass('active');
            my.go_duty();
        }else{
            localStorage.removeItem('news_page');
        }

        
    }else{
        my.news_page = 'news_list';
        $('.news-menu').removeClass('active');
        $('.news').addClass('active');
    }

	if (my.news_page == '' || my.news_page == undefined) {
        my.news_page = 'news_list';
    }
}]).controller('news_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my=this;
	
	my.cur=parseInt($location.search().cur);
	if(!my.cur)my.cur=1;
	my.id=parseInt($routeParams.id);
	my.backurl="news_list?cur="+my.cur;
	CRUD.setUrl("components/news/api.php");
	
	CRUD.detail({task: "detail",id:my.id}, "GET").then(function(res){
		if(res.status == 1) {
			my.news_detail= res.data;
			my.news_detail.content = $sce.trustAsHtml(res.data.content);
			
			$scope._name = res.data.name;
			$scope._content = res.data._content;
			$scope._imgwidth = res.data._imgwidth;
			$scope._imgheight = res.data._imgheight;
			$scope._content_img = res.data._content_img;
			
		}else{
			error(res.msg).set('onok', function(closeEvent){ 
				$location.path(my.backurl);
				$rootScope.$apply();
			} );
			
		}
	});

}]);

