<?php
/*
*登录信息基本类
*权限表可以缓存数据，登录时候恢复。
*/
	class mybase {
		public $options=array();
        public $uid;
		public $username;
		public $isadmin;
		public $role=array();  //我使用的身份
		public $group=array(); //我所在组
		public $grouprole=array(); //组的身份
		public $mygroupMar=array(); //我拥有管理的组
		public $mygroupOwn=array(); //属于我的组
		public $acl=array();       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
		public $groupacl=array();  //组拥用的控制权限
		public $myacl=array();     //我的身份拥用的控制权限
		public $loginfaild=0;      //登录失败次数 如果超过这个数应该禁止IP登录几分种

		public function __construct() {
			session_start();
			if($_SESSION['logined']==true)
			{
			  $this->options['logined']=true;
			  $this->uid=$_SESSION['uid'];
			  $this->isadmin=$_SESSION['isadmin'];
			  $this->username=$_SESSION['username'];
			  $this->getACL();
			}else{
			  $this->options['logined']=false;
			}
		}
		/*
		*设置缓存文件
		*/
		public function setACL() {
		  //cache::write($this->uid,json_encode($this->role));
		}
		/*
		*取得缓存权限
		*/
		public function getACL() {
		  if(is_numeric($this->uid))
		  {
			//cache::read($this->uid);
			$this->acl=json_decode($_SESSION['acl'],true);
		  }
		}
		/*
		*会员属性
		*/
		public function __set($name,$value) {
			$this->data[$name]=$value;
			Return $this;
		}
		/*
		*会员属性
		*/
		public function __get($name) {
			Return $this->data[$name];
		}
		/*
		*_SESSION设置如果第二个参数为空则是返回值
		*/
        public function session() {
        	$args = func_get_args();
			if(isset($args[1]))
			{
				$_SESSION[$args[0]]=$args[1];
			    Return $this;
			}else{
			    Return $_SESSION[$args[0]];
			}
        }
		/*
		*_COOKIE设置如果第二个参数为空则是返回值
		*/
        public function cookie() {
        	$args = func_get_args();
			if(isset($args[1]))
			{
				$_COOKIE[$args[0]]=$args[1];
			    Return $this;
			}else{
			    Return $_COOKIE[$args[0]];
			}
        }
		/***
		*退出登录
		*
		***/
		public function logout() {
						unset($_SESSION['logined']);
			unset($this->options['logined']);
		}
		/*
		*设置登录状态
		*/
		public function setLogin() {
			$_SESSION['logined']=true;
			$this->options['logined']=true;
		}
		/*
		*检查登录状态
		*/
		public function isLogin()
		{
		  Return $this->options['logined']?true:false;
		}
		public function array_multi2single($array)
		{
			static $result_array=array();
			foreach($array as $value)
			{
				if(is_array($value))
				{
					$this->array_multi2single($value);
				}
				else  
					$result_array[]=$value;
			}
			return $result_array;
		}
	} 
?>