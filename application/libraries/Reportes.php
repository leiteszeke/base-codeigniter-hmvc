<?php 
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    require_once( APPPATH . 'libraries/Reportes/ReportesHelper.php' );

    class Reportes {
        var $ci;
        var $helper;
        var $session;

        public function __construct() {
            $this->ci     =& get_instance();
            $this->helper = new ReportesHelper();
        }

        public function generarReporte($data, $headers, $body, $nombre){
            return $this->helper->generarReporte($data, $headers, $body, $nombre);
        }
    }

?>