<?php 
 class gbookBase extends model{ 
   var $tablename='gbook';
 var $PRI='id';
 var $autoid=true;
 var $fields=array (
  'id' => NULL,
  'siteid' => '0',
  'language' => 'cn',
  'title' => '',
  'author' => '',
  'homepage' => '',
  'email' => '',
  'qq' => '',
  'datetime' => '0000-00-00 00:00:00',
  'pic' => '0',
  'ip' => '',
  'content' => '',
  'reptime' => '0000-00-00 00:00:00',
  'repname' => '',
  'reply' => '',
);
 var $types=array (
  'id' => 'int(11)',
  'siteid' => 'int(6) unsigned',
  'language' => 'varchar(10)',
  'title' => 'varchar(100)',
  'author' => 'varchar(100)',
  'homepage' => 'varchar(100)',
  'email' => 'varchar(100)',
  'qq' => 'varchar(100)',
  'datetime' => 'datetime',
  'pic' => 'int(11)',
  'ip' => 'varchar(100)',
  'content' => 'text',
  'reptime' => 'datetime',
  'repname' => 'varchar(30)',
  'reply' => 'text',
);
}
?>