<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once( APPPATH . 'libraries/Paginador/PaginadorHelper.php' );

class Paginador {
    var $ci;
    var $helper;
    var $session;
 
    public function __construct() {
        $this->ci =& get_instance();

        $this->helper = new PaginadorHelper();
 	}


	public function generarPaginador($nPagina, $totalPaginas, $offset_left, $offset_right, $url = false){
		return $this->helper->generarPaginador($nPagina, $totalPaginas, $offset_left, $offset_right, $url);
	}
}

?>