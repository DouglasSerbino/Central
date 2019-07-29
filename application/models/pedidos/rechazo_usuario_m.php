<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rechazo_usuario_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  
	function listado($Id_Usuario, $Anho, $Mes)
	{
		
		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, ped.id_pedido
			from cliente clie, procesos proc, pedido ped, pedido_rechazo pedre
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = pedre.id_pedido
				and fecha >= "'.$Anho.'-'.$Mes.'-01"
				and fecha <= "'.$Anho.'-'.$Mes.'-31"
				and pedre.id_usuario = "'.$Id_Usuario.'"
			group by ped.id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 == $Resultado->num_rows())
		{
			return array();
		}
		else
		{
			return $Resultado->result_array();
		}
	}
}

/* Fin del archivo */