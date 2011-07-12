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
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/grouplist",true);?>">组列表</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addgroup",true);?>"><STRONG><FONT color=#ff6600>添加组别</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/editgrouppost",true);?>"><table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="23%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="65%" height="25" align="center" nowrap="nowrap" class="thCornerL">
  </th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
   <tr>
    <td align="center" class="row2">公司名称</td>
    <td align="left" class="row2"><select name="projectid" id="projectid">
          <?php if(is_array($group)):foreach($group as $g):?>
          <option value="<?php echo $g['projectid'];?>" <?php if($g['projectid']==$info['pid']):?>selected<?php endif;?>><?php echo $g['projectname'];?></option>
          <?php endforeach; endif;?>    
    </select></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr> 
  <tr>
  <td align="center" class="row2">组名:</td>
  <td align="left" class="row2"><input type="text" name="groupname" id="groupname" value="<?php echo $info['groupname'];?>"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
  <tr>
    <td align="center" class="row2">管理员</td>
    <td align="left" class="row2"><input name="uid" type="hidden" id="uid" value="<?php echo $info['uid']; ?>"><a href="" id="uidmar"></a></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
  <td align="center" class="row2">组说明:</td>
  <td align="left" class="row2"><textarea name="dest" id="dest" cols="45" rows="5"><?php echo $info['dest'];?></textarea></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="left" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2"><input name="gid" type="hidden" id="gid" value="<?php echo $info['gid'];?>">    <input type="submit" name="addsuper" id="addsuper" value="修改"></td>
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