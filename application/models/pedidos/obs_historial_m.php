<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class obs_historial_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	function observaciones(
		$Id_pedido,
		$Id_Proceso
	)
	{
		
		$Procesos_v = array($Id_Proceso);
		
		//Se necesita saber el id_proceso del pedido enlazado, si lo hubiere.
		
		//Quiza es un hijo
		$Consulta = '
			select id_proceso
			from pedido ped, pedido_pedido pedped
			where id_pedido = id_ped_primario
			and id_ped_secundario = "'.$Id_pedido.'"
			group by id_proceso
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Procesos_v[] = $Fila['id_proceso'];
		}
		
		//Quiza es un padre
		$Consulta = '
			select id_proceso
			from pedido ped, pedido_pedido pedped
			where id_pedido = id_ped_secundario
			and id_ped_primario = "'.$Id_pedido.'"
			group by id_proceso
		';
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Procesos_v[] = $Fila['id_proceso'];
		}
		
		
		$Consulta = '
			select obs.fecha_hora, obs.observacion, usu.usuario, ped.id_pedido
			from pedido ped, observacion obs, usuario usu
			where ped.id_pedido = obs.id_pedido and obs.id_usuario = usu.id_usuario
			and id_proceso in ('.implode(',', $Procesos_v).')
			order by obs.fecha_hora desc
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		if($Resultado->num_rows() >= 1)
		{
			
			return $Resultado->result_array();
			
		}
		else
		{
				return '';	
		}	
	}
	
}

/* Fin del archivo */