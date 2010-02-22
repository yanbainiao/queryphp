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
	$supply->get(3,4)->up();
	$supply->getDataBaseName();

	echo "===".$supply->Books->Supply->title;
	print_r(M("booktype")->record);	
  }
}
?>