-- phpMyAdmin SQL Dump
-- version 3.2.0
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2010 年 04 月 12 日 21:04
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
  `roleid` int(8) NOT NULL COMMENT '身份ID',
  `rbacid` int(8) NOT NULL COMMENT '模型权限ID',
  `start` date NOT NULL COMMENT '日期开始',
  `end` date NOT NULL COMMENT '日期结束',
  PRIMARY KEY  (`aclid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `acl`
--


-- --------------------------------------------------------

--
-- 表的结构 `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `gid` int(8) NOT NULL auto_increment,
  `groupname` varchar(30) NOT NULL COMMENT '组名字',
  `uid` int(8) NOT NULL COMMENT '组属管理员',
  `dest` varchar(256) NOT NULL COMMENT '说明',
  PRIMARY KEY  (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户组' AUTO_INCREMENT=1 ;

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
  `jicheng` enum('Y','N') NOT NULL COMMENT '是否继承给组员',
  PRIMARY KEY  (`grid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组身份表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `grouprole`
--


-- --------------------------------------------------------

--
-- 表的结构 `groupuser`
--

CREATE TABLE IF NOT EXISTS `groupuser` (
  `guid` int(8) NOT NULL auto_increment,
  `gid` int(8) NOT NULL COMMENT '组ID',
  `uid` int(8) NOT NULL COMMENT '用户ID',
  `adduid` int(8) NOT NULL COMMENT '谁添加的',
  `isMar` enum('Y','N') NOT NULL COMMENT '是否组管理员',
  PRIMARY KEY  (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='组成员表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `groupuser`
--


-- --------------------------------------------------------

--
-- 表的结构 `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `projectid` int(8) NOT NULL auto_increment,
  `projectname` varchar(30) NOT NULL COMMENT '项目名或系统名',
  `dest` varchar(256) NOT NULL COMMENT '说明',
  PRIMARY KEY  (`projectid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='项目名' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `project`
--


-- --------------------------------------------------------

--
-- 表的结构 `rbac`
--

CREATE TABLE IF NOT EXISTS `rbac` (
  `rbacid` int(8) NOT NULL auto_increment,
  `projectid` int(8) NOT NULL COMMENT '项目ID',
  `parentid` int(8) NOT NULL COMMENT '父ID',
  `model` varchar(30) NOT NULL COMMENT 'router类名',
  `name` varchar(30) NOT NULL COMMENT '模型名router',
  `level` tinyint(3) NOT NULL COMMENT '水平0无要求1登录4管理员',
  `isAll` enum('Y','N') NOT NULL COMMENT '整个模型使用本设置',
  `rolemap` text NOT NULL COMMENT '组合对象集合',
  `groupmap` text NOT NULL COMMENT '组访问权限',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模型设置表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `rbac`
--


-- --------------------------------------------------------

--
-- 表的结构 `rbacgroup`
--

CREATE TABLE IF NOT EXISTS `rbacgroup` (
  `rgid` int(8) NOT NULL auto_increment,
  `rbacid` int(8) NOT NULL COMMENT '资源权限id',
  `gid` int(8) NOT NULL COMMENT '组id',
  PRIMARY KEY  (`rgid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rbac允许组表就是包含意思' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `rbacgroup`
--


-- --------------------------------------------------------

--
-- 表的结构 `rbacrole`
--

CREATE TABLE IF NOT EXISTS `rbacrole` (
  `aclid` int(8) NOT NULL auto_increment,
  `roleid` int(8) NOT NULL COMMENT '身份ID',
  `rbacid` int(8) NOT NULL COMMENT '模型权限ID',
  PRIMARY KEY  (`aclid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='rbac允许身份就是包含意思' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `rbacrole`
--


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='身份表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `role`
--


-- --------------------------------------------------------

--
-- 表的结构 `supperadmin`
--

CREATE TABLE IF NOT EXISTS `supperadmin` (
  `supperid` int(8) NOT NULL auto_increment,
  `adminname` varchar(30) NOT NULL COMMENT '管理员用户名',
  `adminpwd` varchar(32) NOT NULL COMMENT '管理员密码',
  `isMar` enum('Y','N') NOT NULL COMMENT '是否最高超级管理员',
  `linkname` varchar(30) NOT NULL COMMENT '管理员名字',
  PRIMARY KEY  (`supperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='超级管理员' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `supperadmin`
--


-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(8) NOT NULL auto_increment,
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '用户密码',
  `realname` varchar(30) NOT NULL COMMENT '用户名字显示用',
  `email` varchar(30) NOT NULL COMMENT '邮件',
  `isAction` enum('Y','N') NOT NULL COMMENT '是否激活',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `user`
--


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户身份表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `userrole`
--

