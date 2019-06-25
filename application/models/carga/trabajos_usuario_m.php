<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Trabajos_usuario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function total_trabajos($Anho, $Mes, $Id_Usuario = '')
	{
		
		$Condicion = '';
		if('' != $Id_Usuario)
		{
			$Condicion = ' and id_usuario = "'.$Id_Usuario.'"';
		}
		
		if('Anual' == $Mes)
		{
			$SQL = 'fecha_inicio >= "'.$Anho.'-01-01 00:00:01"
							and fecha_inicio <= "'.$Anho.'-12-31 23:59:59"';
		}
		else
		{
			$SQL = 'fecha_inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
							and fecha_inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"';
		}
		
		$Consulta = '
			select count(id_peus) as tt_pedidos, id_usuario
			from pedido_usuario
			where
			'.$SQL.'
			'.$Condicion.'
			group by id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return array();
		}
		else
		{
			
			$Trabajos = array();
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Trabajos[$Fila['id_usuario']] = $Fila['tt_pedidos'];
			}
			
			return $Trabajos;
			
		}
		
	}
	
	
	
	function total_rechazos($Anho, $Mes, $Trabajos, $Id_Usuario = '')
	{
		
		$Condicion = '';
		if('' != $Id_Usuario)
		{
			$Condicion = ' and id_usuario = "'.$Id_Usuario.'"';
		}
		
		
		if('Anual' == $Mes)
		{
			$SQL = 'fecha >= "'.$Anho.'-01-01 00:00:01"
							and fecha <= "'.$Anho.'-12-31 23:59:59"';
		}
		else
		{
			$SQL = 'fecha >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
							and fecha <= "'.$Anho.'-'.$Mes.'-31 23:59:59"';
		}
		
		$Consulta = '
			select count(distinct fecha) as tt_rechazos, id_usuario
			from pedido_rechazo
			where  '.$SQL.'
			'.$Condicion.'
			group by id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return array();
		}
		else
		{
			
			$Rechazos = array();
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Rechazos[$Fila['id_usuario']] = $Fila['tt_rechazos'];
			}
			
			return $Rechazos;
			
		}
		
	}
	
	
	
	function tiempo_progr($Anho, $Mes, $Id_Usuario = '')
	{
		
		$Condicion = '';
		if('' != $Id_Usuario)
		{
			$Condicion = ' and id_usuario = "'.$Id_Usuario.'"';
		}
		
		if('Anual' == $Mes)
		{
			$SQL = 'fecha_inicio >= "'.$Anho.'-01-01 00:00:01"
							and fecha_inicio <= "'.$Anho.'-12-31 23:59:59"';
		}
		else
		{
			$SQL = 'fecha_inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
							and fecha_inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"';
		}
		$Consulta = '
			select id_usuario, sum(tiempo_asignado) as tprogramado
			from pedido_usuario
			where '.$SQL.'
			'.$Condicion.'
			group by id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return array();
		}
		else
		{
			
			$TProgramado = array();
			
			foreach($Resultado->result_array() as $Fila)
			{
				$TProgramado[$Fila['id_usuario']]['minutos'] = $Fila['tprogramado'];
				$TProgramado[$Fila['id_usuario']]['horas'] = floor($Fila['tprogramado'] / 60);
				$Modulo = ($Fila['tprogramado'] % 60);
				if(10 > $Modulo)
				{
					$Modulo = '0'.$Modulo;
				}
				$TProgramado[$Fila['id_usuario']]['horas'] =
					$TProgramado[$Fila['id_usuario']]['horas']
					.':'.$Modulo;
			}
			
			return $TProgramado;
			
		}
		
	}
	
	
	
	function tiempo_utili($Anho, $Mes, $Id_Usuario = '')
	{
		
		$Condicion = '';
		if('' != $Id_Usuario)
		{
			$Condicion = ' and ped_tie.id_usuario = "'.$Id_Usuario.'"';
		}
		
		if('Anual' == $Mes)
		{
			$SQL = 'and ped_tie.inicio >= "'.$Anho.'-01-01 00:00:01"	
							and ped_tie.inicio <= "'.$Anho.'-12-31 23:59:59"';
		}
		else
		{
			$SQL = 'and ped_tie.inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
							and ped_tie.inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"';
		}
		
		$TUtilizado = array();
		
		
		$Consulta = '
			select sum(ped_tie.tiempo) as tutilizado, ped_tie.id_usuario
			from pedido_tiempos ped_tie, usuario usu
			where ped_tie.id_usuario = usu.id_usuario
			'.$SQL.'
			and SUBSTRING(ped_tie.inicio, 12, 2) < 17
			'.$Condicion.' and usu.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by ped_tie.id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		
		if(0 == $Resultado->num_rows())
		{
			return $TUtilizado;
		}
		else
		{
			
			foreach($Resultado->result_array() as $Fila)
			{
				//$TUtilizado[$Fila['id_usuario']]['util_hab'] = $Fila['tutilizado'];
				$TUtilizado[$Fila['id_usuario']]['habil']['minutos'] = $Fila['tutilizado'];
				$TUtilizado[$Fila['id_usuario']]['habil']['horas'] = floor($Fila['tutilizado'] / 60);
				$Modulo = $Fila['tutilizado'] % 60;
				if(10 > $Modulo)
				{
					$Modulo = '0'.$Modulo;
				}
				$TUtilizado[$Fila['id_usuario']]['habil']['horas'] =
					$TUtilizado[$Fila['id_usuario']]['habil']['horas']
					.':'.$Modulo;
			}
			
		}
		
		
		
		
		
		$Consulta = '
			select sum(ped_tie.tiempo) as tutilizado, ped_tie.id_usuario
			from pedido_tiempos ped_tie, usuario usu
			where usu.id_usuario = ped_tie.id_usuario
			'.$SQL.'
			'.$Condicion.' and usu.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by ped_tie.id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		
		if(0 == $Resultado->num_rows())
		{
			return $TUtilizado;
		}
		else
		{
			
			foreach($Resultado->result_array() as $Fila)
			{
				$TUtilizado[$Fila['id_usuario']]['real']['minutos'] = $Fila['tutilizado'];
				$TUtilizado[$Fila['id_usuario']]['real']['horas'] = floor($Fila['tutilizado'] / 60);
				$Modulo = $Fila['tutilizado'] % 60;
				if(10 > $Modulo)
				{
					$Modulo = '0'.$Modulo;
				}
				$TUtilizado[$Fila['id_usuario']]['real']['horas'] =
					$TUtilizado[$Fila['id_usuario']]['real']['horas']
					.':'.$Modulo;
			}
			
		}	
		
		return $TUtilizado;
		
	}
	
	
	
	function listado_trabajos($Id_Usuario, $Anho, $Mes)
	{
		
		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, ped.id_pedido, fecha_fin,
			tiempo_asignado
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido and peus.id_usuario = "'.$Id_Usuario.'"
			and fecha_inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
			and fecha_inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
			order by codigo_cliente asc, fecha_inicio asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		if(0 < $Resultado->num_rows())
		{
			
			$Info_Usuario = array(
				'ListTrab' => array(),
				'ListClie' => array(),
				'ListTiem' => array(),
				'TotaTrab' => $Resultado->num_rows()
			);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Info_Usuario['ListTrab'][$Fila['codigo_cliente']][] = $Fila;
			}
			
			
			
			$Consulta = '
				select distinct codigo_cliente, clie.nombre
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and peus.id_usuario = "'.$Id_Usuario.'"
				and fecha_inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
				and fecha_inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
				order by codigo_cliente asc, fecha_inicio asc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Info_Usuario['ListClie'][$Fila['codigo_cliente']] = $Fila['nombre'];
			}
			
			
			
			
			$Consulta = '
				select peus.id_pedido, sum(tiempo) as tiempo
				from pedido_usuario peus, pedido_tiempos tiem
				where peus.id_pedido = tiem.id_pedido
				and tiem.id_usuario = peus.id_usuario
				and tiem.id_usuario = "'.$Id_Usuario.'"
				and fecha_inicio >= "'.$Anho.'-'.$Mes.'-01 00:00:01"
				and fecha_inicio <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
				group by tiem.id_pedido
				order by fecha_inicio asc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Info_Usuario['ListTiem'][$Fila['id_pedido']] = $Fila['tiempo'];
			}
			
			return $Info_Usuario;
			
		}
		else
		{
			return array();
		}
		
	}
	
	
}

/* Fin del archivo */