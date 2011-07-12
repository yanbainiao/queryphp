<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>权限管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo url_project();?>images/css.css" rel="stylesheet" type="text/css">
<script>
function hidemsg(a,w,h){
	 if(a==1)
	 {
	   clearTimeout(show_timeid);
	   head.document.getElementById("divProcessing").style.display="none";
	 }else{
		if(!a)
		 {
		  a=2;
		 }
         head.document.getElementById("divProcessing").style.left=10;
		 head.document.getElementById("divProcessing").style.top=10;
		 head.document.getElementById("divProcessing").style.display="";
	   show_timeid=setTimeout("hidemsg(1)",a*1000);
	 }
}
</script>
</head>
<frameset id="sidebar_content" cols="210, *" frameborder="1" border="0" framespacing="0" bordercolor="#1679AA">
    <frame name="leftFrame" id="leftFrame" src="<?php echo url_for("rbac/siteleft");?>" scrolling="no" framespacing="0" frameborder="0" />
    <frame name="rightFrame" src="<?php echo url_for("rbac/right",true);?>" frameborder="0" />
</frameset>
<noframes>
<div id="divProcessing" name=divProcessing style="width:200px;height:30px;position:absolute;display:none">
<table border=0 cellpadding=0 cellspacing=1 bgcolor="#000000" width="100%" height="100%"><tr><td bgcolor=#1679AA><marquee align="middle" direction="right" behavior="ALTERNATE" scrollamount="2" style="font-size:9pt"><font color=#FFFFFF>...正在处理...请等待...</font></marquee></td></tr></table>
</div>
</noframes>
</html>