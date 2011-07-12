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
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/userimport",true);?>">导入人员</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/adduser",true);?>"><STRONG><FONT color=#ff6600>添加人员</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/adduserpost",true);?>">
  <table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <td align="center" class="row2">选择公司</td>
    <td align="left" class="row2"><select name="projectid" id="projectid">
    <?php foreach($projectlist as $k=>$v):?>
    <option value="<?php echo $v['projectid'];?>"><?php echo $v['projectname'];?></option>
    <?php endforeach;?>
  </select></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="right" class="row2">姓名</td>
  <td align="left" class="row2"><input type="text" name="realname" id="realname" class="required"><span style="color:#F00;">*</span></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="right" class="row2">职务</td>
  <td align="left" class="row2"><input type="text" name="job" id="job"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
  <tr>
    <td width="23%" align="right" class="row2">用户名</td>
    <td width="65%" align="left" class="row2"><input type="text" name="username" id="username" class="required"><span style="color:#F00;">*</span></td>
    <td width="12%" height="30" align="center" valign="middle" nowrap="nowrap" class="row2">&nbsp;</td>
</tr>

<tr>
  <td align="right" class="row2">密码</td>
  <td align="left" class="row2"><input type="password" name="password" id="password" class="required"><span style="color:#F00;">*</span></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>

<tr>
  <td align="right" class="row2">性别</td>
  <td align="left" class="row2"><input type="radio" name="sex" id="radio" value="1">
    男 
      <input type="radio" name="sex" id="radio2" value="0">
      女</td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="right" class="row2">电子邮件</td>
  <td align="left" class="row2"><input type="text" name="email" id="email" class="required"><span style="color:#F00;">*</span></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td height="30" colspan="3" align="center" class="row2"><input type="submit" name="addsuper" id="addsuper" value="添加">
    <input type="reset" name="button" id="button" value="重置" /></td>
  </tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
</table>
</form>
</html>