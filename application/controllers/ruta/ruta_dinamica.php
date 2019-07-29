<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ruta_dinamica extends CI_Controller {
	
	
	//***********************************************************
	public function listar()
	{

		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Administrar Rutas de Trabajo',
			'Mensaje' => ''
		);


		$this->load->model('ruta/ruta_dinamica_m', 'ruta');
		$Variables['Listado'] = $this->ruta->listar();
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Se carga el encabezado de pagina
		$this->load->view('ruta/listar_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	


	//***********************************************************
	public function crear()
	{

		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Crear Ruta de Trabajo',
			'Mensaje' => ''
		);

		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		//Busqueda del listado
		$Variables['Clientes'] = $this->clientes->mostrar_clientes();
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Se carga el encabezado de pagina
		$this->load->view('ruta/ruta_crear_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	


	//***********************************************************
	public function almacenar()
	{

		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		$Ruta_Texto = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_texto')
		);
		$Ruta_Texto = str_replace('&quot;', '"', $Ruta_Texto);
		$Ruta_Texto = str_replace('px', '', $Ruta_Texto);


		$Ruta_Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_nombre')
		);

		$Elemento = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_elemento')
		);

		$Id_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_cliente')
		);
		
		
		$this->load->model('ruta/ruta_dinamica_m', 'ruta');
		$Almacenar = $this->ruta->almacenar($Ruta_Texto, $Elemento, $Id_Cliente, $Ruta_Nombre);


		if('ok' == $Almacenar)
		{
			header('location: /ruta/ruta_dinamica/listar');
		}
		else
		{
			echo 'Ocurrio un error.';
		}


	}
	


	//***********************************************************
	public function modificar($Id_Ruta = 0)
	{

		$Id_Ruta = (int)$Id_Ruta + 0;
		if(0 == $Id_Ruta)
		{
			show_404();
			exit();
		}


		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Modificar Ruta de Trabajo',
			'Mensaje' => '',
			'Id_Ruta' => $Id_Ruta
		);


		$this->load->model('ruta/ruta_dinamica_m', 'ruta');
		$Variables['Ruta'] = $this->ruta->obtener($Id_Ruta);
		//print_r($Variables['Ruta']); exit();


		//Listado de los clientes de este grupo
		$this->load->model('clientes/busquedad_clientes_m', 'clientes');
		//Busqueda del listado
		$Variables['Clientes'] = $this->clientes->mostrar_clientes();

		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Se carga el encabezado de pagina
		$this->load->view('ruta/ruta_modificar_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	


	//***********************************************************
	public function actualizar()
	{

		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		$Id_Ruta = $this->seguridad_m->mysql_seguro(
			$this->input->post('id_ruta')
		);


		$Ruta_Nombre = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_nombre')
		);

		$Ruta_Texto = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_texto')
		);
		$Ruta_Texto = str_replace('&quot;', '"', $Ruta_Texto);

		$Elemento = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_elemento')
		);

		$Id_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('ruta_cliente')
		);


		$this->load->model('ruta/ruta_dinamica_m', 'ruta');
		
		$this->ruta->eliminar($Id_Ruta);

		$Almacenar = $this->ruta->almacenar($Ruta_Texto, $Elemento, $Id_Cliente, $Ruta_Nombre);


		if('ok' == $Almacenar)
		{
			header('location: /ruta/ruta_dinamica/listar');
		}
		else
		{
			echo 'Ocurrio un error.';
		}


	}
	


	//***********************************************************
	public function eliminar($Id_Ruta)
	{

		$Permitido = array('Gerencia' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();

		$Id_Ruta = $this->seguridad_m->mysql_seguro($Id_Ruta);


		$this->load->model('ruta/ruta_dinamica_m', 'ruta');
		$this->ruta->eliminar($Id_Ruta);
		
		
		header('location: /ruta/ruta_dinamica/listar');


	}
	

}

/* Fin del archivo */