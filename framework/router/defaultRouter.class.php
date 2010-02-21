<?php
class defaultRouter extends controller{
  function index()
  {
    echo "hello world!";
	$this->assign("ssss","aa");
	$this->hhh="88";
	/*
	$booktype=M("booktype");
	echo $booktype->fetch('FETCH_OBJ')->up()->bookid;
	print_r($booktype->data);
	echo $booktype->classname;
	*/
	J($this,"saybye");
  }
  function saybye()
  {
    echo "bye";
	//$supply=M("supply");
	//print_r($supply->getAll());
  }
}
?>