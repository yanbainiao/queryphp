<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/project/rbac/js/jquery-1.3.2.min.js"></script>
<script language="javascript">
function selectall(obj)
{
	 if(obj.checked)
	 {
	   jQuery(".test").attr("checked",true);
	   
	   jQuery(".test").each(function(){
									 
					jQuery.getJSON("<?php echo url_for("rbac/roleuser",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/1/'+Math.random(),function(data){
                                   
							 });				 
									 });
	 }else{
	   jQuery(".test").attr("checked",false);	
	   jQuery(".test").each(function(){
									 
					jQuery.getJSON("<?php echo url_for("rbac/roleuser",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/0/'+Math.random(),function(data){
                                   
							 });				 
									 });	   
	 }	 

}
jQuery(document).ready(function(){
  	   jQuery(".test").click(function(){
									 
									 var check=0;
									 if(this.checked)
									 {
										 check=1;
									 }
									 
					jQuery.getJSON("<?php echo url_for("rbac/roleuser",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/'+check+'/'+Math.random(),function(data){
                                   
											alert(data['msg']);										   
							 });				 
									 });
}); 

</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="#"><STRONG><FONT color=#ff6600>圈角色人员列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/rolelist",true);?>">返回角色管理</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE><br />
 &nbsp;&nbsp; <span class="message"><?php echo $info['rolename'];?></span> 角色人员权限管理 ，该角色包含那些人员
<br />
<hr color="#0066CC" align="left" width="400">
<table width="640" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="10%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="10%" align="center" nowrap="nowrap" class="thCornerL">圈定<input type="checkbox" name="wwaa2" onClick="selectall(this);" id="wwaa2">
    </th>
    <th width="27%" height="25" align="center" nowrap="nowrap" class="thCornerL">名字</th>
    <th width="21%" align="center" nowrap="nowrap" class="thCornerL">登录名</th>
	<th width="32%" align="center" nowrap="nowrap" class="thCornerR">Email</th>
  </tr>
  <?php foreach($userrecord as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['uid'];?></td>
    <td align="center" class="row2"><input type="checkbox" name="checkbox<?php echo $v['uid'];?>" id="checkbox<?php echo $v['uid'];?>" class="test" value="<?php echo $v['uid'];?>" <?php if(isset($roleuid[$v['uid']])):?>checked<?php endif;?>></td>
    <td align="center" class="row2"><?php echo $v['realname'];?></td>
    <td align="center" class="row2"><?php echo $v['username'];?></td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap"><?php echo $v['email'];?></td>
  </tr>
  <?php endforeach;?>
   <tr>
    <td height="50" align="center" class="row2"></td>
    <td align="center" class="row2">&nbsp;</td>
    <td align="center" class="row2"></td>
    <td align="center" class="row2"></td>
    <td class="row2" align="center" valign="middle" nowrap="nowrap"></td>
  </tr> 
</table>
<br />
<table width="80%" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
   <tr>
    <td height="50" align="left" class="row2"><?php echo $nav_bar;?></td>
  </tr> 
</table>
</body>
</html>