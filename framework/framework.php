<?php
//$iscacheconfig 如果定义了就是缓存，不定义就不使用缓存
$config["frameworkpath"]=dirname(__FILE__)."/";
if(isset($iscacheconfig)&&$projectenv=='product'&&file_exists($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php"))
{
  $config=array_merge(include($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php"),$config);
  $isreadconfig=true;
}
if(!isset($isreadconfig)){
	include($config["frameworkpath"]."config/inc.ini.php");
	if(file_exists($config["webprojectpath"]."config/precore.ini.php"))
	{
	  require_once $config["webprojectpath"]."config/precore.ini.php";
	}
}
if($projectenv=='product'&&file_exists($config["frameworkpath"]."cache/core.cache.php"))
{
  require_once $config["frameworkpath"]."cache/core.cache.php";
}else{
 if($projectenv=='product')
 {
	//$corecontent=substr(php_strip_whitespace($config["frameworkpath"]."core/base.class.php"),0,-2);
	$corecontent=substr(php_strip_whitespace($config["frameworkpath"]."core/model.php"),0,-2);

	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/function.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/mylog.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/router.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/view.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/sitehtml.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/acl.php"),5,-2);
	$corecontent.=substr(php_strip_whitespace($config["frameworkpath"]."core/controller.php"),5);

	file_put_contents($config["frameworkpath"]."cache/core.cache.php",$corecontent);
	unset($corecontent);
	require_once $config["frameworkpath"]."cache/core.cache.php";
 }else{
	include($config["frameworkpath"]."core/sitehtml.php");
	include($config["frameworkpath"]."core/model.php");
	include($config["frameworkpath"]."core/function.php");
	include($config["frameworkpath"]."core/router.php");
	include($config["frameworkpath"]."core/mylog.php");
	include($config["frameworkpath"]."core/view.php");
	include($config["frameworkpath"]."core/acl.php");
	include($config["frameworkpath"]."core/controller.php");
 }
}
if(!isset($isreadconfig)&&file_exists($config["webprojectpath"]."config/aftercore.ini.php"))
{
  require_once $config["webprojectpath"]."config/aftercore.ini.php";
}
    if(isset($iscacheconfig))
	if($projectenv=='product')
	{
	  if(!file_exists($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php"))
	   file_put_contents($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php","<?php return ".var_export($config,TRUE)."; ?>");
	}else{
	   if(file_exists($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php"))
	   unlink($config["frameworkpath"]."cache/".$config["webprojectname"]."config.cache.php");
	}
//如果只想使用数据库链接那么注销下面代码就可以了
$dispaths=C("router")->setMaps($config["routermaps"])->start();
$view=C("view");
$router=R($dispaths->controller);
if (method_exists($router,$dispaths->action)) {
    //权限检查
	$ispass=false;	
    if(method_exists($router,"isAcl"))
    {
	   $mask=$router->isAcl();
	   if(empty($mask)) $mask=$dispaths->controller;	   
       if($acl=ACL($mask))
		{
          $mask=$acl->aclCheck($dispaths->controller,$dispaths->action);
		  if($mask!==true) $ispass=true;
		}else{
		  $mask='';
		  $ispass=true;
		}
	}
	if($ispass)
	{
	    if(method_exists($router,"noAcl"))
		{
		  $viewmodel=$router->noAcl($mask);
		}elseif(is_object($acl)){
		  $acl->noAcl($mask);
		}
	}else{
		//call_user_func(array($router,$dispaths->action));
		//检查有没有要前置执行方法
		if (method_exists($router,"pre_".$dispaths->action)) {
		  $viewmodel=$router->{"pre_".$dispaths->action}();
		}
		if($viewmodel!==false) $viewmodel=$router->{$dispaths->action}();
		//检查有没有要后置执行方法
		if(method_exists($router,"after_".$dispaths->action)) {
		   $router->{"after_".$dispaths->action}();
		}
	}
	//如果$viewmodel有值就使用$viewmodel中的值设置视图
	if(false!==$viewmodel&&'ajax'!=$viewmodel)
	{
		 $view->display(R($dispaths->controller)->view($dispaths->action));
	}
	
}else{
  header("HTTP/1.1 404 Not Found");
}
?>