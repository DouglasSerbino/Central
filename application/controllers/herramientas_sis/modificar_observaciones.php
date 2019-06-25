<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modificar_observaciones extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function modificar()
	{
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		$observaciones = $this->seguridad_m->mysql_seguro(
			$this->input->post('observacion')
		);
		
		$id_usuario = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_usuario')
		);
		
		$id_pedido = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_pedido')
		);
		
		$id_observacion = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_observacion')
		);
		
		$this->load->model('herramientas_sis/modificar_observaciones_m', 'observa');
		$modificar_observaciones = $this->observa->modificar($observaciones, $id_usuario, $id_pedido, $id_observacion);
	}
}
?>