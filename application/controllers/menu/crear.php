<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear extends CI_Controller {
	
	/**
	 *Formulario de creacion de Menu
	 *@param string $Mensaje: Notificacion para retroalimentar al usuario.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		if('ok' == $Mensaje)
		{
			$Mensaje = 'El Men&uacute; fue ingresado exitosamente.';
		}
		if('error' == $Mensaje)
		{
			$Mensaje = 'Ocurri&oacute; un error en el ingreso, favor intentar nuevamente.<br />Se ha creado un registro del error para buscar una soluci&oacute;n';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Crear Men&uacute;',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Modelo que lista los menus principales
		$this->load->model('menu/listar_m', 'listar_p');
		//Listar_P = Listar Padres
		//Asigno a la variable el listado de menus principales (padres, id_menu_padre = 0)
		$Variables['Menu_Padre'] = $this->listar_p->padres();
		
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('menu/crear_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	/**
	 *Almacena en la base de datos el nuevo item del menu
	 *@param nada.
	 *@return redirige a: menu/crear/index/Mensaje[ok|error].
	*/
	public function menu()
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Etiqueta = $this->seguridad_m->mysql_seguro(
			$this->input->post('etiqueta')
		);
		$Enlace = $this->seguridad_m->mysql_seguro(
			$this->input->post('enlace')
		);
		$Id_Menu_Padre = $this->seguridad_m->mysql_seguro(
			$this->input->post('grupo')
		);
		
		//Carga del modelo que da ingreso al menu
		$this->load->model('menu/crear_m', 'crear');
		
		$Ingreso = $this->crear->menu($Etiqueta, $Enlace, $Id_Menu_Padre);
		
		if('ok' == $Ingreso)
		{
			header('location: /menu/crear/index/ok');
			exit();
		}
		else
		{
			header('location: /menu/crear/index/error');
			exit();
		}
		
	}
	
}

/* Fin del archivo */