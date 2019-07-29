<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Movimientos2 extends CI_Controller {
	
	//********************************************************
	public function index($Id_Linea = 'todos', $Pagina = 1, $Inicio = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		$Pagina += 0;
		$Inicio += 0;
		if('todos' != $Id_Linea)
		{
			$Id_Linea += 0;
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Movimientos',
			'Mensaje' => '',
			'Pagina' => $Pagina,
			'Inicio' => $Inicio,
			'Id_Linea' => $Id_Linea
		);
		
		
		
		//Obtener las lineas ya almacenadas
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$Variables['Lineas'] = $this->mc_lineas->listar();
		
		
		//Obtener los movimientos
		$this->load->model('conta/movimientos_m', 'mc_movimientos');
		$Variables['Movimientos'] = $this->mc_movimientos->listar('s', $Inicio, $Id_Linea);
		
		$Total_Movimientos = $this->mc_movimientos->total('s', $Id_Linea);
		
		//Carga del modelo para la paginacion
		$this->load->model('utilidades/paginacion_m', 'paginacion');
		$Variables['Paginacion'] = $this->paginacion->paginar(
			'/conta/movimientos/index/'.$Id_Linea.'/',
			$Total_Movimientos,
			60,
			$Pagina
		);
		
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('conta/movimientos2_v', $Variables);
		
		
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
		$Movimientos = array();
		$Contador = 0;
		for($i = 0; $i < 5; $i++)
		{
			if('' != $this->input->post('mov_fecha_'.$i))
			{
				$Movimientos[$Contador]['mov_fecha'] = $this->input->post('mov_fecha_'.$i);
				$Movimientos[$Contador]['mov_fecha'] = date(
					'Y-m-d',
					strtotime($Movimientos[$Contador]['mov_fecha'])
				);
				$Movimientos[$Contador]['mov_descripcion'] = $this->input->post('mov_descripcion_'.$i);
				$Movimientos[$Contador]['mov_linea'] = $this->input->post('mov_linea_'.$i);
				$Movimientos[$Contador]['mov_cantidad'] = $this->input->post('mov_cantidad_'.$i);
				$Movimientos[$Contador]['mov_precio'] = $this->input->post('mov_precio_'.$i);
				$Movimientos[$Contador]['mov_total'] = $this->input->post('mov_total_'.$i);
				$Contador++;
			}
		}
		
		
		$this->load->model('conta/movimientos_m', 'mc_movimientos');
		
		$this->mc_movimientos->agregar($Movimientos);
		
		header('location: /conta/movimientos');
		exit();
		
	}
	
	
	//********************************************************
	public function eliminar($Id_Mc_Movimiento = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpieza de variables
		$Id_Mc_Movimiento += 0;
		if(0 == $Id_Mc_Movimiento)
		{
			show_error();
			exit();
		}
		
		
		$this->load->model('conta/movimientos_m', 'mc_movimientos');
		$this->mc_movimientos->Eliminar($Id_Mc_Movimiento);
		
		
		header('location: /conta/movimientos');
		exit();
		
	}
	
	
	
	
	public function detalle($Id_Linea = 0, $Anho = '', $Mes = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido, true);
		
		$this->ver_sesion_m->no_clientes();
		
		$Id_Linea += 0;
		$Anho += 0;
		
		if('anual' == $Mes)
		{
			$Mes = '01';
		}
		
		if(0 == ($Mes + 0))
		{
			show_404();
		}
		
		if(0 == $Anho)
		{
			$Anho = date('Y');
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Detalle de Movimientos',
			'Mensaje' => '',
			'Mes' => $Mes,
			'Anho' => $Anho,
			'Id_Linea' => $Id_Linea,
			'Meses_v' => $this->Meses_m->MandarMesesCompletos()
		);
		
		
		$Anho_Mes = $Anho.'-'.$Mes;
		
		
		//Obtener las lineas ya almacenadas
		$this->load->model('conta/lineas_m', 'mc_lineas');
		$Variables['Lineas'] = $this->mc_lineas->listar();
		
		
		//Obtener los movimientos
		$this->load->model('conta/movimientos_m', 'mc_movimientos');
		$Variables['Movimientos'] = $this->mc_movimientos->listar('s', 0, $Id_Linea, false, $Anho_Mes);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario que toma los datos necesarios para la creacion del menu
		$this->load->view('conta/movimientos_detalle_v', $Variables);
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
		
	}
	
	
}

/* Fin del archivo */