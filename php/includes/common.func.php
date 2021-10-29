<?php
/**
* version #1.0
* package Taxi APP
* date 2009/3
* author Justin hsu 
* email bibibobo97@gmail.com
* copyright protected
*/

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

/** 資料庫連線 */
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
		//$db->PConnect($host, $user, $password, $db_name);//不間斷連線
		$db->Connect($host, $user, $password, $db_name);//連線
		//連線錯誤時的處理
		for ($i=0; $i<8; $i++)
		{
			//
			if ($db->Connect($host, $user, $password, $db_name)===false)
			{
				sleep (5);
				if ($i==7)
				{
					//資料庫連線錯誤資訊
					$errmsg = "網站無回應，請稍候再試，<br>\n";
					echo $errmsg;
					exit;
				}
			}	
			else
				break;
		}
		
		//以欄位名稱為Key值
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

		//設定SQL編碼為utf8
		//$db->Execute('set character set utf8');
		//$db->Execute('set collation_connection = utf8_general_ci');
	}
	
	return $db;
}
/** 資料庫連線結束 */

/** 自訂的錯誤處理器 */
/** 它將列出一個完整的回溯追蹤紀錄 */
function detailed_err($errno, $errstr, $errfile, $errline, $errcontext) 
{
	//首先，顯示重大錯誤標題以及基本資訊
	echo "<p>重大錯誤 - {$errstr}</p>\n";
	
	//利用debug_print_backtrace，以壓縮格式顯示函式呼叫列表
	//方便掃描:
	echo "<pre>\n函式呼叫列表:\n";
	debug_print_backtrace();
	
	//列出一個完整的回溯追蹤紀錄
	//其中包含現存的所有變數及其他更多東西
	echo "\n完整回溯追蹤紀錄:\n";
	var_dump(debug_backtrace());
	
	//關閉pre標籤
	echo "</pre>\n";
	
	//程式結束
	die();
}
/** 自訂的錯誤處理器結束 */

/** 自訂的錯誤處理器 */
/** 它將會把所有的錯誤存入資料庫 */
function db_err_handler($errno, $errstr, $errfile, $errline, $errcontext) 
{
	global $globalConf_debug,$globalConf_dbtype,$globalConf_host,$globalConf_user,$globalConf_password,$globalConf_db;
	
	//開啟一個全新的資料庫連線，不重複利用舊的連線
	//資料庫連線
	$db = global_init_database($globalConf_dbtype, $globalConf_user, $globalConf_password, $globalConf_host, $globalConf_db);

	//由於錯誤資訊是個相當巨量的陣列，儲存所有現存的變數
	//需要序列化錯誤資訊以便儲存
	$ized = serialize($errcontext);
	//在特殊字元上加反斜線
	$errstrMod = $db->qstr(addslashes($errstr));
	$errfileMod = $db->qstr(addslashes($errfile));
	$izedMod = $db->qstr(addslashes($ized));

	//將錯誤寫入資料表
	$sql = "insert into error_log set ".
	       "errno = $errno,".
		   "errstr = '$errstrMod',".
		   "errfile = '$errfileMod',".
		   "errline = $errline,".
		   "errcontext = '$izedMod',".
		   "ctime = '".$_SERVER['REQUEST_TIME']."';";

	$db->Execute($sql);
	
	//如果是重大錯誤，就輸出'ERROR'然後砍掉程序，否則程式繼續
	if (($errno == E_ERROR) || ($errno == E_USER_ERROR)) 
	{
		if ($globalConf_debug)//如果debug開啟
			die(detailed_err($errno, $errstr, $errfile, $errline, $errcontext));
		else//如果debug未開啟
			die(_FATAL_ERROR_MSG);
	}
}
/** 自訂的錯誤處理器結束 */

/** 判斷造訪者是否為機器人 */
function global_is_robot() 
{ 
	if(!defined('IS_ROBOT')) 
	{ 
		//取得user agent參數
		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']); 
		//機器人種類
		$kw_spiders = "/(bot|crawl|spider|slurp|yahoo|sohu-search|lycos|robozilla|adsense|feed|google)/i"; 
		//瀏覽器種類
		$kw_browsers = '/(MSIE|Netscape|Opera|Konqueror|Mozilla)/i'; 
		
		if(preg_match($kw_spiders, $user_agent)) //是機器人
		{ 
			define('IS_ROBOT', TRUE); 
		} 
		elseif(preg_match($kw_browsers, $user_agent)) //是瀏覽器
		{ 
			define('IS_ROBOT', FALSE); 
		} 
		else //其它
		{ 
			define('IS_ROBOT', TRUE); 
		} 
	} 
	return IS_ROBOT; 
} 
/** 判斷造訪者是否為機器人結束 */

/** 判斷機器人名稱 */
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
/** 判斷機器人名稱結束 */

//初始化Gzip
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

//執行Gzip
function global_do_gzip() 
{
	global $globalConf_gzip;
	if ( $globalConf_gzip  && !global_is_robot() && $_SERVER['HTTPS']!='on') 
	{
		/**
		*Borrowed from php.net!
		*/
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
          
/**
* 設定檔案執行路徑
* Determines the paths for including engine and menu files
* @param string The current option used in the url
* @param string The base path from which to load the configuration file
*/
function global_set_path( $option, $component, $basePath='.') {
	global $db;
	
	$option = strtolower( $option );
	$path = array();

	$prefix = substr( $option, 0, 4 );
	if ($prefix != 'com_') 
	{
		// ensure backward compatibility with existing links
		$name = $option;
		$component = "com_$component";
	} 
	else 
	{
		$name = substr( $option, 4 );
	}

	//前端模組
	if (file_exists( "$basePath/components/$component/$name.php" )) 
	{
		$path['process'] = "$basePath/components/$component/$name.php";
	}

	//物件模組
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

/** 取得component名 */
function global_get_component($functional, $option)
{
	global $db;
	
	$sql = "select componentid from sysoption_m where functional='$functional' and name='$option'";
	$rs = $db->Execute($sql);
	if($rs)//取用memory資料表
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
	if (empty($component))//取用MyISAM資料表
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
		//清空memory資料表
		$sql = " TRUNCATE TABLE `sysoption_m`";
		$db->Execute($sql);
		$sql = " TRUNCATE TABLE `syscomponent_m`";
		$db->Execute($sql);
		//寫入memory資料表
		$sql = "INSERT INTO `sysoption_m` SELECT * FROM `sysoption`";
		$db->Execute($sql);
		$sql = "INSERT INTO `syscomponent_m` SELECT * FROM `syscomponent`";
		$db->Execute($sql);
	}
	return $component;
}
/** 取得component名結束 */

function utf8_urldecode($str) {
	$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str)); 
	return html_entity_decode($str,null,'UTF-8');
}
/**
* 取得參數的funtion
* Utility function to return a value from a named array or a specified default value
* this function could be modified for advanced filter
* $def = 預設值；$mask:0 = 去除空白，1 = 不更動；$filter:0 = 不更動；1 = 過濾HTML語法;
*/
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
			//去除 undefined
			$arr[$name] = str_replace( 'undefined' , '', $arr[$name] );
			
			
			//去除空白
			if (!$mask && !is_array($arr) && !is_object($arr)) 
			{
				$arr[$name] = trim( $arr[$name] );
			}
			//過濾HTML語法
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
			//字串加入斜線
			if (!get_magic_quotes_gpc()) 
			{
				$arr[$name] = addslashes( $arr[$name] );
			}
			
			//必要欄位檢查
			if(empty($arr[$name]) && $required)
			{
				$arrJson = array();
				$arrJson['status'] = "0";
				$arrJson['msg'] = urlencode($nameStr._COMMON_PARAM_VALIDATE_NOT_REQUIRED);
				JsonEnd($arrJson);
				exit;
			}
			
			//資料驗證
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
/** 取得參數的funtion結束 */

/** 產生快取檔 */
function global_set_cache($flagCache=false, $option='', $cache_name='', $printers='')
{
	global $tpl,$globalConf_cache,$globalConf_cache_dir,$lang;
	//快取檔存放的目錄
	$cache_dir = "{$globalConf_cache_dir}{$lang}/";
	if ($globalConf_cache==true)//執行快取
	{
		if ($flagCache==true)//要產生快取檔
		{
			try 
			{
				$cache=new Cache();
				$cache->addDriver('file', new FileCacheDriver($cache_dir));
				$cache->set($option, $cache_name, $printers); //產生快取檔
			
			}
			catch (CacheException $e)
			{
				echo 'Error: '.$e->getMessage();
			}
		}
	}
	
}
/** 產生快取檔結束 */

/** 取得快取 */
function global_get_cache($option='', $compath='', $tablename='', $id='', $mtime='', $appendsql = '')
{
	global $db, $flagCache, $cache_name,$globalConf_cache,$globalConf_cache_dir,$lang;
	if ($globalConf_cache==true)//執行快取
	{
		//快取檔存放的目錄
		$cache_dir = "{$globalConf_cache_dir}{$lang}/";
		$cache_name = md5($compath);
		try 
		{
			//取得快取檔建立的時間
			if (file_exists("{$cache_dir}{$option}/{$cache_name}.cache"))
			{
				$mcount = 0;
				$cache_time = filemtime("{$cache_dir}{$option}/{$cache_name}.cache");
				if ($mtime!='')
				{
					if ($mtime>$cache_time)
						$mcount = 1;
				}
				if ($mcount==0 && $tablename!='')//檢查是否有修改時間大於快取時間的資料
				{
					if (!empty($id))
						$appendsql .= " and id=$id";

					$sqlcnt = "select count(*) from $tablename where mtime>'$cache_time' $appendsql";
					$mcount = global_get_record_count($db, $sqlcnt);
				}
				if ($mcount==0)//沒有新資料，將使用快取
				{
					$cache=new Cache();
					$cache->addDriver('file', new FileCacheDriver($cache_dir));
					$printers=$cache->get($option, $cache_name, 2592000); //取得一個月內的快取資料
					
					if($printers===false) //沒有快取資料
					{ 	
						$flagCache = true;//提醒要產生快取
					}
					else
					{
						//印出快取
						echo $printers;
						//關閉資料庫
						$db->Close(); 
						//輸出header資訊
						global_output_header();
						//ob_end
						global_do_gzip();
						exit;
					}
				}
				else//沒有快取資料
					$flagCache = true;//提醒要產生快取
			}
			else
				$flagCache = true;//提醒要產生快取
		}
		catch (CacheException $e)
		{
			echo 'Error: '.$e->getMessage();
		}
	}
	
}
/** 取得快取結束 */

/** 輸出header資訊 */
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
			//header('Content-type: text/plain');
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
/** 輸出header資訊結束 */

/** 產生月曆input text */
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
/** 產生月曆input text結束 */

/** 產生時間input text */
function global_make_time_text($name, $value, $txtclass='textbg', $btnclass='btn', $pickerclass='timepickr')
{
	global $arrLangCommon;
	
	if (!empty($txtclass))
		$txtclass = " class=\"{$txtclass} {$pickerclass}\" ";
	else
		$txtclass = " class=\"{$pickerclass}\" ";
	
	if (!empty($btnclass))
		$btnclass = " class=\"$btnclass\" ";
	
	//值不為空
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
/** 產生時間input text結束 */

/** 初始化HTML編輯器 */
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
		//$editorjs .= "	plugins : 'style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',\n";
		$editorjs .= "	themes : 'advanced',//編輯器主題，有'advanced'及'simple'\n";
		$editorjs .= "	languages : '$languages',//語系\n";
		$editorjs .= "	disk_cache : true,\n";
		$editorjs .= "	debug : false\n";
		$editorjs .= "});\n";
		$editorjs .= "</script>\n";
		/*
		$editorjs .= "<script type=\"text/javascript\" src=\"$globalConf_includePath/tinymce/tiny_mce.js\"></script>\n";
		*/
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
		/*
		$editorjs .= "<script type=\"text/javascript\" src=\"$globalConf_includePath/tinymce/tiny_mce.js\"></script>\n";
		*/
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
		/*
		$editorjs .= "<script type=\"text/javascript\" src=\"$globalConf_includePath/tinymce/tiny_mce.js\"></script>\n";
		*/
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
		$editorjs .= "		entity_encoding : \"raw\",\n";//編碼類型
		$editorjs .= "		plugi2n_insertdate_dateFormat : \"%Y-%m-%d\",\n";
	    $editorjs .= "		plugi2n_insertdate_timeFormat : \"%H:%M:%S\",\n";
		if ($file_browser)
			$editorjs .= "		file_browser_callback : \"kfm_for_tiny_mce\",//檔案瀏覽器\n";
		$editorjs .= "		remove_linebreaks : false,\n";
		$editorjs .= "		convert_fonts_to_spans : false,\n";
		$editorjs .= "		inline_styles : false,\n";//換行
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
/** 初始化HTML編輯器結束 */

//寄送mail的function
function global_send_mail($from='', $fromname='', $sendto , $subject, $body, $footer_html='', $replyto='', $lang='', $file='', $filename='') 
{
	global $globalConf_absolute_path, $globalConf_includePath, $globalConf_smtpauth, $globalConf_smtpuser,$globalConf_smtpsecure;
	global $globalConf_smtppass, $globalConf_smtphost,$globalConf_smtpport;
	global $globalConf_mailfrom, $globalConf_fromname, $globalConf_mailer;
    global $globalConf_lang,$db,$globalConf_sys_email,$Conf_smtpauth,$Conf_smtpuser,$Conf_smtppass,$Conf_smtphost,$Conf_smtpport,$conf_php;
	
	
	$globalConf_mailer = 'smtp';

	//aicl?A
	$mail = new PHPMailer();

	$mail->PluginDir = "$conf_php{$globalConf_includePath}phpmailer/";
	$mail->SetLanguage("zh", "$conf_php{$globalConf_includePath}phpmailer/language/");
	//$mail->CharSet = substr_replace(_ISO, '', 0, 8);
	
	//設定郵件模式
	$mail->Mailer 	= $globalConf_mailer;
	// Add smtp values if needed
	if ( $globalConf_mailer == 'smtp' ) 
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = $Conf_smtpauth;
		//$mail->SMTPSecure = "ssl";
		$mail->Host = $Conf_smtphost;		      
		$mail->Port = $Conf_smtpport;
		$mail->CharSet = "UTF-8"; //設定郵件編碼
		$mail->Username = $Conf_smtpuser;
		$mail->Password = $Conf_smtppass;
	}
	else
	{
		$mail->IsMail();
	}
	
	//字元編碼
	if($lang=="")
		$lang='utf-8';
		//$lang='BIG5';
	
	//設定字元編碼
	$mail->CharSet=$lang;
	if (empty($from))
		$from = $globalConf_mailfrom;
	//寄件人名稱
	if (empty($fromname))
		$fromname = $globalConf_fromname;
	if ($lang!='utf-8')
		$fromname = mb_convert_encoding($fromname, $lang, 'UTF-8');
	//寄件人
	$mail->SetFrom($from, $fromname);
	if($replyto){
		if ($lang!='utf-8')
			$mail->AddReplyTo( $replyto,mb_convert_encoding( $fromname, $lang, 'UTF-8') );
		else
			$mail->AddReplyTo( $replyto, $fromname );
	}
	//$mail->Subject =mb_convert_encoding($subject,$lang,"UTF-8");
	//$mail->Body =mb_convert_encoding($body,$lang,"UTF-8");
	
    //because sending mail may take a long time, we want to disable timeout
    //unfortunately when you are running php in safe mode you cannot use set_time_limnit(0)
    //therefor I've made the timout optional.
	  
    $disable_timeout  = global_get_param( $_POST, "disable_timeout", '' );
    if ( $disable_timeout ) {
        @set_time_limit(0);
    }
    
    //郵件內容
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
				  
	//作mail內容語系轉換
	if ($lang!='utf-8')
	{
		$mail->Subject =mb_convert_encoding($subject, $lang, 'UTF-8');
		$mail->MsgHTML(mb_convert_encoding($html_message, $lang, 'UTF-8'));
		$mail->AltBody = mb_convert_encoding(strip_tags($html_message), $lang, 'UTF-8');
	}
	else
	{
		//郵件標題
		$mail->Subject = $subject;
		//郵件內容
		$mail->MsgHTML($html_message);
		$mail->AltBody = strip_tags($html_message);
	}

	if (!empty($file) && file_exists($file))
	{
		$mail->ClearAttachments();
		//設定信件編碼，大部分郵件工具都支援此編碼方式
		$mail->Encoding = 'base64';
		//傳送附檔
		$mail->AddAttachment($file, $filename); 
	}
	
	$mail->ClearAddresses();
	
	$logarr = array();
	$logarr['state']='';
	$logarr['msg']='';
	
	//收件人("Email","收件人名稱")
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

	//記錄寄送狀態
	//if($mail->Send())	
	if(true)
	{
		$logarr['state']='sus';
	}
	else
	{
		$logarr['state']='err';
		$logarr['msg']=$mail->ErrorInfo;
	}

	return $logarr;
}

/** 取得syscode的名稱 */
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
/** 取得syscode的名稱結束 */

/** 取得syscode的值 */
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
/** 取得syscode的值結束 */

/** 取得第一個syscode的值 */
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
/** 取得syscode的值結束 */

/** 產生syscode下拉選單控制項 */
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
/** 產生syscode下拉選單控制項結束 */

/** 產生syscode radio 選單控制項 */
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
/** 產生syscode radio 選單控制項結束 */

/** 產生下拉選單控制項 */
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
/** 產生下拉選單控制項結束 */

/** 利用陣列產生下拉選單控制項 */
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
/** 利用陣列產生下拉選單控制項結束 */

/** 產生日期下拉選單控制項 */
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
/** 產生日期下拉選單控制項結束 */

/** 產生syscode checkbox 選單控制項 */
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
/** 產生syscode radio 選單控制項結束 */

/** 取得xajax的js語法 */
function global_get_xajax_js()
{
	$argsarr = func_get_args();
	
	$xajax_js = 'xajax_processForm(';
	
	foreach ($argsarr as $value)
	{
		$xajax_js .= "$value,";
	}
	$xajax_js = substr($xajax_js,0,strlen($xajax_js)-1);//去掉結尾的','
	$xajax_js .= ');';

	return $xajax_js;
}
/** 取得xajax的js語法結束 */

/** 取得loading的js語法 */
function global_get_loading_js($elId, $loadimg='loading.gif')
{
	global $imgurl;
	
	$loading_js = "global_show_loading('$elId', '{$imgurl}{$loadimg}');";

	return $loading_js;
}
/** 取得loading的js語法結束 */

/** 確認檔案在路徑中 */
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
/** 確認檔案在路徑中結束 */

/** 尋找$dir目錄下的$regex檔案 */
function global_find_file($regex, $dir) 
{
    if (mb_ereg('\/$',$dir))
		$dir = substr($dir,0,strlen($dir));
	
	if (!is_dir($dir))
		return false;
	
	$matches = array();
    
    // Ok, open up the directory and prepare to start looping:
    $d = dir($dir);

    // Loop through all the files:
    while (false !== ($file = $d->read())) 
	{
        // Skip . and .., we don't want to deal with them.
        if (($file == '.') || ($file == '..')) 
		{ 
			continue; 
		}

        // If this is a directory, then:
        if (is_dir("{$dir}/{$file}")) 
		{
            // Call this function recursively to look in that subdirectory:
            $submatches = global_find_file($regex, "{$dir}/{$file}");
            // Add them to the current match list:
            $matches = array_merge($matches, $submatches);
        } 
		else 
		{
            // It's a file, so check to see if it is a match:
            if (preg_match($regex, $file)) 
			{
                // Add it to our array:
                $matches[] = "{$dir}/{$file}";
            }
        }
    }
    
    // Ok, that's it, return the array now:
    return $matches;
}
/** 尋找$dir目錄下的$regex檔案結束 */

/**
* 讀取資料夾檔案陣列
* Utility function to read the files in a directory
* @param string The file system path
* @param string A filter for the names
* @param string A file format name
*/
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

/** 檔案上傳 */
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
		
		//取得副檔名
		$fileext = strrchr($filearr['name'],'.');
		
		$cpurl = $uppath.$fpath.$fileext;		
		
		//過濾危險檔案MIME Type
		if ($filearr['type'] != 'image/gif' and $filearr['type'] != 'image/pjpeg' and $filearr['type'] != 'image/jpeg'
		 	and $filearr['type'] != 'image/x-png' and $filearr['type'] != 'image/png' and $filearr['type'] != 'image/bmp' 
		 	and $filearr['type'] != 'application/pdf' and $filearr['type'] != 'application/zip' and $filearr['type'] != 'text/plain' 
			and $filearr['type'] != 'application/msword' and $filearr['type'] != 'application/x-rar-compressed' 
			and $filearr['type'] != 'application/vnd.ms-powerpoint' and $filearr['type'] != 'application/vnd.ms-excel' 
			and $filearr['type'] != 'application/octet-stream' and $filearr['type'] != 'application/x-zip-compressed')
			return _BASEINFO_UPDATEIMG_TYPEERR_MSG;
			
		
		if ($filearr['size']>$globalConf_fileupload_limit)
			return _BASEINFO_UPDATEIMG_SIZEERR_MSG;
		
		//資料夾不存在則建立目錄
		if (!is_dir($uppath))
			mkdir($uppath, 0777);
	
		if (!move_uploaded_file($filearr['tmp_name'], $cpurl))
			return _BASEINFO_UPDATEIMG_ERR_MSG;
		  	
	}
	
	return true;
}
/** 檔案上傳結束 */

/** 檔案刪除 */
function global_file_del($regex,$uppath,$errbackurl)
{
	//尋找檔案
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
/** 檔案刪除結束 */

/** 批次刪除檔案 */
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
/** 批次刪除檔案結束 */

/** 刪除目錄及目錄內的File */
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
/** 刪除目錄及目錄內的File結束 */

/** 取得圖檔資訊，將圖檔做處理以免破圖 */
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
/** 取得圖檔資訊，將圖檔做處理以免破圖結束 */

/** 圖片上傳 */
function global_img_upd($id,$imgarr,$ext_name=null,$uppath,$errbackurl,$imageResize=null,$imageRewidth=null,$imageReheight=null,$symbol=null)
{
	global $globalConf_imgupload_limit, $lang, $globalConf_img_s_width, $globalConf_img_s_height, $globalConf_img_b_width, $globalConf_img_b_height, $globalConf_img_width, $globalConf_img_height,$arrLangCommon;

	if($imgarr['size']!=0)
	{
		if (!mb_ereg('\/$',$uppath))
			$uppath = "{$uppath}/";

		//���圖
		$fpath = "{$id}{$ext_name}.jpg";
		//小圖
		$fspath = "{$id}{$ext_name}_s.jpg";	
		//大圖
		$fbpath = "{$id}{$ext_name}_b.jpg";
		//特���結尾
		$fdpath = "{$id}{$ext_name}{$symbol}.jpg";
		
		//原圖
		$cpurl = $uppath.$fpath;	
		//���圖
		$cpsurl = $uppath.$fspath;
		//大圖
		$cpburl = $uppath.$fbpath;		
		//特殊結尾
		$cpdurl = $uppath.$fdpath;	

		//���斷圖片類型
		if ($imgarr['type'] != 'image/gif' and $imgarr['type'] != 'image/pjpeg' and $imgarr['type'] != 'image/jpeg' and $imgarr['type'] != 'image/x-png' and $imgarr['type'] != 'image/png' and $imgarr['type'] != 'image/bmp')
			showMsgRedirect($arrLangCommon['_IMGFMTERR'],$errbackurl);
		
		//圖片大小過大
		if ($imgarr['size']>$globalConf_imgupload_limit)
			showMsgRedirect($arrLangCommon['_IMGTOBIG'],$errbackurl);
		
		//資料夾不存在則建立目錄
		if (!is_dir($uppath))
			mkdir($uppath, 0777);

		//取得副檔名
		$fileext = strrchr($imgarr['name'],'.');
		$cpurl = $uppath.$id.$ext_name.$symbol.$fileext;
		
		if (!move_uploaded_file($imgarr['tmp_name'], $cpurl))
			showMsgRedirect($arrLangCommon['_IMGUPDERR'], $errbackurl);  
		/*	
		switch ($imageResize)
		{
			case 'sb_image':
				//產生小圖
				$resizeimage = new Resizeimage($cpsurl, $imgarr['tmp_name'], $globalConf_img_s_width, $globalConf_img_s_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				//產生大圖
				$resizeimage = new Resizeimage($cpburl, $imgarr['tmp_name'], $globalConf_img_b_width, $globalConf_img_b_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				//原圖
				$resizeimage = new Resizeimage($cpurl, $imgarr['tmp_name'], $globalConf_img_width, $globalConf_img_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				break;
			case 's_image':
				//產生小圖
				$resizeimage = new Resizeimage($cpsurl, $imgarr['tmp_name'], $globalConf_img_s_width, $globalConf_img_s_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				//原圖
				$resizeimage = new Resizeimage($cpurl, $imgarr['tmp_name'], $globalConf_img_width, $globalConf_img_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl);  
				break;
			case 'b_image':
				//產生大圖
				$resizeimage = new Resizeimage($cpburl, $imgarr['tmp_name'], $globalConf_img_b_width, $globalConf_img_b_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				//原圖
				$resizeimage = new Resizeimage($cpurl, $imgarr['tmp_name'], $globalConf_img_width, $globalConf_img_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				break;
			case 'define_image':
				//產生自訂義大小的圖片
				$resizeimage = new Resizeimage($cpdurl, $imgarr['tmp_name'], $imageRewidth, $imageReheight, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				//原圖
				$resizeimage = new Resizeimage($cpurl, $imgarr['tmp_name'], $globalConf_img_width, $globalConf_img_height, '0', '0');
				if (!$resizeimage->status)
					showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				break;
			default:
				if (!empty($imageRewidth) && !empty($imageReheight))//產生自訂義大小的圖片
				{
					$resizeimage = new resizeimage($cpurl, $imgarr['tmp_name'], $imageRewidth, $imageReheight, '0', '0');
					if (!$resizeimage->status)
						showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				}
				else
				{
					//原圖
					$resizeimage = new Resizeimage($cpurl, $imgarr['tmp_name'], $globalConf_img_width, $globalConf_img_height, '0', '0');
					if (!$resizeimage->status)
						showMsgRedirect($arrLangCommon['_IMGUPDERR'],$errbackurl); 
				}
				break;  
		}
		*/	
	}
	
	return true;
}
/** 圖片上傳結束 */

//刪除圖片
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

/** 批次目錄資料轉移 */
function global_dir_trans_all($db,$id,$tablename,$level,$errurl)
{
	global $arrLangCommon;
	
	$gettime = $_SERVER['REQUEST_TIME'];
	
	$presql = "select id,datatype from $tablename  where belongid=$id";
	$rs = $db->Execute($presql);
	$tolevel = $level + 1;

	if ($rs)
	{
		while (!$rs->EOF)
		{
			$toid = intval( $rs->fields['id'] );
			$sql = "update $tablename set treelevel=$tolevel, mtime='$gettime', muser='".$_SESSION['admin']['uid']."' where id=$toid ";

			if(!$db->Execute($sql) || !$db->Affected_Rows())//更新失敗
				showMsgRedirect($arrLangCommon['_MSG_UPD_ERR'],$errurl);
			if ( $rs->fields['datatype'] == 'dir' )
				global_dir_trans_all($db,$toid,$tablename,$tolevel,$errurl);	
			
			$rs->MoveNext();
		}	
		$rs->Close();
	}
	
	return true;
}
/** 批次資料轉移結束 */

/** 批次刪除目錄及目錄內的資料 */
function global_dir_del_all($db,$tablename,$id,$filearr=null,$errurl='index.php')
{
	global $arrLangCommon;
	
	$sql = "select id, datatype from $tablename where belongid='$id' ";
	$rs = $db->Execute($sql);
	if ($rs)
	{
		while (!$rs->EOF)
		{
			if ( $rs->fields['datatype'] == 'dir' )
			{
				global_dir_del_all($db,$tablename,$rs->fields['id'],$filearr)	;
			}	
			else
			{			
				if (is_array($filearr) && !empty($filearr))
				{
					foreach ($filearr as $key => $value)
					{
						/*
						if ($filearr[$key]['type']=='img')
							global_img_del($rs->fields['id'],$filearr[$key]['name'],$filearr[$key]['path'],$errurl);
						else if ($filearr[$key]['type']=='file')
							global_file_del_all($rs->fields['id'],$filearr[$key]['name'],$filearr[$key]['path'],$errurl);
						*/
						global_file_del('/'.$rs->fields['id'].$filearr[$key]['name'].'/',$filearr[$key]['path'],$errurl);//刪除相關檔案
					}		
				}	
			}
			
			$sql = "delete from $tablename  where id=".$rs->fields['id'];
			$db->Execute($sql);
			if(!$db->Affected_Rows())//資料刪除失敗
				showMsgRedirect( $arrLangCommon['_MSG_DEL_ERR'], $errurl);
			
			$rs->MoveNext();
		}
		$rs->Close();
	}	
	return true;
}
/** 批次資料刪除結束 */

/**
* Generates an HTML select list
* @param array An array of objects
* @param string The value of the HTML name attribute
* @param string Additional HTML attributes for the <select> tag
* @param string The name of the object variable for the option value
* @param string The name of the object variable for the option text
* @param mixed The key that is selected
* @returns string HTML for the select list
*/
function global_select_list( &$arr, $tag_name, $tag_attribs, $key, $text, $selected=NULL ) 
{
	reset( $arr );
	$html = "\n<select name=\"$tag_name\" $tag_attribs>";
	for ($i=0, $n=count( $arr ); $i < $n; $i++ ) 
	{
		$k = $arr[$i]->$key;
		$t = $arr[$i]->$text;
		$id = @$arr[$i]->id;

		$extra = '';
		$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
		if (is_array( $selected )) 
		{
			foreach ($selected as $obj) 
			{
				$k2 = $obj->$key;
				if ($k == $k2) 
				{
					$extra .= " selected=\"selected\"";
					break;
				}
			}
		} 
		else 
		{
			$extra .= ($k == $selected ? " selected=\"selected\"" : '');
		}
		$html .= "\n\t<option value=\"".$k."\"$extra>" . $t . "</option>";
	}
	$html .= "\n</select>\n";
	return $html;
}


/**
* Generates an HTML radio list
* @param array An array of objects
* @param string The value of the HTML name attribute
* @param string Additional HTML attributes for the <select> tag
* @param mixed The key that is selected
* @param string The name of the object variable for the option value
* @param string The name of the object variable for the option text
* @returns string HTML for the select list
*/
function global_radio_list( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
	reset( $arr );
	$html = "";
	for ($i=0, $n=count( $arr ); $i < $n; $i++ ) 
	{
		$k = $arr[$i]->$key;
		$t = $arr[$i]->$text;
		$id = @$arr[$i]->id;

		$extra = '';
		$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
		if (is_array( $selected )) 
		{
			foreach ($selected as $obj) 
			{
				$k2 = $obj->$key;
				if ($k == $k2) 
				{
					$extra .= " selected=\"selected\"";
					break;
				}
			}
		} 
		else 
		{
			$extra .= ($k == $selected ? " checked=\"checked\"" : '');
		}
		$html .= "\n\t<input type=\"radio\" name=\"$tag_name\" value=\"{$k}\"{$extra} {$tag_attribs} />$t";
	}
	$html .= "\n";
	return $html;
}

/**
* 陣列轉物件
* Copy the named array content into the object as properties
* only existing properties of object are filled. when undefined in hash, properties wont be deleted
* @param array the input array
* @param obj byref the object to fill of any class
* @param string
* @param boolean
*/
function global_array_to_object( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) 
{
	if (!is_array( $array ) || !is_object( $obj )) 
	{
		return (false);
	}

	foreach (get_object_vars($obj) as $k => $v) 
	{
		if( substr( $k, 0, 1 ) != '_' ) // internal attributes of an object are ignored
		{			
			if (strpos( $ignore, $k) === false) 
			{
				if ($prefix) 
				{
					$ak = $prefix . $k;
				} 
				else 
				{
					$ak = $k;
				}
				if (isset($array[$ak])) 
				{
					$obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? global_stripslashes( $array[$k] ) : $array[$k];
				}
			}
		}
	}

	return true;
}

//將物件轉成陣列
function global_object_to_array($p_obj)
{
	$retarray = null;
	if(is_object($p_obj))
	{
		$retarray = array();
		foreach (get_object_vars($p_obj) as $k => $v)
		{
			if(is_object($v))
			$retarray[$k] = rsObjectToArray($v);
			else
			$retarray[$k] = $v;
		}
	}
	return $retarray;
}

/**
* Makes a variable safe to display in forms
*
* Object parameters that are non-string, array, object or start with underscore
* will be converted
* @param object An object to be parsed
* @param int The optional quote style for the htmlspecialchars function
* @param string|array An optional single field name or array of field names not
*                     to be parsed (eg, for a textarea)
*/
function global_make_html_safe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
	if (is_object( $mixed )) 
	{
		foreach (get_object_vars( $mixed ) as $k => $v) 
		{
			if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' )
				continue;
			if (is_string( $exclude_keys ) && $k == $exclude_keys)
				continue;
			else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys ))
				continue;

			$mixed->$k = htmlspecialchars( $v, $quote_style );
		}
	}
}


/**
* js網頁的執行開頭
* js message page show
*/
function global_js_headder()
{
	global $globalConf_default_charset;
	
	$headstr = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \n" 
		." \"http://www.w3.org/TR/html4/loose.dtd\"><html>\n"
        ."<head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=$globalConf_default_charset\">\n"
        ."<title>Javascript Message</title>\n"
        ."</head><body>\n"
		."<script type=\"text/javascript\">\n"
		."<!--\n";
	echo $headstr;
}

//顯示js Alert
function global_js_alert_back($str)
{
	$headstr = "alert(\"$str\"); window.history.go(-1);\n";
	
	return $headstr;
}
				
//js網頁結尾
function global_js_tailer()
{
	echo '//-->';
	echo "</script>";
	echo "</body></html>";
	exit();
}

function global_js_tailer_goon()
{
	$headstr = "</body></html>";
	return $headstr;
}

/**
* 建立一組亂數密碼
* Random password generator
* @return password
*/
function global_make_password() 
{
	$salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$len = strlen($salt);
	$makepass = '';
	mt_srand(10000000*(double)microtime());
	for ($i = 0; $i < 8; $i++)
		$makepass .= $salt[mt_rand(0,$len - 1)];
	return $makepass;
}

if (!function_exists('html_entity_decode')) 
{
	/**
	* html_entity_decode function for backward compatability in PHP
	* @param string
	* @param string
	*/
	function html_entity_decode ($string, $opt = ENT_COMPAT) 
	{

		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);

		if ($opt & 1) // Translating single quotes
		{ 
			// Add single quote to translation table;
			// doesn't appear to be there by default
			$trans_tbl["&apos;"] = "'";
		}

		if (!($opt & 2)) // Not translating double quotes
		{ 
			// Remove double quote from translation table
			unset($trans_tbl["&quot;"]);
		}

		return strtr ($string, $trans_tbl);
	}
}

/**
* Sorts an Array of objects
*/
function SortArrayObjects_cmp( &$a, &$b ) 
{
	global $csort_cmp;

	if ( $a->$csort_cmp['key'] > $b->$csort_cmp['key'] ) 
	{
		return $csort_cmp['direction'];
	}

	if ( $a->$csort_cmp['key'] < $b->$csort_cmp['key'] ) 
	{
		return -1 * $csort_cmp['direction'];
	}

	return 0;
}

/**
* Sorts an Array of objects
*/
function SortArrayObjects( &$a, $k, $sort_direction=1 ) 
{
	global $csort_cmp;

	$csort_cmp = array
	(
		'key'          => $k,
		'direction'    => $sort_direction
	);

	usort( $a, 'SortArrayObjects_cmp' );

	unset( $csort_cmp );
}

/** 數字轉中文數字 */
function global_num_to_cht($i,$s=0)
{
    $c_digit_min = array('零','十','百','千','万','亿','兆');
    $c_num_min = array('零','一','二','三','四','五','六','七','八','九','十');
    
    $c_digit_max = array('零','拾','佰','仟','万','亿','兆');
    $c_num_max = array('零','壹','贰','叁','肆','伍','陆','柒','捌','玖','拾');
    
    if($s==1){
        $c_digit = $c_digit_max;
        $c_num = $c_num_max;
    }
    else{
        $c_digit = $c_digit_min;
        $c_num = $c_num_min;
    }
    
    if($i<0)
        return "负".global_num_to_cht(-$i);
    if ($i < 11)
        return $c_num[$i];
    if ($i < 20)
        return $c_num[1].$c_digit[1] . $c_num[$i - 10];
    if ($i < 100) {
        if ($i % 10)
            return $c_num[$i / 10] . $c_digit[1] . $c_num[$i % 10];
        else
            return $c_num[$i / 10] . $c_digit[1];
    }
    if ($i < 1000) {
        if ($i % 100 == 0)
            return $c_num[$i / 100] . $c_digit[2];
        else if ($i % 100 < 10)
            return $c_num[$i / 100] . $c_digit[2] . $c_num[0] . global_num_to_cht($i % 100);
        else if ($i % 100 < 10)
            return $c_num[$i / 100] . $c_digit[2] . $c_num[1] . global_num_to_cht($i % 100);
        else
            return $c_num[$i / 100] . $c_digit[2] . global_num_to_cht($i % 100);
    }
    if ($i < 10000) {
        if ($i % 1000 == 0)
            return $c_num[$i / 1000] . $c_digit[3];
        else if ($i % 1000 < 100)
            return $c_num[$i / 1000] . $c_digit[3] . $c_num[0] . global_num_to_cht($i % 1000);
        else 
            return $c_num[$i / 1000] . $c_digit[3] . global_num_to_cht($i % 1000);
    }
    if ($i < 100000000) {
        if ($i % 10000 == 0)
            return global_num_to_cht($i / 10000) . $c_digit[4];
        else if ($i % 10000 < 1000)
            return global_num_to_cht($i / 10000) . $c_digit[4] . $c_num[0] . global_num_to_cht($i % 10000);
        else
            return global_num_to_cht($i / 10000) . $c_digit[4] . global_num_to_cht($i % 10000);
    }
    if ($i < 1000000000000) {
        if ($i % 100000000 == 0)
            return global_num_to_cht($i / 100000000) . $c_digit[5];
        else if ($i % 100000000 < 1000000)
            return global_num_to_cht($i / 100000000) . $c_digit[5] . $c_num[0] . global_num_to_cht($i % 100000000);
        else 
            return global_num_to_cht($i / 100000000) . $c_digit[5] . global_num_to_cht($i % 100000000);
    }
    if ($i % 1000000000000 == 0)
        return global_num_to_cht($i / 1000000000000) . $c_digit[6];
    else if ($i % 1000000000000 < 100000000)
        return global_num_to_cht($i / 1000000000000) . $c_digit[6] . $c_num[0] . global_num_to_cht($i % 1000000000000);
    else
        return global_num_to_cht($i / 1000000000000) . $c_digit[6] . global_num_to_cht($i % 1000000000000);
}
/** 數字轉中文數字結束 */

/** 輸出內容文字 */
function global_show_cont($content=null,$showtype='txt',$width=null,$class='')
{
	global $lang;

	if (empty($width))
		$width = $globalConf_wordwrap;

	if ($showtype=='txt')
	{
		$content = strip_tags($content);
		//英文版不需要此語法
		if ($lang != 'en')
		{
			$content = str_replace(' ','',$content);	
		}

		$content = nl2br($content);
		if (!empty($width))
			$content = global_wordwrap_utf8($content,$width);	
		return $content;
	}
	else
	{
		//套入css
		if (!empty($class))
			$class = " class=\"$class\"";
		$content = str_replace('<div>','<div'.$class.'>',$content);
		$content = str_replace('</div>','</div>',$content);
		return $content;		
	}
}
/** 輸出內容文字結束 */

/** 換行function */
function global_wordwrap_utf8($str, $width=60, $break="\n")
{
	//global $iweb_wordwrap;
	//if (!empty($iweb_wordwrap))
	//$width = $iweb_wordwrap;
	
	$arrstr = explode('\n',$str);	
	foreach ($arrstr as $key => $value)
	{
		$s='';
   		for($i=0; $i<mb_strlen($value,'UTF-8'); $i+=$width)
   		{
    	   	$s .= mb_substr($value, $i, $width, 'UTF-8').$break;    
   		}
    	
   		$s = mb_substr($s, 0, mb_strlen($s,'UTF-8')-mb_strlen($break,'UTF-8')+1, 'UTF-8');
   		$arrstr[$key] = $s;
	}   	   
   	
   	$str = implode("\n", $arrstr);
   	return $str;
}
/** 換行function結束 */

/**
* 清除文字的所有樣式及javascript
* Cleans text of all formating and scripting code
*/
function global_clean_text ( &$text ) 
{
	$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
	$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
	$text = preg_replace( '/<!--.+?-->/', '', $text );
	$text = preg_replace( '/{.+?}/', '', $text );
	$text = preg_replace( '/&nbsp;/', ' ', $text );
	$text = preg_replace( '/&amp;/', ' ', $text );
	$text = preg_replace( '/&quot;/', ' ', $text );
	$text = strip_tags( $text );
	$text = htmlspecialchars( $text );
	return $text;
}

/**
* 去掉反斜線字元
* Strip slashes from strings or arrays of strings
* @param value the input string or array
*/
function global_stripslashes(&$value)
{
	$ret = '';
    if (is_string($value)) 
	{
		$ret = stripslashes($value);
	} 
	else 
	{
	    if (is_array($value)) 
		{
	        $ret = array();
	        while (list($key,$val) = each($value)) 
			{
	            $ret[$key] = global_stripslashes($val);
	        } // while
	    } 
		else 
		{
	        $ret = $value;
		} // if
	} // if
    return $ret;
} 

/** 切割utf8字元 */
function global_utf8_substr($str,$from,$len)
{
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}

/** 在字詞邊界處截斷字串 */
function global_truncate_text_nicely($string, $start, $max, $moretext=null, $charset=null, $cope='strip_tags')
{
	global $lang,$globalConf_charset,$globalConf_more_text;
	
	if (empty($moretext))
		$moretext = $globalConf_more_text;
	
	if (empty($charset))
		$charset = $globalConf_charset;
	
	//先對字串作處理
	switch ($cope)
	{
		case 'strip_tags':
			$string = strip_tags($string);
			$string = str_replace('&nbsp;','',$string);
			//英文版不需要此語法
			if ($lang!='en')
				$string = str_replace(' ','',$string);
			break;
		case 'nl2br';
			$string = nl2br($string);
			break;
	}

	if ($lang!='en')
		return global_truncate_text_cht($string, $start, $max, $moretext, $charset);
	else
		return global_truncate_text_en($string, $start, $max, $moretext, $cope);
}
/** 在字詞邊界處截斷字串結束 */

/** 在字詞邊界處截斷字串，可處理中文字 */
function global_truncate_text_cht($string, $start, $max, $moretext, $charset='utf-8')
{
	
	//字串超過所設定的最大長度才會進行處理
	if (strlen($string) > $max)
	{
		//修正$max，減去省略符號的長度以騰出更多空間
		$max -= strlen($moretext);
		
		//只擷取字串合適的部份
		$string = mb_substr ($string, $start, $max , $charset);
		
		//將省略符號加到後面
		$string .= $moretext;
	}
	
	//不管字串有沒有變動，都將它傳回
	return $string;
}
/** 在字詞邊界處截斷字串，可處理中文字結束 */

/** 在字詞邊界處截斷字串，只能處理英文字 */
function global_truncate_text_en($string, $start, $max, $moretext)
{
	
	//字串超過所設定的���大長度才會進行處理
	if (strlen($string) > $max)
	{
		//修正$max，減去省略符號的長度以騰出更多空間
		$max -= strlen($moretext);
		
		//只擷取字串合適的部份
		$string = strrev(strstr(strrev(substr($string, $start, $max)),' '));
		
		//將省略符號加到後面
		$string .= $moretext;
	}
	
	//不管字串有沒有變動，都將它傳回
	return $string;
}
/** 在字詞邊界處截斷字串，只能處理英文字結束 */

/** 將計數值加1 */
function global_add_click($tablename=null,$id=null)
{
	global $db;
	if (!empty($tablename) && !empty($id))
	{
		$sql = "update $tablename set click=click+1 where id=$id";
		$result = $db->Execute($sql);
	}
	return $result;
}
/** 將計數值加1結束 */

/** 取得記錄筆數 */
function global_get_record_count(&$db, $sqlcnt)
{
	$rs = $db->GetOne($sqlcnt);
	return $rs;//取得記錄數
}
/** 取得記錄筆數結束 */

/** 替換網址query參數 */
function global_querystr_replace($search='', $replace='', $url='')
{
	$querystr = substr($url , strpos($url, "&$search=")+1);
	if (strpos($querystr, '&')>0)
		$querystr = substr($querystr , 0, strpos($querystr, '&'));
	else
		$querystr = substr($querystr , 0);
	$querystr = substr($querystr , strpos($querystr, '=')+1);
	
	if (!empty($replace))
		return str_replace("$search=$querystr", "$search=$replace", $url);
	else
		return str_replace("&$search=$querystr", "", $url);
}
/** 替換網址query參數結束 */

/** 從網址中取得query參數值 */
function global_get_querystr($search='', $url='')
{
	//取得查詢字串
	$GETpart = substr($url, strrpos($url,'?'));
	if ($GETpart==$url)
		 return '';
	
	if (!mb_ereg('\?'.$search,$GETpart) && !mb_ereg('\&'.$search,$GETpart))
		return '';

	$querystr = substr($GETpart , strpos($GETpart, "&$search=")+1);

	if (strpos($querystr,'&'))
		$querystr = substr($querystr , 0, strpos($querystr, '&'));
	$querystr = substr($querystr , strpos($querystr, '=')+1);
	
	return $querystr;
}
/** 從網址中取得query參數值 */

/** 取得路徑層次 */
function global_get_path_prefix()
{
	global $globalConf_live_site;
	
	$url = str_replace($globalConf_live_site, '', 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']);

	$strcnt = substr_count($url, '/');

	for ($i=0; $i<$strcnt; $i++)
	{
		$prefix .= '../';
	}
	return $prefix;
}
/** 取得路徑層次結束 */

/** 替換網頁中的路徑 */
function global_replace_path($content, $original_path, $real_path) 
{
	$real_path = preg_replace("/\/$/", "", $real_path);//去掉結尾反斜線
	$original_path = preg_replace("/\/$/", "", $original_path);//去掉結尾反斜線
	$original_path = str_replace('.', '\.', $original_path);
	$original_path = str_replace('/', '\/', $original_path);

	if ($real_path == '') 
	{
		return $content;
	}

	$new_content = preg_replace('/"'.$original_path.'\//', '"'.$real_path.'/', $content);
	$new_content = preg_replace('/\''.$original_path.'\//', '\''.$real_path.'/', $new_content);
	
	return $new_content;
	
}
/** 替換網頁中的路徑結束 */

/** 去除HTML語法 */
function global_strip_tags($str)
{
	$search = array("'<script[^>]*?>.*?</script>'si",//刪除javascript   
					"'<[\/\!]*?[^<>]*?>'si",//刪除HTML標記
					"'([\r\n])[\s]+'",//刪除空白字符   
					"'&(quot|#34);'i",//替换HTML實體 
					"'&(amp|#38);'i",   
					"'&(lt|#60);'i",   
					"'&(gt|#62);'i",   
					"'&(nbsp|#160);'i",   
					"'&(iexcl|#161);'i",   
					"'&(cent|#162);'i",   
					"'&(pound|#163);'i",   
					"'&(copy|#169);'i",   
					"'&#(\d+);'e");
	
	$replace = array("",
					  "",   
					  "",   
					  "",  
					  "",   
					  "",   
					  "",  
					  "   ",   
					  "",   
					  "",   
					  "",   
					  "",  
					  "");   
	
	$str = preg_replace($search, $replace, $str); 

	$str = strip_tags( $str );
	$str = htmlspecialchars( $str );
	return $str; 
}
/** 去除HTML語法結束 */

/** 處理POST資料的送出 */
function global_http_post($url, $post)
{
	//初始化一個cURL的工作期
	$c = curl_init();
	
	//設定我們欲聯繫的URL:
	curl_setopt($c, CURLOPT_URL, $url);
	
	//再來，告訴cURL我們要進行POST，並給它資料
	curl_setopt($c, CURLOPT_POST, true);
	curl_setopt($c, CURLOPT_POSTFIELDS, $post);
	
	//告訴cURL傳回頁面結果，而不是直接輸出到頁面上:
	//curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	
	//最後，執行這項請求，並傳回我們收到的資料：
	curl_exec($c);
	curl_close($c);
	
}
/** 處理POST資料的送出結束 */

/** 隱藏文字 */
function global_mask_text($str, $start, $replace_text = '*', $charset='utf-8')
{
	global $global_mask_start;
	
	if (empty($start))
		$start = $global_mask_start;
	elseif ($start=='all')
		$start = 0;
		
	
	//取出要隱藏的部份
	$mask_text = mb_substr ($str, $start, mb_strlen($str, $charset) , $charset);

	//取出要隱藏的部份
	$unmask_text = mb_substr ($str, 0, $start , $charset);
	
	$n = mb_strlen($mask_text, $charset);
	
	for ($i = 0; $i < $n; $i++)
	{
		$masked_text .= $replace_text;
	}
	$masked_text = $unmask_text.$masked_text;
	return $masked_text;
}
/** 隱藏文字結束 */

/** 隱藏聯絡資訊 */
function global_mask_contactinfo($show_contactinfo, $text, $replace_text = '*')
{
	if ($show_contactinfo=='false')
	{
		//數字全形轉半形
		$masked_text = str_replace('０','0',$text);
		$masked_text = str_replace('１','1',$masked_text);
		$masked_text = str_replace('２','2',$masked_text);
		$masked_text = str_replace('３','3',$masked_text);
		$masked_text = str_replace('４','4',$masked_text);
		$masked_text = str_replace('５','5',$masked_text);
		$masked_text = str_replace('６','6',$masked_text);
		$masked_text = str_replace('７','7',$masked_text);
		$masked_text = str_replace('８','8',$masked_text);
		$masked_text = str_replace('９','9',$masked_text);
		
		//遮蔽Email
		$masked_text = mb_ereg_replace('[a-z0-9_-][a-z0-9._-]+@([a-z0-9][a-z0-9-]*\.)+[a-z]{2,6}',str_repeat($replace_text,5).'@'.str_repeat($replace_text,5).'.'.str_repeat($replace_text,3),$masked_text);
		//遮蔽網址
		$masked_text = mb_ereg_replace('([_0-9a-z-]+\.)+([0-9a-z-]+\.)+[a-z]{2,3}',str_repeat($replace_text,3).'.'.str_repeat($replace_text,5).'.'.str_repeat($replace_text,3),$masked_text);
		//遮蔽電話
		$masked_text = mb_ereg_replace('[0-9]{4}',str_repeat($replace_text,4),$masked_text);
		$masked_text = mb_ereg_replace('([0-9][ 　]){3}[0-9]',str_repeat($replace_text,4),$masked_text);
	}
	else
		$masked_text = $text;
	return $masked_text;
}
/** 隱藏聯絡資訊結束 */

/** 將關鍵字標亮處理 */
function global_keyword_highlighted($arrKword, $str, $class='wrongfont', $append='')
{
	$highlighted = $str;
	
	if (!empty($class))
		$class = " class=\"$class\"";
	
	if (is_array($arrKword) && sizeof($arrKword)>0)//陣列
	{
		foreach ($arrKword as $kword)
		{
			$highlighted = str_replace($kword,"<strong $class $append>".$kword.'</strong>',$highlighted);
			if ($kword != strtolower($kword))
				$highlighted = str_replace(strtolower($kword),"<strong $class $append>".strtolower($kword).'</strong>',$highlighted);
			if ($kword != strtoupper($kword))
				$highlighted = str_replace(strtoupper($kword),"<strong $class $append>".strtoupper($kword).'</strong>',$highlighted);
			if ($kword != ucwords(strtolower($kword)))
				$highlighted = str_replace(ucwords(strtolower($kword)),"<strong $class $append>".ucwords(strtolower($kword)).'</strong>',$highlighted);
		}
	}
	else//非陣列
	{
		$kword = $arrKword;
		
		$highlighted = str_replace($kword,"<strong $class $append>".$kword.'</strong>',$highlighted);
		if ($kword != strtolower($kword))
			$highlighted = str_replace(strtolower($kword),"<strong $class $append>".strtolower($kword).'</strong>',$highlighted);
		if ($kword != strtoupper($kword))
			$highlighted = str_replace(strtoupper($kword),"<strong $class $append>".strtoupper($kword).'</strong>',$highlighted);
		if ($kword != ucwords(strtolower($kword)))
			$highlighted = str_replace(ucwords(strtolower($kword)),"<strong $class $append>".ucwords(strtolower($kword)).'</strong>',$highlighted);
	}
	return $highlighted;
}
/** 將關鍵字標亮處理結束 */

/** 輸出欄位名稱 */
function global_get_varname($tablename, $fieldname='varname', $codevalue='varname', $action='array')
{
	global $tpl, $db;
	
	//欄位資料
	$arrVarname = array();
	
	$sql="select * from $tablename";

	$rs = $db->Execute( $sql );
	if ($rs)
	{
		$arrVarname["{$fieldname}01"] = $rs->fields['show_var01'];
  		$arrVarname["{$fieldname}02"] = $rs->fields['show_var02'];
  		$arrVarname["{$fieldname}03"] = $rs->fields['show_var03'];
  		$arrVarname["{$fieldname}04"] = $rs->fields['show_var04'];
  		$arrVarname["{$fieldname}05"] = $rs->fields['show_var05'];
  		$arrVarname["{$fieldname}06"] = $rs->fields['show_var06'];
  		$arrVarname["{$fieldname}07"] = $rs->fields['show_var07'];
  		$arrVarname["{$fieldname}08"] = $rs->fields['show_var08'];
  		$arrVarname["{$fieldname}09"] = $rs->fields['show_var09'];
  		$arrVarname["{$fieldname}10"] = $rs->fields['show_var10'];
		$rs->Close();
	} 
	//預設資料
	$sql="select CodeValue, CodeName from syscode where CodeKind='$tablename'";
	$rs = $db->Execute( $sql );
	if ($rs)
	{
		while (!$rs->EOF)
		{
			switch ($rs->fields['CodeValue'])
			{
				case "{$codevalue}01":
					$str01 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}02":
					$str02 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}03":
					$str03 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}04":
					$str04 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}05":
					$str05 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}06":
					$str06 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}07":
					$str07 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}08":
					$str08 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}09":
					$str09 = $rs->fields['CodeName'];
					break;
				case "{$codevalue}10":
					$str10 = $rs->fields['CodeName'];
					break;
			}
			$rs->MoveNext();
		}
		$rs->Close();
	}
	if (empty($arrVarname["{$fieldname}01"])){$arrVarname["{$fieldname}01"] =$str01;}
	if (empty($arrVarname["{$fieldname}02"])){$arrVarname["{$fieldname}02"] =$str02;}
	if (empty($arrVarname["{$fieldname}03"])){$arrVarname["{$fieldname}03"] =$str03;}
	if (empty($arrVarname["{$fieldname}04"])){$arrVarname["{$fieldname}04"] =$str04;}
	if (empty($arrVarname["{$fieldname}05"])){$arrVarname["{$fieldname}05"] =$str05;}
	if (empty($arrVarname["{$fieldname}06"])){$arrVarname["{$fieldname}06"] =$str06;}
	if (empty($arrVarname["{$fieldname}07"])){$arrVarname["{$fieldname}07"] =$str07;}
	if (empty($arrVarname["{$fieldname}08"])){$arrVarname["{$fieldname}08"] =$str08;}
	if (empty($arrVarname["{$fieldname}09"])){$arrVarname["{$fieldname}09"] =$str09;}
	if (empty($arrVarname["{$fieldname}10"])){$arrVarname["{$fieldname}10"] =$str10;}

	if ($action=='assign')//輸出變數
	{
		$tpl->assignGlobal($arrVarname);
	}
	else
		return $arrVarname;
}
/** 輸出欄位名稱結束 */

/** 將BBCode轉碼為HTML語法 **/
function global_bbcode_to_html($str)
{
	$a = array(
	"/\[i\](.*?)\[\/i\]/is",
	"/\[b\](.*?)\[\/b\]/is",
	"/\[u\](.*?)\[\/u\]/is",
	"/\[img\](.*?)\[\/img\]/is",
	"/\[url=(.*?)\](.*?)\[\/url\]/is",
	"/\[size=(.*?)\](.*?)\[\/size\]/is",
	"/\[color=(.*?)\](.*?)\[\/color\]/is",
	);
	$b = array(
	"<i>$1</i>",
	"<b>$1</b>",
	"<u>$1</u>",
	"<img src=\"$1\" />",
	"<a href=\"$1\" target=\"_blank\">$2</a>",
	'<font size=$1>$2</font>',
	'<font color=$1>$2</font>',
	);
	$str = preg_replace($a, $b, $str);
	$str = nl2br($str);
	return $str;
	/*
	// 定義一些直接轉換對照
    $trans = array( 'b' => 'b', 'i' => 'i', 'u' => 'u', 'code' => 'pre' );
    
    // 利用正規來尋找bbcode，然後梭巡字串
    while (preg_match('|\[([a-z]+)\](.*?)\[/\1\]|', 
            $str, $r, PREG_OFFSET_CAPTURE)) {
        // [0][0]含有完全符合的字串
        // [0][1] 含有該字串的徧移位置
        // [1][0] 第二個符合的文字，也就是標記名稱
        // [2][0] 第三個符合的文字，也就是標記內容

        // 依照標記來實作HTML
        // 如果為直接轉換的標記
        if (isset($trans[$r[1][0]])) {
            // 轉換為HTML標籤
            $replace = "<{$trans[$r[1][0]]}>{$r[2][0]}</{$trans[$r[1][0]]}>";
        }
        // 特殊狀況: quote(引號)
        elseif ($r[1][0] == 'quote') {
            // 用兩個標籤來轉換:
            $replace = "<blockquote><i>{$r[2][0]}</i></blockquote>";
        }
        // 特殊狀況: img(影像)
        elseif ($r[1][0] == 'img') {
            // 建立一個影像標籤
            $replace = "<img src=\"{$r[2][0]}\" />";
        }
		// 特殊狀況: url(連結)
        elseif ($r[1][0] == 'url') {
            // 建立一個連結標籤
            $replace = "<a href=\"{$r[2][0]}\" />";
        } else {
            // 如果發現其它標記，視為不合法，將它去除
            $replace = $r[2][0];
        }
        
        // 開始真正的替換工作
        $str = substr_replace($str, $replace, $r[0][1], 
            strlen($r[0][0]));
    }
    
    // 將雙重歸位字元轉成段落換行，讓它適用於window的伺服器
    $str = str_replace("\r\n\r\n", '</p><p>', $str);
    // 和 unix:
    $str = str_replace("\n\n", '</p><p>', $str);

    return "<p>{$str}</p>";
	*/
}
/** 將BBCode轉碼為HTML語法結束 **/ 
/** 取得網域 **/
function global_get_domain($url)
{
	$url = str_replace('http://', '', $url);
	if (strpos($url, '/')>0)
		$url = substr($url, 0, strpos($url, '/'));
	return $url;
}
/** 取得網域結束 **/

/** 字串處理 **/
function global_str_handler($str)
{
	$arr = array();
	//$arr["\r"] = '';
	//$arr["\n"] = '';
	//$arr['/'] = '／';
	$arr['%'] = '％';
	
	foreach ($arr as $key=>$value)
	{
		$str = str_replace($key, $value, $str);	
	}
	return $str;
}
/** 字串處理結束 **/

/** 共用函數 FOR SchoolHomeAPI **/

/** 產生認證碼(金鑰) **/
function generateVerifyCode($vr)
{	
	global $globalConf_token;
	
	$token = $globalConf_token;
	
	switch($vr % sizeof($token))
	{
		case "0":
			$str = md5($token[0].date('Y/m/d'));
			break;
		case "1":
			$str = md5($token[1].date('Y').date('Y/m/d'));
			break;
		case "2":
			$str = md5($token[2].date('m').date('Y/m/d'));
			break;
		case "3":
			$str = md5($token[3].date('d').date('Y/m/d'));
			break;
		case "4":
			$str = md5($token[4].date('Y/m/d').date('Y/m/d'));
			break;
		default:
			$str = "ERROR";
			break;
	}
	
	return $str;
}
/** 產生認證碼(金鑰) 結束 **/

