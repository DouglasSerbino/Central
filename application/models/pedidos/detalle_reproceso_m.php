<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_reproceso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Busca en la base de datos todos los motivos, causa o razon del porque se reprocesara un trabajo.
	*/
	function detalle_reproceso()
	{
		
		$Consulta = '
			select * from reproceso_detalle where activo = "s"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Regreso el array con los usuarios encontrados
		return $Resultado->result_array();
	}
}

/* Fin del archivo */