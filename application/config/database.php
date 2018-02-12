<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$dotenv = new Dotenv\Dotenv(__DIR__ . '/../../');
	$dotenv->load();	

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/

$active_group = 'default';
$active_record = TRUE;
$query_builder = TRUE;

$db['default']['hostname'] = getenv('DB_HOST');
$db['default']['username'] = getenv('DB_USER');
$db['default']['password'] = getenv('DB_PASS');
$db['default']['database'] = getenv('DB_BASE');
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