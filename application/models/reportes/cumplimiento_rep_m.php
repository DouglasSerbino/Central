<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_rep_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Realiza la busqueda de los procesos para
	 *obtener el total de trabajos a tiempo, reprocesos y atrasados.
	 *@param string $anho1.
	 *@param string $mes.
	 *@param string $cod_cliente.
	 *@return array.
	*/
	
	function Porcentajes($anho, $mes, $cod_cliente = '')
	{
		$Info = array();
		
		$SQL = '';
		$SQL2 = array('1'=> 'and ped.fecha_reale != "0000-00-00"', '2' => 'and ped.fecha_reale <= fecha_entrega and ped.fecha_reale != "0000-00-00"', '3'=>'and ped.fecha_reale > ped.fecha_entrega and ped.fecha_reale != "0000-00-00"',
									'4'=>'and ped.id_tipo_trabajo = "4" and ped.fecha_reale != "0000-00-00"');
		
		if($cod_cliente != 'gen')
		{
			$SQL = 'and cli.codigo_cliente = "'.$cod_cliente.'"';
		}
		
		$item = array('1'=>'pedidos_tot', '2' => 'pedidos_tie', '3'=>'pedidos_atra', '4'=>'pedidos_rep');
		
		for($a = 1; $a <=4; $a++)
		{
			$Consulta = '
							select count(ped.id_pedido) as '.$item[$a].',
							date_format(ped.fecha_entrega, "%m") as fecha
							from pedido ped, procesos proc, cliente cli
							where proc.id_cliente = cli.id_cliente
							and proc.id_proceso = ped.id_proceso
							and ped.fecha_entrega >= "'.$anho.'-01-01"
							and ped.fecha_entrega <= "'.$anho.'-12-31"
							'.$SQL2[$a].'
							'.$SQL.'
							and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"
							group by date_format(ped.fecha_entrega, "%m")';
							
			//echo $Consulta.'<br><br>';
			$Resultado= $this->db->query($Consulta);
			if(0 < $Resultado->num_rows)
			{
				foreach($Resultado->result_array() as $Datos)
				{
					$Info[$Datos['fecha']][$item[$a]] = $Datos[$item[$a]];
				}
			}
		}
		//print_r($Info);
		return $Info;
	}
		
	/**
	 *Realiza la busqueda del cliente que se selecciono.
	 *@param string $cod_cliente.
	 *@return array.
	*/
	function cliente($cod_cliente)
	{
		$Consulta = '';
		if($cod_cliente != 'gen')
		{
			$Consulta = '
					select nombre as nombre, id_cliente
					from cliente
					where codigo_cliente = "'.$cod_cliente.'"
				';
					
			$Resultado= $this->db->query($Consulta);

			if(0 < $Resultado->num_rows())
			{
				return $Resultado->row_array();
			}
		}
	}
	
	/**
	 *Se buscan los tipos de trabajos que se han realizado para el
	 *cliente seleccionado
	 *@param string $cod_cliente.
	 *@param array $trabajos_finales.
	 *@param string $mes.
	 *@param string $anho.
	 *@return array.
	*/
	function trabajos_finales($trabajos_finales, $anho, $mes, $id_cliente)
	{
		$SQL = '';
		if($id_cliente != 'gen')
		{
			$SQL = 'and proc.id_cliente = "'.$id_cliente.'"';
		}
	
		foreach($trabajos_finales as $final => $trabajo)
		{
			$Condiciones[] = ' matsol.id_material_solicitado = "'.$final.'"';
		}
			$Consulta = 'select count(distinct ped.id_pedido) as tpedido, matsol.id_material_solicitado, matsol.material_solicitado
						from pedido ped,
							procesos proc,
							especificacion_matsolgru espe_matsolgru,
							material_solicitado_grupo matsolgru,
							material_solicitado matsol
						where
							proc.id_proceso = ped.id_proceso
							'.$SQL.' and ('.implode(' or ', $Condiciones).')
							and ped.fecha_entrega >= "'.$anho.'-'.$mes.'-01"
							and ped.fecha_entrega <= "'.$anho.'-'.$mes.'-31"
							and ped.id_pedido = espe_matsolgru.id_pedido
							and espe_matsolgru.id_material_solicitado_grupo = matsolgru.id_material_solicitado_grupo
							and matsolgru.id_material_solicitado = matsol.id_material_solicitado
							and matsolgru.id_grupo = "'.$this->session->userdata('id_grupo').'"
							group by matsol.id_material_solicitado
							order by matsol.id_material_solicitado asc 
							';
			//echo $Consulta;			
			$Resultado= $this->db->query($Consulta);
			$mandar = array();
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Datos)
				{
					$mandar[$Datos['id_material_solicitado']]['id_matsol'] = $Datos['id_material_solicitado'];
					$mandar[$Datos['id_material_solicitado']]['tped'] = $Datos['tpedido'];
					$mandar[$Datos['id_material_solicitado']]['matsol'] = $Datos['material_solicitado'];
				}
				return $mandar;
			}
			else
			{
				return array();
			}
	}
	
	
	function tiempos_desarrollo($Tipo, $anho, $mes, $SQL)
	{
		$Tiempo = '';
		$Consulta = '
			select count(ped.id_pedido) as total
			from cliente clie, procesos proc, pedido ped, pedido_tie_repro pedt
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pedt.id_pedido and fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and fecha_entrega <= "'.$anho.'-'.$mes.'-31" and '.$Tipo.' != "n/a"
			'.$SQL.' and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Tiempo = $Fila['total'] + 0;
		}
		return $Tiempo;
	}
	
	function tiempos_utilizados($anho, $mes, $cod_cliente)
	{
		$Tiempos = array(
			'Ventas' => 0,
			'Plani' => 0,
			'Arte' => 0,
			'Aprobacion' => 0,
			'Final' => 0
		);
		$SQL = '';
		
		if($cod_cliente != 'gen')
		{
			$SQL = 'and codigo_cliente = "'.$cod_cliente.'"';
		}
		
		//Pedidos trabajados por Plani
		$Consulta = '
			select count(distinct tiem.id_pedido) as pedidos, sum(tiem.tiempo) as ttiempo
			from cliente clie, procesos proc, pedido ped, pedido_tiempos tiem,
			usuario usu, departamentos dpto
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = tiem.id_pedido and tiem.id_usuario = usu.id_usuario
			and usu.id_dpto = dpto.id_dpto and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			and fin >= "'.$anho.'-'.$mes.'-01 00:00:00" and fin <= "'.$anho.'-'.$mes.'-31 23:59:59"
			
			'.$SQL.'
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			
			$Fila['pedidos'] += 0;
			//echo $Fila['pedidos'].'<br />';
			if(0 < $Fila['pedidos'])
			{
				$Plani_RP = ($Fila['ttiempo'] / $Fila['pedidos']);
				
				
				$Tiempos['Plani'] = number_format(($Plani_RP /60 / 24), 2).' dias';
				
				
				
				//$Tiempos['Plani'] = $this->fechas_m->minutos_a_hora($Tiempos['Plani']);
			}
		}
		
		//Extraemos los tiempos utilizados en Arte, tiempo en aprobacion y tiempo de Finalizacion.
		$Tiempos['Arte'] = $this->tiempos_desarrollo("tiempo_arte", $anho, $mes, $SQL);
		//echo $Tiempos['Arte'].'<br>';
		
		$Tiempos['Aprobacion'] = $this->tiempos_desarrollo("tiempo_aprobacion", $anho, $mes, $SQL);
		//echo $Tiempos['Aprobacion'].'<br>';
		
		$Tiempos['Final'] = $this->tiempos_desarrollo("tiempo_efinal", $anho, $mes, $SQL);
		//echo $Tiempos['Final'].'<br>';
	
		$Consulta = '
			select sum(pedt.tiempo_arte) as arte, sum(pedt.tiempo_aprobacion) as aprobacion,
			sum(pedt.tiempo_efinal) as efinal
			from cliente clie, procesos proc, pedido ped, pedido_tie_repro pedt
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = pedt.id_pedido and fecha_entrega >= "'.$anho.'-'.$mes.'-01"
			and fecha_entrega <= "'.$anho.'-'.$mes.'-31"
			'.$SQL.' and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			if(0 < $Tiempos['Arte'])
			{
				$Tiempos['Arte'] = number_format(($Fila['arte'] / $Tiempos['Arte']), 2);
			}
			if(0 < $Tiempos['Aprobacion'])
			{
				$Tiempos['Aprobacion'] = number_format(($Fila['aprobacion'] / $Tiempos['Aprobacion']), 2);
			}
			if(0 < $Tiempos['Final'])
			{
				$Tiempos['Final'] = number_format(($Fila['efinal'] / $Tiempos['Final']), 2);
			}
		}
		//echo microtime().'<br />';
		
		return $Tiempos;
		
	}
}
/* Fin del archivo */