<?php
//测试使用此文件(test)
$projectenv="test";
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$time_start = microtime_float();

//产品环境只使用下在代码
//Start Product Code
/////////////////////////////////////////////////////////////////
//产品环境使用(Product)
//$projectenv="product";
$projectenv="product";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=strlen($_SERVER['SCRIPT_FILENAME'])."projectname"; //根据项目来缓存,所以最好一个网站不要一样
include("../framework/framework.php");


/////////////////////////////////////////////////////////////////
//End Product Code 
//结束产品环境代码
function echo_memory_usage() {
        $mem_usage = memory_get_usage(true);      
        if ($mem_usage < 1024)
            return $mem_usage." B";
        elseif ($mem_usage < 1048576)
            return round($mem_usage/1024,3)." KB";
        else
            return round($mem_usage/1048576,3)." MB";

    } 
$time_end = microtime_float();
$time = $time_end - $time_start;
if($viewmodel!='ajax')
echo " <div style=\"position:absolute;right:0px;top:0px;border:2px solid red;background:#ccc;filter:alpha(opacity=70); -moz-opacity:0.7; opacity: 0.7;\">内存:".echo_memory_usage()." 时间(秒):".number_format($time,3,'.', '')."</div>";
?>