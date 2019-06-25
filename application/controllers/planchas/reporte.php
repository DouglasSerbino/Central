<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte extends CI_Controller {
	
	
	public function index($Id_Cliente = 0, $Anho = '', $Mes = '', $Id_Proceso = 0)
	{
		
		$Id_Cliente += 0;
		$Id_Proceso += 0;
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		if('' == $Mes)
		{
			$Mes = date('m');
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Lecturas de Planchas',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Id_Cliente' => $Id_Cliente,
			'Reporte' => 'fecha'
		);
		
		if(0 < $Id_Proceso)
		{
			$Variables['Reporte'] = 'proceso';
		}
		
		
		$Variables['meses_v'] = array(
			"01" => "Enero",
			"02" => "Febrero",
			"03" => "Marzo",
			"04" => "Abril",
			"05" => "Mayo",
			"06" => "Junio",
			"07" => "Julio",
			"08" => "Agosto",
			"09" => "Septiembre",
			"10" => "Octubre",
			"11" => "Noviembre",
			"12" => "Diciembre"
		);
		
		
		$this->load->model('planchas/lecturas_m', 'lecturas');
		$Variables['Referencias'] = $this->lecturas->referencias_Reporte($Id_Cliente);
		$Variables['Compensacion'] = $this->lecturas->compensacion();
		$Variables['Plancha'] = $this->lecturas->planchas();
		$Variables['Sistema'] = $this->lecturas->sistema();
		$Variables['Altura'] = $this->lecturas->altura();
		$Variables['Trama'] = $this->lecturas->trama();
		$Variables['Lineaje'] = $this->lecturas->lineaje();
		
		$Variables['Reales'] = $this->lecturas->reales(
			$this->session->userdata('id_grupo'), $Id_Cliente, $Anho, $Mes, $Id_Proceso
		);
		
		
		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		//Busqueda del listado
		$Variables['Clientes'] = $this->clientes->mostrar_clientes();
		
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista
		$this->load->view('planchas/reporte_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
}

/* Fin del archivo */