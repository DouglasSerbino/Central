<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempo_consumo extends CI_Controller {
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($Id_pedido = 0)
	{
		$this->ver_sesion_m->no_clientes();
		
		$Id_pedido += 0;
		
		if(0 == $Id_pedido)
		{
			show_404();
			exit();
		}
		
		$Variables['id_pedido'] = $Id_pedido;
		
		//Cargamos el modelo encargado de mostrar los proceso finalizados.
		$this->load->model('pedidos/tiempo_consumo_m', 'tiempo_con');
		$Variables['informacion_procesos'] = $this->tiempo_con->informacion_proceso($Id_pedido);
		$Variables['informacion_general'] = $this->tiempo_con->info_general($Id_pedido, $Variables['informacion_procesos'][0]['id_proceso']);
		$Variables['informacion_sap'] = $this->tiempo_con->info_sap($Id_pedido);
		if(isset($Variables['informacion_general']['orden']))
		{
			$Variables['informacion_sap'][0]['orden'] = $Variables['informacion_general']['orden'];
			$Variables['informacion_general'] = $Variables['informacion_general']['tiem'];
		}
		$Variables['informacion_materiales'] = $this->tiempo_con->info_materiales($Id_pedido);
		
		
		
		//Cargamos la vista
		$this->load->view('pedidos/tiempo_consumo_v', $Variables);
	}
}

/* Fin del archivo */