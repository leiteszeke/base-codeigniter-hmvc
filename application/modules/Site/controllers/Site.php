<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MX_Controller {
	private $_data;

	function __construct() {
		parent::__construct();

		$this->_data = array();

		// Data basica
		$this->_data['base_url'] = $this->config->item('base_url');
		$this->_data['titulo']   = $this->config->item('site_name');

		// Partes de la pagina
		$this->_data['head']   = $this->parser->parse("common/head_inc", $this->_data, true);
		$this->_data['footer'] = $this->parser->parse("common/footer_inc", $this->_data, true);
		$this->_data['menu']   = $this->parser->parse("common/menu_inc", $this->_data, true);

		// Analytics
		$this->_data['analytics'] = (APP_ENV == "prod") ? $this->parser->parse("common/analytics_inc", $this->_data, true) : "";
	}

	public function index() {
		$this->parser->parse('home_view', $this->_data);
	}

	public function error404(){
		echo "error 404";
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */