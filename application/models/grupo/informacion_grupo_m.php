<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Informacion_grupo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos la información relacionada al grupo seleccionado
	 *por el usuario.
	 *@param string $Nombre_Grupo: Abreviatura que corresponde al grupo a buscar
	 *@return string 'error': Si no existe el grupo seleccionado.
	 *@return array: Si se encuentra el grupo y contiene la informacion solicitada.
	*/
	function grupo($Abreviatura)
	{
		
		$Grupo = array();
		
		//Consultamos la base de datos para obtener la informacion del grupo.
		$Consulta = '
			select *
			from grupos
			where abreviatura = "'.$Abreviatura.'"
			limit 0, 1
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		//Si la consulta obtuvo resultados
		if(1 == $Resultado->num_rows())
		{
			$Grupo = $Resultado->row_array();
			$Grupo['id_cliente'] = 0;
			
			
			//Id_Cliente al que hace referencia este cliente
			$Consulta = '
				select id_cliente
				from cliente_grupo
				where id_grupo_externo = "'.$Grupo['id_grupo'].'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			//Si la consulta obtuvo resultados
			if(1 == $Resultado->num_rows())
			{
				$Resultado = $Resultado->row_array();
				$Grupo['id_cliente'] = $Resultado['id_cliente'];
			}
			
			
			
			//Regreso el array con los grupos encontrados
			return $Grupo;
		}
		else
		{//Si no hay resultados
			return 'error';
		}
		
	}
	
}

/* Fin del archivo */