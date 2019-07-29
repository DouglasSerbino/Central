<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enlaces_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Un pedido tiene en su ruta una sub-ruta que pertenece a otro pedido.
	 *@param string $Id_Pedido.
	 *@param string $Id_Pedido_Hijo.
	 *@return 'ok'.
	*/
	function enlazar(
		$Id_Pedido,
		$Id_Pedido_Hijo
	)
	{
		
		//Creacion del enlace
		$Consulta = '
			insert into pedido_pedido values(
				NULL,
				"'.$Id_Pedido.'",
				"'.$Id_Pedido_Hijo.'"
			)
		';
		
		//Ingreso del pedido
		$this->db->query($Consulta);
		
		return 'ok';
		
	}
	
	
	function existe_enlace(
		$Id_Pedido,
		$Id_Pedido_Hijo
	)
	{
		
		$Consulta = '
			select id_pedido_pedido
			from pedido_pedido
			where id_ped_primario = "'.$Id_Pedido.'" and id_ped_secundario = "'.$Id_Pedido_Hijo.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return 'no';
		}
		else
		{
			return 'si';
		}
		
	}
	
	function es_hijo($Id_Pedido)
	{
		
		$Consulta = '
			select *
			from pedido_pedido
			where id_ped_secundario = "'.$Id_Pedido.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Fila = 0;
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
		}
		
		return $Fila;
		
	}
	
	function es_padre($Id_Pedido)
	{
		
		$Consulta = '
			select *
			from pedido_pedido
			where id_ped_primario = "'.$Id_Pedido.'"
			order by id_pedido_pedido desc
			limit 0, 1
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Fila = 0;
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
		}
		
		return $Fila;
		
	}
	
	function proceso_padre($Codigo_cliente, $Proceso)
	{
		
		$Consulta = '
			select id_pedido
			from procesos proc, cliente clie, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and codigo_cliente = "'.$Codigo_cliente.'" and proceso = "'.$Proceso.'"
			and id_grupo = 1
			order by id_pedido desc
			limit 0, 1
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			return array();
		}
		
	}
	
}

/* Fin del archivo */