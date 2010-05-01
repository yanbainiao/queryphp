<?php

class Model
{
   public $tablename;
   public $fields=array();
   public $types=array();
   public $PRI;  //主键名
   public $data; //数据编辑焦点
   public $autoid=false; //是否自增表
   private $sql=array();  //查询使用的条件
   public $string;       //保存查询sql语句
   private $DB=array();   //数据库链接保存
   private $res=null;
   private $record=array();
   public $conn=0;
   public $objpoint=0;
   public $modelname;
   public $databasename;
   public $ismapper;
   public $isjoinleft;
   public $after;
   public $before;
   public function __construct() {
	   $this->modelname=substr(get_class($this),0,-5);
	   $this->DB=getConnect($this->getTableName(),$this->modelname,$this->conn);	   
	   return $this;
   }
  /*
  *手工切换数据库链接
  */
  public function switchDB($model)
  {
	  $this->DB=getConnect($this->tablename,$model,$this->conn);
	  return $this;
  }
  /*
  *取得数据库结构
  */
  public function getMate()
  {
    $this->string="DESCRIBE ".$this->tablename;	
	try{	
	    $this->res=$this->DB['master']->query($this->string);
        $result = $this->res->fetchAll(PDO::FETCH_ASSOC);  
	} catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1001);
        }

	return $result;
  }
  /*
  *取得表结构字段信息
  *处理表结构字段信息
  */
  public function fieldsTable()
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
  /*
  * 自动填充字段
  * create(array("field"=>"aabbcc","field2"=>"112233"));
  * create(array("field"=>"aabbcc","field2"=>"112233"),'field,field2');
  * create(array("field"=>"aabbcc","field2"=>"112233"),'field','field2');
  * create($_POST,'field','field2');
  */
 public function create()
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
	}else{
		if('fix'==func_get_arg(0))
		{
		  $fix=func_get_arg(1);
		  foreach($this->fields as $k=>$v)
			{
			 if(isset($_POST[$k]))
			  {
				 $this->data[$k]=$_POST[$k];
			  }			
			  if(isset($fix[$k])&&isset($_POST[$fix[$k]]))
			  {
				 $this->data[$k]=$_POST[$fix[$k]];
			  }		  
			}
		}
	}
	return $this;    
  }

  public function __get($name)
  {
    if(isset($this->data[strtolower($name)]))
	{
	  return $this->data[strtolower($name)];
	}elseif(isset($this->mapper[$name])){	  
	  if(method_exists($this,$this->mapper[$name]['map'])) {
		call_user_func(array($this,$this->mapper[$name]['map']),$name);
	  }
	  $this->ismapper=true;
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
 public function __set($name,$value)
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

 public function __isset($name)
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
 public function getOne()
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
	  if(isset($arg_list[$i]))
	  {
		  $fields=$arg_list[$i];
		  $i=1;
	  }
	}
    for (; $i < $numargs; $i++) {
		$t=strtoupper($arg_list[$i]);
	   if($t=="DESC"||$t=="ASC")
		{
		  	if(empty($this->sql['orderby']))
		    $this->sql['orderby']=" order by ".$this->PRI." ".$t;
		}else if($t=="FETCH_OBJ"){
		  $returnobj=PDO::FETCH_OBJ;
		}else
		 $pkey.=intval($arg_list[$i]).",";
	 }
	if($pkey!='')
		$pkey=substr($pkey,0,-1);
	if($pkey=='')
		$pkey=1;
	$pkey=$this->PRI." IN (".$pkey.")";

    if(isset($this->sql['fields']))
	{
	  $fields=$this->sql['fields'];
	}
    if(empty($order)) $order=isset($this->sql['orderby'])?$this->sql['orderby']:null;
    $this->string="select ".$fields." from ".$this->tablename." where ".$pkey.$order;	
	try{
		$res=$this->DB['slaves']->query($this->string);	
		$this->record=$res->fetchAll($returnobj); 
		$this->data=$this->record[0];
		$this->objpoint=0;
		$this->sql=array();
		return $this;
	}catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1002);
        }
  }
  /*
  *返回所有行数
  */
 public function getAllArray()
  {
	$arg_list = func_get_args();
	$arg_list=$arg_list[0];
	$numargs=count($arg_list);
	$pkey='';
	$fields="*";
    $returnobj=PDO::FETCH_ASSOC;   
    for ($i=0; $i < $numargs; $i++) {
		$t=strtoupper($arg_list[$i]);
	   if($t=="DESC"||$t=="ASC")
		{
		  if(empty($this->sql['orderby']))
		    $this->sql['orderby']=" order by ".$this->PRI." ".$t;
		}else if($t=="FETCH_OBJ"){
		  $returnobj=PDO::FETCH_OBJ;
		}else if(is_numeric($arg_list[$i])){
		  $this->where($this->PRI."='".$arg_list[$i]."'");
		}else
		 $fields=$arg_list[$i];
	 }

   if(empty($this->sql['where']))
		$this->sql['where']=" where 1 ";
    if(isset($this->sql['fields']))
	{
	  $fields=$this->sql['fields'];
	}
    if(!isset($this->sql['groupby']))
	{
	  $this->sql['groupby']='';
	}
    if(!isset($this->sql['orderby']))
	{
	  $this->sql['orderby']='';
	}
    $this->string="select ".$fields." from ".$this->tablename." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$res=$this->DB['slaves']->query($this->string);	
		$this->record=$res->fetchAll($returnobj); 
		$this->data=$this->record[0];
		$this->objpoint=0;
		$this->sql=array();
		return $this;
	}catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1003);
        }
  }
  /*
  *取回ID主键名
  */
 public function pkkey()
  {
	return $this->PRI;
  }
  /*
  *生成一个空的data
  *也可以返回一个空的小对像 
  *$this->getFields(true); 返回一个空的数组对象
  */
