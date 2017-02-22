<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
    require_once( APPPATH . 'libraries/Files/FilesHelper.php' );

    class Files {
        var $ci;
        var $helper;
        var $session;
    
        public function __construct() {
            $this->ci =& get_instance();

            $this->helper = new FilesHelper();
        }

        public function subirArchivo($carpeta, $archivo, $hash = false){
            return $this->helper->subirArchivo($carpeta, $archivo, $hash);
        }
        
        public function normalizarArchivos($archivos){
            return $this->helper->normalizarArchivos($archivos);
        }

        public function validarArchivo($ruta){
            return $this->helper->validarArchivo($ruta);
        }
    }

?>