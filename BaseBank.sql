
-- ----------------------------
-- 用户列表
-- ----------------------------
DROP TABLE IF EXISTS `user_list`;
CREATE TABLE `user_list` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL DEFAULT '',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM CHARSET=utf8;

-- ----------------------------
-- 账户列表
-- ----------------------------
DROP TABLE IF EXISTS `account_list`;
CREATE TABLE `account_list` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `add_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM CHARSET=utf8;

-- ----------------------------
-- 账户信息属性
-- ----------------------------
DROP TABLE IF EXISTS `account_info`;
CREATE TABLE `account_info` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `balance` decimal(15, 2) unsigned NOT NULL DEFAULT 0,
  `transfer_limit` decimal(15, 2) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB CHARSET=utf8;

-- ----------------------------
-- 账户转账限额
-- ----------------------------
DROP TABLE IF EXISTS `account_transfer_limit`;
CREATE TABLE `account_transfer_limit` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `remain_limit` decimal(15, 2) unsigned NOT NULL DEFAULT 0,
  `limit_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB CHARSET=utf8;

-- ----------------------------
-- 交易类型
-- ----------------------------
-- DROP TABLE IF EXISTS `deal_type`;
-- CREATE TABLE `deal_type` (
--   `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
--   `title` varchar(50) NOT NULL DEFAULT '',
--   PRIMARY KEY (`id`)
-- ) ENGINE=MyISAM CHARSET=utf8;

-- ----------------------------
-- 交易记录
-- ----------------------------
-- DROP TABLE IF EXISTS `deal_list`;
-- CREATE TABLE `deal_list` (
--   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
--   `account_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
--   `type_id` tinyint(2) unsigned NOT NULL DEFAULT 0,
--   `changes_amount` decimal(15, 2) NOT NULL DEFAULT 0,
--   `balance` decimal(15, 2) unsigned NOT NULL DEFAULT 0,
--   `add_time` int(11) NOT NULL DEFAULT '0',
--   PRIMARY KEY (`id`),
--   KEY `account_id` (`account_id`)
-- ) ENGINE=MyISAM CHARSET=utf8;

-- ----------------------------
-- 转账记录
-- ----------------------------
-- DROP TABLE IF EXISTS `transfer_list`;
-- CREATE TABLE `transfer_list` (
--   `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
--   `deal_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
--   `transfer_account_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
--   `transfer_into_account_id` mediumint(8) unsigned NOT NULL DEFAULT 0,
--   `service_charge` decimal(15, 2) unsigned NOT NULL DEFAULT 0,
--   `add_time` int(11) NOT NULL DEFAULT '0',
--   PRIMARY KEY (`id`),
--   UNIQUE KEY `deal_id` (`deal_id`)
-- ) ENGINE=MyISAM CHARSET=utf8;
