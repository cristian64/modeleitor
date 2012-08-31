-- MySQL dump 10.13  Distrib 5.1.63, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: catalog
-- ------------------------------------------------------
-- Server version	5.1.63-0ubuntu0.10.04.1

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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_padre` int(11) NOT NULL DEFAULT '0',
  `nombre` varchar(100) NOT NULL,
  `mostrar` tinyint(1) NOT NULL DEFAULT '1',
  `zindex` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,0,'Mujer',1,1,'Zapatos de mujer'),(2,0,'Hombre',1,0,'Zapatos de hombre'),(3,0,'Niño y Niña',1,0,'Zapatos de niño y niña'),(4,0,'Tallas Especiales',1,0,'Números de tamaño especial tanto de mujer como hombre'),(6,1,'Zapatos',1,10,''),(7,1,'Botas y Botines',1,8,''),(9,1,'Anchos Especiales',1,9,''),(10,1,'Fiesta',1,6,''),(11,1,'Anatómicos y Zapatillas',1,0,''),(13,1,'Mocasines y Kiowas',1,7,''),(14,2,'Zapatos Varios',1,0,''),(15,2,'Casual',1,4,''),(16,2,'Suela de Cuero',1,2,''),(17,2,'Deportivos',1,1,''),(18,2,'Mocasines y Kiowas',1,3,''),(19,2,'Clásicos',1,5,''),(25,2,'Zapatillas',1,0,''),(26,2,'Náuticos',1,6,''),(27,1,'Abotinados',1,7,''),(28,1,'Plataforma',1,7,'');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias_modelos`
--

