<?php
// ini_set('display_errors','1');
include('php/common_start.php');
include('config.php');
if (global_get_naps_bot() && false) {
	$real_url = str_replace('"', '', $real_url);
	$real_url = str_replace('\\', '', $real_url);
	$web_data = shell_exec("sudo node /home/output_web/chrome.js \"$real_url\"");
	$web_data = str_replace('ng-repeat=', '', $web_data);
	$web_data = str_replace('ng-if=', '', $web_data);
	$web_data = str_replace('ng-bind=', '', $web_data);
	$web_data = str_replace('ng-repeat-start=', '', $web_data);
	$web_data = str_replace('ng-repeat-end', '', $web_data);
	echo $web_data;
	exit;
}

if (!$_SESSION['view_session']) {
	$today = date("Y-m-d");
	$webviewcnt = getFieldValue("select id from webviewcnt where viewDate='$today'", "id");
	if (!$webviewcnt) {
		$sql = "insert into webviewcnt (viewDate,cnt) values ('$today',1)";
		$db->setQuery($sql);
		$db->query();
	} else {
		$sql = "update webviewcnt set cnt=cnt+1 where viewDate='$today'";
		$db->setQuery($sql);
		$db->query();
	}
	$_SESSION['view_session'] = 1;
}

$_rec = global_get_param($_GET, 'mno', null, 0, 1);

if (!empty($_rec)) {
	$_SESSION['temp_reccommend_code'] = $_rec;
}



$sql = "SELECT * FROM siteinfo";
$db->setQuery($sql);
$siteinfo_info = $db->loadRow();
$siteinfo_info['img1'] = "upload/logo/logo_admin_1.png";
if (!is_file($siteinfo_info['img1'])) {
	$siteinfo_info['img1'] = "templates/default/images/logo.png";
}

$sql = "SELECT * FROM imglist WHERE code='" . global_get_param($_GET, 'code', null, 0, 1) . "'";
$db->setQuery($sql);
$ogimg_info = $db->loadRow();

if ($ogimg_info['name']) {
	$ogimg = str_replace($conf_upload, $conf_real_upload, $ogimg_info['name']);
} else {
	$ogimg = $siteinfo_info['img1'];
}
if (is_file($ogimg)) {
	$imgsizeArr = getimagesize($ogimg);
	$ogimg = "http://" . $_SERVER['HTTP_HOST'] . "/" . $ogimg;
}

global $conf_user;
$moneyList = getLanguageList("money");
$_currency = $moneyList[0]['code'];
$_SESSION[$conf_user]['sysCurrency'] = $_currency;


?>
<!DOCTYPE html>
<html ng-app="goodarch2uApp">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="robots" content="all" />
	<base href="/" />
	<meta name="keywords" content="<?= $siteinfo_info['webkeys'] ?>" />
	<meta name="description" content="<?= $siteinfo_info['webintro'] ?>" />
	<meta name="og:description" content="<?= $siteinfo_info['webintro'] ?>" />
	<meta property="og:title" content="<?= $siteinfo_info['name' . (($_lang) ? '_' . $_lang : '')] ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= $real_url ?>" />
	<? if ($ogimg) { ?>
		<meta property="og:image" content="<?= $ogimg ?>" />
		<? if ($imgsizeArr) { ?>
			<meta property="og:image:width" content="<?= $imgsizeArr[0] ?>" />
			<meta property="og:image:height" content="<?= $imgsizeArr[1] ?>" />
		<? } ?>
	<? } ?>
	<meta property="og:site_name" content="<?= $siteinfo_info['name' . (($_lang) ? '_' . $_lang : '')] ?>" />
	<title><?= $siteinfo_info['name' . (($_lang) ? '_' . $_lang : '')] ?></title>
	<!-- Favicon -->
	<link type="image/x-icon" href="templates/default/images/favicon.png?v=2" rel="shortcut icon" />
	<link rel="apple-touch-icon" href="templates/default/images/favicon_logo.png?v=3" rel="shortcut icon" />
	<link rel="icon" sizes="192x192" href="templates/default/images/favicon_logo.png?v=3">
	<link rel="icon" sizes="128x128" href="templates/default/images/favicon_logo.png?v=3">
	<link rel="apple-touch-icon-precomposed" sizes="128x128" href="templates/default/images/favicon_logo.png?v=3">
	<!-- alertify js-->
	<link rel="stylesheet" type="text/css" href="lib/alertifyjs/css/alertify.min.css" />
	<link rel="stylesheet" type="text/css" href="lib/alertifyjs/css/themes/default.min.css" />
	<!-- css -->

	<link rel="stylesheet" type="text/css" href="lib/lightbox/lightbox.css" />
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="lib/bootstrap/bootstrap.css" />
	<!-- Augular busy-->
	<link rel="stylesheet" type="text/css" href="lib/angular-busy/angular-busy.css" />
	<!-- Angular bootstrap -->
	<link rel="stylesheet" type="text/css" href="lib/agbootstrap/datepicker.css" />
	<!-- fontawesome -->
	<link rel="stylesheet" type="text/css" href="lib/font-awesome-4.5.0/css/font-awesome.min.css">
	<!-- custom_css -->
	<link rel="stylesheet" type="text/css" href="lib/css/my.css?v=<?= time() ?>">
	<!-- basic-rwd-table -->
	<link rel="stylesheet" type="text/css" href="lib/basic-rwd-table/basictable.css">
	<!-- google font libraries -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
