<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_envio extends CI_Controller {
	
	/**
	 *Podremos mostrar las notas de envio.
	*/
	public function index()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos la variable.
			$id_cliente = $this->seguridad_m->mysql_seguro(
				$this->input->post('id_cliente')
			);
			$fecha = $this->seguridad_m->mysql_seguro(
				$this->input->post('fecha_despacho')
			);
			$cajas = $this->seguridad_m->mysql_seguro(
				$this->input->post('cajas')
			);
			$pedidos = '';
			//Le asignamos todos los pedidos a un array para poder manipular la informacion.
			for($i = 0; $i < $cajas; $i++)
			{
				$pedidos[] = $this->input->post("nota_$i");
			}
			//Cargamos el modelo para mostrar los clientes.
			$this->load->model('notas/despacho_m', 'despacho');
			$clientes = $this->despacho->clientes($id_cliente);
			
			//Cargamos el modelo para mostrar los materiales recibidos solicitados y otros.
			$this->load->model('notas/nota_envio_m', 'nota_m');
			//Llamamos la funcion para mostrar la informacion.
			$especificacion = $this->nota_m->mostrar_especificacion($pedidos);
			
			
			//Variables necesarias en la pagina.
			$Variables = array(
				'Titulo_Pagina' => 'Nota de Envio',
				'Mensaje' => '',
				'Clientes' => $clientes,
				'id_cliente' => $id_cliente,
				'cajas' => $cajas,
				'especificacion' => $especificacion,
				'pedidos' => $pedidos
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('notas/nota_envio_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');

	}
}

/* Fin del archivo */