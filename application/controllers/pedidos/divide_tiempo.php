<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Divide_tiempo extends CI_Controller {
	
	public function index()
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		/*
		 *Elementos necesarios:
		 *Lista de departamentos.
		 *Tiempos habiles para cada departamento (85%) en minutos.
		 *Lista de usuarios (Se conocera solamente el id_usuario segun los trabajos).
		 *Lista de trabajos.
		 *Fecha de inicio.
		 *Para fotopolimeros debo conocer la cotizacion realizada para tener las pulgas solicitadas.
		 *
		 *
		 *Tiempos puestos a mano:
		 *Calidad 25 minutos.
		 *SAP 7 minutos.
		 *Despacho 10 minutos.
		*/
		
		
		//Almacenamiento de cada departamento
		$Departamentos = array();
		$Ultimo_Trabajo = array();
		$Usuarios = array();
		$Id_Pedido_Anterior = 0;
		$Puesto_Aterior = date('Y-m-d H:i:s');
		
		
		
		//Lista de departamentos, tiempos habiles, horas de inicio.
		$Consulta = '
			select dpto.id_dpto, departamento, tiempo_habil, hora_inicio
			from usuario usu, departamentos dpto, departamentos_tiempo_habil hab
			where usu.id_dpto = dpto.id_dpto and dpto.id_dpto = hab.id_dpto
			and usu.id_grupo = 1
			group by dpto.id_dpto
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		//Acondicionamiento de los datos recibidos
		foreach($Resultado->result_array() as $Fila)
		{
			$Departamentos[$Fila['id_dpto']]['dpt'] = $Fila['departamento'];
			//Tiempo en minutos
			$Departamentos[$Fila['id_dpto']]['habm'] = ceil(
				($Fila['tiempo_habil'] * 0.94) * 60
			);
			$Departamentos[$Fila['id_dpto']]['ini'] = $Fila['hora_inicio'];
		}
		
		
		
		$Planchas_F = array(18 => '', 26 => '', 29 => '', 30 => '', 38 => '', 57 => '', 58 => '');
		$Tiempos_Foto = array();
		
		$Consulta = '
			select peus.id_pedido, pped.cantidad, id_producto
			from pedido_usuario peus, producto_pedido pped, producto_cliente pcli
			where peus.id_pedido = pped.id_pedido and pped.id_prod_clie = pcli.id_prod_clie
			and peus.estado != "Terminado"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		foreach($Resultado->result_array() as $Fila)
		{
			if(!isset($Tiempos_Foto[$Fila['id_pedido']]))
			{
				$Tiempos_Foto[$Fila['id_pedido']] = 0;
			}
			
			if(isset($Planchas_F[$Fila['id_producto']]))
			{
				$Tiempos_Foto[$Fila['id_pedido']] += $Fila['cantidad'];
			}
		}
		
		
		
		$Consulta = '
			select peus.id_pedido, peus.id_usuario, sum(tiempo) as tiempo
			from pedido_usuario peus, pedido_tiempos tiem
			where peus.id_pedido = tiem.id_pedido and peus.id_usuario = tiem.id_usuario
			and peus.estado != "Terminado"
			group by id_pedido, id_usuario
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Tiempos_Utilizados = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Tiempos_Utilizados[$Fila['id_pedido']][$Fila['id_usuario']] = $Fila['tiempo'];
		}
		
		
		
		
		
		//Listado de los trabajos sin finalizar (pedido_usuario).
		$Consulta = '
			select ped.id_pedido, ped.fecha_entrega, peus.tiempo_asignado,
			peus.id_usuario, usu.id_dpto, dpto.codigo, usu.usuario, peus.id_peus
			from cliente clie, procesos proc, pedido ped, pedido_usuario peus,
			usuario usu, departamentos dpto
			where clie.id_cliente = proc.id_cliente and proc.id_proceso = ped.id_proceso
			and peus.id_pedido = ped.id_pedido and peus.id_usuario = usu.id_usuario
			and usu.id_dpto = dpto.id_dpto and peus.estado != "Terminado"
			and dpto.codigo != "Ventas" and dpto.codigo != "Plani"
			and clie.id_grupo = "'.$this->session->userdata('id_grupo').'"
			order by ped.fecha_entrega asc, ped.id_pedido asc, peus.id_peus asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		
		//Recorrido de los trabajos por puesto para crear el cronograma inicial
		foreach($Resultado->result_array() as $Fila)
		{
			
			//Asignacion manual de los tiempos
			if('SAP' == $Fila['codigo'])
			{
				$Fila['tiempo_asignado'] = 7;
			}
			if('Despacho' == $Fila['codigo'])
			{
				$Fila['tiempo_asignado'] = 10;
			}
			if('3602' == $Fila['codigo'])
			{
				$Fila['tiempo_asignado'] = 25;
			}
			
			
			
			//Asignacion de la fecha de inicio para el cronograma por usuarios
			/*****************************************************************************
			FALTA VER HUECOS EN LOS CRONOGRAMAS DE LOS USUARIOS PARA PONER EL MEJOR TIEMPO
			*****************************************************************************/
			
			
			//Asignacion de un usuario al array
			if(!isset($Ultimo_Trabajo[$Fila['id_usuario']]))
			{
				$Ultimo_Trabajo[$Fila['id_usuario']] = date('Y-m-d H:i:s');
			}
			
			
			
			if(!isset($Usuarios[$Fila['id_usuario']]))
			{
				$Usuarios[$Fila['id_usuario']] = array();
			}
			
			
			//Si este id_pedido es diferente al anterior de reasignan algunas variables
			/*if($Id_Pedido_Anterior != $Fila['id_pedido'])
			{
				$Id_Pedido_Anterior = $Fila['id_pedido'];
				//Se reinicia el contador para puesto anterior
				$Puesto_Aterior = date('Y-m-d H:i:s');
			}*/
			
			
			//A la fecha actual se le suman 5 minutos para tener un espacio entre la
			//asignacion de un trabajo y el recibimiento del mismo
			$Fecha_Actual = date('Y-m-d H:i:s', strtotime('+5 minutes', strtotime(date('Y-m-d H:i:s'))));
			
			
			
			
			if(
				strtotime($Fecha_Actual) > strtotime($Ultimo_Trabajo[$Fila['id_usuario']])
			)
			{
				//La fecha actual es la mayor
				$Fecha_Inicio = $Fecha_Actual;
			}
			else
			{
				//El ultimo trabajo de este usuario es mayor
				$Fecha_Inicio = $Ultimo_Trabajo[$Fila['id_usuario']];
			}
			
			
			//Division de la fecha y hora, muy util
			$Fecha_Fecha = explode(' ', $Fecha_Inicio);
			
			
			//La fecha de inicio no debe sobrepasar el limite asignado al departamento
			if(
				strtotime($Fecha_Inicio)
				>= strtotime(
					'+'.$Departamentos[$Fila['id_dpto']]['habm'].' minutes',
					strtotime(
						$Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00'
					)
				)
			)
			{
				//Se toma la fecha del siguiente dia a primera hora
				$Fecha_Inicio = date(
					'Y-m-d H:i:s',
					strtotime(
						'+1 days',
						strtotime(
							$Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00'
						)
					)
				);
				
				
				//Division de la fecha y hora, muy util
				$Fecha_Fecha = explode(' ', $Fecha_Inicio);
				
			}
			
			
			
			//Si la fecha de inicio es menor a la hora de inicio de este puesto
			if(
				strtotime($Fecha_Inicio)
				< strtotime(
					$Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00'
				)
			)
			{
				//Se asigna a la hora de inicio para el dia en curso
				$Fecha_Inicio = date(
					'Y-m-d H:i:s',
					strtotime($Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00')
				);
				
				//Division de la fecha y hora, muy util (Otra vez)
				$Fecha_Fecha = explode(' ', $Fecha_Inicio);
			}
			
			
			
			
			
			
			
			
			//Bandera que indicara al bucle su finalizacion
			$Salir = false;
			//Tiempo programado para el trabajo en curso
			$Tiempo_Programar = $Fila['tiempo_asignado'];
			if($Fila['id_dpto'] == 9 && isset($Tiempos_Foto[$Fila['id_pedido']]))
			{
				$Tiempo_Programar = ceil($Tiempos_Foto[$Fila['id_pedido']] / 29.76);
			}
			if(isset($Tiempos_Utilizados[$Fila['id_pedido']][$Fila['id_usuario']]))
			{
				$Tiempo_Programar -= $Tiempos_Utilizados[$Fila['id_pedido']][$Fila['id_usuario']];
				if(0 > $Tiempo_Programar)
				{
					$Tiempo_Programar = 0;
				}
			}
			//Almacenamiento de los tiempos y segmentos para el usuario en curso y su trabajo
			$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_inicio'] = $Fecha_Inicio;
			$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos'] = '{"seg":[';
			//{segmentos: [{inicio,tiempo},{inicio,tiempo},{inicio,tiempo},{inicio,tiempo}]}
			//Almacenamiento temporal para los segmentos resultantes
			$Segmentos = array();
			
			
			//Creacion de los segmentos de tiempo habil para el trabajo asignado a este usuario
			do{
				
				//Una vez mas
				$Fecha_Fecha = explode(' ', $Fecha_Inicio);
				
				//Si la fecha de inicio es mayor al medio dia del sabado
				if(
					(date('H', strtotime($Fecha_Inicio))+0) >= 12
					&& 6 == date('w', strtotime($Fecha_Inicio))
				)
				{
					//Se pasa el trabajo para el lunes a primera hora
					$Fecha_Inicio = date(
						'Y-m-d H:i:s',
						strtotime(
							'+2 days',
							strtotime(
								$Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00'
							)
						)
					);
				}
				
				
				//Si el trabajo inicia a las doce...
				if((date('H', strtotime($Fecha_Inicio))+0) == 12)
				{
					//Se pasa a la una de la tarde
					$Fecha_Inicio = $Fecha_Fecha[0].' 13:00:00';
				}
				
				
				
				//Hasta que hora se le puede programar a este puesto?
				//Tomando como base la fecha de inicio en curso
				$Fecha_Fecha = explode(' ', $Fecha_Inicio);
				$Limite_Programa = date(
					'Y-m-d H:i:s',
					strtotime(
						'+'.$Departamentos[$Fila['id_dpto']]['habm'].' minutes',
						strtotime(
							$Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00'
						)
					)
				);
				
				
				
				//A que hora terminara este trabajo?
				$Hora_Fin = date('Y-m-d H:i:s',
					strtotime(
						'+'.$Tiempo_Programar.' minutes',
						strtotime($Fecha_Inicio)
					)
				);
				
				
				
				//Creacion de los segmentos de tiempo habil para el pedido y usuario
				$Segmentos[$Fila['id_pedido']][$Fecha_Fecha[0].$Fecha_Fecha[1]] = '{"inicio":"'.$Fecha_Inicio.'",';
				
				
				//Validacion para evitar trabajos a las doce del medio dia o despues
				//de la hora limite
				if(
					((date('H', strtotime($Fecha_Inicio))+0) >= 8
					 && (date('H', strtotime($Fecha_Inicio))+0) < 12)
					&& (strtotime($Fecha_Fecha[0].' 12:00:00') < strtotime($Hora_Fin))
				)
				{
					//Si el trabajo inicia antes de las doce y termina despues de las doce
					//debe segmentarse para continuar a la una de la tarde
					//Se calcula cuantos segundos del trabajo quedaran para el siguiente dia
					$Segmento_Sobrante = strtotime($Hora_Fin) - strtotime($Fecha_Fecha[0].' 12:00:00');
					//Se pasa a minutos
					$Segmento_Sobrante = floor($Segmento_Sobrante / 60);
					//El total de minutos de este trabajo para este dia
					$Segmento_Hoy = $Tiempo_Programar - $Segmento_Sobrante;
					
					//Se resta del tiempo asignado
					$Tiempo_Programar -= $Segmento_Hoy;
					$Segmentos[$Fila['id_pedido']][$Fecha_Fecha[0].$Fecha_Fecha[1]] .= '"fin":"'.date('Y-m-d H:i:s', strtotime('+'.$Segmento_Hoy.' minutes', strtotime($Fecha_Inicio))).'",';
					//Se avanza la fecha de inicio un dia
					$Fecha_Inicio = $Fecha_Fecha[0].' 13:00:00';
					
				}
				elseif(strtotime($Hora_Fin) > strtotime($Limite_Programa))
				{
					//Se calcula cuantos segundos del trabajo quedaran para el siguiente dia
					$Segmento_Sobrante = strtotime($Hora_Fin) - strtotime($Limite_Programa);
					//Se pasa a minutos
					$Segmento_Sobrante = floor($Segmento_Sobrante / 60);
					//El total de minutos de este trabajo para este dia
					$Segmento_Hoy = $Tiempo_Programar - $Segmento_Sobrante;
					
					//Se resta del tiempo asignado
					$Tiempo_Programar -= $Segmento_Hoy;
					$Segmentos[$Fila['id_pedido']][$Fecha_Fecha[0].$Fecha_Fecha[1]] .= '"fin":"'.$Limite_Programa.'",';
					//Se avanza la fecha de inicio un dia
					$Fecha_Inicio = date('Y-m-d H:i:s', strtotime('+1 days', strtotime($Fecha_Fecha[0].' '.$Departamentos[$Fila['id_dpto']]['ini'].':00:00')));

				}
				else
				{//La fecha de finalizacion del trabajo esta en el limite del tiempo disponible
					//Se avanza la fecha de inicio con los minutos que dura el trabajo
					$Fecha_Inicio = $Hora_Fin;
					$Segmento_Hoy = $Tiempo_Programar;
					$Segmentos[$Fila['id_pedido']][$Fecha_Fecha[0].$Fecha_Fecha[1]] .= '"fin":"'.$Hora_Fin.'",';
					//Se debe salir del bucle
					$Salir = true;
				}
				
				
				
				$Segmentos[$Fila['id_pedido']][$Fecha_Fecha[0].$Fecha_Fecha[1]] .= '"tiempo":"'.$Segmento_Hoy.'"}';
				
				
			}while(!$Salir);
			
			
			
			
			
			
			$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos'] .= implode(',', $Segmentos[$Fila['id_pedido']]).']}';
			$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos'] = str_replace('"', "'", $Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos']);
			$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_fin'] = $Hora_Fin;
			
			$Ultimo_Trabajo[$Fila['id_usuario']] = $Hora_Fin;
			//$Puesto_Aterior = $Hora_Fin;
			
			
			
			echo $Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_inicio'].'<br />';
			echo $Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos'].'<br />';
			echo $Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_fin'].'<br />';
			echo $Fila['id_pedido'].' - '.$Fila['usuario'].' - '.$Fila['tiempo_asignado'].'<br /><br />';
			
			
			$Tiempo_Pedido_Usuario = '
				insert into pedido_cronograma values(
					NULL,
					"'.$Fila['id_pedido'].'",
					"'.$Fila['id_usuario'].'",
					"'.$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_inicio'].'",
					"'.$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['fecha_fin'].'",
					"'.$Usuarios[$Fila['id_usuario']][$Fila['id_pedido']]['segmentos'].'"
				)
			';
			
			
			
			$this->db->query($Tiempo_Pedido_Usuario);
			
			/*
			 *SELECT DISTINCT cro.id_usuario, usu.usuario, MAX( cro.fecha_fin )
			 *FROM pedido_cronograma cro, usuario usu
			 *WHERE usu.id_usuario = cro.id_usuario
			 *GROUP BY id_usuario
			*/
			
		}
		
		
	}
	
	
}

/* Fin del archivo */