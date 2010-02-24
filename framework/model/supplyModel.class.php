<?php 
class supplyModel extends supplyBase{ 
  var $mapper=array("Books"=>array("map"=>"hasMany","TargetModel"=>"booktype","localFiled"=>"typeid","targetFiled"=>"typeid","localFiled2"=>"bookid","targetFiled2"=>"bookid","localFiled3"=>"supplyid","targetFiled3"=>"supplyid"));
 var $maps;
 var $maparray=array();
 
} 
?>