<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tablero extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index()
	{
		/*
		 *Determinamos los departamentos con acceso a la informacion del tablero.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Debemos evitar que un clienta vea esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Planificador Semanal',
			'Mensaje' => ''
		);
		
		//Listado de los usuarios
		$this->load->model('usuarios/listado_usuario_m', 'lusus');
		$Variables['Dpto_Usu'] = $this->lusus->departamento_usuario();
		
		
		//Listado de los trabajos de cada operador
		$this->load->model('carga/tablero_m', 'tablero');
		$Variables['Trabajos'] = $this->tablero->pedidos($Variables['Dpto_Usu']);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/tablero_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	
	public function asignar()
	{
		/*
		 *Determinamos los departamentos con acceso a la informacion del tablero.
		*/
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Evitamos que un clienta acceda a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		$Pedidos = $this->input->post('datos');
		$Pedidos = json_decode($Pedidos);
		
		
		//Listado de los trabajos de cada operador
		$this->load->model('carga/tablero_m', 'tablero');
		$Variables['Ajax'] = $this->tablero->asignar($Pedidos);
		
		$this->load->view('ajax_v', $Variables);
		
		
	}
	
	
}

/* Fin del archivo */