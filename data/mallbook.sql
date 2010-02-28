-- phpMyAdmin SQL Dump
-- version 3.2.0
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2010 年 02 月 28 日 22:20
-- 服务器版本: 5.0.27
-- PHP 版本: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `mallbook`
--

-- --------------------------------------------------------

--
-- 表的结构 `booktype`
--

CREATE TABLE IF NOT EXISTS `booktype` (
  `bookid` int(6) NOT NULL auto_increment,
  `supplyid` int(8) NOT NULL,
  `classname` varchar(40) NOT NULL,
  `typeid` int(6) NOT NULL,
  PRIMARY KEY  (`bookid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=879 ;

--
-- 转存表中的数据 `booktype`
--

INSERT INTO `booktype` (`bookid`, `supplyid`, `classname`, `typeid`) VALUES
(1, 0, '七手八脚五手图片', 1),
(2, 0, '出租房子', 1),
(3, 0, '求租房子', 2);

-- --------------------------------------------------------

--
-- 表的结构 `supply`
--

CREATE TABLE IF NOT EXISTS `supply` (
  `supplyid` int(8) NOT NULL auto_increment,
  `typeid` int(6) NOT NULL,
  `bookid` int(8) NOT NULL,
  `userid` int(8) NOT NULL,
  `total` int(6) NOT NULL,
  `isView` enum('Y','N') NOT NULL default 'Y',
  `author` varchar(20) NOT NULL,
  `linkname` varchar(30) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `isPic` enum('Y','N') NOT NULL default 'N',
  `mobile` varchar(30) NOT NULL,
  `address` varchar(120) NOT NULL,
  `email` varchar(30) NOT NULL,
  `msn` varchar(40) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `title` varchar(120) NOT NULL,
  `dest` varchar(255) NOT NULL,
  `picurl` varchar(255) NOT NULL,
  `srcpri` decimal(3,2) NOT NULL,
  `outpri` decimal(3,2) NOT NULL,
  `per` decimal(1,1) NOT NULL,
  `press` varchar(120) NOT NULL,
  `adddate` date NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`supplyid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=275 ;

--
-- 转存表中的数据 `supply`
--

INSERT INTO `supply` (`supplyid`, `typeid`, `bookid`, `userid`, `total`, `isView`, `author`, `linkname`, `phone`, `isPic`, `mobile`, `address`, `email`, `msn`, `qq`, `title`, `dest`, `picurl`, `srcpri`, `outpri`, `per`, `press`, `adddate`, `content`) VALUES
(180, 9, 411, 0, 0, 'Y', '', '', '', 'N', '126666', '清上河', '', '', '', '', '', '', 0.00, 0.00, 0.0, '', '2010-02-26', '');

-- --------------------------------------------------------

--
-- 表的结构 `www_info`
--

CREATE TABLE IF NOT EXISTS `www_info` (
  `myid` int(8) NOT NULL auto_increment,
  `myname` varchar(30) NOT NULL,
  `myage` int(3) NOT NULL,
  `typeid` int(8) NOT NULL,
  PRIMARY KEY  (`myid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `www_info`
--

INSERT INTO `www_info` (`myid`, `myname`, `myage`, `typeid`) VALUES
(1, '胡老大', 56, 9);
