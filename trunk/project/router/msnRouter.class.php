<?php
class msnRouter extends controller{
  public function index()
  {
     $msn = C("MsnFriend");                                            
    $list = $msn->GetList('queryphp@msn.com','******');        
    print_r($list);  
	Return false;
  }
 function show() {

 }
}
?>