<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Envio extends CI_Controller {
	
	
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Cliente = 0;
		$Inicio = date('d-m-Y');
		$Fin = date('d-m-Y');
		
		if('' != $this->input->post('inicio'))
		{
			$Id_Cliente = $this->input->post('cliente');
			$Id_Cliente += 0;
			$Inicio = $this->input->post('inicio');
			$Fin = $this->input->post('fin');
		}
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Notas de envio',
			'Mensaje' => '',
			'Id_Cliente' => $Id_Cliente,
			'Inicio' => $Inicio,
			'Fin' => $Fin
		);
		
		
		
		//Listado de los clientes
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		$Variables['Clientes'] = $this->buscar_cli->mostrar_clientes(
			'id_cliente, codigo_cliente, nombre'
		);
		
		
		
		if(0 != $Id_Cliente)
		{
			//$this->fechas_m->fecha_ymd_dmy($Inicio)
			$this->load->model('reportes/envio_m', 'envio');
			$Variables['Envios'] = $this->envio->listado(
				$Id_Cliente,
				$this->fechas_m->fecha_dmy_ymd($Inicio),
				$this->fechas_m->fecha_dmy_ymd($Fin)
			);
		}
		
		
		
		$this->load->view('encabezado_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('reportes/envio_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */