<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends MX_Controller {
	private $_data;

	/**
	*
	* */
	public function __construct() {
		parent::__construct();

		$this->_data = array();

		if(!is_logged_in()) {
			header("Location: ".$this->config->item('base_url').'admin/login');
		}else {
			$this->_usuarioLogueado = $this->session->userdata('usuariologueado');
			$this->_data['username'] = $this->_usuarioLogueado['username'];
		}

		foreach(common() as $k=>$v) {
			$this->_data[$k] = $v;
		}

		$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);
	}

	public function show_404($objeto = null) {
		$this->parser->parse('error_404.php', $this->_data);
	}

	public function show_403($objeto = null) {
		$this->parser->parse('error_403.php', $this->_data);
	}
}