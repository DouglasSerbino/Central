<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_equipos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos los equipos pertenecientes a las areas existentes
	 *@return array.
	*/
	function mostrar_equipos()
	{
			
			$Consulta = '
				select id_inventario_equipo, nombre_equipo
				from inventario_equipo
				where id_grupo = "'.$this->session->userdata["id_grupo"].'"
				order by nombre_equipo asc
			';
		
		$Resultado = $this->db->query($Consulta);
				
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
}

/* Fin del archivo */