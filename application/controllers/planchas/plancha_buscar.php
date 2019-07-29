<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_buscar extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index()
	{
		
		$this->ver_sesion_m->no_clientes();
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'FORMULARIO DE BUSQUEDA',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		$buscar = 0;
		//Modelo que realiza la busqueda de los retazos.
		$this->load->model('planchas/planchas_m', 'planchas');
		if($_POST)
		{
			$Ancho = $this->seguridad_m->mysql_seguro($this->input->post('ancho'));
			$Alto = $this->seguridad_m->mysql_seguro($this->input->post('alto'));
			$Codigo = $this->seguridad_m->mysql_seguro($this->input->post('codigo'));
			$buscar = 1;
			$Alto2 = $Alto+10;
			$Ancho2 = $Ancho+10;
			$Variables['retazos'] = $this->planchas->buscar_retazos($Codigo, $Alto, $Ancho, $Alto2, $Ancho2);
		}
		else
		{
			$Ancho = '';
			$Alto = '';
			$Codigo = '';
		}
		$Variables['Codigo'] = $Codigo;
		$Variables['buscar'] = $buscar;
		$Variables['Alto'] = $Alto;
		$Variables['Ancho'] = $Ancho;
		
		if($Codigo != '')
		{
			$Variables['plancha_especifica'] = $this->planchas->buscar_planchas($Codigo);
		}
		//Igualo el codigo a nada para poder mostrar el tipo de plancha.
		$Codigo = '';
		$Variables['planchas'] = $this->planchas->buscar_planchas($Codigo);
		
		//$Variables['plancha_tipo'] = $this->planchas->plancha_tipo();
		//Cargamos la vista
		$this->load->view('planchas/plancha_buscar_v', $Variables);
		
		
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