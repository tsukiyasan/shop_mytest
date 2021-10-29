<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );



$encryt1 = "";
$encryt2 = "";

if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER["HTTP_X_FORWARDED_PROTO"] ){	
	$HTTP_X_FORWARDED_PROTO=$_SERVER["HTTP_X_FORWARDED_PROTO"];	
}else if($_SERVER["REQUEST_SCHEME"]){	
	$HTTP_X_FORWARDED_PROTO=$_SERVER["REQUEST_SCHEME"];	
}else{	
	$HTTP_X_FORWARDED_PROTO="http";	
}


$real_page = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$real_domain = substr($real_page, 0, strrpos($real_page, '/')+1);
$real_domain = $HTTP_X_FORWARDED_PROTO.'://'.$real_domain;
$real_weburl = $real_domain;
$file_path = $HTTP_X_FORWARDED_PROTO.'://'.$_SERVER['HTTP_HOST']."/manager/upload/";
$real_url = $HTTP_X_FORWARDED_PROTO.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$real_path = $_SERVER['SCRIPT_FILENAME'];
$real_path = substr($real_path, 0, strrpos($real_path, '/')+1);
$template_path = 'templates/t3/';
$template_option = '';




$globalConf_dbtype = 'mysql';
$globalConf_host = '127.0.0.1';
$globalConf_user = 'root';
$globalConf_password = '';
$globalConf_db = 'shop_goodarch2u_us';


$globalConf_dbtype2 = 'mysql';
$globalConf_host2 = '192.168.7.44';
$globalConf_user2 = 'root';
$globalConf_password2 = 'goodarch20181011';
$globalConf_db2 = 'goodarch2';


$globalConf_dbtype3 = 'mysql';
$globalConf_host3 = '192.168.7.46';
$globalConf_user3 = 'root';
$globalConf_password3 = '';
$globalConf_db3 = 'money_bank';

// $globalConf_dbtype2 = 'mysql';
// $globalConf_host2 = '192.168.7.37';
// $globalConf_user2 = 'root';
// $globalConf_password2 = 'goodarch20181011';
// $globalConf_db2 = 'goodarch2';





$globalConf_token = array("Andy" ,"Justin" ,"Eways" ,"Ricky" ,"James");

$globalConf_default_template = 'default';
$globalConf_default_option = 'home';
$globalConf_errforward = $real_domain;
$globalConf_url_rewrite = true;


$globalConf_sitename = '後台管理系統';

$globalConf_charset = 'utf-8';

$globalConf_max_adminLevel = 6;

//$globalConf_sys_name = 'bibibobo Hsu';
$globalConf_sys_name = 'GoodArch';
//$globalConf_sys_email = 'bibibobo97@e-ways.com.tw';
$globalConf_sys_email = 'service@goodarch2u.com';
//$globalConf_service_email = 'bibibobo97@gmail.com';

// $globalConf_service_email = 'bibibobo97@gmail.com';

// $globalConf_service_phone = '0932820241';

// $globalConf_service_website = 'http://www.e-ways.com.tw';
$globalConf_service_email = 'service@goodarch2u.com';

$globalConf_service_phone = '0932820241';

$globalConf_service_website = 'https://shop.goodarch2u.com.tw/';

$globalConf_mailer = "smtp";
$Conf_smtpauth = true;
$Conf_smtpuser = 'service';
$Conf_smtppass = 's123123e';
$Conf_smtphost = '192.168.7.3';

// $Conf_smtpport = '2525';
$Conf_smtpport = '25';

// $globalConf_mailer = "smtp";
// $Conf_smtpauth = true;
// $Conf_smtpuser = '';
// $Conf_smtppass = '';
// $Conf_smtphost = '';

// $Conf_smtpport = '2525';

$globalConf_area = "TW";
$globalConf_includePath = 'includes/';

$globalConf_upload_dir = '../upload/';
$globalConf_upload_atk = "atk/";
$globalConf_upload_store = "../store/images/";
$globalConf_upload_product = "product/";
$globalConf_upload_banner = "banner/";
$globalConf_upload_treemenu = "treemenu/";
$globalConf_upload_parentPhotos = 'upload/parentPhotos';
$globalConf_upload_AD = 'upload/advertisement';

$globalConf_upload_doctor = 'upload/doctor';


$globalConf_imgupload_limit = '1048576';
$globalConf_fileupload_limit = '52428800';
$globalConf_encrypt_1 = 'bibibobo';
$globalConf_encrypt_2 = 'mjiotghjtoe';
$globalConf_default_password = 'casbu888';

$globalConf_more_text = '...';
$globalConf_word_limit = 70;
$globalConf_disable_day = array("Monday","Sunday");

$globalConf_emptyimg = '1pix.gif';																						

$globalConf_absolute_path = $real_path;
$globalConf_live_site = $real_weburl;
$globalConf_debug = false;
$globalConf_default_charset = 'utf-8';
$globalConf_default_lang = 'chs';


$globalConf_gzip = 0;

$globalConf_cache = false;

$globalConf_cache_dir = "cache/";
$globalConf_error_reporting = E_ALL & ~E_NOTICE;

$globalConf_list_limit = '20';


$globalConf_default_page_limit = '20';
$globalConf_default_contactPage_limit = '5';

$tmpActiveId0221 = 99;

$ESignupActiveEndDate = '2029-12-31';
$ESignupFreeProductId = '504';
$ESignupFreeProductQuantity = '1';
$ESignupFreeProductFormat1 = '155';
$ESignupFreeProductFormat2 = '153';

		
$globalConf_signup_ver2020 = true;		
$globalConf_signupDemo_ver2020 = false;		
$globalConf_sms_open=true;//是否開啟簡訊功能		
$Conf_sms_username="97252712";		
$Conf_sms_password="a123123";
?>