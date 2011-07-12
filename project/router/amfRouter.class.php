<?php
class amfRouter extends controller{
  public function index()
  {
	 //加载amf插件
	  ob_clean();
	 import('@plugin.amf.sfAmfGateway');
	 //调用amf插件
     $gateway = new sfAmfGateway();
	 //输出内容 $gateway->service();为返回内容
	 //handleRequest 中自动调用 header(SabreAMF_Const::MIMETYPE);
	 //因为我不没有别的内容输出了所以直接输出内容
	 $gateway->handleRequest();exit;
	 Return 'ajax';
  }
 function show() {

 }
}
?>