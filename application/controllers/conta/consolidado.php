<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consolidado extends CI_Controller {
	
	//********************************************************
	public function index($Anho = '', $Mes_Inicio = 'anual', $Mes_Fin = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		$Mes_Inicio = $this->seguridad_m->mysql_seguro($Mes_Inicio);
		$Mes_Fin = $this->seguridad_m->mysql_seguro($Mes_Fin);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte Consolidado',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes_Inicio' => $Mes_Inicio,
			'Mes_Fin' => $Mes_Fin,
			'Meses_v' => $this->Meses_m->MandarMesesCompletos()
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Obtener las lineas ya almacenadas
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$Variables['Lineas'] = $this->mc_lineas->listar();
		
		
		//Totales por mes
		$this->load->model('conta/consolidado_m', 'mc_consolidado');
		$Variables['Consolidado'] = $this->mc_consolidado->consolidado(
			$Anho,
			$Mes_Inicio,
			$Mes_Fin
		);
		
		
		//Obtener los presupuestos
		$this->load->model('conta/presupuesto_m', 'mc_presupuesto');
		$Variables['Presupuesto'] = $this->mc_presupuesto->asignados(
			$Anho,
			$Mes_Inicio,
			$Mes_Fin
		);
		
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('conta/consolidado_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
}

/* Fin del archivo */