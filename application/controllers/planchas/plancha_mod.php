<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_mod extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($codigo = 0)
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$codigo += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $codigo)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'MODIFICAR RETAZOS DE FOTOPOLIMERO',
			'Mensaje' => ''
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		//Modelo que realiza la busqueda de los click_tabs.
		$this->load->model('planchas/planchas_m', 'planchas');
		$ordenar = 'nombre_tipo';
		
		$Variables['info_retazos'] = $this->planchas->mostrar_retazos($codigo);
		$Variables['codigo'] = $codigo;
		$codigo = '';
		$Variables['plancha_tipo'] = $this->planchas->plancha_tipo();
		//Cargamos la vista
		$this->load->view('planchas/plancha_mod_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	/**
	 *Funcion que nos servira para almacenar los nuevos retazos.
	*/
	public function modificar_planchas()
	{
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables para evitar inyecciones
		$Cantidad = $this->seguridad_m->mysql_seguro($this->input->post('cantidad'));
		$Ancho = $this->seguridad_m->mysql_seguro($this->input->post('ancho'));
		$Tipo = $this->seguridad_m->mysql_seguro($this->input->post('tipo'));
		$Alto = $this->seguridad_m->mysql_seguro($this->input->post('alto'));
		$Codigo = $this->seguridad_m->mysql_seguro($this->input->post('codigo'));
		$Codigo_plancha = $this->seguridad_m->mysql_seguro($this->input->post('cod_plancha'));
		
		$this->load->model('planchas/planchas_m', 'planchas');
		$Modificar = $this->planchas->modificar_retazos($Cantidad, $Ancho, $Tipo, $Alto, $Codigo);
		
		if('ok' == $Modificar)
		{
			header('location: /planchas/consultar_material/index/'.$Codigo_plancha.'/nombre_tipo');
			exit();
		}
		else
		{
			header('location: /planchas/consultar_material/index/'.$Codigo_plancha.'/nombre_tipo');
			exit();
		}
	}
}

/* Fin del archivo */