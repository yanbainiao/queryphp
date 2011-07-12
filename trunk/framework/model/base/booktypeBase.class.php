<?php 
 class booktypeBase extends model{ 
   var $tablename='booktype';
 var $PRI='bookid';
 var $autoid=true;
 var $fields=array (
  'bookid' => NULL,
  'supplyid' => '',
  'classname' => '',
  'typeid' => '',
);
 var $types=array (
  'bookid' => 'int(6)',
  'supplyid' => 'int(8)',
  'classname' => 'varchar(40)',
  'typeid' => 'int(6)',
);
}
?>