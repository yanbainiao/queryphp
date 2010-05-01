<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>办公OA管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url_project();?>js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo url_project();?>js/jquery.validate.min.js"></script>
<script language="javascript">
jQuery(document).ready(function(){
jQuery("#commentForm").validate();
});

</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="padding-top:200px; background-color:#606060"><br />
<form name="commentForm" id="commentForm" method="post" action="<?php echo url_for("rbacmar/loginpost");?>">
<table width="300" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th height="25" colspan="2" align="center" nowrap="nowrap" class="thCornerR">后台登录</th>
	</tr>
  <tr>
    <td width="91" height="30" align="center" valign="middle" nowrap="nowrap" class="row2">用户名</td>
    <td width="198" height="30" align="center" valign="middle" nowrap="nowrap" class="row2"><input name="loginname" type="text" id="loginname" class="required" title="必须填用户名"></td>
  </tr>
  <tr>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2">密码</td>
    <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap"><input name="pwd" type="password" id="pwd"  class="required" title="必须填密码"></td>
  </tr>
  <tr>
    <td class="row2" align="center" valign="middle" nowrap="nowrap"><input name="keduz" type="hidden" id="keduz" value="<?php echo $checknum;?>"></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">
      <input type="submit" name="Submit" value="提交"></td>
  </tr>
</table>
</form>
</html>