<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregas_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *
	*/
	function pedidos()
	{
		
		$Pedidos = array(
			'Trabajos' => array(),
			'Materiales' => array(),
			'Mat_Lis' => array(),
			'Ruta' => array()
		);
		
		$Fecha = date('Y-m-d', strtotime('+ 4 days', strtotime(date('Y-m-d'))));
		
		$Consulta = '
			select codigo_cliente, proceso, proc.nombre, id_pedido, fecha_entrega
			from cliente clie, procesos proc, pedido ped
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and fecha_reale = "0000-00-00" and fecha_entrega <= "'.$Fecha.'"
			and fecha_entrega >= "2012-01-01" order by ped.fecha_entrega asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			
			foreach($Resultado->result_array() as $Fila)
			{
				$Pedidos['Trabajos'][$Fila['id_pedido']] = $Fila;
			}
			
		}
		
		
		
		
		$Consulta = '
			select ped.id_pedido, min(final), espe.id_material_solicitado_grupo
			from pedido ped, especificacion_matsolgru espe,
			material_solicitado_grupo grupo, material_solicitado soli
			where ped.id_pedido = espe.id_pedido
			and espe.id_material_solicitado_grupo = grupo.id_material_solicitado_grupo
			and grupo.id_material_solicitado = soli.id_material_solicitado
			and ped.fecha_reale = "0000-00-00" and ped.fecha_entrega <= "'.$Fecha.'"
			and ped.fecha_entrega >= "2012-01-01"
			group by ped.id_pedido
			order by ped.id_pedido asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			
			$Pedidos['Materiales'][$Fila['id_pedido']][$Fila['id_material_solicitado_grupo']] = '';
			$Pedidos['Mat_Lis'][$Fila['id_material_solicitado_grupo']] = '';
			
		}
		
		
		
		
		
		
		//Rutas de trabajo
		$Consulta = '
			select ped.id_pedido, peus.id_peus, dpto.iniciales, dpto.departamento, peus.estado, usu.usuario
			from pedido ped, pedido_usuario peus, usuario usu, departamentos dpto
			where ped.id_pedido = peus.id_pedido and peus.id_usuario = usu.id_usuario
			and usu.id_dpto = dpto.id_dpto and ped.fecha_reale = "0000-00-00"
			and ped.fecha_entrega <= "'.$Fecha.'" and ped.fecha_entrega >= "2012-01-01"
			order by ped.fecha_entrega asc, ped.id_pedido asc, peus.id_peus asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Pedidos['Ruta'][$Fila['id_pedido']][$Fila['id_peus']]['est'] = $Fila['estado'];
				$Pedidos['Ruta'][$Fila['id_pedido']][$Fila['id_peus']]['ini'] = $Fila['iniciales'];
				$Pedidos['Ruta'][$Fila['id_pedido']][$Fila['id_peus']]['dep'] = $Fila['departamento'];
				$Pedidos['Ruta'][$Fila['id_pedido']][$Fila['id_peus']]['usu'] = $Fila['usuario'];
			}
		}
		
		
		
		
		return $Pedidos;
		
	}
	
}

/* Fin del archivo */