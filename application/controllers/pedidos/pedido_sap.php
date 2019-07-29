<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_sap extends CI_Controller {
	
	/**
	 *Modificacion de los datos del pedido sap.
	 *@param string $Id_Pedido.
	 *@return 'ok'.
	*/
	public function modificar($Id_Pedido)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Super validacion
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		//Extraemos toda la informacion del proceso
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		
		if(0 == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		$Pedido_SAP = $this->seguridad_m->mysql_seguro(
			$this->input->post('pedido_sap')
		);
		$Venta = $this->seguridad_m->mysql_seguro(
			$this->input->post('venta')
		);
		$Orden = '';
		
		//Modelo para modificar la info del pedido sap
		$this->load->model('pedidos/pedido_sap_m', 'pedido_sap');
		$this->pedido_sap->modificar(
			$Id_Pedido,
			$Pedido_SAP,
			$Venta,
			$Orden
		);
		
		$Variables['Ajax'] = 'ok';
		
		$this->load->view('ajax_v', $Variables);
		
	}
	
}

/* Fin del archivo */