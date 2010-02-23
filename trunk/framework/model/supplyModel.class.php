<?php 
class supplyModel extends supplyBase{ 
  var $mapper=array("Books"=>array("map"=>"ManyhasMany","TargetModel"=>"booktype","localFiled"=>"typeid","targetFiled"=>"typeid"));
  var $maps;
  var $maparray=array();
} 
?>