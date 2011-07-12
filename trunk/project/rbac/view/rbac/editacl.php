<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url_project();?>js/jquery-1.3.2.min.js"></script>
<script language="javascript">
function ajaxtest()
{
	var url=jQuery("#path").val();
	 url=url.replace("/","_");
  	jQuery.getJSON("<?php echo url_for("rbac/refclassmethod",true);?>/name/"+jQuery("#model").val()+"/path/"+encodeURIComponent(url)+"/randrom/"+Math.random(),function(data){
                                   jQuery("#dest").html(data['msg']);
							 });
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/acllist",true);?>">Router类列表</a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addacl",true);?>"><STRONG><FONT color=#ff6600>添加Router类</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/editaclpost",true);?>"><table width="500" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="19%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="75%" height="25" align="center" nowrap="nowrap" class="thCornerL">如果原来Router文件变了修改后方法也会变
  </th>
    <th width="6%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>
 
  <tr>
    <td align="center" class="row2">Router路径</td>
    <td align="left" class="row2"><input name="path" type="text" id="path" value="<?php echo $info['aclpath'];?>" size="60"></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="row2">&nbsp;</td>
    <td align="left" class="row2"><p>比如:http://www.abc.com/project/default/index<br>
      那么default就是你的Router地址<br>
      Router路径应该是:project<br>
      也就是相对于框架framework位置<br>
    程序会搜索<br>
    framework/router/Router类名Router.class.php 目录<br>
    ../project/router/Router类名Router.class.php  目录</p></td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
  <td align="center" class="row2">Router类名:</td>
  <td align="left" class="row2"><input name="model" type="text" id="model" size="10" value="<?php echo $info['model'];?>">
    <input type="button" name="addsuper2" id="addsuper2" value="测试该类" onClick="ajaxtest();"></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
  <tr>
    <td align="center" class="row2">权限名称</td>
    <td align="left" class="row2"><input name="title" type="text" id="title" size="12" value="<?php echo $info['title'];?>"> 
      比如 新闻管理 产品展示 添加权限时候用到</td>
    <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
  <td align="center" class="row2">方法查看:</td>
  <td align="left" class="row2"><div id="dest"></div></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="left" class="row2">&nbsp;</td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2"><input name="aclid" type="hidden" id="aclid" value="<?php echo $info['aclid'];?>"></td>
  <td align="center" class="row2"><input type="submit" name="addsuper" id="addsuper" value="修改Router类名"></td>
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