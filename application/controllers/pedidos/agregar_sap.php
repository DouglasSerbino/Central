<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agregar_sap extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($cod_cliente = '', $proceso = '')
	{
		$this->ver_sesion_m->no_clientes();
		
		
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Agregar Pedido Sap',
				'Mensaje' => '',
				'cod_cliente' => $cod_cliente,
				'proceso' => $proceso
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$Variables['procesos'] = array();
			if($_POST)
			{
				$cod_cliente = $this->seguridad_m->mysql_seguro(
					$this->input->post('codigo_cliente')
				);
				
				$proceso = $this->seguridad_m->mysql_seguro(
					$this->input->post('proceso')
				);
			}
			
			if('' != $cod_cliente and '' != $proceso)
			{
				$this->load->model('pedidos/agregar_sap_m', 'sap');
				$Variables['procesos'] = $this->sap->buscar_procesos($cod_cliente, $proceso);
			}
			$this->load->view('pedidos/agregar_sap_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
	}
	
	/*
		Funcion que servira para reportar la venta
	*/
	function agregar_pedido_sap($cod_cliente = '', $proceso = '')
	{
		$this->ver_sesion_m->no_clientes();
		
		$id_cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_cliente')
		);
		
		$id_pedido = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_pedido')
		);
		
		$pedido_sap = $this->seguridad_m->mysql_seguro(
			$this->input->post('pedido_sap')
		);
		
		$venta = $this->seguridad_m->mysql_seguro(
			$this->input->post('venta')
		);
		
		$fecha = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha')
		);
		$ordenes = $this->seguridad_m->mysql_seguro(
			$this->input->post('ordenes')
		); 
		
		$this->load->model('pedidos/agregar_sap_m', 'sap');
		$agregar_sap = $this->sap->agregar_pedido_sap($id_cliente, $id_pedido, $pedido_sap, $venta, $ordenes, $fecha);
		
		if($agregar_sap != '')
		{
			header("location: /pedidos/agregar_sap/index/".$cod_cliente.'/'.$proceso);
		}
		else
		{
			header("location: /pedidos/agregar_sap/");
		}
	}
}

/* Fin del archivo */