<?php
 class rbacadminRouter extends controller {
   public	function index() {
          
 	}
   public function loginpost() {
   	  $user=M("supperadmin");
	  if($user->login(array('adminname'=>$_POST['loginname'],'adminpwd'=>$_POST['pwd'])))
	  {
	    redirect(url_for("rbacmar",true),"登录成功!",3);
	  }else{
	    redirect(url_for("rbacadmin/index"),"登录失败!",3);
	  }
   }
 } 
?>