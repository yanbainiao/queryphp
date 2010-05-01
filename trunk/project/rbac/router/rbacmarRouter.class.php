<?php
 class rbacmarRouter extends controller {
   public	function index() {
         
 	}
	/***
	*登录验证
	*
	***/
   public function loginpost() {
   	  $user=M("supperadmin");
	  if($user->login(array('adminname'=>$_POST['loginname'],'adminpwd'=>$_POST['pwd'])))
	  {
	    redirect(url_for("rbacmar/admin",true),"登录成功!",3);
	  }else{
	    redirect(url_for("rbacadmin/index"),"登录失败!",3);
	  }
   }
   /***
   *管理列表
   *
   ***/
  public function admin() {
  	
  }
 } 
?>