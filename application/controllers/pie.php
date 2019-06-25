<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pie extends CI_Controller {
	
	/**
	 *Busca la informacion que se muestra en el pie de pagina.
	*/
	public function index()
	{
    
		//Rechazos
		$this->load->model('carga/trabajos_usuario_m', 'trab_usu');
		$Total = $this->trab_usu->total_rechazos(
			date('Y'),
			date('m'),
			'',
			$this->session->userdata('id_usuario')
		);
		
		$Variables['Ajax'] = 0;
		if(isset($Total[$this->session->userdata('id_usuario')]))
		{
			$Variables['Ajax'] = $Total[$this->session->userdata('id_usuario')];
		}
		
		
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', '410' => '');
		if(isset($Permitido[$this->session->userdata('codigo')]))
		{
			//Trabajos curiosos
			$this->load->model('herramientas_sis/curiosos_m', 'curioso');
			$Variables['Ajax'] .= ','.$this->curioso->rarosos('totales');
			
			//Trabajos en Venta
			$this->load->model('pedidos/preingreso_m', 'pendientes');
			$Variables['Ajax'] .= ','.$this->pendientes->total('Ventas');
			
			//Trabajos en Plani
			$Variables['Ajax'] .= ','.$this->pendientes->total('Plani');
			
			//Tareas
			$Variables['Ajax'] .= ',0';
		}
		else
		{
			$Variables['Ajax'] .= ',0,0,0,0';
		}
		
		$this->load->view('ajax_v', $Variables);
		
	}


	function temporizador()
	{
		$Iniciado = $this->session->userdata('temporizador');
		//echo strtotime('now').' - '.$Iniciado.'--';
		echo strtotime('now') - $Iniciado;
	}

}