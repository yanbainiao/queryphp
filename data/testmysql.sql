-- phpMyAdmin SQL Dump
-- version 3.2.0
-- http://www.phpmyadmin.net
--
-- 主机: localhost:3306
-- 生成日期: 2010 年 02 月 21 日 09:07
-- 服务器版本: 5.0.27
-- PHP 版本: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `mallbook`
--

-- --------------------------------------------------------

--
-- 表的结构 `booktype`
--

CREATE TABLE IF NOT EXISTS `booktype` (
  `bookid` int(6) NOT NULL auto_increment,
  `classname` varchar(40) NOT NULL,
  `typeid` int(6) NOT NULL,
  PRIMARY KEY  (`bookid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `booktype`
--

INSERT INTO `booktype` (`bookid`, `classname`, `typeid`) VALUES
(1, '七手八脚五手图片', 1),
(2, '出租房子', 2),
(3, '求租房子', 3);

-- --------------------------------------------------------

--
-- 表的结构 `supply`
--

CREATE TABLE IF NOT EXISTS `supply` (
  `supplyid` int(8) NOT NULL auto_increment,
  `typeid` int(6) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `supply`
--

INSERT INTO `supply` (`supplyid`, `typeid`, `userid`, `total`, `isView`, `author`, `linkname`, `phone`, `isPic`, `mobile`, `address`, `email`, `msn`, `qq`, `title`, `dest`, `picurl`, `srcpri`, `outpri`, `per`, `press`, `adddate`, `content`) VALUES
(1, 1, 0, 0, 'Y', '', '', '', '', '', '', '', '', '', 'ttttttttttttttttttt', '', '', 0.00, 0.00, 0.0, '', '2007-10-10', ''),
(2, 1, 0, 0, 'Y', '', '', '', '', '', '', '', '', '', 'aaa', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', 'aaaaaaaaaaaaaaa'),
(3, 1, 0, 0, 'Y', '', '', '', '', '', '重庆沙坪坝', '', '', '', 'ssssssss', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', 'ssssssssssss'),
(4, 2, 0, 0, 'Y', '', '', '', '', '', '天津河东', '', '', '', '标题', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', '内容'),
(5, 3, 0, 0, 'Y', '', '', '', 'N', '', '上海长宁', '', '', '', 'dgfsg', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', 'sssssssss'),
(6, 1, 0, 0, 'Y', '', '', '', 'N', '', '山西忻州', '', '', '', 'sdgfsd', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', 'sdfgs'),
(7, 1, 0, 0, 'Y', '', '', '', 'N', '', '', '', '', '', 'sdfgsd', '', 'http://localhost/book/upimages/2007-10-06/1191685139830.jpg', 0.00, 0.00, 0.0, '', '2007-10-06', 'sdgfs'),
(8, 1, 0, 0, 'Y', '', '', '', 'N', '', '天津河西', '', '', '', 'asdfa', '', '', 0.00, 0.00, 0.0, '', '2007-10-06', 'fasdfa'),
(9, 1, 0, 0, 'Y', '', '', '', 'N', '', '', '', '', '', 'dfgsdgfsd', '', '', 0.00, 0.00, 0.0, '', '2007-10-07', 'fgsdfgs'),
(10, 1, 0, 0, 'Y', '', '', '', 'N', '2421141', '上海徐汇', '', '', '', 'sdfaf', '', '', 0.00, 0.00, 0.0, '', '2007-10-07', '1234142121'),
(11, 1, 0, 0, 'Y', '', '', '', 'N', '234141', '河北张家口', '', '', '', 'asdfa', '', '', 0.00, 0.00, 0.0, '', '2007-10-07', '14114143123'),
(12, 1, 0, 0, 'Y', '', '', '', 'N', '241414', '内蒙古阿拉善盟', '', '', '', 'asdfa', '', '', 0.00, 0.00, 0.0, '', '2007-10-07', '123412341234');
