<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reprocesos_det_m extends CI_Model {
	
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
		//echo $Consulta;
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
	function pedidos_reprocesos($id_cliente, $mes, $anho, $razon_reproceso)
	{
		$Info = array();
		if($mes == 'anual')
		{
			$fecha1 = $anho.'-01-01';
			$fecha2 = $anho.'-12-31';
		}
		else
		{
			$fecha1 = $anho.'-'.$mes.'-01';
			$fecha2 = $anho.'-'.$mes.'-31';
		}
		
		$SQL = '';
		if('todos' != $razon_reproceso)
		{
			$SQL .= ' and ped.id_repro_deta = "'.$razon_reproceso.'"';
		}
		
			$Consulta = '
								select proc.id_cliente, cli.codigo_cliente,
								proc.proceso, proc.nombre, ped.id_pedido,
								ped.fecha_entrada, ped.fecha_entrega, ped.fecha_reale
								from procesos proc, pedido ped, cliente cli
								where proc.id_proceso = ped.id_proceso
								and proc.id_cliente = cli.id_cliente
								and ped.fecha_entrega >= "'.$fecha1.'" 
								and ped.fecha_entrega <= "'.$fecha2.'" 
								and ped.id_tipo_trabajo = "4"
								'.$SQL.'
								and proc.id_cliente = "'.$id_cliente.'"
								and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
								order by proc.id_proceso
							';
			//echo $Consulta;
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$a = 0;
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Datos)
				{

					$Consulta = 'select repro.detalle from pedido ped, reproceso_detalle repro
											where repro.id_repro_deta = ped.id_repro_deta
											and ped.id_pedido in ("'.$Datos['id_pedido'].'")
											'.$SQL;
					$Resultado = $this->db->query($Consulta);
					$Info[$a]['detalle'] = '';
					if(0 < $Resultado->num_rows())
					{
						$Detalle = $Resultado->row_array();
						$Info[$a]['detalle'] = $Detalle['detalle'];
					}
					$Info[$a]['id_cliente'] = $Datos['id_cliente'];
					$Info[$a]['codigo_cliente'] = $Datos['codigo_cliente'];
					$Info[$a]['proceso'] = $Datos['proceso'];
					$Info[$a]['nombre'] = $Datos['nombre'];
					$Info[$a]['id_pedido'] = $Datos['id_pedido'];
					$Info[$a]['fecha_entrada'] = $Datos['fecha_entrada'];
					$Info[$a]['fecha_entrega'] = $Datos['fecha_entrega'];
					$Info[$a]['fecha_reale'] = $Datos['fecha_reale'];

					$a++;
				}
			}
		return $Info;
	}
	
	
	/**
	 *Busca la informacion general de todos los usuarios que realizaron el trabajo.
	 *@param string $id_pedido.
	 *@return array.
	*/
	function info_general($pedidos)
	{
		$info = array();
		//print_r($pedidos);
		if(0 < count($pedidos))
		{
			foreach($pedidos as $Datos)
			{
				
				$id_pedido = $Datos['id_pedido'];
				//echo $id_pedido.'<br>';
				$Grupo = $this->session->userdata('id_grupo');
				
				//Establecemos la consulta para extraer la informacion
				//relacionada al proceso.
				
				
				$Consulta = '
									select sum(ped_tie.tiempo) as tiempo_usuario, ped_tie.id_tiempo,
									usu.puesto, usu.usuario, inicio, fin, ped.id_pedido, usu.id_usuario
									from pedido_tiempos ped_tie, usuario usu,
									pedido ped, departamentos dpto
									where ped.id_pedido = ped_tie.id_pedido
									and usu.id_usuario = ped_tie.id_usuario
									and ped_tie.id_pedido = "'.$id_pedido.'"
									and usu.id_grupo = "'.$Grupo.'"
									and usu.id_dpto = dpto.id_dpto
									and dpto.codigo != "Gerencia" and dpto.codigo != "Plani"
									and dpto.codigo != "Sistemas" and dpto.codigo != "Ventas"
									and dpto.codigo != "SAP"
									group by usu.id_usuario
									order by ped_tie.id_tiempo
								';
				//echo $Consulta.'<br><br>';
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				
				$Result = $Resultado->result_array();
				foreach($Result as $Datos_pedido)
				{
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['id_pedido'] = $Datos_pedido['id_pedido'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['id_usuario'] = $Datos_pedido['id_usuario'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['tiempo_usuario'] = $Datos_pedido['tiempo_usuario'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['puesto'] = $Datos_pedido['puesto'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['usuario'] = $Datos_pedido['usuario'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['inicio'] = $Datos_pedido['inicio'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['id_tiempo']]['fin'] = $Datos_pedido['fin'];
				}
			}
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
		if(0 < count($pedidos))
		{
			foreach($pedidos as $Datos)
			{
				$id_pedido = $Datos['id_pedido'];
				//Establecemos la consulta para extraer la informacion
				//relacionada al proceso.
				$Consulta = '
									select invent_mat.codigo_sap, invent_mat.nombre_material,
									ped_mat.cantidad, invent_mat.tipo, ped.id_pedido, invent_mat.id_inventario_material
									from pedido ped, pedido_material ped_mat, inventario_material invent_mat
									where ped_mat.id_pedido = "'.$id_pedido.'"
									and ped_mat.id_pedido = ped.id_pedido
									and ped_mat.id_inventario_material = invent_mat.id_inventario_material
									and invent_mat.id_grupo = "'.$this->session->userdata('id_grupo').'"
								';
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				$Result = $Resultado->result_array();
				
				foreach($Result as $Datos_pedido)
				{
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['id_pedido'] = $Datos_pedido['id_pedido'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['id_inventario_material'] = $Datos_pedido['id_inventario_material'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['codigo_sap'] = $Datos_pedido['codigo_sap'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['nombre_material'] = $Datos_pedido['nombre_material'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['cantidad'] = $Datos_pedido['cantidad'];
					$info[$Datos_pedido['id_pedido']][$Datos_pedido['codigo_sap']]['tipo'] = $Datos_pedido['tipo'];
				}
				
			}
		}
		return $info;
	}
}

/* Fin del archivo */