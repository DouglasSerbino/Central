<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingresar_ruta_m extends CI_Model {
	
	function __construct()
	{
		parent::__construct();
	}
	
  /**
	 *Ingresa la ruta de trabajo para el pedido senhalado.
	 *@param string $Id_Pedido.
	 *@param string $Ruta.
	 *@param string $Fecha_Hora.
	 *@param string $Id_Grupo.
	 *@return string $Mensaje.
	*/
	function index($Id_Pedido, $Ruta, $Fecha_Hora, $Plani_Estado = 'Terminado', $Id_Dpto = 0)
	{

		//Aplica para vendedores y planificadores
		//$Agregar_Automatico = false;
		//Variable para almacenar la ruta de trabajo, por default posee ruta de
		//ventas y plani
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
			)'
		);

		if('Asignado' == $Plani_Estado)
		{
			$Ruta_Trabajo[] = '(
				NULL,
				"2",
				"'.$Id_Pedido.'",
				"6",
				"'.$Fecha_Hora.'",
				"0000-00-00 00:00:00",
				"0000-00-00 00:00:00",
				"N/A",
				"Asignado",
				99,
				"0"
			)';
		}
		else
		{
			$Ruta_Trabajo[] = '(
				NULL,
				"2",
				"'.$Id_Pedido.'",
				"14",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"'.$Fecha_Hora.'",
				"N/A",
				"Terminado",
				99,
				"0"
			)';
		}
		
		//Estado del primer puesto
		$Estado = 'Asignado';
		//Id del usuario que fue al que fue asignado el pedido
		$Id_Usuario_Asignado = 0;
		//Mensaje a regresar
		$Mensaje = '';

		$Inicio_Fin = '0000-00-00 00:00:00';

		
		
		//Recorro la ruta de este grupo para tomar asi los puestos seleccionados
		foreach($Ruta as $Dpto)
		{

			
			//Limpieza de variables
			$Id_Usuario = $this->seguridad_m->mysql_seguro(
				$this->input->post('slc_'.$Dpto['id_ruta_puesto'])
			);
			
			$Tiempo = $this->seguridad_m->mysql_seguro(
				$this->input->post('tie_'.$Dpto['id_ruta_puesto'])
			);
			
			
			
			//Si no es un campo automatico debe haberse seleccionado el puesto actual
			//'n' == $Dpto['automatico'] and 
			if(
				'on' != $this->input->post('chk_'.$Dpto['id_ruta_puesto'])
			)
			{
				//Si no esta seleccionado pasamos al siguiente
				continue;
			}
			
			
			/*
			//Si este usuario enlaza a un grupo y tiene estado Asignado
			if(isset($Usu_Grup[$Id_Usuario]) && 'Asignado' == $Estado)
			{
				//Se regresara la info para agregar una ruta en ese grupo
				$Mensaje['Grupos']['id_grupo'] = $Usu_Grup[$Id_Usuario];
				$Mensaje['Grupos']['usuario'] = $Id_Usuario;
			}
			*/
			
			//Agregamos un registro para este dpto
			$Ruta_Trabajo[] = '(
				NULL,
				"'.$Dpto['id_ruta_puesto'].'",
				"'.$Id_Pedido.'",
				"'.$Id_Usuario.'",
				"'.$Fecha_Hora.'",
				"'.$Inicio_Fin.'",
				"'.$Inicio_Fin.'",
				"'.$Tiempo.'",
				"'.$Estado.'",
				99,
				"0"
			)';
			
			
			if('Asignado' == $Estado)
			{
				$Id_Usuario_Asignado = $Id_Usuario;
			}
			
			/*if('Asignado' == $Estado && 's' == $Dpto['automatico'])
			{
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
			*/
			
			
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
		
		if(0 == count($Ruta_Trabajo))
		{//Si no tiene elementos la ruta de trabajo
			//es que algo paso
			$Mensaje = 'error';
		}
		else
		{
			//Si todo salio bien, creamos la consulta
			$Consulta = '
				INSERT INTO pedido_usuario values '.implode(',', $Ruta_Trabajo).'
			';
			//Ingresamos la ruta de trabajo
			$this->db->query($Consulta);
			
			//Informamos que todo fue bien creado regresando el $Id_Usuario_Asignado
			$Mensaje = $Id_Usuario_Asignado;
		}
		
		
		return $Mensaje;
		
	}
	
	
	
}

/* Fin del archivo */