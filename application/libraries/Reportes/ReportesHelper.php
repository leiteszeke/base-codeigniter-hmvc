<?php
    class ReportesHelper {
        private $ci;
        private $path;
        private $root;
        private $excel;

        public function __construct() {
            $this->ci   =& get_instance();
            $this->path = $this->ci->config->item("base_path");
            $this->root = "data/reportes/"; 

            if(!$this->ci->load->library("Reportes/PHPExcel/PHPExcel.php")){
                die("No se encuentra la libreria PHPExcel.");
            }
            
            $this->excel  = new PHPExcel();
        }

        public function generarReporte($data = array(), $headers = array(), $body = array(), $nombre){
            $this->excel->setActiveSheetIndex(0);

            if(!empty($data)){
                $fila = 1;
                if(!empty($headers)){
                    $letra = 'A';

                    foreach($headers as $key => $value){
                        $this->excel->getActiveSheet()->SetCellValue($letra . $fila, $headers[$key]);
                        $letra++;
                    }

                    $fila++;
                }

                if(empty($body)){
                    foreach($data[0] as $key => $value){
                        $body[] = $key;
                    }
                }

                foreach($data as $key => $value){
                    $letra = 'A';
                    foreach($body as $key2 => $value){
                        $this->excel->getActiveSheet()->SetCellValue($letra . $fila, $data[$key][$body[$key2]]);
                        $letra++;
                    }

                    $fila++;
                }

                $letra = 'A';

                foreach($body as $key => $value){
                    $this->excel->getActiveSheet()->getColumnDimension($letra)->setAutoSize(true);
                    $letra++;
                }

                $archivo = $this->path . $this->root . $nombre;
                $ExcelWriter = new PHPExcel_Writer_Excel2007($this->excel);
                $ExcelWriter->save($archivo);
            
                if($this->Files_model->validarCarpeta($archivo)){
                    $resp = array("error" => false, "message" => "Se ha creado el reporte.", "data" => array("archivo" => $nombre));    
                }else{
                    $resp = array("error" => true, "message" => "Ha ocurrido un error.", "data" => array());
                }
            }else{
                $resp = array("error" => true, "message" => "No se han recibido datos.", "data" => array());
            }
            
            return $resp;
        }    
    }