<?php
class fetionRouter extends controller{
  public function index()
  {
     $fetion = C("fetion"); //创建飞信类   
	 $fetion->setFetion('1371773***4', '*******');  //设置你的飞信用户名和密码
	 $fetion->init() or die("fetion init failure!\n");
	//example 1 给自己发 做监控用
	$fetion->sent_sms('tel:1371773***4', '有个香蕉先生和女朋友约会，走在街上，天气很热，香蕉先生就把衣服脱掉了，之后他的女朋友就摔倒了');

	//example 2 给好友发
	//$fetion->sent_sms('sip:721989459@fetion.com.cn;p=1424', 'u are OK?');

	//example 3 //取得好友列表
	//$friends = $fetion->get_friends_list();
	//var_dump($friends);    
	Return false;
  }
 function show() {

 }
}
?>