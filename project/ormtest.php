<?php
//产品环境使用(Product)
//$projectenv="product";
$projectenv="test";
$config["webprojectpath"]=dirname(__FILE__)."/";
$config["webprojectname"]=strlen($_SERVER['SCRIPT_FILENAME'])."projectname"; //根据项目来缓存,所以最好一个网站不要一样
include("../framework/queryorm.php");

 //ORM关联测试
 function ormrelationtest() {
    $books=M("booktype");
    print_r($books->orderby("bookid desc")->find(946,911)->getRecord()); //取得一条记录 
    print_r($books->Supply->getRecord()); //取得关联对像记录
    print_r($books->getRecord()); //取得关联后的结果	
 }  
 //orm关联赋值保存测试
 function ormmappingsave() {
 	$books=M("booktype");
	//取得一个值，设置为编辑状态，也是说焦点，下面关联保存时候用到
	print_r($books->find(911)->edit()->getRecord());
    $books->Supply=array("address"=>"关联保存1","mobile"=>"13715698487");
	print_r($books->maparray);
   // $books->save();
 }
  //orm关联赋值保存测试
 function ormmappingsave2() {
 	$books=M("booktype");
	//取得一个值，设置为编辑状态，也是说焦点，下面关联保存时候用到
	print_r($books->find(911)->edit()->getRecord());
    $books->Supply=array("address"=>"关联保存2","mobile"=>"13715698487");
	$books->Infos=array("myid"=>13,"myname"=>"我叫韩MM","myage"=>"28");
	$books->Infos=array("0"=>array("myid"=>14,"myname"=>"我叫李磊","myage"=>"29"),"1"=>array("myname"=>"我叫乌鸦嘴","myage"=>"3"));
	print_r($books->maparray);
    $books->save();

    $books->Infos(array("myname"=>"关联保存3","myage"=>"87"))->save();
 }
  // echo highlight_file("D:/work/queryphp/project/router/curdRouter.class.php");
   //echo highlight_file(__FILE__);
  // phpinfo();
   echo "<pre>";
    //对像关联测试
	//ormrelationtest();

   //orm关联赋值保存测试
    // ormmappingsave();
    //ormmappingsave2();

class user {
	public $mobile;
	private $address;
	private $adddate;
	function __set($name,$value) {
		$this->{$name}="[".$value."]";
	}
	function show() {
		echo($this->mobile."-".$this->address."-".$this->adddate);
	}
}
$user=new user();
$user->address="aaaaa";
$supply=M("supply"); 
$books=M("booktype"); 
$books->get(911)->edit(); 
//取得id为911的行 并设置为编辑状态 
$info=M("info")->limit(1)->get(1)->edit(); 
//取得info一行记录并设置编辑状态 
//我们先看看已有对象数据

print_r($books->getData());
print_r($info->getData());
//$ss=$supply->Books($books)->Infos($info)->fetch(); 
//echo($supply->querySQL());
//$supply->wheresupplyidDYbookidXY(1,5)->orderby(" desc")->limit(10,20)->fetch();
//echo $supply->querySQL();

$books->selectbooktype("bookid,classname")->selectsupply("address,title")->leftjoin("supply")->joinon("supply.bookid=booktype.bookid")->where('bookid',404)->fetch();

echo $supply->querySQL();
//print_r($supply->getRecord());

//var_export($user->fetchAll(PDO::FETCH_CLASS, 'user'));
//foreach($user as $animals)
 //   {
 //     var_export($animals);
//	  echo($animals->show());
//	}

//关联查询 
//有点类似这样子 
//where("bookid='".$books->bookid."' and typeid='".$infos->infoid."'")->fetch();
//当然这样也行 只要设置了$books,$infos有数据为编辑状态后 
//$supply->Books()->Infos()->fetch(); //是一样的。 


	//$supply=M("supply");
	//$supply->get(3,4);
	//$supply->up();//edit 3
	//M("booktype")->classname="星际解霸2";
   // echo "bye<pre>";
   // $supply->copyRecord()->save(M("booktype"));

	//$supply->Books=array("classname"=>"星际解霸5");
	//print_r($supply->save());

	//$supply->where($supply->PRI.">12")->delete();
	//$supply->save();
	//$books=M("booktype");
	//echo "aaa";
	//$books->get(246)->up(); //取一个值
	//$books->classname="开发游戏新行"; //更新字段
	//$supply->update($books);  //关联保存
	//$books->where($books->PRI.">3")->delete();
	//M("booktype")->where($books->PRI.">12")->delete();
	//$supply->Books->setclassname("星际争霸9")->save();
   // print_r($supply->data);
	//$supply->address="北京海淀区";
	//$supply->update("address");
	//$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	//$supply->update("mobile,address",array(1100120,"大钟寺"));

	//	$supply=M("supply");
	//$supply->get(3,4);
	//$supply->edit();//edit 3
	//M("booktype")->classname="星际解霸2";

   // $supply->copyRecord()->save(M("booktype"));

	//$supply->Books=array("classname"=>"星际解霸5");
	//print_r($supply->save());

	//$supply->where($supply->PRI.">12")->delete();
	//$supply->save();
	//$books=M("booktype");
	//echo "aaa";
	//$books->get(246)->up(); //取一个值
	//$books->classname="开发游戏新行"; //更新字段
	//$supply->update($books);  //关联保存
	//$books->where($books->PRI.">3")->delete();
	//M("booktype")->where($books->PRI.">12")->delete();
	//$supply->Books->setclassname("星际争霸9")->save();
   // print_r($supply->data);
	//$supply->address="北京海淀区";
	//$supply->update("address");
	//$supply->update(array("mobile"=>126666,"address"=>"清上河"));
	//$supply->update("mobile,address",array(13800138000,"上地站"));
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
    //$supply->Books=array("classname"=>"星际解霸21");
	//$supply->Books=array("classname"=>"星际解霸22");
	//$supply->Books=array("0"=>array("classname"=>"星际解霸88"),2=>array("classname"=>"星际解霸98"));
	//print_r($supply->data);
	//$supply->copyRecord();
	//print_R($supply);
	//$supply->copyRecord()->save();
	//print_r($books->record);
	//print_r($supply);
	//print_r(M("booktype")->record);
	//$sub="useridANDlanguageORlangLIKEcnpri";
	//$sub="useridAND";
	//$sub="asdfdgdasdLIKE";
	echo "</pre>";
?>