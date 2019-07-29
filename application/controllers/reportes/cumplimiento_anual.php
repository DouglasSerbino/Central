<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cumplimiento_anual extends CI_Controller {
	
	/**
	 *Cumplimiento de pedidos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Cumplimiento Anual',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos el modelo encargado de mostrar la informacion de los procesos.
		$this->load->model('reportes/cumplimiento_m', 'cumplimiento');
		$Variables['cumplimiento_anual'] = $this->cumplimiento->cumplimiento_anual();
		
		//Cargamos la vista.
		$this->load->view('reportes/cumplimiento_anual_v', $Variables);

		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
}

/* Fin del archivo */