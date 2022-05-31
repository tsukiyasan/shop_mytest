<?php



defined( '_VALID_WAY' ) or die( 'Do not Access the Location Directly!' );

if (phpversion() < '4.2.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php41x.php" );
}
if (phpversion() < '4.3.0') 
{
	require( "$globalConf_absolute_path/includes/compat.php42x.php" );
}
if (in_array( '_post', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  Post variable hack attempted.' );
}
if (in_array( '_get', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  GET variable hack attempted.' );
}
if (in_array( '_request', array_keys( array_change_key_case( $_REQUEST, CASE_LOWER ) ) ) ) 
{
	die( 'Fatal error.  REQUEST variable hack attempted.' );
}


@set_magic_quotes_runtime( 0 );

if (@$globalConf_error_reporting === 0) {
	error_reporting( 0 );
} else if (@$globalConf_error_reporting > 0) {
	error_reporting( $globalConf_error_reporting );
}



function global_init_database($dbtype, $user, $password, $host, $db_name, $sqltype=null)
{
	global $globalConf_sys_email,$globalConf_service_email,$globalConf_debug;
	
	if($dbtype == 'sqlsrv')
	{
		$connectionOptions = array( 
			"UID"=>$user,
			"PWD"=>$password,
			"Database"=>$db_name,
			"CharacterSet"=>'utf-8',
		);
		
		$db = sqlsrv_connect( $host, $connectionOptions);
	}
	else
	{
		$db = NewADOConnection("$dbtype");
		
		$db->Connect($host, $user, $password, $db_name);
		
		for ($i=0; $i<8; $i++)
		{
			
			if ($db->Connect($host, $user, $password, $db_name)===false)
			{
				sleep (5);
				if ($i==7)
				{
					
					$errmsg = "網站無回應，請稍候再試，<br>\n";
					echo $errmsg;
					exit;
				}
			}	
			else
				break;
		}
		
		
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

		
		
		
	}
	
	return $db;
}







function detailed_err($errno, $errstr, $errfile, $errline, $errcontext) 
{
	
	echo "<p>重大錯誤 - {$errstr}</p>\n";
	
	
	
	echo "<pre>\n函式呼叫列表:\n";
	debug_print_backtrace();
	
	
	
	echo "\n完整回溯追蹤紀錄:\n";
	var_dump(debug_backtrace());
	
	
	echo "</pre>\n";
	
	
	die();
}







function db_err_handler($errno, $errstr, $errfile, $errline, $errcontext) 
{
	global $globalConf_debug,$globalConf_dbtype,$globalConf_host,$globalConf_user,$globalConf_password,$globalConf_db;
	
	
	
	$db = global_init_database($globalConf_dbtype, $globalConf_user, $globalConf_password, $globalConf_host, $globalConf_db);

	
	
	$ized = serialize($errcontext);
	
	$errstrMod = $db->qstr(addslashes($errstr));
	$errfileMod = $db->qstr(addslashes($errfile));
	$izedMod = $db->qstr(addslashes($ized));

	
	$sql = "insert into error_log set ".
	       "errno = $errno,".
		   "errstr = '$errstrMod',".
		   "errfile = '$errfileMod',".
		   "errline = $errline,".
		   "errcontext = '$izedMod',".
		   "ctime = '".$_SERVER['REQUEST_TIME']."';";

	$db->Execute($sql);
	
	
	if (($errno == E_ERROR) || ($errno == E_USER_ERROR)) 
	{
		if ($globalConf_debug)
			die(detailed_err($errno, $errstr, $errfile, $errline, $errcontext));
		else
			die(_FATAL_ERROR_MSG);
	}
}





function global_is_robot() 
{ 
	if(!defined('IS_ROBOT')) 
	{ 
		
		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']); 
		
		$kw_spiders = "/(bot|crawl|spider|slurp|yahoo|sohu-search|lycos|robozilla|adsense|feed|google)/i"; 
		
		$kw_browsers = '/(MSIE|Netscape|Opera|Konqueror|Mozilla)/i'; 
		
		if(preg_match($kw_spiders, $user_agent)) 
		{ 
			define('IS_ROBOT', TRUE); 
		} 
		elseif(preg_match($kw_browsers, $user_agent)) 
		{ 
			define('IS_ROBOT', FALSE); 
		} 
		else 
		{ 
			define('IS_ROBOT', TRUE); 
		} 
	} 
	return IS_ROBOT; 
} 





function global_get_naps_bot()
{
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	
	if (strpos($useragent, 'googlebot') !== false)
	{
		return 'Googlebot';
	}
	
	if (strpos($useragent, 'msnbot') !== false)
	{
		return 'MSNbot';
	}
	
	if (strpos($useragent, 'slurp') !== false)
	{
		return 'Yahoobot';
	}
	
	if (strpos($useragent, 'baiduspider') !== false)
	{
		return 'Baiduspider';
	}
	
	if (strpos($useragent, 'sohu-search') !== false)
	{
		return 'Sohubot';
	}
	
	if (strpos($useragent, 'lycos') !== false)
	{
		return 'Lycos';
	}
	
	if (strpos($useragent, 'robozilla') !== false)
	{
		return 'Robozilla';
	} 
	return false;
}




function global_init_gzip() 
{
	global $globalConf_gzip;
	
	$do_gzip_compress = FALSE;
	if ($globalConf_gzip == 1 && !global_is_robot() && $_SERVER['HTTPS']!='on') 
	{
		$phpver = phpversion();
		$useragent = global_get_param( $_SERVER, 'HTTP_USER_AGENT', '' );
		$canZip = global_get_param( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

		if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) ) 
		{
			if ( extension_loaded('zlib') ) 
			{
				ob_start( 'ob_gzhandler' );
				return;
			}
		} 
		else if ( $phpver > '4.0' ) 
		{
			if ( $canZip == 'gzip' ) 
			{
				if (extension_loaded( 'zlib' )) 
				{
					$do_gzip_compress = TRUE;
					ob_start();
					ob_implicit_flush(0);

					header( 'Content-Encoding: gzip' );
					return;
				}
			}
		}
	}
	ob_start();
}


function global_do_gzip() 
{
	global $globalConf_gzip;
	if ( $globalConf_gzip  && !global_is_robot() && $_SERVER['HTTPS']!='on') 
	{
		

		$gzip_contents = ob_get_contents();
		ob_end_clean();

		$gzip_size = strlen($gzip_contents);
		$gzip_crc = crc32($gzip_contents);

		$gzip_contents = gzcompress($gzip_contents, 9);
		$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);
	} 
	else 
	{
		ob_end_flush();
	}
}
          


