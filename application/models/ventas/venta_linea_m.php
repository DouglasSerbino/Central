<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_linea_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Ventas por linea
	*/
	function venta_linea($anho, $mes, $condicion, $id_material,  $Id_Cliente = '', $Divis = '')
	{
		if($condicion == 'lineal')
		{
			$Consulta_SQL = 'and s.fecha >= "'.$anho.'-01-01"
							and s.fecha <= "'.$anho.'-12-31"
							and mate.id_material_solicitado = "'.$id_material.'"';
		}
		
		if($condicion == '')
		{
			$Consulta_SQL = 'and s.fecha >= "'.$anho.'-'.$mes.'-01"
									and s.fecha <= "'.$anho.'-'.$mes.'-31"';
		}
		
		if($condicion == 'rango_fechas')
		{
			$Consulta_SQL = 'and s.fecha >= "'.$anho.'-'.$mes.'-01"
									and s.fecha <= "'.$anho.'-'.$mes.'-31"
									and mate.id_material_solicitado = "'.$id_material.'"';
		}
		
		if('todos' != $Id_Cliente and '' != $Id_Cliente)
		{
			$Consulta_SQL.= ' and cli.id_cliente = "'.$Id_Cliente.'" ';
		}
		
		//Total de clientes
		// $Consulta = '
		// 				SELECT 
		// 					m.id_material_solicitado,
		// 					m.material_solicitado, 
		// 					s.fecha,
		// 					(pp.precio*pp.cantidad) as total,
		// 					cli.codigo_cliente, ps.proceso, ps.nombre
		// 				FROM 
		// 					material_solicitado m, 
		// 					producto p,
		// 					producto_cliente pc, 
		// 					procesos ps, 
		// 					cliente c,
		// 					producto_pedido pp, 
		// 					pedido pd, 
		// 					pedido_sap s
		// 				WHERE 
		// 					and m.id_material_solicitado = p.id_material
		// 					and p.id_producto = pc.id_producto
		// 					and ps.id_proceso = pd.id_proceso
		// 					and ps.id_cliente = c.id_cliente
		// 					and pc.id_prod_clie = pp.id_prod_clie
		// 					and pp.id_pedido = pd.id_pedido
		// 					and pd.id_pedido = s.id_pedido					
		// 					and confirmada = "si"'.$Consulta_SQL.' 
		// 					and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
		// 					order by m.id_material_solicitado asc
		// 			';

		$Consulta = '
		SELECT m.id_material_solicitado,
			   m.material_solicitado,
			   s.fecha,
			   (pp.precio*pp.cantidad) as total,
			   c.codigo_cliente,
			   ps.proceso,
			   ps.nombre
		FROM material_solicitado m 
		INNER JOIN producto p ON m.id_material_solicitado = p.id_material
		INNER JOIN producto_cliente pc ON p.id_producto = pc.id_producto
		INNER JOIN cliente c ON pc.id_prod_clie = c.id_cliente
		INNER JOIN procesos ps ON c.id_cliente = ps.id_cliente
		INNER JOIN producto_pedido pp ON pp.id_producto = p.id_producto
		INNER JOIN pedido pd ON pp.id_pedido = pd.id_pedido
		INNER JOIN pedido_sap s ON pd.id_pedido = s.id_pedido	
		WHERE confirmada = "si"'.$Consulta_SQL.' 
							and c.id_grupo = "'.$this->session->userdata('id_grupo').'"
							order by m.id_material_solicitado asc




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
	
	/**
	 *Funcion para extraer los totales de las ventas por lineas.
	 *Y asi calcular el porcentaje de estas ventas.
	*/
	function porcentaje_linea($anho, $mes, $condicion, $id_material, $Id_Cliente = '')
	{
		
		if($condicion == 'lineal')
		{
			$Consulta_SQL = 'and pd.fecha_reale >= "'.$anho.'-01-01"
										and pd.fecha_reale <= "'.$anho.'-12-31"
										and m.id_material_solicitado = "'.$id_material.'"';
		}
		if($condicion == '')
		{
			$Consulta_SQL = 'and pd.fecha_reale >= "'.$anho.'-'.$mes.'-01"
									and pd.fecha_reale <= "'.$anho.'-'.$mes.'-31"';
		}
		if($condicion == 'rango_fechas')
		{
			$Consulta_SQL = 'and s.fecha >= "'.$anho.'-'.$mes.'-01"
									and s.fecha <= "'.$anho.'-'.$mes.'-31"
									and m.id_material_solicitado = "'.$id_material.'"';
		}
		
		if('todos' != $Id_Cliente)
		{
			$Consulta_SQL.= ' and c.id_cliente = "'.$Id_Cliente.'" ';
		}
		
		
		// $Consulta = '
		// 					SELECT 
		// 						mate.id_material_solicitado, 
		// 						mate.material_solicitado,
		// 						(prodp.precio*prodp.cantidad) as total, 
		// 						ped.fecha_reale,
		// 						cli.codigo_cliente, 
		// 						proc.proceso, 
		// 						proc.nombre
		// 					FROM 
		// 						material_solicitado mate, 
		// 						producto prod, 
		// 						producto_cliente prodc,
		// 						producto_pedido prodp, 
		// 						pedido ped, 
		// 						pedido_sap sap,
		// 						procesos proc, 
		// 						cliente cli
		// 					WHERE mate.id_material_solicitado = prod.id_material
		// 						and prod.id_producto = prodc.id_producto
		// 						and proc.id_proceso = ped.id_proceso
		// 						and proc.id_cliente = cli.id_cliente
		// 						and prodc.id_prod_clie = prodp.id_prod_ped
		// 						and prodp.id_pedido = ped.id_pedido
		// 						and ped.id_pedido = sap.id_pedido
		// 						and sap.fecha = "0000-00-00"
		// 						'.$Consulta_SQL.'
		// 						and ped.fecha_reale != "0000-00-00"
		// 						and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
		// 					order by mate.id_material_solicitado asc
		// 				';
		$Consulta = '
							SELECT 
								m.id_material_solicitado, 
								m.material_solicitado,
								(pp.precio*pp.cantidad) as total, 
								pd.fecha_reale,
								c.codigo_cliente, 
								ps.proceso, 
								ps.nombre
							FROM material_solicitado m 
							INNER JOIN producto p ON  m.id_material_solicitado = p.id_material
							INNER JOIN producto_cliente pc ON pc.id_producto = p.id_producto  
							INNER JOIN cliente c ON pc.id_prod_clie = c.id_cliente
							INNER JOIN producto_pedido pp ON pp.id_producto = p.id_producto
							INNER JOIN pedido pd ON pp.id_pedido = pd.id_pedido
							INNER JOIN pedido_sap s ON pd.id_pedido = s.id_pedido
							INNER JOIN procesos ps ON c.id_cliente = ps.id_cliente
							WHERE s.fecha = "0000-00-00" '.$Consulta_SQL.'
								AND pd.fecha_reale != "0000-00-00"
								AND c.id_grupo = "'.$this->session->userdata('id_grupo').'"
							ORDER BY m.id_material_solicitado ASC
						';
						
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			return $Fila = $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
	
	
	/**
	 *Clientes que se le ha hecho  ventas en el mes.
	*/
	function venta_linea_cliente($anho, $mes, $condicion, $id_material)
	{
		if($condicion == 'lineal')
		{
			$Consulta_SQL = 'and sap.fecha >= "'.$anho.'-01-01"
							and sap.fecha <= "'.$anho.'-12-31"
							and mate.id_material_solicitado = "'.$id_material.'"';
		}
		
		if($condicion == '')
		{
			$Consulta_SQL = 'and sap.fecha >= "'.$anho.'-'.$mes.'-01"
									and sap.fecha <= "'.$anho.'-'.$mes.'-31"';
		}
		
		if($condicion == 'rango_fechas')
		{
			$Consulta_SQL = 'and sap.fecha >= "'.$anho.'-'.$mes.'-01"
									and sap.fecha <= "'.$anho.'-'.$mes.'-31"
									and mate.id_material_solicitado = "'.$id_material.'"';
		}
		
		$Info = array();
		//Total de clientes
		$Consulta = '
						select distinct(cli.codigo_cliente), cli.nombre, cli.id_cliente
						from material_solicitado mate, producto prod,
							producto_cliente prodc, procesos proc, cliente cli,
							producto_pedido prodp, pedido ped, pedido_sap sap
						where mate.id_material_solicitado = prod.id_material
							and prod.id_producto = prodc.id_producto
							and proc.id_proceso = ped.id_proceso
							and proc.id_cliente = cli.id_cliente
							and prodc.id_prod_clie = prodp.id_prod_ped
							and prodp.id_pedido = ped.id_pedido
							and ped.id_pedido = sap.id_pedido					
							and confirmada = "si"
							'.$Consulta_SQL.'
							and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
						order by cli.codigo_cliente asc
					';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Info[$Datos['id_cliente']]['id_cliente'] = $Datos['id_cliente'];
				$Info[$Datos['id_cliente']]['cliente'] = $Datos['codigo_cliente'].'-'.$Datos['nombre'];
			}
			return $Info;
		}
		else
		{
			return array();
		}
	}
	
	
	
	
}

/* Fin del archivo */