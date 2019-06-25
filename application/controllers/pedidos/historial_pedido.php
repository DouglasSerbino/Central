<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial_pedido extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los procesos.
	 *@param string $Id_Proceso.
	 *@return nada.
	*/
	public function index($Id_Proceso = 0)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Validacion
		$Id_Proceso += 0;
		
		if(0 == $Id_Proceso)
		{
			show_404();
			exit();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Historial de Pedido',
			'Mensaje' => '',
			'Redir' => '/pedidos/historial_pedido/index/'.$Id_Proceso,
			'Id_Proceso' => $Id_Proceso
		);
		
		
		//Informacion del Proceso
		$this->load->model('procesos/buscar_proceso_m', 'info');
		//Solicito la informacion completa
		$Variables['Info_Proceso'] = $this->info->id_proceso($Id_Proceso);
		
		if('' == $Variables['Info_Proceso'])
		{
			show_404();
			exit();
		}
		
		
		//Necesito la miniatura
		$Consulta = '
			select url
			from proceso_imagenes
			where id_proceso = "'.$Id_Proceso.'"
			order by id_proceso_imagenes desc
			limit 0, 1
		';
		$Resultado = $this->db->query($Consulta);

		$Variables['Miniatura'] = '';
		if(1 == $Resultado->num_rows())
		{
			$Variables['Miniatura'] = $Resultado->row_array();
			$Variables['Miniatura'] = $Variables['Miniatura']['url'];
		}
		

		//Pedidos relacionados con este proceso
		$this->load->model('pedidos/lista_pedidos_m', 'lpedidos');
		$Variables['Pedidos'] = $this->lpedidos->listar($Id_Proceso);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$Variables['num_cajas'] = 3;
		
		//Listado de pedidos
		$this->load->view('pedidos/administrar_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
}

/* Fin del archivo */