function global_set_path( $option, $component, $basePath='.') {
	global $db;
	
	$option = strtolower( $option );
	$path = array();

	$prefix = substr( $option, 0, 4 );
	if ($prefix != 'com_') 
	{
		
		$name = $option;
		$component = "com_$component";
	} 
	else 
	{
		$name = substr( $option, 4 );
	}

	
	if (file_exists( "$basePath/components/$component/$name.php" )) 
	{
		$path['process'] = "$basePath/components/$component/$name.php";
	}

	
	if (file_exists( "$basePath/components/$component/$name.class.php" )) 
	{
		$path['class'] = "$basePath/components/$component/$name.class.php";
	} 
	else if (file_exists( "$basePath/includes/$name.php" )) 
	{
		$path['class'] = "$basePath/includes/$name.php";
	}
	
	return $path;
}



function global_get_component($functional, $option)
{
	global $db;
	
	$sql = "select componentid from sysoption_m where functional='$functional' and name='$option'";
	$rs = $db->Execute($sql);
	if($rs)
	{
		$sql_tmp = "select name from syscomponent_m where id=".$rs->fields['componentid'];
		$rs_tmp = $db->Execute($sql_tmp);
		if($rs_tmp)
		{
			$component = $rs_tmp->fields['name'];
			$rs_tmp->Close();
		}
		$rs->Close();
	}
	if (empty($component))
	{
		$sql = "select componentid from sysoption where functional='$functional' and name='$option'";
		$rs = $db->Execute($sql);
		if($rs)
		{
			$sql_tmp = "select name from syscomponent where id=".$rs->fields['componentid'];
			$rs_tmp = $db->Execute($sql_tmp);
			if($rs_tmp)
			{
				$component = $rs_tmp->fields['name'];
				$rs_tmp->Close();
			}
			$rs->Close();
		}
		
		$sql = " TRUNCATE TABLE `sysoption_m`";
		$db->Execute($sql);
		$sql = " TRUNCATE TABLE `syscomponent_m`";
		$db->Execute($sql);
		
		$sql = "INSERT INTO `sysoption_m` SELECT * FROM `sysoption`";
		$db->Execute($sql);
		$sql = "INSERT INTO `syscomponent_m` SELECT * FROM `syscomponent`";
		$db->Execute($sql);
	}
	return $component;
}



function utf8_urldecode($str) {
	$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str)); 
	return html_entity_decode($str,null,'UTF-8');
}


function array_decode($arr){
	
	if(is_array($arr) || is_object($arr)){
		$tmp=array();
		foreach($arr as $key=>$row){
			$tmp[$key]= array_decode($row);
		}
		return $tmp;
	}else{
		return urldecode($arr);
	}
}

function global_get_param( &$arr, $name, $def='', $mask=0 ,$filter=0 , $required=0 , $format='' , $nameStr = '') 
{	
	if (isset( $arr[$name] )) 
	{
		
		$arr[$name]=array_decode($arr[$name]);
		if(is_string($arr[$name])){
			
			$arr[$name] = str_replace( 'undefined' , '', $arr[$name] );
			
			
			
			if (!$mask && !is_array($arr) && !is_object($arr)) 
			{
				$arr[$name] = trim( $arr[$name] );
			}
			
			if ($filter) 
			{
				$arr[$name] = strip_tags( $arr[$name] );
			}
			if ($filter) {
				$arr[$name] = str_replace( "%", "", $arr[$name] );
				$arr[$name] = str_replace( "'", "", $arr[$name] );
				$arr[$name] = str_replace( "\"", "", $arr[$name] );
				$arr[$name] = str_replace( "=", "", $arr[$name] );
				$arr[$name] = str_replace( "--", "", $arr[$name] );
				$arr[$name] = str_replace( "|", "", $arr[$name] );
				$arr[$name] = str_replace( "\\", "", $arr[$name] );
				$arr[$name] = str_replace( "&", "", $arr[$name] );
			}
			
			if (!get_magic_quotes_gpc()) 
			{
				$arr[$name] = addslashes( $arr[$name] );
			}
			
			
			if(empty($arr[$name]) && $required)
			{
				$arrJson = array();
				$arrJson['status'] = "0";
				$arrJson['msg'] = urlencode($nameStr._COMMON_PARAM_VALIDATE_NOT_REQUIRED);
				JsonEnd($arrJson);
				exit;
			}
			
			
			$format = strtolower($format);
			if(	!empty($arr[$name]) &&
				(
					($format == 'int' &&  !filter_var($arr[$name], FILTER_VALIDATE_INT)) || 
					($format == 'float' && !filter_var($arr[$name], FILTER_VALIDATE_FLOAT)) || 
					($format == 'url' && !filter_var($arr[$name], FILTER_SANITIZE_URL)) || 
					($format == 'email' && !filter_var($arr[$name], FILTER_VALIDATE_EMAIL)) || 
					($format == 'ip' && !filter_var($arr[$name], FILTER_VALIDATE_IP)) ||
					($format == 'order' && strtolower($arr[$name]) != 'desc' && strtolower($arr[$name]) != 'asc')
				)
			)
			{
				
				$arrJson = array();
				$arrJson['status'] = "0";
				$arrJson['msg'] = urlencode($nameStr._COMMON_PARAM_VALIDATE_FORMAT_ERR);
				JsonEnd($arrJson);
				exit;
			}
		}
		return $arr[$name];
	} 
	else 
	{
		if($required)
		{
			$arrJson = array();
			$arrJson['status'] = "0";
			$arrJson['msg'] = urlencode($nameStr._COMMON_PARAM_VALIDATE_NOT_REQUIRED);
			JsonEnd($arrJson);
			exit;
		}
		
		
		
		
		return $def;
	}
}





function global_set_cache($flagCache=false, $option='', $cache_name='', $printers='')
{
	global $tpl,$globalConf_cache,$globalConf_cache_dir,$lang;
	
	$cache_dir = "{$globalConf_cache_dir}{$lang}/";
	if ($globalConf_cache==true)
	{
		if ($flagCache==true)
		{
			try 
			{
				$cache=new Cache();
				$cache->addDriver('file', new FileCacheDriver($cache_dir));
				$cache->set($option, $cache_name, $printers); 
			
			}
			catch (CacheException $e)
			{
				echo 'Error: '.$e->getMessage();
			}
		}
	}
	
}





