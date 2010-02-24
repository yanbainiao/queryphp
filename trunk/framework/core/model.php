<?php

class Model
{
   var $tablename;
   var $fields=array();
   var $types=array();
   var $PRI;
   var $data;
   var $autoid=false;
   var $sql=array();
   var $string;
   var $DB=array();
   var $res=null;
   var $record=array();
   var $conn=0;
   var $objpoint=0;
   var $modelname;
   var $databasename;
   var $ismapper;
   function __construct() {
	   $this->modelname=substr(get_class($this),0,-5);
	   $this->DB=getConnect($this->tablename,$this->modelname,$this->conn);
	   if(is_array($this->DB))
	   {
	     
	   }
	   return $this;
   }
  function getMate()
  {
    $this->string="DESCRIBE ".$this->tablename;	
	try{	
	    $this->res=$this->DB['master']->query($this->string);
        $result = $this->res->fetchAll(PDO::FETCH_ASSOC);  
	} catch (PDOException $e) 
        {
           echo $e->getMessage();
        }

	return $result;
  }

  function analyseTable()
  {
     $mate=$this->getMate();
	 if(is_array($mate))
	 {
	   foreach($mate as $key=>$value)
	   {
		  $value['Field']=strtolower($value['Field']);
	      if($value['Key']=='PRI')
		   {
			  $this->PRI=$value['Field'];
	         if($value['Extra']=='auto_increment')
			  $this->autoid=true;
		   }
		  $this->fields[$value['Field']]=$value['Default'];
		  $this->types[$value['Field']]=$value['Type'];
	   }
	 }
  }
  function autoField()
  {
    $numargs = func_num_args();
    if ($numargs==1&&is_array(func_get_arg(0))) {
        $prefields=func_get_arg(0);
		foreach($prefields as $k=>$v)
		{
         if(isset($this->fields[$v]))
		  {
		     $this->data[$v]=$_POST[$v];
		  }
		}
    }
	if(is_array(func_get_arg(0)))
	{
		$arg_list = func_get_args();
		$arg0=func_get_arg(0);
		for ($i = 1; $i < $numargs; $i++) {
		  if(isset($this->fields[$arg_list[$i]]))
		  {
		     $this->data[$v]=$arg0[$arg_list[$i]];
		  }
		}
	}
	return $this;
  }

  function __get($name)
  {
    if(isset($this->data[strtolower($name)]))
	{
	  return $this->data[strtolower($name)];
	}elseif(isset($this->mapper[$name])){	  
	  if(method_exists($this,$this->mapper[$name]['map'])) {
		  echo $this->mapper[$name]['map'];
		call_user_func(array($this,$this->mapper[$name]['map']),$name);
	  }
	  $this->ismapper=true;
	  return $this->maps[$mapper]=M($this->mapper[$name]['TargetModel']);
	}else{ 
	  if(count($this->record)>0)
	  {
		$this->objpoint=0;
	    $this->up(0);
        if(isset($this->data[strtolower($name)]))
			return $this->data[strtolower($name)];
	  }
	  return null;
	}
  } 
  function __set($name,$value)
  {
    if(isset($this->types[strtolower($name)]))
	{
	  return $this->data[strtolower($name)]=$value;
	}elseif(isset($this->mapper[$name])){
        $this->ismapper=true;
		$this->promaparray($name,$value);       
		return $this;
	 }else{
	  return null;
	}
  }

  function __isset($name)
  {
    return isset($this->data[strtolower($name)]);
  }
  private function __unset($name)
  {
    unset($this->data[strtolower($name)]);
  }

  function getArray()
  {
	$arg_list = func_get_args();
	$arg_list=$arg_list[0];
	$numargs=count($arg_list);
	$pkey='';
	$fields="*";
	$order='';
	$i=0;
	if(strtoupper($arg_list[$i])=="FETCH_OBJ")
    {
      $returnobj=PDO::FETCH_OBJ;
	  $i++;
	}else{
	  $returnobj=PDO::FETCH_ASSOC;
	}
	if(!is_numeric($arg_list[$i]))
	{
	  if($arg_list[$i]!='')
	  {
		  $fields=$arg_list[$i];
		  $i=1;
	  }
	}
    for (; $i < $numargs; $i++) {
	   if(strtoupper($arg_list[$i])=="DESC"||strtoupper($arg_list[$i])=="ASC")
		{
		  $order=" order by ".$this->PRI." ".strtoupper($arg_list[$i]);
		}else if(strtoupper($arg_list[$i])=="FETCH_OBJ"){
		  $returnobj=PDO::FETCH_OBJ;
		}else
		 $pkey.=intval($arg_list[$i]).",";
	 }
	if($pkey!='')
		$pkey=substr($pkey,0,-1);
	if($pkey=='')
		$pkey=1;
	$pkey=$this->PRI." IN (".$pkey.")";

    if($this->sql['fields']!='')
	{
	  $fields=$this->sql['fields'];
	}

    $this->string="select ".$fields." from ".$this->tablename." where ".$pkey.$order;	
	try{
		$res=$this->DB['slaves']->query($this->string);	
		$this->record=$res->fetchAll($returnobj); 
		$this->sql=array();
		return $this;
	}catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
  }

