<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Permisos_model extends CI_Model {
        public function __construct() {
            parent::__construct();
            $this->_prefix = $this->config->item("db_prefix");
        }

        public function getPermisosSecciones($offset = false){
            $sql_add = "";

			if(is_numeric($offset)){
				$offset = ($offset > 0) ? ($offset - 1) * $this->config->item("limit_permisos") : 0;	
			}

            $sql_add .= " ORDER BY id_admin_user ASC";
			$sql_add .= " LIMIT ".$offset.", ".$this->config->item("limit_permisos");

            $sql = "SELECT * FROM ".$this->_prefix."admin_user_seccion_permisos WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $permisos = $qry->result_array();

                foreach($permisos as $key => $value){
                    $seccion = $this->Secciones_model->getSeccion($permisos[$key]["id_admin_secciones"]);
                    $permisos[$key]["seccion"] = $seccion["nombre"];
                    $permisos[$key]["icono"] = $seccion["icono"];
                    $usuario = $this->Administradores_model->getAdministrador($permisos[$key]["id_admin_user"]);
                    $permisos[$key]["usuario"] = $usuario["username"];
                    $permisos[$key]["base_url"] = $this->config->item("base_url");
                }

                $resp = $permisos;
            }else{
                $resp = array();
            }

            return $resp;
        }

        public function getTotalPermisosSecciones($offset = false){
            $sql_add = "";

            $sql = "SELECT COUNT(id_admin_user_seccion_permisos) AS tot FROM ".$this->_prefix."admin_user_seccion_permisos WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                return $qry->row_array()["tot"];
            }else{
                return 0;
            }
        }

        public function getPermisosOpciones($offset = false){
            $sql_add = "";

			if(is_numeric($offset)){
				$offset = ($offset > 0) ? ($offset - 1) * $this->config->item("limit_permisos") : 0;	
			}

            $sql_add .= " ORDER BY id_admin_opciones ASC";
			$sql_add .= " LIMIT ".$offset.", ".$this->config->item("limit_permisos");

            $sql = "SELECT * FROM ".$this->_prefix."admin_user_opcion_permisos WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $permisos = $qry->result_array();
                
                foreach($permisos as $key => $value){
                    $opcion = $this->Opciones_model->getOpcion($permisos[$key]["id_admin_opciones"]);
                    $permisos[$key]["opcion"] = $opcion["nombre"];
                    $permisos[$key]["icono"] = $opcion["icono"];
                    $seccion = $this->Secciones_model->getSeccion($opcion["id_admin_secciones"]);
                    $permisos[$key]["seccion"] = $seccion["nombre"];
                    $usuario = $this->Administradores_model->getAdministrador($permisos[$key]["id_admin_user"]);
                    $permisos[$key]["usuario"] = $usuario["username"];
                    $permisos[$key]["base_url"] = $this->config->item("base_url");
                }

                $resp = $permisos;
            }else{
                $resp = array();
            }

            return $resp;
        }

        public function getTotalPermisosOpciones($offset = false){
            $sql_add = "";

            $sql = "SELECT COUNT(id_admin_user_opcion_permisos) AS tot FROM ".$this->_prefix."admin_user_opcion_permisos WHERE 1 ".$sql_add.";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                return $qry->row_array()["tot"];
            }else{
                return 0;
            }
        }

        public function getPermisosByUser($usuario, $tipo){
            $tabla = $this->_prefix . "admin_user_" . $tipo . "_permisos";
            
            $sql = "SELECT * FROM ".$tabla." WHERE id_admin_user = ".$this->db->escape($usuario).";";
            $qry = $this->db->query($sql);

            if($qry->num_rows() > 0){
                $permisos = $qry->result_array();

                $resp = array("error" => false, "message" => "Se han encontrado permisos.", "data" => $permisos);
            }else{
                $resp = array("error" => true, "message" => "No tienes ningun permiso.", "data" => array());
            }

            return $resp;
        }

        public function asignarPermisos($usuario, $tipo, $permisos){
            $tabla   = $this->_prefix . "admin_user_" . $tipo . "_permisos";
            $colum   = $tipo . "es";
            $resp    = array("error" => false, "message" => "Se han asignado los permisos.", "data" => array());

            foreach($permisos as $key => $value){
                $sql = "DELETE FROM " . $tabla . " WHERE id_admin_user = ".$this->db->escape($usuario)." AND id_admin_".$colum." = ".$this->db->escape($permisos[$key]).";";
                $qry = $this->db->query($sql);

                $sql = "INSERT INTO " . $tabla . " (id_admin_user, id_admin_" . $colum . ") VALUES(".$this->db->escape($usuario).", ".$this->db->escape($permisos[$key]).");";
                $qry = $this->db->query($sql);

                if($this->db->insert_id() <= 0){
                    $resp = array("error" => true, "message" => "Ha ocurrido un error.", "data" => array());
                }
            }

            return $resp;
        }

        public function eliminarPermiso($permiso, $tipo){
            $tabla = $this->_prefix . "admin_user_" . $tipo . "_permisos";

            $sql = "DELETE FROM " . $tabla . " WHERE id_admin_user_" . $tipo . "_permisos = " . $this->db->escape($permiso);
            $qry = $this->db->query($sql);

            if($qry){
                $resp = array("error" => false, "message" => "Se ha eliminado el permiso.", "data" => array());
            }else{
                $resp = array("error" => true, "message" => "No se ha eliminado el permiso.", "data" => array());
            }

            return $resp;
        }
    }