-- phpMyAdmin SQL Dump
-- version 3.2.0
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2011 年 07 月 12 日 16:07
-- 服务器版本: 5.0.27
-- PHP 版本: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `rbac`
--

-- --------------------------------------------------------

--
-- 表的结构 `acl`
--

CREATE TABLE IF NOT EXISTS `acl` (
  `aclid` int(8) NOT NULL auto_increment,
  `model` varchar(60) NOT NULL COMMENT 'Router模型',
  `method` varchar(60) NOT NULL COMMENT '方法',
  `title` varchar(60) NOT NULL COMMENT '权限名称',
  `aclpath` varchar(160) NOT NULL COMMENT '模型文件url地址',
  `start` date NOT NULL COMMENT '日期开始',
  `end` date NOT NULL COMMENT '日期结束',
  `paclid` int(8) NOT NULL COMMENT '父类ID',
  PRIMARY KEY  (`aclid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `acl`
--

INSERT INTO `acl` (`aclid`, `model`, `method`, `title`, `aclpath`, `start`, `end`, `paclid`) VALUES
(12, 'crm', '', '订单管理', 'project/rbac', '2010-09-23', '2010-09-23', 0),
(14, 'admin', '', '公司后台管理', 'project/rbac', '2010-12-13', '2010-12-13', 0);

-- --------------------------------------------------------

--
-- 表的结构 `aclmethod`
--

CREATE TABLE IF NOT EXISTS `aclmethod` (
  `caclid` int(8) NOT NULL auto_increment,
  `aclid` int(8) NOT NULL COMMENT '父Router类',
  `title` varchar(60) NOT NULL COMMENT '权限名字',
  `method` varchar(60) NOT NULL COMMENT '方法名',
  PRIMARY KEY  (`caclid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='acl访法表' AUTO_INCREMENT=102 ;

--
-- 转存表中的数据 `aclmethod`
--

INSERT INTO `aclmethod` (`caclid`, `aclid`, `title`, `method`) VALUES
(1, 4, '数据列表', 'index'),
(2, 4, '方法名称', 'addpost'),
(3, 4, '', 'setopen'),
(4, 4, '', 'delete'),
(23, 6, '', 'index'),
(6, 4, '', 'editpost'),
(7, 4, '', 'setitemuse'),
(8, 4, '', 'setitemone'),
(9, 4, '', 'setoneopen'),
(10, 4, '', 'setmembertest'),
(24, 6, '', 'first'),
(12, 5, '', 'index'),
(13, 5, '', 'addpost'),
(14, 5, '', 'setopen'),
(15, 5, '', 'delete'),
(16, 5, '', 'edit'),
(17, 5, '', 'editpost'),
(18, 5, '', 'setitemuse'),
(19, 5, '', 'setitemone'),
(20, 5, '', 'setoneopen'),
(21, 5, '', 'setmembertest'),
(22, 5, '', 'quanding'),
(25, 6, '', 'second'),
(26, 6, '', 'firstadd'),
(27, 6, '', 'secondadd'),
(28, 6, '', 'secondaddpost'),
(29, 6, '', 'biaolist'),
(30, 6, '', 'firstaddpost'),
(31, 6, '', 'ajaxgetfirst'),
(32, 6, '', 'ajaxgetsecord'),
(33, 6, '', 'editsecond'),
(34, 6, '', 'secondeditpost'),
(35, 6, '', 'editfirst'),
(36, 6, '', 'firsteditpost'),
(37, 6, '', 'secondpinyu'),
(38, 6, '', 'deletesecondpinyupost'),
(39, 6, '', 'addsecondpingyu'),
(40, 6, '', 'editeditpost'),
(41, 6, '', 'deletesecond'),
(42, 6, '', 'deletefirst'),
(43, 6, '', 'secondajax'),
(44, 6, '', 'onejianyi'),
(45, 6, '', 'qingjing'),
(46, 6, '', 'deleteqingjing'),
(47, 6, '', 'qingjingedit'),
(48, 6, '', 'qingjingset'),
(49, 6, '', 'qingjingsetonoff'),
(50, 6, '', 'qingjingadd'),
(51, 6, '', 'qingjingpost'),
(52, 6, '', 'qingjingeditpost'),
(53, 6, '', 'mianshi'),
(54, 6, '', 'mianshiadd'),
(55, 6, '', 'deletemianshi'),
(56, 6, '', 'mianshipost'),
(57, 6, '', 'mianshieditpost'),
(58, 6, '', 'mianshiset'),
(59, 6, '', 'mianshisetonoff'),
(60, 6, '', 'mianshiedit'),
(61, 6, '', 'addonepingyu'),
(62, 6, '', 'deleteonejianpost'),
(63, 6, '', 'editonejianpost'),
(64, 7, '', 'index'),
(65, 7, '', 'add'),
(66, 7, '', 'addpost'),
(67, 7, '', 'delete'),
(68, 7, '', 'addshiti'),
(69, 7, '', 'shitilist'),
(70, 7, '', 'answerlist'),
(71, 7, '', 'addanswer'),
(72, 7, '', 'editanswer'),
(73, 7, '', 'edit'),
(74, 7, '', 'editpost'),
(75, 8, '', 'index'),
(76, 8, '', 'post'),
(77, 8, '', 'reg'),
(78, 8, '', 'regpost'),
(79, 8, '', 'editinfo'),
(80, 8, '', 'login'),
(81, 8, '', 'loginpost'),
(82, 8, '', 'logout'),
(86, 10, '', 'index'),
(87, 12, '未处理订单', 'index'),
(88, 12, '添加订单', 'addform'),
(89, 12, '订单列表', 'formlist'),
(90, 14, '', 'index'),
(91, 14, '', '_pre'),
(92, 14, '', 'loginpost'),
(93, 14, '', 'logout'),
(94, 14, '', 'main'),
(95, 14, '', 'siteleft'),
(96, 14, '', 'right'),
(97, 14, '销售管理', 'xiaoshou'),
(98, 14, '总监管理', 'zongjian'),
(99, 14, '财务管理', 'caiwu'),
(100, 14, '试题显示', 'shiti'),
(101, 14, '客户显示', 'kefu');

-- --------------------------------------------------------

--
-- 表的结构 `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `gid` int(8) NOT NULL auto_increment,
  `pid` int(8) NOT NULL COMMENT '项目表',
  `groupname` varchar(30) NOT NULL COMMENT '组名字',
  `uid` int(8) NOT NULL COMMENT '组属管理员',
  `dest` varchar(256) NOT NULL COMMENT '说明',
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户组' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `group`
--


-- --------------------------------------------------------

--
-- 表的结构 `grouprole`
--

CREATE TABLE IF NOT EXISTS `grouprole` (
  `grid` int(8) NOT NULL auto_increment,
  `gid` int(8) NOT NULL COMMENT '组ID',
  `roleid` int(8) NOT NULL COMMENT '身份ID',
  `jicheng` enum('Y','N') NOT NULL default 'N' COMMENT '是否继承给组员',
  PRIMARY KEY  (`grid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='组身份表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `grouprole`
--

INSERT INTO `grouprole` (`grid`, `gid`, `roleid`, `jicheng`) VALUES
(4, 2, 3, 'N'),
(6, 3, 3, 'Y'),
(7, 3, 2, 'Y'),
(9, 2, 4, 'Y'),
(10, 2, 5, 'Y');

-- --------------------------------------------------------

--
-- 表的结构 `groupuser`
--

CREATE TABLE IF NOT EXISTS `groupuser` (
  `guid` int(8) NOT NULL auto_increment,
  `gid` int(8) NOT NULL COMMENT '组ID',
  `uid` int(8) NOT NULL COMMENT '用户ID',
  `adduid` int(8) NOT NULL COMMENT '谁添加的',
  `isMar` enum('Y','N') NOT NULL default 'N' COMMENT '是否组管理员',
  PRIMARY KEY  (`guid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='组成员表' AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `groupuser`
--

INSERT INTO `groupuser` (`guid`, `gid`, `uid`, `adduid`, `isMar`) VALUES
(2, 2, 58, 0, 'Y'),
(3, 3, 55, 0, 'Y'),
(4, 3, 51, 0, 'Y'),
(5, 3, 50, 0, 'Y'),
(10, 2, 2, 0, 'N'),
(9, 2, 3, 0, 'N'),
(13, 2, 50, 0, 'N'),
(14, 7, 50, 0, 'N'),
(15, 7, 6, 0, 'Y'),
(16, 3, 44, 0, 'N'),
(17, 2, 44, 0, 'N'),
(18, 7, 44, 0, 'N'),
(19, 3, 45, 0, 'N'),
(20, 2, 45, 0, 'N'),
(21, 7, 45, 0, 'N'),
(22, 7, 51, 0, 'N'),
(23, 2, 51, 0, 'N'),
(24, 3, 1, 0, 'N'),
(25, 7, 1, 0, 'N'),
(26, 2, 1, 0, 'N');

-- --------------------------------------------------------

--
-- 表的结构 `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `projectid` int(8) NOT NULL auto_increment,
  `projectname` varchar(30) NOT NULL COMMENT '项目名或系统名',
  `loginname` varchar(40) NOT NULL,
  `loginpwd` varchar(40) NOT NULL,
  `province` int(4) NOT NULL,
  `business` int(4) NOT NULL,
  `linkname` varchar(60) NOT NULL,
  `job_bm` varchar(60) NOT NULL,
  `job_gw` varchar(60) NOT NULL,
  `iphone1` varchar(6) NOT NULL,
  `iphone2` bigint(10) NOT NULL,
  `iphone3` varchar(5) NOT NULL,
  `mobile` varchar(14) NOT NULL,
  `jinjiipone` varchar(120) NOT NULL,
  `jinjilinks` varchar(120) NOT NULL,
  `email` varchar(40) NOT NULL,
  `regaddress` varchar(80) NOT NULL,
  `zipnum` int(6) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `servericname` varchar(30) NOT NULL COMMENT '服务代表',
  `servicetype` int(3) NOT NULL COMMENT '服务类型',
  `dest` varchar(256) NOT NULL COMMENT '说明',
  `isaction` enum('Y','N') NOT NULL default 'Y' COMMENT '是否激活代理',
  PRIMARY KEY  (`projectid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='项目名' AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `project`
--

INSERT INTO `project` (`projectid`, `projectname`, `loginname`, `loginpwd`, `province`, `business`, `linkname`, `job_bm`, `job_gw`, `iphone1`, `iphone2`, `iphone3`, `mobile`, `jinjiipone`, `jinjilinks`, `email`, `regaddress`, `zipnum`, `price`, `servericname`, `servicetype`, `dest`, `isaction`) VALUES
(7, '公司名称2', '', '', 0, 0, '', '', '', '0', 0, '0', '', '', '', '', '', 0, 0.00, '', 0, '', 'Y'),
(3, '公司名称4', 'aaa', '47bce5c74f589f4867dbd57e9ca9f808', 0, 0, '联系人', '职务', '', '10', 12345678, '0', '123456789', '紧急联系电话', '紧急联系人', 'sss@aaf.com', '注册地址', 888888, 478.00, '联系人', 0, '', 'Y'),
(11, 'asdfasdf', 'aaaaaa', '0b4e7a0e5fe84ad35fb5f95b9ceeac79', 0, 0, '', '', '', '', 0, '', '', '', '', '', '', 0, 0.00, '', 0, '', 'Y'),
(12, 'aaaaa', 'ggg', '65ba841e01d6db7733e90a5b7f9e6f80', 0, 0, 'werwer', '', '', 'asdf', 234234, '243', '234', '', '', '', '', 0, 0.00, '', 0, '', 'Y'),
(13, 'asdfasdfasdf', 'fff', '47bce5c74f589f4867dbd57e9ca9f808', 0, 0, 'asdf', '', '', '2342', 23424, '3234', '', '', '', '', '', 0, 0.00, '', 0, '', 'Y');

-- --------------------------------------------------------

--
-- 表的结构 `rbac`
--

CREATE TABLE IF NOT EXISTS `rbac` (
  `rbacid` int(8) NOT NULL auto_increment,
  `projectid` int(8) NOT NULL COMMENT '项目ID',
  `aclid` int(8) NOT NULL COMMENT '控制资源ID',
  `parentid` int(8) NOT NULL COMMENT '父ID',
  `model` varchar(30) NOT NULL COMMENT 'router类名',
  `name` varchar(30) NOT NULL COMMENT '模型名router',
  `method` varchar(60) NOT NULL COMMENT '方法权限',
  `level` int(8) NOT NULL COMMENT '水平0无要求1登录4管理员',
  `isAll` enum('Y','N') NOT NULL COMMENT '整个模型使用本设置',
  `rolemap` text NOT NULL COMMENT '组合对象集合',
  `groupmap` text NOT NULL COMMENT '组访问权限',
  `disablerole` text NOT NULL,
  `timestart` date NOT NULL COMMENT '日期开始',
  `timeend` date NOT NULL COMMENT '日期结束',
  `daystart` int(2) NOT NULL COMMENT '一天开始',
  `dayend` int(2) NOT NULL COMMENT '一天结束',
  `weekstart` tinyint(1) NOT NULL COMMENT '周开始',
  `weekend` tinyint(1) NOT NULL COMMENT '周结束',
  `loginnum` int(8) NOT NULL COMMENT '登录次数',
  `password` varchar(32) NOT NULL COMMENT '密码或obj字符',
  `objmodel` varchar(30) NOT NULL COMMENT '模型对象类',
  `field` varchar(30) NOT NULL COMMENT '模型对像字段',
  PRIMARY KEY  (`rbacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='模型设置表' AUTO_INCREMENT=33 ;

--
-- 转存表中的数据 `rbac`
--

INSERT INTO `rbac` (`rbacid`, `projectid`, `aclid`, `parentid`, `model`, `name`, `method`, `level`, `isAll`, `rolemap`, `groupmap`, `disablerole`, `timestart`, `timeend`, `daystart`, `dayend`, `weekstart`, `weekend`, `loginnum`, `password`, `objmodel`, `field`) VALUES
(10, 0, 8, 0, 'member', '被权限设置', '', 31, 'N', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(11, 0, 8, 10, 'member', '查看自己', 'index', 29, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(12, 0, 8, 10, 'member', '编辑自己', 'editinfo', 31, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(13, 0, 8, 10, 'member', '会员登录', 'login', 0, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(14, 0, 8, 10, 'member', '注册提交', 'post', 29, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(15, 0, 10, 0, 'default', '财务管理', '', 29, 'N', '["3"]', '["3","7"]', '["2"]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(16, 0, 10, 15, 'default', '查看财务', 'index', 29, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(17, 0, 8, 10, 'member', '退出', 'logout', 0, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(19, 0, 12, 0, 'crm', '订单管理', '', 1, 'N', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(20, 0, 12, 19, 'crm', '未处理单', 'index', 1, 'N', '["2","3","5"]', '["2","7"]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(21, 0, 12, 19, 'crm', '添加订单', 'addform', 1, 'N', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(22, 0, 12, 19, 'crm', '订单查看', 'formlist', 1, 'N', '["5"]', '["7"]', '["5"]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(23, 0, 14, 0, 'admin', '公司管理', '', 9, 'N', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(24, 0, 14, 23, 'admin', '销售显示', 'xiaoshou', 9, 'Y', '["5"]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(25, 0, 14, 23, 'admin', '财务管理', 'caiwu', 9, 'Y', '["4"]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(26, 0, 14, 23, 'admin', '销售显示', 'zongjian', 9, 'Y', '["5"]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(27, 0, 14, 23, 'admin', '框架', 'main', 1, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(28, 0, 14, 23, 'admin', '导航', 'siteleft', 1, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(29, 0, 14, 23, 'admin', '右边', 'index', 1, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(30, 0, 14, 23, 'admin', '右边', 'right', 1, 'Y', '[]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(31, 0, 14, 23, 'admin', '试题权限', 'shiti', 9, 'Y', '["6"]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', ''),
(32, 0, 14, 23, 'admin', '客服权限', 'kefu', 9, 'Y', '["7"]', '[]', '[]', '0000-00-00', '0000-00-00', 0, 0, 0, 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `rbacgroup`
--

CREATE TABLE IF NOT EXISTS `rbacgroup` (
  `rgid` int(8) NOT NULL auto_increment,
  `rbacid` int(8) NOT NULL COMMENT '资源权限id',
  `gid` int(8) NOT NULL COMMENT '组id',
  PRIMARY KEY  (`rgid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='rbac允许组表就是包含意思' AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `rbacgroup`
--

INSERT INTO `rbacgroup` (`rgid`, `rbacid`, `gid`) VALUES
(1, 5, 7),
(2, 11, 7),
(3, 13, 7),
(4, 17, 7),
(5, 5, 3),
(6, 11, 3),
(7, 13, 3),
(8, 17, 3),
(9, 8, 2),
(10, 9, 2),
(11, 15, 2),
(12, 16, 2),
(13, 19, 2),
(14, 21, 2);

-- --------------------------------------------------------

--
-- 表的结构 `rbacrole`
--

CREATE TABLE IF NOT EXISTS `rbacrole` (
  `aclid` int(8) NOT NULL auto_increment,
  `roleid` int(8) NOT NULL COMMENT '身份ID',
  `rbacid` int(8) NOT NULL COMMENT '模型权限ID',
  PRIMARY KEY  (`aclid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='rbac允许身份就是包含意思' AUTO_INCREMENT=108 ;

--
-- 转存表中的数据 `rbacrole`
--

INSERT INTO `rbacrole` (`aclid`, `roleid`, `rbacid`) VALUES
(92, 4, 2),
(89, 2, 12),
(95, 4, 14),
(32, 2, 8),
(88, 2, 10),
(79, 3, 6),
(93, 4, 3),
(90, 2, 9),
(33, 2, 7),
(91, 3, 5),
(78, 3, 4),
(94, 4, 1),
(96, 5, 10),
(97, 5, 12),
(98, 5, 13),
(99, 5, 17),
(100, 5, 2),
(101, 5, 1),
(102, 5, 23),
(103, 5, 26),
(105, 5, 27),
(106, 5, 28),
(107, 5, 30);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `roleid` int(8) NOT NULL auto_increment,
  `rolename` varchar(30) NOT NULL COMMENT '身份名字',
  `gid` int(8) NOT NULL COMMENT '组ID',
  `isMar` enum('Y','N') NOT NULL COMMENT '是否管理员',
  `dest` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY  (`roleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='身份表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`roleid`, `rolename`, `gid`, `isMar`, `dest`) VALUES
(6, '试题管理', 0, 'Y', ''),
(4, '财务管理', 0, 'Y', ''),
(5, '销售总监', 0, 'Y', '组说明'),
(7, '客户管理', 0, 'Y', '');

-- --------------------------------------------------------

--
-- 表的结构 `supperadmin`
--

CREATE TABLE IF NOT EXISTS `supperadmin` (
  `supperid` int(8) NOT NULL auto_increment,
  `adminname` varchar(30) NOT NULL COMMENT '管理员用户名',
  `adminpwd` varchar(32) NOT NULL COMMENT '管理员密码',
  `isMar` enum('Y','N') NOT NULL default 'N' COMMENT '是否最高超级管理员',
  `linkname` varchar(30) NOT NULL COMMENT '管理员名字',
  PRIMARY KEY  (`supperid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='超级管理员' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `supperadmin`
--

INSERT INTO `supperadmin` (`supperid`, `adminname`, `adminpwd`, `isMar`, `linkname`) VALUES
(1, 'root', 'e10adc3949ba59abbe56e057f20f883e', 'Y', '超级管理员');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(8) NOT NULL auto_increment,
  `projectid` int(8) NOT NULL COMMENT '公司名字',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `realname` varchar(30) NOT NULL COMMENT '用户名字显示用',
  `email` varchar(30) NOT NULL COMMENT '邮件',
  `age` int(2) NOT NULL COMMENT '年龄',
  `job` varchar(60) NOT NULL COMMENT '职务',
  `sex` int(1) NOT NULL COMMENT '性别',
  `xueli` int(2) NOT NULL COMMENT '学历',
  `isAction` enum('Y','N') NOT NULL default 'Y' COMMENT '是否激活',
  `isMar` enum('Y','N') NOT NULL default 'N' COMMENT '是否公司管理者',
  `rbaccache` text NOT NULL COMMENT '权限缓存',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=75 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`uid`, `projectid`, `username`, `password`, `realname`, `email`, `age`, `job`, `sex`, `xueli`, `isAction`, `isMar`, `rbaccache`) VALUES
(59, 7, 'lisi333', '96e79218965eb72c92a549dd5a330112', '伍德里', 'aaaa@aaf.com', 88, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(58, 8, 'zhangsan5760', '96e79218965eb72c92a549dd5a330112', '张三风', '', 0, '副总经理', 1, 0, 'Y', 'Y', '[]'),
(57, 8, 'lisi4173', '96e79218965eb72c92a549dd5a330112', '里斯', '', 0, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(56, 8, 'zhangsan4018', '96e79218965eb72c92a549dd5a330112', '张三', '', 0, '总经理', 1, 0, 'Y', 'Y', '[]'),
(55, 8, 'qiushi4047', '96e79218965eb72c92a549dd5a330112', '求实', '', 0, '人事经理', 1, 0, 'Y', 'Y', '[]'),
(54, 8, 'wanger1578', '96e79218965eb72c92a549dd5a330112', '王尔', '', 0, '财务经理', 1, 0, 'Y', 'Y', '[]'),
(53, 8, 'lisi2039', '96e79218965eb72c92a549dd5a330112', '伍德里', '', 0, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(52, 8, 'zhangsan8467', '96e79218965eb72c92a549dd5a330112', '张三风', '', 0, '副总经理', 1, 0, 'Y', 'Y', '[]'),
(51, 8, 'lisi4685', '96e79218965eb72c92a549dd5a330112', '里斯', '', 0, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(50, 8, 'zhangsan9516', '96e79218965eb72c92a549dd5a330112', '张三', '', 0, '总经理', 1, 0, 'Y', 'Y', '[]'),
(49, 8, 'qiushi', '96e79218965eb72c92a549dd5a330112', '求实', '', 0, '人事经理', 1, 0, 'Y', 'Y', '[]'),
(48, 8, 'wanger', '96e79218965eb72c92a549dd5a330112', '王尔', '', 0, '财务经理', 1, 0, 'Y', 'Y', '[]'),
(47, 8, 'lisi7579', '96e79218965eb72c92a549dd5a330112', '伍德里', '', 0, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(46, 8, 'zhangsan7278', '96e79218965eb72c92a549dd5a330112', '张三风', '', 0, '副总经理', 1, 0, 'Y', 'Y', '[]'),
(45, 8, 'lisi', '96e79218965eb72c92a549dd5a330112', '里斯', '', 0, '副总经理', 0, 0, 'Y', 'Y', '[]'),
(44, 8, 'zhangsan', '96e79218965eb72c92a549dd5a330112', '张三', '', 0, '总经理', 1, 0, 'Y', 'Y', '[]'),
(73, 0, 'wwww', '74b87337454200d4d33f80c4663dc5e5', 'asdf', '', 45, 'asdfas', 1, 0, 'Y', 'N', '[]'),
(74, 13, 'ggg', '47bce5c74f589f4867dbd57e9ca9f808', 'asdfa', '', 0, 'asdf', 1, 0, 'Y', 'N', '[]'),
(1, 3, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'queryphp', 'sdfa@afa.com', 0, '', 0, 0, 'Y', 'Y', '[]');

-- --------------------------------------------------------

--
-- 表的结构 `userrole`
--

CREATE TABLE IF NOT EXISTS `userrole` (
  `urid` int(8) NOT NULL auto_increment,
  `uid` int(8) NOT NULL COMMENT '用户id',
  `roleid` int(8) NOT NULL COMMENT '身份ID',
  `timestart` date NOT NULL COMMENT '日期开始',
  `timeend` date NOT NULL COMMENT '日期结束',
  PRIMARY KEY  (`urid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户身份表' AUTO_INCREMENT=68 ;

--
-- 转存表中的数据 `userrole`
--

INSERT INTO `userrole` (`urid`, `uid`, `roleid`, `timestart`, `timeend`) VALUES
(52, 50, 4, '2010-09-19', '2010-09-19'),
(50, 50, 3, '2010-09-19', '2010-09-19'),
(48, 6, 2, '2010-09-19', '2010-09-19'),
(47, 6, 3, '2010-09-19', '2010-09-19'),
(67, 1, 6, '2010-12-13', '2010-12-13'),
(51, 50, 2, '2010-09-19', '2010-09-19'),
(53, 44, 3, '2010-09-23', '2010-09-23'),
(54, 44, 2, '2010-09-23', '2010-09-23'),
(55, 44, 4, '2010-09-23', '2010-09-23'),
(56, 44, 5, '2010-09-23', '2010-09-23'),
(57, 51, 3, '2010-09-23', '2010-09-23'),
(58, 51, 2, '2010-09-23', '2010-09-23'),
(59, 51, 4, '2010-09-23', '2010-09-23'),
(60, 51, 5, '2010-09-23', '2010-09-23'),
(66, 1, 4, '2010-12-13', '2010-12-13'),
(65, 58, 5, '2010-09-26', '2010-09-26');
