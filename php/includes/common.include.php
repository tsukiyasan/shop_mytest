<?php



require( "common.func1.php" ); 
require( "common.func2.php" ); 
require( "common.func3.php" ); 
require( "common.func4.php" ); 


require( "adodb5/adodb.inc.php" );
require( "phpmailer/class.phpmailer.php" );

$setLang=global_get_param( $_GET, 'setLang', '');
if($setLang){
	$_lang=global_get_param( $_GET, 'lang', 'zh-tw');
}else{
	$_lang=$_SESSION[$conf_user]['syslang']?$_SESSION['syslang']:'zh-tw';
}
switch(strtolower($_lang))
{
	case "zh-tw":
		$lg = "cht";
		break;
	case "zh-cn":
		$lg = "chs";
		break;
	case "en":
		$lg = "en";
		break;
	case "in":
		$lg = "in";
		break;
	default:
		$lg = "cht";
}

$lg = (!empty($lg)) ? $lg : 'cht';
if($lg != 'cht' && $lg != 'chs' && $lg != 'en' && $lg != 'in')
	$lg = 'cht';

require( "lang/$lg.php" );
?>