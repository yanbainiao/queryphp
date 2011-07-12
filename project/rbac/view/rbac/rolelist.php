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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/rolelist",true);?>"><STRONG><FONT color=#ff6600>角色列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addrole",true);?>">添加角色</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>

<hr color="#0066CC" align="left" width="400">
<table width="760" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="9%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="26%" height="25" align="center" nowrap="nowrap" class="thCornerL">角色名称</th>
    <th width="26%" align="center" nowrap="nowrap" class="thCornerL">说明</th>
    <th width="15%" align="center" nowrap="nowrap" class="thCornerL"><span class="thCornerR">操作</span></th>
	<th width="24%" align="center" nowrap="nowrap" class="thCornerR">权限操作</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['roleid'];?></td>
    <td align="center" class="row2"><?php echo $v['rolename'];?></td>
    <td align="center" class="row2"><?php echo $v['dest'];?></td>
    <td align="center" class="row2">[<a href="<?php echo url_for("rbac/deleterole/sid/".$v['roleid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editrole/sid/".$v['roleid'],true);?>">编辑</a>]</td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap">[<a href="<?php echo url_for("rbac/setroleuser/rid/".$v['roleid'],true);?>">人员列表</a>][<a href="<?php echo url_for("rbac/setrolerbac/rid/".$v['roleid'],true);?>">权限设置</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
</html>