<?php 
 class r_grouproleBase extends model{ 
   public $tablename='grouprole';
 public $PRI='grid';
 public $autoid=true;
 public $fields=array (
  'grid' => NULL,
  'gid' => '',
  'roleid' => '',
  'jicheng' => 'N',
);
 public $types=array (
  'grid' => 'int(8)',
  'gid' => 'int(8)',
  'roleid' => 'int(8)',
  'jicheng' => 'enum(\'Y\',\'N\')',
);
}
?>