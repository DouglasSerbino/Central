<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tareas extends CI_Controller {
	
	/**
	 *Pagina que muestra las ventas por linea
	*/
	public function index($Ver = '', $Pagina = 1, $Inicio = 0)
	{
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', '410' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$Inicio += 0;
		if(0 > $Inicio)
		{
			$Inicio = 0;
		}
		
		$Pagina += 0;
		if(1 > $Pagina)
		{
			$Pagina = 1;
		}
		
		//Asignacion de variables para que sean accesibles desde a vista.
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Tareas',
			'Mensaje' => '',
			'Ver' => $this->seguridad_m->mysql_seguro($Ver)
		);
		
		
		//Busqueda de las tareas incompletas y finalizadas
		$this->load->model('ventas/tarea_m', 'tarea');
		$Variables['Incompletas'] = $this->tarea->listar_incompletas();
		$Variables['Finalizadas'] = $this->tarea->listar_finalizadas($Inicio);
		$Tt_Tareas = $this->tarea->finalizadas();
		//print_r($Variables['Finalizadas']);
		
		//Carga del modelo para la paginacion
		$this->load->model('utilidades/paginacion_m', 'paginacion');
		$Variables['Paginacion'] = $this->paginacion->paginar(
			'/ventas/tareas/index/fina/',
			$Tt_Tareas,
			50,
			$Pagina
		);
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		
		//
		$this->load->view('ventas/preingreso_roto_tarea_v', $Variables);
		
		
		//Cargamos la vista
		$this->load->view('ventas/tareas_v', $Variables);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
		
	}
	
	
	
	
	/**
	 *
	*/
	public function finalizar()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '', '410' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		
		$Id_Tarea = $this->input->post('finta_id_tarea');
		$Id_Tarea += 0;
		
		if(0 < $Id_Tarea)
		{
			
			$Comentario = $this->seguridad_m->mysql_seguro(
				$this->input->post('finta_comentario')
			);
			
			$this->load->model('ventas/tarea_m', 'tarea');
			$this->tarea->finalizar($Id_Tarea, $Comentario);
			
		}
		
		
		header('location: /ventas/tareas');
		
	}
	
}

/* Fin del archivo */