-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 29-09-2016 a las 15:52:41
-- Versión del servidor: 5.5.37-MariaDB
-- Versión de PHP: 5.4.45-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `DM_estructura_base`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_opciones`
--

CREATE TABLE IF NOT EXISTS `admin_opciones` (
`id_admin_opciones` int(11) NOT NULL,
  `id_admin_secciones` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `friendly_url` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL DEFAULT '0',
  `mostrar_menu` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `admin_opciones`
--

INSERT INTO `admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES
(1, 1, 'Agregar Sección', 'agregar', 'icono-user.png', 1, 1, '2016-09-28 19:41:09', NULL, 0, 1),
(2, 1, 'Listar Secciones', 'listar', 'icono-user.png', 2, 1, '2016-09-28 20:23:06', NULL, 0, 1),
(3, 2, 'Agregar Administrador', 'agregar', 'icono-user.png', 1, 1, '2016-09-29 18:49:52', NULL, 0, 1),
(4, 2, 'Listar Administradores', 'listar', 'icono-user.png', 2, 1, '2016-09-29 18:49:52', NULL, 0, 1),
(5, 3, 'Agregar Opciones', 'agregar', 'icono-user.png', 1, 1, '2016-09-28 19:41:23', NULL, 0, 1),
(6, 3, 'Listar Opciones', 'listar', 'icono-user.png', 2, 1, '2016-09-28 19:41:26', NULL, 0, 1),
(7, 4, 'Asignar Permisos Secciones', 'secciones/agregar', 'icono-user.png', 1, 1, '2016-09-29 18:49:52', NULL, 0, 1),
(8, 4, 'Listar Permisos Secciones', 'secciones/listar', 'icono-user.png', 2, 1, '2016-09-28 20:50:02', NULL, 0, 1),
(9, 4, 'Asignar Permisos Opciones', 'opciones/agregar', 'icono-user.png', 3, 1, '2016-09-29 18:49:52', NULL, 0, 1),
(10, 4, 'Listar Permisos Opciones', 'opciones/listar', 'icono-user.png', 4, 1, '2016-09-28 20:51:56', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_secciones`
--

CREATE TABLE IF NOT EXISTS `admin_secciones` (
`id_admin_secciones` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `friendly_url` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1',
  `mostrar_menu` tinyint(1) NOT NULL DEFAULT '1',
  `es_home` int(1) DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `admin_secciones`
--

INSERT INTO `admin_secciones` (`id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `created`, `updated`, `deleted`, `publicado`, `mostrar_menu`, `es_home`) VALUES
(1, 'Secciones', 'secciones', 'icono-user.png', 4, '2016-09-29 18:49:20', NULL, 0, 1, 1, 1),
(2, 'Administradores', 'administradores', 'icono-user.png', 1, '2016-09-29 18:49:20', NULL, 0, 1, 1, 1),
(3, 'Opciones', 'opciones', 'icono-user.png', 2, '2016-09-29 18:49:20', NULL, 0, 1, 1, 1),
(4, 'Permisos', 'permisos', 'icono-user.png', 3, '2016-09-29 18:49:20', NULL, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
`id_admin_user` int(11) NOT NULL,
  `username` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `first_name` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `last_name` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `admin_user`
--

INSERT INTO `admin_user` (`id_admin_user`, `username`, `password`, `email_address`, `first_name`, `last_name`, `created`, `updated`, `deleted`, `publicado`) VALUES
(1, 'cmansilla', 'fe01ce2a7fbac8fafaed7c982a04e229', 'cesar@dmfusion.com', 'Cesar', 'Mansilla', '2016-09-26 18:18:03', NULL, 0, 1),
(2, 'eleites', 'fe01ce2a7fbac8fafaed7c982a04e229', 'ezequiel@dmfusion.com', 'Ezequiel', 'Leites', '2016-09-26 18:18:03', NULL, 0, 1),
(3, 'sman', 'fe01ce2a7fbac8fafaed7c982a04e229', 'sergio@dmfusion.com', 'Sergio', 'Man', '2016-09-26 18:18:03', NULL, 0, 1),
(4, 'usuario', 'fe01ce2a7fbac8fafaed7c982a04e229', 'usuario@dmfusion.com', 'Usuario', 'Demo', '2016-09-26 18:40:34', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_user_opcion_permisos`
--

CREATE TABLE IF NOT EXISTS `admin_user_opcion_permisos` (
`id_admin_user_opcion_permisos` int(11) NOT NULL,
  `id_admin_user` int(11) NOT NULL,
  `id_admin_opciones` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=31 ;

--
-- Volcado de datos para la tabla `admin_user_opcion_permisos`
--

INSERT INTO `admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 2, 1),
(12, 2, 2),
(13, 2, 3),
(14, 2, 4),
(15, 2, 5),
(16, 2, 6),
(17, 2, 7),
(18, 2, 8),
(19, 2, 9),
(20, 2, 10),
(21, 3, 1),
(22, 3, 2),
(23, 3, 3),
(24, 3, 4),
(25, 3, 5),
(26, 3, 6),
(27, 3, 7),
(28, 3, 8),
(29, 3, 9),
(30, 3, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_user_seccion_permisos`
--

CREATE TABLE IF NOT EXISTS `admin_user_seccion_permisos` (
`id_admin_user_seccion_permisos` int(11) NOT NULL,
  `id_admin_user` int(11) NOT NULL,
  `id_admin_secciones` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `admin_user_seccion_permisos`
--

INSERT INTO `admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`, `id_admin_user`, `id_admin_secciones`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 2, 3),
(8, 2, 4),
(9, 2, 1),
(10, 2, 2),
(11, 2, 3),
(12, 2, 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_opciones`
--
ALTER TABLE `admin_opciones`
 ADD PRIMARY KEY (`id_admin_opciones`), ADD KEY `id_admin_secciones` (`id_admin_secciones`);

--
-- Indices de la tabla `admin_secciones`
--
ALTER TABLE `admin_secciones`
 ADD PRIMARY KEY (`id_admin_secciones`), ADD UNIQUE KEY `friendly_url` (`friendly_url`);

--
-- Indices de la tabla `admin_user`
--
ALTER TABLE `admin_user`
 ADD PRIMARY KEY (`id_admin_user`), ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `admin_user_opcion_permisos`
--
ALTER TABLE `admin_user_opcion_permisos`
 ADD PRIMARY KEY (`id_admin_user_opcion_permisos`), ADD UNIQUE KEY `id_admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`), ADD KEY `FK_admin_user_opcion_permisos_admin_opciones` (`id_admin_opciones`);

--
-- Indices de la tabla `admin_user_seccion_permisos`
--
ALTER TABLE `admin_user_seccion_permisos`
 ADD PRIMARY KEY (`id_admin_user_seccion_permisos`), ADD UNIQUE KEY `id_admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`), ADD KEY `FK_admin_user_seccion_permisos_admin_secciones` (`id_admin_secciones`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_opciones`
--
ALTER TABLE `admin_opciones`
MODIFY `id_admin_opciones` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `admin_secciones`
--
ALTER TABLE `admin_secciones`
MODIFY `id_admin_secciones` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `admin_user`
--
ALTER TABLE `admin_user`
MODIFY `id_admin_user` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `admin_user_opcion_permisos`
--
ALTER TABLE `admin_user_opcion_permisos`
MODIFY `id_admin_user_opcion_permisos` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT de la tabla `admin_user_seccion_permisos`
--
ALTER TABLE `admin_user_seccion_permisos`
MODIFY `id_admin_user_seccion_permisos` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
