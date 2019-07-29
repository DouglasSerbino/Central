<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class V_buscar extends CI_Controller
{
	
	/**
	 *Formulario para busqueda de trabajos.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Buscar Trabajos',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		$this->load->view('pedidos/v_buscar_v');
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */