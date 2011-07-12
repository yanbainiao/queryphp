<?php 
 class r_aclmethodBase extends model{ 
   public $tablename='aclmethod';
 public $PRI='caclid';
 public $autoid=true;
 public $fields=array (
  'caclid' => NULL,
  'aclid' => '',
  'title' => '',
  'method' => '',
);
 public $types=array (
  'caclid' => 'int(8)',
  'aclid' => 'int(8)',
  'title' => 'varchar(60)',
  'method' => 'varchar(60)',
);
}
?>