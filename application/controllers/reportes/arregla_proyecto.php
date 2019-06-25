<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arregla_proyecto extends CI_Controller {
	
	
	public function index()
	{
		
		$Microtimee = microtime();
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Consulta = '
			update proyecto_tiempo
			set tiempo_repro = 0, tiempo_cs = 0, tiempo_venta = 0
		';
		$this->db->query($Consulta);
		
		
		$Consulta = '
			select proc.id_proceso, ped.id_pedido, id_proyecto,
			min(fecha_asignado) as inicio, max(fecha_fin) as fin, fecha_reale
			from procesos proc, pedido ped, pedido_usuario peus
			where proc.id_proceso = ped.id_proceso and ped.id_pedido = peus.id_pedido
			and ped.id_pedido > 72621 and id_cliente = 116
			group by ped.id_pedido
			order by proc.id_proceso asc, ped.id_pedido asc
		';
		$Resultado = $this->db->query($Consulta);
		
		$Proceso_Anterior = 0;
		$Pedido_Anterior = 0;
		$Proyecto_Anterior = 0;
		$Finaliza = '';
		$Aprobacion = 0;
		$Fecha_Anterior = '';
		foreach($Resultado->result_array() as $Fila)
		{
			if($Proceso_Anterior != $Fila['id_proceso'])
			{
				echo '<br />';
			}
			
			echo $Fila['id_proceso'].' *-* '.$Fila['id_pedido'].' *-* '.$Fila['id_proyecto'];
			echo ' *-* '.$Fila['inicio'].' *-* '.$Fila['fin'].' *-* '.$Fila['fecha_reale'].'<br />';
			
			
			//Si proyecto anterior es igual a este se acumula en variable la aprobacion
			//$Proyecto_Anterior == $Fila['id_proyecto'] && 
			if(0 != $Proyecto_Anterior && $Proceso_Anterior == $Fila['id_proceso'])
			{
				if('' != $Fecha_Anterior)
				{
					$Aprobacion_Temp = $this->fechas_m->tiempo_habil(
						$Fecha_Anterior,
						$Fila['inicio']
					);
					
					$Consulta = '
						update pedido_tie_repro
						set tiempo_venta = "'.$Aprobacion_Temp.'"
						where id_pedido = "'.$Pedido_Anterior.'"
					';
					$this->db->query($Consulta);
					
					$Aprobacion += $Aprobacion_Temp;
					echo $Aprobacion.' -- '.$Proyecto_Anterior.'<br />';
				}
				
				if($Proyecto_Anterior != $Fila['id_proyecto'])
				{
					if(0 != $Proyecto_Anterior && 0 < $Aprobacion)
					{
						echo 'Actualiza: '.$Aprobacion.' -- '.$Proyecto_Anterior.'<br />';
						$Consulta = '
							update proyecto_tiempo
							set tiempo_venta = "'.$Aprobacion.'"
							where id_proyecto = "'.$Proyecto_Anterior.'"
						';
						$this->db->query($Consulta);
					}
					echo 'Limpia<br />';
					$Aprobacion = 0;
				}
			}
			else
			{//Si proyecto anterior es diferente a este se actualiza la Base con la variable
				if(0 != $Proyecto_Anterior && 0 < $Aprobacion)
				{
					echo 'Actualiza: '.$Aprobacion.' -- '.$Proyecto_Anterior.'<br />';
					$Consulta = '
						update proyecto_tiempo
						set tiempo_venta = "'.$Aprobacion.'"
						where id_proyecto = "'.$Proyecto_Anterior.'"
					';
					$this->db->query($Consulta);
				}
				echo 'Limpia<br />';
				$Aprobacion = 0;
			}
			
			
			
			
			
			$Proyecto_Anterior = $Fila['id_proyecto'];
			$Fecha_Anterior = $Fila['fin'];
			$Proceso_Anterior = $Fila['id_proceso'];
			$Pedido_Anterior = $Fila['id_pedido'];
			
		}
		
		
		//exit();
		
		
		
		
		$Consulta = '
			select ped.id_proyecto, peus.id_pedido, min(fecha_asignado) as inicio,
			max(fecha_fin) as fin
			from proyecto_tiempo proy, pedido ped, pedido_usuario peus
			where proy.id_proyecto = ped.id_proyecto and ped.id_pedido = peus.id_pedido
			and ped.id_pedido != 0 and fecha_reale != "0000-00-00"
			group by peus.id_pedido
		';
		
		$Tiempos_Inicio = $this->db->query($Consulta);
		
		foreach($Tiempos_Inicio->result_array() as $Fila)
		{
			
			$Tiempo_Producc = $this->fechas_m->tiempo_habil(
				$Fila['inicio'],
				$Fila['fin']
			);
			
			
			//Tiempo de planificacion, ventas e ing. de producto
			$Consulta = '
				select inicio, fin
				from pedido_tiempos tiem, usuario usu
				where tiem.id_usuario = usu.id_usuario and id_pedido = "'.$Fila['id_pedido'].'"
				and (id_dpto = 1 or id_dpto = 23 or id_dpto = 46)
			';
			$Planis_Inicios = $this->db->query($Consulta);
			$Tiempo_Plani = 0;
			
			if(0 < $Planis_Inicios->num_rows())
			{
				foreach($Planis_Inicios->result_array() as $Planis)
				{
					$Tiempo_Plani += $this->fechas_m->tiempo_habil(
						$Planis['inicio'],
						$Planis['fin']
					);
				}
			}
			
			echo '<br />'.$Fila['id_pedido'].'<br />';
			echo $Tiempo_Producc.' Total<br />';
			echo $Tiempo_Plani.' Plani<br />';
			$Tiempo_Producc = $Tiempo_Producc - $Tiempo_Plani;
			echo $Tiempo_Producc.' Repro<br />';
			
			
			
			$Consulta = '
				update pedido_tie_repro
				set tiempo_repro = "'.$Tiempo_Producc.'",
				tiempo_cs = "'.$Tiempo_Plani.'"
				where id_pedido = "'.$Fila['id_pedido'].'"
			';
			$this->db->query($Consulta);
			
			
			
			$Consulta = '
				select tiempo_repro, tiempo_cs
				from proyecto_tiempo
				where id_proyecto = "'.$Fila['id_proyecto'].'"
			';
			$Resultado = $this->db->query($Consulta);
			
			$TActual = $Resultado->row_array();
			
			
			$Consulta = '
				update proyecto_tiempo
				set tiempo_repro = "'.($Tiempo_Producc+$TActual['tiempo_repro']).'",
				tiempo_cs = "'.($Tiempo_Plani+$TActual['tiempo_cs']).'"
				where id_proyecto = "'.$Fila['id_proyecto'].'"
			';
			$this->db->query($Consulta);
			
			
		}
		
		
		
		
		echo $Microtimee.'<br />'.microtime();
		
		
	}
	
}
