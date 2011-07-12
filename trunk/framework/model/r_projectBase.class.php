<?php 
 class r_projectBase extends model{ 
   public $tablename='project';
 public $PRI='projectid';
 public $autoid=true;
 public $fields=array (
  'projectid' => NULL,
  'projectname' => '',
  'loginname' => '',
  'loginpwd' => '',
  'province' => '',
  'business' => '',
  'linkname' => '',
  'job_bm' => '',
  'job_gw' => '',
  'iphone1' => '',
  'iphone2' => '',
  'iphone3' => '',
  'mobile' => '',
  'jinjiipone' => '',
  'jinjilinks' => '',
  'email' => '',
  'regaddress' => '',
  'zipnum' => '',
  'price' => '',
  'servericname' => '',
  'servicetype' => '',
  'dest' => '',
  'isaction' => 'Y',
);
 public $types=array (
  'projectid' => 'int(8)',
  'projectname' => 'varchar(30)',
  'loginname' => 'varchar(40)',
  'loginpwd' => 'varchar(40)',
  'province' => 'int(4)',
  'business' => 'int(4)',
  'linkname' => 'varchar(60)',
  'job_bm' => 'varchar(60)',
  'job_gw' => 'varchar(60)',
  'iphone1' => 'varchar(6)',
  'iphone2' => 'bigint(10)',
  'iphone3' => 'varchar(5)',
  'mobile' => 'varchar(14)',
  'jinjiipone' => 'varchar(120)',
  'jinjilinks' => 'varchar(120)',
  'email' => 'varchar(40)',
  'regaddress' => 'varchar(80)',
  'zipnum' => 'int(6)',
  'price' => 'decimal(8,2)',
  'servericname' => 'varchar(30)',
  'servicetype' => 'int(3)',
  'dest' => 'varchar(256)',
  'isaction' => 'enum(\'Y\',\'N\')',
);
}
?>