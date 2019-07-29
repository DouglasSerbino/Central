<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guardar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Toma la ruta creada por el usuario y la guarda en la base de datos.
	 *@param string $Id_Grupo.
	 *@param string $Ruta.
	 *@return string "ok", "error"
	*/
	function buscar_dptos($Id_Grupo, $Ruta)
	{
		
		$Ruta = explode(',', $Ruta);
		if(0 < count($Ruta))
		{
			
			$Consulta = '
				insert into ruta_grupo values(NULL, "'.$Id_Grupo.'", "Default")
			';
			
			$this->db->query($Consulta);
			
			$Consulta = '
				select id_ruta_grupo
				from ruta_grupo
				where id_grupo = "'.$Id_Grupo.'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(1 != $Resultado->num_rows())
			{
				return 'error';
				exit();
			}
			
			$Ruta_Grupo = $Resultado->result_array();
			
			$Consulta = array();
			foreach($Ruta as $Dpto)
			{
				$Id_Dpto = explode('_', $Dpto);
				$Consulta[] = ' (NULL, "'.$Ruta_Grupo[0]['id_ruta_grupo'].'", "'.$Id_Dpto[1].'")';
				
			}
			
			$Consulta = 'insert into ruta_dpto values'.implode(',', $Consulta);
			
			$this->db->query($Consulta);
			
			return 'ok';
			
		}
		else
		{
			return 'error';
		}
		
	}
}

/* Fin del archivo */