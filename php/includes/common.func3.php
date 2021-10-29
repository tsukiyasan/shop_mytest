<?php





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

			if(!$db->Execute($sql) || !$db->Affected_Rows())
				showMsgRedirect($arrLangCommon['_MSG_UPD_ERR'],$errurl);
			if ( $rs->fields['datatype'] == 'dir' )
				global_dir_trans_all($db,$toid,$tablename,$tolevel,$errurl);	
			
			$rs->MoveNext();
		}	
		$rs->Close();
	}
	
	return true;
}





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
						

						global_file_del('/'.$rs->fields['id'].$filearr[$key]['name'].'/',$filearr[$key]['path'],$errurl);
					}		
				}	
			}
			
			$sql = "delete from $tablename  where id=".$rs->fields['id'];
			$db->Execute($sql);
			if(!$db->Affected_Rows())
				showMsgRedirect( $arrLangCommon['_MSG_DEL_ERR'], $errurl);
			
			$rs->MoveNext();
		}
		$rs->Close();
	}	
	return true;
}





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



function global_array_to_object( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) 
{
	if (!is_array( $array ) || !is_object( $obj )) 
	{
		return (false);
	}

	foreach (get_object_vars($obj) as $k => $v) 
	{
		if( substr( $k, 0, 1 ) != '_' ) 
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


function global_js_alert_back($str)
{
	$headstr = "alert(\"$str\"); window.history.go(-1);\n";
	
	return $headstr;
}
				

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
	

	function html_entity_decode ($string, $opt = ENT_COMPAT) 
	{

		$trans_tbl = get_html_translation_table (HTML_ENTITIES);
		$trans_tbl = array_flip ($trans_tbl);

		if ($opt & 1) 
		{ 
			
			
			$trans_tbl["&apos;"] = "'";
		}

		if (!($opt & 2)) 
		{ 
			
			unset($trans_tbl["&quot;"]);
		}

		return strtr ($string, $trans_tbl);
	}
}



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





function global_show_cont($content=null,$showtype='txt',$width=null,$class='')
{
	global $lang;

	if (empty($width))
		$width = $globalConf_wordwrap;

	if ($showtype=='txt')
	{
		$content = strip_tags($content);
		
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
		
		if (!empty($class))
			$class = " class=\"$class\"";
		$content = str_replace('<div>','<div'.$class.'>',$content);
		$content = str_replace('</div>','</div>',$content);
		return $content;		
	}
}





function global_wordwrap_utf8($str, $width=60, $break="\n")
{
	
	
	
	
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
	        } 
	    } 
		else 
		{
	        $ret = $value;
		} 
	} 
    return $ret;
} 



function global_utf8_substr($str,$from,$len)
{
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}



