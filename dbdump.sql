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
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidates` (
  `candidate_id` int(11) NOT NULL AUTO_INCREMENT,
  `citizen_id` int(11) NOT NULL,
  `office` varchar(100) NOT NULL,
  `election` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  PRIMARY KEY (`candidate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates`
--

LOCK TABLES `candidates` WRITE;
/*!40000 ALTER TABLE `candidates` DISABLE KEYS */;
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
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_year` int(11) DEFAULT NULL,
  `gender` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `postal_code` varchar(25) DEFAULT NULL,
  `telephone` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`citizen_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `citizens`
--

LOCK TABLES `citizens` WRITE;
/*!40000 ALTER TABLE `citizens` DISABLE KEYS */;
INSERT INTO `citizens` VALUES (1,'9e1051cfaf548b4b97071a403dffff05fb36ab9a','bftrock@gmail.com','Brendan','Taylor',1969,1,'South Burlington','VT',236,'05403','8023551612'),(4,'78d958bddf77a4f71eca2333920e95e255c3e83f','monicataylor1026@gmail.com','Monica','Taylor',0,2,'South Burlington','VT',236,'05403','8023186709');
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
  `position_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` varchar(1000) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,1,'2012-02-21 11:44:48','This is a test.'),(2,1,1,'2012-02-21 11:45:01','This is a test.'),(3,1,1,'2012-02-21 11:45:02','This is a test.'),(4,1,1,'2012-02-21 11:45:03','This is a test.'),(5,1,1,'2012-02-21 11:45:55','This is a test.'),(6,1,1,'2012-02-21 11:46:05','This is another test.'),(7,1,1,'2012-02-22 11:02:06','Today I added another comment.'),(8,8,1,'2012-02-22 11:41:36','Here is my comment. Blah blah blah.'),(9,7,1,'2012-03-04 12:28:42','lsdlfjsdlfjsldjf'),(10,10,0,'2012-12-09 15:19:06','This is a comment. Blah blah blah.'),(11,1,1,'2013-01-11 11:26:48','Here\'s another comment: blah blah blah.'),(13,10,1,'2013-01-12 13:11:22','Here is another comment.');
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
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'AFGHANISTAN'),(2,'Ã…LAND ISLANDS'),(3,'ALBANIA'),(4,'ALGERIA'),(5,'AMERICAN SAMOA'),(6,'ANDORRA'),(7,'ANGOLA'),(8,'ANGUILLA'),(9,'ANTARCTICA'),(10,'ANTIGUA AND BARBUDA'),(11,'ARGENTINA'),(12,'ARMENIA'),(13,'ARUBA'),(14,'AUSTRALIA'),(15,'AUSTRIA'),(16,'AZERBAIJAN'),(17,'BAHAMAS'),(18,'BAHRAIN'),(19,'BANGLADESH'),(20,'BARBADOS'),(21,'BELARUS'),(22,'BELGIUM'),(23,'BELIZE'),(24,'BENIN'),(25,'BERMUDA'),(26,'BHUTAN'),(27,'BOLIVIA, PLURINATIONAL STATE OF'),(28,'BONAIRE, SINT EUSTATIUS AND SABA'),(29,'BOSNIA AND HERZEGOVINA'),(30,'BOTSWANA'),(31,'BOUVET ISLAND'),(32,'BRAZIL'),(33,'BRITISH INDIAN OCEAN TERRITORY'),(34,'BRUNEI DARUSSALAM'),(35,'BULGARIA'),(36,'BURKINA FASO'),(37,'BURUNDI'),(38,'CAMBODIA'),(39,'CAMEROON'),(40,'CANADA'),(41,'CAPE VERDE'),(42,'CAYMAN ISLANDS'),(43,'CENTRAL AFRICAN REPUBLIC'),(44,'CHAD'),(45,'CHILE'),(46,'CHINA'),(47,'CHRISTMAS ISLAND'),(48,'COCOS (KEELING) ISLANDS'),(49,'COLOMBIA'),(50,'COMOROS'),(51,'CONGO'),(52,'CONGO, THE DEMOCRATIC REPUBLIC OF THE'),(53,'COOK ISLANDS'),(54,'COSTA RICA'),(55,'CÃ”TE D\'IVOIRE'),(56,'CROATIA'),(57,'CUBA'),(58,'CURAÃ‡AO'),(59,'CYPRUS'),(60,'CZECH REPUBLIC'),(61,'DENMARK'),(62,'DJIBOUTI'),(63,'DOMINICA'),(64,'DOMINICAN REPUBLIC'),(65,'ECUADOR'),(66,'EGYPT'),(67,'EL SALVADOR'),(68,'EQUATORIAL GUINEA'),(69,'ERITREA'),(70,'ESTONIA'),(71,'ETHIOPIA'),(72,'FALKLAND ISLANDS (MALVINAS)'),(73,'FAROE ISLANDS'),(74,'FIJI'),(75,'FINLAND'),(76,'FRANCE'),(77,'FRENCH GUIANA'),(78,'FRENCH POLYNESIA'),(79,'FRENCH SOUTHERN TERRITORIES'),(80,'GABON'),(81,'GAMBIA'),(82,'GEORGIA'),(83,'GERMANY'),(84,'GHANA'),(85,'GIBRALTAR'),(86,'GREECE'),(87,'GREENLAND'),(88,'GRENADA'),(89,'GUADELOUPE'),(90,'GUAM'),(91,'GUATEMALA'),(92,'GUERNSEY'),(93,'GUINEA'),(94,'GUINEA-BISSAU'),(95,'GUYANA'),(96,'HAITI'),(97,'HEARD ISLAND AND MCDONALD ISLANDS'),(98,'HOLY SEE (VATICAN CITY STATE)'),(99,'HONDURAS'),(100,'HONG KONG'),(101,'HUNGARY'),(102,'ICELAND'),(103,'INDIA'),(104,'INDONESIA'),(105,'IRAN, ISLAMIC REPUBLIC OF'),(106,'IRAQ'),(107,'IRELAND'),(108,'ISLE OF MAN'),(109,'ISRAEL'),(110,'ITALY'),(111,'JAMAICA'),(112,'JAPAN'),(113,'JERSEY'),(114,'JORDAN'),(115,'KAZAKHSTAN'),(116,'KENYA'),(117,'KIRIBATI'),(118,'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF'),(119,'KOREA, REPUBLIC OF'),(120,'KUWAIT'),(121,'KYRGYZSTAN'),(122,'LAO PEOPLE\'S DEMOCRATIC REPUBLIC'),(123,'LATVIA'),(124,'LEBANON'),(125,'LESOTHO'),(126,'LIBERIA'),(127,'LIBYA'),(128,'LIECHTENSTEIN'),(129,'LITHUANIA'),(130,'LUXEMBOURG'),(131,'MACAO'),(132,'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF'),(133,'MADAGASCAR'),(134,'MALAWI'),(135,'MALAYSIA'),(136,'MALDIVES'),(137,'MALI'),(138,'MALTA'),(139,'MARSHALL ISLANDS'),(140,'MARTINIQUE'),(141,'MAURITANIA'),(142,'MAURITIUS'),(143,'MAYOTTE'),(144,'MEXICO'),(145,'MICRONESIA, FEDERATED STATES OF'),(146,'MOLDOVA, REPUBLIC OF'),(147,'MONACO'),(148,'MONGOLIA'),(149,'MONTENEGRO'),(150,'MONTSERRAT'),(151,'MOROCCO'),(152,'MOZAMBIQUE'),(153,'MYANMAR'),(154,'NAMIBIA'),(155,'NAURU'),(156,'NEPAL'),(157,'NETHERLANDS'),(158,'NEW CALEDONIA'),(159,'NEW ZEALAND'),(160,'NICARAGUA'),(161,'NIGER'),(162,'NIGERIA'),(163,'NIUE'),(164,'NORFOLK ISLAND'),(165,'NORTHERN MARIANA ISLANDS'),(166,'NORWAY'),(167,'OMAN'),(168,'PAKISTAN'),(169,'PALAU'),(170,'PALESTINIAN TERRITORY, OCCUPIED'),(171,'PANAMA'),(172,'PAPUA NEW GUINEA'),(173,'PARAGUAY'),(174,'PERU'),(175,'PHILIPPINES'),(176,'PITCAIRN'),(177,'POLAND'),(178,'PORTUGAL'),(179,'PUERTO RICO'),(180,'QATAR'),(181,'RÃ‰UNION'),(182,'ROMANIA'),(183,'RUSSIAN FEDERATION'),(184,'RWANDA'),(185,'SAINT BARTHÃ‰LEMY'),(186,'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA'),(187,'SAINT KITTS AND NEVIS'),(188,'SAINT LUCIA'),(189,'SAINT MARTIN (FRENCH PART)'),(190,'SAINT PIERRE AND MIQUELON'),(191,'SAINT VINCENT AND THE GRENADINES'),(192,'SAMOA'),(193,'SAN MARINO'),(194,'SAO TOME AND PRINCIPE'),(195,'SAUDI ARABIA'),(196,'SENEGAL'),(197,'SERBIA'),(198,'SEYCHELLES'),(199,'SIERRA LEONE'),(200,'SINGAPORE'),(201,'SINT MAARTEN (DUTCH PART)'),(202,'SLOVAKIA'),(203,'SLOVENIA'),(204,'SOLOMON ISLANDS'),(205,'SOMALIA'),(206,'SOUTH AFRICA'),(207,'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS'),(208,'SOUTH SUDAN'),(209,'SPAIN'),(210,'SRI LANKA'),(211,'SUDAN'),(212,'SURINAME'),(213,'SVALBARD AND JAN MAYEN'),(214,'SWAZILAND'),(215,'SWEDEN'),(216,'SWITZERLAND'),(217,'SYRIAN ARAB REPUBLIC'),(218,'TAIWAN, PROVINCE OF CHINA'),(219,'TAJIKISTAN'),(220,'TANZANIA, UNITED REPUBLIC OF'),(221,'THAILAND'),(222,'TIMOR-LESTE'),(223,'TOGO'),(224,'TOKELAU'),(225,'TONGA'),(226,'TRINIDAD AND TOBAGO'),(227,'TUNISIA'),(228,'TURKEY'),(229,'TURKMENISTAN'),(230,'TURKS AND CAICOS ISLANDS'),(231,'TUVALU'),(232,'UGANDA'),(233,'UKRAINE'),(234,'UNITED ARAB EMIRATES'),(235,'UNITED KINGDOM'),(236,'UNITED STATES'),(237,'UNITED STATES MINOR OUTLYING ISLANDS'),(238,'URUGUAY'),(239,'UZBEKISTAN'),(240,'VANUATU'),(241,'VENEZUELA, BOLIVARIAN REPUBLIC OF'),(242,'VIET NAM'),(243,'VIRGIN ISLANDS, BRITISH'),(244,'VIRGIN ISLANDS, U.S.'),(245,'WALLIS AND FUTUNA'),(246,'WESTERN SAHARA'),(247,'YEMEN'),(248,'ZAMBIA'),(249,'ZIMBABWE');
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
  `msg` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `debug`
--

LOCK TABLES `debug` WRITE;
/*!40000 ALTER TABLE `debug` DISABLE KEYS */;
/*!40000 ALTER TABLE `debug` ENABLE KEYS */;
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
INSERT INTO `issue_category` VALUES (1,3),(1,6),(1,7),(2,1),(2,2),(9,1);
/*!40000 ALTER TABLE `issue_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `issues`
--

