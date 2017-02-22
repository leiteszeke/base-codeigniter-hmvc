<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once($this->config->item('base_path').'application/controllers/Error_controller.php');

	class Secciones extends MX_Controller {
		private $_data;
		private $_nombreDeTabla;
		private $_usuarioLogueado;
		private $_error;
		private $_limit;
		private $_iconos;

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

			$this->_data['menu'] = $this->parser->parse("Admin/common/menu_inc", $this->_data, true);
			$this->_nombreDeTabla = "admin_secciones";

			$this->_data["encontrado"] = array();
			$this->_data["noencontrado"] = array();
			$this->_limit = $this->config->item("limit_secciones");

			$this->_iconos = array();

			$dir = scandir($this->config->item("base_path") . "data/upload/imagenes_secciones");
			foreach($dir as $key => $value){
				if($dir[$key] != ".." && $dir[$key] != "."){
					$this->_iconos[] = array("base_url" => $this->config->item("base_url"), "icono" => $dir[$key]);
				}
			}
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
					$this->_error->show_404($metodo);
				}
			}else {
				$this->_error->show_403();
			}
		}

		public function agregar() {
			$total = $this->Secciones_model->getTotalSecciones() + 1;
			$orden = array();
			for($i = 1; $i <= $total; $i++){
				$orden[] = array("id_orden" => $i, "n_orden" => $i);
			}
			$this->_data["iconos"] = $this->_iconos;
			$this->_data["orden"]  = $orden;
			$this->parser->parse('Admin/secciones/agregar_secciones_view', $this->_data);
		}

		public function editar() {
			$id_seccion = $this->uri->segment(4);
			$seccion  = $this->Secciones_model->getSeccion($id_seccion);
			$this->_data["seccion"] = array();

			if(!empty($seccion)){
				$total = $this->Secciones_model->getTotalSecciones();
				$orden = array();
				
				for($i = 1; $i <= $total; $i++){
					$selected = ($i == $seccion["orden"]) ? 'selected = \"selected\"' : '';
					$orden[] = array("id_orden" => $i, "n_orden" => $i, "selected" => $selected);
				}

				foreach($this->_iconos as $key => $value){
					$this->_iconos[$key]["icono-seleccionado"] = ($this->_iconos[$key]["icono"] == $seccion["icono"]) ? 'icono-seleccionado' : '';
				}

				$seccion["menu_si"] = ($seccion["mostrar_menu"] == 1) ? 'checked' : '';
				$seccion["menu_no"] = ($seccion["mostrar_menu"] == 0) ? 'checked' : '';
				$seccion["home_si"] = ($seccion["es_home"] == 1) ? 'checked' : '';
				$seccion["home_no"] = ($seccion["es_home"] == 0) ? 'checked' : '';

				$this->_data["seccion"][0]           = $seccion;
				$this->_data["seccion"][0]["iconos"] = $this->_iconos;
				$this->_data["seccion"][0]["orden"]  = $orden;

				$this->parser->parse('Admin/secciones/editar_secciones_view', $this->_data);	
			}else{
				redirect($this->_data["base_url"] . "admin/secciones");
			}
		}

		/**
		* Lista los registros que hayan de esta seccion
		* */
		public function listar() {
			$this->_data["secciones"]  = array();
			$this->_data["paginador"] = array();
			$pagina = 1;
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

				redirect($this->data["base_url"] . "secciones/listar/" . $hash);
			}

			if($url){
				$hash = json_decode(base64_decode($url), true);

				$url = '/' . $url;
			}

			$totalResultados = $this->Secciones_model->getTotalSecciones();
			$totalPaginas = ceil($totalResultados / $this->_limit);

			$this->_data["encontrado"]   = ($totalResultados > 0) ? array(array()) : array();
			$this->_data["noencontrado"] = ($totalResultados <= 0) ? array(array()) : array();
			
			if(is_numeric($pagina) && $pagina <= $totalPaginas){
				$secciones = $this->Secciones_model->getSecciones($pagina);
				$this->_data["encontrado"][0]["secciones"] = $secciones;
				$this->_data["paginador"] = $this->paginador->generarPaginador($pagina, $totalPaginas, 3, 3, $url);
				$this->parser->parse('Admin/secciones/listar_secciones_view', $this->_data);
			}else{
				redirect($this->_data["base_url"] . "admin/secciones");
			}
		}
	}
