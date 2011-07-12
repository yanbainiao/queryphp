<?php
class rbacRouter extends controller {
   public function index() {

   }
   public function help() {
   	
   }
	public function _pre($a) {
		if($a!='index'&&$a!='loginpost')
		{
		  if(!isset($_SESSION['suadmin']))
		  {
		    redirect(url_for("rbac/index"),"你没有权限或登录超时!",3);
		  }
		}
		Return false;
	}

    public function main() {
   	  
   }  
    public function siteleft() {
   	  
   }  
    public function right() {
   	  
   }  
	//超级管理员登录
   public function loginpost() {
   	  $user=M("r.supperadmin");
	  if($user->where('adminname',$_POST['username'])->whereAnd('adminpwd',md5($_POST['password']))->fetch()->getData())
	  {
	  $_SESSION['ismar']=$user->isMar;
	  $_SESSION['suadmin']=$user->pkid();
	    redirect(url_for("rbac/main",true),"登录成功!",3); //超级管理员跳转地址
	  }else{
	    redirect(url_for("rbac/index"),"登录失败!",3);
	  }
   }
  public function logout() {
	$_SESSION['suadmin']='';
	unset($_SESSION['suadmin']);
	redirect(url_for("rbac/index"),"成功退出!",3);
  }
   /*
   *超级管理员开始
   */
  public function superlist() {
  	$user=M("r.supperadmin");
	$this->userrecord=$user->getAll()->getRecord();

  }
  public function deletesuper() {
  	$sid=intval($_GET['sid']);
	 $user=M("r.supperadmin");
	 $user->get($sid);

	$myuid=$_SESSION['suadmin'];
	$id=intval($myuid);
	$m=$user->getData();
	 if($m['isMar']!='Y')
	 {
	   if($_SESSION['suadmin'])
	   {
		  $user->clearEdit()->newSQL();
		  $user->get($id);

		  if($id>0&&$user->isMar=='Y')
		  {		
		   $user->delete($sid);
		   $msg="删除成功1!";
		  }else{
		   $msg="删除失败1!";
		  }
	   }else{
	     $msg="请登录!";
	   }
	 }elseif($user->getismar()=='Y'){
       if($id!=$sid)
	   {
	    $user->delete($sid);
		$msg="删除成功2!";
	   }else{
	    $msg="删除失败2!";
	   }	   
	 }
    redirect(url_for("rbac/superlist"),$msg,3);
  }
  public function addsuper() {
  	
  }
  public function addsuperpost() {
    $user=M("r.supperadmin");
	$uinfo=array();
	$uinfo=$user->where("adminname='".trim($_POST['adminname'])."'")->count();
	if($uinfo>0)
	{
	  $msg="添加失败,不能重名!";
	}else{
	  $uinfo=array();
	  $uinfo['adminname']=trim($_POST['adminname']);	
	  if($_POST['adminpwd']!=$_POST['adminpwd1'])
	  $msg="密码前后不同!";
      $uinfo['adminpwd']=md5(strtolower(trim($_POST['adminpwd'])));
	  $uinfo['linkname']=trim($_POST['linkname']);
	  $user->clearEdit($uinfo);
	  $user->newSQL()->save();
	  if($user->getSupperid()>0)
	  {
	    $msg="添加管理员成功!";
	  }else{
	    $msg="添加管理员失败!";
	  }
	}

	redirect(url_for("rbac/superlist"),$msg,3);
  }
  public function editsuper() {
  	$sid=intval($_GET['sid']);
	$myuid=$_SESSION['suadmin'];
	$id=intval($myuid);
		  
	$user=M("r.supperadmin");
	$info=array();
	$info=$user->get($id)->getData();
	$this->info=array();
	if($info['isMar']=='Y'||$sid==$id)
	{
	  $this->info=$user->get($sid)->getData();
	}else{
	  redirect(url_for("rbac/superlist"),"你不是管理员，不能编辑",3);
	}
  }
  public function editsuperpost() {
  	$sid=intval($_GET['sid']);
	$myuid=$_SESSION['suadmin'];
	$id=intval($myuid);
		  
	$user=M("r.supperadmin");
	$info=array();
	$info=$user->get($id)->getData();
	$this->info=array();
	if($info['isMar']=='Y'||$sid==$id)
	{

      $user->clearEdit()->newSQL();
	  
	  if($user->where("adminname='".trim($_POST['adminname'])."'")->count()==0)
	  {
	     $uinfo['adminname']=trim($_POST['adminname']); 	    
	  }
	  $user->clearEdit()->newSQL();
	  $uinfo['linkname']=trim($_POST['linkname']);  
	  if($_POST['adminpwd']!=$_POST['adminpwd1'])
	  $msg="密码前后不同!";
	  else{
       if($_POST['adminpwd']!='')
	   $uinfo['adminpwd']=md5(strtolower(trim($_POST['adminpwd'])));
	  
	  }
	  $user->where('supperid',$sid)->update($uinfo);
	   $msg="修改成功!";

	}else{
	  $msg="不是管理员，不能修改!";
	}
   redirect(url_for("rbac/superlist"),$msg,3);
  }
/*
*超级管理员结束
*/
public function deleteuser() {
	$sid=intval($_GET['sid']);
	$u=M("r.user");
	$u->where("uid='".$sid."'")->delete($sid);
	redirect(url_for("rbac/mymemberlist"),"删除成功!",3);
  }
  public function useredit() {
  	$sid=intval($_GET['sid']);
	$u=M("r.user");
	  if($_SESSION['cid']!='')
	  {
	    $u->where("projectid='".intval($_SESSION['cid'])."'");
	  }
	  $this->info=$u->where("uid='".$sid."'")->limit(1)->fetch()->getData();
	    	  	$p=M("r.project");
	$p->getAll();
	$this->projectlist=$p->getRecord();
  }
  public function mymemberlist() {
      $u=M("r.user");
       $this->pager=C("pager");//取得分页类 
	   $this->pager->setPager($u->count(),20,'page');//取得数据总数中，设置每页为10 
       $this->assign("userlist",$u->orderby("uid desc")->limit($this->pager->offset(),20)->fetch()->getRecord()); 
	   //输出分页导航
	   $this->assign("nav_bar",$this->pager->getWholeBar(url_for("rbac/mymemberlist/page/:page"))); 
  }
 public function urbaccache() {
	$sid=intval($_GET['sid']);
 	$u=M("r.user");
	$u->rbaccache=json_encode(array());
	$u->where("uid='".$sid."'")->update("rbaccache");
	redirect(url_for("rbac/mymemberlist"),"已清除该人员权限缓存!",3);
 }
  public function userviewrbac() {
	$uid=intval($_GET['sid']);
	$u=M("r.user");
	$this->info=$u->get($uid)->getData();

			$acl=json_decode("[]",true);//$u->rbaccache
			$role=array();
			if(empty($acl))
			{
				$ur=M("r.userrole");
				$role=$ur->where("uid='".$uid."'")->fetch()->getCol('roleid');
				$ur=M("r.groupuser");
				//取得自己所在的组
				$group=$ur->select("gid,isMar")->where("uid='".$uid."'")->fetch()->getCol('gid');
				$mygroupMar=array();
				$groupjc=array();
				if(count($group)>0)
				{
					foreach($ur->getRecord() as $k=>$v)
					{
						if($v['isMar']=='Y')
							$mygroupMar[]=$v['gid'];//我能管理的组
					}
					
					$gr=M("r.grouprole");
					$grouprole=$gr->whereIN("gid",implode(",",$group))->fetch()->getCol('roleid');
					if(count($grouprole)>0)
					foreach($gr->getRecord() as $k=>$v)
					{
						if($v['jicheng']=='Y')
							$groupjc[]=$v['roleid'];//可继承的角色
					}
					$g=M("r.group");
					$mygroupOwn=$g->where("uid='".$uid."'")->fetch()->getCol('gid');
					$rg=M("r.rbacgroup");
					//组拥有的权限
					$groupacl=$rg->whereIn("gid",implode(",",$group))->fetch()->getCol('rbacid');

				}
					$rr=M("r.rbacrole");
					//角色拥有权限
					$role=array_unique(array_merge($role,$groupjc));//合并角色，并消除重复角色
					if(is_array($role)){
					 $myacl=$rr->whereIn("roleid",implode(",",$role))->fetch()->getCol('rbacid');
					}else{
					$myacl=array();
					}
					$role=is_array($role)?$role:array();
					$groupjc=is_array($groupjc)?$groupjc:array();

					$myacl=is_array($myacl)?$myacl:array();
					$groupacl=is_array($groupacl)?$groupacl:array();
					$acl=array_unique(array_merge($myacl,$groupacl));//合并权限

					$cache=array();//缓存权限
					$cache['role']=$role;  //我使用的身份
					$cache['group']=$group; //我所在组
					$cache['grouprole']=$grouprole; //组的身份
					$cache['mygroupMar']=$mygroupMar; //我拥有管理的组
					$cache['mygroupOwn']=$mygroupOwn; //属于我的组
					$cache['groupjc']=$groupjc;	//可继承的角色(身份)
					$cache['acl']=$acl;       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
					$cache['groupacl']=$groupacl;  //组拥用的控制权限
					$cache['myacl']=$myacl;     //我的身份拥用的控制权限

					$u->rbaccache=json_encode($cache);
					$u->where("uid='".$uid."'")->update('rbaccache');
					$this->urbacid=array_flip($acl); 
			}else{
			  //直接使用缓存

					$this->urbacid=array_flip($acl['acl']);       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid

			}
	
 	$r=M("r.rbac");
	$rall=$r->select("rbacid,parentid,model,name,method,isAll")->orderby("parentid asc")->fetch()->getRecord();
	$i=0;
	$j=0;
	$n=0;
	$row=array();//把一组权限先放在一起
	foreach($rall as $k=>$v)
	{
	  if(!isset($row[$v['rbacid']])&&$v['parentid']==0)
	  {
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());		
	  }else{
		if(isset($row[$v['parentid']]))
	    $row[$v['parentid']]['sub'][]=array("name"=>$v['name']."(".$v['method'].")","rbacid"=>$v['rbacid']);	
		else
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());	
	  }
	}
    $this->rlist=$row; 	  	
  }
