<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Revision_inventario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	

	function Listado($Id_Cliente, $Fecha_Inicio, $Fecha_Fin, $Sap)
	{
		$SQL = '';
		if('' != $Sap)
		{
			$SQL .= ' and mat.codigo_sap = "'.$Sap.'"';
		}
		if('--' != $Id_Cliente)
		{
			$SQL .= ' and cli.id_cliente = "'.$Id_Cliente.'"';
		}
		$Consulta = '
							select proc.proceso, cli.codigo_cliente, sap.orden, cantidad, mat.codigo_sap, ped.fecha_reale, mat.nombre_material
							from procesos proc, pedido ped, cliente cli, pedido_sap sap, pedido_material pedmat, inventario_material mat
							where ped.id_proceso = proc.id_proceso
							and cli.id_cliente = proc.id_cliente
							and ped.id_pedido = sap.id_pedido
							and ped.id_pedido = pedmat.id_pedido
							and pedmat.id_inventario_material = mat.id_inventario_material
							and ped.fecha_entrega >= "'.$Fecha_Inicio.'" and ped.fecha_entrega <= "'.$Fecha_Fin.'"
							'.$SQL.'
							and ped.fecha_reale != "0000-00-00"
							and sap.venta > 0
						';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
		
	}
	
	
}
/* Fin del archivo */