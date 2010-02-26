<?php 
 class infoBase extends model{ 
   var $tablename='www_info';
 var $PRI='myid';
 var $autoid=true;
 var $fields=array (
  'myid' => NULL,
  'myname' => '',
  'myage' => '',
  'typeid' => '',
);
 var $types=array (
  'myid' => 'int(8)',
  'myname' => 'varchar(30)',
  'myage' => 'int(3)',
  'typeid' => 'int(8)',
);
}
?>