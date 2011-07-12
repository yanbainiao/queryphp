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
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/superlist",true);?>">管理员列表</a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addsuper",true);?>"><STRONG><FONT color=#ff6600>添加管理员</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/addsuperpost",true);?>"><table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="11%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="28%" height="25" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="5%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
         
<tr>
  <td align="center" class="row2">管理员登录名:</td>
  <td align="left" class="row2"><input type="text" name="adminname" id="adminname"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">登录密码</td>
  <td align="left" class="row2"><input type="password" name="adminpwd" id="adminpwd"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">密码确认</td>
  <td align="left" class="row2"><input type="password" name="adminpwd1" id="adminpwd1"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">真实名字</td>
  <td align="left" class="row2"><input type="text" name="linkname" id="linkname"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2"><input type="submit" name="addsuper" id="addsuper" value="添加"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
</table>
</form>
</html>