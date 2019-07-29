<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permisos_usuarios_clientes extends CI_Controller {
	
	/*
	*Funcion para desactivar  o activar los clientes
	*@param string id del cliente para poder desactivarlo.
	**@param string Tipo determinara si se activa o desactiva el cliente.
	*@param return ok si si desactiva el cliente
	*/
	public function accion($Tipo = '', $Id_Cliente, $Id_Usuario)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a esta pagina.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deben tener acceso a esta informacion
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que desactiva los clientes
		$this->load->model('clientes/desactivar_activar_m', 'cliente');
		
		//Llamamos el modelo para desactivar los clientes.
		$Cliente = $this->cliente->permisos_usuarios_clientes($Tipo, $Id_Cliente, $Id_Usuario);
		
		if('ok' == $Cliente)
		{
			header('location: /clientes/listado/index/'.$Id_Usuario);
			exit();
		}
		else
		{
			header('location: /clientes/listado/index/'.$Id_Usuario);
			exit();
		}
	}
}