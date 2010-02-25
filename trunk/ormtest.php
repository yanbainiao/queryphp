<?php
//产品环境使用(Product)
//$projectenv="product";
$projectenv="product";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=strlen($_SERVER['SCRIPT_FILENAME'])."projectname"; //根据项目来缓存,所以最好一个网站不要一样
include($config["webprojectpath"]."framework/queryorm.php");

	$supply=M("supply");
	$supply->get(3,4);
	$supply->up();//edit 3
	//M("booktype")->classname="星际解霸2";
    echo "bye<pre>";
   // $supply->copyRecord()->save(M("booktype"));

	//$supply->Books=array("classname"=>"星际解霸5");
	//print_r($supply->save());

	//$supply->where($supply->PRI.">12")->delete();
	//$supply->save();
	$books=M("booktype");
	echo "aaa";
	//$books->get(246)->up(); //取一个值
	$books->classname="开发游戏新行"; //更新字段
	$supply->update($books);  //关联保存
	//$books->where($books->PRI.">3")->delete();
	//M("booktype")->where($books->PRI.">12")->delete();
	//$supply->Books->setclassname("星际争霸9")->save();
    print_r($supply->data);
	$supply->address="北京海淀区";
	$supply->update("address");
	//$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	//$supply->update("mobile,address",array(1100120,"大钟寺"));

		$supply=M("supply");
	$supply->get(3,4);
	$supply->up();//edit 3
	//M("booktype")->classname="星际解霸2";

   // $supply->copyRecord()->save(M("booktype"));

	//$supply->Books=array("classname"=>"星际解霸5");
	//print_r($supply->save());

	//$supply->where($supply->PRI.">12")->delete();
	//$supply->save();
	$books=M("booktype");
	echo "aaa";
	//$books->get(246)->up(); //取一个值
	$books->classname="开发游戏新行"; //更新字段
	$supply->update($books);  //关联保存
	//$books->where($books->PRI.">3")->delete();
	//M("booktype")->where($books->PRI.">12")->delete();
	//$supply->Books->setclassname("星际争霸9")->save();
   // print_r($supply->data);
	$supply->address="北京海淀区";
	$supply->update("address");
	$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	$supply->update("mobile,address",array(13800138000,"上地站"));
  /*
  * update为指定字段更新，不像save什么都更新
  * $supply->update('fields,fields');
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888));
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888),true); //true表示更新到$supply->data
  * $supply->update($Books); //关联更新 $Books是M对像,表示更新到$supply->data
  * $books 为类对象，record将会改为对像的。
  * $supply->update($books,true); 
  * $supply->update('fields,fields',array("aa","bbb"));
  */

	//$supply->Books(array('classname'=>"星星争霸78"))->save();
	//print_r($supply->Books->record);
    //print_r($supply->Books->record);
    $supply->Books=array("classname"=>"星际解霸21");
	$supply->Books=array("classname"=>"星际解霸22");
	$supply->Books=array("0"=>array("classname"=>"星际解霸88"),2=>array("classname"=>"星际解霸98"));
	//print_r($supply->data);
	//$supply->copyRecord();
	print_R($supply);
	$supply->copyRecord()->save();
	print_r($books->record);
	//print_r($supply);
	//print_r(M("booktype")->record);
	$sub="useridANDlanguageORlangLIKEcnpri";
	//$sub="useridAND";
	//$sub="asdfdgdasdLIKE";
	echo "</pre>";
?>