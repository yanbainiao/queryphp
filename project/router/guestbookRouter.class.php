<?php
class guestbookRouter extends controller{
  function isAcl() {
  	//开启认证
  }
  function index()
  {
       $gbook=M("gbook"); 
       $this->pager=C("pager");//取得分页类 
       $this->pager->setPager($gbook->count(),10,'page');//取得数据总数中，设置每页为10 
       $this->assign("glist",$gbook->orderby("id desc")->limit($this->pager->offset(),10)->fetch()->getRecord()); 
	   //输出分页导航
	   $this->assign("nav_bar",$this->pager->getWholeBar(url_for("guestbook/index/page/:page"))); 
  }
  function add() {
    //添加留言
    $gbook=M("gbook")->create("fix",array("content"=>"comment"))->save();
	if($gbook->isEffect()) 
    { 	  
      $this->assign("msg","添加成功!"); 
    }
	redirect(url_for("guestbook/index"),"添加成功!",3);
  }
  /*
  *提交前做下过滤
  *如果返回false不会执行add()的
  *注意本文件要保存为utf-8因为提交的是utf-8编码。
  */
  public function pre_add() {
  	if(preg_match("/枪支|你知道太多了/",$_POST['comment']))
	{
	 redirect(url_for("guestbook/adminlist"),"非法提交!",3);
	}
	if($_SERVER['REMOTE_ADDR']=='192.168.0.8')
	{
	 redirect(url_for("guestbook/index"),"你被黑了",3);
	}
  }
  public function adminlist() {
  	   $gbook=M("gbook"); 
       $this->pager=C("pager");//取得分页类 
       $this->pager->setPager($gbook->count(),10,'page');//取得数据总数中，设置每页为10 
       $this->assign("glist",$gbook->orderby("id desc")->limit($this->pager->offset(),10)->fetch()->getRecord()); 
	   //输出分页导航
	   $this->assign("nav_bar",$this->pager->getWholeBar(url_for("guestbook/adminlist/page/:page"))); 
  }
  public function editpost() {
  	  $gbook=M("gbook")->create()->update();
	if($gbook->isEffect()) 
    { 	  
      $this->assign("msg","修改成功!"); 
    }
	  redirect(url_for("guestbook/adminlist"),"修改成功",3);
  }
  public function delete() {
  	  $id=intval($_GET['id']);
      $gbook=M("gbook")->delete($id);
	  redirect(url_for("guestbook/adminlist"),"删除成功",3);
  }
  public function login(){

  }
  public function logout(){

	  MY()->logout();
     redirect(url_for("guestbook/index"),"退出成功",3);
  }
  public function noAcl($mask) {
  	redirect(url_for("guestbook/login"),"需要登录",3);
  }
  public function loginpost() {
  	if($_POST['author']=='queryphp'&&md5($_POST['pwd'])==md5('123456'))
	{
	  MY()->setLogin();
	  redirect(url_for("guestbook/adminlist"),"登录成功",3);
	}
	redirect(url_for("guestbook/login"),"登录失败",3);
  }
}
?>