<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {
	private $_data;
	private $_usuarioLogueado;
	private $_seccionesConPermiso;
	private $_error;

	/**
	* Se asegura tener sesión activa y en caso positivo
	* obtiene los datos del usuario y sus secciones.
	* */
	public function __construct() {
		parent::__construct();
		log_message('DEBUG',__METHOD__);

		if(!is_logged_in()) {
			$this->session->set_userdata(array($this->config->item('encryption_key').'_volver'=>"http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"));
			header("Location: ".$this->config->item('base_url').'admin/login');
			die;
		}
		require_once($this->config->item('base_path').'application/controllers/Error_controller.php');
		$this->_error = new Error();

		$this->_data = array();

		$this->_usuarioLogueado = $this->session->userdata($this->config->item('encryption_key'));
		$this->_data['username'] = $this->_usuarioLogueado['username'];
		$this->_seccionesConPermiso = $this->_usuarioLogueado['secciones_con_permiso'];

		foreach(common() as $k=>$v) {
			$this->_data[$k] = $v;
		}

		$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);
	}

	/**
	* Accede al panel
	* */
	public function index() {
		$this->panel();
	}

	/**
	* Es el panel de control del usuario.
	* Se mostrar�n las secciones a la que tiene acceso.
	* */
	public function panel() {
		$this->_data['secciones'] = $this->_data['sinsecciones'] = array();
		$this->_data["secciones_home"] = array();

		if(empty($this->_seccionesConPermiso)) {
			$this->_data['sinsecciones'] = array(array());
		}else {
			$secciones = $this->_seccionesConPermiso;
			$this->_data["secciones_home"] = array();
			foreach($secciones as $i=>$seccion) {
				if($seccion['mostrar_menu'] == 0) {
					unset($secciones[$i]);
					continue;
				}

				if(isset($seccion["es_home"]) && $seccion["es_home"] == 1){
					$seccion["base_url"] = $this->_data["base_url"];
					$this->_data["secciones_home"][] = $seccion;
				}
				$secciones[$i]['base_url'] = $this->_data['base_url'];
			}
			$this->_data['secciones'] = $secciones;
		}
		$this->parser->parse('Admin/panel_view', $this->_data);
	}

	/**
	*
	* */
	public function modificarClave() {
		if($this->input->post()) {
			if($this->input->post('password') && $this->input->post('passwordconfirm')) {
				if($this->input->post('password') === $this->input->post('passwordconfirm')) {
					$chg = $this->Membership_model->change_password(
						md5($this->input->post('password')),$this->input->post('id_user')
					);
					if($chg) {
						array_push($this->_data['error'], array('mensaje'=>'Contrase&ntilde;a modificada','alerta' =>'alert-success'));
					}else {
						array_push($this->_data['error'], array('mensaje'=>'Contrase&ntilde;a no modificada','alerta' =>'alert-danger'));
					}
				}else {
					array_push($this->_data['error'], array('mensaje'=>'Verifique las claves ingresadas','alerta' =>'alert-danger'));
				}
			}else {
				array_push($this->_data['error'], array('mensaje'=>'Ingrese los datos solicitados.','alerta' =>'alert-danger'));
			}
			$this->parser->parse('Admin/usuarios/usuarios_modificar_clave.php', $this->_data);
		}else {
			$this->_data['id_admin_user'] = $this->_usuarioLogueado['id'];
			$this->parser->parse('Admin/usuarios/usuarios_modificar_clave.php', $this->_data);
		}
	}

	/**
	* Cuando se accede por route a admin/(:any) se accede a este sitio
	* para listar las opciones que tiene habilitadas sobre la secci�n.
	* */
	public function listaropciones() {
		$this->_data['opciones'] = $this->_data['sinopciones'] = array();

		$seccion = $this->Admin_model->getSeccionByFriendlyUrl($this->uri->segment(2));

		if(search_array_in_array($this->uri->segment(2), $this->_seccionesConPermiso) >= 0) {

			$opcionesConPermiso = $this->Admin_model->getOpcionesSeccionByUser($seccion['id_admin_secciones'], $this->_usuarioLogueado['id']);

			if(empty($opcionesConPermiso)) {
				$this->_data['sinopciones'] = array(array());
			}else {
				foreach($opcionesConPermiso as $i=>$opcion) {
					$opcionesConPermiso[$i]['base_url'] = $this->_data['base_url'];
					$opcionesConPermiso[$i]['seccion'] = $this->uri->segment(2);
				}
				$this->_data['opciones'] = $opcionesConPermiso;
			}

			$this->parser->parse('Admin/opciones_view', $this->_data);
		}else {
			$this->_error->show_403();
		}
	}

	/**
	* Cuando se accede por route a admin/(:any)/(:any) o admin/(:any)/(:any)/(:any) se accede a este sitio
	* para invocar a la clase correcta.
	* */
	public function abriropcion() {
		$url   = ucfirst($this->uri->segment(2));
		$clase = str_replace("-","_",$url);

		if(file_exists(__DIR__.'/'.$clase.'.php')) {
			require_once(__DIR__.'/'.$clase.'.php');
			$clase = new $clase();
			$clase->friendly($this->uri->segment(3));
		}else {
			log_message('error',$clase." ".__METHOD__);
			$this->_error->show_404();
		}
	}


	function templatefortable() {
		if($this->uri->segment(3)) {
			$nombre = $this->uri->segment(3);

			$this->load->model('Admin/Secciones_model');
			$d      = $this->Secciones_model->getTypeField($nombre);
			d($d);
		}else {
			$this->parser->parse('Admin/template/seleccionar_tabla', $this->_data);
		}
	}
}