function global_get_cache($option='', $compath='', $tablename='', $id='', $mtime='', $appendsql = '')
{
	global $db, $flagCache, $cache_name,$globalConf_cache,$globalConf_cache_dir,$lang;
	if ($globalConf_cache==true)
	{
		
		$cache_dir = "{$globalConf_cache_dir}{$lang}/";
		$cache_name = md5($compath);
		try 
		{
			
			if (file_exists("{$cache_dir}{$option}/{$cache_name}.cache"))
			{
				$mcount = 0;
				$cache_time = filemtime("{$cache_dir}{$option}/{$cache_name}.cache");
				if ($mtime!='')
				{
					if ($mtime>$cache_time)
						$mcount = 1;
				}
				if ($mcount==0 && $tablename!='')
				{
					if (!empty($id))
						$appendsql .= " and id=$id";

					$sqlcnt = "select count(*) from $tablename where mtime>'$cache_time' $appendsql";
					$mcount = global_get_record_count($db, $sqlcnt);
				}
				if ($mcount==0)
				{
					$cache=new Cache();
					$cache->addDriver('file', new FileCacheDriver($cache_dir));
					$printers=$cache->get($option, $cache_name, 2592000); 
					
					if($printers===false) 
					{ 	
						$flagCache = true;
					}
					else
					{
						
						echo $printers;
						
						$db->Close(); 
						
						global_output_header();
						
						global_do_gzip();
						exit;
					}
				}
				else
					$flagCache = true;
			}
			else
				$flagCache = true;
		}
		catch (CacheException $e)
		{
			echo 'Error: '.$e->getMessage();
		}
	}
	
}





function global_output_header($type='html', $filename='')
{
	switch($type)
	{
		case 'html':
			header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
			header( 'Cache-Control: no-store, no-cache, must-revalidate' );
			header( 'Cache-Control: post-check=0, pre-check=0', false );
			header( 'Pragma: no-cache' );
			break;
		case 'xml':
			header ("Content-Type:text/xml");
			break;
		case 'rss':
			header ("Content-Type:application/rss+xml");
			break;
		case 'javascript':
			header("Content-type: application/x-javascript");
			break;
		case 'json':
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Content-type: application/json');
			
			break;
		case 'excel':
			header("Content-type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$filename" );
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
			break;
		case 'pdf':
			header("Content-type:application/pdf");
			header("Content-Disposition:attachment;filename=$filename");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
			header("Pragma: public");
			break;
	}
	return;
}





function global_make_date_text($name, $value, $txtclass='textbg', $btnclass='btn', $pickerclass='datepicker')
{
	global $arrLangCommon;
	
	if (!empty($txtclass))
		$txtclass = " class=\"{$txtclass} {$pickerclass}\" ";
	else
		$txtclass = " class=\"{$pickerclass}\" ";
	
	if (!empty($btnclass))
		$btnclass = " class=\"$btnclass\" ";
	
	$html = "<input $txtclass name=\"$name\" id=\"$name\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"$value\"  readonly />\n";
	$html .= "&nbsp;<input $btnclass type=\"button\" name=\"Clear\" value=\"".$arrLangCommon['_CLEAR']."\" onclick=\"this.form.$name.value=''\">";
	return $html;
}





function global_make_time_text($name, $value, $txtclass='textbg', $btnclass='btn', $pickerclass='timepickr')
{
	global $arrLangCommon;
	
	if (!empty($txtclass))
		$txtclass = " class=\"{$txtclass} {$pickerclass}\" ";
	else
		$txtclass = " class=\"{$pickerclass}\" ";
	
	if (!empty($btnclass))
		$btnclass = " class=\"$btnclass\" ";
	
	
	if (!empty($value))
	{
		$arrTime = explode(':', $value);
		if (is_array($arrTime) && sizeof($arrTime)>=2)
		{
			$arrTime[1] = floor($arrTime[1]/10).'0';
		}
		if (empty($arrTime[1]))
			$arrTime[1] = '00';
		
		$value = $arrTime[0].':'.$arrTime[1];
	}
	
	$html = "<input $txtclass name=\"$name\" id=\"$name\" type=\"text\" size=\"20\" maxlength=\"20\" value=\"$value\"  readonly />\n";
	$html .= "&nbsp;<input $btnclass type=\"button\" name=\"Clear\" value=\"".$arrLangCommon['_CLEAR']."\" onclick=\"this.form.$name.value=''\">";
	return $html;
}





