<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nota_ver extends CI_Controller {
	
	/**
	 *Podremos mostrar las notas de envio.
	*/
	public function index($id_nota_env = '')
	{
		$this->ver_sesion_m->no_clientes();
			
			//Cargamos el modelo que nos permitira mostrar la informacion.
			$this->load->model('notas/nota_ver_m', 'nota_ver');
			//Mostramos el correlativo de la nota de envio.
			$nota_envio = $this->nota_ver->mostrar_correlativo($id_nota_env);
			//Funcion que nos permite mostrar el nombre y el Id del cliente.
			$nota_cliente = $this->nota_ver->mostrar_cliente($id_nota_env);
			//Funcion que nos permitira mostrar todos los id de los pedidos.
			$nota_pedido = $this->nota_ver->mostrar_pedidos($id_nota_env);
			//Funcion que nos permitira mostrar todos los materiales,
			//que selecciono el usuario, para realizar la nota de envio.
			$nota_materiales = $this->nota_ver->mostrar_materiales($nota_pedido, $id_nota_env);
			
			//Cargamos el modelo que nos pernmite mostrar la informacion de lo materiales
			//que pertenecen a este pedido.
			$this->load->model('notas/nota_envio_m', 'nota_m');
			$especificacion = $this->nota_m->mostrar_especificacion($nota_pedido);
			
			
			//Variables necesarias en la pagina.
			$Variables = array(
				'nota_envio' => $nota_envio,
				'id_nota_env' => $id_nota_env,
				'nota_cliente' => $nota_cliente,
				'nota_pedido' => $nota_pedido,
				'nota_materiales' => $nota_materiales,
				'especificacion' => $especificacion
			);
			
			//Se carga la vista.
			$this->load->view('notas/nota_ver_v', $Variables);
	}
}

/* Fin del archivo */