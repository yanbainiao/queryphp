<?php
 /***
 *mysql扩展类 创建表 数据库用户名一起创建等
 *
 ***/ 
 class mysqlExt{
    public $username;
	public $pwd;
	public $DB;
	public $database;
	public $table;
	public $tableinfo;
	public $effect=false;

    public function setDB($model,$tablename='') {
      if(empty($tablename)) $tablename=$model;
	   if(strpos($model,'.'))
	   {
		 list($fix,$model)=explode(".",$model);
	   }else{
		  $fix=NULL;
	   }
	  $this->DB=getConnect($tablename,$model,$this->conn,$fix);
	  return $this;
    }
	public function setInfo($database,$username='',$pwd='') {        
		if(is_array($database))
		{
			$this->username=$database['username'];
			$this->pwd=$database['pwd'];
			$this->database=$database['database'];
		}else{
			$this->username=$username;
			$this->pwd=$pwd;
			$this->database=$database;
		}
		Return $this;
	}
    public function CreateUser($username,$pwd) {
		$this->username=$username;
		$this->pwd=$pwd;
    	$this->effect=$this->DB['master']->query("CREATE USER '".$username."'@'localhost' IDENTIFIED BY '".$pwd."'");
		$this->DB['master']->query("GRANT USAGE ON * . * TO '".$username."'@'localhost' IDENTIFIED BY '".$pwd."' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0");
		Return $this;
    }
	public function CreateDatabase($database) {
		$res=$this->DB['master']->query("SHOW DATABASES");
		$dataarray=array();
        $dataarray=array_values($res->fetch(PDO::FETCH_ASSOC));		
		if(!in_array($database,$dataarray))
		{
		 $this->database=$database;
		 $this->effect=$this->DB['master']->query("CREATE DATABASE IF NOT EXISTS `".$database."`");
		}else{
		 $this->effect=false;
		}
		Return $this;
	}
	public function ChownDBUser($database,$username) {
		$this->effect=$this->DB['master']->query("GRANT ALL PRIVILEGES ON `".$database."` . * TO '".$username."'@'localhost'");
		Return $this;
	}
	public function CreateDBuser() {
		$this->CreateUser($this->username,$this->pwd);
		if($this->effect){
		 $this->CreateDatabase($this->database);
		 if($this->effect)
		 {
		  $this->ChownDBUser($this->database,$this->username);
		 }
		}
		Return $this;
	}
	public function getCreateTable($table) {
		$this->table=$table;
		$res=$this->DB['master']->query("SHOW CREATE TABLE `".$table."`");
		  $record=$res->fetch(PDO::FETCH_ASSOC);  
		  $this->tableinfo[$this->table]=$record['Create Table'];
		Return $this;
	}
	/***
	*复制表
	*
	***/
	public function copyTable($tablesrc,$tabledst) {
	   $this->getCreateTable($tablesrc);
       $this->tableinfo[$tabledst]=str_replace("CREATE TABLE `".$tablesrc."`","CREATE TABLE `".$tabledst."`",$this->tableinfo[$this->table]);
	   $this->DB['master']->query($this->tableinfo[$tabledst]);
	   Return $this;
	}
	public function fieldTarray()
	{
	   
	}
 }
?>