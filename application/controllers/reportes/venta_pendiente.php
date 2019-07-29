<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Venta_pendiente extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($mes = '', $anho = '')
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$mes += 0;
		$anho += 0;
		if(0 == $mes or 0 == $anho)
		{
			show_404();
			exit();
		}
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Pedidos sin Facturar',
				'Mensaje' => ''
			);
			
			//Se carga la vista.
			$no = 'reporte';
			$Id_cliente = '';
			$this->load->model('pedidos/hoja_tiempo_consumo_m', 'consumo');
			$Variables['procesos'] = $this->consumo->buscar_pedidos_notificacion($Id_cliente, $no, $mes, $anho);
			$Variables['Id_Cliente'] = $Id_cliente;
			$Variables['mes'] = $mes;
			$Variables['anho'] = $anho;
			$this->load->view('reportes/venta_pendiente_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
	}
}

/* Fin del archivo */