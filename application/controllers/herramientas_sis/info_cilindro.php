<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_cilindro extends CI_Controller {
	
	/**
	 *Muestra los pedidos sin sap
	 *@return nada.
	*/
	public function index()
	{
		$this->ver_sesion_m->no_clientes();

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Informaci&oacute;n de Cilindros',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Declaramos las variables necesarias para mostrar la informacion.
		$menos = 0.5;
		$mas = 0.5;
		$mostrar_sticky = '';
		$stickyback = '';
		$polimero = '';
		$pulgas = '';
		$mostrar_div2 = '';
		$Mandar_sticky = 0;
		$Mandar_polimero = 0;
	
		$this->load->model('herramientas_sis/info_cilindro_m', 'info');
		
		//Verificamos si hay informacion por medio de POST.
		if($_POST)
		{
			$pulgas = $this->seguridad_m->mysql_seguro($this->input->post('pulgas'));

			//Variables que nos serviran para controlar el checkbox.
			$mostrar_sticky = $this->seguridad_m->mysql_seguro($this->input->post('stickyback'));
			$mostrar_div2 = $this->seguridad_m->mysql_seguro($this->input->post('mostrar_div2'));

			//Verificamos si lo que queremos es convertir un desarrollo.
			if(isset($_POST['mostrar_div2']))
			{
				$menos = $this->seguridad_m->mysql_seguro($this->input->post('menos2'));
				$mas = $this->seguridad_m->mysql_seguro($this->input->post('mas2'));
			}

			//Asignamos la informacion a las variables.
			$polimero = $this->seguridad_m->mysql_seguro($this->input->post('polimero'));
			$stickyback = $this->seguridad_m->mysql_seguro($this->input->post('stickyback'));

			$Mandar_sticky = $stickyback * 25.4;
			$Mandar_polimero = $polimero* 25.4;

			$Variables['Informacion_cilindro'] = $this->info->buscar_info_cilindro($mas, $menos, $pulgas,$Mandar_polimero,$Mandar_sticky);
		}
		
		$Variables['Cilindro_Desnudo'] = $this->info->info_cilindro_desnudo();
		$Variables['mas'] = $mas;
		$Variables['menos'] = $menos;
		$Variables['mostrar_div2'] = $mostrar_div2;
		$Variables['mostrar_sticky'] = $mostrar_sticky;
		$Variables['stickyback2'] = $stickyback;
		$Variables['polimero2'] = $polimero;
		$Variables['pulgas'] = $pulgas;
	
		$this->load->view('herramientas_sis/info_cilindro_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}
?>