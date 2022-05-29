/*
SQLyog Community
MySQL - 10.4.24-MariaDB : Database - laravel
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
USE `laravel`;

/*Table structure for table `addresses` */

CREATE TABLE `addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer` int(11) NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `addresses` */

/*Table structure for table `adtypes` */

CREATE TABLE `adtypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discontinued` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `adtypes` */

insert  into `adtypes`(`id`,`type`,`discontinued`,`created_at`,`updated_at`) values 
(1,'Existing Customer',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'networking',0,'2017-08-04 05:56:18','2017-08-04 05:56:18'),
(4,'Les Ami',0,'2017-08-04 05:57:46','2020-01-27 04:27:59'),
(5,'Website IberRent',0,'2020-01-27 04:28:59','2020-01-27 04:30:40'),
(6,'Website IberHoa',0,'2020-01-27 04:30:58','2020-01-27 04:31:10');

/*Table structure for table `ajaxerrors` */

CREATE TABLE `ajaxerrors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `occuredOn` datetime DEFAULT NULL,
  `ajaxId` varchar(255) DEFAULT NULL,
  `data` mediumtext DEFAULT NULL,
  `status` text DEFAULT NULL,
  `errorThrown` varchar(255) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `body` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

/*Data for the table `ajaxerrors` */

insert  into `ajaxerrors`(`id`,`occuredOn`,`ajaxId`,`data`,`status`,`errorThrown`,`url`,`body`) values 
(7,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13755<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(8,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13756<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(9,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13757<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(10,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13758<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(11,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13759<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(12,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13760<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(13,'2020-01-28 14:03:00','create_reminder','{\"page\":\"https:\\/\\/iberhola.pepper-crm.net\\/customers\\/42\",\"title\":\"Carine visit\",\"description\":\"11:00 2 Calle Equitacion\",\"date\":\"04-02-2020 14:01\",\"sendTo\":[\"1\"],\"sendToOutlook\":\"true\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://iberhola.pepper-crm.net/customers/42','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#13761<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(14,'2022-02-15 09:24:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://laravelnew.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34423<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(15,'2022-02-15 09:38:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://laravelnew.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34435<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(16,'2022-02-15 09:39:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://laravelnew.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34436<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(17,'2022-02-15 09:42:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34405<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(18,'2022-02-15 09:49:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34418<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(19,'2022-02-15 09:49:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34421<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(20,'2022-02-24 11:23:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34428<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(21,'2022-02-24 11:50:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34429<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(22,'2022-02-24 11:51:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34433<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(23,'2022-02-24 11:51:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34436<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(24,'2022-02-24 11:52:00','get_quote','{\"quoteId\":\"85\"}','parsererror','SyntaxError: Unexpected token < in JSON at position 0','https://crm.luxdemoestate.com/quotes/85/edit','\"<!DOCTYPE html>\\n<html>\\n<head>\\n\\t<meta charset=\\\"utf-8\\\">\\n\\t<meta http-equiv=\\\"X-UA-Compatible\\\" content=\\\"IE=edge\\\">\\n\\t<title>Pepper CRM - 500<\\/title>\\n\\t<link href=\'https:\\/\\/fonts.googleapis.com\\/css?family=Roboto:400,500\' rel=\'stylesheet\' type=\'text\\/css\'>\\n\\t<link rel=\\\"stylesheet\\\" href=\\\"https:\\/\\/maxcdn.bootstrapcdn.com\\/font-awesome\\/4.6.1\\/css\\/font-awesome.min.css\\\">\\n\\t<style>\\n\\t\\tbody {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-family: \'Roboto\', sans-serif;\\n\\t\\t\\tbackground-image: url(\'error-page-bg.png\');\\n\\t\\t\\tbackground-repeat: repeat;\\n\\t\\t}\\n\\n\\t\\t* {\\n\\t\\t\\tbox-sizing: border-box;\\n\\t\\t}\\n\\n\\t\\t#main-wrapper {\\n\\t\\t\\tposition: fixed;\\n\\n\\t\\t\\twidth: 600px;\\n\\t\\t\\theight: 250px;\\n\\t\\t\\t\\n\\t\\t\\tleft: 50%;\\n\\t\\t\\ttop: 50%;\\n\\n\\t\\t\\tmargin-top: -125px;\\n\\t\\t\\tmargin-left: -300px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper {\\n\\t\\t\\tfloat: left;\\n\\t\\t\\twidth: 150px;\\n\\t\\t}\\n\\n\\t\\t#cogs-wrapper img {\\n\\t\\t\\theight: 125px;\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper {\\n\\t\\t\\tfloat: left;\\n\\n\\t\\t\\twidth: 450px;\\n\\n\\t\\t\\tpadding-left: 20px;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper h1 {\\n\\t\\t\\tmargin: 0;\\n\\t\\t\\tfont-size: 18px;\\n\\t\\t\\tfont-weight: medium;\\n\\t\\t\\tcolor: #616161;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper p {\\n\\t\\t\\tmargin: 10px 0 0 0;\\n\\t\\t\\tfont-size: 14px;\\n\\t\\t\\tcolor: #8A8A8A;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button {\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t\\tborder: 0;\\n\\n\\t\\t\\tpadding: 10px 20px;\\n\\n\\t\\t\\ttransition: background-color 0.1s linear;\\n\\n\\t\\t\\tfont-size: 12px;\\n\\t\\t\\tfont-weight: bold;\\n\\n\\t\\t\\tborder-radius: 3px;\\n\\n\\t\\t\\tcolor: white;\\n\\n\\t\\t\\tmargin-bottom: 4px;\\n\\n\\t\\t\\tbackground-color: #E07833;\\n\\n\\t\\t\\tcursor: pointer;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:focus {\\n\\t\\t\\toutline: 0;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:active {\\n\\t\\t\\t\\tbox-shadow: inset 0px 2px 2px 0px rgba(0,0,0,0.4);\\n\\t\\t}\\n\\n\\t\\t#text-wrapper button:hover {\\n\\t\\t\\t\\tbackground-color: #F88234;\\n\\t\\t}\\n\\n\\t\\t#text-wrapper img#logo {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tfloat: right;\\n\\n\\t\\t\\twidth: 90px;\\n\\t\\t\\tmargin-top: 25px;\\n\\t\\t\\tmargin-left: 15px;\\n\\t\\t}\\n\\n\\t\\t.clearfix {\\n\\t\\t\\tclear: both;\\n\\t\\t}\\n\\n\\t\\t.caseid {\\n\\t\\t\\tdisplay: block;\\n\\t\\t\\tcolor: #838383;\\n\\t\\t\\tfont-size: 12px;\\n\\n\\t\\t\\tmargin-top: 20px;\\n\\t\\t}\\n\\t<\\/style>\\n<\\/head>\\n<body>\\n\\t<div id=\\\"main-wrapper\\\">\\n\\t\\t<div id=\\\"cogs-wrapper\\\">\\n\\t\\t\\t<img src=\'\\/img\\/layout\\/500-cogs.gif\'>\\n\\t\\t<\\/div>\\n\\t\\t<div id=\\\"text-wrapper\\\">\\n\\t\\t\\t<h1>We\'re sorry. Something went terribly wrong.<\\/h1>\\n\\t\\t\\t<p>\\n\\t\\t\\t\\tDon\'t panic. Although it seems Pepper has exploded, our highly skilled team or tech-monkeys are already fixing the problem. In the meantime, please try again. If the error persists, please contact us via the orange \'Help & feedback\' button at the bottom-left of Pepper.\\n\\t\\t\\t<\\/p>\\n\\t\\t\\t<a href=\'\\/\'><button type=\'button\'><i class=\\\"fa fa-arrow-left\\\"><\\/i>&nbsp;&nbsp;Back to Pepper<\\/button><\\/a>\\n\\t\\t\\t<img id=\'logo\' src=\'\\/img\\/pepper_logo.png\'>\\n\\t\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t\\t\\t<span class=\\\"caseid\\\">\\n\\t\\t\\t\\tIf you contact support, please provide us with the following case number: <b>#34443<\\/b>.\\n\\t\\t\\t<\\/span>\\n\\t\\t<\\/div>\\n\\t\\t<div class=\\\"clearfix\\\"><\\/div>\\n\\t<\\/div>\\n<\\/body>\\n<\\/html>\"'),
(25,'2022-04-20 10:44:00','get_quote','{\"quoteId\":\"85\"}','error','Internal Server Error','https://crm.luxdemoestate.com/quotes/85/edit','\"{\\\"error\\\":{\\\"type\\\":\\\"Symfony\\\\\\\\Component\\\\\\\\Debug\\\\\\\\Exception\\\\\\\\FatalErrorException\\\",\\\"message\\\":\\\"Uncaught TypeError: Argument 1 passed to Illuminate\\\\\\\\Exception\\\\\\\\WhoopsDisplayer::display() must be an instance of Exception, instance of Error given, called in \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php on line 9294 and defined in \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php:9184\\\\nStack trace:\\\\n#0 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9294): Illuminate\\\\\\\\Exception\\\\\\\\WhoopsDisplayer->display(Object(Error))\\\\n#1 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9246): Illuminate\\\\\\\\Exception\\\\\\\\Handler->displayException(Object(Error))\\\\n#2 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9250): Illuminate\\\\\\\\Exception\\\\\\\\Handler->handleException(Object(Error))\\\\n#3 [internal function]: Illuminate\\\\\\\\Exception\\\\\\\\Handler->handleUncaughtException(Object(Error))\\\\n#4 {main}\\\\n  thrown\\\",\\\"file\\\":\\\"\\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php\\\",\\\"line\\\":9184}}\"'),
(26,'2022-04-20 10:44:00','get_quote','{\"quoteId\":\"85\"}','error','Internal Server Error','https://crm.luxdemoestate.com/quotes/85/edit','\"{\\\"error\\\":{\\\"type\\\":\\\"Symfony\\\\\\\\Component\\\\\\\\Debug\\\\\\\\Exception\\\\\\\\FatalErrorException\\\",\\\"message\\\":\\\"Uncaught TypeError: Argument 1 passed to Illuminate\\\\\\\\Exception\\\\\\\\WhoopsDisplayer::display() must be an instance of Exception, instance of Error given, called in \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php on line 9294 and defined in \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php:9184\\\\nStack trace:\\\\n#0 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9294): Illuminate\\\\\\\\Exception\\\\\\\\WhoopsDisplayer->display(Object(Error))\\\\n#1 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9246): Illuminate\\\\\\\\Exception\\\\\\\\Handler->displayException(Object(Error))\\\\n#2 \\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php(9250): Illuminate\\\\\\\\Exception\\\\\\\\Handler->handleException(Object(Error))\\\\n#3 [internal function]: Illuminate\\\\\\\\Exception\\\\\\\\Handler->handleUncaughtException(Object(Error))\\\\n#4 {main}\\\\n  thrown\\\",\\\"file\\\":\\\"\\\\\\/home\\\\\\/democrm\\\\\\/public_html\\\\\\/bootstrap\\\\\\/compiled.php\\\",\\\"line\\\":9184}}\"');

/*Table structure for table `calloutfees` */

CREATE TABLE `calloutfees` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `calloutfees` */

/*Table structure for table `companyroles` */

CREATE TABLE `companyroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `companyroles` */

