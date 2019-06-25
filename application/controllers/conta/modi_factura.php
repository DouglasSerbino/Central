<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modi_Factura extends CI_Controller {

	public function index($Id_Pedido = 0, $Anho = '', $Mes = '', $Pagina = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Factura',
			'Pagina' => 'infografico',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Pagina' => $Pagina
		);
		
		
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
			exit();
		}
		
		$Variables['Id_Pedido'] = $Id_Pedido;
		
		
		$this->load->model('conta/modi_factura_m', 'factura');
		$Variables['Factura'] = $this->factura->info($Id_Pedido);
		
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos la vista.
		$this->load->view('conta/modi_factura_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}

	
	function actualizar($Id_Pedido = 0, $Anho = '', $Mes = '', $Pagina = '')
	{

		$Id_Pedido += 0;

		$Factura = $this->input->post('factura');
		$Fecha = $this->input->post('fecha');
		$Venta = $this->input->post('venta');

		$this->load->model('conta/modi_factura_m', 'factura');
		$this->factura->actualizar($Id_Pedido, $Factura, $Venta, $Fecha);

		header('location: /conta/'.$Pagina.'/index/'.$Anho.'/'.$Mes);

	}


}
