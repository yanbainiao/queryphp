<?php
class aclRouter extends controller{
  public function isAcl(){}
  public function index()
  {
	// MY()->logout();
  }
 function show() {

 }
 function login() {
 	MY()->isadmin=true;
	MY()->acl=array(2);
	MY()->session("acl",json_encode(array(2)));
	MY()->session("uid",2);
	MY()->setLogin();
	MY()->session("acl",json_encode(array(2)));
 }
}
?>