<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Despacho extends CI_Controller {
	
	/**
	 *Podremos mostrar las notas de envio.
	*/
	public function index()
	{
		$this->ver_sesion_m->no_clientes();
		//Declaramos las variables
		$id_cliente = '';
		$fecha = '';
		$clientes = '';
		$Notas = '';
		if($_POST)
		{
			//Limpiamos la variable.
			$id_cliente = $this->seguridad_m->mysql_seguro(
				$this->input->post('id_cliente')
			);
			$fecha = $this->seguridad_m->mysql_seguro(
				$this->input->post('fecha_despacho')
			);
		}
		//Verificamos si el id del cliente ya tiene un codigo.
			if('' != $id_cliente)
			{
				//Cargamos el modelo para mostrar la informacion del cliente seleccionado.
				$this->load->model('notas/despacho_m', 'despacho');
				//Mostramos los clientes.
				$clientes = $this->despacho->clientes($id_cliente);
				//Mostramos todas las notas de envio.
				$Notas = $this->despacho->mostrar_notas($id_cliente, $fecha);
				
			}
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Notas de Envio',
				'Mensaje' => '',
				'Notas' => $Notas,
				'id_cliente' => $id_cliente,
				'fecha' => $fecha,
				'clientes' => $clientes
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('notas/despacho_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');

	}
}

/* Fin del archivo */