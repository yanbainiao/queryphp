<?php
/***
*前台URL函数
*输出本项目URL地址，根据$_SERVER["SCRIPT_NAME"]去掉index.php或别的文件得出
***/
function PU($fix='/') {
 Return url_project($fix);	
}
function url_project($fix='/') {
  if(!isset($GLOBALS['__PROJECT__']))
  $GLOBALS['__PROJECT__']=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"));
  Return $GLOBALS['__PROJECT__'].$fix;	
}
/*
*用户对像返回函数
*/
function MY() {
	if(isset($GLOBALS['myUser']))
	{
	 return $GLOBALS['myUser'];
	}  	
	$GLOBALS['myUser']=new myUser();
	return $GLOBALS['myUser'];
}
//输出页头
function sendHeader($c='utf-8') {
	header("Content-type: text/html; charset=".$c);
}
/*
*跳转函数
*/
function redirect($url,$msg,$second=0,$o=true) {
	sendHeader();
    $str='<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="refresh" content="'.$second.';URL='.$url.'"></head><body>'.$msg.'</body></html>';
	if($o){
	  echo $str;exit;
	}
   Return $str;
}
/*
*权限控制函数
*/
function ACL($acl) {
   if(isset($GLOBALS[$acl."ACL"]))
   {
     return $GLOBALS[$acl."ACL"];
   }

		if(file_exists(P("webprojectpath")."router/acl/".$acl."ACL.class.php"))
		{
		   require_once P("webprojectpath")."router/acl/".$acl."ACL.class.php";
		}elseif(file_exists(P("modelpath")."router/acl/".$acl."ACL.class.php")){
		   require_once P("modelpath")."router/acl/".$acl."ACL.class.php";	
		}else{
		  if (is_dir(P("webprojectpath")."../")) {
				if ($dh = opendir($dir)) {
					while (($file = readdir($dh)) !== false) {
						echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
					}
					closedir($dh);
				}
			}
		  Return false;
		}	

     $t=$acl."ACL";
	 $GLOBALS[$acl."ACL"]=new $t();
	 return $GLOBALS[$acl."ACL"];
}
/*
*语言显示可以自动显示目标语言
*默认是I('systemlanuage');
*需要转换语言是I('language');
*/
function L($str,$model='') {
	if(I('language')!=I('systemlanuage'))
	{
		 /*
		 *在这里取得缓存翻译结果没有就翻译
		 */
		$url = 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&q=' .
			urlencode($str) . 
			'&langpair=' . I('systemlanuage') . '%7C' .
			I('language');			
		$json_data = file_get_contents($url);		
		$j = json_decode($json_data);		   
		if (isset($j->responseStatus) and $j->responseStatus == 200)
		{
			$t = $j->responseData->translatedText;
			 /*
			 *在这里开始做翻译好的文本缓存
			 */
			 
			 /*
			 *结束翻译好的文件缓存;
			 */
			 Return $t;
		}
	}
	Return $str;
}

/*
*视图函数
*/
function V() {
	Return new view();
}
/*
* 文件名安全处理
*/
function filepath_safe($name) {
    $except = array('\\',' ', '..', ':', '*', '?', '"', '<', '>', '|');
    return str_replace($except,'', $name);
} 
/*
* 文件名安全处理
*/
function filename_safe($name) {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|');
    return str_replace($except, '', $name);
} 
/*
*加入类库
*/
function import($libpath)
{
   if(preg_match("|^@lib|i",$libpath))
   {
     $p=str_replace(".","/",substr($libpath,4)).".class.php";
	 if(checkrequire(P("webprojectpath")."lib".$p)) return true;
	 if(checkrequire(P("frameworkpath")."lib".$p)) return true;
   }
   if(preg_match("|^@plugin|i",$libpath))
   {
     $p=str_replace(".","/",substr($libpath,7)).".class.php";
     if(checkrequire(P("webprojectpath")."plugin".$p)) return true;
	 if(checkrequire(P("frameworkpath")."plugin".$p)) return true;
   }
   if(preg_match("|^@plugin|i",$libpath))
   {
     $p=str_replace(".","/",substr($libpath,7)).".php";
     if(checkrequire(P("webprojectpath")."plugin".$p)) return true;
	 if(checkrequire(P("frameworkpath")."plugin".$p)) return true;
   }
}
/*
*
*/
function checkrequire($files)
{
  if(file_exists($files))
  {
    require_once($files);
    return true;
  }else{
    return false;
  }
}
/*
*数据库链接生成
*/
function pdoconnects($dsn,$connmodel)
{    
   try {
	    $GLOBALS['pdolinks'][$connmodel]=new PDO($dsn['dsn'],$dsn['username'],$dsn['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND =>'SET CHARACTER SET '.$dsn['CHARACTER']));
	    return $GLOBALS['pdolinks'][$connmodel];
	  } catch (PDOException $e) {
       print "connects Error!: " . $e->getMessage() . "<br/>";
    }
}
/*
*数据库链接处理
*/
function getConnect($table,$model=null,$connper=0,$conn=null)
{
	 $tconn=array();
	 if(!isset($GLOBALS['pdolinks'])) $GLOBALS['pdolinks']=array();
	 
	 //直接返回模型链接
	 /*
	 *  M("s.user");
	 *  s.表示使用数据库链接数组的key值 user是模型
	 *  如:配置文件inc.ini.php
	 *  $config['pdoconn']=array(
	 *	'default'=>array("master"=>array //default 是默认缺省链接
	 *	's'=>array("master"=>array  //s组链接集合,因为有些数据库没有前辍每个模型要配置一个链接很麻烦
	 */
	 if($conn!=null&&isset($GLOBALS['config']['pdoconn'][$conn]))
	 {
		$prand=rand(0,count($GLOBALS['config']['pdoconn'][$conn]["master"])-1);
		$connmodel=md5(json_encode($GLOBALS['config']['pdoconn'][$conn]["master"][$prand]));
		$table_fix=$GLOBALS['config']['pdoconn'][$conn]["master"][$prand]['table_fix'];
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		   $tconn['master']=$GLOBALS['pdolinks'][$connmodel];
		 else
		 {
		   $tconn['master']=pdoconnects($GLOBALS['config']['pdoconn'][$conn]["master"][$prand],$connmodel);
		 }
		$prand=rand(0,count($GLOBALS['config']['pdoconn'][$conn]["slaves"])-1);
		$connmodel=md5(json_encode($GLOBALS['config']['pdoconn'][$conn]["slaves"][$prand]));
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		   $tconn['slaves']=$GLOBALS['pdolinks'][$connmodel];
		 else
		 {
		   $tconn['slaves']=pdoconnects($GLOBALS['config']['pdoconn'][$conn]["slaves"][$prand],$connmodel);
		 }
	 }
	 
	 //正则去查询数据库链接
	 if(empty($tconn)&&is_array($GLOBALS['config']['pdoconn']))
	 {
        foreach($GLOBALS['config']['pdoconn'] as $k=>$v)
		{
		  if(strlen($k)>3&&($k==$model||preg_match("|".$k."|i",$table)||preg_match("|".$k."|i",$model)))
		  {
			 $prand=rand(0,count($v["master"])-1);
			 $connmodel=md5(json_encode($v["master"][$prand]));
			 $table_fix=$v["master"][$prand]['table_fix'];
			 if(isset($GLOBALS['pdolinks'][$connmodel]))
			   $tconn['master']=$GLOBALS['pdolinks'][$connmodel];
			 else
			 {
			   $tconn['master']=pdoconnects($v["master"][$prand],$connmodel);
			 }
			$prand=rand(0,count($v["slaves"])-1);
			$connmodel=md5(json_encode($v["slaves"][$prand]));
			 if(isset($GLOBALS['pdolinks'][$connmodel]))
			   $tconn['slaves']=$GLOBALS['pdolinks'][$connmodel];
			 else
			 {
			   $tconn['slaves']=pdoconnects($v["slaves"][$prand],$connmodel);
			 }
		    break;
		  }
		}
	 }
	 if(count($tconn)<2)
	 {
		$prand=rand(0,count($GLOBALS['config']['pdoconn']['default']["master"])-1);
	    $connmodel=md5(json_encode($GLOBALS['config']['pdoconn']['default']["master"][$prand]));
		$table_fix=$GLOBALS['config']['pdoconn']['default']["master"][$prand]['table_fix'];
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		 {
			$tconn['master']=$GLOBALS['pdolinks'][$connmodel];
		 }else{
			$tconn['master']=pdoconnects($GLOBALS['config']['pdoconn']['default']["master"][$prand],$connmodel);
		 }
        $prand=rand(0,count($GLOBALS['config']['pdoconn']['default']["slaves"])-1);
		$connmodel=md5(json_encode($GLOBALS['config']['pdoconn']['default']["slaves"][$prand]));
		 if(isset($GLOBALS['pdolinks'][$connmodel]))
		   $tconn['slaves']=$GLOBALS['pdolinks'][$connmodel];
		 else
		 {
		   $tconn['slaves']=pdoconnects($GLOBALS['config']['pdoconn']['default']["slaves"][$prand],$connmodel);
		 }
	 }
	 if($connper==1)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['slaves'],'table_fix'=>$table_fix);//根据$model返回主从就可以了
	 }else if($connper==0)
	 {
	   return array('master'=>$tconn['master'],'slaves'=>$tconn['master'],'table_fix'=>$table_fix);//根据$model返回主从就可以了
	 }
     
}
/*
*P()取路径函数，比如lib plugin model view class
*/
function P($name)
{
 if(isset($GLOBALS['config'][$name])) return $GLOBALS['config'][$name];
 else return $GLOBALS['config']["frameworkpath"];
}
/*
*$config['key']值存取;
*/
function I($name)
{
  return isset($GLOBALS['config'][$name])?$GLOBALS['config'][$name]:null;
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
		Return $router->{C("router")->action}($arg);
		//call_user_func(array($router,C("router")->action),$arg);
	}
}
/*
*DM是datamodel数据模型类
*就是提数据集合类
*/
function DM($newc) {
   if(isset($GLOBALS[$newc."DM"]))
   {
     return $GLOBALS[$newc."DM"];
   }

		if(file_exists(P("webprojectpath")."model/dm/".$newc."DM.class.php"))
		{
		   require_once P("webprojectpath")."model/dm/".$newc."DM.class.php";
		}elseif(file_exists(P("modelpath")."model/dm/".$newc."DM.class.php")){
		   require_once P("modelpath")."model/dm/".$newc."DM.class.php";	
		}	
      $t=$newc."DM";
     $GLOBALS[$newc."DM"]=new $t();
	 return $GLOBALS[$newc."DM"];

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
	 return $GLOBALS[$router]=new $router();
   }
}
//M为调用类库模型，第一次就开始生成文件了
function M($modelname=null,$tablename=null)
{
   $conn='';
   $fix='';
   if(strpos($modelname,'.'))
   {
	 list($conn,$modelname)=explode(".",$modelname);
	 $fix=$conn."_";
   }

   if($modelname==null) return null;
   $table=$fix.$modelname;
   if(isset($GLOBALS[$table]))
   {
     return $GLOBALS[$table];
   }else{
	 if(!empty($tablename))
	   {
		 initModelclass($modelname,$tablename,$conn);
	   }
//如果是新的模型检查模型文件
//注意先包含这个文件
		   if(!file_exists(P("modelpath")."model/".$table.'Base.class.php')&&!file_exists(P("webprojectpath")."model/".$table.'Base.class.php'))
			{			   
			   initModelclass($modelname,$tablename,$conn);	
			   clearstatcache();
			}		
			if(file_exists(P("webprojectpath")."model/".$table.'Base.class.php'))
			{	
			   require_once P("webprojectpath")."model/".$table.'Base.class.php';		
			}elseif(file_exists(P("modelpath")."model/".$table.'Base.class.php')){
			   require_once P("modelpath")."model/".$table.'Base.class.php';	
			}

		if(file_exists(P("webprojectpath")."model/".$table."Model.class.php"))
		{
		   require_once P("webprojectpath")."model/".$table."Model.class.php";
		}elseif(file_exists(P("modelpath")."model/".$table."Model.class.php")){
		   require_once P("modelpath")."model/".$table."Model.class.php";	
		}else{	
           $newmodelstr="<?php \nclass ".$table."Model extends ".$table."Base{ \n ";
		   $newmodelstr.="  public \$modelname='".$modelname."';\n";
		   $newmodelstr.="  public \$fix='".$conn."';\n";
		   $newmodelstr.=" public \$mapper=array();\n";
		   $newmodelstr.=" public \$maps;\n";
		   $newmodelstr.=" public \$maparray=array();\n";
           $newmodelstr.=" \n} \n?>";
		   file_put_contents(P("modelpath")."model/".$table.'Model.class.php',$newmodelstr);

		   require_once P("modelpath")."model/".$table.'Model.class.php';
		}
				   //也检查下基类信息
	 $modelname=$table."Model";
     return $GLOBALS[$table]=new $modelname($conn);
   }
}
//初始化基本类文件，文件格式根据mysql数据库自动把结构写进去
function initModelclass($modelname,$tablename=null,$conn=null)
{
   if($tablename==null) $tablename=$modelname;
   $string="DESCRIBE `".$tablename."`";	
   
   $DB=getConnect($tablename,$modelname,0,$conn);
	try{
		$res=$DB['master']->query($string);
	    $mate =$res->fetchAll(PDO::FETCH_ASSOC);  
	} catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
   if(is_array($mate))
	 {
	   //生成基类
	   if($conn){ $table=$conn."_".$modelname;
	   }else{ $table=$modelname; }
	   $newmodelstr="<?php \n class ".$table."Base extends model{ \n ";
	   $fields=array();
       $types=array();
	   $newmodelstr.="  public \$tablename='".$tablename."';";
	   foreach($mate as $key=>$value)
	   {
		  $value['Field']=strtolower($value['Field']);
	      if($value['Key']=='PRI')
		   {
             $newmodelstr.="\n public \$PRI='".$value['Field']."';";
	         if($value['Extra']=='auto_increment')
			   {
			     $newmodelstr.="\n public \$autoid=true;";
			   }else{
			     $newmodelstr.="\n public \$autoid=false;";
			   }
		   }
		  $fields[$value['Field']]=$value['Default'];
		  $types[$value['Field']]=$value['Type'];
	   }
	   $newmodelstr.="\n public \$fields=".var_export($fields,true).";";
	   $newmodelstr.="\n public \$types=".var_export($types,true).";";
	   $newmodelstr.="\n}\n?>";
	 }
	 file_put_contents(P("modelpath")."model/".$table.'Base.class.php',$newmodelstr);
}
/*
* 自动加载类
*/
function __autoload($class_name) {
	$fix=substr($class_name,-6);
	if($fix=='Router'){ //路由文件
		$newc=substr($class_name,0,-6);
		if(file_exists(P("webprojectpath")."router/".$newc."Router.class.php"))
		{
		   require_once P("webprojectpath")."router/".$newc."Router.class.php";
		   return;
		}elseif(file_exists(P("routerpath")."router/".$newc."Router.class.php")){
		   require_once P("routerpath")."router/".$newc."Router.class.php";	
		   return;
		}
	}
	if(isset($GLOBALS['config']['frameworklib'][$class_name])){
		require_once $GLOBALS['config']['frameworklib'][$class_name];
	    return;
	}
	if(file_exists(P("webprojectpath")."class/".$class_name.'.class.php'))
	{		   
	  require_once P("webprojectpath")."class/".$class_name.'.class.php';	
	  return;
	}
	if(file_exists(P("frameworkpath")."class/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."class/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath")."lib/".$class_name."/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."lib/".$class_name."/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath")."lib/".$class_name.'.class.php'))
	{		   
	  require_once P("frameworkpath")."lib/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("webprojectpath")."lib/".$class_name.'.class.php'))
	{		   
	  require_once P("webprojectpath")."lib/".$class_name.'.class.php';
	  return;
	}
	if(file_exists(P("frameworkpath").$class_name.'.php'))
	{		   
	  require_once P("frameworkpath").$class_name.'.php';
	  return;
	}
	if(isset($GLOBALS['config']['frameworklib'])&&is_array($GLOBALS['config']['frameworklib']))
	{
	  foreach($GLOBALS['config']['frameworklib'] as $k=>$v)
	  {
		if(is_numeric($k))
		{
		   if(preg_match("@".$class_name."\.(class\.)?php$@i",$v)){ require_once $v; return; }
		}
	  }
	}

   if(isset($GLOBALS['config']['searchlib'])&&is_array($GLOBALS['config']['searchlib']))
	{		
	  foreach($GLOBALS['config']['searchlib'] as $v)
	  {
		 $t = new RecursiveDirectoryIterator($v);
         foreach(new RecursiveIteratorIterator($t) as $file) {
		   if(preg_match("@".$class_name."\.(class\.)?php$@i",$file)){ require_once $file; return; }
		}
	  }
	}
	throw new mylog(' autoload ['.$class_name.' no exists]',3002);
}
/*
* URL函数，可能要考虑到ruleMaps设置的 因为地址显示是别名
* 读取ruleMaps 和初始传入的$controller
*/
function U() {
	$arg_list = func_get_args();
	Return call_user_func_array('url_for',$arg_list);	
}
function url_for()
{
  $arg_list = func_get_args();
	if(C("router")->isPathInfo()===true)
	{
	  $url=explode("?",$arg_list[0]);
	  $t=explode("/",$url[0]);
	    
	   $u="?router=".array_shift($t)."&action=".array_shift($t);
	   if(is_array($t))
	   {
	     $n=count($t);
		 for($i=0;$i<$n;$i++)
		 {
		   $u.="&".$t[$i]."=".$t[++$i];
		 }
	   }
	   if(!empty($url[1]))
	   {
		 $u.="&".$url[1];
	   }
	   $url='';
       $url=$_SERVER["SCRIPT_NAME"].$u;
	}else{
	  //如果是动态使用$_SERVER["SCRIPT_NAME"]
	  if($_SERVER["PATH_INFO"]=='/'&&!isset($GLOBALS['config']['html']))
	  {
	   $url=$_SERVER["SCRIPT_NAME"]."/".$arg_list[0];	   
	  }else{
	   $url=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],$_SERVER["PATH_INFO"]))."/".$arg_list[0];
      }
	  if(isset($GLOBALS['config']['html'])&&(substr($url,-strlen($GLOBALS['config']['html']))!=$GLOBALS['config']['html']))
	  {
		 if(isset($arg_list[1])&&$arg_list[1]===true)
		  {
		  }else{
		   $url.=$GLOBALS['config']['html'];
		   //把静态目录补上
		   if(isset($GLOBALS['config']['realhtml']))
		   {
			   $url=substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],$_SERVER["PATH_INFO"])).$GLOBALS['config']['realhtml']."/".$arg_list[0].$GLOBALS['config']['html'];
		   }
		  }
	   }
	}
  return $url;
}
?>