  function getAllArray()
  {
	$arg_list = func_get_args();
	$arg_list=$arg_list[0];
	$numargs=count($arg_list);
	$pkey='';
	$fields="*";
    $returnobj=PDO::FETCH_ASSOC;   
    for ($i=0; $i < $numargs; $i++) {
	   if(strtoupper($arg_list[$i])=="DESC"||strtoupper($arg_list[$i])=="ASC")
		{
		  if($this->sql['orderby']=='')
		    $this->sql['orderby']=" order by ".$this->PRI." ".strtoupper($arg_list[$i]);
		}else if(strtoupper($arg_list[$i])=="FETCH_OBJ"){
		  $returnobj=PDO::FETCH_OBJ;
		}else if(is_numeric($arg_list[$i])){
		  $this->where($this->PRI."='".$arg_list[$i]."'");
		}else
		 $fields=$arg_list[$i];
	 }

   if($this->sql['where']=='')
		$this->sql['where']=" where 1 ";
    if($this->sql['fields']!='')
	{
	  $fields=$this->sql['fields'];
	}
    $this->string="select ".$fields." from ".$this->tablename." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$res=$this->DB['slaves']->query($this->string);	
		$this->record=$res->fetchAll($returnobj); 
		$this->sql=array();
		return $this;
	}catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
  }
  function pkidv()
  {
    if(strtolower(substr($this->types[$this->PRI],0,3))=='int')
	{
	   return intval($this->data[$this->PRI]);
	}
  }
  function newRecord()
  {
    if($this->autoid) unset($this->data[$this->PRI]);
	return $this;
  }
  function copyRecord()
  {
    if($this->autoid) unset($this->data[$this->PRI]);
	return $this;
  }
  /*
  插入前操作
  */
  function updatemaper()
  {
	foreach($this->maparray as $m=>$v)
	{	  
	  if(count($v)>0)
	  { //避免重复掉交
		  $mname=$this->mapper[$m]['TargetModel'];
		  $tm=M($mname);
		  foreach($v as $key=>$value)
		  {
			  $tm->setData($value);
			  $tm->save();
			  $this->maparray[$m][$key][$tm->PRI]=$tm->pkid();
			  if($tm->PRI==$this->mapper[$m]['targetFiled']&&isset($this->mapper[$m]['localFiled']))
			  {
				 $this->data[$this->mapper[$m]['localFiled']]=$tm->pkid();
			  }
			  if(M($mname)->PRI==$this->mapper[$m]['targetFiled2']&&isset($this->mapper[$m]['localFiled2']))
			  {
				 $this->data[$this->mapper[$m]['localFiled2']]=$tm->pkid();
			  }
			  if(M($mname)->PRI==$this->mapper[$m]['targetFiled3']&&isset($this->mapper[$m]['localFiled3']))
			  {
				 $this->data[$this->mapper[$m]['localFiled3']]=$tm->pkid();
			  }
		  }
	  }
	}
	return $this;
  }
  /*
  插入后操作
  */
  function updatemaperafter()
  {
    foreach($this->maparray as $m=>$v)
	{
	  foreach($v as $key=>$value)
		{
		  $mname=$this->mapper[$m]['TargetModel'];
		  M($mname)->setData($value);
		  if($this->PRI==$this->mapper[$m]['localFiled']&&isset($this->mapper[$m]['targetFiled']))
		  {
			 M($mname)->data[$this->mapper[$m]['targetFiled']]=$this->pkid();
		  }
		  if($this->PRI==$this->mapper[$m]['localFiled2']&&isset($this->mapper[$m]['targetFiled2']))
		  {
			 M($mname)->data[$this->mapper[$m]['targetFiled2']]=$this->pkid();
		  }
		  if($this->PRI==$this->mapper[$m]['localFiled3']&&isset($this->mapper[$m]['targetFiled3']))
		  {
			 M($mname)->data[$this->mapper[$m]['targetFiled3']]=$this->pkid();
		  }
		  M($mname)->save();
	   }
	}
	$this->maparray=array();//恢复
	$this->ismapper=false;
	unset($this->ismapper);
    return $this;	
  }
  /*
  * update为指定字段更新，不像save什么都更新
  * $supply->update('fields,fields');
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888));
  * $supply->update(array('fields'=>"aaabbb","fields2"=>8888),true); //true表示更新到$supply->data
  * $supply->update($Books); //关联更新 $Books是M对像,表示更新到$supply->data
  * $books 为类对象，record将会改为对像的。
  * $supply->update($books,true); 
  * $supply->update('fields,fields',array("aa","bbb"));
  */
  function update()
  {
     $arglist=func_get_args();
	 $argnum=func_num_args();
	 if($argnum==0){
	   return $this->save();
	 }	 
     if(!is_array($arglist[0])&&!is_object($arglist[0]))
	 {
	   $filedarray=explode(",",$arglist[0]);
	   if(is_array($arglist[1]))
	   {//有数组情况
	      $sql="";
		  $i=0;
		  foreach($arglist[1] as $key=>$value)
		  {
		     if(is_numeric($key))
			 {
			   if(isset($filedarray[$i]))
			     $sql.=$filedarray[$i]."='".$value."',";
			 }else{
			  $sql.=$key."='".$value."',";
			 }
			 $i++;
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if($this->sql['where']=='')
		    {
			   $this->where($this->PRI."='".$this->data[$this->PRI]."'");
			}
			$this->string.=" ".$this->sql['where'].$this->sql['limit'];
			$this->sql=array();
			$this->effactrow=$this->DB['master']->exec($this->string);
		   }
		  if(!isset($arglist[2]))
		  { 
		     $this->setData($arglist[1]);
		  }
	   }elseif($arglist[1]==''){ //从data中取值
	      $sql="";
		  foreach($filedarray as $value)
		  {
			 $sql.=$value."='".$this->data[$value]."',";
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if($this->sql['where']=='')
		    {
			   $this->where($this->PRI."='".$this->data[$this->PRI]."'");
			}
			$this->string.=" ".$this->sql['where'].$this->sql['limit'];
			$this->sql=array();
			$this->effactrow=$this->DB['master']->exec($this->string);
		   }			
	   }
	 }elseif(is_array($arglist[0])){ //数组更新
	     $sql="";
		  foreach($arglist[0] as $key=>$value)
		  {
			$sql.=$key."='".$value."',";
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if($this->sql['where']=='')
		    {
			   $this->where($this->PRI."='".$this->data[$this->PRI]."'");
			}
			$this->string.=" ".$this->sql['where'].$this->sql['limit'];
			$this->sql=array();
			$this->effactrow=$this->DB['master']->exec($this->string);
			if(!isset($arglist[1]))
			  {  
				 $this->setData($arglist[0]);
			  }
		   }
	 }elseif(is_object($arglist[0]))
	 {
	    $objectname=get_class($arglist[0]);
		$objectname=substr($objectname,0,-5);
		$mapper='';
		//关联更新
		if(count($this->mapper)>0&&$objectname!='')
		{
		  foreach($this->mapper as $k=>$v)
		  {
		    if($v['TargetModel']==$objectname)
			{
			  $mapper=$k;
			  break;
			}
		  }
          if($mapper!='')
		  {
		     $this->setDataToMapper($mapper);
			 M($objectname)->save();
			 //加入关联更新
			 $localfields=$this->setMapperToData($mapper)->getMapperlocalFields($mapper);
			 if($localfields!='') $this->update($localfields);
		  }
		}
		//if(in_array())
		if($mapper=='')		
		{
          $arrays=get_object_vars($arglist[0]);
	      $sql="";
		  foreach($arrays as $key=>$value)
		  {
			$sql.=$key."='".$value."',";
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if($this->sql['where']=='')
		    {
			   $this->where($this->PRI."='".$this->data[$this->PRI]."'");
			}
			$this->string.=" ".$this->sql['where'].$this->sql['limit'];
			$this->sql=array();
			$this->effactrow=$this->DB['master']->exec($this->string);
		   }
		  if(!isset($arglist[1]))
		  {  //更新当然record
		     $this->setData($arrays);
		  }
		}
	 }
	 return $this;
  }
  public function getMappertargetFields($mapper)
  {
    $fileds='';
	if(isset($this->mapper[$mapper]['targetFiled'])) $fileds=$this->mapper[$mapper]['targetFiled'].",";
	if(isset($this->mapper[$mapper]['targetFiled2'])) $fileds.=$this->mapper[$mapper]['targetFiled2'].",";
	if(isset($this->mapper[$mapper]['targetFiled3'])) $fileds.=$this->mapper[$mapper]['targetFiled3'].",";
	if($fileds!='')
    $fileds=substr($fileds,0,-1);
	return $fileds;
  }
  public function getMapperlocalFields($mapper)
  {
    $fileds='';
	if(isset($this->mapper[$mapper]['localFiled'])) $fileds=$this->mapper[$mapper]['localFiled'].",";
	if(isset($this->mapper[$mapper]['localFiled2'])) $fileds.=$this->mapper[$mapper]['localFiled2'].",";
	if(isset($this->mapper[$mapper]['localFiled3'])) $fileds.=$this->mapper[$mapper]['localFiled3'].",";
	if($fileds!='')
    $fileds=substr($fileds,0,-1);
	return $fileds;
  }
  /*
  *关联mapper更新,本模块更新到关联mapper
  */
  public function setMapperToData($mapper,$args=array(),$PRI=false)
  {
 	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	 if(isset($this->mapper[$mapper]['localFiled']))
	  {
		if($this->PRI==$this->mapper[$mapper]['localFiled'])
		{
		 if($PRI)
		   $this->setData(array($this->mapper[$mapper]['localFiled']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled']]));		   
		}else{
		   $this->setData(array($this->mapper[$mapper]['localFiled']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled']]));	
		}
	  }
	 if(isset($this->mapper[$mapper]['localFiled2']))
	  {
		if($this->PRI==$this->mapper[$mapper]['localFiled2'])
		{
		 if($PRI)
		   $this->setData(array($this->mapper[$mapper]['localFiled2']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled2']]));	   
		}else{
		   $this->setData(array($this->mapper[$mapper]['localFiled2']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled2']]));
		}
	  }
	 if(isset($this->mapper[$mapper]['localFiled3']))
	  {
		if($this->PRI==$this->mapper[$mapper]['localFiled3'])
		{
		 if($PRI)
		   $this->setData(array($this->mapper[$mapper]['localFiled3']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled3']]));   
		}else{
		   $this->setData(array($this->mapper[$mapper]['localFiled3']=>$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled3']]));
		}
	  }
	 if(!Empty($args))
	   $this->setData($args);
	 return $this;     
  }
  function save($id=null)
  {
	 $pkey='';
	 $mapper='';
	// $saveafter=false;
	 if($id=='add'||$id=='new')
	 {
	   //处理不是自动增长，但是唯一的字段
	 }else if(is_numeric($id))
	 {
	   $pkey=$this->PRI."='".intval($id)."'";
	   unset($this->data[$this->PRI]);	
	 }else if($id=='all')
	 {
	    $pkey='1';
		unset($this->data[$this->PRI]);
	 }elseif(is_array($id)){
	    $this->setData($id);
		if(isset($this->data[$this->PRI])) $pkey=$this->PRI."='".$this->pkidv()."'";
	 }elseif(is_object($id)){
		 if($id->modelname!=$this->modelname&&count($this->mapper)>0)
	     {
			foreach($this->mapper as $k=>$v)
			{
			  if($id->modelname==$v['TargetModel'])
			  {
			    $mapper=$k;
				$prearray='';
				$this->maps[$mapper]=M($v['TargetModel']);
				if(isset($this->data[$v['localFiled']])&&$this->data[$v['localFiled']]!='')
				{
				   if(M($v['TargetModel'])->PRI==$v['targetFiled']&&$this->data[$v['localFiled']]==0)
				   {
				      $prearray=$v['targetFiled'];
				   }else{
					   M($v['TargetModel'])->$v['targetFiled']=$this->data[$v['localFiled']];
					   $saveafter=true;
				   }
				}
				if(isset($v['localFiled2'])&&isset($this->data[$v['localFiled2']])&&$this->data[$v['localFiled2']]!='')
				{
				   if(M($v['TargetModel'])->PRI==$v['targetFiled2']&&$this->data[$v['localFiled2']]==0)
				   {
				      $prearray=$v['targetFiled2'];
				   }else{
					   M($v['TargetModel'])->$v['targetFiled2']=$this->data[$v['localFiled2']];	
					   $saveafter=true;
				   }				   	
				}
				if(isset($v['localFiled3'])&&isset($this->data[$v['localFiled3']])&&$this->data[$v['localFiled3']]!='')
				{
				   if(M($v['TargetModel'])->PRI==$v['targetFiled3']&&$this->data[$v['localFiled3']]==0)
				   {
				      $prearray=$v['targetFiled3'];
				   }else{
					   M($v['TargetModel'])->$v['targetFiled3']=$this->data[$v['localFiled3']];	
					   $saveafter=true;
				   }
				}
				if($saveafter)
				{
				  M($v['TargetModel'])->save();
                  if($prearray!='')
				   $this->data[$prearray]=M($v['TargetModel'])->pkid();
				  unset($saveafter);
				}
			    break;
			  }
			}
	     }
	 }elseif(is_numeric($this->data[$this->PRI]))
	 {
	   if($this->data[$this->PRI]==0) unset($this->data[$this->PRI]);
	   else $pkey=$this->PRI."='".$this->pkidv()."'";
	   //unset($this->data[$this->PRI]);
	 }
     if($this->ismapper&&count($this->maparray)>0)
	 {
	    $this->updatemaper();
	 }
	 if($pkey=='')
	  {
		//如果是自动增长，那么不用赋值，但要更改id使用update就可以了
	    if($this->autoid) unset($this->data[$this->PRI]);
		foreach($this->fields as $k=>$v)
		{
		  if($v!=''&&!isset($this->data[$k]))
		  {
		     if($this->types[$k]=='date')
			 {
			   $this->data[$k]=date("Y-m-d");
			 }else if($this->types[$k]=='datetime')
			 {
			   $this->data[$k]=date("Y-m-d H:i:s");
			 }else{
				 $this->data[$k]=$v;
			 }
		  }
		  	if($this->types[$k]=='date'&&!isset($this->data[$k]))
			 {
			   $this->data[$k]=date("Y-m-d");
			 }else if($this->types[$k]=='datetime')
			 {
			   $this->data[$k]=date("Y-m-d H:i:s");
			 }
		}    
		$this->string="INSERT INTO `".$this->tablename."` (";
		$i=0;
		$temp='';
		foreach($this->data as $key=>$value)
		{
		  if($i==0)
		  {
			$this->string.="`".$key."`";
			$temp="'".$value."'";
		  }else
		  {
			$this->string.=",`".$key."`";
			$temp.=",'".$value."'";
		  }
		  $i++;
		}	
		$this->string.=") VALUES(".$temp.")";
		try{
		 $this->DB['master']->exec($this->string);
		 $this->sql=array();
	    }catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
        if(isset($this->types[$this->PRI]))
		{
		  $this->data[$this->PRI]=$this->DB['master']->lastInsertId();
		}
	  }else{
	        $this->string="UPDATE ".$this->tablename." set ";
			$i=0;
			foreach($this->data as $key=>$value)
			{
			  if($i==0)
			  {
				$this->string.=$key."='".$value."'";	
			  }else
			  {
				$this->string.=",".$key."='".$value."'";	
			  }
			  $i++;
			}
			if($this->sql['where']!='')
			 {
			   $pkey.=" and ".substr($this->sql['where'],6,-1);
			 }
			$this->string.=" where ".$pkey;
			$this->sql=array();
			$this->effactrow=$this->DB['master']->exec($this->string);
	  }
	  if($mapper!='')
	  {
		  $afterkey='';
		  if(isset($this->data[$this->mapper[$mapper]['localFiled']])&&$this->data[$this->mapper[$mapper]['localFiled']]!='')
			{
			   if($this->PRI==$this->mapper[$mapper]['localFiled'])
                 $afterkey=$this->mapper[$mapper]['targetFiled'];
			}
		
			if(isset($this->mapper[$mapper]['localFiled2'])&&isset($this->data[$this->mapper[$mapper]['localFiled2']])&&$this->data[$this->mapper[$mapper]['localFiled2']]!='')
			{
			   if($this->PRI==$this->mapper[$mapper]['localFiled2'])
               $afterkey=$this->mapper[$mapper]['targetFiled2'];
			}
			if(isset($this->mapper[$mapper]['localFiled3'])&&isset($this->data[$this->mapper[$mapper]['localFiled3']])&&$this->data[$this->mapper[$mapper]['localFiled3']]!='')
			{
			   if($this->PRI==$this->mapper[$mapper]['localFiled3'])
               $afterkey=$this->mapper[$mapper]['targetFiled3'];
			}
		if($afterkey!='')
		  {
			$this->maps[$mapper]->data[$afterkey]=$this->pkidv();	
			$this->maps[$mapper]->save();
		  }
		 unset($pkey);
	     unset($mapper);
	     unset($saveafter);
		 unset($afterkey);
	  }
     if($this->ismapper&&count($this->maparray)>0)
	 {
		  $this->updatemaperafter();
	 }
	  return $this;
  }
  function pkid($id=null)
  {
	if($id!=null)
	{
	  $this->data[$this->PRI]=intval($id);
	  return $this;
	}
	if(isset($this->data[$this->PRI])) return $this->data[$this->PRI]; else null;
  }
  function select($name)
  {
    $this->sql['fields'].=$name;
	return $this;
  }
  function from($name='')
  {
    if($name==''){ 
	   $this->sql['from']=$this->tablename;
	}else{		
		if(M($name)->tablename!=$this->tablename)
		{	      
		  $this->sql['from']=$this->getDataBaseName().".".$this->tablename.",".M($name)->getDataBaseName().".".M($name)->tablename;
		}else
		  $this->sql['from']=$this->tablename;
	}
	return $this;
  }
  function leftjoin($name,$one=null)
  {
	if($one==null)
	{
     $this->sql['from']=$this->getDataBaseName().".".$this->tablename." as ".$this->modelname." LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->tablename." as ".M($name)->modelname;
	}else{
	 $this->sql['from'].=" LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->tablename." as ".M($name)->modelname;
	}
	return $this;
  }
  function joinon($name)
  {
    $this->sql['from']=$this->sql['from']." ON ".$name;
	return $this;
  }
  function orderby($name)
  {
    $this->sql['orderby']=" order by ".$name;
	return $this;
  }
  function groupby($name)
  {
    $this->sql['groupby']=" group by ".$name;
	return $this;
  }
  function where($name,$value='')
  {
	if($value!='') $this->sql['where']=" where ".$name."='".$value."'";
	else $this->sql['where']=" where ".$name;
	return $this;
  }
  function whereIn($name,$value)
  {
	if($this->sql['where']=='')
     $this->sql['where']=" where ".$name." IN (".$value.")";
	else
	 $this->sql['where'].=" and ".$name." IN (".$value.")";
	return $this;
  }
  function whereLike($name,$value)
  {
	if($this->sql['where']=='')
     $this->sql['where']=" where ".$name." like ('".$value."')";
	else
	 $this->sql['where'].=" and ".$name." like ('".$value."')";
	return $this;
  }
  function whereOr($name,$value='')
  {
    if($this->sql['where']=='') $this->sql['where']=" where ";
	else $this->sql['where'].=" OR ";
	if($value!='') $this->sql['where'].=$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function whereAnd($name,$value='')
  {
    if($this->sql['where']=='') $this->sql['where']=" where ";
	else $this->sql['where'].=" and ";
	if($value!='') $this->sql['where'].=$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function limit($start,$end=null)
  {
    $this->sql['limit']=" limit ".intval($start);
	if($end!=null) $this->sql['limit'].=",".intval($end);
    return $this;
  }
  function colupdate($colname,$num=1)
  {	
    if(isset($this->types[$colname]))
	{
    $this->string="update ".$this->tablename." set "."`$colname`=`$colname`+".intval($num)." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$res=$this->DB['master']->exec($this->string);
		$this->sql=array();
		return $res;
	 }catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
	}else{
	  return false;
	}
  }
  function Totalnum()
  {
    $pfields="*";
	if(isset($this->types[$this->PRI])) $pfields=$this->PRI;
    $this->string="select count(".$pfields.") as totalnum from ".$this->tablename." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$res=$this->DB['master']->query($this->string);	
		$total=$res->fetch(PDO::FETCH_ASSOC);  
		$this->sql=array();
		return $total['totalnum'];
	}catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
	return 0;
  }
  function getData($obj='')
  {
	if(count($this->data)>0)
	{
	   if($obj='Object') return new ArrayObject($this->data);
       else return $this->data;
	}
	$this->up(0);
	if(count($this->data)>0)
	{
	  if($obj='Object') return new ArrayObject($this->data);
       else return $this->data;
	}else{
	  return $this;
	}
  }
  function setData($caseArray)
  {
	if(is_array($caseArray))
	{
	  foreach($caseArray as $k=>$v)
	  {
	    if(isset($this->types[$k]))
		{
		  $this->data[$k]=$v;
		}
	  }
	  return $this;
	}else{
	  return null;
	}
  }
  function clear()
  {
  	 $this->string="TRUNCATE TABLE `".$this->tablename."`";
	 $this->sql=array();
	 return $this->DB['master']->exec($this->string);
  }
  function delete($id='')
  {
    if(is_numeric($id))
	{
	  if($this->sql['where']=='')
	  {
		$this->where($this->PRI."='".$id."'");
	  }else{
		$this->whereAnd($this->PRI."='".$id."'");
	  }	  
	  $this->string="DELETE from ".$this->tablename." ".$this->sql['where'].$this->sql['limit'];
	  $this->sql=array();
	  return $this->DB['master']->exec($this->string);
	}else if($id=='all')
	{
	   $this->string="TRUNCATE TABLE `".$this->tablename."`";
	   $this->sql=array();
	   return $this->DB['master']->exec($this->string);
	}else{
          if($this->sql['where']=='')
		  {
		    $this->where($this->PRI."='".$this->data[$this->PRI]."'");
		  }

          $this->string="DELETE from ".$this->tablename." ".$this->sql['where'].$this->sql['limit'];
		  $this->sql=array();
		  return $this->DB['master']->exec($this->string);
	}
  }
  /*
  *重新把指针设置为record开头
  */
  function reset()
  {
     $this->objpoint=0;
	 $this->recordend=false;
	 unset($this->recordend);
	 return $this;
  }
  function next()
  {
    if(count($this->record)>=$this->objpoint){
	  $this->objpoint=count($this->record);
	  return $this;
	}else{
	  $this->objpoint++;
	  return $this;
	}
  }
  function isEmpty()
  {
    if(count($this->record)==0) return true;
  }
  /*
  *判断是否up完record数组
  */
  function isEnd()
  {
	 if(count($this->record)==$this->objpoint){
	   $this->recordend=true;
	   return true;
	 }else{
	   return false;
	 }
  }
  function up($id=null)
  {
	if($this->recordend==true){
	  unset($this->data);
	  return $this;
	}
    if(is_array($this->record))
	{
	  if($id!=null) $this->objpoint=$id;
	  if(is_object($this->record[$this->objpoint]))
	  {
		$temp=get_object_vars($this->record[$this->objpoint]);
	    foreach($temp as $k=>$v)
		{
		  if(isset($this->types[$k]))
		  {
		    $this->data[$k]=$v;
		  }
		}		
	  }else if(is_array($this->record[$this->objpoint]))
	  {
		 foreach($this->record[$this->objpoint] as $k=>$v)
		{
		  if(isset($this->types[$k]))
		  {
		    $this->data[$k]=$v;
		  }
		}
	  }
	}else if(is_object($this->record)){
		$temp=get_object_vars($this->record);
	    foreach($temp as $k=>$v)
		{
		  if(isset($this->types[$k]))
		  {
		    $this->data[$k]=$v;
		  }
		}	
	}
	return $this;
  }
  /*
  *自己手动支持sql,目前还不清楚是返回数组好还是返回pdo对像好
  */
  function query($string,$ms='')
  {
	$this->string=$string;
	$this->sql=array();
    if(empty($ms))
	 return $this->DB['master']->query($this->string); 
	else
	 return $this->DB['slaves']->query($this->string); 	 
  }
  function fetch($fetchobj='')
  {
	if($this->sql['fields']!='')
	{
	  $pfields=$this->sql['fields'];
	}else{
	  $pfields="*";
	}
	if($this->sql['from']=='')
	{
	  $this->sql['from']=$this->tablename;
	}
	if($this->sql['where']=='')
	{
	  if(isset($this->data[$this->PRI])&&is_numeric($this->data[$this->PRI]))
      $this->where($this->PRI."='".$this->data[$this->PRI]."'");
	}
    $this->string="select ".$pfields." from ".$this->sql['from']." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'].$this->sql['limit'];	
	try{
		$res=$this->DB['slaves']->query($this->string);	
        if($fetchobj=='FETCH_OBJ')
        {
		   $fetchobj=PDO::FETCH_OBJ;
		}else{
		   $fetchobj=PDO::FETCH_ASSOC;
		}
		$this->record=$res->fetchAll($fetchobj);  
		$this->sql=array();
		return $this;
	}catch (PDOException $e) 
        {
           echo $e->getMessage();
        }    
  }
  function getDataBaseName()
  {
	  if($this->databasename) return $this->databasename;
	  $this->string="SELECT DATABASE() AS name";
	  $res=$this->DB['slaves']->query($this->string);
      $database=$res->fetch(PDO::FETCH_ASSOC);  
	  $this->databasename=$database['name'];
	  return $database['name'];
  }
  function getTableName()
  {
     return $this->tablename;
  }
  function mapsFileds()
  {
     $numargs = func_num_args();
	 $fileds=func_get_args();
	 $selectfiled='';
	 for ($i = 0; $i < $numargs; $i++) {
        $selectfiled.=$this->modelname.".".$fileds[$i]." as".$this->modelname.$fileds[$i]. ",";
     }
	 if($selectfiled!='')
	   $selectfiled=substr($selectfiled, 0, -1);
	 else
	   $selectfiled=$this->modelname.".*";
	 $this->sql['mapsfiled']=$selectfiled;
	 return $this;
  }
  /*
  *数据关联$m->Books->bookname;
  *多对多
  */
  function ManyhasMany($mapper,$relation=array())
  {
	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	 if(count($relation)>0)
	  {
	    $fileds=implode(",",$relation);		
	  }
     if(is_array($this->record)&&isset($this->record[0]))
	 { 
		 $n=count($this->record);
		 for($i=0;$i<$n;$i++)
		 {
			 $this->maps[$mapper]->select($fileds);
			 $this->maps[$mapper]->where($this->mapper[$mapper]['targetFiled']."='".$this->record[$i][$this->mapper[$mapper]['localFiled']]."'");
			 if(isset($this->mapper[$mapper]['targetFiled2']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled2']."='".$this->record[$i][$this->mapper[$mapper]['localFiled2']]."'");
			 }
			 if(isset($this->mapper[$mapper]['targetFiled3']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled3']."='".$this->record[$i][$this->mapper[$mapper]['localFiled3']]."'");
			 }
				try{
					$this->maps[$mapper]->fetch();	
					$this->maps[$mapper]->up();
					$this->sql=array();
					$this->record[$i][$mapper]=$this->maps[$mapper]->record; 
				}catch (PDOException $e) 
					{
					   echo $e->getMessage();
					}
		 }
	 }elseif(is_array($this->record))
	 {
	    $this->maps[$mapper]->where($this->mapper[$mapper]['targetFiled']."='".$this->record[$this->mapper[$mapper]['localFiled']]."'");
			if(isset($this->mapper[$mapper]['targetFiled2']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled2']."='".$this->record[$i][$this->mapper[$mapper]['localFiled2']]."'");
			 }
			 if(isset($this->mapper[$mapper]['targetFiled3']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled3']."='".$this->record[$i][$this->mapper[$mapper]['localFiled3']]."'");
			 }
				try{
					$this->maps[$mapper]->fetch();	
					$this->maps[$mapper]->up();
					$this->sql=array();
					$this->record[$mapper]=$this->maps[$mapper]->record; 
				}catch (PDOException $e) 
					{
					   echo $e->getMessage();
					}
	 }
	 return $this;
  }
  /*
  *数据关联$m->Books->bookname;
  *一对多
  */
  function hasMany($mapper,$relation=array())
  {
	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	 if(count($relation)>0)
	 {
	    $fileds=implode(",",$relation);
		$this->maps[$mapper]->select($fileds);
	 }
             $this->maps[$mapper]->where($this->mapper[$mapper]['targetFiled']."='".$this->data[$this->mapper[$mapper]['localFiled']]."'");
			 if(isset($this->mapper[$mapper]['targetFiled2']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled2']."='".$this->data[$this->mapper[$mapper]['localFiled2']]."'");
			 }
			 if(isset($this->mapper[$mapper]['targetFiled3']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled3']."='".$this->data[$this->mapper[$mapper]['localFiled3']]."'");
			 }
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->up();
			$this->sql=array();
			if(is_array($this->record)&&isset($this->record[0]))
			{
			  $n=count($this->record);
			  for($i=0;$i<$n;$i++)
			  {
			   $this->record[$i][$mapper]=$this->maps[$mapper]->record; 
			  }
			}elseif(is_array($this->record)){
			  $this->record[$mapper]=$this->maps[$mapper]->record;
			}
			return $this;
		}catch (PDOException $e) 
			{
			   echo $e->getMessage();
			}
  }
  /*
  *数据关联$m->Books->bookname;
  *一对一
  */
  function hasOne($mapper,$relation=array())
  {
	  $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	  if(count($relation)>0)
	  {
	    $fileds=implode(",",$relation);
		$this->maps[$mapper]->select($fileds);
	  }
     $this->maps[$mapper]->where($this->mapper[$mapper]['targetFiled']."='".$this->data[$this->mapper[$mapper]['localFiled']]."'")->limit(1);
	        if(isset($this->mapper[$mapper]['targetFiled2']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled2']."='".$this->record[$i][$this->mapper[$mapper]['localFiled2']]."'");
			 }
			 if(isset($this->mapper[$mapper]['targetFiled3']))
			 {
			   $this->maps[$mapper]->whereAnd($this->mapper[$mapper]['targetFiled3']."='".$this->record[$i][$this->mapper[$mapper]['localFiled3']]."'");
			 }
	 
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->up();
			$this->sql=array();
			if(is_array($this->record)&&isset($this->record[0]))
			{
			  $n=count($this->record);
			  for($i=0;$i<$n;$i++)
			  {
			   $this->record[$i][$mapper]=$this->maps[$mapper]->record; 
			  }
			}elseif(is_array($this->record)){
			  $this->record[$mapper]=$this->maps[$mapper]->record;
			}
			return $this;
		}catch (PDOException $e) 
			{
			   echo $e->getMessage();
			}
  }
  function getArrayFormField($data='')
  {
    if($data=='') $data=$this->data;
	$t=array();
	foreach($this->types as $k=>$v)
	{
	  if(isset($data[$k])) $t[$k]=$data[$k];
	}
	return $t;
  }
  /*
  *处理映像数组
  */
  function promaparray($mapper,$maparray)
  {
	if(empty($maparray)) return $this; //清空mapper关系
    $mapmodel=$this->mapper[$mapper]['TargetModel'];
	$mpi=count($this->maparray[$mapper]);
	foreach($maparray as $k=>$v)
	{
	  if(isset(M($mapmodel)->types[$k]))
	  {
	    $this->maparray[$mapper][$mpi][$k]=$v;
		$mpi++;
	  }elseif(is_array($v))
	  {
	    $this->maparray[$mapper][]=M($mapmodel)->getArrayFormField($v);
	  }elseif(is_object($v))
	  {
	    $this->maparray[$mapper][]=M($mapmodel)->getArrayFormField(get_object_vars($v));
	  }
	}
	//处理关联模型
    foreach($this->maparray[$mapper] as $k=>$v)
	{
	  if(isset(M($mapmodel)->types[$this->mapper[$mapper]['targetFiled']])&&!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled']]))
	  {
		 $this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled']]=$this->data[$this->mapper[$mapper]['localFiled']];
	  }
	  if(isset(M($mapmodel)->types[$this->mapper[$mapper]['targetFiled2']])&&!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled2']]))
	  {
		 $this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled2']]=$this->data[$this->mapper[$mapper]['localFiled2']];
	  }
	  if(isset(M($mapmodel)->types[$this->mapper[$mapper]['targetFiled3']])&&!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled3']]))
	  {
		 $this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled3']]=$this->data[$this->mapper[$mapper]['localFiled3']];
	  }
	}
	return $this;
  }
  /*
  *关联ORM赋值方式$tablemodel->Books(array("booksname"=>"小学生守则"))->save();
  *Books为关联mapper $tablemodel自动给books模型赋值关联键的值
  */
  public function setDataToMapper($mapper,$args=array(),$PRI=false)
  {
	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	 if(isset($this->mapper[$mapper]['targetFiled']))
	  {
		if($this->maps[$mapper]->PRI==$this->mapper[$mapper]['targetFiled'])
		{
		 if($PRI)
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled']=>$this->data[$this->mapper[$mapper]['localFiled']]));		   
		}else{
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled']=>$this->data[$this->mapper[$mapper]['localFiled']]));	
		}
	  }
	 if(isset($this->mapper[$mapper]['targetFiled2']))
	  {
		if($this->maps[$mapper]->PRI==$this->mapper[$mapper]['targetFiled2'])
		{
		 if($PRI)
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled2']=>$this->data[$this->mapper[$mapper]['localFiled2']]));	   
		}else{
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled2']=>$this->data[$this->mapper[$mapper]['localFiled2']]));
		}
	  }
	 if(isset($this->mapper[$mapper]['targetFiled3']))
	  {
		if($this->maps[$mapper]->PRI==$this->mapper[$mapper]['targetFiled3'])
		{
		 if($PRI)
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled3']=>$this->data[$this->mapper[$mapper]['localFiled3']]));   
		}else{
		   $this->maps[$mapper]->setData(array($this->mapper[$mapper]['targetFiled3']=>$this->data[$this->mapper[$mapper]['localFiled3']]));
		}
	  }
	 if(!Empty($args))
	   $this->maps[$mapper]->setData($args);
	 return $this;
  }
  function __call($name,$Args)
  {
	if($name=='get') return $this->getArray($Args);
	if($name=='getAll') return $this->getAllArray($Args);
	if(isset($this->mapper[$name])){	
	  $this->maps[$name]=M($this->mapper[$name]['TargetModel']);
	  if(is_array($Args[0]))
	  {
		$this->setDataToMapper($name,$Args[0]);
	  }elseif(method_exists($this,$this->mapper[$name]['map'])) {
		call_user_func(array($this,$this->mapper[$name]['map']),$name,$Args);
	  }
	  return $this->maps[$mapper];
	}
	if(strtolower(substr($name,0,3))=='set')
	{
	  $str=substr($name,3);
      if(isset($this->types[strtolower($str)]))
	  {
		$this->data[$str]=$Args['0'];
		return $this;
	  }
	}
	if(strtolower(substr($name,0,3))=='get')
	{
	  $str=substr($name,3);
      if(isset($this->types[strtolower($str)]))
	  {
		return $this->data[$str];
	  }
	}
	if(substr($name,0,5)=='where')
	{
      $sub=substr($name,5);	  
	  if(isset($this->types[strtolower($sub)]))
	  {
	    return $this->where($sub."='".$Args['0']."'");
	  }else{
	    $substr=explode("And",$sub);
		if(isset($this->types[strtolower($substr[0])]))
	    {
		    $temp='';
			$i=0;			
			if(is_array($Args[0]))
			{
			  $Args=explode(",",$Args[0][0]);
			}else if(count($Args)<2){
			  $Args=explode(",",$Args[0]);
			}
			foreach($substr as $key=>$value)
			{
			  if(isset($this->types[strtolower($value)]))
	          {			  
				  if($i==0)
				  {
					$temp.=$value."='".$Args[$i]."'";	
				  }else
				  {
					$temp.=" and ".$value."='".$Args[$i]."'";	
				  }
				  $i++;
			  }
			}
			if($temp!='')
              return $this->where($temp);
		}
	  }
	}	
  }
}

?>