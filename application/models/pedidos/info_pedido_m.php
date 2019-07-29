<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_pedido_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  
	function informacion($Id_Pedido)
	{
		
		$Consulta = '
			select * from pedido where id_pedido = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return array();
		}
		else
		{
			return $Resultado->row_array();
		}
		
	}
	
	function procesando_id($Id_Proceso)
	{
		
		//Consulta para verificar si este proceso tiene un pedido sin finalizar
		$Consulta = '
			select id_pedido
			from pedido ped, procesos proc, cliente clie
			where ped.id_proceso = proc.id_proceso and proc.id_cliente = clie.id_cliente
			and ped.id_proceso = "'.$Id_Proceso.'" and fecha_reale = "0000-00-00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			return 0;
		}
		
	}
	
}

/* Fin del archivo */