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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/mymemberlist",true);?>"><STRONG><FONT color=#ff6600>人员列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/adduser",true);?>">添加人员</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="830" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="5%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="18%" height="25" align="center" nowrap="nowrap" class="thCornerL">姓名</th>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL">职务</th>
    <th width="20%" align="center" nowrap="nowrap" class="thCornerL">登录名</th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
	<th width="34%" align="center" nowrap="nowrap" class="thCornerR">权限管理</th>
  </tr>
  <?php foreach($userlist as $k=>$v):?>                
  <tr>
    <td height="25" align="center" class="row2"><?php echo $v['uid'];?></td>
    <td height="25" align="center" class="row2"><?php echo $v['realname'];?></td>
    <td align="center" class="row2"><?php echo $v['job'];?></td>
    <td height="25" align="center" class="row2"><?php echo $v['username'];?></td>
    <td align="center" valign="middle" nowrap="nowrap" class="row2">[<a  onClick="return confirm('确认删除吗?')"  href="<?php echo url_for("rbac/deleteuser/sid/".$v['uid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/useredit/sid/".$v['uid'],true);?>">编辑</a>] </td>
    <td height="25" align="center" valign="middle" nowrap="nowrap" class="row2">[<a href="<?php echo url_for("rbac/userrole/sid/".$v['uid'],true);?>">所属角色</a>] [<a href="<?php echo url_for("rbac/usergroup/sid/".$v['uid'],true);?>">所属组</a>][<a href="<?php echo url_for("rbac/userviewrbac/sid/".$v['uid'],true);?>">查看权限</a>] [<a href="<?php echo url_for("rbac/urbaccache/sid/".$v['uid'],true);?>">清除缓存</a>]</td>
  </tr>
  <?php endforeach;?>
</table>

<p>&nbsp;</p>
<table width="60%" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
           
  <tr>
    <td height="50" align="center" class="row2"><?php echo $nav_bar;?></td>
  </tr>

</table>
</html>