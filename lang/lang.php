<?php

    $path="../components/";
    $lang=$_GET['lang'];
    $dir=scandir($path);
    $lang_str="";
    foreach($dir as $key=>$row){
        if($key>1){
            $path2="$path$row/";
            $lang_file=$path2."lang/{$lang}.json";
            if(is_file($lang_file)){
                $str=file_get_contents($lang_file);
                if($str){
                    $lang_str.=$str.",";
                }
            }
        }
    }
    $lang_str.=file_get_contents("{$lang}.json");
    echo "{".$lang_str."}";
    die();
?>