<?php 
 class userBase extends model{ 
   var $tablename='user';
 var $PRI='uid';
 var $autoid=true;
 var $fields=array (
  'uid' => NULL,
  'username' => '',
  'password' => '',
  'realname' => '',
  'email' => '',
  'isaction' => '',
);
 var $types=array (
  'uid' => 'int(8)',
  'username' => 'varchar(30)',
  'password' => 'varchar(32)',
  'realname' => 'varchar(30)',
  'email' => 'varchar(30)',
  'isaction' => 'enum(\'Y\',\'N\')',
);
}
?>