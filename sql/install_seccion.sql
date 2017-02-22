// Creacion Secciones
INSERT INTO `DM_estructura_base`.`admin_secciones` (`id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `created`, `updated`, `deleted`, `publicado`, `mostrar_menu`, `es_home`) VALUES (NULL, 'Secciones', 'secciones', 'icono-user.png', '3', CURRENT_TIMESTAMP, NULL, '0', '1', '1', '1');
INSERT INTO `DM_estructura_base`.`admin_secciones` (`id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `created`, `updated`, `deleted`, `publicado`, `mostrar_menu`, `es_home`) VALUES (NULL, 'Usuarios', 'usuarios', 'icono-user.png', '4', CURRENT_TIMESTAMP, NULL, '0', '1', '1', '1');
INSERT INTO `DM_estructura_base`.`admin_secciones` (`id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `created`, `updated`, `deleted`, `publicado`, `mostrar_menu`, `es_home`) VALUES (NULL, 'Opciones', 'opciones', 'icono-user.png', '1', CURRENT_TIMESTAMP, NULL, '0', '1', '1', '1');
INSERT INTO `DM_estructura_base`.`admin_secciones` (`id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `created`, `updated`, `deleted`, `publicado`, `mostrar_menu`, `es_home`) VALUES (NULL, 'Permisos', 'permisos', 'icono-user.png', '2', CURRENT_TIMESTAMP, NULL, '0', '1', '1', '1');

// Asignación Permisos
INSERT INTO `DM_estructura_base`.`admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`, `id_admin_user`, `id_admin_secciones`) VALUES (NULL, '1', '1');
INSERT INTO `DM_estructura_base`.`admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`, `id_admin_user`, `id_admin_secciones`) VALUES (NULL, '1', '2');
INSERT INTO `DM_estructura_base`.`admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`, `id_admin_user`, `id_admin_secciones`) VALUES (NULL, '1', '3');
INSERT INTO `DM_estructura_base`.`admin_user_seccion_permisos` (`id_admin_user_seccion_permisos`, `id_admin_user`, `id_admin_secciones`) VALUES (NULL, '1', '4');

// Creación Opciones
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '1', 'Agregar Sección', 'agregar', 'icono-user.png', '1', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '1', 'Listar Secciones', 'listar', 'icono-user.png', '2', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '2', 'Agregar Usuarios', 'agregar', 'icono-user.png', '1', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '2', 'Listar Usuarios', 'listar', 'icono-user.png', '2', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '3', 'Agregar Opciones', 'agregar', 'icono-user.png', '1', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '3', 'Listar Opciones', 'listar', 'icono-user.png', '2', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '4', 'Agregar Permisos', 'agregar', 'icono-user.png', '1', '1', CURRENT_TIMESTAMP, NULL, '0', '1');
INSERT INTO `DM_estructura_base`.`admin_opciones` (`id_admin_opciones`, `id_admin_secciones`, `nombre`, `friendly_url`, `icono`, `orden`, `mostrar_menu`, `created`, `updated`, `deleted`, `publicado`) VALUES (NULL, '4', 'Listar Permisos', 'listar', 'icono-user.png', '2', '1', CURRENT_TIMESTAMP, NULL, '0', '1');

// Asignación Permisos
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '1');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '2');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '3');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '4');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '5');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '6');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '7');
INSERT INTO `DM_estructura_base`.`admin_user_opcion_permisos` (`id_admin_user_opcion_permisos`, `id_admin_user`, `id_admin_opciones`) VALUES (NULL, '1', '8');