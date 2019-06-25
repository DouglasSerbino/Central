<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Repor_reprocesos extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los procesos.
	 *@param string $Id_Proceso.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index($Id_Pedido = '', $Pagina = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Reprocesos',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Llamamos al modelo para mostrar la informacion del proceso.
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		//Extraemos toda la informacion del proceso
		$Variables['Proceso'] = $this->buscar_proc->busqueda_pedido($Id_Pedido);
		
		
		if('ver' == $Pagina)
		{
			//Llamamos al modelo para mostrar la informacion de los reprocesos.
			$this->load->model('pedidos/repor_reprocesos_m', 'reproc');
			//Extraemos toda la informacion del proceso
			$Variables['Reproceso'] = $this->reproc->Info_reproceso($Id_Pedido);
			$this->load->view('pedidos/ver_reprocesos_v', $Variables);
		}
		else
		{
			$this->load->view('pedidos/repor_reprocesos_v', $Variables);
		}
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
}

/* Fin del archivo */