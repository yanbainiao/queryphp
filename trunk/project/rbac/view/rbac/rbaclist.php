<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/rbaclist",true);?>"><STRONG><FONT color=#ff6600>权限资源列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addrbac",true);?>">添加权限资源</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="820" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="6%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="15%" height="25" align="center" nowrap="nowrap" class="thCornerL">Router名</th>
    <th width="15%" align="center" nowrap="nowrap" class="thCornerL">应用于子权限</th>
    <th width="17%" align="center" nowrap="nowrap" class="thCornerL">权限名称</th>
	<th width="26%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
	<th width="21%" align="center" nowrap="nowrap" class="thCornerR">权限操作</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['rbacid'];?></td>
    <td align="center" class="row2"><?php echo $v['model'];?></td>
    <td align="center" class="row2"><?php if($v['isAll']=='Y'):?>应用<?php else:?>不应用<?php endif;?></td>
    <td align="center" class="row2"><?php echo $v['name'];?></td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap">[<a href="<?php echo url_for("rbac/deleterbac/sid/".$v['rbacid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editrbac/sid/".$v['rbacid'],true);?>">编辑</a>][<a href="<?php echo url_for("rbac/rbacsublist/sid/".$v['rbacid'],true);?>">子权限列表</a>]</td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap">[<a href="<?php echo url_for("rbac/rbacupdate/sid/".$v['rbacid'],true);?>">应用权限</a>][<a href="<?php echo url_for("rbac/rbacdelac/sid/".$v['rbacid'],true);?>">取消权限</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
<p>权限设置完毕需要 <STRONG><FONT color=#ff6600>应用权限</FONT></STRONG><br>
应用权限到系统才能真正生效,取消权限将会删除权限文件任何人可以访问,有些需要登录的如果取消了取不到登录信息可能会出错。</p>
<p><a href="<?php echo url_for("rbac/rbaccache/",true);?>">清除人员权限缓存</a> 因为人员或前台会员登录第一次后，权限会自动缓存起来，当改变人员或会员所在的角色或组时候体现不出来，所要清理下缓存</p>
</html>