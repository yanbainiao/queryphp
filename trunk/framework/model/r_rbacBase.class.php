<?php 
 class r_rbacBase extends model{ 
   public $tablename='rbac';
 public $PRI='rbacid';
 public $autoid=true;
 public $fields=array (
  'rbacid' => NULL,
  'projectid' => '',
  'aclid' => '',
  'parentid' => '',
  'model' => '',
  'name' => '',
  'method' => '',
  'level' => '',
  'isall' => '',
  'rolemap' => '',
  'groupmap' => '',
  'disablerole' => '',
  'timestart' => '',
  'timeend' => '',
  'daystart' => '',
  'dayend' => '',
  'weekstart' => '',
  'weekend' => '',
  'loginnum' => '',
  'password' => '',
  'objmodel' => '',
  'field' => '',
);
 public $types=array (
  'rbacid' => 'int(8)',
  'projectid' => 'int(8)',
  'aclid' => 'int(8)',
  'parentid' => 'int(8)',
  'model' => 'varchar(30)',
  'name' => 'varchar(30)',
  'method' => 'varchar(60)',
  'level' => 'int(8)',
  'isall' => 'enum(\'Y\',\'N\')',
  'rolemap' => 'text',
  'groupmap' => 'text',
  'disablerole' => 'text',
  'timestart' => 'date',
  'timeend' => 'date',
  'daystart' => 'int(2)',
  'dayend' => 'int(2)',
  'weekstart' => 'tinyint(1)',
  'weekend' => 'tinyint(1)',
  'loginnum' => 'int(8)',
  'password' => 'varchar(32)',
  'objmodel' => 'varchar(30)',
  'field' => 'varchar(30)',
);
}
?>