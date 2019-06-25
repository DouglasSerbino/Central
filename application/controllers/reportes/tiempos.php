<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tiempos extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte de Tiempos',
			'Mensaje' => ''
		);
		
		
		$Fecha_Inicio = date('Y-m-d');
		$Fecha_Fin = date('Y-m-d');
		$Id_Cliente = '';
		$Tipo_Tiempo = 'n';
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
			$Tipo_Tiempo = $this->seguridad_m->mysql_seguro(
				$this->input->post('tipo_tiempo')
			);
		}
		
		$Variables['Fecha_Inicio'] = $Fecha_Inicio;
		$Variables['Fecha_Fin'] = $Fecha_Fin;
		$Variables['Id_Cliente'] = $Id_Cliente;
		$Variables['Tipo_Tiempo'] = $Tipo_Tiempo;
		
		//Cargamos el modelo que muestra los clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		//Llamamos el modelo para poder almacenar los datos.
		$Variables['mostrar_clientes'] = $this->buscar_cli->mostrar_clientes();
		
		if('' != $Id_Cliente)
		{
			//Cargamos el modelo que muestra los pedidos.
			$this->load->model('reportes/tiempos_m', 'tiempos');
			//Llamamos el modelo para poder almacenar los datos.
			$Variables['pedidos'] = $this->tiempos->pedidos(
				$Id_Cliente,
				$Fecha_Inicio,
				$Fecha_Fin,
				$Tipo_Tiempo
			);
			
			$Variables['productos'] = $this->tiempos->info_productos(
				$Id_Cliente,
				$Fecha_Inicio,
				$Fecha_Fin,
				$Tipo_Tiempo
			);
			
			//Cargamos el modelo que muestra la ruta de trabajo.
			$this->load->model('reportes/reprocesos_det_m', 'reprocesos');
			//Llamamos el modelo para poder almacenar los datos.
			$Variables['informacion_usuarios'] = $this->reprocesos->info_general($Variables['pedidos']);
			$Variables['informacion_materiales'] = $this->reprocesos->info_materiales($Variables['pedidos']);
		}
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->view('reportes/tiempos_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
}
/* Fin del archivo */