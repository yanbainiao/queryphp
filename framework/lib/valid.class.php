<?php
/*
 * 数据验证类
 */ 
class basevalid {
	//是否URL
	public function url($str) {
		Return filter_var($str, FILTER_VALIDATE_URL);
	}
	//是否邮件
	public function email($str) {
		Return filter_var($str, FILTER_VALIDATE_EMAIL);
	}
	//是否正数
	public function int() {
		$i=func_num_args();
		$arrays=func_get_args();
		if($i==1)
		{
		  Return filter_var($arrays[0], FILTER_VALIDATE_INT);//返回一个参数 验证是否为整数
		}elseif($i==2){//最小值
		  Return filter_var($arrays[0], FILTER_VALIDATE_INT,array("options"=>array("min_range"=>$arrays[1])));
		}elseif($i==3) {//数值范围
		  Return filter_var($arrays[0], FILTER_VALIDATE_INT,array("options"=>array("min_range"=>$arrays[1],"max_range"=>$arrays[2])));
		}else{
		 Return false;
		}
	}
	//检查日期
	public function date($str) {
		$date=strtotime($str);
		return checkdate(date('m', $date), date('d',$date), date('Y',$date));
	}
	//手机号
	public function moblie($str) {
		Return preg_match("/^0{0,1}(13[0-9]|15[7-9]|153|156|18[7-9])[0-9]{8}$/",$str);
	}
	//中文英文用户名
	public function cnname($str) {
		Return preg_match("/^[\x80-\xff_a-zA-Z0-9]{3,15}+$/",$str);
	}
	//英文字母用户名
	public function en($str) {
		Return preg_match("/^[A-Za-z]+$/",$str);
	}
	//英文字母数字用户名
	public function ennum($str) {
		Return preg_match("/^[A-Za-z0-9]+$/",$str);
	}
	//是否包含了中文
	public function hascn($str) {
		Return preg_match("/[\x7f-\xff]/", $str);
	}
	function idcard_verify_number($idcard_base){ 
		if (strlen($idcard_base) != 17)
		{
			return false;
		}
		// 加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

		// 校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');

		$checksum = 0;
		for ($i = 0; $i < strlen($idcard_base); $i++){
		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
		}

		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];

