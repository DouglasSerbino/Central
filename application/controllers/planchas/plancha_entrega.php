<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_entrega extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'ENTREGAS DE PLANCHAS PROGRAMADAS',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		//Modelo que realiza la busqueda de los retazos.
		$this->load->model('planchas/planchas_m', 'planchas');
		//Llamamos la funcion que nos muestra los trabajos en aprobacion.
		$Variables['Aprobacion'] = $this->planchas->pedidos_aprobacion();
		//Llamamos la funcion que nos muestra los pedidos que se entregaran.
		//Ordenados por fecha.
		$Variables['Pedidos'] = $this->planchas->entregas_fecha();
		
		//Cargamos la vista
		$this->load->view('planchas/plancha_entregas_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */