<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

//模式(測試為"test",其他為"")
define("MODE", "test");
//購物網站IP
define("SHOPIP", "192.168.7.39:82");
//購物網站網址
define("SHOPURL", "https://myshoptest.goodarch2u.com/");
//傳銷IP
define("MLMIP", "192.168.7.30");
//傳銷網址
define("MLMURL", "http://" . MLMIP . "/");
//點數銀行網址
define("POINTBANKURL", "http://192.168.7.46/money_bank_my_test/");
//點數銀行UPLOAD
define("POINTBANKUPLOAD", "http://192.168.7.46/upload_my/");
//OROCLE
define("OROCLE", "oci:dbname=192.168.7.11:1521/toptest;charset=UTF8");
//MAILIP
define("MAILIP", "192.168.7.3");
//海旅年月
define("ECASHDATE", "2022-01");
//保暖壺CODE
define("LIMITPROCODE", json_encode([
	1 => '4713696395492',
	2 => '4713696395508',
	3 => '4713696395515',
	4 => 'FD00372',
	5 => 'FD00373',
	6 => 'FD00374',
]));

//保暖壺PID
define("LIMITPROPID","563");

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
$globalConf_host = '192.168.7.39';
$globalConf_user = 'hwadmin';
$globalConf_password = 'x!7wu$VSwx';
$globalConf_db = 'shop_goodarch2u_mytest';


$globalConf_dbtype2 = 'mysql';
$globalConf_host2 = '192.168.7.30';
$globalConf_user2 = 'root';
$globalConf_password2 = 'nVE-=x=UrYMhssZV';
$globalConf_db2 = 'goodarch';


$globalConf_dbtype3 = 'mysql';
$globalConf_host3 = '192.168.7.46';
$globalConf_user3 = 'hwadmin';
$globalConf_password3 = 'f@CPNM29vvHz@vXb';
$globalConf_db3 = 'money_bank_my_test';

$globalConf_dbtype5 = 'mysql';
$globalConf_host5 = '192.168.7.41';
$globalConf_user5 = 'hwroot';
$globalConf_password5 = 'p5ph@qTEdtSQnxhz';
$globalConf_db5 = 'genomics';

$globalConf_dbtype6 = 'mysql';
$globalConf_host6 = '192.168.7.54';
$globalConf_user6 = 'hwadmin';
$globalConf_password6 = 'm57qHWFgAh';
$globalConf_db6 = 'ai_foot';

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
$Conf_smtppass = 's80737468e';
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
$ESignupFreeProductId = '356'; //七彩珠 28
$ESignupFreeProductQuantity = '1';
$ESignupFreeProductFormat1 = '155';
$ESignupFreeProductFormat2 = '153';

$ESignupFreeProductId_2 = '527'; //咖啡 5包
$ESignupFreeProductQuantity_2 = '5';
$ESignupFreeProductFormat1_2 = '155';
$ESignupFreeProductFormat2_2 = '153';

$ESignupFreeProductId_3 = '525'; //學習護照
$ESignupFreeProductQuantity_3 = '1';
$ESignupFreeProductFormat1_3 = '155';
$ESignupFreeProductFormat2_3 = '153';

		
$globalConf_signup_ver2020 = true;		
$globalConf_signupDemo_ver2020 = false;		
$globalConf_sms_open=true;//是否開啟簡訊功能		
$Conf_sms_username="API8MYP4FC57K";		
$Conf_sms_password="API8MYP4FC57K8MYP4";




?>