public  function newRecord($data=array())
  {    
	$this->data=array();
	$this->getFillFields($data);
	if($this->autoid) unset($this->data[$this->PRI]);
	return $this;
  }
  /*
  *复制一行对像内容
  *$id为pkid();
  */
 public function copyRecord($id='')
  {
    if($id!='')
	{
	   $this->getOne(intval($id));
	   $this->edit();
	}
	if($this->autoid) unset($this->data[$this->PRI]);
	return $this;
  }
  /*
  插入前操作
  */
 public function updatemaper()
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
 public function updatemaperafter()
  {
    foreach($this->maparray as $m=>$v)
	{
	    $mapperid='';
		$mname=$this->mapper[$m]['TargetModel'];
		foreach($v as $key=>$value)
		{ 
		  $value=M($mname)->getFillFields($value);
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
 public function update()
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
			   if(isset($arglist[1][$this->PRI]))
			   {
				 $this->where($this->PRI."='".$arglist[1][$this->PRI]."'");
			   }else
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
	   }elseif(!isset($arglist[1])){ //从data中取值
	      $sql="";
		  foreach($filedarray as $value)
		  {
			 $sql.=$value."='".$this->data[$value]."',";
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if(empty($this->sql['where']))
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
			if(empty($this->sql['where']))
		    {
			   if(isset($arglist[0][$this->PRI]))
			   {
				 $this->where($this->PRI."='".$arglist[0][$this->PRI]."'");
			   }else
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
			  $t=$this->setMapperToData($mapper)->getlocalPRIFields($mapper,M($objectname)->PRI);
			  if($t!='')
			    $localfields.=$t.",";
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
	      $sql='';
		  foreach($arrays as $key=>$value)
		  {
			$sql.=$key."='".$value."',";
		  }
		  if($sql!='')
		   {
	        $this->string="UPDATE ".$this->tablename." set ".substr($sql,0,-1);
			if(empty($this->sql['where']))
		    {
			   if(isset($arrays[$this->PRI]))
			   {
				 $this->where($this->PRI."='".$arrays[$this->PRI]."'");
			   }else
			   $this->where($this->PRI."='".$this->data[$this->PRI]."'");
			}
			$this->string.=" ".$this->sql['where'].$this->sql['limit'];
			$this->sql=array();
			$this->effectrow=$this->DB['master']->exec($this->string);
		   }
		  if(!isset($arglist[1]))
		  {  //更新DATA
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
    if(is_array($this->mapper[$mapper]['mapping']))
	{
		$fileds=array_values($this->mapper[$mapper]['mapping']);
		if(empty($fileds)) Return '';
		else {
			Return implode(",",$fileds);
		}
	}
	return '';
  }
  /*
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
  public function getMapperlocalFields($mapper)
  {
    $fileds='';
    if(is_array($this->mapper[$mapper]['mapping']))
	{
		$fileds=array_keys($this->mapper[$mapper]['mapping']);
		if(empty($fileds)) Return '';
		else {
			Return implode(",",$fileds);
		}
	}
	Return '';
  }
  /*
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
  public function gettargetPRIFields($mapper,$PRI)
  {
	if(is_array($this->mapper[$mapper]['mapping']))
	{		
		if(isset($this->mapper[$mapper]['mapping'][$PRI])) Return $this->mapper[$mapper]['mapping'][$PRI];
	}	
    return '';
  }
  /*
  * 取得本模型关联模型字段
  * 返回字段 field,field
  */
  public function getlocalPRIFields($mapper,$PRI)
  {
	if(is_array($this->mapper[$mapper]['mapping']))
	{		
		$t=array_search($PRI,$this->mapper[$mapper]['mapping']);
        if($t!=false) return $t;
	}
	return '';
  }
  /*
  *关联mapper更新,本模块更新到关联mapper
  */
  public function setMapperToData($mapper,$args=array(),$PRI=false)
  {
 	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);

    if(is_array($this->mapper[$mapper]['mapping']))
	{
		foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
		{
	       if($this->PRI==$local)
			{
			 if($PRI)
			   $this->setData(array($local=>$this->maps[$mapper]->data[$target]));		   
			}else{
			   $this->setData(array($local=>$this->maps[$mapper]->data[$target]));	
			}
		}
	}else{
	  //没有使用主键关联
	  $this->setData(array($this->maps[$mapper]->pkkey()=>$this->maps[$mapper]->pkid()));
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
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1004);
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
		if(isset($this->sql['where']))
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
  /*
  *清除对象编辑内容
  *也可以使用新数组覆盖
  */
  function clearEdit($data='')
  {
    $this->data=array();
	$this->setData($data);
  }
  /*
  *返回主键值
  *
  */
  function pkid()
  {
	if(isset($this->data[$this->PRI])) return $this->data[$this->PRI]; else null;
  }
  /*
  *清除所有sql设置
  */
  function newSQL()
  {
    $this->sql=array();
	$this->effectrow=false;
	return $this;
  }
  /*
  *返回最新的sql语句
  */
  function querySQL()
  {
	return $this->string;
  }
  /*
  *选择字段
  */
  function select($name)
  {
    if(isset($this->sql['fields'])) $this->sql['fields'].=",".$name;
	else $this->sql['fields']=$name;
	return $this;
  }
  /*
  *选择表名 内链接表各table1,table2
  *默认不用选择自身表名
  */
  function from($name='')
  {
    if($name==''){ 
	   $this->sql['from']=$this->tablename;
	}else{		
		if(M($name)->getTableName()!=$this->tablename)
		{
		  $this->sql['isjoinleft']=true;
		  $this->sql[$this->modelname.'.']=$this->tablename.".";
		  $this->sql["fix".$this->modelname.'.']=$this->modelname.".";
		  $this->sql["fix".M($name)->modelname."."]=M($name)->modelname.".";
		  $this->sql[M($name)->modelname."."]=M($name)->modelname.".";
		  if($this->sql['joinmodel']!='') $this->sql['joinmodel'].="|";
		  $this->sql['joinmodel']=M($name)->modelname.".";
		  $this->sql['from']=$this->getDataBaseName().".".$this->tablename." as ".$this->modelname.",".M($name)->getDataBaseName().".".M($name)->getTableName()." as ".M($name)->modelname;
		}else
		  $this->sql['from']=$this->tablename;
	}
	return $this;
  }
  /*
  *左链接表名left join 主式
  */
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
	  $this->sql['from'].=" LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->getTableName()." as ".M($name)->modelname;
	}else{
	  $this->sql['isjoinleft']=true;
	  $this->sql[$this->modelname.'.']=$this->tablename.".";
	  $this->sql["fix".$this->modelname.'.']=$this->modelname.".";
	  $this->sql["fix".M($name)->modelname."."]=M($name)->modelname.".";
	  $this->sql[M($name)->modelname."."]=M($name)->modelname.".";
	  if(isset($this->sql['joinmodel'])) $this->sql['joinmodel'].="|";
	  $this->sql['joinmodel']=M($name)->modelname.".";
     $this->sql['from']=$this->getDataBaseName().".".$this->tablename." as ".$this->modelname." LEFT JOIN ".M($name)->getDataBaseName().".".M($name)->getTableName()." as ".M($name)->modelname;
	}
	return $this;
  }
  /*
  *左链接条件
  */
  function joinon($name,$modelname='')
  {
	$this->sql['from']=$this->sql['from']." ON ".$name;
	return $this;
  }
  /*
  *魔术引用带modelname的方法
  */
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
  /*
  *左链接表断在ON部分
  *注释掉要全部小写
  */
  function joinwhere($name,$modelname)
  {
	//$modelname=strtolower($modelname);
	$fields=preg_split("/( AND | OR )/i",$name,-1,PREG_SPLIT_DELIM_CAPTURE);
    $count=count($fields);
	  $str='';
	  for($i=0;$i<$count;$i++)
	  {
		  $field=explode("=",$fields[$i]);		  	  
		  if(count($field)==2){
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
  /*
  * orderby 条件
  * 前面可以带model.fields
  */
  function orderby($name)
  {
	if(!preg_match("|\w(\s)\w|",$name)){ 
	  $this->sql['orderby']=" order by ".$this->getFixSQL($this->PRI).$this->PRI." ".$name; }
	else{
	  $n=explode(" ",trim($name));
	  $this->sql['orderby']=" order by ".$this->getFixSQL($n[0]).$name; 
	}
	return $this;
  }
  function groupby($name)
  {
    $this->sql['groupby']=" group by ".$this->getFixSQL($name).$name;
	return $this;
  }
  function where($name,$value='')
  {

    if(empty($this->sql['where'])) $this->sql['where']=" where ";
	else $this->sql['where'].=" and ";

	if($value!='') $this->sql['where'].=$this->getFixSQL($name).$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function whereIn($name,$value)
  {
	if(empty($this->sql['where']))
     $this->sql['where']=" where ".$this->getFixSQL($name).$name." IN (".$value.")";
	else
	 $this->sql['where'].=" and ".$this->getFixSQL($name).$name." IN (".$value.")";
	return $this;
  }
  function whereLike($name,$value)
  {
	if(empty($this->sql['where']))
     $this->sql['where']=" where ".$this->getFixSQL($name).$name." like '".$value."'";
	else
	 $this->sql['where'].=" and ".$this->getFixSQL($name).$name." like '".$value."'";
	return $this;
  }
  function whereOr($name,$value='')
  {
    if(empty($this->sql['where'])) $this->sql['where']=" where ";
	else $this->sql['where'].=" OR ";
	if($value!='') $this->sql['where'].=$this->getFixSQL($name).$name."='".$value."'";
	else $this->sql['where'].=$name;
	return $this;
  }
  function whereAnd($name,$value='')
  {
    if(empty($this->sql['where'])) $this->sql['where']=" where ";
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
  /*
  *取得字段前辍，看看是不是带有model名的
  */
  function getFixSQL($fields,$modelname='')
  {
    if($modelname=='') $modelname=$this->modelname;
	$fix='';
	if(isset($this->sql['isjoinleft']))
	{
	   $fix=$this->sql["fix".$modelname.'.'];	
	   if(preg_match ("/".str_replace(".","\.",$fix)."/i",$fields))
		   $fix='';
	}
	return $fix;
  }
  /*
  *字段累加可以自己设置累加值
  *比如在点击数时候有用
  */
  function updateCol($colname,$num=1)
  {	
    if(isset($this->types[$colname]))
	{
	$fix=$num>0?'+':'-';
	$num=abs($num);
    $this->string="update ".$this->tablename." set "."`$colname`=`$colname`".$fix.$num." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$this->effectrow=$this->DB['master']->exec($this->string);
		$this->sql=array();
		return $this;
	 }catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1005);
        }
	}else{
	  return false;
	}
  }
  /*
  *查询前处理sql语句
  */
  function preSQL() {
	if(empty($this->sql['from']))
	{
	  $this->sql['from']=$this->tablename;
	}
	if(!isset($this->sql['where'])) $this->sql['where']=' where 1 ';
	if(!isset($this->sql['groupby'])) $this->sql['groupby']='';
	if(!isset($this->sql['orderby'])) $this->sql['orderby']='';  	 
  }
  /*
  *取得表的行数，在做列表分页时候经常用到
  *
  */
  function count()
  {
    $pfields=$this->tablename.".*";
	if(empty($this->sql['from']))
	{
	  $this->sql['from']=$this->tablename;
	}
	if(!isset($this->sql['where'])) $this->sql['where']=' where 1 ';
	if(!isset($this->sql['groupby'])) $this->sql['groupby']='';
	if(!isset($this->sql['orderby'])) $this->sql['orderby']='';

	if(isset($this->types[$this->PRI])) $pfields=$this->tablename.".".$this->PRI;
    $this->string="select count(".$pfields.") as totalnum from ".$this->sql['from']." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'];	
	try{
		$res=$this->DB['master']->query($this->string);	
		$total=$res->fetch(PDO::FETCH_ASSOC);  
		return $total['totalnum'];
	}catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1006);
        }
	return 0;
  }
  /*
  *简单分页数据量偏移 pager要离在最后面就是，不然取count时候可能后面设置的条件得不到
  */
  public function pager($page,$onepage) {	
        $total= $this->count();		
        $total_page =ceil($total/$onepage);
        if (empty($page))  
        {
			$this->limit(0,$onepage);
        }else
        {			
            if($page>$total_page) $page=$total_page+1;
			$page = ($page-1)*$onepage;
			$this->limit($page,intval($onepage));
        }  
		Return $this;
  }
  /*
  *取得已经查询的数据内容
  *$i=obj 返回obj形式
  */
  function getRecord($i='')
  {
	if(count($this->record)>0)
	{
      if($i!='')
	  {	    
	   if($i===true) return new ArrayObject($this->record);
	   if(is_numeric($i)){ 
          return $this->record[$i]; 
		}
	  }else
	    return $this->record;
	}
	return null;
  }
  /*
  *取得编辑数据可以返回对象形式
  */
  function getData($obj=false)
  {
	if(count($this->data)>0)
	{
	  if($obj===true){
		  return new ArrayObject($this->data);       
	  }elseif(is_array($obj)){
		  $t=array();
	      foreach($obj as $k)
		  {
			if(isset($this->types[$k]))
			{
			  $t[$k]=$this->data[$k];
			}
		  }
		  return $t;
	   }else return $this->data;
	}else{
	  return null;
	}
  }
  /*
  *设置编辑数据
  */
  function setData($caseArray)
  {
	if(is_object($caseArray)) $caseArray=get_class_vars($caseArray);
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
  /*
  *清空数据库内容，慎用
  */
  function clear()
  {
  	 $this->string="TRUNCATE TABLE `".$this->tablename."`";
	 $this->sql=array();
	 $this->record=array();
	 return $this->DB['master']->exec($this->string);
  }
  /*
  *删除对象数据记录
  */
 public function delete($id='')
  {
    
	if(is_numeric($id))
	{
	  $this->whereAnd($this->PRI."='".$id."'");
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
	}elseif(is_object($id)){
		$objectname=get_class($id);
		$objectname=substr($objectname,0,-5);
		$mapper='';
		//关联删除
		if(count($this->mapper)>0&&$objectname!='')
		{
		  $localfields='';
		  foreach($this->mapper as $k=>$v)
		  {
		    if($v['TargetModel']==$objectname)
			{
			  $mapper=$k;
			  $this->wheremapper($mapper);
			  break;
			}
		  }          
		}
	}elseif(is_array($id)){
	   $this->whereIn($this->PRI,implode(",",$id));	
	}else{
	  if(empty($this->sql['where']))
	  {
		$this->where($this->PRI."='".$this->data[$this->PRI]."'");
	  }
	}
	      $this->string="DELETE from ".$this->tablename." ".$this->sql['where'].$this->sql['limit'];
		  $this->sql=array();
		  $this->effectrow=$this->DB['master']->exec($this->string);
		  return $this;
  }
  /***
  *使用缓存
  *
  ***/
  public function cache($cachekey=''){
  	$this->sql['cachekey']=$cachekey;
	 return $this;
  }
  /*
  *判断是否有插入更新删除效果
  */
 public function isEffect()
  {
    if($this->effectrow>1) return  $this->effectrow;
	else false;
  }
  /*
  *重新把指针设置为record开头
  */
 public function reset()
  {
     $this->objpoint=0;
	 $this->recordend=false;
	 return $this;
  }
 public function next()
  {
    $p=$this->objpoint+1;
	$c=count($this->record);
	if($c>$p){
	  $this->objpoint++;	  
	  return $this;
	}else{
	  $this->objpoint=$c-1;
	  return $this;
	}
  }
  /*
  *返回查询数据的行数或是否为真
  */
 public function isEmpty()
  {
    if(count($this->record)==0) return true;
	else return false;
  }
  /*
  *判断是否up完record数组
  */
 public function isEnd()
  {
	 $p=$this->objpoint+1;
	 $c=count($this->record);
	 if($p>=$c){
	   if($p>$c)
	   {
	     $this->objpoint=$c>0?$c-1:0;
	   }
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
 public function edit($id=null)
  {
	if(isset($this->recordend)&&$this->recordend==true){
	  $this->data=array();
	  return $this;
	}
	$this->data=array();
    if(is_array($this->record))
	{
	  $this->isEnd();
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
 public function query($string,$ms='')
  {
	$this->string=$string;
	$this->sql=array();
    if(empty($ms))
	 return $this->DB['master']->query($this->string); 
	else
	 return $this->DB['slaves']->query($this->string); 	 
  }
 public function fetch($fetchobj='')
  {
	if(isset($this->sql['fields']))
	{
	  $pfields=$this->sql['fields'];
	}else{
	  $pfields="*";
	}
	if(empty($this->sql['from']))
	{
	  $this->sql['from']=$this->tablename;
	}
	if(empty($this->sql['where']))
	{
      $this->where("1");
	}
	if(!isset($this->sql['orderby'])) $this->sql['orderby']='';
	if(!isset($this->sql['groupby'])) $this->sql['groupby']='';
	if(!isset($this->sql['limit'])) $this->sql['limit']='';
    $this->string="select ".$pfields." from ".$this->sql['from']." ".$this->sql['where'].$this->sql['groupby'].$this->sql['orderby'].$this->sql['limit'];	
	try{
		$res=$this->DB['slaves']->query($this->string);	
		if($fetchobj=='FETCH_OBJ')
		{
		   $f=PDO::FETCH_OBJ;
		   $fetchobj='';
		}else{
		   $f=PDO::FETCH_ASSOC;
		}
		$this->sql=array();
		if(is_object($fetchobj))
		{
		  $res->setFetchMode(PDO::FETCH_INTO,$fetchobj);		  
		  Return $res->fetchAll(PDO::FETCH_INTO);
		}elseif($fetchobj!=''&&class_exists($fetchobj,false)){
		  Return $res->fetchAll(PDO::FETCH_CLASS,$fetchobj);
		}else{		    
		  $this->record=$res->fetchAll($f); 
		  $this->objpoint=0;
		  if(isset($this->record[0]))
		   $this->data=$this->record[0];
		  else
		   $this->data=array();
		}		
		return $this;
	}catch (PDOException $e) 
        {
           throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1007);
        }    
  }
  /*
  *返回数据库名
  */
 public function getDataBaseName()
  {
	  if(empty($this->databasename)){ 
		  $s="SELECT DATABASE() AS name";
		  $res=$this->DB['slaves']->query($s);
		  $database=$res->fetch(PDO::FETCH_ASSOC);  
		  $this->databasename=$database['name'];
		  return $database['name'];
	  }else{
	    return $this->databasename;
	  }
  }
  /*
  *返回表名可以重载设置表名
  */
 public function getTableName()
  {
     return $this->tablename;
  }
  /*
  *链接leftjoin 时候使用
  *->selectbooks("bookname,bookid")这样子
  */
 public function selectFileds($fields,$modelname)
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
  *把编辑数据库设置到record里面
  *有时候新添加数据时候又想做关系查询
  *dataUP(true);是附加到现有数组后面
  */
 public function dataUp($up=true){
  	if($up){
	  if($this->record[$this->objpoint][$this->PRI]==$this->pkid())
	  {
		$this->record[$this->objpoint]=$this->data;
	  }else{
		$this->record[]=$this->data;
	  }	  
	}else{
	  $this->record=array();
	  $this->record[]=$this->data;
	}
	Return $this;
  }
  /*
  *数据关联$m->Books->bookname;
  *多对多
  */
 public function ManyhasMany($mapper,$relation=array())
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

		    if(is_array($this->mapper[$mapper]['mapping']))
			{
				foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
				{
				  $this->maps[$mapper]->whereAnd($target,$this->record[$i][$local]);
				}
			}else{
			  //没有使用主键关联
			  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$i][$this->pkkey()]);
			}
			try{
				$this->maps[$mapper]->fetch();					
				$this->sql=array();
				$this->record[$i][$mapper]=$this->maps[$mapper]->record; 
			}catch (PDOException $e) 
				{
				   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1008);
				}
		 }
	 }elseif(is_array($this->record))
	 {	   
		    if(is_array($this->mapper[$mapper]['mapping']))
			{
				foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
				{
				  $this->maps[$mapper]->whereAnd($target,$this->record[$local]);
				}
			}else{
			  //没有使用主键关联
			  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$this->pkkey()]);
			}		
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->edit();
			$this->sql=array();
			$this->record[$mapper]=$this->maps[$mapper]->record; 
		}catch (PDOException $e) 
			{
			   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1009);
			}
	 }
	 return $this;
  }
  /*
  *数据关联$m->Books->bookname;
  *一对多
  */
 public function hasMany($mapper,$relation=array())
  {
	 $this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	 if(count($relation)>0)
	 {
	    $fileds=implode(",",$relation);
		$this->maps[$mapper]->select($fileds);
	 }
     if(is_array($this->record)&&isset($this->record[0]))
	 { 
		 	$this->maps[$mapper]->select($fileds);
            $this->isEnd();
		    if(is_array($this->mapper[$mapper]['mapping']))
			{
				foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
				{
				  $this->maps[$mapper]->whereAnd($target,$this->record[$this->objpoint][$local]);
				}
			}else{
			  //没有使用主键关联
			  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$this->objpoint][$this->pkkey()]);
			}
			try{
				$this->maps[$mapper]->fetch();	
				$this->maps[$mapper]->edit();
				$this->sql=array();
				$n=count($this->record);
				 for($i=0;$i<$n;$i++)
				 {
					$this->record[$i][$mapper]=$this->maps[$mapper]->record; 
				 }
				
			}catch (PDOException $e) 
				{
				   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1008);
				}
		 
	 }elseif(is_array($this->record))
	 {	   
		    if(is_array($this->mapper[$mapper]['mapping']))
			{
				foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
				{
				  $this->maps[$mapper]->whereAnd($target,$this->record[$local]);
				}
			}else{
			  //没有使用主键关联
			  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$this->pkkey()]);
			}	
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->edit();
			$this->sql=array();
			$this->record[$mapper]=$this->maps[$mapper]->record; 
		}catch (PDOException $e) 
			{
			   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1009);
			}
	 }
	 return $this;
  }
  /*
  *数据关联$m->Books->bookname;
  *一对一
  */
 public function hasOne($mapper,$relation=array())
  {
	$this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
	if(count($relation)>0)
	{
		$fileds=implode(",",$relation);
		$this->maps[$mapper]->select($fileds);
	}

     if(is_array($this->record)&&isset($this->record[0]))
	 { 
	    $this->maps[$mapper]->select($fileds);
		$this->isEnd();
		if(is_array($this->mapper[$mapper]['mapping']))
		{
			foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
			{
			  $this->maps[$mapper]->whereAnd($target,$this->record[$this->objpoint][$local]);
			}
		}else{
		  //没有使用主键关联
		  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$this->objpoint][$this->pkkey()]);
		}
		$this->maps[$mapper]->limit(1);
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->edit();
			$this->sql=array();
			 $n=count($this->record);
			 for($i=0;$i<$n;$i++)
			 {
				$this->record[$i][$mapper]=$this->maps[$mapper]->record; 
			 }
			
		}catch (PDOException $e) 
			{
			   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1008);
			}
	 }elseif(is_array($this->record))
	 {	   
		    if(is_array($this->mapper[$mapper]['mapping']))
			{
				foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
				{
				  $this->maps[$mapper]->whereAnd($target,$this->record[$local]);
				}
			}else{
			  //没有使用主键关联
			  $this->maps[$mapper]->whereAnd($this->maps[$mapper]->pkkey(),$this->record[$this->pkkey()]);
			}	
			$this->maps[$mapper]->limit(1);
		try{
			$this->maps[$mapper]->fetch();	
			$this->maps[$mapper]->edit();
			$this->sql=array();
			$this->record[$mapper]=$this->maps[$mapper]->record; 
		}catch (PDOException $e) 
			{
			   throw new mylog('model ['.$e->getMessage()."]".$this->modelname,1009);
			}
	 }
	 return $this;
  }
  /*
  * 返回一个record空对像
  *
  */
 public function getFields($obj=false)
  {
    if($obj)
	 return new ArrayObject($this->fields);
	else
	 return $this->fields;
  }
  /*
  *填充一行内容，没有使用默认值填充
  */
 public function getFillFields($data=array())
  {
     $t=array();
	 foreach($this->fields as $key=>$value)
	 {
	   if(isset($data[$key])) $t[$key]=$data[$key];
	   else $t[$key]=$value;
	 }
	 return $t;
  }
  /*
  *从数组中取得表字段的值也跟过滤差不多
  */
 public function getFormFields($data='')
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
 public function promaparray($mapper,$maparray)
  {
	if(empty($maparray)){ $this->maparray[$mapper]=array(); return $this; } //清空mapper关系
    $mapmodel=$this->mapper[$mapper]['TargetModel'];
	$mpi=count($this->maparray[$mapper]);
	foreach($maparray as $k=>$v)
	{
	  if(isset(M($mapmodel)->types[$k]))
	  {
		$this->maparray[$mapper][$mpi][$k]=$v;
	  }elseif(is_array($v))
	  {	    
		$this->maparray[$mapper][]=M($mapmodel)->getFormFields($v);
	  }elseif(is_object($v))
	  {
	    $this->maparray[$mapper][]=M($mapmodel)->getFormFields(get_object_vars($v));
	  }
	}
	//处理关联模型
    foreach($this->maparray[$mapper] as $k=>$v)
	{	  
		if(is_array($this->mapper[$mapper]['mapping']))
		{
			foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
			{
				if(M($mapmodel)->pkkey()!=$target)
				{
		          if(!isset($this->maparray[$mapper][$k][$target]))
		             $this->maparray[$mapper][$k][$target]=$this->data[$local];	
				}
			}
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
	if(is_array($this->mapper[$mapper]['mapping']))
	{
		foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
		{
		    if($this->maps[$mapper]->pkkey()==$target)
			{
			 if($PRI) $this->maps[$mapper]->setData(array($target=>$this->data[$local]));		   
			}else{
			   $this->maps[$mapper]->setData(array($target=>$this->data[$local]));	
			}
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
  /*
  *关联对像查询
  */
 public function wheremapper($mapper)
  {
	$this->maps[$mapper]=M($this->mapper[$mapper]['TargetModel']);
    
    if(is_array($this->mapper[$mapper]['mapping']))
	{
		foreach($this->mapper[$mapper]['mapping'] as $local=>$target)
		{
		  $this->whereAnd($local,$this->maps[$mapper]->data[$target]);
		}
	}else{
	  //没有使用主键关联
	  $this->whereAnd($this->pkkey(),$this->maps[$mapper]->data[$this->maps[$mapper]->pkid()]);
	}
	return $this;
  }
 public function __call($name,$Args)
  {
	if($name=='get') return $this->getOne($Args);
	if($name=='find') return $this->getOne($Args);
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
	  return $this->maps[$name];
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
	  $str=strtolower(substr($name,3));
      if(isset($this->types[$str]))
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