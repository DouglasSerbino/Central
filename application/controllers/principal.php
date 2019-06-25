<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function index($Cliente = '')
	{
		
		if(28 == $this->session->userdata('id_dpto'))
		{
			header('location: /conta/infografico');
			exit();
		}
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Bienvenido',
			'Mensaje' => ''
		);
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->model('balance/objetivos_m', 'objetivo');
		
		$Variables['Def_Objetivos'] = $this->objetivo->objetivos();
		
		
		//Cargamos la vista.
		$this->load->view('principal_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
}
