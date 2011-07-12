<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>通用权限管理</title>
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/project/rbac/js/jquery-1.3.2.min.js"></script>
<script language="javascript">
 Array.prototype.unique = function(){    
      var a = {};  
      var len = this.length;  
      for(var i=0; i<len; i++)  {    
        if(typeof a[this[i]] == "undefined")    
        a[this[i]] = 1;    
      }    
      this.length = 0;    
      for(var i in a)    
     this[this.length] = i;    
     return this;    
   }  
function addgroup()
{
  if(!jQuery("#sourcegroup").val()){ alert("请选择来源组"); return false; }	
  
    if(jQuery("#destgroup option[value='"+jQuery("#sourcegroup").val()+"']").val()) {
		   alert("已存在该组了。"); return false;		
		}
    jQuery("#destgroup").append("<option value='"+jQuery("#sourcegroup").val()+"'>"+jQuery("#sourcegroup").find("option:selected").text()+"</option>");
	
	var a=jQuery("#sourcegroup").val();
	var pdfarray=Array();
        pdfarray=document.getElementById('groupmap').value.split(",");
    pdfarray.push(a);
	pdfarray.unique();
	document.getElementById('groupmap').value=pdfarray.join(",");	
}
function deletegroup()
{
  	if(!jQuery("#destgroup").val()){ alert("请选择要删除目标组"); return false; }	
		var a=jQuery("#destgroup").val();
	jQuery("#destgroup option[value='"+jQuery("#destgroup").val()+"']").remove();
var j=0;
	pdfarray=document.getElementById('groupmap').value.split(",");
	num=pdfarray.length;
	var t=Array();

	for(var i=0;i<num;i++)
	{
	  if(pdfarray[i]==a)
	  {
		
	  }else{
		t.push(pdfarray[i]);
	  }
	}
	t.unique();
	document.getElementById('groupmap').value=t.join(",");	
}
function addacrole()
{
  if(!jQuery("#sourcerole1").val()){ alert("请选择来源角色"); return false; }	
  
    if(jQuery("#destrole1 option[value='"+jQuery("#sourcerole1").val()+"']").val()) {
		   alert("已存在该角色了。"); return false;		
		}
    jQuery("#destrole1").append("<option value='"+jQuery("#sourcerole1").val()+"'>"+jQuery("#sourcerole1").find("option:selected").text()+"</option>");
	
	var a=jQuery("#sourcerole1").val();
	var pdfarray=Array();
        pdfarray=document.getElementById('rolemap').value.split(",");
    pdfarray.push(a);
	pdfarray.unique();
	document.getElementById('rolemap').value=pdfarray.join(",");	
}
function deleteacrole()
{
  	if(!jQuery("#destrole1").val()){ alert("请选择已允许角色"); return false; }	
	var a=jQuery("#destrole1").val();	
	
	jQuery("#destrole1 option[value='"+jQuery("#destrole1").val()+"']").remove();
	
	
var j=0;
	pdfarray=document.getElementById('rolemap').value.split(",");
	num=pdfarray.length;
	var t=Array();

	for(var i=0;i<num;i++)
	{
	  if(pdfarray[i]==a)
	  {
		
	  }else{
		t.push(pdfarray[i]);
	  }
	}
	t.unique();
	document.getElementById('rolemap').value=t.join(",");		
}
function adddcrole()
{
  if(!jQuery("#sourcerole2").val()){ alert("请选择来源角色"); return false; }	
  
    if(jQuery("#destrole2 option[value='"+jQuery("#sourcerole2").val()+"']").val()) {
		   alert("已存在该角色了。"); return false;		
		}
    jQuery("#destrole2").append("<option value='"+jQuery("#sourcerole2").val()+"'>"+jQuery("#sourcerole2").find("option:selected").text()+"</option>");
	
	var a=jQuery("#sourcerole2").val();
	var pdfarray=Array();
        pdfarray=document.getElementById('disablerole').value.split(",");
    pdfarray.push(a);
	pdfarray.unique();
	document.getElementById('disablerole').value=pdfarray.join(",");	
}
function deletedcrole()
{
  	if(!jQuery("#destrole2").val()){ alert("请选择已禁止角色"); return false; }	
	var a=jQuery("#destrole2").val();	
	jQuery("#destrole2 option[value='"+jQuery("#destrole2").val()+"']").remove();
	
var j=0;
	pdfarray=document.getElementById('disablerole').value.split(",");
	num=pdfarray.length;
	var t=Array();

	for(var i=0;i<num;i++)
	{
	  if(pdfarray[i]==a)
	  {
		
	  }else{
		t.push(pdfarray[i]);
	  }
	}
	t.unique();
	document.getElementById('disablerole').value=t.join(",");		
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0"><br />
<TABLE height=28 cellSpacing=0 cellPadding=0 width="546" background="<?php echo url_project();?>images/37.gif" border=0>
        <TBODY>
        <TR>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center  background="<?php echo url_project();?>images/29.gif"><a href="<?php echo url_for("rbac/rbaclist",true);?>">权限资源列表</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width=115 align=center background="<?php echo url_project();?>images/30.gif"><span class="cattitle"><a href="<?php echo url_for("rbac/addrbac",true);?>"><STRONG><FONT color=#ff6600>添加权限资源</FONT></STRONG></a></span></TD>
		  <TD width=10>&nbsp;</TD>
          <TD width=10>&nbsp;</TD>
          <TD width="266" align=right></TD>
</TR></TBODY></TABLE>
<hr color="#0066CC" align="left" width="400">

<form name="form1" method="post" action="<?php echo url_for("rbac/editrbacpost",true);?>"><table width="720" border="0" align="center" cellpadding="2" cellspacing="1" class="forumline">
  <tr>
    <th width="17%" align="center" nowrap="nowrap" class="thCornerL">&nbsp;</th>
    <th width="71%" height="25" align="center" nowrap="nowrap" class="thCornerL">
  </th>
    <th width="12%" align="center" nowrap="nowrap" class="thCornerR">&nbsp;&nbsp;</th>
  </tr>

  <tr>
  <td align="center" class="row2">权限名称:</td>
  <td align="left" class="row2"><input name="name" type="text" id="name" value="<?php echo $info['name'];?>"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">模型名:</td>
  <td align="left" class="row2"><select name="aclid" id="aclid">
            <?php if(is_array($acllist)):foreach($acllist as $g):?>
          <option value="<?php echo $g['aclid'];?>" <?php if($g['aclid']==$info['aclid']):?>selected<?php endif;?> <?php if(isset($acid[$g['aclid']])):?>style="background:#666"<?php endif;?>><?php echo $g['title'];?>(<?php echo $g['model'];?>)</option>
          <?php endforeach; endif;?>  
  </select></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">模型方法:</td>
  <td align="left" class="row2">    应用该Router目录下面所有子权限继承本类(父类)权限
    <input name="isall" type="checkbox" id="isall" value="1" <?php if($info['isAll']=='Y'):?>checked<?php endif;?>></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">访问设置</td>
  <td align="left" class="row2"><p>
    <input name="level[0]" type="checkbox" id="level[0]" value="1" <?php if($info['level']=='0'):?>checked<?php endif;?>>
    任何人可以 如果这里钩了，下面不用设置了<br>
    <input name="level[1]" type="checkbox" id="level[1]" value="1" <?php if($info['level']&1):?>checked<?php endif;?>>
      
      需要登录
      <br>
      <input name="level[2]" type="checkbox" id="level[2]" value="1" <?php if($info['level']&2):?>checked<?php endif;?>>
      自身修改
      <br>
      <input name="level[3]" type="checkbox" id="level[3]" value="1" <?php if($info['level']&4):?>checked<?php endif;?>>
      需要组的权限集合<br>
      <input name="level[4]" type="checkbox" id="level[4]" value="1" <?php if($info['level']&8):?>checked<?php endif;?>>
      需要角色访问集合
      <br>
      <input name="level[5]" type="checkbox" id="level[5]" value="1" <?php if($info['level']&16):?>checked<?php endif;?>>
      角色被禁止访问
      <br>
      <input name="level[6]" type="checkbox" id="level[6]" value="1" <?php if($info['level']&32):?>checked<?php endif;?>>

      可访问的日期
      <br>
      <input name="level[7]" type="checkbox" id="level[7]" value="1" <?php if($info['level']&64):?>checked<?php endif;?>>
      可访问的周日
      <br>
      <input name="level[8]" type="checkbox" id="level[8]" value="1" <?php if($info['level']&128):?>checked<?php endif;?>>
      可访问的时间
      <br>
      <input name="level[9]" type="checkbox" id="level[9]" value="1" <?php if($info['level']&256):?>checked<?php endif;?>>
      输入密码才能访问 
      <br>
      <input name="level[10]" type="checkbox" id="level[10]" value="1" <?php if($info['level']&512):?>checked<?php endif;?>>
      超级管理使用</p></td> 
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">日期区间</td>
  <td align="left" class="row2">始
    <input name="timestart" type="text" id="timestart" value="<?php echo $info['timestart'];?>" size="12">
    终
    <input name="timeend" type="text" id="timeend" value="<?php echo $info['timeend'];?>" size="12"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">时段</td>
  <td align="left" class="row2">始
    <input name="daystart" type="text" id="daystart" value="<?php echo $info['daystart'];?>" size="3">
终
<input name="dayend" type="text" id="textfield6" value="<?php echo $info['dayend'];?>" size="3"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">周期</td>
  <td align="left" class="row2">始
    <input name="weekstart" type="text" id="textfield7" value="<?php echo $info['weekstart'];?>" size="3">
终
<input name="weekend" type="text" id="textfield8" value="<?php echo $info['weekend'];?>" size="3"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">访问密码</td>
  <td align="left" class="row2"><input name="password" type="text" id="textfield9" value="<?php echo $info['password'];?>"></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">组访问</td>
  <td align="left" class="row2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td>系统已有组</td>
      <td>&nbsp;</td>
      <td>设置可以访问组</td>
    </tr>
      <tr>
        <td rowspan="4"><select name="sourcegroup" size="10" id="sourcegroup">
          <?php if(is_array($group)):foreach($group as $g):?>
          <option value="<?php echo $g['gid'];?>"><?php echo $g['groupname'];?></option>
          <?php endforeach; endif;?>
        </select></td>
        <td height="54">&nbsp;</td>
        <td rowspan="4"><select name="destgroup" size="10" multiple id="destgroup">
          <?php if(is_array($gmap)):foreach($gmap as $g):?>
          <option value="<?php echo $g['gid'];?>"><?php echo $g['groupname'];?></option>
          <?php endforeach; endif;?>        
        </select>
          <input name="groupmap" type="hidden" id="groupmap" value="<?php echo implode(",",json_decode($info['groupmap'],true));?>"></td>
      </tr>
      <tr>
      <td height="19">请添加到右边</td>
      </tr>
    <tr>
      <td><input type="button" name="button" id="button" value="添加&gt;&gt;" onClick="addgroup();"></td>
    </tr>
    <tr>
      <td><input type="button" name="button4" id="button4" value="&lt;&lt;删除" onClick="deletegroup();"></td>
    </tr>
    
  </table></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">角色访问</td>
  <td align="left" class="row2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td>系统已有角色</td>
      <td>&nbsp;</td>
      <td>设置可以访问角色</td>
    </tr> 
    <tr>
      <td rowspan="4"><select name="sourcerole1" size="10" id="sourcerole1">
               <?php if(is_array($role)):foreach($role as $g):?>
          <option value="<?php echo $g['roleid'];?>"><?php echo $g['rolename'];?></option>
          <?php endforeach; endif;?> 
      </select></td>
      <td height="54">请添加到右边</td>
      <td rowspan="4"><select name="destrole1" size="10" id="destrole1">
               <?php if(is_array($rmap)):foreach($rmap as $g):?>
          <option value="<?php echo $g['roleid'];?>"><?php echo $g['rolename'];?></option>
          <?php endforeach; endif;?>    
      </select>
        <input name="rolemap" type="hidden" id="rolemap" value="<?php echo implode(",",json_decode($info['rolemap'],true));?>"></td>
    </tr>
    <tr>
      <td><input type="button" name="button2" id="button2" value="添加&gt;&gt;" onClick="addacrole();"></td>
    </tr>
    <tr>
      <td><input type="button" name="button5" id="button5" value="&lt;&lt;删除" onClick="deleteacrole();"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  <td class="row2" align="center" valign="middle" height="30" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">禁止角色访问</td>
  <td align="center" class="row2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td>系统已有角色</td>
      <td>&nbsp;</td>
      <td>设置禁止访问角色</td>
    </tr> 
    <tr>
      <td rowspan="4"><select name="sourcerole2" size="10" id="sourcerole2">
               <?php if(is_array($role)):foreach($role as $g):?>
          <option value="<?php echo $g['roleid'];?>"><?php echo $g['rolename'];?></option>
          <?php endforeach; endif;?> 
      </select></td>
      <td height="54">请添加到右边</td>
      <td rowspan="4"><select name="destrole2" size="10" id="destrole2">
                    <?php if(is_array($dmap)):foreach($dmap as $g):?>
          <option value="<?php echo $g['roleid'];?>"><?php echo $g['rolename'];?></option>
          <?php endforeach; endif;?>   
      </select>
        <input type="hidden" name="disablerole" id="disablerole" value="<?php echo implode(",",json_decode($info['disablerole'],true));?>"></td>
    </tr>
    <tr>
      <td><input type="button" name="button3" id="button3" value="添加&gt;&gt;" onClick="adddcrole();"></td>
    </tr>
    <tr>
      <td><input type="button" name="button6" id="button6" value="&lt;&lt;删除" onClick="deletedcrole();"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table></td>
  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap">&nbsp;</td>
</tr>
<tr>
  <td align="center" class="row2">&nbsp;</td>
  <td align="center" class="row2"><input name="rbacid" type="hidden" id="rbacid" value="<?php echo $info['rbacid'];?>">    <input type="submit" name="addsuper" id="addsuper" value="修改权限"></td>
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