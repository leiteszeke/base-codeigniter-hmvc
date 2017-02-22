<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	!defined("APP_ENV") ? define("APP_ENV", getenv('APP_ENV')) : "";

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/

$active_group = 'default';
$active_record = TRUE;
$query_builder = TRUE;

switch(APP_ENV){
	case "prod":
		$host = "";
		$user = "";
		$pass = "";
		$base = "";
		break;
	case "dev":
		$host = "";
		$user = "";
		$pass = "";
		$base = "";
		break;
	case "loc":
		$host = "190.191.242.44";
		$user = "root";
		$pass = "Vaseli490na21";
		$base = "DM_estructura_base";
		break;
	default:
		$host = "190.191.242.44";
		$user = "root";
		$pass = "Vaseli490na21";
		$base = "DM_estructura_base";
		break;
}

$db['default']['hostname'] = $host;
$db['default']['username'] = $user;
$db['default']['password'] = $pass;
$db['default']['database'] = $base;
$db['default']['dsn']	= '';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = (ENVIRONMENT !== 'production');
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['encrypt'] = FALSE;
$db['default']['compress'] = FALSE;
$db['default']['stricton'] = FALSE;
$db['default']['failover'] = array();
$db['default']['save_queries'] = TRUE;

?>