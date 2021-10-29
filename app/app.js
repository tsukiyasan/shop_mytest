/**
 * Cube - Bootstrap Admin Theme
 * Copyright 2014 Phoonio
 */

var app = angular.module('goodarch2uApp', [
	'ngRoute',
	'ngAnimate',
	'pascalprecht.translate', 
	'angular-storage',
	'ngCookies',
	'ui.bootstrap',
	'cgBusy',
	'ngTouch',
	'googleplus'
]);

app.config(['$translateProvider','$locationProvider','$compileProvider','GooglePlusProvider','$sceDelegateProvider', function($translateProvider,$locationProvider,$compileProvider,GooglePlusProvider,$sceDelegateProvider) {
  $translateProvider.useUrlLoader('lang/lang.php')
  .preferredLanguage(syslang);
  
  $locationProvider.html5Mode({
	  enabled: true,
	  requireBase: false
	});
	
  $sceDelegateProvider.resourceUrlWhitelist([
	'self',
	'http://shop.goodarch2u.com.tw/**'
  ]);
	
  $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|ftp|mailto|file|javascript):/);
  
  
}]);

