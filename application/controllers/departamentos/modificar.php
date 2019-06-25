<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar extends CI_Controller {
	
	/**
	 *Presenta el formulario para realizar modificaciones a un departamento asi
	 *como especificar el menu al cual tendran acceso los usuarios que pertenecen
	 *a dicho departamento.
	*/
	public function mostrar_datos($Codigo)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deben de tener acceso a esta información.
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos la variable.
		$Codigo = $this->seguridad_m->mysql_seguro($Codigo);
		
		//Variables de las vistas
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Departamento',
			'Pagina' => 'asignar_departamento',
			'Mensaje' => '',
			'Codigo' => $Codigo
		);
		
		
		//Obtencion de la informacion perteneciente al departamento seleccionado
		$this->load->model('departamentos/buscar_dpto', 'buscar');
		$Variables['Departamento'] = $this->buscar->departamento($Codigo);
		
		//Si no se ha encontrado un departamento valido, se muestra una pagina de error
		if('error' == $Variables['Departamento'])
		{
			show_404();
			exit();
		}
		
		$Variables['Departamento'] = $Variables['Departamento'][0];
		
		//Obtencion del listado de los Menus.
		$this->load->model('menu/listar_m', 'listar_m');
		$Variables['Menu'] = $this->listar_m->listado();
		$Variables['menu_activo'] = $this->listar_m->menu_departamento($Codigo);
		
		
		
		//Carga de las vistas
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('departamentos/modificar_v');
		$this->load->view('menu/listado_v');
		$this->load->view('pie_v');
		
	}
	
	
	/**
	 *Guarda en el sistema los cambios realizados al departamento.
	*/
	public function modificar_datos()
	{
		//Departamentos con acceso a este controlador
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no pueden modificar esta informacion
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_dpto = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_dpto')
		);
		$Codigo = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo')
		);
		$Departamento = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre_dpto')
		);
		$Tipo_inv= $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo_inv')
		);
		$Cant_mensual = $this->seguridad_m->mysql_seguro(
			$this->input->post('cant_mensual')
		);
		$Iniciales = $this->seguridad_m->mysql_seguro(
			$this->input->post('iniciales')
		);
		
		
		//Carga del modelo que nos permite modificar la informacion
		$this->load->model('departamentos/modificar_m', 'mod_m');
		
		//Llamamos el modelo para poder modificar los datos.
		$modificar_grupos= $this->mod_m->modificar_sql($Id_dpto, $Codigo, $Departamento, $Tipo_inv, $Cant_mensual, $Iniciales);
		
		
		//Redirigimos al usuario al listado de los departamentos
		header('location: /departamentos/listado_dptos/');
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
	
	/**
	 *Asignar un item del menu al departamento.
	*/
	public function asignar_menu($Id_dpto, $Id_menu)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso a los Menus.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que nos permite asignar los departamentos.
		$this->load->model('departamentos/modificar_m', 'mod_m');
		
		//Llamamos el modelo para poder activar los departamentos.
		$asignar_menu = $this->mod_m->activar_menu($Id_dpto, $Id_menu);
		
		
		//Se redirige al usuario al formulario de modificar departamento
		header('location: /departamentos/modificar/mostrar_datos/'.$Id_dpto.'#'.$Id_menu);
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
	
	/**
	 *Remueve un item del menu al departamento.
	*/
	public function desactivar_menu($Id_dpto, $Id_menu)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso a los Menus.
		$this->ver_sesion_m->no_clientes();
		
		//Carga del modelo que nos permite asignar los departamentos.
		$this->load->model('departamentos/modificar_m', 'mod_m');
		
		//Llamamos el modelo para poder activar los departamentos.
		$asignar_menu = $this->mod_m->eliminar_menu($Id_dpto, $Id_menu);
		
		//Se redirige al usuario al formulario de modificar departamento
		header('location: /departamentos/modificar/mostrar_datos/'.$Id_dpto.'#'.$Id_menu);
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
}