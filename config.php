<?php

try{
    @include( 'manager/config.php' ); 
} catch(Exception $e){
    
}


$conf_web="mcc";
$conf_user="client";

$conf_php="../../php/";
$conf_dir_path="../";
$conf_upload="../upload/";
$conf_real_upload="upload/";
$conf_banner=$conf_upload."banner/";
$conf_product=$conf_upload."product/";
$conf_treemenu=$conf_upload."treemenu/";
$conf_index=$conf_upload."index/";
$conf_mainmenu=$conf_upload."mainmenus/";


$conf_fb_id="";
$conf_fb_key="";
$conf_fb_version="v2.6";

$conf_aes_key=$conf_web."";
$conf_aes_iv="";

$conf_url_name = "";

try{
    @include( $conf_php.'common_start.php' ); 
} catch(Exception $e){
    
}

$setLang=global_get_param( $_GET, 'setLang', '');
$langList = getLanguageList("text");
$defaultLang = $langList[1]['code'];
if($setLang){
	$_lang=global_get_param( $_GET, 'lang', $defaultLang);
	$_SESSION[$conf_user]['syslang']=$_lang;
	$_SESSION['syslang']=$_lang;
}else{
	$_lang=$_SESSION[$conf_user]['syslang']?$_SESSION[$conf_user]['syslang']:$defaultLang;
	$_SESSION[$conf_user]['syslang']=$_lang;
}
	
?>