//人员组管理
public function setusergroup() {
      $check=intval($_GET['check']);
	  $rid=intval($_GET['sid']);
	  $sid=intval($_GET['iid']);
      $g=M("r.groupuser");
	  if($check==1)
	  {
		if($g->where("gid='".$rid."'")->whereAnd("uid='".$sid."'")->count()==0)
		{
		   $g->newSQL();
		   $g->gid=$rid;
           $g->uid=$sid;
		   $g->save();
		     echo json_encode(array("msg"=>"添加成员到组成功"));
		}
	  }else{
	    if($g->where("gid='".$rid."'")->whereAnd("uid='".$sid."'")->count()>0)
		{
           $g->newSQL();
		   $g->where("gid='".$rid."'")->whereAnd("uid='".$sid."'")->delete();
		   	  echo json_encode(array("msg"=>"取消成员所属成功"));
		}
	  }
	  Return 'ajax';		
}
//人员组管理
public function usergroup() {
	$sid=intval($_GET['sid']);
	$ru=M("r.user");
	$this->info=$ru->get($sid)->getData();
		$this->sid=$sid;
	$u=M("r.groupuser");
	$ruid=$u->where("uid='".$sid."'")->fetch()->getCol("gid");
	if(is_array($ruid))
		$this->grlist=array_flip($ruid);

	$r=M("r.group");
	$this->grouplist=$r->getAll()->getRecord();

}
//人员角色管理
public function setuserrole() {
      $check=intval($_GET['check']);
	  $rid=intval($_GET['sid']);
	  $sid=intval($_GET['iid']);
      $g=M("r.userrole");
	  if($check==1)
	  {
		if($g->where("roleid='".$rid."'")->whereAnd("uid='".$sid."'")->count()==0)
		{
		   $g->newSQL();
		   $g->roleid=$rid;
           $g->uid=$sid;
		   $g->save();
		     echo json_encode(array("msg"=>"添加成员角色成功"));
		}
	  }else{
	    if($g->where("roleid='".$rid."'")->whereAnd("uid='".$sid."'")->count()>0)
		{
           $g->newSQL();
		   $g->where("roleid='".$rid."'")->whereAnd("uid='".$sid."'")->delete();
		   	  echo json_encode(array("msg"=>"取消成员角色成功"));
		}
	  }
	  Return 'ajax';		
}
public function userrole() {
	$sid=intval($_GET['sid']);
	$ru=M("r.user");
	$this->info=$ru->get($sid)->getData();
	$u=M("r.userrole");
	$ruid=$u->where("uid='".$sid."'")->fetch()->getCol("roleid");
	if(is_array($ruid))
		$this->grlist=array_flip($ruid);
	$r=M("r.role");
	$this->rolelist=$r->getAll()->getRecord();
	$this->sid=$sid;
}
/*
*用户编辑结束
*/
  public function addproject() {
  		 $this->p=array("1"=>"北京市",
				 "2"=>"天津市",
				 "3"=>"上海市",
				 "4"=>"重庆市",
				 "5"=>"河北省",
				 "6"=>"河南省",
				 "7"=>"山东省",
				 "8"=>"山西省",
				 "9"=>"广东省",
				"10"=>"甘肃省",
				"11"=>"陕西省",
				"12"=>"浙江省",
				"13"=>"江苏省",
				"14"=>"安徽省",
				"15"=>"湖南省",
				"16"=>"湖北省",
				"17"=>"四川省",
				"18"=>"辽宁省",
				"19"=>"吉林省",
				"20"=>"江西省",
				"21"=>"云南省",
				"22"=>"贵州省",
				"23"=>"福建省",
				"24"=>"青海省",
				"25"=>"黑龙江省",
				"26"=>"广西自治区",
				"27"=>"西藏自治区",
				"28"=>"新疆自治区",
				"29"=>"宁夏自治区",
				"30"=>"内蒙古自治区",
				"31"=>"大陆以外地区");
  }
  public function setdailiaction() {
  	 $sid=intval($_GET['sid']);
	 $user=M("r.project");
	 $user->get(intval($sid));  
	 if($_GET['check']==1)
	 {
	   $user->isaction='Y';
	   $msg="代理状态已激活，代理可以在前台登录了。";
	 }else{
	   $user->isaction='N';
	   $msg="您已取消代理激活状态，请把代理名下的企业客户转到其它代理名下。\r\n";
	 }
	 $user->update("isaction");
	 	    echo json_encode(array("msg"=>$msg));
			Return 'ajax';
  }
  public function editproject() {
  	 $sid=intval($_GET['sid']);
	 $user=M("r.project");
	 $user->get(intval($sid));
     $this->info=$user->getData();
	 $this->p=array("1"=>"北京市",
				 "2"=>"天津市",
				 "3"=>"上海市",
				 "4"=>"重庆市",
				 "5"=>"河北省",
				 "6"=>"河南省",
				 "7"=>"山东省",
				 "8"=>"山西省",
				 "9"=>"广东省",
				"10"=>"甘肃省",
				"11"=>"陕西省",
				"12"=>"浙江省",
				"13"=>"江苏省",
				"14"=>"安徽省",
				"15"=>"湖南省",
				"16"=>"湖北省",
				"17"=>"四川省",
				"18"=>"辽宁省",
				"19"=>"吉林省",
				"20"=>"江西省",
				"21"=>"云南省",
				"22"=>"贵州省",
				"23"=>"福建省",
				"24"=>"青海省",
				"25"=>"黑龙江省",
				"26"=>"广西自治区",
				"27"=>"西藏自治区",
				"28"=>"新疆自治区",
				"29"=>"宁夏自治区",
				"30"=>"内蒙古自治区",
				"31"=>"大陆以外地区");
  }
  public function deleteproject() {
  	 $sid=intval($_GET['sid']);
	 $user=M("r.project");
	 $user->delete(intval($sid));
	 	redirect(url_for("rbac/projectlist"),"删除成功!",3);
  }
