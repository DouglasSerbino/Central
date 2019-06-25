<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Especificacion_ingresar_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  /**
	 *Busca en la base de datos la informacion de las especificaciones del pedido
	 *senhalado.
	 *@param string $Especs.
	 *@param string $Id_Pedido.
	 *@return array.
	*/
	function ingreso($Especs, $Id_Pedido)
	{
		
		$Tablas_Directas = array(
			'general' => '',
			'matrecgru' => '',
			'matsolgru' => ''
		);
		
		$Id_Especificacion_General = 0;
		
		foreach($Especs as $Tabla => $Informacion)
		{
			
			
			if(
				'colores_estr' == $Tabla
			)
			{
				continue;
			}
			
			
			//De las dos tablas en la condicion se realiza una captura distinta
			if('matrecgru' == $Tabla || 'matsolgru' == $Tabla || 'acabado' == $Tabla)
			{
				
				$Consulta_Array = array();
				
				$Id_Tabla_Externa = $Id_Pedido;
				if('acabado' == $Tabla)
				{
					$Id_Tabla_Externa = $Id_Especificacion_General;
				}
				
				foreach($Especs[$Tabla] as $Index => $Material)
				{
					$Consulta_Array[] = '(NULL, "'.$Index.'", "'.$Id_Tabla_Externa.'")';
				}
				
				unset($Consulta_Array[0]);
				
				if(0 < count($Consulta_Array))
				{
					//Se ingresan los nuevos materiales
					$Consulta = 'insert into especificacion_'.$Tabla.' values '.implode(', ', $Consulta_Array);
					
					$this->db->query($Consulta);
				}
				
			}
			elseif('colores' == $Tabla)
			{
				
				$Consulta_Array = array();
				
				for($i = 1; $i <= 10; $i++)
				{
					
					if(isset($Especs[$Tabla][$i]))
					{
						$Consulta_Array[] = '(
							NULL,
							"'.$Id_Especificacion_General.'",
							"'.implode('", "', $Especs[$Tabla][$i]).'"
						)';
					}
					
				}
				
				if(0 < count($Consulta_Array))
				{
					
					$Consulta = '
						insert into especificacion_colores values '.implode(', ', $Consulta_Array).'
					';
					$this->db->query($Consulta);
					
				}
				
			}
			else
			{
				
				$Id_Tabla_Externa = $Id_Pedido;
				if(!isset($Tablas_Directas[$Tabla]))
				{
					$Id_Tabla_Externa = $Id_Especificacion_General;
				}
				
				$Consulta = '
					insert into especificacion_'.$Tabla.' values(
						NULL,
						"'.$Id_Tabla_Externa.'",
						"'.implode('", "', $Especs[$Tabla]).'"
					)
				';
				
				$this->db->query($Consulta);
				
				if('general' == $Tabla)
				{
					$Id_Especificacion_General = $this->db->insert_id();
				}
				
			}
			
		}
		
	}
	
	
}

/* Fin del archivo */