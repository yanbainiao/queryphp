<?php 
 class r_groupuserBase extends model{ 
   public $tablename='groupuser';
 public $PRI='guid';
 public $autoid=true;
 public $fields=array (
  'guid' => NULL,
  'gid' => '',
  'uid' => '',
  'adduid' => '',
  'ismar' => 'N',
);
 public $types=array (
  'guid' => 'int(8)',
  'gid' => 'int(8)',
  'uid' => 'int(8)',
  'adduid' => 'int(8)',
  'ismar' => 'enum(\'Y\',\'N\')',
);
}
?>