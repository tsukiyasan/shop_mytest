app.controller('contact_page',['$rootScope','$scope','$http','$location','$route','$routeParams','$translate','CRUD','$filter','$sce',function($rootScope,$scope, $http, $location,$route,$routeParams,$translate,CRUD,$filter,$sce) {
	var my = this;
	
	CRUD.setUrl("app/controllers/eways.php");
	CRUD.detail({task:'siteinfo'},"GET").then(function(res){
		if(res.status==1){
			my.siteinfo=res.data;
		}
	});
	
	my.get_addrCode=function(){	
		var turl=CRUD.getUrl();
		CRUD.setUrl("app/controllers/eways.php");
		CRUD.detail({task: "get_addrCode"}, "GET").then(function(res){
			if(res.status == 1) {
				my.city = res.city[2];
				my.canton = res.canton;
			}
		});
		CRUD.setUrl(turl);
	};
	my.get_addrCode();
	
	my.contact={};
		
	if($routeParams.qtype == 3)
	{
		my.contact.type = $translate.instant('lg_main.index_esignup');
	}
	
	my.contact_sub=function(){
		var err=0;
		
		if(!my.contact.name){
			error($translate.instant('lg_main.write') + $translate.instant('lg_contact.contact_name'));
			err=1;
		}
		
		if(!my.contact.email){
			error($translate.instant('lg_main.write') + $translate.instant('lg_contact.contact_mail'));
			err=1;
		}
		
		if(!my.contact.type){
			error($translate.instant('lg_main.write') + $translate.instant('lg_contact.contact_question_type'));
			err++;
		}
		
		if(!my.contact.city){
			error($translate.instant('lg_main.write') + $translate.instant('lg_contact.contact_city'));
			err++;
		}
		
		if(!my.contact.content){
			error($translate.instant('lg_main.write') + $translate.instant('lg_contact.contact_notes'));
			err=1;
		}
		
		if(err==0){
			CRUD.setUrl("components/contact/api.php");
			my.contact.task="update";
			
			CRUD.update(my.contact,"POST").then(function(res){
				if(res.status==1){
					my.contact={};
					success(res.msg);
					
				}
			});
		}
		
		
	};
}]);