insert  into `companyroles`(`id`,`type`,`created_at`,`updated_at`) values 
(1,'Administration','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `contacthistory` */

CREATE TABLE `contacthistory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `placedOn` datetime DEFAULT NULL,
  `placedBy` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;

/*Data for the table `contacthistory` */

insert  into `contacthistory`(`id`,`placedOn`,`placedBy`,`message`,`customer`) values 
(90,'2020-01-27 10:38:00',1,'Quote #84 created by Frederique Fred',40),
(91,'2020-01-27 13:43:00',1,'Quote #85 created by Frederique Rudolph',41),
(92,'2020-02-07 11:14:00',2,'Quote #86 created by Nathalie Perez',42),
(93,'2022-02-16 15:56:00',1,'Quote #87 created by Rudolph Demo',40);

/*Table structure for table `credit` */

CREATE TABLE `credit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discontinued` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `credit` */

insert  into `credit`(`id`,`type`,`abbreviation`,`discontinued`,`created_at`,`updated_at`) values 
(1,'Trust Worthy','TW',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'charge up front','CUF',0,'2017-08-04 05:58:40','2017-08-04 05:58:40'),
(3,'late payers','LPA',0,'2017-08-04 05:59:34','2017-08-04 05:59:34');

/*Table structure for table `creditnotecomments` */

CREATE TABLE `creditnotecomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creditnoteId` int(11) DEFAULT NULL,
  `placedBy` int(11) DEFAULT NULL,
  `placedOn` datetime DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `creditnotecomments` */

/*Table structure for table `creditnotedetails` */

CREATE TABLE `creditnotedetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creditnoteId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quoteId` int(11) NOT NULL,
  `unitPrice` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `supCosts` double DEFAULT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `creditnotedetails` */

/*Table structure for table `creditnoteemails` */

CREATE TABLE `creditnoteemails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creditnote` int(255) DEFAULT NULL,
  `to` text DEFAULT NULL,
  `cc` text DEFAULT NULL,
  `bcc` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `filename` varchar(10) DEFAULT NULL,
  `sentOn` datetime DEFAULT NULL,
  `sentBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `creditnoteemails` */

/*Table structure for table `creditnotes` */

CREATE TABLE `creditnotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdOn` datetime DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `jobTitle` varchar(255) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `creditnotes` */

/*Table structure for table `currencies` */

CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `currencies` */

insert  into `currencies`(`id`,`name`,`symbol`,`abbreviation`,`created_at`,`updated_at`) values 
(1,'Euro','‚Ç¨','EUR','2017-04-18 01:25:34','2017-04-18 01:25:34'),
(2,'U.S. Dollar','$','USD','2017-04-18 01:25:34','2017-04-18 01:25:34'),
(3,'Pound Sterling','¬£','GBP','2017-04-18 01:25:34','2017-04-18 01:25:34');

/*Table structure for table `customerfiles` */

CREATE TABLE `customerfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `filetype` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `addedBy` int(11) DEFAULT NULL,
  `addedOn` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `customerfiles` */

/*Table structure for table `customers` */

CREATE TABLE `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contactName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contactTitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cifnif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `notes2` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `joined` date NOT NULL,
  `visualDirections` longtext COLLATE utf8_unicode_ci NOT NULL,
  `credit` int(11) NOT NULL,
  `createdBy` int(11) NOT NULL,
  `managedBy` int(11) NOT NULL,
  `advertisingType` int(11) NOT NULL,
  `assignedVisitFee` double NOT NULL,
  `newsletter` int(11) NOT NULL,
  `newsletter_unsubscribe` timestamp NULL DEFAULT NULL,
  `discontinued` tinyint(11) NOT NULL,
  `accountHolder` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_cifnif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branchId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_notes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locationLat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locationLng` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sepa_mandateId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sepa_mandateDate` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxId` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `swiftCode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `readonly` tinyint(4) DEFAULT 0,
  `shopName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paymentTerms` int(11) DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `skype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `deliveryAddress` int(11) DEFAULT NULL,
  `sector` int(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `companyName` (`companyName`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `customers` */

insert  into `customers`(`id`,`customerCode`,`companyName`,`contactName`,`contactTitle`,`address`,`city`,`region`,`postalCode`,`country`,`phone`,`mobile`,`email`,`fax`,`cifnif`,`notes`,`notes2`,`joined`,`visualDirections`,`credit`,`createdBy`,`managedBy`,`advertisingType`,`assignedVisitFee`,`newsletter`,`newsletter_unsubscribe`,`discontinued`,`accountHolder`,`bankName`,`bank_cifnif`,`iban`,`bankId`,`branchId`,`dc`,`accountId`,`bank_notes`,`created_at`,`updated_at`,`website`,`locationLat`,`locationLng`,`sepa_mandateId`,`sepa_mandateDate`,`taxId`,`swiftCode`,`readonly`,`shopName`,`paymentTerms`,`currency`,`skype`,`type`,`deliveryAddress`,`sector`) values 
(40,'','General Public','Cliente Contado','Mrs','1','Fuengirola','','29640','Spain','','','','','','','','0000-00-00','',1,1,1,2,190,1,NULL,0,'','','','','','','','','','2018-06-12 07:54:07','2020-01-27 01:29:12','',NULL,NULL,'','',NULL,'',0,'',1,1,'',1,NULL,1),
(41,'','Ap2 SQUARED','Ali','MR','','','','','','','+34605841886','parandeh@urbytus.com','','','',NULL,'0000-00-00','',1,1,1,2,0,1,NULL,0,'','','','','','','','','','2020-01-27 04:43:46','2020-01-27 04:43:46','',NULL,NULL,'','',NULL,'',0,'PC doctor',1,1,NULL,1,NULL,1),
(42,'','Carine','Carine Pierrar, Compere','','Torrequebrada','Benalmadena','Espagne','29630','spain','','+33 6 87 19 42 79','ladeessedunil@gmail.com','','','-Organised visits for Tuesday 04/02/20 with Ultimate estates spain Simon Scott 637534902 at 11h to see R3261373 and R3533536<br />\n-Tuesday at 12:30&nbsp;https://www.lvrealestate.es/cliente/?cliente=015080_19591753122319826572M9173451&amp;x=1<br />\n<br />\n-Tuesday at 2pm ref YPIS6582&nbsp; &nbsp;https://www.yourpropertyinspain.com/en/property/id/793787<br />\n-Tuesday 17:15 wirh Remax&nbsp; Ignacio Ref 347600917<br />\n-Wednesday&nbsp;&nbsp;R2154689 and&nbsp;R2300333&nbsp; on the 5th of Feb at 12:30pm&nbsp; Damien 699440312<br />\n- Wednesday R3438139&nbsp;<br />\nConfirmed the appointment for Wednesday February 5 th.<br />\nAgent who will make the visit is Massimo Conte: 633206538<br />\nMeeting Point:https://goo.gl/maps/1nqc7pqZPQ8Fa3Pc6<br />\n&nbsp;','','0000-00-00','',1,1,1,1,0,1,NULL,0,'','','','','','','','','','2020-01-28 04:44:48','2020-01-31 03:01:23','',NULL,NULL,'','',NULL,'',0,'',2,1,NULL,1,NULL,1),
(43,'','SAMIR','SAMIR','MR','','VERSAILLES','','78000','FRANCE','','+34 604 29 19 82','','','','3 PIECES TORREMOLINOS PROCHE CALLE CRUZ VISITE A PREVOIR EN FEVRIER','','0000-00-00','',1,2,2,4,0,1,NULL,0,'','','','','','','','','','2020-01-29 06:01:54','2020-01-29 06:03:07','',NULL,NULL,'','',NULL,'',0,'',1,1,NULL,1,NULL,1),
(44,'','Stark Mejia Plc','Cade Morales','Consequat Voluptate','','','','','','+1 (255) 101-3872','Nisi laboriosam fug','dunus@mailinator.com','+1 (534) 289-7624','Dolor est vitae libe','',NULL,'0000-00-00','',1,1,1,1,0,1,NULL,0,'','','','','','','','','','2022-03-28 04:22:54','2022-03-28 04:22:54','https://www.wufy.in',NULL,NULL,'','',NULL,'',0,'Hermione Macdonald',1,1,NULL,1,NULL,1);

/*Table structure for table `customertype` */

CREATE TABLE `customertype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `customertype` */

/*Table structure for table `customertypes` */

CREATE TABLE `customertypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `customertypes` */

insert  into `customertypes`(`id`,`type`,`created_at`,`updated_at`) values 
(1,'Not Applicable','2017-04-18 01:25:34','2017-04-18 01:25:34');

/*Table structure for table `directdebitdetails` */

CREATE TABLE `directdebitdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job` int(11) DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `invoice` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sent` tinyint(4) DEFAULT NULL,
  `debited` tinyint(4) DEFAULT NULL,
  `bankCharge` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `directdebitdetails` */

/*Table structure for table `directdebitjobs` */

CREATE TABLE `directdebitjobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `completed` tinyint(4) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `directdebitjobs` */

/*Table structure for table `expensecategories` */

CREATE TABLE `expensecategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english` varchar(255) DEFAULT NULL,
  `spanish` varchar(255) DEFAULT NULL,
  `accountingCode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=latin1;

/*Data for the table `expensecategories` */

insert  into `expensecategories`(`id`,`english`,`spanish`,`accountingCode`,`created_at`,`updated_at`,`disabled`) values 
(1,'Merchandise Purchased','Compras de mercader√≠as','600',NULL,'2015-11-10 08:49:10',1),
(2,'Raw materials purchased','Compras de materias primas','601',NULL,'2015-10-21 10:26:20',0),
(3,'Other supplies purchased','Compras de otros aprovisionamientos','602',NULL,NULL,0),
(4,'Subcontracted work','Trabajos realizados por otras empresas','607',NULL,NULL,0),
(5,'Changes in inventories of merchandise','Variaci√≥n de exististencias de mercader√≠as','610',NULL,NULL,0),
(6,'Changes in inventories of raw materials','Variaci√≥n de existencias de materias primas','611',NULL,NULL,0),
(7,'Changes in inventories of other supplies','Variaci√≥n de existencias de otros aprovisionamientos','612',NULL,NULL,0),
(8,'Research and development expenses for the period','Gastos de investig y desarrollo del ejercicio','620',NULL,NULL,0),
(9,'Leases and royalties','Arrendamientos y c√°nones','621',NULL,NULL,0),
(10,'Repairs and maintenance','Reparaciones y conservaci√≥n','622',NULL,NULL,0),
(11,'Independent professional services','Servicios profesionales independientes','623',NULL,NULL,0),
(12,'Transport','Transportes','624',NULL,NULL,0),
(13,'Insurance premiums','Primas de seguros','625',NULL,NULL,0),
(14,'Banking and similar services','Servicios bancarios y similares','626',NULL,NULL,0),
(15,'Advertising, publicity and public relations','Publicidad, propaganda y relaciones publicas','627',NULL,NULL,0),
(16,'Utilities','Suministros','628',NULL,NULL,0),
(17,'Other services','Otros servicios','629',NULL,NULL,0),
(18,'Other taxes','Otros tributos','631',NULL,NULL,0),
(19,'¬ø? ESTA CUENTA NO PERTENECE AL PLAN GENERAL CONTABLE','Entidades transp., efecto imposit.','632',NULL,NULL,0),
(20,'Negative adjustments to income tax','Ajustes negativos en la imposici√≥n sobre beneficios','633',NULL,NULL,0),
(21,'Negative adjustments to indirect taxes','Ajustes negativos en la imposici√≥n indirecta','634',NULL,NULL,0),
(22,'¬ø? ESTA CUENTA NO PERTENECE AL PLAN GENERAL CONTABLE','Impuesto sobre beneficios extranjeros','635',NULL,NULL,0),
(23,'Tax refunds','Devoluci√≥n de impuestos','636',NULL,NULL,0),
(24,'Positive adjustments to income tax','Ajustes positivos en imposici√≥n sobre beneficios','638',NULL,NULL,0),
(25,'Positive adjustments to indirect taxes','Ajustes positivos en imposici√≥n indirecta','639',NULL,NULL,0),
(26,'Salaries and wages','Sueldos y salarios','640',NULL,NULL,0),
(27,'Termination benefits','Indemnizaciones','641',NULL,NULL,0),
(28,'Social Security payable by the company','Seguridad Social a cargo empresa','642',NULL,NULL,0),
(29,'Long-term employee benefits payable through defined contribution schemes','Retribuciones a l/p mediante sistemas de aportaci√≥n definida','643',NULL,NULL,0),
(30,'Long-term employee benefits payable through defined benefit schemes','Retribuciones a l/p mediante sistemas de prestaci√≥n definida','644',NULL,NULL,0),
(31,'Employee benefits expense','Otros gastos sociales','649',NULL,NULL,0),
(32,'Losses on irrecoverable trade receivables','P√©rdidas de cr√©ditos comerciales incobrables','650',NULL,NULL,0),
(33,'Results on profit-sharing agreements','Resultados de operaciones en com√∫n','651',NULL,NULL,0),
(34,'Other operating losses','Otras perdidas en gesti√≥n corriente','659',NULL,NULL,0),
(35,'Finance expenses arising from provision adjustments','Gastos financieros por actualizaci√≥n de provisiones','660',NULL,NULL,0),
(36,'Losses on investments and debt securities','Perdidas en participaciones y valores representativos de deuda','666',NULL,NULL,0),
(37,'Losses on non-trade receivables','P√©rdidas de cr√©ditos no comerciales','667',NULL,NULL,0),
(38,'Exchange losses','Diferencias negativas de cambio','668',NULL,NULL,0),
(39,'Other finance expenses','Otros gastos financieros','669',NULL,NULL,0),
(40,'Losses on intangible assets','P√©rdidas procedentes del inmovilizado intangible','670',NULL,NULL,0),
(41,'Losses on property, plant and equipment','P√©rdidas procedentes del inmovilizado material','671',NULL,NULL,0),
(42,'Losses on investment property','P√©rdidas procedentes de las inversiones inmobiliarias','672',NULL,NULL,0),
(43,'Losses on non-current investments in related parties','P√©rdidas procedentes de participaciones a l/p en partes vinculadas','673',NULL,NULL,0),
(44,'Losses on transactions with own bonds','P√©rdidas por operaciones con obligaciones propias','675',NULL,NULL,0),
(45,'Exceptional expenses','Gastos excepcionales','678',NULL,NULL,0),
(46,'Amortisation of intangible assets','Amortizaci√≥n del inmovilizado intangible','680',NULL,NULL,0),
(47,'Depreciation of property, plant and equipment','Amortizaci√≥n del inmovilizado material','681',NULL,NULL,0),
(48,'Depreciation of investment property','Amortizaci√≥n de las inversiones inmobiliarias','682',NULL,NULL,0),
(49,'Impairment losses on intangible assets','P√©rdidas por deterioro de inmovilizado intangible','690',NULL,NULL,0),
(50,'Impairment losses on property, plant and equipment','P√©rdidas por deterioro del inmovilizado material','691',NULL,NULL,0),
(51,'Impairment losses on investment property','P√©rdidas por deterioro de las inversiones inmobiliarias','692',NULL,NULL,0),
(52,'Impairment losses on trade receivables','P√©rdidas por deterioro de cr√©ditos por operaciones comerciales','694',NULL,NULL,0),
(53,'Trade provisions','Dotaci√≥n a la provisi√≥n por operaciones comerciales','695',NULL,NULL,0),
(54,'Impairment losses on non-current investments and debt securities','P√©rdidas por deterioro de participaciones y valores representativos de deuda a l/p','696',NULL,NULL,0),
(55,'Impairment losses on non-current loans','P√©rdidas por deterioro de cr√©ditos a l/p','697',NULL,NULL,0),
(56,'Impairment losses on current investments and debt securities','P√©rdidas por deterioro de participaciones y valores representativos de deuda a c/p','698',NULL,NULL,0);

/*Table structure for table `expensepaymentdetails` */

CREATE TABLE `expensepaymentdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expensepayment` int(11) DEFAULT NULL,
  `invoiceId` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expensepaymentdetails` */

/*Table structure for table `expensepayments` */

CREATE TABLE `expensepayments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `paymentMethod` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expensepayments` */

/*Table structure for table `expenseproducts` */

CREATE TABLE `expenseproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `lastPrice` double DEFAULT NULL,
  `purchasePrice` double DEFAULT NULL,
  `salesPrice` double DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `isAsset` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expenseproducts` */

/*Table structure for table `expenses` */

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceNumber` varchar(255) DEFAULT NULL,
  `invoiceDate` date DEFAULT NULL,
  `invoiceReceivedDate` date DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `supplierName` varchar(255) DEFAULT NULL,
  `supplierVatId` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `subcategory` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isOfficial` tinyint(4) DEFAULT NULL,
  `isInternal` tinyint(4) DEFAULT NULL,
  `proforma` varchar(255) DEFAULT NULL,
  `waitingForInvoice` tinyint(4) DEFAULT NULL,
  `quoteId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expenses` */

/*Table structure for table `expensesubcategories` */

CREATE TABLE `expensesubcategories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english` varchar(255) DEFAULT NULL,
  `spanish` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `accountingCode` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expensesubcategories` */

/*Table structure for table `expensesubtotals` */

CREATE TABLE `expensesubtotals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `expense` int(11) DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `expensesubtotals` */

/*Table structure for table `invoicecomments` */

CREATE TABLE `invoicecomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceId` int(11) DEFAULT NULL,
  `placedBy` int(11) DEFAULT NULL,
  `placedOn` datetime DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `emailId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `invoicecomments` */

/*Table structure for table `invoicedetails` */

CREATE TABLE `invoicedetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoiceId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `quoteId` int(11) NOT NULL,
  `unitPrice` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `discount` int(11) NOT NULL,
  `supCosts` double DEFAULT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `invoicedetails` */

insert  into `invoicedetails`(`id`,`invoiceId`,`productId`,`productName`,`quoteId`,`unitPrice`,`quantity`,`discount`,`supCosts`,`description`) values 
(7,1,-999,'Quote #85',85,239,1,0,0,'3ch 2 sb  180000\nfuengirola');

/*Table structure for table `invoiceemailattachments` */

CREATE TABLE `invoiceemailattachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailId` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `invoiceemailattachments` */

/*Table structure for table `invoiceemails` */

CREATE TABLE `invoiceemails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice` int(255) DEFAULT NULL,
  `to` text DEFAULT NULL,
  `cc` text DEFAULT NULL,
  `bcc` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `filename` varchar(10) DEFAULT NULL,
  `sentOn` datetime DEFAULT NULL,
  `sentBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `invoiceemails` */

/*Table structure for table `invoices` */

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdOn` datetime DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `jobTitle` varchar(255) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `vat_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `irpf` int(11) NOT NULL DEFAULT 0,
  `irpf_per` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(19,2) NOT NULL DEFAULT 0.00,
  `description` mediumtext DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `invoices` */

insert  into `invoices`(`id`,`createdOn`,`customer`,`jobTitle`,`createdBy`,`vat`,`vat_per`,`irpf`,`irpf_per`,`subtotal`,`description`,`notes`) values 
(1,'2020-01-27 13:59:10',41,'3ch 2 sb  180000\nfuengirola',2,2,0.00,0,0.00,0.00,'','');

/*Table structure for table `jobstatus` */

CREATE TABLE `jobstatus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobstatus` */

insert  into `jobstatus`(`id`,`type`,`created_at`,`updated_at`) values 
(1,'Enquiry','0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'In Progress','0000-00-00 00:00:00','0000-00-00 00:00:00'),
(3,'Cancelled','0000-00-00 00:00:00','0000-00-00 00:00:00'),
(4,'Completed','0000-00-00 00:00:00','0000-00-00 00:00:00'),
(5,'Completed Pending Payment','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `logs` */

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `log_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=latin1;

/*Data for the table `logs` */

insert  into `logs`(`id`,`userId`,`text`,`log_time`) values 
(122,1,'Updated user Frederique Rudolph','2020-01-27 13:14:32'),
(123,1,'Created user  ','2020-01-27 13:14:45'),
(124,1,'Updated user Nathalie Perez','2020-01-27 13:16:58'),
(125,1,'Updated user Frederique Rudolph','2020-01-27 13:17:28'),
(126,1,'User logged in.','2020-01-27 13:19:23'),
(127,2,'User logged in.','2020-01-27 13:20:16'),
(128,1,'Created customer: Ap2 SQUARED','2020-01-27 13:43:46'),
(129,1,'Created customer: Carine','2020-01-28 13:44:48'),
(130,2,'Created customer: SAMIR','2020-01-29 15:01:54'),
(131,1,'User logged in.','2022-02-11 13:32:13'),
(132,1,'Updated user Rudolph Demo','2022-02-11 13:32:52'),
(133,1,'Updated user Rudolph Demo','2022-02-11 13:33:35'),
(134,1,'User logged in.','2022-02-11 13:33:50'),
(135,1,'User logged in.','2022-02-11 13:39:21'),
(136,1,'User logged in.','2022-02-14 06:07:32'),
(137,1,'User logged in.','2022-02-14 12:57:14'),
(138,1,'User logged in.','2022-02-15 09:59:53'),
(139,1,'User logged in.','2022-02-16 15:55:38'),
(140,1,'User logged in.','2022-02-24 11:22:56'),
(141,1,'User logged in.','2022-02-28 11:43:13'),
(142,1,'User logged in.','2022-03-28 12:28:16'),
(143,1,'User logged in.','2022-03-28 13:21:14'),
(144,1,'Created customer: Stark Mejia Plc','2022-03-28 13:22:54'),
(145,1,'User logged in.','2022-03-28 13:32:45'),
(146,1,'User logged in.','2022-04-18 15:47:53'),
(147,1,'User logged in.','2022-04-19 14:50:47'),
(148,1,'User logged in.','2022-04-19 14:57:17'),
(149,1,'User logged in.','2022-04-19 15:54:49'),
(150,1,'User logged in.','2022-04-20 12:09:40'),
(151,1,'Updated user a f','2022-05-02 11:21:39'),
(152,1,'Created user sf sgf','2022-05-02 11:22:17'),
(153,1,'Updated user sf sgf','2022-05-02 11:22:23'),
(154,1,'User logged in.','2022-05-03 10:54:00'),
(155,1,'User logged in.','2022-05-03 11:01:10'),
(156,1,'Updated user Changed a','2022-05-03 11:02:22'),
(157,1,'Created user 1 2','2022-05-03 11:02:50'),
(158,1,'Updated user 1 2','2022-05-03 11:03:07'),
(159,1,'Updated user Ch 2','2022-05-03 11:03:27'),
(160,1,'User logged in.','2022-05-08 12:52:09'),
(161,1,'User logged in.','2022-05-08 12:59:43');

/*Table structure for table `media` */

CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `hash` varchar(16) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `deletedBy` int(11) DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `media` */

/*Table structure for table `migrations` */

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `paymentdetails` */

CREATE TABLE `paymentdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `quoteId` int(11) DEFAULT NULL,
  `paymentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `paymentdetails` */

/*Table structure for table `payments` */

CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quoteId` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `paymentType` int(11) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `nonCash` double DEFAULT NULL,
  `customerId` int(11) DEFAULT NULL,
  `accounted` tinyint(4) DEFAULT NULL,
  `n500` int(4) DEFAULT NULL,
  `n200` int(4) DEFAULT NULL,
  `n100` int(4) DEFAULT NULL,
  `n50` int(4) DEFAULT NULL,
  `n20` int(4) DEFAULT NULL,
  `n10` int(4) DEFAULT NULL,
  `n5` int(4) DEFAULT NULL,
  `c200` int(4) DEFAULT NULL,
  `c100` int(4) DEFAULT NULL,
  `c50` int(4) DEFAULT NULL,
  `c20` int(4) DEFAULT NULL,
  `c10` int(4) DEFAULT NULL,
  `c5` int(4) DEFAULT NULL,
  `c2` int(4) DEFAULT NULL,
  `c1` int(4) DEFAULT NULL,
  `outToBank` tinyint(4) DEFAULT NULL,
  `checkedByManagement` tinyint(4) DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payments` */

/*Table structure for table `paymentterms` */

CREATE TABLE `paymentterms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `paymentterms` */

insert  into `paymentterms`(`id`,`type`,`created_at`,`updated_at`) values 
(1,'Not Applicable','2017-04-18 01:25:34','2017-04-18 01:25:34'),
(2,'Comptant','2020-01-27 04:42:24','2020-01-27 04:42:24'),
(3,'Credit','2020-01-27 04:42:32','2020-01-27 04:42:32');

/*Table structure for table `paymethod` */

CREATE TABLE `paymethod` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `commission` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `paymethod` */

insert  into `paymethod`(`id`,`type`,`commission`,`created_at`,`updated_at`) values 
(1,'Cash',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'Credit Card',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(3,'Cheque',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(4,'Bank Transfer',0,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `productcategories` */

CREATE TABLE `productcategories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discontinued` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `productcategories` */

insert  into `productcategories`(`id`,`type`,`discontinued`,`created_at`,`updated_at`) values 
(1,'legal services',0,'2017-08-04 05:54:27','2017-08-07 06:50:52'),
(2,'accounting services',0,'2017-08-04 05:54:43','2017-08-04 05:54:43'),
(3,'financial services',0,'2017-08-04 06:11:01','2017-08-04 06:11:01'),
(5,'general services',0,'2017-08-08 06:58:19','2017-08-08 06:58:19');

/*Table structure for table `products` */

CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `supplier` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `purchasePrice` double NOT NULL,
  `salesPrice` double NOT NULL,
  `image` blob DEFAULT NULL,
  `discontinued` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `isWork` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `supplier` (`supplier`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`category`,`supplier`,`name`,`description`,`purchasePrice`,`salesPrice`,`image`,`discontinued`,`created_at`,`updated_at`,`isWork`) values 
(23,2,5,'Comission Location / Alquier / Rent','',1,2,NULL,0,'2020-01-27 01:40:19','2020-01-27 04:26:49',0),
(24,5,5,'Travaux / Obra / Repairs','',1,2,NULL,0,'2020-01-27 04:24:50','2020-01-27 04:24:50',1),
(25,5,5,'Comision Vendre','',1,2,NULL,0,'2020-01-27 04:26:01','2020-01-27 04:26:01',0),
(26,5,5,'comment','',0,0,NULL,0,'2020-01-27 04:47:17','2020-01-27 04:47:17',0),
(27,3,5,'df','dfd',3,3,NULL,0,'2022-04-29 01:14:38','2022-04-29 01:14:38',1),
(28,5,5,'dfs','sdfa',23,20,NULL,1,'2022-04-29 17:53:27','2022-04-29 17:53:27',1);

/*Table structure for table `quotecomments` */

CREATE TABLE `quotecomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quoteId` int(11) DEFAULT NULL,
  `placedBy` int(11) DEFAULT NULL,
  `placedOn` datetime DEFAULT NULL,
  `comment` mediumtext DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `emailId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=223 DEFAULT CHARSET=latin1;

/*Data for the table `quotecomments` */

insert  into `quotecomments`(`id`,`quoteId`,`placedBy`,`placedOn`,`comment`,`deleted_at`,`emailId`) values 
(218,85,1,'2020-01-27 13:49:00','Status changed from Enquiry to In Progress',NULL,NULL),
(219,85,1,'2020-01-27 13:50:00','J√°i montrer la maison ref r15647',NULL,NULL),
(220,85,1,'2020-01-27 13:51:00','Ref 456987 et ref6489',NULL,NULL),
(221,85,2,'2020-01-27 13:53:00','J ai eu ali au telephone ',NULL,NULL),
(222,86,2,'2020-02-07 11:15:00','Status changed from Enquiry to In Progress',NULL,NULL);

/*Table structure for table `quotedescriptionrows` */

CREATE TABLE `quotedescriptionrows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quoteId` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `image1` int(11) DEFAULT NULL,
  `image2` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `quotedescriptionrows` */

/*Table structure for table `quotedetails` */

CREATE TABLE `quotedetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quoteId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  `productName` varchar(255) DEFAULT NULL,
  `purchasePrice` double DEFAULT NULL,
  `unitPrice` double DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `visitDate` datetime DEFAULT NULL,
  `finishDate` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=273 DEFAULT CHARSET=latin1;

/*Data for the table `quotedetails` */

insert  into `quotedetails`(`id`,`quoteId`,`productId`,`productName`,`purchasePrice`,`unitPrice`,`quantity`,`discount`,`visitDate`,`finishDate`,`description`) values 
(268,84,23,'Rent Comission',NULL,2,1,0,NULL,NULL,''),
(270,85,25,'Comision Vendre',0,239,1,0,'1970-01-01 01:00:00','0000-00-00 00:00:00',''),
(271,86,23,'Comission Location / Alquier / Rent',NULL,2,1,0,NULL,NULL,''),
(272,87,26,'comment',NULL,1,20,0,NULL,NULL,'');

/*Table structure for table `quoteemailattachments` */

CREATE TABLE `quoteemailattachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emailId` int(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `mime` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `quoteemailattachments` */

/*Table structure for table `quoteemails` */

CREATE TABLE `quoteemails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote` int(255) DEFAULT NULL,
  `to` text DEFAULT NULL,
  `cc` text DEFAULT NULL,
  `bcc` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `filename` varchar(10) DEFAULT NULL,
  `sentOn` datetime DEFAULT NULL,
  `sentBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

/*Data for the table `quoteemails` */

insert  into `quoteemails`(`id`,`quote`,`to`,`cc`,`bcc`,`subject`,`message`,`filename`,`sentOn`,`sentBy`) values 
(28,85,'parandeh@urbytus.com','','','Quote #85','Dear Ali,\r\n\r\nPlease find attached the job quote #85 that we have created for you.  We look forward to hearing from you regarding this work and hope that you are happy with the service we provide.\r\n\r\nKind regards',NULL,'2020-01-27 14:10:00',2),
(29,87,'ap@businessdevelopment.es','ap@businessdevelopment.es','ap@businessdevelopment.es','Quote #87','Dear Cliente Contado,\r\n\r\nPlease find attached the job quote #87 that we have created for you.  We look forward to hearing from you regarding this work and hope that you are happy with the service we provide.\r\n\r\nKind regards','wujz5xoazO','2022-04-20 10:45:00',1);

/*Table structure for table `quotes` */

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `createdOn` datetime DEFAULT NULL,
  `customer` int(11) DEFAULT NULL,
  `requiredBy` datetime DEFAULT NULL,
  `estimatedVisitDate` datetime DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `completedOn` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `assignedTo` int(11) DEFAULT NULL,
  `adType` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `supCosts` double DEFAULT NULL,
  `startedOn` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assignedTo` (`assignedTo`)
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;

/*Data for the table `quotes` */

insert  into `quotes`(`id`,`createdOn`,`customer`,`requiredBy`,`estimatedVisitDate`,`description`,`status`,`completedOn`,`createdBy`,`assignedTo`,`adType`,`vat`,`supCosts`,`startedOn`) values 
(84,'2020-01-27 10:38:00',40,'2020-01-27 10:40:00','0000-00-00 00:00:00','',1,'1970-01-01 01:00:00',1,1,1,2,0,'2020-01-27 10:38:00'),
(85,'2020-01-27 13:43:00',41,'2020-01-31 13:44:00','0000-00-00 00:00:00','3ch 2 sb  180000\nfuengirola',2,'1970-01-01 01:00:00',1,1,2,2,0,'2020-01-27 13:43:00'),
(86,'2020-02-07 11:14:00',42,'2020-02-08 12:15:00','0000-00-00 00:00:00','Demo- Casa en Sierrezulea Apt 4B',2,'1970-01-01 01:00:00',2,2,1,2,0,'2020-02-07 11:14:00'),
(87,'2022-02-16 15:56:00',40,'2022-02-16 15:56:00','0000-00-00 00:00:00','cquote by Ali.',1,'1970-01-01 01:00:00',1,1,1,2,0,'2022-02-16 15:56:00');

/*Table structure for table `reminders` */

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `reminderDate` datetime DEFAULT NULL,
  `createdOn` datetime DEFAULT NULL,
  `createdBy` int(11) DEFAULT NULL,
  `sentTo` varchar(255) DEFAULT NULL,
  `sentToOutlook` tinyint(4) DEFAULT NULL,
  `read` tinyint(4) DEFAULT 0,
  `dismissed` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `reminders` */

insert  into `reminders`(`id`,`user`,`title`,`description`,`url`,`reminderDate`,`createdOn`,`createdBy`,`sentTo`,`sentToOutlook`,`read`,`dismissed`) values 
(19,2,'call tjhis client','','https://iberhola.pepper-crm.net/quotes/85/edit','2020-01-27 13:52:00','2020-01-27 13:55:00',2,'2',1,1,1),
(20,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(21,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(22,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(23,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(24,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(25,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(26,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',1,1,1),
(27,1,'Carine visit','11:00 2 Calle Equitacion','https://iberhola.pepper-crm.net/customers/42','2020-02-04 14:01:00','2020-01-28 14:03:00',1,'1',0,1,1),
(28,2,'RAPPELER SAMIR ','','https://iberhola.pepper-crm.net/customers/43','2020-01-30 15:10:00','2020-01-29 15:05:00',2,'2',0,1,0),
(29,2,'SAMIR','','https://iberhola.pepper-crm.net/customers/43','2020-01-29 15:12:00','2020-01-29 15:06:00',2,'2',0,1,0);

/*Table structure for table `renewals` */

CREATE TABLE `renewals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `discount` double NOT NULL,
  `renewalCount` int(11) DEFAULT NULL,
  `nextRenewalDate` date DEFAULT NULL,
  `renewalFreq` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `cancelled` tinyint(4) NOT NULL DEFAULT 0,
  `cancelDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `renewals` */

/*Table structure for table `sectors` */

CREATE TABLE `sectors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `sectors` */

insert  into `sectors`(`id`,`type`,`created_at`,`updated_at`) values 
(1,'Private',NULL,NULL);

/*Table structure for table `sessions` */

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sessions` */

insert  into `sessions`(`id`,`payload`,`last_activity`) values 
('0061e451181a189e4ca2ed1ae2568a6fe9ffb5ae','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2ZkRm1iYVlqbnhEREtuSk9IbnM0aTBmUmFPUEdpeXhIVE9udUM2RiI7czo1OiJmbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9zZjJfbWV0YSI7YTozOntzOjE6InUiO2k6MTY1MDUxODUyMjtzOjE6ImMiO2k6MTY1MDUxODUxNTtzOjE6ImwiO3M6MToiMCI7fX0=',1650518522),
('3ba1249f472c214d8043cfe6b4597030b26f7aa9','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3NMN3R4dDMzdnVwZjlWMXRvN2FaMXVHZExFdkpCUmxWYXhmOFlwQiI7czo1OiJmbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9zZjJfbWV0YSI7YTozOntzOjE6InUiO2k6MTY1MDQ3NDMyNTtzOjE6ImMiO2k6MTY1MDQ2OTE5ODtzOjE6ImwiO3M6MToiMCI7fX0=',1650474325),
('9951f6c20d126c16cf1e12337e1a18f264fff20e','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXQ1Tjlza2N4d2hTSGIyTEVHOTl6VWc1WEhBNWlGQklHd29WelZTdSI7czo1OiJmbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9zZjJfbWV0YSI7YTozOntzOjE6InUiO2k6MTY1MDQ3ODUzNztzOjE6ImMiO2k6MTY1MDQ3ODUzMTtzOjE6ImwiO3M6MToiMCI7fX0=',1650478537);

/*Table structure for table `settings` */

CREATE TABLE `settings` (
  `name` varchar(255) NOT NULL,
  `value` longblob DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

/*Data for the table `settings` */

insert  into `settings`(`name`,`value`) values 
('additionalInvoiceLine',NULL),
('app_name',NULL),
('awaitingPaymentJobStatusses',''),
('bankDetails',NULL),
('chatEnabled','1'),
('companyAddress',NULL),
('companyName',NULL),
('completedJobStatusses',''),
('defaultAdType','1'),
('defaultDirectDebitBankCharge',NULL),
('defaultJobStatus','1'),
('defaultVat','1'),
('developmentNotes',NULL),
('emailFooterBar',NULL),
('emailFooterCopyright',NULL),
('emailTitle',NULL),
('icon','âPNG\r\n\Z\n\0\0\0\rIHDR\0\0\0¿\0\0\0E\0\0\0°Èl\0\0 \0IDATx⁄Ì›{ú]uu7˛˜⁄Á0Ñiöéi:‰ïbä)\"µëR~Q¡RA+är´WP@ÃLê\"•ëLòdÇQ1ô	}®¨>(äƒ\ràààà#≈àc1Õ/∆8NŒŸÎ˘co¬‰	Pü˛ ¨◊krô≥œ>˚ÏΩ>Îª÷g]æ·˜-.ËT{coa‚œeÓã©áùÿHÆ!6kd,\'åï’O.70sÉ1ì\'!Ò_ØóP˛Aá0YÊÒLAßpøt?Ò3roëÀIDÅΩ1C¯˛D⁄›Xá˚àÎîÓ¡FÎÀ“‡Ã±ß:&ˇM\0–∑pqÒà*ÂÕOâb»ºèZ˛EÖvy^\'„<Û{VÅº≤∞˚∆∑°C£c°˛3K≥é”å+ÖÒRWµbƒÕ2ø™lﬂÊ›Ô{¥cÚ\0˝eLñ*e¶E\\Gﬁ-cπ»…8ü∏U¯òy=√õﬂ{¡PS‰ÖøÃÎ]_ipÒ>≠é{]|&}ÉØ≈Ú{g„`‹\"„JÖªª\rÎ?sÏ)è…Ô	\0ï¢èY¬J,P∏YÔ∆≠éõÑsQó\ZËY≥˘µ9É„§wb£V«BüŸ™ﬂs˛≥Ù∂Ù\rN√˘zœ¨_?™~˝Á8+Ñ˚E«¡òl_äßFÒ5ı\rN«’8˜KkàeZZ€?–ªF∆≈xòºÿú¡©˙V◊2ØwòXàgiéúÏ]õ „∂:8>4äÿË¬¡ÁV\'ÕUïõT\\Nºì•+LπDﬂ‡4˝◊å=Ì1˘/\0@ˇ‚ 3p÷ 8I∆kp•ÃÖ>≥ßlÛæ˘=Î5ä˜„´“•ƒÅè§gΩt1^Æ·EõèOÉ8Àú¡â˙g¥àS:≈ãâ\\INÊ·Rxà∏ó‚π∏ZkÕãºkq1ˆ»«‰©sÅÊN%.êyPÌ£∆@Ì∑CﬂP7yé√e|ﬁ¶ÊZóºmt†\\á‚L|Z7ò7cƒª”‹¥üÃÛÖä‚øj”ˇà’¢¯WeŸÆê1W≥µRªq•,fâ\\\'\\(}Tô´æP±Nyôt≠Åô«˝ò<π`Œ¬Ω§E‰ÅDØÅﬁ›B˘+KæZŸ¸q>¶íÏ∂È˝˝£éôY\ZËΩçNêyÜYCMÔ>ìy=ÀpµtéñIg>â#E´À¸ﬁæ/r˝Ô(Ò#QÓW¡:+x7íêpÅå^‚ù˙;«˝ò@„	ªà∏{gS~”ísª«ﬁ˙Eñ‹ü;Ê¬èÖeÁK˛Ú5éx˘√nπ±G˚+mKD´pò√èYj…çøq¯±?CS‰ÎΩ¯Âwj˙•åêq¥#é˝ñ*qvÑ%7~›KéÈ¬>¬w•óﬂÒ\'“çÊádY‚ÿ◊ãè˘∂ø=qÉ[>7¶c+¿Æ(ˇ–4\\&≠¬ø·5ƒªÙ\r∏9ê›ûÃÔmËΩG´yæIú≠Ô‘∑hRKÃ`~œ\ZQŒ˛?ò34’@OÀ∫ºV˙ô\"Œ÷ä¬¶∆gÒ,eÓØå•ÿÀÉ„e,ûC£õΩª©¯â˛≥J<Á`™¬eF6NSÅ±`Áeˆ–4·\n<$Ú\\mÎ1NÊsÖ„–ÖÔ‚.e>‡7≠ç>€	ú—.\'‡D¸-æàÙÆ≠›´¶åcp*.µ©qáéV\'qûÙY|\\î·√{úm‹oOñ:àkE^&¢gK%è!Ôï±J8ì<õx#Ê‚fï∫vL∆\\†«ñÜ∆+ÚΩhà8◊ºﬁá›zcZr√&Kn\\Èàø˛:ù?$ûQ˘Ë^k˜∆Ó{ŸCñ‹Ùª-ŒuÀ\r,πÒwé¯ÎªeÁàø≈â?f•#é¸ÖπÔh;‚Â?V∆OEúØôøV˜…¸n,o‘àoJái∂Ü•;‹\"Ç‡á¯+ﬂ∆·ƒ˜5ä’2ﬂ\"Õ^ã)?Êñ‹ÿSá±`ÔP\'.ñπ?Në± ¸ûÌ€øòˆ¶Bëîé\'^H˛Ñ∏W‰]äÊ™⁄yÙ¯rS!s?Url>*b©MJªŸõêæ)\\•‘-\\$Ûç\\.ã+pæå”Ò% ó_√aµÖ?°v’N%I\'ãº[∆]\"?åpÅ∆¶èÎˇárL%∆\0∞ï€s9—>û∏å<›@ÔÕªÙ	u*sˇZ—ûè’¯çdªe~o9äYö@º\'HﬂTWŸ‘Zß—úLyZ2Á˚Á£Gaäh…\\%≠S€≠ˆR∆·ï2oﬁ$]RóN¸X⁄ úá≥DﬁaﬁX1›\0∂Ù˚ßãºÜ¯àFÛ˙ﬂ÷z¬ü÷ø∞©˚„$UV˜á∏K#Ó’?™&®aßvºáÀºN∏©™-ﬁå‚\"b*yq¶Ëπ~\'æÀÅä,eAé#˛Ö<´]é(ã„\\4c˝òZå‡∫≥ó·˜ã|\0?¬2Tñ6[≤QfÀªﬂ±sü˙Æ°¶FNˆó˛öòL~Y∑»rùˇÃœ(öî”à7a\n±@ë˜+ΩØ&.$P‰sg>∏Û ººCª|Ω¥D‰ŸXK|é\\Ñ≈\Zyï˛ôcÆ–\00{¬ºFª}ü¢ÿõb?ë˚äxñLÿ(m˘0Ò†åï‰É~≥iùÀv¬ß>c1›õ∫EÉˇk•ÔQ‹n˛åïuuÈ8KZãè»/‚u2.2T!›NÉ‡ä¶v´á\\+‚/„8r/·$Ûzóç©∆”\0sv…∏Ü¯Ü2˛ŸE3F∂≤§M≠ˆ°KÍ—çΩ…?UqÔ≠zµxP∆≤JŸr#1¨,Ü]4cKp¸”Â4€DL√AÚÁ¬\r“rëdZªOﬂêq≠,÷∫ËÌªn≠˚?Bπ°˚´öpæ+„ëWbâ‘∑El2&O3\0Ù/(¥ã◊„\\/7ØgÂÆ)ÿ¢B;\'í˚	{KœÆ≠v´\nd˝Vƒ\nÚAÚA\r+ıœlmuéeyêÙrL~Ü€µ=†p(>o‡I*Èú°U+NñxQÂZôÆàö€Û–òz<]0gpÇÙ|U„WÛ∂®›y\"2ka°0N„…Òƒƒ:à›¶J†≠¨Xô|ê‚!rÉà\r\"FdN ß„:x^`†w≈ì˛ˆ≥tRºµj¿â[••‰\\‹´˙{Zc*Ú?[ö€˝m∆tÏ%‚3OZ˘·‚ô%6÷?´±wUñæüˆƒŒ\n1MÿWÊãÖ&YTqF>,‚~È\ZëÎd±ˆ)˙ˆ#Jüë1^Ê4]ÉÒï⁄yµ™ÖsLûV+@ˇÂÖvπ@‰8°◊‹ﬁ·ﬂÎΩÛ=ÏæGS°S5%¢S∆îjÇD˛πsÛz?Ùî}ﬁú¡’/U]dJãqô∂´ºªwLKûV+@ª‹K◊(Ìeˆ‡ˇù+€¢∂4WK´q{Õ=u“j‹%⁄+E]òà∏ì<A√«T’¶èJﬂ‡8≥D˛¿@Ô\'˝˝–Dú#MèúgÛw˘ôpãÅûª∂Ôä\rΩä¸õÕ|Yˇ1˙ˇ’?/3–Û`˝û√pú–‹Ú¯¸qátß˘=ÎvÍÙ\råcÒ	=ÀÉÏ1Äo„3z ∑o5¡ÛÑ©~åª\rÙ‹˛8◊r¥™ÎÔ*O Î:^:\\∏“@œΩªÄﬁ™˙~ì\'x”„ki<Å◊û\"‚´∏Â);m£ÏñN&˜ÿÍæ,\Z]l∏å·ÕƒM¯‰®W∆„4ad≥ÎT›ÇÇ|âåÛÙ\r]Ö>=√[≠√áÀ8\rK±aã≈y¥!»Ïıû˝qñ	Î‰Ê„«„tëkÙ\rΩ	Kw®®èﬁ⁄Á\nß…º;@Dì|—I~f+≈+»}•Y\"^äu“\Zi§6.á…<_ﬂ–Édn7–ªΩÈ„u“\rxhï˙D £”Ï°3ÕÏÔæ%\0&4\nÚ˘ƒùzf˝∑Yß˙˚9q/•˝<U}Ãõjé√}\"ñYÊüœ1gp2ˆëÂ¡∏i\'hÉ— ˙qÛ{.e\rõƒﬁ‰yƒ€d~uõsV˘îeúj~œ≤ù∑πFƒ9zn€J	é¬Ö‰e™ä⁄;Â«N|flÁ‡æ!8àX$L&/ìæ\"‚~=Õ*Ñ©\"¡Ÿ2˛M‰%˙Ü>h†gdõy¬∂3è™í™±Lxë¥Ôcz\0Dv‡·√øw%üı¡ÕMân’‡´©ïœS¥sí™ﬁÁ!UÈÙS∏∞DANó^c∑é.}ÉÎ•ïU0úGÍˇóõÙü∂ÁÂ≤åñ™kÌ}ÉÔ√!\"NŸ%P=ñ“nØi†gçæ°èÀÿ |\ná>.\0Ú1Œ∑Ωœ‹ˆzß·∫ä÷é`Âyî \n?XY◊◊›ysÎ’ıc€|»π≥á&·tÈ>\\*|DxïŸCÔ5«lﬁñ\0(ãçröÃßû˝Ë_Xy”Eı\rGötÏS”õœ£µ/&`π\Z?¡∑Í∆õ’⁄±Z#∫)è÷∑p3ü¸5ˆ\ré\'æ&ä˜ rº4	ìQV•\Z&`˝„zvè˚–bÉ¥^Ë‹ym~ºSÓ‡CzË∫Ø~æ˚<ÈÛms©9˙6•≥——˜ò~˚@l–7Ù>·\0ôÁËº›@ÔÚß@√ˆó!œqπßà¯–6nÏP‰4´k•{Ú“∑∞SF∑0E€‰ä…ÒL≤õ›&êk•	ﬂëyµﬂ˛vπ˜˝ck#’°›ûN•·˘µí\\#\ZÎÕæú˘o≤X°åª»ìdy¶Ùëw»‚Æ\ZÑWÀ¢{\0Ï°∂ï<W‰¥zºÀìèóÒÔKì…RƒÍù?gÓ‹Gå>ˆ /Ú_\rÙ~mß>ßZ•à∏Z:ﬁÏ°˜?ûØ˛8æfêÀâÙõ=¥H¯é¿µ;ÎÌ+≠ívÆ\"ÚÇEÌfuû(dLPƒ>≤úéÁ`Z˝|Vbµåëw`\r÷h‰⁄-2¿=È[ÿ9A/Qñ/√ﬂ.±JñØíÂ	gÎø¶•ˇ§\'v”Œ_LéºV‰≥D\\!\" é$OæÉâıœË{ÙàÎ¥£3w‘¬Òô\'‡úE¨!Ô¿üﬂ5ƒˆÿ≤:MSÑƒjô˜=	R|˚n^Ê®cc∫0©&\'v¿KÑµ¬_◊t˜∆GcÄ]5πøàâ´5ﬁy±L:≈Ï¡ÎÕﬂ>ùø5\r∫_u”b›ïf˜Vπ¶»úJ<›“xëMô´TY_ê≈Rç=÷Ë?≠‹â’bJMù˝ïån|W∆E\Z±¬¶¢C¥éñ9ã¯°ÃãEy¥ˆ√∑U.”.ªcÖ÷»°U@±NÊ≈¯∂Ãèõ?Ûz}ã∫…£Î”Ω]-ŸÒ3zßåwnÈ3\'b	ŒÒ‡ˆ]èúàı\r≠ﬁBµÚÀ•[Ã4ft‚8≥ßoˆù3ˇ¨Ítãnú\'‚éùº+„àø”7∏œñË\ZÂûe4•é-VÄÙÏöò∏góû¡¸ûçf= r\n—‹Ö˚ª=ÎJ˝øOmˆ˜#¬\r¬ﬂ/¬Wv\0LÆöÊvJ„§â¬~ÿóëÁ»òTï)X)˝T¯w¨&÷â\\´ÿ¥aß:´ﬁµ∞–(&`öÃW‡Y™~‚œ	(¨’.õZ¶+Ú-µÖ¨óÙ”ÒSiÇ96öwˆŒ√Ì,î≈dë%z…èTåâ£E\\jŒ‡5 Ú6´;Æ≤ÁH/1~|“«∂ ‚ Èu¬\"Ã2{h…∂K~tWÀ∏V•oè(ÙfM∏A∏£&6Ûv¬õeå‘\n3ô¡gÒ)\\o`\'À9“8¸=F’˘≠Æ°XGı˚xt≈´˛πÎe#Uq‰‰mòΩ][\0&„µ∏˜çr≥FÙ\r}JÊÖ„Í{>Úx\0òPMó÷|r≥ÊµJﬁèØ˜kÏπ^ˇâO–ıX–°£8PzQ•Ùπ_íﬁ≥≈25{po·ı¬3…OêK*j“,_%Ôyæl,–∑∞00Û˛ùz\'+5eyq£àE2œ¡mj|H≥}3N^¶{‰”Uî„∑œò‰∂˛CÂ\"¨0–≥d´óË˙ﬂ2?\"‚·$<∏’Ió·Ñ«LDmKπÆ¬ÃÔπÕÏ°ÚK¬‚t=;øwB•pÎp™ÅﬁØ<F\"¨Sƒ/∂zÔ√è∆ªZ:SeÆ©ã$wëí⁄,\'÷y´+∑…wÙ‹©oËvú(ºoÀ{æ}\0å«¬G´fóXO±ﬁHcÿ{û‡ÄŸ˛+(ÀÒ≤Ïñ^àu¬◊ÖOjó´\\T[ûLlT”‰“ﬂ‡K∏\\;÷i8o’˚\nÃ¬ç‰4‚\0}Éü¨3ékÑJ%õYó4\r]“¡∏ö<]€b\\®–´£=Eô£¿A\"ORçUú∞m€gLãEËYiˆ–2ØqÄæ°kV‰1@ı∏ÙQs9øgDﬂ–E‰G…3Ù\r-‹Îøsñw≥ˇøÖÇ.≈F·EªÄŸC˚ ‹wë#€òˇùâfMñNê9lˆ–æ€9Í´‰+dº\0¯·SˆªÃÕ^>EŸ>/¨≥Åﬂq±y=€“^sÜ:îyîÃø√èDûoﬁÃ5˙uà<Qz°àKdÒÌ∑„˚\ZçØh∑É¯{ëÀ*˛ﬁ›™Y˜™¶Uﬂ\\Ö.∆kÑ•è(úE\\\"Úbo∆?‡Ézn◊ø¯NÌë˝Ñq[Â\r∂Ø(;√Üƒ∏zŸ~å$”ŒZÌmé_\"„_Ñ“ù˙Ün€≤]B¬Œﬂõ?˚.‹+ù†oË˙-&}ÔÿooJ\'Ö»/nìﬁô{9{êpˆ´‚O∑nQíπòÚ≥á>πyﬂâ\0`˛	+¸CMEvc:^H{oôﬂí(bµ∆Ø÷oS]:gQá≤‹_ÊI¬0Ò>,W¸™•oaóvûF˛Ê\Z)÷⁄≠˝Fî2?´›>ﬂó˘@5ÀµU2$Ó˛™¢≈<£ˆO◊‘äwçÙ&\\ }A‰‰d„CäÚ2Ù-ºƒÔ6=h7√“ÜÌ+@Óÿ\ZÔXˆíŸ\"÷n{h>°T¿Ô©¸ﬁK\n·B’L÷çOPè≥˙@Æ$ã—´oË¢m≥ª[(n!ä”ÖÍ	ªûâØ*ôX.ºN∆»£ÆËËï·Tº\rGô=t’Ëâ&€†¢Ìv—ÕÍPÊA2è≠Xì¸©à[E\\º√j“”≥Á»^≤<M¯c\\ß—±Dˇôem%&ìÁì?í≈≠nn‘ΩÈ(¸E≈Ÿ«jîo—.ƒ*ë+eLP5π Ü…_ìù2né¥.ÁÍäó	Gk‰ı ËñÒfÌ÷Âö>´+EÃ“Ã´+˜\'÷Ôˇ;ŒWÀu7ﬁTÁYÓﬂ¬2«„º˜1bÓm.c†gÉŸÉó‘£+O∆ø<˘∆V4ËË7\rÙ“7Ùø+Éßm}Cóow%ËÏîq|ï	Œ5ıﬁ√€˝bÒ∏÷‡\0U’¬Özñ>ŒäsN√IılŸ·`ΩÃâ.XP∏ËÏÚ1≥∫ÌòP1C“Œêkdqù(Ô√zÛz∑Ô^ä∆d9r¥™¡ÂS∏E#7Í?Ûën≤Èdo’¨ÓfÛgåË¨:∂\"/À›◊È9ü”.ÜUmíãiåßl ⁄¬ÜRZAÏ≠ÃÎ5‚TtìW„|Ì∏]‰µƒ\ZÒF≠º èˆº√~ø\\É^Èπuêæ≠∆lÔEê¶Í:l+ﬂz∫Ùñä:6KX∫˜gºtêæ°ÓÉ!WËΩÀåÏvcëÎÎ{{ææ°eè[Åô;ÈçÍË∞äΩŒØ…íƒ©˙Ü>ãØ◊¡uJ«’eÌ7ó‘ƒ ˆ–ÿ)®ohG∆x•tV5§!>ªâ∑˙Ü>©*|nÌ\"o\0?¡>äb¸v≥üß/fœ÷dÌÚï™ÕÓ÷’Éh/¥Òw+ºÔº«¶?ˇiQSQQ+ÏwEqæÅ[f*€˘Ú\rƒá7?∏Ü¶êßbÅ¢Xkœ÷tiötô0\rù~Ÿ±‘‰M/\"~™»ëÕ3¨êˆÓôÙ\r~G‰‰\'â‡ïÊÕ¸_f}\\‰,‚ü:Ò6<‡ÇÀ/T¥Oﬁ&œèk6_/Ω~´•|U≈bEüp”6lEu™ΩÖ´∑àFØ6’ÔˇUU‹ˆÿ÷{†gÉæ°ãe/‚|}C\'<¶[≤+.Pló”ØºáŸÉÔq;éì˘‚ÌU}Yù«®h‹KâõÕÔyå>ìúB\\πmñ:‘«\rU5l\\ª=fgÁ¸q≤täæ°{!∂¿}\"N≠≥üÎı_Qh∑\'ì˚a\Z#/Sï´VeΩ¨Vƒà˛≠≠«+Ï+L≠πÈU“F W„2f…rç˘£\Z„´,ßUsz‹ÑÉı\ruUõ]‰Y“\"È^≠Ï\"œﬁgu«Z{nz-YÍ9Czq}C@≥\nXc9eù.éõDŒê≈W»ªpiΩ]”}“u¬)˙G4ä;m*ªàµuÏ∞’*È/Î1Z¬sD€Z÷(1\"çÏ`¢ﬁ,\\∏EÜ57Ø(*>~¥®ƒcXΩ?Ø=ä«Àì|\\∫váÁzT6 x¶0\"b[cW¿›¶oË°Éléquàºÿíö\ZùyÆœïY√;(©ﬁé˛«m¬≥Ti≠Ì„yˆ‡^¬∑p¨Åﬁ{Ã^xêàsC+§íÏÆÃÔiïÙÓw¨ﬂ ﬂÍÆ|w•-≤Sµ◊Túi†wÈVTÈÌ÷π8X¯rïçVê©jVøG¥Œ5Ôúa}è\'˛F4œ°}àÃÛÑÔ(›[SûSÖ#•N‚tç∆˝⁄≠è÷±BﬁWgxó?ÆnFv‚Ø∞\ne„—>§\n$Û$3ü|ˇÒò¸∑îÿ&H©≤±∑UI†Ë (◊htWåÔ_¨¶–°té≠\\â\\_5∫Á≥§©u“a©\"KsgVı‚e¬¥j\0ÎD<$≥KƒÚy¯Çﬂ4>È[’{.XTç≤ú®(é^P[„#Í¥v•¢}°lõ◊SïjÏ6B¥;h°\Z›>BÄ/‘_˘92/≈2≈•yo•oäÇÿgëÕ*ò∑Qo0∑gl€’ß	\0:¢)ÚR•e¬∞‘¨fˇ<Rô´0,≤ìÿGµÒuY¬ñÀbΩ¢l\nù“d©P—ﬁ`„nÏ—öÜ©“∏z~Á\n+ïŸ$:&+≥S´îπ÷oá[:«MÆíYŸEl¨°Q l*r¢2&âXØù+ÌVéhù\"ˆ´™O≥%bç≤XÆ,7hhäbºÃ…2™`πŸ÷jt( ÈXÄÎÙæwLM˛Á ñ1@c”àr∑ØÀ|ìë=Ó∂€∆à◊›¬F _‡èÎÏË∑àÎÎæÀ™˜Úú˜3~∑©‰õÒ©ê~é›d9ô¯ë=Z7ËΩwã¨aﬂ`óÃ„+Ê\";ïV„◊ |&÷Ÿc‹óm €\\<s’(&™CH\'`¢2÷„ó“û\n„ï≈◊n6ØÁÓQyäBQN”÷äN-[¯9πª¬^Jﬂ◊h∂äW¢K‰\rc*ÚtZ*e<WV-m&üêÓ“Ë^£ˇ$ﬁıÅBQÏ+‚® oéóπ\\ƒƒ™ù“Å®ËÀ\"Wò;≥‘ªêÆòDºT:R¯ÚŒ⁄ﬂWxaµr∏Z#óõ[ob7gaßåÉà„j∑‰ÀU†ñ{/&)]¶»•ÊÕ¨ö˙/ojó˚êØ¬_‡À“CB7˛ü:@˛4n{¨4Ô≠•Yãçë™4‚HU¡Ÿ°¢|ﬁ.⁄ç…ˇ\0t‚SµBûÁ¢ﬁaΩ_tjDóåçZçu.9ìæ¡âƒµbïU“ mZç˛˘ÌUÇ¨ù]Õ∆Z˝ooyÁM{¥S5R<?ÓT˜Íü—“ø®@óv9N£XßF\rÜ¡È“a¯3Úa‹#„Û{7Ë_„µcÇ(6˙Esùü…Ï¡IƒQ\"ˇB¯PK¨nÆÙ°3´Üõ≤ú®,J#ªØÒû∑î˙tj∑VLT^``l\\˙”\0ﬁ¶\Zxvù88ºv{÷VÂ¡˘3|û∏ãL^Âu ÿ_‰Q¯s’tÄfM~\Z˜\ZË›í[ü3ÿQF≈+j0çT1ÜÒ“Weﬁ¢mu=`Î®PFóå£…TLN¨Ø\'¸L‰\'âÕEì]∞à¢è§cÖ)ııB)ÚSƒ:È”8S£uã˛s∆¥‰iÄŸC{	ﬂ ó·ﬂq≠∆ûË?±4Î\Z≈KEWÛ‰7 rÖ(&‘˘Ç‘gπNëwò;sDÔÇBW±/Nï—%‹(si5˝ÕT·Èô¯í\"øfnÌŒÙ-Ï&N∆Û•o+,©¸ÛúLTˇ˛{äºﬁº∫G∏ˇÚNÌÚÚ’™‘e¨&˚í/&7ü{‹mﬁ[GºkqS12o®&‰ZÈuÊ˜éÌ¥¿ú°\\R5;≤bPLêJel–6¢Yé1ΩÍ∂…g‡◊xHX*côﬂÌ1l‹pßÃq2á±Q4:îÂ‘*ÎœÑﬂbµtüpü·Æ5∆≠GŸYq†≈Üj*tvìá÷´CÅá•˚Ö˚¥ \ZYàÊx≤†ÿ®√\ZÌ.i∫pò™¿Ô?…áD‹K>†P≥D:U9ãı2˜≈\'à>ç5ü}J∆Bé…ˇPÅ‡@Èj\\C˛°™È†™dîﬂ!n2–≥~.‘~ƒÒd≈U€˝T‰µäµmW±˙ªT≈[œV\r¿mì7¯Ì›ﬁªÉ2ãæ¡„Ÿ‹W:ÆææÔjî◊Î?{˚ïê} ^]OqõPøoY‚öá8kl7˘ß5\0˙áö⁄y1^Uo#ÙHˆ∂Ø¿sà/U¡hπ^Qt»úT)êÁW˛~.´Î∆õı$≥cÒ|EZ]˜t’q∆ëµÍ6è¥˝Ö©“´âY~AJ≠z¬ÙT·XÈ◊U<Rèæàò ÛUãÂó´ÎÀ\r\"∆ì»√*êÂßâÂUMÚ™÷»Y ∏ E=c˚<≠PY◊Ωâ/êË˝¯ñ+ƒ¬.‚%2&ììà?®-ËOçèõ˚ˆm´(gé$¶ìT9ÖøÒyÛz∂Ì(ÍøºPñ˚»<¥ƒ˜¨ÎA~ã;∑ªi_œÇävç8L’0	ªW´ä”¯åÅQ◊7˚Úq¢}i=°˙T3∆ˆ\r\0f-(4äøfü!S◊ÿﬂ+„Ÿ\\ßh5—Q/µ4å(u+„π\n{ÀÏB!≠yªFÆ‘ndá(•2á5t b:ˆ©\'$åSï>‹©·~e£%€„T…ªñÃõˆ(u?ó‹áòÇÒõØ/‹M{ΩléUî5¢±©•Ω˚îj WÏUÒŸx©(^mﬁå•cj1Ä—ñ~ºå++≈t^m±èTç.º⁄o˜ÇΩ&!\0\0hIDATïÕ˛yﬂÇq«‡5¯>Ó÷VÖmˆ«ãÖoŸ‘Òø\\|Êf:≥Ì@‚tU	ˆ∑Uïï√UøØø≠¶™ÂÛg>:uÏÇ{)ä”jWÁõuü#5I£*œ∏zãaM}:)N¨˚ÔKÎûÅWaÓ˚∆‰i\n\0òΩp™\">\'›¨°Oô-Lïqj5Œ#7®J®ß`Ö(.îÂjçVπôGøpà2´Õ\'™ –’µˇﬂ≠\Z´∑\0∑+¢‘?£vﬁOŸ1á»<E’≤ŸQª4<“4OÀ@=«ˇå≈¸…H!Ï#º©æÆçıﬂì±å‚—ZML .ìQ*ú-v€†ˇÃ1≠¿6q°ù\'‚\",P6>& u\ZAôSj≈GÆ÷∞R©e”n„Ï÷⁄ØÓ¸/î≈]äˆJçfYÔ6µ⁄´◊:e>§ll∞[GáôJπOÂŒ‘Tg°•lt ˆ‘Zë7»\\©a≠2ãjvQ£.z≥F·Æ∫ûG‘ú\\÷πFë+mÍ ⁄SyñjÿìÃÔ€\rf\0èÇ~ ?~\'Ê÷”ìØñy≠··eﬁ˚èµ¥∞Y∏NR’÷ˇ∞ﬁq•…Êq(_÷ÍX‚›géårÅ¶«®íh´j¶hc≈&eq+yÉÅôÎFπ@]ä‚0U∆∫Uw¶≠Æ˜{>æáÎ\rå⁄Ú¥oA≈A8´b∑¨ƒ©\Zø∫}åÛ¿„Àú¡Ò“Äj◊\ru@∫ˇQOê˚À:F∏wh¥÷çrÅ∆)ÛPºLÍæ[ª3œÆY§[E‹§àï˙ÎN±˛˜⁄ªMWÕªˇK|ßvÉ¶ |∂ qçpˇÊíá”3yd\n^ZªZ?QuâuW‘-µ+t\0yñﬂ˝¡\rﬁÛñ1 s\0;ªé”vIm±{ÖUuM˝zEÒÄNZmÚ*∫L™z¢K‰:e±Ã¸ÎÙ/¶lÌ-À˝™@◊\nç?xPˇi•æ„iL´KF™âπRoiŒÂ]¥¨{Vi¥Ó7˜ú·™,⁄^2¶™∆Ù≠Pƒr˝=#Ê5•…IBΩÕRúNûåÛd~ﬁ¸±]·«\0∞´r¡Pó¬π‰—“˚≈gÃõ1¢oa!cºp≤*;˚cUÈ¬nµKÙ%aâV«jÔÆ«üÙ/§≠≥ÆÌySÌ˝¶~ﬂüíˇ!‚Û \\a˛ÃQÖm:4öìey^ù¯yΩ\"=£^Y>°*º€‡ü–—ËíYÕ\0Ák4n–÷„3Ä\'(Ω]ÒV‚<·„2ø≠\ZìÚ|È´ä¯åy£J%.Í“ŒW÷çÚ+Íü¶»©2ûáˇƒøh¸Í—Râ˛˜wh7èêq`µ=j,ØÜgÈñ˘<ìÑ+õÓ—ˇï2üÒA∫[”EæÇx˘5„Ù:’l—sµcâã«2ΩcÚdw±ÎÍyêt	¶W»¸ºVk©ÊnïoØ®fÙƒ:ˇYÆ◊•KƒT{◊ØØ±\\Y.ØJ¶ã	Ë$m≤VCá»©™‹√‘:X.cπfÆS\Z_è\n/0,c}Ω9›¥:‡}%Ó.R‰}˙«‹û1y*\0àT”$ŒU5¨T’>\0˛H˙ù∞{Ek∆≠\"o2Øw√6S{‚˛ƒ´6èÁàÒ2J~∆ÔÚﬁÛ∂‡‡$ºû¸SbX˙µ∞±Ó?∏∑öØ¨aÂêÅô∆˘ò<ı\0ÄŸ«â‚•‰Ÿ™¨ÒÁqE›éHïº˙{UÊˆ™û‡¢fd^Æ‚˜Ø&óÎic„k6Áe¯q\'9RC≠ÜÓVEwK™M=RU¢ÌlUQﬁ]¬E b©˘3∆Z«‰ø\0è»ú¡nÈmè¥Òiç¯W˝3÷ÍYXxÜiƒQxf\rä\r¬˜îyΩ˘3◊Ï Ëû®»j.hµ\r·á¯öÅﬁ•˙.GyÄ»WW{˛Zç+„≥.Íkjìﬂ#\0*Ü¶ILTƒi¯;’êŸØê◊+îVkh ,–“?¢¸M∑ÃÉÎÆ≠?©òú¯ïÃ%\"Ó’àı ≤CjjG©QN†òRuìÂ´Îíåe“b‹£Y¨ﬂúOì1˘Ω‡9}1{˛näà√ÍimG+Îâﬂ≈} XÆ»Éâì»‚N<$\rãÿõ<Æä,ñÂZäÉÒW¬AD=º6Æ≠F∆R3Ü«Îò¸˜\0¿Ê wêRìú ÌO¸≠™:¥ª*H´kxX&Í°ºè~døÅ¢ÓˆZ_\'µn≈∏áﬁf‡Ïòå…\0l?V\'M¡ƒjõMkK?û∫O∑™Z_oR±VƒZô´5ä’˙«Ç⁄1yÚÚ\09ò\Z∏‡®l\0\0\0\0IENDÆB`Ç'),
('invoiceEmailTemplate',NULL),
('invoiceHasIrpf','0'),
('invoiceText',NULL),
('jobMonitoringEnabled','1'),
('logo','âPNG\r\n\Z\n\0\0\0\rIHDR\0\0\0≥\0\0\0A\0\0\0öNï·\0\0 \0IDATx⁄Ì›}ò]ey.ﬂ≥ˆŒ8§”CFD∆#\"EDDJ9¿ë\0ÇäEKÒ´\"bf¢‘Rä…Ñ!‰p8àd&(¢\"ı ÇHê™Ö) F§àbƒúcöá8{ØÁ¸±Væ?Hƒˆ\\≈yØkÆ\\ôŸ{Øµˆ{øœ{?˜ÛÒÜˇ®qÓ•ù b*¶\nSdºúúå›—B≥˛Yâ!aµå%‰èÒπèËüπ÷Ë[ÒÔˆ…}}¥\'é≈dúL^ÉuXxÀXFN¡´d^,b≠L\":–+|_Zãó◊†Ôƒ˝ƒ-2Ô◊éU.ËnçN„Ë¯˜sﬂ%MÌÊt‚≠‰ƒ\"L â‚|sgl∞Æ}\nÌÚ\\¸õ˛ûèØˇ˝ÏÅ√Ö„5:Œ“wzi÷¸Òä∏BX*M√x∑ìüW∂˚ËáFgsÃø«1g`ºQz{\r∫/)s°(ÜD61-óö€Ωjp«âÏ≈w4‚F}›•9ÖÙ<†›q£VãÁîÔ√c∏S∆^\"?É¶t∑àk4∆‹ßÔÙQK=\nÊg0zÁw·|åŒnu^œöÕ^7ñx^-bñ\"WËÎ©ˇ60	 ∏‘ì#ã\\Ú◊ÙL¡Ÿ8Sœê9S•wkƒ,}›•ﬁÅÛ]L≈[Ñ;ïq)π‹ºûrtzˇ∞FÒÃ≠ÒÇIƒ≈∏J∆W•[•◊+c/=Û7}mˇÃµ ‚S¯ô¥Ì∑·o=Àe^(ÚL‘úZc#æOúÊˆ< úRΩ)ÇBœ«à+d)Ú·Ω˙Æ+Fß˜k4~w^º†È–ÈÀº¢V#zîcnPîw‡xøŒx•√¶ˇ‘”Ìˆ[¸ÛÕÈà„~,Ûg8√a”ávÏO-ºπÙÍ£~iLÛﬂÑnáMø›˘3ÜrÃﬁÁ∞Èã-ºeï√¶ÔBº‘¬õ‡cûKLsËÙ{Ñ©ƒ˜u¸ù\\;≈a«‹o·-OéNÛ(Õÿé5û_»xõp>OÃ7∑{ı¶Ø/GûÄkµãõt¸rX_ﬂF¥c`\nŒ≈-t^ßˇ¥ñæMÌÚ$·Â\".p^˜∞ﬁÅ7cäF˚j7&„ç÷Y⁄Õiƒ)2ŒU‰I(=˘‘\r∆6Ø¬8agKOËÔùÌQ0oŒè:~úÅsı˜\\∑˝◊é\'ªÒ2\\!›e^œ\'≠op¨∂Û…!\rËÎY[_Á#2Ù‘†Á6;¥„·*EÎeÛBiÅàaôg·‚D°ƒBôÁi4?®›∫X8\\:≈Í|¿¿Ã—ÂÃÎ≠m\'∫Òn<Jå’;JÂÿmcÙwØ˛qAÌ§ù´w`ö9óW◊ÓÎ^Kûã_jÎ◊;∏GEás/ÙGcﬁ¨Uå»∏Bzß≤Ÿ)}o&áÑNäÊFÀr\"VË;c?óÓ¬∆á{Îu£3> ôÎqËÙw’V˘t•+1Aƒ1ƒtáÛ<Ø?˛Qw|}KiÏé[Jo˛•#é˚∂ÃNÃ†|æ√éyÿ¬[~k·-øu¯ÙÔíˇß9|˙C\Z´ñ)«>(Ã~åáÖ◊à(µ‚Ö∑Kw‚uä¯^Ñîô¯cvÏ£8â<Sƒ>òa◊\'ZxÀä—iˇC¶≥\"ﬂHˆãò°»Ö˙zòΩ\0eSá√q~ÜE2Ô’\\µjés»V\'Õw‡œàÌ[Õ˝‡Z≥Êä8\0≥àã4‹≠ÃIRøå4rµ“y2Œ˘óïæl*ñãá≤Z,πÜË$:4‚+⁄Â%Xé#q™˛ûGGß˛ÃsêyUÂL≈ÕÊuó€êÈ∆ r*ˆó^UY‘∏M‰äÊêæ36RC>Yh∑¶êo√Û§œ+‚O)5b≤¢Ïóæ,‹(é„µsñF\'åïÒy0yq ñ÷üºÓ∆±ƒ’ÄÛ1È·ÀxÄÏ—?s’ËÙˇ°ÅyŒ¿n∏FZDû≠Êé#˙t*À§#«x7jt=°Ô›õ_„`ú*≠qππ›À*˝∫<K˙Ö2?.‚8·µ¬•“π8èxáYô˚U`çR‰d•GEºGÊ≠8Á\n˝“¬ªÒÄ˛ûsFßˇ	Ã≥ëó`ö¢xª¯Âñ¥a{„§kô∂¢)å«>8kÒ=,“à\'Ùuó˙Æ§\Zã#•§!ØQ‚Ωx!.≈â∆ÉDh4FÙù±È˚ªÀ\nc Ni?ë#ƒÒuÄe!ŒØ,yŒ€±∑˘ü5äÇg=òœ§ô‡kƒbÚ¸èàX,≠![ ≤•Ã“;êË3k†©0E8@zµ*’Û´2íÂ∞y3KΩªì\'Ø\">£»EJÔ¿kâÛ…#ÑÎÕÌŸ1ö–∑†C;ﬂ[Qè<[∫Zÿ«‡Ì˙{Vè¬‡ŸÊŸÉE^çoêÛï9I{SEºTfQ[…µ‰œâ•ƒ£ZÂ.ò9¸¥W~ﬂÂ<dZmmü/˝\\ƒ∑eÒÄyhô303§W`7\"„ô◊=ºSOŸ˜âNÌ÷y\"øT9âq*N.0∑ÁS£0x∂Éπw‡/â3µ„0ù1¥)8.+¥⁄]Bó4Nƒÿì|©JÁ]]q‰xBÊX-¨Vkú?cSÈÓÏÖ1ÂSjµaWÚª∏W‰J{„T|_∆(ÜÃ˚¿Œ=eEcv√õÒ\nÈ\ZL√ª•SÃÎY:\nÖg+ò˚.Ì‘.˛◊ËÔπlß>qŒ`áÃI¬^“Ó2˜Ò<åì:Ñü |@√2}õ9îÁÓ!Û Èµ™d¸o(›≠0Eœ›øÛìŒË$ˆïñêá‡-8@∏‘‹ù|∆—ÒüÃ}hóÔ¿_	o5∑Áw2¸›¸*,SDÌ+\Zù⁄ÂTë{„U_[Ò_I+±Lƒ¬2iï2\'(ú®\nÖ_¢øgŸ3z⁄ŸóÓ!äsàÔ)äîÌCpëFºF_˜–(˛sèÊøig\'q,æ¶ÿÌôEÀ˛«˙\\àu÷w-Ó«˝˙˙æ†Ωk\'&¨ˇ	{ê{/$Öü‚1ëw)ãøáß]ÆWb™≤}vM5∆kÁ—ﬁw˘\r>u˙(\"ûUñyŒ‡“wD˛7sˇ?F f-Ø»ΩÑ…2◊ËÔ˘ÊÔÂs{è#;±TëKïÒ6ºßÈÔ-ñ}VYÊÃ„0§Ù≥Üw˛#”ÔßÄ•¨>™ Ç~Ï˜ˆƒô+Uö˜xÌÿWãCUë√á6St>/}–ºÓ€6Zü®Â√Õ?}	æÑø◊ﬂΩ|≥Et\0Æ≈∏Ì|u´…Wö◊3¨w∞êæ/ÏæŸ˝Ø±_≈ç˙ª∑ƒö=∞èà/„√˙ªo⁄ÜrE¨O {ã˛Ó%õ˝Ω¿>\"ﬂ\"„aﬂ˙/-|S˙ñ»õıoÉíˆH{	o◊øtÆw∞ﬂBN÷ﬂΩx«¡¸∑MÜõ∞h´6<∑b”◊·7±Ÿˇ7zœˆˆÖ‹¸˜Yﬂ_t‡˜«g√ö\nt9,ïïÜÓbÔ-¿ö2\'‘’‚™Kƒrúßí\'°ØìyéàWË<C˜⁄ÕæÎq2?)‚û∫\n}Û{k…ŸË7„…Ö2Æ‹Ë{‹oóyπàÒz˛∑˛ûm◊=F4+\Zó€›ü3«bºàb@•˜g•»€àKÍ˚ÔReBû/„≥g	˜m±¿R9nÁç\\Ç˝eî¬·zÎÔﬁA0wÿÉÿCƒÖ˙g‹ˇæO|‰äˆ¬dU]ﬂK§â¯˝=Åy∫Ù\nb•ìäìª_ÊÎq˝	>’Ç])‹º	`{oÒC©\nÓTFa”Ì&‚ûmZ»MÃ∫ã=fﬁfØÔ¸ÇàqûåÖ;∂s=\rêbõVßWx.¬e˙{67,_‘;∏ØpnΩ+ùÜ€∂ºÙNπw∞ßÀºM‰XÈ‚≥ŸAöì…qZÒ–ø`{/)‰òq¬ƒ⁄Ò€S»“ûDÆ≠ÙiÀ¯πF´w`Ï3Ê¥Ω]™êˆM\"∆…úR≠|{	ı-Ë‘7c«®U‰ñsﬂﬂMÔ‡}‰∞à›∑R;\0Æmå˛Ó!≥?/|@òˆ¥`Œ˙~∑∑p÷Ô®πLpﬁ+Û\"”øç%ÈÅ:ÁÂÛ∏»Ï¡{Õ€¨Úhk;—ˆ«˛“°¯ 1åkÑ£∑b ∂Ê*ÃªƒGgå<#–Ù]J;äz5\"w´É˚‚Â\"\'÷‘°Æxﬂññz™πRGªT‰x•7‡ıxXï∑ˆ›S+∆÷e\\e~]ÊWÍ/j*>°,wﬂπ= ∂HÔ\0≤KDSjm4øüfd£Ü9œ»(Qƒıœ‘%ÛØÄ©mÊu√\Z≥/y-Òz/€ÇÏËBÓ$ù\\Á’‹HéØR(ÚÌzø©Î‡Õ,sæÇÿyzq⁄Âº†Ï¢Ωß¥ßvÓYk√„1^k…%2 ‹™èÍÎŸt¡Ù}åvs7œÒFôØQ∆¸ìF|XªúFÒF|˙w˜\'ã.ë”qµ¢X°ÃìÑK±˜T+±ÁñV.v%ï≥≤?.îπ\\ƒ7∑¥é±„∫=˜éïy≤àU“3ﬂEc£ª·r{à8\Z=˙ªWÔ‡ªó∏[‰	“jcµ©/µcœ=Eƒâ2ò◊ΩkÕ¸<>,ÌÉ˚vDÕòRÛûm¨òÀ:DŸU≠ZÍ^qSy1&T…G~Ü%\"æ$≠™<ÙX£πrxãåªﬁ˘Eıæò¢ÌÚ%“=¬’JKÑâ⁄˘>ºPÊ?òΩ`≤à•˙?∞sç^˙ÊZv√e˘0æH\\GÓ• ÷õà=v«ô˚ˇ¨w∞¨TólìDﬁ/¢W˜“≠™<{È‹w3^ºŒQ^aﬁf*◊øﬁz‚\r∏å‹¡P|Ï©w`ﬂ \no¥H≤æØÃT7±Ó˜&ìcE,⁄·Ôπø{≠Ÿãq§à±Î¡;°rıx≥Ã!SäØà8Wπ›!0ÔFnêWf^Vxn{r\\àiî/ØΩﬁa¨¡„“b∑Q.—h≠÷˜◊;ˆ‡≥ßí\'í/~ä;5bææÓg~¨–’ÒÚµ¯GE„≥ Úî˚h˚{}}C;ïä™9V¥ß◊RR/ﬁÄE~U—ºIﬂãÙæı]iã|Î≠ÉcÖtç0≤ﬁ¡IØ&≈…f,1ØgÂfV1∞ô#πÅGÃ´Ôocﬁ¸.ôÔ⁄\0¯$bë»•AÛzvÄ„gA\\≤¡˙nTèmÑ∑Æ˙u;óUÒãZ~ÏÿbW⁄Üï∆ìÔq\'‹à <fˆ¿mƒIz/—ﬂΩ‚È¿<«Í|=9ëˆ∫pÛOd˘êà(VàrDô•‘r˛V<ˇÕ%Ö]ö”§ÖW◊“ÿ◊EbXˇÃ“Ï˘zﬁÄ∑íﬂ¡Y‰Xey∂åÔíã4ú≠=Ò\nsÊØ5wÊ ÌØÚ˘E⁄Ì≥â·ô∆Äåõ…wi∑Ã∏™*µ“e‰◊ÖuÀÌìyŸ&NiÔ`S:WäË–;·ı≤Y¬úåõ7XÍM∂Äë-9f|\\DØ‘ƒ≈\"$Oê±lõ?[ﬁlYYs7nCô!‚CµŒºéz¨ÆhAÏVÎÁ;:^à’2á∑Ú,;BUﬁX—Ω<Mˇf]©\"ÆêÆ¬üõ=xŸÊœø50ˇ∑+e¨ÚTs•Oˇ›Z]ù{YS∂\'(Ì/UﬂÏø‡R#O=Ê¬ø.kaøiŒ‡dôß„◊∏Xã9ï¯†p\r˘Pn◊íoî±HÔ¿$OyƒMÖae ;kÁk*vó&·k‰_hõØ∞g)ÚR•ASEûZNn›¥ƒ7…≠XîıﬁlæÕ∂póŸ◊Wm»‚úz7ÿà/«ZÛv pêÎ-hk}†°wb‹\"ΩM¯∏\ri;√\Zﬁf¿¢R.F6Yºè	´qÓ›A«m]∆2kwXùŸÙ˝oó!VØØ‘ﬂáX&,≈	¬Á6è?4∑‘A}€‹Óªûô^|yá∆Sá Úı™úãä∏¬‹≠DpÊNTÊ{eæH‰ï\nãÙÕ,Õ<RzìàY,°˝!¸c\réW7êÁi∫á◊e]%‚É“Ö¬…ä¯∞véSxü(ÊÀÚQeÃ\"Ø‘ﬂ}∑æOŒ“~™CËPƒ”sÊàß3€?Æ≥ª6(0πsNPl’\\\\[Á÷ïÈwÔ–Álï⁄’#,√≠“[Ã¸¢y›+v`·$Ú\0‚,˝›kv⁄2g ‚¿∫2È_7˘ä7}k˚·ŒÌÅπ%uÏ4xﬂz”~—UØ ≈»+•)ÛZE,Ì!s?Tn∂\n\'êG <Jƒ?`–‹û!ΩÛ;ıû(ÛOpÅëbô1Ì˜KKe.Tƒy∏FÊ°¬øSàÏ%î≤,ââx\\ƒDÚ*eŒî>&Ï.À„yì,Œì˘AΩì˝∂ıEM#R©Ωï®dlcÊ∑MA^\"cı&Rb∆Œ≈\r“ñ·”˛ÓRÔ‡ßp¨pæﬁÅ„üV{œîÁrì»´ZÔæ$ÛΩÉÁlK´$I{HΩ“C¬∂∫HûN…âx7ñJØﬁ‡ãl±√ÏÜØJgËºo„{⁄Ã#b¡<kQ6& _L«ü©:‹ﬂCÒE\r:|nbµõ\Zπ/y:˛UËUåYÓ©Vïsú>@>ó8ﬂàµ∆î«UE~N·îäºWÁW‹‘—“›îàxÇ‹M∆BëG¯µy˛ÿ[¶j‰Á¥„o¥c•ˇ€∫”sããÑ˜kÊ˚Ñ›d¸XÂ”oç€à”˜6±èÃ7ã∏};©3«6¨Y˜pM7Æ¨ù°ˇΩ›¸åuüì;hï◊Qß™ùŸ]f^$‚lz.¿*˝µ¨:{ê»N’ç|&ßmÙ±˝›°wp\Zéñyây=èmG8X!\\è7V¬Ñ˚∑Ê5U}ª2ÌòDy^#uIﬂ\"Œ‘\\πzõ*√ÏKâboôß÷◊=WœÍ‹¡›îzÒ]çéèÎ;Ω‘;péÂY⁄ES·ù8W]\'b©Ãó)„s\Zπé_-#’ŒÎ5‚l˜j‚Ωì<ã¯§pñÒ±RœbsÊT∆ª™*Ú¸û1+w@Î\r2˜ >†wpcßÌï“I\"Igõ◊Ωy©êyÇﬁ¡…[•)©˘M˝=ãü∆äﬁ^s∆ÛÒ¿∆∫”—ƒ≠ÛÎçu~Ì\0ûO¸•¥PÔ‡?≠˜±2éˆ!n™5È∑±;¨˚æÜ∑‹	bôÙZay%óngÃÎ—;xïÆZ¶€&ò´µÊÕVÕ«õ≤ò(bÔJˆ\"|G∏\\ZÍ7√k]|÷FÄÔ√ƒN¢Ù√ˇ2‚eøÿCòéóì◊“∏_6ÆéãÿOô3àØIﬂ¨Åº7ﬁ$]®#öqíÃÔk{B”;páÃÒDr„≠v%vu¡ÃΩﬂ≠ªºé¯ôvq\"≈ç2?!Ã–;pâπ=À|‰_–hΩÀ7[åCU)7Õ>VIïyÚ˙Ëô¨éà≥§õ∂–ã”P\rº˝™™ÒÿTg^∑3f<ä≈5Õx\0?›ÍÑŒºD8\0o–;¯‡vBÕk…˚E¨z\Z/êõ)*µπ±÷vè˛¥6HÎÓ˚VU˘˚∂È`Ü«eÓS©Tõ˘\r˝zDh‚j±C …É‰â…z;ÙWF#6„>áóÎÔyg≠FåSñÔ!ˇ§Vˆ∆g…ÛÙœ\\≥≈%zÊ∆«¡\"èØ‰ïhIÀ»I∏E€W|t#]¥™j9ü®t…x§é»}M‰Ò“\'Ù˜,2{`/\\†0C-·jrï*5pZùPäxå∏MñÎÔ9MÔ‡>‰iï∂õß·Õ’ë˘√z·N÷(˛óV9æRL‚l˝›ø{i÷Ë¯ˇ:6Ûâ™≥«„\0Si¡Õ€E{≠2\'*ºMÊãe¸ì(ÓÙõµ´tÓ2A‰‰⁄ÏˇõÙÖÂ2\nÚPº	?ínU6ÌEÏÖ£ÎÖRµ»bDô{íÔ±\'æQ\'±úJ>NÒy<~¨(æ®,á¥\Z≠ì™ﬁ#æV∑øÔ/™∑¢´E«Éì≈ﬁÌp@mqæÖY\"ﬂdÓÃÂ£∞xVÄypˇä∏ﬂ\'n‘ﬂΩƒŸÉÖ1—QÂ≥ñ≠*†b_bÚ%UCñ¸Öå€ÄÊ ñrbá≤,4«åhµ:´ÍÎ<\0/≠*∫≠Rı·∏’Úé’^¯T°‘Å“Ø€ÚG{*X∂Ó_o—„vç÷b\Zeı˙¢õrXßq2ˆ«ü™ì\'î˘MÕÆaÜö⁄ESY¥D©™EÃ√Î≈[j«Ëõ1z|ƒ≥ÃÛ«◊™äGo5k†K√â“À§¢J‹Œ_ŸBGÑø˝t·9ø9@‰2û[94Ò§»øZºÖsÿ◊G{◊Iïg\Z/&K°êÓ1<|ìãŒ⁄íˆ-Ë‘ŒC)ˇ¥¶&D~≈‹ô[œSò=0M8ZxÅTJ•\"ø°pw≠^‡ˇò€3oœ0˜}åˆòãk.˙œ¬Q“7à;…!4e)‚(‚‹)À’äbºÃ=âS»e™d•eòÌÖSk-¯ZôO‘dwQ9ÖÆ≠,oÆ%∫jŸÂU“-\n˜VNIé±Øt¨Ù#Eﬁ†åUUÉÒ8ê|~Bﬁ*cY]µ\'é≠È∆µ¢X,ÀEy*1NulƒÄpöπ=wéB‚ŸÊä7Å´qÅs{ñ:{ê1—$hµKÕú$‚p‚O»=´ÿÆ!Æ¡›\Z+Gîãöfî⁄:e{ëá„øZ◊b†:¥Ú:øËXi˜V°Ã™dgnwKÔ‡‘ö‘ZÊH•!˚≤Vπ»s¢TjR0‘hÈjèWñáàxm\r‚U^…øH◊kv≠e®–.\neQ ﬂ\Zç˝U˘ê\'Ëüπlœ&0œ/‹Å•˚4ºAö\\5p	‰#ƒ≠[ïaŒ˙L°sÌæ\"ñ1æ¶&kDﬁ¨¯’£€†•£+á/ã˙éÓÛõ·ooùfvh;êÚ\0å%öxB‰≠Ênå≥&á\nì§Bj)ÚvÖµc\0À4ZÁÍ;sî/?À¿‹¨ﬁ9DxT∫á∏´Œ*ÎêyDe˝‚Î∏WñCä¢KÊ$‚d≤Uo›À§V]œ˜&‚ó∏^Ê\n°PµÒ:ØSÂ\\<XÈ∫∫0ˇU˙öp?Z2ª1U:øy”f4„® iıMô+Etì*Îû/#æ^W+¨£\'ÖCîﬁb^œÉ£px∂ÅÊÓ_Ûﬁ≥dﬁPΩ¨¨∂ÁvI”û\"¶/≠∑bDxR∆∑â˚DåT9?ÎÚtõ]≤}˘zt#xRu. mû≥“=QÍü°JHœ#UöRïC]eÙµ 4ã äF0T–UNPÊÑWYwFwïÅ∑Xë∑ X[›jT)ûÌVS£q	¶j¸ÍøÔ\\~ÙË¯œÊ\n–™Ù…¸*^U5N)ØZ€∆¬Õ Ëk´>øCURZæPï›Ù∞m≈ØñnI3–.kΩŸ$<è¯yØ··ª\\t÷ñ€~Ô¸.‚pU\0Á˘í˛UëÕùπçæ\rÛ˜\'í^X“∆ZÚ˚8ÁËÔπu\nœn0(Û\Z|Å¸RÃËTÂ˝æà¯*™æ E—©Ã	¬€jãx{æUeµ˘≥Z#æUZ]´]™\\Ÿ◊©ÇKk\\üèª»¸j]Í‘™Kxˆîﬁ¥ﬁBWâ<E›Ô˘U∏wKk™Ñ©ø¡âÃ[à* í1Id?V’]@◊åB·ŸÊÍºø^L&O◊?sÉ√7g˛x‚h´\0J<ß*˝â[4äªımV£◊;–¨¨uÏOvUÈqÕ‚:}3÷le1Ì)Û–Z˘x~]˙Û‹§øgÀƒöèÃÔ‘à√´˚5NuÙDâáª|¡yÔ›`Â{ßâ¸ÜåSıw/Ö¡≥Ã@∆iƒ∑àE.ïÒduÿM,îÕäVŸ¨;›¥Ÿ‘.ˆµÆ˝ïÏ®≠‰Bçr±vS}:U•(d∂±átPM3∆÷‘‰AÖÖ≤9,[ÕÍ\ZJiD6∆* Tπ\"„jæL∫K„WèhOl÷›{J≤•1\\jÔ≤è™Õ¡ƒ˙=Gw*äsù˜ÅëQ<;∆ˆœ|˝Ùﬂ‚ﬂ‹†Í,Ù2·]¢lHÀÖƒsbÁ‡≈¯A˝Û`}jÍüÀ8HzTx\nMëªÆ™M{ºz}ﬁO¸ú|µåø†\\ãïu„.xç¬áUÌ¶ÓÒ@≠ÇÏäS‰ÿΩj∫“™ÔÈrÃÈ¯ou‰Ú·O0M¯–G$èég±eÜøπ®0∂Û|ÈHÖˇÓºûUz/ùHqÒí™áòÆöS^£qªæ3Z[°,©¢qù5uh‚˚\ZÒ)}›[£ìeæ/®_ﬂ©\nÇ\\•øÁﬁ≠“åf\'„uµeW©#æ£ÿÂ”‚âñr◊Éd,¿ô£Ù‚Ã\'·3’÷’˘◊E¨™⁄dgM3ÜŸ“.¶÷`∑∫∏t%Ó÷,”n6)ªjö1¨Ãaçò(cø:MtºußIyü3\"[]ı5FdÆïç¶¢‹[ïw=°¶K§E\ZøZ™ú8VÊÿ∫Ñ≠ßbX3ˆyÑÍ8âY¸Ωy3Z£”ˇáD3÷ç#^;ƒÿE¯k/ÏM˘ke˘∏àµ“S‰≥TiïKÖá…ãxÆp\n±è≤¯Å»\'Ò[ëcDú†:P~Hï_¸C¸ö<L∆[(ÅüW-¥≤çó)¨k˛∞H˝æ©8Ué›UUÙ˘ÎZÀßÔŒ∆I¯\n˘1Ûzû\Zù˙?TÀº¡BXYË∏Oï‘Û<…Ëö¬’ä∆}[úÀW—å√U©ñ´ÎÌø?–»ÎÙÕ⁄\nÕò*Ûî⁄)Æ˛Õµ∏FˇÃá∂B3∆V’(^S_£Yù‰Íóx+Ó‘jüÈÇç:|£`Fﬂ@QïM≈%‰We¸Ω»a√¢1$€E}^…~™düÍ‡…¥H≥\\ÆlvRé´˙ZƒPïIWN™˘Ù^ïd?!)r±S“\Z_ÁSk∂Üîc∆RÓW7b|˛Ø∞HzP„W+¥\'é´4i#J{ã∏Ä\\ÑÛÙ˜å1<\nÊç∆ﬂ~íé÷æ\"Ø¿bçfèæ3÷Ëù?AF∑∞ãpá¥™∂¿{„ï¯±v«g}ÙÙ∫ˆo~á∂£ÎÛSæ#<ZÀo…?%JÈ\ZÛz™Q:ü3Eß…|íX®*Â/T9/Æ‘ñ∏¡SÂà1q®*O˘nÌ1Ù—˜èN˜(ò∑=Ûª´J¨∆ä™˜Z^©l€G?¥¡πÍ[¿HŸ°aö™ﬂÔpeÅÌÜUñüS‰ê˛˙Ñ◊æèQ6eLN¨ù¬r◊ -># ≈Ênt‰⁄¨¡B°ì<ÆVLv«û¬Úìû*F\\–=:€£`ﬁŒò3øK∆T9¡W·”u´ÇÍåê™è⁄‹.,Sî¥ã	u†euÏú†äÓEÓVqpwíâEóÃq“ZÕˆjÌfß»)“æu¥’UÛ…G÷≈\0\0√IDATFèäÏí—_9à˘awö€3™ZåÇy\'∆ÏÅCÑY÷ùŒ\Z.QzHï7±?qî»üKüˆ£›ñπ˛dfÕﬂM·çƒü·Uçﬂµ≈>∂˛¨/ﬁÂvΩ∑tˆ«:t4Æ“L\r·[2óàh©N^=∂~ÔM≤∏‘ºOåNÔ(òw~|‰r\Z#]™H·©àãÖÖäïÀµvÌπ?Ò¶ä2¨£æ\'Û≥¢\\©£ˆ]U.«dU˘‘K–!çVWärQe‹€S•Îr≠«d\\(ÚÓıwF«(òÁÒ7:ü3YƒõÒWï•ç/K7´e©,vìeá(Và1kÈ¨)√Trw¨ëÒMëK4¢•ÃÒ“iHëk(:eXxö™€ŒÂƒ};‹·}tåÇyß∆¨˘¶ã8°Î#‰7TAç%J+∑ÆßFuT€í⁄y;°íı‚ZŸ^J±ˆ√!X]’∆ı˙g,ù∆—ÒÔÊJ~£å.iwr?‚ı*=ylM3ˆPâv±[◊∫iº*»RTJ	Oé{…;êFs˘¡ô—1\nÊˇ–1g†∑›Ö›T˘]5¿wQïS\rUg°XE¨ óiÀıÕÂ¬£cõ„ˇOWJ1&|\0\0\0\0IENDÆB`Ç'),
('notes1Name',NULL),
('notes2Name',NULL),
('quoteEmailTemplate',NULL),
('quoteHasVisitDate','0'),
('quoteTemplate','1'),
('quoteText',NULL),
('receiptText',NULL),
('sepa_batch_booking','1'),
('sepa_creditor_bic',NULL),
('sepa_creditor_iban',NULL),
('sepa_creditor_name',NULL),
('sepa_creditor_scheme_identification',NULL),
('sepa_default_collection_date',NULL),
('sepa_initiating_party',NULL),
('sepa_local_instrument_code',NULL),
('sepa_sequence_type',NULL),
('smtpEncryption','none'),
('smtpHost',NULL),
('smtpPassword','SERV2017law'),
('smtpPort',NULL),
('smtpSenderEmail',NULL),
('smtpSenderName',NULL),
('smtpUsername',NULL),
('templateColor','smtpHost');

/*Table structure for table `suppliercomments` */

CREATE TABLE `suppliercomments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier` int(11) DEFAULT NULL,
  `placedOn` datetime DEFAULT NULL,
  `placedBy` int(11) DEFAULT NULL,
  `message` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `suppliercomments` */

/*Table structure for table `suppliers` */

CREATE TABLE `suppliers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplierCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companyName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contactName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contactTitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postalCode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cifnif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `services` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdBy` int(11) NOT NULL,
  `discontinued` int(11) NOT NULL,
  `accountHolder` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_cifnif` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bankId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `branchId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountId` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_notes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tradingName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` int(11) DEFAULT NULL,
  `paymentTerms` int(11) DEFAULT NULL,
  `paymentType` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `suppliers` */

insert  into `suppliers`(`id`,`supplierCode`,`companyName`,`contactName`,`contactTitle`,`address`,`city`,`region`,`postalCode`,`country`,`phone`,`mobile`,`email`,`fax`,`cifnif`,`notes`,`website`,`services`,`createdBy`,`discontinued`,`accountHolder`,`bankName`,`bank_cifnif`,`iban`,`bankId`,`branchId`,`dc`,`accountId`,`bank_notes`,`created_at`,`updated_at`,`tradingName`,`currency`,`paymentTerms`,`paymentType`) values 
(5,'Internal','Internal Services','','','Fuengirola','','','','','','','','','','','','',0,0,'','','','','','','','','','2020-01-27 01:39:46','2020-01-27 01:39:46','',1,1,1),
(6,'sdf','sd','df','df','a','df','da','df','a','df','a','d','sf','sf','','a','df',0,0,'afd','df','','fd','','','','','','2022-04-29 18:03:43','2022-04-29 18:03:43','fs',1,1,1);

/*Table structure for table `usergrouppermissions` */

CREATE TABLE `usergrouppermissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) DEFAULT NULL,
  `permission` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `usergrouppermissions` */

insert  into `usergrouppermissions`(`id`,`groupId`,`permission`) values 
(1,1,'*');

/*Table structure for table `usergroups` */

CREATE TABLE `usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `usergroups` */

insert  into `usergroups`(`id`,`name`) values 
(1,'Administrator');

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `initials` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companyEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `companyMobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hireDate` date NOT NULL,
  `companyRole` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `personalEmail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homePhone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `homeMobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `dni` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci NOT NULL,
  `disabled` int(11) NOT NULL DEFAULT 0,
  `photo` blob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `userGroup` int(11) DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `myJobStatusses` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`firstname`,`lastname`,`initials`,`username`,`password`,`companyEmail`,`companyMobile`,`extension`,`hireDate`,`companyRole`,`address`,`city`,`region`,`postcode`,`personalEmail`,`homePhone`,`homeMobile`,`dob`,`dni`,`notes`,`disabled`,`photo`,`created_at`,`updated_at`,`userGroup`,`remember_token`,`myJobStatusses`) values 
(1,'Rudolph','Demo','FRU','Demo','$2y$10$pmbXgAZix7ubCw9f47tRuuv45sGn.8sFcmFn9CO8syIOdZR3i9aWi','ap@businessdevelopment.es','','','1970-01-01',1,'','','','','','','','1970-01-01','','',0,'âPNG\r\n\Z\n\0\0\0\rIHDR\0\0\0å\0\0\02\0\0\0é\03;\0\0HIDATx⁄Ì›ú]uy\'˜sÓe:¶i”4¶Ÿl^i6ÕfiJ)M)•(-\"\"Ú∑Çu-µ,fk≠Î\"π!…d≠RMfFë™¥ääH-\"•îBöEöfY_ë“y•1Õ¶iiöÜ8NÊûgˇ8\'ì			∞∞Ø˘˛ìy›˚=ﬂ{Œ˜˘úÁ˚y>œÛ˝&¸[∂%ÉMrít*NÒ≥2gaˆ÷Ωˆb˚∞	èá01§›;bºΩ`[¸è∞ÙËú0â8o∆Ÿ‰z‚À‰#ƒvÚ$,ƒá¥˚F¥\'ìW\n7K3ZºHÜ˚îù]>Ù;„˙wò•+)c∫t~\rﬂ¡Ω5@_÷ZﬁÀU´\nQ^$Ùhü±tQ©5p>∫u∫nUò$≤∑„\"ºL‹™q¬VK/∑‘§5û’U≠Å	2.∆«1\"|PªÔÎ÷|c´5w˛≥ÖÁ≠≈[1”ŸÁ=¨›[:ÎºÔ‚óeæƒ/˝˙&\'4∑oRt˛Nﬂ„Œ:Ôç∏]z”w∏Rv¶{ŸyﬂµÊ˚«Õı£ò•´yÈ´fc¶À∏FË¡</;o´5ﬂ¯Wpˇ7xÈ˘à\\ Ω‹¬◊|KªÔ^ˆÍGD\\®´¯Å˛æ≠ŒzÕ„xµÖÁ≠«Êìﬂ¡ô\Zªoê?ﬁƒ·gΩÊ¸⁄€¨Ω#«Õˆ¸µ‚ı.áœ≈Õ¯\n.◊ﬂ˚ÄQb≠∞Hk‡W≠Í◊,\Z°s#íπÃí¡©˙ØÿO|Çx≠%≥Ì)7†[∆\\ÈAÈW5ròx¬»Oı√ƒ*|ë¸à\\b…@s‹l?\nf…¿€§ÀE\\nyÔ√G.SÉ›‰Bº\nÇ5⁄}•÷ıDÁ$ã»ÌæçZ3±H∆µä,§˜-©üØ	fa±â<Yz}ÿ¨ìÀ|Ëä·qÛΩP”\Z¯≠öànê˛Q∏CªÔ—£ˆ]ºj¢(ﬂÇü#Vh˜nÒ?ÆÁÑë©ƒ\rÌæ{,8oq\ryÅ∞[⁄å≥Öá§Y\"öÿ$sa\r§>Lƒ?h^Ó˜//«M¯BÃ‚UDæóä|´“^bí»≥¯ÆÃªÑ]\ZªKKó÷\\gùr2ﬁá¡\'4Ï5“Ïqv)„s\Zy¶4ﬂH„öùÎîç+ù+•Ñ◊;±ôÚ<∑\ng`Í?†›7ö`ZÉgêÀvÌæG‡ŒÅŸ‰yË©≈∑u\"v[ﬁ[éÈ3üºﬂîqáCNpâ»È2Æ\'/¬~r£àÖ“Niß»”âGkÅo6Üâ»ã•ÎÑ?«jåZ˙ﬁqK>ÔÄi\rÃ®	Óï⁄};zZ’‘)‡4t·AÈi∑k˙h\r4Ös•ó‚Î2q^%≠¬[Ñq&æFÃ$wH;Ñf5fú&sXÿáì•GÑ´Í˚[3n Á0≠Å&V›‰G∞Kª,{\Z≤}k`Œ¿Iu®ºNƒFÀ{˜Y≤ríå7ê”â€P‚r‚fÚB‚±Wªw◊SxºôòGN©Ø˝%ú\"„ı˙{˜éõÛ˘Ãyxß¥Zòä)\"&…‹á]‰vbª¥√√{|Ï}OYÕê`.v X/b£(gHo®\0k»∑Jü’ÃÕñ\'˙i\rN\"\n”§u∏˜k˜˝œqs>Ä©<ƒgâkµ{◊ZvÆoÍtz0”àÈ‰¥z	⁄á=“6a´åm˙{˛ÉÉMçúM,¿Lr+¨πœÖ“˝kÙ/zzâ«%ÒÙ(≠V…|´˛+∂åõÙπÃ*·rÀ˚ém¿•Ét≤[ò$ıH”ÖôòNî‰v„QôõÌ¸±ù¶\rOêf◊À÷U∆˙^Ìæ›œËŒ[≥•sÑüÆ«Ÿ≠›◊\Z7Ès	ò÷™nÚf·cñ˜>¨G]z}°SvQN &◊QŒli≤PVOn#∂÷ﬁi£vﬂ3+kX≤bÇlÃ\'\'cÅ*wıÎ⁄}€«Õ˙√kOíŸs69Å\\oÒ@!!+\\ÑVÊ—ùS‘ﬂEp†£ñıáeÓ±yÙ;öR≈aπ˚É≤1Ñ9ÿ+‹*˝8^âœ=âÛ4Qj◊°~k∞@q±∆¥Ìﬁ±◊öüÉÛQˇç®˚∑y0≈2⁄ß √∆;B„™Ô£ˇ(ÅDkê‘Y°3µ…,àbÃ‹zæ#˚6EåÛ^yÌÉ„>eˇCÄÈ]â<îÒªá0Òd`C∫9¯]Ì≥phÚÎ?™øÔyñ`/eé`æ4_òÑ7Z≤˙è,øÏ–‰e˛\rñ©J\'êã•+EÏ´@¨ ∂·≠¡õ¥kÓïŸ-‚{GÀë#û-Û,<Ç∑	üñπ∑˛ºƒÈèµ?1:ﬁëæ˝wÒ+x„QﬁÜ.·ªƒ€±¶6&¢GÊ%\"ﬁ(Õ≠%á=¯ñ÷‡\0÷>	83àøê.ƒ∆„ÖÃèo√Y5U8`N,∫»_q•vÔÜä?˚‡ıÂEN$\'ÀòÌô&@ç\\Æ5üÉd˜:y`:∂Ê˙‚àU¯K⁄ΩÔı@ôE‹ mØt†—~˚ÒZ˝}Îés3ÍÔ;´ˆMa°t]˝› g≈*0è˝¨“≈\"ˆbô∞Nªwü÷‡4\\ÄU2øfÒ¿2˝}√OSö;ü”DúW\"¸˛±9”dl¸7GkeìË©9ÃLÃ¢úNûHIèc+∫¥äg!Òw´J=ß	ò!rﬁaÄâ±^Ì(ìXïÑﬁ£5x∑ÚCÄ9¥\ZÆ«¸@ˇËx+N≠¡Oj˜=≥w·IÀ_k∞ã\\-=Bº˜∞¥›ªü‘\ZºSƒ◊UÖlü;b)=∂wÅKTyªõqï≈Éü—ﬂªÛ&¶ì€µ=≥,“ïtL\"¶’⁄»Ï*Ïˆ‚ †Y#6‡NYÏ™ÄcAÕAöƒÑö¸>ì÷3z}Utıê˜µ`¯Ãñπ÷`W}œ¡…éáò|\nÉ§ëÍŸÚô{–—ÂoàeÃ≈≈áÅ≈a¿ﬂ¶5–∆G¥ø§›;tTJqÙgËÒvÈ*<@\\%úç[éÂaÊ{ÃÅØ\Zl* Èï{åŸ‰t/Æø}L⁄Åç2ÔSƒ.Ö}ñˆ—∑íûúÇ3DÃ√>iΩàá)/êq™%∑¨µ¸-OœÀ|`u¡˘u»æ∂\"œ˛~F∆”cÜ÷¿˘’•Wì›ƒßèÍ5ég‹±}+í}™ÃÀE‹£›˜Ï*kæVƒΩ⁄Ω{é}M|\r7Û±˛òÄ>¸Œ¡∞p∑vﬂê≈_ñﬁ°5x˚ìΩ„ÿ(È††∆oØfÍdaV\rå9¬â2ªà«∞M‰fi-ˆ“‹£}îRÉ≈´∫-8S‰b§ÎnT\ZQƒõ»©\"Ó$œV>∂’“•[F3ﬁOÈ—V:√g◊kÌN\\ä˚EÒ2œ¿\"Kˇê•Ô|…>¨ÕíÒÊ1ﬁ‰db]Ì∆Nd7æ`Ò¿˛√\'=7Ô“Ó›_˜;Uk‡€µ[*d∏ìºÓ8†8Gk€G˘¶1}Ùﬁ3¶s\\êı˜k\rnó¶=≠ Â¿ﬂÉOçÇ#‚ˆz^O≠<Œ—s\"^£5ãwU@= ÕüóÂ>iü˛„H˜Ô˝É¬ƒN©B‹Ú«•o‚FrwEp„\\Ö_√W…m2.ìæ\"ºNg ≠ñ¨‹g˘ªü\"ÏõÆ3<ü(Öôß ¯∞»≥ey5˛\Z•·Ì™¥ûÉ/È®Y´ø&ΩQÌπLƒ\n≠¡ã«D6√’d∆∑F˘L÷üá±oﬁ#2^ONÒ·Ωƒù«ﬁ2ìD¨≈eG·J]“_ç~ˆ÷K¸”â&ã|&yµ3*~È∂1üm¨¢≥º‘‚Åu˙…c3J‹Åae1‚öw?}\"∫d’LYæé¯è‰ˇqìÃ›˙˚JΩ+yqú,º˜À∏∫“|‚=ƒ\nëÁT.4/Q∆ÌZßaì∞C©[òXÁ§∫±ù<YÈaõ»˜À¸∞(Ó#S5„`™7ÙxoÊ≠¡U¯K©ßˆ\\UËŒN˝Ω€éÛÙC˙{∑i\rn√á•˜‚ﬁcÛ≤:\nkeÏ÷@óàëC∆_·Úß¡≈Nï9âx¯˜˜‘iùﬂ¥xpÏ5Mg”Î‡‰¿å®2≈«\'üo¸Û˛©â…µXˆã≤¸ø¬mä][Góï´o®ºóˇ§tï(áq6»XFæ˚DÏ\"á™Â¡º≠Õ~Ã™Ùw´í¢mÔπJ7àlëü¬-¬/◊Ü~vBf<ã0?Gâ\'≠¡œâ|µÙn≠¡èUP{∆ªExø÷‡oë75¢¨ññ∂àµ{wè·¶Oçò÷‡ôgã∏ÓàﬂÕ¯[úF˛W≠ÅÂ⁄}G\0fø0Ò8§∑P‰d˛È4¸*ûÓQ∑X∫Ëá∏j†G9Ú&Ln∞ºwá÷@7Òï“Z|îÚa∂Ãqù\"ØT∆’\"W»òW?ËÚîä8S˙™E˘ae¸û≤¸∏¬5Ë≈vâå√óÕ»\'O∆ìU◊Iuë◊FbÔ”Z˚ü™µ{á¥€\"o&Ô∆∑éèá#»kıË1Í˜[<.üñ1Akè»Ωı¶¿.ié‘™≈Àk<}˜Ú‚Ìﬁ˛ß\0‘‚˝∏ªûò«kw|x{◊\'ò62ôúOûQÖ±πé‚Z˝Ω˚è‚N\'‡ï“/„À⁄}ï∏dp≤Ãﬂƒ√\Z]wÈòÉsD~\\∆“ü+c*˛ÅbJ]@UIi≠≠º^∏ØUÍ¡M\nÔ>.}øÉ¶ÊÆëßû¥ úiÒ¿ÕcH…¨ö.\Z}¶*∫ËíñY<∏kîe‰_‘ÓΩ„)ƒ±o_ƒ5Zo’Ó€˚42yG~>ÿk…ã±L∆€El±xp=u¬˜kRü˛1ﬁ•\Zh>bÒ¿û—àÆzæâ7	Ø:†Ó¿ï8ª’Ã¶—ã´‰·,r^QIÃ˛7i˜s>8–•aNu√1\\Â8úÜı:ùeÆ˝ùë∫æw∂ÃWrsî Û»◊Iüî—-ºT\'ﬁß·øì;eæ\'≤´~Ë]Rè]·§j;m\\K¸±ÃG\Z≈M:˘0^rX§ïÒr”ò»‰ñZ≥;yªà\r£QO5◊√xß–u§j∆HÌ˜aÛìºLiÒ¿«EúvXŒÁv[}Ì—5úp)˘ò1aΩ÷‡ÖòS…kJﬁˆÓ8 @ªàwVKÌiõ=∏¯ò∞›ªKkbi»ó∑Ê`5ﬁQÛíûö<\rW{£sÇå5\Z\'<dd§[ësk.≤Sÿ&£Iæ\ZﬂìæÊâÊV;ìÎu¬_ÀÿQgó_QE`Ó¿ÎÍõ>(ƒúƒ9uø?Ø”[kûµ_\'∂‘ﬁ·‰:˜Ògµ¯∂]ªÔ∆ÒºÚßçÃ‡dÚÎµ\'πá∆CF ¶FLR{4F&b>qRQÂk4wmWNô†ÃnQÏ•úäST%\rÍ∑Ô>çbHôìP\ZÈÏ◊(Ê÷!›œ„€ÿ†1≤ûF°=˚Ë4âSUµæ6Àx@‰EtÎhÓ÷5<≠ﬁ’á˜h7˜3ﬁûuT0\nùŒYl®Ñöl“πZ”‰nçrzE>„Û⁄Ωk+Rª≤[È¸‰\\roÂâ óTkc‹=™A¥VN%~[ßúX+≥]\Z≈‹nﬂÅ}ÏwÎ∞|‡dùÊ’‰˜´~9Öÿ+|…Ú∫»˚™UMEYóèÊø–Ÿß´SÖÒ‹GºY‰£„f}.<Lµ,Ω≠v˘üqõlÏ’æº¥xe1_∏∞Zìsr•âƒW…{4r»„xqcÇ2_YÖ∂ŸÖI5i]%ÚQÀØ±de!ã©ıˆíˇÄâ‰ƒ∫FxÖfÓ±Ùä≤>kfÆ“E¬ãÍ%k˛∑ã˜˘>≈p∑∞∞^J˜jå\\6æÌ‰πÃ|JzßpJÕäöX›FÏp∞!Eè(œ≈©6ëw˚G˚E™ÀŒ©{â52◊ãÉyü$¢êq:ÂiD±_îw»ÿ\\ÉÙ`◊	8Wa∂aá»;d\\áœj˜›;n÷ÁbIÇ≤ÿÆ»Õ\"?çö◊æ|ÿ‚ï3E¸Üdﬁ+L®\0ï/¡4ÚÛWÍâ”)ÆÆ8I>¨*ó¯¸≥»œ[~≈NKVtÀ‚\"‚˛ZUù-˝™Ãˇ•i–“æ!Kß ¯ç ìÂΩ5\0Áˇ	_Êƒ[ïﬂ/√Ûâkj–>8n“Á“√Tz…©“5‰j‚Z ÿ(rKΩ,úRã|õ•›urrV-≈ot ∂kÊ<avÎ‰FçhsÍ˝H;…çärø≤8µZílπI∆¥:Ø1ôÿ, Õ ËNë9\"lπS∆,≥DLÆHuæﬂ—Óªi‹§?‹v‰˘0/?Ô1©⁄ïXe*˜◊À”B‚üe|[aO}‘ÿõ“chgj¯˘∫.e#~†à_RI6$Ì^åse1YU‚¯=≤!‚¸Z=~OˇØ®ıê\rUÍ†òJº	?-‚±z©|^ä´¨˘∆–∏IükSqôπ∏WäÿØÃ]ärò∆©“LrR…ƒ\ZŸÿ-:S»necªËÙà8ôúQ•\0l”,Ó”â&ù)—àù:9Û0ï&·˚ÎyQO•^∆^eπØ\ZÀÏöˇÏ#–»ÌFL1ëºÜ¯¢vÔÌ„Ê|Æ9Ã¡∂Ô¿&O¯”ä\0ÁßÑ©≤ÿ#À€Ù_Q’G,ËyæËT˚†Qtf‡Y‹•ˇ›CñÆ†≥u w´6ºm«ÑÍdá∏óºC˚ä“Ô˝~·E/:ù-´=Ãnô”jR|ªvﬂ˙:Dü@úØìÛDl\'œ¬∞F„éqS>üØÏVƒjÈõ\"o¬I2Œ©…ÁOV\0àØìk¥˚ÜjÒobùoZ0¶ﬂêàœ*bì•ã ˙‘Õiµö</™<V¸>£›WÌ´ÆùsÎËj¢™fw\"Òóîwâò%\roµºoÁ∏)üo¿TK”L| X≠àM2\'÷˚Cò\"„ÇJGQHªykµg®úÄôC\"Ê◊\0QiÉÜ{t¢´÷kÜ¯±a\'¸‡|UJ‡`øªEnê≈ÑJLÃ!YÙà<x¿—•⁄}çõÒ˘^íF)Ò„[ï?yYURê3enTM¨5\Z‰gÑ:≥s§+e˘è¬ÃÒ¯éIi®*äéÖ:>L˛Ô*¨ŒπN˙/ƒü`∞éÃ&à|KuZg˛e†8Y‰œ©é4ªT„Òq∞º†<Ã!Os\ZVÔ#˜K{Es≥)ÎÏπñ±^QÓ®¢ôú%ÌPLÿ™‹`3*ÒØ|H£{ürxÆÃâ2∑h6w+G¶À8	ìÖç“∆:%R≠waÆïyª˛+∆-¯Ç,ú\'ruUYüw’z…9¬fl™çπ\0#ƒ›‰në›2N©?_Wãtì…ıÈR‘b‡ƒ∫r}2÷÷öŒÏ\ZàÎ∞Aµ}‰Z‚jùÓÚ°Ò√û_ÿÄ©<Õ‘ h_®*ÂbX‰Tî¬NeUàÍ(ê∞≈HgçFsŸSÒöÿ.rFÌu¶ê%±^ëï1•RwÌ±_ÊŸ¯@Eûµπﬁ“qœÚ£¯‡`óF^ÇÎ7ˇ	iK];Kµ\'˜_≤«úÖ¶yƒπ™“Uq*÷ bù˛E√>∏¢[£8ΩÊ&€+çG~¶&¡õ•èÈÔ€5n≤5¿¿ï´iœ∆˚kÒÌÊj/KÓØU‚Sk/q\"ˆÀºYs˜££ïp≠Å)™‚´ŸUXm\"˛^¯#Yãr°ÙÆ˙◊Æ”»,Ωb¸¥ÃY¿lW≠häb~UNË$‹O‹)=,rÜEsø9SïÉ:x‘∆wâ≤äÜ™R…ì™ÍE…áÒÖ# &«€è8`FÅ≥≤PòJºRU.9≥^V˛?ß:Vı+ƒæ:î?ß´vBŒØ=Õ¸EuXbÏ–^4ÓQ˛›Ê0bº¢®Nﬂåır5G¯©öªT^\'Ì√øõ…GÖM“˛gu∞–x{N€ˇ™#ÂÎJt\0\0\0\0IENDÆB`Ç','2017-07-14 06:48:10','2022-02-28 02:44:06',1,'JfU8n3LCjbDMIqaNX8hvqtLWpaQUU2VOcTIl2AXAExBmSOje4nVpRszY0j9V','1,2,5'),
(3,'A','AD','Dd','aaa','a51bf33acf6a8e0d3f02a067e69da0a88c2445c702172693989656382ad842041a05ae109a132c3823eb20580f3f050b997a7d03fcd410da6c20b37d5b76ea5c','','','','2022-04-29',0,'','','','','','','','2022-04-29','','',1,'','2022-04-29 01:32:49','2022-04-29 17:50:58',0,NULL,NULL),
(4,'A','AD','Dd','aaa','dd88a70e1ed2455fb2822b9a5307f606ee5fb48e25959a8c330b973ec69cd7f570b93a828a12ec4edfb3b1b6652bbb32f2069894ef787579cf904507bf12da22','','','','2022-04-29',0,'','','','','','','','2022-04-29','','',0,'','2022-04-29 01:33:31','2022-04-29 01:33:31',0,NULL,NULL),
(5,'a','f','e','f','1f10f39a8645cd3dcf3093405f1b2c9eacb19fb6330a585495e6bdaae5fa146a5cbcd93004e4571c5ad07aaf5d1be60d6c0af0de653e391479845db0296e7db2','','','','2022-04-29',1,'','','','','','','','2022-04-29','','',1,'','2022-04-29 01:46:17','2022-05-01 19:22:03',1,NULL,''),
(6,'Changed','a','d','d','7c8acac639acab37f36818fb0c7026b5698d42535c7a08d81f5ed634b966101cb69f6ebab29bfd3a4d467086847829b49757e5ca72fa49d63b5e431a624645eb','sdf','sdf','df','2022-04-30',1,'df','df','dd','fsf','sfd','df','fd','2022-04-30','df','df',0,'','2022-04-29 17:50:09','2022-05-02 19:02:22',1,NULL,''),
(7,'sf','sgf','a','dfa','433b191965dab0033aeb6ddfc471b9fd8f089d0a562a8e85be9cf90e3c3b82037ad86bf5fd570f9bce0758a0b4f2176b880692d657357ec96190d1e28ae9b773','','','','2022-05-02',1,'','','','','','','','2022-05-02','','',1,'','2022-05-01 19:22:17','2022-05-02 19:02:33',1,NULL,''),
(8,'Ch','2','2','2','f5ea28a63fd96d8a62fd93c455f644dbec1fe298ee1a659754c3bf8ea3eee8ed851a1e80eae31ea91efc80900025c3f2ae6cdf8757842d77ca28380a186b0a46','sdf','dfa','ad','2022-05-03',1,'fad','df','a','afd','df','df','aad','2022-05-03','adf','adf',0,'','2022-05-02 19:02:50','2022-05-02 19:03:27',1,NULL,'');

/*Table structure for table `vat` */

CREATE TABLE `vat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `vat` */

insert  into `vat`(`id`,`type`,`description`,`value`,`created_at`,`updated_at`) values 
(1,'0%','No VAT',0,'0000-00-00 00:00:00','0000-00-00 00:00:00'),
(2,'21%','21%',21,'0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `vatconfirms` */

CREATE TABLE `vatconfirms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `vatconfirms` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
