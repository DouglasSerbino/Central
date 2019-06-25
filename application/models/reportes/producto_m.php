<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	function reporte($tipo, $anho, $mes, $Id_Cliente)
	{
		
		$Productos = array();
		$Pedido_Productos = array();
		$Id_Prod_Clie = array();
		
		
		//Productos
		$Consulta = '
			select id_prod_clie, producto, concepto
			from producto_cliente clie, producto prod
			where clie.id_producto = prod.id_producto and id_cliente = "'.$Id_Cliente.'"
			order by producto asc
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Productos[$Fila['id_prod_clie']]['pro'] = $Fila['producto'];
			$Productos[$Fila['id_prod_clie']]['con'] = $Fila['concepto'];
			$Productos[$Fila['id_prod_clie']]['tot'] = 0;
			$Productos[$Fila['id_prod_clie']]['can'] = 0;
			$Id_Prod_Clie[] = $Fila['id_prod_clie'];
		}
		
		
		if(0 < count($Id_Prod_Clie))
		{
			//Pedido_Productos
			$Consulta = '
				select id_prod_clie, sum(precio * cantidad) as total, sum(cantidad) as cantidad
				from pedido ped, producto_pedido ppdd
				where ped.id_pedido = ppdd.id_pedido and fecha_reale >= "'.$anho.'-'.$mes.'-01"
				and fecha_reale <= "'.$anho.'-'.$mes.'-31"
				and id_prod_clie in ('.implode(',', $Id_Prod_Clie).')
				group by id_prod_clie
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Productos[$Fila['id_prod_clie']]['tot'] = $Fila['total'];
				$Productos[$Fila['id_prod_clie']]['can'] = $Fila['cantidad'];
			}
		}
				
		
		//Materiales utilizados
		$Consulta = '
			select mate.id_inventario_material as iim, sum(cantidad) as cantidad,
			nombre_material, tipo
			from procesos proc, pedido ped, pedido_material mate, inventario_material inve
			where proc.id_proceso = ped.id_proceso and ped.id_pedido = mate.id_pedido
			and mate.id_inventario_material = inve.id_inventario_material
			and fecha_reale >= "'.$anho.'-'.$mes.'-01" and fecha_reale <= "'.$anho.'-'.$mes.'-31"
			and id_cliente = "'.$Id_Cliente.'" and id_tipo_trabajo != 4
			group by mate.id_inventario_material
			order by nombre_material asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Materiales = array('normal' => array(), 'repro' => array());
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales['normal'][] = $Fila;
		}
		
		
		
		//Materiales utilizados (Reprocesos)
		$Consulta = '
			select mate.id_inventario_material as iim, sum(cantidad) as cantidad,
			nombre_material, tipo
			from procesos proc, pedido ped, pedido_material mate, inventario_material inve
			where proc.id_proceso = ped.id_proceso and ped.id_pedido = mate.id_pedido
			and mate.id_inventario_material = inve.id_inventario_material
			and fecha_reale >= "'.$anho.'-'.$mes.'-01" and fecha_reale <= "'.$anho.'-'.$mes.'-31"
			and id_cliente = "'.$Id_Cliente.'" and id_tipo_trabajo = 4
			group by mate.id_inventario_material
			order by nombre_material asc
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Materiales['repro'][] = $Fila;
		}
		
		
		foreach($Productos as $Prod)
		{
			if(0 < $Prod['tot'])
			{
				$Pedido_Productos[] = $Prod;
			}
		}
		
		
		
		
		
		$Consulta = '
			select sum(tiempo) as tiempo, tiem.id_usuario, usuario
			from procesos proc, pedido ped, pedido_tiempos tiem, usuario usu
			where proc.id_proceso = ped.id_proceso and ped.id_pedido = tiem.id_pedido
			and tiem.id_usuario = usu.id_usuario and id_cliente = "'.$Id_Cliente.'"
			and fecha_reale >= "'.$anho.'-'.$mes.'-01" and fecha_reale <= "'.$anho.'-'.$mes.'-31"
			and id_dpto in (2, 3, 5, 6, 9, 17)
			group by tiem.id_usuario
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		$Tiempos = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Tiempos[] = $Fila;
		}
		
		
		
		return array(
			'coti' => $Pedido_Productos,
			'mate' => $Materiales,
			'tiem' => $Tiempos
		);
		
	}
}

/* Fin del archivo */