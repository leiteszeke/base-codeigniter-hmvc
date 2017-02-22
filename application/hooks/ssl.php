<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function redirect_ssl() {
    $CI =& get_instance();
    $class = $CI->router->fetch_class();
    $exclude =  array();  // add more controller name to exclude ssl.
    if(!in_array($class,$exclude)) {
      // redirecting to ssl.
      $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
      
      if ($_SERVER['SERVER_PORT'] != 443) {
        $url = explode("/", $_SERVER['PATH_INFO']);
        $url = array_values(array_filter($url, "strlen"));
        
        $uri = "";
        foreach($url as $valor){
            $uri .= $valor."/";
        }
        
        redirect($CI->config->config['base_url'].$uri);
      }
    }else{
      // redirecting with no ssl.
      $CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
      
      if ($_SERVER['SERVER_PORT'] == 443) { 
        redirect($CI->config->config['base_url']);
      }
    }
}

?>