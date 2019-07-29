<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_planchas extends CI_Controller {
	
	
	public function index($Anho, $Mes, $Dia)
	{
		if($Dia < 10)
		{
			$Dia = '0'.$Dia;
		}
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' =>'');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Detalle, Consumo de Planchas',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Dia' => $Dia
		);
			
		//Informacion para crear el grafico para planchas.
		$this->load->model('planchas/reporte_planchas_m', 'planchas');
		$Variables['Planchas'] = $this->planchas->RepPlanchasDet($Anho, $Mes, $Dia);
			
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista.
		$this->load->view('reportes/detalle_planchas_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
}

/* Fin del archivo */