<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar extends CI_Controller {
	
	/**
	 *Formulario de modificacion de Menu
	 *@param string $Mensaje: Notificacion para retroalimentar al usuario.
	 *@return nada.
	*/
	public function index($Id_Menu = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion.
		//Id_menu aumenta en cero, lo que hace es comprobar que el valor recibido sea
		//un numero.
		$Id_Menu += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $Id_Menu)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Men&uacute;',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Modelo que lista los menus principales
		$this->load->model('menu/listar_m', 'listar_p');
		//Listar_P = Listar Padres
		//Asigno a la variable el listado de menus principales (padres, id_menu_padre = 0)
		$Variables['Menu_Padre'] = $this->listar_p->padres();
		
		
		//Modelo que lista los menus principales
		$this->load->model('menu/informacion_m', 'informacion');
		//Se recoge la informacion del menu solicitado.
		$Variables['Informacion'] = $this->informacion->menu($Id_Menu);
		//$Variables['Extraer_accesos'] = $this->informacion->Extraer_accesos($Id_Menu);
		
		//Modelo que lista los Departamentos
		$this->load->model('departamentos/listado_m', 'listar_d');
		//Asigno a la variable el listado de los departamentos activos
		$Variables['Departamentos'] = $this->listar_d->buscar_dptos('s');
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('menu/modificar_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	/**
	 *Modifica el menu en la base de datos
	 *@param int $Id_Menu.
	 *@return redirige a: menu/crear/index/Mensaje[ok|error].
	*/
	public function menu($Id_Menu)
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion.
		//Id_menu aumenta en cero, lo que hace es comprobar que el valor recibido sea
		//un numero.
		$Id_Menu += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $Id_Menu)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
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
		
		//Carga del modelo que modifica el menu
		$this->load->model('menu/modificar_m', 'modificar');
		//Se realiza la modificacion con los valores enviados
		$Modificar = $this->modificar->menu(
			$Id_Menu,
			$Etiqueta,
			$Enlace,
			$Id_Menu_Padre
		);
		
		if('ok' == $Modificar)
		{
			header('location: /menu/listado/index/mok');
			exit();
		}
		else
		{
			header('location: /menu/listado/index/error');
			exit();
		}
		
	}
	
	
	/*
	 *Crear los accesos a los usuarios.
	*/
	public function ensamblar()
	{
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_Menu = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_menu')
		);
		$Ruta = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta')
		);
		
		//Carga del modelo que guarda la ruta
		$this->load->model('menu/modificar_m', 'modi');
		$this->modi->Crear_acceso($Id_Menu, $Ruta);
		
		header('location: /menu/listado');
	}
	
}

/* Fin del archivo */