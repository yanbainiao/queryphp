<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo url_project();?>js/jquery-1.3.2.min.js"></script>
<script language="javascript">
function ajaxtest(id)
{
	var title=jQuery("#title"+id).val();
	jQuery.ajax({
 url: "<?php echo url_for("rbac/ajaxaclmethod",true);?>/title/"+encodeURIComponent(title)+"/mid/"+id+"/randrom/"+Math.random(),
 type: 'json',
 dataType: 'html',
 timeout: 20000,//超时时间设定
 success: function(html){
   alert(html);
 }
	});
}
function ajaxdelete(id)
{
	jQuery.getJSON("<?php echo url_for("rbac/deleteaclmethod",true);?>/mid/"+id+"/randrom/"+Math.random(),function(data){
                                   alert(data['msg']);
								   if(data['state']==1) jQuery("#acmd"+id).remove();
							 });
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/acllist",true);?>"><STRONG><FONT color=#ff6600>方法列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/acllist",true);?>">Router类列表</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE><br />
<span class="cattitle"><?php echo $info['title'];?>(<?php echo $info['model'];?>)</span> 方法列表,在添加资源管理权限用到
<hr color="#0066CC" align="left" width="400"><br />
<table width="760" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="29%" height="25" align="center" nowrap="nowrap" class="thCornerL">权限名称</th>
    <th width="29%" align="center" nowrap="nowrap" class="thCornerL">方法名称</th>
    <th width="30%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
  </tr>
  <?php foreach($acllist as $k=>$v):?>                
  <tr id="acmd<?php echo $v['caclid'];?>">
    <td height="30" align="center" class="row2"><?php echo $v['caclid'];?></td>
    <td height="30" align="center" class="row2"><input type="text" name="title[<?php echo $v['caclid'];?>]" id="title<?php echo $v['caclid'];?>" value="<?php echo $v['title'];?>"></td>
    <td height="30" align="center" class="row2"><?php echo $v['method'];?></td>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2"><input type="button" name="button<?php echo $v['caclid'];?>" onClick="ajaxtest(<?php echo $v['caclid'];?>);" id="button<?php echo $v['caclid'];?>" value="编辑权限名称">
    <input type="button" name="button<?php echo $v['caclid'];?>2" onClick="ajaxdelete(<?php echo $v['caclid'];?>);" id="button<?php echo $v['caclid'];?>2" value="删除本方法权限"></td>
  </tr>
  <?php endforeach;?>
</table>
<p>如果是误删除方法，先到Router类编辑下就可以找回来了</p>
<p><br />
  <br />
</p>
</html>