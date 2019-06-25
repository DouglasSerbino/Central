<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregas extends CI_Controller {
	
	/**
	 *Tablero de entregas para este dia y entregas atrasadas.
	*/
	public function index()
	{
		/*
		 *Verificaremos si un cliente quiere acceder a esta pagina
		 *Si es asi no puede porque no debe tener acceso a esta información.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Tablero de Entregas',
			'Mensaje' => ''
		);
		
		//Cargamos el modelo
		$this->load->model('carga/entregas_m', 'entrega');
		$Variables['Pedidos'] = $this->entrega->pedidos();
		
		
		//Listado de los materiales recibidos y solicitados
		$this->load->model('pedidos/materiales_m', 'materiales');
		$Variables['Mat_Solicitado'] = $this->materiales->solicitados('s');
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/entregas_v');
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */