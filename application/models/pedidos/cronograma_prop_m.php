<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cronograma_prop_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function dividir(
		$Usuarios,
		$Tiempos,
		$Fecha_Entrega_Solicitada
	)
	{
		
		//Solamente central-g tiene acceso a esta funcion asignadora de fechas
		if('' == $Usuarios)
		{
			return '{"pro":"1","fes":"1","val":"si"}';
		}
		else
		{
			
			//Almacena la informacion relacionada con los usuarios
			$Info_Usuarios = array();
			
			
			/*
			 *El usuario de fotopolimero ha sido excluido del calculo de fechas, porque
			 *los tiempos que le programan no son adecuados para el calculo.
			*/
			
			//Recorrido de los usuarios agregados por el planificador a la ruta del pedido
			foreach($Usuarios as $Index => $Usuario)
			{
				//Captura el tiempo programado, en minutos, para el usuario en turno
				$Info_Usuarios[$Usuario]['asi'] = $Tiempos[$Index];
				//Fecha de fin del ultimo tiempo, por default, por si acaso
				$Info_Usuarios[$Usuario]['fin'] = date('Y-m-d H:i:s');
				
				//Asignacion manual de los tiempos
				//SAP
				if('27' == $Usuario)
				{
					$Info_Usuarios[$Usuario]['asi'] = 10;
				}
				//Despacho
				elseif('17' == $Usuario)
				{
					$Info_Usuarios[$Usuario]['asi'] = 7;
				}
				//Morena y Roberto
				elseif('23' == $Usuario || '50' == $Usuario)
				{
					$Info_Usuarios[$Usuario]['asi'] = 25;
				}
			}
			
			
			//Se desea conocer la fecha de finalizacion del ultimo tiempo utilizado
			//por cada usuario
			$Consulta = '
				select max(fin) as fin, id_usuario
				from pedido_tiempos
				where tiempo > 0 and (id_usuario = "'.implode('" or id_usuario = "', $Usuarios).'")
				group by id_usuario
			';
			
			
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				//Con los resultados obtenidos se captura la fecha de finalizacion
				foreach($Resultado->result_array() as $Fila)
				{
					$Info_Usuarios[$Fila['id_usuario']]['fin'] = $Fila['fin'];
				}
				
			}
			
			
			//Busqueda del tiempo habil para cada departamento y la hora de inicio
			$Consulta = '
				select dpto.id_dpto, tiempo_habil, usu.id_usuario
				from usuario usu, departamentos dpto, departamentos_tiempo_habil hab
				where usu.id_dpto = dpto.id_dpto and dpto.id_dpto = hab.id_dpto
				and usu.id_grupo = 1 and (id_usuario = "'.implode('" or id_usuario = "', $Usuarios).'")
				group by dpto.id_dpto, id_usuario
			';
			$Resultado = $this->db->query($Consulta);
			
			foreach($Resultado->result_array() as $Fila)
			{
				//Tiempo habil en minutos
				$Info_Usuarios[$Fila['id_usuario']]['hab'] = ceil(
					($Fila['tiempo_habil'] * 0.94) * 60
				);
			}
			
			
			//Busqueda de los tiempos programados para cada trababajo por cada usuario
			//y por cada pedido...
			$Consulta = '
				select tiempo_asignado as tiempo, peus.id_usuario, peus.id_pedido
				from pedido ped, pedido_usuario peus
				where ped.id_pedido = peus.id_pedido and estado != "Terminado"
				and (id_usuario = "'.implode('" or id_usuario = "', $Usuarios).'")
			';
			$Resultado = $this->db->query($Consulta);
			
			
			$Tiempos_Usuario = array();
			//Si se obtienen resultados, se procede a tomar cada uno
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					/*if(18 != $Fila['id_usuario'])
					{*/
						$Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']] = $Fila['tiempo'] + 0;
					//}
				}
			}
			
				
			//Busqueda de los tiempos utilizados en los trabajos por operador por pedido
			$Consulta = '
				select tiem.id_usuario, sum(tiempo) as tiempo, tiem.id_pedido
				from pedido_tiempos tiem, pedido_usuario peus
				where peus.id_pedido = tiem.id_pedido and peus.id_usuario = tiem.id_usuario
				and estado != "Terminado"
				and (tiem.id_usuario = "'.implode('" or tiem.id_usuario = "', $Usuarios).'")
				group by tiem.id_usuario, tiem.id_pedido
			';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					//Si este usuario y pedido existen en los tiempos programados
					if(isset($Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']]))
					{
						//Al tiempo programado se le resta el utilizado
						$Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']] -= $Fila['tiempo'];
						//Si el tiempo queda menor a cero
						if(0 > $Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']])
						{
							//Se asigna a cero para
							$Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']] = 0;
						}
					}
					
				}
				
			}
			
			
			//Busqueda de los tiempos utilizados en los trabajos por operador por pedido
			$Consulta = '
				select tiem.id_usuario, inicio, tiem.id_pedido
				from pedido_tiempos tiem, pedido_usuario peus
				where peus.id_pedido = tiem.id_pedido and peus.id_usuario = tiem.id_usuario
				and estado != "Terminado" and fin = "0000-00-00 00:00:00"
				and (tiem.id_usuario = "'.implode('" or tiem.id_usuario = "', $Usuarios).'")
				group by tiem.id_usuario, tiem.id_pedido
			';
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				foreach($Resultado->result_array() as $Fila)
				{
					//Si este usuario y pedido existen en los tiempos programados
					if(isset($Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']]))
					{
						//Se calcula cuanto tiempo ha pasado
						$Segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($Fila['inicio']);
						$Segundos = floor($Segundos / 60);
						
						//Al tiempo programado se le resta el utilizado
						$Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']] -= $Segundos;
						//Si el tiempo queda menor a cero
						if(0 > $Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']])
						{
							//Se asigna a cero para
							$Tiempos_Usuario[$Fila['id_usuario']][$Fila['id_pedido']] = 0;
						}
						$Info_Usuarios[$Fila['id_usuario']]['fin'] = date('Y-m-d H:i:s');
					}
					
				}
				
			}
			
			
			//Se busca la fecha de finalizacion del trabajo
			$Fecha_Anterior = date('Y-m-d H:i:s');
			$Fechas_Usuarios = array();
			foreach($Usuarios as $IInn => $Index)
			{
				
				$Tiempo_Asignado = 0;
				if(isset($Tiempos_Usuario[$Index]))
				{
					$Tiempo_Asignado = array_sum($Tiempos_Usuario[$Index]);
				}
				
				if(!isset($Info_Usuarios[$Index]['hab']))
				{
					$Info_Usuarios[$Index]['hab'] = 22;
				}
				
				
				$Info_Usuarios[$Index]['fin'] = $this->fecha_fin(
					$Info_Usuarios[$Index]['fin'],
					$Tiempo_Asignado,
					$Info_Usuarios[$Index]['hab']
				);
				
				
				
				
				//Si la fecha de inicio es menor a la fecha y hora actual, se toma la actual
				if(
					strtotime($Info_Usuarios[$Index]['fin'])
					< strtotime(date('Y-m-d H:i:s'))
				)
				{
					$Info_Usuarios[$Index]['fin'] =  date('Y-m-d H:i:s');
				}
				
				//Si la fecha anterior es mayor a la fecha de inicio
				if(
					strtotime($Info_Usuarios[$Index]['fin'])
					< strtotime($Fecha_Anterior)
				)
				{
					$Info_Usuarios[$Index]['fin'] = $Fecha_Anterior;
				}
				
				
				
				//Se busca la fecha de inicio para este usuario al realizar el cronograma
				$Info_Usuarios[$Index]['fin'] = $this->fecha_fin(
					$Info_Usuarios[$Index]['fin'],
					$Info_Usuarios[$Index]['asi'],// + $Tiempo_Asignado,
					$Info_Usuarios[$Index]['hab']
				);
				
				
				$Fecha_Anterior = $Info_Usuarios[$Index]['fin'];
				$Fechas_Usuarios[] = '"'.$Info_Usuarios[$Index]['fin'].'"';
				
				
			}
			
			
			$FF = explode(' ', $Fecha_Anterior);
			$Se_Puede = 'si';
			if(strtotime($FF[0].' 00:00:00') > strtotime($Fecha_Entrega_Solicitada.' 00:00:01'))
			{
				$Se_Puede = 'no';
			}
			
			return '{"pro":"'.$Fecha_Entrega_Solicitada.'","fes":['.implode(',', $Fechas_Usuarios).'],"val":"'.$Se_Puede.'"}';
			
		}
		
		
	}
	
	
	function fecha_fin($Fecha, $Hora_Programada, $Tiempo_Habil)
	{
		//Calculo de la fecha de inicio/fin (segun se vea)
		
		//Hasta que hora se le puede programar a este puesto?
		//Tomando como base la fecha de inicio en curso
		$FF = explode(' ', $Fecha);
		$Limite_Programa = date(
			'Y-m-d H:i:s',
			strtotime('+'.$Tiempo_Habil.' minutes', strtotime($FF[0].' 08:00:00'))
		);
		if(strtotime($Fecha) > strtotime($Limite_Programa))
		{
			$Fecha = date(
				'Y-m-d H:i:s',
				strtotime('+1 days', strtotime($FF[0].' 08:00:00'))
			);
		}
		
		
	
		$Salir = false;
		do{
			
			/*
			 *Calculo de los Dias Feriados.
			*/
			
			
			//Se divide la fecha de la hora para futuros tratamientos
			$FF = explode(' ', $Fecha);
			
			
			//Si la fecha de inicio es mayor al medio dia del sabado
			if(
				(date('H', strtotime($Fecha))+0) >= 12
				&& 6 == date('w', strtotime($Fecha))
			)
			{
				//Se pasa el trabajo para el lunes a primera hora
				$Fecha = date(
					'Y-m-d H:i:s',
					strtotime('+2 days', strtotime($FF[0].' 08:00:00'))
				);
			}
			
			
			//Una vez mas
			$FF = explode(' ', $Fecha);
			
			
			//Si el trabajo inicia antes de las ocho am
			if((date('H', strtotime($Fecha)) + 0) < 8)
			{
				//Se pasa para las ocho de la manhana
				$Fecha = $FF[0].' 08:00:00';
			}
			
			//Si el trabajo inicia a las doce...
			if((date('H', strtotime($Fecha))+0) == 12)
			{
				//Se pasa a la una de la tarde
				$Fecha = $FF[0].' 13:00:00';
			}
			
			
			//Hasta que hora se le puede programar a este puesto?
			//Tomando como base la fecha de inicio en curso
			$FF = explode(' ', $Fecha);
			$Limite_Programa = date(
				'Y-m-d H:i:s',
				strtotime('+'.$Tiempo_Habil.' minutes', strtotime($FF[0].' 08:00:00'))
			);
			
			
			//A que hora terminara este trabajo?
			$Hora_Fin = date(
				'Y-m-d H:i:s',
				strtotime('+'.$Hora_Programada.' minutes', strtotime($Fecha))
			);
			
			
			//Validacion para evitar trabajos a las doce del medio dia o despues
			//de la hora limite
			if(
				((date('H', strtotime($Fecha))+0) >= 8
				&& (date('H', strtotime($Fecha))+0) < 12)
				&& (strtotime($FF[0].' 12:00:00') < strtotime($Hora_Fin))
			)
			{
				//Si el trabajo inicia antes de las doce y termina despues de las doce
				//debe segmentarse para continuar a la una de la tarde
				//Se calcula cuantos segundos del trabajo quedaran para el siguiente dia
				$Segmento_Sobrante = strtotime($Hora_Fin) - strtotime($FF[0].' 12:00:00');
				//Se pasa a minutos
				$Segmento_Sobrante = floor($Segmento_Sobrante / 60);
				//El total de minutos de este trabajo para este dia
				$Segmento_Hoy = $Hora_Programada - $Segmento_Sobrante;
				
				//Se resta del tiempo asignado
				$Hora_Programada -= $Segmento_Hoy;
								
				//Se avanza la fecha de inicio a la una de la tarde
				$Fecha = $FF[0].' 13:00:00';
				
			}
			elseif(strtotime($Hora_Fin) > strtotime($Limite_Programa))
			{
				//Si la fecha de fin es mayor al limite
				
				//Se calcula cuantos segundos del trabajo quedaran para el siguiente dia
				$Segmento_Sobrante = strtotime($Hora_Fin) - strtotime($Limite_Programa);
				//Se pasa a minutos
				$Segmento_Sobrante = floor($Segmento_Sobrante / 60);
				//El total de minutos de este trabajo para este dia
				$Segmento_Hoy = $Hora_Programada - $Segmento_Sobrante;
				
				//Se resta del tiempo asignado
				$Hora_Programada -= $Segmento_Hoy;
				
				
				//Se avanza la fecha de inicio un dia
				$Fecha = date(
					'Y-m-d H:i:s',
					strtotime('+1 days', strtotime($FF[0].' 08:00:00'))
				);
				
			}
			else
			{
				//La fecha de finalizacion del trabajo esta en el limite del tiempo disponible
				//Se avanza la fecha de inicio con los minutos que dura el trabajo
				$Fecha = $Hora_Fin;
				
				//Se debe salir del bucle
				$Salir = true;
			}
			
			
		}while(!$Salir);
		
		
		return $Fecha;
		
	}
	
}

/* Fin del archivo */