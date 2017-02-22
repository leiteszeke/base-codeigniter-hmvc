<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Administradores_model extends CI_Model {
        public function __construct() {
            parent::__construct();
            $this->_prefix = $this->config->item("db_prefix");
        }

        public function agregarAdministrador($nombre, $apellido, $usuario, $email, $password){
            if(!$this->validarAdministradorPorUsuario($usuario) && !$this->validarAdministradorPorEmail($email)){
                $sql = "INSERT INTO ".$this->_prefix."admin_user(first_name, last_name, username, email_address, password) VALUES(".$this->db->escape($nombre).", ".$this->db->escape($apellido).", ".$this->db->escape($usuario).", ".$this->db->escape($email).", ".$this->db->escape(md5($password)).");";
                $qry = $this->db->query($sql);

                if($this->db->insert_id() > 0){
                    $resp = array("error" => false, "message" => "Se ha creado el administrador.", "data" => array());
                }else{
                    $resp = array("error" => true, "message" => "No se ha podido crear el administrador.", "data" => array());
                }
            }else{
                $resp = array("error" => true, "message" => "Ya hay un administrador con estos datos registrados.", "data" => array());
            }

            return $resp;
        }

        public function editarAdministrador($id, $nombre, $apellido, $usuario, $email, $password){
            $admin = $this->getAdministrador($id);

            if(!empty($admin)){
                if($admin["username"] != $usuario){
                    if($this->validarAdministradorPorUsuario($usuario)){
                        return array("error" => true, "message" => "Este nombre de usuario ya está en uso.", "data" => array());
                    }
                }

                if($admin["email_address"] != $email){
                    if($this->validarAdministradorPorEmail($email)){
                        return array("error" => true, "message" => "Este email ya está en uso.", "data" => array());
                    }
                }

                $sql = "UPDATE ".$this->_prefix."admin_user SET first_name = ".$this->db->escape($nombre).", last_name = ".$this->db->escape($apellido).", username = ".$this->db->escape($usuario).", email_address = ".$this->db->escape($email).", password = ".$this->db->escape(md5($password)).");";
                $qry = $this->db->query($sql);

                if($this->db->affected_rows() > 0){
                    $resp = array("error" => false, "message" => "Se ha editado el administrador.", "data" => array());
                }else{
                    $resp = array("error" => true, "message" => "No se ha podido editar el administrador.", "data" => array());
                }
            }else{
                $resp = array("error" => true, "message" => "Ha ocurrido un error.", "data" => array());
            }
            
            return $resp;
        }

        public function borrarAdministrador($id){
            $sql = "DELETE FROM ".$this->_prefix."admin_user WHERE id_admin_user = ".$this->db->escape($id).";";
            $qry = $this->db->query($sql);

            if($qry){
                $resp = array("error" => false, "message" => "El administrador ha sido borrado.", "data" => array());
            }else{
                $resp = array("error" => true, "message" => "No se ha podido borrar el administrador.", "data" => array());
            }

            return $resp;
        }

        public function getAdministrador($id){
            $sql = "SELECT * FROM " . $this->_prefix . "admin_user WHERE id_admin_user = " . $this->db->escape($id) . ";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $usuario = $qry->row_array();

                $resp = $usuario;
            }else{
                $resp = array();
            }

            return $resp;
        }

        public function getAdministradores($offset = false, $nombre = false, $email = false){
            $sql_add = "";

			if($nombre != false){
				$sql_add .= " AND (first_name LIKE '%".$this->db->escape_like_str($nombre)."%'";
				$sql_add .= " OR last_name LIKE '%".$this->db->escape_like_str($nombre)."%')";
			}

			if($email != false){
				$sql_add .= " AND email_address LIKE '%".$this->db->escape_like_str($email)."%'";
			}

			if(is_numeric($offset)){
				$offset = ($offset > 0) ? ($offset - 1) * $this->config->item("limit_admins") : 0;	
			}

			$sql_add .= " LIMIT ".$offset.", ".$this->config->item("limit_admins");

            $sql = "SELECT * FROM ".$this->_prefix."admin_user WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $usuarios = $qry->result_array();

                foreach($usuarios as $key => $value){
                    $usuarios[$key]["base_url"] = $this->config->item("base_url");
                }
                
                $resp = $usuarios;
            }else{
                $resp = array();
            }

            return $resp;
        }

        public function getTotalAdministradores($nombre = false, $email = false){
            $sql_add = "";

            $sql = "SELECT COUNT(id_admin_user) AS tot FROM ".$this->_prefix."admin_user WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                return $qry->row_array()["tot"];
            }else{
                return 0;
            }
        }

        public function getTodosAdministradores(){
            $sql = "SELECT * FROM ".$this->_prefix."admin_user ORDER BY username ASC;";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $usuarios = $qry->result_array();
                $resp = $usuarios;
            }else{
                $resp = array();
            }

            return $resp;
        }

        public function validarAdministradorPorUsuario($usuario){
            $sql = "SELECT id_admin_user FROM ".$this->_prefix."admin_user WHERE username = ".$this->db->escape($usuario).";";
            $qry = $this->db->query($sql);

            return $qry->row_array(); 
        }

        public function validarAdministradorPorEmail($email){
            $sql = "SELECT id_admin_user FROM ".$this->_prefix."admin_user WHERE email_address = ".$this->db->escape($email).";";
            $qry = $this->db->query($sql);

            return $qry->row_array(); 
        }
        
    }