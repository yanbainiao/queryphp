<?php
class controller{
  var $render;
  function assign($name,$value=null)
  {
    C("view")->assign($name,$value);
  }
  function render($view)
  {
     $this->render=$view;
  }
  function fetch($view)
  {
    return C("view")->fetch($view);
  }
  function view($view='')
  {
    if($this->render) return  $this->render;
	return $view;
  }
  function __set($name,$value)
  {
     C("view")->assign($name,$value);
  }
  function __get($name)
  {
     C("view")->get($name);
  }
}
?>