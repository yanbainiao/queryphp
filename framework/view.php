<?php
class view{
  var $vvar=array();
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
	public function display($viewfile='index')
	{
       echo $this->fetch($viewfile);	
	}
    public function fetch($viewfile='',$display=false)
    {
	    $content ="";
		if(INI("view")=='')
		{
			if(file_exists(INI("rootpath")."view/".$viewfile.".php"))
			   $viewfile=INI("rootpath")."view/".$viewfile.".php";
			if(file_exists(INI("rootpath")."view/".C("router")->controller."/".$viewfile.".php"))
			   $viewfile=INI("rootpath")."view/".C("router")->controller."/".$viewfile.".php";
			ob_start();
			ob_implicit_flush(0);
			extract($this->vvar, EXTR_OVERWRITE);
			include $viewfile;
			$content = ob_get_clean();
		}elseif(INI("view")=="Smarty")
		{
		  $Smarty=C("Smarty");
		  $Smarty->assign($this->vvar);
		  $content=$Smarty->fetch($viewfile);
		}
        return $content;
    }
}
?>