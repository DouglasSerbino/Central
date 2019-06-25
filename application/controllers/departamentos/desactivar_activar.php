<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_Activar extends CI_Controller {
	
	/**
	 *Cambia el estado de un departamento a Activo o Inactivo segun la solicitud
	 *realizada por el usuario.
	*/
	public function opcion($Id_dpto, $opcion)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso.
		$this->ver_sesion_m->no_clientes();
		
		
		//Carga del modelo que desactiva los departamentos
		$this->load->model('departamentos/desactivar_activar_m', 'opcion_m');
		
		//Llamamos el modelo para desactivar o desactivar los grupos.
		$dpto = $this->opcion_m->Operacion($Id_dpto, $opcion);
		
		
		//Enviamos a la pagina listado para mostrar los departamentos
		header('location: /departamentos/listado_dptos');
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
}