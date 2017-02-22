<?php
if( ! defined('BASEPATH')) exit('No direct script access allowed');
function camposAuditoria() {
	return array('created','updated','deleted','habilitado');
}

/**
* Obtiene el dispositivo desde el cual se accede
* @return string (tablet, mobile, desktop)
*/
function getDispositivo() {
	$tablet_browser = 0;
	$mobile_browser = 0;

	if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$tablet_browser++;
	}

	if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
		$mobile_browser++;
	}

	if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
		$mobile_browser++;
	}

	$mobile_ua     = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
	$mobile_agents = array(
		'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
		'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
		'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
		'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
		'newt','noki','palm','pana','pant','phil','play','port','prox',
		'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
		'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
		'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
		'wapr','webc','winw','winw','xda ','xda-');

	if(in_array($mobile_ua,$mobile_agents)) {
		$mobile_browser++;
	}

	if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
		$mobile_browser++;
		//Check for tablets on opera mini alternative headers
		$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
		if(preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
			$tablet_browser++;
		}
	}

	if($tablet_browser > 0) {
		return "tablet";
	}elseif($mobile_browser > 0) {
		return "mobile";
	}
	else {
		return "desktop";
	}
}

/**
* Valida si el email ingresado es correcto
* @param  string $email
* @return boolean
*/
function validaEmail($email) {
	if(empty($email)) {
		return false;
	}
	return (preg_match('/^([a-zA-Z0-9\._-]+)\@([a-zA-Z0-9\.-]+)\.([a-zA-Z]{2,4})$/i', $email) == 1);
}
/**
* Valida si email está en uso
* @param string $email
* @return boolean
*/
function validaEmailEnUso($email) {
	$ci =& get_instance();
	return $ci->system_model->validaEmailEnUso($email);
}
function verificarURLYoutube($url) {
	/*
	preg_match('#^(?:https?://)?(?:www\.)?(?:youtube\.com(?:/embed/|/v/|/watch\?v=))([\w-]{11})(?:.+)?$#x', $url, $r);

	return (isset($r[1]) && !empty($r[1])) ? $r[1] : false;
	*/

	$match = preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $url, $matches);

	return ((int) $match > 0) ? $matches[0] : false;

}


function friendlizarArchivo($nombre) {
	$microtime      = microtime();
	$ext            = end(explode(".", $nombre));
	$archivo_nombre = str_replace(".".$ext, "", $nombre);
	$archivo_nombre = friendlizar($archivo_nombre);
	$archivo_nombre = $microtime . $archivo_nombre . "." . $ext;
	return $archivo_nombre;
}

function friendlizar($text, $separator = 'dash') {
	$text          = utf8_decode($text);
	$replace_array = array(
		'Á'=> 'A',
		'É'=> 'E',
		'Í'=> 'I',
		'Ó'=> 'O',
		'Ú'=> 'U',
		'á'=> 'a',
		'é'=> 'e',
		'í'=> 'i',
		'ó'=> 'o',
		'ú'=> 'u',
		'Ñ'=> 'N',
		'ñ'=> 'n',
		'Ü'=> 'U',
		'ü'=> 'u'
	);
	$str = strtr($text,$replace_array);
	if($separator == 'dash') {
		$search = '_';
		$replace= '-';
	}else {
		$search = '-';
		$replace= '_';
	}
	$trans = array(
		$search                => $replace,
		"\s+"                  => $replace,
		"[^a-z0-9".$replace."]"=> '',
		$replace."+"           => $replace,
		$replace."$"           => '',
		"^".$replace           => ''
	);
	$str = strip_tags(strtolower($str));
	foreach($trans as $key => $val) {
		$str = preg_replace("#".$key."#", $val, $str);
	}
	return trim(stripslashes($str));
}

