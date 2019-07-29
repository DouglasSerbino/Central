<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nueva_revision extends CI_Controller {
	
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
			'Titulo_Pagina' => 'Hoja de Revisi&oacute;n',
			'Mensaje' => '',
			'Id_Pedido' => $Id_Pedido
		);
		
		
		$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
		//Extraemos toda la informacion del proceso
		$Variables['Info_Proceso'] = $this->buscar_proc->busqueda_pedido(
			$Id_Pedido,
			false//Necesito mostrarles a los grupos, que antes eran cliente solamente, sus pedidos viejitos
		);
		

		$this->load->model('hojas_revision/nueva_revision_m', 'revision');
		$Variables['Items_Revision'] = $this->revision->items_completo(
			$this->session->userdata('id_dpto')
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Hoja de revision
		$this->load->view('hojas_revision/nueva_revision_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');

	}
	
	
	//*********************************
	public function revisar($Id_Pedido = 0)
	{
		
		//Los unicos que no podran acceder a esta pagina son los clientes.
		$this->ver_sesion_m->no_clientes();
		
		$Id_Pedido += 0;
		if(0 == $Id_Pedido)
		{
			show_404();
		}
		
		
		//Almacenamiento de la hoja de revision
		$this->load->model('hojas_revision/nueva_revision_m', 'hojas_m');
		
		$Items_Revision = $this->hojas_m->items_completo(
			$this->session->userdata('id_dpto')
		);

		$this->hojas_m->revision($Id_Pedido, $Items_Revision);
		
		header('location: /pedidos/detalle_activo/index/'.$Id_Pedido);
		
	}


	//********************************
	function listado()
	{

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Hojas de Revisi&oacute;n',
			'Mensaje' => ''
		);



		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Hoja de revision
		$this->load->view('hojas_revision/listado_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');

	}


	//********************************
	function editar($Id_Dpto = 0)
	{

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Editar Hojas de Revisi&oacute;n',
			'Mensaje' => '',
			'Id_Dpto' => $Id_Dpto
		);


		
		$this->load->model('hojas_revision/nueva_revision_m', 'revision');
		
		$Variables['Items_Revision'] = $this->revision->items_completo($Id_Dpto);


		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		//Hoja de revision
		$this->load->view('hojas_revision/editar_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');

	}


	//************************************
	function agregar()
	{

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		

		$Item = $this->seguridad_m->mysql_seguro(
			$this->input->post('item')
		);
		
		$Nivel = $this->seguridad_m->mysql_seguro(
			$this->input->post('nivel')
		);
		$Nivel += 0;
		
		$Id_Dpto = $this->seguridad_m->mysql_seguro(
			$this->input->post('dpto')
		);
		$Id_Dpto += 0;


		$this->load->model('hojas_revision/nueva_revision_m', 'revision');
		$this->revision->agregar($Item, $Nivel, $Id_Dpto);

		echo 'ok';

	}


	//************************************
	function eliminar($Tipo = '', $Item = 0, $Id_Dpto = 0)
	{

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		$Tipo = $this->seguridad_m->mysql_seguro($Tipo);
		$Item += 0;
		$Id_Dpto += 0;

		
		$this->load->model('hojas_revision/nueva_revision_m', 'revision');
		$this->revision->eliminar($Tipo, $Item);

		header('location: /hojas_revision/nueva_revision/editar/'.$Id_Dpto);

	}


	//************************************
	function modificar()
	{

		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		

		$Item = $this->seguridad_m->mysql_seguro(
			$this->input->post('item')
		);
		
		$Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('tipo')
		);
		
		$Id_Item = $this->seguridad_m->mysql_seguro(
			$this->input->post('id')
		);
		$Id_Item += 0;


		$this->load->model('hojas_revision/nueva_revision_m', 'revision');
		$this->revision->modificar($Item, $Tipo, $Id_Item);

		echo 'ok';

	}
	
}

/* Fin del archivo */