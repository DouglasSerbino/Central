<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_agr extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($dia = '', $mes = '', $anho = '')
	{
		//Departamentos que tiene acceso a esta area.
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		
		$this->ver_sesion_m->no_clientes();
		
		//Pequenha validacion
		$dia += 0;
		$mes += 0;
		$anho += 0;
		//Si el resultado de la suma anterio es igual a cero, significa que el valor
		//recibido era una cadena de letras
		if(0 == $dia or 0 == $mes or 0 == $anho)
		{
			//Muestro un mensaje de error
			show_404();
			//Evito que se siga ejecutando el script
			exit();
		}
		
		if($dia < 10)
		{
			$dia = '0'.$dia;
		}
		if($mes < 10)
		{
			$mes = '0'.$mes;
		}
		
		$fecha = $anho.'-'.$mes.'-'.$dia;
		$tipo = 'todos';
		$id_usuario = '';
		//Cargamos el modelo para poder mostrar la informacion.
		$this->load->model('extras/extra_agr_m', 'ext_agr');
		//Listado de usuarios a los que se les pueden agregar extras.
		$usuarios = $this->ext_agr->Usuarios();
		//Horas extras por usuario
		$buscar_extras = $this->ext_agr->Buscar_extras($tipo, $fecha, $id_usuario);
		
		//Cargamos el modelo para mostra la persona que ingreso las extras.
		$this->load->model('extras/extra_rep_m', 'ext_rep');
		$Administradores = $this->ext_rep->mostrar_admon($buscar_extras);
			
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Requerimiento de Horas Extras',
				'Mensaje' => '',
				'dia' => $dia,
				'mes' => $mes,
				'anho' => $anho,
				'Usuarios' => $usuarios,
				'buscar_extras' => $buscar_extras,
				'Administradores' => $Administradores
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('extras/extra_agr_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
	
	
	/*
		Funcion que servira para eliminar las horas extras.
	*/
	function eliminar_extras($id_extra, $dia, $mes, $anho)
	{
		//Un cliente no debera tener acceso a esta informacion.
		$this->ver_sesion_m->no_clientes();
		
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
			$this->load->model('extras/extra_eli_m', 'ext_eli');
			$Eliminar = $this->ext_eli->eliminar_horas_extras($id_extra, $dia, $mes, $anho);

			if($Eliminar == 'ok')
			{
				header("location: /extras/extra_agr/index/$dia/$mes/$anho");
			}
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
	
	
	/*
		Funcion que servira para agregar el flete a las horas extras.
	*/
	function agregar_flete($id_extra, $dia, $mes, $anho)
	{
		//Los clientes no deben de tener acceso a este controlador.
		$this->ver_sesion_m->no_clientes();
		//Cargamos el modelo
		$this->load->model('extras/extra_agr_m', 'ext_flete');
		$Agregar_flete = $this->ext_flete->flete_agr($id_extra);
		if($Agregar_flete == 'ok')
		{
			header("location: /extras/extra_agr/index/$dia/$mes/$anho");
		}
	}
}

/* Fin del archivo */