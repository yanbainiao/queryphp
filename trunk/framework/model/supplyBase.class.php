<?php 
 class supplyBase extends model{ 
 
 var $tablename='supply';
 var $PRI='supplyid';
 var $autoid=true;
 var $fields=array (
  'supplyid' => NULL,
  'typeid' => '',
  'userid' => '',
  'total' => '',
  'isview' => 'Y',
  'author' => '',
  'linkname' => '',
  'phone' => '',
  'ispic' => 'N',
  'mobile' => '',
  'address' => '',
  'email' => '',
  'msn' => '',
  'qq' => '',
  'title' => '',
  'dest' => '',
  'picurl' => '',
  'srcpri' => '',
  'outpri' => '',
  'per' => '',
  'press' => '',
  'adddate' => '',
  'content' => '',
);
 var $types=array (
  'supplyid' => 'int(8)',
  'typeid' => 'int(6)',
  'userid' => 'int(8)',
  'total' => 'int(6)',
  'isview' => 'enum(\'Y\',\'N\')',
  'author' => 'varchar(20)',
  'linkname' => 'varchar(30)',
  'phone' => 'varchar(30)',
  'ispic' => 'enum(\'Y\',\'N\')',
  'mobile' => 'varchar(30)',
  'address' => 'varchar(120)',
  'email' => 'varchar(30)',
  'msn' => 'varchar(40)',
  'qq' => 'varchar(20)',
  'title' => 'varchar(120)',
  'dest' => 'varchar(255)',
  'picurl' => 'varchar(255)',
  'srcpri' => 'decimal(3,2)',
  'outpri' => 'decimal(3,2)',
  'per' => 'decimal(1,1)',
  'press' => 'varchar(120)',
  'adddate' => 'date',
  'content' => 'text',
);} 
 ?>