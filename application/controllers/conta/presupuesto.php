<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presupuesto extends CI_Controller {
	
	//********************************************************
	public function index($Anho = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		if('' == $Anho)
		{
			$Anho = date('Y');
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Asignaci&oacute;n de Presupuesto',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Meses_v' => $this->Meses_m->MandarMesesAbre()
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Obtener las lineas ya almacenadas
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$Variables['Lineas'] = $this->mc_lineas->listar();
		
		
		//Obtener los presupuestos
		$this->load->model('conta/presupuesto_m', 'mc_presupuesto');
		$Variables['Presupuesto'] = $this->mc_presupuesto->asignados($Anho);
		
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('conta/presupuesto_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
	public function modificar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$Id_MC_Linea = $this->input->post('id_mc_linea');
		$Id_MC_Linea += 0;
		
		$Anho = $this->input->post('anho');
		$Anho += 0;
		
		if(0 == $Id_MC_Linea || 0 == $Anho)
		{
			show_404();
			exit();
		}
		
		
		$Meses = array(
			'01', '02', '03', '04', '05', '06',
			'07', '08', '09', '10', '11', '12'
		);
		
		$Presupuesto = array();
		foreach($Meses as $Mes)
		{
			$Presupuesto['mes_'.$Mes] = $this->input->post('mc_pres_'.$Mes);
		}
		
		
		$this->load->model('conta/presupuesto_m', 'mc_presupuesto');
		$this->mc_presupuesto->modificar($Id_MC_Linea, $Anho, $Presupuesto);
		
		header('location: /conta/presupuesto');
		
		
	}
	
}

/* Fin del archivo */