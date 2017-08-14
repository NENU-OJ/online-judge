
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nenuoj`
--

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

DROP TABLE IF EXISTS `problem`;
CREATE TABLE `problem` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `description` longtext NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL,
  `sample_in` text NOT NULL,
  `sample_out` text NOT NULL,
  `total_submit` int(10) unsigned NOT NULL,
  `total_ac` int(10) unsigned NOT NULL,
  `total_wa` int(10) unsigned NOT NULL,
  `total_re` int(10) unsigned NOT NULL,
  `total_ce` int(10) unsigned NOT NULL,
  `total_tle` int(10) unsigned NOT NULL,
  `total_mle` int(10) unsigned NOT NULL,
  `total_pe` int(10) unsigned NOT NULL,
  `total_ole` int(10) unsigned NOT NULL,
  `total_rf` int(10) unsigned NOT NULL,
  `is_special_judge` smallint(6) NOT NULL DEFAULT '0' COMMENT 'have special judger?',
  `time_limit` int(10) unsigned NOT NULL,
  `memory_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `hint` text NOT NULL,
  `source` varchar(1024) NOT NULL,
  `author` text NOT NULL,
  `is_hide` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `is_hide` (`is_hide`),
  KEY `title` (`title`),
  KEY `source` (`source`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='Problem list';

-- --------------------------------------------------------

--
-- Stand-in structure for view `ranklist`
--
DROP VIEW IF EXISTS `ranklist`;
CREATE TABLE `ranklist` (
`uid` int(10) unsigned
,`username` varchar(255)
,`nickname` varchar(1024)
,`total_ac` int(10) unsigned
,`total_submit` int(10) unsigned
);
-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `runid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `result` varchar(100) DEFAULT NULL,
  `memory_used` int(11) DEFAULT NULL,
  `time_used` int(11) DEFAULT NULL,
  `time_submit` datetime DEFAULT NULL,
  `contest_belong` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `source` mediumtext,
  `language` int(10) unsigned NOT NULL,
  `ce_info` text,
  `ip_addr` varchar(255) DEFAULT NULL,
  `is_shared` tinyint(1) NOT NULL,
  PRIMARY KEY (`runid`),
  KEY `pid` (`pid`),
  KEY `result` (`result`),
  KEY `time_submit` (`time_submit`),
  KEY `contest_belong` (`contest_belong`),
  KEY `username` (`username`),
  KEY `is_shared` (`is_shared`),
  KEY `language` (`language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Problem Status';

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `nickname` varchar(1024) DEFAULT NULL,
  `password` char(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `total_submit` int(10) unsigned NOT NULL,
  `total_ac` int(10) unsigned NOT NULL,
  `register_time` datetime NOT NULL,
  `is_root` int(11) NOT NULL,
  `ip_addr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`(255)),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='User List';

-- --------------------------------------------------------