		return $verify_number;
	} 
}
class valid {
	var $modname='';
	var $vkey=array();
	var $error=array();
	public function __construct($orm=null,$validkey=null) {
		if($orm)
		{
		$this->modname=$orm;
	    $this->vkey=$orm->valid[$validkey];
		}
	}
	public function getError() {
		Return $this->error;
	}
	public function validData($orm,$validkey) {
	  	if(!empty($orm))
		{
		 $this->modname=$orm;
	     $this->vkey=$orm->valid[$validkey];
		}
		$this->error=array();
		//把所有要验证字段取出来
       if(is_array($this->vkey))
	   {
	     foreach($this->vkey as $k=>$v)
		 {
		    //取得字段验条件
			foreach($v as $kk=>$vv)
			{
			   if(is_numeric($kk))
			   {
				 if(method_exists($this,$vv))
				 $this->{$vv}($k,array());
			   }elseif(method_exists($this,$kk))
			   {
			      $this->{$kk}($k,$vv);
			   }
			}
		 }
	   }else{
	     Return true;
	   }
	   if(empty($this->error)) Return true;
	   else {
	   	Return false;
	   }
	}
	/*
	*自定义对象检查
	*/
	public function obj($key,$option) {
		if(isset($option['obj'])&&isset($option['method'])&&call_user_func(array($option['obj'],$option['method']),$this->modname->data[$key],$option))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 通不过对象检查，内含非法字。";
			  }		
			Return false;
		}
	}
	/*
	*自定义函数检查
	*/
	public function fun($key,$option) {
		if(isset($option['fun'])&&call_user_func($option['fun'],$this->modname->data[$key],$option))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 通不过函数检查，内含非法字。";
			  }		
			Return false;
		}
	}
	/*
	*自定义正则检查
	*/
	public function reg($key,$option) {
		if(isset($option['reg'])&&preg_match($option['reg'],$this->modname->data[$key]))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 通不过正则检查，内含非法字。";
			  }		
			Return false;
		}
	}
	/*
	*检测是否英文字母和数字
	*/
	public function engnum($key,$option) {
		if(preg_match("/^[A-Za-z0-9]]+$/",$this->modname->data[$key]))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不是英文字母和数字。";
			  }		
			Return false;
		}
	}
	/*
	*检测是否英文字母和数字和汉字
	*/
	public function cnname($key,$option) {
		if(basevalid::cnname($this->modname->data[$key]))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 只能是中文或英文和数字和下划线。";
			  }		
			Return false;
		}
	}
	/*
	*检测是否英文字母
	*/
	public function eng($key,$option) {
		if(preg_match("/^[A-Za-z]+$/",$this->modname->data[$key]))
		{
		   return true;
		}else{
		  	if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不是英文字母。";
			  }		
			Return false;
		}
	}
	/*
	* 检查字符长度
	*/
	public function min_leng($key,$option) {
		if(is_array($option)) 
		{
		  $value=$option['value'];
		}else{
		  $value=$option;
		}
		$value=intval($value);
		if(strlen($this->modname->data[$key])<$value)
		{
			  if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 字符太短了。";
			  }		
			Return false;
		}else {
			Return true;
		}
	}
	/*
	* 检查字符长度
	*/
	public function max_leng($key,$option) {
		if(is_array($option)) 
		{
		  $value=$option['value'];
		}else{
		  $value=$option;
		}
		$value=intval($value);
		if(strlen($this->modname->data[$key])>$value)
		{
			  if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 字符太长了。";
			  }		
			Return false;
		}else {
			Return true;
		}
	}
	/*
	*是否整数
	*/
	public function int($key,$option) {
		if(!isset($option['min_range'])&&!isset($option['max_range']))
		{
			if(basevalid::int($this->modname->data[$key]))
		    {
			  Return true;
			}else{
			   if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不是整数。";
			  }		
			Return false;
			}
		}elseif(isset($option['min_range'])&&!isset($option['max_range'])){//最小值
		  if(basevalid::int($this->modname->data[$key],$option['min_range']))
		    {
			  Return true;
			}else{
			   if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不是整数,或小于最小值。";
			  }		
			Return false;
			}
		}elseif(isset($option['min_range'])&&isset($option['max_range'])) {//数值范围
		  if(basevalid::int($this->modname->data[$key],$option['min_range'],$option['max_range']))
		    {
			  Return true;
			}else{
			   if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不是整数,或不在设置的值范内。";
			  }		
			Return false;
			}
		}else{
		 Return false;
		}
	}
	/**
	*判断前后值是否相同
	*/
	public function config($key,$option) {
	
	   if(isset($_POST[$option['POST']]))
	   {
		 $value=$_POST[$option['POST']];
	   }elseif(isset($_GET[$option['POST']])){
	     $value=$_GET[$option['POST']];
	   }else{
		   //没有找到值
		  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
			$this->error[$key]=$this->error[$key]." 前后值不相同,不能为空。";
		  }			
		  Return false;
	   }
	   if(empty($this->modname->data[$key])){
	   	  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
			$this->error[$key]=$this->error[$key]." 前后值不相同,也不能为空。";
		  }			
		  Return false;
	   }elseif($this->modname->data[$key]==$value)
	   {
	     Return true;
	   }else{
	   	  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
			$this->error[$key]=$this->error[$key]." 前后值不相同。";
		  }			
		  Return false;
	   }
	}
	/*
	* 必须赋值
	*/
	public function request($key,$option) {
		if(empty($this->modname->data[$key])){ 
			  if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不能为空。";
			  }		
			Return false;
		}else {
			Return true;
		}
	}
	/*
	* 修改时候检查是否唯一的值 key为字段名 
	* 取了自己主键不相同
	*/
	public function updateunique($key,$option) {
		$this->modname->newSQL();//清除条件
		if(isset($option['sql']))
		{
		  $this->modname->where($option['sql']);
		}
		if($this->modname->where($key."='".$this->modname->{get.$key()}."'")->whereAnd($this->modname->pkkey()."!='".$this->modname->pkid()."'")->count()>0){
		  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
		    $this->error[$key]=$this->error[$key]." 数据库中不是唯一的值。";
		  }
		  Return false;
		}else{
		  Return true;
		}
	}
	/*
	*检查是否唯一的值 key为字段名
	*/
	public function unique($key,$option) {
		$this->modname->newSQL();//清除条件
		if(isset($option['sql']))
		{
		  $this->modname->where($option['sql']);
		}
		if($this->modname->where($key."='".$this->modname->{get.$key()}."'")->count()>0){
		  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
		    $this->error[$key]=$this->error[$key]." 不是唯一的值。";
		  }
		  Return false;
		}else{
		  Return true;
		}
	}
	// 18位身份证校验码有效性检查 
	function idcard($key,$option){ 
		$idcard=$this->modname->data[$key];
		if(strlen($idcard)!=18){
          if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
		    $this->error[$key]=$this->error[$key]." 身份证长度不够。";
		  }
		   return false; 
		}
		$b= substr($idcard, 0, -1); 
		if(basevalid::idcard_verify_number($b) != strtoupper(substr($idcard,-1, 1))){ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." 身份证号码不正确。".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 身份证号码不正确。";
			} 
			return false; 
		}else{ 
			return true; 
		} 
	}
	// 电子邮件验证
	function email($key,$option){ 
		if(basevalid::email($this->modname->data[$key])){ 
			return true; 
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 不是邮件地址。";
			} 
			return false; 
		} 
	}
	// URL验证
	function url($key,$option){ 
		if(basevalid::url($this->modname->data[$key])){ 
			return true;
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 不是URL地址。";
			} 
			return false; 
		} 
	}
	// 手机号码
	function mobile($key,$option){ 
		if(basevalid::moblie($this->modname->data[$key])){ 
			return true;
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 不是手机号码。";
			} 
			return false; 
		} 
	}
	// 日期格式
	function date($key,$option){ 
		if(basevalid::date($this->modname->data[$key])){ 
			return true;
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 不是日期格式。";
			} 
			return false; 
		} 
	}
	// 日期时间格式
	function datetime($key,$option){ 
		if(strtotime($this->modname->data[$key])){ 
			return true;
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 不是日期时间格式。";
			} 
			return false; 
		} 
	}
	//全国固定电话验证
	//(\(\d{3,4}\)|\d{3,4}-|\s)?\d{8}
	function phone($key,$option) {
		if(preg_match("#(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,8}#",$this->modname->data[$key])){ 
			return true;
		}else{ 
			if(isset($option['error']))
			{
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			}else{
				$this->error[$key]=$this->error[$key]." 正填写正确的电话格式。";
			} 
			return false; 
		} 		
	}
	//使用模型查查是否在某个表中
	function modelrequest($key,$option) {
		$m=M($option['model']);
		$m->newSQL();//清除条件
		if(isset($option['sql']))
		{
		  $m->where($option['sql']);
		}
		if($m->where($option['request']."='".$this->modname->data[$key]."'")->count()==0){
		  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
		    $this->error[$key]=$this->error[$key]." 模型不存在的值。";
		  }
		  Return false;
		}else{
		  Return true;
		}		
	}
	//使用模型查查是否在某个表中,可能看看是否要替换值
	function modelkey($key,$option) {
		$m=M($option['model']);
		$m->newSQL();//清除条件
		if(isset($option['sql']))
		{
		  $m->where($option['sql']);
		}
		//如果存在方法则调用
		if(method_exists($m,$option['method']))
		{
		  //第一个参数提值,第二个是对象,返回值
		  $v=$m->{$option['method']}($this->modname->data[$key],$this->modname->getModel());
		  if(isset($option['values'])&&$option['values'])
		  { 
			//要替换值，看看是不是要给别的字段替换或更新自己
			if(isset($option['fields']))
			  $this->modname->data[$option['fields']]=$v;
			else
		      $this->modname->data[$key]=$v;
		  }elseif(empty($v)){
			  //如果不是替换值那么看看返回的值是否真假，也可能是做一个复杂的运算
		     if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 模型值有误。";
			  }
			  Return false;	
		  }
		  Return true;
		}else{
		  if(isset($option['error']))
		  {
			$this->error[$key]=$this->error[$key]." ".$option['error'];
		  }else{
		    $this->error[$key]=$this->error[$key]." 模型不存在的值。";
		  }
		  Return false;		  
		}	
	}
	//查询是否在某个数组键名中，存在返回值或替换自己或某个个字段
	function arraykey($key,$option) {
		if(is_array($option['arraykey'])&&isset($option['arraykey'][$this->modname->data[$key]]))
		{		  
		  if(isset($option['values'])&&$option['values'])
		  { 
			//要替换值，看看是不是要给别的字段替换或更新自己
			if(isset($option['fields']))
			  $this->modname->data[$option['fields']]=$option['arraykey'][$this->modname->data[$key]];
			else
		      $this->modname->data[$key]=$option['arraykey'][$this->modname->data[$key]];
		  }
		  Return true;
		}else{
		     if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不存在数组中。";
			  }
			  Return false;	
		}
	}
	//查询是否在某个数组中的值,并返回替换或验证
	function arrayvalue($key,$option) {
		if(is_array($option['arrayvalue']))
		{	
		  //返回键名
		  $v=array_search($option['arrayvalue'],$this->modname->data[$key]);
		  if($v)
		  {
		      if(isset($option['values'])&&$option['values'])
			  { 
				//要替换值，看看是不是要给别的字段替换或更新自己
				if(isset($option['fields']))
				  $this->modname->data[$option['fields']]=$v;
				else
				  $this->modname->data[$key]=$v;
			  }
			 Return true;
		  }else{
		     if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 不存在数组的值中。";
			  }
			  Return false;			    		  
		  }
		}else{
		     if(isset($option['error']))
			  {
				$this->error[$key]=$this->error[$key]." ".$option['error'];
			  }else{
				$this->error[$key]=$this->error[$key]." 验证来源数组有误，不是数组。";
			  }
			  Return false;	
		}
	}
}
?>