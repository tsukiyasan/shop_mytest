<?php



$conf_user="manager";

$conf_php="../../../php/";
$conf_dir_path="../../";
$conf_upload="../upload/";
$conf_real_upload="../upload/";
$conf_banner=$conf_upload."banner/";
$conf_uploadfile=$conf_upload."uploadfile/";
$conf_poster=$conf_upload."poster/";
$conf_members=$conf_upload."members/";
$conf_product=$conf_upload."product/";
$conf_active=$conf_upload."activebundle/";
$conf_treemenu=$conf_upload."treemenu/";
$conf_index=$conf_upload."index/";
$conf_goodrecommend=$conf_upload."goodrecommend/";
$conf_mainmenud=$conf_upload."mainmenus/";


$conf_instock_mode='none';

$conf_fb_id="";
$conf_fb_key="";
$conf_fb_version="v2.6";


try{
    @include( $conf_php.'common_start.php' ); 
} catch(Exception $e){
    
}
?>