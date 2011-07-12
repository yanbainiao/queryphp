<?php 
class userModel extends userBase{ 
  var $mapper=array();
 var $maps;
 var $maparray=array();
  var $valid=array('reg'=>array(
	       'username'=>array('unique',
							 'request',
	                         'max_leng'=>16,
	                         'cnname',
	                         'min_leng'=>5),
	       'password'=>array('request',
	                         'config'=>array('POST'=>'password_confirg'))
	       ));
 function login($a) {
 	$user=M("user");

	$user->whereusernameANDpassword($a['username'],md5($a['password']))->limit(1)->fetch();
	echo $user->querySQL();
	if(!$user->isEmpty())  
    {  
      MY()->setLogin();
	  Return true;
	}else{
	  Return false;
	}
 }
} 
?>