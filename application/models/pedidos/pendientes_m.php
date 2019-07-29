<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendientes_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 *Buscar los pedidos que estan en el puesto de este usuario.
	 *@param string $Id_Proceso.
	 *@return string 'activo','inactivo'.
	*/
	function listado($Estado)
	{
		
		$Trabajos = array();
		
		//Pedidos para este usuario
		$SQL = ' and peus.id_usuario = "'.$this->session->userdata('id_usuario').'"';
		if(3 == $this->session->userdata('id_dpto'))
		{
			$SQL = ' and id_dpto = "'.$this->session->userdata('id_dpto').'"';
		}
		
		$Consulta = '
			select proc.id_proceso, ped.id_pedido, estado, proceso,
			proc.nombre as proceso_nombre, clie.id_cliente, fecha_entrega,
			fecha_asignado, prioridad, tiempo_asignado, codigo_cliente
			from usuario usu, pedido_usuario peus, pedido ped, procesos proc, cliente clie
			where peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
			and usu.id_usuario = peus.id_usuario
			and proc.id_cliente = clie.id_cliente and estado != "Terminado"
			'.$SQL.'
			order by ped.ped_prioridad asc, ped.fecha_entrega asc, ped.id_pedido asc, peus.id_peus desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Consulta2 = '
						select imagen.url, proc.id_proceso, imagen.nombre_adjunto
						from procesos proc, proceso_imagenes imagen
						where proc.id_proceso = imagen.id_proceso
						and proc.id_proceso = "'.$Fila['id_proceso'].'"
						order by id_proceso_imagenes asc
					';
					//echo '<br />'.$Consulta2.'<br />';
					$Resultado2 = $this->db->query($Consulta2);
					
					if(0 < $Resultado2->num_rows())
					{
						foreach($Resultado2->result_array() as $Fila2)
						{
							$Trabajos[$Fila['id_pedido']]['url'] = $Fila2['url'];
							$Trabajos[$Fila['id_pedido']]['nombre_adjunto'] = $Fila2['nombre_adjunto'];
						}
					}
					else
					{
						$Trabajos[$Fila['id_pedido']]['url'] = '';
						$Trabajos[$Fila['id_pedido']]['nombre_adjunto'] = '';
					}
				
				$Trabajos[$Fila['id_pedido']]['producto'] = '';
				$Trabajos[$Fila['id_pedido']]['estado'] = $Fila['estado'];
				$Trabajos[$Fila['id_pedido']]['proceso'] = $Fila['proceso'];
				$Trabajos[$Fila['id_pedido']]['prioridad'] = $Fila['prioridad'];
				$Trabajos[$Fila['id_pedido']]['id_proceso'] = $Fila['id_proceso'];
				$Trabajos[$Fila['id_pedido']]['id_cliente'] = $Fila['id_cliente'];
				$Trabajos[$Fila['id_pedido']]['fecha_entrega'] = $Fila['fecha_entrega'];
				$Trabajos[$Fila['id_pedido']]['fecha_asignado'] = $Fila['fecha_asignado'];
				$Trabajos[$Fila['id_pedido']]['proceso_nombre'] = $Fila['proceso_nombre'];
				$Trabajos[$Fila['id_pedido']]['codigo_cliente'] = $Fila['codigo_cliente'];
				$Trabajos[$Fila['id_pedido']]['tiempo_asignado'] = $Fila['tiempo_asignado'];
				
			}
			
			
			$Consulta = '
				select material_solicitado, ped.id_pedido
				from pedido_usuario peus, pedido ped, especificacion_matsolgru espe,
					material_solicitado_grupo solgru, material_solicitado mate
				where peus.id_pedido = ped.id_pedido and ped.id_pedido = espe.id_pedido
					and espe.id_material_solicitado_grupo = solgru.id_material_solicitado_grupo
					and solgru.id_material_solicitado = mate.id_material_solicitado
					and estado != "Terminado" and peus.id_usuario = "'.$this->session->userdata('id_usuario').'"
				order by final desc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Trabajos[$Fila['id_pedido']]['producto'] = $Fila['material_solicitado'];
				}
			}
		}
		
		return $Trabajos;
		
	}
	
	/**
	 *Busca la informacion de la tabla pedido_usuario para el pedido y usuario actual
	 *@param string $Id_Pedido.
	 *@return array con los datos o 0 si no hay resultado.
	*/
	function pedido_usuario($Id_Pedido)
	{
		$SQL = ' and peus.id_usuario = "'.$this->session->userdata('id_usuario').'"';
		if(3 == $this->session->userdata('id_dpto'))
		{
			$SQL = ' and id_dpto = "'.$this->session->userdata('id_dpto').'"';
		}
		//Pedidos para este usuario
		$Consulta = '
			select peus.id_peus, peus.estado,
			peus.fecha_asignado, peus.tiempo_asignado,
			peus.fecha_inicio, ped.id_tipo_trabajo
			from pedido_usuario peus, usuario usu, pedido ped
			where peus.id_pedido = "'.$Id_Pedido.'"
			and ped.id_pedido = peus.id_pedido
			and usu.id_usuario = peus.id_usuario
			and (peus.estado = "Procesando"
			or peus.estado = "Asignado"
			or peus.estado = "Aprobacion"
			or peus.estado = "Pausado")
			'.$SQL.'
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(1 ==  $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			return 0;
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function listado_todos($Estado)
	{
		
		$Trabajos = array();
		
		//Pedidos para este usuario
		$Consulta = '
			select proc.id_proceso, ped.id_pedido, estado, proceso,
			proc.nombre as proceso_nombre, clie.id_cliente, fecha_entrega,
			fecha_asignado, prioridad, tiempo_asignado, codigo_cliente
			from usuario usu, pedido_usuario peus, pedido ped, procesos proc, cliente clie
			where usu.id_usuario = peus.id_usuario and peus.id_pedido = ped.id_pedido and ped.id_proceso = proc.id_proceso
			and proc.id_cliente = clie.id_cliente and estado != "Terminado"
			and id_dpto = "'.$this->session->userdata('id_dpto').'"
			order by ped.ped_prioridad asc, ped.fecha_entrega asc, ped.id_pedido asc, peus.id_peus desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Consulta2 = '
						select imagen.url, proc.id_proceso, imagen.nombre_adjunto
						from procesos proc, proceso_imagenes imagen
						where proc.id_proceso = imagen.id_proceso
						and proc.id_proceso = "'.$Fila['id_proceso'].'"
						order by id_proceso_imagenes asc
					';
					//echo '<br />'.$Consulta2.'<br />';
					$Resultado2 = $this->db->query($Consulta2);
					
					if(0 < $Resultado2->num_rows())
					{
						foreach($Resultado2->result_array() as $Fila2)
						{
							$Trabajos[$Fila['id_pedido']]['url'] = $Fila2['url'];
							$Trabajos[$Fila['id_pedido']]['nombre_adjunto'] = $Fila2['nombre_adjunto'];
						}
					}
					else
					{
						$Trabajos[$Fila['id_pedido']]['url'] = '';
						$Trabajos[$Fila['id_pedido']]['nombre_adjunto'] = '';
					}
				
				$Trabajos[$Fila['id_pedido']]['producto'] = '';
				$Trabajos[$Fila['id_pedido']]['estado'] = $Fila['estado'];
				$Trabajos[$Fila['id_pedido']]['proceso'] = $Fila['proceso'];
				$Trabajos[$Fila['id_pedido']]['prioridad'] = $Fila['prioridad'];
				$Trabajos[$Fila['id_pedido']]['id_proceso'] = $Fila['id_proceso'];
				$Trabajos[$Fila['id_pedido']]['id_cliente'] = $Fila['id_cliente'];
				$Trabajos[$Fila['id_pedido']]['fecha_entrega'] = $Fila['fecha_entrega'];
				$Trabajos[$Fila['id_pedido']]['fecha_asignado'] = $Fila['fecha_asignado'];
				$Trabajos[$Fila['id_pedido']]['proceso_nombre'] = $Fila['proceso_nombre'];
				$Trabajos[$Fila['id_pedido']]['codigo_cliente'] = $Fila['codigo_cliente'];
				$Trabajos[$Fila['id_pedido']]['tiempo_asignado'] = $Fila['tiempo_asignado'];
				
			}
			
			
			$Consulta = '
				select material_solicitado, ped.id_pedido
				from usuario usu, pedido_usuario peus, pedido ped, especificacion_matsolgru espe,
				material_solicitado_grupo solgru, material_solicitado mate
				where usu.id_usuario = peus.id_usuario and peus.id_pedido = ped.id_pedido
				and ped.id_pedido = espe.id_pedido
				and espe.id_material_solicitado_grupo = solgru.id_material_solicitado_grupo
				and solgru.id_material_solicitado = mate.id_material_solicitado
				and estado != "Terminado" and id_dpto = "'.$this->session->userdata('id_dpto').'"
				order by final desc
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					$Trabajos[$Fila['id_pedido']]['producto'] = $Fila['material_solicitado'];
				}
			}
		}
		
		return $Trabajos;
		
	}
	
	function pedido_usuario_todos($Id_Pedido)
	{
		
		//Pedidos para este usuario
		$Consulta = '
			select peus.id_peus, peus.estado, peus.fecha_asignado, peus.tiempo_asignado,
			peus.fecha_inicio, ped.id_tipo_trabajo
			from usuario usu, pedido_usuario peus, pedido ped
			where usu.id_usuario = peus.id_usuario and peus.id_pedido = "'.$Id_Pedido.'"
			and ped.id_pedido = peus.id_pedido
			and (peus.estado = "Procesando"
			or peus.estado = "Asignado" or peus.estado = "Aprobacion" or peus.estado = "Pausado")
			and id_dpto = "'.$this->session->userdata('id_dpto').'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(1 ==  $Resultado->num_rows())
		{
			return $Resultado->row_array();
		}
		else
		{
			return 0;
		}
	}
	
	
	
	
	
	
}

/* Fin del archivo */