<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/project/rbac/js/jquery-1.3.2.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function() {

						      jQuery(".pda").click(function(){
									 var check=0;
									 if(this.checked)
									 {
										 check=1;
									 }	
							             jQuery.getJSON("<?php echo url_for("rbac/setdailiaction",true);?>/sid/"+this.value+'/check/'+check+'/'+Math.random(),function(data){
                                   
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
          <TD width=115 align=center  background="<?php echo url_project();?>images/30.gif"><a href="<?php echo url_for("rbac/projectlist",true);?>"><STRONG><FONT color=#ff6600>代理公司列表</FONT></STRONG></a></TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/29.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addproject",true);?>">添加代理公司</a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">
<table width="830" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="9%" align="center" nowrap="nowrap" class="thCornerL">序号</th>
    <th width="35%" height="25" align="center" nowrap="nowrap" class="thCornerL">公司名字</th>
    <th width="16%" align="center" nowrap="nowrap" class="thCornerR">登录名</th>
    <th width="9%" align="center" nowrap="nowrap" class="thCornerR">激活代理</th>
    <th width="16%" align="center" nowrap="nowrap" class="thCornerR">代理客户</th>
    <th width="15%" align="center" nowrap="nowrap" class="thCornerR">操作</th>
  </tr>
  <?php foreach($projectlist as $k=>$v):?>                
  <tr>
    <td height="30" align="center" class="row2"><?php echo $v['projectid'];?></td>
    <td height="30" align="center" class="row2"><?php echo $v['projectname'];?></td>
    <td align="center" valign="middle" nowrap="nowrap" class="row2"><?php echo $v['loginname'];?></td>
    <td align="center" valign="middle" nowrap="nowrap" class="row2"><input name="isaction" type="checkbox" id="isaction" class="pda" value="<?php echo $v['projectid'];?>" <?php if($v['isaction']=='Y'):?>checked<?php endif;?>></td>
    <td align="center" valign="middle" nowrap="nowrap" class="row2"><a href="#">查看客户</a></td>
    <td height="30" align="center" valign="middle" nowrap="nowrap" class="row2">[<a  onClick="return confirm('确认删除吗?')"  href="<?php echo url_for("rbac/deleteproject/sid/".$v['projectid'],true);?>">删除</a>] [<a href="<?php echo url_for("rbac/editproject/sid/".$v['projectid'],true);?>">编辑</a>]</td>
  </tr>
  <?php endforeach;?>
</table>
<table width="80%" border="0" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <td><?php echo $nav_bar;?></td>
  </tr>
</table>
<p><a href="<?php echo url_for("rbac/exportdaili",true);?>" target="_blank">导出代理机构信息</a></p>
<div id="showmsg"></div>
</html>