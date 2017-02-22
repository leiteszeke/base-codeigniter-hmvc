<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
*/
$autoload['libraries'] = array('parser', 'database', 'session', 'paginador');

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
*/
$autoload['helper'] = array('admin', 'url');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
*/
$autoload['config'] = array('admin_config');

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
*/
$autoload['model'] = array(
		'Admin_model', 'Administradores_model', 'Membership_model',
		'Opciones_model', 'Permisos_model','Secciones_model',
		'Abm_model'
);