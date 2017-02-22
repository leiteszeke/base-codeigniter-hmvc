<?php
class PaginadorHelper {
	private $ci;

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function generarPaginador($nPagina, $totalPaginas, $offset_left = 1, $offset_right = 1, $url = false){
		$paginador = array();

		$segments = $this->ci->uri->segments;

		if($url != false){
			if($url[0] != "/"){
				$url = "/" . $url;
			}
		}

		foreach($segments as $key => $value){
			if(is_numeric($segments[$key]) || ("/" . $segments[$key]) == $url){
				unset($segments[$key]);
			}
		}

		$segments = implode("/", $segments);
		
		if($totalPaginas > 1){
			$linksPaginas = array(
				"base_url"     => $this->ci->config->item("base_url"). $segments,
				"url"          => $url,
				"ultimaPagina" => $totalPaginas
			);

			$mostrarPrimera = ($nPagina > ($offset_left + 1)) ? array($linksPaginas) : array(); 
			$mostrarUltima  = ($totalPaginas > ($offset_right + 1) && $nPagina < ($totalPaginas - $offset_right)) ? array($linksPaginas) : array(); 

			$paginador = array(
				array(
					"base_url"       => $this->ci->config->item("base_url"). $segments, 
					"ultimaPagina"   => $totalPaginas, 
					"mostrarPrimera" => $mostrarPrimera, 
					"mostrarUltima"  => $mostrarUltima
				)
			);

			$pag_inicio = (($nPagina - $offset_left) > 0) ? $nPagina - $offset_left : 1;
			$pag_final  = (($nPagina + $offset_right) <= $totalPaginas) ? $nPagina + $offset_right : $totalPaginas;
			$paginas    = array();

			for($i = $pag_inicio; $i <= $pag_final && $i <= $totalPaginas; $i++){
				$activo = ($i == $nPagina) ? "active" : "";

				$pagina = array(
					"base_url" => $this->ci->config->item("base_url") . $segments, 
					"n_pagina" => $i, 
					"active"   => $activo,
					"url"      => $url
				);

				$paginas[] = $pagina;

			}
			
			$paginador[0]["paginas"] = $paginas;
		}

		$paginador = $this->generarHtmlPaginador($paginador);

		return $paginador;
	}

	private function generarHtmlPaginador($paginador){
		$html = "";

		if(!empty($paginador)){
			$mPrimera = $paginador[0]["mostrarPrimera"];
			$mUltima  = $paginador[0]["mostrarUltima"];
			$ultPag   = $paginador[0]["ultimaPagina"];
			$base_url = $paginador[0]["base_url"];
			$paginas  = $paginador[0]["paginas"];

			$html .= '<nav class="nav-pagination">';
				$html .= '<ul class="pagination">';
					if(!empty($mPrimera)){
						$html .= '<li><a title="Primera" class="next" href="'.$base_url.'/1" aria-label="Previous">&laquo;</a></li>';
					}	

					foreach($paginas as $key => $value){
						$pagina = $paginas[$key];
						$html .= '<li class="'.$pagina["active"].'">';
						$html .= '<a href="' . $base_url . $pagina["url"] . '/'.$pagina["n_pagina"].'">'.$pagina["n_pagina"].'</a>';
						$html .= '</li>';
					}

					if(!empty($mUltima)){
						$html .= '<li><a title="&Uacute;ltima" class="next" href="'.$base_url.'/'.$ultPag.'" aria-label="Next">&raquo;</a></li>';
					}
				$html .= '</ul>';
			$html .= '</nav>';
		}

		return $html;
	}
}