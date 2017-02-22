<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MX_Controller {
	private $_data;
	private $_ruta;
	private $_instalated;

	function __construct() {
		parent::__construct();

		$this->_data = array();

		// No se implementa aún la instalación
		//$this->validarInstalacion();

		// Data basica
		$this->_data['base_url'] = $this->config->item('base_url');
		$this->_data['fb_app_url'] = $this->config->item('fb_app_url');
		$this->_data['site_name'] = $this->config->item('site_name');
		$this->_data['protocolo'] = $this->config->item('protocolo');

		$this->_data['titulo'] = $this->config->item('client_name') . " " . $this->config->item('site_name');

		// Metadata
		$this->_data['og_title'] = $this->config->item('og_title');
		$this->_data['og_url'] = $this->config->item('og_url');
		$this->_data['og_image'] = $this->config->item('og_image');
		$this->_data['og_description'] = $this->config->item('og_description');
		$this->_data['meta_keywords'] = $this->config->item('meta_keywords');
		$this->_data['meta_description'] = $this->config->item('meta_description');

		// Partes de la pagina
		$this->_data['head'] = $this->parser->parse("common/head_inc", $this->_data, true);
		$this->_data['footer'] = $this->parser->parse("common/footer_inc", $this->_data, true);
		$this->_data['menu'] = $this->parser->parse("common/menu_inc", $this->_data, true);

		// Analytics
		$this->_data['analytics'] = (APP_ENV == "prod") ? $this->parser->parse("common/analytics_inc", $this->_data, true) : "";
	}

	private function validarInstalacion(){
		$this->_instalated = $this->Instalacion_model->validarInstalacion();
		$this->_ruta       = $this->Instalacion_model->getRuta();

		if(!$this->_instalated){
			redirect("http://" . $this->_ruta . "install");
		}
	}

	public function index() {
		$this->parser->parse('home_view', $this->_data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */