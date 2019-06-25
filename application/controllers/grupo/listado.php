<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado extends CI_Controller {
	
	public function index()
	{
		/*Solamente Sistema y Gerencia tendran acceso a ver el listado de los grupos.*/
		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		//Los clientes nunca deberan poder ver esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		$Variables = array(
			'Titulo_Pagina' => 'Administrar Grupos',
			'Mensaje' => ''
		);
		
		//Cargamos la vista para el encabezado.
		$this->load->view('encabezado_v', $Variables);
	
		//Modelo que realiza la busqueda de los grupos.
		$this->load->model('grupo/listar_grupos_m', 'listar_g');
		//listar_g = Listar Grupos
		//Obtencion del listado de los grupos pertenecientes a CentralGraphics.
		//El listado se asigna a una variable para ser usado en la vista.
		$Grupos = $this->listar_g->listado();
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables_Vista = array(
			'Grupos' => $Grupos
		);
	
		//Cargamos la vista.
		$this->load->view('/grupo/listado_v',$Variables_Vista);
		
		//Cargamos la vista para el pie de pagina.
		$this->load->view('pie_v');
		
	}
}

/* Fin del archivo */