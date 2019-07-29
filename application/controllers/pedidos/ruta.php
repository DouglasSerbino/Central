<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruta extends CI_Controller {
	
	/**
	 *Busca la ruta del trabajo que va hacia atras desde el puesto que lo solicita.
	 *@param string $Id_Pedido.
	 *@param string $Id_Peus.
	 *@return nada.
	*/
	public function rechazo($Id_Pedido, $Id_Peus)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		$Id_Peus += 0;
		
		if(0 == $Id_Pedido || 0 == $Id_Peus)
		{
			show_404();
			exit();
		}
		
		
		$this->load->model('ruta/buscar_ruta_m', 'buscar_rut');
		$Variables['Ajax'] = $this->buscar_rut->hacia_atras($Id_Pedido, $Id_Peus);
		
		$this->load->view('ajax_v', $Variables);
		
	}
	
}

/* Fin del archivo */