<?php 
class booktypeModel extends booktypeBase{ 
  var $mapper=array("Supply"=>array("map"=>"hasOne","TargetModel"=>"supply","localFiled"=>"typeid","targetFiled"=>"typeid"));
 var $maps;
 var $maparray=array();
 
} 
?>