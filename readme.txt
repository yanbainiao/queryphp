使用方法
构造一个数据库表模型
$beian=M('beian');

自动填充aaa bbb字段 $_POST中也要有这两个字段
//$beian->autoField(array("aaa","bbbb"));
$data中填充
$beian->autoField($data,array("aaa","bbbb"));
取两个主键值，排序为升序
//print_r($beian->get(53,54,'asc'));

赋值给字段。
$beian->userid=2;
$beian->language=1;


打印已经赋值字段
//print_r($beian->data);

保存，再显示刚才插入的ID
//echo $beian->save()->pkid();

设置主键然后删除
//echo($beian->pkid(69)->delete());

取得表的行数
//echo $beian->Totalnum();

select显示两个字段，Arraylist为数组
//print_r($beian->getAll("userid,language")->record); //改为record了

查询两个userid和language为1和5，fetch为取值
print_r($beian->whereUseridAndLanguage('1','5')->fetch()->record);

取得两个主键，显示三个字段，升序
//print_r($beian->get('confid,userid,language',53,54,'asc')->record);

输出主键值
//echo $beian->confid;

更新某个表的字段累加1
$beian->colupdate('tplid');

可以在model目录下，*****Model.class.php文件里面添加方法。

生成模型后可以马上可以使用
print_r($booktype=M("booktype")->getAll());