function global_init_editor($fieldarr,$editor_mod='simple',$width='100%',$height=350,$lang='cht')
{
	global $globalConf_includePath,$imgurl,$globalConf_live_site,$globalConf_editor_css;
	switch($lang)
	{
		case 'cht':
			$languages = 'tw';
			break;
		case 'en':
			$languages = 'en';
			break;
	}
	if ( is_array($fieldarr) && !empty($fieldarr) )
		$elements = implode(',',$fieldarr);
	else
		$elements = $fieldarr;
	
	if ($editor_mod=='simple')
	{
		$editorjs = "<!-- TinyMCE -->\n";
		$editorjs .= "<script type=\"text/javascript\" src=\"{$globalConf_includePath}tinymce/tiny_mce_gzip.js\"></script>\n";
		$editorjs .= "<script type=\"text/javascript\">\n";
		$editorjs .= "tinyMCE_GZ.init({\n";
		
		$editorjs .= "	themes : 'advanced',//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "	languages : '$languages',//語系\n";
		$editorjs .= "	disk_cache : true,\n";
		$editorjs .= "	debug : false\n";
		$editorjs .= "});\n";
		$editorjs .= "</script>\n";
		

		$editorjs .= "<script language=\"javascript\" type=\"text/javascript\">\n";
		$editorjs .= "	tinyMCE.init({\n";
		$editorjs .= "		language : \"$languages\",//語系\n";
		$editorjs .= "		mode : \"exact\",//頁面載入時elements指定元素轉換成編輯器\n";
		$editorjs .= "		elements : \"$elements\",\n";
		$editorjs .= "		theme : \"advanced\",//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "		theme_advanced_buttons1 : \"bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,link,unlink\",\n";
		$editorjs .= "		theme_advanced_buttons2 : \"\",\n";
		$editorjs .= "		theme_advanced_buttons3 : \"\",\n";
		$editorjs .= "		theme_advanced_toolbar_location : \"top\",//工具列的位置\n";
		$editorjs .= "		theme_advanced_toolbar_align : \"left\",//工具列的對齊方式，有left,right,center\n";
		$editorjs .= "		theme_advanced_statusbar_location : \"bottom\",//路徑以及調整大小按鈕的狀態條的位置\n";
		$editorjs .= "		content_css : \"$globalConf_editor_css\",//指定自定義的CSS檔\n";
		$editorjs .= "		paste_use_dialog : false,\n";
		$editorjs .= "		theme_advanced_resizing : false,//打開或者關閉調整大小按鈕\n";
		$editorjs .= "		theme_advanced_resize_horizontal : false,//是否允許水平調整編輯器大小\n";
		$editorjs .= "		paste_auto_cleanup_on_paste : true,\n";
		$editorjs .= "		paste_convert_headers_to_strong : false,\n";
		$editorjs .= "		paste_strip_class_attributes : \"all\",\n";
		$editorjs .= "		paste_remove_spans : false,\n";
		$editorjs .= "		paste_remove_styles : false	,\n";
		$editorjs .= "		forced_root_block : false,//是否自重插入P\n";
		$editorjs .= "		width : \"$width\",\n";
		$editorjs .= "		height : \"$height\"\n";
		$editorjs .= "	});\n";
		$editorjs .= "</script>\n";
		$editorjs .= "<!-- /TinyMCE -->\n";
	}
	else if ($editor_mod=='word')
	{
		$editorjs = "<!-- TinyMCE -->\n";
		
		$editorjs .= "<script type=\"text/javascript\" src=\"{$globalConf_includePath}tinymce/tiny_mce_gzip.js\"></script>\n";
		$editorjs .= "<script type=\"text/javascript\">\n";
		$editorjs .= "tinyMCE_GZ.init({\n";
		$editorjs .= "	plugins : 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups',\n";
		$editorjs .= "	themes : 'advanced',//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "	languages : '$languages',//語系\n";
		$editorjs .= "	disk_cache : true,\n";
		$editorjs .= "	debug : false\n";
		$editorjs .= "});\n";
		$editorjs .= "</script>\n";
		

		$editorjs .= "<script language=\"javascript\" type=\"text/javascript\">\n";
		$editorjs .= "	tinyMCE.init({\n";
		$editorjs .= "		language : \"$languages\",//語系\n";
		$editorjs .= "		mode : \"exact\",//頁面載入時elements指定元素轉換成編輯器\n";
		$editorjs .= "		elements : \"$elements\",\n";
		$editorjs .= "		theme : \"advanced\",//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "		//插件列表\n";
		$editorjs .= "		plugins : \"safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,googlemaps\",\n";
		$editorjs .= "		//隱藏按鈕\n";
		$editorjs .= "		theme_advanced_disable : \"styleselect,help\",\n";
		$editorjs .= "		//增加按鈕\n";
		$editorjs .= "		theme_advanced_buttons1 : \"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor\",\n";
		$editorjs .= "		theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,googlemaps, googlemapsdel,cleanup,help,code,|,insertdate,inserttime\",\n";
		$editorjs .= "		theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl\",\n";
		$editorjs .= "		theme_advanced_buttons4 : \"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,|,print,|,fullscreen,preview\",\n";
		$editorjs .= "		theme_advanced_toolbar_location : \"top\",//工具列的位置\n";
		$editorjs .= "		theme_advanced_toolbar_align : \"left\",//工具列的對齊方式，有left,right,center\n";
		$editorjs .= "		theme_advanced_statusbar_location : \"bottom\",//路徑以及調整大小按鈕的狀態條的位置\n";
		$editorjs .= "		content_css : \"$globalConf_editor_css\",//指定自定義的CSS檔\n";
		$editorjs .= "		template_external_list_url : \"{$globalConf_includePath}tinymce/js/template_list.js\",//樣版列表\n";
		$editorjs .= "		plugi2n_insertdate_dateFormat : \"%Y-%m-%d\",\n";
	    $editorjs .= "		plugi2n_insertdate_timeFormat : \"%H:%M:%S\",\n";
		$editorjs .= "		file_browser_callback : \"kfm_for_tiny_mce\",//檔案瀏覽器\n";
		$editorjs .= "		paste_use_dialog : false,\n";
		$editorjs .= "		theme_advanced_resizing : false,//打開或者關閉調整大小按鈕\n";
		$editorjs .= "		theme_advanced_resize_horizontal : false,//是否允許水平調整編輯器大小\n";
		$editorjs .= "		paste_auto_cleanup_on_paste : true,\n";
		$editorjs .= "		paste_convert_headers_to_strong : false,\n";
		$editorjs .= "		paste_strip_class_attributes : \"all\",\n";
		$editorjs .= "		paste_remove_spans : false,\n";
		$editorjs .= "		paste_remove_styles : false,\n";
		$editorjs .= "		forced_root_block : false,//是否自重插入P\n";
		$editorjs .= "		relative_urls : false,//是否將絕對位址替換成相對位址\n";
		$editorjs .= "		extended_valid_elements : \"iframe[src|width|height|name|align]\",//是否將允許iframe語法\n";
		$editorjs .= "		width : \"$width\",\n";
		$editorjs .= "		height : \"$height\"\n";
		$editorjs .= "	});\n";
		$editorjs .= "function kfm_for_tiny_mce(field_name, url, type, win){  \n";
		$editorjs .= "	window.SetUrl=function(url,width,height,caption){   \n";
		$editorjs .= "		win.document.forms[0].elements[field_name].value = url;  \n"; 
		$editorjs .= "		if(caption){    \n";
		$editorjs .= "			win.document.forms[0].elements[\"alt\"].value=caption;   \n"; 
		$editorjs .= "			win.document.forms[0].elements[\"title\"].value=caption; \n";  
		$editorjs .= "		}  \n";
		$editorjs .= "	}  \n";
		$editorjs .= "	window.open('{$globalConf_live_site}{$globalConf_includePath}kfm/index.php?lang=zh&mode=selector&type='+type,'kfm','modal,width=800,height=600');\n";
		$editorjs .= "}\n";
		$editorjs .= "</script>\n";
		$editorjs .= "<!-- /TinyMCE -->\n";
	}
	else if ($editor_mod=='bbcode')
	{
		$editorjs = "<!-- TinyMCE -->\n";
		
		$editorjs .= "<script type=\"text/javascript\" src=\"{$globalConf_includePath}tinymce/tiny_mce_gzip.js\"></script>\n";
		$editorjs .= "<script type=\"text/javascript\">\n";
		$editorjs .= "tinyMCE_GZ.init({\n";
		$editorjs .= "	plugins : 'bbcode',\n";
		$editorjs .= "	themes : 'advanced',//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "	languages : '$languages',//語系\n";
		$editorjs .= "	disk_cache : true,\n";
		$editorjs .= "	debug : false\n";
		$editorjs .= "});\n";
		$editorjs .= "</script>\n";
		

		$editorjs .= "<script language=\"javascript\" type=\"text/javascript\">\n";
		$editorjs .= "	tinyMCE.init({\n";
		$editorjs .= "		language : \"$languages\",//語系\n";
		$editorjs .= "		mode : \"exact\",//頁面載入時elements指定元素轉換成編輯器\n";
		$editorjs .= "		elements : \"$elements\",\n";
		$editorjs .= "		theme : \"advanced\",//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "		//插件列表\n";
		$editorjs .= "		plugins : \"bbcode\",\n";
		$editorjs .= "		//隱藏按鈕\n";
		$editorjs .= "		theme_advanced_disable : \"styleselect,help\",\n";
		$editorjs .= "		//增加按鈕\n";
		$editorjs .= "		theme_advanced_buttons1 : \"bold,italic,underline,undo,redo,link,unlink,image,forecolor,styleselect,removeformat,cleanup,code\",\n";
		$editorjs .= "		theme_advanced_buttons2 : \"\",\n";
		$editorjs .= "		theme_advanced_buttons3 : \"\",\n";
		$editorjs .= "		theme_advanced_buttons4 : \"\",\n";
		$editorjs .= "		theme_advanced_toolbar_location : \"top\",//工具列的位置\n";
		$editorjs .= "		theme_advanced_toolbar_align : \"left\",//工具列的對齊方式，有left,right,center\n";
		$editorjs .= "		theme_advanced_statusbar_location : \"bottom\",//路徑以及調整大小按鈕的狀態條的位置\n";
		$editorjs .= "		content_css : \"$globalConf_editor_css\",//指定自定義的CSS檔\n";
		$editorjs .= "		entity_encoding : \"raw\",\n";
		$editorjs .= "		plugi2n_insertdate_dateFormat : \"%Y-%m-%d\",\n";
	    $editorjs .= "		plugi2n_insertdate_timeFormat : \"%H:%M:%S\",\n";
		if ($file_browser)
			$editorjs .= "		file_browser_callback : \"kfm_for_tiny_mce\",//檔案瀏覽器\n";
		$editorjs .= "		remove_linebreaks : false,\n";
		$editorjs .= "		convert_fonts_to_spans : false,\n";
		$editorjs .= "		inline_styles : false,\n";
		$editorjs .= "		paste_use_dialog : false,\n";
		$editorjs .= "		theme_advanced_resizing : false,//打開或者關閉調整大小按鈕\n";
		$editorjs .= "		theme_advanced_resize_horizontal : false,//是否允許水平調整編輯器大小\n";
		$editorjs .= "		paste_auto_cleanup_on_paste : true,\n";
		$editorjs .= "		paste_convert_headers_to_strong : false,\n";
		$editorjs .= "		paste_strip_class_attributes : \"all\",\n";
		$editorjs .= "		paste_remove_spans : false,\n";
		$editorjs .= "		paste_remove_styles : false,\n";
		$editorjs .= "		relative_urls : false,//是否將絕對位址替換成相對位址\n";
		$editorjs .= "		width : \"$width\",\n";
		$editorjs .= "		height : \"$height\"\n";
		$editorjs .= "	});\n";
		$editorjs .= "function kfm_for_tiny_mce(field_name, url, type, win){  \n";
		$editorjs .= "	window.SetUrl=function(url,width,height,caption){   \n";
		$editorjs .= "		win.document.forms[0].elements[field_name].value = url;  \n"; 
		$editorjs .= "		if(caption){    \n";
		$editorjs .= "			win.document.forms[0].elements[\"alt\"].value=caption;   \n"; 
		$editorjs .= "			win.document.forms[0].elements[\"title\"].value=caption; \n";  
		$editorjs .= "		}  \n";
		$editorjs .= "	}  \n";
		$editorjs .= "	window.open('{$globalConf_live_site}{$globalConf_includePath}kfm/index.php?lang=zh&mode=selector&type='+type,'kfm','modal,width=800,height=600');\n";
		$editorjs .= "}\n";
		$editorjs .= "</script>\n";
		$editorjs .= "<!-- /TinyMCE -->\n";
	}
	
	return $editorjs;
}