/**
* Convierte una cadena friendly a formato de nombre de clase o nombre de título de tabla
* @param $text Texto en cuestion
* @param $separador Cómo está compuesto, puede ser "_","-",etc.
* @param $titulo Si es un titulo de tabla o nombre de clase.
* */
function unfriendlizar($text,$separador,$titulo = false) {
	$texto      = explode($separador,$text);
	$nuevotexto = $titulo ? ucfirst($texto[0]) : $texto[0];
	for($i = 1; $i < count($texto); $i++) {
		$nuevotexto .= $titulo ? " " : "";
		$nuevotexto .= ucfirst($texto[$i]);
	}
	return $nuevotexto;
}
/**
* Corta un texto a la longitud especificada
* @param  string  $text         Texto original
* @param  integer $length       Longitud máxima
* @param  string  $ending       Caracteres a agregar al final
* @param  boolean $exact        Si no hay que cortar las palabras
* @param  boolean $considerHtml Se se contabilizan las entidades HTML
* @return string
*/
function truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
	//echo "original: " . $text . " < br />";
	if($considerHtml) {
		// if the plain text is shorter than the maximum length, return the whole text
		if(strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
		// splits all html - tags to scanable lines
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length = strlen($ending);
		$open_tags    = array();
		$truncate = '';
		foreach($lines as $line_matchings) {
			// if there is any html - tag in this line, handle it and add it (uncounted) to the output
			if(!empty($line_matchings[1])) {
				// if it's an "empty element" with or without xhtml - conform closing slash
				if(preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
					// do nothing
					// if tag is a closing tag
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
					// delete tag from $open_tags list
					$pos = array_search($tag_matchings[1], $open_tags);
					if($pos !== false) {
						unset($open_tags[$pos]);
					}
					// if tag is an opening tag
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
					// add tag to the beginning of $open_tags list
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				// add html - tag to $truncate'd text
				$truncate .= $line_matchings[1];
			}
			// calculate the length of the plain text part of the line; handle entities as one character
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
			if($total_length + $content_length > $length) {
				// the number of characters which are left
				$left            = $length - $total_length;
				$entities_length = 0;
				// search for html entities
				if(preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					// calculate the real length of all entities in the legal range
					foreach($entities[0] as $entity) {
						if($entity[1] + 1 - $entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							// no more characters left
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left + $entities_length);
				// maximum lenght is reached, so get off the loop
				break;
			} else {
				$truncate .= $line_matchings[2];
				$total_length += $content_length;
			}
			// if the maximum length is reached, get off the loop
			if($total_length >= $length) {
				break;
			}
		}
	} else {
		if(strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	// if the words shouldn't be cut in the middle...
	if(!$exact) {
		// ...search the last occurance of a space...
		$spacepos = strrpos($truncate, ' ');
		if(isset($spacepos)) {
			// ...and cut the text in this position
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	// add the defined ending to the text
	$truncate .= $ending;
	if($considerHtml) {
		// close all unclosed html - tags
		foreach($open_tags as $tag) {
			$truncate .= '</' . $tag . '>';
		}
	}
	//echo "truncado: " . $truncate . " < hr />";
	return $truncate;
}
/**
* Obtiene los años entre 2 fechas
* Puede ser usado para calcular edad por ejemplo
* El orden de datos en el string de fechas debe ser: dia, mes y año, en ese orden.
* @param  string $fecha_fin    La mayor de las fechas. Para cálculo de edad correspondería a la fecha de hoy
* @param  string $fecha_inicio La menor de las fechas. Para cálculo de edad correspondería a la fecha de nacimiento
* @param  string $separador    Caracter que separa el día, mes y año
* @return int
*/
function calcular_anios($fecha_fin, $fecha_inicio, $separador = "/") {
	list($dia1, $mes1, $ano1) = explode($separador, $fecha_inicio);
	list($dia2, $mes2, $ano2) = explode($separador, $fecha_fin);
	$fecha1          = mktime(0, 0, 0, $mes1, $dia1, $ano1);
	$fecha2          = mktime(0, 0, 0, $mes2, $dia2, $ano2);
	$diferencia      = abs($fecha2 - $fecha1);
	(($diferencia % 4) == 0)? $d               = 365 : $d               = 366;
	$anos_diferencia = floor($diferencia / (60 * 60 * 24 * $d));
	return $anos_diferencia;
}
/**
* Convierte bytes a la medida requerida
* Utilizado para validación de tamaños
* @param  number $bytes bytes de entrada
* @param  string $caso  medida de salida
* @return number
*/
function convertir_bytes($bytes, $caso) {
	$kb = number_format($bytes / 1024, 2);
	$mb = number_format($kb / 1024, 2);
	$gb = number_format($mb / 1024, 2);
	switch($caso) {
		case "kb":
		$return = $kb;
		break;
		case "mb":
		$return = $mb;
		break;
		case "gb":
		$return = $gb;
		break;
		default:
		$return = $mb;
		break;
	}
	return $return;
}
/**
* Escala proporcionalmente una imágen JPG y la guarda en el servidor
* @param  [type]  $imagen    Imágen original
* @param  [type]  $destino   Imágen final escalada
* @param  [type]  $max_ancho Ancho máximo
* @param  [type]  $max_alto  Alto máximo
* @param  integer $calidad   Calidad
* @return void
*/
function escalar($imagen, $destino, $max_ancho, $max_alto, $calidad = 95) {
	//Ruta de la imagen original
	$rutaImagenOriginal = $imagen;
	//Creamos una variable imagen a partir de la imagen original
	$img_original       = imagecreatefromjpeg($rutaImagenOriginal);
	//Ancho y alto de la imagen original
	list($ancho, $alto) = getimagesize($rutaImagenOriginal);
	//Se calcula ancho y alto de la imagen final
	$x_ratio = $max_ancho / $ancho;
	$y_ratio = $max_alto / $alto;
	//Si el ancho y el alto de la imagen no superan los maximos,
	//ancho final y alto final son los que tiene actualmente
	if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ) {
		//Si ancho
		$ancho_final = $ancho;
		$alto_final  = $alto;
	}
	/*
	* si proporcion horizontal*alto mayor que el alto maximo,
	* alto final es alto por la proporcion horizontal
	* es decir, le quitamos al ancho, la misma proporcion que
	* le quitamos al alto
	*
	*/
	elseif(($x_ratio * $alto) < $max_alto) {
		$alto_final = ceil($x_ratio * $alto);
		$ancho_final= $max_ancho;
	}
	/*
	* Igual que antes pero a la inversa
	*/
	else {
		$ancho_final = ceil($y_ratio * $ancho);
		$alto_final  = $max_alto;
	}
	//Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final .
	$tmp = imagecreatetruecolor($ancho_final, $alto_final);
	//Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
	imagecopyresampled($tmp,$img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho,$alto);
	//Se destruye variable $img_original para liberar memoria
	imagedestroy($img_original);
	//Se crea la imagen final en el directorio indicado
	imagejpeg($tmp, $destino, $calidad);
}
/**
* Obtiene el último comienzo de semana a partir del día actual
* @return string
*/
function get_start_week() {
	$start_week = date("D");
	if($start_week == "Fri") {
		$start_week = date("Ymd")."000000";
	}else {
		$start_week = date("Ymd", strtotime("Last Friday"))."000000";
	}
	//$start_week = "20120220000000";
	return $start_week;
}
/**
* Obtiene el siguiente día en el que finaliza la semana a partir del día actual
* @return string
*/
function get_end_week() {
	$end_week = date("D");
	if($end_week == "Thu") {
		$end_week = date("Ymd")."000000";
	}else {
		$end_week = date("Ymd", strtotime("Next Thursday"))."235900";
	}
	//$end_week = "20500101000000";
	return $end_week;
}
/**
* Ordena un array multidimensional a partir de uno de sus nodos
* @param  array   $toOrderArray array origninal
* @param  string  $field        nodo a partir del cual se ordenará
* @param  boolean $inverse      ascendente o descendente
* @return array
*/
function orderMultiDimensionalArray ($toOrderArray, $field, $inverse = false) {
	$position = array();
	$newRow = array();
	foreach($toOrderArray as $key => $row) {
		$position[$key] = $row[$field];
		$newRow[$key] = $row;
	}
	if($inverse) {
		arsort($position);
	}
	else {
		asort($position);
	}
	$returnArray = array();
	foreach($position as $key => $pos) {
		$returnArray[] = $newRow[$key];
	}
	return $returnArray;
}
/**
* Para debug, muestra el parámetro en pantalla preformateado
* @param  mixed $el
* @return void
*/
function d($el) {
	echo "<pre>"; print_r($el); echo "</pre>";
}
function comboDias() {
	$dia = "";
	for($i = 1; $i <= 31; $i++) {
		$dia .= "<option value=\"$i\">".$i."</option>\n";
	}
	return $dia;
}
function comboMeses() {
	$meses = array('ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE');
	$mes = "";
	for($i = 1; $i <= 12; $i++) {
		$mes .= "<option value=\"$i\">".$meses[$i - 1]."</option>\n";
	}
	return $mes;
}
function comboAnios($desde, $hasta) {
	$anio = "";
	for($i = $hasta; $i >= $desde; $i--) {
		$anio .= "<option value=\"$i\">".$i."</option>\n";
	}
	return $anio;
}

/**
* SUBE UN ARCHIVO AL SERVIDOR REALIZANDO VAIDACIONES PREVIAS.
* @param array $file_name
* @param array $file_tmp_name
* @param string $directorio
* @return mixed
* */
function subirArchivo($file_name, $file_tmp_name, $new_filename, $directorio, $extensiones_permitidas) {
	$ci =& get_instance();

	$ext= strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

	if(in_array($ext, $extensiones_permitidas)) {
		$ruta_destino   = $ci->config->item('base_path').'data/upload/'.$directorio.'/';

		$nombre_archivo = $new_filename.'.'.$ext;

		if(! move_uploaded_file($file_tmp_name, $ruta_destino.$nombre_archivo) ) {
			return false;
		}else {
			//create_thumbail($nombre_archivo, $ext, $directorio, 100);
			return $nombre_archivo;
		}

	}else {
		log_message("info","Extensiones no permitidas");
		return false;
	}
}


/**
* Valida si un archivo es un video
* @return -2 si no hubo post
* @return -1 si el video no existe
* @return 0 si el video no tiene formato permitido
* @return tamanio del video convertido. =0 si no hay video, >0 si hay video
* */
function validVideoFile($archivoVideo) {
	$CI =& get_instance();
	if(isset($archivoVideo)) {
		$ext = strtolower(pathinfo($archivoVideo['name'], PATHINFO_EXTENSION));
		if(in_array($ext, $CI->config->item('ext_videos_permitidas'))) {

			header("Content-Type: text/html; charset=UTF-8");
			$rutaFFMPEG = $CI->config->item('base_path') . "ffmpeg-2.4.3-64bit-static/ffmpeg";

			$video      = $archivoVideo['tmp_name'];
			$output     = $CI->config->item('base_path').'data/upload/videos_tmp/'.$archivoVideo['name'];

			if(file_exists($video)) {
				$convertir = shell_exec($rutaFFMPEG." -i ".$video." -c:v copy -an -sn ".$output);
				$tamanio   = filesize($output);
				unlink($output);
				return $tamanio;
			}else {
				return - 1;
			}
		}else {
			return 0;
		}
	}else {
		return - 2;
	}
}

/**
* Generador de hash
* @return string hash
* */
function hashGenerator() {
	$pattern   = "[^A-Z0-9]";
	$aleatorio =
	substr(preg_replace($pattern, "", md5(rand())) .
		preg_replace($pattern, "", md5(rand())) .
		preg_replace($pattern, "", md5(rand())),
		0, 10);

	return md5($aleatorio);
}

function create_thumbail($nombre_archivo,$extension_archivo, $directorio, $desired_width) {
	$ci   =& get_instance();
	$dest = $ci->config->item('base_url').'data/upload/'.$directorio.'/'.$nombre_archivo;

	if($extension_archivo == "jpg") {
		$source_image = imagecreatefromjpeg($dest);
	}else {
		$source_image = imagecreatefrompng($dest);
	}

	$width          = imagesx($source_image);
	$height         = imagesy($source_image);

	$desired_height = floor($height * ($desired_width / $width));

	$virtual_image  = imagecreatetruecolor($desired_width, $desired_height);

	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	$dest           = $ci->config->item('base_path').'data/upload/'.$directorio.'/.thumbails/thumb_'.$nombre_archivo;
	imagejpeg($virtual_image, $dest);
}

function search_array_in_array($valor, $array) {
	foreach($array as $ind=>$arr) {
		foreach($arr as $key=>$value) {
			if($valor == $value) {
				return $ind;
			}
		}
	}
	return - 1;
}

function limpiarCaracteresEspeciales($text) {
	return (preg_replace('/[^ A-Za-z0-9_-ñÑ]/', '', $text));
	/*
	$text = utf8_decode($text);
	$replace_array = array(
	'Á' => 'A',
	'É' => 'E',
	'Í' => 'I',
	'Ó' => 'O',
	'Ú' => 'U',
	'á' => 'a',
	'é' => 'e',
	'í' => 'i',
	'ó' => 'o',
	'ú' => 'u',
	'Ñ' => 'N',
	'ñ' => 'n',
	'Ü' => 'U',
	'ü' => 'u',
	'º' => 'o',
	'\'' => '',
	'"' => '',
	'ö' => 'o',
	'ä' => 'a',
	'¿' => '&iquest;',
	'ª' => 'a',
	'°' => 'o',
	'´' => ' ',
	'¡' => '&iexcl;'
	);
	$str = strtr($text,$replace_array);
	return $str;
	*/
}
function validarFechaValida($fecha, $separador){
	$tmp = explode($separador, $fecha);
	$ok = false;
	if(strlen($tmp[0]) == 4){
		$ok = true;
	}
	if(strlen($tmp[1]) == 2){
		$ok = true;
	}
	if(strlen($tmp[2]) == 2){
		$ok = true;
	}
	return $ok;
}

function id_youtube($url) {
    $patron = '%^ (?:https?://)? (?:www\.)? (?: youtu\.be/ | youtube\.com (?: /embed/ | /v/ | /watch\?v= ) ) ([\w-]{10,12}) $%x';
    $array = preg_match($patron, $url, $parte);
    if (false !== $array) {
        return $parte[1];
    }
    return false;
}
?>