/** 產生會員唯一碼 **/
function generateUniqueCode($mc = null , $parentid = null)
{
	global $globalConf_encrypt_1,$globalConf_encrypt_2;
	
	return md5($globalConf_encrypt_1.$mc.$parentid.$globalConf_encrypt_2);
}
/** 產生會員唯一碼 結束 **/

/** 產生apiUrl **/
function getapiUrl($param)
{
	if(count($param) > 0)
	{
		$apiUrl = "";
		foreach($param as $key=>$row)
		{
			if(empty($apiUrl))
				$apiUrl = "?".$key."=".$row;
			else
				$apiUrl .= "&".$key."=".$row;
		}
		return $apiUrl;
	}
	else
	{
		return false;
	}
}
/** 產生apiUrl 結束 **/

/** 取得可用的LinkDBs **/
function get_linkString_arr()
{
	global $db;
	
	$get_linkString_sql = "SELECT * FROM LinkDBs WHERE linkType = '1'";
	$get_linkString = sqlsrv_query( $db, $get_linkString_sql);
	if ($get_linkString)
	{
		$info = array();
		while ($linkString_arr=sqlsrv_fetch_array($get_linkString))
		{  
			$info['sourceDB'][] = $linkString_arr['sourceDB'];
			$info[$linkString_arr['sourceDB']] = $linkString_arr['linkString'];
			$info[$linkString_arr['sourceDB']."_Code"] = chr($linkString_arr['id']+64);
			$info[chr($linkString_arr['id']+64)] = $linkString_arr['sourceDB'];
		}
		return $info;
	}
	else
		return false;
	
}

/** 取得可用的LinkDBs 結束 **/

/** 取得Syscode名稱 **/
function get_syscodeName($CodeKind,$CodeValue,$linkString)
{
	global $db;
	
	$get_syscodeName_sql = "SELECT CodeName FROM ".$linkString."syscode WHERE CodeKind='$CodeKind' AND CodeValue='$CodeValue'";
	$get_syscodeName = sqlsrv_query( $db, $get_syscodeName_sql);
	if ($get_syscodeName)
	{
		$syscodeName = sqlsrv_fetch_array($get_syscodeName);
		return $syscodeName['CodeName'];
	}
	else
		return false;
	
}

/** 取得Syscode名稱 結束 **/

/** 取得addrCode名稱 **/
function get_addrCodeName($addrLevel,$id)
{
	global $db;
	
	$get_addrCodeName_sql = "SELECT name FROM addrCode WHERE addrLevel='$addrLevel' AND id='$id'";
	
	$get_addrCodeName = sqlsrv_query( $db, $get_addrCodeName_sql);
	if ($get_addrCodeName)
	{
		$addrCodeName = sqlsrv_fetch_array($get_addrCodeName);
		return $addrCodeName['name'];
	}
	else
		return false;
	
}

/** 取得addrCode名稱 結束 **/

/** 取得家長旗下學生資料列表 **/
function get_student_fromParent_arr($parentid)
{
	global $db;
	
	$get_elearn_student_sql = "SELECT DISTINCT B.schoolid,B.sourceDB,B.studentid,B.elearnStuid FROM elearnStudents A JOIN elearnStuLinks B ON A.id = B.elearnStuid WHERE ( A.parentid0 = '$parentid' OR A.parentid1 = '$parentid' OR A.parentid2 = '$parentid' ) AND B.validState = 1 ORDER BY B.elearnStuid";
	$get_elearn_student = sqlsrv_query( $db, $get_elearn_student_sql);
	if ($get_elearn_student)
	{
		$estuid_arr = array();
		$stuinfo_arr = array();
		
		//目前可用的LinkDBs
		$linkString_arr = get_linkString_arr();
		
		while ($elearn_student=sqlsrv_fetch_array($get_elearn_student))
		{  
			$estuid = $elearn_student['elearnStuid'];
			if(!in_array($estuid,$estuid_arr))
				$estuid_arr[] = $estuid;
				
			$info = array();
			$info['schoolid'] = $elearn_student['schoolid'];
			$info['sourceDB'] = $elearn_student['sourceDB'];
			$info['studentid'] = $elearn_student['studentid'];
			$linkString = $linkString_arr[$elearn_student['sourceDB']].".dbo.";
			//抓出該生在該校的課程
			$get_course_sql = "SELECT A.courseid FROM ".$linkString."courseStudent AS A,".$linkString."courses AS B WHERE A.courseid = B.id AND B.state <> '1' AND B.deleteChk <> '1' AND A.studentid='".$elearn_student['studentid']."' AND A.stateScode NOT IN (4,5,6)";
			$get_course = sqlsrv_query( $db, $get_course_sql);
			if ($get_course)
			{
				$course_arr = array();
				while ($course_row = sqlsrv_fetch_array($get_course))
				{
					$course_arr[] = $course_row['courseid'];
				}
				$info['course_arr'] = $course_arr;
			}
				
			$stuinfo_arr[$estuid][] = $info;
		}
		
		$info = array();
		$info['estuid_arr'] = $estuid_arr;
		$info['stuinfo_arr'] = $stuinfo_arr;
		
		return $info;
	}
	else
		return false;
}
/** 取得家長旗下學生資料列表 (結束) **/

/** 取得學生資料列表 **/
function get_student_arr($estudentid)
{
	global $db;
	
	$get_elearn_student_sql = "SELECT DISTINCT A.name,A.ename, B.schoolid,B.sourceDB,B.studentid,B.elearnStuid FROM elearnStudents A JOIN elearnStuLinks B ON A.id = B.elearnStuid WHERE A.id='$estudentid' AND B.validState = 1 ORDER BY B.elearnStuid";
	$get_elearn_student = sqlsrv_query( $db, $get_elearn_student_sql);
	if ($get_elearn_student)
	{
		$estuid_arr = array();
		$stuinfo_arr = array();
		
		//目前可用的LinkDBs
		$linkString_arr = get_linkString_arr();
		
		while ($elearn_student=sqlsrv_fetch_array($get_elearn_student))
		{  
			$estuid = $elearn_student['elearnStuid'];
			if(!in_array($estuid,$estuid_arr))
				$estuid_arr[] = $estuid;
				
			$info = array();
			$info['schoolid'] = $elearn_student['schoolid'];
			$info['sourceDB'] = $elearn_student['sourceDB'];
			$info['studentid'] = $elearn_student['studentid'];
			$info['name'] = $elearn_student['name'];
			$info['ename'] = $elearn_student['ename'];
			$linkString = $linkString_arr[$elearn_student['sourceDB']].".dbo.";
			//抓出該生在該校的課程
			$get_course_sql = "SELECT A.courseid FROM ".$linkString."courseStudent AS A,".$linkString."courses AS B WHERE A.courseid = B.id AND B.state <> '1' AND B.deleteChk <> '1' AND A.studentid='".$elearn_student['studentid']."' AND A.stateScode NOT IN (4,5,6)";
			$get_course = sqlsrv_query( $db, $get_course_sql);
			if ($get_course)
			{
				$course_arr = array();
				while ($course_row = sqlsrv_fetch_array($get_course))
				{
					$course_arr[] = $course_row['courseid'];
				}
				$info['course_arr'] = $course_arr;
			}
				
			$stuinfo_arr[$estuid][] = $info;
		}
		
		$info = array();
		$info['estuid_arr'] = $estuid_arr;
		$info['stuinfo_arr'] = $stuinfo_arr;
		
		return $info;
	}
	else
		return false;
}
/** 取得學生資料列表 (結束) **/

/** 取得學生資料 **/
function get_studentInfo($estudentid,$schid)
{
	global $db;
	
	//取得該雲端學生的學生列表
	$student_arr = get_student_arr($estudentid);
	$stuinfo_arr = $student_arr['stuinfo_arr'];
	//print_r($stuinfo_arr[$estudentid]);
	
	//取得資料庫來源&學校ID
	$linkString_arr = get_linkString_arr();
	$school_arr = explode("-",$schid);
	
	$sourceDB = $linkString_arr[$school_arr[0]];
	$schoolid = $school_arr[1];
	
	foreach($stuinfo_arr[$estudentid] as $row)
	{
		if($row['schoolid'] == $schoolid && $row['sourceDB'] == $sourceDB)
			return $row;
	}
	
	return false;
	
}
/** 取得學生資料列表 (結束) **/

/** 取得家長ID **/
function get_parentid($uk)
{
	global $db;
	
	$get_parent_info_sql = "SELECT * FROM elearnParents WHERE uniqKeyAPI='$uk'";
	$get_parent_info = sqlsrv_query( $db, $get_parent_info_sql);
	if ($get_parent_info)
	{
		$parent_info = sqlsrv_fetch_array($get_parent_info);		
		return $parent_info['id'];
	}
	else
		return false;
	
}
/** 取得家長ID (結束) **/


/** 取得學校名稱 **/
function get_schoolName($stuinfo_arr)
{
	global $db;
	
	$schoolid = $stuinfo_arr['schoolid'];
	$sourceDB = $stuinfo_arr['sourceDB'];
	
	//目前可用的LinkDBs
	$linkString_arr = get_linkString_arr();
	$linkString = $linkString_arr[$sourceDB].".dbo.";
	$schoolName = "";
	$get_schoolName_sql = "SELECT name FROM ".$linkString."schools WHERE id='$schoolid'";
	//echo $get_schoolName_sql."\n";
	$get_schoolName = sqlsrv_query( $db, $get_schoolName_sql);
	if($get_schoolName)
	{
		$schoolName_info = sqlsrv_fetch_array($get_schoolName);
		$schoolName = $schoolName_info['name'];
	}
	return $schoolName;
}
/** 取得學校名稱 (結束) **/

/** 取得學校名稱2 **/
function get_schoolName2($schoolid,$linkString)
{
	global $db;
	
	$schoolName = "";
	$get_schoolName_sql = "SELECT id,name FROM ".$linkString."schools WHERE id='$schoolid'";
	//echo $get_schoolName_sql."\n";
	$get_schoolName = sqlsrv_query( $db, $get_schoolName_sql);
	if($get_schoolName)
	{
		$schoolName_info = sqlsrv_fetch_array($get_schoolName);
		if(!empty($schoolName_info['id']))
			$schoolName = $schoolName_info['name'];
	}
	return $schoolName;
}
/** 取得學校名稱 (結束) **/

/** 取得職員名稱 **/
function get_employeeName($id,$linkString)
{
	global $db;
	
	$employeeName = "";
	$get_employeeName_sql = "SELECT A.name,B.CodeName FROM ".$linkString."employees A, ".$linkString."pubcode B WHERE A.titlePcode = B.id AND  A.id='$id'  ";
	//echo $get_employeeName_sql."\n";
	$get_employeeName = sqlsrv_query( $db, $get_employeeName_sql);
	if($get_employeeName)
	{
		$employeeName = sqlsrv_fetch_array($get_employeeName);
		$employeeName = $employeeName['CodeName']." ".$employeeName['name'];
	}
	return $employeeName;
}
/** 取得職員名稱 (結束) **/

/** 取得學生名稱 **/
function get_studentName($id,$linkString)
{
	global $db;
	
	$student_Name = "";
	$get_studentName_sql = "SELECT name FROM ".$linkString."students WHERE id='$id' ";
	//echo $get_studentName_sql."\n";
	$get_studentName = sqlsrv_query( $db, $get_studentName_sql);
	if($get_studentName)
	{
		$studentName = sqlsrv_fetch_array($get_studentName);
		$student_Name = $studentName['name'];
	}
	return $student_Name;
}
/** 取得學生名稱 (結束) **/

/** 取得課程名稱 **/
function get_courseName($courseid,$schoolid,$linkString)
{
	global $db;
	
	$course_Name = "";
	$get_courseName_sql = "SELECT courseName FROM ".$linkString."View_courses WHERE id='$courseid' AND schoolid='$schoolid'";
	//echo $get_courseName_sql."\n";
	$get_courseName = sqlsrv_query( $db, $get_courseName_sql);
	if($get_courseName)
	{
		$courseName = sqlsrv_fetch_array($get_courseName);
		$course_Name = $courseName['courseName'];
	}
	return $course_Name;
}
/** 取得課程名稱 (結束) **/

/** 取得課程資訊 **/
function get_course_info_arr($stuinfo_arr)
{
	global $db;
	
	$schoolid = $stuinfo_arr['schoolid'];
	$sourceDB = $stuinfo_arr['sourceDB'];
	$studentid = $stuinfo_arr['studentid'];
	$course_arr = $stuinfo_arr['course_arr'];
	
	//目前可用的LinkDBs
	$linkString_arr = get_linkString_arr();
	$linkString = $linkString_arr[$sourceDB].".dbo.";
	$course_info_arr = array();
	
	if (count($course_arr)>0)
	{
		foreach($course_arr as $courseid)
		{
			$get_course_info_sql="SELECT courseName FROM ".$linkString."View_courses WHERE id='$courseid' AND schoolid='$schoolid'";
			//echo $get_course_info_sql."\n";
			$get_course_info = sqlsrv_query( $db, $get_course_info_sql);
			if($get_course_info)
			{
				$course_info = sqlsrv_fetch_array($get_course_info);
				$course_info_arr[$courseid]['courseName'] = $course_info['courseName'];
			}
		}
		
	}
	return $course_info_arr;
}

/** 取得課程資訊 (結束) **/


/** 取得未簽名聯絡簿數 **/
function getContactBookNoSignCnt($elearn_studentid = null,$stuinfo_arr = array(),$linkString_arr = array())
{
	global $db;
	
	if(empty($elearn_studentid) || count($stuinfo_arr) == 0 || count($linkString_arr) == 0)
		return "0";
		
	$noSignCnt = 0;
	
	foreach($stuinfo_arr[$elearn_studentid] as $row)
	{
		$schoolid = $row['schoolid'];
		$sourceDB = $row['sourceDB'];
		$studentid = $row['studentid'];
		$linkString = $linkString_arr[$sourceDB].".dbo.";
		$where_course_str = "AND A.courseid IN ('".implode("','",$row['course_arr'])."') ";
		
		$get_contactbookNoSign_sql = "SELECT A.id FROM ".$linkString."contactbooks AS A , ".$linkString."schedules AS B , ".$linkString."contactbookDetails AS C WHERE A.schedid=B.id AND A.id = C.contactbookid AND  A.schoolid='$schoolid' $where_course_str AND A.approveState='finish' AND C.parentSignature ='0' AND C.studentid='$studentid' ORDER BY B.realDate DESC";
		$get_contactbookNoSign = sqlsrv_query( $db, $get_contactbookNoSign_sql);
		if ($get_contactbookNoSign)
		{
			$arReturn = array();
			while ($contactbookid = sqlsrv_fetch_array($get_contactbookNoSign))
				$arReturn[] = $contactbookid;
			$noSignCnt += count($arReturn);
		}
	}
	
	return $noSignCnt;
}
/** 取得未簽名聯絡簿數 結束 **/

/** 取得未讀留言數 **/
function getMessageNoReadCnt($elearn_studentid = null,$stuinfo_arr = array(),$linkString_arr = array(),$type = null)
{
	global $db;
	
	if(empty($elearn_studentid) || count($stuinfo_arr) == 0 || count($linkString_arr) == 0)
		return "0";
		
	if($type == "detail")
		$noReadCnt = array();
	else
		$noReadCnt = 0;
	
	foreach($stuinfo_arr[$elearn_studentid] as $row)
	{
		$schoolid = $row['schoolid'];
		$sourceDB = $row['sourceDB'];
		$studentid = $row['studentid'];
		$course_arr = $row['course_arr'];
		$linkString = $linkString_arr[$sourceDB].".dbo.";
		
		if($type == "detail")
		{
			foreach($row['course_arr'] as $courseid)
			{
				$get_messageNoRead_sql = "SELECT id FROM ".$linkString."messages WHERE schoolid='$schoolid' AND studentid='$studentid' AND courseid = '$courseid' AND msgAuthorType <> 'students' AND readChk IS NULL";
				//echo $get_messageNoRead_sql."\n";
				$get_messageNoRead = sqlsrv_query( $db, $get_messageNoRead_sql);
				if ($get_messageNoRead)
				{
					$arReturn = array();
					while ($messageNoRead = sqlsrv_fetch_array($get_messageNoRead))
						$arReturn[] = $messageNoRead;
						
					$noReadCnt[$linkString_arr[$sourceDB."_Code"]."-".$schoolid."-".$courseid] = count($arReturn);
				}
				
			}
		}
		else
		{
			//檢查該生是否為訪客
			$chk_student_sql = "SELECT * FROM ".$linkString."students WHERE schoolid = '$schoolid' AND id = '$studentid'";
			$chk_student = sqlsrv_query( $db, $chk_student_sql);
			if($chk_student)
			{
				$student_state = sqlsrv_fetch_array($chk_student);
				
				if(!empty($student_state['state']) && $student_state['state'] == '-1')
				{
					//抓取該校試聽課程
					$get_trialid_sql = "SELECT * FROM ".$linkString."courses WHERE schoolid='$schoolid' AND subjectid = '0'";
					$get_trialid = sqlsrv_query( $db, $get_trialid_sql);
					if ($get_trialid)
					{
						$trial_info = sqlsrv_fetch_array($get_trialid);
						
						if(!empty($trial_info['id']))
							$course_arr[] = $trial_info['id'];
					}
				}
			}
			
			if(count($course_arr) > 0)
			{
				$where_course_str = "AND courseid IN ('".implode("','",$course_arr)."') ";
			
				$get_messageNoRead_sql = "SELECT id FROM ".$linkString."messages WHERE schoolid='$schoolid' AND studentid='$studentid' $where_course_str AND msgAuthorType <> 'students' AND readChk IS NULL";
				//echo $get_messageNoRead_sql."\n";
				$get_messageNoRead = sqlsrv_query( $db, $get_messageNoRead_sql);
				if ($get_messageNoRead)
				{
					$arReturn = array();
					while ($messageNoRead = sqlsrv_fetch_array($get_messageNoRead))
						$arReturn[] = $messageNoRead;
						
					$noReadCnt += count($arReturn);
				}
			}
		}
	}
	
	//print_r($noReadCnt);
	
	return $noReadCnt;
}
/** 取得未讀留言數 結束 **/

/** 取得未讀通知數 **/
function getReceiveNoReadCnt($elearn_studentid = null,$stuinfo_arr = array(),$linkString_arr = array(),$parentid)
{
	global $db;
	
	if(empty($elearn_studentid) || count($stuinfo_arr) == 0 || count($linkString_arr) == 0)
		return "0";
		
	$noReadCnt = 0;
	
	foreach($stuinfo_arr[$elearn_studentid] as $row)
	{
		$schoolid = $row['schoolid'];
		$sourceDB = $row['sourceDB'];
		$studentid = $row['studentid'];
		$linkString = $linkString_arr[$sourceDB].".dbo.";
		
		$get_notificationNoRead_sql = "SELECT count(id) as cnt FROM ".$linkString."notifications WHERE studentid='$studentid' AND parentid='$parentid' AND sendTime IS NOT NULL AND (readChk is NULL OR readChk = '0')";
		//echo $get_notificationNoRead_sql."\n";
		$get_notificationNoRead = sqlsrv_query( $db, $get_notificationNoRead_sql);
		if ($get_notificationNoRead)
		{
			$notificationNoRead = sqlsrv_fetch_array($get_notificationNoRead);
			$noReadCnt += $notificationNoRead['cnt'];
		}
	}
	
	return $noReadCnt;
}
/** 取得未讀通知數 結束 **/


/** 寫入LOG檔 **/
function write_log($str,$status,$data_array)  //傳入資料夾名 想寫近的狀態 資料      
{
	$textname = $str.date("Ymd").".txt"; //檔名  filename
	$URL = "log/".$str."/";                         //路徑  Path
	if(!is_dir($URL))                                 // 路徑中的$str 資料夾是否存在 Folder exists in the path
		mkdir($URL,0700);

	$URL .= $textname;                           //完整路徑與檔名 The full path and filename

	$time = $str.$status.":[".date("H:i:s")."]"; //時間 Time
	$writ_tmp = '';
	foreach ($data_array as $key => $value) //將陣列資料讀出 To read array data
	{
	   $writ_tmp .= ",".$key."=".$value;            
	}
	$write_data = $time.$writ_tmp."\n";
			   
	$fileopen = fopen($URL, "a+");              
	fseek($fileopen, 0);
	fwrite($fileopen,$write_data);                 //寫資料進去 write data
	fclose($fileopen);
} 
/** 寫入LOG檔 結束 **/


