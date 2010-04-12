<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="history" content="">
<meta name="author" content="Verdana Core, phpdoc.net Inc.">
<title>ajax测试</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script language="javascript">
function ajax(obj) {
	jQuery("#"+obj).html("请稍候........");
	jQuery.getJSON('<?php echo url_for("default/ajaxtest",true);?>', function(data) {
        jQuery("#"+obj).html(data['REQUEST_URI']);
   });
}
</script>
</head>

<body>
<div id="showid"></div>
<button onclick="ajax('showid')">测试ajax</button>
</body>

</html>