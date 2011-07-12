<?php 
 class r_groupBase extends model{ 
   public $tablename='group';
 public $PRI='gid';
 public $autoid=true;
 public $fields=array (
  'gid' => NULL,
  'pid' => '',
  'groupname' => '',
  'uid' => '',
  'dest' => '',
);
 public $types=array (
  'gid' => 'int(8)',
  'pid' => 'int(8)',
  'groupname' => 'varchar(30)',
  'uid' => 'int(8)',
  'dest' => 'varchar(256)',
);
}
?>