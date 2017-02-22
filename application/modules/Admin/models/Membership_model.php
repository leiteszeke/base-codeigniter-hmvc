<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Membership_model extends CI_Model {

	private $_id_admin_user;
	private $_username;
	private $_password;
	private $_email_address;
	private $_first_name;
	private $_last_name;
	private $_nombreDeTabla;

	function __construct() {
		$this->_nombreDeTabla = "admin_user";
	}

	/**
	* Inicializa valores
	*
	* @return
	*/
	public function Membership_model() {
		$this->_id_admin_user = null;
		$this->_username = "";
		$this->_password = "";
		$this->_email_address = "";
		$this->_first_name = "";
		$this->_last_name = "";

		return array(
			'id_admin_user'=> $this->_id_admin_user,
			'username'     => $this->_username,
			'password'     => $this->_password,
			'email_address'=> $this->_email_address,
			'first_name'   => $this->_first_name,
			'last_name'    => $this->_last_name
		);
	}

	/**
	* Busca al usuario que viene por $_POST en la base de datos
	* @return bool
	* */
	public function validate() {
		if($this->input->post()) {
			$this->db->where('username', $this->input->post('username'));
			$this->db->where('password', md5($this->input->post('password')));
			$query = $this->db->get($this->_nombreDeTabla);
			
			if($this->db->affected_rows() == 1) {
				$datos           = $query->row_array();

				$usuarioLogueado = array(
					'id'                   => $datos['id_admin_user'],
					'username'             => $datos['username'],
					'firstname'            => $datos['first_name'],
					'lastname'             => $datos['last_name'],
					'email_address'        => $datos['email_address'],
					'secciones_con_permiso'=> $this->Admin_model->getSeccionesByUser($datos['id_admin_user']),
					'opciones_con_permiso' => $this->Admin_model->getOpcionesIdConPermisosByUser($datos['id_admin_user'])
				);

				$this->session->set_userdata(array($this->config->item('encryption_key')=>$usuarioLogueado));

				return true;
			}
		}else {
			log_message('ERROR', 'No se ha podido ingresar');
			return false;
		}
	}

	/**
	* Crea un usuario con datos que vienen de $_POST
	* @return bool
	* */
	public function create_member() {
		if($this->input->post()) {
			$created                = date('Y-m-d H:i:s');
			$new_member_insert_data = array(
				'first_name'   => $this->input->post('first_name'),
				'last_name'    => $this->input->post('last_name'),
				'email_address'=> $this->input->post('email_address'),
				'username'     => $this->input->post('username'),
				'password'     => md5($this->input->post('password')),
				'created'      => $created
			);

			$insert = $this->db->insert($this->_nombreDeTabla, $new_member_insert_data);
			return $insert;
		}else {
			return false;
		}
	}

	/**
	* Modifica un usuario con datos que vienen de $_POST
	* @return bool
	* */
	public function update_member() {
		if($this->input->post()) {

			$updated                = date('Y-m-d H:i:s');
			$new_member_insert_data = array(
				'first_name'   => $this->input->post('first_name'),
				'last_name'    => $this->input->post('last_name'),
				'email_address'=> $this->input->post('email_address'),
				'username'     => $this->input->post('username'),
				'updated'      => $updated
			);

			if($this->input->post('password')) {
				$new_member_insert_data['password'] = md5($this->input->post('password'));
			}

			$this->db->where('id_admin_user',$this->input->post('id_admin_user'));
			$update = $this->db->update($this->_nombreDeTabla, $new_member_insert_data);
			return $update;
		}else {
			return false;
		}
	}
	
	/**
	*
	* @param string $clave
	* @param int $id
	*
	* @return bool
	*/
	public function change_password($clave = "", $id = "") {
		if($clave !== "" && $id !== "") {
			$datos = array('password'=>$clave);
			$this->db->where('id_admin_user',$id);
			return $this->db->update($this->_nombreDeTabla, $datos);
		}else {
			return false;
		}
	}

	/**
	*
	* @param $id
	*
	* @return
	*/
	public function search_by_id($id = null) {
		if(!is_null($id)) {
			$get = $this->db->get_where($this->_nombreDeTabla,array('id_admin_user'=>$id));
			return $get->row_array();
		}else {
			return false;
		}
	}

	/**
	*
	* @param $username
	*
	* @return
	*/
	public function search_by_username($username = null) {
		if(!is_null($username)) {
			$get = $this->db->get_where($this->_nombreDeTabla,array('username'=>$username));
			return $get->row_array();
		}else {
			return false;
		}
	}

	public function delete_member($valor) {
		if(isset($valor) && $valor != "") {
			$dl = $this->db->delete($this->_nombreDeTabla,array('id_admin_user'=>$valor));
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
			$this->db->where('id_admin_user',$valor);
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
			$this->db->where('id_admin_user',$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('enabled'=>1));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}
	
	public function getUsers(){
		$g = $this->db->get($this->_nombreDeTabla);
		return $g->result_array();
	}
	
}