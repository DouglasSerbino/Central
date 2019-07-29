<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listado_procesos extends CI_Controller {
	
	/**
	 *Listado de los procesos que pertenecen a cada cliente
	 *@return nada.
	*/
	public function index($Id_Cliente = '', $Pagina = 1, $Inicio = 0)
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Id_cliente= $this->seguridad_m->mysql_seguro($Id_Cliente);
		$Pagina += 0;
		$Inicio += 0;
		$Mensaje = '';
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Listado de Procesos por Cliente',
			'Mensaje' => $Mensaje,
			'Paginacion' => '',
			'Id_Cliente' => $Id_Cliente
		);
		
		if($Id_Cliente != '')
		{
			
			//Carga del modelo que permite mostrar los datos.
			$this->load->model('procesos/buscar_proceso_m', 'buscar_proc');
			
			//Extraemos todos los proceso
			$Info_procesos = $this->buscar_proc->listado_procesos($Id_cliente, $Inicio);
			
			
			//Total de procesos para este cliente
			$Tt_procesos = $this->buscar_proc->total_procesos($Id_cliente);
			
			
			//Carga del modelo para la paginacion
			$this->load->model('utilidades/paginacion_m', 'paginacion');
			$Variables['Paginacion'] = $this->paginacion->paginar(
				'/procesos/listado_procesos/index/'.$Id_cliente.'/',
				$Tt_procesos,
				50,
				$Pagina
			);
			
			
			
			if(0 == $Info_procesos)
			{
				$Mensaje = 'El cliente seleccionado no tiene procesos.';
			}
		}
		else
		{
			$Info_procesos = '';
		}
		
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Cargamos el modelo que muestra los clientes.
		$this->load->model('clientes/busquedad_clientes_m', 'buscar_cli');
		
		//Llamamos el modelo para poder almacenar los datos.
		$mostrar_clientes = $this->buscar_cli->mostrar_clientes();
		
		//Variables necesarias en la vista.
		$Variables_vista = array(
			'Clientes' => $mostrar_clientes,
			'Informacion_proc' => $Info_procesos
		);
		
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('procesos/listado_procesos_v', $Variables_vista);
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');	
	}
	
}

/* Fin del archivo */