<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Revision_inventario extends CI_Controller {

	public function index($Cliente = '')
	{
		
		
		$Variables = array(
			'Titulo_Pagina' => 'Revisi&oacute;n de Inventarios',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
		
		
		$Variables['Fecha_Inicio'] = date('Y-m-d');
		$Variables['Fecha_Fin'] = date('Y-m-d');
		$Variables['Id_Cliente'] = '';
		$Variables['sap'] = '';
		if($_POST)
		{
			$Variables['Id_Cliente'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('cliente')
			);
			$Variables['Fecha_Inicio'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('fecha_inicio')
			);
			$Variables['Fecha_Fin'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('fecha_fin')
			);
			$Variables['sap'] = $this->seguridad_m->mysql_seguro(
				$this->input->post('sap')
			);
		}

		
		//Cargamos el modelo que muestra los clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		//Llamamos el modelo para poder almacenar los datos.
		$Variables['mostrar_clientes'] = $this->buscar_cli->mostrar_clientes();
			
		$Variables['Listado'] = array();
		if('' != $Variables['Id_Cliente'])
		{
			$this->load->model('herramientas_sis/revision_inventario_m', 'revision');
			$Variables['Listado'] = $this->revision->Listado($Variables['Id_Cliente'], $Variables['Fecha_Inicio'], $Variables['Fecha_Fin'], $Variables['sap']);
		}
		
		
		//Cargamos la vista.
		$this->load->view('herramientas_sis/revision_inventario_v', $Variables);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
	}
}
