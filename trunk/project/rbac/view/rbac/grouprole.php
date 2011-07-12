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
									 
					jQuery.getJSON("<?php echo url_for("rbac/setgrouprole",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/1/'+Math.random(),function(data){
                                   
							 });				 
									 });
	 }else{
	   jQuery(".test").attr("checked",false);	
	   jQuery(".test").each(function(){
									 
					jQuery.getJSON("<?php echo url_for("rbac/setgrouprole",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/0/'+Math.random(),function(data){
                                   
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
									 
					jQuery.getJSON("<?php echo url_for("rbac/setgrouprole",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/'+check+'/'+Math.random(),function(data){
                                   
											alert(data['msg']);										   
							 });				 
									 });
	  	   jQuery(".mar").click(function(){
									 if(!document.getElementById("role"+this.value).checked) { alert("该角色必需是组的角色"); return false; };   
									 var check=0;
									 if(this.checked)
									 {
										 check=1;
									 }
									 
					jQuery.getJSON("<?php echo url_for("rbac/grouprolemap",true);?>/iid/<?php echo $sid;?>/sid/"+this.value+'/check/'+check+'/'+Math.random(),function(data){
                                   
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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="#"><STRONG><FONT color=#ff6600>角色列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/grouplist",true);?>">返回组管理</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE><br />
 &nbsp;&nbsp; <span class="message"><?php echo $info['groupname'];?></span> 组的角色管理
,注意组成员会继承组的直接权限，这个在权限在组权限那里设置。<br>
如果不想让组成员继承组权限可以使用下面角色来设置，然后把角色设置为不继承。<br />
<hr color="#0066CC" align="left" width="400">
<table width="480" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="7%" height="25" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="10%" align="center" nowrap="nowrap" class="thCornerL">圈定<input type="checkbox" name="checkbox2" onClick="selectall(this);" id="checkbox2">
    </th>
    <th width="19%" align="center" nowrap="nowrap" class="thCornerL">组员是否有继承组的权限</th>
    <th width="19%" align="center" nowrap="nowrap" class="thCornerL">角色</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['roleid'];?></td>
    <td align="center" class="row2"><input type="checkbox" name="role<?php echo $v['roleid'];?>" id="role<?php echo $v['roleid'];?>" class="test" value="<?php echo $v['roleid'];?>" <?php if(isset($grlist[$v['roleid']])):?>checked<?php endif;?>></td>
    <td align="center" class="row2"><input type="checkbox" name="jicheng<?php echo $v['roleid'];?>" id="jicheng<?php echo $v['roleid'];?>" value="<?php echo $v['roleid'];?>" class="mar" <?php if(isset($jicheng[$v['roleid']])&&$jicheng[$v['roleid']]=='Y'):?>checked<?php endif;?>></td>
    <td align="center" class="row2"><?php echo $v['rolename'];?></td>
  </tr>
  <?php endforeach;?>
   <tr>
    <td height="50" align="center" class="row2"></td>
    <td align="center" class="row2">&nbsp;</td>
    <td align="center" class="row2"></td>
    <td align="center" class="row2"></td>
  </tr> 
</table>
<br />
</body>
</html>