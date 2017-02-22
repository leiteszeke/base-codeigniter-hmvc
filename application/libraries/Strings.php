<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    require_once( APPPATH . 'libraries/Strings/StringsHelper.php' );

    class Strings {
        var $ci;
        var $helper;
        var $session;
    
        public function __construct() {
            $this->ci =& get_instance();

            $this->helper = new StringsHelper();
        }


        public function quitarTildes($string){
            return $this->helper->quitarTildes($string);
        }
    }

?>