<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hoja_produccion extends CI_Controller {
	
	/**
	 *Ingreso del preingreso y todos sus elementos en la base de datos.
	 *@param string $Id_Pedido.
	 *@return nada.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Hoja de Producci&oacute;n',
			'Mensaje' => ''
		);
		
		$Variables['cod_cliente'] = '';
		$Variables['informacion_procesos'] = '';
		if($_POST)
		{
			$cod_cliente = $this->seguridad_m->mysql_seguro(
				$this->input->post('cod_cliente')
			);
			$proceso = $this->seguridad_m->mysql_seguro(
				$this->input->post('proceso')
			);
			
			if('' != $cod_cliente and '' != $proceso)
			{
				//Cargamos el modelo encargado de mostrar la informacion de los procesos.
				$this->load->model('pedidos/hoja_produccion_m', 'produccion');
				$Variables['informacion_procesos'] = $this->produccion->buscar_procesos($cod_cliente, $proceso);
			}
			$Variables['cod_cliente'] = $cod_cliente;
		}
		//print_r($Variables['informacion_procesos']);
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);

		$this->load->view('pedidos/hoja_produccion_v', $Variables);

		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
		
	}
	

	
}

/* Fin del archivo */