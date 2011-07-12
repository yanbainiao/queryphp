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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/grouplist",true);?>"><STRONG><FONT color=#ff6600>组列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addgroup",true);?>">添加组</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="830" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="6%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="22%" height="25" align="center" nowrap="nowrap" class="thCornerL">组名</th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerL">管理员</th>
    <th width="19%" align="center" nowrap="nowrap" class="thCornerL">说明</th>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL"><span class="thCornerR">操作</span></th>
	<th width="30%" align="center" nowrap="nowrap" class="thCornerR">权限操作</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['gid'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['groupname'];?></td>
    <td height="30" align="center" class="row2"><a href="<?php echo url_for("rbac/useredit/sid/".$v['uid'],true);?>"><?php echo $ua[$v['uid']];?></a></td>
    <td height="30" align="center" class="row2"><?php echo $v['dest'];?></td>
    <td align="center" class="row2">[<a href="<?php echo url_for("rbac/deletegroup/sid/".$v['gid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editgroup/sid/".$v['gid'],true);?>">编辑</a>]</td>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2">[<a href="<?php echo url_for("rbac/setgroupuser/sid/".$v['gid'],true);?>">组员管理</a>][<a href="<?php echo url_for("rbac/grouprole/sid/".$v['gid'],true);?>">角色权限管理</a>][<a href="<?php echo url_for("rbac/setgrouprbac/sid/".$v['gid'],true);?>">直接权限设置</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
</html>