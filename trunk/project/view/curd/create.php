<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CURD演示</title>
<style type="text/css">
body {background-color: #ffffff; color: #000000;}
body, td, th, h1, h2 {font-family: sans-serif;}
pre {margin: 0px; font-family: monospace;}
a:link {color: #000099; text-decoration: none; background-color: #ffffff;}
a:hover {text-decoration: underline;}
table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: left;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #ccccff; font-weight: bold; color: #000000;}
.h {background-color: #9999cc; font-weight: bold; color: #000000;}
.v {background-color: #cccccc; color: #000000;}
.vr {background-color: #cccccc; text-align: right; color: #000000;}
img {float: right; border: 0px;}
hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color: #000000;}
</style>
</head>

<body>
目前编辑#:<?php echo $form['bookid']?>
<ul>
<li><a href="<?php echo url_for("curd/create")?>">添加新记录</a></li>
<li><a href="<?php echo url_for("curd/index")?>">数据列表</a></li>
</ul>
<form id="form1" name="form1" method="post" action="<?php echo url_for("curd/createForm",true);?>">
<dt>
<label for="clalssname">类型名</label>
</dt>
<dd>
<input type="text" name="classname" id="classname" value="<?php echo $form['classname'];?>"/>
</dd>
<dt>
<label for="clalssname">类型ID</label>
</dt>
<dd>
<input type="text" name="typeid" id="typeid" value="<?php echo $form['typeid'];?>"/>
</dd>
<dt>
<label for="clalssname"></label>
</dt>
<dd>
<input name="submitform" value="修改" type="submit" />
</dd>
<input name="bookid" type="hidden" value="<?php echo $form['bookid']?>" />
</form>
</body>
</html>
