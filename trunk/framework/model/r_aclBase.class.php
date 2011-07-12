<?php 
 class r_aclBase extends model{ 
   public $tablename='acl';
 public $PRI='aclid';
 public $autoid=true;
 public $fields=array (
  'aclid' => NULL,
  'model' => '',
  'method' => '',
  'title' => '',
  'aclpath' => '',
  'start' => '',
  'end' => '',
  'paclid' => '',
);
 public $types=array (
  'aclid' => 'int(8)',
  'model' => 'varchar(60)',
  'method' => 'varchar(60)',
  'title' => 'varchar(60)',
  'aclpath' => 'varchar(160)',
  'start' => 'date',
  'end' => 'date',
  'paclid' => 'int(8)',
);
}
?>