function global_send_mail($from='', $fromname='', $sendto , $subject, $body, $footer_html='', $replyto='', $lang='', $file='', $filename='') 
{
	global $globalConf_absolute_path, $globalConf_includePath, $globalConf_smtpauth, $globalConf_smtpuser,$globalConf_smtpsecure;
	global $globalConf_smtppass, $globalConf_smtphost,$globalConf_smtpport;
	global $globalConf_mailfrom, $globalConf_fromname, $globalConf_mailer;
    global $globalConf_lang,$db,$globalConf_sys_email,$Conf_smtpauth,$Conf_smtpuser,$Conf_smtppass,$Conf_smtphost,$Conf_smtpport,$conf_php;
	
	
	$globalConf_mailer = 'smtp';

	
	$mail = new PHPMailer();

	$mail->PluginDir = "$conf_php{$globalConf_includePath}phpmailer/";
	$mail->SetLanguage("zh", "$conf_php{$globalConf_includePath}phpmailer/language/");
	
	
	
	$mail->Mailer 	= $globalConf_mailer;
	
	if ( $globalConf_mailer == 'smtp' ) 
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = $Conf_smtpauth;
		
		$mail->Host = $Conf_smtphost;		      
		$mail->Port = $Conf_smtpport;
		$mail->CharSet = "UTF-8"; 
		$mail->Username = $Conf_smtpuser;
		$mail->Password = $Conf_smtppass;
	}
	else
	{
		$mail->IsMail();
	}
	
	
	if($lang=="")
		$lang='utf-8';
		
	
	
	$mail->CharSet=$lang;
	if (empty($from))
		$from = $globalConf_mailfrom;
	
	if (empty($fromname))
		$fromname = $globalConf_fromname;
	if ($lang!='utf-8')
		$fromname = mb_convert_encoding($fromname, $lang, 'UTF-8');
	
	$mail->SetFrom($from, $fromname);
	if($replyto){
		if ($lang!='utf-8')
			$mail->AddReplyTo( $replyto,mb_convert_encoding( $fromname, $lang, 'UTF-8') );
		else
			$mail->AddReplyTo( $replyto, $fromname );
	}
	
	
	
    
    
    
	  
    $disable_timeout  = global_get_param( $_POST, "disable_timeout", '' );
    if ( $disable_timeout ) {
        @set_time_limit(0);
    }
    
    
	$html_message = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
					  <html>
						  <head>
							  <title>$subject</title>
						  </head>
						  <body>
						  {$body}
						  {$footer_html}
						  </body>
					  </html>";
				  
	
	if ($lang!='utf-8')
	{
		$mail->Subject =mb_convert_encoding($subject, $lang, 'UTF-8');
		$mail->MsgHTML(mb_convert_encoding($html_message, $lang, 'UTF-8'));
		$mail->AltBody = mb_convert_encoding(strip_tags($html_message), $lang, 'UTF-8');
	}
	else
	{
		
		$mail->Subject = $subject;
		
		$mail->MsgHTML($html_message);
		$mail->AltBody = strip_tags($html_message);
	}

	if (!empty($file) && file_exists($file))
	{
		$mail->ClearAttachments();
		
		$mail->Encoding = 'base64';
		
		$mail->AddAttachment($file, $filename); 
	}
	
	$mail->ClearAddresses();
	
	$logarr = array();
	$logarr['state']='';
	$logarr['msg']='';
	
	
	foreach ($sendto as $row) 	
	{
		if (!empty($row['email']))
		{
			if ($lang!='utf-8')
				$mail->AddAddress( $row['email'], mb_convert_encoding($row['name'], $lang, 'UTF-8') );
			else
				$mail->AddAddress( $row['email'], $row['name'] );
		}
	}

	
	if($mail->Send())	
	{
		$logarr['state']='sus';
	}
	else
	{
		$logarr['state']='err';
		$logarr['msg']=$mail->ErrorInfo;
	}
	$logarr['body'] = $body;
	$logarr['to'] = $row['email'];

	return $logarr;
}



