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
		
		$Resultado2 = $Resultado->result_array();
		
		
		foreach($Resultado2 as $Datos_lotes)
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
			//Ejecuto la consulta
			$Resultado_cant = $this->db->query($Consulta_cantidad);
			//Asignamos el array a una variable.
			$Resultado_cantidad = $Resultado_cant->result_array();
			//Exploramos el array para obtener la cantidad total.
			foreach($Resultado_cantidad as $Datos_cantidad)
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
			$Resultado_ultima_salida = $Resultado_salida->result_array();
			
			$Datos[$Datos_lotes['id_inventario_lote']]['fecha_salida'] = '';
			if($Resultado_ultima_salida > 0)
			{
				foreach($Resultado_ultima_salida as $Datos_salida)
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
			//Asignamos el array a una variable
			$Resultado_usuarios = $Resultado_usu->result_array();
			
			$Datos[$Datos_lotes['id_inventario_lote']]['req'] = '';
			
			foreach($Resultado_usuarios as $Datos_usuario)
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
		$anho_actual = date("Y");
		$anho_inicio = 2008;
		$anhos = $anho_actual - $anho_inicio;
		$suma = 0;
		$meses_tot = 0;
		$info = array();
		
		//Consulta para extraer las unidades del material seleccionado.
		$Consulta = 'select *
								from inventario_material
								where id_inventario_material = "'.$codigo.'"
								';
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);					
		//Exploramos el array para mostrar la informacion.
		foreach($Resultado->result_array() as $Datos_material)
		{
			//Asignamos el resultado a una variable.
			$unidad = $Datos_material["cantidad_unidad"];
		}
		//Consumo promedio mensual
		//==============================
		//For para extraer el total de meses del que se realizara el reporte.
		for($i = 0; $i<= $anhos; $i++)
		{
			if($i < $anhos)
			{
				$mes_fin = 13;
			}
			else
			{
				$mes_fin = date("m");
			}
			//For para determinar el total de meses.
			for($o = 1; $o < $mes_fin; $o++)
			{
				if($o < 10)
				{
					$o = '0'.$o;
				}
				$Consulta = 'select id_inventario_requisicion
										from inventario_requisicion requi, inventario_lote lote
										where lote.id_inventario_lote = requi.id_inventario_lote
										and id_inventario_material = "'.$Id_inventario_material.'"
										and fecha_salida >= "'.$anho_inicio.'-'.$o.'-01"
										and fecha_salida <= "'.$anho_inicio.'-'.$o.'-31"
										';
				//echo $Consulta.'<br>';
				$Resultado = $this->db->query($Consulta);
				
				$num = $Resultado->num_rows($Consulta);
				$suma = $suma + $num;
				$meses_tot++;
			}
			
			$anho_inicio++;
		}
		
		//Variables que tienen el consumo promedio.
		$info['lote']['cons_prom'] = number_format(($suma / $meses_tot), 2);
		//Variable que contine el consumo promedio por unidad.
		$info['lote']['cons_uni'] = number_format(($info['lote']['cons_prom'] * $unidad), 2);
		
		//Consumo del mes
		//===============================
		$mes = date("m");
		//Consulta para extraer el los inventario de requisicion que correspondan a la fecha actual.
		$Consulta = 'select id_inventario_requisicion
								from inventario_requisicion requi, inventario_lote lote
								where lote.id_inventario_lote = requi.id_inventario_lote
								and id_inventario_material = "'.$Id_inventario_material.'"
								and fecha_salida >= "'.$anho.'-'.$mes.'-01"
								and fecha_salida <= "'.$anho.'-'.$mes.'-31"
								';
		//echo $Consulta.'<br>';
		$Resultado = $this->db->query($Consulta);
		$numero = $Resultado->num_rows($Consulta);
		//COnsumo promedio mensual.
		$info['lote']['consumo'] = number_format(($numero * $unidad), 2);
		
		//Rotacion General Promedio
		//===============================
		
		//Selecciono todos los lotes correspondientes a este material para saber cuantos hay
		$Consulta = 'select id_inventario_lote, fecha_ingreso, estado, unidades
								from inventario_lote
								where id_inventario_material = "'.$Id_inventario_material.'"';
		
		$Resultado = $this->db->query($Consulta);
		$rotacion_lote = 0;
		$lotes = 0;
		//Exploramos el array.
		foreach($Resultado->result_array() as $Datos)
		{
			$i = 0;
			
			list( $year1, $month1, $day1 ) = explode( '-', $Datos["fecha_ingreso"] );
			$id_lote = $Datos["id_inventario_lote"];
			$estado = $Datos["estado"];
			$unidades = $Datos["unidades"];
			
			//Quiero ver cuantas unidades han salido
			$Consulta_num = 'select id_inventario_requisicion
											from inventario_requisicion
											where id_inventario_lote = '.$id_lote;
			$Resultado_num = $this->db->query($Consulta_num);
			$unidades = $Resultado_num->num_rows();
			
			//Si el estado es diferente de cero.
			if($estado != "0")
			{//Si este lote aun no ha sido finalizado; le agrego la rotacion a la fecha de hoy
				$i = 1;
				$dias_fecha1 = mktime(0,0,0,$month1,$day1,$year1);
				$dias_fecha2 = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$seg_trans = $dias_fecha2 - $dias_fecha1;
				$dias_trans = ((($seg_trans / 60) / 60) / 24);
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
					list( $year, $month, $day ) = explode( '-', $Datos_mat["fecha_salida"] );
					
					//Calcular los dias transcurridos entre la fecha de ingreso y el ultimo movimiento
					$dias_fecha1 = mktime(0,0,0,$month1,$day1,$year1);
					$dias_fecha2 = mktime(0,0,0,$month,$day,$year);
					$seg_trans = $dias_fecha2 - $dias_fecha1;
					$dias_trans = ((($seg_trans / 60) / 60) / 24);
					
					$i++;
				}
			}
			
			if($i > 0 && $unidades > 0)
				$rotacion_lote += $dias_trans / $unidades;
			else{
				if($i > 0)
					$rotacion_lote += $dias_trans;
			}
			
			$lotes++;
		}
		//Asignamos la informacion a un array.
		$info['lote']['lotes'] = $lotes;
		$info['lote']['rotacion_lote'] = $rotacion_lote;
		$info['lote']['unidad'] = $unidad;
		$info['lote']['numero'] = $numero;
		
		//Regresamos el array.
		return $info;
	}
}
/* Fin del archivo */