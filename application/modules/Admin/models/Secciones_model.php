<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secciones_model extends CI_Model {

	private $_id_admin_secciones;
	private $_nombre;
	private $_friendly_url;
	private $_icono;
	private $_orden;
	private $_mostrar_menu;
	private $_nombreDeTabla;
	private $_prefix;

	public function __construct() {
		parent::__construct();

		$this->_nombreDeTabla = "admin_secciones";
		$this->_prefix = $this->config->item("db_prefix");
	}

	/**
	* Inicializa una seccion
	*
	* @return
	*/
	public function secciones_model() {
		$this->_id_admin_secciones = null;
		$this->_nombre = "";
		$this->_friendly_url = "";
		$this->_icono = "";
		$this->_orden = "";
		$this->_mostrar_menu = "";

		return array(
			'id_admin_secciones'=> $this->_id_admin_secciones,
			'nombre'            => $this->_nombre,
			'friendly_url'      => $this->_friendly_url,
			'icono'             => $this->_icono,
			'orden'             => $this->_orden,
			'mostrar_menu'      => $this->_mostrar_menu
		);
	}

	/**
	*
	* @param int $idSeccion
	*
	* @return string
	*/
	public function getIcono($idSeccion) {
		$this->db->select('icono')->from($this->_nombreDeTabla)->where('id_admin_secciones',$idSeccion);
		$get = $this->db->get();

		if($get->num_rows() > 0) {
			$fila = $get->row_array();
			return $fila['icono'];
		}else {
			return "";
		}
	}

	/**
	* Obtiene un array con las columnas de la tabla y el tipo de datos que �s
	* @param $table
	* @return array
	* */
	public function getTypeField($table = "") {
		$type_fields = array();
		if($table === "") {
			return $type_fields;
		}

		$fields = $this->db->field_data($table);

		if($fields) {
			foreach($fields as $i=>$field) {
				$type_fields[$i]['name'] = $field->name;
				$type_fields[$i]['type'] = $field->type;
				$type_fields[$i]['max_length'] = $field->max_length;
				$type_fields[$i]['default'] = $field->default;
			}
		}else {
			echo "No hay registros para ".$table;
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
					$table_fk     = explode('id_', $field);
					$field        = $table_fk[1];
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
	* @param $table
	* @param $offset Indica a partir de que registro devuelve
	* @param $publicado
	* @param $orderBy array
	* @return array
	* */
	public function show_contents($table = "", $offset = 0, $publicado = 1, $orderBy = array()) {
		$data = array();
		if($table === "") {
			return $data;
		}

		if(!empty($orderBy)) {
			foreach ($orderBy as $key => $value) {
				$orderBy 		= $value['orden'];
				$direccionBy	= $value['direccion'];
				$this->db->order_by($orderBy, $direccionBy);
			}
		}else{
			#Si no tiene orden establecido pero la tabla tiene columna 'orden', la utilizo por defecto 
			$resultado = $this->db->list_fields($table);
        	if(in_array('orden', $resultado)){
				$this->db->order_by('orden', 'asc');
        	}else{
				#Si no tiene columna 'orden', utilizo la 1er columna de datos por defecto 
        		$this->db->order_by($resultado[1], 'asc');
        	}
		}

		$this->db->limit($this->config->item('items_per_page'), $offset);
		if($publicado != false) {
			$this->db->where('publicado',$publicado);
		}
		$resultado = $this->db->get($table);

		if($resultado) {

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
						$data[$indice][$table][$key]['hidden-phone'] = "";
					}elseif(strpos($key, "id_") !== false) {
						//FK
						$table_fk = explode('id_', $key);
						$datosFK  = $this->db->get_where($table_fk[1], array($key=>$value));
						$resultFK = $datosFK->row_array();
						if(is_array($resultFK)){
							$ind = array_keys($resultFK)[1];
							$valor    = isset($resultFK['nombre']) ? $resultFK['nombre'] : (isset($resultFK['titulo']) ? $resultFK['titulo'] : $resultFK[$ind]);
						}else{
							$valor = '';
						}
						$data[$indice][$table][$key]['valor'] = $valor;
						$data[$indice][$table][$key]['hidden-phone'] = "hidden-phone";
					}elseif(in_array($key,camposAuditoria())) {
						continue;
					}else {
						if(strlen($value) > 120) {
							$value = substr($value, 0, 120) . '...';
						}
						$data[$indice][$table][$key]['valor'] = $value;
						$data[$indice][$table][$key]['hidden-phone'] = "";
					}
				}

				$data[$indice][$table]['editar']['valor'] = "<i class=\"fa fa-edit\"></i>";
				$data[$indice][$table]['editar']['hidden-phone'] = "";
				$data[$indice][$table]['eliminar']['valor'] = "<i class=\"fa fa-trash-o\"></i>";
				$data[$indice][$table]['eliminar']['hidden-phone'] = "";

			}
		}
		return $data;
	}

	/**
	* Devuelve todas las admin_secciones existentes
	* @return array
	* */
	public function listar() {
		$res = $this->db->get($this->_nombreDeTabla);
		return $res->result_array();
	}

	/**
	* Agregar datos a la tabla admin_secciones
	* @param $data Array
	* @return bool
	* */
	public function agregar($data = null) {
		if(is_null($data) || !is_array($data)) {
			return null;
		}

		$this->desplazarOrden($data['orden']);

		$data['created'] = date('Y-m-d H:i:s');

		return $this->db->insert($this->_nombreDeTabla, $data);
	}

	/**
	* Agregar datos a la tabla admin_secciones
	* @param $data Array
	* @return bool
	* */
	public function editar($data = null, $id = 0) {
		if(is_null($data) || !is_array($data)) {
			return null;
		}

		$this->reemplazarOrden($id, $data['orden']);

		$this->db->where('id_admin_secciones', $id);
		return $this->db->update($this->_nombreDeTabla, $data);
	}

	/**
	* Buscar una seccion por id
	* @param $id
	* @return array
	* */
	public function buscarById($id = 0) {
		if($id === 0) {
			return array();
		}

		$sec = $this->db->get_where($this->_nombreDeTabla,array('id_admin_secciones'=>$id));
		$seccion = $sec->row_array();

		return $seccion;
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

		$get   = $this->db->select('friendly_url')->from($this->_nombreDeTabla)->where('id_admin_secciones',$id)->get();
		$valor = $get->row_array();
		return $valor['friendly_url'];
	}

	/**
	* Buscar una seccion por friendly_url
	* @param $friendly
	* @return array
	* */
	public function buscarByFriendly($friendly = "") {
		if($friendly === "") {
			return array();
		}

		$sec = $this->db->get_where($this->_nombreDeTabla,array('friendly_url'=>$friendly));
		$seccion = $sec->row_array();

		return $seccion;
	}

	public function delete_member($valor) {
		if(isset($valor) && $valor != "") {
			$dl = $this->db->delete($this->_nombreDeTabla,array('id_admin_secciones'=>$valor));
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
			$this->db->where('id_admin_secciones',$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('enabled'=>0));
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
			$this->db->where('id_admin_secciones',$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('enabled'=>1));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}

	/**
	* Retorna la cantidad de veces que el friendly est� usado
	*
	* @param string $friendly
	*
	* @return int
	*/
	public function friendlyDisponible($friendly) {
		if($friendly === "") {
			return array();
		}

		$get = $this->db->select('id_admin_secciones')->from($this->_nombreDeTabla)->like('friendly_url',$friendly)->get();

		return $get->num_rows();
	}

	/**
	* Asigna permisos a un usuario a una seccion
	*
	* @param array $array
	* @return bool
	*/
	public function asignarPermisos($array) {
 		if(is_array($array['seccion'])) {
			foreach($array['seccion'] as $key => $value) {
		$insert = array(
			'id_admin_user'     => $array['usuario'],
					'id_admin_secciones'=> $value,
		);

		if($array['id_admin_permisos'] != "") {
			$this->db->where('id_admin_user_seccion_permisos',$array['id_admin_permisos']);
			return $this->db->update('admin_user_seccion_permisos', $insert);
		}

				$ins = $this->db->insert('admin_user_seccion_permisos', $insert);
			}
			return;
		}else {
			return array();
		}
	}

	/**
	*
	* @param int  $id
	*
	* @return
	*/
	public function getPermisosBy($id) {
		$this->db->where('id_admin_user_seccion_permisos', $id);
		$q = $this->db->get('admin_user_seccion_permisos');
		return $q->row_array();
	}

	/**
	*
	* @return array
	*/
	public function getPermisos() {
		$permisosget = $this->db->get('admin_user_seccion_permisos');
		$permisos    = $permisosget->result_array();
		return $permisos;
	}

	/**
	* Devuelve todas las admin_secciones existentes sin permisos
	* @return array
	* */
	public function listarNoAsignadasAPermisos() {
		$res       = $this->db->get($this->_nombreDeTabla);
		$todas     = $res->result_array();

		$res       = $this->db->get('admin_user_seccion_permisos');
		$asignadas = $res->result_array();

		foreach($todas as $i=>$cadauna) {
			foreach($asignadas as $key => $value) {
				if($cadauna['id_admin_secciones'] == $value['id_admin_secciones']) {
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
		$res       = $this->db->get('admin_user_seccion_permisos');
		$asignadas = $res->result_array();

		foreach($todas as $i=>$cadauna) {
			foreach($asignadas as $key => $value) {
				if($cadauna['id_admin_secciones'] == $value['id_admin_secciones']) {
					unset($todas[$i]);
				}
			}
		}

		return $todas;
	}

	public function desplazarOrden($ordenDesde) {
		$existente = $this->db->where('orden',$ordenDesde)->from($this->_nombreDeTabla)->get();
		$existe    = $existente->row_array();

		if(!empty($existe)) {
			$get = $this->db->where('orden>=', $ordenDesde)->from($this->_nombreDeTabla)->order_by('orden','desc')->get();
			$res = $get->result_array();

			foreach($res as $id => $pagina) {
				$this->db->where('id_admin_secciones',$pagina['id_admin_secciones']);
				$this->db->set('orden', 'orden+1', FALSE);
				$this->db->set('updated',date('Y-m-d H:i:s'));
				$db = $this->db->update($this->_nombreDeTabla);
			}
		}
	}

	public function reemplazarOrden($id, $ordenNuevo) {
		$existente = $this->db->where('orden',$ordenNuevo)->from($this->_nombreDeTabla)->get();
		$existe    = $existente->row_array();

		if(!empty($existe)) {
			$yaexiste       = $existe['id_admin_secciones'];
			/*---*/
			$get            = $this->db->select('orden')->from($this->_nombreDeTabla)->where('id_admin_secciones',$id)->get();
			$res            = $get->row_array();
			$ordenQueExiste = $res['orden'];
			/*---*/
			$update         = array('orden'  =>$ordenQueExiste,'updated'=> date('Y-m-d H:i:s'));
			$this->db->where('id_admin_secciones',$yaexiste);
			$db = $this->db->update($this->_nombreDeTabla, $update);
		}
	}

	/*
	|-------------------------------------------------------------------------------
	| SECCIONES
	|-------------------------------------------------------------------------------
	*/

	public function agregarSeccion($nombre, $url, $menu, $home, $icono, $orden){

		$this->desplazarOrden($orden);

		$home  = ($home == true) ? 1 : 0;
		$menu  = ($menu == true) ? 1 : 0;
		$orden = ($orden <= 0) ? $this->getTotalSecciones() + 1 : $orden;

		$sql = "INSERT INTO ".$this->_prefix."admin_secciones(nombre, friendly_url, mostrar_menu, es_home, icono, orden) VALUES(".$this->db->escape($nombre).", ".$this->db->escape($url).", ".$this->db->escape($menu).", ".$this->db->escape($home).", ".$this->db->escape($icono).", ".$this->db->escape($orden).");";
		$qry = $this->db->query($sql);

		if($this->db->insert_id() > 0){
			$resp = array("error" => false, "message" => "Se ha creado la sección", "data" => array());
		}else{
			$resp = array("error" => true, "message" => "No se pudo crear la sección", "data" => array());
		}

		return $resp;
	}

	public function editarSeccion($id, $nombre, $url, $home, $menu, $icono, $orden){
		
		$this->reemplazarOrden($id, $orden);

		$home  = ($home == true) ? 1 : 0;
		$menu  = ($menu == true) ? 1 : 0;
		$orden = ($orden <= 0) ? $this->getTotalSecciones() + 1 : $orden;

		$sql = "UPDATE ".$this->_prefix."admin_secciones SET nombre = ".$this->db->escape($nombre).", friendly_url = ".$this->db->escape($url).", mostrar_menu = ".$this->db->escape($menu).", es_home = ".$this->db->escape($home).", icono = ".$this->db->escape($icono).", orden = ".$this->db->escape($orden)." WHERE id_admin_secciones = ".$this->db->escape($id).";";
		$qry = $this->db->query($sql);

		if($this->db->affected_rows() > 0){
			$resp = array("error" => false, "message" => "Se ha editado la sección", "data" => array());
		}else{
			$resp = array("error" => true, "message" => "No se pudo editar la sección", "data" => array('q'=>'No hay modificaciones para hacer.'));
		}

		return $resp;
	}

	public function borrarSeccion($id_seccion){
		$sql = "DELETE FROM ".$this->_prefix."admin_secciones WHERE id_admin_secciones = ".$this->db->escape($id_seccion).";";
		$qry = $this->db->query($sql);

		if($qry){
			$resp = array("error" => false, "message" => "Se ha borrado la sección.", "data" => array());
		}else{
			$resp = array("error" => true, "message" => "No se ha podido borrar la sección.", "data" => array());
		}

		return $resp;
	}

	public function getSeccion($id){
		$sql = "SELECT * FROM ".$this->_prefix."admin_secciones WHERE id_admin_secciones = ".$this->db->escape($id).";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$seccion = $qry->row_array();
			$resp = $seccion;
		}else{
			$resp = array();
		}

		return $resp;
	}

	public function getSecciones($pagina){
		$sql_add = "";

		$offset = ($pagina - 1) * $this->config->item('limit_secciones');
		$sql_add .= " LIMIT ". $this->config->item('limit_secciones') ." OFFSET ". $offset;

		$sql = "SELECT * FROM ".$this->_prefix."admin_secciones WHERE 1 ".$sql_add.";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$secciones = $qry->result_array();

			foreach($secciones as $key => $value){
				$secciones[$key]["base_url"] = $this->config->item("base_url");
				$secciones[$key]["menu"] = ($secciones[$key]["mostrar_menu"] == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
				$secciones[$key]["home"] = ($secciones[$key]["es_home"] == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
			}

			$resp = $secciones;
		}else{
			$resp = array();
		}

		return $resp;
	}

	public function getTodasSecciones(){
		$sql_add = "ORDER BY nombre ASC";

		$sql = "SELECT * FROM ".$this->_prefix."admin_secciones WHERE 1 ".$sql_add.";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			$secciones = $qry->result_array();

			foreach($secciones as $key => $value){
				$secciones[$key]["base_url"] = $this->config->item("base_url");
			}

			$resp = $secciones;
		}else{
			$resp = array();
		}

		return $resp;
	}

	public function getTotalSecciones(){
		$sql_add = "";

		$sql = "SELECT COUNT(id_admin_secciones) AS tot FROM ".$this->_prefix."admin_secciones WHERE 1 ".$sql_add.";";
		$qry = $this->db->query($sql);

		if($qry->num_rows() > 0){
			return $qry->row_array()["tot"];
		}else{
			return 0;
		}
	}
}