function global_get_code_name($CodeKind,$CodeValue)
{	
	global $db;
	$sql="select CodeName from syscode where CodeKind='$CodeKind' and CodeValue='$CodeValue'"  ;
  	$rs = $db->Execute($sql);
  	if ($rs)
  	{
  		return  $rs->fields['CodeName'];
    }
    else
		return  null;
} 





function global_get_code_value($CodeKind,$CodeName)
{
	global $db;
	$sql="select CodeValue from syscode where CodeKind='$CodeKind' and CodeName='$CodeName'"  ;
  	$rs = $db->Execute($sql);
  	if ($rs)
  	{
  		return  $rs->fields['CodeValue'];
    }
    else
		return  null;
}





function global_get_first_code_value($CodeKind)
{
	global $db;
	$sql="select CodeValue from syscode where CodeKind='$CodeKind' order by odring, id"  ;
  	$rs = $db->GetOne($sql);
  	if ($rs===false)
  		return  null;
    else
		return  $rs;
}





function global_get_code_slect($ctrlName,$CodeKind,$default,$please_choose=null,$tourl=null,$appendsql=null,$append=null,$urlencode=false,$append_front_opt='',$append_end_opt='')
{
	global $db;
	
	if (!empty($tourl))
		 $tourlstr = " onChange=\"location.href='$tourl'+this.value\"";
	
	$htm = "<select name=\"$ctrlName\" id=\"$ctrlName\" $tourlstr $append>\n";
	
	if ($please_choose!=null)
	{
		$htm .= "<option value=''>$please_choose</option>\n";
	} 
	
	if (!mb_ereg('order by', strtolower($appendsql)))
		$appendsql .= "order by odring, id";
	
	$htm .= $append_front_opt;

	$sql="select CodeValue, CodeName from syscode where CodeKind='$CodeKind' $appendsql"  ;

	$rs = $db->Execute($sql);
	if ($rs)
	{
		while (!$rs->EOF)
		{
			if ($rs->fields['CodeValue']==$default)
			{
				if (!$urlencode)
					$htm .= "<option value='".$rs->fields['CodeValue']."' selected>".$rs->fields['CodeName']."</option>\n"; 
				else
					$htm .= "<option value='".$rs->fields['CodeValue']."' selected>".urlencode($rs->fields['CodeName'])."</option>\n"; 
			}
			else
			{
				if (!$urlencode)	
					$htm .= "<option value='".$rs->fields['CodeValue']."'>".$rs->fields['CodeName']."</option>\n"; 
				else
					$htm .= "<option value='".$rs->fields['CodeValue']."'>".urlencode($rs->fields['CodeName'])."</option>\n"; 
			}
			$rs->MoveNext();
		}
		$rs->Close();
	}
	$htm .= $append_end_opt;
	$htm .= "</select>\n";
	
	return $htm;
}





function global_get_code_radio($ctrlName,$CodeKind,$default=null,$first_choose=true,$appendsql=null,$append='')
{
	global $db;
	
	if (!mb_ereg('order by', strtolower($appendsql)))
		$appendsql .= "order by odring, id";

	$sql="select * from syscode where CodeKind='$CodeKind' $appendsql"  ;
  	$rs = $db->Execute($sql);
	if ($rs)
	{
		while (!$rs->EOF)
		{
			if (empty($default) && $first_choose && $rs->CurrentRow()==0)
			{
				$htm .= "<input name=\"$ctrlName\" id=\"{$ctrlName}_".$rs->fields['CodeValue']."\" type=\"radio\" value=\"".$rs->fields['CodeValue']."\" {$append} checked><label for=\"{$ctrlName}_".$rs->fields['CodeValue']."\">".$rs->fields['CodeName']."</label>\n";
			}
			elseif ($rs->fields['CodeValue']==$default)
			{
				$htm .= "<input name=\"$ctrlName\"id=\"{$ctrlName}_".$rs->fields['CodeValue']."\"  type=\"radio\" value=\"".$rs->fields['CodeValue']."\" {$append} checked><label for=\"{$ctrlName}_".$rs->fields['CodeValue']."\">".$rs->fields['CodeName']."</label>\n";
			}
			else
			{
				$htm .= "<input name=\"$ctrlName\"id=\"{$ctrlName}_".$rs->fields['CodeValue']."\"  type=\"radio\" value=\"".$rs->fields['CodeValue']."\" {$append}><label for=\"{$ctrlName}_".$rs->fields['CodeValue']."\">".$rs->fields['CodeName']."</label>\n";
			}
			$rs->MoveNext();
		}
		$rs->Close();
	}
	
	return $htm;
}





function global_make_select($db, $sql, $value, $name, $ctrlName, $default='', $please_choose=null, $tourl=null, $append='')
{
	$value = strtolower($value);
	$name = strtolower($name);
	
	if (!empty($tourl))
		 $tourlstr = " onChange=\"location.href='$tourl'+this.value\"";
	
	$htm = "<select name=\"$ctrlName\" id=\"$ctrlName\" $tourlstr $append>\n";
	
	if ($please_choose!=null)
	{
		$htm .= "<option value=''>".$please_choose."</option>\n";
	} 

  	$rs = $db->Execute($sql);
	if ($rs)
	{
		while (!$rs->EOF)
		{
			if ($rs->fields[$value]==$default)
			{
				$htm .= "<option value='".$rs->fields[$value]."' selected>".$rs->fields[$name]."</option>\n"; 
			}
			else
			{
				$htm .= "<option value='".$rs->fields[$value]."'>".$rs->fields[$name]."</option>\n"; 
			}
			$rs->MoveNext();
		}
		$rs->Close();
	}
	$htm .= "</select>\n";
	
	if ($rs->RecordCount()>0)
		return $htm;
	else
		return false;
}





function global_array_to_select($arrOpt, $ctrlName, $default, $please_choose=null, $tourl=null, $append='')
{
	if (!empty($tourl))
		 $tourlstr = " onChange=\"location.href='$tourl'+this.value\"";
	
	$htm = "<select name=\"$ctrlName\" id=\"$ctrlName\" $tourlstr>\n";
	
	if ($please_choose!=null)
	{
		$htm .= "<option value=''>".$please_choose."</option>\n";
	} 
	if (is_array($arrOpt) && sizeof($arrOpt)>0)
	{
		foreach ($arrOpt as $key=>$value)
		{
			if ($key==$default)
			{
				$htm .= "<option value='$key' selected>$value</option>\n"; 
			}
			else
			{
				$htm .= "<option value='$key'>$value</option>\n"; 
			}
		}
	}
	$htm .= "</select>\n";
	
	return $htm;
}





