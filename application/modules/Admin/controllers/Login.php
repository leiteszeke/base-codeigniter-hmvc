<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {
	private $_data;

	/**
	* Obtiene datos básicos para la carga de la vista
	* */
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
		$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);

		$this->_data['error'] = array();
	}

	/**
	* Verifica sesión de usuario
	* Envía a login o a panel
	* */
	public function index() {
		log_message('DEBUG',__METHOD__);
		if(!is_logged_in()) {
			$this->parser->parse('Admin/login/login_form.php', $this->_data);
		}else {
			header("Location: ".$this->_data['base_url']."admin/panel");
		}
	}

	/**
	* Valida al usuario al iniciar sesión
	* Envia $_POST a validate()
	* */
	public function validar_credenciales() {
		log_message('DEBUG',__METHOD__);
		$valid = $this->Membership_model->validate();

		if($valid) {
			$data = array(
				$this->config->item('encryption_key').'_is_logged_in'=> true
			);
			$this->session->set_userdata($data);

			if($this->session->userdata($this->config->item('encryption_key').'_volver')) {
				$volver = $this->session->userdata($this->config->item('encryption_key').'_volver');
				$this->session->unset_userdata($this->config->item('encryption_key').'_volver');
				header("Location: ".$volver);
			}else {
			header("Location: ".$this->_data['base_url']."admin/panel");
			}

		}else {
			array_push($this->_data['error'], array('mensaje'=>"Credenciales incorrectas"));
			$this->parser->parse('Admin/login/login_form.php', $this->_data);
		}
	}

	/**
	* Elimina la sesión y vuelve al login
	* */
	public function logout() {
		log_message('DEBUG',__METHOD__);
		$this->session->unset_userdata($this->config->item('encryption_key').'_is_logged_in');

		$user_data = $this->session->all_userdata();
		foreach ($user_data as $key => $value) {
			$this->session->unset_userdata($key);
		}
		
		$this->session->sess_destroy();
		
		
		if(!is_logged_in()) {
			header("Location: ".$this->_data['base_url'].'admin/login');
		}
	}
}