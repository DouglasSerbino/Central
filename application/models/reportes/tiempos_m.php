<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Busca en la base de datos el nombre de cliente
	 *@return array.
	*/
	function clientes($id_cliente)
	{
		//Establecemos la consulta para extraer la informacion
		
		$Consulta = '
								select nombre from cliente
								where id_cliente = "'.$id_cliente.'"
								and id_grupo = "'.$this->session->userdata('id_grupo').'"
							';
		
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		//Verificamos si hay informacion que regresar.
		if(0 < $Resultado->num_rows())
		{
			//Si la hay regresamos el array.
			return $Resultado->row_array();
		}
		else
		{
			//Si no hay informacion regresamos un array.
			return array();
		}
	}
	
	/**
	 *Busca los pedidos que son reprocesos.
	 *@return array.
	*/
	function pedidos($id_cliente, $Fecha_Inicio, $Fecha_Fin, $Tipo_Tiempo)
	{
		
		$Grupo = $this->session->userdata('id_grupo');
		
		
		
		if('pendiente' == $Tipo_Tiempo)
		{
			$Consulta = '
				select proc.proceso, proc.nombre, cli.codigo_cliente, ped.id_pedido
				from procesos proc, pedido ped, cliente cli, pedido_sap sapo
				where cli.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = sapo.id_pedido and confirmada != "si" and venta > 0
				and fecha_reale >= "'.$Fecha_Inicio.'" and fecha_reale <= "'.$Fecha_Fin.'"
				and proc.id_cliente = "'.$id_cliente.'" and cli.id_grupo = "'.$Grupo.'"
			';
		}
		
		if('cambios' == $Tipo_Tiempo)
		{
			$Consulta = '
				select proc.proceso, proc.nombre, cli.codigo_cliente, ped.id_pedido
				from procesos proc, pedido ped, cliente cli, pedido_sap sapo
				where cli.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = sapo.id_pedido and venta = 0 and id_tipo_trabajo != 4
				and fecha_reale >= "'.$Fecha_Inicio.'" and fecha_reale <= "'.$Fecha_Fin.'"
				and proc.id_cliente = "'.$id_cliente.'" and cli.id_grupo = "'.$Grupo.'"
			';
		}
		
		if('reprocesos' == $Tipo_Tiempo)
		{
			$Consulta = '
				select proc.proceso, proc.nombre, cli.codigo_cliente, ped.id_pedido
				from procesos proc, pedido ped, cliente cli
				where cli.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and fecha_reale >= "'.$Fecha_Inicio.'" and fecha_reale <= "'.$Fecha_Fin.'"
				and proc.id_cliente = "'.$id_cliente.'" and cli.id_grupo = "'.$Grupo.'"
				and id_tipo_trabajo = "4"
			';
		}
		
		if('todos' == $Tipo_Tiempo)
		{
			$Consulta = '
				select proc.proceso, proc.nombre, cli.codigo_cliente, ped.id_pedido
				from procesos proc, pedido ped, cliente cli
				where cli.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and fecha_reale >= "'.$Fecha_Inicio.'" and fecha_reale <= "'.$Fecha_Fin.'"
				and proc.id_cliente = "'.$id_cliente.'" and cli.id_grupo = "'.$Grupo.'"
			';
		}
		
		
		$Resultado = $this->db->query($Consulta);
		
		return $Resultado->result_array();
		
	}
	
	
	/**
	 *Busca la informacion general de todos los usuarios que realizaron el trabajo.
	 *@param string $id_pedido.
	 *@return array.
	*/
	function info_productos($id_cliente, $Fecha_Inicio, $Fecha_Fin, $Cambio)
	{
		$info = array();
		$Grupo = $this->session->userdata('id_grupo');
		
			//Establecemos la consulta para extraer la informacion
			//relacionada al proceso.
			$Consulta = '
								select prod.id_producto, producto, ped.id_pedido,
									prodp.precio, prodp.cantidad
								from procesos proc, pedido ped, producto_pedido prodp,
								producto_cliente prodc, producto prod
								where proc.id_proceso = ped.id_proceso and ped.id_pedido = prodp.id_pedido
									and prodp.id_prod_clie = prodc.id_prod_clie and prodc.id_producto = prod.id_producto
									and proc.id_cliente = "'.$id_cliente.'"
									and ped.fecha_reale != "0000-00-00"
									and fecha_reale >= "'.$Fecha_Inicio.'"
									and fecha_reale <= "'.$Fecha_Fin.'"
								order by fecha_reale asc
							';
			//echo $Consulta;
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			
			$Result = $Resultado->result_array();
			
			foreach($Result as $Datos_productos)
			{
				
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['id_pedido'] = $Datos_productos['id_pedido'];
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['producto'] = $Datos_productos['producto'];
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['id_producto'] = $Datos_productos['id_producto'];
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['precio'] = $Datos_productos['precio'];
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['cantidad'] = $Datos_productos['cantidad'];
				$info[$Datos_productos['id_pedido']][$Datos_productos['id_producto']]['total'] = floatval($Datos_productos['cantidad']) * floatval($Datos_productos['precio']);
			}
			//print_r($info);
		return $info;
	}

	
	/**
	 *Busca la informacion general de todos los materiales que se utilizaron
	 *en la realizacion del proceso o pedido.
	 *@param string $id_pedido.
	 *@return array.
	 */
	function info_materiales($pedidos)
	{
		$info = array();
		foreach($pedidos as $Datos)
		{
			$id_pedido = $Datos['id_pedido'];
			//Establecemos la consulta para extraer la informacion
			//relacionada al proceso.
			$Consulta = '
								select invent_mat.codigo_sap, invent_mat.nombre_material,
								ped_mat.cantidad, invent_mat.tipo, ped.id_pedido
								from pedido ped, pedido_material ped_mat, inventario_material invent_mat
								where ped_mat.id_pedido = "'.$id_pedido.'"
								and ped_mat.id_pedido = ped.id_pedido
								and ped_mat.id_inventario_material = invent_mat.id_inventario_material
								and invent_mat.id_grupo = "'.$this->session->userdata('id_grupo').'"
							';
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$Result = $Resultado->result_array();
			if(count($Result) != 0)
			{
				foreach($Result as $Datos_pedido)
				{
					$info[$Datos_pedido['id_pedido']]['id_pedido'] = $Datos_pedido['id_pedido'];
					$info[$Datos_pedido['id_pedido']]['codigo_sap'] = $Datos_pedido['codigo_sap'];
					$info[$Datos_pedido['id_pedido']]['nombre_material'] = $Datos_pedido['nombre_material'];
					$info[$Datos_pedido['id_pedido']]['cantidad'] = $Datos_pedido['cantidad'];
					$info[$Datos_pedido['id_pedido']]['tipo'] = $Datos_pedido['tipo'];
				}
			}
		}
		
		return $info;
	}
}

/* Fin del archivo */