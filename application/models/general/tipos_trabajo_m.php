<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipos_trabajo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Buscar en la base de datos los tipos de trabajo existentes.
	 *@param nada.
	 *@return array.
	*/
	function tipos()
	{
		
		//Selecciono todos los tipos de trabajo
		$Consulta = '
			select *
			from tipo_trabajo
			order by id_tipo_trabajo asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
}

/* Fin del archivo */