DROP TABLE IF EXISTS `categorias_modelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias_modelos` (
  `id_categoria` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  PRIMARY KEY (`id_categoria`,`id_modelo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias_modelos`
--

LOCK TABLES `categorias_modelos` WRITE;
/*!40000 ALTER TABLE `categorias_modelos` DISABLE KEYS */;
INSERT INTO `categorias_modelos` VALUES (1,18),(1,19),(1,24),(1,25),(1,26),(1,27),(1,28),(1,29),(1,30),(1,31),(1,32),(6,18),(6,19),(6,24),(6,25),(6,26),(6,27),(6,28),(6,31),(7,29),(7,30),(7,32),(9,30),(13,31);
/*!40000 ALTER TABLE `categorias_modelos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fabricantes`
--

DROP TABLE IF EXISTS `fabricantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fabricantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(100) NOT NULL DEFAULT '',
  `descripcion` text NOT NULL,
  `email` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fabricantes`
--

LOCK TABLES `fabricantes` WRITE;
/*!40000 ALTER TABLE `fabricantes` DISABLE KEYS */;
INSERT INTO `fabricantes` VALUES (1,'Antonio Méndez','','',''),(2,'Par y Medio','','',''),(3,'Carlos Mora','','',''),(4,'José Cutillas','','',''),(5,'Paquito','','',''),(12,'Antonio Hernández','','',''),(7,'Herminio','','',''),(9,'Gavis','','',''),(10,'Pepito','','',''),(11,'Aghata Shoes','','','');
/*!40000 ALTER TABLE `fabricantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `logo` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (19,'Alto Estilo',''),(16,'Annora',''),(15,'Anchos Calzados',''),(18,'Hernández',''),(13,'Agatha Shoes',''),(17,'Antonella',''),(14,'Air',''),(20,'Bellatriz',''),(21,'Zapatos Elda',''),(22,'Ballerinas A&J',''),(23,'Cactus',''),(24,'Calzados Chachi',''),(25,'Candy\'s',''),(26,'CKC',''),(27,'Cutillas',''),(28,'Carmelo',''),(29,'Deportivos Varios',''),(30,'Dio',''),(31,'Dominico',''),(32,'D\'Alvaro',''),(33,'Sakut',''),(34,'Castellanos JAM',''),(35,'Fortium',''),(36,'Gavis',''),(37,'Guantifles',''),(38,'Elegance',''),(39,'Lambus',''),(40,'Ibérico',''),(41,'Zancadas',''),(42,'JAM Almansa',''),(43,'Carlo Garelli',''),(44,'Calzados Bailen',''),(45,'Luciher',''),(46,'Mistral',''),(47,'Morxiva',''),(48,'Mne marrone',''),(49,'Tallas Especiales',''),(50,'JAM Fiesta',''),(51,'JAM',''),(52,'Par y Medio',''),(53,'Peña Rubia',''),(54,'Roydi',''),(55,'Roquisini',''),(56,'Roger Milton',''),(57,'Nikoe',''),(58,'Sanchini',''),(59,'Serial',''),(60,'Serna',''),(61,'Carleti',''),(62,'Elegance',''),(63,'Vicente Garban',''),(64,'Wheti\'s',''),(65,'Zuecos Anatómicos',''),(66,'Beppi',''),(67,'Linea 7','');
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modelos`
--

DROP TABLE IF EXISTS `modelos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modelos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fabricante` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `referencia` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `talla_menor` int(11) NOT NULL DEFAULT '0',
  `talla_mayor` int(11) NOT NULL DEFAULT '0',
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `oferta` tinyint(1) NOT NULL DEFAULT '0',
  `prioridad` int(11) NOT NULL DEFAULT '5',
  `descatalogado` tinyint(1) NOT NULL DEFAULT '0',
  `foto` varchar(256) NOT NULL DEFAULT '',
  `fecha_creacion` datetime NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modelos`
--

LOCK TABLES `modelos` WRITE;
/*!40000 ALTER TABLE `modelos` DISABLE KEYS */;
INSERT INTO `modelos` VALUES (19,3,23,'1678','Pollaca Gorda',35,41,'<p>&lt;</p>\r\n<table style=\"width: 228px; height: 148px;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td><img title=\"Kiss\" src=\"js/tinymce/plugins/emotions/img/smiley-kiss.gif\" alt=\"Kiss\" border=\"0\" /><img title=\"Yell\" src=\"js/tinymce/plugins/emotions/img/smiley-yell.gif\" alt=\"Yell\" border=\"0\" /></td>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</tbody>\r\n</table>','14.00',0,5,0,'19.jpg','2012-08-29 01:11:13','2012-08-30 21:35:05'),(18,0,0,'1678','Francesita con lazo y putan de charol',35,41,'<p>Francesita con lazo y putan de charol</p>','500.00',0,5,0,'18.jpg','2012-08-28 16:54:45','2012-08-30 22:52:46'),(24,11,0,'22054','Zapato abotinado en piel lodo con tacón y cremallera ',35,41,'','24.00',0,5,0,'24.jpg','2012-08-30 13:11:51','2012-08-31 13:11:22'),(25,0,0,'1024','Prueba 1024 PS',0,0,'','0.00',0,5,0,'25.jpg','2012-08-30 13:27:55','2012-08-30 13:28:24'),(26,0,0,'1024 herramienta sin compresión','',0,0,'','0.00',0,5,0,'26.jpg','2012-08-30 13:32:09','2012-08-30 13:32:09'),(27,0,0,'1024 sin compresión','',0,0,'','0.00',0,5,0,'27.jpg','2012-08-30 13:35:08','2012-08-30 13:35:08'),(28,11,13,'1024 ps','TACÓN PIEL LODO POMPON ',35,41,'','30.00',0,5,0,'28.jpg','2012-08-30 13:43:58','2012-08-31 11:35:18'),(29,11,0,'15126746','Bota mujer en piel camel con tacón ',35,41,'','500.00',0,5,0,'29.jpg','2012-08-30 17:07:10','2012-08-31 11:24:03'),(30,0,15,'438','Botín de piel negra con forro de borreguillo y cremallera',35,41,'','16.90',0,5,0,'30.jpg','2012-08-31 11:02:44','2012-08-31 11:58:30'),(31,0,48,'4001-S','Castellano, Mujer, Piel, Negro, Burdeos, Suela cuerolite',35,41,'','21.80',0,5,0,'31.jpg','2012-08-31 11:07:01','2012-08-31 11:07:01'),(32,0,56,'4624','Bota, Piel Marrón, Elástico y Cremallera',36,41,'','36.80',0,5,0,'32.jpg','2012-08-31 11:20:02','2012-08-31 11:21:51');
/*!40000 ALTER TABLE `modelos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `contrasena` char(128) NOT NULL,
  `nombre` varchar(100) NOT NULL DEFAULT '',
  `telefono` varchar(100) NOT NULL DEFAULT '',
  `direccion` text NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'cristian64@gmail.com','ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','Cristian Aguilera Martínez','+34630276575','Calle José García Ferrández, 38\r\nElche (ALICANTE)\r\n03205\r\nEspaña',1,1,'2012-08-22 23:57:00'),(3,'cristian@correo.com','ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','Cristano Ronaldo','','',0,0,'2012-08-28 01:03:20'),(4,'probando@gasdad.com','ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413','Probando Jajajaja','','',0,0,'2012-08-31 09:58:45');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-08-31 13:35:56
