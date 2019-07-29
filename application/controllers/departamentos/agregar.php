<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar extends CI_Controller {
	
	/**
	 *Mostrar formulario para agregar un departamento
	*/
	public function index($Agregado = '')
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso a los Menus.
		$this->ver_sesion_m->no_clientes();
		
		//Variables de las vistas
		$Variables = array(
			'Titulo_Pagina' => 'Agregar Departamentos',
			'Mensaje' => ''
		);
		
		//Si existe una confirmacion de agregado, se confirma al usuario
		if($Agregado == 'ok')
		{
			$Variables['Mensaje'] = 'Departamento ingresado exitosamente';
		}
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista.
		$this->load->view('/departamentos/agregar_v');
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	/**
	 *Funcion que nos permitira agregar los departamentos.
	*/
	public function agregar_datos()
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso a los Menus.
		$this->ver_sesion_m->no_clientes();
		
		
		//Limpieza de variables
		$Codigo = $this->seguridad_m->mysql_seguro(
			$this->input->post('codigo')
		);
		$Nombre_dpto = $this->seguridad_m->mysql_seguro(
			$this->input->post('nombre_dpto')
		);
		$Tipo_inv = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo_inv')
		);
		$cant_mensual = $this->seguridad_m->mysql_seguro(
				    $this->input->post('cant_mensual')
		);
		$Iniciales = $this->seguridad_m->mysql_seguro(
			$this->input->post('iniciales')
		);
		
		
		//Carga del modelo que permite ingresar los datos.
		$this->load->model('departamentos/agregar_m', 'agre_m');
		
		//Llamamos el modelo para poder almacenar los datos.
		$agregar_dptos= $this->agre_m->guardar_datos($Codigo,$Nombre_dpto,$Tipo_inv,$cant_mensual,$Iniciales);
		
		
		
		if('ok' == $agregar_dptos)
		{
			//Si el grupo se guardo con exito
			//Enviamos a la pagina de agregar por si se quiere agregar
			//un nuevo grupo.
			header('location: /departamentos/agregar/index/ok');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{
			//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /departamentos/agregar');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
	
}