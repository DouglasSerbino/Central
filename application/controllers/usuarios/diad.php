<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Se llama dia D porque no quiero ponerle ni planilla ni salarios porque se vera
muy apetecible la url si alguien la intercepta.
*/
class Diad extends CI_Controller {
	
	
	public function index()
	{
		
		$Permisos = array('usuarios');
		$this->ver_sesion_m->acceso('autorizados', false, $Permisos);
		
		$this->ver_sesion_m->no_clientes();
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Planilla de Pago',
			'Mensaje' => ''
		);
		


		//Cargo el modelo que desactiva el usuario
		$this->load->model('usuarios/diad_m', 'diad');
		
		$Variables['Salarios'] = $this->diad->calcular();



		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('usuarios/diad_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');

	}
}

/* Fin del archivo */