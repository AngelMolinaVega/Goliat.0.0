/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: goliat_db
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-5ubuntu0.1 from Ubuntu

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `faccion` varchar(100) NOT NULL,
  `puntos` int(11) NOT NULL,
  `ataque` varchar(20) DEFAULT NULL,
  `resistencia` int(11) DEFAULT NULL,
  `heridas` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES
(1,'Barbagaunts','Tiranidos',55,'1D6',4,2),
(2,'Biovores','Tiranidos',60,'1D3',6,5);
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` char(60) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1,
  `rol` enum('usuario','admin') DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'kiiRON','angelmlkok@gmail.com','$2y$12$V0EJ38PmJPUAQ5ma3Cqf7uB7gRx7Y9yB3Ga9p/Wb9DsFlQohMepp2','2026-08-26 12:21:08',1,'usuario'),
(2,'ADAM','adamdrismohamed2019@gmail.com','$2y$12$Rlxi.yz8lNEMgKIDuNjibuCbmfZBG1o8qziGJDlQ3tORhVpDND1Cq','2026-08-26 12:31:03',1,'usuario'),
(3,'Jachuli','psnps31346@gmail.com','$2y$12$d/CFnasPcLHDbERIA2B6Weqx2AJ2ie7EDocJdjMEcg00WkXtmnLUG','2026-08-26 14:07:26',1,'usuario'),
(4,'K','kayjjkpp@gmail.com','$2y$12$h3NqciT735b3f.sw6X41rOjFQ566Erx6ItGcVPO.0KWmmXRXhzegy','2026-08-27 20:35:36',1,'usuario'),
(5,'noni','penekillo15@gmail.com','$2y$12$mXBf3ScJYqx0sthTnSn6cuoTKaapkK8yZazsQxppSwOER3BYa5FNe','2026-09-03 13:37:28',1,'usuario'),
(7,'Elbi_Turon','sergiolopezsoliman@gmail.com','$2y$12$aRExDGGjclLuxIrjHidu5unYy0MnH.mFPnuI8uj.AhrC65B.th9/.','2026-09-13 20:28:19',1,'usuario');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wh40k_campana_participantes`
--

DROP TABLE IF EXISTS `wh40k_campana_participantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wh40k_campana_participantes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campana_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `faccion` varchar(100) DEFAULT NULL,
  `nombre_personaje` varchar(100) DEFAULT NULL,
  `fecha_union` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico_campana_usuario` (`campana_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `wh40k_campana_participantes_ibfk_1` FOREIGN KEY (`campana_id`) REFERENCES `wh40k_campanas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wh40k_campana_participantes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wh40k_campana_participantes`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wh40k_campana_participantes` WRITE;
/*!40000 ALTER TABLE `wh40k_campana_participantes` DISABLE KEYS */;
INSERT INTO `wh40k_campana_participantes` VALUES
(1,6,1,NULL,NULL,'2026-09-13 14:10:44'),
(2,6,5,NULL,NULL,'2026-09-13 14:47:56'),
(3,6,7,'Tiranidos','Pepe el pollo','2026-09-13 20:29:17');
/*!40000 ALTER TABLE `wh40k_campana_participantes` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wh40k_campanas`
--

DROP TABLE IF EXISTS `wh40k_campanas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wh40k_campanas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `edicion` varchar(100) DEFAULT NULL,
  `num_participantes` int(10) unsigned DEFAULT 1,
  `creador_id` int(10) unsigned NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `creador_id` (`creador_id`),
  CONSTRAINT `wh40k_campanas_ibfk_1` FOREIGN KEY (`creador_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wh40k_campanas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wh40k_campanas` WRITE;
/*!40000 ALTER TABLE `wh40k_campanas` DISABLE KEYS */;
INSERT INTO `wh40k_campanas` VALUES
(4,'Prueba','El sobrin es calvo','10',1,5,'2026-09-03 13:39:10'),
(6,'Autismo','viva españa','11ª',3,1,'2026-09-13 14:10:44');
/*!40000 ALTER TABLE `wh40k_campanas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wh40k_lista_unidades`
--

DROP TABLE IF EXISTS `wh40k_lista_unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wh40k_lista_unidades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lista_id` int(10) unsigned NOT NULL,
  `unidad_id` int(11) NOT NULL,
  `cantidad` int(10) unsigned DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unico_lista_unidad` (`lista_id`,`unidad_id`),
  KEY `unidad_id` (`unidad_id`),
  CONSTRAINT `wh40k_lista_unidades_ibfk_1` FOREIGN KEY (`lista_id`) REFERENCES `wh40k_listas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wh40k_lista_unidades_ibfk_2` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wh40k_lista_unidades`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wh40k_lista_unidades` WRITE;
/*!40000 ALTER TABLE `wh40k_lista_unidades` DISABLE KEYS */;
/*!40000 ALTER TABLE `wh40k_lista_unidades` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `wh40k_listas`
--

DROP TABLE IF EXISTS `wh40k_listas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `wh40k_listas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) unsigned NOT NULL,
  `nombre_lista` varchar(100) NOT NULL,
  `faccion` varchar(100) NOT NULL,
  `puntos_totales` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `wh40k_listas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wh40k_listas`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `wh40k_listas` WRITE;
/*!40000 ALTER TABLE `wh40k_listas` DISABLE KEYS */;
/*!40000 ALTER TABLE `wh40k_listas` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-04 22:01:59
