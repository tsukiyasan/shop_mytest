<?php

// ini_set('display_errors','1');

define( '_VALID_WAY', 1 );

session_start();
@include_once("/home/waf.php");

if (function_exists ( 'date_default_timezone_set' ))
{
    date_default_timezone_set('Asia/Taipei'); 
} 
else 
{
    putenv("TZ=Asia/Taipei"); 
} 

$today = date('Y-m-d');



include( 'global.conf.common.php' ); 
include_once( 'database.php' );
include( "{$globalConf_includePath}common.include.php" ); 


$func = strtolower( global_get_param( $_REQUEST, 'func', null ,0,1  ) ) ;




$db = null;
$db = new database( $globalConf_host, $globalConf_user, $globalConf_password, $globalConf_db, $Conf_dbprefix);
$db->debug( $Conf_debug );
$db2 = null;
$db2 = new database( $globalConf_host2, $globalConf_user2, $globalConf_password2, $globalConf_db2, $Conf_dbprefix);
$db2->debug( $Conf_debug );
$db3 = null;
$db3 = new database( $globalConf_host3, $globalConf_user3, $globalConf_password3, $globalConf_db3, $Conf_dbprefix);
$db3->debug( $Conf_debug );
$db5 = null;
$db5 = new database( $globalConf_host5, $globalConf_user5, $globalConf_password5, $globalConf_db5, $Conf_dbprefix);
$db5->debug( $Conf_debug );
$db6 = null;
$db6 = new database( $globalConf_host6, $globalConf_user6, $globalConf_password6, $globalConf_db6, $Conf_dbprefix);
$db6->debug( $Conf_debug );
$root_admin = "WEQGWH";
$root_store = "HJKTDS";


global_init_gzip();

if ( function_exists($func))
	eval($func.'();');

?>