<?php
/*
* 本类是为了解决eclipse代码提示问题 好像还不行，symfony的就可以
* 
*/
class Q{
	public static function P($name)
	{
	 if(isset($GLOBALS['config'][$name])) return $GLOBALS['config'][$name];
	 else return $GLOBALS['config']["frameworkpath"];
	}
	public static function I($name)
	{
       return $GLOBALS['config'][$name];
	}
	public static function M($table)
	{
	   if($table==null) return null;
	   $table=$table."Model";
	   if(isset($GLOBALS[$table]))
	   {
		 return $GLOBALS[$table];
	   }else{
		 $GLOBALS[$table]=new $table;
		 return $GLOBALS[$table];
	   }
	}
	public static function R($router)
	{
	   if($router==null) return null;
	   $router=$router."Router";
	   if(isset($GLOBALS[$router]))
	   {
		 return $GLOBALS[$router];
	   }else{
		 try{
			$GLOBALS[$router]=new $router();
		  }catch (PDOException $e) 
		  {
			echo $e->getMessage();
		  }
		 return $GLOBALS[$router];
	   }
	}
	public static function C($class)
	{
	   if($class==null) return null;
	   if(isset($GLOBALS[$class."class"]))
	   {
		 return $GLOBALS[$class."class"];
	   }else{
		 $GLOBALS[$class."class"]=new $class();
		 return $GLOBALS[$class."class"];
	   }
	}
	public static function J($class)
	{
	   $arg = func_get_args();
	   if(is_object($arg[0]))
		{
		  $controller=get_class($arg[0]);
		  $controller=substr($controller,0,-6);
		  C("router")->controller=$controller;	  
		  if($arg[1]=='') $arg[1]=$GLOBALS['config']['defaultindex'];      
		  C("router")->action=$arg[1];
		  array_shift($arg);
		  array_shift($arg);
		}else if(is_string($arg[0]))
		{
		  if($arg[1]=='')
		  {
			C("router")->action=$arg[0];
			array_shift($arg);
		  }else if(is_array($arg[1])){
			C("router")->action=$arg[0];
			array_shift($arg);
		  }else{
			C("router")->controller=$arg[0];
			C("router")->action=$arg[1];
			array_shift($arg);
			array_shift($arg);
		  }
		}
		$router=R(C("router")->controller);
		if(method_exists($router,C("router")->action)) {
			$router->render(C("router")->action);
			call_user_func(array($router,C("router")->action),$arg);
		}
	}
}
?>