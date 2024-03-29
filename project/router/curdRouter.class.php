<?php
class curdRouter extends controller{
    
	//返回 RBAC 控制访问列表验证类默认是跟router同名也就是curd
	//可以不写这个函数，那么不会启用通用权限系统。
    public function index()
	{
	   $booktype=M("booktype");
	   $this->pager=C("pager");//取得分类
	   $this->pager->setPager($booktype->count(),10,'page');//取得数据总数中，设置每页为10
	   $this->assign("list",$booktype->orderby("bookid desc")->limit($this->pager->offset(),10)->fetch()->getRecord());
	}
	public function create()
	{
	  //自动显示view/curd/目录下create.php文件
	}
	public function createForm()
	{
	  $booktype=M("booktype")->create()->save();
	  //看看mysql没有操作成功
	  if($booktype->validData('add')&&$booktype->isEffect())
	  {
	    $this->assign("msg","添加成功!");
	  }else{
	    print_r($booktype->showError());
	  }
	}
	public function show()
	{
	  //显示数据
	  $form=M("booktype")->get(intval($_GET['id']))->getData();
	  $this->assign("form",$form);
	}
	public function edit()
	{
	  //->edit()为原来up函数，现在改为edit表示编辑那个record默认是record[0];
	  $form=M("booktype")->get(intval($_GET['id']))->getData();
	  $this->assign("form",$form);
	}
	public function update()
	{
	  $booktype=M("booktype")->create()->save();
	  $this->assign("form",$booktype->getData());
	  if($booktype->isEffect())
	  {
	    $this->assign("msg","修改成功!");
	  }
	}
	public function delete()
	{
	  $booktype=M("booktype")->delete(intval($_GET['id']));
	  //检查有没有操作成功
	  if($booktype->isEffect())
	  {
	    $this->assign("msg","删除成功!");
	  }
	}
}
?>