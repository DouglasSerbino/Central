<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_online extends CI_Controller {
	
	public function index($Pagina = 1, $Inicio = 0, $Mensaje = '')
	{
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'USERS ONLINE',
			'Mensaje' => $Mensaje
		);
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Modelo que realiza la busqueda de los Clientes.
		$this->load->model('usuarios/usuarios_online_m', 'usu');
		$Variables['usuarios'] = $this->usu->usuarios();

		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('usuarios/usuarios_online_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
}

/* Fin del archivo */