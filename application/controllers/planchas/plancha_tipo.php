<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plancha_tipo extends CI_Controller {
	
	/**
	 *Pagina que mostrara la informacion de las planchas.
	*/
	public function index($Mensaje = '', $Nombre = '')
	{
		
		$this->ver_sesion_m->no_clientes();
		if('ok' == $Mensaje)
		{
			$Mensaje = 'El Tipo de Plancha <u>'.$Nombre.'</u> ha sido ingresado con &eacute;xito.';
		}
		elseif('error' == $Mensaje)
		{
			$Mensaje = '<strong>Error:</strong> El Tipo de Plancha <u>'.$Nombre.'</u> ya existe en el sistema.
									Verifique el nombre a ingresar.';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'TIPOS DE PLANCHA',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);

		//Modelo que realiza la busqueda de todos los tipos de planchas.
		$this->load->model('planchas/planchas_m', 'planchas');
		
		$Variables['plancha_tipo'] = $this->planchas->plancha_tipo();
		
		//Cargamos la vista
		$this->load->view('planchas/plancha_tipo_v', $Variables);
		
		
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


	public function eliminar($Cod_Tipo = '')
	{

		$Cod_Tipo += 0;

		$Consulta = '
			delete from plancha_tipo
			where cod_tipo = "'.$Cod_Tipo.'"
		';
		$this->db->query($Consulta);

		header('location: /planchas/plancha_tipo');
		exit();

	}

}

/* Fin del archivo */