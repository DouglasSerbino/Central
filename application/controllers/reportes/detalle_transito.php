<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_transito extends CI_Controller {
	
	/**
	 *Muestra el inventario de materiales y sus existencias.
	 *@param string $Codigo;
	 *@param string $Proveedor;
	 *@param string $Cantidad;
	 *@param string $Equipo;
	 *@return nada.
	*/
	public function index($Id_inventario_material='')
	{
		
		//Validacion
		$Id_inventario_material += 0;
		
		
		if(0 == $Id_inventario_material)
		{
			show_404();
			exit();
		}
		$this->ver_sesion_m->no_clientes();

		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Pedidos Realizados',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		$todos = 'si';
		$orden = '';
		//Infomacion de los pedidos en transito.
		$this->load->model('herramientas_sis/info_transito_m', 'info');
		$Variables['Informacion_transito'] = $this->info->buscar_info_transito($Id_inventario_material, $todos, $orden);
		$Variables['Informacion_material_detalle'] = $this->info->info_adicional($Id_inventario_material);
		
		//Informacion del material correspondiente al pedido en transito.
		$this->load->model('herramientas_sis/agregar_ped_tran_m', 'ped_tran');
		$Variables['info_material'] = $this->ped_tran->mostrar_material($Id_inventario_material);
		
		$this->load->view('herramientas_sis/info_transito_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */