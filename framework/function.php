<?php

//pdo链接 多少自己填,主从可以一样
$conn=array(
  'default'=>array("master"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8")),
             "slaves"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
			                 "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
							 "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"))
			 ),
  '^web'=>array("master"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "3"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "4"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "5"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "6"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8")),
             "slaves"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
			                 "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
							 "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"))
			 )
);
function pdoconnects($dsn,$connmodel)
{    
   try {
	     $GLOBALS['pdoconn'][$connmodel]=new PDO($dsn['dsn'],$dsn['username'],$dsn['password']);
	     $GLOBALS['pdoconn'][$connmodel]->exec('SET CHARACTER SET '.$dsn['CHARACTER']);
	    return $GLOBALS['pdoconn'][$connmodel];
	  } catch (PDOException $e) {
       print "connects Error!: " . $e->getMessage() . "<br/>";
    }
}
function getConnect($table,$model=null,$connper=0)
{
     global $conn;
	 $tconn=array();
	 if(is_array($conn))
	 {
        foreach($conn as $k=>$v)
		{
		  if(preg_match("|".$k."|i",$table)||preg_match("|".$k."|i",$model))
		  {
			 $prand=rand(0,count($v["master"])-1);
			 $connmodel=md5(json_encode($v["master"][$prand]));
			 if($GLOBALS['pdoconn'][$connmodel]!='')
			   $tconn['master']=$GLOBALS['pdoconn'][$connmodel];
			 else
			 {
			   $tconn['master']=pdoconnects($v["master"][$prand],$connmodel);
			 }
			$prand=rand(0,count($v["slaves"])-1);
			$connmodel=md5(json_encode($v["slaves"][$prand]));
			 if($GLOBALS['pdoconn'][$connmodel]!='')
			   $tconn['slaves']=$GLOBALS['pdoconn'][$connmodel];
			 else
			 {
			   $tconn['slaves']=pdoconnects($v["slaves"][$prand],$connmodel);
			 }
		  }
		}
	 }
	 if(count($tconn)<2)
	 {
		$prand=rand(0,count($conn['default']["master"])-1);
	    $connmodel=md5(json_encode($conn['default']["master"][$prand]));
		 if($GLOBALS['pdoconn'][$connmodel]!='')
		 {
			$tconn['master']=$GLOBALS['pdoconn'][$connmodel];
		 }else{
			$tconn['master']=pdoconnects($conn['default']["master"][$prand],$connmodel);
		 }
        $prand=rand(0,count($conn['default']["slaves"])-1);
		$connmodel=md5(json_encode($conn['default']["slaves"][$prand]));
		 if($GLOBALS['pdoconn'][$connmodel]!='')
		   $tconn['slaves']=$GLOBALS['pdoconn'][$connmodel];
		 else
		 {
		   $tconn['slaves']=pdoconnects($conn['default']["slaves"][$prand],$connmodel);
		 }
	 }
	 if($connper==1)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['slaves']);//根据$model返回主从就可以了
	 }else if($connper==0)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['master']);//根据$model返回主从就可以了
	 }
     
}
function INI($name)
{
  global $config;
  return $config[$name];
}
//J路由跳转
function J()
{
   $arg = func_get_args();
   if(is_object($arg[0]))
	{
	  $controller=get_class($arg[0]);
	  $controller=substr($controller,0,-6);
	  C("router")->controller=$controller;	  
	  if($arg[1]=='') $arg[1]=ROUTER_DEFAULT_ACTION;      
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
//C创建类
function C($class=null)
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
//R为创建Router
function R($router=null)
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
//M为调用类库模型，第一次就开始生成文件了
function M($table=null)
{
   if($table==null) return null;
   $table=$table."Model";
   if(isset($GLOBALS[$table]))
   {
     return $GLOBALS[$table];
   }else{
     $GLOBALS[$table]=new $table();
	 return $GLOBALS[$table];
   }
}
//初始化基本类文件，文件格式根据mysql数据库自动把结构写进去
function initModelclass($modelname)
{
   $fix=substr($modelname,-4);
   if($fix=="Base") $modelname=substr($modelname,0,-4);
   $string="DESCRIBE ".$modelname;	
   $DB=getConnect($modelname);
	try{
	    $res=$DB['master']->query($string);
        $mate =$res->fetchAll(PDO::FETCH_ASSOC);  
	} catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
   if(is_array($mate))
	 {
	   $newmodelstr="<?php \n class ".$modelname."Base extends model{ \n ";
	   $fields=array();
       $types=array();
	   $newmodelstr.="  var \$tablename='".$modelname."';";
	   foreach($mate as $key=>$value)
	   {
		  $value['Field']=strtolower($value['Field']);
	      if($value['Key']=='PRI')
		   {
             $newmodelstr.="\n var \$PRI='".$value['Field']."';";
	         if($value['Extra']=='auto_increment')
			   {
			    $newmodelstr.="\n var \$autoid=true;";
			   }else{
			     $newmodelstr.="\n var \$autoid=false;";
			   }
		   }
		  $fields[$value['Field']]=$value['Default'];
		  $types[$value['Field']]=$value['Type'];
	   }
	   $newmodelstr.="\n var \$fields=".var_export($fields,true).";";
	   $newmodelstr.="\n var \$types=".var_export($types,true).";";
	   $newmodelstr.="\n}\n?>";
	 }
	 file_put_contents(dirname(__FILE__)."/model/".$modelname.'Base.class.php',$newmodelstr);
}
function __autoload($class_name) {
    $fix=substr($class_name,-5);
	if($fix=='Model'){
		$newc=substr($class_name,0,-5);		
		if(file_exists(dirname(__FILE__)."/model/".$class_name.".class.php"))
		{
		   require_once dirname(__FILE__)."/model/".$class_name.".class.php";
		}else{		   
           $newmodelstr="<?php \nclass ".$newc."Model extends ".$newc."Base{ \n ";
		   $newmodelstr.=" var \$mapper=array();\n";
		   $newmodelstr.=" var \$maps;";
           $newmodelstr.=" \n} \n?>";
		   file_put_contents(dirname(__FILE__)."/model/".$newc.'Model.class.php',$newmodelstr);
		   require_once dirname(__FILE__)."/model/".$newc.'Model.class.php';
		}
	}
	$fix=substr($class_name,-4);
	if($fix=='Base'){
		$newc=substr($class_name,0,-4);
		if(!file_exists(dirname(__FILE__)."/model/".$newc.'Base.class.php'))
		{		   
		   initModelclass($newc);		   
		}
		if(file_exists(dirname(__FILE__)."/model/".$newc.'Base.class.php'))
		{		   
		   require_once dirname(__FILE__)."/model/".$newc.'Base.class.php';		   
		}
	}
	$fix=substr($class_name,-6);
	if($fix=='Router'){
		$newc=substr($class_name,0,-6);
		if(file_exists(dirname(__FILE__)."/router/".$newc."Router.class.php"))
		{
		   require_once dirname(__FILE__)."/router/".$newc."Router.class.php";
		}
	}
	if(file_exists(dirname(__FILE__)."/".$class_name.'.class.php'))
	{		   
	  require_once dirname(__FILE__)."/".$class_name.'.class.php';		   
	}
	if(file_exists(dirname(__FILE__)."/".$class_name.'.php'))
	{		   
	  require_once dirname(__FILE__)."/".$class_name.'.php';		   
	}
}
?>