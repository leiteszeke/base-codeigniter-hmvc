<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require_once($this->config->item('base_path').'application/controllers/Error_controller.php');

	class Opciones extends MX_Controller {
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
			$this->_nombreDeTabla = "admin_opciones";

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
			$url  = $this->uri->segment(3);

			$d = $this->Admin_model->getOpcionesByFriendlyUrl($url);

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
			$orden = array();
			$this->_data["orden"]  = $orden;
			$this->_data["iconos"] = $this->_iconos;
			$this->_data["secciones"] = $this->Secciones_model->getTodasSecciones();
			$this->parser->parse('Admin/opciones/agregar_opciones_view', $this->_data);
		}

		public function editar() {
			$id_opcion = $this->uri->segment(4);
			$opcion  = $this->Opciones_model->getOpcion($id_opcion);
			$this->_data["opcion"] = array();
			
			$secciones = $this->Secciones_model->getTodasSecciones();
			
			if(!empty($opcion)){
				$total = $this->Opciones_model->getTotalOpciones($opcion['id_admin_secciones']);
				$orden = array();
				
				for($i = 1; $i <= $total; $i++){
					$selected = ($i == $opcion["orden"]) ? 'selected = \"selected\"' : '';
					$orden[] = array("id_orden" => $i, "n_orden" => $i, "selected" => $selected);
				}

				foreach ($secciones as $seccionk => $seccionv) {
					if($opcion['id_admin_secciones'] == $seccionv['id_admin_secciones']){
						$secciones[$seccionk]['selected'] = 'selected';
					}else{
						$secciones[$seccionk]['selected'] = '';
					}
					$secciones[$seccionk]['nombre_seccion'] = $seccionv['nombre'];
				}
				
				foreach($this->_iconos as $key => $value){
					$this->_iconos[$key]["icono-seleccionado"] = ($value["icono"] == $opcion["icono"]) ? 'icono-seleccionado' : '';
				}

				$opcion["menu_si"] = ($opcion["mostrar_menu"] == 1) ? 'checked' : '';
				$opcion["menu_no"] = ($opcion["mostrar_menu"] == 0) ? 'checked' : '';

				$this->_data["opcion"][0]             	= $opcion;
				$this->_data["opcion"][0]['secciones']	= $secciones;
				$this->_data["opcion"][0]["iconos"] 	= $this->_iconos;
				$this->_data["opcion"][0]["orden"]  	= $orden;

				$this->parser->parse('Admin/opciones/editar_opciones_view', $this->_data);	
			}else{
				redirect($this->_data["base_url"] . "admin/opciones");
			}
		}

		/**
		* Lista los registros que hayan de esta seccion
		* */
		public function listar() {
			$this->_data["opciones"]  = array();
			$this->_data["paginador"] = array();
			$pagina = 1;
			$url    = false;
			$seccion = null;

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

				redirect($this->_data["base_url"] . "admin/opciones/listar/" . $hash);
			}

			if($url){
				$hash = json_decode(base64_decode($url), true);
				$seccion = $hash['sch_seccion'];
				$url = '/' . $url;
			}

			$totalResultados = $this->Opciones_model->getTotalOpciones($seccion);
			$totalPaginas = ceil($totalResultados / $this->_limit);

			$this->_data["encontrado"]   = ($totalResultados > 0) ? array(array()) : array();
			$this->_data["noencontrado"] = ($totalResultados <= 0) ? array(array()) : array();
			
			$this->_data['accion'] 		= 'listar';
			$this->_data['secciones'] 	= $this->Secciones_model->getTodasSecciones();
			foreach ($this->_data['secciones'] as $key => $value) {
				$this->_data['secciones'][$key]['sch_seccion'] = '';
				if(!is_null($seccion) && $seccion == $value['id_admin_secciones']){
					$this->_data['secciones'][$key]['sch_seccion'] = 'selected';
				}
			}

			if(is_numeric($pagina) && $pagina <= $totalPaginas){
				$opciones = $this->Opciones_model->getOpciones($pagina, $seccion);
				$this->_data["encontrado"][0]["opciones"] = $opciones;
				$this->_data["paginador"] = $this->paginador->generarPaginador($pagina, $totalPaginas, 3, 3, $url);
				$this->parser->parse('Admin/opciones/listar_opciones_view', $this->_data);
			}else{
				redirect($this->_data["base_url"] . "admin/opciones");
			}
		}
	}
