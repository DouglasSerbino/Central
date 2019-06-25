<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lsol_pedido extends CI_Controller {
	
	/**
	 *Mostraremos la informacion de todos los pedidos.
	 *@param string $Puesto.
	 *@param string $Mensaje.
	 *@return nada.
	*/
	public function index()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Solicitudes',
			'Mensaje' => ''
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		$this->load->model('herramientas_sis/lsol_pedido_m', 'solicitud');
		$Variables['Solicitudes'] = $this->solicitud->info_solicitud();
		$Variables['Solicitudes_Material'] = $this->solicitud->info_solicitud_material();
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('herramientas_sis/lsol_pedido_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	function eliminar($Id_Material = '', $Id_solicitud = '')
	{
		
		$this->load->model('herramientas_sis/lsol_pedido_m', 'solicitud');
		$Eliminar = $this->solicitud->eliminar_solicitud($Id_Material, $Id_solicitud);
		
		if('ok' == $Eliminar)
		{
			header('location: /herramientas_sis/lsol_pedido/index');
			exit();
		}
		else
		{
			header('location: /herramientas_sis/lsol_pedido/index');
			exit();
		}
	}
	
}

/* Fin del archivo */