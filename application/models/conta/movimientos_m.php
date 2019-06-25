<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movimientos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function listar(
		$Activo = "s",
		$Inicio = 0,
		$Id_Linea,
		$Limite = true,
		$Fecha = '',
		$MC_Pago = ''
	)
	{
		
		$Condicion = '';
		
		if('s' == $Activo || 'n' == $Activo)
		{
			$Condicion .= ' and mclm.activo = "'.$Activo.'" ';
		}
		
		if('todos' != $Id_Linea)
		{
			$Condicion .= ' and linea.id_mc_linea = "'.$Id_Linea.'" ';
		}
		
		if('pend' == $MC_Pago)
		{
			$Condicion .= ' and codigo_pago = "" ';
		}
		
		if('canc' == $MC_Pago)
		{
			$Condicion .= ' and codigo_pago != "" ';
		}
		
		if('' != $Fecha)
		{
			$Condicion .= ' and fecha >= "'.$Fecha.'-01" and fecha <= "'.$Fecha.'-31" ';
		}
		
		$Consulta = '
			select id_mc_linea_movimiento as imlm, codigo, linea, fecha, descripcion, monto,
			factura, pedido, mas_menos, codigo_pago, tipo_pago
			from mc_linea_movimiento as mclm, mc_linea linea
			Where mclm.id_mc_linea = linea.id_mc_linea
			'.$Condicion.'
			order by fecha desc, id_mc_linea_movimiento desc
		';
		
		if($Limite)
		{
			$Consulta .= '
				limit '.$Inicio.', 60
			';
		}
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		$Movimientos = array();
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Movimientos[$Fila['imlm']] = $Fila;
			
			/*
			$Movimientos[$Fila['imlm']]['linea'] = $Fila['linea'];
			$Movimientos[$Fila['imlm']]['fecha'] = $Fila['fecha'];
			$Movimientos[$Fila['imlm']]['monto'] = $Fila['monto'];
			$Movimientos[$Fila['imlm']]['pedido'] = $Fila['pedido'];
			$Movimientos[$Fila['imlm']]['codigo'] = $Fila['codigo'];
			$Movimientos[$Fila['imlm']]['factura'] = $Fila['factura'];
			$Movimientos[$Fila['imlm']]['mas_menos'] = $Fila['mas_menos'];
			$Movimientos[$Fila['imlm']]['codigo_pago'] = $Fila['codigo_pago'];
			$Movimientos[$Fila['imlm']]['descripcion'] = $Fila['descripcion'];
			*/
		}
		
		
		
		
		
		
		if('' != $Fecha)
		{
			
			if('19' == $Id_Linea)
			{
				//Venta por mes
				$Consulta = '
					select venta, fecha, ped.id_pedido, proceso, proc.nombre, codigo_cliente
					from cliente clie, procesos proc, pedido ped, pedido_sap sapo
					where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
					and ped.id_pedido = sapo.id_pedido and venta != 0
					and fecha >= "'.$Fecha.'-01" and fecha <= "'.$Fecha.'-31" 
				';
				
				$Resultado = $this->db->query($Consulta);
				
				foreach($Resultado->result_array() as $Fila)
				{
					$Movimientos['v'.$Fila['id_pedido']]['linea'] = '101';
					$Movimientos['v'.$Fila['id_pedido']]['fecha'] = $Fila['fecha'];
					$Movimientos['v'.$Fila['id_pedido']]['monto'] = $Fila['venta'];
					$Movimientos['v'.$Fila['id_pedido']]['codigo'] = $Fila['codigo_cliente'].'-'.$Fila['proceso'];
					$Movimientos['v'.$Fila['id_pedido']]['pedido'] = '';//['cantidad'] = 1;
					$Movimientos['v'.$Fila['id_pedido']]['mas_menos'] = $Fila['id_pedido'];
					$Movimientos['v'.$Fila['id_pedido']]['descripcion'] = $Fila['nombre'];
					$Movimientos['v'.$Fila['id_pedido']]['factura'] = '';//['precio_unitario'] = $Fila['venta'];
				}
			}
			
			
			if('25' == $Id_Linea)
			{
				//Materias primas por mes
				$Consulta = '
					select sum(cantidad * valor) as costo, fecha_entrega, ped.id_pedido, proceso,
					proc.nombre, codigo_cliente, mate.id_inventario_material
					from cliente clie, procesos proc, pedido ped, pedido_material pema, inventario_material mate
					where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
					and ped.id_pedido = pema.id_pedido
					and pema.id_inventario_material = mate.id_inventario_material
					and fecha_entrega >= "'.$Fecha.'-01" and fecha_entrega <= "'.$Fecha.'-31"
					group by ped.id_pedido
				';
				$Resultado = $this->db->query($Consulta);
				
				foreach($Resultado->result_array() as $Fila)
				{
					$Movimientos['v'.$Fila['id_pedido']]['linea'] = '109';
					$Movimientos['v'.$Fila['id_pedido']]['fecha'] = $Fila['fecha_entrega'];
					$Movimientos['v'.$Fila['id_pedido']]['monto'] = $Fila['costo'];
					$Movimientos['v'.$Fila['id_pedido']]['codigo'] = $Fila['codigo_cliente'].'-'.$Fila['proceso'];
					$Movimientos['v'.$Fila['id_pedido']]['pedido'] = '';//['cantidad'] = 1;
					$Movimientos['v'.$Fila['id_pedido']]['mas_menos'] = $Fila['id_pedido'];
					$Movimientos['v'.$Fila['id_pedido']]['descripcion'] = $Fila['nombre'];
					$Movimientos['v'.$Fila['id_pedido']]['factura'] = '';//['precio_unitario'] = $Fila['costo'];
				}
			}
		}
		
		
		
		return $Movimientos;
		
	}
	
	
	function total($Activo = "s", $Id_Linea = 'todos')
	{
		
		$Condicion = '';
		
		if('s' == $Activo || 'n' == $Activo)
		{
			$Condicion .= ' and mclm.activo = "'.$Activo.'" ';
		}
		
		if('todos' != $Id_Linea)
		{
			$Condicion .= ' and linea.id_mc_linea ="'.$Id_Linea.'"';
		}
		
		$Consulta = '
			select count(id_mc_linea_movimiento) as total
			from mc_linea_movimiento as mclm, mc_linea linea
			Where mclm.id_mc_linea = linea.id_mc_linea
			'.$Condicion.'
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		$Total = $Resultado->row_array();
		
		return $Total['total'];
		
	}
	
	
	
	function agregar($Movimientos)
	{
		
		foreach($Movimientos as $Index => $Datos)
		{
			
			$Consulta = '
				insert into mc_linea_movimiento values(
					NULL,
					"'.$Movimientos[$Index]['mov_linea'].'",
					"'.$Movimientos[$Index]['mov_fecha'].'",
					"'.$Movimientos[$Index]['mov_descripcion'].'",
					"'.$Movimientos[$Index]['mov_monto'].'",
					"'.$Movimientos[$Index]['mov_factura'].'",
					"'.$Movimientos[$Index]['mov_pedido'].'",
					"'.$this->session->userdata('id_usuario').'",
					"s",
					"0",
					"0",
					"",
					""
				)
			';
			
			//Ejecuto la consulta
			$this->db->query($Consulta);
			
		}
		
	}
	
	
	
	function eliminar($Id_Mc_Movimiento)
	{
		
		$Consulta = '
			update mc_linea_movimiento set
			activo = "n", id_usu_elimina = "'.$this->session->userdata('id_usuario').'"
			where id_mc_linea_movimiento = "'.$Id_Mc_Movimiento.'"
		';
		
		//Ejecuto la consulta
		$this->db->query($Consulta);
		
	}
	
	
	
	function confirmar($Id_Linea, $NTransaccion, $Tipo)
	{
		
		$Consulta = '
			update mc_linea_movimiento
			set id_usuario_pago = "'.$this->session->userdata('id_usuario').'",
			codigo_pago = "'.$NTransaccion.'", tipo_pago = "'.$Tipo.'"
			where id_mc_linea_movimiento = "'.$Id_Linea.'"
		';
		
		$this->db->query($Consulta);
		
	}
	
	
}

/* Fin del archivo */