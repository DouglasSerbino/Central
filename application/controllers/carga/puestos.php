<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Puestos extends CI_Controller {
	
	
	public function index()
	{
		/*
		 *Determinamos los dpeartamentos que tendran acceso a esta pagina
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'Ventas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		/*
		 *Validamos que ningun cliente tenga acceso a esta pagina.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Establecmos el año y mes actual
		$Anho = date('Y');
		$Mes = date('m');
		//Si recibimos datos por $_POST asignamos los nuevos valores.
		if('' != $this->input->post('anho'))
		{
			
			$Anho = $this->seguridad_m->mysql_seguro($this->input->post('anho'));
			$Mes = $this->seguridad_m->mysql_seguro($this->input->post('mes'));
		}
		
		
		//Variables necesarias en el encabezado
		$Variables = array(
			'Titulo_Pagina' => 'Reporte por Puestos',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes
		);
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
		
		
		//Cargamos el model
		$this->load->model('usuarios/listado_usuario_m', 'lusus');
		//Listado de los usuarios
		$Variables['Usuarios'] = $this->lusus->departamento_usuario('', false);
		
		//Cuantos trabajos procesaron estos usuarios?
		$this->load->model('carga/trabajos_usuario_m', 'trab_usu');
		$Variables['Trabajos'] = $this->trab_usu->total_trabajos($Anho, $Mes);
		//Numero de rechazos por operador
		$Variables['Rechazos'] = $this->trab_usu->total_rechazos($Anho, $Mes, $Variables['Trabajos']);
		//Tiempo programado por operador
		$Variables['TProgramado'] = $this->trab_usu->tiempo_progr($Anho, $Mes);
		//Tiempo utilizado por operador.
		$Variables['TUtilizado'] = $this->trab_usu->tiempo_utili($Anho, $Mes);
		//Tiempo habil para cada operador.
		//El mes posee 133 horas habiles (un 70% del real) y eso por 60 minutos = 
		$Variables['THabil'] = 7980;
	
		
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/puesto_v');
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
	
	
	
	public function usuario($Id_Usuario = 0, $Anho = '', $Mes = '')
	{
		/*
		 *Verificamos que ningun cliente tenga acceso a esta pagina.
		*/
		$this->ver_sesion_m->no_clientes();
		
		//Validamos todas la variables.
		$Id_Usuario += 0;
		$Anho = $this->seguridad_m->mysql_seguro($Anho);
		$Mes = $this->seguridad_m->mysql_seguro($Mes);
		
		if(0 == $Id_Usuario)
		{
			show_404();
			exit();
		}
		
		
		//Variables necesarias para el encabezado.
		$Variables = array(
			'Titulo_Pagina' => 'Reporte por Puestos',
			'Mensaje' => '',
			'Anho' => $Anho,
			'Mes' => $Mes,
			'Id_Usuario' => $Id_Usuario
		);
		
		$Variables['Meses'] = $this->Meses_m->MandarMesesCompletos();
		
		//Cargamos el modelo
		$this->load->model('carga/trabajos_usuario_m', 'trab_usu');
		//Total de trabajos por operador
		$Variables['Trabajos'] = $this->trab_usu->total_trabajos($Anho, $Mes, $Id_Usuario);
		//Total de rechazos por operador
		$Variables['Rechazos'] = $this->trab_usu->total_rechazos($Anho, $Mes, $Variables['Trabajos'], $Id_Usuario);
		//Tiempo programado por operador
		$Variables['TProgramado'] = $this->trab_usu->tiempo_progr($Anho, $Mes, $Id_Usuario);
		//Tiempo utilizado por operador.
		$Variables['TUtilizado'] = $this->trab_usu->tiempo_utili($Anho, $Mes, $Id_Usuario);
		
		//Asignamos el listado de los trabajos por operador.
		$Variables = $Variables + $this->trab_usu->listado_trabajos($Id_Usuario, $Anho, $Mes);
		
		//El mes posee 133 horas habiles (un 70% del real) y eso por 60 minutos = 
		$Variables['THabil'] = 7980;
		
		//Cargamos el modelo.
		$this->load->model('usuarios/informacion_usuario_m', 'info_u');
		//Captura de la informacion del usuario.
		$Variables['Usuario'] = $this->info_u->datos($Id_Usuario, true);
		
		//Cargamos el modelo
		$this->load->model('pedidos/rechazo_usuario_m', 'rech_usu');
		//Captura de la informacion de los rechazos por operador.
		$Variables['ListRech'] = $this->rech_usu->listado($Id_Usuario, $Anho, $Mes);
		
	
		//Se carga el encabezado de pagina
		$this->load->view('encabezado_v', $Variables);
		
		//Formulario de busqueda de proceso a ingresar
		$this->load->view('carga/puesto_usuario_v');
		
		//Se carga el pie de pagina
		$this->load->view('pie_v');
	}
	
}

/* Fin del archivo */