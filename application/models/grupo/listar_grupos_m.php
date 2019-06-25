<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listar_grupos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos todos los grupos almacenados y extrae la informacion
	 *de cada uno para enviarlo en un array.
	 *@param string $Activo.
	 *@param string $Solo_CG.
	 *@return array: Contiene el listado de los grupos de la corporacion.
	*/
	function listado($Activo = '', $Solo_CG = 'n')
	{
		
		$Condicion = '';
		
		if('' != $Activo)
		{
			if('s' == $Solo_CG)
			{
				$Condicion = ' and tipo_grupo != "cli"';
			}
			//Consultamos la base de datos para que nos ofresca los grupos.
			$Consulta = '
				select *
				from grupos
				where activo = "'.$Activo.'"'.$Condicion.'
				order by nombre_grupo asc
			';
		}
		else
		{
			if('s' == $Solo_CG)
			{
				$Condicion = 'where tipo_grupo != "cli"';
			}
			//Consultamos la base de datos para que nos ofresca los grupos.
			$Consulta = '
				select *
				from grupos
				'.$Condicion.'
				order by nombre_grupo asc
			';
		}
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
		//Regreso el array con los grupos encontrados
		return $Resultado->result_array();
		
	}
	
}

/* Fin del archivo */