public function exportdaili() {
	$p=M("r.project");
	$p->getAll("desc");
    $projectlist=$p->getRecord();
	$pre=array("1"=>"北京市",
				 "2"=>"天津市",
				 "3"=>"上海市",
				 "4"=>"重庆市",
				 "5"=>"河北省",
				 "6"=>"河南省",
				 "7"=>"山东省",
				 "8"=>"山西省",
				 "9"=>"广东省",
				"10"=>"甘肃省",
				"11"=>"陕西省",
				"12"=>"浙江省",
				"13"=>"江苏省",
				"14"=>"安徽省",
				"15"=>"湖南省",
				"16"=>"湖北省",
				"17"=>"四川省",
				"18"=>"辽宁省",
				"19"=>"吉林省",
				"20"=>"江西省",
				"21"=>"云南省",
				"22"=>"贵州省",
				"23"=>"福建省",
				"24"=>"青海省",
				"25"=>"黑龙江省",
				"26"=>"广西自治区",
				"27"=>"西藏自治区",
				"28"=>"新疆自治区",
				"29"=>"宁夏自治区",
				"30"=>"内蒙古自治区",
				"31"=>"大陆以外地区");
		date_default_timezone_set('Asia/Shanghai');
		import('@plugin.Classes.PHPExcel');
		$objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory(" php to result file");
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '机构名称')->setCellValue('B1', '省份')->setCellValue('C1', '联系人')->setCellValue('D1', '职务')->setCellValue('E1', '电话')->setCellValue('F1', '手机')->setCellValue('G1', '电子邮件')->setCellValue('H1', '地址')->setCellValue('I1', '帐号');


		$i=2;
        foreach($projectlist as $k=>$v)
		{
		  $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $v['projectname'])->setCellValue('B'.$i,$pre[$v['province']])->setCellValue('C'.$i, $v['linkname'])->setCellValue('D'.$i, $v['job_bm'])->setCellValue('E'.$i, $v['iphone1']."-".$v['iphone2']."-".$v['iphone3'])->setCellValue('F'.$i, $v['mobile'])->setCellValue('G'.$i, $v['email'])->setCellValue('H'.$i, $v['regaddress'])->setCellValue('I'.$i, $v['loginname']);
		  $i++;
		}

		$objPHPExcel->getActiveSheet()->setTitle('代理数据备份');
		$objPHPExcel->setActiveSheetIndex(0);

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="daili'.date("Y_m_d").'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit;
}
  public function projectlist() {
  	$p=M("r.project");
//////////////////////////////////////////////////////////
	$this->pager=C("pager");//取得分页类 
	$this->pager->setPager($p->count(),15,'page');//取得数据总数中，设置每页为10 
	$this->assign("projectlist",$p->orderby($p->pkkey()." desc")->limit($this->pager->offset(),15)->fetch()->getRecord()); 
	//输出分页导航
	$this->assign("nav_bar",$this->pager->getWholeBar(url_for("rbac/projectlist/page/:page",true))); 
//////////////////////////////////////////////////////////
  }
  public function addprojectpost() {
  	$p=M("r.project");
	
	if($p->where("loginname='".addslashes(trim($_POST['loginname']))."'")->limit(1)->count()>0)
	{
	  redirect(url_for("rbac/addproject"),"已有登录名，请填写一个新的登录名!",3);
	}
	$p->clearEdit()->newSQL();
	$p->projectname=$_POST['projectname'];
      

	$p->create()->setloginpwd(md5($_POST['loginpwd']));

	$p->save();
	redirect(url_for("rbac/projectlist"),"添加成功!",3);
  }
  public function editprojectpost() {
	$sid=intval($_POST['sid']);
  	$p=M("r.project");

    if($p->where("loginname='".addslashes(trim($_POST['loginname']))."'")->whereAnd("projectid!='".$sid."'")->count()>0)
	{
	  redirect(url_for("rbac/editproject/sid/".$sid),"已有登录名，请填写一个新的登录名!",3);
	}
	$p->clearEdit()->newSQL();

	$p->get($sid);
    $p->create();

    if(trim($_POST['loginpwd'])=='')
	{
	  unset($p->loginpwd); 
	}else{
	  $p->setloginpwd(md5($_POST['loginpwd']));
	}
	$p->projectname=$_POST['projectname'];
	$p->dest=$_POST['dest'];
	$p->save();
	redirect(url_for("rbac/projectlist"),"添加成功!",3);
  }
  public function adduser() {
  	  	$p=M("r.project");
	$p->getAll();
	$this->projectlist=$p->getRecord();
	
  }
  public function edituserpost() {
    $m=M("r.user");
    $projectid=$_SESSION['cid']!=''?$_SESSION['cid']:intval($_POST['projectid']);
	$username=trim($_POST['username']);
		$uid=intval($_POST['uid']);
	$un=$m->where("username='".$username."'")->where("uid!='".$uid."'")->count();
	$m->clearEdit()->newSQL();
	if($_SESSION['cid']!='')
	{
	  $m->where("projectid='".$projectid."'");
	}


    if(is_numeric($uid))
	  {
		$m->where("uid='".$uid."'");
		

		$u=$m->limit(1)->fetch()->getData();
		if($un>0)
		{
			redirect(url_for("rbac/useredit/sid/".$uid),"已有被试登录名,请修改新的被试登录名!",3);
		}
		$m->username=$username;
		if(trim($_POST['password'])!='')
		{
		  $m->password=md5(trim($_POST['password']));
		}
		if($_SESSION['cid']=='')
		{
		  $m->setprojectid($projectid);
		}
		$m->realname=trim($_POST['realname']);		
		$m->job=trim($_POST['job']);
		$m->sex=trim($_POST['sex'])==1?1:0;
		$m->age=trim($_POST['age']);
		$m->email=trim($_POST['email']);
		$m->xueli=trim($_POST['xueli']);
		$m->save();
		redirect(url_for("rbac/useredit/sid/".$uid),"修改成功!",1);
	  }else{
	    redirect(url_for("rbac/useredit/sid/".$uid),"修改有误，用户ID不正确!",1);
	  }
	
  }
  public function adduserpost() {
    $m=M("r.user");
    $projectid=$_SESSION['cid']!=''?$_SESSION['cid']:intval($_POST['projectid']);
	$username=trim($_POST['username']);

    $u=$m->where("username='".$username."'")->limit(1)->fetch()->getData();
    
    if(isset($u['username'])&&$u['username']==$username)
	{
		redirect(url_for("rbac/adduser"),"已有用户名!",1);
	}
	$m->username=$username;
	$m->password=md5(trim($_POST['password']));
	$m->realname=trim($_POST['realname']);
	$m->setprojectid($projectid);
	$m->job=trim($_POST['job']);
	$m->sex=trim($_POST['sex'])==1?1:0;

	$m->age=trim($_POST['age']);
	$m->email=trim($_POST['email']);
	$m->xueli=trim($_POST['xueli']);
	$m->save();
	redirect(url_for("rbac/adduser"),"添加成功!",1);
  }
 public function userimport() {
  	$p=M("r.project");
	$p->getAll();
	$this->projectlist=$p->getRecord(); 	
	
 } 
 public function adduserimport() {
	 if(strtolower(substr($_FILES['userfile']['name'],-4))=='.xls')
	 {
       if(move_uploaded_file($_FILES['userfile']['tmp_name'],P("webprojectpath")."temp/tempuser.xls")) 
	   {
			if(file_exists(P("webprojectpath")."temp/tempuser.xls"))
		    {
				$m=M("r.user");
				date_default_timezone_set('Asia/Shanghai');
				import('@plugin.Classes.PHPExcel.IOFactory');
				$objPHPExcel =PHPExcel_IOFactory::load(P("webprojectpath")."temp/tempuser.xls");
				$objWorksheet = $objPHPExcel->getActiveSheet();

				$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
				$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'

				$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
				
				$row=1;
				$user=array();
				for ($col = 0; $col < $highestColumnIndex; ++$col) {
					$user[$col]=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				  }
				$tmkey=array();
               //映射字段
			    if(is_array($user))
				{
				  foreach($user as $key=>$value)
				  {
				    $value=str_replace(" ","",trim($value));
					switch($value)
					{
					  case '姓名':
					  	   $tmkey['realname']=$key;
					  	break;
					  case '职务':
					  	   $tmkey['job']=$key;
					  	break;
					  case '性别':
					  	   $tmkey['sex']=$key;
					  	break;
					  case '用户名':
					  	   $tmkey['username']=$key;
					  	break;
					  case '密码':
					  	   $tmkey['password']=$key;
					  	break;
					  case 'email':
					  	   $tmkey['email']=$key;
					  	break;
					  case '电子邮件':
					  	   $tmkey['email']=$key;
					  	break;						
					}
				  }
				  if(empty($tmkey))
				  {
				    $tmkey['realname']=0;
					$tmkey['job']=1;
					$tmkey['sex']=2;
					$tmkey['username']=3;
					$tmkey['password']=4;
				  }
				}
			    
				$projectid=$_SESSION['cid']!=''?$_SESSION['cid']:intval($_POST['projectid']);
				$utable='';
				$utable.='<table width="720" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">' . "\n";
				$utable.='<tr>' . "\n";
				for ($col = 0; $col <=$highestColumnIndex; ++$col) {
				    $utable.='<td class="row1">'. $user[$col] . '</td>' . "\n";
				    }
                $utable.='</tr>' . "\n";
				for ($row = 2; $row <= $highestRow; ++$row) {
				  $utable.='<tr>' . "\n";
                  $user=array();
				  for ($col = 0; $col <=$highestColumnIndex; ++$col) {
                    $user[$col]=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
				  }
                        						
						$m->clearEdit();
						$username=trim($user[$tmkey['username']]);
						if($username!='')
					    {
							$u=$m->where("username='".$username."'")->limit(1)->fetch()->getData();
							
							if(isset($u['username'])&&$u['username']==$username)
							{
								$username=$username.rand(1000,9999);
								$user[$tmkey['username']]=$username;
							}
							$m->clearEdit();
							$m->username=$username;
							$m->password=md5(trim($user[$tmkey['password']]));
							$m->realname=trim($user[$tmkey['realname']]);
							$m->setprojectid($projectid);
							if(isset($tmkey['email']))
							{
							  $m->email=trim($user[$tmkey['email']]);
							}
							$m->job=trim($user[$tmkey['job']]);
							$m->sex=trim($user[$tmkey['sex']])=='男'?1:0;
							$m->save();
						}
				   for ($col = 0; $col <=$highestColumnIndex; ++$col) {
				    $utable.='<td>'. $user[$col] . '</td>' . "\n";
				    }
				  $utable.='</tr>' . "\n";
				}
				$utable.='</table>' . "\n";				
				unlink(P("webprojectpath")."temp/tempuser.xls");
		 }else{
		   redirect(url_for("rbac/userimport"),"读取用户名单出错!",3);
		 }
	   }else{
		   redirect(url_for("rbac/userimport"),"用户名单文件出错!",3);
		 }
	 }else{
		   redirect(url_for("rbac/userimport"),"提交用户名单文件有误!",3);
		 }
	 $this->utable=$utable;
 }
 /*
 *取得router类实反射方法
 */
