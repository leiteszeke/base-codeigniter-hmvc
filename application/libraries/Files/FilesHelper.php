<?php
    class FilesHelper {
        private $ci;
        private $path;
        private $root;

        public function __construct() {
            $this->ci =& get_instance();
            $this->path = $this->ci->config->item("base_path");
            $this->root = "data/upload/"; 
        }

        public function subirArchivo($carpeta, $archivo, $hash){
            if($archivo["error"] == 0){
                $file    = array();
                $carpeta = (substr($carpeta, -1) == "/") ? $carpeta : $carpeta . "/";

                if(!$this->validarArchivo($this->path . $this->root . $carpeta)){
                    if(!$this->crearCarpeta($this->path . $this->root . $carpeta)){
                        die("No se pudo crear la carpeta.");                    
                    }
                }

                $ext  = explode(".", $archivo["name"]);
                $ext  = array_pop($ext);
                $file = array();
                $generated_hash        = ($hash) ? date("dmY_His") . "_" : "";
                $file["size"]          = $archivo["size"];
                $file["type"]          = $archivo["type"];
                $file["original_name"] = $archivo["name"];
                $file["uploaded_name"] = $generated_hash . $archivo["name"];
                $file["extension"]     = $ext;

                $ruta = $this->path . $this->root . $carpeta;

                if(move_uploaded_file($archivo["tmp_name"], $ruta . $file["uploaded_name"])){
                    $resp = array("error" => false, "message" => "El archivo ha sido subido.", "data" => $file);
                }else{
                    $resp = array("error" => true, "message" => "No se ha podido subir el archivo.", "data" => $file);
                }
            }else{
                $resp = array("error" => true, "message" => "No se ha podido procesar el archivo.", "data" => array());
            }

            return $resp;
        }

        public function normalizarArchivos($archivos){
            $res = array();

			foreach ($archivos as $key => $value) {
				$archivo = $archivos[$key];
				$i = 0;
				foreach ($archivo as $key2 => $value) {
					$res[$key2][$key] = $value;
				}
			}

			return $res;
        }

        public function validarArchivo($ruta){
            return file_exists($ruta);
        }

        private function crearCarpeta($ruta){
            return mkdir($ruta);
        }
    }