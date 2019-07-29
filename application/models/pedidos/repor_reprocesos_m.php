<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repor_reprocesos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Todos los pedidos que pertenecen a este proceso.
	 *@return nada.
	*/
	function Info_reproceso($Id_Pedido)
	{		
		$Consulta = '
				select *
				from pedido_adjunto_reproceso
				where id_pedido = "'.$Id_Pedido.'"
				order by id_pedido_adjunto_reproceso desc
			';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				return $Resultado->row_array();
			}
			else
			{
				return array();
			}
	}
	
	
	/**
	 *Busca el total de los pedidos que ha finalizado el usuario senhalado.
	 *@param string $Id_Usuario.
	 *@return string $Total_Pedidos.
	*/
	function fin_usuario_total($Id_Usuario)
	{
		$Consulta = '
			select count(distinct id_pedido) as tt_pedidos
			from pedido_usuario peus, usuario usu
			where peus.id_usuario = usu.id_usuario and estado = "Terminado"
			and peus.id_usuario = "'.$Id_Usuario.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Total_Pedidos = 0;
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Total_Pedidos = $Fila['tt_pedidos'];
			$Total_Pedidos++;
		}
		
		return $Total_Pedidos;
		
	}
	
	
	/**
	 *Busca el listado de los pedidos que ha finalizado el usuario senhalado segun
	 *el rango recibido.
	 *@param string $Id_Usuario.
	 *@param string $Inicio.
	 *@return array.
	*/
	function fin_usuario($Id_Usuario, $Inicio, $tipo)
	{
		$Trabajos = array();
		
		
		$Consulta = '
			select ped.id_pedido, codigo_cliente, proceso,
			proc.nombre, fecha_inicio,
			fecha_fin
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido and peus.id_usuario = "'.$Id_Usuario.'"
			and id_grupo = "'.$this->session->userdata('id_grupo').'"
			and estado = "Terminado"
			order by fecha_fin desc
			limit '.$Inicio.', 50
		';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Trabajos[$Fila['id_pedido']]['producto'] = '';
				$Trabajos[$Fila['id_pedido']]['id_pedido'] = $Fila['id_pedido'];
				$Trabajos[$Fila['id_pedido']]['codigo_cliente'] = $Fila['codigo_cliente'];
				$Trabajos[$Fila['id_pedido']]['proceso'] = $Fila['proceso'];
				$Trabajos[$Fila['id_pedido']]['nombre'] = $Fila['nombre'];
				$Trabajos[$Fila['id_pedido']]['fecha_inicio'] = $Fila['fecha_inicio'];
				$Trabajos[$Fila['id_pedido']]['fecha_fin'] = $Fila['fecha_fin'];
			
			$Consulta = '
				select material_solicitado, ped.id_pedido
				from pedido_usuario peus, pedido ped, especificacion_matsolgru espe,
					material_solicitado_grupo solgru, material_solicitado mate
				where peus.id_pedido = ped.id_pedido and ped.id_pedido = espe.id_pedido
					and espe.id_material_solicitado_grupo = solgru.id_material_solicitado_grupo
					and solgru.id_material_solicitado = mate.id_material_solicitado
					'.$SQL.'
					and ped.id_pedido = "'.$Fila['id_pedido'].'"
				order by final desc
			';
			//echo $Consulta.'<br><br>';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Trabajos[$Fila['id_pedido']]['producto'] = $Fila['material_solicitado'];
				}
			}
		}
		}
		
		//print_r($Trabajos);
		return $Trabajos;		
	}
	
	
	function clientes_offset()
	{
		$Consulta = 'select distinct codigo_cliente, clie.nombre
								from cliente clie, procesos proc, pedido ped, pedido_usuario peus
								where clie.id_cliente = proc.id_cliente
								and proc.id_proceso = ped.id_proceso
								and ped.id_pedido = peus.id_pedido
								and peus.id_usuario = "43"
								and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
								and estado = "Terminado"
								ORDER BY codigo_cliente asc';
		
		$Resultado = $this->db->query($Consulta);
			
		if(0 < $Resultado->num_rows())
		{
			//print_r($Resultado->result_array());
			return $Resultado->result_array();
		}
		else
		{
			return array();
		}
	}
	
}

/* Fin del archivo */