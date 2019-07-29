<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entregas_cliente extends CI_Controller {

	public function index()
	{
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Bienvenido',
			'Mensaje' => ''
		);
	
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['Mes'] = date('m');
		$Variables['Cliente'] = '';
		if($_POST)
		{
			$Variables['Cliente'] = $this->seguridad_m->mysql_seguro($this->input->post('cod_cliente'));
			$Variables['Mes'] = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
		}

		$this->load->model('/herramientas_sis/pruebas_m', 'prueba');
		$Variables['Prueba'] = $this->prueba->pruebas($Variables['Cliente'], $Variables['Mes']);
		
		$Variables['Meses'] = array(
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
		);
		
		//Cargamos la vista.
		$this->load->view('/herramientas_sis/entregas_cliente_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
	}
}
