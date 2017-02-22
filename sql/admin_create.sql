CREATE TABLE IF NOT EXISTS `admin_user` (
  `id_admin_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `first_name` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `last_name` varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_admin_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `admin_secciones` (
  `id_admin_secciones` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `friendly_url` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1',
  `mostrar_menu` tinyint(1) NOT NULL DEFAULT '1',
  `es_home` TINYINT(1) NOT NULL DEFAULT '1'
  PRIMARY KEY (`id_admin_secciones`),
  UNIQUE KEY `friendly_url` (`friendly_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `admin_opciones` (
  `id_admin_opciones` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin_secciones` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `friendly_url` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `icono` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `orden` int(11) NOT NULL DEFAULT '0',
  `mostrar_menu` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `publicado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_admin_opciones`),
  KEY `id_admin_secciones` (`id_admin_secciones`),
  CONSTRAINT `FK_admin_opciones_admin_secciones` FOREIGN KEY (`id_admin_secciones`) REFERENCES `admin_secciones` (`id_admin_secciones`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `admin_user_seccion_permisos` (
  `id_admin_user_seccion_permisos` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin_user` int(11) NOT NULL,
  `id_admin_secciones` int(11) NOT NULL,
  PRIMARY KEY (`id_admin_user`,`id_admin_secciones`),
  UNIQUE KEY `id_admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`),
  KEY `FK_admin_user_seccion_permisos_admin_secciones` (`id_admin_secciones`),
  CONSTRAINT `FK_admin_user_seccion_permisos_admin_user` FOREIGN KEY (`id_admin_user`) REFERENCES `admin_user` (`id_admin_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_admin_user_seccion_permisos_admin_secciones` FOREIGN KEY (`id_admin_secciones`) REFERENCES `admin_secciones` (`id_admin_secciones`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE IF NOT EXISTS `admin_user_opcion_permisos` (
  `id_admin_user_opcion_permisos` int(11) NOT NULL AUTO_INCREMENT,
  `id_admin_user` int(11) NOT NULL,
  `id_admin_opciones` int(11) NOT NULL,
  PRIMARY KEY (`id_admin_user`,`id_admin_opciones`),
  UNIQUE KEY `id_admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`),
  KEY `FK_admin_user_opcion_permisos_admin_opciones` (`id_admin_opciones`),
  CONSTRAINT `FK_admin_user_opcion_permisos_admin_user` FOREIGN KEY (`id_admin_user`) REFERENCES `admin_user` (`id_admin_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_admin_user_opcion_permisos_admin_opciones` FOREIGN KEY (`id_admin_opciones`) REFERENCES `admin_opciones` (`id_admin_opciones`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;
