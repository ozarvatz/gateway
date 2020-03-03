<?php

class m161208_071131_oz_create_user_and_user_friend_tbl extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->execute("
-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2016 at 08:23 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";

--
-- Database: `iron_source`
--

-- --------------------------------------------------------

--
-- Table structure for table `isrc_user`
--

CREATE TABLE IF NOT EXISTS `isrc_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(100) NOT NULL,
  `user_age` smallint(5) unsigned NOT NULL DEFAULT '30',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `isrc_user_friend`
--

CREATE TABLE IF NOT EXISTS `isrc_user_friend` (
  `user_id` int(10) unsigned NOT NULL,
  `user_id_friend` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`user_id_friend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
		");
	}

	public function safeDown()
	{
	}
}