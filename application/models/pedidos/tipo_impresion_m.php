<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_impresion_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Listado de los tipos de impresion disponibles.
	 *@return array.
	*/
	function tipos()
	{
		
		$Consulta = '
			select *
			from tipo_impresion
			order by tipo_impresion asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
}

/* Fin del archivo */