</head>

<body id="template_defult">
	<div id="mbody" ng-controller="app_ctrl as appctrl" ng-include="appctrl.page" class="fixed-footer pace-done fixed-header"></div>
</body>

<!-- global scripts -->
<!-- JQuery -->

<script src="lib/jquery-2.2.2/jquery-2.2.2.min.js"></script>
<script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57456adebf26c371&async=1"></script>
<script src="lib/lightbox/lightbox.min.js"></script>
<!-- bootstrap -->
<script src="lib/bootstrap-3.3.6/js/bootstrap.js"></script>
<!-- alertify js-->
<script src="lib/alertifyjs/alertify.min.js"></script>
<!-- angularJS -->
<script src="lib/angular-1.5.2/angular.min.js"></script>
<script src="lib/angular-1.5.2/angular-route.min.js"></script>
<script src="lib/angular-1.5.2/angular-animate.js"></script>
<script src="lib/angular-1.5.2/angular-cookies.js"></script>
<script src="lib/angular-1.5.2/angular-aria.min.js"></script>
<script src="lib/angular-1.5.2/angular-touch.min.js"></script>
<script src="lib/angular-1.5.2/i18n/angular-locale_zh-tw.js"></script>
<!-- Angular translate -->
<script src="lib/angular-translate/dist/angular-translate.min.js"></script>
<script src="lib/angular-translate-loader-url/angular-translate-loader-url.min.js"></script>
<script src="lib/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js"></script>
<!-- Augular storage -->
<script src="lib/angular-storage/dist/angular-storage.js"></script>
<!-- Augular busy -->
<script src="lib/angular-busy/angular-busy.js"></script>
<!-- Augular bootstrap
	<script src="lib/agbootstrap/angular-locale_zh-tw.js"></script>
	<script src="lib/agbootstrap/datepicker.js"></script> -->
<!-- ui bootstrap -->
<script src="lib/ui-bootstrap-1.3.2/ui-bootstrap-tpls-1.3.2.min.js"></script>
<!-- googleplus -->
<script src="lib/googleplus/angular-google-plus.min.js"></script>

<script src="lib/clipboard.js-master/dist/clipboard.js"></script>

<script src="lib/jquery-textfill-master/dist/jquery.textfill.min.js"></script>
<!-- app設定 -->
<script src="app/app.js"></script>
<script src="app/app_config.js"></script>
<script src="app/services/Services.js?v=<?= time() ?>"></script>
<!--<script src="app/directives/directives.js?v=<?= time() ?>"></script>-->
<script src="app/directives/directives1.js?v=<?= time() ?>"></script>
<script src="app/directives/directives2.js?v=<?= time() ?>"></script>
<script src="app/filters/numberformat.js"></script>
<script src="app/controllers/eways.js"></script>
<!-- basic-rwd-table -->
<script src="lib/basic-rwd-table/jquery.basictable.js"></script>
<script src="lib/underscore/underscore-min.js"></script>
<script src="lib/dom2image/dom2image.js"></script>
<script src="https://www.tracking.my/track-button.js"></script>
<?php

