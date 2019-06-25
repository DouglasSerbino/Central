<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ventas_diarias extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index()
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'REPORTE DE VENTAS DIARIAS',
			'Mensaje' => ''
			);
		
		
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			$Fecha = '';
			if($_POST)
			{
				$Fecha = $this->seguridad_m->mysql_seguro(
					$this->input->post('fecha')
				);
			}
			$Variables['Fecha'] = $Fecha;
			

			//Cargamos el modelo que muestra los clientes.
			$this->load->model('ventas/ventas_diarias_m', 'ventas');
			//Llamamos el modelo para poder almacenar los datos.
			if($Fecha != '')
			{
				$Variables['mostrar_clientes'] = $this->ventas->mostrar_clientes($Fecha);
				//$Variables['mostrar_ventas'] = $this->ventas->ventas_diarias($Fecha);
			}
			

			$this->load->view('ventas/ventas_diarias_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
	}
}

/* Fin del archivo */