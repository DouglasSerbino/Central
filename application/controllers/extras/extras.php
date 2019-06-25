<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extras extends CI_Controller {
	
	/**
	 *Muestra el calendario para poder ingresar las horas extras.
	*/
	public function index($anho = '', $mes = '')
	{
		/*
		 *Determinar que departamentos tienen acceso a este controlador.
		*/
		$Permitido = array('Gerencia' => '', 'Plani' => '', 'Sistemas' => '', 'SAP' => '');
		$this->ver_sesion_m->acceso($Permitido);
		//Los clientes no deben de tener acceso.
		$this->ver_sesion_m->no_clientes();
		
		//Verificamos si la persona que quiere acceder se ha auntenticado
		//Como un usuario que tiene acceso a esta area.
		if($this->session->userdata('contra_ok') == 'ok')
		{
			if($anho == '' or $mes == '')
			{
				$anho = date('Y');
				$mes = date('m');
			}
			
			//Comprobamos cuantos dias tiene febrero.
			if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0))
			{
				$dias_febrero = 29;
			}
			else
			{
				$dias_febrero = 28;
			}
			
			$dias_v = array(0,31,$dias_febrero,31,30,31,30,31,31,30,31,30,31);
			
			//Cuantos dias tiene el mes seleccionado?
			$mes_d = number_format($mes, 0);
			$dias_mes = $dias_v[$mes_d];
			
			//Cargamos el modelo para poder realizar la verificacion.
			$this->load->model('extras/extras_m', 'extras');
			$Verificacion = $this->extras->buscar_extras($dias_mes, $anho, $mes);
			
			$Proyecciones = $this->extras->proyecciones($anho, $mes);
			
			//print_r($Proyecciones);
			
			//Variables necesarias en el encabezado
			$Variables = array(
				'Titulo_Pagina' => 'Horas Extras',
				'Mensaje' => '',
				'anho' => $anho,
				'mes' => $mes,
				'dias_mes' => $dias_mes,
				'dias_extras' => $Verificacion,
				'proyecciones' => $Proyecciones
			);
			
			//Se carga el encabezado de pagina
			$this->load->view('encabezado_v', $Variables);
			//Se carga la vista.
			$this->load->view('extras/extras_v', $Variables);
			//Se carga el pie de pagina
			$this->load->view('pie_v');
		}
		else
		{
			header('location: /extras/extra_con');
		}
	}
	
	
	
	
	/**
	 *Permitira validar si el usuario que quiere ingresar existe.
	 *@param string $Contrasenha;
	 *@return nada.
	*/
	public function verificar_con()
	{
		
		$this->ver_sesion_m->no_clientes();
		
		//Limpiamos la variable.
		$Contrasenha = $this->seguridad_m->mysql_seguro(
			$this->input->post('contrasena')
		);
		
		//Cargamos el modelo para poder realizar la verificacion.
		$this->load->model('extras/extras_m', 'extras');
		$Verificacion = $this->extras->verificar_contra($Contrasenha);
		
		
		if('ok' == $Verificacion)
		{//Si la contrasenha es correcta.
			//Creamos la session.
			$ok = 'ok';
			$this->session->set_userdata(array('contra_ok' => $ok));
			//Lo dirigimos a la pagina de extras.
			header('location: /extras/extras');
			//Evitamos que se continue ejecutando el script
			exit();
		}
		else
		{//Ocurrio un error al intentar ingresar.
			//Mandamos un mensaje de error al usuario.
			header('location: /extras/extra_con/index/error');
			//Evitamos que se continue ejecutando el script
			exit();
		}
	}
}

/* Fin del archivo */