<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tsk extends CI_Controller {
	private $_data;
	private $_urladmin;

	function __construct() {
		parent::__construct();

		// data basica
		$this->_data['base_url'] = $this->config->item('base_url');
		$this->_data['protocolo'] = $this->config->item('protocolo');
		$this->_data['site_name'] = $this->config->item('site_name');

		// configuraciones generales
		$this->_data['titulo'] = $this->_data['site_name'];

		// partes de la pagina
		$this->_data['head'] = $this->parser->parse("common/head_inc", $this->_data, true);
		$this->_data['header'] = $this->parser->parse("common/header_inc", $this->_data, true);
		$this->_data['footer'] = $this->parser->parse("common/footer_inc", $this->_data, true);
		$this->_data['menu'] = $this->parser->parse("Site/common/menu_inc", $this->_data, true);

		$this->load->model('Migrar_model');

	}

	function index() {
		$this->Migrar_model->select_old();
	}

}
?>