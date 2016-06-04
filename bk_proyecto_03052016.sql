/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.6.17 : Database - proyecto
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`proyecto` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `proyecto`;

/*Table structure for table `actividad` */

DROP TABLE IF EXISTS `actividad`;

CREATE TABLE `actividad` (
  `idActividad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `duracion` int(11) NOT NULL DEFAULT '0',
  `tipoDuracion` enum('Hora','Dia') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Dia',
  `fechaInicio` datetime DEFAULT NULL,
  `fechaFin` datetime DEFAULT NULL,
  `predecesora` int(11) DEFAULT NULL,
  `recurso` int(11) DEFAULT NULL,
  `estado` varchar(45) COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  `estatus` enum('Asignado','Sin Avance','En Proceso','Cumplido') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Asignado',
  `avance` int(11) NOT NULL DEFAULT '1',
  `Resultado` int(11) DEFAULT NULL,
  PRIMARY KEY (`idActividad`),
  KEY `fk_actividad_avance_idx` (`avance`),
  KEY `fk_actividad_recurso_idx` (`recurso`),
  CONSTRAINT `fk_actividad_recurso` FOREIGN KEY (`recurso`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `actividad` */

LOCK TABLES `actividad` WRITE;

insert  into `actividad`(`idActividad`,`nombre`,`duracion`,`tipoDuracion`,`fechaInicio`,`fechaFin`,`predecesora`,`recurso`,`estado`,`estatus`,`avance`,`Resultado`) values (1,'Reunión inicial',1,'Dia','2016-06-04 00:00:00','2016-06-04 00:00:00',NULL,2,'Activo','Asignado',5,1),(2,'Contitución del Proyecto',4,'Dia','2016-06-06 00:00:00','2016-06-09 00:00:00',1,2,'Activo','Asignado',5,1);

UNLOCK TABLES;

/*Table structure for table `actividad_avance_porcentaje` */

DROP TABLE IF EXISTS `actividad_avance_porcentaje`;

CREATE TABLE `actividad_avance_porcentaje` (
  `idAvance` int(11) NOT NULL AUTO_INCREMENT,
  `cantidad` int(11) NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idAvance`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `actividad_avance_porcentaje` */

LOCK TABLES `actividad_avance_porcentaje` WRITE;

insert  into `actividad_avance_porcentaje`(`idAvance`,`cantidad`,`estado`) values (1,25,'Activo'),(2,50,'Activo'),(3,75,'Activo'),(4,100,'Activo'),(5,0,'Activo');

UNLOCK TABLES;

/*Table structure for table `audittrail` */

DROP TABLE IF EXISTS `audittrail`;

CREATE TABLE `audittrail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  `script` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `table` varchar(255) DEFAULT NULL,
  `field` varchar(255) DEFAULT NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;

/*Data for the table `audittrail` */

LOCK TABLES `audittrail` WRITE;

insert  into `audittrail`(`id`,`datetime`,`script`,`user`,`action`,`table`,`field`,`keyvalue`,`oldvalue`,`newvalue`) values (1,'2016-06-04 06:41:22','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','cantidad','1','','25'),(2,'2016-06-04 06:41:22','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','estado','1','','Activo'),(3,'2016-06-04 06:41:22','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','idAvance','1','','1'),(4,'2016-06-04 06:41:33','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','cantidad','2','','50'),(5,'2016-06-04 06:41:33','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','estado','2','','Activo'),(6,'2016-06-04 06:41:33','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','idAvance','2','','2'),(7,'2016-06-04 06:41:40','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','cantidad','3','','75'),(8,'2016-06-04 06:41:40','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','estado','3','','Activo'),(9,'2016-06-04 06:41:40','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','idAvance','3','','3'),(10,'2016-06-04 06:41:49','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','cantidad','4','','100'),(11,'2016-06-04 06:41:49','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','estado','4','','Activo'),(12,'2016-06-04 06:41:49','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','idAvance','4','','4'),(13,'2016-06-04 06:42:23','/gestion/cliente_tipoadd.php','-1','A','cliente_tipo','nombre','1','','Internacional'),(14,'2016-06-04 06:42:23','/gestion/cliente_tipoadd.php','-1','A','cliente_tipo','idClienteTipo','1','','1'),(15,'2016-06-04 06:43:56','/gestion/ingreso_tipoadd.php','-1','A','ingreso_tipo','nombre','1','','Prorrateo'),(16,'2016-06-04 06:43:56','/gestion/ingreso_tipoadd.php','-1','A','ingreso_tipo','idIngresoTipo','1','','1'),(17,'2016-06-04 06:44:07','/gestion/ingreso_tipoadd.php','-1','A','ingreso_tipo','nombre','2','','Estándar'),(18,'2016-06-04 06:44:07','/gestion/ingreso_tipoadd.php','-1','A','ingreso_tipo','idIngresoTipo','2','','2'),(19,'2016-06-04 06:44:37','/gestion/objetivos_tipoadd.php','-1','A','objetivos_tipo','nombre','1','','General'),(20,'2016-06-04 06:44:37','/gestion/objetivos_tipoadd.php','-1','A','objetivos_tipo','idObjetivosTipo','1','','1'),(21,'2016-06-04 06:44:46','/gestion/objetivos_tipoadd.php','-1','A','objetivos_tipo','nombre','2','','Especifico'),(22,'2016-06-04 06:44:46','/gestion/objetivos_tipoadd.php','-1','A','objetivos_tipo','idObjetivosTipo','2','','2'),(23,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','nit','1','','C/F'),(24,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','cliente','1','','International Found'),(25,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','direccion','1','','Guatemala'),(26,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','telefono','1','','24242424'),(27,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','tipo','1','','1'),(28,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','descripcion','1','','Fundación de Partido Patriota'),(29,'2016-06-04 06:46:03','/gestion/clienteadd.php','-1','A','cliente','idCliente','1','','1'),(30,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','usuario','1','','jramirez'),(31,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','password','1','','********'),(32,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','nombres','1','','Juan Carlos'),(33,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','apellidos','1','','Ramírez'),(34,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','direccion','1','','Guatemala'),(35,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','telefonos','1','','55555555'),(36,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','tipoUsuario','1','','1'),(37,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','tipoIngreso','1','','1'),(38,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','grupo','1','',NULL),(39,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','etiquetas','1','',NULL),(40,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','iniciales','1','','JR'),(41,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','sueldo','1','','15000.00'),(42,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','tipoSueldo','1','','Mes'),(43,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','horaExtra','1','',NULL),(44,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','empresa','1','','1'),(45,'2016-06-04 06:50:09','/gestion/usuarioadd.php','-1','A','usuario','idUsuario','1','','1'),(46,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','usuario','2','','rmunoz'),(47,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','password','2','','********'),(48,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','nombres','2','','Romeo'),(49,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','apellidos','2','','Muñoz'),(50,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','direccion','2','','Guatemala'),(51,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','telefonos','2','','55555556'),(52,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','tipoUsuario','2','','1'),(53,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','tipoIngreso','2','','1'),(54,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','grupo','2','',NULL),(55,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','etiquetas','2','',NULL),(56,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','iniciales','2','','RM'),(57,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','sueldo','2','','15000.00'),(58,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','tipoSueldo','2','','Mes'),(59,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','horaExtra','2','',NULL),(60,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','empresa','2','','1'),(61,'2016-06-04 06:50:53','/gestion/usuarioadd.php','-1','A','usuario','idUsuario','2','','2'),(62,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','usuario','3','','losorio'),(63,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','password','3','','********'),(64,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','nombres','3','','Liliana'),(65,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','apellidos','3','','Osorio'),(66,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','direccion','3','','Guatemala'),(67,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','telefonos','3','','55555556'),(68,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','tipoUsuario','3','','1'),(69,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','tipoIngreso','3','','1'),(70,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','grupo','3','',NULL),(71,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','etiquetas','3','',NULL),(72,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','iniciales','3','','LO'),(73,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','sueldo','3','','15000.00'),(74,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','tipoSueldo','3','','Mes'),(75,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','horaExtra','3','',NULL),(76,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','empresa','3','','1'),(77,'2016-06-04 06:51:37','/gestion/usuarioadd.php','-1','A','usuario','idUsuario','3','','3'),(78,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','usuario','4','','millescas'),(79,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','password','4','','********'),(80,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','nombres','4','','Marvin'),(81,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','apellidos','4','','Illescas'),(82,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','direccion','4','','Guatemala'),(83,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','telefonos','4','','55555558'),(84,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','tipoUsuario','4','','1'),(85,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','tipoIngreso','4','','1'),(86,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','grupo','4','',NULL),(87,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','etiquetas','4','',NULL),(88,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','iniciales','4','','MI'),(89,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','sueldo','4','','15000.00'),(90,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','tipoSueldo','4','','Mes'),(91,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','horaExtra','4','',NULL),(92,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','empresa','4','','1'),(93,'2016-06-04 06:52:17','/gestion/usuarioadd.php','-1','A','usuario','idUsuario','4','','4'),(94,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','usuario','5','','coordinador'),(95,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','password','5','','********'),(96,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','nombres','5','','Coordinador'),(97,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','apellidos','5','','I.S.'),(98,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','direccion','5','','Guatemala'),(99,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','telefonos','5','','11111111'),(100,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','tipoUsuario','5','','1'),(101,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','tipoIngreso','5','','1'),(102,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','grupo','5','',NULL),(103,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','etiquetas','5','',NULL),(104,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','iniciales','5','','JR'),(105,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','sueldo','5','','30000.00'),(106,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','tipoSueldo','5','','Mes'),(107,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','horaExtra','5','',NULL),(108,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','empresa','5','','1'),(109,'2016-06-04 06:53:18','/gestion/usuarioadd.php','-1','A','usuario','idUsuario','5','','5'),(110,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','nombre','1','','InterFound'),(111,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','descripcion','1','','Proyecto para el control de donaciones'),(112,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','fechaInicio','1','','2016-06-01'),(113,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','fechaFin','1','','2016-06-30'),(114,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','usuarioLider','1','','3'),(115,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','usuarioEncargado','1','','5'),(116,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','cliente','1','','1'),(117,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','prioridad','1','','Estándar'),(118,'2016-06-04 06:54:26','/gestion/proyectoadd.php','-1','A','proyecto','idProyecto','1','','1'),(119,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','nombre','1','','Consensuar Proyecto'),(120,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','comentarios','1','','Reunión para iniciar proyecto'),(121,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','duracion','1','','2'),(122,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','fechaInicio','1','','2016-06-01'),(123,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','fechFin','1','','2016-06-02'),(124,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','proyecto','1','','1'),(125,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','tipo','1','','2'),(126,'2016-06-04 07:07:42','/gestion/objetivoadd.php','-1','A','objetivo','idObjetivo','1','','1'),(127,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','objetivo','1','','1'),(128,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','nombre','1','','Definición de Proyecto'),(129,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','tiempoEstimado','1','','10'),(130,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','tiempoTipo','1','','Dia'),(131,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','fechaInicio','1','','2016-06-06'),(132,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','fechaFin','1','','2016-06-17'),(133,'2016-06-04 07:08:50','/gestion/resultadoadd.php','-1','A','resultado','idResultado','1','','1'),(134,'2016-06-04 07:40:46','/gestion/usuarioedit.php','-1','U','usuario','tipoUsuario','2','1','4'),(135,'2016-06-04 07:41:00','/gestion/usuarioedit.php','-1','U','usuario','tipoUsuario','4','1','4'),(136,'2016-06-04 07:43:18','/gestion/usuarioedit.php','-1','U','usuario','tipoUsuario','3','1','3'),(137,'2016-06-04 07:43:31','/gestion/usuarioedit.php','-1','U','usuario','tipoUsuario','5','1','2'),(138,'2016-06-04 07:55:34','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','cantidad','5','','0'),(139,'2016-06-04 07:55:34','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','estado','5','','Activo'),(140,'2016-06-04 07:55:34','/gestion/actividad_avance_porcentajeadd.php','-1','A','actividad_avance_porcentaje','idAvance','5','','5'),(141,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','avance','1','','5'),(142,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','nombre','1','','Reunión inicial'),(143,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','duracion','1','','1'),(144,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','tipoDuracion','1','','Dia'),(145,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','fechaInicio','1','','2016-06-04'),(146,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','fechaFin','1','','2016-06-04'),(147,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','predecesora','1','',NULL),(148,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','recurso','1','','2'),(149,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','estatus','1','','Asignado'),(150,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','Resultado','1','','1'),(151,'2016-06-04 07:56:18','/gestion/actividadlist.php','-1','A','actividad','idActividad','1','','1'),(152,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','avance','2','','5'),(153,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','nombre','2','','Contitución del Proyecto'),(154,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','duracion','2','','4'),(155,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','tipoDuracion','2','','Dia'),(156,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','fechaInicio','2','','2016-06-06'),(157,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','fechaFin','2','','2016-06-09'),(158,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','predecesora','2','','1'),(159,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','recurso','2','','2'),(160,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','estatus','2','','Asignado'),(161,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','Resultado','2','','1'),(162,'2016-06-04 07:58:00','/gestion/actividadlist.php','-1','A','actividad','idActividad','2','','2'),(163,'2016-06-04 08:17:59','/gestion/usuarioedit.php','-1','U','usuario','password','2','********','********'),(164,'2016-06-04 08:17:59','/gestion/usuarioedit.php','-1','U','usuario','userlevelid','2',NULL,'6'),(165,'2016-06-04 08:18:28','/gestion/usuarioedit.php','-1','U','usuario','password','1','********','********'),(166,'2016-06-04 08:18:28','/gestion/usuarioedit.php','-1','U','usuario','userlevelid','1',NULL,'-1'),(167,'2016-06-04 08:18:46','/gestion/usuarioedit.php','-1','U','usuario','password','3','********','********'),(168,'2016-06-04 08:18:46','/gestion/usuarioedit.php','-1','U','usuario','userlevelid','3',NULL,'5'),(169,'2016-06-04 08:19:10','/gestion/usuarioedit.php','-1','U','usuario','password','4','********','********'),(170,'2016-06-04 08:19:10','/gestion/usuarioedit.php','-1','U','usuario','userlevelid','4',NULL,'6'),(171,'2016-06-04 08:19:29','/gestion/usuarioedit.php','-1','U','usuario','password','5','********','********'),(172,'2016-06-04 08:19:29','/gestion/usuarioedit.php','-1','U','usuario','userlevelid','5',NULL,'4');

UNLOCK TABLES;

/*Table structure for table `cliente` */

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `nit` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `cliente` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefono` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  `tipo` int(11) NOT NULL,
  `descripcion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`idCliente`),
  KEY `fk_cliente_tipo_idx` (`tipo`),
  CONSTRAINT `fk_cliente_tipo` FOREIGN KEY (`tipo`) REFERENCES `cliente_tipo` (`idClienteTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `cliente` */

LOCK TABLES `cliente` WRITE;

insert  into `cliente`(`idCliente`,`nit`,`cliente`,`direccion`,`telefono`,`estado`,`tipo`,`descripcion`) values (1,'C/F','International Found','Guatemala','24242424','Activo',1,'Fundación de Partido Patriota');

UNLOCK TABLES;

/*Table structure for table `cliente_tipo` */

DROP TABLE IF EXISTS `cliente_tipo`;

CREATE TABLE `cliente_tipo` (
  `idClienteTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idClienteTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `cliente_tipo` */

LOCK TABLES `cliente_tipo` WRITE;

insert  into `cliente_tipo`(`idClienteTipo`,`nombre`,`estado`) values (1,'Internacional','Activo');

UNLOCK TABLES;

/*Table structure for table `empresa` */

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `empresa` */

LOCK TABLES `empresa` WRITE;

insert  into `empresa`(`idEmpresa`,`nombre`,`estado`) values (1,'GPTR3S','Activo');

UNLOCK TABLES;

/*Table structure for table `ingreso_tipo` */

DROP TABLE IF EXISTS `ingreso_tipo`;

CREATE TABLE `ingreso_tipo` (
  `idIngresoTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idIngresoTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `ingreso_tipo` */

LOCK TABLES `ingreso_tipo` WRITE;

insert  into `ingreso_tipo`(`idIngresoTipo`,`nombre`,`estado`) values (1,'Prorrateo','Activo'),(2,'Estándar','Activo');

UNLOCK TABLES;

/*Table structure for table `objetivo` */

DROP TABLE IF EXISTS `objetivo`;

CREATE TABLE `objetivo` (
  `idObjetivo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `comentarios` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `duracion` int(10) DEFAULT '0',
  `formatoDuracion` enum('Hora','Dia') COLLATE utf8_spanish_ci DEFAULT 'Dia',
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci DEFAULT 'Activo',
  `fechaInicio` datetime NOT NULL,
  `fechFin` datetime NOT NULL,
  `proyecto` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idObjetivo`),
  KEY `fk_objetivo_Proyecto_idx` (`proyecto`),
  KEY `fk_objetivo_tipo_idx` (`tipo`),
  CONSTRAINT `fk_objetivo_Proyecto` FOREIGN KEY (`proyecto`) REFERENCES `proyecto` (`idProyecto`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `objetivo` */

LOCK TABLES `objetivo` WRITE;

insert  into `objetivo`(`idObjetivo`,`nombre`,`comentarios`,`duracion`,`formatoDuracion`,`estado`,`fechaInicio`,`fechFin`,`proyecto`,`tipo`) values (1,'Consensuar Proyecto','Reunión para iniciar proyecto',2,'Dia','Activo','2016-06-01 00:00:00','2016-06-02 00:00:00',1,2);

UNLOCK TABLES;

/*Table structure for table `objetivos_tipo` */

DROP TABLE IF EXISTS `objetivos_tipo`;

CREATE TABLE `objetivos_tipo` (
  `idObjetivosTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idObjetivosTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `objetivos_tipo` */

LOCK TABLES `objetivos_tipo` WRITE;

insert  into `objetivos_tipo`(`idObjetivosTipo`,`nombre`,`estado`) values (1,'General','Activo'),(2,'Especifico','Activo');

UNLOCK TABLES;

/*Table structure for table `proyecto` */

DROP TABLE IF EXISTS `proyecto`;

CREATE TABLE `proyecto` (
  `idProyecto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `fechaInicio` datetime DEFAULT NULL,
  `fechaFin` datetime DEFAULT NULL,
  `fechaCreacion` datetime DEFAULT NULL,
  `usuarioCreacion` int(11) DEFAULT NULL,
  `usuarioLider` int(11) NOT NULL,
  `usuarioEncargado` int(11) NOT NULL,
  `cliente` int(11) NOT NULL,
  `prioridad` enum('Alta','Estándar','Baja') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Estándar',
  `fechaUltimoAcceso` datetime DEFAULT NULL,
  `fechaModificacion` datetime DEFAULT NULL,
  `usuarioModificacion` int(11) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL,
  `estatus` enum('Creado','Asignado','En Proceso','Finalizado') COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`idProyecto`),
  KEY `fk_proyecto_Encargado_idx` (`usuarioEncargado`),
  KEY `fk_proyecto_Lider_idx` (`usuarioLider`),
  KEY `fk_proyecto_Cliente_idx` (`cliente`),
  CONSTRAINT `fk_proyecto_Cliente` FOREIGN KEY (`cliente`) REFERENCES `cliente` (`idCliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_proyecto_Encargado` FOREIGN KEY (`usuarioEncargado`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_proyecto_Lider` FOREIGN KEY (`usuarioLider`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `proyecto` */

LOCK TABLES `proyecto` WRITE;

insert  into `proyecto`(`idProyecto`,`nombre`,`descripcion`,`fechaInicio`,`fechaFin`,`fechaCreacion`,`usuarioCreacion`,`usuarioLider`,`usuarioEncargado`,`cliente`,`prioridad`,`fechaUltimoAcceso`,`fechaModificacion`,`usuarioModificacion`,`estado`,`estatus`) values (1,'InterFound','Proyecto para el control de donaciones','2016-06-01 00:00:00','2016-06-30 00:00:00',NULL,NULL,3,5,1,'Estándar',NULL,NULL,NULL,'Activo','Creado');

UNLOCK TABLES;

/*Table structure for table `resultado` */

DROP TABLE IF EXISTS `resultado`;

CREATE TABLE `resultado` (
  `idResultado` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  `objetivo` int(11) NOT NULL,
  `tiempoEstimado` int(11) NOT NULL DEFAULT '0',
  `tiempoTipo` enum('Hora','Dia') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Dia',
  `fechaInicio` datetime DEFAULT NULL,
  `fechaFin` datetime DEFAULT NULL,
  `estatus` enum('Sin Avance','En Proceso','Cumplido') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Sin Avance',
  PRIMARY KEY (`idResultado`),
  KEY `fk_resultado_Objetivo_idx` (`objetivo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `resultado` */

LOCK TABLES `resultado` WRITE;

insert  into `resultado`(`idResultado`,`nombre`,`estado`,`objetivo`,`tiempoEstimado`,`tiempoTipo`,`fechaInicio`,`fechaFin`,`estatus`) values (1,'Definición de Proyecto','Activo',1,10,'Dia','2016-06-06 00:00:00','2016-06-17 00:00:00','Sin Avance');

UNLOCK TABLES;

/*Table structure for table `userlevelpermissions` */

DROP TABLE IF EXISTS `userlevelpermissions`;

CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `userlevelpermissions` */

LOCK TABLES `userlevelpermissions` WRITE;

insert  into `userlevelpermissions`(`userlevelid`,`tablename`,`permission`) values (-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}audittrail',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevelpermissions',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevels',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario',0),(-2,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo',0),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}audittrail',0),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevelpermissions',0),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevels',0),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario',109),(4,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje',104),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}audittrail',0),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo',0),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto',104),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado',109),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevelpermissions',0),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevels',0),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario',104),(5,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo',109),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad',108),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}actividad_avance_porcentaje',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}audittrail',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}cliente_tipo',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}empresa',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}ingreso_tipo',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivo',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}objetivos_tipo',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}proyecto',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}resultado',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevelpermissions',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}userlevels',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario',0),(6,'{4744AE7F-D0AE-4DE0-9BB4-73B8ABE765D6}usuario_tipo',0);

UNLOCK TABLES;

/*Table structure for table `userlevels` */

DROP TABLE IF EXISTS `userlevels`;

CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `userlevels` */

LOCK TABLES `userlevels` WRITE;

insert  into `userlevels`(`userlevelid`,`userlevelname`) values (-2,'Anonymous'),(-1,'Administrator'),(0,'Default'),(4,'Coordinador'),(5,'Lider'),(6,'Colaborador');

UNLOCK TABLES;

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `password` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `nombres` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `apellidos` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `direccion` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `telefonos` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci DEFAULT 'Activo',
  `tipoUsuario` int(11) DEFAULT NULL,
  `tipoIngreso` int(11) DEFAULT NULL,
  `grupo` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `etiquetas` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `iniciales` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `sueldo` decimal(10,2) DEFAULT '0.00',
  `tipoSueldo` enum('Hora','Dia','Quincena','Mes','Trimestre','Semestre','Año','Otro') COLLATE utf8_spanish_ci DEFAULT 'Mes',
  `horaExtra` decimal(10,2) DEFAULT NULL,
  `empresa` int(11) DEFAULT NULL,
  `userlevelid` int(11) DEFAULT NULL,
  PRIMARY KEY (`idUsuario`),
  KEY `fk_usuario_tipoIngreso_idx` (`tipoIngreso`),
  KEY `fk_usuario_tipoUsuario_idx` (`tipoUsuario`),
  CONSTRAINT `fk_usuario_tipoIngreso` FOREIGN KEY (`tipoIngreso`) REFERENCES `ingreso_tipo` (`idIngresoTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_tipoUsuario` FOREIGN KEY (`tipoUsuario`) REFERENCES `usuario_tipo` (`idUsuarioTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `usuario` */

LOCK TABLES `usuario` WRITE;

insert  into `usuario`(`idUsuario`,`usuario`,`password`,`nombres`,`apellidos`,`direccion`,`telefonos`,`estado`,`tipoUsuario`,`tipoIngreso`,`grupo`,`etiquetas`,`iniciales`,`sueldo`,`tipoSueldo`,`horaExtra`,`empresa`,`userlevelid`) values (1,'jramirez','e10adc3949ba59abbe56e057f20f883e','Juan Carlos','Ramírez','Guatemala','55555555','Activo',1,1,NULL,NULL,'JR',15000.00,'Mes',NULL,1,-1),(2,'rmunoz','e10adc3949ba59abbe56e057f20f883e','Romeo','Muñoz','Guatemala','55555556','Activo',4,1,NULL,NULL,'RM',15000.00,'Mes',NULL,1,6),(3,'losorio','e10adc3949ba59abbe56e057f20f883e','Liliana','Osorio','Guatemala','55555556','Activo',3,1,NULL,NULL,'LO',15000.00,'Mes',NULL,1,5),(4,'millescas','e10adc3949ba59abbe56e057f20f883e','Marvin','Illescas','Guatemala','55555558','Activo',4,1,NULL,NULL,'MI',15000.00,'Mes',NULL,1,6),(5,'coordinador','e10adc3949ba59abbe56e057f20f883e','Coordinador','I.S.','Guatemala','11111111','Activo',2,1,NULL,NULL,'JR',30000.00,'Mes',NULL,1,4);

UNLOCK TABLES;

/*Table structure for table `usuario_tipo` */

DROP TABLE IF EXISTS `usuario_tipo`;

CREATE TABLE `usuario_tipo` (
  `idUsuarioTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8 NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idUsuarioTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

/*Data for the table `usuario_tipo` */

LOCK TABLES `usuario_tipo` WRITE;

insert  into `usuario_tipo`(`idUsuarioTipo`,`nombre`,`estado`) values (1,'Administrador','Activo'),(2,'Coordinador','Activo'),(3,'Lider Proyecto','Activo'),(4,'Colaborador','Activo');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
