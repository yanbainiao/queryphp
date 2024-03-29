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
<ul>
<li><a href="<?php echo url_for("curd/create")?>">添加新记录</a></li>
<li><a href="<?php echo url_for("curd/index")?>">数据列表</a></li>
</ul>
<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>序号</td>
    <td>类名</td>
    <td>typeid</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php foreach($list as $key=>$value):?>
  <tr>
    <td><?php echo $value['bookid'];?></td>
    <td><?php echo $value['classname'];?></td>
    <td><?php echo $value['typeid'];?></td>
    <td><a href="<?php echo url_for("curd/show/id/".$value['bookid'])?>">显示</a></td>
    <td><a href="<?php echo url_for("curd/edit/id/".$value['bookid'],true)?>">编辑</a></td>
    <td><a href="<?php echo url_for("curd/delete/id/".$value['bookid'],true)?>">删除</a></td>
  </tr>
  <?php endforeach;?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<span>
<?php
echo $pager->getWholeBar(url_for("curd/index/page/:page"));
?>
</span>
</body>
</html>
