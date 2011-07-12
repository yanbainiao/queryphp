<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/project/rbac/js/jquery-1.3.2.min.js"></script>
<script language="javascript">
function setrbac(obj,id)
{
	 if(obj.checked)
	 {						 
		jQuery.getJSON("<?php echo url_for("rbac/setrgaccess",true);?>/rid/<?php echo $info['gid'];?>/sid/"+id+'/check/1/'+Math.random(),function(data){
								
								 jQuery("#showmsg").html(data['msg']);
                                  jQuery("#showmsg").show();
								 jQuery("#showmsg").hide(1000); 
							 });				 

	 }else{					 
		jQuery.getJSON("<?php echo url_for("rbac/setrgaccess",true);?>/rid/<?php echo $info['gid'];?>/sid/"+id+'/check/0/'+Math.random(),function(data){
     								 jQuery("#showmsg").html(data['msg']);
                                  jQuery("#showmsg").show();
								 jQuery("#showmsg").hide(1000);                               
							 });				 
   
	 }	 

}
</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="#"><STRONG><FONT color=#ff6600>权限汇总列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/grouplist",true);?>">返回组管理</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE><br />
 &nbsp;&nbsp; [<span class="message"><?php echo $info['groupname'];?></span>] 组权限管理,注意组的成员会继承下面的权限，如果不想让组员继承请使用角色来代替<br>
 然后在组的角色管理那里设置为不可继承。<br />
<hr color="#0066CC" align="left" width="400">
<table width="830" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th height="25" align="center" nowrap="nowrap" class="thCornerR"><span class="message"><?php echo $info['groupname'];?></span> 组权限设置 需要设置请限后面打钩√</th>
  </tr>

   <tr>
     <td height="50" width="826" valign="middle" nowrap="nowrap" align="left"><ul style="padding:0; margin:0">
         <?php if(is_array($rlist)):foreach($rlist as $rbac):?>
         <li style="width:273px;float:left; padding:0; margin:0; list-style-type:none;"><table width="273" border="0" cellpadding="2" cellspacing="1" class="forumline">
           <tr>
             <td width="97%" class="row2"><?php echo $rbac['name']?></td>
             <td width="3%" class="row2"><input type="checkbox" name="prbac[<?php echo $rbac['rbacid'];?>]" id="prbac<?php echo $rbac['rbacid'];?>" <?php if(isset($urbacid[$rbac['rbacid']])):?>checked<?php endif;?> onClick="setrbac(this,<?php echo $rbac['rbacid'];?>);"></td>
           </tr>
            <?php if(is_array($rbac['sub'])):foreach($rbac['sub'] as $k=>$v):?>
           <tr>
             <td height="25"  class="row1">&nbsp;|- <?php echo $v['name'];?></td>
             <td  class="row1"><input type="checkbox" name="rbac[<?php echo $v['rbacid'];?>]" id="rbac<?php echo $v['rbacid'];?>" <?php if(isset($urbacid[$v['rbacid']])):?>checked<?php endif;?> onClick="setrbac(this,<?php echo $v['rbacid'];?>);"></td>
           </tr>
           <?php endforeach; endif;?>
         </table></li>
         <?php endforeach; endif;?>
        </ul>
     </table></td>
   </tr>
   
   <tr>
    <td height="50" align="center" valign="middle" nowrap="nowrap" class="row2"></td>
  </tr> 
</table>
<br />
<div id="showmsg" class="cattitle" style="position:absolute;left:10px; top:10px;"></div>
<br />
</body>
</html>