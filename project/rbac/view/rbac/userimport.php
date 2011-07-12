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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/userimport",true);?>"><STRONG><FONT color=#ff6600>导入人员</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/adduser",true);?>">添加人员</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<form action="<?php echo url_for("rbac/adduserimport",true);?>" method="post" enctype="multipart/form-data" name="form1"><table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="23%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="65%" height="25" align="center" nowrap="nowrap" class="thCornerL">
  </th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
   <?php if($_SESSION['cid']==''):?>       
  <tr>
    <td align="center" class="row2">选择公司</td>
    <td align="left" class="row2"> 
    <select name="projectid" id="projectid">     
    <?php foreach($projectlist as $k=>$v):?> 
     <option value="<?php echo $v['projectid'];?>"><?php echo $v['projectname'];?></option>
    <?php endforeach;?>
    </select></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr><?php endif;?>
  <tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="left" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">上传xls</td>
  <td align="left" class="row2"><input type="file" name="userfile" id="userfile"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="left" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2"><input type="submit" name="addsuper" id="addsuper" value="导入用户"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
</table>
</form>
<p>请在excel里面设置用户名如下面图所示的用户列表，注意用户名如果跟现有用户冲突将会在后面加上几个数字，被有很多最好分为200个被试提交</p>
<p><img src="<?php echo url_project();?>images/importuser.gif"></p>
</body>
</html>