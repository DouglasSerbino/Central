<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tarea_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	
	function ingresar($Tarea, $Fecha_Fin)
	{
		
		$Puestos = array(
			13 => 1,
			11 => 2,
			25 => 3
		);
		
		
		$Consulta = '
			select id_usu_asignado
			from tareas
			order by id_tarea desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);
		
		$Asignado = $Resultado->row_array();
		$Asignado = $Puestos[$Asignado['id_usu_asignado']] + 1;
		if(3 < $Asignado)
		{
			$Asignado = 1;
		}
		
		
		$Consulta = '
			insert into tareas values(
				NULL,
				"'.$this->session->userdata('id_usuario').'",
				0,
				"'.$Tarea.'",
				"",
				"'.date('Y-m-d H:i:s').'",
				"0000-00-00 00:00:00",
				"'.array_search($Asignado, $Puestos).'",
				"'.$Fecha_Fin.'"
			)
		';
		
		$this->db->query($Consulta);
		
	}
	
	
	
	function finalizar($Id_Tarea, $Comentario)
	{
		
		$Consulta = '
			update tareas
			set fecha_realizada = "'.date('Y-m-d H:i:s').'",
			id_usu_realizado = "'.$this->session->userdata('id_usuario').'",
			observaciones = "'.$Comentario.'"
			where id_tarea = "'.$Id_Tarea.'"
		';
		
		$this->db->query($Consulta);
		
		
		$Consulta = '
			insert into pedido_tiempos values(
				NULL,
				68699,
				"'.$this->session->userdata('id_usuario').'",
				"'.date('Y-m-d H:i:s', strtotime('-15 minutes', strtotime(date('Y-m-d H:i:s')))).'",
				"'.date('Y-m-d H:i:s').'",
				"15",
				0
			)
		';
		$this->db->query($Consulta);
		
	}
	
	
	
	function incompletas()
	{
		
		$Consulta = '
			select count(id_tarea) as ttarea
			from tareas
			where fecha_realizada = "0000-00-00 00:00:00"
		';
		
		if('410' == $this->session->userdata('codigo'))
		{
			$Consulta .= '
				and id_usu_asignado = "'.$this->session->userdata('id_usuario').'"
			';
		}
		
		$Resultado = $this->db->query($Consulta);
		
		$Tareas_Pendientes = 0;
		
		if(0 < $Resultado->num_rows())
		{
			
			$Fila = $Resultado->row_array();
			$Tareas_Pendientes = $Fila['ttarea'] += 0;
			
		}
		
		return $Tareas_Pendientes;
		
	}
	
	
	
	function finalizadas()
	{
		
		$Consulta = '
			select count(id_tarea) as ttarea
			from tareas
			where fecha_realizada != "0000-00-00 00:00:00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Tareas_Pendientes = 0;
		
		if(0 < $Resultado->num_rows())
		{
			
			$Fila = $Resultado->row_array();
			$Tareas_Pendientes = $Fila['ttarea'] += 0;
			
		}
		
		return $Tareas_Pendientes;
		
	}
	
	
	
	function listar_incompletas()
	{
		
		$Tareas = array();
		
		$Condicion = '';
		if('410' == $this->session->userdata('codigo'))
		{
			$Condicion = 'and id_usu_asignado = "'.$this->session->userdata('id_usuario').'"';
		}
		
		$Consulta = '
			select usuario, id_tarea, tarea, fecha_creada
			from tareas tare, usuario usu
			where tare.id_usu_creado = usu.id_usuario and fecha_realizada = "0000-00-00 00:00:00"
			'.$Condicion.'
			order by fecha_creada asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Tareas[$Fila['id_tarea']]['tarea'] = $Fila['tarea'];
			$Tareas[$Fila['id_tarea']]['solicitado'] = $Fila['usuario'];
			$Tareas[$Fila['id_tarea']]['fecha_creada'] = $Fila['fecha_creada'];
			$Tareas[$Fila['id_tarea']]['asignado'] = '';
			$Tareas[$Fila['id_tarea']]['fecha_estimada'] = '';
			
		}
		
		
		
		$Consulta = '
			select usuario, id_tarea, fecha_estimada
			from tareas tare, usuario usu
			where tare.id_usu_asignado = usu.id_usuario and fecha_realizada = "0000-00-00 00:00:00"
			'.$Condicion.'
			order by fecha_creada asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Tareas[$Fila['id_tarea']]['asignado'] = $Fila['usuario'];
			$Tareas[$Fila['id_tarea']]['fecha_estimada'] = $Fila['fecha_estimada'];
			
		}
		
		
		
		return $Tareas;
		
	}
	
	
	
	function listar_finalizadas($Inicio)
	{
		
		$Tareas = array();
		
		$Consulta = '
			select usuario, id_tarea, tarea, observaciones, fecha_creada, fecha_realizada
			from tareas tare, usuario usu
			where tare.id_usu_creado = usu.id_usuario and fecha_realizada != "0000-00-00 00:00:00"
			order by fecha_realizada desc, id_tarea asc
			limit '.$Inicio.', 50
		';
		//echo $Consulta.'<br />';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Tareas[$Fila['id_tarea']]['realizado'] = '';
			$Tareas[$Fila['id_tarea']]['tarea'] = $Fila['tarea'];
			$Tareas[$Fila['id_tarea']]['solicitado'] = $Fila['usuario'];
			$Tareas[$Fila['id_tarea']]['fecha_creada'] = $Fila['fecha_creada'];
			$Tareas[$Fila['id_tarea']]['observaciones'] = $Fila['observaciones'];
			$Tareas[$Fila['id_tarea']]['fecha_realizada'] = $Fila['fecha_realizada'];
			
		}
		
		
		
		$Consulta = '
			select usuario, id_tarea
			from tareas tare, usuario usu
			where tare.id_usu_realizado = usu.id_usuario and fecha_realizada != "0000-00-00 00:00:00"
			order by fecha_realizada desc, id_tarea asc
			limit '.$Inicio.', 50
		';
		//echo $Consulta.'<br />';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Tareas[$Fila['id_tarea']]['realizado'] = $Fila['usuario'];
			
		}
		
		
		
		return $Tareas;
		
	}
	
	
	
}

/* Fin del archivo */