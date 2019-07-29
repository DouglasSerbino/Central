<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sin_cobro_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function listado($Anho, $Mes)
	{
		
		$Trabajos = array('clientes' => array(), 'trabajos' => array());
		
		$Condicion = ' and factura >= "'.$Anho.'-'.$Mes.'-01" and factura <= "'.$Anho.'-'.$Mes.'-31"';
		if('anual' == $Mes)
		{
			$Condicion = ' and factura >= "'.$Anho.'-01-01" and factura <= "'.$Anho.'-12-31"';
		}
		
		$Consulta = '
			select codigo_cliente, proceso, ped.id_pedido, venta, fecha_reale,
			proc.nombre, sap, factura, clie.id_cliente
			from cliente clie, procesos proc, pedido_sap sapo, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido and venta > 0 and sap != "" and factura != "" and orden = ""
			'.$Condicion.'
			and id_grupo = '.$this->session->userdata('id_grupo').'
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
	
	function cobrar($Id_Pedido)
	{
		
		$Consulta = '
			update pedido_sap
			set orden = "si"
			where id_pedido = "'.$Id_Pedido.'"
		';
		$this->db->query($Consulta);
		
		return 'ok';
		
	}
	
}
/* Fin del archivo */