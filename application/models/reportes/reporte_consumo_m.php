<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_consumo_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function consumo_promedio_mensual($Id_inventario_material = '')
	{
		$SQL = '';
		if('' != $Id_inventario_material)
		{
			$SQL = ' and material.id_inventario_material = "'.$Id_inventario_material.'"';
		}
		//Declaracion de variables
		$anho = date("Y");
		$mes = date('m');
		$mes_inicio = $mes - 3;
		$consumo_placas = 0;
		
		$info = array();
		
		//Consulta para extraer las unidades del material seleccionado.
		$Consulta = 'select sum(cantidad) as Resultado, material.id_inventario_material,
								material.numero_individual, material.numero_cajas, material.codigo_sap, material.tipo
								from inventario_requisicion requi, inventario_lote lote, inventario_material material
								where lote.id_inventario_lote = requi.id_inventario_lote
								and material.id_inventario_material = lote.id_inventario_material
								'.$SQL.'
								and fecha_salida >= "'.$anho.'-'.$mes_inicio.'-01"
								and fecha_salida <= "'.$anho.'-'.$mes.'-31"
								group by lote.id_inventario_material
								order by lote.id_inventario_material
								';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);					
		//Exploramos el array para mostrar la informacion.
		foreach($Resultado->result_array() as $Datos_material)
		{
			$consumo_cajas = 0;
			$consumo_placas = 0;
			//Asignamos el resultado a una variable.
			//Consumo en pulgadas cada mes
			$consumo_pulmensual = $Datos_material["Resultado"] / 3;
			
			if($Datos_material['numero_individual'] != 0)
			{
				$consumo_placas = $consumo_pulmensual / $Datos_material['numero_individual'];
			}
			
			if($Datos_material['numero_cajas'] != 0)
			{
				$consumo_cajas = $consumo_placas / $Datos_material['numero_cajas'];
			}
			
			//Asignamos la informacion a un array.
			$info[$Datos_material['id_inventario_material']]['inventario'] = $Datos_material["id_inventario_material"];
			$info[$Datos_material['id_inventario_material']]['consumo_cajas'] = $consumo_cajas;
		}
		
		//Regresamos el array.
		return $info;
	}
	
	/**
		@param Pedidos que estan en transito
		Nos permitira ver los cantidas de pedidos que estan en transito.
	*/
	function mostrar_pedido_transito()
	{
		$info = array();
		//Consulta extraer la informacion del material solicitado.
		$Consulta = 'select sum(tran.cantidad) as cantidad, tran.orden,
								mate.numero_individual, mate.numero_cajas, mate.id_inventario_material
								from inventario_material mate, pedido_transito tran
								where mate.id_inventario_material = tran.id_inventario_material
								and tran.finalizado = "n"
								and tran.id_grupo = "'.$this->session->userdata('id_grupo').'"
								group by mate.id_inventario_material
								';
		//echo $Consulta;
		//Ejecutamos la consulta.
		$Resultado = $this->db->query($Consulta);
		$Cantidad = 0;
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Pedidos)
			{
				
				$Cantidad_transito = 0;
				$Ped_transito = 0;
				$Cantidad = $Pedidos["cantidad"];
				
				if($Pedidos['numero_individual'] != 0)
				{
					$Ped_transito = $Cantidad / $Pedidos['numero_individual'];
				}
				
				if($Pedidos['numero_cajas'] != 0)
				{
					$Cantidad_transito = number_format(($Ped_transito / $Pedidos['numero_cajas']), 0);
				}
				
				$info[$Pedidos['id_inventario_material']]['cantidad'] = $Cantidad_transito;
				$info[$Pedidos['id_inventario_material']]['orden'] = $Pedidos['orden'];
			}
		}
		return $info;
	}
}
/* Fin del archivo */