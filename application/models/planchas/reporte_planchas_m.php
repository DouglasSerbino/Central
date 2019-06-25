<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_planchas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Funcion que servira para extraer la informacion y crear el grafico
	*/
	function RepPlanchas($Anho, $Mes, $Tipo, $ICliente)
	{
		$Info = array();
		if('Anual' == $Tipo)
		{
			$Fecha_Inicio = $Anho.'-01-01';
			$Fecha_Fin = $Anho.'-12-31';
			$agrupar = 'm';
		}
		else
		{
			$Fecha_Inicio = $Anho.'-'.$Mes.'-01';
			$Fecha_Fin = $Anho.'-'.$Mes.'-31';
			$agrupar = 'd';
		}
		
		$SQL = '';
		if('todos' != $ICliente and '' != $ICliente)
		{
			$SQL = ' and proc.id_cliente = "'.$ICliente.'"';
		}
		
		//Extraemos lo que se reporta en las cotizaciones.
		$Consulta = 'select sum(prop.cantidad) as cantidad, ped.id_proceso,
				proc.id_cliente, proc.codigo_cliente,
				date_format(ped.fecha_reale, "%'.$agrupar.'") as dia
				from producto_cliente proc, producto_pedido prop,
				pedido ped, procesos proce
				where proc.id_producto in("29", "18")
				and proc.id_prod_clie = prop.id_prod_clie
				and ped.id_pedido = prop.id_pedido
				and ped.fecha_reale >= "'.$Fecha_Inicio.'"
				and ped.fecha_reale <= "'.$Fecha_Fin.'"
				and ped.fecha_reale != "0000-00-00"
				'.$SQL.'
				and ped.id_proceso = proce.id_proceso
				and proc.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by date_format(ped.fecha_reale, "%'.$agrupar.'"), proc.id_cliente
				order by proc.id_cliente asc';
								
			//Ejecutamos la consulta.
			//echo $Consulta.'<br /><br />';
			$Resultado = $this->db->query($Consulta);
			
			//Exploramos el array
			foreach($Resultado->result_array() as $Datos)
			{
				if(!isset($Info[$Datos['dia']]['totalC']))
				{
					$Info[$Datos['dia']]['totalC'] = 0;
				}
				
				if('Anual' != $Tipo)
				{
					$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado'] = number_format($Datos['cantidad'], 0, '', '');
				}
				$Info[$Datos['dia']]['totalC'] = $Info[$Datos['dia']]['totalC'] + number_format($Datos['cantidad'], 0, '', '');
				
			}
			
			//Extraemos los consumos diarios del departamento de planchas.
			//and peus.id_usuario = "18"
			$Consulta = 'select sum(pedmat.cantidad) as cantidad,
					ped.id_proceso, cli.codigo_cliente,
					proc.id_cliente, date_format(peus.fecha_fin, "%'.$agrupar.'") as dia
					from pedido_material pedmat, pedido ped, procesos proc,
					inventario_material inve, cliente cli, pedido_usuario peus
					where pedmat.id_pedido = ped.id_pedido
					and peus.id_pedido = ped.id_pedido
					and pedmat.id_pedido = ped.id_pedido
					and id_inventario_equipo = 6
					and peus.fecha_fin >= "'.$Fecha_Inicio.' 00:00:00 00:00:00"
					and peus.fecha_fin <= "'.$Fecha_Fin.' 23:59:00 00:00:00"
					and ped.id_proceso = proc.id_proceso
					and pedmat.id_inventario_material = inve.id_inventario_material
					and inve.nombre_material like "%PLANCHA%"
					and inve.tipo = "in2"
					'.$SQL.'
					and inve.nombre_material != "Maquilado de planchas"
					and proc.id_cliente = cli.id_cliente
					group by date_format(peus.fecha_fin, "%'.$agrupar.'"), proc.id_cliente
					order by cli.id_cliente asc , date_format(peus.fecha_fin, "%'.$agrupar.'")';
			//echo $Consulta.'<BR><BR>';			
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			
			//Exploramos el array
			foreach($Resultado->result_array() as $Datos)
			{
				if(!isset($Info[$Datos['dia']]['totalR']))
				{
					$Info[$Datos['dia']]['totalR'] = 0;
				}
				
				if(0 > $Datos['cantidad'])
				{
					$Datos['cantidad'] = 0;
				}
				
				if('Anual' != $Tipo)
				{
					if(!isset($Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado']))
					{
						$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado'] = 0;
					}
					$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['real'] = number_format($Datos['cantidad'], 0, '', '');
				}
				$Info[$Datos['dia']]['totalR'] = $Info[$Datos['dia']]['totalR'] + number_format($Datos['cantidad'], 0, '', '');
			}
			
			ksort($Info);
			//print_r($Info);
			//echo '<br><br>';
			return $Info;
	}
	
	
	function RepPlanchasDet($Anho, $Mes, $Dia)
	{
		$Fecha_Inicio = $Anho.'-'.$Mes.'-'.$Dia;
		$Fecha_Fin = $Anho.'-'.$Mes.'-'.$Dia;
		$Info = array();
		//Extraemos lo que se reporta en las cotizaciones.
		$Consulta = 'select sum(prop.cantidad) as cantidad, ped.id_proceso, proc.id_cliente, cli.nombre, proc.codigo_cliente, date_format(ped.fecha_reale, "%d") as dia
			from producto_cliente proc, producto_pedido prop, pedido ped, procesos proce, cliente cli
			where proc.id_producto in("29", "18")
			and proc.id_prod_clie = prop.id_prod_clie
			and ped.id_pedido = prop.id_pedido
			and ped.fecha_reale = "'.$Anho.'-'.$Mes.'-'.$Dia.'"
			and ped.id_proceso = proce.id_proceso
			and cli.id_cliente = proce.id_cliente
			group by date_format(ped.fecha_reale, "%d"), cli.codigo_cliente asc';
			
			//Ejecutamos la consulta.
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			//Exploramos el array
			
			foreach($Resultado->result_array() as $Datos)
			{
				if(!isset($Info[$Datos['dia']]['totalC']))
				{
					$Info[$Datos['dia']]['totalC'] = 0;
				}
				$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['nomcliente'] = $Datos['nombre'];
				$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado'] = number_format($Datos['cantidad'], 0, '.', '');
				$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['real'] = 0;
				$Info[$Datos['dia']]['totalC'] = $Info[$Datos['dia']]['totalC'] + number_format($Datos['cantidad'], 0, '', '');
			}
				
				
				//and peus.id_usuario in( "18" )
				$Consulta = 'select sum(pedmat.cantidad) as cantidad, ped.id_proceso, cli.id_grupo,
								cli.nombre, cli.codigo_cliente, proc.id_cliente, date_format(peus.fecha_fin, "%d") as dia
								from pedido_material pedmat, pedido ped, procesos proc,
								inventario_material inve, cliente cli, pedido_usuario peus
								where pedmat.id_pedido = ped.id_pedido
								and peus.id_pedido = ped.id_pedido
								and peus.id_pedido = pedmat.id_pedido
								and id_inventario_equipo = 6
								and peus.fecha_fin >= "'.$Fecha_Inicio.' 00:00:00 00:00:00"
								and peus.fecha_fin <= "'.$Fecha_Fin.' 23:59:00 00:00:00"
								and ped.id_proceso = proc.id_proceso
								and pedmat.id_inventario_material = inve.id_inventario_material
								and inve.nombre_material like "%PLANCHA%"
								and inve.tipo = "in2"
								and inve.nombre_material != "Maquilado de planchas"
								and proc.id_cliente = cli.id_cliente
								group by date_format(peus.fecha_fin, "%d"), proc.id_cliente
								order by cli.id_cliente asc , date_format(peus.fecha_fin, "%d")';
			
			
			echo $Consulta;
			exit();
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			
			//Exploramos el array
			foreach($Resultado->result_array() as $Datos)
			{
				if(!isset($Info[$Datos['dia']]['totalR']))
				{
					$Info[$Datos['dia']]['totalR'] = 0;
				}
				if(0 > $Datos['cantidad'])
				{
					$Datos['cantidad'] = 0;
				}
				
				if(!isset($Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado']))
				{
					$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['cotizado'] = 0;
				}
				
				if(!isset($Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['real']))
				{
					$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['real'] = 0;
				}
				
				$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['nomcliente'] = $Datos['nombre'];
				$Info[$Datos['dia']]['clientes'][$Datos['codigo_cliente']]['real'] = number_format($Datos['cantidad'], 0, '', '');
				$Info[$Datos['dia']]['totalR'] = $Info[$Datos['dia']]['totalR'] + number_format($Datos['cantidad'], 0, '', '');
			}
			ksort($Info);
			
			return $Info;
	}
	
	
	/*
	 *Extraeremos los clientes a los que se les procesan planchas.
	*/
	function ClientesPlanchas($Anho, $Mes)
	{
		$Info = array();

		$Consulta = '
			select cli.nombre, proc.id_cliente, cli.codigo_cliente
			from pedido_material pedmat, pedido ped, procesos proc, inventario_material inve, cliente cli
			where pedmat.id_pedido = ped.id_pedido
			and ped.fecha_reale != "0000-00-00"
			and ped.id_proceso = proc.id_proceso
			and pedmat.id_inventario_material = inve.id_inventario_material
			and inve.nombre_material like "%PLANCHA%"
			and inve.tipo = "in2"
			and ped.fecha_reale >= "'.$Anho.'-'.$Mes.'-01" and ped.fecha_reale <= "'.$Anho.'-'.$Mes.'-31"
			and inve.nombre_material != "Maquilado de planchas"
			and proc.id_cliente = cli.id_cliente
			group by cli.id_Cliente order by cli.id_cliente asc
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
	
	
}

/* Fin del archivo */