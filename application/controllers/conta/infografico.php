<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Infografico extends CI_Controller {

	public function index($Anho = '', $Mes = '')
	{
		
		/*
			Dato importante:
			Si venta > 0 y sap = "": Sin Facturar.
			Si venta > 0 y sap != "" y factura = "": Sin quedan.
			Si venta > 0 y sap != "" y factura = !"" y orden = "": Sin pago.
		*/

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Variables = array(
			'Titulo_Pagina' => 'Infogr&aacute;fico Contable: ',
			'Pagina' => 'infografico',
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
		
		
		$this->load->model('conta/infografico_m', 'infografico');
		$Variables['Pagado'] = $this->infografico->pagado($Anho, $Mes);
		$Variables['Sin_Quedan'] = $this->infografico->sin_quedan($Anho, $Mes);
		$Variables['Sin_Pago'] = $this->infografico->sin_pago($Anho, $Mes);
		$Variables['Sin_Factura'] = $this->infografico->sin_factura($Anho, $Mes);
		
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		//Cargamos la vista.
		$this->load->view('conta/menu_v', $Variables);
		$this->load->view('conta/infografico_v', $Variables);
		
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
}
