<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Envio_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
  
	function listado($Id_Cliente, $Inicio, $Fin)
	{
		
		$Notas_Envio = array(
			'Notas' => array(),
			'Trabajos' => array(),
			'Materiales' => array()
		);
		
		$Consulta = '
			select nota.id_nota_env, correlativo, observacion
			from cliente clie, procesos proc, pedido ped, pedido_nota_material mate,
			pedido_nota_envio nota
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = mate.id_pedido and mate.id_nota_env = nota.id_nota_env
			and fecha >= "'.$Inicio.' 00:00:01" and fecha <= "'.$Fin.' 23:59:59"
			and clie.id_cliente = "'.$Id_Cliente.'"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			group by nota.id_nota_env
			order by nota.id_nota_env desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			
			$Notas_Envio['Notas'] = $Resultado->result_array();
			
		}
		
		
		
		$Consulta = '
			select distinct codigo_cliente, proceso, proc.nombre, ped.id_pedido,
			nota.id_nota_env
			from cliente clie, procesos proc, pedido ped, pedido_nota_material mate,
			pedido_nota_envio nota
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = mate.id_pedido and mate.id_nota_env = nota.id_nota_env
			and fecha >= "'.$Inicio.' 00:00:01" and fecha <= "'.$Fin.' 23:59:59"
			and clie.id_cliente = "'.$Id_Cliente.'"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by nota.id_nota_env desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Notas_Envio['Trabajos'][$Fila['id_nota_env']][] = $Fila;
			}
			
		}
		
		
		return $Notas_Envio;
		
	}
	
}

/* Fin del archivo */