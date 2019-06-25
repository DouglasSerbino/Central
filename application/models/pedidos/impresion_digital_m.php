<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Impresion_digital_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Listado de los tipos de acabados para impresion digital disponibles.
	 *@return array.
	*/
	function tipo_impd_acabado()
	{
		
		$Consulta = '
			select *
			from tipo_impd_acabado
			order by tipo_impd_acabado asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
  /**
	 *Listado de los tipos de materiales para impresion digital disponibles.
	 *@return array.
	*/
	function tipo_impd_material()
	{
		
		$Consulta = '
			select *
			from tipo_impd_material
			order by tipo_impd_material asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
}

/* Fin del archivo */