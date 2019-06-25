<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyectos_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	function info_pedidos($Tipo_Proy, $Proceso = '', $Mes, $Anho)
	{
		
		$Where = '';
		$Condicion = array();
		
		$Activo = 's';
		$Condicion = '';
		if('finalizados' == $Tipo_Proy)
		{
			$Activo = 'n';
			$Condicion = '
			and fecha >= "'.$Anho.'-'.$Mes.'-01 00:00:00"
			and fecha <= "'.$Anho.'-'.$Mes.'-31 23:59:59"
			';
		}
		
		$Consulta = '
			SELECT id_proyecto, proyecto, tiempo_venta, tiempo_cs, tiempo_repro
			from proyecto_tiempo
			where activo = "'.$Activo.'"'.$Condicion.'
			
			order by id_proyecto desc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//echo $Consulta."\n";
		
		$Id_Proyectos = array();
		$Proyectos = array(
			'p' => array(),
			't' => array(
				'cs' => 0,'rp' => 0,'ap' => 0,'pcs' => 0,'prp' => 0,'pap' => 0
			)
		);
		if(8 != $this->session->userdata('id_grupo'))
		{
			foreach($Resultado->result_array() as $Fila)
			{
				$Id_Proyectos[] = $Fila['id_proyecto'];
				$Proyectos['p'][$Fila['id_proyecto']]['proy'] = $Fila['proyecto'];
				$Proyectos['p'][$Fila['id_proyecto']]['vent'] = $Fila['tiempo_venta'];
				$Proyectos['p'][$Fila['id_proyecto']]['plan'] = $Fila['tiempo_cs'];
				$Proyectos['p'][$Fila['id_proyecto']]['repr'] = $Fila['tiempo_repro'];
				$Proyectos['t']['cs'] += $Fila['tiempo_cs'];
				$Proyectos['t']['rp'] += $Fila['tiempo_repro'];
				$Proyectos['t']['ap'] += $Fila['tiempo_venta'];
				
			}
			
			if(0 < count($Id_Proyectos))
			{
				
				$Where = '';
				
				if('' != $Proceso)
				{
					$Where .= ' and proc.proceso = "'.$Proceso.'"';
				}
				
				$Consulta = '
					SELECT ped.id_proyecto, clie.codigo_cliente, proc.proceso, proc.nombre,
					ped.fecha_reale, proc.id_proceso
					from cliente clie, procesos proc, pedido ped
					where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
					and ped.id_proyecto in ('.implode(',', $Id_Proyectos).')
					'.$Where.'
					group by ped.id_proceso
				';
				
				//echo $Consulta."\n";
				$Resultado = $this->db->query($Consulta);
				
				
				$Proyectos['total'] = $Resultado->num_rows();
				foreach($Resultado->result_array() as $Fila)
				{
					$Proyectos['p'][$Fila['id_proyecto']]['idpr'] = $Fila['id_proceso'];
					$Proyectos['p'][$Fila['id_proyecto']]['nomb'] = $Fila['nombre'];
					$Proyectos['p'][$Fila['id_proyecto']]['proc'] = $Fila['proceso'];
					$Proyectos['p'][$Fila['id_proyecto']]['fech'] = $Fila['fecha_reale'];
					$Proyectos['p'][$Fila['id_proyecto']]['codi'] = $Fila['codigo_cliente'];
				}
				
				/*print_r($Proyectos['p']);
				echo count($Proyectos['p']);*/
				
				$Proyectos['t']['pcs'] = floor($Proyectos['t']['cs'] / $Proyectos['total']);
				$Proyectos['t']['prp'] = floor($Proyectos['t']['rp'] / $Proyectos['total']);
				$Proyectos['t']['pap'] = floor($Proyectos['t']['ap'] / $Proyectos['total']);
				
				
			}
		}
		
		return $Proyectos;
	}
	
	
	function estado_proyectos($Id_proceso)
	{
		$Consulta = '
			select proy.activo
			from pedido ped, procesos proc, proyecto_tiempo proy
			where ped.id_proceso = proc.id_proceso
			and proy.id_proyecto = ped.id_proyecto
			and proc.id_proceso = "'.$Id_proceso.'"
			and proy.activo = "s"
		';
		
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			//No se iniciara un proyecto
			return 'no';
		}
		else
		{
			//Se iniciara un proyecto
			return 'si';
		}
		
	}
	
	
	function finalizar_proyecto($Id_Proyecto, $Aprobacion)
	{
		
		//Se debe calcular el tiempo que venta ha tardado en solicitar un nuevo ingreso
		$Consulta = '
			select id_pedido
			from pedido
			where id_proyecto = "'.$Id_Proyecto.'"
			order by id_pedido desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);
		if(0 <  $Resultado->num_rows())
		{
			
			$Id_Pedido = $Resultado->row_array();
			
			
			//Fecha y hora en que el pedido anterior fue finalizado vs fecha y hora actual
			$Consulta = '
				select fecha_fin
				from pedido_usuario
				where id_pedido = "'.$Id_Pedido['id_pedido'].'"
				order by id_peus desc
				limit 0, 1
			';
			$Resultado = $this->db->query($Consulta);
			$Tiempo_Fin = $Resultado->row_array();
			
			
			$Tiempo_Aprob = $this->fechas_m->tiempo_habil(
				$Tiempo_Fin['fecha_fin'],
				date('Y-m-d H:i:s')
			);
			
			
			//El tiempo de aprobacion debe ser actualizado
			//pedido_tie_repro
			$Consulta = '
				update pedido_tie_repro
				set tiempo_venta = "'.$Tiempo_Aprob.'"
				where id_pedido = "'.$Id_Pedido['id_pedido'].'"
			';
			$this->db->query($Consulta);
			
			
			//proyecto_tiempo
			$Consulta = '
				select tiempo_venta
				from proyecto_tiempo
				where id_proyecto = "'.$Id_Proyecto.'"
			';
			
			$Resultado = $this->db->query($Consulta);
			$Tiempo_Venta = $Resultado->row_array();
			
			$Consulta = '
				update proyecto_tiempo
				set tiempo_venta = "'.($Tiempo_Aprob + $Tiempo_Venta['tiempo_venta']).'",
				activo = "n", id_usuario = "'.$this->session->userdata('id_usuario').'",
				aprobacion = "'.$Aprobacion.'", fecha = "'.date('Y-m-d H:i:s').'"
				where id_proyecto = "'.$Id_Proyecto.'" and activo = "s"
			';
			$this->db->query($Consulta);
			
		}
		
	}
	
	
	function cliente_proyecto()
	{
		$SQL = '';
		if(1 != $this->session->userdata('id_grupo'))
		{
			$SQL = 'and cli.id_grupo = "'.$this->session->userdata('id_grupo').'"';
		}
		$Consulta = 'select distinct cli.id_cliente, cli.nombre
					from proyecto_tiempo proy, pedido ped, cliente cli, procesos proc
					where proy.id_proyecto = ped.id_proyecto
					and cli.id_cliente = proc.id_cliente
					and proc.id_proceso = ped.id_proceso '. $SQL;
					
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
	
}

/* Fin del archivo */