<?php

/*
*pdo链接 多少自己填,主从可以一样
* key 为正则表达式 ^web_开头的表，使用的链接
*/
$config['pdoconn']=array(
  'default'=>array("master"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8")),
             "slaves"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
			                 "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
							 "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"))
			 ),
  '^web_'=>array("master"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "3"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "4"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "5"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
								   "6"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8")),
             "slaves"=>array("0"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
			                 "1"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"),
							 "2"=>array("dsn"=>"mysql:dbname=mallbook;host=localhost;port=3306","username"=>"admin","password"=>"123456","CHARACTER"=>"utf8"))
			 )
);

$config['defaultrouter']='default'; //默认URL路由控制器 Router
$config['defaultindex']='index';//默认URL路由控制器方法    action
$config['html']='.html';//开启.html结尾url
//视图设置,默认为空使用自带php模板
/*
*  php自带模板意思是在模板中使用php代码foreach(): endforeach if(): else: endif这几种标签当然也可以使用php代码
*  未来版本视图将将全部是php标签
*  foreach($row as $key=>$value):
*  在这里放html代码或做类似Smarty赋值
*  endforeach;
*  结束标签 endif;，endwhile; endfor; endforeach; 以及 endswitch;。 
*/
//$config['view']="Smarty"; //可以使用Smarty作为视图
//$config['plugin']=$config["frameworkpath"]."lib";     //插件所在目录，可能会自动搜寻目录
//$config['frameworklib']=array("Smarty"=>$config["frameworkpath"]."lib/Smarty/Smarty.class.php",
//                    "SendMail"=>$config["frameworkpath"]."lib/Mail/SendMail.php");//类所在的文件 这具配置可以另外放一个地方
//配置路由规则login为路由模型 rule为规则 成功后target设置 conditions rule自定义规则对应为:id
$config["routermaps"]['login']=array("rule"=>'/login/:id',
		                                    "target"=>array('controller' => 'auth', 'action' => 'login'),
		                                    "conditions"=>array('id' => '[\d]{1,8}'));
$config["routermaps"]['member']=array("rule"=>'/member/:id',
		                                    "target"=>array('controller' => 'auth', 'action' => 'login'),
		                                    "conditions"=>array('id' => '[\d]{1,8}'));
//每个project可以配置 内核加前文件precore.ini.php  内核加后文件 aftercore.ini.php 其中precore.ini.php文件是在inc.ini.php(也是本文件)后面加的，这样可以冲抵这个文件设置
//
?>