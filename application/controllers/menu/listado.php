<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		switch($Mensaje)
		{
			case 'mok':
				$Mensaje = 'El Men&uacute; fue modificado exitosamente.';
				break;
			case 'dok':
				$Mensaje = 'El Men&uacute; fue desactivado exitosamente.';
				break;
			case 'aok':
				$Mensaje = 'El Men&uacute; fue activado exitosamente.';
				break;
			case 'error':
				$Mensaje = 'Ocurri&oacute; un error en la acci&oacute;n solicitada, favor intentar nuevamente.<br />Se ha creado un registro del error para buscar una soluci&oacute;n';
				break;
			default:
				$Mensaje = '';
				break;
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Men&uacute; de Usuario',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Modelo que realiza la busqueda de los Menus.
		$this->load->model('menu/listar_m', 'listar_m');
		//listar_m = Listar Menus
		//Obtencion del listado de los Menus.
		$Menu = $this->listar_m->listado();
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Menu' => $Menu,
			'Pagina' => 'listado'
		);
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('menu/listado_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */