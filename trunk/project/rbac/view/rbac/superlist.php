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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/superlist",true);?>"><STRONG><FONT color=#ff6600>管理员列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addsuper",true);?>">添加管理员</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="640" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="28%" height="25" align="center" nowrap="nowrap" class="thCornerL">管理员名字</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">登录名</th>
	<th width="35%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
	<th width="5%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
  <?php foreach($userrecord as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['supperid'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['linkname'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['adminname'];?></td>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2">[<a href="<?php echo url_for("rbac/deletesuper/sid/".$v['supperid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editsuper/sid/".$v['supperid'],true);?>">编辑</a>]</td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
  </tr>
  <?php endforeach;?>
</table>
</html>