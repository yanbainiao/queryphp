<?php 
class booktypeModel extends booktypeBase{ 
   public $modelname='booktype';
  public $fix='';
 public $mapper=array();
 public $maps;
 public $maparray=array();
    var $valid=array('add'=>array(
                                       'classname'=>array(

                                                         'url'=>array('error'=>"url必需填写。")

                                                         )

                                             ));

} 
?>