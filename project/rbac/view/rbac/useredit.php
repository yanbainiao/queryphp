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
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/userimport",true);?>">导入销售</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/adduser",true);?>"><STRONG><FONT color=#ff6600>添加销售</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/edituserpost",true);?>">
  <table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
    <tr>
    <th width="23%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="65%" height="25" align="center" nowrap="nowrap" class="thCornerL">销售人员编辑
  </th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
   <?php if($_SESSION['cid']==''):?>       
  <?php endif;?>
  <tr>
    <td align="center" class="row2">选择公司</td>
    <td align="left" class="row2"><select name="projectid" id="projectid">
      <?php foreach($projectlist as $k=>$v):?>
      <option value="<?php echo $v['projectid'];?>" <?php if($v['projectid']==$info['projectid']) echo "selected";?>><?php echo $v['projectname'];?></option>
      <?php endforeach;?>
    </select></td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
  <td align="center" class="row2">登录名:</td>
  <td align="left" class="row2"><input name="username" type="text" id="username" value="<?php echo $info['username'];?>" class="required"><span style="color:#F00;">*</span></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">登录密码</td>
  <td align="left" class="row2"><input type="text" name="password" id="password">
    不修改不用填</td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">职务</td>
  <td align="left" class="row2"><input name="job" type="text" id="job" value="<?php echo $info['job'];?>"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">真实名字</td>
  <td align="left" class="row2"><input name="realname" type="text" id="realname" value="<?php echo $info['realname'];?>" class="required"><span style="color:#F00;">*</span></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">性别</td>
  <td align="left" class="row2"><input type="radio" name="sex" id="radio" value="1" <?php if($info['sex']==1) echo "checked";?>>
    男 
    <input type="radio" name="sex" id="radio2" value="0" <?php if($info['sex']==0) echo "checked";?>>
    女</td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">电子邮件</td>
  <td align="left" class="row2"><input name="email" type="text" id="email" value="<?php echo $info['email'];?>"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2"><input name="uid" type="hidden" id="uid" value="<?php echo $info['uid'];?>">    <input type="submit" name="addsuper" id="addsuper" value="修改"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
</table>
</form>
</html>