public function acllist() {
	$a=M("r.acl");

	$a->getAll();
	$this->acllist=$a->getRecord(); 
}
public function addacl() {
	
}
public function aclmethod() {
	$gid=intval($_GET['sid']);
	$a=M("r.acl");
	$this->info=$a->get($gid)->getData();
	$am=M("r.aclmethod");
	$this->acllist=$am->where("aclid='".$a->pkid()."'")->fetch()->getRecord();
}
public function deleteacl() {
	$gid=intval($_GET['sid']);
	$a=M("r.acl");
	if($a->get($gid)->pkid())
	{
	  $a->delete($a->pkid());
	}
	redirect(url_for("rbac/acllist"),"删除".$a->gettitle()."成功",1);
}
public function addaclpost() {
	$name=preg_replace("#[^a-zA-Z0-9_]+#is","",$_POST['model']);
	$path=preg_replace("/[^a-zA-Z0-9_\.\/]+/is","",$_POST['path']);
	$a=M("r.acl");
	$a->model=$name;
	$a->title=$_POST['title'];
	$a->aclpath=$path;
	$a->save();

	$fix=substr($name,-5);
	$model='';
	if($fix=='Router'){
		$rc=$name;
	    $model=substr($name,0,-6);
	}else{
	  $rc=$name."Router";
	  $model=$name;
	}
    $p=$GLOBALS['config']['frameworkpath'];
	$aclpath='';
	//查找model Router类
	if(file_exists($path."/router/".$rc.".class.php")){
		include_once($path."/router/".$rc.".class.php");
		$aclpath=$path."/router/";
	}elseif(file_exists($p."router/".$rc.".class.php")){
		include_once($p."/router/".$rc.".class.php");
		$aclpath=$p."router/";	  
	}elseif(file_exists($p."../router/".$rc.".class.php")){
		include_once($p."../router/".$rc.".class.php");
		$aclpath=$p."../router/";	  
	}elseif(file_exists($p."../".$path."/router/".$rc.".class.php")){
		include_once($p."../".$path."/router/".$rc.".class.php");
		$aclpath=$p."../".$path."/router/";	  
	}elseif(file_exists("./".$rc.".class.php")){
		include_once("./".$rc.".class.php");
		$aclpath="./";	  
	}
    if($aclpath=='')
	{
	   redirect(url_for("rbac/acllist"),"没有这个".$aclpath."目录",1);
	}
	$d=new ReflectionClass($rc);
    $am=M("r.aclmethod");
	//取得类的方法
	  foreach($d->getMethods() as $v)
	  {
		 if($v->class==$rc)
		 {
		   //清除条件
		   $am->clearEdit()->newSQL();
		   $am->aclid=$a->pkid();
		   $am->method=$v->name;
		   $am->save();
		 }
	  }
	redirect(url_for("rbac/acllist"),"成功添加Router类，可以给方法设置名字!",1);
}
//编辑提交
public function editaclpost() {
	$name=preg_replace("#[^a-zA-Z0-9_]+#is","",$_POST['model']);
	$path=preg_replace("/[^a-zA-Z0-9_\.\/]+/is","",$_POST['path']);
	$aclid=intval($_POST['aclid']);
	$a=M("r.acl");
	$a->get($aclid);
	$a->model=$name;
	$a->title=$_POST['title'];
	$a->aclpath=$path;
	$a->save();

	$fix=substr($name,-5);
	$model='';
	if($fix=='Router'){
		$rc=$name;
	    $model=substr($name,0,-6);
	}else{
	  $rc=$name."Router";
	  $model=$name;
	}
    $p=$GLOBALS['config']['frameworkpath'];
	$aclpath='';
	//查找model Router类
	if(file_exists($path."/router/".$rc.".class.php")){
		include_once($path."/router/".$rc.".class.php");
		$aclpath=$path."/router/";
	}elseif(file_exists($p."router/".$rc.".class.php")){
		include_once($p."/router/".$rc.".class.php");
		$aclpath=$p."router/";	  
	}elseif(file_exists($p."../router/".$rc.".class.php")){
		include_once($p."../router/".$rc.".class.php");
		$aclpath=$p."../router/";	  
	}elseif(file_exists($p."../".$path."/router/".$rc.".class.php")){
		include_once($p."../".$path."/router/".$rc.".class.php");
		$aclpath=$p."../".$path."/router/";	  
	}elseif(file_exists("./".$rc.".class.php")){
		include_once("./".$rc.".class.php");
		$aclpath="./";	  
	}
    if($aclpath=='')
	{
	   redirect(url_for("rbac/acllist"),"没有这个".$aclpath."目录",1);
	}
	$d=new ReflectionClass($rc);
    $am=M("r.aclmethod");
	$c=array();
	//取得类的方法
	  foreach($d->getMethods() as $v)
	  {
		 if($v->class==$rc)
		 {
		   //清除条件
		   $am->clearEdit()->newSQL();
		   $am->where("aclid='".$a->pkid()."'")->whereAnd("method='".$v->name."'")->limit(1)->fetch();
		   if($am->pkid())
		   {
			 
		   }else{
			$am->clearEdit()->newSQL();
			$am->aclid=$a->pkid();
			$am->method=$v->name;
			$am->save();
		   }
		 }
	  }
	redirect(url_for("rbac/acllist"),"成功添加Router类，可以给方法设置名字!",1);	
}
//ajax修改方法标记
public function ajaxaclmethod() {
	$title=$_GET['title'];
	$gid=intval($_GET['mid']);
    $am=M("r.aclmethod");
	$am->get($gid);
	if($am->pkid())
	{
	  $am->title=$title;
	  $am->update('title');
	  if($am->isEffect())
	  {
	     echo "修改成功";
	  }else{
		 echo "修改失败";
	  }
	}else{
	  echo "修改有误";
	}
	Return 'ajax';
}
//删除不使用的方法
public function deleteaclmethod() {

	$gid=intval($_GET['mid']);
    $am=M("r.aclmethod");
	$am->get($gid);
	if($am->pkid())
	{
	  $am->delete($gid);
	    echo json_encode(array("msg"=>"已删除".$am->gettitle(),"state"=>1));
	}else{
	    echo json_encode(array("msg"=>"删除有误"));
	}
	Return 'ajax';
}
public function editacl() {
	$gid=intval($_GET['sid']);
	$a=M("r.acl");
	$this->info=$a->get($gid)->getData();

}
public function refclassmethod() {
	$name=preg_replace("#[^a-zA-Z0-9_]+#is","",$_GET['name']);
	$path=preg_replace("/[^a-zA-Z0-9_\.\/]+/is","",$_GET['path']);
	$path=preg_replace("/_/","/",$path);
	$fix=substr($name,-5);
	$model='';
	if($fix=='Router'){
		$rc=$name;
	    $model=substr($name,0,-6);
	}else{
	  $rc=$name."Router";
	  $model=$name;
	}
    $p=$GLOBALS['config']['frameworkpath'];
	$aclpath='';
	if(file_exists($path."/router/".$rc.".class.php")){
		include_once($path."/router/".$rc.".class.php");
		$aclpath=$path."/router/";
	}elseif(file_exists($p."router/".$rc.".class.php")){
		include_once($p."/router/".$rc.".class.php");
		$aclpath=$p."router/";	  
	}elseif(file_exists($p."../router/".$rc.".class.php")){
		include_once($p."../router/".$rc.".class.php");
		$aclpath=$p."../router/";	  
	}elseif(file_exists($p."../".$path."/router/".$rc.".class.php")){
		include_once($p."../".$path."/router/".$rc.".class.php");
		$aclpath=$p."../".$path."/router/";	  
	}elseif(file_exists("./".$rc.".class.php")){
		include_once("./".$rc.".class.php");
		$aclpath="./";	  
	}
    if($aclpath=='')
	{
	   echo json_encode(array("msg"=>"没有这个".$aclpath."目录".$rc));exit;
	}
	$a=new ReflectionClass($rc);
    $b=$a->getMethods();
	$c=array();
  
	  foreach($a->getMethods() as $v)
	  {
		 if($v->class==$rc)
		 {
		   $c[]=$v->name;
		 }
	  }
  echo json_encode(array("msg"=>implode("<br />",$c)));
  Return 'ajax';
}
//更新权限
public function rbacupdate() {
	$gid=intval($_GET['sid']);
	$rbac=M("r.rbac");
	$rbac->get($gid);
	if(!$rbac->pkid())
	{
	  	redirect(url_for("rbac/rbaclist"),"不存在权限!",3);
	}
	$rbaclist=$rbac->newSQL()->clearEdit()->where("parentid='".$gid."'")->fetch()->getRecord();
    $routername=$rbac->model;//模块权限名
	$acltxt='class '.$routername.'ACL extends acl {';
	$acltxt.='	public $routername="'.$routername.'";';
	//资源ID
	$aclid=$rbac->getCol('rbacid',true,'method');
	$aclid['all']=$gid;
	$acltxt.='  public $aclid='.var_export($aclid,true).";";
	//密码
	$aclid=$rbac->getCol('password',true,'method');
	$aclid['all']=$rbac->password;
	$acltxt.='  public $pwd='.var_export($aclid,true).";";
	//日期
	$aclid=$rbac->getCol('timestart',true,'method');	
	$aclid2=$rbac->getCol('timeend',true,'method');
	$t=array();
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=array("begin"=>$v,"end"=>$aclid2[$k]);
	}
	$t['all']=array("begin"=>$rbac->timestart,"end"=>$rbac->timeend);
	$acltxt.='  public $date='.var_export($t,true).";";
    //时间
	$aclid=$rbac->getCol('daystart',true,'method');	
	$aclid2=$rbac->getCol('dayend',true,'method');
	$t=array();
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=array("begin"=>$v,"end"=>$aclid2[$k]);
	}
	$t['all']=array("begin"=>$rbac->daystart,"end"=>$rbac->dayend);
	$acltxt.='  public $hours='.var_export($t,true).";";

    //周
	$aclid=$rbac->getCol('weekstart',true,'method');	
	$aclid2=$rbac->getCol('weekend',true,'method');
	$t=array();
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=array("begin"=>$v,"end"=>$aclid2[$k]);
	}
	$t['all']=array("begin"=>$rbac->weekstart,"end"=>$rbac->weekend);
	$acltxt.='  public $weeks='.var_export($t,true).";";

	//组
	$aclid=$rbac->getCol('groupmap',true,'method');
	$t=array();
	$t['all']=implode(",",json_decode($rbac->groupmap,true));
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=implode(",",json_decode($v,true));
	}
	$acltxt.='  public $aclgroup='.var_export($t,true).";";
	//角色
	$aclid=$rbac->getCol('rolemap',true,'method');
	$t=array();
	$t['all']=implode(",",json_decode($rbac->rolemap,true));
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=implode(",",json_decode($v,true));
	}
	$acltxt.='  public $aclrole='.var_export($t,true).";";
	//禁用角色
	$aclid=$rbac->getCol('disablerole',true,'method');
	$t=array();
	$t['all']=implode(",",json_decode($rbac->disablerole,true));
	foreach($aclid as $k=>$v)
	{
	  $t[$k]=implode(",",json_decode($v,true));
	}
	$acltxt.='  public $roledisable='.var_export($t,true).";";
	//访问权限
	$aclid=$rbac->getCol('level',true,'method');
	$aclid['all']=$rbac->level;
	$acltxt.='  public $acl='.var_export($aclid,true).";";
	$acltxt.="}";
	
	$acc=M("r.acl");
	$acc->get($rbac->aclid);

     //查找路径
	 $path=$acc->aclpath;
    $p=$GLOBALS['config']['frameworkpath'];
	$aclpath='';
	$rc=$acc->model;
	$rc=$rc."Router";
  
    $acltxt="<?php ".$acltxt." ?>";

	if(file_exists($path."/router/".$rc.".class.php")){
		include_once($path."/router/".$rc.".class.php");
		$aclpath=$path."/router/";
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);

	}elseif(file_exists($p."router/".$rc.".class.php")){
		include_once($p."/router/".$rc.".class.php");
		$aclpath=$p."router/";	  
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);
	}elseif(file_exists($p."../router/".$rc.".class.php")){
		include_once($p."../router/".$rc.".class.php");
		$aclpath=$p."../router/";
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);
		
	}elseif(file_exists($p."../".$path."/router/".$rc.".class.php")){
		include_once($p."../".$path."/router/".$rc.".class.php");
		$aclpath=$p."../".$path."/router/";	  
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);

	}elseif(file_exists("./".$rc.".class.php")){
		include_once("./".$rc.".class.php");
		$aclpath="./";
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);		
	}
    if($aclpath=='')
	{
	    $aclpath=$p."router/";	  
		if(!is_dir($aclpath."/acl")){
		  mkdir($aclpath."/acl",0777);
		  chmod($aclpath."/acl",0777);
		}
		file_put_contents($aclpath."/acl/".$acc->model."ACL.class.php",$acltxt);
	}
	$user=M("r.user");
	$user->where("uid>0");
	$user->rbaccache=json_encode(array());
	$user->update('rbaccache');
	redirect(url_for("rbac/rbaclist"),"权限文件已刷新!",3);
}
//删除权限文件
function rbacdelac() {
	$gid=intval($_GET['sid']);
	$rbac=M("r.rbac");
	$rbac->get($gid);
	if(!$rbac->pkid())
	{
	  	redirect(url_for("rbac/rbaclist"),"不存在权限!",3);
	}

	$acc=M("r.acl");
	$acc->get($rbac->aclid);

     //查找路径
	 $path=$acc->aclpath;
    $p=$GLOBALS['config']['frameworkpath'];
    $msg="权限文件已刷新!";
	if(file_exists($path."/router/acl/".$acc->model."ACL.class.php")){
		unlink($path."/router/acl/".$acc->model."ACL.class.php");
	}elseif(file_exists($p."router/acl/".$acc->model."ACL.class.php")){
		unlink($p."router/acl/".$acc->model."ACL.class.php");
	}elseif(file_exists($p."../router/acl/".$acc->model."ACL.class.php")){
		unlink($p."../router/acl/".$acc->model."ACL.class.php");
		
	}elseif(file_exists($p."../".$path."/router/acl/".$acc->model."ACL.class.php")){
		unlink($p."../".$path."/router/acl/".$acc->model."ACL.class.php");

	}elseif(file_exists("./acl/".$acc->model."ACL.class.php")){
		unlink("./acl/".$acc->model."ACL.class.php");		
	}else{
	  $msg="权限文件没有清除，请你手工清除";
	}
	redirect(url_for("rbac/rbaclist"),$msg,2);
}
 /*
 *取得router类实反射方法
 */
 /*
 * 组的管理
 */
 public function grouplist() {
  	$p=M("r.group");
	$p->getAll();
	$this->projectlist=$p->getRecord();
	$s=$p->getCol('uid',false);
	if($s)
	{
	$u=M("r.user");
	$this->ua=$u->whereIn("uid",$s)->fetch()->getCol("realname",true,'uid');
	}
 }
 //添加组
 public function addgroup() {
	$p=M("r.project");
	$p->getAll();
	$this->group=$p->getRecord(); 	
 }
 public function addgrouppost() {
	
 	$p=M("r.group");
	$p->pid=$_POST['projectid'];
	$p->groupname=$_POST['groupname'];
	$p->dest=$_POST['dest'];
	$p->save();
	redirect(url_for("rbac/grouplist"),"添加组成功!",3);
 }
 public function editgroup() {
	$gid=intval($_GET['sid']);
	$p=M("r.group");
	$this->info=$p->get($gid)->getData();
	$p=M("r.project");
	$p->getAll();
	$this->group=$p->getRecord(); 	
 }
 public function editgrouppost() {
	$gid=intval($_POST['gid']);
	$p=M("r.group");
	$p->get($gid);
	$p->pid=$_POST['projectid'];
	$p->groupname=$_POST['groupname'];
	$p->dest=$_POST['dest'];
	$p->update();
	redirect(url_for("rbac/grouplist"),"修改组成功!",3);
 }
 public function deletegroup() {
 	$gid=intval($_GET['sid']);
	 	 	$p=M("r.group");
	$p->get($gid);
	$p->delete($gid);
	redirect(url_for("rbac/grouplist"),"删除组成功!",3);
 }
 public function groupower() {
	$sid=intval($_GET['iid']);
	$uid=intval($_GET['sid']);
	$g=M("r.group");
	$g->get($sid);
	if($g->pkid())
	{
		$g->setuid($uid);
		$g->update('uid');
		if($g->isEffect())
			echo json_encode(array("msg"=>"设置成功"));
		else
			echo json_encode(array("msg"=>"没有设置"));
	}else{
	 echo json_encode(array("msg"=>"成功"));	
	}
	$g=M("r.groupuser");

	if($g->where("gid='".$sid."'")->whereAnd("uid='".$uid."'")->count()>0)
	{
	   $g->ismar='Y';
	   $g->update('ismar');
	}

	Return 'ajax';
 }
 //设置组员
 public function setgroupuser() {
	$sid=intval($_GET['sid']);
	$role=M("r.group");
	$this->info=$role->get($sid)->getData();
	$user=M("r.user");
	$this->pager=C("pager");//取得分页类 
	$this->pager->setPager($user->count(),15,'page');//取得数据总数中，设置每页为10 
	$this->assign("userrecord",$user->orderby("uid desc")->limit($this->pager->offset(),15)->fetch()->getRecord()); 
	//输出分页导航
	$this->assign("nav_bar",$this->pager->getWholeBar(url_for("rbac/setgroupuser/sid/".$sid."/page/:page",true))); 
	$uidarray=$user->getCol('uid',false);
	if($uidarray)
	{ //取出已设置的人员
	 $g=M("r.groupuser");
	 $uid=$g->where("gid='".$sid."'")->whereIn('uid',$uidarray)->fetch()->getCol("uid");
	 $this->groupuid=$g->getCol("isMar",true,'uid');
	 if($uid)
	 {
	   $this->roleuid=array_flip($uid);
	 }
	}
	$this->sid=intval($_GET['sid']);
 }
  //组权限分配
 public function setrgaccess() {
      $check=intval($_GET['check']);
	  $sid=intval($_GET['sid']);
	  $rid=intval($_GET['rid']);
      $g=M("r.rbacgroup");
	  if($check==1)
	  {
		if($g->where("gid='".$rid."'")->whereAnd("rbacid='".$sid."'")->count()==0)
		{
		   $g->newSQL();
		   $g->gid=$rid;
           $g->rbacid=$sid;
		   $g->save();
		   	  echo json_encode(array("msg"=>"成功添加权限"));
		}else{
		   echo json_encode(array("msg"=>"权限设置有误"));
		}
	  }else{
	    if($g->where("gid='".$rid."'")->whereAnd("rbacid='".$sid."'")->count()>0)
		{
		   $g->newSQL();
           $g->where("gid='".$rid."'")->whereAnd("rbacid='".$sid."'")->delete();
		    echo json_encode(array("msg"=>"成功取消权限"));
		}else{
		  echo json_encode(array("msg"=>"权限设置有误,不存在"));
		}
	  }

	  Return 'ajax';		
 }
 //组权限管理
 public function setgrouprbac() {
	$gid=intval($_GET['sid']);
	$p=M("r.group");
	$this->info=$p->get($gid)->getData();
	
	if(!$p->pkid())
	{
		redirect(url_for("rbac/grouplist"),"组有误!",3);	  
	}
    $rr=M("r.rbacgroup");
	$urbacid=$rr->where("gid='".$gid."'")->fetch()->getCol('rbacid');
	if(is_array($urbacid))
	{
	  $this->urbacid=array_flip($urbacid);
	}
 	$r=M("r.rbac");
	$rall=$r->select("rbacid,parentid,model,name,method,isAll")->orderby("parentid asc")->fetch()->getRecord();
	$i=0;
	$j=0;
	$n=0;
	$row=array();//把一组权限先放在一起
	foreach($rall as $k=>$v)
	{
	  if(!isset($row[$v['rbacid']])&&$v['parentid']==0)
	  {
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());		
	  }else{
		if(isset($row[$v['parentid']]))
	    $row[$v['parentid']]['sub'][]=array("name"=>$v['name']."(".$v['method'].")","rbacid"=>$v['rbacid']);	
		else
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());	
	  }
	}
    $this->rlist=$row; 	
 }
 //设置组管理员
 public function groupmaruser() {
	$check=intval($_GET['check']);
	$sid=intval($_GET['sid']);
	$iid=intval($_GET['iid']);
	$g=M("r.groupuser");

	if($g->where("gid='".$iid."'")->whereAnd("uid='".$sid."'")->count()>0)
	{
	  if($check==1)
	  {
	   $g->ismar='Y';
	  }else{
		$g->ismar='N'; 
	  }
	   $g->update('ismar');
	}
	if($g->isEffect())
	echo json_encode(array("msg"=>"设置成功"));
	else
	echo json_encode(array("msg"=>"没有设置"));
	Return 'ajax';	
 }
 //设置组员
 public function groupuser() {
	$check=intval($_GET['check']);
	$sid=intval($_GET['sid']);
	$iid=intval($_GET['iid']);
	$g=M("r.groupuser");
	if($check==1)
	{
	if($g->where("gid='".$iid."'")->whereAnd("uid='".$sid."'")->count()==0)
	{
	   $g->gid=$iid;
	   $g->uid=$sid;
	   $g->save();
	}
	}else{
	if($g->where("gid='".$iid."'")->whereAnd("uid='".$sid."'")->count()>0)
	{
	   $g->where("gid='".$iid."'")->whereAnd("uid='".$sid."'")->delete();
	}
	}
	echo json_encode(array("msg"=>"成功"));
	Return 'ajax';	
 }
 public function setgrouprole() {
	$check=intval($_GET['check']);
	$sid=intval($_GET['sid']);
	$iid=intval($_GET['iid']);
	$g=M("r.grouprole");
	if($check==1)
	{
	if($g->where("roleid='".$sid."'")->whereAnd("gid='".$iid."'")->count()==0)
	{
	   $g->roleid=$sid;
	   $g->gid=$iid;
	   $g->save();
	}
	}else{
	if($g->where("roleid='".$sid."'")->whereAnd("gid='".$iid."'")->count()>0)
	{
	   $g->where("roleid='".$sid."'")->whereAnd("gid='".$iid."'")->delete();
	}
	}
	echo json_encode(array("msg"=>"成功"));
	Return 'ajax';		
 }
 public function grouprolemap() {
	$sid=intval($_GET['iid']);
	$rid=intval($_GET['sid']);
	$check=intval($_GET['check']);
	$g=M("r.grouprole");

	if($g->where("gid='".$sid."'")->whereAnd("roleid='".$rid."'")->count()>0)
	{
		if($check==1)
	   {
		$g->jicheng='Y';
	   }else{
		$g->jicheng='N';
	   }
	   $g->update('jicheng');
	}

	Return 'ajax';
 }
 //组角色管理
 public function grouprole() {
  	$sid=intval($_GET['sid']);
	$role=M("r.group");
	$this->info=$role->get($sid)->getData();	
    $this->sid=$sid;
  	$p=M("r.role");
	$p->getAll();
	$this->projectlist=$p->getRecord();
    
	$rid=$p->getCol('roleid');
	$gr=M("r.grouprole");
	$gr->where("gid='".$sid."'")->fetch();
    $grid=$gr->getCol('roleid');
	$this->jicheng=$gr->getCol("jicheng",true,'roleid');
	if($grid)
	{
	  $this->grlist=array_flip($grid);
	}
 }
