<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Curiosos extends CI_Controller {
	
	/**
	 *Pagina que muestra el listado de los menus del usuario.
	 *Tiene opciones para crear los click_tabs.
	*/
	public function index()
	{
		
		$Permitido = array('Sistemas' => '', 'Plani' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Pedidos Curiosos',
			'Mensaje' => ''
		);
		
		
		//Modelo que realiza la busqueda de los click_tabs.
		$this->load->model('herramientas_sis/curiosos_m', 'curioso');
		$Variables['Curiosos'] = $this->curioso->rarosos('');
		
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		
		$this->load->view('herramientas_sis/curiosos_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */