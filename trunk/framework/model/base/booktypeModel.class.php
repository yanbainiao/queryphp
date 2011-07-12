<?php 
class booktypeModel extends booktypeBase{ 
  var $mapper=array("Supply"=>array("map"=>"hasMany","TargetModel"=>"supply","mapping"=>array("bookid"=>"bookid")),
	                "Infos"=>array("map"=>"hasOne",
	                               "TargetModel"=>"info",
	                               "mapping"=>array("typeid"=>"typeid")));
 var $maps;
 var $maparray=array();
 
} 
?>