function global_truncate_text_nicely($string, $start, $max, $moretext=null, $charset=null, $cope='strip_tags')
{
	global $lang,$globalConf_charset,$globalConf_more_text;
	
	if (empty($moretext))
		$moretext = $globalConf_more_text;
	
	if (empty($charset))
		$charset = $globalConf_charset;
	
	
	switch ($cope)
	{
		case 'strip_tags':
			$string = strip_tags($string);
			$string = str_replace('&nbsp;','',$string);
			
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





function global_truncate_text_cht($string, $start, $max, $moretext, $charset='utf-8')
{
	
	
	if (strlen($string) > $max)
	{
		
		$max -= strlen($moretext);
		
		
		$string = mb_substr ($string, $start, $max , $charset);
		
		
		$string .= $moretext;
	}
	
	
	return $string;
}





function global_truncate_text_en($string, $start, $max, $moretext)
{
	
	
	if (strlen($string) > $max)
	{
		
		$max -= strlen($moretext);
		
		
		$string = strrev(strstr(strrev(substr($string, $start, $max)),' '));
		
		
		$string .= $moretext;
	}
	
	
	return $string;
}





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





function global_get_record_count(&$db, $sqlcnt)
{
	$rs = $db->GetOne($sqlcnt);
	return $rs;
}





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





function global_get_querystr($search='', $url='')
{
	
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





function global_replace_path($content, $original_path, $real_path) 
{
	$real_path = preg_replace("/\/$/", "", $real_path);
	$original_path = preg_replace("/\/$/", "", $original_path);
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





function global_strip_tags($str)
{
	$search = array("'<script[^>]*?>.*?</script>'si",
					"'<[\/\!]*?[^<>]*?>'si",
					"'([\r\n])[\s]+'",
					"'&(quot|#34);'i",
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





function global_http_post($url, $post)
{
	
	$c = curl_init();
	
	
	curl_setopt($c, CURLOPT_URL, $url);
	
	
	curl_setopt($c, CURLOPT_POST, true);
	curl_setopt($c, CURLOPT_POSTFIELDS, $post);
	
	
	
	
	
	curl_exec($c);
	curl_close($c);
	
}





function global_mask_text($str, $start, $replace_text = '*', $charset='utf-8')
{
	global $global_mask_start;
	
	if (empty($start))
		$start = $global_mask_start;
	elseif ($start=='all')
		$start = 0;
		
	
	
	$mask_text = mb_substr ($str, $start, mb_strlen($str, $charset) , $charset);

	
	$unmask_text = mb_substr ($str, 0, $start , $charset);
	
	$n = mb_strlen($mask_text, $charset);
	
	for ($i = 0; $i < $n; $i++)
	{
		$masked_text .= $replace_text;
	}
	$masked_text = $unmask_text.$masked_text;
	return $masked_text;
}





function global_mask_contactinfo($show_contactinfo, $text, $replace_text = '*')
{
	if ($show_contactinfo=='false')
	{
		
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
		
		
		$masked_text = mb_ereg_replace('[a-z0-9_-][a-z0-9._-]+@([a-z0-9][a-z0-9-]*\.)+[a-z]{2,6}',str_repeat($replace_text,5).'@'.str_repeat($replace_text,5).'.'.str_repeat($replace_text,3),$masked_text);
		
		$masked_text = mb_ereg_replace('([_0-9a-z-]+\.)+([0-9a-z-]+\.)+[a-z]{2,3}',str_repeat($replace_text,3).'.'.str_repeat($replace_text,5).'.'.str_repeat($replace_text,3),$masked_text);
		
		$masked_text = mb_ereg_replace('[0-9]{4}',str_repeat($replace_text,4),$masked_text);
		$masked_text = mb_ereg_replace('([0-9][ 　]){3}[0-9]',str_repeat($replace_text,4),$masked_text);
	}
	else
		$masked_text = $text;
	return $masked_text;
}





function global_keyword_highlighted($arrKword, $str, $class='wrongfont', $append='')
{
	$highlighted = $str;
	
	if (!empty($class))
		$class = " class=\"$class\"";
	
	if (is_array($arrKword) && sizeof($arrKword)>0)
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
	else
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





function global_get_varname($tablename, $fieldname='varname', $codevalue='varname', $action='array')
{
	global $tpl, $db;
	
	
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

	if ($action=='assign')
	{
		$tpl->assignGlobal($arrVarname);
	}
	else
		return $arrVarname;
}





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
	

}

 


function global_get_domain($url)
{
	$url = str_replace('http://', '', $url);
	if (strpos($url, '/')>0)
		$url = substr($url, 0, strpos($url, '/'));
	return $url;
}





function global_str_handler($str)
{
	$arr = array();
	
	
	
	$arr['%'] = '％';
	
	foreach ($arr as $key=>$value)
	{
		$str = str_replace($key, $value, $str);	
	}
	return $str;
}








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





function generateUniqueCode($mc = null , $parentid = null)
{
	global $globalConf_encrypt_1,$globalConf_encrypt_2;
	
	return md5($globalConf_encrypt_1.$mc.$parentid.$globalConf_encrypt_2);
}





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






function get_student_fromParent_arr($parentid)
{
	global $db;
	
	$get_elearn_student_sql = "SELECT DISTINCT B.schoolid,B.sourceDB,B.studentid,B.elearnStuid FROM elearnStudents A JOIN elearnStuLinks B ON A.id = B.elearnStuid WHERE ( A.parentid0 = '$parentid' OR A.parentid1 = '$parentid' OR A.parentid2 = '$parentid' ) AND B.validState = 1 ORDER BY B.elearnStuid";
	$get_elearn_student = sqlsrv_query( $db, $get_elearn_student_sql);
	if ($get_elearn_student)
	{
		$estuid_arr = array();
		$stuinfo_arr = array();
		
		
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





function get_student_arr($estudentid)
{
	global $db;
	
	$get_elearn_student_sql = "SELECT DISTINCT A.name,A.ename, B.schoolid,B.sourceDB,B.studentid,B.elearnStuid FROM elearnStudents A JOIN elearnStuLinks B ON A.id = B.elearnStuid WHERE A.id='$estudentid' AND B.validState = 1 ORDER BY B.elearnStuid";
	$get_elearn_student = sqlsrv_query( $db, $get_elearn_student_sql);
	if ($get_elearn_student)
	{
		$estuid_arr = array();
		$stuinfo_arr = array();
		
		
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





function get_studentInfo($estudentid,$schid)
{
	global $db;
	
	
	$student_arr = get_student_arr($estudentid);
	$stuinfo_arr = $student_arr['stuinfo_arr'];
	
	
	
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






function get_schoolName($stuinfo_arr)
{
	global $db;
	
	$schoolid = $stuinfo_arr['schoolid'];
	$sourceDB = $stuinfo_arr['sourceDB'];
	
	
	$linkString_arr = get_linkString_arr();
	$linkString = $linkString_arr[$sourceDB].".dbo.";
	$schoolName = "";
	$get_schoolName_sql = "SELECT name FROM ".$linkString."schools WHERE id='$schoolid'";
	
	$get_schoolName = sqlsrv_query( $db, $get_schoolName_sql);
	if($get_schoolName)
	{
		$schoolName_info = sqlsrv_fetch_array($get_schoolName);
		$schoolName = $schoolName_info['name'];
	}
	return $schoolName;
}





function get_schoolName2($schoolid,$linkString)
{
	global $db;
	
	$schoolName = "";
	$get_schoolName_sql = "SELECT id,name FROM ".$linkString."schools WHERE id='$schoolid'";
	
	$get_schoolName = sqlsrv_query( $db, $get_schoolName_sql);
	if($get_schoolName)
	{
		$schoolName_info = sqlsrv_fetch_array($get_schoolName);
		if(!empty($schoolName_info['id']))
			$schoolName = $schoolName_info['name'];
	}
	return $schoolName;
}





function get_employeeName($id,$linkString)
{
	global $db;
	
	$employeeName = "";
	$get_employeeName_sql = "SELECT A.name,B.CodeName FROM ".$linkString."employees A, ".$linkString."pubcode B WHERE A.titlePcode = B.id AND  A.id='$id'  ";
	
	$get_employeeName = sqlsrv_query( $db, $get_employeeName_sql);
	if($get_employeeName)
	{
		$employeeName = sqlsrv_fetch_array($get_employeeName);
		$employeeName = $employeeName['CodeName']." ".$employeeName['name'];
	}
	return $employeeName;
}





function get_studentName($id,$linkString)
{
	global $db;
	
	$student_Name = "";
	$get_studentName_sql = "SELECT name FROM ".$linkString."students WHERE id='$id' ";
	
	$get_studentName = sqlsrv_query( $db, $get_studentName_sql);
	if($get_studentName)
	{
		$studentName = sqlsrv_fetch_array($get_studentName);
		$student_Name = $studentName['name'];
	}
	return $student_Name;
}





function get_courseName($courseid,$schoolid,$linkString)
{
	global $db;
	
	$course_Name = "";
	$get_courseName_sql = "SELECT courseName FROM ".$linkString."View_courses WHERE id='$courseid' AND schoolid='$schoolid'";
	
	$get_courseName = sqlsrv_query( $db, $get_courseName_sql);
	if($get_courseName)
	{
		$courseName = sqlsrv_fetch_array($get_courseName);
		$course_Name = $courseName['courseName'];
	}
	return $course_Name;
}





function get_course_info_arr($stuinfo_arr)
{
	global $db;
	
	$schoolid = $stuinfo_arr['schoolid'];
	$sourceDB = $stuinfo_arr['sourceDB'];
	$studentid = $stuinfo_arr['studentid'];
	$course_arr = $stuinfo_arr['course_arr'];
	
	
	$linkString_arr = get_linkString_arr();
	$linkString = $linkString_arr[$sourceDB].".dbo.";
	$course_info_arr = array();
	
	if (count($course_arr)>0)
	{
		foreach($course_arr as $courseid)
		{
			$get_course_info_sql="SELECT courseName FROM ".$linkString."View_courses WHERE id='$courseid' AND schoolid='$schoolid'";
			
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
			
			$chk_student_sql = "SELECT * FROM ".$linkString."students WHERE schoolid = '$schoolid' AND id = '$studentid'";
			$chk_student = sqlsrv_query( $db, $chk_student_sql);
			if($chk_student)
			{
				$student_state = sqlsrv_fetch_array($chk_student);
				
				if(!empty($student_state['state']) && $student_state['state'] == '-1')
				{
					
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
	
	
	
	return $noReadCnt;
}





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
		
		$get_notificationNoRead = sqlsrv_query( $db, $get_notificationNoRead_sql);
		if ($get_notificationNoRead)
		{
			$notificationNoRead = sqlsrv_fetch_array($get_notificationNoRead);
			$noReadCnt += $notificationNoRead['cnt'];
		}
	}
	
	return $noReadCnt;
}






function write_log($str,$status,$data_array)  
{
	$textname = $str.date("Ymd").".txt"; 
	$URL = "log/".$str."/";                         
	if(!is_dir($URL))                                 
		mkdir($URL,0700);

	$URL .= $textname;                           

	$time = $str.$status.":[".date("H:i:s")."]"; 
	$writ_tmp = '';
	foreach ($data_array as $key => $value) 
	{
	   $writ_tmp .= ",".$key."=".$value;            
	}
	$write_data = $time.$writ_tmp."\n";
			   
	$fileopen = fopen($URL, "a+");              
	fseek($fileopen, 0);
	fwrite($fileopen,$write_data);                 
	fclose($fileopen);
} 



?>
