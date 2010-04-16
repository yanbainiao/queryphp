<?php
/*
*控制访问表
*  acl值    功能
*	1        需要登录
*	2		 自身修改
*	4		 需要组的权限集合		 
*	8		 需要身份访问集合	
*	16		 身份被禁止访问	
*	32		 可访问的日期	
*	64		 可访问的周日	
*	128		 可访问的时间	
*	256		 输入密码才能访问	
*	512		 超级管理使用	
*/
class aclACL extends acl {
	public $routername="acl";
	public $aclid=array("all"=>2,"index"=>3);   //权限资源ID,如果登录人员没有拥用这个权限那么其（下面）它值都为0也不能访问方法数组ID rbacid
	public $roledisable=array(9); //禁用身份id
	public $pwd=123456;           //密码访问 ACL->noPwd();
	public $date=array('begin'=>0,'end'=>0);   //允许日期之间
	public $hours=array('begin'=>0,'end'=>0);  //一日内小时区间
	public $weeks=array('begin'=>0,'end'=>0);  //一周内周一到周七
	public $aclgroup=array("create"=>"4,45,8"); //create需要的组才能创建
	public $aclrole=array("all"=>"6","create"=>"7,95,78"); //create需要的角色才能创建,该组需要ID为6的角色才能访问
	public $acl=array("all"=>0,
		              "index"=>1,    //表列0表示任何人可以访问
		              "delete"=>1,   //删除只登录后删除,当然呆以设置为2或4
		              "update"=>1,   //更新提交只能登录后才能更新，在这里做也防止非法、post，edit是不能访问显示编辑内容页
		              "createForm"=>1, //也不能新提交数据库
	                  "edit"=>0,       //登录才显示编辑框
		              "show"=>0,       //不用登录也能显示
		              "create"=>1);    //创新表单需要登录操作 可以设置某个组才能创建

} 
?>