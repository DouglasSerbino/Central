<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_gra extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($cod_plancha = '', $anho = '')
	{
		$this->ver_sesion_m->no_clientes();
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'REPORTE DE PLANCHAS FOTOPOLIMERAS',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		$buscar = 0;
		//Modelo que realiza la busqueda de los retazos.
		$this->load->model('planchas/planchas_m', 'planchas');
		if($cod_plancha == '' and $anho == '')
		{
			$anho = date("Y");
			$cod_plancha = 'todo';
		}

		$Codigo = '';
		$Variables['planchas'] = $this->planchas->buscar_planchas($Codigo);
		$Variables['grafico_plancha'] = $this->planchas->plancha_grafico($anho, $cod_plancha);
		$Variables['cod_plancha'] = $cod_plancha;
		$Variables['anho'] = $anho;
		//Cargamos la vista
		$this->load->view('planchas/plancha_gra_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/**
	 *Funcion que nos servira para almacenar los nuevos proveedores.
	*/
	public function agregar_proveedor()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables para evitar inyecciones
		$Nombre = $this->seguridad_m->mysql_seguro($this->input->post('nombre'));
		
		$this->load->model('planchas/planchas_m', 'planchas');
		$Agregar = $this->planchas->agregar_proveedor($Nombre);
		
		if('ok' == $Agregar)
		{
			header('location: /planchas/plancha_tipo/index/ok/'.$Nombre);
			exit();
		}
		else
		{
			header('location: /planchas/plancha_tipo/index/error/'.$Nombre);
			exit();
		}
	}
}

/* Fin del archivo */