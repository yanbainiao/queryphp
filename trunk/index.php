<?php
//产品环境使用(Product)
//$projectenv="product";
$projectenv="product";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=basename($_SERVER['SCRIPT_FILENAME']);
include("framework/framework.php");
?>