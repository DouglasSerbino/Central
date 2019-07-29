<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_facturacion extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($Id_cliente = '')
	{
		//Los clientes no deben de tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Pedidos sin Facturar',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$no = 'ok';
		$this->load->model('pedidos/hoja_tiempo_consumo_m', 'consumo');
		$Variables['procesos'] = $this->consumo->buscar_pedidos_notificacion($Id_cliente, $no);
		$Variables['Id_Cliente'] = $Id_cliente;
		//Se carga la vista.
		$this->load->view('facturacion/detalle_facturacion_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/*
		Funcion que servira para reportar la venta
	*/
	function facturar()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Info = array();
		$e = 0;
		$Info['factura'] = $_POST['factura'];
		$Info['id_cliente'] = $_POST['id_cliente_ver'];
		$Info['fecha_fac'] = $_POST['fecha_fac'];
		foreach($_POST as $Datos)
		{
			if($e > 2)
			{
				$temp = explode('--', $Datos);
				$Info['selected']['info'.$e]['pedido_sap'] = $temp[0];
				$Info['selected']['info'.$e]['id_pedido_sap'] = $temp[1];
			}
			$e++;
		}
		
		$this->load->model('facturacion/detalle_facturacion_m', 'facturar');
		$facturar_ped = $this->facturar->facturar_pedido($Info);
		
		if($facturar_ped != '')
		{
			header("location: /facturacion/detalle_facturacion/index/$facturar_ped");
		}
		else
		{
			header("location: /facturacion/detalle_facturacion/");
		}
	}
}

/* Fin del archivo */