<?php 
 class r_userBase extends model{ 
   public $tablename='user';
 public $PRI='uid';
 public $autoid=true;
 public $fields=array (
  'uid' => NULL,
  'projectid' => '',
  'username' => '',
  'password' => '',
  'realname' => '',
  'email' => '',
  'age' => '',
  'job' => '',
  'sex' => '',
  'xueli' => '',
  'isaction' => 'Y',
  'ismar' => 'N',
  'rbaccache' => '',
);
 public $types=array (
  'uid' => 'int(8)',
  'projectid' => 'int(8)',
  'username' => 'varchar(30)',
  'password' => 'varchar(32)',
  'realname' => 'varchar(30)',
  'email' => 'varchar(30)',
  'age' => 'int(2)',
  'job' => 'varchar(60)',
  'sex' => 'int(1)',
  'xueli' => 'int(2)',
  'isaction' => 'enum(\'Y\',\'N\')',
  'ismar' => 'enum(\'Y\',\'N\')',
  'rbaccache' => 'text',
);
}
?>