-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: app_educativa_recuperada
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acudientes`
--

DROP TABLE IF EXISTS `acudientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acudientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL DEFAULT '',
  `parentesco` varchar(60) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `estudiante_id` (`estudiante_id`),
  KEY `idx_acudiente_estudiante` (`estudiante_id`),
  CONSTRAINT `fk_acudiente_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acudientes`
--

LOCK TABLES `acudientes` WRITE;
/*!40000 ALTER TABLE `acudientes` DISABLE KEYS */;
INSERT INTO `acudientes` VALUES (1,1,'JUAN ALBERTO','ARIAS BONILLA','Padre','3175336167','juancholaos@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(2,2,'KAREN YHOLEISY','NUÑEZ BRICEÑO','Madre','3226478774','wendy.karen16@4gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(3,3,'MARY LUZ','SANCHEZ RINCON','MADRE','3206637110','ms3184130@hotmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(4,4,'YNEMAR PAREJO','ROJAS','Madre','3105090449','parejoynemar77@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(5,5,'MARTHA JOHANA','ROCHA RODRIGUEZ','Madre','3161944029','rochajohana429@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(6,6,'MARTA LILIANA DE','HOYOS MONTES','Madre','3022996001','luisco1990@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(7,7,'EDINSON JAVIER','DELGADO ZAPATA','Padre','3206962264','lospinguinitos22@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(8,8,'LEYDI YURANY','CARDONA','Madre','3127472919','wilsonalexanderflorezvera1@gamil.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(9,9,'JOHANY CAROLINA','ZERPA ARANDA','MADRE','3205894118','johanyzerpa@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(10,10,'YOHANA ANDREA','HENAO GOMEZ','MADRE','3015433421','henaoandrea328@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(11,11,'JORGE IVAN','HERNANDEZ MAZO','Padre','3117787938','angelyoryi9114@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(12,12,'MARIA DE LOS','ANGELES DAVID RIOS','Madre','3135069818','mariaangeldr@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(13,15,'VERONICA VANEGAS','JARAMILLO','MADRE','3216880183','jaramilloveronica888@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(14,16,'BEATRIZ EUGENIA','REINOSA','MADRE','3015677757','beatrizeugeniareinosa@hotmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(15,17,'DANIEL DARIO','MORALES PEREZ','Padre','3145849194','mayemisal@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(16,18,'LUIS FERNANDO','PLATA ÁLVAREZ','Padre','3126947059','darluis1811@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(17,20,'RAQUEL YELTIZA','FERNANDEZ SEGOVIA','MAMA','3225331945','yelitzasegoviaraquel@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(18,21,'ANGEE LILLEY','CANO GIL','Madre','3116938400','canoangee@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(19,22,'BEATRIZ ELENA','ARBELAEZ ARBOLEDA','MADRE','3225586842','simonsalazar521@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(20,23,'NATALIA ANDREA','ROJAS RIVERA','Madre','3245079529','nataliaalejo6@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(21,24,'DANIELA ANDREA','VILLA SAMPEDRO','MAMA','3002392309','','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(22,25,'DAYANA CARDONA','PEREZ','Madre','3238055910','danacardona0203@gmail.com','','2026-05-15 19:26:41','2026-05-15 19:45:36'),(35,14,'DANIEL SANTIAGO','MARULANDARUIZ','Padre','3216446348','','','2026-05-15 19:37:29','2026-05-15 19:45:36'),(92,26,'ISABEL','OSPINA','Madre','31333036789','mdiana1013@gmail.com','','2026-05-15 19:52:03','2026-05-15 19:52:03');
/*!40000 ALTER TABLE `acudientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docentes`
--

DROP TABLE IF EXISTS `docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `docentes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL DEFAULT 'Docente',
  `rol` varchar(20) NOT NULL DEFAULT 'docente',
  `correo` varchar(100) DEFAULT NULL,
  `pregunta_seguridad` varchar(80) DEFAULT NULL,
  `respuesta_seguridad_hash` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docentes`
--

LOCK TABLES `docentes` WRITE;
/*!40000 ALTER TABLE `docentes` DISABLE KEYS */;
INSERT INTO `docentes` VALUES (1,'admin','$2y$10$grrjr/tNLvezOzDUuwSBJOCy9zjHjTCHXD.aVYfMvQXK/3bSLpft.','Administrador','Principal','administrador','admin@ieaea.edu.co',NULL,NULL,1,'2026-05-15 18:21:19'),(2,'carlos','$2y$10$S3X1s5JJEN1qjsr8PWLLXudrmAa6zkRlHsUhfPw2w845hvQ1nXob6','CARLOS','MARTINEZ','docente','carlos.martinez@alzate.edu.co','escuela','$2y$10$XO2a/63OG0xohifaWZY2huf68usoHUDOExf.EoWpxgnqoSvKoFXaK',1,'2026-05-15 19:00:20');
/*!40000 ALTER TABLE `docentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `numero_matricula` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_matricula` (`numero_matricula`),
  KEY `idx_estudiantes_activo` (`activo`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (1,'MELANIE','ARIAS ALVAREZ','1023651188',1,'2026-05-15 18:21:19'),(2,'DYLAN ENMANUEL','BRICEÑO NUÑEZ','5160715',1,'2026-05-15 18:21:19'),(3,'VALENTINA','CARMONA SANCHEZ','1020313061',1,'2026-05-15 18:21:19'),(4,'CHRISTOPHER JESUS','CASTILLO PAREJO','N77404556047',1,'2026-05-15 18:21:19'),(5,'JUANITA SOFIA','CASTRO ROCHA','1073706405',1,'2026-05-15 18:21:19'),(6,'MATEO','COCHERO DE HOYOS','1013355879',1,'2026-05-15 18:21:19'),(7,'ANTHONY','DELGADO PINO','1023650437',1,'2026-05-15 18:21:19'),(8,'PAULINA','FLOREZ CARDONA','1023534618',1,'2026-05-15 18:21:19'),(9,'KRISTHOFER ALEXANDER','GORDONES ZERPA','7896357',1,'2026-05-15 18:21:19'),(10,'DANIEL','HENAO GOMEZ','1013354966',1,'2026-05-15 18:21:19'),(11,'MIGUEL ANGEL','HERNANDEZ ALVAREZ','1042154844',1,'2026-05-15 18:21:19'),(12,'JUAN CAMILO','JARAMILLO DAVID','1041635447',1,'2026-05-15 18:21:19'),(13,'JHOAN ENRIQUE','MARTINEZ OSORIO','3069',1,'2026-05-15 18:21:19'),(14,'NICOLAS','MARULANDA AVENDAÑO','1023650752',1,'2026-05-15 18:21:19'),(15,'MATHIAS','MIRANDA VANEGAS','1121336028',1,'2026-05-15 18:21:19'),(16,'MARIANGEL','MONSALVE REINOSA','1020317225',1,'2026-05-15 18:21:19'),(17,'DANIELA','MORALES PEREZ','1027813307',1,'2026-05-15 18:21:19'),(18,'DARWIN','PLATA RODRIGUEZ','1068435821',1,'2026-05-15 18:21:19'),(19,'MIGUEL ANGEL','QUINTERO MENESES','222204',1,'2026-05-15 18:21:19'),(20,'RASHEL JOHANA','REYES FERNANDEZ','6522135',1,'2026-05-15 18:21:19'),(21,'TAHIRA','RUEDA CANO','1011413092',1,'2026-05-15 18:21:19'),(22,'NICOLAS','SEPULVEDA ARBELAEZ','1032026114',1,'2026-05-15 18:21:19'),(23,'ALEJANDRO','SERNA ROJAS','1021937296',1,'2026-05-15 18:21:19'),(24,'ANTHONY','VILLA SANPEDRO','1023533486',1,'2026-05-15 18:21:19'),(25,'VALERIN','VILLEGAS CARDONA','1023648769',1,'2026-05-15 18:21:19'),(26,'LUIS','OSPINA','123456',1,'2026-05-15 19:52:03');
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones_acudiente`
--

DROP TABLE IF EXISTS `notificaciones_acudiente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notificaciones_acudiente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registro_id` int(11) DEFAULT NULL,
  `estudiante_id` int(11) NOT NULL,
  `acudiente_id` int(11) DEFAULT NULL,
  `correo_destino` varchar(150) DEFAULT NULL,
  `asunto` varchar(255) NOT NULL,
  `mensaje` longtext NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notificacion_registro` (`registro_id`),
  KEY `idx_notificacion_estudiante` (`estudiante_id`),
  KEY `idx_notificacion_acudiente` (`acudiente_id`),
  CONSTRAINT `fk_notificacion_acudiente` FOREIGN KEY (`acudiente_id`) REFERENCES `acudientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_notificacion_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notificacion_registro` FOREIGN KEY (`registro_id`) REFERENCES `registros_disciplinarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones_acudiente`
--

LOCK TABLES `notificaciones_acudiente` WRITE;
/*!40000 ALTER TABLE `notificaciones_acudiente` DISABLE KEYS */;
INSERT INTO `notificaciones_acudiente` VALUES (1,1,26,92,'mdiana1013@gmail.com','Informe disciplinario de LUIS OSPINA','Señor(a) ISABEL OSPINA (Madre),\n\nPor medio de la presente se comparte el informe del estudiante LUIS OSPINA (Matrícula: 123456) con fecha 15/5/2026, 2:52:14 p. m..\n\nResumen del informe:\nFaltas tipo 1:\n- Hace comentarios inadecuados con temas fuera de contexto.\n- Juega en clase y/o cambia de puesto, lanza objetos, basuras, saliva dentro del aula.\n- Llega tarde al salón de clase sin autorización; situación que perturba el normal desarrollo de las clases, por lo que si es reiterativo pasará a tipo II.\n\nFaltas tipo 2:\n- Sin registros\n\nFaltas tipo 3:\n- Sin registros\n\nEstímulos:\n- Sin registros\n\nAgradecemos su acompañamiento y seguimiento del proceso formativo.\n\nAtentamente,\nDocente responsable','2026-05-15 19:52:39');
/*!40000 ALTER TABLE `notificaciones_acudiente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `registros_disciplinarios`
--

DROP TABLE IF EXISTS `registros_disciplinarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registros_disciplinarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estudiante_id` int(11) NOT NULL,
  `docente_id` int(11) DEFAULT NULL,
  `faltas_tipo1` longtext NOT NULL,
  `faltas_tipo2` longtext NOT NULL,
  `faltas_tipo3` longtext NOT NULL,
  `estimulos` longtext NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_registros_estudiante` (`estudiante_id`),
  KEY `idx_registros_docente` (`docente_id`),
  CONSTRAINT `fk_registro_docente` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_registro_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registros_disciplinarios`
--

LOCK TABLES `registros_disciplinarios` WRITE;
/*!40000 ALTER TABLE `registros_disciplinarios` DISABLE KEYS */;
INSERT INTO `registros_disciplinarios` VALUES (1,26,2,'[\"Hace comentarios inadecuados con temas fuera de contexto.\",\"Juega en clase y/o cambia de puesto, lanza objetos, basuras, saliva dentro del aula.\",\"Llega tarde al salón de clase sin autorización; situación que perturba el normal desarrollo de las clases, por lo que si es reiterativo pasará a tipo II.\"]','[]','[]','[]','2026-05-15 19:52:36');
/*!40000 ALTER TABLE `registros_disciplinarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'app_educativa_recuperada'
--

--
-- Dumping routines for database 'app_educativa_recuperada'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-15 15:00:09
