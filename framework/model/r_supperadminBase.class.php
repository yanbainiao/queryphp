<?php 
 class r_supperadminBase extends model{ 
   public $tablename='supperadmin';
 public $PRI='supperid';
 public $autoid=true;
 public $fields=array (
  'supperid' => NULL,
  'adminname' => '',
  'adminpwd' => '',
  'ismar' => 'N',
  'linkname' => '',
);
 public $types=array (
  'supperid' => 'int(8)',
  'adminname' => 'varchar(30)',
  'adminpwd' => 'varchar(32)',
  'ismar' => 'enum(\'Y\',\'N\')',
  'linkname' => 'varchar(30)',
);
}
?>