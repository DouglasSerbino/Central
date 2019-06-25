<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_lot_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Total de lotes para este material.
	 *@return $Total.
	*/
	function total_lotes($Id_inventario_material)
	{
		
		//Total de lotes
		$Consulta = '
			select count(id_inventario_lote) as total_lotes
			from inventario_lote
			where id_inventario_material = "'.$Id_inventario_material.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Datos_lote = $Resultado->row_array();
		
		$Total_Lotes = $Datos_lote['total_lotes'];
		$Total_Lotes += 0;
		
		return $Total_Lotes;
		
	}
	
	
	/**
	 *Busca en la base de datos la informacion de todos los lotes, segun el
	 *rango que se especifique.
	 *@param string $Inicio.
	 *@return array.
	*/
	function lotes_materiales($Inicio,$Id_inventario_material)
	{
		$Datos = array();
		$Requisicion = array();
		//Consultamos la base de datos para obtener la informacion de los clientes.
		$Consulta = 'select * from inventario_lote
							where id_inventario_material = "'.$Id_inventario_material.'"
							order by id_inventario_lote desc limit '.$Inicio.', 10
		';
		
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Datos_lotes)
		{
			$Datos[$Datos_lotes['id_inventario_lote']]['id_inventario_lote'] = $Datos_lotes['id_inventario_lote'];
			$Datos[$Datos_lotes['id_inventario_lote']]['id_inventario_material'] = $Datos_lotes['id_inventario_material'];
			$Datos[$Datos_lotes['id_inventario_lote']]['pedido'] = $Datos_lotes['pedido'];
			$Datos[$Datos_lotes['id_inventario_lote']]['fecha_ingreso'] = $Datos_lotes['fecha_ingreso'];
			$Datos[$Datos_lotes['id_inventario_lote']]['fecha_fin'] = $Datos_lotes['fecha_fin'];
			$Datos[$Datos_lotes['id_inventario_lote']]['unidades'] = $Datos_lotes['unidades'];
			$Datos[$Datos_lotes['id_inventario_lote']]['estado'] = $Datos_lotes['estado'];
			
			
			//Asignamos el valor de cada id del lote a una variable.
			$Id_lote = $Datos_lotes['id_inventario_lote'];
			//Verificamos cual es el estado de cada lote.
			$estado = $Datos_lotes["estado"];
			
			//Establecemos la consulta para determinar la cantidad que hay por cada lote.
			$Consulta_cantidad = 'select sum(cantidad) as cantidad
									from inventario_requisicion
									where id_inventario_lote = "'.$Id_lote.'"';
			//echo $Consulta_cantidad.'<br>';
			//Ejecuto la consulta
			$Resultado_cant = $this->db->query($Consulta_cantidad);
			
			//Exploramos el array para obtener la cantidad total.
			foreach($Resultado_cant->result_array() as $Datos_cantidad)
			{
				$Datos[$Datos_lotes['id_inventario_lote']]['cantidad'] = $Datos_cantidad['cantidad'];
			}
			
			//Establecemos la consulta para seleccionar el ultimo movimiento para este lote.
			$Consulta_ultima_salida = 'select fecha_salida
																from inventario_requisicion
																where id_inventario_lote = "'.$Id_lote.'"
																order by id_inventario_requisicion desc limit 0,1';
			//Ejecuto la consulta
			$Resultado_salida = $this->db->query($Consulta_ultima_salida);
			
			$Datos[$Datos_lotes['id_inventario_lote']]['fecha_salida'] = '';
			if(0 < count($Resultado_salida->result_array()))
			{
				foreach($Resultado_salida->result_array() as $Datos_salida)
				{
					$Datos[$Datos_lotes['id_inventario_lote']]['fecha_salida'] = $Datos_salida['fecha_salida'];
				}
			}	
			//Establecemos la consulta para obtener la informacion del usuario
			//que realizo la requisicion y la cantidad requisada.
			$Consulta_usuarios = 'SELECT numero_requ, nombre, fecha_salida,
														sum(cantidad) as cantidad
														FROM inventario_requisicion requi, usuario usu
														WHERE requi.id_usuario = usu.id_usuario
														AND id_inventario_lote = "'.$Id_lote.'"
														group by numero_requ
														order by id_inventario_requisicion desc';

			//Ejecuto la consulta
			$Resultado_usu = $this->db->query($Consulta_usuarios);
			
			$Datos[$Datos_lotes['id_inventario_lote']]['req'] = '';
			
			foreach($Resultado_usu->result_array() as $Datos_usuario)
			{
				$Datos[$Datos_lotes['id_inventario_lote']]['req'][$Datos_usuario['numero_requ']]['numero_requ'] = $Datos_usuario['numero_requ'];
				$Datos[$Datos_lotes['id_inventario_lote']]['req'][$Datos_usuario['numero_requ']]['nombre'] = $Datos_usuario['nombre'];
				$Datos[$Datos_lotes['id_inventario_lote']]['req'][$Datos_usuario['numero_requ']]['fecha_salida'] = $Datos_usuario['fecha_salida'];
				$Datos[$Datos_lotes['id_inventario_lote']]['req'][$Datos_usuario['numero_requ']]['cantidad'] = $Datos_usuario['cantidad'];
			}
		}
		return $Datos;
	}
	
	
	function consumo_promedio_mensual($Id_inventario_material, $codigo, $anho)
	{
		//Declaracion de variables
		$info = array();
		$rotacion_general = 0;
		//Selecciono todos los lotes correspondientes a este material para saber cuantos hay
		$Consulta = 'select id_inventario_lote, fecha_ingreso, estado, unidades
								from inventario_lote
								where id_inventario_material = "'.$Id_inventario_material.'"';
		
		$Resultado = $this->db->query($Consulta);
		$rotacion_lote = 0;
		$lotes = 0;
		//print_r($Resultado->result_array());
		//Exploramos el array.
		foreach($Resultado->result_array() as $Datos)
		{
			$i = 0;
			
			$id_lote = $Datos["id_inventario_lote"];
			$unidades = $Datos["unidades"];
			
			//Quiero ver cuantas unidades han salido
			$Consulta_num = 'select id_inventario_requisicion
											from inventario_requisicion
											where id_inventario_lote = '.$id_lote;
											
			$Resultado_num = $this->db->query($Consulta_num);
			$unidades = $Resultado_num->num_rows();
			
			if(0 < $Datos["estado"])
			{
				//Si este lote aun no ha sido finalizado; le agrego la rotacion a la fecha de hoy
				$i = 1;
				$dias_trans = (strtotime(date('Y-m-d')) - strtotime($Datos["fecha_ingreso"]));
				$dias_trans = ((($dias_trans / 60) / 60) / 24);
			}
			else
			{
				//Selecciono el ultimo movimiento para este lote
				$Consulta_mat = "select fecha_salida
											from inventario_requisicion
											where id_inventario_lote = $id_lote
											order by id_inventario_requisicion
											desc limit 0,1";
				$Resultado_mat = $this->db->query($Consulta_mat);
				
				foreach($Resultado_mat->result_array() as $Datos_mat)
				{
					//Calcular los dias transcurridos entre la fecha de ingreso y el ultimo movimiento
					$dias_trans = (strtotime($Datos_mat["fecha_salida"]) - strtotime($Datos["fecha_ingreso"]));		
					$dias_trans = ((($dias_trans / 60) / 60) / 24);
					$i++;
				}
			}
			
			if($i > 0 && $unidades > 0)
			{
				$rotacion_lote += $dias_trans / $unidades;
			}
			else
			{
				if($i > 0)
				{
					$rotacion_lote += $dias_trans;
				}
			}
			$lotes++;
		}
		
		if($lotes > 0)
		{
			$rotacion_general = $rotacion_lote / $lotes;
		}
		
		
		//Regresamos el array.
		return $rotacion_general;
	}
	
	
	function consumoAnual($Inicio,$Id_inventario_material, $anho)
	{
		$Info = array();
		//Establecemos la consulta para determinar la cantidad que hay por cada lote.
		$Consulta_cantidad = 'select sum(cantidad) as cantidad, date_format(ped.fecha_entrega, "%m") as mes
								from pedido_material pedmat, pedido ped
								where ped.id_pedido = pedmat.id_pedido
								and ped.fecha_entrega >= "'.$anho.'-01-01"
								and ped.fecha_entrega <= "'.$anho.'-12-31"
								and pedmat.id_inventario_material = "'.$Id_inventario_material.'"
								group by date_format(ped.fecha_entrega, "%m+0")
								';
		//Ejecuto la consulta
		$Resultado = $this->db->query($Consulta_cantidad);
			
		if(0 < $Resultado->num_rows)
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Info[$Datos['mes']]['cantidad'] = $Datos['cantidad'];
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