function global_make_date_select($datetype ,$ctrlName, $default, $please_choose=null, $tourl=null, $append='', $format='', $start=null, $end=null)
{
	$value = strtolower($value);
	$name = strtolower($name);
	
	if (!empty($tourl))
		 $tourlstr = " onChange=\"location.href='$tourl'+this.value\"";
	
	$htm = "<select name=\"$ctrlName\" id=\"$ctrlName\" $tourlstr $append>\n";
	
	if ($please_choose!=null)
	{
		$htm .= "<option value=''>".$please_choose."</option>\n";
	} 
	
	switch ($datetype)  
	{
		case 'Y':
			$start = 1920;
			$end = 2020;
			break;
		case 'M':
			$start = 1;
			$end = 12;
			break;
		case 'D':
			$start = 1;
			$end = 31;
			break;
		case 'custom':
			$start = $start;
			$end = $end;
			break;
	}
	for ($i=$start; $i<=$end; $i++)
	{
		if ($i!=$default)
			$selected = '';
		else
			$selected = ' selected';
		if ($format=='')
			$htm .= "<option value='$i' $selected>{$i}</option>\n"; 
		else
			$htm .= "<option value='$i' $selected>".sprintf($format, $i)."</option>\n"; 
	}
		

	$htm .= "</select>\n";
	
	return $htm;
}





function global_get_code_checkbox($ctrlName,$CodeKind,$arrDefault=array(),$appendsql=null)
{
	global $db;

	$sql="select CodeValue, CodeName from syscode where CodeKind='$CodeKind' $appendsql"  ;
  	$rs = $db->Execute($sql);
	if ($rs)
	{
		while (!$rs->EOF)
		{
			if (in_array($rs->fields['CodeValue'], $arrDefault))
			{
				$htm .= "<label><input name=\"{$ctrlName}[]\"id=\"{$ctrlName}_".$rs->fields['CodeValue']."\"  type=\"checkbox\" value=\"".$rs->fields['CodeValue']."\" checked>".$rs->fields['CodeName']."</label>\n";
			}
			else
			{
				$htm .= "<label><input name=\"{$ctrlName}[]\"id=\"{$ctrlName}_".$rs->fields['CodeValue']."\"  type=\"checkbox\" value=\"".$rs->fields['CodeValue']."\">".$rs->fields['CodeName']."</label>\n";
			}
			$rs->MoveNext();
		}
		$rs->Close();
	}
	
	return $htm;
}





function global_get_xajax_js()
{
	$argsarr = func_get_args();
	
	$xajax_js = 'xajax_processForm(';
	
	foreach ($argsarr as $value)
	{
		$xajax_js .= "$value,";
	}
	$xajax_js = substr($xajax_js,0,strlen($xajax_js)-1);
	$xajax_js .= ');';

	return $xajax_js;
}





function global_get_loading_js($elId, $loadimg='loading.gif')
{
	global $imgurl;
	
	$loading_js = "global_show_loading('$elId', '{$imgurl}{$loadimg}');";

	return $loading_js;
}





function global_file_in_dir($substr,$path='.')
{
	$rtn = null;
	$dir = $path;
	if (is_dir($dir)) 
	{
    	if ($dh = opendir($dir)) {
    	    while (($file = readdir($dh)) !== false) 
    	    {
    	    	if ($file!='.' and $file!='..' )
    	    	{
    	    		$filename = substr($file, 0,strpos($file, '.'));
					if ($filename == $substr)
    	    		{
    	    			$rtn = $file;
    	    		}
    	    	}
    	    }
    	    closedir($dh);
    	}
	}
	return $rtn;
}





function global_find_file($regex, $dir) 
{
    if (mb_ereg('\/$',$dir))
		$dir = substr($dir,0,strlen($dir));
	
	if (!is_dir($dir))
		return false;
	
	$matches = array();
    
    
    $d = dir($dir);

    
    while (false !== ($file = $d->read())) 
	{
        
        if (($file == '.') || ($file == '..')) 
		{ 
			continue; 
		}

        
        if (is_dir("{$dir}/{$file}")) 
		{
            
            $submatches = global_find_file($regex, "{$dir}/{$file}");
            
            $matches = array_merge($matches, $submatches);
        } 
		else 
		{
            
            if (preg_match($regex, $file)) 
			{
                
                $matches[] = "{$dir}/{$file}";
            }
        }
    }
    
    
    return $matches;
}





function global_read_directory( $path, $filter='.' ,$format=null) 
{
	$arr = array();
	if (!@is_dir( $path )) 
	{
		return $arr;
	}
	$handle = opendir( $path );

	while ($file = readdir($handle)) 
	{
		if (($file <> ".") && ($file <> "..") && preg_match( "/$filter/", $file )) 
		{
			if ($format != null)
			{
				$file_count = explode(".",$file);
				if ($file_count[count($file_count)] == $format)
				{
					$arr[] = trim( $file );
				}
			} 
			else 
			{
				$arr[] = trim( $file );
			}

		}
	}
	closedir($handle);
	asort($arr);
	return $arr;
}



function global_file_upd($id,$filearr,$ext_name=null,$uppath,$errbackurl)
{
	global $globalConf_fileupload_limit, $arrLangCommon;
	if($filearr['size']!=0)
	{
		if (!mb_ereg('\/$',$uppath))
			$uppath = "{$uppath}/";
		
		if ($ext_name!=null)
			$fpath = $ext_name;	
		else
			$fpath = $id;	
		
		
		$fileext = strrchr($filearr['name'],'.');
		
		$cpurl = $uppath.$fpath.$fileext;		
		
		
		if ($filearr['type'] != 'image/gif' and $filearr['type'] != 'image/pjpeg' and $filearr['type'] != 'image/jpeg'
		 	and $filearr['type'] != 'image/x-png' and $filearr['type'] != 'image/png' and $filearr['type'] != 'image/bmp' 
		 	and $filearr['type'] != 'application/pdf' and $filearr['type'] != 'application/zip' and $filearr['type'] != 'text/plain' 
			and $filearr['type'] != 'application/msword' and $filearr['type'] != 'application/x-rar-compressed' 
			and $filearr['type'] != 'application/vnd.ms-powerpoint' and $filearr['type'] != 'application/vnd.ms-excel' 
			and $filearr['type'] != 'application/octet-stream' and $filearr['type'] != 'application/x-zip-compressed')
			return _BASEINFO_UPDATEIMG_TYPEERR_MSG;
			
		
		if ($filearr['size']>$globalConf_fileupload_limit)
			return _BASEINFO_UPDATEIMG_SIZEERR_MSG;
		
		
		if (!is_dir($uppath))
			mkdir($uppath, 0777);
	
		if (!move_uploaded_file($filearr['tmp_name'], $cpurl))
			return _BASEINFO_UPDATEIMG_ERR_MSG;
		  	
	}
	
	return true;
}





