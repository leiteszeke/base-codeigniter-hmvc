<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_controller extends CI_Controller {
	private $data = array();

	function __construct() {
		parent::__construct();

		$this->data['base_url'] = $this->config->item('base_url');
	}

	/*
	|------------------------------------------------------------------------------
	| ADMINISTRADORES
	|------------------------------------------------------------------------------
	*/

	public function agregarAdministrador(){
		if($this->input->post()){
			$nombre   = $this->input->post('nombre');
			$apellido = $this->input->post('apellido');
			$usuario  = $this->input->post('usuario');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$admin = $this->Administradores_model->agregarAdministrador($nombre, $apellido, $usuario, $email, $password);

			$resp = array("error" => $admin["error"], "message" => $admin["message"], "data" => $admin["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function editarAdministrador(){
		if($this->input->post()){
			$id       = $this->input->post('id');
			$nombre   = $this->input->post('nombre');
			$apellido = $this->input->post('apellido');
			$usuario  = $this->input->post('usuario');
			$email    = $this->input->post('email');
			$password = $this->input->post('password');

			$admin = $this->Administradores_model->editarAdministrador($id, $nombre, $apellido, $usuario, $email, $password);

			$resp = array("error" => $admin["error"], "message" => $admin["message"], "data" => $admin["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function borrarAdministrador(){
		if($this->input->post()){
			$id    = $this->input->post('id');
			$admin = $this->Administradores_model->borrarAdministrador($id);

			$resp = array("error" => $admin["error"], "message" => $admin["message"], "data" => $admin["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	/*
	|------------------------------------------------------------------------------
	| SECCIONES
	|------------------------------------------------------------------------------
	*/
	public function editarSeccion(){
		if($this->input->post()){
			$id     = $this->input->post('id');
			$nombre = $this->input->post('nombre');
			$url    = $this->input->post('url');
			$home   = $this->input->post('home')==='true' ? 1 : 0;
			$menu   = $this->input->post('menu')==='true' ? 1 : 0;
			$icono  = $this->input->post('icono');
			$orden  = $this->input->post('orden');

			$seccion = $this->Secciones_model->editarSeccion($id, $nombre, $url, $home, $menu, $icono, $orden);

			$resp = array("error" => $seccion["error"], "message" => $seccion["message"], "data" => $seccion["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function borrarSeccion(){
		if($this->input->post()){
			$seccion = $this->input->post("seccion");
			$borrar  = $this->Secciones_model->borrarSeccion($seccion);

			$resp = array("error" => $borrar["error"], "message" => $borrar["message"], "data" => $borrar["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function agregarSeccion(){
		if($this->input->post()){
			$nombre  = $this->input->post("nombre");
			$url     = $this->input->post("url");
			$menu    = $this->input->post("menu")==='true'  ? 1 : 0;
			$home    = $this->input->post("home")==='true'  ? 1 : 0;
			$icono   = $this->input->post("icono");
			$orden   = $this->input->post("orden");
			$seccion = $this->Secciones_model->agregarSeccion($nombre, $url, $menu, $home, $icono, $orden);

			$resp = array("error" => $seccion["error"], "message" => $seccion["message"], "data" => $seccion["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	/*
	|------------------------------------------------------------------------------
	| OPCIONES
	|------------------------------------------------------------------------------
	*/
	public function agregarOpcion(){
		if($this->input->post()){
			$nombre  = $this->input->post("nombre");
			$seccion = $this->input->post("seccion");
			$orden   = $this->input->post("orden");
			$url     = $this->input->post("url");
			$menu    = $this->input->post("menu")==='true' ? 1 : 0;
			$icono   = $this->input->post("icono");
			$seccion = $this->Opciones_model->agregarOpcion($nombre, $seccion, $orden, $url, $menu, $icono);

			$resp = array("error" => $seccion["error"], "message" => $seccion["message"], "data" => $seccion["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function editarOpcion(){
		if($this->input->post()){
			$id     = $this->input->post('id');
			$nombre = $this->input->post('nombre');
			$url    = $this->input->post('url');
			$menu   = $this->input->post('menu')==='true' ? 1 : 0;
			$seccion= $this->input->post('seccion');
			$icono  = $this->input->post('icono');
			$orden  = $this->input->post('orden');

			$seccion = $this->Opciones_model->editarOpcion($id, $nombre, $url, $seccion, $menu, $icono, $orden);

			$resp = array("error" => $seccion["error"], "message" => $seccion["message"], "data" => $seccion["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function borrarOpcion(){
		if($this->input->post()){
			$opcion = $this->input->post("opcion");
			$borrar  = $this->Opciones_model->borrarOpcion($seccion);

			$resp = array("error" => $borrar["error"], "message" => $borrar["message"], "data" => $borrar["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function getTotalOpciones(){
		if($this->input->post()){
			$seccion = $this->input->post('seccion');
			
			$tot = $this->Opciones_model->getTotalOpciones($seccion);

			$resp = array("error" => false, "message" => "Total", "data" => array('tot'=>$tot));
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	/*
	|------------------------------------------------------------------------------
	| PERMISOS
	|------------------------------------------------------------------------------
	*/
	public function eliminarPermiso(){
		if($this->input->post()){
			$permiso  = $this->input->post("permiso");
			$tipo     = $this->input->post("tipo");
			$permisos = $this->Permisos_model->eliminarPermiso($permiso, $tipo);

			$resp = array("error" => $permisos["error"], "message" => $permisos["message"], "data" => $permisos["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function getOpcionesBySeccion(){
		if($this->input->post()){
			$seccion  = $this->input->post("seccion");
			$opciones = $this->Opciones_model->getOpcionesBySeccion($seccion);

			$resp = array("error" => $opciones["error"], "message" => $opciones["message"], "data" => $opciones["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function getPermisosByUser(){
		if($this->input->post()){
			$id_user = $this->input->post("id");
			$type    = $this->input->post("type");
			$permisos = $this->Permisos_model->getPermisosByUser($id_user, $type);

			$resp = array("error" => $permisos["error"], "message" => $permisos["message"], "data" => $permisos["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	public function asignarPermisos(){
		if($this->input->post()){
			$usuario  = $this->input->post("user");
			$tipo     = $this->input->post("tipo");
			$permisos = $this->input->post("valor");
			$asignar  = $this->Permisos_model->asignarPermisos($usuario, $tipo, $permisos);

			$resp = array("error" => $asignar["error"], "message" => $asignar["message"], "data" => $asignar["data"]);
		}else{
			$resp = array("error" => true, "message" => "Acceso Denegado.", "data" => array());
		}

		echo json_encode($resp);
	}

	/*
	|------------------------------------------------------------------------------
	| ADMIN OLD
	|------------------------------------------------------------------------------
	*/
	function get_permisos_secciones_sin_asignar() {
		if($this->input->post()) {
			$user = $this->input->post('usuario');
			$this->_data['secciones'] = $this->Secciones_model->listarNoAsignadasAPermisosByUsuario($user);

			echo json_encode($this->_data['secciones']);
		}else {
			echo json_encode(array());
		}
	}

	function get_permisos_opciones_sin_asignar() {
		if($this->input->post()) {
			$user = $this->input->post('usuario');
			$this->_data['opciones'] = $this->Opciones_model->listarNoAsignadasAPermisosByUsuario($user);

			echo json_encode($this->_data['opciones']);
		}else {
			echo json_encode(array());
		}
	}

	function template() {
		$nombre                = $this->uri->segment(3);

		$camposDeLaBaseDeDatos = $this->Secciones_model->getTypeField($nombre);

		$camposPrivados        = $campoInicializador    = $campoConstructor      = "";
		foreach($camposDeLaBaseDeDatos as $key => $value) {
			$camposPrivados .= 'private $_'.$value['name'].';'.PHP_EOL;
			$value['default'] = $value['default'] == "" ? "\"\"":$value['default'];
			$campoInicializador .= "$"."this->_".$value['name']."= ".$value['default'].";".PHP_EOL;
			$campoConstructor .= "'".$value['name']."' => "."$"."this->_".$value['name'].",".PHP_EOL;
		}
		$campoConstructor = substr($campoConstructor, 0, - 3);

		/*CONTROLLLERS*/
		$var              = $_SERVER['DOCUMENT_ROOT'].'/sur54/templates/controllers/controller_template.php';
		$file             = file_get_contents($var);
		$file             = str_replace('{nombreControlador}',ucfirst($nombre), $file);
		$file             = str_replace('{nombreTabla}',$nombre, $file);
		$file             = str_replace('{nombreControladorFriendly}',unfriendlizar($nombre,'_',false), $file);

		echo $fichero = $_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/controllers/'.ucfirst($nombre).'.php';
		$fp      = fopen($fichero, "w+");
		fwrite($fp,$file);
		fclose($fp);

		/*MODELS*/
		$var     = $_SERVER['DOCUMENT_ROOT'].'/sur54/templates/models/model_template.php';
		$file    = file_get_contents($var);
		$file    = str_replace('{nombreControlador}',ucfirst($nombre), $file);
		$file    = str_replace('{nombreTabla}',$nombre, $file);

		$file    = str_replace('{camposPrivados}',$camposPrivados, $file);
		$file    = str_replace('{campoInicializador}',$campoInicializador, $file);
		$file    = str_replace('{campoConstructor}',$campoConstructor, $file);

		$fichero = $_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/models/'.ucfirst($nombre).'_model.php';
		$fp      = fopen($fichero, "w+");
		fwrite($fp,$file);
		fclose($fp);

		/*VIEWS - crea la carpeta si no existe*/
		if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/views/'.$nombre.'/')) {
			mkdir($_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/views/'.$nombre.'/', 0777, true);
		}

		/*VIEWS - list*/
		$var = $_SERVER['DOCUMENT_ROOT'].'/sur54/templates/views/view_template.php';
		$file= file_get_contents($var);
		$file= str_replace('{nombreControlador}',ucfirst($nombre), $file);
		$file= str_replace('{nombreTabla}',$nombre, $file);
		$file= str_replace('{titulo}',unfriendlizar($nombre, "_", true), $file);
		$fichero = $_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/views/'.$nombre.'/'.$nombre.'_view.php';
		$fp      = fopen($fichero, "w+");
		fwrite($fp,$file);
		fclose($fp);

		/*VIEWS - agregar/modificar*/
		$var     = $_SERVER['DOCUMENT_ROOT'].'/sur54/templates/views/agregar_view_template.php';
		$file    = file_get_contents($var);
		$file    = str_replace('{nombreControlador}',ucfirst($nombre), $file);
		$file    = str_replace('{nombreTabla}',$nombre, $file);
		$file    = str_replace('{nombreTitulo}',unfriendlizar($nombre,'_',true), $file);
		$file = str_replace('{nombreTitulounfriendlizar}',unfriendlizar($nombre,'_',false), $file);

		$fichero = $_SERVER['DOCUMENT_ROOT'] . '/sur54/application/modules/admin/views/'.$nombre.'/'.$nombre.'_agregar_view.php';
		$fp      = fopen($fichero, "w+");
		fwrite($fp,$file);
		fclose($fp);

	}
}

// END OF FILE
?>