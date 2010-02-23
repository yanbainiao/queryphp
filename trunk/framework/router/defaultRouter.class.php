<?php
class defaultRouter extends controller{
  function index()
  {
    echo "hello world!";
	$this->assign("ssss","aa");
	$this->hhh="88";
	
	$booktype=M("booktype");
	/*
	echo $booktype->fetch('FETCH_OBJ')->up()->bookid;
	print_r($booktype->data);
	echo $booktype->classname;
	*/
	J("saybye",array("bbee"=>6666,"ccdd"=>888));
  }
  function saybye()
  {
	$a=func_get_args();
	print_r($a);
    echo "bye";
	$supply=M("supply");
	$supply->get(3,4);
	//print_r($supply->record);
	//echo $supply->title;
	$supply->up();
	print_r($supply->getData());
	$supply->up();
	print_r($supply->getData());

	//$supply->getDataBaseName();
    
	//echo "===".$supply->Books->Supply->title;
	$supply->get(3,4);
	$supply->up();//edit 3
	M("booktype")->classname="星际解霸2";

    $supply->save(M("booktype"));
    $supply->Books=array("classname"=>"星际解霸4");
	$supply->save();
	//print_r(M("booktype")->record);
  }
}
?>