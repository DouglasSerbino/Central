<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sin_quedan_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function listado($Anho, $Mes)
	{
		
		$Trabajos = array('clientes' => array(), 'trabajos' => array());
		
		$Condicion = ' and fecha >= "'.$Anho.'-'.$Mes.'-01" and fecha <= "'.$Anho.'-'.$Mes.'-31"';
		if('anual' == $Mes)
		{
			$Condicion = ' and fecha >= "'.$Anho.'-01-01" and fecha <= "'.$Anho.'-12-31"';
		}
		
		$Consulta = '
			select codigo_cliente, proceso, ped.id_pedido, venta, fecha_reale,
			proc.nombre, sap, clie.id_cliente
			from cliente clie, procesos proc, pedido_sap sapo, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido and venta > 0 and sap != "" and factura = ""
			'.$Condicion.'
			and id_grupo = '.$this->session->userdata('id_grupo').'
			order by fecha_reale asc
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Trabajos['trabajos'][$Fila['id_cliente']][] = $Fila;
		}
		
		
		if(0 < count($Trabajos['trabajos']))
		{
			$Consulta = '
				select id_cliente, nombre
				from cliente
				where id_cliente in ('.implode(', ', array_keys($Trabajos['trabajos'])).')
				order by nombre asc
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Trabajos['clientes'][$Fila['id_cliente']] = $Fila['nombre'];
			}
		}
		
		
		return $Trabajos;
		
	}
	
	function quedan($Id_Pedido, $Fecha)
	{
		
		$Fecha = date('Y-m-d', strtotime($Fecha));
		
		$Consulta = '
			update pedido_sap
			set factura = "'.$Fecha.'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		$this->db->query($Consulta);
		
		return 'ok';
		
	}
	
}
/* Fin del archivo */