<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resumen_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/***********************************************************************/
	function general($Fecha = '', $Rango = '')
	{
	
	}
	
	
	/***********************************************************************/
	function cumplimiento($Fecha = '', $Rango = '')
	{
		$Fe_compa = explode('-', $Fecha);
		if('mes' == $Rango)
		{
			$SQL = ' ped.fecha_entrega >= "'.$Fecha.'-01"
				and ped.fecha_entrega <= "'.$Fecha.'-31" ';
		}
		else
		{
			$SQL = ' ped.fecha_entrega >= "'.$Fe_compa[0].'-01-01"
				and ped.fecha_entrega <= "'.$Fe_compa[0].'-12-31" ';
		}
			
			$Consulta = '
				select clie.codigo_cliente, count(ped.id_pedido) as pedidos
				from cliente clie
				INNER JOIN procesos proc ON clie.id_cliente = proc.id_cliente
				INNER JOIN pedido ped ON proc.id_proceso = ped.id_proceso
				where 
				'.$SQL.'
				and ped.fecha_reale != "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by clie.codigo_cliente
				order by clie.codigo_cliente asc
			';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			$Pedidos = $Resultado->result_array();
			
			
			$Consulta = '
				select clie.codigo_cliente, count(ped.id_pedido) as atrasados
				from cliente clie
				INNER JOIN procesos proc ON  clie.id_cliente = proc.id_cliente
				INNER JOIN pedido ped ON proc.id_proceso = ped.id_proceso
				where 
				'.$SQL.'
				and ped.fecha_reale > ped.fecha_entrega
				and ped.fecha_reale = "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by codigo_cliente
			';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			$Puntuales = array();
			foreach($Resultado->result_array() as $Fila)
			{
				$Atrasados[$Fila['codigo_cliente']] = $Fila['atrasados'];
			}
			
			
			$Cumplimiento = array();
			foreach($Pedidos as $Fila)
			{
				$Cumplimiento[$Fila['codigo_cliente']]['to'] = $Fila['pedidos'];
				if(isset($Atrasados[$Fila['codigo_cliente']]))
				{
					$Cumplimiento[$Fila['codigo_cliente']]['ti'] = $Fila['pedidos'] - $Atrasados[$Fila['codigo_cliente']];
					$Cumplimiento[$Fila['codigo_cliente']]['at'] = $Atrasados[$Fila['codigo_cliente']];
					$Cumplimiento[$Fila['codigo_cliente']]['po'] = number_format(($Cumplimiento[$Fila['codigo_cliente']]['ti'] * 100 / $Fila['pedidos']), 2);
				}
				else
				{
					$Cumplimiento[$Fila['codigo_cliente']]['ti'] = $Fila['pedidos'];
					$Cumplimiento[$Fila['codigo_cliente']]['at'] = 0;
					$Cumplimiento[$Fila['codigo_cliente']]['po'] = '100.00';
				}
			}
		
		echo json_encode($Cumplimiento);	
	}
	
	
	
	/***********************************************************************/
	function reprocesos($Fecha = '', $Rango = '')
	{
		
		$Fe_compa = explode('-', $Fecha);
		if('mes' == $Rango)
		{
			$SQL = ' ped.fecha_entrega >= "'.$Fecha.'-01"
				and ped.fecha_entrega <= "'.$Fecha.'-31"';
		}
		else
		{
			$SQL = ' ped.fecha_entrega >= "'.$Fe_compa[0].'-01-01"
				and ped.fecha_entrega <= "'.$Fe_compa[0].'-12-31"';
		}
			
			$Consulta = '
				select clie.codigo_cliente, count(ped.id_pedido) as pedidos
				from cliente clie
				INNER JOIN procesos proc ON clie.id_cliente = proc.id_cliente
				INNER JOIN pedido ped ON proc.id_proceso = ped.id_proceso
				where 
				'.$SQL.'
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by codigo_cliente
				order by codigo_cliente asc
			';
			//echo $Consulta;
			$Resultado = $this->db->query($Consulta);
			$Pedidos = $Resultado->result_array();
			
			
			$Consulta = '
				select clie.codigo_cliente, count(ped.id_pedido) as reprocesos
				from cliente clie
				INNER JOIN procesos proc ON  clie.id_cliente = proc.id_cliente
				INNER JOIN pedido ped ON proc.id_proceso = ped.id_proceso
				where
				'.$SQL.'
				and id_tipo_trabajo = 4
				and ped.fecha_reale != "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by codigo_cliente
			';
			$Resultado = $this->db->query($Consulta);
			$Reprocesos = array();
			foreach($Resultado->result_array() as $Fila)
			{
				$Reprocesos[$Fila['codigo_cliente']] = $Fila['reprocesos'];
			}
			
	
			$Reclamos = array();
			foreach($Pedidos as $Fila)
			{
				$Reclamos[$Fila['codigo_cliente']]['to'] = $Fila['pedidos'];
				if(isset($Reprocesos[$Fila['codigo_cliente']]))
				{
						$Reclamos[$Fila['codigo_cliente']]['re'] = $Reprocesos[$Fila['codigo_cliente']];
						$Reclamos[$Fila['codigo_cliente']]['po'] = number_format(($Reprocesos[$Fila['codigo_cliente']] * 100 / $Fila['pedidos']), 2);
				}
				else
				{
					$Reclamos[$Fila['codigo_cliente']]['re'] = 0;
					$Reclamos[$Fila['codigo_cliente']]['po'] = '0.00';
				}
			}
			
			echo json_encode($Reclamos);
		
	}
	
	
	/***********************************************************************/
	function ventas($Fecha = '', $Rango = '')
	{
		$Fe_compa = explode('-', $Fecha);
		if('mes' == $Rango)
		{
			$SQL = 'proy.anho = "'.$Fe_compa[0].'" and proy.mes = "'.$Fe_compa[1].'"';
			$SQL_fecha = 'sap.fecha >= "'.$Fecha.'-01" and sap.fecha <= "'.$Fecha.'-31"';
		}
		else
		{
			$SQL = ' proy.anho = "'.$Fe_compa[0].'"';
			$SQL_fecha = 'sap.fecha >= "'.$Fe_compa[0].'-01-01" and sap.fecha <= "'.$Fe_compa[0].'-12-31"';
		}
			
			$Consulta = '
				select id_tipo, tipo
				from cliente_tipo
			';
			$Resultado = $this->db->query($Consulta);
			$Clientes = $Resultado->result_array();
			
			
			$Consulta = '
				select tipo.id_tipo, sum(proyeccion) as proyeccion
				from venta_proyeccion proy
				INNER JOIN cliente cli ON proy.id_cliente = cli.id_cliente
				INNER JOIN cliente_tipo tipo ON cli.id_tipo = tipo.id_tipo
				where 
				 '.$SQL.'
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				group by tipo.id_tipo
			';
			//echo $Consulta.'<br>';
			$Resultado = $this->db->query($Consulta);
			$Proyeccion = array();
			foreach($Resultado->result_array() as $Fila)
			{
				$Proyeccion[$Fila['id_tipo']] = $Fila['proyeccion'];
			}

			$Consulta = '
				select tipo.id_tipo, sum(sap.venta) as venta
				from pedido_sap sap, cliente cli, cliente_tipo tipo
				where sap.id_cliente = cli.id_cliente
				and cli.id_tipo = tipo.id_tipo
				and '.$SQL_fecha.'
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				and confirmada != "pe"
				group by tipo.id_tipo
			';
			//echo $Consulta.'<br>';
			$Resultado = $this->db->query($Consulta);
			$Venta = array();
			foreach($Resultado->result_array() as $Fila)
			{
				$Venta[$Fila['id_tipo']] = $Fila['venta'];
			}
			
			
			$Cliente_Venta_Proyeccion = array('tot' => 0, 'pro' => 0, 'por' => 0);
			foreach($Clientes as $Cliente)
			{
				
				$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['tip'] = $Cliente['tipo'];
				$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['pro'] = '0.00';
				if(isset($Proyeccion[$Cliente['id_tipo']]))
				{
					$Cliente_Venta_Proyeccion['pro'] += $Proyeccion[$Cliente['id_tipo']];
					$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['pro'] = number_format($Proyeccion[$Cliente['id_tipo']], 2);
				}
				$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['ven'] = '0.00';
				if(isset($Venta[$Cliente['id_tipo']]))
				{
					$Cliente_Venta_Proyeccion['tot'] += $Venta[$Cliente['id_tipo']];
					$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['ven'] = number_format($Venta[$Cliente['id_tipo']], 2);
				}
				$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['por'] = '0.00';
				if(isset($Proyeccion[$Cliente['id_tipo']]) && 0 < $Proyeccion[$Cliente['id_tipo']])
				{
					if(isset($Venta[$Cliente['id_tipo']]))
					{
						$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['por'] = ($Venta[$Cliente['id_tipo']] *100 ) / $Proyeccion[$Cliente['id_tipo']];
					}
					$Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['por'] = number_format($Cliente_Venta_Proyeccion['cli'][$Cliente['id_tipo']]['por'], 2);
				}
				
				
			}
			
			if(0 < $Cliente_Venta_Proyeccion['pro'])
			{
				$Cliente_Venta_Proyeccion['por'] = ($Cliente_Venta_Proyeccion['tot'] *100 ) / $Cliente_Venta_Proyeccion['pro'];
				$Cliente_Venta_Proyeccion['por'] = number_format($Cliente_Venta_Proyeccion['por'], 2);
			}
			$Cliente_Venta_Proyeccion['tot'] = number_format($Cliente_Venta_Proyeccion['tot'], 2);
			$Cliente_Venta_Proyeccion['pro'] = number_format($Cliente_Venta_Proyeccion['pro'], 2);
			
			echo json_encode($Cliente_Venta_Proyeccion);

	}
	
		/***********************************************************************/
	function extras($Fecha = '', $Rango = '')
	{
		$Informacion = array();
		$Fe_compa = explode('-', $Fecha);
		
		if('mes' == $Rango)
		{
			$SQL = 'ext.fecha >= "'.$Fecha.'-01"
				and ext.fecha <= "'.$Fecha.'-31"';
		}
		else
		{
			$SQL = 'ext.fecha >= "'.$Fe_compa[0].'-01-01"
				and ext.fecha <= "'.$Fe_compa[0].'-12-31"';
		}

			$Consulta = ' select sum(ext.total_h) as total, dpto.departamento, dpto.id_dpto
					from extra ext
					INNER JOIN usuario usu ON usu.id_usuario = ext.id_usuario
					INNER JOIN departamentos dpto ON dpto.id_dpto = usu.id_dpto
					where 
					'.$SQL.'
					and usu.id_grupo = "'.$this->session->userdata["id_grupo"].'"
					group by dpto.id_dpto
			';
			//echo $Consulta;
			
			//Ejecutamos la consulta.
			$Resultado = $this->db->query($Consulta);
			$Datos = $Resultado->result_array();
			foreach($Datos as $Fila)
			{
				$Informacion[$Fila['id_dpto']]['tot'] = $Fila['total'];
				$Informacion[$Fila['id_dpto']]['dpto'] = $Fila['departamento'];
			}
			asort($Informacion);
			//print_r($Informacion);
			echo json_encode($Informacion);
		}
	
	/***********************************************************************/
	function ventas_grafico($Anho = '')
	{

			$Consulta = '
				select tipo.id_tipo, sum(sap.venta) as venta, date_format(sap.fecha, "%m") + 0 as fecha
				from pedido_sap sap
				INNER JOIN cliente cli ON sap.id_cliente = cli.id_cliente
				INNER JOIN cliente_tipo tipo ON cli.id_tipo = tipo.id_tipo
				where sap.fecha >= "'.$Anho.'-01-01" and sap.fecha <= "'.$Anho.'-12-31"
				and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
				and confirmada != "pe"
				group by date_format(sap.fecha, "%m"), tipo.id_tipo
			';
			//echo $Consulta.'<br>';
			$Resultado = $this->db->query($Consulta);
			$Venta = array();
			//print_r($Resultado->result_array());
			foreach($Resultado->result_array() as $Fila)
			{
				$Venta[$Fila['id_tipo']]['tipo'] = $Fila['id_tipo'];
				$Venta[$Fila['id_tipo']]['ventas'][$Fila['fecha']] = number_format($Fila['venta'],2,'.','');
			}
			
			return $Venta;

	}
	
	/***********************************************************************/
	function Informacion_reportes($Anho = '')
	{

		$Informacion = array();
		$Consulta = '
			select tped, ttie, tatra, trepro, extras, cafi, ccom, cdiv, csto, anho
			from reporte_anual
			where anho = "'.$Anho.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by mes
		';
		
		//echo $Consulta.'<br>';
		$Resultado = $this->db->query($Consulta);
		$Info = $Resultado->result_array();
		//print_r($Info);
			
		$Consultap = '
			select sum( proyeccion ) as proyeccion
			from venta_proyeccion proy, cliente cli
			where proy.anho = "'.$Anho.'"
			and cli.id_cliente = proy.id_cliente
			and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by mes
		';
		//echo $Consultap;
		
		$Resultadop = $this->db->query($Consultap);
		
		$Filap = $Resultadop->result_array();
		
		$e = 1;
		//print_r($Filap);
		for($a=0; $a<12;$a++)
		{
			$Informacion[$e]['extras'] = isset($Info[$a])?$Info[$a]['extras']:0;
			$Informacion[$e]['reprocesos'] = isset($Info[$a])?$Info[$a]['trepro']:0;
			$Informacion[$e]['anho'] = isset($Info[$a])?$Info[$a]['anho']:0;
			$Informacion[$e]['ttiempo'] = isset($Info[$a])?$Info[$a]['ttie']:0;
			$Informacion[$e]['tatrasados'] = isset($Info[$a])?$Info[$a]['tatra']:0;
			$Informacion[$e]['ttrabajos'] = isset($Info[$a])?$Info[$a]['tped']:0;
			$Informacion[$e]['cafi'] = isset($Info[$a])?$Info[$a]['cafi']:0;
			$Informacion[$e]['ccom'] = isset($Info[$a])?$Info[$a]['ccom']:0;
			$Informacion[$e]['cdiv'] = isset($Info[$a])?$Info[$a]['cdiv']:0;
			$Informacion[$e]['csto'] = isset($Info[$a])?$Info[$a]['csto']:0;
			$Informacion[$e]['proy'] = isset($Filap[$a])?number_format($Filap[$a]['proyeccion'] , 2, '.', ''):0;
			//$Info[$e]['ttiempo'] = number_format($Fila['venta'],2,'.','');
			$e++;
		}
		//print_r($Informacion);
		//echo '<br><br><br>';
		return $Informacion;
		//echo json_encode($Cliente_Venta_Proyeccion);

	}
	
}

/* Fin del archivo */