$dir = scandir("components");
$dir_arr = array();
foreach ($dir as $key => $item) {
	if ($key > 1) {
		$itemdir = scandir("components/" . $item);
		foreach ($itemdir as $k => $i) {
			if ($k > 1) {
				if ((strripos($i, "html") || strripos($i, "php")) && $i != "api.php") {
					$name = explode(".", $i);
					$name = $name[0];
					$dir_arr[$item][$name] = $i;
				}
			}
		}
	}
}

$defaultPageArr = json_decode(file_get_contents("permission/1.json"), true);
$defaultPage = "";
$js_arr = array();
?>
<script type="text/javascript">
	var clipboard = new ClipboardJS('.copy');
	clipboard.on('success', function(e) {
		e.clearSelection();
		console.log('ok');
		var syslang = '<?= $_lang ?>';
		if(syslang == 'en'){
			msg = 'Copy success!';
		}else if (syslang == 'zh-cn'){
			msg = '复制成功！';
		}else{
			msg = '複製成功！';
		}
		console.log(syslang);
		alert(msg);
	});
</script>

<script>
	var syslang = '<?= $_lang ?>';
	var sysCurrency = '<?= $_currency ?>';

	app.config(['$routeProvider', function($routeProvider) {
		$routeProvider

		<?php

		foreach ($defaultPageArr as $menuname => $row) {
			if (!$row['hide']) {
				if ($row['index']) {

		?>
						.when("/", {
							redirectTo: '/<?= $menuname . "_" . $row['page'] ?>'
						})
					<?
				}
				if ($row['page'] != "tree") {
					if (is_dir("components/{$menuname}")) {
						$js_arr[] = $menuname;
						foreach ($dir_arr[$menuname] as $type => $filename) {

					?>
								.when("/<?= $menuname . "_" . $type ?>", {
									templateUrl: "components/<?= $menuname ?>/<?= $filename ?>?v=<?= time() ?>",
									controller: "<?= $menuname . "_" . $type ?>",
									controllerAs: 'ctrl'
								})
							<?
							if ($row['route']) {
								foreach ($row['route'] as $num => $routeArr) {
									$str = "";
									foreach ($routeArr as $routeValue) {
										$str .= "/:$routeValue";
									}
							?>
										.when("/<?= $menuname . "_" . $type . $str ?>", {
											templateUrl: "components/<?= $menuname ?>/<?= $filename ?>?v=<?= time() ?>",
											controller: "<?= $menuname . "_" . $type ?>",
											controllerAs: 'ctrl'
										})
								<?
								}
							}
						}
					}
				} else {
					foreach ($row['child'] as $menuname => $row) {
						if (is_dir("components/{$menuname}")) {
							$js_arr[] = $menuname;
							foreach ($dir_arr[$menuname] as $type => $filename) {
								?>
									.when("/<?= $menuname . "_" . $type ?>", {
										templateUrl: "components/<?= $menuname ?>/<?= $filename ?>?v=<?= time() ?>",
										controller: "<?= $menuname . "_" . $type ?>",
										controllerAs: 'ctrl'
									})
								<?
								if ($row['route']) {
									foreach ($row['route'] as $num => $routeArr) {
										$str = "";
										foreach ($routeArr as $routeValue) {
											$str .= "/:$routeValue";
										}
								?>
											.when("/<?= $menuname . "_" . $type . $str ?>", {
												templateUrl: "components/<?= $menuname ?>/<?= $filename ?>?v=<?= time() ?>",
												controller: "<?= $menuname . "_" . $type ?>",
												controllerAs: 'ctrl'
											})
		<?
									}
								}
							}
						}
					}
				}
			} else {
				if (is_dir("components/{$menuname}")) {
					$js_arr[] = $menuname;
				}
			}
		}
		?>
			.otherwise({
				redirectTo: '/<?= $defaultPage ?>'
			});
	}]);
</script>
<?
$now = time();
foreach ($js_arr as $key => $item) {
	echo "<script src=\"components/{$item}/ctrl.js?v={$now}\"></script>";
}
?>


</html>
<?php
include('php/common_end.php');
?>