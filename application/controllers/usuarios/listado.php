<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus de usuario existentes.
	 *Tiene opciones para modificar y desactivar.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Activo = 's', $Mensaje = '')
	{
		
		$Permisos = array('usuarios');
		$this->ver_sesion_m->acceso('autorizados', false, $Permisos);
		
		$this->ver_sesion_m->no_clientes();
		
		switch($Mensaje)
		{
			case 'ok':
				$Mensaje = 'El Usuario fue modificado exitosamente.';
				break;
			case 'error':
				$Mensaje = 'Ocurri&oacute; un error en la acci&oacute;n solicitada, favor intentar nuevamente.<br />Se ha creado un registro del error para buscar una soluci&oacute;n';
				break;
			default:
				$Mensaje = '';
				break;
		}
		
		//Validacion sencilla
		if('n' != $Activo)
		{
			$Activo = 's';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Administraci&oacute;n de Usuarios',
			'Mensaje' => $Mensaje,
			'Activo' => $Activo
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		

		//Modelo que realiza la busqueda de los Menus.
		$this->load->model('usuarios/listado_usuario_m', 'listar_u');
		
		//Obtencion del listado de los Usuarios.
		$Variables['Usuarios'] = $this->listar_u->departamento_usuario('', false, $Activo);
		
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('usuarios/listado_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */