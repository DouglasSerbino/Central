<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ventas_diarias_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *@param Fecha en la que se realizara el reporte.
	 *@return Ventas diarias.
	*/
	function mostrar_clientes($Fecha)
	{
		
		$info = array();
		
		$Consulta2 = '
			SELECT cli.id_cliente, cli.codigo_cliente, cli.nombre,
			sap.sap, sap.venta, sap.confirmada, sap.factura, sap.id_pedido_sap
			FROM pedido_sap sap, cliente cli
			WHERE sap.id_cliente = cli.id_cliente
			AND fecha = "'.$Fecha.'"
			AND venta != ""
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			ORDER BY cli.id_cliente
		';
		
		//Ejecuto la consulta
		$Resultado2 = $this->db->query($Consulta2);
		//Regreso el array con los grupos encontrados
		$Fila2 = $Resultado2->result_array();
		
		foreach($Fila2 as $Datos_ventas)
		{
			$info[$Datos_ventas['id_cliente']]['nombre'] = $Datos_ventas['nombre'];
			$info[$Datos_ventas['id_cliente']]['codigo_cliente'] = $Datos_ventas['codigo_cliente'];
			$info[$Datos_ventas['id_cliente']]['ventas'][$Datos_ventas['id_pedido_sap']]['sap'] = $Datos_ventas['sap'];
			$info[$Datos_ventas['id_cliente']]['ventas'][$Datos_ventas['id_pedido_sap']]['venta'] = $Datos_ventas['venta'];
			$info[$Datos_ventas['id_cliente']]['ventas'][$Datos_ventas['id_pedido_sap']]['confirmada'] = $Datos_ventas['confirmada'];
			$info[$Datos_ventas['id_cliente']]['ventas'][$Datos_ventas['id_pedido_sap']]['factura'] = $Datos_ventas['factura'];
		}
		
		return $info;
		
	}
}

/* Fin del archivo */