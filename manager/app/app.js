/**
 * Cube - Bootstrap Admin Theme
 * Copyright 2014 Phoonio
 */

var app = angular.module('managerApp', [
	'ngRoute',
	'angular-loading-bar',
	'ngAnimate',
	'pascalprecht.translate', 
	'angular-storage',
	'ui.bootstrap',
	'cgBusy',
	'ngMaterial',
	'AngularPrint',
	'ngCookies',
	'chart.js',
	'ngFileUpload'
]);

app.config(['$translateProvider','$mdThemingProvider', function($translateProvider,$mdThemingProvider) {
  /*$translateProvider
  .useStaticFilesLoader({
    prefix: 'lang/',
    suffix: '.json'
  })*/
  $translateProvider.useUrlLoader('lang/lang.php')
  .preferredLanguage('zh-tw');
  $mdThemingProvider.theme('default').primaryPalette('blue');
}]);

app.config(['cfpLoadingBarProvider', '$httpProvider', function(cfpLoadingBarProvider, $httpProvider) {
	cfpLoadingBarProvider.includeBar = true;
	cfpLoadingBarProvider.includeSpinner = true;
	cfpLoadingBarProvider.latencyThreshold = 100;
	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
}]);
/**
 * Configure the Routes
 */


