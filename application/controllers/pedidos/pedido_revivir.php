<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_revivir extends CI_Controller {
	/**
	 *Eliminar un pedido en especifico.
	*/
	public function index($id_pedido, $id_proceso, $fecha)
	{
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$id_pedido = $this->seguridad_m->mysql_seguro($id_pedido);
		$id_proceso = $this->seguridad_m->mysql_seguro($id_proceso);
		$fecha = $this->seguridad_m->mysql_seguro($fecha);
		//Cargamos el modelo para poder almacenar la informacion.
		$this->load->model('pedidos/pedido_revivir_m', 'ped_rev');
		
		$Revivir = $this->ped_rev->revivir_pedido($id_pedido, $id_proceso, $fecha);
		
		if($Revivir == 'ok')
		{
			header("location: /pedidos/administrar/info/$id_proceso");
		}
		else
		{
			header("location: /pedidos/administrar/info/$id_proceso/error");
		}
	}
}

/* Fin del archivo */