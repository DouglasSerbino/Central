<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar_dpto extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la informacion relacionada al departamento seleccionado
	 *por el usuario.
	 *@param string $Codigo: Codigo del departamento seleccionado.
	 *@return string 'error': Si no existe el departamento seleccionado.
	 *@return array: Si se encuentra el departamento y contiene la informacion solicitada.
	*/
	function departamento($Codigo)
	{
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = '
			select *
			from departamentos
			where id_dpto = "'.$Codigo.'"
			limit 0, 1
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(1 == $Resultado->num_rows())
		{
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		else
		{//Si no hay resultados
			return 'error';
		}
		
	}
	
}

/* Fin del archivo */