<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
    class Instalacion_model extends CI_Model{     
        private $_prefix;
        
        function Instalacion_model(){
            parent::__construct();
            $this->_prefix = "";
        }

        public function getRuta(){
            $ruta     = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            $segments = $this->uri->segments;
            $segments = implode("/", $segments);
            $ruta     = str_replace($segments, "", $ruta);

            return $ruta;
        }

        public function validarInstalacion(){
            $this->db->db_debug = FALSE;

            $ruta = $this->getRuta();

            $sql = "SELECT * FROM ".$this->_prefix."admin_config;";
            $qry = $this->db->query($sql);
        
            if($qry){
                $data = $qry->row_array();

                if($data["url_config"] == $ruta){
                    $resp = true;
                }else{
                    $resp = false;
                }
            }else{
                $resp = false;
            }

            return $resp;
        }
    }
?>