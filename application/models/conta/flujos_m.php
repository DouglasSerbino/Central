<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flujos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function listado($Rango)
	{
		
		$Valores = array(
			'General' => array(
				'Proyectado' => array(),
				'Real' => array()
			),
			'Clientes' => array()
		);
		
		
		$Fechas = ' and factura >= "2016-01-01" and factura <= "2016-12-31" ';
		if('1semestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-01-01" and factura <= "2016-06-31" ';
		}
		if('2semestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-07-01" and factura <= "2016-12-31" ';
		}
		if('1trimestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-01-01" and factura <= "2016-03-31" ';
		}
		if('2trimestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-04-01" and factura <= "2016-06-31" ';
		}
		if('3trimestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-07-01" and factura <= "2016-09-31" ';
		}
		if('4trimestre' == $Rango)
		{
			$Fechas = ' and factura >= "2016-10-01" and factura <= "2016-12-31" ';
		}
		
		//proyectado
		$Consulta = '
			select sum(venta) as total, date_format(factura, "%m") as mes,
			clie.codigo_cliente, clie.nombre
			from cliente clie, procesos proc, pedido ped, pedido_sap sapo
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido
			'.$Fechas.'
			group by clie.codigo_cliente, date_format(factura, "%m")
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Valores['Clientes'][$Fila['codigo_cliente']]['Proyectado'][$Fila['mes']] = $Fila['total'];
			$Valores['Clientes'][$Fila['codigo_cliente']]['Nombre'] = $Fila['nombre'];
			
			if(!isset($Valores['General']['Proyectado'][$Fila['mes']]))
			{
				$Valores['General']['Proyectado'][$Fila['mes']] = 0;
			}
			$Valores['General']['Proyectado'][$Fila['mes']] += $Fila['total'];
		}
		
		
		//Real
		$Consulta = '
			select sum(venta) as total, date_format(factura, "%m") as mes,
			clie.codigo_cliente, clie.nombre
			from cliente clie, procesos proc, pedido ped, pedido_sap sapo
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = sapo.id_pedido
			'.$Fechas.'
			and orden != ""
			group by clie.codigo_cliente, date_format(factura, "%m")
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Valores['Clientes'][$Fila['codigo_cliente']]['Real'][$Fila['mes']] = $Fila['total'];
			$Valores['Clientes'][$Fila['codigo_cliente']]['Nombre'] = $Fila['nombre'];
			
			if(!isset($Valores['General']['Real'][$Fila['mes']]))
			{
				$Valores['General']['Real'][$Fila['mes']] = 0;
			}
			$Valores['General']['Real'][$Fila['mes']] += $Fila['total'];
		}
		
		return $Valores;
		
	}
	
	
}
/* Fin del archivo */