<?php
class view{
  var $vvar=array();
  protected $content;
  public function assign($name,$value=''){
        if(is_array($name)) {
            $this->vvar   =  array_merge($this->vvar,$name);
        }elseif(is_object($name)){
            foreach($name as $key =>$val)
                $this->vvar[$key] = $val;
        }else {
            $this->vvar[$name] = $value;
        }
    }
    public function get($name){
        if(isset($this->vvar[$name]))
            return $this->vvar[$name];
        else
            return false;
    }
	public function filter(){}
	public function display($viewfile='index',$display=true)
	{
       $this->content=$this->fetch($viewfile);
	   //视图自身过滤输出内容
	   $this->filter();
	   //使用路由类过滤输出内容,就是每个路由可以自定义输出内容过滤
	   if(method_exists(R(C("router")->controller),"view_filter"))
	   {
	     $this->content=call_user_func(array(R(C("router")->controller),"view_filter"),$this->content);
	   }
	   if($display===true){
		   echo $this->content;
	   }else {
	   	 Return $this->content;
	   }
	}
    public function fetch($viewfile='',$display=false)
    {
	    $content ="";
		if(I("view")=='')
		{
			if(file_exists(P("webprojectpath")."view/".$viewfile.".php"))
			   $viewfile=P("webprojectpath")."view/".$viewfile.".php";
			elseif(file_exists(P("webprojectpath")."view/".C("router")->controller."/".$viewfile.".php"))
			   $viewfile=P("webprojectpath")."view/".C("router")->controller."/".$viewfile.".php";
			elseif(file_exists(P("viewpath")."view/".$viewfile.".php"))
			   $viewfile=P("viewpath")."view/".$viewfile.".php";
			elseif(file_exists(P("viewpath")."view/".C("router")->controller."/".$viewfile.".php"))
			   $viewfile=P("viewpath")."view/".C("router")->controller."/".$viewfile.".php";
			ob_start();
			ob_implicit_flush(0);
			extract($this->vvar, EXTR_OVERWRITE);
			include $viewfile;
			$content = ob_get_clean();
		}elseif(I("view")=="Smarty")
		{
		  $Smarty=C("Smarty");
		  $Smarty->assign($this->vvar);
		  $content=$Smarty->fetch($viewfile);
		}
        return $content;
    }
}
?>