<?php
//$iscacheconfig 如果定义了就是缓存，不定义就不使用缓存
//单独使用数据库类
/*
*  queryphp ORM 
*
*  文使用时候直接含本文件就可以了，这样可以把queryorm导入到你的框架中。
*  使用方法见文档。操作借鉴了jquery doctrine操作方式 努力模拟doctrine ORM行为
*  $books=M("booktype");取得模型
*/
$config["frameworkpath"]=dirname(__FILE__)."/";
include($config["frameworkpath"]."config/inc.ini.php");
if($projectenv=='product'&&file_exists($config["frameworkpath"]."cache/orm.cache.php"))
{
  require_once $config["frameworkpath"]."cache/orm.cache.php";
}else{
 if($projectenv=='product')
 {
	$corecontent=substr(php_strip_whitespace($config["frameworkpath"]."core/model.php"),0,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/function.php"),5,-2);
	file_put_contents($config["frameworkpath"]."cache/orm.cache.php",$corecontent);
	unset($corecontent);
	require_once $config["frameworkpath"]."cache/orm.cache.php";
 }else{
	include($config["frameworkpath"]."core/model.php");
	include($config["frameworkpath"]."core/function.php");
 }
}
?>