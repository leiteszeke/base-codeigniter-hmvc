<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once($this->config->item('base_path').'application/controllers/Error_controller.php');

class Generic extends MX_Controller {
	protected $_data;
	protected $_nombreDeTabla;
	protected $_usuarioLogueado;
	protected $_error;

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
		$d   = $this->Admin_model->getOpcionesByFriendlyUrl($url);

		//if(search_array_in_array($d['id_admin_opciones'],$this->_usuarioLogzueado['opciones_con_permiso']) >= 0) {
		if(search_array_in_array($d['id_admin_opciones'],$this->_data['opciones_con_permiso']) >= 0) {
			$metodo = unfriendlizar($url,'-');

			if(method_exists(get_class($this),$metodo)) {
				$this->$metodo();
			}else {
				$this->_error->show_404($metodo);
			}
		}else {
			$this->_error->show_403();
		}
	}

	/**
	 * Prepara los datos para la vista
	 * @param  array $mostrar_columnas es un array de las columnas de la tabla a mostrar
	 * @param  string $param_url        url de la edicion del registro
	 * @param  array $orderBy          lista de campos y direccion para ordenar OPCIONAL
	 * @return array                   encontrado | noencontrado
	 */
	function listar($mostrar_columnas, $param_url, $orderBy = null) {
		$this->_data['noencontrado'] = $this->_data['encontrado'] = array();
		$offset = 0;

		/*PAGINACION*/
		$this->_data['paginado'] = array();
		$primero = $ultimo  = "";
		$pagina  = 1;

		if($this->uri->segment(4)) {
			$pagina = $this->uri->segment(4);
		}

		$offset       = ($pagina - 1) * $this->config->item('items_per_page');

		$totalPaginas = ceil(($this->Secciones_model->get_count_contents($this->_nombreDeTabla)) / $this->_limit);

		if($totalPaginas > 1) {

			if($pagina == 1) {
				$pagAnt = "";
				$pagSig = $pagina + 1;
				$ultimo = $totalPaginas;
			}elseif($pagina == $totalPaginas) {
				$pagAnt = $pagina - 1;
				$pagSig = "";
				$primero= 1;
			}else {
				$pagAnt = $pagina - 1;
				$pagSig = $pagina + 1;

				if($pagina > 2) {
					$primero = 1;
				}
				if($pagina < $totalPaginas - 1) {
					$ultimo = $totalPaginas;
				}
			}

			$this->_data['paginado'][0]['primero'] = ($primero == "") ? array() : array(array('base_url'=>$this->_data['base_url'].'admin/','valor'   =>$primero));
			$this->_data['paginado'][0]['ultimo'] = ($ultimo == "") ? array() : array(array('base_url'=>$this->_data['base_url'].'admin/','valor'   =>$ultimo));
			$this->_data['paginado'][0]['anterior'] = ($pagAnt == "") ? array() : array(array('base_url'=>$this->_data['base_url'].'admin/','valor'   =>$pagAnt));
			$this->_data['paginado'][0]['actual'] = $pagina;
			$this->_data['paginado'][0]['siguiente'] = ($pagSig == "") ? array() : array(array('base_url'=>$this->_data['base_url'].'admin/','valor'   =>$pagSig));

		}
		/*PAGINACION*/

		$show_headers = $this->Secciones_model->show_columns($this->_nombreDeTabla, $mostrar_columnas);
		$show_contents= $this->Secciones_model->show_contents($this->_nombreDeTabla, $offset, false, $orderBy);

		foreach($show_contents as $key=>$news) {
			$id_tabla = 0;
			foreach($news as $id=>$content) {
				$link = $this->_data['base_url'].'admin/'.$this->_data['seccion'].'/'.$param_url.'/'.$show_contents[$key][$id]['id_'.$this->_nombreDeTabla]['valor'];
				foreach($content as $clave=>$valor) {
					if($clave == "id_".$this->_nombreDeTabla) {
						$id_tabla = $valor['valor'];
						//$show_contents[$key][$id][$clave]['valor'] = " < a href = \"{$link}\" > ".$valor['valor']."</a > ";
					}

					if($clave == "editar") {
						$show_contents[$key][$id][$clave]['valor'] = "<a href=\"{$link}\">".$valor['valor']."</a>";
					}elseif($clave == "eliminar") {
						$show_contents[$key][$id][$clave]['valor'] = "<a href=\"#\" title=\"{$id_tabla}\" class=\"eliminar\">".$valor['valor']."</a>";
					}elseif(!empty($mostrar_columnas) and !in_array($clave,$mostrar_columnas)) {
						unset($show_contents[$key][$id][$clave]);
					}
				}
			}
		}



		if(empty($show_contents)) {
			$this->_data['noencontrado'] = array(array());
		}else {
			$this->_data['encontrado'] = array(
				array(
					'show_headers' =>$show_headers,
					'show_contents'=>$show_contents
				)
			);
		}
	}

	/**
	 * Agregar generico para mostrar formulario o procesar data
	 * @return string '_POST_' | array $this->_data['encontrado'] $this->_data['noencontrado']
	 */
	function agregar($param_url) {
		if($this->input->post()) {
			if($this->session->userdata('hash_form_data') !== null and
				$this->session->userdata('hash_form_data') === $this->input->post('hash_form_data')) {
				return '_POST_';
			}else {
				show_404();
			}
		}else {
			$this->session->unset_userdata('hash_form_data');

			$this->_data['ext_images_permitidas'] = implode('\',\'',$this->config->item('ext_images_permitidas'));
			$this->_data['ext_images_permitidas'] = '\''.$this->_data['ext_images_permitidas'].'\'';

			$this->_data['noencontrado'] = array();
			// inicializo el formulario
			$this->_data['encontrado'] = array($this->Secciones_model->show_all_columns($this->_nombreDeTabla));

			$this->_data['encontrado'][0]['base_url'] = $this->_data['base_url'];
			$this->_data['encontrado'][0]['seccion'] = $this->_data['seccion'];
			$this->_data['encontrado'][0]['accion'] = $param_url;

			$this->_data['encontrado'][0]['hash_form_data'] = md5(uniqid(rand(), true));
			if($this->session->userdata('hash_form_data') == null) {
				$this->session->set_userdata('hash_form_data',$this->_data['encontrado'][0]['hash_form_data']);
			}
		}
	}

	/**
	 * Editar generico para mostrar formulario o procesar data
	 * @param string $model [Nombre del modelo a utilizar]
	 * @param string $accion [A donde redirecciona el formulario]
	 * @param string $param [Parametro recibido por get en caso de estar permitido]
	 * @return string '_POST_' | array $this->_data['encontrado'] $this->_data['noencontrado']
	 */
	public function editar($model, $accion, $param = null) {
		if($this->input->post()) {
			if($this->session->userdata('hash_form_data') !== null and
				$this->session->userdata('hash_form_data') === $this->input->post('hash_form_data')) {
				return '_POST_';
			}else {
				show_404();
			}
		}else {
			$this->session->unset_userdata('hash_form_data');

			$this->_data['ext_images_permitidas'] = implode('\',\'',$this->config->item('ext_images_permitidas'));
			$this->_data['ext_images_permitidas'] = '\''.$this->_data['ext_images_permitidas'].'\'';

			$this->_data['noencontrado'] = array();
			$this->_data['encontrado'] = array(array());

			if($param === null) {
				$this->_data['noencontrado'] = array(array());
				$this->_data['encontrado']	 = array();

				$this->_data['noencontrado'][0]['accion'] = $accion;
			}else {
				$res = $this->$model->buscarByFriendly($param);

				if(!empty($res)) {
					foreach($res as $i=>$new) {
						if(!is_array($new)) {
							$res[$i] = htmlspecialchars($new);
						}
					}

					$res['base_url'] = $this->_data['base_url'];
					$res['seccion'] = $this->_data['seccion'];
					$res['accion'] = $accion;

					$this->_data['encontrado'][0] = $res;
					$this->_data['encontrado'][0]['hash_form_data'] = md5(uniqid(rand(), true));
					if($this->session->userdata('hash_form_data') == null) {
						$this->session->set_userdata('hash_form_data',$this->_data['encontrado'][0]['hash_form_data']);
					}

				}else {
					$this->_data['noencontrado'] = array(array());
					$this->_data['encontrado'] = array();
				}
			}
		}
	}

	/**
	 * [eliminar description]
	 * @return [type] [description]
	 */
	public function eliminar($model){
		if($this->input->post()) {
			$accion = $this->input->post('accion');
			$valor  = $this->input->post('valor');

			if($accion != "" && $valor != "") {
				if($accion == "eliminar") {
					return $this->$model->eliminarItem($valor);
				}elseif($accion == "deshabilitar") {
					return $this->$model->disabled_member($valor);
				}elseif($accion == "habilitar") {
					return $this->$model->enabled_member($valor);
				}
			}
		}
	}
}
?>