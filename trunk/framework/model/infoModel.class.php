<?php 
class infoModel extends infoBase{ 
  var $mapper=array();
 var $maps;
 var $maparray=array();
 function setPassword($pwd) { 
	 $this->data['password']=md5($pwd);
	 return $this; 
  }
} 
?>