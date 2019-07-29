<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_cilindro_keep extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Informaci&oacute;n de Cilindros Keep',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Declaramos las variables necesarias para mostrar la informacion.
		$pulgas_desa = 0;
		$menos = 0;
		$mas = 0;
		$mostrar_sticky = '';
		$mostrar_div = '';
		$Total = 0;
		
		//Verificamos si hay informacion por medio de POST.
		if($_POST)
		{
			//Asignamos la informacion a las variables.
			$pulgas_desa = $this->seguridad_m->mysql_seguro($this->input->post('pulgas_desa'));
			
			//Verificamos si debemos de cambiar el valor de la tolerancia
			if(isset($_POST['mostrar_div']))
			{
				$menos = $this->seguridad_m->mysql_seguro($this->input->post('menos'));
				$mas = $this->seguridad_m->mysql_seguro($this->input->post('mas'));
			}
			
			//Variables que nos serviran para controlar el checkbox.
			$mostrar_div = $this->seguridad_m->mysql_seguro($this->input->post('mostrar_div'));
			$mostrar_sticky = $this->seguridad_m->mysql_seguro($this->input->post('stickyback'));
			
		}
		
		$this->load->model('herramientas_sis/info_cilindro_keep_m', 'info');
		$Variables['Informacion_cilindro'] = $this->info->buscar_info_cilindro($pulgas_desa, $mas, $menos);
		
		$this->load->model('herramientas_sis/info_cilindro_m', 'info2');
		$Variables['Cilindro_Desnudo'] = $this->info2->info_cilindro_desnudo();
		//Asignamos las variables para poder acceder desde la vista.
		
		$Variables['mandar_pulgas'] = $pulgas_desa;
		$Variables['pulgas_desa'] = $pulgas_desa;
		$Variables['mas'] = $mas;
		$Variables['menos'] = $menos;
		$Variables['mostrar_div'] = $mostrar_div;
		$Variables['mostrar_sticky'] = $mostrar_sticky;
		
		$this->load->view('herramientas_sis/info_cilindro_keep_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}
?>