<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_tiempo_consumo extends CI_Controller {
	
	
	public function index($Id_cliente = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Confirmar Producci&oacute;n',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Se carga la vista.
		$no = 'no';
		$this->load->model('pedidos/hoja_tiempo_consumo_m', 'consumo');
		$Variables['procesos'] = $this->consumo->buscar_pedidos_notificacion($Id_cliente, $no);
		$Variables['Id_Cliente'] = $Id_cliente;
		$this->load->view('pedidos/hoja_tiempo_consumo_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	/*
		Funcion que servira para reportar la venta
	*/
	function reportar_tiempo()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$this->load->model('pedidos/hoja_tiempo_consumo_m', 'consumo');
		$reportar = $this->consumo->reportar_venta();
		
		if($reportar != '')
		{
			header("location: /pedidos/hoja_tiempo_consumo/index/$reportar");
		}
		else
		{
			header("location: /pedidos/hoja_tiempo_consumo/");
		}
		
	}
	
}

/* Fin del archivo */