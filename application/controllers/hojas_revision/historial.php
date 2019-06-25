<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Historial extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos las hojas de revision
	 *@return nada.
	*/
	public function index($Id_Pedido = 0)
	{
		
		//Los unicos que no podran acceder a esta pagina son los clientes.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Historial de Revisi&oacute;n',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		//Extraemos toda la informacion del proceso
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido,
			false//Necesito mostrarles a los grupos, que antes eran cliente solamente, sus pedidos viejitos
		);
		
		$this->load->model('hojas_revision/historial_m', 'hojas');
		$Variables['Historial'] = $this->hojas->listado($Id_Pedido);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('hojas_revision/historial_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	public function detalle($Id_Pedido = 0, $Id_Hoja = 0)
	{
		//Los unicos que no podran acceder a esta pagina son los clientes.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		$Id_Hoja += 0;
		if(0 == $Id_Pedido || 0 == $Id_Hoja)
		{
			show_404();
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Revisi&oacute;n',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		//Extraemos toda la informacion del proceso
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido,
			false//Necesito mostrarles a los grupos, que antes eran cliente solamente, sus pedidos viejitos
		);
		
		$this->load->model('hojas_revision/historial_m', 'hojas');
		$Variables['Detalle'] = $this->hojas->detalle($Id_Pedido, $Id_Hoja);
		

		$this->load->model('hojas_revision/nueva_revision_m', 'hojas_m');
		
		$Variables['Items_Revision'] = $this->hojas_m->items_completo(
			$Variables['Detalle']['id_dpto']
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		$this->load->view('hojas_revision/detalle_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
}

/* Fin del archivo */