function global_file_del($regex,$uppath,$errbackurl)
{
	
	$delarr = global_find_file($regex,$uppath);

	if (is_array($delarr) && !empty($delarr))
	{
		foreach ($delarr as $value)
		{
			unlink($value);
		}
	}
	
  	return true;
}





function global_file_del_all($id, $ext_name=null, $uppath, $errbackurl)
{
	if ($ext_name!=null)
		$fpath = $id.$ext_name;	
	else
		$fpath = $id;
	
	$dir = $uppath;
	if (is_dir($dir)) 
	{
    	if ($dh = opendir($dir)) {
    	    while (($file = readdir($dh)) !== false) 
    	    {
    	    	if ($file!='.' and $file!='..' )
    	    	{
    	    		if (stristr($file, $fpath))
    	    		{
    	    			@unlink($dir.$file);
    	    		}
    	    	}
    	    }
    	    closedir($dh);
    	}
	}

  	return true;
}





function global_sure_remove_dir($dir, $DeleteMe) 
{
    if(!$dh = @opendir($dir)) return false;
    while (($obj = readdir($dh))) 
	{
        if($obj=='.' || $obj=='..') continue;
        if (!@unlink($dir.'/'.$obj)) global_sure_remove_dir($dir.'/'.$obj, true);
    }
    if ($DeleteMe)
	{
        closedir($dh);
		if(@rmdir($dir))
			return true;
    }
	return false;
}





function global_image_size_control($img, $width, $height, $returnValue=null)
{
	list($img_width, $img_height, $img_type, $img_html) = getimagesize($img);
	if ($img_width >= $img_height && $img_width > $width)
	{
		$img_rate = $width/$img_width;
		$revise_width = $width;
		$revise_height = round(($img_height*$img_rate));
		
	}
	else if($img_width <= $img_height && $img_height > $height)
	{
		$img_rate = $height/$img_height;
		$revise_height = $height;
		$revise_width = round(($img_width*$img_rate));
	}
	else
	{
		$revise_height = $img_height;
		$revise_width = $img_width;
	}
	
	if ($revise_width > $width)
	{
		$img_rate = $width/$revise_width;
		$revise_width = $width;
		$revise_height = round(($revise_height*$img_rate));
		
	}
	else if($revise_height > $height)
	{
		$img_rate = $height/$revise_height;
		$revise_height = $height;
		$revise_width = round(($revise_width*$img_rate));
	}
	
	if ($revise_width>$revise_height)
		$img_fit = " width=\"$width\" ";
	else if($revise_width<$revise_height)
		$img_fit = " height=\"$height\" ";
	
	$img_set = " width=\"$revise_width\" height=\"$revise_height\" ";
	
	switch (trim($returnValue))
	{
	case 'width':
		return $revise_width;
		break;
	case 'height':
		return $revise_height;
		break;
	case 'fit':
		return $img_fit;
		break;
	default:
		return $img_set;
	}
}





function global_img_upd($id,$imgarr,$ext_name=null,$uppath,$errbackurl,$imageResize=null,$imageRewidth=null,$imageReheight=null,$symbol=null)
{
	global $globalConf_imgupload_limit, $lang, $globalConf_img_s_width, $globalConf_img_s_height, $globalConf_img_b_width, $globalConf_img_b_height, $globalConf_img_width, $globalConf_img_height,$arrLangCommon;

	if($imgarr['size']!=0)
	{
		if (!mb_ereg('\/$',$uppath))
			$uppath = "{$uppath}/";

		
		$fpath = "{$id}{$ext_name}.jpg";
		
		$fspath = "{$id}{$ext_name}_s.jpg";	
		
		$fbpath = "{$id}{$ext_name}_b.jpg";
		
		$fdpath = "{$id}{$ext_name}{$symbol}.jpg";
		
		
		$cpurl = $uppath.$fpath;	
		
		$cpsurl = $uppath.$fspath;
		
		$cpburl = $uppath.$fbpath;		
		
		$cpdurl = $uppath.$fdpath;	

		
		if ($imgarr['type'] != 'image/gif' and $imgarr['type'] != 'image/pjpeg' and $imgarr['type'] != 'image/jpeg' and $imgarr['type'] != 'image/x-png' and $imgarr['type'] != 'image/png' and $imgarr['type'] != 'image/bmp')
			showMsgRedirect($arrLangCommon['_IMGFMTERR'],$errbackurl);
		
		
		if ($imgarr['size']>$globalConf_imgupload_limit)
			showMsgRedirect($arrLangCommon['_IMGTOBIG'],$errbackurl);
		
		
		if (!is_dir($uppath))
			mkdir($uppath, 0777);

		
		$fileext = strrchr($imgarr['name'],'.');
		$cpurl = $uppath.$id.$ext_name.$symbol.$fileext;
		
		if (!move_uploaded_file($imgarr['tmp_name'], $cpurl))
			showMsgRedirect($arrLangCommon['_IMGUPDERR'], $errbackurl);  
		
	
	}
	
	return true;
}




function global_img_del($id,$ext_name=null,$uppath,$errbackurl)
{
	global $mainframe;
	if ($ext_name!=null)
		$fpath = "{$id}{$ext_name}.jpg";	
	else
		$fpath = "{$id}.jpg";
	
	$dlurl = $uppath.$fpath;

	if (file_exists($dlurl))
  	{
  		unlink($dlurl);
  	}
  	
  	return true;
}

function convertEncoding($string){
    //根據系統進行配置
    $encode = stristr(PHP_OS, 'WIN') ? 'GBK' : 'UTF-8';
    $string = iconv('UTF-8', $encode, $string);
    //$string = mb_convert_encoding($string, $encode, 'UTF-8');
    return $string;
}

function is_simplified($str)
{
    $len = mb_strlen($str, 'utf-8');

    return ($len != mb_strlen(iconv('UTF-8', 'cp950//IGNORE', $str), 'cp950')) ? true : false;
}

function is_traditional($str)
{
    $len = mb_strlen($str, 'utf-8');

    // gbk 包含 big5 內的字元，所以不能用 gbk
    return ($len != mb_strlen(iconv('UTF-8', 'gb2312//IGNORE', $str), 'gb2312')) ? true : false;
}

?>
