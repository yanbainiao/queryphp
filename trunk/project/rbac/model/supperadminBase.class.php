<?php 
 class supperadminBase extends model{ 
   var $tablename='supperadmin';
 var $PRI='supperid';
 var $autoid=true;
 var $fields=array (
  'supperid' => NULL,
  'adminname' => '',
  'adminpwd' => '',
  'ismar' => '',
  'linkname' => '',
);
 var $types=array (
  'supperid' => 'int(8)',
  'adminname' => 'varchar(30)',
  'adminpwd' => 'varchar(32)',
  'ismar' => 'enum(\'Y\',\'N\')',
  'linkname' => 'varchar(30)',
);
}
?>