DROP TABLE IF EXISTS `issues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issues` (
  `issue_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(3000) NOT NULL,
  `refs` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`issue_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `issues`
--

LOCK TABLES `issues` WRITE;
/*!40000 ALTER TABLE `issues` DISABLE KEYS */;
INSERT INTO `issues` VALUES (1,'The Legality and Morality of Abortion','Abortion is defined as the termination of pregnancy by the removal or expulsion from the uterus of a fetus or embryo prior to viability. An abortion can occur spontaneously, in which case it is usually called a miscarriage, or it can be purposely induced. The term abortion most commonly refers to the induced abortion of a human pregnancy.\r\n\r\nAbortion, when induced in the developed world in accordance with local law, is among the safest procedures in medicine. However, unsafe abortions (those performed by persons without proper training or outside of a medical environment) result in approximately 70 thousand maternal deaths and 5 million disabilities per year globally. An estimated 42 million abortions are performed globally each year, with 20 million of those performed unsafely. The incidence of abortion has declined worldwide as access to family planning education and contraceptive services has increased. Forty percent of the world\'s women have access to induced abortions (within gestational limits).\r\n\r\nThe abortion debate refers to discussion and controversy surrounding the moral and legal status of abortion. The two main groups involved in the abortion debate are the self-described \"pro-choice\" movement (emphasizing the right of women to choose whether or not they wish to bring a fetus to term) and the \"pro-life\" movement (emphasizing the right of the unborn child to live). Each movement has, with varying results, sought to influence public opinion and to attain legal support for its position. Both of these are considered loaded terms in general media where terms such as \"abortion rights\" or \"anti abortion\" are preferred. In Canada, for example, abortion is available on demand, while in Ireland abortions are illegal. In some cases, the abortion debate has led to the use of violence.',NULL),(2,'Wind Power','Wind power refers to the conversion of wind energy into electricity for use in the electric grid. The total amount of economically extractable power available from the wind is considerably more than present human power use from all sources. At the end of 2010, worldwide nameplate capacity of wind-powered generators was 197 gigawatts (GW). Wind power now has the capacity to generate 430 TWh annually, which is about 2.5% of worldwide electricity usage. Over the past five years the average annual growth in new installations has been 27.6 percent. Wind power market penetration is expected to reach 3.35 percent by 2013 and 8 percent by 2018. Several countries have already achieved relatively high levels of wind power penetration, such as 21% of stationary electricity production in Denmark, 18% in Portugal, 16% in Spain, 14% in Ireland and 9% in Germany in 2010. As of 2011, 83 countries around the world are using wind power on a commercial basis.\r\n\r\nA large wind farm may consist of several hundred individual wind turbines which are connected to the electric power transmission network. Offshore wind power can harness the better wind speeds that are available offshore compared to on land, so offshore wind power’s contribution in terms of electricity supplied is higher. However, the cost of installing wind power offshore is higher than onshore. Although a variable source of power, the intermittency of wind seldom creates problems when using wind power to up to 20% of total electricity demand. As the percentage of wind power increases, so does the need to use other methods to mitigate the effects of its intermittency, such as energy storage.\r\n\r\nThe construction of wind farms is sometimes opposed because of visual impacts, concerns about noise and the health effects of visual flicker from the shadows cast by the rotating blades, local environmental impacts due to road construction, and concerns about habitat conservation.\r\n\r\nWind power is plentiful, renewable, widely distributed, clean, produces no greenhouse gas emissions during operation, and uses little land. In operation, the overall cost per unit of energy produced is similar to the cost for new coal and natural gas installations. While fossil fuel-generated electricity is subject to the price volatility of the fuel source, the wind is virtually free.\r\n',NULL),(9,'Global Warming','Global warming refers to the rising average temperature of Earth\'s atmosphere and oceans, which started to increase in the late 19th century and is projected to keep going up. Since the early 20th century, Earth\'s average surface temperature has increased by about 0.8 °C (1.4 °F), with about two thirds of the increase occurring since 1980. Warming of the climate system is unequivocal, and scientists are more than 90% certain that most of it is caused by increasing concentrations of greenhouse gases produced by human activities such as deforestation and burning fossil fuels. These findings are recognized by the national science academies of all the major industrialized nations.\r\n\r\nClimate model projections are summarized in the 2007 Fourth Assessment Report (AR4) by the Intergovernmental Panel on Climate Change (IPCC). They indicate that during the 21st century the global surface temperature is likely to rise a further 1.1 to 2.9 °C (2 to 5.2 °F) for their lowest emissions scenario and 2.4 to 6.4 °C (4.3 to 11.5 °F) for their highest. The ranges of these estimates arise from the use of models with differing sensitivity to greenhouse gas concentrations.\r\n\r\nAn increase in global temperature will cause sea levels to rise and will change the amount and pattern of precipitation, and a probable expansion of subtropical deserts. Warming is expected to be strongest in the Arctic and would be associated with continuing retreat of glaciers, permafrost and sea ice. Other likely effects of the warming include more frequent occurrence of extreme-weather events including heat waves, droughts and heavy rainfall, species extinctions due to shifting temperature regimes, and changes in crop yields. Warming and related changes will vary from region to region around the globe, with projections being more robust in some areas than others. If global mean temperature increases to 4 °C above preindustrial levels, the limits for human adaptation are likely to be exceeded in many parts of the world, while the limits for adaptation for natural systems would largely be exceeded throughout the world. Hence, the ecosystem services upon which human livelihoods depend would not be preserved.','[{\"id\":1,\"type\":\"Web\",\"title\":\"Global Warming\",\"author\":\"Wikipedia contributors\",\"publisher\":\"Wikipedia, the Free Encyclopedia\",\"date\":\"19 January 2013\",\"url\":\"http://en.wikipedia.org/wiki/Global_warming\"},{\"id\":2,\"type\":\"Book\",\"title\":\"America\'s Climate Choices\",\"publisher\":\"The National Academies Press\",\"isbn\":\"978-0-309-14585-5\",\"location\":\"Washington, D.C.\",\"date\":\"2011\",\"url\":\"http://www.nap.edu/openbook.php?record_id=12781&page=1\",\"page\":15},{\"id\":3,\"type\":\"News\",\"title\":\"Midwest to feel the heat of global warming\",\"author\":\"Bob Berwyn\",\"publisher\":\"Summit County Citizens Voice\",\"date\": \"19 January 2013\",\"url\":\"http://summitcountyvoice.com/2013/01/19/midwest-to-feel-the-heat-of-global-warming/\"},{\"id\":4,\"type\":\"Journal\",\"author\":\"Held, Isaac M., and Brian J. Soden\",\"title\": \"Robust responses of the hydrological cycle to global warming\",\"publisher\":\"Journal of Climate\",\"volume\":19,\"number\":21,\"date\":2006,\"page\":\"5686 - 5699\",\"url\":\"http://journals.ametsoc.org/doi/pdf/10.1175/JCLI3990.1\"}]');
/*!40000 ALTER TABLE `issues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `position_citizen`
--

DROP TABLE IF EXISTS `position_citizen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `position_citizen` (
  `position_id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  PRIMARY KEY (`position_id`,`citizen_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `position_citizen`
--

LOCK TABLES `position_citizen` WRITE;
/*!40000 ALTER TABLE `position_citizen` DISABLE KEYS */;
INSERT INTO `position_citizen` VALUES (1,1,1),(2,1,2),(3,1,1),(4,1,1),(6,1,1),(7,1,2),(10,1,1),(11,1,2);
/*!40000 ALTER TABLE `position_citizen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `positions`
--

DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `justification` varchar(1000) NOT NULL,
  `issue_id` int(11) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `positions`
--

LOCK TABLES `positions` WRITE;
/*!40000 ALTER TABLE `positions` DISABLE KEYS */;
INSERT INTO `positions` VALUES (1,'A woman\'s right to privacy supercedes the fetus\' right to life.','In relation to abortion, privacy is defined as the ability of a woman to decide what happens to her own body. In political terms, privacy can be understood as a condition in which one is not observed or disturbed by government. \r\n\r\nEileen L. McDonagh explains privacy in US law:\r\n\r\nAlthough not widely understood, there are in fact two components to the right to bodily integrity and liberty: the right of a person to choose how to live her own life and the right of a person to consent to the effects of a private party on her bodily integrity and liberty. In the context of constitutional guarantees, a person\'s right to consent to \"what is done\" to her body is an even stronger right than a person\'s right to choose \"what to do\" with her life...Since there are two components to the right to bodily integrity and liberty--choice and consent--once the state designates the fetus as an entity separate from the woman, her right to terminate pregnancy stems not only from her right to make a choice a',1),(2,'Terminating the life of a fetus is immoral, and therefore abortion should be illegal.','',1),(3,'Utility-scale wind power should be subsidized by the federal government.','',2),(4,'The Production Tax Credit should be extended for 10 years,','',2),(6,'Sexual emancipation and gender equality justify abortion in all cases.','Women\'s freedoms are limited until they can have the right to abortion on demand and to walk away from parenthood like men can. Governments that ban abortion arguably burden women with certain duties that men (who, too, are responsible for the pregnancy) are not also held accountable to, therefore, creating a double standard. Margaret Sanger wrote: \"No woman can call herself free until she can choose consciously whether she will or will not be a mother.\" Denying the right to abortion can be construed from this perspective as a form of female oppression under a patriarchal system, perpetuating inequality between the sexes.',1),(7,'Global warming is a farce imposed by those who seek research funding.','I choose to ignore science.',9),(10,'The federal government should make radical policy changes to try to avert disaster, even at the expense of the health of the short-term economy.','This is an issue that requires world cooperation to reduce greenhouse gas emissions, and each country\'s federal government should have as a high priority making the hard choices to avoid catastrophe. Even the optimistic scenarios for climate change paint a grimm picture of mass species extinction, ecological shifts that will make it hard or impossible for people to live in certain parts of the world, and rising sea levels. This will inevitably lead to large-scale migrations of people and straining of the remaining habitable land. With these predicted outcomes, the federal government should be doing everything it can to steer us away from the cliff we are headed toward.',9);
/*!40000 ALTER TABLE `positions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-01-21  4:35:40