function getFieldValue( $sql , $fName)
{	
	global $db;
  	$db->setQuery( $sql );
  	if($db->loadObject($r))
  	{
  		return  $r->$fName;
    }
    else
		return  null;
}
function imgupd($imageData,$path,$tablename,$id,$num=1,$cut=true){
	global $db,$conf_dir_path;
	
	$filteredData = substr($imageData, strpos($imageData, ",") + 1);
	$unencodedData = base64_decode($filteredData);
	$fpath=$path;
	$path=$conf_dir_path.$path;
	$fp = fopen($path, 'wb');
	fwrite($fp, $unencodedData);
	fclose($fp);
	try{
		$quality = 80;
		
		if($tablename == "products") {
			$targetWidth = "622";
			$targetHeight = "726";
		}else{
			$cut = false;
		}
		
		switch (exif_imagetype($path)) {
		 
			case IMAGETYPE_PNG :
                $img = imagecreatefrompng($path);
		        imagesavealpha($img, true);
                if($cut) {
                	$src_w = imagesx($img);
                    $src_h = imagesy($img);
                    // 依長與寬兩者最短的邊來設為新的圖片的長寬
                    if( $src_w > $src_h){
                        $new_w = $src_h * $targetWidth / $targetHeight;
                        $new_h = $src_h;
                    }else{
                        $new_w = $src_w;
                        $new_h = $src_w * $targetHeight / $targetWidth;
                    }
                    
                    $srt_w = ( $src_w - $new_w ) / 2;
                    $srt_h = ( $src_h - $new_h ) / 2;
                    // 定義一個圖形 ( 針對正方形圖形 )
                    $newpc = imagecreatetruecolor($new_w,$new_h);
                    // 抓取正方形的截圖
                    imagealphablending($newpc, false);
		            imagesavealpha($newpc, true);
                    imagecopy($newpc, $img, 0, 0, $srt_w, $srt_h, $new_w, $new_h );
                    @imagedestroy($img);
                    $img = $newpc;
                    
                } else {
                	imagealphablending($img, false);
                }
                
                @imagepng($img, $path, 8);
				break;
			case IMAGETYPE_JPEG :
				@$img = imagecreatefromjpeg($path);
				if($cut) {
                	$src_w = imagesx($img);
                    $src_h = imagesy($img);
                    // 依長與寬兩者最短的邊來算出新的圖片的長寬
                    if( $src_w > $src_h){
                        $new_w = $src_h * $targetWidth / $targetHeight;
                        $new_h = $src_h;
                    }else{
                        $new_w = $src_w;
                        $new_h = $src_w * $targetHeight / $targetWidth;
                    }
                    
                    $srt_w = ( $src_w - $new_w ) / 2;
                    $srt_h = ( $src_h - $new_h ) / 2;
                    // 定義一個圖形 ( 針對正方形圖形 )
                    $newpc = imagecreatetruecolor($new_w,$new_h);
                    // 抓取正方形的截圖
                    imagecopy($newpc, $img, 0, 0, $srt_w, $srt_h, $new_w, $new_h );
                    @imagedestroy($img);
                    $img = $newpc;
                    
                }
				@imagejpeg($img, $path, $quality);
				break;
		}
		//釋放記憶體
		@imagedestroy($img);
	}catch(Exception $e){
		
	}
	if(is_file($path)){
		$code=getFieldValue("select code from imglist where belongid='$id' AND path='$tablename' AND num='$num'","code");
		if(!$code){
			$db->setQuery("insert into imglist (belongid,path,name,code,num,version) values ('$id','$tablename','$fpath','".md5($id.$num.$tablename)."','$num',1)");
			$db->query();
		}else{
			$db->setQuery("update imglist set version=version+1 where code='$code'");
			$db->query();
		}
		return true;
	}else{
		return false;
	}
}

function getimg($tablename,$belongid=0,$num=0){
	global $db,$conf_dir_path,$conf_real_upload,$conf_upload;
	$where_str="";
	if($belongid){
		$where_str.=" AND belongid='$belongid'";
	}
	
	if($num>0){
		$where_str.=" AND num='$num'";
	}
	$sql="select * from imglist where path='$tablename' $where_str order by num";
	$db->setQuery($sql);
	$r=$db->loadRowList();
	$imgArr=array();
	foreach($r as $row){
		$name=$row['name'];
		if(is_file($conf_dir_path.$name)){
			if($num==0){
				$imgArr[$row['num']]=str_replace($conf_upload,$conf_real_upload,$name)."?v=".$row['version'];
			}else{
				$imgArr=str_replace($conf_upload,$conf_real_upload,$name)."?v=".$row['version'];
			}
		}
	}
	return $imgArr;
}
function delimg($tablename,$id,$num=1){
	global $db,$conf_dir_path;
	$code=getFieldValue("select code from imglist where belongid='$id' AND path='$tablename' AND num='$num'","code");
	if($code){
		$name=getFieldValue("select name from imglist where belongid='$id' AND path='$tablename' AND num='$num'","name");
		$db->setQuery("delete from imglist where belongid='$id' AND path='$tablename' AND num='$num'");
		$r=$db->query();
		if($r){
			unlink($conf_dir_path.$name);
		}
	}
}


function delAllimg($tablename, $id) {
	global $db, $conf_dir_path;
	
	$db->setQuery("select name from imglist where belongid='$id' AND path='$tablename'");
	$list = $db->loadRowList();
	$db->setQuery("delete from imglist where belongid='$id' AND path='$tablename'");
	$r=$db->query();
	if($list && $r) {
		foreach($list as $key=>$val) {
			unlink($conf_dir_path.$val['name']);
		}
	}
}

function odrchg($tablename,$id,$ext_where=null,$str=null){
	global $db,$conf_user;
	$sql=null;
	
	if(empty($str))
	{
		$str = 'odring';
	}
	
	for($i=1;$i<=count($id);$i++){
		$sql.="update $tablename set $str='$i' where id='{$id[($i-1)]}' $ext_where;";
	}
	$db->setQuery( $sql );
	$db->query_batch();
	JsonEnd(array("status"=>1,"msg"=>"操作成功"));
}

function JsonEnd($arrJson)
{
	//測試用參數，完成需關閉
	$showtxt = 0;
	
	global_output_header('json');
	session_write_close();
	if(isset($_GET['callback'])) {
		echo $_GET['callback']."(".json_encode($arrJson).")"; 
	} else {
		echo json_encode($arrJson);
	}
	exit;
}

//取得系統編號
function getSysid($ulevel){
	global $conf_user,$db,$root_admin,$root_store;
	
	//$ulevel = $_SESSION[$conf_user]['ulevel'];
	if($ulevel==$root_admin){
		return "admin";
	}else if($ulevel==$root_store){
		return $_SESSION[$conf_user]['uid'];
	}else{
		return false;
	}
}

function getsiteinfo($on=array()){
	global $db,$conf_user;
	
	$sql="select * from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$data=array();
	foreach($r as $key=>$value){
		if(in_array($key,$on)){
			$data[$key]=$value;
		}
	}
	return $data;
}

function getpubcode($ajax=null){
	global $db;
	
	$sql = " select * from pubcode where deleteChk=0 order by odring";	

	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$info=array();
		$info['name']=$row['codeName'];
		$info['codeName_chs']=$row['codeName_chs'];
		$info['codeName_en']=$row['codeName_en'];
		$info['value']=$row['codeValue'];
		
		$data[$row['codeKinds']][$info['value']]=$info;
		
		$_SESSION['pubcode'][$row['codeKinds']][$info['value']]=$info;
	}
	if($ajax){
		JsonEnd($data);
	}
}

function getWMDate($type,$targetDate=null){
	if(!$targetDate){
		$targetDate=date("Y-m-d");
	}
	if(!is_numeric($type)){
		if(!$type)$type="w";
		if($type=='w'){
	    	$sdate=date("Y-m-d",strtotime("-7 day".$targetDate));
	    }else if($type=='m'){
	    	$sdate=date("Y-m-d",strtotime("-1 month".$targetDate));
	    }
	}else{
		if($type>0)$type="+".$type;
		$sdate=date("Y-m-d",strtotime("$type day".$targetDate));
	}
    return $sdate;
}

function dateNum($sdate,$edate,$row,$type='label',$usedate=true){
	
	$s=((strtotime($edate)-strtotime($sdate))/60/60/24);
	$a=array();
	
	
	for($i=0;$i<($s-0);$i++){
		if($usedate){
			$d=date("Y-m-d",strtotime("+{$i} day ".$sdate));
			if($row[$d]){
				if($type=='label'){
					$a[$d]=date("m-d",strtotime($row[$d]));
				}else{
					$a[$d]=$row[$d];
				}
			}else{
				if($type=='value'){
					$v=0;
				}else{
					$v=date("m-d",strtotime($d));
				}
				$a[$d]=$v;
			}
		}else{
			return $row;
		}
	}
	
	
	return $a;
}

function arrayRmKey($arr=array(),$arr2=array()){
	ksort($arr);
	ksort($arr2);

	if(count($arr)>0){
		if(count($arr2)>0){
			$a=array();
			
			foreach($arr as$key=>$value){
				$b=array();
				$b['key']=$value;
				$b['values']=$arr2[$key];
				$a[]=$b;
			}
		}else{
			$a=array();
			foreach($arr as $value){
				if(is_numeric($value) && substr($value,1)!=0){
					$value=floatval($value);
				}
				$a[]=$value;
			}
		}
		$arr=$a;
	}
	
	return $arr;
}
/*
	chartType->圖表類型
	labels->X軸
	total->Y軸
	series->Y軸單位
*/
function getChart($chartType,$labels,$total,$series){
	
	$dataArr=array();
	if($chartType=="d3"){
		$dataArr['data']=arrayRmKey($labels,$total);
	}else if($chartType=="chartjs"){
		$labels=arrayRmKey($labels);
		$total=arrayRmKey($total);
		$series=array($series);
		$dataArr['labels']=$labels;
		$dataArr['series']=$series;
		$dataArr['data']=array($total);
	}
	return $dataArr;
}

function zeroChk($v1,$v2){
	
	if($v1!="∞"){
		if($v2!=0){
			$v1=round($v1/$v2);
		}else{
			$v1="∞";
		}
	}
	return $v1;
}

function parr($arr=array()){
	
	echo "<pre>".print_r($arr,true)."</pre>"; 
}

function enpw($pw=null){
	global $globalConf_encrypt_1,$globalConf_encrypt_2;
	if(!$pw)die();
	return md5($globalConf_encrypt_1.$pw.$globalConf_encrypt_2);
}


//計算指定商品活動的折扣
function ComputeDesActve($allcart,$cart,$act,$actRangePCode,$discount,$discountFree){
	global $db,$conf_user;
	
	
	$actTypePCode = $act['actTypePCode'];	//優惠條件
	$activePlanid = $act['activePlanid'];	//優惠方案
	
	//parr($allcart);
	//parr($cart);
	//parr($act);
	$arr = array();
	if(count($cart) > 0)
	{
		$tarcart = array('totalAmt'=>0,'totalNum'=>0);
		$usepro = array();	//符合的指定商品編號
		$minproid = 0;	//最低價商品編號
		$minproAmt = 0;	//最低價商品金額
		$actproArr = array();   //參與活動的商品
		
		$odrproArr = array();	//紀錄售價低至高的商品資料
		$odrproRArr = array();	//紀錄售價高至低的商品資料
		
		foreach($cart as $pid=>$row)
		{
			//檢查此商品是否符合指定商品
			if($actRangePCode == '2' && strpos($act['var03'],"||".$pid."||") === false)
			{
				continue;
			}
			else
			{
				//統計指定商品的金額與件數
				$tarcart['totalAmt'] += $row['num'] * $row['siteAmt'];
				$tarcart['totalNum'] += $row['num'];
				
				//符合的指定商品編號
				$usepro[] = $pid;
				
				//紀錄最低價商品
				if($minproAmt == 0 || $minproAmt > $row['siteAmt'])
				{
					$minproid = $pid;
					$minproAmt = $row['siteAmt'];
				}
				
				//符合條件商品，售價由低到高排列
				if($activePlanid == '12' || $activePlanid == '3')
				{
					$tmp = array();
					$chk = true;
					if(count($odrproArr) > 0)
					{
						foreach($odrproArr as $pro)
						{
							if($pro['siteAmt'] > $row['siteAmt'] && $chk)
							{
								for($i = 0 ; $i < $row['num'] ; $i++)
								{
									$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
								}
								$chk = false;
							}
							
							$tmp[] = $pro;
							
						}
						
						if($chk)
						{
							for($i = 0 ; $i < $row['num'] ; $i++)
							{
								$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
							}
						}
						
					}
					else
					{
						for($i = 0 ; $i < $row['num'] ; $i++)
						{
							$tmp[] = array('pid'=>$pid,'siteAmt'=>$row['siteAmt']);
						}
					}
					$odrproArr = $tmp;
					
				}
			}
		}
		
		$odrproRArr = array_reverse($odrproArr);
		
		//贈品免運計算
		if($activePlanid == '8' || $activePlanid == '9' || $activePlanid == '10')
		{
			if($actTypePCode == '2')
			{
				$allcart['totalAmt'] = $allcart['totalAmt'] - intval($discount);
			}
			else if($actTypePCode == '4')
			{
				$tarcart['totalAmt'] = $tarcart['totalAmt'] - intval($discount);
			}
		}
		
		if($activePlanid == '9')
		{
			if($actTypePCode == '2')
			{
				$allcart['totalAmt'] = $allcart['totalAmt'] - intval($discountFree);
			}
			else if($actTypePCode == '4')
			{
				$tarcart['totalAmt'] = $tarcart['totalAmt'] - intval($discountFree);
			}
		}
		
		//檢查是符合條件
		switch ($actTypePCode) {
			case "2":	//全單滿額
				if($allcart['totalAmt'] < $act['var01']) {
					return null;	
				}
				
				//滿足次數
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalAmt'] / $act['var01']);
				}
				
		        break;
			case "3":	//全單滿件
				if($allcart['totalNum'] < $act['var01']) {
					return null;	
				}
				
				//滿足次數
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($allcart['totalNum'] / $act['var01']);
				}
				
				break;
			case "4":	//單品滿額
				if($tarcart['totalAmt'] < $act['var01']) {
					return null;	
				}
				
				//滿足次數
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalAmt'] / $act['var01']);
				}
				
				break;
			case "5":	//單品滿件
				if($tarcart['totalNum'] < $act['var01']) {
					return null;	
				}
				
				//滿足次數
				$freeCnt = 0;
				if($act['var01'] > "0")
				{
					$freeCnt = (int)($tarcart['totalNum'] / $act['var01']);
				}
				
				break;
		}
		
		//計算折價
		switch($activePlanid)
		{
			case "1":	//1:折價（每件折X元）
				$tmp = $act['var02'] * $tarcart['totalNum'];
				$arr['dispro'] = $usepro;
				$arr['actproArr'] = $usepro;
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? $tarcart['totalAmt'] : $tmp);
				break;
			case "2":	//2:折扣（每件打X折）
				$arr['dispro'] = $usepro;
				$arr['actproArr'] = $usepro;
				$arr['disAmt'] = intval(round($tarcart['totalAmt'] * (100 - intval($act['var02'])) * 0.01));
				break;
			case "3":	//3:單一價
				$usepro = array();
				$var01 = intval($act['var01']);	//須符合件數
				$var02 = intval($act['var02']);	//單一價金額
				$q = 1;
				if($var01 > 0)
				{
					$q = intval($tarcart['totalNum'] / $var01);
				}
				$sum = 0;
				for($i = 0 ; $i< ($q * $var01) ; $i++)
				{
					$sum += $odrproRArr[$i]['siteAmt'];
					$usepro[] = $odrproRArr[$i]['pid'];
					$arr['dispro'][] = $odrproRArr[$i]['pid'];
					$arr['actproArr'][] = $odrproRArr[$i]['pid'];
				}
				$arr['disAmt'] = intval(($sum < ($q * $var02)) ? '0' : $sum - ($q * $var02));
				break;
			case "4":	//4:活動商品總共折X元
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? $tarcart['totalAmt'] : $tmp);
				break;
			case "5":	//5:活動商品總共固定X元
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $tarcart['totalAmt']) ? '0' : $tarcart['totalAmt'] - $tmp);
				break;
			case "6":	//6:單筆最低價商品折價
				$tmp = $act['var02'];
				$arr['disAmt'] = intval(($tmp > $minproAmt) ? $minproAmt : $tmp);
				break;
			case "7":	//7:單筆最低價商品折扣
				$arr['disAmt'] = intval(round($minproAmt * (100 - intval($act['var02'])) * 0.01));
				break;
			case "8":	//8:加購商品
			case "9":	//9:贈品
				$arr['disAmt'] = '0';
				$arr['actpid'] = $activePlanid;
				if($activePlanid == '9')
				{
					if(empty($freeCnt))
					{
						return null;	
					}
					
					$arr['freeCnt'] = $freeCnt;
				}
				break;
			case "10":	//10:免運
			case "11":	//11:紅利
				$arr['disAmt'] = '0';
				$arr['actpid'] = $activePlanid;
				break;
			case "12":	//12:第二件N折
				$sum = 0;
				$arr['dispro'] = array();
				$pro_cnt = array();
				$usepro = array();
				
				$pairProArr = $_SESSION[$conf_user]["pairpro_list"];
				
				if(count($pairProArr) > 0)	//使用者自行配對商品
				{
					$disAmt = 0;
					foreach($pairProArr as $pair)
					{
						$pairArr = explode("@@",$pair);
						foreach($odrproArr as $odrpro)
						{
							if($odrpro['pid'] == $pairArr[1])
							{
								//折扣金額 = 原價 - 特價
								$disAmt +=   (intval($odrpro['siteAmt']) -  round(intval($odrpro['siteAmt']) * intval($act['var02']) * 0.01));
								$arr['dispro'][] = $odrpro['pid'];
								//$usepro[] = $odrpro['pid'];
								break;
							}
						}
						
						if(count($pairArr) > 0)
						{
							foreach($pairArr as $pair_pid)
							{
								if(!empty($pair_pid))
								{
									$arr['actproArr'][]= $pair_pid;
								}
							}
						}
						
					}
				}
				else
				{
					$disAmt = 0;
					//$arr['actpid'] = $activePlanid;
				}
				
				//最大折扣金額
				$tmpNum = ($actTypePCode == '2' || $actTypePCode == '3') ? $allcart['totalNum'] : $tarcart['totalNum'];
				$disAmtMax = 0;
				$odrproArr_reverse  = array_reverse($odrproArr);
				for($i = 0 ; $i< intval($tmpNum / 2) ; $i++)
				{
					//折扣金額 = 原價 - 特價
					$j = (2*$i)+1;
					if(count($odrproArr_reverse[$j]) > 0)
					{
						$disAmtMax +=   (intval($odrproArr_reverse[$j]['siteAmt']) -  round(intval($odrproArr_reverse[$j]['siteAmt']) * intval($act['var02']) * 0.01));
					}
				}
				
				if(count($odrproArr) > 0)
				{
					foreach($odrproArr as $odrproData)
					{
						$usepro[] = $odrproData['pid'];
					}
				}
				
				//$arr['actpid'] = $activePlanid;
				$arr['disAmt'] = $disAmt;
				$arr['disAmtMax'] = $disAmtMax;
				break;
			
		}
	}
	
	if(count($usepro) > 0)
	{
		$arr['usepro'] = $usepro;
	}
	
	
	
	return $arr;
}




