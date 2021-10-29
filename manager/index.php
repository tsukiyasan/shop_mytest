<?php
	include("config.php");
	$path="";
	$dir = scandir($path."modals");
	$dir_arr=array();
	$modals="";
	foreach($dir as $key=>$item){
		if($key>1 && is_file($path."modals/".$item."/ctrl.js")){
			$modals.="<script src=\"modals/$item/ctrl.js?v={$libVersion}\"></script>";
		}
	}
?>
<!DOCTYPE html>
<html ng-app="managerApp">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="robots" content="none" />
		<title>後台管理系統 - 俊達生技</title>
		<!-- Favicon -->
		<link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />
		
		<!-- css -->
		<!-- ModalWindowEffects -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/ModalWindowEffects/css/component.css" />
		<!-- jquery-ui.dnd -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/jquery-ui.dnd/jquery-ui.min.css" />
		<!-- bootstrap -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/bootstrap-manager/bootstrap.min.css" />
		<!-- bootstrap-datepicker -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/bootstrap-datepicker.1.6.0/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/daterangepicker/daterangepicker.css" />
		
		<!-- alertify js-->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/alertifyjs/css/alertify.min.css" />
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/alertifyjs/css/themes/default.min.css" />
		<!-- Augular loading bar-->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/angular-loading-bar/loading-bar.min.css" />
		<!-- Augular busy-->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/angular-busy/angular-busy.css" />
		
		<!-- theme styles -->
		
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/CubeTheme/theme_styles.css" />
		
		<!-- Angular bootstrap -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/agbootstrap/datepicker.css" />
		<!-- Augular material -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/material-1.1.0-rc3/angular-material.min.css" />
		<!-- Augular angularPrint -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/angularPrint/angularPrint.css" />
		<!-- Augular angular-chart -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/angular-chart/angular-chart.css" />
		<!-- JQuery Plugin -->
		<!-- nanoscroller -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/nanoScroller-0.8.7/nanoscroller.css" />
		<!-- fontawesome -->
		<link rel="stylesheet" type="text/css" href="<?=$path?>/lib/font-awesome-4.5.0/css/font-awesome.min.css">
		<!-- google font libraries -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
		
		
  
	</head>
	
	<body id="site_bg">
		<div id="mbody" ng-controller="app_ctrl as appctrl" ng-include="appctrl.page"  class="fixed-footer pace-done fixed-header"></div>
	</body>

	<!-- global scripts -->
	<!-- JQuery -->
	<script src="<?=$path?>/lib/jquery-2.2.2/jquery-2.2.2.min.js"></script>
	<!-- JQuery-UI-dnd -->
	<script src="<?=$path?>/lib/jquery-ui.dnd/jquery-ui.min.js"></script>
	<!-- AES -->
	<script src="<?=$path?>/lib/cryptojs/aes.js"></script>
	<!-- JQuery Plugin -->
	<!-- nanoscroller -->
	<script src="<?=$path?>/lib/nanoScroller-0.8.7/nanoscroller.min.js"></script>
	<!-- bootstrap -->
	<script src="<?=$path?>/lib/bootstrap-3.3.6/js/bootstrap.min.js"></script>
	<!-- bootstrap datepicker-->
	<script src="<?=$path?>/lib/moment/moment.min.js"></script>
	<script src="<?=$path?>/lib/bootstrap-datepicker.1.6.0/js/bootstrap-datepicker.min.js"></script>
	<script src="<?=$path?>/lib/bootstrap-datepicker.1.6.0/locales/bootstrap-datepicker.zh-TW.min.js"></script>
	<script src="<?=$path?>/lib/daterangepicker/daterangepicker.js"></script>
	
	<!-- alertify js-->
	<script src="<?=$path?>/lib/alertifyjs/alertify.min.js"></script>
	<!-- 通用method -->
	<script src="app/common.js"></script>
	<!-- ckeditor -->
	<script src="<?=$path?>/lib/ckeditor/ckeditor.js?v=1"></script>
	<!-- ModalWindowEffects -->
	<script src="<?=$path?>/lib/ModalWindowEffects/js/classie.js"></script>
	<script src="<?=$path?>/lib/ModalWindowEffects/js/modalEffects.js"></script>
	<script src="<?=$path?>/lib/ModalWindowEffects/js/modernizr.custom.js"></script>
	<!-- filestyle -->
    <script src="<?=$path?>/lib/filestyle/bootstrap-filestyle.min.js"></script>
	<!-- angularJS -->
	<script src="<?=$path?>/lib/angular-1.5.2/angular.min.js"></script>
	<script src="<?=$path?>/lib/angular-1.5.2/angular-route.min.js"></script>
	<script src="<?=$path?>/lib/angular-1.5.2/angular-animate.js"></script>
	<script src="<?=$path?>/lib/angular-1.5.2/angular-cookies.js"></script>
	<script src="<?=$path?>/lib/angular-1.5.2/angular-aria.min.js"></script>
	<script src="<?=$path?>/lib/angular-1.5.2/i18n/angular-locale_zh-tw.js"></script>
	<!-- Augular loading bar-->
	<script src="<?=$path?>/lib/angular-loading-bar/loading-bar.min.js"></script>
	<!-- angular-bootstrap-file-field 
	<script src="<?=$path?>/lib/angular-bootstrap-file-field/angular-bootstrap-file-field.min.js"></script>-->
	<!-- Angular translate -->
	<script src="<?=$path?>/lib/angular-translate/dist/angular-translate.min.js"></script>
	<script src="<?=$path?>/lib/angular-translate-loader-url/angular-translate-loader-url.min.js"></script>
	<script src="<?=$path?>/lib/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
	<!-- Augular storage -->
	<script src="<?=$path?>/lib/angular-storage/dist/angular-storage.js"></script>
	<!-- Augular busy -->
	<script src="<?=$path?>/lib/angular-busy/angular-busy.js"></script>
	<!-- Augular material -->
	<script src="<?=$path?>/lib/material-1.1.0-rc3/angular-material.min.js"></script>
	<!-- Augular angularPrint -->
	<script src="<?=$path?>/lib/angularPrint/angularPrint.js"></script>
	<!-- Augular angular-charts -->
	<script src="<?=$path?>/lib/angular-chart/Chart.min.js"></script>
	<script src="<?=$path?>/lib/angular-chart/angular-chart.js"></script>
	<!-- ui bootstrap -->
	<script src="<?=$path?>/lib/ui-bootstrap-1.3.2/ui-bootstrap-tpls-1.3.2.min.js"></script>
	<script src="<?=$path?>/lib/underscore/underscore-min.js"></script>
	<!-- ng upload file -->
	<script src="<?=$path?>/lib/ng-file-upload-master/dist/ng-file-upload-shim.js"></script>
	<script src="<?=$path?>/lib/ng-file-upload-master/dist/ng-file-upload.js"></script>
	
	<!-- app設定 -->
	<script src="app/app.js"></script>
	<script src="app/app_config.js"></script>
	<script src="app/services/Services.js"></script>
	<script src="app/directives/directives.js"></script>
	<script src="app/filters/numberformat.js"></script>
	<script src="app/controllers/eways.js"></script>
	<script src="modals/productSelector/ctrl.js"></script>
	
  
	<?php
		echo $modals;
		$dir = scandir("components");
		$dir_arr=array();
		foreach($dir as $key=>$item){
			if($key>1){
				$itemdir=scandir("components/".$item);
				foreach($itemdir as $k=>$i){
					if($k>1){
						if((strripos($i,"html") || strripos($i,"php")) && $i!="api.php"){
							$name=explode(".",$i);
							$name=$name[0];
							$dir_arr[$item][$name]=$i;
						}
								
					}
				}
				
			}
		}
		
		$defaultPageArr = json_decode(file_get_contents("permission/1.json"),true);
		
		
		
		@include('../php/common_start.php' ); 
		$uid = $_SESSION[$conf_user]['uid'];
		@$functionsCht=getFieldValue("SELECT functionsCht FROM adminmanagers WHERE id ='$uid'","functionsCht");
		
		
		
		$closefunc = array();	
		$funclist = array();	
		if(!empty($functionsCht))
		{
			$fun_arr = explode( "|||||" ,$functionsCht);
			if(count($fun_arr) > 0)
			{
				foreach($fun_arr as $row)
				{
					if(!empty($row))
					{
						$arr = explode( "|||" ,$row);
						$funclist[$arr[0]] = array("C"=>$arr[1],"U"=>$arr[2],"D"=>$arr[3],"R"=>$arr[4]);
						if($arr[1] == 'false' && $arr[2] == 'false' && $arr[3] == 'false' && $arr[4] == 'false')
						{
							$closefunc[] = $arr[0];
						}
					}
				}
			}
			
			
			if(count($defaultPageArr) > 0)
			{
				$tmp =array();
				foreach($defaultPageArr as $func=>$row)
				{
					if($row['hide'] == '1')	
					{
						$tmp[$func] = $row;
					}
					elseif(count($row['child']) == 0)	
					{
						if(!in_array($func, $closefunc))
						{
							$tmp[$func] = $row;
						}
					}
					else	
					{
						$tmp2 = array();
						foreach($row['child'] as $func2=>$row2)
						{
							if(!in_array($func2, $closefunc))
							{
								$tmp2[$func2] = $row2;
							}
						}
						
						if(count($tmp2) > 0)
						{
							$row['child'] = $tmp2;
							$tmp[$func] = $row;
						}
					}
				}	
				$defaultPageArr = $tmp;
			}	
		}
		else	
		{
			$defaultPageArr = array();
		}
		
		$defaultPage="";
		$js_arr=array();
		
		$setLang=global_get_param( $_GET, 'setLang', '');
		if($setLang){
			$_lang=global_get_param( $_GET, 'lang', 'zh-tw');
			$_SESSION[$conf_user]['syslang']=$_lang;
			
		}else{
			$_lang=$_SESSION[$conf_user]['syslang']?$_SESSION[$conf_user]['syslang']:'zh-tw';
		}
	?>
	<script>
	var syslang='<?=$_lang?>';
	
	app.run(['$location', '$rootScope', 'sessionCtrl', '$translate', 'templateCtrl','pubcode', function($location, $rootScope, sessionCtrl, $translate, templateCtrl,pubcode) {
		var uid = sessionCtrl.getuid();
	  	sessionCtrl.set("_lang",syslang);
	  	$translate.use(syslang);
	  	pubcode.get();
	  	<?if(count($defaultPageArr)>0){?>
	  		$rootScope.menulist=<?=json_encode($defaultPageArr)?>;
	  	<?}?>
		
		<?if(count($funclist)>0){?>
	  		$rootScope.funclist=<?=json_encode($funclist)?>;
	  	<?}?>
		
	  	//instockMode
	  	$rootScope.conf_instock_mode='<?=$conf_instock_mode?>';
	    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
	        //$rootScope.title = current.$$route.title;
			if(!sessionCtrl.loginCheck()) {
				templateCtrl.gotoLogin();
			};
			sessionCtrl.set("absUrl", $location.absUrl());
			/*var navbar = $(".navbar-toggle");
			if(navbar.attr("class").indexOf("collapsed")==-1){
				navbar.click();
			}*/
	    });
	}]);
	
	app.config(['$routeProvider', function ($routeProvider) {
	
		$routeProvider
			
			<?php
				
				foreach($defaultPageArr as $menuname=>$row){
					if(!$row['hide']){
						if($row['index']){
							
							?>
							.when("/", {
								redirectTo:'/<?=$row['component']."_".$row['page']?>'
							})
							<?
						}
						if($row['page']=="list" || $row['page']=="page"){
							if(is_dir("components/{$row['component']}")){
								$js_arr[]=$row['component'];
								foreach($dir_arr[$row['component']] as $type=>$filename){
									
									?>
										.when("/<?=$row['component']."_".$type?>", {
											templateUrl: "components/<?=$row['component']?>/<?=$filename?>?v=<?=time()?>",
											controller:"<?=$row['component']."_".$type?>",
							        		controllerAs: 'ctrl'
										})
									<?
										
								}
							}
						}else if($row['page']=="tree"){
							foreach($row['child'] as $menuname=>$row){
								if(is_dir("components/{$row['component']}")){
									$js_arr[]=$row['component'];
									foreach($dir_arr[$row['component']] as $type=>$filename){
										?>
											.when("/<?=$row['component']."_".$type?>", {
												templateUrl: "components/<?=$row['component']?>/<?=$filename?>?v=<?=time()?>",
												controller:"<?=$row['component']."_".$type?>",
								        		controllerAs: 'ctrl'
											})
										<?
											
									}
								}
							}
							
						}else{
							$js_arr[]=$row['component'];
						}
					}else{
						$js_arr[]=$row['component'];
						if(is_dir("components/{$row['component']}")){
							
							foreach($dir_arr[$row['component']] as $type=>$filename){
								
								?>
									.when("/<?=$row['component']."_".$type?>", {
										templateUrl: "components/<?=$row['component']?>/<?=$filename?>?v=<?=time()?>",
										controller:"<?=$row['component']."_".$type?>",
						        		controllerAs: 'ctrl'
									})
								<?
									
							}
						}
					}
				}
			?>
			.otherwise({
				redirectTo:'/<?=$defaultPage?>'
			});
	}]);
	</script>
	<?
		$now = time();
		foreach($js_arr as $key=>$item){
			echo "<script src=\"components/{$item}/ctrl.js?v={$now}\"></script>";
		}
	?>
	
	
	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->

</html>