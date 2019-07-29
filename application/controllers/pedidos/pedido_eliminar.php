<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_eliminar extends CI_Controller {
	/**
	 *Eliminar un pedido en especifico.
	*/
	public function index($id_pedido, $id_proceso)
	{
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$id_pedido = $this->seguridad_m->mysql_seguro($id_pedido);
		
		//Cargamos el modelo para poder almacenar la informacion.
		$this->load->model('pedidos/pedido_eliminar_m', 'ped_eli');
		
		$Eliminar = $this->ped_eli->eliminar_pedido($id_pedido);
		
		if($Eliminar == 'ok')
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