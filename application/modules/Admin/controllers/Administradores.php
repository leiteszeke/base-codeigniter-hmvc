<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once($this->config->item('base_path').'application/controllers/Error_controller.php');

	class Administradores extends MX_Controller {
		private $_data;
		private $_nombreDeTabla;
		private $_usuarioLogueado;
		private $_error;
		private $_limit;

		/**
		* Carga la informacion basica.
		* */
		function __construct() {
			parent::__construct();

			$this->_data = array();
			$this->_error = new Error();

			foreach(common() as $k=>$v) {
				$this->_data[$k] = $v;
			}

			if(!is_logged_in()) {
				header("Location: ".$this->_data['base_url'].'admin/login');
			}

			$this->_usuarioLogueado = $this->session->userdata($this->config->item('encryption_key'));
			$this->_data['username'] = $this->_usuarioLogueado['username'];

			$this->initMenu();
			$this->_nombreDeTabla = "admin_user";

			$this->_data["encontrado"] = array();
			$this->_data["noencontrado"] = array();
			$this->_limit = $this->config->item("limit_admins");
		}

		private function initMenu(){
			$url = $this->uri->segment(3);
			$d   = $this->Admin_model->getOpcionesByFriendlyUrl($url);
			$this->_data["actual_" . $d["id_admin_secciones"]] = "active";
			$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);
		}

		/**
		* Obtiene la seccion
		*
		*/
		public function index() {
			echo $this->uri->segment(2);
		}

		/**
		* Invoca al metodo segun la url
		*
		*/
		public function friendly() {
			$url = $this->uri->segment(3);
			$d   = $this->Admin_model->getOpcionesByFriendlyUrl($url);

			if(search_array_in_array($d['id_admin_opciones'],$this->_data['opciones_con_permiso']) >= 0) {
				$metodo = unfriendlizar($url,'-');

				if(method_exists(__CLASS__,$metodo)) {
					$this->$metodo();
				}else {
					log_message('error',$metodo." ".__METHOD__);
					$this->_error->show_404($metodo);
				}
			}else {
				$this->_error->show_403();
			}
		}

		public function agregar() {
			$this->parser->parse('Admin/administradores/agregar_administradores_view', $this->_data);
		}
		
		public function editar() {
			$id_admin      = $this->uri->segment(4);
			$administrador = $this->Administradores_model->getAdministrador($id_admin);
			$this->_data["administrador"][0] = $administrador;
			$this->parser->parse('Admin/administradores/editar_administradores_view', $this->_data);
		}

		/**
		* Lista los registros que hayan de esta seccion
		* */
		public function listar() {
			$this->_data["usuarios"]  = array();
			$this->_data["paginador"] = array();
			$pagina = 1;
			$nombre = false;
			$email  = false;
			$url    = false;

			if($this->uri->segment(5) && is_numeric($this->uri->segment(5))){
				$url    = $this->uri->segment(4);
				$pagina = $this->uri->segment(5);
			}else{
				if(is_numeric($this->uri->segment(4))){
					$pagina = $this->uri->segment(4);
				}else{
					$url = $this->uri->segment(4);
				}
			}

			if($this->input->post()){
				$post  = $this->input->post();
				$empty = true;
				$hash  = false;

				foreach ($post as $key => $value) {
					if(!empty($post[$key])){
						$empty = false;
					}
				}

				if(!$empty){
					$hash = array();

					foreach ($post as $key => $value) {
						$hash[$key] = $post[$key];
					}

					$hash = base64_encode(json_encode($hash));
				}	

				redirect($this->data["base_url"] . "administradores/listar/" . $hash);
			}

			if($url){
				$hash = json_decode(base64_decode($url), true);

				$nombre = (isset($hash["nombre"])) ? $hash["nombre"] : false;
				$email  = (isset($hash["email"])) ? $hash["email"] : false;

				$url = '/' . $url;
			}

			$totalResultados = $this->Administradores_model->getTotalAdministradores($nombre, $email);
			$totalPaginas = ceil($totalResultados / $this->_limit);

			$this->_data["encontrado"]   = ($totalResultados > 0) ? array(array()) : array();
			$this->_data["noencontrado"] = ($totalResultados <= 0) ? array(array()) : array();

			$this->_data["nombre"]   = $nombre;
			$this->_data["email"]    = $email;
			
			if(is_numeric($pagina) && $pagina <= $totalPaginas){
				
				$administradores = $this->Administradores_model->getAdministradores($pagina, $nombre, $email);
				$this->_data["encontrado"][0]["administradores"] = $administradores;
				$this->_data["paginador"] = $this->paginador->generarPaginador($pagina, $totalPaginas, 3, 3, $url);
				$this->parser->parse('Admin/administradores/listar_administradores_view', $this->_data);
			}else{
				redirect($this->_data["base_url"] . "admin/administradores");
			}
		}
	}