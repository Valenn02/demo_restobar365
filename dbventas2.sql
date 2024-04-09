-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: dbventas2
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

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
-- Current Database: `dbventas2`
--

-- CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dbventas2` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

-- USE `dbventas2`;

--
-- Table structure for table `almacens`
--

DROP TABLE IF EXISTS `almacens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `almacens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_almacen` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ubicacion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encargado` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lugar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacion` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `almacens_nombre_almacen_unique` (`nombre_almacen`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacens`
--

LOCK TABLES `almacens` WRITE;
/*!40000 ALTER TABLE `almacens` DISABLE KEYS */;
INSERT INTO `almacens` VALUES (1,'General','Calle heroinas','Root','4251252','Cochabamba','NInguna',1,'2023-08-08 16:13:52','2023-08-08 16:13:52');
/*!40000 ALTER TABLE `almacens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `arqueo_cajas`
--

DROP TABLE IF EXISTS `arqueo_cajas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `arqueo_cajas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcaja` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `billete200` int(11) NOT NULL,
  `billete100` int(11) NOT NULL,
  `billete50` int(11) NOT NULL,
  `billete20` int(11) NOT NULL,
  `billete10` int(11) NOT NULL,
  `moneda5` int(11) NOT NULL,
  `moneda2` int(11) NOT NULL,
  `moneda1` int(11) NOT NULL,
  `moneda050` int(11) NOT NULL,
  `moneda020` int(11) NOT NULL,
  `moneda010` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `arqueo_cajas_idcaja_foreign` (`idcaja`),
  KEY `arqueo_cajas_idusuario_foreign` (`idusuario`),
  CONSTRAINT `arqueo_cajas_idcaja_foreign` FOREIGN KEY (`idcaja`) REFERENCES `cajas` (`id`),
  CONSTRAINT `arqueo_cajas_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arqueo_cajas`
--

LOCK TABLES `arqueo_cajas` WRITE;
/*!40000 ALTER TABLE `arqueo_cajas` DISABLE KEYS */;
/*!40000 ALTER TABLE `arqueo_cajas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articulos`
--

DROP TABLE IF EXISTS `articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articulos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcategoria` int(10) unsigned NOT NULL,
  `idgrupo` int(10) unsigned NOT NULL,
  `idproveedor` int(10) unsigned NOT NULL,
  `idmedida` int(10) unsigned NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_generico` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidad_envase` int(11) NOT NULL,
  `precio_list_unid` decimal(11,2) DEFAULT NULL,
  `precio_costo_unid` decimal(11,2) NOT NULL,
  `precio_costo_paq` decimal(11,2) NOT NULL,
  `precio_venta` decimal(11,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `costo_compra` decimal(10,2) NOT NULL,
  `fotografia` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `idmarca` int(10) unsigned NOT NULL,
  `idindustria` int(10) unsigned NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articulos_nombre_unique` (`nombre`),
  KEY `articulos_idcategoria_foreign` (`idcategoria`),
  KEY `articulos_idgrupo_foreign` (`idgrupo`),
  KEY `articulos_idproveedor_foreign` (`idproveedor`),
  KEY `articulos_idmedida_foreign` (`idmedida`),
  KEY `articulos_idmarca_foreign` (`idmarca`),
  KEY `articulos_idindustria_foreign` (`idindustria`),
  CONSTRAINT `articulos_idcategoria_foreign` FOREIGN KEY (`idcategoria`) REFERENCES `categorias` (`id`),
  CONSTRAINT `articulos_idgrupo_foreign` FOREIGN KEY (`idgrupo`) REFERENCES `grupos` (`id`),
  CONSTRAINT `articulos_idindustria_foreign` FOREIGN KEY (`idindustria`) REFERENCES `industrias` (`id`),
  CONSTRAINT `articulos_idmarca_foreign` FOREIGN KEY (`idmarca`) REFERENCES `marcas` (`id`),
  CONSTRAINT `articulos_idmedida_foreign` FOREIGN KEY (`idmedida`) REFERENCES `medidas` (`id`),
  CONSTRAINT `articulos_idproveedor_foreign` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articulos`
--

LOCK TABLES `articulos` WRITE;
/*!40000 ALTER TABLE `articulos` DISABLE KEYS */;
INSERT INTO `articulos` VALUES (1,1,1,2,4,'15615','Vacuna Jhonsson','Jhonsson',10,NULL,15.00,150.00,16.00,35,'Vacuna contra el covid',1,'2023-08-08 16:12:11','2023-08-08 16:14:37',14.00,'vacuna-jhonsson.jpeg',183,2,'2023-08-25');
/*!40000 ALTER TABLE `articulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cajas`
--

DROP TABLE IF EXISTS `cajas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cajas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idsucursal` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `fechaApertura` datetime NOT NULL,
  `fechaCierre` datetime DEFAULT NULL,
  `saldoInicial` decimal(11,2) NOT NULL,
  `depositos` decimal(11,2) NOT NULL DEFAULT 0.00,
  `salidas` decimal(11,2) NOT NULL DEFAULT 0.00,
  `ventasContado` decimal(11,2) NOT NULL DEFAULT 0.00,
  `ventasCredito` decimal(11,2) NOT NULL DEFAULT 0.00,
  `comprasContado` decimal(11,2) NOT NULL DEFAULT 0.00,
  `comprasCredito` decimal(11,2) NOT NULL DEFAULT 0.00,
  `saldoFaltante` decimal(11,2) DEFAULT NULL,
  `saldoSobrante` decimal(11,2) NOT NULL DEFAULT 0.00,
  `saldoCaja` decimal(11,2) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cajas_idsucursal_foreign` (`idsucursal`),
  KEY `cajas_idusuario_foreign` (`idusuario`),
  CONSTRAINT `cajas_idsucursal_foreign` FOREIGN KEY (`idsucursal`) REFERENCES `sucursales` (`id`),
  CONSTRAINT `cajas_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cajas`
--

LOCK TABLES `cajas` WRITE;
/*!40000 ALTER TABLE `cajas` DISABLE KEYS */;
INSERT INTO `cajas` VALUES (1,1,1,'2023-08-08 11:36:56',NULL,5000.00,0.00,0.00,717.00,0.00,225.00,0.00,NULL,0.00,NULL,1,'2023-08-08 15:36:56','2023-08-10 18:54:09');
/*!40000 ALTER TABLE `cajas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigoProductoSin` int(11) DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Accesorios para Computadoras','Teclados, monitores, mouse, audífonos',45290,1,'2023-08-08 15:49:42','2023-08-08 15:49:46'),(2,'Consolas de Videojuegos','Diferentes consolas, PlayStation, Xbox, Nintendo',38581,1,'2023-08-08 15:50:57','2023-08-08 15:50:57');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_trabajos`
--

DROP TABLE IF EXISTS `configuracion_trabajos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuracion_trabajos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gestion` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigoProductos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consultasAlmacenes` tinyint(1) NOT NULL DEFAULT 1,
  `limiteDescuento` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maximoDescuento` decimal(6,2) DEFAULT NULL,
  `valuacionInventario` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `backupAutomatico` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rutaBackup` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldosNegativos` tinyint(1) NOT NULL DEFAULT 0,
  `monedaTrabajo` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `separadorDecimales` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mostrarCostos` tinyint(1) NOT NULL DEFAULT 1,
  `mostrarProveedores` tinyint(1) NOT NULL DEFAULT 1,
  `mostrarSaldosStock` tinyint(1) NOT NULL DEFAULT 1,
  `actualizarIva` tinyint(1) NOT NULL DEFAULT 1,
  `vendedorAsignado` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permitirDevolucion` tinyint(1) NOT NULL DEFAULT 0,
  `editarNroDoc` tinyint(1) NOT NULL DEFAULT 0,
  `registroClienteObligatorio` tinyint(1) NOT NULL DEFAULT 1,
  `buscarClientePorCodigo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracion_trabajos_gestion_unique` (`gestion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_trabajos`
--

LOCK TABLES `configuracion_trabajos` WRITE;
/*!40000 ALTER TABLE `configuracion_trabajos` DISABLE KEYS */;
INSERT INTO `configuracion_trabajos` VALUES (1,'2023','00000',1,'Precio mayorista',0.00,'Ninguno','Nunca','D:\\laravel\\backups',0,'Bs','Punto',1,1,1,1,'No permite cambiar el vendedor',0,0,1,1,NULL,'2023-07-17 23:04:23');
/*!40000 ALTER TABLE `configuracion_trabajos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cotizacion_venta`
--

DROP TABLE IF EXISTS `cotizacion_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cotizacion_venta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcliente` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validez` datetime DEFAULT NULL,
  `plazo_entrega` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tiempo_entrega` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar_entrega` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forma_pago` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nota` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_venta_idcliente_foreign` (`idcliente`),
  KEY `cotizacion_venta_idusuario_foreign` (`idusuario`),
  CONSTRAINT `cotizacion_venta_idcliente_foreign` FOREIGN KEY (`idcliente`) REFERENCES `personas` (`id`),
  CONSTRAINT `cotizacion_venta_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cotizacion_venta`
--

LOCK TABLES `cotizacion_venta` WRITE;
/*!40000 ALTER TABLE `cotizacion_venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `cotizacion_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_cotizacion`
--

DROP TABLE IF EXISTS `detalle_cotizacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_cotizacion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcotizacion` int(10) unsigned NOT NULL,
  `idarticulo` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_cotizacion_idcotizacion_foreign` (`idcotizacion`),
  KEY `detalle_cotizacion_idarticulo_foreign` (`idarticulo`),
  CONSTRAINT `detalle_cotizacion_idarticulo_foreign` FOREIGN KEY (`idarticulo`) REFERENCES `articulos` (`id`),
  CONSTRAINT `detalle_cotizacion_idcotizacion_foreign` FOREIGN KEY (`idcotizacion`) REFERENCES `cotizacion_venta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_cotizacion`
--

LOCK TABLES `detalle_cotizacion` WRITE;
/*!40000 ALTER TABLE `detalle_cotizacion` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_cotizacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_ingresos`
--

DROP TABLE IF EXISTS `detalle_ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_ingresos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idingreso` int(10) unsigned NOT NULL,
  `idarticulo` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_ingresos_idingreso_foreign` (`idingreso`),
  KEY `detalle_ingresos_idarticulo_foreign` (`idarticulo`),
  CONSTRAINT `detalle_ingresos_idarticulo_foreign` FOREIGN KEY (`idarticulo`) REFERENCES `articulos` (`id`),
  CONSTRAINT `detalle_ingresos_idingreso_foreign` FOREIGN KEY (`idingreso`) REFERENCES `ingresos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_ingresos`
--

LOCK TABLES `detalle_ingresos` WRITE;
/*!40000 ALTER TABLE `detalle_ingresos` DISABLE KEYS */;
INSERT INTO `detalle_ingresos` VALUES (1,1,1,15,15.00);
/*!40000 ALTER TABLE `detalle_ingresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_ventas`
--

DROP TABLE IF EXISTS `detalle_ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_ventas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idventa` int(10) unsigned NOT NULL,
  `idarticulo` int(10) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_ventas_idventa_foreign` (`idventa`),
  KEY `detalle_ventas_idarticulo_foreign` (`idarticulo`),
  CONSTRAINT `detalle_ventas_idarticulo_foreign` FOREIGN KEY (`idarticulo`) REFERENCES `articulos` (`id`),
  CONSTRAINT `detalle_ventas_idventa_foreign` FOREIGN KEY (`idventa`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_ventas`
--

LOCK TABLES `detalle_ventas` WRITE;
/*!40000 ALTER TABLE `detalle_ventas` DISABLE KEYS */;
INSERT INTO `detalle_ventas` VALUES (1,1,1,2,16.00,0.00),(2,2,1,3,16.00,0.00),(3,3,1,4,16.00,0.00),(4,4,1,3,16.00,0.00),(5,5,1,3,16.00,0.00),(6,6,1,2,16.00,0.00),(7,7,1,3,16.00,0.00),(8,8,1,4,16.00,0.00),(9,9,1,3,16.00,0.00),(10,10,1,5,16.00,0.00),(11,11,1,4,16.00,0.00),(12,12,1,3,16.00,0.00),(13,13,1,3,16.00,0.00),(14,14,1,4,16.00,0.00);
/*!40000 ALTER TABLE `detalle_ventas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monedaPrincipal` tinyint(1) NOT NULL DEFAULT 1,
  `valorMaximoDescuento` decimal(6,2) NOT NULL,
  `tipoCambio1` decimal(10,2) DEFAULT NULL,
  `tipoCambio2` decimal(10,2) DEFAULT NULL,
  `tipoCambio3` decimal(10,2) DEFAULT NULL,
  `licencia` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `empresas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Farmacorp S.R.L.','Avenida América y Pando','65963002','farmacorpsrl@gmail.com',1,0.00,5.00,6.00,7.00,'si',NULL,'2023-08-08 15:31:22');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturas`
--

DROP TABLE IF EXISTS `facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facturas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcliente` int(10) unsigned NOT NULL,
  `numeroFactura` int(11) NOT NULL,
  `cuf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fechaEmision` datetime NOT NULL,
  `codigoMetodoPago` int(11) NOT NULL,
  `montoTotal` decimal(10,2) NOT NULL,
  `montoTotalSujetoIva` decimal(10,2) NOT NULL,
  `descuentoAdicional` decimal(10,2) NOT NULL,
  `productos` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facturas_idcliente_foreign` (`idcliente`),
  CONSTRAINT `facturas_idcliente_foreign` FOREIGN KEY (`idcliente`) REFERENCES `personas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas`
--

LOCK TABLES `facturas` WRITE;
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
INSERT INTO `facturas` VALUES (1,3,2,'1609FD28BF13F92E3A30C57859601E5E5CF52878D481CC52DD110DFD74','2023-08-08 12:16:31',1,32.00,32.00,0.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00002</numeroFactura><cuf>1609FD28BF13F92E3A30C57859601E5E5CF52878D481CC52DD110DFD74</cuf><cufd>BQUFVQktrUEZBNzDNBNzA5MUMzODc=QznDjTJYSkpJWFVczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-08T12:16:31.093</fechaEmision><nombreRazonSocial>Juan Garcia</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>45255</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>45255</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>32.00</montoTotal><montoTotalSujetoIva>32.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>32.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>0</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera></facturaComputarizadaCompraVenta>\n',1,'2023-08-08 16:16:33','2023-08-08 16:16:33'),(2,4,3,'1609FD28BF13F92E3ABB46EDD07A4050EFFC98913E31CC52DD110DFD74','2023-08-08 12:23:16',1,48.00,48.00,0.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00003</numeroFactura><cuf>1609FD28BF13F92E3ABB46EDD07A4050EFFC98913E31CC52DD110DFD74</cuf><cufd>BQUFVQktrUEZBNzDNBNzA5MUMzODc=QznDjTJYSkpJWFVczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-08T12:23:16.940</fechaEmision><nombreRazonSocial>Maria Galindo</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>5165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>5165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>48.00</montoTotal><montoTotalSujetoIva>48.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>48.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>0</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera></facturaComputarizadaCompraVenta>\n',1,'2023-08-08 16:23:19','2023-08-08 16:23:19'),(3,5,4,'1609FD28BF13F92E3AF823226113206056B1E8A9A811CC52DD110DFD74','2023-08-08 12:26:18',1,64.00,64.00,0.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00004</numeroFactura><cuf>1609FD28BF13F92E3AF823226113206056B1E8A9A811CC52DD110DFD74</cuf><cufd>BQUFVQktrUEZBNzDNBNzA5MUMzODc=QznDjTJYSkpJWFVczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-08T12:26:18.305</fechaEmision><nombreRazonSocial>Pedro Guzman</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>65165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>65165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>64.00</montoTotal><montoTotalSujetoIva>64.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>64.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>0</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera></facturaComputarizadaCompraVenta>\n',1,'2023-08-08 16:26:22','2023-08-08 16:26:22'),(4,2,5,'1609FD28BF13F9346E00C06BA1FDAD2FB6F7D8C21207CDAB97B80DFD74','2023-08-10 13:44:22',1,48.00,48.00,0.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00005</numeroFactura><cuf>1609FD28BF13F9346E00C06BA1FDAD2FB6F7D8C21207CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T13:44:22.464</fechaEmision><nombreRazonSocial>INTI</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>51545</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>51545</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>48.00</montoTotal><montoTotalSujetoIva>48.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>48.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>0</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 17:44:26','2023-08-10 17:44:26'),(5,3,6,'1609FD28BF13F9346F08528678FA1F9F6F0908DA7C37CDAB97B80DFD74','2023-08-10 13:57:27',1,48.00,48.00,0.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00006</numeroFactura><cuf>1609FD28BF13F9346F08528678FA1F9F6F0908DA7C37CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T13:57:27.603</fechaEmision><nombreRazonSocial>Juan Garcia</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>45255</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>45255</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>48.00</montoTotal><montoTotalSujetoIva>48.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>48.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>0</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 17:57:29','2023-08-10 17:57:29'),(6,4,7,'1609FD28BF13F9346F358E7C34446875E05A58F2E677CDAB97B80DFD74','2023-08-10 13:59:51',1,30.00,30.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00007</numeroFactura><cuf>1609FD28BF13F9346F358E7C34446875E05A58F2E677CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T13:59:51.592</fechaEmision><nombreRazonSocial>Maria Galindo</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>5165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>5165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>30.00</montoTotal><montoTotalSujetoIva>30.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>30.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin xsi:nil=\"true\"/><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>2</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>32.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 17:59:54','2023-08-10 17:59:54'),(7,5,8,'1609FD28BF13F93472D60D059420AB547D8F690B5077CDAB97B80DFD74','2023-08-10 14:05:49',1,46.00,46.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00008</numeroFactura><cuf>1609FD28BF13F93472D60D059420AB547D8F690B5077CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:05:49.273</fechaEmision><nombreRazonSocial>Pedro Guzman</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>65165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>65165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>46.00</montoTotal><montoTotalSujetoIva>46.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>46.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin xsi:nil=\"true\"/><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>31</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>496.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:05:51','2023-08-10 18:05:51'),(8,2,9,'1609FD28BF13F93472F9743BA3CBF0B7F6142923BA17CDAB97B80DFD74','2023-08-10 14:07:24',1,61.00,61.00,3.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00009</numeroFactura><cuf>1609FD28BF13F93472F9743BA3CBF0B7F6142923BA17CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:07:24.581</fechaEmision><nombreRazonSocial>INTI</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>51545</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>51545</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>61.00</montoTotal><montoTotalSujetoIva>61.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>61.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>3.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin xsi:nil=\"true\"/><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>4</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>64.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:07:26','2023-08-10 18:07:26'),(9,4,10,'1609FD28BF13F9347335D36101989F20C644493C2417CDAB97B80DFD74','2023-08-10 14:10:23',1,46.00,46.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00010</numeroFactura><cuf>1609FD28BF13F9347335D36101989F20C644493C2417CDAB97B80DFD74</cuf><cufd>BQVVCS2tQRkE=NzDNBNzA5MUMzODc=Qz58N2FOTElYVUFczODlBRTQ2NEFBR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:10:23.527</fechaEmision><nombreRazonSocial>Maria Galindo</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>5165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>5165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>46.00</montoTotal><montoTotalSujetoIva>46.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>46.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin xsi:nil=\"true\"/><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>3</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>48.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:10:26','2023-08-10 18:10:26'),(10,2,11,'1609FD28BF13F934738699CEFE6F7C39C108F9548E57DE71EFB80DFD74','2023-08-10 14:14:23',1,62.00,62.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00011</numeroFactura><cuf>1609FD28BF13F934738699CEFE6F7C39C108F9548E57DE71EFB80DFD74</cuf><cufd>BQVVCS2tQRkE=NzEQ0MDlEN0EzODc=Q3l3aE5PTElYVUFc1MzU1NDZCNzEyR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:14:23.506</fechaEmision><nombreRazonSocial>INTI</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>51545</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>51545</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>62.00</montoTotal><montoTotalSujetoIva>62.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>62.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin xsi:nil=\"true\"/><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>4</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>64.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:14:25','2023-08-10 18:14:25'),(11,3,12,'1609FD28BF13F934743AB73BEB3702B2E773D96CF827DE71EFB80DFD74','2023-08-10 14:23:15',1,46.00,46.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00012</numeroFactura><cuf>1609FD28BF13F934743AB73BEB3702B2E773D96CF827DE71EFB80DFD74</cuf><cufd>BQVVCS2tQRkE=NzEQ0MDlEN0EzODc=Q3l3aE5PTElYVUFc1MzU1NDZCNzEyR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:23:15.392</fechaEmision><nombreRazonSocial>Juan Garcia</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>45255</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>45255</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>46.00</montoTotal><montoTotalSujetoIva>46.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>46.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin>45290</codigoProductoSin><codigoProducto xsi:nil=\"true\"/><descripcion>Vacuna Jhonsson</descripcion><cantidad>30</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>480.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:23:17','2023-08-10 18:23:17'),(12,5,13,'1609FD28BF13F934749E22B3DF57EA630C6AC9856207DE71EFB80DFD74','2023-08-10 14:28:07',1,46.00,46.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00013</numeroFactura><cuf>1609FD28BF13F934749E22B3DF57EA630C6AC9856207DE71EFB80DFD74</cuf><cufd>BQVVCS2tQRkE=NzEQ0MDlEN0EzODc=Q3l3aE5PTElYVUFc1MzU1NDZCNzEyR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:28:07.695</fechaEmision><nombreRazonSocial>Pedro Guzman</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>65165</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>65165</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>46.00</montoTotal><montoTotalSujetoIva>46.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>46.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin>45290</codigoProductoSin><codigoProducto>15615</codigoProducto><descripcion>Vacuna Jhonsson</descripcion><cantidad>3</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>48.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:28:10','2023-08-10 18:28:10'),(13,2,14,'1609FD28BF13F93476AB8B48F66F2190E932D99DCC876B4BA3C80DFD74','2023-08-10 14:54:09',1,62.00,62.00,2.00,'<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n<facturaComputarizadaCompraVenta xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:noNamespaceSchemaLocation=\"facturaComputarizadaCompraVenta.xsd\"><cabecera><nitEmisor>5153610012</nitEmisor><razonSocialEmisor>365 SOFT</razonSocialEmisor><municipio>Cochabamba</municipio><telefono>77490451</telefono><numeroFactura>00014</numeroFactura><cuf>1609FD28BF13F93476AB8B48F66F2190E932D99DCC876B4BA3C80DFD74</cuf><cufd>BQVVCS2tQRkE=NzEQ0MDlEN0EzODc=Q1UtRjJPTElYVUFc1MzU1NDZCNzEyR</cufd><codigoSucursal>0</codigoSucursal><direccion>CALLE BARQUISIMETO NRO.1493 EDIFICIO ESTELITA PISO PB DPTO. OFICINA 1 ZONA CALA CALA</direccion><codigoPuntoVenta>0</codigoPuntoVenta><fechaEmision>2023-08-10T14:54:09.392</fechaEmision><nombreRazonSocial>INTI</nombreRazonSocial><codigoTipoDocumentoIdentidad>1</codigoTipoDocumentoIdentidad><numeroDocumento>51545</numeroDocumento><complemento xsi:nil=\"true\"/><codigoCliente>51545</codigoCliente><codigoMetodoPago>1</codigoMetodoPago><numeroTarjeta xsi:nil=\"true\"/><montoTotal>62.00</montoTotal><montoTotalSujetoIva>62.00</montoTotalSujetoIva><codigoMoneda>1</codigoMoneda><tipoCambio>1</tipoCambio><montoTotalMoneda>62.00</montoTotalMoneda><montoGiftCard xsi:nil=\"true\"/><descuentoAdicional>2.00</descuentoAdicional><codigoExcepcion>0</codigoExcepcion><cafc xsi:nil=\"true\"/><leyenda>Ley N° 453: El proveedor de servicios debe habilitar medios e instrumentos para efectuar consultas y reclamaciones.</leyenda><usuario>root</usuario><codigoDocumentoSector>1</codigoDocumentoSector></cabecera><detalle><actividadEconomica>474110</actividadEconomica><codigoProductoSin>45290</codigoProductoSin><codigoProducto>15615</codigoProducto><descripcion>Vacuna Jhonsson</descripcion><cantidad>4</cantidad><unidadMedida>57</unidadMedida><precioUnitario>16.00</precioUnitario><montoDescuento>0</montoDescuento><subTotal>64.00</subTotal><numeroSerie xsi:nil=\"true\"/><numeroImei xsi:nil=\"true\"/></detalle></facturaComputarizadaCompraVenta>\n',1,'2023-08-10 18:54:12','2023-08-10 18:54:12');
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_grupo` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupos_nombre_grupo_unique` (`nombre_grupo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (1,'Vacunas','2023-08-08 15:52:10','2023-08-08 15:52:10');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industrias`
--

DROP TABLE IF EXISTS `industrias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industrias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `industrias_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industrias`
--

LOCK TABLES `industrias` WRITE;
/*!40000 ALTER TABLE `industrias` DISABLE KEYS */;
INSERT INTO `industrias` VALUES (1,'INTI',1),(2,'EEUU',0);
/*!40000 ALTER TABLE `industrias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresos`
--

DROP TABLE IF EXISTS `ingresos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingresos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idproveedor` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `tipo_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_comprobante` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_comprobante` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idcaja` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ingresos_idproveedor_foreign` (`idproveedor`),
  KEY `ingresos_idusuario_foreign` (`idusuario`),
  KEY `ingresos_idcaja_foreign` (`idcaja`),
  CONSTRAINT `ingresos_idcaja_foreign` FOREIGN KEY (`idcaja`) REFERENCES `cajas` (`id`),
  CONSTRAINT `ingresos_idproveedor_foreign` FOREIGN KEY (`idproveedor`) REFERENCES `proveedores` (`id`),
  CONSTRAINT `ingresos_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresos`
--

LOCK TABLES `ingresos` WRITE;
/*!40000 ALTER TABLE `ingresos` DISABLE KEYS */;
INSERT INTO `ingresos` VALUES (1,2,1,'FACTURA','54154','545','2023-08-08 12:14:37',0.13,225.00,'Registrado',1,'2023-08-08 16:14:37','2023-08-08 16:14:37');
/*!40000 ALTER TABLE `ingresos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventarios`
--

DROP TABLE IF EXISTS `inventarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idalmacen` int(10) unsigned NOT NULL,
  `idarticulo` int(10) unsigned NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `saldo_stock` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventarios_idalmacen_foreign` (`idalmacen`),
  KEY `inventarios_idarticulo_foreign` (`idarticulo`),
  CONSTRAINT `inventarios_idalmacen_foreign` FOREIGN KEY (`idalmacen`) REFERENCES `almacens` (`id`),
  CONSTRAINT `inventarios_idarticulo_foreign` FOREIGN KEY (`idarticulo`) REFERENCES `articulos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventarios`
--

LOCK TABLES `inventarios` WRITE;
/*!40000 ALTER TABLE `inventarios` DISABLE KEYS */;
INSERT INTO `inventarios` VALUES (1,1,1,'2023-08-11',-31,'2023-08-08 16:14:38','2023-08-10 18:54:09');
/*!40000 ALTER TABLE `inventarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marcas`
--

DROP TABLE IF EXISTS `marcas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `marcas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marcas`
--

LOCK TABLES `marcas` WRITE;
/*!40000 ALTER TABLE `marcas` DISABLE KEYS */;
INSERT INTO `marcas` VALUES (1,'SDI',1,NULL,NULL),(2,'MAILLEFER',1,NULL,NULL),(3,'MAQUIRA',1,NULL,NULL),(4,'PERU',1,NULL,NULL),(5,'TIZIANO',1,NULL,NULL),(6,'DENCOR',1,NULL,NULL),(7,'RITE DENT',1,NULL,NULL),(8,'ARGENTINA',1,NULL,NULL),(9,'JON',1,NULL,NULL),(10,'HAVEL#S',1,NULL,NULL),(11,'NACIONAL',1,NULL,NULL),(12,'M-DENT',1,NULL,NULL),(13,'HHSS',1,NULL,NULL),(14,'ARAIN',1,NULL,NULL),(15,'BRASIL',1,NULL,NULL),(16,'VANNINI',1,NULL,NULL),(17,'DENTAURUM',1,NULL,NULL),(18,'IODONTOSUL',1,NULL,NULL),(19,'BEUTELROCK',1,NULL,NULL),(20,'SENDOLINE',1,NULL,NULL),(21,'DENTSCLER',1,NULL,NULL),(22,'NEW STETIC',1,NULL,NULL),(23,'PERFEX',1,NULL,NULL),(24,'BUFFALO',1,NULL,NULL),(25,'DIATECH',1,NULL,NULL),(26,'PEMACO',1,NULL,NULL),(27,'ABLEH',1,NULL,NULL),(28,'ALFA DENT',1,NULL,NULL),(29,'DEDECO',1,NULL,NULL),(30,'CORAL',1,NULL,NULL),(31,'LIGHT DENT',1,NULL,NULL),(32,'MEDECO',1,NULL,NULL),(33,'TANARI',1,NULL,NULL),(34,'PREMIUM',1,NULL,NULL),(35,'SSWHITE BURS',1,NULL,NULL),(36,'SULTAN',1,NULL,NULL),(37,'SULPAK',1,NULL,NULL),(38,'KEYSTON',1,NULL,NULL),(39,'MICRODONT',1,NULL,NULL),(40,'MIRAGE',1,NULL,NULL),(41,'SAGIMA',1,NULL,NULL),(42,'SUBITON',1,NULL,NULL),(43,'ADAPT',1,NULL,NULL),(44,'ALPHA',1,NULL,NULL),(45,'DICA',1,NULL,NULL),(46,'DRILLCO',1,NULL,NULL),(47,'KOMET',1,NULL,NULL),(48,'MANI',1,NULL,NULL),(49,'LA',1,NULL,NULL),(50,'COMFIT',1,NULL,NULL),(51,'URABY',1,NULL,NULL),(52,'CAAJARA',1,NULL,NULL),(53,'DEEPAK',1,NULL,NULL),(54,'CONEXÃO',1,NULL,NULL),(55,'UNIVET',1,NULL,NULL),(56,'SIGMA',1,NULL,NULL),(57,'DURATONE',1,NULL,NULL),(58,'ORMCO',1,NULL,NULL),(59,'LITEX',1,NULL,NULL),(60,'ULTRABLUE',1,NULL,NULL),(61,'EGEO',1,NULL,NULL),(62,'FLOW XRAY',1,NULL,NULL),(63,'TECH NEW',1,NULL,NULL),(64,'DICKINSON',1,NULL,NULL),(65,'ANGELUS',1,NULL,NULL),(66,'APOLO',1,NULL,NULL),(67,'CAPULLO',1,NULL,NULL),(68,'M.L.',1,NULL,NULL),(69,'PRIME DENT',1,NULL,NULL),(70,'DENTAMERICA',1,NULL,NULL),(71,'MEDENTAL',1,NULL,NULL),(72,'GN',1,NULL,NULL),(73,'XIANTAO',1,NULL,NULL),(74,'PENTA',1,NULL,NULL),(75,'NEW DENT',1,NULL,NULL),(76,'OLYMPIC',1,NULL,NULL),(77,'T-REAL',1,NULL,NULL),(78,'MICRODENT',1,NULL,NULL),(79,'DENTEX',1,NULL,NULL),(80,'KG',1,NULL,NULL),(81,'NEW SMILE',1,NULL,NULL),(82,'DENTSPLY CAULK',1,NULL,NULL),(83,'FAVA',1,NULL,NULL),(84,'VIGODENT',1,NULL,NULL),(85,'NIPRO',1,NULL,NULL),(86,'MULTI-BRUSH',1,NULL,NULL),(87,'WINMAX',1,NULL,NULL),(88,'ZEYCO',1,NULL,NULL),(89,'VAICRIL',1,NULL,NULL),(90,'GERMANY',1,NULL,NULL),(91,'SSWHITE',1,NULL,NULL),(92,'MAYOR DENT',1,NULL,NULL),(93,'CORMET',1,NULL,NULL),(94,'CAIMAN',1,NULL,NULL),(95,'TEDEQUIM',1,NULL,NULL),(96,'DENTSPLY',1,NULL,NULL),(97,'RADIANT-P',1,NULL,NULL),(98,'NEWCRYL',1,NULL,NULL),(99,'MISAWA',1,NULL,NULL),(100,'CK DENTAL',1,NULL,NULL),(101,'VDW',1,NULL,NULL),(102,'MAGNUS',1,NULL,NULL),(103,'TDV',1,NULL,NULL),(104,'MORELLI',1,NULL,NULL),(105,'ADECO',1,NULL,NULL),(106,'PREHMA',1,NULL,NULL),(107,'NUGARD',1,NULL,NULL),(108,'IMAGE',1,NULL,NULL),(109,'CHINA',1,NULL,NULL),(110,'BIO SEP',1,NULL,NULL),(111,'SCHELBLE',1,NULL,NULL),(112,'KODAK',1,NULL,NULL),(113,'SURE DENT',1,NULL,NULL),(114,'BK-MEDENT',1,NULL,NULL),(115,'NEY',1,NULL,NULL),(116,'DEGUDENT',1,NULL,NULL),(117,'INODON',1,NULL,NULL),(118,'ODP',1,NULL,NULL),(119,'DENTAL USA',1,NULL,NULL),(120,'STODDARD',1,NULL,NULL),(121,'SSWHITE DIAMOND BURS',1,NULL,NULL),(122,'TOR VM',1,NULL,NULL),(123,'TEXABOL',1,NULL,NULL),(124,'UVEX',1,NULL,NULL),(125,'TIGER HEAD',1,NULL,NULL),(126,'SUPER GLUE',1,NULL,NULL),(127,'DOCHEM',1,NULL,NULL),(128,'NAHITA',1,NULL,NULL),(129,'PREMIER',1,NULL,NULL),(130,'RIMAX',1,NULL,NULL),(131,'GLASS',1,NULL,NULL),(132,'COLGATE',1,NULL,NULL),(133,'COREGA',1,NULL,NULL),(134,'ORALGENE',1,NULL,NULL),(135,'JOHNSON',1,NULL,NULL),(136,'POLIDENT',1,NULL,NULL),(137,'DFL',1,NULL,NULL),(138,'QUIMIDROL',1,NULL,NULL),(139,'MILLENIUM',1,NULL,NULL),(140,'FORAMEN',1,NULL,NULL),(141,'URGO',1,NULL,NULL),(142,'KING',1,NULL,NULL),(143,'TELCHI',1,NULL,NULL),(144,'MEDICARE',1,NULL,NULL),(145,'TENSO',1,NULL,NULL),(146,'HANS BIOMED',1,NULL,NULL),(147,'ABD',1,NULL,NULL),(148,'DYCOME',1,NULL,NULL),(149,'POLAROID',1,NULL,NULL),(150,'VITAL SUTURES',1,NULL,NULL),(151,'PEPSODENT',1,NULL,NULL),(152,'INSIBOL',1,NULL,NULL),(153,'VARIOS',1,NULL,NULL),(154,'ALT',1,NULL,NULL),(155,'SHULTZ',1,NULL,NULL),(156,'X-DENT',1,NULL,NULL),(157,'PRESSURE COMPRESSORES LTDA',1,NULL,NULL),(158,'BIODINAMICA',1,NULL,NULL),(159,'JULIANA',1,NULL,NULL),(160,'ASTRIX S.A.',1,NULL,NULL),(161,'DENTITO',1,NULL,NULL),(162,'CODENSA',1,NULL,NULL),(163,'AIRON',1,NULL,NULL),(164,'BEYES',1,NULL,NULL),(165,'SANCTUARY',1,NULL,NULL),(166,'KAFOO DISPOCARE',1,NULL,NULL),(167,'RUNYES',1,NULL,NULL),(168,'WOSON',1,NULL,NULL),(169,'FOSHAN ',1,NULL,NULL),(170,'INTOP INDUSTRY',1,NULL,NULL),(171,'GREELOY',1,NULL,NULL),(172,'G4',1,NULL,NULL),(173,'GUILIN WOODPECKER',1,NULL,NULL),(174,'IVOCLAR VIVADENT',1,NULL,NULL),(175,'IVOSTAR',1,NULL,NULL),(176,'GNATHOSTAR',1,NULL,NULL),(177,'DENTFLEX',1,NULL,NULL),(178,'AKOSDENT',1,NULL,NULL),(179,'SHANGHAI LAMAR',1,NULL,NULL),(180,'COXO',1,NULL,NULL),(181,'CO-HEALTHY',1,NULL,NULL),(182,'CICADA',1,NULL,NULL),(183,'AICREATE',1,NULL,NULL),(184,'MDC',1,NULL,NULL),(185,'MEISINGER',1,NULL,NULL);
/*!40000 ALTER TABLE `marcas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medidas`
--

DROP TABLE IF EXISTS `medidas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medidas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion_medida` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_corta` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medidas`
--

LOCK TABLES `medidas` WRITE;
/*!40000 ALTER TABLE `medidas` DISABLE KEYS */;
INSERT INTO `medidas` VALUES (1,'Unidad','U.',1,NULL,NULL),(2,'Litro','Lt.',1,NULL,NULL),(3,'Centimetros','Cm.',1,NULL,NULL),(4,'Caja','Cj.',1,NULL,NULL);
/*!40000 ALTER TABLE `medidas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1),(2,'2017_06_14_105223_create_grupos_table',1),(3,'2018_02_05_133141_create_medidas_table',1),(4,'2018_02_05_133425_create_roles_table',1),(5,'2018_02_05_143638_create_personas_table',1),(6,'2018_02_06_024616_create_proveedores_table',1),(7,'2018_02_17_163613_create_empresa_table',1),(8,'2018_02_18_090808_create_sucursales_table',1),(9,'2018_02_19_000000_create_users_table',1),(10,'2018_02_20_171225_create_categorias_table',1),(11,'2018_02_22_235257_create_articulos_table',1),(12,'2018_09_06_140852_create_cajas_table',1),(13,'2018_09_07_150703_create_ingresos_table',1),(14,'2018_09_07_150819_create_detalle_ingresos_table',1),(15,'2018_09_09_033145_create_ventas_table',1),(16,'2018_09_09_033241_create_detalle_ventas_table',1),(17,'2018_09_09_214633_create_notifications_table',1),(18,'2023_02_24_104420_add_foto_person',1),(19,'2023_03_07_095058_create_fotoar_table',1),(20,'2023_03_14_102954_create_monedas_table',1),(21,'2023_04_12_141938_create_transacciones_cajas_table',1),(22,'2023_04_19_130514_create_arqueo_cajas_table',1),(23,'2023_04_27_082926_create_industrias_table',1),(24,'2023_04_28_101419_create_marca_table',1),(25,'2023_05_02_164914_create_tipo_pagos_table',1),(26,'2023_05_03_103942_add_referencias_articulos',1),(27,'2023_05_15_103430_add_fecha_vencimiento_articulos',1),(28,'2023_05_22_115356_create_configuraciones_trabajo',1),(29,'2023_05_22_144033_create_cotizacionventa_table',1),(30,'2023_06_07_110143_add_nuevas_columnas_sucursales',1),(31,'2023_06_13_095747_create_precios_table',1),(32,'2023_07_10_175052_create_detalle_cotizacion_table',1),(33,'2023_07_12_141211_create_almacens_table',1),(34,'2023_07_12_152133_create_inventarios_table',1),(35,'2023_08_01_133238_create_facturas_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monedas`
--

DROP TABLE IF EXISTS `monedas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monedas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idempresa` int(10) unsigned NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `simbolo` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `compra` decimal(10,2) NOT NULL,
  `venta` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `monedas_nombre_unique` (`nombre`),
  KEY `monedas_idempresa_foreign` (`idempresa`),
  CONSTRAINT `monedas_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monedas`
--

LOCK TABLES `monedas` WRITE;
/*!40000 ALTER TABLE `monedas` DISABLE KEYS */;
/*!40000 ALTER TABLE `monedas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` int(10) unsigned NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_id_notifiable_type_index` (`notifiable_id`,`notifiable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('2fe40b4d-ff33-4c6b-83f0-e3fa74fa0972','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":2,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 17:57:27','2023-08-10 17:57:27'),('37c51e56-4b7b-4b31-a05f-eb35a2fe27bd','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":11,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:54:10','2023-08-10 18:54:10'),('48afeebf-61f7-4fed-b4b3-67fb255ecb6f','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":6,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:10:23','2023-08-10 18:10:23'),('56f906ab-ff50-4db3-8c07-b9e1b20fa95f','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":4,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:05:49','2023-08-10 18:05:49'),('6d5c4aa4-8983-409b-a542-0f8958e3e2f6','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":9,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:23:15','2023-08-10 18:23:15'),('88dc61ef-239f-4baf-bd47-73c8a0d60425','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":0,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":1,\"msj\":\"Ingresos\"}}}','2023-08-10 15:14:53','2023-08-08 16:14:37','2023-08-10 15:14:53'),('89bf4a31-e67d-4ee1-a593-09c8d3b5507e','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":8,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:14:27','2023-08-10 18:14:27'),('90ac250f-869c-4152-a4de-8095d1cd43c9','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":1,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 17:44:23','2023-08-10 17:44:23'),('aeef1e0c-75d9-4fe0-aa9c-1a00ad660270','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":2,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":1,\"msj\":\"Ingresos\"}}}','2023-08-10 15:14:53','2023-08-08 16:23:17','2023-08-10 15:14:53'),('bbd68f27-da9b-4e49-9ad0-096bbe673371','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":3,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":1,\"msj\":\"Ingresos\"}}}','2023-08-10 15:14:53','2023-08-08 16:26:18','2023-08-10 15:14:53'),('c2b61570-ea37-41c5-acdb-1d86da51b2d4','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":3,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 17:59:51','2023-08-10 17:59:51'),('cc1a9dd9-390b-4206-9624-5af4ebed907a','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":1,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":1,\"msj\":\"Ingresos\"}}}','2023-08-10 15:14:53','2023-08-08 16:16:31','2023-08-10 15:14:53'),('e57c1511-a6d1-4f46-b039-9e18e4233298','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":10,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:28:11','2023-08-10 18:28:11'),('e6efe8da-97d7-4298-a19b-fcd8fdf3f84b','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":7,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:13:12','2023-08-10 18:13:12'),('f8bdafda-5bf9-417a-9426-a511de9bcf46','App\\Notifications\\NotifyAdmin',1,'App\\User','{\"datos\":{\"ventas\":{\"numero\":5,\"msj\":\"Ventas\"},\"ingresos\":{\"numero\":0,\"msj\":\"Ingresos\"}}}',NULL,'2023-08-10 18:07:24','2023-08-10 18:07:24');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personas`
--

DROP TABLE IF EXISTS `personas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fotografia` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personas_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personas`
--

LOCK TABLES `personas` WRITE;
/*!40000 ALTER TABLE `personas` DISABLE KEYS */;
INSERT INTO `personas` VALUES (1,'H.D.CH.Z','CEDULA','00000000','','NA','61452585','root@gmail.com',NULL,'2023-08-08 15:32:16','hdchz.jpeg'),(2,'INTI','DNI','51545',NULL,'La paz, calle lanza','4251252','inti@gmail.com','2023-08-08 16:09:52','2023-08-08 16:09:52',NULL),(3,'Juan Garcia','DNI','45255',NULL,'Calle juan','65251252','juan@gmail.com','2023-08-08 16:10:35','2023-08-08 16:10:35',NULL),(4,'Maria Galindo','DNI','5165',NULL,'Calle galindo','62125025','maria@gmail.com','2023-08-08 16:22:50','2023-08-08 16:22:50',NULL),(5,'Pedro Guzman','DNI','65165',NULL,'Calle guzman','65212520','pedro@gmail.com','2023-08-08 16:25:40','2023-08-08 16:25:40',NULL);
/*!40000 ALTER TABLE `personas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `precios`
--

DROP TABLE IF EXISTS `precios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `precios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_precio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentage` double(11,2) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `precios`
--

LOCK TABLES `precios` WRITE;
/*!40000 ALTER TABLE `precios` DISABLE KEYS */;
/*!40000 ALTER TABLE `precios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proveedores` (
  `id` int(10) unsigned NOT NULL,
  `contacto` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_contacto` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  KEY `proveedores_id_foreign` (`id`),
  CONSTRAINT `proveedores_id_foreign` FOREIGN KEY (`id`) REFERENCES `personas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (2,'inti','65251252');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador','Administradores de área',1),(2,'Vendedor','Vendedor área venta',1),(3,'Almacenero','Almacenero área compras',1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sucursales`
--

DROP TABLE IF EXISTS `sucursales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sucursales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idempresa` int(10) unsigned NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `responsable` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sigla` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nro_auth` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facturacion` enum('W','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'W',
  `fac3ros` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `nit3ros` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `empresa3ros` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `casamatriz` enum('S','N') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `leyenda` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cuenta` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sucursales_direccion_unique` (`direccion`),
  KEY `sucursales_idempresa_foreign` (`idempresa`),
  CONSTRAINT `sucursales_idempresa_foreign` FOREIGN KEY (`idempresa`) REFERENCES `empresas` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursales`
--

LOCK TABLES `sucursales` WRITE;
/*!40000 ALTER TABLE `sucursales` DISABLE KEYS */;
INSERT INTO `sucursales` VALUES (1,1,'Sucursal','NA','empresa@gmail.com','00000000','Cochabamba',1,NULL,NULL,NULL,'',NULL,'W','N',NULL,NULL,'N',NULL,NULL);
/*!40000 ALTER TABLE `sucursales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tipo_pagos`
--

DROP TABLE IF EXISTS `tipo_pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_pagos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_tipo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion_tipo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tipo_pagos`
--

LOCK TABLES `tipo_pagos` WRITE;
/*!40000 ALTER TABLE `tipo_pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tipo_pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacciones_cajas`
--

DROP TABLE IF EXISTS `transacciones_cajas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transacciones_cajas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcaja` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `fecha` datetime NOT NULL,
  `transaccion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importe` decimal(11,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transacciones_cajas_idcaja_foreign` (`idcaja`),
  KEY `transacciones_cajas_idusuario_foreign` (`idusuario`),
  CONSTRAINT `transacciones_cajas_idcaja_foreign` FOREIGN KEY (`idcaja`) REFERENCES `cajas` (`id`),
  CONSTRAINT `transacciones_cajas_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacciones_cajas`
--

LOCK TABLES `transacciones_cajas` WRITE;
/*!40000 ALTER TABLE `transacciones_cajas` DISABLE KEYS */;
/*!40000 ALTER TABLE `transacciones_cajas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL,
  `usuario` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1,
  `idrol` int(10) unsigned NOT NULL,
  `idsucursal` int(10) unsigned NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  UNIQUE KEY `users_usuario_unique` (`usuario`),
  KEY `users_id_foreign` (`id`),
  KEY `users_idrol_foreign` (`idrol`),
  KEY `users_idsucursal_foreign` (`idsucursal`),
  CONSTRAINT `users_id_foreign` FOREIGN KEY (`id`) REFERENCES `personas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_idrol_foreign` FOREIGN KEY (`idrol`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_idsucursal_foreign` FOREIGN KEY (`idsucursal`) REFERENCES `sucursales` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'root','$2y$10$4B3N8GcZAaw1RnE.WvGFT.1D67X8GaW7k8wT5nb3CR1c76UWjm7ZW',1,1,1,'6LkYkDH0tV24CaAH6AIuPyez7jUGsyFOcWqjWwpUsqAJyvo55QFWIEM5f2ur');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventas`
--

DROP TABLE IF EXISTS `ventas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idcliente` int(10) unsigned NOT NULL,
  `idusuario` int(10) unsigned NOT NULL,
  `tipo_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_comprobante` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_comprobante` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `impuesto` decimal(4,2) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `idcaja` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventas_idcliente_foreign` (`idcliente`),
  KEY `ventas_idusuario_foreign` (`idusuario`),
  KEY `ventas_idcaja_foreign` (`idcaja`),
  CONSTRAINT `ventas_idcaja_foreign` FOREIGN KEY (`idcaja`) REFERENCES `cajas` (`id`),
  CONSTRAINT `ventas_idcliente_foreign` FOREIGN KEY (`idcliente`) REFERENCES `personas` (`id`),
  CONSTRAINT `ventas_idusuario_foreign` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventas`
--

LOCK TABLES `ventas` WRITE;
/*!40000 ALTER TABLE `ventas` DISABLE KEYS */;
INSERT INTO `ventas` VALUES (1,3,1,'FACTURA',NULL,'00002','2023-08-08 12:16:31',0.18,32.00,'Registrado',1,'2023-08-08 16:16:31','2023-08-08 16:16:31'),(2,4,1,'FACTURA',NULL,'00003','2023-08-08 12:23:17',0.18,48.00,'Registrado',1,'2023-08-08 16:23:17','2023-08-08 16:23:17'),(3,5,1,'BOLETA',NULL,'00004','2023-08-08 12:26:18',0.18,64.00,'Registrado',1,'2023-08-08 16:26:18','2023-08-08 16:26:18'),(4,2,1,'FACTURA',NULL,'00005','2023-08-10 13:44:22',0.18,48.00,'Registrado',1,'2023-08-10 17:44:22','2023-08-10 17:44:22'),(5,3,1,'FACTURA',NULL,'00006','2023-08-10 13:57:27',0.18,48.00,'Registrado',1,'2023-08-10 17:57:27','2023-08-10 17:57:27'),(6,4,1,'FACTURA',NULL,'00007','2023-08-10 13:59:51',0.18,30.00,'Registrado',1,'2023-08-10 17:59:51','2023-08-10 17:59:51'),(7,5,1,'BOLETA',NULL,'00008','2023-08-10 14:05:49',0.18,46.00,'Registrado',1,'2023-08-10 18:05:49','2023-08-10 18:05:49'),(8,2,1,'FACTURA',NULL,'00009','2023-08-10 14:07:24',0.18,61.00,'Registrado',1,'2023-08-10 18:07:24','2023-08-10 18:07:24'),(9,4,1,'FACTURA',NULL,'00010','2023-08-10 14:10:23',0.18,46.00,'Registrado',1,'2023-08-10 18:10:23','2023-08-10 18:10:23'),(10,3,1,'FACTURA',NULL,'00011','2023-08-10 14:13:12',0.18,78.00,'Registrado',1,'2023-08-10 18:13:12','2023-08-10 18:13:12'),(11,2,1,'FACTURA',NULL,'00011','2023-08-10 14:14:27',0.18,62.00,'Registrado',1,'2023-08-10 18:14:27','2023-08-10 18:14:27'),(12,3,1,'FACTURA',NULL,'00012','2023-08-10 14:23:15',0.18,46.00,'Registrado',1,'2023-08-10 18:23:15','2023-08-10 18:23:15'),(13,5,1,'FACTURA',NULL,'00013','2023-08-10 14:28:11',0.18,46.00,'Registrado',1,'2023-08-10 18:28:11','2023-08-10 18:28:11'),(14,2,1,'FACTURA',NULL,'00014','2023-08-10 14:54:09',0.18,62.00,'Registrado',1,'2023-08-10 18:54:09','2023-08-10 18:54:09');
/*!40000 ALTER TABLE `ventas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-10 14:55:48
