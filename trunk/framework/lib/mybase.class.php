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
		public $fix;
		public $role=array();  //我使用的身份
		public $group=array(); //我所在组
		public $grouprole=array(); //组的身份
		public $mygroupMar=array(); //我拥有管理的组
		public $mygroupOwn=array(); //属于我的组
		public $groupjc=array();	//可继承的角色(身份)
		public $acl=array();       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
		public $groupacl=array();  //组拥用的控制权限
		public $myacl=array();     //我的身份拥用的控制权限
		public $loginfaild=0;      //登录失败次数 如果超过这个数应该禁止IP登录几分种

		public function __construct() {
			if(isset($_SESSION['logined'])&&$_SESSION['logined']==true)
			{
			  $this->options['logined']=true;
			  $this->uid=$_SESSION['uid'];
			  $this->isadmin=$_SESSION['isadmin'];
			  $this->username=$_SESSION['username'];
			  $this->getACL();
			}else{
			  $this->options['logined']=false;
			}
					Return $this;
		}
		//设置用户名
		public function setName($name) {
		    $this->username=$name;
			$_SESSION['username']=$name;
			Return $this;
		}
		//是否管理员或超级管理员
		public function setAdmin($t=false) {
			$this->isadmin=$t;
			$_SESSION['isadmin']=$t;
			Return $this;
		}
		public function setUID($id) {
			$this->uid=$id;
			$_SESSION['uid']=$id;
					Return $this;
		}
		/*
		*设置权限
		*/
		public function setACL($acl=array()) {
					$this->role=$acl['role'];  //我使用的身份
					$this->group=$acl['group']; //我所在组
					$this->grouprole=$acl['grouprole']; //组的身份
					$this->mygroupMar=$acl['mygroupMar']; //我拥有管理的组
					$this->mygroupOwn=$acl['mygroupOwn']; //属于我的组
					$this->groupjc=$acl['groupjc'];	//可继承的角色(身份)
					$this->acl=$acl['acl'];       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
					$this->groupacl=$acl['groupacl'];  //组拥用的控制权限
					$this->myacl=$acl['myacl'];     //我的身份拥用的控制权限
					Return $this;
		}
		//更新数据库缓存文件
		public function saveACL() {
					$cache=array();//缓存权限
					$cache['role']=$this->role;  //我使用的身份
					$cache['group']=$this->group; //我所在组
					$cache['grouprole']=$this->grouprole; //组的身份
					$cache['mygroupMar']=$this->mygroupMar; //我拥有管理的组
					$cache['mygroupOwn']=$this->mygroupOwn; //属于我的组
					$cache['groupjc']=$this->groupjc;	//可继承的角色(身份)
					$cache['acl']=$this->acl;       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
					$cache['groupacl']=$this->groupacl;  //组拥用的控制权限
					$cache['myacl']=$this->myacl;     //我的身份拥用的控制权限
					$u=M($this->fix."user");
					$u->rbaccache=json_encode($cache);
					$u->where("uid='".$this->uid."'")->update('rbaccache');
					Return $this;
		}
		//清除数据库缓存权限
		public function clearACL() {
					$u=M($this->fix."user");
					$u->rbaccache=json_encode(array());
					$u->where("uid='".$this->uid."'")->update('rbaccache');
			Return $this;
		}
		/*
		*取得缓存权限
		*/
		public function getACL() {
		  if(is_numeric($this->uid))
		  {
			//cache::read($this->uid);
			//$this->acl=json_decode($_SESSION['acl'],true);
			$u=M($this->fix."user");
			$u->get($this->uid);
			$acl=json_decode($u->rbaccache,true);

			if(empty($acl['acl']))
			{
				$ur=M($this->fix."userrole");

				$this->role=$ur->where("uid='".$this->uid."'")->fetch()->getCol('roleid');
				$ur=M($this->fix."groupuser");
				//取得自己所在的组
				$this->group=$ur->select("gid,isMar")->where("uid='".$this->uid."'")->fetch()->getCol('gid');
				$this->groupjc=array();
				$this->groupacl=array();
				if(count($this->group)>0)
				{
					foreach($ur->getRecord() as $v)
					{
						if($v['isMar']=='Y')
							$this->mygroupMar[]=$v['gid'];//我能管理的组
					}
					$gr=M($this->fix."grouprole");
					$this->grouprole=$gr->whereIN("gid",implode(",",$this->group))->fetch()->getCol('roleid');
					if(count($this->grouprole)>0)
					foreach($gr->getRecord() as $v)
					{
						if($v['jicheng']=='Y')
							$this->groupjc[]=$v['roleid'];//可继承的角色
					}
					$g=M($this->fix."group");
					$this->mygroupOwn=$g->where("uid='".$this->uid."'")->fetch()->getCol('gid');
					$rg=M($this->fix."rbacgroup");
					//组拥有的权限
					$this->groupacl=$rg->whereIn("gid",implode(",",$this->group))->fetch()->getCol('rbacid');

				}
					$rr=M($this->fix."rbacrole");
					$this->role=array_unique(array_merge($this->role,$this->groupjc));//合并角色，并消除重复角色
					//角色拥有权限
					$this->myacl=$rr->whereIn("roleid",implode(",",$this->role))->fetch()->getCol('rbacid');					

					$this->acl=array_unique(array_merge($this->myacl,$this->groupacl));//合并权限

					$this->saveACL();//保存权限值
			}else{
			  //直接使用缓存
			  		$this->role=$acl['role'];  //我使用的身份
					$this->group=$acl['group']; //我所在组
					$this->grouprole=$acl['grouprole']; //组的身份
					$this->mygroupMar=$acl['mygroupMar']; //我拥有管理的组
					$this->mygroupOwn=$acl['mygroupOwn']; //属于我的组
					$this->groupjc=$acl['groupjc'];	//可继承的角色(身份)
					$this->acl=$acl['acl'];       //主动控制表 groupacl和myacl控制权限集合 内容是rbac的rbacid
					$this->groupacl=$acl['groupacl'];  //组拥用的控制权限
					$this->myacl=$acl['myacl'];     //我的身份拥用的控制权限
			}
			Return $this;
		  }
		  Return $this->logout();
		}
		//设置RBAC数据库链接分组可以为空如M("r.user");
		public function setRbacFix($fix=null) {
			$this->fix=$fix;
			if(substr($fix,-1)!='.')
			 $this->fix=$this->fix.".";			
			Return $this;
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
				if(isset($args[2])) $t=$args[2]; else $t=3600;
				setcookie($args[0],$args[1], time()+$t); 
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
			Return $this;
		}
		/*
		*设置登录状态
		*/
		public function setLogin($uid=0,$name=null) {
			$_SESSION['logined']=true;
			$this->options['logined']=true;
			$this->uid=$uid;
			$_SESSION['uid']=$uid;
			$_SESSION['username']=$name;
			$this->username=$name;
			Return $this;
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