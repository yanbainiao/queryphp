<?php 
 class r_roleBase extends model{ 
   public $tablename='role';
 public $PRI='roleid';
 public $autoid=true;
 public $fields=array (
  'roleid' => NULL,
  'rolename' => '',
  'gid' => '',
  'ismar' => '',
  'dest' => '',
);
 public $types=array (
  'roleid' => 'int(8)',
  'rolename' => 'varchar(30)',
  'gid' => 'int(8)',
  'ismar' => 'enum(\'Y\',\'N\')',
  'dest' => 'varchar(255)',
);
}
?>