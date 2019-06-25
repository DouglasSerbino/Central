<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruta_existe_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos si el grupo indicado ya posee una ruta de trabajo.
	 *@param string $Id_Grupo.
	 *@return string $Id_Ruta.
	*/
	function verificar($Id_Grupo)
	{
		
		//Consulta para verificar la existencia de la ruta para este grupo.
		$Consulta = '
			select id_ruta_grupo
			from ruta_grupo
			where id_grupo = "'.$Id_Grupo.'"
		';
		
		//Ejecucion de la consulta
		$Resultado = $this->db->query($Consulta);
		
		if(1 != $Resultado->num_rows())
		{//Si existe una ruta
			//Obtenemos los valores
			$Fila = $Resultado->result_array();
			//Devolvemos el id
			return $Fila[0]['id_ruta_grupo'];
		}
		else
		{//Si no existe
			//Devolvemos 0 en muestra e ello
			return 0;
		}
		
	}
	
	/**
	 *Busca en la base de datos todos los grupos que ya poseen una ruta de trabajo.
	 *@return array el id_grupo como indice de cada posicion.
	*/
	function grupos()
	{
		
		//Consulta para verificar la existencia de la ruta para este grupo.
		$Consulta = '
			select id_grupo
			from ruta_grupo
			order by id_ruta_grupo asc
		';
		
		//Ejecucion de la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Ruta_Grupo = array();
		
		if(0 < $Resultado->num_rows())
		{//Si existen rutas
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Ruta_Grupo[$Fila['id_grupo']] = '';
			}
		}
		
		return $Ruta_Grupo;
		
	}
	
}