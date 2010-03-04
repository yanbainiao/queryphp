testproject是项目目录
本目录是www根
每个项目都有自己的 
 config 配置文件目录 precore.ini.php  aftercore.ini.php
 model  本项目模型文件
 router 本项目路由文件
 view   视图文件可以按路由目录存放
 class  本项目使用的普通类文件
 lib    存放类库文件或插件
等目录

ormtest.php是独立使用ORM数据库对像设计的
可以把本ORM类在你的现有项目中使用
ORM使用方式见文档

去除index.php测试
http://www.app.com/queryphp/project/index.php/default/index
配置后希望可以变成这样子
http://www.app.com/queryphp/project/default/index.html
记得在inc.ini.php文件里面加多一行
 $config['html']='.html'; 或把前面那个//去掉，这样就可以了
虚拟主机配置测试，AllowOverride FileInfo 将会使用.htaccess配置
<VirtualHost *:80>
    <Directory "D:/work">
        Order allow,deny
        Allow from all
	AllowOverride FileInfo 
    </Directory> 
  DocumentRoot "D:/work"
  ServerName "www.app.com"
</VirtualHost>


.htaccess文件 放在project目录下面 就是每个项目目录下面，这样就会访问同级目录
index.php文件

  RewriteEngine On

  # uncomment the following line, if you are having trouble
  # getting no_script_name to work
  #RewriteBase /

  # we skip all files with .something
  #RewriteCond %{REQUEST_URI} \..+$
  #RewriteCond %{REQUEST_URI} !\.html$
  #RewriteRule .* - [L]

  # we check if the .html version is here (caching)
  RewriteRule ^$ index.html [QSA]
  RewriteRule ^([^.]+)$ $1.html [QSA]
  RewriteCond %{REQUEST_FILENAME} !-f

  # no, so we redirect to our front web controller
  RewriteRule ^(.*)$ index.php [QSA,L]