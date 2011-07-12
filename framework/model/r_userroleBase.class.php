<?php 
 class r_userroleBase extends model{ 
   public $tablename='userrole';
 public $PRI='urid';
 public $autoid=true;
 public $fields=array (
  'urid' => NULL,
  'uid' => '',
  'roleid' => '',
  'timestart' => '',
  'timeend' => '',
);
 public $types=array (
  'urid' => 'int(8)',
  'uid' => 'int(8)',
  'roleid' => 'int(8)',
  'timestart' => 'date',
  'timeend' => 'date',
);
}
?>