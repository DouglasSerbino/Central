<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_agr extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($codigo = '', $Mensaje = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		
		if('ok' == $Mensaje)
		{
			$Mensaje = 'La informaci&oacute;n ha sido ingresada con &eacute;xito.';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'AGREGAR RETAZOS DE FOTOPOLIMEROS',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		//Modelo que realiza la busqueda de los click_tabs.
		$this->load->model('planchas/planchas_m', 'planchas');
		$Variables['planchas_especifica'] = $this->planchas->buscar_planchas($codigo);
		$Variables['codigo'] = $codigo;
		$codigo = '';
		$Variables['plancha_tipo'] = $this->planchas->plancha_tipo();
		//Cargamos la vista
		$this->load->view('planchas/plancha_agr_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/**
	 *Funcion que nos servira para almacenar los nuevos retazos.
	*/
	public function agregar_planchas()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables para evitar inyecciones
		$Cantidad = $this->seguridad_m->mysql_seguro($this->input->post('cantidad'));
		$Ancho = $this->seguridad_m->mysql_seguro($this->input->post('ancho'));
		$Tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		$Alto = $this->seguridad_m->mysql_seguro($this->input->post('alto'));
		$Codigo = $this->seguridad_m->mysql_seguro($this->input->post('codigo'));
		
		$this->load->model('planchas/planchas_m', 'planchas');
		$Almacenar = $this->planchas->guardar_retazos($Cantidad, $Ancho, $Tipo, $Alto, $Codigo);
		
		if('ok' == $Almacenar)
		{
			header('location: /planchas/plancha_agr/index/'.$Codigo.'/ok');
			exit();
		}
		else
		{
			header('location: /planchas/plancha_agr/index/'.$Codigo.'/error');
			exit();
		}
	}
}

/* Fin del archivo */