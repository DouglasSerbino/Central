<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_consumo extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function modificar($cantidad, $id_material, $id_pedido)
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		$this->load->model('herramientas_sis/modificar_consumo_m', 'consumo');
		$modificar_consumo = $this->consumo->modificar_mat($cantidad, $id_material, $id_pedido);
		header('location: /pedidos/pedido_detalle/index/'.$id_pedido);
	}
	
	
	public function cambio_estado($estado, $id_material, $id_pedido)
	{
		
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		
		$this->load->model('herramientas_sis/modificar_consumo_m', 'consumo');
		$Cambiar_estado = $this->consumo->cambio_estado($estado, $id_material, $id_pedido);
		header('location: /pedidos/pedido_detalle/index/'.$id_pedido);
	}
	
	
}
?>