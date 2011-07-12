<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="<?php echo url_project();?>js/ui.datepicker.js"></script>
<link href="<?php echo url_project();?>js/ui.datepicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url_project();?>js/ui.datepicker-zh-CN.js"></script>
<script type="text/javascript" src="<?php echo url_project();?>js/jquery.validate.min.js"></script>
<script language="javascript">

jQuery(document).ready(function(){
								jQuery('#adddate').datepicker();
								jQuery('#enddate').datepicker();
	jQuery("#mainform").validate();
})
</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/projectlist",true);?>">代理公司列表</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addproject",true);?>"><STRONG><FONT color=#ff6600>添加代理公司</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" id="mainform" method="post" action="<?php echo url_for("rbac/addprojectpost",true);?>"><table width="640" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="17%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="79%" height="25" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="4%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
         
  <tr>
    <td height="30" align="center" class="row2">管理帐号</td>
    <td height="30" align="left" class="row2"><input type="text" name="loginname" id="loginname"></td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" align="center" class="row2">管理密码</td>
    <td height="30" align="left" class="row2"><input type="text" name="loginpwd" id="loginpwd"></td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td height="30" align="center" class="row2">&nbsp;</td>
    <td height="30" align="left" class="row2">&nbsp;</td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="row2">公司名称:</td>
    <td height="30" align="left" class="row2"><input type="text" name="projectname" id="projectname"></td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">所在省份</td>
  <td height="30" align="left" class="row2"><select name="province" id="province">
        <option value="0">选择所在省份</option>
      <?php if(is_array($p)):foreach($p as $k=>$g):?>
      <option value="<?php echo $k;?>" <?php if($k==$info['province']):?>selected<?php endif;?>><?php echo $g?></option>
      <?php endforeach; endif;?>
    </select></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">代理级别</td>
  <td height="30" align="left" class="row2"><input type="text" name="business" id="business"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">联系人姓名</td>
  <td height="30" align="left" class="row2"><input type="text" name="linkname" id="linkname"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">联系人职务</td>
  <td height="30" align="left" class="row2"><input name="job_bm" type="text" id="job_bm" size="20"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">联系人电话</td>
  <td height="30" align="left" class="row2"><input name="iphone1" type="text" id="iphone1" size="6">
    区号      
    <input name="iphone2" type="text" id="iphone2" size="10">
    电话 
    <input name="iphone3" type="text" id="iphone3" size="6"> 
    分机</td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">手机号码</td>
  <td height="30" align="left" class="row2"><input type="text" name="mobile" id="mobile"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">电子邮件</td>
  <td height="30" align="left" class="row2"><input type="text" name="email" id="email"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">法人姓名</td>
  <td height="30" align="left" class="row2"><input type="text" name="jinjilinks" id="jinjilinks"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">营业执照编号</td>
  <td height="30" align="left" class="row2"><input type="text" name="jinjiipone" id="jinjiipone"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">注册地址</td>
  <td height="30" align="left" class="row2"><input type="text" name="regaddress" id="regaddress"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">邮政编码</td>
  <td height="30" align="left" class="row2"><input type="text" name="zipnum" id="zipnum"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">邮寄地址</td>
  <td height="30" align="left" class="row2"><input name="servericname" type="text" id="servericname"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">合同有效期</td>
  <td height="30" align="left" class="row2"><input name="adddate" type="text" id="adddate" size="12">
    -
      <input name="enddate" type="text" id="enddate" size="12"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td height="30" align="center" class="row2">&nbsp;</td>
  <td height="30" align="left" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
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