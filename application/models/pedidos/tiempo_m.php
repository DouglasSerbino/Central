<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempo_m extends CI_Model {
	
	
	function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 *Busca el tiempo que ha utilizado el usuario en el pedido.
	 *@param string $Id_Pedido.
	 *@param string $Id_Usuario.
	 *@return string $Tiempo.
	*/
	function usuario($Id_Pedido, $Id_Usuario)
	{
		
		//Tiempo utilizado
		$Tiempo = 0;
		
		//Sumatoria de los tiempos que este usuario a utilizado, para el pedido senhalado
		$Consulta = '
			select sum(tiempo) as tiempo
			from pedido_tiempos
			where id_pedido = "'.$Id_Pedido.'" and id_usuario = "'.$Id_Usuario.'"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//Si hay resultado
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->result_array();
			//Es probable que este usuario no tenga tiempo para este pedido y por ello
			//reciba un null como respuesta, entonces sumo el resultado mas cero
			//si es un numero no afecta y si es null lo pone como cero.
			$Fila[0]['tiempo'] += 0;
			//Se toma el tiempo resultante
			$Tiempo = $Fila[0]['tiempo'];
		}
		
		//Hay tiempos corriendo para este usuario?
		$Consulta = '
			select inicio
			from pedido_tiempos
			where id_pedido = "'.$Id_Pedido.'" and id_usuario = "'.$Id_Usuario.'"
			and fin = "0000-00-00 00:00:00"
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//Si hay un tiempo corriendo
		if(0 < $Resultado->num_rows())
		{
			$Fila = $Resultado->result_array();
			
			//Debo saber cuantos minutos tardo en realizarse este trabajo
			$Minutos = $this->fechas_m->minutos_de_trabajo(
				$Fila[0]['inicio'],
				date('Y-m-d H:i:s')
			);
			
			$Tiempo += $Minutos;
		}
		
		
		return $Tiempo;
		
	}
	
	
	/**
	 *Rechazar trabajo.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@param string $Rech_Razones.
	 *@return nada.
	*/
	function rechazar($Id_Pedido, $Id_Peus, $Rech_Razones)
	{
		
		//Limpieza
		//Quiza el usuario que recibe el rechazo no pertenecia a la ruta de trabajo
		$Rech_peus = $this->seguridad_m->mysql_seguro($this->input->post('rech_peus'));
		$Tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		if('on' == $this->input->post('chk_todos'))
		{
			//Tomo el id del usuario en cuestion
			$rech_a = $this->seguridad_m->mysql_seguro(
				$this->input->post('rech_a_todos')
			);
			
			$rech_asignar = $this->seguridad_m->mysql_seguro(
				$this->input->post('rech_a_todos')
			);
		}
		else
		{
			//Obtengo el "id_peus-id_usuario" del puesto al que debe regresar
			$rech_asignar = $this->input->post('rech_a');
			//Puesto que recibe el rechazo (este puesto esta en la ruta programada)
			$rech_a = $this->seguridad_m->mysql_seguro(
				$this->input->post('rech_a')
			);
		}
		$rech_asignar = explode('-', $rech_asignar);
			
		if(3 != count($rech_asignar))
		{
			show_404();
			exit();
		}
		//****************
		//INGRESO DE LAS RAZONES POR LAS CUALES SE RECHAZA
		//Fecha y hora actual... se toma al inicio para evitar llamar a la funcion en cada
		//interaccion, asi todos los ingresos poseen la misma informacion
		$Fecha_Hora = date('Y-m-d H:i:s');
		//Variable que tomara los valores a ingresar en la base de datos
		$Rechazos = array();
		//Variable que tomara los valores a ingresar en la base de datos cuando el
		//rechazo aplique a un usuario de otro grupo
		//$Rechazos_Grupo = array();
		//Observacion a guardar.
		$Observaciones = array();
		
		
		//Entonces se toma el valor de textarea y se limpia
		$rech_comentario = $this->seguridad_m->mysql_seguro(
			$this->input->post('rech_comentario')
		);
		$Observaciones[] = $rech_comentario;
		
		
		$Rechazo_Asignar = explode('-', $rech_a);
		if(1 < count($Rechazo_Asignar))
		{
			$Rechazo_Asignar = $Rechazo_Asignar[2];
		}
		else
		{
			$Rechazo_Asignar = $rech_a;
		}
		
		//Recorrido por las razones de rechazo
		foreach($Rech_Razones as $Razon)
		{
			//Si seleccionaron esta razon
			if('on' == $this->input->post('rz_'.$Razon['id_rechazo_razones']))
			{
				$Observaciones[] = $Razon['rechazo_razon'];
				
				//Se crea la consulta que le corresponde
				$Rechazos[] = '(
					NULL,
					"'.$Id_Pedido.'",
					"'.$Rechazo_Asignar.'",
					"'.$Razon['id_rechazo_razones'].'",
					"'.$Fecha_Hora.'",
					"'.$rech_comentario.'",
					"no"
				)';
				/*
				$Rechazos_Grupo[] = '(
					NULL,
					"[$Id_Pedido]",
					"[$rech_a]",
					"'.$Razon['id_rechazo_razones'].'",
					"'.$Fecha_Hora.'",
					"'.$rech_comentario.'",
					"no"
				)';
				*/
			}
		}
		
		
		
		//Sera posible que el formulario haya llegado sin razones para ser rechazado?
		//Pongo una validacion mas, nunca sobran.
		if(0 < count($Rechazos))
		{
			
			//Ingreso de las razones del rechazo para este pedido y usuario
			$Consulta = '
				insert into pedido_rechazo values '.implode(', ', $Rechazos).'
			';
			
			if('rechazo' == $Tipo)
			{
				$this->db->query($Consulta);
			}
			
			
			
			//****************
			//DETENER EL TIEMPO EN EJECUCION Y PONER CUANTO HA UTILIZADO
			$this->detener_tiempo($Id_Pedido);
			
			
			//****************
			//REASIGNAR LOS ESTADOS EN PEDIDO_USUARIO
			
			//Asignar al puesto que se rechaza, siempre y cuando no sea el mismo del usuario
			//que rechaza
			
			
			if($Id_Peus != $rech_asignar[0])
			{
				
				//Asignacion al nuevo puesto
				$Consulta = '
					update pedido_usuario
					set estado = "Asignado"
					where id_peus = "'.$rech_asignar[0].'"
				';
				//echo $Consulta.'<br />';
				$this->db->query($Consulta);
				
				//Necesito saber si es vendedor o planificador, para que tenga el tiempo corriendo
				$Consulta = '
					select codigo
					from usuario usu, departamentos dpto
					where usu.id_dpto = dpto.id_dpto
					and id_usuario = "'.$rech_asignar[2].'"
				';
				//echo $Consulta.'<br />';
				$Codigo = $this->db->query($Consulta);
				$Codigo = $Codigo->result_array();
				
				if(
					'Plani' == $Codigo[0]['codigo']
					|| 'Ventas' == $Codigo[0]['codigo']
				)
				{
					$this->crear_tiempo($Id_Pedido, $rech_asignar[2]);
				}
				
				
				//Los puestos siguientes deben ser "Agregado"
				$Consulta = '
					update pedido_usuario
					set estado = "Agregado"
					where id_peus > "'.$rech_asignar[0].'" and id_pedido = "'.$Id_Pedido.'"
				';
				//echo $Consulta.'<br />';
				$this->db->query($Consulta);
				
			}
			
			$Frase = 'Se rechaza el siguiente trabajo a '.$rech_asignar[1].' por problemas en: '.implode(', ', $Observaciones).'.';
			if('rechazo' != $Tipo)
			{
				$Frase = 'Se regresa el siguiente trabajo a '.$rech_asignar[1].' por: '.implode(', ', $Observaciones).'.';
			}
			
			//Ingreso una observacion con el comentario
			$Consulta = '
				INSERT INTO observacion VALUES(
					NULL,
					"'.$Id_Pedido.'",
					"'.$this->session->userdata('id_usuario').'",
					"'.date('Y-m-d H:i:s').'",
					"'.$Frase.'",
					"n"
				)
			';
			//echo $Consulta.'<br />';
			$this->db->query($Consulta);
			
			
			

			/*
			// ** ATENCION ** //
			//Es posible que este rechazo haya sido aplicado al puesto de Ventas.
			//El cuidado viene cuando este vendedor debe hacer referencia al planificador
			//de un Grupo externo que ha puesto a este Grupo dentro de su ruta.
			
			//Como se vigila esto? Primero se debe averiguar si este pedido pertenece
			//a pedido_pedido, y si pertenece tomo el id del pedido superior para aplicar
			//rechazo y tiempo abierto.
			//Luego ver si el pedido se rechazara al puesto de venta en el pedido hijo.
			
			//A quien se le asignara?
			$Consulta = '
				select usuario from usuario where id_usuario = "'.$rech_asignar[2].'"
			';
			//echo $Consulta.'<br />';
			$Resultado = $this->db->query($Consulta);
			$Usuario_Asi = $Resultado->row_array();
			
			$Rechazar_A = explode('-', $rech_a);
			//A quien se le asignara?
			$Consulta = '
				select usuario from usuario where id_usuario = "'.$Rechazar_A[2].'"
			';
			//echo $Consulta.'<br />';
			$Resultado = $this->db->query($Consulta);
			$Usuario_Rech = $Resultado->row_array();
			
			
			//Este pedido es hijo de otro?
			$Consulta = '
				select id_ped_primario
				from pedido_pedido
				where id_ped_secundario = "'.$Id_Pedido.'"
			';
			//echo $Consulta.'<br />';
			$Resultado = $this->db->query($Consulta);
			
			
			if(0 < $Resultado->num_rows())
			{
				
				
				$Fila = $Resultado->row_array();
				$Id_Pedido = $Fila['id_ped_primario'];
				
				//Ahora se identifica el id_peus e id_usuario que pertenece a plani
				//y que esta antes del grupo en su ruta.
				$Consulta = '
					select id_peus, peus.id_usuario, id_grupo
					from pedido_usuario peus, usuario usu
					where peus.id_usuario = usu.id_usuario
					and id_pedido = "'.$Id_Pedido.'" and estado = "Terminado"
					order by id_peus desc
					limit 0, 1
				';
				
				$Resultado = $this->db->query($Consulta);
				
				$Fila = $Resultado->row_array();
				
				
				
				if('Ventas' == $Usuario_Rech['usuario'])
				{
					//APLICACION DEL RECHAZO CUANDO EL RESPONSABLE SEA ALGUIEN DE UN GRUPO EXTERNO
					//Se aplica el rechazo En el grupo externo
					$Rechazos_Grupo = implode(', ', $Rechazos_Grupo);
					$Rechazos_Grupo = str_replace(
						'[$rech_a]',
						$Fila['id_usuario'],
						$Rechazos_Grupo
					);
					$Rechazos_Grupo = str_replace(
						'[$Id_Pedido]',
						$Id_Pedido,
						$Rechazos_Grupo
					);
					
					$Consulta = '
						insert into pedido_rechazo values '.$Rechazos_Grupo.'
					';
					//echo $Consulta.'<br />';
					if($Tipo == 'rechazo')
					{
						$this->db->query($Consulta);
					}
					
				}
				
				
				if('Ventas' == $Usuario_Asi['usuario'])
				{
					
					//En la ruta principal, este usuario poseia un tiempo corriendo.
					//Debe ser pausado
					$this->detener_tiempo($Id_Pedido);
					
					
					//Se quita el estado de Asignado al grupo que lo poseia
					$Consulta = '
						update pedido_usuario
						set estado = "Agregado"
						where id_pedido = "'.$Id_Pedido.'" and estado = "Asignado"
					';
					//echo $Consulta.'<br />';
					$this->db->query($Consulta);
					
					//Y se pone el estado de Asignado al planificador
					$Consulta = '
						update pedido_usuario
						set estado = "Asignado"
						where id_peus = "'.$Fila['id_peus'].'"
					';
					//echo $Consulta.'<br />';
					$this->db->query($Consulta);
					
				}
				
				
				if('Ventas' == $Usuario_Asi['usuario'])
				{
					//Se deja un tiempo abierto para el planificador
					$this->crear_tiempo($Id_Pedido, $Fila['id_usuario']);
				}
				
				
			}
			*/
			//** Tan pocas lineas y tanto tiempo quebrandose la cabeza **//
			//** Existira una profesion menos peligrosa que esta? **//
			
		}
		
		return 'ok';
		
	}
	
	
	/**
	 *Inicia el tiempo de trabajo para el pedido y usuario en turno.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return nada.
	*/
	function iniciar($Id_Pedido, $Id_Peus)
	{
		
		//Se ingresa en la base de datos el registro para que inicie a aumentar el
		//tiempo utilizado, ver funcion ubicada lineas mas abajo para su explicacion
		$this->crear_tiempo($Id_Pedido, $this->session->userdata('id_usuario'));
		
		
		//Cambio el estado del pedido_usuario
		$Consulta = '
			update pedido_usuario
			set estado = "Procesando", fecha_inicio = "'.date('Y-m-d H:i:s').'"
			where id_peus = "'.$Id_Peus.'"
		';
		
		$this->db->query($Consulta);
		
	}
	
	
	/**
	 *Pausa el tiempo de trabajo para el pedido y usuario en turno.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return string ok.
	*/
	function pausar($Id_Pedido, $Id_Peus)
	{
		
		//Si hay tiempo corriendo se debe pausar y calcular lo que se utilizo
		$this->detener_tiempo($Id_Pedido);
		
		//Cambiar estado a Pausado
		$Consulta = '
			update pedido_usuario
			set estado = "Pausado"
			where id_peus = "'.$Id_Peus.'"
		';
		
		$this->db->query($Consulta);
		
	}
	
	
	/**
	 *Continuar el tiempo de trabajo para el pedido y usuario en turno.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return string ok.
	*/
	function continuar($Id_Pedido, $Id_Peus)
	{
		
		$this->crear_tiempo($Id_Pedido, $this->session->userdata('id_usuario'));
		
		
		//Cambio el estado del pedido_usuario
		$Consulta = '
			update pedido_usuario
			set estado = "Procesando"
			where id_peus = "'.$Id_Peus.'"
		';
		
		$this->db->query($Consulta);
		
	}
	
	
	/**
	 *Finalizar pedido para este usuario y si es el ultimo puesto finalizar todo
	 *el pedido.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return string ok.
	*/
	function finalizar($Id_Pedido, $Id_Peus)
	{
		
		$Ped = 0;
		//$Usuario_Grupo = 0;
		
		
		$Fecha_Hora = date('Y-m-d H:i:s');
		
		//Si hay tiempo corriendo se debe detener y calcular lo que se utilizo
		$this->detener_tiempo($Id_Pedido);
		
		
		//Cambio el estado del pedido_usuario
		$Consulta = '
			update pedido_usuario
			set estado = "Terminado", fecha_fin = "'.$Fecha_Hora.'"
			where id_peus = "'.$Id_Peus.'"
		';
		$this->db->query($Consulta);
		


		/*
		$Consulta = '
			select id_grupo
			from pedido_usuario peus, usuario usu
			where peus.id_usuario = usu.id_usuario and id_peus = "'.$Id_Peus.'"
		';
		$Resultado = $this->db->query($Consulta);
		$Grupo_Usuario = $Resultado->row_array();
		
		if(isset($Grupo_Usuario['id_grupo']) && $Grupo_Usuario['id_grupo'] == $this->session->userdata('id_grupo'))
		{
			*/
			$Consulta = '
				update pedido_usuario
				set id_usuario = "'.$this->session->userdata('id_usuario').'"
				where id_peus = "'.$Id_Peus.'"
			';
			$this->db->query($Consulta);
		
		//}
		
		//Hay casos especiales en los que no se asigna tiempo a un puesto
		//(Planificacion, Calidad, etc) y no hay un tiempo iniciado.
		//Se actualiza si este puesto tiene 'N/A'
		$Consulta = '
			update pedido_usuario
			set fecha_inicio = "'.$Fecha_Hora.'"
			where id_peus = "'.$Id_Peus.'" and tiempo_asignado = "N/A"
		';
		$this->db->query($Consulta);
		
		
		
		//Que departamento libera el trabajo?
		$Consulta = '
			select usuario
			from pedido_usuario peus, usuario usu, departamentos dpto
			where id_peus = "'.$Id_Peus.'" and peus.id_usuario = usu.id_usuario
			and usu.id_dpto = dpto.id_dpto
		';
		$Resultado = $this->db->query($Consulta);
		$Ha_Liberado = $Resultado->row_array();
		$Ha_Liberado = $Ha_Liberado['usuario'];
		
		
		
		//Debo saber si hay un puesto despues de el actual o debe finalizarse el pedido
		$Consulta = '
			select id_peus, codigo, peus.id_usuario, tiempo, id_grupo
			from pedido_usuario peus, usuario usu, departamentos dpto
			where peus.id_usuario = usu.id_usuario and usu.id_dpto = dpto.id_dpto
			and id_pedido = "'.$Id_Pedido.'" and id_peus > "'.$Id_Peus.'"
			order by id_peus asc limit 0, 1
		';
		
		$Resutado = $this->db->query($Consulta);
		
		
		if(1 == $Resutado->num_rows())
		{
			
			//Si obtengo resultado se debe asignar el pedido a ese puesto
			$Fila = $Resutado->result_array();
			
			$Consulta = '
				update pedido_usuario
				set estado = "Asignado", fecha_asignado = "'.$Fecha_Hora.'"
				where id_peus = "'.$Fila[0]['id_peus'].'"
			';
			
			$this->db->query($Consulta);
			
			
			
			
			//Que departamento recibe el trabajo?
			$Consulta = '
				select usuario
				from pedido_usuario peus, usuario usu, departamentos dpto
				where id_peus = "'.$Fila[0]['id_peus'].'" and peus.id_usuario = usu.id_usuario
				and usu.id_dpto = dpto.id_dpto
			';
			$Resultado = $this->db->query($Consulta);
			$Ha_Recibido = $Resultado->row_array();
			$Ha_Recibido = $Ha_Recibido['usuario'];
			
			
			//Agrega un comentario
			$Consulta = '
				insert into observacion values(
					NULL,
					"'.$Id_Pedido.'",
					"'.$this->session->userdata('id_usuario').'",
					"'.date('Y-m-d H:i:s').'",
					"<strong>Trabajo liberado: '.$Ha_Liberado.' -> '.$Ha_Recibido.'</strong>",
					"n"
				)
			';
			$this->db->query($Consulta);
			
			
			if('Plani' == $Fila[0]['codigo'])
			{
				$this->crear_tiempo($Id_Pedido, $Fila[0]['id_usuario']);
			}
			//}
			
			


			/*
			//Chistosamente, es probable que ventas () libere directo a plani ()
			//En ese caso debo saber si el pedido ha sido asignado a un usuario-grupo
			//Finalizar el usuario anterior y asignar el primer usuario "Agregado"
			$Consulta = '
				select id_grupo
				from usuario_grupo
				where id_usuario = "'.$Fila[0]['id_usuario'].'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				$Resultado = $Resultado->row_array();
				$Usuario_Grupo = $Resultado['id_grupo'];
				
				//Debo saber el id_pedido hijo
				$Consulta = '
					select id_ped_secundario
					from pedido_pedido
					where id_ped_primario = "'.$Id_Pedido.'"
					order by id_pedido_pedido desc
					limit 0, 1
				';
				
				$Resultado = $this->db->query($Consulta);
				
				if(0 < $Resultado->num_rows())
				{
					
					$Ped_Hijo = $Resultado->row_array();
					
					//Si hay tiempo corriendo se debe detener y calcular lo que se utilizo
					$this->detener_tiempo($Ped_Hijo['id_ped_secundario']);
					
					
					//Cambio el estado del pedido_usuario
					$Consulta = '
						update pedido_usuario
						set estado = "Terminado", fecha_fin = "'.$Fecha_Hora.'"
						where id_pedido = "'.$Ped_Hijo['id_ped_secundario'].'"
						and estado = "Asignado"
					';
					
					$this->db->query($Consulta);
					
					//Cambio el estado del pedido_usuario
					$Consulta = '
						update pedido_usuario
						set estado = "Asignado", fecha_asignado = "'.$Fecha_Hora.'"
						where id_pedido = "'.$Ped_Hijo['id_ped_secundario'].'"
						and estado = "Agregado"
						order by id_peus asc
						limit 1
					';
					
					$this->db->query($Consulta);
					
					
					$this->crear_tiempo($Ped_Hijo['id_ped_secundario'], 0);
					
				}
			}
			*/
		}
		else
		{
			//Si no obtengo resultados se debe finalizar el pedido completo
			$Consulta = '
				update pedido
				set fecha_reale = "'.date('Y-m-d').'"
				where id_pedido = "'.$Id_Pedido.'"
			';
			$this->db->query($Consulta);
			
			
			//Consulta para actualizar la tabla pedido_sap
			$Consulta = '
				update pedido_sap
				set fecha = "'.date('Y-m-d').'"
				where id_pedido = "'.$Id_Pedido.'"
			';
			$this->db->query($Consulta);
			
			
			//Rellenado de la tabla pedido_tie_repro
			$this->tiemp_repro($Id_Pedido);
			
			
			/*
			//Lo chistoso se da cuando el pedido se finaliza por completo pero este
			//pedido esta enlazado con otro hacia arriba.
			//Debo finalizar el usuario del pedido_usuario principal que corresponde a
			//este grupo
			
			//En ese caso debo saber si este pedido esta agregado en pedido_pedido
			//como un pedido secundario
			$Consulta = '
				select id_ped_primario
				from pedido_pedido
				where id_ped_secundario = "'.$Id_Pedido.'"
			';
			
			$Resultado = $this->db->query($Consulta);
			
			if(0 < $Resultado->num_rows())
			{
				
				$Fila = $Resultado->row_array();
				$Id_Pedido = $Fila['id_ped_primario'];
				
				//Busco el id_peus que esta asignado al grupo en la ruta principal
				$Consulta = '
					select id_peus
					from pedido_usuario
					where id_pedido = "'.$Id_Pedido.'" and estado != "Agregado"
					and estado != "Terminado"
					order by id_peus desc
					limit 0, 1
				';
				
				$Resultado = $this->db->query($Consulta);
				$Fila = $Resultado->row_array();
				
				//Finalizo el usuario que corresponde al este grupo en la ruta principal
				$this->finalizar($Id_Pedido, $Fila['id_peus']);
				
				
			}
			*/
		}
		
		return 'ok';//$Usuario_Grupo;
		
	}
	
	
	/**
	 *Finaliza la ejecucion del pedido completo.
	 *@param string $Id_Pedido.
	 *@return string ok.
	*/
	function terminar($Id_Pedido)
	{
		
		$this->detener_tiempo($Id_Pedido);
		
		$Consulta = '
			select id_peus, fecha_inicio
			from pedido_usuario
			where id_pedido = "'.$Id_Pedido.'" and estado != "Terminado"
			order by id_peus desc
			limit 0, 1
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			
			$Fila = $Resultado->row_array();
			
			$Fecha_Fin = '';
			if('0000-00-00 00:00:00' == $Fila['fecha_inicio'])
			{
				$Fecha_Fin = ', fecha_inicio = "'.date('Y-m-d H:i:s').'"';
			}
			
			$Consulta = '
				update pedido_usuario
				set estado = "Terminado", fecha_fin = "'.date('Y-m-d H:i:s').'"'.$Fecha_Fin.'
				where id_peus = "'.$Fila['id_peus'].'"
			';
			
			$this->db->query($Consulta);
			
		}
		
		$Consulta = '
			update pedido_usuario
			set estado = "Terminado", fecha_inicio = "'.date('Y-m-d H:i:s').'",
			fecha_fin = "'.date('Y-m-d H:i:s').'"
			where id_pedido = "'.$Id_Pedido.'" and estado != "Terminado"
		';
		
		$this->db->query($Consulta);
		
		
		//Consulta para actualizar la tabla pedido_sap
		$Consulta = '
			update pedido_sap
			set fecha = "'.date('Y-m-d').'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		$this->db->query($Consulta);
		
		
		$Consulta = '
			update pedido
			set fecha_reale = "'.date('Y-m-d').'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		
		$this->db->query($Consulta);
		
		
		//Rellenado de la tabla pedido_tie_repro
		$this->tiemp_repro($Id_Pedido);
		
		
	}
	
	
	
	/**
	 *Guarda la informacion en la tabla pedido_tie_repro
	*/
	function tiemp_repro($Id_Pedido)
	{
		
		$Consulta = '
			select fecha_entrada
			from pedido
			where id_pedido = "'.$Id_Pedido.'"
		';
		$Resultado = $this->db->query($Consulta);
		$Entrada = $Resultado->row_array();
		
		//Debo calcular entonces el tiempo de resplani, arte, aprobacion, efinales; y guardarlo en una nueva tabla :S
		
		//Dias entre fecha de inicio y fin del pedido actual
		//Pasar a segundos la fecha de entrada
		$segundos_entrada = $this->fechas_m->fecha_a_segundos($Entrada['fecha_entrada'].' 00:00:01');
		//Pasar a segundos la fecha de finalizacion
		$segundos_real = $this->fechas_m->fecha_a_segundos(date('Y-m-d').' 00:00:01');
		//Convierto a total de dias que estuvo el trabajo en central-g
		$DiasTotalRepro = number_format((($segundos_real - $segundos_entrada) / 86400 + 1), 2);
		
		$DiasArte = 'n/a';//Total dias en arte, inicializamos como n/a por si no fuera el caso
		$DiasEFinal = 'n/a';//Total dias eFinal, inicializamos como n/a por si no fuera el caso
		
		
		
		
		//Si este pedido ya tuviere un registro en pedido_tie_repro, es posible que sea un pedido traido del mas alla
		$sql = 'delete from pedido_tie_repro where id_pedido = "'.$Id_Pedido.'"';//Debe ser eliminado
		$this->db->query($sql);
		
		//Hay elementos de arte o pdf en este pedido?
		$Consulta = '
			select id_material_solicitado
			from especificacion_matsolgru espe, material_solicitado_grupo mate
			where espe.id_material_solicitado_grupo = mate.id_material_solicitado_grupo
			and id_pedido = "'.$Id_Pedido.'" and (id_material_solicitado = 1
			or id_material_solicitado = 2 or id_material_solicitado = 16)
		';
		
		$Resultado = $this->db->query($Consulta);
		
		//Hay elementos de arte o pdf en este pedido?
		if(0 < $Resultado->num_rows())
		{
			$DiasArte = $DiasTotalRepro;
		}
		
		//Busco si este pedido tiene elementos finales solicitados
		$Consulta = '
			select id_material_solicitado
			from especificacion_matsolgru espe, material_solicitado_grupo mate
			where espe.id_material_solicitado_grupo = mate.id_material_solicitado_grupo
			and id_pedido = "'.$Id_Pedido.'" and (id_material_solicitado = 4
			or id_material_solicitado = 6 or id_material_solicitado = 8
			or id_material_solicitado = 9 or id_material_solicitado = 10
			or id_material_solicitado = 11 or id_material_solicitado = 12
			or id_material_solicitado = 13 or id_material_solicitado = 15)
		';
		
		$Resultado = $this->db->query($Consulta);
		
		if(0 < $Resultado->num_rows())
		{
			$DiasArte = 'n/a';
			$DiasEFinal = $DiasTotalRepro;
		}
		
		
		//Todo listo, ingreso de nuevo registro
		$Consulta = '
			insert into pedido_tie_repro values(
				NULL,
				"'.$Id_Pedido.'",
				"n/a",
				"'.$DiasArte.'",
				"n/a",
				"'.$DiasEFinal.'",
				"0",
				"0",
				"0"
			)
		';
		$this->db->query($Consulta);
		
		
		
	}
	
	
	/**
	 *Cambiar la fecha de entrega.
	 *@param string $Id_Pedido.
	 *@return string ok.
	*/
	function fecha($Id_Pedido, $Pon_Comentario = true)
	{
		
		$Fecha_Entrega = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha_entrega')
		);
		$Quien_Solicita = $this->seguridad_m->mysql_seguro(
			$this->input->post('quien_solicita')
		);
		$Justifica_Fecha = $this->seguridad_m->mysql_seguro(
			$this->input->post('justifica_fecha')
		);
		$Fecha_Anterior = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha_anterior')
		);
		
		$Consulta = '
			update pedido
			set fecha_entrega = "'.$this->fechas_m->fecha_dmy_ymd($Fecha_Entrega).'"
			where id_pedido = "'.$Id_Pedido.'"
		';
		//echo $Consulta;
		$this->db->query($Consulta);
		
		if($Pon_Comentario)
		{
			$Consulta = '
				insert into observacion values(
					NULL,
					"'.$Id_Pedido.'",
					"'.$this->session->userdata('id_usuario').'",
					"'.date('Y-m-d H:i:s').'",
					"Se realiz&oacute; Cambio de Fecha.<br />Solicitado por: '.$Quien_Solicita.'.<br />Por los siguientes motivos: '.$Justifica_Fecha.'.<br />Fecha Anterior: '.$Fecha_Anterior.', Nueva Fecha: '.$Fecha_Entrega.'",
					"n"
				)
			';
			//echo $Consulta.'<br><br>';
			$this->db->query($Consulta);
		}
		
	}
	
	
	/**
	 *Ingresa en la base de datos una marca para medir el tiempo
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	function crear_tiempo($Id_Pedido, $Id_Usuario)
	{
		
		//Debo confirmar que no existe un tiempo ya abierto
		$Consulta = '
			select id_tiempo
			from pedido_tiempos
			where id_pedido = "'.$Id_Pedido.'"
			and id_usuario = "'.$Id_Usuario.'"
			and fin = "0000-00-00 00:00:00"
		';
		
		$Result = $this->db->query($Consulta);
		
		//Si no hay tiempos abiertos, creo un registro
		if(0 == $Result->num_rows())
		{
			
			$Tipo = 0;
			
			$Consulta = '
				select tipo
				from pedido_usuario
				where id_pedido = "'.$Id_Pedido.'" and id_usuario = "'.$Id_Usuario.'"
				and estado != "Terminado" and estado != "Agregado"
			';
			
			$Result = $this->db->query($Consulta);
			
			if(1 == $Result->num_rows())
			{
				$Tipo = $Result->row_array();
				$Tipo = $Tipo['tipo'];
			}
			
			$Consulta = '
				insert into pedido_tiempos values(
					NULL,
					"'.$Id_Pedido.'",
					"'.$Id_Usuario.'",
					"'.date('Y-m-d H:i:s').'",
					"0000-00-00 00:00:00",
					"0",
					"'.$Tipo.'"
				)
			';
			
			$this->db->query($Consulta);
			
		}
		
	}
	
	
	/**
	 *Busca si hay un tiempo en ejecucion y lo finaliza.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	function detener_tiempo($Id_Pedido)
	{
		
		//Selecciono la fecha y hora de inicio de este tiempo
		$Consulta = '
			select inicio, id_usuario
			from pedido_tiempos
			where id_pedido = "'.$Id_Pedido.'"
			and fin = "0000-00-00 00:00:00"
		';
		
		$Result = $this->db->query($Consulta);
		
		if(0 < $Result->num_rows())
		{
			$Fila = $Result->row_array();
			
			$Minutos = 0;
			$Consulta_Set = '';
			
			
			
			if('0' == $Fila['id_usuario'])
			{
				$Consulta_Set = ',id_usuario = "'.$this->session->userdata('id_usuario').'"';
				
				$CI =& get_instance();
				$CI->load->model('utilidades/fechas_m','fechas',true);
				
				// use $CI instead of $this to query the other models
				$Minutos = $CI->fechas->tiempo_habil($Fila['inicio'], date('Y-m-d H:i:s'));
				
			}
			else
			{
				//Debo saber cuantos minutos tardo en realizarse este trabajo
				$Minutos = $this->fechas_m->minutos_de_trabajo(
					$Fila['inicio'],
					date('Y-m-d H:i:s')
				);
			}
			
			//Actualizacion del trabajo
			$Consulta = '
				update pedido_tiempos
				set fin = "'.date('Y-m-d H:i:s').'", tiempo = "'.$Minutos.'"
				'.$Consulta_Set.'
				where id_pedido = "'.$Id_Pedido.'"
				and fin = "0000-00-00 00:00:00"
			';
			
			$this->db->query($Consulta);
		}
		
	}
	
	/**
	 *Permite cambiar el tiempo asignado a un usuario.
	 *@return nada.
	*/
	function cambiar_tiempo($id_peus, $horas, $minutos, $id_usuario)
	{
		$tiempo = $horas.':'.$minutos;
		$tiempo_total = $this->fechas_m->hora_a_minutos($tiempo);
		
		$Consulta = 'update pedido_usuario
								set tiempo_asignado = "'.$tiempo_total.'"
								where id_peus = "'.$id_peus.'"';
		$Resultado = $this->db->query($Consulta);
		
		$Consulta = 'select sum(petie.tiempo) as tiempo
								from pedido_tiempos petie, pedido_usuario peus
								where peus.id_peus = "'.$id_peus.'"
								and peus.id_pedido = petie.id_pedido
								and petie.id_usuario = "'.$id_usuario.'"';
								
		
		$Resultado = $this->db->query($Consulta);
		foreach($Resultado->result_array() as $Datos)
		{
			$tiempo_utilizado = $Datos['tiempo'];
		}
		
		$tiempo_asignado = $this->fechas_m->minutos_a_hora($tiempo_total);
		$tiempo_restante = $this->fechas_m->minutos_a_hora($tiempo_total - $tiempo_utilizado);
		
		return $tiempo_asignado.'-'.$tiempo_restante;
	}
	
	/**
	 *Actualizara el listado de los procesos que solicitan cambio de fecha.
	 *@return nada.
	*/
	function actualizar_cambio_fecha()
	{
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		$Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		$Fecha = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha_entrega')
		);
		
		$Consulta = 'update sol_cambio_fecha set activo="no"
				where proceso = "'.$Proceso.'"
				and codigo_cliente = "'.$Cliente.'"
				and fecha = "'.$this->fechas_m->fecha_dmy_ymd($Fecha).'"
				';
		//echo $Consulta;
		$Resultado = $this->db->query($Consulta);
		
	}
	
	
	/**
	 *Retoque de imagen.
	 *@return nada.
	*/
	function retoque($Id_Pedido)
	{
		$Consulta = 'select * from pedido_usuario where id_pedido = '.$Id_Pedido;
		$Resultado = $this->db->query($Consulta);
		foreach($Resultado->result_array() as $Datos)
		{
			if($this->session->userdata('id_usuario') == $Datos['id_usuario'])
			{
				$this->detener_tiempo($Id_Pedido);
				
				$Consulta = 'select usu.id_usuario, peus.id_peus
						from pedido_usuario peus, usuario usu, departamentos dpto
						where peus.id_usuario = usu.id_usuario
						and dpto.id_dpto = usu.id_dpto and dpto.id_dpto = "2"
						and peus.id_pedido = "'.$Id_Pedido.'"
					';
					
				$Resultado = $this->db->query($Consulta);
				if(0 < $Resultado->num_rows())
				{
					foreach($Resultado->result_array() as $Datos)
					{
						$Id_peus = $Datos['id_peus'];
						$Consulta = 'update pedido_usuario set estado = "Asignado"
							where id_usuario = "'.$Datos['id_usuario'].'"
							and id_pedido = "'.$Id_Pedido.'"';
						//echo $Consulta.'<br>';
						$Resultado = $this->db->query($Consulta);
						
					}
				
					$Consulta = 'update pedido_usuario set estado = "Agregado"
							where id_pedido = "'.$Id_Pedido.'"
							and id_peus > "'.$Id_peus.'"';
					//echo $Consulta.'<br>';
					$Resultado = $this->db->query($Consulta);
				}
			}
			
		}
	}
	
	
}

/* Fin del archivo */