<?php 
 class r_rbacgroupBase extends model{ 
   public $tablename='rbacgroup';
 public $PRI='rgid';
 public $autoid=true;
 public $fields=array (
  'rgid' => NULL,
  'rbacid' => '',
  'gid' => '',
);
 public $types=array (
  'rgid' => 'int(8)',
  'rbacid' => 'int(8)',
  'gid' => 'int(8)',
);
}
?>