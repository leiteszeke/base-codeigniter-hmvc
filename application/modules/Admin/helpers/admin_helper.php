<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function common() {
	$ci    =& get_instance();
	$_data = array();

	// data basica
	$_data['base_url']  = $ci->config->item('base_url');
	$_data['base_path'] = $ci->config->item('base_path');
	$_data['protocolo'] = $ci->config->item('protocolo');
	$_data['site_name'] = $ci->config->item('site_name');

	// configuraciones generales
	$_data['titulo'] = $_data['site_name'];

	// partes de la pagina
	$_data['head']        = $ci->parser->parse("Admin/common/head_inc", $_data, true);
	$_data['header']      = $ci->parser->parse("Admin/common/header_inc", $_data, true);
	$_data['scripts']     = $ci->parser->parse("Admin/common/scripts_inc", $_data, true);
	$_data['footer']      = $ci->parser->parse("Admin/common/footer_inc", $_data, true);

	foreach(get_breadcrumbs() as $k=>$v) {
		$_data[$k] = $v;
	}

	$_data['error']   = array();
	$_data['mensaje'] = array();
	$_data['redireccionar'] = array();

	$_usuarioLogueado      = $ci->session->userdata($ci->config->item('encryption_key'));
	$secciones_con_permiso = $ci->Admin_model->getSeccionesByUser($_usuarioLogueado['id']);

	foreach($secciones_con_permiso as $key=>$value) {
		$secciones_con_permiso[$key]['opciones_con_permiso'] = $ci->Admin_model->getOpcionesSeccionByUser($value['id_admin_secciones'], $_usuarioLogueado['id']);
		foreach($secciones_con_permiso[$key]['opciones_con_permiso'] as $ky=>$op) {
			
			$secciones_con_permiso[$key]['opciones_con_permiso'][$ky]['base_url'] = $_data['base_url'];
			$secciones_con_permiso[$key]['opciones_con_permiso'][$ky]['seccion']  = $secciones_con_permiso[$key]['friendly_url'];

		}
	}

	$_data['menu_secc_opc'] = $secciones_con_permiso;
	$_data['opciones_con_permiso'] = $ci->Admin_model->getOpcionesIdConPermisosByUser($_usuarioLogueado['id']);

	return $_data;
}

function is_logged_in() {
	$ci           = & get_instance();
	$is_logged_in = $ci->session->userdata($ci->config->item('encryption_key').'_is_logged_in');

	if(is_null($is_logged_in) || $is_logged_in != true) {
		return false;
	}

	return true;
}
/**/
function get_breadcrumbs() {
	$ci    =& get_instance();
	$_data = array();

	$_data['base_url']  = $ci->config->item('base_url');
	$_data['seccion']   = $seccion_menu_link   = $seccion_menu   = $ci->uri->segment(2);
	$_data['operacion'] = $operacion_menu_link = $operacion_menu = $ci->uri->segment(3);
	$_data['valor']     = $valor_menu_link     = $valor_menu     = $ci->uri->segment(4);

	// Capitalizado de Secciones
	$seccion_menu   = str_replace("-", " ", $seccion_menu);
	$seccion_menu   = ucfirst($seccion_menu);
	$operacion_menu = str_replace("-", " ", $operacion_menu);
	$operacion_menu = ucfirst($operacion_menu);

	$seccion_menu_con_lnk   = "<a href=\"{$ci->config->item('base_url')}admin/{$seccion_menu_link}\">{$seccion_menu}</a>";
	$seccion_menu_sin_lnk   = $seccion_menu;

	$lnk                    = ($ci->uri->segment(3) == "editar") ? "listar" : $ci->uri->segment(3);
	$operacion_menu_con_lnk = "<a href=\"{$ci->config->item('base_url')}admin/{$seccion_menu_link}/{$lnk}\">{$operacion_menu}</a>";
	$operacion_menu_sin_lnk = $operacion_menu;


	if(isset($seccion_menu)) {
		if(isset($operacion_menu)) {
			$_data['seccion_menu']   = $seccion_menu_con_lnk;
			$_data['operacion_menu'] = $operacion_menu_sin_lnk;

			if(isset($valor_menu)) {
				$_data['valor_menu']     = $valor_menu;
				$_data['operacion_menu'] = $operacion_menu_con_lnk;
			}else {
				$_data['valor_menu'] = "";
			}

		}else {
			$_data['valor_menu']     = "";
			$_data['seccion_menu']   = $seccion_menu_sin_lnk;
			$_data['operacion_menu'] = "";
		}
	}else {
		$_data['valor_menu']     = "";
		$_data['seccion_menu']   = "";
		$_data['operacion_menu'] = "";
	}

	// Agregar Numero de Pagina al Breadcrumb
	$ll = $ci->config->item("listados");
	if(!empty($ll)){
		if(in_array($ci->uri->segment(3), $ci->config->item("listados"))){
			if($ci->uri->segment(5)){
				$_data["valor_menu"] = $ci->uri->segment(5);	
			}else{
				if(is_numeric($ci->uri->segment(4))){
					$_data["valor_menu"] = $ci->uri->segment(4);	
				}else{
					$_data["valor_menu"] = "";
				}
			}
		}else{
			$_data["valor_menu"] = "";
		}
	}else{
		$_data["valor_menu"] = "";
	}

	$_data['si_seccion'] = $_data['si_operacion'] = $_data['si_valor'] = array();

	if($_data['seccion_menu'] != '') { $_data['si_seccion'][0]['seccion_menu'] = $_data['seccion_menu']; }
	if($_data['operacion_menu'] != '') { $_data['si_operacion'][0]['operacion_menu'] = $_data['operacion_menu']; }
	if($_data['valor_menu'] != '') { $_data['si_valor'][0]['valor_menu'] = $_data['valor_menu']; }

	$_data['breadcrumb'] = $ci->parser->parse("Admin/common/breadcrumb_inc", $_data, true);

	return $_data;
}