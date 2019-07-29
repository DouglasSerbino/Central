<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Flujos extends CI_Controller {

	public function index($Rango = 'anual')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Variables = array(
			'Titulo_Pagina' => 'PRESUPUESTO DE EFECTIVO',
			'Pagina' => 'sin_factura',
			'Mensaje' => '',
			'Meses' => array(
				'anual' => 'Anual',
				'01' => 'Enero',
				'02' => 'Febrero',
				'03' => 'Marzo',
				'04' => 'Abril',
				'05' => 'Mayo',
				'06' => 'Junio',
				'07' => 'Julio',
				'08' => 'Agosto',
				'09' => 'Septiembre',
				'10' => 'Octubre',
				'11' => 'Noviembre',
				'12' => 'Diciembre'
			),
			'Rango' => $Rango
		);
		
		
		$this->load->model('conta/flujos_m', 'flujos');
		$Variables['Detalle'] = $this->flujos->listado($Rango);
		//print_r($Variables['Detalle']);
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista.
		$this->load->view('conta/flujos_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
}
