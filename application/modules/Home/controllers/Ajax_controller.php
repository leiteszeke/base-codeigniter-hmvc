<?php

class Ajax_controller extends CI_Controller {
    private $data = array();

	function __construct(){ 
		parent::__construct();
        
        $this->data['base_url'] = $this->config->item('base_url');
        $this->data['base_path'] = $this->config->item('base_path');
	}

    /*********************************************************/
    /**
     * Almacena la sesión de facebook
     * */
    function store_user(){
        if($this->input->post()){
                    
            $fbuid = $_POST['fbuid'];
            $acctk = $_POST['acctk'];
            $name = $_POST['name'];
            $last_name = $_POST['last_name'];
            
            $this->region_model->almacenarDataFacebook($fbuid, $acctk, $name, $last_name);   
        }
    }
    
    function delete_session(){
        if($this->session->userdata('dmfbat')){
            $this->session->unset_userdata('dmfbat');
        }
    }
    /****************************************************/
    
    function localidades(){
        $provincia = $this->uri->segment(3);
        $this->db->order_by('localidad','ASC');
        $res = $this->db->get_where('localidades',array('provincia'=>$provincia));
        echo json_encode($res->result_array());
    }
    
    function checkemail(){
        if($this->input->post()){
            echo json_encode($this->system_model->validEmailEnUso($this->input->post('email'),$this->input->post('region'))); // ? json_encode(false) : json_encode(true);
        }else{
            echo json_encode(false);
        }
    }
    
    function checkdni(){
        if($this->input->post()){
            $region = $this->input->post('region');
            $dni = $this->input->post('dni');
            
            $yaInscriptoComoLiderOIntegrante = true;

            $lideres = $this->system_model->getLideres($region);
            foreach($lideres as $lider){
                if($lider['dni'] == $dni) {
                    $yaInscriptoComoLiderOIntegrante = false;
                }
            }
            
            $integrantes = $this->system_model->getIntegrantes($region);
            foreach($integrantes as $integrante){
                if($integrante['dni'] == $dni) {
                    $yaInscriptoComoLiderOIntegrante = false;
                }
            }
            
            echo json_encode($yaInscriptoComoLiderOIntegrante);
            
            //echo (is_array($this->system_model->getUsuario($this->input->post('dni')))) ? json_encode(false) : json_encode(true);
        }else{
            echo json_encode(false);
        }
    }
	
    function validImage(){
        if(isset($_FILES['file'])){
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            if(!in_array($ext, $this->config->item('ext_imagenes_permitidas'))){
                echo 0;
            }else{
                echo 1;
            }
        }else{
            echo -1;
        }
    }
    
    /**
     * @return -2 si no hubo post
     * @return -1 si el video no existe
     * @return 0 si el video no tiene formato permitido
     * @return tamanio del video convertido. =0 si no hay video, >0 si hay video
     * */
    function videoMp4(){ 
        if(isset($_FILES['file'])){
            echo validVideoFile($_FILES['file']);
        }else{
            echo -2;
        }
    }
    
    function getEntradasVip(){
        if($this->input->post()){
            $fbuid = $_POST['fbuid'];
            $ent = $this->region_model->getEntradasVip($fbuid);
            echo json_encode(empty($ent));
        }else{
            echo json_encode(false);
        }
    }
    
}

// END OF FILE
?>