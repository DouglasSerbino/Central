<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crear_procesos extends CI_Controller {
	
	/**
	 *Pagina que permite ingresar los procesos
	 *@return nada.
	*/
	public function index($Mensaje = '')
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		if($Mensaje == 'ok')
		{
			$Mensaje = 'El proceso fue agregado exitosamente';
		}
		elseif($Mensaje == 'ex')
		{
			$Mensaje = 'El numero de proceso ingresado ya existe. <Br /> Por favor revise la informacion ingresada.';
		}
		elseif($Mensaje == 'error')
		{
			$Mensaje = 'Ocurri&oacute; un error en el ingreso.';
		}
		else
		{
			$Mensaje = '';
		}
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Crear Procesos',
			'Mensaje' => $Mensaje
		);
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
	
		//Pagina que muestra el listado de las unidades existentes y su enlace de
		//ingreso, se adjunta las variables que necesita
		$this->load->view('procesos/crear_procesos_v');
		
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');	
	}
	
	public function crear()
	{
		
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos las variables
		$Id_Cliente = $this->seguridad_m->mysql_seguro(
			$this->input->post('cliente')
		);
		
		$Proceso = $this->seguridad_m->mysql_seguro(
			$this->input->post('proceso')
		);
		
		$Producto= $this->seguridad_m->mysql_seguro(
			$this->input->post('producto')
		);
		
		//Carga del modelo que permite ingresar los datos.
		//crea_proc = Crear_procesos_m
		$this->load->model('procesos/crear_procesos_m', 'crea_proc');
		$this->load->model('utilidades/crear_carpetas','creacion_carp');
		
		
		//Llamamos el modelo para poder almacenar los datos.
		$Id_Proceso = $this->crea_proc->guardar_proceso(
			$Id_Cliente,
			$Proceso,
			$Producto,
			$this->session->userdata('id_grupo')
		);
		
		
		if('existe' == $Id_Proceso)
		{
			//Si el proceso ya existe se notifica.
			header('location: /procesos/crear_procesos/index/ex/');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		
		$Id_Proceso += 0;
		if(0 == $Id_Proceso)
		{//Ocurrio un error al intentar ingresar el grupo.
			//Regresamos a la pagina para ingresar los datos nuevamente
			header('location: /procesos/crear_procesos/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		
		//Creamos la carpeta para el proceso recien creado.
		$this->creacion_carp->creacion_carpetas('', $Id_Proceso);
		
		//Dirigimos a la pagina de crear pedido, por algo crearon el proceso :)
		header('location: /pedidos/agregar/index/'.$Id_Proceso);
		//Evitamos que se continue ejecutando el script
		exit();
		
	}
	
}

/* Fin del archivo */