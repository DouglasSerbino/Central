<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_proy extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index($id_proyecto = '')
	{
		$this->ver_sesion_m->no_clientes();
		$id_proyecto+0;
		if(0 == $id_proyecto)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Proyecto',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		
		$this->load->model('procesos/detalle_proy_m', 'ciclo');
		$Variables['Proyecto'] = $this->ciclo->info_proyecto($id_proyecto);
		
		//Asignamos las variables para poder acceder desde la vista.
		
		$this->load->view('procesos/detalle_proy_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}
?>