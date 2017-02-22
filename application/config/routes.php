<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
*/

$route['_ajax/(:any)/(:any)'] = "Site/Ajax_controller/$1/$2";
$route['_ajax/(:any)']        = "Site/Ajax_controller/$1";
$route['_ajax']               = "Site/Ajax_controller";

$route['default_controller']   = 'Site';
$route['404_override']         = 'Site/error404';
$route['translate_uri_dashes'] = FALSE;