/*
*组管理结束
***/
 /*
 * 角色的管理
 */
 public function rolelist() {
  	$p=M("r.role");
	$p->getAll();
	$this->projectlist=$p->getRecord();
 }
 public function addrole() {

 }
 public function addrolepost() {
 	$p=M("r.role");
	$p->rolename=$_POST['rolename'];
	$p->dest=$_POST['dest'];
	$p->save();
	redirect(url_for("rbac/rolelist"),"添加角色成功!",3);
 }
 public function editrole() {
	$gid=intval($_GET['sid']);
	$p=M("r.role");
	$this->info=$p->get($gid)->getData();
 }
 public function editrolepost() {
	$gid=intval($_POST['roleid']);
	$p=M("r.role");
	$p->get($gid);
	$p->rolename=$_POST['rolename'];
	$p->dest=$_POST['dest'];
	$p->update();
	redirect(url_for("rbac/rolelist"),"修改角色成功!",3);
 }
 public function deleterole() {
 	$gid=intval($_GET['sid']);
	$p=M("r.role");
	$p->get($gid);
	$p->delete($gid);
	redirect(url_for("rbac/rolelist"),"删除角色成功!",3);
 }
 //角色权限分配
 public function setrraccess() {
      $check=intval($_GET['check']);
	  $sid=intval($_GET['sid']);
	  $rid=intval($_GET['rid']);
      $g=M("r.rbacrole");
	  if($check==1)
	  {
		if($g->where("roleid='".$rid."'")->whereAnd("rbacid='".$sid."'")->count()==0)
		{
		   $g->newSQL();
		   $g->roleid=$rid;
           $g->rbacid=$sid;
		   $g->save();
		   	  echo json_encode(array("msg"=>"成功添加权限"));
		}else{
		   echo json_encode(array("msg"=>"权限设置有误"));
		}
	  }else{
	    if($g->where("roleid='".$rid."'")->whereAnd("rbacid='".$sid."'")->count()>0)
		{
		   $g->newSQL();
           $g->where("roleid='".$rid."'")->whereAnd("rbacid='".$sid."'")->delete();
		    echo json_encode(array("msg"=>"成功取消权限"));
		}else{
		  echo json_encode(array("msg"=>"权限设置有误,不存在"));
		}
	  }
	  Return 'ajax';		
 }
 public function setrolerbac() {
	$gid=intval($_GET['rid']);
	$p=M("r.role");
	$this->info=$p->get($gid)->getData();
	
	if(!$p->pkid())
	{
		redirect(url_for("rbac/rolelist"),"角色有误!",3);	  
	}
    $rr=M("r.rbacrole");
	$urbacid=$rr->where("roleid='".$gid."'")->fetch()->getCol('rbacid');
	if(is_array($urbacid))
	{
	  $this->urbacid=array_flip($urbacid);
	}
 	$r=M("r.rbac");
	$rall=$r->select("rbacid,parentid,model,name,method,isAll")->orderby("parentid asc")->fetch()->getRecord();
	$i=0;
	$j=0;
	$n=0;
	$row=array();//把一组权限先放在一起
	foreach($rall as $k=>$v)
	{
	  if(!isset($row[$v['rbacid']])&&$v['parentid']==0)
	  {
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());		
	  }else{
		if(isset($row[$v['parentid']]))
	    $row[$v['parentid']]['sub'][]=array("name"=>$v['name']."(".$v['method'].")","rbacid"=>$v['rbacid']);	
		else
		$row[$v['rbacid']]=array("name"=>$v['name']."(".$v['model'].")","rbacid"=>$v['rbacid'],"sub"=>array());	
	  }
	}
    $this->rlist=$row;
 }
 public function setroleuser() {
 	$rid=intval($_GET['rid']);
	$role=M("r.role");
	$this->info=$role->get($rid)->getData();
	$user=M("r.user");
	$this->pager=C("pager");//取得分页类 
	$this->pager->setPager($user->count(),15,'page');//取得数据总数中，设置每页为10 
	$this->assign("userrecord",$user->orderby("uid desc")->limit($this->pager->offset(),15)->fetch()->getRecord()); 
	//输出分页导航
	$this->assign("nav_bar",$this->pager->getWholeBar(url_for("rbac/setroleuser/rid/".$rid."/page/:page",true))); 
	$uidarray=$user->getCol('uid',false);
	if($uidarray)
	{ //取出已设置的人员
	 $g=M("r.userrole");
	 $uid=$g->where("roleid='".$rid."'")->whereIn('uid',$uidarray)->fetch()->getCol("uid");
	 if($uid)
	 {
	   $this->roleuid=array_flip($uid);
	 }
	}
	$this->sid=intval($_GET['rid']);
 }
 public function roleuser() {
      $check=intval($_GET['check']);
	  $sid=intval($_GET['sid']);
	  $iid=intval($_GET['iid']);
      $g=M("r.userrole");
	  if($check==1)
	  {
		if($g->where("roleid='".$iid."'")->whereAnd("uid='".$sid."'")->count()==0)
		{
		   $g->roleid=$iid;
           $g->uid=$sid;
		   $g->save();
		}
	  }else{
	    if($g->where("roleid='".$iid."'")->whereAnd("uid='".$sid."'")->count()>0)
		{
           $g->where("roleid='".$iid."'")->whereAnd("uid='".$sid."'")->delete();
		}
	  }
	  echo json_encode(array("msg"=>"成功"));
	  Return 'ajax';	
 }
