<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ventas_semanal extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'REPORTE SEMANAL DE VENTAS',
			'Mensaje' => ''
			);
		
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			
			$Fecha_Inicio = date('Y-m-d');
			$Fecha_Fin = date('Y-m-d');
			$Id_Cliente = '';
			$Cambio = 'n';
			if($_POST)
			{
				$Id_Cliente = $this->seguridad_m->mysql_seguro(
					$this->input->post('cliente')
				);
				$Fecha_Inicio= $this->seguridad_m->mysql_seguro(
					$this->input->post('fecha_inicio')
				);
				$Fecha_Fin = $this->seguridad_m->mysql_seguro(
					$this->input->post('fecha_fin')
				);
				$Cambio = $this->seguridad_m->mysql_seguro(
					$this->input->post('cambio')
				);
			}
			
			if('s' != $Cambio)
			{
				$Cambio = 'n';
			}
			
			$Variables['Fecha_Inicio'] = $Fecha_Inicio;
			$Variables['Fecha_Fin'] = $Fecha_Fin;
			$Variables['Id_Cliente'] = $Id_Cliente;
			$Variables['Cambio'] = $Cambio;
			
			//Cargamos el modelo que muestra los clientes.
			$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
			//Llamamos el modelo para poder almacenar los datos.
			$Variables['mostrar_clientes'] = $this->buscar_cli->mostrar_clientes();
			
			
			//Cargamos el modelo que muestra los pedidos.
			$this->load->model('reportes/ventas_semanal_m', 'ventas');
			//Llamamos el modelo para poder almacenar los datos.
			$pedidos = $this->ventas->pedidos(
				$Id_Cliente,
				$Fecha_Inicio,
				$Fecha_Fin,
				$Cambio
			);
			$Variables['pedidos'] = $pedidos;
			$Variables['productos'] = $this->ventas->info_productos(
				$Id_Cliente,
				$Fecha_Inicio,
				$Fecha_Fin,
				$Cambio
			);
			
			//Cargamos el modelo que muestra la ruta de trabajo.
			$this->load->model('reportes/reprocesos_det_m', 'reprocesos');
			//Llamamos el modelo para poder almacenar los datos.
			$Variables['informacion_usuarios'] = $this->reprocesos->info_general($pedidos);
			$Variables['informacion_materiales'] = $this->reprocesos->info_materiales($pedidos);
		
			$this->load->view('reportes/ventas_semanal_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
	}
}
/* Fin del archivo */