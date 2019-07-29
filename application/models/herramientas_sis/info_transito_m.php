<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_transito_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
		Nos mostrara informacion especifica de los pedidos
		en transito de un material.
	*/
	function buscar_info_transito($Id_inventario_material, $todos, $orden)
	{
		$Info = array();
		
		if($todos == 'si')
		{
			$SQL = '';
		}
		elseif($todos == '')
		{
			$SQL = 'and tran.finalizado = "n"';
		}
		//Consulta extraer la informacion del pedido en transito solicitado.
		$Consulta = 'select cantidad, tran.orden, mate.numero_individual,
								tran.cantidad_solicitada, mate.id_inventario_material, tran.finalizado,
								tran.detalle, tran.tipo, mate.numero_cajas, tran.fecha_ingreso
								from inventario_material mate, pedido_transito tran
								where mate.id_inventario_material = tran.id_inventario_material
								'.$SQL.'
								and tran.id_grupo = "'.$this->session->userdata["id_grupo"].'"
								and mate.id_inventario_material = "'.$Id_inventario_material.'"
								order by tran.id_ped_transito asc
								';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
	
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Orden = $Datos['orden'];
				$Info[$Orden]['orden'] = $Orden;
				$Info[$Orden]['detalle'] = $Datos['detalle'];
				$Info[$Orden]['cantidad_solicitada'] = $Datos['cantidad_solicitada'];
				$Info[$Orden]['cantidad_placas'] = number_format((($Datos['cantidad'] / $Datos['numero_individual']) / $Datos['numero_cajas']), 2);
				$Info[$Orden]['id_inventario_material'] = $Datos['id_inventario_material'];
				$Info[$Orden]['fecha_ingreso'] = $Datos['fecha_ingreso'];
				$Info[$Orden]['finalizado'] = $Datos['finalizado'];
				$Info[$Orden]['tipo'] = $Datos['tipo'];
				
				//Consulta para extraer el historial del pedido en transito.
				$Consulta = 'select * from
										pedido_transito_cantidad cant, pedido_transito trans
										where cant.orden = "'.$Orden.'"
										and cant.id_inventario_material = "'.$Id_inventario_material.'"
										and cant.orden = trans.orden
										and trans.id_inventario_material = cant.id_inventario_material
										order by cant.id_ped_tran_cantidad';
										
				//echo $Consulta;					
				//Ejecutamos la consulta.
				$Resultado = $this->db->query($Consulta);
				$Info[$Orden]['detalles'] = array();
				//Asignamos la informacion a un array.
				foreach($Resultado->result_array() as $Datos_cantidad)
				{
					$Info[$Orden]['detalles'][$Datos_cantidad['id_ped_tran_cantidad']]['cant_anterior'] = $Datos_cantidad['cant_anterior'];
					$Info[$Orden]['detalles'][$Datos_cantidad['id_ped_tran_cantidad']]['cant_ingresar'] = $Datos_cantidad['cant_ingresar'];
					$Info[$Orden]['detalles'][$Datos_cantidad['id_ped_tran_cantidad']]['fecha'] = $Datos_cantidad['fecha'];
					$Info[$Orden]['detalles'][$Datos_cantidad['id_ped_tran_cantidad']]['restante'] = $Datos_cantidad['cant_anterior'];
				}
			}
		}
		//print_r($Info);
		return $Info;
	}

	
	
	
	/*
		Permitira almacenar la informacion de las cantidades, que ingresan a la bodega.
		en transito de un material.
	*/
	function agregar_cant_transito($Orden, $Cantidad_original, $Cantidad_ingresar)
	{
		$Fecha = date("Y-m-d");
		$SQL = '';
		//Consulta extraer la informacion del material solicitado.
		$Consulta = 'INSERT INTO
								pedido_transito_cantidad values("null", "'.$Orden.'",
								"'.$Cantidad_original.'", "'.$Cantidad_ingresar.'", "'.$Fecha.'"
								)
								';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		
		if($Cantidad_ingresar > $Cantidad_original)
		{
			return 'cant';
		}
		else
		{
			$Total = $Cantidad_original - $Cantidad_ingresar;
			
			if($Total == 0)
			{
				$SQL = ', finalizado = "s" ';
			}
			
			$Consulta_update = 'UPDATE pedido_transito set cantidad = "'.$Total.'" '.$SQL.'
									where orden = "'.$Orden.'"
									and finalizado = "n"';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta_update);
			
			return 'ok';
		}
	}
	
	
	
	/*
		Nos mostrara informacion adicional del material seleccionado.\
	*/
	function info_adicional($Id_inventario_material)
	{
		//Consulta extraer la informacion del material solicitado.
		$Consulta = 'select prov.proveedor_nombre, mate.nombre_material, deta.numero_individual, deta.numero_total,
								deta.tamanho, tipo.nombre_tipo, deta.tipo
								from inventario_material mate, inventario_material_detalle deta, inventario_proveedor prov, plancha_tipo tipo
								where mate.id_inventario_material = "'.$Id_inventario_material.'"
								and mate.id_inventario_material = deta.id_inventario_material
								and prov.id_inventario_proveedor = deta.id_inventario_proveedor
								and tipo.cod_tipo = deta.cod_tipo';
		//echo $Consulta;
		//Ejecutamos la consulta.
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