/*
*角色管理结束
***/

 /*
 * 权限的管理
 */
 public function rbaclist() {
  	$p=M("r.rbac");
	$p->where("parentid=0")->fetch();
	$this->projectlist=$p->getRecord();
 }
 public function addrbac() {
    $g=M("r.group");
	$this->group=$g->getAll()->getRecord();
	$r=M("r.role");
	$this->role=$r->getAll()->getRecord();
	$a=M("r.acl");
	$this->acllist=$a->getAll()->getRecord();
	$rc=M("r.rbac");
	$acid=$rc->select("aclid")->fetch()->getCol('aclid');
	if(is_array($acid))
		$this->acid=array_flip($acid);
 }
 public function addrbacpost() {

 	$p=M("r.rbac");
	$a=M("r.acl");
	$aid=intval($_POST['aclid']);
	$a->get($aid);
	if(!$a->pkid())
	{
	  	redirect(url_for("rbac/addrbac"),"不存在的模块权限!",3);
	}
	if($p->where("aclid='".$aid."'")->whereAnd("parentid='0'")->count()>0)
	{
	  	redirect(url_for("rbac/addrbac"),"已存在的模块权限，不能再添加!",3);	  
	}
	$p->clearEdit()->newSQL();	
	$p->aclid=$aid;
	$p->model=$a->model;
	
	$p->name=empty($_POST['name'])?$a->gettitle():$_POST['name'];
	$p->method=$_POST['method'];
	$p->isall=$_POST['isall']==1?'Y':'N';
	if(isset($_POST['level'][0]))
	{
	  $p->level=0;
	}else{
	  $level=0;
	  if(isset($_POST['level'][1])) $level=$level+1;
	  if(isset($_POST['level'][2])) $level=$level+2;
	  if(isset($_POST['level'][3])) $level=$level+4;
	  if(isset($_POST['level'][4])) $level=$level+8;
	  if(isset($_POST['level'][5])) $level=$level+16;
	  if(isset($_POST['level'][6])) $level=$level+32;	  
	  if(isset($_POST['level'][7])) $level=$level+64;
	  if(isset($_POST['level'][8])) $level=$level+128;
	  if(isset($_POST['level'][9])) $level=$level+256;
	  if(isset($_POST['level'][10])) $level=$level+512;

	  $p->level=$level;
	}
	$group=explode(",",trim($_POST['groupmap']));
    if(empty($group[0])) array_shift($group);

	$rolemap=explode(",",trim($_POST['rolemap']));
    if(empty($rolemap[0]))	array_shift($rolemap);

	$disablerole=explode(",",trim($_POST['disablerole']));
    if(empty($disablerole[0])) array_shift($disablerole);

	$p->groupmap=json_encode($group);
	$p->rolemap=json_encode($rolemap);
	$p->disablerole=json_encode($disablerole);


    $p->timestart=$_POST['timestart'];
	$p->timeend=$_POST['timeend'];
	$p->daystart=$_POST['daystart'];
	$p->dayend=$_POST['dayend'];
	$p->weekstart=$_POST['weekstart'];
	$p->weekend=$_POST['weekend'];

	$p->password=$_POST['password'];
    //exit;
	$p->save();
	redirect(url_for("rbac/rbaclist"),"添加权限资源成功!",3);
 }
 public function editrbac() {
	$gid=intval($_GET['sid']);
 	$p=M("r.rbac");
	$a=M("r.acl");
	$this->acllist=$a->getAll()->getRecord();
	$acid=$p->select("aclid")->fetch()->getCol('aclid');
	//着色已有的ACL权限
	if(is_array($acid))
		$this->acid=array_flip($acid);

	$p->clearEdit()->newSQL();	
	$this->info=$p->get($gid)->getData();

    $g=M("r.group");
	$this->group=$g->getAll()->getRecord();
    
	$nowgmap=array_flip(json_decode($this->info['groupmap'],TRUE)); //反转数组
	$nowrmap=array_flip(json_decode($this->info['rolemap'],TRUE));
	$nowdmap=array_flip(json_decode($this->info['disablerole'],TRUE));

    $gmap=array();//提取已经存在的组
	if(is_array($this->group))
	{
	  foreach($this->group as $v)
	  {
	    if(isset($nowgmap[$v['gid']]))
		{
		  $gmap[]=array("gid"=>$v['gid'],"groupname"=>$v['groupname']);//取出已有组
		}
	  }
	}
	$this->gmap=$gmap;
	$r=M("r.role");
	$this->role=$r->getAll()->getRecord();

    $gmap=array();//提取已经存在的组
	$dmap=array();//提取已经存在的组
	if(is_array($this->role))
	{
	  foreach($this->role as $v)
	  {
	    if(isset($nowrmap[$v['roleid']]))
		{
			//取出已有角色
		  $gmap[]=array("roleid"=>$v['roleid'],"rolename"=>$v['rolename']);
		}
	    if(isset($nowdmap[$v['roleid']]))
		{
		  $dmap[]=array("roleid"=>$v['roleid'],"rolename"=>$v['rolename']);//取出已有禁止角色
		}
	  }
	}
	$this->rmap=$gmap;
	$this->dmap=$dmap;
 }
 public function editrbacpost() {
	$gid=intval($_POST['rbacid']);
	$p=M("r.rbac");
	$p->get($gid);

	$a=M("r.acl");
	$aid=intval($_POST['aclid']);
	$a->get($aid);
	if(!$a->pkid())
	{
	  	redirect(url_for("rbac/addrbac"),"不存在的模块权限!",3);
	}
	if($p->where("aclid='".$aid."'")->whereAnd("rbacid!='".$p->pkid()."'")->whereAnd("parentid='0'")->count()>0)
	{
	  	redirect(url_for("rbac/addrbac"),"已存在的模块权限，不能再添加!",3);	  
	}
	$p->newSQL();	
	$p->aclid=$aid;
	$p->model=$a->model;

	$p->model=$a->model;
	$p->name=$_POST['name'];
	$p->method=$_POST['method'];
	$p->isall=$_POST['isall']==1?'Y':'N';
	if(isset($_POST['level'][0]))
	{
	  $p->level=0;
	}else{
	  $level=0;
	  if(isset($_POST['level'][1])) $level=$level+1;
	  if(isset($_POST['level'][2])) $level=$level+2;
	  if(isset($_POST['level'][3])) $level=$level+4;
	  if(isset($_POST['level'][4])) $level=$level+8;
	  if(isset($_POST['level'][5])) $level=$level+16;
	  if(isset($_POST['level'][6])) $level=$level+32;	  
	  if(isset($_POST['level'][7])) $level=$level+64;
	  if(isset($_POST['level'][8])) $level=$level+128;
	  if(isset($_POST['level'][9])) $level=$level+256;
	  if(isset($_POST['level'][10])) $level=$level+512;

	  $p->level=$level;
	}
	$group=explode(",",trim($_POST['groupmap']));
    if(empty($group[0])) array_shift($group);

	$rolemap=explode(",",trim($_POST['rolemap']));
    if(empty($rolemap[0]))	array_shift($rolemap);

	$disablerole=explode(",",trim($_POST['disablerole']));
    if(empty($disablerole[0])) array_shift($disablerole);

	$p->groupmap=json_encode($group);
	$p->rolemap=json_encode($rolemap);
	$p->disablerole=json_encode($disablerole);


    $p->timestart=$_POST['timestart'];
	$p->timeend=$_POST['timeend'];
	$p->daystart=$_POST['daystart'];
	$p->dayend=$_POST['dayend'];
	$p->weekstart=$_POST['weekstart'];
	$p->weekend=$_POST['weekend'];

	$p->password=$_POST['password'];
	$p->update();
	redirect(url_for("rbac/rbaclist"),"修改权限资源成功!",3);
 }
 public function deleterbac() {
 	$gid=intval($_GET['sid']);
	$p=M("r.rbac");
	$p->get($gid);
	$p->where("parentid='".$gid."'");
	$p->delete();
	$p->delete($gid);
	redirect(url_for("rbac/rbaclist"),"删除权限资源成功!",3);
 }
 public function rbaccache() {
 	$u=M("r.user");
	$u->rbaccache=json_encode(array());
	$u->where("uid>0")->update("rbaccache");
	redirect(url_for("rbac/rbaclist"),"已清除人员权限缓存!",3);
 }
