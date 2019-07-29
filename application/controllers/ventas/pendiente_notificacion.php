<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pendiente_notificacion extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($Id_cliente = '')
	{
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'PEDIDOS SIN NOTIFICAR',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Se carga la vista.
		$no = 'no';
		$this->load->model('pedidos/hoja_tiempo_consumo_m', 'consumo');
		$Variables['procesos'] = $this->consumo->buscar_pedidos_notificacion($Id_cliente, $no);
		$Variables['Id_Cliente'] = $Id_cliente;
		$this->load->view('ventas/pendiente_notificacion_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */