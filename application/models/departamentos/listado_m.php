<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busca en la base de datos todos los departamentos almacenados y extrae la informacion
	 *de cada uno para enviarlo en un array.
	 *@param string $Activo.
	 *@param string $Formato.
	 *@return array: Contiene el listado de los departamentos de las divisiones.
	*/
	function buscar_dptos($Activo = '', $Formato = '')
	{
		
		$Condiciones = array();
		
		if('' != $Activo)
		{
			//Consultamos la base de datos para que nos ofresca los departamentos.
			$Condiciones[] = ' activo = "'.$Activo.'" ';
		}
		if(
			'Gerencia' != $this->session->userdata('codigo')
			&& 'Sistemas' != $this->session->userdata('codigo')
		)
		{
			$Condiciones[] = ' codigo != "Gerencia" and codigo != "Sistemas" ';
		}
		
		//Consultamos la base de datos para que nos ofresca los departamentos.
		$Consulta = '
			select *
			from departamentos
		';
		if(0 < count($Condiciones))
		{
			$Consulta .= ' where '.implode('and', $Condiciones);
		}
		$Consulta .= '
			order by departamento asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		if('' == $Formato){
			//Regreso el array con los grupos encontrados
			return $Resultado->result_array();
		}
		if('si' == $Formato)
		{
			//Arreglo la informacion para que el array resultante tenga como indice el
			//id_dpto
			
			$Departamentos = array();
			foreach($Resultado->result_array() as $Fila)
			{
				$Departamentos[$Fila['id_dpto']]['codigo'] = $Fila['codigo'];
				$Departamentos[$Fila['id_dpto']]['tiempo'] = $Fila['tiempo'];
				$Departamentos[$Fila['id_dpto']]['iniciales'] = $Fila['iniciales'];
				$Departamentos[$Fila['id_dpto']]['departamento'] = $Fila['departamento'];
			}
			
			//Regreso el array con los elementos ordenados.
			return $Departamentos;
			
		}
		
	}
}

/* Fin del archivo */