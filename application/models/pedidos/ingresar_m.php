<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresar_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Crea el registro del pedido en la base de datos.
	 *@param string $Id_Proceso.
	 *@param string $Fecha_Entrega.
	 *@param string $Prioridad.
	 *@param string $Tipo_Trabajo.
	 *@param string $Id_Usu_Rechazo.
	 *@return string [$Id_Pedido|'error'].
	*/
	function index(
		$Id_Proceso,
		$Fecha_Entrega,
		$Prioridad,
		$Tipo_Trabajo,
		$Id_Usu_Rechazo,
		$Fecha,
		$Id_Cliente = 0,
		$Reproceso = ''
	)
	{
		
		
		//Ingreso del pedido
		$Consulta = '
			insert into pedido values(
				NULL,
				"'.$Id_Proceso.'",
				"'.$Tipo_Trabajo.'",
				"'.$Fecha.'",
				"'.$Fecha_Entrega.'",
				"0000-00-00",
				"'.$Prioridad.'",
				"'.$Id_Usu_Rechazo.'",
				"0",
				"0",
				"'.$Reproceso.'"
			)
		';
		
		$this->db->query($Consulta);
		
		//Busco el id_pedido recien ingresado
		$Consulta = '
			select id_pedido
			from pedido
			where id_proceso = "'.$Id_Proceso.'" and fecha_reale = "0000-00-00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(1 == $Resultado->num_rows())
		{//Si existe un regisrto
			
			$Fila = $Resultado->result_array();
			
			//Envio el id_pedido
			return $Fila[0]['id_pedido'];
			
		}
		else
		{//Si no aparecio envio mensaje de error
			return 'error';
		}
		
	}
	
	
}

/* Fin del archivo */