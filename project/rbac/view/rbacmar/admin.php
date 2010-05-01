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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("/jifen/list");?>"><STRONG><FONT color=#ff6600>管理员列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("/jifen/setaddset");?>">用户列表</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th align="center" nowrap="nowrap" class="thCornerL">ID</th>
    <th align="center" nowrap="nowrap" class="thCornerL"></th>
	<th height="25" align="center" nowrap="nowrap" class="thCornerL"></th>
	<th align="center" nowrap="nowrap" class="thTop">&nbsp;&nbsp;</th>
	<th align="center" nowrap="nowrap" class="thTop">&nbsp;</th>
	<th align="center" nowrap="nowrap" class="thCornerR"></th>
	<th align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
                  
  <tr>
    <td class="row2">&nbsp;</td>
    <td class="row2">&nbsp;</td>
    <td height="50" colspan="3" class="row2"><form action="" method="post" name="commentForm" id="commentForm">
      <input name="keyword" type="text" id="keyword" class="required" title="必需填写关键字!">
        <input type="submit" name="Submit" value="查找">
     按名字或会员登录帐号 
    </form>    </td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr>
</table>
</html>