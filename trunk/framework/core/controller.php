<?php
class controller{
  var $render;
  var $htmlpath;
  function assign($name,$value=null)
  {
    C("view")->assign($name,$value);
  }
  function render($view)
  {
     $this->render=$view;
  }
  /*
  * 输出
  * echo R("aaa")->fetch("router");
  */
  function fetch($view)
  {
	//返回本路由类和输出内容
	if(method_exists($this,$view))
	{
	  $this->{$view}();
	  $view=substr(get_class($this),0,-6)."/".$view;
	}
    return C("view")->fetch($view);
  }
  function view($view='')
  {
    if($this->render) return  $this->render;
	return $view;
  }
  function __set($name,$value)
  {
	 C("view")->vvar[$name]=$value;
  }
  function __get($name)
  {
    return C("view")->get($name);
  }
  /*
  *可以自己设置生成html文件名
  *不然使用$_SERVER["REQUEST_URI"]生成
  */
  function setHtmlPath($htmlpath) {
  	$this->htmlpath=$htmlpath;
  }
  /*
  *显示最后一步过滤内容,比如生成html，替换显示内容再输出.
  *可以继承本方法,处理过的内容再调用父类view_filter()也就是本方法生成html
  *$GLOBALS['config']['htmlcache']['class'],$GLOBALS['config']['htmlcache']['method']
  *在inc.ini.php中设置当然也可以在project中设置这样每一个项目都不有同的缓存方法。
  *如果只是重写模拟html静态页面可以把
  *$GLOBALS['config']['htmlcache']['class']),$GLOBALS['config']['htmlcache']['method']设置为空
  *$GLOBALS['config']['htmlcache']=''或注释掉
  */
  function view_filter($content) {
    if(C("router")->isPathInfo()||C("router")->isScript) Return $content;
  	if(isset($GLOBALS['config']['html'])&&(substr($_SERVER["REQUEST_URI"],-strlen($GLOBALS['config']['html']))==$GLOBALS['config']['html']))
    {	  
	  if(empty($this->htmlpath))
	  {
	   if(isset($GLOBALS['config']['realhtml']))
	   {   
		 $this->htmlpath=$GLOBALS['config']['realhtml'].substr($_SERVER["REQUEST_URI"],strlen(substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"))));
	   }else{
		 $this->htmlpath=substr($_SERVER["REQUEST_URI"],strlen(substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],"/"))));
	   }
	  }
	  $this->htmlpath=filepath_safe($this->htmlpath);//消除安全目录
	  //把project目录补上
	  $htmlpath=$GLOBALS['config']["webprojectpath"].$this->htmlpath;
	  $htmlpath=str_replace("//","/",$htmlpath);
      //看看有没有设置缓存类，没有就不生成，只做html结尾模拟
	  if(isset($GLOBALS['config']['htmlcache']['class'])&&class_exists($GLOBALS['config']['htmlcache']['class']))
	  {
	    call_user_func(array(C($GLOBALS['config']['htmlcache']['class']),$GLOBALS['config']['htmlcache']['method']),$content,$htmlpath);
	  }
	}
	Return $content;
  }
}
?>