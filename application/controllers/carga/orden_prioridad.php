<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orden_prioridad extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index()
	{
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$this->load->model('carga/orden_prioridad_m', 'ordenar');
		if($this->ordenar->ordena())
		{
			echo 'ok';
		}
		else
		{
			echo 'no';
		}
		
	}
}

/* Fin del archivo */