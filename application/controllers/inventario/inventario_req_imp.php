<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventario_req_imp extends CI_Controller {
	
	/**
	 *Permite imprimir la boleta para hacer la requisicion.
	 *@param string $Codigo: Codigo de material que se quiere mostrar.
	 *@return nada.
	*/
	public function index($Codigo)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables		
		$Id_requisicion = $this->seguridad_m->mysql_seguro($Codigo);
		
		//Carga del modelo que nos permite mostrar la informacion.
		$this->load->model('inventario/inventario_req_m', 'inven_req');
			
		//Llamamos la funcion para poder mostrar la informacion.
		$Mostrar_requisicion = $this->inven_req->numero_requisicion($Id_requisicion);
		$Mostrar_materiales = $this->inven_req->materiales_requisados($Id_requisicion);
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => '',
			'Mensaje' => '',
			'Requisicion' => $Mostrar_requisicion,
			'Materiales' => $Mostrar_materiales
		);
		
		//Se carga el encabezado de pagina
		//$this->load->view('encabezado_v', $Variables);
		//Cargamos la vista para poder modificar la informacion.
		$this->load->view('inventario/inventario_req_imp_v', $Variables);
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}
/* Fin del archivo */