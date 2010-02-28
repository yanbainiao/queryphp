<?php
class curdRouter extends controller{
    public function index()
	{
	   $booktype=M("booktype");
	   $this->assign("list",$booktype->orderby("bookid desc")->limit(10)->fetch()->getRecord());
	}
	public function create()
	{
	  
	}
	public function createForm()
	{
	  $booktype=M("booktype")->createForm()->save();
	  if($booktype->isEffect())
	  {
	    $this->assign("msg","添加成功!");
	  }
	}
	public function edit()
	{
	  $form=M("booktype")->get(intval($_GET['id']))->getRecord('0');
	  $this->assign("form",$form);
	}
	public function update()
	{
	  $booktype=M("booktype")->createForm()->save();
	  $this->assign("form",$booktype->getData());
	  if($booktype->isEffect())
	  {
	    $this->assign("msg","修改成功!");
	  }
	}
	public function delete()
	{
	  $booktype=M("booktype")->delete(intval($_GET['id']));
	  if($booktype->isEffect())
	  {
	    $this->assign("msg","删除成功!");
	  }
	}
}
?>