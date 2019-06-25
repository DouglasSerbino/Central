<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sin_quedan extends CI_Controller {

	public function index($Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Variables = array(
			'Titulo_Pagina' => 'Pedidos sin Quedan: ',
			'Pagina' => 'sin_quedan',
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
			)
		);
		
		
		$Anho += 0;
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		if('anual' != $Mes)
		{
			$Mes += 0;
			if(0 == $Mes)
			{
				$Mes = date('m');
			}
			elseif(10 > $Mes)
			{
				$Mes = '0'.$Mes;
			}
		}
		$Variables['Titulo_Pagina'] .= $Variables['Meses'][$Mes].' - '.$Anho;
		$Variables['Anho'] = $Anho;
		$Variables['Mes'] = $Mes;
		
		
		$this->load->model('conta/sin_quedan_m', 'quedan');
		$Variables['Sin_Quedan'] = $this->quedan->listado($Anho, $Mes);
		
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista.
		$this->load->view('conta/menu_v', $Variables);
		$this->load->view('conta/sin_quedan_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
	
	
	function quedan()
	{
		
		$Id_Pedido = $this->input->post('pedi') + 0;
		$Fecha = $this->seguridad_m->mysql_seguro(
			$this->input->post('fecha')
		);
		
		
		if(0 == $Id_Pedido || '' == $Fecha)
		{
			echo 'INFORMACION NO VALIDA';
			exit();
		}
		
		
		$this->load->model('conta/sin_quedan_m', 'quedan');
		echo $this->quedan->quedan($Id_Pedido, $Fecha);
		
	}
	
	
}
