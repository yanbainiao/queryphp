<?php 
class userModel extends userBase{ 
  var $mapper=array();
 var $maps;
 var $maparray=array();
 
 function login($a) {
 	$user=M("user");
	print_r($a);
	$user->whereusernameANDpassword($a['username'],md5($a['password']))->limit(1)->fetch();
	echo $user->querySQL();
	if(!$user->isEmpty())  
    {  
	  print_r($user->getRecord());
      MY()->setLogin();
	  Return true;
	}else{
	  Return false;
	}
 }
} 
?>