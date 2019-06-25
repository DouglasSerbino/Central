<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curiosos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  
	function rarosos($Calculo)
	{
		
		$Pedidos_Curiosos = array(
			'Totales' => 0,
			'Pedidos' => array()
		);
		
		
		
		//Es probable que existan trabajos finalizados y posean un puesto sin "Terminado"
		$Consulta = '
			select count(ped.id_pedido) as total
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and ped.id_pedido = peus.id_pedido and fecha_reale != "0000-00-00"
			and estado != "Terminado" and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->row_array();
			$Pedidos_Curiosos['Totales'] += $Fila['total'];
		}
		
		if('totales' == $Calculo)
		{
			
			//Tambien es probable que exista un pedido sin finalizar pero nadie lo tenga
			//en su puesto de trabajo
			//Cuantos trabajos hay ingresados
			$Consulta = '
				select count(id_pedido) as total
				from cliente clie, procesos proc, pedido ped
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and fecha_reale = "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				$Fila = $Resultado->row_array();
				$Pedidos_Curiosos['Totales'] += $Fila['total'];
			}
			
			//Luego se obtiene el numero de trabajos que estan asignados
			$Consulta = '
				select count(ped.id_pedido) as total
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and fecha_reale = "0000-00-00"
				and (estado = "Asignado" or estado = "Procesando" or estado = "Pausado")
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				$Fila = $Resultado->row_array();
				$Pedidos_Curiosos['Totales'] -= $Fila['total'];
			}
			
			
			
			/*
			//Pedidos padres con tiempos sin finalizar y pedidos hijos finalizados
			$Consulta = '
				select id_ped_secundario
				from pedido_pedido ppdd, pedido ped
				where ppdd.id_ped_primario = ped.id_pedido and fecha_reale = "0000-00-00"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				$Ides_Pedidos = array();
				foreach($Resultado->result_array() as $Fila)
				{
					$Ides_Pedidos[] = 'id_pedido = "'.$Fila['id_ped_secundario'].'"';
				}
				
				$Consulta = '
					select count(id_pedido) as pedidos
					from pedido
					where fecha_reale != "0000-00-00" and ('.implode(' or ', $Ides_Pedidos).')
				';
				
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					$Fila = $Resultado->row_array();
					$Pedidos_Curiosos['Totales'] += ($Fila['pedidos'] + 0);
				}
				
			}
			*/
			
			
			return $Pedidos_Curiosos['Totales'];
			
		}
		else
		{
			//Tambien es probable que exista un pedido sin finalizar pero nadie lo tenga
			//en su puesto de trabajo
			//Cuantos trabajos hay ingresados
			$Consulta = '
				select ped.id_pedido
				from cliente clie, procesos proc, pedido ped
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.fecha_reale = "0000-00-00"
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				order by ped.id_pedido
			';
			
			$Resultado = $this->db->query($Consulta);
			
			$Pedidos_Vivos = array();
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Pedidos_Vivos[] = 'id_pedido='.$Fila['id_pedido'];
				}
				$Pedidos_Curiosos['Totales'] += $Resultado->num_rows();
				//echo $Resultado->num_rows().'<br />';
			}
			
			//print_r($Pedidos_Vivos);
			//echo '<br />';
			
			
			//Luego se obtiene el numero de trabajos que estan asignados
			$Consulta = '
				select ped.id_pedido
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and fecha_reale = "0000-00-00"
				and (peus.estado = "Asignado" or peus.estado = "Procesando" or peus.estado = "Pausado")
				and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
				order by ped.id_pedido
			';
			
			$Resultado = $this->db->query($Consulta);
			
			$Pedidos_Asignados = array();
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Pedidos_Asignados[] = 'id_pedido='.$Fila['id_pedido'];
				}
				$Pedidos_Curiosos['Totales'] -= $Resultado->num_rows();
				//echo $Resultado->num_rows().'<br />';
			}
			
			//print_r($Pedidos_Asignados);
			//echo '<br />';
			
			$Pedidos_Rarosos = array_diff($Pedidos_Vivos, $Pedidos_Asignados);
			
			//print_r($Pedidos_Rarosos);
			
			
			//Al array de pedidos rarosos hay que agregar el que esta terminado y no esta terminado
			$Consulta = '
				select ped.id_pedido
				from cliente clie, procesos proc, pedido ped, pedido_usuario peus
				where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
				and ped.id_pedido = peus.id_pedido and fecha_reale != "0000-00-00"
				and peus.estado != "Terminado" and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Pedidos_Rarosos[] = 'id_pedido='.$Fila['id_pedido'];
				}
			}
			
			//print_r($Pedidos_Rarosos);
			if(0 < count($Pedidos_Rarosos))
			{
				//Informacion de los pedidos rarosos
				$Consulta = '
					select codigo_cliente, proceso, proc.nombre, id_pedido, fecha_entrada,
					fecha_entrega, fecha_reale, clie.id_grupo
					from cliente clie, procesos proc, pedido ped
					where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
					and ('.implode(' or ', $Pedidos_Rarosos).')
					order by fecha_reale asc, fecha_entrega asc
				';
				
				$Resultado = $this->db->query($Consulta);
				
				foreach($Resultado->result_array() as $Fila)
				{
					$Pedidos_Curiosos['Pedidos'][] = $Fila;
				}
			}
			
			
			/*
			//Pedidos padres con tiempos sin finalizar y pedidos hijos finalizados
			$Consulta = '
				select id_ped_secundario
				from pedido_pedido ppdd, pedido ped
				where ppdd.id_ped_primario = ped.id_pedido and fecha_reale = "0000-00-00"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				$Ides_Pedidos = array();
				foreach($Resultado->result_array() as $Fila)
				{
					$Ides_Pedidos[] = 'id_pedido = "'.$Fila['id_ped_secundario'].'"';
				}
				
				$Consulta = '
					select codigo_cliente, proceso, concat(proc.nombre, " [Hijo]") as nombre, id_pedido, fecha_entrada,
					fecha_entrega, fecha_reale, clie.id_grupo
					from cliente clie, procesos proc, pedido ped
					where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
					and fecha_reale != "0000-00-00" and ('.implode(' or ', $Ides_Pedidos).')
				';
				
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					foreach($Resultado->result_array() as $Fila)
					{
						$Pedidos_Curiosos['Pedidos'][] = $Fila;
					}
				}
				
			}
			*/
			
			
			
			
			
			
			return $Pedidos_Curiosos;
			
		}
		
		
		
	}
	
	
}

/* Fin del archivo */