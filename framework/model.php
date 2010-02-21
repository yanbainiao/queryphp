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
   function __construct() {
	   $this->DB=getConnect($this->tablename,substr(get_class($this),0,-5),$this->conn);
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
	}else 
	  return null;
  }

  function __set($name,$value)
  {
    if(isset($this->types[strtolower($name)]))
	{
	  return $this->data[strtolower($name)]=$value;
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

  function save($id=null)
  {
	 $pkey='';
	 if(is_numeric($this->data[$this->PRI]))
	 {
	   $pkey=$this->PRI."='".intval($this->data[$this->PRI])."'";
	   unset($this->data[$this->PRI]);
	 }else if(is_numeric($id))
	 {
	   $pkey=$this->PRI."='".intval($id)."'";
	   unset($this->data[$this->PRI]);	   
	 }else if($id=='all')
	 {
	    $pkey='1';
		unset($this->data[$this->PRI]);
	 }
	 if($pkey=='')
	  {
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
  function from($name)
  {
    $this->sql['from']=$this->tablename.",".$name;
	return $this;
  }
  function leftjoin($name,$one=null)
  {
	if($one==null)
     $this->sql['from']=$this->tablename." leftjoin ".$name;
	else{
	 $this->sql['from'].=" leftjoin ".$name;
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
  function where($name)
  {
	$this->sql['where']=" where ".$name;
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
  function whereAnd($name)
  {
    $this->sql['where'].=" and ".$name;
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
		  }else{
		    $this->whereAnd($this->PRI."='".$this->data[$this->PRI]."'");
		  }

	  if(isset($this->data[$this->PRI])&&is_numeric($this->data[$this->PRI])) 
	  {	   	  
		  $this->string="DELETE from ".$this->tablename." ".$this->sql['where'].$this->sql['limit'];
		  $this->sql=array();
		  return $this->DB['master']->exec($this->string);
	  }
	}
  }
  function up($id=null)
  {
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
     $this->objpoint++;
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
  function query($string)
  {
	$this->string=$string;
	$this->sql=array();
    return $this->DB['master']->query($this->string); 
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
  function __call($name,$Args)
  {
	if($name=='get') return $this->getArray($Args);
	if($name=='getAll') return $this->getAllArray($Args);
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