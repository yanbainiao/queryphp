<?php 
 class booktypeBase extends model{ 
   public $tablename='booktype';
 public $PRI='bookid';
 public $autoid=true;
 public $fields=array (
  'bookid' => NULL,
  'supplyid' => '',
  'classname' => '',
  'typeid' => '',
);
 public $types=array (
  'bookid' => 'int(6)',
  'supplyid' => 'int(8)',
  'classname' => 'varchar(40)',
  'typeid' => 'int(6)',
);
}
?>