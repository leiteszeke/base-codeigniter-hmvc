<?php
	defined('BASEPATH') OR exit('No direct script access allowed');	

	class Permisos extends MX_Controller {
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
			require_once($this->config->item('base_path').'application/controllers/Error_controller.php');

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

			$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);
			$this->_nombreDeTabla = "admin_user";

			$this->_data["encontrado"] = array();
			$this->_data["noencontrado"] = array();
			$this->_limit = $this->config->item("limit_permisos");
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
			// Para una opción compuesta, se agrega la siguiente validación
			$url2 = $this->uri->segment(4);
			if(!is_numeric($url2)){
				$url .= "/".$url2;
			}

			$d = $this->Admin_model->getOpcionesByFriendlyUrl($url);

			if(search_array_in_array($d['id_admin_opciones'],$this->_data['opciones_con_permiso']) >= 0) {
				$metodo = unfriendlizar($url,'-');
				$metodo = unfriendlizar($url,'/');

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

		public function seccionesAgregar() {
			$administradores = $this->Administradores_model->getTodosAdministradores();
			$secciones       = $this->Secciones_model->getTodasSecciones();
			$this->_data["usuarios"]  = $administradores;
			$this->_data["secciones"] = $secciones;
			$this->parser->parse('Admin/permisos/agregar_permisos_secciones_view', $this->_data);
		}

		public function opcionesAgregar() {
			$administradores = $this->Administradores_model->getTodosAdministradores();
			$secciones       = $this->Secciones_model->getTodasSecciones();
			$this->_data["usuarios"]  = $administradores;
			$this->_data["secciones"] = $secciones;
			$this->parser->parse('Admin/permisos/agregar_permisos_opciones_view', $this->_data);
		}

		/**
		* Lista los registros que hayan de esta seccion
		* */
		public function seccionesListar() {
			$this->_data["secciones"] = array();
			$this->_data["paginador"] = array();
			$pagina = 1;
			$nombre = false;
			$email  = false;
			$url    = false;

			if($this->uri->segment(6) && is_numeric($this->uri->segment(6))){
				$url    = $this->uri->segment(5);
				$pagina = $this->uri->segment(6);
			}else{
				if(is_numeric($this->uri->segment(5))){
					$pagina = $this->uri->segment(5);
				}else{
					$url = $this->uri->segment(5);
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

				redirect($this->data["base_url"] . "permisos/secciones/listar/" . $hash);
			}

			if($url){
				$hash = json_decode(base64_decode($url), true);

				$nombre = (isset($hash["nombre"])) ? $hash["nombre"] : false;
				$email  = (isset($hash["email"])) ? $hash["email"] : false;

				$url = '/' . $url;
			}

			$totalResultados = $this->Permisos_model->getTotalPermisosSecciones();
			$totalPaginas = ceil($totalResultados / $this->_limit);

			$this->_data["encontrado"]   = ($totalResultados > 0) ? array(array()) : array();
			$this->_data["noencontrado"] = ($totalResultados <= 0) ? array(array()) : array();

			$this->_data["nombre"]   = $nombre;
			$this->_data["email"]    = $email;
			
			if(is_numeric($pagina) && $pagina <= $totalPaginas){
				$secciones = $this->Permisos_model->getPermisosSecciones($pagina);

				$this->_data["encontrado"][0]["secciones"] = $secciones;
				$this->_data["paginador"] = $this->paginador->generarPaginador($pagina, $totalPaginas, 3, 3, $url);
				$this->parser->parse('Admin/permisos/listar_permisos_secciones_view', $this->_data);
			}else{
				redirect($this->_data["base_url"] . "admin/permisos");
			}
		}

		/**
		* Lista los registros que hayan de esta seccion
		* */
		public function opcionesListar() {
			$this->_data["opciones"]  = array();
			$this->_data["paginador"] = array();
			$pagina = 1;
			$nombre = false;
			$email  = false;
			$url    = false;

			if($this->uri->segment(6) && is_numeric($this->uri->segment(6))){
				$url    = $this->uri->segment(5);
				$pagina = $this->uri->segment(6);
			}else{
				if(is_numeric($this->uri->segment(5))){
					$pagina = $this->uri->segment(5);
				}else{
					$url = $this->uri->segment(5);
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

				redirect($this->data["base_url"] . "permisos/opciones/listar/" . $hash);
			}

			if($url){
				$hash = json_decode(base64_decode($url), true);

				$nombre = (isset($hash["nombre"])) ? $hash["nombre"] : false;
				$email  = (isset($hash["email"])) ? $hash["email"] : false;

				$url = '/' . $url;
			}

			$totalResultados = $this->Permisos_model->getTotalPermisosOpciones();
			$totalPaginas = ceil($totalResultados / $this->_limit);

			$this->_data["encontrado"]   = ($totalResultados > 0) ? array(array()) : array();
			$this->_data["noencontrado"] = ($totalResultados <= 0) ? array(array()) : array();

			$this->_data["nombre"]   = $nombre;
			$this->_data["email"]    = $email;
			
			if(is_numeric($pagina) && $pagina <= $totalPaginas){
				$opciones = $this->Permisos_model->getPermisosOpciones($pagina);

				$this->_data["encontrado"][0]["opciones"] = $opciones;
				$this->_data["paginador"] = $this->paginador->generarPaginador($pagina, $totalPaginas, 3, 3, $url);
				$this->parser->parse('Admin/permisos/listar_permisos_opciones_view', $this->_data);
			}else{
				redirect($this->_data["base_url"] . "admin/permisos");
			}
		}
	}