<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Buscar_procesos extends CI_Controller {
	
	/**
	 *Pagina que permite ingresar los procesos
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'El proceso se modifico exitosamente';
		}
		elseif($Mensaje == 'existe')
		{
			$Mensaje = 'No es posible asignar ese n&uacute;mero de Proceso al Cliente. Ya existe otro trabajo con esa informaci&oacute;n.';
		}
		else
		{
			$Mensaje = '';
		}
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Proceso',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('procesos/buscar_procesos_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');	
	}
}

/* Fin del archivo */