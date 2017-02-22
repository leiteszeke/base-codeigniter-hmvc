<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* PATCH PARA MANTENER COOKIES EN IFRAME PARA IE */
if (stristr($_SERVER['HTTP_USER_AGENT'], 'Safari')){
    //$config->fbconfig_cookieOverride = 1;
}else if (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')){
    // header('p3p: CP="ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV"');
}
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

$config['fb_fanpage_id']           = array('');
$config['permissions']             = array();
$config['protocolo']               = (isset($_SERVER['HTTPS'])) ? "https://" : "http://"; 

if(APP_ENV == "prod"){
    $config['fb_appid']                = '';
    $config['fb_secret']               = '';
    $config['fb_canvas']               = '';

    $config['fb_app_url']              = "";
    $config['fb_fanpage_url']          = "";
    $config['fb_fanpage_to_like']      = "";
}elseif(APP_ENV == "dev"){
    $config['fb_appid']                = '';
    $config['fb_secret']               = '';
    $config['fb_canvas']               = '';

    $config['fb_app_url']              = "";
    $config['fb_fanpage_url']          = "";
    $config['fb_fanpage_to_like']      = "";
}else{
    $config['fb_appid']                = '';
    $config['fb_secret']               = '';
    $config['fb_canvas']               = '';

    $config['fb_app_url']              = "";
    $config['fb_fanpage_url']          = "";
    $config['fb_fanpage_to_like']      = "";
}
    
$config['client_name']	           = 'DMFusion';
    
$config['solo_aplicacion_externa'] = true; // sirve para que una aplicacion se pueda cargar en solapa y de forma externa (apps.facebook.com/...) o solamente de forma externa
?>