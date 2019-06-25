<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acumulado_ventas extends CI_Controller {
	
	/**
	 *Reporte de status.
	 *@return nada.
	*/
	public function index($Anho = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Acumulado de Ventas',
			'Mensaje' => ''
		);
		
		
		$Variables['Meses'] = array(
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
		);
		
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		$Variables['Anho'] = $Anho;
		
		
		$this->load->model('reportes/acumulado_ventas_m', 'acumulado');
		//Acumulado de ventas para el anho actual
		$Variables['Actual'] = $this->acumulado->ventas($Anho);
		
		$Variables['Anterior'] = $this->acumulado->ventas(($Anho -1));
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('reportes/acumulado_ventas_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	
}

/* Fin del archivo */