<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Desactivar_Activar extends CI_Controller {
	
	/*
	*Funcion para desactivar  o activar los clientes
	*@param string id del cliente para poder desactivarlo.
	**@param string Tipo determinara si se activa o desactiva el cliente.
	*@param return ok si si desactiva el cliente
	*/
	public function accion($Tipo = '', $Id_cliente)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta informacion.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no tendran acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que desactiva los departamentos
		$this->load->model('clientes/desactivar_activar_m', 'cliente');
		
		//Llamamos el modelo para desactivar los grupos.
		$Cliente = $this->cliente->acciones($Tipo, $Id_cliente);
		//Redirigimos a la pagina de administracion de clientes.
		header('location: /clientes/administrar_clientes');
		exit();
	}
}