<?php
class loginRouter extends controller{
  function index()
  {
	echo $_GET['id'];
	echo "******login";
  }
  function auth()
  {
	echo $_GET['id'];
	echo "******login";
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

	$supply->getDataBaseName();
    
	echo "===".$supply->Books->Supply->title;

   $supply->save(M("booktype"));

	//print_r(M("booktype")->record);
  }
}
?>