<?php

/*
*访问控制基类，可以在router里面继承
*
*/
class acl {
  public $acl=array("all"=>32);	//表示需要管理员才能访问
  public $acldb=false;          //是否动态访问权限数据库
  public $mid="1";
  
  /*
  *权限检查，测试
  *
  */
  public function aclCheck($router,$action) {
	 if(!isset($this->acl[$action])){
       if($this->acl['all']!=0)
	   {
	     $this->acl[$action]=$this->acl['all'];
	   }else{
	     Return true;//如果都是0返回
	   }
  	 }
	 if($this->acl[$action]==0){
		 Return true; 
  	 }
	 if(!in_array($this->mid,MY()->acl))
	 {
	   Return false;
	 }
	  /*
	  *是否登录
	  */
	  if($this->acl[$action]&1)
	  {
	     if($this->isLogin())
		 {
		   $mask=1;
		 }
	  }
	  /*
	  *自身管理
	  */
	  if($this->acl[$action]&2)
	  {
	     if($this->isLogin())
		 {
		   $mask=$mask+2;
		 }
	  }
	  /*
	  *组的限制
	  */
	  if($this->acl[$action]&4)
	  {
	     if(array_diff(explode(",",$this->aclgroup[$action]),MY()->group))
		 {
		   $mask=$mask+4;
		 }else{
		   $mask=0;
		 }
	  }
	  /*
	  *身份的限制
	  */
	  if($this->acl[$action]&8)
	  {
	     if(array_diff(($this->aclrole['all'].",".$this->aclrole[$action]),MY()->group))
		 {
		   $mask=$mask+8;
		 }else{
		   $mask=0;
		 }
	  }
  }
  function checkLogin() {
  	
  }
  /*
  *没有权限处理
  */
  public function noAcl($mask) {
    $str=L('你没有足够的权限。');
	redirect($this->getErrorUrl(),$str,3);    
  }
  function getErrorUrl() {
  	$dispaths=C("router");
	if(isset($this->router)&&$this->router!='')
	{
	  $dispaths->controller=$this->router; 
	}else{
	  $dispaths->controller="default";
	}
	if(isset($this->router)&&$this->router!='')
	{
	  $dispaths->action=$this->action; 
	}else{
	  $dispaths->action="index";
	}
	Return url_for($dispaths->controller."/".$dispaths->action);
  }
  /*
  *没有登录出错方法
  */
  public function noLogin() {
    $str=L('需要登录才能访问。');
	redirect($this->getErrorUrl(),$str,3);
  }
  /*
  *没有权限出错方法
  */
  public function noAccess() {
  	
  }
  /*
  *组管理设置sql
  *返回所有组成员UID
  */
  public function groupWhere($model,$uidkey) {
  	
  }
  /*
  *返自身设置的sql
  *返回UID的sql
  */
  public function userWhere($model,$uidkey) {
  	
  }
  /*
  *是否我的组成员
  *需要是当前router里面需要的组的成员
  */
  public function isMyGroup($uid) {
  	
  }
  /*
  *该时间不能访问
  */
  public function timeout() {
  	
  }
  /*
  *需要输入密码
  */
  public function loginpwd() {
  	
  }
  /*
  *没有访问次数了
  */
  public function EmptyView() {
  	
  }
  public function isLogin() {
  	Return MY()->isLogin();
  }
  /*
  *是否我的UID
  *就是防止非法提交修改
  */
  public function isMyUid($uid) {
     Return MY()->UID()==$uid?true:false;
  }
  /*
  *是否管理员
  */
  public function isAdmin() {
  	
  }
}
?>