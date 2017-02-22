<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	* Obtiene secciones habilitadas para un usuario
	* @param userId
	* @return array de secciones o empty
	* */
	public function getSeccionesByUser($userId = 0) {
		if($userId === 0) {
			return array();
		}

		$seccionIds = $this->getSeccionesIdConPermisosByUser($userId);
		$secRes = null;
		
		if(!empty($seccionIds)){
			$this->db->order_by('orden','asc');
			$this->db->where_in('id_admin_secciones', $seccionIds);
			$secRes     = $this->db->get('admin_secciones');
		}

		if(!$secRes) {
			return array();
		}

		return $secRes->result_array();

	}

	/**
	* Obtiene los id de las secciones para un usuario
	* @param userId
	* @return array de id's o empty
	* */
	public function getSeccionesIdConPermisosByUser($userId = 0) {
		if($userId === 0) {
			return array();
		}

		$this->db->select('id_admin_secciones');
		$this->db->where('id_admin_user', $userId);
		$adminpermisos = $this->db->get('admin_user_seccion_permisos');

		$ids           = array();
		if($adminpermisos) {
			$d = $adminpermisos->result_array();
			foreach($d as $k=>$v) {
				if(!in_array($v['id_admin_secciones'], $ids)){
					$ids[] = $v['id_admin_secciones'];
				}
			}
		}

		return $ids;
	}

	/**
	* Obtiene opciones de una secci�n habilitadas para un usuario
	* @param seccionId
	* @param userId
	* @return array de opciones de la secci�n o empty
	* */
	public function getOpcionesSeccionByUser($seccionId = 0, $userId = 0) {
		if($seccionId === 0 || $userId === 0) {
			return array();
		}

		$opcionesdata = array();
		$this->db->order_by('orden','asc');
		$this->db->where('mostrar_menu', 1);
		$opcionesRes = $this->db->get_where('admin_opciones',array('id_admin_secciones'=>$seccionId));
		if(empty($opcionesRes)) return $opcionesdata;

		$opciones = $opcionesRes->result_array();

		foreach($opciones as $opcion) {
			if($this->tengoPermisosParaOpcion($opcion['id_admin_opciones'],$userId)) {
				array_push($opcionesdata, $opcion);
			}
		}

		return $opcionesdata;
	}

	/**
	* Obtiene los id de las opciones para un usuario
	* @param userId
	* @return array de id's o empty
	* */
	public function getOpcionesIdConPermisosByUser($userId = 0) {
		if($userId === 0) {
			return array();
		}

		$this->db->select('id_admin_opciones');
		$this->db->where('id_admin_user', $userId);
		$adminopciones = $this->db->get('admin_user_opcion_permisos');
		return $adminopciones->result_array();
	}

	/**
	* Buscar si una opci�n en particular est� permitida para un usuario
	* @param opcionId
	* @param userId
	* @return bool
	* */
	public function tengoPermisosParaOpcion($opcionId = 0, $userId = 0) {
		if($opcionId === 0 || $userId === 0) {
			return false;
		}

		$this->db->where('id_admin_opciones', $opcionId);
		$this->db->where('id_admin_user', $userId);
		$res = $this->db->get('admin_user_opcion_permisos');

		return $this->db->affected_rows();
	}

	/**
	* Obtiene los datos de una secci�n en base su url
	* @param friendly_url
	* @return array de fila
	* */
	public function getSeccionByFriendlyUrl($friendly_url = "") {
		if($friendly_url === "") {
			return array();
		}

		$res = $this->db->get_where('admin_secciones',array('friendly_url'=>$friendly_url));
		return $res->row_array();
	}

	/**
	* Obtiene los datos de una opci�n en base su url
	* @param friendly_url
	* @return array de fila
	* */
	public function getOpcionesByFriendlyUrl($friendly_url = "") {
		if($friendly_url === "") {
			return array();
		}

		$res = $this->db->get_where('admin_opciones',array('friendly_url'=>$friendly_url));
		return $res->row_array();
	}
}