//折扣計算
function saleCalc($proArr,$numArr){
	global $db,$conf_user;
	
	if(count($proArr)==0)return false;
	
	/*
		$numArr從
		array(
			$pid=>$num
		)
		變成
		array(
			$pid=>array(
				$format1=>array(
					$format2=>$num
				)
			)
		)
	
	*/
	
	$amt=0;
	$total=0;
	
	$cartpro=$proArr;
	$cartpro2=$proArr;
	
	$data=array();
	
	//撈取現有活動
	$today=date("Y-m-d H:i");
	$sql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='')
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$active=array();
	$des_active=array(); //指定商品活動
	$all_active=array(); //全館商品活動
	foreach($r as $row){
		$active[$row['id']]=$row;
		$all_active[$row['actRangePCode']][$row['ptype']][$row['id']] = $row;
	}
	
	$sql = "select coin_to,coin_take from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$coin_to=$r['coin_to'];
	$coin_take=$r['coin_take'];
	
	$active_list=array();
	$discount=0;
	$disDlvrAmt=0;
	$loopable=true;
	$otherCalc=true;//剩下的商品是否計算非折上折活動

	$usePro=array();
	$calcPro=$proArr;
	$tmpActive=array();
	$tmpproamt=0;
	
	$allprodiscount = 0;	//全館活動最大折價
	$allproactive_arr = array();
	
	
	//整理購物車商品
	if(count($proArr) > 0)
	{
		$allcart = array('totalAmt'=>0,'totalNum'=>0);
		$cartpro = array();
		foreach($proArr as $row)
		{
			if(count($cartpro[$row['id']]) == '0')
			{
				$info = array();
				$info['id'] = $row['id'];
				$info['imgname'] = $row['imgname'];
				$info['num'] = 0;
				$info['siteAmt'] = $row['siteAmt'];
				$info['CalcHighAmt'] = 0;
				$info['CalcSiteAmt'] = 0;
				$info['item'] = array();
				
				$cartpro[$row['id']] = $info;
			}
			
			$cartpro[$row['id']]['num'] += $row['num'];
			$cartpro[$row['id']]['CalcHighAmt'] += $row['CalcHighAmt'];
			$cartpro[$row['id']]['CalcSiteAmt'] += $row['CalcSiteAmt'];
			
			$info = array();
			$info['num'] = $row['num'];
			$info['format1'] = $row['format1'];
			$info['format2'] = $row['format2'];
			$info['format1title'] = $row['format1title'];
			$info['format2title'] = $row['format2title'];
			$info['format1name'] = $row['format1name'];
			$info['format2name'] = $row['format2name'];
			$info['name'] = $row['name'];
			
			$cartpro[$row['id']]['item'][] = $info;
			
			$allcart['totalAmt'] += $row['siteAmt']*$row['num'];
			$allcart['totalNum'] += $row['num'];
			
		}
	}
	
	$amt = $allcart['totalAmt'];	//購物車總額
	$total=$amt;
	//parr($all_active);
	//parr($cartpro);
	
	
	//單品折扣活動優先計算
	if(count($all_active) > 0)
	{
		foreach($all_active as $actRangePCode=>$info)
		{
			$tmp_active = $info;
			foreach($tmp_active as $ptype=>$row)
			{
				$tmp_cartpro = $cartpro;
				while((count($tmp_active[$ptype]) > 0) && (count($tmp_cartpro) > 0))
				{
					//計算同一類別最大折扣的活動
					if(count($row) > 0)
					{
						$tmp_actid = 0;	//有最大折扣的活動
						$tmp_disAmt = 0; //最大折扣數
						$tmp_usepro = array();	//有最大折扣的活動包含的商品編號
						
						foreach($row as $act)
						{
							$arr = ComputeDesActve($allcart,$tmp_cartpro,$act,$actRangePCode);
							
							if(count($arr) > 0 && ( $arr['disAmt'] > $tmp_disAmt) || (!empty($arr['actpid'])))
							{
								$tmp_actid = $act['id'];
								$tmp_disAmt = $arr['disAmt'];
								$tmp_usepro = $arr['usepro'];
							}
						}
						
						if(!empty($tmp_actid))
						{
							if($active[$tmp_actid]['ptype'] == '2')	//加價購
							{
								//可加價購商品
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$addAmtPro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$proinfo['format'] =getProductFormat($proinfo['id']);
										$proinfo['img']=getimg('products',$proinfo['id'],1);
										$addAmtPro[] = $proinfo;
									}
								}
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"addAmtPro"=>$addAmtPro,"usepro"=>$tmp_usepro);
							}
							elseif($active[$tmp_actid]['ptype'] == '3')	//贈品
							{
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0);
							}
							elseif($active[$tmp_actid]['ptype'] == '4')	//免運活動
							{
								//符合免運活動
								$disDlvrAmt=$_SESSION[$conf_user]['dlvrAmt'];
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$disDlvrAmt,"dlvrfee"=>1);
							}
							else
							{
								$discount += $tmp_disAmt; //目前總折價
										
								//紀錄活動
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro);
									
								$amt=$amt-($tmp_disAmt);	//目前總額
							}
								
							//將使用過的商品撈出來
							foreach($tmp_usepro as $proid)
							{
								unset($tmp_cartpro[$proid]);
							}
							//將使用過的活動撈出來
							unset($tmp_active[$ptype][$tmp_actid]);
							
						}
						else
						{
							break;
						}
					}
				}
			}
		}
	}
	
	
	
	if($coin_to){
		$free_coin=floor(($amt-intval($_SESSION[$conf_user]['usecoin']))/$coin_to)*$coin_take;
	}
	
	$data['amt']=$amt;
	$data['total']=$total;
	$data['active_list']=$active_list;
	$data['discount']=$discount;
	$data['disDlvrAmt']=$disDlvrAmt;
	$data['usecoin']=intval($_SESSION[$conf_user]['usecoin']);
	$data['free_coin']=$free_coin;
	
	
	return $data;
	
	

	/*
	//單品折扣活動優先計算
	foreach($active_rank as $active_num){
		if(count($active[$active_num])>0 && $loopable){
			foreach($active[$active_num] as $row){
				if($active_num==2){	//折扣活動
				
					$usepro=array();
					$usecalcPro = array();
					$tmpsum = 0;
					
					//個別商品
					if($row['actRangePCode'] == '2')
					{
						//檢查購物車中符合活動的商品總額
						foreach($calcPro as $k=>$proinfo)
						{
							if(strripos($row['var03'],"||".$proinfo['id']."||") || strripos($row['var03'],"||".$proinfo['id']."||")===0)
							{
								$tmpsum+=$proinfo['siteAmt']*$numArr[$proinfo['id']]; 
								$usepro[]=$proinfo['id'];
								$usecalcPro[]=$k;
							}
						}
						
						if($tmpsum>=intval($row['var01'])){
							$a=$tmpsum;
							$tmpsum=round($tmpsum*$row['var02']/100);
							$discount+=$a-$tmpsum; //目前總折價
							
							$active_list[]=array("id"=>$row['id'],"name"=>$row['name'],"amt"=>$tmpsum,"discount"=>$a-$tmpsum,"usepro"=>$usepro);
							$amt=$amt-($a-$tmpsum);	//目前總額
							
							//將每個商品目前單價寫回去
							foreach($usecalcPro as $ucp)
							{
								if(empty($calcPro[$ucp]['tmpamt']))
								{
									$calcPro[$ucp]['tmpamt'] = $calcPro[$ucp]['CalcSiteAmt'];
								}
								$calcPro[$ucp]['tmpamt'] = round($calcPro[$ucp]['tmpamt'] * $row['var02']/100);
							}
						}
					}
					else if($row['actRangePCode'] == '1'){//全館
						//全館活動只生效折扣最多的
							print_r($calcPro);
						//購物車內總額
						$tmpsum_t = 0;
						foreach($calcPro as $k=>$proinfo)
						{
							//總金額
							$tmpsum+=$proinfo['siteAmt']*$numArr[$proinfo['id']]; 
							
							//可折價金額
							if(!empty($proinfo['tmpamt']))
							{
								$tmpsum_t += $proinfo['tmpamt'];
							}
							else
							{
								$tmpsum_t += $proinfo['siteAmt']*$numArr[$proinfo['id']];
							}
							
						}
						
						if($tmpsum>=intval($row['var01'])){
							$a=$tmpsum_t;
							$tmpsum_t=round($tmpsum_t*$row['var02']/100);
							
							if($allprodiscount < ($a-$tmpsum_t) )
							{
								$allprodiscount = $a-$tmpsum_t; //目前最大折價
								$allproactive_arr = array("id"=>$row['id'],"name"=>$row['name'],"amt"=>($amt-$allprodiscount),"discount"=>$allprodiscount,"all"=>1);
							}
						}
					}
				}
			}
		}
	}
	
	
	
	if(!empty($allproactive_arr))
	{
		$active_list[] = $allproactive_arr;
		
		$discount+=$allprodiscount; //目前總折價
		$amt-=$allprodiscount;	//目前總額
	}
	
	//運費計算
	foreach($active_rank as $active_num){
		if(count($active[$active_num])>0 && $loopable){
			foreach($active[$active_num] as $row){
				if($active_num==4 && $amt>=intval($row['var01'])){
					$disDlvrAmt=$_SESSION[$conf_user]['dlvrAmt'];
					$active_list[]=array("id"=>$row['id'],"name"=>$row['name'],"amt"=>$amt,"discount"=>$disDlvrAmt,"dlvrfee"=>1);
				}
			}
		}
	}
	
	//合併重覆的活動
	$active_list0=array();
	foreach($active_list as $k=>$v){
		if($active_list0[$v['id']]){
			$active_list0[$v['id']]['amt']+=$v['amt'];
			$active_list0[$v['id']]['discount']+=$v['discount'];
		}else{
			$active_list0[$v['id']]=$v;
		}
	}
	$active_list=arrayRmKey($active_list0);
	*/
	
	
	
}


