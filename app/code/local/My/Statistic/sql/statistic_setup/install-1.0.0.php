<?php

/** @var Mage_Core_Model_Resource_Setup $installer*/
$installer =$this;
$installer->startSetup();

//Создание таблиц в БД


$installer->run("
    CREATE TABLE IF NOT EXISTS `my_stats_visits` (
      `visit_id` MEDIUMINT UNSIGNED NOT NULL auto_increment,
      `userid` MEDIUMINT NOT NULL COMMENT 'CMS UserId',
      `visit_date` DATE NOT NULL COMMENT 'visit date in  Local time zone',
      `day` tinyint(4) NOT NULL default '0', /*день*/
      `month` tinyint(4) NOT NULL default '0', /*месяц*/
      `year` smallint(6) NOT NULL default '0', /*год*/
      `visit_time` TIME NOT NULL COMMENT 'visit time in  Local time zone',
      `hour` tinyint(4) NOT NULL default '0', /*час*/
      `minute` tinyint(4) NOT NULL default '0', /*минута*/
      `sys_string` varchar(25) NOT NULL default 'unknown',
      `sys_fullname` varchar(25) NOT NULL default 'unknown',
      `browser_name` varchar(25) NOT NULL default 'unknown',
      `browser_version` varchar(25) NOT NULL default 'unknown',
      PRIMARY KEY (visit_id)
    ) 
    ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
    ");

$installer->run("
    CREATE TABLE IF NOT EXISTS `my_stats_country` (
      `id` int(9) NOT NULL auto_increment, /*ID*/
      `visit_id` mediumint(8) NOT NULL,
      `continent` varchar(30) NOT NULL default 'unknown', /*континент*/
      `tld` varchar(10) NOT NULL default 'unknown', /*код строны - by ru ...*/
      `timezone` varchar(50) NOT NULL default 'unknown', /*временная зона*/
      `country_name_ru` varchar(255) NOT NULL default 'unknown',/*Название страны на русском*/
      `country_name_en` varchar(255) NOT NULL default 'unknown',/*Название страны на английском*/
      `city_name_ru` varchar(255) NOT NULL default 'unknown', /*Название города на русском*/
      `city_name_en` varchar(255) NOT NULL default 'unknown', /*Название города на английском*/
      `lat` varchar(50) NOT NULL default '0', /*Широта*/
      `lon` varchar(50) NOT NULL default '0', /*Долгота*/
       PRIMARY KEY (id)
    ) 
    ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;
    ");

$installer->run("
    CREATE TABLE IF NOT EXISTS `my_stats_ipaddresses` (
      `id` mediumint(9) NOT NULL auto_increment, /*ID*/
      `ip` varchar(50) NOT NULL default '000.000.000.000', /*ip адрес посетителя*/
      `visit_id` mediumint(8) NOT NULL,
      `ip_server` varchar(50) NOT NULL default '000.000.000.000', /*ОС*/
      `tld` varchar(10) NOT NULL default 'unknown', /*код строны - by ru ...*/
      `useragent` varchar(255) default NULL, /*вся строка*/
      `system` varchar(50) NOT NULL default '-', /*ОС*/
      `browser` varchar(50) NOT NULL default '-', /*браузер*/
       PRIMARY KEY (id),
    ) 
    ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;
    ");

$installer->run("
    CREATE TABLE IF NOT EXISTS `my_stats_pages` (
      `page_id` mediumint(9) NOT NULL auto_increment, /*Номер страницы*/
      `page` text NOT NULL, /*ссылка*/
      PRIMARY KEY (`page_id`)
    ) 
    ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;
    ");

$installer->run("
    CREATE TABLE IF NOT EXISTS `my_stats_page_visit` (
      `id` mediumint(9) NOT NULL auto_increment, /* id ссылки*/
      `page_id` MEDIUMINT UNSIGNED NOT NULL,
      `page` text NOT NULL, /*ссылка*/
      `visit_id` MEDIUMINT UNSIGNED NOT NULL, /*номер посетителя*/
      `referrer` varchar(255) NOT NULL default '-', /*ссылка по каторой пришли на сайт*/
      `visit_time` TIME NOT NULL COMMENT 'visit time in  Local time zone', /*время */
      `visit_date` DATE NOT NULL COMMENT 'visit date in  Local time zone',  /*дата*/
      PRIMARY KEY (`id`)
    ) 
    ENGINE=InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;
    ");

$installer->endSetup();