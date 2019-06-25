<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modi_factura_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function info($Id_Pedido)
	{
		
		
		$Consulta = '
			select clie.nombre as nom_clie, clie.codigo_cliente, proc.proceso, proc.nombre, sapo.sap,
			sapo.venta, sapo.fecha
			from cliente clie, procesos proc, pedido ped, pedido_sap sapo
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido and ped.id_pedido = '.$Id_Pedido.'
		';
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		
		return array();
		
	}


	function actualizar($Id_Pedido, $Factura, $Venta, $Fecha)
	{

		$Consulta = '
			update pedido_sap
			set sap = "'.$Factura.'", venta = "'.$Venta.'", 
			fecha = "'.date('Y-m-d', strtotime($Fecha)).'"
			where id_pedido = "'.$Id_Pedido.'"
		';

		$this->db->query($Consulta);

	}
	
	
}
/* Fin del archivo */