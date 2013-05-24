CREATE DATABASE  IF NOT EXISTS `democranet` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `democranet`;
-- MySQL dump 10.13  Distrib 5.5.24, for osx10.5 (i386)
--
-- Host: localhost    Database: democranet
-- ------------------------------------------------------
-- Server version	5.1.49

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `position_id` int(11) NOT NULL,
  PRIMARY KEY (`action_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;
/*!40000 ALTER TABLE `actions` DISABLE KEYS */;
INSERT INTO `actions` VALUES (5,'Implement a carbon fee and dividend law','A carbon fee is based on the amount of carbon in a fossil fuel. Fossil fuels such as oil, gas and coal contain carbon. When burned they release the potent green house gas, carbon dioxide (CO2), into the atmosphere.  The fee is based on the tons of carbon dioxide the fuel would generate, and it would be collected at the point of entry -- well, mine or port. The fee would start out low -- $15 per ton -- and gradually increase $10-$15 each year. The amount would be determined by Congress.\r\n\r\nA dividend is defined as a quantity of revenue to be divided.  Ideally, 100% of the total carbon fees collected are divided up and given back to all citizens equally. This dividend helps citizens pay the increased costs associated with the carbon fee while our nation transitions to a clean energy economy. Realistically, it will have to be less than 100% to cover the cost of collecting and distributing the tax.\r\n\r\nThis legislation will put us on the path of a sustainable climate by reducing our greenhouse gas emissions and transitioning us to a clean energy economy. Because the fee (and the price of fossil fuel) goes up predictably over time, it sends a clear price signal to begin using fossil fuels more efficiently or replace them with green energy.  Investment flows to green technologies and the rising cost of fossil fuels increases the demand for these products, making them even less expensive as they reach mass production.  This clear, easy-to-understand price signal (increasing fossil fuel costs and decreasing green technology costs) drive the transition to a green economy.  This transition will reduce greenhouse gases stabilizing, our climate and the health of our oceans.','','',35),(6,'Extend the Production Tax Credit for 10 years','The PTC is federal subsidy for wind power that currently gives 2.2 cents/kWh produced by a wind farm in the form of a tax credit. The subsidy was originally passed in 1992, expired in 1999, and has been renewed several times since then. Each time the PTC was set to expire and there was uncertainty about its renewal, investment in wind energy dropped significantly, leading to a boom-bust cycle over the past 12 years that has been extremely harmful to the wind industry. The PTC is a relatively inexpensive, efficient subsidy that has been extremely effective at spurring investment in wind energy and reducing the cost of energy from wind power by 90% since the early 80\'s. Because the subsidy is paid only after the project is operating, there has been very little waste.','2013','',40);
/*!40000 ALTER TABLE `actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidates` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `citizen_id` int(11) DEFAULT NULL,
  `election_id` int(11) DEFAULT NULL,
  `party` varchar(100) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `summary` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`candidate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates`
--

LOCK TABLES `candidates` WRITE;
/*!40000 ALTER TABLE `candidates` DISABLE KEYS */;
INSERT INTO `candidates` VALUES (5,16,6,'Independent','http://www.brendanftaylor.com','Brendan is a cool guy.');
/*!40000 ALTER TABLE `candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Environment'),(2,'Energy'),(3,'Health'),(4,'Foreign Policy'),(5,'Legislature'),(6,'Personal Freedom'),(7,'Civil Rights'),(8,'Government');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `citizens`
--

DROP TABLE IF EXISTS `citizens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `citizens` (
  `citizen_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `postal_code` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`citizen_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citizens`
--

LOCK TABLES `citizens` WRITE;
/*!40000 ALTER TABLE `citizens` DISABLE KEYS */;
INSERT INTO `citizens` VALUES (16,'115ecee442bee9ef046dc1c2e79827663be9be95','bftrock@gmail.com','Brendan Taylor',1969,1,237,'05403'),(17,'78d958bddf77a4f71eca2333920e95e255c3e83f','monicataylor1026@gmail.com','Monica Taylor',NULL,2,237,'05403');
/*!40000 ALTER TABLE `citizens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(1) NOT NULL,
  `type_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` varchar(1000) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=251 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'(Unspecified)'),(2,'AFGHANISTAN'),(3,'ALBANIA'),(4,'ALGERIA'),(5,'AMERICAN SAMOA'),(6,'ANDORRA'),(7,'ANGOLA'),(8,'ANGUILLA'),(9,'ANTARCTICA'),(10,'ANTIGUA AND BARBUDA'),(11,'ARGENTINA'),(12,'ARMENIA'),(13,'ARUBA'),(14,'AUSTRALIA'),(15,'AUSTRIA'),(16,'AZERBAIJAN'),(17,'Ã…LAND ISLANDS'),(18,'BAHAMAS'),(19,'BAHRAIN'),(20,'BANGLADESH'),(21,'BARBADOS'),(22,'BELARUS'),(23,'BELGIUM'),(24,'BELIZE'),(25,'BENIN'),(26,'BERMUDA'),(27,'BHUTAN'),(28,'BOLIVIA, PLURINATIONAL STATE OF'),(29,'BONAIRE, SINT EUSTATIUS AND SABA'),(30,'BOSNIA AND HERZEGOVINA'),(31,'BOTSWANA'),(32,'BOUVET ISLAND'),(33,'BRAZIL'),(34,'BRITISH INDIAN OCEAN TERRITORY'),(35,'BRUNEI DARUSSALAM'),(36,'BULGARIA'),(37,'BURKINA FASO'),(38,'BURUNDI'),(39,'CAMBODIA'),(40,'CAMEROON'),(41,'CANADA'),(42,'CAPE VERDE'),(43,'CAYMAN ISLANDS'),(44,'CÃ”TE D\'IVOIRE'),(45,'CENTRAL AFRICAN REPUBLIC'),(46,'CHAD'),(47,'CHILE'),(48,'CHINA'),(49,'CHRISTMAS ISLAND'),(50,'COCOS (KEELING) ISLANDS'),(51,'COLOMBIA'),(52,'COMOROS'),(53,'CONGO'),(54,'CONGO, THE DEMOCRATIC REPUBLIC OF THE'),(55,'COOK ISLANDS'),(56,'COSTA RICA'),(57,'CROATIA'),(58,'CUBA'),(59,'CURAÃ‡AO'),(60,'CYPRUS'),(61,'CZECH REPUBLIC'),(62,'DENMARK'),(63,'DJIBOUTI'),(64,'DOMINICA'),(65,'DOMINICAN REPUBLIC'),(66,'ECUADOR'),(67,'EGYPT'),(68,'EL SALVADOR'),(69,'EQUATORIAL GUINEA'),(70,'ERITREA'),(71,'ESTONIA'),(72,'ETHIOPIA'),(73,'FALKLAND ISLANDS (MALVINAS)'),(74,'FAROE ISLANDS'),(75,'FIJI'),(76,'FINLAND'),(77,'FRANCE'),(78,'FRENCH GUIANA'),(79,'FRENCH POLYNESIA'),(80,'FRENCH SOUTHERN TERRITORIES'),(81,'GABON'),(82,'GAMBIA'),(83,'GEORGIA'),(84,'GERMANY'),(85,'GHANA'),(86,'GIBRALTAR'),(87,'GREECE'),(88,'GREENLAND'),(89,'GRENADA'),(90,'GUADELOUPE'),(91,'GUAM'),(92,'GUATEMALA'),(93,'GUERNSEY'),(94,'GUINEA'),(95,'GUINEA-BISSAU'),(96,'GUYANA'),(97,'HAITI'),(98,'HEARD ISLAND AND MCDONALD ISLANDS'),(99,'HOLY SEE (VATICAN CITY STATE)'),(100,'HONDURAS'),(101,'HONG KONG'),(102,'HUNGARY'),(103,'ICELAND'),(104,'INDIA'),(105,'INDONESIA'),(106,'IRAN, ISLAMIC REPUBLIC OF'),(107,'IRAQ'),(108,'IRELAND'),(109,'ISLE OF MAN'),(110,'ISRAEL'),(111,'ITALY'),(112,'JAMAICA'),(113,'JAPAN'),(114,'JERSEY'),(115,'JORDAN'),(116,'KAZAKHSTAN'),(117,'KENYA'),(118,'KIRIBATI'),(119,'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF'),(120,'KOREA, REPUBLIC OF'),(121,'KUWAIT'),(122,'KYRGYZSTAN'),(123,'LAO PEOPLE\'S DEMOCRATIC REPUBLIC'),(124,'LATVIA'),(125,'LEBANON'),(126,'LESOTHO'),(127,'LIBERIA'),(128,'LIBYA'),(129,'LIECHTENSTEIN'),(130,'LITHUANIA'),(131,'LUXEMBOURG'),(132,'MACAO'),(133,'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF'),(134,'MADAGASCAR'),(135,'MALAWI'),(136,'MALAYSIA'),(137,'MALDIVES'),(138,'MALI'),(139,'MALTA'),(140,'MARSHALL ISLANDS'),(141,'MARTINIQUE'),(142,'MAURITANIA'),(143,'MAURITIUS'),(144,'MAYOTTE'),(145,'MEXICO'),(146,'MICRONESIA, FEDERATED STATES OF'),(147,'MOLDOVA, REPUBLIC OF'),(148,'MONACO'),(149,'MONGOLIA'),(150,'MONTENEGRO'),(151,'MONTSERRAT'),(152,'MOROCCO'),(153,'MOZAMBIQUE'),(154,'MYANMAR'),(155,'NAMIBIA'),(156,'NAURU'),(157,'NEPAL'),(158,'NETHERLANDS'),(159,'NEW CALEDONIA'),(160,'NEW ZEALAND'),(161,'NICARAGUA'),(162,'NIGER'),(163,'NIGERIA'),(164,'NIUE'),(165,'NORFOLK ISLAND'),(166,'NORTHERN MARIANA ISLANDS'),(167,'NORWAY'),(168,'OMAN'),(169,'PAKISTAN'),(170,'PALAU'),(171,'PALESTINIAN TERRITORY, OCCUPIED'),(172,'PANAMA'),(173,'PAPUA NEW GUINEA'),(174,'PARAGUAY'),(175,'PERU'),(176,'PHILIPPINES'),(177,'PITCAIRN'),(178,'POLAND'),(179,'PORTUGAL'),(180,'PUERTO RICO'),(181,'QATAR'),(182,'RÃ‰UNION'),(183,'ROMANIA'),(184,'RUSSIAN FEDERATION'),(185,'RWANDA'),(186,'SAINT BARTHÃ‰LEMY'),(187,'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA'),(188,'SAINT KITTS AND NEVIS'),(189,'SAINT LUCIA'),(190,'SAINT MARTIN (FRENCH PART)'),(191,'SAINT PIERRE AND MIQUELON'),(192,'SAINT VINCENT AND THE GRENADINES'),(193,'SAMOA'),(194,'SAN MARINO'),(195,'SAO TOME AND PRINCIPE'),(196,'SAUDI ARABIA'),(197,'SENEGAL'),(198,'SERBIA'),(199,'SEYCHELLES'),(200,'SIERRA LEONE'),(201,'SINGAPORE'),(202,'SINT MAARTEN (DUTCH PART)'),(203,'SLOVAKIA'),(204,'SLOVENIA'),(205,'SOLOMON ISLANDS'),(206,'SOMALIA'),(207,'SOUTH AFRICA'),(208,'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS'),(209,'SOUTH SUDAN'),(210,'SPAIN'),(211,'SRI LANKA'),(212,'SUDAN'),(213,'SURINAME'),(214,'SVALBARD AND JAN MAYEN'),(215,'SWAZILAND'),(216,'SWEDEN'),(217,'SWITZERLAND'),(218,'SYRIAN ARAB REPUBLIC'),(219,'TAIWAN, PROVINCE OF CHINA'),(220,'TAJIKISTAN'),(221,'TANZANIA, UNITED REPUBLIC OF'),(222,'THAILAND'),(223,'TIMOR-LESTE'),(224,'TOGO'),(225,'TOKELAU'),(226,'TONGA'),(227,'TRINIDAD AND TOBAGO'),(228,'TUNISIA'),(229,'TURKEY'),(230,'TURKMENISTAN'),(231,'TURKS AND CAICOS ISLANDS'),(232,'TUVALU'),(233,'UGANDA'),(234,'UKRAINE'),(235,'UNITED ARAB EMIRATES'),(236,'UNITED KINGDOM'),(237,'UNITED STATES'),(238,'UNITED STATES MINOR OUTLYING ISLANDS'),(239,'URUGUAY'),(240,'UZBEKISTAN'),(241,'VANUATU'),(242,'VENEZUELA, BOLIVARIAN REPUBLIC OF'),(243,'VIET NAM'),(244,'VIRGIN ISLANDS, BRITISH'),(245,'VIRGIN ISLANDS, U.S.'),(246,'WALLIS AND FUTUNA'),(247,'WESTERN SAHARA'),(248,'YEMEN'),(249,'ZAMBIA'),(250,'ZIMBABWE');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `debug`
--

DROP TABLE IF EXISTS `debug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `msg` varchar(5000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `debug`
--

LOCK TABLES `debug` WRITE;
/*!40000 ALTER TABLE `debug` DISABLE KEYS */;
/*!40000 ALTER TABLE `debug` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `elections`
--

DROP TABLE IF EXISTS `elections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `elections` (
  `election_id` int(11) NOT NULL AUTO_INCREMENT,
  `office_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`election_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `elections`
--

LOCK TABLES `elections` WRITE;
/*!40000 ALTER TABLE `elections` DISABLE KEYS */;
INSERT INTO `elections` VALUES (6,6,'2016-11-01');
/*!40000 ALTER TABLE `elections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follows` (
  `type` char(1) NOT NULL DEFAULT '',
  `type_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  PRIMARY KEY (`type`,`type_id`,`citizen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
INSERT INTO `follows` VALUES ('a',5,16),('c',5,16),('c',5,17),('e',6,16),('e',6,17),('i',1,16),('i',2,16),('i',2,17),('i',6,16),('i',7,16),('o',6,16),('o',6,17),('p',35,16),('p',36,16),('p',36,17),('p',37,16),('p',37,17),('p',38,16),('p',38,17),('p',39,16),('p',40,16);
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issue_category`
--

DROP TABLE IF EXISTS `issue_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issue_category` (
  `issue_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`issue_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issue_category`
--

LOCK TABLES `issue_category` WRITE;
/*!40000 ALTER TABLE `issue_category` DISABLE KEYS */;
INSERT INTO `issue_category` VALUES (1,1),(2,3),(2,6),(2,7),(3,1),(3,2),(4,8),(5,3),(5,6),(5,7),(6,1),(6,2),(7,2);
/*!40000 ALTER TABLE `issue_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL,
  `version` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(3000) NOT NULL,
  `citizen_id` int(11) DEFAULT NULL,
  `ts` datetime DEFAULT NULL,
  PRIMARY KEY (`issue_id`,`version`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
INSERT INTO `issues` VALUES (1,1,'Global Warming','Global warming refers to the rising average temperature of Earth\'s atmosphere and oceans, which started to increase in the late 19th century and is projected to keep going up. Since the early 20th century, Earth\'s average surface temperature has increased by about 0.8 Â°C (1.4 Â°F), with about two thirds of the increase occurring since 1980. Warming of the climate system is unequivocal, and scientists are more than 90% certain that most of it is caused by increasing concentrations of greenhouse gases produced by human activities such as deforestation and burning fossil fuels. These findings are recognized by the national science academies of all the major industrialized nations.\r\n\r\nClimate model projections are summarized in the 2007 Fourth Assessment Report (AR4) by the Intergovernmental Panel on Climate Change (IPCC). They indicate that during the 21st century the global surface temperature is likely to rise a further 1.1 to 2.9 Â°C (2 to 5.2 Â°F) for their lowest emissions scenario and 2.4 to 6.4 Â°C (4.3 to 11.5 Â°F) for their highest. The ranges of these estimates arise from the use of models with differing sensitivity to greenhouse gas concentrations.\r\n\r\nAn increase in global temperature will cause sea levels to rise and will change the amount and pattern of precipitation, and a probable expansion of subtropical deserts. Warming is expected to be strongest in the Arctic and would be associated with continuing retreat of glaciers, permafrost and sea ice. Other likely effects of the warming include more frequent occurrence of extreme-weather events including heat waves, droughts and heavy rainfall, species extinctions due to shifting temperature regimes, and changes in crop yields. Warming and related changes will vary from region to region around the globe, with projections being more robust in some areas than others. If global mean temperature increases to 4 Â°C above preindustrial levels, the limits for human adaptation are likely to be exceeded in many parts of the world, while the limits for adaptation for natural systems would largely be exceeded throughout the world. Hence, the ecosystem services upon which human livelihoods depend would not be preserved.\r\n\r\nGiven the substantial impacts to the natural world and human society that are likely to occur this century, the question of this political issue is, what roles should federal governments and the United Nations play in order to avoid or reduce the devastating impacts of global warming?',16,'2013-05-15 12:36:01'),(1,2,'Global Warming','Global warming refers to the rising average temperature of Earth\'s atmosphere and oceans, which started to increase in the late 19th century and is projected to keep going up.[2] Since the early 20th century, Earth\'s average surface temperature has increased by about 0.8 Â°C (1.4 Â°F), with about two thirds of the increase occurring since 1980. Warming of the climate system is unequivocal, and scientists are more than 90% certain that most of it is caused by increasing concentrations of greenhouse gases produced by human activities such as deforestation and burning fossil fuels. These findings are recognized by the national science academies of all the major industrialized nations.\r\n\r\nClimate model projections are summarized in the 2007 Fourth Assessment Report (AR4) by the Intergovernmental Panel on Climate Change (IPCC). They indicate that during the 21st century the global surface temperature is likely to rise a further 1.1 to 2.9 Â°C (2 to 5.2 Â°F) for their lowest emissions scenario and 2.4 to 6.4 Â°C (4.3 to 11.5 Â°F) for their highest. The ranges of these estimates arise from the use of models with differing sensitivity to greenhouse gas concentrations.[3]\r\n\r\nAn increase in global temperature will cause sea levels to rise and will change the amount and pattern of precipitation, and a probable expansion of subtropical deserts. Warming is expected to be strongest in the Arctic and would be associated with continuing retreat of glaciers, permafrost and sea ice. Other likely effects of the warming include more frequent occurrence of extreme-weather events including heat waves, droughts and heavy rainfall, species extinctions due to shifting temperature regimes, and changes in crop yields. Warming and related changes will vary from region to region around the globe, with projections being more robust in some areas than others. If global mean temperature increases to 4 Â°C above preindustrial levels, the limits for human adaptation are likely to be exceeded in many parts of the world, while the limits for adaptation for natural systems would largely be exceeded throughout the world. Hence, the ecosystem services upon which human livelihoods depend would not be preserved.\r\n\r\nGiven the substantial impacts to the natural world and human society that are likely to occur this century, the question of this political issue is, what roles should federal governments and the United Nations play in order to avoid or reduce the devastating impacts of global warming?',16,'2013-05-15 12:52:43'),(1,3,'Global Warming','Global warming refers to the rising average temperature of Earth\'s atmosphere and oceans, which started to increase in the late 19th century and is projected to keep going up.[2] Since the early 20th century, Earth\'s average surface temperature has increased by about 0.8 Â°C (1.4 Â°F), with about two thirds of the increase occurring since 1980. Warming of the climate system is unequivocal, and scientists are more than 90% certain that most of it is caused by increasing concentrations of greenhouse gases produced by human activities such as deforestation and burning fossil fuels. These findings are recognized by the national science academies of all the major industrialized nations.\r\n\r\nClimate model projections are summarized in the 2007 <a href=\"http://en.wikipedia.org/wiki/IPCC_Fourth_Assessment_Report\">Fourth Assessment Report (AR4)</a> by the Intergovernmental Panel on Climate Change (IPCC). They indicate that during the 21st century the global surface temperature is likely to rise a further 1.1 to 2.9 Â°C (2 to 5.2 Â°F) for their lowest emissions scenario and 2.4 to 6.4 Â°C (4.3 to 11.5 Â°F) for their highest. The ranges of these estimates arise from the use of models with differing sensitivity to greenhouse gas concentrations.[3]\r\n\r\nAn increase in global temperature will cause sea levels to rise and will change the amount and pattern of precipitation, and a probable expansion of subtropical deserts. Warming is expected to be strongest in the Arctic and would be associated with continuing retreat of glaciers, permafrost and sea ice. Other likely effects of the warming include more frequent occurrence of extreme-weather events including heat waves, droughts and heavy rainfall, species extinctions due to shifting temperature regimes, and changes in crop yields. Warming and related changes will vary from region to region around the globe, with projections being more robust in some areas than others. If global mean temperature increases to 4 Â°C above preindustrial levels, the limits for human adaptation are likely to be exceeded in many parts of the world, while the limits for adaptation for natural systems would largely be exceeded throughout the world. Hence, the ecosystem services upon which human livelihoods depend would not be preserved.\r\n\r\nGiven the substantial impacts to the natural world and human society that are likely to occur this century, the question of this political issue is, what roles should federal governments and the United Nations play in order to avoid or reduce the devastating impacts of global warming?',16,'2013-05-15 12:54:53'),(1,4,'Global Warming','Global warming refers to the rising average temperature of Earth\'s atmosphere and oceans, which started to increase in the late 19th century and is projected to keep going up.[2] Since the early 20th century, Earth\'s average surface temperature has increased by about 0.8 Â°C (1.4 Â°F), with about two thirds of the increase occurring since 1980. Warming of the climate system is unequivocal, and scientists are more than 90% certain that most of it is caused by increasing concentrations of greenhouse gases produced by human activities such as deforestation and burning fossil fuels. These findings are recognized by the national science academies of all the major industrialized nations.\r\n\r\nClimate model projections are summarized in the 2007 Fourth Assessment Report (AR4)[4] by the Intergovernmental Panel on Climate Change (IPCC). They indicate that during the 21st century the global surface temperature is likely to rise a further 1.1 to 2.9 Â°C (2 to 5.2 Â°F) for their lowest emissions scenario and 2.4 to 6.4 Â°C (4.3 to 11.5 Â°F) for their highest. The ranges of these estimates arise from the use of models with differing sensitivity to greenhouse gas concentrations.\r\n\r\nAn increase in global temperature will cause sea levels to rise and will change the amount and pattern of precipitation, and a probable expansion of subtropical deserts.[5] Warming is expected to be strongest in the Arctic and would be associated with continuing retreat of glaciers, permafrost and sea ice. Other likely effects of the warming include more frequent occurrence of extreme-weather events including heat waves, droughts and heavy rainfall, species extinctions due to shifting temperature regimes, and changes in crop yields. Warming and related changes will vary from region to region around the globe, with projections being more robust in some areas than others. If global mean temperature increases to 4 Â°C above preindustrial levels, the limits for human adaptation are likely to be exceeded in many parts of the world, while the limits for adaptation for natural systems would largely be exceeded throughout the world. Hence, the ecosystem services upon which human livelihoods depend would not be preserved.\r\n\r\nGiven the substantial impacts to the natural world and human society that are likely to occur this century, the question of this political issue is, what roles should federal governments and the United Nations play in order to avoid or reduce the devastating impacts of global warming?',16,'2013-05-15 13:15:16'),(2,1,'The Legality and Morality of Abortion','Abortion is defined as the termination of pregnancy by the removal or expulsion from the uterus of a fetus or embryo prior to viability. An abortion can occur spontaneously, in which case it is usually called a miscarriage, or it can be purposely induced. The term abortion most commonly refers to the induced abortion of a human pregnancy.\r\n\r\nAbortion, when induced in the developed world in accordance with local law, is among the safest procedures in medicine. However, unsafe abortions (those performed by persons without proper training or outside of a medical environment) result in approximately 70 thousand maternal deaths and 5 million disabilities per year globally. An estimated 42 million abortions are performed globally each year, with 20 million of those performed unsafely. The incidence of abortion has declined worldwide as access to family planning education and contraceptive services has increased. Forty percent of the world\'s women have access to induced abortions (within gestational limits).\r\n\r\nThe abortion debate refers to discussion and controversy surrounding the moral and legal status of abortion. The two main groups involved in the abortion debate are the self-described \"pro-choice\" movement (emphasizing the right of women to choose whether or not they wish to bring a fetus to term) and the \"pro-life\" movement (emphasizing the right of the unborn child to live). Each movement has, with varying results, sought to influence public opinion and to attain legal support for its position. Both of these are considered loaded terms in general media where terms such as \"abortion rights\" or \"anti abortion\" are preferred. In Canada, for example, abortion is available on demand, while in Ireland abortions are illegal. In some cases, the abortion debate has led to the use of violence.',16,'2013-05-15 15:02:13'),(2,2,'The Legality and Morality of Abortion','Abortion is defined as the termination of pregnancy by the removal or expulsion from the uterus of a fetus or embryo prior to viability. An abortion can occur spontaneously, in which case it is usually called a miscarriage, or it can be purposely induced. The term abortion most commonly refers to the induced abortion of a human pregnancy.\r\n\r\nAbortion, when induced in the developed world in accordance with local law, is among the safest procedures in medicine.[2] However, unsafe abortions (those performed by persons without proper training or outside of a medical environment) result in approximately 70 thousand maternal deaths and 5 million temporary or permanent disabilities per year. An estimated 42 million abortions are performed globally each year, with 20 million of those performed unsafely. The incidence of abortion has declined worldwide as access to family planning education and contraceptive services has increased.[3] Forty percent of the world\'s women have access to induced abortions (within gestational limits).[4]\r\n\r\nThis issue refers to the legal and moral status of abortion. The two main groups are the \"abortion rights\" group, who emphasize the right of women to choose whether or not they wish to bring a fetus to term and support policies that make abortion legal and accessible, and the \"anti abortion\" group, who emphasize the right of the unborn child to live and support policies that either restrict or prohibit access to abortion.\r\n',16,'2013-05-15 15:38:01'),(3,1,'Tar Sands Oil Production','Oil sands, tar sands or, more technically, bituminous sands, are a type of unconventional petroleum deposit. The oil sands are loose sand or partially consolidated sandstone containing naturally occurring mixtures of sand, clay, and water, saturated with a dense and extremely viscous form of petroleum technically referred to as bitumen (or colloquially tar due to its similar appearance, odour and colour). Natural bitumen deposits are reported in many countries, but in particular are found in extremely large quantities in Canada. Other large reserves are located in Kazakhstan and Russia. The estimated deposits in the United States could be as much as 2 trillion barrels. The estimates include deposits that have not yet been discovered; proven reserves of bitumen contain approximately 100 billion barrels. Total natural bitumen reserves are estimated at 249.67 billion barrels globally, of which 176.8 billion barrels, or 70.8%, are in Canada. Oil sands reserves have only recently been considered to be part of the world\'s oil reserves, as higher oil prices and new technology enable profitable extraction and processing. Oil produced from bitumen sands is often referred to as unconventional oil or crude bitumen, to distinguish it from liquid hydrocarbons produced from traditional oil wells.\r\n\r\nOil sands extraction can affect the land when the bitumen is initially mined: water by its requirement of large quantities of water during separation of the oil and sand, and the air due to the release of carbon dioxide and other emissions. Heavy metals such as vanadium, nickel, lead, cobalt, mercury, chromium, cadmium, arsenic, selenium, copper, manganese, iron and zinc are naturally present in oil sands and may be concentrated by the extraction process. The environmental impact caused by oil sand extraction is frequently criticized by environmental groups such as Greenpeace, Climate Reality Project, 350.org, MoveOn.org, League of Conservation Voters, Patagonia, Sierra Club, and Energy Action Coalition. The European Union has indicated that it may vote to label oil sands oil as \"highly polluting\". Although oil sands exports to Europe are minimal, the issue has caused friction between the EU and Canada. According to the California-based Jacobs Consultancy, the European Union used inaccurate and incomplete data in assigning a high greenhouse gas rating to gasoline derived from Alberta\'s oilsands. Also, Iran, Saudi Arabia, Nigeria and Russia do not provide data on how much natural gas is released via flaring or venting in the oil extraction process. As a result of these, Jacobs report pointed out that extra carbon emissions from oil-sand crude are 12 percent higher than from regular crude, although it was assigned a GHG rating 22% above the conventional benchmark by EU.',16,'2013-05-15 16:23:35'),(3,2,'Tar Sands Oil Production','Oil sands, tar sands or, more technically, bituminous sands, are a type of unconventional petroleum deposit. The oil sands are loose sand or partially consolidated sandstone containing naturally occurring mixtures of sand, clay, and water, saturated with a dense and extremely viscous form of petroleum technically referred to as bitumen (or colloquially tar due to its similar appearance, odour and colour). Natural bitumen deposits are reported in many countries, but in particular are found in extremely large quantities in Canada. Other large reserves are located in Kazakhstan and Russia. The estimated deposits in the United States could be as much as 2 trillion barrels. The estimates include deposits that have not yet been discovered; proven reserves of bitumen contain approximately 100 billion barrels. Total natural bitumen reserves are estimated at 249.67 billion barrels globally, of which 176.8 billion barrels, or 70.8%, are in Canada. Oil sands reserves have only recently been considered to be part of the world\'s oil reserves, as higher oil prices and new technology enable profitable extraction and processing. Oil produced from bitumen sands is often referred to as unconventional oil or crude bitumen, to distinguish it from liquid hydrocarbons produced from traditional oil wells.\r\n\r\nOil sands extraction can affect the land when the bitumen is initially mined: water by its requirement of large quantities of water during separation of the oil and sand, and the air due to the release of carbon dioxide and other emissions. Heavy metals such as vanadium, nickel, lead, cobalt, mercury, chromium, cadmium, arsenic, selenium, copper, manganese, iron and zinc are naturally present in oil sands and may be concentrated by the extraction process. The environmental impact caused by oil sand extraction is frequently criticized by environmental groups such as Greenpeace, Climate Reality Project, 350.org, MoveOn.org, League of Conservation Voters, Patagonia, Sierra Club, and Energy Action Coalition. The European Union has indicated that it may vote to label oil sands oil as \"highly polluting\". Although oil sands exports to Europe are minimal, the issue has caused friction between the EU and Canada. According to the California-based Jacobs Consultancy, the European Union used inaccurate and incomplete data in assigning a high greenhouse gas rating to gasoline derived from Alberta\'s oilsands. Also, Iran, Saudi Arabia, Nigeria and Russia do not provide data on how much natural gas is released via flaring or venting in the oil extraction process. As a result of these, Jacobs report pointed out that extra carbon emissions from oil-sand crude are 12 percent higher than from regular crude, although it was assigned a GHG rating 22% above the conventional benchmark by EU.',16,'2013-05-15 17:05:00'),(3,3,'Tar Sands Oil Production','Oil sands, tar sands or, more technically, bituminous sands, are a type of unconventional petroleum deposit. The oil sands are loose sand or partially consolidated sandstone containing naturally occurring mixtures of sand, clay, and water, saturated with a dense and extremely viscous form of petroleum technically referred to as bitumen (or colloquially tar due to its similar appearance, odour and colour). Natural bitumen deposits are reported in many countries, but in particular are found in extremely large quantities in Canada. Other large reserves are located in Kazakhstan and Russia. The estimated deposits in the United States could be as much as 2 trillion barrels. The estimates include deposits that have not yet been discovered; proven reserves of bitumen contain approximately 100 billion barrels. Total natural bitumen reserves are estimated at 249.67 billion barrels globally, of which 176.8 billion barrels, or 70.8%, are in Canada. Oil sands reserves have only recently been considered to be part of the world\'s oil reserves, as higher oil prices and new technology enable profitable extraction and processing. Oil produced from bitumen sands is often referred to as unconventional oil or crude bitumen, to distinguish it from liquid hydrocarbons produced from traditional oil wells.\r\n\r\nOil sands extraction can affect the land when the bitumen is initially mined: water by its requirement of large quantities of water during separation of the oil and sand, and the air due to the release of carbon dioxide and other emissions. Heavy metals such as vanadium, nickel, lead, cobalt, mercury, chromium, cadmium, arsenic, selenium, copper, manganese, iron and zinc are naturally present in oil sands and may be concentrated by the extraction process. The environmental impact caused by oil sand extraction is frequently criticized by environmental groups such as Greenpeace, Climate Reality Project, 350.org, MoveOn.org, League of Conservation Voters, Patagonia, Sierra Club, and Energy Action Coalition. The European Union has indicated that it may vote to label oil sands oil as \"highly polluting\". Although oil sands exports to Europe are minimal, the issue has caused friction between the EU and Canada. According to the California-based Jacobs Consultancy, the European Union used inaccurate and incomplete data in assigning a high greenhouse gas rating to gasoline derived from Alberta\'s oilsands. Also, Iran, Saudi Arabia, Nigeria and Russia do not provide data on how much natural gas is released via flaring or venting in the oil extraction process. As a result of these, Jacobs report pointed out that extra carbon emissions from oil-sand crude are 12 percent higher than from regular crude, although it was assigned a GHG rating 22% above the conventional benchmark by EU.',16,'2013-05-15 17:05:57'),(3,4,'Tar Sands Oil Production','Oil sands, tar sands or, more technically, bituminous sands, are a type of unconventional petroleum deposit. The oil sands are loose sand or partially consolidated sandstone containing naturally occurring mixtures of sand, clay, and water, saturated with a dense and extremely viscous form of petroleum technically referred to as bitumen (or colloquially tar due to its similar appearance, odour and colour). Natural bitumen deposits are reported in many countries, but in particular are found in extremely large quantities in Canada.[2] Other large reserves are located in Kazakhstan and Russia. The estimated deposits in the United States could be as much as 2 trillion barrels. The estimates include deposits that have not yet been discovered; proven reserves of bitumen contain approximately 100 billion barrels.[3] Total natural bitumen reserves are estimated at 249.67 billion barrels globally, of which 176.8 billion barrels, or 70.8%, are in Canada. Oil sands reserves have only recently been considered to be part of the world\'s oil reserves, as higher oil prices and new technology enable profitable extraction and processing. Oil produced from bitumen sands is often referred to as unconventional oil or crude bitumen, to distinguish it from liquid hydrocarbons produced from traditional oil wells.\r\n\r\nOil sands extraction can affect the land when the bitumen is initially mined: water by its requirement of large quantities of water during separation of the oil and sand, and the air due to the release of carbon dioxide and other emissions. Heavy metals such as vanadium, nickel, lead, cobalt, mercury, chromium, cadmium, arsenic, selenium, copper, manganese, iron and zinc are naturally present in oil sands and may be concentrated by the extraction process. The environmental impact caused by oil sand extraction is frequently criticized by environmental groups such as Greenpeace, Climate Reality Project, 350.org, MoveOn.org, League of Conservation Voters, Patagonia, Sierra Club, and Energy Action Coalition. The European Union has indicated that it may vote to label oil sands oil as \"highly polluting\". Although oil sands exports to Europe are minimal, the issue has caused friction between the EU and Canada. According to the California-based Jacobs Consultancy, the European Union used inaccurate and incomplete data in assigning a high greenhouse gas rating to gasoline derived from Alberta\'s oilsands. Also, Iran, Saudi Arabia, Nigeria and Russia do not provide data on how much natural gas is released via flaring or venting in the oil extraction process. As a result of these, Jacobs report pointed out that extra carbon emissions from oil-sand crude are 12 percent higher than from regular crude, although it was assigned a GHG rating 22% above the conventional benchmark by EU.',16,'2013-05-15 17:16:54'),(4,1,'Campaign Finance in the United States','Campaign finance in the United States is the financing of electoral campaigns at the federal, state, and local levels. At the federal level, campaign finance law is enacted by Congress and enforced by the Federal Election Commission (FEC), an independent federal agency. Although most campaign spending is privately financed, public financing is available for qualifying candidates for President of the United States during both the primaries and the general election. Eligibility requirements must be fulfilled to qualify for a government subsidy, and those that do accept government funding are usually subject to spending limits.\r\n\r\nRaces for non-federal offices are governed by state and local law. Over half the states allow some level of corporate and union contributions. Some states have limits on contributions from individuals that are lower than the national limits, while four states (Missouri, Oregon, Utah and Virginia) have no limits at all.',16,'2013-05-15 20:06:53'),(4,2,'Political Finance in the United States','Political finance covers all funds that are raised and spent for political purposes, including the financing of electoral campaigns at the federal, state, and local levels. At the federal level in the US, campaign finance law is enacted by Congress and enforced by the Federal Election Commission (FEC), an independent federal agency. Although most campaign spending is privately financed, public financing is available for qualifying candidates for President of the United States during both the primaries and the general election. Eligibility requirements must be fulfilled to qualify for a government subsidy, and those that do accept government funding are usually subject to spending limits.\r\n\r\nRaces for non-federal offices are governed by state and local law. Over half the states allow some level of corporate and union contributions. Some states have limits on contributions from individuals that are lower than the national limits, while four states (Missouri, Oregon, Utah and Virginia) have no limits at all.\r\n\r\nAll modern democracies operate a variety of permanent party organizations, e.g. the Democratic National Committee and the Republican National Committee in the U.S. or the Conservative Central Office and the Labour headquarters (\"John Smith House\", \"Millbank Tower\") in the U.K. The annual budgets of such organizations are part of political finance. In Europe the term \"party finance\" is frequently used and refers only to funds that are raised and spent in order to influence the outcome of some sort of party competition. Even a limited range of political purposes (campaign and party activity) indicates that the term \"campaign funds\" (used as subject heading in Library of Congress cataloguing) is too narrow to cover all funds that are deployed in the political process.',16,'2013-05-15 20:19:28'),(5,1,'Gun Politics','Gun politics addresses safety issues and ideologies related to firearms through criminal and noncriminal use. Gun politics deals with rules, regulations, and restrictions on the use, ownership, as well as distribution of firearms. Gun control laws and policy vary greatly around the world. Some countries, such as North Korea, China, the United Kingdom or Germany, have very strict limits on gun possession while others, such as Yemen and the USA, have relatively lenient limits.\r\n\r\nArguments for freedom of access to firearms include increased personal safety through deterrence and self-defense, the ability of the population to defend itself against a tyrannical government, and personal freedom. Arguments for regulation of firearms include decreased personal safety due to proliferation among criminals, increased suicide and homicide rates, and increased violence by men against women.\r\n\r\nRecent mass shootings such as the one at Sandy Hook Elementary School in Newtown, Connecticut in December 2012 have increased the level of debate and political activity around this issue. In April 2013, despite overwhelming public support for universal background checks, the US Senate failed to pass a bill would expand background checks for types of gun sales that currently do not require one.',16,'2013-05-15 20:50:32'),(5,2,'Gun Politics','Gun politics addresses safety issues and ideologies related to firearms through criminal and noncriminal use. Gun politics deals with rules, regulations, and restrictions on the use, ownership, as well as distribution of firearms. Gun control laws and policy vary greatly around the world. Some countries, such as North Korea, China, the United Kingdom or Germany, have very strict limits on gun possession while others, such as Yemen and the USA, have relatively lenient limits.\r\n\r\nArguments for freedom of access to firearms include increased personal safety through deterrence and self-defense, the ability of the population to defend itself against a tyrannical government, and personal freedom. Arguments for regulation of firearms include decreased personal safety due to proliferation among criminals, increased suicide and homicide rates, and increased violence by men against women.\r\n\r\nRecent mass shootings such as the one at Sandy Hook Elementary School in Newtown, Connecticut in December 2012 have increased the level of debate and political activity around this issue. In April 2013, despite overwhelming public support for universal background checks[2], the US Senate failed to pass a bill would expand background checks for types of gun sales that currently do not require one.[3]',16,'2013-05-15 20:58:43'),(6,1,'Keystone XL Pipeline','The Keystone Pipeline System is a pipeline system to transport petroleum products from Canada and the northern United States to the Gulf Coast of Texas. The products to be shipped include synthetic crude oil and diluted bitumen from the Western Canadian Sedimentary Basin (WCSB) in Alberta, Canada, and Bakken synthetic crude oil and light crude oil produced from the Williston Basin (Bakken) region in Montana and North Dakota. Two phases of the project are in operation, a third, from Oklahoma to the Texas Gulf coast, is under construction and the fourth is awaiting U.S. government approval as of mid-April 2013. Upon completion, the Keystone Pipeline System would consist of the completed 2,151-mile Keystone Pipeline (Phases I and II) and the proposed 1,661-mile Keystone Gulf Coast Expansion Project (Phases III and IV) . The controversial fourth phase, the Keystone XL Pipeline Project, would begin at the oil distribution hub in Hardisty, Alberta and extend 1,179 miles, to Steele City, Nebraska.\r\n\r\nThe operational Keystone Pipeline system currently has the capacity to deliver up to 590,000 barrels per day of Canadian crude oil into the Mid-West refining markets. In the summer of 2010 Phase 1 of the Keystone Pipeline was completed, delivering crude oil from Hardisty, Alberta to Steele City, Nebraska, and then east through Missouri to Wood River refineries and Patoka, Illinois. Phase 2 the Keystone-Cushing extension was completed in February 2011 with the pipeline from Steele City, Nebraska to storage and distribution facilities at Cushing, Oklahoma, a major crude oil marketing/refining and pipeline hub.\r\n\r\nThe Keystone XL proposal, which would be comprised of phases 3 and 4, faced lawsuits from oil refineries and criticism from environmentalists and some members of the United States Congress. In January 2012, President Barack Obama rejected the application amid protests about the pipeline\'s impact on Nebraska\'s environmentally sensitive Sand Hills region. TransCanada changed the original proposed route of Keystone XL and the new route was approved by Nebraska Governor Dave Heineman in January 2013. On March 22, 2012, Obama endorsed the building of its southern half that begins in Cushing, Okla.\r\n\r\nIn its Supplemental Environmental Impact Statement (SEIS) released for public scrutiny in March 2013, the United States Department of State, described a number of changes to the original proposals and stated \"there would be no significant impacts to most resources along the proposed Project route.\" In response to the Department of State\'s report which recommended neither acceptance nor rejection, the editor of the New York Times recommended that President Obama, who acknowledges climate change as one of humanity\'s \"most challenging issues\", should reject the project which \"even by the State Department\'s most cautious calculations â€” can only add to the problem.\"',16,'2013-05-15 21:02:38'),(6,2,'Keystone XL Pipeline','The Keystone Pipeline System is a pipeline system to transport petroleum products from Canada and the northern United States to the Gulf Coast of Texas. The products to be shipped include synthetic crude oil and diluted bitumen from the Western Canadian Sedimentary Basin (WCSB) in Alberta, Canada, and Bakken synthetic crude oil and light crude oil produced from the Williston Basin (Bakken) region in Montana and North Dakota.[2] Two phases of the project are in operation, a third, from Oklahoma to the Texas Gulf coast, is under construction and the fourth is awaiting U.S. government approval as of mid-April 2013. Upon completion, the Keystone Pipeline System would consist of the completed 2,151-mile Keystone Pipeline (Phases I and II) and the proposed 1,661-mile Keystone Gulf Coast Expansion Project (Phases III and IV) . The controversial fourth phase, the Keystone XL Pipeline Project, would begin at the oil distribution hub in Hardisty, Alberta and extend 1,179 miles, to Steele City, Nebraska.\r\n\r\nThe operational Keystone Pipeline system currently has the capacity to deliver up to 590,000 barrels per day of Canadian crude oil into the Mid-West refining markets. In the summer of 2010 Phase 1 of the Keystone Pipeline was completed, delivering crude oil from Hardisty, Alberta to Steele City, Nebraska, and then east through Missouri to Wood River refineries and Patoka, Illinois. Phase 2 the Keystone-Cushing extension was completed in February 2011 with the pipeline from Steele City, Nebraska to storage and distribution facilities at Cushing, Oklahoma, a major crude oil marketing/refining and pipeline hub.\r\n\r\nThe Keystone XL proposal, which would be comprised of phases 3 and 4, faced lawsuits from oil refineries and criticism from environmentalists and some members of the United States Congress. In January 2012, President Barack Obama rejected the application amid protests about the pipeline\'s impact on Nebraska\'s environmentally sensitive Sand Hills region. TransCanada changed the original proposed route of Keystone XL and the new route was approved by Nebraska Governor Dave Heineman in January 2013. On March 22, 2012, Obama endorsed the building of its southern half that begins in Cushing, Okla.\r\n\r\nIn its Supplemental Environmental Impact Statement (SEIS) released for public scrutiny in March 2013, the United States Department of State, described a number of changes to the original proposals and stated \"there would be no significant impacts to most resources along the proposed Project route.\"[3] In response to the Department of State\'s report which recommended neither acceptance nor rejection, the editor of the New York Times recommended that President Obama, who acknowledges climate change as one of humanity\'s \"most challenging issues\", should reject the project which \"even by the State Department\'s most cautious calculations â€” can only add to the problem.\"',16,'2013-05-15 21:32:08'),(7,1,'Wind Power','Wind power refers to the conversion of wind energy into electricity for use in the electric grid. The total amount of economically extractable power available from the wind is considerably more than present human power use from all sources. At the end of 2010, worldwide nameplate capacity of wind-powered generators was 197 gigawatts (GW). Wind power now has the capacity to generate 430 TWh annually, which is about 2.5% of worldwide electricity usage. Over the past five years the average annual growth in new installations has been 27.6 percent. Wind power market penetration is expected to reach 3.4% by 2013 and 8% by 2018. Several countries have already achieved relatively high levels of wind power penetration, such as 21% of stationary electricity production in Denmark, 18% in Portugal, 16% in Spain, 14% in Ireland and 9% in Germany in 2010. As of 2011, 83 countries around the world are using wind power on a commercial basis.\r\n\r\nA large wind farm may consist of several hundred individual wind turbines which are connected to the electric power transmission network. Offshore wind power can harness the better wind speeds that are available offshore compared to on land, so offshore wind power\'s contribution in terms of electricity supplied is higher. However, the cost of installing wind power offshore is higher than onshore. Although a variable source of power, the intermittency of wind seldom creates problems when using wind power up to 20% of total electricity demand. As the percentage of wind power increases, so does the need to use other methods to mitigate the effects of its intermittency, such as energy storage.\r\n\r\nThe construction of wind farms is sometimes opposed because of visual impacts, concerns about noise and the health effects of visual flicker from the shadows cast by the rotating blades, local environmental impacts due to road construction, and concerns about habitat conservation.\r\n\r\nWind power is plentiful, renewable, widely distributed, clean, produces no greenhouse gas emissions during operation, and uses little land. In operation, the overall cost per unit of energy produced is similar to the cost for new coal and natural gas installations. While fossil fuel-generated electricity is subject to the price volatility of the fuel source, the wind is virtually free.',16,'2013-05-15 21:48:35');
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `offices` (
  `office_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `office_holder` int(11) DEFAULT NULL,
  PRIMARY KEY (`office_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offices`
--

LOCK TABLES `offices` WRITE;
/*!40000 ALTER TABLE `offices` DISABLE KEYS */;
INSERT INTO `offices` VALUES (6,'President of the United States','The President of the United States of America is the head of state and head of government of the United States. The president leads the executive branch of the federal government and is the commander-in-chief of the United States Armed Forces. Article II of the U.S. Constitution vests the executive power of the United States in the president and charges him/her with the execution of federal law, alongside the responsibility of appointing federal executive, diplomatic, regulatory, and judicial officers, and concluding treaties with foreign powers, with the advice and consent of the Senate. The president is further empowered to grant federal pardons and reprieves, and to convene and adjourn either or both houses of Congress under extraordinary circumstances.',237,NULL);
/*!40000 ALTER TABLE `offices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) DEFAULT NULL,
  `justification` varchar(3000) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `citizen_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (35,'The US federal government should implement aggressive regulations to slow global warming','Insufficiently regulated capitalism is the root cause of the climate change crisis. Specifically, the current system has enabled certain costs to be externalized such that large profits are obtained while, for example, the environment is degraded and human health is significantly impacted. Further regulation of the industries that contribute most to CO2 emissions is a logical choice within the current political system.',1,16),(36,'Sexual emancipation and gender equality justify abortion in all cases','Women\'s freedoms are limited until they can have the right to abortion on demand and to walk away from parenthood like men can. Governments that ban abortion arguably burden women with certain duties that men (who, too, are responsible for the pregnancy) are not also held accountable to, therefore, creating a double standard. Margaret Sanger wrote: \"No woman can call herself free until she can choose consciously whether she will or will not be a mother.\" Denying the right to abortion can be construed from this perspective as a form of female oppression under a patriarchal system, perpetuating inequality between the sexes.',2,16),(37,'A woman\'s right to privacy supercedes a fetus\' right to life prior to viability','Even though the right to privacy is not explicitly stated in many constitutions of sovereign nations, many people see it as foundational to a functioning democracy. In general the right to privacy can be found to rest on the provisions of habeas corpus, which first found official expression under Henry II in 11th century England, but has precedent in Anglo-Saxon law. This provision guarantees the right to freedom from arbitrary government interference, as well as due process of law. This conception of the right to privacy is operant in all countries which have adopted English common law through Acts of Reception. The Law of the United States rests on English common law by this means.\r\n\r\nTraditionally, American courts have located the right to privacy in the Fourth Amendment, Ninth Amendment, Fourteenth Amendment, as well as the penumbra of the Bill of Rights. The landmark decision, Roe v Wade relied on the 14th Amendment which guarantees that federal rights shall be applied equally to all persons born in the United States. The 14th Amendment has given rise to the doctrine of Substantive due process, which is said to guarantee various privacy rights, including the right to bodily integrity. In Canada, the courts have located privacy rights in the security of persons clause of the Canadian Charter of Rights and Freedoms. Section 7 of that charter echoes language used in the Universal Declaration of Human Rights, which also guarantees security of persons.\r\nEileen L. McDonagh explains privacy in US law:\r\n\r\n\"Although not widely understood, there are in fact two components to the right to bodily integrity and liberty: the right of a person to choose how to live her own life and the right of a person to consent to the effects of a private party on her bodily integrity and liberty. In the context of constitutional guarantees, a person\'s right to consent to \"what is done\" to her body is an even stronger right than a person\'s right to choose \"what to do\" with her life...Since there are two components to the right to bodily integrity and liberty--choice and consent--once the state designates the fetus as an entity separate from the woman, her right to terminate pregnancy stems not only from her right to make a choice about her liberty, but more fundamentally, from her right to consent to how the fetus, as another entity, affects her body and liberty.\"[2]\r\n\r\nWhile governments are allowed to invade the privacy of their citizens in some cases, they are expected to protect privacy in all cases lacking a compelling state interest. In the US, the compelling state interest test has been developed in accordance with the standards of strict scrutiny. In Roe v Wade, the Court decided that the state has an \"important and legitimate interest in protecting the potentiality of human life\" from the point of viability on, but that prior to viability, the woman\'s fundamental rights are more compelling than that of the state.',2,16),(38,'Life begins at conception so abortion is immoral and should be illegal','The Roman Catholic Church maintains that life begins at conception, and the Fifth Commandment prohibits killing a human.[1] Therefore, abortion violates the Fifth Commandment. Since US law is based on a Judeo-Christian tradition, US law should prohibit abortion.',2,16),(39,'The Keystone XL Pipeline should not be approved by the US Department of State','The main reasons to block this project are the risk of tar sands oil spills along the pipeline, which would traverse highly sensitive terrain, and 12â€“17% higher greenhouse gas emissions from the extraction of oil sands compared to extraction of conventional oil. A pipeline spill would pollute air and critical water supplies and harm migratory birds and other wildlife. At a time when atmospheric concentrations of CO2 have exceeded 400 ppm (350 ppm is the recommended safe level) and extreme weather events are on the rise around the world, it makes no logical sense to increase the capacity to produce and consume tar sands oil.',6,16),(40,'Wind power projects should continue to be subsidized by the US federal government','When considering all forms of subsidy for all forms of energy production, wind power looks like a bargain. Some people criticize wind energy for being too expensive when they compare the $/kWh from a wind farm to a coal plant. But that number excludes the externalized costs, including the cost of environmental degradation (e.g. global warming). More over, subsidies given to the fossil fuel industries in the form of direct funds and tax breaks are substantially greater than for renewables[1] while oil companies are some of the most profitable corporations in the world. Given the fact that CO2 concentrations in the atmosphere have recently exceeded 400 ppm, and 350 ppm has been designated as the safe level[2], the federal government needs to do all it can to substantially replace fossil fuel energy with renewable energy.',7,16);
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refs`
--

DROP TABLE IF EXISTS `refs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refs` (
  `ref_id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_type` int(11) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `author` varchar(1000) DEFAULT NULL,
  `publisher` varchar(1000) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `date` varchar(100) DEFAULT NULL,
  `isbn` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `page` varchar(100) DEFAULT NULL,
  `volume` varchar(10) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `type` char(1) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`ref_id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refs`
--

LOCK TABLES `refs` WRITE;
/*!40000 ALTER TABLE `refs` DISABLE KEYS */;
INSERT INTO `refs` VALUES (61,1,'Global Warming','Wikipedia contributors','Wikipedia: The Free Encyclopedia','http://en.wikipedia.org/wiki/Global_warming','14 May 2013','','','','','','i',1),(62,2,'America\'s Climate Choices','Committee on America\'s Climate Choices','The National Academies Press','http://www.nap.edu/openbook.php?record_id=12781','2011','978-0-309-14585-5','Washington, D.C.','1','','','i',1),(63,1,'Joint science academiesâ€™ statement: Global response to climate change','','The National Academies Press','http://nationalacademies.org/onpi/06072005.pdf','14 May 2013','978-0-309-14585-5','Washington, D.C.','1','','','i',1),(64,1,'IPCC Fourth Assessment Report','Wikipedia contributors','Wikipedia: The Free Encyclopedia','http://en.wikipedia.org/wiki/IPCC_Fourth_Assessment_Report','14 May 2013','','','','','','i',1),(65,4,'Expansion of the Hadley cell under global warming','Lu, Jian; Vechhi, Gabriel A.; Reichler, Thomas','Geophysical Research Letters','','March 2007','','','','34','6','i',1),(67,1,'Abortion','Wikipedia contributors','Wikipedia: The Free Encyclopedia','http://en.wikipedia.org/wiki/Abortion','14 May 2013','','','','','','i',2),(68,4,'Unsafe abortion: The preventable pandemic','Grimes, D. A.; Benson, J.; Singh, S.; Romero, M.; Ganatra, B.; Okonofua, F. E.; Shah, I. H.','The Lancet','http://www.who.int/reproductivehealth/publications/general/lancet_4.pdf','October 2006','','','','','','i',2),(69,4,'Unsafe abortion: global and regional incidence, trends, consequences, and challenges','Shah, I.; Ahman, E.','Journal of Obstetrics and Gynaecology Canada','http://www.who.int/reproductivehealth/publications/general/lancet_4.pdf','December 2009','','','1149 - 58','31','12','i',2),(70,4,'Critical gaps in universal access to reproductive health: Contraception and prevention of unsafe abortion','Culwell KR, Vekemans M, de Silva U, Hurwitz M','International Journal of Gynecology ','','July 2010','','','S13 - 16','110','','i',2),(71,1,'Abortion debate','Wikipedia contributors','Wikipedia: the Free Encyclopedia','http://en.wikipedia.org/wiki/Abortion_debate#Privacy','14 May 2013','','','','','','p',37),(72,1,'My body, my consent: securing the constitutional right to abortion funding','Eileen L. McDonagh','Albany Law Review','http://findarticles.com/p/articles/mi_hb3243/is_3_62/ai_n28731577/?tag=content;col1','1999','','','','','','p',37),(73,1,'Article 5: The Fifth Commandment','','The Vatican','http://www.vatican.va/archive/ccc_css/archive/catechism/p3s2c2a5.htm','14 May 2013','','','','','','p',38),(74,1,'Tar sands','Wikipedia contributors','Wikipedia: the Free Encyclopedia','http://en.wikipedia.org/wiki/Tar_sands','14 May 2013','','','','','','i',3),(75,4,'Alberta\'s Oil Sands: Opportunity, Balance','','Government of Alberta','http://www.environment.alberta.ca/documents/Oil_Sands_Opportunity_Balance.pdf','March 2008','','','','','','i',3),(76,4,'Bitumen and heavy crudes: The energy security problem solved?','','Oil and Energy Trends','','2006','','','3 - 5','31','6','i',3),(77,1,'Political finance','Wikipedia contributros','Wikipedia: the Free Encyclopedia','http://en.wikipedia.org/wiki/Political_finance','14 May 2013','','','','','','i',4),(78,1,'Campaign finance','Wikipedia contributros','Wikipedia: the Free Encyclopedia','http://en.wikipedia.org/wiki/Campaign_finance','14 May 2013','','','','','','i',4),(79,1,'Gun politics','Wikipedia contributors','Wikipedia','http://en.wikipedia.org/wiki/Gun_politics','14 May 2013','','','','','','i',5),(80,1,'Americans Back Obama\'s Proposals to Address Gun Violence','','Gallup','http://www.gallup.com/poll/160085/americans-back-obama-proposals-address-gun-violence.aspx','14 May 2013','','','','','','i',5),(81,3,'Senate Blocks Drive for Gun Control','Jonathan Weisman','New York Times','http://www.nytimes.com/2013/04/18/us/politics/senate-obama-gun-control.html?_r=0','17 April 2012','','','','','','i',5),(82,1,'Keystone XL Pipeline','Wikipedia contributors','Wikipedia','http://en.wikipedia.org/wiki/Keystone_XL_pipeline','14 May 2013','','','','','','i',6),(83,1,'Keystone XL Pipeline Project','','TransCanada','http://www.transcanada.com/keystone.html','14 May 2013','','','','','','i',6),(84,1,'Draft Supplemental Environmental Impact Statement for the KEYSTONE XL PROJECT Applicant for Presidential Permit: TransCanada Keystone Pipeline, LP (SEIS)','United States Department of State Bureau of Oceans and International Environmental and Scientific Affairs','United States Department of State','http://keystonepipeline-xl.state.gov/documents/organization/205719.pdf','1 March 2013','','','','','','i',6),(85,1,'Wind power','Wikipedia contributors','Wikipedia','http://en.wikipedia.org/wiki/Wind_power','15 May 2013','','','','','','i',7),(86,1,'Energy Subsidies Black, Not Green','','Environmental Law Institute','http://www.eli.org/pdf/Energy_Subsidies_Black_Not_Green.pdf','16 May 2013','','','','','','p',40),(87,4,'Target atmospheric CO2: Where should humanity aim?','Hansen, James, et al','Open Atmospheric Science Journal','http://arxiv.org/pdf/0804.1126v3.pdf','2008','','','217 - 231','2','','p',40);
/*!40000 ALTER TABLE `refs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `type` char(1) NOT NULL,
  `type_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `vote` int(11) DEFAULT NULL,
  PRIMARY KEY (`type`,`type_id`,`citizen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES ('a',5,16,1),('p',35,16,1),('p',36,16,1),('p',37,16,1),('p',38,16,2),('p',39,16,1),('c',5,16,1),('p',40,16,1),('p',36,17,1),('p',37,17,1),('p',38,17,2),('c',5,17,2);
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-05-24  5:01:56
