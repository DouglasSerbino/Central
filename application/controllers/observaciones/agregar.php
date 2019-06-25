<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar extends CI_Controller {
	
	/**
	 *Agregar observaciones para un pedido en la base de datos.
	 *@return nada.
	*/
	public function index()
	{
		
		//$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido = $this->input->post('obs_pedido');
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		$Redireccion = $this->seguridad_m->mysql_seguro(
			$this->input->post('obs_redireccion')
		);
		
		$Observacion = $this->seguridad_m->mysql_seguro(
			$this->input->post('obs_observacion')
		);
		
		$Req_Aprobacion = 'n';
		if('on' == $this->input->post('apro'))
		{
			$Req_Aprobacion = 's';
		}
		
		if('' == $Observacion)
		{
			header('location: '.$Redireccion);
			exit();
		}
		
		
		
		//Modelo para guardar observaciones
		$this->load->model('observaciones/guardar_m', 'obs_guardar');
		//Guardar observacion
		$this->obs_guardar->index(
			$Id_Pedido,
			$Observacion,
			date('Y-m-d H:i:s'),
			$Req_Aprobacion
		);
		
		header('location: '.$Redireccion);
		
		
	}
	
	
}

/* Fin del archivo */