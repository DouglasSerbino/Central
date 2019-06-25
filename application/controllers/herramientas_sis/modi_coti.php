<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modi_coti extends CI_Controller {
	
	public function index()
	{
		$this->ver_sesion_m->no_clientes();
		
		
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Cotizaciones Modificadas',
				'Mensaje' => ''
			);
			
			$this->load->view('encabezado_v', $Variables);
		
			$this->load->model('herramientas_sis/listado_coti_m', 'coti');
			$Variables['Listado'] = $this->coti->listado();
			
			$this->load->view('herramientas_sis/listado_coti_v', $Variables);
		
			$this->load->view('pie_v');
	}
	
	function eliminar($Id_Pedido)
	{
		$this->ver_sesion_m->no_clientes();
		
		$this->load->model('herramientas_sis/listado_coti_m', 'coti');
		$eliminar = $this->coti->eliminar($Id_Pedido);
		
		header("location: /herramientas_sis/modi_coti");
	}
}

/* Fin del archivo */