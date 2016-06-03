-- MySQL dump 10.13  Distrib 5.6.31, for Linux (x86_64)
--
-- Host: localhost    Database: proyectos
-- ------------------------------------------------------
-- Server version	5.6.29

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
-- Table structure for table `actividad`
--

DROP TABLE IF EXISTS `actividad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  PRIMARY KEY (`idActividad`),
  KEY `fk_actividad_avance_idx` (`avance`),
  KEY `fk_actividad_recurso_idx` (`recurso`),
  CONSTRAINT `fk_actividad_avance` FOREIGN KEY (`avance`) REFERENCES `actividad_avance_porcentaje` (`idAvance`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_actividad_recurso` FOREIGN KEY (`recurso`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividad`
--

LOCK TABLES `actividad` WRITE;
/*!40000 ALTER TABLE `actividad` DISABLE KEYS */;
/*!40000 ALTER TABLE `actividad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `actividad_avance_porcentaje`
--

DROP TABLE IF EXISTS `actividad_avance_porcentaje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actividad_avance_porcentaje` (
  `idAvance` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idAvance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividad_avance_porcentaje`
--

LOCK TABLES `actividad_avance_porcentaje` WRITE;
/*!40000 ALTER TABLE `actividad_avance_porcentaje` DISABLE KEYS */;
/*!40000 ALTER TABLE `actividad_avance_porcentaje` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_tipo`
--

DROP TABLE IF EXISTS `cliente_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cliente_tipo` (
  `idClienteTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idClienteTipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_tipo`
--

LOCK TABLES `cliente_tipo` WRITE;
/*!40000 ALTER TABLE `cliente_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES (1,'GPTR3S','Activo');
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingreso_tipo`
--

DROP TABLE IF EXISTS `ingreso_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingreso_tipo` (
  `idIngresoTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci DEFAULT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idIngresoTipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingreso_tipo`
--

LOCK TABLES `ingreso_tipo` WRITE;
/*!40000 ALTER TABLE `ingreso_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `ingreso_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objetivo`
--

DROP TABLE IF EXISTS `objetivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objetivo` (
  `idObjetivo` int(11) NOT NULL,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `comentarios` varchar(128) COLLATE utf8_spanish_ci DEFAULT NULL,
  `duracion` int(10) NOT NULL DEFAULT '0',
  `formatoDuracion` enum('Hora','Dia') COLLATE utf8_spanish_ci DEFAULT 'Dia',
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci DEFAULT 'Activo',
  `fechaInicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechFin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `proyecto` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idObjetivo`),
  KEY `fk_objetivo_Proyecto_idx` (`proyecto`),
  KEY `fk_objetivo_tipo_idx` (`tipo`),
  CONSTRAINT `fk_objetivo_Proyecto` FOREIGN KEY (`proyecto`) REFERENCES `proyecto` (`idProyecto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_objetivo_tipo` FOREIGN KEY (`tipo`) REFERENCES `objetivos_tipo` (`idObjetivosTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objetivo`
--

LOCK TABLES `objetivo` WRITE;
/*!40000 ALTER TABLE `objetivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `objetivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objetivos_tipo`
--

DROP TABLE IF EXISTS `objetivos_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objetivos_tipo` (
  `idObjetivosTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') COLLATE utf8_spanish_ci NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idObjetivosTipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objetivos_tipo`
--

LOCK TABLES `objetivos_tipo` WRITE;
/*!40000 ALTER TABLE `objetivos_tipo` DISABLE KEYS */;
/*!40000 ALTER TABLE `objetivos_tipo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecto`
--

DROP TABLE IF EXISTS `proyecto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecto`
--

LOCK TABLES `proyecto` WRITE;
/*!40000 ALTER TABLE `proyecto` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resultado`
--

DROP TABLE IF EXISTS `resultado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  KEY `fk_resultado_Objetivo_idx` (`objetivo`),
  CONSTRAINT `fk_resultado_Objetivo` FOREIGN KEY (`objetivo`) REFERENCES `objetivo` (`idObjetivo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resultado`
--

LOCK TABLES `resultado` WRITE;
/*!40000 ALTER TABLE `resultado` DISABLE KEYS */;
/*!40000 ALTER TABLE `resultado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
  PRIMARY KEY (`idUsuario`),
  KEY `fk_usuario_tipoIngreso_idx` (`tipoIngreso`),
  KEY `fk_usuario_tipoUsuario_idx` (`tipoUsuario`),
  CONSTRAINT `fk_usuario_tipoIngreso` FOREIGN KEY (`tipoIngreso`) REFERENCES `ingreso_tipo` (`idIngresoTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_tipoUsuario` FOREIGN KEY (`tipoUsuario`) REFERENCES `usuario_tipo` (`idUsuarioTipo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_tipo`
--

DROP TABLE IF EXISTS `usuario_tipo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_tipo` (
  `idUsuarioTipo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) COLLATE utf8_spanish_ci NOT NULL,
  `estado` enum('Activo','Inactivo') CHARACTER SET utf8 NOT NULL DEFAULT 'Activo',
  PRIMARY KEY (`idUsuarioTipo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_tipo`
--

LOCK TABLES `usuario_tipo` WRITE;
/*!40000 ALTER TABLE `usuario_tipo` DISABLE KEYS */;
INSERT INTO `usuario_tipo` VALUES (1,'Administrador','Activo'),(2,'Coordinador','Activo'),(3,'Lider Proyecto','Activo'),(4,'Colaborador','Activo');
/*!40000 ALTER TABLE `usuario_tipo` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-06-03 16:02:21
