<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class System_model extends CI_Model{     
	function System_model(){
        parent::__construct();
	}
    
    /**
     * CONSULTA LA IMAGEN DE PROMOCION DE UNA BANDA
     * @param int $id_banda
     * @return mixed
     * */
    function getImagenAutopromocion($id_banda){
        $sql = "SELECT `imagen_promocion` FROM `bandas` WHERE `id_bandas` = '".$id_banda."' AND `imagen_promocion` != '';";
        $query = $this->db->query($sql);
        
        if($query->num_rows() == 0){
            return false;
        }else{
            $data = $query->row_array();
            
            return $data['imagen_promocion'];
        }
    }
    
    /**
     * SETEA EL ESTADO DE UNA INVITACION
     * */
    function setEstadoInvitacion($hash, $estado){
        $data = array('estado' => $estado);
        $this->db->where('hash',$hash);
        $this->db->update('bandas_invitaciones',$data);
    }
    
    /**
     * VALIDA SI UN EMAIL PERTENECE A UN USUARIO INTEGRANTE DE UNA BANDA
     * @param string $email
     * @return bool TRUE si no lo encuentra
     * */
    function validEmailEnUso($email, $region){
        $this->db->select('id_usuarios');
        $this->db->from('usuarios');
        $this->db->where('email', $email);
        $res = $this->db->get();

        if($res->num_rows() == 0){
            return true;
        }else{
            $data = $res->row_array();

			$this->db->select('id_bandas');
            $this->db->from('bandas');
			$this->db->where('region',$region);
			$resreg = $this->db->get();
			
			if($resreg->num_rows() == 0){
				return true;
			}else{
				$bandas = $resreg->result_array();
				$arr = array();
				foreach($bandas as $registro){
					$arr[] = $registro['id_bandas'];
				}
				
				$this->db->select('id_bandas_integrantes');
				$this->db->from('bandas_integrantes');
				$this->db->where_in('id_banda',$arr);
				$this->db->where('id_usuario', $data['id_usuarios']);
				$res = $this->db->get();

				return ($res->num_rows() == 0);
			}
        }
    }
    
    /**
     * GENERA LA IMAGEN DE PROMOCION AGREGANDOLE TEXTO SOBRE LA IMAGEN CARGADA POR EL LIDER
     * @param string $archivo
     * @param string $nombre_banda
     * @return mixed
     * */
    function generarImagenPromocion($archivo, $nombre_banda){
        /* PROCESO EL NOMBRE DEL ARCHIVO */
        $ext = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        $aFile = explode(".", $archivo);
        $nombre_archivo_promo = $aFile[0] . "_promo." . $ext;
        $image_full = $this->config->item('base_path').'data/upload/bandasFoto/' . $archivo;

        /* CARGO LA LIBRERIA DE PROCESAMIENTO DE IMAGENES */
        $path_file = $this->config->item('base_path').'data/upload/bandasFoto/' . $archivo;
        $params = array('file' => $path_file);
        $this->load->library('Image_magician', $params);

        /* CAMBIO EL TAMAÑO */
        $this->image_magician->resizeImage($this->config->item('ancho_img_autopromocion'), $this->config->item('alto_img_autopromocion'), 0);
        /* AGREGO EL BORDE */
        $this->image_magician->addBorder(3);
        /* GUARDO LA NUEVA IMAGEN */
        $this->image_magician->saveImage($this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo, 100);

        /* PROCESO PARA AGREGAR EL TEXTO... */
        switch($ext){
            case "jpeg":
            case "jpg":
                $im = imagecreatefromjpeg($this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo);
            break;
            
            case "gif":
                $im = imagecreatefromgif($this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo);
            break;
            
            case "png":
                $im = imagecreatefrompng($this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo); 
            break;
        }
        
        /* AGREGO EL LOGO */
        $im_logo = imagecreatefrompng($this->config->item('base_path') . "images/autobombo_logo.png");
        imagecopy($im, $im_logo, 0, 0, 0, 0, 470, 60); 
        
        $red = ImageColorAllocate($im, 255, 0, 0);
        $black = ImageColorAllocate($im, 255, 255, 255);       
        
        /*
        $nombre_banda = strtoupper($nombre_banda);
        $linea_1 = strtoupper($linea_1);
        $linea_2 = strtoupper($linea_2);
        */
            
        Imagettftext($im, 25, 0, 10, 195, $red, $this->config->item('base_path').'fonts/franklinatfbq-wide-webfont.ttf', $nombre_banda);
        Imagettftext($im, 14, 0, 10, 215, $black, $this->config->item('base_path').'fonts/franklinatfbq-wide-webfont.ttf', "Está participando para tocar en vivo");
        Imagettftext($im, 14, 0, 10, 235, $black, $this->config->item('base_path').'fonts/franklinatfbq-wide-webfont.ttf', "En la gira Budweiser");
        Imagettftext($im, 14, 0, 230, 235, $red, $this->config->item('base_path').'fonts/franklinatfbq-wide-webfont.ttf', "Made for Music 2015.");
        
        switch($ext){
            case "jpeg":
            case "jpg":
                $resp = imagejpeg($im, $this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo, 100);            
            break;
            
            case "gif":
                $resp = imagegif($im, $this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo, 100);
            break;
            
            case "png":
                $resp = imagepng($im, $this->config->item('base_path').'data/upload/bandasFoto/' . $nombre_archivo_promo, 0);
            break;
        }

        ImageDestroy($im);
        
        return $nombre_archivo_promo;
    }
    
    /**
     * SUBE UN ARCHIVO AL SERVIDOR REALIZANDO VAIDACIONES PREVIAS.
     * @param array $file_name
     * @param array $file_tmp_name
     * @param string $directorio
     * @param array $extensiones_permitidas
     * @return mixed
     * */
    function subirArchivo($file_name, $file_tmp_name, $directorio, $extensiones_permitidas){
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if(in_array($ext, $extensiones_permitidas)){
            $ruta_destino = $this->config->item('base_path').'data/upload/'.$directorio.'/';
        
            $nombre_archivo = $this->getNombreUnico($file_name, $ruta_destino);
    
            if (! move_uploaded_file($file_tmp_name, $ruta_destino.$nombre_archivo) ){            
                return false;
            }else{
                return $nombre_archivo;
            }
        }else{
            return false;
        }
    }
    
    /**
     * GENERA UN NOMBRE DE ARCHIVO UNICO EN EL DIRECTORIO
     * @param string $nombre_archivo
     * @param string $directorio
     * @return string
     * */
    function getNombreUnico($nombre_archivo, $directorio){
        $ext = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));

        $nombre = md5(microtime()) . "." . $ext;
        
        while(file_exists($directorio . $nombre)){
            $nombre = md5(microtime()) . $ext;
        }
        
        return $nombre;
    }
    
    function getRegion(){
        $this->db->select('id_regiones, region, fecha_inicio, fecha_fin, clase');
        $this->db->from('regiones');
        $hoy = date('Y-m-d H:i:s');
        $this->db->where('fecha_inicio <', $hoy);
        $this->db->where('fecha_fin >', $hoy);
        $res = $this->db->get();
        
        $this->db->order_by("region", "asc"); 
        
        $region = $res->result_array();
        return ($region) ? $region[0] : null;
    }

    function getMaxFechaCierre(){
        $this->db->select_max('fecha_fin');
        $this->db->from('regiones');
        $res = $this->db->get();
        $region = $res->row_array();
        return $region['fecha_fin'];
    }
    
    function getRegionByID($id){
        $this->db->select('region, fecha_inicio, fecha_fin');
        $this->db->from('regiones');
        $this->db->where('id_regiones',$id);
        $res = $this->db->get();
        if(count($res->result_array()) === 0){
            return null;
        }
        return $res->result_array();
    }

    
    function getRegionesActivas(){
        $hoy = date('Y-m-d H:i:s');
        
        $this->db->select('id_regiones, region')->from('regiones');
        $this->db->where('fecha_inicio <', $hoy);
        $this->db->where('fecha_fin_votacion >', $hoy);
        $res = $this->db->get();
        
        $regiones = $res->result_array();
        if(empty($regiones)){
            return array();
        }else{
            return($regiones);
        }
    }
    
    function getInvitadoByHash($datos){
        if($datos[2] == "email"){
            $destino = base64_decode($datos[1]);
        }elseif($datos['2'] == "facebook"){
            $destino = $datos[1];
        }else{
            return null;
        }
        if(isset($datos[1])){            
            $hash = $datos[0]."_".$datos[1];
            $this->db->select('id_banda')->from('bandas_invitaciones');
            $this->db->where('hash',$hash);
            $this->db->where('origen',$datos[2]);
            $this->db->where('destino',$destino);
            $this->db->where('estado',0);
            $res = $this->db->get();
            if(count($res->result_array()) === 0){
                return null;
            }
            $r = $res->row_array();
            return $r['id_banda'];
        }else{
            return null;
        }
    }
    
    function emailNoRegistrado($email,$region){
        return ($this->validEmailEnUso($email,$region));
        /*
        $this->db->select('id_usuarios')->from('usuarios')->where('email',$email);
        $res = $this->db->get();
        $e = $res->row_array();
        if(empty($e['id_usuarios'])){
            return true;
        }else{
            return false;
        }
        */
    }
    
    function getNombreBandaById($id){
        $this->db->select('nombre')->from('bandas')->where('id_bandas',$id);
        $res = $this->db->get();
        if(count($res->result_array()) === 0){
            return null;
        }
        $r = $res->row_array();
        return $r['nombre'];
    }
    
    function getIdBandaByLiderId($id){
        $this->db->select('id_bandas')
            ->from('bandas b')
            ->where('lider',$id);
        $q = $this->db->get();
        $arr = $q->row_array();
        return $arr['id_bandas'];
    }
    
    function getProvinciasByRegion($region){
        $this->db->select('provincias')->from('regiones');
        $this->db->where('id_regiones',$region);
        $this->db->order_by("region", "asc");
        
        $res = $this->db->get();
        return $res->row_array();
    }
    
    /**
     * @param Array $region provincias a descartar
     * @return array provincias
     * */
    function getProvincias($provincias = 0){
        $provincias = explode(',', $provincias);
        if($provincias != 0){
            foreach($provincias as $provincia){
                $this->db->where('id_provincias != ', $provincia);    
            }
        }
        $res = $this->db->get('provincias');
        $provs = $res->result_array();
        
        return $provs;
    }
    
    function getProvinciasById($id){
        $this->db->select('provincia')->from('provincias')->where('id_provincias',$id);
        $res = $this->db->get();
        return $res->row_array();
    }
    
    function getLocalidadesByRegion($provincia){
        $this->db->order_by("localidad", "ASC");
        $this->db->from('localidades');
        $this->db->where('provincia',$provincia);
        
        $res = $this->db->get();
        return $res->result_array();
    }
    
    function getBandaByHash($hash){
        $res = $this->db->get_where('bandas_invitaciones',array('hash'=>$hash));
        $ar = $res->row_array();
        return $ar[0]['id_banda'];
    }
    
    function getLideres($region){
        $this->db->select('dni');
        $this->db->from('usuarios u');
        $this->db->join('bandas b','u.id_usuarios = b.lider');
        $this->db->where('b.region',$region);
        $res = $this->db->get();
        return $res->result_array();
    }
    
    function getIntegrantes($region){
        $this->db->select('dni');
        $this->db->from('usuarios u');
        $this->db->join('bandas_integrantes b','u.id_usuarios = b.id_usuario');
        $this->db->join('bandas ba','ba.id_bandas = b.id_banda');
        $this->db->where('ba.region',$region);
        $res = $this->db->get();

        return $res->result_array();
    }
    
    function setUsuario($datos){
        $fbiud = isset($datos['fbuid']) ? $datos['fbuid'] : null;
        $contrasena = isset($datos['contrasena']) ? md5($datos['contrasena']) : "";
        $insert = array(
            'fbuid'=> $fbiud,
            'nombre'=> $datos['nombre'],
            'apellido'=> $datos['apellido'],
            'dni'=> $datos['dni'],
            'fecha_nacimiento'=> $datos['fecha_nacimiento'],
            'provincia'=> $datos['provincia'],
            'localidad'=> $datos['localidad'],
            'email'=> $datos['email'],
            'contrasena'=> $contrasena,
            'caracteristica_telefono'=> $datos['caracteristicaTelefono'],
            'telefono'=> $datos['telefono'],
            'caracteristica_celular'=> $datos['caracteristicaCelular'],
            'celular'=> $datos['celular'],
            'otros_medios' => $datos['otrosMedios']
        );
        $q = $this->db->insert('usuarios',$insert);
        if($q){
            return $this->db->insert_id();
        }
        return false;
    }
    
    function modifUsuario($datos){
        $fbiud = isset($datos['fbuid']) ? $datos['fbuid'] : null;
        $contrasena = isset($datos['contrasena']) ? md5($datos['contrasena']) : "";
        $insert = array(
            'fbuid'=> $fbiud,
            'nombre'=> $datos['nombre'],
            'apellido'=> $datos['apellido'],
            'dni'=> $datos['dni'],
            'fecha_nacimiento'=> $datos['fecha_nacimiento'],
            'provincia'=> $datos['provincia'],
            'localidad'=> $datos['localidad'],
            'email'=> $datos['email'],
            'contrasena'=> $contrasena,
            'caracteristica_telefono'=> $datos['caracteristicaTelefono'],
            'telefono'=> $datos['telefono'],
            'caracteristica_celular'=> $datos['caracteristicaCelular'],
            'celular'=> $datos['celular'],
            'otros_medios' => $datos['otrosMedios']
        );
        $this->db->where('id_usuarios',$datos['id_usuarios']);
        return $this->db->update('usuarios',$insert);
    }
    
    function delUsuario($usuarioId){
        return $this->db->delete('usuarios',array('id_usuarios'=>$usuarioId));
    }
    
    function solicitarCambio($email){
        $this->db->where('email',$email);
        $data = array(
            'recupero_contrasenia' => 1
        );
        return $this->db->update('usuarios',$data);
    }
    
    function permisoDeCambio($email){
        $res = $this->db->get_where('usuarios',array('email'=>$email,'recupero_contrasenia'=>1));
        $ar = $res->row_array();
        if(!empty($ar)){
            return $ar;
        }else{
            return null;
        }
    }
    
    function modificarContrasenia($datos){
        $this->db->where('email',$datos['email']);
        $data = array(
            'contrasena' => md5($datos['contrasenia']),
            'recupero_contrasenia' => 0
        );
        return $this->db->update('usuarios',$data);
    }
    
    function getUsuario($dni){
        $res = $this->db->get_where('usuarios',array('dni'=>$dni));
        $ar = $res->row_array();
        if(!empty($ar)){
            return $ar;
        }else{
            return null;
        }
    }
    
    function setBandas($datos){
        $insert = array(
            'nombre' => $datos['nombre'],
            'estilos' => $datos['estilos'],
            'otros_estilos' => $datos['otros_estilos'],
            'resena' => $datos['resena'],
            'imagen_promocion' => $datos['imagen_promocion'],
            'demo_youtube' => $datos['demo_youtube'],
            'demo_archivo' => $datos['demo_archivo'],
            'region' => $datos['region'],
            'lider' => $datos['lider'],
            'estado' => $datos['estado']
        );
        $q = $this->db->insert('bandas',$insert);
        if($q){
            return $this->db->insert_id();
        }
        return false;
    }
    
    function setIntegranteBanda($datos){
        $insert = array(
            'id_banda' => $datos['banda'],
            'id_usuario' => $datos['usuario'],
            'instrumento' => $datos['instrumento']
        );
        $q = $this->db->insert('bandas_integrantes',$insert);
        if($q){
            return $this->db->insert_id();
        }
        return false;        
    }
    
    function setInvitaciones($datos){
        $insert = array(
            'id_banda' => $datos['id_banda'],
            'hash' => $datos['hash'],
            'origen' => $datos['origen'],
            'destino' => $datos['destino']
        );
        $q = $this->db->insert('bandas_invitaciones',$insert);
        if($q){
            return $this->db->insert_id();
        }
        return false;
    }
    
    function updateInvitacionesFb($idBanda, $ids = null){
        $ids_fb = explode(',',$ids);
        
        $data = array('id_banda'=>$idBanda);
        $this->db->where('id_banda',0);
        
        $this->db->where('estado',0);
        
        foreach($ids_fb as $id){
            $this->db->where('destino', $id);
        }
        
        $this->db->update('bandas_invitaciones',$data);
    }
    
    function reenvioInvitacionesEm($datos){
        $update = array(
            'hash' => $datos['hash']
        );
        $this->db->where('id_bandas_invitaciones',$datos['id']);
        $q = $this->db->update('bandas_invitaciones',$update);
        if($q){
            return true;
        }else{
            return false;
        }
    }
    
    function invitacionAceptada($datos){
        $hash = $datos[0]."_".$datos[1];
        $data = array('estado' => 1);
        $this->db->where('hash',$hash);
        $this->db->update('bandas_invitaciones',$data);
    }
    
    function login($data){
        $contra = md5($data['contrasenia']);
        $this->db->select('u.id_usuarios as id');
        $this->db->from('usuarios u');
        //$this->db->join('bandas b','u.id_usuarios = b.lider');
        $this->db->where('u.email',$data['email']);
        $this->db->where('u.contrasena',$contra);
        $q = $this->db->get();
        return $q->row_array();
    }
    
    function getInvitadosByIdBanda($idbanda){
        $this->db->select('*')->from('bandas_invitaciones')
        /*->join('usuarios','usuarios.id_usuarios=bandas_invitaciones.')*/
        ->where('id_banda',$idbanda);
        $res = $this->db->get();
        return $res->result_array();
    }
    
    function getIntegrantesByIdBanda($idbanda){
        $this->db->select('u.*,bi.id_bandas_integrantes, bi.instrumento')->from('bandas_integrantes bi')
        ->join('usuarios u','u.id_usuarios = bi.id_usuario')
        ->where('id_banda',$idbanda);
        $res = $this->db->get();
        return $res->result_array();
    }

    function getAllVideosBandas(){
        
		$this->db->select('id_bandas')
                    ->select('nombre')
					->select('demo_youtube')
                    ->select('demo_archivo')
					->select('archivo_procesado')
                    ->from('bandas');
        $res = $this->db->get();
        return $res->result_array();
		/*
		$data = array(
            'demo_archivo' => '70c3c7f810138c441bb4a99f1c7d4a1a.flv',
            'archivo_procesado' => '0'
        );
        $this->db->where('id_bandas','4');
        return $this->db->update('bandas',$data);
		*/
	}
	
	function getVideosSinProcesar(){
        $this->db->select('id_bandas')
                    ->select('nombre')
                    ->select('demo_archivo')
                    ->from('bandas')
                    ->where('archivo_procesado',0)
					->where('demo_archivo is not null');
        $res = $this->db->get();
        return $res->result_array();
    }
    
    function setVideosProcesado($idBanda, $linkYoutube){
        $data = array(
            'demo_youtube' => $linkYoutube,
            'archivo_procesado' => 1
        );
        $this->db->where('id_bandas',$idBanda);
        return $this->db->update('bandas',$data);
    }
	
    function procesandoVideo($idBanda){
        $data = array(
            'archivo_procesado' => 2
        );
        $this->db->where('id_bandas',$idBanda);
        return $this->db->update('bandas',$data);        
    }

}
?>