<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activar_desactivar extends CI_Controller {
	
	/**
	 *Tareas de administracion: Desactivar usuarios.
	 *@param int $Id_Usuario.
	 *@return nada.
	*/
	public function acciones($Tipo = '', $Id_Usuario = 0)
	{
		
		$Permisos = array('usuarios');
		$this->ver_sesion_m->acceso('autorizados', false, $Permisos);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion del id del menu a desactivar
		$Id_Usuario += 0;
		if(0 == $Id_Usuario or '' == $Tipo)
		{
			//Si no es un int el recibido, muestro un error
			show_404();
			//No permito que continue el script
			exit();
		}
		
		
		//Cargo el modelo que desactiva el usuario
		$this->load->model('usuarios/activar_desactivar_m', 'usuarios');
		
		$Usuario = $this->usuarios->accion($Tipo , $Id_Usuario);
		
		if('ok' == $Usuario)
		{
			header('location: /usuarios/listado/index/');
		}
		else
		{
			header('location: /usuarios/listado/index/s/error');
		}
	}
}

/* Fin del archivo */