//折扣計算
function saleCalc2($proArr, $carchk = false){
	global $db,$conf_user;
	
	if(count($proArr)==0)return false;
	
	$amt=0;
	$total=0;
	
	$cartpro=$proArr;
	$cartpro2=$proArr;
	
	$data=array();
	
	$where_str = "";
	if($carchk)	//只檢查免運活動
	{
		$where_str = " AND A.activePlanid = '10'";
	}
	
	//撈取現有活動
	$today=date("Y-m-d H:i");
	$sql = " SELECT A.*,B.type AS ptype FROM active A , activePlans B  WHERE A.activePlanid = B.id AND A.publish = '1' 
			 AND ( A.sdate<='$today' OR A.sdate='') AND ( A.edate>='$today' OR A.edate='') $where_str
			 ORDER BY A.odring, A.actRangePCode DESC, B.type ASC, A.id ASC ";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$active=array();
	$des_active=array(); //指定商品活動
	$all_active=array(); //全館商品活動
	foreach($r as $row){
		$active[$row['id']]=$row;
		if($row['ptype'] == 1)
		{
			if($row['activePlanid'] == 12)
			{
				$all_active[1][12][$row['id']] = $row;
			}
			else
			{
				$all_active[1][$row['ptype']][$row['id']] = $row;
			}
			
		}
		else
		{
			$all_active[$row['actRangePCode']][$row['ptype']][$row['id']] = $row;
		}
	}
	
	ksort($all_active);
		
	$sql = "select coin_to,coin_take from siteinfo";
	$db->setQuery( $sql );
	$r=$db->loadRow();
	$coin_to=$r['coin_to'];
	$coin_take=$r['coin_take'];
	
	$active_list=array();
	$discount=0;
	$discountFree=0;
	$disDlvrAmt=0;
	$loopable=true;
	$otherCalc=true;//剩下的商品是否計算非折上折活動

	$usePro=array();
	$calcPro=$proArr;
	$tmpActive=array();
	$tmpproamt=0;
	
	$allprodiscount = 0;	//全館活動最大折價
	$allproactive_arr = array();
	
	//整理購物車商品
	if(count($proArr) > 0)
	{
		$allcart = array('totalAmt'=>0,'totalNum'=>0);
		$cartpro = array();
		foreach($proArr as $row)
		{
			if(count($cartpro[$row['id']]) == '0')
			{
				$info = array();
				$info['id'] = $row['id'];
				$info['imgname'] = $row['imgname'];
				$info['num'] = 0;
				$info['siteAmt'] = $row['siteAmt'];
				$info['CalcHighAmt'] = 0;
				$info['CalcSiteAmt'] = 0;
				$info['item'] = array();
				
				$cartpro[$row['id']] = $info;
			}
			
			$cartpro[$row['id']]['num'] += $row['num'];
			$cartpro[$row['id']]['CalcHighAmt'] += $row['CalcHighAmt'];
			$cartpro[$row['id']]['CalcSiteAmt'] += $row['CalcSiteAmt'];
			
			$info = array();
			$info['num'] = $row['num'];
			$info['format1'] = $row['format1'];
			$info['format2'] = $row['format2'];
			$info['format1title'] = $row['format1title'];
			$info['format2title'] = $row['format2title'];
			$info['format1name'] = $row['format1name'];
			$info['format2name'] = $row['format2name'];
			$info['name'] = $row['name'];
			
			$cartpro[$row['id']]['item'][] = $info;
			
			$allcart['totalAmt'] += $row['siteAmt']*$row['num'];
			$allcart['totalNum'] += $row['num'];
			
		}
	}
	
	$amt = $allcart['totalAmt'];	//購物車總額
	$total=$amt;
	$act_cnt = 0;
	$tmp_actproArr = array();	//有參與活動的商品編號
	
	$tmp_cartpro_1 = array();
	$tmp_cartpro_1_chk = false;
	
	//單品折扣活動優先計算
	if(count($all_active) > 0)
	{
		foreach($all_active as $actRangePCode=>$info)
		{
			$tmp_active = $info;
			foreach($tmp_active as $ptype=>$row)
			{
				$tmp_cartpro = $cartpro;
				
				if($ptype == 12 && $tmp_cartpro_1_chk)
				{
					$tmp_cartpro = $tmp_cartpro_1;
				}
				
				while((count($tmp_active[$ptype]) > 0) && (count($tmp_cartpro) > 0) && $act_cnt < 50)
				{
					$act_cnt++;
					
					//計算同一類別最大折扣的活動
					if(count($tmp_active[$ptype]) > 0)
					{
						$tmp_actid = 0;	//有最大折扣的活動
						$tmp_disAmt = 0; //最大折扣數
						$tmp_usepro = array();	//有最大折扣的活動包含的商品編號
						$tmp_dispro = array();	//第二件N折有打折的商品編號
						$tmp_freeCnt = 0; //符合贈品次數
						
						
						foreach($tmp_active[$ptype] as $act)
						{
							$arr = ComputeDesActve($allcart,$tmp_cartpro,$act,$act['actRangePCode'],$discount,$discountFree);
							
							$disAmtMax = ( intval($arr['disAmtMax']) > 0 ) ? $arr['disAmtMax'] : $arr['disAmt'];
							
							if(count($arr) > 0 && (( $disAmtMax > $tmp_disAmt) || (!empty($arr['actpid']))))
							{
								$tmp_actid = $act['id'];
								$tmp_disAmt = $arr['disAmt'];
								$tmp_usepro = $arr['usepro'];
								$tmp_act = $act;
								if(count($arr['dispro']) > 0)
								{
									$tmp_dispro = $arr['dispro'];
								}
								else
								{
									$tmp_dispro = array();
								}
								$tmp_freeCnt = $arr['freeCnt'];
								$tmp_actproArr = $arr['actproArr'];
							}
						}
						
						if(!empty($tmp_actid) && count($tmp_active[$ptype][$tmp_actid]) > 0)
						{
							if($active[$tmp_actid]['ptype'] == '2')	//加價購
							{
								//可加價購商品
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$addAmtPro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$proinfo['bid'] = getFieldValue(" SELECT A.ptid FROM protype A, producttype B WHERE A.ptid = B.id AND A.pid = '".$proinfo['id']."' AND B.pagetype = 'page'","ptid");
										$proinfo['format'] =getProductFormat($proinfo['id']);
										$proinfo['img']=getimg('products',$proinfo['id'],1);
										$addAmtPro[] = $proinfo;
									}
								}
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"addAmtPro"=>$addAmtPro,"usepro"=>$tmp_usepro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							elseif($active[$tmp_actid]['ptype'] == '3')	//贈品
							{
								$discountFree += $tmp_freeCnt * intval($active[$tmp_actid]['var01']);	//門檻金額
								$var02 = intval($active[$tmp_actid]['var02']);	//數量
								$var04 = $active[$tmp_actid]['var04'];
								$where_str = str_replace("||",",",$var04);
								$sql = " SELECT * FROM products WHERE id IN (''".$where_str."'')";
								$db->setQuery($sql);
								$list = $db->loadRowList();
								$freePro = array();
								if(count($list) > 0)
								{
									foreach($list as $proinfo)
									{
										$tmp_var02 = $var02;
										if($tmp_freeCnt > 1)
										{
											$tmp_var02 = (int)($tmp_freeCnt) * $var02;
										}
										
										for($i = 1 ; $i<= $tmp_var02 ; $i++)
										{
											$proinfo['fid'] = $proinfo['id']."|||0|||".$tmp_actid."|||".$i;
											$proinfo['format'] =getProductFormat($proinfo['id']);
											$proinfo['img']=getimg('products',$proinfo['id'],1);
											$freePro[] = $proinfo;
										}
									}
								}
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"freePro"=>$freePro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							elseif($active[$tmp_actid]['ptype'] == '4')	//免運活動
							{
								//符合免運活動
								$disDlvrAmt=$_SESSION[$conf_user]['dlvrAmt'];
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$disDlvrAmt,"dlvrfee"=>1,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							elseif($active[$tmp_actid]['ptype'] == '5')	//紅利活動
							{
								//符合紅利活動
								$var02 = intval($active[$tmp_actid]['var02']);	//紅利百分比
								$var03 = $active[$tmp_actid]['var03'];	//指定商品
								
								if(empty($var03))
								{
									$tmp_usepro = array();
								}
								
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>0,"bonus"=>$var02,"bonuspro"=>$tmp_usepro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
							}
							else
							{
								$discount += $tmp_disAmt; //目前總折價
										
								//紀錄活動
								$active_list[]=array("id"=>$tmp_actid,"name"=>$active[$tmp_actid]['name'],"amt"=>$amt,"discount"=>$tmp_disAmt,"usepro"=>$tmp_usepro,"dispro"=>$tmp_dispro,"act"=>$tmp_act,"actproArr"=>$tmp_actproArr);
									
								$amt=$amt-($tmp_disAmt);	//目前總額
							}
							
							if(count($tmp_dispro) > 0)
							{
								//將使用過的商品撈出來
								foreach($tmp_dispro as $proid)
								{
									if(intval($tmp_cartpro[$proid]['num']) > 1)
									{
										$tmp_cartpro[$proid]['num'] = intval($tmp_cartpro[$proid]['num']) - 1;
									}
									else
									{
										unset($tmp_cartpro[$proid]);
									}
								}
							}
							else if($active[$tmp_actid]['ptype'] == '3') //贈品不用
							{
								
							}
							else
							{
								//將使用過的商品撈出來
								foreach($tmp_usepro as $proid)
								{
									unset($tmp_cartpro[$proid]);
								}
							}
							
							
							
							if($ptype == 1)
							{
								$tmp_cartpro_1_chk = true;
								$tmp_cartpro_1 = $tmp_cartpro;
							}
							
							//將使用過的活動撈出來
							unset($tmp_active[$ptype][$tmp_actid]);
							
						}
						else
						{
							break;
						}
					}
				}
			}
		}
	}
	
	if($coin_to){
		$free_coin=floor(($amt-intval($_SESSION[$conf_user]['usecoin']))/$coin_to)*$coin_take;
	}
	
	$data['amt']=$amt;
	$data['total']=$total;
	$data['active_list']=$active_list;
	$data['discount']=$discount;
	$data['disDlvrAmt']=$disDlvrAmt;
	$data['usecoin']=intval($_SESSION[$conf_user]['usecoin']);
	$data['free_coin']=$free_coin;
	
	
	return $data;
	
}


//產生網頁資料庫的路徑
function getDBPageLink($linktype,$url,$tb,$id){
	global $db;
	
	if ($linktype == "link" || !$linktype) {
		$linkurl=$url?$url:'javascript:void(0)';
	} else if ($linktype=="database") {
		$sql="select * from dbpageLink where fromtable='$tb' and fromid='$id'";
		$db->setQuery( $sql );
		$r = $db->loadRow();
		if($r){
			$totable=$r['totable'];
			if($r['pageid']){
				$pageid=$r['pageid'];
				$dirid=$r['dirid'];
				$pagetype="page";
			}else if($r['dirid']){
				$pageid=getFieldValue("select id from {$r['totable']} where belongid='{$r['dirid']}' order by odring,id","id");
				$dirid=$r['dirid'];
				$pagetype="list";
			} else {
				$pageid = 0;
				$dirid = 0;
				if(fieldExist($r['totable'], "belongid")) {
					if(fieldExist($r['totable'], "pagetype")) {
						$pagetypestr = " and pagetype='page' ";
					}
					$pageid=intval(getFieldValue("select id from {$r['totable']} where belongid='root' $pagetypestr order by odring,id","id"));
				}
				$pagetype="root";
			}
		}
		
		switch ($totable) {
			case "producttype":
				$linkurl="product_list/".$dirid."?id=".$pageid;
				break;
			case "products":
				$linkurl="bonus_list/".$dirid."?id=".$pageid;
				break;	
			case "news":
				if($pagetype=="root"){
					$linkurl="news_list?cur=1";
				}else{
					$linkurl="news_page?id=".$pageid."&cur=1";
				}
				break;
			case "treemenus":
				$linkurl="dbpage_page/".$dirid."?id=".$pageid;
				break;
		}
	}
	
	
	
	return $linkurl;
}

//檢查購物車的商品是否合法
function chkCartPro(){
	global $db,$conf_user;
	$tablename="products";
	$mode=getCartMode();
	$cart=$_SESSION[$conf_user]['{$mode}_list'];
	$ProFormatList=getProFormatList();
	if(count($cart)>0){
		$sql="select id from $tablename where publish=1";
		$db->setQuery( $sql );
		$r = $db->loadRowList();
		$a=array();
		
		foreach($r as $row){
			$a[$row['id']]=1;
		}
		
		foreach($cart as $fid=>$num){
			if($a[$ProFormatList[$fid]['pid']]!=1){
				$_SESSION[$conf_user]['cart_list'][$fid]=null;
				unset($_SESSION[$conf_user]['cart_list'][$fid]);
			}
		}
		
	}
}


function AddCartProductInfo($cart,$realCart=array(),$addToCart=false,$proType = 'amtPro'){
	global $db,$conf_user;
	$tablename="products";
	if(count($cart)==0 || count($realCart)==0){
    	return $realCart;
    }
	
	$pidArr=array();
    $numArr=array();
	foreach($cart as $pid=>$row){
		$pidArr[]=$pid;
		$numArr[$pid]=$row;
	}
	
	if($proType == 'freePro')
	{
		$where_str = "AND amtProChk=1";
	}
	else
	{
		$where_str = "AND amtProChk=1";
	}
	
	
	$sql="select id,name,highAmt,amtProAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND amtProChk=1";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$total=0;
	$proArr=array();
	foreach($numArr as $pid=>$row){
		
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		if(is_array($row)){
			
			foreach($row as $format1=>$row2){
				$f1=getProductFormat($pid);
				if(is_array($row2)){
					
					foreach($row2 as $format2=>$row3){
						
						$data=array();
						$data['id']=$pid;
						$data['imgname']=$imgname;
						$data['num']=$row3;
						
						
						$data['siteAmt']=$info['siteAmt'];
						
						$data['highAmt']=$info['highAmt'];
						$data['CalcHighAmt']=$data['num']*$data['highAmt'];
						$data['CalcSiteAmt']=$data['num']*$data['siteAmt'];
						$total+=$data['CalcSiteAmt'];
						$data['format']=$f1;
						$profr='';
						if($format1 && $format2){
							$data['format1']=$format1;
							$data['format2']=$format2;
							$data['format1title']=$f1['format1title'];
							$data['format2title']=$f1['format2title'];
							
							foreach($f1['format1'] as $f1arr){
								if($f1arr['id']==$format1){
									$data['format1name']=$f1arr['name'];
									break;
								}
							}
							foreach($f1['format2'][$format1] as $f2arr){
								if($f2arr['id']==$format2){
									$data['format2name']=$f2arr['name'];
									break;
								}
							}
							$profr="【{$data['format1name']} - {$data['format2name']}】";
						}
						$data['name']="【加購品】".$info['name'].$profr;
						$proArr[]=$data;
						
					}
				}else{
					$data=array();
					$data['id']=$pid;
					$data['imgname']=$imgname;
					$data['name']="【加購品】".$info['name'];
					$data['num']=$row2;
					$data['siteAmt']=$info['siteAmt'];
					
					$data['highAmt']=$info['highAmt'];
					$data['CalcHighAmt']=$data['num']*$info['highAmt'];
					$data['CalcSiteAmt']=$data['num']*$data['siteAmt'];
					$total+=$data['CalcSiteAmt'];
					if($format1){
						$data['format1']=$format1;
						$data['format1title']=$f1['format1title'];
						$data['format']=$f1;
						foreach($f1['format1'] as $f1arr){
							if($f1arr['id']==$format1){
								$data['format1name']=$f1arr['name'];
								break;
							}
						}
					}
					$proArr[]=$data;
				}
			}
		}else{
			$data['id']=$pid;
			$data['imgname']=$imgname;
			$data['name']=$info['name'];
			$data['siteAmt']=$info['siteAmt'];
			$data['highAmt']=$info['highAmt'];
			$data['num']=$row;
			$data['CalcHighAmt']=$data['num']*$info['highAmt'];
			$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
			$total+=$data['CalcSiteAmt'];
			$proArr[]=$data;
		}
	}
	if($addToCart){
		foreach($proArr as $row){
			$row['addProChk']=1;
			$realCart['data'][]=$row;
		}
	}
	
	$realCart['amt']=$realCart['amt']+$total;
	$realCart['total']=$realCart['total']+$total;
	
	$total=$realCart['total'];
	$amt=$realCart['amt'];
	$active_list=$realCart['active_list'];
	$discount=$realCart['discount'];
	$disDlvrAmt=$realCart['disDlvrAmt'];
	$usecoin=$realCart['usecoin'];
	$free_coin=$realCart['free_coin'];
	return array("data"=>$realCart,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin);
}


/*
	idtype:
		id->取得id=bid的資料
		pid->取得pid=bid的資料
*/
function getProFormatList($bid=0,$idtype=null,$getDtl=false){
	global $db;
	
	$where_str="";
	if($bid && $idtype=='id'){
		$where_str.=" AND id='$bid'";
	}else if($bid && $idtype=='pid'){
		$where_str.=" AND pid='$bid'";
	}
	$select_str="";
	if($getDtl){
		$select_str.=",(select name from proformat where id=format1_type) as format1title";
		$select_str.=",(select name from proformat where id=format2_type) as format2title";
		$select_str.=",(select name from proformat where id=format1) as format1name";
		$select_str.=",(select name from proformat where id=format2) as format2name";
	}
	
	$sql="select id,pid,format1_type,format2_type,format1,format2,safetystock,instock,odring $select_str from proinstock where 1=1 $where_str";
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$data=array();
	foreach($r as $row){
		$data[$row['id']]=$row;
	}
	
	
	
	return $data;
}

function CartProductInfo2($cart,$activeChk = null){
	global $db,$conf_user;
	$tablename="products";
	
	$mode=getCartMode();
	
    if(count($cart)==0){
    	if($mode == 'amtpro' || $mode == 'freepro')
		{
    		unset($_SESSION[$conf_user]["cart_list_mode"]);
    		JsonEnd(array("status" => 1));
    	}
    	else
    	{    		
    		unset($_SESSION[$conf_user]["cart_list_mode"]);
    		unset($_SESSION[$conf_user]["amtpro_list"]);
    		unset($_SESSION[$conf_user]["freepro_list"]);
			unset($_SESSION[$conf_user]['disDlvrAmt']);
			unset($_SESSION[$conf_user]['dlvrAmt']);
			unset($_SESSION[$conf_user]['usecoin']);
			unset($_SESSION[$conf_user]['proArr']);
			unset($_SESSION[$conf_user]['realDlvrAmt']);
			unset($_SESSION[$conf_user]['totalAmt']);
    		JsonEnd(array("status" => 0, "msg"=>"購物車為空","cnt"=>0));
    	}
    }
    $pidArr=array();
    $numArr=array();
    $plist=getProFormatList(0,null,true);
	foreach($cart as $pid=>$row){
		$pid=explode("|||",$pid);
		$pidArr[]=$pid[0];
	}
	$total=0;
	$carchk = false; //只檢查運費活動
	if($mode=="cart"){
		if($activeChk == 'false')
		{
			$discountAble=false;
			$carchk = true;
		}
		else
		{
			$discountAble=true;
		}
		$sql="select id,name,highAmt,siteAmt,pv,bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode=="bonus"){
		$discountAble=false;
		$sql="select id,name,highAmt,bonusAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode == "amtpro")
	{
		$discountAble=false;
		$sql="select id,name,highAmt,amtProAmt as siteAmt,'0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND amtProChk = 1";
	}else if($mode == "freepro")
	{
		$discountAble=false;
		$sql="select id,name,highAmt,'0' as siteAmt,'0' as pv,'0' as bv,'0' as bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1 AND freeProChk = 1";
	}
	
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$proArr=array();
	foreach($cart as $fid=>$num){
		$orifid = $fid;
		$fid=explode("|||",$fid);
		$pid=$fid[0];
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		
		$data['id']=$pid;
		$data['imgname']=$imgname;
		$data['name']=$info['name'];
		$data['siteAmt']=$info['siteAmt'];
		$data['pv']=$info['pv'];
		$data['bv']=$info['bv'];
		$data['bonus']=$info['bonus'];
		$data['num']=$num;
		$data['fid']=$orifid;
		$data['CalcHighAmt']=$data['num']*$info['highAmt'];
		$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
		$total+=$data['CalcSiteAmt'];
		
		if($fid[1]){
			$format1=$plist[$fid[1]]['format1'];
			
			if($format1){
				$data['format1']=$format1;
				$data['format1title']=$plist[$fid[1]]['format1title'];
				$data['format1name']=$plist[$fid[1]]['format1name'];
				$data['format1instock']=$plist[$fid[1]]['instock'];
			}
			$format2=$plist[$fid[1]]['format2'];
			if($format2){
				$data['format2']=$format2;
				$data['format2title']=$plist[$fid[1]]['format2title'];
				$data['format2name']=$plist[$fid[1]]['format2name'];
				$data['format2instock']=$plist[$fid[1]]['instock'];
			}
			
			$data['name']=$info['name'];
			if($data['format1name']){
				$data['name'].="【";
				$data['name'].=$data['format1name'];
				
				if($data['format2name']){
					$data['name'].=" - ";
					$data['name'].=$data['format2name'];
				}
				
				$data['name'].="】";
			}
		}
		
		if($fid[2])
		{
			$data['activeName'] = getFieldValue(" SELECT * FROM active WHERE id = '".$fid[2]."'","name");
		}
		
		$proArr[]=$data;
	}
	
	if($discountAble || $carchk){
		$calc=saleCalc2($proArr, $carchk);
	}else{
		$calc=array(
				"total"=>$total,
				"amt"=>$total,
				"discount"=>0,
				"disDlvrAmt"=>0,
				"usecoin"=>0,
				"free_coin"=>0,
				"active_list"=>array()
			);
	}
	
	//計算紅利活動
	/*
	if($mode=="cart")
	{
		if(count($calc['active_list']) > 0)
		{
			foreach($calc['active_list'] as $row)
			{
				if(!empty($row['bonus']))
				{
					if(count($row['bonuspro']) > 0)	//指定商品有紅利
					{
						foreach($row['bonuspro'] as $pid)
						{
							foreach($proArr as $key=>$pro)
							{
								if($pro['id'] == $pid)
								{
									$proArr[$key]['bonus'] += ($pro['siteAmt'] * $row['bonus'] * 0.01);
								}
							}
						}
					}
					else //所有商品有紅利
					{
						foreach($proArr as $key=>$pro)
						{
							$proArr[$key]['bonus'] += ($pro['siteAmt'] * $row['bonus'] * 0.01);
						}
					}
				}
			}
		}
	}
	*/
	
	$total=$calc['total'];
	$amt=$calc['amt'];
	$active_list=$calc['active_list'];
	$discount=$calc['discount'];
	$disDlvrAmt=$calc['disDlvrAmt'];
	$usecoin=$calc['usecoin'];
	$free_coin=$calc['free_coin'];
	
	return array("data"=>$proArr,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin);
}

//購物車商品資訊
function CartProductInfo($cart){
	global $db,$conf_user;
	$tablename="products";
	
	$mode=getCartMode();
    if(count($cart)==0){
    	unset($_SESSION[$conf_user]["cart_list_mode"]);
    	JsonEnd(array("status" => 0, "msg"=>"購物車為空","cnt"=>0));
    	
    }
    $pidArr=array();
    $numArr=array();
	foreach($cart as $pid=>$row){
		$pidArr[]=$pid;
		$numArr[$pid]=$row;
	}
	$total=0;
	if($mode=="cart"){
		$discountAble=true;
		$sql="select id,name,highAmt,siteAmt,pv,bv,bonus from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}else if($mode=="bonus"){
		$discountAble=false;
		$sql="select id,name,highAmt,bonusAmt as siteAmt from $tablename where id in (".implode(",",$pidArr).") AND publish=1";
	}
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	
	$proInfoArr=array();
	foreach($r as $row){
		$proInfoArr[$row['id']]=$row;
	}
	$proArr=array();
	foreach($numArr as $pid=>$row){
		
		$data=array();
		$imgname=getimg($tablename,$pid,1);
		$info=$proInfoArr[$pid];
		if(is_array($row)){
			
			foreach($row as $format1=>$row2){
				$f1=getProductFormat($pid);
				if(is_array($row2)){
					
					foreach($row2 as $format2=>$row3){
						
						$data=array();
						$data['id']=$pid;
						$data['imgname']=$imgname;
						$data['num']=$row3;
						$data['siteAmt']=$info['siteAmt'];
						$data['pv']=$info['pv'];
						$data['bv']=$info['bv'];
						$data['bonus']=$info['bonus'];
						
						$data['CalcHighAmt']=$data['num']*$info['highAmt'];
						$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
						$data['format1']=$format1;
						$data['format2']=$format2;
						$data['format1title']=$f1['format1title'];
						$data['format2title']=$f1['format2title'];
						$total+=$data['CalcSiteAmt'];
						foreach($f1['format1'] as $f1arr){
							if($f1arr['id']==$format1){
								$data['format1name']=$f1arr['name'];
								break;
							}
						}
						foreach($f1['format2'][$format1] as $f2arr){
							if($f2arr['id']==$format2){
								$data['format2name']=$f2arr['name'];
								break;
							}
						}
						$data['name']=$info['name']."【{$data['format1name']} - {$data['format2name']}】";
						$proArr[]=$data;
						
					}
				}else{
					$data=array();
					$data['id']=$pid;
					$data['imgname']=$imgname;
					$data['name']=$info['name'];
					$data['num']=$row2;
					$data['siteAmt']=$info['siteAmt'];
					$data['pv']=$info['pv'];
					$data['bv']=$info['bv'];
					$data['bonus']=$info['bonus'];
					$data['CalcHighAmt']=$data['num']*$info['highAmt'];
					$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
					$total+=$data['CalcSiteAmt'];
					$data['format1']=$format1;
					$data['format1title']=$f1['format1title'];
					foreach($f1['format1'] as $f1arr){
						if($f1arr['id']==$format1){
							$data['format1name']=$f1arr['name'];
							break;
						}
					}
					$proArr[]=$data;
				}
			}
		}else{
			$data['id']=$pid;
			$data['imgname']=$imgname;
			$data['name']=$info['name'];
			$data['siteAmt']=$info['siteAmt'];
			$data['pv']=$info['pv'];
			$data['bv']=$info['bv'];
			$data['bonus']=$info['bonus'];
			$data['num']=$row;
			$data['CalcHighAmt']=$data['num']*$info['highAmt'];
			$data['CalcSiteAmt']=$data['num']*$info['siteAmt'];
			$total+=$data['CalcSiteAmt'];
			$proArr[]=$data;
		}
	}
	
	if($discountAble){
		$calc=saleCalc($proArr,$numArr,$discountAble);
	}else{
		$calc=array(
				"total"=>$total,
				"amt"=>$total,
				"discount"=>0,
				"disDlvrAmt"=>0,
				"usecoin"=>0,
				"free_coin"=>0,
				"active_list"=>array()
			);
	}
	
	$total=$calc['total'];
	$amt=$calc['amt'];
	$active_list=$calc['active_list'];
	$discount=$calc['discount'];
	$disDlvrAmt=$calc['disDlvrAmt'];
	$usecoin=$calc['usecoin'];
	$free_coin=$calc['free_coin'];
	return array("data"=>$proArr,"total"=>$total,"amt"=>$amt,"active_list"=>$active_list,"discount"=>$discount,"disDlvrAmt"=>$disDlvrAmt,"usecoin"=>$usecoin,"free_coin"=>$free_coin);
}


function take_type($getamt=null,$getname=null){
	global $db,$conf_user;
	$mode=getCartMode();
	$data=array();
	$data2=array();
	$sql="select * from payconf";
	$db->setQuery( $sql );
	$r = $db->loadRow();
	$pay_type=$_SESSION[$conf_user]['pay_type'];
	if(($r['homeDlvr']==1 && ( $pay_type==2 || $pay_type==3 || $pay_type==4)) || $getname || $getamt){
		$data[1]=array("id"=>1,"name"=>"宅配","amt"=>$r['homeDlvrAmt']);
	}
	$data2[1]=array("id"=>1,"name"=>"宅配","amt"=>$r['homeDlvrAmt']);
	
	if(($r['selfDlvr']==1 && ($pay_type==2 || $pay_type==3 || $pay_type==4 || $pay_type==5)) || $getname || $getamt){
		//$data[2]=array("id"=>2,"name"=>"門市自取","amt"=>0);
	}
	$data2[2]=array("id"=>2,"name"=>"門市自取","amt"=>0);
	
	if(($r['dlvrPay']==1 && $pay_type==1) || $getname || $getamt){
		$data[3]=array("id"=>3,"name"=>"貨到付款","amt"=>$r['dlvrAmt']);
	}
	$data2[3]=array("id"=>3,"name"=>"貨到付款","amt"=>$r['dlvrAmt']);
	
	if($getname){
		return $data2[$getname]['name'];
	}
	$take_type=1;
	if($pay_type==5){
		$take_type=2;
	}
	if($pay_type==1){
		$take_type=3;
	}
	$dlvrAmt=0;
	if($_SESSION[$conf_user]['take_type']){
		foreach($data as $key=>$row){
			if($row['id']==$_SESSION[$conf_user]['take_type']){
				$take_type=$row['id'];
				$dlvrAmt=$row['amt'];
				
			}
		}
	}
	
	if($getamt){
		return $dlvrAmt;
	}
	$cart=$_SESSION[$conf_user]["{$mode}_list"];
	$proArr=CartProductInfo2($cart);
	
	JsonEnd(array("status" => 1, "data" =>$data,"take_type"=>$take_type,"dlvrAmt"=>intval($dlvrAmt)-intval($proArr['disDlvrAmt']),"disDlvrAmt"=>intval($proArr['disDlvrAmt'])));
}

function pay_type($getname=null){
	global $db,$conf_user;
	
	$data=array();
	$data2=array();
	$sql="select * from payconf";
	$db->setQuery( $sql );
	$r = $db->loadRow();
	if($r['dlvrPay']==1){
		$data[1]=array("id"=>1,"name"=>"貨到付款");
	}
	$data2[1]=array("id"=>1,"name"=>"貨到付款");
	
	if($r['bankPay']==1){
		$data[2]=array("id"=>2,"name"=>"ATM匯款");
	}
	$data2[2]=array("id"=>2,"name"=>"ATM匯款");
	
	if($r['creditallPay']==1){
		$data[3]=array("id"=>3,"name"=>"線上刷卡");
	}
	$data2[3]=array("id"=>3,"name"=>"線上刷卡");
	
	if($r['vanallPay']==1){
		$data[4]=array("id"=>4,"name"=>"ATM虛擬帳號");
	}
	$data2[4]=array("id"=>4,"name"=>"ATM虛擬帳號");
	//$data[5]=array("id"=>5,"name"=>"店取付現");
	$data2[5]=array("id"=>5,"name"=>"店取付現");
	if($getname){
		return $data2[$getname]['name'];
	}
	
	if($r['dlvrPay']==1){
		$pay_type=1;
	}
	elseif($r['bankPay']==1)
	{
		$pay_type=2;
	}
	elseif($r['creditallPay']==1)
	{
		$pay_type=3;
	}
	else
	{
		$pay_type=4;
	}
	
	
	if($_SESSION[$conf_user]['pay_type']){
		foreach($data as $key=>$row){
			if($row['id']==$_SESSION[$conf_user]['pay_type']){
				$pay_type=$row['id'];
			}
		}
	}else{
		$_SESSION[$conf_user]['pay_type']=$pay_type;
	}
	JsonEnd(array("status" => 1, "data" =>$data,"pay_type"=>$pay_type));
}

function getdbpagelinkdata($tablename, $fromid=0){
	global $db;
	$dbpageDate = array();
	if($fromid){
	
		$sql = "select * from dbpageLink where fromtable='$tablename' AND fromid = '$fromid'";
		$db->setQuery($sql);
		$r = $db->loadRow();
		if($r) {
			$dbpageDate['tablename'] = $r['totable'];
			$dbpageDate['databaseid'] = $r['pageid'] ? $r['pageid'] : $r['dirid'];
			$dbpageDate['databasename'] = $r['name'];
		}
	}
	return $dbpageDate;
}

function getProductFormat($id=0){
	global $db,$conf_user;
	
	$dataArr=array();
	if($id==0){
		return $dataArr;
	}

	$sql=" SELECT * FROM ( select 
			A.format1,(select name from proformat where id=A.format1) as name1,(select name from proformat where id=A.format1_type) as title1,
			A.format2,(select name from proformat where id=A.format2) as name2,(select name from proformat where id=A.format2_type) as title2,
			A.instock , B.odring
		  from proinstock A LEFT JOIN proformat B ON A.format2 = B.id 
		  where A.pid='$id' ) AS tbl order by odring ";
	
	$db->setQuery( $sql );
	$r = $db->loadRowList();
	$format1Arr=array();
	$format2Arr=array();
	foreach($r as $row){
		$format1=intval($row['format1']);
		$format2=intval($row['format2']);
		$name1=$row['name1'];
		$name2=$row['name2'];
		$title1=$row['title1'];
		$title2=$row['title2'];
		$instock=intval($row['instock']);
		
		if($instock>0 || true){
			$format1Arr[$format1]['id']=$format1;
			$format1Arr[$format1]['name']=$name1;
			$format2Arr[$format1][$format2]['id']=$format2;
			$format2Arr[$format1][$format2]['name']=$name2;
			$format2Arr[$format1][$format2]['instock']=$instock;
			$format2Arr2[$format1][] = array('id'=>$format2,'name'=>$name2,'instock'=>$instock);
		}
		
	}
	
	
	$tmp=array();
	foreach($format1Arr as $row){
		$tmp[]=$row;
	}
	$format1Arr=$tmp;
	
	$dataArr['format1title']=$title1;
	$dataArr['format1']=$format1Arr;
	$dataArr['format2title']=$title2;
	$dataArr['format2']=$format2Arr;
	$dataArr['format22']=$format2Arr2;
	
	$dataArr['formatonly'] = false;
	if(count($format1Arr) == 1 && count($format2Arr) == 1)
	{
		if(count($format2Arr[$format1Arr[0]['id']]) == 1)
		{
			$dataArr['formatonly'] = true;
			$dataArr['format1only'] = $format1Arr[0];
			
			foreach($format2Arr[$format1Arr[0]['id']] as $row)
			{
				$dataArr['format2only'] = $row;
			}
		}
		else
		{
			$dataArr['formatonly'] = true;
			$dataArr['format1only'] = $format1Arr[0];
		}
	}
	
	return $dataArr;
}

function LoginChk(){
	global $conf_user;
	$uid=intval($_SESSION[$conf_user]['uid']);
	if($uid==0){
		JsonEnd(array("status" => 0, "msg" =>"請先登入"));
	}
	return $uid;
}


//取得後台管理權限
function getUserPermission(){
	global $conf_user;
	$uid=intval($_SESSION[$conf_user]['uid']);
	$functionsCht = getFieldValue(" SELECT functionsCht FROM adminmanagers WHERE locked ='0' AND id='$uid' ","functionsCht");
	
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
				}
			}
		}
	}
	return $funclist;
}

