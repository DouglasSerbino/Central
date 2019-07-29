<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preingreso_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Buscar los pedidos que estan en el puesto de este usuario.
	 *@param string $Estado.
	 *@param string $Id_Cliente.
	 *@return array.
	*/
	function listado($Estado, $Id_Cliente = 0, $Todos = 'no')
	{
		
		$Id_Cliente += 0;
		
		$Condicion_Cliente = '';
		
		if(0 < $Id_Cliente)
		{
			$Condicion_Cliente = 'and proc.id_cliente = "'.$Id_Cliente.'"';
		}
		
		
		if(
			'Plani' == $this->session->userdata('codigo')
			|| 'Ventas' == $this->session->userdata('codigo')
		)
		{
			//Pedidos para plani o ventas
			$Consulta = '
				select proc.id_proceso, ped.id_pedido, proceso, proc.nombre as proceso_nombre,
				clie.id_cliente, fecha_entrada, fecha_entrega, codigo_cliente as codc, id_peus, codigo, tipo
				from departamentos dpto, usuario usu, pedido_usuario peus, pedido ped,
					procesos proc, cliente clie
				where dpto.id_dpto = usu.id_dpto and usu.id_usuario = peus.id_usuario
					and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
					and proc.id_cliente = clie.id_cliente and estado = "Asignado"
					and dpto.codigo = "'.$this->session->userdata('codigo').'"
					and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
					'.$Condicion_Cliente.'
			';
		}
		else
		{
			//Pedidos para este usuario
			$Consulta = '
				select proc.id_proceso, ped.id_pedido, proceso, proc.nombre as proceso_nombre,
				clie.id_cliente, fecha_entrada, fecha_entrega, codigo_cliente as codc, id_peus, codigo, tipo
				from departamentos dpto, usuario usu, pedido_usuario peus, pedido ped,
				procesos proc, cliente clie
				where dpto.id_dpto = usu.id_dpto and usu.id_usuario = peus.id_usuario
				and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
				and proc.id_cliente = clie.id_cliente and estado = "Asignado"
				and (dpto.codigo = "Plani" or dpto.codigo = "Ventas")
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				'.$Condicion_Cliente.'
			';
		}
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		$Informacion  = array();
		$Pedidos_v = array();
		$Tipos_v = array();
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Datos)
			{
				$Pedidos_v[] = $Datos['id_pedido'];
				$Tipos_v[$Datos['tipo']] = $Datos['tipo'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['tipo'] = $Datos['tipo'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['tipo'] = $Datos['tipo'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['codigo'] = $Datos['codigo'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['id_peus'] = $Datos['id_peus'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['proceso'] = $Datos['proceso'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['id_pedido'] = $Datos['id_pedido'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['id_proceso'] = $Datos['id_proceso'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['id_cliente'] = $Datos['id_cliente'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['fecha_entrada'] = $Datos['fecha_entrada'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['fecha_entrega'] = $Datos['fecha_entrega'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['codc'] = $Datos['codc'];
				$Informacion[$Datos['tipo']][$Datos['codc']][$Datos['id_pedido']]['proceso_nombre'] = $Datos['proceso_nombre'];
			}
			
			
			
			$Consulta = '
				select obs.observacion, ped.id_pedido, codigo_cliente, tipo
				from departamentos dpto, usuario usu, pedido_usuario peus, pedido ped,
				procesos proc, cliente clie, observacion obs
				where dpto.id_dpto = usu.id_dpto and usu.id_usuario = peus.id_usuario
				and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
				and proc.id_cliente = clie.id_cliente and estado = "Asignado"
				and (dpto.codigo = "Plani" or dpto.codigo = "Ventas")
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				and obs.id_pedido = ped.id_pedido
				and obs.observacion like "%Pedido solicitado por:%"
				'.$Condicion_Cliente.'
			';
			
			$Resultado = $this->db->query($Consulta);
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Datos)
				{
					if(isset($Informacion[$Datos['tipo']][$Datos['codigo_cliente']][$Datos['id_pedido']]))
					{
						$Informacion[$Datos['tipo']][$Datos['codigo_cliente']][$Datos['id_pedido']]['observacion'] = substr($Datos['observacion'], 23);
					}
				}
			}
			
			
			
			if(isset($Informacion[0]))
			{
				ksort($Informacion[0]);
			}
			//print_r($Informacion); exit();
			return $Informacion;
		}
		else
		{
			return array();
		}
		
	}
	
	/*
	 *Mostramos los procesos que estan pendientes de aprobacion por el departamento de ingenieria
	 *Se mostraran los procesos que ya han sido finalizados por central-g.
	*/
	function Aprobacion_Separados()
	{
			//Pedidos para este usuario
			$Consulta = '
				select proc.id_proceso, ped.id_pedido, proceso, proc.nombre as proceso_nombre,
				clie.id_cliente, fecha_entrada, fecha_entrega, codigo_cliente as codc
				from pedido ped,  procesos proc, cliente clie, aprobacion_separados apro
				where ped.id_proceso = proc.id_proceso
					and proc.id_cliente = clie.id_cliente
					and ped.id_pedido = apro.id_pedido
					and mostrar_dpto = "s"
					and aprobacion_finalizado = "n"
			';
			
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
	
	
  /**
	 *Buscar los pedidos pendientes de ingreso por parte de planificacion.
	 *@param string $Estado.
	 *@return array.
	*/
	function listado_todos($Estado)
	{
		
		//Pedidos para este usuario
		$Consulta = '
			select proc.id_proceso, ped.id_pedido, proceso, proc.nombre as proceso_nombre,
				clie.id_cliente, fecha_entrada, fecha_entrega
			from departamentos dpto, usuario usu, pedido_usuario peus, pedido ped,
				procesos proc, cliente clie
			where dpto.id_dpto = usu.id_dpto and usu.id_usuario = peus.id_usuario
				and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
				and proc.id_cliente = clie.id_cliente and estado = "Asignado"
				and (dpto.codigo = "Plani" or dpto.codigo = "Ventas")
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
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
	
	
	function total($Venta_Plani)
	{
		
		$Consulta = '
			select count(ped.id_pedido) as total
			from departamentos dpto, usuario usu, pedido_usuario peus, pedido ped,
				procesos proc, cliente clie
			where dpto.id_dpto = usu.id_dpto and usu.id_usuario = peus.id_usuario
				and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
				and proc.id_cliente = clie.id_cliente and estado = "Asignado"
				and dpto.codigo = "'.$Venta_Plani.'"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Total = $Fila['total'] + 0;
			
			return $Total;
		}
		else
		{
			return 0;
		}
		
	}
}

/* Fin del archivo */