<?php

/*
*访问控制基类，可以在router里面继承
*
*/
class acl {
  public $acl=array("all"=>512);	//表示需要管理员才能访问
  public $acldb=false;          //是否动态访问权限数据库
  public $aclid=array("all"=>0);
  public $pwd='';
  public $date=array('begin'=>0,'end'=>0);
  public $hours=array('begin'=>0,'end'=>0);
  public $weeks=array('begin'=>0,'end'=>0);
  public $roledisable=array(); //禁用表列
  public $error_method='';
  /*
  *权限检查，测试
  *
  */
  public function aclCheck($router,$action='') {
	 if(!isset($this->acl[$action])){ //如果没有定义方法权限值
       if($this->acl['all']!=0)     //使用方all值
	   {
	     $this->acl[$action]=$this->acl['all']; //取得all值
		 $action='all'; //把$acton方法也使用all
	   }else{
	     Return true;//如果都是0返回 如果又不定义$action方法，all值也是为0说明 程序模块不需要保护
	   }
  	 }
	 if($this->acl[$action]==0){
		 Return true; //如果设置了$action方法为0说明不用保护，就算all为512也不用保护 
  	 }
     	if(isset($this->aclid[$action])&&in_array($this->aclid[$action],MY()->acl))
		 {
		   $this->error=L(" 你没有权限 ");
		   if(empty($this->error_method)){
			  $this->error_method='noPassport';
			}
		   Return false;
		 }

	  $mask=0;
	  /*
	  *是否登录
	  */
	  if($this->acl[$action]&1)
	  {
	     if(MY()->isLogin())
		 {
		   $mask=1;
		 }else {
		 	$this->error.=L(" 需要登录 ");
			if(empty($this->error_method)){
			  $this->error_method='noLogin';
			}
		 }
	  }
	  /*
	  *自身管理
	  */
	  if($this->acl[$action]&2)
	  {
	     if(MY()->isLogin())
		 {
		   $mask=$mask+2;
		 }else {
		 	$this->error.=L(" 需要登录 ");
			if(empty($this->error_method)){
			  $this->error_method='noLogin';
			}
		 }
	  }
	  /*
	  *组的限制
	  */
	  if($this->acl[$action]&4)
	  {
	     if(count(array_diff(explode(",",$this->aclgroup[$action]),MY()->group))==0)
		 {
		   $mask=$mask+4;
		 }else {
		 	$this->error.=L(" 你没有组的权限 ");
			if(empty($this->error_method)){
			  $this->error_method='noGroup';
			}
		 }
	  }
	  /*
	  *身份的限制
	  */
	  if($this->acl[$action]&8)
	  {
	     if(count(array_diff(explode(",",$this->aclrole['all'].",".$this->aclrole[$action]),MY()->group))==0)
		 {
		   $mask=$mask+8;
		 }else {
		 	$this->error.=L(" 你没有身份访问 ");
			if(empty($this->error_method)){
			  $this->error_method='noRole';
			}
		 }
	  }
	  /***
	  *禁止身份登录
	  *
	  ***/
	  if($this->acl[$action]&16) {
	  	if(count(array_intersect($this->roledisable,array_merge(MY()->array_multi2single(MY()->grouprole),MY()->role)))==0) {
	  		$mask=$mask+16;
	  	}else {
		 	$this->error.=L(" 你的身份被禁止访问 ");
			if(empty($this->error_method)){
			  $this->error_method='noAccess';
			}
		 }
	  }
	  /***
	  *日期禁止函数
	  *
	  ***/
	  if($this->acl[$action]&32) {
	  	if($this->checkVisitDate())
		{
		  $mask=$mask+32;
		}else {
		 	$this->error.=L(" 不许可访问的日期 ");
			if(empty($this->error_method)){
			  $this->error_method='noDate';
			}
		 }
	  }
	  /***
	  *日间限制
	  *周的限制
	  ***/
	  if($this->acl[$action]&64) {
	  	if($this->checkVisitWeeks())
		{
		  $mask=$mask+64;
		}else {
		 	$this->error.=L(" 不许可访问的周日 ");
			if(empty($this->error_method)){
			  $this->error_method='noWeek';
			}
		 }
	  }
	  /***
	  *日间限制
	  *小时的限制
	  ***/
	  if($this->acl[$action]&128) {
	  	if($this->checkVisitHours())
		{
		  $mask=$mask+128;
		}else {
		 	$this->error.=L(" 不许可访问的时间 ");
			if(empty($this->error_method)){
			  $this->error_method='noTime';
			}
		 }
	  }
	  /***
	  *密码限制1
	  *
	  ***/
	  if($this->acl[$action]&256) {
	  	if(!empty($this->pwd)&&($this->pwd==MY()->modelpwd||$this->pwd==trim($_POST['requestpwd'])))
		{
		  $mask=$mask+256;
		}else {
			MY()->loginfaild++;
		 	$this->error.=L(" 要输入密码才能访问 ");
			if(empty($this->error_method)){
			  $this->error_method='noPwd';
			}
		 }
	  }
	  /***
	  *管理员功能
	  *
	  ***/
	  if($this->acl[$action]&512) {
	  	if(MY()->isadmin==true)
		{
		  $mask=$mask+512;
		}else {
		 	$this->error.=L(" 超级管理使用 ");
			if(empty($this->error_method)){
			  $this->error_method='noPassport';
			}
		 }
	  }
	  $result=$this->acl[$action]^$mask;
	  if($result==0)
	  {
	    Return true;
	  }else{
	    Return $result; //返回缺少的权限
	  }
  }

  /***
  *比较周日函数
  *周一到周七
  ***/
  public function checkVisitWeeks() {
  	$now=date("N");
	if($this->weeks['begin']<=$now&&$now<=$this->weeks['end'])
	Return true;
	else {
		Return false;
	}
  }
  /***
  *比较时间函数
  *0到23
  ***/
  public function checkVisitHours() {
  	$now=date("H");
	if($this->hours['begin']<=$now&&$now<=$this->hours['end'])
	Return true;
	else {
		Return false;
	}
  }
  /***
  *比较日期函数
  *
  ***/
  public function checkVisitDate() {
  	$now=time();
	if(strtotime($this->date['begin'])<$now&&$now<strtotime($this->date['end']))
	Return true;
	else {
		Return false;
	}
  }
  public function noPassport(){
    
  }
  /*
  *没有权限处理
  */
  public function noAcl($mask,$output=true) {
     $this->{$this->error_method}($mask);
	 if($output)
     redirect($this->getErrorUrl(),$this->error,3);  
  }
  /***
  *取得错误地址
  *
  ***/
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

  }
  /*
  *组管理设置sql
  *返回所有组成员UID
  */
  public function groupWhere($model,$uidkey) {
 	  $model=is_object($model)?$model->modelname:$model;
	  if(empty(MY()->group)) Return false;
  	  M($model)->whereIN($uidkey,implode(",",MY()->group));
	  Return true;     
  }
  /*
  *返自身设置的sql
  *返回UID的sql
  */
  public function userWhere($model,$uidkey) {
	  $model=is_object($model)?$model->modelname:$model;
	  if(empty(MY()->uid)) Return false;
  	  M($model)->whereAnd($uidkey,MY()->uid);
	  Return true;
  }
  /***
  *组的限制
  *
  ***/
  public function noGroup() {
  	
  }
  /***
  *身份的限制
  *
  ***/
  public function noRole() {
  	
  }
  /***
  *没有访问权限
  *
  ***/
  public function noAccess() {
  	
  }
  /***
  *访问日期被禁止
  *
  ***/
  public function noDate() {
  	
  }
   /***
  *禁止访问周日
  *
  ***/ 
  public function noWeek() {
  	
  }
  /***
  *时间段禁止访问
  *
  ***/
  public function noTime() {
  	
  }
  /***
  *没有密码
  *
  ***/
  public function noPwd() {
  	Return '<form method=post action="'.url_for(C("router")->controller."/".C("router")->action).'"><INPUT TYPE="text" NAME="requestpwd"><INPUT TYPE="submit"></form>';
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