//後台管理權限檢查
function userPermissionChk($func){
	global $conf_user;
	
	$arrJson = array();
	
	
	if(empty($func))
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
		JsonEnd($arrJson);
	}
	
	
	if(count($_SESSION[$conf_user]['funclist']) == 0 || true)
	{
		$_SESSION[$conf_user]['funclist'] = getUserPermission();
	}
	

	if(!empty($_SESSION[$conf_user]['funclist']) && count($_SESSION[$conf_user]['funclist']) > 0)
	{
		$funclist = $_SESSION[$conf_user]['funclist'];
		
		//無項目紀錄
		if(count($funclist[$func]) == 0)
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
		
		//無項目權限
		if($funclist[$func]["C"] == "false" && $funclist[$func]["U"] == "false" && $funclist[$func]["D"] == "false" && $funclist[$func]["R"] == "false")
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
		
		$task = global_get_param( $_REQUEST, 'task', null ,0,1  );
		
		//列表搜尋
		if($task == 'list')
		{
			$search = global_get_param( $_REQUEST, 'search', null);
			if(!empty($search))
			{
				$task = 'search';
			}
		}
		
		//批次操作
		if($task == 'operate')
		{
			$action = intval(global_get_param( $_REQUEST, 'action', null ,0,1  ));
			if($action == '3')
			{
				$task = 'operate_D';
			}
			else
			{
				$task = 'operate_U';
			}
		}
		
		$id = intval(global_get_param( $_REQUEST, 'id', null ,0,1  ));
		$task = ($task == "update" && empty($id)) ? 'add' : $task;
		
		switch ($task) {
			case "add": //新增紀錄
				$Permission = 'C';
				break;
			case "update": //更新紀錄
			case "operate_U":	//批次處理
			case "publishChg":	//更新狀態
				$Permission = 'U';
				break;
			case "del": //刪除紀錄
			case "operate_D":	//批次處理
				$Permission = 'D';
				break;
			case "search": //列表搜尋
				$Permission = 'R';
				break;
			default:
				$Permission = 'R';
				break;
		}
		
		if($funclist[$func][$Permission] != 'true')
		{
			$arrJson['status'] = "0";
			$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
			JsonEnd($arrJson);
		}
	}
	else
	{
		$arrJson['status'] = "0";
		$arrJson['msg'] = _COMMON_ERRORMSG_NET_ERR;
		JsonEnd($arrJson);
	}
	

}

function createUpdateSql($tablename, $dataArr) {
	$updatesql = "INSERT INTO $tablename (";
	$updateval = " VALUES (";
	$updateend = " ON DUPLICATE KEY UPDATE ";
	$i = 0;

	foreach($dataArr as $key => $val) {
		if(isset($val)) {
			if(++$i > 1) {
				$updatesql .= ",";
				$updateval .= ",";
				$updateend .= ",";
			}
			$updatesql .= "$key";
			$updateval .= "'$val'";
			$updateend .= "$key=VALUES($key)";
		}
	}
	$updatesql .= ")";
	$updateval .= ")";
	return $updatesql.$updateval.$updateend.";";
}


function getIP(){
	
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
	   $ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
	   $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED'])) 
	   $ip = $_SERVER['HTTP_X_FORWARDED'];
	elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) 
	   $ip = $_SERVER['HTTP_FORWARDED_FOR'];
	elseif (!empty($_SERVER['HTTP_FORWARDED'])) 
	   $ip = $_SERVER['HTTP_FORWARDED'];
	else 
	   $ip = $_SERVER['REMOTE_ADDR'];
	   
	return $ip;   
}

function getCartMode(){
	global $conf_user;
	return $_SESSION[$conf_user]["cart_list_mode"]?$_SESSION[$conf_user]["cart_list_mode"]:"cart";
}

function fieldExist($tablename, $fieldname) {
	global $db;
	$db->setQuery("DESCRIBE $tablename");
	$collist = $db->loadRowList();
	$returnVal = false;
	foreach($collist as $row){
		if($row['Field'] == $fieldname){
			$returnVal = true;
			break;
		}
	}
	return $returnVal;
	
}

//訂單狀態接換時，商品庫存處理
function order_instock($ori_status = null , $status = null , $oid = null)
{
	global $db;
	
	if(($ori_status == '0' || !empty($ori_status)) && ($status == '0' || !empty($status)) && !empty($oid) )
	{
		$instock_chk = "";
		if( $ori_status != "8" && $ori_status != "6" && ($status == "8" || $status == "6"))
		{
			$instock_chk = " + ";
		}
		elseif( ( $ori_status == "8" || $ori_status == "6") &&  $status != "8" && $status != "6")
		{
			$instock_chk = " - ";
		}
		
		if(!empty($instock_chk))
		{
			$sql = " SELECT * FROM orderdtl WHERE oid = '$oid'";
			$db->setQuery($sql);
			$list = $db->loadRowList();
			if(count($list) > 0)
			{
				//交易開始
				$sql="BEGIN;";
				
				foreach($list as $row)
				{
					$pid = $row['pid'];
					$quantity = $row['quantity'];
					$format1 = $row['format1'];
					$format2 = $row['format2'];
					$sql.="update proinstock set instock=instock $instock_chk '$quantity' where pid='$pid' AND format1 = '$format1' AND format2 = '$format2';";
				}
				
				//紅利處理
				$info_sql = " SELECT * FROM orders WHERE id = '$oid'";
				$db->setQuery($info_sql);
				$info = $db->loadRow();
				
				if($info['orderMode'] == 'bonus')
				{
					$sql .= "update members set bonus=bonus+'{$info['bonusAmt']}' where id='{$info['memberid']}';";
				}
				
				$sql.="COMMIT;";
				//交易結束
				
				$db->setQuery($sql);
				$r=$db->query_batch();
			}
		}
		
		return true;
	}
	else
	{
		return false;
	}
	
}


function cartProductClac($active_list = array(), $cart_list = array())
{
	global $db;
	
	$uid=intval($_SESSION[$conf_user]['uid']);
	
	$salesChk = "0";    //0:一般會員 1:經銷商
	if(!empty($uid))
	{
		$salesChk = getFieldValue("select * from members where id='$uid'","salesChk"); 
	}
	
	
	
	//整理活動
	$pvbvratio = (float)getFieldValue("SELECT pvbvratio FROM siteinfo","pvbvratio");
	$active_disPro_list = array();
	$active_actPro_list = array();
	$active_usePro_list = array();
	
	$index_pro_list = array();	//紀錄商品序號
	$index2_pro_list = array();	//紀錄商品序號
	$index3_pro_list = array();	//紀錄商品序號
	if(count($active_list) > 0)
	{
		foreach($active_list as $row)
		{
			if( count($row['dispro']) > 0)
			{
				$info = array();
				$info["name"] = $row['name'];
				$info["activePlanid"] = $row['act']['activePlanid'];
				
				if($info["activePlanid"] == "1")  //折價、折扣依比例計算
				{
					$info["var01"] = intval($row['act']['var01']);	//滿足筆數
					$info["var02"] = intval($row['act']['var02']);	//打折數
				}
				else if($info["activePlanid"] == "2")	//折價、折扣依比例計算
				{
					$info["var01"] = intval($row['act']['var01']);	//滿足筆數
					$info["var02"] = intval($row['act']['var02']);	//打折數
				}
				else if($info["activePlanid"] == "3")	//單一價 //N件單一價，額外設定 PV BV
				{
					$info["var01"] = intval($row['act']['var01']);	//滿足筆數
					$info["var02"] = intval($row['act']['var02']);	//單一價金額
					$info["pv"] = intval($row['act']['pv']);
					$info["bv"] = intval($row['act']['bv']);
					
					//$index_pro_list = array();	//紀錄商品序號
					$index_act = 1;	//紀錄活動序號
					$tmp_sum = 0;
					$tmp_sum_pv = 0;
					$tmp_sum_bv = 0;
					foreach($row['dispro'] as $key2=>$row2)
					{
						$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
						
						if(  $index_act % $info["var01"] == 0  )
						{
							$info["amt"] = $info["var02"] - $tmp_sum;
							$info["amt_pv"] = $info["pv"] - $tmp_sum_pv;
							$info["amt_bv"] = $info["bv"] - $tmp_sum_bv;
							$tmp_sum = 0;
							$tmp_sum_pv = 0;
							$tmp_sum_bv = 0;
						}
						else
						{
							$info["amt"] = round( $info["var02"] / $info["var01"] );
							$info["amt_pv"] = round( $info["pv"] / $info["var01"] );
							$info["amt_bv"] = round( $info["bv"] / $info["var01"] );
							$tmp_sum += $info["amt"];
							$tmp_sum_pv += $info["amt_pv"];
							$tmp_sum_bv += $info["amt_bv"];
						}
						
						$active_disPro_list[$row2."|".$index] = $info;
						$index_pro_list[$row2] = $index;
						$index_act ++;
					}
				}
				else if($info["activePlanid"] == "12")	//第二件49折 //第二件N折，打折商品PVBV為0
				{
					$info["var01"] = intval($row['act']['var01']);	//滿足筆數
					$info["var02"] = intval($row['act']['var02']);	//打折數
					
					//$index_pro_list = array();	//紀錄商品序號
					foreach($row['dispro'] as $key2=>$row2)
					{
						$index = (!empty($index_pro_list[$row2])) ? (intval($index_pro_list[$row2]) + 1) : 1;
						
						$info["amt_pv"] = 0;
						$info["amt_bv"] = 0;
						
						$active_disPro_list[$row2."|".$index] = $info;
						$index_pro_list[$row2] = $index;
					}
				}
			}
			
			if(count($row['actproArr']))
			{
				foreach($row['actproArr'] as $row2)
				{
					$index = (!empty($index2_pro_list[$row2])) ? (intval($index2_pro_list[$row2]) + 1) : 1;
					
					$active_actPro_list[$row2."|".$index] = $row['act']['activePlanid'];
					$index2_pro_list[$row2] = $index;
				}
			}
			
			if(count($row['usepro']))
			{
				foreach($row['usepro'] as $row2)
				{
					$index = (!empty($index3_pro_list[$row2])) ? (intval($index3_pro_list[$row2]) + 1) : 1;
					
					$active_usePro_list[$row2."|".$index] = array("activePlanid"=>$row['act']['activePlanid'] , "name"=>$row['name']);
					$index3_pro_list[$row2] = $index;
				}
			}
			
		}
	}
	
	//parr($active_list);
	//parr($active_disPro_list);
	//parr($active_actPro_list);
	//parr($active_usePro_list);
	
	
	//整理購物車
	$index_cart_pro_list = array();
	if(count($cart_list) > 0 )
	{
		foreach($cart_list as $key=>$row)
		{
			
			$prodtl = array();
			$prodtl_amt_sum = 0;
			$prodtl_amt_pv = 0;
			$prodtl_amt_bv = 0;
			$prodtl_act = "";
			$prodtl_use_act = "";
			for($i = 1 ; $i <= intval($row['num']) ; $i++)
			{
				if(empty($index_cart_pro_list[$row['id']]))
				{
					$index = 1;
				}
				else
				{
					$index = $index_cart_pro_list[$row['id']] + 1;
				}
				$index_cart_pro_list[$row['id']] = $index;
				
				
				if(count($active_disPro_list[$row['id']."|".$index]) > 0)
				{
					$tmp_arr = $active_disPro_list[$row['id']."|".$index];
					
					if($tmp_arr['activePlanid'] == "1") //折價、折扣依比例計算
					{
						$amt = round($row["siteAmt"] - $prodtl['var02']);
						$pv = $row["pv"] * (($row['siteAmt'] - $prodtl['var02'] ) / $row['siteAmt']);
					}
					else if($tmp_arr['activePlanid'] == "2") //折價、折扣依比例計算
					{
						$amt = round($row["siteAmt"] * $prodtl['var02'] * 0.01);
						$pv = round($row["pv"] * $prodtl['var02'] * 0.01);
					}
					else if($tmp_arr['activePlanid'] == "3") //單一價  //N件單一價，額外設定 PV BV
					{
						$amt = $tmp_arr["amt"];
						$pv = $tmp_arr["amt_pv"];
					}
					else if($tmp_arr['activePlanid'] == "12") //第二件49折  //第二件N折，打折商品PVBV為0
					{
						$amt = round($row["siteAmt"] * ( $tmp_arr["var02"] * 0.01 ));
						$pv = $tmp_arr["amt_pv"];
					}
					
					$prodtl['amt'][] = $amt;
					$prodtl['amt_pv'][] = $pv;
					$prodtl['amt_bv'][] = $pv * $pvbvratio;
					
					$prodtl['pair'][] = ($active_actPro_list[$row['id']."|".$index] == "12") ? "Y" : "N" ;
					$prodtl['use'][] = ($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12") ? "Y" : "N" ;
					
					
					if( $active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12" && $tmp_arr['activePlanid'] == "12")
					{
						$prodtl_use_act = $tmp_arr['name'];
					}
					
					$prodtl_amt_sum += ($amt);
					$prodtl_amt_pv += ($pv);
					$prodtl_amt_bv += ($pv * $pvbvratio);
					
					
					if(!empty($prodtl_act))
					{
						$prodtl_act .= ",";
					}
					$prodtl_act .= $tmp_arr['name'];
					
				}
				else
				{
					$prodtl['amt'][] = $row["siteAmt"];
					$prodtl['amt_pv'][] = $row["pv"];
					$prodtl['amt_bv'][] = $row["bv"];
					
					$prodtl['pair'][] = ($active_actPro_list[$row['id']."|".$index] == "12") ? "Y" : "N" ;
					$prodtl['use'][] = ($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12") ? "Y" : "N" ;
					
					if($active_usePro_list[$row['id']."|".$index]['activePlanid'] == "12")
					{
						$prodtl_use_act = $active_usePro_list[$row['id']."|".$index]['name'];
					}
					
					
					
					$prodtl_amt_sum += ($row["siteAmt"]);
					$prodtl_amt_pv += ($row["pv"]);
					$prodtl_amt_bv += ($row["bv"]);
				}
			}
			
			$cart_list[$key]['prodtl'] = $prodtl;
			
			$cart_list[$key]['prodtl_amt'] = $prodtl_amt_sum;
			$cart_list[$key]['prodtl_pv'] = $prodtl_amt_pv;
			$cart_list[$key]['prodtl_bv'] = $prodtl_amt_bv;
			$cart_list[$key]['prodtl_act'] = $prodtl_act;
			$cart_list[$key]['prodtl_use_act'] = $prodtl_use_act;
			
			
		}
	}
	
	return $cart_list;
	
}

function orderChk()
{
	global $db;
	
	$uid=intval($_SESSION[$conf_user]['uid']);
	
	//檢查今日是否已執行過
	$chkDate = getFieldValue(" SELECT CodeValue FROM syscode WHERE CodeKind = 'orderChkDate' ","CodeValue");
	
	if( strtotime(date("Y-m-d 0:0:0")) >  strtotime($chkDate." 0:0:0"))
	{
		$dayStr = date("Y-m-d",strtotime("-5 days"));
		
		//取消待付款超過5天的訂單
		$sql = " SELECT * FROM orders WHERE status='0' AND buyDate <= '$dayStr' ";
		$db->setQuery( $sql );
		$list=$db->loadRowList();
		
		if(count($list) > 0)
		{
			foreach($list as $row)
			{
				$id = $row['id'];
				
				$sql="update orders set status=6,mtime='".date("Y-m-d H:i:s")."' where id='$id'";
				
				$db->setQuery( $sql );
				$db->query();
				
				$now = date('Y-m-d H:i:s');
				$today = date('Y-m-d');
				
				$sql="insert into orderlog (oid,cdate,status,ctime,mtime,muser) values 
				('$id','$today','6','$now','$now','$uid');";
				$db->setQuery($sql);
				$db->query();
				
				//狀態變為退貨、取消須加回庫存，回復須扣庫存
				order_instock("0","6",$id);
				
			}
		}
		
		
		//刷新檢查日期
		$sql="update syscode set CodeValue='".date("Y-m-d")."' WHERE CodeKind = 'orderChkDate' ";
		$db->setQuery($sql);
		$db->query();
		
	}
	
	return true;
}


/** 共用函數 結束 **/


?>