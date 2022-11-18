-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-12-2019 a las 15:45:43
-- Versión del servidor: 5.7.24
-- Versión de PHP: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdgilga`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_atencion`
--

DROP TABLE IF EXISTS `areas_atencion`;
CREATE TABLE IF NOT EXISTS `areas_atencion` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `areas_atencion`
--

INSERT INTO `areas_atencion` (`id`, `descripcion`) VALUES
(1, 'sistemas'),
(2, 'mantenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_estacion`
--

DROP TABLE IF EXISTS `areas_estacion`;
CREATE TABLE IF NOT EXISTS `areas_estacion` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `estacion` varchar(10) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `areas_estacion`
--

INSERT INTO `areas_estacion` (`id`, `estacion`, `descripcion`) VALUES
(1, '6620', 'baños'),
(2, '6620', 'cuarto electrico'),
(3, '6620', 'islas'),
(4, '6620', 'tanques'),
(5, '6620', 'oficinas'),
(6, '6620', 'corte'),
(7, '6620', 'site'),
(8, '6620', 'zona'),
(9, '6620', 'super'),
(10, '6620', 'facturacion'),
(11, '0437', 'baños'),
(12, '0437', 'cuarto electrico'),
(13, '0437', 'islas'),
(14, '0437', 'tanques'),
(15, '0437', 'oficinas'),
(16, '0437', 'corte'),
(17, '0437', 'site'),
(18, '0437', 'zona'),
(19, '0437', 'super'),
(20, '0437', 'facturacion'),
(21, '1040', 'baños'),
(22, '1040', 'cuarto electrico'),
(23, '1040', 'islas'),
(24, '1040', 'tanques'),
(25, '1040', 'oficinas'),
(26, '1040', 'corte'),
(27, '1040', 'site'),
(28, '1040', 'zona'),
(29, '1040', 'super'),
(30, '1040', 'facturacion');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `companias`
--

DROP TABLE IF EXISTS `companias`;
CREATE TABLE IF NOT EXISTS `companias` (
  `id_usuario` int(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_incidencias`
--

DROP TABLE IF EXISTS `detalle_incidencias`;
CREATE TABLE IF NOT EXISTS `detalle_incidencias` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_incidencia` int(255) NOT NULL,
  `id_usuario` int(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `fecha_detalle_incidencia` datetime NOT NULL,
  `comentarios` varchar(255) NOT NULL,
  `foto_ruta` varchar(255) DEFAULT NULL,
  `estatus` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `detalle_incidencias`
--

INSERT INTO `detalle_incidencias` (`id`, `id_incidencia`, `id_usuario`, `created_at`, `updated_at`, `fecha_detalle_incidencia`, `comentarios`, `foto_ruta`, `estatus`) VALUES
(1, 1, 1, '2019-08-07 18:44:26', '2019-10-22 21:28:27', '2019-08-07 00:00:00', 'comentarioxfdfdfd', '1571797707sebas.png', 'Terminado'),
(2, 1, 1, '2019-08-07 18:47:30', '2019-08-07 18:47:30', '2019-08-07 00:00:00', 'retertretrete', NULL, 'En Proceso'),
(3, 1, 1, '2019-08-07 18:48:48', '2019-08-07 18:48:48', '2019-08-07 00:00:00', 'xcvxcxv', NULL, 'En Proceso'),
(4, 1, 1, '2019-08-30 09:38:54', '2019-08-30 09:38:54', '2019-08-30 00:00:00', 'gfgfgfdg', NULL, 'En Proceso'),
(5, 1, 1, '2019-10-16 14:44:39', '2019-10-16 14:44:39', '2019-10-16 00:00:00', 'ivan ivan', NULL, 'En Proceso'),
(6, 1, 1, '2019-10-17 16:01:14', '2019-10-17 16:01:14', '2019-10-17 00:00:00', 'en camino', NULL, 'En Proceso'),
(7, 1, 1, '2019-10-18 14:57:09', '2019-10-18 14:57:09', '2019-10-18 00:00:00', 'dwdsdsd', NULL, 'En Proceso'),
(8, 1, 1, '2019-10-18 15:51:46', '2019-10-18 15:51:46', '2019-10-12 00:00:00', 'LISTO?...', NULL, 'En Proceso'),
(9, 29, 1, '2019-10-18 15:53:06', '2019-10-18 15:53:06', '2019-10-02 00:00:00', 'adsadad', NULL, 'En Proceso'),
(10, 30, 1, '2019-10-18 16:05:04', '2019-10-18 16:05:04', '2019-10-18 00:00:00', 'detalle inci30', NULL, 'En Proceso'),
(11, 28, 1, '2019-10-18 16:57:51', '2019-10-18 16:57:51', '2019-10-17 00:00:00', 'hHDAHDHADH', NULL, 'En Proceso'),
(12, 31, 1, '2019-10-18 17:00:13', '2019-10-18 17:00:13', '2019-10-18 00:00:00', 'soy el aarea de sistemas solucionare su problema a la brevedad', NULL, 'En Proceso'),
(13, 32, 1, '2019-10-19 14:25:05', '2019-10-19 14:25:05', '2019-10-19 00:00:00', 'ok me traslado', NULL, 'En Proceso'),
(14, 34, 2, '2019-10-20 09:46:14', '2019-10-20 09:46:14', '2019-10-20 00:00:00', 'estoya la espera', NULL, 'Pendiente'),
(15, 35, 1, '2019-10-21 10:29:31', '2019-10-21 10:29:31', '2019-10-21 00:00:00', 'ok voy en camino', NULL, 'En Proceso'),
(16, 35, 1, '2019-10-21 11:10:28', '2019-10-21 11:10:28', '2019-10-21 00:00:00', 'listo', NULL, 'Terminado'),
(17, 34, 1, '2019-10-21 11:12:57', '2019-10-21 11:12:57', '2019-10-21 00:00:00', 'terminada', NULL, 'Terminado'),
(18, 1, 1, '2019-10-21 11:16:23', '2019-10-21 11:16:23', '2019-10-22 00:00:00', 'por parte de sistemas terminado', NULL, 'Terminado'),
(19, 1, 2, '2019-10-21 11:17:24', '2019-10-21 11:17:24', '2019-10-21 00:00:00', 'ok confirmo de terminado', NULL, 'Terminado'),
(20, 33, 1, '2019-10-22 20:07:19', '2019-10-22 20:07:19', '2019-10-22 00:00:00', 'prueba detalle incidencia 33', '1571792839hilton.png', 'En Proceso'),
(21, 29, 1, '2019-10-22 23:18:36', '2019-10-22 23:18:36', '2019-10-22 00:00:00', 'detalle incidencia por ivan', '1571804316pemex.jpg', 'En Proceso'),
(22, 29, 1, '2019-10-22 23:32:23', '2019-10-22 23:32:23', '2019-10-22 00:00:00', 'detalle con foto', '1571805143repsol.png', 'En Proceso'),
(23, 29, 1, '2019-10-22 23:33:13', '2019-10-22 23:33:13', '2019-10-22 00:00:00', 'terminado', NULL, 'Terminado'),
(24, 43, 1, '2019-11-15 12:27:25', '2019-11-15 12:27:25', '2019-11-15 12:27:25', 'prueba fecha detalle automatica', NULL, 'En Proceso'),
(25, 45, 1, '2019-11-19 13:38:46', '2019-11-19 13:38:46', '2019-11-19 13:38:46', 'prurba final', NULL, 'En Proceso'),
(26, 45, 1, '2019-11-19 13:39:43', '2019-11-19 13:39:43', '2019-11-19 13:39:43', 'prueba final cierre', NULL, 'Terminado'),
(27, 43, 1, '2019-11-19 13:47:38', '2019-11-19 13:47:38', '2019-11-19 13:47:38', 'prueba cierre con dias', NULL, 'Terminado'),
(28, 43, 1, '2019-11-19 13:48:03', '2019-11-19 13:48:03', '2019-11-19 13:48:03', 'prueba cierre con dias', NULL, 'Terminado'),
(29, 43, 1, '2019-11-19 13:48:31', '2019-11-19 13:48:31', '2019-11-19 13:48:31', 'prueba cierre con dias', NULL, 'Terminado'),
(30, 43, 1, '2019-11-19 13:54:32', '2019-11-19 13:54:32', '2019-11-19 13:54:32', 'prueba final cierre', NULL, 'Terminado'),
(31, 44, 1, '2019-11-19 15:38:49', '2019-11-19 15:38:49', '2019-11-19 15:38:49', 'prueba id 44 cierre', NULL, 'Terminado'),
(32, 46, 1, '2019-11-19 15:40:23', '2019-11-19 15:40:23', '2019-11-19 15:40:23', 'ok recibido', NULL, 'En Proceso'),
(33, 46, 1, '2019-11-19 15:41:05', '2019-11-19 15:41:05', '2019-11-19 15:41:05', 'cierre 0437', NULL, 'Terminado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encargados_areas_atencion`
--

DROP TABLE IF EXISTS `encargados_areas_atencion`;
CREATE TABLE IF NOT EXISTS `encargados_areas_atencion` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(255) NOT NULL,
  `id_area_atencion` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `encargados_areas_atencion`
--

INSERT INTO `encargados_areas_atencion` (`id`, `id_usuario`, `id_area_atencion`) VALUES
(2, 1, 2),
(3, 4, 1),
(4, 4, 2),
(5, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

DROP TABLE IF EXISTS `equipos`;
CREATE TABLE IF NOT EXISTS `equipos` (
  `id` int(255) NOT NULL,
  `estacion` varchar(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `modelo` varchar(50) DEFAULT NULL,
  `serie` varchar(50) DEFAULT NULL,  
  `prioridad` varchar(10) NOT NULL,
  `id_area_estacion` int(255) NOT NULL,
  `posicion` boolean DEFAULT 0,
   CONSTRAINT pk_equipos PRIMARY KEY (id,estacion)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `estacion`, `descripcion`, `marca`, `modelo`, `serie`, `posicion`, `prioridad`, `id_area_estacion`, `id_area_atencion`) VALUES
(1, '6620', 'pc gerencia 6620', NULL, NULL, NULL, 'bomba1', 'alta', 5, 1),
(2, '6620', 'pc auxiliar 6620', NULL, NULL, NULL, NULL, 'media', 5, 1),
(3, '0437', 'taza de baño', NULL, NULL, NULL, NULL, 'baja', 11, 2),
(4, '6571', 'pc gerente', NULL, NULL, NULL, NULL, 'media', 32, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estaciones`
--

DROP TABLE IF EXISTS `estaciones`;
CREATE TABLE IF NOT EXISTS `estaciones` (
  `id_usuario` int(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estacion` varchar(10) NOT NULL,
  `id_compania` int(255) NOT NULL,
  `nombre_corto` varchar(30) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `permiso_expedido` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`estacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

DROP TABLE IF EXISTS `incidencias`;
CREATE TABLE IF NOT EXISTS `incidencias` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `folio` varchar(10) NOT NULL,
  `estacion` varchar(10) NOT NULL,
  `fecha_incidencia` datetime NOT NULL,
  `id_area_estacion` int(255) NOT NULL,
  `id_equipo` int(255) NOT NULL,
  `asunto` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `id_area_atencion` int(255) NOT NULL,
  `foto_ruta` varchar(255) DEFAULT NULL,
  `estatus_incidencia` varchar(10) NOT NULL,
  `tipo_solicitud` varchar(15) NOT NULL,
  `prioridad` varchar(10) NOT NULL,
  `fecha_ultima_actualizacion` datetime DEFAULT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `dias_vida_incidencia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `id_usuario`, `created_at`, `updated_at`, `folio`, `estacion`, `fecha_incidencia`, `id_area_estacion`, `id_equipo`, `asunto`, `descripcion`, `id_area_atencion`, `foto_ruta`, `estatus_incidencia`, `tipo_solicitud`, `prioridad`, `fecha_ultima_actualizacion`, `fecha_cierre`, `dias_vida_incidencia`) VALUES
(1, 2, '2019-07-03 18:12:06', '2019-10-22 17:10:18', 'inc-1', '6620', '2019-07-03 18:12:06', 5, 1, 'sdsdsd', 'pc gerencia', 1, NULL, 'CERRADA', 'incidencia', 'alta', NULL, NULL, 0),
(28, 1, '2019-10-17 15:59:53', '2019-10-22 17:13:16', 'inc-10', '6620', '2019-10-17 00:00:00', 5, 1, 'falla skype', 'falla skype favor de atendernos', 1, NULL, 'abierta', 'incidencia', 'media', NULL, NULL, 0),
(29, 1, '2019-10-17 18:21:24', '2019-10-22 23:33:13', 'inc-11', '6620', '2019-10-17 00:00:00', 5, 1, 'prueba', 'kjfkjdfkjdfkjd', 1, NULL, 'CERRADA', 'incidencia', 'alta', NULL, NULL, 0),
(30, 1, '2019-10-18 16:04:04', '2019-10-18 16:04:04', 'inc-12', '6620', '2019-10-18 00:00:00', 5, 1, 'x', 'dfdfdf', 1, NULL, 'abierta', 'incidencia', 'alta', NULL, NULL, 0),
(31, 1, '2019-10-18 16:59:24', '2019-10-22 18:02:46', 'inc-13', '6620', '2019-10-18 00:00:00', 5, 1, 'falla skype3', 'falla skype 3', 1, '1571785366sebas.png', 'abierta', 'incidencia', 'alta', NULL, NULL, 0),
(32, 1, '2019-10-19 14:24:27', '2019-10-19 14:24:27', 'inc-14', '6620', '2019-10-19 00:00:00', 5, 1, 'no enciende', 'la pc no enciende', 1, '1571513067cg sistemas.jpg', 'abierta', 'incidencia', 'baja', NULL, NULL, 0),
(33, 1, '2019-10-19 20:27:47', '2019-10-19 20:27:47', 'inc-15', '6620', '2019-10-19 00:00:00', 5, 1, 'se apaga', 'fdgfgfgfgfhfhgjgjgj', 1, '1571534867cel2.jpg', 'abierta', 'incidencia', 'alta', NULL, NULL, 0),
(34, 2, '2019-10-20 09:45:22', '2019-10-22 17:45:05', 'inc-16', '6620', '2019-10-20 00:00:00', 5, 1, 'prueba de imagen', 'prueba de imagen', 1, NULL, 'CERRADA', 'incidencia', 'alta', NULL, NULL, 0),
(35, 1, '2019-10-21 10:29:04', '2019-10-22 17:11:14', 'inc-17', '6620', '2019-10-21 00:00:00', 5, 1, 'falla la pc', 'esta fallando la pc', 1, NULL, 'CERRADA', 'incidencia', 'alta', NULL, NULL, 0),
(36, 1, '2019-10-22 23:19:42', '2019-10-22 23:19:42', 'inc-18', '6620', '2019-10-22 00:00:00', 5, 1, 'prueba', 'prueba', 1, '1571804382pemex.jpg', 'ABIERTA', 'incidencia', 'alta', NULL, NULL, 0),
(37, 3, '2019-10-23 17:06:54', '2019-10-24 18:38:58', 'inc-1', '0437', '2019-10-23 00:00:00', 11, 2, 'taza de baño', 'la taza tiene fuga', 2, NULL, 'CERRADA', 'incidencia', 'baja', NULL, NULL, 0),
(38, 2, '2019-10-24 18:18:08', '2019-10-24 18:21:42', 'inc-19', '6620', '2019-10-24 00:00:00', 5, 1, 'Pantalla Azul', 'Pantalla azul espontanea', 1, NULL, 'CERRADA', 'incidencia', 'baja', NULL, NULL, 0),
(39, 4, '2019-10-24 18:30:24', '2019-10-24 18:31:08', 'inc-2', '0437', '2019-10-24 00:00:00', 11, 2, 'prueba', 'prueba 0437', 2, NULL, 'CERRADA', 'incidencia', 'alta', NULL, NULL, 0),
(40, 4, '2019-10-24 18:34:39', '2019-10-24 18:35:17', 'req-1', '0437', '2019-10-24 00:00:00', 11, 2, 'agregar taza', 'agregar taza de baño', 2, NULL, 'CERRADA', 'requerimiento', 'media', NULL, NULL, 0),
(41, 1, '2019-11-04 11:49:15', '2019-11-04 11:49:15', 'inc-1', '6571', '2019-11-04 00:00:00', 32, 1, 'pc no prende', 'no prende', 1, '1572889754cerrar sesion.png', 'ABIERTA', 'incidencia', 'alta', NULL, NULL, 0),
(43, 1, '2019-11-15 12:21:37', '2019-11-19 13:54:32', 'inc-3', '0437', '2019-11-15 12:21:37', 11, 2, 'pruba', 'prueba fecha automatica', 2, NULL, 'ABIERTA', 'incidencia', 'alta', '2019-11-19 13:54:32', NULL, NULL),
(44, 1, '2019-11-16 13:31:04', '2019-11-19 15:38:50', 'inc-4', '0437', '2019-11-16 13:31:04', 11, 2, 'prueba', 'pruebadfdfdfdfdfdf', 2, NULL, 'CERRADA', 'incidencia', 'baja', '2019-11-19 15:38:49', '2019-11-19 15:38:49', 3),
(45, 1, '2019-11-19 13:38:22', '2019-11-19 13:39:43', 'inc-20', '6620', '2019-11-19 13:38:22', 5, 1, 'prueba final', 'prueba final', 1, NULL, 'CERRADA', 'incidencia', 'alta', '2019-11-19 13:39:43', '2019-11-19 13:39:43', NULL),
(46, 1, '2019-11-19 15:40:03', '2019-11-19 15:41:05', 'inc-5', '0437', '2019-11-19 15:40:03', 11, 2, 'prueba 0437', 'prueba 0437', 2, NULL, 'CERRADA', 'incidencia', 'baja', '2019-11-19 15:41:05', '2019-11-19 15:41:05', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias_logs`
--

DROP TABLE IF EXISTS `incidencias_logs`;
CREATE TABLE IF NOT EXISTS `incidencias_logs` (
  `id_usuario` int(255) NOT NULL,
  `id_incidencia` int(255) NOT NULL,
  `estatus` enum('VISTO') NOT NULL DEFAULT 'VISTO',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `incidencias_logs`
--

INSERT INTO `incidencias_logs` (`id_usuario`, `id_incidencia`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 34, 'VISTO', '2019-10-20 09:46:53', '2019-10-20 09:46:53'),
(2, 35, 'VISTO', '2019-10-21 12:20:47', '2019-10-21 12:20:47'),
(1, 1, 'VISTO', '2019-10-22 08:50:21', '2019-10-22 08:50:21'),
(1, 2, 'VISTO', NULL, NULL),
(1, 37, 'VISTO', '2019-10-23 17:12:02', '2019-10-23 17:12:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `role` varchar(20) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(200) DEFAULT NULL,
  `nick` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role`, `name`, `surname`, `nick`, `email`, `password`, `image`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, 'admin', 'ivan', NULL, NULL, 'icalzadas@grupogilga.com', '$2y$10$U3zKekOOhN9K0702T/cE7eL4f87CZqzy97xlfjINoA0tVQuXAQnl2', NULL, '2019-06-24 15:05:22', '2019-10-28 14:00:42', 'jMqwbwJbqLq3va1F1yCXI6V3dpVkYXunIxZAW6yHsjN4NV86cQpDW2MbvE6Z'),
(2, NULL, 'Gerente6620', NULL, NULL, 'gerencia6620@grupogilga.com', '$2y$10$EYQfdSm/iiJM4L5BCFDDUuWSk/feaOCDqF9MRhMKiaai5kLeSxHIO', NULL, '2019-07-26 11:13:22', '2019-07-26 11:13:22', 'aYsEXW23PYmJnOdhgvGaXI0gWx5NDT98bRGIe4jHfmfWMrAeYJiXXwiC53Jf'),
(3, NULL, 'gerente0437', NULL, NULL, 'gerencia0437@grupogilga.com', '$2y$10$LXaNKpGlQrdj.V7un1/f2Ogrl34Ds2tyII5w0tpmYnZeqsjShtaji', NULL, '2019-10-21 17:58:07', '2019-10-21 17:58:07', 'jzLtlvWYDUXUHeOPLHKKaTBGQwMDsDzvFXWg5X7ps1KFHZUA9fXVC8EX7G2J'),
(4, 'admin', 'Admin', NULL, NULL, 'admin@admin.com', '$2y$10$6dofF8/ptS6W9FAgFMkZBeA0LfKLuHTkuIrYVu3bgBPbT36aCo4I.', NULL, '2019-10-24 17:17:04', '2019-10-24 17:17:04', '6qrScq9wnyACt5RjF009lb9ZCQRfqhck2C3GBRshcvA4IqJeptu7j6m3AF9G');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_estaciones`
--

DROP TABLE IF EXISTS `usuarios_estaciones`;
CREATE TABLE IF NOT EXISTS `usuarios_estaciones` (
  `id_usuario` int(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `id_usuario_permiso` int(255) NOT NULL,
  `estacion` varchar(10) NOT NULL,
  PRIMARY KEY (`id_usuario_permiso`,`estacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios_estaciones`
--

INSERT INTO `usuarios_estaciones` (`id_usuario`, `created_at`, `updated_at`, `id_usuario_permiso`, `estacion`) VALUES
(1, NULL, NULL, 1, '0437'),
(1, NULL, NULL, 1, '1018'),
(1, NULL, NULL, 1, '1030'),
(1, NULL, NULL, 1, '1034'),
(1, NULL, NULL, 1, '1040'),
(1, NULL, NULL, 1, '1041'),
(1, NULL, NULL, 1, '10680'),
(1, NULL, NULL, 1, '10884'),
(1, NULL, NULL, 1, '10897'),
(1, NULL, NULL, 1, '11074'),
(1, NULL, NULL, 1, '11367'),
(1, NULL, NULL, 1, '12027'),
(1, NULL, NULL, 1, '12155'),
(1, NULL, NULL, 1, '12311'),
(1, NULL, NULL, 1, '3211'),
(1, NULL, NULL, 1, '4007'),
(1, NULL, NULL, 1, '4274'),
(1, NULL, NULL, 1, '4951'),
(1, NULL, NULL, 1, '5420'),
(1, NULL, NULL, 1, '6571'),
(1, NULL, NULL, 1, '6620'),
(1, NULL, NULL, 1, '6669'),
(1, NULL, NULL, 1, '6817'),
(1, NULL, NULL, 1, '7219'),
(1, NULL, NULL, 1, '7589'),
(1, NULL, NULL, 1, '7784'),
(1, NULL, NULL, 1, '8360'),
(1, NULL, NULL, 1, '9088'),
(1, NULL, NULL, 1, '9282'),
(1, NULL, NULL, 1, '9825'),
(1, NULL, NULL, 1, '9934'),
(1, '2019-10-19 00:00:00', '2019-10-19 00:00:00', 2, '6620'),
(1, '2019-10-23 00:00:00', '2019-10-23 00:00:00', 3, '0437'),
(1, NULL, NULL, 4, '0437'),
(1, NULL, NULL, 4, '1018'),
(1, NULL, NULL, 4, '1030'),
(1, NULL, NULL, 4, '1034'),
(1, NULL, NULL, 4, '1040'),
(1, NULL, NULL, 4, '1041'),
(1, NULL, NULL, 4, '10680'),
(1, NULL, NULL, 4, '10884'),
(1, NULL, NULL, 4, '10897'),
(1, NULL, NULL, 4, '11074'),
(1, NULL, NULL, 4, '11367'),
(1, NULL, NULL, 4, '12027'),
(1, NULL, NULL, 4, '12155'),
(1, NULL, NULL, 4, '12311'),
(1, NULL, NULL, 4, '3211'),
(1, NULL, NULL, 4, '4007'),
(1, NULL, NULL, 4, '4274'),
(1, NULL, NULL, 4, '4951'),
(1, NULL, NULL, 4, '5420'),
(1, NULL, NULL, 4, '6571'),
(1, NULL, NULL, 4, '6620'),
(1, NULL, NULL, 4, '6669'),
(1, NULL, NULL, 4, '6817'),
(1, NULL, NULL, 4, '7219'),
(1, NULL, NULL, 4, '7589'),
(1, NULL, NULL, 4, '7784'),
(1, NULL, NULL, 4, '8360'),
(1, NULL, NULL, 4, '9088'),
(1, NULL, NULL, 4, '9282'),
(1, NULL, NULL, 4, '9825'),
(1, NULL, NULL, 4, '9934');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
