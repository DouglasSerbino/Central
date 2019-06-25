<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear extends CI_Controller {
	
	/**
	 *Formulario de creacion de Ruta de trabajo.
	 *@param string $Mensaje: Notificacion para retroalimentar al usuario.
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Sistemas' => '');
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
			'Titulo_Pagina' => 'Crear Ruta de Trabajo',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Modelo que lista los Departamentos
		$this->load->model('departamentos/listado_m', 'listar_d');
		//Listar_d = Listar Departamentos
		//Asigno a la variable el listado de los departamentos activos
		$Variables['Departamentos'] = $this->listar_d->buscar_dptos('s');
		
		
		//Modelo que lista los grupos de la corporacion
		$this->load->model('grupo/listar_grupos_m', 'listar_g');
		//Listar_g = Listar Grupos
		//Asigno a la variable el listado de los grupos activos
		$Variables['Grupos'] = $this->listar_g->listado('s');
		
		
		//Modelo que lista los grupos de la corporacion
		$this->load->model('ruta/ruta_existe_m', 'existe');
		//Asigno a la variable el listado de los grupos activos
		$Variables['Ruta_Grupo'] = $this->existe->grupos();
		
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('ruta/crear_v', $Variables);
		
		
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
		
		$Permitido = array('Sistemas' => '');
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
	
	/**
	 *Guarda la ruta creada por el usuario.
	 *@param nada.
	 *@return nada.
	*/
	public function ensamblar()
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_Grupo = $this->seguridad_m->mysql_seguro(
			$this->input->post('sl_grupos')
		);
		$Ruta = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta')
		);
		
		//Carga del modelo que guarda la ruta
		$this->load->model('ruta/guardar_m', 'guardar');
		$Guardar_Ruta = $this->guardar->buscar_dptos($Id_Grupo, $Ruta);
		
		if('ok' != $Guardar_Ruta)
		{
			$Guardar_Ruta = 'error';
		}
		
		header('location: /ruta/crear/index/'.$Guardar_Ruta);
		
	}
	
}

/* Fin del archivo */