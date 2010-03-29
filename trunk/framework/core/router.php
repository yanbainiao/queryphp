<?php
class Router {
  public $request_uri;
  public $routes;
  public $controller, $controller_name;
  public $action, $id;
  public $params;
  public $route_found = false;
  public $rules=array();
  public $urlcontroller;
  public $isScript=false;
  public $fixpath;
  public function __construct() {
	self::getPathInfo();
	//去掉静态目录
	if(isset($GLOBALS['config']['realhtml']))
	{
	  //消除path中静态缓存目录
	  if(strncasecmp($_SERVER['PATH_INFO'],$GLOBALS['config']['realhtml'],strlen($GLOBALS['config']['realhtml']))==0)
	  $_SERVER['PATH_INFO']=substr($_SERVER['PATH_INFO'],strlen($GLOBALS['config']['realhtml']));
	  if(strncasecmp($_SERVER["REQUEST_URI"],$_SERVER["SCRIPT_NAME"],strlen($_SERVER["SCRIPT_NAME"]))==0){
	    $this->isScript=true;//带有index.php的
        $_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],strlen($_SERVER["SCRIPT_NAME"]));
		//先切开开始目录，删除html目录然后再组装起来
        if(strncasecmp($_SERVER["REQUEST_URI"],$GLOBALS['config']['realhtml'],strlen($GLOBALS['config']['realhtml']))==0)
	     $_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],strlen($GLOBALS['config']['realhtml']));
       $_SERVER["REQUEST_URI"]=$_SERVER["SCRIPT_NAME"].$_SERVER["REQUEST_URI"];
	  }
      //重写时候没有index.php，去掉中间html目录 这样动态可以不使用html目录
	  if(0!==strrpos($_SERVER["SCRIPT_NAME"],"/"))
	  {
		  $this->fixpath=substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"));
		  if(strncasecmp($_SERVER["REQUEST_URI"],$this->fixpath.$GLOBALS['config']['realhtml'],strlen($this->fixpath.$GLOBALS['config']['realhtml']))==0)
		  {
			  $_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],strlen($this->fixpath.$GLOBALS['config']['realhtml']));
			  $_SERVER["REQUEST_URI"]=$this->fixpath.$_SERVER["REQUEST_URI"];
		  }
      }
	  //echo(substr($_SERVER["REQUEST_URI"],0,strlen(substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/")))));
	  //如果没有PATH目录
	  if(strncasecmp($_SERVER["REQUEST_URI"],$GLOBALS['config']['realhtml'],strlen($GLOBALS['config']['realhtml']))==0)
	  $_SERVER["REQUEST_URI"]=substr($_SERVER["REQUEST_URI"],strlen($GLOBALS['config']['realhtml']));
	  if(strncasecmp($_SERVER["REDIRECT_URL"],$GLOBALS['config']['realhtml'],strlen($GLOBALS['config']['realhtml']))==0)
	  $_SERVER["REDIRECT_URL"]=substr($_SERVER["REDIRECT_URL"],strlen($GLOBALS['config']['realhtml']));
	  $this->fixpath.=$GLOBALS['config']['realhtml'];
	}
    $this->request_uri = $_SERVER['PATH_INFO'];
    $this->routes = array();
	//if(file_exists($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php"))
	//{
	//  $this->rules=include($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php"); 
	//}
  }
 
  public function map($controller,$rule, $target=array(), $conditions=array()) {
    $this->routes[$controller] = new Route($rule, $this->request_uri, $target, $conditions);
	if($this->routes[$controller]->is_matched) $this->set_route($this->routes[$controller]);
	return $this;
  }
  public function RuleCheck($controller)
  {
    if(isset($this->rules[$controller])) return $this->rules[$controller]; else return false;
  }
  public function isPathInfo() {
  	  Return $this->urlcontroller;
  }
  public function start()
  {
	 $paths = explode("/",trim($_SERVER['PATH_INFO'],'/'));
     $controller=array_shift($paths);
     if(!empty($controller)){
	   $this->urlcontroller=false;
	   $rule=$this->RuleCheck($controller);	   
	 }else{
	   if(isset($_GET['router']))
	   {
		 $controller=$_GET['router'];
	     $this->controller =$_GET['router']; 
		 unset($_GET['router']);		 
		 $this->urlcontroller=true;
	   }
	   if(isset($_GET['action']))
	   {
	     $this->action =$_GET['action']; 
		 unset($_GET['action']);
	   }else{
	     $this->action = $GLOBALS['config']['defaultindex'];
	   }
	 }
	//if($GLOBALS['projectenv']=='product'&&!file_exists($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php"))
	//{
	 // $this->cacheruleMaps();
	//}
	 if($rule){
	     $this->map($controller,$rule['rule'],$rule['target'],$rule['conditions']);
		 if(!$this->routes[$controller]->is_matched){
			 $this->controller=$controller;
		     $this->action=array_shift($paths);
		 }else{
		   return $this;
		 }
	 }else{
		 if(empty($controller))
		 {
			 $this->controller = $GLOBALS['config']['defaultrouter'];
			 $this->action = $GLOBALS['config']['defaultindex'];
			 $this->id = null;	   
		 }else{
		   	 $this->controller=$controller;
		     if($this->action==null) $this->action=array_shift($paths);
		 }
	 }
	 if(is_numeric($paths[0])) { $this->id=array_shift($paths); $_GET['id']=$this->id; }
	for($i=0;$i<count($paths);$i++)
		 $_GET[$paths[$i]]=$paths[++$i];
	return $this;
  }
  public function setMaps($rules)
  {
	   if(is_array($this->rules)&&is_array($rules)) {
            $this->rules  =  array_merge($this->rules,$rules);
        }elseif(is_object($rules)){
            foreach($rules as $key =>$val)
                $this->rules[$key] = $val;
        }elseif(is_array($rules)){
            $this->rules=$rules;
        }
	return $this;	
  }
  public function ruleMaps($rulename,$rule=null, $target=array(), $conditions=array())
  {
    if(is_array($rulename))
	{
	  foreach($rulename as $k=>$v)
	  {
	    $this->rules[$k]=$v;
	  }
	}else if($rule==null){
	  $this->rules[$rulename]=array("rule"=>"/".$rulename);
	}else{
	  $this->rules[$rulename]=array("rule"=>$rule,
		                            "target"=>$target,
		                            "conditions"=>$conditions);
	}
	return $this;
  }
  public function clearruleMaps()
  {
	if(!file_exists($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php"))
	{
	  @unlink($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php");
	}
	 return $this;
  }
  public function cacheruleMaps()
  {
	 file_put_contents($GLOBALS['config']["frameworkpath"]."cache/".$GLOBALS['config']["webprojectname"]."rule.cache.php","<?php return ".var_export($this->rules,TRUE)."; ?>");
	 return $this;
  }
  private function set_route($route) {
    $this->route_found = true;
    $params = $route->params;
    $this->controller = $params['controller']; unset($params['controller']);
    $this->action = $params['action']; unset($params['action']);
    $this->id = $params['id'];
	if(is_numeric($this->controller)&&$this->id==null){
	  $this->id=$this->controller;
	  $this->controller=null;
      $params['id']=$this->id;
	}
	if(is_numeric($this->action)&&$this->id==null){
	  $this->id=$this->action;
	  $this->action=null;
      $params['id']=$this->id;
	} 
    $this->params = array_merge($params, $_GET);
    $_GET=$this->params;
    if (empty($this->controller)) $this->controller = $GLOBALS['config']['defaultrouter'];
    if (empty($this->action)) $this->action = $GLOBALS['config']['defaultindex'];
    if (empty($this->id)) $this->id = null;
 
    $w = explode('_', $this->controller);
    foreach($w as $k => $v) $w[$k] = ucfirst($v);
    $this->controller_name = implode('', $w);
  }
  private function getPathInfo()
  {
        if(!empty($_SERVER['PATH_INFO'])){
            $pathInfo = $_SERVER['PATH_INFO'];
            if(0 === strpos($pathInfo,$_SERVER['SCRIPT_NAME']))
                $path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
            else{
                $path = $pathInfo;
			    if(0 !== strpos($pathInfo,$_SERVER['SCRIPT_NAME'])&&0 === strpos($_SERVER["REDIRECT_URL"],dirname($_SERVER['SCRIPT_NAME'])))
				{
				 $path = substr($_SERVER["REDIRECT_URL"], strlen(dirname($_SERVER['SCRIPT_NAME'])));                
				}
			}
        }elseif(!empty($_SERVER['ORIG_PATH_INFO'])) {
            $pathInfo = $_SERVER['ORIG_PATH_INFO'];
            if(0 === strpos($pathInfo, $_SERVER['SCRIPT_NAME']))
                $path = substr($pathInfo, strlen($_SERVER['SCRIPT_NAME']));
            else
                $path = $pathInfo;
        }elseif (!empty($_SERVER['REDIRECT_PATH_INFO'])){
            $path = $_SERVER['REDIRECT_PATH_INFO'];
        }elseif(!empty($_SERVER["REDIRECT_Url"])){
            $path = $_SERVER["REDIRECT_Url"];
            if(empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == $_SERVER["REDIRECT_QUERY_STRING"])
            {
                $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
                if(!empty($parsedUrl['query'])) {
                    $_SERVER['QUERY_STRING'] = $parsedUrl['query'];
                    parse_str($parsedUrl['query'], $GET);
                    $_GET = array_merge($_GET, $GET);
                    reset($_GET);
                }else {
                    unset($_SERVER['QUERY_STRING']);
                }
                reset($_SERVER);
            }
        }elseif(!empty($_SERVER["REDIRECT_URL"])){
			$_SERVER["REDIRECT_URL"]=str_replace("//","/",$_SERVER["REDIRECT_URL"]);
			$_SERVER["REQUEST_URI"]=$_SERVER["REDIRECT_URL"];
            $path = $_SERVER["REDIRECT_URL"];
            if(empty($_SERVER['QUERY_STRING']) || $_SERVER['QUERY_STRING'] == $_SERVER["REDIRECT_QUERY_STRING"])
            {
                $parsedUrl = parse_url($_SERVER["REQUEST_URI"]);
                if(!empty($parsedUrl['query'])) {
                    $_SERVER['QUERY_STRING'] = $parsedUrl['query'];
                    parse_str($parsedUrl['query'], $GET);
                    $_GET = array_merge($_GET, $GET);
                    reset($_GET);
                }else {
                    unset($_SERVER['QUERY_STRING']);
                }
                reset($_SERVER);
            }
			if(0 !== strpos($pathInfo,$_SERVER['SCRIPT_NAME'])&&0 === strpos($_SERVER["REDIRECT_URL"],dirname($_SERVER['SCRIPT_NAME'])))
			{
			$path = substr($_SERVER["REDIRECT_URL"], strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
        }
		    //是否带有静态文件结尾形式
			if(isset($GLOBALS['config']['html'])&&$GLOBALS['config']['html']!='')
			if(substr($path,-strlen($GLOBALS['config']['html']))==$GLOBALS['config']['html'])
			{
			  $path=substr($path,0,-strlen($GLOBALS['config']['html']));
			}
        $_SERVER['PATH_INFO'] = empty($path) ? '/' : $path;
      	return $this;
	} 
}
 
class Route {
  public $is_matched = false;
  public $params;
  public $url;
  private $conditions;
 
  function __construct($url, $request_uri, $target, $conditions) {
    $this->url = $url;
    $this->params = array();
    $this->conditions = $conditions;
    $p_names = array(); $p_values = array();
    preg_match_all('@:([\w]+)@', $url, $p_names, PREG_PATTERN_ORDER);
    $p_names = $p_names[0];
    $url_regex = preg_replace_callback('@:[\w]+@', array($this, 'regex_url'), $url);
    $url_regex .= '/?';
    if (preg_match('@^' . $url_regex . '@', $request_uri, $p_values)) {
      $sub=array_shift($p_values);
	  $sub=substr($request_uri,strlen($sub));  
      foreach($p_names as $index => $value) $this->params[substr($value,1)] = urldecode($p_values[$index]);
	  preg_replace('@(\w+)/([^,\/]+)@e', '$this->params[\'\\1\']="\\2";',$sub);
      foreach($target as $key => $value) $this->params[$key] = $value;
      $this->is_matched = true;
    } 
    unset($p_names); unset($p_values);
  }
 
  function regex_url($matches) {
    $key = str_replace(':', '', $matches[0]);
    if (array_key_exists($key, $this->conditions)) {
      return '('.$this->conditions[$key].')';
    } 
    else {
      return '([a-zA-Z0-9_\+\-%]+)';
    }
  }
}
?>