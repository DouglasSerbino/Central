<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eliminar extends CI_Controller {
	
	
	public function index($Id_Proceso = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Sistemas' => '', 'Plani' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Proceso += 0;
		if(0 == $Id_Proceso)
		{
			show_404();
		}
		
		
		$this->load->model('procesos/eliminar_proceso_m', 'borrar');
		$Pedidos_Eliminar = $this->borrar->desaparecer($Id_Proceso);
		
		
		$this->load->model('pedidos/pedido_eliminar_m', 'ped_eli');
		foreach($Pedidos_Eliminar as $Id_Pedido)
		{
			$Eliminar = $this->ped_eli->eliminar_pedido($Id_Pedido['id_pedido']);
		}
		
		
		header('location: /pedidos/buscar/modificar');
		
	}
}

/* Fin del archivo */