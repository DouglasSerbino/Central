<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reprocesos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Busqueda de todos los reprocesos
	 *@param string $fmanho1.
	 *@param string $fmmes1.
	 *@param string $cod_cliente.
	 *@return array.
	*/
	function reprocesos($anho, $mes, $razon_reproceso)
	{
		$dia = date('d');
		$total_t = 0;
		$total_tr = 0;
		$total_tt = 0;
		$pedidos = array();
		if($mes == 'anual')
		{
			$fecha1 = $anho.'-01-01';
			$fecha2 = $anho.'-12-01';
		}
		else
		{
			$fecha1 = $anho.'-'.$mes.'-01';
			$fecha2 = $anho.'-'.$mes.'-31';
		}
		
		
		$SQL = '';
		$tabla = '';
		
		if(strtotime('2013-06-20') < strtotime($anho.'-'.$mes.'-'.$dia))
		{
			if('todos' != $razon_reproceso)
			{
				$tabla = ' ,reproceso_detalle reproc ';
				$SQL .= 'and ped.id_repro_deta = reproc.id_repro_deta';
				$SQL .= ' and reproc.id_repro_deta = "'.$razon_reproceso.'"';
			}
		}

		//Buscar clientes que poseen reprocesos
		$Consulta = '
							select distinct proc.id_cliente, codigo_cliente
							from procesos proc, pedido ped, cliente cli '.$tabla.'
							where proc.id_proceso = ped.id_proceso
								and cli.id_cliente = proc.id_cliente
								and ped.fecha_entrega >= "'.$fecha1.'"
								and ped.fecha_entrega <= "'.$fecha2.'"
								and ped.id_tipo_trabajo = "4"
								'.$SQL.'
								and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
							order by id_cliente
							';
		
		$Resultado= $this->db->query($Consulta);
		$Info = $Resultado->result_array();
		
		foreach($Info as $Datos)
		{
			$pedidos[$Datos['id_cliente']]['id_cliente'] = $Datos['id_cliente'];
			$pedidos[$Datos['id_cliente']]['codigo_cliente'] = $Datos['codigo_cliente'];

			//Buscar reprocesos por cliente
			$Consulta2 = '
									select count(id_cliente) as total
									from procesos proc, pedido ped '.$tabla.'
									where proc.id_proceso = ped.id_proceso
									and ped.fecha_entrega >= "'.$fecha1.'"
									and ped.fecha_entrega <= "'.$fecha2.'"
									and ped.id_tipo_trabajo = "4"
									'.$SQL.'
									and id_cliente = "'.$Datos['id_cliente'].'"
									order by id_cliente';
			
			$Resultado2 = $this->db->query($Consulta2);
			$Info2 = $Resultado2->result_array();
			foreach($Info2 as $Datos2)
			{
				$total_r = $Datos2["total"];
				$total_tt += $total_r;
				$pedidos[$Datos['id_cliente']]['total_reprocesos'] = $total_r;
				$pedidos[$Datos['id_cliente']]['num_reprocesos'] = $total_tt;
			}
			
			//Total pedidos del cliente en el rango de fecha
			$Consulta3 = 'select count(id_cliente) as total_a
									from procesos proc, pedido ped
									where proc.id_proceso = ped.id_proceso
									and ped.fecha_entrega >= "'.$fecha1.'"
									and ped.fecha_entrega <= "'.$fecha2.'"
									and id_cliente = "'.$Datos['id_cliente'].'"
									order by id_cliente';
			
			$Resultado3 = $this->db->query($Consulta3);
			$Info3 = $Resultado3->result_array();
			foreach($Info3 as $Datos3)
			{
				$total_t = $Datos3["total_a"];
				$total_tr = $total_tr + $total_t;
				$porc = ($total_r * 100) / $total_t;
				$porc = round($porc * 100) / 100;
				$pedidos[$Datos['id_cliente']]['total_pedidos'] = $total_t;
				$pedidos[$Datos['id_cliente']]['porcentaje'] = $porc;
				$pedidos[$Datos['id_cliente']]['total_trabajos'] = $total_tr;
			}
		}
		
		return $pedidos;
	}
}

/* Fin del archivo */