<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Consumos extends CI_Controller {
	
	
	public function index($Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Consumos de Materia Prima',
			'Mensaje' => $Mensaje
		);


		$Variables['Pais'] = $this->seguridad_m->mysql_seguro($this->input->post('pais'));
		$Variables['Mes'] = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
		$Variables['Anho'] = $this->seguridad_m->mysql_seguro($this->input->post('anho'));

		if('' == $Variables['Pais'] || '' == $Variables['Mes'] || '' == $Variables['Anho'])
		{
			$Variables['Pais'] = 'sv';
			$Variables['Mes'] = date('m');
			$Variables['Anho'] = date('Y');
		}


		$this->load->model('inventario/consumos_m');
		$Variables['Consumos'] = $this->consumos_m->ver_consumos(
			$Variables['Pais'],
			$Variables['Mes'],
			$Variables['Anho']
		);


		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();

		$this->load->model('clientes/listado_paises_m', 'paisesc');
		$Variables['Paises_C'] = $this->paisesc->paises_cliente();

		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/consumos_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
}
/* Fin del archivo */