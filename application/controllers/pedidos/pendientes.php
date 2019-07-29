<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendientes extends CI_Controller {
	
	/**
	 *Busca todos los pedidos que este usuario tiene: pendientes, en proceso o
	 *en aprobacion. Y los lista por fecha de entrega y luego id_pedido.
	 *@param string $Estado Mostrar todos, procesando, en aprobacion o atrazados?.
	 *@return nada.
	*/
	public function index($Estado = 'todos')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza.
		$Estado = $this->seguridad_m->mysql_seguro($Estado);
		
		if('procesando' != $Estado && 'aprobacion' != $Estado && 'atrazados' != $Estado)
		{
			$Estado = 'todos';
		}
		
		
		$this->session->set_userdata(array('estado' => $Estado));
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Trabajos Pendientes',
			'Mensaje' => '',
			'Estado' => $Estado
		);
		//Se carga el encabezado de pagina
		
		$this->load->view('encabezado_v', $Variables);
		
		
		//Pedidos que estan en el puesto de este usuario
		$this->load->model('pedidos/pendientes_m', 'pendientes');
		$Variables['Pedidos'] = $this->pendientes->listado($Estado);
		
		
		//Listado de trabajos pendientes
		$this->load->view('pedidos/pendientes_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */