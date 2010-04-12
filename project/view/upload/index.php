<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="history" content="">
<meta name="author" content="Verdana Core, phpdoc.net Inc.">
<title>上传文件测试</title>
</head>

<body>
<FORM METHOD=POST  enctype="multipart/form-data" ACTION="<?php echo url_for("upload/webimages",true);?>">
<input type="file" name="upload" id="upload" onchange="preview()"; />
<br /><br />
<INPUT TYPE="submit" value="上传图片">
</FORM>
</body>

</html>