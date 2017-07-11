# Host: localhost  (Version 5.5.39)
# Date: 2017-06-28 08:51:24
# Generator: MySQL-Front 6.0  (Build 2.20)


#
# Structure for table "dadores_de_sangre"
#

DROP TABLE IF EXISTS `dadores_de_sangre`;
CREATE TABLE `dadores_de_sangre` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `idDador` varchar(50) DEFAULT NULL,
  `CPF` varchar(50) NOT NULL DEFAULT '',
  `senha` varchar(30) NOT NULL DEFAULT '123456',
  `name` varchar(150) NOT NULL DEFAULT '',
  `birthDate` varchar(50) DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `cellPhone` varchar(30) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `bloodType` varchar(20) DEFAULT 'Apositivo',
  `gender` varchar(1) DEFAULT '1',
  `height` varchar(5) DEFAULT '1.50',
  `weight` varchar(5) NOT NULL DEFAULT '65',
  `state` varchar(50) NOT NULL DEFAULT 'RJ',
  `city` varchar(50) NOT NULL DEFAULT 'Campos Dos Goytacazes',
  `se_dono` varchar(1) DEFAULT '0',
  `comentarios` varchar(255) DEFAULT 'Sem Comentarios',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Data for table "dadores_de_sangre"
#

INSERT INTO `dadores_de_sangre` VALUES (1,'meruvia@gmail.com','062131277-04','','Fernando Meruvia','1972-12-14','meruvia@gmail.com','22 997 0660 580','22 997 0660 580','Apositivo','m','72','76','estado','CamposDosGoytacazes','',''),(2,'sdf@sdfsd.com','455.455.454-54','','jOSE mejia','1977-12-12','sdf@sdfsd.com','sadad','','Onegativo','m','63','64','al','CamposDosGoytacazes','',''),(3,'jtalonzo','061.131.288.04','jose','Jose Tiago Alonzo','12/12/1978','meruvia@gmail.com','220997006600584','','Apositivo','1','1.89','100','RJ','Campos Dos Goytacazes','1','primera donacion');

#
# Structure for table "donacoes_sangre"
#

DROP TABLE IF EXISTS `donacoes_sangre`;
CREATE TABLE `donacoes_sangre` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `CPF` varchar(50) NOT NULL DEFAULT '',
  `idDador` varchar(50) DEFAULT NULL,
  `hora` varchar(15) NOT NULL DEFAULT '12:00',
  `dataDoacao` date DEFAULT NULL,
  `hospistal` varchar(100) DEFAULT NULL,
  `enderecoHospital` varchar(100) DEFAULT NULL,
  `comentarios` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Data for table "donacoes_sangre"
#

INSERT INTO `donacoes_sangre` VALUES (1,'062.131.277-04','','10:05','2017-06-27','Ferrera Machado','','primeira vez '),(2,'062.131.277-04','','11:00','2017-06-27','Ferrera Machado','','');

#
# Structure for table "hospitais"
#

DROP TABLE IF EXISTS `hospitais`;
CREATE TABLE `hospitais` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `estado` varchar(30) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "hospitais"
#


#
# Structure for table "membership_grouppermissions"
#

DROP TABLE IF EXISTS `membership_grouppermissions`;
CREATE TABLE `membership_grouppermissions` (
  `permissionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `groupID` int(11) DEFAULT NULL,
  `tableName` varchar(100) DEFAULT NULL,
  `allowInsert` tinyint(4) DEFAULT NULL,
  `allowView` tinyint(4) NOT NULL DEFAULT '0',
  `allowEdit` tinyint(4) NOT NULL DEFAULT '0',
  `allowDelete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`permissionID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

#
# Data for table "membership_grouppermissions"
#

INSERT INTO `membership_grouppermissions` VALUES (1,2,'dadores_de_sangre',1,3,3,3),(2,2,'donacoes_sangre',1,3,3,3),(3,2,'hospitais',1,3,3,3);

#
# Structure for table "membership_groups"
#

DROP TABLE IF EXISTS `membership_groups`;
CREATE TABLE `membership_groups` (
  `groupID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  `description` text,
  `allowSignup` tinyint(4) DEFAULT NULL,
  `needsApproval` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`groupID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

#
# Data for table "membership_groups"
#

INSERT INTO `membership_groups` VALUES (1,'anonymous','Anonymous group created automatically on 2017-06-27',0,0),(2,'Admins','Admin group created automatically on 2017-06-27',0,1);

#
# Structure for table "membership_userpermissions"
#

DROP TABLE IF EXISTS `membership_userpermissions`;
CREATE TABLE `membership_userpermissions` (
  `permissionID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `memberID` varchar(20) NOT NULL,
  `tableName` varchar(100) DEFAULT NULL,
  `allowInsert` tinyint(4) DEFAULT NULL,
  `allowView` tinyint(4) NOT NULL DEFAULT '0',
  `allowEdit` tinyint(4) NOT NULL DEFAULT '0',
  `allowDelete` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`permissionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "membership_userpermissions"
#


#
# Structure for table "membership_userrecords"
#

DROP TABLE IF EXISTS `membership_userrecords`;
CREATE TABLE `membership_userrecords` (
  `recID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tableName` varchar(100) DEFAULT NULL,
  `pkValue` varchar(255) DEFAULT NULL,
  `memberID` varchar(20) DEFAULT NULL,
  `dateAdded` bigint(20) unsigned DEFAULT NULL,
  `dateUpdated` bigint(20) unsigned DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`recID`),
  UNIQUE KEY `tableName_pkValue` (`tableName`,`pkValue`),
  KEY `pkValue` (`pkValue`),
  KEY `tableName` (`tableName`),
  KEY `memberID` (`memberID`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "membership_userrecords"
#


#
# Structure for table "membership_users"
#

DROP TABLE IF EXISTS `membership_users`;
CREATE TABLE `membership_users` (
  `memberID` varchar(20) NOT NULL,
  `passMD5` varchar(40) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `signupDate` date DEFAULT NULL,
  `groupID` int(10) unsigned DEFAULT NULL,
  `isBanned` tinyint(4) DEFAULT NULL,
  `isApproved` tinyint(4) DEFAULT NULL,
  `custom1` text,
  `custom2` text,
  `custom3` text,
  `custom4` text,
  `comments` text,
  `pass_reset_key` varchar(100) DEFAULT NULL,
  `pass_reset_expiry` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`memberID`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "membership_users"
#

INSERT INTO `membership_users` VALUES ('admin','827ccb0eea8a706c4c34a16891f84e7b','meruvia@gmail.com','2017-06-27',2,0,1,NULL,NULL,NULL,NULL,'Admin member created automatically on 2017-06-27',NULL,NULL),('guest',NULL,NULL,'2017-06-27',1,0,1,NULL,NULL,NULL,NULL,'Anonymous member created automatically on 2017-06-27',NULL,NULL);
