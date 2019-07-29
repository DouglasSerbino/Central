<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra_rep_dep extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($id_dpto, $mes, $anho, $tipo)
	{
		/*
		 *Determinamos los departamentos que tendran acceso a este reporte.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deberan tener acceso a este reporte.
		$this->ver_sesion_m->no_clientes();
		
		if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0))
		{
			$dias_febrero = 29;
		}
		else
		{
			$dias_febrero = 28;
		}
		
		$dias_v = array(0,31,$dias_febrero,31,30,31,30,31,31,30,31,30,31);
		
		//Que dia de la semana inicia el mes?
		$dia_inicio = date("w", mktime(0, 0, 0, $mes, 1, $anho));
		//Cuantos dias tiene el mes seleccionado?
		$mes_d = number_format($mes, 0);
		$dias_mes = $dias_v[$mes_d];
		
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extra_rep_dep_m', 'extra_dep');
		$Departamento = $this->extra_dep->mostrar_dpto($id_dpto);
		$Proyecciones = $this->extra_dep->mostrar_proyeccion($id_dpto, $mes, $anho);
		$Usuarios = $this->extra_dep->mostrar_usuarios($id_dpto, $mes, $anho);
			
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
		
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Reporte de Horas Extras por Departamento',
				'Mensaje' => '',
				'departamento' => $Departamento,
				'proyecciones' => $Proyecciones,
				'usuarios' => $Usuarios,
				'mes' => $mes,
				'anho' => $anho,
				'tipo' => $tipo,
				'dias_mes' => $dias_mes
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('extras/extra_rep_dep_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
}

/* Fin del archivo */