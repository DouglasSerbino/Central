<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesando_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Buscar en la base de datos si el proceso indicado tiene una ruta activa.
	 *@param string $Id_Proceso.
	 *@return string 'activo','inactivo'.
	*/
	function proceso($Id_Proceso)
	{
		
		//Consulta para verificar si este proceso tiene un pedido sin finalizar
		$Consulta = '
			select id_pedido
			from pedido ped, procesos proc, cliente clie
			where ped.id_proceso = proc.id_proceso and proc.id_cliente = clie.id_cliente
			and ped.id_proceso = "'.$Id_Proceso.'" and fecha_reale = "0000-00-00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows)
		{
			return 'inactivo';
		}
		else
		{
			return 'activo';
		}
		
	}
	
  /**
	 *Buscar en la base de datos si el proceso indicado tiene una ruta activa.
	 *@param string $Id_Proceso.
	 *@return string [0|$Id_Pedido].
	*/
	function proceso_ped($Id_Proceso)
	{
		
		//Consulta para verificar si este proceso tiene un pedido sin finalizar
		$Consulta = '
			select id_pedido
			from pedido ped, procesos proc, cliente clie
			where ped.id_proceso = proc.id_proceso and proc.id_cliente = clie.id_cliente
			and ped.id_proceso = "'.$Id_Proceso.'" and fecha_reale = "0000-00-00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return 0;
		}
		else
		{
			$Fila = $Resultado->row_array();
			return $Fila['id_pedido'];
		}
		
	}
	
	
	/**
	 *Buscar en la base de datos si el pedido indicado tiene una ruta activa.
	 *@param string $Id_Pedido.
	 *@return string 'activo','inactivo'.
	*/
	function pedido($Id_Pedido)
	{
		
		//Consulta para verificar si este pedido tiene un pedido sin finalizar
		$Consulta = '
			select id_pedido
			from pedido ped, procesos proc, cliente clie
			where ped.id_proceso = proc.id_proceso and proc.id_cliente = clie.id_cliente
			and id_pedido = "'.$Id_Pedido.'" and fecha_reale = "0000-00-00"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows)
		{
			return 'inactivo';
		}
		else
		{
			return 'activo';
		}
		
	}	
}

/* Fin del archivo */