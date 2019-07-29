<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Score_card extends CI_Controller {
	
	/**
	 *Cumplimiento de pedidos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index($tipo = '', $anho = '', $mes = '', $cod_cliente = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$anho += 0;
		if(0 == $anho)
		{
			$anho = date('Y');
		}
		$Tipo = 'bsc_general';
		if($_POST)
		{
			$anho = $this->seguridad_m->mysql_seguro(
					$this->input->post('anho')
				);
			
			$anho += 0;
			if(0 == $anho)
			{
				$anho = date('Y');
			}
			$Tipo = $this->seguridad_m->mysql_seguro(
					$this->input->post('bsc_vista')
				);
		}
		//Anho que se realizara el reporte.
		$Variables['anho'] = $anho;
		$Variables['Tipo'] = $Tipo;
		
		//Llamamos el modelo que contiene la informacion.
		$this->load->model('reportes/score_card_m', 'score');
		
		//Llamamos a la funcion que nos permitira mostrar la informacion.
		$Variables['Score_card'] = $this->score->score_card($anho);

		//Cargamos la vista para el pie de pagina.
		$this->load->view('reportes/score_card_v', $Variables);
		
		
	}
}

/* Fin del archivo */