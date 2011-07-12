<?php
 class obj_test {
    function __construct() {
    	
    }	
	function show() {
		echo "ssss";
	}
	function show_aaww() {
		echo "bbbb";
	}
  function __call($name,$Args) {
  	$this->{str_replace(".","_",$name)}();
  }
 } 
 $a=new obj_test();
 $a->show.aaww();
?>