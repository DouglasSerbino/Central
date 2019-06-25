<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos_sin_sap extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Pedidos sin Sap',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		$Fecha_Inicio = date('Y-m-d', strtotime('- 70 days', strtotime(date('d-m-Y'))));
		
		$Fecha_Fin = date('Y-m-d'); //date('Y-m-d', strtotime('+ 30 days', strtotime(date('d-m-Y'))));
		
		$Variables['Fecha_Inicio'] = $Fecha_Inicio;
		$Variables['Fecha_Fin'] = $Fecha_Fin;
		
		//Infomacion de los pedidos en transito.
		$this->load->model('herramientas_sis/pedidos_sin_sap_m', 'pedidos_sap');
		$Variables['pedidos_sap'] = $this->pedidos_sap->pedidos_sin_sap($Fecha_Inicio, $Fecha_Fin);
		
		$this->load->view('herramientas_sis/pedidos_sin_sap_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */