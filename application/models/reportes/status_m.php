<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Estado de los pedidos para el cliente proporcionado.
	 *@return array.
	*/
	function trabajos_procesando($Codigo_Cliente)
	{
		
		//Pedidos en proceso
		$Consulta = '
			select proceso, proc.nombre, id_pedido, fecha_entrega
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and fecha_reale = "0000-00-00" and codigo_cliente = "'.$Codigo_Cliente.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by fecha_entrega asc, id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
	
	
	/**
	 *Estado de los pedidos para el cliente proporcionado.
	 *@return array.
	*/
	function trabajos_finalizados($Codigo_Cliente, $Mes, $Anho)
	{
		
		//Pedidos en proceso
		$Consulta = '
			select proceso, proc.nombre, id_pedido, fecha_reale
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and fecha_reale != "0000-00-00"
				and fecha_entrega >= "'.$Anho.'-'.$Mes.'-01"
				and fecha_entrega <= "'.$Anho.'-'.$Mes.'-31"
				and codigo_cliente = "'.$Codigo_Cliente.'"
				and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by fecha_entrega asc, id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
	function materiales_procesando($Codigo_Cliente)
	{
		
		$Materiales = array();
		
		$Consulta = '
			select ped.id_pedido, min(final), material_solicitado
			from cliente clie, procesos proc, pedido ped,
				especificacion_matsolgru espe,
				material_solicitado_grupo grupo, material_solicitado soli
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = espe.id_pedido
				and espe.id_material_solicitado_grupo = grupo.id_material_solicitado_grupo
				and grupo.id_material_solicitado = soli.id_material_solicitado
				and fecha_reale = "0000-00-00" and codigo_cliente = "'.$Codigo_Cliente.'"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by ped.id_pedido
			order by ped.id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales[$Fila['id_pedido']] = $Fila['material_solicitado'];
		}
		return $Materiales;
		
	}
	
	
	function materiales_finalizados($Codigo_Cliente, $Mes, $Anho)
	{
		
		$Materiales = array();
		
		$Consulta = '
			select ped.id_pedido, min(final), material_solicitado
			from cliente clie, procesos proc, pedido ped,
				especificacion_matsolgru espe,
				material_solicitado_grupo grupo, material_solicitado soli
			where clie.id_cliente = proc.id_cliente
				and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = espe.id_pedido
				and espe.id_material_solicitado_grupo = grupo.id_material_solicitado_grupo
				and grupo.id_material_solicitado = soli.id_material_solicitado
				and fecha_reale != "0000-00-00"
				and fecha_entrega >= "'.$Anho.'-'.$Mes.'-01"
				and fecha_entrega <= "'.$Anho.'-'.$Mes.'-31"
				and codigo_cliente = "'.$Codigo_Cliente.'"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by ped.id_pedido
			order by ped.id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales[$Fila['id_pedido']] = $Fila['material_solicitado'];
		}
		
		return $Materiales;
		
	}
		
}

/* Fin del archivo */