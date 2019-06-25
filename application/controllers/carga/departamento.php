<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departamento extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index()
	{
		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Carga por Departamentos',
			'Mensaje' => ''
		);
		
		
		//Listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'lusus');
		$Variables['Dpto_Usu'] = $this->lusus->departamento_usuario();
		
		
		//Listado de los trabajos de cada operador
		$this->load->model('carga/carga_dpto_m', 'equipo');
		$Variables['Trabajos'] = $this->equipo->pedidos($Variables['Dpto_Usu']);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/departamento_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	
	public function asignar()
	{
		/*
		 *Estas sentencias de codigo serviran para
		 *especificar que departamentos tienen acceso a este controlador
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque esta informacion es confidencial.
		*/
		
		$this->ver_sesion_m->no_clientes();
		
		$Pedidos = $this->input->post('datos');
		//Convertimos la informacion a JSON
		$Pedidos = json_decode($Pedidos);
		
		//Listado de los trabajos de cada operador
		$this->load->model('carga/carga_dpto_m', 'equipo');
		$Variables['Ajax'] = $this->equipo->asignar($Pedidos);
		//Mandamos los datos por medio de AJAX
		$this->load->view('ajax_v', $Variables);
		
		
	}
}

/* Fin del archivo */