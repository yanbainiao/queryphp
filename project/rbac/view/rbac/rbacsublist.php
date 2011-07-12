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
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/rbaclist",true);?>">权限系表</a></span></TD>
		  <TD width=10>&nbsp;</TD>        
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/rbacsublist/sid/".$gid,true);?>"><STRONG><FONT color=#ff6600>子权限资源列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addsubrbac/sid/".$gid,true);?>">添加子权限资源</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="141" align=right></TD>
</TR></TBODY></TABLE>
<br />
<br />
<?php echo $info['name'];?> 权限子权限管理,可以继承父级设置应用于所有子权限。
<hr color="#0066CC" align="left" width="400">
<table width="600" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="28%" height="25" align="center" nowrap="nowrap" class="thCornerL">Router名</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">访法</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">权限名称</th>
	<th width="35%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['rbacid'];?></td>
    <td align="center" class="row2"><?php echo $v['model'];?></td>
    <td align="center" class="row2"><?php echo $v['method'];?></td>
    <td align="center" class="row2"><?php echo $v['name'];?></td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap">[<a href="<?php echo url_for("rbac/deletesubrbac/sid/".$v['rbacid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editsubrbac/sid/".$v['rbacid'],true);?>">编辑</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
</html>