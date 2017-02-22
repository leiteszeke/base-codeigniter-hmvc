<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opciones_model extends CI_Model {

	private $_id_admin_opciones;
	private $_nombre;
	private $_friendly_url;
	private $_icono;
	private $_orden;
	private $_mostrar_menu;
	private $_admin_secciones;
	private $_nombreDeTabla;
	private $_prefix;

	public function __construct() {
		parent::__construct();

		$this->_nombreDeTabla = "admin_opciones";
		$this->_prefix = $this->config->item("db_prefix");
	}

	/**
	* Inicializa una opcion
	*
	* @return
	*/
	public function opciones_model() {
		$this->_id_admin_opciones = null;
		$this->_nombre = "";
		$this->_friendly_url = "";
		$this->_icono = "";
		$this->_orden = "";
		$this->_mostrar_menu = "";
		$this->_admin_secciones = $this->Secciones_model->listar();

		return array(
			'id_admin_opciones'=> $this->_id_admin_opciones,
			'nombre'           => $this->_nombre,
			'friendly_url'     => $this->_friendly_url,
			'icono'            => $this->_icono,
			'orden'            => $this->_orden,
			'mostrar_menu'     => $this->_mostrar_menu,
			'admin_secciones'  => $this->_admin_secciones
		);
	}

	/**
	*
	* @param int $idOpcion
	*
	* @return string
	*/
	public function getIcono($idOpcion) {
		$this->db->select('icono')->from($this->_nombreDeTabla)->where('id_admin_opciones',$idOpcion);
		$get = $this->db->get();

		if($get->num_rows() > 0) {
			$fila = $get->row_array();
			return $fila['icono'];
		}else {
			return "";
		}
	}

	/**
	* Obtiene un array con las columnas de la tabla y el tipo de datos que és
	* @param $table
	* @return array
	* */
	public function getTypeField($table = "") {
		$type_fields = array();
		if($table === "") {
			return $type_fields;
		}

		$fields = $this->db->field_data($table);

		foreach($fields as $i=>$field) {
			$type_fields[$i]['name'] = $field->name;
			$type_fields[$i]['type'] = $field->type;
			$type_fields[$i]['max_length'] = $field->max_length;
		}
		return $type_fields;
	}

	/**
	* Retorna las columnas de una tabla como clave de un array
	* @param table
	* @return array
	* */
	public function show_all_columns($table = "") {
		if($table === "") {
			return array();
		}

		$resultado = $this->db->list_fields($table);
		foreach($resultado as $field) {
			$data[$field] = "";
			if($field == "publicado") {
				$data[$field][0] = array('checked'=>'checked');
			}

			if($field != "id_".$table && strpos($field, "id_") !== false) {
				//FK
				$tabla   = explode("id_",$field);
				$fk      = $this->db->get($tabla[1]);
				$datosfk = $fk->result_array();

				foreach($datosfk as $i=>$dato) {
					$datosfk[$i]['marcado'] = '';
				}
				$data[$tabla[1]] = $datosfk;
			}

		}
		return $data;
	}

	/**
	* Devuelve los datos de la tabla y columnas especificas
	* @param $table strin
	* @param $mostrar_columnas array
	* @return array
	* */
	public function show_columns($table = "", $mostrar_columnas = array()) {
		if($table === "") {
			return array();
		}

		$resultado = $this->db->list_fields($table);

		foreach($resultado as $field) {
			$hidden_phone = "";
			if($field == "id_".$table) {
				//PK
				$field = "#";
				continue;
			}elseif(strpos($field, "id_") !== false) {
				//FK
				if(!empty($mostrar_columnas) and !in_array($field,$mostrar_columnas)) {
					continue;
				}else {
					$table_fk = explode('id_admin_', $field);
					if(isset($table_fk[1])) {
						$field = $table_fk[1];
					}else {
						$table_fk = explode('id_', $field);
						$field    = $table_fk[1];
					}

					$hidden_phone = "hidden-phone";
				}
			}elseif(in_array($field,camposAuditoria())) {
				continue;
			}elseif(!empty($mostrar_columnas) and !in_array($field,$mostrar_columnas)) {
				continue;
			}
			$field = unfriendlizar($field,'_',true);
			$data[] = array('titulo_columns'=>$field,'hidden-phone'  =>$hidden_phone);
		}

		$data[] = array('titulo_columns'=>"Editar",'hidden-phone'  =>"");
		$data[] = array('titulo_columns'=>"Eliminar",'hidden-phone'  =>"");

		return $data;
	}

	/**
	* Indica cantidad de registro totales de la tabla
	* @param table
	* @return int
	* */
	public function get_count_contents($table = "") {
		if($table === "") {
			return - 1;
		}

		return $this->db->count_all($table);
	}


	/**
	* Devuelve el contenido de la tabla paginado
	* @param table
	* @param offset Indica a partir de que registro devuelve
	* @return array
	* */
	public function show_contents($table = "", $offset = 0) {
		$data = array();
		if($table === "") {
			return $data;
		}

		$this->db->limit($this->config->item('items_per_page'), $offset);
		$this->db->where('publicado',1);
		$resultado   = $this->db->get($table);
		$rows        = $resultado->result_array();

		$type_fields = $this->getTypeField($table);

		foreach($rows as $indice=>$content) {
			foreach($content as $key=>$value) {
				$ind = search_array_in_array($key, $type_fields);
				if($ind >= 0) {
					if($type_fields[$ind]['type'] == "tinyint") {
						$value = $value ? "SI" : "NO";
					}
				}

				if($key == "id_".$table) {
					//PK
					$data[$indice][$table][$key]['valor'] = $value;
				}elseif(strpos($key, "id_") !== false) {
					//FK
					$table_fk = explode('id_', $key);
					$datosFK  = $this->db->get_where($table_fk[1], array($key=>$value));
					$resultFK = $datosFK->row_array();
					$data[$indice][$table][$key]['valor'] = $resultFK['nombre'];
					$data[$indice][$table][$key]['hidden-phone'] = "hidden-phone";
				}elseif(in_array($key,camposAuditoria())) {
					continue;
				}else {
					if(strlen($value) > 120) {
						$value = substr($value, 0, 120) . '...';
					}
					$data[$indice][$table][$key]['valor'] = $value;
				}
			}

			$data[$indice][$table]['editar']['valor'] = "<i class=\"fa fa-edit\"></i>";
			$data[$indice][$table]['eliminar']['valor'] = "<i class=\"fa fa-trash-o\"></i>";

		}
		return $data;
	}

	/**
	* Devuelve todas las admin_opciones existentes
	* @return array
	* */
	public function listar() {
		$res = $this->db->get($this->_nombreDeTabla);
		return $res->result_array();
	}

	/**
	* Agregar datos a la tabla admin_opciones
	* @param $data Array
	* @return bool
	* */
	public function agregar($data = null) {
		if(is_null($data) || !is_array($data)) {
			return null;
		}

		$this->desplazarOrden($data['id_admin_secciones'], $data['orden']);

		$data['created'] = date('Y-m-d H:i:s');

		return $this->db->insert($this->_nombreDeTabla, $data);
	}

	/**
	* Agregar datos a la tabla admin_opciones
	* @param $data Array
	* @return bool
	* */
	public function editar($data = null, $id = 0) {
		if(is_null($data) || !is_array($data)) {
			return null;
		}

		$this->reemplazarOrden($data['id_admin_secciones'], $id, $data['orden']);

		$this->db->where('id_admin_opciones', $id);
		return $this->db->update($this->_nombreDeTabla, $data);
	}

	public function borrarOpcion($id_opcion){
		$sql = "DELETE FROM ".$this->_prefix."admin_opciones WHERE id_admin_opciones = ".$this->db->escape($id_opcion).";";
		$qry = $this->db->query($sql);

		if($qry){
			$resp = array("error" => false, "message" => "Se ha borrado la opción.", "data" => array());
		}else{
			$resp = array("error" => true, "message" => "No se ha podido borrar la opción.", "data" => array());
		}

		return $resp;
	}

	/**
	* Buscar una opcion por id
	* @param $id
	* @return array
	* */
	public function buscarById($id = 0) {
		if($id === 0) {
			return array();
		}

		$sec = $this->db->get_where($this->_nombreDeTabla,array('id_admin_opciones'=>$id));
		$opcion = $sec->row_array();

		return $opcion;
	}

	/**
	*
	* @param int $id
	*
	* @return string
	*/
	public function getFriendlyById($id = "") {
		if($id === "") {
			return $id;
		}

		$get   = $this->db->select('friendly_url')->from($this->_nombreDeTabla)->where('id_admin_opciones',$id)->get();
		$valor = $get->row_array();
		return $valor['friendly_url'];
	}

	/**
	* Buscar una opcion por friendly_url
	* @param $friendly
	* @return array
	* */
	public function buscarByFriendly($friendly = "") {
		if($friendly === "") {
			return array();
		}

		$sec = $this->db->get_where($this->_nombreDeTabla,array('friendly_url'=>$friendly));
		$opcion = $sec->row_array();

		return $opcion;
	}

	public function delete_member($valor) {
		if(isset($valor) && $valor != "") {
			$dl = $this->db->delete($this->_nombreDeTabla,array('id_admin_opciones'=>$valor));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}

	public function disabled_member($valor) {
		if(isset($valor) && $valor != "") {
			$this->db->where('id_admin_opciones',$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('publicado'=>0));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}

	public function enabled_member($valor) {
		if(isset($valor) && $valor != "") {
			$this->db->where('id_admin_opciones',$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('publicado'=>1));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}

	/**
	* Retorna la cantidad de veces que el friendly está usado
	*
	* @param string $friendly
	*
	* @return int
	*/
	public function friendlyDisponible($friendly) {
		if($friendly === "") {
			return array();
		}

		$get = $this->db->select('id_admin_opciones')->from($this->_nombreDeTabla)->like('friendly_url',$friendly)->get();

		return $get->num_rows();
	}

	/**
	* Asigna permisos a un usuario a una seccion
	*
	* @param array $array
	* @return bool
	*/
	public function asignarPermisos($array) {
		if(is_array($array['opcion'])) {
			foreach($array['opcion'] as $key => $value) {
		$insert = array(
			'id_admin_user'    => $array['usuario'],
					'id_admin_opciones'=> $value,
		);

		if($array['id_admin_permisos'] != "") {
			$this->db->where('id_admin_user_opcion_permisos',$array['id_admin_permisos']);
			return $this->db->update('admin_user_opcion_permisos', $insert);
		}

				$ins = $this->db->insert('admin_user_opcion_permisos', $insert);
			}
			return;
		}else {
			return array();
		}
	}

	/**
	* Desasigna permisos a un usuario a una seccion
	*
	* @param int $valor
	* @return bool
	*/
	public function desasignarPermisos($valor) {
		$this->db->where('id_admin_user_opcion_permisos',$valor);
		return $this->db->delete('admin_user_opcion_permisos');
	}

	/**
	*
	* @param int  $id
	*
	* @return
	*/
	public function getPermisosBy($id) {
		$this->db->where('id_admin_user_opcion_permisos', $id);
		$q = $this->db->get('admin_user_opcion_permisos');
		return $q->row_array();
	}

	/**
	*
	* @return array
	*/
	public function getPermisos() {
		$permisosget = $this->db->get('admin_user_opcion_permisos');
		$permisos    = $permisosget->result_array();
		return $permisos;
	}

	/**
	* Devuelve todas las admin_opciones existentes sin permisos
	* @return array
	* */
	public function listarNoAsignadasAPermisos() {
		$res       = $this->db->get($this->_nombreDeTabla);
		$todas     = $res->result_array();

		$res       = $this->db->get('admin_user_opcion_permisos');
		$asignadas = $res->result_array();

		foreach($todas as $i=>$cadauna) {
			foreach($asignadas as $key => $value) {
				if($cadauna['id_admin_opciones'] == $value['id_admin_opciones']) {
					unset($todas[$i]);
				}
			}
		}

		return $todas;
	}

	/**
	* Devuelve todas las admin_opciones existentes sin permisos
	* @param $usuario
	* @return array
	* */
	public function listarNoAsignadasAPermisosByUsuario($usuario) {
		$res       = $this->db->get($this->_nombreDeTabla);
		$todas     = $res->result_array();

		$this->db->where('id_admin_user',$usuario);
		$res       = $this->db->get('admin_user_opcion_permisos');
		$asignadas = $res->result_array();

		foreach($todas as $i=>$cadauna) {
			foreach($asignadas as $key => $value) {
				if($cadauna['id_admin_opciones'] == $value['id_admin_opciones']) {
					unset($todas[$i]);
				}
			}
		}

		return $todas;
	}

	/**
	* 
	* @param undefined $seccion
	* @param undefined $ordenDesde
	* 
	* @return
	*/
	public function desplazarOrden($seccion, $ordenDesde) {
		$existente = $this->db->where('id_admin_secciones',$seccion)->where('orden',$ordenDesde)->from($this->_nombreDeTabla)->get();
		$existe    = $existente->row_array();

		if(!empty($existe)) {
			$get = $this->db->where('orden>=', $ordenDesde)->from($this->_nombreDeTabla)->order_by('orden','desc')->get();
			$res = $get->result_array();

			foreach($res as $id => $pagina) {
				$this->db->where('id_admin_opciones',$pagina['id_admin_opciones']);
				$this->db->set('orden', 'orden+1', FALSE);
				$this->db->set('updated',date('Y-m-d H:i:s'));
				$db = $this->db->update($this->_nombreDeTabla);
			}
		}
	}

	/**
	* 
	* @param undefined $seccion
	* @param undefined $id
	* @param undefined $ordenNuevo
	* 
	* @return
	*/
	public function reemplazarOrden($seccion, $id, $ordenNuevo) {
		$existente = $this->db->where('id_admin_secciones',$seccion)->where('orden',$ordenNuevo)->from($this->_nombreDeTabla)->get();
		$existe    = $existente->row_array();

		if(!empty($existe)) {
			$yaexiste       = $existe['id_admin_opciones'];
			/*---*/
			$get            = $this->db->select('orden')->from($this->_nombreDeTabla)->where('id_admin_opciones',$id)->get();
			$res            = $get->row_array();
			$ordenQueExiste = $res['orden'];
			/*---*/
			$update         = array('orden'  =>$ordenQueExiste,'updated'=> date('Y-m-d H:i:s'));
			$this->db->where('id_admin_opciones',$yaexiste);
			$db = $this->db->update($this->_nombreDeTabla, $update);
		}
	}

	/*
	|----------------------------------------------------------------------------------------------
	| OPCIONES v2
	|----------------------------------------------------------------------------------------------
	*/

	public function agregarOpcion($nombre, $seccion, $orden, $url, $menu, $icono){
		
		$this->desplazarOrden($seccion, $orden);

		$sql_fields = "";
		$sql_in     = "";

		$sql_fields .= "nombre, id_admin_secciones, orden, friendly_url, icono, mostrar_menu, created";

		$sql_in .= "  " . $this->db->escape($nombre) . "";
		$sql_in .= ", " . $this->db->escape($seccion) . "";
		$sql_in .= ", " . $this->db->escape($orden) . "";
		$sql_in .= ", " . $this->db->escape($url) . "";
		$sql_in .= ", " . $this->db->escape($icono) . "";
		$sql_in .= ", " . $this->db->escape($menu) . "";
		$sql_in .= ", " . $this->db->escape(date("Y-m-d H:i:s")) . "";

		$sql = "INSERT INTO " . $this->_prefix . "admin_opciones(" . $sql_fields . ") VALUES(" . $sql_in . ");";
		$qry = $this->db->query($sql);

		if($this->db->insert_id() > 0){
			$resp = array("error" => false, "message" => "La opción ha sido creada.", "data" => array());
		}else{
			$error = array("error_num" => $this->db->_error_number(), "error_msg" => $this->db->_error_message());
			$resp  = array("error" => true, "message" => "No se ha podido crear la sección", "data" => $error);
		}

		return $resp;
	}

	public function editarOpcion($id, $nombre, $url, $seccion, $menu, $icono, $orden){

		$this->reemplazarOrden($seccion, $id, $orden);

		$menu  = ($menu == true) ? 1 : 0;
		$orden = ($orden <= 0) ? $this->getTotalOpciones($seccion) + 1 : $orden;

		$sql = "UPDATE ".$this->_prefix."admin_opciones SET
			id_admin_secciones = ".$seccion.",
			nombre = ".$this->db->escape($nombre).",
			friendly_url = ".$this->db->escape($url).",
			mostrar_menu = ".$this->db->escape($menu).",
			icono = ".$this->db->escape($icono).",
			orden = ".$this->db->escape($orden)."
			WHERE id_admin_opciones = ".$this->db->escape($id).";";
		$qry = $this->db->query($sql);

		if($this->db->affected_rows() > 0){
			$resp = array("error" => false, "message" => "Se ha editado la opción", "data" => array());
		}else{
			$resp = array("error" => true, "message" => "No se pudo editar la opción", "data" => array('q'=>'No hay modificaciones para hacer.'));
		}

		return $resp;
	}

	public function getOpcion($id){
		$sql = "SELECT * FROM " . $this->_prefix . "admin_opciones WHERE id_admin_opciones = " . $this->db->escape($id) . ";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$opcion = $qry->row_array();
			$resp = $opcion;
		}else{
			$resp = array();
		}

		return $resp;
	}

	public function getOpciones($pagina, $seccion = null){
		$sql_add = "";

		if(!is_null($seccion)){
			$sql_add .= " AND id_admin_secciones = ". $seccion;
		}

		$offset = ($pagina - 1) * $this->config->item('limit_opciones');

		$sql_add .= " LIMIT ". $this->config->item('limit_opciones') ." OFFSET ". $offset;

		$sql = "SELECT * FROM ".$this->_prefix."admin_opciones WHERE 1 ".$sql_add.";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$opciones = $qry->result_array();

			foreach($opciones as $key => $value){
				$opciones[$key]["base_url"] = $this->config->item("base_url");
				$seccion = $this->Secciones_model->getSeccion($opciones[$key]["id_admin_secciones"]);
				$opciones[$key]["seccion"] = (isset($seccion["nombre"])) ? $seccion["nombre"] : "";
			}

			$resp = $opciones;
		}else{
			$resp = array();
		}

		return $resp;
	}

	public function getTotalOpciones($seccion = null){
		$sql_add = "";
		if(!is_null($seccion)){
			$sql_add .= " AND id_admin_secciones = ". $seccion;
		}

		$sql = "SELECT COUNT(id_admin_opciones) AS tot FROM ".$this->_prefix."admin_opciones WHERE 1 ".$sql_add.";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			return $qry->row_array()["tot"];
		}else{
			return 0;
		}
	}

	public function getOpcionesBySeccion($seccion){
		$sql = "SELECT * FROM " . $this->_prefix . "admin_opciones WHERE id_admin_secciones = " . $this->db->escape($seccion) . ";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$opciones = $qry->result_array();
			$resp = array("error" => false, "message" => "Se han encontrado opciones para esta sección.", "data" => $opciones);
		}else{
			$resp = array("error" => true, "message" => "No se han encontrado opciones.", "data" => array());
		}

		return $resp;
	}
}