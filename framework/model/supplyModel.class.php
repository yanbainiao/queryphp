<?php 
class supplyModel extends supplyBase{ 
  var $mapper=array("Books"=>array("map"=>"hasMany",
	                               "TargetModel"=>"booktype",
	                               "mapping"=>array("bookid"=>"bookid")
	                               ),
	                "Infos"=>array("map"=>"hasOne",
	                               "TargetModel"=>"info",
	                               "mapping"=>array("typeid"=>"typeid")));
 var $maps;
 var $maparray=array();
 
} 
?>