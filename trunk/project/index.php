<?php
//产品环境使用(Product)
//$projectenv="product";
$projectenv="product";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=strlen($_SERVER['SCRIPT_FILENAME'])."projectname"; //根据项目来缓存,所以最好一个网站不要一样
include("../framework/framework.php");
?>