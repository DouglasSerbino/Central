<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sol_pedido extends CI_Controller {

	public function index($id_material)
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$this->load->model('herramientas_sis/sol_pedido_m', 'solicitud');
		
		$agregar_sol = $this->solicitud->guardar_solicitud($id_material);
		
		header('location: /reportes/reporte_consumo');
	}
}