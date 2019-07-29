<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_consumo extends CI_Controller {
	
	/**
	 *Reportar el consumo de material del usuario y el pedido.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function reportar($Id_Pedido)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		$this->load->model('pedidos/pedido_consumo_m', 'consumo');
		$this->consumo->reportar($Id_Pedido);
		
		$Variables['Ajax'] = 'ok';
		$this->load->view('ajax_v', $Variables);
		
	}
	
}

/* Fin del archivo */