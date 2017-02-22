<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Install extends MX_Controller {
        private $_data;
        private $_instalated;
        private $_ruta;

        function __construct() {
            parent::__construct();
            $this->_data = array();

            // Data basica
            $this->_data['base_url'] = $this->config->item('base_url');
            // Validar Instalación
            $this->_instalated = $this->Instalacion_model->validarInstalacion();
            $this->_ruta       = $this->Instalacion_model->getRuta();
            // Partes del Sitio
            $this->_data['head'] = $this->parser->parse('common/head_inc', $this->_data);
        }

        public function index() {
            if(!$this->_instalated){
                $this->parser->parse('install_view', $this->_data);
            }else{
                redirect("http://" . $this->_ruta);
            }
        }
    }