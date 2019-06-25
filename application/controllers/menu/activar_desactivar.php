<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activar_desactivar extends CI_Controller {
	
	/**
	 *Tareas de administracion: Desactivar menu, evita que este disponible para
	 *los usuarios en sus menus personalizados.
	 *@param int $Id_Menu.
	 *@return nada.
	*/
	public function activar($Id_Menu = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion del id del menu a desactivar
		$Id_Menu += 0;
		if(0 == $Id_Menu)
		{
			//Si no es un int el recibido, muestro un error
			show_404();
			//No permito que continue el script
			exit();
		}
		
		
		//Cargo el modelo que desactiva el menu
		$this->load->model('menu/activar_desactivar_m', 'activar_m');
		//activar_m = Activar Menu
		//Realizo la desactivacion del menu
		$Activar = $this->activar_m->activar($Id_Menu);
		
		if('ok' == $Activar)
		{
			header('location: /menu/listado/index/aok');
		}
		else
		{
			header('location: /menu/listado/index/error');
		}
		
		
	}
	
	/**
	 *Tareas de administracion: Desactivar menu, evita que este disponible para
	 *los usuarios en sus menus personalizados.
	 *@param int $Id_Menu.
	 *@return nada.
	*/
	public function desactivar($Id_Menu = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion del id del menu a desactivar
		$Id_Menu += 0;
		if(0 == $Id_Menu)
		{
			//Si no es un int el recibido, muestro un error
			show_404();
			//No permito que continue el script
			exit();
		}
		
		
		//Cargo el modelo que desactiva el menu
		$this->load->model('menu/activar_desactivar_m', 'desactivar_m');
		//desactivar_m = Desactivar Menu
		//Realizo la desactivacion del menu
		$Desactivar = $this->desactivar_m->desactivar($Id_Menu);
		
		if('ok' == $Desactivar)
		{
			header('location: /menu/listado/index/dok');
		}
		else
		{
			header('location: /menu/listado/index/error');
		}
		
		
	}
	
}

/* Fin del archivo */