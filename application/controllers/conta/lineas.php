<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lineas extends CI_Controller {
	
	//********************************************************
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		if('ok' == $Mensaje)
		{
			$Mensaje = 'L&iacute;nea ingresada exitosamente.';
		}
		if('error' == $Mensaje)
		{
			$Mensaje = 'Ocurri&oacute; un error en el ingreso, favor intentar nuevamente.<br />Se ha creado un registro del error para buscar una soluci&oacute;n';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Administraci&oacute;n de L&iacute;nea',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//Obtener las lineas ya almacenadas
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$Variables['Lineas'] = $this->mc_lineas->listar();
		
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('conta/lineas_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	//********************************************************
	public function agregar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$MC_Codigo = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_codigo')
		);
		$MC_Linea = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_linea')
		);
		$MC_Padre = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_nivel')
		);
		$MC_Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_tipo')
		);
		
		
		$this->load->model('conta/lineas_m', 'mc_lineas');
		
		$Ingreso = $this->mc_lineas->agregar(
			$MC_Codigo,
			$MC_Linea,
			$MC_Padre,
			$MC_Tipo
		);
		
		header('location: /conta/lineas');
		exit();
		
	}
	
	
	//********************************************************
	public function eliminar($Id_Mc_Linea = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_Mc_Linea += 0;
		if(0 == $Id_Mc_Linea)
		{
			show_error();
			exit();
		}
		
		
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$this->mc_lineas->Eliminar($Id_Mc_Linea);
		
		
		header('location: /conta/lineas');
		exit();
		
	}
	
	
	//********************************************************
	public function modificar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$MC_ID_Linea = $this->input->post('mc_modificar_id');
		$MC_ID_Linea += 0;
		$MC_Codigo = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_modificar_codigo')
		);
		$MC_Linea = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_modificar_linea')
		);
		$MC_Padre = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_modificar_nivel')
		);
		$MC_Tipo = $this->seguridad_m->mysql_seguro(
			$this->input->post('mc_modificar_tipo')
		);
		
		
		$this->load->model('conta/lineas_m', 'mc_lineas');
		
		$Ingreso = $this->mc_lineas->modificar(
			$MC_ID_Linea,
			$MC_Codigo,
			$MC_Linea,
			$MC_Padre,
			$MC_Tipo
		);
		
		header('location: /conta/lineas');
		exit();
		
	}
	
}

/* Fin del archivo */