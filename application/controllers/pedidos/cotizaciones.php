<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cotizaciones extends CI_Controller {
	
	
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Cotizaciones',
			'Mensaje' => ''
		);
		
		
		
		//Solicito las ultimas 100 cotizaciones
		$Consulta = '
			select id_pedido_adjuntos, url as revisado, mime_type as mensaje,
			nombre_adjunto as fecha
			from pedido_adjuntos
			where tipo_adjunto = "cliente"
			order by id_pedido_adjuntos desc
			limit 0, 100
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Cotizaciones'] = array();
		foreach($Resultado->result_array() as $Fila)
		{
			$Variables['Cotizaciones'][] = $Fila;
		}


		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Listado de pedidos
		$this->load->view('pedidos/cotizaciones_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
}

/* Fin del archivo */