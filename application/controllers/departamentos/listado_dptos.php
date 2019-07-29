<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_dptos extends CI_Controller {
	
	/**
	 *Listado de los departamentos agregados al sistema.
	 *En el mismo listado aparecen tanto los activos como inactivos.
	*/
	public function index($Modificado = '')
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		//Los clientes no deben de tener acceso a los Menus.
		$this->ver_sesion_m->no_clientes();
		
		//Variables para las vistas
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Departamentos',
			'Mensaje' => ''
		);
		
		
		//Si se recibe una notificacion de la modificacion correcta de un departamento
		if($Modificado == 'ok')
		{
			//Se informa al usuario
			$Variables['Mensaje'] = 'Departamento modificado exitosamente';
		}
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Carga del modelo que permite mostrar la informacion.
		$this->load->model('departamentos/listado_m', 'list_m');
		
		//Llamamos el modelo para establecer una conexion.
		$Departamentos = $this->list_m->buscar_dptos();
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables_Vista = array(
			'Departamentos' => $Departamentos
		);
		
		//Cargamos la vista.
		$this->load->view('/departamentos/listado_v', $Variables_Vista);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
}