<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lineas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function listar($Activo = "s")
	{
		
		
		if('s' == $Activo || 'n' == $Activo)
		{
			$Condicion = 'where activo = "'.$Activo.'"';
		}
		else
		{
			$Condicion = '';
		}
		
		$Consulta = '
			select id_mc_linea, id_padre, codigo, linea, mas_menos
			from mc_linea
			'.$Condicion.'
			order by id_padre asc, codigo asc
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Lineas = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Lineas[$Fila['id_padre']][$Fila['id_mc_linea']]['linea'] = $Fila['linea'];
			$Lineas[$Fila['id_padre']][$Fila['id_mc_linea']]['codigo'] = $Fila['codigo'];
			$Lineas[$Fila['id_padre']][$Fila['id_mc_linea']]['mas_menos'] = $Fila['mas_menos'];
			
		}
		
		//print_r($Lineas);
		
		
		
		
		/*
		foreach($Resultado->result_array() as $Fila)
		{
			
			if('0' == $Fila['id_padre'])
			{
				$Lineas[$Fila['id_mc_linea']]['codigo'] = $Fila['codigo'];
				$Lineas[$Fila['id_mc_linea']]['linea'] = $Fila['linea'];
				$Lineas[$Fila['id_mc_linea']]['mas_menos'] = $Fila['mas_menos'];
				
				$Lineas[$Fila['id_mc_linea']]['sub_lineas'] = array();
			}
			else
			{
				$Lineas[$Fila['id_padre']]['sub_lineas'][$Fila['id_mc_linea']]['codigo'] = $Fila['codigo'];
				$Lineas[$Fila['id_padre']]['sub_lineas'][$Fila['id_mc_linea']]['linea'] = $Fila['linea'];
				$Lineas[$Fila['id_padre']]['sub_lineas'][$Fila['id_mc_linea']]['mas_menos'] = $Fila['mas_menos'];
			}
			
		}
		*/
		
		return $Lineas;
		
	}
	
	
	
	function agregar(
		$MC_Codigo,
		$MC_Linea,
		$MC_Padre,
		$MC_Tipo
	)
	{
		
		$Consulta = '
			insert into mc_linea values(
				NULL,
				"'.$MC_Padre.'",
				"'.$MC_Codigo.'",
				"'.$MC_Linea.'",
				"'.$MC_Tipo.'",
				"s"
			)
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		
	}
	
	
	
	function eliminar($Id_Mc_Linea)
	{
		
		$Consulta = '
			update mc_linea
			set activo = "n"
			where id_mc_linea = "'.$Id_Mc_Linea.'"
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
	}
	
	
	
	function modificar(
		$MC_ID_Linea,
		$MC_Codigo,
		$MC_Linea,
		$MC_Padre,
		$MC_Tipo
	)
	{
		
		$Consulta = '
			update mc_linea set
			id_padre = "'.$MC_Padre.'",
			codigo = "'.$MC_Codigo.'",
			linea = "'.$MC_Linea.'",
			mas_menos = "'.$MC_Tipo.'"
			where id_mc_linea = "'.$MC_ID_Linea.'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
		
	}
	
	
}

/* Fin del archivo */