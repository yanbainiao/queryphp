配置
framework/config/inc.ini.php 里面的数据库链接


访问 test.php 或index.php文件

那么会默认访问 framework/router 
里面的defaultRouter.class.php文件index方法
index.php/default/index 方式

现在你清空defaultRouter.class.php index方法内容


自动生成数据库表模型方法
     	 //第一次使用请加上表名"www_channel"这样就会自动生成channel模型了，
	 //生成在model目录下面一个是channelBase.class.php和channelModel.class.php结尾的两个文件
	 //$channel=M("channel","www_channel");
	 //如果已生成过就不用表名了，只有第一次用或数据库更新了需要刷新才使用表名，切记
	 //使用一次后如果正确的那么生成了两个文件，于是赶紧把$channel=M("channel","www_channel");改为
	 //$channel=M("channel"); 这样就可以了不会再去生成那个Base.class.php文件
	 //如果我把数据库表改了一下添加或删除字段
	 //$channel=M("channel","www_channel");再加上表名再去刷新一下那个Base.class.php文件
	 //然后再改回来$channel=M("channel");
	 //正式发布时候我们model目录下已有所有数据库表模型文件了所以我们不会再使用$channel=M("channel","www_channel");加表名了
	 //有点哆嗦，意思是model下面有文件了就不要加表名了


其它请看document文档

