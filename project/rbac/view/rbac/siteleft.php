<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>权限管理系统</title>
<meta name="keywords" content="">
<meta name="description" content="">
<script type="text/javascript" src="<?php echo url_project();?>js/jquery-1.3.2.min.js"></script>
<link href="<?php echo url_project();?>images/list.css" rel="stylesheet" type="text/css">
</head><body>
<div id="frameWrap">
  <div id="leftFrame">
     <div class="row" id="leftHead"><a href="#">权限管理系统</a></div>
	 <div class="row" id="leftSearch"> 
	  <a href="<?php echo url_for("rbac/logout");?>" target="_top">[退出登录]</a>
    </div>
	 <dl class="row" id="leftContent"> 
	 <dt class="tit">  
	   <a href="#" class="sel" target="_self">管理员管理</a>
	   </dt>  
	   <dd class="con" id="leftSublist"> 
	     <b rel="scon-31" class=""><a href="#" target="rightFrame">权限管理</a></b>
		 <div style="display: ;" class="scon show" id="scon-31">
		   <dl class="param">
           <dt><a href="<?php echo url_for("rbac/help",true);?>" target="rightFrame">权限说明</a></dt>
		     <dt><a href="<?php echo url_for("rbac/superlist",true);?>" target="rightFrame">超级管理员</a></dt>
              <dt><a href="<?php echo url_for("rbac/acllist",true);?>" target="rightFrame">控制列表</a></dt>	
              <dt><a href="<?php echo url_for("rbac/rbaclist",true);?>" target="rightFrame">权限列表</a></dt>               
              <dt><a href="<?php echo url_for("rbac/rolelist",true);?>" target="rightFrame">角色管理</a></dt>
			  <dt><a href="<?php echo url_for("rbac/grouplist",true);?>" target="rightFrame">组管理</a></dt>
              <dt><a href="<?php echo url_for("rbac/projectlist",true);?>" target="rightFrame">机构列表</a></dt>
			  <dt><a href="<?php echo url_for("rbac/addproject",true);?>" target="rightFrame">添加机构</a></dt>
              <dt><a href="<?php echo url_for("rbac/adduser",true);?>" target="rightFrame">添加人员</a></dt>
              <dt><a href="<?php echo url_for("rbac/mymemberlist",true);?>" target="rightFrame">人员权限列表</a></dt>
			  </dl></div>

	  <b rel="scon-385"><a href="<?php echo url_for("rbac/logout",true);?>" target="_top">退出系统管理</a></b>
  </dd></dl><div id="leftFoot"></div></div>
  <div id="mainFrame">

  </div>
</div>
<div id="divProcessing" name=divProcessing style="width:200px;height:30px;position:absolute;display:none">
<table border=0 cellpadding=0 cellspacing=1 bgcolor="#000000" width="100%" height="100%"><tr><td bgcolor=#1679AA><marquee align="middle" direction="right" behavior="ALTERNATE" scrollamount="2" style="font-size:9pt"><font color=#FFFFFF>...正在处理...请等待...</font></marquee></td></tr></table>
</div>
</body></html>