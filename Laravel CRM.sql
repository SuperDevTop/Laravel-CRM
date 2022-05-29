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
(1,'Euro','€','EUR','2017-04-18 01:25:34','2017-04-18 01:25:34'),
(2,'U.S. Dollar','$','USD','2017-04-18 01:25:34','2017-04-18 01:25:34'),
(3,'Pound Sterling','£','GBP','2017-04-18 01:25:34','2017-04-18 01:25:34');

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
(1,'Merchandise Purchased','Compras de mercaderías','600',NULL,'2015-11-10 08:49:10',1),
(2,'Raw materials purchased','Compras de materias primas','601',NULL,'2015-10-21 10:26:20',0),
(3,'Other supplies purchased','Compras de otros aprovisionamientos','602',NULL,NULL,0),
(4,'Subcontracted work','Trabajos realizados por otras empresas','607',NULL,NULL,0),
(5,'Changes in inventories of merchandise','Variación de exististencias de mercaderías','610',NULL,NULL,0),
(6,'Changes in inventories of raw materials','Variación de existencias de materias primas','611',NULL,NULL,0),
(7,'Changes in inventories of other supplies','Variación de existencias de otros aprovisionamientos','612',NULL,NULL,0),
(8,'Research and development expenses for the period','Gastos de investig y desarrollo del ejercicio','620',NULL,NULL,0),
(9,'Leases and royalties','Arrendamientos y cánones','621',NULL,NULL,0),
(10,'Repairs and maintenance','Reparaciones y conservación','622',NULL,NULL,0),
(11,'Independent professional services','Servicios profesionales independientes','623',NULL,NULL,0),
(12,'Transport','Transportes','624',NULL,NULL,0),
(13,'Insurance premiums','Primas de seguros','625',NULL,NULL,0),
(14,'Banking and similar services','Servicios bancarios y similares','626',NULL,NULL,0),
(15,'Advertising, publicity and public relations','Publicidad, propaganda y relaciones publicas','627',NULL,NULL,0),
(16,'Utilities','Suministros','628',NULL,NULL,0),
(17,'Other services','Otros servicios','629',NULL,NULL,0),
(18,'Other taxes','Otros tributos','631',NULL,NULL,0),
(19,'¿? ESTA CUENTA NO PERTENECE AL PLAN GENERAL CONTABLE','Entidades transp., efecto imposit.','632',NULL,NULL,0),
(20,'Negative adjustments to income tax','Ajustes negativos en la imposición sobre beneficios','633',NULL,NULL,0),
(21,'Negative adjustments to indirect taxes','Ajustes negativos en la imposición indirecta','634',NULL,NULL,0),
(22,'¿? ESTA CUENTA NO PERTENECE AL PLAN GENERAL CONTABLE','Impuesto sobre beneficios extranjeros','635',NULL,NULL,0),
(23,'Tax refunds','Devolución de impuestos','636',NULL,NULL,0),
(24,'Positive adjustments to income tax','Ajustes positivos en imposición sobre beneficios','638',NULL,NULL,0),
(25,'Positive adjustments to indirect taxes','Ajustes positivos en imposición indirecta','639',NULL,NULL,0),
(26,'Salaries and wages','Sueldos y salarios','640',NULL,NULL,0),
(27,'Termination benefits','Indemnizaciones','641',NULL,NULL,0),
(28,'Social Security payable by the company','Seguridad Social a cargo empresa','642',NULL,NULL,0),
(29,'Long-term employee benefits payable through defined contribution schemes','Retribuciones a l/p mediante sistemas de aportación definida','643',NULL,NULL,0),
(30,'Long-term employee benefits payable through defined benefit schemes','Retribuciones a l/p mediante sistemas de prestación definida','644',NULL,NULL,0),
(31,'Employee benefits expense','Otros gastos sociales','649',NULL,NULL,0),
(32,'Losses on irrecoverable trade receivables','Pérdidas de créditos comerciales incobrables','650',NULL,NULL,0),
(33,'Results on profit-sharing agreements','Resultados de operaciones en común','651',NULL,NULL,0),
(34,'Other operating losses','Otras perdidas en gestión corriente','659',NULL,NULL,0),
(35,'Finance expenses arising from provision adjustments','Gastos financieros por actualización de provisiones','660',NULL,NULL,0),
(36,'Losses on investments and debt securities','Perdidas en participaciones y valores representativos de deuda','666',NULL,NULL,0),
(37,'Losses on non-trade receivables','Pérdidas de créditos no comerciales','667',NULL,NULL,0),
(38,'Exchange losses','Diferencias negativas de cambio','668',NULL,NULL,0),
(39,'Other finance expenses','Otros gastos financieros','669',NULL,NULL,0),
(40,'Losses on intangible assets','Pérdidas procedentes del inmovilizado intangible','670',NULL,NULL,0),
(41,'Losses on property, plant and equipment','Pérdidas procedentes del inmovilizado material','671',NULL,NULL,0),
(42,'Losses on investment property','Pérdidas procedentes de las inversiones inmobiliarias','672',NULL,NULL,0),
(43,'Losses on non-current investments in related parties','Pérdidas procedentes de participaciones a l/p en partes vinculadas','673',NULL,NULL,0),
(44,'Losses on transactions with own bonds','Pérdidas por operaciones con obligaciones propias','675',NULL,NULL,0),
(45,'Exceptional expenses','Gastos excepcionales','678',NULL,NULL,0),
(46,'Amortisation of intangible assets','Amortización del inmovilizado intangible','680',NULL,NULL,0),
(47,'Depreciation of property, plant and equipment','Amortización del inmovilizado material','681',NULL,NULL,0),
(48,'Depreciation of investment property','Amortización de las inversiones inmobiliarias','682',NULL,NULL,0),
(49,'Impairment losses on intangible assets','Pérdidas por deterioro de inmovilizado intangible','690',NULL,NULL,0),
(50,'Impairment losses on property, plant and equipment','Pérdidas por deterioro del inmovilizado material','691',NULL,NULL,0),
(51,'Impairment losses on investment property','Pérdidas por deterioro de las inversiones inmobiliarias','692',NULL,NULL,0),
(52,'Impairment losses on trade receivables','Pérdidas por deterioro de créditos por operaciones comerciales','694',NULL,NULL,0),
(53,'Trade provisions','Dotación a la provisión por operaciones comerciales','695',NULL,NULL,0),
(54,'Impairment losses on non-current investments and debt securities','Pérdidas por deterioro de participaciones y valores representativos de deuda a l/p','696',NULL,NULL,0),
(55,'Impairment losses on non-current loans','Pérdidas por deterioro de créditos a l/p','697',NULL,NULL,0),
(56,'Impairment losses on current investments and debt securities','Pérdidas por deterioro de participaciones y valores representativos de deuda a c/p','698',NULL,NULL,0);

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
(219,85,1,'2020-01-27 13:50:00','Jái montrer la maison ref r15647',NULL,NULL),
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
('icon','�PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0�\0\0\0E\0\0\0��l\0\0 \0IDATx���{�]uu7����0�i��i:�b�)\"��R~Q�RA+�r�WP@�L�\"��L�d�Q1�	}��>(��\r����#ňc1�/�8N����co��	P��ʬ�kr���>��>��g]���-.�T{coa��e���H�!6kd,\'���O.70s�1�\'!�_��P�A�0Y��LA�p�t?�3ro��ID��1C��D��X������F����̱�:&�M\0зpq��*��O�bȼ�Z��E�vy^\'�<�{V�����Ʒ�C�c��3K��ӌ+��RW�b��2��l����{�c�\0�eL�*e�E\\G�-c���8��U��y=Û�{�PS�����]_ip�>��{]|&}����{g�`�\"�J���\r�?s�)���	\0���Y�J,P�Y�ƭ���sQ�\Z�Y���9��wb�V�B�٪�s�����\rN��zϬ_?�~��8+��E���l_��F�5�\rN��8�Kk�eZZ�?лF��x��؜���V�2�w�X�gi���]� �:8>4������V\'�U��T\\N���+L�D��4�׌=�1�/\0@���3p��8I�kp���>��l��=�5���ҥā��g�t1^��E��O�8˜���g��S:���\\IN��Rx���⹸Zk͋�kq1����s��N%.�yP��@�C�P7y��e|ަ�Z��mt�\\��L|Z7�7cĻ�ܴ������j���բ�We���1W��R�q�,f�\\\'\\(}T���P�Ny�t������<�`�½�E�D����B�+K�Z��q>���������Y\Z轍N�y�YCM�>�y=�p�t��Ig>�#E�����/r��(�#Q�W�:+x7��p��^��;���@�	���{gS~Ӓs����E���;��e�K��5�x��n��G�+mKD�p�ÏYjɍ�q��?CS����wj����q�#���*qv�%7~�K���>�w����\'���dY��׋����=q�[>7�c+��(��4\\&�¿�5Ļ�\r�9�ݞ��m�G�y�I���ԷhRK�`~�\ZQ��?�34�@O˺�V��\"�֊¦�g�,e�����e,�C���������J<�`��eF6NS��`�e��4�\n<$�\\m�1N�s��Ѕ��.e>�7��>��	��.\'�D�-�����ݫ��cp*.��q��V\'q��Y|\\���{�m�oO�:�kE^&�gK%�!J8�<�x#��f��vL�\\�ǖ��+�h�8׼އ�zcZr�&Kn\\鈿�:�?$�Q��^k���{�C����-�u�\r,��w���e���ŉ?f�#�����h;��?V�OE����V���n,oԈoJ�i���;��\"����+�����5��2�\"�^�)?����S��`�P\'.��?N������ۿ���B���\'^H���W�]���y��rS!s?Url>*b�MJ�ٛ��)\\��-\\$��\\.�+p����%ʗ_�a��?�v�N%I\'��[�]\"?�p�Ʀ����rL%�\0���s9�>���<�@�ͻ�	u*s�Zў����d�e~o9�Y�@�\'H�TW��Z�ќLyZ2���Ga�h�\\%�Sۭ�R��2o�$]R�N�X� ���D�a�X1�\0���������F����zֿ����$UV���K#��?�&�a�v��˼N���-ތ�\"b*yq��~\'�ˁ�,eA�#��<�]�(��\\4c��Z��������|\0?�2T�6[�Qf˻߱s�����FN�����L~Y��r����(��ӈ7a\n�@��+��&.$P�sg>�� ��C�|��D��XK|�\\��\Zy���c��\00{���F�}��؛b?���x�L�(m�0񠌕�~�i��v§>c1ݛ�E��k��Q�n���uu�8KZ���/�u2.2T!�N����v��\\+�/�8r/�$�z�����\0svɸ���2��E3F���M���K�э��?Uq�z�xPƲJ�r#1�,�]4cKp���4�DL�A���\r�r�dZ�Oߐq�,ֺ��n��?B�����p�+��Wb�ԷEl2&O3\0�/(����\\/7�g�)آB;\'��	{KϮ�v�\nd�V�\n�A�A\r+��lmu�ey��rL~�۵=�p(>o�I*霡U+N�xQ�Z������Иz<]0gp��|U�W��y\"2ka�0N�����:���J���X�|��!r��\r\"FdN ���:x^`�wœ���tR��j��[���\\ܫ�{Zc*�?[���m�t�%�3OZ���%6�?��wU������\n1M�W担&YTqF>,�~�\Z��d��)��#J��1^�4]���y���sL�V+@��v�@�8���������=�GS�S5%�SƔj�D���s�z?��}ޜ���/U]dJ�q�����wLK�V+@��K�(�e����+ۢ�4WK�q{�=u�j�%�+E]����<A��Tզ�J��8�D��@�\'���D�#M��g�w��p������\r�����|Y�1���?/3��`���p������q�t��=�v��\r�c�	=���1�o�3z��o5���~��\r���8�r����*O �:^:\\��@Ͻ�����~��\'x��ki<�מ\"⫸�);m��N&���,\Z]l�����M��W��4ad��T݂�|����\r]�>=�[�Ç�8\rK�a��y�!�����q�	������t�k�\r�	Kw������\n�ɼ;@D�|�I~f+�+�}�Y\"^�u�\Zi�6.��<_�Ѓdn7л���u�\rxh��D ���3���%\0&4\n��ĝzf��Y���9q/��<U}̛j��}\"�Y��1gp2�����i\'h����q�{.e\r����y��d~u�sV��e�j~ϲ���F�9zn�J	��e���;��N|fl��!8�X$L&/��\"�~=�*��\"��2�M�%��>h�gd�y¶3�����Lx���cz\0Dv��ÿw%����M�n�૩��S�s����!U��S��DAN�^c��.}�륕U0�G��������岌��k�}���!\"N�%P=��n�i�g������ |\n�>.\0�1η����z�Ẋ֎`�y��\n?XY���ys���c�|����&�t�>\\*|Dx��C�5�lޖ\0(��r�̧���_Xy�E�\rG�t�Sӛϣ�/&`�\Z?���ƛ�ڱZ#�)�ַp3��5�\r�\'�&���r�4	�QV�\Z&`��zv���b��^��ym~�S��Cz���~��<��ms�9�6������~�@l�7�>�\0�����@��@���!�q�����6n�P�4�k�{�ҷ�SF�0E����L���&�k�	ߑy���v���ck#աݞN�����\\#\Z�;��o�X���ȓdy���w��\Z�Wˢ{\0����<W�z�˓����K��R��?g��G�>� /�_\r�~m�>�Z���Z:���?���8�f�ˉ��=�H����;��+��v�\"�E�fu�(dLP�>����`Z�|Vb���w`\r�h��-2�=�[�9A/Q�/��.�J����	g뿦���\'v��_L��V�D\\!\" �$O������{�봣3w���\'��E�!����5��ز:MS��j��=	R|�n^�cc�0�&\'v�K���_�t��Gc�]5�����5�y�L:������>��5\r�_u�b��f�V��ȜJ<��x�M��TY_��R�=��?�܉�bJM����n|W�E\Z�¦�C���9���̋Ey��÷U.�.�c��ȡU@�N����̏�?�z}��ɣ�ӽ]-��3z��wn�3\'b	����]����\r��B��˥[�4ft�8��o��3���t�n�\'⎝�+㈿�7�ϖ�\Z�e4��-V��욘�g������f= r\n�܅��=�J��Om��#�\r��/�Wv\0L���vJ㤉�~ؗ��ȘT�)X)�T�w�&։\\�شa�:�޵��(&`��W�Y�~��	(��.�Z�+�-�������Si�96�w����,��d�%zɏT���E\\j��5��6�;���H/1~|�Ƕ � �u�\"�2{hɶK~tW˸V�o�(�fM�A��&6�ve��\n3��g�)\\o`\'�9�8�=F�����XG��xtū���e#Uq��m��][\0&㵸��r�F�\r}J����{>�x\0�PM��|r��Jޏ��k�^��O��XС�8PzQ���_�޳�25{po���3�O�K*j�,_%�y�l,з�00���z\'+5eyq��E2��mj|H�}3N^�{��U��Ϙ��C�\"�0гd�����2?\"��$<��I���LDmK��������K��t=;�wB�p�p��ޯ<F\"�S�/�z�Ï��Z:Se���$w���,\'�y�+��w�ܩo�v�(�o�{�}\0���G�f�XO��Hc�{�����+(���^�u�ׅOj��\\T[�LlT�����K�\\;�i8o��\n��4�\0}���3�k�J%�Y�4\r]����<]�b\\�Ы�=E���A\"OR�U��m�gL�E�Yi��2�q���kV�1@���Qs9�gD��E�G�3�\r-��s�w�����.�F�E���C���w�#ۘ���fM�N�9l�о�9��+d�\0��S����^>E�>/����q�y=��^s�:�y�̿ÏD�o��5�u�<Qz��Kd����\Z��h���{��*��ݪY���U�\\�.�k���(�E\\\"�bo�?��zn׿�N���q[�\r��(;Æĸz�~�$��Z�m�_\"�_�ҝ��n��]B��ߛ?�.�+��o��-&}��ooJ\'��/n�ޙ{9{�p���O�nQ������>�y߉\0`��	+�CMEvc:^H{o�ߒ(b�Ư�oS]:gQ���_�I�0�>,W���oa�v�F��\Z)�ڭ�F�2?��>ߗ�@5˵U2$�����<��O�Ԋw��&\\ }A��d�C��2�-���6=h7�҆�+@��\Z�X���\"�n{h>�T����K�\n�B�L֍OP���@�$�ѫo�m��[(n!�Ӆ�	����*�X.�N�ȣ����T�\rG�=t��&����v���P�A2��X����[E\\��j�����^�<M�c\\�ѱD��em%&��?���nnԽ�(�E���j�o�.�*�+eLP5� ��_��2n��.�ꊗ	Gk�����f���>�+E��̫+�\'���;�W�u7�T�Y���2���1b�m.c�g�ك�ԣ+Oƿ<��V4��7\r��7��+��m}C�ow%��q|�	�5�����b���\0U�z�>ΊsN�I�l��`�̉.XP����1���P1C���kdq�(��z�z��^���d9r����S�E#7�?�n��doլ�f�g���:�\"/�����9��.�Um��i��l��RZA���5�Tt�W�|�]��\Z�F��ʏ���~�\\�^�u����l�E���:l+�z����:6KX��g�t�����!W�ˌ�vc���{{���e�[��;�������ίɒĩ��>����uJ��e�7�������)�ohG�x�tV5�!>�����>�*|n�\"o\0?�>�b�v���/f��d�����Ճh/��w+��Ǧ?�iQSQQ+�wEq��[f*���\rć7?�����b��Xk��ti�t�0\r�~ٱ��M/\"~�ȑ�3�����\r~G��\'������_f}\\�,��:�6<���/T�O�&��k6_/�~��|U�bE�p�6lEu������F�6���UU����{�g����e/�|}C\'<�[�+.Pl�ӯ��ك�q;�����U}Y�Ǩh�K����y�>��B\\�m�:��\rU5l\\�=fg��q�t���{!��}\"N�����_Qh�\'��a\Z#/S��Ve��VĈ����+�+L���U�F�W�2f�r���\Z�,�Usz܄��\ruU�]�Y�\"�^��\"��gu�Z{nz-Y�9Czq}C@�\nXc9e�.��Dΐ�WȻpi�]�}�u�)�G4�;m*���u��*�/�1Z�sD�Z�(1\"��`��,\\�E�57�(*>~����cX�?�=��˓|\\�v��zT6�x�0\"b[cW�ݦo���l�qu��ؒ�\Z�y�ϕY�;(�ގ��m³Ti���y��^·p���{�^x��s�C+�����i���w�����|w��-�S��T�i�w�VT��ֹ8X�r��V��jV�G��5�a}�\'�F4ϡ}����(�[S�S�#�N�t���ڭ�ֱB�Wgx�?�nFv⯰\ne��>�\n$�$3�|������&H����UI�� (�htW��_��Сt��\\�\\_5�糤�u�a�\"KsgV��e´j\0�D<$�K��y���4>�[�{.XT����(�^P[�#�v��}�l��S�j�6B�;h�\Z�>B�/�_�92/�2��yo�o���g��*��Qo0�gl�է	\0:��)�R�e°Ԭf�<R��0,���G��uY�b��l\n��d�P��`�n�њ��Ҹz~�\n+��$:&+�S����o�[:�M��Y�El��Q�l*r�2&�X��+�V�h�\"���O�%b��X�,7hh�b���2�`���jt(��X����wLM��ʖ1@cӈr���|��=�������F�_����跈��˪���3~������~��d9���=Z7�w��a�`���+�\";�V���|&��cܗm��\\<s�(&�CH\'`�2��Ҟ\n���n6���Qy�BQN��֊N-[�9���^J��h��W�K�\rc*�tZ*e<WV-m&�����^��$���BQ�+��o���\\�Ī�ҁ���\"W�;�Ի���D�T:R�����Wxa�r�Z#��[ob7ga�����j���U��{/&)]�ȥ�ͬ��/oj�����_���CB7��:@�4n{�4ﭥY����4�HU�١�|�.ڍ��\0t�S�B���a�_tjD���Z�u.9������b�U��mZ����U���]��Z�ooy�M{�S5R<?�T���ҿ�@�v9N�X�F\r����a�3�a�#��{7�_�c�(6�Es�����I�Q\"�B�PK�n���3������,J#��񞷔�tj�VLT^``l\\��\0ަ\Zxv�88�v{�V���3|���L^�u��_�Q�s�t�fM~\Z�\Z�ݒ[�3�QF�+j0�T1���Weޢmu=`��PF����TLN��\'�L�\'��E�]�����c�)��B)�S�:��8S�u��sƴ�i��C{	� ���q�ƞ�?�4�\Z�KEW��7�r�(&����g�N�w�;sD�BW�/N��%�(si5��T����\"�fn���-�&N��o+,���LT��{��޼�G���N���ժ�e�&��/&7�{�m�[G�kqS12o�&�Z�u��������\\R5;�bPL�Jel�6�Y�1���g��xHX*c���1l�p��q2��Q4:���*�τ�b�t�p��5ƭG�Yq�ņj*tv��֫C��������\ZY��x��ب�\Z�.i�p����?ɇD�K>�P�D:U9��2��\'�>�5�}J�B���P��@�j\\C���頪d��!n2г~.�~��d�U��T䵊�mW���T�[�V\r�m�7����޻�2������W:����j���?{���} ^]Oq�P�oY���8kl7��5\0����y1^Uo#�H����s�/U�h�^QtȜT)��W�~.��ƛ�$�c�|EZ]�t�qƑ��6�����ҫ�Y~AJ�z��T�X��U<R���� �U�嗫��\r\"����*�姉�U�M����Yʸ�E=c�<�PY׽�/�����+��.�%2&���?�-�O�����m�(g�$��T9���y�z��(꿼P���<�����A~�;��i_ς�v�8L�0	�W������Q�7��q�}i=��T3��\r\0f-(4��f�!S���+��\\�h5�Q/�4�(u+�\n{��B!�y�F��nd�(�2�5t�b:��\'$�S�>ܩ�~e�%��Tɻ����(u?�܇���/�M{�l�U�5�������j W�U��x�(^mތ�cj1�і~��++�t^m��T�.��o���&!\0\0hIDAT���y߂q��5�>��V�m�ǋ�o���\\|��f:��@�tU	��U���U��������g>:u�{)��jW�u��#5I�*ϸz�aM}:)N���K랁Wa����i\n\0��p�\">\'ݬ�O�-L�qj5�#7�J��`�(.��j�V��G�p�2��\'���յ�߭\Z��\0�+��?�v�O�1��<Eղ�Q�4<�4O�@=������H!�#������ߓ����ZML .�Q*�-v۠��1��6q��\'�\",P6>&�u\ZA�Sj�G�ְR�e�n���گ��/��]��J�fY�6�ګ�:e>�ll�[G��J�O���Tg��lt���Z�7�\\�a�2�jvQ�.z�Fᮺ�GԜ\\ֹF�+m� �Sy�jؓ���\rf\0��~�?~\'��ӓ��y���e������Y�NR�����q���q(_��X��g��r��Ǩ�h�j�hc�&eq+y����F�@]��0UƺUw����{>���\r���oA�A8�b��ĩ\Z��}����˜��Ҁj�\ru@��QO���:F�wh�֍r��)�P�L��[�3ϮY�[Eܤ����N���ڻMWͻ�K|�v���|�� q�p�撇�3yd\n^Z�Z?Qu�uW�-�+t\0y����\r��1�s\0;���vIm�{�UuM�zE�NZm�*�L�z�K�:e�����/�l�-���@�\n�?xP�i���iL�KF���Roi��]��{Vi��7���,�^2�����P�r�=#�5��IB��R�N���d~���]��\0��r�P�¹�����g̛1�oa!c�p�*;�cU��n�K�%a�V�j�ǟ�/�����yS���~ߟ��!���\\a��Q�m:4��ey^��y�\"=�^Y>�*�������Y�\0�k4n���3�\'(�]�V�<��2��\Z��|髊��y�J%.���W֍�+꟦ȩ2���Ŀh���R���wh7��q`�=j,��g��<��+�����2��A�[�E��x�5��:�l�s�c���2�c�dw���y�t	�W���Vk��n�o��f��:�Y�ץK�T{ׯ��\\Y.�J��	�$m�VC�ȩ����:X.c�f�S\Z_�\n/0,c}�9ݴ:�}%�.R�}��ܞ1y*\0��T�$�U5�T�>\0�H���{Ekƭ\"o2�w�6S{��ī�6���2J~��������$���SbX�����?�����a吁����<�\0��ǉ��٪���qEݎH���{U�����fd^����&��ic�k6�e�q\'9RC���VEwK�M=RU��lUQ�]�E�b��3�Z��\0�Ȝ�n�m���i��W�3��YXx�i�Qxf\r�\r���y��3�� 螨�j.h�\r����ޥ�.Gy��WW{�Z�+�.�kj��#\0*��ILT�i�;Րٯ��+�Vkh�,��?��M�̃뮭?������%\"�Ո�ʲCjjG�QN��Ru��뒌e�bܣY�ߜO�1���9}1{�n����imG+���}�X�ȃ����N<$\r�؛<��,��Z���W�AD=�6��F�R3�����\0�� w�R�� �O���:��*H�kxX&꡼�~d�����Z_\'�n����f�옌�\0l?V\'M��j�MkK?��O��Z_oR�V�Z��5���ǂ�1y��\09�\Z��l\0\0\0\0IEND�B`�'),
('invoiceEmailTemplate',NULL),
('invoiceHasIrpf','0'),
('invoiceText',NULL),
('jobMonitoringEnabled','1'),
('logo','�PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0�\0\0\0A\0\0\0�N��\0\0 \0IDATx���}�]ey.�߳��8��CFD�#\"EDDJ9��\0��EK�\"bf��R�Ʉ!�p8�d&(�\"� �H���) F��bĜc��8{����V�?H��\\�y�k�\\��{���{��{?�����q�b*�\nSd������B��Y�!a��%����蟹��[����}}�\'��d�L^�uXx�XFN��d^,b�L\":�+|_Z��נ����-2�׎U.�n�N����s�%M��t���\"L ��|sgl��}\n��\\���������Å�5:��wzi���BX*M�x���W���Fgs̿�1g`�Qz{\r�/)s�(�D61-��۽jpǉ��w4�F}ݥ9��<��q�V����c�S�^\"?��t��k4�ܧ��QK=\n�g0z�w�|��nu^Ϛ�^7�x^-b�\"W���60	ʸԓ#�\\���L��8Sϐ9S�wk�,}ݥށ��]L�[�;�q)�ܼ�rtz��F�̭�I�ŸJ�W�[��+c/=�7}m�̵��S�����o=�e^(�LԜZc#�O���<ʜR�)�B�ǈ+d)����+F��k4~w^�����˼�V#z�cnP�w�x��x�æ�����[�����~,�g8�aӇv�O-����~iL�߄n�M���3�r����-�e�æ�B����c�KLs��{�����u��\\;�a��o�-O�N�(�؎5�_�x�p>O�7�{���/G��k���t�rX_�F�c`\n��-t^�����M��$��\".p^��ށ7c�F�j7&���Y��i�)2�U�I(=��\r�6��8agKO����Q0oΏ:�~��s��\\����\'��2\\!�e^�\'�op����!\r��Y[_�#2�Ԡ�6;���*E�e�Bi��a�g��D��B��i4?�ݺX8\\:��|�������m\'��n<J��;J��mc�w��qA��w`�9�W���^K��_j��;�GE�s/�GcެU�ȸBz���)}o&��N��F�r\"V�;c?�����{�u�3>ʙ�q��w�V�t�+1A�1�t��<�?�Qw|}Ki�[Jo��#����N̠|�Îy��[~k�-�u�����9|�C\Z��)�>(�~���׈(����Kw�u��^����cv�8�<S�>�a�\'Zxˊ�i�C��\"�H����ȅ�z��\0eS��q~�E2��\\�j�s�V\'�w�ψ�[���Z���8\0���4ܭ�IR��4r��y2�����l*����Z,���$:4�+��%X�#q���GG���s�yU�L���u�ې���r*��^UYԸM��搾36RC>Yh���o���+�O)5b��엾,�(��s�F\'���y0yq �֟��Ʊ����1���x���?s������y��n�FZD���#�t*��#��x7jt=��ݛ_�`�*�q����*��<K��2?.�8�¥ҹ8�x��Y��U`�R�d�GE�G�8�\n��»���sF��	̳��`��x��喴a{�k���)��>8k�=,҈\'�u����\Z�#��!�Q�x!.ŉƃDh4F�������\nc�Ni?�#��u�e!ί,y��۱���5��g=�����k�b����X,�![ʲ���;��3k��0E8@z�*��2��y3K���\'�\">��EJ��k���#�����1�з�C;�[Q�<[�Z�����{V�����كE^�o��9I{SE�TfQ[ɵ�ω�ģZ�.�9��W~��<dZmm�/�\\ķe�yh�303�W`7\"���=�SO���N��y\"�T9�q*N.0��S�0x���w�/�3��0�1�)8.+��]B�4N�ؓ|�J�]]q�xB�X-�Vk�?cS����1�Sj�aW�W�J{�T|_�(�����=eEcvÛ�\n�\ZLû�S��Y:\n�g+��.��.����l�>q�`��I�^��2��<��:���|@�2}�9���!� 鵪d�o(ݭ0E�ݿ���$������-8@��ܝ|����}h���_	o5��w2���*,SD�+\Z���T�{�U_[�_I+�L��2i�2\'(��\n�_��g�3z�ٗ�!�s��)���Cp�F�F_��(�s���ig\'q,����E����\\�u�w-��������k\'&��	{�{/$���1�w)����]�Wb��}vM5�k���w�\r>u�(\"�U�y���wD�7s�?F�f-�Ƚ��2�������s{�#;�T�K��6����-�}VY���0����w�#�燎��>�ʂ~���ę+U��x��W�CU�Ç6St>/}м��6Z�����?}	����߽|�Et\0�Ÿ�|u��W��3�w���/�����_ō���Ě=���/����oچrE�O {���%����>\"�\"�a��/-|S��ț�o���H{	o׿t�w��BN�߽x����M���h�6<�b���7���7z�������Y�_t���gÚ\nt9,����b�-��2\'����K�r���\'���y��W�<C��;�q2?)➺\n}�{k���7�Ʌ2���{�o�y���z����m�=F4+\Z��ݟ3�b��b@��g��ۈK���ReB�/��g	�m��R9n�\\��e���z���A0w؃�Cą�g���O|����dU]�K����=�y��\nb�𓊓�_��q�	>Ղ])ܼ	`{o�C�\n�TFa��&�mZ�M̺�=f�f�����q���;�s=\r�b�V�Wx.�e�{67,_�;��pn�+��۶��N�w��˼M�X���A���qZ�п`{/)�q�����S�ҞD���i���F�w`�3洽]���M\"�ɜR�|{	�-��7cǨU�s��M��}䰈ݷR;\0�m���!�?/|@���`��~��p�塀Lp�+�\"ӿ�%�:������{�۬�hk;����ҡ� 1�k���b ��*̻�Gg�<#��]J;�z5\"w�����\"\'�ԡ�xߖ�z��RG�T�x�7��xX����S+��e\\e~]�W�/j*>�,w��= �H�\0�KDSjm4��fd��9��(Q����%����m�u�\Z�/y-�z/ۂ��B�$�\\���H��R(��z�����,s���yzq�张좽���v�Yk��1^k�%2 ܪ����t��}�vs7��F��Q���F|X��F�F|�w�\'�.��q��X�̓�K��T+��V.v%���?.��\\�7������=����y��U�3�Ec���r{�8\Z=��W�����[�	�jc��/�c�=Eĉ2�׽k��<>,��vD͘R�m���:D�U�Z�^qSy1&T�G~�%\"�$��<�X��rx�����E������%�=��JK����>�P�?��`����?�s�^��Zv�e�0�H\\G��֛�=vǙ���w��T�l�D�/�W�ҭ�<{��w3^��Q^a�f*׿�z��\r����P|�w`��\no�H����T7���&�cE,��ﹿ{���q�����;�r�x��!S���8W��!0�Fn�Wf^Vxn{r\\�i�/���a����b�Q.�h����;����\'�/~�;5b���g~������GE�����h�{}}C;���9V���RR/ހE~UѼI�����]i�|뭃c�t�0���I�&��f,1�g�fV1��#��G̫�oc��.���\0�$b���A�zv��gA\\���nT�m����u;�U�Z~��bW���Ɠ�q\'܈�<f��m�Iz/�߽���<��|=9���p�Od���(V�rD���r�V<��%�]�Ӥ�W����EbX�����zހ����Y�Xey��4��=�\ns�5w�����E����ƀ���wi����*��e�ׅu����y�&Ni�`S:W���;����Y����7X�M���-9f|\\D����\"$O��l�?[�lYYs7nC�!�C�μ�z��hA�V��;:^��2���,;BU�Xѽ<M�f]�\"����=x��Ͽ50��+e��Ts�O��Z]�{YS�\'(�/U���R#O=�¿.ka�i��d���׸X�9���p\r�Pnגo��H��$Oy�M�ae ;k�k*v�&�k�_h���g)�R�ASE�ZNnݴ�7ɭX���l�Ͷp���Wm��z7؈/�Z�v p��-hk}��w�b�\"�M��\ri;�\Z�f��R.F6Y��	�q��A�m]�2kwX����o�!V�����X&,�	��6�?4��A}���^|y��S����������ܭDp�NT�{e�H�\n���,�<Rz��Y,��!�c\r�W7��i���e]%�҅�Ɋ��v�Sx�(���Qe�\"���}��O��~�C�P��s戧3�?���6(0�sNPl�\\\\[�֕�w���l���#,í�[���y�+v`�$�\0�,��kv�2g ���2�_7��7}k���큹%u�4x�z�~�U����+�)�ZE,�!s?Tn�\n\'�G�<J�?`�ܞ!��;��(�Op��b�1��KKe.T�y�F�¿S��%��,��x\\�D�*eΔ>&�.��y�,Γ�A�����EM#R����dlc�MA^\"c�&Rb���\rҖ����R��p�p�ށ�V{���r�ȫZ��$����lK�$I{H��C���H�Nɉx7�J����l��솯Jg��o�{��#b�<kQ6&�_Lǟ�:��C�E\r:|nb��\Z�/y:�U�U�Y�V�s�>@>�8߈�Ɣ�UE~N����W�W�������x��M�B�G��y��[�j���o�c��ۺ�s����k����d�X��o�ۈ��6���7��};�3�6�Y�pM7���������u��;h��Q����]f^$�lz.�*���:{��NՍ|&�m���ݡwp\Z��y�y=�mG8X!\\�7V���5U}�2�Dy^#uI�\"��\\�z�*��K�bo����=W����ݔz�]����;��;p��Y�ES�8W]\'b�̗)�s\Z��_-#���5�l�j⽓<���p��R�bs�Tƻ�*���1+w@�\r2� >�wpc���I\"Ig�׽y�y����[�)��M�=��Ɗ�^s��������ĭ��u~�\0�O���P��?���2��!n�5���;������	b��Zay%�ng���;x��Z��&�����V�Ǜ��(b�J�\"|G�\\Z�7�k]|�F����N����2�e��C�����Ҹ_6����O�3��I߬��7�$]�#�q���k{B�;p���Dr�v%vu���߭�����vq\"ō2?!��;p��=�|�_�h��7[�CU)7�>VI�y��虬������Ћ�P\r������Tg^�3f<��5�x\0?����D8\0o�;��vB�k��E�z\Z/��)*����v���6H���VU����`��e�S�T��\r�zDh�j�C�Ƀ���z;�WF#6�>����yg�F�S��!��V��g����\\��%z����\"���hI��I�E�W|t#]��j9��t�x���}M���\'��,2{`/\\�0C-�jr�*5pZ�P�x��M���9M��>�i������Ց��z�N�(��V9�RL�l�ݿ{i����:6����\0Si���E{�2\'*�M�e��(�����t�2A���������2\n�P�	?�nU6�E셣�R��bD�{���\'�Q\'��J>N�y<~�(��,��\Z����#�V���/����Eǃ����p@mq��Y\"�d��声xV�yp����\'n�߽�ك�1�Q峖�*�b_b�%UC��������ʖrb��,4ǌh�:���<\0/�*��R�����^�T�ԁү��G{*X��_o��v��b\Ze����rX�q2�ǟ��\'��Mͮa���ESY�D��E����[j��1z|ĳ���ת�Go5k�KÉ�ˤ�J��_�BG���t�9�9@�2�[94���Z��s��G{�I�g\Z/&K���1<|���ڒ�-���C)���&�D~�ܙ[�S�=0M8Zx�TJ�\"��pw�^����3o�0�}����k.���Q�7�;�!4e)�(��)�Պb��=�S�e�d�e��Sk-�Z�O�dwQ9���,o�%�j��U�-\n�VNI���t��#Eޠ�UU��8�|~B�*cY]�\'���Ƶ�X,�Ey*1NulĀp��=w�B���7��q��s{�:{�1�$h�K͜$�p�O�=���!���\Z+G���f��:e{���Z�b�:��:��Xi�V�̪dgnwK��Ԛ�Z�H�!��V��s�TjR0�h�j�W���xm\r�U^ɿH�kv�e��.\neQ��\Z��U��\'蟹l�&0�/܁��4�A�\\5p	�#ĭ[�a��L�s�\"�1��&kDެ�գ۠��+�/������oo�fvh;��\0�%�xB��n��&�\n��Bj)�v��c\0�4Z��;s�/?��ܬ�9DxT�����*�yDe���W�C��K�$�d�Uo�ˤV]��&◸^�\n�P��:�S�\\<X麺0�U��p?Z2�1U:�y�f4��i�M�+Et�*�/#�^W+��\'�C��b^σ�px����_�޳d�P�����vIӞ\"�/��bDxRƷ��D�T9?��t�]�}�zt#xRu.�m���=Q꟡JH�#U�R�C]e���4�ʊF0T�UNP��WYwFw���X���X[�jT)��VS�q	�j���\\~�����\n�����*^U5N)�Z������k�>�CURZ�P�����mů�nI3�.k��$<��y���\\t֖�~��.�pU\0����U�͝���\r��\'�^X��Z��8���u\n�n0(�\Z|��R��T�����*���Eѩ�	��j�x{�Ue���Z#�UZ]�]�\\�ש�Kk\\�����j]�ԪKx��޴�BW�<E���U�wKk�������[�* �1Id?V�]@׌B���꼿^L&O�?s��7g�x�h�\0J<�*��[4���mV��;Ь�u�OvU�q��:}3�le1�)��Z�x~]��ܤ�g�Ě���Ԉë�5Nu�D���|�y��`�{�����S�w/����@�iķ�E.��du�M,���V٬;ݴ��.�����쨭�B�r�vS}:U�(d���tPM3����A���9,[��\ZJiD6�*�T�\"�j�L�K�W�hOl��{J��1\\jﲏ�����=Gw*�s����Q<;���|������ܠ�,�2�]�lH˅�s�b����A��`}j��8HzTx\nM����M{�z}�O��|����\\��u�.x�U���@���S�ؽj�Ҫ��r���ou���O0M��G$��g�e����0��|�H��Uz/�Hq񒪇���S^�q��3Z[�,��q�5uh��\Z�)}�[��e�/�_ߩ\n�\\���ޭҌf\'�u�eW�#�����≖r׃d,������\'�3�����E���dgM3���.��`���t%��,�n6)�j�1��a��(c�:Mt�u�Iy�3\"[]�5Fd������[�w=��K�E\Z�Z��8V�غ���bX3�y��8�Y��y3Z����D3֍#^;��E�k/�M�ke�����S��Ti�K����x�p\n������\'�[�cD��:P~H�_�C��<L�[(��W-����)�k���H���8U��UU���Z�����I�\n�1�z�\Z��?T˼�BXY�O���<����Պ�}[��Wь�U�����?������\n͘*��)��͵�F�̇�B3�V�(^S_�Y���x+��j���:|�`F�@Q�M�%�We���aâ1$�E}^�~�d���ɴH�\\�lvR���Z�P�IWN���^�d?!)r�S�\Z_�Sk���c�R�W7b|���HzP�W+�\'��4i#J{���\\�����1<\n���~��־\"��b�f��3��?AF���p�����{���v�g}����o~�����S�#<Z�o�?%J�\Z�z�Q:�3E��|�X�*�/T9/�Ԗ��S�1q�*O�n�1����N�(��=���J�Ɗ��Z^�l�G?����[�H١a����pe��U��S����׾�Q6eLN���r��->#���nt�ڬ�B��<�VLvǞ��*F\\�=:ۣ`�Θ3�K�T9�W��u��ꌐ����.,S���	u�eu유��E�Vqpw��E��q�Z��j�f��)Ҿu��U��G��\0\0�IDATF����_9��aw��3�Z��y\'��C�Y֝�\Z.QzH�7�?q�ȟK���ݖ��df��M�ğ�U����>���/��v��t��:t4��L\r�[2��h�N^=�~�M��ԼO�N�(�w~|�r\Z#]�H�������˵v��?�2���\'�\\���]U.�dU��K�!�VW�rQe��S��r��d\\(���wF�(���7:�3Yě�W���/K7�e�,v�e�(V�1k�)�Trw���M�K4�����iH�k(:eXx������};��}t��y�Ƭ���8��#�7TA�%J+���FuTے�y;����Z�^J���!X]����g,������J~��.iwr?��*=ylM3�P�v�[׺i�*�RTJ	O�{�;�Fs����1\n���1g��݅�T�]5�wQ�S\rUg�XE� �i����£c���OWJ1&|\0\0\0\0IEND�B`�'),
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
(1,'Rudolph','Demo','FRU','Demo','$2y$10$pmbXgAZix7ubCw9f47tRuuv45sGn.8sFcmFn9CO8syIOdZR3i9aWi','ap@businessdevelopment.es','','','1970-01-01',1,'','','','','','','','1970-01-01','','',0,'�PNG\r\n\Z\n\0\0\0\rIHDR\0\0\0�\0\0\02\0\0\0�\03;\0\0HIDATx����]uy\'��s�e:�i�4��l^i6�fiJ)M)�(-\"\"��u-�,fk��\"�!�d�RMfF�����H-\"��B�E�fY_��y�1ͦii��8N�g�8\'�			������y��=�{������y>���&�[�%�Mr�t*N�2ga�ֽ�b��	���01��;b��`[�����0�8o���z���#�v�$,ć��F�\'�W\n7K3�Z�H����]>�;��w��+)c�t~\r���5@_�Z��U�\nQ^$�h��tQ�5p>�u�nU�$���\"�Lܪq�VK/���5��U��	2.��1\"|P����|c�5w�����[1���=��[:���e��/��&\'4�oRt�N���:���]z��w�Rv�{�yߵ���������y�fc�˸F��</;o�5��Wp�7x���\\ ����|K��^��GD\\��������z��x��������\Z�o�?���g����۬�#�������.�����\n.�����Qb��Hk�W���,\Z�s#��̒�����O|�x�%��)7�[�\\�A�W5r�x��O���*|���\\b�@s�l?\nf��ۤ�E\\ny��G.S���B�\n�5�}���D�$���Z3�HƵ�,��-���	�fa��<Yz}ج��|��q�P�\Z����n��Q�C��ѣ�]�j�(߂�#Vh�n�?�焑���\r�{,8oq\ry��[ڌ����Y\"��$sa\r�>L�?h^��//�M�B��UD���|��^b�����̻�]\Z�KK��\\g�r2އ�\'4�5��qv)�s\Zy�4�H���딍+�+���;���<�\ng`�?��7�`Z�g���v�G�΁��y�ŷu\"v[�[��3��ߔq�CNp���2�\'/�~r����Ni��ӉGk�o6��ȋ��?�j�Z��qK>�i\r̨	��};zZ��)�4t�A�i�k�h\r4�s����2q^%��[�q&�F�$wH;�f5f�&sX؇��G����[3n��0��&V��G�K�,{\Z�}k`��Iu��N�F�{�Y�r��7�Ӊ�P�r�f�B��W�w�Sx���GN���%�\"���{������yx��Z��)\"&�܇]�vb����{|�}OY͐`.v�X/b�(gHo�\0kȷJ���͖\'�i\rN\"\nӤu��k���qs>��<�g�k�{�Zv�o�tz0ӈ��z	ڇ=�6a��m�{���M��M,�Lr+��υ��k�/zz��%��(�V�|��+�������*�r���m���t�[�$�HӅ��N��v�Q�������\rO�f���U��^�����[��s����٭��\Z7�s	�֪n�f�c��>�G]z}�SvQN &�Q�li�PVOn#���i�v�3+kX�b�l�\'\'c�*w���}�����kO��s69�\\o�@!!+\\�V�ѝS��Ep�����e��y�;�R�a����1�9�+�*�8^��=��4Qjס~k�@q�ƴ�ޱ�����Q�����y0�2ڧ���;B���(�Dk��Y�3��,�b��z�#�6E��^y��>e�C��]�<��0�d`C�9�]�ph��?���y�`/e�`�4_��7Z���,����e�\r��J\'���+E�@� ������k��-�{Gˑ#�-�,<��	�������鏵?1:ޑ��w�+x�Qކ.��۱�6&�G�%\"�(ͭ%�=����\0�>	83���.����̏o�Y5U8`N,��_q�v��?����EN$\'˘�&@�\\�5��d�:y`:����U�Kڽ��@�E� m�t��~��Z�}�s3��;��Ma�t]���g�*0�����\"�b��N�w���4\\�U2�f��2�}�OS�;��D�W�\"���9�dl�7Gke��9�L̢�N�HI�c+���g!�w�J=�	��!r�a���^�(�X��ޣ5x���C�9�\Z���@��x+�N��Oj�=�w�I�_k��\\-=B����ݻ��\Z�S��U�l�;b)=�w�KTy��q�Ń��߻�&��۵=�,�ҕtL\"�����*���ʠY#6�NY쪀cA�A�Ą��>��3z}Ut�����`�̖��`W}��Ɏ��|\n������{���o�e��Ň��a�ߦ5��G����;tTJq�g��v�*<@\\%��[��a�{́�\Zl*��{���t/��}Lځ�2�S�.�}��ѷ����3D��>i���)/�q�%����-O��|`u���uȾ�\"��~F��c������W��ħ��5�gܱ}+�}���Eܣ���*k�VĽڽ{�}M|\r7����>����p�vߐ�_�ޡ5x�����(頠�o�f��daV\r�92��ǰM�fi-��ܣ}�R�ū�-8S�b��nT\ZQěȩ\"�$�V>��ҥ[F3�O��V:�g�k�N\\��E�2��\"K����|�>�͒��1��db]��Nd7�`����\'=7����_�;Uk�۵[*d����8�8Gk��G��1}��3�s\\���k\rn��=� ��߃O��#��z^O�<��s\"^�5��wU@�=�����>i���H������N�B��ǥo�FrwEp�\\�_�W�m2.��\"�Ngʭ���g���\"웮3<�(������ȳey5�\Z�����/��Y��&�Q��L�\n����D6��dƷF�L֟��o�#2^ON��ĝ��2�D��eG�J]�_�~��K�Ӊ&�|&y�3*~�1�m������u��c3J܁ae1�w?}\"�d�LY������q�����J�+yq�,��˸��|�=�\n��T.4/Q��Z�a��C�[�X示��<Y�a������(�#S5�`�7�xo���U�K���\\U��N��ێ��C�{�i\rnÇ����c�:\nke��@���C�_����N�9�x����i�ߴxp�5Mg�������2��\'�o�����ɵX������m�][G���o�����t�(�q6�XF��D�\"�������~̪�w���m��J7�l���-�/׆~vBf<�0?G�\'��ω|��n���UP{��Ex���o�75�������{w��O�����g������[�F�W����}G\0f�0�8��P�d��4�*��Q�X�����j�G9�&Ln��w��@7���Z|��a��q�\"�T��\"WȘW?��8S���E�ae������5��v��×��\'OƓU�Iu��Fb��Z����{���\"o&�Ʒ���#�k��1��[<�.��1Ak��Ƚ���.i�Ԫ��k<}�������\0��������kw|x{�\'�62��O�Q�����Z�����N\'���/���}��dp�����\Z]w���sD~\\�ҟ+c*��bJ]@UI�i���^��U��M\n�>.}���殑��� �i���cHɬ��.\Z}�*�蒖Y<�k�e�_���)ıo_�5Zo����42yG~>�kɋ�L��El�xp=u��kR��1ޥ\Zh>b���ш�z��7	�:����8��̦ы���,r^QI��7i�s>8ХaNu�1\\�8���:�e������w��Wrs�����I���-�T\'ާΐ;e�\'��~�]R��]�j;m\\K���G�\Z�M:�0^rX���rӘ��Z�;y��\r�QO5��x��u�j�H��a�Li���E�vX���v[}��5�p)��1a�����S�kJ���8�@��wVK�i�=����ݻKk�bi����`5�Q󒞚<\rW{�s��5\Z\'<dd�[�sk.�S�&�I�\Zߓ���V;��u��_��Qg�_QE`����>(���9u�?��[k��_\'�����:��g���]���������d��\'���CFʦFLR{4F&b>qRQ�k4wmWN���nQ쥜�ST%\r��>�bH��P\Z���(��!����ؠ1��F�=��4�SU��6�x@�Et�h��5<��Շ�h7�3ޞuT0\n��Yl���lҹZ��n�rzE>��ڽk+R��[���\\ro�ʗTkc�=�A�VN%~[��X+�]\Z��n߁}�w�|�d������~9��+|�����UMEY����٧�S���G�Y��f}.<L�,��v��q�l�վ��xe1_��Z�sr���W�{4r��xqc�2_Y��مI5i]%�Q˯�de!���������ĺFx�f��>kf��E�%k�����>�p���^J�j�\\6����|Jz�pJ���X�F�p�!E�(���6�w�G�E��Ω{�52׋�y�$��q:�iD�_�w��\\��`�	8Wa�a��;d\\��j��;n��bI��خ��\"?��׾|��3E��d�+L�\0�/�4��W��)��8I>�*������[~�NKVt��\"��ZU�-�����i�Ҿ!K����ʓ�5\0��	_��[��/��kj�>8n����Tzɩ�5�j�Z��(rK�,�R�|���urrV-�ot �k�<av��F�hs��H;ɍ�r��8�Z�l�Iƴ:�1��,����N�9\"l�S�,�DL�Hu����iܤ?�v��0/?�1�ڕXe*����B�e|[aO}�؛��chgj���.e#~��_RI6$�^�se1YU��=�!��Z=~O�����\rUꠘJ�	?-�z�|^�����иI�kSq���W�د�]�r�Ʃ�LrR��\Z��-:S�nec���8��Q�\0l�,�Ӊ&�)ш�:9�0�&���yQO�^�^e��\Z����#���FL1�����v����|�9�����&O�ӊ\0秄���#���_Q�G,�y��T��Qtf�Yܥ��C����u�w�6�mǄ�d����C�����~�E/:�-�=�n��jR|�v��:D�@����Dl\'�°F�qS>���V�j�\"o�I2Ω��OV\0���k���j�ob�oZ0�ߐ��*b�������i��</�<V�>��W��s��j��fw\"�w��%\ro��o�)�o�TK�L|�X��M2\'��C�\"�JGQH�yk�g����C\"��\0Qi��{t���k���a\'��|UJ�`��En�ńJL�!Y�<x�ѥ�}����^�F)��[�?yYUR�3enTM�5\Z�g�:�s�+e��������Ii�*���:>L��*�ιN�/ğ`���&�|KuZg�e�8Y�ϩ�4�T��q���<�!Os\ZV�#�K{Es�)����^Q��%�PLت�`3*�|H�{�rx�̉2�h6w+G��8	�����:%R�wa��y��+�-��,�\'ruUY�w�z�9�fl���\0#���n��2N�?_W�t����R�b�ĺr}2�֚��\Z��A�}�Z�j����Þ_؀�<���h_�*�bX�T��NeU��(���Hg�Fs�S��.rF�u��%�^��1�Rw��_���@E�����q����`�F^��7�	iK];K�\'�_�ǜ��yĹ��Uq*��b��E�>��[�8��&�+�G~�&�������5n�5����i���k���j/K�U�Sk/q\"�˼Ys����p��)���UXm\"�^�#Y�r����׮��,�b���Y�lW�h�b~UN�$�O�)=,r�Es�9S��:x��w�����Rɓ��Eɇ�#�&�ۏ8`F���P�J�RU.9�^V�?�:V�+ľ:�?��vBί=��EuXb��^4�Q���0b���Nߌ�r5G����T^\'�ÿ��G�M��gu��x{N���#��Jt\0\0\0\0IEND�B`�','2017-07-14 06:48:10','2022-02-28 02:44:06',1,'JfU8n3LCjbDMIqaNX8hvqtLWpaQUU2VOcTIl2AXAExBmSOje4nVpRszY0j9V','1,2,5'),
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
