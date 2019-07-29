<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Obs_historial extends CI_Controller {
	
	
	public function index($Id_Pedido = '')
	{
		
		//Limpiamos las variables
		$Id_Pedido += 0;
		
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		//Llamamos al modelo para mostrar la informacion del proceso.
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		$this->load->model('pedidos/obs_historial_m', 'historia');
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Historial de Observaciones',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		
		//Extraemos toda la informacion del proceso
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido,
			true
		);
		
		if(0 == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		$Variables['Observaciones'] = $this->historia->observaciones(
			$Id_Pedido,
			$Variables['Info_Proceso']['id_proceso']
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('pedidos/obs_historial_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */