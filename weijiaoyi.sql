
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `js_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loginName` varchar(20) DEFAULT NULL,
  `password` varchar(40) DEFAULT NULL,
  `name` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `js_admin`
--

INSERT INTO `js_admin` (`id`, `loginName`, `password`, `name`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', '总后台'),

-- --------------------------------------------------------

--
-- 表的结构 `js_balance_log`
--

CREATE TABLE IF NOT EXISTS `js_balance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `order_code` varchar(20) DEFAULT NULL,
  `out_trade_no` varchar(50) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `money` varchar(20) DEFAULT NULL,
  `give_money` varchar(20) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `cz` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_Reference_4` (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1955 ;

--
-- 转存表中的数据 `js_balance_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `js_collect_article`
--

CREATE TABLE IF NOT EXISTS `js_collect_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `t1` varchar(20) DEFAULT NULL,
  `t2` varchar(20) DEFAULT NULL,
  `datetime` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `text` varchar(500) DEFAULT NULL,
  `prefix` varchar(20) DEFAULT NULL,
  `predicted` varchar(20) DEFAULT NULL,
  `actual` varchar(20) DEFAULT NULL,
  `star` varchar(20) DEFAULT NULL,
  `effect` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `nil` varchar(20) DEFAULT NULL,
  `newstimespan` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `js_collect_data`
--

CREATE TABLE IF NOT EXISTS `js_collect_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(20) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `js_collect_data`
--

INSERT INTO `js_collect_data` (`id`, `value`, `type`, `datetime`) VALUES
(1, '1286.01', 1, '2017-08-11 23:14:03'),
(2, '17.060', 2, '2017-08-11 23:14:03'),
(3, '23549.00', 3, '2017-08-11 23:14:03'),
(4, '1.17910', 4, '2017-08-11 23:14:03'),
(5, '1.29770', 5, '2017-08-11 23:14:03');

-- --------------------------------------------------------

--
-- 表的结构 `js_order`
--

CREATE TABLE IF NOT EXISTS `js_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `play_type` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `trade_time` int(11) DEFAULT NULL,
  `order_price` varchar(20) DEFAULT NULL,
  `vouchers_price` varchar(20) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `gain_price` varchar(20) DEFAULT NULL,
  `open_price` varchar(20) DEFAULT NULL,
  `close_price` varchar(20) DEFAULT NULL,
  `isModel` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `begin_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Reference_3` (`user_id`),
  KEY `FK_Reference_5` (`play_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1390 ;

-- --------------------------------------------------------

--
-- 表的结构 `js_order_virtual`
--

CREATE TABLE IF NOT EXISTS `js_order_virtual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `play_type` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `order_price` varchar(20) DEFAULT NULL,
  `begin_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='' AUTO_INCREMENT=5959 ;

--
-- 转存表中的数据 `js_order_virtual`
--

-- --------------------------------------------------------

--
-- 表的结构 `js_pay`
--

CREATE TABLE IF NOT EXISTS `js_pay` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(10) DEFAULT NULL,
  `orderid` varchar(50) DEFAULT NULL,
  `amount` varchar(10) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `fanxian` varchar(10) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1331 ;

--
-- 转存表中的数据 `js_pay`
--

-- --------------------------------------------------------

--
-- 表的结构 `js_rebate`
--

CREATE TABLE IF NOT EXISTS `js_rebate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `rebate_uid` int(11) DEFAULT NULL,
  `money` varchar(20) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Reference_10` (`userId`),
  KEY `FK_Reference_11` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `js_rebate_setting`
--

CREATE TABLE IF NOT EXISTS `js_rebate_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `conditions` int(11) DEFAULT NULL,
  `return_rates` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `js_rebate_setting`
--

INSERT INTO `js_rebate_setting` (`id`, `name`, `conditions`, `return_rates`) VALUES
(12, '推广百元返利2%', 100, 0.02);

-- --------------------------------------------------------

--
-- 表的结构 `js_setting`
--

CREATE TABLE IF NOT EXISTS `js_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pre_odds` int(11) DEFAULT NULL,
  `group_odds` int(11) DEFAULT NULL,
  `moni_odds` int(11) DEFAULT NULL,
  `reg_count_sms` varchar(200) DEFAULT NULL,
  `vouchers_num` int(11) DEFAULT NULL,
  `vouchers_money` int(11) DEFAULT NULL,
  `use_vouchers_money` int(11) DEFAULT NULL,
  `isUseCode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `js_setting`
--

INSERT INTO `js_setting` (`id`, `pre_odds`, `group_odds`, `moni_odds`, `reg_count_sms`, `vouchers_num`, `vouchers_money`, `use_vouchers_money`, `isUseCode`) VALUES
(1, 4, 4, 8, '【迷你富】您的验证码是；', 1, 1, 1, 2);

-- --------------------------------------------------------

--
-- 表的结构 `js_temp_data`
--

CREATE TABLE IF NOT EXISTS `js_temp_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) DEFAULT NULL,
  `typeId` int(11) DEFAULT NULL,
  `_temp_value` varchar(20) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `second` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Reference_2` (`data_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22693959 ;

--
-- 转存表中的数据 `js_temp_data`
--

--
-- 表的结构 `js_wx_info`
--

CREATE TABLE IF NOT EXISTS `js_wx_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscribe` int(11) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `headimgurl` varchar(500) DEFAULT NULL,
  `subscribe_time` varchar(20) DEFAULT NULL,
  `unionid` varchar(50) DEFAULT NULL,
  `remark` varchar(500) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `t_data`
--

CREATE TABLE IF NOT EXISTS `t_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_value` varchar(20) DEFAULT NULL,
  `close_value` varchar(20) DEFAULT NULL,
  `max_value` varchar(20) DEFAULT NULL,
  `min_value` varbinary(20) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_Reference_6` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=811039 ;

--
-- 转存表中的数据 `t_data`
--
