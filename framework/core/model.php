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
   var $isjoinleft;
   var $after;
   var $before;
   public function __construct() {
	   $this->modelname=substr(get_class($this),0,-5);
	   $this->DB=getConnect($this->getTablename(),$this->modelname,$this->conn);
	   /*
	   if(is_array($this->DB))
	   {
	     
	   }
	   */
	   return $this;
   }
  public function getMate()
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

  public function analyseTable()
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
  function createForm()
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
    }elseif($numargs==0){
	  foreach($this->fields as $k=>$v)
		{
         if(isset($_POST[$k]))
		  {
			 $this->data[$k]=$_POST[$k];
		  }
		} 
	}elseif(is_array(func_get_arg()))
	{
		$arg_list = func_get_args();
		  if(!isset($arg_list[2]))
		  {
             $filedarray=explode(",",$arglist[1]);
		  }
		$arg0=func_get_arg(0);
		for ($i = 1; $i < $numargs; $i++) {
		  if(isset($arg_list[2]))
		  {
		   if(isset($this->fields[$arg_list[$i]]))
		   {
		     $this->data[$v]=$arg0[$arg_list[$i]];
		   }
		  }else{
		    $this->data[$v]=$arg0[$filedarray[--$i]];
		  }
		}
	}
	return $this;    
  }
  /*
  * 自动填充字段
  * autoFieldS(array("field"=>"aabbcc","field2"=>"112233"));
  * autoFieldS(array("field"=>"aabbcc","field2"=>"112233"),'field,field2');
  * autoFieldS(array("field"=>"aabbcc","field2"=>"112233"),'field','field2');
  * autoFieldS($_POST,'field','field2');
  */
  function autoFields()
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
    }elseif($numargs==0)
	{
		foreach($this->fields as $k=>$v)
		{
         if(isset($_POST[$k]))
		  {
			 $this->data[$k]=$_POST[$k];
		  }
		}
	}elseif(is_array(func_get_arg()))
	{
		$arg_list = func_get_args();
		  if(!isset($arg_list[2]))
		  {
             $filedarray=explode(",",$arglist[1]);
		  }
		$arg0=func_get_arg(0);
		for ($i = 1; $i < $numargs; $i++) {
		  if(isset($arg_list[2]))
		  {
		   if(isset($this->fields[$arg_list[$i]]))
		   {
		     $this->data[$v]=$arg0[$arg_list[$i]];
		   }
		  }else{
		    $this->data[$v]=$arg0[$filedarray[--$i]];
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
		call_user_func(array($this,$this->mapper[$name]['map']),$name);
	  }
	  $this->ismapper=true;
	  $this->after=$name;
	  $this->aftermodel=$this->mapper[$name]['TargetModel'];
	  $this->before=$name;
	  $this->beforemodel=$this->modelname;
	  return $this->maps[$mapper]=M($this->mapper[$name]['TargetModel']);
	}else{ 
	  if(count($this->record)>0)
	  {
		$this->objpoint=0;
	    $this->edit(0);
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
	    $this->after=$name;
	    $this->aftermodel=$this->mapper[$name]['TargetModel'];
	    $this->before=$name;
	    $this->beforemodel=$this->modelname;
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
  /*
  * 到得一个ID record(一行)
  * $book->get(1,6);
  * $book->find(1,6)
  */
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
  function pkidkey()
  {
	return $this->PRI;
  }
  /*
  *生成一个空的data
  *也可以返回一个空的小对像 
  *$this->getObjFields 返回一个空的数组对象
  */
  function newRecord($data=array())
  {    
	$this->data=array();
	$this->getDefaultFormField($data);
	if($this->autoid) unset($this->data[$this->PRI]);
	return $this;
  }
  function copyRecord($id='')
  {
    if($id!='')
	{
	   $this->getArray(intval($id));
	   $this->edit();
	}
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
              $fields=$this->getlocalPRIFields($m,$tm->PRI);
			  if($fields!=''&&isset($this->maparray[$m][$key][$tm->PRI])&&$this->maparray[$m][$key][$tm->PRI]!=NULL)
			  {  
			    $this->setMapperToData($m);
				$this->data[$fields]=$this->maparray[$m][$key][$tm->PRI];
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
	    $mapperid='';
		$mname=$this->mapper[$m]['TargetModel'];
		foreach($v as $key=>$value)
		{ 
		  $value=M($mname)->getDefaultFormField($value);
		  $fields=$this->gettargetPRIFields($m,$this->PRI);
		  $this->maparray[$m][$key][$fields]=$this->pkid();
		  $value[$fields]=$this->pkid();
          M($mname)->clearEdit($value);
		  M($mname)->save();          
		  $mapperid=M($mname)->pkid();
          $this->maparray[$m][$key][M($mname)->PRI]=M($mname)->pkid();
	   }
       $fields=$this->getlocalPRIFields($m,M($mname)->PRI);
       if($fields!=''&&$mapperid!=''){
		   $this->data[$fields]=$mapperid;
		   $this->update($fields);
	   }
	}
    M($mname)->record=$this->maparray;//给原来record记录
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
			$this->effectrow=$this->DB['master']->exec($this->string);
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
			$this->effectrow=$this->DB['master']->exec($this->string);
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
			$this->effectrow=$this->DB['master']->exec($this->string);
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
		  $localfields='';
		  foreach($this->mapper as $k=>$v)
		  {
		    if($v['TargetModel']==$objectname)
			{
			  $mapper=$k;
              $this->objsaveper($mapper);
			  $k=$this->setMapperToData($mapper)->getlocalPRIFields($mapper,M($objectname)->PRI);
			  if($k!='')
			    $localfields.=$k.",";
			}
		  }
          if($mapper!='')
		  {
			 if($localfields!='')
			 {	 
			   $localfields=substr($localfields,0,-1);
			   $this->update($localfields);
			 }
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
			$this->effectrow=$this->DB['master']->exec($this->string);
		   }
		  if(!isset($arglist[1]))
		  {  //更新当然record
		     $this->setData($arrays);
		  }
		}
	 }
	 return $this;
  }
  /*
  * 取得关联模型字段
  * 返回字段 field,field
  */
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
  /*
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
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
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
  public function gettargetPRIFields($mapper,$PRI)
  {
	if(isset($this->mapper[$mapper]['targetFiled'])&&$this->mapper[$mapper]['localFiled']==$PRI) return $this->mapper[$mapper]['targetFiled'];
	if(isset($this->mapper[$mapper]['targetFiled2'])&&$this->mapper[$mapper]['localFiled2']==$PRI) return $this->mapper[$mapper]['targetFiled2'];
	if(isset($this->mapper[$mapper]['targetFiled3'])&&$this->mapper[$mapper]['localFiled3']==$PRI) return $this->mapper[$mapper]['targetFiled3'];
    return '';
  }
  /*
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
  public function getlocalPRIFields($mapper,$PRI)
  {
	if(isset($this->mapper[$mapper]['localFiled'])&&$this->mapper[$mapper]['targetFiled']==$PRI) return $this->mapper[$mapper]['localFiled'];
	if(isset($this->mapper[$mapper]['localFiled2'])&&$this->mapper[$mapper]['targetFiled2']==$PRI) return $this->mapper[$mapper]['localFiled2'];
	if(isset($this->mapper[$mapper]['localFiled3'])&&$this->mapper[$mapper]['targetFiled3']==$PRI) return $this->mapper[$mapper]['localFiled3'];
    return '';
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
  /*
  *关联更新object;
  */
  function objsaveper($mapper)
  {
		$this->maps[$mapper]=M($v['TargetModel']);
		$this->setDataToMapper($mapper);
		$this->maps[$mapper]->save();
		$localfields=$this->setMapperToData($mapper)->getlocalPRIFields($mapper,$this->maps[$mapper]->PRI);
		if($localfields!='')
		  $this->data[$localfields]=$this->maps[$mapper]->pkid();
        return $this;
  }
  function save($id=null)
  {
	 $pkey='';
	 $mapper='';
	 $saveafter=array();
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
		if(isset($this->data[$this->PRI])) $pkey=$this->PRI."='".$this->pkid()."'";
	 }elseif(is_object($id)){
		 if($id->modelname!=$this->modelname&&count($this->mapper)>0)
	     {
			foreach($this->mapper as $k=>$v)
			{
			  if($id->modelname==$v['TargetModel'])
			  {
                   $this->objsaveper($k);
			       array_push($saveafter,$k);
			  }
			}
	     }
	   if($this->data[$this->PRI]==0) unset($this->data[$this->PRI]);
	   else $pkey=$this->PRI."='".$this->pkid()."'";

	 }elseif(is_numeric($this->data[$this->PRI]))
	 {
	   if($this->data[$this->PRI]==0) unset($this->data[$this->PRI]);
	   else $pkey=$this->PRI."='".$this->pkid()."'";
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
			 }elseif($this->types[$k]=='datetime')
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
		 $this->effectrow=$this->DB['master']->exec($this->string);
		 $this->sql=array();
	    }catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
        if(isset($this->types[$this->PRI]))
		{
		  $this->data[$this->PRI]=$this->DB['master']->lastInsertId();
		}
		$pkey=true;
	  }else
	  {
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
		$this->effectrow=$this->DB['master']->exec($this->string);
		$pkey=false;
	  }
	  if($pkey===true)
	  {//插入后操作
         if(!Empty($saveafter))
	     {
			foreach($saveafter as $v)
			{
	          $this->save_after($v);
			}
	     }
	  }
     if($this->ismapper&&count($this->maparray)>0)
	 {
		  $this->updatemaperafter();
	 }
	  return $this;
  }
  function save_after($mapper)
  {
     $fields=$this->gettargetPRIFields($mapper,$this->$PRI);
     M($this->mapper[$mapper]['TargetModel'])->setData(array($fields=>$this->pkid()));
     M($this->mapper[$mapper]['TargetModel'])->update($fields);
  }
  function clearEdit($data='')
  {
    $this->data=array();
	$this->setData($data);
  }
  function pkid()
  {
	if(isset($this->data[$this->PRI])) return $this->data[$this->PRI]; else null;
  }
  function select($name)
  {
    if($this->sql['fields']=='') $this->sql['fields'].=$name;
	else $this->sql['fields'].=",".$name;
	return $this;
  }
  function from($name='')
  {
    if($name==''){ 
	   $this->sql['from']=$this->tablename;
	}else{		
		if(M($name)->tablename!=$this->tablename)
		{
		  $this->sql['isjoinleft']=true;
		  $this->sql[$this->modelname.'.']=$this->tablename.".";
		  $this->sql["fix".$this->modelname.'.']=$this->modelname.".";
		  $this->sql["fix".M($name)->modelname."."]=M($name)->modelname.".";
		  $this->sql[M($name)->modelname."."]=M($name)->modelname.".";
		  if($this->sql['joinmodel']!='') $this->sql['joinmodel'].="|";
		  $this->sql['joinmodel']=M($name)->modelname.".";
		  $this->sql['from']=$this->getDataBaseName().".".$this->tablename." as ".$this->modelname.",".M($name)->getDataBaseName().".".M($name)->tablename." as ".M($name)->modelname;
		}else
		  $this->sql['from']=$this->tablename;
	}
	return $this;
  }

  function leftjoin($name,$one=null)
  {
	if(isset($this->sql['isjoinleft']))
	{
	  $this->sql['isjoinleft']=true;
	  $this->sql[$this->modelname.'.']=$this->tablename.".";
	  $this->sql["fix".$this->modelname.'.']=$this->modelname.".";
	  $this->sql["fix".M($name)->modelname."."]=M($name)->modelname.".";
	  $this->sql[M($name)->modelname."."]=M($name)->modelname.".";
	  if($this->sql['joinmodel']!='') $this->sql['joinmodel'].="|";
	  $this->sql['joinmodel']=M($name)->modelname.".";
	  $this->sql['from'].=" LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->tablename." as ".M($name)->modelname;
	}else{
	  $this->sql['isjoinleft']=true;
	  $this->sql[$this->modelname.'.']=$this->tablename.".";
	  $this->sql["fix".$this->modelname.'.']=$this->modelname.".";
	  $this->sql["fix".M($name)->modelname."."]=M($name)->modelname.".";
	  $this->sql[M($name)->modelname."."]=M($name)->modelname.".";
	  if($this->sql['joinmodel']!='') $this->sql['joinmodel'].="|";
	  $this->sql['joinmodel']=M($name)->modelname.".";
     $this->sql['from']=$this->getDataBaseName().".".$this->tablename." as ".$this->modelname." LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->tablename." as ".M($name)->modelname;
	}
	return $this;
  }
  function joinon($name,$modelname='')
  {
	$this->sql['from']=$this->sql['from']." ON ".$name;
	return $this;
  }
  function joinpreon($fields,$t=0,$modelname)
  {
	 $str='';
     for($i=0,$j=0;$i<$t;$i++,$j++)
	 {
			if(isset($this->sql[$modelname."."]))
			{
			  $mname=$this->getFixSQL($fields[$i],$modelname).$fields[$i];
			}else{
			  $mname=$this->getFixSQL($fields[$i]).$fields[$i];
			}	
			$i++;
        $fields[$i]=str_replace("'","",$fields[$i]);
		if(is_numeric($fields[$i]))
		{
		  $str.=$mname."='".$fields[$i]."'";
		}elseif(isset($this->types[$fields[$i]]))
		{
		  $str.=$mname."=".$this->getFixSQL($fields[$i]).$fields[$i];
		}else{
		  $str.=$mname."=".$this->getFixSQL($fields[$i]).$fields[$i];
		}
		$j++;
	 }
	 return $str;
  }
  function joinwhere($name,$modelname)
  {
	$modelname=strtolower($modelname);
	$fields=preg_split("/( AND | OR )/i",$name,-1,PREG_SPLIT_DELIM_CAPTURE);
    $count=count($fields);
	  $str='';
	  for($i=0;$i<$count;$i++)
	  {
		  $field=explode("=",$fields[$i]);		  	  
		  if(count($field)==2){
			  echo $modelname;
		    $str.=$this->joinpreon($field,2,$modelname);
	      }else{
			 $f=strtoupper(trim($fields[$i]));	
			if($f=='AND'||$f=='OR') 
		    {
		     $str.=" ".$f." ";
		    }else{
		    $str.=$fields[$i];
			}
		  }
	  }
    if($str!='') $name=$str;
	$this->sql['from']=$this->sql['from']." ON ".$name;
	return $this;
  }
  function orderby($name)
  {
	$this->sql['orderby']=" order by ".$this->getFixSQL($name).$name;
	return $this;
  }
  function groupby($name)
  {
    $this->sql['groupby']=" group by ".$this->getFixSQL($name).$name;
	return $this;
  }
  function where($name,$value='')
  {

    if($this->sql['where']=='') $this->sql['where']=" where ";
	else $this->sql['where'].=" and ";

	if($value!='') $this->sql['where'].=$this->getFixSQL($name).$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function whereIn($name,$value)
  {
	if($this->sql['where']=='')
     $this->sql['where']=" where ".$this->getFixSQL($name).$name." IN (".$value.")";
	else
	 $this->sql['where'].=" and ".$this->getFixSQL($name).$name." IN (".$value.")";
	return $this;
  }
  function whereLike($name,$value)
  {
	if($this->sql['where']=='')
     $this->sql['where']=" where ".$this->getFixSQL($name).$name." like '".$value."'";
	else
	 $this->sql['where'].=" and ".$this->getFixSQL($name).$name." like '".$value."'";
	return $this;
  }
  function whereOr($name,$value='')
  {
    if($this->sql['where']=='') $this->sql['where']=" where ";
	else $this->sql['where'].=" OR ";
	if($value!='') $this->sql['where'].=$this->getFixSQL($name).$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function whereAnd($name,$value='')
  {
    if($this->sql['where']=='') $this->sql['where']=" where ";
	else $this->sql['where'].=" and ";

	if($value!='') $this->sql['where'].=$this->getFixSQL($name).$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function limit($start,$end=null)
  {
    $this->sql['limit']=" limit ".intval($start);
	if($end!=null) $this->sql['limit'].=",".intval($end);
    return $this;
  }
  function getFixSQL($fields,$modelname='')
  {
    if($modelname=='') $modelname=$this->modelname;
	$fix='';
	if($this->sql['isjoinleft'])
	{
	   $fix=$this->sql["fix".$modelname.'.'];	
	   if(preg_match ("/".str_replace(".","\.",$fix)."/i",$fields))
		   $fix='';
	}
	return $fix;
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
		//$this->sql=array();
		return $total['totalnum'];
	}catch (PDOException $e) 
        {
           echo $e->getMessage();
        }
	return 0;
  }
  function getObjRecord()
  {
	if(count($this->record)>0)
	{
      return new ArrayObject($this->record);
	}
	return null;    
  }
  function getRecord($i='')
  {
	if(count($this->record)>0)
	{
      if($i!='')
	  {	    
	   if(!is_numeric($i)) $i=intval($i);
        return $this->record[$i];
	  }else
	    return $this->record;
	}
	return null;
  }
  function getData($obj='')
  {
	if(count($this->data)>0)
	{
	   if($obj=='Object') return new ArrayObject($this->data);
       else return $this->data;
	}
	if(count($this->data)>0)
	{
	  if($obj=='Object') return new ArrayObject($this->data);
       else return $this->data;
	}else{
	  return null;
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
		  $this->effectrow=$this->DB['master']->exec($this->string);
		  return $this;
	}else if($id=='all')
	{
	   $this->string="TRUNCATE TABLE `".$this->tablename."`";
	   $this->sql=array();
		  $this->effectrow=$this->DB['master']->exec($this->string);
		  return $this;
	}else{
          if($this->sql['where']=='')
		  {
		    $this->where($this->PRI."='".$this->data[$this->PRI]."'");
		  }

          $this->string="DELETE from ".$this->tablename." ".$this->sql['where'].$this->sql['limit'];
		  $this->sql=array();
		  $this->effectrow=$this->DB['master']->exec($this->string);
		  return $this;
	}
  }
  function isEffect()
  {
    if($this->effectrow>1) return  $this->effectrow;
	else false;
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
  /*
  *设置编辑函数
  *把原来的-> function up()改为edit这样比较理解
  */
  function edit($id=null)
  {
	if($this->recordend==true){
	  $this->data=array();
	  return $this;
	}
	$this->data=array();
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
  *自定义sql，手工使用sql操作 $ms为指定是master还slaves数据库链接
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
  /*
  *链接leftjoin 时候使用
  *->selectbooks("bookname,bookid")这样子
  */
  function selectFileds($fields,$modelname)
  {
     $tablename=M($modelname)->getTableName();
	 $modelname=M($modelname)->modelname;
	 $fields=explode(",",$fields);
	 $selectfiled='';
     $numargs=count($fields);
	 for ($i = 0; $i < $numargs; $i++) {
        $selectfiled.=$modelname.".".$fields[$i].",";
     }
	 if($selectfiled!='')
	   $selectfiled=substr($selectfiled, 0, -1);
	 else
	   $selectfiled=$modelname.".*";
	 $this->select($selectfiled);
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
				$this->maps[$mapper]->edit();
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
			$this->maps[$mapper]->edit();
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
		$this->maps[$mapper]->edit(0);
		$this->sql=array();
		if(is_array($this->record)&&is_array($this->record[0]))
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
		$this->maps[$mapper]->edit();
		$this->sql=array();
		if(is_array($this->record)&&is_array($this->record[0]))
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
  * 返回一个record空对像
  *
  */
  function getObjFields()
  {
    return new ArrayObject($this->fields);
  }
  function getDefaultFormField($data=array())
  {
     $t=array();
	 foreach($this->fields as $key=>$value)
	 {
	   if(isset($data[$key])) $t[$key]=$data[$key];
	   else $t[$key]=$value;
	 }
	 return $t;
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
	if(empty($maparray)){ $this->maparray[$mapper]=array(); return $this; } //清空mapper关系
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
	  if(isset($this->mapper[$mapper]['targetFiled'])&&M($mapmodel)->PRI!=$this->mapper[$mapper]['targetFiled'])
	  {
		 if(!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled']]))
		   $this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled']]=$this->data[$this->mapper[$mapper]['localFiled']];
	  }
	  if(isset($this->mapper[$mapper]['targetFiled2'])&&M($mapmodel)->PRI!=$this->mapper[$mapper]['targetFiled2'])
	  {
		 if(!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled2']]))
		  $this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled2']]=$this->data[$this->mapper[$mapper]['localFiled2']];
	  }
	  if(isset($this->mapper[$mapper]['targetFiled3'])&&M($mapmodel)->PRI!=$this->mapper[$mapper]['targetFiled3'])
	  {
		 if(!isset($this->maparray[$mapper][$k][$this->mapper[$mapper]['targetFiled3']])) 
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
  /*
  *处理sql语句方法
  *支持whereuserANDlanguageORbooksLIKE
  *
  */
  public function whereSQL($sub,$Args)
  {		
		$substr=preg_split("/(AND|OR|LIKE|DY|DD|XY|XD|BD|ISNULL|NOTNULL|IN|NOTIN|NOTEQ|EQ)/",$sub,-1,PREG_SPLIT_DELIM_CAPTURE);
		$numsub=count($substr);
		if($numsub>0)
	    {
			$temp='';
			$after=true;
           for($i=0,$j=0;$i<$numsub;$i++,$j++)
			{
			  $value=strtolower($substr[$i]);
			  if(isset($this->types[$value]))
	          {			  
				  $key=++$i;
				  switch($substr[$key])
				  {
				    case 'AND':
					case 'EQ':
                        $temp.=$this->getFixSQL($name).$value."='".$Args[$j]."' AND ";
						break;
				    case 'OR':
                        $temp.=$this->getFixSQL($name).$value."='".$Args[$j]."' OR  ";
						break;
				    case 'LIKE':
                        $temp.=$this->getFixSQL($name).$value." LIKE '".$Args[$j]."' AND ";
						break;
				    case 'DY':
                        $temp.=$this->getFixSQL($name).$value.">'".$Args[$j]."' AND ";
						break;
				    case 'DYOR':
                        $temp.=$this->getFixSQL($name).$value.">'".$Args[$j]."' OR  ";
						break;
				    case 'DYOR':
					case 'EQOR':
                        $temp.=$this->getFixSQL($name).$value."='".$Args[$j]."' OR  ";
						break;
				    case 'DD':
                        $temp.=$this->getFixSQL($name).$value.">='".$Args[$j]."' AND ";
						break;
				    case 'XY':
                        $temp.=$this->getFixSQL($name).$value."<'".$Args[$j]."' AND ";
						break;
				    case 'DDOR':
                        $temp.=$this->getFixSQL($name).$value.">='".$Args[$j]."' OR  ";
						break;
				    case 'XYOR':
                        $temp.=$this->getFixSQL($name).$value."<'".$Args[$j]."' OR  ";
						break;
				    case 'XD':
                        $temp.=$this->getFixSQL($name).$value."<='".$Args[$j]."' AND ";
						break;
				    case 'BD':
					case 'NOTEQ':
                        $temp.=$this->getFixSQL($name).$value."!='".$Args[$j]."' AND ";
						break;
				    case 'XDOR':
                        $temp.=$this->getFixSQL($name).$value."<='".$Args[$j]."' OR  ";
						break;
				    case 'BDOR':
					case 'NOTEQOR':
                        $temp.=$this->getFixSQL($name).$value."!='".$Args[$j]."' OR  ";
						break;
				    case 'NOTIN':
						if(is_array($Args[$j]))
                         $temp.=$this->getFixSQL($name).$value." NOTIN (".implode($Args[$j]).") AND ";
					    else
						 $temp.=$this->getFixSQL($name).$value." NOTIN (".$Args[$j].") AND "; 
						break;
				    case 'IN':
						if(is_array($Args[$j]))
						  $temp.=$this->getFixSQL($name).$value." IN (".implode(",",$Args[$j]).") AND ";                          
					    else{
                           $temp.=$this->getFixSQL($name).$value." IN (".$Args[$j].") AND ";
						}
						break;
				    case 'ISNULL':
                        $temp.=$this->getFixSQL($name).$value."  IS NULL AND ";
						break;
				    case 'NOTNULL':
                        $temp.=$this->getFixSQL($name).$value." NOTNULL  AND ";
						break;
					default:
						  if($key==$numsub)
					      {
							  $temp.=$this->getFixSQL($name).$value."='".$Args[$j]."'     ";
							  $after=false;
						  }
				  }
			  }//TYPES
			}	
			if($temp!='')
			{
              if($after)
			  {
			    $temp=substr($temp,0,-4);
			  }
			  $this->whereAnd($temp);
			}
		}
		return $this;
  }
  function wheremapper($mapper)
  {
	$this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
    
	if(isset($this->mapper[$mapper]['targetFiled'])&&isset($this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled']]))
	{
	  $this->whereAnd($this->mapper[$mapper]['localFiled'],$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled']]);	  
	}
	if(isset($this->mapper[$mapper]['targetFiled2'])&&isset($this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled2']]))
	{
	  $this->whereAnd($this->mapper[$mapper]['localFiled2'],$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled2']]);	  
	}
	if(isset($this->mapper[$mapper]['targetFiled3'])&&isset($this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled3']]))
	{
	  $this->whereAnd($this->mapper[$mapper]['localFiled3'],$this->maps[$mapper]->data[$this->mapper[$mapper]['targetFiled3']]);	  
	}
	return $this;
  }
  function __call($name,$Args)
  {
	if($name=='get') return $this->getArray($Args);
	if($name=='find') return $this->getArray($Args);
	if($name=='getAll') return $this->getAllArray($Args);
	if(isset($this->mapper[$name])){
	  $this->maps[$name]=M($this->mapper[$name]['TargetModel']);
	  if(is_array($Args[0]))
	  {
		$this->setDataToMapper($name,$Args[0]);
	  }elseif(is_object($Args[0])||empty($Args[0])){
		$this->wheremapper($name);
		return $this;
	  }elseif(method_exists($this,$this->mapper[$name]['map'])) {
		call_user_func(array($this,$this->mapper[$name]['map']),$name,$Args);
	  }	  
	  $this->after=$name;
	  $this->aftermodel=$this->mapper[$name]['TargetModel'];
	  $this->before=$name;
	  $this->beforemodel=$this->modelname;
	  return $this->maps[$mapper];
	}
	if(substr($name,0,6)=='select')
	{
      $sub=substr($name,6);	  
	  $this->selectFileds($Args['0'],strtolower($sub));
	  return $this;
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
	    $this->where(strtolower($sub),$Args['0']);
		return $this;
	  }else{
        $this->whereSQL($sub,$Args);
		return $this;
	  }
	}
	if(substr($name,0,6)=='findBy')
	{
      $sub=substr($name,6);	
	  if(isset($this->types[strtolower($sub)]))
	  {
	    $this->where(strtolower($sub),$Args['0']);
		return $this;
	  }else{
        $this->whereSQL($sub,$Args);
		return $this;
	  }
	}

	if(substr($name,0,6)=='joinon')
	{
	  $sub=substr($name,6);
	  $this->joinwhere($Args['0'],$sub);
	  return $this;
	}
  }
}

?>