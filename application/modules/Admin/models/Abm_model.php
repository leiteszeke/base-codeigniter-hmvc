<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abm_model extends CI_Model {
	private $_nombreDeTabla;
	private $_id_tabla;
	
	public function __construct() {
		parent::__construct();
	}

    /**
     * Gets the value of _nombreDeTabla.
     *
     * @return mixed
     */
    public function getNombreDeTabla()
    {
        return $this->_nombreDeTabla;
    }

    /**
     * Sets the value of _nombreDeTabla.
     *
     * @param mixed $_nombreDeTabla the nombre de tabla
     *
     * @return self
     */
    protected function _setNombreDeTabla($nombreDeTabla)
    {
        $this->_nombreDeTabla = $nombreDeTabla;

        return $this;
    }

    /**
     * Gets the value of _id_tabla.
     *
     * @return mixed
     */
    public function getIdTabla()
    {
        return $this->_id_tabla;
    }

    /**
     * Sets the value of _id_tabla.
     *
     * @param mixed $_id_tabla the id tabla
     *
     * @return self
     */
    public function _setIdTabla($id_tabla)
    {
        $this->_id_tabla = $id_tabla;

        return $this;
    }

	/**
	* Buscar una seccion por friendly_url
	* @param $friendly
	* @return array
	* */
	public function buscarByFriendly($friendly = "") {
		if($friendly === "") {
			return array();
		}

		$types = $this->Secciones_model->getTypeField($this->_nombreDeTabla);

		$this->db->limit(15,0);
		$sec   = $this->db->get_where($this->_nombreDeTabla,array('id_'.$this->_nombreDeTabla=>$friendly));
		$rows = $sec->row_array();

		if(!is_null($rows)) {
			foreach($rows as $field=>$row) {
				$retorno = search_array_in_array($field, $types);

				if($types[$retorno]['type'] == "datetime") {
					$rows[$field] = date('d/m/Y H:i', strtotime($row));
				}elseif($types[$retorno]['type'] == "tinyint") {
					$rows[$field] = array();
					$publi = ($row == 1) ? array('checked'=>"checked") : array('checked'=>'');
					array_push($rows[$field], $publi);
				}

				if($field != "id_".$this->_nombreDeTabla && strpos($field, "id_") !== false) {
					//FK
					$tabla   = explode("id_",$field);
					$fk      = $this->db->get($tabla[1]);
					$datosfk = $fk->result_array();

					foreach($datosfk as $i=>$dato) {
						$datosfk[$i]['marcado'] = ($dato[$field] == $row) ? 'selected' : '';
					}
					$rows[$tabla[1]] = $datosfk;
				}
			}
		}
		return $rows;
	}

	/**
	* Insertar una noticia en la base de datos
	* @param $datos Array
	* */
	public function add($datos) {

        $resultado = $this->db->list_fields($this->getNombreDeTabla());
        if(in_array('orden', $resultado)){
            $g = $this->db->select_max('orden')->get($this->getNombreDeTabla());
            $res = $g->row_array();
            $ordenMax = $res['orden'];

            if(isset($datos['orden'])){
	            $orden_desplazar_intercambiar = $this->config->item('orden_desplazar_intercambiar');
            	if($datos['orden'] < $ordenMax){
            		# El orden es menor al máximo existente
            		if ($orden_desplazar_intercambiar == 1) {
		            	# DESPLAZAR
		            	$this->db->where('orden >=',$datos['orden']);
		            	$this->db->set('orden', 'orden+1', false);
		            }elseif ($orden_desplazar_intercambiar == 2) {
		            	# INTERCAMBIAR
			            $this->db->where('orden =',$datos['orden']);
			            $this->db->set('orden', $ordenMax+1);
		            }
            	}elseif($datos['orden'] == $ordenMax){
	            	# El orden es igual al máximo existente
					# DESPLAZAR AUTOMATICO
		            $this->db->where('orden =',$datos['orden']);
		            $this->db->set('orden', 'orden+1', false);
            	}
	        
	        	if($datos['orden'] <= $ordenMax){
	        		$this->db->update($this->getNombreDeTabla());
	        	}
	        }
        }
        
		$datos['created'] = date('Y-m-d H:i:s');
		$in    = $this->db->insert($this->_nombreDeTabla,$datos);

		$error = $this->db->error();

		if($error['code'] != 0) {
			var_dump($this->db->error());
		}

		return $error['code'];

	}

	/**
	 * Retorna el último id generado
	 * @return [type] [description]
	 */
	public function lastInsertId(){
		return $this->db->insert_id();
	}

	/**
	* Modificar una noticia en la base de datos
	* @param $datos Array
	* @return int
	* */
	public function modified($datos) {
		$updated = date('Y-m-d H:i:s');

        $resultado = $this->db->list_fields($this->getNombreDeTabla());
        if(in_array('orden', $resultado)){
            if(isset($datos['orden'])){
	            if($datos['orden_original'] != $datos['orden']){
	            	#El orden se modificó
		            $orden_desplazar_intercambiar = $this->config->item('orden_desplazar_intercambiar');
		            if ($orden_desplazar_intercambiar == 1) {
				        # DESPLAZAR
			            if($datos['orden_original'] < $datos['orden']){
			            	# El orden almacenado es menor al orden requerido
			            	$this->db->where('orden >',  $datos['orden_original']);
			            	$this->db->where('orden <=', $datos['orden']);
			            	$this->db->set('orden', 'orden-1', false);
			            }elseif($datos['orden_original'] > $datos['orden']){
							# El orden almacenado es mayor al orden requerido
				            $this->db->where('orden >=', $datos['orden']);
			            	$this->db->where('orden <',  $datos['orden_original']);
				            $this->db->set('orden', 'orden+1', false);
				        }
			        }elseif ($orden_desplazar_intercambiar == 2) {
			            # INTERCAMBIAR
			            $this->db->where('orden', $datos['orden']);
			            $this->db->set('orden', $datos['orden_original']);
		            }
			        
					$this->db->set('updated', $updated);
		        	$this->db->update($this->getNombreDeTabla());
		        }
		        # ELSE : No Hago nada ya que el orden ingresado es el mismo que el almacenado
		        unset($datos['orden_original']);
	        }
	    }

		$this->db->where('id_'.$this->_nombreDeTabla,$this->_id_tabla);
		$datos['updated'] = $updated;
		$in    = $this->db->update($this->_nombreDeTabla,$datos);

		$error = $this->db->error();

		if($error['code'] != 0) {
			var_dump($this->db->error());
		}

		return $error['code'];

	}

	/**
	* Elimina item
	*
	* @param int $valor
	* @return bool
	*/
	public function eliminarItem($valor) {
		$this->db->where('id_'.$this->_nombreDeTabla,$valor);
		return $this->db->delete($this->_nombreDeTabla);
	}

	public function enabled_member($valor) {
		if(isset($valor) && $valor != "") {
			$this->db->where('id_'.$this->_nombreDeTabla,$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('publicado'=>1));
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}

	public function disabled_member($valor) {
		if(isset($valor) && $valor != "") {
			$this->db->where('id_'.$this->_nombreDeTabla,$valor);
			$dl = $this->db->update($this->_nombreDeTabla,array('publicado'=>0));
			echo $this->db->last_query();
			if($dl) {
				return true;
			}else {
				return false;
			}
		}
		return false;
	}
	
	public function getMaxOrden(){
        $this->db->select_max('orden')->from($this->getNombreDeTabla());
        $get = $this->db->get();
        $arr = $get->row_array();
        return (int)$arr['orden'];
    }
}
