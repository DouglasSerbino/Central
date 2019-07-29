<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_ruta_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Modifica la ruta de trabajo para el pedido senhalado.
	 *@param string $Id_Pedido.
	 *@param string $Ruta.
	 *@param string $Fecha_Hora.
	 *@param string $Puesto_Asignado.
	 *@param string $Usu_Grup.
	 *@return string ['ok'|'error'].
	*/
	function index($Id_Pedido, $Ruta, $Fecha_Hora, $Puesto_Asignado, $Usu_Grup)
	{
		
		//Obtencion de la ruta de trabajo actual
		$Consulta = '
			select id_ruta_dpto, id_usuario, fecha_asignado, fecha_inicio, fecha_fin,
			estado, pprioridad
			from pedido_usuario
			where id_pedido = "'.$Id_Pedido.'"
			order by id_peus asc
		';
		
		$Resultado = $this->db->query($Consulta);
		
		$Ruta_Actual = array();
		
		foreach($Resultado->result_array() as $Fila)
		{
			$Ruta_Actual[$Fila['id_ruta_dpto']]['estado'] = $Fila['estado'];
			$Ruta_Actual[$Fila['id_ruta_dpto']]['fecha_fin'] = $Fila['fecha_fin'];
			$Ruta_Actual[$Fila['id_ruta_dpto']]['pprioridad'] = $Fila['pprioridad'];
			$Ruta_Actual[$Fila['id_ruta_dpto']]['id_usuario'] = $Fila['id_usuario'];
			$Ruta_Actual[$Fila['id_ruta_dpto']]['fecha_inicio'] = $Fila['fecha_inicio'];
			$Ruta_Actual[$Fila['id_ruta_dpto']]['fecha_asignado'] = $Fila['fecha_asignado'];
		}
		
		//Permitira saber si se debe de agregar el puesto de ingenieria
		$puesto_ingenieria = false;
		//Aplica para vendedores y planificadores
		$Agregar_Automatico = false;
		//Variable para almacenar la ruta de trabajo
		$Ruta_Trabajo = array(
			'(
				NULL,
				"1",
				"'.$Id_Pedido.'",
				"5",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"N/A",
				"Terminado",
				99,
				"0"
			)',
			'(
				NULL,
				"2",
				"'.$Id_Pedido.'",
				"6",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"N/A",
				"Terminado",
				99,
				"0"
			)'
		);
		//Estado del primer puesto
		$Estado = 'Asignado';
		//Si cambia el usuario asignado, se debe finalizar el tiempo abierto
		//si lo hubiere
		$Finalizar_Tiempo = 'true';
		//Mensajes a regresar
		$Mensajes = array(
			'Estado' => '',//['error'|$Id_Usuario_Asignado]
			'Grupos' => array()//Listado de los grupos que fueron agregados a la ruta
		);
		
		
		
		//Se debe eliminar la ruta actual, para agregar la nueva ruta, es mucho mejor
		//hacer eso que modificar pedido_usuario, es mas facil para mi.
		$Consulta = '
			delete from pedido_usuario where id_pedido = "'.$Id_Pedido.'"
		';
		$this->db->query($Consulta);
		
		
		
		//Recorro la ruta de este grupo para tomar asi los puestos seleccionados
		foreach($Ruta as $Dpto)
		{
			$Asignado = $Fecha_Hora;
			$Inicio = '0000-00-00 00:00:00';
			$Fin = '0000-00-00 00:00:00';
			$Pprioridad = 99;
			//Limpieza de variables
			$Id_Usuario = $this->seguridad_m->mysql_seguro(
				$this->input->post('slc_'.$Dpto['id_ruta_puesto'])
			);
			//Este usuario cambiara si es parte de la ruta actual y sirve para comparar
			//contra el que viene del formulario
			$Id_Usuario_Actual = $Id_Usuario;
			//Tiempo asignado.
			$Tiempo = $this->seguridad_m->mysql_seguro(
				$this->input->post('tie_'.$Dpto['id_ruta_puesto'])
			);
			
			
			
			//Si no es un campo automatico debe haberse seleccionado el puesto actual
			if(
				'on' != $this->input->post('chk_'.$Dpto['id_ruta_puesto'])
			)
			{
				//Si no esta seleccionado pasamos al siguiente
				continue;
			}
			
			
			//Necesito los datos actuales del puesto seleccionado
			if(
				isset($Ruta_Actual[$Dpto['id_ruta_puesto']])
			)
			{
				//Quien era el usuario asignado anteriormente?
				$Id_Usuario_Actual = $Ruta_Actual[$Dpto['id_ruta_puesto']]['id_usuario'];
				//Siempre y cuando sea el mismo operador
				if($Id_Usuario == $Ruta_Actual[$Dpto['id_ruta_puesto']]['id_usuario'])
				{
					$Asignado = $Ruta_Actual[$Dpto['id_ruta_puesto']]['fecha_asignado'];
					$Inicio = $Ruta_Actual[$Dpto['id_ruta_puesto']]['fecha_inicio'];
					$Fin = $Ruta_Actual[$Dpto['id_ruta_puesto']]['fecha_fin'];
					$Pprioridad = $Ruta_Actual[$Dpto['id_ruta_puesto']]['pprioridad'];
				}
			}
			
			
			
			//Estado a asignar, dependiendo de como se asigno el pedido en el formulario
			
			if($Dpto['id_ruta_puesto'] < $Puesto_Asignado)
			{
				//Ejemplo: Si(5 < 7)
				$Estado = 'Terminado';
				//Si este puesto no tenia fecha y hora de inicio, se debe poner el actual
				if('0000-00-00 00:00:00' == $Inicio)
				{
					$Inicio = $Fecha_Hora;
				}
				//Si este puesto no tenia fecha y hora de fin, se debe poner el actual
				if('0000-00-00 00:00:00' == $Fin)
				{
					$Fin = $Fecha_Hora;
				}
			}
			
			if($Dpto['id_ruta_puesto'] == $Puesto_Asignado)
			{
				//Ejemplo: Si(7 == 7)
				//Pero si pertenece al mismo usuario que ya estaba trabajandolo
				if(
					$Id_Usuario == $Id_Usuario_Actual
					&& isset($Ruta_Actual[$Dpto['id_ruta_puesto']])
					&& $Ruta_Actual[$Dpto['id_ruta_puesto']]['estado'] != 'Terminado'
					&& $Ruta_Actual[$Dpto['id_ruta_puesto']]['estado'] != 'Agregado'
				)
				{
					//Se deja el valor que ya poseia y no se finalizara su tiempo
					$Estado = $Ruta_Actual[$Dpto['id_ruta_puesto']]['estado'];
					$Finalizar_Tiempo = 'false';
				}
				else
				{
					$Estado = 'Asignado';
				}
			}
			
			if($Dpto['id_ruta_puesto'] > $Puesto_Asignado)
			{
				//Ejemplo: Si(9 > 7)
				$Estado = 'Agregado';
			}
			
			
			//Si este usuario enlaza a un grupo y tiene estado Asignado
			if(isset($Usu_Grup[$Id_Usuario]) && 'Asignado' == $Estado)
			{
				//Se regresara la info para agregar una ruta en ese grupo
				$Mensajes['Grupos']['id_grupo'] = $Usu_Grup[$Id_Usuario];
				$Mensajes['Grupos']['usuario'] = $Id_Usuario;
			}
			//echo $Dpto['id_ruta_puesto'].'->'.$Id_Usuario.'<br>';
			
			//Agregamos un registro para este dpto
			$Ruta_Trabajo[] = '(
				id_peus,
				"'.$Dpto['id_ruta_puesto'].'",
				"'.$Id_Pedido.'",
				"'.$Id_Usuario.'",
				"'.$Asignado.'",
				"'.$Inicio.'",
				"'.$Fin.'",
				"'.$Tiempo.'",
				"'.$Estado.'",
				"'.$Pprioridad.'",
				"0"
			)';
			
			
			
			//Si el departamento es de preprensa y se estan solicitando Plotter Separados
			//se debera de agregar el puesto de ingenieria.
			/*if($Dpto['id_ruta_puesto'] == 6 and $puesto_ingenieria)
			{
				$Ruta_Trabajo[] = '(
						id_peus,
						"18",
						"'.$Id_Pedido.'",
						"94",
						"'.$Asignado.'",
						"'.$Inicio.'",
						"'.$Fin.'",
						"0",
						"Agregado",
						"'.$Pprioridad.'",
						"'.$Tipo.'"
					)';
			}
			*/
			//print_r($Ruta_Trabajo);
			//echo '<br><br>';
			if('Asignado' == $Estado)
			{
				$Mensajes['Asignado'] = $Id_Usuario;
			}
			
			
			
			if('Terminado' == $Estado)
			{//Si el puesto anterior fue quien programo el trabajo
				//El proximo puesto debe tener asignado el trabajo
				$Estado = 'Asignado';
			}
			else
			{
				//Los siguientes puestos tendran estado de Agregado
				$Estado = 'Agregado';
			}
			
		}
		
		//exit();
		if(0 == count($Ruta_Trabajo))
		{
			//Si no tiene elementos la ruta de trabajo es que algo paso
			$Mensajes['Estado'] = 'error';
		}
		else
		{
			
			//Si todo salio bien, creamos la consulta
			$Consulta = '
				INSERT INTO pedido_usuario values '.implode(',', $Ruta_Trabajo).'
			';
			
			//Ingresamos la ruta de trabajo
			$this->db->query($Consulta);
			
			//Informamos que todo fue bien creado
			$Mensajes['Estado'] = $Finalizar_Tiempo;
		}
		
		
		return $Mensajes;
		
	}
	
}

/* Fin del archivo */