<?php
//产品环境使用(Product)
//$projectenv="product";
$projectenv="product";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=strlen($_SERVER['SCRIPT_FILENAME'])."projectname"; //根据项目来缓存,所以最好一个网站不要一样
include("../framework/queryorm.php");

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
    print_r($supply->data);
	$supply->address="北京海淀区";
	$supply->update("address");
	//$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	//$supply->update("mobile,address",array(1100120,"大钟寺"));
?>