/*
*权限管理结束
***/
 /*
 * 子权限的管理
 */
 public function rbacsublist() {
	 $gid=intval($_GET['sid']);
	
  	$p=M("r.rbac");
	$this->info=$p->get($gid)->getData();
    $p->newSQL()->clearEdit();
	$p->where("parentid ='".$gid."'")->fetch();
	$this->projectlist=$p->getRecord();
	$this->gid=$gid;
 }
 public function addsubrbac() {
	$gid=intval($_GET['sid']);
	$p=M("r.rbac");
	$p->get($gid);
	$this->info=$p->getData();

    $g=M("r.group");
	$this->group=$g->getAll()->getRecord();
	$r=M("r.role");
	$this->role=$r->getAll()->getRecord();

	$a=M("r.acl");
	$this->ainfo=$a->get($p->getaclid())->getData();
	$p->newSQL();//清除条件
	$acid=$p->select("method")->where("aclid='".$a->pkid()."'")->where("parentid='".$gid."'")->fetch()->getCol('method');
	if(is_array($acid))
		$this->acid=array_flip($acid);

	$am=M("r.aclmethod");
	$this->acllist=$am->where("aclid='".$a->pkid()."'")->fetch()->getRecord();
 }
 public function addsubrbacpost() {
 	$p=M("r.rbac");
	$gid=intval($_POST['rbacid']);
	$p->get($gid);
	$info=$p->getData();

	$a=M("r.aclmethod");
	$aid=intval($_POST['method']);
	$a->get($aid);
	if(!$a->pkid())
	{
	  	redirect(url_for("rbac/addsubrbac"),"不存在的模块权限!",3);
	}
	$p->newSQL();

	if($p->where("aclid='".$info['aclid']."'")->whereAnd("method='".$a->getmethod()."'")->whereAnd("parentid='".$gid."'")->count()>0)
	{
	  	redirect(url_for("rbac/addsubrbac"),"已存在的子模块权限，不能再添加!",3);	  
	}
	$p->clearEdit()->newSQL();	

	$p->model=$info['model'];
	$p->aclid=$info['aclid'];
	$p->parentid=intval($_POST['rbacid']);

	$p->name=$_POST['name'];
	$p->method=$a->getmethod();
	if(isset($_POST['level'][0]))
	{
	  $p->level=0;
	}else{
	  $level=0;
	  if(isset($_POST['level'][1])) $level=$level+1;
	  if(isset($_POST['level'][2])) $level=$level+2;
	  if(isset($_POST['level'][3])) $level=$level+4;
	  if(isset($_POST['level'][4])) $level=$level+8;
	  if(isset($_POST['level'][5])) $level=$level+16;
	  if(isset($_POST['level'][6])) $level=$level+32;	  
	  if(isset($_POST['level'][7])) $level=$level+64;
	  if(isset($_POST['level'][8])) $level=$level+128;
	  if(isset($_POST['level'][9])) $level=$level+256;
	  if(isset($_POST['level'][10])) $level=$level+512;

	  $p->level=$level;
	}
	$group=explode(",",trim($_POST['groupmap']));
    if(empty($group[0])) array_shift($group);

	$rolemap=explode(",",trim($_POST['rolemap']));
    if(empty($rolemap[0]))	array_shift($rolemap);

	$disablerole=explode(",",trim($_POST['disablerole']));
    if(empty($disablerole[0])) array_shift($disablerole);

	$p->groupmap=json_encode($group);
	$p->rolemap=json_encode($rolemap);
	$p->disablerole=json_encode($disablerole);


    $p->timestart=$_POST['timestart'];
	$p->timeend=$_POST['timeend'];
	$p->daystart=$_POST['daystart'];
	$p->dayend=$_POST['dayend'];
	$p->weekstart=$_POST['weekstart'];
	$p->weekend=$_POST['weekend'];

	$p->password=$_POST['password'];


	$p->save();
	redirect(url_for("rbac/rbacsublist/sid/".intval($_POST['rbacid'])),"添加子权限资源成功!",3);
 }
 public function editsubrbac() {
	$gid=intval($_GET['sid']);
	$p=M("r.rbac");
	$this->info=$p->get($gid)->getData();

	$g=M("r.group");
	$this->group=$g->getAll()->getRecord();
    
	$nowgmap=array_flip(json_decode($this->info['groupmap'],TRUE)); //反转数组
	$nowrmap=array_flip(json_decode($this->info['rolemap'],TRUE));
	$nowdmap=array_flip(json_decode($this->info['disablerole'],TRUE));

    $gmap=array();//提取已经存在的组
	if(is_array($this->group))
	{
	  foreach($this->group as $v)
	  {
	    if(isset($nowgmap[$v['gid']]))
		{
		  $gmap[]=array("gid"=>$v['gid'],"groupname"=>$v['groupname']);//取出已有组
		}
	  }
	}
	$this->gmap=$gmap;
	$r=M("r.role");
	$this->role=$r->getAll()->getRecord();

    $gmap=array();//提取已经存在的组
	$dmap=array();//提取已经存在的组
	if(is_array($this->role))
	{
	  foreach($this->role as $v)
	  {
	    if(isset($nowrmap[$v['roleid']]))
		{
			//取出已有角色
		  $gmap[]=array("roleid"=>$v['roleid'],"rolename"=>$v['rolename']);
		}
	    if(isset($nowdmap[$v['roleid']]))
		{
		  $dmap[]=array("roleid"=>$v['roleid'],"rolename"=>$v['rolename']);//取出已有禁止角色
		}
	  }
	}
	$this->rmap=$gmap;
	$this->dmap=$dmap;

	$a=M("r.acl");
	$this->ainfo=$a->get($p->getaclid())->getData();
	$p->newSQL();//清除条件
	$pid=$p->getparentid();
	$acid=$p->select("method")->where("aclid='".$a->pkid()."'")->where("parentid='".$pid."'")->fetch()->getCol('method');
	if(is_array($acid))
		$this->acid=array_flip($acid);
	$p->newSQL()->clearEdit();//清除条件
	$p->get($pid);
	$this->pinfo=$p->getData();
	$am=M("r.aclmethod");
	$this->acllist=$am->where("aclid='".$a->pkid()."'")->fetch()->getRecord();

 }
 public function editsubrbacpost() {
	$gid=intval($_POST['rbacid']);
	$p=M("r.rbac");
	$p->get($gid);

	$a=M("r.aclmethod");
	$aid=intval($_POST['method']);
	$a->get($aid);
	if(!$a->pkid())
	{
	  	redirect(url_for("rbac/editsubrbac/sid/".$p->pkid()),"不存在的模块权限!",3);
	}
	$p->newSQL();
	if($p->where("aclid='".$p->getaclid()."'")->whereAnd("method='".$a->getmethod()."'")->whereAnd("parentid='".$p->getparentid()."'")->whereAnd("rbacid!='".$gid."'")->count()>0)
	{
	  	redirect(url_for("rbac/editsubrbac/sid/".$p->pkid()),"已存在的子模块权限，不能再添加!",3);	  
	}
	$p->newSQL();	

	$p->name=$_POST['name'];

	$p->method=$a->getmethod();
	$p->isall=$_POST['isall']==1?'Y':'N';
	if(isset($_POST['level'][0]))
	{
	  $p->level=0;
	}else{
	  $level=0;
	  if(isset($_POST['level'][1])) $level=$level+1;
	  if(isset($_POST['level'][2])) $level=$level+2;
	  if(isset($_POST['level'][3])) $level=$level+4;
	  if(isset($_POST['level'][4])) $level=$level+8;
	  if(isset($_POST['level'][5])) $level=$level+16;
	  if(isset($_POST['level'][6])) $level=$level+32;	  
	  if(isset($_POST['level'][7])) $level=$level+64;
	  if(isset($_POST['level'][8])) $level=$level+128;
	  if(isset($_POST['level'][9])) $level=$level+256;
	  if(isset($_POST['level'][10])) $level=$level+512;

	  $p->level=$level;
	}
	$group=explode(",",trim($_POST['groupmap']));
    if(empty($group[0])) array_shift($group);

	$rolemap=explode(",",trim($_POST['rolemap']));
    if(empty($rolemap[0]))	array_shift($rolemap);

	$disablerole=explode(",",trim($_POST['disablerole']));
    if(empty($disablerole[0])) array_shift($disablerole);

	$p->groupmap=json_encode($group);
	$p->rolemap=json_encode($rolemap);
	$p->disablerole=json_encode($disablerole);


    $p->timestart=$_POST['timestart'];
	$p->timeend=$_POST['timeend'];
	$p->daystart=$_POST['daystart'];
	$p->dayend=$_POST['dayend'];
	$p->weekstart=$_POST['weekstart'];
	$p->weekend=$_POST['weekend'];

	$p->password=$_POST['password'];

	$p->update();
	redirect(url_for("rbac/rbacsublist/sid/".$p->getparentid()),"修改子权限资源成功!",3);
 }
 public function deletesubrbac() {
 	$gid=intval($_GET['sid']);
	$p=M("r.rbac");
	$p->get($gid);
	$id=$p->getparentid();
	$p->delete($gid);
	redirect(url_for("rbac/rbacsublist/sid/".$id),"删除子权限资源成功!",3);
 }
/*
*角色管理结束
***/
}  
?>