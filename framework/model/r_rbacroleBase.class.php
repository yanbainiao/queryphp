<?php 
 class r_rbacroleBase extends model{ 
   public $tablename='rbacrole';
 public $PRI='aclid';
 public $autoid=true;
 public $fields=array (
  'aclid' => NULL,
  'roleid' => '',
  'rbacid' => '',
);
 public $types=array (
  'aclid' => 'int(8)',
  'roleid' => 'int(8)',
  'rbacid' => 'int(8)',
);
}
?>